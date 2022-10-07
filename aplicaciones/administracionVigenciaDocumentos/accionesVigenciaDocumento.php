<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
//require_once '../../clases/ControladorVigenciaDocumentos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
//$cvd = new ControladorVigenciaDocumentos();


$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$idAreaTematica = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
$idTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$idSubtipoProducto = htmlspecialchars ($_POST['subTipoProducto'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
	
	case 'area':
		echo '<label for="tipoOperacion">*Operación: </label>';
		echo '<select id="tipoOperacion" name="tipoOperacion" class="itemsRadio">';
		echo '<option value="">Seleccione...</option>';
		$qTipoOperacion = $cc -> obtenerTiposOperacionPorIdAreaTematica($conexion, $idAreaTematica);
		while ($tipoOperacion = pg_fetch_assoc($qTipoOperacion)){
			echo '<option   value="'. $tipoOperacion['id_tipo_operacion'].'" >'.$tipoOperacion['nombre'].'</option>';
		}
		echo '</select>';
	break;	
	
	case 'tipoOperacion':
		echo '<label for="tipoProducto">Tipo Producto: </label>';
		echo '<select id="tipoProducto" name="tipoProducto">';
		echo '<option value="">Seleccione...</option>';
		$qTipoProducto = $cc -> listarTipoProductosXarea($conexion, $idAreaTematica);
		while ($tipoProducto = pg_fetch_assoc($qTipoProducto)){
			echo '<option   value="'. $tipoProducto['id_tipo_producto'].'" >'.$tipoProducto['nombre'].'</option>';
		}
		echo '</select>';
	break;
	
	case 'tipoProducto':
		echo '<label for="subTipoProducto">Subtipo Producto: </label>';
		echo '<select id="subTipoProducto" name="subTipoProducto">';
		echo '<option value="">Seleccione...</option>';
		$qSubtipoProducto = $cc -> listarSubTipoProductoXtipoProducto($conexion, $idTipoProducto);
		while ($subtipoProducto = pg_fetch_assoc($qSubtipoProducto)){
			echo '<option   value="'. $subtipoProducto['id_subtipo_producto'].'" >'.$subtipoProducto['nombre'].'</option>';
		}
		echo '</select>';
	break;
		
	case 'subtipoProducto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $idSubtipoProducto);

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

}

?>

<script type="text/javascript">

	$(document).ready(function(event){		
		distribuirLineas();

		//$('#tipoProducto').append('<option value="noAplica">No aplica</option>');
	});
	
	$('#tipoOperacion').change(function(event){
		$("#resultadoTipoOperacion").show();
		$("#productosAgregados").hide();	
		$("#resultadoSubtipoProducto").hide();
		$("#subtipoProducto").val("");
	
		$("#nuevoVigenciaDocumento").attr('data-opcion','accionesVigenciaDocumento');
		$("#nuevoVigenciaDocumento").attr('data-destino','resultadoTipoOperacion');
		$('#opcion').val('tipoOperacion');		
		event.stopImmediatePropagation();		
		abrir($("#nuevoVigenciaDocumento"),event,false);
	});

	$('#tipoProducto').change(function(event){
		$("#productosAgregados").hide();	
		$("#resultadoSubtipoProducto").hide();	
		if($("#tipoProducto").val() != ''){					
			$("#nuevoVigenciaDocumento").attr('data-opcion','accionesVigenciaDocumento');
			$("#nuevoVigenciaDocumento").attr('data-destino','resultadoTipoProducto');
			$('#opcion').val('tipoProducto');		
			event.stopImmediatePropagation();
			abrir($("#nuevoVigenciaDocumento"),event,false);
		}else{
			$("#productosAgregados").hide();	
			$("#resultadoSubtipoProducto").hide();				
		}
	});

	$('#subTipoProducto').change(function(event){

		if($("#subTipoProducto").val() != ''){		
			$("#agregar").show();
			$("#productosAgregados").show();	
			$("#resultadoSubtipoProducto").show();			
			$("#nuevoVigenciaDocumento").attr('data-opcion','accionesVigenciaDocumento');
			$("#nuevoVigenciaDocumento").attr('data-destino','resultadoSubtipoProducto');
			$('#opcion').val('subtipoProducto');
			event.stopImmediatePropagation();
			abrir($("#nuevoVigenciaDocumento"),event,false);
		}else{
			$("#productosAgregados").hide();	
			$("#resultadoSubtipoProducto").hide();	
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