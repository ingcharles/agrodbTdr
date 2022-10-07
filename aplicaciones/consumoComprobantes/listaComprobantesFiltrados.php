<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';
		
	$conexion = new Conexion();
	$cf = new ControladorFinanciero();
	
	$tipoDocumento = htmlspecialchars ($_POST['tipoBusquedaDocumento'],ENT_NOQUOTES,'UTF-8');
	$varDocumento = htmlspecialchars ($_POST['txtDocumentoBusqueda'],ENT_NOQUOTES,'UTF-8');
	$numeroEstablecimiento = htmlspecialchars ($_POST['numeroEstablecimiento'],ENT_NOQUOTES,'UTF-8');
	$puntoEmision = htmlspecialchars ($_POST['puntoEmision'],ENT_NOQUOTES,'UTF-8');
	$rucDistrito = htmlspecialchars ($_POST['rucDistrito'],ENT_NOQUOTES,'UTF-8');
	$numeroDocumento = str_pad($varDocumento, 9, "0", STR_PAD_LEFT);
	
	$datosFactura = $cf->listarComprobantesXdarBaja($conexion, $tipoDocumento, $numeroDocumento, $numeroEstablecimiento, $puntoEmision, $rucDistrito);
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Razón social</th>
			<th>Fecha</th>
			<th>Total</th>
			<th>Número de orden</th>
			<th>Estado</th>
		</tr>
	</thead>

<?php 
	$contador = 0;
	while($fila = pg_fetch_assoc($datosFactura)){
		echo '<tr 
				id="'.$fila['id_pago'].'"
				class="item"
				data-rutaAplicacion="consumoComprobantes"
				data-opcion="abrirFinalizarComprobante" 
				ondragstart="drag(event)" 
				draggable="true" 
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
				<td>'.$fila['razon_social'].'</td>
				<td>'.$fila['fecha_facturacion'].'</td>
				<td>'.$fila['total_pagar'].'</td>
				<td>'.$fila['numero_solicitud'].'</td>
				<td style="white-space:nowrap;">'.($fila['utilizado']=='t'?'Utilizado':'Pendiente').'</td>
			</tr>';
	}

?>			

</table>
</body>


<script type="text/javascript"> 

$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");	
});


</script>
