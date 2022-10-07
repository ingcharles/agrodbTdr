<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$idarea=$_POST['idarea'];
	$identificadorRRHH = $_POST['identificadorRRHH'];
	$identificadorUsuario = $_POST['identificadorUsuario'];
	$estadoResp = $_POST['estadoResp'];
	
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				
				if($identificadorRRHH != $identificadorUsuario and $estadoResp == "activo" and $identificadorUsuario != ''){
					
					//$consul = $cc->obtenerResponsablesRRHH($conexion, '', '', $identificadorUsuario,'',$estado=NULL);
					$consul = $cc->actualizarEstadoResponsableRRHH ($conexion, $identificadorRRHH, 'inactivo');
					
					$consul = $cc->guardarNuevoResponsableRRHH ($conexion, $identificadorUsuario,$idarea);
					
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				}else{
					
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados';
				}
				
					$conexion->desconectar();
					echo json_encode($mensaje);
				
				} catch (Exception $ex){
					pg_close($conexion);
					echo json_encode($mensaje);
				}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>