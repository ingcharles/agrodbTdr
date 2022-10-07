<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$idRequerimiento=$_POST['idRequerimiento'];
	$idFuncionarioReplicado=$_POST['idFuncionarioReplicado'];
	$conocimientoTema=$_POST['conocimientoTema'];
	$respuestaInquietudes=$_POST['respuestaInquietudes'];
	$manejoGrupo=$_POST['manejoGrupo'];
	$cumplimientoAgenda=$_POST['cumplimientoAgenda'];
	$conocimientosRelacionados=$_POST['conocimientosRelacionados'];
	$aplicaraInstitucion=$_POST['aplicaraInstitucion'];
	$asesoriaInterna=$_POST['asesoriaInterna'];
	
		
	try {
		$conexion = new Conexion();
		$cc = new ControladorCapacitacion();
		
		$conexion->ejecutarConsulta("begin;");
		$cc->insertarCalificacionReplicador($conexion,$idFuncionarioReplicado,$conocimientoTema,$respuestaInquietudes,$manejoGrupo,$cumplimientoAgenda, $conocimientosRelacionados,$aplicaraInstitucion,$asesoriaInterna);
		$cc->actualizarEstadoReplicacion($conexion, $idFuncionarioReplicado,'2');
		
		$resultado = pg_fetch_assoc($cc->verificarCambioEstadoReplica($conexion, $idRequerimiento));
		
		if($resultado['total'] == $resultado['estado']){
			$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento,'16');
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
		
		$conexion->ejecutarConsulta("commit;");
		
	} catch (Exception $ex) {
			$conexion->ejecutarConsulta("rollback;");
			$mensaje['mensaje'] = $ex->getMessage();
			$mensaje['error'] = $conexion->mensajeError;
		} finally {
			$conexion->desconectar();
		}
	
	} catch (Exception $ex) {
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		echo json_encode($mensaje);
	}
?>

