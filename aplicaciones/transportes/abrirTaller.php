<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cv = new controladorVehiculos();

$res = $cv->abrirTaller($conexion, $_POST['id']);
$taller = pg_fetch_assoc($res);

//Identificador Usuario Administrador o Apoyo de Transportes
if($_SESSION['usuario'] != '' && $_SESSION['usuario']!=$mantenimiento['identificador_registro']){
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
}else if($_SESSION['usuario'] != '' && $_SESSION['usuario']==$mantenimiento['identificador_registro']){
	$identificadorUsuarioRegistro = $mantenimiento['identificador_registro'];
}else{
	$identificadorUsuarioRegistro = '';
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos de taller</h1>
</header>

	<div id="estado"></div>
	
<form id="datosTaller" data-rutaAplicacion="transportes" data-opcion="actualizarTaller" data-accionEnExito="ACTUALIZAR">
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

	<table class="soloImpresion">
		<tr><td>
	
<fieldset>
		<legend>Taller seleccionado</legend>
		
		<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		<input type="hidden" name="id_taller" value="<?php echo $taller['id_taller'];?>" />
		
	<div data-linea="1">	
		
		<label>Nombre</label> 
			<input type="text" id="nombre" name="nombre" value="<?php echo $taller['nombre'];?>" disabled="disabled" data-er="[A-Za-z0-9]" placeholder="Ej: EcuaMotor"/>
			
	</div><div data-linea="1">
		
		<label>Dirección</label> 
			<input type="text" id="direccion" name="direccion" value="<?php echo $taller['direccion'];?>" disabled="disabled" data-er="[A-Za-z0-9]" placeholder="Ej: Av. amazonas y la prensa"/>
			
	</div><div data-linea="2">
			
		<label>Teléfono</label> 
			<input type="text" id="telefono" name="telefono" value="<?php echo $taller['telefono'];?>" disabled="disabled" placeholder="Ej: (04) 9999-999" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{3}" data-inputmask="'mask': '(99) 9999-999'" size="15"/>
			
	</div><div data-linea="2">
				
		<label>Contacto</label> 
			<input type="text" id="contacto" name="contacto" value="<?php echo $taller['contacto'];?>" disabled="disabled" data-er="[A-Za-z0-9]" placeholder="Ej: Sr. Miguel Soto"/>
			
	</div><div data-linea="3">
	
		<label>Observaciones</label> 
			<input type="text" id="observaciones" name="observaciones" value="<?php echo $taller['observacion'];?>" disabled="disabled" data-er="[A-Za-z0-9]"/>	
			
	</div>
			
	</fieldset>	
		
	
		</td>
		</tr>
	</table>
</form>



</body>

<script type="text/javascript">

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
				
$("#datosTaller").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#taller").val()=="" || !esCampoValido("#taller")){
		error = true;
		$("#taller").addClass("alertaCombo");
	}

	if($("#direccion").val()=="" || !esCampoValido("#direccion")){
		error = true;
		$("#direccion").addClass("alertaCombo");
	}

	if($("#contacto").val()=="" || !esCampoValido("#contacto")){
		error = true;
		$("#contacto").addClass("alertaCombo");
	}
	
	if($("#telefono").val()=="" || !esCampoValido("#telefono")){
		error = true;
		$("#telefono").addClass("alertaCombo");
	}

	if($("#observaciones").val()=="" || !esCampoValido("#observaciones")){
		error = true;
		$("#observaciones").addClass("alertaCombo");
	}

	if (!error){
		ejecutarJson(this);
	}else{
		$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
	}
});

</script>

</html>
