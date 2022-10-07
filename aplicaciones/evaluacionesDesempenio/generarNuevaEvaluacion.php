<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';
function array_random($arr, $num) {
	$keys = array_keys ( $arr );
	shuffle ( $keys );	
	$r = array ();
	
	for($i = 0; $i < $num; $i ++) {
		$r [$keys [$i]] = $arr [$keys [$i]];
	}
	return $r;
}
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

	$qAreas = $ca->obtenerAreasDireccionesTecnicas ( $conexion, "('Planta Central','Dirección Distrital A','Dirección Distrital B','Oficina Técnica','Zona','Unidad')", "(3,4,1,2,5)" ); // Listado de las areas Agrocalidad.	
	$evaluacionSuperior = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idEvaluacion, 'superior' ) );
	$evaluacionInferior = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idEvaluacion, 'inferior' ) );
	$evaluacionPares = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idEvaluacion, 'pares' ) );
	$autoevaluacion = pg_fetch_assoc ( $ced->abrirTipoEvaluacion ( $conexion, $idEvaluacion, 'autoevaluacion' ) );
	$conexion->ejecutarConsulta("begin;");
	while ( $area = pg_fetch_assoc ( $qAreas ) ) {
		
		// echo '<br/><br/>AREA INCIAL +++++++++++++++++++++++++++++'. $area['id_area'].'<br/>';		
		//if (0) { // Si es el area padre AGR...
		if ($area ['id_area_padre'] == "AGR") { // Si es el area padre AGR...
			$responsable = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $area ['id_area'] ), 0, 'identificador' ); // Buscar responsable
			
			$qSubprocesos = $ca->buscarAreasSubprocesos ( $conexion, $area ['id_area'] ); // buscar subprocesos de area Padre.
			echo '</br>>>> Nivel 1';
			while ( $subProceso = pg_fetch_assoc ( $qSubprocesos ) ) {
				echo '>>> Nivel 1'.$subProceso ['id_area'].'</br>';
				
				$responsableSubproceso = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $subProceso ['id_area'] ), 0, 'identificador' ); // Buscar responsable subproceso
				$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
				$ced->guardarAplicantes ( $conexion, $responsableSubproceso, $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
				
				/////////////EVALUACION INDIVIDUAL///////////
				
				$ced->guardarAplicantesIndividual($conexion, $responsable, $responsableSubproceso, 'true', $idEvaluacion);
				
				////////////////////////////////////////////
				
			}
			
			//$ced->verificarAplicanteRegistrado($conexion, $responsable, $responsable,$autoevaluacion ['id_tipo_evaluacion'], $autoevaluacion ['tipo']);
			//if(pg_num_rows($ced) != 0)
			$ced->guardarAplicantes ( $conexion, $responsable, $responsable, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluación.

			//////////////////////SECRETARIAS/////////////////////////				
			$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $area['id_area'], $responsable,NULL); // Buscar responsable subprocesos de nivel 4 (gestiones)...
			
			$aParesDir=array();
			if (pg_num_rows ( $qSecretaria ) != 0) {//Pregunta si hay
										
				while ( $paresDir = pg_fetch_assoc ( $qSecretaria ) ) {
					$aParesDir [] = array (
							identificador => $paresDir ['identificador']
					);
				}
					//print_r($aParesDir);
				foreach ( $aParesDir as $paresDir ) {
					echo '>>>pares nivel 1</br>';
					//print_r($aParesDir);
					$ced->guardarAplicantes ( $conexion, $responsable, $paresDir ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
					$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
				
					/////////////EVALUACION INDIVIDUAL///////////					
					//$ced->guardarAplicantesIndividual($conexion, $responsable, $paresDir ['identificador'], 'true', $idEvaluacion);					
					////////////////////////	
					
				}
				//echo $evaluacionPares ['cantidad_usuario'].'<br>';
				if ($evaluacionPares ['cantidad_usuario'] == '0') {
					foreach ( $aParesDir as $paresDir ) {
						foreach ( $aParesDir as $oParesDir) {
							if ($paresDir ['identificador'] != $oParesDir ['identificador']) {
								$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'] , $oParesDir ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								echo '>>>pares nivel 1 todos</br>';
							}
						}
							
						$ced->guardarAplicantes ( $conexion, $paresDir  ['identificador'], $paresDir  ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						echo '>>>5</br>';
					}
				} else {
					
					if (count ( $aParesDir ) > $evaluacionPares ['cantidad_usuario']) {
												
						foreach ( $aParesDir as $paresDir ) {
							$aMiembroEquipoUsuario = array ();
							$qMiembrosEquipoSinUsuarioActual = $ca->buscarMiembrosEquipo ( $conexion, $area ['id_area'], $responsable, $paresDir ['identificador'] ); // Buscar miembros de area sin el responsable.
							while ( $miembrosEquipoUsuario = pg_fetch_assoc ( $qMiembrosEquipoSinUsuarioActual ) ) {
								$aMiembroEquipoUsuario [] = array (
										identificador => $miembrosEquipoUsuario ['identificador']
								);
							}
						
							$paresLiderAleatorias = array_random ( $aMiembroEquipoUsuario, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.

							foreach ( $paresLiderAleatorias as $parAleatorias ) {
								//print_r($parAleatorias);
								$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $parAleatorias ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}							
							$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $paresDir ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						}
					} else {
						
						foreach ( $aParesDir as $paresDir ) {
							foreach ( $aParesDir as $oParesDir ) {
								
								if ($paresDir ['identificador'] != $oParesDir ['identificador']) {
									$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $oParesDir ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}
							$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $paresDir ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						}
					}
				}
					
			}
				
			///////////////////FIN SECRETARIAS/////////////////////////

			
		} else if(1) {
//-----------------------------------------------------------------------------------------------------------------------------------------
			
			$qResponsable = $ca->buscarResponsableSubproceso ( $conexion, $area ['id_area'] );// Busca responsable de subproceso...

			if (pg_num_rows ( $qResponsable ) != 0) {//Verifica si hay responsable...
				$responsable = pg_fetch_result ( $qResponsable, 0, 'identificador' );// devuelve el responsable...
			} else {
				$responsable = '0';
				//echo '<br>'.$area ['id_area'].'<br>';
			}
			
			if ($area ['categoria_area'] == '2') {//verifica si es categoria 2...
				ECHO '</br>'.$responsable.'</br>';
				//echo '>>> Nivel 2'.$area ['id_area'].'</br>';
				
				// echo '****************************AREA 2***************************<br/>';			
				$responsablesOficina = $ca->obtenerResponsablesOficinasTecnicasZonaPadre ( $conexion, $area ['id_area'], $responsable );//Obtiene responsables de (oficna tecnica o Zona) sin responsable lider...
				
				$aParesOficinaTecnica = array ();
				
				while ( $subProceso = pg_fetch_assoc ( $responsablesOficina ) ) {
					echo '>>> Nivel 2'.$area ['id_area'].'--'.$subProceso ['identificador'].'--</br>';
					
					
					$ced->guardarAplicantes ( $conexion, $responsable, $subProceso ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
					$ced->guardarAplicantes ( $conexion, $subProceso ['identificador'], $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
				
					/////////////EVALUACION INDIVIDUAL///////////
						
					$ced->guardarAplicantesIndividual($conexion, $responsable, $subProceso ['identificador'], 'true', $idEvaluacion);
						
					////////////////////////
					
					$aParesOficinaTecnica [] = array (
							identificador => $subProceso ['identificador'] //Almacena en un array los responsables de oficinas técnicas...
					);
				}
				
				$aParesLider = array ();
				
				$qParesLider = $ca->obtenerAreasXcategoria ( $conexion, '2', 'Zona', $area ['id_area'] ); //Obtiene pares lider de Zona excepto Zona Z1...
				
				while ( $fila = pg_fetch_assoc ( $qParesLider ) ) {
					if ($area ['id_area'] != $fila ['id_area']) {
						$aParesLider [] = array (
								idArea => $fila ['id_area'] //Almacena en un array las zonas pares excepto la primera zona lider... 
						);
					}
				}
				
				echo $evaluacionPares ['cantidad_usuario'];
				if ($evaluacionPares ['cantidad_usuario'] == '0') {//si cantidad de evaluados es 0 se evalua a todos los pares de zona....
					foreach ( $aParesLider as $paresLider ) {
						$responsableSubproceso = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ), 0, 'identificador' ); // Buscar responsable de zona área par.
						$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par de zona.
					}
					
					foreach ( $aParesOficinaTecnica as $paresOficinaTecnica ) {//recorre array con los pares lideres de oficina tecnica...
						foreach ( $aParesOficinaTecnica as $oParesOficinaTecnica ) {//recorre array con los pares lider de oficina tecnica...
							if ($paresOficinaTecnica ['identificador'] != $oParesOficinaTecnica ['identificador']) {//pregunta si no es par actual...
								$ced->guardarAplicantes ( $conexion, $paresOficinaTecnica ['identificador'], $oParesOficinaTecnica ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}
						}
					}
				} else {

					//PROCESO PARA EVALUAR A PARES """LIDERES"""" DE OFICINAS TECNICAS...

					if (count ( $aParesLider ) > $evaluacionPares ['cantidad_usuario']) {//Si hay numero de pares lider  a evaluar...
						$paresLiderAleatorias = array_random ( $aParesLider, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
						
						foreach ( $paresLiderAleatorias as $paresLider ) {
							$responsableSubproceso = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ), 0, 'identificador' ); // Buscar responsable área par oficina tecnica.
							$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
						}
					} else {
						foreach ( $aParesLider as $paresLider ) {// Si hay menos pares de la cantidad a evaluar se evalua a todos...
							$responsableSubproceso = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ), 0, 'identificador' ); // Buscar responsable área par.
							$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
						}
					}
					
					//PROCESO PARA EVALUAR A PARES DE OFICINAS TECNICAS
		
					if (count ( $aParesOficinaTecnica ) > $evaluacionPares ['cantidad_usuario']) {
						$paresOficinaTecnica = array_random ( $aParesOficinaTecnica, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
						
						foreach ( $paresOficinaTecnica as $pOficinaTecnica ) {
							foreach ( $paresOficinaTecnica as $pOficinaTecnicaC ) {
								if ($pOficinaTecnica ['identificador'] != $pOficinaTecnicaC ['identificador']) {
									$ced->guardarAplicantes ( $conexion, $pOficinaTecnica ['identificador'], $pOficinaTecnicaC ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}
						}
					} else {
						foreach ( $aParesOficinaTecnica as $paresOficinaTecnica ) {
							foreach ( $aParesOficinaTecnica as $oParesOficinaTecnica ) {
								if ($paresOficinaTecnica ['identificador'] != $oParesOficinaTecnica ['identificador']) {
									$ced->guardarAplicantes ( $conexion, $paresOficinaTecnica ['identificador'], $oParesOficinaTecnica ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}
						}
					}
				}
				// TODO: SE QUITO LA AUTOEVALUACION DE LA CATEGORIA 2 Y SE VALIDO EN LA AUTOEVALUACION A NIVEL DE CATEGORIA 4 PORQUE SON LOS MISMOS RESPONSABLES
				 $ced ->guardarAplicantes($conexion, $responsable, $responsable, $autoevaluacion['id_tipo_evaluacion'], 'true', $autoevaluacion['tipo']); //Asignar autoevaluación.
//**********************************************************************************
		  } else if ($area ['categoria_area'] == '3') { // El area tiene subprocesos. Coordinaciones
			//} else if (0) { // El area tiene subprocesos. Coordinaciones			
//-----------------------------------------------------------------------------------------------------------------------------------------			                                          
				// echo '****************************AREA 3***************************<br/>';
				echo '>>nivel 3'.$area ['id_area'].'</br>';
				//PROCESO PARA EVALUAR A COORDINACIONES DE JEFE DE COORDINACIONES  A DIRECCIONES...
				
				$qAreasSubprocesos = $ca->buscarAreasSubprocesos ( $conexion, $area ['id_area'] ); // Busca todas las areas de categoria 3 con un area padre..
				
				while ( $subProceso = pg_fetch_assoc ( $qAreasSubprocesos ) ) {					
					//echo $subProceso ['id_area'];									
					$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $subProceso ['id_area'] ); // Buscar responsable subproceso coordinaciones.
					if (pg_num_rows ( $qResponsableSubproceso ) != 0) {//Pregunta si tiene responsable...
						$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
						$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
						$ced->guardarAplicantes ( $conexion, $responsableSubproceso, $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
						
						/////////////EVALUACION INDIVIDUAL///////////
						
						$ced->guardarAplicantesIndividual($conexion, $responsable, $responsableSubproceso, 'true', $idEvaluacion);
						
						////////////////////////												
					}
				}
								
				//PROCESO PARA EVALUAR PARES DE JEFES DE COORDINACIONES...
				
				$aParesLider = array ();
				
				$qParesLider = $ca->obtenerAreasXcategoriaPadre ( $conexion, '3', $area['clasificacion'], $area ['id_area'],$area ['id_area_padre'] );//Obiene pares de lider de coordiciones
				
				while ( $fila = pg_fetch_assoc ( $qParesLider ) ) {
					$aParesLider [] = array (
							idArea => $fila ['id_area'] //Almacena en un array los lideres pares... menos el que llega...
					);
				}
				
				if ($evaluacionPares ['cantidad_usuario'] == '0') {//si cantidad de evaluados es 0 se evalua a todos los pares de zona...
					foreach ( $aParesLider as $paresLider ) {
						$responsableSubproceso = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ), 0, 'identificador' ); // Buscar responsable área par.
						$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
					}
				} else {
					if (count ( $aParesLider ) > $evaluacionPares ['cantidad_usuario']) {
						$paresLiderAleatorias = array_random ( $aParesLider, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
						
						foreach ( $paresLiderAleatorias as $paresLider ) {
							$responsableSubproceso = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ), 0, 'identificador' ); // Buscar responsable área par.
							$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
						}
					} else {
						foreach ( $aParesLider as $paresLider ) {
							$responsableSubproceso = pg_fetch_result ( $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ), 0, 'identificador' ); // Buscar responsable área par.
							$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
						}
					}
				}		

				//$ced->verificarAplicanteRegistrado($conexion, $responsable, $responsable,$autoevaluacion ['id_tipo_evaluacion'], $autoevaluacion ['tipo']);
				//if(pg_num_rows($ced) != 0)
				$ced->guardarAplicantes ( $conexion, $responsable, $responsable, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluación.
			
				//////////////////////SECRETARIAS/////////////////////////				
				$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $area['id_area'], $responsable,NULL); // Buscar responsable subprocesos de nivel 4 (gestiones)...
				
				$aParesDir=array();
				if (pg_num_rows ( $qSecretaria ) != 0) {//Pregunta si hay
						
						
					while ( $paresDir = pg_fetch_assoc ( $qSecretaria ) ) {
						$aParesDir [] = array (
								identificador => $paresDir ['identificador']
						);
					}
						
					foreach ( $aParesDir as $paresDir ) {
						$ced->guardarAplicantes ( $conexion, $responsable, $paresDir ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
						$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
						
						/////////////EVALUACION INDIVIDUAL///////////
						
						$ced->guardarAplicantesIndividual($conexion, $responsable, $paresDir ['identificador'], 'true', $idEvaluacion);
						
						////////////////////////												
					}												
					if ($evaluacionPares ['cantidad_usuario'] == '0') {
						foreach ( $aParesDir as $paresDir ) {
							foreach ( $aParesDir as $oParesDir) {
								if ($paresDir ['identificador'] != $oParesDir ['identificador']) {
									$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'] , $oParesDir ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}		
								
							$ced->guardarAplicantes ( $conexion, $paresDir  ['identificador'], $paresDir  ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						}
					} else {
							
						if (count ( $aParesDir ) > $evaluacionPares ['cantidad_usuario']) {
				
							foreach ( $aParesDir as $paresDir ) {
								$aMiembroEquipoUsuario = array ();
								$qMiembrosEquipoSinUsuarioActual = $ca->buscarMiembrosEquipo ( $conexion, $area ['id_area'], $responsable, $paresDir ['identificador'] ); // Buscar miembros de area sin el responsable.
								while ( $miembrosEquipoUsuario = pg_fetch_assoc ( $qMiembrosEquipoSinUsuarioActual ) ) {
									$aMiembroEquipoUsuario [] = array (
											identificador => $miembrosEquipoUsuario ['identificador']
									);
								}
				
								$paresLiderAleatorias = array_random ( $aMiembroEquipoUsuario, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
				
								foreach ( $paresLiderAleatorias as $parAleatorias ) {
									$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $parAleatorias ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
									
								$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $paresDir ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
							}
						} else {
				
							foreach ( $aParesDir as $paresDir ) {
								foreach ( $aParesDir as $oParesDir ) {
									if ($paresDir ['identificador'] != $oParesDir ['identificador']) {
										$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $oParesDir ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
									}
								}
								$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $paresDir ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
							}
						}
					}
						
				}				
				///////////////////FIN SECRETARIAS/////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------------							
			} else if ($area ['categoria_area'] == '4') { // El área tiene subprocesos, pero no se toman en cuenta. Direcciones administrativas y oficinas tecnicas.
			//} else if (0) { // El área tiene subprocesos, pero no se toman en cuenta. Direcciones administrativas y oficinas tecnicas.
//**********************************************************************************				
				// echo '****************************AREA 4***************************<br/>';				
				echo '>>nivel 4 >'.$responsable.'>'.$area ['id_area'].'</BR>';
				$identificadorJefe='';
				if($responsable == '0vv'){
					$areaRecursiva = pg_fetch_assoc(ControladorAreas::buscarAreaResponsablePorUsuarioRecursivo($conexion, $area ['id_area']));
					$tipoArea = $areaRecursiva['clasificacion'];
					$arrayAreas = explode(',', $areaRecursiva['path']);
					$numAreas = sizeof($arrayAreas)-1;
				
					//print_r ($arrayAreas);
				
					for($i=$numAreas, $j=0 ; $j<$numAreas; $i--, $j++){
						$idArea=$arrayAreas[$i];
						$identificadorJefe = pg_fetch_result(ControladorAreas::buscarResponsableSubproceso($conexion,$idArea), 0, 'identificador');
						if($identificadorJefe != ''){
							break;
						}
					}
					$responsable= $identificadorJefe;
				}
				
				
				$qAreasSubProcesos = $ca->buscarAreasSubprocesos ( $conexion, $area ['id_area'] );
		
				while ( $subProceso = pg_fetch_assoc ( $qAreasSubProcesos ) ) {
					
					$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $subProceso ['id_area'] ); // Buscar responsable subproceso en dirección
					
					if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
						$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
						$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
						$ced->guardarAplicantes ( $conexion, $responsableSubproceso, $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
					
						/////////////EVALUACION INDIVIDUAL///////////
						
						$ced->guardarAplicantesIndividual($conexion, $responsable, $responsableSubproceso, 'true', $idEvaluacion);
						
						////////////////////////										
					}									
				}		
				
				if($identificadorJefe != '')$responsable='0';
				
				
				if ($area ['clasificacion'] == 'Planta Central') {
					$aParesDireccion = array ();
					
					if ($area ['id_area_padre'] == 'DE') {
						$qParesDireccionCoordinacionDE = $ca->obtenerParesXareaPadreYcategoria ( $conexion, '4', 'DE', $area ['id_area'] );
						
						while ( $fila = pg_fetch_assoc ( $qParesDireccionCoordinacionDE ) ) {
							$aParesDireccion [] = array (
									idArea => $fila ['id_area'] 
							);
						}
						
						if ($evaluacionPares ['cantidad_usuario'] == '0') {
							foreach ( $aParesDireccion as $paresLider ) {
								$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ); // Buscar responsable área par.
								if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
									$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
									$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}
						} else {
							
							if (count ( $aParesDireccion ) > $evaluacionPares ['cantidad_usuario']) {
								$paresLiderAleatorias = array_random ( $aParesDireccion, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
								
								foreach ( $paresLiderAleatorias as $paresLider ) {
									$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ); // Buscar responsable área par.
									if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
										$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
										$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
									}
								}
							} else {
								foreach ( $aParesDireccion as $paresLider ) {
									$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ); // Buscar responsable área par.
									if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
										$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
										$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
									}
								}
							}
						}
					} else {
						$aParesDireccionCoordinacion = array ();
						$qParesDireccionCoordinacion = $ca->obtenerParesXareaPadreYcategoria ( $conexion, '4', $area ['id_area_padre'], $area ['id_area'] );
						
						while ( $fila = pg_fetch_assoc ( $qParesDireccionCoordinacion ) ) {
							$aParesDireccionCoordinacion [] = array (
									idArea => $fila ['id_area'] 
							);
						}
						
						foreach ( $aParesDireccionCoordinacion as $paresLiderCoordinacion ) {
							if ($evaluacionPares ['cantidad_usuario'] == '0') {
								$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLiderCoordinacion ['idArea'] ); // Buscar responsable área par.
								if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
									$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
									$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							} else {
								
								if (count ( $aParesDireccionCoordinacion ) > $evaluacionPares ['cantidad_usuario']) {
									$paresLiderAleatorias = array_random ( $aParesDireccionCoordinacion, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
									
									foreach ( $paresLiderAleatorias as $paresLider ) {
										$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ); // Buscar responsable área par.
										if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
											$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
											$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
										}
									}
								} else {
									$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLiderCoordinacion ['idArea'] ); // Buscar responsable área par.
									if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
										$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
										$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
									}
								}
							}
						}
					}
				}
				
				
				
				
				
				
				
				//$ced->verificarAplicanteRegistrado($conexion, $responsable, $responsable,$autoevaluacion ['id_tipo_evaluacion'], $autoevaluacion ['tipo']);
				//if(pg_num_rows($ced) != 0)
				$ced->guardarAplicantes ( $conexion, $responsable, $responsable, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluación.
				
				//////////////////////SECRETARIAS/////////////////////////
			//	echo '>>categoria 4.2>>';
				$qSecretaria = $ca->buscarMiembrosEquipo($conexion, $area['id_area'], $responsable,NULL); // Buscar responsable subprocesos de nivel 4 (gestiones)...
				
				$aParesDir=array();
				if (pg_num_rows ( $qSecretaria ) != 0) {//Pregunta si hay
				
				
					while ( $paresDir = pg_fetch_assoc ( $qSecretaria ) ) {
						$aParesDir [] = array (
								identificador => $paresDir ['identificador']
						);
					}
				
					foreach ( $aParesDir as $paresDir ) {
						$ced->guardarAplicantes ( $conexion, $responsable, $paresDir ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
						$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
					
						/////////////EVALUACION INDIVIDUAL///////////
						
						$ced->guardarAplicantesIndividual($conexion, $responsable, $paresDir ['identificador'], 'true', $idEvaluacion);
						
						////////////////////////					
					
					}
				
				
					if ($evaluacionPares ['cantidad_usuario'] == '0') {
						foreach ( $aParesDir as $paresDir ) {
							foreach ( $aParesDir as $oParesDir) {
								if ($paresDir ['identificador'] != $oParesDir ['identificador']) {
									$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'] , $oParesDir ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}
				
							$ced->guardarAplicantes ( $conexion, $paresDir  ['identificador'], $paresDir  ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						}
					} else {
							
						if (count ( $aParesDir ) > $evaluacionPares ['cantidad_usuario']) {
				
							foreach ( $aParesDir as $paresDir ) {
								$aMiembroEquipoUsuario = array ();
								$qMiembrosEquipoSinUsuarioActual = $ca->buscarMiembrosEquipo ( $conexion, $area ['id_area'], $responsable, $paresDir ['identificador'] ); // Buscar miembros de area sin el responsable.
								while ( $miembrosEquipoUsuario = pg_fetch_assoc ( $qMiembrosEquipoSinUsuarioActual ) ) {
									$aMiembroEquipoUsuario [] = array (
											identificador => $miembrosEquipoUsuario ['identificador']
									);
								}
				
								$paresLiderAleatorias = array_random ( $aMiembroEquipoUsuario, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
				
								foreach ( $paresLiderAleatorias as $parAleatorias ) {
									$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $parAleatorias ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
									
								$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $paresDir ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
							}
						} else {
				
							foreach ( $aParesDir as $paresDir ) {
								foreach ( $aParesDir as $oParesDir ) {
									if ($paresDir ['identificador'] != $oParesDir ['identificador']) {
										$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $oParesDir ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
									}
								}
								$ced->guardarAplicantes ( $conexion, $paresDir ['identificador'], $paresDir ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
							}
						}
					}
				
				}
				
				///////////////////FIN SECRETARIAS/////////////////////////			
			
//**********************************************************************************			
			//} else if (0) {
		   //} else if ($area ['categoria_area'] == '5' ) {

		   } else if ($area ['categoria_area'] == '5' && $responsable != '0') {
		   	//**********************************************************************************
				echo '>>nivel5 5>>'.$responsable.'--'.$area ['id_area'].'</BR>';				
				// echo '****************************AREA 5***************************<br/>';
				
			/*	if($responsable == '0'){
				$areaRecursiva = pg_fetch_assoc(ControladorAreas::buscarAreaResponsablePorUsuarioRecursivo($conexion, $area ['id_area']));
				$tipoArea = $areaRecursiva['clasificacion'];
				$arrayAreas = explode(',', $areaRecursiva['path']);
				$numAreas = sizeof($arrayAreas)-1;
				
				//print_r ($arrayAreas);
				
				for($i=$numAreas, $j=0 ; $j<$numAreas; $i--, $j++){
					$idArea=$arrayAreas[$i];
					$identificadorJefe = pg_fetch_result(ControladorAreas::buscarResponsableSubproceso($conexion,$idArea), 0, 'identificador');
					if($identificadorJefe != ''){
						break;
					}
				}
				$responsable= $identificadorJefe;
				}
				*/
				$aParesGestion = array ();
				
				$qParesGestion = $ca->obtenerParesXareaPadreYcategoria ( $conexion, '5', $area ['id_area_padre'], $area ['id_area'] ); // / escoge areas pares de gestion
				
				while ( $fila = pg_fetch_assoc ( $qParesGestion ) ) {
					$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $fila ['id_area'] ); // Buscar responsable área par.
					if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
						$aParesGestion [] = array (
								idArea => $fila ['id_area'] 
						);
					}
				}
				
				if ($evaluacionPares ['cantidad_usuario'] == '0') {
					foreach ( $aParesGestion as $paresLiderGestion ) {
						$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLiderGestion ['idArea'] ); // Buscar responsable área par.
						if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
							$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
							$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
						}
					}
				} else {
					
					if (count ( $aParesGestion ) > $evaluacionPares ['cantidad_usuario']) {
						$paresLiderAleatorias = array_random ( $aParesGestion, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
						
						foreach ( $paresLiderAleatorias as $paresLider ) {
							$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLider ['idArea'] ); // Buscar responsable área par.
							if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
								$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
								$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}
						}
					} else {
						
						foreach ( $aParesGestion as $paresLiderGestion ) {
							$qResponsableSubproceso = $ca->buscarResponsableSubproceso ( $conexion, $paresLiderGestion ['idArea'] ); // Buscar responsable área par.
							
							if (pg_num_rows ( $qResponsableSubproceso ) != 0) {
								$responsableSubproceso = pg_fetch_result ( $qResponsableSubproceso, 0, 'identificador' );
								$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}
						}
					}
				}
				
				$qMiembrosEquipo = $ca->buscarMiembrosEquipo ( $conexion, $area ['id_area'], $responsable ); // Buscar miembros de area sin el responsable.
				$aMiembroEquipo = array ();
				
				while ( $miembrosEquipo = pg_fetch_assoc ( $qMiembrosEquipo ) ) {
					$aMiembroEquipo [] = array (
							identificador => $miembrosEquipo ['identificador'] 
					);
				}
				
				foreach ( $aMiembroEquipo as $miembrosEquipo ) {
					
					
					$ced->guardarAplicantes ( $conexion, $responsable, $miembrosEquipo ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
					$ced->guardarAplicantes ( $conexion, $miembrosEquipo ['identificador'], $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
				
					/////////////EVALUACION INDIVIDUAL///////////
					
					$ced->guardarAplicantesIndividual($conexion, $responsable, $miembrosEquipo ['identificador'], 'true', $idEvaluacion);
					
					////////////////////////				
				
				}
				
				if ($evaluacionPares ['cantidad_usuario'] == '0') {
					foreach ( $aMiembroEquipo as $miembrosEquipo ) {
						foreach ( $aMiembroEquipo as $oMiembrosEquipo ) {
							if ($miembrosEquipo ['identificador'] != $oMiembrosEquipo ['identificador']) {
								$ced->guardarAplicantes ( $conexion, $miembrosEquipo ['identificador'], $oMiembrosEquipo ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}
						}
						$ced->guardarAplicantes ( $conexion, $miembrosEquipo ['identificador'], $miembrosEquipo ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
					}
				} else {
					
					if (count ( $aMiembroEquipo ) > $evaluacionPares ['cantidad_usuario']) {
						
						foreach ( $aMiembroEquipo as $miembrosEquipo ) {
							$aMiembroEquipoUsuario = array ();
							$qMiembrosEquipoSinUsuarioActual = $ca->buscarMiembrosEquipo ( $conexion, $area ['id_area'], $responsable, $miembrosEquipo ['identificador'] ); // Buscar miembros de area sin el responsable.
							while ( $miembrosEquipoUsuario = pg_fetch_assoc ( $qMiembrosEquipoSinUsuarioActual ) ) {
								$aMiembroEquipoUsuario [] = array (
										identificador => $miembrosEquipoUsuario ['identificador'] 
								);
							}
							
							$paresLiderAleatorias = array_random ( $aMiembroEquipoUsuario, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
							
							foreach ( $paresLiderAleatorias as $parAleatorias ) {
								$ced->guardarAplicantes ( $conexion, $miembrosEquipo ['identificador'], $parAleatorias ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}
							$ced->guardarAplicantes ( $conexion, $miembrosEquipo ['identificador'], $miembrosEquipo ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						}
					} else {
						foreach ( $aMiembroEquipo as $paresMiembro ) {
							foreach ( $aMiembroEquipo as $oParesMiembro ) {
								if ($paresMiembro ['identificador'] != $oParesMiembro ['identificador']) {
									$ced->guardarAplicantes ( $conexion, $paresMiembro ['identificador'], $oParesMiembro ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}
							$ced->guardarAplicantes ( $conexion, $paresMiembro ['identificador'], $paresMiembro ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						}
					}
				}
				try {
					
					//$ced->verificarAplicanteRegistrado($conexion, $responsable, $responsable,$autoevaluacion ['id_tipo_evaluacion'], $autoevaluacion ['tipo']);
					//if(pg_num_rows($ced) != 0)
				$ced->guardarAplicantes ( $conexion, $responsable, $responsable, $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluación.
				} catch (Exception $e) {
					echo '<br>error 1 <br>'.$e.'<br>';
				}
//_-------------------------------------------------------------------------------------------------------------------------------------
		   } else if ($area ['categoria_area'] == '5' && $responsable == '0') {
				echo '>>nivel5 5.2>>'.$responsable.'--'.$area ['id_area'].'</BR>';
//------------------------------------------------------------------------------------------------------------------------------------------
				//if($area ['id_area'] == 'UDATSAM04'){
				$areaRecursiva = pg_fetch_assoc(ControladorAreas::buscarAreaResponsablePorUsuarioRecursivo($conexion, $area ['id_area']));
				$tipoArea = $areaRecursiva['clasificacion'];
				$arrayAreas = explode(',', $areaRecursiva['path']);
				$numAreas = sizeof($arrayAreas)-1;
				
				print_r ($arrayAreas);
				
				for($i=$numAreas-1, $j=0 ; $j<$numAreas; $i--, $j++){
					$idArea=$arrayAreas[$i];
					$identificadorJefe = pg_fetch_result(ControladorAreas::buscarResponsableSubproceso($conexion,$idArea), 0, 'identificador');
					if($identificadorJefe != ''){
						break;
					}
				}
				$responsable= $identificadorJefe;
				
				//}
			
				
				$qMiembrosEquipoSR = $ca->buscarMiembrosEquipo ( $conexion, $area ['id_area'], NULL ); // Buscar miembros de area sin el responsable.
				$aMiembroEquipoSR = array ();
				
				while ( $miembrosEquipoSR = pg_fetch_assoc ( $qMiembrosEquipoSR ) ) {
					$aMiembroEquipoSR [] = array (
							identificador => $miembrosEquipoSR ['identificador'] 
					);
				}
				
				foreach ( $aMiembroEquipoSR as $miembrosEquipo ) {
					if($miembrosEquipo ['identificador'] != $responsable){					
				 $ced->guardarAplicantes ( $conexion, $responsable, $miembrosEquipo ['identificador'], $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
				 $ced->guardarAplicantes ( $conexion, $miembrosEquipo ['identificador'], $responsable, $evaluacionInferior ['id_tipo_evaluacion'], 'true', $evaluacionInferior ['tipo'] ); // Asignar evaluación de inferior a superior.
					}
					/////////////EVALUACION INDIVIDUAL///////////
						
					//$ced->guardarAplicantesIndividual($conexion, $responsable, $miembrosEquipo ['identificador'], 'true', $idEvaluacion);
						
					////////////////////////
				
				}
				
				
				
				echo '</br>'.$evaluacionPares ['cantidad_usuario'].'</br>';
				
				
				
				if ($evaluacionPares ['cantidad_usuario'] == '0') {
					foreach ( $aMiembroEquipoSR as $miembrosEquipoSR ) {
						foreach ( $aMiembroEquipoSR as $oMiembrosEquipoSR ) {
							if ($miembrosEquipoSR ['identificador'] != $oMiembrosEquipoSR ['identificador'] and  $oMiembrosEquipoSR ['identificador'] != $responsable) {
								$ced->guardarAplicantes ( $conexion, $miembrosEquipoSR ['identificador'], $oMiembrosEquipoSR ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}
						}
						$ced->guardarAplicantes ( $conexion, $miembrosEquipoSR ['identificador'], $miembrosEquipoSR ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
					}
				} else {
					
					if (count ( $aMiembroEquipoSR ) > $evaluacionPares ['cantidad_usuario']) {
						foreach ( $aMiembroEquipoSR as $miembrosEquipoSR ) {	
							$aMiembroEquipoUsuarioSR = array ();
							$qMiembrosEquipoSinUsuarioActualSR = $ca->buscarMiembrosEquipo ( $conexion, $area ['id_area'], NULL, $miembrosEquipoSR ['identificador'] ); // Buscar miembros de area sin el responsable.
							
							while ( $miembrosEquipoUsuarioSR = pg_fetch_assoc ( $qMiembrosEquipoSinUsuarioActualSR ) ) {
								$aMiembroEquipoUsuarioSR [] = array (
										identificador => $miembrosEquipoUsuarioSR ['identificador'] 
								);
							}
							
							$paresMiembrosEquipoAleatorias = array_random ( $aMiembroEquipoUsuarioSR, $evaluacionPares ['cantidad_usuario'] ); // Array aleatorio.
							
							foreach ( $paresMiembrosEquipoAleatorias as $parAleatoriasSR ) {
								if ($parAleatoriasSR ['identificador'] != $responsable)
									$ced->guardarAplicantes ( $conexion, $miembrosEquipoSR ['identificador'], $parAleatoriasSR ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
							}
							$ced->guardarAplicantes ( $conexion, $miembrosEquipoSR ['identificador'], $miembrosEquipoSR ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
						}
					} else {
						foreach ( $aMiembroEquipoSR as $paresMiembroSR ) {
							foreach ( $aMiembroEquipoSR as $oParesMiembroSR ) {
								if ($paresMiembroSR ['identificador'] != $oParesMiembroSR ['identificador'] and $oParesMiembroSR ['identificador'] != $responsable) {
									$ced->guardarAplicantes ( $conexion, $paresMiembroSR ['identificador'], $oParesMiembroSR ['identificador'], $evaluacionPares ['id_tipo_evaluacion'], 'true', $evaluacionPares ['tipo'] ); // Asignar evaluación par.
								}
							}
							$ced->guardarAplicantes ( $conexion, $paresMiembroSR ['identificador'], $paresMiembroSR ['identificador'], $autoevaluacion ['id_tipo_evaluacion'], 'true', $autoevaluacion ['tipo'] ); // Asignar autoevaluacion.
							//$ced->guardarAplicantes ( $conexion, $responsable, $responsableSubproceso, $evaluacionSuperior ['id_tipo_evaluacion'], 'true', $evaluacionSuperior ['tipo'] ); // Asignar evaluación de superior a inferior.
						}
					}
				}
			}
		}
	}
	echo '<div><label>EVALUACIÓN GENERADA SATISFACTORIAMENTE</label></div>';
	$conexion->ejecutarConsulta("commit;");
	} catch (Exception $e) {
		$conexion->ejecutarConsulta("rollback;");
		echo '<br>error general <br>'.$e.'<br>';
	}
	$conexion->desconectar();
	?>
</body>
</html>