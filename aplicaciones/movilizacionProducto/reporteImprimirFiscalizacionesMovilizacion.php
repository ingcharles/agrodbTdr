<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorCatalogos.php';

header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_FISCALIZACIONES_MOVILIZACION_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(3000);

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();
$cc = new ControladorCatalogos();

$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
$canton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
$parroquia= htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
$resultado = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');


if ($provincia!="todos"){
	$provincia=pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $provincia), 0, 'nombre');
	if ($canton!="todos" && $canton!=""){
		$canton=pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $canton), 0, 'nombre');
		if($parroquia!="todos" && $parroquia!=""){
			$parroquia=pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $parroquia), 0, 'nombre');
		}else{
			$parroquia="";
		}
	}else{
		$canton="";
	}
}else{
	$provincia="";
}

if ($resultado=="todos"){
	$resultado="";
}

$res=$cmp->imprimirReporteFiscalizacionesMovilizacion($conexion, $provincia, $canton, $parroquia, $resultado, $fechaInicio, $fechaFin);

?>
<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">
#tablaReporteVacunaAnimal
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
display: inline-block;
width: auto;
margin: 0;
padding: 0;
border-collapse:collapse;
}
#tablaReporteVacunaAnimal td, #tablaReporteVacunaAnimal th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}
#tablaReporteVacunaAnimal th 
{
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}

#textoEncabezado{
width: 40%;
height:80px;
text-align: center;
float:left;
}

</style>
</body>
</head>
<body>
<div id="textoEncabezado">
	MINISTERIO DE AGRICULTURA, GANADERIA, ACUACULTURA Y PESCA<br>
	AGROCALIDAD<br>
	CONTROL DE PESTE PORCINA CLASICA (PPC)<br>
</div>

<div id="tabla">
<table id="tablaReporteVacunaAnimal" class="soloImpresion">
	<thead>
		<tr>
			<th>Número Certificado</th>
			<th>Tipo Usuario</th>
			<th>Identificación Responsable Fiscalización</th>
			<th>Nombre Responsable Fiscalización</th>
			<th>Provincia Responsable Fiscalización</th>
			<th>Provincia Origen</th>
			<th>Código Provincia Origen</th>
		    <th>Cantón Origen</th>	    
		    <th>Parroquia Origen</th>
		    <th>Sitio Origen</th>
		    <th>Identificación Propietario</th>
		    <th>Nombre Propietario</th>
		    <th>Lugar de fiscalización</th>
		    <th>Resultado</th>
		    <th>Acción Correctiva</th>	 
		    <th>Porcinos en CSMI</th>
		    <th>Porcinos fiscalizados</th>		   
		    <th>Observación</th>
		    <th>Fecha Registro</th>
		    <th>Fecha Fiscalización</th>	   
		    <th>Estado Fiscalización</th> 
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>	
			    <td>'.sprintf("&nbsp;%0s",$fila['numero_certificado']).'</td>
				<td>'.$fila['tipo_usuario'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['identificacion_responsable_fiscalizacion']).'</td>
				<td>'.$fila['nombre_responsable_fiscalizacion'].'</td>
				<td>'.$fila['provincia_responsable_fiscalizacion'].'</td>
				<td>'.$fila['provincia_sitio_origen'].'</td>
                <td>'.$fila['codigo_provincia_origen'].'</td>
				<td>'.$fila['canton_sitio_origen'].'</td>
				<td>'.$fila['parroquia_sitio_origen'].'</td>
				<td>'.$fila['nombre_sitio_origen'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['identificacion_propietario']).'</td>
				<td>'.$fila['nombre_propietario'].'</td>
                <td>'.$fila['lugar_fiscalizacion'].'</td>
				<td>'.$fila['resultado'].'</td>
				<td>'.$fila['accion_correctiva'].'</td>
                <td>'.$fila['cantidad_total'].'</td>
                <td>'.$fila['cantidad_animales'].'</td>
				<td>'.$fila['observacion'].'</td>
				<td>'.$fila['fecha_registro'].'</td>
				<td>'.$fila['fecha_fiscalizacion'].'</td>
				<td>'.$fila['estado_fiscalizacion'].'</td>
			  </tr>';
	 }
	 ?>	
	</tbody>
</table>
</div>
</html>