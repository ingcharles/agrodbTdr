<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';


$conexion = new Conexion();
$cpoa = new ControladorPAPP();

$res = $cpoa->abrirIndicador($conexion, $_POST['idIndicador']);
$indicador = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Indicador</h1>
	</header>
	
	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="poa" data-opcion="abrirActividad" data-destino="detalleItem">
		<input type="hidden" name="idActividad" value="<?php echo $indicador['id_actividad'];?>"/>
		<button class="regresar">Volver a Actividad</button>
	</form>
	
	<form id="indicador" data-rutaAplicacion="poa" data-opcion="actualizarIndicador" data-destino="detalleItems" >
	<input type="hidden" name="idIndicador" value="<?php echo $indicador['id_indicador'];?>"/>

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<fieldset id="fs_detalle">
		<legend>Detalle</legend>
			<div data-linea="1">
				<label>Descripción </label>
					<input type="text" id="descripcion" name="descripcion" disabled="disabled" value="<?php echo $indicador['descripcion'];?>" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="2">
				<label>Línea base </label>
					<input type="number" id="linaBase" name="lineaBase" disabled="disabled" value="<?php echo $indicador['linea_base'];?>" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="3">
				<label>Método cálculo </label>
					<input type="text" id="metodoCalculo" name="metodoCalculo" disabled="disabled" value="<?php echo $indicador['metodo_calculo'];?>" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			
			<div data-linea="4">	
				<label>Tipo de control</label>
					<select id="tipo" name="tipo" disabled="disabled" required="required">
						<option value="">Seleccione....</option>
						<option value="Numero">Número</option>
						<option value="Porcentaje">Porcentaje</option>
					</select>
			</div>
	</fieldset>
	
</form>

</body>

<script type="text/javascript">
$(document).ready(function(){
	acciones("#actividad");
	distribuirLineas();
	construirValidador();
	cargarValorDefecto("tipo","<?php echo $indicador['tipo'];?>");
});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
	
});

$("#indicador").submit(function(event){
	event.preventDefault();
	chequearCamposIndicador(this);
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCamposIndicador(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#descripcion").val()) || !esCampoValido("#descripcion")){
		error = true;
		$("#descripcion").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);

		if($("#estado").html()=='El indicador ha sido actualizado satisfactoriamente'){
			$("#_actualizar").click();
		}
	}
}

</script>
</html>