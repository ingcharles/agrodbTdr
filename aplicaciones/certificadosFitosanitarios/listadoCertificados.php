<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();

$certificados = $cc -> listarOperadores($conexion);

?>

<header>
	<h1>Impresión Certificados.</h1>
	<nav>
	<form id="reporteCertificados" data-rutaAplicacion="certificadosFitosanitarios" data-opcion="listaCertificadosFiltrados" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Entre las fechas</th>
				<td>inicio:</td>
				<td><input type="text" name="fi" id="fechaInicio" /></td>
				<td>fin:</td>
				<td><input type="text" name="ff" id="fechaFin" /></td>
			</tr>
			<tr>
				<th>Operadores</th>
				<td>agencias:</td>
				<td><select id="operador" name="operador" >
					<option value="">Operador....</option>
					<?php 
						while($fila = pg_fetch_assoc($certificados)){
							echo '<option value="' . $fila['identificador'] . '">' . $fila['razon_social'] . '</option>';					
						}
					?>
				</select></td>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<div id="tabla"></div>
<script>

	$("#reporteVehiculos").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
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
