<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
require_once '../../clases/ControladorCatastro.php';

	define('IN_MSG','<br/> >>> ');
	echo IN_MSG.'Inicio Proceso automático 360 ';
try{
	$conexion = new Conexion();
    $ced = new ControladorEvaluacionesDesempenio();
	
	$fechaActual=strtotime(date('Y-m-d'));
	
		try {
			$conexion->ejecutarConsulta("begin;");
			set_time_limit(2000);
			$consulta360 = $ced->devolverEvaluacionVigente ($conexion,1);	
			
			while($consulta = pg_fetch_assoc($consulta360)){
				$fechaIni = strtotime($consulta['fecha_inicio']);
				$fechaFin = strtotime($consulta['fecha_fin']);
				
//------------------------------------activar proceso de evaluacion--------------------------------------------------------------------------------
				if($fechaActual>=$fechaIni && $fechaActual <=$fechaFin){	
					if($consulta['vigencia']=='activo'){
						//-----------actualizar estado-----------------------------------------------------------------
						$ced->actualizarEvaluacion($conexion,$consulta['id_evaluacion'],'proceso',3);
						//-------------verificar activar inactivar opcion catastro-------------------------------------
						if($consulta['estado_catastro']=='Si'){
						echo IN_MSG.'catastro inactivado';
						$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Contrato por funcionario');
						$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar reponsables RRHH');
						$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar usuarios');
						$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar responsables');
						$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Manual de funciones');
						}
						//-----------------------------------------------------------------------------------
						echo IN_MSG.'activar evaluaciones a funcionarios';
						//------------------proceso de asignación-------------------------------------------------------------------------------------
					
							$idEvaluacion = $consulta['id_evaluacion'];
							$ca = new ControladorAreas ();
							$cc = new ControladorCatastro();
							$ced = new ControladorEvaluacionesDesempenio();
							
							$idTipoEvaluacion=pg_fetch_result($ced->devolverEvaluacion ($conexion,$idEvaluacion),0,'id_tipo');
							
							$evaluacionSuperior = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'superior' ) );
							$evaluacionInferior = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'inferior' ) );
							$evaluacionPares = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'pares' ) );
							$autoevaluacion = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idTipoEvaluacion, 'autoevaluacion' ) );
							
							$servidores= $ced->devolverFuncionariosActivos($conexion);
							
							while ( $identifi = pg_fetch_assoc ( $servidores ) ) {
								$identificadorUsuario=$identifi['identificador'];
							
							
								//if($identificadorUsuario == '1712735529'){
									if(1){
										
							
									$consulta =$ced->devolverNivelAreas($conexion, $identificadorUsuario);
									//print_r($consulta);
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
							
												//echo $consulta['numAreas'];
							
												if($consulta['numAreas'] > 1){
													if($consulta['arrayAreas'][2] == 'Z1' OR $consulta['arrayAreas'][2] == 'Z2' OR $consulta['arrayAreas'][2] == 'Z3' OR $consulta['arrayAreas'][2] == 'Z4' OR $consulta['arrayAreas'][2] == 'Z5' OR $consulta['arrayAreas'][2] == 'Z6' OR $consulta['arrayAreas'][2] == 'Z7')
													{
														//-----------------------------------------------------------------------------------------------------------------------------------------------------
														//echo '<br><br><br>Pernese a zona jefe';
														//echo '<br>Asignacion  a nivel de jefes evaluacion conductual ';
														//echo '<br>Asignacion  a nivel de jefes evaluacion individual ';
														if($inializador == 1)
															$result=devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior,$evaluacionInferior,$evaluacionSuperior);
														$identificadorJefe=$result['identificadorJefe'];
							
														//echo '<br>'.$idArea.'->'.$identificadorJefe.' -> '.pg_fetch_result($ced->datosFuncionario($conexion, $identificadorJefe),0,'user');
							
														$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorJefe, $evaluacionInferior['id_tipo_evaluacion'], 'true', $evaluacionInferior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
														$ced->guardarAplicantes ( $conexion, $identificadorJefe,$identificadorUsuario, $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
							
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
															$paresAsignados=devolverPares($areaUsuarioPadre1,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario);
							
														for($i=0; $i < sizeof($paresAsignados); $i++){
															//echo '<br>'.$paresAsignados[$i].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $paresAsignados[$i]),0,'user');
															$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $paresAsignados[$i], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'],$idEvaluacion ); // Asignar evaluación par.
																
														}
							
							
														//---------------------------------------------------------------------------------------------------------------------------------------------------
														//echo '<br><br><br>'.$areaUsuario.' Asignacion  a nivel de jefes evaluacion SUBORDINADOS '.$consulta['arrayAreas'][$posicion].'<br>';
														$areaUsuarioPadre1=$areaUsuarioPadre;
														if($clasificacion != 'Dirección Distrital A'){
															$areaUsuarioPadre1=$areaUsuario;
														}
														$subOrdinad=devolverSubordinados($areaUsuarioPadre1,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
														$ban=1;
														if(sizeof($subOrdinad) != 0){
																
														}else {
															$arregloSub = array();
															$ban=0;
														//	echo 'otro proceso '.$areaUsuarioPadre1;
															$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuarioPadre1);
															while($fila = pg_fetch_assoc($listaReporte)) {
																if($fila['identificador'] != $identificadorUsuario){
																	$arregloSub[]=$fila['identificador'];
																	/* echo '<br>'.$fila['identificador'].'->'.$fila['nombre'];
																	 $ced->guardarAplicantes ( $conexion, $identificadorUsuario, $fila['identificador'], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
																	$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$fila['identificador'], 'true', $idEvaluacion);
																	*/
																}
															}
															$subOrdinad=$arregloSub;
														}
							
														for($i=0; $i < sizeof($subOrdinad); $i++){
															//echo '<br>'.$subOrdinad[$i].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $subOrdinad[$i]),0,'user');
							
															$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $subOrdinad[$i], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
															$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$subOrdinad[$i], 'true', $idEvaluacion);
														}
							
														//echo '<br>'.$categoria;
														if(($categoria == 4 or $categoria ==3) and $ban)
															devolverSubordinadosAsistentes($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
														//---------------------------------------------------------------------------------------------------------------------------------------------------
							
														//echo '<br><br>Asignacion  de autoevaluacion <br>';
														if($inializador == 1){
															$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
														//	echo '<br>'.$identificadorUsuario.'-->'.$nombre;
															$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'],$idEvaluacion ); // Asignar autoevaluacion.
							
														//	echo '<br><br>Ingresar en tabla de resultados <br>';
															$areaCumplimiento=devolverAreaPadre($consulta, $areaUsuario);
															$ced->crearRegistroResultadoEvaluacion ($conexion, $identificadorUsuario,$areaUsuario, $idEvaluacion,$nombre, $areaCumplimiento );
														}
							
														//---------------------------------------------------------------------------------------------------------------------------------------------------
							
													}else {
														//echo '<br><br>Planta central jefe..<br>';
														//echo '<br>Asignacion  a nivel de jefes evaluacion conductual ';
														//echo '<br>Asignacion  a nivel de jefes evaluacion individual ';
							
														if($inializador == 1)
															$result=devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior,$evaluacionSuperior);
														$identificadorJefe=$result['identificadorJefe'];
														$idArea=$result['area'];
							
							
														//echo '<br>'.$idArea.'->'.$identificadorJefe.' -> '.pg_fetch_result($ced->datosFuncionario($conexion, $identificadorJefe),0,'user');
							
														$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorJefe, $evaluacionInferior['id_tipo_evaluacion'], 'true', $evaluacionInferior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
														$ced->guardarAplicantes ( $conexion, $identificadorJefe,$identificadorUsuario, $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
							
							
															
														//-----------------------------------------------------------------------------------------------------------------------------------------------------
												//	echo '<br><br><br>Asignacion  a nivel de jefes evaluacion pares <br>'.$consulta['arrayAreas'][$posicion-1].'<br>';
														if($inializador == 1)
															$paresAsignados=devolverPares($areaUsuarioPadre,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario);
							
														for($i=0; $i < sizeof($paresAsignados); $i++){
														//	echo '<br>'.$paresAsignados[$i].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $paresAsignados[$i]),0,'user');
															$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $paresAsignados[$i], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'],$idEvaluacion ); // Asignar evaluación par.
																
														}
							
														//---------------------------------------------------------------------------------------------------------------------------------------------------
														//echo '<br><br><br>'.$areaUsuario.'.. Asignacion  a nivel de jefes evaluacion SUBORDINADOS '.$consulta['arrayAreas'][$posicion].'<br>';
														if($areaUsuarioPadre=='AGR')$areaUsuarioPadre=$idArea;
														else $areaUsuarioPadre=$consulta['arrayAreas'][$posicion];
							
														$subOrdinad=devolverSubordinados($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
							
														$ban=1;
														if(sizeof($subOrdinad) != 0){
															//print_r($subOrdinad);
							
														}else {
															$arregloSub = array();
															$ban=0;
														//	echo 'otro proceso '.$areaUsuario;
															$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuario);
															while($fila = pg_fetch_assoc($listaReporte)) {
																if($fila['identificador'] != $identificadorUsuario){
																	$arregloSub[]=$fila['identificador'];
																	/*echo '<br>'.$fila['identificador'].'->'.$fila['nombre'];
																	 $ced->guardarAplicantesIndividual($conexion, $identificadorUsuario, $fila['identificador'], 'true', $idEvaluacion);
																	$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $fila['identificador'], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
																	*/
																}
															}
															$subOrdinad=$arregloSub;
														}
							
														for($i=0; $i < sizeof($subOrdinad); $i++){
														//	echo '<br>'.$subOrdinad[$i].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $subOrdinad[$i]),0,'user');
																
															$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $subOrdinad[$i], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
															$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$subOrdinad[$i], 'true', $idEvaluacion);
														}
							
							
														//devolverSubordinados($areaUsuario,$identificadorJefe,$identificadorUsuario);
														//echo '<br>'.$categoria;
														if(($categoria == 4 or $categoria ==3) and $ban)
															devolverSubordinadosAsistentes($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
							
														//echo '<br><br>Asignacion  de autoevaluacion <br>';
														if($inializador == 1){
															$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
														//	echo '<br>'.$identificadorUsuario.'-->'.$nombre.'ddd'.$autoevaluacion ['id_tipo_evaluacion'].'ss'.$autoevaluacion ['tipo'];
															$ced->guardarAplicantes( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'],$idEvaluacion ); // Asignar autoevaluacion.
							
							
														//	echo '<br><br>Ingresar en tabla de resultados <br>';
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
														$result=devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior,$evaluacionSuperior);
													$identificadorJefe=$result['identificadorJefe'];
							
													$identificadorJefe=$result['identificadorJefe'];
													$idArea=$result['area'];
							
							
													//echo '<br>'.$idArea.'->'.$identificadorJefe.' -> '.pg_fetch_result($ced->datosFuncionario($conexion, $identificadorJefe),0,'user');
							
													$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorJefe, $evaluacionInferior['id_tipo_evaluacion'], 'true', $evaluacionInferior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
													$ced->guardarAplicantes ( $conexion, $identificadorJefe,$identificadorUsuario, $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
							
							
													//-----------------------------------------------------------------------------------------------------------------------------------------------------
													//echo '<br><br><br>Asignacion  a nivel de jefes evaluacion pares <br>'.$consulta['arrayAreas'][$posicion-1].'<br>';
													if($inializador == 1){
							
														$paresAsignados = devolverPares($areaUsuarioPadre,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario);
														//$paresAsignados=devolverPares($areaUsuarioPadre,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario);
							
														for($i=0; $i < sizeof($paresAsignados); $i++){
															//echo '<br>'.$paresAsignados[$i].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $paresAsignados[$i]),0,'user');
															$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $paresAsignados[$i], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'],$idEvaluacion ); // Asignar evaluación par.
														}
													}
													//---------------------------------------------------------------------------------------------------------------------------------------------------
												//	echo '<br><br><br>'.$areaUsuario.' Asignacion  a nivel de jefes evaluacion SUBORDINADOS '.$consulta['arrayAreas'][$posicion].'<br>';
							
													if($areaUsuarioPadre=='AGR')$areaUsuarioPadre=$areaUsuario;
													else $areaUsuarioPadre=$consulta['arrayAreas'][$posicion];
							
													$subOrdinad=devolverSubordinados($areaUsuarioPadre,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
							
													$ban=1;
													if(sizeof($subOrdinad) != 0){
														//print_r($subOrdinad);
							
													}else {
														$arregloSub = array();
														$ban=0;
													//	echo 'otro proceso '.$areaUsuario;
														$listaReporte = $cc->filtroObtenerFuncionarios($conexion, '', '', '', '', $areaUsuario);
														while($fila = pg_fetch_assoc($listaReporte)) {
															if($fila['identificador'] != $identificadorUsuario){
																$arregloSub[]=$fila['identificador'];
															}
														}
														$subOrdinad=$arregloSub;
													}
							
													for($i=0; $i < sizeof($subOrdinad); $i++){
														//echo '<br>'.$subOrdinad[$i].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $subOrdinad[$i]),0,'user');
							
														$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $subOrdinad[$i], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
														$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$subOrdinad[$i], 'true', $idEvaluacion);
													}
							
							
													//echo '<br>'.$categoria;
													if($categoria == 4 or $categoria ==3)
														devolverSubordinadosAsistentes($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion);
							
													//echo '<br><br>Asignacion  de autoevaluacion <br>';
													if($inializador == 1){
														$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
														//echo '<br>'.$identificadorUsuario.'-->'.$nombre;
														$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'],$idEvaluacion ); // Asignar autoevaluacion.
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
											//echo '<br><br>Asignacion  a nivel normal evaluacion conductual..';
											//echo '<br>Asignacion  a nivel normal evaluacion individual ';
							
											//echo '<br>'.$idArea.'->'.$identificadorJefe.' '.pg_fetch_result($ced->datosFuncionario($conexion, $identificadorJefe),0,'user');
							
											$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorJefe, $evaluacionInferior['id_tipo_evaluacion'], 'true', $evaluacionInferior['tipo'],$idEvaluacion ); // Asignar evaluación de inferior a superior.
											$ced->guardarAplicantes ( $conexion, $identificadorJefe,$identificadorUsuario, $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
							
							
											//echo '<br><br>Asignacion  a nivel normal evaluacion pares <br>';
											$paresNormal=devolverPares2($consulta, $identificadorJefe,$identificadorUsuario,$evaluacionPares);
											//--------------------------------------------------------------------------------------------------------
											$banderaP=1;
											for($i=0; $i < sizeof($paresNormal); $i++){
												//echo '<br>'.$paresNormal[$i].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $paresNormal[$i]),0,'user');
												$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $paresNormal[$i], $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'],$idEvaluacion ); // Asignar evaluación par.
												if($banderaP){
													$banderaP=0;
													$ced->guardarAplicantes ( $conexion, $paresNormal[$i],$identificadorUsuario, $evaluacionPares['id_tipo_evaluacion'], 'true', $evaluacionPares['tipo'],$idEvaluacion ); // Asignar evaluación par.
												}
											}
							
											//-------------------------------------------------------------------------------------------------------------
												
										//	echo '<br><br>Asignacion  de autoevaluacion <br>';
											$nombre=pg_fetch_result($ced->datosFuncionario($conexion, $identificadorUsuario),0,'user');
										//	echo '<br>'.$identificadorUsuario.'-->'.$nombre;
											$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $identificadorUsuario, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'],$idEvaluacion ); // Asignar autoevaluacion.
										//	echo '<br><br>Ingresar en tabla de resultados <br>';
											$areaCumplimiento=devolverAreaPadre($consulta, $areaUsuario);
											$ced->crearRegistroResultadoEvaluacion ($conexion, $identificadorUsuario,$areaUsuario, $idEvaluacion,$nombre, $areaCumplimiento );
										}
							
										$conexion->ejecutarConsulta("commit;");
									}else{
										print_r($consulta);
									//	echo '<br>'.$identificadorUsuario;
										$ced->guardarAplicantesNoAsignados ($conexion, $identificadorUsuario,$idEvaluacion);
										$conexion->ejecutarConsulta("commit;");
									}
									//------------------------------------------------------------------------------------------------------------------------
								}
							
							}
							echo IN_MSG.'EVALUACIÓN GENERADA SATISFACTORIAMENTE';
							//-----------------------------------------------------------------------------------------------------------------------------
							
							echo IN_MSG.'EN PROCESO...';
							
					  }
							
			        }	
      //---------------------------------------finalizar subrogacion de funcionarios----------------------------------------------------------------------------------------------------------
			        if($fechaActual > $fechaFin){
			        	if($consulta['vigencia']=='proceso'){
			        	//-----------actualizar estado-----------------------------------------------------------------
			        	$ced->actualizarEvaluacion($conexion,$consulta['id_evaluacion'],'finalizado',3);
			        	//-------------verificar activar inactivar opcion catastro-------------------------------------
			        	if($consulta['estado_catastro']=='Si'){
			        		echo IN_MSG.'catastro activado';
			        		$ced->activarInactivarCatastroOpcion($conexion, 'activo','Contrato por funcionario');
							$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar reponsables RRHH');
							$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar usuarios');
							$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar responsables');
							$ced->activarInactivarCatastroOpcion($conexion, 'activo','Manual de funciones');
			        	}
			        	//----------------------------------------------------------------------------------------------
			        	echo IN_MSG.'inactivar evaluaciones a funcionarios';
			        	//------------------------------------------------------------------------------------------------------------------------------
			        	$ced->inactivarActivarAplicantes($conexion,'','finalizado',$consulta['id_evaluacion']);
			        	$ced->inactivarActivarAplicantesIndividual($conexion,'','finalizado', $consulta['id_evaluacion']);
			        	}			        	
			         }	
				 }
				 
				$conexion->ejecutarConsulta("commit;");
				} catch (Exception $ex){
					$conexion->ejecutarConsulta("rollback;");
					echo IN_MSG.'Error de ejecucion'.$ex;
				}finally {
			$conexion->desconectar();
		}
} catch (Exception $ex) {
	echo IN_MSG.'Error de conexión a la base de datos';
}
//---------------------------------------------------------------------------------------------------------------------------------------------------------
function devolverJefeArea($posiAreas,$consulta,$identificadorUsuario,$areaUsuarioPadre,$evaluacionInferior,$evaluacionSuperior){
 
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	
	$cal=min($posiAreas);
	$posicion=$cal['posi'];
	
	if($posicion ==''){
		$posicion=$consulta['numAreas'];
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
	
	$valor = array (
			'area'=>$idArea,
			'identificadorJefe' => $identificadorJefe);
	
  return $valor;
}	
//---------------------------------------------------------------------------------------------------------------------------------------------------------
function devolverPares($areaUsuarioPadre,$areaUsuario,$evaluacionPares,$identificadorJefe,$identificadorUsuario){
	
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	$arregloPares = array();
	
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
	//echo count ( $aParesDir ).'-->'.$evaluacionPares['cantidad_usuario'].'mmm';
		if (count ( $aParesDir ) > $evaluacionPares['cantidad_usuario']) {
			$paresLiderAleatorias = array_random ( $aParesDir, $evaluacionPares['cantidad_usuario'] ); // Array aleatorio.
			$banderaP=1;
			foreach ( $paresLiderAleatorias as $parAleatorias ) {
				$arregloPares [] =$parAleatorias['identificador'];
							}
		} else {
	
			foreach ( $aParesDir as $parAleatorias ) {
				$arregloPares [] =$parAleatorias['identificador'];
			
			}
		}
	
	}
	return $arregloPares;
	
}
//---------------------------------------------------------------------------------------------------------------------------------------------------------
function devolverSubordinados($areaUsuario,$identificadorJefe,$identificadorUsuario,$evaluacionSuperior,$idEvaluacion){
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	$contador=0;
	$arregloSubordinados=array();
	
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

			$arregloSubordinados[]=$parAleatorias['identificador'];
			
			/*echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
			print_r($parAleatorias);
			$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias['identificador'], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
			$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$parAleatorias['identificador'], 'true', $idEvaluacion);
			*/	
				
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
				
				$arregloSubordinados[]=$parAleatorias['identificador'];
				
				/*
				echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
				//print_r($parAleatorias);
				$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias['identificador'], $evaluacionSuperior['id_tipo_evaluacion'], 'true', $evaluacionSuperior['tipo'],$idEvaluacion ); // Asignar evaluación de superior a inferior.
				$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$parAleatorias['identificador'], 'true', $idEvaluacion);
						*/
			}
				
		}
	}
	
	return $arregloSubordinados;
	
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
		//	echo '<br>'.$parAleatorias['identificador'].'->'.pg_fetch_result($ced->datosFuncionario($conexion, $parAleatorias['identificador']),0,'user');
			//print_r($parAleatorias);
			$ced->guardarAplicantes ( $conexion, $identificadorUsuario, $parAleatorias ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'],$idEvaluacion ); // superior inferior.
			$ced->guardarAplicantesIndividual($conexion, $identificadorUsuario,$parAleatorias ['identificador'], 'true', $idEvaluacion);
		}
	 }
	
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

function devolverPares2($consulta, $identificadorJefe,$identificadorUsuario,$evaluacionPares){
	
	$ca = new ControladorAreas ();
	$conexion = new Conexion ();
	$ced = new ControladorEvaluacionesDesempenio ();
	$paresNormal= array();
	
	$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $consulta['arrayAreas'][$consulta['numAreas']], $identificadorJefe,$identificadorUsuario); // Buscar responsable subprocesos de nivel 4 (gestiones)...
	$aParesDir=array();
	if (pg_num_rows ( $qSecretaria ) != 0) {
	
		while ( $paresDir = pg_fetch_assoc ( $qSecretaria ) ) {
			$aParesDir [] = array (
					identificador => $paresDir ['identificador']
			);
		}
	if (count ( $aParesDir ) > $evaluacionPares['cantidad_usuario']) {
	
			$paresLiderAleatorias = array_random ( $aParesDir, $evaluacionPares['cantidad_usuario'] ); // Array aleatorio.
			$banderaP=1;
			foreach ( $paresLiderAleatorias as $parAleatorias ) {
				$paresNormal[]=$parAleatorias['identificador'];
			}
		} else {
				
			foreach ( $aParesDir as $parAleatorias ) {
				$paresNormal[]=$parAleatorias['identificador'];
			}
		}
	}
	
	return $paresNormal;
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
?>
</body>
</html>