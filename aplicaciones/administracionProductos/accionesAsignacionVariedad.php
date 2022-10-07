<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {

	case 'tipoProducto':

		$tipo= htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
		$subTipoProducto = $cc-> listaTipoProducto($conexion,'subtipoProducto', $tipo);
		
		echo '<label>Subtipo producto:</label>
				<select id="subtipoProducto" name="subtipoProducto">
				<option value="0" >Seleccionar...</option>';
			
		while ($fila = pg_fetch_assoc($subTipoProducto)){
			echo  '<option  value="'. $fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
		}
			
		echo '</select>';

		break;
		
		case 'producto':
		
			$subtipo= htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
			$producto = $cc-> listaTipoProducto($conexion, 'producto', null, $subtipo);
		
			echo '<label>Producto:</label>
				<select id="producto" name="producto">
				<option value="0" >Seleccionar...</option>';
		
			while ($fila = pg_fetch_assoc($producto)){
				echo  '<option  value="'. $fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
			}
			echo '</select>';
				
			break;
		
		
		case 'operaciones':
		
		
			$operacionesPermitidas = $cc->listarOperaciones($conexion,'SV');
				
			echo '<label>Operación:</label>
				<select id="tipoOperacion" name="tipoOperacion">
				<option value="0" >Seleccionar...</option>';
				
			while ($fila = pg_fetch_assoc($operacionesPermitidas)){
				echo  '<option  value="'. $fila['id_tipo_operacion'].'">'.$fila['nombre'].'</option>';
			}
				
			echo '</select>';
		
			break;
}
?>

<script type="text/javascript">

$(document).ready(function(){		
		distribuirLineas(); 
	});

$("#subtipoProducto").change(function(event){
	$('#nuevoAsignacionVariedad').attr('data-opcion','accionesAsignacionVariedad');
	$('#nuevoAsignacionVariedad').attr('data-destino','resultadosubtipoProducto');
	$('#opcion').val('producto');	
	abrir($("#nuevoAsignacionVariedad"),event,false);	
 });

$("#producto").change(function(event){
	$('#nuevoAsignacionVariedad').attr('data-opcion','accionesAsignacionVariedad');
	$('#nuevoAsignacionVariedad').attr('data-destino','resultadoProducto');
	$('#opcion').val('operaciones');
	//$('#idProducto').val($("#producto option:selected").val());	
	abrir($("#nuevoAsignacionVariedad"),event,false);	
	

});

$("#tipoOperacion").change(function(event){
	$('#nuevoAsignacionVariedad').attr('data-opcion','accionesAsignacionVariedad');
	$('#nuevoAsignacionVariedad').attr('data-destino','resultadoOperacion');
	$('#opcion').val('areas');
	$('#elegirNumeroVariedades').show();
	$('#btnGuardar').show();
	abrir($("#nuevoAsignacionVariedad"),event,false);	
	
});



</script>