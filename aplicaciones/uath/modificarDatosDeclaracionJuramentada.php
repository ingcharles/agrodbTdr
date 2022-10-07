<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cd = new ControladorCatastro();

$identificador = $_SESSION['usuario'];
$id=$_POST['id'];
$declaracion = $cd->obtenerDatosDeclaracionJuramentada($conexion, $identificador,'',$id);
$FechaActual = date('Y-m-d');
$estado=pg_fetch_result($declaracion, 0, 'estado');
$fechaDeclaracion=pg_fetch_result($declaracion, 0, 'fecha_declaracion');
?>

<header>
	<h1>Declaración Juramentada</h1>
</header>

	
	<div id="estado"></div>

<form id="formDeclaracionJuramentada" data-rutaAplicacion="uath" data-opcion="actualizarDeclaracionJuramentada" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>">
	<input type="hidden" id="id" name="id" value="<?php echo $id;?>">
	<?php 
		$estadoH=$estado;
		if($estado == 'Rechazado')$estadoH='Modificado';	
	?>
	<input type="hidden" id="estado" name="estado" value="<?php echo $estadoH;?>">
	<fieldset>
		<legend>Declaración juramentada periódica</legend>
			
			<div data-linea="1" >
			<label>Fecha declaración:</label> 
			<input type="text" id="fecha_declaracion" name="fecha_declaracion" value="<?php echo $fechaDeclaracion; ?>"
							 required="required" readonly />
			
			</div> 
			
		
			<?php 
				if(pg_fetch_result($declaracion, 0, 'ruta_declaracion_juramentada') != ''){
					echo '<pre>Documento adjunto: <a href="'.pg_fetch_result($declaracion, 0, "ruta_declaracion_juramentada").'" target="_blank">Declaración juramentada</a></pre>';
				}
			?>
			
			<div data-linea="2" id="documentoAdjunto">				
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

			<div data-linea="1" class="alerta">Su sesión expiró, por favor ingrese nuevamente al sistema.</div> 
			
	</fieldset>
	
</form>

<script type="text/javascript">

var usuario = <?php echo json_encode($identificador);?>;
var estado = <?php echo json_encode($estado);?>;
var dato_fecha = <?php echo json_encode($FechaActual);?>;

$(document).ready(function(){
	
	if(estado == 'Aceptado' || estado == 'Finalizado'){
	 $("input").attr("disabled","disabled");
	}
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
	if($("#fecha_declaracion").val()==""){
		error = true;
		$("#estado").html("Debe seleccionar la fecha de declaración...!").addClass('alerta');
		$("#fecha_declaracion").addClass("alertaCombo");
	}
	
	if($("#identificador").val()==""){
		error = true;
		$("#estado").html("Su sesión expiró, por favor ingrese nuevamente al sistema.").addClass('alerta');
	}

	if (!error){
		ejecutarJson(this);
	}
	
});

</script>
