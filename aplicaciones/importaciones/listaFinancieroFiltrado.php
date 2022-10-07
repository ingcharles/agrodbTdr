<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$ci = new ControladorImportaciones();

$contador = 0;
$itemsFiltrados[] = array();

	echo'<header> <nav>';
		 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
		
	echo'</nav></header>';
	
	if($_POST['estados'] == 'pago'){
		//listar importaciones con estado pago, autorizado, confirmado
		$res = $ci -> listarImportacionesRevisionFinanciero($conexion);
	}else{
		$res = $ci -> listarImportacionesRevisionFinanciero($conexion, $_POST['estados']);
	}
	
	while($importaciones = pg_fetch_assoc($res)){
	
		$itemsFiltrados[] = array('<tr
							id="'.$importaciones['id_importacion'].'"
							class="item"
							data-rutaAplicacion="importaciones"
							data-opcion="abrirImportacionEnviadaFinanciero"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td style="white-space:nowrap;"><b>'.$importaciones['razon_social'].'</b></td>
						<td>'.$importaciones['id_importacion'].'</td>
						<td>'.$importaciones['tipo_certificado'].'</td>
						<td>'.$importaciones['pais_exportacion'].'</td>
					</tr>');
	}
?>	

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>RUC</th>
			<th>#Importación</th>
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
	});
</script>