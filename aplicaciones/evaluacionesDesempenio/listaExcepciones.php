<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

?>
<header>
<h1>Excepciones</h1>
<nav>
<?php
$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
while($fila = pg_fetch_assoc($res)){
	echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
}
?>
		</nav>
	</header>
<?php 
	$ced = new ControladorEvaluacionesDesempenio();
   $res = $ced->listaEvaluaciones($conexion, 'EXCEPCION');
		
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['id_evaluacion'].'"
						class="item"
						data-rutaAplicacion="evaluacionesDesempenio"
						data-opcion="abrirEvaluacion"
						ondragstart="drag(event)"
						draggable="true"
						>
					<span class="ordinal">'.++$contador.'</span>
					<span>'.$fila['nombre'].'<br/></span>
					<span>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'<br/></span>
					<aside>'.strtoupper($fila['vigencia']).'</aside>
				</article>';
	}
	?>
	
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccionar aqui para revisarlo.</div>');
	});
</script>