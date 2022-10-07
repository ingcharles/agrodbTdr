<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion();;
$mes =  htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
$anio =  htmlspecialchars ($_POST['elementos'],ENT_NOQUOTES,'UTF-8');
$csl = new ControladorServiciosLinea();
?>
<header>
<h1>Información de Pagos</h1>
<br>
<?php 

	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	echo '<fieldset><legend><label >'.$meses[$mes-1].' de '.$anio.' </label></legend>';
	$qMatrizMEFyCYDRRHH=$csl->buscarDetalleConfirmacionPagoUsuario($conexion, $mes, $anio,$_SESSION['usuario']);
	echo '<table class="tablaMatriz">
				<thead>
					<tr>
					<th>CUR</th>
					<th>Descripción</th>
					<th>Fecha</th>
					<th>Monto</th>
					<th>Banco</th>
					</tr>
				</thead>';
	while($fila=pg_fetch_assoc($qMatrizMEFyCYDRRHH)){
		echo '<tbody>
				<tr>
					<td>'.$fila['num_trans_cur'].' </td>
					<td>'.$fila['descripcion'].'</th>
					<td>'.$fila['fecha_pago'].'</td>
					<td>'.number_format($fila['monto_pago'],2,".","").'</td>
					<td>'.$fila['banco'].'</td>
				</tr>
			</tbody>';
	}
	echo '</table></fieldset>';
?>
</header>