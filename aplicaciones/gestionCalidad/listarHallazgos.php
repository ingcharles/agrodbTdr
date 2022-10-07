<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorGestionCalidad.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones('gestionCalidad','abrirHallazgo');
	$cgc = new ControladorGestionCalidad();
?>
	<header>
		<h1>Hallazgos</h1>
		<?php echo $ca->imprimirMenuDeAcciones($conexion, $_POST["opcion"], $_SESSION['usuario']);?>
	</header>
	<?php 
		$hallazgos = $cgc->listarHallazgos($conexion);	
		$contador = 0;
		while($hallazgo = pg_fetch_assoc($hallazgos)){
			echo $ca->imprimirArticulo($hallazgo['id_hallazgo'],++$contador,$hallazgo['nombre'] . ' <br/><strong> ' . $hallazgo['tipo'] . '</strong>',$hallazgo['estado']);
		}
	?>	
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		/*$("#enviado div> article").length == 0 ? $("#enviado").remove():"";
		$("#reenviado div> article").length == 0 ? $("#reenviado").remove():"";
		$("#revisionResponsable div> article").length == 0 ? $("#revisionResponsable").remove():"";*/
	});
</script>