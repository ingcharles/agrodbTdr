<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$ca = new ControladorAplicaciones();
	
	$qProvincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');
	
?>



<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Depósito</h1>
		<!-- nav>
		< ?php 

			
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				
			}
		?>
		</nav-->
</header>

<nav>
	<form id="listaRevisionFinanciera" data-rutaAplicacion="financiero" data-opcion="listaRevisionFinancieraFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Solicitud</th>
			
					<td>
						<select id="solicitudes" name="solicitudes" style="width:100%;">
							<option value="" >Seleccione....</option>
							<option value="Operadores" >Registro Operador</option>
							<option value="Importación" >Importación</option>
							<option value="Fitosanitario" >Fitosanitario</option>
							<option value="FitosanitarioExportacion" >Fitosanitario Exportación V2</option>
							<option value="Emisión de Etiquetas" >Emisión de Etiquetas</option>
							<!-- option value="Zoosanitario" >Zoosanitario</option-->
							<!--option value="CLV" >Certificado de Libre Venta</option-->
							<!-- >option value="certificadoCalidad" >Certificado de calidad</option-->
							<option value="mercanciasImportacionExportacion" >Imp./Exp. Mascotas</option>
							<option value="dossierPlaguicida">Dossier Plaguicida</option>
							<option value="dossierPecuario" >Dossier Pecuario</option>
							<option value="dossierFertilizantes">Dossier Fertilizantes</option>
							<option value="ensayoEficacia">Ensayo Eficacia</option>
							<option value="certificacionBPA">Certificación BPA</option>
							<option value="certificadoFito">Certificado Fitosanitario</option>
							<option value="modificacionProductoRia">Modificación de registro de producto</option>
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
				
				<tr id="estadoOrdenPago">
					<th>Estado </th>
						<td>
							<select id="estados" name="estados">
									<option value="" >Seleccione....</option>
									<option value="pago" >Generar orden de pago</option>
									<option value="verificacionVUE" >Modificar orden de pago</option>
							</select>
					<td id="operador" colspan="4"></td>
				</tr>
				
				<tr >	
					<td colspan="5"><button id="botonFiltrar">Filtrar lista</button></td>
				</tr>
		</table>
		
		<input type="hidden" name="opcion" value= "	<?php echo $_POST["opcion"];?>">
		<input type="hidden" id="estadoActual" name="estadoActual" value="financiero">
		
	</form>
</nav>

<div id="tabla"></div>
	
</body>
<script>
$(document).ready(function(){

	
	//$("#ventanaAplicacion #botonFiltrar").click();
	//$("#botonFiltrar").trigger("click");
	
	$("#listadoItems").addClass("comunes");
	$("#operador").hide();
});

$("#listaRevisionFinanciera").submit(function(e){
	e.preventDefault();
	if($("#solicitudes").val() == 'Operadores'){
		$("#listaRevisionFinanciera").attr('data-opcion', 'listaRevisionFinancieraFiltradoOperador');
		$("#listaRevisionFinanciera").attr('data-destino', 'tabla');
		abrir($(this),e,false);
	}else if ($("#solicitudes").val() == 'certificadoCalidad'){
		$("#listaRevisionFinanciera").attr('data-opcion', 'listaRevisionGrupoCcalidad');
		$("#listaRevisionFinanciera").attr('data-destino', 'tabla');
		abrir($(this),e,false);
	}else if ($("#solicitudes").val() == 'Otros'){
		$("#listaRevisionFinanciera").attr('data-opcion', 'listaRevisionOtrasSolicitudes');
		$("#listaRevisionFinanciera").attr('data-destino', 'tabla');		
		abrir($(this),e,false);
	}else{
		$("#listaRevisionFinanciera").attr('data-opcion', 'listaRevisionFinancieraFiltrado');
		 $("#listaRevisionFinanciera").attr('data-destino', 'tabla');
		abrir($(this),e,false);
	}

});

$("#estados").change(function (event) {
	if($("#solicitudes").val() == 'Operadores' || $("#solicitudes").val() == 'certificadoCalidad'){
		$("#listaRevisionFinanciera").attr('data-opcion', 'combosOperador');
    	$("#listaRevisionFinanciera").attr('data-destino', 'operador');
    	abrir($("#listaRevisionFinanciera"), event, false); 
	}else{
		$("#operador").hide();
	}
});

$("#solicitudes").change(function (event) {

	if($("#solicitudes").val() == 'Otros'){
		$("#estadoOrdenPago").hide();
	}else{
		$("#estadoOrdenPago").show();
	}
	
	cargarValorDefecto("estados","");
	$("#operador").html('');
});

$("#provincia").change(function (event) {
	cargarValorDefecto("estados","");
	$("#operador").html('');
});


</script>
</html>