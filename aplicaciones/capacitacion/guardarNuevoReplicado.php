<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAreas.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion();
	$cc = new ControladorCapacitacion();
	$ca = new ControladorAreas();

	$idRequerimiento=htmlspecialchars ( $_POST ['idRequerimiento'], ENT_NOQUOTES, 'UTF-8' );
	$identificador=htmlspecialchars ( $_POST ['ocupante'], ENT_NOQUOTES, 'UTF-8' );
	$area=htmlspecialchars ( $_POST ['area'], ENT_NOQUOTES, 'UTF-8' );
	$categoriaArea = htmlspecialchars ($_POST['categoriaArea'],ENT_NOQUOTES,'UTF-8');
	$identificadorReplicador= ($_POST['identificadorReplicador']);
	$identificadorReplicado= ($_POST['identificadorReplicado']);
	$nombreReplicado= ($_POST['nombreReplicado']);

	try {
		$conexion->ejecutarConsulta("begin;");

		if($identificador!='Todos'){
			if($identificadorReplicador!=$identificadorReplicado){
				if(pg_num_rows($cc->consultarFuncionarioReplicadoBloqueado($conexion,$idRequerimiento, $identificadorReplicado))==0){
					$qIdReplicado=$cc -> guardarFuncionariosReplicados($conexion, $idRequerimiento, $identificadorReplicador,$identificadorReplicado);
					$mensaje ['estado'] = 'exito';
					$mensaje['mensaje'] = $cc->imprimirLineaReplicado(pg_fetch_result($qIdReplicado, 0, 'id_funcionarios_replicados'), $nombreReplicado,$identificadorReplicador);
				}else{
					$mensaje ['mensaje'] = 'El funcionario ya ha sido agregado para ser replicado en esta capacitación.';
				}
			}else{
				$mensaje ['mensaje'] = 'El funcionario capacitado y replicado es el mismo, no puede ser agregado.';
					

			}
		}else{
			$qAreasSubProcesos = $ca->buscarAreasSubprocesos($conexion, $area);

			while($fila = pg_fetch_assoc($qAreasSubProcesos)){
				$areaSubproceso .= "'".$fila['id_area']."',";
			}
			$imprimirFuncionarioCapacitacion = '';
			$banderaGuardar=true;
			$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
			$funcionarios = $ca->obtenerFuncionariosXareasCapacitacion($conexion, $areaSubproceso);
			$arrayFuncionarios=pg_fetch_all($funcionarios);

			if(pg_num_rows($funcionarios)!=0){
				foreach ($arrayFuncionarios as $resultado){
					if(pg_num_rows($cc->consultarFuncionarioReplicadoBloqueado($conexion,$idRequerimiento, $resultado['identificador']))!=0){
						$banderaGuardar=false;
					}
				}
				if($banderaGuardar){
					foreach ($arrayFuncionarios as $resultado){
						if($identificadorReplicador!=$resultado['identificador']){
							$qIdReplicado=$cc -> guardarFuncionariosReplicados($conexion, $idRequerimiento, $identificadorReplicador,$resultado['identificador']);
							$imprimirFuncionarioReplicado.=$cc->imprimirLineaReplicado(pg_fetch_result($qIdReplicado, 0, 'id_funcionarios_replicados'),  $resultado['apellido'].' '.$resultado['nombre'],$identificadorReplicador);
						}
					}
					$mensaje ['estado'] = 'exito';
					$mensaje['mensaje']=$imprimirFuncionarioReplicado;
				}else{
					$mensaje ['mensaje'] = 'Unos de los funcionarios ya se encuentra registrado en esta capacitación para ser replicados.';
				}
			}else{
				$mensaje ['mensaje'] = 'En la lista no existen funcionarios para se agregados.';
			}
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