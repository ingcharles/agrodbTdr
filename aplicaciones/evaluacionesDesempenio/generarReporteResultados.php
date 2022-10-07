<?php 
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorAreas.php';


$idEvaluacion = $_POST['idEvaluacion'];
$tipo = $_POST['tipo'];
$provincia = $_POST['provincia'];
$fecha=date("Y-m-d");
$nombreArchivo='Resultado_servidores_'.$provincia.'_'.$fecha.'.xls';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=$nombreArchivo");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$ced = new ControladorEvaluacionesDesempenio();
$conexion = new Conexion();
$ca = new ControladorAreas();

$qFuncionarios = $ced->listarAplicantesEvaluacion($conexion, $idEvaluacion);
$idTipoEvaluacion=pg_fetch_result($ced->devolverEvaluacion ($conexion,$idEvaluacion),0,'id_tipo');

$evaluacionSuperior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'superior'));
$evaluacionInferior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'inferior'));
$evaluacionPares = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'pares'));
if($provincia == 'Todas')$provincia='';

$resultadoEvaluacion = $ced->listaResultadoEvaluacionNotas($conexion, $idEvaluacion,$provincia);
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

</style>
</head>
<body>


<div id="tabla">
<table id="tablaReporte" class="soloImpresion">
	<thead>
		<tr>
			<th>Provincia</th>
		    <th>Área</th>
		    <th>Lugar</th>
		    <th>Nombres</th>
			<th>Cédula</th>
			<th>Jefe Directo</th>
			<th>Funcionarios a Cargo</th>
			<th>Pares</th>
			<th>Autoevaluacion</th>
			<th>Individual</th>
			<th>Cumplimiento</th>
			<th>Total</th>
			
		</tr>
	</thead>
	<tbody>
	 <?php
	 
	 While($fila = pg_fetch_assoc($resultadoEvaluacion)) {

if(1){
	 	$nombrePadre = pg_fetch_result($ca->buscarPadreSubprocesos($conexion,$fila['id_area_cumplimiento']), 0, 'nombre');
	 	
	 	$competenciasSuperior = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $fila['identificador'], $evaluacionSuperior['id_tipo_evaluacion'],$idEvaluacion));
	 	$competenciasInferior = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $fila['identificador'], $evaluacionInferior['id_tipo_evaluacion'],$idEvaluacion));
	 	$competenciasPares = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $fila['identificador'], $evaluacionPares['id_tipo_evaluacion'],$idEvaluacion));
	 	
	 	$bandera=1;
	 	$resultadoSuperior = $fila['resultado_superior'];
	 	$resultadoInferior = $fila['resultado_inferior'];
	 	$resultadoPares = $fila['resultado_par'];
	 	//---------------no tiene subordinados - pares-------uno---------------
	 	if($competenciasInferior == 0 and $competenciasPares == 0 ){
	 		$bandera=0;
	 		$resultadoInferior='N/A';
	 		$resultadoPares='N/A';
	 	}
	 	//---------------no tiene jefe inmediato - pares------dos---------------
	 	if($competenciasSuperior == 0 and $competenciasPares == 0 and $bandera ){
	 		$bandera=0;
	 		$resultadoSuperior='N/A';
	 		$resultadoPares='N/A';
	 	}
	 	//---------------no tiene jefe inmediato---------------tres--------------
	 	if($competenciasSuperior == 0 and $bandera ){
	 		$bandera=0;
	 		$resultadoSuperior='N/A';
	 	}
	 	//---------------no tiene subordinado------------------cuatro--------------
	 	if($competenciasInferior == 0 and $bandera){
	 		$bandera=0;
	 		$resultadoInferior='N/A';
	 	}
	 	//---------------no tiene pares------------------------cinco--------------
	 	if($competenciasPares == 0 and $bandera){
	 		$resultadoPares='N/A';
	 	}
	 	
		echo '<tr>
		    <td>'.$provincia.'</td>
			<td>'.$fila['nombre_area'].'</td>
			<td>'.$nombrePadre.'</td>
			<td>'.$fila['nombres'].'</td>
			<td class="formato">'.$fila['identificador'].'</td>
	        <td>'.$resultadoSuperior.'</td>
	        <td>'.$resultadoInferior.'</td>
			<td>'.$resultadoPares.'</td>
	        <td>'.$fila['resultado_autoevaluacion'].'</td>
	        <td>'.$fila['resultado_individual'].'</td>
	        <td>'.$fila['resultado_cumplimiento'].'</td>
	    	<td>'.$fila['total'].'</td>
    	</tr>';
	 }
	 }
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>


