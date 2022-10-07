<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_TRANSACCIONES_CATASTRO_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(3000);

$conexion = new Conexion();
$ccp = new ControladorCatastroProducto();
$cc = new ControladorCatalogos();

$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
$identificacionPropietario = htmlspecialchars ($_POST['identificacionPropietario'],ENT_NOQUOTES,'UTF-8');
$provincia=$provincia!="todos" ? pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $provincia), 0, 'nombre'):"";
$res=$ccp->imprimirReporteTransaccionesCatastro($conexion, $provincia, $identificacionPropietario);

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
			<th>Identificación Propietario</th>
			<th>Nombre Propietario</th>
			<th>Nombre Sitio</th>
		    <th>Cantidad Ingreso</th>	    
		    <th>Cantidad Egreso</th>
		    <th>Cantidad Total</th>
		    <th>Motivo</th>
		    <th>Tipo Operacion</th>
		    <th>Subtipo Producto</th>
		    <th>Producto</th>
		    <th>Identificación Responsable</th>
		    <th>Nombre Responsable</th>
		    <th>Fecha Registro</th>		    	       
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>	
			     <td>'.sprintf("&nbsp;%0s",$fila['identificador_propietario']).'</td>
				 <td>'.$fila['nombre_propietario'].'</td>
				 <td>'.$fila['nombre_sitio'].'</td>	
				 <td>'.$fila['cantidad_ingreso'].'</td>						 
				 <td>'.$fila['cantidad_egreso'].'</td>
				 <td>'.$fila['cantidad_total'].'</td>
				 <td>'.$fila['motivo'].'</td>
				 <td>'.$fila['tipo_operacion'].'</td>
				 <td>'.$fila['subtipo_producto'].'</td>
				 <td>'.$fila['producto'].'</td>
				 <td>'.sprintf("&nbsp;%0s",$fila['identificacion_responsable']).'</td>
				 <td>'.$fila['nombre_responsable'].'</td>
				<td>'.$fila['fecha_registro'].'</td>
        	  </tr>';
	 }
	 ?>	
	</tbody>
</table>
</div>
</html>