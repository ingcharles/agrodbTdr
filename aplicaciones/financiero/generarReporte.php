<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
		
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTE.xls");
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
	$ruc = $_POST['ruc'];
	
	if($opcionReporte == 8 ){
		$res = $cc -> filtrarRecaudacionPorPuntoEmision($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $ruc, $valor=1);  //punto de venta
	}else {
		$res = $cc -> filtrarRecaudacionPorPuntoEmision($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0);       //provincia
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
		    <th>Id</th>
		    <th>Cliente</th>
		    <th>Razón Social</th>
		    <th>Provincia de generación de orden</th>
			<th>Oficina de generación de orden</th>
			<th>Lugar de finalización de orden</th>
			<th>Número establecimiento</th>
			<th>Punto emisión</th>
			<th>Número factura</th>
			<th>Estado SRI</th>
			<th>Número autorización</th>
			<th>Observación</th>
			<th>Total a pagar</th>
			<th>Fecha facturación</th>
			<th>Fecha autorización</th>
			<th>Concepto</th>
			<th>Cantidad</th>
			<th>Precio unitario</th>
			<th>Descuento</th>
			<th>Iva</th>
			<th>Total individual</th>
			<th>Banco</th>
			<th>Cuenta</th>
			<th>Número transacción</th>
			<th>Valor depositado</th>
			<th>Fecha depósito</th>
			<th>Otros con utilización del sistema financiero (cheques, depósitos, transferencias).</th>
			<th>Dinero electrónico</th>
			<th>Sin utilización del sistema financiero (efectivo)</th>			
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
	 $arrayFormaPago20 = array();
	 $arrayFormaPago17 = array();
	 $arrayFormaPago01 = array();
	 $controlIngresoFila = 0;
	 $codigo01 = true;
	 $codigo17 = true;
	 $codigo20 = true;
	
	 
	 While($fila = pg_fetch_assoc($res)) {
	 	
	 	$datoVacio = true;
	 	$variableTabla = '';
	 	
	 	$aux1Pago = $auxPago;
	 	$auxPago = $fila['id_pago'];
	 	
	 	if($auxPago != $aux1Pago &&  $aux1Pago != 0){
	 		if($auxColor == 'pintado'){
	 			$auxColor = 'noPintado'; 			
	 			
	 			$numeroArray20 = (!$codigo20?1:0);
	 			
	 			for($i = $numeroArray20 ; $i<$controlIngresoFila; $i++){
	 				$arrayFormaPago20[] = '<td></td>';
	 			}
	 			
	 			$numeroArray17 = (!$codigo17?1:0);
	 				
	 			for($i = $numeroArray17 ; $i<$controlIngresoFila; $i++){
	 				$arrayFormaPago17[] = '<td></td>';
	 			}
	 			
	 			$numeroArray01 = (!$codigo01?1:0);
	 			
	 			for($i = $numeroArray01 ; $i<$controlIngresoFila; $i++){
	 				$arrayFormaPago01[] = '<td></td>';
	 			}	 			
	 			
	 			$controlIngresoFila = 0;
	 			$codigo01 = true;
	 			$codigo17 = true;
	 			$codigo20 = true;
	 			
	 		}else{
	 			$auxColor = 'pintado';
	 			
	 			$numeroArray20 = (!$codigo20?1:0);
	 			
	 			for($i = $numeroArray20 ; $i<$controlIngresoFila; $i++){
	 				$arrayFormaPago20[] = '<td></td>';
	 			}
	 			
	 			$numeroArray17 = (!$codigo17?1:0);
	 				
	 			for($i = $numeroArray17 ; $i<$controlIngresoFila; $i++){
	 				$arrayFormaPago17[] = '<td></td>';
	 			}
	 			
	 			$numeroArray01 = (!$codigo01?1:0);
	 			
	 			for($i = $numeroArray01 ; $i<$controlIngresoFila; $i++){
	 				$arrayFormaPago01[] = '<td></td>';
	 			}	 
	 			
	 			$controlIngresoFila = 0;
	 			$codigo01 = true;
	 			$codigo17 = true;
	 			$codigo20 = true;
	 		}
	 		$auxImpresion = 0;
	 	}

		if($auxPago == 0 || $aux1Pago == 0){
			$variableTabla .= '<tr class= "colorCelda">';
		}else if($auxPago == $aux1Pago && $auxColor == 'pintado'){
			$variableTabla .= '<tr class= "colorCelda">';
		}else if($auxPago == $aux1Pago && $auxColor == 'noPintado'){
			$variableTabla .= '<tr>';
		}else if ($auxColor == 'pintado'){
			$variableTabla .= '<tr class= "colorCelda">';
		}else{
			$variableTabla .= '<tr>';
		}

		
						
        $variableTabla .= '
			<td >'.$fila['id_pago'].'</td>
			<td class="formato">'.$fila['identificador_operador'].'</td>
			<td>'.$fila['razon_social'].'</td>
			<td>'.$fila['nombre_provincia'].'</td>
		    <td>'.$fila['localizacion'].'</td>
		    <td>'.($opcionReporte== 1 ? $fila['provincia']:$fila['oficina']).' </td>
			<td class="formato">'.$fila['numero_establecimiento'].'</td>
	       	<td class="formato">'.$fila['punto_emision'].'</td>
	        <td class="formato">'.$fila['numero_factura'].'</td>
			<td class="formato">'.$fila['estado_sri'].'</td>
	        <td class="formato">'.$fila['numero_autorizacion'].'</td>
	        <td>'.$fila['observacion'].'</td>';
        	if($auxImpresion == 0){
        		$variableTabla .= ' <td class="formatoNumero">'.number_format($fila['total_pagar'],6,',','.').'</td>';
        		$auxImpresion = 1;
        	}else{
        		$variableTabla .= ' <td class="formatoNumero">0</td>';
        	}      
	        
	        $variableTabla .= '<td>'.$fila['fecha_facturacion'].'</td>
	        <td>'.$fila['fecha_autorizacion'].'</td>
	        <td>'.$fila['concepto_orden'].'</td>
	        <td class="formatoNumero">'.number_format($fila['cantidad'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['precio_unitario'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['descuento'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['iva'],6,',','.').'</td>
	        <td class="formatoNumero">'.number_format($fila['total'],6,',','.').'</td>
	        <td>'.$fila['nombre_banco'].'</td>
			<td>'.$fila['numero_cuenta'].'</td>
	        <td class="formato">'.$fila['numero_transaccion'].'</td>
	        <td class="formatoNumero">'.number_format($fila['valor_depositado'],6,',','.').'</td>
	        <td>'.$fila['fecha_depositada'].'</td>';
	        	 	
        
        if( $var != $fila['id_pago']){
        	$total += $fila['total_pagar'];
        	$var = $fila['id_pago'];
        }
        
        $cantidad += $fila['cantidad'];
        $precioUnitario += $fila['precio_unitario'];
        $descuento += $fila['descuento'];
        $iva += $fila['iva'];
        $subtotal += $fila['total'];
        $valorDepositado += $fila['valor_depositado'];
        
        
        if($fila['numero_transaccion']=='Valor nota credito'){
        	
			$qtipo = $cc-> obtenerFormaPagoNotaCredito($conexion, $fila['id_pago']);
			
			$codigo = 0;
						
			while($tipo = pg_fetch_assoc($qtipo)) {
				
				switch ($tipo['transaccion']) {
										
					case '':
						$datoVacio = false;
					break;					
					case 'Efectivo':
						if($codigo01){
							$codigo = '01';
							$codigo01 = false;
						}
						
					break;						 
					case 'Dinero Electronico';
						if($codigo17){
							$codigo = '17';
							$codigo17 = false;
						}
					break;					
					case 'Saldo disponible':
						if($codigo20){
							$codigo = '20';
							$codigo20 = false;
						}
					break;					
					default:
						if($codigo20){
							$codigo = '20';
							$codigo20 = false;
						}
				}

				if($datoVacio){
					switch ($codigo){
						case '20':
							$arrayFormaPago20[] = '<td>'.$codigo.'</td>';
						break;
						case '17':
							$arrayFormaPago17[] = '<td>'.$codigo.'</td>';
						break;
						case '01':
							$arrayFormaPago01[] = '<td>'.$codigo.'</td>';
						break;
					}					
					$controlIngresoFila++;
				}else{
					$controlIngresoFila++;						
				}			

			}
			

        }else{

			$codigo = 0;
        	
			switch($fila['numero_transaccion']){
		
				case '':
					$datoVacio = false;
				break;						
				case 'Efectivo':
					if($codigo01){
						$codigo = '01';
						$codigo01 = false;
					}
				break;						
				case 'Dinero Electronico':
					if($codigo17){
						$codigo = '17';
						$codigo17 = false;
					}
				break;						
				case 'Saldo disponible':
					if($codigo20){
						$codigo = '20';
						$codigo20 = false;
					}
				break;				
				default:
					if($codigo20){
						$codigo = '20';
						$codigo20 = false;
					}
			}
			
			if($datoVacio){
				switch ($codigo){
					case '20':
						$arrayFormaPago20[] = '<td>'.$codigo.'</td>';
					break;
					case '17':
						$arrayFormaPago17[] = '<td>'.$codigo.'</td>';
					break;
					case '01':
						$arrayFormaPago01[] = '<td>'.$codigo.'</td>';
					break;
				}					
				$controlIngresoFila++;
			}else{
				$controlIngresoFila++;						
			}	
			
        }

        $arrayTabla[] = $variableTabla;
        
	 }
	 	
	 for ($p = 0; $p < count($arrayTabla); $p++){
	 	echo $arrayTabla[$p].$arrayFormaPago20[$p].$arrayFormaPago17[$p].$arrayFormaPago01[$p].'</tr>';
	 }
	 	 
	 echo '<tr>
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
			<td></td>
			<td class="formatoNumero">'.number_format($total,6,',','.').'</td>
			<td></td>
			<td></td>
			<td></td>
			<td class="formatoNumero">'.number_format($cantidad,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($precioUnitario,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($descuento,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($iva,6,',','.').'</td>
			<td class="formatoNumero">'.number_format($subtotal,6,',','.').'</td>
			<td></td>
			<td></td>
			<td class="formatoNumero">'.number_format($valorDepositado,6,',','.').'</td>
			<td></td>
		</tr>';	 
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>

</html>



