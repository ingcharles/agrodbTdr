<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CentrosFaenamiento' data-opcion='tipoInspector/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">

	<button type="button" class="editar">Modificar</button>
	<button type="submit" class="guardar">Actualizar</button>

	<input type="hidden" name="id_tipo_inspector" id="id_tipo_inspector" value ="<?php echo $this->modeloTipoInspector->getIdTipoInspector() ?>">
	<input type="hidden" name="identificador_operador" id="identificador_operador" value ="<?php echo $this->modeloTipoInspector->getIdentificadorOperador()?>">

	<fieldset>
		<legend>Datos de auxiliar</legend>				

		<div data-linea="2">
			<label >RUC/CI auxiliar: </label>	
			<?php echo $this->modeloTipoInspector->getIdentificadorOperador(); ?>	
		</div>				

		<div data-linea="3">
			<label >Nombre: </label>	
			<?php echo $this->modeloTipoInspector->getNombreOperador(); ?>			
		</div>				

		<div data-linea="4">
			<label >Provincia: </label>
			<?php echo $this->modeloTipoInspector->getProvincia(); ?>	
		</div>		
		
	</fieldset>
	
	<fieldset>		
	<legend>Resultado de revisión</legend>
		<div data-linea="5">
			<label for="resultado">Resultado: </label>		
			<select id="resultado" name= "resultado" disabled="disabled">
            	<?php 
					echo $this->comboResultadoTipoInspector($this->modeloTipoInspector->getResultado());
				?>
        	</select>
		</div>				
		<div data-linea="6">
			<label for="tipo_inspector">Tipo de inspector: </label>
			<select id="tipo_inspector" name= "tipo_inspector" disabled="disabled">
            	<?php 
					echo $this->comboTipoInspector($this->modeloTipoInspector->getTipoInspector());
				?>
        	</select>
		</div>		
		<div data-linea="7">
			<label for="observacion">Observación: </label>
			<input type="text" id="observacion" name="observacion" value="<?php echo $this->modeloTipoInspector->getObservacion(); ?>" placeholder="observación" required maxlength="500" disabled="disabled"/>
		</div>

	</fieldset >
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		fn_restricciones();
		fn_limpiar();
		distribuirLineas();
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

	//***************** función de restricciones**************************
	function fn_restricciones() {
		$(".guardar").attr('disabled','disabled');
	}
	//******************boton modificar************************************
	$(".editar").click(function(){
		$(".editar").attr('disabled','disabled');
		$(".guardar").removeAttr('disabled');
		$("#tipo_inspector").removeAttr('disabled');
		$("#resultado").removeAttr('disabled');
		$("#observacion").removeAttr('disabled');
	});
</script>
