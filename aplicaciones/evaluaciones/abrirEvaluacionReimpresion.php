<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluaciones.php';
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
		
		$q_impresion = $ce->datosImpresion($conexion,$_SESSION['usuario'],$_POST['id']);
		$impresion = pg_fetch_assoc($q_impresion);
		
		$qNumeroOportunidad = $ce->buscarOportunidadActual($conexion, $_SESSION['usuario'], $_POST['id']);
 
		$q_calificacion = $ce->obtenerCalificacion($conexion,$_POST['id'], $_SESSION['usuario'], pg_fetch_result($qNumeroOportunidad, 0, 'id_resultado_evaluacion'));
		$calificacion = pg_fetch_assoc($q_calificacion);
		$fechaInicio = date('j/n/Y G:i',strtotime($impresion['fecha_inicio']));
		$fechaFin = date('j/n/Y G:i',strtotime($impresion['fecha_fin']));
		
		
		$calif = ($calificacion['calificacion'] * 20 )/ $calificacion['num_preguntas'];		
		
	   
	    
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

		$("#imprimir").click(function(){
			//window.print();	
			w=window.open();
			w.document.write($('#impresion').html());
			w.print();
			w.close();
		});

		
		
	</script>
</html>
