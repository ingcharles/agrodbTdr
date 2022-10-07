<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();

$res = $cm->listaAutorizadoTramitarMovilizacion($conexion, $_POST['id']);
$autorizadoMovilizacion = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos del autorizado de movilización</h1>
</header>
	
<form id="datosEmisionMovilizacion" data-rutaAplicacion="movilizacionAnimal" data-opcion="actualizarAlmacen" data-accionEnExito="ACTUALIZAR">
<p>
	<button id="modificar" type="button" class="editar">Modificar</button>
	<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
</p>

<table class="soloImpresion">
<tr><td>
	
<fieldset>
		<legend>Autorizado seleccionado</legend>
		
		<input type="hidden" name="idResponsableMovilizacion" value="<?php echo $almacen['id_responsable_movilizacion'];?>" />
		<input type="hidden" id="estado" name="estado" value="<?php echo $autorizadoMovilizacion['estado'];?>" />
		<div data-linea="1">			
			<label>Sitio :</label><?php echo $autorizadoMovilizacion['sitio'];?>
		</div>
		<div data-linea="1">			
			<label>Area :</label><?php echo $autorizadoMovilizacion['area'];?>
		</div>		
		<div data-linea="2">			
			<label>Identificación propietario :</label><?php echo $autorizadoMovilizacion['identificador_propietario'];?>
		</div>
		<div data-linea="2">			
			<label>Propietario :</label><?php echo $autorizadoMovilizacion['nombre_propietario'];?>
		</div>
		<div data-linea="3">			
			<label>Identificación autorizado :</label><?php echo $autorizadoMovilizacion['identificador_autorizado'];?>
		</div>
		<div data-linea="3">			
			<label>Autorizado :</label><?php echo $autorizadoMovilizacion['nombre_autorizado'];?>
		</div>
		<div data-linea="4">			
			<label>Fecha autorización :</label><?php echo $autorizadoMovilizacion['fecha_autorizacion'];?>
		</div>
		<div data-linea="5">			
			<label>Observación :</label><?php echo $autorizadoMovilizacion['observacion'];?>
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
