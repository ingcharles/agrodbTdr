<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();

$idAreaPadre = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
//$categoriaArea = htmlspecialchars ($_POST['categoriaArea'],ENT_NOQUOTES,'UTF-8');

if($idAreaPadre == 'DE' || substr($idAreaPadre,0,2) == 'CG'){
	$qAreasSubProcesos = $ca->buscarArea($conexion, $idAreaPadre);
}else{
	$qAreasSubProcesos = $ca->buscarDivisionEstructura($conexion, $idAreaPadre);
}

		echo '<div data-linea="13">
				<label>Gestión-Unidad</label>
					<select id="coordinacion" name="coordinacion" required>
						<option value="" selected="selected" >Seleccione....</option>';
						
						while ($fila = pg_fetch_assoc($qAreasSubProcesos)){
							echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" data-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'] . '</option>';
						}
					
		echo '</select>
				<input type="hidden" id="nombreCoordinacion" name="nombreCoordinacion" />
				<input type="hidden" id="areaClasificacion" name="areaClasificacion" />
			</div>';

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$('#coordinacion').change(function(event){
		$("#areaClasificacion").val($('#coordinacion option:selected').attr('data-clasificacion'));
		$("#nombreCoordinacion").val($('#coordinacion option:selected').text());
		
		$("#datosContrato").attr('data-opcion', 'combosPuesto');
	    $("#datosContrato").attr('data-destino', 'dPuesto');
	    abrir($("#datosContrato"), event, false); //Se ejecuta ajax
    });
</script>