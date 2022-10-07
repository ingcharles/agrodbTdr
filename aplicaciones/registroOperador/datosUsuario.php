<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';


$conexion = new Conexion();
$cu = new ControladorUsuarios();


$identificador = $_SESSION['usuario'];

$res = $cu->obtenerNombresUsuario($conexion, $identificador);
$usuario = pg_fetch_assoc($res);

?>

<header>
	<h1>Datos y clave de usuario</h1>
</header>

<form id="cambioDatosUsuario" data-rutaAplicacion="registroOperador" data-opcion="guardarDatosUsuario" data-accionEnExito="NADA">
	<p>
		<button id="actualizar" type="submit" class="guardar">Actualizar</button>
	</p>
	<div id="estado"></div>
	<fieldset>
		<legend>Nombre de usuario</legend>
		<div data-linea="1">
			<label>Alias</label> 
			<input type="text" name="alias"	value="<?php echo $usuario['nombre_usuario'];?>" />
		</div>
	</fieldset>
	<fieldset>
		<legend>Cambio de clave</legend> 
		
		<div data-linea="1">
			<label>Clave actual</label>
			<input type="password" id="claveActual" name="claveActual" />
		</div>
		
		<div data-linea="2">
			<label>Clave nueva</label>
			<input type="password" id="claveNueva1" name="claveNueva1" maxlength="16" data-er="(^(?=.*\d)(?=.*[\u0021-\u002f\u003a-\u0040\u005b-\u0060\u007b-\u007e])(?=.*[A-Z])(?=.*[a-z])\S{8,16}$)" />
		</div>
		<div data-linea="2">
			<label>Repita la clave nueva</label>
			<input type="password" id="claveNueva2" name="claveNueva2" maxlength="16" data-er="(^(?=.*\d)(?=.*[\u0021-\u002f\u003a-\u0040\u005b-\u0060\u007b-\u007e])(?=.*[A-Z])(?=.*[a-z])\S{8,16}$)" />
		</div>
		
		<p class="nota">Ingrese al menos 8 digitos que incluyan al menos una letra mayuscula y un caracter especial cómo ^ ! @ # $ %.</p>	
		
	</fieldset>
	
</form>

<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
		construirValidador();	
	});

	$("#cambioDatosUsuario").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
		/*var obj= ejecutarJson($(this));
		obj.success(function(){
			$("#nombre").html($("input[name='alias']").val());
		});*/
	});

		function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#claveActual").val()) || !esCampoValido("#claveActual") || !$.trim($("#claveActual").val().length < 8)){
			error = true;
			$("#claveActual").addClass("alertaCombo");
		}
		
		if(!$.trim($("#claveNueva1").val()) || !esCampoValido("#claveNueva1") || $("#claveNueva1").val().length < ($("#claveNueva1").attr("maxlength")/2)){
			error = true;
			$("#claveNueva1").addClass("alertaCombo");
		}

		if(!$.trim($("#claveNueva2").val()) || !esCampoValido("#claveNueva2") || $("#claveNueva2").val().length < ($("#claveNueva2").attr("maxlength")/2)){
			error = true;
			$("#claveNueva2").addClass("alertaCombo");
		}

		if($("#claveNueva1").val() != $("#claveNueva2").val()){
			error = true;
			$("#claveNueva1").addClass("alertaCombo");
			$("#claveNueva2").addClass("alertaCombo");
		}		
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>