<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMensualizacionDecimos.php';

$conexion = new Conexion();
$cms = new ControladorMensualizacionDecimos();

$identificador = $_SESSION['usuario'];
$anioActual = date('Y');

$mensualizacion = $cms->obtenerMensualizacionDecimos($conexion, $identificador, $anioActual);

if(!pg_num_rows($mensualizacion)){
	$banderaMensualizacion = 'NO';
}else{
	$banderaMensualizacion = 'SI';
}


?>

<header>
	<h1>Mensualización décimos</h1>
</header>

	
	<div id="estado"></div>

<form id="datosMensualizacion" data-rutaAplicacion="uath" data-opcion="guardarDatosMensualizacion" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>">
	<input type="hidden" id="anio" name="anio" value="<?php echo $anioActual;?>">
	
	<fieldset>
		<legend>Modelo solicitud</legend>
		
		<pre><a href="modelos/solicitudMensualizacion.docx" target="_blank">Solicitud mensualización decimos</a></pre>
		
		<div><label>Indicaciones:</label>
		
		<pre>1.- Elegir la opción para el pago de décimos.
2.- Si escoge la opción SI siga los siguientes pasos :
  	a) Descargar el formato en Word de acumulación de décimos.
	b) Llenar el Formulario con sus datos personales
	c) Imprimir 
	d) Firmar el Formulario  y escanear
	e)  Adjuntar el documento en pdf y subir al sistema guía. 
		</pre></div>
		
	</fieldset>
			
	<fieldset>
		<legend> Solicitud de acumulación del décimo tercer y décimo cuarto sueldo</legend>

			<div data-linea="1">1.- ¿Desea Usted ACUMULAR su décimo tercer sueldo y décimo cuarto sueldo?</div> 
		
			<div id="dMensualizacionDecimo">
				<input type="radio" name="mensualizacionDecimo" id="mensualizacionSI" value="SI">
				<label for="regular">SI</label><br/>
				<input type="radio" name="mensualizacionDecimo" id="mensualizacionNO" value="NO">
				<label for="puerto">NO</label><br/>
			</div>
		
			<!-- div data-linea="2">Documento adjunto</div-->
			
			<div data-linea="3" id="documnetoAdjunto">				
				<input type="hidden" class="rutaArchivo" name="archivoMensualizacionDecimo" value="0"/>
				<input type="file" class="archivo" accept="application/pdf"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivoMensualizacion" >Subir archivo</button>		
			</div>
	</fieldset>
		
	<button type="submit" class="guardar" disabled="disabled">Guardar</button>

</form>

<form id="resumenMensualizacion">

	<fieldset>
		<legend>Solicitud de acumulación del décimo tercer y décimo cuarto sueldo</legend>

			<div data-linea="1">1.- ¿Desea Usted ACUMULAR su décimo tercer sueldo y décimo cuarto sueldo?</div> 
		
			<div data-linea="2"><label>Respuesta:  </label><?php echo pg_fetch_result($mensualizacion, 0, 'mensualizacion_decimo');?></div>
			
			<?php 
				if(pg_fetch_result($mensualizacion, 0, 'ruta_mensualizacion_decimo') != '0'){
					echo '<pre>Documento adjunto: <a href="'.pg_fetch_result($mensualizacion, 0, "ruta_mensualizacion_decimo").'" target="_blank">Mensualización décimos</a></pre>';
				}
			?>
			
			
	</fieldset>
	
</form>

<form id="tiempoAgotado">

	<fieldset>
		<legend>Tiempo ha expirado</legend>

			<div data-linea="1" class="alerta">El periodo para el envio de la solicitud de décimos ha finalizado.</div> 
	
			
			
	</fieldset>
	
</form>



<script type="text/javascript">

var usuario = <?php echo json_encode($identificador);?>;
var dato_mensualizacion = <?php echo json_encode($banderaMensualizacion);?>;
var dato_anio = <?php echo json_encode($anioActual);?>;

$(document).ready(function(){

	$('#datosMensualizacion').hide();
	$('#resumenMensualizacion').hide();
	$('#documnetoAdjunto').hide();	
	$('#tiempoAgotado').hide();
	
	if(dato_mensualizacion == 'SI'){
		$('#resumenMensualizacion').show();
	}else{
		$('#datosMensualizacion').show();
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
	                , usuario + "-" + rutaArchivo.attr("name")+"-"+dato_anio
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new cargaMensualizacion(estado, archivo, boton)
	            );
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
});


function cargaMensualizacion(estado, archivo, boton) {
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
            $("#datosMensualizacion button.guardar").removeAttr("disabled");
        }
    };

    this.error = function (msg) {
        estado.html(msg);
        archivo.removeClass("amarillo");
        archivo.addClass("rojo");
    };
}


$("#datosMensualizacion").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("input:radio[name=mensualizacionDecimo]:checked").val() == null){
		error = true;
		$("#mensualizacionDecimo label").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese una respuesta para la mensualización de décimos.").addClass('alerta');
	}
	
	if($("#identificador").val()==""){
		error = true;
		$("#estado").html("Su sesión expiró, por favor ingrese nuevamente al sistema.").addClass('alerta');
	}

	if (!error){
		ejecutarJson(this);
	}
	
});

$("input:radio[name=mensualizacionDecimo]").change(function(){

	if ($("input:radio[name=mensualizacionDecimo]:checked").val() == 'SI') {
		$('#documnetoAdjunto').show();
		 $("#datosMensualizacion button.guardar").attr("disabled","disabled");
	}

	if ($("input:radio[name=mensualizacionDecimo]:checked").val() == 'NO') {
		$('#documnetoAdjunto').hide();
		 $("#datosMensualizacion button.guardar").removeAttr("disabled");
	}
});

</script>
