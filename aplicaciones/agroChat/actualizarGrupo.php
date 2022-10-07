<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$data  = json_decode($_POST['data'], true);
$grupo = htmlspecialchars ($_POST['grupo'],ENT_NOQUOTES,'UTF-8');
$idGrupo = htmlspecialchars ($_POST['idgrupo'],ENT_NOQUOTES,'UTF-8');
$identificador = htmlspecialchars ($_POST['usuario'],ENT_NOQUOTES,'UTF-8');

$conexion = new Conexion();
$cc = new ControladorChat();

try{
		
	try {
	    
	    $conexion->ejecutarConsulta("begin;");
		
		$items = array();
		$busqueda="";
		
		$cc->actualizarGrupo($conexion, $idGrupo, $grupo);
		$cc->elmininarContactosGrupos($conexion, $idGrupo);
		
		$guardarDetalle="";

		foreach ($data as $key1 => $val1){		   
		    $guardarDetalle.= "('".$idGrupo ."','".$val1['identificadorUsuario']."'),";
		}	
		
		$trim = rtrim($guardarDetalle,",");
		$trim.= ",('".$idGrupo."','".$identificador."')";
		$cc->guardarContactosGrupo($conexion,$trim);
		$items[] = array(grupo=>$idGrupo);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $items;
		

			
	    $conexion->ejecutarConsulta("commit;");
	    
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>