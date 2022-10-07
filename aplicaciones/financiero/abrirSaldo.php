<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';


$conexion = new Conexion();
$cf = new ControladorFinanciero();
$cc = new ControladorCertificados();

$registro = explode('-', $_POST['id']);

$datos = array('identificador' => $registro[0],
			   'fechaInicio' => $registro[1],
			   'fechaFin' => $registro[2],
		 	   'tipoSaldo' => $registro[3],
			   'tipoSolicitud' => $registro[4],
			   'tipoProceso' => $registro[5]);

$datosCliente = pg_fetch_assoc($cc->listaComprador($conexion, $datos['identificador']));
$datosFacturaSaldo = $cf->obtenerCantidadOrdenPagoPorTipoSolicitudFechas($conexion, $datos['identificador'], $datos['tipoSolicitud'], $datos['fechaInicio'], $datos['fechaFin'], 'campoIndividual');
$montoConsumo = pg_fetch_assoc($cf->obtenerCantidadOrdenPagoPorTipoProcesoFechas($conexion, $datos['identificador'], $datos['tipoProceso'], $datos['fechaInicio'], $datos['fechaFin'], 'campoGrupal'));
$datosComprobante = $cf->obtenerCantidadOrdenPagoPorTipoProcesoFechas($conexion, $datos['identificador'], $datos['tipoProceso'], $datos['fechaInicio'], $datos['fechaFin'], 'campoIndividual');

$datosSaldo = 	pg_fetch_assoc($cf->obtenerMaxSaldoPorIdentificadorFechas($conexion, $datos['identificador'], $datos['tipoSaldo'], $datos['fechaInicio'], $datos['fechaFin']));

?>

<header>
	<h1>Detalle saldos</h1>
</header>

	<fieldset>
        <legend>Datos operador</legend>
        <div data-linea = "1">
        	<label>Identificador: </label><?php echo $datosCliente['identificador'];?>
        </div>
        
        <div data-linea = "2">
        	<label>Razón social: </label><?php echo $datosCliente['razon_social'];?>
        </div>
        
        <div data-linea = "3">
        	<label>Dirección: </label><?php echo $datosCliente['direccion'];?>
        </div>
        
        <div data-linea = "4">
        	<label>Monto consumo: </label><?php echo $montoConsumo['consumo_comprobantes'];?>
        </div>
        
        <div data-linea = "4">
        	<label>Saldo disponible VUE: </label><?php echo $datosSaldo['saldo_disponible'];?>
        </div>
	</fieldset>
		
	<fieldset>
		<legend>Facturas generadas para acreditación de saldo - # <?php echo pg_num_rows($datosFacturaSaldo);?></legend>
		
		<table id="tablaItems">
			<thead>
				<tr>
					<th>Número factura</th>
					<th>Fecha</th>
					<th>Servicio</th>
					<th>Total</th>
				</tr>
			</thead>
		
		<?php
			
		while ($factura = pg_fetch_assoc($datosFacturaSaldo)){
		
			echo '<tr>
					<td><a href='.$factura['factura'].' target="_blank" class="archivo_cargado" id="archivo_cargado">'.$factura['numero_establecimiento'].'-'.$factura['punto_emision'].'-'.$factura['numero_factura'].'</a></td>
					<td>'.date('d/m/Y G:i',strtotime($factura['fecha_facturacion'])).'</td>
					<td>'. $factura['concepto_orden'].'</td>
					<td>'.$factura['total_pagar'].'</td>
				</tr>';
				
		}		
		
		?>
		</table>
	</fieldset>
	
	<fieldset>
		<legend>Comprobantes de pago VUE - # <?php echo pg_num_rows($datosComprobante);?></legend>
		
		<table id="tablaItems">
			<thead>
				<tr>
					<th>Número comprobante pago VUE</th>
					<th>Fecha</th>
					<th>Servicio</th>
					<th># Solciitud</th>
					<th>Total</th>
				</tr>
			</thead>
		
		<?php
			
		while ($comprobante = pg_fetch_assoc($datosComprobante)){

			switch ($comprobante['tipo_solicitud']){
				
				case 'Importación':
					$ci = new ControladorImportaciones();
					$importacion = pg_fetch_assoc($ci->obtenerImportacion($conexion, $comprobante['id_solicitud']));
					$solicitudAtendida = $importacion['id_vue'];
					$tipoServicio = 'Importación';
				break;
						
				case 'Fitosanitario':
					$cfi = new ControladorFitosanitario();
					$fitosanitario = pg_fetch_assoc($cfi->listarFitoExportacion($conexion, $comprobante['id_solicitud']));
					$solicitudAtendida = $fitosanitario['id_vue'];
					$tipoServicio = 'Fitosanitario';
				break;
						
				case 'FitosanitarioExportacion':
					$cfe = new ControladorFitosanitarioExportacion();
					$fitosanotarioExportacion = pg_fetch_assoc($cfe->obtenerCabeceraFitosanitarioExportacion($conexion, $comprobante['id_solicitud']));
					$solicitudAtendida = $fitosanotarioExportacion['id_vue'];
					$tipoServicio = 'Fitosanitario';
				break;
						
			}
		
			echo '<tr>
					<td><a href='.$comprobante['factura'].' target="_blank" class="archivo_cargado" id="archivo_cargado">'.$comprobante['numero_establecimiento'].'-'.$comprobante['punto_emision'].'-'.$comprobante['numero_factura'].'</a></td>
					<td>'.date('d/m/Y G:i',strtotime($comprobante['fecha_facturacion'])).'</td>
					<td>'.$tipoServicio.'</td>
					<td>'.$solicitudAtendida.'</td>
					<td>'.$comprobante['total_pagar'].'</td>
				</tr>';
				
		}		
		
		?>
		</table>
		
	</fieldset>
	


	
<script type="text/javascript">

$('document').ready(function(){	
	distribuirLineas();	
});

</script>