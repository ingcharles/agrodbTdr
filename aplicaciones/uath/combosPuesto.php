<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$ca = new ControladorCatastro();

$idRegimen = htmlspecialchars ($_POST['regimen_laboral'],ENT_NOQUOTES,'UTF-8');

$idAreaDireccion = htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8');
$idDireccionPadre = htmlspecialchars ($_POST['idDireccionPadre'],ENT_NOQUOTES,'UTF-8');

$idAreaGestion = htmlspecialchars ($_POST['gestion'],ENT_NOQUOTES,'UTF-8');
$clasificacion = htmlspecialchars ($_POST['areaClasificacion'],ENT_NOQUOTES,'UTF-8');

if($idRegimen != 5){
	if($clasificacion == 'Planta Central'){
		$qPuestos = $ca->obtenerPuestoXArea($conexion, $idAreaGestion);
		
	}else{
		if(substr($idAreaGestion,0,2) == 'OT'){
			if(strlen($idDireccionPadre) > 4){
				$qPuestos = $ca->obtenerPuestoXArea($conexion, 'DDAT');
			}else{
				$qPuestos = $ca->obtenerPuestoXArea($conexion, 'DD');
			}
		}else if(substr($idAreaGestion,0,4) == 'UDAT'){
			$qPuestos = $ca->obtenerPuestoXArea($conexion, 'UDAT');
		}else{
			$qPuestos = $ca->obtenerPuestoXArea($conexion, 'UD');
		}
	}
}else{
	$qPuestos = $ca->obtenerPuestoXArea($conexion, 'AGR');
}

		echo '<hr/>
				<div data-linea="35">
					<label>Puesto institucional</label>
						<select id="puesto_institucional" name="puesto_institucional" required>
							<option value="" selected="selected" >Seleccione....</option>';
							
								while ($fila = pg_fetch_assoc($qPuestos)){
									echo '<option value="' . $fila['nombre_puesto'] . '" data-area="' . $fila['id_area'] . '" >' . $fila['nombre_puesto'] . '</option>';
								}
						
		echo '		</select>
			</div>';
		
?>

<script type="text/javascript">

$(document).ready(function(){
	distribuirLineas();	
	$("#divisor").hide();
});

$('#puesto_institucional').change(function(event){
	$("#nombreGestion").val($('#gestion option:selected').text());
	
	$("#datosContrato").attr('data-opcion', 'combosGrupoOcupacional');
    $("#datosContrato").attr('data-destino', 'dGrupoOcupacional');
    abrir($("#datosContrato"), event, false); //Se ejecuta ajax
});

</script>