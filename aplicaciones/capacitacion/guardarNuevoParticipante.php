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
	$nombreFuncionario=htmlspecialchars ( $_POST ['nombreFuncionario'], ENT_NOQUOTES, 'UTF-8' );
	$area=htmlspecialchars ( $_POST ['area'], ENT_NOQUOTES, 'UTF-8' );
	$categoriaArea = htmlspecialchars ($_POST['categoriaArea'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion->ejecutarConsulta("begin;");
		if($identificador!='Todos'){
			if(pg_num_rows($cc->consultarFuncionarioCapacitadoBloqueado($conexion, $identificador, '1'))==0){
				$qParticipante=$cc -> guardarParticipantesEvento($conexion, $idRequerimiento, $identificador,'1');
				$mensaje ['estado'] = 'exito';
				$mensaje['mensaje'] = $cc->imprimirLineaAsistenteCapacitacion(pg_fetch_result($qParticipante, 0, 'id_participantes'), $nombreFuncionario);
			}else{
				$mensaje ['mensaje'] = 'El funcionario se encuentra inhabilitado para participar en otra capacitaciones por no culminar el anterior proceso de capacitación.';
			}
		}else{
			if($categoriaArea == '3' || $categoriaArea == '1'){
				$areaSubproceso = "'".$area."',";
			}else{
				$qAreasSubProcesos = $ca->buscarAreasSubprocesos($conexion, $area);
				$areaSubproceso = "'".$area."',";
				while($fila = pg_fetch_assoc($qAreasSubProcesos)){
					$areaSubproceso .= "'".$fila['id_area']."',";
				}
			}
			
			$imprimirFuncionarioCapacitacion = '';
			$banderaGuardar=true;
			$areaSubproceso = "(".rtrim($areaSubproceso,',').")";
			$funcionarios = $ca->obtenerFuncionariosXareasCapacitacion($conexion, $areaSubproceso);
			$arrayFuncionarios=pg_fetch_array($funcionarios);
		
			if(pg_num_rows($funcionarios)!=0){
			//while ($resultado = pg_fetch_assoc($funcionarios)){
			foreach ($arrayFuncionarios as $resultado){
				if(pg_num_rows($cc->consultarFuncionarioCapacitadoBloqueado($conexion, $resultado['identificador'], '1'))!=0)
					$banderaGuardar=false;
			//}
			}
			$funcionarioss = $ca->obtenerFuncionariosXareasCapacitacion($conexion, $areaSubproceso);
			if($banderaGuardar){
				while ($resultado = pg_fetch_assoc($funcionarioss)){
					$qParticipante=$cc -> guardarParticipantesEvento($conexion, $idRequerimiento, $resultado['identificador'],'1');
					$imprimirFuncionarioCapacitacion.= $cc->imprimirLineaAsistenteCapacitacion(pg_fetch_result($qParticipante, 0, 'id_participantes'), $resultado['apellido'].' '.$resultado['nombre']);
				}
				$mensaje ['estado'] = 'exito';
				$mensaje['mensaje']=$imprimirFuncionarioCapacitacion;
			}else{
				$mensaje ['mensaje'] = 'Unos de los funcionarios se encuentra inhabilitado para participar en otra capacitaciones por no culminar el anterior proceso de capacitación.';
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