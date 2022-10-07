<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
header("Content-type: application/octet-stream");
$fecha = date('d-m-Y H:i:s');
$ext   = '.xls';
$nomReporte = 'REPORTE VACUNACION '.$fecha.$ext;
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename='".$nomReporte."'");
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();
$res = $vdr->listaReporteVacunacionAnimal($conexion);

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
	CONTROL DE PESTE PORCINA CLASICA (PPC)<br>
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>

<div id="tabla">
<table id="tablaReporteVacunaAnimal" class="soloImpresion">
	<thead>
		<tr>
		    <th>Tipo Digitador</th>
			<th>Digitador</th>
			<th>No.Certificado</th>
			<th>Nombre propietario</th>
		    <th>Nombre sitio</th>
		    <th>Nombre area</th>		    
		    <th>Provincia</th>
		    <th>Cantón</th>
		    <th>Parroquia</th>
		    <th>Operador vacunador</th>
		    <th>Distribuidor</th>		    
		    <th>Vacunador</th>
		    <th>Tipo de vacuna</th>
		    <th>Laboratorio</th>
		    <th>Lote</th>		   		  
		    <th>No.vacunados</th>			
		    <th>costo</th>
		    <th>total</th>
		    <th>Fecha registro</th>
		    <th>Fecha vacunacion</th>
		    <th>Fecha vencimiento</th>		   
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>
				 <td>'.$fila['tipo_digitador'].'</td>	
			     <td>'.$fila['nombre_responsable'].'</td>
				 <td>'.$fila['num_certificado'].'</td>
				 <td>'.$fila['nombre_propietario'].'</td>	
				 <td>'.$fila['nombre_sitio'].'</td>		
		 		 <td>'.$fila['nombre_area'].'</td>				 
				 <td>'.$fila['provincia'].'</td>
				 <td>'.$fila['canton'].'</td>
				 <td>'.$fila['parroquia'].'</td>
				 <td>'.$fila['nombre_administrador'].'</td>
				 <td>'.$fila['nombre_distribuidor'].'</td>
				 <td>'.$fila['nombre_vacunador'].'</td>
				 <td>'.$fila['nombre_vacuna'].'</td>		
		 		 <td>'.$fila['nombre_laboratorio'].'</td>
				 <td>'.$fila['numero_lote'].'</td>
				 <td>'.$fila['total_vacunado'].'</td>		
				 <td>'.$fila['costo_vacuna'].'</td>
				 <td>'.$fila['total_vacuna'].'</td>
				 <td>'.$fila['fecha_registro'].'</td>		
		 		 <td>'.$fila['fecha_vacunacion'].'</td>
				 <td>'.$fila['fecha_vencimiento'].'</td>
        	  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>