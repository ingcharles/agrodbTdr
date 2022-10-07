<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorAplicaciones.php';


$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cc = new ControladorCertificadoCalidad();



$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$identificador = $_SESSION['usuario'];

$provincia = $_POST['provincia'];
$estado = "'inspeccionResponsable'";


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
										data-opcion="abrirCertificadoCalidadResponsableGrupo"
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
	
	?>

<script type="text/javascript"> 

	$("#listadoItems").addClass("comunes");	
</script>

