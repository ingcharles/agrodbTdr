<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';
$conexion = new Conexion();
$cap = new ControladorAplicacionesPerfiles();

$idAplicacion=$_POST['id'];
$qOpcionesAplicacion=$cap->buscarOpcionesAplicacion($conexion, $idAplicacion);
$qPerfilesAplicacion=$cap->buscarPerfilesAplicacion($conexion, $idAplicacion);
$qDatosAplicacion=$cap->obtenerDatosAplicacion($conexion, $idAplicacion);
$datosAplicacion=pg_fetch_assoc($qDatosAplicacion);
$color=$datosAplicacion['color']==''?'#ffffff':$datosAplicacion['color'];
?>
<header>
	<h1>Modificar Información Aplicación</h1>
</header>
<div id="estado"></div>
<form id="modificarAplicacion" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="actualizarAplicacion" >
	<input type="hidden" id="idAplicacion" name="idAplicacion" value="<?php echo $idAplicacion;?>" /> 
	<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $_SESSION['usuario'];?>" />
	<div>
		<button id="modificar" type="button" class="editar">Editar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</div>	

	<fieldset>
		<legend>Datos Aplicación</legend>	
		<div data-linea="1">
			<label>Nombre: </label> 
			<input type="text" id="nombreAplicacion" name="nombreAplicacion" value="<?php echo $datosAplicacion['nombre'];?>" maxlength="256" disabled />
		</div>
		<div data-linea="1">
			<label>Version: </label> 
			<input type="text" id="versionAplicacion" name="versionAplicacion" value="<?php echo $datosAplicacion['version'];?>" maxlength="8" disabled />
		</div>
		<div data-linea="2">
			<label>Ruta: </label> 
			<input type="text" id="rutaAplicacion" name="rutaAplicacion" value="<?php echo $datosAplicacion['ruta'];?>"  maxlength="1024" disabled />
		</div>
		<div data-linea="2">
			<label>Color: </label> 
			<input name="colorAplicacion" id="colorAplicacion" type="color" value="<?php echo $color;?>" disabled />
		</div>
		<div data-linea="3">
			<label>Codificación: </label> 
			<input type="text" id="codificacionAplicacion" name="codificacionAplicacion" value="<?php echo $datosAplicacion['codificacion_aplicacion'];?>" maxlength="16" disabled />
		</div>
		<div data-linea="3">
			<label>Estado: </label>
			<select id="estadoAplicacion" name="estadoAplicacion" disabled >
					<option value="">Seleccione...</option>
					<option value="activo">Activo</option>
					<option value="inactivo">Inactivo</option>
			</select>
		</div>
		<div data-linea="4">
		<label>Descripción: </label> 
		<input type="text" id="descripcionAplicacion" name="descripcionAplicacion" value="<?php echo $datosAplicacion['descripcion'];?>" maxlength="1024" disabled />
	</div>
	</fieldset>
</form>

<form id="nuevoOpcionAplicacion" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="guardarNuevoOpcion" >
	<input type="hidden" id="idAplicacion" name="idAplicacion" value="<?php echo $idAplicacion?>" />
	<fieldset>
		<legend>Nueva Opción</legend>
			<div data-linea="1">
				<label>Nombre: </label>
				<input type="text" id="nombreOpcion" name="nombreOpcion"  maxlength="256"  />
			</div>
			<div data-linea="1">
				<label>Estilo: </label> 
				<input type="text" id="estiloOpcion" name="estiloOpcion" maxlength="256"  />
			</div>
			<div data-linea="2">
				<label>pagina: </label> 
				<input type="text" id="paginaOpcion" name="paginaOpcion" maxlength="256" />
			</div>
			<div data-linea="2">
				<label>orden: </label> 
				<input type="text" id="ordenOpcion" name="ordenOpcion" maxlength="2" />
			</div>
			<div data-linea="3">
				<button type="submit" class="mas">Agregar</button>
			</div>
	</fieldset>
</form>
				
<fieldset>
	<legend>Opciones Aplicación</legend>
	<table id="camposOpciones" style="width:100%;">
		<tr>
			<th>Nombre</th>
			<th>Estilo</th>
			<th>Pagina </th>
			<th>Orden</th>
			<th>Abrir</th>
			<th>Eliminar</th>
		</tr>
		<?php 
			while ($fila = pg_fetch_assoc($qOpcionesAplicacion)){
				echo $cap->imprimirLineaOpcionesAplicacion($fila['id_opcion'], $fila['nombre_opcion'],$fila['estilo'],$fila['pagina'],$fila['orden'],$idAplicacion);
			}
		?>
	</table>
</fieldset>
				
<form id="nuevoPerfilAplicacion" data-rutaAplicacion="asignarAplicacionPerfil" data-opcion="guardarNuevoPerfil" >
	<input type="hidden" id="idAplicacion" name="idAplicacion" value="<?php echo $idAplicacion?>" />
	<fieldset>
		<legend>Nuevo Perfil</legend>
			<div data-linea="1">
				<label>Nombre: </label>
				<input type="text" id="nombrePerfil" name="nombrePerfil"  maxlength="256"  />
			</div>
			<div data-linea="1">
				<label>Estado: </label> 
				<select id="estadoPerfil" name="estadoPerfil" >
					<option value="">Seleccione...</option>
					<option value="1" selected>Activo</option>
					<option value="2">Inactivo</option>
				</select>
			</div>
			<div data-linea="2">
				<label>Codificación: </label> 
				<input type="text" id="codificacionPerfil" name="codificacionPerfil" maxlength="256" />
			</div>	
			<div data-linea="3">
				<button type="submit" class="mas">Agregar</button>
			</div>
	</fieldset>
</form>
				
<fieldset>
	<legend>Perfiles Aplicación</legend>
		<table id="camposPerfiles" style="width:100%;">
			<tr>
				<th>Nombre</th>
				<th>Estado</th>
				<th>Codificación </th>
				<th>Abrir</th>
				<th>Eliminar</th>
			</tr>
			<?php 
				while ($fila = pg_fetch_assoc($qPerfilesAplicacion)){
					echo $cap->imprimirLineaPerfilesAplicacion($fila['id_perfil'], $fila['nombre'], $fila['estado'], $fila['codificacion_perfil'],$idAplicacion);
				}
			?>
		</table>
</fieldset>
				
<script type="text/javascript">
						
	$('document').ready(function(){
		distribuirLineas();
		cargarValorDefecto("estadoAplicacion","<?php echo $datosAplicacion['estado_aplicacion'];?>");
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

    acciones("#nuevoOpcionAplicacion","#camposOpciones",null,null,null,null,null,new validarInputs());
    acciones("#nuevoPerfilAplicacion","#camposPerfiles",null,null,null,null,null,new validarInputss());

    function validarInputs() {
	    this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	        
			if ($("#nombreOpcion").val()==""){
			    error = true;
		        $("#nombreOpcion").addClass("alertaCombo");
		        $("#estado").html('Por favor ingrese el nombre de la opción').addClass("alerta");
			}
		
			if ($("#estiloOpcion").val()==""){
			    error = true;
		        $("#estiloOpcion").addClass("alertaCombo");
		        $("#estado").html('Por favor ingrese el estilo de la opción').addClass("alerta");
			}
			
			if ($("#paginaOpcion").val()==""){
				error = true;
		        $("#paginaOpcion").addClass("alertaCombo");
		        $("#estado").html('Por favor ingrese la pagina de la opción').addClass("alerta");
			}

			if ($("#ordenOpcion").val()==""){
				error = true;
			   	$("#ordenOpcion").addClass("alertaCombo");
			    $("#estado").html('Por favor ingrese el orden de la opción').addClass("alerta");
			}

	        return !error; 
	    };
	}

    function validarInputss() {
	    this.ejecutar = function () {
	        var error = false;
	        $(".alertaCombo").removeClass("alertaCombo");
	        
			if ($("#nombrePerfil").val()==""){
			    error = true;
		        $("#nombrePerfil").addClass("alertaCombo");
		        $("#estado").html('Por favor ingrese el nombre del perfil').addClass("alerta");
			}
		
			if ($("#estadoPerfil").val()==""){
			    error = true;
		        $("#estadoPerfil").addClass("alertaCombo");
		        $("#estado").html('Por favor seleccione el estado del perfil').addClass("alerta");
			}
			
			if ($("#codificacionPerfil").val()==""){
				error = true;
		        $("#codificacionPerfil").addClass("alertaCombo");
		        $("#estado").html('Por favor ingrese la codificación del perfil').addClass("alerta");
			}
	        return !error; 
	    };
	}
	
	$("#aplicacion").change(function (event) {
		$("#nombreAplicacion").val($("#aplicacion option:selected").text());
	});

	$("#modificarAplicacion").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($.trim($("#descripcionAplicacion").val())=="" ){
			error = true;
			$("#descripcionAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la descripción de la aplicación.").addClass("alerta");
		}

		if($.trim($("#estadoAplicacion").val())=="" ){
			error = true;
			$("#estadoAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione el estado de la aplicación.").addClass("alerta");
		}

		if($.trim($("#codificacionAplicacion").val())=="" ){
			error = true;
			$("#codificacionAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la codificación de la aplicación.").addClass("alerta");
		}

		if($.trim($("#rutaAplicacion").val())=="" ){
			error = true;
			$("#rutaAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la ruta de la aplicación.").addClass("alerta");
		}

		if($.trim($("#versionAplicacion").val())=="" ){
			error = true;
			$("#versionAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la versión de la aplicación.").addClass("alerta");
		}

		if($.trim($("#nombreAplicacion").val())=="" ){
			error = true;
			$("#nombreAplicacion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el nombre de la aplicación.").addClass("alerta");
		}
		
		if (!error){
			ejecutarJson($(this));
		}
	});
</script>