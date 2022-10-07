<?php 
session_start();
?>

<header>
	<h1>Comparar Operador</h1>
</header>

<form id='nuevaComparacion' data-rutaAplicacion='registroOperador' data-opcion='compararOperador' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<div id="estado"></div>
	<br />
	
	<fieldset>
		<legend>Comparación de Operador</legend>
			<label>Código de Operador</label>
				<input type="text" id="identificador" name="identificador">
			<button type="submit" class="guardar">Guardar solicitud</button> 

	</fieldset>
	
 	
</form> 

<script type="text/javascript">
	

		$("#nuevaComparacion").submit(function(event){
			abrir($(this),event,false);	
		});
</script>