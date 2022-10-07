<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=Reporte_Matriz_Presupuesto.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$fecha = getdate();

$conexion = new Conexion();
$cd = new ControladorPAPP();

$res =$cd->sacarReporteMatrizPresupuesto($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['fi'],$_POST['ff'],$_POST['codigo_Item'],$_POST['detalle_gasto'],$_POST['estado'], $fecha['year']);
	
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

#logoMagap{
width: 15%;
height:70px;
background-image: url(imgPOA/magap_logo.jpg); background-repeat: no-repeat;
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
background-image: url(imgPOA/agrocalidad.png); background-repeat: no-repeat;
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
background-image: url(imgPOA/direccion.png); background-repeat: no-repeat;
float: left;
}

#bandera{
width: 5%;
height:80px;
background-image: url(imgPOA/bandera.png); background-repeat: no-repeat;
float: right;
}

@page{
   margin: 5px;
}

</style>
</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA">MINISTERIO DE AGRICULTURA Y GANADERIA<br>
	AGROCALIDAD - GASTO CORRIENTE<br>
	MATRIZ DE PROFORMA PRESUPUESTARIA ANUAL 2020<br>
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>

<div id="tabla">
<table id="tablaReportePresupuesto" class="soloImpresion">
	<thead>
		<tr>
		    <th rowspan="2">objetivos estrategicos</th>
		    <th rowspan="2">estructura</th>		    
		    <th rowspan="2">proceso</th>
			<th rowspan="2">subproceso</th>
			<th rowspan="2">actividad</th>
			<!-- th rowspan="2">meta</th-->
			<th rowspan="2">No. Item presupuestario</th>
			<th rowspan="2">nombre del item presupuestario</th>
			<th rowspan="2">detalle del gasto</th>
			<th colspan="12" align="center">Presupuesto mensual</th>
			<th rowspan="2">total gasto</th>
			<th rowspan="2">trimestre I</th>
			<th rowspan="2">trimestre II</th>
			<th rowspan="2">trimestre III</th>
			<th rowspan="2">trimestre IV</th>
			<th rowspan="2">estado</th>
		</tr>
		<tr>
		    <th>I</th>
			<th>II</th>
			<th>III</th>
			<th>IV</th>
			<th>V</th>
			<th>VI</th>
			<th>VII</th>
			<th>VIII</th>
			<th>IX</th>
			<th>X</th>
			<th>XI</th>
			<th>XII</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	 	
	 	$t_enero+=$fila['enero'];
        $t_febrero+=$fila['febrero'];
        $t_marzo+=$fila['marzo'];
        $t_abril+=$fila['abril'];
        $t_mayo+=$fila['mayo'];
        $t_junio+=$fila['junio'];
        $t_julio+=$fila['julio'];
        $t_agosto+=$fila['agosto'];
        $t_septiembre+=$fila['septiembre'];
        $t_octubre+=$fila['octubre'];
        $t_noviembre+=$fila['noviembre'];
        $t_diciembre+=$fila['diciembre'];
	 
	 	echo '<tr>
		<td>'.$fila['objetivo'].'</td>
        <td>'.$fila['nombre'].'</td>        
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <td>'.$fila['actividad'].'</td>
        <!--td>'.$fila['total'].'</td-->
    	<td>'.$fila['codigo_item'].'</td>
        <td>'.$fila['nombreitem'].'</td>
    	<td>'.$fila['detalle_gasto'].'</td>
    	<td>'.$fila['enero'].'</td>
        <td>'.$fila['febrero'].'</td>
        <td>'.$fila['marzo'].'</td>
        <td>'.$fila['abril'].'</td>
        <td>'.$fila['mayo'].'</td>
        <td>'.$fila['junio'].'</td>
        <td>'.$fila['julio'].'</td>
        <td>'.$fila['agosto'].'</td>
        <td>'.$fila['septiembre'].'</td>
		<td>'.$fila['octubre'].'</td>
		<td>'.$fila['noviembre'].'</td>
		<td>'.$fila['diciembre'].'</td>
		<td>'.($fila['enero']+$fila['febrero']+$fila['marzo']+$fila['abril']+$fila['mayo']+$fila['junio']+$fila['julio']+$fila['agosto']+$fila['septiembre']+$fila['octubre']+$fila['noviembre']+$fila['diciembre']).'</td>
		<td>'.($fila['enero']+$fila['febrero']+$fila['marzo']).'</td>
		<td>'.($fila['abril']+$fila['mayo']+$fila['junio']).'</td>
		<td>'.($fila['julio']+$fila['agosto']+$fila['septiembre']).'</td>
		<td>'.($fila['octubre']+$fila['noviembre']+$fila['diciembre']).'</td>
		<td>'.($fila['estado']==1?'Creado':($fila['estado']==2?'Revisión Coordinador':($fila['estado']==3?'Revisión Administrador':'Aprobado en Planta Central'))).'</td>
        </tr>';
	 }
	 echo '<tr>
		  <td colspan="8"></td>
		  <td>'.$t_enero.'</td>
		  <td>'.$t_febrero.'</td>
		  <td>'.$t_marzo.'</td>
		  <td>'.$t_abril.'</td>
		  <td>'.$t_mayo.'</td>
		  <td>'.$t_junio.'</td>
		  <td>'.$t_julio.'</td>
		  <td>'.$t_agosto.'</td>
		  <td>'.$t_septiembre.'</td>
		  <td>'.$t_octubre.'</td>
		  <td>'.$t_noviembre.'</td>
		  <td>'.$t_diciembre.'</td>	
		  <td>'.($t_enero+$t_febrero+$t_marzo+$t_abril+$t_mayo+$t_junio+$t_julio+$t_agosto+$t_septiembre+$t_octubre+$t_noviembre+$t_diciembre).'</td>
		  <td>'.($t_enero+$t_febrero+$t_marzo).'</td>
		  <td>'.($t_abril+$t_mayo+$t_junio).'</td>
		  <td>'.($t_julio+$t_agosto+$t_septiembre).'</td>
		  <td>'.($t_octubre+$t_noviembre+$t_diciembre).'</td>';
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>
