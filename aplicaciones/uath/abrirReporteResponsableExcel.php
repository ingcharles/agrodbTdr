<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorVacaciones.php';


$conexion = new Conexion();
$cc = new ControladorCatastro();
$cv = new ControladorVacaciones();
$constg = new Constantes();
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
	
	<form id="filtrar" action="reporteExcelResponsable.php" target="_blank" method="post">
				 
		 <input type="hidden" id="responsable" name="responsable" value="<?php echo $_POST['responsable']; ?>"/>	
	     <input type="hidden" id="area" name="area" value="<?php echo $_POST['area']; ?>" /> 
	     
	     <input type="hidden" id="responsable" name="responsable" value="<?php echo $_POST['responsable']; ?>"/>	
	     <input type="hidden" id="identificador" name="identificador" value="<?php echo $_POST['identificador']; ?>"/>	
	     <input type="hidden" id="area" name="area" value="<?php echo $_POST['area']; ?>" /> 
	 	 
	 	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
</div>

<div id="tabla">
<table id="tablaReporteContratos" class="soloImpresion">
	<thead>
		<tr>	    
		    <th>Cédula</th>
		    <th>Apellidos</th>
			<th>Nombres</th>
		    <th>Dirreción Domiciliaria</th>
		    <th>Lugar de trabajo</th>
		    <th>Telf. Casa</th>
		    <th >Celular</th>
		    <th >Puesto</th>
		    <th >Designación</th>
		    <th >Correo Electrónico</th>
		    <th>Periódo de Gestión desde</th>
		    <th>Periódo de Gestión hasta</th>		    		    			
			
		</tr>
	</thead>
	<tbody>
	 <?php	
	 if($_POST['responsable']=='Titula' or $_POST['responsable']=='Subrogante')$respon='';
	  //$listaReporte = $cc->filtroObtenerFuncionarios($conexion, $_POST['identificador'], '', '', $respon, $_POST['area']);
	  try {
	  	
	  
	 $listaReporte = $cc->filtroObtenerEncargo($conexion, $_POST['identificador'], '', '', 'Aprobado', '','unico','');
	  
	 while($fila = pg_fetch_assoc($listaReporte)) {
	 if($_POST['responsable']=='Subrogante'){
				$estado="'activo','inactivo'";
				$fecha_inicio = pg_fetch_result($Reporte = $cc->obtenerSubrogacionesFuncionarios($conexion, $_POST['area'],$_POST['identificador'],$estado), 0, 'fecha_inicio');
				$fecha_fin = pg_fetch_result($Reporte = $cc->obtenerSubrogacionesFuncionarios($conexion, $area,$_POST['identificador'],$estado), 0, 'fecha_inicio');
			}else {
				$fecha_inicio = pg_fetch_result($cc->obtenerFechasResponsables($conexion, $_POST['area'], $_POST['identificador']), 0, 'fecha_inicio');
			}
			if($_POST['responsable']=='Titular')
				$fecha_inicio = pg_fetch_result($cc->obtenerFechasResponsablesPuestos($conexion, $_POST['area'], $_POST['identificador']), 0, 'fecha_inicio');
			
			if($fecha_inicio == '')
				$fecha_inicio = pg_fetch_result($cc->obtenerInformacionFuncionarioContratoActivo ($conexion, $_POST['identificador']), 0, 'fecha_inicio');
			
			$fecha_fin='';
			if($fecha_inicio!='')$fecha_fin='Actualidad';
			
	 	
	 	if($fila['area']==$_POST['area'] OR $fila['padre']= $_POST['area']){
	 		
//	 		$consulta=pg_fetch_assoc($cc->obtenerInformacionFuncionarioContratoActivo ($conexion, $_POST['identificador']));
	 	$consulta=pg_fetch_assoc($cc->filtroObtenerDatosFuncionario($conexion, $_POST['identificador']));
	 		//$consulta2=pg_fetch_assoc($cc->filtroObtenerNombreArea($conexion, $fila['padre']));
	 	$fecha_inicio=date('Y-m-d', strtotime($fecha_inicio)); 
	 	echo '<tr>
	    <td>'.$_POST['identificador'].'</td>
		<td>'.ucwords(strtolower($consulta['apellido'])).'</td>
		<td>'.ucwords(strtolower($consulta['nombre'])).'</td>
        <td>'.$consulta['domicilio'].'</td>
        <td>'.$fila['nombrearea'].'</td>
        <td>'.$consulta['convencional'].'</td>
        <td >'.$consulta['celular'].'</td>
        <td >'.$fila['nombre_puesto'].'</td>
        <td >'.$_POST['responsable'].'</td>
        <td >'.$consulta['mail_institucional'].'</td>
        <td>'.$fecha_inicio.'</td>
		<td>'.$fecha_fin.'</td>	
		</tr>';
	 	}
	 }	 
	 
	 
	 $listaReporte = $cc->filtroObtenerEncargo($conexion, $_POST['identificador'], '', '', 'Aprobado', $_POST['area'],'','no');
	 while($fila = pg_fetch_assoc($listaReporte)){ 	
	 	$consulta=pg_fetch_assoc($cc->obtenerInformacionFuncionarioContratoActivo ($conexion, $fila['identificador_encargado']));
	 		
	 		$prioridad = pg_fetch_result($cc->verificarResponsable($conexion,$fila['identificador_subrogador'], $fila['area']), 0, 'prioridad');
	 		if($prioridad==1 or $prioridad==3)$nombre='Titular';
	 		else $nombre='Subrogante';
	 		
	 		if($fila['designacion']=='Encargado')$nombre='Encargado';
	 	
	 	echo '<tr>
	    <td>'.$fila['identificador_encargado'].'</td>
		<td>'.ucwords(strtolower($consulta['apellido'])).'</td>
		<td>'.ucwords(strtolower($consulta['nombre'])).'</td>
        <td>'.$consulta['domicilio'].'</td>
        <td>'.$fila['nombrearea'].'</td>
        <td>'.$consulta['convencional'].'</td>
        <td >'.$consulta['celular'].'</td>
        <td >'.$consulta['nombre_puesto'].'</td>
        <td >'.$nombre.'</td>
        <td >'.$consulta['mail_institucional'].'</td>
        <td>'.$fila['fecha_ini'].'</td>
		<td>'.$fila['fecha_fin'].'</td>	
		</tr>';
	 }
	 } catch (Exception $ex) {
		$conexion->ejecutarLogsTryCatch($ex);
	 }
	 ?>
	</tbody>
</table>
</div>
</body>
</html>
