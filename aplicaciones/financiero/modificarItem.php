<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idItem = ($_POST['idItem']);
	$concepto = htmlspecialchars ($_POST['concepto'],ENT_NOQUOTES,'UTF-8');
	$unidad = ($_POST['unidad']);
	$valor = ($_POST['valor']);
	$iva = htmlspecialchars ($_POST['iva'],ENT_NOQUOTES,'UTF-8');
	$partidaPresupuestaria = htmlspecialchars ($_POST['partidaPresupuestaria'],ENT_NOQUOTES,'UTF-8');
	$unidadMedida = htmlspecialchars ($_POST['unidadMedida'],ENT_NOQUOTES,'UTF-8');
	$subsidio = ($_POST['subsidio']);
	//INICIO EJAR
	$poseeExceso = $_POST['exceso'];
	$itemsUsuadoPara = $_POST['itemUsadoPara'];
	$idItemExceso = ($_POST['idItemExceso'] == "" ? 'NULL' : $_POST['idItemExceso']);
	//FIN EJAR
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();

		$cf->actualizarItem($conexion, $idItem, $concepto, $unidad, $valor, $iva, $partidaPresupuestaria, $unidadMedida, $subsidio, $poseeExceso, $itemsUsuadoPara, $idItemExceso);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fuerón actualizados';

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