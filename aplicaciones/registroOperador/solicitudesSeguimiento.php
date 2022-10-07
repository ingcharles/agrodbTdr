<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();

	$tiposOperacion = $cr->listarTiposOperacion($conexion);
?>
<header>
	<h1>Solicitudes Recibidas</h1>
	<nav>
	<form id="reporteSolicitudes" data-rutaAplicacion="registroOperador" data-opcion="listaSolicitudesSeguimiento" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th></tr>
			<tr>
									
				<td>RUC:</td>
				<td><input name="ruc" type="text" /></td>	
			</tr>
			
			<tr>				
				<td># Solicitud:</td>
				<td><input name="idSolicitud" type="text" /></td>	
			</tr>
				
			<tr>
				<td>estado:</td>
					<td><select id="estado" name="estado" >
					<option value="" selected="selected">Seleccione....</option>
					<option value="enviado" >Enviado</option>
					<option value="proceso" >En Proceso</option>
					<option value="finalizado" >Finalizado</option>
					<option value="aprobado" >Aprobado</option>
					<option value="rechazado" >Rechazado</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>tipo operación:</td>
					<td><select id="tipoOperacion" name="tipoOperacion" >
					<option value="" selected="selected">Seleccione....</option>
					<?php 					
						while ($fila = pg_fetch_assoc($tiposOperacion)){
							echo '<option value="' . $fila['id_tipo_operacion'] . '" data-grupo='. $fila['id_area'] .'>' . $fila['nombre_operacion'] . '</option>';
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<div id="tabla"></div>

<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios items y presione el boton "Generar reporte".</div>');
	});

	$("#reporteSolicitudes").submit(function(e){
		abrir($(this),e,false);
	});
</script>