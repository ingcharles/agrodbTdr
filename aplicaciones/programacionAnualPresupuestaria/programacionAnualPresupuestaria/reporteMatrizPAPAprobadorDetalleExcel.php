<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorAreas.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=reporteMatrizPAP.xls");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$fecha = getdate();
$anio = $fecha['year'];

$conexion = new Conexion();
$cu = new ControladorUsuarios();
$cpp = new ControladorProgramacionPresupuestaria();
$ca = new ControladorAreas();

    $completo = $cpp->obtenerReportePlanificacionAnual($conexion, $_POST['objetivoEstrategico'], $_POST['areaN2'], 
			$_POST['objetivoEspecifico'], $_POST['areaN4'], $_POST['objetivoOperativo'], $_POST['gestion'], $_POST['proceso'], 
			$_POST['componente'], $_POST['actividad'], $_POST['provincia'], $anio, $_POST['estado'], $_POST['tipo']);
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
							Programación Anual Presupuestaria <?php echo $anio;?><br>
	</div>
	<div id="direccion"></div>
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
			<td>'.$aprobador['nombre'].' '.$aprobador['apellido'].'</td>>
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