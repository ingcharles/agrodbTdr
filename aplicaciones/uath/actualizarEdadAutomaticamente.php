<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
	if($_SERVER['REMOTE_ADDR']==''){

		try {
			require_once '../../clases/Conexion.php';
			require_once '../../clases/ControladorCatastro.php';
			require_once '../../clases/ControladorMonitoreo.php';

			define('IN_MSG','<br/> >>> ');

			$conexion = new Conexion();
			$ca = new ControladorCatastro();
			$cm = new ControladorMonitoreo();

			$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_TTHH_EDAD');

			if($resultadoMonitoreo){

				echo IN_MSG.'Actualizar Edad de funcionarios. '.date("Y-m-d H:i:s A");

				$res=$ca->obtenerFechaNacimiento($conexion,date("d"),date("m"));
				while($fila = pg_fetch_assoc($res)){

					echo IN_MSG.'funcionario '.$fila['identificador'].' año '.$ano.' edad anterior '.$fila['edad'].' edad actual '.$fila['nuevaedad'];

					$ca->actualizarDatosFichaEmpleado($conexion, $fila['identificador'], $fila['nuevaedad']);

				}
				echo IN_MSG.'Proceso terminado.';
			}

		} catch (Exception $ex){
			echo $ex;
		}
	}else {

		$s=microtime(true);
		$s1=microtime(true);
		$t=$s1-$s;
		$xcadenota = date("d/m/Y").", ".date("H:i:s");
		$xcadenota.= ";REMOTE ".$_SERVER['REMOTE_ADDR'];
		$xcadenota.= ";HTTP ".$_SERVER['HTTP_REFERER'];
		$xcadenota.= "; ".$t." seg\n";
		$arch = fopen("../../aplicaciones/uath/lib_logs/logs/actualizar_edad_acceso".date("d-m-Y").".txt", "a+");
		fwrite($arch, $xcadenota);
		fclose($arch);
	}
	?>
</body>
</html>
