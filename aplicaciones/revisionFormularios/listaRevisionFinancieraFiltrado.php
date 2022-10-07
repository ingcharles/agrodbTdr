<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';
//require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
//Controladores formularios
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cd = new ControladorDestinacionAduanera();

//$crs = new ControladorRevisionSolicitudesVUE();

$tipoSolicitud = $_POST['solicitudes'];
$estado = 'Financiero';
//$provinciaUsuario = $_SESSION['nombreProvincia'];
$provinciaUsuario = $_POST['provincia'];

$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			while($fila = pg_fetch_assoc($res)){
				if($fila['estilo'] != '_agrupar'){
					echo '<a href="#"
							id="' . $fila['estilo'] . '"
							data-destino="detalleItem"
							data-opcion="' . $fila['pagina'] . '"
							data-rutaAplicacion="' . $fila['ruta'] . '"
							>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				}
			}
	echo'</nav></header>';
	
	//Elección de tipo de formulario para impresión
	
	switch ($tipoSolicitud){
		
		case 'Operadores' :
			$cr = new ControladorRegistroOperador();
		
			$res = $cr -> listarOperacionesRevisionFinancieroRS($conexion, $provinciaUsuario,$_POST['estados']);
		
			$nombreArchivo = 'abrirOperacionEnviadaFinanciero';
			$nombreModulo = 'registroOperador';
		
			$nombreSolicitud = 'Registro de Operador';
		
		break;
		
		case 'Importación' :
			$ci = new ControladorImportaciones();
			
			$res = $ci -> listarImportacionesRevisionFinancieroRS($conexion, $provinciaUsuario,$_POST['estados']);
			
			$nombreArchivo = 'abrirImportacionEnviadaFinanciero';
			$nombreModulo = 'importaciones';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
		
		
		case 'Fitosanitario' :
			$cf = new ControladorFitosanitario();
			
			$res = $cf -> listarFitoRevisionFinancieroRS($conexion, $provinciaUsuario ,$_POST['estados']);
			
			$nombreArchivo = 'abrirFitoExportacionEnviadoFinanciero';
			$nombreModulo = 'exportacionFitosanitario';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
			
		case 'Zoosanitario' :
			$cz = new ControladorZoosanitarioExportacion();
			
			$res = $cz -> listarZooRevisionFinancieroRS($conexion, $provinciaUsuario,$_POST['estados']);
				
			$nombreArchivo = 'abrirZoosanitarioEnviadoFinanciero';
			$nombreModulo = 'exportacionZoosanitario';
			
			$nombreSolicitud = $tipoSolicitud;
			
		break;
						
		case 'CLV' :
			
			$cl = new ControladorClv();
			
			$res = $cl -> listarClvRevisionFinancieroRS($conexion, $_POST['estados']);
				
			$nombreArchivo = 'abrirClvEnviadoFinanciero';
			$nombreModulo = 'certificadoLibreVenta';
			$nombreSolicitud = 'Certificado de Libre Venta';
			
		break;
		
		case 'certificadoCalidad':
			$cc = new ControladorCertificadoCalidad();
			
			$res = $cc->listarCertificadoCalidadImposicionTasa($conexion, $provinciaUsuario, $_POST['estados']);
			
			$nombreArchivo = 'abrirCalidadImposicionTasa';
			$nombreModulo = 'certificadoCalidad';
			$nombreSolicitud = 'Certificado de calidad';
			
		break;
		
			
		default :
			$res='';
			$nombreArchivo = '';
			$nombreModulo = '';
		break;

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
						<td>'.$solicitud['pais'].'</td>
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
	});

	$('#_asignar').addClass('_asignar');
	$('#_asignar').attr('id', <?php echo json_encode($tipoSolicitud);?>+'-'+<?php echo json_encode($estado);?>);
</script>

