<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
header("Content-type: application/octet-stream");
$fecha = date("d-m-Y_H-i-s");
$ext   = ".xls";
$nomReporte = "REPORTE_CATASTRO_".$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();

$idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');

if ($fechaInicio=="")
	$fechaInicio = 0;
if ($fechaFin=="")
	$fechaFin = 0;


$vdr = new ControladorVacunacionAnimal();
$res = $vdr->listaReporteCatastros($conexion, $idSitio, $fechaInicio, $fechaFin);


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
		    <th>Concepto Catastro</th>
		    <th>Especie</th>
			<th>Producto</th>			
		    <th>Coeficiente</th> 
		    <th>Cant.Existentes</th> 
		    <th>Tot.Existentes</th> 
		    <th>Cant.Vacunados</th> 
		    <th>Tot.Vacunados</th>
		    <th>No.Certificado</th> 
		    <th>Operador</th>
		    <th>Provincia Origen</th>
		    <th>Canton Origen</th>
		    <th>Parroquia Origen</th>
		    <th>Sitio Origen</th>
		    <th>Área Origen</th> 
		    <th>Provincia Destino</th>
		    <th>Canton Destino</th>
		    <th>Parroquia Destino</th>
		    <th>Sitio Destino</th>
		    <th>Área Destino</th> 
		    <th>Fecha Catastro</th> 
		    <th>Fecha Movilizacion</th> 
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>
				<td>'.$fila['nombre_concepto'].'</td>
				<td>'.$fila['nombre_especie'].'</td>	
			    <td>'.$fila['nombre_comun'].'</td>
				<td>'.$fila['coeficiente'].'</td>
				<td>'.$fila['cantidad'].'</td>
				<td>'.$fila['total'].'</td>
				<td>'.$fila['cantidad_vacunado'].'</td>
				<td>'.$fila['total_vacunado'].'</td>
				<td>'.$fila['numero_documento'].'</td>
				<td>'.$fila['nombres'].'</td>
				<td>'.$fila['provincia'].'</td>
				<td>'.$fila['canton'].'</td>
				<td>'.$fila['parroquia'].'</td>	
				<td>'.$fila['sitio'].'</td>
		        <td>'.$fila['area'].'</td>
				<td>'.$fila['provincia_destino'].'</td>
				<td>'.$fila['canton_destino'].'</td>
				<td>'.$fila['parroquia_destino'].'</td>	
				<td>'.$fila['sitio_destino'].'</td>
		        <td>'.$fila['area_destino'].'</td>
		 		<td>'.$fila['fecha_catastro'].'</td>
				<td>'.$fila['fecha_movilizacion_desde'].'</td>
        	  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>