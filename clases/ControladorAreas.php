<?php

class ControladorAreas{

	public function listarAreas($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM 
												g_estructura.area
											WHERE
												estado = 1
											order by 3 desc;");
		return $res;
	}
	
	public function areaUsuario($conexion,$usuario){
		$res = $conexion->ejecutarConsulta("select 
												a.*
											from 
												g_estructura.area as a,
												g_estructura.funcionarios as f
											where  
												a.id_area = f.id_area 
												and f.identificador = '$usuario'");
		return $res;
	}
	
	public function obtenerUsuarioAdministradorXProvincia($conexion, $nombreProvincia){
		$res = $conexion->ejecutarConsulta("select
												f.identificador,
												fe.nombre,
												fe.apellido
											from
												g_estructura.funcionarios f,
												g_uath.ficha_empleado fe
											where
												f.administrador = 1 and
												f.identificador = fe.identificador and
												f.id_area = (select
																a.id_area
															from
																g_estructura.area a
															where
																nombre like '%$nombreProvincia%');");
				return $res;
	}
	
	public function buscarAreasSubprocesos($conexion, $areaPadre){
									
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												id_area_padre = '$areaPadre'
												and estado = 1;");
		return $res;
	}
	
	public function buscarResponsableSubproceso($conexion, $area){
										
		$sql="select
					r.*
				from
					g_estructura.responsables r, g_uath.datos_contrato c
				where
					r.id_area = '$area'
					and r.responsable = true
					and r.estado = 1 and 
					c.identificador = r.identificador and 
					c.estado=1;";
					
		$res = $conexion->ejecutarConsulta($sql);
		
		return $res;
	}
	
	public function buscarParesEvaluacion($conexion, $tipo, $areaPadre, $area){
		
	switch ($tipo){
			case 'COORDINACION': $busqueda = "id_area like 'CP%' and id_area not in ('$area') and"; break;
			case 'PLANTACENTRAL': $busqueda = "id_area not like 'CP%' and id_area not in ('$area') and"; break;
			case 'OTRO': $busqueda = "id_area not in ('$area') and"; break;
		}
		
			$res = $conexion->ejecutarConsulta("select
													*
												from
													g_estructura.area
												where
													" . $busqueda ."
													id_area_padre = '$areaPadre';");
		return $res;
	}
	
	public function buscarMiembrosEquipo($conexion, $area, $responsable, $miembro = null){
								
		$res = $conexion->ejecutarConsulta("select
													*
												from
													g_estructura.funcionarios
												where
													id_area = '$area'
													and identificador not in ('$responsable', '$miembro')
													and estado = 1 ;");
		return $res;
	}
	
	
	public function buscarResponsableArea($conexion, $idArea){
		$res = $conexion->ejecutarConsulta("SELECT
												g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
												r.identificador
											FROM
												g_estructura.responsables r,
												g_uath.ficha_empleado fe
											WHERE
												r.identificador = fe.identificador
												and r.id_area = '$idArea'");
		return $res;
	}
	
	public function listarFuncionariosInstitucion($conexion){
		$res = $conexion->ejecutarConsulta("select
												f.*,
												a.nombre
											from
												g_estructura.funcionarios f,
												g_estructura.area a
											where 
												f.id_area = a.id_area
											order by 1;");
		return $res;
	}
	
	public function buscarPadreSubprocesos($conexion, $area){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												id_area = '$area';");
		return $res;
	}
	
	public function buscarAreaResponsablePorUsuarioRecursivo($conexion, $idArea){
		$res = $conexion->ejecutarConsulta("WITH RECURSIVE area_cte(id, nombre_area, path,clasificacion) AS (
												SELECT 
													tn.id_area, 
													tn.nombre, 
													tn.id_area::TEXT AS path, 
													tn.clasificacion 
												FROM 
													g_estructura.area AS tn 
												WHERE 
													tn.id_area_padre IS NULL and estado=1
												UNION ALL	 
												SELECT 
													c.id_area, 
													c.nombre, 
													(p.path || ',' || c.id_area::TEXT), 
													c.clasificacion 
												FROM 
													area_cte AS p, 
													g_estructura.area AS c 
												WHERE 
													c.id_area_padre = p.id and 
													c.estado=1
											)SELECT 
													* 
											FROM 
													area_cte AS n
											WHERE 
													n.id='$idArea' 
											ORDER BY 
													n.id ASC;");
		return $res;
	}
	
	public function buscarAreaPadrePorClasificacion($conexion, $areaPadre, $clasificacion){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.area
											WHERE
												id_area_padre = '$areaPadre'
												and clasificacion = '$clasificacion';");
		return $res;
	}
	
	
	
	public function obtenerFuncionariosXareasCapacitacion($conexion,$areas){
		
		$areas = ($areas=='()'?'(null)':$areas);
				
		$res = $conexion->ejecutarConsulta("select
												fu.id_area,
												fe.nombre,
												fe.apellido,
												fe.identificador,
												(Select pa.bloqueo from g_capacitacion.participantes  pa where fe.identificador=pa.identificador and pa.bloqueo=1) as bloqueo
											from 
												g_estructura.funcionarios fu INNER JOIN g_uath.ficha_empleado fe on fe.identificador = fu.identificador 
											where
												id_area IN $areas
											order by bloqueo;
				 ");
				return $res;
	}
	
	public function obtenerFuncionariosXareas($conexion,$areas){
	
		$areas = ($areas=='()'?'(null)':$areas);
	
		$res = $conexion->ejecutarConsulta("select
												fu.id_area,
												fe.nombre,
												fe.apellido,
												fe.identificador
											from 
												g_estructura.funcionarios fu 
												INNER JOIN g_uath.ficha_empleado fe on fe.identificador = fu.identificador 
											where
												fu.id_area IN $areas
												and fe.estado_empleado = 'activo'
											order by 
												fe.apellido, fe.nombre asc;");
				return $res;
	}
	
	public function obtenerAreasDireccionesTecnicas($conexion, $tipoClasificacion, $categorias){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_estructura.area
											WHERE
												categoria_area IN $categorias
												and clasificacion IN $tipoClasificacion
												and estado = 1
											ORDER BY categoria_area, nombre");
		
		return $res;
	}
	
	public function obtenerResponsablesOficinasTecnicasZona($conexion,$zona, $identificadorResponsable){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.responsables r
											WHERE
												id_area IN (SELECT id_area FROM g_estructura.area a WHERE zona_area = '$zona')
												and identificador not in ('$identificadorResponsable')
												and estado = 1");
	
				return $res;
	}
	
	public function obtenerAreasXcategoria($conexion,$categoriaArea, $clasificacion, $area){
						
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.area
											WHERE
												categoria_area = $categoriaArea
												and clasificacion = '$clasificacion'
												and id_area not in ('$area')
												and estado = 1");
	
		return $res;
	}
	
	public function buscarAreasYSubprocesos($conexion, $areaPadre){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												id_area_padre = '$areaPadre'
											UNION
											select
												*
											from
												g_estructura.area
											where
												id_area = '$areaPadre' and
												id_area not like 'OT%';");
		
		return $res;
	}
	
	public function buscarArea($conexion, $idArea){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												id_area = '$idArea';");
	
		return $res;
	}
	
	public function buscarDivisionEstructura($conexion, $areaPadre){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												id_area_padre = '$areaPadre'
											
											UNION
											
											select
												*
											from
												g_estructura.area
											where
												id_area = '$areaPadre'
											order by
												id_area asc;");
	
		return $res;
	}
	
	public function buscarDireccionesGenerales($conexion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												id_area_padre = 'DE' and
												categoria_area = 4 and
												clasificacion = 'Planta Central'
											order by
											id_area asc;");
	
	
		return $res;
	}
	
	public function buscarOficinaTecnicaXArea($conexion, $idArea){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												zona_area = '$idArea';");
	
		return $res;
	}
	
	public function obtenerParesXareaPadreYcategoria($conexion, $categoria ,$areaPadre, $area){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.area
											WHERE
												categoria_area = $categoria
												and id_area_padre = '$areaPadre'
												and id_area not in ('$area')
												and estado = 1;");
		return $res;
	}
	
	public function buscarEstructuraPlantaCentral($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.area
											WHERE
												clasificacion = 'Planta Central' and
												estado = 1
											order by
												id_area asc;");
	
	
		return $res;
	}
	
	public function listarAplicantesEvaluacionIndividual($conexion,$identificador,$vigente='activo'){
	
		$res = $conexion->ejecutarConsulta("SELECT
												e.*,ai.*,p.*,g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos
											FROM
												g_uath.ficha_empleado fe,
												g_catalogos.puestos p,
												g_uath.datos_contrato dc,
												g_evaluacion_desempenio.aplicantes_individual ai,
												g_evaluacion_desempenio.evaluaciones e
											WHERE
												fe.identificador='$identificador'
												and fe.identificador=dc.identificador
												and p.nombre_puesto=dc.nombre_puesto
												and ai.identificador_evaluado='$identificador'
												and e.id_evaluacion=ai.id_evaluacion
												and p.id_area=dc.id_gestion 
												and dc.estado=1
												and ai.estado=TRUE
												and p.estado = 1
												and ai.vigencia='$vigente';");
		return $res;
	}
	
	public function buscarEstructuraPlantaCentralProvinciasXCategoria($conexion, $idCategoria){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_estructura.area
											WHERE
												categoria_area IN ($idCategoria) and								
												estado = 1
											ORDER BY nombre, id_area_padre asc;");
	
		return $res;
	}
	
	////***PAP-PAC***////
	public function buscarEstructuraPlantaCentralProvincias($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.area
											WHERE
												categoria_area in (3,4) and
												clasificacion = 'Planta Central' and
												estado = 1 and
												id_area_padre = ('DE')
											
											UNION
											
											SELECT
												*
											FROM
												g_estructura.area
											WHERE
												categoria_area in (4) and
												clasificacion = 'Oficina Técnica' and
												estado = 1
											ORDER BY
												categoria_area, clasificacion desc,
												nombre asc;");
	
		return $res;
	}
	
	public function obtenerAreasXcategoriaPadre($conexion,$categoriaArea, $clasificacion, $area,$areaPadre){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.area
											WHERE
												categoria_area = $categoriaArea
												and clasificacion = '$clasificacion'
												and id_area not in ('$area')
												and id_area_padre='$areaPadre'
												and estado = 1");
												
				return $res;
	}
	
	public function obtenerResponsablesOficinasTecnicasZonaPadre($conexion,$zona, $identificadorResponsable){


	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_estructura.responsables r

											WHERE
												id_area IN (SELECT id_area FROM g_estructura.area a WHERE id_area_padre = '$zona')
												and identificador not in ('$identificadorResponsable')
												and estado = 1");	
				return $res;
	}	
	
}
