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
$tipoProducto = $_POST['cbTipoProducto'];
$subTipoProducto = $_POST['cbSubTipoProducto'];
$catalogo = $_POST['cbCatalogo'];

$cbAreaListar = $_POST['cbAreaListar'];
$cbTipoProductoListar=$_POST['cbTipoProductoListar'];
$cbSubTipoProductoListar=$_POST['cbSubTipoProductoListar'];
$productoListar=$_POST['productoListar'];

$idProducto=$_POST['idProducto'];

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
	
	case'plantilla':
		
		$mensaje = array();
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Ha ocurrido un error!';
		
		$res=$cc->obtenerTipoSubtipoProductoOperacionMasivo($conexion, $idProducto);
		$fila=pg_fetch_assoc($res);
		
		switch ($fila['id_area']){
			case'SV':
				$area="Sanidad Vegetal";
				break;
				
			case'SA':
				$area="Sanidad Animal";
				break;
				
			case'LT':
				$area="Laboratorios";
				break;
				
			case'AI':
				$area="Inocuidad de los Alimentos";
				break;
		}
	
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
		$res=$cc->listarProductoXsubTipoProducto($conexion, $cbSubTipoProductoListar);
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
	if($("#cbTipoProducto").val()!=''){	
    	$('#frmPlantilla').attr('data-opcion','comboPlantilla');
    	$('#frmPlantilla').attr('data-destino','resultadoSubTipoProducto');
    	$('#opcion').val('subTipoProducto');
    	abrir($("#frmPlantilla"),event,false);	
	}
});

$("#cbSubTipoProducto").change(function(event){
	event.preventDefault();	
	if($("#cbSubTipoProducto").val()!=''){
    	$('#frmPlantilla').attr('data-opcion','comboPlantilla');
    	$('#frmPlantilla').attr('data-destino','resultadoProducto');
    	$('#opcion').val('producto');
    	abrir($("#frmPlantilla"),event,false);	
	}
});

$("#cbTipoProductoListar").change(function(event){
	event.preventDefault();	
	$('#filtrarPlantilla').attr('data-opcion','comboPlantilla');
	$('#filtrarPlantilla').attr('data-destino','resultadoSubTipoProductoListar');
	$('#opcionCombo').val('subTipoProductoListar');
	abrir($("#filtrarPlantilla"),event,false);	
});

$("#cbSubTipoProductoListar").change(function(event){
	event.preventDefault();	
	$('#filtrarPlantilla').attr('data-opcion','comboPlantilla');
	$('#filtrarPlantilla').attr('data-destino','resultadoProductoListar');
	$('#opcionCombo').val('productoListar');
	abrir($("#filtrarPlantilla"),event,false);	
});

$("#cbProducto").change(function(event){
	$("#cbPlantilla").attr("disabled",false);
	$("#cbTamanio").attr("disabled",false);
	$("#cbOrientacion").attr("disabled",false);
	$("#cbEtiquetaPorHoja").attr("disabled",false);
	$("#txtNombreImpresion").attr("disabled",false);
	$("#btnPrevizualizar").attr("disabled",false);
	$("#btnGuardar").attr("disabled",false);

	var data ="producto="+$("#cbProducto").val();		
    $.ajax({
        type: "POST",
        data: data,        
        url: "aplicaciones/administracionEtiquetas/comprobarEtiquetaAsignada.php",
        dataType: "json",
        success: function(msg) {
        	if(msg.estado=="exito"){
        		$(msg.mensaje).each(function(i){
	        		$("#cbPlantilla").html(this.contenido);		
	        		$("#resultadoPlantilla").html(this.plantilla);
	        		distribuirLineas();
        		});	
        	} else{
        		$(msg.mensaje).each(function(i){
	        		$("#cbPlantilla").html(this.contenido);
	        		$("#resultadoPlantilla").html('');		
	        		distribuirLineas();
        		});	
        	}
            	                       
        },
        error: function(msg){            
        	$("#estado").html(msg).addClass("alerta");          
        }
    });	
});


</script>