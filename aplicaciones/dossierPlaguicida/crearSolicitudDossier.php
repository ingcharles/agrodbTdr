<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once 'clases/GeneradorDocumentoPlaguicida.php';

	$conexion = new Conexion();
	$cGenerador=new GeneradorDocumentoPlaguicida();

	$id_solicitud = $_POST['id_solicitud'];
	$mensaje=$cGenerador->generarDossier($conexion,$id_solicitud);

	echo json_encode($mensaje);

?>


