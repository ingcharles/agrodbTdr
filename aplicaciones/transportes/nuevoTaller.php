<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();

//Identificador Usuario Administrador o Apoyo de Transportes
$identificadorUsuarioRegistro = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' href='../general/estilos/agrodb_papel.css' >
<link rel='stylesheet' href='../general/estilos/agrodb.css'>
</head>
<body>

<header>
	<h1>Nuevo taller</h1>
</header>
<form id='nuevoTaller' data-rutaAplicacion='transportes' data-opcion='guardarNuevoTaller' data-destino="detalleItem"data-accionEnExito='ACTUALIZAR'>
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<fieldset>
		
		<legend>Información básica</legend>
		
		<div data-linea="1">
		
			<label>Nombre</label> 
				<input type="text" id="taller" name="taller"  placeholder="Ej: EcuaMotor" data-er="[A-Za-z0-9]"/>
				
		</div><div data-linea="1">
			
			<label>Dirección</label> 
				<input	type="text" id="direccion" name="direccion" placeholder="Ej: Av. amazonas y la prensa" data-er="[A-Za-z0-9]"/>
				
		</div><div data-linea="2">
				
			<label>Persona Contacto</label> 
				<input	type="text" id="contacto" name="contacto" placeholder="Ej: Sr. Miguel Soto" data-er="[A-Za-z0-9]"/>
				
		</div><div data-linea="2">
							
			<label>Teléfono</label> 
				<input	type="text" id="telefono" name="telefono" placeholder="Ej: (04) 9999-999" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{3}" data-inputmask="'mask': '(99) 9999-999'" size="15"/>
				
		</div><div data-linea="3">
						
			<label>Observaciones</label> 
				<input	type="text" id="observaciones" name="observaciones" data-er="[A-Za-z0-9]"/>		
				
		</div>
	
	</fieldset>
	
	<button type="submit" class="guardar">Guardar Taller</button>
</form>


</body>

<script type="text/javascript">

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
				
$("#nuevoTaller").submit(function(event){
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

