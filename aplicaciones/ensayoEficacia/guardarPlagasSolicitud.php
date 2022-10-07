<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	//miro si solicitud ya existe
	$datoProtocolo=array();
	$yaExiste=false;
	$identificador= $_SESSION['usuario'];

	try{
		$idProtocolo=intval($_POST['id_protocolo']);
		if($idProtocolo>0){
			$datoProtocolo['id_protocolo'] = $idProtocolo;
			$yaExiste=true;
		}
	}catch(Exception $e){}

	
	if($yaExiste==true){
		$datoProtocolo=array();
		$datoProtocolo['id_protocolo']=$idProtocolo;
		$datoProtocolo['nivel']=intval($_POST['nivel']);
		try{
			$res=$ce -> guardarProtocolo($conexion,$datoProtocolo);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Detalle de plagas actualizado';
		}
		catch(Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			echo json_encode($mensaje);
		}
	}

	
	$conexion->desconectar();

	echo json_encode($mensaje);
} catch (Exception $ex) {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error de conexión a la base de datos';
		echo json_encode($mensaje);
}
?>