<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificadorUsuario = $_SESSION['usuario'];

?>

<header>
	<h1>Registro Vigencia de Documento</h1>
</header>

<div id="estado"></div>

<form id="nuevoVigenciaDocumento" data-rutaAplicacion="administracionVigenciaDocumentos" ><!--   data-opcion="guardarNuevoVigenciaDocumento" data-accionEnExito="ACTUALIZAR" */-->

	<input type="hidden" id="opcion" name="opcion" value="">
	<input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="<?php echo $identificadorUsuario; ?>" >
		
	<fieldset>
		<legend>Datos Generales</legend>		
		<div data-linea="1">
            <label for="nombreVigencia">*Nombre vigencia: </label><input type="text" id="nombreVigencia" name="nombreVigencia" />
		</div>
		<div data-linea="2">
            <label for="etapaVigencia">*Etapa de uso: </label>
			<select id="etapaVigencia" name="etapaVigencia" >
				<option value="">Seleccione....</option>
				<option value="documental">Documental</option>
				<option value="inspeccion">Inspección</option>
				<option value="cargarRespaldo">Cargar Convenio</option>
			</select>
		</div>
		<hr/>
		<div data-linea="3">			
			<label for="tipoDocumento">*Tipo de Documento: </label>
			<select id="tipoDocumento" name="tipoDocumento" >
				<option value="">Seleccione....</option>
				<option value="CRO">Certificado de registro de operador</option>
				<option value="PF">Permiso de funcionamiento</option>
			</select>
		</div>
		<div data-linea="4">			
			<label for="area">*Área Temática: </label>
            <select id="area" name="area" >
				<option value="">Seleccione....</option>
				<option value="SV">Sanidad vegetal</option>
				<option value="SA">Sanidad animal</option>
				<option value="IAP">Registros de insumos agrícolas</option>
				<option value="IAV">Registros de insumos pecuarios</option>
				<option value="IAF">Registros de insumos fertilizantes</option>
				<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
				<option value="AI">Inocuidad de los alimentos</option>
				<option value="LT">Laboratorios</option>		
			</select>
		</div>
		<div data-linea="5" id="resultadoArea">			
			<label for="tipoOperacion">*Operación: </label>
			<select id="tipoOperacion" name="tipoOperacion" >
				<option value="">Seleccione....</option>
			</select>
		</div>		
		<div data-linea="6" id="resultadoTipoOperacion">			
			<label for="tipoProducto">Tipo Producto: </label>
			<select id="tipoProducto" name="tipoProducto" >
				<option value="">Seleccione....</option>
			</select>
		</div>	
		<div data-linea="7" id="resultadoTipoProducto">			
			<label for="subtipoProducto">Subtipo Producto: </label>
			<select id="subtipoProducto" name="subtipoProducto" >
				<option value="">Seleccione....</option>
			</select>
		</div>	
		<hr/>
		<div data-linea="8" id="resultadoSubtipoProducto"></div>
	
	</fieldset>	
		
	<div id="popup" style="display: none;">
    		<div class="content-popup">
		           <h5 class="aviso">Confirmación de configuración</h5>
		           <label><br/>Por favor confirme si los datos configurados son correctos, y dar click a confirmar. </label>
		           <div class="botonesAviso">
		           		<button type="submit" >Confirmar</button>
		           		<button id="close" type="button" >Cancelar</button>
		           </div>
	    	</div>    
		</div>

	<button type="submit" class="guardar">Guardar vigencia</button>
	
</form>		


<script type="text/javascript">

$(document).ready(function(){
	distribuirLineas();
	
	$("#productosAgregados").hide();

	$('#open').click(function(){
		$('#popup').fadeIn('slow');
		$('.popup-overlay').fadeIn('slow');
		$('.popup-overlay').height($(window).height());
		return false;
	});
	
    $('#close').click(function(){
        $('#popup').fadeOut('slow');
        $('.popup-overlay').fadeOut('slow');
        return false;
    });
    
});

$("#area").change(function(event){
	$("#productosAgregados").hide();	
	$("#resultadoSubtipoProducto").hide();
	$("#tipoProducto").val("");	
	$("#subtipoProducto").val("");		
	$("#nuevoVigenciaDocumento").attr('data-opcion','accionesVigenciaDocumento');
	$("#nuevoVigenciaDocumento").attr('data-destino','resultadoArea');
	$('#opcion').val('area');
	abrir($("#nuevoVigenciaDocumento"),event,false);		
});

$('#valorTiempoVigencia').change(function(event){
	
	if($('#valorTiempoVigencia').val() == 0){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
	}else if($('#valorTiempoVigencia').val() == 1){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoTiempoVigencia").append("<option value='anio'>Año</option>");
		$("#tipoTiempoVigencia").append("<option value='mes'>Mes</option>");
		$("#tipoTiempoVigencia").append("<option value='dia'>Día</option>");
	}else if($('#valorTiempoVigencia').val() > 1){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoTiempoVigencia").append("<option value='anio'>Años</option>");
		$("#tipoTiempoVigencia").append("<option value='mes'>Meses</option>");
		$("#tipoTiempoVigencia").append("<option value='dia'>Días</option>");
	}
	
});

$('#nuevoVigenciaDocumento').submit(function(event){
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;	

	if ($("#nombreVigencia").val() == "") {
		$("#nombreVigencia").addClass("alertaCombo");
		error = true;
	}

	if ($("#etapaVigencia").val() == "") {
		$("#etapaVigencia").addClass("alertaCombo");
		error = true;
	}

	if ($("#tipoDocumento").val() == "") {
		$("#tipoDocumento").addClass("alertaCombo");
		error = true;
	} 

	if ($("#area").val() == "") {
		$("#area").addClass("alertaCombo");
		error = true;
	} 

	if ($("#tipoOperacion").val() == "") {
		$("#tipoOperacion").addClass("alertaCombo");
		error = true;
	} 
		
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{	
		$('#nuevoVigenciaDocumento').attr('data-opcion','guardarNuevoVigenciaDocumento');  	
		$('#nuevoVigenciaDocumento').attr('data-destino','detalleItem');		
		ejecutarJsonMovilizacion($('#nuevoVigenciaDocumento'));
		                      
	}
	
});

function ejecutarJsonMovilizacion(form,metodoExito,metodoFallo){
	var $botones = $(form).find("button[type='submit']"),
    serializedData = $(form).serialize(),
    url = "aplicaciones/"+$(form).attr("data-rutaAplicacion")+"/"+$(form).attr("data-opcion")+".php";

	$botones.attr("disabled", "disabled");
    var resultado = $.ajax({
	    url: url,
	    type: "post",
	    data: serializedData,
	    dataType: "json",
	    async:   true,
	    beforeSend: function(){
	    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
	    	$("#estado").removeClass();
	    },
	    success: function(msg){
	    	if(msg.estado=="exito"){
			    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
			    	$("#detalleItem").html("<input type='hidden' id='" + msg.idVigenciaDocumento + "' data-rutaAplicacion='administracionVigenciaDocumentos' data-opcion='abrirVigenciaDocumento' data-destino='detalleItem'/>");	
			    	abrir($("#detalleItem input"), null, true);							
	    		if(metodoExito!=null){
	    			metodoExito.ejecutar(msg);
	    		} else {
	    			mostrarMensaje(msg.mensaje,"EXITO");
	    		}
	    		
	    	} else {
	    		if(metodoFallo!=null){
	    			metodoFallo.ejecutar(msg);
	    		} else {
	    			mostrarMensaje(msg.mensaje,"FALLO");
					if(typeof msg.error != "undefined"){
                        console.log(msg.error);
                    }
	    		}
	    	}
	   },
	   error: function(jqXHR, textStatus, errorThrown){
		   $("#cargando").delay("slow").fadeOut();
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    },
        complete: function(){
        	$("#cargando").delay("slow").fadeOut();
           $botones.removeAttr("disabled");
        }
	});
};

</script>