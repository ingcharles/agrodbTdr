<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$conexion = new Conexion();
$cd = new ControladorPAPP();

    $seguimiento = $cd->sacarReporteActividadesSeguimiento($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades']);
	$sinSeguimiento = $cd->sacarReporteActividadesSinSeguimiento($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades']);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="estilos/estiloapp.css" rel="stylesheet"></link>

</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA">MINISTERIO DE AGRICULTURA Y GANADERIA<br>
	AGROCALIDAD - GASTO CORRIENTE<br>
	PROFORMA PRESUPUESTARIA ANUAL 2020<br>
	</div>
	<div id="direccion"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteImprimirSeguimientoTrimestral.php" target="_blank" method="post">
	 <input type="hidden" id="areaDireccion" name="areaDireccion" value="<?php echo $_POST['areaDireccion'];?>" />
	 <input type="hidden" id="listaObjetivoEstrategico" name="listaObjetivoEstrategico" value="<?php echo $_POST['listaObjetivoEstrategico'];?>" />
	 <input type="hidden" id="listaProcesos" name="listaProcesos" value="<?php echo $_POST['listaProcesos'];?>" />
	 <input type="hidden" id="listaSubprocesos" name="listaSubprocesos" value="<?php echo $_POST['listaSubprocesos'];?>" />
	 <input type="hidden" id="listaComponentes" name="listaComponentes" value="<?php echo $_POST['listaComponentes'];?>" />
	 <input type="hidden" id="listaActividades" name="listaActividades" value="<?php echo $_POST['listaActividades'];?>" />
	 <input type="hidden" id="fi" name="fi" value="<?php echo $_POST['fi'];?>" />
	 <input type="hidden" id="ff" name="ff" value="<?php echo $_POST['ff'];?>" />
	 <input type="hidden" id="trimestre" name="trimestre" value="<?php echo $_POST['trimestre'];?>" />
	 <input type="hidden" id="listaVerificacion" name="listaVerificacion" value="<?php echo $_POST['id'];?>" />
	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
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
				        <td>'.number_format($seguimientoT1['porcentaje_avance'],2).'%</td>
				        <td>'.$seguimientoT1['items_realizados'].'</td>
				        <td>'.$seguimientoT1['items_solicitados'].'</td>
		        		<td>'.number_format($seguimientoT1['porcentaje_cumplimiento'],2).'%</td>
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
			        <td>'.number_format($seguimientoT2['porcentaje_avance'],2).'%</td>
			        <td>'.$seguimientoT2['items_realizados'].'</td>
			        <td>'.$seguimientoT2['items_solicitados'].'</td>
	        		<td>'.number_format($seguimientoT2['porcentaje_cumplimiento'],2).'%</td>
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
			        <td>'.number_format($seguimientoT3['porcentaje_avance'],2).'%</td>
			        <td>'.$seguimientoT3['items_realizados'].'</td>
			        <td>'.$seguimientoT3['items_solicitados'].'</td>
	        		<td>'.number_format($seguimientoT3['porcentaje_cumplimiento'],2).'%</td>
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
			        <td>'.number_format($seguimientoT4['porcentaje_avance'],2).'%</td>
			        <td>'.$seguimientoT4['items_realizados'].'</td>
			        <td>'.$seguimientoT4['items_solicitados'].'</td>
	        		<td>'.number_format($seguimientoT4['porcentaje_cumplimiento'],2).'%</td>
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
