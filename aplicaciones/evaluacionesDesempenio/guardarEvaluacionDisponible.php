<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<?php
	$conexion = new Conexion();
	$ced = new ControladorEvaluacionesDesempenio();
	
	$qAplicante = $ced->abrirAplicante($conexion, $_POST['idAplicante']);
	$aplicante = pg_fetch_assoc($qAplicante);
	
		$sentencia = "INSERT INTO g_evaluacion_desempenio.respuestas (id_tipo_evaluacion,identificador_evaluador,identificador_evaluado,id_pregunta,id_opcion,id_aplicante, id_evaluacion) VALUES ";
		foreach ($_POST as $key => $value){
			$idPregunta = explode("resp_",htmlspecialchars($key));
			$idOpcion = htmlspecialchars($value);
			if($idPregunta[0] != 'idAplicante')
				if($idPregunta[0] != 'idEvaluacion')
				$sentencia.= "(".$aplicante['id_tipo_evaluacion'].",'".$aplicante['identificador_evaluador']."','".$aplicante['identificador_evaluado']."',".$idPregunta[1].",".$idOpcion.",".$aplicante['id_aplicante'].",".$_POST['idEvaluacion']."),";
		}

	//----------------------------------------------------------------------------------------------------------------------------------	
	 	$ced->grabarRespuestas($conexion,$sentencia);
		$ced->quitarEvaluacionDisponible($conexion, $aplicante['id_aplicante'], $aplicante['id_tipo_evaluacion']);
	//----------------------------------------------------------------------------------------------------------------------------------
		if(pg_num_rows($ced->abrirEvaluacionDisponibleUsuario($conexion, $aplicante['identificador_evaluador'])) == 0){

			echo  $aplicante['identificador_evaluador'];
			$ced->actualizarNotificacion($conexion, $aplicante['identificador_evaluador'],-1);
		}
		
	?>
	
	<p>La evaluación ha sido enviada satisfactoriamente.</p>
	<p>¡GRACIAS POR PARTICIPAR!</p>
	
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>
