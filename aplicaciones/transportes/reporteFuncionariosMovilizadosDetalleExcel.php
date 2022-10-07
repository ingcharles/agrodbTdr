<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorVehiculos.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=reporteFuncionariosMovilizados.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cv = new ControladorVehiculos();
$constg = new Constantes();

$localizacion = htmlspecialchars ($_POST['localizacion'],ENT_NOQUOTES,'UTF-8');
$fechaInicio = htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8');
$fechaFin = htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8');

$completo = $cv->obtenerReporteUsuariosMovilizados($conexion, $localizacion, $fechaInicio, $fechaFin);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<style type="text/css">


#tablaReportePresupuesto 
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	width: 100%;
	margin: 0;
	padding: 0;
    border-collapse:collapse;
}

#tablaReportePresupuesto td, #tablaReportePresupuesto th 
{
font-size:1em;
border:0.5px solid #000000;
padding:1px 3px 1px 3px;
}

#tablaReportePresupuesto th 
{
font-size:1em;
text-align:left;
padding-top:3px;
padding-bottom:2px;
background-color:#A7C942;
color:#ffffff;
}


//Cabecera
#tablaReportePac 
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	width: 100%;
	margin: 0;
	padding: 0;
    border-collapse:collapse;
}

#tablaReportePac td, #tablaReportePac th 
{
font-size:1em;
padding:1px 3px 1px 3px;
}

#textoTitulo{
font-size:12em;
text-align: center;
float:left;
}

#textoSubtitulo{
text-align: center;
float:left;
}

.formatoTexto{
 mso-style-parent:style0;
 mso-number-format:"\@";
}

.formatoNumeroDecimal4{
 mso-style-parent:style0;
 mso-number-format:"\#\\#\#0\.0000";
}

#logotexto{
width: 10%;
height:80px;
float: left;
}

#textoPOA{
width: 40%;
height:80px;
text-align: center;
float:left;
}


</style>
</head>
	<body>

		<div id="header">
		   	<div id="logoMagap"></div>
			<div id="texto"></div>
			<div id="logoAgrocalidad"></div>
			<div id="textoPOA">Ministerio de Agricultura, Ganaderia, Acuacultura y Pesca<Br>
								<?php echo $constg::NOMBRE_INSTITUCION;?><Br> 
									Reporte de Movilizaciones Generadas y Funcionarios Movilizados <Br>
									Del <?php echo $fechaInicio . ' al ' . $fechaFin;?><br>
			</div>
			<div id="direccion"></div>
			<div id="bandera"></div>
		</div>
		<div id="tabla">
		<table id="tablaReportePresupuesto" class="soloImpresion">
			<thead>
				<tr>
				    <th>ID MOVILIZACIÓN</th>
				    <th>TIPO DE MOVILIZACIÓN</th>
					<th>FECHA DE SOLICITUD</th>
					<th>DESCRIPCIÓN</th>
					<th>PLACA</th>
					<th>KM INICIAL</th>
					<th>KM FINAL</th>
					<th>LOCALIZACIÓN</th>
					<th>OBSERVACIÓN DE RUTA</th>
					<th>NÚM DE PASAJEROS</th>
				    <th>CONDUCTOR</th>
				    <th>ESTADO</th>
				</tr>
			</thead>
			<tbody>
			
			 <?php
			 
			 //Matriz completa
			 while($fila = pg_fetch_assoc($completo)){
			 	
			 	echo '	<tr>
						    <td class="formatoTexto">'.$fila['id_movilizacion'].'</td>
					        <td class="formatoTexto">'.$fila['tipo_movilizacion'].'</td>
					        <td class="formatoTexto">'.$fila['fecha_solicitud'].'</td>
					        <td class="formatoTexto">'.$fila['descripcion'].'</td>
					    	<td class="formatoTexto">'.$fila['placa'].'</td>
					    	<td class="formatoTexto">'.$fila['kilometraje_inicial'].'</td>
					        <td class="formatoTexto">'.$fila['kilometraje_final'].'</td>
					        <td class="formatoTexto">'.$fila['localizacion'].'</td>
					        <td class="formatoTexto">'.$fila['observacion_ruta'].'</td>
							<td class="formatoTexto">'.$fila['num_pasajeros'].'</td>
					        <td class="formatoTexto">'.$fila['nombre'].' '.$fila['apellido'].'</td>
					        <td class="formatoTexto">'.(($fila['estado']=="1")? 'Creado':(($fila['estado']=="2")? 'Por Imprimir':(($fila['estado']=="3")? 'Por Finalizar':(($fila['estado']=="4")? 'Finalizado':'Eliminado')))).'</td>
						</tr>';
			 }
			 
			 ?>
			
			</tbody>
		</table>
		
		</div>
	</body>
</html>