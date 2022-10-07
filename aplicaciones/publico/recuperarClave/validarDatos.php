<!DOCTYPE html>
<?php  
//header('Location: ../../../../agrodbOut.html');
?>
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
	    
	    <form id="validarDatos" data-rutaAplicacion="../../../publico/recuperarClave" data-opcion="validarDatosFormulario">
	
			<input type="hidden" id="opcion" name="opcion" />
			<input type="hidden" id="valorUsuario" name="valorUsuario" />
			<input type="text" id="origenSolicitud" name="origenSolicitud" value="recuperarClave" />
			
		    <div id="main">
				<p>
					<h1>Volver a tu cuenta</h1>
				</p>
		  			Podemos ayudarte a restablecer tu contraseña y la información de seguridad. Primero escribe el número de cédula de Identidad o RUC asociada al sistema GUIA y luego tu cuenta de correo electrónico.
		  
		  		<p>
		  			<h3>¿Cómo obtener tu código de seguridad? </h3>
		  		</p>
		  
		  		<div id="contenedor">
		   			 <p>Paso 1</p>
		    		<div>Ingresar su Cédula de identidad o RUC, con el que se registró en el Sistema GUIA.</div>
		    		<input class="input_texto" type="text" id="identificador" name="identificador">
		    		<div class="ayuda">* Usuario con el que accede al sistema GUIA.</div>
		    	</div>
	
		    
		    	<div id="resutladoVerificacion"></div>
		  
		  		<div class="boton">
		  			<button type="submit" id="enviar" name="enviar">Enviar código</button> 
		   			<button type="button" id="cancelar" name="cancelar">Cancelar</button> 
		   		</div>
		   		
		   		<div id="resultadoMail"></div>
		     
			</div>
		</form>
	
		<footer>
	    	<span class="pie">&copy; El Sistema Gestionador Unificado de Información para Agrocalidad - GUIA es un SII (Sistema de Información Integrado).</span>
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

		$("#identificador").change(function(event){	
			$("#validarDatos").attr('data-opcion', 'opcionMail');
		    $("#validarDatos").attr('data-destino', 'resutladoVerificacion');
		    $("#opcion").val('mail');
		    abrir($("#validarDatos"), event, false); 
		});
	
		$("#validarDatos").submit(function(event){
			event.preventDefault();
	
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;
	
			if(!$.trim($("#identificador").val())){
				error = true;
				$("#identificador").addClass("alertaCombo");
			}
	
			if(!$.trim($("#mail").val())){
				error = true;
				$("#mail").addClass("alertaCombo");
			}
	
			if (!error){
	
				$("#validarDatos").attr('data-opcion', 'opcionMail');
			    $("#validarDatos").attr('data-destino', 'resultadoMail');
				$("#opcion").val('verificarMail');
			    abrir($("#validarDatos"), event, false);	

			}	
		});

		$("#cancelar").click(function(event){
			$(location).attr('href','http://181.112.155.173/agrodbPrueba'); //https://guia.agrocalidad.gob.ec
		});

	</script>
	
</html>