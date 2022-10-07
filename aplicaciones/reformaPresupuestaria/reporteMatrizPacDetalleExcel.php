<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorAreas.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=reporteMatrizPAC.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$fecha = getdate();
$anio = $fecha['year'];

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
$ca = new ControladorAreas();

/*$completo = $cpp->obtenerReportePac($conexion, $_POST['areaN2'], $_POST['proceso'], $_POST['actividad'], 
													$_POST['tipo'], $_POST['provincia'], $anio, 'aprobado');*/

/*$completo = $cpp->obtenerReportePac($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'], $_POST['codigoProyectoPAC'],
		$_POST['codigoActividadPAC'], $_POST['provincia'], $anio, 'aprobado');*/

	$nombreArea = htmlspecialchars ($_POST['nombreAreaN2'],ENT_NOQUOTES,'UTF-8');

	if($nombreArea == 'Fortalecimiento Institucional'){
		$completo = $cpp->obtenerReportePacFortalecimiento($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'], $_POST['codigoProyectoPAC'], 
													$_POST['codigoActividadPAC'], $_POST['provincia'], $anio, 'aprobado');
	}else{
		$completo = $cpp->obtenerReportePac($conexion, $_POST['areaN2'], $_POST['codigoProgramaPAC'], $_POST['codigoProyectoPAC'],
				$_POST['codigoActividadPAC'], $_POST['provincia'], $anio, 'aprobado');
	}
	
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


</style>
</head>
<body>

<div id="tablaHeader">
	<table id="tablaReportePac" class="soloImpresion">
		<thead>
			<tr>
			    <td colspan="32" id="textoTitulo">PLAN ANUAL DE COMPRAS</td>
			</tr>
			<tr>
			    <td colspan="32">Por favor no modifique la estructura del archivo para subir al sistema USHAY - Módulo Facilitador de Contratación Pública</td>
			</tr>			
			<tr>
			    <td>RUC_ENTIDAD</td>
			    <td class="formatoTexto">1768105720001</td>
			</tr>
		</thead>
		<tbody>
			 <tr>
				 <td colspan="15" id="textoSubtitulo">INFORMACION DE LA PARTIDA PRESUPUESTARIA</td>
				 <td colspan="17" id="textoSubtitulo">INFORMACION DETALLADA DE LOS PRODUCTOS</td>
			 </tr>
		</tbody>
	</table>
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
			
			<!-- th></th>	
			<th>ID</th>
		    <th>ID Presupuesto</th>
		    <th>Revisor</th>
			<th>Fecha Revisión</th>
			<th>Observaciones Revisión</th>
			<th>Aprobador</th>
			<th>Fecha Aprobación</th>
			<th>Observaciones Aprobación</th>
			<th>Estado</th-->
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
		 	 
	 	$cantidadAnual+=$fila['cantidad_anual'];
	 	$costo+=$fila['costo'];
	 	$subtotal+=$fila['cantidad_anual']*$fila['costo'];
	 	
	 	$totalMatriz+=$total;
	 	
	 	$aprobador = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_aprobador']));
	 	$aprobadorPresupuesto = pg_fetch_assoc($cu->obtenerNombresUsuario($conexion, $fila['identificador_aprobador_presupuesto']));
		 
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
		<td class="formatoNumeroDecimal4">'.$fila['costo'].'</td>

        <td class="formatoTexto">'.(($fila['cuatrimestre']=="Cuatrimestre I")? 'S':'').'</td>
        <td class="formatoTexto">'.(($fila['cuatrimestre']=="Cuatrimestre II")? 'S':'').'</td>
        <td class="formatoTexto">'.(($fila['cuatrimestre']=="Cuatrimestre III")? 'S':'').'</td>
        
        <td class="formatoTexto">'. $fila['tipo_cambio'].' - '. $fila['numero_cur'] .'</td>
        
    	<td class="formatoTexto">'.$fila['tipo_producto'].'</td>
        <td class="formatoTexto">'.$fila['catalogo_electronico'].'</td>
        <td class="formatoTexto">'.$fila['procedimiento_sugerido'].'</td>
    	<td class="formatoTexto">'.$fila['fondos_bid'].'</td>
    	
    	<td class="formatoTexto">'.$fila['operacion_bid'].'</td>
        <td class="formatoTexto">'.$fila['proyecto_bid'].'</td>
        <td class="formatoTexto">'.$fila['tipo_regimen'].'</td>        
        <td class="formatoTexto">'.$fila['nombre_tipo_presupuesto'].'</td>
        
        <!--td></td>
        <td>'.$fila['id_planificacion_anual'].'</td>
		<td>'.$fila['id_presupuesto'].'</td>
		<td>'.$fila['nombre_revisor_presupuesto'].' '.$fila['apellido_revisor_presupuesto'].'</td>
		<td>'.$fila['fecha_revision_presupuesto'].'</td>
		<td>'.$fila['observaciones_revision_presupuesto'].'</td>
		<td>'.$aprobadorPresupuesto['nombre'].' '.$aprobadorPresupuesto['apellido'].'</td>
		<td>'.$fila['fecha_aprobacion_presupuesto'].'</td>
		<td>'.$fila['observaciones_aprobacion_presupuesto'].'</td>
		<td>'.$fila['estado_presupuesto'].'</td-->
		</tr>';
	 }
 
	 /*echo '<tr>
	 <td colspan="20"></td>
	 <td class="formatoNumeroDecimal4">'.$costo.'</td>
	 <td colspan="21"></td>
	 </tr>';*/
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>