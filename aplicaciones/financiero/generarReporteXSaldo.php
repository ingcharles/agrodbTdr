<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';	
	
	//header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	//header("Content-Disposition: attachment; filename=REPORTESALDODISPONIBLE.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	//header("Pragma: no-cache");
	//header("Expires: 0");
	
	$conexion = new Conexion();
	$cf = new ControladorFinanciero();
	
	$establecimiento = $_POST['establecimiento'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$provincia = $_POST['provincia'];
	$ruc = $_POST['ruc'];
	
	$tipoSaldo = 'saldoVue';
	
	$res = $cf -> filtrarConsumoSaldoDisponible($conexion, $establecimiento, $fechaInicio, $fechaFin, $tipoSaldo, $ruc);
	
?>


<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<style type="text/css">
#tablaReporte
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}

#tablaReporte td, #tablaReporte th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}

#tablaReporte th 
{
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}


@page{
   margin: 5px;
}

.formato{
 mso-style-parent:style0;
 mso-number-format:"\@";
}

</style>


</head>
<body>

<div id="tabla">
<table id="tablaReporte" class="soloImpresion">
	<thead>
		<tr>
		    <th>Cliente</th>
		    <th>Razón Social</th>
		    <th>Número establecimiento</th>
		    <th>Púnto emisión</th>
			<th>Número factura</th>
			<th>Número comprobante pago VUE</th>
			<th>Orden de pago VUE</th>
			<th>Fecha facturación</th>
			<th>Concepto</th>
			<th>Monto depósito</th>
			<th>Total a pagar</th>
			<th>Ingreso</th>
			<th>Egreso</th>
			<th>Saldo disponible</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)) {

			
	
		if($fila['tipo_proceso'] == 'factura'){
			$numeroFactura = $fila['numero_factura'];
			$numeroComprobante = '';
			$motoDepositado = $fila['total'];
			$totalPagar = '';
		}else{
			$numeroFactura = '';
			$numeroComprobante = $fila['numero_factura'];
			$motoDepositado = '';
			$totalPagar = $fila['total'];
		}
	
		echo '<tr>
				<td class="formato">'.$fila['identificador_operador'].'</td>
				<td>'.$fila['razon_social'].'</td>	
				<td class="formato">'.$fila['numero_establecimiento'].'</td>
				<td class="formato">'.$fila['punto_emision'].'</td>	
				<td class="formato">'.$numeroFactura.'</td>
				<td class="formato">'.$numeroComprobante.'</td>
				<td>'.$fila['numero_orden_vue'].'</td>
				<td>'.date('d/m/Y G:i',strtotime($fila['fecha_facturacion'])).'</td>
				<td>'.$fila['concepto_orden'].'</td>
				<td>'.$motoDepositado.'</td>
				<td>'.$totalPagar.'</td>
				<td>'.$fila['valor_ingreso'].'</td>
				<td>'.$fila['valor_egreso'].'</td>
				<td>'.$fila['saldo_disponible'].'</td>
	       	 </tr>';
	 }
	 	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>