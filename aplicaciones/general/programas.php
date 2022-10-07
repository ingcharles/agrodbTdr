<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
?>

	<header>
		<h1>Aplicaciones registradas</h1>
	</header>
	<?php 
	$conexion = new Conexion();
	$ca = new ControladorAplicaciones();
	$res = $ca->obtenerAplicacionesRegistradas($conexion, $_SESSION['usuario']);
	while($fila = pg_fetch_assoc($res)){
		echo '<article style="background-color:'.$fila['color'].';"
			ondragstart="drag(event)"
			draggable="true"
			class="item'. (($fila['cantidad_notificacion']>0)?' pendiente':'') .'"
			id="' . $fila['id_aplicacion'] . '"
			data-rutaAplicacion="' . $fila['ruta'] .'"
				data-opcion="index"
				data-nombreAplicacion="' .  $fila['nombre'] . '"
					data-destino="ventanaAplicacion">';
		echo '	<div></div>';
		echo '	<span>' . $fila['nombre'] . '</span>';
		echo '	<aside>'. $fila['cantidad_notificacion'] .' '. $fila['mensaje_notificacion'] .'</aside>';
		echo '</article>';
	}
	?>


	<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("programas");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí la aplicacion para abrirla.</div>');
	});
	</script>