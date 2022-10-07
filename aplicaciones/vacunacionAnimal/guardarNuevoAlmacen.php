<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

print_r($_POST);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php
$datos = array(
		'id_provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
	    'provincia' => htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8'), 
	    'id_canton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
	    'canton' => htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8'),
	    'nombre_almacen' => htmlspecialchars ($_POST['nombreAlmacen'],ENT_NOQUOTES,'UTF-8'),
		'lugar_almacen' => htmlspecialchars ($_POST['lugarAlmacen'],ENT_NOQUOTES,'UTF-8'),
		'estado' => 'activo');
			
$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

//Guardar datos del almacen
$dAlmacen = $vdr->guardarDatosAlmacen($conexion, $datos['id_provincia'], $datos['provincia'], $datos['id_canton'], $datos['canton'], $datos['nombre_almacen'], $datos['lugar_almacen'], $datos['estado']);
$idAlmacen = pg_fetch_result($dAlmacen, 0, 'identificador');

$conexion->desconectar();			

?>
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>






