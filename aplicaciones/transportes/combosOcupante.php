<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();

$idAreaPadre = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
$categoriaArea = htmlspecialchars ($_POST['categoriaArea'],ENT_NOQUOTES,'UTF-8');

	if($categoriaArea == '3' || $categoriaArea == '1'){
		$areaSubproceso = "'".$idAreaPadre."'";

	}else{
		$qAreasSubProcesos = $ca->buscarAreasSubprocesos($conexion, $idAreaPadre);
		
		if(pg_num_rows($qAreasSubProcesos) > 0){
			$areaSubproceso = "'".$idAreaPadre."',";
			
			while($fila = pg_fetch_assoc($qAreasSubProcesos)){
				$areaSubproceso .= "'".$fila['id_area']."',";
			}
		}else{
			$areaSubproceso = "'".$idAreaPadre."'";
		}
	}

$areaSubproceso = "(".rtrim($areaSubproceso,',').")";

$ocupantes = $ca->obtenerFuncionariosXareas($conexion, $areaSubproceso);

		echo '<label>Personas</label>
				<select id="ocupante" name="ocupante" required>
				<option value="" selected="selected" >Seleccione....</option>';
					while ($fila = pg_fetch_assoc($ocupantes)){
						echo '<option value="' . $fila['identificador'] . '">' . strtoupper($fila['apellido'] .' '. $fila['nombre']) . '</option>';
					}
		echo '	<option value="Otro">Otro</option>
			  </select>';

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$('#ocupante').change(function(){
		if($('#ocupante option:selected').attr("value")=="Otro"){
			$("#opcion_ocupante").show();
		}else{
			$("#opcion_ocupante").hide();
		}
			 
	});
</script>
