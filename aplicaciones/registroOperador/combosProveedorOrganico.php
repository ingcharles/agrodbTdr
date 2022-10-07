<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificadorProveedor = htmlspecialchars ($_POST['identificadorProveedor'],ENT_NOQUOTES,'UTF-8');
$codigoTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	
    case 'esImportador':
        echo '<label>Nombre Proveedor: </label>
        <input type="text" id="nombreProveedor" name="nombreProveedor" placeholder="Ej: José Antonio Pérez García"/>';
    break;
    
    case 'noImportador':
        echo '<label>CI/RUC Proveedor: </label>
        <input type="text" id="identificadorProveedor" name="identificadorProveedor" placeholder="Ej: 1815161213" data-er="^[0-9]+$"/>';
    break;
    
    case 'verificarProveedor':		
        $tipoProducto = $cro->listarTipoProductosOperacionesOrganicoXIdentificadorProveedor($conexion, $identificadorProveedor);
        
        echo '<label>Tipo de producto: </label>
				<select id="tipoProducto" name="tipoProducto" >
				<option value="" selected="selected" >Seleccione....</option>';
        while ($fila = pg_fetch_assoc($tipoProducto)){
            echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
        }
        echo '</select>';
	break;
	
	case 'tipoProducto':
	    $subTipoProducto = $cro->listarProductosOperacionesOrganicoXIdentificadorProveedorXIdTipoProducto($conexion, $identificadorProveedor, $codigoTipoProducto);
			
		  echo '<label>Subtipo de producto: </label>
				<select id="subtipoProducto" name="subtipoProducto" >
				<option value="" selected="selected" >Seleccione....</option>';
					while ($fila = pg_fetch_assoc($subTipoProducto)){
						echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
					}
		  echo '</select>';
	  break;
	
	case 'subtipoProducto':
	    $producto = $cro->listarProductosOperacionesOrganicoXIdentificadorProveedorXIdSubtipoProducto($conexion, $identificadorProveedor, $codigoSubTipoProducto);

		echo '<label>Producto: </label>
				<select id="producto" name="producto" >
				<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($producto)){
						echo '<option value="'.$fila['id_producto'].'" data-nombreProducto="'.$fila['nombre_producto'].'" data-idTipoTransicion="'.$fila['id_tipo_transicion'].'">'.$fila['nombre_producto'].' - '.$fila['nombre_tipo_transicion'].'</option>';
					}
		echo '</select>';
	break;	
	
	case 'producto':
	    echo '<label>Estatus: </label>
				<select id="idTipoTransicionProducto" name="idTipoTransicionProducto" >
				<option value="">Seleccione....</option>';
	    
	    $qTipoTransicion = $cro->obtenerTipoTransicion($conexion);
	    
	    while ($tipoTransicion = pg_fetch_assoc($qTipoTransicion)){
	        echo '<option value="'.$tipoTransicion['id_tipo_transicion'].'" >'.$tipoTransicion['nombre_tipo_transicion'].'</option>';
	    }
	    echo '</select>';	  
	break;	
	
	case 'transicion':
    	echo '<label>País Origen: </label>
    				<select id="idPaisOrigen" name="idPaisOrigen" >
    				<option value="">Seleccione....</option>';
    	$qPaises = $cc->listarLocalizacion($conexion, 'PAIS');
    	
    	while ($pais = pg_fetch_assoc($qPaises)){
    	    echo '<option value="'.$pais['id_localizacion'].'" >'.$pais['nombre'].'</option>';
    	}
    	echo '</select>';
    break;
	
	case 'listarTipoProducto':
	    $tipoProducto = $cc->listarTipoProductosXarea($conexion, 'AI');	    
	    echo '<label>Tipo de producto: </label>
				<select id="tipoProducto" name="tipoProducto" >
				<option value="" selected="selected" >Seleccione....</option>';
	    while ($fila = pg_fetch_assoc($tipoProducto)){
	        echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
	    }
	    echo '</select>';
	break;
		
	case 'listarSubtipoProducto':
	    $subTipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $codigoTipoProducto);
	    
	    echo '<label>Subtipo de producto: </label>
				<select id="subtipoProducto" name="subtipoProducto" >
				<option value="" selected="selected" >Seleccione....</option>';
	    while ($fila = pg_fetch_assoc($subTipoProducto)){
	        echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
	    }
	    echo '</select>';
	break;
	
	case 'listarProducto':
	    $producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);
	    
	    echo '<label>Producto: </label>
				<select id="producto" name="producto" >
				<option value="">Seleccione....</option>';
	    while ($fila = pg_fetch_assoc($producto)){
	        echo '<option value="'.$fila['id_producto'].'" data-nombreProducto="'.$fila['nombre_comun'].'">'.$fila['nombre_comun'].'</option>';
	    }
	    echo '</select>';
	break;		
	
	default:
		echo 'Tipo desconocido';
}

?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});
		
	$("#identificadorProveedor").change(function(event){
		$("#subtipoProducto").empty();
		$("#subtipoProducto").append('<option selected="selected" value="">Seleccione...</option>');
		$('#producto').empty();
		$('#producto').append('<option selected="selected" value="">Seleccione...</option>');
		$('#idPaisOrigen').empty();
		$('#idPaisOrigen').append('<option selected="selected" value="">Seleccione...</option>');
		event.preventDefault();
		event.stopImmediatePropagation();
 		$("#nuevoProveedorOrganico").attr('data-destino','resultadoIdentificadorProveedor');
 		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
 		$("#opcion").val('verificarProveedor');
 	 	abrir($("#nuevoProveedorOrganico"),event,false);
	});

	$("#nombreProveedor").change(function(event){
		$("#subtipoProducto").empty();
		$("#subtipoProducto").append('<option selected="selected" value="">Seleccione...</option>');
		$('#producto').empty();
		$('#producto').append('<option selected="selected" value="">Seleccione...</option>');
		event.preventDefault();
		event.stopImmediatePropagation();
		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
	    $("#nuevoProveedorOrganico").attr('data-destino', 'resultadoIdentificadorProveedor');
	    $("#opcion").val('listarTipoProducto');
	    abrir($("#nuevoProveedorOrganico"), event, false);
	});

	$("#tipoProducto").change(function(event){
		$('#producto').empty();
		$('#producto').append('<option selected="selected" value="">Seleccione...</option>');
		if($("#tipoProducto").val() != ""){
    		event.preventDefault();
    		event.stopImmediatePropagation();    		
    		if($('#importador').prop('checked')){
    			$("#nuevoProveedorOrganico").attr('data-destino','resultadoTipoProducto');
                $("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
                $("#opcion").val('listarSubtipoProducto');
    	    }else{
    	    	$("#nuevoProveedorOrganico").attr('data-destino','resultadoTipoProducto');
                $("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
                $("#opcion").val('tipoProducto');
    		}		     	
     	 	abrir($("#nuevoProveedorOrganico"),event,false);

		}		
	 });
	
	$("#subtipoProducto").change(function(event){
		if($("#subtipoProducto").val() != ""){
    		event.preventDefault();
    		event.stopImmediatePropagation();
    		if($('#importador').prop('checked')){
    			$("#nuevoProveedorOrganico").attr('data-destino','resultadoSubtipoProducto');
         		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
         		$("#opcion").val('listarProducto');
    		}else{    			
         		$("#nuevoProveedorOrganico").attr('data-destino','resultadoSubtipoProducto');
         		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
         		$("#opcion").val('subtipoProducto');
    		}    		
     	 	abrir($("#nuevoProveedorOrganico"),event,false);
		}		
	 });

	$("#producto").change(function(event){ 
		event.preventDefault();
		event.stopImmediatePropagation();
		if($('#importador').prop('checked')){
			$("#nuevoProveedorOrganico").attr('data-destino','resultadoProducto');
     		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
     		$("#opcion").val('producto');
     		$('#nombreProducto').val($("#producto option:selected").attr('data-nombreProducto'));
     		abrir($("#nuevoProveedorOrganico"),event,false);
		}else{
			$('#nombreProducto').val($("#producto option:selected").attr('data-nombreProducto'));
	 	 	$('#idTipoTransicion').val($("#producto option:selected").attr('data-idTipoTransicion'));
		}			 	 	
 	 	
 	 	$("#nuevoProveedorOrganico").attr('data-opcion', 'agregarNuevoProveedorOrganico');
	   	$("#nuevoProveedorOrganico").attr('data-destino', 'detalleItem');	 	 	
	 });

	$("#idTipoTransicionProducto").change(function(event){ 
		event.preventDefault();
		event.stopImmediatePropagation();
		if($('#importador').prop('checked')){
			$('#idTipoTransicion').val($("#idTipoTransicionProducto option:selected").val());
			$("#nuevoProveedorOrganico").attr('data-destino','resultadoTransicion');
     		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
     		$("#opcion").val('transicion');
     		abrir($("#nuevoProveedorOrganico"),event,false);
		} 

		$("#nuevoProveedorOrganico").attr('data-opcion', 'agregarNuevoProveedorOrganico');
	   	$("#nuevoProveedorOrganico").attr('data-destino', 'detalleItem');
	 });	
	 
</script>
