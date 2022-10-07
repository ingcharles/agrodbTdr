<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';
$conexion = new Conexion();
$cap = new ControladorAplicacionesPerfiles();

$identificador=$_POST['identificacionUsuario'];
$idAplicacion=$_POST['idAplicacion'];
$qPerfilesUsuario=$cap->obtenerPerfilesUsuario($conexion, $idAplicacion,$identificador);
?>
<header>
	<h1>Nuevo Registro (Perfil Usuario)</h1>
</header>
<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirAplicacionUsuario" data-destino="detalleItem">
	<input type="hidden" name="id" value="<?php echo $identificador;?>"/>
	<button class="regresar">Regresar a Aplicación</button>
</form>
	
<form id="nuevoPerfilUsuario" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="guardarNuevoPerfilUsuario" >
	<input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value="<?php echo $identificador?>" />
	<input type="hidden" id="nombrePerfil" name="nombrePerfil" />
		
	<fieldset>
		<legend>Pefiles</legend>
			<div data-linea="1">
				<label>Perfil: </label>
				<select id="perfil" name="perfil" >
					<option value="" >Seleccione....</option>
					<?php 
						$qPerfiles=$cap->listarPerfilesXidAplicacion($conexion, $idAplicacion);
						while($fila=pg_fetch_assoc($qPerfiles)){
							echo '<option value='.$fila['id_perfil'].'>'.$fila['nombre'].'</option>';
						}
					?>
				</select>
			</div>
			<div data-linea="2">
				<button type="submit" class="mas">Agregar</button>
			</div>
	</fieldset>
</form>

<fieldset>
	<legend>Perfiles Usuario</legend>
		<table id="camposPerfiles" style="width:100%;">
			<tr>
				<th style="width:10%;">Id Perfil</th>
				<th style="width:80%;">Nombre</th>
				<th style="width:10%;">Eliminar</th>
			</tr>
			<?php 
			while ($fila = pg_fetch_assoc($qPerfilesUsuario)){
					echo $cap->imprimirLineaPerfilesUsuario($identificador,$fila['id_perfil'], $fila['nombre']);
			}
			?>
	</table>
</fieldset>
				
<script type="text/javascript">
	$('document').ready(function(){
		distribuirLineas();
	});

	acciones("#nuevoPerfilUsuario","#camposPerfiles",null,null,null,null,null,new validarInputs());

	function validarInputs() {
	    this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
			if ($("#perfil").val()==""){
			   error = true;
		       $("#perfil").addClass("alertaCombo");
		       $("#estado").html('Por favor seleccione un perfil para el usuario').addClass("alerta");
			}
	        return !error;
	    };
	}

	$("#perfil").change(function (event) {
		$("#nombrePerfil").val($("#perfil option:selected").text());
	});
</script>