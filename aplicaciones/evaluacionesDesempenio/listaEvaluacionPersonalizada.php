<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorEvaluacionesDesempenio.php';
	
	$identificador = $_SESSION['usuario']
?>


<header>
	<h1>Evaluaciones</h1>
	<nav>
		<?php 
		$conexion = new Conexion();
		$car = new ControladorAreas ();
		$ced = new ControladorEvaluacionesDesempenio();

		?>
	</nav>
</header>
	
	<div>
		<h2>Evaluación a funcionarios de nivel inferior</h2>
	</div>
	
<?php 

$qListaAplicantes=$ced->listarAplicantesEvaluacionIndividual($conexion, $identificador,'activo');

while ( $aplicantes = pg_fetch_assoc ( $qListaAplicantes ) ) {
	
	$listaAplicantes = $car->listarAplicantesEvaluacionIndividual($conexion,$aplicantes['identificador_evaluado'],'activo');
	$fila = pg_fetch_assoc($listaAplicantes);
	if($fila['identificador_evaluado'] != '')
	echo '<article
					id="'.$fila['id_puesto'].'-'.$fila['id_aplicante_individual'].'-'.$fila['id_evaluacion'].'-'.$fila['identificador_evaluado'].'"
					class="item"
					data-rutaAplicacion="evaluacionesDesempenio"
					data-opcion="generarNuevaEvaluacionPersonalizada"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.$fila['nombres_completos'].'<br/></span>
					<aside></aside>
			</article>';
			
 }?>


			
<script type="text/javascript"> 

	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una evaluación para revisarlo.</div>');
	
</script>