<?php
session_start();

require_once '../../clases/Conexion.php';
require_once 'clases/GeneradorProtocolo.php';

$conexion = new Conexion();
$cProtocolo=new GeneradorProtocolo();


$idInforme = $_POST['id_documento'];

$esDocumentoLegal=$_POST['esDocumentoLegal'];

$mensaje=$cProtocolo->generarInforme($conexion,$idInforme,$esDocumentoLegal);

$conexion->desconectar();

echo json_encode($mensaje);

?>


