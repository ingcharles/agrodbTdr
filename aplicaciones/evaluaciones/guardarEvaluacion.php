<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluaciones.php';
require_once '../../clases/ControladorUsuarios.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<div id="impresion" >
	<?php
	$conexion = new Conexion();
	$ce = new ControladorEvaluaciones();
		//$sentencia = "INSERT INTO g_encuesta.respuestas (id_pregunta,id_opcion,area) VALUES ";
		foreach ($_POST as $key => $value){
			$idPregunta = explode("resp_",htmlspecialchars($key));
			$idOpcion = htmlspecialchars($value);
			if($idPregunta[0] != 'evaluacion' && $idPregunta[0] != 'usuario' && $idPregunta[0] != 'numeroResultadoEvaluacion')
				$ce->grabarRespuesta($conexion, $idPregunta[1], $idOpcion, $_POST['usuario'], $_POST['evaluacion']);
		}
			
		$ce->quitarEvaluacion($conexion,$_POST['usuario'],$_POST['evaluacion']);
		
		$q_impresion = $ce->datosImpresion($conexion,$_POST['usuario'],$_POST['evaluacion']);
		$impresion = pg_fetch_assoc($q_impresion);
 
		$q_calificacion = $ce->obtenerCalificacion($conexion,$_POST['evaluacion'], $_POST['usuario'], $_POST['numeroResultadoEvaluacion']);
		$calificacion = pg_fetch_assoc($q_calificacion);
		$fechaInicio = date('j/n/Y G:i',strtotime($impresion['fecha_inicio']));
		$fechaFin = date('j/n/Y G:i',strtotime($impresion['fecha_fin']));
		
		
		$calif = ($calificacion['calificacion'] * 20 )/ $calificacion['num_preguntas'];
		
		$ce->actualizarResultadoEvaluacion($conexion, $_POST['usuario'], $_POST['numeroResultadoEvaluacion'], $calif);
		
			
		echo"<img src='aplicaciones/general/img/membrete.png'>
		     <h2 style='text-align:center'>".$impresion['evaluacion']."</h2>
			 <div id = 'contenedor' style='text-align:center'>
			 	<div><p>La evaluación ha sido enviada satisfactoriamente.</p></div>
			 	<div><b>Nombre: </b>" .$impresion['apellido'].' ' .$impresion['nombre']  ."</div> 
			 	<div><b>Fecha de inicio: </b>" . $fechaInicio."</div>
		     	<div><b>Fecha de finalización: </b>" . $fechaFin."</div>";
		
		if($impresion['imprimir'] == 1){
		    echo "<div id='nota'><p>Su calificación es: <b>" . number_format($calif,2) . " / 20 </b></p></div>";
		}
		echo "</div>";

	?>
	
	<p style="text-align:center"><strong>¡GRACIAS POR PARTICIPAR!</strong></p>
	
	</div>
	
	<?php  if($impresion['imprimir']== 1){
	    echo '<button id="imprimir" type="submit" class="imprimir">Imprimir</button>';
	} ?>
	
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);

		$("#imprimir").click(function(){
			//window.print();
			w=window.open();
			w.document.write($('#impresion').html());
			w.print();
			w.close();
		});
		
	</script>
</html>
