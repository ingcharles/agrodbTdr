<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ce = new ControladorCatastro();
$res = $ce->obtenerDatosFamiliares($conexion, $_SESSION['usuario']);
$familiar = pg_fetch_assoc($res);
?>

<header>
	<h1>Familiares y Contactos</h1>
</header>

<form id="datosContactos" data-rutaAplicacion="uath" data-opcion="guardarDatosContactos">
	<input type="hidden" id="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="opcion" value="" name="opcion" />

	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		<button id="adjunto" type="button" class="adjunto" data-rutaaplicacion="uath" data-opcion="modificarEnfermedad" data-destino="detalleItem">Enfermedades</button>
	</p>
	<div id="estado"></div>
	<table class="soloImpresion">
	<tr><td></td>
	<td>
	<fieldset>
		<legend>Información básica</legend>
			<label>Nombres</label> 
				<input type="text" name="nombre" value="<?php echo $familiar['nombre'];?>" disabled="disabled" required="required"/> 
			<label>Apellidos</label>
				<input type="text" name="apellido" 	value="<?php echo $familiar['apellido'];?>" disabled="disabled" required="required"/> 
			<label>Relacion</label>
				<input type="text" name="relacion" 	value="<?php echo $familiar['relacion'];?>" disabled="disabled" required="required"/> 
			<label>Fecha de nacimiento</label> 
				<input type="text"	id="nacimiento" name="nacimiento"	value="<?php echo date('j/n/Y',strtotime($familiar['fecha_nacimiento']));?>" disabled="disabled" /> 
			<label>Edad</label>
				<input type="text" name="edad" 	value="<?php echo $familiar['edad'];?>" disabled="disabled" required="required"/> 
	</fieldset>
	<fieldset>
		<legend>Ubicación</legend>
			<label>Calle Principal</label> 
				<input type="text" name="calle_principal" value="<?php echo $familiar['calle_principal'];?>" disabled="disabled" required="required"/> 
			<label>Número</label>
				<input type="text" name="numero" 	value="<?php echo $familiar['numero'];?>" disabled="disabled" /> 
			<label>Calle Secundaria</label>
				<input type="text" name="calle_secundaria" 	value="<?php echo $familiar['calle_secundaria'];?>" disabled="disabled" /> 
			<label>Referencia</label>
				<input type="text" name="referencia" 	value="<?php echo $familiar['referencia'];?>" disabled="disabled" />
	</fieldset>
	<fieldset>
		<legend>Teléfonos</legend>
			<label>Convencional</label>
				<input type="text" name="telefono" 	value="<?php echo $familiar['telefono'];?>" disabled="disabled" /> 
			<label>Celular</label>
				<input type="text" name="celular" 	value="<?php echo $familiar['celular'];?>" disabled="disabled" />
			<label>Oficina</label>
				<input type="text" name="telefono_oficina" 	value="<?php echo $familiar['telefono_oficina'];?>" disabled="disabled" /> 
			<label>Extension</label>
				<input type="text" name="extension" 	value="<?php echo $familiar['extension'];?>" disabled="disabled" />
		
	</fieldset>
	</td></tr></table>
</form>

<script type="text/javascript">

	$("#datosContactos").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});
  
  	$("#adjunto").click(function(event){
  		abrir($("#adjunto"),event, false);
  	});
  	
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		if($('select[name="etnia"] option:selected').attr("value")!="Indigena"){
			$('[name="indigena"]').attr("disabled","disabled");
		} else{
			$('[name="indigena"]').removeAttr("disabled");
		}
	});

	

	$(document).ready(function(){
		// $('select[name="sexo"]').find('option[value="<?php echo $empleado['genero'];?>"]').prop("selected","selected");
		// cargarValorDefecto("sexo","<?php echo $empleado['genero'];?>");
		// cargarValorDefecto("estadoCivil","<?php echo $empleado['estado_civil'];?>");
		// cargarValorDefecto("sangre","<?php echo $empleado['tipo_sangre'];?>");
		// cargarValorDefecto("nacionalidad","<?php echo $empleado['nacionalidad'];?>");
		// cargarValorDefecto("etnia","<?php echo $empleado['identificacion_etnica'];?>");
		// cargarValorDefecto("indigena","<?php echo $empleado['nacionalidad_indigena'];?>");
		// $( "#nacimiento" ).datepicker({
		      // changeMonth: true,
		      // changeYear: true
		    // });
		// abrir($("#datosPersonales input:hidden"),null,false);
		
    	
	});

	$('select[name="etnia"]').change(function(){
		if($('select[name="etnia"] option:selected').attr("value")!="Indigena"){
			cargarValorDefecto($('[name="indigena"] option'),"No aplica");
			$('[name="indigena"]').attr("disabled","disabled");
		} else{
			$('[name="indigena"]').removeAttr("disabled");
		}
	});
</script>
