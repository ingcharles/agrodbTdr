<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

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
	<h1>Nueva estación de gasolina</h1>
</header>

<form id='nuevoGasolinera' data-rutaAplicacion='transportes' data-opcion='guardarNuevoGasolinera' data-destino="detalleItem" data-accionEnExito='ACTUALIZAR'>
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		
	<fieldset>
		<legend>Información de la gasolinera</legend>
		
		<div data-linea="1">
		
			<label>Nombre</label> 
				 <input	type="text" id="nombreGasolinera" name="nombreGasolinera" data-er="[A-Za-z0-9]" placeholder="Ej: Petrocomercial"/>
				 
		</div><div data-linea="1">
				 
			<label>Cupo mensual</label> 
				 <input	type="number" step="0.01" id="cupo" name="cupo" data-er="^[0-9]+(\.[0-9]{1,2})?$" placeholder="Ej: 2000.00"/>
				 
		</div><div data-linea="2">
			
			<label>Dirección</label> 
				 <input	type="text" id="direccion" name="direccion" data-er="[A-Za-z0-9]" placeholder="Ej: Av. amazonas y la prensa"/>
				 
		</div>
				
	</fieldset>
	
		<fieldset>
		<legend>Datos de contacto</legend>
		
		<div data-linea="1">
				
			<label>Nombre</label> 
				 <input	type="text" id="contacto" name="contacto" data-er="[A-Za-z0-9]" placeholder="Sr. José Soto"/>
				 
		</div><div data-linea="1">
				
			<label>Teléfono</label> 
				<input	type="text" id="telefono" name="telefono" placeholder="Ej: (04) 9999-999" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{3}" data-inputmask="'mask': '(99) 9999-999'" size="15"/>
				
		</div><div data-linea="2">
				
			<label>Observaciones</label> 
				<input	type="text" id="observaciones" name="observaciones" data-er="[A-Za-z0-9]"/>
				
		</div>
				
	</fieldset>
	
	
	<fieldset>
		<legend>Precio por Galón de Combustible</legend>
			
		<div data-linea="2">
			
			<label>Extra</label> 
				<input type="text" id="extra" name="extra" placeholder="Ej: 1.48" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
				
		</div><div data-linea="2">
				
			<label>Súper</label> 
				<input type="text" id="super" name="super" placeholder="Ej: 2.00" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
				
		</div><div data-linea="2">
				
			<label>Diesel</label> 
				<input type="text" id="diesel" name="diesel" placeholder="Ej: 1.04" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
				
		</div><div data-linea="2">
				
			<label>Ecopaís</label> 
				<input type="text" id="ecopais" name="ecopais" placeholder="Ej: 1.48" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
				
		</div>

	</fieldset>
	
	<p class="nota">El valor por tipo de combustible es el costo por cada galón.</p>
	
	<button type="submit" class="guardar">Guardar gasolinera</button>
	
</form>


</body>

<script type="text/javascript">
	$("#nuevoGasolinera").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#nombreGasolinera").val()=="" || !esCampoValido("#nombreGasolinera")){
			error = true;
			$("#nombreGasolinera").addClass("alertaCombo");
		}

		if($("#cupo").val()=="" || !esCampoValido("#cupo")){
			error = true;
			$("#cupo").addClass("alertaCombo");
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

		
		if($("#super").val()=="" || !esCampoValido("#super")){
			error = true;
			$("#super").addClass("alertaCombo");
		}

		if($("#extra").val()=="" || !esCampoValido("#extra")){
			error = true;
			$("#extra").addClass("alertaCombo");
		}

		if($("#diesel").val()=="" || !esCampoValido("#diesel")){
			error = true;
			$("#diesel").addClass("alertaCombo");
		}

		if($("#ecopais").val()=="" || !esCampoValido("#ecopais")){
			error = true;
			$("#ecopais").addClass("alertaCombo");
		}

		if (!error){
			ejecutarJson(this);
		}else{
			$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
		}
	});		

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});	
</script>
</html>

