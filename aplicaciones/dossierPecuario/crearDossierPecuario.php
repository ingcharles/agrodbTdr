<?php
session_start();

	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorUsuarios.php';
		
	require_once 'clases/GeneradorDocumentoPecuario.php';

	$conexion = new Conexion();
	$cu=new ControladorUsuarios();
	$cd=new GeneradorDocumentoPecuario();
	$id_solicitud = $_POST['id_solicitud'];
	$esDocumentoLegal=$_POST['esDocumentoLegal'];
	$esBorrador=true;
	if($DocumentoLegal==='SI')
		$esBorrador=false;


	$mensaje=$cd->generarDossier($conexion,$id_solicitud,$esBorrador);


	echo json_encode($mensaje);

?>


