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
$opcionCombo = $_POST['opcionCombo'];
$area = $_POST['cbArea'];
$cbAreaListar= $_POST['cbAreaListar'];
$tipoProducto = $_POST['cbTipoProducto'];
$subTipoProducto = $_POST['cbSubTipoProducto'];
$catalogo = $_POST['cbCatalogo'];


$cbTipoProductoListar=$_POST['cbTipoProductoListar'];
$subTipoProductoListar=$_POST['cbSubTipoProductoListar'];
$productoListar=$_POST['productoListar'];
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
		$res=$cat->listarItems($conexion, $catalogo,1);
		while($fila=pg_fetch_assoc($res)){
			$con+=1;
			echo'<tr>
				<td>'.$con.'</td><td>'.'<input type="hidden" id="dtxtItem" name="dtxtItem[]" value="'.$fila['id_item'].'">'.$fila['nombre'].'</td>
				</tr>';
		}
		
	break;
}
switch ($opcionCombo){
	
	case 'tipoProductoListar':
		$res=$cc->listarTipoProductosXarea($conexion, $cbAreaListar);
		echo'<td>Tipo Producto:</td>
			<td><select id="cbTipoProductoListar" name="cbTipoProductoListar" style="width: 200px;">
			<option value="">Seleccione....</option>';
		while($fila=pg_fetch_assoc($res)){
			echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
		}
		
		echo'</select></td>';
	break;
	
	case 'subTipoProductoListar':
		$res=$cc->listarSubTipoProductoXtipoProducto($conexion, $cbTipoProductoListar);
		echo'<td>Subtipo Producto:</td>
			<td><select id="cbSubTipoProductoListar" name="cbSubTipoProductoListar" style="width: 200px;">
			<option value="">Seleccione....</option>';
		while($fila=pg_fetch_assoc($res)){
			echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
		}
		
		echo'</select></td>';
	break;
	
	case 'productoListar':
		$res=$cc->listarProductoXsubTipoProducto($conexion, $subTipoProductoListar);
		echo'<td>Producto:</td>
			<td><select id="cbProductoListar" name="cbProductoListar" style="width: 200px;">
			<option value="">Seleccione....</option>';
		while($fila=pg_fetch_assoc($res)){
			echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
		}
		
		echo'</select><td>';
	break;
}

?>


<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();
	
});

$("#cbTipoProducto").change(function(event){
	event.preventDefault();	
	event.stopImmediatePropagation();
	if($("#cbTipoProducto").val()!=""){
    	$('#nuevaCaracteristica').attr('data-opcion','comboCaracteristicas');
    	$('#nuevaCaracteristica').attr('data-destino','resultadoSubTipoProducto');
    	$('#opcion').val('subTipoProducto');
    	abrir($("#nuevaCaracteristica"),event,false);
    	$("#cbProducto").html('<option value="">Seleccione....</option>');
	} else{
		$("#cbSubTipoProducto").html('<option value="">Seleccione....</option>');
		$("#cbProducto").html('<option value="">Seleccione....</option>');
	}	
});

$("#cbSubTipoProducto").change(function(event){
	event.preventDefault();
	event.stopImmediatePropagation();
	if($.trim($("#cbSubTipoProducto").val())!=""){
    	$('#nuevaCaracteristica').attr('data-opcion','comboCaracteristicas');
    	$('#nuevaCaracteristica').attr('data-destino','resultadoProducto');
    	$('#opcion').val('producto');
    	abrir($("#nuevaCaracteristica"),event,false);
	} else{
		$("#cbProducto").html('<option value="">Seleccione....</option>');
	}
});

$("#cbProducto").change(function(event){	
	if($("#cbProducto").val()!="" ){		
		$("#btnAgregarItem").attr("disabled",false);
	} else{
		$("#btnAgregarItem").attr("disabled",true);
	}
});


$("#cbTipoProductoListar").change(function(event){
	event.preventDefault();	
	event.stopImmediatePropagation();
	if($("#cbTipoProductoListar").val()!=""){
    	$('#filtrarCaracteristicas').attr('data-opcion','comboCaracteristicas');
    	$('#filtrarCaracteristicas').attr('data-destino','resultadoSubTipoProductoListar');
    	$('#opcionCombo').val('subTipoProductoListar');
    	abrir($("#filtrarCaracteristicas"),event,false);
    	$("#resultadoProductoListar").html("");
	} else{
		$("#resultadoSubTipoProductoListar").html("");
		$("#resultadoProductoListar").html("");
	}
});

$("#cbSubTipoProductoListar").change(function(event){
	event.preventDefault();
	event.stopImmediatePropagation();
	if($("#cbSubTipoProductoListar").val()!=""){
    	$('#filtrarCaracteristicas').attr('data-opcion','comboCaracteristicas');
    	$('#filtrarCaracteristicas').attr('data-destino','resultadoProductoListar');
    	$('#opcionCombo').val('productoListar'); 
    	abrir($("#filtrarCaracteristicas"),event,false);
	} else{
		$("#resultadoProductoListar").html("");
	}
});

</script>