<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>IngresoCuvsAretes' data-opcion='IngresoCuvsAretes/cargarIdentificadores' data-destino="detalleItem" method="post" data-accionEnExito='ACTUALIZAR'>

	<fieldset>
		<legend>Cargar identificadores</legend>
		<div data-linea="1">
			<input type="file" id="informe" class="archivo" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mvc/modulos/IngresoCuvsAretes/archivos/identificadores">Subir archivo</button>
		</div>
		<hr/>
		<div data-linea="2">
    		<label>Número de GLPI:</label>
    		<input type="text" name="observacion" id="observacion" value="" class="validacion" maxlength="8" />
		</div>
	</fieldset>
	
	<button type="submit" class="guardar"> Guardar</button>		
	
	<div id="cargarMensajeTemporal"></div>

</form>
<?php
?>

<script type="text/javascript">

$(document).ready(function(){
	distribuirLineas();
	$("#observacion").numeric();
});

$('button.subirArchivo').click(function (event) {
	var nombre_archivo = "<?php echo 'identificadores' . (md5(time())); ?>";
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'XLS' || extension[extension.length - 1].toUpperCase() == 'XLSX') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato XLS / XLSX');
            archivo.val("0");
        }
});

$("#formulario").submit(function (event) {

	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

    $('#formulario .validacion').each(function(i, obj) {
    
    	if(!$.trim($(this).val())){
    		error = true;
    		$(this).addClass("alertaCombo");
    	}
    
    });    	

	if($("#archivo").val() == 0){
		error = true;
		$("#informe").addClass("alertaCombo");
	}

	if (!error) {

		$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();
		setTimeout(function(){
			var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);
			$("#cargarMensajeTemporal").html("");
		}, 1000);

		
		
    }
});

</script>