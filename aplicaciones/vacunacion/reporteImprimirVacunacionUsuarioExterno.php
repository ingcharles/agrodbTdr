<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
header("Content-type: application/octet-stream");
$fecha = date('d-m-Y_H-i-s');
$ext   = '.xls';

$nomReporte = "REPORTE_VACUNACION_USUARIOS_EXTERNOS_".$fecha.$ext;
header("Content-Disposition: attachment; filename=$nomReporte");
header("Pragma: no-cache");
header("Expires: 0");
set_time_limit(3000);
$conexion = new Conexion();
$va = new ControladorVacunacion();

$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
$identificacionOperadora= htmlspecialchars ($_POST['identificacionOperadora'],ENT_NOQUOTES,'UTF-8');
$identificadorDistribuidor = htmlspecialchars ($_POST['distribuidor'],ENT_NOQUOTES,'UTF-8');
$identificadorVacunador = htmlspecialchars ($_POST['vacunador'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');
$estado = htmlspecialchars ($_POST['cmbEstado'],ENT_NOQUOTES,'UTF-8');

$res = $va->imprimirReporteVacunacionUsuarioExterno($conexion, $identificacionOperadora,$identificadorDistribuidor,$identificadorVacunador,$provincia, $fechaInicio, $fechaFin, $estado);

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
			<th>Digitador</th>
			<th>Vacunador</th>
			<th>Distribuidor</th>
			<th>Provincia Sitio</th>
			<th>Cantón Sitio</th>
			<th>Parroquia Sitio</th>
			<th>Nombre Sitio</th>
			<th>Identificación Propietario</th>
			<th>Nombre Propietario</th>
		    <th>Operador de Vacunación</th>	    
		    <th>Laboratorio</th>
		    <th>Tipo de Vacuna</th>
		    <th>Lote</th>
		 	<th>Número Productos Vacunados</th>			
		    <th>Fecha Registro</th>
		    <th>Fecha Vacunacion</th>   
		    <th>Estado</th>  
		</tr>		
	</thead>
	<tbody>
	 <?php	
	 while($fila = pg_fetch_assoc($res)){
	 	echo '<tr>	
				<td>'.$fila['numero_certificado'].'</td>
			    <td>'.$fila['nombre_responsable'].'</td>
				<td>'.$fila['nombre_vacunador'].'</td>
				<td>'.$fila['nombre_distribuidor'].'</td>
				<td>'.$fila['provincia'].'</td>
				<td>'.$fila['canton'].'</td>
				<td>'.$fila['parroquia'].'</td>
				<td>'.$fila['nombre_sitio'].'</td>	
				<td>'.sprintf("&nbsp;%0s",$fila['identificador_propietario']).'</td>	
				<td>'.$fila['nombre_propietario'].'</td>
				<td>'.$fila['nombre_administrador'].'</td>
				<td>'.$fila['nombre_laboratorio'].'</td>
				<td>'.$fila['nombre_vacuna'].'</td>
				<td>'.$fila['numero_lote'].'</td> 	
				<td>'.$fila['total_vacunado'].'</td>	
				<td>'.$fila['fecha_registro'].'</td>		
		 		<td>'.$fila['fecha_vacunacion'].'</td>
                <td>'.$fila['estado'].'</td>
        	  </tr>';
	 }

	 ?>	
	</tbody>
</table>
</div>
</html>