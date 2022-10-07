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
		<form id="listarFinalizarOrdenPago" data-rutaAplicacion="financiero" data-opcion="listaFinalizarOrdenPago" data-destino="tabla">
		<table class="filtro">
			
			<tr>
				<th>Solicitud</th>
			
					<td>
						<select id="solicitudes" name="solicitudes" style="width: 100%;">
							<option value="" >Seleccione....</option>
							<option value="Operadores" >Registro Operador</option>
							<option value="Importación" >Importación</option>
							<option value="Fitosanitario" >Fitosanitario</option>
							<option value="FitosanitarioExportacion" >Fitosanitario Exportación V2</option>
							<option value="Emisión de Etiquetas" >Emisión de Etiquetas</option>
							<!--option value="Zoosanitario" >Zoosanitario</option-->
							<!-- option value="CLV" >Certificado de Libre Venta</option-->
							<!-- >option value="certificadoCalidad" >Certificado de calidad</option-->
							<option value="dossierPlaguicida">Dossier Plaguicida</option>
							<option value="dossierPecuario" >Dossier Pecuario</option>							
							<option value="dossierFertilizantes">Dossier Fertilizantes</option>
							<option value="ensayoEficacia">Ensayo Eficacia</option>
							<option value="mercanciasImportacionExportacion" >Imp./Exp. Mascotas</option>
							<option value="certificacionBPA">Certificación BPA</option>
							<option value="certificadoFito">Certificado Fitosanitario</option>
							<option value="modificacionProductoRia">Modificación de registro de producto</option>
							<option value="recargaSaldo">Recarga de Saldo</option>
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
				<td id="operador" colspan="4"></td>
			</tr>
					
			<tr id="combosAdicionales">
				<th>Número documento</th>
					<td>
						<input id="factura" name="factura" type="text" />
					</td>
					
				<th>Estado</th>
				
					<td>
						<select id="estadoSolicitud" name="estadoSolicitud" style="width: 100%;">
							<option value="" data-tipo = "estadoComprobante">Todos</option>
							<option value="3" selected="selected" data-tipo = "estadoComprobante">Por liquidar</option>
							<option value="POR ATENDER"  data-tipo = "estadoSRI">Por enviar SRI</option>
							<option value="RECIBIDA"  data-tipo = "estadoSRI">Enviado al SRI</option>
							<option value="DEVUELTA"  data-tipo = "estadoSRI">Devuelto por SRI</option>
							<option value="AUTORIZADO"  data-tipo = "estadoSRI">Autorizado por SRI</option>
							<option value="NO AUTORIZADO"  data-tipo = "estadoSRI">No autorizado por SRI</option>
						</select>
						<input type="hidden" id="tipoEstado" name="tipoEstado"/>
					</td>	
			</tr>
			
			<tr >	
				<td colspan="5">
					<button id="filtrarSolicitudes" name="filtrarSolicitudes">Filtrar lista</button>
				</td>
			</tr>

		</table>
		
		<input type="hidden" name="opcion" value= "	<?php echo $_POST["opcion"];?>">
		<input type="hidden" id="estadoActual" name="estadoActual" value="financiero">
		<input type="hidden" id="estados" name="estados" value="verificacion">
		
		</form>
	</nav>
</header>
<div id="tabla"></div>
<script>

	$("#listarFinalizarOrdenPago").submit(function(e){
		if($("#solicitudes").val() == 'Operadores'){
			$("#listarFinalizarOrdenPago").attr('data-opcion', 'listaRevisionFinancieraFiltradoOperador');
			$("#listarFinalizarOrdenPago").attr('data-destino', 'tabla');
			abrir($(this),e,false);
		}else if ($("#solicitudes").val() == 'certificadoCalidad'){
			$("#listarFinalizarOrdenPago").attr('data-opcion', 'listaRevisionGrupoCcalidad');
			$("#listarFinalizarOrdenPago").attr('data-destino', 'tabla');
			abrir($(this),e,false);
		}else if ($("#solicitudes").val() == 'Otros' || $("#solicitudes").val() == 'recargaSaldo'){
			$("#listarFinalizarOrdenPago").attr('data-opcion', 'listaFinalizarOrdenPago');
			$("#listarFinalizarOrdenPago").attr('data-destino', 'tabla');		
			abrir($(this),e,false);
		}else{
			$("#listarFinalizarOrdenPago").attr('data-opcion', 'listaRevisionFinancieraFiltrado');
			 $("#listarFinalizarOrdenPago").attr('data-destino', 'tabla');
			abrir($(this),e,false);
		}
	});

	$(document).ready(function(){
		$("#tipoEstado").val($("#estadoSolicitud option:selected").attr("data-tipo"));
		$("#operador").hide();
		$("#combosAdicionales").hide();
	});

	$("#estadoSolicitud").change(function(event){	 
		$("#tipoEstado").val($("#estadoSolicitud option:selected").attr("data-tipo"));
	});

	

	$("#solicitudes").change(function (event) {
		$("#operador").html('');

		if($("#solicitudes").val() == 'Operadores' || $("#solicitudes").val() == 'certificadoCalidad'){
			$("#combosAdicionales").hide();
			$("#listarFinalizarOrdenPago").attr('data-opcion', 'combosOperador');
	    	$("#listarFinalizarOrdenPago").attr('data-destino', 'operador');
	    	abrir($("#listarFinalizarOrdenPago"), event, false); 
		}else if($("#solicitudes").val() == 'Otros' || $("#solicitudes").val() == 'recargaSaldo'){
			$("#combosAdicionales").show();
			$("#operador").hide();
		}else{
			$("#operador").hide();
			$("#combosAdicionales").hide();
		}	
	});

	$("#provincia").change(function (event) {
		$("#operador").html('');

		if($("#solicitudes").val() == 'Operadores' || $("#solicitudes").val() == 'certificadoCalidad'){
			$("#listarFinalizarOrdenPago").attr('data-opcion', 'combosOperador');
	    	$("#listarFinalizarOrdenPago").attr('data-destino', 'operador');
	    	abrir($("#listarFinalizarOrdenPago"), event, false); 
		}else{
			$("#operador").hide();
		}
	});
	
</script>	
	