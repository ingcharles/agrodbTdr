<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
require_once '../../clases/ControladorAreas.php';

$fecha = getdate();

$conexion = new Conexion();
$cd = new ControladorPAPP();
$ca = new ControladorAreas();

if($_POST['coordinador'] == '1'){
    
    $qAreas = $ca->buscarAreasSubprocesos($conexion, $_POST['areaDireccion']);
    
    $areaBusqueda .= $_POST['areaDireccion']."'-'";
    
    while ($fila = pg_fetch_assoc($qAreas)){
        $areaBusqueda .= $fila['id_area']."'-'";
    }
    
    $areaBusqueda = rtrim($areaBusqueda,"-'");
    
    $_POST['areaDireccion'] = $areaBusqueda;
    
}else{
    //Administrador
    if($_POST['areaDireccion'] == 'Todos'){
        
        $qAreas = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Gestión','Unidad')", "(1,3,4,5)");
        
        while ($fila = pg_fetch_assoc($qAreas)){
            $areaBusqueda .= $fila['id_area']."-";
        }
        
        $areaBusqueda = rtrim($areaBusqueda,"-");
        
        $_POST['areaDireccion'] = $areaBusqueda;
    }else{
        $qAreas = $ca->buscarAreasSubprocesos($conexion, $_POST['areaDireccion']);
        
        $areaBusqueda .= $_POST['areaDireccion']."-";
        
        while ($fila = pg_fetch_assoc($qAreas)){
            $areaBusqueda .= $fila['id_area']."-";
        }
        
        $areaBusqueda = rtrim($areaBusqueda,"-");
        
        $_POST['areaDireccion'] = $areaBusqueda;
    }
}

$res =$cd->sacarReporteMatrizPresupuesto($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['fi'],$_POST['ff'],$_POST['codigo_Item'],$_POST['detalle_gasto'],$_POST['estadoFiltro'], $fecha['year']);
	
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
	<form id="filtrar" action="reporteImprimirPresupuesto.php" target="_blank" method="post">
	 <input type="hidden" id="areaDireccion" name="areaDireccion" value="<?php echo $_POST['areaDireccion'];?>" />
	 <input type="hidden" id="listaObjetivoEstrategico" name="listaObjetivoEstrategico" value="<?php echo $_POST['listaObjetivoEstrategico'];?>" />
	 <input type="hidden" id="listaProcesos" name="listaProcesos" value="<?php echo $_POST['listaProcesos'];?>" />
	 <input type="hidden" id="listaSubprocesos" name="listaSubprocesos" value="<?php echo $_POST['listaSubprocesos'];?>" />
	 <input type="hidden" id="listaComponentes" name="listaComponentes" value="<?php echo $_POST['listaComponentes'];?>" />
	 <input type="hidden" id="listaActividades" name="listaActividades" value="<?php echo $_POST['listaActividades'];?>" />
	 <input type="hidden" id="fi" name="fi" value="<?php echo $_POST['fi'];?>" />
	 <input type="hidden" id="ff" name="ff" value="<?php echo $_POST['ff'];?>" />
	 <input type="hidden" id="codigo_Item" name="codigo_Item" value="<?php echo $_POST['codigo_Item'];?>" />
	 <input type="hidden" id="detalle_gasto" name="detalle_gasto" value="<?php echo $_POST['detalle_gasto'];?>" />
	 <input type="hidden" id="estado" name="estado" value="<?php echo $_POST['estadoFiltro'];?>" />
	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
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
			<!--th rowspan="2">meta</th-->
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
	 	
	 	/*echo '<tr>
		<td>'.$fila['objetivo'].'</td>
		<td>'.$fila['nombre'].'</td>
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <td>'.$fila['actividad'].'</td>
        <td>'.$fila['total'].'</td>
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
        </tr>';*/
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
