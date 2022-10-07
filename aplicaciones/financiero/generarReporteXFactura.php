<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTEXFACTURA.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	
	$comprobante = $_POST['comprobante'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$provincia = $_POST['provincia'];
	$ruc = $_POST['ruc'];
	
	$res = $cc -> filtrarRecaudacionPorFactura($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc);

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
			<th>Número factura</th>
			<th>Estado SRI</th>
			<th>Area</th>
			<th>Código</th>
			<th>Servicio</th>
			<th>Cantidad</th>
			<th>Precio unitario</th>
			<th>Descuento</th>
			<th>Iva</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 While($fila = pg_fetch_assoc($res)) {
		
		echo '<tr>
				<td class="formato">'.$fila['identificador_operador'].'</td>
				<td>'.$fila['razon_social'].'</td>
				<td class="formato">'.$fila['numero_factura'].'</td>
		       	<td>'.$fila['estado_sri'].'</td>
		       	<td>'.$fila['nombre'].'</td>
		       	<td class="formato">'.$fila['codigo'].'</td>
		        <td>'.$fila['concepto_orden'].'</td>
		       	<td>'.$fila['cantidad'].'</td>
		        <td>'.$fila['precio_unitario'].'</td>
		        <td>'.$fila['descuento'].'</td>
		        <td>'.$fila['iva'].'</td>
		        <td>'.$fila['total'].'</td>
	       	 </tr>';
	 }
	 	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>



