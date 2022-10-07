<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$operador = $_SESSION['usuario'];
?>

<header>
	<h1>Nueva Solicitud</h1>
</header>
<div id="mensajeCargando"></div>
<form id="nuevoRegistro" data-rutaAplicacion="mercanciasSinValorComercial" data-opcion="guardarNuevaSolicitud">
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="usuario" value=<?php echo $operador;?> name="usuario">
	<input type="hidden" id="numeracionCarnetVacuna">
	<input type="hidden" id="numeracionCertificadoMedico">
	<input type="hidden" id="numeracionTitularAnticuerpo">
	<input type="hidden" id="numeracionAutMinAmb">
	<input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="Exportacion">
	<input type="hidden" id="idSolicitud" name="idSolicitud">
	<input type="hidden" id="clasificacion" value="" name="clasificacion">
	<input type="hidden" id="numero" value="" name="numero">

	<fieldset>
		<legend>Datos del Propietario </legend>
		<div data-linea="1">
				<label>*Tipo de identificación: </label> 
				<select id="tipoIdentificacion" name="tipoIdentificacion">
					<option value = ''>Seleccione....</option>
					<option value="04">Ruc</option>
					<option value="05">Cédula</option>
					<option value="06">Pasaporte</option>
				</select>
		</div>
		<div data-linea="3">
			<label for="identificacionPropietario">* Identificación: </label>
			<input type="text" id="identificacionPropietario" name="identificacionPropietario" onkeypress="return checkGuion(event)" autocomplete="off">
		</div>
		<div data-linea="2">
			<label for="nombrePropietario">* Nombre: </label>
			<input type="text" id="nombrePropietario" name="nombrePropietario" onkeypress='return soloLetras(event)'>
		</div>
		<div data-linea="4"> 
			<label for="direccionPropietario">* Dirección: </label>
			<input type="text" id="direccionPropietario" name="direccionPropietario">
		</div>
		<div data-linea="5">
			<label>*Teléfono: </label> 
			<input type="number" id="telefonoPropietario" name="telefonoPropietario" class="soloNumeros"/>
		</div>
		<div data-linea="6">
			<label>*Correo: </label> 
			<input type="text" id="correoPropietario" name="correoPropietario" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$"/>
		</div>
	</fieldset>

	<fieldset>
		<legend>Datos del Destinatario </legend>
		<div data-linea="1">
			<label for="nombreDestinatario">* Nombre: </label>
			<input type="text" id="nombreDestinatario" name="nombreDestinatario" onkeypress="return soloLetras(event)">
		</div>
		<div data-linea="2"> 
			<label for="direccionDestinatario">* Dirección: </label>
			<input type="text" id="direccionDestinatario" name="direccionDestinatario">
		</div>
	</fieldset>

	<fieldset>
		<legend>Datos Generales </legend>
		<div data-linea="1">
			<label for="pais">* País Destino: </label>
			<select id="pais" name="pais">
				<option value="">Seleccione...</option>
				<?php
					$pais = $cc->listarLocalizacion($conexion, 'PAIS');
					while($fila=pg_fetch_assoc($pais)){
						echo '<option value="' . $fila['id_localizacion'] .'">' . $fila['nombre'] . '</option>';
					}
				?>
			</select>
			<input type="hidden" id="nombrePais" name="nombrePais">
		</div>
		<div data-linea="3">
			<label for="uso">* Uso Destinado:</label>
			<select id="uso" name="uso">
				<option value="">Seleccione...</option>
				<?php
					$res= $cc->listarUsosPorArea($conexion, 'SA');
					while($fila=pg_fetch_assoc($res)){
						echo'<option value="'.$fila['id_uso'].'">'.$fila['nombre_uso'].'</option>';
					}
				?>
			</select>
			<input type="hidden" id="nombreUso" name="nombreUso">
		</div>
		<div data-linea="3">
			<label for="fechaEmbarque">* Fecha Embarque: </label>
			<input type="text" id="fechaEmbarque" name="fechaEmbarque">
		</div>

		<div data-linea="2">
			<label for="puestoControl">* Puesto Control Cuarentenario: </label>
			<select id="puestoControl" name="puestoControl">
				<option value="">Seleccione...</option>
				<?php
					$res= $cc->listarCatalogoLugarInspeccion($conexion, 'Mercancia');
					while($fila=pg_fetch_assoc($res)){
						echo'<option value="'.$fila['id_lugar'].'" data-provincia="'.$fila['nombre_provincia'].'" data-idprovincia="'.$fila['id_provincia'].'">'.$fila['nombre'].'</option>';
					}
				?>
			</select>

			<input type="hidden" id="nombrePuestoControl" name="nombrePuestoControl">
			<input type="hidden" id="idProvincia" name="idProvincia">
			<input type="hidden" id="nombreProvincia" name="nombreProvincia">
		</div>
	</fieldset>

	<fieldset class="soloPantalla" id="seccionDocumentos">
		<legend>Adjuntar Documentos </legend>
		<div  style="width:100%">
		    <label>* Carnet de Vacunas:</label>
			<input type="hidden" class="rutaArchivo" id="rutaVacuna" name="rutaVacuna" value="0" />
			<input type="file" class="archivo" id="archivoVacuna" accept="application/msword | application/pdf | image/*"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" id="cargarVacunas" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
		</div>
		<div  style="width:100%">
			<hr>
			<label>* Certificado Médico Veterinario:</label>			
			<input type="hidden" class="rutaArchivo" id="rutaVeterinario" name="rutaVeterinario" value="0"/>
			<input type="file" class="archivo" id="archivoVeterinario" accept="application/msword | application/pdf | image/*"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" id="cargarVeterinario" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
		</div>
		<div  style="width:100%">
			<hr>
			<label>Titulación Anticuerpos:</label>
			<input type="hidden" class="rutaArchivo" name="rutaAnticuerpos" value="0"/>
			<input type="file" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" id="cargarAnticuerpos" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
		</div>
		<div  style="width:100%">
			<hr>
			<label>Autorización del Ministerio del Ambiente:</label>
			<input type="hidden" class="rutaArchivo" name="rutaAutMinAmb" value="0"/>
			<input type="file" class="archivo" name="autMinAmb" accept="application/msword | application/pdf | image/*"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" id="cargarAutMinAmb" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
		</div>
	</fieldset>
	
	<fieldset id="datosProducto">
		<legend>Datos del Producto </legend>
		<div data-linea="1">
			<label for="tipoProducto">* Tipo de Producto:</label>
			<select id="tipoProducto" name="tipoProducto">
				<option value="">Seleccione...</option>
				<?php
					$res= $cc->listarTipoProductosXAreaCodificacion($conexion, "SA","'PRD_MASCOTA'");
					while($fila=pg_fetch_assoc($res)){
						echo'<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
					}
				?>
			</select>
		</div>
		<div data-linea="2" id="resultadoSubTipo">
			<label for="subTipoProducto">* Subtipo:</label>
			<select id="subTipoProducto" name="subTipoProducto" disabled>
				<option value="">Seleccione</option>
			</select>
		</div>
		<div data-linea="2" id="resultadoProducto">
			<label for="producto">* Producto:</label>
			<select id="producto" name="producto" disabled>
				<option value="">Seleccione</option>
			</select>
		</div>

		<div data-linea="8">
			<label for="sexo">* Sexo:</label>
			<select id="sexo" name="sexo" disabled>
				<option value="">Seleccione</option>
				<option value="H">Hembra</option>
				<option value="M">Macho</option>
			</select>
		</div>
		<div data-linea="8">
			<label for="color">* Color: </label>
			<input type="text" id="color" name="color" onkeypress="return soloLetras(event)" disabled>
		</div>
		<div data-linea="8">
			<label for="edad">* Edad (Meses): </label>
			<input type="text" id="edad" name="edad" class="soloNumeros" disabled style="max-width:81%">
		</div>		
		<div data-linea="9">
			<label for="raza">* Raza: </label>
			<input type="text" id="raza" name="raza" onkeypress="return soloLetras(event)" disabled>
		</div>
		<div data-linea="9">
			<label for="identificacionProducto">* Identificación: </label>
			<input type="text" id="identificacionProducto" name="identificacionProducto" disabled>
		</div>
		<div >
			<button class="mas" id="aregarRegistro" onClick="agregar();return false">Agregar</button>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Productos registrados</legend>
		<div data-linea="11">
			<div id="dRegistroAConform">
				<table style="width:100%" id="tablaProductos">
				<thead>
				<tr>
					<th>Tipo</th>
					<th>Producto</th>
					<th>Identificación</th>
					<th>Acciones</th>
				</tr>
				</thead>
				<tbody id="bodyTablaProductos">
				</tbody>
				</table>
			</div>
		</div>
	</fieldset>
		
	<button type="submit" class="guardar" id="guardarRegistro">Enviar solicitud</button>
</form>

<script type="text/javascript">

$("document").ready(function(){

	$("#nombrePropietario").attr('maxlength','256');
	$("#direccionPropietario").attr('maxlength','256');
	$("#nombreDestinatario").attr('maxlength','256');
	$("#direccionDestinatario").attr('maxlength','256');
	$("#raza").attr("maxlength","32");
	$("#edad").attr("maxlength","4");
	$("#color").attr("maxlength","16");
	$("#identificacionProducto").attr("maxlength","16");
	$("#fechaEmbarque").attr("readonly",true);

	$("#fechaEmbarque").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      minDate: "0",
	      dateFormat: 'yy-mm-dd'
	});

	distribuirLineas();
	construirValidador();
});

$(".soloNumeros").on("keypress keyup blur",function (event) {
    $(this).val($(this).val().replace(/[^\d].+/, ""));
     if ((event.which < 48 || event.which > 57)) {
         if(event.which != 8)
         event.preventDefault();
     }
});

function soloLetras(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúäëïöüabcdefghijklmnñopqrstuvwxyz";
    especiales = "8-37-39-46";

    tecla_especial = false
    for(var i in especiales){
         if(key == especiales[i]){
             tecla_especial = true;
             break;
         }
     }

     if(letras.indexOf(tecla)==-1 && !tecla_especial){
         return false;
     }
 }

$("#pais").change(function (event){	
	if($.trim($("#detalleItem #pais").val()) != "" ) {
		$("#nombrePais").val($("#pais option:selected").text());
	}
});

$("#uso").change(function (event){
	if($.trim($("#detalleItem #uso").val()) != "" ) {
		$("#nombreUso").val($("#uso option:selected").text());
	}
});

$("#puestoControl").change(function (event){
	if($.trim($("#puestoControl").val())!=""){
		$("#nombrePuestoControl").val($("#puestoControl option:selected").text());
		$("#idProvincia").val($("#puestoControl option:selected").attr("data-idprovincia"));
		$("#nombreProvincia").val($("#puestoControl option:selected").attr("data-provincia"));
	}
});

$("#tipoProducto").change(function(event){
	if($("#tipoProducto").val()!=""){
		$("#nuevoRegistro").attr('data-destino', 'resultadoSubTipo');
	    $("#nuevoRegistro").attr('data-opcion', 'comboExportacion');
		$("#opcion").val("subtipo");		
		abrir($("#nuevoRegistro"), event, false);

		$("#subTipoProducto").removeAttr("disabled");
		$("#sexo").removeAttr("disabled");
		$("#raza").removeAttr("disabled");
		$("#edad").removeAttr("disabled");
		$("#color").removeAttr("disabled");
		$("#identificacionProducto").removeAttr("disabled");
		$("#producto").attr("disabled",true);
		limpiar();
	}else{
		limpiar();
		$("#subTipoProducto").val("");
	}
});

function limpiar(){
	$("#datosProducto .alertaCombo").removeClass("alertaCombo");
}

$('#cargarVacunas').click(function (event) {
var fecha= obtenerFecha();
$("#numeracionCarnetVacuna").val(fecha);
cargarArchivos('#cargarVacunas',$("#numeracionCarnetVacuna").val(),'_carnet_');
});

$('#cargarVeterinario').click(function (event) {
	var fecha= obtenerFecha();
	$("#numeracionCertificadoMedico").val(fecha);
	cargarArchivos('#cargarVeterinario',$("#numeracionCertificadoMedico").val(),'_certificadoVeterinario_');
});

$('#cargarAnticuerpos').click(function (event) {
	var fecha= obtenerFecha();
	$("#numeracionTitularAnticuerpo").val(fecha);
    cargarArchivos('#cargarAnticuerpos',$("#numeracionTitularAnticuerpo").val(),'_titularAnticuerpo_');
});

$('#cargarAutMinAmb').click(function (event) {
	var fecha= obtenerFecha();
	$("#numeracionAutMinAmb").val(fecha);
    cargarArchivos('#cargarAutMinAmb',$("#numeracionAutMinAmb").val(),'_autorizacionMinAmbiente_');
});

function cargarArchivos(button,numero,documento){
	var numero = numero;
	var usuario = $("#usuario").val();

    var boton = $(button);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF'){
    		subirArchivo(
	                archivo
	                , usuario+documento+numero
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, $("#no"))
	            );
    		$(archivo).removeClass("alertaCombo")
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
}

function obtenerFecha(){
	var fecha = new Date();
	var dd=("00" + fecha.getDate()).slice (-2);
	var mm=("00" + (fecha.getMonth()+1)).slice (-2);
	var yy=fecha.getFullYear();
	var hh=fecha.getHours();
	var mi=fecha.getMinutes();
	var ss=fecha.getSeconds();
	var fechaFinal=yy+"-"+mm+"-"+dd+"_"+hh+"-"+mi+"-"+ss;
	return fechaFinal;
}

function limpiarMascota(){
	$("#subTipoProducto").val("");
	$("#producto").val("");
	$("#sexo").val("");
	$("#raza").val("");
	$("#edad").val("");
	$("#color").val("");
	$("#identificacionProducto").val("");
}

function checkGuion(e) {
    tecla = (document.all) ? e.keyCode : e.which;

       if (tecla == 45) {
        return false;
    }
}

$("#nuevoRegistro").submit(function(event){
	event.preventDefault();
	$("#estado").html("");
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($.trim($("#tipoIdentificacion").val()) == "" ) {
		error=true;
		$("#tipoIdentificacion").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#telefonoPropietario").val()) == "" ) {
		error=true;
		$("#telefonoPropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if(!$.trim($("#correoPropietario").val()) || !esCampoValido("#correoPropietario")){
		error = true;
		$("#correoPropietario").addClass("alertaCombo");
	}

	if($.trim($("#nombrePropietario").val()) == "" ) {
		error=true;
		$("#nombrePropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#identificacionPropietario").val()) == "" ) {
		error=true;
		$("#identificacionPropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($("#identificacionPropietario").val() == "04" || $("#identificacionPropietario").val() == "05" || $("#identificacionPropietario").val() == "06"){
		if($("#identificacionPropietario").val().length != $("#identificacionPropietario").attr("maxlength")){
			$("#identificacionPropietario").addClass("alertaCombo");
			$("#estado").html("Por favor verifique la longitud del campo.").addClass('alerta');
		}
	}

	if($.trim($("#direccionPropietario").val()) == "" ) {
		error=true;
		$("#direccionPropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#nombreDestinatario").val()) == "" ) {
		error=true;
		$("#nombreDestinatario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#direccionDestinatario").val()) == "" ) {
		error=true;
		$("#direccionDestinatario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($("#pais").val() == "" ) {
		error=true;
		$("#pais").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta')
	}

	if($("#uso").val() == "" ) {
		error=true;
		$("#uso").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#fechaEmbarque").val()) == "" ) {
		error=true;
		$("#fechaEmbarque").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($("#puestoControl").val() == "" ) {
		error=true;
		$("#puestoControl").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	var rows = document.getElementById('tablaProductos').rows.length;

	if(rows==1){
		error=true;
		$("#estado").html("Debe agregar al menos una mascota para crear la solicitud.").addClass('alerta');
		$('#tablaProductos').addClass("alertaCombo");
	}
	
	if($.trim($("#rutaVacuna").val()) == "0" ) {
		error=true;
		$("#archivoVacuna").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#rutaVeterinario").val()) == "0" ) {
		error=true;
		$("#archivoVeterinario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if(!error){
		$("#nuevoRegistro").attr('data-destino', 'detalleItem');
		$("#nuevoRegistro").attr('data-opcion', 'guardarNuevaSolicitud');
		 ejecutarJson($(this));

		var estado = $("#estado").html().split('-');
		$("#idSolicitud").val(estado[0]);
		//abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		if($("#idSolicitud").val() != ''){
			$("#nuevoRegistro").attr('data-rutaAplicacion','mercanciasSinValorComercial');
			$("#nuevoRegistro").attr('data-opcion','mostrarComprobantePDF');
			$("#nuevoRegistro").attr('data-destino','detalleItem');
			abrir($("#nuevoRegistro"),event,false);
		}else{
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
		}
	}
});

$("#tipoIdentificacion").change(function(event){

	$("#identificacionPropietario").val('');

	if($('#tipoIdentificacion').val() == '04'){
		$("#identificacionPropietario").attr("maxlength","13");
		$("#clasificacion").val("Natural");
	}else if($('#tipoIdentificacion').val() == '05'){
		$("#identificacionPropietario").attr("maxlength","10");
		$("#clasificacion").val("Cédula");
	}else if($('#tipoIdentificacion').val() == '06'){
		$("#clasificacion").val("Pasaporte");
		$("#identificacionPropietario").attr("maxlength","20");
	}

});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

$("#identificacionPropietario").on('change',function(event){
	validarIdentificador();
});

$("#identificacionPropietario").keypress(function(event){

	if(event.keyCode == 32){
		return false;
	}

	if (tecla == 45) {
        return false;
    }

});

$("#identificacionPropietario").on('paste', function (event) {

	event.preventDefault();
	var clipboarddata =  window.event.clipboardData.getData('text');    
	var sinEspacios = clipboarddata.replace(/ /g, "");
	var sinGuion = sinEspacios.replace(/-/g, "");
	var maxlength = 16;

	if($('#tipoIdentificacion').val() == '04'){
		maxlength = 13;		
	}else if($('#tipoIdentificacion').val() == '05'){
		maxlength = 10;
	}else if($('#tipoIdentificacion').val() == '06'){
		maxlength = 20;
	}

	$(this).val(sinGuion.substring(0, maxlength));

	validarIdentificador();

});

function validarIdentificador(){
	if($("#tipoIdentificacion").val() != ''){
		$("#numero").val($("#identificacionPropietario").val());
		event.preventDefault();
		var $botones = $("#nuevoRegistro").find("button[type='submit']"),
		serializedData = $("#nuevoRegistro").serialize(),
		url = "aplicaciones/general/consultaWebServices.php";
		//url = "aplicaciones/general/consultaValidarIdentificacion.php";
		$botones.attr("disabled", "disabled");
		resultado = $.ajax({
			url: url,
			type: "post",
			data: serializedData,
			dataType: "json",
			async:   true,
			beforeSend: function(){
				$("#estado").html('').removeClass();
				$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			
			success: function(msg){
				if(msg.estado=="exito"){
					$botones.removeAttr("disabled");
				}else{
					mostrarMensaje(msg.mensaje,"FALLO");
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#cargando").delay("slow").fadeOut();
				mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
			},
			complete: function(){
				$("#cargando").delay("slow").fadeOut();
			}
		});
	}else{
		$("#estado").html("Por favor seleccione un tipo de identificación.").addClass('alerta');
	}
}

</script>