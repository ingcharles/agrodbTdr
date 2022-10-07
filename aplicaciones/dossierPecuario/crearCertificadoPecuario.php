<?php
session_start();

	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';

	
	require_once 'clases/GeneradorDocumentoPecuario.php';
	

	$conexion=new Conexion();

	$cu=new ControladorUsuarios();
	
	$ccert=new GeneradorDocumentoPecuario();
	
	$ce=new ControladorEnsayoEficacia();

	$id_solicitud = $_POST['id_solicitud'];
	$datos=$ce->obtenerFuncionarioXarea($conexion,'CGRIA');
	$datosAprobador=pg_fetch_assoc( $cu->obtenerAreaUsuario($conexion,$datos['identificador']),0);
	$nombresModoAccion=pg_fetch_assoc($cu->obtenerNombresUsuario($conexion,$datos['identificador']),0);

	$firmante=$nombresModoAccion['nombre'].' '.$nombresModoAccion['apellido'];
	$firmanteCargo=$datosAprobador['nombre'];

	$mensaje=$ccert->generarCertificado($conexion,$id_solicitud,$firmante,$firmanteCargo);

	$conexion->desconectar();

	echo json_encode($mensaje);

?>


