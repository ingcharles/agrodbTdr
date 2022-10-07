<?php 

class ControladorMensualizacionDecimos{
	
	public function obtenerMensualizacionDecimos ($conexion,$identificador, $anioActual){
				
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_uath.mensualizacion_sueldos 
											where
												identificador='$identificador' and
												anio_mensualizacion = $anioActual;");
				return $res;
	}
	
	public function guardarMensualizacionDecimos ($conexion, $identificador, $anioActual, $respuestaMensualizacionDecimo, $rutaMensualizacionDecimo){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_uath.mensualizacion_sueldos(
										            identificador, mensualizacion_decimo, ruta_mensualizacion_decimo, anio_mensualizacion)
										    VALUES ('$identificador', '$respuestaMensualizacionDecimo', '$rutaMensualizacionDecimo', $anioActual);");
		return $res;
	}
		
}