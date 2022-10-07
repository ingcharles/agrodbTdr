<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';


$conexion = new Conexion();
$cr = new ControladorRequisitos();

$codigoTipoProducto = htmlspecialchars ($_POST['fTipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['fSubtipoProducto'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	case 'subTipoProducto':
		$subTipoProducto = $cr->obtenerSubTipoProductoXrequisitoProductoPais($conexion, $codigoTipoProducto);
			
		echo '<td><label>Subtipo de producto</label>
				<select id="fSubtipoProducto" name="fSubtipoProducto" style="width: 65%;">
				<option value="" selected="selected" >Seleccione....</option>';
					while ($fila = pg_fetch_assoc($subTipoProducto)){
						echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select><input id="fNombreSubTipoProducto" name="fNombreSubTipoProducto" type="hidden"/><td>';
	break;

	case 'producto':
		$producto = $cr->obtenerProductoXrequisitoProductoPais($conexion, $codigoSubTipoProducto);

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
 		$("#flitrarOpcionProducto").attr('data-destino','tProducto');
 		$("#flitrarOpcionProducto").attr('data-opcion', 'combosProducto');
 		$("#opcion").val('producto');
 		 $("#fNombreSubTipoProducto").val($("#fSubtipoProducto  option:selected").text());
 	 	abrir($("#flitrarOpcionProducto"),event,false);
	 });

	$("#fProducto").change(function(event){
 		$("#opcion").val('otro');
	 });

	 
</script>
