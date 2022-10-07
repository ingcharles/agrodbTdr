<?php 
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorAreas.php';

$idEvaluacion = $_POST['idEvaluacion'];
$tipo = $_POST['tipo'];
$provincia = $_POST['provincia'];
$fecha=date("Y-m-d");
$nombreArchivo='Servidores_con_evaluaciones_pendientes_'.$provincia.'_'.$fecha.'.xls';
header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=$nombreArchivo");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$ced = new ControladorEvaluacionesDesempenio();
$conexion = new Conexion();
$ca = new ControladorAreas();

if($provincia == 'Todas')$provincia='';
$pendient=$ced->abrirEvaluacionPendienteUsuario ($conexion, '','',$idEvaluacion,$provincia);

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
		text-align:center;
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
		
		.colorCelda{
			background-color: #dddfff;
		}
	</style>

</head>
<body>


<div id="tabla">
<table id="tablaReporte" class="soloImpresion">
	<thead>
	<tr >
		<th colspan=10>Reporte de Servidores con Evaluaciones Pendientes</th>
	</tr>
	<tr >
		<th></th><th colspan=3 bgcolor="#00FF00" >Información del Funcionario</th><th colspan=3 bgcolor="#FF00FF">Evaluaciones Pendientes</th>
		<th colspan=3 bgcolor= "#800080">Estructura Organizacional</th>
		</tr>
		<tr>
			<th>Num</th>
			<th>Identificador</th>
			<th>Nombres</th>
			<th>Apellidos</th>
			
			<th>Cantidad</th>
			<th>Tipo</th>
			<th>Funcionario a Evaluar</th>
		    
		    <th>Coordinación</th>
		    <th>Dirección</th>
		    <th>Gestión</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 $contador=0;  $ctrColor=1;
	 While($fila = pg_fetch_assoc($pendient)) {
	
	 if($identifi != $fila['identificador_evaluador']){
	  	
	 $result=pg_fetch_assoc($ced->obtenerExcepcionesFuncionarios($conexion,$fila['identificador_evaluador']));
	 
	 $lista=$ced->filtrarFuncionariosPendientes($conexion, $fila['identificador_evaluador'], $idEvaluacion);	
	 $numS=$numI=$numP=$numA=$numX=0;
	 $tipoS = array ();
	 $tipoI = array ();
	 $tipoP = array ();
	 $tipoA = array ();
	 $tipoX = array ();
	 
	
	 while($listas = pg_fetch_assoc($lista)){
		
	 	if($listas['tipo']=='superior'){ 
					++$numS;
					$tipoS[$numS][]='Superior';
					$tipoS[$numS][]=$listas['nombres_completos'];
					$tipoS[$numS][]=$listas['coordinacion'];
					$tipoS[$numS][]=$listas['direccion'];
					$tipoS[$numS][]=$listas['gestion'];
				}
	 	if($listas['tipo']=='inferior'){
					++$numI; 
					$tipoI[$numI][]='Inferior';
					$tipoI[$numI][]=$listas['nombres_completos'];
					$tipoI[$numI][]=$listas['coordinacion'];
					$tipoI[$numI][]=$listas['direccion'];
					$tipoI[$numI][]=$listas['gestion'];
				}
	 	if($listas['tipo']=='pares'){
					++$numP;
					$tipoP[$numP][]='Pares';
					$tipoP[$numP][]=$listas['nombres_completos'];
					$tipoP[$numP][]=$listas['coordinacion'];
					$tipoP[$numP][]=$listas['direccion'];
					$tipoP[$numP][]=$listas['gestion'];
				}
	 	if($listas['tipo']=='autoevaluacion'){
					++$numA;
					$tipoA[$numA][]='Autoevaluacion';
					$tipoA[$numA][]=$listas['nombres_completos'];
					$tipoA[$numA][]=$listas['coordinacion'];
					$tipoA[$numA][]=$listas['direccion'];
					$tipoA[$numA][]=$listas['gestion'];
				}
	       } 
	       
	    $responsable= $ced-> obtenerResponsabilidad($conexion, $fila['identificador_evaluador']);
	        
	    if(pg_num_rows($responsable) != 0){
	       	$qListaAplicantes=$ced->listarAplicantesEvaluacionIndividual($conexion, $fila['identificador_evaluador'],'',$idEvaluacion);
	       	while($aplicantes = pg_fetch_assoc($qListaAplicantes)){
	       		++$numX;
	       		$tipoX[$numX][]='Evaluación Individual';
	       		$tipoX[$numX][]=$aplicantes['nombres_completos'];
	       		$tipoX[$numX][]=$aplicantes['coordinacion'];
	       		$tipoX[$numX][]=$aplicantes['direccion'];
	       		$tipoX[$numX][]=$aplicantes['gestion'];
	       	}
	    }       
	       
	 	$ctrIni=0; 
	 	if($ctrColor){$color="e4e0e0"; $ctrColor=0; }
	 	else{ $color="#ffffff"; $ctrColor=1; }
	 	
	    if($numS != 0 and $ctrIni == 0){$ctrIni=1; 
	        llenarFilas(++$contador,$fila['identificador_evaluador'],$result['nombre'],$result['apellido'],$numS, $tipoS[1][0],$tipoS[1][1],$tipoS[1][2], $tipoS[1][3],$tipoS[1][4],$color);}
	    if($numI != 0 and $ctrIni == 0){$ctrIni=2;
	    	llenarFilas(++$contador,$fila['identificador_evaluador'],$result['nombre'],$result['apellido'],$numI, $tipoI[1][0],$tipoI[1][1],$tipoI[1][2], $tipoI[1][3],$tipoI[1][4],$color);}
	    if($numP != 0 and $ctrIni == 0){$ctrIni=3;
	    	llenarFilas(++$contador,$fila['identificador_evaluador'],$result['nombre'],$result['apellido'],$numP, $tipoP[1][0],$tipoP[1][1],$tipoP[1][2], $tipoP[1][3],$tipoP[1][4],$color);}
	    if($numA != 0 and $ctrIni == 0){$ctrIni=4;
	    	llenarFilas(++$contador,$fila['identificador_evaluador'],$result['nombre'],$result['apellido'],$numA, $tipoA[1][0],$tipoA[1][1],$tipoA[1][2], $tipoA[1][3],$tipoA[1][4],$color);}
	    if($numX != 0 and $ctrIni == 0){$ctrIni=5;
	    	llenarFilas(++$contador,$fila['identificador_evaluador'],$result['nombre'],$result['apellido'],$numX, $tipoX[1][0],$tipoX[1][1],$tipoX[1][2], $tipoX[1][3],$tipoX[1][4],$color);}
	    	
	    
	    for($i=1;$i<=sizeof($tipoS);$i++){
		    if($ctrIni == 1 and $numS != ''){ $i++; $numS='';}
		    if($tipoS[$i][0] != '')
            llenarFilas('','','','',$numS, $tipoS[$i][0],$tipoS[$i][1],$tipoS[$i][2], $tipoS[$i][3],$tipoS[$i][4],$color);
            $numS='';
	    }
	    for($i=1;$i<=sizeof($tipoI);$i++){
			if($ctrIni == 2 and $numI != ''){$i++; $numI='';}
			if($tipoI[$i][0] != '')
	    	llenarFilas('','','','',$numI, $tipoI[$i][0],$tipoI[$i][1],$tipoI[$i][2], $tipoI[$i][3],$tipoI[$i][4],$color);
	    	$numI='';
	    }
	    for($i=1;$i<=sizeof($tipoP);$i++){
			if($ctrIni == 3 and $numP != ''){$i++; $numP='';}
			if($tipoP[$i][0] != '')
	    	llenarFilas('','','','',$numP, $tipoP[$i][0],$tipoP[$i][1],$tipoP[$i][2], $tipoP[$i][3],$tipoP[$i][4],$color);
	    	$numP='';
	    }
	    for($i=1;$i<=sizeof($tipoA);$i++){
			if($ctrIni == 4){$i++; $numA='';}
			if($tipoA[$i][0] != '')
	    	llenarFilas('','','','',$numA, $tipoA[$i][0],$tipoA[$i][1],$tipoA[$i][2], $tipoA[$i][3],$tipoA[$i][4],$color);
	    }
	    
	    for($i=1;$i<=sizeof($tipoX);$i++){
	    	if($ctrIni == 5 and $numX != ''){$i++; $numX='';}
	    	if($tipoX[$i][0] != '')
	    		llenarFilas('','','','',$numX, $tipoX[$i][0],$tipoX[$i][1],$tipoX[$i][2], $tipoX[$i][3],$tipoX[$i][4],$color);
	    	    $numX='';
	    }
	     
	    
	    $identifi=$fila['identificador_evaluador'];
	   }
	 }
	 
	 function llenarFilas($contador,$identificador,$nombre,$apellido,$cantidad, $tipo,$funcionario,$coordinacion, $direccion,$gestion,$color){
			
			if(strcmp($tipo, 'Superior')==0){
				$tipo='Funcionario a Cargo';
				}
			if(strcmp($tipo, 'Inferior')==0){
				$tipo='Jefe Directo';
				}
			
			echo '<tr >
				 <td bgcolor= "'.$color.'">'.$contador.'</td>
				 <td class="formato" bgcolor= "'.$color.'">'.$identificador.'</td>
				 <td bgcolor= "'.$color.'">'.$nombre.'</td>
				 <td bgcolor= "'.$color.'">'.$apellido.'</td>
				 <td bgcolor= "'.$color.'">'.$cantidad.'</td>
				 <td bgcolor= "'.$color.'">'.$tipo.'</td>
				 <td bgcolor= "'.$color.'">'.$funcionario.'</td>
				 <td bgcolor= "'.$color.'">'.$coordinacion.'</td>
				 <td bgcolor= "'.$color.'">'.$direccion.'</td>
				 <td bgcolor= "'.$color.'">'.$gestion.'</td>
				 </tr>';
		}
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>


