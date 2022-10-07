<?php
session_start();

$id_protocolo = $_POST['elementos'];
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

	<form id="frmProcesar" data-rutaaplicacion="ensayoEficacia" data-opcion="eliminarSolicitudProtocolo" data-accionenexito="ACTUALIZAR">

		<input type="hidden" id="id_protocolo" name="id_protocolo" value="<?php echo $id_protocolo;?>" />
		<fieldset>
			<legend>Importante...</legend>
			<div data-linea="1">
				<label for="protocolo">Está segúro de eliminar la(s) solicitud(es) de número :</label>
				<input type="text" id="protocolo" name="protocolo" value="<?php echo $id_protocolo;?>" disabled="disabled" />
			</div>

		</fieldset>

		<button id="eliminarSI" type="submit" class="guardar">Confirmar</button>
		
	</form>

</body>



<script type="text/javascript">

var id_protocolo= <?php echo json_encode($id_protocolo); ?>;
		

$(document).ready(function(){

	if(id_protocolo==""){
		mostrarMensaje("Petición no será procesada. Para borrar, seleccione un item y pulse el boton eliminar",'FALLO');
	}
	
});

$('#eliminarNO').click(function (event) {
	event.preventDefault();

	if($('#protocolo').val()==''){
		mostrarMensaje('Favor seleccione la solicitud a eliminar','FALLO');
		return;
	}

	var form=$(this).parent();

	form.attr('data-opcion', 'abrirSolicitudProtocolo');
	form.attr('data-destino', 'detalleItem');
	

	form.append("<input type='hidden' id='id' name='id' value='"+id_protocolo+"' />"); // añade el nivel del formulario

	$("#estado").html("");

	
	abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios

});

$("#frmProcesar").submit(function(event){
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

