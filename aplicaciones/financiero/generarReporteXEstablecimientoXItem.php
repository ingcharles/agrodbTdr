<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTEXPUNTORECAUDACION-". strtoupper($_POST['provincia']) .".xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	
	$establecimiento = $_POST['establecimiento'];
	$area = $_POST['area'];
	$codigoItem = ($_POST['item'] == "") ? 'todos' : $_POST['item'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$provincia = $_POST['provincia'];
	$comprobante = $_POST['comprobante'];
	$ruc = $_POST['ruc'];
	
	$res = $cc -> filtrarRecaudacionXEstablecimientoXItem($conexion, $establecimiento, $area, $codigoItem, $fechaInicio, $fechaFin, $provincia, $ruc, $comprobante);
	
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
		    <th>Nombre de Provincia</th>
		    <th>Número de establecimiento</th>
		    <th>Punto de emisión</th>
		    <th>Código de servicio</th>
			<th>Nombre del servicio</th>
			<th>Precio unitario</th>	
		    <th># Items Facturados</th>			
			<th>Descuento</th>
			<th>IVA</th>
			<th>Subsidio</th>
			<th>Total</th>
					
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
	 
	 While($fila = pg_fetch_assoc($res)) {

		$aux1Pago = $auxPago;
		$auxPago = $fila['numero_establecimiento'];
		 
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
 
	 	echo '
			  	<td>'.$provincia/*$fila['nombre_provincia']*/.'</td>
				<td class="formato">'.$fila['numero_establecimiento'].'</td>
				<td class="formato">'.$fila['punto_emision'].'</td>
				<td class="formato">'.$fila['codigo'].'</td>
			    <td class="formato">'.$fila['concepto_orden'].'</td>
				<td class="formatoNumero">'.number_format($fila['precio_unitario'],4,',','.').'</td>
				<td class="formatoNumero">'.number_format($fila['cantidad'],4,',','.').'</td>
			    <td class="formatoNumero">'.number_format($fila['descuento'],4,',','.').' </td>
				<td class="formatoNumero">'.number_format($fila['iva'],4,',','.').'</td>
			    <td class="formatoNumero">'.number_format($fila['subsidio'],4,',','.').'</td>
			    <td class="formatoNumero">'.number_format($fila['total'],4,',','.').'</td>
			    </tr>';

		 	$cantidad += $fila['cantidad'];
		 	$descuento += $fila['descuento'];
		 	$iva += $fila['iva'];
		 	$subsido += $fila['subsidio'];
		 	$total += $fila['total'];
	 	
		}
		
		echo '<tr>
				<td colspan="6" style="text-align:center;">TOTAL</td>
				<td class="formatoNumero">'.number_format($cantidad,4,',','.').'</td>
				<td class="formatoNumero">'.number_format($descuento,4,',','.').'</td>
				<td class="formatoNumero">'.number_format($iva,4,',','.').'</td>
				<td class="formatoNumero">'.number_format($subsido,4,',','.').'</td>
				<td class="formatoNumero">'.number_format($total,4,',','.').'</td>
			</tr>';		 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>



