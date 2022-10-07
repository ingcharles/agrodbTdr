<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$codigoTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
$areaProducto = htmlspecialchars ($_POST['idArea'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	
		
	case 'subTipoProducto':
		
		$subTipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $codigoTipoProducto);
			
		echo '<label>Subtipo de producto</label>
				<select id="subtipoProducto" name="subtipoProducto" required>
				<option value="" selected="selected" >Seleccione....</option>';
					while ($fila = pg_fetch_assoc($subTipoProducto)){
						echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select>';
	break;

	case 'producto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);

		echo '<label>Seleccione uno o varios Productos</label>
				
					<div class="seleccionTemporal">
						<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
				    	<label >Seleccionar todos </label>
					</div>
				
				<hr>
			 <div id="contenedorProducto"><table style="border-collapse: initial;"><tr>';
			$agregarDiv = 0;
			$cantidadLinea = 0;
		while ($fila = pg_fetch_assoc($producto)){
			
			echo '<td><input id="'.$fila['id_producto'].'" type="checkbox" name="producto[]" class="productoActivar" data-resetear="no" value="'.$fila['id_producto'].'" />
			 	<label for="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</label></td>';
			$agregarDiv++;
			
			if(($agregarDiv % 3) == 0){
				echo '</tr><tr>';
				$cantidadLinea++;
			}
			
			if($cantidadLinea == 9){
				echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
			}
		}
		echo '</tr></table></div>';
		
	break;	
		
	default:
		echo 'Tipo desconocido';
}


?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});
	

	$("#subtipoProducto").change(function(event){

		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
		
 		$("#nuevoItemProducto").attr('data-destino','dProducto');
 		$("#nuevoItemProducto").attr('data-opcion', 'combosProducto');
 		$("#opcion").val('producto');
 		
 		if($("#subtipoProducto").val() == ''){
 			$("#subtipoProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");
		}else{	 
			event.stopImmediatePropagation();	 	 	
 	 		abrir($("#nuevoItemProducto"),event,false);
 	 		$("#nuevoItemProducto").removeAttr('data-destino');
	 		$("#nuevoItemProducto").attr('data-opcion', 'guardarNuevoItemProducto');
		}
	 });

	$("#cTemporal").click(function(e){
		if($('#cTemporal').is(':checked')){
			$('.productoActivar').prop('checked', true);
		}else{
			$('.productoActivar').prop('checked', false);
		}
	});
	 
</script>
