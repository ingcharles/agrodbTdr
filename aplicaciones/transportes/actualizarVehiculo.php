<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array('marca' => htmlspecialchars ($_POST['marca'],ENT_NOQUOTES,'UTF-8'),
			'modelo' => htmlspecialchars ($_POST['modelo'],ENT_NOQUOTES,'UTF-8'),
			'tipo' =>  htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8'),
			'combustible' => htmlspecialchars ($_POST['combustible'],ENT_NOQUOTES,'UTF-8'),
			'carroceria' => htmlspecialchars ($_POST['carroceria'],ENT_NOQUOTES,'UTF-8'),
			'color_uno' => htmlspecialchars ($_POST['color_uno'],ENT_NOQUOTES,'UTF-8'),
			'color_dos' => htmlspecialchars ($_POST['color_dos'],ENT_NOQUOTES,'UTF-8'),
			'pais_origen' => htmlspecialchars ($_POST['pais_origen'],ENT_NOQUOTES,'UTF-8'),
			'condicion' => htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8'),
			'fabricacion' => htmlspecialchars ($_POST['fabricacion'],ENT_NOQUOTES,'UTF-8'),
			'tonelaje' => htmlspecialchars ($_POST['tonelaje'],ENT_NOQUOTES,'UTF-8'),
			'cilindraje' => htmlspecialchars ($_POST['cilindraje'],ENT_NOQUOTES,'UTF-8'),
			'motor' => htmlspecialchars ($_POST['motor'],ENT_NOQUOTES,'UTF-8'),
			'chasis' => htmlspecialchars ($_POST['chasis'],ENT_NOQUOTES,'UTF-8'),
			'fecha_compra' => htmlspecialchars ($_POST['fecha_compra'],ENT_NOQUOTES,'UTF-8'),
			'factura_compra' => htmlspecialchars ($_POST['factura_compra'],ENT_NOQUOTES,'UTF-8'),
			'valor_compra' => htmlspecialchars ($_POST['valor_compra'],ENT_NOQUOTES,'UTF-8'),
			'area' => htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8'),
			'responsable' => htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'),
			'avaluo' => htmlspecialchars ($_POST['avaluo'],ENT_NOQUOTES,'UTF-8'),
			'observaciones' => htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8'));
	
	$placa = htmlspecialchars ($_POST['numPlaca'],ENT_NOQUOTES,'UTF-8');
	$idVehiculo = htmlspecialchars ($_POST['idVehiculo'],ENT_NOQUOTES,'UTF-8');
	
	if($datos['valor_compra'] == ''){
		$datos['valor_compra'] = 0;
	}

	
	try {
		$conexion = new Conexion();
		
		if ($identificadorUsuarioRegistro != ''){		
			
			$cv = new ControladorVehiculos();
			
			$cv->actualizarDatosVehiculo($conexion,$idVehiculo,$placa, $datos['marca'], $datos['modelo'], $datos['tipo'], $datos['combustible'], $datos['carroceria'], $datos['color_uno'], $datos['color_dos'], 
										$datos['pais_origen'], $datos['condicion'], $datos['fabricacion'], $datos['tonelaje'], $datos['cilindraje'], $datos['motor'], $datos['chasis'], $datos['fecha_compra'], 
										$datos['factura_compra'], $datos['valor_compra'], $datos['area'], $datos['responsable'], $datos['avaluo'], $datos['observaciones'], $identificadorUsuarioRegistro);
			
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