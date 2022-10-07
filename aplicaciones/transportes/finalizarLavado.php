<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'numeroFactura' => htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8'),
					'kilometraje' =>  htmlspecialchars ($_POST['kilometraje'],ENT_NOQUOTES,'UTF-8'),
					'valorTotal' => htmlspecialchars ($_POST['valorTotal'],ENT_NOQUOTES,'UTF-8'));
	
	$lavado = ($_POST['id']);
	 
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		$res= $cv-> abrirMantenimiento($conexion, $lavado[0]);
		$mantenimiento = pg_fetch_assoc($res);
		
		if ($identificadorUsuarioRegistro != ''){
			if($mantenimiento['imagen_factura']!=''){
				for ($i = 0; $i < count($lavado); $i++) {
					$cv -> actualizarDatosMantenimientoDetalle($conexion, $lavado[$i], $datos['valorTotal'], $datos['numeroFactura'], 0, $identificadorUsuarioRegistro);
				}
										
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
					
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Por favor, suba la factura escaneada emitida por el taller.';
			}
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