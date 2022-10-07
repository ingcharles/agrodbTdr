<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->listaDescapacidadEx($conexion, $_SESSION['usuario_seleccionado']);
$identificador=$_SESSION['usuario_seleccionado'];

?>

<header>
	<h1>Enfermedades</h1>
</header>

<form id="datosEnfermedades" data-rutaAplicacion="uath" data-opcion="guardarDatosEnfermedades">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="opcion" value="Nuevo" name="opcion" />
	<input type="hidden" id="id_relacion_discapacidad" value="<?php echo $_POST['id'] ?>" name="id" />

	<p>
		<button id="actualizar" type="submit" class="guardar">Guardar</button>
	</p>
	
	<div id="estado"></div>
	<table class="soloImpresion">
	<tr><td></td>
	<td>
	<fieldset>
		<legend>Enfermedad Catastrofica</legend>
		
		<div data-linea="1">
			<label>Discapacidad</label>
				<select name="id_discapacidad_enfermedad" id="id_discapacidad_enfermedad"> 
					<option value="">Seleccione....</option>
					<?php
						while($discapacidad = pg_fetch_assoc($res)){
							echo '<option value="'.$discapacidad['id_discapacidad_enfermedad'].'">'.$discapacidad['descripcion'].'</option>';
						}
					?> 
				</select>
			</div>
		<div data-linea="2">
			<label>Porcentaje</label>
				<input type="text" name="porcentaje" id="porcentaje" placeholder="Ej. 05%" data-inputmask="'mask': '99'" pattern="[0-9]{1,2}" title="99" data-er="^[0-9]+$"/> 
		</div>
		<div data-linea="2">
			<label>N° Carnet</label>
				<input type="text" name="carnet" id="carnet" data-er="^[0-9]+$"/> 
		</div>
		<div data-linea="3">
			<label>Certificado Enfermedad</label>
				<!-- input type="file" name="archivo_certificado" id="archivo_certificado" accept="application/msword | application/pdf | image/*" /-->
				
				<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
				<input type="file" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/uath/archivosEnfermedades" >Subir archivo</button>
					
		</div>
	</fieldset>

	</td></tr></table>
</form>

<script type="text/javascript">
$(document).ready(function(){
	construirValidador();
	distribuirLineas();
});

/*$('#archivo_certificado').change(function(event){

	 $("#estado").html('');
		var archivo = $("#archivo_certificado").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
	  		subirArchivo('archivo_certificado','< ?php echo $_SESSION['usuario'].'_id_familiar_'.$identificador.'_';?>'+$("#id_discapacidad_enfermedad option:selected").text().replace(/ /g,''),'aplicaciones/uath/archivosEnfermedades', 'archivo');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#archivo_certificado').val("");
		}
});*/

var usuario = <?php echo json_encode($_SESSION['usuario']);?>;
var numIdentificador = <?php echo json_encode($identificador);?>;

$('button.subirArchivo').click(function (event) {

    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        	
    		subirArchivo(
	                archivo
	                , usuario+'_id_familiar_'+numIdentificador+'_'+$("#id_discapacidad_enfermedad option:selected").text().replace(/ /g,'')
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, boton)
	            );
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
});


$("#datosEnfermedades").submit(function(event){
	event.preventDefault();
	chequearCampos(this);
	//ejecutarJson($(this));
	//if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
	//	$('#_actualizar').click();
		
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#id_discapacidad_enfermedad").val())){
		error = true;
		$("#id_discapacidad_enfermedad").addClass("alertaCombo");
	}

	if(!$.trim($("#porcentaje").val()) || !esCampoValido("#porcentaje")){
		error = true;
		$("#porcentaje").addClass("alertaCombo");
	}

	if(!$.trim($("#carnet").val()) || !esCampoValido("#carnet")){
		error = true;
		$("#carnet").addClass("alertaCombo");
	}

	/*if($("#archivo").val() == 0){
		error = true;
		$("#archivo_certificado").addClass("alertaCombo");
	}*/

	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
			$('#_actualizar').click();
	}
}
</script>
