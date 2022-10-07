<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
header("Content-type: application/octet-stream");

$fecha = date('d-m-Y H:i:s');
$ext   = '.xls';
$nomReporte = 'REPORTE CSMI '.$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename='".$nomReporte."'");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();
$empresa = htmlspecialchars ($_POST['operador'],ENT_NOQUOTES,'UTF-8');
$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');



//$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
//$autoservicio = htmlspecialchars ($_POST['autoservicio'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['cmbEstado'],ENT_NOQUOTES,'UTF-8');

if ($empresa=="")
	$empresa = 0;

if ($fechaInicio=="")
	$fechaInicio = 0;
if ($fechaFin=="")
	$fechaFin = 0;
if (($estado=="0") || ($estado=="1"))
	$estado = 0;


$res = $cm->listaReporteMovilizacionAnimal($conexion, $empresa,  $fechaInicio, $fechaFin, $estado);

//print_r($_POST);

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteVacunaAnimal
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}
#tablaReporteVacunaAnimal td, #tablaReporteVacunaAnimal th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#tablaReporteVacunaAnimal th 
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
	CERTIFICADO SANITARIO MOVILIZACION INTERNA (CSMI)<br>
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>

<div id="tabla">
<table id="tablaReporteVacunaAnimal" class="soloImpresion">
	<thead>
		<tr>
		    <th>Número certificado</th>
			<th>Lugar emisión</th>
			<th>Identificador emisor</th>
			<th>Nombre emisor</th>
			<th>Provincia origen</th>
		    <th>Lugar origen</th>
		    <th>Sitio origen</th>
		    <th>Área origen</th>
		    <th>Provincia destino</th>		    
		    <th>Lugar destino</th>
		    <th>Sitio destino</th>
		    <th>Área destino</th>
		    <th>Total movilizados</th>	
		    <th>Identificador autorizado</th>
		    <th>Medio transporte</th>		    
		    <th>Placa</th>
		    <th>Identificación conductor</th>
		    <th>Descripción transporte</th>	
		    <th>Estado</th>
		    <th>Observación</th>		   
		    <th>Fecha movilización desde</th>
		    <th>Fecha movilización hasta</th>
		    <th>Fecha registro</th>	
		    <th>Fecha anulación</th>
		    	    		  
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 
	 while($fila = pg_fetch_assoc($res)){		
	 	echo '<tr>
		 		 <td>'.$fila['numero_certificado'].'</td>
				 <td>'.$fila['lugar_emision'].'</td>
				 <td align="right">'.sprintf("&nbsp;%0s", $fila['identificador_emisor']).'</td>	
			     <td>'.$fila['nombre_emisor'].'</td>
				 <td>'.$fila['provincia_origen'].'</td>	
				 <td>'.$fila['lugar_movilizacion_origen'].'</td>		
		 		 <td>'.$fila['sitio_origen'].'</td>				 
				 <td>'.$fila['area_origen'].'</td>
				 <td>'.$fila['provincia_destino'].'</td>	
				 <td>'.$fila['lugar_movilizacion_destino'].'</td>
				 <td>'.$fila['sitio_destino'].'</td>
				 <td>'.$fila['area_destino'].'</td>
				 <td align="right">'.sprintf("%0d",$fila['total']).'</td>
				 <td align="right">'.sprintf("&nbsp;%0s",$fila['identificador_autorizado']).'</td>
				 <td>'.$fila['medio_transporte'].'</td>
				 <td>'.$fila['placa'].'</td>		
		 		 <td align="right">'.sprintf("&nbsp;%0s",$fila['identificacion_conductor']).'</td>
				 <td>'.$fila['descripcion_transporte'].'</td>	
				 <td>'.$fila['estado'].'</td>
				 <td>'.$fila['observacion'].'</td>			 
				 <td>'.$fila['fecha_movilizacion_desde'].'</td>		
		 		 <td>'.$fila['fecha_movilizacion_hasta'].'</td>
				 <td>'.$fila['fecha_registro'].'</td>
				 <td>'.$fila['fecha_anulacion'].'</td>
				 
        	  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>