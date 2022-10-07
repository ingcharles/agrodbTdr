<?php
//if($_SERVER['REMOTE_ADDR'] == ''){
if(1){
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorMail.php';
	require_once '../../../clases/ControladorTablets.php';
	require_once '../../../clases/ControladorMonitoreo.php';
	require_once '../../../clases/ControladorDestinacionAduanera.php';
	require_once '../../../clases/ControladorRevisionSolicitudesVUE.php';
	require_once '../../../clases/ControladorSeguimientoCuarentenario.php';

	$conexion = new Conexion();
	$ct = new ControladorTablets();
	$cMail = new ControladorMail();
	$cm = new ControladorMonitoreo();
	$cda = new ControladorDestinacionAduanera(); 
	$crs = new ControladorRevisionSolicitudesVUE();
	$csc = new ControladorSeguimientoCuarentenario();
	
	define('IN_MSG','<br/> >>> ');

	$fecha = date("Y-m-d h:m:s");
	echo IN_MSG .' INICIO '. $fecha;
	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_CONTROL_DDA');
	//if($resultadoMonitoreo){
	if(1){
		$datosSincronizar = $ct->obtenerRegistrosInspeccionImportacion($conexion, 'POR ENVIAR');
		
		while ($fila = pg_fetch_assoc($datosSincronizar)){
			
			$idRegistro = $fila['id'];
			$dda = $fila['dda'];
			$pfi = $fila['pfi'];
			$dictamenFinal = $fila['dictamen_final'];
			$observacion = $fila['observaciones'];
			$inspector = $fila['usuario_id'];
			$fechaInspeccion = $fila['fecha_inspeccion'];
			$seguiminetoCuarentenario = $fila['seguimiento_cuarentenario'];
			$provincia = $fila['provincia'];
			$pesoIngreso = $fila['peso_ingreso'];
			$tipoSolicitud = 'DDA';
			$tipoInspector = 'Técnico';
			
			$identificadorTablet = $fila['tablet_id'];
			$versionBD =  $fila['tablet_version_base'];
			
			switch ($dictamenFinal){
				case 'Aprobar':
					$dictamenFinal = 'aprobado';
				break;
			
				case 'Subsanar':
					$dictamenFinal = 'subsanacion';
				break;
			
				case 'Desaprobar':
					$dictamenFinal = 'rechazado';
				break;			
			}
			
			$ct->actualizarEstadoInspeccionImportacion($conexion, $idRegistro, 'W');
			$datosDDA = pg_fetch_assoc($cda->buscarDDAPorIdentificadorVUE($conexion, $dda));
			
			echo IN_MSG . 'Crear nueva inspección';
						
			// Crear nueva inspeccion
			$inspectorAsignado= $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector);
			$idInspeccionAsignado = pg_fetch_assoc($inspectorAsignado);
			
			// Guardar grupo de solicitudes
			$crs->guardarGrupo($conexion, $datosDDA['id_destinacion_aduanera'],$idInspeccionAsignado['id_grupo'], $tipoInspector);
			
			// Generar orden de inspeccion
			$ordenInspeccion = $crs->buscarSerialOrden($conexion, $idInspeccionAsignado['id_grupo'], $tipoInspector);
			$orden = pg_fetch_assoc($ordenInspeccion);
						
			// Crear regitro de revisión de inspeccion y observaciones
			$idInspeccionRegistro =  $crs->guardarDatosInspeccionTablets($conexion, $idInspeccionAsignado['id_grupo'], $inspector, $fechaInspeccion, $dictamenFinal, $orden['orden'], $idRegistro, '00:00:00:00:00:00', $versionBD, '1');
			$crs->actualizarObservacionTablets($conexion, pg_fetch_result($idInspeccionRegistro, 0, 'id_inspeccion'), $observacion);

			// Actualizar el peso en DDA
			
			$cda->actualizarPesoInspeccionDDA($conexion, $datosDDA['id_destinacion_aduanera'], $pesoIngreso);
			
			// Evaluar productos del DDA
			$productos = $ct->obtenerRegistrosInspeccionProductosImportacion($conexion, $idRegistro);
			while ($producto = pg_fetch_assoc($productos)){
				$cda->evaluarProductosDDAPorNombre($conexion, $datosDDA['id_destinacion_aduanera'], $producto['nombre'], $dictamenFinal, $observacion, $producto['cantidad_ingresada']);
			}
			
			// Enviar estado aprobado al DDA
			$cda->enviarDDA($conexion, $datosDDA['id_destinacion_aduanera'], $dictamenFinal);
			
			// Enviar fechas de vigencia DDA
			switch ($dictamenFinal){
				case 'aprobado':
					$cda->enviarFechaVigenciaDDA($conexion, $datosDDA['id_destinacion_aduanera']);
					$ct->ingresarSolicitudesXatenderGUIA($conexion, '101-024-REQ','320','21',$dda, 'Por atender', $observacion);
				break;
			
				case 'subsanacion':;
					$ct->ingresarSolicitudesXatenderGUIA($conexion, '101-024-REQ','410','21',$dda, 'Por atender', $observacion);
					$cda->actualizarContadorInspeccionDDA($conexion, $datosDDA['id_destinacion_aduanera'], 2);
				break;
			
				case 'rechazado':
					$ct->ingresarSolicitudesXatenderGUIA($conexion, '101-024-REQ','310','21',$dda, 'Por atender', $observacion);
				break;		
			}
						
			// Enviar check de seguimiento cuarentenario y provincia
			if($seguiminetoCuarentenario == 'Si'){
			    if($datosDDA['tipo_certificado']=='VEGETAL'){
			        $cda->actualizarSeguimientoCuarentenario($conexion, $datosDDA['id_destinacion_aduanera'], $provincia);
			        $qListadoTecnico = $csc->listarTecnicoInspectorProvinciaDDA($conexion,$provincia, "('PFL_SEGUI_CUARE')");
			    }else{
			        $qListadoTecnico = $csc->listarTecnicoInspectorProvinciaDDA($conexion,$provincia, "('PFL_SEGUI_CUARSA')");
			    }
				
				$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
									<style type="text/css">
										.titulo  {
											margin-top: 30px;
											width: 800px;
											text-align: center;
											font-size: 14px;
											font-weight: bold;
											font-family:Times New Roman;
										}
										.lineaDos{
											font-style: oblique;
											font-weight: normal;
										}
										.lineaLeft{
											text-align: left;
										}
										.lineaEspacio{
											height: 35px;
										}
										.lineaEspacioMedio{
											height: 50px;
										}
										.espacioLeft{
											padding-left: 15px;
										}
									</style>';
				
				        $cuerpoMensaje.='<table class="titulo">
									<thead>
										<tr><th>Se ha registrado un seguimiento cuarentenario.</th></tr>
									</thead>
									<tbody>
									<tr><td class="lineaDos lineaEspacio">Se ha registrado un seguimiento cuarentenario de la solicitud con número # '.$dda.' </td>	</tr>
									<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
									</tbody>
									<tfooter>
									<tr><td class="lineaEspacioMedio"></td></tr>
									<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
									<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
									</tfooter>
									</table>';
				
				$asunto = 'Solicitud de seguimiento cuarentenario';
				$codigoModulo='';
				$tablaModulo='';
				
				foreach ((array)$qListadoTecnico['listado_usuarios'] as $fila) {
				    $destinatarios = array();
				    if($fila['mail_institucional']!= ''){
				        $destinatarios  = explode('; ',$fila['mail_institucional']);
				        
				    }else if($fila['mail_personal'] !=''){
				        $destinatarios  = explode('; ',$fila['mail_personal']);
				    }
				    
				    $qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
				    $idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
				    $cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
				    
				}
				
				
			}
					
			$ct->actualizarEstadoInspeccionImportacion($conexion, $idRegistro, 'FINALIZADO');
		}
	}
}else{
	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../../aplicaciones/logs/cron/tablets_inspeccion_importacion_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>