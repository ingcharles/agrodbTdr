<?php
session_start();



	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once 'clases/GeneradorDocumentoPlaguicida.php';

	$conexion=new Conexion();
	$ce=new ControladorEnsayoEficacia();

	$cGenerador=new GeneradorDocumentoPlaguicida();

	$id_solicitud = $_POST['id_solicitud'];


	$firmante='';
	$firmanteCargo='';
	$respuesta=$ce->obtenerFuncionarioXarea($conexion,'CGRIA');
	if(sizeof($respuesta)>0){
		$firmante=$respuesta[0]['nombre'].' '.$respuesta[0]['apellido'];
		$firmanteCargo=$respuesta[0]['cargo'];
	}

	$mensaje=$cGenerador->generarPuntosMinimos($conexion,$id_solicitud,$firmante,$firmanteCargo);

	$conexion->desconectar();

	echo json_encode($mensaje);

?>


