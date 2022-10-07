<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();;
	$cc = new ControladorCatalogos();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Ingreso de Información de Pagos</h1>
	</header>
	<div id="estado"></div>
	<form id="nuevoGenerarPagosTesoreria" data-rutaAplicacion="serviciosLinea" data-opcion="guardarGFConfirmacionPagos" >
		<input type="hidden" id="opcion" value="Nuevo" name="opcion" /> 
		
		<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $_SESSION['usuario'];?>" />
		<fieldset>
			<legend>Carga de Matrices</legend>	
		
			<div data-linea="1">
				<label>Localización:</label> 
				<select name="localizacion" id="localizacion" >
					<option value="" >Seleccione...</option>
					<?php
					$area = array('Oficina Planta Central','Zona 1','Zona 2','Zona 3','Zona 4','Zona 5','Zona 6','Zona 7');
					for ($i=0; $i<sizeof($area); $i++)
						echo '<option value="'.$area[$i].'">'. $area[$i] . '</option>';
					?>
				</select>
			</div>
			<div data-linea="2">
				<label>Fecha: </label>
				<input type="text" id="fecha" name="fecha" readonly="readonly"  />
			</div>
			<div data-linea="3">
				<label>Archivo (.xls):</label>
				<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0" />
				<input type="file"  class="archivo" name="informe" accept="application/msexcel"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/serviciosLinea/archivosConfirmacionPagos" >Subir archivo</button>
			</div>
		</fieldset>
		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Guardar</button>
	</form>
</body>
<script>
	$('document').ready(function(){
		distribuirLineas();
	});

	$("#fecha").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$('button.subirArchivo').click(function (event) {
		numero = Math.floor(Math.random()*1000);
			var localizacion=$("#localizacion").val();
			var nombreArchivo=localizacion+'_'+$("#fecha").val()+'_'+numero;
			nombreArchivo=limpiarString(nombreArchivo);
			var boton = $(this);
	        var archivo = boton.parent().find(".archivo");
	        var rutaArchivo = boton.parent().find(".rutaArchivo");
	        var extension = archivo.val().split('.');
	        var estado = boton.parent().find(".estadoCarga");
	        if (extension[extension.length - 1].toUpperCase() == 'XLS') {
	            subirArchivo(
	                archivo
	                , nombreArchivo
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, $("no"))
	            );
	        } else { 
	            estado.html('Formato incorrecto, solo se admite archivos en formato xls');
	            archivo.val("");
	        }
	
    });

	function limpiarString(cadena){
		var specialChars = "!@#$^&%*()+=-[]\/{}|:<>?,.";
    	for (var i = 0; i < specialChars.length; i++) {
    		cadena= cadena.replace(new RegExp("\\" + specialChars[i], 'gi'), '');
    	}   
    	cadena = cadena.replace(/ /g,"_");
    	cadena = cadena.replace(/á/gi,"a");
    	cadena = cadena.replace(/é/gi,"e");
    	cadena = cadena.replace(/í/gi,"i");
    	cadena = cadena.replace(/ó/gi,"o");
    	cadena = cadena.replace(/ú/gi,"u");
    	cadena = cadena.replace(/ñ/gi,"n");
    	return cadena;
    }


	$("#nuevoGenerarPagosTesoreria").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#localizacion").val())){
			error = true;
			$("#localizacion").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione la localización.").addClass('alerta');
		}

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione la fecha de registro.").addClass('alerta');
		}

		if($("#archivo").val()==0){
			error = true;
			$(".archivo").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione el archivo con extensión .xls").addClass('alerta');	
		}

		if($("#localizacion").val()!="" && $("#fecha").val()!="" && $("#archivo").val()!=0){

			var data = new Array();
			data.push({
	    		name : 'fecha',
	    		value : $("#fecha").val()
	    	}, {
	    		name : 'localizacion',
	    		value : $("#localizacion").val()
	    	});
	    	
	    	url = "aplicaciones/serviciosLinea/verificarArchivo.php";
	    
	    	var pinNumber = $.ajax({
	      		type:'POST',
	      		url: url,
	      		data: data,
	      	    async: false
	      	}).responseText;
	      	
	      	if(pinNumber=='false'){
				var mensaje = confirm("Ya existe un archivo cargado previamente con la fecha seleccionada ¿Desea remplazar los datos?");
	    		if (!mensaje) {
		    		error = true;
		    		$("#estado").html("¡Haz denegado el mensaje los datos no han sido guardado!").addClass('alerta');
	    		}
	    	}
		}
	
		if (!error){
			ejecutarJson("#nuevoGenerarPagosTesoreria");
			if( $('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
				$('#_actualizarSubListadoItems').click();
		}
	});
</script>
</html>