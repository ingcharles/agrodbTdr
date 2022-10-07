<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$ca = new ControladorAreas();

$nombreOficina = htmlspecialchars ($_POST['nombreOficina'],ENT_NOQUOTES,'UTF-8');

		echo '<div data-linea="32" style="width:100%">
				<label id="lCoordinacion">Coordinación</label> 
					<select id="coordinacion" name="coordinacion" style="width:100%">
						<option value="" >Seleccione...</option>';
						
		if($nombreOficina == 'Oficina Planta Central'){
			$qCoordinaciones = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central')", "(1,3)");
			
			while ($fila = pg_fetch_assoc($qCoordinaciones)){
				if($fila['clasificacion'] == 'Planta Central' && $fila['id_area'] != 'CGL' && $fila['id_area'] != 'CGSA' && $fila['id_area'] != 'CGRIA'){
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" >' . $fila['nombre'] . '</option>';
				}
			}
		}else if($nombreOficina == 'Laboratorios Tumbaco'){
			$qCoordinaciones = $ca->buscarArea($conexion, 'CGL');
			
			while ($fila = pg_fetch_assoc($qCoordinaciones)){
				echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '">' . $fila['nombre'] . '</option>';
			}
			
			$qCoordinaciones = $ca->buscarArea($conexion, 'CGSA');
				
			while ($fila = pg_fetch_assoc($qCoordinaciones)){
				echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '">' . $fila['nombre'] . '</option>';
			}
			
			$qCoordinaciones = $ca->buscarArea($conexion, 'CGIA');
				
			while ($fila = pg_fetch_assoc($qCoordinaciones)){
				echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '">' . $fila['nombre'] . '</option>';
			}
			
			$qCoordinaciones = $ca->buscarArea($conexion, 'CGRIA');
			
			while ($fila = pg_fetch_assoc($qCoordinaciones)){
				echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '">' . $fila['nombre'] . '</option>';
			}
		}else{
			$qCoordinaciones = $ca->buscarArea($conexion, 'DE');
			
			while ($fila = pg_fetch_assoc($qCoordinaciones)){
				echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '">' . $fila['nombre'] . '</option>';
			}
		}
					
		echo '		</select>
				<input type="hidden" id="nombreCoordinacion" name="nombreCoordinacion" />
				<input type="hidden" id="clasificacionCoordinacion" name="clasificacionCoordinacion" />
			</div>';

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$('#coordinacion').change(function(event){
		$("#nombreCoordinacion").val($('#coordinacion option:selected').text());
		$("#clasificacionCoordinacion").val($('#coordinacion option:selected').attr('data-clasificacion'));
				
		$("#datosContrato").attr('data-opcion', 'combosDireccion');
	    $("#datosContrato").attr('data-destino', 'dDireccionOficina');
	    abrir($("#datosContrato"), event, false); //Se ejecuta ajax
    });
</script>