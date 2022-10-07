
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
	if($_SERVER['REMOTE_ADDR'] == ''){
		try {
			require_once '../../clases/Conexion.php';
			require_once '../../clases/ControladorVacaciones.php';
			require_once '../../clases/ControladorMonitoreo.php';
			require_once '../../clases/ControladorUsuarios.php';
			require_once '../../clases/ControladorAreas.php';
			require_once '../../clases/ControladorReportes.php';

			$conexion = new Conexion();
			$cc = new ControladorVacaciones();
			$cm = new ControladorMonitoreo();
			$cu = new ControladorUsuarios();
			$ca = new ControladorAreas();

			$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_TTHH_ACCI_PERS');

			if($resultadoMonitoreo){

				define('IN_MSG','<br/> >>> ');

				try {
					set_time_limit(2000);
					echo IN_MSG.'Generar Acciones de Personal<br>';
					if( pg_num_rows($cc->consultarPermisosAprobados($conexion))){

						$consulta = $cc->consultarPermisosAprobados($conexion);
						while($fila = pg_fetch_assoc($consulta)) {
							$id_registro=$fila['id_permiso_empleado'];
							$identificadorFuncionario = $fila['identificador'];
							$identificadorTH=$fila['identificadorrhh'];
							$id_area_permiso=$fila['id_area_permiso'];

							$codigoAccionPersonal = '';

							$filaSolicitud = pg_fetch_assoc($cc->obtenerDatosPermiso($conexion,$id_registro));

							//Obtiene el tiempo disponible para permisos del funcionario por años
							$tiempoDisponible = pg_fetch_assoc($cc->obtenerTiempoDisponibleFuncionario($conexion, $identificadorFuncionario));

							//Obtiene el subtipo de permiso de la solicitud
							$subtipos = pg_fetch_assoc($cc->obtenerSubTipoPermiso($conexion,null,$filaSolicitud['sub_tipo']));
							$codigoSubtipoPermiso = $subtipos['codigo'];

							//Obtiene información de jefe superior
							$filaDirector = pg_fetch_assoc($cc->obtenerNombreDirector($conexion,$filaSolicitud['identificador_jefe_superior']));
							$nombreDirector = $filaDirector['directorath'];
							$cargoDirector = $filaDirector['puestoth'];

							//Obtiene información de responsable de talento humano
							$filaDirectorTH = pg_fetch_assoc($cc->obtenerNombreDirector($conexion,$identificadorTH));
							$nombreResponsableTH = $filaDirectorTH['directorath'];
							$cargoResponsableTH = $filaDirectorTH['puestoth'];
							$idArea = $ca->areaUsuario($conexion, $identificadorTH);

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

							//Generar texto para acción de personal del catálogo provisto por TH
							$arrayFechaHoraInicio = explode(" ", $filaSolicitud['fecha_inicio']);
							$fechaInicio = $arrayFechaHoraInicio[0];
							$horaInicio = $arrayFechaHoraInicio[1];

							//-------------------------------------------------//
							$anioPermiso = date("Y", strtotime($filaSolicitud['fecha_inicio']));
							$numMes=date('m', strtotime($filaSolicitud['fecha_inicio']));
							$meses = array('01' => 'enero','02' => 'febrero','03' => 'marzo','04' => 'abril','05' => 'mayo','06' =>'junio','07' => 'julio',
									'08' => 'agosto','09' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' =>'diciembre');
							$mesPermiso = $meses[$numMes];
							//-------------------------------------------------//

							$arrayFechaHoraFin = explode(" ", $filaSolicitud['fecha_fin']);
							$fechaFin = $arrayFechaHoraFin[0];
							$horaFin = $arrayFechaHoraFin[1];

							$arrayFechaHoraSuceso = explode(" ", $filaSolicitud['fecha_suceso']);
							$fechaSuceso = $arrayFechaHoraSuceso[0];
							$horaSuceso = $arrayFechaHoraSuceso[1];

							//Días y horas solicitadas en el permiso
							$tiemposDescontado=$cc->devolverFormatoDiasDisponibles($filaSolicitud['minutos_utilizados']);
							$tiempoFinSemana=($filaSolicitud['minutos_utilizados']/1.36)*0.36;
							$tiempoFinSemana=$cc->devolverFormatoDiasDisponibles(round($tiempoFinSemana));


							//$filaSolicitud['minutos_utilizados'] = $filaSolicitud['minutos_utilizados']/1.27;
							$filaSolicitud['minutos_utilizados'] = $filaSolicitud['minutos_utilizados']/1.36;

							$dias=floor(intval($filaSolicitud['minutos_utilizados'])/480);
							$horas=floor((intval($filaSolicitud['minutos_utilizados'])-$dias*480)/60);
							$minutos=(intval($filaSolicitud['minutos_utilizados'])-$dias*480)-$horas*60;


							$diasDescontados = $cc->devolverFormatoDiasDisponibles($filaSolicitud['minutos_utilizados']);

							//Días fin de semana
							//$diasFinSemana = $filaSolicitud['minutos_utilizados']*0.27;
							$diasFinSemana = $filaSolicitud['minutos_utilizados']*0.36;
							$diasFinSemana=$cc->devolverFormatoDiasDisponibles($diasFinSemana);

							$diasSaldo='';
							if($filaSolicitud['minutos_actuales'] != ''){
								$diasSaldo=$cc->devolverFormatoDiasDisponibles($filaSolicitud['minutos_actuales']);

								$diasSaldo = 'le restan '.$diasSaldo;
							}

							$anioSaldo = $tiempoDisponible['anio'];

							//Obtener destino de comisión local o provincial
							$lugar = $filaSolicitud['destino_comision'];

							$texto = $subtipos['texto_accion_personal'];

							$texto = str_replace('$fechaInicio', $fechaInicio, $texto);
							$texto = str_replace('$horaInicio', $horaInicio, $texto);
							$texto = str_replace('$fechaFin', $fechaFin, $texto);
							$texto = str_replace('$horaFin', $horaFin, $texto);
							$texto = str_replace('$fechaSuceso', $fechaSuceso, $texto);
							$texto = str_replace('$horaSuceso', $horaSuceso, $texto);
							$texto = str_replace('$diasSaldo', $diasSaldo, $texto);
							$texto = str_replace('$diasFinSemana', $diasFinSemana, $texto);
							$texto = str_replace('$diasDescontados', $diasDescontados, $texto);
							$texto = str_replace('$anioSaldo', $anioSaldo, $texto);
							$texto = str_replace('$lugar', $lugar, $texto);
							$texto = str_replace('$mesPermiso', $mesPermiso, $texto);
							$texto = str_replace('$anioPermiso', $anioPermiso, $texto);
							$texto = str_replace('$tiemposDescontado', $tiemposDescontado, $texto);
							$texto = str_replace('$tiempoFinSemana', $tiempoFinSemana, $texto);

							//echo $texto;
							$textoAccionPersonal = $texto;

							///JASPER///

							//Ruta del reporte compilado por Jasper y generado por IReports
							$jru = new ControladorReportes();

							$filename = $filaSolicitud['identificador'].'-AccionPersonal-'.$id_registro.'.pdf';
							$ReporteJasper='aplicaciones/vacacionesPermisos/reportes/accionPersonal.jrxml';
							$salidaReporte = 'aplicaciones/vacacionesPermisos/accionPersonal/'.$filename;
							
							$parameters['parametrosReporte'] = array(
								'identificador'=> $identificadorFuncionario,
								'codigoAccionPersonal'=> $codigoAccionPersonal,
								'textoAccionPersonal'=> $textoAccionPersonal,
								'nombreResponsableTH'=> $nombreResponsableTH,
								'cargoResponsableTH'=> $cargoResponsableTH,
								'nombreDirector'=> $nombreDirector,
								'cargoDirector'=> $cargoDirector,
								'rigeDesde'=> $fechaInicio,
								'resolucion'=> ' '
							);

							if($codigoSubtipoPermiso == "VA-VA"){
								$parameters['parametrosReporte'] += array('vacaciones'=> ' ');
							}else{
								$parameters['parametrosReporte'] += array('licencia'=> ' ');
							}

							//CAMBIAR RUTA IMAGEN A accionPersonal
							$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'defecto');
							$cc->actualizarRutaDocumento($conexion, $id_registro, $salidaReporte);
							$cc->actualizarEstadoPermiso($conexion, $id_registro, 'InformeGenerado');
							$cc->actualizarNumeroAccionPersonal($conexion, $id_registro, $numeroAccionPersonal);

							//Registro de observaciones del proceso
							$cc->agregarObservacion($conexion, 'El usuario '.$identificadorTH.' ha creado la acción de personal para la solicitud de '.$filaSolicitud['descripcion_subtipo'].' con fecha de salida '
									.$filaSolicitud['fecha_inicio'].', fecha de retorno '.$filaSolicitud['fecha_fin'].' y con '.$filaSolicitud['minutos_utilizados'].' minutos solicitados', $id_registro, $identificadorTH);

							echo IN_MSG.'accion de personal No '.$id_registro.' generada-> '.$identificadorFuncionario;
						}
		   }
		   echo IN_MSG.'proceso terminado ';
		   $conexion->desconectar();
				} catch (Exception $ex){
					pg_close($conexion);
					echo IN_MSG.'Error de ejecucion '.$ex;
				}
			}
		} catch (Exception $ex) {
			echo IN_MSG.'Error de conexión a la base de datos';
		}

	}else{

		$minutoS1=microtime(true);
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
