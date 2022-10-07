<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once 'clases/GeneradorProtocolo.php';

	$conexion = new Conexion();
	$cProtocolo=new GeneradorProtocolo();
	$ce=new ControladorEnsayoEficacia();

	$idProtocolo = $_POST['id_protocolo'];

	$esDocumentoLegal=$_POST['esDocumentoLegal'];
	
	$tituloPrevio=$ce->generarTituloDelEnsayo($conexion, $idProtocolo);
	$mensaje=$cProtocolo->generarProtocolo($conexion,$idProtocolo,$tituloPrevio,$esDocumentoLegal);


	$conexion->desconectar();

	echo json_encode($mensaje);

?>