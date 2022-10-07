<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$ca = new ControladorCatastro();

$idRegimenLaboral = htmlspecialchars ($_POST['regimen_laboral'],ENT_NOQUOTES,'UTF-8');

if($idRegimenLaboral == 2 || $idRegimenLaboral == 3){
	$qGrupoOcupacional = $ca->obtenerGrupoOcupacionalXRegimenLaboral($conexion, 2);
}else{
	$qGrupoOcupacional = $ca->obtenerGrupoOcupacionalXRegimenLaboral($conexion, $idRegimenLaboral);
}

if($idRegimenLaboral != ''){
		echo '<div data-linea="36">
				<label>Grupo ocupacional</label> 
					<select name="grupo_ocupacional" id="grupo_ocupacional" required>
						<option value="" selected="selected" >Seleccione....</option>';
						
							while ($fila = pg_fetch_assoc($qGrupoOcupacional)){
								echo '<option value="' . $fila['nombre_grupo'] . '" data-remuneracion="'. $fila['remuneracion'].'" data-grado="'. $fila['grado'].'">' . $fila['nombre_grupo'].' </option>';
							}
					
		echo '		</select>
			</div>';
		
}else{
	echo '<div data-linea="0">
	<label class="alerta">Por favor seleccione un régimen laboral para continuar</label>
	</div>';
}
?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$("#grupo_ocupacional").change(function(){
		$("#lRemuneracion").show();
    	$("#remuneracion").show();
    	$("#lGrado").show();
    	$("#grado").show();

    	$("#remuneracion").val( $("#grupo_ocupacional option:selected").attr("data-remuneracion"));
		$("#grado").val( $("#grupo_ocupacional option:selected").attr("data-grado"));
	});

</script>