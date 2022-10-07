<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEtiquetas.php';
header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_ETIQUETAS_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$ce = new ControladorEtiquetas();
set_time_limit(240);


$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estadoH'],ENT_NOQUOTES,'UTF-8');
$identificacionOperador = htmlspecialchars ($_POST['identificacionOperador'],ENT_NOQUOTES,'UTF-8');


$res = $ce->imprimirReporteEtiquetas($conexion, $estado, $fechaInicio, $fechaFin,$identificacionOperador);

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporte{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}
#tablaReporte td, #tablaReporte th {
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#tablaReporte th {
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}

#textoEncabezado{
width: 40%;
height:80px;
text-align: center;
float:left;
}

</style>
</body>
</head>
<body>
	<div id="textoEncabezado">
	MINISTERIO DE AGRICULTURA, GANADERIA, ACUACULTURA Y PESCA<br>
	AGROCALIDAD<br>
	ETIQUETAS ORNAMENTALES<br>
	</div>
<div id="tabla">
<table id="tablaReporte" class="soloImpresion">
	<thead>
		<tr>
		 	<th>Número Solicitud </th>
		 	<th>Sitio</th>
			<th>Fecha Solicitud</th>
			<th>RUC</th>
			<th>Razón Social</th>
			<th>Saldo Etiquetas</th>
			<th>Cantidad Etiquetas</th>
		    <th>Serie Etiquetas</th>	    
		    <th>Estado</th>
		</tr>		
	</thead>
	<tbody>
	 <?php	
while ($registro = pg_fetch_assoc($res)) {
	echo '<tr>';
		echo '<td>'.$registro['numero_solicitud'].'</td>
			<td>'.$registro['nombre_sitio'].'</td>
			<td>'.$registro['fecha_registro_detalle'].'</td>
	 		<td>'.sprintf("&nbsp;%0s",$registro['identificador_operador']).'</td>
			<td>'.$registro['razon_social'].'</td>
			<td>'.$registro['saldo_etiqueta'].'</td>
			<td>'.$registro['cantidad_etiqueta'].'</td>
			<td>'.sprintf("&nbsp;%0s",$registro['etiqueta_detalle']).'</td>
			<td>'.$registro['estado'].'</td>';
	echo '</tr>';

}
	 ?>	
	</tbody>
</table>
</div>
</html>