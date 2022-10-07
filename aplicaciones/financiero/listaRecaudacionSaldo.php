<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorFinanciero.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();
$cf = new ControladorFinanciero();

	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$provincia = $_POST['provincia'];
	$establecimiento = $_POST['establecimiento'];
	$opcionReporte = $_POST['opcionReporte'];
	$ruc = $_POST['ruc'];
	
	$tipoSaldo = 'saldoVue';
	
	$res = $cf -> obtenerIdentificadorPorEstadoSriTipoProceoNumeroEstablecimiente($conexion, $establecimiento, $fechaInicio, $fechaFin, $tipoSaldo, $ruc);
	
?>

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Razón social</th>
			<th>Fecha</th>
			<th>Saldo disponible</th>
		</tr>
	</thead>

	<?php 
	$contador = 0;
	$var = 0;

		while($fila = pg_fetch_assoc($res)){

			$qSaldo = $cf->obtenerMaxSaldoPorIdentificadorFechas($conexion, $fila['identificador_operador'], 'saldoVue', $fechaInicio, $fechaFin);
			
			if(pg_num_rows($qSaldo)== 0){
				$fecha = 'No disponible.';
				$saldo = 'No disponible.';
			}else{
				$saldo = pg_fetch_assoc($qSaldo);
				$fecha = date('d/m/Y G:i',strtotime($saldo['fecha_deposito']));
				$saldo = number_format($saldo['saldo_disponible'],2,',','.');
			}
		
				echo '<tr
						id="'.$fila['identificador_operador'].'"
						data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td>'.$fila['identificador_operador'].'</td>
						<td>'.$fila['razon_social'].'</td>
						<td>'.$fecha.'</td>
						<td>'.$saldo.'</td>
						</tr>';
		}
	?>
	
</table>

<?php 

switch ($opcionReporte){
	case '16':

		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteXSaldo.php" target="_blank" method="post">
		
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="provincia" value="'.$provincia.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>	
					
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>

		</form>';

	break;

}

?>

<script type="text/javascript"> 

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");	
	});

</script>
