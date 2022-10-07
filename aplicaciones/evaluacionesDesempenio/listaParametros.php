<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

?>
<header>
<h1>Parámetros</h1>
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
	$res = $ced->listaParametros($conexion,'ABIERTOS');
		
	$contador = 0;
	while($fila = pg_fetch_assoc($res)){
		echo '<article
						id="'.$fila['cod_parametro'].'"
						class="item"
						data-rutaAplicacion="evaluacionesDesempenio"
						data-opcion="abrirParametros"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.$fila['nombre_parametro'].'<br/></span>
					<span>Año: '.$fila['anio'].'<br/></span>
					<span>Periodo: '.$fila['periodo'].'<br/></span>
					<aside>Desde:'.date('j/n/Y',strtotime($fila['fecha_inicio'])).'<br/>Hasta: '.date('j/n/Y',strtotime($fila['fecha_fin'])).'</aside>
				</article>';
	}
	?>
	
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui para revisarlo.</div>');
	});
</script>