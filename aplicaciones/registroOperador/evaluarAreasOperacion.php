<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idOperacion = ($_POST['idSolicitud']);
	$listaAreas = ($_POST['listaAreas']);
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$resultadoOperacion = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$areasRevisadas=true;
	$registrado = 0;
	$rechazado = 0;
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		//Verifica si la operación está asignada a un inspector, caso contrario se asigna a la persona que realiza la inspección
		$inspectorAsignado = $cr->listarInspectoresAsignados($conexion, $idOperacion);
		
		if(pg_num_rows($inspectorAsignado)==0){
			$res= $cr->guardarNuevoInspector($conexion, $idOperacion, $inspector, $inspector);
			$res= $cr->enviarOperacion($conexion, $idOperacion, 'asignado');
		}
		
		if(count($listaAreas)>0){
			for ($i=0; $i<count($listaAreas);$i++){
				//Guarda estado de área
				$cr->evaluarAreasOperacion($conexion, $idOperacion, $listaAreas[$i], $resultadoOperacion, $observaciones, $archivo);
				
				//Guarda inspector, calificación y fecha
				//$cr->guardarDatosInspeccion($conexion, $idOperacion, $listaAreas[$i], $inspector, $archivo, 'aprobado', $observaciones);
				$cr->guardarDatosInspeccion($conexion, $idOperacion, $listaAreas[$i], $inspector, $archivo, $resultadoOperacion, $observaciones);
			}
			
			//Consulta el estado de las areas evaluadas
			$areas = $cr->abrirAreasOperacion($conexion, $idOperacion);
			
			foreach ($areas as $area){
				if($area['estado']=='registrado'){
					$registrado ++;
				}else if($area['estado']=='rechazado'){
					$rechazado ++;
				}else{
					$areasRevisadas = false;
					break;
				}
			}
			
			if($areasRevisadas){
				if($registrado>$rechazado){
					$cr->evaluarOperacion($conexion, $idOperacion, 'registrado', $observaciones);
				}else{
					$cr->evaluarOperacion($conexion, $idOperacion, 'rechazado', $observaciones);
				}
			}
			
			//$cr->evaluarOperacion($conexion, $idOperacion, $resultadoOperacion);
					
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';
			//$mensaje['mensaje'] = $registrado .'-'. $rechazado .'-'. $areasRevisadas;

		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Debe seleccionar por lo menos un área.';
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
		
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>