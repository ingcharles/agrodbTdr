<header>
<script src="<?php echo URL ?>modulos/MovilizacionSueros/vistas/js/movilizacionSuero.js"></script>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>MovilizacionSueros' data-opcion='produccion/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
<input type="hidden" id="id_produccion" name="id_produccion" value="<?php echo $this->modeloProduccion->getIdProduccion(); ?>" />
<input type="hidden" id="estado" name="estado" value="eliminado"/>
	<fieldset>
		<legend>Producción diaria</legend>		

		<div data-linea="1">
			<label for="cantidad_leche_acopio">Litros de leche acopiada: </label>
			<input type="text" id="cantidad_leche_acopio" name="cantidad_leche_acopio" value="<?php echo $this->modeloProduccion->getCantidadLecheAcopio(); ?>"
			disabled style="width:80px;"/>
		</div>				

		<div data-linea="2">
			<label for="cantidad_leche_produccion">Litros de leche para producción de queso: </label>
			<input type="text" id="cantidad_leche_produccion" name="cantidad_leche_produccion" value="<?php echo $this->modeloProduccion->getCantidadLecheProduccion(); ?>"
			disabled style="width:80px;"/>
		</div>				
		<div data-linea="3">
			<label for="tipo_producto">Tipo de producto: </label>
			<select id="id_producto_queso" name= "id_producto_queso" disabled>
				<option value="">Seleccionar....</option>
				<?php echo $this->comboSubProducto($_SESSION['usuario'],"SUB_TIPO_IA_QUES','SUB_TIPO_IA_MAN",$this->modeloProduccion->getNombreProductoQueso()); ?>
			</select>	
		</div>	
		<div data-linea="4">
			<label for="id_producto_queso">Tipo de queso: </label>
			<select id="id_producto_queso" name= "id_producto_queso" disabled>
				<option value="">Seleccionar....</option>
				<?php echo $this->comboProducto($_SESSION['usuario'],"SUB_TIPO_IA_QUES','SUB_TIPO_IA_MAN",$this->modeloProduccion->getIdProductoQueso()); ?>
			</select>			
		</div>				

		<div data-linea="4">
			<label for="cantidad_queso_produccion">Cantidad de quesos producidos: </label>
			<input type="text" id="cantidad_queso_produccion" name="cantidad_queso_produccion" value="<?php echo $this->modeloProduccion->getCantidadQuesoProduccion(); ?>"
			disabled />
		</div>				

		<div data-linea="5">
			<label for="id_producto_suero">Tipo de suero: </label>
			<select id="id_producto_suero" name= "id_producto_suero" disabled>
				<option value="">Seleccionar....</option>
				<?php echo $this->comboProducto($_SESSION['usuario'],'SUB_TIPO_IA_SUER',$this->modeloProduccion->getIdProductoSuero()); ?>
			</select>			
		</div>			

		<div data-linea="5">
			<label for="cantidad_suero_produccion">Litros de suero producido: </label>
			<input type="text" id="cantidad_suero_produccion" name="cantidad_suero_produccion"  disabled
			value="<?php echo $this->modeloProduccion->getCantidadSueroProduccion(); ?>"/>
		</div>				

		<div data-linea="6">
			<label for="fecha_produccion_suero">Fecha de producción suero: </label>
			<input type="text" id="fecha_produccion_suero" name="fecha_produccion_suero" 
			disabled readonly 
			value="<?php echo $this->modeloProduccion->getFechaProduccionSuero() == '' ? '' : date('Y-n-j',strtotime($this->modeloProduccion->getFechaProduccionSuero())); ?>"/>
		</div>
		<div data-linea="7" id="msg">
			<label id="msg2" ><span class="alerta"><br>No se puede eliminar la producción esta siendo utilizada...!!</span></label>
		</div>

	</fieldset >
	
	<div data-linea="1">
			<button type="submit" class="guardar">Eliminar</button>
		</div>
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		mostrarMensaje("", "");
		construirValidador();
		distribuirLineas();
		$("#cantidad_leche_acopio").numeric();
		$("#cantidad_leche_produccion").numeric();
		$("#cantidad_queso_produccion").numeric();
		$("#cantidad_suero_produccion").numeric();
		$("#msg").hide();
		
		var btn = <?php echo json_encode ($this->btn); ?>;
		var estado = <?php echo json_encode( $this->modeloProduccion->getEstado()); ?>;
		if(btn == 'no'){
			$(".guardar").hide();
			}
		if(estado == 'utilizado' || estado == 'pendiente'){
			$(".guardar").hide();
			$("#msg").show();
			}
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		abrir($(this), event, false);
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	});
	
</script>
