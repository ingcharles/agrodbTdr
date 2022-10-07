<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
		
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTENOTACREDITO.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	
	$comprobante = $_POST['comprobante'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$establecimiento = $_POST['establecimiento'];
	$opcionReporte = $_POST['opcionReporte'];
	$provincia = $_POST['provincia'];	
	$ruc = $_POST['ruc'];
	
	if( $opcionReporte == 7 || $opcionReporte == 9 )
	{													
		$res = $cc -> filtrarNotaCreditoPorPuntoEmision($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $ruc, $valor=1);  //punto de venta
	}else {
		$res = $cc -> filtrarNotaCreditoPorPuntoEmision($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc, $valor=0);       //provincia
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
			<th>Localización</th>
			<th>Provincia de finalización de orden</th>			
			<th>Punto emisión</th>
			<th>Número nota crédito</th>
			<th>Número establecimiento</th>		
			<th>Número factura</th>
			<th>Número autorización</th>
			<th>Total a pagar</th>
			<th>Fecha nota crédito</th>
			<th>Motivo</th>
			<th>Usuario</th>
			<th>Concepto</th>
			<th>Cantidad</th>
			<th>Precio unitario</th>
			<th>Descuento</th>
			<th>Iva</th>
			<th>Total</th>
					
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
	 	
	 	if( $var != $fila['identificador_operador']){
	 		
	 		$aux1Pago = $auxPago;
	 		$auxPago = $fila['id_nota_credito'];
	 		
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
	 		
	 			echo   '<td>'.$fila['id_nota_credito'].'</td>
						<td class="formato">'.$fila['identificador_operador'].'</td>
						<td>'.$fila['razon_social'].'</td>
						<td>'.$fila['nombre_provincia'].'</td>
				        <td class="formato">'.$fila['localizacion'].'</td>
				      	<td>'.($opcionReporte== 10 ? $fila['provincia']:$fila['oficina']).' </td>
						<td class="formato">'.$fila['punto_emision'].'</td>
						<td class="formato">'.$fila['numero_nota_credito'].'</td>
				       	<td class="formato">'.$fila['numero_establecimiento'].'</td>
				        <td class="formato">'.$fila['numero_factura'].'</td>
				        <td class="formato">'.$fila['numero_autorizacion'].'</td>
						<td class="formatoNumero">'.number_format($fila['total_pagar'],6,',','.').'</td>
				        <td>'.$fila['fecha_nota_credito'].'</td>
				        <td>'.$fila['motivo'].'</td>
				        <td class="formato">'.$fila['identificador_usuario'].'</td>
			    	 </td>';
				
				$detalleNCredito = $cc-> abrirDetalleNotaCredito($conexion, $fila['id_nota_credito']);
				$bandera = 0;
				
				foreach ($detalleNCredito as $detalleNotaCredito){
					if($bandera == 0){
					
						echo '<td>'.$detalleNotaCredito['concepto'].'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['cantidad'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['precioUnitario'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['descuento'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['iva'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['total'],6,',','.').'</td>
							  </tr>';
						$bandera = 1;
					}else{
						$aux1Pago = $auxPago;
						$auxPago = $detalleNotaCredito['idNC'];
							
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
						
						
						echo '<td colspan=15></td>
							  <td>'.$detalleNotaCredito['concepto'].'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['cantidad'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['precioUnitario'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['descuento'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['iva'],6,',','.').'</td>
							  <td class="formatoNumero">'.number_format($detalleNotaCredito['total'],6,',','.').'</td>';
						}	
					}
				}	 
	 		}
	 
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>



