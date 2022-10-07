<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once '../../clases/ControladorDossierFertilizante.php';

require_once '../../clases/ControladorUsuarios.php';

require_once '../../clases/ControladorMail.php';

require_once '../ensayoEficacia/clases/Transaccion.php';


require_once '../ensayoEficacia/clases/Flujo.php';
require_once '../ensayoEficacia/clases/Perfil.php';

require_once 'clases/GeneradorCertificadosFertilizante.php';



$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Transaccion();
	$ce = new ControladorEnsayoEficacia();
	$cf = new ControladorDossierFertilizante();
	$identificador= $_SESSION['usuario'];
	$opcion_llamada = $_POST['opcion_llamada'];

	$datoMensaje=array();

	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
	$perfil=new Perfil($perfiles);

	$id_documento= $_POST['id_documento'];
	$datosDocumento['id_solicitud']=$id_documento;
	$id_tramite= $_POST['id_tramite'];
	$id_tramite_flujo=$_POST['id_tramite_flujo'];
	$id_flujo=$_POST['id_flujo'];
	$estadoTramite='';
	if($id_tramite_flujo>0){
		$tramiteFlujo=$cf->obtenerTramiteFlujoDF($conexion,$id_tramite_flujo);
		$id_tramite=$tramiteFlujo['id_tramite'];
		$tramite=$ce->obtenerTramiteDesdeFlujoTramiteEE($conexion,$id_tramite_flujo);
		$estadoTramite=$tramite['status'];
	}
	$esta_procesado=false;
	$actualizarEstadoTramite=false;
	$pendiente='S';
	$condicion='';
	$observacion='';
	$ident='';
	$ident_ejecutor='';

	$finTramite=false;
	$enviarCorreo=false;
	$fecha=new DateTime();
	$fecha = $fecha->format('Y-m-d');
	$asuntoCorreo='';
	$destinatarios=array();
	$adjuntos=array();

	$retraso=trim(htmlspecialchars ($_POST['retraso'],ENT_NOQUOTES,'UTF-8'));
	$plazo=0;

	switch($opcion_llamada){

		case 'asignarTramiteDossier':
			$pendiente='S';

			//*************** DISTRIBUYO EL TRAMITE ********
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);

			$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
			//*************** Plazo para completar la siguiente fase ********
			$fecha=new DateTime();
			$fecha = $fecha->format('Y-m-d');
			$fechaSubsanacion=new DateTime();
			$plazo=$flujo->Plazo();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

			if($perfil->tieneEstePerfil('PFL_RES_CENTRAL')){
				$ident=$_POST['tecnico'];

				$ident_ejecutor=$ident;
				$esta_procesado=true;
				$datoMensaje='Actualización del tramite fue enviada';
				
			}
			else{
				$datoMensaje='No se pudo procesar el tramite';
			}
			break;

		case 'guardarObservacionesSolicitud':
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
			
			$tipo_documento="DF";
			$observacion=trim(htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));


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

			if($perfil->tieneEstePerfil('PFL_DF_ARIA') ){
				$ident=$flujo->PerfilActual();		//Envia al director de RIA
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
				$esta_procesado=true;
			}

			break;

		case 'guardarAprobacionesSolicitud':
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
			$tipo_documento=$flujoAnterior->TipoDocumento();

			$observacion=$_POST['observacion'];
			$condicion=$_POST['condicion'];

			$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
			$pendiente=$tramiteFlujo['pendiente'];

			$fechaSubsanacion=new DateTime();
			if($condicion=='C_I_O'){
				
				$plazo=$flujo->PlazoExtendido();
				if($perfil->tieneEstePerfil('PFL_EE_CRIA'))
					$ident='PFL_EE_DRIA';
				else
					$ident=$tramiteFlujo['ejecutor'];
			}
			else if($condicion=='C_D_O'){
				$plazo=$flujo->Plazo();
				$ident=$tramiteFlujo['operador'];
				
				$datoMensaje['resultado']='O';
				$asuntoCorreo='Dossier Observado';
				$enviarCorreo=true;
			}
			else {
				$plazo=$flujo->Plazo();
				if($perfil->tieneEstePerfil('PFL_EE_DRIA')){
					$ident=$flujo->PerfilActual();
					
				}
				else if($perfil->tieneEstePerfil('PFL_EE_CRIA')){
					$ident=$tramiteFlujo['operador'];
					$pendiente='T';
					$actualizarEstadoTramite=true;
					$estadoTramite='T';
					$datoMensaje['resultado']='A';
					$asuntoCorreo='Solicitud de dossier APROBADA';

					$enviarCorreo=true;
				}
			}
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

			$ident_ejecutor=$tramiteFlujo['ejecutor'];
			$esta_procesado=true;

			break;

		case 'guardarSubsanacionesSolicitud':
		   $tipo_documento='DF';	
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
			
			
		   $datos=array();
			$observacion='Observaciones subsanadas: ';
			$datosDocumento['ruta_dossier']=$_POST['ruta'];
		   foreach($_POST as $key=>$item){
		      if(substr($key,0,7)=="subsan_"){
		         //obtengo los parametros del campo recuperado
		         $formato=$ce->obtenerFormatoDelElemeno($conexion,$tipo_documento,substr($key,7));
					if($formato==null)
						continue;
					//*********************
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
		               $observacion=$observacion.'; '.$item;
		            }
		         }
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
			$ident_ejecutor=$tramiteFlujo['ejecutor'];
		   $ident=$ident_ejecutor;

		   break;


		case 'guardarPuntosEtiqueta':
			$firmante='';
			$firmanteCargo='';
			$id_solicitud=$_POST['id_solicitud'];
			if($id_solicitud==null || $id_solicitud=='')
				break;
			$respuesta=$ce->obtenerFuncionarioXarea($conexion,'CGRIA');
			if(sizeof($respuesta)>0){
				$firmante=$respuesta[0]['apellido'].' '.$respuesta[0]['nombre'];
				$firmanteCargo=$respuesta[0]['cargo'];
			}
			$cGenerador=new GeneradorCertificadosFertilizante();
			$mensaje=$cGenerador->generarPuntosMinimos($conexion,$id_solicitud,$firmante,$firmanteCargo);

			$datosEtiqueta=$cf->obtenerEtiquetaSolicitud($conexion, $id_solicitud);
			$datosEtiqueta['estado']='aprobarEtiqueta';
			$datosEtiqueta['ruta']=$mensaje['datos'];
			$res=$cf->guardarEtiqueta($conexion,$datosEtiqueta);

			break;

		case 'guardarAprobacionCumplimiento':
			$id_solicitud=$_POST['id_solicitud'];

			$observacion=$_POST['observacion'];
			$condicion=$_POST['condicion'];

			if($condicion=='C_D_O'){
				$datosEtiqueta=$cf->obtenerEtiquetaSolicitud($conexion, $id_solicitud);
				$datosEtiqueta['estado']='subsanarEtiqueta';
				$datosEtiqueta['comentario']=$observacion;
				$res=$cf->guardarEtiqueta($conexion,$datosEtiqueta);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Trámite enviado para ser subsanado';

			}
			else {

				$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
				$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
				$tipo_documento=$flujoAnterior->TipoDocumento();
				$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);

				$fechaSubsanacion=new DateTime();

				$plazo=$flujo->Plazo();
				if($perfil->tieneEstePerfil('PFL_DF_ARIA')){
					$ident=$tramiteFlujo['operador'];
					$pendiente='A';
					$actualizarEstadoTramite=true;
					$estadoTramite='A';
					$datoMensaje['resultado']='A';
					$asuntoCorreo='Dossier Aprobado';
					$finTramite=true;
					$enviarCorreo=true;
					$esta_procesado=true;
					$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
					$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

					$ident_ejecutor=$tramiteFlujo['ejecutor'];
				}

			}



			break;

		case 'guardarOrganismoExterno':
			$id_solicitud=$_POST['id_solicitud'];
			$datosDossier=array();
			$datosDossier['id_solicitud']=$id_solicitud;
			$tipoPerfil=$_POST['tipoPerfil'];
			$comentario=trim(htmlspecialchars ($_POST['comentario'],ENT_NOQUOTES,'UTF-8'));
			$rutaArchivo=$_POST['rutaArchivo'];
			$opcionAprobar=$_POST['opcionAprobar'];
			$estadoAprobado='t';
			if($opcionAprobar=='C_D_O')
				$estadoAprobado='f';

			if($tipoPerfil=='PFL_DA_SALUD'){
				$datosDossier['salud_estado']=$estadoAprobado;
				$datosDossier['salud_comentario']=$comentario;
				$datosDossier['salud_ruta']=$rutaArchivo;
			}
			else{
				$datosDossier['mae_estado']=$estadoAprobado;
				$datosDossier['mae_comentario']=$comentario;
				$datosDossier['mae_ruta']=$rutaArchivo;
			}


			$res=$cf->guardarSolicitud($conexion,$datosDossier);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Trámite enviado para ser subsanado';
			break;

		case 'enviarCorreo':

			$cMail=new ControladorMail();
			$asunto = $_POST['asuntoCorreo'];
			$salidaReporte = $_POST['rutaAdjunto'];
			$cuerpoCorreo='';
			$fechaActual=new DateTime();
			$cuerpoMensaje=$cg->redactarNotificacionEmailPG($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);
			$destinatario = array();
			
			array_push($destinatario, $cuerpoMensaje['datos']['correo']);
			$adjuntos = array();
			
			echo IN_MSG . 'Insertar registro de envío de correo electronico.';

			$codigoModulo = 'PRG_DOSSIER_PEC';
			$tablaModulo = '';
			$idSolicitudTabla='';	

			try{
				$conexion->Begin();
				$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje['mensaje'], 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
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
		$datosDocumento['estado'] = $flujo->EstadoActual();
		
		try{
			$conexion->Begin();

			$ce->actualizarTramiteIdentificador($conexion,$id_tramite,$ident);
			
			$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fecha,null,$retraso,$pendiente,$identificador);	//Cierra la fase del tramite
			$ce->guardarFlujoDelTramite($conexion,null,$id_tramite,$flujo->Flujo_documento(),$ident,$identificador,$ident_ejecutor,$pendiente,$fecha,$fechaSubsanacion,$observacion,$plazo);
			if($actualizarEstadoTramite){
				$ce->actualizarTramiteEstado($conexion,$id_tramite,$observacion,$estadoTramite,$fechaSubsanacion);
			}

			$fechaInscripcion=new DateTime();
			$fechaInscripcion = $fechaInscripcion->format('Y-m-d');
			if($finTramite){
				//Genera Certificado
				$ccert=new GeneradorCertificadosFertilizante();
				$cu=new ControladorUsuarios();
				$respuesta=$ce->obtenerFuncionarioXarea($conexion,'CGRIA');
				$firmante='';
				$firmanteCargo='';
				if(sizeof($respuesta)>0){
					$firmante=$respuesta[0]['nombre'].' '.$respuesta[0]['apellido'];
					$firmanteCargo=$respuesta[0]['cargo'];
				}

				$datosCertificado=$ccert->generarCertificado($conexion,$datosDocumento['id_solicitud'],$firmante,$firmanteCargo);
				$datosDocumento['id_certificado']=$datosCertificado['id_certificado'];
				$datosDocumento['ruta_certificado']=$datosCertificado['datos'];
				
				$datosDocumento['fecha_inscripcion']=$fechaInscripcion;
				//Genera Puntos minimos de etiqueta
				$datosEtiqueta=$ccert->generarPuntosMinimos($conexion,$datosDocumento['id_solicitud'],$firmante,$firmanteCargo,$datosCertificado['id_certificado']);
				$datosDocumento['ruta_etiqueta']=$datosEtiqueta['datos'];
				
				$datosSolicitud=$ccert->generarSolicitudRegistro($conexion,'SI',$id_solicitud);
				$datosDocumento['ruta_dossier']=$datosSolicitud['datos'];
				
			}
			$cf -> guardarSolicitud($conexion,$datosDocumento);
			if($finTramite){
				//Sube el producto al modulo GUIA
				$tipo_aplicacion = ($_SESSION['idAplicacion']);			
				$datosUsuario=$_SESSION['datosUsuario'];
				$cGenerador=new GeneradorCertificadosFertilizante();
				$subido=$cGenerador->subirProducto($conexion,$datosDocumento['id_solicitud'],$datosCertificado['id_certificado'],$fechaInscripcion,$tipo_aplicacion,$identificador,$datosUsuario);
			}
			if($enviarCorreo){
				$fechaActual=new DateTime();
				$cuerpoMensaje=$cf->redactarNotificacionEmailPF($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);
				array_push($destinatarios, $cuerpoMensaje['datos']['correo']);

				$codigoModulo = 'PRG_DOSSIER_FER';
				$tablaModulo = '';
				$idSolicitudTabla='';
				$cMail=new ControladorMail();

				$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asuntoCorreo, $cuerpoMensaje['mensaje'], 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
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