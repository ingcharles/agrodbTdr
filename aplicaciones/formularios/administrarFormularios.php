<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	//$cf = new ControladorFormularios();
	$ca = new ControladorAplicaciones('formularios','abrirOperacion');
    $cc = new ControladorCatalogos();
?>
	<header>
		<h1>Operaciones disponibles</h1>
		<?php echo $ca->imprimirMenuDeAcciones($conexion, $_POST["opcion"], $_SESSION['usuario']);?>
	</header>
	<?php 
		$res = $cc->listarOperaciones($conexion,'');
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			echo $ca->imprimirArticulo($fila['id_tipo_operacion'],++$contador,$fila['nombre'],$fila['id_area'] . ' - ' .$fila['codigo']);
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