<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$identificadorOPerador = $_SESSION['usuario'];

$contador = 0;
$itemsFiltrados[] = array();
$qAsociaciones = $cro->obtenerAsociacionXNombreCorreoFecha($conexion, $_POST['nombre'], $_POST['mail'], $_POST['fecha']);

while($asociaciones = pg_fetch_assoc($qAsociaciones)){
	$itemsFiltrados[] = array('<tr id="'.$asociaciones['identificador'].'"
			class="item"
			data-rutaAplicacion="registroAsociacion"
			data-opcion="abrirAsociacion"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<td>'.++$contador.'</td>
			<td>'.$asociaciones['razon_social'].'</td>
			<td>'.$asociaciones['correo'].'</td>
			<td>'.$asociaciones['fecha_registro'].'</td>
			</tr>');
}

?>

<header>
	<h1>Lista de asociaciones</h1>
	<nav>
		<?php			
		$contador = 0;
		$itemsFiltrados[] = array();
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
	<nav>
		<form id="filtrar" data-rutaAplicacion="registroAsociacion"	data-opcion="listaAsociacion" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />

			<table class="filtro" style="width: 400px;">
				<tbody>
					<tr>
						<th colspan="3">Buscar Asociaciones:</th>
					</tr>
					<tr>
						<td>Nombre de la asociación:</td>
						<td><input id="nombre" type="text"
							name="nombre" maxlength="256">
						</td>
					</tr>
					<tr>
						<td>Correo electrónico:</td>
						<td><input id="mail" type="text" name="mail" maxlength="128">
						</td>
					</tr>
					<tr>
						<td>Fecha de registro:</td>
						<td><input id="fecha" type="text" name="fecha" maxlength="128">
						</td>
					</tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="5">
							<button id="buscar">Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</nav>
</header>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Nombre asociación</th>
			<th>Correo electrónico</th>
			<th>Fecha registro</th>
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
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	});

	$("#fecha").datepicker({
	    changeMonth: true,
	    changeYear: true
	}).datepicker('setDate', '');
	
	$("#filtrar").submit(function(event){
		event.preventDefault();
		abrir($('#filtrar'),event, false);
	});

</script>