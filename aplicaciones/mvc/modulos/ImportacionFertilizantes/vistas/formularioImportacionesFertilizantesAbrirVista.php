<header>
	<h1><?php echo $this->accion; ?></h1>
</header>	  
	  <?php echo $this->resultadoRevision;?>
	  
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ImportacionFertilizantes'
	  data-opcion='importacionesFertilizantesProductos/guardar' data-destino='detalleItem' data-accionEnExito='ACTUALIZAR'>
	
	<fieldset>
		<legend>Datos del importador</legend>
		
		<div data-linea="1">
			<label for="identificador">RUC: </label><?php echo $this->modeloImportacionesFertilizantes->getIdentificador(); ?>
		</div>

		<div data-linea="2">
			<label for="razon_social">Razón social: </label><?php echo $this->modeloImportacionesFertilizantes->getRazonSocial(); ?>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Datos de importación</legend>
		
		<input type="hidden" id="id_importacion_fertilizantes" name="id_importacion_fertilizantes" value="<?php echo $this->modeloImportacionesFertilizantes->getIdImportacionFertilizantes(); ?>" />

		<div data-linea="1">
			<label for="tipo_operacion">Operación registrada: </label><?php echo $this->modeloImportacionesFertilizantes->getTipoOperacion(); ?>
		</div>

		<div data-linea="2">
			<label for="tipo_solicitud">Tipo solicitud: </label><?php echo $this->modeloImportacionesFertilizantes->getTipoSolicitud(); ?>
		</div>
		
		<div data-linea="7">
			<label for="id_pais_origen">País origen: </label><?php echo $this->modeloImportacionesFertilizantes->getNombrePaisOrigen(); ?>
		</div>
		
		<div data-linea="7">
			<label for="id_pais_procedencia">País procedencia: </label><?php echo $this->modeloImportacionesFertilizantes->getNombrePaisProcedencia(); ?>
		</div>
		
		<div data-linea="3">
			<label for="producto_formular">Producto a formular: </label><?php echo $this->modeloImportacionesFertilizantes->getProductoFormular(); ?>
		</div>

		<div data-linea="8">
			<label for="numero_factura_pedido">Número factura pedido: </label><?php echo $this->modeloImportacionesFertilizantes->getNumeroFacturaPedido(); ?>
		</div>
	
	</fieldset>
	
	<fieldset>
		<legend>Detalle de productos</legend>
			<table id="tbItems" style="width:100%">
				<thead>
					<tr>
						<th style="width: 30%;">Nombre comercial</th>
						<th style="width: 15%;"># registro</th>
                        <th style="width: 15%;">Cantidad</th>
                        <th style="width: 15%;">Peso</th>
                        <th style="width: 15%;">Partida</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $this->tablaProductos;?>
				</tbody>
			</table>
	</fieldset>	
	
	<fieldset>
		<legend>Documentos anexos</legend>
		
		<?php echo $this->documentos;?>
		
	</fieldset>
	
	<div id="cargarMensajeTemporal"></div>
	
	<?php echo $this->botonActualizar;?>
	

</form>
	
<script type="text/javascript">

	$(document).ready(function() {
		distribuirLineas();
		mostrarMensaje("","EXITO");
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");
		
		$('.validacionProducto').each(function(i, obj) {

			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}

			if($(this).attr('type') == 'number'){
				if($(this).val() <= 0){
					error = true;
					$(this).addClass("alertaCombo");
				}
			}
		});
		
		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				JSON.parse(ejecutarJson($("#formulario")).responseText);
			}, 1000);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

</script>
