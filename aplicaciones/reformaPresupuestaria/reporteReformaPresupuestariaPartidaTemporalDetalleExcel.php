<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';
require_once '../../clases/ControladorAreas.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=reportePartidasPresupuestariasTemporal.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

/*$fecha = getdate();
$anio = $fecha['year'];*/

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
$crp = new ControladorReformaPresupuestaria();
$ca = new ControladorAreas();
$constg = new Constantes();

/*$completo = $cpp->obtenerReportePac($conexion, $_POST['areaN2'], $_POST['proceso'], $_POST['actividad'], 
													$_POST['tipo'], $_POST['provincia'], $anio, 'aprobado');*/

/*$completo = $cpp->obtenerReportePac($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'], $_POST['codigoProyectoPAC'],
		$_POST['codigoActividadPAC'], $_POST['provincia'], $anio, 'aprobado');*/

$nombreArea = htmlspecialchars ($_POST['nombreAreaN2'],ENT_NOQUOTES,'UTF-8');
$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
$estadoReporte = htmlspecialchars ($_POST['estadoReporte'],ENT_NOQUOTES,'UTF-8');

//Obtiene todas las partidas que han sido modificadas por los usuarios y el nombre de la partida
$completoTemporal = $crp->obtenerPartidasReformadasTemporal($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'],
		$_POST['codigoProyectoPAC'], $_POST['codigoActividadPAC'],
		$anio, $estadoReporte);



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
 mso-number-format:"0.0000";
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

//Firmas
#tablaReportePacFirmas
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	width: 100%;
	margin: 0;
	padding: 0;
    border-collapse:collapse;
}

#tablaReportePacFirmas td, #tablaReportePacFirmas th 
{
font-size:1em;
padding:1px 3px 1px 3px;
}

#textoTituloFirmas{
font-size:12em;
text-align: center;
float:left;
}

#textoSubtituloFirmas{
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
							Reforma Presupuestaria por Partidas <?php echo $anio;?><br>
	</div>
	
	<table>
		<thead>
			<tr></tr>			
			<tr></tr>			
			<tr></tr>			
		</thead>
	</table>
</div>

<div id="tabla">
<table id="tablaReportePresupuesto" class="soloImpresion">
	<thead>
		<tr>
		    <th>DESCRIPCION</th>
		    <th>PARTIDA</th>		    
			<th>INICIAL</th>
			<th>AUMENTO</th>
			<th>REDUCCION</th>
			<th>CODIFICADO</th>
		</tr>
	</thead>
	
<?php

	 $codificado=0;
	 $aumento=0;
	 $reduccion=0;
	 $total=0;
	 
	 //Matriz completa
	 while($fila = pg_fetch_assoc($completoTemporal)){
	 	
	 	//Obtiene el monto total aprobado para la partida en el PAP-PAC Real
	 	$montoPartidaReal = pg_fetch_assoc($crp->obtenerMontoTotalXPartida($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'],
											 			$_POST['codigoProyectoPAC'], $_POST['codigoActividadPAC'],
											 			$anio, 'aprobado', $fila['renglon']));
	 	
	 	//Obtener monto aumentado por partida
	 	$montoPartidaIncremento = pg_fetch_assoc($crp->obtenerMontoReformadoXPartidasYEstadoTemporal($conexion, $_POST['areaN2'], 
											 			$_POST['codigoProgramaPAC'],$_POST['codigoProyectoPAC'], 
											 			$_POST['codigoActividadPAC'],$anio, 
	 													$estadoReporte, $fila['renglon'], 'incremento'));
	 	 
	 	//Obtener monto reducido por partida
	 	$montoPartidaDecremento = pg_fetch_assoc($crp->obtenerMontoReformadoXPartidasYEstadoTemporal($conexion, $_POST['areaN2'],
											 			$_POST['codigoProgramaPAC'],$_POST['codigoProyectoPAC'],
											 			$_POST['codigoActividadPAC'],$anio,
											 			$estadoReporte, $fila['renglon'], 'decremento'));
	 	
	 	echo '	<tr>
				    <td class="formatoTexto">'.$fila['nombre'].'</td>
			        <td class="formatoTexto">'.$fila['renglon'].'</td>
			    	<td class="formatoNumeroDecimal4">'.$montoPartidaReal['codificado'].'</td>
			    	
					<td class="formatoNumeroDecimal4">'.abs($montoPartidaIncremento['monto_modificado']).'</td>
					<td class="formatoNumeroDecimal4">'.$montoPartidaDecremento['monto_modificado'].'</td>
					<td class="formatoNumeroDecimal4">'.($montoPartidaReal['codificado']+abs($montoPartidaIncremento['monto_modificado'])-$montoPartidaDecremento['monto_modificado']).'</td>
				</tr>';
	 	
	 	$codificado += $montoPartidaReal['codificado'];
	 	$aumento += abs($montoPartidaIncremento['monto_modificado']);
	 	$reduccion += $montoPartidaDecremento['monto_modificado'];
	 	//$total += ($montoPartidaReal['codificado']+$montoPartidaIncremento['monto_modificado']-$montoPartidaDecremento['monto_modificado']);
	 }
 
	 $total = $codificado + $aumento - $reduccion;
	 
	 echo '	<tr>
				<td colspan="2">Total</td>
				<td class="formatoNumeroDecimal4">'.$codificado.'</td>
				<td class="formatoNumeroDecimal4">'.$aumento.'</td>
				<td class="formatoNumeroDecimal4">'.$reduccion.'</td>
				<td class="formatoNumeroDecimal4">'.$total.'</td>
			</tr>';
	 ?>
	
	</tbody>
</table>

</div>

<div id="tablaHeader">
	<table id="tablaReportePacFirmas" class="soloImpresion">
		<thead>
			<tr></tr>
			<tr></tr>
			<tr></tr>
			<tr></tr>
			<tr></tr>
		</thead>
		
		<?php 
			//Realizado por
			/*$idRealizado = $_SESSION['usuario'];
			$nombreRealizado = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $idRealizado));*/

			//Aprobado por
			$idAprobado = pg_fetch_result($ca->buscarResponsableSubproceso($conexion, $_POST['areaN2']), 0, 'identificador');
			$nombreAprobador = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $idAprobado));
		?>
		<tbody>
			 <tr>
				 <!-- td colspan="2" id="textoSubtituloFirmas">< ?php echo $nombreRealizado['nombre'].' '.$nombreRealizado['apellido'];?></td-->
				 <td colspan="5" id="textoSubtituloFirmas"><?php echo $nombreAprobador['nombre'].' '.$nombreAprobador['apellido'];?></td>
			 </tr>
			 
			 <tr>
				 <!--td colspan="2" id="textoSubtituloFirmas">REALIZADO</td-->
				 <td colspan="5" id="textoSubtituloFirmas">APROBADOR</td>
			 </tr>
		</tbody>
	</table>
</div>
</body>
</html>