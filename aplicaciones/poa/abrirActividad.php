<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cpoa = new ControladorPAPP();
	
	$res = $cpoa->abrirActividad($conexion, $_POST['idActividad']);
	$actividad = pg_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Actividad</h1>
	</header>
	
	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="poa" data-opcion="abrirSubproceso" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $actividad['id_subproceso'];?>"/>
		<button class="regresar">Volver a Subproceso</button>
	</form>
	
	<form id="actividad" data-rutaAplicacion="poa" data-opcion="actualizarActividad" data-destino="detalleItems" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idActividad" value="<?php echo $actividad['id_actividad'];?>"/>

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<fieldset id="fs_detalle">
		<legend>Detalle</legend>
			<div data-linea="1">
				<label>Descripción </label>
					<input type="text" id="descripcion" name="descripcion" disabled="disabled" value="<?php echo $actividad['descripcion'];?>" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
	</fieldset>
	
</form>

<!--form id="indicador" data-rutaAplicacion="poa" data-opcion="guardarNuevoIndicador"  data-destino="detalleItems" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idActividad" name="idActividad" value="< ?php echo $actividad['id_actividad'];?>" />
	<input type="hidden" name="anio" value="< ?php echo $fecha['year'];?>"/>
	
		<fieldset>
			<legend>Indicadores por Actividad</legend>
			<div data-linea="1">
				<label>Descripción</label>
					<input type="text" id="descripcionIndicador" name="descripcionIndicador" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
			</div>
			
			<div data-linea="2">	
				<label>Línea Base</label>
					<input type="number" id="lineaBase" name="lineaBase" maxlength="128" data-er="^[0-9]+$" required="required"/>
			</div>
			
			<div data-linea="3">	
				<label>Método de cálculo</label>
					<input type="text" id="metodoCalculo" name="metodoCalculo" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
			</div>
			
			<div data-linea="4">	
				<label>Tipo de control</label>
					<select id="tipo" name="tipo" required="required">
						<option value="">Seleccione....</option>
						<option value="Numero">Número</option>
						<option value="Porcentaje">Porcentaje</option>
					</select>
					
				<button type="submit" class="mas">Agregar</button>
			</div>
		</fieldset>
</form-->

	
<!-- fieldset>
	<legend>Indicadores por Actividad</legend>
	
		<div data-linea="1">
		
			<table id="listadoDeComponentes">
			
			<tr>
				<th>
					Descripción
				</th>
				<th>
					Línea Base
				</th>
				<th>
					Método de Cálculo
				</th>
				<th>
					Tipo de control
				</th>
				<th>
				
				</th>
			</tr>
					
				< ?php
						$cpoa1 = new ControladorPAPP();
						$res1 = $cpoa1->listarIndicadorXActividad($conexion, $_POST['idActividad']);
													
						while($fila = pg_fetch_assoc($res1)){

							echo $cpoa1->imprimirLineaIndicador($fila['id_indicador'], $fila['descripcion'], $fila['linea_base'], $fila['metodo_calculo'], $fila['tipo']);
						}
					?>
				
			</table>
			
		</div>
</fieldset-->


</body>

<script type="text/javascript">
$(document).ready(function(){
	acciones("#indicador","#listadoDeComponentes");
	distribuirLineas();
	construirValidador();
});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
	
});

$("#actividad").submit(function(event){
	event.preventDefault();
	chequearCamposActividad(this);
});

/*$("#indicador").submit(function(event){
	event.preventDefault();
	ejecutarJson(this);
	//chequearCamposIndicador(this);
});
*/

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCamposActividad(form){
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

		if($("#estado").html()=='El Proceso ha sido actualizado satisfactoriamente'){
			$("#_actualizar").click();
		}
	}
}

/*function chequearCamposIndicador(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#descripcionIndicador").val()) || !esCampoValido("#descripcionIndicador")){
		error = true;
		$("#descripcionIndicador").addClass("alertaCombo");
	}

	if(!$.trim($("#lineaBase").val()) || !esCampoValido("#lineaBase")){
		error = true;
		$("#lineaBase").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		$("#estado").html('El indicador ha sido actualizado satisfactoriamente');
		$("#_actualizar").click();
	}
}

*/

</script>
</html>