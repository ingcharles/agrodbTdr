<?php

class ControladorComercializacion{
	
	public function listarCategoriaProducto ($conexion,$area){
		$res = $conexion->ejecutarConsulta("SELECT cp.*, a.nombre as nombre_area    
											FROM 
												g_comercializacion.categoria_producto as cp,
												g_estructura.area as a
											WHERE
												cp.id_area = a.id_area  
												and a.id_area='$area';");
		return $res;
	}
	
}