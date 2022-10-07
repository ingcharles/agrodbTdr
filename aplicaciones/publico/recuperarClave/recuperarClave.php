<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Documento de recuperación</title>
	<link href="estilos/estilo.css" rel="stylesheet" type="text/css">
	<link rel='stylesheet' href='../../general/estilos/agrodb.css'>
	<link rel='stylesheet' href='../../general/estilos/jquery-ui-1.10.2.custom.css'>

</head>

<body>
    <header>
    <div>
    <h2 class="encabezado1"><a href="#">CUENTA SISTEMA GUIA</a></h2>
    <!--  h2 class="encabezado2"> <a href="https://guia.agrocalidad.gob.ec/agrodb/ingreso.php">INICIAR SESIÓN</a></h2-->
	<h2 class="encabezado2"> <a href="http://181.112.155.173/agrodbPrueba/ingreso.php">INICIAR SESIÓN</a></h2>
    </div>
    </header>

   
     <form id="resetearClave" data-rutaAplicacion="../../../publico/recuperarClave" data-opcion="resetearClave">
     
     <input type="hidden" id="identificador" name="identificador" value="<?php echo $_GET['id'];?>">
     
	    <div id="main">
	    
	  		<p> 
	  			<h1>Restablecer la contraseña</h1>
	  		</p>
	   
	   
			<div id="contenedor">
	         	<h3>Escribe tu código de seguridad </h3>
	        	<input class="input_texto"type="text" id="codigo" name="codigo" maxlength="8">
	        	<div class="ayuda">* Ingresar el código que te hemos enviado al correo electrónico. </div>
	        </div>
	    
	    	<div id="contenedor">
		    	<div>Nueva contraseña</div>
	    		<input class="input_texto"type="password" id="claveUno" name="claveUno" data-er="(^(?=.*\d)(?=.*[\u0021-\u002f\u003a-\u0040\u005b-\u0060\u007b-\u007e])(?=.*[A-Z])(?=.*[a-z])\S{8,16}$)">
		    	<div class="ayuda">* Ingrese al menos 8 dígitos que incluyan al menos una letra mayúscula y un carácter especial cómo ^ ! @ # $ %.</div> 
	    	</div>
	  
	  		<div id="contenedor">
	    		<div>Confirmar contraseña</div>
	    		<input class="input_texto"type="password" id="claveDos" name="claveDos" data-er="(^(?=.*\d)(?=.*[\u0021-\u002f\u003a-\u0040\u005b-\u0060\u007b-\u007e])(?=.*[A-Z])(?=.*[a-z])\S{8,16}$)">
	    		<div class="ayuda">* Ingresar la misma contraseña que se ingreso en el campo anterior.</div> 
	    	</div>
	    	
	    	<div id="mensajeError"></div>
	  
	  
	  		<div class="boton">
	  			<button id="aceptar" name="aceptar" type="submit">Aceptar</button> 
	   			<button id="cancelar" type="button" name="cancelar">Cancelar</button>
	   		</div>
	     
		</div>

	</form>



<footer>
    <span class="pie">&copy; El Sistema Gestionador Unificado de Información para Agrocalidad -
     GUIA es un SII (Sistema de Información Integrado).</span>
    </footer>
</body>

	<script src="../../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="../../general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
	<script src="../../general/funciones/agrdbfunc.js" type="text/javascript"></script>
	<script src="../../general/funciones/jquery.inputmask.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function(){	
			$(document).bind("contextmenu",function(e){
				return false;
			});
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

$("#resetearClave").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	$("#mensajeError").html("");

	if(!$.trim($("#codigo").val()) || $("#codigo").val().length != $("#codigo").attr("maxlength")){
		error = true;
		$("#codigo").addClass("alertaCombo");
		$("#mensajeError").html("El código debe ser de 8 caracteres alfanuméricos.").addClass("mensajeErrorClave");
	}

	if(!$.trim($("#claveUno").val())){
		error = true;
		$("#claveUno").addClass("alertaCombo");
	}

	if(!$.trim($("#claveDos").val())){
		error = true;
		$("#claveDos").addClass("alertaCombo");
	}

	if(!esCampoValido("#claveUno") || $("#claveUno").val().length < 8){
		error = true;
		$("#claveUno").addClass("alertaCombo");
		$("#mensajeError").html("Las contraseñas ingresadas deben tener el formato indicado.").addClass("mensajeErrorClave");
	}

	if(!esCampoValido("#claveDos") || $("#claveDos").val().length < 8 ){
		error = true;
		$("#claveDos").addClass("alertaCombo");
		$("#mensajeError").html("Las contraseñas ingresadas deben tener el formato indicado.").addClass("mensajeErrorClave");
	}

	
	if($("#claveUno").val() != $("#claveDos").val()){
		error = true;
		$("#claveDos").addClass("alertaCombo");
		$("#claveUno").addClass("alertaCombo");
		$("#mensajeError").html("Las contraseñas no coinciden").addClass("mensajeErrorClave");
	}

	if (!error){
		 $("#resetearClave").attr('data-destino', 'mensajeError');
	    abrir($("#resetearClave"), event, false);	
	}	
});

	$("#cancelar").click(function(event){
		$(location).attr('href','http://181.112.155.173/agrodbPrueba'); //https://guia.agrocalidad.gob.ec
	});

</script>

</html>