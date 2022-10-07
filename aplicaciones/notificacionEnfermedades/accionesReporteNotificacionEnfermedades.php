<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();	
$cre = new ControladorNotificacionEnfermedades();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$animal = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
$tipoEnfermedad = htmlspecialchars ($_POST['tipoEnfermedad'],ENT_NOQUOTES,'UTF-8');
$enfermedad = htmlspecialchars ($_POST['enfermedad'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {

	case 'producto':
				
				echo '<select id="tipoEnfermedad" name="tipoEnfermedad" style="width: 420px;">
				<option value="TODOS">TODOS</option>';
				$tipoEnfermedad = $cre->obtenerTipoEnfermedad($conexion,$animal);
					while ($fila = pg_fetch_assoc($tipoEnfermedad)){
						echo '<option value="'.$fila['id_tipo_enfermedad']. '">'. $fila['nombre_tipo_enfermedad'] .'</option>';
				}
				
				echo '</select>';
	
	break;
	
	case 'tipoEnfermedad':
	
				echo '<select id="enfermedad" name="enfermedad" style="width: 420px;">
				<option value="TODOS">TODOS</option>';
	
				$enfermedad = $cre->obtenerEnfermedad($conexion,$tipoEnfermedad);
				while ($fila = pg_fetch_assoc($enfermedad)){
					echo '<option value="'.$fila['id_enfermedad']. '">'. $fila['nombre_enfermedad'] .'</option>';
				}
				
				echo '</select>';
	
	break;

}

?>

<script type="text/javascript"> 

	$(document).ready(function(){		
		distribuirLineas(); 
	});

	$("#tipoEnfermedad").change(function(){
		$('#filtrarReporteEnfermedades').attr('data-opcion','accionesReporteNotificacionEnfermedades');
    	$('#filtrarReporteEnfermedades').attr('data-destino','resultadoTipoEnfermedad');
    	$('#opcion').val('tipoEnfermedad');	
	 	$('#divEsconderEnfermedad').hide();
    	abrir($("#filtrarReporteEnfermedades"),event,false);	
	 });
	 
</script>