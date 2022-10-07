<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cm = new ControladorMovilizacionAnimal();

?>
<header>
	<h1>Nuevo Activador de Certificados</h1>
</header>
	<form id='nuevoGeneradorCertificados' data-rutaAplicacion='movilizacionAnimal' data-opcion='guardarActivarCertificadosMovilizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>
	
		<fieldset id="seleccionarEmisor1">
		
			<legend>Activar Certificados de Movilización</legend>
			
			<div data-linea="1">
				<label>Digite serie a generar desde/hasta</label>								
			</div>
			<div data-linea="2">		
            	<label>Serie Desde:</label>
                	<input type="text" name="minimo" id="minimo" maxlength="9"  placeholder="Ej 000111111"  data-er="[0-9]" >  
            </div>
        	
        	<div  data-linea="2">		
           		<label>Serie Hasta:</label>            	
                	<input type="text" name="maximo" id="maximo" maxlength="9" placeholder="Ej 0009999999" data-er="[0-9]" >  
            </div>
           
	    </fieldset>

		<button id="btnGuardar" type="submit" name="btnGuardar">Activar Certificados</button>

  </form>

<script type="text/javascript">		

	$(document).ready(function(){			
		distribuirLineas();	
		construirValidador();
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

    $("#nuevoGeneradorCertificados").submit(function(event){
        
    	event.preventDefault();
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#minimo").val()=="" || $("#minimo").val()==0 || !esCampoValido("#minimo") || $("#minimo").val().length != $("#minimo").attr("maxlength")){
			error = true;
			$("#minimo").addClass("alertaCombo");
		}

		if($("maximo").val()=="" || $("#maximo").val()==0 || !esCampoValido("#maximo") || $("#maximo").val().length != $("#maximo").attr("maxlength")){
			error = true;
			$("#maximo").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson($("#nuevoGeneradorCertificados"));                   			      	
		}
					 		 			 		 	
	});


		
	
</script>