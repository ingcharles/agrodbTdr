<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluaciones.php';

$conexion = new Conexion();
$ce = new ControladorEvaluaciones();

//Cargar un arreglo de preguntas y otro de opciones

$encuesta = $ce->obtenerDatosEvaluacion($conexion, $_POST['id']); 
$usuario = $_SESSION['usuario'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	
	<script src="aplicaciones/general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="aplicaciones/general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>

</head>
<body>
	<header>
		<h1>Evaluación: <?php echo pg_fetch_result($encuesta,0,'nombre'); //Imprimir el nombre de la evaluacion ?></h1>
	</header>
	<section class="evaluacion">
		<form id="evaluacion" data-rutaAplicacion="evaluaciones" data-opcion="abrirPreguntasEvaluacion" data-destino="detalleItem">
				
				<p><b>Objetivo:</b> <?php echo pg_fetch_result($encuesta,0,'objetivo'); //Imprimir el objetivo de la evaluacion ?></p>
				<p><b>Instrucciones:</b> Lea detenidamente cada pregunta y escoga solamente un opción. Todas las preguntas deben ser contestadas.</p>
			<div class="recuadro">
				<p class="importante">¡Tenga en cuenta que solo dispone de <?php echo pg_fetch_result($encuesta,0,'duracion_evaluacion'); //Imprimir el nombre de la evaluacion ?> minutos a partir de pulsar el boton Iniciar evaluación!</p>
			</div>
			
			<input type="hidden" name="evaluacion" value="<?php echo $_POST['id'];?>"/>
			<input type="hidden" name="usuario" value="<?php echo $usuario;?>"/>
			
			<p><button type="submit">Iniciar evaluación</button></p>
			
		</form>
		
	</section>

</body>
	
	<script type="text/javascript">

		$("#evaluacion").submit(function(event){
			event.preventDefault();
			abrir($(this),event,false);
		});
	</script>
</html>
