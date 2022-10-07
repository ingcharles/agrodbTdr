<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=Reporte_Matriz_Pap.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$fecha = getdate();

$conexion = new Conexion();
$cd = new ControladorPAPP();

    $completo =$cd->sacarReporteMatrizPOA($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['fi'],$_POST['ff'],$_POST['codigo_Indicador'],$_POST['listaCobertura'],$_POST['listaPoblacion'],$_POST['ListaResponsable'],$_POST['listaVerificacion'], $fecha['year']);
	$presupuesto = $cd->sacarReporteActividadesPresupuesto($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'], $fecha['year']);
	$actividades = $cd->sacarReporteActividades($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'], $fecha['year']);
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
			<!-- th>objetivo operativo</th-->
			<th>actividades</th>
			<!-- th>indicador</th>
			<th>meta total</th>
			<th>meta trimestral I</th>
			<th>meta trimestral II</th>
			<th>meta trimestral III</th>
			<th>meta trimestral IV</th-->
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
	 
	 $t_prog1=0;
	 $t_prog2=0;
	 $t_prog3=0;
	 $t_prog4=0;
	 $t_beneficiados=0;
	 
	 //Matriz completa
	 while($fila = pg_fetch_assoc($completo)){
	 	
	 	$datosPresupuesto = $cd->obtenerPresupuestoTrimestral($conexion, $fila['id_item_planta']);
	 	$fila2 = pg_fetch_assoc($datosPresupuesto);
	 	 
	 	$t_prog1+=$fila2['trim1'];
	 	$t_prog2+=$fila2['trim2'];
	 	$t_prog3+=$fila2['trim3'];
	 	$t_prog4+=$fila2['trim4'];
	 	
		 /*$t_prog1+=$fila['programacion1'];
		 $t_prog2+=$fila['programacion2'];
		 $t_prog3+=$fila['programacion3'];
		 $t_prog4+=$fila['programacion4'];*/
		 $t_beneficiados+=$fila['numero_beneficiados'];
		 
	 	echo '<tr>
	    <td>'.$fila['nombre'].'</td>
		<td>'.$fila['objetivo'].'</td>
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <td>'.$fila['actividad'].'</td>
    	<td>'.$fila2['trim1'].'</td>
        <td>'.$fila2['trim2'].'</td>
        <td>'.$fila2['trim3'].'</td>
        <td>'.$fila2['trim4'].'</td>
        <td>'.($fila2['trim1']+$fila2['trim2']+$fila2['trim3']+$fila2['trim4']).'</td>
        <td>'.$fila['cobertura'].'</td>
        <td>'.$fila['numero_beneficiados'].'</td>
		<td>'.$fila['poblacion'].'</td>
		<td>'.$fila['responsable'].'</td>
		<td>'.$fila['medios_verificacion'].'</td>
		</tr>';
	 	
	 	/*echo '<tr>
	    <td>'.$fila['nombre'].'</td>
		<td>'.$fila['objetivo'].'</td>
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <td>'.$fila['componente'].'</td>
    	<td>'.$fila['actividad'].'</td>
    	<td>'.$fila['indicador'].'</td>
        <td>'.($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']).'</td>
        <td>'.$fila['meta1'].'</td>
    	<td>'.$fila['meta2'].'</td>
    	<td>'.$fila['meta3'].'</td>
        <td>'.$fila['meta4'].'</td>
        <td>'.$fila2['trim1'].'</td>
        <td>'.$fila2['trim2'].'</td>
        <td>'.$fila2['trim3'].'</td>
        <td>'.$fila2['trim4'].'</td>
        <td>'.($fila2['trim1']+$fila2['trim2']+$fila2['trim3']+$fila2['trim4']).'</td>
        <td>'.$fila['cobertura'].'</td>
        <td>'.$fila['numero_beneficiados'].'</td>
		<td>'.$fila['poblacion'].'</td>
		<td>'.$fila['responsable'].'</td>
		<td>'.$fila['medios_verificacion'].'</td>
		</tr>';*/
	 }
		
	 //Matriz presupuesto
	 while($fila = pg_fetch_assoc($presupuesto)){
	 	
	 	$datosPresupuesto = $cd->obtenerPresupuestoTrimestral($conexion, $fila['id_item']);
	 	$fila3 = pg_fetch_assoc($datosPresupuesto);
	 	
	 	$t_prog1+=$fila3['trim1'];
	 	$t_prog2+=$fila3['trim2'];
	 	$t_prog3+=$fila3['trim3'];
	 	$t_prog4+=$fila3['trim4'];
	 	
	 	echo '<tr>
	 	<td>'.$fila['area'].'</td>
	 	<td>'.$fila['objetivo'].'</td>
	 	<td>'.$fila['proceso'].'</td>
	 	<td>'.$fila['subproceso'].'</td>
	 	<td>'.$fila['actividad'].'</td>
	 	<td>'.$fila3['trim1'].'</td>
	 	<td>'.$fila3['trim2'].'</td>
	 	<td>'.$fila3['trim3'].'</td>
	 	<td>'.$fila3['trim4'].'</td>
	 	<td>'.($fila3['trim1']+$fila3['trim2']+$fila3['trim3']+$fila3['trim4']).'</td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	</tr>';
	 	
	 	/*echo '<tr>
	 	<td>'.$fila['area'].'</td>
	 	<td>'.$fila['objetivo'].'</td>
	 	<td>'.$fila['proceso'].'</td>
	 	<td>'.$fila['subproceso'].'</td>
	 	<td>'.$fila['componente'].'</td>
	 	<td>'.$fila['actividad'].'</td>
	 	<td>'.$fila['indicador'].'</td>
	 	<td>'.($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']).'</td>
	 	<td>'.$fila['meta1'].'</td>
	 	<td>'.$fila['meta2'].'</td>
	 	<td>'.$fila['meta3'].'</td>
	 	<td>'.$fila['meta4'].'</td>
	 	<td>'.$fila3['trim1'].'</td>
	 	<td>'.$fila3['trim2'].'</td>
	 	<td>'.$fila3['trim3'].'</td>
	 	<td>'.$fila3['trim4'].'</td>
	 	<td>'.($fila3['trim1']+$fila3['trim2']+$fila3['trim3']+$fila3['trim4']).'</td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	</tr>';*/
	 }

	 //Matriz Actividades
	 while($fila = pg_fetch_assoc($actividades)){
	 	echo '<tr>
	 	<td>'.$fila['area'].'</td>
	 	<td>'.$fila['objetivo'].'</td>
	 	<td>'.$fila['proceso'].'</td>
	 	<td>'.$fila['subproceso'].'</td>
	 	<td>'.$fila['actividad'].'</td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	</tr>';
	 	
	 	/*echo '<tr>
	 	<td>'.$fila['area'].'</td>
	 	<td>'.$fila['objetivo'].'</td>
	 	<td>'.$fila['proceso'].'</td>
	 	<td>'.$fila['subproceso'].'</td>
	 	<td>'.$fila['componente'].'</td>
	 	<td>'.$fila['actividad'].'</td>
	 	<td>'.$fila['indicador'].'</td>
	 	<td>'.($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']).'</td>
	 	<td>'.$fila['meta1'].'</td>
	 	<td>'.$fila['meta2'].'</td>
	 	<td>'.$fila['meta3'].'</td>
	 	<td>'.$fila['meta4'].'</td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	</tr>';*/
	 }
	 
	 //colspan=12
	 echo '<tr>
	 <td colspan="5">Total</td>	 
	 <td>'.$t_prog1.'</td>
	 <td>'.$t_prog2.'</td>
	 <td>'.$t_prog3.'</td>
	 <td>'.$t_prog4.'</td>
	 <td>'.($t_prog1+$t_prog2+$t_prog3+$t_prog4).'</td>	 
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