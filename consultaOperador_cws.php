<?php 

/*require_once 'clases/conexion.php';
 require_once 'clases/ControladorCatalogos.php';

$conexion = new Conexion();
$controlador = new ControladorCatalogos();

$canton=  $controlador->listarSitiosLocalizacion($conexion, 'CANTONES');
$sitio = $controlador->listarSitiosLocalizacion($conexion, 'SITIOS');
*/


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Panel de control GUIA</title>
<link rel='stylesheet'
	href='aplicaciones/general/estilos/estiloSolicitud.css'>
<!-- link rel='stylesheet' href='aplicaciones/general/estilos/agrodb.css' -->
<script src="aplicaciones/general/funciones/jquery-1.9.1.js"
	type="text/javascript"></script>
<script src="aplicaciones/general/funciones/agrdbfunc.js"
	type="text/javascript"></script>
<!-- link rel='stylesheet'
	href='aplicaciones/general/estilos/jquery-ui-1.10.2.custom.css' -->


</head>
<body>

	<section id="formulario">
		<header>
			<img src="aplicaciones/general/img/MAG-ICO.png" /> <img
				src="aplicaciones/general/img/AGR-ICO.png" />
		</header>
		<h1>Registro de Operador</h1>
		<h2>Agencia Ecuatoriana de Aseguramiento de la calidad del Agro -
			Agrocalidad</h2>
		<p class="nota">La información ingresada en este formulario servirá
			para registrarse en sistema informático y acceder a los servicios de
			AGROCALIDAD. La información es de carácter confidencial y de uso
			exclusivo para la institución y el usuario dueño de los datos.</p>


		<form id="registroOperador" data-RutaAplicacion="" data-opcion="test2">
			<fieldset>
				<legend>Tipo de identificación</legend>
				<div id="clasificacion">
					<label>CI/RUC</label><input name="numero" type="text" id="numero"
						placeholder="Número de identificación" data-er="^[0-9]+$" /><br />


				</div>
			</fieldset>
			<button type="submit">Enviar Datos</button>
		</form>


		<fieldset>
			<legend>Datos generales</legend>
			<div>
				<label for="razon" class="opcional">Razón social</label> <input
					name="razon" type="text" id="razon"
					placeholder="Nombre de la empresa" class="cuadroTextoCompleto"
					disabled="disabled" maxlength="70"
					data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" />
			</div>
			<label for="nombre">Representante legal</label> <input name="nombre"
				type="text" id="nombre" placeholder="Nombres" maxlength="200"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" /> <input name="apellido"
				type="text" id="apellido" placeholder="Apellidos" maxlength="250"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
		</fieldset>
		<fieldset>
			<legend>Datos de oficina</legend>

			<div>
				<label for="sitio">Parroquia</label> <input name="sitio" id="sitio"
					/>
				</select>
			</div>
			<div>
				<label for="direccion">Dirección</label> <input name="direccion"
					type="text" id="direccion" class="cuadroTextoCompleto"
					maxlength="70" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$" />
			</div>
			<div>
				<label for="telefono1">Teléfonos</label> <input name="telefono1"
					type="text" id="telefono1" placeholder="Principal"
					data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?"
					size="15" /> <input name="telefono2" type="text" id="telefono2"
					placeholder="Secundario" maxlength="50"
					data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?"
					size="15" /> <span class="ejemplo">Ej.: (00) 000-0000 ext. 0000</span>
			</div>
			<div>
				<label for="celular1">Celular</label> <input name="celular1"
					type="text" id="celular1" placeholder="Principal"
					data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" size="15" /> <input
					name="celular2" type="text" id="celular2" placeholder="Secundario"
					maxlength="30" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" size="15" />
				<span class="ejemplo">Ej.: (00) 0000-0000</span>
			</div>
			<div>
				<label for="correo">Correo electrónico</label> <input name="correo"
					type="text" id="correo" class="cuadroTextoCompleto" maxlength="128"
					data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
			</div>
		</fieldset>
		


	</section>
</body>
<script type="text/javascript">

	$('#registroOperador').submit(function(event){

		event.preventDefault();

		
		
		var $botones = $(this).find("button[type='submit']"),
    	serializedData = $(this).serialize(),
    	url = "test2.php";
	//$("#clave").val($.md5($("#clave").val()));
	
    $botones.attr("disabled", "disabled");
     resultado = $.ajax({
	    url: url,
	    type: "post",
	    data: serializedData,
	    dataType: "json",
	    async:   false,
	    beforeSend: function(){
	    	$("#estado").removeClass();
		},
	    success: function(msg){
		    //alert("hola");
	    	$('#razon').val(msg.mensaje['razon_social']);
	    	$('#nombre').val(msg.mensaje['nombre_representante_legal']);
	    	$('#apellido').val(msg.mensaje['apellido_representante_legal']);
	    	$('#nombreTecnico').val(msg.mensaje['nombre_tecnico']);
	    	$('#apellidoTecnico').val(msg.mensaje['apellido_tecnico']);
	    	$('#sitio').val(msg.mensaje['localizacion']);
	    	$('#direccion').val(msg.mensaje['direccion']);
	    	$('#telefono1').val(msg.mensaje['telefono_uno']);
	    	$('#telefono2').val(msg.mensaje['telefono_dos']);
	    	$('#celular1').val(msg.mensaje['celular_uno']);
	    	$('#celular2').val(msg.mensaje['celular_dos']);
	    	$('#correo').val(msg.mensaje['correo']);
	   },
	    error: function(jqXHR, textStatus, errorThrown){
	       alert("error");
	    },
        complete: function(){
           
           //$("#clave").val("");
        }
	});

		
	});


	//var canton = <?php echo json_encode($canton);?>;
	//var sitio = <?php echo json_encode($sitio);?>;

	

	/*$("#provincia").change(function(){
		scanton ='0';
		scanton = '<option value="">canton...</option>';
		for(var i=0;i<canton.length;i++){
		      if ($("#provincia").val()==canton[i]['padre']){
		      	scanton += '<option value="'+canton[i]['codigo']+'">'+canton[i]['nombre']+'</option>';
		      }
		}
		$('#canton').html(scanton);
		$("#canton").removeAttr("disabled");
	});

	$("#canton").change(function(){
		  ssitio ='0';
		  ssitio = '<option value="">Sitio...</option>';
		     for(var i=0;i<sitio.length;i++){
		      if ($("#canton").val()==sitio[i]['padre']){
		       ssitio += '<option value="'+sitio[i]['codigo']+'">'+sitio[i]['nombre']+'</option>';
		       } 
		     }

		  $('#sitio').html(ssitio);
		  $("#sitio").removeAttr("disabled");
	});

	$("#cedula").change(function(){
		//alert("cedula");
		$("#razon").attr("disabled","disabled");
		$("#razon").val("");
		$("#numero").removeAttr("disabled");
		$("#numero").attr("maxlength","10");
		var valor = $("#numero").val();
		$("#numero").val(valor.substring(0,10));
	});

	$("#personaNatural").change(function(){
		//$("#razon").removeAttr("disabled");
		$("#razon").attr("disabled","disabled");
		$("#razon").val("");
		$("#numero").removeAttr("disabled");
		$("#numero").attr("maxlength","13");
	});
	$("#personaJuridica").change(function(){
		$("#razon").removeAttr("disabled");
		$("#numero").removeAttr("disabled");
		$("#numero").attr("maxlength","13");
	});

	$("#registroOperador").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});

	function esCampoValido(elemento){
		//var patron = /^[0-9]+$/g;
		//var patron = new RegExp("^[0-9]+$","g");
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alerta").removeClass("alerta");
		var error = false;
		if($("input:radio[name=clasificacion]:checked").val() == null){
			error = true;
			$("#clasificacion label").addClass("alerta");
			
		}
		if(!$.trim($("#numero").val()) || !esCampoValido("#numero") || $("#numero").val().length != $("#numero").attr("maxlength")){
			error = true;
			$("#numero").addClass("alerta");
		}

		if($("#personaJuridica").is(':checked') && (!$.trim($("#razon").val()) || !esCampoValido("#razon"))){
			error = true;
			$("#razon").addClass("alerta");
		}

		if(!$.trim($("#nombre").val()) || !esCampoValido("#nombre")){
			error = true;
			$("#nombre").addClass("alerta");
		}

		if(!$.trim($("#apellido").val()) || !esCampoValido("#apellido")){
			error = true;
			$("#apellido").addClass("alerta");
		}

		if(!$.trim($("#direccion").val()) || !esCampoValido("#direccion")){
			error = true;
			$("#direccion").addClass("alerta");
		}

		if(!$.trim($("#telefono1").val()) || !esCampoValido("#telefono1")){
			error = true;
			$("#telefono1").addClass("alerta");
		}

		if($("#telefono2").val().length!=0 && !esCampoValido("#telefono2")){
			error = true;
			$("#telefono2").addClass("alerta");
		}

		if(!$.trim($("#celular1").val()) || !esCampoValido("#celular1")){
			error = true;
			$("#celular1").addClass("alerta");
		}

		if($("#celular2").val().length!=0 && !esCampoValido("#celular2")){
			error = true;
			$("#celular2").addClass("alerta");
		}
		
		if(!$.trim($("#correo").val()) || !esCampoValido("#correo")){
			error = true;
			$("#correo").addClass("alerta");
		}

		if($("#clave1").val() != $("#clave2").val() || !esCampoValido("#clave1") || $("#clave1").val().length < 8){
			error = true;
			$("#clave1").addClass("alerta");
			$("#clave2").addClass("alerta");
		}
		
		if (!error){
			//alert("Datos enviados");
		}	
	}*/
</script>
</html>
