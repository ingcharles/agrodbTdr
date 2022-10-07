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
		'id_administrador_vacunacion' => htmlspecialchars ($_POST['cmbOperadorVacunacion'],ENT_NOQUOTES,'UTF-8')
		,'identificador_distribuidor' => htmlspecialchars ($_POST['ptoDistribucion'],ENT_NOQUOTES,'UTF-8')
		,'estado' => 'activo');
			
$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

//Guardar datos del administrador vacunación
$dAdministradorVacunacionAnimal = $vdr->guardarAdministradorDistribuidor($conexion, $datos['id_administrador_vacunacion'], $datos['identificador_distribuidor'], $datos['estado']);
$conexion->desconectar();			


echo 'validar';

?>
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>






