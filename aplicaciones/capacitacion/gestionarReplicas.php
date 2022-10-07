<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCapacitacion.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$idRequerimiento=$_POST['idRequerimiento'];
	$ocupante_id= ($_POST['ocupanteId']);
	$tipoReplica = $_POST['tipoReplicacion'];
	$descripcionReplica = $_POST['descripcionReplica'];
	$modoReplica = $_POST['modoReplica'];
	$archivo = $_POST['archivo'];
	try {
		$conexion = new Conexion();
		$cc = new ControladorCapacitacion();
		$cca = new ControladorCatastro();
		
		$conexion->ejecutarConsulta("begin;");
		$resFuncionarios=$cc->obtenerFuncionarios($conexion,$idRequerimiento);
		$cc->actualizarTipoReplicaRequerimiento($conexion, $idRequerimiento, $tipoReplica, $descripcionReplica, $modoReplica);
		
		switch ($tipoReplica){
			case 'replica':				
				$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento,'15');
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
			break;
			
			case 'procedimiento':
			case 'manual':
				while ($fila = pg_fetch_assoc($resFuncionarios)){
					$cc -> guardarFuncionariosReplicados($conexion, $idRequerimiento, $fila['identificador'],$fila['identificador']);
				}
				$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento,'19');
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
			break;
			
			case 'noReplica':
				$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento,'18');
				$res=$cc->obtenerRequerimientosUsuario($conexion, '','','', '', '', '', '', $idRequerimiento);
				
				while($fila = pg_fetch_assoc($res)){
					$cca->crearDatosCapacitacion($conexion, $fila['funcionario'], $fila['nombre_evento'], $fila['empresa_capacitadora'], $fila['pais'],'', 'Ingresado', $fila['horas'], '', $fila['fecha_inicio'], $fila['fecha_fin']);
				}
				$cc->bloqueoAsistentes($conexion,$idRequerimiento,'0');
				$cc->actualizarArchivoNoReplica($conexion,$idRequerimiento,$archivo);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';		
			break;
		}

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

