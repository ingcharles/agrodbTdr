<?php 

class ControladorDirectorio{
	
	public function obtenerOficinas ($conexion){
		$res = $conexion->ejecutarConsulta("select id_localizacion, nombre
  											from g_catalogos.localizacion
  											where categoria = 3
											order by 2;");
		return $res;
	}
	
	public function obtenerFuncionariosPorArea($conexion,$areas = null, $apellido = null){
		$consulta='';
		if($areas != null)
			$consulta = " a.id_area IN $areas ";
		else
			$consulta = " upper(e.apellido) like upper('%$apellido%') ";
		
		
		
		$res = $conexion->ejecutarConsulta("
											SELECT 
												distinct e.identificador, 
												e.nombre as nombre, 
												e.apellido as apellido, 
												d.id_oficina, 
												l.nombre as oficina, 
												a.nombre as area,
												l.otros as telefono,
												e.extension_magap as extension
								  			FROM 
												g_uath.ficha_empleado e, 
												g_uath.datos_contrato d, 
												g_catalogos.localizacion l, 
												g_estructura.funcionarios f, 
												g_estructura.area a
											WHERE 
												e.identificador=d.identificador AND 
												d.id_oficina=l.id_localizacion AND 
												d.identificador=f.identificador AND 
												f.id_area=a.id_area AND
												d.estado = 1 AND
												" . $consulta . "
											ORDER BY
												5,6,3,2");
		return $res;
	}
	
	
}