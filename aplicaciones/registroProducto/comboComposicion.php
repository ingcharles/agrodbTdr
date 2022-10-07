<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRequisitos.php';

$conexion = new Conexion();
$cr = new ControladorRequisitos();

$idProductoInocuidad = htmlspecialchars ($_POST['idProductoInocuidad'],ENT_NOQUOTES,'UTF-8');

$productoInocuidad = pg_fetch_assoc($cr->buscarProductoInocuidad($conexion,$idProductoInocuidad));


echo'<div data-linea="1">
<label>Composición: </label> '.$productoInocuidad['composicion'].'
</div>';

$conexion->desconectar();

?>

