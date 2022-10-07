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

    $res = $cfe -> listarFitosanitarioExportacionRecibidos($conexion, 'CONFIRMADO','HUB');
    
    while($solicitud = pg_fetch_assoc($res)){
    
    $itemsFiltrados[] = array('<tr style = "text-align:center;"
							id="'.$solicitud['id_recepcion'].'"
							class="item"
							data-rutaAplicacion="fitosanitarioExportacion"
							data-opcion="abrirRecepcionEphyto"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td style="white-space:nowrap;"><b>'.$solicitud['codigo'].'</b></td>
						<td>'.date('Y/m/d',strtotime($solicitud['fecha'])).'</td>
					</tr>');
    
    }

?>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th># Solicitud</th>
			<th>Fecha recepción</th>
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
