<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEncuestas.php';

$conexion = new Conexion();
$ce = new ControladorEncuestas();

//Cargar un arreglo de preguntas y otro de opciones
$preguntas = $ce->obtenerPreguntas($conexion, $_POST['id']); 
$opciones = $ce->obtenerOpciones($conexion, $_POST['id']);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Encuesta: <?php echo pg_fetch_result($preguntas,0,'nombre'); //Imprimir el nombre de la encuesta ?></h1>
	</header>
	<section class="encuesta">
		<div class="recuadro">
			<p><b>Objetivo:</b> <?php echo pg_fetch_result($preguntas,0,'objetivo'); //Imprimir el objetivo de la encuesta ?></p>
			<p><b>Instrucciones:</b> Lea detenidamente cada pregunta y escoja solamente un opción. Todas las preguntas deben ser contestadas.</p>
			<p class="importante">¡Esta encuesta es anónima!</p>
		</div>
		<form id="encuesta" data-rutaAplicacion="encuestas" data-opcion="guardarEncuesta" data-destino="detalleItem">
			<input type="hidden" name="encuesta" value="<?php echo $_POST['id'];?>"/>
			<?php 
				$contador = 0;
				//bucle para imprimir las preguntas que tienen un div con el nombre
				while ($pregunta = pg_fetch_assoc($preguntas)){ 
					echo '<div  class="pregunta" tabindex="' . $contador . '" id="pre_' . $pregunta['id_pregunta'] . '"><p>'. ++$contador.'. ' . $pregunta['descripcion'] . '</p>'.($pregunta['ruta_archivo']!=''?'<a href="'.$pregunta['ruta_archivo'].'" download="documentoEncuesta.pdf">Documento</a><br/><br/>':'').($pregunta['ruta_imagen']!=''?'<img src="'.$pregunta['ruta_imagen'].'" /><br/>':'').'</div>';
				}
				//bucle para almacenar en memoria las opciones de todas las preguntas para que despues sean organizadas
				while ($opcion = pg_fetch_assoc($opciones)){
					$listaOpciones['pre_'. $opcion['id_pregunta'] . '.' . $opcion['id_opcion']] = '<input type="radio" name="resp_' . $opcion['id_pregunta'] . '" id="op_' . $opcion['id_opcion'] . '" value="' . $opcion['id_opcion'] . '"><label for="op_' . $opcion['id_opcion'] . '">' . $opcion['opcion'] . '</label><br/>';
				}
				//var_dump( $listaOpciones);
			?>
			<div><p>Fin de la encuesta, por favor revise que todas las preguntas hayan sido contestadas.</p></div>
			<p><button type="submit">Enviar encuesta</button></p>
		</form>
	</section>

</body>
				<script type="text/javascript">

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
					$("#encuesta").submit(function(event){
						event.preventDefault();
						//extrae dos arreglos de las preguntas totales y contestadas
						var preguntas =  $("#" + this.id + " input:radio");
						var preguntasContestadas = $("#" + this.id + " input:radio:checked");
					    preguntas.parent().addClass('alerta'); //coloca alerta en todas
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

