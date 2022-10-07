<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Nuevo Pictograma de Riesgo</h1>
	</header>
	<div id="estado"></div>
	
	<form id="nuevoPictogramaRiesgoMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoPictogramaRiesgoMaterialPeligroso" >
		<input type="hidden" id="opcion" value="Nuevo" name="opcion" /> 
		<fieldset>
			<legend>Datos Pictograma de Riesgo</legend>	
			
			<div data-linea="1">			
				<label>Nombre pictograma:</label> 
				<input type="text" id="nombrePictogramaUno" name="nombrePictogramaUno" placeholder="Ej: Gases" maxlength="512" />	
			</div>

			<div data-linea="3">
				<label>Pictograma:</label>
				<input type="hidden" class="rutaArchivo" id="archivo" name="archivo" value="0" />
				<input type="file" id="informe" class="archivo" name="informe"  accept="image/png"  />
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/seguridadOcupacional/img/pictogramas" >Subir archivo</button>
			</div>
		</fieldset> 
		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" > Guardar </button>
	</form>
		
</body>
<script>

	$('document').ready(function(){
		distribuirLineas();	
	});

	var normalize = (function() {
		  var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç", 
		      to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
		      mapping = {};
		 
		  for(var i = 0, j = from.length; i < j; i++ )
		      mapping[ from.charAt( i ) ] = to.charAt( i );
		 
		  return function( str ) {
		      var ret = [];
		      for( var i = 0, j = str.length; i < j; i++ ) {
		          var c = str.charAt( i );
		          if( mapping.hasOwnProperty( str.charAt( i ) ) )
		              ret.push( mapping[ c ] );
		          else
		              ret.push( c );
		      }      
		      return ret.join( '' );
		  }
	})();

	$('button.subirArchivo').click(function (event) {
		
		numero = Math.floor(Math.random()*100000000);	
	
		var fileName = $("#informe")[0].files[0].name;
		var nombreArchivo =  fileName.substring(0, fileName.lastIndexOf("."));
		nombreArchivo=normalize(nombreArchivo);
		var boton = $(this);

		
		$("#archivo").val(nombreArchivo.replace(" ", "_"));
		nombreArchivo=nombreArchivo.replace(" ", "_");
		
		nombreArchivo=normalize(nombreArchivo);
		
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PNG') {

            subirArchivo(
                archivo
                , nombreArchivo.replace(" ", "_")+'_'+numero
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
            
        } else { 
            estado.html('Formato incorrecto, solo se admite archivos en formato PNG');
            archivo.val("");
        } 
    });

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}
	
	$("#nuevoPictogramaRiesgoMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

			if(!$.trim($("#nombrePictogramaUno").val())){
				error = true;
				$("#nombrePictogramaUno").addClass("alertaCombo");
			}
			
			if($("#archivo").val() == 0){
				error = true;
				$("#informe").addClass("alertaCombo");
			}
			
			if (error){
				$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
			}else{
				ejecutarJson("#nuevoPictogramaRiesgoMaterialPeligroso");
				if( $('#estado').html()=='Los datos han sido ingresados satisfactoriamente' )
					$('#_actualizar').click();
			}
	});
</script>
</html>