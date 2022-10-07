<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/Constantes.php';

try {

$conexion = new Conexion();
$cd = new ControladorCatastro();
$constg = new Constantes();

$res =$cd->reporteContratosConsolidado($conexion, $_POST['regimen_laboral'], $_POST['provincia'], $_POST['canton'], 
		$_POST['oficina'], $_POST['coordinacion'], $_POST['direccion'], $_POST['gestion'], $_POST['estado'], 
		$_POST['fechaInicio'], $_POST['fechaFin']);
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
	<div id="textoPOA"><?php echo $constg::NOMBRE_INSTITUCION;?><br> 
	</div>
	<div id="direccionFisica"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteExcelContratos.php" target="_blank" method="post">
		 <input type="hidden" id="regimen_laboral" name="regimen_laboral" value="<?php echo $_POST['regimen_laboral'];?>" />
		 <input type="hidden" id="tipo_contrato" name="tipo_contrato" value="<?php echo $_POST['tipo_contrato'];?>" />
		 <input type="hidden" id="presupuesto" name="presupuesto" value="<?php echo $_POST['presupuesto'];?>" />
		 <input type="hidden" id="fuente" name="fuente" value="<?php echo $_POST['fuente'];?>" />
		 <input type="hidden" id="puesto" name="puesto" value="<?php echo $_POST['puesto'];?>" />
		 <input type="hidden" id="grupo" name="grupo" value="<?php echo $_POST['grupo'];?>" />
		 <input type="hidden" id="remuneracion" name="remuneracion" value="<?php echo $_POST['remuneracion'];?>" />
		 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
		 <input type="hidden" id="canton" name="canton" value="<?php echo $_POST['canton'];?>" />
		 <input type="hidden" id="oficina" name="oficina" value="<?php echo $_POST['oficina'];?>" />
		 <input type="hidden" id="coordinacion" name="coordinacion" value="<?php echo $_POST['coordinacion'];?>" />
		 <input type="hidden" id="direccion" name="direccion" value="<?php echo $_POST['direccion'];?>" />
		 <input type="hidden" id="gestion" name="gestion" value="<?php echo $_POST['gestion'];?>" />
		 <input type="hidden" id="anio" name="anio" value="<?php echo $_POST['anio'];?>" />
		 <input type="hidden" id="mes" name="mes" value="<?php echo $_POST['mes'];?>" />
		 <input type="hidden" id="estado" name="estado" value="<?php echo $_POST['estado'];?>" />
		 <input type="hidden" id="fechaInicio" name="fechaInicio" value="<?php echo $_POST['fechaInicio'];?>" />
		 <input type="hidden" id="fechaFin" name="fechaFin" value="<?php echo $_POST['fechaFin'];?>" />
	 	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
</div>
<div id="tabla">
<table id="tablaReporteContratos" class="soloImpresion">
	<thead>
		<tr>
		    <th>Identificador</th>
		    <th>Nombre</th>
		    <th>Apellido</th>
			<th>Regimen laboral</th>
			<th>Tipo contrato</th>
			<th>No. contrato/acción de personal</th>
			<th>Estado</th>
			<th>Fecha Inicio</th>
			<th>Fecha Finalización</th>
			<th>Presupuesto</th>
			<th>Fuente</th>
			<th>Grupo ocupacional</th>
			<th>Grado</th>
			<th>Remuneración</th>
			<th>Provincia</th>
			<th>Cantón</th>
			<th>Oficina</th>			
			<th>Coordinación</th>
			<th>Dirección</th>
			<th>Gestión</th>
			<th>Puesto</th>
			<th>Motivo Salida</th>
			<th>Fecha Salida</th>
			<th>Nota</th>
			<th>Escala calificación</th>
			<th>Historial laboral IESS</th>
			<th>Declaración juramentada periódica</th>
			<th>Observación</th>
			
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
		$historial=$declaracion='';
		if($fila['estado']=="1"){
			$historial=$declaracion='No';
			if(pg_num_rows($cd->obtenerDatosHistorialLaboralIess ($conexion,$fila['identificador'],'Aceptado',''))){
				$historial='Si';
			 }
			if(pg_num_rows($cd->obtenerDatosDeclaracionJuramentada ($conexion,$fila['identificador'],'Aceptado',''))){
			 	$declaracion='Si';
			 }  
		 }
	 	echo '<tr>
	    <td>'.$fila['identificador'].'</td>
		<td>'.strtoupper($fila['nombre']).'</td>
		<td>'.strtoupper($fila['apellido']).'</td>
        <td>'.$fila['regimen_laboral'].'</td>
        <td>'.$fila['tipo_contrato'].'</td>
    	<td>'.$fila['numero_contrato'].'</td>
        <td>';
        
        if ($fila['estado']=="3")
        	echo "Finalizado";
		else if($fila['estado']=="1")
        	echo "Vigente";
		else if($fila['estado']=="4")
        	echo "Inactivo";
        else
        	echo "Caducado";
        echo '</td>
        <td>'.$fila['fecha_inicio'].'</td>
    	<td>'.$fila['fecha_fin'].'</td>
    	<td>'.$fila['presupuesto'].'</td>
        <td>'.$fila['fuente'].'</td>
        <td>'.$fila['grupo_ocupacional'].'</td>
        <td>'.$fila['grado'].'</td>
        <td>'.$fila['remuneracion'].'</td>
        <td>'.$fila['provincia'].'</td>
        <td>'.$fila['canton'].'</td>
        <td>'.$fila['oficina'].'</td>
        <td>'.$fila['coordinacion'].'</td>
        <td>'.$fila['direccion'].'</td>
        <td>'.$fila['gestion'].'</td>
        <td>'.$fila['nombre_puesto'].'</td>
		<td>'.$fila['motivo_terminacion_laboral'].'</td>
		<td>'.$fila['fecha_salida'].'</td>
		<td>'.$fila['nota'].'</td>
		<td>'.$fila['escala_calificacion'].'</td>
		<td>'.$historial.'</td>
		<td>'.$declaracion.'</td>
		<td>'.$fila['observacion'].'</td>
		</tr>';
        
        
	 }
	 $conexion->desconectar();
 } catch (Exception $ex) {
	$conexion->ejecutarLogsTryCatch($ex);
	 }	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>
