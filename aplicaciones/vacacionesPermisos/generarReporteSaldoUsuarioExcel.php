<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorVacaciones.php';
		
	header("Content-type: application/octet-stream");
	//indicamos al navegador que se está devolviendo un archivo
	header("Content-Disposition: attachment; filename=REPORTE.xls");
	//con esto evitamos que el navegador lo grabe en su caché
	header("Pragma: no-cache");
	header("Expires: 0");
	
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
		
	$identificador = $_POST['identificador'];
	$estadoSaldo = $_POST['estadoSaldo'];
	$apellido = $_POST['apellidoUsuario'];
	$nombre = $_POST['nombreUsuario'];
	$area = $_POST['area'];
	
	$listaReporte = $cv->filtroObtenerReporteSaldoUsuario($conexion, $identificador, $estadoSaldo, $apellido, $nombre, $area, 'individual');
	
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

<table id="tablaReporte" class="soloImpresion">
	<thead>
		<tr>
		    <th>Cédula</th>
		    <th>Apellidos y Nombres</th>
		    <th>Año</th>
		    <th>Tiempo disponibles</th>
		    <th>Minutos disponibles</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 While($fila = pg_fetch_assoc($listaReporte)) {
		$tiempoFormateado= $cv->devolverTiempoFormateadoDHM($fila['minutos_disponibles']);
        echo '<tr>
			<td class="formato">'.$fila['identificador'].'</td>
			<td>'.$fila['apellido'].' '.$fila['nombre'].'</td>
			<td>'.$fila['anio'].'</td>
		    <td>'. $tiempoFormateado.'</td> 	
            <td>'. $fila['minutos_disponibles'].'</td> 
    	</tr>';
	 }
	 $listaReporteFuncionario = $cv->filtroObtenerReporteSaldoFuncionario($conexion, $identificador, $estadoSaldo, $apellido, $nombre, $area, 'individual');
	 While($fila = pg_fetch_assoc($listaReporteFuncionario)) {
	 	$tiempoFormateado= $cv->devolverTiempoFormateadoDHM($fila['minutos_disponibles']);
	 	echo '<tr>
			<td class="formato">'.$fila['identificador'].'</td>
			<td>'.$fila['apellido'].' '.$fila['nombre'].'</td>
			<td>'.$fila['anio'].'</td>
		    <td>'. $tiempoFormateado.'</td>
            <td>'. $fila['minutos_disponibles'].'</td>
    	</tr>';
	 }
	 
	 ?>
	
	</tbody>
</table>


</body>
</html>



