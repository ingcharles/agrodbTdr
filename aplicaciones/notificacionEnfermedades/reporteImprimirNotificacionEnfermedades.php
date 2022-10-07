<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';
require_once '../../clases/ControladorAplicaciones.php';
	
header("Content-type: application/octet-stream");

$fecha = date('d-m-Y H:i:s');
$ext   = '.xls';
$nomReporte = 'REPORTE NEZ '.$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename='".$nomReporte."'");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cne = new ControladorNotificacionEnfermedades();

$animal = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
$tipoEnfermedad= htmlspecialchars ($_POST['tipoEnfermedad'],ENT_NOQUOTES,'UTF-8');
$enfermedad= htmlspecialchars ($_POST['enfermedad'],ENT_NOQUOTES,'UTF-8');

$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');

$identificador=$_SESSION['usuario'];

if ($fechaInicio=="")
	$fechaInicio = 0;
if ($fechaFin=="")
	$fechaFin = 0;


$res = $cne-> listarReporteNotificacionEnfermedades($conexion, $animal, $tipoEnfermedad, $enfermedad, $fechaInicio, $fechaFin,$identificador);

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
background-image: url(http://localhost/agrodb/aplicaciones/notificacionEnfermedades/img/logoAgro.png); background-repeat: no-repeat;
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
	<table class="soloImpresion">
	<thead>
		<tr>
		<th><img src="http://localhost/agrodb/aplicaciones/notificacionEnfermedades/img/logoAgro.png">
		</th><th>
		</th><th>
		</th><th>
		</th><th>
		</th><th>
		</th><th>
		</th><th>
		</th>
		<th></th>
		</tr>
		</thead>
		</table>
	<div id="textoPOA" style="font-size:16px; font-weight:bold;">MINISTERIO DE AGRICULTURA, GANADERIA, ACUACULTURA Y PESCA<br>
	AGROCALIDAD<br>
	REPORTE DE NOTIFICACIÓN DE ENFERMEDADES ZOONOSICAS DE PERROS Y GATOS (RNEZ)<br>
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>

	
		
		
<div id="tabla">
<table id="tablaReporteVacunaAnimal" class="soloImpresion">
	<thead>
		<tr>
		    <th>Id. Animal</th>
			<th>Animal</th>
			<th>Nombre Animal</th>
			<th>Nombre Enfermedad</th>
			<th>Tipo Enfermedad</th>
			<th>Fecha de Reporte</th>
		    <th>Area</th>
		    <th>Sitio</th>
		    <th>Id. Dueño</th>
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){		
	 	echo '<tr>
		 		 <td style="text-aling=right;">'.$fila['identificador_animal'].'</td>
				 <td>'.$fila['nombre_producto'].'</td>
				 <td>'.$fila['nombre_animal'].'</td>
			     <td>'.$fila['nombre_enfermedad'].'</td>
				 <td>'.$fila['nombre_tipo_enfermedad'].'</td>	
				 <td>'. date('d/m/Y', strtotime($fila['fecha_reporte'])).'</td>	
		 		 <td>'.$fila['nombre_area'].'</td>		
				 <td>'.$fila['nombre_lugar'].'</td>				 
				 <td>'.$fila['identificador_duenio'].'</td>
			  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>