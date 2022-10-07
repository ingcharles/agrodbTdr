<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluaciones.php';

$conexion = new Conexion();
$ce = new ControladorEvaluaciones();



$q_evaluacion  = $ce->obtenerDatosEvaluacion($conexion, $_POST['evaluacion']);
$q_estadoAplicante = $ce->estadoAplicante($conexion,$_POST['usuario'],$_POST['evaluacion']);
$estadoAplicante = pg_fetch_assoc($q_estadoAplicante);

function array_random($arr, $num) {
	$keys = array_keys($arr);
	shuffle($keys);
		
	$r = array();
		
	for ($i = 0; $i < $num; $i++) {
		$r[$keys[$i]] = $arr[$keys[$i]];
	}
	return $r;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

	<script src="aplicaciones/general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="aplicaciones/general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
	
   <script type="text/javascript" src="aplicaciones/general/funciones/jquery.countdown.js"></script>
   <script type="text/javascript" src="aplicaciones/general/funciones/jquery.countdown-es.js" charset="utf-8"></script>

</head>
<body>

	<header id="nombreEvaluacion">
		<h1>Evaluación: <?php echo pg_fetch_result($q_evaluacion,0,'nombre'); //Imprimir el nombre de la evaluacion ?></h1>
		
	</header>
		
	<section class="evaluacion">
	<?php 
		
		if($estadoAplicante['fecha_inicio'] == null && $estadoAplicante['estado'] == true ){
			
			$qNumeroOportunidad = $ce->buscarNumeroOportunidad($conexion, $_POST['usuario'], $_POST['evaluacion']);
			
			$qResultadoEvaluacion = $ce->guardarResultadoEvaluacion($conexion, $_POST['usuario'], pg_fetch_result($qNumeroOportunidad, 0, 'codigo'), $_POST['evaluacion']);
			
			
			echo'	<form id="evaluacion" data-rutaAplicacion="evaluaciones" data-opcion="guardarEvaluacion" data-destino="detalleItem">
						<input type="hidden" name="evaluacion" value="'. $_POST['evaluacion'].'"/>
						<input type="hidden" name="usuario" value="'. $_POST['usuario'].'"/>
						<input type="hidden" name="numeroResultadoEvaluacion" value="'. pg_fetch_result($qResultadoEvaluacion, 0, 'id_resultado_evaluacion').'"/>';
						
						$contador = 0;
						
						$tiempoRestante = pg_fetch_result($q_evaluacion,0,'duracion_evaluacion')*60;
	
						$ce->grabarHora($conexion, $_POST['usuario'], $_POST['evaluacion'], pg_fetch_result($q_evaluacion,0,'duracion_evaluacion'));
						
						$fechaInicio = date('Y-m-d') . "<br /> " . date('(G:i)');
						$fechaFin = date('Y-m-d (G:i)', strtotime('+'.pg_fetch_result($q_evaluacion,0,'duracion_evaluacion').' minutes'));
						
			echo'		<div id="tiempo" class="recuadro">
							<div class="hora-inicio"><b>Inicio:</b><br />'. $fechaInicio.'</div>
							<div id="contador" class="tiempo-restante"></div>
							<div class="hora-fin"><b>Fin:</b><br />'. $fechaFin.'</div>
						</div>';
			
						$preguntas = $ce->obtenerPreguntas($conexion, $_POST['evaluacion']);
						
						while($fila = pg_fetch_assoc($preguntas)){
							$aPreguntas[]= array(id=>$fila['id_pregunta'], descripcion=>$fila['descripcion'], rutaImagen=>$fila['ruta_imagen']);
						}
						
						$preguntasAleatorias = array_random($aPreguntas,pg_fetch_result($q_evaluacion,0,'cantidad_preguntas'));
					
						foreach ($preguntasAleatorias as $key => $pregunta){
							$ce->guardarPregunta($conexion, $_POST['evaluacion'],$pregunta['id'],$_POST['usuario'], pg_fetch_result($qResultadoEvaluacion, 0, 'id_resultado_evaluacion'));
							echo '<div  class="pregunta" tabindex="' . $contador . '" id="pre_' . $pregunta['id'] . '"><p>'. ++$contador.'. ' . $pregunta['descripcion'] . '</p>'.($pregunta['rutaImagen']!=''?'<img src="'.$pregunta['rutaImagen'].'" /><br/>':'').'</div>';
						}
					
						$opciones = $ce->obtenerOpciones($conexion, $_POST['evaluacion']);
					
						while ($opcion = pg_fetch_assoc($opciones)){
							$listaOpciones['pre_'. $opcion['id_pregunta'] . '.' . $opcion['id_opcion']] = '<input type="radio" name="resp_' . $opcion['id_pregunta'] . '" id="op_' . $opcion['id_opcion'] . '" value="' . $opcion['id_opcion'] . '"><label class="copcion" for="op_' . $opcion['id_opcion'] . '">' . $opcion['opcion'] . '</label>'.($opcion['ruta_imagen']!=''?'<img src="'.$opcion['ruta_imagen'].'" />':'').'<br/>';
						}
					
			echo'		<div><p>Fin de la evaluación, por favor revise que todas las preguntas hayan sido contestadas.</p></div>
						<p><button type="submit">Finalizar evaluación</button></p>
					</form>';

			}else if(date('j/n/Y G:i',strtotime($estadoAplicante['fecha_fin']))  >= date('j/n/Y G:i') && $estadoAplicante['estado'] == true) {
				
				$qNumeroOportunidad = $ce->buscarOportunidadActual($conexion, $_POST['usuario'], $_POST['evaluacion']);
			
				echo' <form id="evaluacion" data-rutaAplicacion="evaluaciones" data-opcion="guardarEvaluacion" data-destino="detalleItem">
						<input type="hidden" name="evaluacion" value="'. $_POST['evaluacion'].'"/>
						<input type="hidden" name="usuario" value="'. $_POST['usuario'].'"/>
						<input type="hidden" name="numeroResultadoEvaluacion" value="'. pg_fetch_result($qNumeroOportunidad, 0, 'id_resultado_evaluacion').'"/>';
						$contador = 0;
						
						$fechaActual = date('G:i:s');
						$fechaFin = date('G:i:s',strtotime($estadoAplicante['fecha_fin']));
						
						$tiempoRestante =  strtotime($fechaFin) - strtotime($fechaActual);
						
				echo'	<div id="tiempo" class="recuadro">				   
							<div class="hora-inicio"><b>Inicio:</b><br />'.date('Y-m-d',strtotime($estadoAplicante['fecha_inicio'])). "<br /> " . date('(G:i)',strtotime($estadoAplicante['fecha_inicio'])) . '</div>
							<div id="contador" class="tiempo-restante"></div>
							<div class="hora-fin"><b>Fin:</b><br />'.date('Y-m-d (G:i)',strtotime($estadoAplicante['fecha_fin'])).'</div>
						</div>';
	
						$preguntas = $ce-> preguntasAplicante($conexion, $_POST['usuario'], $_POST['evaluacion'],pg_fetch_result($qNumeroOportunidad, 0, 'id_resultado_evaluacion'));
							
						while($fila = pg_fetch_assoc($preguntas)){
							$preguntasAleatorias[]= array(id=>$fila['id_pregunta'], descripcion=>$fila['descripcion'], rutaImagen=>$fila['ruta_imagen']);
						}
						
						foreach ($preguntasAleatorias as $key => $pregunta){
							echo '<div  class="pregunta" tabindex="' . $contador . '" id="pre_' . $pregunta['id'] . '"><p>'. ++$contador.'. ' . $pregunta['descripcion'] . '</p>'.($pregunta['rutaImagen']!=''?'<img src="'.$pregunta['rutaImagen'].'" /><br/>':'').'</div>';
						}
						
						$opciones = $ce->obtenerOpciones($conexion, $_POST['evaluacion']);
						while ($opcion = pg_fetch_assoc($opciones)){
							$listaOpciones['pre_'. $opcion['id_pregunta'] . '.' . $opcion['id_opcion']] = '<input type="radio" name="resp_' . $opcion['id_pregunta'] . '" id="op_' . $opcion['id_opcion'] . '" value="' . $opcion['id_opcion'] . '"><label class="copcion" for="op_' . $opcion['id_opcion'] . '">' . $opcion['opcion'] . '</label>'.($opcion['ruta_imagen']!=''?'<img src="'.$opcion['ruta_imagen'].'" />':'').'<br/>';
						}
						
				echo' 	<div><p>Fin de la evaluación, por favor revise que todas las preguntas hayan sido contestadas.</p></div>
						<p><button type="submit">Finalizar evaluación</button></p>
					</form>';

			
			}else{
			
			echo'<div class="alerta"><p>Su tiempo ha finalizado.</p></div>';		
		
			}
			
			?>

	</section>

</body>
	
		
	<script type="text/javascript">

	$(document).ready(function(){
		$('#contador').countdown({until: 1, onTick: alerta, format: 'MS' });
		$('#contador').removeClass('alerta').countdown('option', {until: +<?php echo $tiempoRestante;?>});		
	});

	function alerta(periods) { 
	    if ($.countdown.periodsToSeconds(periods) <= 300) { 
	        $(this).addClass('alerta'); 
	    }if($.countdown.periodsToSeconds(periods) == 0){
	    	$('#evaluacion').submit();	    	
	     }
	} 
	
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
		//var preguntas =  $("#" + this.id + " input:radio");
		//var preguntasContestadas = $("#" + this.id + " input:radio:checked");
	    //preguntas.parent().addClass('alerta'); //coloca alerta en todas
		//preguntasContestadas.parent().removeClass('alerta'); // les quita alertas a preguntas que han sido respondidas
	    //if($(".alerta").length > 0){
		    //en caso de que no se haya respondido al menos una pregunta
	      // alert("Por favor conteste todas las preguntas. Todos los campos son obligatorios.");
	      //alert(event);
	        //return false;
	    //} else {
			abrir($(this),event,false);
		    //TODO : el event no debe enviarse, hay que cambiar la funcion abrir
		    //}
	});
	</script>
</html>
