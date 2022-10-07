<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorFormularios.php';
	
	$conexion = new Conexion();
	$cf = new ControladorFormularios();
	$ca = new ControladorAplicaciones('formularios','abrirFormulario');
?>
	<header>
		<h1>Formularios</h1>
		<?php echo $ca->imprimirMenuDeAcciones($conexion, $_POST["opcion"], $_SESSION['usuario']);?>
	</header>
	<?php 
		$res = $cf->listarFormularios($conexion);	
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			echo $ca->imprimirArticulo($fila['id_formulario'],++$contador,$fila['nombre'],'');
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