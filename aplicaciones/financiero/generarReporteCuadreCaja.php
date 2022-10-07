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
		
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$establecimiento = $_POST['establecimiento'];
	$ruc = $_POST['ruc'];	

	$res = $cc -> filtrarRecaudacionCuadreCaja($conexion, $fechaInicio, $fechaFin, $establecimiento, $ruc);  //punto de venta
	
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
	mso-number-format:"0.00";
}

.colorCelda{
	background-color: #FFE699;
}

</style>


</head>
<body>

<div id="tablaCabecera">
<table>
<tr>
	<td style="font-weight: bold;">SUBPROCESO DE RECURSOS FINANCIEROS</td>
</tr>
<tr>
	<td style="font-weight: bold;">CUADRE DIARIO DE CAJA</td>
</tr>
<tr>
	<td style="font-weight: bold;">PUNTO DE FACTURACIÓN:</td><td> <?php echo $establecimiento.'-001'?></td>
</tr>
<tr>
	<td style="font-weight: bold;">FECHA DÍA DE FACTURACIÓN:</td><td> <?php echo date('d-m-Y')?></td>
</tr>
<tr>
</tr>
</table>
</div>

<div id="tabla">
<table id="tablaReporte" class="soloImpresion">
	<thead>
		<tr>
		    <th>Cliente CI/RUC</th>
		    <th>Razón Social</th>
		    <th>Provincia de generación de orden</th>
			<th>Oficina de generación de orden</th>
			<th>Lugar de finalización de orden</th>
			<th>Número factura</th>	
			<th>Estado SRI</th>
			<th>Fecha facturación</th>
			<th>Código del tarifario</th>
			<th>Cantidad</th>
			<th>Precio unitario</th>
			<th>Descuento</th>
			<th>Iva</th>
			<th>Total individual</th>
			<th>Total a pagar</th>
			<th>Banco</th>
			<th>Cuenta</th>
			<th>Número papeleta</th>
			<th>Valor depositado</th>
			<th>Fecha depósito</th>
			<th>Excedente</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 $var = 0;
	 $total = 0;
	 $individual = 0;
	 $valorDepositado = 0;
	 $excedente = 0;

	 $auxPago = 0;
	 $aux1Pago = 0;
	 $auxColor = 'pintado';
	 $auxImpresion = 0;
	 $auxExcedente = 0;
	 
	 While($fila = pg_fetch_assoc($res)) {
	 	
	 	$variableTabla = '';
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
			<td class="formato">'.$fila['identificador_operador'].'</td>
			<td>'.$fila['razon_social'].'</td>
			<td>'.$fila['nombre_provincia'].'</td>
		    <td>'.$fila['localizacion'].'</td>
			<td>'.$fila['oficina'].'</td>
			<td class="formato">'.$fila['numero_factura'].'</td>
			<td class="formato">'.$fila['estado_sri'].'</td>
			<td>'.$fila['fecha_facturacion'].'</td>
			<td class="formato">'.$fila['codigo'].'</td>
			<td class="formatoNumero">'.($fila['cantidad']==''?'':number_format($fila['cantidad'],2,',','.')).'</td>
			<td class="formatoNumero">'.($fila['precio_unitario']==''?'':number_format($fila['precio_unitario'],2,',','.')).'</td>
			<td class="formatoNumero">'.($fila['descuento']==''?'':number_format($fila['descuento'],2,',','.')).'</td>
			<td class="formatoNumero">'.($fila['iva']==''?'':number_format($fila['iva'],2,',','.')).'</td>
	        <td class="formatoNumero">'.($fila['total']==''?'':number_format($fila['total'],2,',','.')).'</td>';
	        if($auxImpresion == 0){
	            $variableTabla .= ' <td class="formatoNumero">'.($fila['total_pagar']==''?'':number_format($fila['total_pagar'],2,',','.')).'</td>';
	        	$auxImpresion = 1;
	        	$auxExcedente = 1;
	        }else{
	        	$variableTabla .= ' <td class="formatoNumero"></td>';
	        } 	        
	        $variableTabla .= '<td>'.$fila['nombre_banco'].'</td>
			<td class="formato">'.$fila['numero_cuenta_banco'].'</td>
			<td class="formato">'.$fila['numero_transaccion'].'</td>
			<td class="formatoNumero">'.($fila['valor_depositado']==''?'':number_format($fila['valor_depositado'],2,',','.')).'</td>
			<td>'.$fila['fecha_deposito'].'</td>';
	        if($auxExcedente == 1){
                $variableTabla .= ' <td class="formatoNumero">'.($fila['excedente']==''?'':number_format($fila['excedente'],2,',','.')).'</td>';
                $auxExcedente = 0;
	        }else{
	        	$variableTabla .= ' <td class="formatoNumero"></td>';
	        } 	        

        if( $var != $fila['id_pago']){
        	$total += $fila['total_pagar'];        	
        	$excedente += $fila['excedente'];
        	$var = $fila['id_pago'];
        }

        $individual += $fila['total'];
        $valorDepositado += $fila['valor_depositado'];

        echo  $variableTabla;
	 }
 	 
	 echo '<tr>
			<td colspan="13">SUMAN</td>
            <td class="formatoNumero">'.number_format($individual,2,',','.').'</td>
            <td class="formatoNumero">'.number_format($total,2,',','.').'</td>			
			<td colspan="3"></td>
			<td class="formatoNumero">'.number_format($valorDepositado,2,',','.').'</td>			
			<td></td>
			<td class="formatoNumero">'.number_format($excedente,2,',','.').'</td>			
		</tr>';	 
	 
	 ?>
	 

	</tbody>
</table>

</div>
</body>

</html>



