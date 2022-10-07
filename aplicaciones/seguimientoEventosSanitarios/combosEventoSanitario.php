<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorEventoSanitario.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ces = new ControladorEventoSanitario();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$idEspecie = htmlspecialchars ($_POST['especie'],ENT_NOQUOTES,'UTF-8');
$idEspecieCategoria = htmlspecialchars ($_POST['especiePoblacion'],ENT_NOQUOTES,'UTF-8');
$idEspecieFinal = htmlspecialchars ($_POST['especieFinal'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
		
	case 'buscarFinalidad':
		
		$tiposExplotaciones = $ces->listarCatalogosHijos($conexion, 'FINALIDAD', $idEspecie, 'ESPECIES');	
		
		echo '		<div data-linea="33">
						<label>Finalidad:</label>
							<select id="tipoExplotacion" name="tipoExplotacion" required="required">
								<option value="">Finalidad....</option>';
								 
									while ($tipo = pg_fetch_assoc($tiposExplotaciones)){
										echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nombre'] . '</option>';
									}
								
		echo '					</select> 
					</div>
				
					<div data-linea="34">
						<input type="text" id="nombreTipoExplotacion" name="nombreTipoExplotacion"/>
					</div>';
	break;
	
	case 'buscarCategoria':
	
		$tiposCategorias = $ces->listarCatalogosHijos($conexion, 'CATEGORIA', $idEspecieCategoria, 'ESPECIES');
	
		echo '		<div data-linea="33">
						<label>Categoria:</label>
							<select id="categoriaPoblacion" name="categoriaPoblacion" required="required">
								<option value="">Seleccione....</option>';
			
								while ($tipo = pg_fetch_assoc($tiposCategorias)){
									echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nombre'] . '</option>';
								}
	
		echo '					</select>
					</div>
	
					<input type="hidden" id="nombreCategoriasPoblacion" name="nombreCategoriasPoblacion"/>';
		break;
		
	case 'buscarCategoriaFinal':
	
		$tiposCategorias = $ces->listarCatalogosHijos($conexion, 'CATEGORIA', $idEspecieFinal, 'ESPECIES');
	
		echo '		<div data-linea="33">
						<label>Categoria:</label>
							<select id="categoriaFinal" name="categoriaFinal" required="required">
								<option value="">Seleccione....</option>';
			
								while ($tipo = pg_fetch_assoc($tiposCategorias)){
									echo '<option value="' . $tipo['codigo'] . '">' . $tipo['nombre'] . '</option>';
								}
	
		echo '					</select>
					</div>
	
					<input type="hidden" id="nombreCategoriaFinal" name="nombreCategoriaFinal"/>';
		break;
		
	default:
		echo 'Tipo desconocido';
		
		break;
}

?>
<script type="text/javascript">
	$(document).ready(function(event){		
		distribuirLineas();	
		$("#nombreTipoExplotacion").hide();
	});

	$("#tipoExplotacion").change(function(){
    	
    	if($("#tipoExplotacion option:selected").val() =='0'){
			$("#tipoExplotacion").val('0');
			$("#nombreTipoExplotacion").val('');
			$("#nombreTipoExplotacion").show();
		}else{
			$("#nombreTipoExplotacion").hide();
			$("#nombreTipoExplotacion").val($("#tipoExplotacion option:selected").text());
		}
	});

	$("#categoriaPoblacion").change(function(){
		$("#nombreCategoriasPoblacion").val($("#categoriaPoblacion option:selected").text());
	});

	$("#categoriaFinal").change(function(){
		$("#nombreCategoriaFinal").val($("#categoriaFinal option:selected").text());
	});
</script>