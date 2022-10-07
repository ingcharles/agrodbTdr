<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificador= $_SESSION['usuario'];
	$idProtocolo=intval($_POST['id_protocolo']);
	$datoProtocolo['id_protocolo'] = $idProtocolo;
	
	$datoProtocolo['nivel']=intval($_POST['nivel']);


	try {
		$conexion = new Conexion();
		$ce = new ControladorEnsayoEficacia();

		$res=$ce -> guardarProtocolo($conexion,$datoProtocolo);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_protocolo'];
		
		$mensaje['id'] = $idProtocolo;
		$mensaje['dato'] = $res['resultado'];
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Esta fase ha sido actualizada correctamente';


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