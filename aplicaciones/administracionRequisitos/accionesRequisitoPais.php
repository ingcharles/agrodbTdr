<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$cc = new ControladorCatalogos();

$opcion = htmlspecialchars ($_POST['opcionProductoPais'],ENT_NOQUOTES,'UTF-8');
$tipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$producto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
	
	case 'listaSubTipoProducto':
		echo '<label>Subtipo de producto </label>';
		echo '<select id="subtipoProducto" name="subtipoProducto" required>';
		echo '<option value="">Seleccione...</option>';
		$datosSubTipoProducto=$cc->listarSubTipoProductoXtipoProducto($conexion, $tipoProducto);
		while ($fila = pg_fetch_assoc($datosSubTipoProducto)){
			echo '<option   value="'. $fila['id_subtipo_producto'].'" >'.$fila['nombre'].'</option>';
		}
		echo '</select>';
	break;
	
	case 'listaProducto':
		echo '<label>Producto </label>';
		echo '<select id="producto" name="producto" required >';
		echo '<option value="">Seleccione...</option>';
		$datosProducto=$cc->listarProductoXsubTipoProducto($conexion, $producto);
		while ($fila = pg_fetch_assoc($datosProducto)){
			echo '<option   value="'. $fila['id_producto'].'" >'.$fila['nombre_comun'].'</option>';
		}
		echo '</select>';
	break;


}

?>
<script type="text/javascript">
	$(document).ready(function(event){		
		distribuirLineas();	
		
	});

	$("#subtipoProducto").change(function(event){  	
		if( $("#subtipoProducto").val()!=''){
			$('#nuevoRequisitoComercio').attr('data-destino','resultadoProducto');
			$('#nuevoRequisitoComercio').attr('data-opcion','accionesRequisitoPais');
			$('#opcionProductoPais').val('listaProducto');
			event.stopImmediatePropagation();
			abrir($("#nuevoRequisitoComercio"),event,false);
		}	 		
	 }); 
</script>
	