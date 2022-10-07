<?php

class ControladorOrdenamiento{
	
	private $categorias = 'g_inspeccion.categorias';
	private $categoriasPK = 'id_categoria';
	private $categoriasOrden = 'orden';
	private $categoriasFK = 'id_formulario';
	
	private $registros= array( 'categorias' => array('tabla' => 'g_inspeccion.categorias',
													 'PK' => 'id_categoria',
													 'orden' => 'orden',
													 'FK' => 'id_formulario'),
								'preguntas' => array('tabla' => 'g_inspeccion.preguntas',
													'PK' => 'id_pregunta',
													'orden' => 'orden',
													'FK' => 'id_categoria'),
								'estructuras' => array('tabla' => 'g_biblioteca.estructuras',
													'PK' => 'id_estructura',
													'orden' => 'orden',
													'FK' => 'id_estructura_padre'),
								'estructurasPadre' => array('tabla' => 'g_biblioteca.estructuras',
													'PK' => 'id_estructura',
													'orden' => 'orden',
													'FK' => 'id_resolucion'),
								'detalleRutasTransporte' => array('tabla' => 'g_servicios_linea.detalle_rutas_transporte',
													'PK' => 'id_detalle_rutas_transporte',
													'orden' => 'orden',
													'FK' => 'id_ruta_transporte'),
								'campo_detalle' => array('tabla' => 'g_conciliacion_bancaria.campo_detalle',
													'PK' => 'id_campo_detalle',
													'orden' => 'orden',
													'FK' => 'id_detalle_trama'),
								'campo_cabecera' => array('tabla' => 'g_conciliacion_bancaria.campo_cabecera',
													'PK' => 'id_campo_cabecera',
													'orden' => 'orden',
													'FK' => 'id_cabecera_trama'),
								'campo_documento' => array('tabla' => 'g_conciliacion_bancaria.campo_documento',
													 'PK' => 'id_campo_documento',
													 'orden' => 'orden',
													 'FK' => 'id_documento')
							);
	
	public function disminuirOrden($conexion, $idRegistro,$tabla){
	
		$nombreTabla = $this->registros[$tabla]['tabla'];
		$fk = $this->registros[$tabla]['FK'];
		$orden = $this->registros[$tabla]['orden'];
		$pk = $this->registros[$tabla]['PK'];
		
		$res = $conexion->ejecutarConsulta("select
												public.disminuir_orden($idRegistro, '$nombreTabla', '$fk', '$orden','$pk');");
	
		return $res;
	}
	
	public function aumentarOrden($conexion, $idRegistro,$tabla){
		$nombreTabla = $this->registros[$tabla]['tabla'];
		$fk = $this->registros[$tabla]['FK'];
		$orden = $this->registros[$tabla]['orden'];
		$pk = $this->registros[$tabla]['PK'];
				
		$res = $conexion->ejecutarConsulta("select
												public.aumentar_orden($idRegistro,'$nombreTabla','$fk','$orden','$pk');");
		
		return $idRegistro;
	}
}