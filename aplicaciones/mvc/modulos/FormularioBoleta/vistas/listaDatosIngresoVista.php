<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/estilos/estiloapp.css'>
<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/estilos/bootstrap.min.css'>

</head>
<body >
<div class="container-fluid">
<div class="row">
 <div class="col-12 col-md-3">
    	<div id="busqueda">
    		<fieldset>
        			<p class="text-center h5"><strong>Formulario Declaración Jurada</strong></p>
        			<form id="buscarDeclaraciones" 
        				data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>FormularioBoleta' 
        				data-opcion='datosIngreso/listar'
        				data-destino="resultados">
        				<div class="form-group row">
            				<div class="col-12">
                				<label for="nombre">Nombres:</label> 
                				<input class="form-control form-control-sm" id="nombre" name="nombres" type="text" maxlength="30"/> 
                				<label for="apellidos">Apellidos:</label> 
                				<input class="form-control form-control-sm" id="apellidos" name="apellidos" type="text" maxlength="30"/>
                				<label for="identificador">Pasaporte / Cédula:</label> 
                				<input class="form-control form-control-sm" id="identificador" name="identificador" type="text" maxlength="30"/>
                				<label for="fecha_dia">Fecha (día):</label> 
                				<input class="form-control form-control-sm mb-3" id="fecha_dia" name="fecha_dia" type="date" maxlength="10"/>
                				<button  type="button" class="btnBuscar btn btn-secondary btn-sm btn-block" id="buscar">Buscar</button>
            				</div>
            	
        				</div>
        			</form>
    		</fieldset>
    		<div class="acerca">
    			<p>Sistema Gestionador Unificado de Información</p>
    			<p>Agrocalidad <?php echo date('Y');?></p>
    			<p>Gestión Tecnológica</p>
    		</div>
    	</div>
 </div>

 <div class="col-12 col-md-9">
	<div id="resultados">
			Ingrese los datos de busqueda.
	</div>
	</div>
</div>
	
</div>
</body>

<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/bootstrap.min.js" type="text/javascript"></script>

<script>
	$(document).ready(function () {
		
	});
	
	$("#buscar").click(function(e){
		var data=null;
		data = $("#buscarDeclaraciones").serialize();
		var elementoDestino = "#" + $("#buscarDeclaraciones").attr("data-destino");
		var url = "<?php echo URL ?>FormularioBoleta/datosIngreso/listar.php";
		$.ajax({
			type : "POST",
			url : url,
			data : data,
			dataType : "text",
			contentType : "application/x-www-form-urlencoded; charset=latin1",
			beforeSend : function() {
				$(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			success : function(html) {

				if(html !='error'){
					$(elementoDestino).html(html);
				}else{
					$(elementoDestino).html('<div class="alerta">No existen resultados para la búsqueda</div>');
					}
			},
			error : function(jqXHR, textStatus, errorThrown) {
				$(elementoDestino).html(
						"<div id='error'>¡Ups!... algo no anda bien.<br />"
								+ "Se produjo un " + textStatus + " "
								+ jqXHR.status
								+ ".<br />Disculpe los inconvenientes causados.</div>");
			}
		});
	});
	
		function mostrarFormulario(){
			$(".alertaCombo").removeClass("alertaCombo");
			$(".alerta").removeClass("alerta");
			var error = false;
			if(!$("input[name='item']").is(':checked') ){
	  			$("input[name='item']").addClass("alertaCombo");
	  			 error = true;
		  		}

			if (!error) {
        		var data=null;
        		var item = $('[name="item"]:checked').map(function(){return this.value;}).get();
        		var elementoDestino = "#mostrarFormulario";
        		var url = "<?php echo URL ?>FormularioBoleta/datosIngreso/listarFormulario.php";
        		$.ajax({
        			type : "POST",
        			url : url,
        			data : {item:item[0]},
        			dataType : "text",
        			contentType : "application/x-www-form-urlencoded; charset=latin1",
        			beforeSend : function() {
        				$(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
        			},
        			success : function(html) {
        					$(elementoDestino).html(html);
        			},
        			error : function(jqXHR, textStatus, errorThrown) {
        				$(elementoDestino).html(
        						"<div id='error'>¡Ups!... algo no anda bien.<br />"
        								+ "Se produjo un " + textStatus + " "
        								+ jqXHR.status
        								+ ".<br />Disculpe los inconvenientes causados.</div>");
        			}
        		});
		}else{
			$("#mostrarFormulario").html("Debe seleccionar un Item.").addClass("alerta");
		}
	}
</script>
