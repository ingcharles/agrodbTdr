<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_CATASTRO_CERO_SANIDAD_ANIMAL_PORCINOS_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(3000);

$conexion = new Conexion();
$ccp = new ControladorCatastroProducto();
$cc = new ControladorCatalogos();

$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
$canton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
$parroquia= htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
$operacion = htmlspecialchars ($_POST['operacionB'],ENT_NOQUOTES,'UTF-8');
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

$res=$ccp->imprimirReporteCatastroCero($conexion, $provincia, $canton, $parroquia, $operacion, $fechaInicio, $fechaFin);

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

.formatoTexto{
 mso-style-parent:style0;
 mso-number-format:"0";
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
			<th>Provincia</th>
			<th>Cantón</th>
			<th>Parroquia</th>
		    <th>Tipo Operación</th>	    
		    <th>Nombre Sitio</th>
		    <th>Identificación Propietario</th>
		    <th>Nombre Propietario</th>
		    <th>Cantidad</th>		         
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>	
			     <td>'.$fila['provincia'].'</td>
				 <td>'.$fila['canton'].'</td>
				 <td>'.$fila['parroquia'].'</td>	
				 <td>'.$fila['tipo_operacion'].'</td>						 
				 <td>'.$fila['nombre_sitio'].'</td>
				 <td>'.sprintf("&nbsp;%0s",$fila['identificacion_propietario']).'</td>
				 <td>'.$fila['nombre_propietario'].'</td>			 
                 <td>'.$fila['cantidad'].'</td>
        	  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>