<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';
$conexion = new Conexion();
$cap = new ControladorAplicacionesPerfiles();

$idPerfil=$_POST['idPerfil'];
$qDatosPerfil=$cap->buscarPerfil($conexion, $idPerfil);
$datosPerfil=pg_fetch_assoc($qDatosPerfil);
$qAccionesOpciones=$cap->buscarAccionesOpcionesXidAplicacion($conexion, $datosPerfil['id_aplicacion']);
$qAccionesPerfiles=$cap->buscarAccionesPerfilesXidAplicacion($conexion, $datosPerfil['id_aplicacion'],$idPerfil);
?>
<header>
	<h1>Modificar Información Perfil</h1>
</header>
<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="abrirAplicacion" data-destino="detalleItem">
	<input type="hidden" name="id" value="<?php echo $datosPerfil['id_aplicacion'];?>"/>
	<button class="regresar">Regresar Aplicación</button>
</form>

<form id="modificarPerfil" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="actualizarPerfil" >
	<input type="hidden" id="idPerfil" name="idPerfil" value="<?php echo $idPerfil;?>" /> 
	<div>
		<button id="modificar" type="button" class="editar">Editar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</div>	
	
	<fieldset>
		<legend>Datos Perfil</legend>	
			<div data-linea="1">
				<label>Nombre: </label> 
				<input type="text" id="nombrePerfil" name="nombrePerfil" value="<?php echo $datosPerfil['nombre'];?>" maxlength="256" disabled />
			</div>
			<div data-linea="1">
				<label>Estado: </label> 
				<select id="estadoPerfil" name="estadoPerfil"  disabled >
					<option value="">Seleccione...</option>
					<option value="1">Activo</option>
					<option value="2">Inactivo</option>
				</select>
			</div>
			<div data-linea="2">
				<label>Codificación: </label> 
				<input type="text" id="codificionPerfil" name="codificionPerfil" value="<?php echo $datosPerfil['codificacion_perfil'];?>"  maxlength="1024" disabled />
			</div>
	</fieldset>
</form>

<form id="nuevoAccionPerfil" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="guardarNuevoAccionPerfil" >
	<input type="hidden" id="idAplicacion" name="idAplicacion" value="<?php echo $idAplicacion; ?>" />
	<input type="hidden" id="idPerfil" name="idPerfil" value="<?php echo $idPerfil; ?>" />
	<input type="hidden" id="idOpcion" name="idOpcion"  />
	<input type="hidden" id="opcion" name="opcion"  />
	<input type="hidden" id="accion" name="accion"  />
	
	<fieldset>
		<legend>Nuevo Acción Perfil</legend>
			<div data-linea="1">
				<label>Acciones: </label> 
				<select id="idAccion" name="idAccion">
					<option value="">Seleccione...</option>
					<?php 
						while($fila=pg_fetch_assoc($qAccionesOpciones)){
							echo '<option data-idOpcion="'.$fila['id_opcion'].'" data-accion="'.$fila['descripcion'].'" data-opcion="'.$fila['nombre_opcion'].'" value="'.$fila['id_accion'].'">'.$fila['nombre_opcion'].' --> '.$fila['descripcion'].' </option>';
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
	<legend>Acciones Perfil</legend>
	<table id="camposAccionesPerfil" style="width:100%;">
		<tr>
			<th style="width:50%; text-align:left;">Opción</th>
			<th style="width:40%; text-align:left;">Acción </th>
			<th style="width:10%;" >Eliminar</th>
		</tr>
		<?php 
			while ($fila = pg_fetch_assoc($qAccionesPerfiles)){
				echo $cap->imprimirLineaAccionesPerfil($idPerfil, $fila['id_accion'], $fila['nombre_opcion'], $fila['descripcion']);
			}
		?>
	</table>
</fieldset>

<script type="text/javascript">
			
	$('document').ready(function(){
		distribuirLineas();
		cargarValorDefecto("estadoPerfil","<?php echo $datosPerfil['estado'];?>");
	});
	
	$("#idAccion").change(function (event) {
		$("#opcion").val($("#idAccion option:selected").attr('data-opcion'));
		$("#accion").val($("#idAccion option:selected").attr('data-accion'));
		$("#idOpcion").val($("#idAccion option:selected").attr('data-idOpcion'));
	});
	
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});
	
	acciones("#nuevoAccionPerfil","#camposAccionesPerfil",null,null,null,null,null,new validarInputs());
	
	function validarInputs() {
	    this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
			if ($("#idAccion").val()==""){
			   error = true;
		       $("#idAccion").addClass("alertaCombo");
		       $("#estado").html('Por favor seleccione una acción').addClass("alerta");
			}
	        return !error;
	    };
	}
	
	$("#estiloAccion").change(function(event){
		if($("#estiloAccion").val()!="0"){
	    	if($("#estiloAccion").val()!="")
	        	$("#descripcionAccion").val($("#estiloAccion option:selected").text());
	        else
	        	$("#descripcionAccion").val('TODO');    	
	   	}else{
	   		$("#descripcionAccion").val("");
	    }   
	});
	    
	$("#modificarPerfil").submit(function(event){
		event.preventDefault();
	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($.trim($("#nombrePerfil").val())=="" ){
			error = true;
			$("#nombrePerfil").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el nombre del perfil.").addClass("alerta");
		}
	
		if($.trim($("#estadoPerfil").val())=="" ){
			error = true;
			$("#estadoPerfil").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione el estado del perfil.").addClass("alerta");
		}
	
		if($.trim($("#codificionPerfil").val())=="" ){
			error = true;
			$("#codificionPerfil").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la codificación del perfil.").addClass("alerta");
		}
	
		if (!error){
			ejecutarJson($(this));
		}
	});
</script>