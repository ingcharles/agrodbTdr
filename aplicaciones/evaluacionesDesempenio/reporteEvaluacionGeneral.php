<?php 
session_start();

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=REPORTE.xls");
//con esto evitamos que el navegador lo grabe en su caché
//header("Pragma: no-cache");
//header("Expires: 0");

$area = $_POST['area'];
$nombre = $_POST['nombre'];
$funcionario = $_POST['funcionario'];
$superior = $_POST['superior'];
$pares = $_POST['pares'];
$autoevaluacion = $_POST['autoevaluacion'];
$cumplimiento = $_POST['cumplimiento'];
$valorTotal = $_POST['valorTotal'];

?>


<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
 #tablaReportePresupuesto
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}

#tablaReportePresupuesto td, #tablaReportePresupuesto th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}

#tablaReportePresupuesto th 
{
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}


#logotexto{
width: 10%;
height:80px;
float: left;
}


#textoPOA{
width: 40%;
height:80px;
text-align: center;
float:left;
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
<table id="tablaReportePresupuesto" class="soloImpresion">
	<thead>
		<tr>
		    <th>Área</th>
		    <th>Nombres</th>
			<th>Cédula</th>
			<th>Jefes y suborninados</th>
			<th>Pares</th>
			<th>Autoevaluacion</th>
			<th>Cumplimiento</th>
			<th>Total</th>
			
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 for($i = 0 ; $i < count ($area); $i++) {
        echo '<tr>
			<td>'.$area[$i].'</td>
			<td>'.$nombre[$i].'</td>
			<td class="formato">'.$funcionario[$i].'</td>
	        <td>'.$superior[$i].'</td>
			<td>'.$pares[$i].'</td>
	        <td>'.$autoevaluacion[$i].'</td>
	        <td>'.$cumplimiento[$i].'</td>
	    	<td>'.$valorTotal[$i].'</td>
    	</tr>';
	 }
	 
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>


