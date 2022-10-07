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
		<h1>Nueva Guía (GRE)</h1>
	</header>
	<div id="estado"></div>
	
	<form id="nuevoGuiaMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoGuiaMaterialPeligroso" >
		<input type="hidden" id="opcion" value="Nuevo" name="opcion" /> 
		<fieldset>
			<legend>Datos Guía (GRE)</legend>	
			
			<div data-linea="1">			
				<label>Nombre guía:</label> 
				<input type="text" id="nombreGuiaUno" name="nombreGuiaUno" placeholder="Ej: Sustancias Tóxicas" maxlength="512" />	
			</div>
				
			<div data-linea="2">			
				<label>Número guía:</label> 
				<input type="text" id="numeroGuiaUno" name="numeroGuiaUno" onkeypress='ValidaSoloNumeros()' placeholder="Ej: 100" maxlength="10"  data-er="^[0-9]+$"   />	
			</div>
			
			<div data-linea="3">
				<label>Guía:</label>
				<input type="hidden" class="rutaArchivo" id="archivo" name="archivo" value="0" />
				<input type="file" id="informe" class="archivo" name="informe" accept="application/msword | application/pdf | image/*"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/seguridadOcupacional/guias" >Subir archivo</button>
			</div>
		</fieldset> 
		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" > Guardar </button>
	</form>	
</body>

<script>

	$('document').ready(function(){
		distribuirLineas();	
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
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
		  };
	})();
	
	$('button.subirArchivo').click(function (event) {
		
		numero = Math.floor(Math.random()*100000000);	
	
		var fileName = $("#informe")[0].files[0].name;
		var nombreArchivo =  fileName.substring(0, fileName.lastIndexOf("."));
		nombreArchivo=normalize(nombreArchivo);
		
		var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombreArchivo+'_'+numero
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else { 
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        } 
    });

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}
	
	$("#nuevoGuiaMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		var errorNumero = false;
			if(!$.trim($("#nombreGuiaUno").val())){
				error = true;
				$("#nombreGuiaUno").addClass("alertaCombo");
			}
			
			if(!$.trim($("#numeroGuiaUno").val()) ){
				error = true;
				$("#numeroGuiaUno").addClass("alertaCombo");
			}

			if($.trim($("#numeroGuiaUno").val()) ){
				if(!esCampoValido("#numeroGuiaUno") ){
					error = true;
					errorNumero = true;
					$("#numeroGuiaUno").addClass("alertaCombo");
				}
			}
			
			if($("#archivo").val() == 0){
				error = true;
				$("#informe").addClass("alertaCombo");
			}
			
			if (error){
				$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
				if (errorNumero){
					$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
				}
			}else{
				ejecutarJson("#nuevoGuiaMaterialPeligroso");
				if( $('#estado').html()=='Los datos han sido ingresados satisfactoriamente' )
					$('#_actualizar').click();
			}
	});
</script>
</html>