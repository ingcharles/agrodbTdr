<?php 

require_once '../../../clases/Conexion.php';
require_once '../../../clases/Constantes.php';
require_once '../../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$controlador = new ControladorCatalogos();
$constg = new Constantes();

$canton=  $controlador->listarSitiosLocalizacion($conexion, 'CANTONES');
$parroquia = $controlador->listarSitiosLocalizacion($conexion, 'PARROQUIAS');
//header('Location: ../../../../agrodbOut.html');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Panel de control GUIA</title>
		<link rel='stylesheet' href='../../general/estilos/estiloSolicitud.css'>
		<link rel='stylesheet' href='../../general/estilos/jquery-ui-1.10.2.custom.css'>
			
	</head>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-97784251-2"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-97784251-2');
	</script>
	<body>

	<a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/ManualInscripcionRegistroOperador.pdf" target="_blank"><img class="ayuda" src="../img/ayudaRegistroOperador.png" /></a>
    <a href="https://guia.agrocalidad.gob.ec/tutoriales/pdf/ManualInscripcionRegistroOperador.pdf" target="_blank"><footer class="ayuda1">Ayuda</footer></a>
			
		<section id="formulario">
			<header>
				<img src="../../general/img/MAG-ICO.png" /> 
				<img src="../../general/img/AGR-ICO.png" />
			</header>
				<h1>Inscripción de Operador</h1>
				<h2><?php echo $constg::NOMBRE_INSTITUCION;?></h2> 
				<p class="nota">La información ingresada en este formulario servirá para registrarse en sistema informático y acceder a los servicios de
					AGROCALIDAD. La información es de carácter confidencial y de uso exclusivo para la institución y el usuario dueño de los datos.</p>
							
				<form id="registroOperador" data-rutaAplicacion="../../../publico/registroOperador" data-opcion="guardarRegistroOperador">
				
					<fieldset id="datosConsultaWebServices">
						<legend>Tipo de identificación</legend>
						<div id="clasificacion">
							<input type="radio" name="clasificacion" id="personaNatural" value="Natural">
							<label for="personaNatural">RUC - Persona natural</label><br/>
							<input type="radio" name="clasificacion" id="personaJuridica" value="Juridica">
							<label for="personaJuridica">RUC - Persona jurídica</label><br/>
							<input type="radio" name="clasificacion" id="sociedadPublica" value="Publica">
							<label for="personaJuridica">RUC - Sociedad Pública</label><br/>
							<input type="radio" name="clasificacion" id="cedula" value="Cédula">
							<label for="cedula">Cédula</label><br/>
							<input name="numero" type="text" id="numero" placeholder="Número de identificación" disabled="disabled" data-er="^[0-9]+$" />
							<label id="lNumero"></label>
						</div>
						
						<input type="hidden" id="tipo" name="tipo" value=""/>
						
						<div id="estado"></div>
						
					</fieldset>
					
					
					
					<fieldset>
						<legend>Datos generales</legend>
						
						<div>
							<label for="razon" class="opcional">Razón social</label> 
							<input name="razon" type="text" id="razon" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" readonly maxlength="250" autocomplete="off"/>
							<div id="lRazon"></div>
							
							<input type="hidden" id="razonSocial" name="razonSocial" value=""/>
						</div>
						
						<label for="nombreLegal" style="padding-right: 13px;">Representante legal</label> 
						<input name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres" maxlength="200" readonly data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" autocomplete="off"/> 
						<input name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" maxlength="250" readonly data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" autocomplete="off"/>
						<div id="lNombreLegal"></div>
						<div id="lApellidoLegal"></div>
						
						<label for="nombreTecnico">Representante técnico</label> 
						<input name="nombreTecnico" type="text" id="nombreTecnico" placeholder="Nombres" maxlength="200" readonly data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" autocomplete="off"/> 
						<input name="apellidoTecnico" type="text" id="apellidoTecnico" placeholder="Apellidos" maxlength="250" readonly data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" autocomplete="off"/>
						<div id="lNombreTecnico"></div>
						<div id="lApellidoTecnico"></div>
					</fieldset>
					
					<fieldset id="validarWebservice">
						<legend>Verificación de datos</legend>
						<label for="pregunta1" id="pregunta1l" style="padding-right: 13px;">nombres aleatorios</label> 
						<input name="pregunta1" type="text" id="pregunta1" placeholder="" maxlength="400" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" /> 
						<div id="lpregunta1"></div>
						
						<label for="pregunta2" id="pregunta2l">nombres aleatorios</label> 
						<input name="pregunta2" type="text" id="pregunta2" placeholder="" maxlength="400" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" /> 
						<div id="lpregunta2"></div>
						<button id="validarDatos" type="Button">Validar</button>
						<div id="lvalidarDatos"></div>
					</fieldset>
					
					<fieldset id="datosOficina">
						<legend>Datos de oficina</legend>
						
						<div>
							<label for="provincia">Provincia</label>
							<select name="provincia" id="provincia">
								<option value="">Provincia....</option>
								<?php 
									$provincias = $controlador->listarSitiosLocalizacion($conexion, 'PROVINCIAS');
									
									foreach ($provincias as $provincia){
										echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
									}
								?>
							</select>
						</div>
						<div id="lProvincia"></div>
						
						<div>
							<label for="canton">Cantón</label>
							<select name="canton" id="canton" disabled="disabled">
								<option value="">Cantón....</option>
							</select>
						</div>
						<div id="lCanton"></div>
						
						<div>
							<label for="parroquia">Parroquia</label>
							<select name="parroquia" id="parroquia" disabled="disabled">
								<option value="">Parroquia....</option>
							</select>
						</div>
						<div id="lParroquia"></div>
						
						<div>
							<label for="direccion">Dirección</label> 
							<input name="direccion" type="text" id="direccion" class="cuadroTextoCompleto" maxlength="200" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$" />
							<div id="lDireccion"></div>
						</div>
						
						<div>
							<label for="telefono1">Teléfonos</label> 
							<input name="telefono1" type="text" id="telefono1" placeholder="Principal" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" /> 
							<input name="telefono2" type="text" id="telefono2" placeholder="Secundario" maxlength="50" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" />
							<span class="ejemplo">Ej.: (00) 000-0000</span>
							<div id="lTelefono1"></div>
							<div id="lTelefono2"></div>
						</div>
						
						<div>
							<label for="celular1">Celular</label> 
							<input name="celular1" type="text" id="celular1" placeholder="Principal" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="15" /> 
							<input name="celular2" type="text" id="celular2" placeholder="Secundario" maxlength="30" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="15" /> 
							<span class="ejemplo">Ej.: (00) 0000-0000</span>
							<div id="lCelular1"></div>
							<div id="lCelular2"></div>
						</div>
						
						<div>
							<label for="fax">Fax</label> 
							<input name="fax" type="text" id="fax" placeholder="Principal" maxlength="30" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}" data-inputmask="'mask': '(99) 999-9999'" size="15" /> 
							<span class="ejemplo">Ej.: (00) 000-0000</span>
							<div id="lFax"></div>
						</div>
						
						<div>
							<label for="correo">Correo electrónico</label> 
							<input name="correo" type="text" id="correo" class="cuadroTextoCompleto" maxlength="128" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
							<div id="lCorreo"></div>
						</div>
						
					</fieldset>
					
					<fieldset id="claveAcceso">
						<legend>Clave de acceso</legend>
						
						<div>
							<p class="ejemplo">Ingrese al menos 8 digitos que incluyan al menos una letra mayuscula y un caracter especial cómo ^ ! @ # $ %.</p>
							<label for="clave1">Ingrese su clave</label> 
							<input name="clave1" type="password" id="clave1" maxlength="256" data-er="(?=[^A-Z]*[A-Z])(?=[^!@#\$%]*[!@#\$%])" />
						</div>
						
						<div>
							<label for="clave2" style="padding-right:2px;">Confirmar clave</label> 
							<input name="clave2" type="password" id="clave2" maxlength="256"  />
						</div>
						
						<div id="lClave"></div>
					</fieldset>
					
					<fieldset id="codigoVerificacion">
						<legend>Código de verificación</legend>
						<button id="enviarCodigo" type="button">Enviar código</button><br>
						<div id="mensajeCodigoVerf"></div>
						<div><br>
							<label for="codigoVerifi">Ingresar código</label> 
							<input name="codigoVerifi" type="text" id="codigoVerifi" maxlength="8" disabled data-er="(?=[^A-Z]*[A-Z])(?=[^!@#\$%]*[!@#\$%])" />
						    <div id="lcodigoVerificacion"></div>
						</div>
						<br><br>
						<div>
						<label for="terminosCondiciones"> <a href="../../publico/registroOperador/terminos_condiciones.pdf" target="_blank">Términos y condiciones</a>
							</label><br> 
						<input name="terminoCondi" type="checkbox" id="terminoCondi"/>
							<label for="terminosCondiciones">Acepto términos y condiciones</label> 
						</div>
						<br><br>
					
					<button id="enviarDatos" type="submit">Enviar Datos</button>
					</fieldset>
					<div id="mensajeCargando"></div>				 
				</form>
				
				
		</section>
	</body>
	
	<script src="../../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="../../general/funciones/jquery-ui-1.10.2.custom.js" type="text/javascript"></script>
	<script src="../../general/funciones/agrdbfunc.js" type="text/javascript"></script>
	<script src="../../general/funciones/jquery.inputmask.js" type="text/javascript"></script>
	<script type="text/javascript">
		var canton = <?php echo json_encode($canton);?>;
		var parroquia = <?php echo json_encode($parroquia);?>;
		var cedul= 0;
		var idCrearOperador=0;

		$(document).ready(function(){
			construirValidador();
			$("#enviarDatos").attr("disabled", true);
			$("#terminoCondi").attr("disabled", true);				
			$(document).bind("contextmenu",function(e){
			    return false;
			});
			limpiarBusqueda();
		});

		$("input[name=clasificacion]").click(function () {    
	        $('#tipo').val($(this).val());
			$('#nombreLegal').val('');
		    $('#apellidoLegal').val('');
		    $("#razon").val('');
		    $("#numero").val('');
		    $("#lNumero").text('');
		    limpiarBusqueda();
	    });
	    
		$("#provincia").change(function(){
			scanton ='0';
			scanton = '<option value="">Cantón...</option>';
			for(var i=0;i<canton.length;i++){
			      if ($("#provincia").val()==canton[i]['padre']){
			      	scanton += '<option value="'+canton[i]['codigo']+'">'+canton[i]['nombre']+'</option>';
			      }
			}
			$('#canton').html(scanton);
			$("#canton").removeAttr("disabled");
		});
	
		$("#canton").change(function(){
			  sparroquia ='0';
			  sparroquia = '<option value="">Parroquia...</option>';
			     for(var i=0;i<parroquia.length;i++){
			      if ($("#canton").val()==parroquia[i]['padre']){
			       sparroquia += '<option value="'+parroquia[i]['codigo']+'">'+parroquia[i]['nombre']+'</option>';
			       } 
			     }
	
			  $('#parroquia').html(sparroquia);
			  $("#parroquia").removeAttr("disabled");
		});
	
		$("#cedula").change(function(){
			$("#razon").val("");
			$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
			$("#numero").removeAttr("disabled");
			$("#numero").attr("maxlength","10");
			$("#lNumero").text('');
			var valor = $("#numero").val();
			$("#numero").val(valor.substring(0,10));

			$("#razonSocial").val($("#razon").val());
		});
	
		$("#personaNatural").change(function(){
			$("#razon").removeAttr("disabled");
			if($("#razon").val()==''){
				if($("#apellidoLegal").val()!='' || $("#nombreLegal").val()!=''){
					$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
				}
			}
			$("#lNumero").text('');
			$("#razonSocial").val($("#razon").val());

			$("#numero").removeAttr("disabled");
			$("#numero").attr("maxlength","13");
		});
		
		$("#personaJuridica").change(function(){
			$("#razon").removeAttr("disabled");
			$("#razon").val("");
			$("#numero").removeAttr("disabled");
			$("#numero").attr("maxlength","13");
			$("#lNumero").text('');
			$("#razonSocial").val($("#razon").val());
		});
	
		$("#sociedadPublica").change(function(){
			$("#razon").removeAttr("disabled");
			$("#razon").val("");
			$("#numero").removeAttr("disabled");
			$("#numero").attr("maxlength","13");
			$("#lNumero").text('');
			$("#razonSocial").val($("#razon").val());
		});
		
		$("#registroOperador").submit(function(event){
			event.preventDefault();
			chequearCampos(this);
		});
	
		function esCampoValido(elemento){
			var patron = new RegExp($(elemento).attr("data-er"),"g");
			return patron.test($(elemento).val());
		}
	
		function chequearCampos(form){
			$(".alerta").removeClass("alerta");
			quitarAlertas();
			var error = false;
			if($("input:radio[name=clasificacion]:checked").val() == null){
				error = true;
				$("#clasificacion label").addClass("alerta");
				
			}
			if(!$.trim($("#numero").val()) || !esCampoValido("#numero") || $("#numero").val().length != $("#numero").attr("maxlength")){
				error = true;
				$("#numero").addClass("alerta");
				$("#lNumero").text('El valor ingresado no es correcto.').addClass("alerta");
			}
	
			if($("#personaJuridica").is(':checked') && (!$.trim($("#razon").val()))){
				error = true;
				$("#razon").addClass("alerta");
				$("#lRazon").text('El nombre ingresado no es correcto.').addClass("alerta");
			}
	
			if(!$.trim($("#nombreLegal").val()) || !esCampoValido("#nombreLegal")){
				error = true;
				$("#nombreLegal").addClass("alerta");
				$("#lNombreLegal").text('El nombre del representante legal no es correcto.').addClass("alerta");
			}
	
			if(!$.trim($("#apellidoLegal").val()) || !esCampoValido("#apellidoLegal")){
				error = true;
				$("#apellidoLegal").addClass("alerta");
				$("#lApellidoLegal").text('El apellido del representante legal no es correcto.').addClass("alerta");
			}
	
			if(!$.trim($("#direccion").val()) || !esCampoValido("#direccion")){
				error = true;
				$("#direccion").addClass("alerta");
				$("#lDireccion").text('La dirección ingresada no es correcta.').addClass("alerta");
			}

			if(!$.trim($("#provincia").val())){
				error = true;
				$("#provincia").addClass("alerta");
				$("#lProvincia").text('Debe seleccionar una provincia.').addClass("alerta");
			}
			
			if(!$.trim($("#canton").val())){
				error = true;
				$("#canton").addClass("alerta");
				$("#lCanton").text('Debe seleccionar un cantón.').addClass("alerta");
			}

			if(!$.trim($("#parroquia").val())){
				error = true;
				$("#parroquia").addClass("alerta");
				$("#lParroquia").text('Debe seleccionar una parroquia.').addClass("alerta");
			}
	
			if(!$.trim($("#telefono1").val()) || !esCampoValido("#telefono1")){
				error = true;
				$("#telefono1").addClass("alerta");
				$("#lTelefono1").text('El teléfono ingresado no posee el formato correcto.').addClass("alerta");
			}
	
			if($("#telefono2").val().length!=0 && !esCampoValido("#telefono2")){
				error = true;
				$("#telefono2").addClass("alerta");
				$("#lTelefono2").text('El teléfono ingresado no posee el formato correcto.').addClass("alerta");
			}
	
			if(!$.trim($("#celular1").val()) || !esCampoValido("#celular1")){
				error = true;
				$("#celular1").addClass("alerta");
				$("#lCelular1").text('El celular ingresado no posee el formato correcto.').addClass("alerta");
			}
	
			if($("#celular2").val().length!=0 && !esCampoValido("#celular2")){
				error = true;
				$("#celular2").addClass("alerta");
				$("#lCelular2").text('El celular ingresado no posee el formato correcto.').addClass("alerta");
			}

			if($("#fax").val().length!=0 && !esCampoValido("#fax")){
				error = true;
				$("#fax").addClass("alerta");
				$("#lFax").text('El fax ingresado no posee el formato correcto.').addClass("alerta");
			}
			
			if(!$.trim($("#correo").val()) || !esCampoValido("#correo")){
				error = true;
				$("#correo").addClass("alerta");
				$("#lCorreo").text('El correo electrónico ingresado no posee el formato correcto.').addClass("alerta");
			}
	
			if($("#clave1").val() != $("#clave2").val() || !esCampoValido("#clave1") || $("#clave1").val().length < 8){
				error = true;
				$("#clave1").addClass("alerta");
				$("#clave2").addClass("alerta");
				$("#lClave").text('Las contraseñas ingresadas deben coincidir y tener el formato indicado.').addClass("alerta");
			}
			if(!$.trim($("#codigoVerifi").val())){
				error = true;
				$("#codigoVerifi").addClass("alerta");
				$("#lcodigoVerificacion").text('Ingresar el código enviado al mail registrado...!').addClass("alerta");
			}			
			if (!error){
				$("#razon").removeAttr("disabled");
				$("#correo").removeAttr("disabled");
				ejecutarJson(form);
				if(($("#estado").html())=="Error codigo de verificación."){
				}else if(($("#estado").html())=="Los datos han sido ingresados satisfactoriamente."){
					alert("Su usuario ha sido creado, por favor inicie sesión con su número de identificación y contraseña ingresada.");
					var url = "../../../index.php";
					$(location).attr('href',url); 
				}else if(($("#estado").html())=="El operador ya se encuentra registrado en Agrocalidad."){
					$("#estado p").html('El operador ya se encuentra registrado en Agrocalidad.');
				}
			}
		}

		function quitarAlertas(){
				$("#lNumero").text('');
				$("#lRazon").text('');
				$("#lNombreLegal").text('');
				$("#lApellidoLegal").text('');
				$("#lDireccion").text('');
				$("#lProvincia").text('');
				$("#lCanton").text('');
				$("#lParroquia").text('');
				$("#lTelefono1").text('');
				$("#lTelefono2").text('');
				$("#lCelular1").text('');
				$("#lCelular2").text('');
				$("#lFax").text('');
				$("#lCorreo").text('');
				$("#lClave").text('');
				$("#lcodigoVerificacion").text('');
				$("#mensajeCodigoVerf").text('');
		}

		$("#numero").change(function(event){
			$(".alerta").removeClass("alerta");
			$("#lNumero").text('');
			quitarAlertas();
			if(!$.trim($("#numero").val()) || !esCampoValido("#numero") || $("#numero").val().length != $("#numero").attr("maxlength")){
				$("#numero").addClass("alerta");
				$("#lNumero").text('El valor ingresado no es correcto.').addClass("alerta");
			}else{
				event.preventDefault();
				var $botones = $("form").find("button[type='submit']"),
				serializedData = $("#datosConsultaWebServices").serialize(),
				url = "../../publico/registroOperador/verificarIdentificadorNuevoUsuario.php";
				$botones.attr("disabled", "disabled");
				$('#nombreLegal').attr('readonly', true);	
        	    $('#apellidoLegal').attr('readonly', true);	
        	    $('#nombreLegal').attr('readonly', true);	
        	    $('#apellidoLegal').attr('readonly', true);	
				
			 resultado = $.ajax({
				url: url,
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
    		    		arrayConsul=msg.valores;
    			    	$("#nombreTecnico").attr('readonly', false);
    			    	$("#apellidoTecnico").attr('readonly', false);
    			    	preguntas(msg.valores);
    			    	idCrearOperador=msg.valores.id;
    			    	if($("input:radio[name=clasificacion]:checked").val() == 'Cédula'){
    			    		$('#nombreLegal').val(msg.valores.nombres);
    				    	$('#apellidoLegal').val(msg.valores.apellidos);
    			    	}else{
    			    		$('#nombreLegal').attr('readonly', false);
    				    	$('#apellidoLegal').attr('readonly', false);
    				    	}
    			    	$("#validarWebservice").show();
    			    	$("#validarDatos").attr('disabled',false);
    			    	$("#razonSocial").val(msg.valores.razon);
    			    	$("#razon").val(msg.valores.razon);
    		    	}else{
    		    		mostrarMensaje(msg.mensaje,"FALLO");
    		    		idCrearOperador=0;
    		    		limpiarBusqueda();
    			    }
    		    		
    		   },
    		    error: function(jqXHR, textStatus, errorThrown){
    		    	$("#cargando").delay("slow").fadeOut();
    		    	mostrarMensaje("Error en la conexión a los servicios gubernamentales, por favor intente más tarde. ","FALLO");
    		    	$('#nombreLegal').val('');
    			    $('#apellidoLegal').val('');
    		    },
    	        complete: function(){
    	        	$("#cargando").delay("slow").fadeOut();
    	        }
    		});
		}
	});

		$("#nombreLegal").change(function(event){
			if($("input:radio[name=clasificacion]:checked").val() == 'Cédula'){
				$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
			}else if($("input:radio[name=clasificacion]:checked").val() == 'Natural'){
				if($("#razon").val() == ''){
					$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
				}
			}	

			$("#razonSocial").val($("#razon").val());
		});

		$("#apellidoLegal").change(function(event){
			if($("input:radio[name=clasificacion]:checked").val() == 'Cédula'){
				$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
			}else if($("input:radio[name=clasificacion]:checked").val() == 'Natural'){
				if($("#razon").val() == ''){
					$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
				}else if($("#razon").val()==(' '+$("#nombreLegal").val())){
					$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
				}
			}		

			$("#razonSocial").val($("#razon").val());
		});

		$("#razon").change(function(event){
			if($("input:radio[name=clasificacion]:checked").val() == 'Natural'){
				if($("#razon").val() == '' || $("#razon").val() == ' ' ){
					if($("#apellidoLegal").val() != '' || $("#nombreLegal").val()!=''){
						$("#razon").val($("#apellidoLegal").val()+' '+$("#nombreLegal").val());
					}
				}
			}	

			$("#razonSocial").val($("#razon").val());
		});
		
		function preguntas(valor){
			    $("#pregunta1l").html(valor.pregunta1+': ');
			    $("#pregunta1").attr('placeholder',valor.descripPregunta1);
			    $("#pregunta1").val('');
			    $("#pregunta1").attr('name',valor.idPregunta1);
			   
		    	$("#pregunta2l").html(valor.pregunta2+': ');
		    	$("#pregunta2").attr('placeholder',valor.descripPregunta2);
		    	$("#pregunta2").attr('name',valor.idPregunta2);
		    	$("#pregunta2").val('');
		   }
		   
	function limpiarBusqueda(){
		$('#nombreLegal').val('');
    	$('#apellidoLegal').val('');
    	$("#datosOficina").hide();
		$("#claveAcceso").hide();
		$("#codigoVerificacion").hide();
		$("#enviarDatos").hide();
		$("#validarWebservice").hide();
		$("#codigoVerifi").val('');
	}
	
	$("#terminoCondi").click(function(event){
    		if( $(this).prop('checked') ) {
    			$("#enviarDatos").removeAttr("disabled");
    		}else{
    			$("#enviarDatos").attr("disabled", "disabled");
    		}
	});
	
	$("#validarDatos").click(function(event){
		    $(".alerta").removeClass("alerta");
		    $("#lvalidarDatos").text('');
		    $("#lpregunta1").text('');
		    $("#lpregunta2").text('');
		    event.preventDefault();
        	url = "../../publico/registroOperador/validarPreguntas.php";
         resultado = $.ajax({
    	    url: url,
    	    type: "post",
    	    data: {id:idCrearOperador,respuesta1:$("#pregunta1").val(),respuesta2:$("#pregunta2").val(),idPregunta1:$("#pregunta1").attr('name'),idPregunta2:$("#pregunta2").attr('name')},
    	    dataType: "json",
    	    async:   true,
    	    beforeSend: function(){
    	    	$("#estado").html('').removeClass();
    	    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
    		},
    	  success: function(msg){
    	    	if(msg.estado=="exito"){
    	    		$("#datosOficina").show();
					$("#claveAcceso").show();
					$("#codigoVerificacion").show();
					$("#enviarDatos").show();
					$("#validarDatos").attr('disabled','disabled');
    	    	}else{
        	    	
					if(msg.pregunta1 == "error"){
						$("#pregunta1").addClass("alerta");
	    				$("#lpregunta1").text('El valor ingresado no es correcto.').addClass("alerta");
					}
					if(msg.pregunta2 == "error"){
						$("#pregunta2").addClass("alerta");
	    				$("#lpregunta2").text('El valor ingresado no es correcto.').addClass("alerta");
					}
    				
    		    }
    	   },
    	    error: function(jqXHR, textStatus, errorThrown){
    	    	$("#cargando").delay("slow").fadeOut();
    	    	mostrarMensaje("Error al verificar los datos..! ","FALLO");
    	    },
            complete: function(){
            	$("#cargando").delay("slow").fadeOut();
            }
			});
		
});
	
	$("#enviarCodigo").click(function(event){
	    $(".alerta").removeClass("alerta");
	    quitarAlertas();
	    if(!$.trim($("#correo").val()) || !esCampoValido("#correo")){
			$("#correo").addClass("alerta");
			$("#lCorreo").text('El correo electrónico ingresado no posee el formato correcto.').addClass("alerta");
		}else{
        		event.preventDefault();
        		url = "../../publico/registroOperador/enviarMail.php";
             resultado = $.ajax({
        	    url: url,
        	    type: "post",
        	    data: {mail:$("#correo").val(),id:idCrearOperador},
        	    dataType: "json",
        	    async:   true,
        	    beforeSend: function(){
        	    	$("#estado").html('').removeClass();
        	    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
        		},
        	  success: function(msg){
        	    	if(msg.estado=="exito"){
        		    	$("#mensajeCodigoVerf").text('Código enviado a email registrado.');
        		    	if(msg.mensaje == "Mail enviado."){
        		    	   $("#enviarCodigo").attr('disabled','disabled');
        		    	   $("#correo").attr('disabled','disabled');
        		    	   $("#codigoVerifi").attr('disabled',false);
						}else{
            	    		$("#mensajeCodigoVerf").text("Código se enviará en el transcurso de 1 - 5 minutos al email registrado");
            	    		$("#codigoVerifi").attr('disabled',false);
            	    		$("#enviarCodigo").attr('disabled','disabled');
          		    	    $("#correo").attr('disabled','disabled');
        		    	}
        	    	}else{
        	    		$("#mensajeCodigoVerf").text(msg.mensaje).addClass("alerta");										
						$("#codigoVerifi").attr('disabled',true);
        		    }
        	   },
        	    error: function(jqXHR, textStatus, errorThrown){
        	    	$("#cargando").delay("slow").fadeOut();
        	    	$("#mensajeCodigoVerf").text("Error al verificar los datos..!").addClass("alerta");
        	    },
                complete: function(){
                	$("#cargando").delay("slow").fadeOut();
                }
				});
			}
		});
		
		$("#codigoVerifi").change(function(event){
		$(".alerta").removeClass("alerta");
		$("#lcodigoVerificacion").text('');
		quitarAlertas();
		if(!$.trim($("#codigoVerifi").val()) ){
			$("#codigoVerifi").addClass("alerta");
			$("#lcodigoVerificacion").text('Ingrese el código enviado a su correo.').addClass("alerta");
		}else{
			event.preventDefault();
	    	url = "../../publico/registroOperador/validarCodigo.php";
	     resultado = $.ajax({
		    url: url,
		    type: "post",
		    data: {id:idCrearOperador,codigo:$("#codigoVerifi").val()},
		    dataType: "json",
		    async:   true,
		    beforeSend: function(){
		    	$("#estado").html('').removeClass();
		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			
			success: function(msg){
				
				if(msg.estado=="exito"){
    		    	$("#mensajeCodigoVerf").text('');
    		    	$("#terminoCondi").attr('disabled',false);
    		    	$("#codigoVerifi").attr("readonly",true);
    	    	}else{
    	    		$("#codigoVerifi").addClass("alerta");
    				$("#lcodigoVerificacion").text('Código ingresado incorrecto...!!').addClass("alerta");
    		    }
		   },
		    error: function(jqXHR, textStatus, errorThrown){
		    	$("#cargando").delay("slow").fadeOut();
		    	mostrarMensaje("Error al validar código de verificación..! ","FALLO");
		    },
	        complete: function(){
	        	$("#cargando").delay("slow").fadeOut();
	        }
		});
	}
});
		
	</script>
</html>