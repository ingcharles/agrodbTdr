<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
	//if($_SERVER['REMOTE_ADDR'] == ''){
		if(1){
		try {
			require_once '../../clases/Conexion.php';
			require_once '../../clases/ControladorVacaciones.php';
			require_once '../../clases/ControladorMonitoreo.php';
			require_once '../../clases/ControladorUsuarios.php';
			require_once '../../clases/ControladorAreas.php';
			require_once '../../clases/Constantes.php';
			require_once '../../clases/ControladorCatalogos.php';

			require_once '../../clases/ControladorGenerarAccionPersonalPdf.php';
			
			$conexion = new Conexion();
			$cc = new ControladorVacaciones();
			$cm = new ControladorMonitoreo();
			$cu = new ControladorUsuarios();
			$ca = new ControladorAreas();
			$cat = new ControladorCatalogos();

		//	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_TTHH_ACCI_PERS');

			//if($resultadoMonitoreo){
				if(1){
				define('IN_MSG','<br/> >>> ');

				try {
					$conexion->ejecutarConsulta("begin;");
					set_time_limit(2000);
					echo IN_MSG.'Generar Reintegro de Personal tcpdf<br>';
					if( pg_num_rows($cc->consultarPermisosReintegro($conexion))){

						$consulta = $cc->consultarPermisosReintegro($conexion);
						while($fila = pg_fetch_assoc($consulta)) {
							$id_registro=$fila['id_permiso_empleado'];
							$codigoSubtipo=$fila['codigo'].'-RE';
							$identificadorFuncionario = $fila['identificador'];
							$identificadorTH=$fila['identificadorrhh'];
							$id_area_permiso=$fila['id_area_permiso'];

							$codigoAccionPersonal = '';

							$filaSolicitud = pg_fetch_assoc($cc->obtenerDatosPermisoReintegro($conexion,$id_registro,$codigoSubtipo));

							//Obtiene información de jefe superior
							$filaDirector = pg_fetch_assoc($cc->obtenerNombreDirector($conexion,$filaSolicitud['identificador_jefe_superior']));
							$nombreDirector = $filaDirector['directorath'];
							$cargoDirector = $filaDirector['puestoth'];

							//Obtiene información de responsable de talento humano
							$filaDirectorTH = pg_fetch_assoc($cc->obtenerNombreDirector($conexion,$identificadorTH));
							$nombreResponsableTH = $filaDirectorTH['directorath'];
							$cargoResponsableTH = $filaDirectorTH['puestoth'];
							//$idArea = $ca->areaUsuario($conexion, $identificadorTH);

							//Generando número secuencial de acción de personal por oficina técnica
							$numero = pg_fetch_assoc($cc->generarNumeroAccionPersonal($conexion, $filaSolicitud['id_area_permiso']));
							$nAccion = ($numero['numero'] == '' ? 0001 : $numero['numero']);
							$numeroAccionPersonal = str_pad($nAccion, 4, "0", STR_PAD_LEFT);

							//Generar código para acción de personal por la ubicación del responsable de TH
							if($id_area_permiso == 'DGATH'){
								$codigoArea = 'DARH';
								$codigoAccionPersonal=$codigoArea.'-'.$numeroAccionPersonal;
							}else {
								$codigoAccionPersonal=$id_area_permiso.'-'.$numeroAccionPersonal;
							}

							$filename = $identificadorFuncionario.'-AccionPersonalReintegro-'.$id_registro.'.pdf';
							$salidaReporte = 'aplicaciones/vacacionesPermisos/accionPersonal/'.$filename;
							$consult = pg_fetch_assoc( $cc->obtenerInformacioAccionPersonalFuncionario($conexion , $identificadorFuncionario));
							
							$date_future = strtotime('+1 day', strtotime($filaSolicitud['fecha_fin']));
							$date_future = date('d-m-Y', $date_future);
							
							$texto = $filaSolicitud['texto_accion_personal'];
							
							$servidor = strtolower($consult['ficha_empleado_nombre']).' '.strtolower($consult['ficha_empleado_apellido']);
							$texto = str_replace('$servidor',ucwords($servidor), $texto);
							$texto = str_replace('$puesto',$consult['datos_contrato_puesto'], $texto);
							$texto = str_replace('$direccion', $consult['datos_contrato_direccion'], $texto);
							$texto = str_replace('$fecha', $date_future, $texto);
							
							//echo $texto;
							$textoAccionPersonal = $texto;

							$cc->actualizarRutaDocumentoReintegro($conexion, $id_registro, $salidaReporte,$filaSolicitud['id_subtipo_permiso']);
							$cc->actualizarEstadoPermisoReintegro($conexion, $id_registro, 'TRUE');

							$arrayDatos = array(
								'texoAcccionPersonal' => $textoAccionPersonal,
								'codigoPermiso' => $codigoAccionPersonal,
								'identificador' => $identificadorFuncionario,
								'rigeDesde' => $date_future,
								'nombreFuncionario' => $consult['ficha_empleado_nombre'],
								'apellidoFuncionario' => $consult['ficha_empleado_apellido'],
								'rutaImagen' => '../general/img/encabezado.png'
							);
							$arrayTipo = array(
								'decreto' => '',
								'acuerdo' => '',
								'resolucion' => ''
							);
							
							$arraySituacionActual =array(
								'proceso' => $consult['datos_contrato_direccion'],
								'subProceso' => $consult['datos_contrato_gestion'],
								'puesto' => $consult['datos_contrato_grupo_ocupacional'],
								'lugarTrabajo' => $consult['datos_contrato_provincia'],
								'remuneracion' => $consult['datos_contrato_remuneracion'],
								'partidaPresupuestaria' => $consult['datos_contrato_partida_presupuestaria']
							);
							
							
							if($codigoArea =='DARH'){
								$consultaDirector = pg_fetch_assoc($cc->devolverResponsable($conexion, 'DE'));
								$nombrePuestoDirector = $consultaDirector['funcionario'];
								$identificadorPuestoDirector = $consultaDirector['identificador'];
								
								$arrayFirmaDE = array(
									'nombreFirma' => $nombrePuestoDirector,
									'cargoFirma' => 'DIRECTOR EJECUTIVO',
									'tthh' => 'no',
									'identificador' =>$identificadorPuestoDirector
								);
							}else{
								$consultaDirector = pg_fetch_assoc($cc->devolverResponsable($conexion, $codigoArea));
								$nombrePuestoDirector = $consultaDirector['funcionario'];
								$identificadorPuestoDirector = $consultaDirector['identificador'];
								$arrayFirmaDE = array(
									'nombreFirma' => $nombrePuestoDirector,
									'cargoFirma' => 'DIRECTOR DISTRITAL TIPO A',
									'tthh' => 'no',
									'identificador' =>$identificadorPuestoDirector
								);
							}
							
							if($codigoArea =='DARH'){
								$consultaResgistro = pg_fetch_assoc($cc->devolverResponsableRegistro($conexion, $codigoArea));
								$nombreRegistroControl = $consultaResgistro['funcionario'];
								$identificadorRegistroControl = $consultaResgistro['identificador'];
								$arrayFirmaRC = array(
									'nombreFirma' => $nombreRegistroControl,
									'cargoFirma' => 'RESPONSABLE DE REGISTRO',
									'tthh' => 'no',
									'identificador' =>$identificadorRegistroControl
								);
							}else{
								$identificadorRegistroControl = $identificadorTH;
								$arrayFirmaRC = array(
									'nombreFirma' => $nombreResponsableTH,
									'cargoFirma' => 'RESPONSABLE DE REGISTRO DISTRITAL TIPO A',
									'identificador' => $identificadorRegistroControl,
									'tthh' => 'no');
							}
							
							$th = pg_num_rows($cat->verificarFirmaDigitalFuncionarios($conexion,$identificadorTH));
							$pd = pg_num_rows($cat->verificarFirmaDigitalFuncionarios($conexion,$identificadorPuestoDirector));
							$rc = pg_num_rows($cat->verificarFirmaDigitalFuncionarios($conexion,$identificadorRegistroControl));
							
							if( ($th > 0) && ( $pd > 0) && ( $rc> 0) ){
								$firma = 'si';
								$cc->actualizarFirmaManualPermiso($conexion, $id_registro, 'FALSE');
							}else{
								$firma = 'no';
								$cc->actualizarFirmaManualPermiso($conexion, $id_registro, 'TRUE');
							}
							
							$arrayFirmaTTHH = array(
								'nombreFirma' => $nombreResponsableTH,
								'cargoFirma' => $cargoResponsableTH,
								'nombreAutorizado' => $nombreDirector,
								'cargoAutorizado' => $cargoDirector,
								'tthh' => 'si',
								'firma' => $firma,
								'identificador' => $identificadorTH
							);
							
							$constg = new Constantes();
							$construir=new GeneradorDocumentoPDF();
							$salidaReporteTCPDF =$constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/'.$salidaReporte;
							$construir->generarCertificado($salidaReporteTCPDF,$arrayDatos,$arrayTipo,$arraySituacionActual,$arrayFirmaTTHH,$arrayFirmaDE,$arrayFirmaRC);
							echo IN_MSG.'accion de reintegro No '.$id_registro.' generada-> '.$identificadorFuncionario;
						}
		   }
		   echo IN_MSG.'proceso terminado ';
		   $conexion->ejecutarConsulta("commit;");
		   $conexion->desconectar();
				} catch (Exception $ex){
					$conexion->ejecutarConsulta("rollback;");
					pg_close($conexion);
					echo IN_MSG.'Error de ejecucion ';
				}
			}
		} catch (Exception $ex) {
			echo IN_MSG.'Error de conexión a la base de datos';
		}

	}else{

		$minutoS2=microtime(true);
		$tiempo=$minutoS2-minutoS1;
		$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
		$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
		$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
		$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
		$arch = fopen("../../aplicaciones/logs/cron/accion_personal_".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);

	}
	?>
</body>
</html>
