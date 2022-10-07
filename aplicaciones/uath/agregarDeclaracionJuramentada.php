<?php 
session_start();
$identificador = $_SESSION['usuario'];
$FechaActual = date('Y-m-d');

?>

<header>
	<h1>Declaración Juramentada</h1>
</header>

	
	<div id="estado"></div>

<form id="formDeclaracionJuramentada" data-rutaAplicacion="uath" data-opcion="guardarDatosDeclaracionJuramentada" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>">
	<fieldset>
		<legend>Declaración juramentada periódica</legend>
		<label>Fecha declaración:</label> 
			<input type="text" id="fecha_declaracion" name="fecha_declaracion" value=""
							 required="required" readonly />
			
			<div data-linea="1" id="documentoAdjunto">				
				<input type="hidden" class="rutaArchivo" name="declaracionJuramentada" value="0"/>
				<input type="file" class="archivo" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosDeclaracionJuramentada" >Subir archivo</button>		
			</div> 
		
	</fieldset>
	
		<button type="submit" class="guardar" disabled="disabled">Guardar</button>
</form>

<form id="tiempoAgotado">

	<fieldset>
		<legend>Tiempo ha expirado</legend>

			<div data-linea="1" class="alerta">Su sesión expiró, por favor ingrese nuevamente al sistema..</div> 
			
	</fieldset>
	
</form>



<script type="text/javascript">

var usuario = <?php echo json_encode($identificador);?>;
var dato_fecha = <?php echo json_encode($FechaActual);?>;

$(document).ready(function(){
     $('#formDeclaracionJuramentada').hide();
     $('#tiempoAgotado').hide();

     if(usuario != ''){
 		$('#formDeclaracionJuramentada').show();
 	}else{
 		$('#tiempoAgotado').show();
 	}

     $( "#fecha_declaracion" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      yearRange: '-100:+0'
	});
    	
     distribuirLineas();	
	
});

$('button.subirArchivo').click(function (event) {

    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        	
    		subirArchivo(
	                archivo
	                , usuario + "-" + rutaArchivo.attr("name")+"-"+dato_fecha
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new cargaHistorialLaboral(estado, archivo, boton)
	            );
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
});


function cargaHistorialLaboral(estado, archivo, boton) {
    this.esperar = function (msg) {
        estado.html("Cargando el archivo...");
        archivo.addClass("amarillo");
    };

    this.exito = function (msg) {
        estado.html("El archivo ha sido cargado.");
        archivo.removeClass("amarillo");
        archivo.addClass("verde");
        boton.attr("disabled", "disabled");
        if ($("button.subirArchivo[disabled]").length == 1) {
            $("#formDeclaracionJuramentada button.guardar").removeAttr("disabled");
        }
    };

    this.error = function (msg) {
        estado.html(msg);
        archivo.removeClass("amarillo");
        archivo.addClass("rojo");
    };
}


$("#formDeclaracionJuramentada").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	
	if($("#identificador").val()==""){
		error = true;
		$("#estado").html("Su sesión expiró, por favor ingrese nuevamente al sistema.").addClass('alerta');
	}

	if (!error){
		ejecutarJson(this);
	}
	
});

</script>
