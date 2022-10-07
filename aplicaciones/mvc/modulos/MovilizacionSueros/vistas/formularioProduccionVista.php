<header>
<script src="<?php echo URL ?>modulos/MovilizacionSueros/vistas/js/movilizacionSuero.js"></script>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>MovilizacionSueros' data-opcion='produccion/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Producción diaria</legend>		

		<div data-linea="1">
			<label for="cantidad_leche_acopio">Litros de leche acopiada: </label>
			<input type="text" id="cantidad_leche_acopio" name="cantidad_leche_acopio" value="<?php echo $this->modeloProduccion->getCantidadLecheAcopio(); ?>"
			placeholder="Cantidad de leche acopiada por el operador"  maxlength="8" style="width:80px;"/>
		</div>				

		<div data-linea="2">
			<label for="cantidad_leche_produccion">Litros de leche para producción: </label>
			<input type="text" id="cantidad_leche_produccion" name="cantidad_leche_produccion" value="<?php echo $this->modeloProduccion->getCantidadLecheProduccion(); ?>"
			placeholder="Cantidad de leche que se va a destinar para la producción de quesos"  maxlength="8" style="width:80px;"/>
		</div>	
		<div data-linea="3">
			<label for="tipo_producto">Tipo de producto: </label>
			<select id="nombre_producto_queso" name= "nombre_producto_queso">
				<option value="">Seleccionar....</option>
				<?php echo $this->comboSubProducto($_SESSION['usuario'],"SUB_TIPO_IA_QUES','SUB_TIPO_IA_MAN",$this->modeloProduccion->getIdProductoQueso()); ?>
			</select>	
		</div>			

		<div data-linea="4">
			<label for="id_producto_queso">Producto: </label>
			<select id="id_producto_queso" name= "id_producto_queso">
				<option value="">Seleccionar....</option>
			</select>			
		</div>				

		<div data-linea="4">
			<label for="cantidad_queso_produccion">Cantidad de producto: </label>
			<input type="text" id="cantidad_queso_produccion" name="cantidad_queso_produccion" value="<?php echo $this->modeloProduccion->getCantidadQuesoProduccion(); ?>"
			placeholder="Cantidad de quesos producidos"  maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="id_producto_suero">Tipo de suero: </label>
			<select id="id_producto_suero" name= "id_producto_suero">
				<option value="">Seleccionar....</option>
				<?php echo $this->comboProducto($_SESSION['usuario'],'SUB_TIPO_IA_SUER',$this->modeloProduccion->getIdProductoSuero()); ?>
			</select>			
		</div>			

		<div data-linea="5">
			<label for="cantidad_suero_produccion">Litros de suero producido: </label>
			<input type="text" id="cantidad_suero_produccion" name="cantidad_suero_produccion" 
			placeholder="Campo que almacena la cantidad de suero producido por el operador"  maxlength="8" 
			value="<?php echo $this->modeloProduccion->getCantidadSueroProduccion(); ?>"/>
		</div>				

		<div data-linea="6">
			<label for="fecha_produccion_suero">Fecha de producción suero: </label>
			<input type="text" id="fecha_produccion_suero" name="fecha_produccion_suero" 
			placeholder="Fecha de producción del suero registrado" required readonly 
			value="<?php echo $this->modeloProduccion->getFechaProduccionSuero() == '' ? '' : date('Y-n-j',strtotime($this->modeloProduccion->getFechaProduccionSuero())); ?>"/>
		</div>

	</fieldset >
	<div data-linea="1">
			<button type="submit" class="guardar">Guardar</button>
		</div>
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		$("#cantidad_leche_acopio").numeric();
		$("#cantidad_leche_produccion").numeric();
		$("#cantidad_queso_produccion").numeric();
		$("#cantidad_suero_produccion").numeric();
	 });
	 

	$("#formulario").submit(function (event) {
		event.preventDefault();
		mostrarMensaje("", "EXITO");
		var error = false;
		$(".guardar").attr('disabled','disabled');
		$(".alertaCombo").removeClass("alertaCombo");
		if(!$.trim($("#cantidad_leche_acopio").val())){
			error = true;
			$("#cantidad_leche_acopio").addClass("alertaCombo");
		}
        if (!$.trim($("#cantidad_leche_produccion").val())) {
        	error = true;
			$("#cantidad_leche_produccion").addClass("alertaCombo");
        }
        if (!$.trim($("#id_producto_queso").val())) {
        	error = true;
			$("#id_producto_queso").addClass("alertaCombo");
        }
        if(!$.trim($("#cantidad_leche_acopio").val())){
			error = true;
			$("#cantidad_leche_acopio").addClass("alertaCombo");
		}
        if (!$.trim($("#nombre_producto_queso").val())) {
        	error = true;
			$("#nombre_producto_queso").addClass("alertaCombo");
        }
        if(!$.trim($("#cantidad_queso_produccion").val())){
			error = true;
			$("#cantidad_queso_produccion").addClass("alertaCombo");
		}
        if (!$.trim($("#id_producto_suero").val())) {
        	error = true;
			$("#id_producto_suero").addClass("alertaCombo");
        }
        if (!$.trim($("#cantidad_suero_produccion").val())) {
        	error = true;
			$("#cantidad_suero_produccion").addClass("alertaCombo");
        }
        if (!$.trim($("#fecha_produccion_suero").val())) {
        	error = true;
			$("#fecha_produccion_suero").addClass("alertaCombo");
        }

        if(parseFloat($("#cantidad_leche_produccion").val()) > parseFloat($("#cantidad_leche_acopio").val()) ){
			$("#cantidad_leche_acopio").attr("placeholder", $("#cantidad_leche_acopio").val());
			$("#cantidad_leche_produccion").attr("placeholder", $("#cantidad_leche_produccion").val());
			$("#cantidad_leche_acopio").val('');
			$("#cantidad_leche_produccion").val('');
			$("#cantidad_leche_acopio").addClass("alertaCombo");
			$("#cantidad_leche_produccion").addClass("alertaCombo");
			error = true;
		}
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
			
		} else {
			$(".guardar").removeAttr('disabled');
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
	});
	 //validar cantidad de leche 
    $("#cantidad_leche_produccion").change(function() {
    	$(".alertaCombo").removeClass("alertaCombo");
    	mostrarMensaje("", "EXITO");
		if(parseFloat($("#cantidad_leche_produccion").val()) > parseFloat($("#cantidad_leche_acopio").val()) ){
			mostrarMensaje("La cantidad de leche de producción no puede ser mayor a la acopiada...!!", "FALLO");
			$("#cantidad_leche_produccion").addClass("alertaCombo");
			$("#cantidad_leche_produccion").attr("placeholder", $("#cantidad_leche_produccion").val());
			$("#cantidad_leche_produccion").val('');
		}
	    
	});

    //validar cantidad de leche 
    $("#cantidad_leche_acopio").change(function() {
    	$(".alertaCombo").removeClass("alertaCombo");
    	mostrarMensaje("", "EXITO");
		if(parseFloat($("#cantidad_leche_produccion").val()) > parseFloat($("#cantidad_leche_acopio").val()) ){
			mostrarMensaje("La cantidad de leche de acopio no puede ser menor a la de producción...!!", "FALLO");
			$("#cantidad_leche_acopio").addClass("alertaCombo");
			$("#cantidad_leche_acopio").attr("placeholder", $("#cantidad_leche_acopio").val());
			$("#cantidad_leche_acopio").val('');
		}
	    
	});
	
  //Cuando seleccionamos la tipo de producto
    $("#nombre_producto_queso").change(function() {
        if($("#nombre_producto_queso").val() != ''){
        $.post("<?php echo URL ?>MovilizacionSueros/Produccion/buscarComboProducto", 
				{
				    idTipoProducto: $("#nombre_producto_queso").val()
				},
				function (data) {
	            	$("#id_producto_queso").html(data);
	            	$("#id_producto_queso").removeAttr("disabled");
	        	});
        }else {
        	 $("#id_producto_queso").html('<option value="">Seleccione...</option>');
            }
	});
</script>
