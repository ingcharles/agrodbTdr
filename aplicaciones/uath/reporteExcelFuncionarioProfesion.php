<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorCatastro.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=Reporte_Funcionarios_Profesion.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");


$conexion = new Conexion();
$cd = new ControladorCatastro();
$constg = new Constantes();

/*$res =$cd->sacarReporteContratosFiltrados($conexion,$_POST['regimen_laboral'],$_POST['tipo_contrato'],$_POST['presupuesto'],$_POST['fuente'],$_POST['puesto'],$_POST['grupo'],
		$_POST['remuneracion'],$_POST['provincia'],$_POST['canton'],$_POST['oficina'],$_POST['direccion'],$_POST['coordinacion'],
		$finalizado=$_POST['estado']!='3'?$_POST['anio']:'',$finalizado=$_POST['estado']!='3'?$_POST['mes']:'',$_POST['estado'],
		$finalizado=$_POST['estado']=='3'?$_POST['anio']:'',$finalizado=$_POST['estado']=='3'?$_POST['mes']:'');*/

$res =$cd->reporteFuncionarioProfesionConsolidado($conexion, $_POST['regimen_laboral'], $_POST['provincia'], 
		$_POST['canton'], $_POST['oficina'], $_POST['coordinacion'], $_POST['direccion'], $_POST['gestion'], 
		$_POST['nivelInstruccion'], $_POST['titulos'], $_POST['carrera'], $_POST['estado']);

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
		     <th>Identificador</th>
		    <th>Nombre</th>
		    <th>Apellido</th>
			<th>Régimen laboral</th>
			<th>Tipo contrato</th>
			<th>Coordinación</th>
			<th>Dirección</th>
			<th>Gestión</th>
			<th>Puesto</th>
			<th>Provincia</th>
			<th>Nivel de Instrucción</th>
			<th>Certificado</th>
			<th>Título</th>
			<th>Carrera</th>
		    <th>Institución</th>
		    <th>Estado</th>
			<th>Observaciones</th>
			
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>
	    <td>'.$fila['identificador'].'</td>
		<td>'.mb_strtoupper($fila['nombre'], 'UTF-8').'</td>
		<td>'.mb_strtoupper($fila['apellido'], 'UTF-8').'</td>
        <td>'.$fila['regimen_laboral'].'</td>
        <td>'.$fila['tipo_contrato'].'</td>
        <td>'.$fila['coordinacion'].'</td>
        <td>'.$fila['direccion'].'</td>
        <td>'.$fila['gestion'].'</td>
		<td>'.$fila['nombre_puesto'].'</td>
		<td>'.$fila['provincia'].'</td>
        <td>'.$fila['nivel_instruccion'].'</td>
        <td>'.$fila['num_certificado'].'</td>
        <td>'.$fila['titulo'].'</td>
        <td>'.$fila['carrera'].'</td>
        <td>'.$fila['institucion'].'</td>
        <td>'.$fila['estado'].'</td>
        <td>'.$fila['observaciones_rrhh'].'</td>
		</tr>';
	 }
	
	 
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>
