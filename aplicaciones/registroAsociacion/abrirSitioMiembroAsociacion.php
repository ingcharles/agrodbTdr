<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();


$data =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
list($identificadorMiembroAsociacion, $nombreMiembroAsociacion, $nombreSitio, $nombreArea, $superficie, $nombreAsociacion) = explode("@", $data);

?>

<header>
<h1>Sitio por miembro de asociación</h1>
</header>

<fieldset>	
	<legend>Información general del sitio <?php echo $nombreSitio?></legend>
	<div data-linea="1">
		<label>Identificación: </label> <?php echo $identificadorMiembroAsociacion?>
	</div>
	<div data-linea="2">
		<label>Nombre completo: </label> <?php echo $nombreMiembroAsociacion?>
	</div>
	<div data-linea="3">
		<label>Nombre asociación: </label> <?php echo $nombreAsociacion?>
	</div>
	<div data-linea="4">
		<label>Nombre de sitio: </label> <?php echo $nombreSitio?>
	</div>
	<div data-linea="5">
		<label>Nombre de área: </label> <?php echo $nombreArea?>
	</div>
	<div data-linea="6">
		<label>Superficie: </label> <?php echo $superficie?> m2
	</div>			
</fieldset>


<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();	
	});
</script>