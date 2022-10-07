<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cpoa = new ControladorPAPP();
	
	$res = $cpoa->abrirSubproceso($conexion, $_POST['id']);
	$subproceso = pg_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Subproceso</h1>
	</header>
	
	<div id="estado"></div>
	<form id="subproceso" data-rutaAplicacion="poa" data-opcion="actualizarSubproceso" data-destino="detalleItems" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idSubproceso" value="<?php echo $subproceso['id_subproceso'];?>"/>

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<fieldset id="fs_detalle">
		<legend>Detalle</legend>
			<div data-linea="1">
				<label>Subproceso: </label>
					<input type="text" name="descripcion" id="descripcion" disabled="disabled" value="<?php echo $subproceso['descripcion']; ?>" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
	</fieldset>
	
</form>

<form id="actividad" data-rutaAplicacion="poa" data-opcion="guardarNuevaActividad"  data-destino="detalleItems" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idSubproceso" name="idSubproceso"	value="<?php echo $subproceso['id_subproceso'];?>" />
	<input type="hidden" name="anio" value="<?php echo $fecha['year'];?>"/>
	
	<fieldset>
		<legend>Actividades</legend>
		<div data-linea="1">			
			<input type="text" name="descripcionActividad" id="descripcionActividad" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ]+$" required="required" />
		</div>
		
		<button type="submit" class="mas">Agregar Actividad</button>
	</fieldset>
</form>


<fieldset>
	<legend>Actividades</legend>
		<table id="tActividades">
			<?php 
				$cpoa1 = new ControladorPAPP();
				$res1 = $cpoa1->seleccionarActividades($conexion, $_POST['id']);
				
				while ($actividad = pg_fetch_assoc($res1)){
					echo $cpoa1->imprimirLineaActividad($actividad['id_actividad'], $actividad['descripcion']);
				}
			
			?>
		</table>
</fieldset>

	



</body>

<script type="text/javascript">
$(document).ready(function(){
	acciones("#actividad","#tActividades");
	distribuirLineas();
	construirValidador();
});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");	
});

$("#subproceso").submit(function(event){
	event.preventDefault();
	chequearCamposSubproceso(this);
});

$("#listadoDeActividades").on("submit", "form",function(event){

	event.preventDefault();
	ejecutarJson($(this));
	var texto=$(this).attr('id').substring(2);
	texto=texto.replace(/ /g,'');
	texto="#t_"+texto; 
	$("#actividades tr").eq($(texto).index()).remove();
	
});



function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCamposSubproceso(form){
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

function chequearCamposActividad(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#descripcionActividad").val()) || !esCampoValido("#descripcionActividad")){
		error = true;
		$("#descripcionActividad").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		if($("#actividades #t_"+$("#descripcionActividad").val().replace(/ /g,'')+$("#idSubproceso").val()).length==0){
				$("#actividades").append("<tr id='t_"+$("#descripcionActividad").val().replace(/ /g,'')+$("#idSubproceso").val()+"'><td><form id='f_"+$("#descripcionActividad").val().replace(/ /g,'')+$("#idSubproceso").val()+"' data-rutaAplicacion='poa' data-opcion='quitarActividad'><button type='submit' class='menos'>Quitar</button><input name='actividad_id' value='"+$("#idSubproceso").val()+"' type='hidden'><input name='actividad_dato' value='"+$("#descripcionActividad").val()+"' type='hidden'></form></td><td>"+$("#descripcionActividad").val()+"</td></tr>");
				ejecutarJson(form);
				
				if($("#estado").html()=='El Proceso ha sido actualizado satisfactoriamente'){
					$("#_actualizar").click();
				}
		}else{
			$("#estado").html('Por favor verifique la información, las actividades no pueden ser iguales.').addClass('alerta');
		}
	}
}

</script>

</html>

