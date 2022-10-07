<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = 'REPORTE_CATASTRO_'.$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename='".$nomReporte."'");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
echo $parroquia = htmlspecialchars ($_POST['nombreParroquia'],ENT_NOQUOTES,'UTF-8');
echo $canton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
echo $provincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');

if ($fechaInicio=="")
	$fechaInicio = 0;
if ($fechaFin=="")
	$fechaFin = 0;

set_time_limit(180);

$vdr = new ControladorVacunacionAnimal();
$res = $vdr->listaReporteSitiosProduccion($conexion, $parroquia,$canton,$provincia, $fechaInicio, $fechaFin );


?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteCatastros
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}
#tablaReporteCatastros td, #tablaReporteCatastros th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;

}
#tablaReporteCatastros th 
{
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;

}
#logoMagap{
width: 15%;
height:70px;
background-image: url(img/magap_logo.jpg); background-repeat: no-repeat;
float: left;
}
#logotexto{
width: 10%;
height:80px;
float: left;
}
#logoAgrocalidad{
width: 20%;
height:80px;
background-image: url(img/agrocalidad.png); background-repeat: no-repeat;
float:left;
}
#textoPOA{
width: 40%;
height:80px;
text-align: center;
float:left;
}
#direccion{
width: 10%;
height:80px;
background-image: url(img/direccion.png); background-repeat: no-repeat;
float: left;
}
#bandera{
width: 5%;
height:80px;
background-image: url(img/bandera.png); background-repeat: no-repeat;
float: right;
}
@page{
   margin: 5px;
}
</style>
</body>
</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA">MINISTERIO DE AGRICULTURA, GANADERIA, ACUACULTURA Y PESCA<br>
	AGROCALIDAD<br>
	CONTROL DE PESTE PORCINA CLASICA (PPC)<br>
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>

<div id="tabla">
<table id="tablaReporteCatastros" class="soloImpresion">
	<thead>
		<tr>
			<th>Código Área</th>
		    <th>Provincia</th>
		    <th>Cantón</th>
		    <th>Parroquia</th>
		    <th>Id Operador</th>
		    <th>Representante Operador</th> 
		    <th>Nombre Sitio</th>
		    <th>Dirección</th>
		    <th>Teléfono</th>
		    <th>Id Vacunador</th>
		    <th>Nombre Especie</th>
		    <th># Certificado</th>
		    <th># Existentes</th>
		    <th># Vacunados</th>
		    <th>Fecha Vacunación</th>
		    
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>
				<td style="mso-number-format:\@;" >'.$fila['codigo_catastral'].'</td>
				<td>'.$fila['provincia'].'</td>
				<td>'.$fila['canton'].'</td>
				<td>'.$fila['parroquia'].'</td>
				<td style="mso-number-format:\@;" >'.$fila['identificador_operador'].'</td>
				<td>'.$fila['representante'].'</td>
				<td>'.$fila['nombre_sitio'].'</td>
				<td>'.$fila['direccion'].'</td>
				<td>'.$fila['telefono'].'</td>
				<td style="mso-number-format:\@;" >'.$fila['identificador_vacunador'].'</td>
				<td>'.$fila['nombre_especie'].'</td>
				<td>'.$fila['num_certificado'].'</td>
				<td>'.$fila['total_existente'].'</td>
				<td>'.$fila['total_vacunado'].'</td>	
				<td>'.$fila['fecha_vacunacion'].'</td>
        	  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>