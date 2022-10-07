<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';
require_once '../../clases/ControladorAreas.php';

/*$fecha = getdate();
$anio = $fecha['year'];*/

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
$crp = new ControladorReformaPresupuestaria();
$ca = new ControladorAreas();


/*if($_POST['provincia'] == '1'){
	
	$qAreas = $ca->buscarAreasSubprocesos($conexion, $_POST['areaDireccion']);
	
	while ($fila = pg_fetch_assoc($qAreas)){
			$areaBusqueda .= $fila['id_area']."-";
	}
	
	$areaBusqueda = rtrim($areaBusqueda,"-");
	
	$_POST['areaDireccion'] = $areaBusqueda;
}*/

/*if($_POST['areaDireccion'] == 'Todos'){

	$qAreas = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Gestión','Unidad')", "(1,3,4,5)");
	
	while ($fila = pg_fetch_assoc($qAreas)){
		$areaBusqueda .= $fila['id_area']."-";
	}
	
	$areaBusqueda = rtrim($areaBusqueda,"-");
	
	$_POST['areaDireccion'] = $areaBusqueda;
}*/

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
<link href="estilos/estiloapp.css" rel="stylesheet"></link>

</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA">Ministerio de Agricultura, Ganaderia, Acuacultura y Pesca<Br>
				Agencia Ecuatoriana de Aseguramiento de la Calidad del Agro Agrocalidad<Br>
							Reforma Presupuestaria por Partidas <?php echo $anio;?><br>
	</div>
	<div id="direccion"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteReformaPresupuestariaPartidaTemporalDetalleExcel.php" target="_blank" method="post">
	 <input type="hidden" id="areaN2" name="areaN2" value="<?php echo $_POST['areaN2'];?>" />
	 <input type="hidden" id="nombreAreaN2" name="nombreAreaN2" value="<?php echo $nombreArea;?>" />
	 <input type="hidden" id="proceso" name="proceso" value="<?php echo $_POST['procesoReporte'];?>" />
	 <input type="hidden" id="actividad" name="actividad" value="<?php echo $_POST['actividadReporte'];?>" />
	 <input type="hidden" id="tipo" name="tipo" value="<?php echo $_POST['tipo'];?>" />
	 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
	 <input type="hidden" id="anio" name="anio" value="<?php echo $anio;?>" />
	 <input type="hidden" id="estadoReporte" name="estadoReporte" value="<?php echo $estadoReporte;?>" />
	 
	 <input type="hidden" id="idProgramaPAC" name="idProgramaPAC" value="<?php echo $_POST['idProgramaPAC'];?>" />
	 <input type="hidden" id="codigoProgramaPAC" name="codigoProgramaPAC" value="<?php echo $_POST['codigoProgramaPAC'];?>" />
	 <input type="hidden" id="nombreProgramaPAC" name="nombreProgramaPAC" value="<?php echo $_POST['nombreProgramaPAC'];?>" />
	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
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
	<tbody>
	
<?php

print_r($_POST);

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
</body>
</html>