<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array( 'mantenimiento' => htmlspecialchars ($_POST['mantenimiento'],ENT_NOQUOTES,'UTF-8'),
					'numeroFactura' => htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8'),
					'kilometraje' =>  htmlspecialchars ($_POST['kilometraje'],ENT_NOQUOTES,'UTF-8'),
					'placa' => htmlspecialchars ($_POST['placa'],ENT_NOQUOTES,'UTF-8'),
					'valorTotal' => htmlspecialchars ($_POST['valorTotal'],ENT_NOQUOTES,'UTF-8'),
					'razonIncrementoKm' => htmlspecialchars ($_POST['razonKilometraje'],ENT_NOQUOTES,'UTF-8'));
	
	$concepto = ($_POST['sConcepto']);
	$subTotal = ($_POST['sTotal']);
	 
	if($datos['kilometraje'] == ''){
		$datos['kilometraje'] = 0;
	}
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		$res= $cv-> abrirMantenimiento($conexion, $datos['mantenimiento']);
		$mantenimiento = pg_fetch_assoc($res);
		
		$res = $cv -> obtenerEstadoVehiculo($conexion, $datos['placa']);
		$vehiculo = pg_fetch_assoc($res);
		
		if ($identificadorUsuarioRegistro != ''){
				
			if($mantenimiento['imagen_factura']!=''){
				
				$cv->actualizarDatosMantenimientoDetalle($conexion, $datos['mantenimiento'],$datos['valorTotal'], $datos['numeroFactura'], $datos['kilometraje'], $identificadorUsuarioRegistro, $datos['razonIncrementoKm']);
				
				$tipo = explode('-',$mantenimiento['tipo_mantenimiento']);
				
				if($mantenimiento['orden_trabajo']==''){
					if($tipo[1]=='Preventivo')
						$cv->actualizarKilometrajeVehiculo($conexion,$datos['placa'], $datos['kilometraje'],'Inicial');
					else
						$cv->actualizarKilometrajeVehiculo($conexion,$datos['placa'], $datos['kilometraje'],'Actual');
				}
					
				for ($i = 0; $i < count($concepto); $i++) {
					$cv -> ingresarDetalleMantenimiento($conexion, $datos['mantenimiento'], $concepto[$i], $subTotal[$i]);
				}
				
				
				if($vehiculo['estado'] == 2){
					$cv ->actualizarEstadoVehiculo($conexion, $datos['placa'], 'Liberar');
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