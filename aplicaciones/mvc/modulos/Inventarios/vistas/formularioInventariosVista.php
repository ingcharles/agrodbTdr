<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formularioInventario' data-rutaAplicacion='mvc/inventarios' data-opcion='inventarios/guardar' data-destino="detalleItem"
	  data-accionEnExito="ACTUALIZAR" method="post">
	  
	  <input type="hidden" name="id_raton" id="id_raton" value="<?php echo $this->modeloMouseInventarios->getIdRaton() ?>" />
	  
	  <fieldset>
		<legend>Inventarios</legend>
				
		<div data-linea="1">
			<label for="modelo"> Modelo </label> 
			<input type="text" id="modelo" name="modelo" value="<?php echo $this->modeloMouseInventarios->getModelo(); ?>" 
				placeholder="Escribir modelo" required="required" maxlength="512" />
		</div>
		
		<div data-linea="2">
			<label for="marca"> Marca </label> 
			<input type="text" id="marca" name="marca" value="<?php echo $this->modeloMouseInventarios->getMarca(); ?>" 
				placeholder="Escribir marca" required="required" maxlength="512" />
		</div>
		
		<div data-linea="3">
			<label for="conector"> Conector </label> 
			<input type="text" id="conector" name="conector" value="<?php echo $this->modeloMouseInventarios->getConector(); ?>" 
				placeholder="Escribir tipo de conector" required="required" maxlength="512" />
		</div>
		
		<div data-linea="4">
			<label for="tipo"> Tipo </label> 
			<input type="text" id="tipo" name="tipo" value="<?php echo $this->modeloMouseInventarios->getTipo(); ?>" 
				placeholder="Escribir tipo de mouse" required="required" maxlength="512" />
		</div>
		
		<button type="submit" class="guardar">Guardar sitio</button> 	
		
	</fieldset>
	  
</form>

<script type="text/javascript">

$(document).ready(function() {
	distribuirLineas();
 });

$("#formularioInventario").submit(function (event) {
	event.preventDefault();
	var error = false;
	if (!error) {
		var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	 } else {
	  $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
	 }
	});

</script>