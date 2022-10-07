<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$csit = new ControladorServiciosInformacionTecnica();
$cc = new ControladorCatalogos();

$codigoTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');

$tipoOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$opcionL= htmlspecialchars ($_POST['opcionL'],ENT_NOQUOTES,'UTF-8');
$opcionR= htmlspecialchars ($_POST['opcionR'],ENT_NOQUOTES,'UTF-8');
$opcionP= htmlspecialchars ($_POST['opcionP'],ENT_NOQUOTES,'UTF-8');

if($opcion=="" && $opcionR=="" && $opcionP==""){
$opcion=$opcionL;
}elseif($opcion=="" && $opcionL=="" && $opcionP==""){
	$opcion=$opcionR;	
}elseif($opcion=="" && $opcionL=="" && $opcionR==""){
	$opcion=$opcionP;
}

switch ($opcion){
	case 'listaPaisesZonas':
		$idZona = htmlspecialchars ($_POST['zonaH'],ENT_NOQUOTES,'UTF-8');
		$qZona=$csit->listaPaisesZonas($conexion, $idZona);
		echo '<select id="paisH" name="paisH" style="width:100%">
				<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qZona)){
			echo '<option value="'.$fila['id_pais'].'">'.$fila['nombre'].'</option>';
		}
		echo '</select>';
	break;
		
	case 'listaPaises':
		$idZona = htmlspecialchars ($_POST['zona'],ENT_NOQUOTES,'UTF-8');
		$qZona=$csit->listaPaisesZonas($conexion, $idZona);
		echo '<label>País: </label><select id="pais" name="pais" style="width:100%">
				<option value="">Seleccione...</option>';
		while ($fila = pg_fetch_assoc($qZona)){
			echo '<option value="'.$fila['id_pais'].'">'.$fila['nombre'].'</option>';
		}
		echo '</select>';
	break;
	
	case 'subTipoProducto':	
		$subTipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $codigoTipoProducto);
		echo '<label>Subtipo de producto: </label>
				<select id="subtipoProducto" name="subtipoProducto" >
				<option value="" selected="selected" >Seleccione....</option>';
				while ($fila = pg_fetch_assoc($subTipoProducto)){
					echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
				}
		echo '</select>';
	break;

	case 'producto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);
		echo '<label>Seleccione uno o varios Productos: </label>
				<div class="seleccionTemporal">
					<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
					<label >Seleccionar todos </label>
				</div>
				<hr>
				<div id="contenedorProducto"><table style="border-collapse: initial;"><tr>';
				$agregarDiv = 0;
				$cantidadLinea = 0;
				while ($fila = pg_fetch_assoc($producto)){
				echo '<td><input id="'.$fila['id_producto'].'" type="checkbox" name="producto[]" class="productoActivar" data-resetear="no" value="'.$fila['id_producto'].'" />
				 	<label for="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</label></td>';
					$agregarDiv++;
					if(($agregarDiv % 3) == 0){
						echo '</tr><tr>';
						$cantidadLinea++;
					}
					if($cantidadLinea == 9){
						echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
					}
				}
		echo '</tr></table></div>';
	break;	

	case 'enfermedadProducto':
		$producto= $csit->buscarProductoEnfermedad($conexion,$_POST['idEnfermedadC'], $_POST['subtipoProducto']);
		echo '<label>Seleccione uno o varios Productos: </label>
				<div class="seleccionTemporal">
					<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
				   	<label >Seleccionar todos </label>
				</div>
				<hr>
			 <div id="contenedorProducto"><table style="border-collapse: initial;"><tr>';
			$agregarDiv = 0;
			$cantidadLinea = 0;
			while ($fila = pg_fetch_assoc($producto)){
				echo '<td><input id="'.$fila['id_producto'].'" type="checkbox" name="producto[]" class="productoActivar" data-resetear="no" value="'.$fila['id_producto'].'" />
			 	<label for="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</label></td>';
				$agregarDiv++;
				if(($agregarDiv % 3) == 0){
					echo '</tr><tr>';
					$cantidadLinea++;
				}
				if($cantidadLinea == 9){
					echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
				}
			}
		echo '</tr></table></div>';
	break;
		
	case 'listaElementos':
		$tipoRequerimiento = htmlspecialchars ($_POST['tipoRequerimiento'],ENT_NOQUOTES,'UTF-8');
		$qElemento=$csit->listarRequerimientoElemento($conexion, $tipoRequerimiento);
		echo '<label>Requerimiento: </label><select id="elementoRevision" name="elementoRevision" style="width:100%">
				<option value="">Seleccione...</option>';
			while ($fila = pg_fetch_assoc($qElemento)){
				echo '<option value="'.$fila['id_requerimiento_elemento'].'">'.$fila['nombre'].'</option>';
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
		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
 		$("#nuevoRegistro").attr('data-destino','dProducto');
 		$("#nuevoRegistro").attr('data-opcion', 'combosServicios');
 		$("#opcion").val('producto'); 		
 		if($("#subtipoProducto").val() == ''){
 			$("#subtipoProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un subtipo de producto.").addClass("alerta");
		}else{	 
			event.stopImmediatePropagation();	 	 	
 	 		abrir($("#nuevoRegistro"),event,false);
 	 		$("#nuevoRegistro").removeAttr('data-destino');
	 		$("#nuevoRegistro").attr('data-opcion', 'guardarEnfermedadProductoSAA');
		}
	 });

	$("#pais").change(function(event){
		if($("#pais").val()!=0){
			$('#nombrePais').val($('#pais option:selected').text());
		}
	 });

	$("#elementoRevision").change(function(event){
		if($("#elementoRevision").val()!=0){
			$('#nombreElementoRevision').val($('#elementoRevision option:selected').text());
		}
	 });
	 
	$("#cTemporal").click(function(e){
		if($('#cTemporal').is(':checked')){
			$('.productoActivar').prop('checked', true);
		}else{
			$('.productoActivar').prop('checked', false);
		}
	});
</script>