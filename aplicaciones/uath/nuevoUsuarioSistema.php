<?php
session_start();
require_once '../../aplicaciones/uath/models/salidas.php';
?>

<header>
    <h1>Nuevo usuario del sistema</h1>
</header>
<div id="estado"></div>

<form id="nuevoUsuario" data-rutaAplicacion="uath" data-opcion="guardarUsuarioSistema" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
    <fieldset id="datosConsultaWebServices">
        <legend>Detalle</legend>
        <input id="clasificacion" name="clasificacion" type="hidden" value="Cédula" />
         <div data-linea="0">
            <label for="tipo">Tipo usuario</label>
            <select id="tipo" name="tipo">
            <option value="">Seleccione....</option>
            	<option value="Interno">Usuario interno</option>
            	<option value="Externo">Usuario civiles - profesionales</option>
            </select>
        </div>
        <div data-linea="1">
            <label for="numero">Tipo de identificación</label>
							
							<input type="radio" name="clasifi" id="Pasaporte" value="Pasaporte">
							<label>Pasaporte</label>
							<input type="radio" name="clasifi" id="cedula" value="Cédula">
							<label for="cedula">Cédula</label><br/>
        </div>
        <div data-linea="3">
         					<label for="numero">Número de identificación</label>
							<input name="numero" type="text" id="numero"  disabled="disabled" autocomplete="off" />
							<label id="lNumero"></label>
            				<input type="hidden" id="tipoSel" name="tipoSel" value=""/>
							<div id="estadoConsulta"></div>
        </div>
        <div data-linea="4">
            <label for="nombres">Nombres</label>
            <input id="nombres" name="nombre" type="text" readonly />
        </div>
        <div data-linea="5">
            <label for="apellidos">Apellidos</label>
            <input id="apellidos" name="apellido" type="text"  readonly/>
        </div>
        
        <div data-linea="6">
            <label for="mail_institucional">Correo institucional:</label>
            <input type="text" id="mail_institucional" name="mail_institucional" maxlength="256" data-er="^([\w-]+(?:\.[\w-]+)*)@(agrocalidad.gob.ec)$" />
        </div>
        <div data-linea="7">
            <label for="mail_personal">Correo personal:</label>
            <input type="text" id="mail_personal" name="mail_personal" maxlength="256" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
        </div>
        <div>
        <div id="mensajeCargando"></div>
            <button type="submit" class="guardar" id="btsave">Guardar</button>
        </div>
    </fieldset>
    
    <input type="hidden" id="identificador" name="identificador" />
    <input type="hidden" id="opcion" name="opcion" value="verificarMail" />
    <input type="hidden" id="valorUsuario" name="valorUsuario" />
    <input type="hidden" id="mail" name="mail" />
    <input type="hidden" id="origenSolicitud" name="origenSolicitud" value="catastro"/>
</form>



<script>
    $('document').ready(function() {
        distribuirLineas();
    });

    $("input[name=clasifi]").click(function () {    
        $('#tipoSel').val($(this).val());
    });

    function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

    $("#nuevoUsuario").submit(function(e) {
        e.preventDefault();
        $(".alertaCombo").removeClass("alertaCombo");
        var error = false;
        if ($.trim($("#detalleItem #numero").val()) == "" ) {
            error = true;
            $("#detalleItem #numero").addClass("alertaCombo");
        }
        if ($.trim($("#detalleItem #nombres").val()) == "" ) {
            error = true;
            $("#detalleItem #nombres").addClass("alertaCombo");
        }
        if ($.trim($("#detalleItem #apellidos").val()) == "" ) {
            error = true;
            $("#detalleItem #apellidos").addClass("alertaCombo");
        }
        if ($.trim($("#detalleItem #tipo").val()) == "" ) {
            error = true;
            $("#detalleItem #tipo").addClass("alertaCombo");
        }

        if ($.trim($("#mail_institucional").val()) == ""  || !esCampoValido("#mail_institucional")) {
        	if ($.trim($("#mail_personal").val()) == ""  || !esCampoValido("#mail_personal")) {
                error = true;
                $("#mail_institucional").addClass("alertaCombo");
                $("#mail_personal").addClass("alertaCombo");
            }
        }
        
        if (!error){
            
        	var respuesta = JSON.parse(ejecutarJson($("#nuevoUsuario")).responseText);

        	$("#opcion").val("verificarMail");
	    	$("#valorUsuario").val($("#tipo option:selected").val());
	    	$("#origenSolicitud").val("catastro");
			
        	if($("#mail_institucional").val() != ""){
				$("#mail").val($("#mail_institucional").val());
			}else if($("#mail_personal").val() != ""){
				$("#mail").val($("#mail_personal").val());
			}
        	
        	if(respuesta.estado === 'exito'){

        		$("#nuevoUsuario").attr('data-rutaAplicacion','publico/recuperarClave');
    			$("#nuevoUsuario").attr('data-opcion','opcionMail');
    			$("#nuevoUsuario").attr('data-destino','detalleItem');

    			var data = JSON.parse(ejecutarJson($("#nuevoUsuario")).responseText);

    			if(data.estado === 'exito'){
    				mostrarMensaje(data.mensaje,"EXITO");
    			}else{
    				mostrarMensaje(data.mensaje,"FALLO");
    			}
    			
			}else{
				$("#estado").html('No se ha podido generar el registro o el usuario ya se encuentra registrado.');
			}
        } else {
            mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");
        }
    });

    $("#cedula").change(function(){
    	$("#numero").val("");
    	$("#numero").numeric(false);
		$("#numero").removeAttr("disabled");
		$("#numero").attr("maxlength","10");
		var valor = $("#numero").val();
		$("#numero").val(valor.substring(0,10));
		$('#detalleItem #nombres').val('');
    	$('#detalleItem #apellidos').val('');
    	$('#detalleItem #nombres').attr('readonly', true);
    	$('#detalleItem #apellidos').attr('readonly', true);
	});

    $("#Pasaporte").change(function(){
    	$("#numero").val("");
    	$("#numero").numeric(true);
		$("#numero").removeAttr("disabled");
		$("#numero").attr("maxlength","13");
		var valor = $("#numero").val();
		$("#numero").val(valor.substring(0,13));
		$('#detalleItem #nombres').val('');
    	$('#detalleItem #apellidos').val('');
    	$('#detalleItem #nombres').attr('readonly', false);
    	$('#detalleItem #apellidos').attr('readonly', false);
    	$("#btsave").removeAttr("disabled");
	});
	
	$("#numero").change(function(event){

		event.preventDefault();
		$('#detalleItem #estadoConsulta').html('');
		
		if($("#tipoSel").val()== 'Cédula'){
    		$('#detalleItem #nombres').attr('readonly', true);
    	    $('#detalleItem #apellidos').attr('readonly', true);
    		var $botones = $("form").find("button[type='submit']"),
        	serializedData = $("#datosConsultaWebServices").serialize(),
        	url1 = "aplicaciones/general/consultaWebServices.php";
    	
    		    $botones.attr("disabled", "disabled");
    		     resultado = $.ajax({
    			    url: url1,
    			    type: "post",
    			    data: serializedData,
    			    dataType: "json",
    			    async:   true,
    			    beforeSend: function(){
    			    	$("#estado").html('').removeClass();
    			    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
    				},
    				
    			    success: function(msg){
    			    	if(msg.estado=="exito"){
    				    	$botones.removeAttr("disabled");
    				    	var stringParts = msg.valores.Nombre.split(" ");
    				    	var num = stringParts.length;
    				    	switch(num) {
    				    	case 2:
    				        	var nomb= stringParts[1];
    					    	var apell= stringParts[0];
    				            break;
    				        case 3:
    				        	var nomb= stringParts[1]+' '+stringParts[2];
    					    	var apell= stringParts[0];
    				            break;
    				        case 4:
    				        	var nomb= stringParts[2]+' '+stringParts[3];
    					    	var apell= stringParts[0]+' '+stringParts[1];
    				            break;
    				        case 5:
    				        	var nomb= stringParts[2]+' '+stringParts[3]+' '+stringParts[4];
    					    	var apell= stringParts[0]+' '+stringParts[1];
    				            break;
    				        case 6:
    				        	var nomb= stringParts[4]+' '+stringParts[5];
    					    	var apell= stringParts[0]+' '+stringParts[1]+' '+stringParts[2]+' '+stringParts[3];
    				            break;
    				        default:
    				        	var nomb= stringParts[2]+' '+stringParts[3];
    				    		var apell= stringParts[0]+' '+stringParts[1];  
    				    	} 
    				    	$('#detalleItem #nombres').val(nomb);
    				    	$('#detalleItem #apellidos').val(apell);
    						$("#detalleItem #identificador").val($("#numero").val());
    				    	
    			    	}else{
    			    		
    			    		mostrarMensaje(msg.mensaje,"FALLO");
    			    		$('#detalleItem #nombres').val('');
    				    	$('#detalleItem #apellidos').val('');
    				    	$("#detalleItem #identificador").val('');
    				    }
    			    		
    			   },
    			    error: function(jqXHR, textStatus, errorThrown){
    			    	$("#cargando").delay("slow").fadeOut();

    			    	mostrarMensaje("ERR: ERROR EN LA CONEXION A LOS SERVICIOS GUBERNAMENTALES..! ","FALLO");
    			    	$('#detalleItem #estadoConsulta').html('Error en la conexion con el registro civil intente mas tarde ó puede ingresar de forma manual los datos..!').addClass("alerta");
    			    	$('#detalleItem #nombres').val('');
    			    	$('#detalleItem #apellidos').val('');
    			    	$('#detalleItem #nombres').attr('readonly', false);
    			    	$('#detalleItem #apellidos').attr('readonly', false);
    			    	$botones.removeAttr("disabled");
    			    },
    		        complete: function(){
    		        	$("#cargando").delay("slow").fadeOut();
    		        }
    			});	

		}else{
			$("#detalleItem #identificador").val($("#detalleItem #numero").val());
		}
});
</script>