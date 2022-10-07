<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$codigoTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	
	case 'operador':
		$qOperador = $cr->buscarOperador($conexion, $identificadorOperador);
		
		if(pg_num_rows($qOperador) != 0){
			
			$operador = pg_fetch_assoc($qOperador);
			echo '<label>Razón social / Representante</label> '.($operador['razon_social']==''?$operador['apellido_representante'].' '.$operador['nombre_representante']:$operador['razon_social']);
		}else{
			echo '<label id="lOperador">El operador no se encuentra registrado<label>';
			echo ' <input type="hidden" id="operador" name="operador" value = "0" />';
		}
				
		break;
	
	case 'subTipoProducto':
		$subTipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $codigoTipoProducto);
			
		echo '<label>Subtipo de producto</label>
				<select id="subtipoProducto" name="subtipoProducto">
				<option value="" selected="selected" >Seleccione....</option>';
					while ($fila = pg_fetch_assoc($subTipoProducto)){
						echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
					}
		echo '</select>';
	break;

	case 'producto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);

		echo '<label>Producto</label>
				<select id="producto" name="producto">
				<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($producto)){
						echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
					}
		echo '</select>
			  <input type="hidden" id="nombreProducto" name="nombreProducto"/>';
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
 		$("#nuevoTramite").attr('data-destino','dProducto');
 		$("#nuevoTramite").attr('data-opcion', 'combosTramites');
 		$("#opcion").val('producto');
 	 	abrir($("#nuevoTramite"),event,false);
	 });

	$("#producto").change(function(event){
		$("#nombreProducto").val($("#producto  option:selected").text());
	 });
	 
</script>
