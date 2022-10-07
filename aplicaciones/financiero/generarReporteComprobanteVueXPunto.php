<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTECOMPROBANTEXPUNTO ". strtoupper($_POST['provincia']) .".xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$conexion = new Conexion();
	$cf = new ControladorFinanciero();
	
	$establecimiento = $_POST['establecimiento'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$provincia = $_POST['provincia'];
	$comprobante = $_POST['comprobante'];
	$ruc = $_POST['ruc'];
	
	$res = $cf->obtenerComprovantesVuePorFechas($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, 'grupal', $ruc);
	
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

.formatoNumero{
	mso-style-parent:style0;
	mso-number-format:"0.000000";
}

.colorCelda{
	background-color: #FFE699;
}

</style>


</head>
<body>

<div id="tabla">
<table id="tablaReporte" class="soloImpresion">
	<thead>
		<tr>
		    <th>Id</th>
		    <th>Cliente</th>
		    <th>Razón Social</th>
		    <th>Provincia de generación de orden</th>
			<th>Número establecimiento</th>
			<th>Punto emisión</th>
			<th># comprobante pago VUE</th>
			<th># orden pago VUE</th>
			<th>Observación</th>
			<th>Fecha creación orden de pago VUE</th>
			<th>Concepto</th>
			<th>Cantidad</th>
			<th>Precio unitario</th>
			<th>Descuento</th>
			<th>Iva</th>
			<th>Subtotal</th>
			<th>Total comprobante</th>
			<th>Forma de pago</th>
			<th>Valor descontado</th>
			<th>Fecha pago</th>
			<th>Saldo disponible</th>			
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 $var = 0;
	 $total = 0;
	 $cantidad = 0;
	 $precioUnitario = 0;
	 $descuento = 0;
	 $iva = 0;
	 $subtotal = 0;
	 $valorDepositado = 0;

	 $auxPago = 0;
	 $aux1Pago = 0;
	 $auxColor = 'pintado';
	 $auxImpresion = 0;
	 
	 /*$arrayFormaPago20 = array();
	 $arrayFormaPago17 = array();
	 $arrayFormaPago01 = array();*/
	 $controlIngresoFila = 0;
	 $codigo01 = true;
	 $codigo17 = true;
	 $codigo20 = true;
	
	 
	 While($fila = pg_fetch_assoc($res)) {
	 	
	 	$datoVacio = true;
	 	
	 	$aux1Pago = $auxPago;
	 	$auxPago = $fila['id_pago'];
	 	
	 	if($auxPago != $aux1Pago &&  $aux1Pago != 0){
	 		if($auxColor == 'pintado'){
	 			$auxColor = 'noPintado'; 				 			
	 		}else{
	 			$auxColor = 'pintado';
	 		}
	 		$auxImpresion = 0;
	 	}

		if($auxPago == 0 || $aux1Pago == 0){
			echo '<tr class= "colorCelda">';
		}else if($auxPago == $aux1Pago && $auxColor == 'pintado'){
			echo '<tr class= "colorCelda">';
		}else if($auxPago == $aux1Pago && $auxColor == 'noPintado'){
			echo '<tr>';
		}else if ($auxColor == 'pintado'){
			echo '<tr class= "colorCelda">';
		}else{
			echo '<tr>';
		}

		
						
        echo'
			<td >'.$fila['id_pago'].'</td>
			<td class="formato">'.$fila['identificador_operador'].'</td>
			<td>'.$fila['razon_social'].'</td>
			<td>'.$fila['provincia'].'</td>
			<td class="formato">'.$fila['numero_establecimiento'].'</td>
	       	<td class="formato">'.$fila['punto_emision'].'</td>
	        <td class="formato">'.$fila['numero_factura'].'</td>
			<td class="formato">'.$fila['numero_orden_vue'].'</td>
	        <td>'.$fila['observacion'].'</td>
			<td>'.date('d/m/Y G:i',strtotime($fila['fecha_orden_pago'])).'</td>        
	      	<td>'.$fila['concepto_orden'].'</td>
	        <td class="formatoNumero">'.number_format($fila['cantidad'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['precio_unitario'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['descuento'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['iva'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['total'],6,',','.').'</td>';
	        if($auxImpresion == 0){
	        	echo ' <td class="formatoNumero">'.number_format($fila['total_pagar'],6,',','.').'</td>';
	        	$auxImpresion = 1;
	        }else{
	        	echo ' <td class="formatoNumero">0</td>';
	        }
	        echo '<td>'.$fila['transaccion'].'</td>
	        <td class="formatoNumero">'.number_format($fila['valor_deposito'],6,',','.').'</td>
	 		<td>'.date('d/m/Y G:i',strtotime($fila['fecha_pago'])).'</td>
	 		<td class="formatoNumero">'.number_format($fila['saldo_disponible'],6,',','.').'</td>';
	        	 	
        
        if( $var != $fila['id_pago']){
        	$total += $fila['total_pagar'];
        	$var = $fila['id_pago'];
        }
        
        $cantidad += $fila['cantidad'];
        $precioUnitario += $fila['precio_unitario'];
        $descuento += $fila['descuento'];
        $iva += $fila['iva'];
        $subtotal += $fila['total'];
        $valorDepositado += $fila['valor_deposito'];
                
	 }
	 		 	 
	/* echo '<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="formatoNumero">'.number_format($cantidad,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($precioUnitario,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($descuento,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($iva,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($subtotal,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($total,6,',','.').'</td>
			<td></td>
			<td class="formatoNumero">'.number_format($valorDepositado,6,',','.').'</td>
			<td></td>
	        <td></td>
		</tr>';	 */
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>

</html>
