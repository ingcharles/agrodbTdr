<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorVacunacionAnimal();
$res = $cc->obtenerAdministradorPuntoDistribucion($conexion, $_POST['id']);
$distribuidorPtoVacunacion = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Punto distribuidor de vacunación</h1>
</header>

<div id="estado"></div>

<form id="datosDistribuidorVacunacion" data-rutaAplicacion="vacunacionAnimal" data-opcion="actualizarAdministracionDistribuidor" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<fieldset>
		<legend>Punto distribuidor seleccionado</legend>						
			<input type="hidden" name="id_administrador_distribuidor" value="<?php echo $distribuidorPtoVacunacion['id_administrador_distribuidor'];?>" />
			<input type="hidden" id="estadoPtoDistribuidor" name="estadoPtoDistribuidor" value="<?php echo $distribuidorPtoVacunacion['estado'];?>" />		
		
		<div data-linea="1">			
			<label>Ruc administrador :</label><?php echo $distribuidorPtoVacunacion['identificador_administrador'];?>
		</div>	
		<div data-linea="1">			
			<label>Administrador:</label><?php echo $distribuidorPtoVacunacion['nombre_administrador'];?>
		</div>	
		<div data-linea="2">			
			<label>Id distribuidor:</label><?php echo $distribuidorPtoVacunacion['identificador_distribuidor'];?>
		</div>	
		<div data-linea="2">			
			<label>Distribuidor:</label><?php echo $distribuidorPtoVacunacion['nombre_distribuidor'];?>
		</div>		
		<div data-linea="5">			
			<label>Especie : </label><?php echo $distribuidorPtoVacunacion['nombre_especie'];?>
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

$("#datosDistribuidorVacunacion").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$(document).ready(function(){
	distribuirLineas();
	if($("#estadoPtoDistribuidor").val() == 'activo')
		$("#estado1").attr('checked', true);
	else
	 	$("#estado2").attr('checked', true);
});


</script>

</html>
