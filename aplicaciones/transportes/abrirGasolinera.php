<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();

$res = $cv->abrirGasolinera($conexion, $_POST['id']);
$gasolinera = pg_fetch_assoc($res);

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
	<h1>Datos de estación de gasolina</h1>
</header>


<form id="datosGasolinera" data-rutaAplicacion="transportes" data-opcion="actualizarGasolinera" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<div id="estado"></div>
	
	<table class="soloImpresion">
	<tr><td>
	<fieldset>
		<legend>Información de la gasolinera</legend>
		
		<input type="hidden" name="id_gasolinera" value="<?php echo $gasolinera['id_gasolinera'];?>"/>
		
		<div data-linea="1">
		
			<label>Nombre</label> 
					<input	type="text" id="nombreGasolinera" name="nombreGasolinera" value="<?php echo $gasolinera['nombre'];?>" disabled="disabled" data-er="[A-Za-z0-9]" placeholder="Ej: Petrocomercial"/>
						
		</div><div data-linea="1">
			
			<label>Dirección</label> 
					<input	type="text" id="direccion" name="direccion" value="<?php echo $gasolinera['direccion'];?>" disabled="disabled" data-er="[A-Za-z0-9]" placeholder="Ej: Av. amazonas y la prensa"/>


		</div><div data-linea="2">
		
			<label>Cupo mensual</label> 
					<input	type="number" id="cupo" name="cupo" value="<?php echo $gasolinera['cupo'];?>" disabled="disabled" data-er="^[0-9]+(\.[0-9]{1,2})?$" placeholder="Ej: 2000"/>
							
		</div><div data-linea="2">
		
			<label>Cupo disponible</label>
				<input	type="text" id="cupoDisponible" value="<?php echo $gasolinera['saldo_disponible'];?>" disabled="disabled" />

		</div>

	</fieldset>	
	</td>
	
	<td>
	<fieldset>
		<legend>Datos de Contacto</legend>
		
		<div data-linea="1">
				
			<label>Nombre</label> 
				 <input	type="text" id="contacto" name="contacto" value="<?php echo $gasolinera['contacto'];?>" disabled="disabled" data-er="[A-Za-z0-9]" placeholder="Sr. José Soto"/>
				
		</div><div data-linea="1">
		
			<label>Teléfono</label> 
				<input	type="text" id="telefono" name="telefono" value="<?php echo $gasolinera['telefono'];?>" disabled="disabled" placeholder="Ej: (04) 9999-999" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{3}" data-inputmask="'mask': '(99) 9999-999'" size="15"/>
				
		</div><div data-linea="2">
		
			<label>Observaciones</label> 
				<input	type="text" id="observaciones" name="observaciones" value="<?php echo $gasolinera['observacion'];?>" disabled="disabled" data-er="[A-Za-z0-9]"/> 
		
		</div>

			
	</fieldset>	
	</td>
	
	<td>

	<fieldset>
		<legend>Precio por Galón de Combustible</legend>
		
		<div data-linea="2">
		
		<label>Extra</label> 
				<input type="text" id="extra" name="extra" value="<?php echo $gasolinera['extra'];?>" disabled="disabled" placeholder="Ej: 1.48" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
		
		</div><div data-linea="2">
		
			<label>Súper </label> 
				<input type="text" id="super" name="super" value="<?php echo $gasolinera['super'];?>" disabled="disabled" placeholder="Ej: 2.00" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
		
		</div><div data-linea="2">
				
			<label>Diesel </label> 
				<input type="text" id="diesel" name="diesel" value="<?php echo $gasolinera['diesel'];?>" disabled="disabled" placeholder="Ej: 1.04" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
		
		</div><div data-linea="2">
				
			<label>Ecopaís </label> 
				<input type="text" id="ecopais" name="ecopais" value="<?php echo $gasolinera['ecopais'];?>" disabled="disabled" placeholder="Ej: 1.04" data-er="^[0-9]{1}(\.[0-9]{0,3})?$"/>
		
		</div>
		
		
		
	</fieldset>

		<p class="nota">El valor por tipo de combustible es el costo por cada galón.</p>
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
		$("#cupoDisponible").attr("disabled","disabled");
		$(this).attr("disabled","disabled");
	});


	$("#datosGasolinera").submit(function(event){
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
