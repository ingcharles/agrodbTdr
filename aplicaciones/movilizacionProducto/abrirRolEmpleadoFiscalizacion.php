<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();

$qRolEmpleado = $cmp->abrirRolEmpleado($conexion, $_POST['id']);
$rolEmpleado = pg_fetch_assoc($qRolEmpleado);
?>
<header>
	<h1>Modificar Rol Empleado</h1>
</header>
<form id="abrirRolEmpleado" data-rutaAplicacion="movilizacionProducto" data-opcion="actualizarRolEmpleadoFiscalizacion" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="idRolEmpleado" value="<?php echo $rolEmpleado['id_rol_empleado'];?>" />
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<fieldset>
		<legend>Datos Rol Empleado</legend>						
		<div data-linea="1">			
			<label>Identificación Operador de Movilización: </label>
			<input id="identificacionOperadorMovilizacion" name="identificacionOperadorMovilizacion" type="text" value="<?php echo $rolEmpleado['identificador_operador_movilizacion']?>" disabled="disabled"/>
		</div>	
		<div data-linea="2">			
			<label>Nombre Operador de Movilización: </label>
			<input id="operadorMovilizacion" name="operadorMovilizacion" type="text" value="<?php echo $rolEmpleado['operador_movilizacion']?>" disabled="disabled"/>
		</div>	
		<div data-linea="3">			
			<label>Identificacion Empleado: </label>
			<input id="identificacionEmpleado" name="identificacionEmpleado" type="text" value="<?php echo $rolEmpleado['identificador_empleado']?>" disabled="disabled"/>
		</div>	
		<div data-linea="4">			
			<label>Nombre Empleado: </label>
			<input id="empleado" name="empleado" type="text" value="<?php echo $rolEmpleado['empleado']?>" disabled="disabled"/>
		</div>	
		<div data-linea="5">			
			<label>Rol Empleado: </label>
			<input id="rol" name="rol" type="text" value="<?php echo $rolEmpleado['tipo']?>" disabled="disabled"/>
		</div>
		<div data-linea="6">			
			<label>Usuario Modificación: </label>
			<input id="UsuarioModificacion" name="UsuarioModificacion" type="text" value="<?php echo $rolEmpleado['usuario_modificacion']?>" disabled="disabled"/>
		</div>	
		<div data-linea="7">			
			<label>Fecha Modificación: </label>
			<input id="fechaModificacion" name="fechaModificacion" type="text" value="<?php echo $rolEmpleado['fecha_modificacion']?>" disabled="disabled"/>
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
	$('input:radio[name="estado"]').filter('[value="<?php echo $rolEmpleado['estado']; ?>"]').prop("checked", true);
	distribuirLineas();
});

$("#modificar").click(function(){
	$("#estadoActivo").removeAttr("disabled");
	$("#estadoInactivo").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#abrirRolEmpleado").submit(function(event){
		event.preventDefault();	
		ejecutarJson($(this));
});
</script>