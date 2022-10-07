<?php

class ControladorProgramacionPresupuestaria{

	public function listarParametros ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.parametros p
											WHERE
												p.estado = 'activo'
											ORDER BY
												p.ejercicio asc;");
				
		return $res;
	}
	
	public function nuevoParametros ($conexion,$ejercicio, $entidad, $subprograma, $renglonAux,
			$fuente, $organismo, $correlativo, $obra, $operacionBid, $proyectoBid, $iva, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_programacion_presupuestaria.parametros(
										            ejercicio, entidad, subprograma, renglon_auxiliar,
													fuente, organismo, correlativo, obra, 
													codigo_operacion_bid, codigo_proyecto_bid, iva
										            estado, fecha_creacion, autor)
										    VALUES (
													'$ejercicio', '$entidad', '$subprograma', '$renglonAux',
													'$fuente', '$organismo', '$correlativo', '$obra', 
													'$operacionBid', '$proyectoBid', $iva,
										            'activo', now(), '$identificador'			
													);");
		return $res;
	}
	
	public function abrirParametros ($conexion,$ejercicio){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.parametros
											WHERE
												ejercicio=$ejercicio;");
		
		return $res;
	}
	
	public function modificarParametros ($conexion,$ejercicio, $entidad, $subprograma, $renglonAux,
			$fuente, $organismo, $correlativo, $obra, $operacionBid, $proyectoBid, $iva, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.parametros
											SET 
												entidad='$entidad', 
												subprograma='$subprograma', 
												renglon_auxiliar='$renglonAux', 
												fuente='$fuente', 
												organismo='$organismo', 
												correlativo='$correlativo', 
												obra='$obra', 
												codigo_operacion_bid='$operacionBid', 
												codigo_proyecto_bid='$proyectoBid',
												iva=$iva, 											       
												autor='$identificador', 
												fecha_modificacion=now()
											WHERE 
												ejercicio='$ejercicio'
											;");
		
				return $res;
	}
	
	public function eliminarParametros($conexion, $ejercicio, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.parametros
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												ejercicio in ($ejercicio);");
	
		return $res;
	}
	public function listarProgramas ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.programas p
											WHERE
												p.estado = 'activo'
											ORDER BY
												p.id_programa asc;");
	
		return $res;
	}
	
	public function nuevoPrograma ($conexion,$nombrePrograma, $codigoPrograma, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.programas(
									            	nombre, codigo, estado, 
													fecha_creacion, autor)
									    			VALUES ('$nombrePrograma', '$codigoPrograma', 'activo',
													now(), '$identificador'
											)
											RETURNING id_programa;");
				
		return $res;
	}
	
	public function abrirPrograma ($conexion,$idPrograma){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.programas
											WHERE
												id_programa=$idPrograma;");
	
		return $res;
	}
		
	public function modificarPrograma($conexion, $idPrograma, $nombrePrograma, $codigoPrograma, 
			$identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.programas
							 				SET 
												nombre='$nombrePrograma', 
												codigo='$codigoPrograma', 
												autor='$identificador', 
												fecha_modificacion=now()
							 				WHERE 
												id_programa=$idPrograma
											;");
	
		return $res;
	}
	
	public function eliminarPrograma($conexion, $idPrograma, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.programas
							 				SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_programa in ($idPrograma);");
	
		return $res;
	}
	
	public function buscarCodigoProyecto ($conexion, $codigoProyecto, $idPrograma){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											from
												g_programacion_presupuestaria.codigo_proyecto cp
											WHERE
												cp.id_programa = $idPrograma and
												cp.codigo_proyecto = '$codigoProyecto'
											order by
												cp.codigo_proyecto asc;");
	
		return $res;
	}
	
	public function nuevoCodigoProyecto ($conexion, $nombreProyecto, $codigoProyecto, $idPrograma, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.codigo_proyecto(
							            			codigo_proyecto, nombre, id_programa, estado, 
													fecha_creacion, autor)
							    				VALUES ('$codigoProyecto', '$nombreProyecto', $idPrograma, 
														'activo', now(), '$identificador')
											RETURNING id_codigo_proyecto;");
	
		return $res;
	}
	
	public function imprimirLineaCodigoProyecto($idCodigoProyecto, $nombreProyecto, $codigoProyecto, $idPrograma, $ruta){
		return '<tr id="R' . $idCodigoProyecto . '">' .
				'<td width="75%">' .
				$nombreProyecto .
				'</td>' .
				'<td width="25%">' .
				$codigoProyecto .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirCodigoProyecto" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPrograma" value="' . $idPrograma . '" >' .
				'<input type="hidden" name="idCodigoProyecto" value="' . $idCodigoProyecto . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarCodigoProyecto">' .
				'<input type="hidden" name="idCodigoProyecto" value="' . $idCodigoProyecto . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarCodigoProyecto ($conexion, $idPrograma){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.codigo_proyecto cp
											WHERE
												cp.id_programa = $idPrograma and
												cp.estado = 'activo'
											ORDER BY
												cp.codigo_proyecto asc;");
	
		return $res;
	}
	
	public function abrirCodigoProyecto ($conexion,$idCodigoProyecto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.codigo_proyecto
											WHERE
												id_codigo_proyecto=$idCodigoProyecto;");
	
		return $res;
	}
	
	public function modificarCodigoProyecto ($conexion, $idCodigoProyecto, $nombreProyecto, $codigoProyecto, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_programacion_presupuestaria.codigo_proyecto
												SET
													nombre='$nombreProyecto',
													codigo_proyecto='$codigoProyecto',
													autor='$identificador',
													fecha_modificacion=now()
												WHERE
													id_codigo_proyecto=$idCodigoProyecto
												;");
	
		return $res;
	}
	
	public function eliminarCodigoProyecto($conexion, $idCodigoProyecto, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.codigo_proyecto
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_codigo_proyecto in ($idCodigoProyecto);");
	
		return $res;
	}
	
	public function eliminarCodigoProyectoXPrograma($conexion, $idPrograma, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.codigo_proyecto
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_programa in ($idPrograma);");
	
				return $res;
	}
	
	public function listarCodigoActividad ($conexion, $idCodigoProyecto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.codigo_actividad ca
											WHERE
												ca.id_codigo_proyecto = $idCodigoProyecto and
												ca.estado = 'activo'
											ORDER BY
												ca.codigo_actividad asc;");
	
		return $res;
	}
	
	public function listarCodigoActividadXPrograma ($conexion, $idPrograma){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												ca.id_codigo_actividad
											FROM
												g_programacion_presupuestaria.programas p,
												g_programacion_presupuestaria.codigo_proyecto cp,
												g_programacion_presupuestaria.codigo_actividad ca
											WHERE
												p.id_programa = cp.id_programa and
												cp.id_codigo_proyecto = ca.id_codigo_proyecto and
												p.id_programa in ($idPrograma) and
												ca.estado = 'activo'
											ORDER BY
												ca.codigo_actividad asc;");
	
		return $res;
	}
	
	public function buscarCodigoActividad ($conexion, $codigoActividad, $idCodigoProyecto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.codigo_actividad ca
											WHERE
												ca.id_codigo_proyecto = $idCodigoProyecto and
												ca.codigo_actividad = '$codigoActividad'
											ORDER BY
												ca.codigo_actividad asc;");
	
		return $res;
	}
	
	public function nuevoCodigoActividad ($conexion, $nombreActividad, $codigoActividad, $idCodigoProyecto, 
											$idProvincia, $nombreProvincia, $codigoGeograficoProvincia, 
											$idCanton, $nombreCanton, $codigoGeograficoCanton, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programacion_presupuestaria.codigo_actividad(
												nombre, codigo_actividad, id_codigo_proyecto, 
												id_provincia, provincia, geografico_provincia,
												id_canton, canton, geografico_canton,
												estado, fecha_creacion, autor)
											VALUES ('$nombreActividad', '$codigoActividad', $idCodigoProyecto,
													$idProvincia, '$nombreProvincia', '$codigoGeograficoProvincia', 
													$idCanton, '$nombreCanton', '$codigoGeograficoCanton',
													'activo', now(), '$identificador')
											RETURNING id_codigo_actividad;");
	
		return $res;
	}
	
	public function imprimirLineaCodigoActividad($idCodigoActividad, $nombreActividad, $codigoActividad, $idCodigoProyecto, $idPrograma,
												$nombreProvincia, $codigoGeograficoProvincia, 
												$nombreCanton, $codigoGeograficoCanton, $ruta){
		return '<tr id="R' . $idCodigoActividad . '">' .
				'<td width="25%">' .
				$nombreActividad .
				'</td>' .
				'<td width="10%">' .
				$codigoActividad .
				'</td>' .
				'<td width="23%">' .
				$nombreProvincia .
				'</td>' .
				'<td width="10%">' .
				$codigoGeograficoProvincia .
				'</td>' .
				'<td width="22%">' .
				$nombreCanton .
				'</td>' .
				'<td width="10%">' .
				$codigoGeograficoCanton .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirCodigoActividad" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPrograma" value="' . $idPrograma . '" >' .
				'<input type="hidden" name="idCodigoProyecto" value="' . $idCodigoProyecto . '" >' .
				'<input type="hidden" name="idCodigoActividad" value="' . $idCodigoActividad . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarCodigoActividad">' .
				'<input type="hidden" name="idCodigoActividad" value="' . $idCodigoActividad . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function abrirCodigoActividad ($conexion,$idCodigoActividad){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.codigo_actividad
											WHERE
												id_codigo_actividad=$idCodigoActividad;");
	
		return $res;
	}
	
	public function modificarCodigoActividad ($conexion, $idCodigoActividad, $nombreActividad, $codigoActividad, 
												$idProvincia, $nombreProvincia, $codigoGeograficoProvincia, 
												$idCanton, $nombreCanton, $codigoGeograficoCanton,$identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.codigo_actividad
											SET
												nombre='$nombreActividad',
												codigo_actividad='$codigoActividad',
												id_provincia = $idProvincia,
												provincia = '$nombreProvincia',
												geografico_provincia = '$codigoGeograficoProvincia',
												id_canton = $idCanton,
												canton = '$nombreCanton',
												geografico_canton = '$codigoGeograficoCanton',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_codigo_actividad=$idCodigoActividad
											;");
	
				return $res;
	}
	
	public function eliminarCodigoActividad($conexion, $idCodigoActividad, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.codigo_actividad
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_codigo_actividad in ($idCodigoActividad);");
	
		return $res;
	}
	
	public function listarObjetivoEstrategico ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_estrategico o 
											WHERE
												o.estado = 'activo'
											ORDER BY
												o.nombre asc;");
	
		return $res;
	}
	
	public function nuevoObjetivoEstrategico ($conexion,$nombreObjetivoEstrategico, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programacion_presupuestaria.objetivo_estrategico(
												nombre, estado,
												fecha_creacion, autor)
											VALUES ('$nombreObjetivoEstrategico', 'activo',
													now(), '$identificador'
											)
											RETURNING id_objetivo_estrategico;");
	
		return $res;
	}
	
	public function abrirObjetivoEstrategico ($conexion,$idObjetivoEstrategico){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_estrategico 
											WHERE
												id_objetivo_estrategico=$idObjetivoEstrategico;");
	
		return $res;
	}
	
	public function modificarObjetivoEstrategico($conexion, $idObjetivoEstrategico, $nombreObjetivoEstrategico,	$identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.objetivo_estrategico
											SET
												nombre='$nombreObjetivoEstrategico',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_objetivo_estrategico=$idObjetivoEstrategico
											;");
	
		return $res;
	}
	
	public function eliminarObjetivoEstrategico($conexion, $idObjetivoEstrategico, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.objetivo_estrategico
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_objetivo_estrategico in ($idObjetivoEstrategico);");
	
				return $res;
	}
	
	public function listarObjetivoEspecifico ($conexion, $idObjetivoEstrategico, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_especifico oes
											WHERE
												oes.id_objetivo_estrategico = $idObjetivoEstrategico and
												oes.estado = 'activo' and
												oes.anio = $anio
											ORDER BY
												oes.nombre asc;");
	
		return $res;
	}
	
	public function listarObjetivoEspecificoXArea ($conexion, $idObjetivoEstrategico, $idArea, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_especifico oes
											WHERE
												oes.id_objetivo_estrategico = $idObjetivoEstrategico and
												oes.estado = 'activo' and
												oes.anio = $anio and
												oes.id_area = '$idArea'
											ORDER BY
												oes.nombre asc;");
	
		return $res;
	}
	
	public function imprimirLineaObjetivoEspecifico($idObjetivoEspecifico, $nombreObjetivoEspecifico, $nombreArea, $idObjetivoEstrategico, $anio, $ruta){
		return '<tr id="R' . $idObjetivoEspecifico . '">' .
				'<td width="50%">' .
				$nombreObjetivoEspecifico .
				'</td>' .
				'<td width="25%">' .
				$nombreArea .
				'</td>' .
				'<td width="25%">' .
				$anio .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirObjetivoEspecifico" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idObjetivoEstrategico" value="' . $idObjetivoEstrategico . '" >' .
				'<input type="hidden" name="idObjetivoEspecifico" value="' . $idObjetivoEspecifico . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarObjetivoEspecifico">' .
				'<input type="hidden" name="idObjetivoEspecifico" value="' . $idObjetivoEspecifico . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarObjetivoEspecifico ($conexion, $nombreObjetivoEspecifico, $idArea, $idObjetivoEstrategico){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_especifico oes
											WHERE
												oes.nombre = '$nombreObjetivoEspecifico' and
												oes.id_objetivo_estrategico = '$idObjetivoEstrategico' and
												oes.id_area = '$idArea'
											ORDER BY
												oes.nombre asc;");
	
		return $res;
	}
	
	public function nuevoObjetivoEspecifico ($conexion, $nombreObjetivoEspecifico, $anio, $idObjetivoEstrategico,
								$idArea, $nombreArea, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.objetivo_especifico(
										            nombre, anio, id_objetivo_estrategico, 
										            id_area, nombre_area, estado, fecha_creacion, autor)
										    VALUES ('$nombreObjetivoEspecifico', $anio, $idObjetivoEstrategico, 
													'$idArea', '$nombreArea', 'activo', now(), '$identificador')
											RETURNING id_objetivo_especifico;");
	
		return $res;
	}
	
	public function abrirObjetivoEspecifico ($conexion,$idObjetivoEspecifico){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_especifico
											WHERE
												id_objetivo_especifico=$idObjetivoEspecifico;");
	
		return $res;
	}
	
	public function modificarObjetivoEspecifico ($conexion, $idObjetivoEspecifico, $idArea, $nombreArea, $nombreObjetivoEspecifico, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.objetivo_especifico
											SET
												id_area='$idArea',
												nombre_area='$nombreArea',
												nombre='$nombreObjetivoEspecifico',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_objetivo_especifico=$idObjetivoEspecifico
											;");
	
		return $res;
	}
	
	public function eliminarObjetivoEspecifico($conexion, $idObjetivoEspecifico, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.objetivo_especifico
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_objetivo_especifico in ($idObjetivoEspecifico);");
	
		return $res;
	}
	
	public function listarObjetivoOperativo ($conexion, $idObjetivoEspecifico, $anio){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_operativo oo
											WHERE
												oo.id_objetivo_especifico = $idObjetivoEspecifico and
												oo.estado = 'activo' and
												oo.anio = $anio
											ORDER BY
												oo.nombre asc;");
	
		return $res;
	}
	
	public function listarObjetivoOperativoXArea ($conexion, $idObjetivoEspecifico, $idArea, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_operativo oo
											WHERE
												oo.id_objetivo_especifico = $idObjetivoEspecifico and
												oo.estado = 'activo' and
												oo.anio = $anio and
												oo.id_area = '$idArea'
											ORDER BY
												oo.nombre asc;");
	
				return $res;
	}
	
	public function imprimirLineaObjetivoOperativo($idObjetivoOperativo, $nombreObjetivoOperativo, $nombreArea, $idObjetivoEspecifico, $idObjetivoEstrategico, $anio, $ruta){
		return '<tr id="R' . $idObjetivoOperativo . '">' .
				'<td width="50%">' .
				$nombreObjetivoOperativo .
				'</td>' .
				'<td width="25%">' .
				$nombreArea .
				'</td>' .
				'<td width="25%">' .
				$anio .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirObjetivoOperativo" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idObjetivoEstrategico" value="' . $idObjetivoEstrategico . '" >' .
				'<input type="hidden" name="idObjetivoEspecifico" value="' . $idObjetivoEspecifico . '" >' .
				'<input type="hidden" name="idObjetivoOperativo" value="' . $idObjetivoOperativo . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarObjetivoOperativo">' .
				'<input type="hidden" name="idObjetivoOperativo" value="' . $idObjetivoOperativo . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarObjetivoOperativo ($conexion, $nombreObjetivoOperativo, $idArea, $idObjetivoEspecifico){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_operativo oo
											WHERE
												oo.nombre = '$nombreObjetivoOperativo' and
												oo.id_objetivo_especifico = '$idObjetivoEspecifico' and
												oo.id_area = '$idArea'
											ORDER BY
												oo.nombre asc;");
	
		return $res;
	}
	
	public function nuevoObjetivoOperativo ($conexion, $nombreObjetivoOperativo, $anio, $idObjetivoEspecifico,
			$idArea, $nombreArea, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programacion_presupuestaria.objetivo_operativo(
													nombre, anio, id_objetivo_especifico,
													id_area, nombre_area, estado, fecha_creacion, autor)
											VALUES ('$nombreObjetivoOperativo', $anio, $idObjetivoEspecifico,
													'$idArea', '$nombreArea', 'activo', now(), '$identificador')
											RETURNING id_objetivo_operativo;");
	
		return $res;
	}
	
	public function abrirObjetivoOperativo ($conexion,$idObjetivoOperativo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_operativo
											WHERE
												id_objetivo_operativo=$idObjetivoOperativo;");
	
		return $res;
	}
	
	public function modificarObjetivoOperativo ($conexion, $idObjetivoOperativo, $nombreObjetivoOperativo, $idArea, $nombreArea, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.objetivo_operativo
											SET
												id_area='$idArea',
												nombre_area='$nombreArea',
												nombre='$nombreObjetivoOperativo',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_objetivo_operativo=$idObjetivoOperativo
											;");
	
		return $res;
	}
	
	public function eliminarObjetivoOperativo($conexion, $idObjetivoOperativo, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.objetivo_operativo
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_objetivo_operativo in ($idObjetivoOperativo);");
	
		return $res;
	}
	
	public function listarUnidadesMedidaSercop ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.unidades_medidas ums
											WHERE
												ums.clasificacion = 'sercop' and
												ums.estado = 'activo'
											ORDER BY
												ums.nombre asc;");
	
		return $res;
	}
	
	public function nuevaUnidadMedida ($conexion,$nombreUnidadMedida, $codigoUnidadMedida){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.unidades_medidas(
										            nombre, codigo, estado, 
													clasificacion)
												VALUES (
													'$nombreUnidadMedida', '$codigoUnidadMedida', 'activo',
													'sercop'
												);");

		return $res;
	}
	
	public function abrirUnidadMedida ($conexion,$idUnidadMedida){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.unidades_medidas
											WHERE
												id_unidad_medida=$idUnidadMedida;");
	
		return $res;
	}
	
	public function modificarUnidadMedida ($conexion, $idUnidadMedida, $nombreUnidadMedida, $codigoUnidadMedida){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.unidades_medidas
								   			SET 
												nombre='$nombreUnidadMedida', 
												codigo='$codigoUnidadMedida'
								 			WHERE 
												id_unidad_medida=$idUnidadMedida
											;");
	
		return $res;
	}
	
	public function eliminarUnidadMedida($conexion, $idUnidadMedida, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.unidades_medidas
											SET
												estado='inactivo'
											WHERE
												id_unidad_medida in ($idUnidadMedida);");
	
		return $res;
	}
	
	public function listarCPC ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.cpc c
											WHERE
												c.estado = 'activo'
											ORDER BY
												c.nombre asc;");
	
		return $res;
	}
	
	public function nuevoCPC ($conexion,$nombreCPC, $codigoCPC, $nivelCPC, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programacion_presupuestaria.cpc(
												nombre, codigo, nivel, estado,
												fecha_creacion, autor)
												VALUES (
												'$nombreCPC', '$codigoCPC', $nivelCPC, 'activo',
												now(), '$identificador'
											);");
	
		return $res;
	}
	
	public function abrirCPC ($conexion,$idCPC){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.cpc
											WHERE
												id_cpc=$idCPC;");
	
		return $res;
	}
	
	public function modificarCPC ($conexion, $idCPC, $nombreCPC, $codigoCPC, $nivelCPC,
									$identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.cpc
											SET
												nombre='$nombreCPC',
												codigo='$codigoCPC',
												nivel='$nivelCPC',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_cpc=$idCPC
											;");
	
		return $res;
	}
	
	public function eliminarCPC($conexion, $idCPC, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.cpc
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_cpc in ($idCPC);");
	
		return $res;
	}
	
	public function listarRenglon ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*	
											FROM
												g_programacion_presupuestaria.renglon r
											WHERE
												r.estado = 'activo'
											ORDER BY
												r.codigo asc;");
	
		return $res;
	}
	
	public function nuevoRenglon ($conexion,$nombreRenglon, $codigoRenglon, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programacion_presupuestaria.renglon(
												nombre, codigo, estado,
												fecha_creacion, autor)
											VALUES (
												'$nombreRenglon', '$codigoRenglon', 'activo',
												now(), '$identificador'
											);");
	
		return $res;
	}
	
	public function abrirRenglon ($conexion,$idRenglon){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.renglon
											WHERE
												id_renglon=$idRenglon;");
	
		return $res;
	}
	
	public function modificarRenglon ($conexion, $idRenglon, $nombreRenglon, $codigoRenglon,
			$identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.renglon
											SET
												nombre='$nombreRenglon',
												codigo='$codigoRenglon',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_renglon=$idRenglon
											;");
	
		return $res;
	}
	
	public function eliminarRenglon($conexion, $idRenglon, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.renglon
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_renglon in ($idRenglon);");
	
		return $res;
	}
	
	public function listarProcesoProyecto ($conexion, $idObjetivoOperativo, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto pp
											WHERE
												pp.id_objetivo_operativo = $idObjetivoOperativo and
												pp.estado = 'activo' and
												pp.anio = $anio
											ORDER BY
												pp.nombre asc;");
	
		return $res;
	}
	
	public function listarProcesoProyectoXGestionYTipo ($conexion, $idObjetivoOperativo, $idArea, $tipo, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto pp
											WHERE
												pp.id_objetivo_operativo = $idObjetivoOperativo and
												pp.estado = 'activo' and
												pp.id_area = '$idArea' and
												pp.tipo = '$tipo' and												
												pp.anio = $anio
											ORDER BY
												pp.nombre asc;");
	
		return $res;
	}
	
	public function imprimirLineaProcesoProyecto($idProcesoProyecto, $nombreProcesoProyecto, $tipo, $financiamiento, $codigoPrograma, $nombreArea, $idObjetivoEstrategico, $idObjetivoEspecifico, $idObjetivoOperativo, $areaObjetivoOperativo, $anio, $ruta){
		return '<tr id="R' . $idProcesoProyecto . '">' .
				'<td width="25%">' .
				$nombreProcesoProyecto .
				'</td>' .
				'<td width="15%">' .
				$tipo .
				'</td>' .
				'<td width="15%">' .
				$financiamiento .
				'</td>' .
				'<td width="5%">' .
				$codigoPrograma .
				'</td>' .
				'<td width="30%">' .
				$nombreArea .
				'</td>' .
				'<td width="10%">' .
				$anio .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirProcesoProyecto" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idObjetivoEstrategico" value="' . $idObjetivoEstrategico . '" >' .
				'<input type="hidden" name="idObjetivoEspecifico" value="' . $idObjetivoEspecifico . '" >' .
				'<input type="hidden" name="idObjetivoOperativo" value="' . $idObjetivoOperativo . '" >' .
				'<input type="hidden" name="idProcesoProyecto" value="' . $idProcesoProyecto . '" >' .
				'<input type="hidden" name="areaOO" value="' . $areaObjetivoOperativo . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarProcesoProyecto">' .
				'<input type="hidden" name="idProcesoProyecto" value="' . $idProcesoProyecto . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarProcesoProyecto ($conexion, $nombreProcesoProyecto, $idArea, $idObjetivoOperativo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto pp
											WHERE
												pp.nombre = '$nombreProcesoProyecto' and
												pp.id_objetivo_operativo = $idObjetivoOperativo and
												pp.id_area = '$idArea'
											ORDER BY
												pp.nombre asc;");
	
		return $res;
	}
	
	public function nuevoProcesoProyecto ($conexion, $nombreProcesoProyecto, $productoFinal, $anio, $tipo, $financiamiento, 
											$idObjetivoOperativo, $idArea, $nombreArea, $idPrograma, $codigoPrograma,
											$identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.proceso_proyecto(
										            nombre, producto_final, anio, tipo, 
													financiamiento, id_objetivo_operativo, id_area, 
										            nombre_area, id_programa, codigo_programa, 
										            estado, fecha_creacion, autor)
										    VALUES ('$nombreProcesoProyecto', '$productoFinal', $anio, '$tipo', 
													'$financiamiento', $idObjetivoOperativo, '$idArea',  
										            '$nombreArea', $idPrograma, '$codigoPrograma', 
										            'activo', now(), '$identificador')
											RETURNING id_proceso_proyecto;");
	
				return $res;
	}
	
	public function abrirProcesoProyecto ($conexion,$idProcesoProyecto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto
											WHERE
												id_proceso_proyecto=$idProcesoProyecto;");
	
		return $res;
	}
	
	public function modificarProcesoProyecto ($conexion, $idProcesoProyecto, $nombreProcesoProyecto, $productoFinal, $tipo, 
											$financiamiento, $idObjetivoOperativo, $idArea, $nombreArea, 
											$idPrograma, $codigoPrograma, $identificador){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.proceso_proyecto
											SET 
												nombre='$nombreProcesoProyecto', 
												producto_final='$productoFinal', 
												tipo='$tipo', 											       
												financiamiento='$financiamiento', 
												id_area='$idArea', 
												nombre_area='$nombreArea', 
												id_programa=$idPrograma, 
												codigo_programa='$codigoPrograma', 											       
												autor='$identificador', 
												fecha_modificacion=now()
											WHERE 
												id_proceso_proyecto=$idProcesoProyecto
										;");
	
				return $res;
	}
	
	public function eliminarProcesoProyecto($conexion, $idProcesoProyecto, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.proceso_proyecto
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_proceso_proyecto in ($idProcesoProyecto);");
	
		return $res;
	}
	
	public function buscarComponente ($conexion, $nombreComponente, $idProcesoProyecto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.componente c
											WHERE
												c.nombre = '$nombreComponente' and
												c.id_proceso_proyecto = $idProcesoProyecto
											ORDER BY
												c.nombre asc;");
	
		return $res;
	}
	
	public function nuevoComponente ($conexion, $nombreComponente, $idCodigoProyecto, $codigoCodigoProyecto, 
										$anio, $idProcesoProyecto, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_programacion_presupuestaria.componente(
													nombre, anio, id_codigo_proyecto,  
            										codigo_proyecto, id_proceso_proyecto, 
													estado, fecha_creacion, autor)
											VALUES ('$nombreComponente', $anio, '$idCodigoProyecto', 
													'$codigoCodigoProyecto', '$idProcesoProyecto',
													'activo', now(), '$identificador')
											RETURNING id_componente;");
	
		return $res;
	}
	
	public function imprimirLineaComponente($idComponente, $nombreComponente, $codigoProyecto, 
											$idObjetivoEstrategico, $idObjetivoEspecifico, $idObjetivoOperativo, 
											$idProcesoProyecto, $idPrograma, $idCodigoProyecto, $anio, $ruta){
		return '<tr id="R' . $idComponente . '">' .
				'<td width="50%">' .
				$nombreComponente .
				'</td>' .
				'<td width="15%">' .
				$codigoProyecto .
				'</td>' .
				'<td width="15%">' .
				$anio .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirComponente" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idObjetivoEstrategico" value="' . $idObjetivoEstrategico . '" >' .
				'<input type="hidden" name="idObjetivoEspecifico" value="' . $idObjetivoEspecifico . '" >' .
				'<input type="hidden" name="idObjetivoOperativo" value="' . $idObjetivoOperativo . '" >' .
				'<input type="hidden" name="idProcesoProyecto" value="' . $idProcesoProyecto . '" >' .
				'<input type="hidden" name="idComponente" value="' . $idComponente . '" >' .
				'<input type="hidden" name="idPrograma" value="' . $idPrograma . '" >' .
				'<input type="hidden" name="idCodigoProyecto" value="' . $idCodigoProyecto . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarComponente">' .
				'<input type="hidden" name="idComponente" value="' . $idComponente . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarComponente ($conexion, $idProcesoProyecto, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.componente c
											WHERE
												c.id_proceso_proyecto = $idProcesoProyecto and
												c.estado = 'activo' and
												c.anio = $anio
											ORDER BY
												c.nombre asc;");
	
				return $res;
	}
	
	public function listarComponenteXProcesoProyecto ($conexion, $idProcesoProyecto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.componente c
											WHERE
												c.id_proceso_proyecto in ($idProcesoProyecto)
											ORDER BY
												c.nombre asc;");
	
		return $res;
	}
	
	public function abrirComponente ($conexion,$idComponente){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.componente
											WHERE
												id_componente=$idComponente;");
								
		return $res;
	}
	
	public function modificarComponente ($conexion, $idComponente, $nombreComponente, $idCodigoProyecto, 
			$codigoCodigoProyecto, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.componente
											SET 
												nombre='$nombreComponente', 
												id_codigo_proyecto=$idCodigoProyecto, 
												codigo_proyecto='$codigoCodigoProyecto', 
											    autor='$identificador', 
												fecha_modificacion=now()
											WHERE 
												id_componente=$idComponente;
											;");
	
		return $res;
	}
	
	public function eliminarComponente($conexion, $idComponente, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.componente
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_componente in ($idComponente);");
	
		return $res;
	}
	
	public function eliminarComponenteXProcesoProyecto($conexion, $idProcesoProyecto, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.componente
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_proceso_proyecto in ($idProcesoProyecto);");
	
		return $res;
	}
	
	public function buscarActividad ($conexion, $nombreActividad, $idComponente){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.actividad a
											WHERE
												a.nombre = '$nombreActividad' and
												a.id_componente = $idComponente
											ORDER BY
												a.nombre asc;");
	
		return $res;
	}
	
	public function nuevaActividad ($conexion, $nombreActividad, $idCodigoActividad, $codigoCodigoActividad,
			$anio, $idComponente, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.actividad(
										            nombre, anio, 
													id_codigo_actividad, codigo_actividad, 
										            id_componente, estado, fecha_creacion, autor)
											VALUES ('$nombreActividad', $anio, 
													$idCodigoActividad, '$codigoCodigoActividad', 
											        $idComponente, 'activo', now(), '$identificador')
											RETURNING id_actividad;");	
	
				return $res;
	}
	
	public function imprimirLineaActividad($idActividad, $nombreActividad, $codigoCodigoActividad,
			$idObjetivoEstrategico, $idObjetivoEspecifico, $idObjetivoOperativo,
			$idProcesoProyecto, $idComponente, $idPrograma, $idCodigoProyecto, $idCodigoActividad, $anio, $ruta){
		return '<tr id="R' . $idActividad . '">' .
				'<td width="50%">' .
				$nombreActividad .
				'</td>' .
				'<td width="55%">' .
				$codigoCodigoActividad .
				'</td>' .
				'<td width="25%">' .
				$anio .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirActividad" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idObjetivoEstrategico" value="' . $idObjetivoEstrategico . '" >' .
				'<input type="hidden" name="idObjetivoEspecifico" value="' . $idObjetivoEspecifico . '" >' .
				'<input type="hidden" name="idObjetivoOperativo" value="' . $idObjetivoOperativo . '" >' .
				'<input type="hidden" name="idProcesoProyecto" value="' . $idProcesoProyecto . '" >' .
				'<input type="hidden" name="idComponente" value="' . $idComponente . '" >' .
				'<input type="hidden" name="idActividad" value="' . $idActividad . '" >' .
				'<input type="hidden" name="idPrograma" value="' . $idPrograma . '" >' .
				'<input type="hidden" name="idCodigoProyecto" value="' . $idCodigoProyecto . '" >' .
				'<input type="hidden" name="idCodigoActividad" value="' . $idCodigoActividad . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarActividad">' .
				'<input type="hidden" name="idActividad" value="' . $idActividad . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarActividad ($conexion, $idComponente, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.actividad a
											WHERE
												a.id_componente = $idComponente and
												a.estado = 'activo' and
												a.anio = $anio
											ORDER BY
												a.nombre asc;");
	
		return $res;
	}
	
	public function listarActividadXComponente ($conexion, $idComponente){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.actividad a
											WHERE
												a.id_componente = $idComponente
											ORDER BY
												a.nombre asc;");
	
		return $res;
	}
	
	public function abrirActividad ($conexion,$idActividad){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.actividad
											WHERE
												id_actividad=$idActividad;");
	
		return $res;
	}
	
	public function modificarActividad ($conexion, $idActividad, $nombreActividad, $idCodigoActividad,
			$codigoCodigoActividad, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.actividad
											SET
												nombre='$nombreActividad',
												id_codigo_actividad=$idCodigoActividad,
												codigo_actividad='$codigoCodigoActividad',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_actividad=$idActividad;");
	
		return $res;
	}
	
	public function eliminarActividad($conexion, $idCodigoActividad, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.actividad
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_actividad in ($idCodigoActividad);");
	
		return $res;
	}
	
	public function eliminarActividadXComponente($conexion, $idComponente, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.actividad
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_componente in ($idComponente);");
	
		return $res;
	}
	
	public function listarTipoCompra ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.tipo_compra tc
											WHERE
												tc.estado = 'activo'
											ORDER BY
												tc.id_tipo_compra asc;");
	
		return $res;
	}
	
	public function nuevoTipoCompra ($conexion,$nombreTipoCompra, $codigoTipoCompra, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.tipo_compra(
										            nombre, estado, 
													fecha_creacion, autor)
									    	VALUES ('$nombreTipoCompra', 'activo',
													now(), '$identificador')
											RETURNING id_tipo_compra;");
	
		return $res;
	}
	
	public function abrirTipoCompra ($conexion,$idTipoCompra){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.tipo_compra
											WHERE
												id_tipo_compra=$idTipoCompra;");
	
		return $res;
	}
	
	public function modificarTipoCompra($conexion, $idTipoCompra, $nombreTipoCompra, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.tipo_compra
											SET
												nombre='$nombreTipoCompra',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_tipo_compra=$idTipoCompra
											;");
	
		return $res;
	}
	
	public function eliminarTipoCompra($conexion, $idTipoCompra, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.tipo_compra
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_tipo_compra in ($idTipoCompra);");
	
		return $res;
	}

	public function buscarProcedimientoSugerido ($conexion, $nombreProcedimientoSugerido, $idTipoCompra){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.procedimiento_sugerido ps
											WHERE
												ps.nombre = '$nombreProcedimientoSugerido' and
												ps.id_tipo_compra = '$idTipoCompra'
											ORDER BY
												ps.nombre asc;");
	
		return $res;
	}
	
	public function imprimirLineaProcedimientoSugerido ($idProcedimientoSugerido, $nombreProcedimientoSugerido, $idTipoCompra, $ruta){
		return '<tr id="R' . $idProcedimientoSugerido . '">' .
				'<td width="100%">' .
				$nombreProcedimientoSugerido .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirProcedimientoSugerido" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idTipoCompra" value="' . $idTipoCompra . '" >' .
				'<input type="hidden" name="idProcedimientoSugerido" value="' . $idProcedimientoSugerido . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarProcedimientoSugerido">' .
				'<input type="hidden" name="idProcedimientoSugerido" value="' . $idProcedimientoSugerido . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function nuevoProcedimientoSugerido ($conexion, $nombreProcedimientoSugerido, $idTipoCompra, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.procedimiento_sugerido(
										            nombre, id_tipo_compra, estado, 
										            fecha_creacion, autor)
									    	VALUES ('$nombreProcedimientoSugerido', 
													'$idTipoCompra', 'activo', now(), '$identificador')
											RETURNING id_procedimiento_sugerido;");
	
		return $res;
	}
	
	public function listarProcedimientoSugerido ($conexion, $idTipoCompra){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.procedimiento_sugerido ps
											WHERE
												ps.id_tipo_compra = $idTipoCompra and
												ps.estado = 'activo'
											ORDER BY
												ps.nombre asc;");
	
		return $res;
	}
	
	public function abrirProcedimientoSugerido ($conexion,$idProcedimientoSugerido){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.procedimiento_sugerido
											WHERE
												id_procedimiento_sugerido = $idProcedimientoSugerido;");
	
		return $res;
	}
	
	public function modificarProcedimientoSugerido($conexion, $idProcedimientoSugerido, $nombreProcedimientoSugerido, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.procedimiento_sugerido
											SET
												nombre='$nombreProcedimientoSugerido',
												autor='$identificador',
												fecha_modificacion=now()
											WHERE
												id_procedimiento_sugerido=$idProcedimientoSugerido
											;");
	
		return $res;
	}
	
	public function eliminarProcedimientoSugerido($conexion, $idProcedimientoSugerido, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.procedimiento_sugerido
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_procedimiento_sugerido=$idProcedimientoSugerido;");
	
		return $res;
	}
	
	public function eliminarProcedimientoSugeridoXTipoCompra($conexion, $idTipoCompra, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.procedimiento_sugerido
											SET
												estado='inactivo',
												usuario_eliminacion='$identificador',
												fecha_eliminacion=now()
											WHERE
												id_tipo_compra in ($idTipoCompra);");
	
		return $res;
	}
	
	public function listarUnidadEjeDes ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.unidad_ejedes ued
											WHERE
												ued.estado = 'activo'
											ORDER BY
												ued.nombre asc;");
	
		return $res;
	}
	
	public function listarUnidadEjeDesXTipo ($conexion, $tipo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.unidad_ejedes ued
											WHERE
												ued.estado = 'activo' and
												ued.tipo = '$tipo'
											ORDER BY
												ued.nombre asc;");
	
		return $res;
	}
	
	public function nuevaUnidadEjeDes ($conexion,$nombreUnidadEjeDes, $codigoUnidadEjeDes, $tipo, $idLocalizacion, 
										$codigoGeografico, $identificador){

		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.unidad_ejedes(
										            nombre, codigo, tipo, id_localizacion, 
													codigo_geografico, estado, fecha_creacion, autor)
										    VALUES(
													'$nombreUnidadEjeDes', '$codigoUnidadEjeDes', '$tipo', $idLocalizacion,
													'$codigoGeografico', 'activo', now(), '$identificador'
												);");
	
		return $res;
	}
	
	public function abrirUnidadEjeDes ($conexion,$idUnidadEjeDes){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.unidad_ejedes
											WHERE
												id_unidad_ejedes=$idUnidadEjeDes;");
	
		return $res;
	}
	
	public function modificarUnidadEjeDes ($conexion, $idUnidadEjeDes, $nombreUnidadEjeDes, $codigoUnidadEjeDes, $tipo, 
										$idLocalizacion, $codigoGeografico, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_programacion_presupuestaria.unidad_ejedes
												SET
													nombre='$nombreUnidadEjeDes',
													codigo='$codigoUnidadEjeDes',
													tipo='$tipo',
													id_localizacion=$idLocalizacion, 
													codigo_geografico='$codigoGeografico',
													autor='$identificador',
													fecha_modificacion=now()
												WHERE
													id_unidad_ejedes=$idUnidadEjeDes
												;");
		
		return $res;
	}
	
	public function eliminarUnidadEjeDes ($conexion, $idUnidadEjeDes, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.unidad_ejedes
											SET
												estado ='inactivo',
												fecha_eliminacion = now(),
												usuario_eliminacion = '$identificador'
											WHERE
												id_unidad_ejedes in ($idUnidadEjeDes);");
	
		return $res;
	}
	
	//USUARIO PROGRAMACION ANUAL
	
	public function listarProgramacionAnual ($conexion, $identificador, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.planificacion_anual pa
											WHERE
												pa.estado not in ('eliminado') and
												pa.anio = $anio and
												pa.identificador = '$identificador'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico, 
										        pa.id_objetivo_operativo, pa.id_area_unidad asc;");
									
		return $res;
	}
	
	public function listarProgramacionAnualVista ($conexion, $identificador, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.estado not in ('eliminado') and
												pa.anio = $anio and
												pa.identificador = '$identificador'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
					
		return $res;
	}
	
	public function nuevaProgramacionAnual ($conexion,$identificador, $idAreaFuncionario, $anio,
											$idObjetivoEstrategico, $idAreaN2, $idObjetivoEspecifico,
											$idAreaN4, $idObjetivoOperativo, $idGestion, $tipo, $idProcesoProyecto,
											$idComponente, $idActividad, $productoFinal, $idProvincia, $nombreProvincia,
											$cantidadUsuarios, $poblacionObjetivo, $medioVerificacion, $idResponsable,
											$nombreResponsable, $idRevisor, $idAreaRevisor){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.planificacion_anual(
										            identificador, id_area_funcionario, fecha_creacion, anio, 													
										            id_objetivo_estrategico, id_area_n2, id_objetivo_especifico, 
										            id_area_n4, id_objetivo_operativo, id_area_unidad, tipo, id_proceso_proyecto, 
										            id_componente, id_actividad, producto_final, id_provincia, provincia, 
													cantidad_usuarios, poblacion_objetivo, medio_verificacion, identificador_responsable, 
										            nombre_responsable, identificador_revisor, id_area_revisor, estado)
										    VALUES ('$identificador', '$idAreaFuncionario', now(), $anio, 
										            $idObjetivoEstrategico, '$idAreaN2', $idObjetivoEspecifico,
													'$idAreaN4', $idObjetivoOperativo, '$idGestion', '$tipo', $idProcesoProyecto,
										            $idComponente, $idActividad, '$productoFinal', $idProvincia, '$nombreProvincia',
													$cantidadUsuarios, '$poblacionObjetivo', '$medioVerificacion', '$idResponsable',
													'$nombreResponsable', '$idRevisor', '$idAreaRevisor', 'creado'
													)				
											RETURNING id_planificacion_anual;");
	
		return $res;
	}
	
	public function abrirProgramacionAnual ($conexion, $idProgramacionAnual, $identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.identificador = '$identificador' and
												pa.id_planificacion_anual = $idProgramacionAnual;");
					
		return $res;
	}
	
	public function modificarPlanificacionAnual ($conexion, $idPlanificacionAnual, $idProvincia, $nombreProvincia,
											$cantidadUsuarios, $poblacionObjetivo, $medioVerificacion, $idResponsable,
											$nombreResponsable){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_programacion_presupuestaria.planificacion_anual
											SET 
												id_provincia=$idProvincia, 
												provincia='$nombreProvincia', 
												cantidad_usuarios=$cantidadUsuarios, 
												poblacion_objetivo='$poblacionObjetivo', 											
												medio_verificacion='$medioVerificacion', 
												identificador_responsable='$idResponsable', 
												nombre_responsable='$nombreResponsable',
												fecha_modificacion = now(),
												revisado = null
											 WHERE 
												id_planificacion_anual=$idPlanificacionAnual;");
	
		return $res;
	}
	
	public function eliminarPlanificacionAnual ($conexion, $idPlanificacionAnual, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado ='eliminado',
												fecha_eliminacion = now(),
												usuario_eliminacion = '$identificador'
											WHERE
												id_planificacion_anual in ($idPlanificacionAnual);");
	
		return $res;
	}
	
	

	public function nuevoPresupuesto ($conexion, $idPlanificacionAnual, $ejercicio, $entidad, $idUnidadEjecutora, $unidadEjecutora,
										$idUnidadDesconcentrada, $unidadDesconcentrada, $programa, $subprograma,
										$codigoProyecto, $codigoActividad, $obra, $geografico, $idRenglon, $renglon, $renglonAuxiliar, 
										$fuente, $organismo, $correlativo, $idCPC, $cpc, $idTipoCompra, $tipoCompra, $idActividad,
										$nombreActividad, $actividad, $detalleGasto, $cantidadAnual, $idUnidadMedida, $unidadMedida, 
										$costo, $cuatrimestre, $tipoProducto, $catalogoElectronico, $idProcedimientoSugerido, $procedimientoSugerido, 
										$fondosBID, $operacionBID, $proyectoBID, $tipoRegimen, $tipoPresupuesto, $agregarPac, $iva, $costoIva,
										$idRevisor, $idAreaRevisor, $anio, $idAreaFuncionario, $identificador){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_programacion_presupuestaria.presupuesto_asignado(
													identificador, id_area, fecha_creacion, anio,
													id_planificacion_anual, ejercicio, entidad, id_unidad_ejecutora,
													unidad_ejecutora, id_unidad_desconcentrada, unidad_desconcentrada,
													programa, subprograma, codigo_proyecto, codigo_actividad,
													obra, geografico, id_renglon, renglon, renglon_auxiliar, fuente,
													organismo, correlativo, id_cpc, cpc, id_tipo_compra, tipo_compra,
													id_actividad, nombre_actividad, actividad, detalle_gasto, cantidad_anual,
													id_unidad_medida, unidad_medida, costo, cuatrimestre, tipo_producto,
													catalogo_electronico, id_procedimiento_sugerido, procedimiento_sugerido,
													fondos_bid, operacion_bid, proyecto_bid, tipo_regimen, tipo_presupuesto,
													identificador_revisor, id_area_revisor, estado, agregar_pac, iva, costo_iva)
											VALUES ('$identificador', '$idAreaFuncionario', now(), $anio,
													$idPlanificacionAnual, $ejercicio, '$entidad', $idUnidadEjecutora,
													'$unidadEjecutora', $idUnidadDesconcentrada, '$unidadDesconcentrada',
													'$programa', '$subprograma', '$codigoProyecto', '$codigoActividad',
													'$obra', '$geografico', $idRenglon, '$renglon', '$renglonAuxiliar', '$fuente',
													'$organismo', '$correlativo', $idCPC, '$cpc', $idTipoCompra, '$tipoCompra',
													$idActividad, '$nombreActividad', '$actividad', '$detalleGasto', $cantidadAnual,
													$idUnidadMedida, '$unidadMedida', $costo, '$cuatrimestre', '$tipoProducto',
													'$catalogoElectronico', $idProcedimientoSugerido, '$procedimientoSugerido',
													'$fondosBID', '$operacionBID', '$proyectoBID', '$tipoRegimen', '$tipoPresupuesto',
													'$idRevisor', '$idAreaRevisor', 'creado', '$agregarPac', $iva, $costoIva) 
											RETURNING id_presupuesto;");
	
		return $res;
	}
	
	
	public function imprimirLineaPresupuesto($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre, 
												$idPlanificacionAnual, $ruta, $estadoRevision, $estado){
		
		if($estadoRevision == true && $estado == 'revisado'){
			$revisado = 'activo';
		}else{
			$revisado = 'inactivo';
		}
				
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>
				<td width="10%">' .
				$cuatrimestre .
				'</td>' .
				'<td>' .
				'<div class="'.$revisado.'" >' .
				'<button type="button" class="icono"></button>' .
				'</div>' .
				'</td>' .

				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPresupuesto" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarPresupuesto" data-destino="detalleItem" data-accionEnExito="NADA">' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarPresupuestos ($conexion, $idPlanificacionAnual, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado') and
												anio = $anio
											ORDER BY
												nombre_actividad, detalle_gasto asc;");
	
		return $res;
	}
	
	public function abrirPresupuesto ($conexion,$idPresupuesto, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_presupuesto = $idPresupuesto and
												anio = $anio;");
	
		return $res;
	}
	
	public function modificarPresupuesto ($conexion, $idPresupuesto, $idUnidadEjecutora, $unidadEjecutora,
										$idUnidadDesconcentrada, $unidadDesconcentrada, $idRenglon, $renglon,  
										$idCPC, $cpc, $idTipoCompra, $tipoCompra, $idProcedimientoSugerido, $procedimientoSugerido,
										$detalleGasto, $cantidadAnual, $idUnidadMedida, $unidadMedida, 
										$costo, $cuatrimestre, $tipoProducto, $catalogoElectronico,  
										$fondosBID, $operacionBID, $proyectoBID, $tipoRegimen, $agregarPac, $iva, $costoIva){
	
		$res = $conexion->ejecutarConsulta("UPDATE
										   		g_programacion_presupuestaria.presupuesto_asignado
										   SET 
												id_unidad_ejecutora=$idUnidadEjecutora, 
										        unidad_ejecutora='$unidadEjecutora', 
										        id_unidad_desconcentrada=$idUnidadDesconcentrada, 
										        unidad_desconcentrada='$unidadDesconcentrada',       
										        id_renglon=$idRenglon, 
										        renglon='$renglon',
										        id_cpc=$idCPC, 
										        cpc='$cpc', 
										        id_tipo_compra=$idTipoCompra, 
										        tipo_compra='$tipoCompra', 
										        id_procedimiento_sugerido=$idProcedimientoSugerido, 
										        procedimiento_sugerido='$procedimientoSugerido', 
										        detalle_gasto='$detalleGasto', 
										        cantidad_anual=$cantidadAnual, 
										        id_unidad_medida=$idUnidadMedida, 
										        unidad_medida='$unidadMedida', 
										        costo=$costo, 
										        cuatrimestre='$cuatrimestre', 
										        tipo_producto='$tipoProducto', 
										        catalogo_electronico='$catalogoElectronico', 
										        fondos_bid='$fondosBID', 
										        operacion_bid='$operacionBID', 
										        proyecto_bid='$proyectoBID', 
										        tipo_regimen='$tipoRegimen',
												agregar_pac='$agregarPac',
												iva = $iva,
												costo_iva = $costoIva, 
										        fecha_modificacion = now()
										 WHERE 
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	public function eliminarPresupuesto ($conexion, $idPresupuesto, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
									   			g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado ='eliminado',
												fecha_eliminacion = now(),
												usuario_eliminacion = '$identificador'
											WHERE
												id_presupuesto=$idPresupuesto;");
	
		return $res;
	}
	
	public function eliminarPresupuestoXPlanificacionAnual ($conexion, $idPlanificacionAnual, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
										   		g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado ='eliminado',
												fecha_eliminacion = now(),
												usuario_eliminacion = '$identificador'
											WHERE
												id_planificacion_anual in ($idPlanificacionAnual);");
	
		return $res;
	}

	public function buscarPresupuesto ($conexion, $detalleGasto, $cuatrimestre, $idPlanificacionAnual){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												detalle_gasto = '$detalleGasto' and
												cuatrimestre = '$cuatrimestre' and
												id_planificacion_anual = $idPlanificacionAnual
											ORDER BY
												detalle_gasto asc;");
	
		return $res;
	}
	
	//revisar si hay influencia en reportes
	//Función sin IVA
	public function numeroPresupuestosYCostoTotal ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												count(id_presupuesto) as num_presupuestos, 
												sum(costo) as total
								  			FROM 
												g_programacion_presupuestaria.presupuesto_asignado
								  			WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado');");
					
		return $res;
	}
	
	public function numeroPresupuestosYCostoTotalIVA ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo_iva) as total
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado not in ('eliminado');");
					
		return $res;
	}
	
	//revisar si hay influencia en reportes
	public function numeroPresupuestosYCostoTotalAprobado ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo*cantidad_anual) as total
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('aprobadoDGAF','aprobado');");
					
		return $res;
	}
	
	public function numeroPresupuestosYCostoTotalAprobadoIva ($conexion, $idPlanificacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos,
												sum(costo_iva*cantidad_anual) as total
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('aprobadoDGAF','aprobado');");
					
				return $res;
	}
	
	public function numeroPresupuestosRevisados ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos_revisados
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado') and
												revisado is true;");
					
		return $res;
	}
	
	public function numeroPresupuestosXEstado ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(id_presupuesto) as num_presupuestos_revisados
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado in ('$estado');");
					
		return $res;
	}
	
	public function enviarPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_planificacion_anual=$idPlanificacionAnual;");
	
		return $res;
	}
	
	public function enviarPresupuestosXPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_planificacion_anual=$idPlanificacionAnual and
												estado not in ('eliminado');");
	
		return $res;
	}
	
	public function enviarPresupuesto ($conexion, $idPresupuesto, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_modificacion = now(),
												revisado = null
											WHERE
												id_presupuesto=$idPresupuesto and
												estado not in ('eliminado', 'aprobado');");
	
		return $res;
	}
	
	//REVISORES PROGRAMACION ANUAL
	public function listarProgramacionAnualVistaRevision ($conexion, $areaN2, $areaN4, $idGestion, $tipo, $anio, $identificadorRevisor, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio and
												pa.identificador_revisor = '$identificadorRevisor'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
					
		return $res;
	}
	
	public function abrirProgramacionAnualRevision ($conexion, $idProgramacionAnual){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_planificacion_anual = $idProgramacionAnual;");
			
		return $res;
	}
	
	public function revisarPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_revision = now(),
												observaciones_revision = '$observaciones',
												revisado = true
											WHERE
												id_planificacion_anual=$idPlanificacionAnual and
												identificador_revisor='$identificador';");
	
		return $res;
	}
	
	public function imprimirLineaPresupuestoRevision($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre,
			$idPlanificacionAnual, $ruta, $estadoRevision){
		echo $estadoRevision;
		if($estadoRevision == true){
			$revisado = 'activo';
		}else{
			$revisado = 'inactivo';
		}
		
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .$estadoRevision.
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>' .
				'<td width="10%">' .
				$cantidadAnual .
				'</td>' .
				'<td width="10%">' .
				$cuatrimestre .
				'</td>' .
				'<td>' .
				'<div class="'.$revisado.'" >' .
				'<button type="button" class="icono"></button>' .
				'</div>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPresupuestoRevision" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>';
	}
	
	public function revisarPresupuesto ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_revision = now(),
												observaciones_revision = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_revisor = '$identificador';");
	
		return $res;
	}
	
	public function asignarAprobadorPlanificacionAnual ($conexion, $idPlanificacionAnual, $identificadorAprobador, $idArea){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												identificador_aprobador='$identificadorAprobador',
												id_area_aprobador = '$idArea'
											WHERE
												id_planificacion_anual = $idPlanificacionAnual;");
	
		return $res;
	}
	
	public function asignarAprobadorPresupuesto ($conexion, $idPresupuesto, $identificadorAprobador, $idArea){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												identificador_aprobador='$identificadorAprobador',
												id_area_aprobador = '$idArea'
											WHERE
												id_presupuesto = $idPresupuesto;");
	
		return $res;
	}
	
	//APROBADORES PLANIFICACION ANUAL
	public function listarProgramacionAnualVistaAprobacion ($conexion, $areaN2, $areaN4, $idGestion, $tipo, $anio, $identificadorAprobador, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio and
												pa.identificador_aprobador = '$identificadorAprobador'
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
							
		return $res;
	}
	
	public function imprimirLineaPresupuestoAprobacion($idPresupuesto, $actividad, $detalleGasto, $renglon, $costo, $cantidadAnual, $cuatrimestre,
			$idPlanificacionAnual, $ruta, $estadoRevision){
		echo $estadoRevision;
		if($estadoRevision == true){
			$revisado = 'activo';
		}else{
			$revisado = 'inactivo';
		}
	
		return '<tr id="R' . $idPresupuesto . '">' .
				'<td width="30%">' .
				$actividad .$estadoRevision.
				'</td>' .
				'<td width="30%">' .
				$detalleGasto .
				'</td>' .
				'<td width="10%">' .
				$renglon .
				'</td>' .
				'<td width="10%">' .
				$costo .
				'</td>' .
				'<td width="10%">' .
				$cantidadAnual .
				'</td>' .
				'<td width="10%">' .
				$cuatrimestre .
				'</td>' .
				'<td>' .
				'<div class="'.$revisado.'" >' .
				'<button type="button" class="icono"></button>' .
				'</div>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPresupuestoAprobacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idPresupuesto" value="' . $idPresupuesto . '" >' .
				'<input type="hidden" name="idPlanificacionAnual" value="' . $idPlanificacionAnual . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>';
	}
	
	public function aprobarPlanificacionAnual ($conexion, $idPlanificacionAnual, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_aprobacion = now(),
												observaciones_aprobacion = '$observaciones',
												revisado = true
											WHERE
												id_planificacion_anual=$idPlanificacionAnual and
												identificador_aprobador='$identificador';");
	
		return $res;
	}
	
	//APROBADORES PRESUPUESTO
	public function listarProgramacionAnualVistaAprobacionDGAF ($conexion, $areaN2, $areaN4, $idGestion, $tipo, $anio, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.vista_planificacion_anual pa
											WHERE
												pa.id_area_n2 = '$areaN2' and
												pa.id_area_n4 = '$areaN4' and
												pa.id_area_unidad = '$idGestion' and
												pa.tipo = '$tipo' and
												pa.estado in ($estado) and
												pa.anio = $anio
											ORDER BY
												pa.id_objetivo_estrategico, pa.id_objetivo_especifico,
												pa.id_objetivo_operativo, pa.id_area_unidad asc;");
					
		return $res;
	}
	
	public function aprobarPresupuesto ($conexion, $idPresupuesto, $estado, $observaciones, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_aprobacion = now(),
												observaciones_aprobacion = '$observaciones',
												revisado = true
											WHERE
												id_presupuesto = $idPresupuesto and
												identificador_aprobador = '$identificador';");
	
		return $res;
	}
	
	//Filtros Reportes Aprobador
	public function listarObjetivosEspecificosXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_especifico oes
											WHERE
												oes.estado = 'activo' and
												oes.anio = $anio
											ORDER BY
												oes.nombre asc;");
	
		return $res;
	}
	
	public function listarObjetivosOperativosXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.objetivo_operativo oes
											WHERE
												oes.estado = 'activo' and
												oes.anio = $anio
											ORDER BY
												oes.nombre asc;");
	
		return $res;
	}
	
	public function listarProcesoProyectoXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto pp
											WHERE
												pp.estado = 'activo' and
												pp.anio = $anio
											ORDER BY
												pp.nombre asc;");
	
		return $res;
	}
	
	public function listarComponenteXAnio ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.componente c
											WHERE
												c.estado = 'activo' and
												c.anio = $anio
											ORDER BY
												c.nombre asc;");
	
		return $res;
	}
	
	//Detalle Funciones para Reporte General
	//Planificacion Anual
	public function listarProcesoProyectoXGestionYTipoReporte ($conexion, $idArea, $tipo, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.proceso_proyecto pp
											WHERE
												pp.estado = 'activo' and
												pp.id_area = '$idArea' and
												pp.tipo = '$tipo' and
												pp.anio = $anio
											ORDER BY
												pp.nombre asc;");
	
		return $res;
	}
	
	
	public function obtenerReportePlanificacionAnual($conexion,$idObjetivoEstrategico, $idAreaN2, $idObjetivoEspecifico, 
			$idAreaN4, $idObjetivoOperativo, $idGestion, $idProceso, $idComponente, $idActividad, $idProvincia, 
			$anio, $estado, $tipo){
		
		$idObjetivoEstrategico = $idObjetivoEstrategico!="" ? "" . $idObjetivoEstrategico . "" : "null";
		$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
		$idObjetivoEspecifico = $idObjetivoEspecifico!="" ? "" . $idObjetivoEspecifico . "" : "null";
		$idAreaN4 = $idAreaN4!="" ? "'" . $idAreaN4 . "'" : "null";
		$idObjetivoOperativo = $idObjetivoOperativo!="" ? "" . $idObjetivoOperativo . "" : "null";
		$idGestion = $idGestion!="" ? "'" . $idGestion . "'" : "null";
		$idProceso = $idProceso!="" ? "" . $idProceso . "" : "null";
		$idComponente = $idComponente!="" ? "" . $idComponente . "" : "null";
		$idActividad = $idActividad!="" ? "" . $idActividad . "" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$anio = $anio!="" ? "" . $anio . "" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$tipo = $tipo!="" ? "'" . $tipo . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												* 
											FROM g_programacion_presupuestaria.planificacion_anual($idObjetivoEstrategico,
												$idAreaN2,$idObjetivoEspecifico,$idAreaN4,$idObjetivoOperativo,
												$idGestion,$idProceso,$idComponente,$idActividad,$idProvincia,
												$anio,$estado, $tipo);"
											);
		
		return $res;
	}
	
	public function obtenerReportePresupuestos($conexion,$idAreaN2, $idAreaN4, $idGestion, $idProceso, 
											$idComponente, $idActividad, $tipo, $idProvincia, $anio, $estado){
	
		$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
		$idAreaN4 = $idAreaN4!="" ? "'" . $idAreaN4 . "'" : "null";
		$idGestion = $idGestion!="" ? "'" . $idGestion . "'" : "null";
		$idProceso = $idProceso!="" ? "" . $idProceso . "" : "null";
		$idComponente = $idComponente!="" ? "" . $idComponente . "" : "null";
		$idActividad = $idActividad!="" ? "" . $idActividad . "" : "null";
		$tipo = $tipo!="" ? "'" . $tipo . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$anio = $anio!="" ? "" . $anio . "" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM 
												g_programacion_presupuestaria.presupuestos($idAreaN2,$idAreaN4,$idGestion,$idProceso,
																	$idComponente,$idActividad,$tipo,$idProvincia,$anio,$estado);"
		);
	
		return $res;
	}
	
	//Cerrar Proceso DGPGE
	public function cerrarProcesoPlanificacionAnual ($conexion, $identificador, $estado, $estadoActual){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.planificacion_anual
											SET
												estado='$estado',
												fecha_cierre = now(),
												revisado = null
											WHERE
												identificador_aprobador = '$identificador' and
												estado = '$estadoActual';");
		
		return $res;
	}
	
	//Cerrar Proceso DGAF
	public function cerrarProcesoPlanificacionAnualPresupuesto ($conexion, $identificador, $estado, $estadoActual){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_programacion_presupuestaria.presupuesto_asignado
											SET
												estado='$estado',
												fecha_cierre = now(),
												revisado = null
											WHERE
												identificador_aprobador = '$identificador' and
												estado = '$estadoActual';");
	
		return $res;
	}
	
	//Matriz PAC
	
	public function obtenerReportePac($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $idProvincia, $anio, $estado){
	
			$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
			$idPrograma = $idPrograma!="" ? "'" . $idPrograma . "'" : "null";
			$idProyecto = $idProyecto!="" ? "'" . $idProyecto . "'" : "null";
			$idActividad = $idActividad!="" ? "'" . $idActividad . "'" : "null";
			$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
			$anio = $anio!="" ? "" . $anio . "" : "null";
			$estado = $estado!="" ? "'" . $estado . "'" : "null";
			
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_programacion_presupuestaria.pac($idAreaN2,$idPrograma,
													$idProyecto,$idActividad,$idProvincia,$anio,$estado);"
												);
		
			return $res;
	}
	
	public function obtenerReportePacFortalecimiento($conexion,$idAreaN2, $idPrograma,
			$idProyecto, $idActividad, $idProvincia, $anio, $estado){
	
		$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
		$idPrograma = $idPrograma!="" ? "'" . $idPrograma . "'" : "null";
		$idProyecto = $idProyecto!="" ? "'" . $idProyecto . "'" : "null";
		$idActividad = $idActividad!="" ? "'" . $idActividad . "'" : "null";
		$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
		$anio = $anio!="" ? "" . $anio . "" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.pac_fortalecimiento($idAreaN2,$idPrograma,
													$idProyecto,$idActividad,$idProvincia,$anio,$estado);");
	
		/*"SELECT
												*
											FROM
												g_programacion_presupuestaria.pac($idAreaN2,$idPrograma,
												$idProyecto,$idActividad,$idProvincia,$anio,$estado);"*/
		return $res;
	}
	
	/*
	 public function obtenerReportePac($conexion,$idAreaN2, $idProceso,
			$idActividad, $tipo, $idProvincia, $anio, $estado){
	
			$idAreaN2 = $idAreaN2!="" ? "'" . $idAreaN2 . "'" : "null";
			$idProceso = $idProceso!="" ? "" . $idProceso . "" : "null";
			$idActividad = $idActividad!="" ? "" . $idActividad . "" : "null";
			$tipo = $tipo!="" ? "'" . $tipo . "'" : "null";
			$idProvincia = $idProvincia!="" ? "" . $idProvincia . "" : "null";
			$anio = $anio!="" ? "" . $anio . "" : "null";
			$estado = $estado!="" ? "'" . $estado . "'" : "null";
			
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_programacion_presupuestaria.pac($idAreaN2,$idProceso,
													$idActividad,$tipo,$idProvincia,$anio,$estado);"
												);
		
			return $res;
	}
   
   
   CREATE OR REPLACE FUNCTION g_programacion_presupuestaria.pac(_n2 text, _proceso integer, _actividad integer, _tipo text, _provincia integer, _anio integer, _estado text)
  RETURNS SETOF g_programacion_presupuestaria.vista_pac AS
$BODY$
DECLARE
	query text;
BEGIN	
	return query EXECUTE '	
		SELECT
			*
		FROM 
			g_programacion_presupuestaria.vista_pac
		WHERE 		        
			($1 is NULL or id_area_n2 = $1) and 
			($2 is NULL or id_proceso_proyecto = $2) and 
			($3 is NULL or id_actividad = $3) and
			($4 is NULL or tipo = $4) and
			($5 is NULL or id_provincia = $5) and
			($6 is NULL or anio = $6) and
			($7 is NULL or estado_presupuesto = $7)
			
		ORDER BY
			area_n2, proceso_proyecto, actividad'
			
		using _n2, _proceso, _actividad, _tipo, _provincia, _anio, _estado;
	
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION g_programacion_presupuestaria.pac(text, integer, integer, text, integer, integer, text)
  OWNER TO postgres;

	 */
	
	//Detalle Funciones para Reporte General PAC
	public function listarProgramaXMatrizPAC ($conexion, $tipo){
	
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_programacion_presupuestaria.proceso_proyecto pp
				WHERE
				pp.estado = 'activo' and
				pp.id_area = '$idArea' and
				pp.tipo = '$tipo' and
				pp.anio = $anio
				ORDER BY
				pp.nombre asc;");
	
				return $res;
	}
	
	//REFORMA PRESUPUESTARIA
	public function listarProgramacionAnualAprobada ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.planificacion_anual pa
											WHERE
												pa.estado = 'aprobado' and
												pa.anio = $anio
											ORDER BY
												pa.id_planificacion_anual asc;");
									
		return $res;
	}
	
	public function listarPresupuestosAprobados ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												estado = 'aprobado' and
												anio = $anio
											ORDER BY
												id_presupuesto asc;");
	
		return $res;
	}
	
	public function listarPresupuestosAprobadosXPA ($conexion, $idPlanificacionAnual, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_programacion_presupuestaria.presupuesto_asignado
											WHERE
												id_planificacion_anual = $idPlanificacionAnual and
												estado = 'aprobado' and
												anio = $anio
											ORDER BY
												id_presupuesto asc;");
	
		return $res;
	}
}
?>
