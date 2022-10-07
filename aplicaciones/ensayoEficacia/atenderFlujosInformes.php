<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';

require_once '../../clases/ControladorEnsayoEficacia.php';

require_once '../../clases/Constantes.php';

require_once './clases/Transaccion.php';

require 'clases/GeneradorProtocolo.php';
require 'clases/GeneradorProcesoEnsayos.php';


require_once './clases/Flujo.php';
require_once './clases/Perfil.php';

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
	$datosDocumento['id_informe']=$id_documento;
	$id_tramite= $_POST['id_tramite'];
	$id_tramite_flujo=$_POST['id_tramite_flujo'];
	$id_flujo=$_POST['id_flujo'];
	if($id_tramite_flujo>0){

		$tramiteFlujo=$ce->obtenerTramiteInformeEE($conexion,$id_tramite_flujo);
		$id_tramite=$tramiteFlujo['id_tramite'];
		$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
		$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
	}
	$esta_procesado=false;
	$generarInforme=false;
	$enviarCorreo=false;

	$asuntoCorreo='';

	$esInformeLegal='';
	$pendiente='S';
	$condicion='';
	$observacion='';
	$ident='';
	$ident_ejecutor=$tramiteFlujo['ejecutor'];
	$reasignar_tramite=false;
	$finTramite=false;
	$fecha=new DateTime();
	$fecha = $fecha->format('Y-m-d');
	$plazo=0;

	$adjuntos=array();
	$destinatarios=array();

	$retraso=trim(htmlspecialchars ($_POST['retraso'],ENT_NOQUOTES,'UTF-8'));

	switch($opcion_llamada){

		case 'guardarAsignacionSupervisor':

			$ident=$_POST['tecnico'];
			$zona=$_POST['zona'];
			$id_division=$_POST['id_division'];
			$flujo=$flujoAnterior;
			$datosDocumento=array();
			$datosDocumento['id_protocolo_zona'] = $_POST['id_protocolo_zona'];
			$datosDocumento['estado'] = $flujo->EstadoActual();

			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

			//crea el informe final para la zona
			try{
				$conexion->Begin();
				$resultado=$ce ->guardarInformeFinal($conexion,$datosDocumento);
				if(sizeof($resultado['resultado'])>0){

					$id_informe=$resultado['resultado'][0]['id_informe'];
					$numeroTramite=$ce ->guardarTramiteDelDocumento($conexion,$flujo->TipoDocumento(),$id_informe,$ident,$fecha,$fechaSubsanacion,'S',$id_division);
					$ce->guardarFlujoDelTramite($conexion,null,$numeroTramite,$flujo->Flujo_documento(),$ident,$identificador,$ident_ejecutor,'S',$fecha,$fechaSubsanacion);

					$conexion->Commit();
					$mensaje['mensaje'] = $zona;
					//verifica si ya fue asignado supervisor en todas las zonas
					$zonas=$ce->obtenerProtocoloZonas($conexion,$id_documento);
					//Elimina las zonas que ya tienen asignado el supervisor
					foreach($zonas as $key=>$item){
						$resultado=$ce->obtenerInformeFinal($conexion,$item['id_protocolo_zona']);
						if($resultado !=null){		//Ya existe supervisor asignado
							unset($zonas[$key]);
						}
					}
					if(sizeof($zonas)==0){		//Ya fueron asignados todos los supervisores
						$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'E',$fecha,null,$retraso);	//Cierra la fase del tramite
						$mensaje['mensaje'] = '-1';
					}
					$mensaje['estado'] = 'exito';

				}
			}
			catch(Exception $e){
				$conexion->Rollback();
			}
			break;

		case 'guardarOrganismoInspeccion':

			$tipo_documento=$_POST['tipo_documento'];
			$zona=$_POST['zonas'];
			$ident=$_POST['organismo'];
			$correo=$_POST['correo'];
			$observacion=$_POST['observacion'];
			$zonaNombre=$_POST['zonaNombre'];
			$organismoNombre=$_POST['organismoNombre'];
			$id_expediente=$_POST['id_expediente'];
			$plaguicida_nombre=$_POST['plaguicida_nombre'];
			

			$id_fase=$ce->obtenerFaseDelFlujo($conexion,$id_flujo,'elegirOrganismo');
			$query=$ce->obtenerFlujosDeTramitesProtocoloEE($conexion,$id_fase,$identificador,$id_documento);
			if(pg_num_rows($query)>0){
				$tramiteFlujo=pg_fetch_assoc($query,0);
				$id_tramite_flujo=$tramiteFlujo['id_tramite_flujo'];
				$id_tramite=$tramiteFlujo['id_tramite'];
				$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
				$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
			}
			else{
				break;
			}

			$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,'IF');
			$datosDocumento=array();
			$datosDocumento['id_protocolo_zona'] = $zona;
			$datosDocumento['estado'] = $flujo->EstadoActual();

			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');


			try{
				$conexion->Begin();

				//crea el informe final para la zona y genera nuevo trámite
				$zonaDistrital=$ce->obtenerDivisionDesdeZonaProtocolo($conexion,$zona);
				if($zonaDistrital=='')
					throw new Exception('No se ha asignado zona.');
				$resultado=$ce ->guardarInformeFinal($conexion,$datosDocumento);
				//envia correo
				$generadorProceso=new GeneradorProcesoEnsayos();
				array_push($destinatarios,$correo);		
				$generadorProceso->enviarCorreoPersonal($conexion,$ce,$organismoNombre,$id_expediente,$plaguicida_nombre,'Asignación para supervición de ensayo de eficacia',$adjuntos,$destinatarios,$observacion);

				if(sizeof($resultado['resultado'])>0){
					$id_informe=$resultado['resultado'][0]['id_informe'];

					$numeroTramite=$ce ->guardarTramiteDelDocumento($conexion,$flujo->TipoDocumento(),$id_informe,$ident,$fecha,$fechaSubsanacion,'S',$zonaDistrital);
					$ce->guardarFlujoDelTramite($conexion,null,$numeroTramite,$flujo->Flujo_documento(),$ident,$identificador,$ident_ejecutor,'S',$fecha,$fechaSubsanacion,'',$plazo);
					$mensaje['mensaje'] = $zona;
					//verifica si ya fue asignado supervisor en todas las zonas
					$zonas=$ce->obtenerProtocoloZonas($conexion,$id_documento);
					//Elimina las zonas que ya tienen asignado el supervisor
					foreach($zonas as $key=>$item){
						$resultado=$ce->obtenerInformeFinal($conexion,$item['id_protocolo_zona']);
						if($resultado !=null){		//Ya existe supervisor asignado
							unset($zonas[$key]);
						}
					}
					if(sizeof($zonas)==0){		//Ya fueron asignados todos los supervisores

						$mensaje['mensaje'] = '-1';

						//Migra flujo del protocolo
						$datoProtocolo=array();
						$datoProtocolo['id_protocolo']=$id_documento;
						$datoProtocolo['estado'] = $flujo->EstadoActual();
						$datoProtocolo['id_flujo_documento'] = $flujo->Flujo_documento();
						$ce -> guardarProtocolo($conexion,$datoProtocolo);
						$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'E',$fecha,$observacion,$retraso);	//Cierra la fase del tramite

					}
					$conexion->Commit();
					$mensaje['estado'] = 'exito';

				}
			}
			catch(Exception $e){
				$conexion->Rollback();
			}
			break;

		case 'reportarInstalacionEnsayo':
			$dato=array();
			$dato['id_informe']=$id_documento;
			$dato['tipo']='RIA-IF';
			$dato['estado']='inspeccion';
			$fecha=new DateTime();
			$fecha = $fecha->format('Y-m-d');
			$dato['fecha_instalacion']=$fecha;
			try{
				$pendiente='E';
				$conexion->Begin();
				$res=$ce->guardarInformeFinal($conexion,$dato);
				$ce->actualizarTramiteEstado($conexion,$tramiteFlujo['id_tramite'],null,$pendiente,$tramiteFlujo['fecha_fin']);
				$ce->actualizarTramiteFlujoEstado($conexion,$tramiteFlujo['id_tramite_flujo'],$pendiente,$tramiteFlujo['fecha_fin'],null,$retraso);
				$mensaje['mensaje']=$ce->obtenerInformeFinalEnsayo($conexion,$id_documento);
				$conexion->Commit();
				$mensaje['estado'] = 'exito';
			}
			catch(Exception $e){
				$conexion->Rollback();
				$mensaje['estado'] = 'fallo';
			}

			break;

		case 'notificarInformeEnsayo':
			$referencia=$_POST['referencia'];
			$path=$_POST['path'];                  			
            $protocolo=$ce->obtenerProtocoloDesdeInforme($conexion,$id_documento);
            $ce->agregarArchivoAnexo($conexion,$protocolo['id_protocolo'],$path,$referencia,$flujoAnterior->EstadoActual(),$identificador,'','IF');
			$mensaje['datos']=$ce->listarArchivosAnexos($conexion,$protocolo['id_protocolo'],'IF');
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje']='Notificación enviada';
			break;

		case 'emitirInformeEnsayo':
			
			$informe=$ce->obtenerInformeFinalEnsayo($conexion,$id_documento);
			if($informe['ruta_resumen']!=null){
				$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
				$observacion=$_POST['referencia'];
				$fechaSubsanacion=new DateTime();
				$plazo=$flujo->Plazo();
				$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
				$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

				$ident=$flujoAnterior->PerfilSiguiente();						//Para asignar los técnicos del ensayo

				$asuntoCorreo='Informe de Supervición de Ensayo';
				$enviarCorreo=true;
				$esta_procesado=true;
			}
			else{
				$datosDocumento['ruta_informe_inspeccion']=$_POST['path'];
				$ce ->guardarInformeFinal($conexion,$datosDocumento);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Informe de inspección cargado';
			}

			
			break;

		case 'emitirInformeFinal':

			$msg=$ce->obtenerInformeFinalEnsayo($conexion,$id_documento);
			//verifica si los campos obligatorios han sido llenos
			if($ce->verificarVectorLleno($msg,'caracteristica,ambito,efecto_plagas,condiciones,metodo_aplicacion,instrucciones,numero_aplicacion,eficacia,gasto_agua,fitotoxicidad,conclusiones,dosis'))
			{
				$datosDocumento['fecha_solicitud']=$fecha;
				$datosDocumento['id_expediente']=$ce->obtenerSecuencialEEInforme($conexion,date('Y'));	//Genera el codigo del expediente del informe
				//genera el archivo pdf y lo guarda en la ruta
				
				
				$informe=$ce->obtenerInformeFinalEnsayo($conexion,$id_documento);
				if($informe['ruta_informe_inspeccion']!=null){
					$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
					$observacion=$_POST['referencia'];
					$fechaSubsanacion=new DateTime();
					$plazo=$flujo->Plazo();
					$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
					$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

					$ident=$flujoAnterior->PerfilSiguiente();						//Para asignar los técnicos del ensayo
					$datoMensaje['id_expediente']=$datosDocumento['id_expediente'];
					
					$pendiente=$tramiteFlujo['pendiente'];
					
					$esta_procesado=true;
					$generarInforme=true;
				}
				else{

					try{
						$pendiente="I";
						$conexion->Begin();						
						$ce ->guardarInformeFinal($conexion,$datosDocumento);
						$cGeneradorProtocolo=new GeneradorProtocolo();
						$respuesta=$cGeneradorProtocolo->generarInforme($conexion,$id_documento,$esInformeLegal);
						$datosDocumento['ruta_resumen']=$respuesta['datos'];
						$res=$ce->guardarInformeFinal($conexion,$datosDocumento);
						$ce->actualizarTramiteEstado($conexion,$tramiteFlujo['id_tramite'],null,$pendiente,$tramiteFlujo['fecha_fin']);
						//$ce->actualizarTramiteFlujoEstado($conexion,$tramiteFlujo['id_tramite_flujo'],$pendiente,$tramiteFlujo['fecha_fin'],null,$retraso,$pendiente,$identificador);
						
						//Genera nueva petición de trámite para el paso siguiente
						$plazo=$tramiteFlujo['plazo'];
						$ident=$tramiteFlujo['identificador'];
						$ident_ejecutor=$tramiteFlujo['ejecutor'];
						$fechaSubsanacion= new DateTime( $tramiteFlujo['fecha_fin']);
						$fechaSubsanacion=$fechaSubsanacion->format('Y-m-d');
						$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fecha,null,$retraso,$pendiente,$identificador);	//Cierra la fase del tramite						
						$ce->guardarFlujoDelTramite($conexion,null,$id_tramite,$tramiteFlujo['id_flujo_documento'],$ident,$identificador,$ident_ejecutor,$pendiente,$fecha,$fechaSubsanacion,$observacion,$plazo);

						$msg=$ce->obtenerInformeFinalEnsayo($conexion,$id_documento);
						$mensaje['mensaje']=$msg;
						$conexion->Commit();
						$mensaje['estado'] = 'exito';

					}
					catch(Exception $e){
						$conexion->Rollback();
					}
				}
			}
			break;

		case 'guardarAsignacionInforme':
			$ident=$_POST['tecnico'];
			$ident_ejecutor=$ident;
			$flujo=$flujoAnterior;
			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
			$esta_procesado=true;
			$reasignar_tramite=true;
			break;

		case 'guardarObservacionesInforme':
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

		case 'guardarAprobacionesInforme':
			$tipo_documento=$flujoAnterior->TipoDocumento();

			$observacion=$_POST['observacion'];
			$condicion=$_POST['condicion'];
			if($condicion==''){
				$condicion='';
				if($tramiteFlujo['pendiente']!='A')
					$condicion='C_D_O';
			}

			$fechaActual=new DateTime();

			//*************** DISTRIBUYO EL TRAMITE o Finalizo ********

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
						$ident=$flujo->PerfilActual();
					else{
						$ident=$tramiteFlujo['ejecutor'];	//tramie hacia el técnico
					}
					
					$fechaSubsanacion=new DateTime();
					$plazo=$flujo->PlazoExtendido();
					$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
					$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
				}
				else{
					if($perfil->EsDirector())					//Cuando esta en la fase del director RIA
					{
						$ident=$flujo->PerfilActual();		//tramie a su inmediato superior
						
					}
					else {	//Es director distrital o coordinador tramite al operador
						$enviarCorreo=true;
						if($condicion=='C_D_O'){				//necesita ser subsanado
							$ident=$tramiteFlujo['operador'];
							
							$datoMensaje['resultado']='O';
							$asuntoCorreo='Informe final de ensayo de eficacia observado';
							
						}
						else{		//fin del tramite de ensayos
							//verificar el informe de supervisión

							$finTramite=true;
							$generarInforme=true;
							$esInformeLegal='SI';
							$datosDocumento['fecha_aprobacion']=$fecha;	

							$ident=$tramiteFlujo['operador'];		//tramite al operador para que elija el Organismo de inspeccion
							$fechaSubsanacion=$fecha;
							
							$datoMensaje['resultado']='A';
							$asuntoCorreo='Informe final de ensayo de eficacia aprobado';
						}
					}
				}

				$esta_procesado=true;
			}
			//****************************************************

			break;

		case 'guardarSubsanacionesInforme':
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
						$datosDocumento[$formato['campo']]=$item;
					}

				

		         //obtengo las observaciones pendienes a este punto de estado [S]
					$doc=$ce->obtenerObservacionesDelDocumentoPorEnlace($conexion,$id_documento,$tipo_documento,$formato['id_enlace'],'S');
		         $revision=1;
		         if(sizeof($doc)>0){
		            foreach($doc as $k=>$v){
		               //corrige todas observaciones de este punto pendienes como subsanadas estado ya no pendienes [N]
		               $ce->actualizarObservacionTramiteEstado($conexion,$v['id_tramite_observacion'],'N');
		               $observacion='; '.$observacion.' '.$key.' : '.$item;
		            }
		         }
					$generarInforme=true;
		      }
				else if(substr($key,0,11)=="evaluacion_"){
					$vectorIds=explode('_',$key);
					$valFloat=floatval($item);
					$ce->guardarMatrizEficacia($conexion,$id_documento,$vectorIds[1],$vectorIds[2],$valFloat);		//Cuarda el valor de la matriz de eficacia
					$generarInforme=true;
				}
		   }
			$doc=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento,'S');
			if(sizeof($doc)>0){

				$esta_procesado=true;
			}
			else
				$esta_procesado=true;

		   $flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
		   //*************** Plazo para completar la siguiente fase ********
		   $fechaSubsanacion=new DateTime();
		   $plazo=$flujo->Plazo();
		   $fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
		   $fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
		   //*************** RETORNO EL TRAMITE Al tecnico QUIEN le ENVIÓ ********
		   $ident=$ident_ejecutor;

		   break;

		case 'enviarCorreo':

			$cMail=new ControladorMail();
			$asunto = $_POST['asuntoCorreo'];
			$salidaReporte = $_POST['rutaAdjunto'];
			$cuerpoCorreo='';
			$fechaActual=new DateTime();
			$cuerpoMensaje=$ce->redactarNotificacion($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asunto);
			$destinatario = array();
			$datos=$ce->obtenerProtocolo($conexion,$tramiteFlujo['id_protocolo']);
			array_push($destinatario, $datos['email_representante_legal']);
			$adjuntos = array();
			if($salidaReporte!=null)
			    array_push($adjuntos, $rutaArchivoFirmado, $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte);

			echo IN_MSG . 'Insertar registro de envío de correo electronico.';

			$codigoModulo = 'PRG_ENSAYO_EFI';
			$tablaModulo = '';
			$idSolicitudTabla='';

			$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
			$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
			$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
			$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);

			$esta_procesado=false;
			$mensaje['estado'] = 'OK';
			$mensaje['mensaje'] = 'OK';
			break;
	}

	if($flujo!=null && $esta_procesado && $ident!=''){
		$datosDocumento['estado'] = $flujo->EstadoActual();
		try{
			$conexion->Begin();
			if($generarInforme==true){
				//genera el informe con las modificaciones
				$ce ->guardarInformeFinal($conexion,$datosDocumento);
				$cGeneradorProtocolo=new GeneradorProtocolo();
				$respuesta=$cGeneradorProtocolo->generarInforme($conexion,$id_documento,$esInformeLegal);
				$datosDocumento['ruta_resumen']=$respuesta['datos'];
			}
			$ce ->guardarInformeFinal($conexion,$datosDocumento);
			$ce->actualizarTramiteIdentificador($conexion,$id_tramite,$ident);
			if($reasignar_tramite){
				$ce->reasignarTecnicoTramite($conexion,$id_tramite,$ident);
			}
			$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fecha,null,$retraso,$pendiente,$identificador);	//Cierra la fase del tramite
			//Genera nueva petición de trámite para el paso siguiente
			$ce->guardarFlujoDelTramite($conexion,null,$id_tramite,$flujo->Flujo_documento(),$ident,$identificador,$ident_ejecutor,$pendiente,$fecha,$fechaSubsanacion,$observacion,$plazo);
			$protocolo=array();
			$adjuntos=array();
			if($finTramite)	{
				//actualiza estado del tramite
				$ce->actualizarTramiteEstado($conexion,$id_tramite,$observacion,$pendiente,$fecha);
				//verifica si se trata de ampliacion de uso o modificación de dosis
				$protocolo=$ce->obtenerProtocoloDesdeInforme($conexion,$id_documento);
				$informeFinal=$ce->obtenerInformeFinalEnsayo($conexion, $id_documento);
				if($protocolo['motivo']=='MOT_AMP'){
				    //pendiente tabla de plagas no corresponde con los usos en catalogo
				}
				else if($protocolo['motivo']=='MOT_MOD'){
				    //actualiza la dosis
				    $ce->actualizarDosis($conexion, $protocolo['plaguicida_registro'], $informeFinal['dosis'], $informeFinal['dosis_unidad'], $fecha,$protocolo['uso']);
				}
				
				//verifica si el protocolo tiene mas de un informe final que esté en proceso
				$estaCompleto=$ce->verificarInformesFinalesConEstado($conexion,$id_documento,'aprobado');
				if($estaCompleto){	//ya todos los informes finales están aprobados
					//actualiza el estado del ensayo
					
					$datosProtocolo=array();
					$datosProtocolo['id_protocolo']=$protocolo['id_protocolo'];
					$datosProtocolo['estado']='aprobado';
				
					$ce->guardarProtocolo($conexion,$datosProtocolo);
					$asuntoProtocolo='Ensayo de eficacia aprobado';
					$cuerpoMensaje=$ce->redactarNotificacionDesdeInformes($conexion,$id_tramite,$fecha,$asuntoProtocolo,true);
					if(sizeof($protocolo)==0)
						$protocolo=$ce->obtenerProtocoloDesdeInforme($conexion,$id_documento);
					$destinatarios=array($protocolo['email_representante_legal']);

					$codigoModulo = 'PRG_ENSAYO_EFI';
					$tablaModulo = '';
					$idSolicitudTabla='';
					$cMail=new ControladorMail();

					$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asuntoProtocolo, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
					$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
					$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
					$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
				}
			}

			if($enviarCorreo){
				$cuerpoMensaje=$ce->redactarNotificacionDesdeInformes($conexion,$id_tramite,$fecha,$asuntoCorreo);
				if(sizeof($protocolo)==0)
					$protocolo=$ce->obtenerProtocoloDesdeInforme($conexion,$id_documento);
				$destinatarios=array($protocolo['email_representante_legal']);

				$codigoModulo = 'PRG_ENSAYO_EFI';
				$tablaModulo = '';
				$idSolicitudTabla='';
				$cMail=new ControladorMail();

				$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asuntoCorreo, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
				$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
				$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
				$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);

			}


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