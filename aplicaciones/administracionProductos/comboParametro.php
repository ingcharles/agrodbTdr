<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';
require_once '../../clases/ControladorLotes.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cat = new ControladorAdministrarCatalogos();
$controladorLotes = new ControladorLotes();

$producto = htmlspecialchars ($_POST['productos'],ENT_NOQUOTES,'UTF-8');
$opcion = $_POST['opcion'];
$opcionCombo = $_POST['opcionCombo'];
$area = $_POST['cbArea'];
$tipoProducto = $_POST['cbTipoProducto'];
$subTipoProducto = $_POST['cbSubTipoProducto'];
$catalogo = $_POST['cbCatalogo'];
$opcionCombo = $_POST['opcionCombo'];

$cbAreaListar = $_POST['cbAreaListar'];
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
		$res=$cat->listarItems($conexion, $catalogo);
		while($fila=pg_fetch_assoc($res)){
			$con+=1;
			echo'<tr>
				<td>'.$con.'</td><td>'.'<input type="hidden" id="dtxtItem" name="dtxtItem[]" value="'.$fila['id_item'].'">'.$fila['nombre'].'</td>
				</tr>';
		}
		
	break;
	
	case 'operaciones':
	    
	    $resultado=$controladorLotes->listarOperacionesTrazabilidad($conexion, 'SV', 't');
	    
	    echo '<label>Seleccione uno o varios Tipos de Operaciones</label>
	        
					<div class="seleccionTemporal">
						<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
				    	<label for="cTemporal">Seleccionar todos </label>
					</div>
	        
				<hr>
			 <div id="contenedorProducto"><table style="border-collapse: initial;"><tr>';
        	    $agregarDiv = 0;
        	    $cantidadLinea = 0;
        	    while ($fila = pg_fetch_assoc($resultado)){
        	        
        	        echo '<td style="text-align:left;"><input id="'.$fila['id_tipo_operacion'].'" type="checkbox" class="productoActivar" data-resetear="no" value="'.$fila['id_tipo_operacion'].'" />
        			 	<label for="'.$fila['id_tipo_operacion'].'">'.$fila['nombre'].'</label>
                        <input type="hidden" name="codigoOperacion[]" value="'.$fila['codigo'].'"></td>';
        	        $agregarDiv++;
        	        
        	        if(($agregarDiv % 2) == 0){
        	            echo '</tr><tr>';
        	            $cantidadLinea++;
        	        }
        	        
        	        if($cantidadLinea == 9){
        	            echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
        	        }
    	    }
	    echo '</tr></table></div>
             <button type="button"  onclick="agregarFilas();return false;" class="mas">Agregar Item</button>';
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
    	$('#nuevoParametro').attr('data-opcion','comboParametro');
    	$('#nuevoParametro').attr('data-destino','resultadoSubTipoProducto');
    	$('#opcion').val('subTipoProducto');
    	abrir($("#nuevoParametro"),event,false);	
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
    	$('#nuevoParametro').attr('data-opcion','comboParametro');
    	$('#nuevoParametro').attr('data-destino','resultadoProducto');
    	$('#opcion').val('producto');
    	abrir($("#nuevoParametro"),event,false);	
	} else{
		$("#cbProducto").html('<option value="">Seleccione....</option>');
	}
});

$("#cbProducto").change(function(event){
	if($("#cbProducto").val()!=""){
		$("#btnGuardar").attr("disabled",false);
	} else{
		$("#btnGuardar").attr("disabled",true);
	}
});

$("#cbTipoProductoListar").change(function(event){
	event.preventDefault();	
	event.stopImmediatePropagation();
	if($("#cbTipoProductoListar").val()!=""){
    	$('#filtrarParametros').attr('data-opcion','comboParametro');
    	$('#filtrarParametros').attr('data-destino','resultadoSubTipoProductoListar');
    	$('#opcionCombo').val('subTipoProductoListar');
    	abrir($("#filtrarParametros"),event,false);	
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
	$('#filtrarParametros').attr('data-opcion','comboParametro');
	$('#filtrarParametros').attr('data-destino','resultadoProductoListar');
	$('#opcionCombo').val('productoListar');
	abrir($("#filtrarParametros"),event,false);	
	} else{
		$("#resultadoProductoListar").html("");
	}
});

$("#agregarItem").click(function(event){
	event.preventDefault();	
	$("#frmItem").attr("data-opcion","asociarItemsCatalogo");	
	ejecutarJson($("#frmItem"));
	//return false;
});


</script>