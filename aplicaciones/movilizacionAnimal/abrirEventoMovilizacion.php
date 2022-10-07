<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();

$res = $cm->listaBusquedaInicioEventoMovilizacion($conexion);//, $_POST['id']);
$eventoMovilizacion = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos del emisor de Movilización</h1>
</header>
	
<form id="datosEventoMovilizacion" data-rutaAplicacion="movilizacionAnimal" data-opcion="actualizarAlmacen" data-accionEnExito="ACTUALIZAR">
<p>
	<button id="modificar" type="button" class="editar">Modificar</button>
	<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
</p>

<table class="soloImpresion">
<tr><td>
	
<fieldset>
		<legend>Responsable seleccionado</legend>
		
		<input type="hidden" id="estado" name="estado" value="<?php echo $eventoMovilizacion['estado'];?>" />
		<div data-linea="1">			
			<label>Identificación :</label><?php echo $eventoMovilizacion['identificador_emisor'];?>
		</div>
		<div data-linea="1">			
			<label>Sitio :</label><?php echo $eventoMovilizacion['nombre_sitio'];?>
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
	if($("#estado").val() == 'activo')
		$("#estado1").attr('checked', true);
	else
	 	$("#estado2").attr('checked', true);
});


</script>

</html>
