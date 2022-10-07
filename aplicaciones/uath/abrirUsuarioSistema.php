<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';

$idUsuarioSistema = $_POST['id'];

$conexion = new Conexion();
$cu = new ControladorUsuarios();

$usuario = pg_fetch_assoc($cu->buscarUsuarioSistema($conexion, $idUsuarioSistema));

?>

<header>
    <h1>Detalle Usuario del sistema</h1>
</header>
<div id="estado"></div>

<form id="actualizarUsuario" data-rutaAplicacion="uath" data-opcion="modificarUsuarioSistema" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
    <input id="clasificacion" name="clasificacion" type="hidden" value="Cédula" />
	
	<fieldset id="fs_detalle">
        <legend>Detalle</legend>
        
        <div data-linea="0">
            <label for="numero">Cédula</label> <input id="numero" name="numero" type="text" value="<?php echo $usuario['identificador']; ?>" readonly="readonly" />
        </div>
		
        <div data-linea="1">
            <label for="nombres">Nombres</label> <input id="nombres" name="nombre" type="text" value="<?php echo $usuario['nombre']; ?>" readonly="readonly" />
        </div>
		
        <div data-linea="2">
            <label for="apellidos">Apellidos</label> <input id="apellidos" name="apellido" type="text" value="<?php echo $usuario['apellido']; ?>" readonly="readonly" />
        </div>
        <div data-linea="3">
			<label for="mail_institucional">Correo institucional:</label> 
			<input type="text" id="mail_institucional" name="mail_institucional" value="<?php echo $usuario['mail_institucional']; ?>" maxlength="256" data-er="^([\w-]+(?:\.[\w-]+)*)@(agrocalidad.gob.ec)$" />
		</div>
		<div data-linea="4">
			<label for="mail_personal">Correo personal:</label> 
			<input type="text" id="mail_personal" name="mail_personal" value="<?php echo $usuario['mail_personal']; ?>" maxlength="256" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
		</div>
		<div data-linea="5">
            <label for="estadoUsuario">Estado</label>
            <select id="estadoUsuario" name="estado">
                <option value="0">Inactivo</option>
                <option value="1">Activo</option>
            </select>
        </div>
        <div>
            <button type="submit" class="guardar">Actualizar</button>
        </div>
    </fieldset>
</form>



<script>
    $('document').ready(function() {
        cargarValorDefecto("estado","<?php echo $usuario['estado_usuario_sistema'];?>");
        distribuirLineas();
    });
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

    $("#actualizarUsuario").submit(function(e) {
        e.preventDefault();
        $(".alertaCombo").removeClass("alertaCombo");
		
        var error = false;
		
        if ($.trim($("#detalleItem #numero").val()) == "" ) {
            error = true;
            $("#detalleItem #numero").addClass("alertaCombo");
        }
		
        if ($.trim($("#detalleItem #nombres").val()) == "" ) {
            error = true;
            $("#detalleItem #nombres").addClass("alertaCombo");
        }
		
        if ($.trim($("#detalleItem #apellidos").val()) == "" ) {
            error = true;
            $("#detalleItem #apellidos").addClass("alertaCombo");
        }
		
        if ($.trim($("#detalleItem #estadoUsuario").val()) == "" ) {
            error = true;
            $("#detalleItem #estadoUsuario").addClass("alertaCombo");
        }
		
		 if ($.trim($("#detalleItem #mail_institucional").val()) == ""  || !esCampoValido($("#detalleItem #mail_institucional"))) {
        	error = true;
            $("#detalleItem #mail_institucional").addClass("alertaCombo");
        }

        if ($.trim($("#detalleItem #mail_personal").val()) == ""  || !esCampoValido($("#detalleItem #mail_personal"))) {
            error = true;
            $("#detalleItem #mail_personal").addClass("alertaCombo");
        }
		
       if (!error){
        	var respuesta = JSON.parse(ejecutarJson($("#actualizarUsuario")).responseText);

        	if(data.estado === 'exito'){
				mostrarMensaje(respuesta.mensaje,"EXITO");
			}else{
				mostrarMensaje(respuesta.mensaje,"FALLO");
			}

        } else {
            mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");
        }
    });

</script>