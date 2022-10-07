<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
$conexion = new Conexion();
$cp = new ControladorCatastroProducto();

$idModificacionIdentificador = $_POST['id'];

$qModificacionIdentificador = $cp->obtenerOperadorModificacionIdentificadorPorId($conexion, $idModificacionIdentificador);
$modificacionIdentificador = pg_fetch_assoc($qModificacionIdentificador);

?>

<div id="estado"></div>
<header>
	<h1>Administrar Modificación Catastro</h1>
</header>

<form id="habilitarModificacionCatastro" data-rutaAplicacion="catastroProducto" data-opcion="actualizarHabilitarModificacionCatastro" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<input type="hidden" id="idModificacionIdentificador" name="idModificacionIdentificador"	value="<?php echo $idModificacionIdentificador;?>" />

	<fieldset >	
		<legend>Datos Operador</legend>
		
			<div data-linea="1" >			
				<label>Identificación operador: </label>
				<input type="text" id="identificacionOperador" name="identificacionOperador" value="<?php echo $modificacionIdentificador['identificador_operador']; ?>" disabled="disabled" readonly="readonly" />
			</div>
			<div data-linea="2" >			
				<label>Nombre operador: </label>
				<input type="text" id="nombreOperador" name="nombreOperador" value="<?php echo $modificacionIdentificador['nombre_operador']; ?>" disabled="disabled" readonly="readonly" />
			</div>
			<div data-linea="3" >			
				<label>Modificar catastro: </label>
				<select id="modificarIdentificador" name="modificarIdentificador"disabled="disabled">
    				<option value="" selected="selected">Seleccione....</option>
    				<option value="SI">SI</option>
    				<option value="NO">NO</option>
				</select>
			</div>
			<div data-linea="4" >
				<label>Número de GLPI: </label>
				<input type="text" id="observacionModificacion" name="observacionModificacion" value="<?php echo $modificacionIdentificador['observacion_modificacion_identificador']; ?>" disabled="disabled" />		
			</div>
	</fieldset>
</form>

<script type="text/javascript">

$(document).ready(function(event){

	cargarValorDefecto("modificarIdentificador","<?php echo $modificacionIdentificador['habilitar_modificacion_identificador']; ?>");
	distribuirLineas();

});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$("#observacionModificacion").val("");
	$(this).attr("disabled","disabled");
});

$("#habilitarModificacionCatastro").submit(function(event){

	event.preventDefault();
	$("#estado").html("").removeClass('alerta');
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#modificarIdentificador").val() == ""){
		error = true;
		$("#modificarIdentificador").addClass("alertaCombo");
	}

	if(!$.trim($("#observacionModificacion").val())){
		error = true;
		$("#observacionModificacion").addClass("alertaCombo");
	}

	if (error){
		$("#estado").html("Por favor revise la información ingresada.").addClass("alerta");;
	}else{
		ejecutarJson("#habilitarModificacionCatastro");
	}

});

</script>