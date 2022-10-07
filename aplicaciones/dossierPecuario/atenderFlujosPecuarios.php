<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatalogos.php';

require_once '../ensayoEficacia/clases/Transaccion.php';
require_once '../ensayoEficacia/clases/Flujo.php';
require_once '../ensayoEficacia/clases/Perfil.php';

require_once 'clases/GeneradorDocumentoPecuario.php';

require_once 'clases/GeneradorProcesoPecuario.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Transaccion();
	$ce = new ControladorEnsayoEficacia();
	$cp = new ControladorDossierPecuario();
	$constg = new Constantes();
	$identificador= $_SESSION['usuario'];
	$opcion_llamada = $_POST['opcion_llamada'];

	$datoMensaje=array();

	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
	$perfil=new Perfil($perfiles);

	$id_documento= $_POST['id_documento'];
	$datosDocumento=array();
	$datosDocumento['id_solicitud']=$id_documento;
	$id_tramite= $_POST['id_tramite'];
	$id_tramite_flujo=$_POST['id_tramite_flujo'];
	$id_flujo=$_POST['id_flujo'];
	$estadoTramite='';
	if($id_tramite_flujo>0){
		$tramiteFlujo=$cp->obtenerTramiteFlujoDP($conexion,$id_tramite_flujo);
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
	$generarDossier=false;
	$enviarCorreo=false;
	$adjuntos=array();

	$destinatarios=array();

	$fecha=new DateTime();
	$fecha = $fecha->format('Y-m-d');

	$retraso=trim(htmlspecialchars ($_POST['retraso'],ENT_NOQUOTES,'UTF-8'));
	$plazo=0;
	$archivo=trim(htmlspecialchars ($_POST['archivoInforme'],ENT_NOQUOTES,'UTF-8'));//revisar!!!

	switch($opcion_llamada){

		case 'asignarTramiteIngreso':
			$tipo_documento=$tramiteFlujo['tipo_documento'];
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

			$perfilCoordinador='PFL_DP_CGSA';
			$perfilDirector='PFL_DP_DCZ';
			if($perfil->tieneEstePerfil($perfilCoordinador)){
				$ident=$_POST['director'];
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
				$esta_procesado=true;
				$datoMensaje='Actualización del tramite fue enviada';
			}
			else if($perfil->tieneEstePerfil($perfilDirector)){
				$ident=$_POST['tecnico'];
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
				$esta_procesado=true;
				$datoMensaje='Actualización del tramite fue enviada';
			}
			else{
				$datoMensaje='No se pudo procesar el tramite';
			}
			break;

		case 'asignarTramiteMetodo':
			$tipo_documento=$tramiteFlujo['tipo_documento'];
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

			$perfilDirector='PFL_DP_DDIACIA';
			if($perfil->tieneEstePerfil($perfilDirector)){
				$ident=$_POST['tecnico'];
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
				$esta_procesado=true;
				$datoMensaje='Actualización del tramite fue enviada';
			}
			else{
				$datoMensaje='No se pudo procesar el tramite';
			}
			break;

		case 'evaluarIngreso':
			$tipo_documento=$tramiteFlujo['tipo_documento'];
			$actual=$ce->normalizarBoolean($_POST['boolAcepto']);

			$cepa=htmlspecialchars ($_POST['cepa'],ENT_NOQUOTES,'UTF-8');
			$anexo=htmlspecialchars ($_POST['anexo'],ENT_NOQUOTES,'UTF-8');

			$observacionCondicion='';

			$perfilCoordinador='PFL_DP_CGSA';
			$perfilDirector='PFL_DP_DCZ';
			$perfilTSA='PFL_DP_TSA';
			if($actual=='0'){
				$observacionCondicion='Trámite Observado';
				$pendiente='O';		//Certificado observado
				if(!$perfil->tieneEstePerfil($perfilTSA))
					$condicion='C_I_O';
			}
			else{
				$observacionCondicion='Trámite Aprobado';
				$pendiente='A';		//Ingreso aprobado
				if($perfil->tieneEstePerfil($perfilCoordinador))
					$condicion='NUEVA';
			}
			$observacion=$observacionCondicion.': '.trim( htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));
			//*************** DISTRIBUYO EL TRAMITE ********
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);


			$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,null);
			//*************** Plazo para completar la siguiente fase ********
			$fecha=new DateTime();
			$fecha = $fecha->format('Y-m-d');



			if($perfil->tieneEstePerfil($perfilCoordinador)){
				if($actual=='0'){
					$ident=$tramiteFlujo['remitente'];
					$ident_ejecutor=$tramiteFlujo['ejecutor'];
					$esta_procesado=true;
					$datoMensaje='Evaluación del tramite fue enviada';
				}
				else{
					if($flujoAnterior->Condicion()=="NUEVA"){
						if($estadoTramite!='A'){	//No se aprueba ingreso al país
							//Denegado definitivo
							try{
								$conexion->Begin();
								$datosDocumento['estado'] = "negado";
								$cp -> guardarSolicitud($conexion,$datosDocumento);
								$ce->actualizarTramiteEstado($conexion,$id_tramite,"Tramite negado, no se aprueba ingreso al pais","N",$fecha);
								
								$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fecha,null,$retraso);	//Cierra la fase del tramite
								$conexion->Commit();
								$mensaje['estado'] = 'exito';
								$mensaje['mensaje'] = $datoMensaje;
								$asuntoCorreo="Ingreso de nueva CEPA negado";
								$enviarCorreo=true;
							}catch(Exception $e){
								$conexion->Rollback();
								$mensaje['estado'] = 'fallo';
								$mensaje['mensaje'] = 'No se realizó ningún cambio';
							}
						}
						else{

							$datosDocumento['estado'] = $flujo->EstadoActual();
							$datosDocumento['id_flujo_documento'] = null;
							$ident=$tramiteFlujo['operador'];
							$ident_ejecutor=$tramiteFlujo['ejecutor'];
							$pendiente='N';
							$fechaSubsanacion=$fecha;

							try{
								$conexion->Begin();
								$cp -> guardarSolicitud($conexion,$datosDocumento);								//actualiza estado del documento
								$ce->actualizarTramiteIdentificador($conexion,$id_tramite,$ident);
								
								$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fecha,null,$retraso);	//Cierra la fase del tramite
								$ce->guardarFlujoDelTramite($conexion,null,$id_tramite,$flujo->Flujo_documento(),$ident,$identificador,$ident_ejecutor,$pendiente,$fecha,$fechaSubsanacion,$observacion);
								$conexion->Commit();
								$mensaje['estado'] = 'exito';
								$mensaje['mensaje'] = $datoMensaje;
								$asuntoCorreo='Aprobado ingreso de nueva CEPA : '.$cepa;
								
								$pathAnexo=realpath('./../../');
								
								if($anexo!=null)
									array_push($adjuntos, $pathAnexo.'/'.$anexo);

								$enviarCorreo=true;
								$correoResponsable=$ce->obteneCorreoPorPerfil($conexion,'PFL_RES_CENTRAL','PRG_DOSSIER_PEC');
								foreach($correoResponsable as $itemCorreo){
									if($itemCorreo['mail_institucional']!=null){
										array_push($destinatarios,$itemCorreo['mail_institucional']);	
									}
								}
								
							}
							catch(Exception $e){
								$conexion->Rollback();
								
								$mensaje['estado'] = 'fallo';
								$mensaje['mensaje'] = 'No se realizó ningún cambio';
							}
						}
						$esta_procesado=false;
						if($enviarCorreo){
							$fechaActual=new DateTime();
							$cuerpoMensaje=$cp->redactarNotificacionEmailPC($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);
							array_push($destinatarios,$cuerpoMensaje['datos']['email_representante_legal']);
							
							$codigoModulo = 'PRG_DOSSIER_PEC';
							$tablaModulo = '';
							$idSolicitudTabla='';
							$cMail=new ControladorMail();
							try{
								$conexion->Begin();
								$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asuntoCorreo, $cuerpoMensaje['mensaje'], 'Por enviar', $codigoModulo, $tablaModulo,$idSolicitudTabla);
								$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
								$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
								$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
								$conexion->Commit();
							}
							catch(Exception $e){
								$conexion->Rollback();
								
							}
						}

					}
					else{
						$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,null,'');		//para enviar a pago
						$ident=$tramiteFlujo['operador'];
						$ident_ejecutor=$tramiteFlujo['ejecutor'];
						$esta_procesado=true;
						$datoMensaje='Evaluación del tramite fue enviada';
					}
				}
			}
			else if($perfil->tieneEstePerfil($perfilDirector)){
				if($actual=='0'){
					$ident=$tramiteFlujo['ejecutor'];
					$ident_ejecutor=$tramiteFlujo['ejecutor'];
				}
				else{
					$ident='PFL_DP_CGSA';
					$ident_ejecutor=$tramiteFlujo['ejecutor'];
				}
				$esta_procesado=true;
				$datoMensaje='Evaluación del tramite fue enviada';
			}
			else if($perfil->tieneEstePerfil($perfilTSA)){
				$ident=$tramiteFlujo['remitente'];
				$ident_ejecutor=$identificador;
				$esta_procesado=true;
				$actualizarEstadoTramite=true;
				$estadoTramite=$pendiente;
				$datoMensaje='Evaluación del tramite fue enviada';
			}
			else{
				$datoMensaje='No se pudo procesar el tramite';
			}
			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
			break;

		case 'evaluarMetodo':
			$tipo_documento=$tramiteFlujo['tipo_documento'];
			$condicion=$_POST['boolAcepto'];
			$observacion=$_POST['observacion'];
			$perfilTL='PFL_DP_TL';

			//*************** DISTRIBUYO EL TRAMITE ********
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);

			$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,null);

			//*************** Plazo para completar la siguiente fase ********
			$fecha=new DateTime();
			$fecha = $fecha->format('Y-m-d');
			$perfilDirector='PFL_DP_DDIACIA';
			if($perfil->tieneEstePerfil($perfilDirector)){
				switch($condicion){
					
					case 'C_I_O':
						$ident=$tramiteFlujo['remitente'];
						$pendiente='I';
						break;
					default:
						$condicionAnterior=$tramiteFlujo['pendiente'];
						if($condicionAnterior=='O'){	//Caso subsanacion
							$condicion='C_M_O';
							$ident=$tramiteFlujo['operador'];
							$pendiente='O';
							$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,null);	//Envia a subsanar
						}
						else{				//Caso aprobado metodo
							$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,null,'');	//Selector nulo para enviar a pago
							//Envia el proceso al responable							
							/*
							 * INICIO EJAR BLOQUE COMENTADO
							 * if($tramite['id_division']=='DIV_PICH'){
								$ident='PFL_RES_CENTRAL';								
							}
							else{
								$ident='PFL_RES_DISTRITO';
							}
							*/
							$ident='PFL_RES_DISTRITO';
							/*FIN EJAR AGREGACION DE LINEA $ident='PFL_RES_DISTRITO'; TENER EN CUENTA QUE SE DEBE ELIMINAR DE LA TABLA
							 * g_ensayo_eficacia.flujo_documentos el registro con id_flujo_documento = 59*/
							$pendiente='A';
						}
						break;
				}
				$esta_procesado=true;
				$datoMensaje='Evaluación del tramite fue enviada';
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
			}

			else if($perfil->tieneEstePerfil($perfilTL)){
				$ident=$perfilDirector;	
				$ident_ejecutor=$identificador;
				$esta_procesado=true;
				$datoMensaje='Evaluación del tramite fue enviada';
				switch($condicion){
					case 'C_M_O':
						$pendiente='O';
						break;
					default:
						$pendiente='A';
						break;
				}
			}
			else{
				$datoMensaje='No se pudo procesar el tramite';
			}

			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
			break;

		case 'guardarSubsanacionesMetodo':
			$tipo_documento=$tramiteFlujo['tipo_documento'];
			$condicion=$_POST['boolAcepto'];
			$observacion=$_POST['observacion'];

			//*************** DISTRIBUYO EL TRAMITE ********
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
			$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,null);
			//*************** Plazo para completar la siguiente fase ********
			$fecha=new DateTime();
			$fecha = $fecha->format('Y-m-d');
			if($perfil->EsOperador()){
				$ident=$tramiteFlujo['ejecutor'];
				$ident_ejecutor=$tramiteFlujo['ejecutor'];
				$esta_procesado=true;
				$datoMensaje='Subsanación fue enviada';
				$pendiente='S';
				
			}

			else{
				$datoMensaje='No se pudo procesar el tramite';
			}

			$plazo=$flujo->Plazo();
			$fechaSubsanacion=new DateTime();
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
			break;

		case 'asignarTramiteDossier':
			$pendiente='S';

			//*************** DISTRIBUYO EL TRAMITE ********
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);
			if($flujoAnterior->getSelector()=='tramite_destino'){
				$flujo=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null);
			}
			else{
				$flujoAnterior->CambiarSelector('tramite_destino');
				$selector_valor=$_POST['selector_valor'];
				//incrementa el paso del flujo a la fase de asignación
				$flujoIntermedio=$flujoAnterior->BuscarFaseSiguienteConCondicion($condicion,null,'DP',$selector_valor);
				$flujo=$flujoIntermedio->BuscarFaseSiguienteConCondicion($condicion,null);
			}
						
			//*************** Plazo para completar la siguiente fase ********
			$fecha=new DateTime();
			$fecha = $fecha->format('Y-m-d');
			$fechaSubsanacion=new DateTime();
			
			$plazo=$cp->obtenerPlazoDesdeFlujo($conexion,$id_tramite_flujo,$flujo);
			
			$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');


			if($perfil->tieneEstePerfil('PFL_RES_DISTRITO') || $perfil->tieneEstePerfil('PFL_RES_CENTRAL')){
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
			$tipo_documento=$flujoAnterior->TipoDocumento();
			$observacion=trim(htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));
			$datosDocumento['declaracion_venta']=$_POST['declaracion_venta'];


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

			if($perfil->tieneEstePerfil('PFL_DP_ARIP') || $perfil->tieneEstePerfil('PFL_DP_ADIP')){
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
				if($perfil->tieneEstePerfil('PFL_DP_DRIP'))
					$ident=$tramiteFlujo['ejecutor'];
				else
					$ident=$tramiteFlujo['remitente'];
				$esta_procesado=true;
			}
			else if($condicion=='C_D_O'){				
				$plazo=$cp->obtenerPlazoDesdeFlujo($conexion,$id_tramite_flujo,$flujo);			//plazo total
				$plazoTotal=$plazo;
				$flujosSubsanados=$ce->obtenerFlujosDelTramite($conexion,'subsanarDossier',"'N'",$tramiteFlujo['id_tramite']);
				$cc = new ControladorCatalogos();
				$finalizacionTramite = false;
				foreach($flujosSubsanados as $item){
					$calculoFechaFin = $cc->obtenerFechaFinalDiasLaborables($item['fecha_inicio'], $plazo);
					if(strtotime($item['fecha_fin']) > strtotime($calculoFechaFin)){
						$finalizacionTramite = true;
					}
					
					/*$fechaIni=new DateTime($item['fecha_inicio']);
					$fechaFin=new DateTime($item['fecha_fin']);
					$fechaIntervalo=$fechaFin->diff($fechaIni);
					if($fechaIntervalo->days !==FALSE){
						$plazo=$plazo-$fechaIntervalo->days;
					}*/
				}
				$ident=$tramiteFlujo['operador'];				
				$datoMensaje['resultado']='O';
				$asuntoCorreo='Dossier Observado';
				$enviarCorreo=true;
				$esta_procesado=true;
				
				//se terminó el plazo para subsanar y termina el flujo del trámite
				//if($plazo<0){
				if($finalizacionTramite){
					$esta_procesado=false;
					$observacionTerminar='Trámite finalizado por sobrepasar el tiempo asignado '.$plazoTotal.' días';

					$generadorProceso=new GeneradorProcesoPecuario();
					

					$asuntoCorreo=$observacionTerminar;
					$datosDocumento['estado']='rechazado';

					$mensaje=$generadorProceso->generarTerminacionFlujo($conexion,$ce,$cp,$tramiteFlujo['id_tramite'],$id_tramite_flujo,$datosDocumento,$adjuntos,$destinatarios,$observacionTerminar,$retraso,'C',$fecha);
																	
				}

			}
			else {
				$plazo=$flujo->Plazo();
				if($perfil->tieneEstePerfil('PFL_DP_DRIP')){
					$ident=$flujo->PerfilActual();
					
				}
				else{
					$ident=$tramiteFlujo['operador'];
					$pendiente='A';
					$actualizarEstadoTramite=true;
					$estadoTramite='A';
					$datoMensaje['resultado']='A';
					$asuntoCorreo='Dossier Aprobado';
					$finTramite=true;
					$enviarCorreo=true;
					$generarDossier=true;

				}
				$esta_procesado=true;
			}
			if($plazo>0){
				$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS	
			}			
			$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');

			$ident_ejecutor=$tramiteFlujo['ejecutor'];
			

			break;
			
		case 'guardarSubsanacionesSolicitud':

		   $tipo_documento='DP';
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramiteFlujo['id_flujo_documento']);

		   $datos=array();
			$observacion='Observaciones subsanadas';
			
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
		               $observacion=$observacion.' ('.$item.')';
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
		   
			$plazo=$cp->obtenerPlazoDesdeFlujo($conexion,$id_tramite_flujo,$flujo);

		   $fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo DIAS
		   $fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
		   //*************** RETORNO EL TRAMITE Al tecnico QUIEN le ENVIÓ ********
			$ident_ejecutor=$tramiteFlujo['ejecutor'];
			$pendiente=$tramiteFlujo['pendiente'];
		   $ident=$ident_ejecutor;
			$generarDossier=true;		//regenera el dossier con los datos de las modificaciones


		   break;

		case 'enviarCorreo':

		   $cMail=new ControladorMail();
		   $asunto = $_POST['asuntoCorreo'];
		   $salidaReporte = $_POST['rutaAdjunto'];
		   $cuerpoCorreo='';
		   $fechaActual=new DateTime();
		   $cuerpoMensaje=$cp->redactarNotificacionEmailPC($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);

		   $destinatario = array();
		   $datos=$cp->obtenerSolicitud($conexion,$id_documento );
		   array_push($destinatario, $datos['email_representante_legal']);
		   $adjuntos = array();
		   if($salidaReporte!=null)
		      array_push($adjuntos, $rutaArchivoFirmado, $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte);

		   echo IN_MSG . 'Insertar registro de envío de correo electronico.';

		   $codigoModulo = 'PRG_DOSSIER_PEC';
		   $tablaModulo = '';
		   $idSolicitudTabla='';	// $solicitudPendiente['id_comprobante']

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
		$datosDocumento['estado'] = $flujo->EstadoActual();
		$datosDocumento['id_flujo_documento'] = $flujo->Flujo_documento();
		try{
			if($flujo->EstadoActual()=='pago'){
				$flujo->CambiarSelector('tramite_destino');
				if($ident=='PFL_RES_CENTRAL'){
					$flujo=$flujo->BuscarFaseSiguienteConCondicion($condicion,null,null,'planta_central');
				}
				else{
					$flujo=$flujo->BuscarFaseSiguienteConCondicion($condicion,null,null);
				}
			}
			$conexion->Begin();

			//actualiza estado del documento
			$ce->actualizarTramiteIdentificador($conexion,$id_tramite,$ident);
			
			$ce->actualizarTramiteFlujoEstado($conexion,$id_tramite_flujo,'N',$fecha,null,$retraso,$pendiente,$identificador);	//Cierra la fase del tramite
			
			$ce->guardarFlujoDelTramite($conexion,null,$id_tramite,$flujo->Flujo_documento(),$ident,$identificador,$ident_ejecutor,$pendiente,$fecha,$fechaSubsanacion,$observacion,$plazo, $archivo);
			if($actualizarEstadoTramite){
				$ce->actualizarTramiteEstado($conexion,$id_tramite,$observacion,$estadoTramite,$fechaSubsanacion, $archivo);
			}
			
			$fechaInscripcion=new DateTime();
			$fechaInscripcion = $fechaInscripcion->format('Y-m-d');
			if($finTramite){
				//Genera Certificado
				$cGenerador=new GeneradorDocumentoPecuario();
				$cu=new ControladorUsuarios();
				$datosAprobador=pg_fetch_assoc( $cu->obtenerAreaUsuario($conexion,$identificador),0);
				$nombresModoAccion=pg_fetch_assoc($cu->obtenerNombresUsuario($conexion,$identificador),0);
				$firmante=$nombresModoAccion['nombre'].' '.$nombresModoAccion['apellido'];
				$firmanteCargo=$datosAprobador['nombre'];
				$datosCertificado=$cGenerador->generarCertificado($conexion,$datosDocumento['id_solicitud'],$firmante,$firmanteCargo);
				$datosDocumento['id_certificado']=$datosCertificado['id_certificado'];
				$datosDocumento['ruta_certificado']=$datosCertificado['datos'];
				
				$datosDocumento['fecha_inscripcion']=$fechaInscripcion;
				//Genera Puntos minimos de etiqueta
				$datosEtiqueta=$cGenerador->generarPuntosMinimos($conexion,$datosDocumento['id_solicitud'],$firmante,$firmanteCargo,$datosCertificado['id_certificado']);
				$datosDocumento['ruta_etiqueta']=$datosEtiqueta['datos'];

			}
			$cp -> guardarSolicitud($conexion,$datosDocumento);
			//genera 
			if($generarDossier){
				$cGenerador=new GeneradorDocumentoPecuario();
				$respuesta=$cGenerador->generarDossier($conexion,$id_documento,false);
				$datosDocumento['ruta_dossier']=$respuesta['datos'];
				$cp -> guardarSolicitud($conexion,$datosDocumento);
				array_push($adjuntos,$respuesta['rutaFisica']);
			}
			if($finTramite){
				//Sube el producto al modulo GUIA
				$tipo_aplicacion = ($_SESSION['idAplicacion']);			
				$datosUsuario=$_SESSION['datosUsuario'];
				$subido=$cGenerador->subirProducto($conexion,$datosDocumento['id_solicitud'],$datosCertificado['id_certificado'],$fechaInscripcion,$tipo_aplicacion,$identificador,$datosUsuario);
			}
			if($enviarCorreo){

				$fechaActual=new DateTime();
				$cuerpoMensaje=$cp->redactarNotificacionEmailPC($conexion,$id_tramite,$fechaActual->format('Y-m-d H:i'),$asuntoCorreo);
				array_push($destinatarios,$cuerpoMensaje['datos']['email_representante_legal']);				
				$codigoModulo = 'PRG_DOSSIER_PEC';
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
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error en la transacción de guardado';
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