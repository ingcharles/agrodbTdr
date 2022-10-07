<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion();
$ca = new ControladorAuditoria();

?>

<header>
	<h1>Listado auditorías por asociaciones</h1>

	<nav>
		<form id="filtrarAuditoriaAsociacion" data-rutaAplicacion="registroAsociacion" data-opcion="filtroListaAuditoriaAsociacion" data-destino="tabla">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			
		<table class="filtro">					
			<tr>
				<th colspan="5">Busqueda por miembro de asociación</th>
			</tr>
			<tr>
				<th>Identificación:</th>
				<td colspan="3"> 
					<input id="identificadorMiembro" type="text" name="identificadorMiembro" maxlength="256" style="width: 100%;"> 
				</td>
			</tr>
			<tr>
				<th>Nombre completo:</th>
				<td colspan="3"> 
					<input id="nombreMiembroAsociacion" type="text" name="nombreMiembroAsociacion" maxlength="128" style="width: 100%;"> 
				</td>
			</tr>
			<tr>
				<th>Fecha inicio:</th>
					<td> 
						<input id="fechaInicio" type="text" name="fechaInicio" maxlength="128">
					</td>
				<th>Fecha Fin:</th>
					<td> 
						<input id="fechaFin" type="text" name="fechaFin" maxlength="128"> 
					</td>
			</tr>
			<tr>
				<td id="mensajeError"></td>
				<td colspan="5"> <button id="buscar">Buscar</button> </td>
			</tr>
		</table>
		
		
		</form>
	</nav>
</header>
<div id="tabla"></div>

<script>

	$(document).ready(function(){
		distribuirLineas();
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

	$("#filtrarAuditoriaAsociacion").submit(function(event){
		abrir($(this),event,false);
	});
	
</script>