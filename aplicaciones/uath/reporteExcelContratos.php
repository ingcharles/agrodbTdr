<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';							   
require_once '../../clases/ControladorCatastro.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=REPORTE.xls");
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

$res =$cd->reporteContratosConsolidado($conexion, $_POST['regimen_laboral'], $_POST['provincia'], $_POST['canton'],
		$_POST['oficina'], $_POST['coordinacion'], $_POST['direccion'], $_POST['gestion'], $_POST['estado'], 
		$_POST['fechaInicio'], $_POST['fechaFin']);

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
			<th>Regimen laboral</th>
			<th>Tipo contrato</th>
			<th>No. contrato/acción de personal</th>
			<th>Estado</th>
			<th>Fecha inicio contrato/nombramiento</th>
			<th>Fecha fin contrato/nombramiento</th>
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
         <td>';?>
        <?php 
        if ($fila['estado']=="3")
        	echo "Finalizado";
		else if($fila['estado']=="1")
        	echo "Vigente";
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
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>
