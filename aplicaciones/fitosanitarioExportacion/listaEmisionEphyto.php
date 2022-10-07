<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();

$itemsFiltrados[] = array();

?>
<header>
	<h1>Datos ephyto</h1>
	<nav>
		<?php
            $ca = new ControladorAplicaciones();            
            $res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);            
            while ($fila = pg_fetch_assoc($res)) {
                echo '<a href="#"
        						id="' . $fila['estilo'] . '"
        						data-destino="detalleItem"
        						data-opcion="' . $fila['pagina'] . '"
        						data-rutaAplicacion="' . $fila['ruta'] . '"
        						>' . (($fila['estilo'] == '_seleccionar') ? '<div id="cantidadItemsSeleccionados">0</div>' : '') . $fila['descripcion'] . '</a>';
            }
        ?>
	</nav>

</header>

<?php

    $cfe = new ControladorFitosanitarioExportacion();

    $res = $cfe -> listarFitosanitarioExportacionPorEstado($conexion, 'aprobado', 'Holanda');
    
    while($solicitud = pg_fetch_assoc($res)){
    
    $itemsFiltrados[] = array('<tr
							id="'.$solicitud['id_fitosanitario_exportacion'].'"
							class="item"
							data-rutaAplicacion="fitosanitarioExportacion"
							data-opcion="abrirEmisionEphyto"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td style="white-space:nowrap;"><b>'.$solicitud['identificador_operador'].'</b></td>
						<td>'.$solicitud['id_vue'].'</td>
						<td>Certificado fitosanitario de exportación</td>
						<td>'.($solicitud['nombre_pais_destino']==''?'No aplica':$solicitud['nombre_pais_destino']).'</td>
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
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una solicitud para revisarla.</div>');
	});



</script>
