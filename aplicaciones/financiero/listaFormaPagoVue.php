<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();
$cf = new ControladorFinanciero();

$numeroOrdenVue = $_POST['numeroOrdenVue'];

$contador = 0;

$res = $cc -> obtenerOrdenPagoPorNumeroOrdenVue($conexion, $numeroOrdenVue);
		
		echo '<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Identificador</th>
						<th>Razón social</th>
						<th>Fecha</th>
						<th>Total</th>
						<th># Orden</th>
					</tr>
				</thead>';
		
		while($fila = pg_fetch_assoc($res)){
		
			echo '<tr
					id="'.$fila['id_pago'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirFormaPagoVue"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.date('Y/m/d h:m',strtotime($fila['fecha_facturacion'])).'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td> '.$fila['numero_solicitud'].'</td>
					</tr>';
		}
		
		echo '</table>';
	

?>

<script type="text/javascript"> 

$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
});

</script>
