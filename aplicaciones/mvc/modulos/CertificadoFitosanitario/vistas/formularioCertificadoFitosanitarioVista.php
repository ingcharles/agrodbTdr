<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
	data-opcion='certificadoFitosanitario/guardar'
	data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"
	method="post">

	<input
		type="hidden" id="id_pais_origen" name="id_pais_origen" value=""
		readonly="readonly" /> <input type="hidden" id="nombre_pais_origen"
		name="nombre_pais_origen" value="" readonly="readonly" /> <input type="hidden"
		id="nombre_provincia_origen" name="nombre_provincia_origen" value=""
		readonly="readonly" /> <input type="hidden" id="nombre_idioma"
		name="nombre_idioma" value="" readonly="readonly" /> <input type="hidden"
		id="nombre_medio_transporte" name="nombre_medio_transporte" value=""
		readonly="readonly" /> <input type="hidden"
		id="nombre_puerto_embarque" name="nombre_puerto_embarque" value=""
		readonly="readonly" /> <input type="hidden" id="nombre_pais_destino"
		name="nombre_pais_destino" value="" readonly="readonly" />

	<!-- Inputs de arrays  -->
	<input type="hidden" id="array_pais_puertos_destino"
		name="array_pais_puertos_destino" value="" readonly="readonly" /> <input
		type="hidden" id="array_pais_puertos_transito"
		name="array_pais_puertos_transito" value="" readonly="readonly" /> <input
		type="hidden" id="array_exportadores_productos"
		name="array_exportadores_productos" value="" readonly="readonly" />

	<fieldset>
		<legend>Datos Generales</legend>

		<div data-linea="1">
			<label for="tipo_certificado">Tipo de Solicitud: </label> <select
				id="tipo_certificado" name="tipo_certificado" class="validacion">
				<option value="">Seleccionar....</option>
				<option value="musaceas">Musaceas</option>
				<option value="ornamentales">Ornamentales</option>
				<option value="otros">Otros</option>
			</select>
		</div>

		<div data-linea="1">
			<label for="id_idioma">Idioma: </label> <select id="id_idioma"
				name="id_idioma" class="validacion" disabled="disabled">
				<option value="">Seleccionar....</option>
				<?php
    echo $this->comboIdiomas($this->modeloCertificadoFitosanitario->getIdIdioma());
    ?>
            </select>
		</div>
		<div data-linea="2" id="notaIngles">
			<label for="notaIngles">Nota: </label><em>Llenar los campos de la
				solicitud en idioma inglés.</em>
		</div>
		<hr />

		<div data-linea="3">
			<label for="producto_organico">Producto Orgánico: </label> <select
				id="producto_organico" name="producto_organico" class="validacion"
				disabled="disabled">
                <?php
                echo $this->comboSiNo($this->modeloCertificadoFitosanitario->getProductoOrganico());
                ?>
            </select>
		</div>

		<div data-linea="3">
			<label for="id_provincia_origen">Provincia Origen: </label> <select
				id="id_provincia_origen" name="id_provincia_origen"
				class="validacion" disabled="disabled">
				<option value="">Seleccionar....</option>
                <?php
                echo $this->comboProvinciasEc($this->modeloCertificadoFitosanitario->getIdProvinciaOrigen());
                ?>
            </select>
		</div>

		<div data-linea="4">
			<label for="id_medio_transporte">Medio de Transporte: </label> <select
				id="id_medio_transporte" name="id_medio_transporte"
				class="validacion" disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="4">
			<label for="id_puerto_embarque">Puerto de Embarque: </label> <select
				id="id_puerto_embarque" name="id_puerto_embarque" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="5">
			<label for="fecha_embarque">Fecha de Embarque: </label> <input
				type="text" id="fecha_embarque" name="fecha_embarque" value=""
				placeholder="Ejm: 2021-02-01" readonly="readonly" disabled />
		</div>

		<div data-linea="5">
			<label for="numero_viaje">Número de Viaje: </label> <input
				type="text" id="numero_viaje" name="numero_viaje" value=""
				placeholder="Ejm: 12" maxlength="64" />
		</div>

		<hr />

		<div data-linea="6">
			<label for="nombre_marca">Nombre de Marcas: </label> <input
				type="text" id="nombre_marca" name="nombre_marca" value=""
				placeholder="Ejm: Marca A" maxlength="200" />
		</div>

		<div data-linea="7">
			<label for="informacion_adicional">Información Adicional del Envío: </label>
		</div>

		<div data-linea="8">
			<input type="text" id="informacion_adicional"
				name="informacion_adicional" value=""
				placeholder="Ingrese información adicional" maxlength="825" />
		</div>

		<div data-linea="9">
			<label for="nombre_consignatario">Nombre del Consignatario: </label>
			<input type="text" id="nombre_consignatario"
				name="nombre_consignatario" value=""
				placeholder="Ejm: Juan Adrés Torres Mejía" maxlength="200" />
		</div>

		<div data-linea="10">
			<label for="direccion_consignatario">Dirección del Consignatario: </label>
			<input type="text" id="direccion_consignatario"
				name="direccion_consignatario" value=""
				placeholder="Ejm: Av. Vicente Rocafuerte" maxlength="200" />
		</div>
	</fieldset>

	<fieldset>
		<legend>Puertos de Destino</legend>

		<div data-linea="1">
			<label for="id_pais_destino">País de Destino: </label> <select
				id="id_pais_destino" name="id_pais_destino" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="1">
			<label for="id_puerto_pais_destino">Puerto de Destino: </label> <select
				id="id_puerto_pais_destino" name="id_puerto_pais_destino"
				class="validacion" disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<hr />

		<div data-linea="5">
			<button type="button" class="mas" id="agregarPuertoPaisDestino">Agregar</button>
		</div>

		<table id="detallePuertoPaisDestino" style="width: 100%">
			<thead>
				<tr>
					<th>País</th>
					<th>Puerto</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

	</fieldset>

	<fieldset>
		<legend>Añadir Países, Puertos de Tránsito y Medios de Transporte</legend>

		<div data-linea="1">
			<label for="id_pais_transito">País de Tránsito: </label> <select
				id="id_pais_transito" name="id_pais_transito" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="1">
			<label for="id_puerto_transito">Puerto de Tránsito: </label> <select
				id="id_puerto_transito" name="id_puerto_transito" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="2">
			<label for="id_medio_transporte_transito">Medio de Transporte: </label>
			<select id="id_medio_transporte_transito"
				name="id_medio_transporte_transito" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<hr />

		<div data-linea="3">
			<button type="button" class="mas" id="agregarPaisPuertoTransito">Agregar</button>
		</div>

		<table id="detallePaisPuertoTransito" style="width: 100%">
			<thead>
				<tr>
					<th>País</th>
					<th>Puerto</th>
					<th>Medio de Transporte</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

	</fieldset>

	<fieldset>
		<legend>Exportadores y Productos</legend>

		<input type="hidden" id="estado_exportador_producto"
			name="estado_exportador_producto" value="" readonly="readonly" />

		<div data-linea="1">
			<label for="identificador_exportador">Identificador Exportador: </label>
			<input type="text" id="identificador_exportador"
				name="identificador_exportador" value=""
				placeholder="Ejm: 1715897481" maxlength="13" disabled="disabled" />
		</div>

		<div data-linea="2">
			<label for="razon_social_exportador">Nombre / Razón Social: </label>
			<input type="text" id="razon_social_exportador"
				name="razon_social_exportador" value="" readonly="readonly" />
		</div>

		<div data-linea="3">
			<label for="direccion_exportador">Dirección: </label> <input
				type="text" id="direccion_exportador" name="direccion_exportador"
				value="" readonly="readonly" />
		</div>

		<hr />

		<div data-linea="4">
			<label for="id_tipo_producto">Tipo de Producto: </label> <select
				id="id_tipo_producto" name="id_tipo_producto" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="4">
			<label for="id_subtipo_producto">Subtipo de Producto: </label> <select
				id="id_subtipo_producto" name="id_subtipo_producto"
				class="validacion" disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="5">
			<label for="id_producto">Producto: </label> <select id="id_producto"
				name="id_producto" class="validacion" disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="5">
			<label for="partida_arancelaria_producto">Partida Arancelaria: </label>
			<input type="text" id="partida_arancelaria_producto"
				name="partida_arancelaria_producto" value="" readonly="readonly" />
		</div>

		<div data-linea="6" id="iCentificacionOrganica">
			<label for="certificacion_organica">Certificación orgánica: </label>
			<input type="text" id="certificacion_organica"
				name="certificacion_organica" value="" maxlength="6" />
		</div>

		<div data-linea="7">
			<label for="cantidad_comercial">Cantidad Comercial: </label> <input
				type="text" id="cantidad_comercial" name="cantidad_comercial"
				value="" placeholder="Ejm: 200" maxlength="8" />
		</div>

		<div data-linea="7">
			<select id="id_unidad_cantidad_comercial"
				name="id_unidad_cantidad_comercial" class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="8" id="pesoBruto">
			<label for="peso_bruto">Peso Bruto: </label> <input type="text"
				id="peso_bruto" name="peso_bruto" value="" placeholder="Ejm: 284"
				maxlength="8" />
		</div>

		<div data-linea="8" id="idUnidadPesoBruto">
			<select id="id_unidad_peso_bruto" name="id_unidad_peso_bruto"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="9">
			<label for="peso_neto">Peso Neto: </label> <input type="text"
				id="peso_neto" name="peso_neto" value="" placeholder="Ejm: 240"
				maxlength="8" />
		</div>

		<div data-linea="9">
			<select id="id_unidad_peso_neto" name="id_unidad_peso_neto"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<hr />

		<div data-linea="10" id="cTipoCentroAcopio">
			<label>Tipo centro acopio</label> <select id="tipo_centro_acopio"
				name="tipo_centro_acopio" class="validacion">
				<option value="">Seleccionar....</option>
				<option value="propio">Propio</option>
				<option value="proveedor">Proveedor</option>
			</select>
		</div>

		<div data-linea="10" id="buscarCentroAcopio">
			<input type="text" id="identificador_centro_acopio"
				name="identificador_centro_acopio" value="">
		</div>

		<div data-linea="11" id="centroAcopio">
			<label for="codigo_centro_acopio">Centro de Acopio: </label> <select
				id="codigo_centro_acopio" name="codigo_centro_acopio"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="13" id="fechaInspeccion">
			<label for="fecha_inspeccion">Fecha de Inspección: </label> <input
				type="text" id="fecha_inspeccion" name="fecha_inspeccion" value=""
				placeholder="Ejm: 2021-02-01" readonly="readonly" />
		</div>

		<div data-linea="13" id="horaInspeccion">
			<label for="hora_inspeccion">Hora de Inspección: </label> <input
				type="time" id="hora_inspeccion" name="hora_inspeccion" value="" />
		</div>

		<hr id="hCentroAcopio" />

		<div data-linea="14">
			<label for="id_tipo_tratamiento">Tipo de Tratamiento: </label> <select
				id="id_tipo_tratamiento" name="id_tipo_tratamiento"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="14">
			<label for="id_tratamiento">Tratamiento: </label> <select
				id="id_tratamiento" name="id_tratamiento" class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="15">
			<label for="duracion_tratamiento">Duración: </label> <input
				type="text" id="duracion_tratamiento" name="duracion_tratamiento"
				value="" placeholder="Ejm: 2" maxlength="8" />
		</div>

		<div data-linea="15">
			<select id="id_unidad_duracion" name="id_unidad_duracion"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="16">
			<label for="temperatura_tratamiento">Temperatura: </label> <input
				type="text" id="temperatura_tratamiento"
				name="temperatura_tratamiento" value="" placeholder="Ejm: 34"
				maxlength="8" />
		</div>

		<div data-linea="16">
			<select id="id_unidad_temperatura" name="id_unidad_temperatura"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="17">
			<label for="fecha_tratamiento">Fecha de Tratamiento: </label> <input
				type="text" id="fecha_tratamiento" name="fecha_tratamiento" value=""
				placeholder="Ejm: 2021-01-01" readonly="readonly" />
		</div>

		<div data-linea="17">
			<label for="producto_quimico">Producto Químico: </label> <input
				type="text" id="producto_quimico" name="producto_quimico" value=""
				placeholder="Ingrese el nombre del producto químico" maxlength="64" />
		</div>

		<div data-linea="18">
			<label for="nuevo">Concentración: </label> <input type="text"
				id="concentracion_tratamiento" name="concentracion_tratamiento"
				value="" placeholder="Ejm: 23" maxlength="64" />
		</div>

		<div data-linea="18">
			<select id="id_unidad_concentracion" name="id_unidad_concentracion"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<hr />

		<div data-linea="19">
			<button type="button" class="mas" id="agregarExportadoresProductos">Agregar</button>
		</div>

		<table id="detalleExportadoresProductos" style="width: 100%">
			<thead>
				<tr>
					<th>Identificador</th>
					<th>Razón Social</th>
					<th>Producto</th>
					<th>Código orgánico</th>
					<th>Cantidad Comercial</th>
					<th>Peso Bruto</th>
					<th>Peso Neto</th>
					<th>Inspección</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

	</fieldset>

	<fieldset>
		<legend>Documentos Adjuntos</legend>

		<div data-linea="1">
			<input type="hidden" id="ruta_adjunto" class="ruta_adjunto"
				name="ruta_adjunto" value="" /> <input type="file" class="archivo"
				accept="application/msword | application/pdf | image/*" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?></div>
			<button type="button" id="subirArchivo"
				data-rutaCarga="<?php echo CERT_FIT_DOC_ADJ.$this->rutaFecha ?>">Subir
				archivo</button>
		</div>

		<div data-linea="2">
			<label for="ruta_enlace_adjunto">Ruta a documentos de respaldo (mayor
				a 6Mbs): </label> <input type="text" id="ruta_enlace_adjunto"
				name="ruta_enlace_adjunto" value=""
				placeholder="Ingrese el enlace del archivo" maxlength="256" />
		</div>

	</fieldset>

	<fieldset>
		<legend>Forma de Pago</legend>

		<div data-linea="1">
			<label for="forma_pago">Forma de Pago: </label> <select
				id="forma_pago" name="forma_pago" class="validacion"
				disabled="disabled">
                <?php
                echo $this->comboFormaPago;
                ?>
            </select>

		</div>

		<div data-linea="1">
			<label for="descuento">Descuento: </label> <select id="descuento"
				name="descuento" class="validacion" disabled='disabled'>
                <?php
                echo $this->comboSiNo($this->modeloCertificadoFitosanitario->getDescuento());
                ?>
            </select>
		</div>

		<div data-linea="2">
			<label for="motivo_descuento">Motivo del Descuento: </label> <input
				type="text" id="motivo_descuento" name="motivo_descuento" value=""
				placeholder="Ingrese el motivo del descuento" maxlength="64"
				disabled="disabled" />
		</div>

		<input type="hidden" id="fecha_modificacion_certificado"
			name="fecha_modificacion_certificado"
			value="<?php echo $this->rutaFecha; ?>" />

	</fieldset>

	<button type="submit" class="guardar">Enviar solicitud</button>

</form>

<script type="text/javascript">

	$(document).ready(function() {
		construirValidador();
		fn_mostrarOcultarInformacion();
		$("#cantidad_comercial").numeric();
		$("#peso_neto").numeric();
		$("#peso_bruto").numeric();
		$("#duracion_tratamiento").numeric();
		$("#temperatura_tratamiento").numeric();
		$("#concentracion_tratamiento").numeric();
		$("#notaIngles").hide();
		$("#iCentificacionOrganica").hide();
		$("#estado").html("").removeClass('alerta');
		distribuirLineas();
	});	

	$("#tipo_certificado").change(function () {
		fn_cargarDatosPaisOrigen();	
				
		$("#forma_pago").attr("disabled",false);	
		$("#tipo_certificado option:not(:selected)").remove();
		$("#id_idioma").attr("disabled",false);

		var fecha = new Date();	
		if($("#tipo_certificado").val() == "musaceas"){
			$("#fecha_embarque").datepicker({ 
    		    changeMonth: true,
    		    changeYear: true,
    		    dateFormat: 'yy-mm-dd'
    		});   	
		}else if($("#tipo_certificado").val() == "ornamentales"){
			$("#fecha_embarque").datepicker({ 
    		    changeMonth: true,
    		    changeYear: true,
    		    dateFormat: 'yy-mm-dd',
    		    minDate: fecha
    		});   	
		}else if($("#tipo_certificado").val() == "otros"){			
    		$("#fecha_embarque").datepicker({
    		    changeMonth: true,
    		    changeYear: true,
    		    dateFormat: 'yy-mm-dd',
    		    minDate: fecha,
    		    onSelect: function(selectedDate){ 
    		    	var actualDate = new Date(selectedDate);
    			    $("#fecha_inspeccion").datepicker('option', 'minDate', fecha );
    			    $("#fecha_inspeccion").datepicker('option', 'maxDate', selectedDate );
    		    }
    		});   		
		}
				
		$("#fecha_inspeccion").datepicker({ 
		    changeMonth: true,
		    changeYear: true,
		    dateFormat: 'yy-mm-dd'
		});	
    });

	$("#id_idioma").change(function () {
		$("#producto_organico").attr("disabled",false);
		$("#id_idioma option:not(:selected)").remove();
		$("#nombre_idioma").val($("#id_idioma option:selected").text());

		if($('#id_idioma option:selected').attr('data-codigoIdioma') == 'ENG'){
			$("#notaIngles").show();
		}
		
		//TODO:CARGAR LOS COMBOS
		fn_cargarPaisesPorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarMediosTransportePorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarUnidadesMedidaPorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarUnidadesMedidaPorCodigoPorIdioma('KG', $('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarTiposTratamientoPorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarTratamientosPorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarUnidadesDuracionPorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarUnidadesTemperaturaPorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
		fn_cargarConcentracionesTratamientoPorIdioma($('#id_idioma option:selected').attr('data-codigoIdioma'));
			
    });

	$("#producto_organico").change(function () {
		$("#producto_organico option:not(:selected)").remove();
		$("#id_provincia_origen").attr("disabled",false);
		if($("#producto_organico option:selected").val() == "Si"){
			$("#iCentificacionOrganica").show();
		}
    });

	$("#id_provincia_origen").change(function () {
		$("#nombre_provincia_origen").val($("#id_provincia_origen option:selected").text());
        $("#id_medio_transporte").attr("disabled",false);
        $("#fecha_embarque").attr("disabled",false);
	});
	
	$("#id_medio_transporte").change(function () {
		$("#nombre_medio_transporte").val($("#id_medio_transporte option:selected").text());
		if ($("#id_medio_transporte").val() !== "") {
        	fn_cargarPuertosPorMedioTransporte($("#id_puerto_embarque"), $("#id_pais_origen").val(), $("#id_medio_transporte option:selected").text());
        	$("#id_puerto_embarque option:not(:selected)").remove();	 
        }
		$("#id_puerto_embarque").attr("disabled",false);		
	});

	$("#id_pais_destino").change(function () {
		$("#nombre_pais_destino").val($("#id_pais_destino option:selected").text());
		$("#id_puerto_pais_destino").attr("disabled",false);
		$("#identificador_exportador").attr("disabled",false);
        if ($("#id_medio_transporte").val() !== "") {
        	fn_cargarPuertos("pais", $("#id_puerto_pais_destino"), $("#id_pais_destino").val()); 
        }
	});
	
	$("#id_puerto_embarque").change(function () {
		$("#nombre_puerto_embarque").val($("#id_puerto_embarque option:selected").text());
		$("#id_pais_destino").attr("disabled",false);
		$("#id_pais_transito").attr("disabled",false);
	});
	
	$("#id_pais_transito").change(function () {
		$("#id_puerto_transito").attr("disabled",false);
		$("#id_medio_transporte_transito").attr("disabled",false);
		if ($("#id_pais_transito").val() !== "") {
        	fn_cargarPuertos("pais", $("#id_puerto_transito"), $("#id_pais_transito").val());
        }
	});

	$("#identificador_exportador").change(function () {
		$("#id_tipo_producto").attr("disabled",false);				
		$("#estado").html("").removeClass('alerta');
		$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

		if($("#detallePuertoPaisDestino tbody tr").length > 0){
    		$("#id_tipo_producto").attr("disabled",false);				
    		fn_obtenerDatosOperador($("#identificador_exportador").val());
		}else{			
			$("#identificador_exportador").addClass("alertaCombo");		
			mostrarMensaje("Por favor ingrese un país de destino.", "FALLO");
		} 
		
	});

	$("#tipo_centro_acopio").change(function () {
		if($("#tipo_certificado").val() == "otros" || $("#tipo_certificado").val() == "ornamentales"){
    		if($("#tipo_centro_acopio").val() == "propio")
    		{
    			$("#identificador_centro_acopio").val($("#identificador_exportador").val())
    			$('#identificador_centro_acopio').attr('readonly', true);
    			fn_obtenerCentroAcopioExportadorProducto();
    		}else{
    			$("#identificador_centro_acopio").val("")
    			$('#identificador_centro_acopio').attr('readonly', false);
    		}	
		}	
	});

	$("#identificador_centro_acopio").change(function () {
		if($("#tipo_certificado").val() == "otros" || $("#tipo_certificado").val() == "ornamentales"){
    		if($("#identificador_centro_acopio").val() != ""){
    			fn_obtenerCentroAcopioExportadorProducto();
    		}
		}
	});	

	$("#id_tipo_producto").change(function () {
		$("#id_subtipo_producto").attr("disabled",false);				
		fn_obtenerSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud($("#identificador_exportador").val(), $("#id_tipo_producto").val(), $("#tipo_certificado").val());
	});

	$("#id_subtipo_producto").change(function () {
		$("#id_producto").attr("disabled",false);	
		$("#partida_arancelaria_producto").val("");
		fn_obtenerProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais($("#identificador_exportador").val(), $("#id_subtipo_producto").val(), $("#tipo_certificado").val(), $("#id_pais_destino").val());
	});

	$("#id_producto").change(function () {
		fn_mostrarOcultarInformacion();		
		if($("#tipo_certificado option:selected").val() == "musaceas"){
			fn_mostrarOcultarInformacion($("#tipo_certificado option:selected").val(), true);
		}else{
			fn_mostrarOcultarInformacion($("#tipo_certificado option:selected").val(), false);
		}
		
		$("#partida_arancelaria_producto").val($("#id_producto option:selected").attr("data-partidaArancelaria"));
	});

	$("#certificacion_organica").change(function () {
		var identificadorExportador = $("#identificador_exportador").val();
		var codigoPoa = $("#certificacion_organica").val();
		fn_verificarCodigoPoaExportador(identificadorExportador, codigoPoa);
	});

	$("#forma_pago").change(function () {
		if($("#forma_pago").val() == "saldo"){
			$("#descuento").attr("disabled",true);
			$("#motivo_descuento").attr("disabled",true);
			$("#motivo_descuento").val("");	
			$("#descuento").val("");	
		}else{
			$("#descuento").attr("disabled",false);
		}
	});

	$("#descuento").change(function () {
		if($("#descuento").val() == "Si"){
			$("#motivo_descuento").attr("disabled",false);		
		}else{
			$("#motivo_descuento").attr("disabled",true);	
			$("#motivo_descuento").val("");
		}
    });
	
	//Funcion para agregar puertos de país de destino//
    $("#agregarPuertoPaisDestino").click(function(event) {
        
    	event.preventDefault();
       	mostrarMensaje("", "");
    	
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if($("#id_pais_destino").val() == ""){
			error = true;
			$("#id_pais_destino").addClass("alertaCombo");
		}
    	
    	if($("#id_puerto_pais_destino").val() == ""){
			error = true;
			$("#id_puerto_pais_destino").addClass("alertaCombo");
		}

        if(!error){
   
			$("#estado").html("").removeClass('alerta');
			$('#id_pais_destino option:not(:selected)').attr('disabled',true);

			if($("#id_pais_destino").val() != "" && $("#id_puerto_pais_destino").val() != ""){
				
				var codigoPuertoPaisDestino = 'r_' + $("#id_pais_destino").val() + $("#id_puerto_pais_destino").val();
				var cadena = '';

				//Valida que no exista en la tabla
				if($("#detallePuertoPaisDestino tbody #"+codigoPuertoPaisDestino.replace(/ /g,'')).length == 0){
					
					cadena = "<tr id='"+codigoPuertoPaisDestino.replace(/ /g,'')+"'>"+
								"<td>"+$("#id_pais_destino option:selected").text()+
								"<input id='iPaisDestino' name='iPaisDestino[]' value='"+$("#id_pais_destino").val()+"' type='hidden'>"+
								"<input id='nPaisDestino' name='nPaisDestino[]' value='"+$("#id_pais_destino option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_puerto_pais_destino option:selected").text()+
								"<input id='iPuertoPaisDestino' name='iPuertoPaisDestino[]' value='"+$("#id_puerto_pais_destino").val()+"' type='hidden'>"+
								"<input id='nPuertoPaisDestino' name='nPuertoPaisDestino[]' value='"+$("#id_puerto_pais_destino option:selected").attr('data-nombrepuerto')+"' type='hidden'>"+
								"</td>"+
								"<td class='borrar'>"+
								"<button type='button' onclick='quitarPuertoPaisDestino("+codigoPuertoPaisDestino.replace(/ /g,'')+")' class='icono'></button>"+
								"</td>"+
							"</tr>"

					$("#detallePuertoPaisDestino tbody").append(cadena);
					limpiarDetalle('paisPuertosDestino');
				}else{
					mostrarMensaje("No puede ingresar dos registros iguales.", "FALLO");
				}
			}
        	
		}else{
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
    });

	//Funcion que quita una fila de la tabla puertos de destino
    function quitarPuertoPaisDestino(fila){
		$("#detallePuertoPaisDestino tbody tr").eq($(fila).index()).remove();		 
		
		if($('#detallePuertoPaisDestino tbody tr').length == 0) {	
		   	$('#id_pais_destino option:not(:selected)').attr('disabled',false);
			$("#detalleExportadoresProductos > tbody").empty();
			limpiarDetalle('exportadoresProductos');
		}		
	}

	//////////////////////////////////////
	////////PUERTOS DE TRANSITO///////////
	

	//Funcion para agregar fila de detalle de paises y puertos de transito
    $("#agregarPaisPuertoTransito").click(function(event) {
    	event.preventDefault();
       	mostrarMensaje("", "");
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if($("#id_pais_transito").val() == ""){
			error = true;
			$("#id_pais_transito").addClass("alertaCombo");
		}
    	
    	if($("#id_puerto_transito").val() == ""){
			error = true;
			$("#id_puerto_transito").addClass("alertaCombo");
		}

    	if($("#id_medio_transporte_transito").val() == ""){
			error = true;
			$("#id_medio_transporte_transito").addClass("alertaCombo");
		}

        if(!error){

			$("#estado").html("").removeClass('alerta');

			if($("#id_pais_transito").val() != "" && $("#id_puerto_transito").val() != ""){
				
				var codigoPaisPuertoTransito = 'r_' + $("#id_pais_transito").val() + $("#id_puerto_transito").val();
				var cadena = '';

				//Valida que no exista en la tabla
				if($("#detallePaisPuertoTransito tbody #"+codigoPaisPuertoTransito.replace(/ /g,'')).length == 0){
					
					cadena = "<tr id='"+codigoPaisPuertoTransito.replace(/ /g,'')+"'>"+
								"<td>"+$("#id_pais_transito option:selected").text()+
								"<input id='iPaisTransito' name='iPaisTransito[]' value='"+$("#id_pais_transito").val()+"' type='hidden'>"+
								"<input id='nPaisTransito' name='nPaisTransito[]' value='"+$("#id_pais_transito option:selected").text()+"' type='hidden'>"+
								"</td>"+								
								"<td>"+$("#id_puerto_transito option:selected").text()+
								"<input id='iPuertoTransito' name='iPuertoTransito[]' value='"+$("#id_puerto_transito").val()+"' type='hidden'>"+
								"<input id='nPuertoTransito' name='nPuertoTransito[]' value='"+$("#id_puerto_transito option:selected").attr('data-nombrepuerto')+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_medio_transporte_transito option:selected").text()+
								"<input id='iMedioTransporteTransito' name='iMedioTransporteTransito[]' value='"+$("#id_medio_transporte_transito").val()+"' type='hidden'>"+
								"<input id='nMedioTransporteTransito' name='nMedioTransporteTransito[]' value='"+$("#id_medio_transporte_transito option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td class='borrar'>"+
								"<button type='button' onclick='quitarPaisPuertoTransito("+codigoPaisPuertoTransito.replace(/ /g,'')+")' class='icono'></button>"+
								"</td>"+
							"</tr>"

					$("#detallePaisPuertoTransito tbody").append(cadena);
					limpiarDetalle('paisPuertosTransito');
				}else{
					//$("#estado").html("No puede ingresar dos registros iguales.").addClass('alerta');
					mostrarMensaje("No puede ingresar dos registros iguales.", "FALLO");
				}
			}
        	
		}else{
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
    });

	//Funcion que quita una fila de la tabla paises puertos transito
    function quitarPaisPuertoTransito(fila){
		$("#detallePaisPuertoTransito tbody tr").eq($(fila).index()).remove();
	}

	/////////////////////////////////////////
	////////EXPORTADORES PRODUCTOS///////////	
		
	//Función para verificar que el código POA pertenece al operador exportador
	function fn_verificarCodigoPoaExportador(identificadorExportador, codigoPoa) {
		
		$("#estado").html("").removeClass('alerta');

        $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/verificarCodigoPoaPorExportador",
        {
        	identificadorExportador : identificadorExportador,
        	codigoPoa : codigoPoa
        }, function (data) {
        	if(data.validacion == "Fallo"){
        		$("#certificacion_organica").val("");
        		mostrarMensaje(data.mensaje, "FALLO");		     		
        	}
        }, 'json');
	}			
		
	//----Agregar exportadores productos----//	
    $("#agregarExportadoresProductos").click(function(event) {
    	event.preventDefault();
       	mostrarMensaje("", "");
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;
    	var banderaAgregarProducto = true;

    	if(!$.trim($("#identificador_exportador").val())){
			error = true;
			$("#identificador_exportador").addClass("alertaCombo");
		}
    
    	if(!$.trim($("#id_tipo_producto").val())){
			error = true;
			$("#id_tipo_producto").addClass("alertaCombo");
		}

    	if(!$.trim($("#id_subtipo_producto").val())){
			error = true;
			$("#id_subtipo_producto").addClass("alertaCombo");
		}
		
    	if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}

    	if($("#producto_organico option:selected").val() == "Si"){
        	if(!$.trim($("#certificacion_organica").val())){
    			error = true;
    			$("#certificacion_organica").addClass("alertaCombo");
    		}
    	}

    	if(!$.trim($("#cantidad_comercial").val()) || $("#cantidad_comercial").val() <= 0){
			error = true;
			$("#cantidad_comercial").addClass("alertaCombo");
		}

		if($("#tipo_certificado option:selected").val() == "musaceas"){
    		if(!$.trim($("#peso_bruto").val()) || $("#peso_bruto").val() <= 0){
    			error = true;
    			$("#peso_bruto").addClass("alertaCombo");
	        }
		}

    	if($("#tipo_certificado").val() == "otros"){
    		if(!$.trim($("#fecha_inspeccion").val())){
    			error = true;
    			$("#fecha_inspeccion").addClass("alertaCombo");
    		}
    		if(!$.trim($("#hora_inspeccion").val())){
    			error = true;
    			$("#hora_inspeccion").addClass("alertaCombo");
    		}
        }

    	if(!$.trim($("#peso_neto").val()) || $("#peso_neto").val() <= 0){
			error = true;
			$("#peso_neto").addClass("alertaCombo");
		}

    	if(!$.trim($("#id_unidad_cantidad_comercial").val())){
			error = true;
			$("#id_unidad_cantidad_comercial").addClass("alertaCombo");
		}

		if($("#tipo_certificado").val() == "otros" || $("#tipo_certificado").val() == "ornamentales"){
				
        	if(!$.trim($("#tipo_centro_acopio").val())){
    			error = true;
    			$("#tipo_centro_acopio").addClass("alertaCombo");
    		}
    
        	if(!$.trim($("#identificador_centro_acopio").val())){
    			error = true;
    			$("#identificador_centro_acopio").addClass("alertaCombo");
    		}
    
        	if(!$.trim($("#codigo_centro_acopio").val())){
    			error = true;
    			$("#codigo_centro_acopio").addClass("alertaCombo");
    		}

		}

    	if($.trim($("#duracion_tratamiento").val())){			
			if ($("#id_unidad_duracion").val() == ""){
				error = true;
				$("#id_unidad_duracion").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#id_tratamiento").val())){			
			if ($("#fecha_tratamiento").val() == ""){
				error = true;
				$("#fecha_tratamiento").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#temperatura_tratamiento").val())){			
			if ($("#id_unidad_temperatura").val() == ""){
				error = true;
				$("#id_unidad_temperatura").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#fecha_tratamiento").val())){			
			if ($("#id_tratamiento").val() == ""){
				error = true;
				$("#id_tratamiento").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#concentracion_tratamiento").val())){			
			if ($("#id_unidad_concentracion").val() == ""){
				error = true;
				$("#id_unidad_concentracion").addClass("alertaCombo");				
			}			
		}

		if($("#tipo_certificado option:selected").val() == "ornamentales"){    	
    		if($("#id_pais_destino option:selected").attr("data-codigoPais") == "PA" || $("#id_pais_destino option:selected").attr("data-codigoPais") == "RU"){
    			if($("#detalleExportadoresProductos tbody tr").length >= 1){
    				banderaAgregarProducto = false;
    	        }
    		}
		}

        if(!error){

			$("#estado").html("").removeClass('alerta');			
			
			if(banderaAgregarProducto){

    			if($("#identificador_exportador").val() != "" && $("#razon_social_exportador").val() != "" && $("#id_producto").val() != "" && $("#cantidad_comercial").val() != "" && $("#peso_neto").val() != ""){

    				var area = "";

    				($("#id_producto option:selected").attr("data-clasificacion") != "musaceas") ? area = $("#codigo_centro_acopio option:selected").attr("data-idArea") : area;
    				
    				var codigoExportadoresProductos = 'r_' + $("#identificador_exportador").val() + $("#id_producto").val() + area;
    				var cadena = '';
    
    				//Valida que no exista en la tabla
    				if($("#detalleExportadoresProductos tbody #"+codigoExportadoresProductos.replace(/ /g,'')).length == 0){
    
    					cadena = "<tr id='"+codigoExportadoresProductos.replace(/ /g,'')+"'>"+
    								"<td>"+$("#identificador_exportador").val()+
    								"<input name='iIdentificadorExportador[]' value='"+$("#identificador_exportador").val()+"' type='hidden'>"+
    								"</td>"+
    								"<td>"+$("#razon_social_exportador").val()+
    								"<input name='iRazonSocialExportador[]' value='"+$("#razon_social_exportador").val()+"' type='hidden'>"+
    								"</td>"+
    								"<td>"+$("#id_producto option:selected").attr("data-nombreProducto")+
    								"<input name='iProducto[]' value='"+$("#id_producto").val()+"' type='hidden'>"+
    								"<input name='nProducto[]' value='"+$("#id_producto option:selected").attr("data-nombreProducto")+"' type='hidden'>"+
    								"<input name='dClasificacionProducto[]' value='"+$("#id_producto option:selected").attr("data-clasificacion")+"' type='hidden'>"+
    								"</td>"+
    								"<td>";
									if($("#certificacion_organica").val() != ""){
										cadena += $("#certificacion_organica").val()+"<input name='iCertificacionOrganica[]' value='"+$("#certificacion_organica").val()+"' type='hidden'>"
									}else{
										cadena += "N/A";
									} 								
    								cadena += "</td>"+
    								"<td>"+
    								"<input name='iCantidadComercial[]' value='"+$("#cantidad_comercial").val()+"' type='text' size='2'>"+
    								"<input name='iUnidadCantidadComercial[]' value='"+$("#id_unidad_cantidad_comercial").val()+"' type='hidden'>"+
    								"<input name='nUnidadCantidadComercial[]' value='"+$("#id_unidad_cantidad_comercial option:selected").text()+"' type='hidden'>"+
    								"</td>"+
    								"<td>";
    								if($("#tipo_certificado option:selected").val() == "musaceas"){
    									cadena += "<input id='iPesoBruto' name='iPesoBruto[]' value='"+$("#peso_bruto").val()+"' type='text' size='2'>"+
    									"<input name='iUnidadPesoBruto[]' value='"+$("#id_unidad_peso_bruto").val()+"' type='hidden'>"+
        								"<input name='nUnidadPesoBruto[]' value='"+$("#id_unidad_peso_bruto option:selected").text()+"' type='hidden'>";
    								}else{
    									cadena += "N/A";
    								}
    								cadena += "</td>"+
    								"<td>"+
    								"<input name='iPesoNeto[]' value='"+$("#peso_neto").val()+"' type='text' size='2'>"+
    								"<input name='iUnidadPesoNeto[]' value='"+$("#id_unidad_peso_neto").val()+"' type='hidden'>"+
    								"<input name='nUnidadPesoNeto[]' value='"+$("#id_unidad_peso_neto option:selected").text()+"' type='hidden'>"+
    								"</td>"+
    								"<td>";
    								if($("#tipo_certificado").val() == "otros"){
    						            	cadena += $("#fecha_inspeccion").val()+ " "+$("#hora_inspeccion").val();
						            }else{
						            	cadena += "N/A";
							        }
    								 cadena += "</td>"+
    								"<td class='borrar'>"+
    								"<button type='button' onclick='quitarExportadoresProductos("+codigoExportadoresProductos.replace(/ /g,'')+")' class='icono'></button>"+
    								"<input name='iDireccionExportador[]' value='"+$("#direccion_exportador").val()+"' type='hidden'>"+
    								"<input name='iTipoProducto[]' value='"+$("#id_tipo_producto").val()+"' type='hidden'>"+
    								"<input name='nTipoProducto[]' value='"+$("#id_tipo_producto option:selected").text()+"' type='hidden'>"+
    								"<input name='iSubtipoProducto[]' value='"+$("#id_subtipo_producto").val()+"' type='hidden'>"+
    					            "<input name='nSubtipoProducto[]' value='"+$("#id_subtipo_producto option:selected").text()+"' type='hidden'>"+
    					            "<input name='iPartidaArancelariaProducto[]' value='"+$("#partida_arancelaria_producto").val()+"' type='hidden'>"+
    					            "<input name='iCodigoCentroAcopio[]' value='"+$("#codigo_centro_acopio").val()+"' type='hidden'>"+
    					            "<input name='iFechaInspeccion[]' value='"+$("#fecha_inspeccion").val()+"' type='hidden'>"+
    					            "<input name='iHoraInspeccion[]' value='"+$("#hora_inspeccion").val()+"' type='hidden'>"+
    					            "<input name='iTipoTratamiento[]' value='"+$("#id_tipo_tratamiento").val()+"' type='hidden'>"+
    					            "<input name='nTipoTratamiento[]' value='"+$("#id_tipo_tratamiento option:selected").text()+"' type='hidden'>"+
    					            "<input name='iTratamiento[]' value='"+$("#id_tratamiento").val()+"' type='hidden'>"+
    					            "<input name='nTratamiento[]' value='"+$("#id_tratamiento option:selected").text()+"' type='hidden'>"+
    					            "<input name='iDuracionTratamiento[]' value='"+$("#duracion_tratamiento").val()+"' type='hidden'>"+
    					            "<input name='iUnidadDuracion[]' value='"+$("#id_unidad_duracion").val()+"' type='hidden'>"+
    					            "<input name='nUnidadDuracion[]' value='"+$("#id_unidad_duracion option:selected").text()+"' type='hidden'>"+
    					            "<input name='iTemperaturaTratamiento[]' value='"+$("#temperatura_tratamiento").val()+"' type='hidden'>"+
    					            "<input name='iUnidadTemperatura[]' value='"+$("#id_unidad_temperatura").val()+"' type='hidden'>"+
    					            "<input name='nUnidadTemperatura[]' value='"+$("#id_unidad_temperatura option:selected").text()+"' type='hidden'>"+
    					            "<input name='iFechaTratamiento[]' value='"+$("#fecha_tratamiento").val()+"' type='hidden'>"+
    					            "<input name='iProductoQuimico[]' value='"+$("#producto_quimico").val()+"' type='hidden'>"+
    					            "<input name='iConcentracionTratamiento[]' value='"+$("#concentracion_tratamiento").val()+"' type='hidden'>"+
    					            "<input name='iUnidadConcentracion[]' value='"+$("#id_unidad_concentracion").val()+"' type='hidden'>"+
    					            "<input name='nUnidadConcentracion[]' value='"+$("#id_unidad_concentracion option:selected").text()+"' type='hidden'>"+
    					            "<input name='iArea[]' value='"+$("#codigo_centro_acopio option:selected").attr("data-idArea")+"' type='hidden'>"+
    					            "<input name='nArea[]' value='"+$("#codigo_centro_acopio option:selected").attr("data-nombreArea")+"' type='hidden'>"+
    					            "<input name='iProvinciaArea[]' value='"+$("#codigo_centro_acopio option:selected").attr("data-idProvinciaArea")+"' type='hidden'>"+
    					            "<input name='nProvinciaArea[]' value='"+$("#codigo_centro_acopio option:selected").attr("data-nombreProvinciaArea")+"' type='hidden'>"+
    								"</td>"+
    							+"</tr>";
    
    					$("#detalleExportadoresProductos tbody").append(cadena);

    					limpiarDetalle('exportadoresProductos');
    					fn_mostrarOcultarInformacion();
    				}else{
    					mostrarMensaje("El exportador y producto ya han sido agregados.", "FALLO");
    				}
    			}
			}else{
				mostrarMensaje("No se puede agregar mas de un exportador y producto al destino " + $("#id_pais_destino option:selected").text() + ".", "FALLO");
			}
        	
		}else{
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
    });

    //Funcion que quita una fila de la tabla exportadores productos
    function quitarExportadoresProductos(fila){
		$("#detalleExportadoresProductos tbody tr").eq($(fila).index()).remove();
	}

	////////////////////////////////////////
	////////FUNCIONES ADICIONALES///////////   

	//Funcion que limpia detalles de las tablas
    function limpiarDetalle(valor){

        switch(valor){

        case "paisPuertosDestino":
        	$("#id_puerto_pais_destino").val("");
        break;

        case "paisPuertosTransito":
        	$("#id_pais_transito").val("");
        	$("#id_puerto_transito").val("");
        	$("#id_medio_transporte_transito").val("");
        break; 

        case "paisPuertosTransito":
        	$("#id_pais_transito").val("");
        	$("#id_puerto_transito").val("");
        	$("#id_medio_transporte_transito").val("");
        break; 

        case "exportadoresProductos":
        	$("#identificador_exportador").val("");
        	$("#razon_social_exportador").val("");
        	$("#direccion_exportador").val("");
        	$("#id_tipo_producto").val("");
        	$("#id_subtipo_producto").val("");
        	$("#id_producto").val("");
        	$("#certificacion_organica").val("");
        	$("#partida_arancelaria_producto").val("");
        	$("#cantidad_comercial").val("");
        	$("#id_unidad_cantidad_comercial").val("");
        	$("#peso_bruto").val("");
        	$("#peso_neto").val("");
        	$("#codigo_centro_acopio").val("");
        	$("#fecha_inspeccion").val("");
        	$("#hora_inspeccion").val("");
        	$("#id_tipo_tratamiento").val("");
        	$("#id_tratamiento").val("");
        	$("#duracion_tratamiento").val("");
        	$("#id_unidad_duracion").val("");
        	$("#temperatura_tratamiento").val("");
        	$("#id_unidad_temperatura").val("");
        	$("#fecha_tratamiento").val("");
        	$("#producto_quimico").val("");
        	$("#id_unidad_concentracion").val("");
        	$("#tipo_centro_acopio").val("");
        	$("#identificador_centro_acopio").val("");
        	$("#concentracion_tratamiento").val("");
        	$("#id_unidad_concentracion").val("");
        break;      
        
        }

	}

    //Funcion que agrega elementos a un array//
    //Recibe array, datos del array y el objeto donde se almacena//
    function agregarElementos(array, datos, objeto){
    	array.push(datos);
    	objeto.val(JSON.stringify(array));
	}

    //Funcion para cargar puertos de provincia o de pais
    function fn_cargarPuertos(tipoValor, objeto, idLocalizacion) {                
    
    	if (idLocalizacion !== ""){    
            $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarPuertosPorIdPais",
                {
                 idLocalizacion : idLocalizacion,
                 tipoValor : tipoValor
                }, function (data) {
                objeto.html(data);               
            });
        } 
    
    }

  //Funcion para cargar puertos por medio de trasporte
    function fn_cargarPuertosPorMedioTransporte(objeto, idLocalizacion, nombreMedioTrasporte) {      
                   		      
    	if (idLocalizacion !== ""){    
            $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarPuertosPorNombreMedioTransporte",
                {
                 idLocalizacion : idLocalizacion,
                 nombreMedioTrasporte : nombreMedioTrasporte
                }, function (data) {
                objeto.html(data);               
            });
        } 
    
    }

    //Funcion para obtener la informacion del operador por cedua/RUC
    function fn_obtenerDatosOperador(identificadorExportador) {  

    	$("#estado").html("").removeClass('alerta');
        
    	if (identificadorExportador !== ""){    
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarDatosOperadorPorIdentificador",
    	                {
    			 		identificadorExportador : identificadorExportador
    	                }, function (data) {
    	    				if(data.validacion == "Fallo"){
    	    	        		mostrarMensaje(data.mensaje, "FALLO");
    	    	        		fn_cargarDatosOperador(data);	        		
    	    				}else{
    	    					fn_cargarDatosOperador(data);
    	    					fn_obtenerTiposProductoOperadorPorTipoSolicitud($("#identificador_exportador").val(), $("#tipo_certificado").val());
    	    				}
    	                }, 'json');
    	}    

    }

  	//Función para mostrar los datos obtenidos del operador
    function fn_cargarDatosOperador(data) {
        
    	if(data.validacion == "Fallo"){
    		$("#identificador_exportador").val("");
			$("#razon_social_exportador").val("");
			$("#direccion_exportador").val("");
		}else{
			$("#razon_social_exportador").val(data.nombreOperador);
			$("#direccion_exportador").val(data.direccionOperador);
		}
		
    } 

  	//Funcion para obtener los datos de ecuador
    function fn_cargarDatosPaisOrigen() {  
 
    	 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarDatosLocalizacionEcuador",
    			 {
    	 		//identificadorExportador : identificadorExportador
                }, function (data) {
                	fn_cargarPaisOrigen(data);	    			
                }, 'json');

	}

  	//Función para mostrar los datos obtenidos del operador
    function fn_cargarPaisOrigen(data) {

		$("#id_pais_origen").val(data.idLocalizacion);
		$("#nombre_pais_origen").val(data.nombreLocalizacion);
		
    } 

  	//Función para mostrar los datos obtenidos del operador
    function fn_cargarDatosOperador(data) {
        
    	if(data.validacion == "Fallo"){
    		$("#identificador_exportador").val("");
			$("#razon_social_exportador").val("");
			$("#direccion_exportador").val("");
		}else{
			$("#razon_social_exportador").val(data.nombreOperador);
			$("#direccion_exportador").val(data.direccionOperador);
		}
		
    } 

    //Funcion para mostrar y ocultar informacion    
    function fn_mostrarOcultarInformacion(valor, estado){

    	$("#cantidad_comercial").val("");
    	$("#id_unidad_cantidad_comercial").val("");
    	$("#peso_neto").val("");
    	$("#peso_bruto").val("");
    	$("#tipo_centro_acopio").val("");
    	$("#identificador_centro_acopio").val("");
    	$("#codigo_centro_acopio").val("");
    	$("#fecha_inspeccion").val("");
    	$("#hora_inspeccion").val("");

		switch(valor){
			case "otros":				
				$("#cTipoCentroAcopio").show();
				$("#buscarCentroAcopio").show();
				$("#hCentroAcopio").show();
				$("#centroAcopio").show();
	    		$("#fechaInspeccion").show();
	    		$("#horaInspeccion").show();
	    		if(estado){
		    		$("#pesoBruto").show();
		    		$("#idUnidadPesoBruto").show();
	    		}else{
    				$("#pesoBruto").hide();
    				$("#idUnidadPesoBruto").hide();
	    		}
				
			break;
			case "ornamentales":
				$("#cTipoCentroAcopio").show();
				$("#buscarCentroAcopio").show();
				$("#hCentroAcopio").show();
				$("#centroAcopio").show();
	    		if(estado){
		    		$("#pesoBruto").show();
		    		$("#idUnidadPesoBruto").show();
	    		}else{
    				$("#pesoBruto").hide();
    				$("#idUnidadPesoBruto").hide();
	    		};
			break;
			case "musaceas":
				$("#pesoBruto").show();
				$("#idUnidadPesoBruto").show();
			break;
			default:				
				$("#cTipoCentroAcopio").hide();
				$("#buscarCentroAcopio").hide();
				$("#hCentroAcopio").hide();
				$("#centroAcopio").hide();
	    		$("#fechaInspeccion").hide();
	    		$("#horaInspeccion").hide();
	    		$("#pesoBruto").hide();
				$("#idUnidadPesoBruto").hide();
			break;
		}

		distribuirLineas();
		
    }

	//Funcion para obtener los tipos denproductos registrados del exportador
    function fn_obtenerTiposProductoOperadorPorTipoSolicitud(identificadorExportador, tipoSolicitud) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (identificadorExportador !== "" && tipoSolicitud != ""){ //TODO:Descomentar esta seccion cuando se valide el tipo   
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarTipoProductoPorOperadorPorTipoSolicitud",
    	                {
        			 		identificadorExportador : identificadorExportador,
        			 		tipoSolicitud : tipoSolicitud
    	                }, function (data) {    
    	                	if(data.validacion != 'Exito'){
    	                		mostrarMensaje(data.mensaje, "FALLO");
    		                }
    		                $("#id_tipo_producto").html(data.resultado);
    			                  
    	                }, 'json');
    	}  

    }

	//Funcion para obtener los subtipos de productos registrados del exportador por tipo y tipo de solicitud
    function fn_obtenerSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud(identificadorExportador, idTipoProducto, tipoSolicitud) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idTipoProducto !== "" && tipoSolicitud != ""){ //TODO:Descomentar esta seccion cuando se valide el tipo       
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud",
    	                {
        			 		identificadorExportador : identificadorExportador,
        			 		idTipoProducto : idTipoProducto,
        			 		tipoSolicitud : tipoSolicitud
    	                }, function (data) {    	                    
    	                    $("#id_subtipo_producto").html(data);               
    	                });
    	}  

    }

    //Funcion para obtener los productos registrados del exportador por subtipo y tipo de solicitud
    function fn_obtenerProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais(identificadorExportador, idSubtipoProducto, tipoSolicitud, idPaisDestino) {  
		
    	$("#estado").html("").removeClass('alerta');
    	
    	if (idSubtipoProducto !== "" && tipoSolicitud != ""){
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais",
    	                {
        			 		identificadorExportador : identificadorExportador,
        			 		idSubtipoProducto : idSubtipoProducto,
        			 		tipoSolicitud : tipoSolicitud,
        			 		idLocalizacion : idPaisDestino
    	                }, function (data) {    
    	                    if(data.validacion != 'Exito'){
    	                    	mostrarMensaje(data.mensaje, "FALLO");
    		                }
    		                $("#id_producto").html(data.resultado);    		                       
    	                }, 'json');
    	}  

    }
 
	//Funcion para validar el producto y area registrada//	
	function fn_obtenerCentroAcopioExportadorProducto() {
		
    	$("#estado").html("").removeClass('alerta');

    	var tipoCentroAcopio = $("#tipo_centro_acopio").val();
    	var identificadorExportador = $("#identificador_exportador").val();
		var identificadorProveedor = $("#identificador_centro_acopio").val();
 		var idProducto = $("#id_producto").val();
	 	var idPaisDestino = $("#id_pais_destino").val();
	 	var tipoSolicitud = $("#tipo_certificado").val();

    	if (idProducto !== ""){    
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarCentroAcopioExportadorProducto",
    	                {
        			 		tipoCentroAcopio :tipoCentroAcopio,
    						identificadorExportador : identificadorExportador,
    						identificadorProveedor : identificadorProveedor,
        			 		idProducto : idProducto,
            			 	idLocalizacion : idPaisDestino,
            			 	tipoSolicitud : tipoSolicitud    			 		
    	                }, function (data) {    
    	                    if(data.validacion != 'Exito'){
    	                    	mostrarMensaje(data.mensaje, "FALLO");
    		                }
    	                    $("#codigo_centro_acopio").html(data.resultado);    		                       
    	                }, 'json');
    	}  

    }
    
	//Accion para cargar archivo adjunto
	$("#subirArchivo").click(function (event) {
	  	  
	  	var boton = $(this);
		var nombre_archivo = "<?php echo 'certificadofitosantario_' . (md5(time())); ?>";
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".ruta_adjunto");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	
	        subirArchivo(
	            archivo
	            , nombre_archivo
	            , boton.attr("data-rutaCarga")
	            , rutaArchivo
	            , new carga(estado, archivo, $("#no"))
	            
	        );
	    } else {
	        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	        archivo.val("0");        
	        }
	});

	$("#fecha_tratamiento").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	});

	function cargarDatosDetalle(){

		var arrayPaisPuertosDestino = [];
		var arrayPaisPuertosTransito = [];
		var arrayExportadoresProductos = [];
    	
		datosPuertoPaisDestino = [];

		$('#detallePuertoPaisDestino tbody tr').each(function (rows) {				

			var idPaisDestino = $(this).find('td').find('input[name="iPaisDestino[]"]').val();			
			var nombrePaisDestino = $(this).find('td').find('input[name="nPaisDestino[]"]').val();			
			var idPuertoPaisDestino = $(this).find('td').find('input[name="iPuertoPaisDestino[]"]').val();
			var nombrePuertoPaisDestino = $(this).find('td').find('input[name="nPuertoPaisDestino[]"]').val();

			if ($('#detallePuertoPaisDestino tbody tr').length){		
				
				datosPuertoPaisDestino = {"idPuertoPaisDestino":idPuertoPaisDestino, "nombrePuertoPaisDestino":nombrePuertoPaisDestino};
				agregarElementos(arrayPaisPuertosDestino, datosPuertoPaisDestino, $("#array_pais_puertos_destino"));
				
			}

		});
		
		datosPaisPuertoTransito = [];
	    
		$('#detallePaisPuertoTransito tbody tr').each(function (rows) {				

			var idPaisTransito = $(this).find('td').find('input[name="iPaisTransito[]"]').val();			
			var nombrePaisTransito = $(this).find('td').find('input[name="nPaisTransito[]"]').val();			
			var idMedioTransporteTransito = $(this).find('td').find('input[name="iMedioTransporteTransito[]"]').val();
			var nombreMedioTransporteTransito = $(this).find('td').find('input[name="nMedioTransporteTransito[]"]').val();			
			var idPuertoTransito = $(this).find('td').find('input[name="iPuertoTransito[]"]').val();
			var nombrePuertoTransito = $(this).find('td').find('input[name="nPuertoTransito[]"]').val();

			if ($('#detallePaisPuertoTransito tbody tr').length){		
				
				datosPaisPuertoTransito = {"idPaisTransito":idPaisTransito, "nombrePaisTransito":nombrePaisTransito,
				"idMedioTransporteTransito":idMedioTransporteTransito, "nombreMedioTransporteTransito":nombreMedioTransporteTransito,
				"idPuertoTransito":idPuertoTransito, "nombrePuertoTransito":nombrePuertoTransito};
				agregarElementos(arrayPaisPuertosTransito, datosPaisPuertoTransito, $("#array_pais_puertos_transito"));
				
			}

		});

		datosExportadoresProductos = [];
	    
		$('#detalleExportadoresProductos tbody tr').each(function (rows) {				

			var identificadorExportador = $(this).find('td').find('input[name="iIdentificadorExportador[]"]').val();			
			var razonSocialExportador = $(this).find('td').find('input[name="iRazonSocialExportador[]"]').val();			
			var direccionExportador = $(this).find('td').find('input[name="iDireccionExportador[]"]').val();
			var idTipoProducto = $(this).find('td').find('input[name="iTipoProducto[]"]').val();			
			var nombreTipoProducto = $(this).find('td').find('input[name="nTipoProducto[]"]').val();
			var idSubtipoProducto = $(this).find('td').find('input[name="iSubtipoProducto[]"]').val();
			var nombreSubtipoProducto = $(this).find('td').find('input[name="nSubtipoProducto[]"]').val();			
			var idProducto = $(this).find('td').find('input[name="iProducto[]"]').val();			
			var nombreProducto = $(this).find('td').find('input[name="nProducto[]"]').val();
			var partidaArancelariaProducto = $(this).find('td').find('input[name="iPartidaArancelariaProducto[]"]').val();
			var certificacionOrganica = $(this).find('td').find('input[name="iCertificacionOrganica[]"]').val();		
			var cantidadComercial = $(this).find('td').find('input[name="iCantidadComercial[]"]').val();
			var idUnidadCantidadComercial = $(this).find('td').find('input[name="iUnidadCantidadComercial[]"]').val();
			var nombreUnidadCantidadComercial = $(this).find('td').find('input[name="nUnidadCantidadComercial[]"]').val();
			var pesoBruto = $(this).find('td').find('input[name="iPesoBruto[]"]').val();
			var idUnidadPesoBruto = $(this).find('td').find('input[name="iUnidadPesoBruto[]"]').val();
			var nombreUnidadPesoBruto = $(this).find('td').find('input[name="nUnidadPesoBruto[]"]').val();			
			var pesoNeto = $(this).find('td').find('input[name="iPesoNeto[]"]').val();			
			var idUnidadPesoNeto = $(this).find('td').find('input[name="iUnidadPesoNeto[]"]').val();
			var nombreUnidadPesoNeto = $(this).find('td').find('input[name="nUnidadPesoNeto[]"]').val();	
			var codigoCentroAcopio = $(this).find('td').find('input[name="iCodigoCentroAcopio[]"]').val();			
			var fechaInspeccion = $(this).find('td').find('input[name="iFechaInspeccion[]"]').val();
			var horaInspeccion = $(this).find('td').find('input[name="iHoraInspeccion[]"]').val();
			var idTipoTratamiento = $(this).find('td').find('input[name="iTipoTratamiento[]"]').val();
			var nombreTipoTratamiento = $(this).find('td').find('input[name="nTipoTratamiento[]"]').val();			
			var idTratamiento = $(this).find('td').find('input[name="iTratamiento[]"]').val();
			var nombreTratamiento = $(this).find('td').find('input[name="nTratamiento[]"]').val();			
			var duracionTratamiento = $(this).find('td').find('input[name="iDuracionTratamiento[]"]').val();
			var idUnidadDuracion = $(this).find('td').find('input[name="iUnidadDuracion[]"]').val();
			var nombreUnidadDuracion = $(this).find('td').find('input[name="nUnidadDuracion[]"]').val();			
			var temperaturaTratamiento = $(this).find('td').find('input[name="iTemperaturaTratamiento[]"]').val();
			var idUnidadTemperatura = $(this).find('td').find('input[name="iUnidadTemperatura[]"]').val();
			var nombreUnidadTemperatura = $(this).find('td').find('input[name="nUnidadTemperatura[]"]').val();
			var fechaTratamiento = $(this).find('td').find('input[name="iFechaTratamiento[]"]').val();			
			var productoQuimico = $(this).find('td').find('input[name="iProductoQuimico[]"]').val();
			var concentracionTratamiento = $(this).find('td').find('input[name="iConcentracionTratamiento[]"]').val();
			var idUnidadConcentracion = $(this).find('td').find('input[name="iUnidadConcentracion[]"]').val();
			var nombreUnidadConcentracion = $(this).find('td').find('input[name="nUnidadConcentracion[]"]').val();
			var idArea = $(this).find('td').find('input[name="iArea[]"]').val();
			var nombreArea = $(this).find('td').find('input[name="nArea[]"]').val();
			var idProvinciaArea = $(this).find('td').find('input[name="iProvinciaArea[]"]').val();
			var nombreProvinciaArea = $(this).find('td').find('input[name="nProvinciaArea[]"]').val();
			
			if ($('#detalleExportadoresProductos tbody tr').length){		
				
				datosExportadoresProductos = {"identificadorExportador":identificadorExportador, "razonSocialExportador":razonSocialExportador,
						"direccionExportador":direccionExportador, "idTipoProducto":idTipoProducto, "nombreTipoProducto":nombreTipoProducto,
						"idSubtipoProducto":idSubtipoProducto, "nombreSubtipoProducto":nombreSubtipoProducto,
						"idProducto":idProducto, "nombreProducto":nombreProducto, "partidaArancelariaProducto":partidaArancelariaProducto,
						"certificacionOrganica":certificacionOrganica,
						"cantidadComercial":cantidadComercial, "idUnidadCantidadComercial":idUnidadCantidadComercial, "nombreUnidadCantidadComercial":nombreUnidadCantidadComercial,
						"pesoBruto":pesoBruto, "idUnidadPesoBruto":idUnidadPesoBruto, "nombreUnidadPesoBruto":nombreUnidadPesoBruto,
						"pesoNeto":pesoNeto, "idUnidadPesoNeto":idUnidadPesoNeto, "nombreUnidadPesoNeto":nombreUnidadPesoNeto,
						"codigoCentroAcopio":codigoCentroAcopio, "fechaInspeccion":fechaInspeccion, "horaInspeccion":horaInspeccion,
						"idTipoTratamiento":idTipoTratamiento, "nombreTipoTratamiento":nombreTipoTratamiento, 
						"idTratamiento":idTratamiento, "nombreTratamiento":nombreTratamiento,
						"duracionTratamiento":duracionTratamiento, "idUnidadDuracion":idUnidadDuracion, "nombreUnidadDuracion":nombreUnidadDuracion,
						"temperaturaTratamiento":temperaturaTratamiento, "idUnidadTemperatura":idUnidadTemperatura, "nombreUnidadTemperatura":nombreUnidadTemperatura,
						"fechaTratamiento":fechaTratamiento, "productoQuimico":productoQuimico, 
						"concentracionTratamiento":concentracionTratamiento, "idUnidadConcentracion":idUnidadConcentracion, "nombreUnidadConcentracion":nombreUnidadConcentracion,
						"idArea":idArea, "nombreArea":nombreArea, "idProvinciaArea":idProvinciaArea, "nombreProvinciaArea":nombreProvinciaArea
						};
				agregarElementos(arrayExportadoresProductos, datosExportadoresProductos, $("#array_exportadores_productos"));
				
			}

		});
	
	}

  	//Funcion para obtener el catalogo de paises por idioma
    function fn_cargarPaisesPorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarPaisesPorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_pais_destino").html(data);
                $("#id_pais_transito").html(data);
                $("#id_pais_destino").prepend('<option value="" selected>Seleccionar...</option>');
                $("#id_pais_transito").prepend('<option value="" selected>Seleccionar...</option>');       
            });
    	}else{
   	 		$("#id_pais_destino").html('<option value="">Seleccionar...</option>');
   	 		$("#id_pais_transito").html('<option value="">Seleccionar...</option>');
       	}
    }  
    
    //Funcion para obtener el catalogo medios de trasporte por idioma
    function fn_cargarMediosTransportePorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarMediosTransportePorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_medio_transporte").html(data); 
                $("#id_medio_transporte_transito").html(data);  
                $("#id_medio_transporte").prepend('<option value="" selected>Seleccionar...</option>');
                $("#id_medio_transporte_transito").prepend('<option value="" selected>Seleccionar...</option>');            
            });
    	}else{
   	 		$("#id_medio_transporte").html('<option value="">Seleccionar...</option>');
       	}
    }

  	//Funcion para obtener el catalogo de unidades de medida por idioma
    function fn_cargarUnidadesMedidaPorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarUnidadesMedidaPorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_unidad_cantidad_comercial").html(data);  
                $("#id_unidad_cantidad_comercial").prepend('<option value="" selected>Seleccionar...</option>');            
            });
    	}else{
   	 		$("#id_medio_transporte").html('<option value="">Seleccionar...</option>');
       	}
    }

  //Funcion para obtener el catalogo de unidades de medida por codigo por idioma
    function fn_cargarUnidadesMedidaPorCodigoPorIdioma(codigoUnidadMedida, idioma) {
        
    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarUnidadesMedidaPorCodigoPorIdioma",
    	   	{
				codigoUnidadMedida: codigoUnidadMedida,
				idioma : idioma
            }, function (data) {
            	$("#id_unidad_peso_bruto").html(data);
            	$("#id_unidad_peso_neto").html(data);          
            });
    	}else{
   	 		$("#id_medio_transporte").html('<option value="">Seleccionar...</option>');
       	}       	
    }

  	//Funcion para obtener el catalogo de tipos de tratamiento por idioma
    function fn_cargarTiposTratamientoPorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarTiposTratamientoPorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_tipo_tratamiento").html(data);
                $("#id_tipo_tratamiento").prepend('<option value="" selected>Seleccionar...</option>');                
            });
    	}else{
   	 		$("#id_tipo_tratamiento").html('<option value="">Seleccionar...</option>');
       	}
    }   

  	//Funcion para obtener el catalogo de tratamientos por idioma
    function fn_cargarTratamientosPorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarTratamientosPorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_tratamiento").html(data);    
                $("#id_tratamiento").prepend('<option value="" selected>Seleccionar...</option>');          
            });
    	}else{
   	 		$("#id_tratamiento").html('<option value="">Seleccionar...</option>');
       	}
    }   
    
  	//Funcion para obtener el catalogo de unidades de duracion por idioma
    function fn_cargarUnidadesDuracionPorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarUnidadesDuracionPorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_unidad_duracion").html(data);
                $("#id_unidad_duracion").prepend('<option value="" selected>Seleccionar...</option>');                 
            });
    	}else{
   	 		$("#id_unidad_duracion").html('<option value="">Seleccionar...</option>');
       	}
    }  

  	//Funcion para obtener el catalogo de unidades de temperatura por idioma
    function fn_cargarUnidadesTemperaturaPorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarUnidadesTemperaturaPorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_unidad_temperatura").html(data);
                $("#id_unidad_temperatura").prepend('<option value="" selected>Seleccionar...</option>');              
            });
    	}else{
   	 		$("#id_unidad_temperatura").html('<option value="">Seleccionar...</option>');
       	}
    }      
    
  	//Funcion para obtener el catalogo de unidades de temperatura por idioma
    function fn_cargarConcentracionesTratamientoPorIdioma(idioma) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idioma !== ""){
    		$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarConcentracionesTratamientoPorIdioma",
    	   	{
				idioma : idioma
            }, function (data) {
                $("#id_unidad_concentracion").html(data);
                $("#id_unidad_concentracion").prepend('<option value="" selected>Seleccionar...</option>');              
            });
    	}else{
   	 		$("#id_unidad_concentracion").html('<option value="">Seleccionar...</option>');
       	}
    }  

	////////////////////////////////////////
	////////FUNCION DE GUARDADO///////////    
	
	$("#formulario").submit(function (event) {

		event.preventDefault();
		var error = false;
		var mensajeDetalle = "";
		var aClasificacionProducto = [];
		$(".alertaCombo").removeClass("alertaCombo");
		
		$('#detalleExportadoresProductos tbody tr').each(function (rows) {				

			var vCantidadComercial = $(this).find('td').find('input[name="iCantidadComercial[]"]').val();			
			var vPesoNeto = $(this).find('td').find('input[name="iPesoNeto[]"]').val();			
			var vPesoBruto = $(this).find('td').find('input[name="iPesoBruto[]"]').val();
			var dClasificacionProducto = $(this).find('td').find('input[name="dClasificacionProducto[]"]').val();

			aClasificacionProducto.push(dClasificacionProducto);
			
			if(vCantidadComercial <= 0){
				error = true;
				$(this).find('td').find('input[name="iCantidadComercial[]"]').addClass("alertaCombo");
			}

			if(vPesoNeto <= 0){
				error = true;
				$(this).find('td').find('input[name="iPesoNeto[]"]').addClass("alertaCombo");
			}

			if(vPesoBruto <= 0){
				error = true;
				$(this).find('td').find('input[name="iPesoBruto[]"]').addClass("alertaCombo");
			}

		});

		if($("#tipo_certificado").val() == "otros"){
    		aClasificacionProducto = $.unique(aClasificacionProducto);
    		
    		if(aClasificacionProducto.length == 1){
    			if(aClasificacionProducto[0] == "musaceas"){
    				error = true;
    				mensajeDetalle += " Una solicitud de tipo otros no debe contener solo productos de clasificación musáceas."
    			}
    		}
		}

		if(!$.trim($("#tipo_certificado").val())){
			error = true;
			$("#tipo_certificado").addClass("alertaCombo");
		}

		if(!$.trim($("#producto_organico").val())){
			error = true;
			$("#producto_organico").addClass("alertaCombo");
		}

		if(!$.trim($("#id_idioma").val())){
			error = true;
			$("#id_idioma").addClass("alertaCombo");
		}		

		if(!$.trim($("#id_provincia_origen").val())){
			error = true;
			$("#id_provincia_origen").addClass("alertaCombo");
		}        		

		if(!$.trim($("#id_medio_transporte").val())){
			error = true;
			$("#id_medio_transporte").addClass("alertaCombo");
		}

		if(!$.trim($("#id_puerto_embarque").val())){
			error = true;
			$("#id_puerto_embarque").addClass("alertaCombo");
		}

		if(!$.trim($("#fecha_embarque").val())){
			error = true;
			$("#fecha_embarque").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombre_marca").val())){
			error = true;
			$("#nombre_marca").addClass("alertaCombo");
		}

		if(!$.trim($("#nombre_consignatario").val())){
			error = true;
			$("#nombre_consignatario").addClass("alertaCombo");
		}

		if(!$.trim($("#direccion_consignatario").val())){
			error = true;
			$("#direccion_consignatario").addClass("alertaCombo");
		}

        if($("#detallePuertoPaisDestino tbody tr").length == 0){
        	error = true;
        	mensajeDetalle += " Debe seleccionar un país de destino.";       	
        }

        if($("#detalleExportadoresProductos tbody tr").length == 0){
        	error = true;
        	mensajeDetalle += " Debe seleccionar un exportador y producto.";
        }

        if($.trim($("#ruta_enlace_adjunto").val())){
            if(!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i).test($("#ruta_enlace_adjunto").val())){
                error = true;
                mensajeDetalle += " Debe registrar una url válida.";
            }
        }

        if(!$.trim($("#forma_pago").val())){
			error = true;
			$("#forma_pago").addClass("alertaCombo");
		}

        if($("#forma_pago").val() == "efectivo"){
            if(!$.trim($("#descuento").val())){
    			error = true;
    			$("#descuento").addClass("alertaCombo");
    		}
        }

        if($("#descuento").val() == "Si"){
        	if(!$.trim($("#motivo_descuento").val()) || $("#motivo_descuento").val() == ""){
    			error = true;
    			$("#motivo_descuento").addClass("alertaCombo");
    		}
        }
		
		if (!error) {

			cargarDatosDetalle();
			
			var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);

			if (respuesta.estado == 'exito'){
	       		$("#estado").html(respuesta.mensaje);
	        }
				
		} else {
			mostrarMensaje("Por favor revise los campos obligatorios." + mensajeDetalle, "FALLO");	
		}
		
	});
	
</script>
