<?php 

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' href='estilos/estiloapp.css'>


<script src="../general/funciones/jquery-1.9.1.js"
	type="text/javascript"></script>
<script src="../general/funciones/agrdbfunc.js" type="text/javascript"></script>


</head>
<body>
	<div id="estado"></div>
	<form id="formulario" data-rutaAplicacion="formularios" data-opcion="../../json/cargarFormulario" >
		<input type="text" name="id_formulario" value="30"/>
		<button class="regresar">PROBAR</button>
	</form>
	
</body>
<script>
$("#formulario").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this),new exito());
	//ejecutarJson($(this));
});

function exito(){
	this.ejecutar = function(msg){
		$("#estado").html("Datos cargados");
	};
}
	
</script>
</html>