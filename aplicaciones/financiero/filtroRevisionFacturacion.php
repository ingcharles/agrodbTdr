<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cca = new ControladorCatalogos();
	
	$qProvincias = $cca->listarLocalizacion($conexion, 'PROVINCIAS');
	
?>
<header>
	<h1>Liquidación orden</h1>
	<nav>
		<form id="listarRevisionFacturacion" data-rutaAplicacion="financiero" data-opcion="listaRevisionFacturacion" data-destino="tabla">
		<table class="filtro">
			
			<tr>
				<th>Solicitud</th>
			
					<td>
						<select id="tipoSolicitud" name="tipoSolicitud" style="width: 100%;">
							<option value="" >Seleccione....</option>
							<option value="Operadores" >Registro Operador</option>
							<option value="Importación" >Importación</option>
							<option value="Fitosanitario">Fitosanitario</option>
							<option value="Emisión de Etiquetas" >Etiquetas</option>
							<!--option value="Zoosanitario" >Zoosanitario</option-->
							<!-- option value="CLV" >Certificado de Libre Venta</option-->
							<!-- >option value="certificadoCalidad" >Certificado de calidad</option-->
							<option value="Otros" >Otros</option>
						</select>	
					</td>
					
				<th>Provincia </th>

					<td>
						<select id="provincia" name="provincia">
								<?php 
									while ($fila = pg_fetch_assoc($qProvincias)){
										if($_SESSION['nombreProvincia'] == $fila['nombre']){
											echo '<option value="'.$fila['nombre'].'" selected="selected">'.$fila['nombre'].'</option>';
										}else{
											echo '<option value="'.$fila['nombre'].'">'.$fila['nombre'].'</option>';
										}
									}
								?>
			
						</select>
	
					</td>		
			</tr>
			
			<tr>
				<th>Fecha inicio</th>
					<td>
						<input id="fechaInicio" name="fechaInicio" type="text" style="width: 100%;"/>
					</td>
					
				<th>Fecha fin</th>
				
					<td>
						<input id="fechaFin" name="fechaFin" type="text" style="width: 100%;"/>
					</td>	
			</tr>
								
			<tr>
				<th>Número orden</th>
					<td>
						<input id="numeroOrdenPago" name="numeroOrdenPago" type="text" />
					</td>
					
				<th>Estado</th>
				
					<td>
						<select id="estadoSolicitud" name="estadoSolicitud" style="width: 100%;">
							<option value="">Todos</option>
							<option value="POR ATENDER">Por enviar SRI</option>
							<option value="RECIBIDA">Enviado al SRI</option>
							<option value="DEVUELTA">Devuelto por SRI</option>
							<option value="AUTORIZADO">Autorizado por SRI</option>
							<option value="NO AUTORIZADO">No autorizado por SRI</option>
						</select>
					</td>	
			</tr>
			
			<tr >	
				<td colspan="5">
					<button>Filtrar lista</button>
				</td>
			</tr>

		</table>
		
		</form>
	</nav>
</header>
<div id="tabla"></div>
<script>

$('document').ready(function(){

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
	
});

$("#listarRevisionFacturacion").submit(function(e){
	abrir($(this),e,false);
});

</script>	
	