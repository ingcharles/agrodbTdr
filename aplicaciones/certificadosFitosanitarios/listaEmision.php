<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();

$agencia = $cc->abrirAgencias($conexion);

?>

<header>
	<h1>Emisión certificados</h1>
	<nav>
	<form id="reporteCertificado" data-rutaAplicacion="certificadosFitosanitarios" data-opcion="listaCertificadosFiltrados" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Seleccione:</th>
				<td>agencia:</td>
				<td>
					<select id="agencia" name="agencia">
						<option value="" >Agencia....</option>
							<?php 
								while($fila = pg_fetch_assoc($agencia)){
									echo '<option value="' . $fila['identificador'] . '">' . $fila['razon_social'] . '</option>';
								}
							?>
					</select>
				</td>
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
					<option value="1">Confirmado</option>
					<option value="2">Impreso</option>
					<option value="3">Expedido</option>
					<option value="4">Anulado</option>
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
	$("#reporteCertificado").submit(function(e){
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
