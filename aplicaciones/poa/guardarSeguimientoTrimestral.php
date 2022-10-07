<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$idItem = $_POST['idItem'];
	$trimestre = $_POST['trimestre'];
	
	$meta1 = $_POST['meta1'];
	$avanceMeta1 = $_POST['avanceMeta1']?$_POST['avanceMeta1']:0;
	$porcentajeAvance1 = $_POST['porcentajeMeta1']?$_POST['porcentajeMeta1']:0;
	$numeroItems1 = $_POST['numeroRealizados1']?$_POST['numeroRealizados1']:0;
	$numeroPlanificados1 = $_POST['numeroPlanificados1']?$_POST['numeroPlanificados1']:0;
	$porcentajeRealizados1 = $_POST['porcentajeRealizados1']?$_POST['porcentajeRealizados1']:0;
	$observacionesMetas1 = $_POST['observacionesMeta1'];
	
	$meta2 = $_POST['meta2'];
	$avanceMeta2 = $_POST['avanceMeta2']?$_POST['avanceMeta2']:0;
	$porcentajeAvance2 = $_POST['porcentajeMeta2']?$_POST['porcentajeMeta2']:0;
	$numeroItems2 = $_POST['numeroRealizados2']?$_POST['numeroRealizados2']:0;
	$numeroPlanificados2 = $_POST['numeroPlanificados2']?$_POST['numeroPlanificados2']:0;
	$porcentajeRealizados2 = $_POST['porcentajeRealizados2']?$_POST['porcentajeRealizados2']:0;
	$observacionesMetas2 = $_POST['observacionesMeta2'];
	
	$meta3 = $_POST['meta3'];
	$avanceMeta3 = $_POST['avanceMeta3']?$_POST['avanceMeta3']:0;
	$porcentajeAvance3 = $_POST['porcentajeMeta3']?$_POST['porcentajeMeta3']:0;
	$numeroItems3 = $_POST['numeroRealizados3']?$_POST['numeroRealizados3']:0;
	$numeroPlanificados3 = $_POST['numeroPlanificados3']?$_POST['numeroPlanificados3']:0;
	$porcentajeRealizados3 = $_POST['porcentajeRealizados3']?$_POST['porcentajeRealizados3']:0;
	$observacionesMetas3 = $_POST['observacionesMeta3'];
	
	$meta4 = $_POST['meta4'];
	$avanceMeta4 = $_POST['avanceMeta4']?$_POST['avanceMeta4']:0;
	$porcentajeAvance4 = $_POST['porcentajeMeta4']?$_POST['porcentajeMeta4']:0;
	$numeroItems4 = $_POST['numeroRealizados4']?$_POST['numeroRealizados4']:0;
	$numeroPlanificados4 = $_POST['numeroPlanificados4']?$_POST['numeroPlanificados4']:0;
	$porcentajeRealizados4 = $_POST['porcentajeRealizados4']?$_POST['porcentajeRealizados4']:0;
	$observacionesMetas4 = $_POST['observacionesMeta4'];
	
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
		if(($avanceMeta1 > 0 ) || ($avanceMeta2 > 0 ) || ($avanceMeta3 > 0 ) || ($avanceMeta4 > 0 ) || 
			($presupuestoEjecutado1 > 0 ) || ($presupuestoEjecutado2 > 0 ) || ($presupuestoEjecutado3 > 0 ) || 
			($presupuestoEjecutado4 > 0 )){
			
			switch ($trimestre){
				case 1:{
					if($numeroItems1 != 0 && $numeroPlanificados1 != 0 ){
						$meta = $cpoa->listarSeguimientoXTrimestre($conexion, $idItem, $trimestre);
						
						if(pg_num_rows($meta)>0){
							$cpoa->actualizarSeguimiento($conexion, $idItem, $trimestre, $meta1, $avanceMeta1, $porcentajeAvance1, $numeroItems1, $numeroPlanificados1, $porcentajeRealizados1, $observacionesMetas1);
						}else{
							$cpoa->guardarNuevoSeguimiento($conexion, $idItem, $trimestre, $meta1, $avanceMeta1, $porcentajeAvance1, $numeroItems1, $numeroPlanificados1, $porcentajeRealizados1, $observacionesMetas1);
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'El seguimiento trimestral ha sido guardado satisfactoriamente';
						
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'Los valores no pueden ser negativos o ceros ...';
					}
					break;
				}
				
				case 2:{
					if($numeroItems4 != 0 && $numeroPlanificados4 != 0 ){
						$meta = $cpoa->listarSeguimientoXTrimestre($conexion, $idItem, $trimestre);
						
						if(pg_num_rows($meta)>0){
							$cpoa->actualizarSeguimiento($conexion, $idItem, $trimestre, $meta2, $avanceMeta2, $porcentajeAvance2, $numeroItems2, $numeroPlanificados2, $porcentajeRealizados2, $observacionesMetas2);
						}else{
							$cpoa->guardarNuevoSeguimiento($conexion, $idItem, $trimestre, $meta2, $avanceMeta2, $porcentajeAvance2, $numeroItems2, $numeroPlanificados2, $porcentajeRealizados2, $observacionesMetas2);
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'El seguimiento trimestral ha sido guardado satisfactoriamente';
						
						}else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = 'Los valores no pueden ser negativos o ceros ...';
					}
					
					break;
				}
				
				case 3:{
					if($numeroItems3 != 0 && $numeroPlanificados3 != 0 ){
						$meta = $cpoa->listarSeguimientoXTrimestre($conexion, $idItem, $trimestre);
						
						if(pg_num_rows($meta)>0){
							$cpoa->actualizarSeguimiento($conexion, $idItem, $trimestre, $meta3, $avanceMeta3, $porcentajeAvance3, $numeroItems3, $numeroPlanificados3, $porcentajeRealizados3, $observacionesMetas3);
						}else{
							$cpoa->guardarNuevoSeguimiento($conexion, $idItem, $trimestre, $meta3, $avanceMeta3, $porcentajeAvance3, $numeroItems3, $numeroPlanificados3, $porcentajeRealizados3, $observacionesMetas3);
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'El seguimiento trimestral ha sido guardado satisfactoriamente';
						
						}else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = 'Los valores no pueden ser negativos o ceros ...';
					}
					break;
				}
				
				case 4:{
					if($numeroItems4 != 0 && $numeroPlanificados4 != 0 ){
						$meta = $cpoa->listarSeguimientoXTrimestre($conexion, $idItem, $trimestre);
						
						if(pg_num_rows($meta)>0){
							$cpoa->actualizarSeguimiento($conexion, $idItem, $trimestre, $meta4, $avanceMeta4, $porcentajeAvance4, $numeroItems4, $numeroPlanificados4, $porcentajeRealizados4, $observacionesMetas4);
						}else{
							$cpoa->guardarNuevoSeguimiento($conexion, $idItem, $trimestre, $meta4, $avanceMeta4, $porcentajeAvance4, $numeroItems4, $numeroPlanificados4, $porcentajeRealizados4, $observacionesMetas4);
						}
						
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'El seguimiento trimestral ha sido guardado satisfactoriamente';
						
						}else{
							$mensaje['estado'] = 'error';
							$mensaje['mensaje'] = 'Los valores no pueden ser negativos o ceros ...';
					}
					break;
				}
				
				default : {
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = 'El trimestre no es válido.';
					break;
				}
			}
		
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'No puede ingresar valores negativos o ceros.';
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