<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorInventarios.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones('inscripciones','abrirInscripcion');
	$ci = new ControladorInventario();
?>
	<header>
		<h1>Equipos informaticos</h1>
		<?php echo $ca->imprimirMenuDeAcciones($conexion, $_POST["opcion"], $_SESSION['usuario']);?>
	</header>
	<?php 
		$equiposInformaticos = $ci->listarEquiposAsignados($conexion);	
		$contador = 0;
		while($equipoInformatico = pg_fetch_assoc($equiposInformaticos)){
			echo $ca->imprimirArticulo($equipoInformatico['id_asignacion'],++$contador,$equipoInformatico['identificador'] .' - '. $equipoInformatico['id_area'] .'<br/>'.date('j/n/Y (G:i)',strtotime($equipoInformatico['fecha_asignacion'])),$equipoInformatico['estado'],'','',null,'abrirInscripcion_'.$equipoInformatico['tipo_evento']);
		}
	?>	
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
	});

</script>