
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
	if ($_SERVER['REMOTE_ADDR'] == ''){

		require_once '../../clases/Conexion.php';
		require_once '../../clases/ControladorCatastro.php';
		require_once '../../clases/ControladorMonitoreo.php';

		define('IN_MSG', '<br/> >>> ');
		try{
			$conexion = new Conexion();
			$cc = new ControladorCatastro();
			$cm = new ControladorMonitoreo();
			$conexion->ejecutarConsulta("begin;");

			$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_TTHH_RESPONSAB');

			if ($resultadoMonitoreo){

				$fechaActual = strtotime(date('Y-m-d'));

				try{
					echo IN_MSG . 'Iniciar subrogacion';
					$consultaSubrogar = $cc->obtenerSubrogacionesFuncionarios($conexion, '', '', '');

					while ($consulta = pg_fetch_assoc($consultaSubrogar)){

						if ($consulta['identificador_subrogador'] != '' and $consulta['identificador'] != '' and $consulta['area'] != ''){

							$fechaIni = strtotime($consulta['fecha_inicio']);
							$fechaFin = strtotime($consulta['fecha_fin']);

							// ------------------------------------activar subrogacion de funcionarios--------------------------------------------------------------------------------
							if ($fechaActual >= $fechaIni && $fechaActual <= $fechaFin){
								if ($consulta['estado'] == 'creado'){
									echo IN_MSG . 'activar subrogacion funcionarios';
									// -------------------------------inactivar funcionario responsable-------------------------------------------------------------
									$cc->inactivarActivarResponsables($conexion, $consulta['area'], 0, 'false', 0, '', 3);

									// -------------------------------activar funcionario resonsable-----------------------------------------------------------------
									if (pg_num_rows($cc->verificarExisteResponsable($conexion, $consulta['area'], $consulta['identificador_subrogador']))){
										$cc->inactivarActivarResponsables($conexion, $consulta['area'], 1, 'true', 1, $consulta['identificador_subrogador'], 2);
									}else{
										$cc->crearResponsable($conexion, $consulta['area'], $consulta['identificador_subrogador'], 2, 'true', 1, 1);
									}
									// -----------------------------------------------------------------------------------
									$aplicacion = $cc->devolverAplicacionesNuevas($conexion, $consulta['identificador'], $consulta['identificador_subrogador']);
									while ($consultaAplicacion = pg_fetch_assoc($aplicacion)){
										$cc->asignarAplicacionResponsable($conexion, $consulta['identificador_subrogador'], $consultaAplicacion['id_aplicacion'], $consultaAplicacion['mensaje_notificacion']);
									}
									// -----------------------------------------------------------------------------------
									$perfil = $cc->devolverPerfilesNuevos($conexion, $consulta['identificador'], $consulta['identificador_subrogador']);
									while ($consultaPerfil = pg_fetch_assoc($perfil)){
										$cc->asignarPerfilResponsable($conexion, $consulta['identificador_subrogador'], $consultaPerfil['id_perfil']);
									}

									$cc->actualizarResponsablesEstado($conexion, 'activo', $consulta['id_responsable']);
									echo IN_MSG . 'funcionario activado->' . $consulta['identificador_subrogador'];
								}
							}
							// ---------------------------------------finalizar subrogacion de funcionarios----------------------------------------------------------------------------------------------------------
							if ($fechaActual > $fechaFin){
								if ($consulta['estado'] == 'activo'){
									echo IN_MSG . 'finalizar subrogacion funcionarios';
									echo IN_MSG . 'funcionario inactivado->' . $consulta['identificador_subrogador'];
									// -------------------------------inactivar funcionario responsable-------------------------------------------------------------
									$cc->inactivarActivarResponsables($conexion, $consulta['area'], 0, 'false', 0, '', 3);

									// -------------------------------activar funcionario resonsable-----------------------------------------------------------------
									if (pg_num_rows($cc->verificarExisteResponsable($conexion, $consulta['area'], $consulta['identificador']))){
										$cc->inactivarActivarResponsables($conexion, $consulta['area'], 1, 'true', 1, $consulta['identificador'], 1);
									}else{
										$cc->crearResponsable($conexion, $consulta['area'], $consulta['identificador'], 1, 'true', 1, 1);
									}
									// --------------restaurar perfiles y aplicaciones en funcionario----------------------------------------------------------------
									$aplicacion = $cc->devolverAplicacionSubrogar($conexion, $consulta['id_responsable']);
									while ($consultaAplicacion = pg_fetch_assoc($aplicacion)){
										$cc->eliminarAplicacionUsuario($conexion, $consultaAplicacion['id_aplicacion'], $consulta['identificador_subrogador']);
									}
									$consulPerfil = $cc->devolverPerfilSubrogar($conexion, $consulta['id_responsable']);
									while ($consultaPerf = pg_fetch_assoc($consulPerfil)){
										$cc->elminarPerfilUsuario($conexion, $consultaPerf['id_perfil'], $consulta['identificador_subrogador']);
									}
									$cc->actualizarResponsablesEstado($conexion, 'inactivo', $consulta['id_responsable']);
									// ------------------------------------------------------------------------------------------------------------------------------
								}
							}
							
						  }else{
						  	echo IN_MSG . 'Error de ejecución: identificador->'.$consulta['identificador_subrogador'].' identificador subrogador ->'.$consulta['identificador'].' área ->'.$consulta['area'];
						  }
						}
						$conexion->ejecutarConsulta("commit;");
						$conexion->desconectar();
					
				}catch (Exception $ex){
					$conexion->ejecutarConsulta("rollback;");
					$conexion->desconectar();
					echo IN_MSG . 'Error de ejecución';
				}
			}
		}catch (Exception $ex){

			echo IN_MSG . 'Error de conexión a la base de datos';
		}
	}else{

		$minutoS1 = microtime(true);
		$minutoS2 = microtime(true);
		$tiempo = $minutoS2 - $minutoS1;
		$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
		$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
		$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
		$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
		$arch = fopen("../../aplicaciones/logs/cron/actualizar_responsables_" . date("d-m-Y") . ".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);
	}

	?>
</body>
</html>
