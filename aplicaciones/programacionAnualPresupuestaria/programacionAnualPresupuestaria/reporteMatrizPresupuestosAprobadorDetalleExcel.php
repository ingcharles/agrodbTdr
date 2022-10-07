<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorAreas.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=reporteMatrizPresupuesto.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$fecha = getdate();
$anio = $fecha['year'];

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
$ca = new ControladorAreas();

$completo = $cpp->obtenerReportePresupuestos($conexion, $_POST['areaN2'], $_POST['areaN4Reporte'], $_POST['gestion'], 
											$_POST['proceso'], $_POST['componente'], $_POST['actividad'], 
											$_POST['tipo'], $_POST['provincia'], $anio, $_POST['estado']);
	
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
border:1px solid #98bf21;
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

#logoMagap{
width: 15%;
height:70px;
background-image: url(imgPOA/magap_logo.jpg); background-repeat: no-repeat;
float: left;
}

#logotexto{
width: 10%;
height:80px;
float: left;
}

#logoAgrocalidad{
width: 20%;
height:80px;
background-image: url(imgPOA/agrocalidad.png); background-repeat: no-repeat;
float:left;
}

#textoPOA{
width: 40%;
height:80px;
text-align: center;
float:left;
}

#direccion{
width: 10%;
height:80px;
background-image: url(imgPOA/direccion.png); background-repeat: no-repeat;
float: left;
}

#bandera{
width: 5%;
height:80px;
background-image: url(imgPOA/bandera.png); background-repeat: no-repeat;
float: right;
}



</style>
</head>
<body>
<div id="header">
   	<div id="logoMagap"></div>
	<div id="texto"></div>
	<div id="logoAgrocalidad"></div>
	<div id="textoPOA">Ministerio de Agricultura, Ganaderia, Acuacultura y Pesca<Br>
				Agencia Ecuatoriana de Aseguramiento de la Calidad del Agro Agrocalidad<Br>
							Programación Anual Presupuestaria - Presupuesto Asignado <?php echo $anio;?><br>
	</div>
	<div id="direccion"></div>
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
		 	
		 	$total = pg_fetch_result($cpp->numeroPresupuestosYCostoTotalAprobado($conexion, $fila['id_planificacion_anual']), 0, 'total');
			 	 
		 	$totalMatriz+=$total;
		 	
		 	$cantidadAnual+=$fila['cantidad_anual'];
		 	$costo+=$fila['costo'];
		 	$subtotalIva+=$fila['cantidad_anual']*$fila['costo_iva'];
		 	$subtotal+=$fila['cantidad_anual']*$fila['costo'];
		 	
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
				 <td colspan="14"></td>
				 </tr>';
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>