<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cat = new ControladorAdministrarCatalogos();

$producto = htmlspecialchars ($_POST['productos'],ENT_NOQUOTES,'UTF-8');
$opcion = $_POST['opcion'];
$area = $_POST['cbArea'];
$tipoProducto = $_POST['cbTipoProducto'];
$subTipoProducto = $_POST['cbSubTipoProducto'];
$catalogo = $_POST['cbCatalogo'];
//$serie= $_POST['serie'];

switch ($opcion){
	case 'tipoProducto':
		$res=$cc->listarTipoProductosXarea($conexion, $area);
		echo'<label for="cbTipoProducto">Tipo Producto:</label>
			<select id="cbTipoProducto" name="cbTipoProducto">
			<option value="">Seleccione....</option>';
		while($fila=pg_fetch_assoc($res)){
			echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
			}
		
		echo'</select>';
	break;
	
	case 'subTipoProducto':
		$res=$cc->listarSubTipoProductoXtipoProducto($conexion, $tipoProducto);
		echo'<label for="cbSubTipoProducto">Subtipo Producto:</label>
			<select id="cbSubTipoProducto" name="cbSubTipoProducto">
			<option value="">Seleccione....</option>';
		while($fila=pg_fetch_assoc($res)){
			echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
		}
		
		echo'</select>';
	break;
	
	case 'producto':
		$res=$cc->listarProductoXsubTipoProducto($conexion, $subTipoProducto);
		echo'<label for="cbProducto">Producto:</label>
			<select id="cbProducto" name="cbProducto">
			<option value="">Seleccione....</option>';
		while($fila=pg_fetch_assoc($res)){
			echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
		}
		
		echo'</select>';
	break;
	
	case 'items':
		$con=0;
		$res=$cat->listarItems($conexion, $catalogo);
		while($fila=pg_fetch_assoc($res)){
			$con+=1;
			echo'<tr>
				<td>'.$con.'</td><td>'.'<input type="hidden" id="dtxtItem" name="dtxtItem[]" value="'.$fila['id_item'].'">'.$fila['nombre'].'</td>
				</tr>';
		}
		
	break;
}

?>


<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();
	
});

$("#cbTipoProducto").change(function(event){
	event.preventDefault();	
	$('#nuevaCaracteristica').attr('data-opcion','comboCaracteristicas');
	$('#nuevaCaracteristica').attr('data-destino','resultadoSubTipoProducto');
	$('#opcion').val('subTipoProducto');
	abrir($("#nuevaCaracteristica"),event,false);	
});

$("#cbSubTipoProducto").change(function(event){
	event.preventDefault();	
	$('#nuevaCaracteristica').attr('data-opcion','comboCaracteristicas');
	$('#nuevaCaracteristica').attr('data-destino','resultadoProducto');
	$('#opcion').val('producto');
	abrir($("#nuevaCaracteristica"),event,false);	
});

</script>