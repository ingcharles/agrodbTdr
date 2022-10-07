<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorAplicaciones.php';


$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cc = new ControladorCertificadoCalidad();


$tipoSolicitud = htmlspecialchars ($_POST['solicitudes'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estados'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$identificador = $_SESSION['usuario'];
//$provincia = $_SESSION['nombreProvincia'];
$provincia = $_POST['provincia'];

$condicion = 'Financiero';


$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
			$res = $ca->obtenerAccionesPermitidas($conexion, $opcion, $identificador);
			while($fila = pg_fetch_assoc($res)){
				
				if($fila['estilo'] != '_agrupar' || $estado != 'verificacion'){
					
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
				
		case 'certificadoCalidad':
			
			
			if($estado == 'pago'){
			
				$qSitios = $cc->obtenerSolicitudesCertificadoCalidad($conexion, $provincia, $estado, 'SITIOS', $identificadorOperador);
				$qOperadores = $cc->obtenerSolicitudesCertificadoCalidad($conexion, $provincia, $estado, 'CERTIFICADOCALIDAD',$identificadorOperador);
			
				while($sitio = pg_fetch_assoc($qSitios)){
					echo '<div id="'.$sitio['id_area_operacion'].'">
						<h2>'.$sitio['nombre_area_operacion'].'</h2>
						<div class="elementos"></div>
					</div>';
				}
			
			
				$contador = 0;
				while($operacion = pg_fetch_assoc($qOperadores)){
					$categoria = $operacion['id_area_operacion'];
					$contenido = '<article
										id="'.$operacion['id_lote_inspeccion'].'"
										class="item"
										data-rutaAplicacion="certificadoCalidad"
										data-opcion="abrirCertificadoCalidadFinancieroGrupo"
										ondragstart="drag(event)"
										draggable="true"
										data-destino="detalleItem">
										<span class="ordinal">'.++$contador.'</span>
										<span> # '.$operacion['id_lote_inspeccion'].'</span><br />
										<span> # Lote: '.$operacion['numero_lote'].'<br />
										<span> Producto: '.$operacion['nombre_producto'].'</span>
										<aside><span>'.date('j/n/Y',strtotime($operacion['fecha_solicitud'])).'</span></aside>
								</article>';
					?>
						<script type="text/javascript">
							var contenido = <?php echo json_encode($contenido);?>;
							var categoria = <?php echo json_encode($categoria);?>;
							$("#"+categoria+" div.elementos").append(contenido);
						</script>
					<?php					
					}
				}else{
							
					$qOperadores = $cc->buscarSolicitudesCalidadFinancieroVerificacion($conexion, $identificadorOperador, $estado, $provincia, $tipoSolicitud);
					
					while($operacion = pg_fetch_assoc($qOperadores)){
	
						echo  '<article
									id="'.$operacion['id_solicitud'].'"
									class="item"
									data-rutaAplicacion="certificadoCalidad"
									data-opcion="abrirCertificadoCalidadFinancieroGrupo"
									ondragstart="drag(event)"
									draggable="true"
									data-destino="detalleItem"
									data-nombre= "'.$operacion['id_grupo'].'">
									<span class="ordinal">'.++$contador.'</span>
									<span> # '.$operacion['id_solicitud'].'</span><br />
									<aside>'.$operacion['identificador_exportador'].'</aside>
								</article>';
						
					}
					
				}
			
		break;
			
		default :
			echo 'Formulario desconocido';
		break;
		
	}
	
	
	
	?>

<script type="text/javascript"> 

	$("#listadoItems").addClass("comunes");
	$('#_agrupar').attr('data-rutaaplicacion','certificadoCalidad');
	$('#_agrupar').attr('data-opcion','abrirCertificadoCalidadFinancieroGrupo');

	
</script>

