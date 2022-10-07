<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$conexion = new Conexion();
$cap = new ControladorAplicacionesPerfiles();

$nombre = htmlspecialchars ($_POST['nombreAplicacion'],ENT_NOQUOTES,'UTF-8');
$version = htmlspecialchars ($_POST['versionAplicacion'],ENT_NOQUOTES,'UTF-8');
$ruta = htmlspecialchars ($_POST['rutaAplicacion'],ENT_NOQUOTES,'UTF-8');
$color = htmlspecialchars ($_POST['colorAplicacion'],ENT_NOQUOTES,'UTF-8');
$codificacion = htmlspecialchars ($_POST['codificacionAplicacion'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estadoAplicacion'],ENT_NOQUOTES,'UTF-8');
$descripcion = htmlspecialchars ($_POST['descripcionAplicacion'],ENT_NOQUOTES,'UTF-8');

$conexion->ejecutarConsulta("begin;");
$idAplicacion=pg_fetch_row($cap->guardarNuevoAplicacion($conexion, $nombre, $version, $ruta, $descripcion, $color, $codificacion, $estado));
$conexion->ejecutarConsulta("commit;");
$conexion->ejecutarConsulta("rollback;");

echo '<input type="hidden" id="' . $idAplicacion[0] . '" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirAplicacion" data-destino="detalleItem"/>'

?>
<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>