<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCertificacionBPA.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cbpa = new ControladorCertificacionBPA();
$cap = new ControladorAplicacionesPerfiles();



$tipoSolicitud = htmlspecialchars ($_POST['solicitudes'],ENT_NOQUOTES,'UTF-8');
$condicion = htmlspecialchars ($_POST['condicion'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$identificadorInspector = htmlspecialchars ($_POST['inspectores'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estados'],ENT_NOQUOTES,'UTF-8');
$estadoActual = htmlspecialchars ($_POST['estadoActual'],ENT_NOQUOTES,'UTF-8');
$tipoOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
$revisionUbicacion = htmlspecialchars ($_POST['revisionUbicacion'],ENT_NOQUOTES,'UTF-8');

//////

$nombreOpcion=$_POST['nombreOpcion'];
$provincia = $_SESSION['nombreProvincia'];
$identificador = $_SESSION['usuario'];
$idAplicacion = $_SESSION['idAplicacion'];

$contador = 0;


	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion, $opcion, $_SESSION['usuario']);
						
			while($fila = pg_fetch_assoc($res)){
				
					echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';				
			}
			
	echo'</nav></header>';
	
	switch ($tipoSolicitud){

	    case 'certificacionBPA':
	    	
	    	$perfiles = $cap->obtenerPerfilesUsuario($conexion, $idAplicacion, $identificador);
	    	
	    	while ($perfil = pg_fetch_assoc($perfiles)){
	    		if($perfil['codificacion_perfil'] == 'PFL_REV_CERT_BPA'){
	    			$asociacion ='';
	    			$pagina = 'abrirSolicitudDocumentalInspeccion';
	    		}else if($perfil['codificacion_perfil'] == 'PFL_ADM_CERT_BPA'){
	    			$asociacion ='Si';
	    			$provincia ='';
	    			$pagina = 'abrirSolicitudAsignarProvincia';
	    		}else if($perfil['codificacion_perfil'] == 'PFL_APR_CERT_BPA'){
	    			$asociacion ='';
	    			$pagina = 'abrirSolicitudDocumentalInspeccion';
	    		}
	    	}
        break;
	   
		default :
			echo 'Formulario desconocido';
		break;
	}
	
	$qSitios = $cbpa->obtenerSitiosOperadorCertificacionBPA($conexion, $estado, $identificadorOperador, $provincia,$asociacion);
	$qOperadores = $cbpa->obtenerSolicitudesOperadorCertificacionBPA($conexion, $estado, $identificadorOperador, $provincia, $asociacion);
	
	while($sitio = pg_fetch_assoc($qSitios)){
		echo '<div id="'.$sitio['id_sitio'].'" class="contenedor">
						<h2>'.$sitio['nombre_lugar'].'</h2>
						<div class="elementos"></div>
					</div>';
	}
	
	while($operacion = pg_fetch_assoc($qOperadores)){
		
		$categoria = $operacion['id_sitio'];
		$contenido = '<article
							id="'.$operacion['id_solicitud'].'"
							class="item"
							data-rutaAplicacion="certificacionesBPA"
							data-opcion="'.$pagina.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem"
							data-sitio="'.$operacion['id_sitio'].'"
							data-idOpcion="'.$nombreOpcion.'">
							<span class="ordinal">'.++$contador.'</span>
							<span> # '.$operacion['id_solicitud'].'</span><br />
							<span><small>Certificación BPA</small></span>
							<aside>'.date('j/n/Y',strtotime($operacion['fecha_creacion'])).'</aside>
					</article>';
		?>
						<script type="text/javascript">
							var contenido = <?php echo json_encode($contenido);?>;
							var categoria = <?php echo json_encode($categoria);?>;
							$("#"+categoria+" div.elementos").append(contenido);
						</script>
		<?php					
		}
		?>

<script type="text/javascript"> 

	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');
	$('#_agrupar').attr('data-rutaaplicacion','certificacionesBPA');
	$('#_agrupar').attr('data-idOpcion',<?php echo json_encode($nombreOpcion);?>);

	$('#_asignar').addClass('_asignar');
	$('#_asignar').attr('id', <?php echo json_encode($tipoSolicitud);?>+'-'+<?php echo json_encode($condicion);?>);

</script>

