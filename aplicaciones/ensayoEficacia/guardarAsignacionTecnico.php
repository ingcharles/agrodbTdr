<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once './clases/Transaccion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificador= $_SESSION['usuario'];
	$idProtocolo=$_POST['id_protocolo'];
	$id_tramite=$_POST['id_tramite'];
	$id_tramite_flujo=$_POST['id_tramite_flujo'];
	$tecnico=$_POST['tecnico'];
	$conexion = new Transaccion();
	try {
		$conexion->Begin();
		$ce = new ControladorEnsayoEficacia();
		$ce->reasignarTecnicoTramite($conexion,$id_tramite,$tecnico);
		$ce->reasignarTecnicoTramiteFlujo($conexion,$id_tramite_flujo,$tecnico);
		$conexion->Commit();
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Técnico ha sido asignado.';
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$conexion->Rollback();
		$conexion->desconectar();
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al asignar el técnico";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	pg_close($conexion);
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>