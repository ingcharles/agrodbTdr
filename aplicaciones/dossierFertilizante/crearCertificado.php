<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';

	require_once 'clases/GeneradorCertificadosFertilizante.php';

	$conexion=new Conexion();

	$cu=new ControladorUsuarios();
	$ccert=new GeneradorCertificadosFertilizante();
	$ce=new ControladorEnsayoEficacia();

	$id_solicitud = $_POST['id_solicitud'];

	$respuesta=$ce->obtenerFuncionarioXarea($conexion,'CGRIA');
	if(sizeof($respuesta)>0){
		$firmante=$respuesta[0]['apellido'].' '.$respuesta[0]['nombre'];
		$firmanteCargo=$respuesta[0]['cargo'];
	}


	$mensaje=$ccert->generarCertificado($conexion,$id_solicitud,$firmante,$firmanteCargo);

	$conexion->desconectar();

	echo json_encode($mensaje);

?>


