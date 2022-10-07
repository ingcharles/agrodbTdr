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
		'id_vacuna_tipo_animal' => 1, // htmlspecialchars ($_POST['tipoIdentificacion'],ENT_NOQUOTES,'UTF-8'),
		'id_provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
		'provincia' => htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8'),
	    'id_canton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'), 
		'canton' => htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8'),
	    'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
	    'usuario_creacion' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
	    'estado' => 'activo');
			
$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

//Guardar datos de el control vacunador
$Control = $vdr-> busquedaControlAreteo($conexion, $datos['id_vacuna_tipo_animal'], $datos['id_provincia'], $datos['id_canton']); 
if(pg_num_rows($Control) == 0 ){
	$ControlAreteo = $vdr-> guardarControlAreteo($conexion, $datos['id_vacuna_tipo_animal'], $datos['id_provincia'], $datos['provincia'], $datos['id_canton'], $datos['canton'], $datos['observacion'], $datos['estado'], $datos['usuario_creacion']);
	$idControlAreteo = pg_fetch_result($ControlAreteo, 0, 'id_control_areteo');
}			
$conexion->desconectar();

?>
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>






