<?php

// header('Location: ../../../../agrodbOut.html');

/*
 * require_once '../../../clases/Conexion.php';
 * require_once '../../../clases/ControladorRequisitos.php';
 * require_once '../../../clases/ControladorCatalogos.php';
 * require_once '../../../clases/GoogleAnalitica.php';
 */

// $conexion = new Conexion();
// $cr = new controladorRequisitos();
// $cc = new controladorCatalogos();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' href='../../publico/estilos/estiloapp.css'>
<script src="../../general/funciones/jquery-1.9.1.js"
	type="text/javascript"></script>
<script src="../../general/funciones/agrdbfunc.js"
	type="text/javascript"></script>
<script src="../../general/funciones/jquery.numeric.js"
	type="text/javascript"></script>
<script src="../../general/funciones/jquery-ui-1.10.2.custom.js"
	type="text/javascript"></script>
</head>
<body id="paginabusqueda">

	<section id="busqueda">
		<fieldset>
			<legend style="text-align: center;">CERTIFICADO FITOSANITARIO</legend>
			<form id="consultaCertificadoFitosanitario"
				data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
				data-opcion='CertificadoFitosanitarioDatosPublicos/datosCosultaCertificadoFitosanitario' data-destino="resultadoConsultaCertificado">
				<pre></pre>
				<div>
					<label>Número de Certificado / Certificate Number</label> <input
						type="text" id="codigo_certificado" name="codigo_certificado"
						maxlength="21" class="validacion">
				</div>

				<div class="acerca">
					<p>Sistema Gestionador Unificado de Información</p>
					<p>Agrocalidad 2013</p>
					<p>Gestión Tecnológica</p>
				</div>

				<div>
					<button type="submit" id="buscar" name="buscar">BUSCAR</button>
				</div>
			</form>

		</fieldset>
	</section>
	<section id="resultadoConsulta">
		<div id="resultadoConsultaCertificado">
			Ingrese los datos de búsqueda en la parte izquierda.
		</div>
	</section>

	<section id="areaNotificacion">
		<div id="estado"></div>
	</section>
</body>

<script type="text/javascript">

    $("#consultaCertificadoFitosanitario").submit(function(event){
    	$(".alertaCombo").removeClass("alertaCombo");
    	$("#estado").html("").removeClass('alerta');
    	event.preventDefault();
    	var error = false;
    	var mensaje = "";

    	$('#consultaCertificadoFitosanitario .validacion').each(function(i, obj) {
        	if(!$.trim($(this).val())){
        		error = true;
        		$(this).addClass("alertaCombo");
        	}
    	});

    	if (!/^[0-9]{20}[P]{1}$/.test($("#codigo_certificado").val())) {	        
			error = true;
			mensaje = "El código registrado no posee el formato correcto";
			$("#codigo_certificado").addClass("alertaCombo");
	    }
    
    	if (!error){    		 
        	
			var codigo_certificado = $("#codigo_certificado").val();
    		    	
	    	$.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitarioDatosPublicos/datosCosultaCertificadoFitosanitario",
	                {
	    				codigo_certificado : codigo_certificado
	                }, function (data) {
	                    $("#resultadoConsultaCertificado").html(data);               
	       			});
     		 
    	}else{
    		$("#estado").html("Por favor revise los campos obligatorios. " + mensaje).addClass("alerta");
    	}	
    });
	
</script>
</html>

