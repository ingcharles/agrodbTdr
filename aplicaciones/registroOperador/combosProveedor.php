<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$idProveedor = htmlspecialchars ($_POST['idProveedor'],ENT_NOQUOTES,'UTF-8');
$codigoTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	
	case 'verificarProveedor':
		
		$proveedor = $cr->buscarOperador($conexion, $idProveedor);
		
		if(pg_num_rows($proveedor)== 0){
			echo '<script type="text/javascript">$("#estado").html("El proveedor no se encuentra registrado en Agrocalidad.").addClass("alerta");</script>';
		}else{
			echo '<script type="text/javascript">$("#estado").html("");</script>';
		}
		
		
		
	break;
	
	case 'subTipoProducto':
		$subTipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $codigoTipoProducto);
			
		echo '<label>Subtipo de producto</label>
				<select id="subtipoProducto" name="subtipoProducto" required>
				<option value="" selected="selected" >Seleccione....</option>';
					while ($fila = pg_fetch_assoc($subTipoProducto)){
						echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select>';
	break;

	case 'producto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);

		echo '<label>Producto</label>
				<select id="producto" name="producto" required>
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

	$(document).ready(function(){
		distribuirLineas();	
	});
	

	$("#subtipoProducto").change(function(event){
 		$("#datosProveedor").attr('data-destino','dProducto');
 		$("#datosProveedor").attr('data-opcion', 'combosProveedor');
 		$("#opcion").val('producto');
 	 	abrir($("#datosProveedor"),event,false);
	 });

	$("#producto").change(function(event){ 	 	
 	 	$('#nombreProducto').val($("#producto option:selected").text());
	 });

	 
</script>
