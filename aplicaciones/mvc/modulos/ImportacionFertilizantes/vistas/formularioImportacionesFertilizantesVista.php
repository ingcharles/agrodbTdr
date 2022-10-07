<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ImportacionFertilizantes'
	  data-opcion='importacionesFertilizantes/guardar' data-destino='detalleItem' data-accionEnExito='ACTUALIZAR'>
	  
	  <?php echo $this->resultadoRevision;?>
	
	<fieldset>
		<legend>Datos del importador</legend>
		
		<div data-linea="1">
			<label for="identificador">RUC: </label> 
			<input type="text"
				   id="identificador" name="identificador"
				   value="<?php echo $this->modeloImportacionesFertilizantes->getIdentificador(); ?>"
				   readonly="readonly" required="required" maxlength="13" />
		</div>

		<div data-linea="2">
			<label for="razon_social">Razón social: </label>
			<input type="text"
				   id="razon_social" name="razon_social"
				   value="<?php echo $this->modeloImportacionesFertilizantes->getRazonSocial(); ?>"
				   readonly="readonly" required="required" maxlength="512" />
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Datos de importación</legend>

		<input type="hidden" id="id_importacion_fertilizantes" name="id_importacion_fertilizantes" value="<?php echo $this->modeloImportacionesFertilizantes->getIdImportacionFertilizantes(); ?>" />

		<div data-linea="1">
			<label for="tipo_operacion">Operación registrada: </label>
			<select id="tipo_operacion" name="tipo_operacion" required="required" class="validacion">
				<option value="">Seleccione....</option>
				<option value="Importador">Importador</option>
				<option value="Importador consumo propio">Importador consumo propio</option>
			</select>
		</div>

		<div data-linea="2">
			<label for="tipo_solicitud">Tipo solicitud: </label>
			<select id="tipo_solicitud" name="tipo_solicitud" required="required" class="validacion">
				<option value="">Seleccione....</option>
			</select>
		</div>
		
		<div data-linea="3">
			<label for="id_pais_origen">País origen: </label>
			<select id="id_pais_origen" name="id_pais_origen" required="required" class="validacion">
				<option value="">Seleccionar....</option>
				<option value="No aplica">No aplica</option>
                <?php 
               		echo $this->comboPaises($this->modeloImportacionesFertilizantes->getIdPaisOrigen());
                ?>
            </select>
			<input type="hidden" id="nombre_pais_origen" name="nombre_pais_origen" value="<?php echo $this->modeloImportacionesFertilizantes->getNombrePaisOrigen(); ?>"/>
		</div>
		
		<div data-linea="3">
			<label for="id_pais_procedencia">País procedencia: </label>
			<select id="id_pais_procedencia" name="id_pais_procedencia" required="required" class="validacion">
				<option value="">Seleccionar....</option>
				<option value="No aplica">No aplica</option>
                <?php 
                echo $this->comboPaises($this->modeloImportacionesFertilizantes->getIdPaisProcedencia());
                ?>
            </select>
			<input type="hidden" id="nombre_pais_procedencia" name="nombre_pais_procedencia" value="<?php echo $this->modeloImportacionesFertilizantes->getNombrePaisProcedencia(); ?>"/>
		</div>
		
		<div data-linea="4">
			<label for="producto_formular">Producto a formular: </label> 
			<input type="text" id="producto_formular" name="producto_formular" 
				   value="<?php echo $this->modeloImportacionesFertilizantes->getProductoFormular(); ?>"
				   placeholder="Producto a formular."
				   maxlength="50"/>
		</div>

		<div data-linea="5">
			<label for="numero_factura_pedido">Número factura Agrocalidad: </label> 
			<input type="text" id="numero_factura_pedido" name="numero_factura_pedido"
				   value="<?php echo $this->modeloImportacionesFertilizantes->getNumeroFacturaPedido(); ?>"
				   placeholder="Número de factura de pedido" 
				   maxlength="17" data-inputmask="'mask': '999-999-999999999'" />
		</div>
	
	</fieldset>
	
	<fieldset>
		<legend>Detalle de productos</legend>
		
		<div data-linea="1">
			<label for="o_nombre_comercial_producto">Nombre comercial producto:</label>
			<input type="text" id="o_nombre_comercial_producto"
				   name="o_nombre_comercial_producto"
				   placeholder="Nombre comercial del producto."
				   maxlength="50" class="validacionProducto"/>
		</div>
		
		<div data-linea="2">
			<label for="o_nombre_producto_origen">Nombre producto país de origen: </label> 
			<input type="text" id="o_nombre_producto_origen" name="o_nombre_producto_origen"
				   placeholder="Nombre del producto en el país de origen." 
				   maxlength="50"/>
		</div>
		
		<div data-linea="3">
			<label for="o_numero_registro"># Registro en Ecuador: </label> 
			<input type="text" id="o_numero_registro" name="o_numero_registro"
				   placeholder="Número de registro." 
				   maxlength="20"/>
		</div>
		
		<div data-linea="3">
			<label for="o_composicion">Composición: </label> 
			<input type="text"
				   id="o_composicion" name="o_composicion"
				   placeholder="Descripción de la composición del producto." 
				   maxlength="500"/>
		</div>
		
		<div data-linea="4">
			<label for="o_cantidad">Cantidad comercial: </label> 
			<input type="text" id="o_cantidad" name="o_cantidad"
				   placeholder="Cantidad de producto" 
				   maxlength="100" class="validacionProducto"/>
		</div>
		
		<div data-linea="4">
			<label for="o_peso_neto">Peso neto (Kg): </label> 
			<input type="number" id="o_peso_neto" name="o_peso_neto"
				placeholder="Peso neto." 
				maxlength="15" class="validacionProducto"/>
		</div>
		
		<div data-linea="5">
			<label for="o_partida_arancelaria">Partida arancelaria: </label> 
			<input type="text" id="o_partida_arancelaria" name="o_partida_arancelaria"
				   placeholder="Partida arancelaria."
				   maxlength="13" data-inputmask="'mask': '9999999999'"/>
		</div>
		
		<div data-linea="6">
    		<button type="button" class="mas" id="btnAgregarProductos">Agregar</button>
    	</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Productos ingresados</legend>
		<div data-linea="1">
			<table id="tbItems" style="width:100%">
				<thead>
					<tr>
						<th style="width: 30%;">Nombre comercial</th>
						<th style="width: 15%;"># registro</th>
                        <th style="width: 15%;">Cantidad</th>
                        <th style="width: 15%;">Peso</th>
                        <th style="width: 15%;">Partida</th>
                        <th style="width: 10%;"></th>
					</tr>
				</thead>
				<tbody>
					<?php echo $this->tablaProductos;?>
				</tbody>
			</table>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Documentos anexos</legend>
		
		<?php echo $this->documentos;?>
		
	</fieldset>
	
	<div id="cargarMensajeTemporal"></div>
	
	<div data-linea="10">
			<button type="submit" class="guardar">Guardar</button>
	</div>
	
</form>
<script type="text/javascript">

	var array_comboTipoSolicitud = <?php echo json_encode($this->tipoSolicitud);?>;

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		for(var i=0; i<array_comboTipoSolicitud.length; i++){
			 $('#tipo_solicitud').append(array_comboTipoSolicitud[i]);
	    }

		cargarValorDefecto("tipo_operacion","<?php echo $this->modeloImportacionesFertilizantes->getTipoOperacion();?>");
		cargarValorDefecto("tipo_solicitud","<?php echo $this->modeloImportacionesFertilizantes->getTipoSolicitud();?>");

		mostrarMensaje("","EXITO");
		
	 });

	 $("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");
		
		$('.rutaArchivo').each(function(i, obj) {
		   if($(this).val() == 0 && $(this).attr("data-obligatorio") == 'SI'){
				error = true;
				$(this).parent().addClass("alertaCombo");
			}
		});

		$('.validacion').each(function(i, obj) {
			if(!$.trim($(this).val())){
				error = true;
				$(this).addClass("alertaCombo");
			}
		});

		if ($("#tbItems tbody tr").length == 0){
			error = true;
			$("#tbItems").addClass("alertaCombo");
		}
		
		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
			setTimeout(function(){
				JSON.parse(ejecutarJson($("#formulario")).responseText);
			}, 1000);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$('button.subirArchivo').click(function (event) {
		var boton = $(this);
		var tipo_archivo = boton.parent().find(".rutaArchivo").attr("id");
		var nombre_archivo = tipo_archivo+"<?php echo '_imf_' . (md5(time())); ?>";
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");

	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	    	subirArchivo(
				archivo
				, nombre_archivo
				, boton.attr("data-rutaCarga")
				, rutaArchivo
				, new carga(estado, archivo, boton)
	        );
	        } else {
	            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	            archivo.val("0");
	        }
	});

	$("#id_pais_origen").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#id_pais_origen").val() != ''){
	    	$("#nombre_pais_origen").val($("#id_pais_origen option:selected").text());
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}
	});

	$("#id_pais_procedencia").change(function(event){
		mostrarMensaje("","EXITO");
	    if($("#id_pais_procedencia").val() != ''){
	    	$("#nombre_pais_procedencia").val($("#id_pais_procedencia option:selected").text());
	    }else{
	    	mostrarMensaje("Por favor seleccione un valor","FALLO");
		}
	});

    //Función para agregar elementos
    $('#btnAgregarProductos').click(function(){
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

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

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			$.post("<?php echo URL ?>ImportacionFertilizantes/ImportacionesFertilizantes/obtenerDatosFilaProducto",
		    	{
					nombre_comercial_producto: $("#o_nombre_comercial_producto").val(),
					nombre_producto_origen: $("#o_nombre_producto_origen").val(),
					numero_registro: $("#o_numero_registro").val(),
					composicion: $("#o_composicion").val(),
					cantidad: $("#o_cantidad").val(),
					peso_neto: $("#o_peso_neto").val(),
					partida_arancelaria: $("#o_partida_arancelaria").val()
		        },
		      	function (data) {
		        	if (data.estado === 'EXITO') {
		        		$("#tbItems tbody").append(data.mensaje);
		        		limpiarProducto();
		        		
	                }
		        }, 'json');
			
		}
    });

    function quitarProductos(fila){
		$("#tbItems tbody tr").eq($(fila).index()).remove();
	}

    function limpiarProducto(){
		$("#o_nombre_comercial_producto").val("");
    	$("#o_nombre_producto_origen").val("");
    	$("#o_numero_registro").val("");
    	$("#o_composicion").val("");
    	$("#o_cantidad").val("");
    	$("#o_peso_neto").val("");
    	$("#o_partida_arancelaria").val("");
	}
    
</script>
