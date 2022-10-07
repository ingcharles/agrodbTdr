<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();

$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$nombreProvincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	case 'sitios';
	$qSitios = $cr->obtenerSitioyCodigoPorProvinciaOperadoryOperacion($conexion,'CUA','SA', $identificadorOperador, $nombreProvincia);
	echo '<div data-linea="4">
		<label>Sitio de cuarentenario: </label>
		<select id="sitio" name="sitio" >
			<option value="">Seleccione....</option>';
	while ($fila = pg_fetch_assoc($qSitios)){
		echo '<option value="'.$fila['id_sitio'].'">'.$fila['sitio'].'</option>';
	}
	echo '</select></div>';
	break;
	
	case 'areas';
		$idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
		$sArea=$cr->obtenerAreayCodigoPorSitioProvinciaOperadoryOperacion($conexion,'CUA','SA', $identificadorOperador, $nombreProvincia, $idSitio);
		echo '<div data-linea="4">
			<label>Área de cuarentena: </label>
			<select id="area" name="area" >
				<option value="">Seleccione....</option>';
		while ($fila = pg_fetch_assoc($sArea)){
			echo '<option value="'.$fila['id_area'].'">'.$fila['area'].'</option>';
		}
		echo '</select></div>';
	break;
	
}

?>

<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();
});

$("#sitio").change(function(event){
	$("#sitio").removeClass("alertaCombo");
		
	if($("#sitio").val()!=""){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$("#requiereSeguimiento").is(':checked')){
			 error = true;	
			 $("#requiereSeguimiento").addClass("alertaCombo");
				$("#estado").html("Por favor seleccione el campo seguimiento cuarentenario.").addClass('alerta');
		}
		
		if (!error){
			$("#estado").html("").removeClass('alerta');
			$('#opcion').val('areas');
			$('#evaluarDocumentosSolicitud').attr('data-destino','resultadoAreas');
			$('#evaluarDocumentosSolicitud').attr('data-opcion','comboSitioCuarentenario');
			abrir($("#evaluarDocumentosSolicitud"),event,false);
		}
	}
 });
</script>