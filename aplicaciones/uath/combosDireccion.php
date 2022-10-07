<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$ca = new ControladorAreas();

$idAreaPadre = htmlspecialchars ($_POST['coordinacion'],ENT_NOQUOTES,'UTF-8');
$nombreOficina = htmlspecialchars ($_POST['nombreOficina'],ENT_NOQUOTES,'UTF-8');

		echo '<div data-linea="33">
				<label>Dirección - Oficina Técnica</label>
					<select id="direccion" name="direccion" required>
						<option value="" selected="selected" >Seleccione....</option>';
						
		if($idAreaPadre != 'DE'){
			$qAreasSubProcesos = $ca->buscarDivisionEstructura($conexion, $idAreaPadre);
				
			while ($fila = pg_fetch_assoc($qAreasSubProcesos)){
				echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" data-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'] . '</option>';
			}
			
		}else{
			
			if($nombreOficina == 'Oficina Planta Central' ){
				$qDireccionEjecutiva = $ca->buscarArea($conexion, $idAreaPadre);
				
				while ($fila = pg_fetch_assoc($qDireccionEjecutiva)){
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" data-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'] . '</option>';
				}
				
				$qDireccionesGenerales = $ca->buscarDireccionesGenerales($conexion);
					
				while ($fila = pg_fetch_assoc($qDireccionesGenerales)){
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" data-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'] . '</option>';
				}
			}else if($nombreOficina == 'Laboratorios Tumbaco'){
				
				$qDireccionEjecutiva = $ca->buscarArea($conexion, $idAreaPadre);
				
				while ($fila = pg_fetch_assoc($qDireccionEjecutiva)){
					if($fila['id_area'] != 'CGL' && $fila['id_area'] != 'CGSA' && $fila['id_area'] != 'CGRIA')
										echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" data-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'] . '</option>';
				}
				
				$qDireccionesGenerales = $ca->buscarDireccionesGenerales($conexion);
					
				while ($fila = pg_fetch_assoc($qDireccionesGenerales)){
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" data-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'] . '</option>';
				}
				
			}else{
				$qOficinasTecnicas = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Oficina Técnica')", "(4)");
					
				while ($fila = pg_fetch_assoc($qOficinasTecnicas)){
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" data-padre="' . $fila['id_area_padre'] . '">' . $fila['nombre'] . '</option>';
				}
			}

		}			
					
		echo '</select>
				<input type="hidden" id="nombreDireccion" name="nombreDireccion" />
				<input type="hidden" id="idDireccionPadre" name="idDireccionPadre" />
			</div>';

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$('#direccion').change(function(event){
		$("#nombreDireccion").val($('#direccion option:selected').text());
		$("#idDireccionPadre").val($('#direccion option:selected').attr('data-padre'));
		
		$("#datosContrato").attr('data-opcion', 'combosGestion');
	    $("#datosContrato").attr('data-destino', 'dGestionUnidad');
	    abrir($("#datosContrato"), event, false); //Se ejecuta ajax
    });
</script>