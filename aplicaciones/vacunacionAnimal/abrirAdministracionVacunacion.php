<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cc = new ControladorVacunacionAnimal();
$res = $cc->obtenerAdministradorVacunacion($conexion, $_POST['id']);
$administradorVacunacion = pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Administración de vacunación</h1>
</header>

<div id="estado"></div>

<form id="datosAdministradorVacunador" data-rutaAplicacion="vacunacionAnimal" data-opcion="actualizarAdministracionVacunacion" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<fieldset>
		<legend>Administrador vacunación seleccionado</legend>						
			<input type="hidden" name="id_administrador_vacunacion" value="<?php echo $administradorVacunacion['id_administrador_vacunacion'];?>" />
			<input type="hidden" id="estadoAdmVacunacion" name="estadoAdmVacunacion" value="<?php echo $administradorVacunacion['estado'];?>" />		
		
		<div data-linea="1">			
			<label>Identificación:</label><?php echo $administradorVacunacion['identificador_administrador'];?>
		</div>	
		<div data-linea="2">			
			<label>Nombre administrador:</label><?php echo $administradorVacunacion['nombre_administrador'];?>
		</div>
		
		<div data-linea="3">			
			<label>Especie : </label><?php echo $administradorVacunacion['nombre_especie'];?>
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
	if($("#estadoAdmVacunacion").val() == 'activo')
		$("#estado1").attr('checked', true);
	else
	 	$("#estado2").attr('checked', true);
});


</script>

</html>
