<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/Constantes.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorAreas.php';

$fecha = getdate();
$anio = $fecha['year'];

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
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

	$completo = $cpp->obtenerReportePresupuestos($conexion, $_POST['areaN2'], $_POST['areaN4Reporte'], $_POST['gestion'], 
													$_POST['proceso'], $_POST['componente'], $_POST['actividad'], 
													$_POST['tipo'], $_POST['provincia'], $anio, $_POST['estado']);
	
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
							Programación Anual Presupuestaria - Presupuesto Asignado <?php echo $anio;?><br>
	</div>
	<div id="direccion"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteMatrizPresupuestosAprobadorDetalleExcel.php" target="_blank" method="post">
	 <input type="hidden" id="areaN2" name="areaN2" value="<?php echo $_POST['areaN2'];?>" />
	 <input type="hidden" id="areaN4" name="areaN4" value="<?php echo $_POST['areaN4Reporte'];?>" />
	 <input type="hidden" id="gestion" name="gestion" value="<?php echo $_POST['gestionReporte'];?>" />
	 <input type="hidden" id="proceso" name="proceso" value="<?php echo $_POST['procesoReporte'];?>" />
	 <input type="hidden" id="componente" name="componente" value="<?php echo $_POST['componenteReporte'];?>" />
	 <input type="hidden" id="actividad" name="actividad" value="<?php echo $_POST['actividadReporte'];?>" />
	 <input type="hidden" id="tipo" name="tipo" value="<?php echo $_POST['tipo'];?>" />
	 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
	 <input type="hidden" id="anio" name="anio" value="<?php echo $anio;?>" />
	 <input type="hidden" id="restado" name="estado" value="<?php echo $_POST['estado'];?>" />
	 <button type="submit" class="guardar">Imprimir</button>	  	 
	</form>
	</div>
	<div id="bandera"></div>
</div>
<div id="tabla">
<table id="tablaReportePresupuesto" class="soloImpresion">
	<thead>
		<tr>
		    <th>ID</th>
		    <th>N2</th>
			<th>N4</th>
			<th>Unidad</th>
			<th>Tipo</th>
			<th>Proceso/Proyecto</th>			
			<th>Componente</th>
			<th>Actividad</th>
			<th>Producto Final</th>
			<th>Provincia</th>
			<th>Total Presupuesto</th>
			<th>Revisor</th>
			<th>Fecha Revisión</th>
			<th>Observaciones Revisión</th>
			<th>Aprobador</th>
			<th>Fecha Aprobación</th>
			<th>Observaciones Aprobación</th>
			<th>Estado</th>
			
			<th>ID Presupuesto</th>
			<th>ID Planificación Asignada</th>
		    <th>Ejercicio</th>
			<th>Unidad Ejecutora</th>
			<th>Código</th>
			<th>Unidad Desconcentrada</th>
			<th>Código</th>			
			<th>Programa</th>
			<th>Subprograma</th>
			<th>Proyecto</th>
			<th>Actividad</th>
			<th>Obra</th>
			<th>Geográfico</th>
		    <th>Renglón</th>		    
			<th>Renglón Auxiliar</th>
			<th>Fuente</th>
			<th>Organismo</th>
			<th>Correlativo</th>			
			<th>CPC</th>
			<th>Código CPC</th>
			<th>Tipo de Compra</th>
			<th>Detalle del Gasto</th> 
			<th>Cantidad Anual</th>
			<th>Unidad de Medida</th>
		    <th>Costo sin IVA</th>
		    <th>IVA</th>
		    <th>Costo con IVA</th>
			<th>Cuatrimestre</th>			
			<th>Tipo de Producto</th>
			<th>Catálogo Electrónico</th>
			<th>Procedimiento Sugerido</th>			
			<th>Fondos BID</th>
			<th>Operación BID</th>
			<th>Proyecto BID</th>
			<th>Tipo de Régimen</th>
			<th>Tipo de Presupuesto</th>			
			<th>Revisor</th>
			<th>Fecha Revisión</th>
			<th>Observaciones Revisión</th>
			<th>Aprobador</th>
			<th>Fecha Aprobación</th>
			<th>Observaciones Aprobación</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	
	 <?php
	 
	 $t_prog1=0;
	 $totalMatriz = 0;
	 $total = 0;
	 
	 $$cantidadAnual=0;
	 $costo=0;
	 $subtotal=0;
	 
	 //Matriz completa
	 while($fila = pg_fetch_assoc($completo)){
	 	
	 	$total = pg_fetch_result($cpp->numeroPresupuestosYCostoTotalAprobadoIva($conexion, $fila['id_planificacion_anual']), 0, 'total');
		 	 
	 	$cantidadAnual+=$fila['cantidad_anual'];
	 	$costo+=$fila['costo'];
	 	$subtotalIva+=$fila['cantidad_anual']*$fila['costo_iva'];
	 	$subtotal+=$fila['cantidad_anual']*$fila['costo'];
	 	
	 	$totalMatriz+=$total;
	 	
	 	$aprobador = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_aprobador']));
	 	$aprobadorPresupuesto = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_aprobador_presupuesto']));
		 
	 	echo '<tr>
	    <td>'.$fila['id_planificacion_anual'].'</td>
		<td>'.$fila['area_n2'].'</td>
        <td>'.$fila['area_n4'].'</td>
    	<td>'.$fila['gestion'].'</td>
        <td>'.$fila['tipo'].'</td>
        <td>'.$fila['proceso_proyecto'].'</td>
    	<td>'.$fila['componente'].'</td>
    	<td>'.$fila['actividad'].'</td>
        <td>'.$fila['producto_final'].'</td>
        <td>'.$fila['provincia'].'</td>
        <td>'.$total.'</td>
        <td>'.$fila['nombre_revisor'].' '.$fila['apellido_revisor'].'</td>
		<td>'.$fila['fecha_revision'].'</td>
		<td>'.$fila['observaciones_revision'].'</td>
		<td>'.$aprobador['nombre'].' '.$aprobador['apellido'].'</td>
		<td>'.$fila['fecha_aprobacion'].'</td>
		<td>'.$fila['observaciones_aprobacion'].'</td>
		<td>'.$fila['estado'].'</td>
		
		<td>'.$fila['id_presupuesto'].'</td>
		<td>'.$fila['id_planificacion_anual_presupuesto'].'</td>
		<td>'.$fila['ejercicio'].'</td>
        <td>'.$fila['unidad_ejecutora'].'</td>
    	<td>'.$fila['codigo_unidad_ejecutora'].'</td>
        <td>'.$fila['unidad_desconcentrada'].'</td>
        <td>'.$fila['codigo_unidad_desconcentrada'].'</td>
    	<td>'.$fila['programa'].'</td>
    	<td>'.$fila['subprograma'].'</td>
        <td>'.$fila['codigo_proyecto'].'</td>
        <td>'.$fila['codigo_actividad'].'</td>
        <td>'.$fila['obra'].'</td>
		<td>'.$fila['geografico'].'</td>
        <td>'.$fila['renglon'].'</td>
    	<td>'.$fila['renglon_auxiliar'].'</td>
        <td>'.$fila['fuente'].'</td>
        <td>'.$fila['organismo'].'</td>
    	<td>'.$fila['correlativo'].'</td>
    	<td>'.$fila['nombre_cpc'].'</td>
        <td>'.$fila['codigo_cpc'].'</td>
        <td>'.$fila['tipo_compra'].'</td>        
        <td>'.$fila['detalle_gasto'].'</td>
        <td>'.$fila['cantidad_anual'].'</td>
        <td>'.$fila['unidad_medida'].'</td>
		<td>'.$fila['costo'].'</td>
		<td>'.$fila['iva'].'</td>
		<td>'.$fila['costo_iva'].'</td>
        <td>'.$fila['cuatrimestre'].'</td>
    	<td>'.$fila['tipo_producto'].'</td>
        <td>'.$fila['catalogo_electronico'].'</td>
        <td>'.$fila['procedimiento_sugerido'].'</td>
    	<td>'.$fila['fondos_bid'].'</td>
    	<td>'.$fila['operacion_bid'].'</td>
        <td>'.$fila['proyecto_bid'].'</td>
        <td>'.$fila['tipo_regimen'].'</td>        
        <td>'.$fila['nombre_tipo_presupuesto'].'</td>
		<td>'.$fila['nombre_revisor_presupuesto'].' '.$fila['apellido_revisor_presupuesto'].'</td>
		<td>'.$fila['fecha_revision_presupuesto'].'</td>
		<td>'.$fila['observaciones_revision_presupuesto'].'</td>
		<td>'.$aprobadorPresupuesto['nombre'].' '.$aprobadorPresupuesto['apellido'].'</td>
		<td>'.$fila['fecha_aprobacion_presupuesto'].'</td>
		<td>'.$fila['observaciones_aprobacion_presupuesto'].'</td>
		<td>'.$fila['estado_presupuesto'].'</td>
		</tr>';
	 }
 
	 echo '<tr>
				 <td colspan="42"></td>
				 <td>'.$subtotal.'</td>
				 <td colspan="1"></td>
				 <td>'.$subtotalIva.'</td>
				 <td colspan="16"></td>
			</tr>';
	 ?>
	
	</tbody>
</table>
</div>
</body>
</html>