<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cre= new ControladorNotificacionEnfermedades();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];


$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$areaProducto = htmlspecialchars ($_POST['areaProducto'],ENT_NOQUOTES,'UTF-8');
$idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {

	case 'tipoProducto':

		$tipo= htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
		$subTipoProducto = $cre-> listaTipoProducto($conexion,'subtipoProducto', $identificador, $tipo, '');

		echo '<label>Subtipo producto:</label>
				<select id="subtipoProducto" name="subtipoProducto">
				<option value="0" >Seleccionar...</option>';
			
		while ($fila = pg_fetch_assoc($subTipoProducto)){
			echo  '<option  value="'. $fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
		}
			
		echo '</select>';

		break;


	case 'producto':

		$subtipo= htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
		$producto = $cre-> listaTipoProducto($conexion,'producto', $_SESSION['usuario'],'', $subtipo);

		echo '<label>Producto:</label>
				<select id="producto" name="producto">
				<option value="0" >Seleccionar...</option>';

		while ($fila = pg_fetch_assoc($producto)){
			echo  '<option  value="'. $fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
		}
		echo '</select>';
			
		break;


	case 'operaciones':


		$operacionesPermitidas = $cre->OperacionesPermitidas($conexion,$_SESSION['usuario'], $idProducto);
			
		echo '<label>Operación:</label>
				<select id="tipoOperacion" name="tipoOperacion">
				<option value="0" >Seleccionar...</option>';
			
		while ($fila = pg_fetch_assoc($operacionesPermitidas)){
			echo  '<option  data-tipo-operacion="'.$fila['codigo'].'" value="'. $fila['id_tipo_operacion'].'">'.$fila['nombre'].'</option>';
		}
			
		echo '</select>';

		break;
			
	case 'areas':
			
		$areasTipoOperacion = $cc -> obtenerAreasXtipoOperacion($conexion, $_POST['tipoOperacion']);

		foreach ($areasTipoOperacion as $areaOperacion){
			echo '<label>'.(strlen($areaOperacion['nombre'])>40?(substr($areaOperacion['nombre'],0,40).'...'):$areaOperacion['nombre']).':</label>
					<select class="areaOperaciones" id='.$areaOperacion['codigo'].' name='.$areaOperacion['codigo'].' required>
					<option value="0">Seleccione....</option>';
				
			$areasOperadorSitio = $cre->obtenerAreaSitioXIdentificadorProductoTipoArea($conexion, $identificador, $idProducto, $areaOperacion['codigo']);
			while ($areaSitio = pg_fetch_assoc($areasOperadorSitio)){
				echo '<option data-razon-social="'.$areaSitio['razon_social'].'" value="'.$areaSitio['id_area'].'"> '.$areaSitio['nombre_area'].' del sitio '.$areaSitio['nombre_lugar'].'</option>';
			}

			echo '</select></div>';
		}
		break;


	case 'tipoenfermedades':

		$tiposEnfermedades = $cre-> obtenerTipoEnfermedad($conexion, $idProducto);

		echo '<label>Diagnóstico:</label>
				<select id="tipoEnfermedad" name="tipoEnfermedad">
				<option value="0" >Seleccionar...</option>';

		while ($fila = pg_fetch_assoc($tiposEnfermedades)){
			echo '<option value="'.$fila['id_tipo_enfermedad'].'">'.$fila['nombre_tipo_enfermedad'].'</option>';
		}

		echo '</select>';

		break;

			
	case 'enfermedades':
			
		$enfermedad = $cre-> obtenerEnfermedad($conexion, $_POST['tipoEnfermedad']);
			
		echo '<label>Agente causal:</label>
				<select id="enfermedad" name="enfermedad">
				<option value="0" >Seleccionar...</option>';

		while ($fila = pg_fetch_assoc($enfermedad)){
			echo '<option value="'.$fila['id_enfermedad'].'">'.$fila['nombre_enfermedad'].'</option>';
		}

		echo '</select>';

		break;


}


?>

<script type="text/javascript"> 

	$(document).ready(function(){		
		distribuirLineas(); 

	});

	
	$("#subtipoProducto").change(function(event){
		$('#nuevoNotificacionEnfermedades').attr('data-opcion','accionesNotificacionEnfermedades');
    	$('#nuevoNotificacionEnfermedades').attr('data-destino','resultadosubtipoProducto');
    	$('#opcion').val('producto');	
    	abrir($("#nuevoNotificacionEnfermedades"),event,false);	
	 });


	$("#producto").change(function(event){
		$('#nuevoNotificacionEnfermedades').attr('data-opcion','accionesNotificacionEnfermedades');
		$('#nuevoNotificacionEnfermedades').attr('data-destino','resultadoProducto');
		$('#opcion').val('operaciones');
		$('#idProducto').val($("#producto option:selected").val());	
		$('#nombreProducto').val($("#producto option:selected").text());	
		abrir($("#nuevoNotificacionEnfermedades"),event,false);	
		

	});


	$("#tipoOperacion").change(function(event){
		$('#nuevoNotificacionEnfermedades').attr('data-opcion','accionesNotificacionEnfermedades');
		$('#nuevoNotificacionEnfermedades').attr('data-destino','resultadoOperacion');
		$('#opcion').val('areas');
		$('#tipoOperac').val($("#tipoOperacion option:selected").attr("data-tipo-operacion"));
		abrir($("#nuevoNotificacionEnfermedades"),event,false);	
		
	});
	

	$(".areaOperaciones").change(function(event){
		if($("#tipoOperac").val()=="MVB" || $("#tipoOperac").val()=="MVC" || $("#tipoOperac").val()=="MVE")
		{			
		$('#registrarDiagnostico').show();//------muestra nuevo fieldset
		$('#opcion').val('tipoenfermedades');
		$('#nuevoNotificacionEnfermedades').attr('data-opcion','accionesNotificacionEnfermedades');
		$('#nuevoNotificacionEnfermedades').attr('data-destino','resultadoTipoEnfermedad');
		abrir($("#nuevoNotificacionEnfermedades"),event,false);		
		//la razón social
		$('#razonSocial').val($(".areaOperaciones option:selected").attr("data-razon-social"));
		}
		else
		{
			$("#registrarDiagnostico").hide();
			$("#archivoAdjunto").hide();
			$('#observacion').hide();
			}
	});

	
	$("#tipoEnfermedad").change(function(event){
		$('#nuevoNotificacionEnfermedades').attr('data-opcion','accionesNotificacionEnfermedades');
		$('#nuevoNotificacionEnfermedades').attr('data-destino','resultadoEnfermedad');
		$('#opcion').val('enfermedades');
		abrir($("#nuevoNotificacionEnfermedades"),event,false);		
	});

</script>
