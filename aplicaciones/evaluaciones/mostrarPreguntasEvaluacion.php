<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluaciones.php';

$conexion = new Conexion();
$ce = new ControladorEvaluaciones();

//Cargar un arreglo de preguntas y otro de opciones

$preguntas = $ce->obtenerPreguntas($conexion, $_POST['id']); 
$opciones = $ce->obtenerOpciones($conexion, $_POST['id']);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<link rel="stylesheet" href="aplicaciones/general/estilos/jquery.countdown.css">
	<link rel='stylesheet' href='aplicaciones/general/estilos/jquery-ui-1.10.2.custom.css'>
	
	<script src="aplicaciones/general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="aplicaciones/general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
	
   <script type="text/javascript" src="aplicaciones/general/funciones/jquery.countdown.js"></script>
   <script type="text/javascript" src="aplicaciones/general/funciones/jquery.countdown-es.js" charset="utf-8"></script>

</head>
<body>
	<header>
		<h1>Evaluación: <?php echo pg_fetch_result($preguntas,0,'nombre'); //Imprimir el nombre de la evaluacion ?></h1>
	</header>
	<section class="evaluacion">
		<div class="recuadro">
			<p><b>Objetivo:</b> <?php echo pg_fetch_result($preguntas,0,'objetivo'); //Imprimir el objetivo de la evaluacion ?></p>
			<p><b>Instrucciones:</b> Lea detenidamente cada pregunta y escoga solamente un opción. Todas las preguntas deben ser contestadas.</p>
			<p class="importante">¡Tenga en cuenta que solo dispone de 30 minutos a partir de pulsar el boton Iniciar evaluación!</p>
		</div>
		
		<p><button id="inicio" type="button">Iniciar evaluación</button></p>
		
		
	</section>

</body>
	
		
	<script type="text/javascript">

	$('#contador').countdown({until: 0, onTick: highlightLast5, format: 'MS' }); 
	
	$(document).ready(function(){
		$("#evaluacion").hide();
		$('#contador').removeClass('alerta').countdown('option', {until: +10});
		$('#contador').countdown('pause');
		
	});

	$("#inicio").click(function(){
		$("#evaluacion").fadeIn();
		$("#inicio").fadeOut();
		$('#contador').countdown('resume');
	});

	     
	function highlightLast5(periods) { 
	    if ($.countdown.periodsToSeconds(periods) == 5) { 
	        $(this).addClass('alerta'); 
	    }
	} 
	 
	

		//funcion para dar foco al primer radio cuando se selecciona una pregunta
		//usar el tabindex que se coloco en el div.pregunta
		$(".pregunta").on("focus",function(){
			$(this).find("input").first().focus();
		});

		//////////// organización de las opciones en cada uno de los divs
		var opciones = <?php echo json_encode($listaOpciones);?>;
		//recorre todas las opciones, extrae el key del arreglo y lo coloca en el div con el mismo nombre
		for (item in opciones){
			var pregunta = item.split('.');
			$("#" + pregunta[0]).append(opciones[item]);
		}

		//////////// valida que todas las opciones estén llenas
		$("#evaluacion").submit(function(event){
			event.preventDefault();
			//extrae dos arreglos de las preguntas totales y contestadas
			var preguntas =  $("#" + this.id + " input:radio");
			var preguntasContestadas = $("#" + this.id + " input:radio:checked");
		    preguntas.parent().addClass('alerta'); //coloca alerta en todas
			alert(preguntas);
			preguntasContestadas.parent().removeClass('alerta'); // les quita alertas a preguntas que han sido respondidas
		    if($(".alerta").length > 0){
			    //en caso de que no se haya respondido al menos una pregunta
		        alert("Por favor conteste todas las preguntas. Todos los campos son obligatorios.");
		        return false;
		    } else {
			    abrir($(this),event,false);
			    //TODO : el event no debe enviarse, hay que cambiar la funcion abrir
		    }
		});
	</script>
</html>
