<?php
session_start();



$id_solicitud = $_POST['elementos'];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />

</head>
<body>

	<header>
		<h1>Confirmar eliminación</h1>
	</header>

	<div id="estado"></div>

	<form id="notificarEliminar" data-rutaaplicacion="dossierPecuario" data-opcion="eliminarSolicitudDossier" data-accionenexito="ACTUALIZAR">

		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
		<fieldset>
			<legend>Importante...</legend>
			<div data-linea="1">
				<label for="solicitud">Está segúro de eliminar la(s) solicitud(es) de número :</label>
				<input type="text" id="solicitud" name="solicitud" value="<?php echo $id_solicitud;?>" disabled="disabled" />
			</div>

		</fieldset>

		<button id="eliminarSI" type="submit" class="guardar">Confirmar</button>
		
	</form>

</body>



<script type="text/javascript">

var id_solicitud= <?php echo json_encode($id_solicitud); ?>;


$(document).ready(function(){


});

$('#eliminarNO').click(function (event) {
	event.preventDefault();

	if($('#solicitud').val()==''){
		mostrarMensaje('Favor seleccione la solicitud a eliminar','FALLO');
		return;
	}

	var form=$(this).parent();

	form.attr('data-opcion', 'abrirSolicitudDossier');
	form.attr('data-destino', 'detalleItem');
	

	form.append("<input type='hidden' id='id' name='id' value='"+id_solicitud+"' />"); // añade el nivel del formulario

	$("#estado").html("");
	abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios

});

$("#notificarEliminar").submit(function(event){
	event.preventDefault();
	
	ejecutarJson($(this),new resultadoExito(),new resultadoFallo() );



});

function resultadoExito() {

	this.ejecutar = function (msg) {
		
		$('#detalleItem').html('Solicitud eliminada');
		$('#estado').html(msg.mensaje);
		
	};
}
function resultadoFallo() {

	this.ejecutar = function (msg) {
		
		$('#detalleItem').html('Error al tratar de eliminar el item seleccionado');
		$('#estado').html(msg.mensaje);
		
	};
}

</script>

</html>
