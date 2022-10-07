<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cac = new ControladorAdministrarCatalogos();
$usuario=$_SESSION['usuario'];
?>

<header>
	<h1>Confirmar Inactivación</h1>
</header>

<div id="estado"></div>

	
	<form id="inactivarCatalogo" data-rutaAplicacion="administracionCatalogos" data-opcion="inactivarCatalogo" data-destino="detalleItem" data-accionenexito="actualizar">
			<input type="hidden" name="id" value="<?php echo $_POST['elementos'];?>"/>
	<button id="eliminar" type="submit" class="inactivar" >Inactivar</button>
	
</form>

<style>
.prueba{
width:50% !important;
}

</style>

<script type="text/javascript">

//var df = dateFormat(d,"yyyy,m,d")

$("document").ready(function(){
	
	distribuirLineas();
	construirValidador();
	if(array_registros == '')
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un registro para ser eliminado.</div>');

	if($("#nEliminar").text()){
		$("#notificarEliminarRegistro").hide();
	}
	
});


$("#inactivarCatalogo").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
	
	if($("#estado").html()=="Los datos han sido actualizados satisfactoriamente"){
		//alert($("#estado").html());
		$("#_actualizar").click();
	}

});


</script>
