<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'id_siniestro' => htmlspecialchars ($_POST['id_siniestro'],ENT_NOQUOTES,'UTF-8'),
					'numeroFactura' => htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8'),
					'valorTotal' => htmlspecialchars ($_POST['valorTotal'],ENT_NOQUOTES,'UTF-8'));
	
	$concepto = $_POST['sConcepto'];
	$subTotal = $_POST['sTotal'];
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		if ($identificadorUsuarioRegistro != ''){
			$res= $cv-> abrirSiniestro($conexion, $datos['id_siniestro']);
			$siniestro = pg_fetch_assoc($res);
			
			if($siniestro['imagen_factura']!='' && count($concepto)!=0){
				
				$cv->actualizarDatosSiniestroFactura($conexion, $datos['id_siniestro'], $datos['numeroFactura'], $identificadorUsuarioRegistro);
				$cv->actualizarDatosSiniestroCierreFase($conexion, $datos['id_siniestro'], 3, $identificadorUsuarioRegistro);
				
				for ($i = 0; $i < count($concepto); $i++) {
					$cv -> ingresarDetalleSiniestro($conexion, $datos['id_siniestro'], $concepto[$i], $subTotal[$i]);
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Por favor, suba la factura escaneada emitida por la aseguradora y los valores correpondientes.';
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