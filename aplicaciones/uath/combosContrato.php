<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$idRegimen = htmlspecialchars ($_POST['regimen_laboral'],ENT_NOQUOTES,'UTF-8');

if($idRegimen != ''){
	$qModalidadContrato = $cc->obtenerModalidadContratoXRegimen($conexion, $idRegimen);
	
	echo '<div data-linea="2">
	<label>Modalidad Contrato</label>
	<select name="tipo_contrato"  id="tipo_contrato" required >
	<option value="">Seleccione....</option>';
	
	while ($fila = pg_fetch_assoc($qModalidadContrato)){
		echo '<option value="' . $fila['id_modalidad_contrato'] . '" >' . $fila['nombre'] . '</option>';
	}
		
	echo '</select>
	<input type="hidden" id="nombreModalidadContrato" name="nombreModalidadContrato" />
	</div>';
}else{
	echo '<div data-linea="0">
	<label class="alerta">Por favor seleccione un régimen laboral para continuar</label>
	</div>';
}

?>

<script type="text/javascript">

	$(document).ready(function(){
		$('#separacion').hide();
		$('#dPresupuesto').html('');
		$('#dFuente').html('');
		$('#dPartida').html('');
		
		distribuirLineas();	
	});

	$('#tipo_contrato').change(function(event){
		$("#nombreModalidadContrato").val($('#tipo_contrato option:selected').text());
		
		$("#datosContrato").attr('data-opcion', 'combosPresupuesto');
	    $("#datosContrato").attr('data-destino', 'dPresupuesto');
	    abrir($("#datosContrato"), event, false); //Se ejecuta ajax
    });
</script>