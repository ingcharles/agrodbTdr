<?php

class ControladorValidacion{
	
	public function validarDatos($conexion){
		
		$consulta = "SELECT c_empleado_id as id, identificacion as identificador FROM contratos.c_empleado WHERE tipo_identificacion = 'C' and validacion_rc is null";
		
		echo $consulta;
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function validarDatosFuncion($conexion){
	
		$consulta = "SELECT c_empleado_id as id, identificacion as identificador FROM contratos.c_empleado WHERE tipo_identificacion = 'C' and validacion_f is null LIMIT 200000";
	
		echo $consulta;
	
		$res = $conexion->ejecutarConsulta($consulta);
	
		return $res;
	}
	
	public function actualizarDatos ($conexion, $estado, $nombre, $identificador){
		
		$consulta = "UPDATE contratos.c_empleado SET validacion_rc = '$estado', nombre_validacion = '$nombre' WHERE c_empleado_id = '$identificador'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actualizarDatosFuncion ($conexion, $estado, $identificador){
	
		$consulta = "UPDATE contratos.c_empleado SET validacion_f = '$estado' WHERE c_empleado_id = '$identificador'";
	
		$res = $conexion->ejecutarConsulta($consulta);
	
		return $res;
	}
	
	
}
?>
