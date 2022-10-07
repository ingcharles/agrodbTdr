<?php

require_once 'Conexion.php';
require_once 'ControladorRegistroOperador.php';

require_once 'ControladorUsuarios.php';
require_once 'ControladorAplicaciones.php';
require_once 'ControladorCatalogos.php';
require_once 'ControladorRequisitos.php';
require_once 'ControladorImportaciones.php';
require_once 'ControladorRevisionSolicitudesVUE.php';
require_once 'ControladorFitosanitario.php';
require_once 'ControladorDestinacionAduanera.php';
require_once 'ControladorZoosanitarioExportacion.php';
require_once 'ControladorClv.php';
require_once 'ControladorCertificados.php';
require_once 'ControladorProtocolos.php';
require_once 'ControladorFinanciero.php';
require_once 'ControladorFinancieroAutomatico.php';
require_once 'ControladorFitosanitarioExportacion.php';
require_once 'ControladorTransitoInternacional.php';													
require_once 'ControladorReportes.php';	
//require_once '../general/webServicesBanano.php';
require_once '../general/crearReporteRequisitos.php';
require_once '../general/administrarArchivoFTP.php';

//PROCESAMIENTO DE DOCUMENTOS
//revisión de CODIGO DE DISTRIBUICION DE DOCUMENTO (Pág. 13)
define('SOLICITUD_ENVIADA',110); //*
define('PAGO_AUTORIZADO',120);
define('PAGO_CONFIRMADO',130); //*
define('MODIFICACION_SOLICITADA',140);
define('CORRECCION_SOLICITADA',150);
define('INFORME_ENVIADO',160);
define('SOLICITUD_RECEPTADA',210);
define('SOLICITUD_NO_APROBADA',310);
define('SOLICITUD_APROBADA',320);
define('RECTITICACION_REALIZADA',330);
define('SOLICITUD_DE_CORRECION_APROBADA',340);
define('SOLICITUD_DE_CORRECION_NO_APROBADA',350);
define('SUBSANACION_REQUERIDA',410); //*
define('SUBSANACION_ENVIADA',420); //*
define('AUCP_ENVIADO_A_ADUANA',510); //*
define('RESULTADO_DE_INSPECCION_RECIBIDO_POR_INSTITUCION',520);
define('RESULTADO_DE_AFORO_RECIBIDO_POR_OCP_OCE',530);
define('DESISTIMIENTO_SOLICITADO',610);
define('DESISTIMIENTO_APROBADO',620);
define('REVOCACION_APROBADA',630);
define('ANULACION_SOLICITADA',640);
define('ANULACION_APROBADA',650);
define('ERROR_DE_VALIDACION',910);
define('INSPECCION_PROGRAMADA', 230);
define('INSPECCION_REALIZADA', 240);

//NOTIFICACIONES
//revisión de CODIGO DE DISTRIBUICION DE DOCUMENTO (Pág. 15)
define('SOLICITUD_DE_TAREA',11); //Esto pone VUE
define('FIN_TAREA',12);
define('ERROR_TAREA',13);
define('SOLICITUD_DE_EVIO_VUE',21);
define('FIN_ENVIO_VUE',22); //Esto pone VUE
define('ERROR_ENVIO_VUE',23); //Esto pone VUE

define('IN_MSG','<br/> >>> ');
define('OUT_MSG','<br/> <<< ');
define('PRO_MSG', '<br/> ... ');

/***************************************************************************************************************************/
/***************************************************************************************************************************/
class ControladorVUE{

	private $parametrosConexionVUE = array();
	private $parametrosConexionGUIA = array();
	private $conexionVUE;


	public function __construct(){
		$this->conexionVUE = new Conexion('192.168.200.9','5432','Solicitudes_Dev','postgres','Agrocalidad2022.');
	}
	
	public function cargarSolicitudesPorAtenderPagoAnticipado(){
		$estado = " estado not in ('Atendida', 'W')";
				
		$solicitudesPorAtender = $this->conexionVUE->ejecutarConsulta("SELECT 
																			*
																		FROM 
																			agrocalidad.pago_anticipado
																		WHERE 
																			$estado");
		return $solicitudesPorAtender;
	}
	
	public function actualizarObservacionPagoAnticipado($observacion, $idPagoAnticipado){
			
		$res = $this->conexionVUE->ejecutarConsulta("UPDATE
														agrocalidad.pago_anticipado
													SET
														observacion = '$observacion'
													WHERE
														id_pago_anticipado = $idPagoAnticipado");
				return $res;
	}
	
	public function actualizarObservacionSolicitud($conexionVUE, $observacion, $id){
			
		$res = $this->conexionVUE->ejecutarConsulta("UPDATE
														agrocalidad.solicitudes_atender
													SET
														observacion = '$observacion'
													WHERE
														id = $id");
		return $res;
	}

	public function cargarSolicitudesPorAtenderVUE($conexionVUE){
		//$estado = 'Por atender'; //estado de solicitudes pendientes
		$estado = " estado not in ('Atendida', 'W','Reverso')";
		$solicitudesPorAtender = $this->conexionVUE->ejecutarConsulta("	SELECT *
																		FROM agrocalidad.solicitudes_atender
																		WHERE $estado");
		
		return $solicitudesPorAtender;
	}
	
	public function cargarSolicitudesPorAtenderVUEReverso($conexionVUE){
		//$estado = 'Por atender'; //estado de solicitudes pendientes
		$estado = " estado in ('Reverso') and codigo_procesamiento = '120' and codigo_verificacion = '11'";
		$solicitudesPorAtender = $this->conexionVUE->ejecutarConsulta("	SELECT 
																			*
																		FROM 
																			agrocalidad.solicitudes_atender
																		WHERE $estado");
	
		return $solicitudesPorAtender;
	}
	
	public function cargarSolicitudesPorAtenderVUEReversoPorIdentificadorVUE($conexionVUE, $solicitud){
	
		$solicitudesPorAtender = $this->conexionVUE->ejecutarConsulta("	SELECT
																			*
																		FROM
																			agrocalidad.solicitudes_atender
																		WHERE 
																			solicitud = '$solicitud' 
																			and estado in ('Reverso') 
																			and codigo_procesamiento = '120' 
																			and codigo_verificacion = '11'");
	
				return $solicitudesPorAtender;
	}
	
	public function cargarSolicitudesPorAtenderGUIA(){
		
		$conexionGUIA = new Conexion();
				
		$solicitudesPorAtenderGUIA = $conexionGUIA->ejecutarConsulta("SELECT 
																			*
																	  FROM 
																			g_vue.solicitudes_atender
																	  WHERE 
																			estado = 'Por atender';");
	
		return $solicitudesPorAtenderGUIA;
	}
	
	public function cambiarSolicitudesPendientesAPorAtenderGUIA(){
	
		$conexionGUIA = new Conexion();
	
		$solicitudesPorAtenderGUIA = $conexionGUIA->ejecutarConsulta("UPDATE 
																		g_vue.solicitudes_atender
																	SET
																		estado = 'Por atender'								
																	WHERE 
																		estado = 'Pendiente';");
	
		return $solicitudesPorAtenderGUIA;
	}

	public function finalizar($solicitudPendiente, $nuevoEstado){

		//$nuevoEstado = 'Atendida';
		$this->conexionVUE->ejecutarConsulta("
											UPDATE 
												agrocalidad.solicitudes_atender
											SET 
												estado = '$nuevoEstado'
											WHERE 
												id = " .$solicitudPendiente->id().";");
	}
	
	public function actualizarEstadoPorIdentificadorVUE($solicitud, $observacion, $estadoAnterior ,$nuevoEstado){
	
		$this->conexionVUE->ejecutarConsulta("UPDATE
													agrocalidad.solicitudes_atender
												SET
													estado = '$nuevoEstado',
													observacion = '$observacion'
												WHERE
													solicitud = '$solicitud'
													and estado = '$estadoAnterior';");
	}
	
	public function finalizarPagoAnticipado($idSolicitud, $nuevoEstado){
	
		//$nuevoEstado = 'Atendida';
		$this->conexionVUE->ejecutarConsulta("
											UPDATE
												agrocalidad.pago_anticipado
											SET
												estado = '$nuevoEstado'
											WHERE
												id_pago_anticipado = $idSolicitud;");
	}
	
	public function finalizarGUIA($idSolcitud, $nuevoEstado){
	
		$conexionGUIA = new Conexion();
		
		$conexionGUIA->ejecutarConsulta("
										UPDATE 
											g_vue.solicitudes_atender
										SET 
											estado = '$nuevoEstado'
										WHERE 
											id = " .$idSolcitud.";");
	}

	public function instanciarFormulario($formulario){
		echo $formulario['formulario'];
		switch (substr($formulario['formulario'],0,7)){
			case '101-001':
				return new RegistroOperador($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
				break;
			case '101-002':
				return new Importaciones($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
				break;
			case '101-024':
				return new DDA($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
				break;
			case '101-008':
				return new Zoosanitario($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
				break;
			case '101-031':
				return new Fitosanitario($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
				break;
			case '101-047':
				return new CLV($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
				break;
			case '101-034':
				return new FitosanitarioExportacion($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
				break;
			case '101-061':
			    return new TransitoInternacional($formulario['formulario'],$formulario['id'],$formulario['codigo_procesamiento'],$formulario['codigo_verificacion'],$formulario['solicitud'], $this->conexionVUE);
			    break;
			default:
				return null;
		}
	}

	public function actualizarEstadoVUE($formulario, $nuevoEstadoProcesamiento, $nuevoCodigoVerificacion, $mensaje, $usuario = array('id' => '101','nombre' => 'G.U.I.A.')){
		//TODO: actualizar datos en last state
		$update = true;
		$impresion = '';

		//////////////////////////////////////////////////////
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$conexionGUIA = new Conexion();
		/////////////////////////////////////////////////////


		/****************/

		$codigoFormulario = $formulario->formulario;
		$organizacion = '101';
		$estadoProcesamiento = $nuevoEstadoProcesamiento;
		$estadoVerificacion = $nuevoCodigoVerificacion;
		$solicitud = $formulario->numeroDeSolicitud;
		$idUsuario = $usuario['id'];
		$nombreUsuario = $usuario['nombre'];
		$observacion = $mensaje;


		/****************/
			
		switch ($nuevoEstadoProcesamiento){

			case SOLICITUD_ENVIADA: //Probado
				$update = true;
				echo OUT_MSG . 'Fin tarea de envío';
				break;

			case PAGO_CONFIRMADO:
				//$update= false;
				$update= true;
				$impresion = OUT_MSG . 'Envío de fin de tarea pago confirmado a VUE';
				break; 

			case SOLICITUD_RECEPTADA: //Probado
				$update= false;
				$impresion = OUT_MSG . 'Envío de solicitud receptada a VUE';
				break;

			case SOLICITUD_APROBADA: //Aprobado
				$update= true;
				$impresion = OUT_MSG . 'Envío de solicitud aprobada a VUE';
				$codigoFormulario = substr($formulario->formulario,0,7).'-RES';
				break;
			
			case SOLICITUD_NO_APROBADA: // Aprobado
				$impresion = OUT_MSG . 'Envío de solicitud no aprobada a VUE';
				$update = false;
				break;
 
			case SUBSANACION_REQUERIDA: //Aprobado
				$update = false;
				$impresion = IN_MSG . 'Solicitud de subsanación requerida';
				break;
					
			case SUBSANACION_ENVIADA: // Aprobado
				$update = true;
				$impresion = OUT_MSG . 'Envío de solicitud de subsanación a VUE';
				break;
					
			case SOLICITUD_DE_CORRECION_NO_APROBADA: // Aprobado
				$impresion = OUT_MSG . 'Envío de solicitud de corrección no aprobada a VUE';
				$update = false;
				break;
					
			case SOLICITUD_DE_CORRECION_APROBADA: //Aprobado
				$impresion = OUT_MSG . 'Envío de solicitud de correción aprobada a VUE';
				$codigoFormulario = substr($formulario->formulario,0,7).'-RES';
				$update = true;
				break;
					 
			case DESISTIMIENTO_APROBADO: //Aprobado
				$update = false;
				$impresion = OUT_MSG . 'Envío de solicitud de desistimiento aprobada a VUE';
				break;
					
			case RECTITICACION_REALIZADA: // No probado
				$update = true;
				$impresion = IN_MSG . 'Envío de solicitud de rectificación solicitada';
				$codigoFormulario = substr($formulario->formulario,0,7).'-RES';
				break;
					
			case CORRECCION_SOLICITADA: // Aprobado
				$update = true;
				$impresion = IN_MSG . 'Envío de solicitud de corrección solicitada';
				break;
					
			case ANULACION_APROBADA: //Aprobado
				$update = false;
				$impresion = OUT_MSG . 'Envío de solicitud de de anulación  aprobada a VUE';
				break;
			
			case PAGO_AUTORIZADO: // No probado
				$update = true;
				$impresion = IN_MSG . 'Envío de pago autorizado';
				break;
			
			case ANULACION_SOLICITADA: // Aprobado
				$update = true;
				$impresion = IN_MSG . 'Envío de fin de anulacion solicitada';
				break;
			
			case DESISTIMIENTO_SOLICITADO: // Aprobado
				$update = true;
				$impresion = IN_MSG . 'Envío de fin de desestimiento solicitada';
				break;
			
			case INSPECCION_PROGRAMADA: //Aprobado
				$update = false;
				$impresion = OUT_MSG . 'Envío de solicitud de inspección programada a VUE';
			break;
				
			case INSPECCION_REALIZADA: //Aprobado
				$update = false;
				$impresion = OUT_MSG . 'Envío de solicitud de inspección realizada a VUE';
			break;
					
			default:
				$update = false;

		}

		if ($update){

			//update tn_eld_edc_last_state
			$res = $this->conexionVUE->ejecutarConsulta("UPDATE vue_gateway.tn_eld_edoc_last_stat SET
																dcm_cd= '$codigoFormulario',     --- Código de Formulario (Certificado)
																orgz_cd= $organizacion,    --- Código de la Entidad AGROCALIDAD
																afr_prst_cd= $estadoProcesamiento,    --- Valor de Código de Aprobación
																ntfc_cfm_cd= $estadoVerificacion,   --- Estado de Espera de Batch
																trss_nbtm = 0
														WHERE
																req_no = '$solicitud';");

		} else {

			//insert tn_eld_ntfc
			$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.TN_ELD_NTFC (
					REQ_NO, NTFC_CL_CD, ORD_NO,
					RCP_NO, RCP_DE, NTFC_DT,
					NTCP_NM, NTFC_CTXT, NTFC_CFM_CD,
					ORGZ_CD, use_fg, rgs_dt,
					rgsp_id, mdf_dt, mdfr_id
			)
					VALUES(
					'$solicitud',
					'$estadoProcesamiento',
					(SELECT CASE WHEN ((SELECT CAST(COUNT(ORD_NO) AS NUMERIC(5)) FROM vue_gateway.TN_ELD_NTFC WHERE REQ_NO ='$solicitud') = 0) THEN 1
					ELSE ( SELECT MAX(CAST(ORD_NO AS NUMERIC(5)))+1 FROM vue_gateway.TN_ELD_NTFC WHERE REQ_NO = '$solicitud') END ),
					'Orden',
					now(),
					now(),
					'$idUsuario',
					'$observacion',
					'$estadoVerificacion',
					'$organizacion',
					'S',
					now(),
					'$nombreUsuario',
					now(),
					''
			);");
		}

		echo $impresion;

	}

	public function aprobarSolicitud($formulario, $respuesta){
		$this->envioCamposRespuesta($formulario);
		$this->actualizarEstadoVUE($formulario, SOLICITUD_APROBADA, SOLICITUD_DE_EVIO_VUE,$respuesta);
	}
	
	public function modificarSolicitud($formulario, $respuesta){
		$this->actualizarCamposRespuesta($formulario);
		$this->actualizarEstadoVUE($formulario, SOLICITUD_DE_CORRECION_APROBADA, SOLICITUD_DE_EVIO_VUE,$respuesta);
	}

	public function solicitudCorreccionNoAprobada($formulario, $respuesta){
		//$this->envioCamposRespuesta($formulario);
		$this->actualizarCamposRespuesta($formulario);
		$this->actualizarEstadoVUE($formulario,SOLICITUD_DE_CORRECION_NO_APROBADA,SOLICITUD_DE_EVIO_VUE, $respuesta);
	}
	
	public function rectificarSolicitud($formulario){
		$this->rectificarCamposRespuesta($formulario);
	}

	public function solicitudCorreccionAprobada($formulario, $respuesta){
		
		switch (substr($formulario->formulario,0,7)){
			
			case '101-001':
				$this->actualizarCamposRespuesta($formulario);
			break;
			
			case '101-002':
				$this->actualizarCamposRespuesta($formulario);
				$this->actualizarAUCP($formulario, SOLICITUD_DE_CORRECION_APROBADA);
				$this->cambioEstadoAUCP($formulario, '35');
			break;
			case '101-031':
				$this->actualizarCamposRespuesta($formulario);
			break;
			case '101-034':
				$this->actualizarCamposRespuesta($formulario);
			break;			
			case '101-061':
			    $this->actualizarCamposRespuesta($formulario);
			    $this->actualizarAUCP($formulario, SOLICITUD_DE_CORRECION_APROBADA);
			    $this->cambioEstadoAUCP($formulario, '35');
			break;			    
			default:
				echo 'Formulario desconocido';
		}
		
		$this->actualizarEstadoVUE($formulario, SOLICITUD_DE_CORRECION_APROBADA, SOLICITUD_DE_EVIO_VUE,$respuesta);
	}
	
	public function crearAnexoRequisitos($formulario){
		
		$pdf = new PDF();
		$conexionGUIA = new Conexion();
		$cFTP = new administrarArchivoFTP();		
		
		switch (substr($formulario->formulario,0,7)){
			case '101-002':
			
				$controladorImportaciones = new ControladorImportaciones();
				
				$solicitud = $formulario->numeroDeSolicitud;
				$identificadorImportador = $formulario->f002['impr_idt_no'];
				
				$idImportacion = pg_fetch_result($controladorImportaciones->buscarImportacionVUE($conexionGUIA, $solicitud),0,'id_importacion');
				
				$numeroAleatorio = rand(0,99999999);
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->Body($idImportacion, 'Importación');
				$pdf->SetFont('Times','',12);
				//$pdf->Output("../importaciones/archivosRequisitos/".$identificadorImportador."-".$idImportacion.$numeroAleatorio.".pdf");
				$pdf->Output("../importaciones/archivosRequisitos/".$solicitud.".pdf");
					
				//$informeRequisitos = "aplicaciones/importaciones/archivosRequisitos/".$identificadorImportador."-".$idImportacion.$numeroAleatorio.".pdf";
				$informeRequisitos = "aplicaciones/importaciones/archivosRequisitos/".$solicitud.".pdf";
					
				//Actualizar registro de archivo  adjunto
				$controladorImportaciones->asignarDocumentoRequisitosImportacion($conexionGUIA, $idImportacion, $informeRequisitos);
				
				//Enviar archivo adjunto VUE 
				//$cFTP->enviarArchivo($informeRequisitos, $identificadorImportador.'-'.$idImportacion.$numeroAleatorio.'.pdf', 'importacion');
				$cFTP->enviarArchivo($informeRequisitos, $solicitud.'.pdf', 'importacion');
					
			break;
			
			case '101-008':
				
				$controladorZoosanitarioExportacion = new ControladorZoosanitarioExportacion();
				
				$solicitud = $formulario->numeroDeSolicitud;
				$identificadorZOO = $formulario->f008['expr_idt_no'];
				
				$idZOO = pg_fetch_result($controladorZoosanitarioExportacion -> buscarZooVUE($conexionGUIA,$identificadorZOO,$solicitud),0,'id_zoo_exportacion');
				
				$numeroAleatorio = rand(0,99999999);
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->Detalle($idZOO,'Zoosanitario');
				$pdf->SetFont('Times','',12);
				//$pdf->Output("../exportacionZoosanitario/archivosRequisitos/".$identificadorZOO.'-'.$idZOO.$numeroAleatorio.".pdf");
				$pdf->Output("../exportacionZoosanitario/archivosRequisitos/".$solicitud.".pdf");
					
				//$informeRequisitos = "aplicaciones/exportacionZoosanitario/archivosRequisitos/".$identificadorZOO.'-'.$idZOO.$numeroAleatorio.".pdf";
				$informeRequisitos = "aplicaciones/exportacionZoosanitario/archivosRequisitos/".$solicitud.".pdf";
					
				//Actualizar registro
				$controladorZoosanitarioExportacion->asignarDocumentoRequisitosZoosanitario($conexionGUIA, $idZOO, $informeRequisitos);
				
				//Enviar archivo adjunto VUE
				//$cFTP->enviarArchivo($informeRequisitos, $identificadorZOO.'-'.$idZOO.$numeroAleatorio.'.pdf', 'zoosanitario');
				$cFTP->enviarArchivo($informeRequisitos, $solicitud.'.pdf', 'zoosanitario');
			
			break;
			
			case '101-031':
				$controladorFitosanitario = new ControladorFitosanitario();
								
				$solicitud = $formulario->numeroDeSolicitud;
				
				$qIdFitosanitario = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $solicitud);
				$idFitosanitario = pg_fetch_result($qIdFitosanitario,0,'id_fito_exportacion');
				
				$pdf->AliasNbPages();
				$pdf->AddPage();
				$pdf->Detalle($idFitosanitario,'Fitosanitario');
				$pdf->SetFont('Times','',12);
				//$pdf->Output("../exportacionFitosanitario/archivosRequisitos/".$idFitosanitario.".pdf");
				$pdf->Output("../exportacionFitosanitario/archivosRequisitos/".$solicitud.".pdf");
					
				//$informeRequisitos = "aplicaciones/exportacionFitosanitario/archivosRequisitos/".$idFitosanitario.".pdf";
				$informeRequisitos = "aplicaciones/exportacionFitosanitario/archivosRequisitos/".$solicitud.".pdf";
					
				//Actualizar registro
				$controladorFitosanitario->asignarDocumentoRequisitosFitosanitario($conexionGUIA, $idFitosanitario, $informeRequisitos);
				
				//Enviar archivo adjunto VUE
				//$cFTP->enviarArchivo($informeRequisitos, $idFitosanitario.'.pdf', 'fitosanitario');
				$cFTP->enviarArchivo($informeRequisitos, $solicitud.'.pdf', 'fitosanitario');
				
			break;
			
			case '101-061':
                
                $controladorTransitoInternacional = new ControladorTransitoInternacional();
                
                $solicitud = $formulario->numeroDeSolicitud;
                $identificadorImportador = $formulario->f061['impr_idt_no'];
                
                $idTransito = pg_fetch_result($controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $solicitud),0,'id_transito_internacional');
                
                //Documento FPDF
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->Body($idTransito,'TransitoInternacional');
                $pdf->SetFont('Times','',12);
                
                
                //$pdf->Output("../exportacionFitosanitario/archivosRequisitos/".$idFitosanitario.".pdf");
                $pdf->Output("../transitoInternacional/archivos/TransitoInternacional_".$idTransito.".pdf");
                
                //$informeRequisitos = "aplicaciones/exportacionFitosanitario/archivosRequisitos/".$idFitosanitario.".pdf";
                $informeRequisitos = "aplicaciones/transitoInternacional/archivos/TransitoInternacional_".$idTransito.".pdf";
                
                //Actualizar registro
                $controladorTransitoInternacional->guardarRutaCertificado($conexionGUIA, $idTransito, $informeRequisitos);
                
                $informeRequisitos = "aplicaciones/transitoInternacional/archivos/TransitoInternacional_".$idTransito.".pdf";
                
                //Enviar archivo adjunto VUE
                $cFTP->enviarArchivo($informeRequisitos, 'TransitoInternacional_'.$idTransito.'.pdf', 'TransitoInternacional');
                
                break;
			
		default:
			echo 'No se encontro observación';
		}
		
	}
	
	/*public function obtenerObservacionRevisionDocumental($formulario){
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$conexionGUIA = new Conexion();
		
		$solicitud = $formulario->numeroDeSolicitud;
		
		switch (substr($formulario->formulario,0,7)){
			case '101-002':
				
				$controladorImportaciones = new ControladorImportaciones();
				$identificadorImportador = $this->f002['impr_idt_no'];
				$idImportacion = pg_fetch_result($controladorImportaciones->buscarImportacionVUE($conexionGUIA, $identificadorImportador, $this->f002['req_no']),0,'id_importacion');
				
				
				
				
			break;
			default:
				echo 'No se encontro observación';
		}
		
		return $revisonDocumental;
	}*/

	public function guardarTasa($formulario){
		
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$conexionGUIA = new Conexion();
		$controladorCertificadoFinanciero = new ControladorCertificados();
		
		$solicitud = $formulario->numeroDeSolicitud;

		switch (substr($formulario->formulario,0,7)){
			case '101-002':
				
				$controladorImportaciones = new ControladorImportaciones();
	
				$identificadorImportador = $formulario->f002['impr_idt_no'];		
		
				$qIdImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $solicitud);
				$idImportacion = pg_fetch_result($qIdImportacion,0,'id_importacion');
				
				$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idImportacion, 'Importación', 'Financiero');
				$financiero = pg_fetch_assoc($qFinanciero);
				
				$tipoSolicitudC = 'Importación';
				$idSolicitudC = $idImportacion;
				$estadoFactura = 3;
				
			break;
			case '101-008':
					
				$controladorZoosanitario = new ControladorZoosanitarioExportacion();
				
				$qIdZoosanitario = $controladorZoosanitario->buscarZooVUE($conexionGUIA, $formulario->f008['expr_idt_no'], $solicitud);	
				$idZoosanitario = pg_fetch_result($qIdZoosanitario,0,'id_zoo_exportacion');
					
				$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idZoosanitario, 'Zoosanitario', 'Financiero');
				$financiero = pg_fetch_assoc($qFinanciero);
				
				$tipoSolicitudC = 'Zoosanitario';
				$idSolicitudC = $idZoosanitario;
				$estadoFactura = 3;
					
			break;
			case '101-031':
			
				$controladorFitosanitario = new ControladorFitosanitario();
			
				$qIdFitosanitario = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $solicitud);
				$idFitosanitario = pg_fetch_result($qIdFitosanitario,0,'id_fito_exportacion');
			
				$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idFitosanitario, 'Fitosanitario', 'Financiero');
				$financiero = pg_fetch_assoc($qFinanciero);
				
				$tipoSolicitudC = 'Fitosanitario';
				$idSolicitudC = $idFitosanitario;
				$estadoFactura = 3;
			
			break;
			case '101-047':
			
				$controladorCLV = new ControladorClv();
			
				$qIdClv = $controladorCLV->buscarClvVUE($conexionGUIA, $solicitud);
				$idClv = pg_fetch_result($qIdClv,0,'id_clv');
			
				$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idClv, 'CLV', 'Financiero');
				$financiero = pg_fetch_assoc($qFinanciero);
				
				$tipoSolicitudC = 'CLV';
				$idSolicitudC = $idClv;
				$estadoFactura = 3;
			
			break;
			
			case '101-034':
					
				$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
				
				$qIdFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $solicitud);
				$idFitosanitarioExportacion = pg_fetch_assoc($qIdFitosanitarioExportacion);
					
				$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], 'FitosanitarioExportacion', 'Financiero');
				$financiero = pg_fetch_assoc($qFinanciero);
			
				$tipoSolicitudC = 'FitosanitarioExportacion';
				$idSolicitudC = $idFitosanitarioExportacion['id_fitosanitario_exportacion'];
				
				if($idFitosanitarioExportacion['estado']== 'verificacionAutomatica'){
					$estadoFactura = 5;
				}else{
					$estadoFactura = 3;
				}
				
					
			break;
				
			default:
				echo 'Desconocido en tasas';		
		}
				
		$qFacturacion = $controladorCertificadoFinanciero->obtenerIdOrdenPagoXtipoOperacion($conexionGUIA, $financiero['id_grupo'], $idSolicitudC, $tipoSolicitudC, $estadoFactura);
		$facturacion = pg_fetch_assoc($qFacturacion);
		
		$valorTotalFactura = pg_fetch_assoc($controladorCertificadoFinanciero->obtenerDatosDetalleFactura($conexionGUIA, $facturacion['id_pago']));
		
		$subTotal =  $valorTotalFactura['total_sin_iva'] + $valorTotalFactura['total_con_iva'];
		$iva = $valorTotalFactura['suma_iva'];
		
		$monto = $facturacion['total_pagar'];
		$responsable = $facturacion['identificador_usuario'];
		
		$res  = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_eld_taxt_imps_bkdn
															(	
																req_no, taxt_imps_cl_cd, ord_no,
																acnt_cd, fee_tot_pric, fee_pric, iva_pric, 
																fee_isud_de, fee_epdt, orgz_fee_no, orgz_cd, 
																ntfc_cfm_cd, use_fg, rgs_dt, rgsp_id
															)
														VALUES
															( 
																'$solicitud' ,'110',  
																(SELECT CASE WHEN ((SELECT CAST(COUNT(ORD_NO) AS NUMERIC(5)) FROM vue_gateway.tn_eld_taxt_imps_bkdn WHERE REQ_NO = '$solicitud') = 0) THEN 1
																ELSE (SELECT MAX(CAST(ORD_NO AS NUMERIC(5)))+1 FROM vue_gateway.tn_eld_taxt_imps_bkdn WHERE REQ_NO = '$solicitud') END),
																'300', $monto, $subTotal, $iva, 					
																now(), now() + '5 day', ('AGR' || CAST(to_char(now(),'yyymmddhh24missmsms') AS VARchar)),
																'101', '21', 'S', now(), '$responsable'
															);");
		
		$detalleFacturacion = $controladorCertificadoFinanciero->abrirDetallePago($conexionGUIA, $facturacion['id_pago']);
		
		while($fila = pg_fetch_assoc($detalleFacturacion)){
			
			$precioUnitario = $fila['precio_unitario'];
			$cantidad = $fila['cantidad'];
			$total = $fila['total'];
			$ivaUnitario = $fila['iva'];
			$descuento = $fila['descuento'];
			$concepto = (strlen($fila['concepto_orden'])>240?substr($fila['concepto_orden'],0,240):$fila['concepto_orden']);
			
			
			$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_eld_taxt_imps_det_bkdn
					(
					req_no, taxt_imps_cl_cd, ord_no, taxt_fee_sn,
					taxt_type_cd, unp, fld_qt, fee_pric, iva_pric,
					enrt_pric, enrt_motv, fee_fld_nm, use_fg,
					rgs_dt, rgsp_id, mdf_dt, mdfr_id
			)
					VALUES
					(
					'$solicitud' ,'110',
					(SELECT MAX(CAST(ORD_NO AS NUMERIC(5))) FROM vue_gateway.tn_eld_taxt_imps_bkdn WHERE REQ_NO = '$solicitud'),
					(SELECT CASE WHEN ((SELECT CAST(COUNT(taxt_fee_sn) AS NUMERIC(5)) FROM vue_gateway.tn_eld_taxt_imps_det_bkdn WHERE REQ_NO = '$solicitud') = 0) THEN 1
					ELSE (SELECT MAX(CAST(taxt_fee_sn AS NUMERIC(5)))+1 FROM vue_gateway.tn_eld_taxt_imps_det_bkdn WHERE REQ_NO = '$solicitud') END),
					'507', $precioUnitario , $cantidad , $total , $ivaUnitario,
					$descuento, null ,'$concepto',
					'S', now(), '$responsable', NULL,NULL
			);");
		}
		
		
		$this->actualizarEstadoVUE($formulario,PAGO_AUTORIZADO,SOLICITUD_DE_EVIO_VUE, 'Imposición de tasas');
		
		echo OUT_MSG. 'Envio valor de tasas a VUE.';
		

	}

	public function envioAUCP($formulario){
		
		switch (substr($formulario->formulario,0,7)){
			case '101-002':
				
				//if($formulario->f002['req_type_cd'] == '0001' || $formulario->f002['req_type_cd'] == '0002'){
				
				$controladorImportaciones = new ControladorImportaciones();
				$conexionGUIA = new Conexion();
				
				$qImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $formulario->f002['req_no']);
				$importacion = pg_fetch_assoc($qImportacion);
									
				$nombreImportador = str_replace("'", " ", $formulario->f002['impr_nm']);
				$razonSocialSolicitante = str_replace("'", " ", $formulario->f002['dclr_nole']);
				
				$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp
																			(
																	            req_no, dcm_cd, dcm_nm, dcm_func_cd, frtr_prcr_ctg_cd, atr_orgz_cd,
																	            atr_orgz_nm, atr_iss_city_cd, atr_iss_city_nm, req_de, ctft_no,
																	            ctft_iss_de, ctft_eftv_stdt, ctft_eftv_finl_de, dclr_cl_cd, dclr_idt_no_type_cd,
																	            dclr_idt_no, dclr_nm, dclr_prvhc_cd, dclr_prvhc_nm, dclr_cuty_cd,
																	            dclr_cuty_nm, dclr_prqi_cd, dclr_prqi_nm, dclr_ad, dclr_tel_no,
																	            dclr_em, impr_cl_cd, impr_idt_no_type_cd, impr_idt_no, impr_nm,
																	            impr_prvhc_cd, impr_prvhc_nm, impr_cuty_cd, impr_cuty_nm, impr_prqi_cd,
																	            impr_prqi_nm, impr_ad, imp_ntn_cd, imp_ntn_nm,
																	            aprb_rmk, use_fg, rgs_dt, rgsp_id, mdf_dt, mdfr_id, dclr_cmp_nm,
																				dcm_type_cd
																			)
																			VALUES
																			(
																				'".$formulario->f002['req_no']."', '".$formulario->f002['dcm_no']."', '".$formulario->f002['dcm_nm']."', '9', 'IMP', '101', 'AGR',
																				'".$formulario->f002['req_city_cd']."', '".$formulario->f002['req_city_nm']."', '".$formulario->f002['req_de']."', '".$formulario->f002['req_no']."',
																				'".$importacion['fecha_inicio']."', '".$importacion['fecha_inicio']."', '".$importacion['fecha_vigencia']."',
																				'".$formulario->f002['impr_cl_cd']."','', '".$formulario->f002['dclr_idt_no']."',
																				'".$razonSocialSolicitante."', '".$formulario->f002['dclr_prvhc_cd']."', '".$formulario->f002['dclr_prvhc_nm']."', '".$formulario->f002['dclr_cuty_cd']."',
																				'".$formulario->f002['dclr_cuty_nm']."', '".$formulario->f002['dclr_prqi_cd']."', '".$formulario->f002['dclr_prqi_nm']."', '".$formulario->f002['dclr_ad']."',
																				'".$formulario->f002['dcrl_tel_no']."', '".$formulario->f002['dclr_em']."', '".$formulario->f002['impr_cl_cd']."', '".$formulario->f002['impr_idt_no_type_cd']."',
																				'".$formulario->f002['impr_idt_no']."', '".$nombreImportador."', '".$formulario->f002['impr_prvhc_cd']."', '".$formulario->f002['impr_prvhc_nm']."',
																				'".$formulario->f002['impr_cuty_cd']."', '".$formulario->f002['impr_cuty_nm']."', '".$formulario->f002['impr_prqi_cd']."', '".$formulario->f002['impr_prqi_nm']."',
																				'".$formulario->f002['impr_ad']."', 'EC', 'ECUADOR', 'Sin observaciones',
																				'S', now(), '".$formulario->f002['rgsp_id']."', '".$formulario->f002['mdf_dt']."', '".$formulario->f002['mdfr_id']."', '".$razonSocialSolicitante."', 'IMP'
																			);");
								
					
					for ($i = 0; $i < count ($formulario->f002pd); $i++) {
						
					$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp_pd
																			(
																				req_no, prdt_sn, hc, prdt_nm, prdt_desc, prdt_nwt, prdt_nwt_ut,
																				prdt_pck_qt, prdt_pck_ut, use_fg, rgs_dt, rgsp_id, mdf_dt, mdfr_id,
																				mdfr_ip
																			)
																			VALUES
																			(
																				'".$formulario->f002pd[$i]['req_no']."', '".$formulario->f002pd[$i]['prdt_sn']."', '".$formulario->f002pd[$i]['hc']."',
																				'".$formulario->f002pd[$i]['prdt_nm']."', '', ".$formulario->f002pd[$i]['prdt_nwt'].", '".$formulario->f002pd[$i]['prdt_nwt_ut']."',
																				 ".$formulario->f002pd[$i]['prdt_qt'].", '".$formulario->f002pd[$i]['prdt_mes']."', 
																				'S', now(), '".$formulario->f002pd[$i]['rgsp_id']."', now(), '".$formulario->f002pd[$i]['mdfr_id']."', '".$formulario->f002pd[$i]['mdfr_ip']."'
																			);");
					
					}
					
					echo OUT_MSG. 'Envio de campos a AUCP';
					
			//	}else{
				//	echo OUT_MSG. 'No se envio de campos a AUCP';
			//	}				
				
			break;
			
			
			case '101-024':	
				
				$conexionGUIA = new Conexion();
				$controladorDDA = new ControladorDestinacionAduanera();
				$controladorCatalogos = new ControladorCatalogos();
				if($formulario->f024['req_type_cd'] == '01' || $formulario->f024['req_type_cd'] == '02'){
					
										
					//$referenciaCerificadoImportacion = $formulario->f024['imp_pht_prmt_no'];
						
					$qDdaProducto = $this->conexionVUE->ejecutarConsulta("SELECT * FROM vue_gateway.tn_agr_024_pd WHERE REQ_NO = '".$formulario->f024['req_no']."';");
						
					//$c_productoImportacion = $this->conexionVUE->ejecutarConsulta(" SELECT * FROM vue_gateway.tn_agr_002_pd WHERE REQ_NO = '$referenciaCerificadoImportacion';")
					$identificadorDDA = $formulario->f024['impr_idt_no'];
					$idVue = $formulario->f024['req_no'];
					
					$dda = pg_fetch_assoc($controladorDDA->buscarDDAVUE($conexionGUIA, $identificadorDDA, $idVue));
					$lugarInspeccion = pg_fetch_assoc($controladorCatalogos->buscarCatalogoLugarInspeccion($conexionGUIA, $formulario->f024['isp_plc_cd']));
					
					$nombreImportador = str_replace("'", " ", $formulario->f024['impr_nm']);
					

					//TODO: Verificar envío de observación
					$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp
																									(
																											req_no, dcm_cd, dcm_nm, dcm_func_cd, 
																											frtr_prcr_ctg_cd, dcm_type_cd, atr_orgz_cd, atr_orgz_nm,
																											atr_iss_city_cd, atr_iss_city_nm, req_de, ctft_no, 
																											ctft_iss_de,ctft_eftv_stdt, /*ctft_eftv_finl_de,*/ dclr_cl_cd, dclr_idt_no, dclr_cmp_nm,
																											dclr_nm, dclr_prvhc_cd, dclr_prvhc_nm, 
																											dclr_cuty_cd, dclr_cuty_nm, dclr_prqi_cd, dclr_prqi_nm, 
																											dclr_ad, dclr_tel_no, dclr_em, 
																											impr_cl_cd, impr_idt_no_type_cd, impr_idt_no, impr_nm, 
																											impr_prvhc_cd, impr_prvhc_nm, impr_cuty_cd, impr_cuty_nm, 
																											impr_prqi_cd, impr_prqi_nm, impr_ad, 										
																											aprb_rmk, use_fg, rgs_dt, rgsp_id, mdf_dt, mdfr_id
																										)
																										VALUES
																										(
																											'".$formulario->f024['req_no']."', '".$formulario->f024['dcm_no']."', 
																											'".$formulario->f024['dcm_nm']."', '9', 
																											'IMP', 'DDA', '101','AGR', '".$lugarInspeccion['codigo_ciudad_vue']."', '".$lugarInspeccion['nombre_ciudad_vue']."', 
																											'".$formulario->f024['req_de']."', '".$formulario->f024['req_no']."',																					
																											'".$dda['fecha_inicio']."','".$dda['fecha_inicio']."',/*now()+ '10 days' ,*/'".$formulario->f024['dclr_cl_cd']."',
																											'".$formulario->f024['dclr_idt_no']."', '".$formulario->f024['dclr_nole']."', 
																											'".$formulario->f024['dclr_nm']."',											
																											'".$formulario->f024['dclr_prvhc_cd']."', '".$formulario->f024['dclr_prvhc_nm']."', 
																											'".$formulario->f024['dclr_cuty_cd']."', '".$formulario->f024['dclr_cuty_nm']."', 
																											'".$formulario->f024['dclr_prqi_cd']."', '".$formulario->f024['dclr_prqi_nm']."', 
																											'".$formulario->f024['dclr_ad']."',	'".$formulario->f024['dcrl_tel_no']."', '".$formulario->f024['dclr_em']."', 
																											'".$formulario->f024['impr_cl_cd']."', '".$formulario->f024['impr_idt_no_type_cd']."', 
																											'".$formulario->f024['impr_idt_no']."', '".$nombreImportador."',
																											'".$formulario->f024['impr_prvhc_cd']."', '".$formulario->f024['impr_prvhc_nm']."',
																											'".$formulario->f024['impr_cuty_cd']."', '".$formulario->f024['impr_cuty_nm']."', 
																											'".$formulario->f024['impr_prqi_cd']."', '".$formulario->f024['impr_prqi_nm']."','".$formulario->f024['impr_ad']."', 
																											'".$formulario->f024['dclr_rmk']."','S', now(), '".$formulario->f024['rgsp_id']."', 
																											'".$formulario->f024['mdf_dt']."', '".$formulario->f024['mdfr_id']."');");
					
					
					while ($fila = pg_fetch_assoc($qDdaProducto)){
						$productoDda[] = $fila;
					}
						
					for ($i = 0; $i < count ($productoDda); $i++){
						//for ($j = 0; $j < count ($productoImportacion); $j++){
						//	if(($formulario->f024pd[$i]['hc'] == $productoImportacion[$j]['hc']) && ($formulario->f024pd[$i]['prdt_cd'] == $productoImportacion[$j]['prdt_cd'])){
																
								$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp_pd
																			(
																				req_no, prdt_sn, hc, prdt_nm,
																				prdt_pck_qt, prdt_pck_ut, use_fg, rgs_dt, rgsp_id, mdf_dt
																				
																			)
																			VALUES
																			(
																				'".$productoDda[$i]['req_no']."', '".$productoDda[$i]['prdt_sn']."', '".$productoDda[$i]['hc']."',
																				'".$productoDda[$i]['prdt_nm']."', 
																				 ".$productoDda[$i]['pkgs_qt'].", '".$productoDda[$i]['pkgs_ut']."', 
																				'S', now(), '".$productoDda[$i]['rgsp_id']."', now()
																			);");
								
								
						//	}	
					}
		
					echo OUT_MSG. 'Envio de campos a AUCP';
				
				}else{
					echo OUT_MSG. 'No se envio de campos a AUCP';
				}
				
				
			break;
			
			
			case '101-061':
			    
			    //if($formulario->f002['req_type_cd'] == '0001' || $formulario->f002['req_type_cd'] == '0002'){
			    
			    $controladorTransitoInternacional = new ControladorTransitoInternacional();
			    $conexionGUIA = new Conexion();
			    
			    $qImportacion = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $formulario->f061['impr_idt_no'], $formulario->f061['req_no']);
			    $importacion = pg_fetch_assoc($qImportacion);
			    
			    $nombreImportador = str_replace("'", " ", $formulario->f061['impr_nm']);
			    $razonSocialSolicitante = str_replace("'", " ", $formulario->f061['dclr_nm']);
			    
			    $res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp
																			(
																	            req_no, dcm_cd, dcm_nm, dcm_func_cd, frtr_prcr_ctg_cd, atr_orgz_cd,
																	            atr_orgz_nm, atr_iss_city_cd, atr_iss_city_nm, req_de, ctft_no,
																	            ctft_iss_de, ctft_eftv_stdt, ctft_eftv_finl_de, dclr_cl_cd, dclr_idt_no_type_cd,
																	            dclr_idt_no, dclr_nm, dclr_prvhc_cd, dclr_prvhc_nm, dclr_cuty_cd,
																	            dclr_cuty_nm, dclr_prqi_cd, dclr_prqi_nm, dclr_ad, dclr_tel_no,
																	            dclr_em, impr_cl_cd, impr_idt_no, impr_nm,
																	            impr_cuty_cd, impr_cuty_nm, impr_prqi_cd,
																	            impr_prqi_nm, impr_ad, imp_ntn_cd, imp_ntn_nm,
																	            aprb_rmk, use_fg, rgs_dt, rgsp_id, mdf_dt, mdfr_id, dclr_cmp_nm,
																				dcm_type_cd
																			)
																			VALUES
																			(
																				'".$formulario->f061['req_no']."', '".$formulario->f061['dcm_no']."', '".$formulario->f061['dcm_nm']."', '9', 'IMP', '101', 'AGR',
																				'".$formulario->f061['req_city_cd']."', '".$formulario->f061['req_city_nm']."', '".$formulario->f061['req_de']."', '".$formulario->f061['req_no']."',
																				'".$importacion['fecha_inicio_vigencia']."', '".$importacion['fecha_inicio_vigencia']."', '".$importacion['fecha_fin_vigencia']."',
																				'".$formulario->f061['impr_cl_cd']."','', '".$formulario->f061['dclr_idt_no']."',
																				'".$razonSocialSolicitante."', '".$formulario->f061['dclr_prvhc_cd']."', '".$formulario->f061['dclr_prvhc_nm']."', '".$formulario->f061['dclr_cuty_cd']."',
																				'".$formulario->f061['dclr_cuty_nm']."', '".$formulario->f061['dclr_prqi_cd']."', '".$formulario->f061['dclr_prqi_nm']."', '".$formulario->f061['dclr_ad']."',
																				'".$formulario->f061['dcrl_tel_no']."', '".$formulario->f061['dclr_em']."', '".$formulario->f061['impr_cl_cd']."', 
																				'".$formulario->f061['impr_idt_no']."', '".$nombreImportador."', 
																				'".$formulario->f061['impr_cuty_cd']."', '".$formulario->f061['impr_cuty_nm']."', '".$formulario->f061['impr_prqi_cd']."', '".$formulario->f061['impr_prqi_nm']."',
																				'".$formulario->f061['impr_ad']."', 'EC', 'ECUADOR', 'Sin observaciones',
																				'S', now(), '".$formulario->f061['rgsp_id']."', '".$formulario->f061['mdf_dt']."', '".$formulario->f061['mdfr_id']."', '".$razonSocialSolicitante."', 'TRA'
																			);");
			    
			    //impr_idt_no_type_cd,  impr_idt_no_type_cd, dcm_type_cd
			    //'".$formulario->f002['impr_idt_no_type_cd']."',
			    //impr_prvhc_cd, impr_prvhc_nm,
			    //'".$formulario->f002['impr_prvhc_cd']."', '".$formulario->f002['impr_prvhc_nm']."',
			    
			    
			    for ($i = 0; $i < count ($formulario->f061pd); $i++) {
			        
			        $res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp_pd
																			(
																				req_no, prdt_sn, hc, prdt_nm, prdt_desc, prdt_nwt, prdt_nwt_ut,
																				prdt_pck_qt, prdt_pck_ut, use_fg, rgs_dt, rgsp_id, mdf_dt, mdfr_id,
																				mdfr_ip
																			)
																			VALUES
																			(
																				'".$formulario->f061pd[$i]['req_no']."', '".$formulario->f061pd[$i]['prdt_sn']."', '".$formulario->f061pd[$i]['hc']."',
																				'".$formulario->f061pd[$i]['prdt_nm']."', '', ".$formulario->f061pd[$i]['prdt_nwt'].", '".$formulario->f061pd[$i]['prdt_nwt_ut']."',
																				 ".$formulario->f061pd[$i]['prdt_qt'].", '".$formulario->f061pd[$i]['prdt_mes']."',
																				'S', now(), '".$formulario->f061pd[$i]['rgsp_id']."', now(), '".$formulario->f061pd[$i]['mdfr_id']."', '".$formulario->f061pd[$i]['mdfr_ip']."'
																			);");
			        
			    }
			    
			    echo OUT_MSG. 'Envio de campos a AUCP';
			    
			    //	}else{
			    //	echo OUT_MSG. 'No se envio de campos a AUCP';
			    //	}
			    
		break;
			
		}
		
	}
	
	
	public function actualizarAUCP($formulario, $estado){
		
		$conexionGUIA = new Conexion();
		
		switch (substr($formulario->formulario,0,7)){
			case '101-002':
				
				$controladorImportaciones = new ControladorImportaciones();
				
				$identificadorImportador = $formulario->f002['impr_idt_no'];
				
				$qImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $formulario->f002['req_no']);
				$importacion = pg_fetch_assoc($qImportacion);
				
				switch ($estado){
					
					case SOLICITUD_DE_CORRECION_APROBADA:
						
						$nombreImportador = str_replace("'", " ", $formulario->f002['impr_nm']);
						$razonSocialSolicitante = str_replace("'", " ", $formulario->f002['dclr_nole']);

						$res = $this->conexionVUE->ejecutarConsulta("UPDATE 
																		vue_gateway.tn_rci_imex_uni_dcp 
																	SET
																		dcm_cd = '".$formulario->f002['dcm_no']."', dcm_nm = '".$formulario->f002['dcm_nm']."', 
															            atr_iss_city_cd = '".$formulario->f002['req_city_cd']."', atr_iss_city_nm = '".$formulario->f002['req_city_nm']."', 
																		req_de = '".$formulario->f002['req_de']."', ctft_no = '".$formulario->f002['req_no']."',
															            dclr_cl_cd = '".$formulario->f002['impr_cl_cd']."', dclr_idt_no = '".$formulario->f002['dclr_idt_no']."', 
																		dclr_nm = '".$razonSocialSolicitante."', dclr_prvhc_cd = '".$formulario->f002['dclr_prvhc_cd']."', 
																		dclr_prvhc_nm = '".$formulario->f002['dclr_prvhc_nm']."', dclr_cuty_cd = '".$formulario->f002['dclr_cuty_cd']."',
															            dclr_cuty_nm = '".$formulario->f002['dclr_cuty_nm']."', dclr_prqi_cd = '".$formulario->f002['dclr_prqi_cd']."', 
																		dclr_prqi_nm = '".$formulario->f002['dclr_prqi_nm']."', dclr_ad = '".$formulario->f002['dclr_ad']."', 
																		dclr_tel_no = '".$formulario->f002['dcrl_tel_no']."', dclr_em = '".$formulario->f002['dclr_em']."', 
																		impr_cl_cd = '".$formulario->f002['impr_cl_cd']."', impr_idt_no_type_cd = '".$formulario->f002['impr_idt_no_type_cd']."', 
																		impr_idt_no = '".$formulario->f002['impr_idt_no']."', impr_nm = '".$nombreImportador."',
															            impr_prvhc_cd = '".$formulario->f002['impr_prvhc_cd']."', impr_prvhc_nm = '".$formulario->f002['impr_prvhc_nm']."', 
																		impr_cuty_cd = '".$formulario->f002['impr_cuty_cd']."', impr_cuty_nm = '".$formulario->f002['impr_cuty_nm']."', 
																		impr_prqi_cd = '".$formulario->f002['impr_prqi_cd']."', impr_prqi_nm = '".$formulario->f002['impr_prqi_nm']."', 
																		impr_ad = '".$formulario->f002['impr_ad']."', imp_ntn_cd = 'EC', imp_ntn_nm = 'ECUADOR',												
															            mdf_dt = '".$formulario->f002['mdf_dt']."', mdfr_id = '".$formulario->f002['mdfr_id']."', dclr_cmp_nm = '".$razonSocialSolicitante."',
																		aprb_rmk = '".$importacion['observacion_rectificacion']."'
																	WHERE
																		req_no = '".$formulario->f002['req_no']."';");
						
						$res = $this->conexionVUE->ejecutarConsulta("DELETE FROM
																			vue_gateway.tn_rci_imex_uni_dcp_pd
																		WHERE
																			req_no = '".$formulario->f002['req_no']."';");
					
						for ($i = 0; $i < count ($formulario->f002pd); $i++) {
			
							$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp_pd
																			(
																				req_no, prdt_sn, hc, prdt_nm, prdt_desc, prdt_nwt, prdt_nwt_ut,
																				prdt_pck_qt, prdt_pck_ut, use_fg, rgs_dt, rgsp_id, mdf_dt, mdfr_id,
																				mdfr_ip
																			)
																			VALUES
																			(
																				'".$formulario->f002pd[$i]['req_no']."', '".$formulario->f002pd[$i]['prdt_sn']."', '".$formulario->f002pd[$i]['hc']."',
																				'".$formulario->f002pd[$i]['prdt_nm']."', '', ".$formulario->f002pd[$i]['prdt_nwt'].", '".$formulario->f002pd[$i]['prdt_nwt_ut']."',
																				 ".$formulario->f002pd[$i]['prdt_qt'].", '".$formulario->f002pd[$i]['prdt_mes']."', 
																				'S', now(), '".$formulario->f002pd[$i]['rgsp_id']."', now(), '".$formulario->f002pd[$i]['mdfr_id']."', '".$formulario->f002pd[$i]['mdfr_ip']."'
																			);");
						}
						
						
					break;
					
					case SOLICITUD_AMPLIADA:
						$fechaVigencia = $formulario->f002['req_eftv_finl_de'];
						
						$fechaAmpliacion = $importacion['fecha_vigencia'];
						
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE 
																		vue_gateway.tn_rci_imex_uni_dcp 
																	SET 
																			 ctft_eftv_finl_de = '$fechaAmpliacion'
																	WHERE 
																			req_no = '".$formulario->f002['req_no']."';");
						
						echo OUT_MSG. 'Actualización de campos a AUCP (Ampliación)';
					break;
					
					case RECTITICACION_REALIZADA:
						
						$identificadorImportador = $formulario->f002['impr_idt_no'];
						
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																			vue_gateway.tn_agr_002
																		SET
																			dcm_func_cd = '34'
																		WHERE
																			req_no = '".$formulario->f002['req_no']."';");
						
						/*$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		vue_gateway.tn_rci_imex_uni_dcp
																	SET
																		impr_idt_no = '$identificadorImportador'
																	WHERE
																		req_no = '".$formulario->f002['req_no']."';");*/
						
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		vue_gateway.tn_rci_imex_uni_dcp_pd
																	SET
																		prdt_fobv_val = null,
																		prdt_fobv_val_curr = null,
																		prdt_cif_val = null,
																		prdt_cif_val_curr = null,
																		org_ntn_cd = null,
																		org_ntn_nm = null
																	WHERE
																		req_no = '".$formulario->f002['req_no']."';");
						
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		vue_gateway.tn_rci_imex_uni_dcp
																	SET
																		trsp_way_cd = null,
																		trsp_way_nm = null,
																		spm_plc_cd = null,
																		spm_plc_nm = null,
																		dst_city_cd = null,
																		dst_plc_nm = null,
																		expr_nm = null,
																		expr_ad = null
																	WHERE
																		req_no = '".$formulario->f002['req_no']."';");
												
					/*	for ($i = 0; $i < count ($formulario->f002pd); $i++) {
							
							$cantidadProducto = $formulario->f002pd[$i]['prdt_qt'];
							$nombreProducto = $formulario->f002pd[$i]['prdt_nm'];
							$partidaArancelaria = $formulario->f002pd[$i]['hc'];
							
							
							$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																			vue_gateway.tn_rci_imex_uni_dcp_pd
																		SET
																			prdt_pck_qt = $cantidadProducto
																		WHERE
																			req_no = '".$formulario->f002['req_no']."'
																			and hc = '$partidaArancelaria'
																			and prdt_nm = '$nombreProducto';");

						}*/
						
						$tipoProducto = strtoupper(($formulario-> f002['prdt_type_nm']));
						$areas[] = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA' ?'IAP':($tipoProducto=='VETERINARIO'? 'IAV' :'No definido'))));
						
						$this->buscarResponsablesfirmas($formulario->f002['req_no'], array_unique($areas), 'Importación',$identificadorImportador);
						
						echo OUT_MSG. 'Actualización de campos a AUCP RECTIFICACIÓN.';
						
					break;
						
					default:
						echo 'Acción desconocida';
							
					}
						
				break;
				
				case '101-061':
				    
				    $controladorTransitoInternacional = new ControladorTransitoInternacional();
				    
				    $identificadorImportador = $formulario->f061['impr_idt_no'];
				    
				    $qImportacion = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $formulario->f061['req_no']);
				    $importacion = pg_fetch_assoc($qImportacion);
				    
				    switch ($estado){
				        
				        case SOLICITUD_DE_CORRECION_APROBADA:
				            
				            $nombreImportador = str_replace("'", " ", $formulario->f061['impr_nm']);
				            $razonSocialSolicitante = str_replace("'", " ", $formulario->f061['dclr_nm']);
				            
				            $res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		vue_gateway.tn_rci_imex_uni_dcp
																	SET
																		dcm_cd = '".$formulario->f061['dcm_no']."', dcm_nm = '".$formulario->f061['dcm_nm']."',
															            atr_iss_city_cd = '".$formulario->f061['req_city_cd']."', atr_iss_city_nm = '".$formulario->f061['req_city_nm']."',
																		req_de = '".$formulario->f061['req_de']."', ctft_no = '".$formulario->f061['req_no']."',
															            dclr_cl_cd = '".$formulario->f061['impr_cl_cd']."', dclr_idt_no = '".$formulario->f061['dclr_idt_no']."',
																		dclr_nm = '".$razonSocialSolicitante."', dclr_prvhc_cd = '".$formulario->f061['dclr_prvhc_cd']."',
																		dclr_prvhc_nm = '".$formulario->f061['dclr_prvhc_nm']."', dclr_cuty_cd = '".$formulario->f061['dclr_cuty_cd']."',
															            dclr_cuty_nm = '".$formulario->f061['dclr_cuty_nm']."', dclr_prqi_cd = '".$formulario->f061['dclr_prqi_cd']."',
																		dclr_prqi_nm = '".$formulario->f061['dclr_prqi_nm']."', dclr_ad = '".$formulario->f061['dclr_ad']."',
																		dclr_tel_no = '".$formulario->f061['dcrl_tel_no']."', dclr_em = '".$formulario->f061['dclr_em']."',
																		impr_cl_cd = '".$formulario->f061['impr_cl_cd']."',
																		impr_idt_no = '".$formulario->f061['impr_idt_no']."', impr_nm = '".$nombreImportador."',						   
																		impr_cuty_cd = '".$formulario->f061['impr_cuty_cd']."', impr_cuty_nm = '".$formulario->f061['impr_cuty_nm']."',
																		impr_prqi_cd = '".$formulario->f061['impr_prqi_cd']."', impr_prqi_nm = '".$formulario->f061['impr_prqi_nm']."',
																		impr_ad = '".$formulario->f061['impr_ad']."', imp_ntn_cd = 'EC', imp_ntn_nm = 'ECUADOR',
															            mdf_dt = '".$formulario->f061['mdf_dt']."', mdfr_id = '".$formulario->f061['mdfr_id']."', dclr_cmp_nm = '".$razonSocialSolicitante."',
																		aprb_rmk = '".$importacion['observacion_tecnico']."'
																	WHERE
																		req_no = '".$formulario->f061['req_no']."';");
				            
				            //impr_idt_no_type_cd = '".$formulario->f002['impr_idt_no_type_cd']."',
				            //impr_prvhc_cd = '".$formulario->f002['impr_prvhc_cd']."', impr_prvhc_nm = '".$formulario->f002['impr_prvhc_nm']."',
				            
				            $res = $this->conexionVUE->ejecutarConsulta("DELETE FROM
																			vue_gateway.tn_rci_imex_uni_dcp_pd
																		WHERE
																			req_no = '".$formulario->f061['req_no']."';");
				            
				            for ($i = 0; $i < count ($formulario->f061pd); $i++) {
				                
				                $res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_rci_imex_uni_dcp_pd
																			(
																				req_no, prdt_sn, hc, prdt_nm, prdt_desc, prdt_nwt, prdt_nwt_ut,
																				prdt_pck_qt, prdt_pck_ut, use_fg, rgs_dt, rgsp_id, mdf_dt, mdfr_id,
																				mdfr_ip
																			)
																			VALUES
																			(
																				'".$formulario->f061pd[$i]['req_no']."', '".$formulario->f061pd[$i]['prdt_sn']."', '".$formulario->f061pd[$i]['hc']."',
																				'".$formulario->f061pd[$i]['prdt_nm']."', '', ".$formulario->f061pd[$i]['prdt_nwt'].", '".$formulario->f061pd[$i]['prdt_nwt_ut']."',
																				 ".$formulario->f061pd[$i]['prdt_qt'].", '".$formulario->f061pd[$i]['prdt_mes']."',
																				'S', now(), '".$formulario->f061pd[$i]['rgsp_id']."', now(), '".$formulario->f061pd[$i]['mdfr_id']."', '".$formulario->f061pd[$i]['mdfr_ip']."'
																			);");
				            }
				            				            
				        default:
				            echo 'Acción desconocida';
				            
				    }
				    
				    break;
				    
				default:
					echo 'Acción desconocida';
			}			
	}
	
	
	public function cambioEstadoAUCP($formulario, $codigo){
		
	    switch (substr($formulario->formulario,0,7)){
	        case '101-002':
	            
		      $res = $this->conexionVUE->ejecutarConsulta("UPDATE 
    														vue_gateway.tn_rci_imex_uni_dcp
    													 SET 
    														dcm_func_cd = '$codigo'
    													 WHERE 
    														req_no = '".$formulario->f002['req_no']."';");
		    break;
		    
	        case '101-061':
	            
	            $res = $this->conexionVUE->ejecutarConsulta("UPDATE
    														vue_gateway.tn_rci_imex_uni_dcp
    													 SET
    														dcm_func_cd = '$codigo'
    													 WHERE
    														req_no = '".$formulario->f061['req_no']."';");
	            break;
		
	        default:
	            echo 'Acción desconocida';
	        
	    }		
		
	}
	
	public function  buscarResponsablesfirmas($solicitud, $areas, $tipoSolicitud, $identificadorOperador=null){
		
		$conexionGUIA = new Conexion();
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorCatalogos = new ControladorCatalogos();
	
		switch ($tipoSolicitud){
			
			case 'Operadores':
				
				foreach ($areas as $area){
					$resultado = pg_fetch_assoc($controladorCatalogos->obtenerResponsableFirmaVUE($conexionGUIA, '101-001', $area));
					$this->firmas($solicitud, $resultado['identificador']);
				}
				
			break;
			
			case 'Importación':
				
				$controladorImportaciones = new ControladorImportaciones();
				
				$importacion = pg_fetch_assoc($controladorImportaciones->buscarImportacionVUE($conexionGUIA, $solicitud));
				$resultado = pg_fetch_assoc($controladorCatalogos->obtenerResponsableFirmaVUE($conexionGUIA, '101-002', $areas[0], $importacion['nombre_provincia']));
				$this->firmas($solicitud, $resultado['identificador']);
				
			break;
			
			case 'DDA':
				
				$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
								
				$datosDDA = pg_fetch_assoc($controladorDestinacionAduanera->buscarDDAVUE($conexionGUIA, $identificadorOperador, $solicitud));
				
				$codigoPuertoDestino = pg_fetch_assoc($controladorCatalogos->obtenerPuertoXid($conexionGUIA, $datosDDA['id_puerto_destino'])); //Obtiene codigo en GUIA de puerto
				
				//$identificadorInspector = pg_fetch_assoc($controladorRevisionSolicitudesVUE->buscarEstadoSolicitudXtipoInspector($conexionGUIA, $datosDDA['id_destinacion_aduanera'], $tipoSolicitud, 'Técnico'));
				
				$resultado = pg_fetch_assoc($controladorCatalogos->obtenerResponsableFirmaVUE($conexionGUIA, '101-024', $areas[0], $codigoPuertoDestino['nombre_provincia'],$codigoPuertoDestino['tipo_puerto']));
				
				//$this->firmas($solicitud, $identificadorInspector['identificador_inspector']);
				$this->firmas($solicitud, $resultado['identificador']);
				
			break;
	
			case 'Zoosanitario':

				$controladorZoosanitarioExportacion = new ControladorZoosanitarioExportacion();
				
				$idZOO = $controladorZoosanitarioExportacion -> buscarZooVUE($conexionGUIA,$identificadorOperador,$solicitud);
				$identificadorInspector = pg_fetch_assoc($controladorRevisionSolicitudesVUE->buscarEstadoSolicitudXtipoInspector($conexionGUIA, pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'), $tipoSolicitud, 'Técnico'));
				
				$this->firmas($solicitud, $identificadorInspector['identificador_inspector']);
				
			break;
		
			case 'Fitosanitario':
				
				$controladorFitosanitario = new ControladorFitosanitario();
				
				$idFito = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $solicitud);
				$identificadorInspector = pg_fetch_assoc($controladorRevisionSolicitudesVUE->buscarEstadoSolicitudXtipoInspector($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'), $tipoSolicitud, 'Documental'));
				
				$this->firmas($solicitud, $identificadorInspector['identificador_inspector']);
				
			break;
			
			case 'CLV':
								
				foreach ($areas as $area){
					$resultado = pg_fetch_assoc($controladorCatalogos->obtenerResponsableFirmaVUE($conexionGUIA, '101-047', $area));
					$this->firmas($solicitud, $resultado['identificador']);
				}
				
			break;
			
			case 'FitosanitarioExportacion':
								
				$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
				
				$idFitosanitarioExportacion = pg_fetch_assoc($controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $solicitud));
				
				$resultado = $controladorCatalogos->obtenerResponsableFirmaVUE($conexionGUIA, '101-034', $areas[0], $idFitosanitarioExportacion['id_provincia_revision']);
				
				while($fila = pg_fetch_assoc($resultado)){
					$arrayResponsables[] = $fila;
				}
								
				$llaveRandomica = array_rand($arrayResponsables);
				
				$this->firmas($solicitud, $arrayResponsables[$llaveRandomica]['identificador']);
								
			break;
			
			case 'TránsitoInternacional':
			    
			    $controladorTransitoInternacional = new ControladorTransitoInternacional();
                
                $transito = pg_fetch_assoc($controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorOperador, $solicitud));
                $resultado = pg_fetch_assoc($controladorCatalogos->obtenerResponsableFirmaVUE($conexionGUIA, '101-061', $areas[0], $transito['provincia_revision']));
                $this->firmas($solicitud, $resultado['identificador']);
                
                break;
			
			default:
				echo 'Formulario desconocido';
		}
	}
	
	public function firmas($solicitud, $identificador){
		
		$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO
														vue_gateway.tn_eld_rpsb_atr_inf
													VALUES ('$solicitud',
														(SELECT CASE WHEN ((SELECT CAST(COUNT(ORD_NO) AS NUMERIC(5)) FROM vue_gateway.tn_eld_rpsb_atr_inf WHERE REQ_NO ='$solicitud') = 0) THEN 1
														ELSE ( SELECT MAX(CAST(ORD_NO AS NUMERIC(5)))+1 FROM vue_gateway.tn_eld_rpsb_atr_inf WHERE REQ_NO = '$solicitud') END ),
														'$identificador',  
														now(),
														1,
														'21',
														'S',
														now(),
														'$identificador',
														now(),
														'101');");
		
		echo OUT_MSG. 'Firmas insertadas en VUE.';
	}

	public function envioCamposRespuesta($formulario){

		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorZoosanitario = new ControladorZoosanitarioExportacion();
		$controladorRequisito = new ControladorRequisitos();
		$controladorImportaciones = new ControladorImportaciones();
		$conexionGUIA = new Conexion();

		$solicitud = $formulario->numeroDeSolicitud;
		$areas = array();

		switch (substr($formulario->formulario,0,7)){
			case '101-001':
				
				$codigoCiudad = $formulario->f001['req_city_cd'];
				$nombreCiudad = $formulario->f001['req_city_nm'];

				$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																vue_gateway.tn_agr_001
															SET
																dcm_no = '101-001-RES',
																ctft_no='$solicitud', --Número certificado
																ctft_iss_de=now(), --fecha emision certificado
																ctft_eftv_stdt=now(), --fecha de inicio de vigencia del certificado
																ctft_eftv_finl_de=now() + '10 year', --fecha de final de vigencia de certificador
																ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																ctft_iss_city_nm='$nombreCiudad' --nombre de ciudad de emision
															WHERE
																req_no ='$solicitud';");

				
				for ($i = 0; $i < count ($formulario->f001pd); $i++) {

					$nombreProducto = $formulario->f001pd[$i]['prdt_nm'];
					$tipoProducto = strtoupper(($formulario-> f001pd[$i]['prdt_type_nm']));
					
					$areas[] = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA'?'IAP':( $tipoProducto=='VETERINARIO'? 'IAV' :( $tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
					
					$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $formulario->f001pd[$i]['agrcd_prdt_cd']);
					$razonSocial = pg_fetch_result($qOperador, 0, 'razon_social');

					$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																	vue_gateway.tn_agr_001_pd
																SET
																	prdt_nole='$razonSocial'
																WHERE
																	req_no ='$solicitud'
																	and prdt_nm ='$nombreProducto';");
				}
				
				$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Operadores');

			break;
			
			case '101-002': 
				
				$codigoCiudad = $formulario->f002['req_city_cd'];
				$nombreCiudad = $formulario->f002['req_city_nm'];
				$identificadorImportador = $formulario->f002['impr_idt_no'];
				
				$qImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $solicitud);
				$importacion = pg_fetch_assoc($qImportacion);
				
				
				$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																vue_gateway.tn_agr_002
															SET
																dcm_no = '101-002-RES',
																ctft_no='$solicitud', --Número certificado
																ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																req_eftv_stdt='".$importacion['fecha_inicio']."', --fecha de inicio de vigencia del certificado
																req_eftv_finl_de='".$importacion['fecha_vigencia']."' --fecha de final de vigencia de certificador
															WHERE
																req_no ='$solicitud';");
				
				
				$tipoProducto = strtoupper(($formulario-> f002['prdt_type_nm']));
				$areas[] = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA' ?'IAP':($tipoProducto=='VETERINARIO'? 'IAV' :($tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));

				$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Importación',$identificadorImportador);
				
			break;
			
			case '101-024':
				
				$referenciaCerificadoImportacion = $formulario->f024['imp_pht_prmt_no'];
				
				$qImportacion = $this->conexionVUE->ejecutarConsulta("SELECT * FROM vue_gateway.tn_agr_002 WHERE REQ_NO = '$referenciaCerificadoImportacion';");
				$importacion = pg_fetch_assoc($qImportacion);
				
				$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
				
				$datosDDA = pg_fetch_assoc($controladorDestinacionAduanera->buscarDDAVUE($conexionGUIA, $formulario->f024['impr_idt_no'], $solicitud));
				$datosDDAProductos = $controladorDestinacionAduanera->abrirProductosDDA($conexionGUIA, $datosDDA['id_destinacion_aduanera']);
				$lugarInspeccion = pg_fetch_assoc($controladorCatalogos->buscarCatalogoLugarInspeccion($conexionGUIA, $formulario->f024['isp_plc_cd']));
								
			
				$codigoCiudad = $formulario->f024['req_city_cd'];
				$nombreCiudad = $formulario->f024['req_city_nm'];
				$identificadorDDA = $formulario->f024['impr_idt_no'];
				
				$contenedores = $datosDDA['numero_contenedores'] == NULL ? 'NULL' : "'".$datosDDA['numero_contenedores']."'";
			
				$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																vue_gateway.tn_agr_024
															SET
																dcm_no = '101-024-RES',
																ctft_no='$solicitud', --Número certificado
																ctft_iss_de = '".$datosDDA['fecha_inicio']."', -- fecha de emisión de ceritifcado
																ctft_iss_city_cd='".$lugarInspeccion['codigo_ciudad_vue']."', --codigo de ciudad de emision
																ctft_iss_city_nm='".$lugarInspeccion['nombre_ciudad_vue']."', --nombre de ciudad de emision
																arv_de = '".$datosDDA['fecha_arribo']."', --fecha de arribo
																--ctnr_no = '".$datosDDA['numero_contenedores']."', --numero de contenedores
																ctnr_no = $contenedores, --numero de contenedores
																spm_ntn_cd = '".$importacion['spm_ntn_cd']."', --codigo de pais de embarque
																spm_ntn_nm = $$".$importacion['spm_ntn_nm']."$$, --nombre de pais de embarque
																spm_port_cd = '".$importacion['spm_port_cd']."', -- codigo de puerto de embarque
																spm_port_nm = $$".$importacion['spm_port_nm']."$$, -- nombre puerto de embarque
																spm_de = '".$datosDDA['fecha_embarque']."',-- fecha de embarque
																nwt_tot_qt = ".$datosDDA['peso_total'].", -- peso neto total
																nwt_tot_qt_ut = '".$datosDDA['unidad_peso_total']."', -- unidad peso neto
																phys_isp_rmk = '".$datosDDAProductos[0]['observacion']."' -- Observación de inspección
															WHERE
																req_no ='$solicitud';");
				
				foreach ($datosDDAProductos as $ddaProducto){

					$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																	vue_gateway.tn_agr_024_pd
																 SET
																	nwt_qt = ".$ddaProducto['peso'].",
																	nwt_ut = '".$ddaProducto['unidadPeso']."'
																 WHERE
																	hc = '".$ddaProducto['partidaProductoVue']."' and
																	prdt_cd = '".$ddaProducto['codigoProductoVue']."' and
																	req_no = '$solicitud'");
				}
			
				$tipoProducto = strtoupper(($formulario-> f024['req_type_nm']));
				$areas[] = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA' ?'IAP':($tipoProducto=='VETERINARIO'? 'IAV' :($tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));

				$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'DDA', $identificadorDDA);
				
				break;
				
				case '101-008':
					
					$identificadorZOO = $formulario->f008['expr_idt_no'];
					$codigoCiudad = $formulario->f008['req_city_cd'];
					$nombreCiudad = $formulario->f008['req_city_nm'];
					
					$qSitioInspeccion = $controladorRegistroOperador->buscarSitios($conexionGUIA, $identificadorZOO, $formulario->f008['isp_reg_sitio']);
					$sitioInspeccion = pg_fetch_result($qSitioInspeccion, 0, 'provincia');
					
					$qZoosanitario = $controladorZoosanitario->buscarZooVUE($conexionGUIA, $identificadorZOO, $solicitud);
					$zoosanitario = pg_fetch_assoc($qZoosanitario);
					
					$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																	vue_gateway.tn_agr_008
																SET
																	dcm_no = '101-008-RES',
																	ctft_no='$solicitud', --Número certificado
																	ctft_iss_de='".$zoosanitario['fecha_inicio']."', --fecha emision certificado
																	ctft_eftv_stdt='".$zoosanitario['fecha_inicio']."', --fecha de inicio de vigencia del certificado
																	ctft_eftv_finl_de='".$zoosanitario['fecha_vigencia']."', --fecha de final de vigencia de certificador
																	ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																	ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																	isp_lugar_org_mm = '$sitioInspeccion',
																	rmk = '".$zoosanitario['observacion']."'
																WHERE
																	req_no ='$solicitud';");
						
						
					for ($i = 0; $i < count ($formulario->f008pd); $i++) {
							
						$partidaArancelariaVUE = $formulario->f008pd[$i]['hc'];
						$codigoProductoVUE = $formulario->f008pd[$i]['prdt_cd'];
							
						$areas[] = 'SA';
							
						$productoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
	    				$producto = pg_fetch_assoc($productoGUIA);
	    				
	    				$nombreTipoProducto = pg_fetch_result($controladorCatalogos->buscarTipoProdcutoVue($conexionGUIA, $producto['id_producto']), 0, 'tipoPorducto');
							
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		vue_gateway.tn_agr_008_pd
																	SET
																		prdt_spc_nm ='$nombreTipoProducto',
																		rmk = '".$zoosanitario['observacion']."'																		
																	WHERE
																		req_no ='$solicitud'
																		and hc ='$partidaArancelariaVUE'
																		and prdt_cd = '$codigoProductoVUE';");
					}
					
					$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Zoosanitario',$identificadorZOO);
						
				break;
				
				case '101-031':
					
					$controladorFitosanitario = new ControladorFitosanitario();
				
					$codigoCiudad = $formulario->f031['req_city_cd'];
					$nombreCiudad = $formulario->f031['req_city_nm'];
					
					$subPartidaProducto = $formulario->f031pd[0]['hc'];
					$codigoProducto = $formulario->f031pd[0]['prdt_cd'];
					
					$qProducto = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $subPartidaProducto, $codigoProducto);
					$producto = pg_fetch_assoc($qProducto);
					
					$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $formulario->f031['dst_ntn_cd']); //Validación del pais de destino
					$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
					
					$requisito = pg_fetch_assoc($controladorRequisito->listarRequisitoProductoPaisUnidoImpreso($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'Exportación'));
					$requisito = substr($requisito['detalle_impreso'], 0,490);
					
					$qFito = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $formulario->f031['req_no']);
					$fitosanitario = pg_fetch_assoc($qFito);
					
					$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																	vue_gateway.tn_agr_031
																SET
																	dcm_no = '101-031-RES',
																	ctft_no='$solicitud', --Número certificado
																	ctft_iss_de='".$fitosanitario['fecha_inicio']."', --fecha emision certificado
																	ctft_eftv_stdt='".$fitosanitario['fecha_inicio']."', --fecha de inicio de vigencia del certificado
																	ctft_eftv_finl_de='".$fitosanitario['fecha_vigencia']."', --fecha de final de vigencia de certificador
																	ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																	ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																	fsty_ncd_inf_nm = '$requisito'
																WHERE
																	req_no ='$solicitud';");
					
					
					for ($i = 0; $i < count ($formulario->f031pd); $i++) {
					
						$subPartidaProducto = $formulario->f031pd[$i]['hc'];
						$codigoProducto = $formulario->f031pd[$i]['prdt_cd'];
						$exportadorProducto = $formulario->f031pd[$i]['expr_idt_no'];
							
						$areas[] = 'SV';
							
						$qProducto = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $subPartidaProducto, $codigoProducto);
						$producto = pg_fetch_assoc($qProducto);
						
						$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $exportadorProducto);
						$operador = pg_fetch_assoc($qOperador);
					
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		vue_gateway.tn_agr_031_pd
																	SET
																		expr_ad='".$operador['direccion']."',
																		expr_nole = '".$operador['razon_social']."',
																		prdt_snt = '".$producto['nombre_cientifico']."',
																		num_reg_agr = '$exportadorProducto'
																	WHERE
																		req_no ='$solicitud'
																		and hc ='$subPartidaProducto'
																		and prdt_cd = '$codigoProducto'
																		and expr_idt_no = '$exportadorProducto';");
					
					}
					
					$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Fitosanitario');
					
				break;
				
				case '101-047':
					
					$controladorCLV = new ControladorClv();
					$controladorCatalogos = new ControladorCatalogos();
					
					$codigoCiudad = $formulario->f047['req_city_cd'];
					$nombreCiudad = $formulario->f047['req_city_nm'];
					$identificadorTitular = $formulario->f047['rgs_nomn_idt_no'];
					
					
					$qIdCLV = $controladorCLV->buscarClvVUE($conexionGUIA, $formulario->f047['req_no']);
					$idCLV = pg_fetch_assoc($qIdCLV);
					
					
					$partidaArancelariaVUE = $formulario->f047['hc'];
					$codigoProductoVUE = $formulario->f047['prdt_cd'];
					
					$codigoTipoSolicitud = $formulario->f047['tip_prod_code'];
					$tipoSolicitud = ($codigoTipoSolicitud == '01'?'IAP':($codigoTipoSolicitud == '02'?'IAV':'No definido'));
					
					$qProducto = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $tipoSolicitud);
					$producto = pg_fetch_assoc($qProducto);	
					
					$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																	vue_gateway.tn_agr_047
																SET
																	dcm_no = '101-047-RES',
																	ctft_no='$solicitud', --Número certificado
																	ctft_iss_de='".$idCLV['fecha_inicio']."', --fecha emision certificado
																	ctft_eftv_stdt='".$idCLV['fecha_inicio']."', --fecha de inicio de vigencia del certificado
																	ctft_eftv_finl_de='".$idCLV['fecha_vencimiento']."', --fecha de final de vigencia de certificador
																	ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																	ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																	org_ntn_cd = 'EC', -- Codigo de País Origen
																	org_ntn_nm = 'ECUADOR', --Nombre de País de origen
																	prdt_type = '".$producto['nombre']."', --Clasificacion de producto (Subtipo)
																	prdt_phar_fm ='".$idCLV['forma_farmaceutica']."', --Forma farmaceutica (Formulacion)
																	prdt_fml_desc ='".$idCLV['forma_farmaceutica']."', --Forma farmaceutica (Formulacion)
																	prdt_com_fm = '".$idCLV['presentacion_comercial_producto']."', --Presentacion comercial (Presentacion)
																	prdt_use = '".$idCLV['uso_producto']."', --Uso de producto 
																	prdt_tgt_sp = '".$idCLV['especie_destino']."', -- Especie de destino
																	prdt_ins_de = '".$idCLV['fecha_inscripcion_producto']."', --fecha de inscripcion de producto
																	prdt_reg_de = '".$idCLV['fecha_vigencia_producto']."', --Fecha de registro de producto
																	faform_nm = '".$idCLV['nombre_datos_certificado']."', -- nombre de fabricante/fromulador
																	faform_ad = '".$idCLV['direccion_datos_certificado']."',  --Direccion de fabricante/formulador
																	prdt_cmca_nm = '".$idCLV['nombre_comercial_producto']."', --Nombre comercial producto
																	agrcd_prdt_rgs_no = '".$idCLV['numero_registro_agrocalidad']."' --Numero registro agrocalidad														
																WHERE
																	req_no ='$solicitud';");
					
				
					
					$res = $this->conexionVUE->ejecutarConsulta("DELETE FROM vue_gateway.tn_agr_047_cps WHERE req_no = '$solicitud'");
					

					
					$detalleCLV = $controladorCLV->listarDetalleCertificados($conexionGUIA, pg_fetch_result($qIdCLV, 0, 'id_clv'));
					
					$areas[] = $tipoSolicitud;
					
					
					for ($i = 0; $i < count ($detalleCLV); $i++) {
						
						if($tipoSolicitud == 'IAP'){
							
							$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_agr_047_cps(req_no, dcd_cps_sn,
																												dcd_cps_ing_act_nm, dcd_cps_conce_nm,
																												dcd_cps_conce_ut,
																												use_fg,
																												rgs_dt, rgsp_id, mdfr_ip)
																										VALUES
																												('$solicitud',
																												(SELECT CASE WHEN ((SELECT CAST(COUNT(dcd_cps_sn) AS NUMERIC(5)) FROM vue_gateway.tn_agr_047_cps WHERE REQ_NO ='$solicitud') = 0) THEN 1
																												ELSE ( SELECT MAX(CAST(dcd_cps_sn AS NUMERIC(5)))+1 FROM vue_gateway.tn_agr_047_cps WHERE REQ_NO = '$solicitud') END ),
																												'".$detalleCLV[$i]['ingredienteActivo']."',
																												".$detalleCLV[$i]['concentracion'].",
																												'".$detalleCLV[$i]['unidadMedida']."',
																												'S', now(), 'G.U.I.A.', '192.168.200.6');");
							
							
							
						}else if ($tipoSolicitud == 'IAV'){							
							
							$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_agr_047_cps(req_no, dcd_cps_sn,
																												dcd_cps_nm, dcd_cps_qt, dcd_cps_qt_ut, cfg_igdt_desc, 
																												use_fg, rgs_dt, rgsp_id, mdfr_ip)
																										VALUES
																												('$solicitud',
																												(SELECT CASE WHEN ((SELECT CAST(COUNT(dcd_cps_sn) AS NUMERIC(5)) FROM vue_gateway.tn_agr_047_cps WHERE REQ_NO ='$solicitud') = 0) THEN 1
																												ELSE ( SELECT MAX(CAST(dcd_cps_sn AS NUMERIC(5)))+1 FROM vue_gateway.tn_agr_047_cps WHERE REQ_NO = '$solicitud') END ),
																												'".$detalleCLV[$i]['composicionDeclarada']."',
																												'".$detalleCLV[$i]['cantidadComposicion']."',
																												'".$detalleCLV[$i]['unidadMedida']."',
																												'".$detalleCLV[$i]['descripcionComposicion']."',
																												'S', now(), 'G.U.I.A.', '192.168.200.6');");
						}
						
					}
					
					
					$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'CLV');
					
				break;
				
				case '101-034':
				
					$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
										
					$idVue = $formulario->f034['req_no'];	
					$tipoSolicitud = $formulario->f034['sps_idt_type_cd'];
					$poseeTransito = $formulario->f034['trsp_use_fg'];
					$poseeTransito = 'S';
					
						$qFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $solicitud);
						$fitosanitarioExportacion = pg_fetch_assoc($qFitosanitarioExportacion);
							
						
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		 vue_gateway.tn_agr_034
																	SET
																		dcm_no = '101-034-RES',
																		ctft_no='$solicitud',
																		ctft_iss_de='".$fitosanitarioExportacion['fecha_inicio_vigencia_certificado']."',
																		ctft_eftv_stdt='".$fitosanitarioExportacion['fecha_inicio_vigencia_certificado']."',
																		--ctft_eftv_finl_de='".$fitosanitarioExportacion['fecha_fin_vigencia_certificado']."',
																		agc_nm = '".$fitosanitarioExportacion['nombre_agencia_carga']."',
																		ctft_iss_city_cd='".$fitosanitarioExportacion['codigo_ciudad_solicitud']."',
																		ctft_iss_city_nm='".$fitosanitarioExportacion['nombre_ciudad_solicitud']."'
																	WHERE
																		req_no ='$solicitud';");
						
						
						
						for ($i = 0; $i < count ($formulario->f034ex); $i++) {
								
							$idExportador = $formulario->f034ex[$i]['expr_sn'];
							$identificadorExportador = $formulario->f034ex[$i]['expr_idt_no'];
								
							$qExportadorFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionExportador($conexionGUIA, $fitosanitarioExportacion['id_fitosanitario_exportacion'], $identificadorExportador);
	
							while($filaExportadorFitosanitarioExportacion=pg_fetch_assoc($qExportadorFitosanitarioExportacion)){
								
								$productos = $this->obtenerDatosExtrasFitosanitarioExportacion($idVue,'tn_agr_034_pd', $idExportador);
									
								for ($j = 0; $j < count ($productos); $j++) {
									
									$areas[] = 'SV';
							
									$partidaArancelariaVUE = $productos[$j]['hc'];
									$codigoProductoVUE = $productos[$j]['prdt_cd'];
									$exportadorProducto = $productos[$j]['expr_sn'];
						
									$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
									$producto = pg_fetch_assoc($qProductoGUIA);
							
									$qProductoFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionProducto($conexionGUIA, $filaExportadorFitosanitarioExportacion['id_fitosanitario_exportador'], $fitosanitarioExportacion['id_fitosanitario_exportacion'], $producto['id_producto']);
																
										if(pg_num_rows($qProductoFitosanitarioExportacion)!=0){
												$productoFitosanitarioExportacion = pg_fetch_assoc($qProductoFitosanitarioExportacion);
												$requisitoProductoPais = pg_fetch_assoc($controladorRequisito->listarRequisitoProductoPaisUnidoImpreso($conexionGUIA, $fitosanitarioExportacion['id_pais_destino'], $productoFitosanitarioExportacion['id_producto'], 'Exportación'));
												


												$requisitoProductoPais = substr($requisitoProductoPais['detalle_impreso'], 0,3999);
										
												$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																									vue_gateway.tn_agr_034_pd
																								SET
																									fctr_btn='".$producto['nombre_cientifico']."',
																									pht_req = '$requisitoProductoPais'
																								WHERE
																									req_no ='$solicitud'
																									and hc ='$partidaArancelariaVUE'
																									and prdt_cd = '$codigoProductoVUE'
																									and expr_sn = '$exportadorProducto';");
										}
								
								}
								if($poseeTransito=='S'){
								
									for($l = 0; $l < count ($formulario->f034tr); $l++ ){
											
										$codigoPaisDestinoTransito = $formulario->f034tr[$l]['trsp_cntry_cd'];
									
										$qPaisTransito = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoTransito); //Validación del pais de transito
										$paisTransito = pg_fetch_assoc($qPaisTransito);
									
										$productos = $this->obtenerDatosExtrasFitosanitarioExportacion($idVue,'tn_agr_034_pd', $idExportador);
									
										$requisitos = '';
										for ($m = 0; $m < count ($productos); $m++) {
									
											$partidaArancelariaVUE = $productos[$m]['hc'];
											$codigoProductoVUE = $productos[$m]['prdt_cd'];
											$exportadorProducto = $productos[$m]['expr_sn'];
												
											$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
											$producto = pg_fetch_assoc($qProductoGUIA);
												
											$qProductoFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionProducto($conexionGUIA, $filaExportadorFitosanitarioExportacion['id_fitosanitario_exportador'], $fitosanitarioExportacion['id_fitosanitario_exportacion'], $producto['id_producto']);
									
											if(pg_num_rows($qProductoFitosanitarioExportacion)!=0){

											$productoFitosanitarioExportacion = pg_fetch_assoc($qProductoFitosanitarioExportacion);
													$requisitoProductoPais = pg_fetch_assoc($controladorRequisito->listarRequisitoProductoPaisUnidoImpreso($conexionGUIA, $paisTransito['id_localizacion'], $productoFitosanitarioExportacion['id_producto'], 'Tránsito'));
													


													$requisitos .= $productoFitosanitarioExportacion['nombre_producto'].'->'.$requisitoProductoPais['detalle_impreso'].'; ';
											}
			
										}
																					
										$requisitoProductoPais = substr($requisitos, 0,3999);
										
										$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																						vue_gateway.tn_agr_034_tr
																					SET
																						fit_req='$requisitoProductoPais'
																					WHERE
																						req_no ='$solicitud'
																						and trsp_cntry_cd ='".$paisTransito['codigo_vue']."';");
										
									}
						
								}
							}
								
						}
						
						//$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'FitosanitarioExportacion');

				break;
				
				case '101-061':
                
                $controladorTransitoInternacional = new ControladorTransitoInternacional();
                
                $codigoCiudad = $formulario->f061['req_city_cd'];
                $nombreCiudad = $formulario->f061['req_city_nm'];
                $identificadorImportador = $formulario->f061['impr_idt_no'];
                
                $qTransito = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $solicitud);
                $registroTransito = pg_fetch_assoc($qTransito);
                
                
                $res = $this->conexionVUE->ejecutarConsulta("UPDATE
																vue_gateway.tn_agr_061
															SET
																dcm_no = '101-061-RES',
																ctft_no='$solicitud', --Número certificado
																ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																req_eftv_stdt='".$registroTransito['fecha_inicio_vigencia']."', --fecha de inicio de vigencia del certificado
																req_eftv_finl_de='".$registroTransito['fecha_fin_vigencia']."' --fecha de final de vigencia de certificador
															WHERE
																req_no ='$solicitud';");
                
                
                $tipoProducto = strtoupper($formulario-> f061['prdt_type_nm']);
                $areas[] = ($tipoProducto== 'VEGETAL'?'SV':($tipoProducto== 'ANIMAL'?'SA':'No definido'));
                
                $this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'TránsitoInternacional',$identificadorImportador);
                
                break;
				
				default: 
					echo 'Formulario desconocido';
		}
		
		
		foreach (array_unique($areas) as $area){
			if($area == 'SA'){
				$this->firmas($solicitud, '1201996343'); //Julio Antonio Arana Onofre
			}else if($area == 'SV'){
				$this->firmas($solicitud, '1721122370'); //Jhenny Marlene Cayambe Terán
			}else if($area == 'IAP' || $area == 'IAV'){
				$this->firmas($solicitud, '0502624745'); // Robert Leonel Molina Cevallos
			}else{
				echo 'No definido';
			}
		}
		
	}
	
	public function actualizarCamposRespuesta($formulario){
			
		$conexionGUIA = new Conexion();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorRequisito = new ControladorRequisitos();
		$controladorRegistroOperador = new ControladorRegistroOperador();

		$solicitud = $formulario->numeroDeSolicitud;
		$areas = array();

		switch (substr($formulario->formulario,0,7)){
			
			case '101-001':				
				
					$codigoCiudad = $formulario->f001['req_city_cd'];
					$nombreCiudad = $formulario->f001['req_city_nm'];
				
					$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																	vue_gateway.tn_agr_001
																SET
																	dcm_no = '101-001-RES',
																	ctft_no='$solicitud', --Número certificado
																	ctft_iss_de=now(), --fecha emision certificado
																	ctft_eftv_stdt=now(), --fecha de inicio de vigencia del certificado
																	ctft_eftv_finl_de=now() + '10 year', --fecha de final de vigencia de certificador
																	ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																	ctft_iss_city_nm='$nombreCiudad' --nombre de ciudad de emision
																WHERE
																	req_no ='$solicitud';");
				
				
					for ($i = 0; $i < count ($formulario->f001pd); $i++) {
				
						$nombreProducto = $formulario->f001pd[$i]['prdt_nm'];
						$tipoProducto = strtoupper(($formulario-> f001pd[$i]['prdt_type_nm']));
							
						$areas[] = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA'?'IAP':( $tipoProducto=='VETERINARIO'? 'IAV' :( $tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
							
						$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $formulario->f001pd[$i]['agrcd_prdt_cd']);
						$razonSocial = pg_fetch_result($qOperador, 0, 'razon_social');
										
						$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																		vue_gateway.tn_agr_001_pd
																	SET
																		prdt_nole='$razonSocial'
																	WHERE
																		req_no ='$solicitud'
																		and prdt_nm ='$nombreProducto';");
					}
				
					$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Operadores');
				
			break;
			
			case '101-002':
				
				$controladorImportaciones = new ControladorImportaciones();
				
				$qImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $solicitud);
				$importacion = pg_fetch_assoc($qImportacion);
				
				$codigoCiudad = $formulario->f002['req_city_cd'];
				$nombreCiudad = $formulario->f002['req_city_nm'];
				$identificadorImportador = $formulario->f002['impr_idt_no'];
							
				$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																vue_gateway.tn_agr_002
															SET
																ctft_no='$solicitud', --Número certificado
																ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																req_eftv_stdt='".$importacion['fecha_inicio']."', --fecha de inicio de vigencia del certificado
																req_eftv_finl_de= '".$importacion['fecha_vigencia']."' --fecha de final de vigencia de certificador
															WHERE
																req_no ='$solicitud';");
				
				
				$tipoProducto = strtoupper(($formulario-> f002['prdt_type_nm']));
				$areas[] = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA' ?'IAP':($tipoProducto=='VETERINARIO'? 'IAV' :($tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
				
				$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Importación',$identificadorImportador);
				
				break;
				
				case '101-031':
						
					$controladorFitosanitario = new ControladorFitosanitario();
				
					$codigoCiudad = $formulario->f031['req_city_cd'];
					$nombreCiudad = $formulario->f031['req_city_nm'];
						
					$subPartidaProducto = $formulario->f031pd[0]['hc'];
					$codigoProducto = $formulario->f031pd[0]['prdt_cd'];
						
					$qProducto = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $subPartidaProducto, $codigoProducto);
					$producto = pg_fetch_assoc($qProducto);
						
					$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $formulario->f031['dst_ntn_cd']); //Validación del pais de destino
					$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
						
					$requisito = pg_fetch_assoc($controladorRequisito->listarRequisitoProductoPaisUnidoImpreso($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'Exportación'));
					$requisito = substr($requisito['detalle_impreso'], 0,490);
						
					$qFito = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $formulario->f031['req_no']);
					$fitosanitario = pg_fetch_assoc($qFito);
						
					$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																	vue_gateway.tn_agr_031
																SET
																	dcm_no = '101-031-RES',
																	ctft_no='$solicitud', --Número certificado
																	ctft_iss_de='".$fitosanitario['fecha_inicio']."', --fecha emision certificado
																	ctft_eftv_stdt='".$fitosanitario['fecha_inicio']."', --fecha de inicio de vigencia del certificado
																	ctft_eftv_finl_de='".$fitosanitario['fecha_vigencia']."', --fecha de final de vigencia de certificador
																	ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																	ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																	fsty_ncd_inf_nm = '$requisito'
																WHERE
																	req_no ='$solicitud';");
								
								
							for ($i = 0; $i < count ($formulario->f031pd); $i++) {
								
							$subPartidaProducto = $formulario->f031pd[$i]['hc'];
							$codigoProducto = $formulario->f031pd[$i]['prdt_cd'];
							$exportadorProducto = $formulario->f031pd[$i]['expr_idt_no'];
								
							$areas[] = 'SV';
								
							$qProducto = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $subPartidaProducto, $codigoProducto);
							$producto = pg_fetch_assoc($qProducto);
				
							$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $exportadorProducto);
							$operador = pg_fetch_assoc($qOperador);
								
							$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																			vue_gateway.tn_agr_031_pd
																		SET
																			expr_ad='".$operador['direccion']."',
																			expr_nole = '".$operador['razon_social']."',
																			prdt_snt = '".$producto['nombre_cientifico']."',
																			num_reg_agr = '$exportadorProducto'
																		WHERE
																			req_no ='$solicitud'
																			and hc ='$subPartidaProducto'
																			and prdt_cd = '$codigoProducto'
																			and expr_idt_no = '$exportadorProducto';");
																									
							}
								
							$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Fitosanitario');
								
					break;
					
				case '101-061':
                
                $controladorTransitoInternacional = new ControladorTransitoInternacional();
                
                $codigoCiudad = $formulario->f061['req_city_cd'];
                $nombreCiudad = $formulario->f061['req_city_nm'];
                $identificadorImportador = $formulario->f061['impr_idt_no'];
                
                $qTransito = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $solicitud);
                $registroTransito = pg_fetch_assoc($qTransito);
                
		
                $res = $this->conexionVUE->ejecutarConsulta("UPDATE
																vue_gateway.tn_agr_061
															SET
																dcm_no = '101-061-RES',
																ctft_no='$solicitud', --Número certificado
																ctft_iss_city_cd='$codigoCiudad', -- codigo de ciudad de emision
																ctft_iss_city_nm='$nombreCiudad', --nombre de ciudad de emision
																req_eftv_stdt='".$registroTransito['fecha_inicio_vigencia']."', --fecha de inicio de vigencia del certificado
																req_eftv_finl_de='".$registroTransito['fecha_fin_vigencia']."' --fecha de final de vigencia de certificador
															WHERE
																req_no ='$solicitud';");
                
                
                $tipoProducto = strtoupper($formulario-> f061['prdt_type_nm']);
                $areas[] = ($tipoProducto== 'VEGETAL'?'SV':($tipoProducto== 'VEGETAL'?'SA':'No definido'));
                
                $this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'TránsitoInternacional',$identificadorImportador);
                
                break;
				
			default: 
					echo 'Formulario desconocido';
		}
		
		/*foreach (array_unique($areas) as $area){
			if($area == 'SA'){
				$this->firmas($solicitud, '1710631993'); //Ing Patricio Almeida
			}else if($area == 'SV'){
				$this->firmas($solicitud, '1710631993'); //1705145306 Ing. Javier Vargas
			}else if($area == 'IAP' || $area == 'IAV'){
				$this->firmas($solicitud, '0400795282'); // 0400795282 Ing Rommel Betancurt
			}else{
				echo 'No definido';
			}
		}*/
		
	}
	
	public function rectificarCamposRespuesta($formulario){
		
		$conexionGUIA = new Conexion();
		$controladorCatalogos = new ControladorCatalogos();
		
		$solicitud = $formulario->numeroDeSolicitud;
		$areas = array();
		
		switch (substr($formulario->formulario,0,7)){
				
			case '101-002':
				
				$controladorImportaciones = new ControladorImportaciones();
				
				$identificadorImportador = $formulario->f002['impr_idt_no'];
				
				$qImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $solicitud);
				$importacion = pg_fetch_assoc($qImportacion);
				
				$valoresTotales = pg_fetch_assoc($controladorImportaciones->obtenerSumaValoresTotalesProductoImportacion($conexionGUIA, $importacion['id_importacion']));
				
				$productosImportacion = $controladorImportaciones->listarHistorialSolicitudes($conexionGUIA, $importacion['id_importacion']);
				
				$codigoMedioTransporte = ($importacion['tipo_transporte']== 'MARITIMO'?'MA':($importacion['tipo_transporte'] == 'AEREO'?'AE':($importacion['tipo_transporte']=='TERRESTRE' ?'TE':'FL')));
				
				$codigoPaisEmbarque = pg_fetch_assoc($controladorCatalogos->obtenerLocalizacion($conexionGUIA, $importacion['id_localizacion']));
				$codigoPuertoEmbarque = pg_fetch_assoc($controladorCatalogos->obtenerPuertoXid($conexionGUIA, $importacion['id_puerto_embarque']));
				$codigoPuertoDestino = pg_fetch_assoc($controladorCatalogos->obtenerPuertoXid($conexionGUIA, $importacion['id_puerto_destino']));
				$codigoMoneda = pg_fetch_assoc($controladorCatalogos->obtenerNombreMoneda($conexionGUIA, $importacion['moneda']));
				$codigoRegimenAduanero = pg_fetch_assoc($controladorCatalogos->obtenerNombreRegimenAduanero($conexionGUIA, $importacion['regimen_aduanero']));
				
				$res = $this->conexionVUE->ejecutarConsulta("UPDATE
																vue_gateway.tn_agr_002
															SET
																expr_nm= $$".$importacion['nombre_exportador']."$$, --Nombre exportador
																expr_ad= $$".$importacion['direccion_exportador']."$$, --Direccion exportador
																shpr_nm= $$".$importacion['nombre_embarcador']."$$, --Nombre embarcador
																trsp_via_nm='".$importacion['tipo_transporte']."', --Nombre medio transporte
																trsp_way_cd='$codigoMedioTransporte', --Codigo medio transporte
																spm_ntn_nm='".strtoupper($importacion['pais_embarque'])."', --Nombre pais de embarque
																spm_ntn_cd='".$codigoPaisEmbarque['codigo_vue']."', --Codigo pais de embarque
																spm_port_cd='".$codigoPuertoEmbarque['codigo_puerto']."', --Codigo puerto de embarque
																spm_port_nm='".strtoupper($importacion['puerto_embarque'])."', --Nombre puerto de embarque
																ptet_cd='".$codigoPuertoDestino['codigo_puerto']."', --Codigo puerto destino
																ptet_nm='".strtoupper($importacion['puerto_destino'])."', --Nombre puerto destino
																cif_val_curr ='".$codigoMoneda['codigo']."', --Codigo moneda
																cutom_rgm_cd =  '".$codigoRegimenAduanero['codigo']."', --Codigo regimen aduanero
																req_eftv_finl_de= '".$importacion['fecha_vigencia']."', --fecha de final de vigencia de certificador,
																mdf_dt = 'now()', --Fecha de modificacion
																prdt_grwg = '".$valoresTotales['peso']."', -- Peso total de la solicitud
																fobv_val_tot = '".$valoresTotales['valor_fob']."', -- Fobv total de la solicitud
																cif_val_tot = '".$valoresTotales['valor_cif']."', -- Cif total de la solicitud
																dcm_func_cd = '34'
															WHERE
																req_no ='$solicitud';");
				
				
				$res = $this->conexionVUE->ejecutarConsulta("DELETE FROM
																vue_gateway.tn_agr_002_pd
															WHERE
																req_no = '$solicitud';");
				
				
				while ($producto = pg_fetch_assoc($productosImportacion)){
					
					$res = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_agr_002_pd
																			(req_no, prdt_sn, hc, prdt_cd, prdt_nm, use_fg, rgs_dt, rgsp_id,
																			mdf_dt, mdfr_id, mdfr_ip, cif_val, fobv_val, prdt_mes, prdt_nwt,
																			prdt_nwt_ut, prdt_qt, prdt_rgs_no)
																		VALUES (
																			'$solicitud', 
																			(SELECT CASE WHEN ((SELECT CAST(COUNT(PRDT_SN) AS NUMERIC(5)) FROM vue_gateway.tn_agr_002_pd WHERE REQ_NO = '$solicitud') = 0) THEN 1
																				ELSE (SELECT MAX(CAST(PRDT_SN AS NUMERIC(5)))+1 FROM vue_gateway.tn_agr_002_pd WHERE REQ_NO = '$solicitud') END),
																			'".$producto['partida_producto_vue']."', '".$producto['codigo_producto_vue']."', '".$producto['nombre_producto_vue']."',
																			'S', '".$formulario->f002['rgs_dt']."', '".$formulario->f002['rgsp_id']."',
																			now(), '".$formulario->f002['mdfr_id']."','".$formulario->f002['mdfr_ip']."', '".$producto['valor_cif']."', '".$producto['valor_fob']."','".$producto['unidad_medida']."',
																			'".$producto['peso']."','KG','".$producto['unidad']."', '".$producto['registro_semillas']."'
																		);");
				}
				
				
				$tipoProducto = strtoupper(($formulario-> f002['prdt_type_nm']));
				$areas[] = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA' ?'IAP':($tipoProducto=='VETERINARIO'? 'IAV' :'No definido'))));
				
				$this->buscarResponsablesfirmas($solicitud, array_unique($areas), 'Importación',$identificadorImportador);
				
				//$this->firmas($solicitud, $importacion['identificador_rectificacion']);
				
				break;
				
			
				
			default:
				echo 'Formulario desconocido';
		}
	}

	public function ingresarSolicitudesXatenderVUE($numeroFormulario,$codigoProcesamiento,$codigoVerificacion,$idVUE, $observacion = null){
		$this->conexionVUE->ejecutarConsulta("INSERT INTO agrocalidad.solicitudes_atender(formulario, codigo_procesamiento, codigo_verificacion, solicitud, estado, observacion)
													VALUES ('$numeroFormulario', '$codigoProcesamiento', '$codigoVerificacion', '$idVUE','Por atender', '$observacion');");
	}
	
	public function ingresarSolicitudesXatenderGUIA($numeroFormulario,$codigoProcesamiento,$codigoVerificacion,$idVUE, $estado, $observacion = null){
		
		$conexionGUIA = new Conexion();
		
		$conexionGUIA->ejecutarConsulta("INSERT INTO g_vue.solicitudes_atender(formulario, codigo_procesamiento, codigo_verificacion, solicitud, estado, observacion)
				VALUES ('$numeroFormulario', '$codigoProcesamiento', '$codigoVerificacion', '$idVUE','$estado', '$observacion');");		

	}
	
	public function ingresarSolicitudesVerificacionTiempoRespuesta($tipoSolicitud, $idVue, $estado = 'subsanacion'){
		
		$conexionGUIA = new Conexion();
		
		$verificacionIdVue = $conexionGUIA->ejecutarConsulta("SELECT * FROM g_vue.estados_solicitudes_vue WHERE id_vue = '$idVue'");
		
		if(pg_num_rows($verificacionIdVue) == 0){
			$conexionGUIA->ejecutarConsulta("INSERT INTO g_vue.estados_solicitudes_vue(tipo_solicitud, id_vue, fecha_registro, cantidad_dia, estado_solicitud) VALUES ('$tipoSolicitud', '$idVue', 'now()', 0, '$estado');");
		}else{
			$datosSolicitud = pg_fetch_assoc($verificacionIdVue);
			$estadoAnterior = $datosSolicitud['estado_solicitud'];
			$conexionGUIA->ejecutarConsulta("UPDATE g_vue.estados_solicitudes_vue SET fecha_registro = 'now()', cantidad_dia = 0, estado_solicitud = '$estado', estado_solicitud_anterior = '$estadoAnterior' WHERE id_vue = '$idVue';");
		}
		
	}
	
	public function obtenerDocumentoAdjuntos($idVue, $codigoVerificacion){
		
		$documentosAdjuntos = $this->conexionVUE->ejecutarConsulta("SELECT 
																		* 
																	FROM 
																		agrocalidad.traer_documentos('$idVue', '$codigoVerificacion')");
		return $documentosAdjuntos;
		
	}
	
	public function obtenerDocumentoAdjuntosIndividual($idVue, $codigoVerificacion, $nombreDocumento){
	
		$documentosAdjuntos = $this->conexionVUE->ejecutarConsulta("SELECT
																		*
																	FROM
																		agrocalidad.traer_documento_individual('$idVue', '$codigoVerificacion', '$nombreDocumento')");
				return $documentosAdjuntos;
	
	}
	
	public function enviarDocumentoAdjuntos($formulario, $codigoVerificacion, $ruta){
		
		$encuentraArchivo = true;
		
		switch (substr($formulario->formulario,0,7)){
			
			case '101-002':
				
				$controladorImportaciones = new ControladorImportaciones();
				$conexionGUIA = new Conexion();
				$solicitud = $formulario->numeroDeSolicitud;
				
				//$idImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $formulario->f002['impr_idt_no'], $formulario->f002['req_no']);
				//$importacion = pg_fetch_assoc($idImportacion);
				
				//$archivo = explode('/', $importacion['informe_requisitos']);
				
				//$nombreArchivo = end($archivo);
				$nombreArchivo = $solicitud.'.pdf';
				$idVue = $formulario->f002['req_no'];
				
			break;
			
			case '101-008':
				
				$controladorZoosanitario = new ControladorZoosanitarioExportacion();
				$conexionGUIA = new Conexion();
				$solicitud = $formulario->numeroDeSolicitud;
				
				//$idZoosanitario = $controladorZoosanitario->buscarZooVUE($conexionGUIA, $formulario->f008['expr_idt_no'], $formulario->f008['req_no']);
				//$zoosanitario = pg_fetch_assoc($idZoosanitario);
				
				//$archivo = explode('/', $zoosanitario['informe_requisitos']);
				
				//$nombreArchivo = end($archivo);
				$nombreArchivo = $solicitud.'.pdf';
				$idVue = $formulario->f008['req_no'];
				
			break;
			
			case '101-031':
				
				$controladorFitosanitario = new ControladorFitosanitario();
				$conexionGUIA = new Conexion();
				$solicitud = $formulario->numeroDeSolicitud;
				
				//$idFitosanitario = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $formulario->f031['req_no']);
				//$fitosanitario = pg_fetch_assoc($idFitosanitario);
				
				//$archivo = explode('/', $fitosanitario['informe_requisitos']);
				
				//$nombreArchivo = end($archivo);
				$nombreArchivo = $solicitud.'.pdf';
				$idVue = $formulario->f031['req_no'];
				
			break;
			
			case '101-034':
				
				$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
				$conexionGUIA = new Conexion();
				
				$qFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $formulario->f034['req_no']);
				$fitosanitarioExportacion = pg_fetch_assoc($qFitosanitarioExportacion);
				
				if($fitosanitarioExportacion['archivo_inspeccion'] != ''){
					$archivo = explode('/', $fitosanitarioExportacion['archivo_inspeccion']);
				}else{
					$encuentraArchivo = false;
				}				
				
			break;
			
			case '101-061':
                
                $controladorTransitoInternacional = new ControladorTransitoInternacional();
                $conexionGUIA = new Conexion();
                
                $transito = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $formulario->f061['impr_idt_no'], $formulario->f061['req_no']);
                $registroTransito = pg_fetch_assoc($transito);
                
                $archivo = explode('/', $registroTransito['informe_requisitos']);
                
                $nombreArchivo = end($archivo);
                $idVue = $formulario->f061['req_no'];
                
            break;
			
			default:
				echo 'Formulario desconocido';
		}
		
		if($encuentraArchivo){
			
			$ruta = trim($ruta);
			
			$documentosAdjuntos = $this->conexionVUE->ejecutarConsulta("INSERT INTO vue_gateway.tn_eld_ntfc_fl_inf(
																				ntfc_fl_id,
																				req_no,
																				ntfc_cl_cd,
																				ord_no,
																				fl_nm,
																				fl_rot_nm,
																				use_fg,
																				rgs_dt,
																				rgsp_id,
																				mdfr_id)
																			VALUES (('AGR' || CAST(to_char(now(),'ymmddhh24missms') AS VARchar)),
																				'$idVue',
																				'$codigoVerificacion',
																				(SELECT CASE WHEN ((SELECT CAST(COUNT(ORD_NO) AS NUMERIC(5)) FROM vue_gateway.tn_eld_ntfc_fl_inf WHERE REQ_NO = '.$idVue.') = 0) THEN 1
																				ELSE (SELECT MAX(CAST(ORD_NO AS NUMERIC(5)))+1 FROM vue_gateway.tn_eld_ntfc_fl_inf WHERE REQ_NO = '.$idVue.') END),
																				'$nombreArchivo',
																				'$ruta', 'S', now(), '192.168.200.6', 'G.U.I.A')");
		}		
		
		return $documentosAdjuntos;
	
	}
	
	public function obtenerDatosRecaudacionTasas($formulario){
		
		$solicitud = $formulario->numeroDeSolicitud;
		
		$recaudacionTasas = $this->conexionVUE->ejecutarConsulta("SELECT 
																	bank_cd as banco,
																	lbct_de as fecha_contable,
																	levy_pric as monto_recaudado,
																	levy_de as fecha_recaudacion,
																	levy_chn_cd as canal_recaudacion,
																	dclr_idt_no as identificador,
																	taxt_pnn as numero_orden_vue,
																	rgsp_id as identificador_pago,
																	ntfc_tp_cd as tipo_pago
																FROM 
																	vue_gateway.tn_eld_taxt_imps_bkdn
																WHERE	
																	req_no = '$solicitud'
																	and ord_no = (SELECT 
																					MAX(ord_no::integer)
																				 FROM 
																					vue_gateway.tn_eld_taxt_imps_bkdn
																				 WHERE	
																					req_no = '$solicitud')::character varying;");
		return $recaudacionTasas;
	}
	
	/*public function verificarConsumoWebServicesBanano($formulario, $accion){
	
		$webServicesBanano = new webServicesBanano();
		$signo = '';
		
		switch ($accion){
			case 'Aprobación':
				$estado = 'SOLICITUD_APROBADA';	
				$signo = '+';			
				break;
			case 'Anulación':
				$estado = 'ANULACION_APROBADA';	
				$signo = '-';
				break;
			default: 
				echo 'Acción desconocida';
		}
		
		for ($i = 0; $i < count ($formulario->f031pd); $i++) {
		
			$partidaArancelaria = substr($formulario->f031pd[$i]['hc'],0,10);
		
			if($partidaArancelaria == '0803101000' || $partidaArancelaria == '0803901100' || $partidaArancelaria == '0803901200' || $partidaArancelaria == '0803901900' || $partidaArancelaria == '0803901190'){
				$musaceas = true;
			}else{
				$musaceas = false;
			}
		
			if($musaceas){
				
				echo '<br/>Autorización: '. $formulario->f031pd[$i]['prdt_per_exp'], '<br/>Cantidad producto: '.$signo.$formulario->f031pd[$i]['prdt_qt'], '<br/>Codigo producto: '. $formulario->f031pd[$i]['prdt_cd'], '<br/>Identificacion: '.$formulario->f031pd[$i]['expr_idt_no'], '<br/>Subpartida: '.$partidaArancelaria, '<br/>Número fitosanitario: '.$formulario->f031['req_no'], '<br/>Estado: '.$estado, '<br/>Pais destino: '.$formulario->f031['dst_ntn_cd'], '<br/>Fecha'.date("j/n/Y");
				
				$webServicesBanano->enviarUtilizacionCupo($formulario->f031pd[$i]['prdt_per_exp'], $signo.$formulario->f031pd[$i]['prdt_qt'], $formulario->f031pd[$i]['prdt_cd'], $formulario->f031pd[$i]['expr_idt_no'], $partidaArancelaria, $formulario->f031['req_no'], $estado, $formulario->f031['dst_ntn_cd'], date("j/n/Y"));
				
				echo IN_MSG.'Actualización cupo servicio web';
			}
		}
		
		
		
		return true;
	}*/
	
	/*public function consultarConsumoWebServices($numeroSolicitud){
		
		$productoBanano = array();
		
		$qProductoBanano = $this->conexionVUE->ejecutarConsulta("SELECT
																	prdt_per_exp, SUM(prdt_qt) as prdt_qt, prdt_cd, expr_idt_no, hc
																FROM
																	vue_gateway.tn_agr_031_pd
																WHERE
																	req_no = '$numeroSolicitud'
																GROUP BY
																	hc,prdt_cd,expr_idt_no,prdt_per_exp");
		while ($fila = pg_fetch_assoc($qProductoBanano)){
			$productoBanano[] = $fila;
		}
				
		return $productoBanano;
	}*/
	
	public function obtenerDatosExtrasFitosanitarioExportacion($idVue, $tabla, $identificadorExportador, $idProducto=null){
		
		$resultado = array();
		$busqueda = '';
		
		switch ($tabla){
			case 'tn_agr_034_pd':
				$busqueda = "expr_sn = '".$identificadorExportador."'";
			break;
			
			case 'tn_agr_034_dt':
				$busqueda = "expr_sn = '".$identificadorExportador."' and  prdt_sn = '".$idProducto."'";
			break;
			
		}
				
		$consulta = "SELECT * FROM vue_gateway.".$tabla." WHERE req_no = '".$idVue."' and ".$busqueda."";
														
		$datos = $this->conexionVUE->ejecutarConsulta($consulta);
		
		while ($fila = pg_fetch_assoc($datos)){
			$resultado[] = $fila;
		}
				
		return $resultado;
	}
	
}

/***************************************************************************************************************************/
/***************************************************************************************************************************/

abstract class FormularioVUE{

	public $formulario;
	private $id;
	private $codigoDeProcesamiento;
	private $codigoDeVerificacion;
	public $numeroDeSolicitud;
	private $tasa;
	//private $campos;
	private $camposObligatorios;
	//private $camposAUCP;


	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios){

		$this->formulario = $formulario;
		$this->id = $id;
		$this->codigoDeProcesamiento = $codigoDeProcesamiento;
		$this->codigoDeVerificacion = $codigoDeVerificacion;
		$this->numeroDeSolicitud = $numeroDeSolicitud;
		//$this->campos = $campos;
		$this->camposObligatorios = $camposObligatorios;
		//$this->camposAUCP = $camposAUCP;
	}

	public function obtenerCodigoDeProcesamiento(){
		return $this->codigoDeProcesamiento;
	}

	public function id(){
		return $this->id;
	}

	public function __toString(){
		return $this->numeroDeSolicitud;
	}

	public function validarCamposObligatorios($datos,$campos){
		foreach ($this->camposObligatorios[$campos] as $campo){
			if (is_null($datos[$campo])) //verificar !isset o empty: http://techtalk.virendrachandak.com/php-isset-vs-empty-vs-is_null/#axzz2n5LnTMvW
				return false;
		}
		return true;
	}

	
	public function asignarAplicacionGUIA($identificador, $nombreAplicacion){
		
		echo IN_MSG. 'Asiganción de la aplicacion al Operador de comercio exterior.';
		
		$controladorAplicaciones = new ControladorAplicaciones();
		$conexionGUIA = new Conexion();
		
		$qAplicacion = $controladorAplicaciones->obtenerIdAplicacion($conexionGUIA,$nombreAplicacion);
		$aplicacion = pg_fetch_result($qAplicacion, 0, 'id_aplicacion');
		
		$aplicacionOperadorRegistro = $controladorAplicaciones -> obtenerAplicacionPerfil($conexionGUIA, $aplicacion, $identificador);
		
		if (pg_num_rows($aplicacionOperadorRegistro) == 0){
			$controladorAplicaciones->guardarAplicacionPerfil($conexionGUIA, $aplicacion, $identificador, 0, 'notificaciones');
		}
		
		return true;
	}
	
	public function  asignarPerfilGUIA($identificador, $nombrePerfil){
		
		echo IN_MSG. 'Asiganción de perfil '.$nombrePerfil;
		
		$conexionGUIA = new Conexion();
		$controladorUsuario = new ControladorUsuarios();
		
		$qPerfil = $controladorUsuario->buscarPerfilUsuario($conexionGUIA, $identificador, $nombrePerfil);
		
		if(pg_num_rows($qPerfil) == 0){
			//Asignar perfil a usuario
			$controladorUsuario->crearPerfilUsuario($conexionGUIA,  $identificador, $nombrePerfil);			
		}		
		return true;
	}
	
	public function  asignarUsuarioGUIA($identificador){
		
		echo IN_MSG. 'Creacion de usuario al Operador de comercio exterior.';
	
		$conexionGUIA = new Conexion();
		$controladorUsuario = new ControladorUsuarios();
	
		$qUsuario = $controladorUsuario->verificarUsuario($conexionGUIA, $identificador);
	
		if(pg_num_rows($qUsuario) == 0){
			
			//Crear Cuenta de usuario
			$controladorUsuario->crearUsuario($conexionGUIA, $identificador, md5($identificador));
			//Activacion de la cuenta del nuevo operador
			$controladorUsuario ->activarCuenta($conexionGUIA, $identificador, md5($identificador));
			
		}
		return true;
	}
	
	public function verificarProductoRepetido($arrayFormularioProductos){
		//TODO: hacer cambios IMPORTANTE------------------------------------------------------------------------------!!!!!!
		$resultadoProducto = false;
				
		$arrayProductos = array(); // creo el array
		
		for ($i = 0; $i < count ($arrayFormularioProductos); $i++) {
			$arrayProductos[] = $arrayFormularioProductos[$i]['hc'].$arrayFormularioProductos[$i]['prdt_cd']; 
		}
		
		$arrayProductosSinRpetidos = array_unique($arrayProductos);
		
		
		if (count($arrayProductos)!= count($arrayProductosSinRpetidos)){
			$resultadoProducto = true; 
		}
		
		return $resultadoProducto;
		
	}
		
	public function  ingresarRegistroOperador($identificador, $razonSocial){
	
		echo IN_MSG. 'Ingreso en tabla de registro de operador.';
	
		$conexionGUIA = new Conexion();
		$controladorRegistroOperador = new ControladorRegistroOperador();
	
		$operador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $identificador); //Metodo que verifica si existe el operador en Agrocalidad
		
		if(pg_num_rows($operador) == 0 ){ //Caso en el que el operador de comercio exterior no existe se inserta en base del sistema GUIA.
			echo IN_MSG. 'El operador no se se encuentra registrado y se procede a registrar';
			$controladorRegistroOperador-> guardarRegistroOperador($conexionGUIA, 'Juridica', $identificador, $razonSocial, $razonSocial, $razonSocial,'','','','','', '', '', '', '','', '', '', '', '');	
		}
		return true;
	}
	
	/*public function  consultaWebServices($numeroSolicitud){
		
		$conexionVUE = new ControladorVUE();
		$webServicesBanano = new webServicesBanano();
		
		$resultado = array();
						
		$productoBanano = $conexionVUE->consultarConsumoWebServices($numeroSolicitud);
		
		
		for ($i = 0; $i < count ($productoBanano); $i++) {			

			try {
							
				echo '<br/>Autorización: '. $productoBanano[$i]['prdt_per_exp'], '<br/>Cantidad producto: '.$productoBanano[$i]['prdt_qt'], '<br/>Codigo producto: '. $productoBanano[$i]['prdt_cd'], '<br/>Identificacion: '.$productoBanano[$i]['expr_idt_no'], '<br/>Subpartida: '.substr($productoBanano[$i]['hc'],0,10);
			
				$resultadoBanano = $webServicesBanano->verificarCupo($productoBanano[$i]['prdt_per_exp'], $productoBanano[$i]['prdt_qt'], $productoBanano[$i]['prdt_cd'], $productoBanano[$i]['expr_idt_no'], substr($productoBanano[$i]['hc'],0,10));
			
			
				if(strpos(strtoupper($resultadoBanano),'OK') === false){
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = $resultadoBanano;
					$resultado[2] = false;
					break;
				}else{
					$resultado[1] = $resultadoBanano;
					$resultado[2] = true;
				}
			
			} catch (Exception $e) {
			
				echo IN_MSG. 'No se logro estrablecer comunicación con el web services.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'No se logro estrablecer comunicación con el web services.';
				$resultado[2] = false;
				break;
			
			}
		}
		
		return $resultado;
	}*/
	
	
	abstract public function validarDatosFormulario(); //Verifica que los datos esten completos o lo que sea
	abstract public function validarActualizacionDeDatos(); //Verifica que los datos esten completos o lo que sea

	abstract public function insertarDatosEnGUIA(); //Copiar la información necesaria en nuestras tablas
	abstract public function actualizarDatosEnGUIA(); //Actualizar la información necesaria en nuestras tablas

	//abstract public function escribirDatosAFrontera();//????????
	abstract public function recaudacionTasa($recaudacionTasas); //Proceso de calculo de tasas
	abstract public function cancelar(); //Cancelar el procesamiento de una solicitud en GUIA
	abstract public function anular(); //Anular una solicitud ya aprobada en GUIA
	
	abstract public function insertarDocumentosAdjuntosGUIA($codigoVerificacion); //Traer documentos adjuntos a GUIA
	
	abstract public function reversoSolicitud();
	abstract public function generarFacturaProcesoAutomatico();

}

/***************************************************************************************************************************/
/***************************************************************************************************************************/

class RegistroOperador extends FormularioVUE{

	//	private $controladorRegistroOperador;
	//	private $coneccionGUIA;
	public $f001;
	public $f001pd = array();

	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){

		$camposObligatorios = array(
				'cabecera' => array(
						'IMPR_NM',
						'IMPR_RPST_NM',
						'IMP_PRVHC_NM',
						'IMPR_CUTY_NM',
						'IMPR_PRQI_NM',
						'IMPR_AD',
						'IMPR_TEL_NO',
						'IMPR_CEL_NO',
						'IMPR_EM'),
				'productos' => array(
						'REQ_NO',
						'HC',
						'PRDT_NM',
						'ORG_NTN_CD',
						'PRDT_CD',
						'AGRCD_PRDT_CD',
						'PRDT_BUSS_ACT_CD',
						'PRDT_BUSS_ACT_NM',
						'PRDT_TYPE_NM',
						'PRDT_STN')
		);

		//Trayendo los datos de cabecera del formulario 101-001

		$this-> f001 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_001
				WHERE REQ_NO = '$numeroDeSolicitud'"));

		//print_r($this->f001);
			
		//Trayendo los datos de detalle del formulario 101-001

		$c_f001pd = $coneccionVUE ->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_001_pd
				WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($fila = pg_fetch_assoc($c_f001pd)){
			$this-> f001pd[] = $fila;
		}

		/* echo '<br/><br/>';

		print_r($this->f001pd); */

		parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
	}

	public function validarDatosFormulario(){
		echo  PRO_MSG. 'validando formulario';
		$solicitudEsValida = true;
		$resultado = array();

		parent::validarCamposObligatorios($f001,'cabecera');
		parent::validarCamposObligatorios($f001_pd,'productos');

		/*TODO:
		 * 1. validar que todos los datos obligatorios esten presentes
		* 2. validar que el proveedor exista*/

		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();


		for ($i = 0; $i < count ($this->f001pd); $i++) {

			$actividad = ($this->f001pd[$i]['prdt_buss_act_cd'] == '01'?'Importación':'Exportación');
			
			$partidaArancelariaVUE = $this->f001pd[$i]['hc'];   
			$codigoProductoVUE = $this->f001pd[$i]['prdt_cd'];
			
			$tipoProducto = strtoupper(($this-> f001pd[$i]['prdt_type_nm']));			
			$area = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA'?'IAP':( $tipoProducto=='VETERINARIO'? 'IAV' :( $tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
			
			if(strlen($codigoProductoVUE) > 5){  // Validación productos presentación.
				$solicitudEsValida = false;
				echo IN_MSG. 'El producto '.$this->f001pd[$i]['prdt_nm'].' contiene una presentación por favor seleccione el producto sin presentación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El producto '.$this->f001pd[$i]['prdt_nm'].' contiene una presentación por favor seleccione el producto sin presentación.';
				break;
			}
			
			
			if(strlen($codigoProductoVUE) < 5){  // Validación productos presentación.
				$solicitudEsValida = false;
				echo IN_MSG. 'El producto '.$this->f001pd[$i]['prdt_nm'].' no pertence a Agrocalidad. Debe seleccionar productos que tengan en su codigo la letra A .';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El producto '.$this->f001pd[$i]['prdt_nm'].' no pertence a Agrocalidad. Debe seleccionar productos que tengan en su codigo la letra A .';
				break;
			}
				
			$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
			$producto = pg_fetch_assoc($qProductoGUIA);
		
			if( pg_num_rows($qProductoGUIA) == 0 ){ // Validación producto registrado en agrocalidad.
				$solicitudEsValida = false;
				echo IN_MSG. 'El producto '.$this->f001pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El producto '.$this->f001pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}

			$operador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd']); // Metodo que busca si existe el operador
			if( pg_num_rows($operador) == 0 ){ //Si no existe el proveedor se envia error.
				$solicitudEsValida = false;
				echo IN_MSG. 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no se encuentra registrado en Agrocalidad';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no se encuentra registrado en Agrocalidad';
				break;
			}

			$productoProveedor = $controladorRegistroOperador -> listaProductoProveedor($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd'], $producto['id_producto']); // Metodo que verifica si el proveedor esta asociado a un producto
			if( pg_num_rows($productoProveedor) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no tiene registrado el produto '.$this->f001pd[$i]['prdt_nm'].' ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no tiene registrado el produto '.$this->f001pd[$i]['prdt_nm'].' ';
				break;
			}

				
			$codigoPaisVUE = $this->f001pd[$i]['org_ntn_cd'];
			
			$qCodigoPais = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisVUE); //Validación del pais
			$codigoPais = pg_fetch_assoc($qCodigoPais);
			
			if( pg_num_rows($qCodigoPais) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. 'El pais '.$this->f001pd[$i]['org_ntn_nm'].' no se encuentra registrado en agrocalidad ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El pais '.$this->f001pd[$i]['org_ntn_nm'].' no se encuentra registrado en agrocalidad ';
				break;
			}
			
			$qProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f001['impr_prvhc_cd']); //Validación de provincia
			if(pg_num_rows($qProvincia) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. "La provincia : ".$this->f001['impr_prvhc_nm']." no se encuentra registrado";
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = "La provincia : ".$this->f001['impr_prvhc_nm']." no se encuentra registrado";
				break;
			}
			
						
			if($producto['id_area'] == 'SA' || $producto['id_area'] == 'SV'){
				
				$produtoPais = $controladorRequisitos->consultarProductoPais($conexionGUIA,  $codigoPais['id_localizacion'] , $producto['id_producto'] ,$actividad); // Metodo que verifica el Producto Pais Requisito
				if( pg_num_rows($produtoPais) == 0 ){
					$solicitudEsValida = false;
					echo IN_MSG. 'El producto '.$this->f001pd[$i]['prdt_nm'].' no esta permitido en el pais '.$this->f001pd[$i]['org_ntn_nm'].' para la actividad de '.$actividad;
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] =  'El producto '.$this->f001pd[$i]['prdt_nm'].' no esta permitido en el pais '.$this->f001pd[$i]['org_ntn_nm'].' para la actividad de '.$actividad;
					break;
				}
			}
						
			$tipoOperacion = ($actividad == 'Importación'?'Importador': 'Exportador');
		
			$idActividad  = $controladorCatalogos -> buscarIdOperacion($conexionGUIA, $producto['id_area'], $tipoOperacion);
			$operacionRegistrada = $controladorRegistroOperador->buscarOperacionProductoPais($conexionGUIA,$this->f001['impr_idt_no'],pg_fetch_result($idActividad, 0, 'id_tipo_operacion'),$producto['id_producto'], $codigoPais['id_localizacion']); //Metodo que verifica si el proveedor tiene el producto para esa actividad en un pais
		
			if(pg_num_rows($operacionRegistrada)!= 0){
				$solicitudEsValida = false;
				echo IN_MSG. 'El operador '.$this->f001['impr_idt_no'].' ya tiene registrada la operación de  '.$tipoOperacion.' para el producto '.$this->f001pd[$i]['prdt_nm'].' con la solicitud '.pg_fetch_result($operacionRegistrada, 0, 'id_vue');
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] =  'El operador '.$this->f001['impr_idt_no'].' ya tiene registrada la operación de  '.$tipoOperacion.' para el producto '.$this->f001pd[$i]['prdt_nm'].' con la solicitud '.pg_fetch_result($operacionRegistrada, 0, 'id_vue');
				break;
			}
			
			//TODO: Agregar productos IMPORTANTE------------------------------------------------------------------------------!!!!!!
		}
		
		//TODO: validar productos IMPORTANTE------------------------------------------------------------------------------!!!!!!
		
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
	}

	public function validarActualizacionDeDatos(){
		
		echo  PRO_MSG. 'validando actualizacion de formulario ';
		$solicitudEsValida = true;
		$resultado = array();
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		
		for ($i = 0; $i < count ($this->f001pd); $i++) {
						
			$qOperador = $controladorRegistroOperador->buscarOperadorVUE($conexionGUIA, $this->f001['req_no']);
			$operador = pg_fetch_assoc($qOperador);
			
			$tipoProducto = strtoupper(($this-> f001pd[$i]['prdt_type_nm']));
			
			$area = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA'?'IAP':( $tipoProducto=='VETERINARIO'? 'IAV' :( $tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
			
			
			//if($operador['identificador'] != $this->f001['impr_idt_no'] || $operador['nombre_representante'] != $this->f001['impr_nm']){
			if($operador['identificador'] != $this->f001['impr_idt_no']){
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar datos del operador de comercio exterior';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar datos del operador de comercio exterior';
				break;
			}
		
			$actividad = ($this->f001pd[$i]['prdt_buss_act_cd'] == '01'?'Importación':'Exportación');
				
			$partidaArancelariaVUE = $this->f001pd[$i]['hc'];
			$codigoProductoVUE = $this->f001pd[$i]['prdt_cd'];
		
			if(strlen($codigoProductoVUE) !='5'){  // Validación productos presentación.
				$solicitudEsValida = false;
				echo IN_MSG. 'El producto '.$this->f001pd[$i]['prdt_nm'].' contiene una presentación por favor seleccione el producto sin presentación.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'El producto '.$this->f001pd[$i]['prdt_nm'].' contiene una presentación por favor seleccione el producto sin presentación.';
				break;
			}
				
			$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
			$producto = pg_fetch_assoc($qProductoGUIA);
		
				
			if( pg_num_rows($qProductoGUIA) == 0 ){ // Validación producto registrado en agrocalidad.
				$solicitudEsValida = false;
				echo IN_MSG. 'El producto '.$this->f001pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'El producto '.$this->f001pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
		
			$operador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd']); // Metodo que busca si existe el proveedor
			
			if( pg_num_rows($operador) == 0 ){ //Si no existe el proveedor se envia error.
				$solicitudEsValida = false;
				echo IN_MSG. 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no se encuentra registrado en Agrocalidad';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no se encuentra registrado en Agrocalidad';
				break;
			}
		
			$productoProveedor = $controladorRegistroOperador -> listaProductoProveedor($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd'], $producto['id_producto']); // Metodo que verifica si el proveedor esta asociado a un producto
			if( pg_num_rows($productoProveedor) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no tiene registrado el produto '.$this->f001pd[$i]['prdt_nm'].' ';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'El proveedor '.$this->f001pd[$i]['agrcd_prdt_cd'].' no tiene registrado el produto '.$this->f001pd[$i]['prdt_nm'].' ';
				break;
			}
		
		
			$codigoPaisVUE = $this->f001pd[$i]['org_ntn_cd'];
				
			$qCodigoPais = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisVUE); //Validación del pais
			$codigoPais = pg_fetch_assoc($qCodigoPais);
				
			if( pg_num_rows($qCodigoPais) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. 'El pais '.$this->f001pd[$i]['org_ntn_nm'].' no se encuentra registrado en agrocalidad ';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'El pais '.$this->f001pd[$i]['org_ntn_nm'].' no se encuentra registrado en agrocalidad ';
				break;
			}
				
			if($producto['id_area'] == 'SV' || $producto['id_area'] == 'SA'){
				$produtoPais = $controladorRequisitos->consultarProductoPais($conexionGUIA,  $codigoPais['id_localizacion'] , $producto['id_producto'] ,$actividad); // Metodo que verifica el Producto Pais Requisito
				if( pg_num_rows($produtoPais) == 0 ){
					$solicitudEsValida = false;
					echo IN_MSG. 'El producto '.$this->f001pd[$i]['prdt_nm'].' no esta permitido en el pais '.$this->f001pd[$i]['org_ntn_nm'].' para la actividad de '.$actividad;
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'El producto '.$this->f001pd[$i]['prdt_nm'].' no esta permitido en el pais '.$this->f001pd[$i]['org_ntn_nm'].' para la actividad de '.$actividad;
					break;
				}
			}

		}
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
		
	}

	public function insertarDatosEnGUIA(){

		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorUsuario = new ControladorUsuarios();
		$controladorAplicaciones = new ControladorAplicaciones();
		$controladorCatalogos = new ControladorCatalogos();
		//$controladorVUE = new ControladorVUE();
		$conexionGUIA = new Conexion();


		$operador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $this->f001['impr_idt_no']); //Metodo que verifica si existe el operador en Agrocalidad
		/*TODO:
		 * Verificar que no exista el usuario y perfil
		*/

		if(pg_num_rows($operador) == 0 ){ //Caso en el que el operador de comercio exterior no existe se inserta en base del sistema GUIA.

			echo IN_MSG. 'El operador no se se encuentra registrado y se procede a registrar';

			//Creacion del registro de operador
			$controladorRegistroOperador-> guardarRegistroOperador($conexionGUIA, 'Juridica', $this->f001['impr_idt_no'], $this->f001['impr_nm'], $this->f001['impr_nm'], $this->f001['impr_nm'],'','',
					$this->f001['impr_prvhc_nm'],$this->f001['impr_cuty_nm'],$this->f001['impr_prqi_nm'], $this->f001['impr_ad'], $this->f001['impr_tel_no'], '', $this->f001['impr_cel_no'],
					'', $this->f001['dclr_fax_no'], $this->f001['impr_em'], md5($this->f001['impr_idt_no']), $this->f001['req_no']);

			echo IN_MSG. 'Creación de la cuenta en el sistema GUIA.';
				
			parent::asignarUsuarioGUIA($this->f001['impr_idt_no']);
			
			parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Usuario externo');
			parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Operadores de Comercio Exterior');
			parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Operadores');
			
			//Asignacion de la aplicacion de "registro de operador" al operador
			
			parent::asignarAplicacionGUIA( $this->f001['impr_idt_no'], 'PRG_REGISTROOPER');

			//Creacion del sitio por defecto Oficina Tributaria
			
			$idProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f001['impr_prvhc_cd']); //Obtener el id de la localizacion
			
			$qSecuencialSitio = $controladorRegistroOperador->obtenerSecuencialSitio($conexionGUIA, pg_fetch_result($idProvincia, 0,'nombre'),$this->f001['impr_idt_no']);
			$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
			
			//Inserta el sitio para el operador
			$cIdSitio = $controladorRegistroOperador->guardarNuevoSitio($conexionGUIA, 'Oficina Central', pg_fetch_result($idProvincia, 0,'nombre'),
					$this->f001['impr_cuty_nm'], $this->f001['impr_prqi_nm'], $this->f001['impr_ad'], '', 0, $this->f001['impr_idt_no'], $this->f001['impr_tel_no'],
					'-1.537901237431487', '-78.99169921875', $secuencialSitio, '0','17', substr(pg_fetch_result($idProvincia, 0, 'codigo_vue'),1));

			//Inserta el area para el operador
			
			$qCodigoArea = $controladorCatalogos->buscarAreaOperadorXNombre($conexionGUIA, 'Domicilio tributario');
			$codigoArea = pg_fetch_assoc($qCodigoArea);
			
			$qSecuencialArea = $controladorRegistroOperador-> obtenerSecuencialArea($conexionGUIA, $this->f001['impr_idt_no'], $codigoArea['codigo'],pg_fetch_result($idProvincia, 0, 'nombre'));
			$secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
			
			$areaCreada = $controladorRegistroOperador -> guardarNuevaArea($conexionGUIA, 'Oficina Principal', 'Domicilio tributario', 0, pg_fetch_result($cIdSitio, 0, 'id_sitio'),$codigoArea['codigo'],$secuencial);

			echo IN_MSG. 'Se registra el area de operación del Operador de comercio exterior.';

			//TODO: consulta de oeprador tipo operacion IMPORTANTE------------------------------------------------------------------------------!!!!!!
			
			for ($i = 0; $i < count ($this->f001pd); $i++) { //Por cada uno de los producto ingresados se genera una nueva operacion para el operador.

				//Tipo de operacion ingresada por el usuario en VUE
				$nombreActividad = ($this->f001pd[$i]['prdt_buss_act_cd'] == '01'?'Importador':'Exportador');

				//$producto = $controladorCatalogos ->obtenerIdProducto($conexionGUIA, $this->f001pd[$i]['prdt_nm']); //Busco el codigo del producto en base GUIA

				$partidaArancelariaVUE = $this->f001pd[$i]['hc'];
				$codigoProductoVUE = $this->f001pd[$i]['prdt_cd'];
				
				$tipoProducto = strtoupper(($this-> f001pd[$i]['prdt_type_nm']));
				
				$area = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA'?'IAP':( $tipoProducto=='VETERINARIO'? 'IAV' :( $tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
				

				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
				$producto = pg_fetch_assoc($qProductoGUIA);
					
				/////////////////////////////////////////Buscar Actividad//////////////////////////////////////////
				//	$areaProducto = $controladorCatalogos->obtenerAreaProductos($conexionGUIA, $producto['id_producto']);
				$idActividad  = $controladorCatalogos -> buscarIdOperacion($conexionGUIA, $producto['id_area'], $nombreActividad);
				/////////////////////////////////////////
				
				$codigoPaisVUE = $this->f001pd[$i]['org_ntn_cd'];
				$qCodigoPais = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisVUE);
				$codigoPais = pg_fetch_assoc($qCodigoPais);
				
				//TODO: aqui es el proceso IMPORTANTE------------------------------------------------------------------------------!!!!!!
				
				$qDatosOperacionAsociada = $controladorRegistroOperador->obtenerIdOperadorTipoOperacionHistorialXIdentificadorTipoOperacionSitio($conexionGUIA, $this->f001['impr_idt_no'],  pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), pg_fetch_result($cIdSitio, 0, 'id_sitio'), " not in ('eliminado')");
				
				if(pg_num_rows($qDatosOperacionAsociada) == 0){
				    $idOperadorTipoOperacion = pg_fetch_result($controladorRegistroOperador->guardarTipoOperacionPorIndentificadorSitio($conexionGUIA, $this->f001['impr_idt_no'], pg_fetch_result($cIdSitio, 0, 'id_sitio'), pg_fetch_result($idActividad, 0, 'id_tipo_operacion')), 0, 'id_operador_tipo_operacion');
				    $historicoOperacion = pg_fetch_result($controladorRegistroOperador->guardarDatosHistoricoOperacion($conexionGUIA,$idOperadorTipoOperacion), 0, 'id_historial_operacion');
				    $nuevaOperacion = true;
				}else{
				    $datosOperacionAsociada = pg_fetch_assoc($qDatosOperacionAsociada);
				    $idOperadorTipoOperacion = $datosOperacionAsociada['id_operador_tipo_operacion'];
				    $historicoOperacion = $datosOperacionAsociada['id_historial_operacion'];
				    $nuevaOperacion = false;
				}
					
				//Guardar la operacion del operador
				$operacion = $controladorRegistroOperador->guardarNuevaOperacion($conexionGUIA, pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $this->f001['impr_idt_no'], $producto['id_producto'], $producto['nombre_comun'], $idOperadorTipoOperacion, $historicoOperacion, 'moduloExterno', $codigoPais['id_localizacion'], $codigoPais['nombre'], $this->f001['req_no'], $this->f001pd[$i]['hc'], $this->f001pd[$i]['prdt_cd']);
				
								
				//Guardar el area en la que ejerce la operacion.
				$idAreas = $controladorRegistroOperador->guardarAreaOperacion($conexionGUIA, pg_fetch_result($areaCreada, 0, 'id_area'), pg_fetch_result($operacion, 0, 'id_operacion'));
				
				if($nuevaOperacion){
				    $controladorRegistroOperador->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexionGUIA, $idOperadorTipoOperacion, pg_fetch_result($operacion, 0, 'id_operacion'));
				    $controladorRegistroOperador->guardarAreaPorIdentificadorTipoOperacion($conexionGUIA, pg_fetch_result($areaCreada, 0, 'id_area'), $idOperadorTipoOperacion);
				    $controladorRegistroOperador-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexionGUIA, $idOperadorTipoOperacion, 'registrado');
				}

				//Creacion de proveedores					
				$controladorRegistroOperador->guardarNuevoProveedorComercioExterior($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd'], $this->f001['impr_idt_no'], pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $nombreActividad, $producto['id_producto'], $producto['nombre_comun'], $codigoPais['id_localizacion'], $codigoPais['nombre'], $this->f001['req_no']);
					

				//Cambio de estado en el area y operacion en el caso de ser producto de sanidad animal y sanidad vegetal.
				//if($producto['id_area'] == 'SA' || $producto['id_area'] == 'SV'){
					$res = $controladorRegistroOperador -> enviarOperacion($conexionGUIA, pg_fetch_result($operacion, 0, 'id_operacion'),'registrado');
					//Cambia el estado del area operacion producto a aprobado.
					$res = $controladorRegistroOperador -> cambiarEstadoSolicitudArea($conexionGUIA, pg_fetch_result($idAreas, 0, 'id_producto_area_operacion'), 'registrado');
					
				//}
				
				//ASIGNACION DE MODULO DE TRAZABILIDAD
				
				/*if($nombreActividad == 'Exportador' && $producto['id_area'] == 'SV'){
						
					$qOperacionesCacao=$controladorRegistroOperador->buscarOperacionesPorCodigoyAreaOperacionCacao($conexionGUIA, $this->f001['impr_idt_no'], "('ACO')", "('SV')");
						
					if(pg_num_rows($qOperacionesCacao)>0){
				
						parent::asignarAplicacionGUIA( $this->f001['impr_idt_no'], 'PRG_CONFO_LOTE');
						parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Administador conformación de lotes');
				
				
					}
						
				}*/
				
				//ASIGNACION DE MODULO DE INSPECCION DE PRODUCTOS
				if($producto['proceso_banano'] == 't' && $producto['id_area'] == 'SV' && $nombreActividad == 'Exportador'){
					parent::asignarAplicacionGUIA( $this->f001['impr_idt_no'], 'PRG_INSP_MUS');
					parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Inspección Musáceas Usuario Externo');
				}
				
			}

			echo IN_MSG. 'Creación de la nueva operación para el Operador de comercio exterior.';

			return true;
		}else{ //Caso en el que el operador de comercio exterior si existe en base del sistema GUIA.
			
			parent::asignarUsuarioGUIA($this->f001['impr_idt_no']);
			
			parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Usuario externo');
			parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Operadores de Comercio Exterior');
			parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Operadores');
			
			parent::asignarAplicacionGUIA( $this->f001['impr_idt_no'], 'PRG_REGISTROOPER');

			$areaIngresada = $controladorRegistroOperador->buscarAreaOperador($conexionGUIA, $this->f001['impr_idt_no'], 'Domicilio tributario');
			
			$creacionSitio = false;

			if(pg_num_rows($areaIngresada)== 0){
				
				$idProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f001['impr_prvhc_cd']);//Obtener el id de la localizacion
				
				$qSecuencialSitio = $controladorRegistroOperador->obtenerSecuencialSitio($conexionGUIA, pg_fetch_result($idProvincia, 0,'nombre'),$this->f001['impr_idt_no']);
				$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);
				
				//Inserta el sitio para el operador
				$cIdSitio = $controladorRegistroOperador->guardarNuevoSitio($conexionGUIA, 'Oficina Central', pg_fetch_result($idProvincia, 0,'nombre'),
						$this->f001['impr_cuty_nm'], $this->f001['impr_prqi_nm'], $this->f001['impr_ad'], '', 0, $this->f001['impr_idt_no'], $this->f001['impr_tel_no'],
						'-1.537901237431487', '-78.99169921875', $secuencialSitio, '0','17', substr(pg_fetch_result($idProvincia, 0, 'codigo_vue'),1));
				
				//Inserta el area para el operador
				
				$qCodigoArea = $controladorCatalogos->buscarAreaOperadorXNombre($conexionGUIA, 'Domicilio tributario');
				$codigoArea = pg_fetch_assoc($qCodigoArea);
					
				$qSecuencialArea = $controladorRegistroOperador-> obtenerSecuencialArea($conexionGUIA, $this->f001['impr_idt_no'], $codigoArea['codigo'],pg_fetch_result($idProvincia, 0, 'nombre'));
				$secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);
					
				
				$areaIngresada = $controladorRegistroOperador -> guardarNuevaArea($conexionGUIA, 'Oficina Principal', 'Domicilio tributario', 0, pg_fetch_result($cIdSitio, 0, 'id_sitio'), $codigoArea['codigo'], $secuencial);
				
				$creacionSitio = true;
				
			}
			
			if($creacionSitio){
			    $nIdSitio = pg_fetch_result($cIdSitio, 0, 'id_sitio');
			}else{
			    $nIdSitio = pg_fetch_result($areaIngresada, 0, 'id_sitio');
			}
			
			//TODO: consulta de oeprador tipo operacion IMPORTANTE------------------------------------------------------------------------------!!!!!!
			
			for ($i = 0; $i < count ($this->f001pd); $i++) { //Por cada uno de los producto ingresados se genera una nueva operacion para el operador.

				//Tipo de operacion ingresada por el usuario en VUE
				$nombreActividad = ($this->f001pd[$i]['prdt_buss_act_cd'] == '01'?'Importador':'Exportador');

				//$producto = $controladorCatalogos ->obtenerIdProducto($conexionGUIA, $this->f001pd[$i]['prdt_nm']); //Busco el codigo del producto en base GUIA

				$partidaArancelariaVUE = $this->f001pd[$i]['hc'];
				$codigoProductoVUE = $this->f001pd[$i]['prdt_cd'];
				
				$tipoProducto = strtoupper(($this-> f001pd[$i]['prdt_type_nm']));
				
				$area = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA'?'IAP':( $tipoProducto=='VETERINARIO'? 'IAV' :( $tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
				
				
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
				$producto = pg_fetch_assoc($qProductoGUIA);

				//$areaProducto = $controladorCatalogos->obtenerAreaProductos($conexionGUIA, $producto['id_producto']);
				$idActividad  = $controladorCatalogos -> buscarIdOperacion($conexionGUIA, $producto['id_area'], $nombreActividad);

				//Guardar la operacion del operador
				
				$codigoPaisVUE = $this->f001pd[$i]['org_ntn_cd'];
				$qCodigoPais = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisVUE);
				$codigoPais = pg_fetch_assoc($qCodigoPais);
				
				//TODO: aqui es el proceso IMPORTANTE------------------------------------------------------------------------------!!!!!!
				$qDatosOperacionAsociada = $controladorRegistroOperador->obtenerIdOperadorTipoOperacionHistorialXIdentificadorTipoOperacionSitio($conexionGUIA, $this->f001['impr_idt_no'],  pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $nIdSitio, " not in ('eliminado')");
				
				if(pg_num_rows($qDatosOperacionAsociada) == 0){
				    $idOperadorTipoOperacion = pg_fetch_result($controladorRegistroOperador->guardarTipoOperacionPorIndentificadorSitio($conexionGUIA, $this->f001['impr_idt_no'], $nIdSitio, pg_fetch_result($idActividad, 0, 'id_tipo_operacion')), 0, 'id_operador_tipo_operacion');
				    $historicoOperacion = pg_fetch_result($controladorRegistroOperador->guardarDatosHistoricoOperacion($conexionGUIA,$idOperadorTipoOperacion), 0, 'id_historial_operacion');
				    $nuevaOperacion = true;
				}else{
				    $datosOperacionAsociada = pg_fetch_assoc($qDatosOperacionAsociada);
				    $idOperadorTipoOperacion = $datosOperacionAsociada['id_operador_tipo_operacion'];
				    $historicoOperacion = $datosOperacionAsociada['id_historial_operacion'];
				    $nuevaOperacion = false;
				}
				
				$operacion = $controladorRegistroOperador->guardarNuevaOperacion($conexionGUIA, pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $this->f001['impr_idt_no'], $producto['id_producto'], $this->f001pd[$i]['prdt_nm'], $idOperadorTipoOperacion, $historicoOperacion, 'moduloExterno', $codigoPais['id_localizacion'], $codigoPais['nombre'],  $this->f001['req_no'],  $this->f001pd[$i]['hc'], $this->f001pd[$i]['prdt_cd']);

				//Guardar el area en la que ejerce la operacion.
				$idAreas = $controladorRegistroOperador->guardarAreaOperacion($conexionGUIA, pg_fetch_result($areaIngresada, 0, 'id_area'), pg_fetch_result($operacion, 0, 'id_operacion'));

				if($nuevaOperacion){
				    $controladorRegistroOperador->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexionGUIA, $idOperadorTipoOperacion, pg_fetch_result($operacion, 0, 'id_operacion'));
				    $controladorRegistroOperador->guardarAreaPorIdentificadorTipoOperacion($conexionGUIA, pg_fetch_result($areaIngresada, 0, 'id_area'), $idOperadorTipoOperacion);
				    $controladorRegistroOperador-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexionGUIA, $idOperadorTipoOperacion, 'registrado');
				}
				
				//Creacion de proveedores
					
				$controladorRegistroOperador->guardarNuevoProveedorComercioExterior($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd'], $this->f001['impr_idt_no'], pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $nombreActividad, $producto['id_producto'], $producto['nombre_comun'], $codigoPais['id_localizacion'], $codigoPais['nombre'], $this->f001['req_no']);

				//Cambio de estado en el area y operacion en el caso de ser producto de sanidad animal y sanidad vegetal.
					
				//if($producto['id_area'] == 'SA' || $producto['id_area'] == 'SV'){
					$res = $controladorRegistroOperador -> enviarOperacion($conexionGUIA, pg_fetch_result($operacion, 0, 'id_operacion'),'registrado');
					//Cambia el estado del area operacion producto a aprobado.
					$res = $controladorRegistroOperador -> cambiarEstadoSolicitudArea($conexionGUIA, pg_fetch_result($idAreas, 0, 'id_producto_area_operacion'), 'registrado');
					
				//}
				
				//ASIGNACION DE MODULO DE TRAZABILIDAD
				
				/*if($nombreActividad == 'Exportador' && $producto['id_area'] == 'SV'){
						
					$qOperacionesCacao=$controladorRegistroOperador->buscarOperacionesPorCodigoyAreaOperacionCacao($conexionGUIA, $this->f001['impr_idt_no'], "('ACO')", "('SV')");
						
					if(pg_num_rows($qOperacionesCacao)>0){
				
						parent::asignarAplicacionGUIA( $this->f001['impr_idt_no'], 'PRG_CONFO_LOTE');
						parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Administador conformación de lotes');
				
				
					}
						
				}*/
				
				//ASIGNACION DE MODULO DE INSPECCION DE PRODUCTOS
				if($producto['proceso_banano'] == 't' && $producto['id_area'] == 'SV' && $nombreActividad == 'Exportador'){
					parent::asignarAplicacionGUIA( $this->f001['impr_idt_no'], 'PRG_INSP_MUS');
					parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Inspección Musáceas Usuario Externo');
				}
			}
			echo IN_MSG. 'Creación de la nueva operación para el Operador de comercio exterior.';
		
		}
	}

	public function actualizarDatosEnGUIA(){
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();

		$actualizarOperador = false;

		$provincia = $this->f001['impr_prvhc_nm'];
		$canton = $this->f001['impr_cuty_nm'];
		$parroquia = $this->f001['impr_prqi_nm'];
		$direccion = $this->f001['impr_ad'];
		$telefono = $this->f001['impr_tel_no'];
		$celular = $this->f001['impr_cel_no'];
		$correo = $this->f001['impr_em'];
		
		//$productosVUE = array();
		//$productoGUIA = array();
		
		$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $this->f001['impr_idt_no']);
		$operador = pg_fetch_assoc($qOperador);
			

		if( strtoupper($operador['provincia']) != $provincia ){
			$actualizarOperador = true;
		}

		if( strtoupper($operador['canton']) != $canton ){
			$actualizarOperador = true;
		}

		if( strtoupper($operador['parroquia']) !=  $parroquia){
			$actualizarOperador = true;
		}

		if( strtoupper($operador['direccion']) !=  $direccion){
			$actualizarOperador = true;
		}

		if( strtoupper($operador['telefono_uno']) !=  $telefono){
			$actualizarOperador = true;
		}

		if( strtoupper($operador['celular_uno']) !=  $celular){
			$actualizarOperador = true;
		}

		if( strtoupper($operador['correo']) !=  $correo){
			$actualizarOperador = true;
		}

		if($actualizarOperador){
			$controladorRegistroOperador->actualizaOperadorVUE($conexionGUIA, $operador['identificador'], $provincia, $canton, $parroquia, $direccion, $telefono, $celular, $correo);

			$operacion = $controladorRegistroOperador->buscarOperacionVue($conexionGUIA,  $this->f001['impr_idt_no'], $this->f001['req_no']);
			$sitio = $controladorRegistroOperador->abrirOperacion($conexionGUIA, $this->f001['impr_idt_no'], pg_fetch_result($operacion, 0, 'id_operacion'));
			$controladorRegistroOperador->actualizarSitioVUE($conexionGUIA, $sitio[0]['idSitio'], $provincia, $canton, $parroquia, $direccion,telefono);
			$controladorRegistroOperador->actualizarFechaModificacionOperacion($conexionGUIA,pg_fetch_result($operacion, 0, 'id_operacion'));

			echo OUT_MSG. 'Actualización de datos del Operador de comercio exterior.';
		}

		$areaIngresada = $controladorRegistroOperador->buscarAreaOperador($conexionGUIA, $this->f001['impr_idt_no'], 'Domicilio tributario');
		
		$controladorRegistroOperador->eliminarProveedoresVUE($conexionGUIA, $this->f001['req_no']);
		
		$o = 0;
		
		for ($i = 0; $i < count ($this->f001pd); $i++) {
			
			$nombreActividad = $this->f001pd[$i]['prdt_buss_act_cd'] == '01'?'Importador':'Exportador';

			$partidaArancelariaVUE = $this->f001pd[$i]['hc'];
			$codigoProductoVUE = $this->f001pd[$i]['prdt_cd'];
			
			$tipoProducto = strtoupper(($this-> f001pd[$i]['prdt_type_nm']));
			
			$area = ($tipoProducto== 'ANIMAL'?'SA':($tipoProducto == 'VEGETAL'?'SV':($tipoProducto=='PLAGUICIDA'?'IAP':( $tipoProducto=='VETERINARIO'? 'IAV' :( $tipoProducto=='FERTILIZANTE'? 'IAF' :'No definido')))));
			
			
			$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
			$producto = pg_fetch_assoc($qProductoGUIA);
				
			$idActividad  = $controladorCatalogos -> buscarIdOperacion($conexionGUIA, $producto['id_area'], $nombreActividad);
			
			$codigoPaisVUE = $this->f001pd[$i]['org_ntn_cd'];
			$qCodigoPais = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisVUE);
			$codigoPais = pg_fetch_assoc($qCodigoPais);


			$operacionRegistrada = $controladorRegistroOperador->buscarOperacionProductoPais($conexionGUIA,$this->f001['impr_idt_no'],pg_fetch_result($idActividad, 0, 'id_tipo_operacion'),$producto['id_producto'], $codigoPais['id_localizacion']);
			
			$productosVUE[] = $this->f001['impr_idt_no'].'-'.pg_fetch_result($idActividad, 0, 'id_tipo_operacion').'-'.$producto['id_producto'].'-'.$this->f001['req_no'].'-'.$codigoPais['id_localizacion'];
				
			if(pg_num_rows($operacionRegistrada)== 0){
			
				//TODO: Asociar al id operador tipo operacion IMPORTANTE------------------------------------------------------------------------------!!!!!!
				
			    $qDatosOperacionAsociada = $controladorRegistroOperador->obtenerIdOperadorTipoOperacionHistorialXIdentificadorTipoOperacionSitio($conexionGUIA, $this->f001['impr_idt_no'],  pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), pg_fetch_result($areaIngresada, 0, 'id_sitio'), " not in ('eliminado')");
			    
			    if(pg_num_rows($qDatosOperacionAsociada) == 0){
			        $idOperadorTipoOperacion = pg_fetch_result($controladorRegistroOperador->guardarTipoOperacionPorIndentificadorSitio($conexionGUIA, $this->f001['impr_idt_no'], pg_fetch_result($areaIngresada, 0, 'id_sitio'), pg_fetch_result($idActividad, 0, 'id_tipo_operacion')), 0, 'id_operador_tipo_operacion');
			        $historicoOperacion = pg_fetch_result($controladorRegistroOperador->guardarDatosHistoricoOperacion($conexionGUIA,$idOperadorTipoOperacion), 0, 'id_historial_operacion');
			        $nuevaOperacion = true;
			    }else{
			        $datosOperacionAsociada = pg_fetch_assoc($qDatosOperacionAsociada);
			        $idOperadorTipoOperacion = $datosOperacionAsociada['id_operador_tipo_operacion'];
			        $historicoOperacion = $datosOperacionAsociada['id_historial_operacion'];
			        $nuevaOperacion = false;
			    }
				
				//Guardar la operacion del operador
			    $operacion = $controladorRegistroOperador->guardarNuevaOperacion($conexionGUIA, pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $this->f001['impr_idt_no'], $producto['id_producto'], $producto['nombre_comun'], $idOperadorTipoOperacion, $historicoOperacion, 'moduloExterno', $codigoPais['id_localizacion'], $codigoPais['nombre'], $this->f001['req_no'],  $this->f001pd[$i]['hc'], $this->f001pd[$i]['prdt_cd']);

				//Guardar el area en la que ejerce la operacion.
				$idAreas = $controladorRegistroOperador->guardarAreaOperacion($conexionGUIA, pg_fetch_result($areaIngresada, 0, 'id_area'), pg_fetch_result($operacion, 0, 'id_operacion'));
				
				if($nuevaOperacion){
				    $controladorRegistroOperador->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexionGUIA, $idOperadorTipoOperacion, pg_fetch_result($operacion, 0, 'id_operacion'));
				    $controladorRegistroOperador->guardarAreaPorIdentificadorTipoOperacion($conexionGUIA, pg_fetch_result($areaIngresada, 0, 'id_area'), $idOperadorTipoOperacion);
				    $controladorRegistroOperador-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexionGUIA, $idOperadorTipoOperacion, 'registrado');
				}
				
				//Creacion de proveedores
				$controladorRegistroOperador->guardarNuevoProveedorComercioExterior($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd'], $this->f001['impr_idt_no'], pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $nombreActividad, $producto['id_producto'], $producto['nombre_comun'], $codigoPais['id_localizacion'], $codigoPais['nombre'], $this->f001['req_no']);

				//Cambio de estado en el area y operacion en el caso de ser producto de sanidad animal y sanidad vegetal.

				//if($producto['id_area'] == 'SA' || $producto['id_area'] == 'SV'){
					$res = $controladorRegistroOperador -> enviarOperacion($conexionGUIA, pg_fetch_result($operacion, 0, 'id_operacion'),'registrado');
					//Cambia el estado del area operacion producto a aprobado.
					$res = $controladorRegistroOperador -> cambiarEstadoSolicitudArea($conexionGUIA, pg_fetch_result($idAreas, 0, 'id_producto_area_operacion'), 'registrado');
				//}

				echo OUT_MSG. 'Actualización de un nuevo producto del Operador de comercio exterior.';

			}else{
			     echo OUT_MSG. 'No se ha añadido nuevos productos a la solicitud de modificación';
				 $controladorRegistroOperador->guardarNuevoProveedorComercioExterior($conexionGUIA, $this->f001pd[$i]['agrcd_prdt_cd'], $this->f001['impr_idt_no'], pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $nombreActividad, $producto['id_producto'], $producto['nombre_comun'], $codigoPais['id_localizacion'], $codigoPais['nombre'], $this->f001['req_no']);
			}
			
			$controladorRegistroOperador->actualizarPartidaYCodigoProductoVUE($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $this->f001['req_no'], $producto['id_producto']);
			
			//ASIGNACION DE MODULO DE INSPECCION DE PRODUCTOS
			if($producto['proceso_banano'] == 't' && $producto['id_area'] == 'SV' && $nombreActividad == 'Exportador'){
				parent::asignarAplicacionGUIA( $this->f001['impr_idt_no'], 'PRG_INSP_MUS');
				parent::asignarPerfilGUIA($this->f001['impr_idt_no'], 'Inspección Musáceas Usuario Externo');
			}
			
		}
		
		$qProductoGUIA = $controladorRegistroOperador->buscarOperadorProductos($conexionGUIA, $this->f001['impr_idt_no'], $this->f001['req_no']);
		while ($fila = pg_fetch_assoc($qProductoGUIA)){
			$productoGUIA[] = $fila['identificador_operador'].'-'.$fila['id_tipo_operacion'].'-'.$fila['id_producto'].'-'.$fila['id_vue'].'-'.$fila['id_pais'];
		}

		$productosEliminados = array_diff($productoGUIA,$productosVUE); 
								
		if(count($productosEliminados) != 0){
			foreach ($productosEliminados as $pEliminados){
				$elemento = explode('-', $pEliminados);
				
				$controladorRegistroOperador->cambiarEstadoOperacionProducto($conexionGUIA, $elemento[0] /*identificador*/, $elemento[1]/*actividad*/, $elemento[2]/*producto*/, $elemento[4]/*pais*/, $elemento[3]/*vue*/, 'noHabilitado');
				echo OUT_MSG.'El producto ha sido inactivado.';
			}
		}

		
		
		return true;
	}

	//public function escribirDatosAFrontera(){return;}
	public function recaudacionTasa($recaudacionTasas){
		return true;
	}

	public function cancelar(){

		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();

		//$operacion = $controladorRegistroOperador->buscarOperacionVue($conexionGUIA,  $this->f001['impr_idt_no'], $this->f001['req_no']);
		
		//if(pg_num_rows($operacion)!=0){
		
			$controladorRegistroOperador->anularSolicitudVUE($conexionGUIA,$this->f001['req_no'] , 'noHabilitado');
		//}

		echo OUT_MSG. 'Solicitud cancelada.';

		return;
	}

	public function anular(){

		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();

		//$operacion = $controladorRegistroOperador->buscarOperacionVue($conexionGUIA,  $this->f001['impr_idt_no'], $this->f001['req_no']);
		//if(pg_num_rows($operacion)!=0){
			//$controladorRegistroOperador->enviarOperacion($conexionGUIA, pg_fetch_result($operacion, 0, 'id_operacion') , 'anulado');
			$controladorRegistroOperador->anularSolicitudVUE($conexionGUIA,$this->f001['req_no'], 'noHabilitado');
		//}

		echo OUT_MSG. 'Solicitud anulada.';

		return;
	}

	public function insertarDocumentosAdjuntosGUIA($codigoVerificacion){
		return true;
	}
	
	public function reversoSolicitud(){
		return true;
	}
	
	public function generarFacturaProcesoAutomatico(){
		return true;
	}


}

/***************************************************************************************************************************/
/***************************************************************************************************************************/

class Importaciones extends FormularioVUE{

	public $f002;
	public $f002pd = array();

	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){

		$camposObligatorios = array(
		 'cabecera' => array(
		 		'IMPR_NM',
		 		'IMPR_RPST_NM',
		 		'IMP_PRVHC_NM',
		 		'IMPR_CUTY_NM',
		 		'IMPR_PRQI_NM',
		 		'IMPR_AD',
		 		'IMPR_TEL_NO',
		 		'IMPR_CEL_NO',
		 		'IMPR_EM'),
			
			'productos' => array(
				'REQ_NO',
				'HC',
				'PRDT_NM',
				'PRDT_CD',
				'PRDT_QT',
				'PRDT_MES',
				'PRDT_NWT',
				'PRDT_NWT_UT',
				'FOBV_VAL',
				'CIF_VAL')
		); 

		//Trayendo los datos de cabecera del formulario 101-002

		$this-> f002 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta("SELECT *
				FROM vue_gateway.tn_agr_002
				WHERE REQ_NO = '$numeroDeSolicitud'"));
			
		//Trayendo los datos de detalle del formulario 101-002

		$c_f002pd = $coneccionVUE ->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_002_pd
				WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($fila = pg_fetch_assoc($c_f002pd)){
			$this-> f002pd[] = $fila;
		}

		parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
	}

	public function validarDatosFormulario(){
		echo  PRO_MSG. 'validando formulario 101-002';
		
		$certificadoImpValido = true;
		$solicitudEsValida = true;
		$resultado = array();
		$subTipoProducto = array();
		$condicion = true;
		
		//parent::validarCamposObligatorios($f002,'cabecera');
		//parent::validarCamposObligatorios($f002_pd,'productos');
		
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$identificadorImportador = $this->f002['impr_idt_no'];						
			
		$tipoProductoVUE = strtoupper(($this->f002['prdt_type_nm']));
		$tipoProductoGUIA = ($tipoProductoVUE== 'ANIMAL'?'SA':($tipoProductoVUE == 'VEGETAL'?'SV':($tipoProductoVUE=='PLAGUICIDA' ?'IAP':($tipoProductoVUE=='VETERINARIO' ? 'IAV' : ($tipoProductoVUE=='FERTILIZANTE' ? 'IAF' : 'No definido')))));

		for($j = 0 ; $j < count($this->f002['req_no']); $j++){
						
			$tipoFormularioVUE = $this->f002['req_type_cd'];
				
			$area = ($tipoFormularioVUE == '0002'?'SA':($tipoFormularioVUE == '0001'?'SV':($tipoFormularioVUE=='0003' ?'IAP':( $tipoFormularioVUE=='0004'? 'IAV' :( $tipoFormularioVUE=='0005'? 'IAF' :'No definido')))));
			$idActividad  = pg_fetch_result($controladorCatalogos -> buscarIdOperacion($conexionGUIA, $area, 'Importador'),0,'id_tipo_operacion'); //Buscar actividad del importador segun el tipo de solicitud.
							
			//Validación país de origen
			$codigoPaisOrigenVUE = $this->f002['org_ntn_cd'];
			$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisOrigenVUE);
			$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
			
			if( pg_num_rows($qCodigoPaisOrigen) == 0 ){ //Validación del pais de origen
					$solicitudEsValida = false;
					$certificadoImpValido = false;
					echo IN_MSG. 'El pais de origen '.$this->f002['org_ntn_nm'].' no se encuentra registrado en agrocalidad';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El pais de origen '.$this->f002['org_ntn_nm'].' no se encuentra registrado en agrocalidad';
					break;			
			}			
			
			//Validación ciudad solicitud
            $codigoCiudadVUE = $this->f002['req_city_cd'];
            $qCodigoCiudad = $controladorCatalogos->obtenerCodigoLocalizacionImportacion($conexionGUIA, $codigoCiudadVUE);
            $codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
            
            if( pg_num_rows($qCodigoCiudad) == 0 ){ //Validación del pais de origen
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'La ciudad '.$this->f002['req_city_nm'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'La ciudad '.$this->f002['req_city_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
			
			$qMonedaGUIA = $controladorCatalogos -> obtenerCodigoMoneda($conexionGUIA, $this->f002['cif_val_curr']);
			
			if( pg_num_rows($qMonedaGUIA) == 0 ){ //Validación de la moneda.
				$solicitudEsValida = false;
				$certificadoImpValido = false;
				echo IN_MSG. 'La moneda no se encuentra registrado en agrocalidad';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'La moneda no se encuentra registrado en agrocalidad';
				break;
			}
			
			
			//Validación codigo país de embarque
			$codigoPaisEmbarqueVUE = $this->f002['spm_ntn_cd'];
			$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisEmbarqueVUE); //Codigo del pais de embarque
			$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
				
			if( pg_num_rows($qCodigoPaisEmbarque) == 0 ){ //Validación del pais de embarque
				$solicitudEsValida = false;
				$certificadoImpValido = false;
				echo IN_MSG. 'El pais de embarque '.$this->f002['spm_ntn_nm'].' no se encuentra registrado en agrocalidad';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El pais de embarque '.$this->f002['spm_ntn_nm'].' no se encuentra registrado en agrocalidad';
				break;
			}
			
			//Validacion codigo régimen aduanero
			$regimenAduaneroVUE = strtoupper($this->f002['cutom_rgm_cd']);
			$qRegimenAduanero = $controladorCatalogos->obtenerCodigoAduanero($conexionGUIA, $regimenAduaneroVUE);
				
			if( pg_num_rows($qRegimenAduanero) == 0 ){ //Validación de regimen aduanero
				$solicitudEsValida = false;
				$certificadoImpValido = false;
				echo IN_MSG. 'El regimen aduanero seleccionado no se encuentra registrado en agrocalidad';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El regimen aduanero seleccionado no se encuentra registrado en agrocalidad';
				break;
			}
			
			//Puerto de embarque
			$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f002['spm_port_cd']); //Obtiene codigo de puerto en GUIA embarque
			$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
			
			if( pg_num_rows($qCodigoPuertoEmbarque) == 0 ){ //Validación del puerto de embarque
				$solicitudEsValida = false;
				$certificadoImpValido = false;
				echo IN_MSG. 'El puerto de embarque '.$this->f002['spm_port_nm'].' no se encuentra registrado en agrocalidad';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El puerto de embarque '.$this->f002['spm_port_nm'].' no se encuentra registrado en agrocalidad';
				break;
			}
			
			//Puerto de destino
			$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f002['ptet_cd']); //Obtiene codigo de puerto en GUIA destino
			$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
			
			if( pg_num_rows($qCodigoPuertoDestino) == 0 ){ //Validación del puerto de embarque
				$solicitudEsValida = false;
				$certificadoImpValido = false;
				echo IN_MSG. 'El puerto de destino '.$this->f002['ptet_nm'].' no se encuentra registrado en agrocalidad';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El puerto de destino '.$this->f002['ptet_nm'].' no se encuentra registrado en agrocalidad';
				break;
			}
				
			if($tipoProductoGUIA != $area){ // Validación tipo de producto sea el mismo del tipo de solicitud.
				$solicitudEsValida = false;
				$certificadoImpValido = false;
				echo IN_MSG. 'El tipo de producto '.$this->f002['prdt_type_nm'].' no corresponde al tipo de solicitud seleccionada. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El tipo de producto '.$this->f002['prdt_type_nm'].' no corresponde al tipo de solicitud seleccionada. ';
				break;
			}

			$productoDuplicado = parent::verificarProductoRepetido($this->f002pd);
			
			if($productoDuplicado){
				$solicitudEsValida = false;
				$certificadoImpValido = false;
				echo IN_MSG.  'No se permite agregar el mismo producto dos veces.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'No se permite agregar el mismo producto dos veces.';
				break;
			}
			
			$numeroCuarentena = $this->f002['grnd_quar'];
			
			if($numeroCuarentena != null || $numeroCuarentena != ''){
			
				$qNumeroRegistroCuarentena = $controladorRegistroOperador->obtenerAreaRegistroCuarentena($conexionGUIA, $numeroCuarentena, $identificadorImportador, $tipoProductoGUIA);
			
				if( pg_num_rows($qNumeroRegistroCuarentena) == 0 ){ //Validación del puerto de embarque
					$solicitudEsValida = false;
					$certificadoImpValido = false;
					echo IN_MSG. 'El Sitio/Predio de cuarentena '.$this->f002['grnd_quar'].' no es correcto o no corresponde a un Sitio/Predio en estado habilitado';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El Sitio/Predio de cuarentena '.$this->f002['grnd_quar'].' no es correcto o no corresponde a un Sitio/Predio en estado habilitado';
					break;
				}
			
			}
			
		}
		
		if($certificadoImpValido){
			for ($i = 0; $i < count ($this->f002pd); $i++) {
				
				$partidaArancelariaVUE = $this->f002pd[$i]['hc'];
				$codigoProductoVUE = $this->f002pd[$i]['prdt_cd'];
				
				if(($area == 'IAP' || $area == 'IAV' || $area == 'IAF') && strlen($codigoProductoVUE) != '9'){ // Validación Area igual IA el producto debe tener un codigo de 8 digitos
					$solicitudEsValida = false;
					$certificadoImpValido = false;
					echo IN_MSG. 'El producto '.$this->f002pd[$i]['prdt_nm'].' no contiene una presentación, por favor seleccione el producto con presentación.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El producto '.$this->f002pd[$i]['prdt_nm'].' no contiene una presentación, por favor seleccione el producto con presentación.';
					break;
				}
				
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
				
				
				if( pg_num_rows($qProductoGUIA) == 0 ){ // Busqueda del producto en base de datos GUIA.
						
					$solicitudEsValida = false;
					echo IN_MSG. 'El producto '.$this->f002pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El producto '.$this->f002pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					break;
				}
				
				$producto = pg_fetch_assoc($qProductoGUIA);
				
				$subTipoProducto[] = $producto['id_subtipo_producto']; 
	
				
				/*$unidadMedidaProductoVUE = $this->f002pd[$i]['prdt_mes'];
				
				if($unidadMedidaProductoVUE != $producto['unidad_medida']){
					$solicitudEsValida = false;
					echo IN_MSG. 'La unidad fisica '.$unidadMedidaProductoVUE.' no corresponde a la del producto '.$this->f002pd[$i]['prdt_nm'];
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La unidad fisica '.$unidadMedidaProductoVUE.' no corresponde a la del producto '.$this->f002pd[$i]['prdt_nm'];
					break;
				}*/
				
				$unidadPesoProductoVUE = $this->f002pd[$i]['prdt_nwt_ut'];
				
				if($unidadPesoProductoVUE != 'KG'){
					$solicitudEsValida = false;
					echo IN_MSG. 'La unidad de peso ingresada para el producto debe estar en KG.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La unidad de peso ingresada para el producto debe estar en KG.';
					break;
				}
				
			
				/*$licenciaMagap = $this->f002['prvw_lcs_ctft_no'];
				
				if($producto['licencia_magap'] == 't'){
					if($licenciaMagap == ''){
						$solicitudEsValida = false;
						echo IN_MSG. 'La licencia de magap es un campo obligatorio';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'La licencia de magap es un campo obligatorio';
						break;
					}
				}*/
												
				$qOperacionOperador= $controladorRegistroOperador -> buscarOperadorProductoPaisActividad($conexionGUIA, $identificadorImportador, $codigoPaisOrigen['id_localizacion'], $producto['id_producto'],$idActividad,'registrado');
					
				if( pg_num_rows($qOperacionOperador) == 0 ){
				
					$solicitudEsValida = false;
					echo IN_MSG. 'El importador '.$identificadorImportador.' no tiene registrado el producto '.$this->f002pd[$i]['prdt_nm'].' para el país '.$this->f002['org_ntn_nm'].'';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El importador '.$identificadorImportador.' no tiene registrado el producto '.$this->f002pd[$i]['prdt_nm'].' para el país '.$this->f002['org_ntn_nm'].'';
					break;
				}
				
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				//$condicion = ($area == 'SA'?true:($area == 'SV'?true:($area=='IAP' ?false:( $area=='IAV'? false :'No definido'))));
				
				
				if($area == 'SA' || $area == 'SV'){
					if(count(array_unique($subTipoProducto))>1){
						$solicitudEsValida = false;
						echo IN_MSG. 'No se permiten productos que no corresponden a un mismo subtipo.';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'No se permiten productos que no corresponden a un mismo subtipo.';
						break;
							
					}
				
					//Validar si el producto esta activo para un pais y actividad
					$qEstadoProductoPais = $controladorRequisitos ->consultarEstadoProductoPaisRequisito($conexionGUIA, $codigoPaisOrigen['id_localizacion'], $producto['id_producto'], 'Importación', 'activo');  //estado producto pais requisito
					
					if( pg_num_rows($qEstadoProductoPais) == 0 ){
						$solicitudEsValida = false;
						echo IN_MSG. 'El producto '.$this->f002pd[$i]['prdt_nm'].' se encuentra inactivo para la operación de importación al pais '.$this->f002['org_ntn_nm'];
						$resultado[0] = SOLICITUD_NO_APROBADA;
						$resultado[1] = 'El producto '.$this->f002pd[$i]['prdt_nm'].' se encuentra inactivo para la operación de importación al pais '.$this->f002['org_ntn_nm'];
						break;
					}
				
				}
				 
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				$ImportadorProveedores = $controladorRegistroOperador-> buscarProveedoresOperadorProducto($conexionGUIA, $identificadorImportador, $idActividad, $producto['id_producto'], "('registrado','registradoObservacion','porCaducar')");
				
				if( pg_num_rows($ImportadorProveedores) == 0 ){
				
					$solicitudEsValida = false;
					echo IN_MSG. 'El importador  '.$identificadorImportador.', no posee Areas de operación en estado registrado para el producto '.$this->f002pd[$i]['prdt_nm'].', en el sistema GUIA (Proveedores)';
					$resultado[0] = SOLICITUD_NO_APROBADA;
					$resultado[1] = 'El importador  '.$identificadorImportador.', no posee Areas de operación en estado registrado para el producto '.$this->f002pd[$i]['prdt_nm'].', en el sistema GUIA (Proveedores)';
				}					
			}	
		}
		
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
	}

	public function validarActualizacionDeDatos(){
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorImportaciones = new ControladorImportaciones();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorVUE = new ControladorVUE();
		$conexionGUIA = new Conexion();
		
		$resultado = array();
		$modificacion = true;
		
		$idVue = $this->f002['req_no'];
		$identificadorImportador = $this->f002['impr_idt_no'];
		
		$nombreExportador = strtoupper($this->f002['expr_nm']);
		$direccionExportador = strtoupper($this->f002['expr_ad']);
		
		$tipoProductoVUE = strtoupper(($this->f002['prdt_type_nm']));
		
		$tipoProductoGUIA = ($tipoProductoVUE== 'ANIMAL'?'SA':($tipoProductoVUE == 'VEGETAL'?'SV':($tipoProductoVUE=='PLAGUICIDA' ?'IAP':($tipoProductoVUE=='VETERINARIO' ? 'IAV' : ($tipoProductoVUE=='FERTILIZANTE' ? 'IAF' : 'No definido')))));
		
		$fechaActual=date('Y-m-d');
		
		//if($tipoProductoGUIA == 'IAP' || $tipoProductoGUIA == 'IAV'){
			//echo IN_MSG. 'No se permite modificar los datos del certificado de importación.';
			//$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
			//$resultado[1] = 'No se permite modificar los datos del certificado de importación.';
		//}else{
			
			for ($i = 0; $i < count ($this->f002['req_no']); $i++) {
				
							
				$qImportacion = $controladorImportaciones->buscarImportacionImportadorVUE($conexionGUIA, $identificadorImportador, $idVue);
				$datosImportador = pg_fetch_assoc($qImportacion);
									
				if( pg_num_rows($qImportacion) == 0){
					$modificacion = false;
					echo IN_MSG. 'No se permite modificar datos del importador.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar datos del importador.';
					break;
				}
				
				$qImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $idVue);
				$datosImportador = pg_fetch_assoc($qImportacion);
				
				$fechaActual=date('j-n-Y');
				$fechaCertificado = date('j-n-Y',strtotime($datosImportador['fecha_vigencia']));
				
				if(!(strtotime($fechaCertificado) >= strtotime($fechaActual))){
					$modificacion = false;
					echo IN_MSG. 'El certificado de importación no se encuentra vigente.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'El certificado de importación no se encuentra vigente.';
					break;
				}
				
				$codigoPaisOrigenVUE = $this->f002['org_ntn_cd'];
				$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisOrigenVUE);
			
				if(pg_fetch_result($qCodigoPaisOrigen, 0 , 'id_localizacion') != $datosImportador['id_pais_exportacion'] ){ //Validación del pais de origen
					$modificacion = false;
					echo IN_MSG. 'No se permite modificar el país de origen';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el país de origen';
					break;
				}
						
				$qMonedaGUIA = $controladorCatalogos -> obtenerCodigoMoneda($conexionGUIA, $this->f002['cif_val_curr']);
					
				if(pg_fetch_result($qMonedaGUIA, 0 , 'id_moneda') != $datosImportador['moneda']){ //Validación de la moneda.
					$modificacion = false;
					echo IN_MSG.  'No se permite modificar la moneda.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la moneda.';
					break;
				}
			

				$regimenAduaneroVUE = strtoupper($this->f002['cutom_rgm_cd']);
				$qRegimenAduanero = $controladorCatalogos->obtenerCodigoAduanero($conexionGUIA, $regimenAduaneroVUE);
				
				if( pg_fetch_result($qRegimenAduanero, 0, 'id_regimen') != $datosImportador['regimen_aduanero']){ //Validación de regimen aduanero
					$modificacion = false;
					echo IN_MSG.  'No se permite modificar el regimen aduanero.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el regimen aduanero.';
					break;
				}
				
				if($tipoProductoGUIA == 'IAP' || $tipoProductoGUIA == 'IAV' || $tipoProductoGUIA == 'IAF' || $tipoProductoGUIA == 'SA'){
					
					if($this->f002['expr_nm'] != $datosImportador['nombre_exportador']){
						$modificacion = false;
						echo IN_MSG.  'No se permite modificar el nombre del exportador.';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'No se permite modificar el nombre del exportador.';
						break;
					}
					
					if($this->f002['expr_ad'] != $datosImportador['direccion_exportador']){
						$modificacion = false;
						echo IN_MSG.  'No se permite modificar la dirección del exportador.';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'No se permite modificar la dirección del exportador.';
						break;
					}
					
					
				}
				
				if($tipoProductoGUIA == 'IAP' || $tipoProductoGUIA == 'IAV' || $tipoProductoGUIA == 'IAF'){
					
					$codigoCiudadVUE = $this->f002['req_city_cd'];					
					$qCodigoCiudad = $controladorCatalogos->obtenerCodigoLocalizacionImportacion($conexionGUIA,  $codigoCiudadVUE); //Codigo de ciudad
					$codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
					
					if($codigoCiudad['id_localizacion'] != $datosImportador['id_ciudad']){
						$modificacion = false;
						echo IN_MSG.  'No se permite modificar la ciudad de solicitud.';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'No se permite modificar la ciudad de solicitud.';
						break;
					}
					
					if($this->f002['frtl_type_nm'] != $datosImportador['nombre_solicitud_fertilizantes']){
						$modificacion = false;
						echo IN_MSG.  'No se permite modificar el nombre del tipo de solicitud de fertilizantes.';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'No se permite modificar el nombre del tipo de solicitud de fertilizantes.';
						break;
					}
					
				}
								
				$productoDuplicado = parent::verificarProductoRepetido($this->f002pd);
					
				if($productoDuplicado){
					$modificacion = false;
					echo IN_MSG.  'No se permite agregar el mismo producto dos veces.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite agregar el mismo producto dos veces.';
					break;
				}
				
				$cantidadProductoGUIA =  pg_num_rows($controladorImportaciones->buscarImportacionProducto($conexionGUIA, $idVue));
				
				if($cantidadProductoGUIA != count ($this->f002pd)){
				    $modificacion = false;
				    echo IN_MSG.  'No se permite eliminar o agregar productos.';
				    $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				    $resultado[1] = 'No se permite eliminar o agregar productos.';
				    break;
				}
				
				$numeroCuarentena = $this->f002['grnd_quar'];
				
				if($numeroCuarentena != null || $numeroCuarentena != ''){
					
					$qNumeroRegistroCuarentena = $controladorRegistroOperador->obtenerAreaRegistroCuarentena($conexionGUIA, $numeroCuarentena, $identificadorImportador, $tipoProductoGUIA);
					
					if( pg_num_rows($qNumeroRegistroCuarentena) == 0 ){ //Validación del puerto de embarque
						$solicitudEsValida = false;
						$certificadoImpValido = false;
						echo IN_MSG. 'El Sitio/Predio de cuarentena '.$this->f002['grnd_quar'].' no es correcto o no corresponde a un Sitio/Predio en estado habilitado';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'El Sitio/Predio de cuarentena '.$this->f002['grnd_quar'].' no es correcto o no corresponde a un Sitio/Predio en estado habilitado';
						break;
					}
					
				}
				
			}
			
			for ($i = 0; $i < count ($this->f002pd); $i++) {
				
				$partidaArancelariaVUE = $this->f002pd[$i]['hc'];
				$codigoProductoVUE = $this->f002pd[$i]['prdt_cd'];
				
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $tipoProductoGUIA);
				
				
				if( pg_num_rows($qProductoGUIA) == 0 ){ // Busqueda del producto en base de datos GUIA.
					$modificacion = false;
					echo IN_MSG. 'El producto '.$this->f002pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'El producto '.$this->f002pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					break;
				}
								
				$qProductoImportacion = $controladorImportaciones->buscarImportacionProductoVUE($conexionGUIA, $identificadorImportador, $idVue, pg_fetch_result($qProductoGUIA, 0, 'id_producto'), $partidaArancelariaVUE, $codigoProductoVUE);
				
				
				if( pg_num_rows($qProductoImportacion) == 0 ){ // Busqueda del producto en base de datos GUIA.
					$modificacion = false;
					echo IN_MSG. 'El producto '.$this->f002pd[$i]['prdt_nm'].' no se encuentra registrado en la solicitud de importación.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'El producto '.$this->f002pd[$i]['prdt_nm'].' no se encuentra registrado en la solicitud de importación.';
					break;
				}
				
				$productoImportacion = pg_fetch_assoc($qProductoImportacion);
				
				if($partidaArancelariaVUE != $productoImportacion['partida_producto_vue']){
					$modificacion = false;
					echo IN_MSG. 'No se permite modificar la subpartida arancelaria del producto.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la subpartida arancelaria del producto.';
					break;
				}
				
				if($this->f002pd[$i]['prdt_qt'] != $productoImportacion['unidad']){
					$modificacion = false;
					echo IN_MSG. 'No se permite modificar la cantidad de producto.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la cantidad de producto.';
					break;
				}
				
				if($this->f002pd[$i]['prdt_nwt'] != $productoImportacion['peso']){
					$modificacion = false;
					echo IN_MSG. 'No se permite modificar el peso del producto.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el peso del producto.';
					break;
				}
				
				if($tipoProductoGUIA == 'IAP' || $tipoProductoGUIA == 'IAV' || $tipoProductoGUIA == 'IAF'){
					if($this->f002pd[$i]['fobv_val'] != $productoImportacion['valor_fob']){
						$modificacion = false;
						echo IN_MSG. 'No se permite modificar el valor fob del producto.';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'No se permite modificar el valor fob del producto.';
						break;
					}
					
					if($this->f002pd[$i]['cif_val'] != $productoImportacion['valor_cif']){
						$modificacion = false;
						echo IN_MSG. 'No se permite modificar el valor cif del producto.';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'No se permite modificar el valor cif del producto.';
						break;
					}
					
					if($tipoProductoGUIA == 'IAF'){
				    	
				    	if($this->f002pd[$i]['cmpst'] != $productoImportacion['composicion']){
				    		$modificacion = false;
				    		echo IN_MSG. 'No se permite modificar la composición del producto.';
				    		$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				    		$resultado[1] = 'No se permite modificar la composición del producto.';
				    		break;
				    	}
				    	
				    	if($this->f002pd[$i]['prdtb_form'] != $productoImportacion['producto_formular']){
				    		$modificacion = false;
				    		echo IN_MSG. 'No se permite modificar el nombre del producto a formular.';
				    		$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				    		$resultado[1] = 'No se permite modificar el nombre del producto a formular.';
				    		break;
				    	}
				    	
				    	if($this->f002pd[$i]['prd_nm_ctry_orig'] != $productoImportacion['nombre_producto_pais_origen']){
				    		$modificacion = false;
				    		echo IN_MSG. 'No se permite modificar el nombre del país de origen del producto.';
				    		$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				    		$resultado[1] = 'No se permite modificar el nombre del país de origen del producto.';
				    		break;
				    	}
				    }
				}
				
				if($this->f002['prvw_lcs_ctft_no'] != $productoImportacion['licencia_magap']){
					$modificacion = false;
					echo IN_MSG. 'No se permite modificar el número de licencia de magap.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el número de licencia de magap.';
					break;
				}
				
				if($this->f002pd[$i]['prdt_mes'] != $productoImportacion['unidad_medida']){
					$modificacion = false;
					echo IN_MSG. 'No se permite modificar la unidad de medida del producto.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la unidad de medida del producto.';
					break;
				}			
				
			}
			
		//}
		
		if($modificacion){
			
			$documentos = $controladorVUE->obtenerDocumentoAdjuntosIndividual($this->f002['req_no'], '150', 'PEDIDO DE AMPLIACION');
			
			if(pg_num_rows($documentos) != 0 && $datosImportador['fecha_ampliacion']==''){
				echo IN_MSG. 'Existe un proceso de ampliación.';
				$resultado[0] = SOLICITUD_AMPLIACION;
				$resultado[1] = 'Existe un proceso de ampliación.';
			}else if (pg_num_rows($documentos) != 0 && $datosImportador['fecha_ampliacion']!=''){
				echo IN_MSG. 'Ya se ha registrado una ampliación, solo se permite realizar una ampliación.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'Ya se ha registrado una ampliación, solo se permite realizar una ampliación.';
			}
			
		}
		
		
		return $resultado;
	}

	public function insertarDatosEnGUIA(){
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorImportaciones = new ControladorImportaciones();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$identificadorImportador = $this->f002['impr_idt_no'];	
		$codigoPaisEmbarqueVUE = $this->f002['spm_ntn_cd'];
		$codigoPaisOrigenVUE = $this->f002['org_ntn_cd'];
		$regimenAduaneroVUE = $this->f002['cutom_rgm_cd'];
		$numeroCuarentena = $this->f002['grnd_quar'];
		
		parent::asignarUsuarioGUIA($identificadorImportador);
		
		parent::asignarPerfilGUIA($identificadorImportador, 'Operadores');
		parent::asignarPerfilGUIA($identificadorImportador, 'Usuario externo');
		parent::asignarPerfilGUIA($identificadorImportador, 'Operadores de Comercio Exterior');
		
		parent::asignarAplicacionGUIA( $identificadorImportador, 'PRG_IMPORTACION');
		parent::asignarAplicacionGUIA( $identificadorImportador, 'PRG_REGISTROOPER');
		
		parent::ingresarRegistroOperador($identificadorImportador, $this->f002['impr_nm']);
				
		$tipoSolicitudVUE = strtoupper(($this->f002['prdt_type_nm']));
		$tipoSolicitudGUIA = ($tipoSolicitudVUE== 'ANIMAL'?'SA':($tipoSolicitudVUE == 'VEGETAL'?'SV':($tipoSolicitudVUE=='PLAGUICIDA' ?'IAP':($tipoSolicitudVUE=='VETERINARIO' ? 'IAV' : ($tipoSolicitudVUE=='FERTILIZANTE' ? 'IAF' : 'No definido')))));
		
		$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisEmbarqueVUE); //Codigo del pais de embarque
		$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
		
		$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisOrigenVUE); //Validación del pais de origen
		$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
		
		$qRegimenAduanero = $controladorCatalogos->obtenerCodigoAduanero($conexionGUIA, $regimenAduaneroVUE);
		$codigoRegimenAduanero = pg_fetch_assoc($qRegimenAduanero);
		
		$qCodigoCiudad = $controladorCatalogos->obtenerCodigoLocalizacionImportacion($conexionGUIA,  $this->f002['req_city_cd']); //Codigo de ciudad
		$codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
		
		$qCodigoProvincia = $controladorCatalogos->obtenerLocalizacion($conexionGUIA,  $codigoCiudad['id_localizacion_padre']); //Codigo provincia
		$codigoProvincia = pg_fetch_assoc($qCodigoProvincia);
	
		
		$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f002['spm_port_cd']);//Puerto de embarque
		$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
		
		
		$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f002['ptet_cd']); //Puerto de destino
		$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
		
		
		$res = $controladorImportaciones->generarNumeroSolicitud($conexionGUIA, '%'.$identificadorImportador.'%');
		$solicitud = pg_fetch_assoc($res);
		$tmp= explode("-", $solicitud['numero']);
		$incremento = end($tmp)+1;
			
		$codigoSolicitud = 'IMP-'.$identificadorImportador.'-'.str_pad($incremento, 7, "0", STR_PAD_LEFT);
		echo OUT_MSG . 'Generación de codigo interno de importación';
		
		$qMonedaGUIA = $controladorCatalogos -> obtenerCodigoMoneda($conexionGUIA, $this->f002['cif_val_curr']);
		$monedaGUIA = pg_fetch_assoc($qMonedaGUIA);
		
		$idImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $this->f002['req_no']);
		
		$nombreExportador = str_replace("'", " ", $this->f002['expr_nm']);
		$nombreEmbarcador = str_replace("'", " ", $this->f002['shpr_nm']);
		
	$validacionRevision = array();
	
		if(pg_num_rows($idImportacion) == 0){
		
			$idImportacion = $controladorImportaciones->guardarNuevaImportacion($conexionGUIA, $identificadorImportador, $nombreExportador, $this->f002['expr_ad'], $codigoPaisOrigen['id_localizacion'], 
																				$codigoPaisOrigen['nombre'], $nombreEmbarcador, $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'],
																				$codigoPuertoEmbarque['id_puerto'], $codigoPuertoEmbarque['nombre_puerto'], $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'],
																				$codigoSolicitud, $this->f002['req_no'], 'enviado', $this->f002['req_type_nm'], $codigoRegimenAduanero['id_regimen'], 
																				$monedaGUIA['id_moneda'], $this->f002['trsp_via_nm'], $tipoSolicitudGUIA, $codigoCiudad['id_localizacion'], $codigoCiudad['nombre'], $codigoProvincia['id_localizacion'], 
																				$codigoProvincia['nombre'], $numeroCuarentena, $this->f002['frtl_type_cd'],$this->f002['frtl_type_nm']);
			
		}else{
			$controladorImportaciones->actualizarDatosImportacion($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), $nombreExportador, $this->f002['expr_ad'], $codigoPaisOrigen['id_localizacion'], 
																  $codigoPaisOrigen['nombre'], $nombreEmbarcador, $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'],
																  $codigoPuertoEmbarque['id_puerto'], $codigoPuertoEmbarque['nombre_puerto'], $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'], 
																  $this->f002['req_no'], 'enviado', $this->f002['req_type_nm'], $codigoRegimenAduanero['id_regimen'], $monedaGUIA['id_moneda'], $this->f002['trsp_via_nm'], 
																  $tipoSolicitudGUIA, $codigoCiudad['id_localizacion'], $codigoCiudad['nombre'], $codigoProvincia['id_localizacion'], $codigoProvincia['nombre'], 
																  $numeroCuarentena, $this->f002['frtl_type_cd'],$this->f002['frtl_type_nm']);
			
			$controladorImportaciones->eliminarProductosImportacion($conexionGUIA,pg_fetch_result($idImportacion, 0, 'id_importacion'));
		}
		
		echo OUT_MSG . 'Datos de cabecera de importación insertados';
		

		for ($i = 0; $i < count ($this->f002pd); $i++) {
			
			$partidaArancelariaVUE = $this->f002pd[$i]['hc'];
			$codigoProductoVUE = $this->f002pd[$i]['prdt_cd'];
			
			$tipoFormularioVUE = $this->f002['req_type_cd'];
			
			$area = ($tipoFormularioVUE == '0002'?'SA':($tipoFormularioVUE == '0001'?'SV':($tipoFormularioVUE=='0003' ?'IAP':( $tipoFormularioVUE=='0004'? 'IAV' :( $tipoFormularioVUE=='0005'? 'IAF' :'No definido')))));
				
			$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $tipoSolicitudGUIA);
			$producto = pg_fetch_assoc($qProductoGUIA);
			
			
			$codigoInocuidad = substr($codigoProductoVUE, 5,4);
				
			$qPresentacionProducto = $controladorCatalogos->buscarCodigoInocuidad($conexionGUIA, $producto['id_producto'], $codigoInocuidad);
			$presentacionProducto = pg_fetch_assoc($qPresentacionProducto);
				
			if(($area == 'IAP' || $area == 'IAV' || $area == 'IAF') && strlen($codigoProductoVUE) == '9'){ // Validación Area igual IA el producto debe tener un codigo de 8 digitos
				if($identificadorImportador == $producto['id_operador']){
					$validacionRevision[] = true;
				}else{
					$validacionRevision[] = false;
				}
			}else{
				$validacionRevision[] = false;
			}
			
			$controladorImportaciones -> guardarImportacionesProductos($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), $producto['id_producto'], $producto['nombre_comun'], $this->f002pd[$i]['prdt_qt'], $this->f002pd[$i]['prdt_nwt'], $this->f002pd[$i]['fobv_val'], 
																	   $this->f002pd[$i]['cif_val'], $this->f002['prvw_lcs_ctft_no'] , $this->f002pd[$i]['prdt_rgs_no'], '',$this->f002pd[$i]['prdt_mes'], $presentacionProducto['presentacion'].' '.$presentacionProducto['unidad_medida'] ,$partidaArancelariaVUE, $codigoProductoVUE, 
																	   $this->f002pd[$i]['prdt_nm'], $this->f002pd[$i]['cmpst'], $this->f002pd[$i]['prdtb_form'], $this->f002pd[$i]['prd_nm_ctry_orig']);
																															
		}
		
		$resultado = array_unique($validacionRevision);
		
		if(count($resultado) == 1){
			if($resultado[0]){
				$ingreso = true;
			}else{
				$ingreso = false;
			}
		}else if(count($resultado) == 2){
			$ingreso = false;
		}
		
		if($ingreso){
			//Salto que permite realizar el cambio de estado para solicitudes de importacion IAV - IAP con memorando AGR-AGROCALIDAD/CRIA-2021-0142-M
			$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
			
			$tipoInspector = 'Documental';
			$tipoSolicitud = 'Importación';
			
			$inspectorAsignado= $controladorRevisionSolicitudesVUE->guardarNuevoInspector($conexionGUIA, '', '', $tipoSolicitud, $tipoInspector, 0, 0);
			$idGrupo =  pg_fetch_result($inspectorAsignado, 0, 'id_grupo');
			
			$controladorRevisionSolicitudesVUE->guardarGrupo($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), $idGrupo, $tipoInspector);
			$ordenInspeccion = $controladorRevisionSolicitudesVUE->buscarSerialOrden($conexionGUIA, $idGrupo, $tipoInspector);
			$controladorRevisionSolicitudesVUE->guardarDatosInspeccionDocumental($conexionGUIA, $idGrupo, '', 'Aprobación automática etapa de revisión documental en base a Memorando Nro. AGR-AGROCALIDAD/CRIA-2021-0142-M', 'pago',pg_fetch_result($ordenInspeccion, 0, 'orden'));
			
			$controladorImportaciones->enviarImportacion($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), 'pago');
			$controladorImportaciones->enviarProductosImportacion($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), 'pago');
			
		}
		
		echo OUT_MSG . 'Datos de detalle de importación insertados';
		
		return true;
	}
	
	public function insertarDocumentosAdjuntosGUIA($documentos){
		
		$controladorImportaciones = new ControladorImportaciones();
		$conexionGUIA = new Conexion();

		$identificadorImportador = $this->f002['impr_idt_no'];
		
		switch ($this->f002['req_type_cd']){
			case '0001':
				$area = 'SV';
				break;
			case '0002':
				$area = 'SA';
				break;
			case '0003':
				$area = 'IAP';
				break;
			case '0004':
				$area = 'IAV';
				break;
			case '0005':
				$area = 'IAF';
				break;
			default:
				$area = 'desconocido';
				break;
		}
		
		$idImportacion = pg_fetch_result($controladorImportaciones->buscarImportacionVUE($conexionGUIA, $this->f002['req_no']),0,'id_importacion');
		
		$controladorImportaciones->eliminarArchivosAdjuntos($conexionGUIA, $idImportacion, $this->f002['req_no']);
		
		if(pg_num_rows($documentos)!= 0){
			while($documentosAdjuntos = pg_fetch_assoc($documentos)){
				$ruta= explode('DEV/', $documentosAdjuntos['ruta']);
				//$ruta= explode('PROD/', $documentosAdjuntos['ruta']);
				$controladorImportaciones ->guardarImportacionesArchivos($conexionGUIA, $idImportacion, $documentosAdjuntos['nombre'], $ruta[1], $area, $this->f002['req_no']);
			}
		}else{
			echo OUT_MSG . 'La solicitud de importación no posee documentos adjuntos.';
		}
		
		echo OUT_MSG . 'Documentos adjuntos de importación insertados.';

		return true;
	}

	public function actualizarDatosEnGUIA(){
		
		$controladorImportaciones = new ControladorImportaciones();
		$controladorVUE = new ControladorVUE();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$identificadorImportador = $this->f002['impr_idt_no'];
		
		$nombreExportador = $this->f002['expr_nm'];
		$direccionExportador = $this->f002['expr_ad'];
		
		$paisEmbarque = $this->f002['spm_ntn_cd'];
		$medioTransporte = $this->f002['trsp_via_nm']; 
		$puertoEmbarque = $this->f002['spm_port_cd'];
		$puertoDestino = $this->f002['ptet_cd'];
		$nombreEmbarcador = $this->f002['shpr_nm'];
		$codigoCiudadVUE = $this->f002['req_city_cd'];
		
		$tipoSolicitudVUE = strtoupper(($this->f002['prdt_type_nm']));		
		$tipoSolicitudGUIA = ($tipoSolicitudVUE== 'ANIMAL'?'SA':($tipoSolicitudVUE == 'VEGETAL'?'SV':($tipoSolicitudVUE=='PLAGUICIDA' ?'IAP':($tipoSolicitudVUE=='VETERINARIO' ? 'IAV' : ($tipoSolicitudVUE=='FERTILIZANTE' ? 'IAF' : 'No definido')))));
				
		$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $paisEmbarque); //Codigo del pais de embarque
		$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
				
		$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $puertoEmbarque); 	//Puerto de embarque
		$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
		
		
		$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $puertoDestino); //Puerto de destino
		$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
		
		$qCodigoCiudad = $controladorCatalogos->obtenerCodigoLocalizacionImportacion($conexionGUIA,  $codigoCiudadVUE); //Codigo de ciudad
		$codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
		
		$qCodigoProvincia = $controladorCatalogos->obtenerLocalizacion($conexionGUIA,  $codigoCiudad['id_localizacion_padre']); //Codigo provincia
		$codigoProvincia = pg_fetch_assoc($qCodigoProvincia);
		
		
		$idImportacion = pg_fetch_assoc($controladorImportaciones->buscarImportacionVUE($conexionGUIA, $this->f002['req_no']));
		
		$controladorImportaciones->actualizarDatosExportador($conexionGUIA, $idImportacion['id_importacion'], $nombreExportador, $direccionExportador, $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'],
															$codigoPuertoEmbarque['id_puerto'], $codigoPuertoEmbarque['nombre_puerto'], $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'], 
															$nombreEmbarcador, $medioTransporte, $codigoCiudad['id_localizacion'], $codigoCiudad['nombre'], $codigoProvincia['id_localizacion'], $codigoProvincia['nombre'], $this->f002['req_no'], $tipoSolicitudGUIA);
															
		if($tipoSolicitudGUIA == 'SA' || $tipoSolicitudGUIA == 'SV'){
    		for ($i = 0; $i < count ($this->f002pd); $i++) {
    		    $valorCif = $this->f002pd[$i]['cif_val'];
    		    $valorFob = $this->f002pd[$i]['fobv_val'];

    		    $partidaArancelariaVUE = $this->f002pd[$i]['hc'];
    		    $codigoProductoVUE = $this->f002pd[$i]['prdt_cd'];

    		    $qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $tipoSolicitudGUIA);
    		    $producto = pg_fetch_assoc($qProductoGUIA);

    		    $controladorImportaciones->actualizarValorFobCifProductoImportacion($conexionGUIA, $idImportacion['id_importacion'], $producto['id_producto'], $partidaArancelariaVUE, $codigoProductoVUE, $valorCif, $valorFob);
    		    
    		}
		}
		
		$documento = $controladorVUE->obtenerDocumentoAdjuntosIndividual($this->f002['req_no'], '150', 'PEDIDO DE AMPLIACION');
		
		if (pg_num_rows($documento)!=0 && $idImportacion['fecha_inicio']!=''){
			//$controladorImportaciones->enviarImportacion($conexionGUIA, $idImportacion, 'enviado');
			
			$controladorImportaciones->enviarImportacion($conexionGUIA, $idImportacion['id_importacion'], 'ampliado');
				
			//Asignar estado a productos de solicitud
			$controladorImportaciones->enviarProductosImportacion($conexionGUIA, $idImportacion['id_importacion'], 'ampliado');
				
			//Asignar fecha de vigencia de solicitud
				
			$fechaAmpliacion = date ('Y-m-j', strtotime("+30 days", strtotime( $idImportacion['fecha_vigencia'] )));
				
			$controladorImportaciones->enviarFechaVigenciaAmpliacion($conexionGUIA, $idImportacion['id_importacion'], $fechaAmpliacion);
						
		}
		
		return true;
	}

	public function recaudacionTasa($recaudacionTasas){
		
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorImportaciones = new ControladorImportaciones();
		$controladorFinanciero = new ControladorFinanciero();
		$controladorCatalogos = new ControladorCatalogos();
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		$resultado = array();
		
		$datosTasa = pg_fetch_assoc($recaudacionTasas);
		$identificadorImportador = $this->f002['impr_idt_no'];
		$verificarProceso = false;
		
		$importacion = pg_fetch_assoc($controladorImportaciones->buscarImportacionVUE($conexionGUIA, $this->f002['req_no']));
		
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $importacion['id_importacion'], 'Importación', 'Financiero');
		$financiero = pg_fetch_assoc($qFinanciero);
		
		if($datosTasa['banco'] == '' && $datosTasa['canal_recaudacion'] == '' && $datosTasa['tipo_pago'] == '01'){
			
			$saldoDisponible = pg_fetch_assoc($controladorFinanciero->obtenerMaxSaldo($conexionGUIA, $importacion['identificador_operador'], 'saldoVue'));
			
			if($saldoDisponible['saldo_disponible']>= $datosTasa['monto_recaudado']){
				$banco = 'saldoVue';
				$idBanco = '0';
				$tipoProceso = 'comprobanteFactura';
				$verificarProceso = true;
				$resultado[0] = SOLICITUD_APROBADA;
				$resultado[1] = 'Continua con proceso de aprobación. ';
			}else{
				$resultado[0] = ERROR_TAREA;
				$resultado[1] = 'Proceso en espera hasta la confirmación de saldo. ';
			}			
		}else if ($datosTasa['banco'] != '' && $datosTasa['canal_recaudacion'] != '' && $datosTasa['tipo_pago'] == '01'){			
			$codigoBanco = trim($datosTasa['banco'], '0');					
			$datosBanco = pg_fetch_assoc($controladorCatalogos->obtenerDatosBancarioPorCodigoVue($conexionGUIA, $codigoBanco));
			$banco = $datosBanco['nombre'];
			$idBanco = $datosBanco['id_banco'];
			$tipoProceso = 'factura';
			$verificarProceso = true;
			$resultado[0] = SOLICITUD_APROBADA;
			$resultado[1] = 'Continua con proceso de aprobación. ';
		}else{
			$resultado[0] = ERROR_DE_VALIDACION;
			$resultado[1] = 'No se reconoce el proceso de verificación de pago. ';
		}
		
		if($verificarProceso){
			
			if($financiero['monto'] == $datosTasa['monto_recaudado']){
			
				//if( $datosTasa['banco']!='' || $datosTasa['monto_recaudado']!='' || $datosTasa['fecha_contable']!=''){
				$controladorRevisionSolicitudesVUE->guardarInspeccionFinanciero($conexionGUIA, $financiero['id_financiero'], $financiero['identificador_inspector'], 'aprobado', $datosTasa['fecha_recaudacion'], $idBanco, $datosTasa['monto_recaudado'], $banco, $datosTasa['numero_orden_vue'] ,$datosTasa['numero_orden_vue']);
				$controladorFinanciero->actualizarNumeroOrdenSolicitudVue($conexionGUIA, $importacion['id_importacion'], $financiero['id_grupo'], 'Importación', $datosTasa['numero_orden_vue']);
				//}
				
				echo OUT_MSG . 'Actualización de datos de tasas de recaudación';
				
				//Asigna el resultado de revisión de pago de solicitud de importacion
				$controladorImportaciones->enviarImportacion($conexionGUIA, $importacion['id_importacion'], 'aprobado');
				//$controladorImportaciones->enviarImportacion($conexionGUIA, $importacion['id_importacion'], 'verificacion');
					
				//Asignar estado a productos de solicitud
				$controladorImportaciones->enviarProductosImportacion($conexionGUIA, $importacion['id_importacion'], 'aprobado');
				//$controladorImportaciones->enviarProductosImportacion($conexionGUIA, $importacion['id_importacion'], 'verificacion');
					
				//Asignar fecha de vigencia de solicitud
				$controladorImportaciones->enviarFechaVigenciaImportacion($conexionGUIA, $importacion['id_importacion'],$importacion['id_area']);
					
				$cfa->actualizarTipoProcesoFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f002['req_no'], $tipoProceso);
			
			}else{
				$resultado = array();
				$resultado[0] = ERROR_DE_VALIDACION;
				$resultado[1] = 'Error en diferenciación de valores cancelados. ';
			}
		}	
					
		return $resultado;
	}

	public function cancelar(){
		
		$controladorImportaciones = new ControladorImportaciones();
		$conexionGUIA = new Conexion();
		
		$identificadorImportador = $this->f002['impr_idt_no'];
		$idVue = $this->f002['req_no'];
		
		$idImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $this->f002['req_no']);
		
		if(pg_num_rows($idImportacion)!=0){

			$controladorImportaciones->enviarImportacion($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), 'cancelado');
			
			//for ($i = 0; $i < count ($this->f002pd); $i++) {
				$controladorImportaciones -> enviarProductosImportacion($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), 'cancelado');
			//}
		}
		
		
		
		echo OUT_MSG. 'Solicitud cancelada.';
		return true;
	}

	public function anular(){
		
		$controladorImportaciones = new ControladorImportaciones();
		$conexionGUIA = new Conexion();
		
		$identificadorImportador = $this->f002['impr_idt_no'];
		
		$idImportacion = $controladorImportaciones->buscarImportacionVUE($conexionGUIA, $this->f002['req_no']);
		
		$controladorImportaciones->enviarImportacion($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), 'anulado');
		
		for ($i = 0; $i < count ($this->f002pd); $i++) {
			$controladorImportaciones -> enviarProductosImportacion($conexionGUIA, pg_fetch_result($idImportacion, 0, 'id_importacion'), 'anulado');
		}
		
		echo OUT_MSG. 'Solicitud anulada.';
		
		
		return true;
	}
	
	public function reversoSolicitud(){
		
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorImportaciones = new ControladorImportaciones();
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		$identificadorImportador = $this->f002['impr_idt_no'];		
		$idImportacion = pg_fetch_assoc($controladorImportaciones->buscarImportacionVUE($conexionGUIA, $this->f002['req_no']));
		
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitudReverso($conexionGUIA, $idImportacion['id_importacion'], 'Importación', 'Financiero');
		$financiero = pg_fetch_assoc($qFinanciero);
		
		$controladorRevisionSolicitudesVUE->actualizarInspeccionFinancieroMontoRecaudado($conexionGUIA, $financiero['id_financiero']);
				
		$controladorImportaciones->enviarImportacion($conexionGUIA, $idImportacion['id_importacion'], 'reverso');
		
		$cfa->actualizarEstadoFinancieroAutomaticoCabeceraPorIdVue($conexionGUIA, $this->f002['req_no'], 'Reverso');
		
		return true;
	}
	
	public function generarFacturaProcesoAutomatico(){
		
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
				
		echo OUT_MSG . 'Actualización de datos de tasas de recaudación';
		
		$cfa->actualizarEstadoFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f002['req_no'], 'Por atender');
		$cfa->actualizarFechaFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f002['req_no']);
				
		echo OUT_MSG . 'Solicitud Fitosanitaria enviada a verificación de pago.';
				
		return true;
	}

}

/***************************************************************************************************************************/
/***************************************************************************************************************************/

class DDA extends FormularioVUE{

	public $f024;
	public $f024pd = array();

	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){

		/* $camposObligatorios = array(
		 'cabecera' => array(
		 		'IMPR_NM',
		 		'IMPR_RPST_NM',
		 		'IMP_PRVHC_NM',
		 		'IMPR_CUTY_NM',
		 		'IMPR_PRQI_NM',
		 		'IMPR_AD',
		 		'IMPR_TEL_NO',
		 		'IMPR_CEL_NO',
		 		'IMPR_EM'),
					
				'productos' => array(
						'REQ_NO',
						'HC',
						'PRDT_NM',
						'ORG_NTN_CD',
						'PRDT_CD',
						'AGRCD_PRDT_CD',
						'PRDT_BUSS_ACT_CD',
						'PRDT_BUSS_ACT_NM',
						'PRDT_TYPE_NM',
						'PRDT_STN')
		); */

		//Trayendo los datos de cabecera del formulario 101-024

		$this-> f024 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_024
				WHERE REQ_NO = '$numeroDeSolicitud'"));
			
		//Trayendo los datos de detalle del formulario 101-024

		$c_f024pd = $coneccionVUE ->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_024_pd
				WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($fila = pg_fetch_assoc($c_f024pd)){
			$this-> f024pd[] = $fila;
		}

		parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
	}

	public function validarDatosFormulario(){
		echo  PRO_MSG. 'validando formulario';
		$solicitudEsValida = true;
		$certificadoImpValido = true;
		$resultado = array();
		$validarCamposImportacion = true;

		//parent::validarCamposObligatorios($f002,'cabecera');
		//parent::validarCamposObligatorios($f002_pd,'productos');
	
		$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorImportaciones = new ControladorImportaciones();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$identificadorDDA = $this->f024['impr_idt_no'];
		
		switch ($this->f024['req_type_cd']){
			case '01':
				$area = 'SA';
				break;
			case '02':
				$area = 'SV';
				break;
			default:
				$area = 'desconocido';
		}
		
		for ($i = 0; $i < count ($this->f024['req_no']); $i++) {
							
			//Busca si el operador se encuentra registrado en el sistema GUIA
		
			$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $identificadorDDA);
				
			if( pg_num_rows($qOperador) == 0 ){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El operador '.$this->f024['impr_idt_no'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El operador '.$this->f024['impr_idt_no'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			
			//Verificar si el lugar de inspeccion se encuentra registrado en base GUIA.
			$qInspeccion = $controladorCatalogos->buscarCatalogoLugarInspeccion($conexionGUIA, $this->f024['isp_plc_cd']);
			 
			if( pg_num_rows($qInspeccion) == 0 ){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El lugar de inspección '.$this->f024['isp_pcl_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El lugar de inspección '.$this->f024['isp_pcl_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			$lugarInspeccion = pg_fetch_assoc($qInspeccion);
			
			$qImportacion = $controladorImportaciones->buscarVigenciaImportacion($conexionGUIA, $this->f024['imp_pht_prmt_no']); //IMP_PHT_PRMT_NO
				
			if( pg_num_rows($qImportacion) == 0 ){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El permiso de importación '.$this->f024['imp_pht_prmt_no'].' no se encuentra registrado en AGROCALIDAD. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El permiso de importación '.$this->f024['imp_pht_prmt_no'].' no se encuentra registrado en AGROCALIDAD. ';
				break;
			}
				
				
			$qImportacion = $controladorImportaciones->buscarOperadorImportacionXidVue($conexionGUIA, $identificadorDDA, $this->f024['imp_pht_prmt_no']); //IMP_PHT_PRMT_NO
			
			if( pg_num_rows($qImportacion) == 0 ){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El importador  '.$identificadorDDA.' no tiene registrado el permiso de importación con número '.$this->f024['imp_pht_prmt_no'];
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El importador  '.$identificadorDDA.' no tiene registrada el permiso de importación con número '.$this->f024['imp_pht_prmt_no'];
				break;
			}
				
			//Busca el código de importación en los certificados vigentes registrados para el operador
			//Se comento la validacion de fecha de vigencia por pedido de margarita el 18 de febrero de 2014*/
				
			/*$qImportacion = $controladorImportaciones->buscarVigenciaImportacion($conexionGUIA, $this->f024['imp_pht_prmt_no']); //IMP_PHT_PRMT_NO
				
			if( pg_num_rows($qImportacion) == 0 ){ 
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'La fecha de vigencia del certificado de importación '.$this->f024['imp_pht_prmt_no'].' ha expirado. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'La fecha de vigencia del certificado de importación '.$this->f024['imp_pht_prmt_no'].' ha expirado. ';
				break;
			}*/			
			
			$fechaIncioVigenciaImportacion = date('Y-n-j',strtotime($this->f024['pfi_de']));
			
			$qImportacion = $controladorImportaciones->verificarVigenciaImportacion($conexionGUIA, $this->f024['imp_pht_prmt_no'], $fechaIncioVigenciaImportacion);
			
			if( pg_num_rows($qImportacion) == 0 ){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'La fecha de permiso ingresada, no coincide con la registrada en el Permiso de importación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'La fecha de permiso ingresada, no coincide con la registrada en el Permiso de importación.';
				break;
			}
			
			
			//Verificar que el certificado de importación no alla sido utilizado.
			
			$qUtilizacionImportacion = $controladorImportaciones->buscarUtilizacionImportacion($conexionGUIA, $this->f024['imp_pht_prmt_no']);
			
			if( pg_num_rows($qUtilizacionImportacion) != 0 ){ 
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El permiso de importacion '.$this->f024['imp_pht_prmt_no'].' ya ha sido utilizado en un documento de destinación aduanera. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El permiso de importacion '.$this->f024['imp_pht_prmt_no'].' ya ha sido utilizado en un documento de destinación aduanera.  ';
				break;
			}			
				
			//VALIDACION INFORMACION DE SOLICITUD DE IMPORTACION
				
			$importacion = pg_fetch_assoc($qImportacion);
			
			//Identificador importador
			
			if(strtoupper($importacion['identificador_operador']) != $this->f024['impr_idt_no']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El Importador ingresado, no coincide con el registrado en el Permiso de importación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El Importador ingresado, no coincide con el registrado en el Permiso de importación.';
				break;
			}
				
			//Nombre exportador
			if(strtoupper($importacion['nombre_exportador']) != strtoupper($this->f024['expr_nm'])){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El Nombre del exportador ingresado no coincide con el registrado en el Permiso de importación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El Nombre del exportador ingresado no coincide con el registrado en el Permiso de importación.';
				break;
			}
				
			//Direccion exportador
			if(strtoupper($importacion['direccion_exportador']) != strtoupper($this->f024['expr_ad'])){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo OUT_MSG .'La Dirección del exportador ingresada, no coincide con la registrada en el Permiso de importación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'La Dirección del exportador ingresada, no coincide con la registrada en el Permiso de importación.';
				break;
			}
				
			//Pais exportador
			$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f024['org_ntn_cd']); //Validación del pais de origen
			$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
		
			if($importacion['id_pais_exportacion'] != $codigoPaisOrigen['id_localizacion']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo OUT_MSG .'El Pais de Origen ingresado no coincide con el registrado en el Permiso de importación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El Pais de Origen ingresado no coincide con el registrado en el Permiso de importación.';
				break;
			}
			
			//Tipo de transporte
			if($importacion['tipo_transporte'] != $this->f024['trsp_way_nm']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo OUT_MSG .'El Medio de Transporte ingresado no coincide con el registrado en el Permiso de importación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El Medio de Transporte ingresado no coincide con el registrado en el Permiso de importación.';
				break;
			}
				
			//Puerto de destino
			$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f024['dst_port_cd']); //Obtiene codigo en GUIA de puerto
			$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
				
			if($importacion['id_puerto_destino'] != $codigoPuertoDestino['id_puerto']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo OUT_MSG .'El Puerto de destino ingresado no coincide con el registrado en el Permiso de importación.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El Puerto de destino ingresado no coincide con el registrado en el Permiso de importación.';
				break;
			}

			//Validación Provincia del Lugar de Inspección sea la misma que la del Puerto de Destino.
			
			if($lugarInspeccion['id_provincia'] != $codigoPuertoDestino['id_provincia']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo OUT_MSG .'La provincia del lugar de inspección no coincide con la provincia del puerto de destino.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'La provincia del lugar de inspección no coincide con la provincia del puerto de destino.';
				break;
			}
			
			$productoDuplicado = parent::verificarProductoRepetido($this->f024pd);
				
			if($productoDuplicado){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG.  'No se permite agregar el mismo producto dos veces.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'No se permite agregar el mismo producto dos veces.';
				break;
			}
				
		}
		
		if($certificadoImpValido){
			for ($j = 0; $j < count ($this->f024pd); $j++) {
					
				$partidaArancelariaVUE = $this->f024pd[$j]['hc'];
				$codigoProductoVUE = $this->f024pd[$j]['prdt_cd'];
		
				//Busca el id del producto en la base de GUIA
		
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
		
				if( pg_num_rows($qProductoGUIA) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'El producto '.$this->f024pd[$j]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El producto '.$this->f024pd[$j]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					break;
				}
		
				//Compara los productos enviados por VUE en el registro de Importaciones de GUIA
		
				$producto = pg_fetch_assoc($qProductoGUIA);
				
								
				if($area!= $producto['id_area']){
					$solicitudEsValida = false;
					echo IN_MSG. 'El producto no corresponde al tipo de solicitud de destinacion aduanera seleccionada. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El producto no corresponde al tipo de solicitud de destinacion aduanera seleccionada. ';
					break;
				}
				
				$qImportacionProductos = $controladorImportaciones->buscarImportacionProductoVUE($conexionGUIA, $identificadorDDA, $this->f024['imp_pht_prmt_no'], $producto['id_producto']);
		
				if( pg_num_rows($qImportacionProductos) == 0 ){ // Busqueda del producto de importacion en base de datos GUIA.
					$solicitudEsValida = false;
					echo IN_MSG. 'El producto '.$this->f024pd[$j]['prdt_nm'].' con partida '.$partidaArancelariaVUE.' y código '.$codigoProductoVUE.' ingresado, no coincide con el registrado en el Permiso de importación.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El producto '.$this->f024pd[$j]['prdt_nm'].' con partida '.$partidaArancelariaVUE.' y código '.$codigoProductoVUE.' ingresado, no coincide con el registrado en el Permiso de importación.';
					break;
				}
				
				$importacionProducto = pg_fetch_assoc($qImportacionProductos);
				
				if($importacionProducto['partida_producto_vue'] != $partidaArancelariaVUE){
					$solicitudEsValida = false;
					echo IN_MSG. 'La partida arancelaria del producto '.$this->f024pd[$j]['prdt_nm'].' no coincide con la registrada en el permiso de importación. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La partida arancelaria del producto '.$this->f024pd[$j]['prdt_nm'].' no coincide con la registrada en el permiso de importación. ';
					break;
				}
				
				if($importacionProducto['codigo_producto_vue'] != $codigoProductoVUE){
					$solicitudEsValida = false;
					echo IN_MSG. 'El código del producto '.$this->f024pd[$j]['prdt_nm'].' no coincide con la registrada en el permiso de importación. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El código del producto '.$this->f024pd[$j]['prdt_nm'].' no coincide con la registrada en el permiso de importación. ';
					break;
				}	
				
				
				if($importacionProducto['unidad'] < $this->f024pd[$j]['pkgs_qt']){
					$solicitudEsValida = false;
					echo IN_MSG. 'La cantidad del producto '.$this->f024pd[$j]['prdt_nm'].' es mayor que la registrada en el permiso de importación. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La cantidad del producto '.$this->f024pd[$j]['prdt_nm'].' es mayor que la registrada en el permiso de importación. ';
					break;
				}
				
				
				if(strtoupper($importacionProducto['unidad_medida'])!= strtoupper($this->f024pd[$j]['pkgs_ut'])){
					$solicitudEsValida = false;
					echo IN_MSG. 'La unidad de medida del producto '.$this->f024pd[$j]['prdt_nm'].' no coincide con el registrado en el permiso de importación. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La unidad de medida del producto '.$this->f024pd[$j]['prdt_nm'].' no coincide con el registrado en el permiso de importación. ';
					break;
				}
				
				/*------------------------------------------------------------------------------------------------------------------------------------------------------*/
				//Función comentada por control de cambios solitado el 21 de noviembre de 2018
				
				/*$cantidadProducto = pg_fetch_assoc($controladorDestinacionAduanera->obtenerCantidadProductoXimportacion($conexionGUIA, $this->f024['imp_pht_prmt_no'], $producto['id_producto']));
				
				$cantidadActualProducto = $importacionProducto['unidad'] - $cantidadProducto['cantidad_producto'];
				
				if($this->f024pd[$j]['pkgs_qt'] > $cantidadActualProducto){
					$solicitudEsValida = false;
					echo IN_MSG. 'El permiso de importacion cuenta con '.$cantidadActualProducto.' '.$this->f024pd[$j]['pkgs_ut'].' de '.$this->f024pd[$j]['prdt_nm'].' disponible.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El permiso de importacion cuenta con '.$cantidadActualProducto.' '.$this->f024pd[$j]['pkgs_ut'].' de '.$this->f024pd[$j]['prdt_nm'].' disponible.';
					break;
				}*/
				/*------------------------------------------------------------------------------------------------------------------------------------------------------*/
			}
		}
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
		
	}

	public function validarActualizacionDeDatos(){
		
		echo  PRO_MSG. 'validando actualizacion de formulario ';
		$resultado = array();
		
		$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
		$resultado[1] = 'No se permite modificar los datos del documento de destinación aduanera.';
		
		return $resultado;
		
	}

	public function insertarDatosEnGUIA(){
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorImportaciones = new ControladorImportaciones();
		$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$identificadorDDA = $this->f024['impr_idt_no'];
		$numeroCertificadoImportacion = $this->f024['imp_pht_prmt_no'];
		
		parent::asignarUsuarioGUIA($identificadorDDA);
		
		parent::asignarPerfilGUIA($identificadorDDA, 'Usuario externo');
		parent::asignarPerfilGUIA($identificadorDDA, 'Operadores de Comercio Exterior');
		parent::asignarPerfilGUIA($identificadorDDA, 'Operadores');
		
		parent::asignarAplicacionGUIA( $identificadorDDA, 'PRG_DDA');
		parent::asignarAplicacionGUIA( $identificadorDDA, 'PRG_REGISTROOPER');
		
		parent::ingresarRegistroOperador($identificadorDDA, $this->f024['impr_nm']);
		
		$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f024['org_ntn_cd']); //Validación del pais de origen
		$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);

		$qCodigoLugarInspeccion = $controladorCatalogos->buscarCatalogoLugarInspeccion($conexionGUIA, $this->f024['isp_plc_cd']);  //Obtiene codigo en GUIA de lugar de inspeccion
		$codigoLugarInspeccion = pg_fetch_assoc($qCodigoLugarInspeccion);
		
		$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f024['dst_port_cd']); //Obtiene codigo en GUIA de puerto
		$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
		
		//Creacion de codigo para DDA
		$res = $controladorDestinacionAduanera->generarNumeroSolicitud($conexionGUIA, '%'.$identificadorDDA.'%');
		$solicitud = pg_fetch_assoc($res);
		$tmp= explode("-", $solicitud['numero']);
		$incremento = end($tmp)+1;
			
		$codigoSolicitud = 'DDA-'.$identificadorDDA.'-'.str_pad($incremento, 7, "0", STR_PAD_LEFT);
		echo OUT_MSG . 'Generación de codigo interno del DDA.';
		
		$idDDA = $controladorDestinacionAduanera->buscarDDAVUE($conexionGUIA, $identificadorDDA, $this->f024['req_no']);
		
		if(pg_num_rows($idDDA) == 0){
			
			$idDDA = $controladorDestinacionAduanera->guardarNuevoDDA($conexionGUIA, $identificadorDDA, $this->f024['expr_nm'], $this->f024['expr_ad'],
					$codigoPaisOrigen['id_localizacion'], $codigoPaisOrigen['nombre'], $this->f024['imp_prmt_fg_desc'], $this->f024['req_type_nm'],
					$this->f024['req_cat_nm'], $numeroCertificadoImportacion, $this->f024['exp_pht_ctft_no'], $codigoPuertoDestino['id_puerto'],
					$codigoPuertoDestino['nombre_puerto'], $this->f024['load_num'], $this->f024['trsp_way_nm'], $this->f024['load_doc_num'],
					$codigoLugarInspeccion['id_lugar'], $codigoLugarInspeccion['nombre'], $this->f024['dclr_rmk'],
					$codigoSolicitud, $this->f024['req_no']);
		}else{
			//actualizar registro													
						
				$controladorDestinacionAduanera->actualizarDatosDDA($conexionGUIA, pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'),$identificadorDDA, $this->f024['expr_nm'],
																	$this->f024['expr_ad'], $codigoPaisOrigen['id_localizacion'], $codigoPaisOrigen['nombre'], $this->f024['imp_prmt_fg_desc'],
																	$this->f024['req_type_nm'], $this->f024['req_cat_nm'], $numeroCertificadoImportacion, $this->f024['exp_pht_ctft_no'], $codigoPuertoDestino['id_puerto'],
																	$codigoPuertoDestino['nombre_puerto'], $this->f024['load_num'], $this->f024['trsp_way_nm'], $this->f024['load_doc_num'],
																	$codigoLugarInspeccion['id_lugar'], $codigoLugarInspeccion['nombre'], $this->f024['dclr_rmk'], $codigoSolicitud,
																	$this->f024['req_no'], 'enviado');
				
				
				//////////////////////////////////////////////////////////////////////////////////////////////////////////
					
				//Buscar estado solicitud.
				$estadoSolicitud = $controladorRevisionSolicitudesVUE->buscarUltimoEstadoSolicitud($conexionGUIA, pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), 'DDA');
					
				switch (pg_fetch_result($estadoSolicitud, 0, 'tipo_inspector')){
					case 'Documental':
						$controladorDestinacionAduanera->enviarDDA($conexionGUIA, pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), 'enviado');
					break;
					
					case 'Técnico':
						$controladorDestinacionAduanera->enviarDDA($conexionGUIA, pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), 'inspeccion');
					break;
					default:
						echo 'Estado de solicitud desconocida.';
				}
					
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			$controladorDestinacionAduanera->eliminarProductosDDA($conexionGUIA,pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'));
		}
		
		echo OUT_MSG . 'Datos de cabecera de DDA insertados';
		
		for ($i = 0; $i < count ($this->f024pd); $i++) {
		
				$partidaArancelariaVUE = $this->f024pd[$i]['hc'];
				$codigoProductoVUE = $this->f024pd[$i]['prdt_cd'];
		
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
				$producto = pg_fetch_assoc($qProductoGUIA);
		
				$controladorDestinacionAduanera -> guardarDDAProductos($conexionGUIA, pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), $producto['id_producto'], $producto['nombre_comun'], $this->f024pd[$i]['pkgs_qt'], $this->f024pd[$i]['pkgs_ut'], $partidaArancelariaVUE, $codigoProductoVUE);
		}
		
		echo OUT_MSG . 'Datos de detalle de DDA insertados';
		
		return true;
	}
	
	public function insertarDocumentosAdjuntosGUIA($documentos){
		
		$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
		$conexionGUIA = new Conexion();
		
		$identificadorDDA = $this->f024['impr_idt_no'];
		
		switch ($this->f024['req_type_cd']){
			case '01':
				$area = 'SA';
				break;
			case '02':
				$area = 'SV';
				break;
			default:
				$area = 'desconocido';
		}
		
		$idDDA = pg_fetch_result($controladorDestinacionAduanera->buscarDDAVUE($conexionGUIA, $identificadorDDA, $this->f024['req_no']),0,'id_destinacion_aduanera');
		
		$controladorDestinacionAduanera->eliminarArchivosAdjuntos($conexionGUIA, $idDDA, $this->f024['req_no']);
		
		if(pg_num_rows($documentos)!= 0){
		
			while($documentosAdjuntos = pg_fetch_assoc($documentos)){
				$ruta= explode('DEV/', $documentosAdjuntos['ruta']);
				//$ruta= explode('PROD/', $documentosAdjuntos['ruta']);
				$controladorDestinacionAduanera ->guardarDDAArchivos($conexionGUIA, $idDDA, $documentosAdjuntos['nombre'], $ruta[1], $area, $this->f024['req_no']);
			}
		}else{
			echo OUT_MSG . 'La solicitud DDA no posee documentos adjuntos.';
		}
		
		echo OUT_MSG . 'Documentos adjuntos de DDA insertados';
		
		return true;
	}

	public function actualizarDatosEnGUIA(){
		
		$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$resultado = array();
		
		$identificadorDDA = $this->f024['impr_idt_no'];
		$numeroCarga = $this->f024['load_num'];
		$documentoTransporte = $this->f024['load_doc_num'];
		$observaciones = $this->f024['dclr_rmk'];
		
		//validaciones de la funcion validar datos
		
		//TODO: cambiar referencia a nueva tabla de lugares de inspeccion (Bodegas autorizadas);
		
		$qCodigoLugarInspeccion = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f024['isp_plc_cd']); //Obtiene codigo en GUIA de lugar de inspeccion
		$codigoLugarInspeccion = pg_fetch_assoc($qCodigoLugarInspeccion);
		
		$qDDA = $controladorDestinacionAduanera->buscarDDAVUE($conexionGUIA, $identificadorDDA, $this->f024['req_no']);
		$registroDDA = pg_fetch_assoc($qDDA);
		
		$controladorDestinacionAduanera->actualizarDatosDDA($conexionGUIA, $registroDDA['id_destinacion_aduanera'], $numeroCarga, $documentoTransporte, $idLugarInspeccion, $nombreLugarInspeccion, $observaciones, $this->f024['req_no']);
		
		return true;
	}

	public function recaudacionTasa($recaudacionTasas){
		return true;
	}

	public function cancelar(){
		
		$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
		$conexionGUIA = new Conexion();
		
		$identificadorDDA = $this->f024['impr_idt_no'];
		$idVue = $this->f024['req_no'];
		
		$idDDA = $controladorDestinacionAduanera->buscarDDAVUE($conexionGUIA, $identificadorDDA, $idVue);
		
		if(pg_num_rows($idDDA)!=0){
			
			$controladorDestinacionAduanera->enviarDDA($conexionGUIA, pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), 'cancelado');
			
			//for ($i = 0; $i < count ($this->f024pd); $i++) {
				$controladorDestinacionAduanera-> enviarProdctosDDA($conexionGUIA,  pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), 'cancelado');
			//}
			
		}
		
		echo OUT_MSG. 'Solicitud cancelada.';
		return true;
	}

	public function anular(){
			$controladorDestinacionAduanera = new ControladorDestinacionAduanera();
			$conexionGUIA = new Conexion();
			
			$identificadorDDA = $this->f024['impr_idt_no'];
			$idVue = $this->f024['req_no'];
			
			$idDDA = $controladorDestinacionAduanera->buscarDDAVUE($conexionGUIA, $identificadorDDA, $idVue);
			
			$controladorDestinacionAduanera->enviarDDA($conexionGUIA, pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), 'anulado');
			
			for ($i = 0; $i < count ($this->f024pd); $i++) {
				$controladorDestinacionAduanera-> enviarProdctosDDA($conexionGUIA,  pg_fetch_result($idDDA, 0, 'id_destinacion_aduanera'), 'anulado');
			}
			
			echo OUT_MSG. 'Solicitud anulada.';
			return true;
	}
	
	public function reversoSolicitud(){
		return true;
	}
	
	public function generarFacturaProcesoAutomatico(){
		return true;
	}

}

/***************************************************************************************************************************/
/***************************************************************************************************************************/


class Zoosanitario extends FormularioVUE{

	public $f008;
	public $f008pd = array();

	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){

			/* $camposObligatorios = array(
		 'cabecera' => array(
		 		'IMPR_NM',
		 		'IMPR_RPST_NM',
		 		'IMP_PRVHC_NM',
		 		'IMPR_CUTY_NM',
		 		'IMPR_PRQI_NM',
		 		'IMPR_AD',
		 		'IMPR_TEL_NO',
		 		'IMPR_CEL_NO',
		 		'IMPR_EM'),
					
				'productos' => array(
						'REQ_NO',
						'HC',
						'PRDT_NM',
						'ORG_NTN_CD',
						'PRDT_CD',
						'AGRCD_PRDT_CD',
						'PRDT_BUSS_ACT_CD',
						'PRDT_BUSS_ACT_NM',
						'PRDT_TYPE_NM',
						'PRDT_STN')
		); */

		//Trayendo los datos de cabecera del formulario 101-024

		$this-> f008 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_008
				WHERE REQ_NO = '$numeroDeSolicitud'"));
			
		//Trayendo los datos de detalle del formulario 101-024

		$c_f008pd = $coneccionVUE ->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_008_pd
				WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($fila = pg_fetch_assoc($c_f008pd)){
			$this-> f008pd[] = $fila;
		}

		parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
	}

	public function validarDatosFormulario(){
		
		echo  PRO_MSG. 'validando formulario 101-008';
		$solicitudEsValida = true;
		$certificadoImpValido = true;
		$resultado = array();
		
		//parent::validarCamposObligatorios($f008,'cabecera');
		//parent::validarCamposObligatorios($f008_pd,'productos');
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorZoosanitarioExportacion = new ControladorZoosanitarioExportacion();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		
		$identificadorZOO = $this->f008['expr_idt_no'];

		
		//Cabecera de producto
		for ($i = 0; $i < count($this->f008['req_no']); $i++) {
			
			
			//Obtener actividad del exportador
			$qOperacion = $controladorCatalogos->buscarIdOperacion($conexionGUIA,'SA','Exportador');
			$operacion = pg_fetch_assoc($qOperacion);
			
			//Busca si el operador se encuentra registrado en el sistema GUIA
			$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $identificadorZOO);
				
			if( pg_num_rows($qOperador) == 0 ){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El operador '.$this->f008['expr_idt_no'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El operador '.$this->f008['expr_idt_no'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
				
			//Obtener pais de destino
			$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f008['dst_ntn_cd']);
			$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
			
			if(pg_num_rows($qCodigoPaisDestino) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El pais de destino  '.$this->f008['dst_ntn_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El pais de destino '.$this->f008['dst_ntn_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			//Obtener puerto de embarque
			$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f008['spm_port_cd']);
				
			if(pg_num_rows($qCodigoPuertoEmbarque) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El puerto de embarque  '.$this->f008['spm_port_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El puerto de embarque '.$this->f008['spm_port_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			//Validación del sitio del exportador
			$qSitioInspeccion = $controladorRegistroOperador->buscarSitios($conexionGUIA, $identificadorZOO, $this->f008['isp_reg_sitio']);
			
			if(pg_num_rows($qSitioInspeccion) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El sitio con código  '.$this->f008['isp_reg_sitio'].' no se encuentra registrado. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El sitio con código '.$this->f008['isp_reg_sitio'].' no se encuentra registrado. ';
				break;
			}
			
			$qUso = $controladorCatalogos -> obtenerUsoVUE($conexionGUIA, $this->f008['prdt_use_cd']);
			
			if(pg_num_rows($qUso) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El uso con código  '.$this->f008['prdt_use_cd'].' no se encuentra registrado. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El uso con código  '.$this->f008['prdt_use_cd'].' no se encuentra registrado. ';
				break;
			}
			
			/*$productoDuplicado = parent::verificarProductoRepetido($this->f008pd);
			
			if($productoDuplicado){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG.  'No se permite agregar el mismo producto dos veces.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'No se permite agregar el mismo producto dos veces.';
				break;
			}*/
			
	    }	

		
	    if($certificadoImpValido){
	    	
	    	for ($j = 0; $j <count($this->f008pd); $j++) {
	    			
	    		$partidaArancelariaVUE = $this->f008pd[$j]['hc'];
	    		$codigoProductoVUE = $this->f008pd[$j]['prdt_cd'];
	    		
	    		//Validar Producto
	    		$productoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
	    		$producto = pg_fetch_assoc($productoGUIA);
	    		
	    		if( pg_num_rows($productoGUIA) == 0 ){
	    			$solicitudEsValida = false;
	    			echo IN_MSG. 'El producto '.$this->f008pd['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
	    			$resultado[0] = SUBSANACION_REQUERIDA;
	    			$resultado[1] = 'El producto '.$this->f008pd['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
	    			break;
	    		}
	    	
	    		//Validar la Unidad de peso
	    		if (strtoupper($this->f008pd[$j]['prdt_qt_ut']) != $producto['unidad_medida']){
	    			$solicitudEsValida = false;
	    			echo IN_MSG. 'La unidad de medidad '.$this->f008pd[$j]['prdt_qt_ut'].' no corresponde a la del producto '.$this->f008pd[$j]['prdt_nm'];
	    			$resultado[0] = SUBSANACION_REQUERIDA;
	    			$resultado[1] = 'La unidad de medidad '.$this->f008pd[$j]['prdt_qt_ut'].' no corresponde a la del producto '.$this->f008pd[$j]['prdt_nm'];
	    			break;
	    	
	    		}
	    		
	    		//Validar pais de origen
	    		$qCodigoPaisOrigen= $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f008pd[$j]['org_ntn_cd']);
	    		
	    		if(pg_num_rows($qCodigoPaisOrigen) == 0){
	    			$solicitudEsValida = false;
	    			echo IN_MSG. 'El pais de origen  '.$this->f008pd[$j]['org_ntn_nm'].' no se encuentra registrado en Agrocalidad.';
	    			$resultado[0] = SUBSANACION_REQUERIDA;
	    			$resultado[1] = 'El pais de origen '.$this->f008pd[$j]['org_ntn_nm'].' no se encuentra registrado en Agrocalidad.';
	    			break;
	    		}
	    			

	    		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    		//Validar si el producto esta activo para un pais y actividad
	    		$qEstadoProductoPais = $controladorRequisitos ->consultarEstadoProductoPaisRequisito($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'Exportación', 'activo');  //estado producto pais requisito
	    		 
	    		if( pg_num_rows($qEstadoProductoPais) == 0 ){
	    			$solicitudEsValida = false;
	    			echo IN_MSG. 'El producto '.$this->f008pd[$j]['prdt_nm'].' se encuentra inactivo para la operación de exportación al pais '.$this->f008['dst_ntn_nm'];
	    			$resultado[0] = SOLICITUD_NO_APROBADA;
	    			$resultado[1] = 'El producto '.$this->f008pd[$j]['prdt_nm'].' se encuentra inactivo para la operación de exportación al pais '.$this->f008['dst_ntn_nm'];
	    			break;
	    		}
	    		
	    		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    		
	    		//Validar si existe el exportador con el producto y pais detallado
	    		$qOperacionOperador = $controladorRegistroOperador -> buscarOperadorProductoPaisActividad($conexionGUIA, $identificadorZOO, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], $operacion['id_tipo_operacion'], 'registrado');  //solicitud en estaso registrado
	    		
	    		if( pg_num_rows($qOperacionOperador) == 0 ){
	    			$solicitudEsValida = false;
	    			echo IN_MSG. 'El operador '.$identificadorZOO.' no posee la operacion de exportación al pais '.$this->f008['dst_ntn_nm'].' con el producto '.$this->f008pd[$j]['prdt_nm'].' ';
	    			$resultado[0] = SUBSANACION_REQUERIDA;
	    			$resultado[1] = 'El operador '.$identificadorZOO.' no posee la operacion de exportación al pais '.$this->f008['dst_ntn_nm'].' con el producto '.$this->f008pd[$j]['prdt_nm'].' ';
	    			break;
	    		}
	    		
	    		$qProveedores = $controladorRegistroOperador->buscarProveedoresOperadorProducto($conexionGUIA, $identificadorZOO, $operacion['id_tipo_operacion'], $producto['id_producto'], "('registrado','registradoObservacion')");
	    			
	    		if( pg_num_rows($qProveedores) == 0 ){ // Busqueda de proveedores del operador en base de datos GUIA.
	    			$solicitudEsValida = false;
	    			echo IN_MSG. 'El operador  '.$identificadorZOO.' no posee proveedores para su actividad. ';
	    			$resultado[0] = SOLICITUD_NO_APROBADA;
	    			$resultado[1] = 'El operador '.$identificadorZOO.' no posee proveedores para su actividad. ';
	    			break;
	    		}
	    	}
	    }
			 
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
	}

	public function validarActualizacionDeDatos(){
		
		echo  PRO_MSG. 'validando actualizacion de formulario ';
		$resultado = array();
		
		$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
		$resultado[1] = 'No se permite modificar los datos del certificado zoosanitario de exportación.';
		
		return $resultado;
		
	}

	public function insertarDatosEnGUIA(){
		
		$controladorZoosanitarioExportacion = new ControladorZoosanitarioExportacion();
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$identificadorZOO = $this->f008['expr_idt_no'];
		
		parent::asignarUsuarioGUIA($identificadorZOO);
		
		parent::asignarPerfilGUIA($identificadorZOO, 'Usuario externo');
		parent::asignarPerfilGUIA($identificadorZOO, 'Operadores de Comercio Exterior');
		parent::asignarPerfilGUIA($identificadorZOO, 'Operadores');
		
		parent::asignarAplicacionGUIA( $identificadorZOO, 'PRG_ZOO');
		parent::asignarAplicacionGUIA( $identificadorZOO, 'PRG_REGISTROOPER');
		
		parent::ingresarRegistroOperador($identificadorZOO, $this->f008['expr_nm']);
		
		//Obtener actividad del exportador
		$qOperacion = $controladorCatalogos->buscarIdOperacion($conexionGUIA,'SA','Exportador');
		$operacion = pg_fetch_assoc($qOperacion);
					
		//Obtener pais de destino
		$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f008['dst_ntn_cd']);
		$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
		
		//Obtener pais de embarque
		$qCodigoPaisEmbarque= $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f008['emb_ntn_cd']);
		$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
			
		//Obtener puerto de embarque
		$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f008['spm_port_cd']);
		$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
		
		//Codigo uso
		$qUso = $controladorCatalogos -> obtenerUsoVUE($conexionGUIA, $this->f008['prdt_use_cd']);
		$idUso = pg_fetch_assoc($qUso);
		
		
		//Obtener codigo sitio
		//$qSitioInspeccion = $controladorRegistroOperador->buscarSitios($conexionGUIA, $identificadorZOO, $this->f008['isp_req_sitio']);
		//$codigoSitio = pg_fetch_assoc($qSitioInspeccion);
		

		$res = $controladorZoosanitarioExportacion->generarNumeroSolicitud($conexionGUIA, '%'.$identificadorZOO.'%');
		$solicitud = pg_fetch_assoc($res);
		$tmp= explode("-", $solicitud['numero']);
		$incremento = end($tmp)+1;
			
		$codigoSolicitud = 'ZOO-'.$identificadorZOO.'-'.str_pad($incremento, 7, "0", STR_PAD_LEFT);
		
		echo OUT_MSG . 'Generación de codigo interno de Zoosanitario';
		
		//Validar si existe una solicitud ingresada por VUE
		$idZOO = $controladorZoosanitarioExportacion -> buscarZooVUE($conexionGUIA,$identificadorZOO,$this->f008['req_no']);
		
		
		if(pg_num_rows($idZOO) == 0){
			$idZOO = $controladorZoosanitarioExportacion->guardarNuevaExportacion($conexionGUIA, $identificadorZOO, $this->f008['rptc_nm'],'', $codigoPuertoEmbarque['id_puerto'], $codigoPuertoEmbarque['nombre_puerto'],
																					$this->f008['mean_tr_nm'], $this->f008['pkg_qt'], $this->f008['pkg_qt_ut'] ,$this->f008['isp_reg_sitio'],$codigoSolicitud,
																					$this->f008['rmk'], $this->f008['csgn_nm'], $this->f008['csgn_ad'], $codigoPaisDestino['id_localizacion'], 
																					$codigoPaisDestino['nombre'], $idUso['id_uso'], $codigoPaisEmbarque['id_localizacion'], 
																					$codigoPaisEmbarque['nombre'], 0,'', $this->f008['isp_de'] ,$this->f008['req_no']);
		}
		else{
						
			$controladorZoosanitarioExportacion->actualizarZoosanitario($conexionGUIA, pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'), $this->f008['rptc_nm'], '', $codigoPuertoEmbarque['id_puerto'], $codigoPuertoEmbarque['nombre_puerto'], 
																		$this->f008['mean_tr_nm'], $this->f008['pkg_qt'], $this->f008['pkg_qt_ut'], $this->f008['isp_reg_sitio'], $this->f008['isp_de'], $this->f008['csgn_nm'], $this->f008['csgn_ad'], $codigoPaisDestino['id_localizacion'], 
																		$codigoPaisDestino['nombre'],  $idUso['id_uso'], $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'], 0, '', 'enviado');
			
			//////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			//Buscar estado solicitud.
			$estadoSolicitud = $controladorRevisionSolicitudesVUE->buscarUltimoEstadoSolicitud($conexionGUIA, pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'), 'Zoosanitario');
			
			switch (pg_fetch_result($estadoSolicitud, 0, 'tipo_inspector')){
				case 'Documental':
						$controladorZoosanitarioExportacion->actualizarEstadoSolicitud($conexionGUIA, pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'), 'enviado');
					break;
				/*case 'Fianciero':
					$controladorZoosanitarioExportacion->actualizarEstadoSolicitud($conexionGUIA, pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'), 'verificacion');
					break;*/
				case 'Técnico':
					$controladorZoosanitarioExportacion->actualizarEstadoSolicitud($conexionGUIA, pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'), 'inspeccion');
					break;
				default:
					echo 'Estado de solicitud desconocida.';
			}
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////
			

			$controladorZoosanitarioExportacion->eliminarProductosExportacionZoosanitario($conexionGUIA,pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'));
		}
		
		echo OUT_MSG . 'Datos de cabecera de Zoosanitario insertados';
		
		
		for ($i = 0; $i < count ($this->f008pd); $i++) {
		
			$partidaArancelariaVUE = $this->f008pd[$i]['hc'];
			$codigoProductoVUE = $this->f008pd[$i]['prdt_cd'];
		
			$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
			$producto = pg_fetch_assoc($qProductoGUIA);
			
			$qCodigoPaisOrigen= $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f008pd[$i]['org_ntn_cd']);
			$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
		
			$controladorZoosanitarioExportacion -> guardarExportacionesProductos($conexionGUIA,pg_fetch_result($idZOO, 0, 'id_zoo_exportacion'),$producto['id_producto'],
																				$producto['nombre_comun'],$this->f008pd[$i]['anml_raza_nm'],$this->f008pd[$i]['anml_sex_cd'],$this->f008pd[$i]['anml_age_cd'],
																				$this->f008pd[$i]['prdt_qt'], $this->f008pd[$i]['prdt_qt_ut'], $codigoPaisOrigen['id_localizacion'], 
																				$codigoPaisOrigen['nombre'],$partidaArancelariaVUE, $codigoProductoVUE);
		}
		
		echo OUT_MSG . 'Datos de detalle de Zoosanitario insertados';
		
		return true;
	}

	public function insertarDocumentosAdjuntosGUIA($documentos){
		
		$controladorZoosanitario = new ControladorZoosanitarioExportacion();
		$controladorFitosanitario = new ControladorFitosanitario();
		$conexionGUIA = new Conexion();
		
		$identificadorZOO = $this->f008['expr_idt_no'];
				
		$idZoosanitario = pg_fetch_result($controladorZoosanitario->buscarZooVUE($conexionGUIA, $identificadorZOO,$this->f008['req_no']), 0, 'id_zoo_exportacion');
		
		$controladorZoosanitario->eliminarArchivosAdjuntos($conexionGUIA, $idZoosanitario, $this->f008['req_no']);
		
		if(pg_num_rows($documentos)!= 0){
		
			while($documentosAdjuntos = pg_fetch_assoc($documentos)){
				$ruta= explode('DEV/', $documentosAdjuntos['ruta']);
				//$ruta= explode('PROD/', $documentosAdjuntos['ruta']);
				$controladorZoosanitario->guardarExportacionesArchivos($conexionGUIA, $idZoosanitario, $documentosAdjuntos['nombre'], $ruta[1], 'SV', $this->f008['req_no']);
			}
		}else{
			echo OUT_MSG . 'La solicitud zoosanitaria no posee documentos adjuntos.';
		}
		
		echo OUT_MSG . 'Documentos adjuntos zoosanitario insertados';
		
		return true;
	}

	public function actualizarDatosEnGUIA(){
		return true;
	}

	public function recaudacionTasa($recaudacionTasas){
		
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorZoosanitario = new ControladorZoosanitarioExportacion();
		$conexionGUIA = new Conexion();
		
		$datosTasa = pg_fetch_assoc($recaudacionTasas);
		
		$identificadorZOO = $this->f008['expr_idt_no'];
		
		$idZoo = pg_fetch_result($controladorZoosanitario->buscarZooVUE($conexionGUIA, $identificadorZOO, $this->f008['req_no']), 0, 'id_zoo_exportacion');
		
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idZoo, 'Zoosanitario', 'Financiero');
		$financiero = pg_fetch_assoc($qFinanciero);
		
		$controladorRevisionSolicitudesVUE->guardarInspeccionFinanciero($conexionGUIA, $financiero['id_financiero'], $financiero['identificador_inspector'], 'aprobado', null, $datosTasa['banco'], $datosTasa['monto_recaudado'], $datosTasa['fecha_recaudacion'],$datosTasa['banco'], $datosTasa['numero_orden_vue'], $datosTasa['numero_orden_vue']);
		
		echo OUT_MSG . 'Actualización de datos de tasas de recaudación';
		
		//Asigna el resultado de revisión de pago de solicitud de importacion
		//$controladorZoosanitario->enviarZoo($conexionGUIA, $idZoo, 'inspeccion');
		$controladorZoosanitario->enviarZoo($conexionGUIA, $idZoo, 'verificacion');
		
		echo OUT_MSG . 'Solicitud de Zoosanitario enviada a verificación de pago.';
		
		return true;
	}

	public function cancelar(){
		
		$controladorZoosanitario = new ControladorZoosanitarioExportacion();
		$conexionGUIA = new Conexion();
		
		$identificadorZOO = $this->f008['expr_idt_no'];
		$idVue = $this->f008['req_no'];
		
		$idZoosanitario = $controladorZoosanitario->buscarZooVUE($conexionGUIA, $identificadorZOO, $idVue);
						
		if(pg_num_rows($idZoosanitario)!= 0){
			
			$controladorZoosanitario->enviarZoo($conexionGUIA, pg_fetch_result($idZoosanitario, 0, 'id_zoo_exportacion'), 'cancelado');
			
			//for ($i = 0; $i < count ($this->f008pd); $i++) {
				$controladorZoosanitario->enviarZoosanitarioProductos($conexionGUIA, pg_fetch_result($idZoosanitario, 0, 'id_zoo_exportacion'), 'cancelado');
			//}
			
		}
		
		echo OUT_MSG. 'Solicitud cancelada.';
		
		return true;
	}

	public function anular(){
		
		$controladorZoosanitario = new ControladorZoosanitarioExportacion();
		$conexionGUIA = new Conexion();
		
		$identificadorZOO = $this->f008['expr_idt_no'];
		$idVue = $this->f008['req_no'];
		
		$idZoosanitario = $controladorZoosanitario->buscarZooVUE($conexionGUIA, $identificadorZOO, $idVue);
		
		$controladorZoosanitario->enviarZoo($conexionGUIA, pg_fetch_result($idZoosanitario, 0, 'id_zoo_exportacion'), 'anulada');
		
		for ($i = 0; $i < count ($this->f008pd); $i++) {
			$controladorZoosanitario->enviarZoosanitarioProductos($conexionGUIA, pg_fetch_result($idZoosanitario, 0, 'id_zoo_exportacion'), 'anulada');
		}
		
		echo OUT_MSG. 'Solicitud anulada.';
		
		return true;
	}
	
	public function reversoSolicitud(){
		return true;
	}
	
	public function generarFacturaProcesoAutomatico(){
		return true;
	}

}

/***************************************************************************************************************************/
/***************************************************************************************************************************/

class Fitosanitario extends FormularioVUE{

	public $f031;
	public $f031pd = array();
	public $f031tr = array();

	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){

		/* $camposObligatorios = array(
		 'cabecera' => array(
		 		'IMPR_NM',
		 		'IMPR_RPST_NM',
		 		'IMP_PRVHC_NM',
		 		'IMPR_CUTY_NM',
		 		'IMPR_PRQI_NM',
		 		'IMPR_AD',
		 		'IMPR_TEL_NO',
		 		'IMPR_CEL_NO',
		 		'IMPR_EM'),
					
				'productos' => array(
						'REQ_NO',
						'HC',
						'PRDT_NM',
						'ORG_NTN_CD',
						'PRDT_CD',
						'AGRCD_PRDT_CD',
						'PRDT_BUSS_ACT_CD',
						'PRDT_BUSS_ACT_NM',
						'PRDT_TYPE_NM',
						'PRDT_STN')
		); */

		//Trayendo los datos de cabecera del formulario 101-024

		$this-> f031 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta(" SELECT *
																		FROM vue_gateway.tn_agr_031
																		WHERE REQ_NO = '$numeroDeSolicitud'"));
			
		//Trayendo los datos de detalle del formulario 101-024

		$c_f031pd = $coneccionVUE ->ejecutarConsulta(" SELECT *
														FROM vue_gateway.tn_agr_031_pd
														WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($fila = pg_fetch_assoc($c_f031pd)){
			$this-> f031pd[] = $fila;
		}
		
		$c_f031tr = $coneccionVUE->ejecutarConsulta(" SELECT *
														FROM vue_gateway.tn_agr_031_tr
														WHERE REQ_NO = '$numeroDeSolicitud'");
		
		while ($transito = pg_fetch_assoc($c_f031tr)){
			$this-> f031tr[] = $transito;
		}

		parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
	}

	public function validarDatosFormulario(){
		echo  PRO_MSG. 'validando formulario 101-031';
		$solicitudEsValida = true;
		$resultado = array();
		$arrayExportadores = array(); // creo el array
		
		//parent::validarCamposObligatorios($f031,'cabecera');
		//parent::validarCamposObligatorios($f031_pd,'productos');
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$musaceas = false;
		$certificadoImpValido = true;
		$transito = false; 
		
		//Obtiene id de operación de exportación para sanidad vegetal
		$qOperacion = $controladorCatalogos->buscarIdOperacion($conexionGUIA, 'SV', 'Exportador'); //Obtiene id de exportacion para SV
		$operacion = pg_fetch_assoc($qOperacion);
		
		for ($i = 0; $i < count ($this->f031['req_no']); $i++) {		
			
			//Obtiene país de destino
			$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['dst_ntn_cd']); //Validación del pais de destino
			$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
			
			
			if(pg_num_rows($qCodigoPaisDestino) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El pais de origen  '.$this->f031['dst_ntn_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El pais de origen '.$this->f031['dst_ntn_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			//Obtiene puerto de destino 			
			$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031['dst_port_cd']); //Validación del puerto de destino
			
			if(pg_num_rows($qCodigoPuertoDestino) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El puerto de destino '.$this->f031['dst_port_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El puerto de destino '.$this->f031['dst_port_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			
			//Obtiene país de embarque
			$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['spm_ntn_cd']); //Validación del pais de embarque
			$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
				
				
			if(pg_num_rows($qCodigoPaisEmbarque) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El pais de embarque  '.$this->f031['spm_ntn_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El pais de embarque '.$this->f031['spm_ntn_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
				
			//Obtiene puerto de embarque
			$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031['spm_port_cd']); //Validación del puerto de embarque
				
			if(pg_num_rows($qCodigoPuertoEmbarque) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El puerto de embarque '.$this->f031['spm_port_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El puerto de embarque '.$this->f031['spm_port_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
			//Validación de Puerto sea Ecuador
			
			if($codigoPuertoEmbarque['codigo_pais'] != 'EC'){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El puerto de embarque '.$this->f031['spm_port_nm'].' no correponde a un puerto de Ecuador.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El puerto de embarque '.$this->f031['spm_port_nm'].' no correponde a un puerto de Ecuador.';
				break;
			}
			
			//Validación puerto tenga provincia 
			
			if($codigoPuertoEmbarque['id_provincia'] == ''){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El puerto de embarque '.$this->f031['spm_port_nm'].' no tiene registrado un lugar autorizado de inspección por Agrocalidad.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El puerto de embarque '.$this->f031['spm_port_nm'].' no tiene registrado un lugar autorizado de inspección por Agrocalidad.';
				break;
			}
			
			//Obtiene provincia
			$qCodigoProvincia = $controladorCatalogos->obtenerNombreLocalizacion($conexionGUIA, $codigoPuertoEmbarque['id_provincia']); //Provincia de inspeccion
			
			if(pg_num_rows($qCodigoProvincia) == 0){
					$certificadoImpValido = false;
					$solicitudEsValida = false;
					echo IN_MSG. 'La provincia del puerto de embarque '.$this->f031['spm_port_nm'].' no se encuentra registrado en Agrocalidad.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La provincia del puerto de embarque '.$this->f031['spm_port_nm'].' no se encuentra registrado en Agrocalidad.';
					break;
				}
								
			
			$qCodigoProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['isp_plc_prvhc_cd']); //Validación de la provincia de inspeccion
			
			if(pg_num_rows($qCodigoProvincia) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El código de la provincia '.$this->f031['isp_plc_prvhc_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El código de la provincia '.$this->f031['isp_plc_prvhc_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}
			
			/*$qCodigoCanton = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['isp_plc_cuty_cd']); //Validación del canton de inspeccion
			
			if(pg_num_rows($qCodigoCanton) == 0){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'El código del canton '.$this->f031['isp_plc_cuty_nm'].' no se encuentra registrado en Agrocalidad. ';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'El código del canton '.$this->f031['isp_plc_cuty_nm'].' no se encuentra registrado en Agrocalidad. ';
				break;
			}*/	
			
			/*$productoDuplicado = parent::verificarProductoRepetido($this->f031pd);
				
			if($productoDuplicado){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG.  'No se permite agregar el mismo producto dos veces.';
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = 'No se permite agregar el mismo producto dos veces.';
				break;
			}*/
			
			//TODO: Variable la cual define si se valdia transito  
			
			if($this->f031['trsp_use_fg'] == 'S'){
				$transito = true;
			}
			
		}
		
		if($transito){
			
			for($k = 0; $k < count($this->f031tr); $k++){				
				//Obtiene país de destino
				$qPaisTransito = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031tr[$k]['trsp_cntry_cd']); //Validación del pais de transito				
					
				if(pg_num_rows($qPaisTransito) == 0){
					$certificadoImpValido = false;
					$solicitudEsValida = false;
					echo IN_MSG. 'El pais de transito  '.$this->f031tr[$k]['trsp_cntry_nm'].' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El pais de transito  '.$this->f031tr[$k]['trsp_cntry_nm'].' no se encuentra registrado en Agrocalidad. ';
					break;
				}
					
				//Obtiene puerto de destino
				$qCodigoPuertoTransito = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031tr[$k]['spm_port_cd']); //Validación del puerto de transito
					
				if(pg_num_rows($qCodigoPuertoTransito) == 0){
					$certificadoImpValido = false;
					$solicitudEsValida = false;
					echo IN_MSG. 'El puerto de transito '.$this->f031tr[$k]['spm_port_cd'].' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El puerto de transito '.$this->f031tr[$k]['spm_port_cd'].' no se encuentra registrado en Agrocalidad. ';
					break;
				}					
			}
		}
		
		/*****************************/
		
		
		if($certificadoImpValido){
			for ($j = 0; $j < count ($this->f031pd); $j++) {
					
				$partidaArancelariaVUE = $this->f031pd[$j]['hc'];
				$codigoProductoVUE = $this->f031pd[$j]['prdt_cd'];
		
				//Busca el id del producto en la base de GUIA
		
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
				$producto = pg_fetch_assoc($qProductoGUIA);
		
				if( pg_num_rows($qProductoGUIA) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'El producto '.$this->f031pd[$j]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El producto '.$this->f031pd[$j]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
					break;
				}
				
				$unidadMedidaProductoVUE = $this->f031pd[$j]['prdt_qt_ut'];
				
				if($unidadMedidaProductoVUE != $producto['unidad_medida']){
					$solicitudEsValida = false;
					echo IN_MSG. 'La unidad fisica '.$unidadMedidaProductoVUE.' no corresponde a la del producto '.$this->f031pd[$j]['prdt_nm'];
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La unidad fisica '.$unidadMedidaProductoVUE.' no corresponde a la del producto '.$this->f031pd[$j]['prdt_nm'];
					break;
				}
		
			
				//Validación de musáceas
				$partidaArancelaria =  substr($partidaArancelariaVUE,0,8);
				
				if( $partidaArancelaria == '06021010' || $partidaArancelaria == '06024000' || $partidaArancelaria == '06029090' || 
					$partidaArancelaria == '06031100' || $partidaArancelaria == '06031210' || $partidaArancelaria == '06031290' || 
					$partidaArancelaria == '06031300' || $partidaArancelaria == '06031410' || $partidaArancelaria == '06031490' || 
					$partidaArancelaria == '06031500' || $partidaArancelaria == '06031910' || $partidaArancelaria == '06031920' || 
					$partidaArancelaria == '06031930' || $partidaArancelaria == '06031940' || $partidaArancelaria == '06031990' || 
					$partidaArancelaria == '06039000' || $partidaArancelaria == '06042000' || $partidaArancelaria == '06049000'){
					//if($this->f031['dst_ntn_cd'] == 'PA' || $this->f031['dst_ntn_cd'] == 'RU'){
						$solicitudEsValida = false;
						echo IN_MSG. ' Los  certificados fitosaniatrios para productos ornamentales con destino Rusia y Panamá se emiten en el Sistema SANIFLORES y para otros destinos se emite de forma manual.';
						$resultado[0] = SOLICITUD_NO_APROBADA;
						$resultado[1] = 'Los  certificados fitosaniatrios para productos ornamentales con destino Rusia y Panamá se emiten en el Sistema SANIFLORES y para otros destinos se emite de forma manual.';
						break;
					//}
				}
		
				//Valores de partidas arancelarias de comparacion pendientes para actualizar por Margarita, 22 de febrero 2014
				if($partidaArancelaria == '08031010' || $partidaArancelaria == '08039011' || $partidaArancelaria == '08039012' || 
				   $partidaArancelaria == '08039019'){
					$solicitudEsValida = false;
						echo IN_MSG. 'Los  certificados fitosaniatrios para musaceas se emiten de forma manual.';
						$resultado[0] = SOLICITUD_NO_APROBADA;
						$resultado[1] = 'Los  certificados fitosaniatrios para musaceas se emiten de forma manual.';
						break;
				}
		
				/*if($musaceas){
					//Si el producto es musácea validar que el producto sea permitido para el pais detallado
					$qProductoPaisAutorizado = $controladorRequisitos->consultarProductoPais($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'Exportación');
											
					if( pg_num_rows($qProductoPaisAutorizado) == 0 ){ // Busqueda del producto con PPR en base de datos GUIA.
						$solicitudEsValida = false;
						echo IN_MSG. 'El producto '.$this->f031pd[$j]['prdt_nm'].' no posee requisitos para exportación a '.$this->f031['dst_ntn_nm'].'. ';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'El producto '.$this->f031pd[$j]['prdt_nm'].' no posee requisitos exportar a '.$this->f031['dst_ntn_nm'].'. ';
						break;
					}
						
					//Verifica que el código de exportacion de musaceas haya sido ingresado por el usuario
					if($this->f031pd[$j]['prdt_per_exp']==''){
						$solicitudEsValida = false;
						echo IN_MSG. 'No ha ingresado el número de autorización para musáceas para el producto '.$this->f031pd[$j]['prdt_nm'].'. ';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'No ha ingresado el número de autorización para musáceas para el producto '.$this->f031pd[$j]['prdt_nm'].'. ';
						break;
					}
								
						
				}else{*/
					//Verifica que los operadores detallados existan en Agrocalidad como exportadores del producto al pais detallado
					//No incluye operadores de musáceas
					
					$qOperador = $controladorRegistroOperador->buscarOperador($conexionGUIA, $this->f031pd[$j]['expr_idt_no']);
					$arrayExportadores[] = $this->f031pd[$j]['expr_idt_no'];
					
					if( pg_num_rows($qOperador) == 0 ){
						$certificadoImpValido = false;
						$solicitudEsValida = false;
						echo IN_MSG. 'El operador de comercio exterior '.$this->f031pd[$j]['expr_idt_no'].' no se encuentra registrado en Agrocalidad. ';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'El operador de comercio exterior '.$this->f031pd[$j]['expr_idt_no'].' no se encuentra registrado en Agrocalidad. ';
						break;
					}
					
					
					$qOperacionProductos = $controladorRegistroOperador->buscarOperadorProductoPaisActividad($conexionGUIA, $this->f031pd[$j]['expr_idt_no'], $codigoPaisDestino['id_localizacion'], $producto['id_producto'], $operacion['id_tipo_operacion'], 'registrado');
					
					if( pg_num_rows($qOperacionProductos) == 0 ){ // Busqueda del operador con PPR en base de datos GUIA.
						$solicitudEsValida = false;
						echo IN_MSG. 'El operador '.$this->f031pd[$j]['expr_idt_no'].' no se encuentra registrado para exportar el producto '.$this->f031pd[$j]['prdt_nm'].' al país '.$this->f031['dst_ntn_nm'].'. ';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'El operador '.$this->f031pd[$j]['expr_idt_no'].' no se encuentra registrado para exportar el producto '.$this->f031pd[$j]['prdt_nm'].' al país '.$this->f031['dst_ntn_nm'].'. ';
						break;
					}
					
					
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					//Validar si el producto esta activo para un pais y actividad
					$qEstadoProductoPais = $controladorRequisitos ->consultarEstadoProductoPaisRequisito($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'Exportación', 'activo');  //estado producto pais requisito
					
					if( pg_num_rows($qEstadoProductoPais) == 0 ){
						$solicitudEsValida = false;
						echo IN_MSG. 'El producto '.$this->f031pd[$j]['prdt_nm'].' se encuentra inactivo para la operación de exportación al pais '.$this->f031['dst_ntn_nm'];
						$resultado[0] = SOLICITUD_NO_APROBADA;
						$resultado[1] = 'El producto '.$this->f031pd[$j]['prdt_nm'].' se encuentra inactivo para la operación de exportación al pais '.$this->f031['dst_ntn_nm'];
						break;
					}
					 
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						   
						
					//Validar que el operador tenga un proveedor en tabla de proveedores para exportacion al pais detallado
					
					$qProveedores = $controladorRegistroOperador->buscarProveedoresOperadorProducto($conexionGUIA, $this->f031pd[$j]['expr_idt_no'], $operacion['id_tipo_operacion'], $producto['id_producto'], "('registrado','registradoObservacion')");
											
					if( pg_num_rows($qProveedores) == 0 ){ // Busqueda de proveedores del operador en base de datos GUIA.
						$solicitudEsValida = false;
						echo IN_MSG. 'El operador '.$this->f031pd[$j]['expr_idt_no'].' no posee proveedores para su actividad. ';
						$resultado[0] = SOLICITUD_NO_APROBADA;
						$resultado[1] = 'El operador '.$this->f031pd[$j]['expr_idt_no'].' no posee proveedores para su actividad. ';
						break;
					}
				//}
			}
			
			if($solicitudEsValida){
				$arrayExportadoresSinRepetidos = array();
				$arrayExportadoresSinRepetidos = array_unique($arrayExportadores);
				
				if(count($arrayExportadoresSinRepetidos) != 1){
					$solicitudEsValida = false;
					echo IN_MSG. 'Se debe ingresar un exportador por solicitud. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'Se debe ingresar un exportador por solicitud. ';
				}
			}
			
			/*if(($musaceas) && ($solicitudEsValida)){
				
				$resultadoWebServices = $this->consultaWebServices($this->f031['req_no']);
				
				if(!$resultadoWebServices[2]){
					echo IN_MSG. $resultadoWebServices[1];
					$resultado[0] = $resultadoWebServices[0];
					$resultado[1] = $resultadoWebServices[1];
					$solicitudEsValida = false;
				}else{
					echo IN_MSG. $resultadoWebServices[1];
				}				
			}*/
		}
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
	}

	public function validarActualizacionDeDatos(){
		
		$solicitudEsValida = true;
		$certificadoImpValido = true;
		$transito = false;
		$resultado = array();
		
		$controladorFitosanitario = new ControladorFitosanitario();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		echo  PRO_MSG. 'validando actualizacion de formulario ';
		
		
		$qFito = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $this->f031['req_no']);
		$fitosanitario = pg_fetch_assoc($qFito);
		
		for ($i = 0; $i < count ($this->f031['req_no']); $i++) {
			
			//Obtiene país de destino
			$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['dst_ntn_cd']); //Validación del pais de destino
			$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
			
			if($codigoPaisDestino['id_localizacion'] != $fitosanitario['id_pais_destino']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo pais de destino.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo pais de destino.';
				break;
			}
			
			if($this->f031['agc_nm'] != $fitosanitario['nombre_agencia_carga']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo agencia de carga.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo agencia de carga.';
				break;				
			}
			
			if($this->f031['bdnm'] != $fitosanitario['nombre_marcas']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo nombre marcas.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo nombre marcas.';
				break;
			}
						
			//Obtiene puerto de embarque
			$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031['spm_port_cd']); //Validación del puerto de embarque
			$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
			
			if($codigoPuertoEmbarque['id_puerto'] != $fitosanitario['id_puerto_embarque']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo puerto de embarque.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo puerto de embarque.';
				break;
			}
			
			if($this->f031['dcd_trsp_way_nm'] != $fitosanitario['transporte']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo medio de transporte.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo medio de transporte.';
				break;
			}
			
			if( date('j/n/Y',strtotime($this->f031['dtf_spm_de'])) != date('j/n/Y',strtotime($fitosanitario['fecha_embarque']))){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo fecha de embarque.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo fecha de embarque.';
				break;
			}
			
			if($this->f031['trip_num'] != $fitosanitario['numero_viaje']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo número de viaje.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo número de viaje.';
				break;
			}
			
			if($this->f031['dfct_slz_prcg_inf'] != $fitosanitario['tratamiento_realizado']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo tratamiento realizado.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo tratamiento realizado.';
				break;
			}
			
			if($this->f031['dfct_slz_prcg_durt'] != $fitosanitario['duracion_tratamiento']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo duración de tratamiento.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo duración de tratamiento.';
				break;
			}
			
			$temperatura = (isset($this->f031['dfct_slz_prcg_tp'])? $this->f031['dfct_slz_prcg_tp'] : 0);
			
			if($temperatura != $fitosanitario['temperatura_tratamiento']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo temperatura de tratamiento.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo temperatura de tratamiento.';
				break;
			}
					
			if(date('j/n/Y',strtotime($this->f031['dfct_slz_prcg_de'])) != date('j/n/Y',strtotime($fitosanitario['fecha_tratamiento']))){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo fecha de tratamiento.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo fecha de tratamiento.';
				break;
			}

			if($this->f031['dfct_slz_prcg_chm_prdt_nm'] != $fitosanitario['quimico_tratamiento']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo nombre de producto quimico de tratamiento.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo nombre de producto quimico de tratamiento.';
				break;
			}
			
			if($this->f031['dfct_slz_prcg_cct'] != $fitosanitario['concentracion_producto']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo concentración de tratamiento.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo concentración de tratamiento.';
				break;
			}
			
			if($this->f031['addt_inf'] != $fitosanitario['observacion_operador']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo información adicional.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo información adicional.';
				break;
			}
			
			//Obtiene país de embarque
			$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['spm_ntn_cd']); //Validación del pais de embarque
			$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
			
			
			if($codigoPaisEmbarque['id_localizacion'] != $fitosanitario['id_pais_embarque']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo país de embarque.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar el campo país de embarque.';
				break;
			}
			
			$qCodigoProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['isp_plc_prvhc_cd']); //Validación de la provincia de inspeccion
			$codigoProvincia = pg_fetch_assoc($qCodigoProvincia);
			
			if($codigoProvincia['nombre'] != $fitosanitario['lugar_inspeccion']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo lugar de inspección.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo lugar de inspección.';
				break;
			}
			
			if($this->f031['dfct_slz_prcg_tp_ut'] != $fitosanitario['unidad_temperatura']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo unidad de temperatura de tratamiento.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo unidad de temperatura de tratamiento.';
				break;
			}
			
			if($this->f031['dclr_idt_no'] != $fitosanitario['identificador_solicitante']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo número de identificación del solicitante.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo número de identificación del solicitante.';
				break;
			}
			
			if($this->f031['prdt_orgn_fg'] != $fitosanitario['producto_organico']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo producto orgánico.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo producto orgánico.';
				break;
			}
			
			if($this->f031['prdt_orgn_cert'] != $fitosanitario['numero_producto_organico']){
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar el campo número producto orgánico.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] = 'No se permite modificar el campo número producto orgánico.';
				break;
			}
			
			if($this->f031['trsp_use_fg'] == 'S'){
				$transito = true;
				
				$numeroTransito = $controladorFitosanitario->listarFitoExportacionTransito($conexionGUIA, $fitosanitario['id_fito_exportacion']);
				
				if(count($numeroTransito) != count($this->f031tr)){
					$transito = false;
					$certificadoImpValido = false;
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la cantidad de registros ingresados en la sección transito.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la cantidad de registros ingresados en la sección transito.';
					break;
				}
				
			}
			
			$numeroProducto = $controladorFitosanitario->listarProductosFitosanitarios($conexionGUIA, $fitosanitario['id_fito_exportacion']);
			
			if(pg_num_rows($numeroProducto) != count($this->f031pd)){
				$transito = false;
				$certificadoImpValido = false;
				$solicitudEsValida = false;
				echo IN_MSG. 'No se permite modificar la cantidad de registros ingresados en la sección productos.';
				$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
				$resultado[1] =  'No se permite modificar la cantidad de registros ingresados en la sección productos.';
				break;
			}
			
		}
		
		if($transito){
			for($k = 0; $k < count($this->f031tr); $k++){
				
				$paisTransito = pg_fetch_assoc($controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031tr[$k]['trsp_cntry_cd'])); //Validación del pais de transito
				$codigoPuertoTransito = pg_fetch_assoc($controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031tr[$k]['spm_port_cd'])); //Validación del puerto de transito
				
				$transito = $controladorFitosanitario->buscarFitosanitarioTransporte($conexionGUIA, $fitosanitario['id_fito_exportacion'], $paisTransito['id_localizacion'], $codigoPuertoTransito['id_puerto'], $this->f031tr[$k]['trsp_via_nm']);
				
				if(pg_num_rows($transito) == 0){
					$certificadoImpValido = false;
					$solicitudEsValida = false;
					echo IN_MSG. 'El pais de transito  '.$this->f031tr[$k]['trsp_cntry_nm'].', puerto de transito '.$this->f031tr[$k]['spm_port_cd'].' y medio de transporte '.$this->f031tr[$k]['trsp_via_nm'].' no se encuentra registrado en el permiso fitosanitario. ';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'El pais de transito  '.$this->f031tr[$k]['trsp_cntry_nm'].', puerto de transito '.$this->f031tr[$k]['spm_port_cd'].' y medio de transporte '.$this->f031tr[$k]['trsp_via_nm'].' no se encuentra registrado en el permiso fitosanitario. ';
					break;
				}
			}
			
		}
		
		if($certificadoImpValido){
			for ($j = 0; $j < count ($this->f031pd); $j++) {
				
				$partidaArancelariaVUE = $this->f031pd[$j]['hc'];
				$codigoProductoVUE = $this->f031pd[$j]['prdt_cd'];
				
				//Busca el id del producto en la base de GUIA
				
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
				$producto = pg_fetch_assoc($qProductoGUIA);
				
				$qProductoFitosanitario = $controladorFitosanitario->buscarFitosanitarioProducto($conexionGUIA, $fitosanitario['id_fito_exportacion'], $producto['id_producto']);
				
				if( pg_num_rows($qProductoFitosanitario) == 0){
						$solicitudEsValida = false;
						echo IN_MSG. 'El producto '.$this->f031pd[$j]['prdt_nm'].' no se encuentra registrado en el permiso fitosanitaria de exportación. ';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'El producto '.$this->f031pd[$j]['prdt_nm'].' no se encuentra registrado en el permiso fitosanitaria de exportación. ';
						break;
				}
				
				$prodcutoFitosanitario = pg_fetch_assoc($qProductoFitosanitario);
				
				if($this->f031pd[$j]['expr_idt_no'] != $prodcutoFitosanitario['identificador_operador']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo número identificación exportador.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el campo número identificación exportador.';
					break;
				}	
				
				if($this->f031pd[$j]['pkgs_no'] != $prodcutoFitosanitario['numero_bultos']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo número de bultos.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el campo número de bultos.';
					break;
				}
				
				if($this->f031pd[$j]['pkgs_ut'] != $prodcutoFitosanitario['unidad_bultos']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo unidad de bulto.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el campo unidad de bulto.';
					break;
				}
				
				if($this->f031pd[$j]['prdt_qt'] != $prodcutoFitosanitario['cantidad_producto']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo cantidad de producto.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el campo cantidad de producto.';
					break;
				}
				
				if($this->f031pd[$j]['prdt_qt_ut'] != $prodcutoFitosanitario['unidad_cantidad_producto']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo unidad cantidad de producto.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el campo unidad cantidad de producto.';
					break;
				}
				
				if($this->f031pd[$j]['prdt_per_exp'] != $prodcutoFitosanitario['permiso_musaceas']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo permiso musaceas.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el campo permiso musaceas.';
					break;
				}

			}
		}
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
		
	}

	public function insertarDatosEnGUIA(){
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorFitosanitario = new ControladorFitosanitario();
		
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		$transito = false;
		
		$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['org_ntn_cd']); //Pais de origen
		$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
		
		
		$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['dst_ntn_cd']); //Pais de destino
		$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
		
		//Obtiene puerto de destino 			
		$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031['dst_port_cd']); //Puerto de destino
		$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
	
		//Obtiene país de embarque
		$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['spm_ntn_cd']); //Pais de embarque
		$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
			
		//Obtiene puerto de embarque
		//TODO: Agregar provincia en tabla de puertos.
		
		$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031['spm_port_cd']); //Puerto de embarque
		$codigoPueroEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
		
		//Obtiene provincia		
		$qCodigoProvincia = $controladorCatalogos->obtenerNombreLocalizacion($conexionGUIA, $codigoPueroEmbarque['id_provincia']); //Provincia de inspeccion
		$codigoProvincia = pg_fetch_assoc($qCodigoProvincia);
		
		$qCodigoProvinciaInspeccion = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031['isp_plc_prvhc_cd']); //Validación de la provincia de inspeccion
		$codigoProvinciaInspeccion = pg_fetch_assoc($qCodigoProvinciaInspeccion);
				
		echo OUT_MSG . 'Generación de codigo interno deL Fitosanitario.';
		
		
		$idFito = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $this->f031['req_no']);
		
		//Fecha de tratamiento
		$fechaTratamiento = (isset($this->f031['dfct_slz_prcg_de'])? "'".$this->f031['dfct_slz_prcg_de']."'" : 'NULL');
		
		//Temperatura
		$temperatura = (isset($this->f031['dfct_slz_prcg_tp'])? $this->f031['dfct_slz_prcg_tp'] : 0);
		
		//Información adicional
		$informacionAdicional = str_replace("'", "''", $this->f031['addt_inf']);
		
		//Nombre de importador
		$nombreImportador = str_replace("'", "''", $this->f031['csgn_nm']);
		
		//Direccion de importador
		$direccionImportador = str_replace("'", "''", $this->f031['csgn_ad']);
		
		//Campo de marcas
		$marcas = str_replace("'", "''", $this->f031['bdnm']);
		
		if(pg_num_rows($idFito) == 0){
			$idFito = $controladorFitosanitario->guardarFitoExportacion($conexionGUIA, $nombreImportador, $direccionImportador, $codigoPaisDestino['id_localizacion'], $codigoPaisDestino['nombre'], 
																		$this->f031['agc_nm'], $marcas, $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'], $codigoPueroEmbarque['id_puerto'], 
																		$codigoPueroEmbarque['nombre_puerto'], $this->f031['dcd_trsp_way_nm'], $this->f031['dtf_spm_de'], $this->f031['trip_num'], 
																		$this->f031['dfct_slz_prcg_inf'], $this->f031['dfct_slz_prcg_durt'], $temperatura, $fechaTratamiento, 
																		$this->f031['dfct_slz_prcg_chm_prdt_nm'], $this->f031['dfct_slz_prcg_cct'], $codigoProvincia['id_localizacion'], $codigoProvincia['nombre'], 
																		$informacionAdicional, $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'], 
																		$codigoPaisOrigen['id_localizacion'], $codigoPaisOrigen['nombre'], $this->f031['isp_infm_no'],$codigoProvinciaInspeccion['nombre'],$this->f031['dfct_slz_prcg_tp_ut'],
																		$this->f031['dclr_idt_no'],$this->f031['prdt_orgn_fg'],$this->f031['prdt_orgn_cert'],$this->f031['req_no']);
		}else{
		//actualizar registro
		
			$controladorFitosanitario->actualizarFito($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'), $nombreImportador, $direccionImportador, $codigoPaisDestino['id_localizacion'], $codigoPaisDestino['nombre'], 
													$this->f031['agc_nm'], $marcas, $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'], $codigoPueroEmbarque['id_puerto'], $codigoPueroEmbarque['nombre_puerto'], 
													$this->f031['dcd_trsp_way_nm'], $this->f031['dtf_spm_de'], $this->f031['trip_num'], $this->f031['dfct_slz_prcg_inf'], $this->f031['dfct_slz_prcg_durt'], 
													$temperatura, $fechaTratamiento, $this->f031['dfct_slz_prcg_chm_prdt_nm'], $this->f031['dfct_slz_prcg_cct'], 
													$codigoProvincia['id_localizacion'], $codigoProvincia['nombre'],  $informacionAdicional, $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'], 
													$codigoPaisOrigen['id_localizacion'], $codigoPaisOrigen['nombre'], $this->f031['isp_infm_no'] , $codigoProvinciaInspeccion['nombre'],$this->f031['dfct_slz_prcg_tp_ut'], 
													$this->f031['dclr_idt_no'],'enviado',$this->f031['prdt_orgn_fg'],$this->f031['prdt_orgn_cert']);
		
			$controladorFitosanitario->eliminarProductosFito($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'));
		}
		
		echo OUT_MSG . 'Datos de cabecera de Fitosanitario insertados';
		
		
		for ($i = 0; $i < count ($this->f031pd); $i++) {
						
			parent::asignarUsuarioGUIA($this->f031pd[$i]['expr_idt_no']);
			
			parent::asignarPerfilGUIA($this->f031pd[$i]['expr_idt_no'], 'Usuario externo');
			parent::asignarPerfilGUIA($this->f031pd[$i]['expr_idt_no'], 'Operadores de Comercio Exterior');
			parent::asignarPerfilGUIA($this->f031pd[$i]['expr_idt_no'], 'Operadores');
			
			parent::asignarAplicacionGUIA( $this->f031pd[$i]['expr_idt_no'], 'PRG_FITOS');
			parent::asignarAplicacionGUIA( $this->f031pd[$i]['expr_idt_no'], 'PRG_REGISTROOPER');
			
			parent::ingresarRegistroOperador($this->f031pd[$i]['expr_idt_no'], $this->f031['expr_nm']);
		
			$partidaArancelariaVUE = $this->f031pd[$i]['hc'];
			$codigoProductoVUE = $this->f031pd[$i]['prdt_cd'];
			
			$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
			$producto = pg_fetch_assoc($qProductoGUIA);
			
			$controladorFitosanitario -> guardarFitoProductos($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'), $this->f031pd[$i]['expr_idt_no'], 
																$producto['id_producto'], $producto['nombre_comun'], $this->f031pd[$i]['pkgs_no'], $this->f031pd[$i]['pkgs_ut'],
																$this->f031pd[$i]['prdt_qt'], $this->f031pd[$i]['prdt_qt_ut'], $this->f031pd[$i]['prdt_per_exp'], 
																$partidaArancelariaVUE,$codigoProductoVUE);
		}
		
		if($this->f031['trsp_use_fg']== 'S'){
			$controladorFitosanitario->eliminaTransitoFitosanitario($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'));
			$transito = true;
		}else{
			$controladorFitosanitario->eliminaTransitoFitosanitario($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'));
			$transito = false;
		}
		
		if($transito){
			for($j = 0; $j < count($this->f031tr); $j++){
				
				//Obtiene país de transito
				$qCodigoPaisTransito = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f031tr[$j]['trsp_cntry_cd']); //Pais de transito
				$codigoPaisTransito = pg_fetch_assoc($qCodigoPaisTransito);
				
				//Obtiene puerto de transito
				$qCodigoPuertoTransito = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f031tr[$j]['spm_port_cd']); //Puerto de transito
				$codigoPueroTransito = pg_fetch_assoc($qCodigoPuertoTransito);
				
				$controladorFitosanitario->guardarFitoTransito($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'), $codigoPueroTransito['id_puerto'], $codigoPueroTransito['nombre_puerto'], 
																$codigoPaisTransito['id_localizacion'], $codigoPaisTransito['nombre'], $this->f031tr[$j]['trsp_via_nm']);				
			}	
		}
		
		echo OUT_MSG . 'Datos de detalle Fitosanitario insertados';
		
		return true;
	}

	public function insertarDocumentosAdjuntosGUIA($documentos){
		
		$controladorFitosanitario = new ControladorFitosanitario();
		$conexionGUIA = new Conexion();
		
		$idFito = pg_fetch_result($controladorFitosanitario->buscarFitoVUE($conexionGUIA, $this->f031['req_no']), 0, 'id_fito_exportacion');
		
		$controladorFitosanitario->eliminarArchivosAdjuntos($conexionGUIA, $idFito, $this->f031['req_no']);
		
		if(pg_num_rows($documentos)!= 0){
		
			while($documentosAdjuntos = pg_fetch_assoc($documentos)){
				$ruta= explode('DEV/', $documentosAdjuntos['ruta']);
				//$ruta= explode('PROD/', $documentosAdjuntos['ruta']);
				$controladorFitosanitario ->guardarFitoDocumentos($conexionGUIA, $idFito, $documentosAdjuntos['nombre'], $ruta[1], 'SV', $this->f031['req_no']);
			}
		}else{
			echo OUT_MSG . 'La solicitud Fitosanitaria no posee documentos adjuntos.';
		}
		
		echo OUT_MSG . 'Documentos adjuntos Fitosanitarios insertados';
		
		return true;
	}

	public function actualizarDatosEnGUIA(){
		
		$controladorFitosanitario = new ControladorFitosanitario();
		$controladorCatalogos = new ControladorCatalogos();
		$conexionGUIA = new Conexion();
		
		//Nombre de importador
		$nombreImportador = str_replace("'", "''", $this->f031['csgn_nm']);
		$direccionImportador = str_replace("'", "''", $this->f031['csgn_ad']);
		$puertoDestino = $this->f031['dst_port_cd'];
		//$informacionAdicional = $this->f031['addt_inf'];
		
		$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $puertoDestino); //Puerto de destino
		$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
		
		$idFito = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $this->f031['req_no']);
		
		$controladorFitosanitario->modificarFitosanitario($conexionGUIA, pg_fetch_result($idFito, 0, 'id_fito_exportacion'), $nombreImportador, $direccionImportador, $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto']);
		
		return true;
	}

	public function recaudacionTasa($recaudacionTasas){
		
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorFitosanitario = new ControladorFitosanitario();
		$controladorFinanciero = new ControladorFinanciero();
		$controladorCatalogos = new ControladorCatalogos();
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		$resultado = array();
		
		$datosTasa = pg_fetch_assoc($recaudacionTasas);
		
		$identificadorFito = $this->f031['expr_idt_no'];
		$verificarProceso = false;		
		$idFito = pg_fetch_result($controladorFitosanitario->buscarFitoVUE($conexionGUIA, $this->f031['req_no']),0,'id_fito_exportacion');
		$qfitoExportacionDetalle = $controladorFitosanitario -> listarFitoExportacionDetalle($conexionGUIA, $idFito);
				
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idFito, 'Fitosanitario', 'Financiero');
		$financiero = pg_fetch_assoc($qFinanciero);
		
		if($datosTasa['banco']==''){
			
			$saldoDisponible = pg_fetch_assoc($controladorFinanciero->obtenerMaxSaldo($conexionGUIA, $qfitoExportacionDetalle[0]['identificador'], 'saldoVue'));
			
			if($saldoDisponible['saldo_disponible']>= $datosTasa['monto_recaudado']){			
				$banco = 'saldoVue';
				$tipoProceso = 'comprobanteFactura';
				$idBanco = '0';
				$verificarProceso = true;
				$resultado[0] = SOLICITUD_APROBADA;
				$resultado[1] = 'Continua con proceso de aprobación. ';
			}else{
				$resultado[0] = ERROR_TAREA;
				$resultado[1] = 'Proceso en espera hasta la confirmación de saldo. ';
			}
			
		}else if ($datosTasa['banco'] != ''){
			$codigoBanco = trim($datosTasa['banco'], '0');					
			$datosBanco = pg_fetch_assoc($controladorCatalogos->obtenerDatosBancarioPorCodigoVue($conexionGUIA, $codigoBanco));
			$banco = $datosBanco['nombre'];
			$idBanco = $datosBanco['id_banco'];
			$tipoProceso = 'factura';
			$verificarProceso = true;
			$resultado[0] = SOLICITUD_APROBADA;
			$resultado[1] = 'Continua con proceso de aprobación. ';
		}else{
			$resultado[0] = ERROR_DE_VALIDACION;
			$resultado[1] = 'No se reconoce el proceso de verificación de pago. ';
		}
		
		if($verificarProceso){

			if($financiero['monto'] == $datosTasa['monto_recaudado']){
			
				$controladorRevisionSolicitudesVUE->guardarInspeccionFinanciero($conexionGUIA, $financiero['id_financiero'], $financiero['identificador_inspector'], 'aprobado', $datosTasa['fecha_recaudacion'], $idBanco, $datosTasa['monto_recaudado'], $banco, $datosTasa['numero_orden_vue'], $datosTasa['numero_orden_vue']);
				$controladorFinanciero->actualizarNumeroOrdenSolicitudVue($conexionGUIA, $idFito, $financiero['id_grupo'], 'Fitosanitario', $datosTasa['numero_orden_vue']);
					
				echo OUT_MSG . 'Actualización de datos de tasas de recaudación';
				
				//Asigna el resultado de revisión de pago de solicitud de importacion
				$controladorFitosanitario->enviarFito($conexionGUIA, $idFito, 'aprobado');
				//$controladorFitosanitario->enviarFito($conexionGUIA, $idFito, 'verificacion');
				//Asignar estado a productos de solicitud
				$controladorFitosanitario->evaluarProductosFito($conexionGUIA, $idFito, 'aprobado');
				//$controladorFitosanitario->evaluarProductosFito($conexionGUIA, $idFito, 'verificacion');
				//Asignar fecha de vigencia de solicitud
				$controladorFitosanitario->enviarFechaVigenciaFito($conexionGUIA, $idFito);
				
				$cfa->actualizarTipoProcesoFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f031['req_no'], $tipoProceso);
				
				echo OUT_MSG . 'Solicitud Fitosanitaria aprobada.';
			
			}else{
				$resultado = array();
				$resultado[0] = ERROR_DE_VALIDACION;
				$resultado[1] = 'Error en diferenciación de valores cancelados. ';
			}
		}
		
		return $resultado;
	}

	public function cancelar(){
		
		$controladorFitosanitario = new ControladorFitosanitario();
		$conexionGUIA = new Conexion();
		
		$idVue = $this->f031['req_no'];
		
		$idFitosanitario = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $idVue);
		
		if(pg_num_rows($idFitosanitario)!=0){
			$controladorFitosanitario->enviarFito($conexionGUIA, pg_fetch_result($idFitosanitario, 0, 'id_fito_exportacion'), 'cancelado');
			
		//	for ($i = 0; $i < count ($this->f024pd); $i++) {
				$controladorFitosanitario->enviarFitosanitarioProductos($conexionGUIA, pg_fetch_result($idFitosanitario, 0, 'id_fito_exportacion'), 'cancelado');
		//	}
		}
		
		echo OUT_MSG. 'Solicitud cancelada.';
		
		return true;
	}

	public function anular(){
		
		$controladorFitosanitario = new ControladorFitosanitario();
		$conexionGUIA = new Conexion();
		
		$idVue = $this->f031['req_no'];
		
		$idFitosanitario = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $idVue);
		
		$controladorFitosanitario->enviarFito($conexionGUIA, pg_fetch_result($idFitosanitario, 0, 'id_fito_exportacion'), 'anulado');
		
		
		//for ($i = 0; $i < count ($this->f031pd); $i++) {
			$controladorFitosanitario->enviarFitosanitarioProductos($conexionGUIA, pg_fetch_result($idFitosanitario, 0, 'id_fito_exportacion'), 'anulado');
		//}
		
		echo OUT_MSG. 'Solicitud anulada.';
		
		return true;
	}
	
	public function reversoSolicitud(){
		
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorFitosanitario = new ControladorFitosanitario();
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		$idFitosanitario = $controladorFitosanitario->buscarFitoVUE($conexionGUIA, $this->f031['req_no']);
		$fitosanotario = pg_fetch_assoc($idFitosanitario);
		
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitudReverso($conexionGUIA, $fitosanotario['id_fito_exportacion'], 'Fitosanitario', 'Financiero');
		$financiero = pg_fetch_assoc($qFinanciero);
		
		$controladorRevisionSolicitudesVUE->actualizarInspeccionFinancieroMontoRecaudado($conexionGUIA, $financiero['id_financiero']);
		
		$controladorFitosanitario->enviarFito($conexionGUIA, $fitosanotario['id_fito_exportacion'], 'reverso');
		
		$cfa->actualizarEstadoFinancieroAutomaticoCabeceraPorIdVue($conexionGUIA, $this->f031['req_no'], 'Reverso');
		
		return true;
	}
	
	public function generarFacturaProcesoAutomatico(){
		
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		echo OUT_MSG . 'Actualización de datos de tasas de recaudación';
		
		$cfa->actualizarEstadoFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f031['req_no'], 'Por atender');
		$cfa->actualizarFechaFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f031['req_no']);
		echo OUT_MSG . 'Solicitud Fitosanitaria enviada a verificación de pago.';
		
		return true;
	}

}

/***************************************************************************************************************************/
/***************************************************************************************************************************/

class CLV extends FormularioVUE{

	public $f047;
	public $f047cps = array();

	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){

		/* $camposObligatorios = array(
		 'cabecera' => array(
		 		'IMPR_NM',
		 		'IMPR_RPST_NM',
		 		'IMP_PRVHC_NM',
		 		'IMPR_CUTY_NM',
		 		'IMPR_PRQI_NM',
		 		'IMPR_AD',
		 		'IMPR_TEL_NO',
		 		'IMPR_CEL_NO',
		 		'IMPR_EM'),
					
				'productos' => array(
						'REQ_NO',
						'HC',
						'PRDT_NM',
						'ORG_NTN_CD',
						'PRDT_CD',
						'AGRCD_PRDT_CD',
						'PRDT_BUSS_ACT_CD',
						'PRDT_BUSS_ACT_NM',
						'PRDT_TYPE_NM',
						'PRDT_STN')
		); */

		//Trayendo los datos de cabecera del formulario 101-024

		$this-> f047 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_047
				WHERE REQ_NO = '$numeroDeSolicitud'"));
			
		//Trayendo los datos de detalle del formulario 101-024

		$c_f047cps = $coneccionVUE ->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_047_cps
				WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($fila = pg_fetch_assoc($c_f047cps)){
			$this-> f047cps[] = $fila;
		}

		parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
	}

	public function validarDatosFormulario(){
		
		echo  PRO_MSG. 'validando formulario 101-047';
		
		$solicitudEsValida = true;
		$resultado = array();
		
		$conexionGUIA = new Conexion();
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorCLV = new ControladorClv();
		
		
		for ($i = 0; $i < count ($this->f047['req_no']); $i++) {
			
			$partidaArancelariaVUE = $this->f047['hc'];
			$codigoProductoVUE = $this->f047['prdt_cd'];
			
			$codigoTipoSolicitud = $this->f047['tip_prod_code'];
			
			$area = ($codigoTipoSolicitud == '01'?'IAP':($codigoTipoSolicitud == '02'?'IAV':'No definido'));
			
			//validar el producto : partida y codigo vue
			$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
			$producto = pg_fetch_assoc($qProductoGUIA);
			
			if(pg_num_rows($qProductoGUIA) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. "El producto  ".$this->f047['prdt_nm']."  no se encuentra registrado en Agrocalidad";
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = "El producto  ".$this->f047['prdt_nm']."  no se encuentra registrado en Agrocalidad";
				break;
			}
			
			//validar el tipo de producto pertenesca solo a Inocuidad
			
			if(($producto['id_area'] == 'SA') || ($producto['id_area'] == 'SV')){
				$solicitudEsValida = false;
				echo IN_MSG. "El producto  ".$this->f047['prdt_nm']."  no se encuentra registrado en Direción de Inocuidad de los alimentos";
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = "El producto  ".$this->f047['prdt_nm']."  no se encuentra registrado en Direción de Inocuidad de los alimentos";
				break;
			}

			//Valida el tipo de producto a la area que pertenecea a IAP/IAV
			
			if($producto['id_area'] != $area){
				$solicitudEsValida = false;
				echo IN_MSG. "El producto  ".$this->f047['prdt_nm']."  no corresponde al tipo de solicitud seleccionado ";
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = "El producto  ".$this->f047['prdt_nm']."  no corresponde al tipo de solicitud seleccionado ";
				break;
			}
						
			$qProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f047['rgs_nomn_prvhc_cd']);
			if(pg_num_rows($qProvincia) == 0 ){
				$solicitudEsValida = false;
					echo IN_MSG. "La provincia : ".$this->f047['rgs_nomn_prvhc_nm']." no se encuentra registrado";
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = "La provincia : ".$this->f047['rgs_nomn_prvhc_nm']." no se encuentra registrado";
					break;
			}
			
			$qCanton = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f047['rgs_nomn_cuty_cd']);
			if(pg_num_rows($qCanton) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. "El canton : ".$this->f047['rgs_nomn_cuty_nm']." no se encuentra registrado";
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = "El canton : ".$this->f047['rgs_nomn_cuty_nm']." no se encuentra registrado";
				break;
			}
			
			$qParroquia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f047['rgs_nomn_prqi_cd']);
			if(pg_num_rows($qParroquia) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. "La parroquia : ".$this->f047['rgs_nomn_prqi_nm']." no se encuentra registrado";
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = "La parroquia : ".$this->f047['rgs_nomn_prqi_nm']." no se encuentra registrado";
				break;
			}
			
			$tipoOperacion = ($area == 'IAP'?'Formulador': 'Fabricante/Formulador/Elaborador por contrato/Exportador');
			
			$idActividad  = $controladorCatalogos -> buscarIdOperacion($conexionGUIA, $area, $tipoOperacion);
			
			$operador = $controladorRegistroOperador->buscarOperacionAreaProducto($conexionGUIA, pg_fetch_result($idActividad, 0, 'id_tipo_operacion'), $producto['id_producto']);

			if(pg_num_rows($operador) == 0 ){
				$solicitudEsValida = false;
				echo IN_MSG. "No existe un ".($area == 'IAP'?'Formulador': 'Fabricante/Formulador/Elaborador por contrato/Exportador')." registrado para el producto " .$this->f047['prdt_nm'];
				$resultado[0] = SUBSANACION_REQUERIDA;
				$resultado[1] = "No existe un ".($area == 'IAP'?'Formulador': 'Fabricante/Formulador/Elaborador por contrato/Exportador')." registrado para el producto " .$this->f047['prdt_nm'];
				break;
			}
		}    	
				

		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
	}

	public function validarActualizacionDeDatos(){
		
		echo  PRO_MSG. 'validando actualizacion de formulario ';
		$resultado = array();
		
		$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
		$resultado[1] = 'No se permite modificar los datos del certificado de libre venta.';
		
		return $resultado;
	}

	public function insertarDatosEnGUIA(){
		
		$conexionGUIA = new Conexion();
		$controladorCLV = new ControladorClv();	
		$controladorUsuario = new ControladorUsuarios();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorAplicaciones = new ControladorAplicaciones();
		$controladorRegistroOperador = new ControladorRegistroOperador();
		
		
		
		if ($this->f047['rgs_nomn_idt_no_type_cd']=='001')
			$tipo_id = 'Persona natural';
		else
			$tipo_id = 'Persona juridica';
		
		$localizacionGUIA = $controladorCatalogos->buscaLocalizacionVue($conexionGUIA, $this->f047['rgs_nomn_prvhc_cd'],$this->f047['rgs_nomn_cuty_cd'],$this->f047['rgs_nomn_prqi_cd']);
		while ($fila = pg_fetch_assoc($localizacionGUIA)){
							$codigoProvincia = $fila['cod_provincia'];
							$provincia = $fila['provincia'];
							$codigoCanton  = $fila['cod_canton'];
							$canton  = $fila['canton'];
							$codigoParroquia  = $fila['cod_parroquia'];
							$parroquia  = $fila['parroquia'];
				}
				
		$datosT = array(
						'tipo_identificacion' => $tipo_id,
						'identificador_titulares' => $this->f047['rgs_nomn_idt_no'],
						'nombre_titular' => $this->f047['rgs_nomn_nm'],
						'apellido_titular' => '',
						'representante' => $this->f047['rgs_nomn_rpst_nm'],
						'id_provincia' => $codigoProvincia,
						'provincia' => $provincia,
						'id_canton' => $codigoCanton,
						'canton' => $canton,
						'id_parroquia' => $codigoParroquia,
						'parroquia' => $parroquia,
						'direccion' => $this->f047['rgs_nomn_ad'],
						'telefono' => $this->f047['rgs_nomn_tel_no'],
						'celular' => '',
						'correo' =>$this->f047['rgs_nomn_em']
				);
				
		
		$operador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $datosT['identificador_titulares']); //Metodo que verifica si existe el operador en Agrocalidad
		
		if(pg_num_rows($operador) == 0 ){ // Busqueda del producto en base de datos GUIA.
			
			echo IN_MSG. 'El operador no se se encuentra registrado y se procede a registrar';
			
			//Creacion del registro de operador
			
			$controladorRegistroOperador-> guardarRegistroOperador($conexionGUIA, $datosT['tipo_identificacion'], $datosT['identificador_titulares'], $datosT['nombre_titular'],
																	$datosT['nombre_titular'], $datosT['nombre_titular'],'','',	$datosT['provincia'], $datosT['canton'],
																	$datosT['parroquia'], $datosT['direccion'], $datosT['telefono'], '', '',
																	'', '', $datosT['correo'], md5($datosT['identificador_titulares']), $this->f047['req_no']);
			
			echo IN_MSG. 'Creación de la cuenta en el sistema GUIA.';
			
			//Asignar perfil a usuario
		}
		
		parent::asignarUsuarioGUIA($datosT['identificador_titulares']);
		parent::asignarPerfilGUIA($datosT['identificador_titulares'], 'Usuario externo');
		parent::asignarPerfilGUIA($datosT['identificador_titulares'], 'Operadores de Comercio Exterior');
		parent::asignarPerfilGUIA($datosT['identificador_titulares'], 'Operadores');
		
		//Asignacion de la aplicacion de "Certificado de libre venta" al operador
		
		parent::asignarAplicacionGUIA($datosT['identificador_titulares'], 'PRG_LIBREVENTA');
		parent::asignarAplicacionGUIA( $datosT['identificador_titulares'], 'PRG_REGISTROOPER');
		
		echo OUT_MSG . 'Datos de titular del clv insertados';
		
		//Crear código de identificación de solicitud para agrupar productos
		$res = $controladorCLV->generarNumeroCertificado($conexionGUIA, '%'.$datosT['identificador_titulares'].'%');
		$solicitud = pg_fetch_assoc($res);
		$tmp= explode("-", $solicitud['numero']);
		$incremento = end($tmp)+1;
			
		$codigoCertificado = 'CLV-'.$datosT['identificador_titulares'].'-'.str_pad($incremento, 7, "0", STR_PAD_LEFT);
		
		echo OUT_MSG . 'Generación de codigo interno de CLV';
		
		$codigoTipoSolicitud = $this->f047['tip_prod_code'];
		$area = ($codigoTipoSolicitud == '01'?'IAP':($codigoTipoSolicitud == '02'?'IAV':'No definido'));
		
		$partidaArancelariaVUE = $this->f047['hc'];
		$codigoProductoVUE = $this->f047['prdt_cd'];
		$codigoTipoSolicitud = $this->f047['tip_prod_code'];
			
		//validar el producto : partida y codigo vue
		$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
		$producto = pg_fetch_assoc($qProductoGUIA);
			
			
		if ($area=='IAP'){
			$ctipo_datos_certificado = 'Formulador';
		} else if ($area = 'IAV'){
			$ctipo_datos_certificado = 'Fabricante/Formulador/Elaborador por contrato/Exportador';
		}
		
		$qIdCLV = $controladorCLV->buscarClvVUE($conexionGUIA, $this->f047['req_no']);
		
		if(pg_num_rows($qIdCLV) == 0){
			
			$qIdCLV= $controladorCLV->guardarCertificadoProducto($conexionGUIA,$datosT['identificador_titulares'],$area, $ctipo_datos_certificado,$codigoCertificado,
																$producto['id_producto'],$producto['nombre_comun'],'enviado', $this->f047['prdt_fml_desc'] ,$this->f047['req_no'], $partidaArancelariaVUE, $codigoProductoVUE);
		}else{
			
			$controladorCLV->actualizarClv($conexionGUIA, pg_fetch_result($qIdCLV, 0, 'id_clv'), $datosT['identificador_titulares'], $area, $ctipo_datos_certificado, 
											$producto['id_producto'],$producto['nombre_comun'], 'enviado',$partidaArancelariaVUE, $codigoProductoVUE);
			
			$controladorCLV->eliminarDetalleProductos($conexionGUIA, pg_fetch_result($qIdCLV, 0, 'id_clv'));
		}
		
		
		
		$idCLV = pg_fetch_result($qIdCLV, 0, 'id_clv');
		
		echo OUT_MSG . 'Datos de cabecera de CLV insertados';
		
		
		// Detalle del producto
				
		if ($area=='IAP'){
			for ($i = 0; $i <count($this->f047cps); $i++) {
				$ingrediente_activo = $this->f047cps[$i]['dcd_cps_ing_act_nm'];
				$concentracion = $this->f047cps[$i]['dcd_cps_conce_nm'];
				$unidad = $this->f047cps[$i]['dcd_cps_conce_ut'];
		
				$cProductoDetalle = $controladorCLV->guardarDetalleCertificadoProductoP($conexionGUIA,$idCLV,$ingrediente_activo,$concentracion, $unidad);
			}
		}
		
		echo OUT_MSG . 'Datos de detalle de CLV insertados';
		
		return true;

	}

	public function insertarDocumentosAdjuntosGUIA($documentos){
		
		$controladorClv = new ControladorClv();
		$conexionGUIA = new Conexion();
		
		$identificadorTitular = $this->f047['rgs_nomn_idt_no'];
		$codigoTipoSolicitud = $this->f047['tip_prod_code'];
		
		$idClv = pg_fetch_result($controladorClv->buscarClvVUE($conexionGUIA, $this->f047['req_no']),0,'id_clv');
		
		$area = ($codigoTipoSolicitud == '01'?'IAP':($codigoTipoSolicitud == '02'?'IAV':'No definido'));
		
		$controladorClv->eliminarArchivosAdjuntos($conexionGUIA, $idClv, $this->f047['req_no']);
		
		if(pg_num_rows($documentos)!= 0){
			while($documentosAdjuntos = pg_fetch_assoc($documentos)){
				$ruta= explode('DEV/', $documentosAdjuntos['ruta']);
				//$ruta= explode('PROD/', $documentosAdjuntos['ruta']);
				$controladorClv ->guardarClvArchivos($conexionGUIA, $idClv, $documentosAdjuntos['nombre'], $ruta[1], $area,$this->f047['req_no']);
			}
		}else{
			echo OUT_MSG . 'La solicitud CLV no posee documentos adjuntos.';			
		}
		
		echo OUT_MSG . 'Documentos adjuntos de CLV insertados';
		
		return true;
	}

	public function actualizarDatosEnGUIA(){
		return true;
	}

	public function recaudacionTasa($recaudacionTasas){
		
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorCLV = new ControladorClv();
		$conexionGUIA = new Conexion();
		
		$datosTasa = pg_fetch_assoc($recaudacionTasas);
		
		$identificadorTitular = $this->f047['rgs_nomn_idt_no'];
		
		$idClv = pg_fetch_result($controladorCLV->buscarClvVUE($conexionGUIA, $this->f047['req_no']), 0, 'id_clv');
				
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idClv, 'CLV', 'Financiero');
		$financiero = pg_fetch_assoc($qFinanciero);
		
		
		$controladorRevisionSolicitudesVUE->guardarInspeccionFinanciero($conexionGUIA, $financiero['id_financiero'], $financiero['identificador_inspector'], 'aprobado', null, $datosTasa['banco'], $datosTasa['monto_recaudado'], $datosTasa['fecha_recaudacion'],$datosTasa['banco'], $datosTasa['numero_orden_vue'], $datosTasa['numero_orden_vue']);
		
		echo OUT_MSG . 'Actualización de datos de tasas de recaudación';
		
		//Asigna el resultado de revisión de pago de solicitud de importacion
		//$controladorCLV->enviarClv($conexionGUIA, $idClv, 'aprobado');
		$controladorCLV->enviarClv($conexionGUIA, $idClv, 'verificacion');
		//Asignar estado a productos de solicitud
		//$controladorCLV->evaluarProductosCLV($conexionGUIA, $idClv, 'aprobado');
		$controladorCLV->evaluarProductosCLV($conexionGUIA, $idClv, 'verificacion');
		//Asignar fecha de vigencia de solicitud
		//$controladorCLV->enviarFechaVigenciaCLV($conexionGUIA, $idClv);
		
		echo OUT_MSG . 'Solicitud de CLV enviada a verificación de pago.';
		
		return true;
	}

	public function cancelar(){
		
		$controladorCLV = new ControladorClv();
		$conexionGUIA = new Conexion();
		
		$identificadorTitular = $this->f047['rgs_nomn_idt_no'];
		$idVue = $this->f047['req_no'];
		
		$idClv = $controladorCLV->buscarClvVUE($conexionGUIA, $idVue);
		
		if(pg_num_rows($idClv)!= 0){
			
			$controladorCLV->enviarClv($conexionGUIA, pg_fetch_result($idClv, 0, 'id_clv'), 'cancelado');
			
			//	for ($i = 0; $i < count ($this->f047cps); $i++) {
			$controladorCLV->evaluarProductosCLV($conexionGUIA, pg_fetch_result($idClv, 0, 'id_clv'), 'cancelado');
			//	}
			
		}
		
		echo OUT_MSG. 'Solicitud cancelada.';
		
		return true;
	}

	public function anular(){
		
		$controladorCLV = new ControladorClv();
		$conexionGUIA = new Conexion();
		
		$identificadorTitular = $this->f047['rgs_nomn_idt_no'];
		$idVue = $this->f047['req_no'];
		
		$idClv = $controladorCLV->buscarClvVUE($conexionGUIA, $idVue);
		
		if(pg_num_rows($idClv)!= 0){
		
		//for ($i = 0; $i < count ($this->f047cps); $i++) {
			
			$controladorCLV->enviarClv($conexionGUIA, pg_fetch_result($idClv, 0, 'id_clv'), 'anulado');
			
			$controladorCLV->evaluarProductosCLV($conexionGUIA, pg_fetch_result($idClv, 0, 'id_clv'), 'anulado');
		}
		
		echo OUT_MSG. 'Solicitud anulada.';
		
		return true;
	}
	
	public function reversoSolicitud(){
		return true;
	}
	
	public function generarFacturaProcesoAutomatico(){
		return true;
	}

}

/*********************************************************************************************************************************************************************************************
 *********************************************************************************************************************************************************************************************/


class FitosanitarioExportacion extends FormularioVUE{

	public $f034;
	public $f034ex = array();
	public $f034tr = array();

	public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){

		/* $camposObligatorios = array(
		 'cabecera' => array(
		 		'IMPR_NM',
		 		'IMPR_RPST_NM',
		 		'IMP_PRVHC_NM',
		 		'IMPR_CUTY_NM',
		 		'IMPR_PRQI_NM',
		 		'IMPR_AD',
		 		'IMPR_TEL_NO',
		 		'IMPR_CEL_NO',
		 		'IMPR_EM'),
					
				'productos' => array(
						'REQ_NO',
						'HC',
						'PRDT_NM',
						'ORG_NTN_CD',
						'PRDT_CD',
						'AGRCD_PRDT_CD',
						'PRDT_BUSS_ACT_CD',
						'PRDT_BUSS_ACT_NM',
						'PRDT_TYPE_NM',
						'PRDT_STN')
		); */
		
		$camposObligatorios = array(
		 'cabecera' => array('trsp_use_fg')
		); 

		//Trayendo los datos de cabecera del formulario 101-034

		$this-> f034 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta(" SELECT *
																		FROM vue_gateway.tn_agr_034
																		WHERE REQ_NO = '$numeroDeSolicitud'"));
			
		//Trayendo los datos de detalle del formulario 101-034

		$c_f034ex = $coneccionVUE ->ejecutarConsulta(" SELECT *
														FROM vue_gateway.tn_agr_034_ex
														WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($fila = pg_fetch_assoc($c_f034ex)){
			$this-> f034ex[] = $fila;
		}
			

		$c_f034tr = $coneccionVUE->ejecutarConsulta(" SELECT *
														FROM vue_gateway.tn_agr_034_tr
														WHERE REQ_NO = '$numeroDeSolicitud'");

		while ($transito = pg_fetch_assoc($c_f034tr)){
			$this-> f034tr[] = $transito;
		}

		parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
	}

	public function validarDatosFormulario(){
		
		echo  PRO_MSG. 'validando formulario 101-034';
		$resultado = array();
		$solicitudEsValida = true;
		$validacionExportador = true;
		$validacionProducto = true;
		$validacionArea = true;
		$poseeTransito = false;
		$provinciaArea = array();
		$contadorArea = 0;
		
		$conexionGUIA = new Conexion();
		$controladorVUE = new ControladorVUE();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorFinanciero = new ControladorFinanciero();
		$controladorRequisitos = new ControladorRequisitos();
		$controladorProtocolos = new ControladorProtocolos(); 
		$controladorRegistroOperador = new ControladorRegistroOperador();
		
		//$comprobacionDatosObligatorios = parent::validarCamposObligatorios($this->f034,'cabecera');
				
		/*if(!$comprobacionDatosObligatorios){
			$solicitudEsValida = false;
			echo IN_MSG. 'Por favor revisar todos los campos obligatorios para el formulario.';
			$resultado[0] = SUBSANACION_REQUERIDA;
			$resultado[1] = 'Por favor revisar todos los campos obligatorios para el formulario.';
		}*/
		
		if($solicitudEsValida){
			//Obtiene id de operación de exportación para sanidad vegetal
			$qOperacion = $controladorCatalogos->buscarIdOperacion($conexionGUIA, 'SV', 'Exportador'); //Obtiene id de exportacion para SV
			$operacion = pg_fetch_assoc($qOperacion);
					
			$idVue = $this->f034['req_no'];
			
			for ($i = 0; $i < count ($this->f034['req_no']); $i++) {
				
				$tipoCertificado = $this->f034['sps_idt_type_cd'];
				
				//Validación ciudad solicitud
				$codigoCiudadVUE = $this->f034['req_city_cd'];
				$nombreCiudadVUE = $this->f034['req_city_nm'];
				$qCodigoCiudad = $controladorCatalogos->obtenerCodigoLocalizacionImportacion($conexionGUIA, $codigoCiudadVUE);
				$codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
				
				//Validación de ciudad de solicitud de certificado
				if( pg_num_rows($qCodigoCiudad) == 0 ){ 
					$solicitudEsValida = false;
					$certificadoImpValido = false;
					echo IN_MSG. 'La ciudad '.$nombreCiudadVUE.' no se encuentra registrado en Agrocalidad.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La ciudad '.$nombreCiudadVUE.' no se encuentra registrado en Agrocalidad.';
					break;
				}
				
				//Obtiene país de origen
				$fitosanitarioCodigoPaisOrigen = $this->f034['org_ntn_cd'];
				$fitosanitarioNombrePaisOrigen = $this->f034['org_ntn_nm'];
				$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA,$fitosanitarioCodigoPaisOrigen); //Validación del pais de origen
				$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
				
				if(pg_num_rows($qCodigoPaisOrigen) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'El pais de origen  '.$fitosanitarioNombrePaisOrigen.' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El pais de origen '.$fitosanitarioNombrePaisOrigen.' no se encuentra registrado en Agrocalidad. ';
					break;
				}
				
				if(strtoupper($fitosanitarioNombrePaisOrigen) == 'ECUADOR'){
					//Validación de provincia de origen
					$codigoProvinciaOrigenVUE =  $this->f034['prdt_org_prvhc_cd'];
					$nombreProvinciaOrigenVUE =  $this->f034['prdt_org_prvhc_nm'];
					
					$qProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoProvinciaOrigenVUE);
					if(pg_num_rows($qProvincia) == 0 ){
						$solicitudEsValida = false;
						echo IN_MSG. "La provincia de origen ".$nombreProvinciaOrigenVUE." no se encuentra registrado";
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = "La provincia de origen ".$nombreProvinciaOrigenVUE." no se encuentra registrado";
						break;
					}
				}				
				
				//Obtiene país de destino
				$fitosanitarioCodigoPaisDestino = $this->f034['dst_ntn_cd'];
				$fitosanitarioNombrePaisDestino = $this->f034['dst_ntn_nm'];
				$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $fitosanitarioCodigoPaisDestino); //Validación del pais de destino
				$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
				
				if(pg_num_rows($qCodigoPaisDestino) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'El pais de destino  '.$fitosanitarioNombrePaisDestino.' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El pais de destino '.$fitosanitarioNombrePaisDestino.' no se encuentra registrado en Agrocalidad. ';
					break;
				}
							
				//Obtiene puerto de destino
				$fitosanitarioCodigoPuertoDestino = $this->f034['dst_port_cd'];
				$fitosanitarioNombrePuertoDestino = $this->f034['dst_port_nm'];
				$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $fitosanitarioCodigoPuertoDestino); //Validación del puerto de destino
					
				if(pg_num_rows($qCodigoPuertoDestino) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'El puerto de destino '.$fitosanitarioNombrePuertoDestino.' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El puerto de destino '.$fitosanitarioNombrePuertoDestino.' no se encuentra registrado en Agrocalidad. ';
					break;
				}
				
				//Obtiene país de embarque
				$fitosanitarioCodigoPaisEmbarque = $this->f034['spm_ntn_cd'];
				$fitosanitarioNombrePaisEmbarque = $this->f034['spm_ntn_nm'];
				$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $fitosanitarioCodigoPaisEmbarque); //Validación del pais de embarque
				$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
				
				
				if(pg_num_rows($qCodigoPaisEmbarque) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'El pais de embarque  '.$fitosanitarioNombrePaisEmbarque.' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El pais de embarque '.$fitosanitarioNombrePaisEmbarque.' no se encuentra registrado en Agrocalidad. ';
					break;
				}
				
				//Obtiene puerto de embarque
				$fitosanitarioCodigoPuertoEmbarque = $this->f034['spm_port_cd'];
				$fitosanitarioNombrePuertoEmbarque = $this->f034['spm_port_nm'];
				$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $fitosanitarioCodigoPuertoEmbarque); //Validación del puerto de embarque
				
				if(pg_num_rows($qCodigoPuertoEmbarque) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'El puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no se encuentra registrado en Agrocalidad. ';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no se encuentra registrado en Agrocalidad. ';
					break;
				}
				
				$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
				//Validación de Puerto sea Ecuador
					
				if($codigoPuertoEmbarque['codigo_pais'] != 'EC'){
					$solicitudEsValida = false;
					echo IN_MSG. 'El puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no correponde a un puerto de Ecuador.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no correponde a un puerto de Ecuador.';
					break;
				}
				
				//Validación puerto tenga provincia
					
				if($codigoPuertoEmbarque['id_provincia'] == ''){
					$solicitudEsValida = false;
					echo IN_MSG. 'El puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no tiene registrado un lugar autorizado de inspección por Agrocalidad.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'El puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no tiene registrado un lugar autorizado de inspección por Agrocalidad.';
					break;
				}
				
				//Obtiene provincia
				$qCodigoProvincia = $controladorCatalogos->obtenerNombreLocalizacion($conexionGUIA, $codigoPuertoEmbarque['id_provincia']); //Provincia de inspeccion
					
				if(pg_num_rows($qCodigoProvincia) == 0){
					$solicitudEsValida = false;
					echo IN_MSG. 'La provincia del puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no se encuentra registrado en Agrocalidad.';
					$resultado[0] = SUBSANACION_REQUERIDA;
					$resultado[1] = 'La provincia del puerto de embarque '.$fitosanitarioNombrePuertoEmbarque.' no se encuentra registrado en Agrocalidad.';
					break;
				}
								
				//Variable la cual define si se valdia transito
				$fitosanitarioUsoTransito = $this->f034['trsp_use_fg'];
				if( $fitosanitarioUsoTransito == 'S'){
					$poseeTransito = true;
				}			
			}
			
			//VALIDACIONES DE TRANSITO
			
			if($poseeTransito){
				
				for($l = 0; $l < count ($this->f034tr); $l++ ){
					
					//Obtiene país de destino
					$codigoPaisDestinoTransito = $this->f034tr[$l]['trsp_cntry_cd'];
					$nombrePaisDestinoTransito =  $this->f034tr[$l]['trsp_cntry_nm'];
					$qPaisTransito = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoTransito); //Validación del pais de transito
						
					if(pg_num_rows($qPaisTransito) == 0){
						$solicitudEsValida = false;
						echo IN_MSG. 'El pais de transito  '.$nombrePaisDestinoTransito.' no se encuentra registrado en Agrocalidad. ';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'El pais de transito  '.$nombrePaisDestinoTransito.' no se encuentra registrado en Agrocalidad. ';
						break;
					}
					
					//Obtiene puerto de destino
					$codigoPuertoDestinoTransito = $this->f034tr[$l]['spm_port_cd'];
					$nombrePuertoDestinoTransito = $this->f034tr[$l]['spm_port_nm'];
					$qCodigoPuertoTransito = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $codigoPuertoDestinoTransito); //Validación del puerto de transito
						
					if(pg_num_rows($qCodigoPuertoTransito) == 0){
						$solicitudEsValida = false;
						echo IN_MSG. 'El puerto de transito '.$nombrePuertoDestinoTransito.' no se encuentra registrado en Agrocalidad. ';
						$resultado[0] = SUBSANACION_REQUERIDA;
						$resultado[1] = 'El puerto de transito '.$nombrePuertoDestinoTransito.' no se encuentra registrado en Agrocalidad. ';
						break;
					}
					
				}
				
			}
			
			/////////////////////////////////////////////////////////////////////
			
			if($solicitudEsValida){
				//Validación de exportadores
				for($j = 0; $j < count ($this->f034ex); $j++){
					
					if($solicitudEsValida && $validacionExportador){
						
						// INICIO EXPORTADOR
						
						// Metodo que busca si existe el operador
						$idExportador = $this->f034ex[$j]['expr_sn'];
						$identificadorExportador = $this->f034ex[$j]['expr_idt_no'];
						$nombreExportador = $this->f034ex[$j]['expr_nm'];
						
						$operador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $identificadorExportador);
						if( pg_num_rows($operador) == 0 ){
							
							$productoAlterno = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($idVue,'tn_agr_034_pd', $idExportador);
							
							for($k= 0; $k < count ($productoAlterno); $k++){
								$partidaArancelariaVUEalterna = $productos[$k]['hc'];
								$partidaAlterna =  substr($partidaArancelariaVUEalterna,0,10);
								if($partidaAlterna == '0803101000' || $partidaAlterna == '0803901110' || $partidaAlterna == '0803901200' || $partidaAlterna == '0803901900' || $partidaAlterna == '0803901190'){
									$musaceasAlterno=true;
								}else{
									$solicitudEsValida = false;
									$validacionExportador = false;
									echo IN_MSG. 'El exportador '.$nombreExportador.' con identificador '.$identificadorExportador.' no dispone de un registro de operador de comercio exterior. Debe realizar el registro correspondiente para poder solicitar un CFE.';
									$resultado[0] = ($tipoCertificado == 'IN'?SOLICITUD_NO_APROBADA:SUBSANACION_REQUERIDA);
									$resultado[1] = 'El exportador '.$nombreExportador.' con identificador '.$identificadorExportador.' no dispone de un registro de operador de comercio exterior. Debe realizar el registro correspondiente para poder solicitar un CFE.';
									break;
								}
							
							}
							
						}				
						
						//FIN EXPORTADOR
						
						//INICIO PRODUCTOS
						
						//Validación de productos del exportador
						$productos = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($idVue,'tn_agr_034_pd', $idExportador);
														
						if(count($productos) == 0){
							$solicitudEsValida = false;
							$validacionProducto = false;
							$validacionExportador = false;
							echo IN_MSG. 'Por favor ingrese uno o varios producto para la exportación.';
							$resultado[0] = SUBSANACION_REQUERIDA;
							$resultado[1] =  'Por favor ingrese uno o varios producto para la exportación.';
							break;							
						}else{
						
							for($k= 0; $k < count ($productos); $k++){
								
								if($solicitudEsValida && $validacionProducto){
									
									$musaceas = false;
									$ornamentales = false;	
									$musaceasOrnamentales = false;
									$partidaArancelariaVUE = $productos[$k]['hc'];
									$codigoProductoVUE = $productos[$k]['prdt_cd'];
									$codigoProducto = $productos[$k]['prdt_sn'];
									
									//Busca el id del producto en la base de GUIA
									$nombreProducto = $productos[$k]['prdt_nm'];
									$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
									$producto = pg_fetch_assoc($qProductoGUIA);
										
									if( pg_num_rows($qProductoGUIA) == 0){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'El producto '.$nombreProducto.' no se encuentra registrado en Agrocalidad. ';
										$resultado[0] = SUBSANACION_REQUERIDA;
										$resultado[1] = 'El producto '.$nombreProducto.' no se encuentra registrado en Agrocalidad. ';
										break;
									}
									
									$partida =  substr($partidaArancelariaVUE,0,10);
									if($partida == '0803101000' || $partida == '0803901110' || $partida == '0803901200' || $partida == '0803901900' || $partida == '0803901190'){
										$musaceas=true;
									}
									
									if(!$musaceas){
										//Validación de unidad de medida de cantidad
										$unidadMedidaProductoVUE = $productos[$k]['prdt_pck_ut'];								
										if($unidadMedidaProductoVUE != $producto['unidad_medida']){
											$solicitudEsValida = false;
											$validacionProducto = false;
											$validacionExportador = false;
											echo IN_MSG. 'La unidad de la cantidad comercial '.$unidadMedidaProductoVUE.' no corresponde a la del producto '.$nombreProducto;
											$resultado[0] = SUBSANACION_REQUERIDA;
											$resultado[1] = 'La unidad de la cantidad comercial '.$unidadMedidaProductoVUE.' no corresponde a la del producto '.$nombreProducto;
											break;
										}
									}
									
									$unidadPesoNeto = $productos[$k]['prdt_nwt_ut'];
									
									if($unidadPesoNeto != 'KG'){
										$solicitudEsValida = false;
										echo IN_MSG. 'La unidad elegida en el campo peso neto no corresponde a una unidad valida en Agrocalidad, por favor seleccione la opción KG.';
										$resultado[0] = SUBSANACION_REQUERIDA;
										$resultado[1] = 'La unidad elegida en el campo peso neto no corresponde a una unidad valida en Agrocalidad, por favor seleccione la opción KG.';
										break;
									}
									
									//Validar si el producto esta activo para un pais y actividad
									$qEstadoProductoPais = $controladorRequisitos ->consultarEstadoProductoPaisRequisito($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'Exportación', 'activo');  //estado producto pais requisito
									
									if( pg_num_rows($qEstadoProductoPais) == 0 ){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'El producto '.$nombreProducto.' no se encuentra habilitado para la exportación al país '.$fitosanitarioNombrePaisDestino;
										$resultado[0] = SUBSANACION_REQUERIDA;
										$resultado[1] = 'El producto '.$nombreProducto.' no se encuentra habilitado para la exportación al país '.$fitosanitarioNombrePaisDestino;
										break;
									}
									
									//Validacion del oportador producto y pais resultado de sanción
									$qExportadorSancion = $controladorProtocolos->buscarExportadorProductoPaisSancion($conexionGUIA, $identificadorExportador, $producto['id_producto'], $codigoPaisDestino['id_localizacion'], 'activo');
									
									if( pg_num_rows($qExportadorSancion) != 0 ){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'El exportador '.$nombreExportador.' dispone de una sanción para la exportación del producto '.$nombreProducto.' al país '.$fitosanitarioNombrePaisDestino;
										$resultado[0] = ($tipoCertificado == 'IN'?SOLICITUD_NO_APROBADA:SUBSANACION_REQUERIDA);
										$resultado[1] = 'El exportador '.$nombreExportador.' dispone de una sanción para la exportación del producto '.$nombreProducto.' al país '.$fitosanitarioNombrePaisDestino;
										break;
									}
									
									//Validacion de unidad de cobro de producto								
									$unidadCobroProductoVUE = $productos[$k]['chrg_ut'];								
									$qProductoUnidadCobro = $controladorFinanciero->obtenerUnidadMedidaCobro($conexionGUIA, $producto['id_producto'], 'Fitosanitario');
									$productoUnidadCobro = pg_fetch_assoc($qProductoUnidadCobro);
										
									if( $productoUnidadCobro['unidad_medida'] != $unidadCobroProductoVUE){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'La unidad de cobro '.$unidadCobroProductoVUE.' no corresponde a la del producto '.$nombreProducto;
										$resultado[0] = SUBSANACION_REQUERIDA;
										$resultado[1] = 'La unidad de cobro '.$unidadCobroProductoVUE.' no corresponde a la del producto '.$nombreProducto;
										break;
									}
									
									//El campo programa en el producto es diferente de null								
									$qProductoProgramaNulo = $controladorCatalogos->buscarProductoProgramaNulo($conexionGUIA, $producto['id_producto']);
									
									if( pg_num_rows($qProductoProgramaNulo) == 0){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'El producto '.$nombreProducto.' no dispone de información sobre programas, requeridos para la exportación. Por favor contáctese con Agrocalidad a fin de registrar la información requerida.';
										$resultado[0] = SUBSANACION_REQUERIDA;
										$resultado[1] = 'El producto '.$nombreProducto.' no dispone de información sobre programas, requeridos para la exportación. Por favor contáctese con Agrocalidad a fin de registrar la información requerida.';
										break;
									}
																		
									//El Producto no pertece a programa 							
									$qProductoSinPrograma = $controladorCatalogos->obtenerNombreProducto($conexionGUIA, $producto['id_producto']);
									$productoPrograma = pg_fetch_assoc($qProductoSinPrograma);
										
									if($productoPrograma['programa'] == 'SI'){
										$validacionProductoPrograma = true;
									}else{
											
										$validacionProductoPrograma = false;
									}
									
									//Verificar si existe el área registrada en GUIA
									$areas = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($idVue,'tn_agr_034_dt', $idExportador, $codigoProducto);
									
									
									if($musaceas){
										
										if ($validacionProductoPrograma){
													
											//Existe protocolo configurado para el país y producto a exportar									
											$qProtocoloPaisProducto = $controladorProtocolos->buscarProtocoloPaisProducto($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'activo');
											$protocoloPaisProducto = pg_fetch_assoc($qProtocoloPaisProducto);
											
											if(pg_num_rows($qProtocoloPaisProducto) == 0){
												$solicitudEsValida = false;
												$validacionProducto = false;
												$validacionExportador = false;
												echo IN_MSG. 'EL producto '.$nombreProducto.' no dispone de información sobre protocolos requeridos para la exportación al país '.$fitosanitarioNombrePaisDestino.'. Por favor contáctese con Agrocalidad a fin de registrar la información requerida.';
												$resultado[0] = SUBSANACION_REQUERIDA;
												$resultado[1] = 'EL producto '.$nombreProducto.' no dispone de información sobre protocolos requeridos para la exportación al país '.$fitosanitarioNombrePaisDestino.'. Por favor contáctese con Agrocalidad a fin de registrar la información requerida.';
												break;
											}
										}
										
									}else{
										
										if(count($areas) == 0){
											$solicitudEsValida = false;
											$validacionProducto = false;
											$validacionExportador = false;
											echo IN_MSG. 'Por favor ingrese una o varias áreas de operación.';
											$resultado[0] = SUBSANACION_REQUERIDA;
											$resultado[1] = 'Por favor ingrese una o varias áreas de operación.';
											break;							
										}else{
											
											if ($validacionProductoPrograma){
												
												//Existe protocolo configurado para el país y producto a exportar									
												$qProtocoloPaisProducto = $controladorProtocolos->buscarProtocoloPaisProducto($conexionGUIA, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], 'activo');
												$protocoloPaisProducto = pg_fetch_assoc($qProtocoloPaisProducto);
												
												if(pg_num_rows($qProtocoloPaisProducto) == 0){
													$solicitudEsValida = false;
													$validacionProducto = false;
													$validacionExportador = false;
													echo IN_MSG. 'EL producto '.$nombreProducto.' no dispone de información sobre protocolos requeridos para la exportación al país '.$fitosanitarioNombrePaisDestino.'. Por favor contáctese con Agrocalidad a fin de registrar la información requerida.';
													$resultado[0] = SUBSANACION_REQUERIDA;
													$resultado[1] = 'EL producto '.$nombreProducto.' no dispone de información sobre protocolos requeridos para la exportación al país '.$fitosanitarioNombrePaisDestino.'. Por favor contáctese con Agrocalidad a fin de registrar la información requerida.';
													break;
												}
												
												//INICIO AREA
												
												for ($l = 0; $l < count ($areas); $l++ ){
													
													if($solicitudEsValida && $validacionArea){
																									
														$codigoAreaVUE = $areas[$l]['agr_area_desc'];
											
														$qAreasCodigo = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $codigoAreaVUE);
														$areaCodigo = pg_fetch_assoc($qAreasCodigo);
															
														if( pg_num_rows($qAreasCodigo) == 0 ){ // Busqueda areas
															$solicitudEsValida = false;
															$validacionProducto = false;
															$validacionExportador = false;
															$validacionArea = false;
															echo IN_MSG. 'El área con código '.$codigoAreaVUE.' no se encuentra registrado en Agrocalidad.';
															$resultado[0] = SUBSANACION_REQUERIDA;
															$resultado[1] = 'El area con código '.$codigoAreaVUE.' no se encuentra registrado en Agrocalidad.';
															break;
														}
														
														//$provinciaArea[] = strtoupper($areaCodigo['provincia']);
														
														//El área declarada con el producto operación y país tiene como resultado de inspección de protocolo, aprobado												
														$qAreaGUIA = $controladorProtocolos->buscarProtocoloXCodigoAreaProductoPais ($conexionGUIA, $areaCodigo['id_area'], $producto['id_producto'], $codigoPaisDestino['id_localizacion'], 'aprobado');
			
														if(pg_num_rows($qAreaGUIA) == 0){
															$solicitudEsValida = false;
															$validacionProducto = false;
															$validacionExportador = false;
															$validacionArea = false;
															echo IN_MSG. 'El país de exportación '.$fitosanitarioNombrePaisDestino.' requiere el cumplimiento de protocolos específicos para el producto '.$nombreProducto.'. El exportador '.$areaCodigo['identificador_operador'].' no dispone de la habilitación requerida en el área '.$codigoAreaVUE;
															//echo IN_MSG. $producto['id_producto'];
															$resultado[0] = SUBSANACION_REQUERIDA;
															$resultado[1] = 'El país de exportación '.$fitosanitarioNombrePaisDestino.' requiere el cumplimiento de protocolos específicos para el producto '.$nombreProducto.'. El exportador '.$areaCodigo['identificador_operador'].' no dispone de la habilitación requerida en el área '.$codigoAreaVUE;
															break;
														}
														
													}else{
														break;
													}	
												}
												//FIN AREA									
											}else{
												
												//INICIO AREA
												
												for ($o = 0; $o < count ($areas); $o++ ){
												
													if($solicitudEsValida && $validacionArea){
														
														$codigoAreaVUE = $areas[$o]['agr_area_desc'];												
														$qAreasCodigo = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $codigoAreaVUE);
														$areaCodigo = pg_fetch_assoc($qAreasCodigo);
															
														if( pg_num_rows($qAreasCodigo) == 0 ){ // Busqueda areas
															$solicitudEsValida = false;
															$validacionProducto = false;
															$validacionExportador = false;
															$validacionArea = false;
															echo IN_MSG. 'El area con código '.$codigoAreaVUE.' no se encuentra registrado en Agrocalidad.';
															$resultado[0] = SUBSANACION_REQUERIDA;
															$resultado[1] = 'El area con código '.$codigoAreaVUE.' no se encuentra registrado en Agrocalidad.';
															break;
														}
															
														//$provinciaArea[] = strtoupper($areaCodigo['provincia']);
														
													}else{
														break;
													}
												}
												//FIN AREA	
											}	
										}
									}
								}else{
									break;
								}
								
								if($solicitudEsValida && $validacionProducto){
								
									//$arrayAreasSinRpetidos = array_unique($provinciaArea);
									
									/*if (count($arrayAreasSinRpetidos)!= 1){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'El código de las areas ingresadas no pertencen a una misma provincia.';
										$resultado[0] = SUBSANACION_REQUERIDA;
										$resultado[1] = 'El código de las areas ingresadas no pertencen a una misma provincia.';
										break;
									}*/
								
									if($poseeTransito){
									
										for($m = 0; $m < count ($this->f034tr); $m++){
												
											//Validar si el producto esta activo para un pais y actividad
											$codigoPaisDestinoTransito = $this->f034tr[$m]['trsp_cntry_cd'];
											$qPaisTransito = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoTransito); //Validación del pais de transito
											$paisTransito = pg_fetch_assoc($qPaisTransito);
												
											$qEstadoProductoPais = $controladorRequisitos ->consultarEstadoProductoPaisRequisito($conexionGUIA, $paisTransito['id_localizacion'], $producto['id_producto'], 'Tránsito', 'activo');  //estado producto pais requisito
												
											if( pg_num_rows($qEstadoProductoPais) == 0 ){
												$solicitudEsValida = false;
												$validacionProducto = false;
												$validacionExportador = false;
												echo IN_MSG. 'El producto '.$nombreProducto.' no dispone de requisitos de tránsito para el pais '.$paisTransito['nombre'].'. Por favor contáctese con Agrocalidad.';
												$resultado[0] = SUBSANACION_REQUERIDA;
												$resultado[1] = 'El producto '.$nombreProducto.' no dispone de requisitos de tránsito para el pais '.$paisTransito['nombre'].'. Por favor contáctese con Agrocalidad.';
												break;
											}
												
										}
									
									}
		
									//Validación de musáceas
									$partidaArancelaria =  substr($partidaArancelariaVUE,0,10);
									
									if($partidaArancelaria == '0803101000' || $partidaArancelaria == '0803901110' || $partidaArancelaria == '0803901200' || $partidaArancelaria == '0803901900' || $partidaArancelaria == '0803901190'){
										$musaceasOrnamentales = true;
									}
									
									if($partidaArancelaria == '0603110000' || $partidaArancelaria == '0603121000' || $partidaArancelaria == '0603129000' || $partidaArancelaria == '0603130000' 
										|| $partidaArancelaria == '0603141000' || $partidaArancelaria == '0603149000' || $partidaArancelaria == '0603150000' || $partidaArancelaria == '0603191000' 
										|| $partidaArancelaria == '0603192000' || $partidaArancelaria == '0603193000' || $partidaArancelaria == '0603194000' || $partidaArancelaria == '0603199010' 
										|| $partidaArancelaria == '0603199090' || $partidaArancelaria == '0603900000' || $partidaArancelaria == '0604200000' || $partidaArancelaria == '0604900000'){
										$musaceasOrnamentales = true;
									}
								
									if(!$musaceasOrnamentales){
									
										$qOperacionProductos = $controladorRegistroOperador->buscarOperadorProductoPaisActividad($conexionGUIA, $identificadorExportador, $codigoPaisDestino['id_localizacion'], $producto['id_producto'], $operacion['id_tipo_operacion'], 'registrado');
									
										if( pg_num_rows($qOperacionProductos) == 0 ){ // Busqueda del operador con PPR en base de datos GUIA.
											$solicitudEsValida = false;
											$validacionProducto = false;
											$validacionExportador = false;
											echo IN_MSG. 'El operador '.$nombreExportador.' con identificador '.$identificadorExportador.' no se encuentra registrado para exportar el producto '.$nombreProducto.' al país '.$fitosanitarioNombrePaisDestino.'. ';
											$resultado[0] = ($tipoCertificado == 'IN'?SOLICITUD_NO_APROBADA:SUBSANACION_REQUERIDA);
											$resultado[1] = 'El operador '.$nombreExportador.' con identificador '.$identificadorExportador.' no se encuentra registrado para exportar el producto '.$nombreProducto.' al país '.$fitosanitarioNombrePaisDestino.'. ';
											break;
										}
										
										//---------------------------ACTUALIZACIÓN DE NUEVO CAMPO PARA EL DIRECCIONAMIENTO E INSPECCIÓN--------------------------------------------------------------------
											
											$lugarInspeccion = $this->f034['agc_nm'];
											
											$qAreasCodigoInspeccion = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $lugarInspeccion);
											$areaCodigoInspeccion = pg_fetch_assoc($qAreasCodigoInspeccion);
											
											if( pg_num_rows($qAreasCodigoInspeccion) == 0 ){ // Busqueda areas
												$solicitudEsValida = false;
												$validacionProducto = false;
												$validacionExportador = false;
												echo IN_MSG. 'El área de inspección con código '.$lugarInspeccion.' no se encuentra registrado en Agrocalidad.';
												$resultado[0] = SUBSANACION_REQUERIDA;
												$resultado[1] = 'El área de inspección con código '.$lugarInspeccion.' no se encuentra registrado en Agrocalidad.';
												break;
											}
											
											$proveedorInspector = explode('.', $lugarInspeccion);
											$identificadorProveedorLugarInspeccion = $proveedorInspector[0];
																					
											$idTipoOperacion  = pg_fetch_assoc($controladorCatalogos -> buscarIdOperacionPorCodigoOperacion($conexionGUIA, 'ACOSV')); //Buscar actividad del importador segun el tipo de solicitud.
											$qCodigoAreaInspeccion = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $lugarInspeccion);
											$codigoAreaInspeccion = pg_fetch_assoc($qCodigoAreaInspeccion);
											
											// Busqueda de la operacion del exportador por producto, área, tipo de operacion y estado.
											$qAreaOperacionProductoTipoExportador = $controladorRegistroOperador->listarOperacionXIdentificadorAreaProductoTipoOperacion($conexionGUIA, $identificadorExportador, $codigoAreaInspeccion['id_area'], $producto['id_producto'], $idTipoOperacion['id_tipo_operacion'],'registrado');
											
											// Busqueda de la operacion del proveedor por producto, área, tipo de operacion y estado.
											$qAreaOperacionProductoTipoProveedor = $controladorRegistroOperador->listarOperacionXIdentificadorAreaProductoTipoOperacionProveedor($conexionGUIA, $identificadorExportador, $codigoAreaInspeccion['id_area'], $producto['id_producto'], $idTipoOperacion['id_tipo_operacion'], $identificadorProveedorLugarInspeccion, 'registrado');
											
											if( pg_num_rows($qAreaOperacionProductoTipoExportador) == 0 || pg_num_rows($qAreaOperacionProductoTipoProveedor) == 0){ 
												$contadorArea++;
											}
										
										//---------------------------FIN--------------------------------------------------------------------
										
										for ($p = 0; $p < count ($areas); $p++ ){
											if($solicitudEsValida && $validacionArea){
												
												$codigoAreaVUE = $areas[$p]['agr_area_desc'];
												$auxProveedor = explode('.', $codigoAreaVUE);
												$identificadorProveedor = $auxProveedor[0];
												
												// Busqueda de proveedor de exportacion del exportador.
												$qProveedorOperador = $controladorRegistroOperador->buscarProveedorPorOperador($conexionGUIA, $identificadorExportador, $identificadorProveedor, 'Exportador');
												
												if( pg_num_rows($qProveedorOperador) == 0 ){ 
													$solicitudEsValida = false;
													$validacionProducto = false;
													$validacionExportador = false;
													echo IN_MSG. 'El área con código '.$codigoAreaVUE.' no pertenece a un proveedor del exportador '.$nombreExportador;
													$resultado[0] = SUBSANACION_REQUERIDA;
													$resultado[1] = 'El área con código '.$codigoAreaVUE.' no pertenece a un proveedor del exportador '.$nombreExportador;
													break;
												}
												
												$qAreasCodigo = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $codigoAreaVUE);
												$areaCodigo = pg_fetch_assoc($qAreasCodigo);
												
												// Busqueda de la operacion del proveedor por producto, área y estado.
												$qAreaOperacionProductoProveedor = $controladorRegistroOperador->listarOperacionXIdentificadorAreaProducto($conexionGUIA, $identificadorProveedor, $areaCodigo['id_area'], $producto['id_producto'], 'registrado');
												$qAreaOperacionProductoExportador = $controladorRegistroOperador->listarOperacionXIdentificadorAreaProducto($conexionGUIA, $identificadorExportador, $areaCodigo['id_area'], $producto['id_producto'], 'registrado');
												
												if( pg_num_rows($qAreaOperacionProductoProveedor) == 0  || pg_num_rows($qAreaOperacionProductoExportador) == 0){ 
													$solicitudEsValida = false;
													$validacionProducto = false;
													$validacionExportador = false;
													echo IN_MSG. 'El área '.$codigoAreaVUE.' ingresada por el exportador '.$identificadorProveedor.' no dispone de una operacion registrada y activa con el producto '.$nombreProducto;
													$resultado[0] = SUBSANACION_REQUERIDA;
													$resultado[1] = 'El área '.$codigoAreaVUE.' ingresada por el exportador '.$identificadorProveedor.' no dispone de una operacion registrada y activa con el producto '.$nombreProducto;
													break;
												}
												
											}else{
												break;
											}
										}						
									}
								}else{
									break;
								}					
							}
						}					
						//FIN PRODUCTOS
					}else{
						break;
					}	
				}
			}
		}

		if(count($this->f034ex)==$contadorArea){
			$solicitudEsValida = false;
			$validacionProducto = false;
			$validacionExportador = false;
			echo IN_MSG. 'El área ingresada para la inspección ( '.$lugarInspeccion.' ) no se encuentra registrada en Agrocalidad. Por favor debe ingresar un código de área válido para ejecutar la inspección de los productos a exportar.';
			$resultado[0] = ($tipoCertificado == 'IN'?SOLICITUD_NO_APROBADA:SUBSANACION_REQUERIDA);
			$resultado[1] = 'El área ingresada para la inspección ( '.$lugarInspeccion.' ) no se encuentra registrada en Agrocalidad. Por favor debe ingresar un código de área válido para ejecutar la inspección de los productos a exportar.';
		}
				
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
	}

	public function validarActualizacionDeDatos(){

		$solicitudEsValida = true;
		$transito = false;
		$resultado = array();
		
		$validacionExportador = true;
		$validacionProducto = true;
		$validacionArea = true;
		$poseeTransito = false;
		$provinciaArea = array();
		
		
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorVUE = new ControladorVUE();
		$conexionGUIA = new Conexion();
		
		echo  PRO_MSG. 'validando actualizacion de formulario ';
		
		//$comprobacionDatosObligatorios = parent::validarCamposObligatorios($this->f034,'cabecera');
		
		/*if(!$comprobacionDatosObligatorios){
			$solicitudEsValida = false;
			echo IN_MSG. 'Por favor revisar todos los campos obligatorios para el formulario.';
			$resultado[0] = SUBSANACION_REQUERIDA;
			$resultado[1] = 'Por favor revisar todos los campos obligatorios para el formulario.';
		}*/
		
		if($solicitudEsValida){		
		
			$qFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']);
			$fitosanitarioExportacion = pg_fetch_assoc($qFitosanitarioExportacion);
			
			for ($i = 0; $i < count ($this->f034['req_no']); $i++) {
					
				
				//Validacion número documento
				if($this->f034['dcm_no'] != $fitosanitarioExportacion['numero_documento']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo numero de documento.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo numero de documento.';
					break;
				}			
				
				/*//Validacion fecha de creación
				if(date('j/n/Y',strtotime($this->f034['req_de'])) != date('j/n/Y',strtotime($fitosanitarioExportacion['fecha_creacion']))){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo fecha de creación.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo fecha de creación.';
					break;
				}*/
				
				
				//Validación ciudad solicitud
				$codigoCiudadVUE = $this->f034['req_city_cd'];
				
				$qCodigoCiudad = $controladorCatalogos->obtenerCodigoLocalizacionImportacion($conexionGUIA, $codigoCiudadVUE);
				$codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
				
				if($codigoCiudad['id_localizacion'] != $fitosanitarioExportacion['id_ciudad_solicitante']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la ciudad del solicitante.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la ciudad del solicitante.';
					break;
				}
							
				
				//Validación código tipo CFE
				if($this->f034['sps_idt_type_cd'] != $fitosanitarioExportacion['codigo_tipo_cfe']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el código de tipo de CFE.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el código de tipo de CFE.';
					break;
				}
								
				//Validación código idioma
				if($this->f034['lang_cd'] != $fitosanitarioExportacion['codigo_idioma']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el código de idioma.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el código de idioma.';
					break;
				}			
				
				/*//Validación fecha inicio vigencia certificado
				if(date('j/n/Y',strtotime($this->f034['ctft_eftv_stdt'])) != date('j/n/Y',strtotime($fitosanitarioExportacion['fecha_inicio_vigencia_certificado']))){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la fecha de inicio de vigencia del CFE.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar la fecha de inicio de vigencia del CFE.';
					break;
				}
	
				
				//Validación fecha inicio vigencia certificado
				if(date('j/n/Y',strtotime($this->f034['ctft_eftv_finl_de'])) != date('j/n/Y',strtotime($fitosanitarioExportacion['fecha_fin_vigencia_certificado']))){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la fecha de fin de vigencia del CFE.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar la fecha de fin de vigencia del CFE.';
					break;
				}*/
				
				
				//Validación código clasificación solicitante
				if($this->f034['dclr_cl_cd'] != $fitosanitarioExportacion['codigo_clasificacion_solicitante']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el código de clasificación del solicitante.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el código de clasificación del solicitante.';
					break;
				}
				
				//Validación identificación solicitante
				if($this->f034['dclr_idt_no'] != $fitosanitarioExportacion['numero_identificacion_solicitante']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el número de indentificación del solicitante.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el número de indentificación del solicitante.';
					break;
				}
				
				//Validación represntante legal solicitante
				if($this->f034['dclr_rpgp_nm'] != $fitosanitarioExportacion['nombre_representante_legal_solicitante']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el representante legal del solicitante.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el representante legal del solicitante.';
					break;
				}
				
				
				//Validación dirección solicitante
				if($this->f034['dclr_ad'] != $fitosanitarioExportacion['direccion_solicitante']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la dirección del solicitante.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar la dirección del solicitante.';
					break;
				}
				
				
				//Validación teléfono solicitante
				if($this->f034['dclr_tel_no'] != $fitosanitarioExportacion['telefono_solicitante']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el número de teléfono del solicitante.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el número de teléfono del solicitante.';
					break;
				}
				
				
				//Validación correo electrónico solicitante
				if($this->f034['dclr_em'] != $fitosanitarioExportacion['correo_electronico_solicitante']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el correo electrónico del solicitante.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el correo electrónico del solicitante.';
					break;
				}
				
				
				//Validación producto orgánico
				
				$productoOrganico = $this->f034['prdt_orgn_fg'];
				
				if($productoOrganico=="S"){
					$productoOrganico="SI";
				}else{
					$productoOrganico="NO";
				}
											
				if($productoOrganico != $fitosanitarioExportacion['producto_organico']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo producto orgánico.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo producto orgánico.';
					break;
				}
				
								
				if($this->f034['prdt_orgn_cert'] != $fitosanitarioExportacion['certificado_organico']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo certificado orgánico.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo certificado orgánico.';
					break;
				}
					
				
				//Validación número de bultos
				if($this->f034['pkgs_no'] != $fitosanitarioExportacion['numero_bultos']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el número de bultos.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el número de bultos.';
					break;
				}
				
				
				//Validación unidad de bultos
				if($this->f034['pkgs_ut'] != $fitosanitarioExportacion['unidad_bultos']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo unidad de bultos.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo unidad de bultos.';
					break;
				}
				
				
				//Obtiene país de origen
				$fitosanitarioCodigoPaisOrigen = $this->f034['org_ntn_cd'];
					
				$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA,$fitosanitarioCodigoPaisOrigen); //Validación del pais de origen
				$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
					
				if($codigoPaisOrigen['id_localizacion'] != $fitosanitarioExportacion['id_pais_origen']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el país de origen.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el país de origen.';
					break;
				}
				
				//Validación pais de destino				
				$fitosanitarioCodigoPaisDestino = $this->f034['dst_ntn_cd'];
					
				$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA,$fitosanitarioCodigoPaisDestino); 
				$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
					
				if($codigoPaisDestino['id_localizacion'] != $fitosanitarioExportacion['id_pais_destino']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el país de destino.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el país de destino.';
					break;
				}
				
				
				//Validación de provincia de origen
				$codigoProvinciaOrigenVUE =  $this->f034['prdt_org_prvhc_cd'];
							
				$qProvincia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoProvinciaOrigenVUE);
				$provincia = pg_fetch_assoc($qProvincia);
				
				if($provincia['id_localizacion'] != $fitosanitarioExportacion['id_provincia_origen_producto']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la provincia de origen.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la provincia de origen.';
					break;
				}
				
				
				//Validación identificación agencia de carga
				if($this->f034['agc_nm'] != $fitosanitarioExportacion['nombre_agencia_carga']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el número de indentificación de la agencia de carga.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el número de indentificación de la agencia de carga.';
					break;
				}				
					
				//Validación de la fecha de embarque
				if(date('j/n/Y',strtotime($this->f034['dtf_spm_de'])) != date('j/n/Y',strtotime($fitosanitarioExportacion['fecha_embarque']))){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la fecha de embarque.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar la fecha de embarque.';
					break;
				}
				
				
				//Obtiene país de embarque
				$fitosanitarioCodigoPaisEmbarque = $this->f034['spm_ntn_cd'];
				
				$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $fitosanitarioCodigoPaisEmbarque); //Validación del pais de embarque
				$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
				
				
				if($codigoPaisEmbarque['id_localizacion'] != $fitosanitarioExportacion['id_pais_embarque']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el país de embarque.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el país de embarque.';
					break;
				}
				
				//Obtiene puerto de embarque
				$fitosanitarioCodigoPuertoEmbarque = $this->f034['spm_port_cd'];
				
				$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $fitosanitarioCodigoPuertoEmbarque); //Validación del puerto de embarque
				$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
				
				if($codigoPuertoEmbarque['id_puerto'] != $fitosanitarioExportacion['id_puerto_embarque']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el puerto de embarque.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar el puerto de embarque.';
					break;
				}
				
				
				//Validación código de medio de transporte
				if($this->f034['dcd_trsp_way_cd'] != $fitosanitarioExportacion['codigo_medio_transporte']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el código de medio de transporte.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el código de medio de transporte.';
					break;
				}				
				
				//Validación de nombre de marca
				if($this->f034['bdnm'] != $fitosanitarioExportacion['nombre_marca']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el nombre de marca.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el nombre de marca.';
					break;
				}
				
				
				//Validación de número de viaje
				if($this->f034['trip_num'] != $fitosanitarioExportacion['numero_viaje']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo número de viaje.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo número de viaje.';
					break;
				}
				
				
				//Validación de descuento
				$descuento = $this->f034['disc_cd'];
				
				if($descuento=="S"){
					$descuento="SI";
				}else{
					$descuento="NO";
				}
								
				if($descuento != $fitosanitarioExportacion['descuento']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo descuento.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo descuento.';
					break;
				}
				
				
				//Validación de motivo de descuento
				if($this->f034['disc_motv'] != $fitosanitarioExportacion['motivo_descuento']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el motivo del descuento.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el motivo del descuento.';
					break;
				}
				
				
				/*//Validación del nombre del aprobador
				if($this->f034['aprb_nm'] != $fitosanitarioExportacion['nombre_aprobador']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el nombre del aprobador.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el nombre del aprobador.';
					break;
				}		
				
				//Validación del cargo del aprobador
				if($this->f034['aprb_odty_nm'] != $fitosanitarioExportacion['cargo_aprobador']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el cargo del aprobador.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el cargo del aprobador.';
					break;
				}
				
				//Validación de la observación del aprobador
				if($this->f034['aprb_rmk'] != $fitosanitarioExportacion['observacion_aprobador']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la observación del aprobador.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar la observación del aprobador.';
					break;
				}*/
	
				
				//Validación uso ciudad de tránsito
				$ciudadTransito = $this->f034['trsp_use_fg'];
				
				if($ciudadTransito=="S"){
					$ciudadTransito="SI";
				}else{
					$ciudadTransito="NO";
				}
				
				if($ciudadTransito != $fitosanitarioExportacion['uso_ciudad_transito']){
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar el campo de uso de ciudad de tránsito.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] =  'No se permite modificar el campo de uso de ciudad de tránsito.';
					break;
				}
				
				
				//Validar número exportadores
				$numeroExportadores = $controladorFitosanitarioExportacion->obtenerExportadoresFitosanitarioExportacion($conexionGUIA, $fitosanitarioExportacion['id_fitosanitario_exportacion']);
					
				if(pg_num_rows($numeroExportadores) != count($this->f034ex)){;
					$solicitudEsValida = false;
					echo IN_MSG. 'No se permite modificar la cantidad de registros ingresados en la sección exportadores.';
					$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
					$resultado[1] = 'No se permite modificar la cantidad de registros ingresados en la sección exportadores.';
					break;
				}	
				
				//Variable la cual define si se valdia transito
				$fitosanitarioUsoTransito = $this->f034['trsp_use_fg'];
				if( $fitosanitarioUsoTransito == 'S'){
					$poseeTransito = true;
					$numeroTransito = $controladorFitosanitarioExportacion->obtenerTransitoFitosanitarioExportacion($conexionGUIA, $fitosanitarioExportacion['id_fitosanitario_exportacion']);
						
					if(count($numeroTransito) != count($this->f034tr)){
						$poseeTransito = false;
						$solicitudEsValida = false;
						echo IN_MSG. 'No se permite modificar la cantidad de registros ingresados en la sección transito.';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'No se permite modificar la cantidad de registros ingresados en la sección transito.';
						break;
					}
				}
			}
	
			
			if($poseeTransito){
			 	
				for($l = 0; $l < count ($this->f034tr); $l++ ){
					
					$codigoPaisDestinoTransito = $this->f034tr[$l]['trsp_cntry_cd'];
					$nombrePaisDestinoTransito = $this->f034tr[$l]['trsp_cntry_nm'];
					$codigoPuertoDestinoTransito = $this->f034tr[$l]['spm_port_cd'];
					$nombrePuertoDestinoTransito = $this->f034tr[$l]['spm_port_nm'];
					$codigoTipoTransporte = $this->f034tr[$l]['trsp_via_cd'];
					$nombreTipoTransporte = $this->f034tr[$l]['trsp_via_nm'];				
					
					$paisTransito = pg_fetch_assoc($controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoTransito)); //Validación del pais de transito
					$codigoPuertoTransito = pg_fetch_assoc($controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $codigoPuertoDestinoTransito)); //Validación del puerto de transito
					
					$transito = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionTransporte($conexionGUIA, $fitosanitarioExportacion['id_fitosanitario_exportacion'], $paisTransito['id_localizacion'], $codigoPuertoTransito['id_puerto'], $codigoTipoTransporte);
					
					if(pg_num_rows($transito) == 0){
						$solicitudEsValida = false;
						echo IN_MSG. 'El pais de transito  '.$nombrePaisDestinoTransito.', puerto de transito '.$nombrePuertoDestinoTransito.' y medio de transporte '.$nombreTipoTransporte.' no se encuentra registrado en el permiso fitosanitario. ';
						$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
						$resultado[1] = 'El pais de transito  '.$nombrePaisDestinoTransito.', puerto de transito '.$nombrePuertoDestinoTransito.' y medio de transporte '.$nombreTipoTransporte.' no se encuentra registrado en el permiso fitosanitario. ';
						break;
					}
				}
				
			}
			
			if($solicitudEsValida){
				
				//Validación de exportadores
				for($j = 0; $j < count ($this->f034ex); $j++){
						
					if($solicitudEsValida && $validacionExportador){
						
						// INICIO EXPORTADOR
						
						$idExportador = $this->f034ex[$j]['expr_sn'];
						$identificadorExportador = $this->f034ex[$j]['expr_idt_no'];
						
	
						// Validación de identificación exportador								
						$qOperador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $identificadorExportador);
						$operador = pg_fetch_assoc($qOperador);
						
						$qExportadorFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionExportador($conexionGUIA, $fitosanitarioExportacion['id_fitosanitario_exportacion'], $operador['identificador']);
						$exportadorFitosanitarioExportacion = pg_fetch_assoc($qExportadorFitosanitarioExportacion);
						
						
						//Validación código clasificación identificación exportador
						if($this->f034ex[$j]['expr_idt_cl_cd'] != $exportadorFitosanitarioExportacion['codigo_clasificacion_identificacion_exportador']){
							$solicitudEsValida = false;
							$validacionExportador = false;
							echo IN_MSG. 'No se permite modificar el código de clasificación de identificación del exportador.';
							$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
							$resultado[1] = 'No se permite modificar el código de clasificación de identificación del exportador.';
							break;
						}		

						//Validación código clasificación identificación exportador
						if($this->f034ex[$j]['expr_idt_no_type_cd'] != $exportadorFitosanitarioExportacion['codigo_tipo_numero_identificacion_exportador']){
							$solicitudEsValida = false;
							$validacionExportador = false;
							echo IN_MSG. 'No se permite modificar el código de tipo de número de identificación del exportador.';
							$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
							$resultado[1] = 'No se permite modificar el código de tipo de número de identificación del exportador.';
							break;
						}	

						//Validación de número de identificación exportador
						if($this->f034ex[$j]['expr_idt_no'] != $exportadorFitosanitarioExportacion['numero_identificacion_exportador']){ 
							$solicitudEsValida = false;
							$validacionExportador = false;
							echo IN_MSG. 'No se permite modificar el número de identificador del exportador.';
							$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
							$resultado[1] = 'No se permite modificar el número de identificador del exportador.';
							break;
						}

						/*/Validación de nombre del exportador TODO: VERIFICAR SI SE VALIDA
						if($this->f034ex[$j]['expr_nm'] != $exportadorFitosanitarioExportacion['nombre_exportador']){
							$solicitudEsValida = false;
							$validacionExportador = false;
							echo IN_MSG. 'No se permite modificar el número de identificador del exportador.';
							$resultado[0] = SUBSANACION_REQUERIDA;
							$resultado[1] = 'No se permite modificar el número de identificador del exportador.';
							break;
						}*/
						
						//Validación de dircción del exportador
						if($this->f034ex[$j]['expr_ad'] != $exportadorFitosanitarioExportacion['direccion_exportador']){
							$solicitudEsValida = false;
							$validacionExportador = false;
							echo IN_MSG. 'No se permite modificar la dirección del exportador.';
							$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
							$resultado[1] = 'No se permite modificar la dirección del exportador.';
							break;
						}
												
						//FIN EXPORTADOR
						
						//Validación de productos del exportador
						$productos = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($this->f034['req_no'],'tn_agr_034_pd', $idExportador);
						$productosGUIA = $controladorFitosanitarioExportacion->obtenerProductosFitosanitarioExportacion($conexionGUIA, $fitosanitarioExportacion['id_fitosanitario_exportacion'],  $exportadorFitosanitarioExportacion['id_fitosanitario_exportador']);
						
						if(count($productos) != pg_num_rows($productosGUIA)){
							$solicitudEsValida = false;
							$validacionProducto = false;
							$validacionExportador = false;
							echo IN_MSG. 'No se permite modificar la cantidad de productos por exportador.';
							$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
							$resultado[1] =  'No se permite modificar la cantidad de productos por exportador.';
							break;
						}
						
						///////////////////////////////////
						
						if(count($productos) == 0){
							$solicitudEsValida = false;
							$validacionProducto = false;
							$validacionExportador = false;
							echo IN_MSG. 'Por favor ingrese uno o varios producto para la exportación.';
							$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
							$resultado[1] =  'Por favor ingrese uno o varios producto para la exportación.';
							break;
						}else{
						
							for($k= 0; $k < count ($productos); $k++){
						
								if($solicitudEsValida && $validacionProducto){
									
									$partidaArancelariaVUE = $productos[$k]['hc'];
									$codigoProductoVUE = $productos[$k]['prdt_cd'];
									$nombreProductoVUE = $productos[$k]['prdt_nm'];
									$secuencialProductoVUE = $productos[$k]['prdt_sn'];
																	
									$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
									$producto = pg_fetch_assoc($qProductoGUIA);
									
									//TODO: FALTA EN LA CONSULTA ENVIAR EL EXPORTADOR	-->YA ESTA
									$qProductoFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionProducto($conexionGUIA, $exportadorFitosanitarioExportacion['id_fitosanitario_exportador'], $fitosanitarioExportacion['id_fitosanitario_exportacion'], $producto['id_producto']);
										
									if( pg_num_rows($qProductoFitosanitarioExportacion) == 0){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'El producto '.$nombreProductoVUE.' no se encuentra registrado en el permiso fitosanitaria de exportación para el exportador '.$exportadorFitosanitarioExportacion['numero_identificacion_exportador'];
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'El producto '.$nombreProductoVUE.' no se encuentra registrado en el permiso fitosanitaria de exportación para el exportador '.$exportadorFitosanitarioExportacion['numero_identificacion_exportador'];
										break;
									}
															
									$productoFitosanitarioExportacion = pg_fetch_assoc($qProductoFitosanitarioExportacion);
									
									//Validación subpartida arancelaria
									if($productos[$k]['hc'] != $productoFitosanitarioExportacion['subpartida_arancelaria']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la subpartida arancelaria del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la subpartida arancelaria del producto.';
										break;
									}
																	
									//Validación de cantidad de cobro
									if($productos[$k]['chrg_no'] != $productoFitosanitarioExportacion['cantidad_cobro']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la cantidad de cobro del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la cantidad de cobro del producto.';
										break;
									}
																	
									//Validación de unidad de cobro
									if($productos[$k]['chrg_ut'] != $productoFitosanitarioExportacion['unidad_cobro']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la unidad de cobro del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la unidad de cobro del producto.';
										break;
									}
									
									//Validación de cantidad de peso neto
									if($productos[$k]['prdt_nwt'] != $productoFitosanitarioExportacion['cantidad_peso_neto']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la cantidad de peso neto del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la cantidad de peso neto del producto.';
										break;
									}
									
									//Validación de unidad de peso neto
									if($productos[$k]['prdt_nwt_ut'] != $productoFitosanitarioExportacion['unidad_peso_neto']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la unidad de peso neto del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la unidad de peso neto del producto.';
										break;
									}
									
									//Validación de cantidad de peso bruto
									if($productos[$k]['prdt_grwg'] != $productoFitosanitarioExportacion['cantidad_peso_bruto']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la cantidad de peso bruto del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la cantidad de peso bruto del producto.';
										break;
									}
									
									//Validación de unidad de peso bruto
									if($productos[$k]['prdt_grwg_ut'] != $productoFitosanitarioExportacion['unidad_peso_bruto']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la unidad de peso bruto del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la unidad de peso bruto del producto.';
										break;
									}
									
									//Validación de cantidad comercial
									if($productos[$k]['prdt_pck_qt'] != $productoFitosanitarioExportacion['cantidad_comercial']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la cantidad comercial del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la cantidad comercial del producto.';
										break;
									}
																	
									//Validación de unidad de cantidad comercial
									if($productos[$k]['prdt_pck_ut'] != $productoFitosanitarioExportacion['unidad_cantidad_comercial']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la unidad de cantidad comercial del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la unidad de cantidad comercial del producto.';
										break;
									}
									
									//Validación de código de tipo de tratamiento
									if($productos[$k]['prcg_type_cd'] != $productoFitosanitarioExportacion['codigo_tipo_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar el código de tipo de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar el código de tipo de tratamiento del producto.';
										break;
									}
																	
									//Validación de código de descripcion de tratamiento 
									if($productos[$k]['prcg_type_desc'] != $productoFitosanitarioExportacion['descripcion_tipo_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la descripción del tipo de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la descripción del tipo de tratamiento del producto.';
										break;
									}
									
									//Validación de código de nombre de tratamiento
									if($productos[$k]['prcg_nm_cd'] != $productoFitosanitarioExportacion['codigo_nombre_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar el código de nombre de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar el código de nombre de tratamiento del producto.';
										break;
									}
									
									//Validación de descripción de nombre de tratamiento 
									if($productos[$k]['prcg_nm_desc'] != $productoFitosanitarioExportacion['descripcion_nombre_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la descripción de nombre de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la descripción de nombre de tratamiento del producto.';
										break;
									}
									
									//Validación de duración tratamiento
									if($productos[$k]['dfct_slz_prcg_durt'] != $productoFitosanitarioExportacion['duracion_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la duración de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la duración de tratamiento del producto.';
										break;
									}
																	
									//Validación de unidad tratamiento
									if($productos[$k]['dfct_slz_prcg_durt_ut'] != $productoFitosanitarioExportacion['unidad_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la unidad de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la unidad de tratamiento del producto.';
										break;
									}
									
									//Validación de temperatura tratamiento
									$temperaturaTratamiento = (isset($productos[$k]['dfct_slz_prcg_tp'])? $productos[$k]['dfct_slz_prcg_tp'] : 0);
									
									if($temperaturaTratamiento != $productoFitosanitarioExportacion['temperatura_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la temperatura de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la temperatura de tratamiento del producto.';
										break;
									}
									
									//Validación de unidad temperatura tratamiento
									if($productos[$k]['dfct_slz_prcg_tp_ut'] != $productoFitosanitarioExportacion['unidad_temperatura_tratamiento']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la unidad de temperatura de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la unidad de temperatura de tratamiento del producto.';
										break;
									}
									
									//Validación de concentración de producto químico
									if($productos[$k]['dfct_slz_prcg_cct'] != $productoFitosanitarioExportacion['concentracion_producto_quimico']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la concentración de producto químico del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la concentración de producto químico del producto.';
										break;
									}
									
									//Validación de concentración de producto químico
									if($productos[$k]['dfct_slz_prcg_cct'] != $productoFitosanitarioExportacion['concentracion_producto_quimico']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la concentración de producto químico del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la concentración de producto químico del producto.';
										break;
									}
									
									//Validación de fecha de tratamiento
									if(date('j/n/Y',strtotime($productos[$k]['dfct_slz_prcg_de'])) != date('j/n/Y',strtotime($productoFitosanitarioExportacion['fecha_tratamiento']))){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la fecha de tratamiento del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar la fecha de tratamiento del producto.';
										break;
									}
									
									//Validación de producto químico
									if($productos[$k]['dfct_slz_prcg_chm_prdt_nm'] != $productoFitosanitarioExportacion['producto_quimico']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar el producto químico del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar el producto químico del producto.';
										break;
									}
									
									/*//Validación de nombre botánico
									if($productos[$k]['fctr_btn'] != $productoFitosanitarioExportacion['nombre_botanico']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar el nombre botánico del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar el nombre botánico del producto.';
										break;
									}
									
									//Validación de requisito fitosanitario
									if($productos[$k]['pht_req'] != $productoFitosanitarioExportacion['requisito_fitosanitario']){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar el requisito fitosanitario del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] = 'No se permite modificar el requisito fitosanitario del producto.';
										break;
									}*/
																	
									///ACTUALIZACION AREAS
																	
									$areas = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($this->f034['req_no'],'tn_agr_034_dt', $idExportador, $secuencialProductoVUE);
									
									$areasGUIA = $controladorFitosanitarioExportacion->obtenerAreasFitosanitarioExportacion($conexionGUIA,  $fitosanitarioExportacion['id_fitosanitario_exportacion'], $exportadorFitosanitarioExportacion['id_fitosanitario_exportador'], $productoFitosanitarioExportacion['id_fitosanitario_producto']);
									
									if(count($areas) != pg_num_rows($areasGUIA)){
										$solicitudEsValida = false;
										$validacionProducto = false;
										$validacionExportador = false;
										echo IN_MSG. 'No se permite modificar la cantidad de áreas del producto.';
										$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
										$resultado[1] =  'No se permite modificar la cantidad de áreas del producto.';
										break;
									}
									
									$partida =  substr($partidaArancelariaVUE,0,10);
									if($partida == '0803101000' || $partida == '0803901110' || $partida == '0803901200' || $partida == '0803901900' || $partida == '0803901190'){
										$musaceas=true;
									}
									
									///////////////////////////////////
									if(!$musaceas){
										if(count($areas) == 0){
											$solicitudEsValida = false;
											$validacionProducto = false;
											$validacionExportador = false;
											echo IN_MSG. 'Por favor ingrese una o varias áreas de operación.';
											$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
											$resultado[1] = 'Por favor ingrese una o varias áreas de operación.';
											break;							
										}else{										
												for($l= 0; $l < count ($areas); $l++){
														
													if($solicitudEsValida && $validacionArea){
											
														$codigoAreaVUE = $areas[$l]['agr_area_desc'];
																											
														$qAreasCodigo = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $codigoAreaVUE);
														$areaCodigo = pg_fetch_assoc($qAreasCodigo);
														
														$qAreaExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionArea($conexionGUIA, $exportadorFitosanitarioExportacion['id_fitosanitario_exportador'], $fitosanitarioExportacion['id_fitosanitario_exportacion'], $productoFitosanitarioExportacion['id_fitosanitario_producto'], $areaCodigo['id_area']);
														
														if( pg_num_rows($qAreasCodigo) == 0){
															$solicitudEsValida = false;
															$validacionProducto = false;
															$validacionExportador = false;
															$validacionArea = false;
															echo IN_MSG. 'El área '.$codigoAreaVUE.' no se encuentra registrado en el permiso fitosanitario de exportación. ';
															$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
															$resultado[1] = 'El área '.$codigoAreaVUE.' no se encuentra registrado en el permiso fitosanitario de exportación. ';
															break;
														}
																												
														$areaExportacion = pg_fetch_assoc($qAreaExportacion);
														
														//Validación código área agrocalidad unibanano
														if($areas[$l]['agr_area_desc'] != $areaExportacion['codigo_area_agrocalidad_unibanano']){
															$solicitudEsValida = false;
															$validacionProducto = false;
															$validacionExportador = false;
															$validacionArea = false;
															echo IN_MSG. 'No se permite modificar el código de área de agrocalidad/unibanano.';
															$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
															$resultado[1] = 'No se permite modificar el código de área de agrocalidad/unibanano.';
															break;
														}
														
														//Validación número de AUCP
														if($areas[$l]['req_aucp_no'] != $areaExportacion['numero_aucp ']){
															$solicitudEsValida = false;
															$validacionProducto = false;
															$validacionExportador = false;
															$validacionArea = false;
															echo IN_MSG. 'No se permite modificar el número de AUCP.';
															$resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
															$resultado[1] = 'No se permite modificar el número de AUCP.';
															break;
														}
																					
													}else{
														break;
													}											
												}									
											}
									}			
							
								}else{
									break;
								}
							}					
						}
					}else{
						break;
					}	
				}
			}
		}
		
		if ($solicitudEsValida)
			return true;
		else
			return $resultado;
	}

	public function insertarDatosEnGUIA(){
		
		$controladorRegistroOperador = new ControladorRegistroOperador();
		$controladorFitosanitario = new ControladorFitosanitario();
		
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		
		$controladorVUE = new ControladorVUE();
		$controladorCatalogos = new ControladorCatalogos();
		$cff = new ControladorFinanciero();
		$conexionGUIA = new Conexion();
		
		$transito = false;
		$productoMango = false;
		$productoMusaceas = true;
		$productoOrnamentales = true;
		$arrayProductoPrograma = array();
		$arrayProductoExoneracion = array();
		$arrayProductoFinanciero = array();
		
		$banderaFito = false;
				
		$idVue = $this->f034['req_no'];
		
		//Obtener ciudad solicitud
		$qCodigoCiudad = $controladorCatalogos->obtenerCodigoLocalizacionImportacion($conexionGUIA, $this->f034['req_city_cd']);
		$codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
				
		//Obtener pais de origen
		$qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f034['org_ntn_cd']);
		$codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
		
		//Obtener pais de destino
		$qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f034['dst_ntn_cd']);
		$codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
		
		//Obtiener provincia origen
		$qProvinciaOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f034['prdt_org_prvhc_cd']);
		$provinciaOrigen = pg_fetch_assoc($qProvinciaOrigen);
		
		//Obtener puerto destino
		$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f034['dst_port_cd']);
		$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
		
		//Obtener pais embarque
		$qCodigoPaisEmbarque = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $this->f034['spm_ntn_cd']);
		$codigoPaisEmbarque = pg_fetch_assoc($qCodigoPaisEmbarque);
		
		//Obtener puerto embarque
		$qCodigoPuertoEmbarque = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f034['spm_port_cd']);
		$codigoPuertoEmbarque = pg_fetch_assoc($qCodigoPuertoEmbarque);
		
		$qCodigoProvincia = $controladorCatalogos->obtenerNombreLocalizacion($conexionGUIA, $codigoPuertoEmbarque['id_provincia']); //Provincia de inspeccion
		$codigoProvincia = pg_fetch_assoc($qCodigoProvincia);
				
		//Nombre de importador
		$nombreImportador = str_replace("'", "''", $this->f034['impr_nm']);	
		
		$productoOrganico = $this->f034['prdt_orgn_fg'];
		
		if($productoOrganico=="S"){
			$productoOrganico="SI";
		}else{
			$productoOrganico="NO";
		}
		
		$descuento = $this->f034['disc_cd'];
		
		if($descuento=="S"){
			$descuento="SI";
		}else{
			$descuento="NO";
		}
		
		$ciudadTransito = $this->f034['trsp_use_fg'];
		
		if($ciudadTransito=="S"){
			$ciudadTransito="SI";
		}else{
			$ciudadTransito="NO";
		}
				
		//Verificar si tiene tránsito
		$fitosanitarioUsoTransito = $this->f034['trsp_use_fg'];
		if( $fitosanitarioUsoTransito == 'S'){
			$poseeTransito = true;
		}		
		
		$codigoProvinciaOrigen = ($provinciaOrigen['id_localizacion'] != '' ? $provinciaOrigen['id_localizacion'] : 'NULL');
		
		$lugarInspeccion =  $this->f034['agc_nm'];
				
		$idFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']);
		
		if(pg_num_rows($idFitosanitarioExportacion) == 0){
		
			$idFitosanitarioExportacion=$controladorFitosanitarioExportacion->guardarFitosanitarioExportacion($conexionGUIA, $this->f034['req_no'], $this->f034['dcm_no'], $this->f034['dcm_nm'], $this->f034['dcm_func_cd'], $codigoCiudad['id_localizacion'],
					$this->f034['req_city_cd'], $codigoCiudad['nombre'], $this->f034['sps_idt_type_cd'], $this->f034['sps_idt_nm'], $this->f034['lang_cd'], 
					$this->f034['dclr_cl_cd'], $this->f034['dclr_idt_no'], $this->f034['dclr_nole'],
					$this->f034['dclr_rpgp_nm'], $this->f034['dclr_ad'], $this->f034['dclr_tel_no'], $this->f034['dclr_em'], $nombreImportador,
					$this->f034['impr_ad'], $productoOrganico, $this->f034['prdt_orgn_cert'], $this->f034['pkgs_no'], $this->f034['pkgs_ut'], $codigoPaisOrigen['id_localizacion'], $codigoPaisOrigen['nombre'],
					$codigoProvinciaOrigen,  $provinciaOrigen['nombre'], $this->f034['agc_id'], $this->f034['agc_nm'], $codigoPaisDestino['id_localizacion'],
					$codigoPaisDestino['nombre'], $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'], $this->f034['dtf_spm_de'], $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'], $codigoPuertoEmbarque['id_puerto'],
					$codigoPuertoEmbarque['nombre_puerto'], $this->f034['dcd_trsp_way_cd'], $this->f034['dcd_trsp_way_nm'], $this->f034['bdnm'], $this->f034['trip_num'], $this->f034['addt_inf'], $descuento, $this->f034['disc_motv'],
					$this->f034['aprb_nm'], $this->f034['aprb_odty_nm'], $this->f034['aprb_rmk'], $ciudadTransito, $lugarInspeccion);
			
			$codigoVerificacion = '110';
			
		}else{
		
			$controladorFitosanitarioExportacion->actualizarFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $this->f034['req_no'], $this->f034['dcm_no'], $this->f034['dcm_nm'], $this->f034['dcm_func_cd'], $codigoCiudad['id_localizacion'],
					$this->f034['req_city_cd'], $codigoCiudad['nombre'], $this->f034['sps_idt_type_cd'], $this->f034['sps_idt_nm'], $this->f034['lang_cd'], 
					$this->f034['dclr_cl_cd'], $this->f034['dclr_idt_no'], $this->f034['dclr_nole'],
					$this->f034['dclr_rpgp_nm'], $this->f034['dclr_ad'], $this->f034['dclr_tel_no'], $this->f034['dclr_em'], $this->f034['impr_nm'],
					$this->f034['impr_ad'], $productoOrganico, $this->f034['prdt_orgn_cert'], $this->f034['pkgs_no'], $this->f034['pkgs_ut'], $codigoPaisOrigen['id_localizacion'], $codigoPaisOrigen['nombre'],
					$codigoProvinciaOrigen,  $provinciaOrigen['nombre'], $this->f034['agc_id'], $this->f034['agc_nm'], $codigoPaisDestino['id_localizacion'],
					$codigoPaisDestino['nombre'], $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'], $this->f034['dtf_spm_de'], $codigoPaisEmbarque['id_localizacion'], $codigoPaisEmbarque['nombre'], $codigoPuertoEmbarque['id_puerto'],
					$codigoPuertoEmbarque['nombre_puerto'], $this->f034['dcd_trsp_way_cd'], $this->f034['dcd_trsp_way_nm'], $this->f034['bdnm'], $this->f034['trip_num'], $this->f034['addt_inf'], $descuento, $this->f034['disc_motv'],
					$this->f034['aprb_nm'], $this->f034['aprb_odty_nm'], $this->f034['aprb_rmk'], $ciudadTransito, $lugarInspeccion);
			
			$codigoVerificacion = '420';
		
			$controladorFitosanitarioExportacion->eliminaTransitoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'));
			$controladorFitosanitarioExportacion->eliminarAreasFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'));
			$controladorFitosanitarioExportacion->eliminarProductosFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'));
			$controladorFitosanitarioExportacion->eliminarExportadoresFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'));
		}
		
		echo OUT_MSG . 'Datos de cabecera de Fitosanitario insertados';
		
		
		for($s = 0; $s < count ($this->f034ex); $s++){
		
			$idExportador = $this->f034ex[$s]['expr_sn'];
			$identificadorExportador = $this->f034ex[$s]['expr_idt_no'];
		
			$qExportador = $controladorRegistroOperador -> buscarOperador($conexionGUIA, $identificadorExportador);
			$exportador = pg_fetch_assoc($qExportador);
		
			$idFitosanitarioExportador = $controladorFitosanitarioExportacion->guardarFitosanitarioExportacionExportadores($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $this->f034ex[$s]['req_no'], $this->f034ex[$s]['expr_idt_cl_cd'],
					$this->f034ex[$s]['expr_idt_no_type_cd'], $this->f034ex[$s]['expr_idt_no'], $this->f034ex[$s]['expr_nm'], $this->f034ex[$s]['expr_ad']);
		
			$idFitosanitarioExportador = pg_fetch_result($idFitosanitarioExportador, 0, 'id_fitosanitario_exportador');
		
			$productos = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($idVue,'tn_agr_034_pd', $idExportador);	

			echo OUT_MSG . 'Datos de exportador de Fitosanitario insertados';
		
			for($t= 0; $t < count ($productos); $t++){		
		
				$partidaArancelariaVUE = $productos[$t]['hc'];
				$codigoProductoVUE = $productos[$t]['prdt_cd'];
				$codigoProducto = $productos[$t]['prdt_sn'];
		
		
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
				$producto = pg_fetch_assoc($qProductoGUIA);
				
				//Obtener si el producto tiene o no exoneración.
				$productoUnidadCobro = pg_fetch_assoc($cff->obtenerUnidadMedidaCobro($conexionGUIA, $producto['id_producto'], 'Fitosanitario'));
				$exoneracion = ($productoUnidadCobro['exoneracion']== 't'?'SI':'NO');
		
				//Fecha de tratamiento
				$fechaTratamiento = (isset($productos[$t]['dfct_slz_prcg_de'])? "'".$productos[$t]['dfct_slz_prcg_de']."'" : 'NULL');
					
				//Temperatura
				$temperaturaTratamiento = (isset($productos[$t]['dfct_slz_prcg_tp'])? $productos[$t]['dfct_slz_prcg_tp'] : 0);
					
				$qIdProducto = $controladorFitosanitarioExportacion->guardarFitosanitarioExportacionProductos($conexionGUIA, $idFitosanitarioExportador, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $productos[$t]['req_no'], $productos[$t]['hc'], $productos[$t]['prdt_cd'], $producto['id_producto'], $producto['nombre_comun'],
						$productos[$t]['chrg_no'], $productos[$t]['chrg_ut'], $productos[$t]['prdt_nwt'], $productos[$t]['prdt_nwt_ut'], $productos[$t]['prdt_grwg'], $productos[$t]['prdt_grwg_ut'], $productos[$t]['prdt_pck_qt'], $productos[$t]['prdt_pck_ut'], $productos[$t]['prcg_type_cd'],
						$productos[$t]['prcg_type_desc'], $productos[$t]['prcg_nm_cd'], $productos[$t]['prcg_nm_desc'],$productos[$t]['dfct_slz_prcg_durt'], $productos[$t]['dfct_slz_prcg_durt_ut'], $temperaturaTratamiento, $productos[$t]['dfct_slz_prcg_tp_ut'], $productos[$t]['dfct_slz_prcg_cct'], $fechaTratamiento,
						$productos[$t]['dfct_slz_prcg_chm_prdt_nm'], $productos[$t]['addt_inf'], $productos[$t]['pht_req'], $exoneracion);
		
				$idProducto = pg_fetch_result($qIdProducto, 0, 'id_fitosanitario_producto');
				
				$partidaArancelaria =  substr($partidaArancelariaVUE,0,10);
				
				if($productoMusaceas && ($partidaArancelaria == '0803101000' || $partidaArancelaria == '0803901110' || $partidaArancelaria == '0803901200' || $partidaArancelaria == '0803901900' || $partidaArancelaria == '0803901190')){
					$productoMusaceas = false;
					$banderaFito = true;
					$arrayProductoFinanciero[] = array(idProducto => $producto['id_producto'] , nombreProducto => $producto['nombre_comun'], cantidadCobro=> 1, unidadCobro => $productos[$t]['chrg_ut'], exoneracion=> $exoneracion);
					
					//TODO realizar inserción de nuevo operador por proceso automatico con conexion a web services
					
				}else if($productoOrnamentales && ($partidaArancelaria == '0603110000' || $partidaArancelaria == '0603121000' || $partidaArancelaria == '0603129000' || $partidaArancelaria == '0603130000' 
										|| $partidaArancelaria == '0603141000' || $partidaArancelaria == '0603149000' || $partidaArancelaria == '0603150000' || $partidaArancelaria == '0603191000' 
										|| $partidaArancelaria == '0603192000' || $partidaArancelaria == '0603193000' || $partidaArancelaria == '0603194000' || $partidaArancelaria == '0603199010' 
										|| $partidaArancelaria == '0603199090' || $partidaArancelaria == '0603900000' || $partidaArancelaria == '0604200000' || $partidaArancelaria == '0604900000')){
					$productoOrnamentales = false;
					$banderaFito = true;
					$arrayProductoFinanciero[] = array(idProducto => $producto['id_producto'] , nombreProducto => $producto['nombre_comun'], cantidadCobro=> 1, unidadCobro => $productos[$t]['chrg_ut'], exoneracion=> $exoneracion);
				}else if($partidaArancelaria == '0804502000'){
					$productoMango = true;
					$arrayProductoFinanciero[] = array(idProducto => $producto['id_producto'] , nombreProducto => $producto['nombre_comun'], cantidadCobro=> $productos[$t]['chrg_no'], unidadCobro => $productos[$t]['chrg_ut'], exoneracion=> $exoneracion);
				}else{
					if(!$banderaFito)
						$arrayProductoFinanciero[] = array(idProducto => $producto['id_producto'] , nombreProducto => $producto['nombre_comun'], cantidadCobro=> $productos[$t]['chrg_no'], unidadCobro => $productos[$t]['chrg_ut'], exoneracion=> $exoneracion);
				}
				
				$arrayProductoPrograma[] = $producto['programa'];
				$arrayProductoExoneracion[] = $exoneracion;
						
				$areas = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($idVue,'tn_agr_034_dt', $idExportador, $codigoProducto);
				
				$provinciaArea = array();
				
				echo OUT_MSG . 'Datos de producto de Fitosanitario insertados';
				
				for ($u = 0; $u < count ($areas); $u++ ){
	   
				     if($partidaArancelaria=='0803901110' || $partidaArancelaria=='0803901190'){
				      
				      	$controladorFitosanitarioExportacion->guardarFitosanitarioAreasExportadores($conexionGUIA, $idFitosanitarioExportador, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $idProducto, $areas[$u]['req_no'], 0, 'PARTIDA DE BANANO', $areas[$u]['req_aucp_no']);
				     
				     }else{
				      
					    $codigoAreaVUE = $areas[$u]['agr_area_desc'];
					   
					    $qAreasCodigo = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $codigoAreaVUE);
					    $areaCodigo = pg_fetch_assoc($qAreasCodigo);
					   
					    $controladorFitosanitarioExportacion->guardarFitosanitarioAreasExportadores($conexionGUIA, $idFitosanitarioExportador, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $idProducto, $areas[$u]['req_no'], $areaCodigo['id_area'], $areas[$u]['agr_area_desc'], $areas[$u]['req_aucp_no']);
				     }
				     
				     echo OUT_MSG . 'Datos de área de Fitosanitario insertados';
				    
				}
			}
		}
		
		if($poseeTransito){
		
			for($v = 0; $v < count ($this->f034tr); $v++){
		
				$codigoPaisDestinoTransito = $this->f034tr[$v]['trsp_cntry_cd'];
				$codigoPuertoDestinoTransito = $this->f034tr[$v]['spm_port_cd'];
		
				$qPaisTransito = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoTransito);
				$paisTransito = pg_fetch_assoc($qPaisTransito);
				
				
				$codigoPuertoTransito = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $codigoPuertoDestinoTransito); 
				$puertoTransito = pg_fetch_assoc($codigoPuertoTransito);
				
				$controladorFitosanitarioExportacion->guardarFitosanitarioAreasTrasportes($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $this->f034tr[$v]['req_no'], $this->f034tr[$v]['trsp_via_cd'], $this->f034tr[$v]['trsp_via_nm'], $paisTransito['id_localizacion'], $paisTransito['nombre'], $puertoTransito['id_puerto'], $puertoTransito['nombre_puerto'], $this->f034tr[$v]['fit_req']);
			}
			
			echo OUT_MSG . 'Datos de tránsito de Fitosanitario insertados';
		}
		
		$arrayProductoProgramaSinRepetidos = array_unique($arrayProductoPrograma);
		$arrayProductoExoneracionSinRepetidos = array_unique($arrayProductoExoneracion);
		
		if(count($arrayProductoProgramaSinRepetidos) == 1){
			if($arrayProductoProgramaSinRepetidos[0] == 'SI'){
				$nombreProvincia = $codigoProvincia['nombre'];
			}else{
				$qCodigoAreaInspeccion = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $lugarInspeccion);
				$codigoAreaInspeccion = pg_fetch_assoc($qCodigoAreaInspeccion);
				$nombreProvincia = $codigoAreaInspeccion['provincia'];
			}
		}else{
			$qCodigoAreaInspeccion = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $lugarInspeccion);
			$codigoAreaInspeccion = pg_fetch_assoc($qCodigoAreaInspeccion);				
			$nombreProvincia = $codigoAreaInspeccion['provincia'];			
		}
		
		
		
		/*if(!$productoOrnamentales || !$productoMusaceas){
			$nombreProvincia = $codigoProvincia['nombre'];
		}else{
			$qCodigoAreaInspeccion = $controladorRegistroOperador->buscarAreaXCodigo($conexionGUIA, $lugarInspeccion);
			$codigoAreaInspeccion = pg_fetch_assoc($qCodigoAreaInspeccion);
			
			$nombreProvincia = $codigoAreaInspeccion['provincia'];
		}*/
		//COMIENZA LO BUENO
		//$arrayAreasSinRpetidos = array_unique($provinciaArea);
		$provincia = pg_fetch_assoc($controladorCatalogos->obtenerIdLocalizacion($conexionGUIA, $nombreProvincia, 'PROVINCIAS'));
		$controladorFitosanitarioExportacion->actualizarProvinciaRevisionFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $provincia['nombre'], $provincia['id_localizacion']);
		
		//Obtener documentos adjuntos 
		$documentosAdjuntos = pg_num_rows($controladorVUE->obtenerDocumentoAdjuntos($idVue, $codigoVerificacion));
		
		if($documentosAdjuntos == 0){
			$poseeAnexo = false;
		}else{
			$poseeAnexo = true;
		}
				
		
		
		if($codigoVerificacion == '110'){
			if(count($arrayProductoProgramaSinRepetidos) == 1){
					
				if($arrayProductoProgramaSinRepetidos[0] == 'SI'){
					if(!$poseeAnexo){ //no tiene anexo
						
						if(count($arrayProductoExoneracionSinRepetidos) == 1){
							if($arrayProductoExoneracionSinRepetidos[0] == 'SI'){
								$procesoPago = false;
							}else{
								$procesoPago = true;
							}
						}else{
							$procesoPago = true;
						}
						
						if($descuento == 'SI' || $productoMango){
							if($procesoPago){
								$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), 'pago', 'pago', 'Solicitud con descuento.');
							}else{
								$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), 'Aprobado', 'verificacion', 'Aprobado');
								$controladorFitosanitarioExportacion->actualizarFechasAprobacionFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'));
								$controladorVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ', '320', '21', $this->f034['req_no'], 'Por atender');
							}
							
						}else{
														
							if($procesoPago){
								
								$cfa = new ControladorFinancieroAutomatico();
								$itemTarifario = array();
								$totalOrden = 0;
									
								foreach ($arrayProductoFinanciero as $productoFinanciero){
										
									$productoUnidadCobro = pg_fetch_assoc($cff->obtenerUnidadMedidaCobro($conexionGUIA, $productoFinanciero['idProducto'], 'Fitosanitario'));
										
									$cantidadProducto = $productoFinanciero['cantidadCobro'];
									$cantidadProductoTarifario = $productoUnidadCobro['unidad'];
									$valorCantidadProductoTarifario = $productoUnidadCobro['valor'];
								
									if($productoUnidadCobro['cobro_exceso'] == 'No'){
										$valorProductoSinExceso = $cantidadProducto * $valorCantidadProductoTarifario;
										$valorProductoSinExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProductoSinExceso: 0;
									}else{
										$valorProductoSinExceso  = $valorCantidadProductoTarifario;
										$valorProductoSinExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProductoSinExceso: 0;
											
										$cantidadProductoExceso = $cantidadProducto - $cantidadProductoTarifario;
								
										if($cantidadProductoExceso != 0){
											$itemExceso = pg_fetch_assoc($cff->obtenerServicio($conexionGUIA, $productoUnidadCobro['id_servicio_exceso']));
											$valorProdutoConExceso = round(($cantidadProductoExceso*$itemExceso['valor'])/$itemExceso['unidad'],2);
											$valorProdutoConExceso = $productoFinanciero['exoneracion'] == 'NO'? $valorProdutoConExceso: 0;
											$itemTarifario[] = array(idServicio => $itemExceso['id_servicio'], conceptoServicio => $itemExceso['concepto'], cantidad => $cantidadProductoExceso, valorUnitario => $itemExceso['valor'], descuento => '0', iva => '0', total => $valorProdutoConExceso);
										}
									}
										
									$itemTarifario[] = array(idServicio => $productoUnidadCobro['id_servicio'], conceptoServicio => $productoUnidadCobro['concepto'], cantidad => $cantidadProductoTarifario, valorUnitario => $valorCantidadProductoTarifario, descuento => '0', iva => '0', total => $valorProductoSinExceso);
										
									$totalOrden += $valorProductoSinExceso + $valorProdutoConExceso;
								}
									
								$idFinancieroCabecera = pg_fetch_result($cfa->guardarFinancieroAutomaticoCabecera($conexionGUIA, $totalOrden, $idVue, 'FitosanitarioExportacion'), 0, 'id_financiero_cabecera');
									
								foreach ($itemTarifario as $item){
									$cfa->guardarFinancieroAutomaticoDetalle($conexionGUIA, $idFinancieroCabecera, $item['idServicio'], $item['conceptoServicio'], $item['cantidad'], $item['valorUnitario'], $item['descuento'], $item['iva'], $item['total']);
								}
									
								$cfa->actualizarEstadoFinancieroAutomaticoCabecera($conexionGUIA, $idFinancieroCabecera, 'Por atender');
								$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), 'verificacionAutomatica', 'automaticoPago', 'Imposición de tasa automática.');
									
							}else{
								$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), 'Aprobado', 'verificacion', 'Aprobado');
								$controladorFitosanitarioExportacion->actualizarFechasAprobacionFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'));
								$controladorVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ', '320', '21', $this->f034['req_no'], 'Por atender');
							}
			
							
						}
					}else{
						$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), 'enviado', 'enviado', 'Solicitud posee anexos.');
					}
				}else{
					$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), 'enviado', 'enviado', 'Solicitud con productos sin programa.');
				}
					
			}else{
				$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), 'enviado', 'enviado', 'Solicitud con productos con programa y sin programa.');
			}
		}else{
			$qFitosanitarioExportacion = $controladorFitosanitarioExportacion->obtenerCabeceraFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'));
			$fitosanitarioExportacion = pg_fetch_assoc($qFitosanitarioExportacion);
			
			$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $fitosanitarioExportacion['estado_anterior'], $fitosanitarioExportacion['estado_anterior'], $fitosanitarioExportacion['observacion']);
		}		
		
		$identificadorSolicitante = $this->f034['dclr_idt_no'];
		$tipoIdentificacion = $this->f034['dclr_cl_cd'];
		$razonSocial = $this->f034['dclr_nole'];
		$direccionSolicitante = $this->f034['dclr_ad'];
		$telefonoSolicitante = $this->f034['dclr_tel_no'];
		$correoElectronicoSolicitante = $this->f034['dclr_em'];
		
		switch ($tipoIdentificacion){
			case '001':
				$tipoIdentificacion= '04';
			break;			
			case '002':
				$tipoIdentificacion= '05';
			break;				
			case '003':
				$tipoIdentificacion= '06';
			break;
		}
		
		$controladorFitosanitarioExportacion->guardarSolicitanteFitosanitarioExportacion($conexionGUIA, $identificadorSolicitante, $tipoIdentificacion, $razonSocial, $direccionSolicitante, $telefonoSolicitante, $correoElectronicoSolicitante);
		//FIN DE LOS BUENO
		
		return true;
	}

	public function insertarDocumentosAdjuntosGUIA($documentos){
		
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		$conexionGUIA = new Conexion();
		
		$idFitosanitarioExportacion = pg_fetch_result($controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']), 0, 'id_fitosanitario_exportacion');
		
		$controladorFitosanitarioExportacion->eliminarArchivosAdjuntosFitosanitarioExportacion($conexionGUIA, $idFitosanitarioExportacion, $this->f034['req_no']);
		
		if(pg_num_rows($documentos)!= 0){
		
			while($documentosAdjuntos = pg_fetch_assoc($documentos)){
				$ruta= explode('DEV/', $documentosAdjuntos['ruta']);
				//$ruta= explode('PROD/', $documentosAdjuntos['ruta']);
				$controladorFitosanitarioExportacion ->guardarFitosanitarioExportacionDocumentosAdjuntos($conexionGUIA, $idFitosanitarioExportacion, $documentosAdjuntos['nombre'], $ruta[1], 'SV', $this->f034['req_no']);
			}
		}else{
			echo OUT_MSG . 'La solicitud Fitosanitaria de exportación no posee documentos adjuntos.';
		}
		
		echo OUT_MSG . 'Documentos adjuntos Fitosanitarios de Exportación insertados.';
			
		return true;
	}

	public function actualizarDatosEnGUIA(){
		
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		$controladorCatalogos = new ControladorCatalogos();
		$controladorVUE = new ControladorVUE();
		$conexionGUIA = new Conexion();
		
		//Actualización de nombre importador, dirección importador, puerto destino, información adicional
		$nombreImportador = str_replace("'", "''", $this->f034['impr_nm']);
		$direccionImportador = $this->f034['impr_ad'];
		$puertoDestino = $this->f034['dst_port_cd'];
		$informacionAdicional = $this->f034['addt_inf'];
		
		$qCodigoPuertoDestino = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $puertoDestino); //Puerto de destino
		$codigoPuertoDestino = pg_fetch_assoc($qCodigoPuertoDestino);
		
		$idFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']);
		
		$controladorFitosanitarioExportacion->modificarCabeceraFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $nombreImportador, $direccionImportador, $codigoPuertoDestino['id_puerto'], $codigoPuertoDestino['nombre_puerto'], $informacionAdicional);
		
		echo OUT_MSG . 'Datos de cabecera Fitosanitarios de Exportación actualizados.';
		
		for($i = 0; $i < count($this->f034ex); $i++ ){
			
			$idExportador = $this->f034ex[$i]['expr_sn'];
			$identificadorExportador = $this->f034ex[$i]['expr_idt_no'];
			
			$fitosanitarioExportador = pg_fetch_assoc($controladorFitosanitarioExportacion->buscarFitosanitarioExportacionExportador($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $identificadorExportador));
						
			//Actualización producto -> información adicional
			$productos = $controladorVUE->obtenerDatosExtrasFitosanitarioExportacion($this->f034['req_no'],'tn_agr_034_pd', $idExportador);
			
			for($k= 0; $k < count ($productos); $k++){
			
				$informacionAdicional = $productos[$k]['addt_inf'];			
				$partidaArancelariaVUE = $productos[$k]['hc'];
				$codigoProductoVUE = $productos[$k]['prdt_cd'];			
			
				$qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE);
				$producto = pg_fetch_assoc($qProductoGUIA);
			
				$controladorFitosanitarioExportacion->modificarProductoFitosanitarioExportacion($conexionGUIA, pg_fetch_result($idFitosanitarioExportacion, 0, 'id_fitosanitario_exportacion'), $producto['id_producto'], $fitosanitarioExportador['id_fitosanitario_exportador'], $informacionAdicional);
			
			}
			
			echo OUT_MSG . 'Datos de producto Fitosanitarios de Exportación actualizados.';
		}		
		
		return true;
	}

	public function recaudacionTasa($recaudacionTasas){
		
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$controladorFinanciero = new ControladorFinanciero();
		$controladorCatalogos = new ControladorCatalogos();
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		$resultado = array();		
		$datosTasa = pg_fetch_assoc($recaudacionTasas);
		$verificarProceso = false;
		
		$qIdFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']);
		$idFitosanitarioExportacion = pg_fetch_assoc($qIdFitosanitarioExportacion);
						
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitud($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], 'FitosanitarioExportacion', 'Financiero');
		$financiero = pg_fetch_assoc($qFinanciero);
		
		if($datosTasa['banco']==''){
			
			$saldoDisponible = pg_fetch_assoc($controladorFinanciero->obtenerMaxSaldo($conexionGUIA, $idFitosanitarioExportacion['numero_identificacion_solicitante'], 'saldoVue'));
				
			if($saldoDisponible['saldo_disponible']>= $datosTasa['monto_recaudado']){			
				$banco = 'saldoVue';
				$tipoProceso = 'comprobanteFactura';
				$idBanco = '0';
				$verificarProceso = true;
				$resultado[0] = SOLICITUD_APROBADA;
				$resultado[1] = 'Continua con proceso de aprobación. ';
			}else{
				$resultado[0] = ERROR_TAREA;
				$resultado[1] = 'Proceso en espera hasta la confirmación de saldo. ';
			}
		}else if ($datosTasa['banco'] != ''){
			$codigoBanco = trim($datosTasa['banco'], '0');					
			$datosBanco = pg_fetch_assoc($controladorCatalogos->obtenerDatosBancarioPorCodigoVue($conexionGUIA, $codigoBanco));
			$banco = $datosBanco['nombre'];
			$idBanco = $datosBanco['id_banco'];
			$tipoProceso = 'factura';
			$verificarProceso = true;
			$resultado[0] = SOLICITUD_APROBADA;
			$resultado[1] = 'Continua con proceso de aprobación. ';
		}else{
			$resultado[0] = ERROR_DE_VALIDACION;
			$resultado[1] = 'No se reconoce el proceso de verificación de pago. ';
		}

		if($verificarProceso){
			
			if($financiero['monto'] == $datosTasa['monto_recaudado']){
			
				//if($datosTasa['banco']!='' || $datosTasa['monto_recaudado']!='' || $datosTasa['fecha_contable']!=''){
				$controladorRevisionSolicitudesVUE->guardarInspeccionFinanciero($conexionGUIA, $financiero['id_financiero'], $financiero['identificador_inspector'], 'aprobado', $datosTasa['fecha_recaudacion'], $idBanco, $datosTasa['monto_recaudado'], $banco, $datosTasa['numero_orden_vue'], $datosTasa['numero_orden_vue']);
				$controladorFinanciero->actualizarNumeroOrdenSolicitudVue($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], $financiero['id_grupo'], 'FitosanitarioExportacion', $datosTasa['numero_orden_vue']);
				//}
				
				$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], 'aprobado', 'verificacionVUE', 'Solicitud aprobada.');
				$controladorFitosanitarioExportacion->actualizarFechasAprobacionFitosanitarioExportacion($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion']);
				
				$cfa->actualizarTipoProcesoFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f034['req_no'], $tipoProceso);
			
			}else{
				$resultado = array();
				$resultado[0] = ERROR_DE_VALIDACION;
				$resultado[1] = 'Error en diferenciación de valores cancelados. ';
			}
			
		}
				
		return $resultado;
	}

	public function cancelar(){
		
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		$conexionGUIA = new Conexion();
		
		$qIdFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']);
		
		if(pg_num_rows($qIdFitosanitarioExportacion)!=0){
			$idFitosanitarioExportacion = pg_fetch_assoc($qIdFitosanitarioExportacion);		
			$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], 'cancelado', $idFitosanitarioExportacion['estado_anterior'], 'Solicitud cancelada por parte de OCE.');
		}
		
		return true;
	}

	public function anular(){
		
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		$qIdFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']);
		$idFitosanitarioExportacion = pg_fetch_assoc($qIdFitosanitarioExportacion);
		
		$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], 'anulado', $idFitosanitarioExportacion['estado_anterior'], 'Solicitud anulada por parte de OCE.');
		
		$cfa->actualizarEstadoFinancieroAutomaticoCabeceraPorIdVue($conexionGUIA, $this->f034['req_no'], 'Anulado');
		
		return true;
	}
	
	public function reversoSolicitud(){
				
		$controladorFitosanitarioExportacion = new ControladorFitosanitarioExportacion();
		$controladorRevisionSolicitudesVUE = new ControladorRevisionSolicitudesVUE();
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
		
		$qIdFitosanitarioExportacion = $controladorFitosanitarioExportacion->buscarFitosanitarioExportacionVUE($conexionGUIA, $this->f034['req_no']);
		$idFitosanitarioExportacion = pg_fetch_assoc($qIdFitosanitarioExportacion);
		
		$qFinanciero = $controladorRevisionSolicitudesVUE->buscarIdImposicionTasaXSolicitudReverso($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], 'FitosanitarioExportacion', 'Financiero');		
		$financiero = pg_fetch_assoc($qFinanciero);
		
		$controladorRevisionSolicitudesVUE->actualizarInspeccionFinancieroMontoRecaudado($conexionGUIA, $financiero['id_financiero']);
		
		$controladorFitosanitarioExportacion->actualizarEstadoFitosanitarioExportacion($conexionGUIA, $idFitosanitarioExportacion['id_fitosanitario_exportacion'], 'reverso', $idFitosanitarioExportacion['estado_anterior'], 'Solicitud en reverso por VUE.');
		
		$cfa->actualizarEstadoFinancieroAutomaticoCabeceraPorIdVue($conexionGUIA, $this->f034['req_no'], 'Reverso');
		
		return true;
	}
	
	public function generarFacturaProcesoAutomatico(){
		
		$cfa = new ControladorFinancieroAutomatico();
		$conexionGUIA = new Conexion();
				
		echo OUT_MSG . 'Actualización de datos de tasas de recaudación';
		
		$cfa->actualizarEstadoFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f034['req_no'], 'Por atender');
		$cfa->actualizarFechaFacturaFinancieroAutomaticoCabecera($conexionGUIA, $this->f034['req_no']);
				
		echo OUT_MSG . 'Solicitud Fitosanitaria enviada a verificación de pago.';
				
		return true;
	}
}

class TransitoInternacional extends FormularioVUE{
    
    public $f061;
    public $f061pd = array();
    
    public function __construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud, $coneccionVUE){
        
        $camposObligatorios = array(
            'cabecera' => array(
                'IMPR_NM',
                'IMPR_RPST_NM',
                'IMP_PRVHC_NM',
                'IMPR_CUTY_NM',
                'IMPR_PRQI_NM',
                'IMPR_AD',
                'IMPR_TEL_NO',
                'IMPR_CEL_NO',
                'IMPR_EM'),
            
            'productos' => array(
                'REQ_NO',
                'HC',
                'PRDT_NM',
                'PRDT_CD',
                'PRDT_QT',
                'PRDT_MES',
                'PRDT_NWT',
                'PRDT_NWT_UT')
        );
        
        //Trayendo los datos de cabecera del formulario 101-061
        
        $this-> f061 = pg_fetch_assoc($coneccionVUE->ejecutarConsulta("SELECT *
				FROM vue_gateway.tn_agr_061
				WHERE REQ_NO = '$numeroDeSolicitud'"));
        
        //Trayendo los datos de detalle del formulario 101-002
        
        $c_f061pd = $coneccionVUE ->ejecutarConsulta(" SELECT *
				FROM vue_gateway.tn_agr_061_pd
				WHERE REQ_NO = '$numeroDeSolicitud'");
        
        while ($fila = pg_fetch_assoc($c_f061pd)){
            $this-> f061pd[] = $fila;
        }
        
        parent::__construct($formulario, $id, $codigoDeProcesamiento, $codigoDeVerificacion, $numeroDeSolicitud,$camposObligatorios);
    }
    
    public function validarDatosFormulario(){
        echo  PRO_MSG. 'validando formulario 101-061';
        
        $certificadoImpValido = true;
        $solicitudEsValida = true;
        $resultado = array();
        $subTipoProducto = array();
        $condicion = true;
        
        //parent::validarCamposObligatorios($f002,'cabecera');
        //parent::validarCamposObligatorios($f002_pd,'productos');
        
        
        $controladorRegistroOperador = new ControladorRegistroOperador();
        $controladorRequisitos = new ControladorRequisitos();
        $controladorCatalogos = new ControladorCatalogos();
        $conexionGUIA = new Conexion();
        
        $identificadorImportador = $this->f061['impr_idt_no'];
        
        $tipoProductoVUE = strtoupper(($this->f061['prdt_type_nm']));
        $tipoProductoGUIA = ($tipoProductoVUE == 'VEGETAL'?'SV': 'No definido');
        
        for($j = 0 ; $j < count($this->f061['req_no']); $j++){
            
            $tipoFormularioVUE = $this->f061['prdt_type_cd'];
            
            $area = ($tipoFormularioVUE == '1'?'SV':'No definido'); //'0002'
            $idActividad  = pg_fetch_result($controladorCatalogos -> buscarIdOperacion($conexionGUIA, $area, 'Comercializador directo'),0,'id_tipo_operacion'); //Buscar actividad del importador segun el tipo de solicitud.
            
            
            //Validación país de origen
            $codigoPaisOrigenVUE = $this->f061['orgn_ntn_cd'];
            $qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisOrigenVUE);
            $codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
            
            if( pg_num_rows($qCodigoPaisOrigen) == 0 ){ //Validación del pais de origen
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El pais de origen '.$this->f061['orgn_ntn_nm'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'El pais de origen '.$this->f061['orgn_ntn_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
            
            
            //Validación ciudad solicitud
            $codigoCiudadVUE = $this->f061['req_city_nm'];
            $qCodigoCiudad = $controladorCatalogos->buscarLocalizacionXNombre($conexionGUIA, $codigoCiudadVUE);
            $codigoCiudad = pg_fetch_assoc($qCodigoCiudad);
            
            if( pg_num_rows($qCodigoCiudad) == 0 ){ //Validación del pais de origen
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'La ciudad '.$this->f061['req_city_nm'].' no se encuentra registrada en agrocalidad';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'La ciudad '.$this->f061['req_city_nm'].' no se encuentra registrada en agrocalidad';
                break;
            }
            
            
            //Validación codigo país de procedencia
            $codigoPaisProcedenciaVUE = $this->f061['pdc_ntn_cd'];
            $qCodigoPaisProcedencia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisProcedenciaVUE); //Codigo del pais de procedencia
            $codigoPaisProcedencia = pg_fetch_assoc($qCodigoPaisProcedencia);
            
            if( pg_num_rows($qCodigoPaisProcedencia) == 0 ){ //Validación del pais de procedencia
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El pais de procedencia '.$this->f061['pdc_ntn_nm'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'El pais de procedencia '.$this->f061['pdc_ntn_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
            
            //Validación codigo país de destino
            $codigoPaisDestinoVUE = $this->f061['dst_ntn_cd'];
            $qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoVUE); //Codigo del pais de Destino
            $codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
            
            if( pg_num_rows($qCodigoPaisDestino) == 0 ){ //Validación del pais de Destino
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El pais de destino '.$this->f061['dst_ntn_nm'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'El pais de destino '.$this->f061['dst_ntn_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
            
            //Puerto de ingreso
            $qCodigoPuertoIngreso = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f061['enty_pnt_cd']); //Obtiene codigo de puerto en GUIA ingreso
            $codigoPuertoIngreso = pg_fetch_assoc($qCodigoPuertoIngreso);
            
            if( pg_num_rows($qCodigoPuertoIngreso) == 0 ){ //Validación del puerto de ingreso
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El puerto de ingreso '.$this->f061['enty_pnt_nm'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'El puerto de ingreso '.$this->f061['enty_pnt_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
            
            //Puerto de sal
            $qCodigoPuertoSalida = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f061['dspt_sttn_cd']); //Obtiene codigo de puerto en GUIA salida
            $codigoPuertoSalida = pg_fetch_assoc($qCodigoPuertoSalida);
            
            if( pg_num_rows($qCodigoPuertoSalida) == 0 ){ //Validación del puerto de salida
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El puerto de salida '.$this->f061['dspt_sttn_cd'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'El puerto de salida '.$this->f061['dspt_sttn_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
            
            if($tipoProductoGUIA != $area){ // Validación tipo de producto sea el mismo del tipo de solicitud.
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El tipo de producto '.$this->f061['prdt_type_nm'].' no corresponde al tipo de solicitud seleccionada. ';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'El tipo de producto '.$this->f061['prdt_type_nm'].' no corresponde al tipo de solicitud seleccionada. ';
                break;
            }
            
            $productoDuplicado = parent::verificarProductoRepetido($this->f061pd);
            
            if($productoDuplicado){
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG.  'No se permite agregar el mismo producto dos veces.';
                $resultado[0] = SUBSANACION_REQUERIDA;
                $resultado[1] = 'No se permite agregar el mismo producto dos veces.';
                break;
            }
            
            
            
        }
        
        if($certificadoImpValido){
            for ($i = 0; $i < count ($this->f061pd); $i++) {
                
                $partidaArancelariaVUE = $this->f061pd[$i]['hc'];
                $codigoProductoVUE = $this->f061pd[$i]['prdt_cd'];
                
                if(($area == 'SV') && strlen($codigoProductoVUE) != '5'){ // Validación Area igual SV el producto debe tener un codigo de 5 digitos
                    $solicitudEsValida = false;
                    $certificadoImpValido = false;
                    echo IN_MSG. 'El producto '.$this->f061pd[$i]['prdt_nm'].' no es correcto, se debe seleccionar productos con código de 5 dígitos.';
                    $resultado[0] = SUBSANACION_REQUERIDA;
                    $resultado[1] = 'El producto '.$this->f061pd[$i]['prdt_nm'].' no es correcto, se debe seleccionar productos con código de 5 dígitos.';
                    break;
                }
                
                //Validación de producto registrado en GUIA
                $qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $area);
                
                
                if( pg_num_rows($qProductoGUIA) == 0 ){ // Busqueda del producto en base de datos GUIA.
                    
                    $solicitudEsValida = false;
                    echo IN_MSG. 'El producto '.$this->f061pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
                    $resultado[0] = SUBSANACION_REQUERIDA;
                    $resultado[1] = 'El producto '.$this->f061pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
                    break;
                }
                
                $producto = pg_fetch_assoc($qProductoGUIA);
                
                $subTipoProducto[] = $producto['id_subtipo_producto'];
                
                //Validar la unidad de peso en KG
                
                $unidadPesoProductoVUE = $this->f061pd[$i]['prdt_nwt_ut'];
                
                if($unidadPesoProductoVUE != 'KG'){
                    $solicitudEsValida = false;
                    echo IN_MSG. 'La unidad de peso ingresada para el producto debe estar en KG.';
                    $resultado[0] = SUBSANACION_REQUERIDA;
                    $resultado[1] = 'La unidad de peso ingresada para el producto debe estar en KG.';
                    break;
                }
                
                /****/
                $codigoUnidadMedidaCantidadVUE = $this->f061pd[$i]['prdt_mes'];
                    
                $qUnidadMedidaCantidad = $controladorCatalogos->obtenerIdUnidadMedida($conexionGUIA, $codigoUnidadMedidaCantidadVUE); //Codigo de unidad de medida cantidad

                if( pg_num_rows($qUnidadMedidaCantidad) == 0 ){ // Busqueda del producto en base de datos GUIA.
                    
                    $solicitudEsValida = false;
                    echo IN_MSG. 'La unidad de medida de cantidad del producto '.$this->f061pd[$i]['prdt_mes'].' no se encuentra registrada en Agrocalidad. ';
                    $resultado[0] = SUBSANACION_REQUERIDA;
                    $resultado[1] = 'La unidad de medida de cantidad del producto '.$this->f061pd[$i]['prdt_mes'].' no se encuentra registrada en Agrocalidad. ';
                    break;
                }
                                
                /****/
                
                
                //Valida que el operador tenga una operacion de comercializador directo con el producto indicado
                
                $qOperacionOperador= $controladorRegistroOperador -> buscarOperadorProductoActividad($conexionGUIA, $identificadorImportador, $producto['id_producto'],$idActividad,'registrado');
                
                if( pg_num_rows($qOperacionOperador) == 0 ){
                    
                    $solicitudEsValida = false;
                    echo IN_MSG. 'El importador '.$identificadorImportador.' no se encuentra registrado como comercializador para el producto '.$this->f061pd[$i]['prdt_nm'];
                    $resultado[0] = SUBSANACION_REQUERIDA;
                    $resultado[1] = 'El importador '.$identificadorImportador.' no se encuentra registrado como comercializador para el producto '.$this->f061pd[$i]['prdt_nm'];
                    break;
                }
                
                
                //Validar si el producto esta activo para un pais y actividad
                $qEstadoProductoPais = $controladorRequisitos ->consultarEstadoProductoPaisRequisito($conexionGUIA, $codigoPaisOrigen['id_localizacion'], $producto['id_producto'], 'Tránsito', 'activo');  //estado producto pais requisito
                
                if( pg_num_rows($qEstadoProductoPais) == 0 ){
                    $solicitudEsValida = false;
                    echo IN_MSG. 'El producto '.$this->f061pd[$i]['prdt_nm'].' no tiene habilitado requisitos de tránsito al pais '.$this->f061['org_ntn_nm'];
                    $resultado[0] = SOLICITUD_NO_APROBADA;
                    $resultado[1] = 'El producto '.$this->f061pd[$i]['prdt_nm'].' no tiene habilitado requisitos de tránsito al pais '.$this->f061['org_ntn_nm'];
                    break;
                }
                
            }
        }
        
        
        if ($solicitudEsValida)
            return true;
            else
                return $resultado;
    }
    
    /*public function validarActualizacionDeDatos(){
    
    echo  PRO_MSG. 'validando actualizacion de formulario ';
    $resultado = array();
    
    $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
    $resultado[1] = 'No se permite modificar los datos del documento de tránsito internacional.';
    
    return $resultado;
    
    }*/
    
    public function validarActualizacionDeDatos(){//<=implementar!!!!!!!!!!!
        
        $controladorRegistroOperador = new ControladorRegistroOperador();
        $controladorTransitoInternacional = new ControladorTransitoInternacional();
        $controladorCatalogos = new ControladorCatalogos();
        $controladorVUE = new ControladorVUE();
        $conexionGUIA = new Conexion();
        
        $resultado = array();
        $modificacion = true;
        
        $idVue = $this->f061['req_no'];
        $identificadorImportador = $this->f061['impr_idt_no'];
        
        $tipoProductoVUE = strtoupper($this->f061['prdt_type_nm']);
        
        $tipoProductoGUIA = ($tipoProductoVUE == 'VEGETAL'?'SV': ($tipoProductoVUE == 'ANIMAL'?'SA': 'No definido'));
        
        $fechaActual=date('Y-m-d');
        
        for ($i = 0; $i < count ($this->f061['req_no']); $i++) {
            
            $qImportacion = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $idVue);
            $datosImportador = pg_fetch_assoc($qImportacion);
            
            //Vigencia del registro
            $fechaActual = date('j-n-Y');
            $fechaCertificado = date('j-n-Y',strtotime($datosImportador['fecha_fin_vigencia']));
            
            if(!(strtotime($fechaCertificado) >= strtotime($fechaActual))){
                $modificacion = false;
                echo IN_MSG. 'El certificado de tránsito internacional no se encuentra vigente.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'El certificado de tránsito internacional no se encuentra vigente.';
                break;
            }
            
            //Valida si cambio el ruc del importador
            /*if( pg_num_rows($qImportacion) == 0){
                $modificacion = false;
                echo IN_MSG. 'No se permite modificar datos del importador.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite modificar datos del importador.';
                break;
            }
            
            //Valida si cambio la ciudad solicitud
            $codigoCiudadVUE = $this->f061['req_city_cd'];
            $codigoCiudadGUIA = $datosImportador['codigo_ciudad_solicitud'];
            
            if( $codigoCiudadVUE != $codigoCiudadGUIA ){ 
                $modificacion = false;
                echo IN_MSG. 'No se permite modificar la ciudad de solicitud.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite modificar la ciudad de solicitud.';
                break;
            }
            
            //Valida si cambio el tipo de producto
            $tipoProductoVUE = $this->f061['prdt_type_nm'];
            $tipoProductoGUIA = $datosImportador['nombre_tipo_producto'];
            
            if( $tipoProductoVUE != $tipoProductoGUIA ){ 
                $modificacion = false;
                echo IN_MSG. 'No se permite modificar el tipo de producto de la solicitud.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite modificar el tipo de producto de la solicitud.';
                break;
            }*/
            
            //Puerto de ingreso
            $qCodigoPuertoIngreso = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f061['enty_pnt_cd']); //Obtiene codigo de puerto en GUIA ingreso
            $codigoPuertoIngreso = pg_fetch_assoc($qCodigoPuertoIngreso);
            
            if( pg_num_rows($qCodigoPuertoIngreso) == 0 ){ //Validación del puerto de ingreso
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El puerto de ingreso '.$this->f061['enty_pnt_nm'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'El puerto de ingreso '.$this->f061['enty_pnt_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
            
            //Puerto de sal
            $qCodigoPuertoSalida = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $this->f061['dspt_sttn_cd']); //Obtiene codigo de puerto en GUIA salida
            $codigoPuertoSalida = pg_fetch_assoc($qCodigoPuertoSalida);
            
            if( pg_num_rows($qCodigoPuertoSalida) == 0 ){ //Validación del puerto de salida
                $solicitudEsValida = false;
                $certificadoImpValido = false;
                echo IN_MSG. 'El puerto de salida '.$this->f061['dspt_sttn_cd'].' no se encuentra registrado en agrocalidad';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'El puerto de salida '.$this->f061['dspt_sttn_nm'].' no se encuentra registrado en agrocalidad';
                break;
            }
            /*
            $productoDuplicado = parent::verificarProductoRepetido($this->f061pd);
            
            if($productoDuplicado){
                $modificacion = false;
                echo IN_MSG.  'No se permite agregar el mismo producto dos veces.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite agregar el mismo producto dos veces.';
                break;
            }
            
   
            $cantidadProductoGUIA =  pg_fetch_result($controladorTransitoInternacional->numeroProductosTransitoInternacional($conexionGUIA, $identificadorImportador, $idVue), 0, 'cantidad');

            if($cantidadProductoGUIA != count ($this->f061pd)){
                $modificacion = false;
                echo IN_MSG.  'No se permite eliminar o agregar productos.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite eliminar o agregar productos.';
                break;
            }*/
            
            //Validaciones de punto de ingreos y salida
            
        }
        
        /*for ($i = 0; $i < count ($this->f061pd); $i++) {
            
            $partidaArancelariaVUE = $this->f061pd[$i]['hc'];
            $codigoProductoVUE = $this->f061pd[$i]['prdt_cd'];
            
            $qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $tipoProductoGUIA);
            
            
            if( pg_num_rows($qProductoGUIA) == 0 ){ // Busqueda del producto en base de datos GUIA.
                $modificacion = false;
                echo IN_MSG. 'El producto '.$this->f061pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'El producto '.$this->f061pd[$i]['prdt_nm'].' no se encuentra registrado en Agrocalidad. ';
                break;
            }
            
            $qProductoImportacion = $controladorTransitoInternacional->buscarTransitoInternacionalProductoVUE($conexionGUIA, $identificadorImportador, $idVue, pg_fetch_result($qProductoGUIA, 0, 'id_producto'));
            
            
            if( pg_num_rows($qProductoImportacion) == 0 ){ // Busqueda del producto en base de datos GUIA.
                $modificacion = false;
                echo IN_MSG. 'El producto '.$this->f061pd[$i]['prdt_nm'].' no se encuentra registrado en la solicitud de tránsito internacional.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'El producto '.$this->f061pd[$i]['prdt_nm'].' no se encuentra registrado en la solicitud de tránsito internacional.';
                break;
            }
            
            $productoImportacion = pg_fetch_assoc($qProductoImportacion);
            
            if($partidaArancelariaVUE != $productoImportacion['subpartida_arancelaria']){
                $modificacion = false;
                echo IN_MSG. 'No se permite modificar la subpartida arancelaria del producto.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite modificar la subpartida arancelaria del producto.';
                break;
            }
            
            if($this->f061pd[$i]['prdt_qt'] != $productoImportacion['cantidad_producto']){
                $modificacion = false;
                echo IN_MSG. 'No se permite modificar la cantidad de producto.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite modificar la cantidad de producto.';
                break;
            }
            
            if($this->f061pd[$i]['prdt_nwt'] != $productoImportacion['peso_kilos']){
                $modificacion = false;
                echo IN_MSG. 'No se permite modificar el peso del producto.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] =  'No se permite modificar el peso del producto.';
                break;
            }
            
            if($this->f061pd[$i]['prdt_mes'] != $productoImportacion['nombre_unidad_cantidad']){
                $modificacion = false;
                echo IN_MSG. 'No se permite modificar la unidad de medida de la cantidad del producto.';
                $resultado[0] = SOLICITUD_DE_CORRECION_NO_APROBADA;
                $resultado[1] = 'No se permite modificar la unidad de medida de la cantidad del producto.';
                break;
            }
            
        }*/		
        if ($modificacion)
            return true;
        else
            return $resultado;
    }
    
    public function insertarDatosEnGUIA(){
        
        $controladorRegistroOperador = new ControladorRegistroOperador();
        $controladorTransitoInternacional = new ControladorTransitoInternacional();
        $controladorCatalogos = new ControladorCatalogos();
        $conexionGUIA = new Conexion();
        
        
        
        //Datos solicitud
        $codigoCiudadSolicitudVUE = $this->f061['req_city_cd'];
        $nombreCiudadSolicitudVUE = $this->f061['req_city_nm'];
        
        $tipoSolicitudVUE = strtoupper(($this->f061['prdt_type_nm']));
        $tipoSolicitudGUIA = ($tipoSolicitudVUE== 'VEGETAL'?'SV': ($tipoSolicitudVUE== 'ANIMAL'?'SA': 'No definido'));
        
        //Datos del solicitante
        $identificadorImportador = $this->f061['impr_idt_no'];
        
        $codigoProvinciaSolicitanteVUE = $this->f061['dclr_prvhc_cd'];
        $codigoCantonSolicitanteVUE = $this->f061['dclr_cuty_cd'];
        $codigoParroquiaSolicitanteVUE = $this->f061['dclr_prqi_cd'];
        
        //Datos del importador
        $codigoCantonImportadorVUE = $this->f061['impr_cuty_cd'];
        $codigoParroquiaImportadorVUE = $this->f061['impr_prqi_cd'];
        
        //Datos de tránsito
        $regimenAduaneroVUE = $this->f061['cutom_rgm_cd'];
        $codigoPaisOrigenVUE = $this->f061['orgn_ntn_cd'];
        $codigoPaisProcedenciaVUE = $this->f061['pdc_ntn_cd'];
        $codigoPaisDestinoVUE = $this->f061['dst_ntn_cd'];
        $codigoUbicacionEnvioVUE = $this->f061['ship_lctn_cd'];
        $codigoPuntoIngresoVUE = $this->f061['enty_pnt_cd'];
        $codigoPuntoSalidaVUE = $this->f061['dspt_sttn_cd'];
        
        $codigoMedioTransporteVUE = $this->f061['trsp_way_cd'];
        
        
        parent::asignarUsuarioGUIA($identificadorImportador);
        
        parent::asignarPerfilGUIA($identificadorImportador, 'Operadores');
        parent::asignarPerfilGUIA($identificadorImportador, 'Usuario externo');
        parent::asignarPerfilGUIA($identificadorImportador, 'Operadores de Comercio Exterior');
        
        parent::asignarAplicacionGUIA( $identificadorImportador, 'PRG_REGISTROOPER');
        
        parent::ingresarRegistroOperador($identificadorImportador, $this->f061['impr_nm']);
        
        
        
        //Datos de solicitud
        
        /*$qCodigoCiudadSolicitud = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoCiudadSolicitudVUE); //Codigo del pais de embarque
         $codigoCiudadSolicitud = pg_fetch_assoc($qCodigoCiudadSolicitud);*/
        
        $qCodigoCiudadSolicitud = $controladorCatalogos->buscarLocalizacionXNombre($conexionGUIA, $nombreCiudadSolicitudVUE); //Codigo del pais de embarque
        $codigoCiudadSolicitud = pg_fetch_assoc($qCodigoCiudadSolicitud);
        
        //Definir la provincia para revision
        $qCodigoProvinciaRevision = $controladorCatalogos->obtenerNombreLocalizacion($conexionGUIA, $codigoCiudadSolicitud['id_localizacion_padre']); //Codigo del pais de embarque
        $codigoProvinciaRevision = pg_fetch_assoc($qCodigoProvinciaRevision);
        
        
        //Datos del solicitante
        $qCodigoProvinciaSolicitante = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoProvinciaSolicitanteVUE); //Codigo del pais de embarque
        $codigoProvinciaSolicitante = pg_fetch_assoc($qCodigoProvinciaSolicitante);
        
        $qCodigoCantonSolicitante = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoCantonSolicitanteVUE); //Codigo del pais de embarque
        $codigoCantonSolicitante = pg_fetch_assoc($qCodigoCantonSolicitante);
        
        $qCodigoParroquiaSolicitante = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoParroquiaSolicitanteVUE); //Codigo del pais de embarque
        $codigoParroquiaSolicitante = pg_fetch_assoc($qCodigoParroquiaSolicitante);
        
        //Datos del importador
        /*$qCodigoCantonImportador = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoCantonImportadorVUE); //Codigo del pais de embarque
         $codigoCantonImportador = pg_fetch_assoc($qCodigoCantonImportador);
         
         $qCodigoParroquiaImportador = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoParroquiaImportadorVUE); //Codigo del pais de embarque
         $codigoParroquiaImportador = pg_fetch_assoc($qCodigoParroquiaImportador);*/
        
        //Datos de tránsito
        $qRegimenAduanero = $controladorCatalogos->obtenerCodigoAduanero($conexionGUIA, $regimenAduaneroVUE);
        $codigoRegimenAduanero = pg_fetch_assoc($qRegimenAduanero);
        
        $qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisOrigenVUE); //Codigo del pais de embarque
        $codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
        
        $qCodigoPaisProcedencia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisProcedenciaVUE); //Codigo del pais de embarque
        $codigoPaisProcedencia = pg_fetch_assoc($qCodigoPaisProcedencia);
        
        $qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoVUE); //Codigo del pais de embarque
        $codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
        
        /*se tendria q quitar y solo guardar la info y borrar la columna de id en base*/
        $qCodigoUbicacionEnvio = $controladorCatalogos->buscarCatalogoLugarInspeccion($conexionGUIA, $codigoUbicacionEnvioVUE); //Codigo del pais de embarque
        $codigoUbicacionEnvio = pg_fetch_assoc($qCodigoUbicacionEnvio);
        /**/
        $qCodigoPuntoIngreso = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $codigoPuntoIngresoVUE); //Codigo del pais de embarque
        $codigoPuntoIngreso = pg_fetch_assoc($qCodigoPuntoIngreso);
        
        $qCodigoPuntoSalida = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $codigoPuntoSalidaVUE); //Codigo del pais de embarque
        $codigoPuntoSalida = pg_fetch_assoc($qCodigoPuntoSalida);
        
        $qCodigoMedioTransporte = $controladorCatalogos->obtenerCodigoMedioTransporte($conexionGUIA, $codigoMedioTransporteVUE); //Codigo del pais de embarque
        $codigoMedioTransporte = pg_fetch_assoc($qCodigoMedioTransporte);
        
        
        
        
        $res = $controladorTransitoInternacional->generarNumeroSolicitud($conexionGUIA, '%'.$identificadorImportador.'%');
        $solicitud = pg_fetch_assoc($res);
        $tmp= explode("-", $solicitud['numero']);
        $incremento = end($tmp)+1;
        
        $codigoSolicitud = 'TRANS_INT-'.$identificadorImportador.'-'.str_pad($incremento, 7, "0", STR_PAD_LEFT);
        echo OUT_MSG . 'Generación de codigo interno de tránsito internacional';
        
        
        $registroTransito = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $this->f061['req_no']);
        
        $nombreSolicitante = str_replace("'", " ", $this->f061['dclr_nm']);
        $nombreRepresentanteLegalSolicitante = str_replace("'", " ", $this->f061['dclr_rpgp_nm']);
        $nombreImportador = str_replace("'", " ", $this->f061['impr_nm']);
        
        
        if(pg_num_rows($registroTransito) == 0){
            
            $registroTransito = $controladorTransitoInternacional->guardarNuevoTransitoInternacional(
                $conexionGUIA, $this->f061['req_no'], $this->f061['dcm_no'],
                $this->f061['dcm_nm'], $this->f061['dcm_func_cd'], $this->f061['req_de'],
                $codigoCiudadSolicitud['id_localizacion'], $this->f061['req_city_cd'], $codigoCiudadSolicitud['nombre'],
                $tipoSolicitudGUIA, $this->f061['prdt_type_nm'],
                $this->f061['dclr_idt_no'], $nombreSolicitante, $nombreRepresentanteLegalSolicitante,
                $codigoProvinciaSolicitante['id_localizacion'], $this->f061['dclr_prvhc_cd'], $codigoProvinciaSolicitante['nombre'],
                $codigoCantonSolicitante['id_localizacion'], $this->f061['dclr_prvhc_cd'], $codigoCantonSolicitante['nombre'],
                $codigoParroquiaSolicitante['id_localizacion'], $this->f061['dclr_prvhc_cd'], $codigoParroquiaSolicitante['nombre'],
                $this->f061['dclr_ad'], $this->f061['dclr_tel_no'], $this->f061['dclr_fax_no'], $this->f061['dclr_em'],
                $this->f061['impr_cl_cd'], $identificadorImportador, $nombreImportador,
                $this->f061['impr_ad'], $this->f061['impr_em'], $this->f061['impr_rpst_nm'],
                $this->f061['impr_tel_no'], $this->f061['impr_cel_no'],
                $codigoRegimenAduanero['id_regimen'], $codigoRegimenAduanero['descripcion'],
                $codigoPaisOrigen['id_localizacion'], $this->f061['orgn_ntn_cd'], $codigoPaisOrigen['nombre'],
                $codigoPaisProcedencia['id_localizacion'], $this->f061['pdc_ntn_cd'], $codigoPaisProcedencia['nombre'],
                $codigoPaisDestino['id_localizacion'], $this->f061['dst_ntn_cd'], $codigoPaisDestino['nombre'],
                $codigoUbicacionEnvio['id_lugar'], $this->f061['ship_lctn_cd'], $codigoUbicacionEnvio['nombre'],
                $codigoPuntoIngreso['id_puerto'], $this->f061['enty_pnt_cd'], $codigoPuntoIngreso['nombre_puerto'],
                $codigoPuntoSalida['id_puerto'], $this->f061['dspt_sttn_cd'], $codigoPuntoSalida['nombre_puerto'],
                $codigoMedioTransporte['id_medios_transporte'], $this->f061['trsp_way_cd'], $codigoMedioTransporte['tipo'],
                $this->f061['vhc_no'], $this->f061['rute_to_fllw'], 'enviado', $codigoSolicitud,
                $codigoProvinciaRevision['id_localizacion'], $codigoProvinciaRevision['nombre']);//salo
                
        }else{//Pendiente de revision
            $controladorTransitoInternacional->actualizarDatosTransitoInternacional(
                $conexionGUIA, pg_fetch_result($registroTransito, 0, 'id_transito_internacional'), $this->f061['req_no'], $this->f061['dcm_no'],
                $this->f061['dcm_nm'], $this->f061['dcm_func_cd'], $this->f061['req_de'],
                $codigoCiudadSolicitud['id_localizacion'], $this->f061['req_city_cd'], $codigoCiudadSolicitud['nombre'],
                $tipoSolicitudGUIA, $this->f061['prdt_type_nm'],
                $this->f061['dclr_idt_no'], $nombreSolicitante, $nombreRepresentanteLegalSolicitante,
                $codigoProvinciaSolicitante['id_localizacion'], $this->f061['dclr_prvhc_cd'], $codigoProvinciaSolicitante['nombre'],
                $codigoCantonSolicitante['id_localizacion'], $this->f061['dclr_prvhc_cd'], $codigoCantonSolicitante['nombre'],
                $codigoParroquiaSolicitante['id_localizacion'], $this->f061['dclr_prvhc_cd'], $codigoParroquiaSolicitante['nombre'],
                $this->f061['dclr_ad'], $this->f061['dclr_tel_no'], $this->f061['dclr_fax_no'], $this->f061['dclr_em'],
                $this->f061['impr_cl_cd'], $identificadorImportador, $nombreImportador,
                $this->f061['impr_ad'], $this->f061['impr_em'], $this->f061['impr_rpst_nm'],
                $this->f061['impr_tel_no'], $this->f061['impr_cel_no'],
                $codigoRegimenAduanero['id_regimen'], $codigoRegimenAduanero['descripcion'],
                $codigoPaisOrigen['id_localizacion'], $this->f061['orgn_ntn_cd'], $codigoPaisOrigen['nombre'],
                $codigoPaisProcedencia['id_localizacion'], $this->f061['pdc_ntn_cd'], $codigoPaisProcedencia['nombre'],
                $codigoPaisDestino['id_localizacion'], $this->f061['dst_ntn_cd'], $codigoPaisDestino['nombre'],
                $codigoUbicacionEnvio['id_lugar'], $this->f061['ship_lctn_cd'], $codigoUbicacionEnvio['nombre'],
                $codigoPuntoIngreso['id_puerto'], $this->f061['enty_pnt_cd'], $codigoPuntoIngreso['nombre_puerto'],
                $codigoPuntoSalida['id_puerto'], $this->f061['dspt_sttn_cd'], $codigoPuntoSalida['nombre_puerto'],
                $codigoMedioTransporte['id_medios_transporte'], $this->f061['trsp_way_cd'], $codigoMedioTransporte['tipo'],
                $this->f061['vhc_no'], $this->f061['rute_to_fllw'], 'enviado',
                $codigoProvinciaRevision['id_localizacion'], $codigoProvinciaRevision['nombre']);
            
            $controladorTransitoInternacional->eliminarProductosTransitoInternacional($conexionGUIA,pg_fetch_result($registroTransito, 0, 'id_transito_internacional'));
        }
        
        echo OUT_MSG . 'Datos de cabecera de tránsito internacional insertados';
        
        
        for ($i = 0; $i < count ($this->f061pd); $i++) {
            
            $partidaArancelariaVUE = $this->f061pd[$i]['hc'];
            $codigoProductoVUE = $this->f061pd[$i]['prdt_cd'];
            $codigoUnidadMedidaPesoVUE = $this->f061pd[$i]['prdt_nwt_ut'];
            $codigoUnidadMedidaCantidadVUE = $this->f061pd[$i]['prdt_mes'];
            
            $tipoFormularioVUE = $this->f061['prdt_type_cd'];
            
            $area = ($tipoFormularioVUE == '1'?'SV':($tipoFormularioVUE == '2'?'SA':'No definido'));
            
            $qProductoGUIA = $controladorCatalogos->buscarProductoXCodigo($conexionGUIA, $partidaArancelariaVUE, $codigoProductoVUE, $tipoSolicitudGUIA);
            $producto = pg_fetch_assoc($qProductoGUIA);
            
            $qUnidadMedidaPeso = $controladorCatalogos->obtenerIdUnidadMedida($conexionGUIA, $codigoUnidadMedidaPesoVUE); //Codigo de unidad de medida peso
            $idUnidadMedidaPeso = pg_fetch_assoc($qUnidadMedidaPeso);
            
            $qUnidadMedidaCantidad = $controladorCatalogos->obtenerIdUnidadMedida($conexionGUIA, $codigoUnidadMedidaCantidadVUE); //Codigo de unidad de medida cantidad
            $idUnidadMedidaCantidad = pg_fetch_assoc($qUnidadMedidaCantidad);
            
            $controladorTransitoInternacional -> guardarTransitoInternacionalProductos( 
                $conexionGUIA, pg_fetch_result($registroTransito, 0, 'id_transito_internacional'), $this->f061['req_no'],
                $partidaArancelariaVUE, $this->f061pd[$i]['hc_nm'],
                $producto['id_tipo_producto'], $producto['nombre_tipo'],
                $producto['id_subtipo_producto'], $producto['nombre'],
                $producto['id_producto'], $codigoProductoVUE, $producto['nombre_comun'],
                $idUnidadMedidaPeso['id_unidad_medida'], $codigoUnidadMedidaPesoVUE,
                $idUnidadMedidaCantidad['id_unidad_medida'], $codigoUnidadMedidaCantidadVUE,
                $this->f061pd[$i]['prdt_qt'], $this->f061pd[$i]['prdt_nwt']);
            
        }
        
        
        echo OUT_MSG . 'Datos de detalle de productos de tránsito internacional insertados';
        
        return true;
    }
    
    public function insertarDocumentosAdjuntosGUIA($documentos){
        
        $controladorTransitoInternacional = new ControladorTransitoInternacional();
        $conexionGUIA = new Conexion();
        
        $identificadorImportador = $this->f061['impr_idt_no'];
        
        $idTransito = pg_fetch_result($controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $this->f061['req_no']),0,'id_transito_internacional');
        
        $controladorTransitoInternacional->eliminarArchivosAdjuntos($conexionGUIA, $idTransito, $this->f061['req_no']);
        
        if(pg_num_rows($documentos)!= 0){
            while($documentosAdjuntos = pg_fetch_assoc($documentos)){
                $ruta= explode('DEV/', $documentosAdjuntos['ruta']);
                //$ruta= explode('PROD/', $documentosAdjuntos['ruta']);
                $controladorTransitoInternacional ->guardarTransitoInternacionalArchivos($conexionGUIA, $idTransito, $documentosAdjuntos['nombre'], $ruta[1], $this->f061['req_no']);
            }
        }else{
            echo OUT_MSG . 'La solicitud de tránsito internacional no posee documentos adjuntos.';
        }
        
        echo OUT_MSG . 'Documentos adjuntos de tránsito internacional insertados.';
        
        return true;
    }
    
    //Revisar si se implementa o se quita!!!!!!!!!
    public function actualizarDatosEnGUIA(){
        
        $controladorTransitoInternacional = new ControladorTransitoInternacional();
        $controladorVUE = new ControladorVUE();
        $controladorCatalogos = new ControladorCatalogos();
        $conexionGUIA = new Conexion();
        
        //Datos solicitud
        $codigoCiudadSolicitudVUE = $this->f061['req_city_cd'];
        
        $tipoSolicitudVUE = strtoupper(($this->f061['prdt_type_nm']));
        $tipoSolicitudGUIA = ($tipoSolicitudVUE== 'VEGETAL'?'SV': ($tipoSolicitudVUE== 'ANIMAL'?'SA': 'No definido'));
        
        //Datos del solicitante
        $identificadorImportador = $this->f061['impr_idt_no'];
        
        $codigoProvinciaSolicitanteVUE = $this->f061['dclr_prvhc_cd'];
        $codigoCantonSolicitanteVUE = $this->f061['dclr_cuty_cd'];
        $codigoParroquiaSolicitanteVUE = $this->f061['dclr_prqi_cd'];
        
        //Datos del importador
        $codigoCantonImportadorVUE = $this->f061['impr_cuty_cd'];
        $codigoParroquiaImportadorVUE = $this->f061['impr_prqi_cd'];
        
        //Datos de tránsito
        $regimenAduaneroVUE = $this->f061['cutom_rgm_cd'];
        $codigoPaisOrigenVUE = $this->f061['orgn_ntn_cd'];
        $codigoPaisProcedenciaVUE = $this->f061['pdc_ntn_cd'];
        $codigoPaisDestinoVUE = $this->f061['dst_ntn_cd'];
        $codigoUbicacionEnvioVUE = $this->f061['ship_lctn_cd'];
        $codigoPuntoIngresoVUE = $this->f061['enty_pnt_cd'];
        $codigoPuntoSalidaVUE = $this->f061['dspt_sttn_cd'];
        
        $codigoMedioTransporteVUE = $this->f061['trsp_way_cd'];
        
        
        
        //Datos de solicitud
        /*$qCodigoCiudadSolicitud = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoCiudadSolicitudVUE); //Codigo del pais de embarque
        $codigoCiudadSolicitud = pg_fetch_assoc($qCodigoCiudadSolicitud);
        
        //Datos del solicitante
        $qCodigoProvinciaSolicitante = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoProvinciaSolicitanteVUE); //Codigo del pais de embarque
        $codigoProvinciaSolicitante = pg_fetch_assoc($qCodigoProvinciaSolicitante);
        
        $qCodigoCantonSolicitante = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoCantonSolicitanteVUE); //Codigo del pais de embarque
        $codigoCantonSolicitante = pg_fetch_assoc($qCodigoCantonSolicitante);
        
        $qCodigoParroquiaSolicitante = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoParroquiaSolicitanteVUE); //Codigo del pais de embarque
        $codigoParroquiaSolicitante = pg_fetch_assoc($qCodigoParroquiaSolicitante);
        
        //Datos del importador
        $qCodigoCantonImportador = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoCantonImportadorVUE); //Codigo del pais de embarque
        $codigoCantonImportador = pg_fetch_assoc($qCodigoCantonImportador);
        
        $qCodigoParroquiaImportador = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoParroquiaImportadorVUE); //Codigo del pais de embarque
        $codigoParroquiaImportador = pg_fetch_assoc($qCodigoParroquiaImportador);
        
        //Datos de tránsito
        $qRegimenAduanero = $controladorCatalogos->obtenerCodigoAduanero($conexionGUIA, $regimenAduaneroVUE);
        $codigoRegimenAduanero = pg_fetch_assoc($qRegimenAduanero);
        
        $qCodigoPaisOrigen = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisOrigenVUE); //Codigo del pais de embarque
        $codigoPaisOrigen = pg_fetch_assoc($qCodigoPaisOrigen);
        
        $qCodigoPaisProcedencia = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisProcedenciaVUE); //Codigo del pais de embarque
        $codigoPaisProcedencia = pg_fetch_assoc($qCodigoPaisProcedencia);
        
        $qCodigoPaisDestino = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoPaisDestinoVUE); //Codigo del pais de embarque
        $codigoPaisDestino = pg_fetch_assoc($qCodigoPaisDestino);
        
        $qCodigoUbicacionEnvio = $controladorCatalogos->obtenerCodigoLocalizacion($conexionGUIA, $codigoUbicacionEnvioVUE); //Codigo del pais de embarque
        $codigoUbicacionEnvio = pg_fetch_assoc($qCodigoUbicacionEnvio);*/
        
        $qCodigoPuntoIngreso = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $codigoPuntoIngresoVUE); //Codigo del pais de embarque
        $codigoPuntoIngreso = pg_fetch_assoc($qCodigoPuntoIngreso);
        
        $qCodigoPuntoSalida = $controladorCatalogos->obtenerPuertoCodigo($conexionGUIA, $codigoPuntoSalidaVUE); //Codigo del pais de embarque
        $codigoPuntoSalida = pg_fetch_assoc($qCodigoPuntoSalida);
        
        /*$qCodigoMedioTransporte = $controladorCatalogos->obtenerCodigoMedioTransporte($conexionGUIA, $codigoMedioTransporteVUE); //Codigo del pais de embarque
        $codigoMedioTransporte = pg_fetch_assoc($qCodigoMedioTransporte);*/
        
        
        
        $registroTransito = pg_fetch_assoc($controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $this->f061['req_no']));
        
        $controladorTransitoInternacional->actualizarDatosTransitoInternacionalPuntos($conexionGUIA, $registroTransito['id_transito_internacional'],
            $codigoPuntoIngreso['id_puerto'], $this->f061['enty_pnt_cd'], $codigoPuntoIngreso['nombre_puerto'],
            $codigoPuntoSalida['id_puerto'], $this->f061['dspt_sttn_cd'], $codigoPuntoSalida['nombre_puerto'], $this->f061['req_no']);
        
        return true;
    }
    
    public function recaudacionTasa($recaudacionTasas){
        
    }
    
    public function cancelar(){
        
        $controladorTransitoInternacional = new ControladorTransitoInternacional();
        $conexionGUIA = new Conexion();
        
        $identificadorImportador = $this->f061['impr_idt_no'];
        
        $registroTransito = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $this->f061['req_no']);
        
        if(pg_num_rows($registroTransito)!=0){
            
            $controladorTransitoInternacional->enviarTransitoInternacional($conexionGUIA, pg_fetch_result($registroTransito, 0, 'id_transito_internacional'), 'cancelado');
            
            $controladorTransitoInternacional -> enviarProductosTransitoInternacional($conexionGUIA, pg_fetch_result($registroTransito, 0, 'id_transito_internacional'), 'cancelado');
            
        }
        
        echo OUT_MSG. 'Solicitud cancelada.';
        return true;
    }
    
    public function anular(){
        
        $controladorTransitoInternacional = new ControladorTransitoInternacional();
        $conexionGUIA = new Conexion();
        
        $identificadorImportador = $this->f061['impr_idt_no'];
        
        $registroTransito = $controladorTransitoInternacional->buscarTransitoInternacionalVUE($conexionGUIA, $identificadorImportador, $this->f061['req_no']);
        
        if(pg_num_rows($registroTransito)!=0){
            
            $controladorTransitoInternacional->enviarTransitoInternacional($conexionGUIA, pg_fetch_result($registroTransito, 0, 'id_transito_internacional'), 'anulado');
            
            $controladorTransitoInternacional -> enviarProductosTransitoInternacional($conexionGUIA, pg_fetch_result($registroTransito, 0, 'id_transito_internacional'), 'anulado');
            
        }
        
        echo OUT_MSG. 'Solicitud anulada.';
        return true;
    }
    
    public function reversoSolicitud(){
        
    }
    
    public function generarFacturaProcesoAutomatico(){
        
    }
}