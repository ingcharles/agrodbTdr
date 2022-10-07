<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();
$cf = new ControladorFinanciero();

$datos = array('tipoCliente' => htmlspecialchars ($_POST['tipoCliente'],ENT_NOQUOTES,'UTF-8'),
			   'identificador' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
			   'fechaInicio' => htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8'),
			   'fechaFin' => htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8'),
		 	   'tipoSaldo' => htmlspecialchars ($_POST['tipoSaldo'],ENT_NOQUOTES,'UTF-8'),
			   'tipoSolicitud' => 'saldoVue',
			   'tipoProceso' => 'comprobante');

$qDatosOperador = $cf->obtenerCantidadOrdenPagoPorTipoSolicitudFechas($conexion, $datos['identificador'], $datos['tipoSolicitud'], $datos['fechaInicio'], $datos['fechaFin'], 'campoGrupal');
$datosComprobante = pg_fetch_assoc($cf->obtenerCantidadOrdenPagoPorTipoProcesoFechas($conexion, $datos['identificador'], $datos['tipoProceso'], $datos['fechaInicio'], $datos['fechaFin'], 'campoGrupal'));
$datosSaldo = 	pg_fetch_assoc($cf->obtenerMaxSaldoPorIdentificadorFechas($conexion, $datos['identificador'], $datos['tipoSaldo'], $datos['fechaInicio'], $datos['fechaFin']));

?>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Razón social</th>
			<th># Facturas</th>
			<th># Comprobantes</th>
			<th>Monto consumido</th>
			<th>Saldo</th>
		</tr>
	</thead>

	<?php 
	$contador = 0;
	
	if(pg_num_rows($qDatosOperador) != 0){

		$datosOperador = pg_fetch_assoc($qDatosOperador);
		
		echo '<tr
				id="'.$datos['identificador'].'-'.$datos['fechaInicio'].'-'.$datos['fechaFin'].'-'.$datos['tipoSaldo'].'-'.$datos['tipoSolicitud'].'-'.$datos['tipoProceso'].'"
				class="item"
				data-rutaAplicacion="financiero"
				data-opcion="abrirSaldo"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$datosOperador['identificador_operador'].'</b></td>
				<td>'.$datosOperador['razon_social'].'</td>
				<td>'. $datosOperador['facturas_generadas'].'</td>				<td>'.$datosComprobante['comprobantes_generados'].'</td>
				<td>'.$datosComprobante['consumo_comprobantes'].'</td>
				<td>'.$datosSaldo['saldo_disponible'].'</td>
			</tr>';
		}
	?>
</table>

<script type="text/javascript"> 

$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para visualizar.</div>');
});
</script>

