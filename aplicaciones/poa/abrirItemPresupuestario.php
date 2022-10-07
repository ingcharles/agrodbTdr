<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$conexion = new Conexion();
$cpoa = new ControladorPAPP();

$res = $cpoa->abrirItemPresupuestario($conexion, $_POST['id']);
$item = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de ítem presupuestario</h1>
	</header>
		<div id="estado"></div>
		
	<form id="itemPresupuestario" data-rutaAplicacion="poa" data-opcion="actualizarItemPresupuestario" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="codigo" name="codigo" value="<?php echo $item['codigo'];?>"/>

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	
	<fieldset id="fs_detalle">
		<legend>Detalle</legend>
			<div data-linea="1">
				<label>Objetivo: </label>
					<input type="text" id="descripcion" name="descripcion" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" value="<?php echo $item['descripcion'];?>" />
			</div>
			
			<div data-linea="2">
				<label>Estado: </label>
					<select id="estadoItem" name="estadoItem" disabled="disabled">
						<option value="">Seleccione....</option>
						<option value="1">Activado</option>
						<option value="0">Desactivado</option>
					</select>
			</div>
	</fieldset>

</form>

</body>

<script type="text/javascript">
$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
	
});

$("#itemPresupuestario").submit(function(event){
	event.preventDefault();
	chequearCampos(this);
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#descripcion").val()) || !esCampoValido("#descripcion")){
		error = true;
		$("#descripcion").addClass("alertaCombo");
	}

	if(!$.trim($("#estadoItem").val())){
		error = true;
		$("#estadoItem").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		
	}
}

$(document).ready(function(){
	distribuirLineas();
	construirValidador();

	cargarValorDefecto("estadoItem","<?php echo $item['estado'];?>");
});
</script>

</html>