<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$codigoTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
$areaProducto = htmlspecialchars ($_POST['areaProducto'],ENT_NOQUOTES,'UTF-8');
$idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
$tipoOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion){
	
	case 'tipoProducto':
	
		$tipoProducto = $cc->listarTipoProductosXarea($conexion, $areaProducto);
			
		echo '<label>Tipo de producto</label>
				<select id="tipoProducto" name="tipoProducto" required>
				<option value="" selected="selected" >Seleccione....</option>';
		while ($fila = pg_fetch_assoc($tipoProducto)){
			echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
		}
		echo '</select>';
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
	
	case 'productoLaboratorio':
		
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);
		
		echo '<label>Producto</label>
				<select id="producto" name="producto[]" required>
				<option value="" selected="selected" >Seleccione....</option>';
		while ($fila = pg_fetch_assoc($producto)){
			echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
		}
		echo '</select>';
	break;
	
	case 'parametro':
		
		$idProducto = htmlspecialchars ($_POST['producto']['0'],ENT_NOQUOTES,'UTF-8');
		
		$parametros = $cc->listarParametrosMetodoRangoPorProducto($conexion, $idProducto);
		
		echo '<label>Seleccione uno o varios Parámetros, métodos y rangos</label>
			
					<div class="seleccionTemporal">
						<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
						<label >Seleccionar todos </label>
					</div>
					<hr>
					<div id="contenedorProducto"><table style="border-collapse: initial;">
					<table style="width: 100%;">
						<tr>
							<th>Parámetro</th>
							<th>Método</th>
							<th>Rango</th>
					  </tr>';
					
		
		while ($fila = pg_fetch_assoc($parametros)){
			echo '<tr>
					<td>
						<input type="checkbox" name="rango[]" class="productoActivar" data-resetear="no" value="'.$fila['id_rango'].'" />
			 			'.$fila['descripcion_parametro'].'
					</td>
					<td>
			 			'.$fila['descripcion_metodo'].'
					</td>
					<td>
			 			'.$fila['descripcion_rango'].'
					</td>
				</tr>';
		}
	
	echo '</table>';
	break;

	case 'producto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);

		echo '<label>Seleccione uno o varios Productos</label>
				
					<div class="seleccionTemporal">
						<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
				    	<label >Seleccionar todos </label>
					</div>
				
				<hr>
			 <div id="contenedorProducto"><table style="border-collapse: initial;">
			<tr>';
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
			
	case 'sitio':
		$sitios = $cr->listarSitios($conexion, $identificadorOperador);
			
		echo '<label>Sitio</label>
				<select id="sitio" name="sitio" required>
				<option value="">Seleccione....</option>';
					while ($fila = pg_fetch_assoc($sitios)){
						echo '<option value="'.$fila['id_sitio'].'">'.$fila['nombre_lugar'].'</option>';
					}
		echo '</select>';
	break;
	
	case 'operaciones':
		$operacionPermitidas = $cr -> listarTipoOperacionPermitidas($conexion, $identificadorOperador, $areaProducto, $idSitio);
		
		echo '<label>Operación</label>
				<select id="tipoOperacion" name="tipoOperacion" required>
				<option value="">Seleccione....</option>'; 
					foreach ($operacionPermitidas as $operaciones){
						echo '<option value="'.$operaciones['idTipoOperacion'].'" data-flujo="'.$operaciones['idFlujo'].'">'.$operaciones['nombre'].'</option>';
					}
		echo '</select>';

	break;
	
	case 'areas':
		$valor = 100;
		$areasTipoOperacion = $cc -> obtenerAreasXtipoOperacion($conexion, $tipoOperacion);
		foreach ($areasTipoOperacion as $areaOperacion){
			echo '<div data-linea='.$valor.'><label>'.(strlen($areaOperacion['nombre'])>40?(substr($areaOperacion['nombre'],0,40).'...'):$areaOperacion['nombre']).'</label>
				<select id='.$areaOperacion['codigo'].' name='.$areaOperacion['codigo'].' class="areas" required>
				<option value="">Seleccione....</option>';
			
			$areasOperadorSitio = $cr->obtenerAreasOperadorPorNombreAreaYsitio($conexion, $idSitio, $areaOperacion['nombre']);
			while ($areaSitio = pg_fetch_assoc($areasOperadorSitio)){
				echo '<option value="'.$areaSitio['id_area'].'">'.$areaSitio['nombre_area'].'</option>';
			}
			$valor++;
			echo '</select></div>';
		}	
	break;
		
	default:
		echo 'Tipo desconocido';
}


?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

	$("#tipoOperacion").change(function(event){

		if($(".areas").length != 0){
			$("#dTipoProducto").html('');
			$("#dSubTipoProducto").html('');
			$("#dProducto").html('');
	 	 }

		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");

 		$("#nuevaSolicitud").attr('data-destino','area');
 		$("#nuevaSolicitud").attr('data-opcion', 'combosOperador');
 		$("#opcion").val('areas');
 		$("#idFlujo").val($('#tipoOperacion option:selected').attr('data-flujo'));

 		if($("#tipoOperacion").val() == ''){
 			$("#tipoOperacion").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");
		}else{
			event.stopImmediatePropagation();
 	 		abrir($("#nuevaSolicitud"),event,false);
		}
	 });

	$(".areas").change(function(event){
 		$("#nuevaSolicitud").removeAttr('data-destino');
 		$("#nuevaSolicitud").attr('data-opcion', 'guardarNuevaOperacion');
	 });
	
	$("#cTemporal").click(function(e){
		if($('#cTemporal').is(':checked')){
			$('.productoActivar').prop('checked', true);
		}else{
			$('.productoActivar').prop('checked', false);
		}
	});
	
	$("#subtipoProducto").change(function(event){
		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
		$("#evaluarSolicitud").attr('data-rutaaplicacion', 'registroOperador');
 		$("#evaluarSolicitud").attr('data-destino','dProducto');
 		$("#evaluarSolicitud").attr('data-opcion', 'combosOperador');
 		$("#opcion").val('productoLaboratorio');
 		if($("#subtipoProducto").val() == ''){
 			$("#subtipoProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");
		}else{
			event.stopImmediatePropagation();
 	 		abrir($("#evaluarSolicitud"),event,false);
 	 		$("#evaluarSolicitud").removeAttr('data-destino');
 	 		$("#evaluarSolicitud").attr('data-rutaaplicacion', 'revisionFormularios');
 	 		$("#evaluarSolicitud").attr('data-opcion', 'evaluarDocumentosSolicitud');
 	 		$("#nombreSubtipoProducto").val($("#subtipoProducto option:selected").text());
		}
	});

	$("#producto").change(function(event){
		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
		$("#evaluarSolicitud").attr('data-rutaaplicacion', 'registroOperador');
 		$("#evaluarSolicitud").attr('data-destino','dParametro');
 		$("#evaluarSolicitud").attr('data-opcion', 'combosOperador');
 		$("#opcion").val('parametro');
 		if($("#producto").val() == ''){
 			$("#producto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un producto.").addClass("alerta");
		}else{
			event.stopImmediatePropagation();
 	 		abrir($("#evaluarSolicitud"),event,false);
 	 		$("#evaluarSolicitud").removeAttr('data-destino');
 	 		$("#evaluarSolicitud").attr('data-rutaaplicacion', 'revisionFormularios');
 	 		$("#evaluarSolicitud").attr('data-opcion', 'evaluarDocumentosSolicitud');
 	 		$("#nombreProducto").val($("#producto option:selected").text());
		}
	});

	 
</script>
