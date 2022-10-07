<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/Constantes.php';

require_once './clases/Transaccion.php';

require_once './clases/Flujo.php';
require_once './clases/Perfil.php';

require_once 'clases/GeneradorProtocolo.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Transaccion();
	$ce = new ControladorEnsayoEficacia();
	$constg = new Constantes();
	$identificador= $_SESSION['usuario'];
	$opcion_llamada = $_POST['opcion_llamada'];

	$datoMensaje=array();

	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
	$perfil=new Perfil($perfiles);

	$id_documento= $_POST['id_documento'];
	$datoProtocolo['id_protocolo']=$id_documento;
	$id_tramite= $_POST['id_tramite'];
	$id_tramite_flujo=$_POST['id_tramite_flujo'];
	$id_flujo=$_POST['id_flujo'];
	if($id_tramite_flujo>0){
		$tramiteFlujo=$ce->obtenerTramiteEE($conexion,$id_tramite_flujo);
		$id_tramite=$tramiteFlujo['id_tramite'];
		$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
		$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
	}
	$esta_procesado=false;
	$generarProtocolo=false;
	$esProtocoloAprobado='';

	$pendiente='S';
	$condicion='';
	$observacion='';
	$ident='';
	$ident_ejecutor=$tramiteFlujo['ejecutor'];
	$reasignar_tramite=false;
	$fecha=new DateTime();
	$fecha = $fecha->format('Y-m-d');
	

	$retraso=trim(htmlspecialchars ($_POST['retraso'],ENT_NOQUOTES,'UTF-8'));
	$plazo=0;

	switch($opcion_llamada){

		case 'guardarAsignacionProtocolo':
			$ident=$_POST['tecnico'];
			$ident_ejecutor=$ident;
			$flujo=$flujoAnterior;
			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
			$observacion='Protocolo asignado';
			$esta_procesado=true;
			$reasignar_tramite=true;
			break;

		case 'guardarObservacionesSolicitud':
			$tipo_documento=$flujoAnterior->TipoDocumento();
			$observacion=$_POST['observacion'];
			$datos=array();
			$pendiente='A';	//Aprobado si no hay observaciones
			//obtengo todas las observaciones realizadas
			foreach($_POST as $key=>$item){
				if(substr($key,0,7)=='obs_EP_'){
					$formato=$ce->obtenerFormatoDelElemeno($conexion,$tipo_documento,substr($key,7));
					//miro si ya tiene observaciones a este punto
					$doc=$ce->obtenerObservacionDelTramite($conexion,$id_tramite_flujo,$formato['id_enlace']);
					$revision=1;
					if(sizeof($doc)>0){
						$revision=$doc['revision'];
						$revision++;
					}
					//agrego la observaciones pendienes a este punto de estado [S]
					$datos=$ce->agregarObservacionAlTramite($conexion,$id_tramite_flujo,$formato['id_enlace'],$item,$revision,'S');
					$pendiente='O';	//Al menos una observacion y el tramite es observador
				}
			}

			//*************** DISTRIBUYO EL TRAMITE ********
			$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
			//*************** Plazo para completar la siguiente fase ********
			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

			if($perfil->EsAnalistaCentral() || $perfil->EsAnalistaDistrital()){
				$ident=$flujo->PerfilActual();		//Envia al director de RIA
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
				$esta_procesado=true;
			}

			break;

		case 'guardarSubsanacionesSolicitud':
		   $tipo_documento=$flujoAnterior->TipoDocumento();
		   $datos=array();
			$observacion='Observaciones subsanadas: ';
			
		   foreach($_POST as $key=>$item){
		      if(substr($key,0,7)=="subsan_"){
		         //obtengo los parametros del campo recuperado
		         $formato=$ce->obtenerFormatoDelElemeno($conexion,$tipo_documento,substr($key,7));
					if($formato==null)
						continue;

					//Guardo los items modificados en las observaciones
					if($formato['campo']==null || trim($formato['campo'])==''){	//Verifica si son directamente para guardar en campos
						//miro que tipo de elemento hay que procesar
					}
					else{
						$datoProtocolo[$formato['campo']]=$item;
						$generarProtocolo=true;
					}

		         //obtengo las observaciones pendienes a este punto de estado [S]
					$doc=$ce->obtenerObservacionesDelDocumentoPorEnlace($conexion,$id_documento,$tipo_documento,$formato['id_enlace'],'S');
		         $revision=1;
		         if(sizeof($doc)>0){
		            foreach($doc as $k=>$v){
		               //corrige todas observaciones de este punto pendienes como subsanadas estado ya no pendienes [N]
		               $ce->actualizarObservacionTramiteEstado($conexion,$v['id_tramite_observacion'],'N');
		               $observacion=$observacion.'; '.$item;
		            }
		         }
					
		      }
				
		   }

			if($generarProtocolo){
				if(count($datoProtocolo)>1)
					$ce -> guardarProtocolo($conexion,$datoProtocolo);
			}

			$doc=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento,'S');
			$esta_procesado=true;
			

		   $flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
		   //*************** Plazo para completar la siguiente fase ********
		   $fechaSubsanacion=new DateTime();
		   $plazo=$flujo->Plazo();
		   $fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
		   $fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
		   //*************** RETORNO EL TRAMITE Al tecnico QUIEN le ENVIÓ ********
			$pendiente=$tramiteFlujo['pendiente'];
		   $ident=$ident_ejecutor;

		   break;

		case 'guardarAprobacionesSolicitud':
			$tipo_documento=$flujoAnterior->TipoDocumento();

			$observacion=$_POST['observacion'];
			$condicion=$_POST['condicion'];
			if($condicion==''){
				if($tramiteFlujo['pendiente']!='A')
					$condicion='C_D_O';
			}

			$fechaActual=new DateTime();

			//*************** DISTRIBUYO EL TRAMITE o EMPIEZO CON LA EJECUCION DEL ENSAYO ********
			if(($perfil->EsCoordinador()) && ($condicion==''))
				$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,'IF');
			else
				$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);

			//*************** Plazo para completar la siguiente fase ********

			$fechaSubsanacion=new DateTime();
			$plazo=$flujo->Plazo();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

			$pendiente=$tramiteFlujo['pendiente'];

			//limita las aprobaciones a los 3 perfiles
			if($perfil->EsDirector() || $perfil->EsDirectorTipoA() || $perfil->EsCoordinador()){
				if($condicion=='C_I_O'){					//Se retorna al técnico que hizo la observación
					if($perfil->EsCoordinador())
						$ident=$flujoAnterior->PerfilSiguiente();
					else
						$ident=$tramiteFlujo['remitente'];	//tramie a quien lo envio
				
					$fechaSubsanacion=new DateTime();
					$plazo=$flujo->PlazoExtendido();
					$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
					$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
				}
				else{
					if($perfil->EsDirector()){					//Cuando esta en la fase del director RIA
						$ident=$flujoAnterior->PerfilSiguiente();		//tramie a su inmediato superior
						
					}
					else {	//Es director distrital o coordinador tramite al operador
						if($condicion=='C_D_O'){				//necesita ser subsanado
							$ident=$tramiteFlujo['operador'];
							
							$datoMensaje['resultado']='O';
							$asuntoCorreo='Protocolo Observado';
						}
						else{			//Protocolo aprobado
							if($perfil->EsDirectorTipoA())
								$ident=$tramiteFlujo['operador'];		//tramite al operador para que elija el Organismo de inspeccion
							else
								$ident=$flujoAnterior->PerfilSiguiente();		//trámite según flujo al responsable para designe a los supervisores
							$respuesta=$ce->verificarProtocoloEstado($conexion,$id_documento);
							if($respuesta['es_modificacion']!='t')
								$datoProtocolo['fecha_aprobacion']=$fecha;
							$esProtocoloAprobado='SI';
							$datoMensaje['resultado']='A';
							$asuntoCorreo='Protocolo aprobado';
							$generarProtocolo=true;
						}
					}
				}

				$esta_procesado=true;
			}
			//****************************************************

			break;

		case 'guardarAccionModificar':
			$respuesta = $ce->obtenerTramitesFlujosModificacion( $conexion,$id_documento);
			$fecha=new DateTime();
			$fecha = $fecha->format('Y-m-d');
			if(pg_num_rows($respuesta)>0){
				try{
					$conexion->Begin();
					while ($fila = pg_fetch_assoc($respuesta)){				
					   $ce->actualizarTramiteEstado($conexion,$fila['id_tramite'],"Operador envía protocolo a modificar",'N',$fecha);
					   $ce->actualizarTramiteFlujoEstado($conexion,$fila['id_tramite_flujo'],'N',$fecha,"Operador envía protocolo a modificar",$retraso);						
					   $datos=array();
					   $datos['id_protocolo']=$id_documento;
					   $datos['es_modificacion']='t';
					   $datos['fecha_solicitud']=$fecha;	
						$datos['estado']='solicitud';	
					   $ce->guardarProtocolo($conexion,$datos);				
					}
					$conexion->Commit();
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = true;
				}catch(Exception $e){
					$conexion->Rollback();
				}

			}
			
			break;
	
		case 'enviarCorreo':
			$cMail=new ControladorMail();
			$asunto = $_POST['asuntoCorreo'];
			$salidaReporte = $_POST['rutaAdjunto'];
			$cuerpoCorreo='';
			$fechaActual=new DateTime();
			$cuerpoMensaje=$ce->redactarNotificacion($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);
			$destinatario = array();
			$datos=$ce->obtenerProtocolo($conexion,$id_documento);
			array_push($destinatario, $datos['email_representante_legal']);
			$adjuntos = array();
			if($salidaReporte!=null)
				array_push($adjuntos, $rutaArchivoFirmado, $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte);

			echo IN_MSG . 'Insertar registro de envío de correo electronico.';

			$codigoModulo = 'PRG_ENSAYO_EFI';
			$tablaModulo = '';
			$idSolicitudTabla='';	

			try{
				$conexion->Begin();
				$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
				$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');

				$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);

				$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);


				$mensaje['estado'] = 'OK';
				$mensaje['mensaje'] = 'OK';
				$conexion->Commit();
			}
			catch(Exception $e){
				$conexion->Rollback();
			}
			$esta_procesado=false;
			break;
	}

	if($flujo!=null && $esta_procesado && $ident!=''){
		$datoProtocolo['estado'] = $flujo->EstadoActual();
		$datoProtocolo['id_flujo_documento'] = $flujo->Flujo_documento();
		try{
			$conexion->Begin();

			if($generarProtocolo){
				$ce -> guardarProtocolo($conexion,$datoProtocolo);
				$cGenerador=new GeneradorProtocolo();
				$tituloPrevio=$ce->generarTituloDelEnsayo($conexion, $id_documento);
				$respuesta=$cGenerador->generarProtocolo($conexion,$id_documento,$tituloPrevio,$esProtocoloAprobado);
				$datoProtocolo['ruta']=$respuesta['datos'];
			}
			$ce -> guardarProtocolo($conexion,$datoProtocolo);
			$ce->actualizarTramiteIdentificador($conexion,$id_tramite,$ident);
			if($reasignar_tramite){
				$ce->reasignarTecnicoTramite($conexion,$id_tramite,$ident);
			}
			$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fecha,null,$retraso,$pendiente,$identificador);	//Cierra la fase del tramite
			//genera otra fase del tramite para la siguiente accion
			$ce->guardarFlujoDelTramite($conexion,null,$id_tramite,$flujo->Flujo_documento(),$ident,$identificador,$ident_ejecutor,$pendiente,$fecha,$fechaSubsanacion,$observacion,$plazo);

			$conexion->Commit();
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $datoMensaje;
		}
		catch(Exception $e){
			$conexion->Rollback();
		}

	}

	$conexion->desconectar();
	echo json_encode($mensaje);

	} catch (Exception $ex) {
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error de conexión a la base de datos';
		echo json_encode($mensaje);
	}

?>