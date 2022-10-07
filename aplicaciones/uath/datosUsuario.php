<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$res = $cu->obtenerNombresUsuario($conexion, $_SESSION['usuario']);
$usuario = pg_fetch_assoc($res);
?>

<header>
	<h1>Datos y clave de usuario</h1>
</header>

<form id="cambioDatosUsuario" data-rutaAplicacion="uath" data-opcion="guardarDatosUsuario" data-accionEnExito="NADA">
	<p>
		<button id="actualizar" type="submit" class="guardar">Actualizar</button>
	</p>
	<div id="estado"></div>
	<fieldset>

		<legend>Nombre usuario</legend>
		<div data-linea="1">
			<label>Alias</label> <input type="text" name="alias"
				value="<?php echo $usuario['nombre_usuario'];?>" />
		</div>

	</fieldset>

	<fieldset>

		<legend>Cambio de clave</legend>
		<div data-linea="1">
			<label>Clave actual</label> <input type="password" name="claveActual" />
		</div>
		<div data-linea="2">
			<label>Clave nueva</label> <input type="password" name="claveNueva1" />
		</div>
		<div data-linea="3">
			<label>Repita la clave nueva</label> <input type="password"
				name="claveNueva2" />
		</div>
	</fieldset>

</form>

<script type="text/javascript">
	$("#cambioDatosUsuario").submit(function(event){
		event.preventDefault();
		var obj= ejecutarJson($(this));
		obj.success(function(){
			$("#nombre").html($("input[name='alias']").val());
		});
	});

	$(document).ready(function(){
		distribuirLineas();
	});
</script>
