<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorAreas.php';

$fecha = getdate();
$anio = $fecha['year'];

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
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

    /*$completo =$cd->sacarReporteMatrizPOA($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'],$_POST['fi'],$_POST['ff'],$_POST['codigo_Indicador'],$_POST['listaCobertura'],$_POST['listaPoblacion'],$_POST['ListaResponsable'],$_POST['listaVerificacion'], $fecha['year']);
	$presupuesto = $cd->sacarReporteActividadesPresupuesto($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'], $fecha['year']);
	$actividades = $cd->sacarReporteActividades($conexion,$_POST['areaDireccion'],$_POST['listaObjetivoEstrategico'],$_POST['listaProcesos'],$_POST['listaSubprocesos'],$_POST['listaComponentes'],$_POST['listaActividades'], $fecha['year']);*/

	$completo = $cpp->obtenerReportePlanificacionAnual($conexion, $_POST['objetivoEstrategico'], $_POST['areaN2'], 
			$_POST['objetivoEspecificoReporte'], $_POST['areaN4Reporte'], $_POST['objetivoOperativo'], $_POST['gestion'], $_POST['proceso'], 
			$_POST['componente'], $_POST['actividad'], $_POST['provincia'], $anio, $_POST['estado'], $_POST['tipo']);

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
							Programación Anual Presupuestaria <?php echo $anio;?><br>
	</div>
	<div id="direccion"></div>
	<div id="imprimir">
	<form id="filtrar" action="reporteMatrizPAPAprobadorDetalleExcel.php" target="_blank" method="post">
	 <input type="hidden" id="objetivoEstrategico" name="objetivoEstrategico" value="<?php echo $_POST['objetivoEstrategico'];?>" />
	 <input type="hidden" id="areaN2" name="areaN2" value="<?php echo $_POST['areaN2'];?>" />
	 <input type="hidden" id="objetivoEspecifico" name="objetivoEspecifico" value="<?php echo $_POST['objetivoEspecificoReporte'];?>" />
	 <input type="hidden" id="areaN4" name="areaN4" value="<?php echo $_POST['areaN4Reporte'];?>" />
	 <input type="hidden" id="objetivoOperativo" name="objetivoOperativo" value="<?php echo $_POST['objetivoOperativoReporte'];?>" />
	 <input type="hidden" id="gestion" name="gestion" value="<?php echo $_POST['gestionReporte'];?>" />
	 <input type="hidden" id="proceso" name="proceso" value="<?php echo $_POST['procesoReporte'];?>" />
	 <input type="hidden" id="componente" name="componente" value="<?php echo $_POST['componenteReporte'];?>" />
	 <input type="hidden" id="actividad" name="actividad" value="<?php echo $_POST['actividadReporte'];?>" />
	 <input type="hidden" id="provincia" name="provincia" value="<?php echo $_POST['provincia'];?>" />
	 <input type="hidden" id="anio" name="anio" value="<?php echo $anio;?>" />
	 <input type="hidden" id="estado" name="estado" value="<?php echo $_POST['estado'];?>" />
	 <input type="hidden" id="tipo" name="tipo" value="<?php echo $_POST['tipo'];?>" />
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
		    <th>Objetivo Estratégico</th>
			<th>N2</th>
			<th>Objetivo Específico</th>
			<th>N4</th>
			<th>Objetivo Operativo</th>
			<th>Unidad</th>
			<th>Tipo</th>
			<th>Proceso/Proyecto</th>			
			<th>Componente</th>
			<th>Actividad</th>
			<th>Producto Final</th>
			<th>Provincia</th>
			<th>Cantidad de Usuarios</th>
			<th>Población Objetivo</th>
			<th>Medio de Verificación</th>
			<th>Responsable</th>
			<th>Total Presupuesto</th>
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
	 
	 //Matriz completa
	 while($fila = pg_fetch_assoc($completo)){
	 	
	 	$total = pg_fetch_result($cpp->numeroPresupuestosYCostoTotalAprobadoIva($conexion, $fila['id_planificacion_anual']), 0, 'total');
		 	 
	 	$totalMatriz+=$total;
	 	
		$aprobador = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_aprobador']));
	 	
	 	echo '<tr>
	    <td>'.$fila['id_planificacion_anual'].'</td>
		<td>'.$fila['objetivo_estrategico'].'</td>
		<td>'.$fila['area_n2'].'</td>
        <td>'.$fila['objetivo_especifico'].'</td>
        <td>'.$fila['area_n4'].'</td>
    	<td>'.$fila['objetivo_operativo'].'</td>
    	<td>'.$fila['gestion'].'</td>
        <td>'.$fila['tipo'].'</td>
        <td>'.$fila['proceso_proyecto'].'</td>
    	<td>'.$fila['componente'].'</td>
    	<td>'.$fila['actividad'].'</td>
        <td>'.$fila['producto_final'].'</td>
        <td>'.$fila['provincia'].'</td>
        <td>'.$fila['cantidad_usuarios'].'</td>
        <td>'.$fila['poblacion_objetivo'].'</td>
        <td>'.$fila['medio_verificacion'].'</td>
        <td>'.$fila['nombre_responsable'].'</td>
        <td>'.$total.'</td>
        <td>'.$fila['nombre_revisor'].' '.$fila['apellido_revisor'].'</td>
		<td>'.$fila['fecha_revision'].'</td>
		<td>'.$fila['observaciones_revision'].'</td>
		<td>'.$aprobador['nombre'].' '.$aprobador['apellido'].'</td>
		<td>'.$fila['fecha_aprobacion'].'</td>
		<td>'.$fila['observaciones_aprobacion'].'</td>
		<td>'.$fila['estado'].'</td>
		</tr>';
	 }
 
	 echo '<tr>
	 <td colspan="17"></td>
	 <td>'.$totalMatriz.'</td>
	 <td colspan="7"></td>
	 </tr>';
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>