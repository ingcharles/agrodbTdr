<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';
header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_MATERIAL_PELIGROSO_POR_LABORATORIO_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$so = new ControladorSeguridadOcupacional();

$res = $so->imprimirManejoMaterialPeligroso($conexion, $_POST['materialPeligroso'],$_POST['laboratorio']);

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteMaterialPeligrosoLaboratorio{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}
#tablaReporteMaterialPeligrosoLaboratorio td, #tablaReporteMaterialPeligrosoLaboratorio th {
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#tablaReporteMaterialPeligrosoLaboratorio th {
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
	MATERIALES PELIGROSOS POR LABORATORIO<br>
	</div>
<div id="tabla">
<table id="tablaReporteMaterialPeligrosoLaboratorio" class="soloImpresion">
	<thead>
		<tr>
		    <th>Coordinación Laboratorio</th>
		     <th>Laboratorio</th>
			<th>Químico</th>
			<th>Número UN</th>
			<th>Número CAS</th>
		    <th>Guía</th>
		    <th>Número guía</th>
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>
				<td>'.$fila['nombre_coordinacion_laboratorio'].'</td>
				<td>'.$fila['nombre_laboratorio'].'</td>
		 		<td>'.$fila['nombre_material_peligroso'].'</td>
				<td>'.$fila['numero_un_material_peligroso'].'</td>
				<td>'.$fila['numero_cas_material_peligroso'].'</td>
				<td>'.$fila['nombre_guia_material_peligroso'].'</td>
				<td>'.$fila['numero_guia_material_peligroso'].'</td>
        	  </tr>';
	 }
	 ?>	
	</tbody>
</table>
</div>
</html>