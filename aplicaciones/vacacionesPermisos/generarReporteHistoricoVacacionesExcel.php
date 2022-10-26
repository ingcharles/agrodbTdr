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
	$estadoVacacion = $_POST['estadoVacacion'];
	$apellido = $_POST['apellido'];
	$nombre = $_POST['nombre'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$tipoPermiso = $_POST['tipoPermiso'];
	$subtipoPermiso = $_POST['subtipoPermiso'];
	$area = $_POST['area'];
	
	$listaReporte = $cv->filtroObtenerReporteHistoricoUsuario($conexion, $identificador, $apellido, $nombre, $fechaInicio, $fechaFin, $tipoPermiso, $subtipoPermiso, $estadoVacacion,$area);
	
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
			<th>Nombre</th>
			<th>Subtipo permiso</th>
			<th>Tiempo utilizado</th>
			<th>Fecha inicio</th>
			<th>Fecha fin</th>
			<th>Estado</th>
			<th>Observación</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 	 
	 While($fila = pg_fetch_assoc($listaReporte)) {

		$dias=floor(intval($fila['minutos_utilizados'])/480);
		$horas=floor((intval($fila['minutos_utilizados'])-$dias*480)/60);
		$minutos=(intval($fila['minutos_utilizados'])-$dias*480)-$horas*60;
		
		$minutosUtilizados=$dias.' días '. $horas .' horas '. $minutos .' minutos';
		//$minutosUtilizados=$cv->devolverFormatoDiasDisponibles($fila['minutos_utilizados']);
		$tiempoActual='';
		if(($fila['codigo'] == 'PE-PIV' || $fila['codigo'] == 'VA-VA' || $fila['codigo'] == 'PE-PIVF' || $fila['codigo'] == 'PE-DA') and $fila['minutos_actuales'] != ''){
			//$dias=floor(intval($fila['minutos_actuales'])/480);
			//$horas=floor((intval($fila['minutos_actuales'])-$dias*480)/60);
			//$minutos=(intval($fila['minutos_actuales'])-$dias*480)-$horas*60;
		
			$tiempoActual="Tiempo a la fecha ".$cv->devolverFormatoDiasDisponibles($fila['minutos_actuales']);
			//$tiempoActual ="Tiempo a la fecha ". $dias." días ". $horas ." horas ". $minutos ." minutos";
		}

				echo '<tr>
					<td class="formato">'.$fila['identificador'].'</td>
					<td>'.$fila['nombre'].'</td>
					<td>'.$fila['descripcion_subtipo'].'</td>
					<td>'.$minutosUtilizados.' </td>
					<td>'. date('Y-m-d H:i',strtotime($fila['fecha_inicio'])).'</td>
					<td>'. date('Y-m-d H:i',strtotime($fila['fecha_fin'])).'</td>
					<td>'.$fila['estado'].'</td>
					<td>'.$tiempoActual.'</td>
				</tr>';
        
	 }
	 
	 ?>
	
	</tbody>
</table>


</body>
</html>



