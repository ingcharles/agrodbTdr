<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$datos = array('cur' => htmlspecialchars ( $_POST['cur'],ENT_NOQUOTES,'UTF-8'),
				   'fecha' =>  htmlspecialchars ($_POST['fecha']),
		           'identificador' =>  htmlspecialchars ($_POST['identificador']),
					'idLiquidacionVacaciones' => htmlspecialchars($_POST['idLiquidacionVacaciones'])
	             );

		try {
				$conexion = new Conexion();
				$cv = new ControladorVacaciones();
				
				//***************proceso para registrar liquidaciones*******************************
				$cv->guardarDetalleDescuentoLiquidar($conexion, $datos['idLiquidacionVacaciones'], $datos['cur'], $datos['fecha'], $_SESSION['usuario']);
				//**********************************************************************************
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
								
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