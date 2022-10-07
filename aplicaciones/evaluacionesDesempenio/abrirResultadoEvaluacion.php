<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

$conexion = new Conexion();
$ced = new ControladorEvaluacionesDesempenio();

//Cargar un arreglo de preguntas y otro de opciones

$idEvaluacion=$_POST['id'];
$identificador=$_SESSION['usuario'];

try {
$idTipoEvaluacion=pg_fetch_result($ced->devolverEvaluacion ($conexion,$idEvaluacion),0,'id_tipo');	
$qTipoEvaluacion = $ced->listarTipoEvaluacion($conexion, $idTipoEvaluacion);

$evaluacionSuperior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'superior'));
$evaluacionInferior = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'inferior'));
$evaluacionPares = pg_fetch_assoc($ced-> abrirTipoEvaluacion($conexion, $idTipoEvaluacion, 'pares'));

$competenciasSuperior = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $identificador, $evaluacionSuperior['id_tipo_evaluacion'],$idEvaluacion));
$competenciasInferior = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $identificador, $evaluacionInferior['id_tipo_evaluacion'],$idEvaluacion));
$competenciasPares = pg_num_rows($ced->verificarCompetenciasConductuales($conexion, $identificador, $evaluacionPares['id_tipo_evaluacion'],$idEvaluacion));

$bandera=1;
$ponderacionSuperior=0;
$ponderacionInferior=0;
$ponderacionPares=0;
$nivel=0;

//---------------no tiene subordinados - pares-------uno---------------
if($competenciasInferior == 0 and $competenciasPares == 0 ){
	$bandera=0; $nivel=1;
	// jefe inmediato 35%
	$ponderacionSuperior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'uno', $idTipoEvaluacion,'superior'),0,'ponderacion');
}
//---------------no tiene jefe inmediato - pares------dos---------------
if($competenciasSuperior == 0 and $competenciasPares == 0 and $bandera){
	$bandera=0; $nivel=2;
	// subordinado 35%
	$ponderacionInferior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'dos', $idTipoEvaluacion,'inferior'),0,'ponderacion');
}
//---------------no tiene jefe inmediato---------------tres--------------
if($competenciasSuperior == 0 and $bandera ){
	$bandera=0; $nivel=3;
	// subordinario 20%
	$ponderacionInferior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'tres', $idTipoEvaluacion,'inferior'),0,'ponderacion');
}
//---------------no tiene subordinado------------------cuatro--------------
if($competenciasInferior == 0 and $bandera){
	$bandera=0; $nivel=4;
	// jefe inmediato 15%
	$ponderacionSuperior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cuatro', $idTipoEvaluacion,'superior'),0,'ponderacion');
	// pares 20%
	$ponderacionPares = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cuatro', $idTipoEvaluacion,'pares'),0,'ponderacion');
}
//---------------no tiene pares------------------------cinco--------------
if($competenciasPares == 0 and $bandera){
	$nivel=5;
	// jefe inmediato 20%
	$ponderacionSuperior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cinco', $idTipoEvaluacion,'superior'),0,'ponderacion');
	// subordinado 15%
	$ponderacionInferior = pg_fetch_result($ced->obternerPonderacionCompetencias($conexion, 'cinco', $idTipoEvaluacion,'inferior'),0,'ponderacion');
}


$qResultado = $ced->listarResultadoEvaluacionIndividual($conexion,$idTipoEvaluacion,$idEvaluacion,$identificador,$ponderacionSuperior,$ponderacionInferior,$ponderacionPares);
$resultado=pg_fetch_assoc($qResultado);

}catch (Exception $ex){
	echo $ex;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Resultados de Evaluación</h1>
	</header>
    <fieldset>
    <legend> Resultados individual</legend>
    <table>
    <th> Puntaje <br>Óptimo </th>
    <th>Tipo de Evaluación</th>
    <th> Puntaje <br>Alcanzado </th>
    <th> Resultado </th>
    <?php 
    echo $nivel.'<br>';
	while($tipoEvaluacion=pg_fetch_assoc($qTipoEvaluacion)){
		
		switch($tipoEvaluacion['tipo']){
		case 'superior':
					$tipo  = 'superior';
					$tipoPond  = 'superiorponderacion';
						
					if( $nivel==3)$tipo  = 'no existe';
					if( $nivel==2)$tipo  = 'no existe';
					break;
		case 'inferior':
					$tipo  = 'inferior';
					$tipoPond  = 'inferiorponderacion';
					if( $nivel==1)$tipo  = 'no existe';
					if( $nivel==4)$tipo  = 'no existe';
					//if($nivel!=1 or $nivel!=4)
					break;
		case 'pares':
					$tipo  = 'pares';
					$tipoPond  = 'paresponderacion';
					if( $nivel==1)$tipo  = 'no existe';
					if( $nivel==2)$tipo  = 'no existe';
					if( $nivel==5)$tipo  = 'no existe';
					//if($nivel!=1 or $nivel!=2 or $nivel!=5)
					break;
		case 'autoevaluacion':
					$tipoPond='autoevaluacionponderacion';
					$tipo  = 'autoevaluacion';
					break;
		case 'individual':
					$tipoPond='individualponderacion';
					$tipo  = 'individual';
					break;
		default:
			'no existe';					
		}
		
		if($tipo != 'no existe'){
			echo' <tr align="">
				 <td>'.$resultado[$tipoPond].'</td>
    			 <td>'.$tipoEvaluacion['nombre'].'</td>
				 <td>'.number_format(($resultado[$tipo]*$resultado[$tipoPond])/100,2).' % </td>
			     <td class="barra1"><div class="medida"> <span class="porcentual" data-valor="'.number_format($resultado[$tipo],2).'"> &nbsp;</span></div></td>
			  </tr>';
		}
	}
	if(number_format($resultado['cumplimiento'],2)!=0 and $idEvaluacion > 2)
		echo' <tr align="">
		<td>'.$resultado['cumplimientoponderacion'].'</td>
		<td>Indicadores Generales</td>
		<td>'.number_format(($resultado['cumplimiento']*$resultado['cumplimientoponderacion']/100),2).' % </td>
		<td class="barra1"><div class="medida"> <span class="porcentual" data-valor="'.number_format($resultado['cumplimiento'],2).'"> &nbsp;</span></div></td>
		</tr>';
	if(number_format($resultado['total'],2)!=0)
	echo' <tr align="">
				 <td> </td>
    			 <td>Total</td>
				 <td>'.number_format($resultado['total'],2).' %</td>
			     <td class="barra1"><div class="medida"> <span class="porcentual" data-valor="'.number_format($resultado['total'],2).'"> &nbsp;</span></div></td>
			  </tr>';
	
	?>
    </table> </fieldset>
	

</body>
		<script type="text/javascript">
				
				$(function() {
			
			$(".porcentual").each(function() {
				//poner valores
				$(this).attr("style","width:" + $(this).attr("data-valor") + "%")
				
				if ($(this).attr("data-valor")<=50)
					$(this).addClass("rojo");
					
				else if ($(this).attr("data-valor")<=75)
					$(this).addClass("amarillo");
					
				else if ($(this).attr("data-valor")<=95)
					$(this).addClass("celeste");
					
				else 
					$(this).addClass("verde")
				
				$(this)
					.data("origWidth", $(this).width())
					.width(0)
					.animate({
						width: $(this).data("origWidth")
					}, 1200);
			});
		});
				
	</script>
</html>
