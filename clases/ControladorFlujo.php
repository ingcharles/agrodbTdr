<?php

class ControladorFlujo{
	
	public $listaEstado = array();
	
	
	public function obtenerEstadoFlujo($conexion,$identificador, $idflujo){
		
		$sql="
				Select 
					*
				From 
					g_flujos_procesos.flujos fl inner join
					g_usuario.usuarios_perfiles as up
					on fl.id_perfil=up.id_perfil
				where 
					fl.id_flujo = '$idflujo' and 
					up.identificador='$identificador'
					and fl.estado_flujo not in ('inicial','final')
					and tipo_flujo = 'principal'";
		
		$res=$conexion->ejecutarConsulta($sql);
		
		$this->listaEstado = pg_fetch_all($res);

		
		foreach ($this->listaEstado as $fila)
			$estado .= "'".$fila['estado_flujo']."',";
				
		$estado = "(".rtrim($estado,',').")";
		
		return $estado;
		
	}
	
	
	
}