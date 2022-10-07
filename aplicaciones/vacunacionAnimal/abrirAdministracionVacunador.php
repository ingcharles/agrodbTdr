<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorVacunacionAnimal();
$res = $cc->obtenerAdministradorVacunador($conexion, $_POST['id']);
$administradorVacunador = pg_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Administración de el vacunador</h1>
</header>

<div id="estado"></div>
<form id="datosAdministradorVacunador" data-rutaAplicacion="vacunacionAnimal" data-opcion="actualizarAdministracionVacunador" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<fieldset>
		<legend>Vacunador seleccionado</legend>						
			<input type="hidden" name="id_administrador_vacunador" value="<?php echo $administradorVacunador['id_administrador_vacunador'];?>" />
			<input type="hidden" id="estadoVacunador" name="estadoVacunador" value="<?php echo $administradorVacunador['estado'];?>" />				
		<div data-linea="1">			
			<label>Ruc administrador:</label><?php echo $administradorVacunador['identificador_administrador'];?>
		</div>	
		<div data-linea="1">			
			<label>Administrador:</label><?php echo $administradorVacunador['nombre_administrador'];?>
		</div>
		<div data-linea="2">			
			<label>Id distribuidor:</label><?php echo $administradorVacunador['identificador_distribuidor'];?>
		</div>	
		<div data-linea="2">			
			<label>Distribuidor:</label><?php echo $administradorVacunador['nombre_distribuidor'];?>
		</div>
		<div data-linea="3">			
			<label>Id vacunador:</label><?php echo $administradorVacunador['identificador_vacunador'];?>
		</div>	
		<div data-linea="3">			
			<label>Vacunador:</label><?php echo $administradorVacunador['nombre_vacunador'];?>
		</div>		
		<div data-linea="4">			
			<label>Especie : </label><?php echo $administradorVacunador['nombre_especie'];?>
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

$("#datosAdministradorVacunador").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$(document).ready(function(){
	distribuirLineas();
	if($("#estadoVacunador").val() == 'activo')
		$("#estado1").attr('checked', true);
	else
	 	$("#estado2").attr('checked', true);
});


</script>

</html>
