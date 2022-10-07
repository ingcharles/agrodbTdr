<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$datos = array ('id_combustible' => htmlspecialchars ($_POST['id_combustible'],ENT_NOQUOTES,'UTF-8'),
					'fechaLiquidacion' => htmlspecialchars ($_POST['fechaLiquidacion'],ENT_NOQUOTES,'UTF-8'),
					'valorLiquidado' => htmlspecialchars ($_POST['valorLiquidado'],ENT_NOQUOTES,'UTF-8'),
					'id_gasolinera' => htmlspecialchars ($_POST['id_gasolinera'],ENT_NOQUOTES,'UTF-8'),
					'tipo_combustible' => htmlspecialchars ($_POST['tipo_combustible'],ENT_NOQUOTES,'UTF-8'),
					'montoSolicitado' => htmlspecialchars ($_POST['monto_solicitado'],ENT_NOQUOTES,'UTF-8'),
					'galonesSolicitados' => htmlspecialchars ($_POST['galones_solicitados'],ENT_NOQUOTES,'UTF-8'),
					'razonCambio' => htmlspecialchars ($_POST['razonCambio'],ENT_NOQUOTES,'UTF-8'));
	
	try {
		$conexion = new Conexion();
		$cv = new ControladorVehiculos();
		
		$combustible = $cv->abrirCombustible($conexion, $datos['id_combustible']);
		
		if ($identificadorUsuarioRegistro != ''){
			if (pg_fetch_result($combustible, 0, 'comprobante_gasolinera') != null){
				$res = $cv-> abrirGasolinera($conexion, $datos['id_gasolinera']);
				$gasolinera = pg_fetch_assoc($res);
				
		
				$saldo_actual = $gasolinera['saldo_disponible'] - $datos['valorLiquidado'];
				$cantidad_galones =  round($datos['valorLiquidado'] / $gasolinera[strtolower($datos['tipo_combustible'])],2);
				
				
				if($saldo_actual < 0){
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'No dispone de cupo suficiente para realizar esta liquidación';
				}else{
					$cv->actualizarDatosCombustibleDetalle($conexion, $datos['id_combustible'], $datos['fechaLiquidacion'], $datos['valorLiquidado'], $cantidad_galones);
					$cv->actualizarCupoGasolinera($conexion, $datos['id_gasolinera'], $saldo_actual);
					
					if($datos['valorLiquidado'] != $datos['razonCambio']){
						$cv->actualizarRazonCambioCombustible($conexion, $datos['id_combustible'], $datos['razonCambio']);
					}
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				}
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Debe subir el comprobante de carga de combustible emitido por la gasolinera.";
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