<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorCatastro.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
	try {
	$idEvaluacion = $_POST ['idEvaluacion'];
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	$ca = new ControladorAreas ();
	$cc = new ControladorCatastro();
	
	$idTipoEvaluacion=pg_fetch_result($ced->devolverEvaluacion ($conexion,$idEvaluacion),0,'id_tipo');
	
	$evaluacionSuperior = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'superior' ) );
	$evaluacionInferior = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'inferior' ) );
	$evaluacionPares = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'pares' ) );
	$autoevaluacion = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'autoevaluacion' ) );
	
	$servidores= $ced->devolverFuncionariosActivos($conexion);

while ( $identifi = pg_fetch_assoc ( $servidores ) ) {
  $identificadorUsuario=$identifi['identificador'];
  $consulta =$ced->devolverNivelAreas($conexion, $identificadorUsuario);
	
//--------------------------------------------------------------------------------------------------------------------------------------------------------
  if($consulta['numAreas'] > 0){
  	$conexion->ejecutarConsulta("begin;");
	$sql = $ced->verificarResponsable($conexion, $identificadorUsuario);
	if(pg_num_rows($sql) != 0){
		
		$inializador=0;
		$posiAreas=array();
		while ( $areaBuscar = pg_fetch_assoc ( $sql ) ) {
			++$inializador;
			$areaUsuario=$areaBuscar['id_area'];
			$areaUsuarioPadre=$areaBuscar['id_area_padre'];
			$clasificacion=$areaBuscar['clasificacion'];
			$categoria=$areaBuscar['categoria_area'];
			$posiAreas []= array (
					posi => array_search( $areaUsuario, $consulta['arrayAreas'] )
			);
		
		$cal=min($posiAreas);
		$posicion=$cal['posi'];
		
		
		if($consulta['numAreas'] > 1){
			if($consulta['arrayAreas'][2] == 'Z1' OR $consulta['arrayAreas'][2] == 'Z2' OR $consulta['arrayAreas'][2] == 'Z3' OR $consulta['arrayAreas'][2] == 'Z4' OR $consulta['arrayAreas'][2] == 'Z5' OR $consulta['arrayAreas'][2] == 'Z6' OR $consulta['arrayAreas'][2] == 'Z7')
			{
//-----------------------------------------------------------------------------------------------------------------------------------------------------
				//echo '<br><br><br>Pernese a zona jefe';
				//echo '<br>Asignacion  a nivel de jefes evaluacion conductual ';
				//echo '<br>Asignacion  a nivel de jefes evaluacion individual ';
				if($inializador == 1)
				$result=devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior);
				$identificadorJefe=$result['identificadorJefe'];
				
		//-----------------------------------------------------------------------------------------------------------------------------------------------------
				//echo '<br><br><br>'.$evaluacionPares ['cantidad_usuario'].'Asignacion  a nivel de jefes evaluacion pares<br>';
				$areaUsuarioPadre1=$areaUsuarioPadre;
				if($clasificacion == 'Dirección Distrital A'){
				
					if( devolverDistrital($areaUsuarioPadre) != ''){
						$result=devolverDistrital($areaUsuarioPadre);
						$areaUsuarioPadre1=$result['agr'];
					}
				}
				if($inializador == 1)
				devolverPares($areaUsuarioPadre1,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario);
				
		//---------------------------------------------------------------------------------------------------------------------------------------------------
				//echo '<br><br><br>'.$areaUsuario.' Asignacion  a nivel de jefes evaluacion SUBORDINADOS '.$consulta['arrayAreas'][$posicion].'<br>';
				$areaUsuarioPadre1=$areaUsuarioPadre;
				if($clasificacion != 'Dirección Distrital A'){
						$areaUsuarioPadre1=$areaUsuario;
				}
				$ban=1;
				if(devolverSubordinados($areaUsuarioPadre1,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion) !=0){

					
				}else {
					$ban=0;
					//echo 'otro proceso '.$areaUsuarioPadre1;
					$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuarioPadre1);
					while($fila = pg_fetch_assoc($listaReporte)) {
						if($fila['identificador'] != $identificadorUsuario){
					   // echo '<br>'.$fila['identificador'].'->'.$fila['nombre'];
					    $ced->guardarAplicantes ( $conexion, $identificadorUsuario, $fila['identificador'], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'] ); // Asignar evaluación de superior a inferior.
						
						}
					}
				}
				//echo '<br>'.$categoria;
				if(($categoria == 4 or $categoria ==3) and $ban)
					devolverSubordinadosAsistentes($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
		//---------------------------------------------------------------------------------------------------------------------------------------------------
				
				//echo '<br><br>Asignacion  de autoevaluacion <br>';
				if($inializador == 1){
				$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
				//echo '<br>'.$identificadorUsuario.'-->'.$nombre;
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
				
				//echo '<br><br>Ingresar en tabla de resultados <br>';
				$areaCumplimiento=devolverAreaPadre($consulta, $areaUsuario);
				$ced->crearRegistroResultadoEvaluacion ($conexion, $identificadorUsuario,$areaUsuario, $idEvaluacion,$nombre, $areaCumplimiento );
				}
				
//---------------------------------------------------------------------------------------------------------------------------------------------------
				
			}else {
				//echo '<br><br>Planta central jefe<br>';
				//echo '<br>Asignacion  a nivel de jefes evaluacion conductual ';
				//echo '<br>Asignacion  a nivel de jefes evaluacion individual ';
				if($inializador == 1)
				$result=devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior);
				$identificadorJefe=$result['identificadorJefe'];
		//-----------------------------------------------------------------------------------------------------------------------------------------------------
				//echo '<br><br><br>Asignacion  a nivel de jefes evaluacion pares <br>'.$consulta['arrayAreas'][$posicion-1].'<br>';
				if($inializador == 1)
				devolverPares($areaUsuarioPadre,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario);
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------		
				//echo '<br><br><br>'.$areaUsuario.'.. Asignacion  a nivel de jefes evaluacion SUBORDINADOS '.$consulta['arrayAreas'][$posicion].'<br>';
				if($areaUsuarioPadre=='AGR')$areaUsuarioPadre=$idArea;
				else $areaUsuarioPadre=$consulta['arrayAreas'][$posicion];
				
				
				$ban=1;
				if(devolverSubordinados($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion) !=0){
						
				}else {
					$ban=0;
					//echo 'otro proceso '.$areaUsuario;
					$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuario);
					while($fila = pg_fetch_assoc($listaReporte)) {
						if($fila['identificador'] != $identificadorUsuario){
							//echo '<br>'.$fila['identificador'].'->'.$fila['nombre'];
							$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario, $fila['identificador'], 'true', $idEvaluacion);
							
						}
					}
				}
				
				
				//devolverSubordinados($areaUsuario,$identificadorJefe,$identificadorUsuario);
				//echo '<br>'.$categoria;
				if(($categoria == 4 or $categoria ==3) and $ban)
				devolverSubordinadosAsistentes($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
				
				//echo '<br><br>Asignacion  de autoevaluacion <br>';
					if($inializador == 1){
						$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
						//echo '<br>'.$identificadorUsuario.'-->'.$nombre;
						$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						//echo '<br><br>Ingresar en tabla de resultados <br>';
						$areaCumplimiento=devolverAreaPadre($consulta, $areaUsuario);
						$ced->crearRegistroResultadoEvaluacion ($conexion, $identificadorUsuario,$areaUsuario, $idEvaluacion,$nombre, $areaCumplimiento );
				
					}
				}
//----------------------------------------------------------------------------------------------------------------------------------------------------
		}else{
				//echo '<br><br>Planta central<br>';
				//echo '<br>Asignacion  a nivel de jefes evaluacion conductual ';
				//echo '<br>Asignacion  a nivel de jefes evaluacion individual ';
				if($inializador == 1)
				$result=devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior);
				$identificadorJefe=$result['identificadorJefe'];
				//-----------------------------------------------------------------------------------------------------------------------------------------------------
				//echo '<br><br><br>Asignacion  a nivel de jefes evaluacion pares <br>'.$consulta['arrayAreas'][$posicion-1].'<br>';
				if($inializador == 1)
				devolverPares($areaUsuarioPadre,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario);
				
				//---------------------------------------------------------------------------------------------------------------------------------------------------
				//echo '<br><br><br>'.$areaUsuario.' Asignacion  a nivel de jefes evaluacion SUBORDINADOS '.$consulta['arrayAreas'][$posicion].'<br>';
				
				if($areaUsuarioPadre=='AGR')$areaUsuarioPadre=$areaUsuario;
				else $areaUsuarioPadre=$consulta['arrayAreas'][$posicion];
				
				devolverSubordinados($areaUsuarioPadre,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
				
				//echo '<br>'.$categoria;
				if($categoria == 4 or $categoria ==3)
					devolverSubordinadosAsistentes($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
				
				//echo '<br><br>Asignacion  de autoevaluacion <br>';
				if($inializador == 1){
					$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
					//echo '<br>'.$identificadorUsuario.'-->'.$nombre;
					$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
					//echo '<br><br>Ingresar en tabla de resultados <br>';
					$areaCumplimiento=devolverAreaPadre($consulta, $areaUsuario);
					$ced->crearRegistroResultadoEvaluacion ($conexion, $identificadorUsuario,$areaUsuario, $idEvaluacion,$nombre, $areaCumplimiento );
				
				}
		   }
	   }	
	}else{
//----------------------------------------------------------------------------------------------------------------------------------------------------		
		for($i=$consulta['numAreas'], $j=0 ; $j<$consulta['numAreas']; $i--, $j++){
			$idArea=$consulta['arrayAreas'][$i];
				
			$identificadorJefe = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $idArea ), 0, 'identificador' ); 
			if(!strcmp($identificadorJefe, $identificadorUsuario )==0 and $identificadorJefe != ''){
				break;
			}
		}
		        $areaUsuario = $consulta['arrayAreas'][$consulta['numAreas']];
				//echo '<br><br>Asignacion  a nivel normal evaluacion conductual';
				//echo '<br>Asignacion  a nivel normal evaluacion individual ';
				//echo '<br>'.$idArea.'->'.$identificadorJefe.' '.pg_fetch_result($ced->datosFuncionario($conexion, $identificadorJefe),0,'user');
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorJefe, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de superior a inferior.
				
				//echo '<br><br>Asignacion  a nivel normal evaluacion pares <br>';
				devolverPares2($consulta, $identificadorJefe,$identificadorUsuario,$evaluacionPares);
	
				//echo '<br><br>Asignacion  de autoevaluacion <br>';
				$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
				//echo '<br>'.$identificadorUsuario.'-->'.$nombre;
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
				//echo '<br><br>Ingresar en tabla de resultados <br>';
				$areaCumplimiento=devolverAreaPadre($consulta, $areaUsuario);
				$ced->crearRegistroResultadoEvaluacion ($conexion, $identificadorUsuario,$areaUsuario, $idEvaluacion,$nombre, $areaCumplimiento );
			}
	
		$conexion->ejecutarConsulta("commit;");
  	   
	  		
		}else{
			print_r($consulta);
			//echo '<br>'.$identificadorUsuario;
			$ced->guardarAplicantesNoAsignados ($conexion, $identificadorUsuario,$idEvaluacion);
			$conexion->ejecutarConsulta("commit;");
		}
		
	}
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = "EVALUACIÓN GENERADA SATISFACTORIAMENTE".$e;
	echo json_encode($mensaje);
	//echo '<div><label>EVALUACIÓN GENERADA SATISFACTORIAMENTE</label></div>';
	} catch (Exception $e) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia".$e;
		echo json_encode($mensaje);
		//echo '<br>error general <br>'.$e.'<br>';
	}
	$conexion->desconectar();
	
	
//---------------------------------------------------------------------------------------------------------------------------------------------------------
		
function devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior){
 try {
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	
	$cal=min($posiAreas);
	$posicion=$cal['posi'];
	
	if($posicion ==''){
		$posicion=$consulta['numAreas'];
		//$areaUsuario=$consulta['arrayAreas'][$consulta['numAreas']];
		for($i=$consulta['numAreas'], $j=0 ; $j<$consulta['numAreas']; $i--, $j++){
			$idArea=$consulta['arrayAreas'][$i];
			$identificadorJefe = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $idArea ), 0, 'identificador' ); // Buscar responsable subproceso
			if(!strcmp($identificadorJefe, $identificadorUsuario )==0 and $identificadorJefe != ''){
				break;
			}
		}
	
	}else{
	
		for($i=$posicion, $j=0 ; $j<$consulta['numAreas']; $i--, $j++){
			$idArea=$consulta['arrayAreas'][$i];
			
			$identificadorJefe = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $idArea ), 0, 'identificador' ); // Buscar responsable subproceso
			if(!strcmp($identificadorJefe, $identificadorUsuario )==0 and $identificadorJefe != ''){
				break;
			}
		}
	}
	
	//echo '<br>'.$idArea.'->'.$identificadorJefe.' -> '.pg_fetch_result($ced->datosFuncionario($conexion, $identificadorJefe),0,'user');
	$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorJefe, $evaluacionInferior['id_tipo_evaluacion'], 'true', $evaluacionInferior['tipo'] ); // Asignar evaluación de superior a inferior.
	
	
	$valor = array (
			'area'=>$idArea,
			'identificadorJefe' => $identificadorJefe);
	
	return $valor;
	
	} catch (Exception $e) {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia..".$e;
		echo json_encode($mensaje);
		//echo $e;
  }	
}	
//---------------------------------------------------------------------------------------------------------------------------------------------------------
function devolverPares($areaUsuarioPadre,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario){
	
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	
	$qSubprocesos = $ca->buscarAreasSubprocesos ( $conexion, $areaUsuarioPadre );
	if(pg_num_rows($qSubprocesos) != 0){
		$aParesDir=array();
		while ( $paresDir = pg_fetch_assoc ( $qSubprocesos ) ) {
			if($areaUsuario != $paresDir ['id_area']){
				$identifi=pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresDir ['id_area'] ), 0, 'identificador' );
				if($identifi != '' and $identificadorJefe != $identifi and $identificadorUsuario != $identifi)
					$aParesDir [] = array (
							identificador => $identifi
					);
			}
		}
			
		if (count ( $aParesDir ) > $evaluacionPares['cantidad_usuario']) {
			$paresLiderAleatorias = array_random ( $aParesDir, $evaluacionPares['cantidad_usuario'] ); // Array aleatorio.
			$banderaP=1;
			foreach ( $paresLiderAleatorias as $parAleatorias ) {
				//echo '<br>'.$parAleatorias['identificador'].'.->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias['identificador'], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'] ); // Asignar evaluación par.
				if($banderaP){
					$banderaP=0;
					$ced->guardarAplicantes ( $conexion, $parAleatorias['identificador'],$identificadorUsuario, $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'] ); // Asignar evaluación par.
				}
				//$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $parAleatorias ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
			}
		} else {
	
			foreach ( $aParesDir as $parAleatorias ) {
				//echo '<br>'.$parAleatorias['identificador'].'-.>'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias['identificador'], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'] ); // Asignar evaluación par.
				
				//$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $parAleatorias ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
			}
		}
	
	}else{
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error en la asignacion".$e;
		echo json_encode($mensaje);
		//echo 'ñlklkñ';
	}
	
	
	
}
//---------------------------------------------------------------------------------------------------------------------------------------------------------
function devolverSubordinados($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion){
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	$contador=0;
	
	$qSubprocesos = $ca->buscarAreasSubprocesos ( $conexion, $areaUsuario );
	if(pg_num_rows($qSubprocesos) != 0){
			
		$aParesDir=array();
		while ( $paresDir = pg_fetch_assoc ( $qSubprocesos ) ) {
			if($areaUsuario != $paresDir ['id_area']){
				$identifi=pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresDir ['id_area'] ), 0, 'identificador' );
				if($identifi != '' and $identifi != $identificadorUsuario ){
					$aParesDir [] = array (
							identificador => $identifi
					);
					$contador++;
				}
			}
		}
		$aParesDir=unique_multidim_array($aParesDir,'identificador');
		foreach ( $aParesDir as $parAleatorias ) {
				
			//echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
			//print_r($parAleatorias);
			$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias['identificador'], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'] ); // Asignar evaluación de superior a inferior.
			$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$parAleatorias['identificador'], 'true', $idEvaluacion);
				
				
		}
	}else{
			
		//$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $consulta['arrayAreas'][$posicion], $identificadorJefe,$identificadorUsuario); // Buscar responsable subprocesos de nivel 4 (gestiones)...
		$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $areaUsuario, $identificadorJefe,$identificadorUsuario); // Buscar responsable subprocesos de nivel 4 (gestiones)...
	
		$aParesDir=array();
		if (pg_num_rows ( $qSecretaria ) != 0) {
	
			while ( $paresDir = pg_fetch_assoc ( $qSecretaria ) ) {
				$aParesDir [] = array (
						identificador => $paresDir ['identificador']
				);
				$contador++;
			}
			//print_r($aParesDir);
			foreach ( $aParesDir as $parAleatorias ) {
				//echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
				//print_r($parAleatorias);
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias['identificador'], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'] ); // Asignar evaluación de superior a inferior.
				$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$parAleatorias['identificador'], 'true', $idEvaluacion);
						
			}
				
		}
	}
	
	return $contador;
	
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

function devolverSubordinadosAsistentes($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion){
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	
	
	$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $areaUsuario, $identificadorJefe,$identificadorUsuario); // Buscar responsable subprocesos de nivel 4 (gestiones)...
	
	$aParesDir=array();
	if (pg_num_rows ( $qSecretaria ) != 0) {
	
		while ( $paresDir = pg_fetch_assoc ( $qSecretaria ) ) {
			$aParesDir [] = array (
					identificador => $paresDir ['identificador']
			);
			$contador++;
		}
		//print_r($aParesDir);
		foreach ( $aParesDir as $parAleatorias ) {
			//echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
			//print_r($parAleatorias);
			$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // superior inferior.
			$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$parAleatorias ['identificador'], 'true', $idEvaluacion);
		}
	 }
	
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

function devolverPares2($consulta, $identificadorJefe,$identificadorUsuario,$evaluacionPares){
	
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	
	$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $consulta['arrayAreas'][$consulta['numAreas']], $identificadorJefe,$identificadorUsuario); // Buscar responsable subprocesos de nivel 4 (gestiones)...
	
	$aParesDir=array();
	if (pg_num_rows ( $qSecretaria ) != 0) {
	
		while ( $paresDir = pg_fetch_assoc ( $qSecretaria ) ) {
			$aParesDir [] = array (
					identificador => $paresDir ['identificador']
			);
		}
		//print_r($aParesDir);
		//echo count ( $aParesDir ) ;
		if (count ( $aParesDir ) > $evaluacionPares['cantidad_usuario']) {
	
			$paresLiderAleatorias = array_random ( $aParesDir, $evaluacionPares['cantidad_usuario'] ); // Array aleatorio.
			$banderaP=1;
			foreach ( $paresLiderAleatorias as $parAleatorias ) {
				//echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias ['identificador'], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'] ); // Asignar evaluación par.
				if($banderaP){
					$banderaP=0;
					$ced->guardarAplicantes ( $conexion, $parAleatorias['identificador'],$identificadorUsuario, $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'] ); // Asignar evaluación par.
				}
			}
		} else {
				
			foreach ( $aParesDir as $parAleatorias ) {
				//echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
				//print_r($parAleatorias);
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias ['identificador'], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'] ); // Asignar evaluación par.
			}
		}
	}
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------
function unique_multidim_array($array, $key) {
	$temp_array = array();
	$i = 0;
	$key_array = array();
	 
	foreach($array as $val) {
		if (!in_array($val[$key], $key_array)) {
			$key_array[$i] = $val[$key];
			$temp_array[$i] = $val;
		}
		$i++;
	}
	return $temp_array;
}
//---------------------------------------------------------------------------------------------------------------------------------------------------------
function devolverDistrital($zona){
	$valor='';
	$areas []=array(
			'Z1'=>'DDAT03', 'Z2'=>'DDAT04','Z3'=>'DDAT07','Z4'=>'DDAT10','Z5'=>'DDAT12','Z6'=>'DDAT14','Z7'=>'DDAT16'
	);
	foreach($areas as $val) {
		if ($val[$zona] != '') {
			$valor = array (
					'agr'=>'DE',
					'area' => $val[$zona]);
		}
	}
	return $valor;
}
//--------------------------------------------------------------------------------------------------------------------------------------------------------
function array_random($arr, $num) {
	$keys = array_keys ( $arr );
	shuffle ( $keys );
	$r = array ();

	for($i = 0; $i < $num; $i ++) {
		$r [$keys [$i]] = $arr [$keys [$i]];
	}
	return $r;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------

function devolverAreaPadre($consulta, $idArea) {
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
$qSubprocesos = $ca->buscarAreasSubprocesos($conexion, $idArea);
	
	if(pg_num_rows($qSubprocesos)!=0){				
		$areaCumplimiento=  $idArea;
	}else{
		//Validación para Oficinas Técnicas Jefes y asistentes
		if(strtoupper(substr($idArea, 0,2)) == 'OT'){
			$areaCumplimiento=  $idArea;
		}else{		
			$areaPadre = pg_fetch_assoc($ca->buscarPadreSubprocesos($conexion, $idArea));
			if($areaPadre['id_area_padre']=='AGR' || $areaPadre['id_area_padre']=='DE'){
				$areaCumplimiento=  $funcionario['id_area'];
			}else{
				$areaCumplimiento=  $areaPadre['id_area_padre'];
			}			
		}
	}
	return $areaCumplimiento;
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------

	?>
</body>
</html>