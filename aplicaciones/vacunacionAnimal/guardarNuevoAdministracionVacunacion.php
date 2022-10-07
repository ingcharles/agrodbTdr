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
		'id_especie' => htmlspecialchars ($_POST['especie'],ENT_NOQUOTES,'UTF-8')
		,'nombre_especie' => htmlspecialchars ($_POST['nombreEspecie'],ENT_NOQUOTES,'UTF-8')
		,'identificador_administrador' => htmlspecialchars ($_POST['administradorOperador'],ENT_NOQUOTES,'UTF-8')	
		,'estado' => 'activo');
			
$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

//Guardar datos del almacen
$dAdministradorVacunacionAnimal = $vdr->guardarAdministradorVacunacionAnimal($conexion, $datos['id_especie'], $datos['nombre_especie'], $datos['identificador_administrador'], $datos['estado']);
$conexion->desconectar();			

?>
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>






