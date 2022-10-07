<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$contador = 0;
$itemsFiltrados[] = array();

$qMiembros = $cro->obtenerSitiosXMiembroAsociacion($conexion, $_POST['identificadorMiembro'], $_POST['nombreMiembroAsociacion']);//consultaCatastroIndividual($conexion, $_POST['identificadorSolicitanteH'],$_POST['nombreOperadorSolicitante'],$_POST['nombreSitio'],$_POST['provincia'],$_POST['fechaInicio'],$_POST['fechaFin'],$_SESSION['usuario']);

while($fila = pg_fetch_assoc($qMiembros)){
	$itemsFiltrados[] = array('<tr
		id="'.$fila['identificador_miembro_asociacion'].'@'.$fila['nombre_miembro_asociacion'].'@'
			.$fila['nombre_lugar'].'@'.$fila['nombre_area'].'@'.$fila['superficie_total'].'@'.$fila['nombre_operador'].'" 
			class="item"
			data-rutaAplicacion="registroAsociacion"
			data-opcion="abrirSitioMiembroAsociacion"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
		<td>'.++$contador.'</td>
		<td>'.$fila['identificador_miembro_asociacion'].'</td>
		<td>'.$fila['nombre_miembro_asociacion'].'</td>
		<td>'.$fila['nombre_lugar'].'</td>
		<td>'.$fila['nombre_operador'].'</td>
		</tr>');
}




?>

<header>
	<h1>Listado de sitios por miembro de asociación</h1>

	<nav>
		<form id="filtrarSitiosXMiembroAsociacion" data-rutaAplicacion="registroAsociacion" data-opcion="listaSitiosRegistrados" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			
		<table class="filtro">
			<tbody>
			<tr>
			<th colspan="4">Buscar miembros de asociación</th>
			</tr>
			<tr>
			<th>Identificación:</th>
			<td colspan="2"> <input id="identificadorMiembro" type="text" name="identificadorMiembro" maxlength="256"> </td>
			</tr>
			<tr>
			<th>Nombre completo:</th>
			<td> <input id="nombreMiembroAsociacion" type="text" name="nombreMiembroAsociacion" maxlength="128"> </td>
			</tr>
			<tr>
			<td id="mensajeError"></td>
			<td colspan="5"> <button id="buscar">Buscar</button> </td>
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
			<th>Identificación</th>	
			<th>Nombre completo</th>
			<th>Nombre de sitio</th>
			<th>Asociación</th>
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

	$("#fechaInicio").datepicker({
	    changeMonth: true,
	    changeYear: true,
	    onSelect: function(dateText, inst) {
   		 $('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val()); 
       } 
	});

	$("#fechaFin").datepicker({
	    changeMonth: true,
	    changeYear: true
	});

	$("#filtrarSitiosXMiembroAsociacion").submit(function(event){
		abrir($(this),event,false);
	});
	
</script>