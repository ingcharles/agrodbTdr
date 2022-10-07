<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	
	$idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$ingredienteActivo = htmlspecialchars ($_POST['ingredienteActivo'],ENT_NOQUOTES,'UTF-8');
	$ingredienteQuimico = htmlspecialchars ($_POST['ingredienteQuimico'],ENT_NOQUOTES,'UTF-8');
	
	$cas = htmlspecialchars ($_POST['cas'],ENT_NOQUOTES,'UTF-8');
	$formulaQuimica = htmlspecialchars ($_POST['formulaQuimica'],ENT_NOQUOTES,'UTF-8');
	$grupoQuimico = htmlspecialchars ($_POST['grupoQuimico'],ENT_NOQUOTES,'UTF-8');
	
	$ingredienteQuimico = str_replace("'", "''", $ingredienteQuimico);
	$cas = str_replace("'", "''", $cas);
	$formulaQuimica = str_replace("'", "''", $formulaQuimica);
	$grupoQuimico = str_replace("'", "''", $grupoQuimico);
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
				
		$cr -> guardarNuevoIngredienteActivo($conexion, $ingredienteActivo, $idArea, $ingredienteQuimico, $cas, $formulaQuimica, $grupoQuimico);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
				
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