<?php 
session_start();

?>


<header>
	<h1>Nueva resolución</h1>
</header>

<div id="estado"></div>

<form id='nuevaResolucion' data-rutaAplicacion='resoluciones' data-opcion='guardarNuevaResolucion' data-destino="detalleItem">

	<input type="hidden" id="archivo1" name="archivo1" value="0"/> 
	<input type="hidden" id="archivo2" name="archivo2" value="0"/> 

	<fieldset>
		<legend>Información resolución</legend>
			
			<div data-linea="1">			
				<label>Número resolución</label> 
				<input id="numeroResolucion" name="numeroResolucion" required="required"></input>
			</div>
			<div data-linea="1">
				<label>Fecha</label> 
				<input id="fecha" name="fecha" required="required"></input> 
			</div>
			<div data-linea="2">
				<label>Nombre</label> 
				<input id="nombre" name="nombre" required="required"></input>
			</div>
			
			<div data-linea="2">
			<label>Estado</label> 
				<select name="estado">
					<option value="Vigente" selected="selected">Vigente</option>
					<option value="No vigente">No vigente</option>
					<option value="Reformado">Reformado</option>
					<option value="Derogado">Derogado</option>
				</select>
			</div>

			<div data-linea="3">
				<label>Observación</label> 
				<input id="observacion" name="observacion"></input>
			</div>
	</fieldset>
	
	
	<fieldset id="subirArchivo">
		<legend>Subir archivo</legend>
			<input type="file" name="archivoResolucion" id="archivoResolucion" accept="application/pdf"/>
	</fieldset>

	<fieldset id="subirAnexo">
		<legend>Subir anexo</legend>
			<input type="file" name="archivoAnexo" id="archivoAnexo" accept="application/pdf"/> 
	</fieldset>

	<button type="submit" class="guardar">Guardar resolución</button>

</form>


<!-- /body-->

<script type="text/javascript">

$("#fecha").datepicker({
    changeMonth: true,
    changeYear: true
  });


$('#archivoResolucion').change(function(event){
	
	$("#estado").html('');
	var archivo = $("#archivoResolucion").val();
	var extension = archivo.split('.');

	if(extension[extension.length-1].toUpperCase() == 'PDF'){
		if($("#numeroResolucion").val() !="" && $("#fecha").val() !=""){
	  		subirArchivo('archivoResolucion',$("#numeroResolucion").val()+'_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/resoluciones/archivosResolucion', 'archivo1');
		}else {
			$("#estado").html('Ingrese un número de resolución y/o fecha de emisión del documento.').addClass("alerta");
			$('#archivoResolucion').val('');
		}
	}else{
		$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		$('#archivoResolucion').val('');
	}
	
});

$('#archivoAnexo').change(function(event){
	
	$("#estado").html('');
	var archivo = $("#archivoAnexo").val();
	var extension = archivo.split('.');

	if(extension[extension.length-1].toUpperCase() == 'PDF'){
		if($("#numeroResolucion").val() !="" && $("#fecha").val() !="" ){
	  		subirArchivo('archivoAnexo',$("#numeroResolucion").val()+'_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/resoluciones/archivosAnexos', 'archivo2');
		}else {
			$("#estado").html('Ingrese un número de resolución y/o fecha de emisión del documento.').addClass("alerta");
			$('#archivoAnexo').val('');
		}
	}else{
		$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		$('#archivoAnexo').val('');
	}
});

$(document).ready(function(){
	distribuirLineas();
});

$("#nuevaResolucion").submit(function(event){
	  event.preventDefault();
	  if($('#archivo1').val()!="0"){
	   	abrir($(this),event,false);
	  }else{
		  $("#estado").html('Por favor cargue el archivo de resolución').addClass("alerta");
	  } 
});
      
</script>


