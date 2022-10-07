<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoLaboral'
	data-opcion='certificado/descargarArchivo' data-destino="detalleItem"
	method="post">
	<input type="hidden" name="id" id="id">
	<fieldset>
		<legend>Selección de certificado laboral</legend>

		<div data-linea="1">
			<label for="id_formato">Tipo Certificado Laboral </label> <select
				id="id_formato" name="id_formato">
				<option value="">Seleccionar....</option>
				 <?php
    echo $this->comboTipoCetificado();
    ?>
			</select>
		</div>

		<div data-linea="2">
			<label for="firma">¿Firma Electrónica? </label> <select id="firma"
				name="firma">
				<option value="">Seleccionar....</option>
				<option value="Si">Si</option>
				<option value="No">No</option>
			</select>
		</div>

		<div data-linea="9">
			<button type="submit" class="generarCertificado">Generar Certificado</button>
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	$("#formulario").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		if(!$.trim($("#id_formato").val())){
			error = true;
			$("#id_formato").addClass("alertaCombo");
		}
        if (!$.trim($("#firma").val())) {
        	error = true;
			$("#firma").addClass("alertaCombo");
        }
		if (!error) {
			$(".generarCertificado").attr('disabled','disabled');
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
			if (respuesta.estado == 'exito'){
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
				$("#id").val(respuesta.contenido);
				$($(this)).attr('data-opcion', 'certificado/editar');
				abrir($(this),event,false);
			}else {
				$("#prueba").html('');
				}
		} else {
			$("#prueba").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
	});

//***************** función para limpiar mensaje en panel de busqueda***************************
	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
</script>
