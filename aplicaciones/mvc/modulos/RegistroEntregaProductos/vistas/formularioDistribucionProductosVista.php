<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='datosDistribucion' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroEntregaProductos' data-opcion='DistribucionProductos/modificarDistribucion' data-destino="detalleItem" method="post" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="id_distribucion" name="id_distribucion" value="<?php echo $this->modeloDistribucionProductos->getIdDistribucion(); ?>">
	
	<fieldset>
		<legend>Distribución</legend>
		
		<div data-linea="1">
			<label for="entidad">Entidad: </label> 		
                <?php echo $this->modeloDistribucionProductos->getEntidad(); ?>
		</div>
		
		<div data-linea="1">
			<label for="id_provincia">Provincia:</label>
 			<?php echo $this->modeloDistribucionProductos->getProvincia(); ?>	
		</div>
		
		<div data-linea="2">
			<label for="producto">Producto: </label> 			
			<?php echo $this->modeloDistribucionProductos->getProducto(); ?>
		</div>
		
		<div data-linea="5">
			<label for="cantidad">Cantidad original: </label> 			
			<input type="text" 	id="cantidad_disponible_original" name="cantidad_disponible_original" value="<?php echo $this->modeloDistribucionProductos->getCantidadAsignada(); ?>" readonly="readonly"/>
		</div>
		
		<div data-linea="5">
			<label for="cantidad">Cantidad disponible: </label> 			
			<input type="number" step="1" id="cantidad_disponible" name="cantidad_disponible" value="<?php echo $this->modeloDistribucionProductos->getCantidadDisponible(); ?>"/>
		</div>

		<div data-linea="3">
    		<button type="submit" class="guardar">Actualizar</button>
    	</div>
	</fieldset>
</form>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroEntregaProductos' data-opcion='DistribucionProductos/agregarDistribucion' data-destino="detalleItem" method="post">
	<fieldset>
		<legend>Existencias en Bodega</legend>
		
		<div data-linea="10">
			<label for="producto">Producto: </label> 			
			<select id="id_producto" name="id_producto" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProductosInventario($this->modeloDistribucionProductos->getIdProducto());
                ?>
            </select>
            
			<input type="hidden" id="producto" name="producto" value="<?php echo $this->modeloDistribucionProductos->getProducto(); ?>" />
		</div>
		
		<div data-linea="11">
			<label for="cantidad">Cantidad disponible: </label> 			
			<input type="text" 	id="cantidad" name="cantidad" readonly="readonly"  />
		</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Nueva Distribución</legend>
		
		<div data-linea="12">
			<label for="entidad">Entidad: </label> 			
			<select id="entidad" name="entidad" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboEntidades($this->modeloDistribucionProductos->getEntidad());
                ?>
            </select>
		</div>
		
		<div data-linea="12">
			<label for="cantidad">Cantidad: </label> 			
			<input type="text" 	id="cantidad_asignada" name="cantidad_asignada" />
		</div>
		
		<div data-linea="13">
			<label for="id_provincia">Provincia:</label>
 			<select id="id_provincia" name="id_provincia" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboProvinciasEc($this->modeloDistribucionProductos->getIdProvincia());
                ?>
            </select>	
            
            <input type="hidden" id="provincia" name="provincia" />		
		</div>
		
		<div data-linea="14">
    		<button type="submit" class="guardar">Agregar</button>
    	</div>
	</fieldset>
</form>

	<fieldset id="TablaFormulario">
		<legend>Distribuciones</legend>
		
		<div data-linea="15">
			<table id="tbItems" style="width:100%">
				<thead>
					<tr>
						<th style="width: 25%;">Entidad</th>
                        <th style="width: 25%;">Provincia</th>                        
                        <th style="width: 25%;">Producto</th>
						<th style="width: 15%;">Cantidad</th>
                        <th style="width: 10%;"></th>
					</tr>
				</thead>
				<tbody id="bodyTbl">
				</tbody>
			</table>
		</div>
		
	</fieldset>


<div data-linea="16">
	<button id="enviarSolicitud" type="button" class="guardar">Guardar</button>
</div>

<script type="text/javascript">
var bandera = <?php echo json_encode($this->formulario); ?>;

	$(document).ready(function() {
		$("#estado").html("");

		if(bandera == 'Nuevo'){
			$("#formulario").show();
			$("#TablaFormulario").show();
			$("#enviarSolicitud").show();
			
			$("#datosDistribucion").hide();
		}else{
			$("#formulario").hide();
			$("#TablaFormulario").hide();
			$("#enviarSolicitud").hide();

			$("#datosDistribucion").show();
		}
		
		construirValidador();
		distribuirLineas();
	 });

	$("#id_producto").change(function () {
        if ($("#id_producto option:selected").val() !== "") {
            $("#cantidad").val($("#id_producto option:selected").attr('data-cantidad'));
            $("#producto").val($("#id_producto option:selected").text());
        }else{
        	$("#cantidad").val("");
        	$("#producto").val("");
        }
    });

	$("#id_provincia").change(function () {
    	if ($("#id_provincia option:selected").val() !== "") {
            $("#provincia").val($("#id_provincia option:selected").text());
        }else{
        	$("#provincia").val("");
        }
    });

	//Agregar nuevo registro
	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#entidad").val())){
			error = true;
			$("#entidad").addClass("alertaCombo");
		}

		//validar que la cantidad disponible sea suficiente para crear un nuevo registro 
		if(!$.trim($("#cantidad_asignada").val()) || ($("#cantidad_asignada").val() <= 0) || (parseInt($("#cantidad_asignada").val()) > parseInt($("#cantidad").val()))){//  
			error = true;
			$("#cantidad_asignada").addClass("alertaCombo");
		}
		
		if(!$.trim($("#id_provincia").val())){
			error = true;
			$("#id_provincia").addClass("alertaCombo");
		}	

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);	
					
		    if (respuesta.estado == 'exito'){
		    	$("#bodyTbl").append(respuesta.contenido);
		    	$("#estado").html(respuesta.mensaje);
		    	limpiarDetalle();
		    	fn_limpiar();
		    }else{
		    	$("#estado").html(respuesta.mensaje).addClass("alerta");
		    }			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Quitar registro
	function quitarProductos(fila){
		$("#tbItems tbody tr").eq($(fila).index()).remove();	  
		fn_eliminarDistribucion(fila);
	}

	//Para eliminar el registro de Distribución seleccionado
	function fn_eliminarDistribucion(fila) {

		$.post("<?php echo URL ?>RegistroEntregaProductos/DistribucionProductos/eliminarDistribucion",
	    		{
	    			idDistribucion : fila
	    		},
	    	    function (data) {
	    			limpiarDetalle();
	    			mostrarMensaje("Registro eliminado con éxito", "EXITO");
	        	}
	    );        
    }    

	//Refresca pantalla y cierra formulario
	$("#enviarSolicitud").click(function (event) {		
		if($("#tbItems >tbody >tr").length != 0){
			$("#_actualizar").click();
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
		}else{
			$("#estado").html("Por favor ingrese por lo menos una distribución del producto").addClass("alerta");
		}			
	});

	function limpiarDetalle(){
		//Volver a cargar el combo con los valores nuevos!
		fn_cargarProductosCatalogo();
		$("#id_producto").val("");
		$("#producto").text("");
		$("#cantidad").val("");
		$("#entidad").val("");
    	$("#cantidad_asignada").val("");
    	$("#id_provincia").val("");
    	$("#provincia").text("");
	}

	//Lista de productos del catálogo de inventario
    function fn_cargarProductosCatalogo() {
    	$.post("<?php echo URL ?>RegistroEntregaProductos/DistribucionProductos/comboProductosInventarioActualizado/", function (data) {
            $("#id_producto").html(data);               
        });
    }

    function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
	
    /********************* Formulario de Edición *************************/
    //Modificar registro
	$("#datosDistribucion").submit(function (event) {
		event.preventDefault();
		var error = false;

		//validar el cambio en la cantidad disponible en comparación con el valor original del registro
		//Si se envia cero como valor disponible se anula el registro
		if(!$.trim($("#cantidad_disponible").val()) || ($("#cantidad_disponible").val() < 0) || (parseInt($("#cantidad_disponible").val()) > parseInt($("#cantidad_disponible_original").val()))){//  
			error = true;
			$("#cantidad_disponible").addClass("alertaCombo");
		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($("#datosDistribucion")).responseText);	
					
		    if (respuesta.estado == 'exito'){
		    	$("#estado").html(respuesta.mensaje);
		    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
		    }else{
		    	$("#estado").html(respuesta.mensaje).addClass("alerta");
		    }			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});    
</script>