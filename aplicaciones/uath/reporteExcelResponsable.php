<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=Reporte_Funcionarios.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");


$conexion = new Conexion();
$cc = new ControladorCatastro();
$constg = new Constantes();
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">


#tablaReporteContratos 
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	width: 100%;
	margin: 0;
	padding: 0;
    border-collapse:collapse;
}

#tablaReporteContratos td, #tablaReporteContratos th 
{
font-size:1em;
border:1px solid #98bf21;
padding:1px 3px 1px 3px;
}

#tablaReporteContratos th 
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
	<div id="textoPOA"><?php echo $constg::NOMBRE_INSTITUCION;?><br>
	AGROCALIDAD TALENTO HUMANO<br>
	
	</div>
	<div id="direccion"></div>
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
		    <th >Correo Electrónico</th>
		    <th>Periódo de Gestión desde</th>
		    <th>Periódo de Gestión hasta</th>	
		</tr>
	</thead>
	<tbody>
	 <?php
	 try {
 if($_POST['responsable']=='Titula' or $_POST['responsable']=='Subrogante')$respon='';
	  $listaReporte = $cc->filtroObtenerFuncionarios($conexion, $_POST['identificador'], '', '', $respon, $_POST['area']);
	 
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
	 		$consulta=pg_fetch_assoc($cc->obtenerInformacionFuncionarioContratoActivo ($conexion, $_POST['identificador']));
	 	echo '<tr>
	    <td>'.$_POST['identificador'].'</td>
		<td>'.ucwords(strtolower($consulta['apellido'])).'</td>
		<td>'.ucwords(strtolower($consulta['nombre'])).'</td>
        <td>'.$consulta['domicilio'].'</td>
        <td>'.$consulta['direccion'].'</td>
        <td>'.$consulta['convencional'].'</td>
        <td >'.$consulta['celular'].'</td>
        <td >'.$consulta['nombre_puesto'].'</td>
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
        <td>'.$consulta['direccion'].'</td>
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
