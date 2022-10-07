<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorInscripciones.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones('inscripciones','abrirInscripcion');
	$ci = new ControladorInscripciones();
?>
	<header>
		<h1>Inscripciones disponibles</h1>
		<?php echo $ca->imprimirMenuDeAcciones($conexion, $_POST["opcion"], $_SESSION['usuario']);?>
	</header>
	<?php 
		$inscripciones = $ci->listarEventosDisponibles($conexion, $_SESSION['usuario']);	
		$contador = 0;
		while($inscripcion = pg_fetch_assoc($inscripciones)){
			echo $ca->imprimirArticulo($inscripcion['id_inscripcion'],++$contador,$inscripcion['nombre'] ,$inscripcion['fecha_inicio'],'','',null,'abrirInscripcion_'.$inscripcion['tipo_evento']);
		}
	?>	
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un evento para revisarlo.</div>');
	});

</script>