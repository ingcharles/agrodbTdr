<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	
	
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTEXBANCO.xls");
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
	
	$res = $cc -> filtrarRecaudacionPorBanco($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc);

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
			<th>Provincia</th>
			<th>Establecimiento</th>
			<th>Punto emisión</th>
			<th>Banco</th>
			<th>Transacción</th>
			<th>Valor Depositado</th>
			<th>Fecha orden</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 While($fila = pg_fetch_assoc($res)) {
		
		echo '<tr>
				<td class="formato">'.$fila['identificador_operador'].'</td>
				<td>'.$fila['razon_social'].'</td>
				<td class="formato">'.$fila['numero_factura'].'</td>
		       	<td>'.$fila['nombre_provincia'].'</td>
		       	<td class="formato">'.$fila['numero_establecimiento'].'</td>
		       	<td class="formato">'.$fila['punto_emision'].'</td>
		        <td>'.$fila['institucion_bancaria'].'</td>
		       	<td class="formato">'.$fila['transaccion'].'</td>
		        <td>'.$fila['valor_deposito'].'</td>
		        <td>'.$fila['fecha_orden_pago'].'</td>
	       	 </tr>';
	 }
	 	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>



