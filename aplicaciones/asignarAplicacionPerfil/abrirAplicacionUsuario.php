<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';
$conexion = new Conexion();
$cap = new ControladorAplicacionesPerfiles();

$identificador=$_POST['id'];
$qAplicacionesUsuario=$cap->obtenerAplicacionesUsuario($conexion, $identificador);
?>
<header>
	<h1>Nuevo Registro (Aplicación Usuario)</h1>
</header>
<div id="estado"></div>
<form id="nuevoAplicacionPerfil" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="guardarNuevoAplicacionUsuario" >
	<input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="<?php echo $identificador?>" />
	<input type="hidden" id="nombreAplicacion" name="nombreAplicacion" />
	
	<fieldset>
		<legend>Aplicaciones</legend>
			<div data-linea="1">
				<label>Aplicación: </label>
				<select id="aplicacion" name="aplicacion" >
					<option value="" >Seleccione....</option>
					<?php 
						$qAplicaciones=$cap->listarAplicaciones($conexion);
						while($fila=pg_fetch_assoc($qAplicaciones)){
							echo '<option value='.$fila['id_aplicacion'].'>'.$fila['nombre'].'</option>';
						}
					?>
				</select>
			</div>
			<div data-linea="2">
				<label>Cantidad notificaciones: </label> 
				<input type="text" id="cantidadNotificaciones" name="cantidadNotificaciones" value="0" maxlength="4" data-er="^[0-9]+$" />
			</div>
			<div data-linea="2">
				<label>Mensaje notificaciones: </label> 
				<input type="text" id="mensajeNotificaciones" name="mensajeNotificaciones" value="notificaciones" maxlength="15" />
			</div>
			<button type="submit" class="mas">Agregar</button>
	</fieldset>
</form>
	
	<fieldset>
		<legend>Aplicaciones Registradas</legend>
			<table id="camposAplicaciones" style="width:100%;">
				<tr>
					<th >Id Aplicación</th>
					<th>Nombre</th>
					<th>Cantidad Notificacion</th>
					<th>Mensaje Notificacion </th>
					<th>Abrir</th>
					<th>Eliminar</th>
				</tr>
					<?php 
						while ($fila = pg_fetch_assoc($qAplicacionesUsuario)){
							echo $cap->imprimirLineaAplicacionesUsuario($identificador,$fila['id_aplicacion'], $fila['nombre'],$fila['cantidad_notificacion'],$fila['mensaje_notificacion']);
						}
					?>
			</table>
	</fieldset>
<script type="text/javascript">
						
	$('document').ready(function(){
		distribuirLineas();
	
	});

    acciones("#nuevoAplicacionPerfil","#camposAplicaciones",null,null,new exitoIngresos(),null,null, new validarInputs());

    function exitoIngresos(){
		this.ejecutar = function(msg){
			var seccion="#camposAplicaciones";
			mostrarMensaje("Nuevo registro agregado","EXITO");
			var fila = msg.mensaje;
			$(seccion).append(fila);	
			$("#aplicacion").val(0);
			//alert("d");
		};
	}

    function validarInputs() {
	    this.ejecutar = function () {

	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	        
			if ($("#aplicacion").val()==""){
				error = true;
		       	$("#aplicacion").addClass("alertaCombo");
		       	$("#estado").html('Por favor seleccione una aplicación').addClass("alerta");
			}

			if ($("#cantidadNotificaciones").val()==""){
			  	error = true;
		      	$("#cantidadNotificaciones").addClass("alertaCombo");
		       	$("#estado").html('Por favor ingrese la cantidad de notificaciones').addClass("alerta");
			}

			if (!esCampoValido("#cantidadNotificaciones")){
			   	error = true;
		     	$("#cantidadNotificaciones").addClass("alertaCombo");
		       	$("#estado").html('Por favor ingrese el formato de la cantidad de notificaciones de la aplicación').addClass("alerta");
			}
			
			if ($("#mensajeNotificaciones").val()==""){
			   error = true;
		       $("#mensajeNotificaciones").addClass("alertaCombo");
		       $("#estado").html('Por favor ingrese la cantidad de notificaciones').addClass("alerta");
			}
			
	        return !error;
	    };
	}

	$("#aplicacion").change(function (event) {
		$("#nombreAplicacion").val($("#aplicacion option:selected").text());
	});

</script>