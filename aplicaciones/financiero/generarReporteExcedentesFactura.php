<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
		
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTEEXCEDENTES.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
		
	$comprobante = $_POST['comprobante'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$provincia = $_POST['provincia'];
	$establecimiento = $_POST['establecimiento'];
	$opcionReporte = $_POST['opcionReporte'];
	$cliente = $_POST['cliente'];
	$ruc = $_POST['ruc'];
	
	if( $opcionReporte == 11 ){			
		$res = $cc -> listaOrdenPagoSaldo($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $valor=0, $cliente, $ruc); //provincia
	}else {
		$res = $cc -> listaOrdenPagoSaldo($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $valor=1, $cliente, $ruc); //punto de venta
	}
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
			<th>Cliente</th>
		    <th>Razón Social</th>
			<th>Número establecimiento</th>
			<th>Punto emisión</th>
			<th>Número factura</th>
			<th>Fecha facturación</th>			
		    <th>Total a pagar</th>		    
			<th>Banco</th>
			<th>Número transacción</th>
			<th>Valor depósito</th>
			<th>Valor ingreso</th>
			<th>Valor egreso</th>
			<th>Saldo disponible</th>
				
		</tr>
	</thead>
	<tbody>
    <?php
	 
		 $var = 0;
		 $auxPago = 0;
		 $aux1Pago = 0;
		 $auxColor = 'pintado';
		 $auxImpresion = 0;
		 
		 While($fila = pg_fetch_assoc($res)) {
	
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
			
			echo '<td class="formato">'.$fila['identificador_operador'].'</td>
				  <td>'.$fila['razon_social'].'</td>
				  <td class="formato">'.$fila['numero_establecimiento'].'</td>
				  <td class="formato">'.$fila['punto_emision'].'</td>
				  <td class="formato">'.$fila['numero_factura'].'</td>
			 	  <td>'.$fila['fecha_facturacion'].'</td>';
				  if($auxImpresion == 0){
	        			echo ' <td class="formatoNumero">'.number_format($fila['total_pagar'],6,',','.').'</td>';
	        				$auxImpresion = 1;
	        		}else{
	        			echo ' <td class="formatoNumero">0</td>';
	        		}
			echo'<td class="formato">'.$fila['institucion_bancaria'].'</td>
				 <td class="formato">'.$fila['transaccion'].'</td>
				 <td class="formatoNumero">'.number_format($fila['valor_deposito'],6,',','.').'</td> 
				 <td class="formatoNumero">'.number_format($fila['valor_ingreso'],6,',','.').'</td>
	   			 <td class="formatoNumero">'.number_format($fila['valor_egreso'],6,',','.').'</td>
			     <td class="formatoNumero">'.number_format($fila['saldo_disponible'],6,',','.').'</td>
	    		</tr>';
		 }
    ?>

	</tbody>
</table>

</div>
</body>
</html>



