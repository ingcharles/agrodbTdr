<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();

$usuario = $_SESSION['usuario'];

$contador = 0;
$itemsFiltrados[] = array();
$qMiembrosAsociacion = $cr->obtenerRendimientoXIdentificacionNombre($conexion, $_POST['identificadorMiembro'], $_POST['nombreMiembroAsociacion'], $_POST['nombreSitio'], $usuario);//consultaCatastroIndividual($conexion, $_POST['identificadorSolicitanteH'],$_POST['nombreOperadorSolicitante'],$_POST['nombreSitio'],$_POST['provincia'],$_POST['fechaInicio'],$_POST['fechaFin'],$_SESSION['usuario']);

while($fila = pg_fetch_assoc($qMiembrosAsociacion)){
	
	switch ($fila['estado']){
		case 'cargarRendimiento':
			$estilo = 'notificacionFilaCargarRendimiento';
		break;
		case 'subsanacion':
		    $estilo = 'notificacionFilaSubsanacion';
		break;
		default:
			$estilo = '';
	}	
	
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_sitio'].'-'.$fila['id_area'].'"
		class="item '.$estilo.'"
		data-rutaAplicacion="registroOperador"
		data-opcion="nuevoRendimientoAsociacion"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td style="white-space:nowrap; text-align:center;">'.++$contador.'</td>
		<td>'.$fila['identificador_miembro_asociacion'].'</td>
		<td>'.$fila['nombre'].'</td>
		<td>'.$fila['nombre_lugar'].'</td>
		<td>'.$fila['nombre_area'].'</td>
		<td>'.$fila['rendimiento'].'</td>
		</tr>');	
}

?>

<header>
	<h1>Lista de asociaciones</h1>
	<nav>
		<form id="filtrarMiembroAsociacion" data-rutaAplicacion="registroOperador" data-opcion="listaRendimientoAsociacion" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			
		<table class="filtro">
			<tbody>
			<tr>
			<th colspan="3">Buscar Asociaciones</th>
			</tr>
			<tr>
			<th>Identificación miembro:</th>
			<td> <input id="identificadorMiembro" type="text" name="identificadorMiembro" maxlength="256"> </td>
			</tr>
			<tr>
			<th>Nombre completo:</th>
			<td> <input id="nombreMiembroAsociacion" type="text" name="nombreMiembroAsociacion" maxlength="128"> </td>
			</tr>
			<tr>
			<th>Nombre de sitio:</th>
			<td> <input id="nombreSitio" type="text" name="nombreSitio" maxlength="128"> </td>
			</tr>
			<tr>
			<td colspan="5"> <button id="buscar">Buscar</button> </td>
			</tr>
			</tbody>
			</table>
			
		</form>
	</nav>
	<div id="mensajeError">
			</div>
</header>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificación</th>	
			<th>Operación</th>
			<th>Sitio</th>
			<th>Área</th>
			<th>Rendimiento</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>

	//if($("#estado").html()=="Elemento borrado" || $("#estado").html()=="Los datos se han guardado correctamente" || $("#estado").html()=="Los datos del miembro de asociaci�n se han eliminado"){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>'); 
	//}
	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	
	});

	$("#_eliminar").click(function(event){
		$("#mensajeError").html("");
		if($("#cantidadItemsSeleccionados").text()>1){	
			$("#mensajeError").html("Por favor seleccione un registro a la vez.").addClass('alerta');
				return false;
			}
		if($("#cantidadItemsSeleccionados").text()==0){
			$("#mensajeError").html("Por favor seleccione un registro a eliminar.").addClass('alerta');
			return false;
		}
	});

	$("#filtrarMiembroAsociacion").submit(function(event){
		abrir($(this),event,false);
	});

</script>

