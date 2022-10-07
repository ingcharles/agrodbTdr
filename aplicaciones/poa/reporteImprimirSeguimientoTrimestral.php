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

$seguimiento = $cd->sacarReporteActividadesSeguimiento($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades']);
$sinSeguimiento = $cd->sacarReporteActividadesSinSeguimiento($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades']);
	
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
		    <th rowspan="2">estructura</th>
		    <th rowspan="2">objetivos estrategicos</th>
			<th rowspan="2">proceso</th>
			<th rowspan="2">subproceso</th>
			<th rowspan="2">objetivo operativo</th>
			<th rowspan="2">actividades</th>
			<th colspan="8">Trimestre I</th>
			<th colspan="8">Trimestre II</th>
			<th colspan="8">Trimestre III</th>
			<th colspan="8">Trimestre IV</th>
		</tr>
		<tr>
			<th>meta trimestral</th>
			<th>avance meta</th>
			<th>porcentaje avance</th>
			<th>número realizados</th>
			<th>número solicitados</th>
			<th>porcentaje cumplimiento</th>
			<th>observaciones</th>
			<th>estado</th>
			
			<th>meta trimestral</th>
			<th>avance meta</th>
			<th>porcentaje avance</th>
			<th>número realizados</th>
			<th>número solicitados</th>
			<th>porcentaje cumplimiento</th>
			<th>observaciones</th>
			<th>estado</th>
			
			<th>meta trimestral</th>
			<th>avance meta</th>
			<th>porcentaje avance</th>
			<th>número realizados</th>
			<th>número solicitados</th>
			<th>porcentaje cumplimiento</th>
			<th>observaciones</th>
			<th>estado</th>
			
			<th>meta trimestral</th>
			<th>avance meta</th>
			<th>porcentaje avance</th>
			<th>número realizados</th>
			<th>número solicitados</th>
			<th>porcentaje cumplimiento</th>
			<th>observaciones</th>
			<th>estado</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 //Matriz completa
	 while($fila = pg_fetch_assoc($seguimiento)){
	 	
	 	echo '<tr>
	    <td>'.$fila['area'].'</td>
		<td>'.$fila['objetivo'].'</td>
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <td>'.$fila['componente'].'</td>
    	<td>'.$fila['actividad'].'</td>
	 	<td>'.$fila['meta1'].'</td>';
        
	 	//Obtener datos de seguimiento trimestre 1
	 	$qSeguimientoT1 = $cd->listarSeguimientoXTrimestre($conexion, $fila['id_item'], 1);	

		if(pg_num_rows($qSeguimientoT1) > 0){
	        	$seguimientoT1 = pg_fetch_assoc($qSeguimientoT1);
	        	
		        echo   '<td>'.$seguimientoT1['avance_meta'].'</td>
				        <td>'.number_format($seguimientoT1['porcentaje_avance'],2).'</td>
				        <td>'.$seguimientoT1['items_realizados'].'</td>
				        <td>'.$seguimientoT1['items_solicitados'].'</td>
		        		<td>'.number_format($seguimientoT1['porcentaje_cumplimiento'],2).'</td>
				        <td>'.$seguimientoT1['observacion_metas'].'</td>
		        		<td>'.($seguimientoT1['estado']==1?'Creado':($seguimientoT1['estado']==2?'En Revisión Coordinador':($seguimientoT1['estado']==3?'En Revisión Planta Central':'Aprobado en Planta Central'))).'</td>';
	        }else{
	        	echo   '<td> - </td>
					 	<td> - </td>
					 	<td> - </td>
					 	<td> - </td>
	        			<td> - </td>
					 	<td> - </td>
					 	<td> - </td>
					 	<td> No registrado </td>';
	        }

        echo '<td>'.$fila['meta2'].'</td>';
		        
        //Obtener datos de seguimiento trimestre 2
        $qSeguimientoT2 = $cd->listarSeguimientoXTrimestre($conexion, $fila['id_item'], 2);
        
        if(pg_num_rows($qSeguimientoT2) > 0){
        	$seguimientoT2 = pg_fetch_assoc($qSeguimientoT2);
        	
	        echo   '<td>'.$seguimientoT2['avance_meta'].'</td>
			        <td>'.number_format($seguimientoT2['porcentaje_avance'],2).'</td>
			        <td>'.$seguimientoT2['items_realizados'].'</td>
			        <td>'.$seguimientoT2['items_solicitados'].'</td>
	        		<td>'.number_format($seguimientoT2['porcentaje_cumplimiento'],2).'</td>
			        <td>'.$seguimientoT2['observacion_metas'].'</td>
	        		<td>'.($seguimientoT2['estado']==1?'Creado':($seguimientoT2['estado']==2?'En Revisión Coordinador':($seguimientoT2['estado']==3?'En Revisión Planta Central':'Aprobado en Planta Central'))).'</td>';
        }else{
        	echo   '<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
        			<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
				 	<td> No registrado </td>';
        }
        
        echo '<td>'.$fila['meta3'].'</td>';
        //Obtener datos de seguimiento trimestre 3
        $qSeguimientoT3 = $cd->listarSeguimientoXTrimestre($conexion, $fila['id_item'], 3);
	 
	 	if(pg_num_rows($qSeguimientoT3) > 0){
        	$seguimientoT3 = pg_fetch_assoc($qSeguimientoT3);
        	
	        echo   '<td>'.$seguimientoT3['avance_meta'].'</td>
			        <td>'.number_format($seguimientoT3['porcentaje_avance'],2).'</td>
			        <td>'.$seguimientoT3['items_realizados'].'</td>
			        <td>'.$seguimientoT3['items_solicitados'].'</td>
	        		<td>'.number_format($seguimientoT3['porcentaje_cumplimiento'],2).'</td>
			        <td>'.$seguimientoT3['observacion_metas'].'</td>
	        		<td>'.($seguimientoT3['estado']==1?'Creado':($seguimientoT3['estado']==2?'En Revisión Coordinador':($seguimientoT3['estado']==3?'En Revisión Planta Central':'Aprobado en Planta Central'))).'</td>';
        }else{
        	echo   '<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
        			<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
				 	<td> No registrado </td>';
        }
        echo '<td>'.$fila['meta4'].'</td>';
        
        //Obtener datos de seguimiento trimestre 4
        $qSeguimientoT4 = $cd->listarSeguimientoXTrimestre($conexion, $fila['id_item'], 4);
        
        if(pg_num_rows($qSeguimientoT4) > 0){
        	$seguimientoT4 = pg_fetch_assoc($qSeguimientoT4);
        	 
        	echo   '<td>'.$seguimientoT4['avance_meta'].'</td>
			        <td>'.number_format($seguimientoT4['porcentaje_avance'],2).'</td>
			        <td>'.$seguimientoT4['items_realizados'].'</td>
			        <td>'.$seguimientoT4['items_solicitados'].'</td>
	        		<td>'.number_format($seguimientoT4['porcentaje_cumplimiento'],2).'</td>
			        <td>'.$seguimientoT4['observacion_metas'].'</td>
	        		<td>'.($seguimientoT4['estado']==1?'Creado':($seguimientoT4['estado']==2?'En Revisión Coordinador':($seguimientoT4['estado']==3?'En Revisión Planta Central':'Aprobado en Planta Central'))).'</td>';
        }else{
        	echo   '<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
        			<td> - </td>
				 	<td> - </td>
				 	<td> - </td>
				 	<td> No registrado </td>';
        }
        
        echo '</tr>';
	 }

	 //Matriz Actividades
	 while($fila = pg_fetch_assoc($sinSeguimiento)){
	 	
	 	echo '<tr>
		 	<td>'.$fila['area'].'</td>
		 	<td>'.$fila['objetivo'].'</td>
		 	<td>'.$fila['proceso'].'</td>
		 	<td>'.$fila['subproceso'].'</td>
		 	<td>'.$fila['componente'].'</td>
		 	<td>'.$fila['actividad'].'</td>
		 	<td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
	        <td> - </td>
			<td> - </td>
			<td> - </td>
			<td> No registrado </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
	        <td> - </td>
			<td> - </td>
			<td> - </td>
			<td> No registrado </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
	        <td> - </td>
			<td> - </td>
			<td> - </td>
			<td> No registrado </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
			<td> - </td>
	        <td> - </td>
			<td> - </td>
			<td> - </td>
			<td> No registrado </td>
	 	</tr>';
	 }
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>
