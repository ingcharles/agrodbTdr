<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ca = new ControladorAreas();
$cc = new ControladorCatastro();

$nivel = htmlspecialchars ($_POST['nivel'],ENT_NOQUOTES,'UTF-8');

switch ($nivel){
	    case 1:
	    	$categoria1 = htmlspecialchars ($_POST['seleccionCategoria'],ENT_NOQUOTES,'UTF-8');
	    	echo '<div data-linea="32" style="width:100%">
	    	<label id="lCategoria2"></label>
	    	<select id="seleccionCategoria2" name="seleccionCategoria2" style="width:100%">
	    	<option value="" >Seleccione...</option>';
	    	
	    	$areaProceso =$cc->buscarDivisionEstruc($conexion, $categoria1);
	    	while($fila = pg_fetch_assoc($areaProceso)){
	    		$areaSubproceso .= "'".$fila['id_area']."',";
	    		if($fila['id_area'] <> $categoria1)
	    			echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" >' . $fila['nombre'] . '</option>';
	    	}
	    		
	    	echo '</select>
	    	<input type="hidden" id="categoria2" name="categoria2" />
	    	</div>';
		break;
		case 2:
			$categoria2 = htmlspecialchars ($_POST['seleccionCategoria2'],ENT_NOQUOTES,'UTF-8');
			echo '<div data-linea="32" style="width:100%">
			<label id="lCategoria3"></label>
			<select id="seleccionCategoria3" name="seleccionCategoria3" style="width:100%">
			<option value="" >Seleccione...</option>';
			
			$areaProceso =$cc->buscarDivisionEstruc($conexion, $categoria2);
			while($fila = pg_fetch_assoc($areaProceso)){
				$areaSubproceso .= "'".$fila['id_area']."',";
				if($fila['id_area'] <> $categoria2)
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" >' . $fila['nombre'] . '</option>';
					
			}
				
			echo '</select>
			<input type="hidden" id="categoria3" name="categoria3" />
			</div>';
		break;
		case 3:
			$categoria3 = htmlspecialchars ($_POST['seleccionCategoria3'],ENT_NOQUOTES,'UTF-8');
			
			echo '<div data-linea="32" style="width:100%">
			<label id="lCategoria4"></label>
			<select id="seleccionCategoria4" name="seleccionCategoria4" style="width:100%">
			<option value="" >Seleccione...</option>';
			
			$areaProceso =$cc->buscarDivisionEstruc($conexion, $categoria3);
			while($fila = pg_fetch_assoc($areaProceso)){
				$areaSubproceso .= "'".$fila['id_area']."',";
				if($fila['id_area'] <> $categoria3)
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" >' . $fila['nombre'] . '</option>';
			}
				
			echo '		</select>
			<input type="hidden" id="categoria4" name="categoria4" />
			</div>';
		break;
		case 4:
			$categoria4 = htmlspecialchars ($_POST['seleccionCategoria4'],ENT_NOQUOTES,'UTF-8');
			
			echo '<div data-linea="32" style="width:100%">
			<label id="lCategoria5"></label>
			<select id="seleccionCategoria5" name="seleccionCategoria5" style="width:100%">
			<option value="" >Seleccione...</option>';
			
			$areaProceso =$cc->buscarDivisionEstruc($conexion, $categoria4);
			while($fila = pg_fetch_assoc($areaProceso)){
				$areaSubproceso .= "'".$fila['id_area']."',";
				if($fila['id_area'] <> $categoria4)
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" >' . $fila['nombre'] . '</option>';
			}
				
			echo '		</select>
			<input type="hidden" id="categoria5" name="categoria5" />
			</div>';
		break;
		case 5:
			$categoria5 = htmlspecialchars ($_POST['seleccionCategoria5'],ENT_NOQUOTES,'UTF-8');				
			echo '<div data-linea="32" style="width:100%">
			<label id="lCategoria6"></label>
			<select id="seleccionCategoria6" name="seleccionCategoria6" style="width:100%">
			<option value="" >Seleccione...</option>';
				
			$areaProceso =$cc->buscarDivisionEstruc($conexion, $categoria5);
			while($fila = pg_fetch_assoc($areaProceso)){
				$areaSubproceso .= "'".$fila['id_area']."',";
				if($fila['id_area'] <> $categoria5)
					echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" data-clasificacion="' . $fila['clasificacion'] . '" >' . $fila['nombre'] . '</option>';
			}		
			echo '</select>
			<input type="hidden" id="categoria6" name="categoria6" />
			</div>';
			break;			
}

?>
<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$('#seleccionCategoria2').change(function(event){
		$("#categoria2").val($('#seleccionCategoria2 option:selected').text());
		$('#nivel').val(2);		
		$('#comboCategoria3').html('');	
		$('#comboCategoria4').html('');	
		$('#comboCategoria5').html('');
		$('#comboCategoria6').html('');
		$("#administrarResponsable").attr('data-opcion', 'combosEstructuraAreas');
	    $("#administrarResponsable").attr('data-destino', 'comboCategoria3');
	    event.stopImmediatePropagation();
	    abrir($("#administrarResponsable"), event, false);  
    });
	$('#seleccionCategoria3').change(function(event){
		$("#categoria3").val($('#seleccionCategoria3 option:selected').text());		
		$('#nivel').val(3);			
		$('#comboCategoria4').html('');	
		$('#comboCategoria5').html('');
		$('#comboCategoria6').html('');
		$("#administrarResponsable").attr('data-opcion', 'combosEstructuraAreas');
	    $("#administrarResponsable").attr('data-destino', 'comboCategoria4');
	    event.stopImmediatePropagation();
	    abrir($("#administrarResponsable"), event, false);  
    });
	$('#seleccionCategoria4').change(function(event){
		$("#categoria4").val($('#seleccionCategoria4 option:selected').text());
		$('#nivel').val(4);			
		$('#comboCategoria5').html('');
		$('#comboCategoria6').html('');	
		$("#administrarResponsable").attr('data-opcion', 'combosEstructuraAreas');
	    $("#administrarResponsable").attr('data-destino', 'comboCategoria5');
	    event.stopImmediatePropagation();
	    abrir($("#administrarResponsable"), event, false); 
    });
	$('#seleccionCategoria5').change(function(event){
		$("#categoria5").val($('#seleccionCategoria5 option:selected').text());
		$('#nivel').val(5);	
		$('#comboCategoria6').html('');					
		$("#administrarResponsable").attr('data-opcion', 'combosEstructuraAreas');
	    $("#administrarResponsable").attr('data-destino', 'comboCategoria6');
	    event.stopImmediatePropagation();
	    abrir($("#administrarResponsable"), event, false); 
    });
</script>
