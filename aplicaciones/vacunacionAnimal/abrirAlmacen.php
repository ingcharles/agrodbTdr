<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

$res = $vdr->listaBusquedaAlmacen($conexion, $_POST['id']);
$almacen = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos del vacunador</h1>
</header>
	
<form id="datosAlmacen" data-rutaAplicacion="vacunacionAnimal" data-opcion="actualizarAlmacen" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

	<table class="soloImpresion">
		<tr><td>
	
<fieldset>
		<legend>Almacen seleccionado</legend>
		
		<input type="hidden" name="idAlmacen" value="<?php echo $almacen['id_almacen'];?>" />
		<input type="hidden" id="estado_almacen" name="estado_almacen" value="<?php echo $almacen['estado'];?>" />		
	
	<div data-linea="1">			
		<label>Provincia</label><?php echo $almacen['provincia'];?>
	</div>	
	<div data-linea="2">			
		<label>Cantón</label><?php echo $almacen['canton'];?>
	</div>
	
	<div data-linea="3">			
		<label>Nombre del almacen : </label> 
		<input type="text" name="nombreAlmacen" value="<?php echo $almacen['nombre_almacen'];?>" disabled="disabled" required="required" placeholder="Ej: Nombre del almacen"/>
	</div>
	<div data-linea="4">			
		<label>Lugar del almacen : </label> 
		<input type="text" name="lugarAlmacen" value="<?php echo $almacen['lugar_almacen'];?>" disabled="disabled" required="required" placeholder="Ej: Lugar del almacen"/>
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

$("#datosAlmacen").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$(document).ready(function(){
	distribuirLineas();
	if($("#estado_almacen").val() == 'activo')
		$("#estado1").attr('checked', true);
	else
	 	$("#estado2").attr('checked', true);
});


</script>

</html>
