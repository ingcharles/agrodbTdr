<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';
//require_once '../../clases/ControladorRevisionSolicitudes.php';

//Controladores formularios
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cd = new ControladorDestinacionAduanera();
$cf = new ControladorFitosanitario();


$tipoSolicitud = $_POST['solicitudes'];
$opcion = $_POST['opcion'];
$identificador = $_SESSION['usuario'];
$estado = 'Técnico';

$provinciaUsuario = $_SESSION['nombreProvincia'];
$medioTransporte = $_POST['medioTransporte'];

$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion,$opcion, $identificador);
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
	echo'</nav></header>';
	
	//Elección de tipo de formulario para impresión
	
	switch ($tipoSolicitud){
		
		case 'DDA' :
			$cd = new ControladorDestinacionAduanera();
			
			//$res = $cd -> listarDDAAsignadasInspectorRS($conexion, 'inspeccion', $_POST['inspectores']);
			if($_POST['inspectores'] == 'asignar'){
				$res = $cd -> listarDDARevisionProvinciaRS($conexion, 'inspeccion', $provinciaUsuario, $medioTransporte);
			}else{
				$res = $cd -> listarDDAAsignadasInspectorRS($conexion, 'asignadoInspeccion', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
			
			$nombreArchivo = 'abrirDDAEnviado';
			$nombreModulo = 'destinacionAduanera';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
		
		case 'Zoosanitario' :
			$cz = new ControladorZoosanitarioExportacion();
			
			//$res = $cz -> listarZooAsignadasInspectorRS($conexion, 'inspeccion', $_POST['inspectores']);
			if($_POST['inspectores'] == 'asignar'){
				$res = $cz -> listarZooRevisionProvinciaRS($conexion, 'inspeccion', $provinciaUsuario);
			}else{
				$res = $cz -> listarZooAsignadasInspectorRS($conexion, 'asignadoInspeccion', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
			
			$nombreArchivo = 'abrirZoosanitarioEnviado';
			$nombreModulo = 'exportacionZoosanitario';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
		
		case 'certificadoCalidad':
			//$cc			
		break;
		
		case 'FitosanitarioExportacion':
			$cfe = new ControladorFitosanitarioExportacion();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $cfe -> listarFitosanitarioExportacionPorProvincia($conexion, $provinciaUsuario ,'inspeccion');
			}else{
				$res = $cfe -> listarFitosanitarioExportacionPorPorInspectorAsignado($conexion, 'asignadoInspeccion', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
			
			$nombreArchivo = 'abrirFitosanitarioExportacionInspeccion';
			$nombreModulo = 'fitosanitarioExportacion';
			$nombreSolicitud = 'Certificado fitosanitario de exportación';
		break;
		
		case 'mercanciasSinValorComercialExportacion':
				
			$cme = new ControladorMercanciasSinValorComercial();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $cme->listarImportacionExportacionRevisionRS($conexion, 'inspeccion', "('Exportacion')");
			}else{
				$res = $cme->listarImportacionExportacionAsignadasInspectorRS($conexion, 'asignadoInspeccion', $_POST['inspectores'], 'mercanciasSinValorComercialExportacion', $estado, 'Exportacion');
			}

			$nombreArchivo = 'abrirExportacionDocumentalInspeccion';			
			$nombreModulo = 'mercanciasSinValorComercial';			
			$nombreSolicitud = 'Exportación de mercancias sin valor comercial';
			
		break;
		
		case 'mercanciasSinValorComercialImportacion':
				
			$cme = new ControladorMercanciasSinValorComercial();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $cme->listarImportacionExportacionRevisionRS($conexion, 'inspeccion', "('Importacion')");
			}else{
				$res = $cme->listarImportacionExportacionAsignadasInspectorRS($conexion, 'asignadoInspeccion', $_POST['inspectores'], 'mercanciasSinValorComercialImportacion', $estado ,'Importacion');
			}
		
			$nombreArchivo = 'abrirImportacionDocumentalInspeccion';			
			$nombreModulo = 'mercanciasSinValorComercial';			
			$nombreSolicitud = 'Importación de Mascotas';
			
		break; 
			
		default :{
			$res='';
			$nombreArchivo = '';
			$nombreModulo = '';
			break;
		}
	}
	
	while($solicitud = pg_fetch_assoc($res)){
	    
	    $validacion = false;
		
		if($solicitud['id_vue'] != ''){
			$numeroSolicitud = $solicitud['id_vue'];
		}else{
			$numeroSolicitud = $solicitud['id_solicitud'];
		}
		
		if($tipoSolicitud == 'DDA'){
		        if($solicitud['contador_inspeccion'] == '2'){
		            $validacion = true;
		        }
		}else{
            $validacion = true;		    
		}
		
		if($validacion){
		    
    		$itemsFiltrados[] = array('<tr
    							id="'.$solicitud['id_solicitud'].'"
    							class="item"
    							data-rutaAplicacion="'.$nombreModulo.'"
    							data-opcion="'.$nombreArchivo.'"
    							ondragstart="drag(event)"
    							draggable="true"
    							data-destino="detalleItem">
    						<td>'.++$contador.'</td>
    						<td style="white-space:nowrap;"><b>'.$solicitud['identificador_operador'].'</b></td>
    						<td>'.$numeroSolicitud.'</td>
    						<td>'.($solicitud['tipo_certificado']!=''?$solicitud['tipo_certificado']:$nombreSolicitud).'</td>
    						<td>'.$solicitud['pais'].'</td>
    					</tr>');
		}
		
	}
	
	
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>RUC</th>
			<th>#Solicitud</th>
			<th>Tipo de Certificado</th>
			<th>País</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript"> 
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');
	});

	$('#_asignar').addClass('_asignar');
	$('#_asignar').attr('id', <?php echo json_encode($tipoSolicitud);?>+'-'+<?php echo json_encode($estado);?>);
	
</script>

