<?php 
session_start();
$identificador = $_SESSION['usuario'];
$FechaActual = date('Y-m-d');

?>

<header>
	<h1>Historial Laboral IESS</h1>
</header>

	
	<div id="estado"></div>

<form id="historialLaboralIess" data-rutaAplicacion="uath" data-opcion="guardarDatosHistorialLaboralIess" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>">
	<fieldset>
		<legend>Historial del tiempo de trabajo por empresa</legend>
			
			<div data-linea="1" id="documentoAdjunto">				
				<input type="hidden" class="rutaArchivo" name="historialLaboral" value="0"/>
				<input type="file" class="archivo" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosHistorialLaboralIESS" >Subir archivo</button>		
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
     $('#historialLaboralIess').hide();
     $('#tiempoAgotado').hide();

     if(usuario != ''){
 		$('#historialLaboralIess').show();
 	}else{
 		$('#tiempoAgotado').show();
 	}
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
            $("#historialLaboralIess button.guardar").removeAttr("disabled");
        }
    };

    this.error = function (msg) {
        estado.html(msg);
        archivo.removeClass("amarillo");
        archivo.addClass("rojo");
    };
}


$("#historialLaboralIess").submit(function(event){
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
