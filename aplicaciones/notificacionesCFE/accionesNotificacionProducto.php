<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cro = new ControladorRegistroOperador();
$cfe = new ControladorFitosanitarioExportacion();


$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$numeroCFE = htmlspecialchars ($_POST['numeroCFE'],ENT_NOQUOTES,'UTF-8');
$certificado = htmlspecialchars ($_POST['idFitodanitarioExportacion'],ENT_NOQUOTES,'UTF-8');
$exportador = htmlspecialchars ($_POST['idExportador'],ENT_NOQUOTES,'UTF-8');
$fitosanitarioExportador = htmlspecialchars ($_POST['fitosanitarioExportador'],ENT_NOQUOTES,'UTF-8');


$qCertificadoCFE = $cfe->buscarFitosanitarioExportacionVUE($conexion, $numeroCFE);
$certificadoCFE = pg_fetch_assoc($qCertificadoCFE);

switch ($opcion) {
	
	
	case 'numeroCFE':
				
		if(pg_numrows($qCertificadoCFE)==0){
			echo "El certificado no existe";
		}else{
			
			echo '<div data-linea="6"><label>Identificación del Exportador: </label>
				<select id="idExportador" name="idExportador" required>
				<option value="">Seleccione...</option>';
				$qExportadores = $cfe->obtenerExportadoresFitosanitarioExportacion($conexion, $certificadoCFE['id_fitosanitario_exportacion']);
			while ($exportadores = pg_fetch_assoc($qExportadores)){
				echo '<option value="'. $exportadores['numero_identificacion_exportador'].'" >'.$exportadores['numero_identificacion_exportador'].' - '.$exportadores['nombre_exportador'].'</option>';
			}
			echo '<input type="hidden" id="idFitodanitarioExportacion" name="idFitodanitarioExportacion" value="'. $certificadoCFE['id_fitosanitario_exportacion'] .'" /><hr/>';
				
		}
		
	break;
	
	case 'datosExportador':
		
		$qDatosExportador = $cfe->buscarFitosanitarioExportacionExportador($conexion, $certificado, $exportador);
		$datosExportador = pg_fetch_assoc($qDatosExportador);

		$qRazonSocialExportador = $cro->buscarOperador($conexion, $exportador);
		$razonSocialExportador = pg_fetch_assoc($qRazonSocialExportador);
		
		echo '<div data-linea="7"><label>Razón social: </label>
					<input type="text" id="razonSocial" name="razonSocial" value="'. $razonSocialExportador['razon_social'] .'" />'.'
	
			</div><hr/>';
		
		echo '<div data-linea="8"><label>País: </label>
					<input type="text" id="paisDestino" name="paisDestino" value="'. $certificadoCFE['nombre_pais_destino'] .'" />'.'
					<input type="hidden" id="idPaisDestino" name="idPaisDestino" value="'. $certificadoCFE['id_pais_destino'] .'" />'.'
		
			</div><hr/>';
		
		echo '<div data-linea="10"><label>Tipo producto: </label>
				<select id="tipoProductoExportador" name="tipoProductoExportador">
				<option value="">Seleccione...</option>';
		
		$qProductosExportador = $cfe->obtenerTipoProductoXExportador($conexion, $certificado, $datosExportador['id_fitosanitario_exportador']);
		
		while ($productosExportador = pg_fetch_assoc($qProductosExportador)){
			echo '<option value="'. $productosExportador['id_tipo_producto'].'" >'.$productosExportador['nombre'].'</option>';
		}
		
		echo '</select>
		
		<input type="hidden" id="idFitosanitarioExportador" name="idFitosanitarioExportador" value="'. $datosExportador['id_fitosanitario_exportador'] .'" />
		</div><hr/>';
		
		break;
	

	case 'tipoProducto':

		//echo $certificado.'</br>'.$_POST['idFitosanitarioExportador'].'</br>'.$_POST['tipoProductoExportador'];
		$subTipoProducto = $cfe-> obtenerSubtipoProductoXExportadorXTipo($conexion, $certificado, $_POST['idFitosanitarioExportador'], $_POST['tipoProductoExportador']);

		echo '<label>Subtipo producto:</label>
				<select id="subtipoProductoExportador" name="subtipoProductoExportador">
				<option value="0" >Seleccionar...</option>';
			
		while ($fila = pg_fetch_assoc($subTipoProducto)){
			echo  '<option  value="'. $fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
		}
			
		echo '</select><hr/>';

		break;


	case 'subtipoProducto':

		$producto = $cfe-> obtenerProductoXExportadorXSubtipo($conexion, $certificado, $_POST['idFitosanitarioExportador'], $_POST['subtipoProductoExportador']);

		echo '<label>Producto:</label>
				<select id="productoExportador" name="productoExportador">
				<option value="0" >Seleccionar...</option>';

		while ($fila = pg_fetch_assoc($producto)){
			echo  '<option  value="'. $fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
		}
		echo '</select>
		<input type="hidden" id="nombreProducto" name="nombreProducto" />';
			
		break;
}


?>

<script type="text/javascript"> 

	$(document).ready(function(){		
		distribuirLineas(); 

	});
	
	$("#idExportador").change(function(event){
    	$('#nuevoNotificacionProductoCFE').attr('data-destino','resultadoDatosExportador');
    	$('#nuevoNotificacionProductoCFE').attr('data-opcion','accionesNotificacionProducto');
    	$('#opcion').val('datosExportador');
    	event.stopPropagation();	
    	abrir($("#nuevoNotificacionProductoCFE"),event,false);	
	 });

	$("#tipoProductoExportador").change(function(event){
    	$('#nuevoNotificacionProductoCFE').attr('data-destino','resultadoTipoProducto');
    	$('#nuevoNotificacionProductoCFE').attr('data-opcion','accionesNotificacionProducto');
    	$('#opcion').val('tipoProducto');
    	event.stopPropagation();	
    	abrir($("#nuevoNotificacionProductoCFE"),event,false);	
	 });

	$("#subtipoProductoExportador").change(function(event){
    	$('#nuevoNotificacionProductoCFE').attr('data-destino','resultadoSubtipoProducto');
    	$('#nuevoNotificacionProductoCFE').attr('data-opcion','accionesNotificacionProducto');
    	$('#opcion').val('subtipoProducto');
    	event.stopPropagation();	
    	abrir($("#nuevoNotificacionProductoCFE"),event,false);	
	 });

	$("#productoExportador").change(function(event){
		$('#nombreProducto').val($('#productoExportador option:selected').text());
		$("#guardar").show();
	});
	
</script>
