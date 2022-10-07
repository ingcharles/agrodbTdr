<?php 
session_start();
?>
<header>
<h1>Inspecciones Realizadas</h1>
	<nav>
		<form id="formulario" action="aplicaciones/consultaInspecciones/reporteImprimirProtocoloTecnicos.php" method="post">
			<table class="filtro" style='width:100%;'>
				<tbody>
					<tr>
						<th colspan="4">Buscar</th>					
					</tr>
					<tr>
						<td align="left">Tipo de Formulario:</td>
						<td colspan="3">
							<select id="tipoFormulario" name="tipoFormulario" required style='width:99%;'>
								<option value="">Seleccione...</option>
								<option value="formularioPiniaFinca">Piña de Finca</option>
								<option value="formularioEmbalajeMadera">Embalaje de Madera</option>
								<option value="formularioCalidadCacao">Calidad de Cacao</option>
								<option value="formularioInspeccionFitosanitaria">Inspección Fitosanitaria</option>
								<option value="formularioOrnamentalesAgencia">Ornamentales en Agencia</option>
								<option value="formularioInspeccionMango">Inspección de Mango</option>
								<option value="formularioProtocolo">Protocolo</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Tipos de Protocolos:</td>
						<td colspan="3">
							<select id="tipoProtocolo" name="tipoProtocolo" disabled="disabled" style='width:99%;'>
								<option value="">Seleccione...</option>
								<option value="protocoloRoya">Protocolo de Roya</option>
								<option value="protocoloAcaros">Protocolo de Ácaros</option>
								<option value="protocoloMinador">Protocolo de Minador</option>
								<option value="protocoloTrips">Protocolo de Trips</option>
								<option value="protocoloDesvitalizacion">Protocolo de Desvitalización</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="left">Fecha Inicio:</td>
						<td><input id="fechaInicio" type="text" name="fechaInicio" readonly="readonly" required style='width:98%;'></td>
						<td>Fecha Fin:</td>
						<td><input id="fechaFin" type="text" name="fechaFin" readonly="readonly" required style='width:98%;' ></td>					
					</tr>
					<tr>
						<td align="left">Operador Cédula/RUC:</td>
						<td colspan="3"><input id="identificadorUsuario" type="text" name="identificadorUsuario" maxlength="13" style='width:98%;'></td>
					</tr>
					<tr>
						<td align="left">Nombre Operador:</td>
						<td colspan="3"><input id="nombreUsuario" type="text" name="nombreUsuario" maxlength="256" style='width:98%;'></td>
					</tr>
					<tr>
						<td colspan="4" style='text-align:center'><button type="submit" class="guardar" >Generar Reporte</button></td>
					</tr>
					<tr>
						<td colspan="4"  align="center" id="estadoError" ></td>
					</tr>
				</tbody>
			</table>
		</form>	
	</nav>
</header>
<script>	
	$(document).ready(function(){
		distribuirLineas();
		var fecha = new Date();
        fecha.setMonth(fecha.getMonth() - 3);
        $("#fechaInicio").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-5:+0',
            dateFormat: "yy-mm-dd",
            defaultDate: -1
        }).datepicker('setDate', fecha);
        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-5:+0',
            dateFormat: "yy-mm-dd"
        }).datepicker('setDate', new Date());
	});

	$("#tipoFormulario").change(function(event){
		if($("#tipoFormulario").val()=="formularioProtocolo"){
			$("#formulario").attr("action","aplicaciones/consultaInspecciones/reporteImprimirProtocoloTecnicos.php");
			$("#tipoProtocolo").attr("disabled",false);
			$("#tipoProtocolo").attr("required",true);
		}else{
			if($("#tipoFormulario").val()=="formularioPiniaFinca"){
				$("#formulario").attr("action","aplicaciones/consultaInspecciones/reporteImprimirPiniaFinca.php");
			}else if($("#tipoFormulario").val()=="formularioEmbalajeMadera"){
				$("#formulario").attr("action","aplicaciones/consultaInspecciones/reporteImprimirEmbalajeMadera.php");
			}else if($("#tipoFormulario").val()=="formularioCalidadCacao"){
				$("#formulario").attr("action","aplicaciones/consultaInspecciones/reporteImprimirCalidadCacao.php");
			}else if($("#tipoFormulario").val()=="formularioInspeccionFitosanitaria"){
				$("#formulario").attr("action","aplicaciones/consultaInspecciones/reporteImprimirInspeccionFitosanitaria.php");
			}else if($("#tipoFormulario").val()=="formularioOrnamentalesAgencia"){
				$("#formulario").attr("action","aplicaciones/consultaInspecciones/reporteImprimirOrnamentalesAgencia.php");
			}else if($("#tipoFormulario").val()=="formularioInspeccionMango"){
				$("#formulario").attr("action","aplicaciones/consultaInspecciones/reporteImprimirInspeccionMango.php");
			}
			$("#tipoProtocolo").val('');
			$("#tipoProtocolo").attr("disabled",true);
			
		}

		
		/*if($("#identificadorUsuario").val()=="" && $("#nombreUsuario").val()==""){
			$("#identificadorUsuario").attr("required",true);
			$("#nombreUsuario").attr("required",true);
		}else{
			$("#identificadorUsuario").attr("required",false);
			$("#nombreUsuario").attr("required",false);
		}*/
		
	});

	/*$("#identificadorUsuario").change(function(event){
		if($("#identificadorUsuario").val()=="" && $("#nombreUsuario").val()==""){
			$("#identificadorUsuario").attr("required",true);
			$("#nombreUsuario").attr("required",true);
		}else{
			$("#identificadorUsuario").attr("required",false);
			$("#nombreUsuario").attr("required",false);
		}
	});

	$("#nombreUsuario").change(function(event){
		if($("#identificadorUsuario").val()=="" && $("#nombreUsuario").val()==""){
			$("#identificadorUsuario").attr("required",true);
			$("#nombreUsuario").attr("required",true);
		}else{
			$("#identificadorUsuario").attr("required",false);
			$("#nombreUsuario").attr("required",false);
		}
	});*/
	 
</script>