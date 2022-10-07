<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$idRegimen = htmlspecialchars ($_POST['regimen_laboral'],ENT_NOQUOTES,'UTF-8');
$idTipoContrato = htmlspecialchars ($_POST['tipo_contrato'],ENT_NOQUOTES,'UTF-8');

if($idRegimen != ''){
	$qPresupuesto = $cc->obtenerPresupuestoXRegimen($conexion, $idRegimen);
	
	echo '<div data-linea="2">
	<label>Presupuesto</label>
	<select name="presupuesto"  id="presupuesto" required >
	<option value="" selected="selected" >Seleccione....</option>';
	
	while ($fila = pg_fetch_assoc($qPresupuesto)){
		
		echo '<option value="' . $fila['nombre'] .' - '. $fila['partida_presupuestaria'] . '" data-fuente="' . $fila['fuente'] . '" >' . $fila['nombre'] .' - '. $fila['partida_presupuestaria'] . '</option>';
	}
		
	echo '</select>
	</div>
	
	<div data-linea="26" style="width:100%">
		<label>Fuente</label> 
			<input type="text" id="fuente" name="fuente" readonly="readonly"/>
	</div>';
	
	if($idRegimen == 1 || $idRegimen == 3){
		echo '<div data-linea="27" style="width:100%">
				<label id="lPartidaIndividual">Partida individual</label> 
					<input type="text" id="partida_individual" name="partida_individual" />
			  </div>';
	}
		
}else{
	echo '<div data-linea="2">
	<label class="alerta">Por favor seleccione un régimen laboral para continuar</label>
	</div>';
}

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$('#presupuesto').change(function(event){
		$('#fuente').val($('#presupuesto option:selected').attr('data-fuente'));
		$('#dFuente').html('');
		$('#dPartida').html('');
    });
</script>