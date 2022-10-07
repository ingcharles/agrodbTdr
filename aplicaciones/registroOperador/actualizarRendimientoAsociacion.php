<?php

session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try{

	$idMiembro = $_POST['identificador'];
	$identificadorMiembro = $_POST['identificacionMiembro'];	
	$nombreMiembro = $_POST['nombreMiembro'];
	$apellidoMiembro = $_POST['apellidoMiembro'];
	$codigoMagap = $_POST['codigoMagap'];
	
	
	$idMiembroAnterior = $_POST['identificadorMiembroAnterior'];
	$nombreMiembroAnterior = $_POST['nombreMiembroAnterior'];
	$apellidoMiembroAnterior = $_POST['apellidoMiembroAnterior'];
	$codigoMagapAnterior = $_POST['codigoMagapAnterior']; 
	
	$usuario = $_SESSION['usuario'];
	
	if( $_POST['codigoMagap']!=''){
		$codigoMagap= $_POST['codigoMagap'];
	}else{
		$codigoMagap="";
		}
		
		try{
		
			$conexion = new Conexion();
			$cro = new ControladorRegistroOperador();
			$ca = new ControladorAuditoria();
			
			$cro -> actualizarCabeceraMiembroAsociacion($conexion, $idMiembro, $identificadorMiembro, $nombreMiembro, $apellidoMiembro, $codigoMagap);
			
			$qDatosMiembroAsociacion = $cro -> obtenerDatosMiembroAsociacionXIdMiembro($conexion, $idMiembro);
			$datosMiembroAsociacion = pg_fetch_assoc($qDatosMiembroAsociacion);
			
			$qDatosDetalleMiembroAsociacion = $cro -> obtenerDatosDetalleMiembroAsociacionXIdMiembro($conexion, $idMiembro);
			$datosDetalleMiembroAsociacion = pg_fetch_assoc($qDatosDetalleMiembroAsociacion);
		
					
			if($identificadorMiembro != $idMiembroAnterior || $nombreMiembro != $nombreMiembroAnterior || $apellidoMiembro != $apellidoMiembroAnterior || $codigoMagap != $codigoMagapAnterior){
				
				$observacionAuditoria.= "El operador ".$usuario. " ha modificado";
				
				if($identificadorMiembro != $idMiembroAnterior){
					$observacionAuditoria.= " la identificación del miembro de asociación de ".$idMiembroAnterior." a ".$identificadorMiembro; 
				}
				
				if($nombreMiembro != $nombreMiembroAnterior){
					$observacionAuditoria.= " el nombre del miembro de asociación de ".$nombreMiembroAnterior." a ".$nombreMiembro;
				}
				
				if($apellidoMiembro != $apellidoMiembroAnterior){
					$observacionAuditoria.= " el apellido del miembro de asociación de ".$apellidoMiembroAnterior." a ".$apellidoMiembro;
				}
				
				if($codigoMagap != $codigoMagapAnterior){
					$observacionAuditoria.= " el codigo magap del miembro de asociación de ".$codigoMagapAnterior." a ".$codigoMagap;
				}
				
				$ca->actualizarAuditoriaXIdMiembroASociacion($conexion, $idMiembro);
				
				$ca->guardarAuditoriaAsociacion($conexion, $idMiembro, $datosMiembroAsociacion['codigo_miembro_asociacion'], $identificadorMiembro, $usuario, $nombreMiembro, $apellidoMiembro, $codigoMagap, $datosDetalleMiembroAsociacion['id_operacion'],  $datosDetalleMiembroAsociacion['id_area'], $datosDetalleMiembroAsociacion['id_sitio'], $datosDetalleMiembroAsociacion['rendimiento'], $observacionAuditoria, 'activo');
				
				//TODO: REINICIAR PROCESO A DOCUMENTAL.
				$cro->enviarOperacionEstadoAnterior($conexion, $datosDetalleMiembroAsociacion['id_operacion']);
				$cro->enviarOperacion($conexion, $datosDetalleMiembroAsociacion['id_operacion'], 'subsanacion');
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos del miembro han sido actualizados satisfactoriamente';
				
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