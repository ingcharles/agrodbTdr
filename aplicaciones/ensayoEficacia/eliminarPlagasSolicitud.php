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
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');

	try {
		$conexion = new Conexion();
		$ce = new ControladorEnsayoEficacia();

		$ret=$ce->borrarPlagaProtocolo($conexion, $idProtocolo);
		if($ret['tipo']!=''){
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Las plagas han sido eliminadas.';
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