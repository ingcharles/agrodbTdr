<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$operaciones = ($_POST['elementos'] == '' ? $_POST['id'] : $_POST['elementos']);
$nombreOpcion = $_POST['opcion'];

$identificadorInspector = $_SESSION['usuario'];

$qOperadorSitio = $cro->obtenerOperadorSitioInspeccion($conexion, $operaciones);
$operadorSitio = pg_fetch_assoc($qOperadorSitio);

$operacionFlujo = explode(',', $operaciones);

$idflujoOPeracion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $operacionFlujo[0]));
$idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], 'documental'));

if($idFlujoActual['estado_alterno'] != ''){
    $subsanacion = '<option value="'.$idFlujoActual['estado_alterno'].'">Subsanación</option>';
}

$formulario = "";

$formulario .= '<fieldset>
            		<legend>Datos del operador</legend>
            		<div data-linea="1">
            			<label>Identificador operador: </label>' . $operadorSitio['identificador'] . '
            		</div>
            		<div data-linea="2">
            			<label>Razón Social: </label>' . $operadorSitio['nombre_operador'] . '
            		</div>
                </fieldset>';

echo $formulario;

?>

<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitudAgrupada" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>" />
	<input type="hidden" name="idSolicitud"	value="<?php echo $operaciones;?>" /> 
	<input type="hidden" name="tipoSolicitud" value="Operadores" /> 
	<input type="hidden" name="tipoInspector" value="Documental" /> 
	<input type="hidden" name="identificadorOperador" value="<?php echo $operadorSitio['identificador'];?>" /> 
	<input type="hidden" name="nombreOpcion" value="<?php echo $nombreOpcion;?>" />
	<input type="hidden" name="codigoProvinciaSitio" value="<?php echo $operadorSitio['codigo_provincia'];?>" />

	<fieldset>
		<legend>Resultado de Revisión Documental</legend>
		<div data-linea="1">
			<label>Resultado</label> <select id="resultadoDocumento"
				name="resultadoDocumento">
				<option value="">Seleccione...</option>
				<option value="noHabilitado">No habilitado</option>
				<?php echo $subsanacion; ?>
			</select>
		</div>
		<div data-linea="2">
			<label>Observaciones</label> <input type="text"
				id="observacionDocumento" name="observacionDocumento" />
		</div>
	</fieldset>
	<button type="submit" class="guardar">Enviar resultado</button>
</form>

<script type="text/javascript">
    
    $(document).ready(function(){
    	distribuirLineas();
    	construirValidador()		
    });	 

    $("#evaluarSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccion(this);
	});

    function chequearCamposInspeccion(form){

    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultadoDocumento").val()) || !esCampoValido("#resultadoDocumento")){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}
	
		if($("#resultadoDocumento").val() == 'noHabilitado' || $("#resultadoDocumento").val() == 'subsanacion'){
			if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}
				
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{			   
			ejecutarJson(form);
		}
		
	}

</script>
