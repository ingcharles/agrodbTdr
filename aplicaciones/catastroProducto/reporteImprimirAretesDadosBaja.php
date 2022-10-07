<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';
$nomReporte = "REPORTE_CATASTRO_PRODUCTO_AGROPECUARIOS_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(3000);

$conexion = new Conexion();
$ccp = new ControladorCatastroProducto();

$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');

$res=$ccp->imprimirReporteAretesDadosBaja($conexion, $provincia, $fechaInicio, $fechaFin);

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
			<th>Identificador usuario</th>
			<th>Nombre usuario responsable</th>
			<th>Tipo usuario</th>
			<th>Identificador propietario</th>
		    <th>Nombre operador</th>	    
		    <th>Fecha de baja</th>
		    <th>Cantidad de aretes</th>
		    <th>Motivo</th>
		    <th>Provincia</th>	         
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){     
	 	echo '<tr>
                 <td>'.sprintf("&nbsp;%0s",$fila['identificacion_responsable']).'</td>	
				 <td>'.$fila['nombre_responsable'].'</td>
                 <td>'.$fila['tipo_usuario'].'</td>
                 <td>'.sprintf("&nbsp;%0s",$fila['identificador_propietario']).'</td>	
				 <td>'.$fila['nombre_propietario'].'</td>						 
				 <td>'.$fila['fecha_baja'].'</td>
                 <td>'.$fila['cantidad_aretes'].'</td>   
				 <td>'.$fila['motivo'].'</td>				 
				 <td>'.$fila['provincia'].'</td>
        	  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>