<?php 
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorAreas.php';

$idEvaluacion = $_POST['idEvaluacion'];
$tipo = $_POST['tipo'];
$provincia = $_POST['provincia'];
$fecha=date("Y-m-d");

$nombreArchivo='Servidores_ubicación_y_estructura_organizacional_'.$provincia.'_'.$fecha.'.xls';
header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=$nombreArchivo");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$ced = new ControladorEvaluacionesDesempenio();
$conexion = new Conexion();
$ca = new ControladorAreas();

$resultadoEvaluacion = $ced->listaResultadoEvaluacion($conexion, $idEvaluacion)

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


<div>
<table id="tablaReporte" class="soloImpresion">
	<thead>
	<tr >
		<th colspan=19>Reporte de Servidores, Ubicación Geográfica y Estructura Organizacional</th>
	</tr>
	<tr >
		<th></th><th colspan=5 bgcolor="#00FF00" >Información del Servidor</th><th colspan=3 bgcolor="#FF00FF">Ubicación Geográfica</th><th colspan=4 bgcolor= "#800080">Estructura Organizacional</th>
		<th colspan=2 bgcolor="#0000FF">Responsable</th><th bgcolor="#00FFFF">Tiempo por área</th><th colspan=3 bgcolor="#00FF80">Estructura Organizacional por cargo</th>
	</tr>
		<tr>
			<th>Num</th>
			<th>Identificador</th>
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Correo Institucional</th>
			<th>Correo Personal</th>
			<th>Provincia</th>
			<th>Cantón</th>
			<th>Oficina</th>
		    <th>Coordinación</th>
		    <th>Dirección</th>
		    <th>Gestión</th>
			<th>Cargo</th>
			<th>Responsable</th>
			<th>Área</th>
			<th>Meses</th>
			<th>Funcionarios a Cargo</th>
			<th>Jefe Directo</th>
			<th>Pares</th>
		</tr>
	</thead>
	<tbody>
	 <?php
	 $contador=0; $ctrColor=1;
	 While($fila = pg_fetch_assoc($resultadoEvaluacion)) {

	 if($provincia == 'Todas')$provincia='';
	 $result=pg_fetch_assoc($ced->obtenerExcepcionesFuncionarios($conexion,$fila['identificador'], $provincia));
	 $responsable='No'; $area = array (); 
	 $sql = $ced->verificarResponsable($conexion, $fila['identificador']);
	 if(pg_num_rows($sql) != 0){
	 	$responsable='Si'; 
	 	while ( $areaBuscar = pg_fetch_assoc ( $sql ) ) {
	 		$area[] = $areaBuscar['nombre'];
	 	     }
	 	}
	 
	 	
	 $estructura = $ced->filtrarFuncionariosEvaluacion($conexion, $fila['identificador'], $idEvaluacion);
	 
	 $numS=$numI=$numP=$estru=0;
	 $estruct = array ();
	 $tipoS = array ();
	 $tipoI = array ();
	 $tipoP = array ();
	 
	 while($listas = pg_fetch_assoc($estructura)){
	    
					if($listas['tipo']=='superior'){
						++$numS;
						$tipoS[$numS][]=$listas['nombres_completos'];
					}
					if($listas['tipo']=='inferior'){
						++$numI;
						$tipoI[$numI][]=$listas['nombres_completos'];
					}
					if($listas['tipo']=='pares'){
						++$numP;
						$tipoP[$numP][]=$listas['nombres_completos'];
					}
	 }
	 
	 $num = max($numS,$numI,$numP);
	 
	 for($i=0, $x=1;$i<$num;$i++, $x++){
		if($i<$numS)
			$estruct[$i][]=$tipoS[$x][0];
		else
			$estruct[$i][]='';
		if($i<$numI)
			$estruct[$i][]=$tipoI[$x][0];
		else
			$estruct[$i][]='';
		if($i<$numP)
			$estruct[$i][]=$tipoP[$x][0];
		else
			$estruct[$i][]='';
		}
	 
	 $ctrIni=0;
	
	 //print_r($estruct);
	 	
	 if($result['nombre'] != ''){
		if($ctrColor){$color="e4e0e0"; $ctrColor=0; }
		else{ $color="#ffffff"; $ctrColor=1; }
       echo crearFilas($color,++$contador, $fila['identificador'], $result['nombre'], $result['apellido'],$result['mail_institucional'], $result['mail_personal'],$result['provincia'], $result['canton'], $result['oficina'], $result['coordinacion'], $result['direccion'],$result['gestion'],$result['nombre_puesto'], 
       $responsable, $area[0], '', $estruct[0][0],$estruct[0][1],$estruct[0][2] );

	   for($i=1;$i<$num;$i++)
			echo crearFilas($color,'', '', '', '','', '', '', '', '', '', '','','', '', '','', $estruct[$i][0],$estruct[$i][1],$estruct[$i][2] );
			 
	   if($responsable == 'Si'){
			for($i=1;$i<sizeof($area);$i++)
	            echo crearFilas($color,'', '', '', '','', '', '', '', '', '', '','','', $responsable, $area[$i], '', '','','' );
		       }
	   }
	 
	 }
	 
	 
	 function crearFilas($color,$contador, $identificador, $nombre, $apellido,$mailInstitucional, $mailPersonal, $provincia, $canton, $oficina, $coordinacion, $direccion,$gestion,$nombrePuesto, $responsable, $area, $meses, $superior,$inferior,$pares ){
			$fila = '<tr >';
			$fila.= '
				 <td bgcolor= "'.$color.'">'.$contador.'</td>
				 <td class="formato" bgcolor= "'.$color.'">'.$identificador.'</td>
				 <td bgcolor= "'.$color.'">'.$nombre.'</td>
				 <td bgcolor= "'.$color.'">'.$apellido.'</td>
				 <td bgcolor= "'.$color.'">'.$mailInstitucional.'</td>
				 <td bgcolor= "'.$color.'">'.$mailPersonal.'</td>
				 <td bgcolor= "'.$color.'">'.$provincia.'</td>
				 <td bgcolor= "'.$color.'">'.$canton.'</td>
				 <td bgcolor= "'.$color.'">'.$oficina.'</td>
				 <td bgcolor= "'.$color.'">'.$coordinacion.'</td>
				 <td bgcolor= "'.$color.'">'.$direccion.'</td>
				 <td bgcolor= "'.$color.'">'.$gestion.'</td>
				 <td bgcolor= "'.$color.'">'.$nombrePuesto.'</td>
				 <td bgcolor= "'.$color.'">'.$responsable.'</td>
				 <td bgcolor= "'.$color.'">'.$area.'</td>
				 <td bgcolor= "'.$color.'">'.$meses.'</td>
				 <td bgcolor= "'.$color.'">'.$superior.'</td>
				 <td bgcolor= "'.$color.'">'.$inferior.'</td>
				 <td bgcolor= "'.$color.'">'.$pares.'</td>';
			$fila .= '</tr>';
			return $fila;
	}
	 ?>
	
	</tbody>
</table>

</div>
</body>
</html>


