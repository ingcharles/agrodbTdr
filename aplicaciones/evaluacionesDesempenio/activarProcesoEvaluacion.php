<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEvaluacionesDesempenio.php';

	try {
	$idEvaluacion = $_POST ['idEvaluacion'];
	$conexion = new Conexion();
	$ced = new ControladorEvaluacionesDesempenio ();
	$conexion->ejecutarConsulta("begin;");
	//-----------actualizar estado-----------------------------------------------------------------
	$ced->actualizarEvaluacion($conexion,$idEvaluacion,'activo',3);
	$estadoCatastro=pg_fetch_result($ced->devolverEvaluacionVigente ($conexion,1,'',$idEvaluacion ),0,'estado_catastro');
	
	if(strcmp($estadoCatastro, 'Si') == 0){
		$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Contrato por funcionario');
		$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar reponsables RRHH');
		$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar usuarios');
		$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Administrar responsables');
		$ced->activarInactivarCatastroOpcion($conexion, 'inactivo','Manual de funciones');
	}else {
		$ced->activarInactivarCatastroOpcion($conexion, 'activo','Contrato por funcionario');
		$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar reponsables RRHH');
		$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar usuarios');
		$ced->activarInactivarCatastroOpcion($conexion, 'activo','Administrar responsables');
		$ced->activarInactivarCatastroOpcion($conexion, 'activo','Manual de funciones');
	}
	
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = "EVALUACIÓN GENERADA SATISFACTORIAMENTE";
	$conexion->ejecutarConsulta("commit;");
	echo json_encode($mensaje);
	} catch (Exception $e) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia".$e;
		echo json_encode($mensaje);
	}
	$conexion->desconectar();
	
//-------------------------------------------------------------------------------------------------------------------------------------------------------
?>
