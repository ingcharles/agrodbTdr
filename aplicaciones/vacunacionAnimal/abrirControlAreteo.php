<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$res = $vdr-> listaFiltroControlAreteo($conexion, $_POST['id']);
$ControlAreteo = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos del control areteo</h1>
</header>

<div id="estado"></div>
	
<form id="datosVacunador" data-rutaAplicacion="vacunacionAnimal" data-opcion="actualizarControlAreteo" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

    <fieldset>
		<legend>Control areteo seleccionado</legend>		
		<input type="hidden" name="id_control_areteo" value="<?php echo $ControlAreteo['id_control_areteo'];?>" />
		<input type="hidden" id="estado_vacunador" name="estado_vacunador" value="<?php echo $ControlAreteo['estado'];?>" />
		<input type="hidden" id="identificador" name="identificador" value="<?php echo $_SESSION['usuario']?>" />		
	<div data-linea="1">							
		<label>Provincia: </label><?php echo $ControlAreteo['provincia'];?>
	</div>
	<div data-linea="2">							
		<label>Cantón: </label><?php echo $ControlAreteo['canton'];?>
	</div>
	<div data-linea="3">							
		<label>Observación: </label> 
		<input type="text" name="observacion" value="<?php echo $ControlAreteo['observacion'];?>" disabled="disabled" required="required" placeholder="Ej: Observacion"/>
	</div>
	<table>													
	<tr>
		<td>
			<label>Estado</label>
		</td>									
		<td>
			<input type="radio" name="estado" id="estado1" value="activo" disabled="disabled">Activo
			<input type="radio" name="estado" id="estado2" value="inactivo" disabled="disabled">Inactivo
		</td>		
	</tr>
	</table>
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

$("#datosVacunador").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$(document).ready(function(){
	distribuirLineas();
	if($("#estado_vacunador").val() == 'activo')
		$("#estado1").attr('checked', true);
	else
	 	$("#estado2").attr('checked', true);
});

</script>

</html>
