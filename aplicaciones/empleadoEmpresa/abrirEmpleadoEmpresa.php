<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';
$conexion = new Conexion();
$cee = new ControladorEmpleadoEmpresa();
$qEmpleado= $cee->abrirEmpleado($conexion, $_POST['id']);
$empleado = pg_fetch_assoc($qEmpleado);
?>
<header>
	<h1>Modificar Empleado</h1>
</header>
<form id="abrirEmpleadoEmpresa" data-rutaAplicacion="empleadoEmpresa" data-opcion="actualizarEmpleadoEmpresa" >
	<div id="estado"></div>
	<input type="hidden" name="idEmpleado" value="<?php echo $empleado['id_empleado'];?>" />
	<input type="hidden" name="identificacionEmpresa" value="<?php echo $empleado['identificador_operador_vacunacion'];?>" />
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<fieldset>
		<legend>Datos Empleado</legend>						
		<div data-linea="1">			
			<label>Ruc Empresa: </label>
			<input id="identificacionOperadorVacunacion" name="identificacionOperadorVacunacion" type="text" value="<?php echo $empleado['identificador_operador_vacunacion']?>" disabled="disabled"/>
		</div>	
		<div data-linea="2">			
			<label>Nombre Empresa: </label>
			<input id="operadorVacunacion" name="operadorVacunacion" type="text" value="<?php echo $empleado['operador_vacunacion']?>" disabled="disabled"/>
		</div>	
		<div data-linea="3">			
			<label>Identificación Empleado: </label>
			<input id="identificacionEmpleado" name="identificacionEmpleado" type="text" value="<?php echo $empleado['identificador_empleado']?>" disabled="disabled"/>
		</div>	
		<div data-linea="4">			
			<label>Nombre Empleado: </label>
			<input id="empleado" name="empleado" type="text" value="<?php echo $empleado['empleado']?>" disabled="disabled"/>
		</div>	

		<div data-linea="8">
		<label>Estado: </label>
			<input type="radio" name="estado" id="estadoActivo" value="activo" disabled="disabled" />Activo
			<input type="radio" name="estado" id="estadoInactivo" value="inactivo" disabled="disabled" />Inactivo
		</div>				
	</fieldset>	
</form>
</body>

<script type="text/javascript">
$(document).ready(function(){
	'<?php echo $empleado['estado'];?>' == 'activo' ? $("#estadoActivo").attr('checked', true) : $("#estadoInactivo").attr('checked', true);
	distribuirLineas();
});

$("#modificar").click(function(){
	$("#estadoActivo").removeAttr("disabled");
	$("#estadoInactivo").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#abrirEmpleadoEmpresa").submit(function(event){
		event.preventDefault();	
		ejecutarJson($(this));
		if($('#estado').html()=='Los datos han sido actualizados satisfactoriamente')
			$('#_actualizar').click();
});
</script>