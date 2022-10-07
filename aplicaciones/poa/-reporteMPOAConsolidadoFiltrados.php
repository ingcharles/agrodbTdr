<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$conexion = new Conexion();
$cd = new ControladorPAPP();

    $completo =$cd->sacarReporteMatrizPOA($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['fi'],$_POST['ff'],$_POST['codigo_Indicador'],$_POST['listaCobertura'],$_POST['listaPoblacion'],$_POST['ListaResponsable'],$_POST['listaVerificacion']);
	$presupuesto = $cd->sacarReporteActividadesPresupuesto($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades']);
	$actividades = $cd->sacarReporteActividades($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades']);
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
	<div id="textoPOA">MINISTERIO DE AGRICULTURA, GANADERIA, ACUACULTURA Y PESCA<br>
	AGROCALIDAD PROYECTOS<br>
	PROGRAMACIÓN ANUAL DE LA POLÍTICA PÚBLICA<br>
	</div>
	<div id="direccion"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteImprimirPOAConsolidado.php" target="_blank" method="post">
	 <input type="hidden" id="areaDireccion" name="areaDireccion" value="<?php echo $_POST['areaDireccion'];?>" />
	 <input type="hidden" id="listaObjetivoEstrategico" name="listaObjetivoEstrategico" value="<?php echo $_POST['listaObjetivoEstrategico'];?>" />
	 <input type="hidden" id="listaProcesos" name="listaProcesos" value="<?php echo $_POST['listaProcesos'];?>" />
	 <input type="hidden" id="listaSubprocesos" name="listaSubprocesos" value="<?php echo $_POST['listaSubprocesos'];?>" />
	 <input type="hidden" id="listaComponentes" name="listaComponentes" value="<?php echo $_POST['listaComponentes'];?>" />
	 <input type="hidden" id="listaActividades" name="listaActividades" value="<?php echo $_POST['listaActividades'];?>" />
	 <input type="hidden" id="fi" name="fi" value="<?php echo $_POST['fi'];?>" />
	 <input type="hidden" id="ff" name="ff" value="<?php echo $_POST['ff'];?>" />
	 <input type="hidden" id="codigo_Indicador" name="codigo_Indicador" value="<?php echo $_POST['codigo_Indicador'];?>" />
	 <input type="hidden" id="listaCobertura" name="listaCobertura" value="<?php echo $_POST['listaCobertura'];?>" />
	 <input type="hidden" id="listaPoblacion" name="listaPoblacion" value="<?php echo $_POST['listaPoblacion'];?>" />
	 <input type="hidden" id="responsable" name="responsable" value="<?php echo $_POST['responsable'];?>" />
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
		    <th>estructura</th>
		    <th>objetivos estrategicos</th>
			<th>proceso</th>
			<th>subproceso</th>
			<th>objetivo operativo</th>
			<th>actividades</th>
			<th>indicador</th>
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
	 //Matriz completa
	 while($fila = pg_fetch_assoc($completo)){
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
    	<td>'.$fila['indicador'].'</td>
        <td>'.($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']).'</td>
        <td>'.$fila['meta1'].'</td>
    	<td>'.$fila['meta2'].'</td>
    	<td>'.$fila['meta3'].'</td>
        <td>'.$fila['meta4'].'</td>
        <td>'.number_format($fila['programacion1'], 2).'</td>
        <td>'.number_format($fila['programacion2'], 2).'</td>
        <td>'.number_format($fila['programacion3'], 2).'</td>
        <td>'.number_format($fila['programacion4'], 2).'</td>
        <td>'.number_format(($fila['programacion1']+$fila['programacion2']+$fila['programacion3']+$fila['programacion4']), 2).'</td>
        <td>'.$fila['cobertura'].'</td>
        <td>'.number_format($fila['numero_beneficiados'], 0).'</td>
		<td>'.$fila['poblacion'].'</td>
		<td>'.$fila['responsable'].'</td>
		<td>'.$fila['medios_verificacion'].'</td>
		</tr>';
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
	 	<td>'.$fila['componente'].'</td>
	 	<td>'.$fila['actividad'].'</td>
	 	<td>'.$fila['indicador'].'</td>
	 	<td>'.($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']).'</td>
	 	<td>'.$fila['meta1'].'</td>
	 	<td>'.$fila['meta2'].'</td>
	 	<td>'.$fila['meta3'].'</td>
	 	<td>'.$fila['meta4'].'</td>
	 	<td>'.number_format($fila3['trim1'], 2).'</td>
	 	<td>'.number_format($fila3['trim2'], 2).'</td>
	 	<td>'.number_format($fila3['trim3'], 2).'</td>
	 	<td>'.number_format($fila3['trim4'], 2).'</td>
	 	<td>'.number_format(($fila3['trim1']+$fila3['trim2']+$fila3['trim3']+$fila3['trim4']), 2).'</td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	<td> - </td>
	 	</tr>';
	 }

	 //Matriz Actividades
	 while($fila = pg_fetch_assoc($actividades)){
	 	echo '<tr>
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
	 	</tr>';
	 }
	 echo '<tr>
	 <td colspan="11"></td>
	 <td>'.number_format($t_prog1, 2).'</td>
	 <td>'.number_format($t_prog2, 2).'</td>
	 <td>'.number_format($t_prog3, 2).'</td>
	 <td>'.number_format($t_prog4, 2).'</td>
	 <td>'.number_format(($t_prog1+$t_prog2+$t_prog3+$t_prog4), 2).'</td>
	 <td></td>
	 <td>'.number_format($t_beneficiados, 0).'</td>
	 <td colspan="3"></td>
	 </tr>';
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>