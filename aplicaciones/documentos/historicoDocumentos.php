<?php 

require_once '../../clases/Conexion.php';
$conexion = new Conexion();

//Validar sesion
$conexion->verificarSesion();
?>

<header>
	<h1>Mis documentos archivados</h1>
	<nav>
	<form id="filtrar" data-rutaAplicacion="documentos" data-opcion="listadoDocumentosFiltrados" data-destino="tabla">
	
	<input name="identificador" type="hidden" value="<?php echo $_SESSION['usuario'];?>"/>
	
	
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td># documento:</td>
				<td><input name="archivo" type="text" /></td>
				<td>asunto:</td>
				<td><input name="asunto" type="text" /></td>
			</tr>
			<tr>
				<th>Entre las fechas</th>
				<td>inicio:</td>
				<td><input type="text" name="fi" id="fechaInicio" /></td>
				<td>fin:</td>
				<td><input type="text" name="ff" id="fechaFin" /></td>
			</tr>
			<tr>
				<th>Mostrar</th>
				<td>estado:</td>
				<td><select name="estado">
					<option value="">Todos</option>
					<option value="1">Sin notificar</option>
					<option value="2">Pendientes</option>
					<option value="3">Aprobados</option>
					<option value="9">Eliminados</option>
				</select>
				</td>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<div id="tabla"></div>
<script>
	$("#filtrar").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
	});
	</script>
