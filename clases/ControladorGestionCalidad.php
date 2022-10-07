<?php

class ControladorGestionCalidad{
	public function guardarHallazgo($conexion, $area, $tipo, $hallazgo, $norma, $fecha, $auditor, $controlador){
				
		$res = $conexion->ejecutarConsulta("INSERT INTO g_gestion_calidad.hallazgos(
            									id_area,
												tipo,
												hallazgo,
												norma,
												fecha,
												auditor,
												controlador) 
										    VALUES ('$area',
												'$tipo',
												'$hallazgo',
												'$norma',
												to_timestamp('$fecha','dd/mm/yyyy'),
												'$auditor',
												'$controlador');");
		
		return $res;
	}
	
	public function listarHallazgos($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												h.*,
												a.nombre
											FROM
												g_gestion_calidad.hallazgos h,
												g_estructura.area a
											WHERE
												h.id_area = a.id_area
											ORDER BY
												a.nombre, h.tipo, h.estado	
											;");
		
		return $res;
	}
	
	public function listarHallazgosArea($conexion, $area){
		$res = $conexion->ejecutarConsulta("SELECT
												h.*,
												a.nombre
											FROM
												g_gestion_calidad.hallazgos h,
												g_estructura.area a
											WHERE
												h.id_area = a.id_area
												and h.id_area = '$area'
											ORDER BY
												a.nombre, h.estado
											;");
	
		return $res;
	}
	
	public function abrirHallazgo($conexion, $idHallazgo){
		$res = $conexion->ejecutarConsulta("SELECT
												h.*,
												to_char(h.fecha, 'DD/MM/YYYY') as fecha_formateada,
												a.nombre, 
												(fe.apellido || ', ' || fe.nombre) as nombre_controlador
											FROM
												g_gestion_calidad.hallazgos h,
												g_estructura.area a,
												g_uath.ficha_empleado fe
											WHERE
												h.id_area = a.id_area
												and h.controlador = fe.identificador
												and h.id_hallazgo = $idHallazgo
											;");
	
		return $res;
	}
	
	public function obtenerCausaRaiz($conexion, $hallazgo){
		$res = $conexion->ejecutarConsulta("
				select
					*
				from
					g_gestion_calidad.causas
				where
					id_hallazgo = $hallazgo and
					es_raiz = true;");
	
				return $res;
	}
	
	public function grabarCausaRaiz($conexion, $causa){
		$res = $conexion->ejecutarConsulta("
				update g_gestion_calidad.causas
					set es_raiz = true
				where
					id_causa = $causa
				returning
					descripcion;");
	
				return $res;
	}
	
	public function abrirCausas($conexion, $idHallazgo){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_gestion_calidad.causas c
											where
												c.id_hallazgo = $idHallazgo
											order by
												c.id_causa;");
	
				return $res;
	}
	
	public function abrirAcciones($conexion, $idHallazgo){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_gestion_calidad.acciones a
											where
												a.id_hallazgo = $idHallazgo
											order by
												a.id_accion;
				;");
	
				return $res;
	}
	
	public function ingresarNuevaCausa($conexion, $hallazgo, $causa){
				
		$res = $conexion->ejecutarConsulta("insert into
				g_gestion_calidad.causas(id_hallazgo,descripcion)
				values
				($hallazgo,'$causa')
				returning
				id_causa;");
				return $res;
	}
	
	public function imprimirLineaCausa($idCausa, $causa, $permitirBorrar = true){
		$linea= '<tr id="R' . $idCausa . '">' .
				'<td width="100%">' .
				$causa.
				'</td>'.
				'<td>';				
		if($permitirBorrar)
			$linea.= 
				'<form class="borrar" data-rutaAplicacion="gestionCalidad" data-opcion="eliminarCausa">' .
				'<input type="hidden" name="causa" value="' . $idCausa . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>';
								
		return $linea . '</td></tr>';
	}
	
	public function actualizarValoresDeMatriz($conexion, $causasAAcualizar){
		$registrosAActualizar = '';
		foreach ($causasAAcualizar as $causa)
			$registrosAActualizar .= ' ('.$causa['c1'].','.$causa['c2'].','.$causa['valor_c1'].','.$causa['valor_c2'].'),';
		
		$res = $conexion->ejecutarConsulta('
				update g_gestion_calidad.valores_de_priorizacion as vdp set
					valoracion_uno = raa.valor_c1,
					valoracion_dos = raa.valor_c2
				from ( 
					values ' . substr($registrosAActualizar,0,-1) . '
				) as raa(c1, c2, valor_c1, valor_c2)
				where 
					vdp.id_causa_uno = raa.c1 and
					vdp.id_causa_dos = raa.c2;');
		return $res;
	}
	
	public function generarMatrizDePriorizacion($conexion, $idHallazgo){
		$causas = pg_fetch_all($this->abrirCausas($conexion, $idHallazgo));
		$matriz = array();
		for($i = 0; $i < count($causas)-1; $i++)
			for($j = $i+1; $j < count($causas); $j++)
				$matriz[] = array($causas[$i][id_causa],$causas[$j][id_causa]);
		$valores = '';
		foreach ($matriz as $fila)
			$valores .= ' ('. $fila[0] . ', ' . $fila[1] .', ' . $idHallazgo . '),';
		$this->guardarRegistroEnMatrizDePriorizacion($conexion, $valores);
	}
	
	public function guardarRegistroEnMatrizDePriorizacion($conexion, $valores){
				
		$res = $conexion->ejecutarConsulta('insert into
				g_gestion_calidad.valores_de_priorizacion(id_causa_uno, id_causa_dos, id_hallazgo)
				values ' . substr($valores,0,-1));
		return $res;
	}
	
	public function cambiarEstadoDeHallazgo($conexion, $idHallazgo, $nuevoEstado){
		$res = $conexion->ejecutarConsulta("
				update g_gestion_calidad.hallazgos
				set estado = '$nuevoEstado'
				where id_hallazgo = $idHallazgo");
		return $res;
	}
	
	public function imprimirMatrizDePriorizacion($conexion, $idHallazgo){
		$res = $conexion->ejecutarConsulta("
				select
					vdp.id_causa_uno, c.descripcion as causa_uno, vdp.id_causa_dos, c2.descripcion as causa_dos
				from
					g_gestion_calidad.valores_de_priorizacion vdp,
					g_gestion_calidad.causas c,
					g_gestion_calidad.causas c2
				where
					vdp.id_causa_uno = c.id_causa
					and vdp.id_causa_dos = c2.id_causa
					and vdp.id_hallazgo = $idHallazgo");
		$html = '<table><thead><tr><th>A</th><th>B</th><th>A vs B es ...</th></tr></thead>';
		
		while ($fila = pg_fetch_assoc($res)){
			$html .= '<tr>'.
					'<td>'. $fila['causa_uno'] . '</td>'.
					'<td>'. $fila['causa_dos'] . '</td>'.
					'<td style="width: 1%">'.
						'<select data-distribuir="no" name="c' . $fila['id_causa_uno'] . 'c' . $fila['id_causa_dos'] . '">' .
							'<option value="">Seleccione una opcion..</option>'.
							'<option value="mayor">Más importante</option>'.
							'<option value="igual">Igual de importante</option>'.
							'<option value="menor">Menos importante</option>'.
						'</select>' .
					'</td></tr>';
		}
		
		$html .= '</table><button type="submit">Calcular causa raíz</button><button id="reiniciar" type="button">Reiniciar valores</button>';
		return $html;
	}
	
}