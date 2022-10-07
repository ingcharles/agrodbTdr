<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';


$conexion = new Conexion();
$cr = new ControladorRequisitos();


$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');



switch ($opcion){
	case 'subTipoProducto':
	    $subTipoProducto = $cr->obtenerSubtipoProductoMovilizacion($conexion, $_POST['fTipoProducto']);
		echo '<td><label>Subtipo de producto</label>
				<select id="fSubtipoProducto" name="fSubtipoProducto" style="width: 65%;">
				<option value="" selected="selected" >Seleccione....</option>';
					while ($fila = pg_fetch_assoc($subTipoProducto)){
						echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select><input id="fNombreSubTipoProducto" name="fNombreSubTipoProducto" type="hidden"/><td>';
	break;

	case 'producto':
	    $producto = $cr->obtenerProductoMovilizacion($conexion, $_POST['fSubtipoProducto']);

		echo '<label>Producto</label>
				<select id="fProducto" name="fProducto" style="width: 83%;">
				<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($producto)){
						echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
					}
		echo '</select>';
	break;	
		
	default:
		echo 'Tipo desconocido';
}


?>

<script type="text/javascript">
	
	$("#fSubtipoProducto").change(function(event){
		$("#filtrarListaProductoMovilizacion").attr('data-opcion', 'accionesMovilizacion');
 		$("#filtrarListaProductoMovilizacion").attr('data-destino','tProducto');
 		$("#opcion").val('producto');
 		$("#fNombreSubTipoProducto").val($("#fSubtipoProducto  option:selected").text());
 	 	abrir($("#filtrarListaProductoMovilizacion"),event,false);
	 });

	$("#fProducto").change(function(event){
 		$("#opcion").val('otro');
	 });

	 
</script>
