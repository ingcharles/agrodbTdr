<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=REPORTE.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");


$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();
$constg = new Constantes();

$res =$cai->reporteAccidenteIncidente($conexion,$_POST['zona'],$_POST['identificador'],$_POST['estadoSolicitud'],$_POST['fechaDesde'],$_POST['fechaHasta']);

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
background-image: url(../img/magap_logo.jpg); background-repeat: no-repeat;
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
background-image: url(../img/agrocalidad.png); background-repeat: no-repeat;
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
background-image: url(../img/direccion.png); background-repeat: no-repeat;
float: left;
}

#bandera{
width: 5%;
height:80px;
background-image: url(../img/bandera.png); background-repeat: no-repeat;
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
	TALENTO HUMANO<br>
	
	</div>
	<div id="direccion"></div>
	<div id="bandera"></div>
</div>
<div id="tabla">
<table id="tablaReporteContratos" class="soloImpresion">
	<thead>
		<tr>
		    <th>Número de Resgistro</th>
		    <th>Estado</th>
		    <th>Fecha del Accidente</th>
			<th>Tipo de Accidente</th>
			<th>Distrito</th>
			<th>Provincia</th>
			<th>Ciudad</th>
			<th>Género</th>
			<th>Identificación</th>
			<th>Nombres y Apellidos</th>
			<th>Edad</th>
			<th>Lugar del Accidente</th>
			<th>Dirección del Accidente</th>
			<th>Descripción del Accidente</th>
			<th>Descripción de las Lesiones</th>
			<th>Tiempo de Reposo</th>
			
		</tr>
	</thead>
	<tbody>
	 <?php
	 
while($fila = pg_fetch_assoc($res)){
	 	$distrito=$fila['nombrearea'];
	 	$reposo='';
	 	if($fila['id_area_padre'] == 'DGATH')$distrito='Planta central';
	 	if($fila['reposo_desde'] != '')
	 		$reposo='Desde: '.$fila['reposo_desde'].' - Hasta: '.$fila['reposo_hasta'];
	 	echo '<tr>
	    <td>'.$fila['cod_datos_accidente'].'</td>
		<td>'.strtoupper($fila['estado']).'</td>
		<td>'.$fila['fecha_accidente'].'</td>
        <td>'.mb_strtoupper($fila['tipo_sso'], 'UTF-8').'</td>
        <td>'.$distrito.'</td>
    	<td>'.$fila['provincia'].'</td>
        <td>'.$fila['ciudad'].'</td>
    	<td>'.$fila['genero'].'</td>
    	<td>'.$fila['identificador_accidentado'].'</td>
        <td>'.$fila['funcionario'].'</td>
        <td>'.$fila['edad'].'</td>
        <td>'.$fila['lugar_accidente'].'</td>
        <td>'.$fila['direccion'].'</td>
        <td>'.$fila['describir_accidentado'].'</td>
        <td>'.$fila['descripcion_lesiones'].'</td>
        <td>'.$reposo.'</td>
		</tr>';
	 }
	 
	 ?>
	</tbody>
</table>

</div>
</body>
</html>
