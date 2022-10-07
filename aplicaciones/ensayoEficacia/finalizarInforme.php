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
		if($idProtocolo>0)
			$datoProtocolo['id_protocolo'] = $idProtocolo;
	}catch(Exception $e){}
	
	
	$acepto= htmlspecialchars ($_POST['boolAcepto'],ENT_NOQUOTES,'UTF-8');
	if($acepto=='SI')
	{
	//guardo el protocolo
	$normativa= htmlspecialchars ($_POST['normativa'],ENT_NOQUOTES,'UTF-8');
	$motivo= htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8');
	$cultivo= htmlspecialchars ($_POST['cultivo_menor'],ENT_NOQUOTES,'UTF-8');

	$datoProtocolo['estado'] = 'aprobarInformeDir';
	
					
	try {
		
				
		$res=$ce -> guardarProtocolo($conexion,$datoProtocolo);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_protocolo'];
		else
			$fila=$res['resultado'];
		
		$mensaje['id'] = $idProtocolo;
		$mensaje['dato'] = $res['resultado'];
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
	}
	else{
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Para finalizar acepte las condiciones";
		echo json_encode($mensaje);

	}
	} catch (Exception $ex) {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error de conexión a la base de datos';
		echo json_encode($mensaje);
	}

?>