<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';
$conexion = new Conexion();	
$cee = new ControladorEmpleadoEmpresa();
$identificadorUsuario=$_SESSION['usuario'];

$contador = 0;
$itemsFiltrados[] = array();
$res = $cee->listaEmpleadoEmpresa($conexion,$_POST['identificacionEmpleadoH'],$_POST['nombreEmpleadoH'],$_POST['apellidoEmpleadoH'],$identificadorUsuario);

while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
						id="'.$fila['id_empleado'].'"
						class="item"
						data-rutaAplicacion="empleadoEmpresa"
						data-opcion="abrirEmpleadoEmpresa"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
						<td>'.$fila['operador_vacunacion'].'</td>
						<td>'.$fila['empleado'].'</td>
						<td>'.$fila['estado'].'</td>
					</tr>');	
}

?>

	<header>
		<h1>Administrar Empleados</h1>
		<nav>
			<a id="_nuevo" data-rutaaplicacion="empleadoEmpresa" data-opcion="nuevoEmpleadoEmpresa" data-destino="detalleItem" href="#">Nuevo</a>
			<a id="_actualizar" data-rutaaplicacion="empleadoEmpresa" data-opcion="listaEmpleadoEmpresa" data-destino="listadoItems" href="#">Actualizar</a>
			<a  id="_seleccionar" data-rutaaplicacion="empleadoEmpresa" href="#"><?php echo '<div id="cantidadItemsSeleccionados">0</div>';?> Seleccionar</a>
		</nav>
	</header>
	<header>
	<nav>
	<form id="filtroEmpleadoEmpresa" data-rutaAplicacion="empleadoEmpresa" data-opcion="listaEmpleadoEmpresa" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<table class="filtro"  >
			<tbody>
				<tr>
					<th colspan="4">Consultar Empleado:</th>
				</tr>	
				<tr>	
					<td colspan="2">Identificación Empleado: </td>
					<td colspan="2"><input id="identificacionEmpleadoH" name="identificacionEmpleadoH" type="text"  /></td>
				</tr>
				<tr>
					<td colspan="2">Nombre Empleado: </td>
					<td colspan="2"><input id="nombreEmpleadoH" name="nombreEmpleadoH" type="text"  /></td>		
				</tr>	
				<tr>
					<td colspan="2">Apellido Empleado: </td>
					<td colspan="2"><input id="apellidoEmpleadoH" name="apellidoEmpleadoH" type="text"  /></td>		
				</tr>
				<tr>
					<td colspan="4" style='text-align:center'><button>Consultar Empleado</button></td>	
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="mensajeError"></td>
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
			<th>Empresa</th>
			<th>Empleado</th>				
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
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);			
	});
	
	$('#_actualizar').click(function(event){
		event.preventDefault();
		abrir($('#_actualizar'),event, false);
	});

	$("#filtroEmpleadoEmpresa").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#identificacionEmpleadoH").val()=="" && $("#nombreEmpleadoH").val()==""  && $("#apellidoEmpleadoH").val()=="" ){	
			 error = true;	
				$("#mensajeError").html("Por favor ingrese al menos un campo").addClass('alerta');					
		}
		
		if(!error){
			abrir($(this),event,false);
		}	
	});
</script>