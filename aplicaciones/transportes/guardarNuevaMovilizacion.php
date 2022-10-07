<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
		
		$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
		$datos = array('tipoMovilizacion' => htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8'),
						'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
						'observacion_ocupante' => htmlspecialchars ($_POST['observacion_ocupante'],ENT_NOQUOTES,'UTF-8'));
		
		$sitio_id= ($_POST['sitio_id']);
		$sitio_nombre= ($_POST['sitio_nombre']);
		$cuidad_nombre= ($_POST['ciudad_nombre']);
		$fecha_desde= ($_POST['fechaDe']);
		$fecha_hasta= ($_POST['fechaHa']);
		$ocupante_id= ($_POST['ocupante_id']);
		$ocupante_nombre= ($_POST['ocupante_nombre']);
		
		$descripcion= ($_POST['descripcion']);
		
		$motivo = implode ( ',' , $descripcion );
		
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
	try {
			
		if ($identificadorUsuarioRegistro != ''){

			$res = $cv->generarNumeroMovilizacion($conexion, '%'.$_SESSION ['codigoLocalizacion'].'%', "'".'MOV-'.$_SESSION['codigoLocalizacion'].'-'."'");
			$movilizacion = pg_fetch_assoc($res);
			$incremento = $movilizacion['numero'] + 1;
			$numero = 'MOV-'.$_SESSION['codigoLocalizacion'].'-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
						
			$movilizacion = $cv->guardarMovilizacion($conexion,$numero,$datos['tipoMovilizacion'],$motivo,$datos['observacion'],$_SESSION['nombreLocalizacion'],$datos['observacion_ocupante'], $identificadorUsuarioRegistro);
			$fila =  pg_fetch_assoc($movilizacion);
			
			
			for ($i = 0; $i < count ($sitio_id); $i++) {
				if($sitio_id[$i] != "Otro"){
					$cv -> guardarMovilizacionRutas($conexion, $fila['id_movilizacion'], $sitio_nombre[$i], $fecha_desde[$i], $fecha_hasta[$i]);
				}else{
					$localizacion = $sitio_nombre[$i] . ' - '. $cuidad_nombre[$i];
					$cv -> guardarMovilizacionRutas($conexion, $fila['id_movilizacion'], $localizacion, $fecha_desde[$i], $fecha_hasta[$i]);
					 
				}  
					
			}
			
			for ($i = 0; $i < count ($ocupante_id); $i++) {
				if($ocupante_id[$i] != "Otro")
					$cv -> guardarMovilizacionOcupantes($conexion, $fila['id_movilizacion'], $ocupante_id[$i]);
			}
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
		}
		
		$conexion->desconectar();
		
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
