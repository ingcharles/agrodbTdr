<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formularioReporteProductos' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario' action="aplicaciones/mvc/CertificadoFitosanitario/CertificadoFitosanitario/generarReporteProductos" target="_blank" method="post">
	
    <fieldset>
		<legend>Documentos de Descarga</legend>
		<div data-linea="1">
    		<label>Formato para Carga Masiva: </label>
    		<a href="<?php echo CERT_FITO_URL . "formatos/certificadoFitosanitarioMasivo.xlsx"; ?>" target="_blank" class="archivo_cargado" id="archivo_cargado">Formulario</a>
    	</div>
    	
    	<hr/>
    	
    	<div data-linea="2">
			<label>Listado de Catálogos: </label>
		</div>
		<div data-linea="3">
			<a href="<?php echo CERT_FITO_URL . "formatos/formato.pdf"; ?>" target="_blank" class="archivo_cargado" id="archivo_cargado">Catálogos Básicos</a>
		</div>
		<div data-linea="3">
			<a href="<?php echo CERT_FITO_URL . "formatos/formato.pdf"; ?>" target="_blank" class="archivo_cargado" id="archivo_cargado">Catálogos Países</a>
		</div>
		
		<hr/>
	
		<div data-linea="4">
			<label>Listado de Catálogos: </label>
		</div>
		<div data-linea="5">
    		<label>Tipo de Producto: </label>
    		<select id="id_tipo_producto" name="id_tipo_producto" class="validacion" style="width: 50%;">
				<?php echo $this->comboCatalogoTipoProductos(); ?>
			</select>
    	</div>
    	<div data-linea="5">
    		<label>Subtipo de Producto: </label>
    		<select id="id_subtipo_producto" name="id_subtipo_producto" class="validacion">
				<option value="">Seleccionar....</option>
			</select>
    	</div>
    	<div data-linea="6" style="text-align:center;">
    		<button type="submit">Generar</button>
    	</div>
    </fieldset>

</form>
    
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario' data-opcion='CertificadoFitosanitario/cargarDocumentoMasivo' data-destino="detalleItem" method="post" data-accionEnExito='ACTUALIZAR'>

	<fieldset>
		<legend>Carga masiva</legend>
		<div data-linea="1">
			<input type="file" id="informe" class="archivo" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mvc/modulos/CertificadoFitosanitario/archivos/masivo">Subir archivo</button>
		</div>

		<button type="submit" class="guardar"> Guardar</button>
		
	</fieldset>
	
	<div id="cargarMensajeTemporal"></div>

</form>
<?php
?>

<script type="text/javascript">

$(document).ready(function(){
	$("#estado").html("").removeClass('alerta');
	distribuirLineas();
});

$('button.subirArchivo').click(function (event) {
	var nombre_archivo = "<?php echo 'valija' . (md5(time())); ?>";
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

$("#id_tipo_producto").change(function (event) {
	idTipoProducto = $("#id_tipo_producto").val();
	fn_cargarSubtipoProductos(idTipoProducto);
	
});

//Funcion para cargar puertos de provincia o de pais
function fn_cargarSubtipoProductos(idTipoProducto) {                

	if (idTipoProducto !== ""){
        $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarSubtipoProductoPorIdTipoProducto",
            {
        		idTipoProducto : idTipoProducto
            }, function (data) {
            $("#id_subtipo_producto").html(data);               
        });
    } 

}

$("#formulario").submit(function (event) {

	event.preventDefault();

	$("#estado").html("").removeClass('alerta');
	var error = false;

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

$("#formularioReporteProductos").submit(function (event) {
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#id_tipo_producto").val() == ""){
		error = true;
		$("#id_tipo_producto").addClass("alertaCombo");
	}

	if($("#id_subtipo_producto").val() == ""){
		error = true;
		$("#id_subtipo_producto").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');		
		event.preventDefault();
	}else{
		$("#estado").html("").removeClass('alerta'); 
		//ejecutarJson(form);    
		ejecutarJson($("#formularioReporteProductos"));
	}

});
	
</script>