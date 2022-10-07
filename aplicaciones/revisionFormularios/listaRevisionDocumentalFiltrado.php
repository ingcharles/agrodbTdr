<?php 

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
//require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

//Controladores formularios
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorTramitesInocuidad.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';
require_once '../../clases/ControladorImportacionesFertilizantes.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';
require_once '../../clases/ControladorTransitoInternacional.php';




$conexion = new Conexion();
$ca = new ControladorAplicaciones();


$tipoSolicitud = $_POST['solicitudes'];
$opcion = $_POST['opcion'];
$medioTransporte = $_POST['medioTransporte'];

$identificador = $_SESSION['usuario'];
$estado = 'Documental';
$provinciaUsuario = $_SESSION['nombreProvincia'];


$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion, $opcion, $identificador);
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
		
		case 'Importación sanidad vegetal' : 
			$ci = new ControladorImportaciones();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $ci -> listarImportacionesRevisionProvinciaRS($conexion, 'enviado', 'SV', $provinciaUsuario);
			}else{
				$res = $ci -> listarImportacionesAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'SV', 'Importación', $estado);
			}
			
			$nombreArchivo = 'abrirImportacionEnviada';
			$nombreModulo = 'importaciones';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;	
		
		case 'Importación sanidad animal' : 
			$ci = new ControladorImportaciones();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $ci -> listarImportacionesRevisionProvinciaRS($conexion, 'enviado', 'SA', $provinciaUsuario);
			}else{
				$res = $ci -> listarImportacionesAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'SA', 'Importación', $estado);
			}
				
			$nombreArchivo = 'abrirImportacionEnviada';
			$nombreModulo = 'importaciones';
				
			$nombreSolicitud = $tipoSolicitud;
				
		break;
		
		case 'Importación plaguicidas' : 
			$ci = new ControladorImportaciones();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $ci -> listarImportacionesRevisionProvinciaRS($conexion, 'enviado', 'IAP', $provinciaUsuario);
			}else{
				$res = $ci -> listarImportacionesAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'IAP', 'Importación', $estado);
			}
				
			$nombreArchivo = 'abrirImportacionEnviada';
			$nombreModulo = 'importaciones';
				
			$nombreSolicitud = $tipoSolicitud;
				
		break;
		
		case 'Importación veterinarios' : 
			$ci = new ControladorImportaciones();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $ci -> listarImportacionesRevisionProvinciaRS($conexion, 'enviado', 'IAV', $provinciaUsuario);
			}else{
				$res = $ci -> listarImportacionesAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'IAV' , 'Importación', $estado);
			}
				
			$nombreArchivo = 'abrirImportacionEnviada';
			$nombreModulo = 'importaciones';
				
			$nombreSolicitud = $tipoSolicitud;
				
		break;
		
		case 'Importación fertilizantes' :
			$ci = new ControladorImportaciones();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $ci -> listarImportacionesRevisionProvinciaRS($conexion, 'enviado', 'IAF', $provinciaUsuario);
			}else{
				$res = $ci -> listarImportacionesAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'IAF' , 'Importación', $estado);
			}
			
			$nombreArchivo = 'abrirImportacionEnviada';
			$nombreModulo = 'importaciones';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
		
		case 'importacionMuestras' :
			
			$cif = new ControladorImportacionesFertilizantes();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $cif -> listarImportacionesFertilizantesRevisionProvinciaRS($conexion, 'enviado');
			}else{
				$res = $cif -> listarImportacionesFertilizantesAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'importacionMuestras', $estado);
			}
			
			$nombreArchivo = 'abrirImportacionFertilizantesEnviada';
			$nombreModulo = 'importacionesFertilizantes';
			
			$nombreSolicitud = $tipoSolicitud;
			
			break;
		
		case 'DDA' :
			$cd = new ControladorDestinacionAduanera();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $cd -> listarDDARevisionProvinciaRS($conexion, 'enviado', $provinciaUsuario, $medioTransporte);
			}else{
				$res = $cd -> listarDDAAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
			
			$nombreArchivo = 'abrirDDAEnviado';
			$nombreModulo = 'destinacionAduanera';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
	
		case 'Fitosanitario' :
			$cf = new ControladorFitosanitario();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $cf -> listarFitoRevisionProvinciaRS($conexion, 'enviado', $provinciaUsuario, $medioTransporte);
			}else{
				$res = $cf -> listarFitoAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
				
			$nombreArchivo = 'abrirFitoExportacionEnviado';
			$nombreModulo = 'exportacionFitosanitario';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
			
		case 'Zoosanitario' :
			$cz = new ControladorZoosanitarioExportacion();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $cz -> listarZooRevisionProvinciaRS($conexion, 'enviado', $provinciaUsuario);
			}else{
				$res = $cz -> listarZooAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
			
			$nombreArchivo = 'abrirZoosanitarioEnviado';
			$nombreModulo = 'exportacionZoosanitario';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
			
		case 'Muestras' :
			
			$nombreArchivo = 'abrirMuestrasEnviada';
			$nombreModulo = '';
			
		break;
		
		case 'CLV' :
				
			$cl = new ControladorClv();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $cl -> listarClvRevisionProvinciaRS($conexion, 'enviado');
			}else{
				$res = $cl -> listarClvAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
		
			$nombreArchivo = 'abrirClvEnviado';
			$nombreModulo = 'certificadoLibreVenta';
				
			$nombreSolicitud = 'Certificado de Libre Venta';
				
			break;
					
		/*case 'certificadoCalidad' :
			
			$cc = new ControladorCertificadoCalidad();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $cc -> listarCertificadoCalidadDisponibles($conexion, 'enviado',$provinciaUsuario);
			}else{
				//$res = $cc -> listarClvAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], $tipoSolicitud, $estado); //TODO: revisar
			} 
				
			$nombreArchivo = 'abrirCertificadoCalidadDocumental';
			$nombreModulo = 'certificadoCalidad';
			
			$nombreSolicitud = 'Certificado de Calidad';
			
		break;*/
		
		case 'tramitesInocuidad' :
				
			$cti = new ControladorTramitesInocuidad();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $cti -> listarTramitesDisponibles($conexion, "'enviado'");
			}else{
				$res = $cti -> listarTramitesAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], $tipoSolicitud, $estado); 
			}
		
			$nombreArchivo = 'abrirTramiteDocumental';
			$nombreModulo = 'tramitesInocuidad';
				
			$nombreSolicitud = 'Seguimiento de tramites Inocuidad';
				
		break;
		
		case 'FitosanitarioExportacion' :
			$cfe = new ControladorFitosanitarioExportacion();
		
			if($_POST['inspectores'] == 'asignar'){
				$res = $cfe -> listarFitosanitarioExportacionPorProvincia($conexion, $provinciaUsuario ,'enviado');
			}else{
				$res = $cfe -> listarFitosanitarioExportacionPorPorInspectorAsignado($conexion, 'asignadoDocumental', $_POST['inspectores'], $tipoSolicitud, $estado);
			}
		
			$nombreArchivo = 'abrirFitosanitarioExportacionDocumental';
			$nombreModulo = 'fitosanitarioExportacion';				
			$nombreSolicitud = 'Certificado fitosanitario de exportación';
				
		break;
		
		case 'mercanciasSinValorComercialExportacion':
				
			$cme = new ControladorMercanciasSinValorComercial();
				
			if($_POST['inspectores'] == 'asignar'){
				$res = $cme->listarImportacionExportacionRevisionRS($conexion, 'enviado', "('Exportacion')");
			}else{
				$res = $cme->listarImportacionExportacionAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'mercanciasSinValorComercialExportacion', $estado, 'Exportacion');
			}

			$nombreArchivo = 'abrirExportacionDocumentalInspeccion';			
			$nombreModulo = 'mercanciasSinValorComercial';			
			$nombreSolicitud = 'Exportación de mercancias sin valor comercial';
			
			break;
		
		case 'mercanciasSinValorComercialImportacion':
				
			$cme = new ControladorMercanciasSinValorComercial();
			
			if($_POST['inspectores'] == 'asignar'){
				$res = $cme->listarImportacionExportacionRevisionRS($conexion, 'enviado', "('Importacion')");
			}else{
				$res = $cme->listarImportacionExportacionAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'mercanciasSinValorComercialImportacion', $estado,'Importacion');
			}
		
			$nombreArchivo = 'abrirImportacionDocumentalInspeccion';			
			$nombreModulo = 'mercanciasSinValorComercial';			
			$nombreSolicitud = 'Importación de Mascotas';
			
			break;
			
		case 'transitoInternacional' :
		    
		    $cti = new ControladorTransitoInternacional();
		    
		    if($_POST['inspectores'] == 'asignar'){
		        $res = $cti -> listarTransitoInternacionalRevisionProvinciaRS($conexion, 'enviado', $provinciaUsuario);
		    }else{
		        $res = $cti -> listarTransitoInternacionalAsignadasInspectorRS($conexion, 'asignadoDocumental', $_POST['inspectores'], 'transitoInternacional', $estado, $provinciaUsuario);
		    }
		    
		    $nombreArchivo = 'abrirTransitoInternacionalEnviada';
		    $nombreModulo = 'transitoInternacional';
		    
		    $nombreSolicitud = $tipoSolicitud;
		    
		    break;
		    			
		default :{
			$res='';
			$nombreArchivo = '';
			$nombreModulo = '';
			break;
		}
	}
	
	while($solicitud = pg_fetch_assoc($res)){
		if($solicitud['id_vue'] != ''){
			$numeroSolicitud = $solicitud['id_vue'];
		}else{
			$numeroSolicitud = $solicitud['id_solicitud'];
		}
		
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
						<td>'.($solicitud['pais']==''?'No aplica':$solicitud['pais']).'</td>
					</tr>');
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

