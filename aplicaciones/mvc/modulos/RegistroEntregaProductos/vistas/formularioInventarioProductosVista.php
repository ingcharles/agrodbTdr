<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroEntregaProductos' data-opcion='InventarioProductos/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_inventario" name="id_inventario" value="<?php echo $this->modeloInventarioProductos->getIdInventario(); ?>" />
			
	<fieldset>
		<legend>Inventario Productos</legend>				

		<div data-linea="1">
			<label for="id_producto_distribucion">Producto:</label>
 			<select id="id_producto_distribucion" name="id_producto_distribucion" required >
                <option value="">Seleccionar....</option>
                <?php
                echo $this->comboProductosDistribucion($this->modeloInventarioProductos->getIdProductoDistribucion());
                ?>
            </select>	
            
            <input type="hidden" id="nombre_producto_distribucion" name="nombre_producto_distribucion" value="<?php echo $this->modeloInventarioProductos->getNombreProductoDistribucion(); ?>" />		
		</div>	
		
		<div data-linea="2">
			<label id="id_cantidad_asignada">Cantidad original: </label>
			<input type="number" id="cantidad_asignada" name="cantidad_asignada" value="<?php echo $this->modeloInventarioProductos->getCantidadAsignada(); ?>" disabled="disabled" />
		</div>			

		<div data-linea="3">
			<label for="cantidad">Cantidad disponible: </label>
			<input type="number" id="cantidad" name="cantidad" min="1" step="1" onblur="this.value=Math.round(this.value)"
				value="<?php echo $this->modeloInventarioProductos->getCantidad(); ?>" required />
		</div>				

		<div data-linea="3">
			<label for="unidad">Unidad: </label>
			<input type="text" id="unidad" name="unidad" value="<?php echo $this->modeloInventarioProductos->getUnidad(); ?>" readonly="readonly" />
		</div>				

		<div data-linea="4">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >

<script type ="text/javascript">
var bandera = <?php echo json_encode($this->formulario); ?>;

	$(document).ready(function() {
		if(bandera == 'Editar'){
			$("#id_producto_distribucion").attr('disabled', 'disabled');
			$("#unidad").attr('disabled', 'disabled');
		}else{
			$("#id_cantidad_asignada").hide();
			$("#cantidad_asignada").hide();
		}
		
		construirValidador();
		distribuirLineas();
	 });

	//Producto
    $("#id_producto_distribucion").change(function () {
    	if ($(this).val() !== "") {
            $("#nombre_producto_distribucion").val($("#id_producto_distribucion option:selected").text());
            $("#unidad").val($("#id_producto_distribucion option:selected").attr('data-unidad'));
        }else{
        	$("#nombre_producto_distribucion").val("");
        	$("#unidad").val("");
        }
    }); 

    $("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_filtrar();
	        }
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>