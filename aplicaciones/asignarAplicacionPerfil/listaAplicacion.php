<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';;
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cap = new ControladorAplicacionesPerfiles();

$res=$cap->listarAplicaciones($conexion);
$usuario=$_POST['identificacionUsuarioH'];
$contador = 0;
$itemsFiltrados[] = array();

while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
			id="'.$fila['id_aplicacion'].'"
			class="item"
			data-rutaAplicacion="asignarAplicacionPerfil"
			data-opcion="abrirAplicacion"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
			<td>'.$fila['id_aplicacion'].'</td>
			<td>'.$fila['nombre'].'</td>
			<td>'.$fila['estado_aplicacion'].'</td>
			<td>'.$estado.'</td>
			</tr>');
}

?>
<header>
	<h1>Parametros</h1>
	<nav>
		<?php			
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
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Id Aplicación</th>
			<th>Nombre</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

	$("#_eliminar").click(function(event){
		if($("#cantidadItemsSeleccionados").text()>1){	
			alert("Por favor seleccione solo un registro");
			return false;
		}
	});	
</script>