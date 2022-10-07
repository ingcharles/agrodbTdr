<?php
session_start();

ob_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';

	require_once 'clases/GeneradorDocumentoPlaguicida.php';

	
	$mensaje=array();
	$mensaje['mensaje'] = 'Error generando documento';
	$mensaje['estado'] = 'NO';

	$conexion=new Conexion();
	$cGenerador=new GeneradorDocumentoPlaguicida();
	$mensaje=$cGenerador->generarSolicitudRegistro($conexion,$_POST['esDocumentoLegal'],$_POST['id_solicitud']);

	$conexion->desconectar();

	echo json_encode($mensaje);

?>


