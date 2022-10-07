<?php

session_start();

require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';

$mensaje = array();
$mensaje['estado'] = 'NO';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	
	$ce = new ControladorEnsayoEficacia();

	$id_protocolo=$_POST['id_protocolo'];
	
	try {				
		$res=$ce -> eliminarIngredientesActivosProtocolo($conexion,$id_protocolo);
		
		if($res==null || sizeof($res)==0){
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			$mensaje['estado'] = 'error';
		}
		else{
			$mensaje['mensaje'] = $res;	
			$mensaje['estado'] = 'OK';
		}
		
		$conexion->desconectar();
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
	}

}
catch(Exception $ex ){}

echo json_encode($mensaje);

?>