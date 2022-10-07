<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=REPORTE.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cd = new ControladorPAPP();

    $res =$cd->sacarReporteMatrizPOA($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['fi'],$_POST['ff'],$_POST['codigo_Indicador'],$_POST['listaCobertura'],$_POST['listaPoblacion'],$_POST['ListaResponsable'],$_POST['listaVerificacion']);
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">


#tablaReportePresupuesto 
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	width: 100%;
	margin: 0;
	padding: 0;
    border-collapse:collapse;
}

#tablaReportePresupuesto td, #tablaReportePresupuesto th 
{
font-size:1em;
border:1px solid #98bf21;
padding:1px 3px 1px 3px;
}

#tablaReportePresupuesto th 
{
font-size:1em;
text-align:left;
padding-top:3px;
padding-bottom:2px;
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



</style>
</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA">MINISTERIO DE AGRICULTURA, GANADERIA, ACUACULTURA Y PESCA<br>
	AGROCALIDAD PROYECTOS<br>
	MATRIZ DE PRESUPUESTO POR CLASIFICADOR DEL GASTO<br>
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>
<div id="tabla">
<table id="tablaReportePresupuesto" class="soloImpresion">
	<thead>
		<tr>
		    <th>estructura</th>
		    <th>objetivos estrategicos</th>
			<th>proceso</th>
			<th>subproceso</th>
			<th>objetivo operativo</th>
			<th>actividades</th>
			<th>meta total</th>
			<th>meta trimestral I</th>
			<th>meta trimestral II</th>
			<th>meta trimestral III</th>
			<th>meta trimestral IV</th>
			<th>presupuesto trimestral I</th>
			<th>presupuesto trimestral II</th>
			<th>presupuesto trimestral III</th>
			<th>presupuesto trimestral IV</th>
			<th>presupuesto total</th>
			<th>cobertura territorial</th>
			<th>no. beneficiarios</th>
			<th>poblacion objetivo</th>
			<th>responsable del subproceso</th>
			<th>medio de verificación</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	 $t_prog1+=$fila['programacion1'];
	 $t_prog2+=$fila['programacion2'];
	 $t_prog3+=$fila['programacion3'];
	 $t_prog4+=$fila['programacion4'];
	 $t_beneficiados+=$fila['numero_beneficiados'];
	 	echo '<tr>
	    <td>'.$fila['nombre'].'</td>
		<td>'.$fila['objetivo'].'</td>
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <td>'.$fila['componente'].'</td>
    	<td>'.$fila['actividad'].'</td>
        <td>'.($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']).'</td>
        <td>'.$fila['meta1'].'</td>
    	<td>'.$fila['meta2'].'</td>
    	<td>'.$fila['meta3'].'</td>
        <td>'.$fila['meta4'].'</td>
        <td>'.number_format($fila['programacion1'],2,',','.').'</td>
        <td>'.number_format($fila['programacion2'],2,',','.').'</td>
        <td>'.number_format($fila['programacion3'],2,',','.').'</td>
        <td>'.number_format($fila['programacion4']).'</td>
        <td>'.number_format(($fila['programacion1']+$fila['programacion2']+$fila['programacion3']+$fila['programacion4']),2,',','.').'</td>
        <td>'.$fila['cobertura'].'</td>
        <td>'.$fila['numero_beneficiados'].'</td>
		<td>'.$fila['poblacion'].'</td>
		<td>'.$fila['responsable'].'</td>
		<td>'.$fila['medios_verificacion'].'</td>
		</tr>';
	 }
	 echo '<tr>
		<td colspan="11"></td>
		<td>'.number_format($t_prog1,2,',','.').'</td>
		<td>'.number_format($t_prog2,2,',','.').'</td>
		<td>'.number_format($t_prog3,2,',','.').'</td>
		<td>'.number_format($t_prog4,2,',','.').'</td>
		<td>'.number_format($t_prog1+$t_prog2+$t_prog3+$t_prog4,2,',','.').'</td>
		<td></td>
		<td>'.$t_beneficiados.'</td>
		<td colspan="3"></td>
		</tr>';
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>