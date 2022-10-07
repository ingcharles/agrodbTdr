<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';

header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_INSPECCIONES_PROTOCOLO_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(3000);

$conexion = new Conexion();
$cp = new ControladorProtocolos();

$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['bFechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['bFechaFin'],ENT_NOQUOTES,'UTF-8');

$res = $cp->imprimirReporteInspeccionesProtocolo($conexion, $estado, $fechaInicio, $fechaFin);

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteInspeccionesProtocolo
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
display: inline-block;
width: auto;
margin: 0;
padding: 0;
border-collapse:collapse;
}
#tablaReporteInspeccionesProtocolo td, #tablaReporteInspeccionesProtocolo th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#tablaReporteInspeccionesProtocolo th 
{
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
	REPORTE DE INSECCIONES DE PROTOCOLO<br>
	AGROCALIDAD<br>
</div>

<div id="tabla">
<table id="tablaReporteInspeccionesProtocolo" class="soloImpresion">
	<thead>
		<tr>
			<th>Identificador</th>
			<th>Razón social</th>
			<th>Código de sitio</th>
			<th>Nombre de sitio</th>
			<th>Código de área</th>
			<th>Nombre de área</th>
		    <th>Protocolo</th>	    
		    <th>Estado</th>
		    <th>Fecha de modificación</th>
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>	
			     <td>'.sprintf("&nbsp;%0s",$fila['identificador']).'</td>
				<td>'.$fila['nombre_operador'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['codigo_sitio']).'</td>
                <td>'.$fila['nombre_lugar'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['codigo_area']).'</td>
                <td>'.$fila['nombre_area'].'</td>
				<td>'.$fila['nombre_protocolo'].'</td>
                <td>'.$fila['estado_protocolo_asignado'].'</td>
                <td>'.$fila['fecha_modificacion_protocolo_area_asignado'].'</td>
		</tr>';
	 }
	 ?>	
	</tbody>
</table>
</div>
</html>