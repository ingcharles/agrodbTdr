<?php 

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion();
$ca = new ControladorAuditoria();
	
header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=REPORTEAUDITORIAASOCIACION.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$qAuditoria = $ca->buscarAuditoriaXMiembrAsociacion($conexion, $_POST['identificadorMiembro'], $_POST['nombreMiembroAsociacion'], $_POST['fechaInicio'], $_POST['fechaFin']);
	
?>


<html LANG="es">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">

<style type="text/css">
#tablaReporte
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	display: inline-block;
	width: auto;
	margin: 0;
	padding: 0;
border-collapse:collapse;
}

#tablaReporte td, #tablaReporte th 
{
font-size:1em;
border:1px solid #98bf21;
padding:3px 7px 2px 7px;
}

#tablaReporte th 
{
font-size:1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
background-color:#A7C942;
color:#ffffff;
}

@page{
   margin: 5px;
}

.formato{
 mso-style-parent:style0;
 mso-number-format:"\@";
}

.formatoNumero{
	mso-style-parent:style0;
	mso-number-format:"0.000000";
}

.colorCelda{
	background-color: #FFE699;
}

</style>


</head>
<body>


<div id="tabla">
<table id="tablaReporte" class="soloImpresion">
	<thead>
		<tr>
		    <th>Identificación</th>
			<th>Nombre completo</th>
			<th>Descripción del cambio</th>
			<th>Fecha de cambio</th>
			<th>Estado</th>					
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 while($fila = pg_fetch_assoc($qAuditoria)){
	 	echo '<tr
		id="'.$fila['id_miembro_asociacion'].'">
		<td>'.$fila['identificador_miembro_asociacion'].'</td>
		<td>'.$fila['nombre_miembro_asociacion'].'</td>
		<td>'.$fila['detalle_auditoria'].'</td>
		<td>'.$fila['fecha_registro'].'</td>
		<td>'.$fila['estado_auditoria'].'</td>
		</tr>';
	 }

	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>



