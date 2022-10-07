<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';

	require_once 'clases/GeneradorDocumentoPlaguicida.php';

	$conexion=new Conexion();

	$cu=new ControladorUsuarios();
	$cGenerador=new GeneradorDocumentoPlaguicida();
	$ce=new ControladorEnsayoEficacia();

	$id_solicitud = $_POST['id_solicitud'];
	$datos=$ce->obtenerFuncionarioXarea($conexion,'CGRIA');
	$datos=current($datos);

	$firmante=$datos['nombre'].' '.$datos['apellido'];
	$firmanteCargo=$datos['cargo'];

	$mensaje=$cGenerador->generarCertificado($conexion,$id_solicitud,$firmante,$firmanteCargo);

	$conexion->desconectar();

	echo json_encode($mensaje);

?>


