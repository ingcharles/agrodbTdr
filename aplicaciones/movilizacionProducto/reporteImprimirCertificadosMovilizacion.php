<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorCatalogos.php';

header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_CERTIFICADOS_MOVILIZACION_".$fecha.$ext;
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
$operacion = htmlspecialchars ($_POST['operacion'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['estadoMovilizacion'],ENT_NOQUOTES,'UTF-8');
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

if ($operacion=="todos"){
	$operacion="";
}
if ($estado=="todos"){
	$estado="";
}
$res=$cmp->imprimirReporteCertificadosMovilizacion($conexion, $provincia, $canton, $parroquia, $operacion, $estado, $fechaInicio, $fechaFin);

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
			<th>Provincia Emisión</th>
			<th>Oficina Emisión</th>
			<th>Operación Origen</th>
			<th>Provincia Origen</th>
			<th>Código Provincia Origen</th>
		    <th>Cantón Origen</th>	    
		    <th>Parroquia Origen</th>
		    <th>Sitio Origen</th>
		    <th>Identificación Operador Origen</th>
		    <th>Razón Social Operador Origen</th>
		    <th>Nombre Operador Origen</th>
		    <th>Operación Destino</th>	    
		    <th>Provincia Destino</th>
		    <th>Código Provincia Destino</th>
		    <th>Cantón Destino</th>
		    <th>Parroquia Destino</th>	    
		    <th>Sitio Destino</th>
		    <th>Identificación Operador Destino</th>
		    <th>Razón Social Operador Destino</th>	    
		    <th>Nombre Operador Destino</th>
		    <th>Identificación Usuario Responsable</th>
		    <th>Nombre Usuario Responsable</th>
		    <th>Producto</th>
		    <th>Cantidad</th>
		    <th>Identificación Solicitante</th>
		    <th>Nombre Solicitante</th>	    
		    <th>Identificación Conductor</th>
		    <th>Nombre Conductor</th>
		    <th>Medio Transporte</th>
		    <th>Placa de Transporte</th>
		    <th>Observación</th>
		    <th>Fecha Registro</th>
		    <th>Fecha Inicio Vigencia</th>
		    <th>Fecha Fin Vigencia</th>
		    <th>Estado</th>
		    <th>Fecha Anulación</th>
		    <th>Observación Anulación</th>
		    <th>Motivo Anulación</th>  
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>	
			     <td>'.sprintf("&nbsp;%0s",$fila['numero_certificado']).'</td>
				<td>'.$fila['provincia_emision'].'</td>
				<td>'.$fila['oficina_emision'].'</td>
				<td>'.$fila['operacion_origen'].'</td>
				<td>'.$fila['provincia_origen'].'</td>
                <td>'.$fila['codigo_provincia_origen'].'</td>
				<td>'.$fila['canton_origen'].'</td>
				<td>'.$fila['parroquia_origen'].'</td>
				<td>'.$fila['sitio_origen'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['identificador_operador_origen']).'</td>
				<td>'.$fila['razon_social_operador_origen'].'</td>
				<td>'.$fila['nombre_operador_origen'].'</td>
				<td>'.$fila['operacion_destino'].'</td>
				<td>'.$fila['provincia_destino'].'</td>
                <td>'.$fila['codigo_provincia_destino'].'</td>
				<td>'.$fila['canton_destino'].'</td>
				<td>'.$fila['parroquia_destino'].'</td>
				<td>'.$fila['sitio_destino'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['identificador_operador_destino']).'</td>
				<td>'.$fila['razon_social_operador_destino'].'</td>
				<td>'.$fila['nombre_operador_destino'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['identificacion_usuario_responsable']).'</td>
				<td>'.$fila['nombre_usuario_responsable'].'</td>
				<td>'.$fila['producto'].'</td>
				<td>'.$fila['cantidad'].'</td>
				<td>'.sprintf("&nbsp;%0s",$fila['identificador_solicitante']).'</td>
				<td>'.$fila['nombre_solicitante'].'</td>
				<td>'.$fila['identificacion_conductor'].'</td>
				<td>'.$fila['nombre_conductor'].'</td>
				<td>'.$fila['medio_transporte'].'</td>
				<td>'.$fila['placa_transporte'].'</td>
				<td>'.$fila['observacion'].'</td>
				<td>'.$fila['fecha_registro'].'</td>
				<td>'.$fila['fecha_inicio_vigencia'].'</td>
				<td>'.$fila['fecha_fin_vigencia'].'</td>
				<td>'.$fila['estado'].'</td>
				<td>'.$fila['fecha_anulacion'].'</td>
				<td>'.$fila['observacion_anulacion'].'</td>
				<td>'.$fila['motivo_anulacion'].'</td>		
		</tr>';
	 }
	 ?>	
	</tbody>
</table>
</div>
</html>