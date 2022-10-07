<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();

$conexion = new Conexion();
$cd = new ControladorPAPP();

//$res =$cd->sacarReporteMatrizPOAEtapas($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['codigo_Indicador'],$_POST['estado'], $fecha['year']);
$res =$cd->sacarReporteMatrizPOAEtapas($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['codigo_Indicador'],$_POST['estadoReporte'], $fecha['year']);

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
	<form id="filtrar" action="reporteImprimirPOACoordinador.php" target="_blank" method="post">
	 <input type="hidden" id="areaDireccion" name="areaDireccion" value="<?php echo $_POST['areaDireccion'];?>" />
	 <input type="hidden" id="listaObjetivoEstrategico" name="listaObjetivoEstrategico" value="<?php echo $_POST['listaObjetivoEstrategico'];?>" />
	 <input type="hidden" id="listaProcesos" name="listaProcesos" value="<?php echo $_POST['listaProcesos'];?>" />
	 <input type="hidden" id="listaSubprocesos" name="listaSubprocesos" value="<?php echo $_POST['listaSubprocesos'];?>" />
	 <!-- input type="hidden" id="listaComponentes" name="listaComponentes" value="< ?php echo $_POST['listaComponentes'];?>" /-->
	 <input type="hidden" id="listaActividades" name="listaActividades" value="<?php echo $_POST['listaActividades'];?>" />
	 <!--input type="hidden" id="codigo_Indicador" name="codigo_Indicador" value="<?php echo $_POST['codigo_Indicador'];?>" /-->
	 <input type="hidden" id="estado" name="estado" value="<?php echo $_POST['estadoReporte'];?>" />
	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
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
			<!-- th>meta total</th>
			<th>meta trimestral I</th>
			<th>meta trimestral II</th>
			<th>meta trimestral III</th>
			<th>meta trimestral IV</th-->
			<th>estado</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	  	echo '<tr>
	    <td>'.$fila['nombre'].'</td>
		<td>'.$fila['objetivo'].'</td>
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <td>'.$fila['actividad'].'</td>
        <td>'.($fila['estado']==1?'Creado':($fila['estado']==2?'Revisión Coordinador':($fila['estado']==3?'Revisión Administrador':'Aprobado en Planta Central'))).'</td>
        </tr>';
	  	
	  	/*while($fila = pg_fetch_assoc($res)){
	  	    echo '<tr>
	    <td>'.$fila['nombre'].'</td>
		<td>'.$fila['objetivo'].'</td>
		<td>'.$fila['proceso'].'</td>
        <td>'.$fila['subproceso'].'</td>
        <!-- td>'.$fila['componente'].'</td-->
    	<td>'.$fila['actividad'].'</td>
        <!-- td>'.($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']).'</td>
        <td>'.$fila['meta1'].'</td>
    	<td>'.$fila['meta2'].'</td>
    	<td>'.$fila['meta3'].'</td>
        <td>'.$fila['meta4'].'</td-->
        <td>'.($fila['estado']==1?'Creado':($fila['estado']==2?'Revisión Coordinador':($fila['estado']==3?'Revisión Administrador':'Aprobado en Planta Central'))).'</td>
        </tr>';*/
	 }
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>
