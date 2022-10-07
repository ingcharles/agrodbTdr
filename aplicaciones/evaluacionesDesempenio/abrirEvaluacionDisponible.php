<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();

//Cargar un arreglo de preguntas y otro de opciones
$separar=explode('-', $_POST['id']);


$qAplicante = $ced->abrirAplicante($conexion, $separar[0]);
$aplicante = pg_fetch_assoc($qAplicante);

$ced->actualizarAplicante($conexion,$aplicante['id_aplicante'],$aplicante['id_tipo_evaluacion']);

$tipoPreguntas = $ced ->obtenerTiposPreguntasEvaluacion($conexion, $aplicante['id_tipo_evaluacion']); 

$preguntas = $ced->obtenerPreguntas($conexion, $aplicante['id_tipo_evaluacion']); 
$opciones = $ced->obtenerOpciones($conexion, $aplicante['id_tipo_evaluacion']);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Encuesta: <?php echo pg_fetch_result($preguntas,0,'nombre'); //Imprimir el nombre de la evaluacion ?></h1>
	</header>
	<section class="evaluacion">
		<div class="recuadro">
			<p><b>Objetivo:</b> <?php echo pg_fetch_result($preguntas,0,'objetivo'); //Imprimir el objetivo de la encuesta ?></p>
			<p><b>Instrucciones:</b> Lea detenidamente cada pregunta y escoga solamente un opción. Todas las preguntas deben ser contestadas.</p>
			<!-- p class="importante">¡Esta encuesta es anónima!</p-->
		</div>
		<form id="evaluacion" data-rutaAplicacion="evaluacionesDesempenio" data-opcion="guardarEvaluacionDisponible" data-destino="detalleItem">
			<input type="hidden" name="idAplicante" value="<?php echo $aplicante['id_aplicante'];?>"/>
			<input type="hidden" name="idEvaluacion" value="<?php echo $separar[1];?>"/>
			
			
			
			<!-- /////////////////////////////////////////////////////////// -->
			
			<?php 
			
			function quitar_tildes($cadena) {
				$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
				$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
				$texto = str_replace($no_permitidas, $permitidas ,$cadena);
				return $texto;
			}
			
			
			while ($tipoPregunta = pg_fetch_assoc($tipoPreguntas)){
				$tipo = quitar_tildes($tipoPregunta['tipo']);
				echo '<div id="'.strtolower(str_replace(' ', '',$tipo)).'">
						<h2>'.$tipoPregunta['tipo'].'</h2>
						<div class="elementos"></div>
					</div>';
				
			}
			?>
						
			<!-- /////////////////////////////////////////////////////////// -->
			
			
			
			<?php 
				$contador = 0;
				//bucle para imprimir las preguntas que tienen un div con el nombre
				while ($pregunta = pg_fetch_assoc($preguntas)){ 
						$tipo = quitar_tildes($pregunta['tipo_pregunta']);
						$categoria = strtolower(str_replace(' ', '', $tipo));	
						$contenido =  '<div  class="pregunta" tabindex="' . $contador . '" id="pre_' . $pregunta['id_pregunta'] . '"><p>'. ++$contador.'. ' . $pregunta['descripcion'] . '</p></div>';
			?>
					<script type="text/javascript">
						var contenido = <?php echo json_encode($contenido);?>;
						var categoria = <?php echo json_encode($categoria);?>;
						$("#"+categoria+" div.elementos").append(contenido);
					</script>
			<?php 
				}
				//bucle para almacenar en memoria las opciones de todas las preguntas para que despues sean organizadas
				while ($opcion = pg_fetch_assoc($opciones)){
					$listaOpciones['pre_'. $opcion['id_pregunta'] . '.' . $opcion['id_opcion']] = '<input type="radio" name="resp_' . $opcion['id_pregunta'] . '" id="op_' . $opcion['id_opcion'] . '" value="' . $opcion['id_opcion'] . '"><label for="op_' . $opcion['id_opcion'] . '">' . $opcion['opcion'] . '</label><br/><br/>';
		
				}
				//var_dump( $listaOpciones);
			?>
			<div><p>Fin de la evaluación, por favor revise que todas las preguntas hayan sido contestadas.</p></div>
			<p><button type="submit">Enviar evaluación</button></p>
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
					$("#evaluacion").submit(function(event){
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
