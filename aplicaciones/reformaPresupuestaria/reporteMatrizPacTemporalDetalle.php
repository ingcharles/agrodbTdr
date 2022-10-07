<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
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
$constg = new Constantes();


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

    /*$completo =$cd->sacarReporteMatrizPOA($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['fi'],$_POST['ff'],$_POST['codigo_Indicador'],$_POST['listaCobertura'],$_POST['listaPoblacion'],$_POST['ListaResponsable'],$_POST['listaVerificacion'], $fecha['year']);
	$presupuesto = $cd->sacarReporteActividadesPresupuesto($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'], $fecha['year']);
	$actividades = $cd->sacarReporteActividades($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'], $fecha['year']);*/

	$nombreArea = htmlspecialchars ($_POST['nombreAreaN2'],ENT_NOQUOTES,'UTF-8');
	$anio = htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$estadoReporte = htmlspecialchars ($_POST['estadoReporte'],ENT_NOQUOTES,'UTF-8');

	if($nombreArea == 'Fortalecimiento Institucional'){
		$completo = $crp->obtenerReportePacFortalecimientoTemporal($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'], $_POST['codigoProyectoPAC'], 
													$_POST['codigoActividadPAC'], $_POST['provincia'], $anio, $estadoReporte);
	}else{
		$completo = $crp->obtenerReportePacTemporal($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'], $_POST['codigoProyectoPAC'],
				$_POST['codigoActividadPAC'], $_POST['provincia'], $anio, $estadoReporte);
	}
	
/*$completo = $cpp->obtenerReportePac($conexion, $_POST['areaN2'], $_POST['proceso'], $_POST['actividad'],
		$_POST['tipo'], $_POST['provincia'], $anio, 'aprobado');*/
	
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
						<?php echo $constg::NOMBRE_INSTITUCION;?><Br> 
						Plan Anual de Contratación PAC <?php echo $anio;?><br>
	</div>
	<div id="direccion"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteMatrizPacTemporalDetalleExcel.php" target="_blank" method="post">
	 <input type="hidden" id="areaN2" name="areaN2" value="<?php echo $_POST['areaN2'];?>" />
	 <input type="hidden" id="nombreAreaN2" name="nombreAreaN2" value="<?php echo $nombreArea;?>" />
	 <input type="hidden" id="proceso" name="proceso" value="<?php echo $_POST['procesoReporte'];?>" />
	 <input type="hidden" id="actividad" name="actividad" value="<?php echo $_POST['actividadReporte'];?>" />
	 <input type="hidden" id="tipo" name="tipo" value="<?php echo $_POST['tipo'];?>" />
	 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
	 <input type="hidden" id="anio" name="anio" value="<?php echo $anio;?>" />
	 <input type="hidden" id="estadoReporte" name="estadoReporte" value="<?php echo $estadoReporte;?>" />
	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
</div>
<div id="tabla">
<table id="tablaReportePresupuesto" class="soloImpresion">
	<thead>
		<tr>
		    <th>EJERCICIO</th>
		    <th>ENTIDAD</th>
			<th>UNIDAD EJECUTORA</th>
			<th>UNIDAD DESCONCENTRADA</th>
			<th>PROGRAMA</th>
			<th>SUBPROGRAMA</th>
			<th>PROYECTO</th>
			<th>ACTIVIDAD</th>
			<th>OBRAS</th>
			<th>GEOGRAFICO</th>
		    <th>RENGLO</th>		    
			<th>RENGLON AUXILIAR</th>
			<th>FUENTE</th>
			<th>ORGANISMO</th>
			<th>CORRELATIVO</th>
						
			<th>CODIGO CATEGORIA CPC A NIVEL 9</th>
			<th>TIPO COMPRA (Bien, obras, servicio o consultoría)</th>
			<th>DETALLE DEL PRODUCTO (Descripción de la contratación)</th>
			<th>CANTIDAD ANUAL</th>
			<th>UNIDAD (metro, litro, etc)</th>
		    <th>COSTO UNITARIO (Dólares)</th>
		    
		    <th>REFORMA (INCREMENTO O DISMINUCION)</th>
		    <th>VALOR REFORMADO</th>
		    
			<th>CUATRIMESTRE 1	(marcar con una S en el cuatrimestre que va a contratar)</th>
			<th>CUATRIMESTRE 2	(marcar con una S en el cuatrimestre que va a contratar)</th>
			<th>CUATRIMESTRE 3	(marcar con una S en el cuatrimestre que va a contratar)</th>
						
			<th>COMENTARIO</th>			
						
			<th>TIPO DE PRODUCTO (normalizado / no normalizado)</th>
			<th>CATALOGO ELECTRÓNICO (si/no)</th>
			<th>PROCEDIMIENTO SUGERIDO (son los procedimientos de contración)</th>			
			<th>FONDOS BID (si/no)</th>
			
			<th>NUMERO CÓDIGO DE OPERACIÓN DEL PRÉSTAMO BID</th>
			<th>NUMERO CÓDIGO DE PROYECTO BID</th>
			<th>TIPO DE RÉGIMEN (común, especial)</th>
			<th>TIPO DE PRESUPUESTO (proyecto de inversión, gasto corriente)</th>
			
			<th></th>	
			<th>ID</th>
		    <th>ID Presupuesto</th>
		    
		    <th>Revisor</th>
			<th>Fecha Revisión</th>
			<th>Observaciones Revisión</th>
			
			<th>Revisor DGPGE</th>
			<th>Fecha Revisión DGPGE</th>
			<th>Observaciones Revisión DGPGE</th>
			
			<th>Revisor GA</th>
			<th>Fecha Revisión GA</th>
			<th>Observaciones Revisión GA</th>
			
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	
	 <?php
	 
	 $t_prog1=0;
	 $totalMatriz = 0;
	 $total = 0;
	 
	 $cantidadAnual=0;
	 $costo=0;
	 $reforma=0;
	 $valorReformado=0;
	 $subtotal=0;
	 
	 //Matriz completa
	 while($fila = pg_fetch_assoc($completo)){
	 	
	 	//$total = pg_fetch_result($cpp->numeroPresupuestosYCostoTotalAprobado($conexion, $fila['id_planificacion_anual']), 0, 'total');
		 	 
	 	$cantidadAnual+=$fila['cantidad_anual'];
	 	$costo+=$fila['costo_original'];
	 	$reforma+=($fila['costo']-$fila['costo_original']);
	 	$valorReformado+=$fila['costo'];
	 	$subtotal+=$fila['cantidad_anual']*$fila['costo'];
	 	
	 	$totalMatriz+=$total;
	 	
	 	$revidorDGPGE = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_aprobador_presupuesto']));
	 	$revisorPresupuestoDGPGE = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_aprobador_presupuesto']));
		 
	 	$revidorGA = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_revisor_ga']));
	 	$revisorPresupuestoGA = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_revisor_ga']));
	 		
	 	echo '<tr>
	    <td class="formatoTexto">'.$fila['ejercicio'].'</td>
        <td class="formatoTexto">'.$fila['entidad'].'</td>
        <td class="formatoTexto">'.$fila['codigo_unidad_ejecutora'].'</td>
        <td class="formatoTexto">'.$fila['codigo_unidad_desconcentrada'].'</td>
    	<td class="formatoTexto">'.$fila['programa'].'</td>
    	<td class="formatoTexto">'.$fila['subprograma'].'</td>
        <td class="formatoTexto">'.$fila['codigo_proyecto'].'</td>
        <td class="formatoTexto">'.$fila['codigo_actividad'].'</td>
        <td class="formatoTexto">'.$fila['obra'].'</td>
		<td class="formatoTexto">'.$fila['geografico'].'</td>
        <td class="formatoTexto">'.$fila['renglon'].'</td>
    	<td class="formatoTexto">'.$fila['renglon_auxiliar'].'</td>
        <td class="formatoTexto">'.$fila['fuente'].'</td>
        <td class="formatoTexto">'.$fila['organismo'].'</td>
    	<td class="formatoTexto">'.$fila['correlativo'].'</td>
    	
    	<td class="formatoTexto">'.$fila['codigo_cpc'].'</td>
        <td class="formatoTexto">'.$fila['tipo_compra'].'</td>        
        <td class="formatoTexto">'.$fila['detalle_gasto'].'</td>
        <td class="formatoTexto">'.$fila['cantidad_anual'].'</td>
        <td class="formatoTexto">'.$fila['unidad_medida'].'</td>
		<td class="formatoNumeroDecimal4">'.$fila['costo_original'].'</td>
		
		<td class="formatoNumeroDecimal4">'.($fila['costo'] - $fila['costo_original'])   /*abs($fila['costo'] - $fila['costo_original'])*/.'</td>
		<td class="formatoNumeroDecimal4">'.$fila['costo'].'</td>

        <td class="formatoTexto">'.(($fila['cuatrimestre']=="Cuatrimestre I")? 'S':'').'</td>
        <td class="formatoTexto">'.(($fila['cuatrimestre']=="Cuatrimestre II")? 'S':'').'</td>
        <td class="formatoTexto">'.(($fila['cuatrimestre']=="Cuatrimestre III")? 'S':'').'</td>
        
        <td class="formatoTexto">'. $fila['tipo_cambio'] .'</td>
        
    	<td class="formatoTexto">'.$fila['tipo_producto'].'</td>
        <td class="formatoTexto">'.$fila['catalogo_electronico'].'</td>
        <td class="formatoTexto">'.$fila['procedimiento_sugerido'].'</td>
    	<td class="formatoTexto">'.$fila['fondos_bid'].'</td>
    	
    	<td class="formatoTexto">'.$fila['operacion_bid'].'</td>
        <td class="formatoTexto">'.$fila['proyecto_bid'].'</td>
        <td class="formatoTexto">'.$fila['tipo_regimen'].'</td>        
        <td class="formatoTexto">'.$fila['nombre_tipo_presupuesto'].'</td>
        
        <td></td>
        <td>'.$fila['id_planificacion_anual'].'</td>
		<td>'.$fila['id_presupuesto'].'</td>
		
		<td>'.$fila['nombre_revisor_presupuesto'].' '.$fila['apellido_revisor_presupuesto'].'</td>
		<td>'.$fila['fecha_revision_presupuesto'].'</td>
		<td>'.$fila['observaciones_revision_presupuesto'].'</td>
		
		<td>'.$revisorPresupuestoDGPGE['nombre'].' '.$revisorPresupuestoDGPGE['apellido'].'</td>
		<td>'.$fila['fecha_aprobacion_presupuesto'].'</td>
		<td>'.$fila['observaciones_aprobacion_presupuesto'].'</td>
		
		<td>'.$revisorPresupuestoGA['nombre'].' '.$revisorPresupuestoGA['apellido'].'</td>
		<td>'.$fila['fecha_revision_ga'].'</td>
		<td>'.$fila['observaciones_revision_ga'].'</td>
		
		<td>'.$fila['estado_presupuesto'].'</td>
		</tr>';
	 }
 
	 echo '<tr>
	 <td colspan="20"></td>
	 <td class="formatoNumeroDecimal4">'.$costo.'</td>
	 <td class="formatoNumeroDecimal4">'.$reforma.'</td>
	 <td class="formatoNumeroDecimal4">'.$valorReformado.'</td>
	 <td colspan="25"></td>
	 </tr>';
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>