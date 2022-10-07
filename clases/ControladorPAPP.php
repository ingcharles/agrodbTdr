<?php

class ControladorPAPP{


	public function listarObjetivosEstrategicos ($conexion, $anio){
				
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.objetivos_estrategicos oe
											where
												anio = $anio
											order by
												oe.descripcion;");
		return $res;
	}
	
	
	public function listarProcesos ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.procesos p
											where
												p.anio = $anio
											order by
												p.descripcion;");
		return $res;
	}
	
	public function listarSubprocesos ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.subprocesos sp
											where
												anio = $anio
											order by
												sp.descripcion;");
		return $res;
	}
	
	public function listarIndicadores ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.indicadores i
											where
												anio = $anio
											order by
												i.descripcion;");
		return $res;
	}
	

	public function listarComponentes($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.componentes c
											where
												c.anio = $anio;");
		return $res;
	}
	
	public function listarActividades($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.actividades a
											where
												a.anio = $anio;");
		return $res;
	}

    public function listarPoblacionObjetivo($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(descripcion_poblacion_objetivo) as poblacion
											from 
													g_poa.matriz_poa
											where
													anio = $anio
											order by
													descripcion_poblacion_objetivo;");
		return $res;
	}
	
	public function listarResponsable($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
									        	distinct(responsable_subproceso) as responsable
											from
												g_poa.matriz_poa
											where
												anio = $anio
											order by 
												responsable_subproceso;");
		return $res;
	}
	
	public function listarMediosVerificacion($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(medios_verificacion) as medios
											from 
												g_poa.matriz_poa
											where
												anio = $anio
											order by
												medios_verificacion;");
		return $res;
	}
	
	public function obtenerDatosMatriz ($conexion,$id_item){
		$res = $conexion->ejecutarConsulta(" select *
	from g_poa.matriz_poa
	where id_item_poa='$id_item';");
		return $res;
		
	}
	
	public function sacarReporteMatrizPresupuesto($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin,$item,$gasto, $estado, $anio){
		$areaDireccion = $areaDireccion!="" ? "'" . $areaDireccion . "'" : "null";
		$objetivo = $objetivo!="" ? "'" . $objetivo . "'" : "null";
		$proceso = $proceso!="" ? "'" . $proceso . "'" : "null";
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$componente = $componente!="" ? "'" . $componente . "'" : "null";
		$actividades = $actividades!="" ? "'" . $actividades . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$item = $item!="" ? "'" . $item . "'" : "null";
		$gasto = $gasto!="" ? "'" . $gasto . "'" : "null";
		$estado = $estado!="" ? $estado : 4;
		$anio = $anio!="" ? "'" . $anio . "'" : "null";
		
		$res = $conexion->ejecutarConsulta("select 
						* 
					from 
						g_poa.mostrar_presupuestos($areaDireccion,$objetivo,$proceso,$subproceso,$actividades,$fechaInicio,$fechaFin,$item,$gasto, $estado, $anio);");
		
		/*$res = $conexion->ejecutarConsulta("select
						*
					from
						g_poa.mostrar_presupuestos($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin,$item,$gasto, $estado, $anio);");*/
		return $res;
	}
	
	public function sacarReporteMatrizPOA($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin, $indicador,$cobertura,$poblacion,$responsable,$medios, $anio){
		$areaDireccion = $areaDireccion!="" ? "'" . $areaDireccion . "'" : "null";
		$objetivo = $objetivo!="" ? "'" . $objetivo . "'" : "null";
		$proceso = $proceso!="" ? "'" . $proceso . "'" : "null";
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$componente = $componente!="" ? "'" . $componente . "'" : "null";
		$actividades = $actividades!="" ? "'" . $actividades . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$indicador = $indicador!="" ? "'" . $indicador . "'" : "null";
		$cobertura = $cobertura!="" ? "'" . $cobertura . "'" : "null";
		$poblacion = $poblacion!="" ? "'" . $poblacion . "'" : "null";
		$responsable = $responsable!="" ? "'" . $responsable . "'" : "null";
		$medios = $medios!="" ? "'" . $medios . "'" : "null";
		$anio = $anio!="" ? "'" . $anio . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_matriz_poa($areaDireccion,$objetivo,$proceso,$subproceso,$actividades,$fechaInicio,$fechaFin,$cobertura,$poblacion,$responsable,$medios, $anio);");
		
		/*$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_matriz_poa($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin,$indicador,$cobertura,$poblacion,$responsable,$medios, $anio);");*/
				
		return $res;
	}
	
	
	public function obtenerNombreArea($conexion,$usuario){
		$res = $conexion->ejecutarConsulta("Select 
												  area.nombre,area.id_area, area.id_area_padre
											from 
												   g_estructura.area, g_estructura.funcionarios
											where 
												   area.id_area=funcionarios.id_area and funcionarios.identificador='$usuario';");
		return $res;
		
		
	}
	
	public function obtenerDatosPOA ($conexion,$id_item){
	    
	         $res = $conexion->ejecutarConsulta("SELECT
													objetivos_estrategicos.descripcion as objetivo,procesos.descripcion as proceso,
													subprocesos.descripcion as subproceso,
                                                    actividades.descripcion as actividad,
													planta.estado, planta.observaciones , planta.detalle_actividad, planta.revisado, planta.detalle_actividad
												FROM
													g_poa.planta,
													g_poa.objetivos_estrategicos,
													g_poa.procesos,
													g_poa.subprocesos,
													g_poa.actividades
												WHERE
													planta.id_objetivo = objetivos_estrategicos.id_objetivo AND
													planta.id_proceso = procesos.id_proceso AND
													subprocesos.id_subproceso = planta.id_subproceso AND
													actividades.id_actividad = planta.id_actividad AND
													planta.id_item=$id_item;");
		
			/*$res = $conexion->ejecutarConsulta("SELECT
													objetivos_estrategicos.descripcion as objetivo,procesos.descripcion as proceso,
													subprocesos.descripcion as subproceso,indicadores.descripcion as indicador,
													indicadores.linea_base,metodo_calculo,indicadores.tipo,
													componentes.descripcion as componente, actividades.descripcion as actividad,
													planta.meta1,planta.meta2, planta.meta3, planta.meta4, planta.estado, planta.observaciones , planta.detalle_actividad, planta.revisado, planta.detalle_actividad
												FROM
													g_poa.planta,
													g_poa.objetivos_estrategicos,
													g_poa.procesos,
													g_poa.subprocesos,
													g_poa.indicadores,
													g_poa.componentes,
													g_poa.actividades
												WHERE
													planta.id_objetivo = objetivos_estrategicos.id_objetivo AND
													planta.id_proceso = procesos.id_proceso AND
													subprocesos.id_subproceso = planta.id_subproceso AND
													indicadores.id_indicador = planta.id_indicadores AND
													actividades.id_actividad = planta.id_actividad AND
													componentes.id_componente = planta.id_componente AND 
													planta.id_item=$id_item;");*/
			return $res;
		}
	
	
	public function listarRegistrosPOA ($conexion,$usuario, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.planta p where identificar_usuario='$usuario'
												and estado != 9
												and anio = $anio;");
		return $res;
	}
	
	public function listarItemPresupuestario ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.item_presupuestario
				order by codigo;");
		return $res;
	}
	
	public function listarItemPresupuestarioActivo ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.item_presupuestario
				where estado=1
				order by codigo;");
		return $res;
	}
	
	public function listarDetalleGasto ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(detalle_gasto)
											from 
												g_poa.matriz_presupuesto 
											where
												anio = $anio
											order by 
												detalle_gasto;");
		return $res;
	}
	

	public function listarNombreResponsable ($conexion,$id_area){
	
		$res = $conexion->ejecutarConsulta("select 
												f.identificador,
												fe.apellido||' '|| fe.nombre as nombre_apellido
											from 	
												g_estructura.funcionarios f, 
												g_uath.ficha_empleado fe
											where 
												id_area in (
															select 
																id_area 
															from 
																g_estructura.area 
															where 
																id_area_padre = '$id_area' 
																
															union  
																
															select 
																id_area 
															where 
																id_area = '$id_area')
											and f.identificador=fe.identificador
                                            ORDER BY 2;");
		return $res;
	}
	
	public function listarPOAAprobadosPlanta($conexion,$estado,$usuario, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(id_item), pr.descripcion as proceso,sp.descripcion as subproceso, a.descripcion as actividad
											FROM
												g_poa.planta p,g_poa.procesos pr,g_poa.subprocesos sp, g_poa.actividades a
											WHERE
												p.identificar_usuario='$usuario' and
												p.id_proceso = pr.id_proceso and
												p.id_subproceso = sp.id_subproceso and
												p.id_actividad = a.id_actividad and
												p.estado=$estado and
												p.anio = $anio
												order by id_item;");
		return $res;
	}
	
	//Cambio
public function listarRegistrosPOAAprobados($conexion,$estado,$usuario, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(id_item), 
												pr.descripcion as proceso,
												sp.descripcion as subproceso, 
												a.descripcion as actividad
											FROM
												g_poa.planta p, 
												g_poa.matriz_presupuesto i,
												g_poa.procesos pr,
												g_poa.subprocesos sp, 
												g_poa.actividades a
											WHERE
												not exists  (SELECT  
																mp.id_item_poa 
															FROM 
																g_poa.matriz_poa as mp where mp.id_item_poa=p.id_item) and				
												p.identificar_usuario='$usuario' and
												p.id_item=i.id_item_planta and
												p.id_proceso=pr.id_proceso and
												p.id_subproceso=sp.id_subproceso and
												p.id_actividad= a.id_actividad and
												p.estado=4 and i.estado=$estado and
												p.anio = $anio;");
		return $res;
}

//Cambio para revisar
public function listarRegistrosCerrados($conexion,$estado,$usuario){

	$res = $conexion->ejecutarConsulta("select
											distinct(id_item), 
											pr.descripcion as proceso,
											sp.descripcion as subproceso, 
											a.descripcion as actividad, 
											id_matriz
										from
											g_poa.planta p, 
											g_poa.matriz_presupuesto i,
											g_poa.procesos pr,
											g_poa.subprocesos sp, 
											g_poa.actividades a, 
											g_poa.matriz_poa as mp
										where
											p.identificar_usuario='$usuario' and	
											p.id_item=i.id_item_planta and	
											p.id_proceso=pr.id_proceso and
											p.id_subproceso=sp.id_subproceso and	
											p.codigo_actividad=(a.descripcion||a.id_subproceso) and
											p.estado=4 and 
											i.estado=$estado and 
											p.id_item=mp.id_item_poa 
										order by 
											id_item");
	
	return $res;
}
	
	public function listarRegistrosPOAUSuario ($conexion,$id, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT  
												p.id_item, a.descripcion, s.descripcion as subproceso, p.detalle_actividad
											FROM 
												g_poa.planta as p, 
												g_poa.actividades as a, 
												g_poa.subprocesos as s
											WHERE 
												a.id_actividad=p.id_actividad and 
												p.identificar_usuario='$id'and 
												estado='1' and 
												p.id_subproceso=s.id_subproceso and
												p.anio = $anio;");
		
		/*$res = $conexion->ejecutarConsulta("SELECT
												p.id_item, a.descripcion, p.meta1, p.meta2,
												p.meta3, p.meta4, s.descripcion as subproceso, p.detalle_actividad
											FROM
												g_poa.planta as p,
												g_poa.actividades as a,
												g_poa.subprocesos as s
											WHERE
												a.id_actividad=p.id_actividad and
												p.identificar_usuario='$id'and
												estado='1' and
												p.id_subproceso=s.id_subproceso and
												p.anio = $anio;");*/
		return $res;
	}
	
	public function listarRegistrosMatrizUSuario ($conexion,$id, $anio){
	
		$res = $conexion->ejecutarConsulta("select 
												id_item,
												sp.descripcion, 
												codigo_actividad,
												codigo_item, 
												detalle_gasto, 
												(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) as total
											from 
												g_poa.planta as p, 
												g_poa.matriz_presupuesto as i, 
												g_poa.subprocesos as sp
											where 
												p.estado=4 and 
												p.id_item=i.id_item_planta and
												p.id_subproceso=sp.id_subproceso and
												p.identificar_usuario='$id'and i.estado='1' and
												p.anio = $anio
											order by 
												id_item;");
			return $res;
		}
	
		/*CAMBIADO NUEVA ESTRUCTURA - SALO 28 ABRIL*/
	public function listarPOARemitidos ($conexion,$id,$subproceso,$asunto,$fechaInicio,$fechaFin, $estado, $anio){
	
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$subproceso = $subproceso!="" ? $subproceso : "null";
		$estado = $estado!="" ? $estado : "null";
		
		/*$res = $conexion->ejecutarConsulta("
				select
					*
				FROM
					g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado)
				WHERE  
					identificar_usuario in (SELECT 
												f.identificador
											FROM 
												g_estructura.funcionarios f,
												g_estructura.responsables r
											WHERE 
												f.id_area=r.id_area and
												r.identificador='$id' and r.responsable='TRUE');");*/
		
		/*$res = $conexion->ejecutarConsulta("select
												*
											FROM
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado, $anio)
											WHERE  
												identificar_usuario in (SELECT 
																			f.identificador
																		FROM 
																			g_estructura.funcionarios f
																		WHERE 
																			f.id_area in (	SELECT 
																						a.id_area
																					FROM 
																						g_estructura.responsables r,
																						g_estructura.area a
																					WHERE 
																						r.id_area = a.id_area_padre and
																						r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
				
																					UNION
				
																		SELECT 
																			a.id_area
																		FROM 
																			g_estructura.responsables r,
																			g_estructura.area a
																		WHERE 
																			r.id_area = a.id_area and
																			r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																					));");*/
		
		/*$res = $conexion->ejecutarConsulta("select
												*
											FROM
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado, $anio)
											WHERE
												identificar_usuario in (SELECT
																			f.identificador
																		FROM
																			g_estructura.funcionarios f
																		WHERE
																			f.id_area in (	SELECT
																						a.id_area
																					FROM
																						g_estructura.funcionarios r,
																						g_estructura.area a
																					WHERE
																						r.id_area = a.id_area_padre and
																						r.identificador='$id' and r.estado= 1
		    
																					UNION
		    
                																		SELECT
                																			a.id_area
                																		FROM
                																			g_estructura.funcionarios r,
                																			g_estructura.area a
                																		WHERE
                																			r.id_area = a.id_area and
                																			r.identificador='$id' and r.estado= 1
																					));");*/
		
		/*echo "select
												*
											FROM
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado, $anio)
											WHERE
												(identificar_usuario in (SELECT f.identificador
                                                FROM g_estructura.funcionarios f WHERE f.id_area in
                                                ( SELECT distinct a1.id_area FROM g_estructura.area a1 WHERE id_area_padre IN
                                                (SELECT a.id_area FROM g_estructura.responsables r, g_estructura.area a
                                                WHERE r.id_area = a.id_area_padre and r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
                                                and a.id_area_padre not ilike ('Z%')
                                                UNION
                                                SELECT a.id_area FROM g_estructura.responsables r, g_estructura.area a
                                                WHERE r.id_area = a.id_area and r.identificador='$id'
                                                and r.estado= 1 and r.responsable='TRUE' and a.id_area not ilike ('Z%')))));";*/
		
		$res = $conexion->ejecutarConsulta("select
												*
											FROM
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado, $anio)
											WHERE
												(identificar_usuario in (SELECT f.identificador 
                                                FROM g_estructura.funcionarios f WHERE f.id_area in 
                                                ( SELECT distinct a1.id_area FROM g_estructura.area a1 WHERE id_area_padre IN 
                                                (SELECT a.id_area FROM g_estructura.responsables r, g_estructura.area a 
                                                WHERE r.id_area = a.id_area_padre and r.identificador='$id' and r.estado= 1 and r.responsable='TRUE' 
                                                and a.id_area_padre not ilike ('Z%') 
                                                UNION 
                                                SELECT a.id_area FROM g_estructura.responsables r, g_estructura.area a 
                                                WHERE r.id_area = a.id_area and r.identificador='$id' 
                                                and r.estado= 1 and r.responsable='TRUE' and a.id_area not ilike ('Z%')))));");
		
		return $res;
	}
	
	public function FiltrarSubProceso($conexion,$id, $anio, $estado){

	    /*$res = $conexion->ejecutarConsulta("select 
												distinct(s.id_subproceso), 
												s.descripcion
											from 
												g_poa.planta as p, 
												g_poa.subprocesos as s
											where 
												p.id_subproceso=s.id_subproceso and 
												p.estado= $estado and 
												p.anio = $anio and
												(p.identificar_usuario in (SELECT 
															f.identificador
														FROM 
															g_estructura.funcionarios f
														WHERE 
															f.id_area in (	SELECT 
																				distinct a1.id_area
																			FROM
																				g_estructura.area a1
																			WHERE 
																				id_area_padre IN (SELECT 
																									a.id_area
																								FROM 
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE 
																									r.id_area = a.id_area_padre and
																									r.identificador='$id' and r.estado= 1
																									and a.id_area_padre not ilike ('Z%')
																								UNION
																								SELECT 
																									a.id_area
																								FROM 
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE 
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1 
																									and a.id_area not ilike ('Z%'))))) 
											order by  descripcion;");*/
		
		$res = $conexion->ejecutarConsulta("select
												distinct(s.id_subproceso),
												s.descripcion
											from
												g_poa.planta as p,
												g_poa.subprocesos as s
											where
												p.id_subproceso=s.id_subproceso and
												p.estado= $estado and
												p.anio = $anio and
												(p.identificar_usuario in (SELECT
															f.identificador
														FROM
															g_estructura.funcionarios f
														WHERE
															f.id_area in (	SELECT
																				distinct a1.id_area
																			FROM
																				g_estructura.area a1
																			WHERE
																				id_area_padre IN (SELECT
																									a.id_area
																								FROM
																									g_estructura.responsables r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area_padre and
																									r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																									and a.id_area_padre not ilike ('Z%')
																								UNION
																								SELECT
																									a.id_area
																								FROM
																									g_estructura.responsables r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																									and a.id_area not ilike ('Z%')))))
											order by  descripcion;");
		
		/*echo "select
												distinct(s.id_subproceso),
												s.descripcion
											from
												g_poa.planta as p,
												g_poa.subprocesos as s
											where
												p.id_subproceso=s.id_subproceso and
												p.estado= $estado and
												p.anio = $anio and
												(p.identificar_usuario in (SELECT
															f.identificador
														FROM
															g_estructura.funcionarios f
														WHERE
															f.id_area in (	SELECT
																				distinct a1.id_area
																			FROM
																				g_estructura.area a1
																			WHERE
																				id_area_padre IN (SELECT
																									a.id_area
																								FROM
																									g_estructura.responsables r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area_padre and
																									r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																									and a.id_area_padre not ilike ('Z%')
																								UNION
																								SELECT
																									a.id_area
																								FROM
																									g_estructura.responsables r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																									and a.id_area not ilike ('Z%')))))
											order by  descripcion;";*/
				return $res;
	} 
	
	
	public function listarPOAAprobados ($conexion,$idusuario, $identificador,$asunto,$fechaInicio,$fechaFin,$estado){
		
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$res = $conexion->ejecutarConsulta("SELECT
													p.id_item, a.descripcion, p.meta1, p.meta2, 
													p.meta3, p.meta4, s.descripcion as subproceso, 
													p.estado, p.observaciones
											FROM
													g_poa.mostrar_poa_filtrados($asunto,$fechaInicio,$fechaFin,$estado) as p, g_poa.actividades as a, g_poa.subprocesos as s
											WHERE 
													a.id_actividad =p.id_actividad and p.id_subproceso=s.id_subproceso and (p.identificar_usuario in (
														SELECT 
															f.identificador
														FROM 
															g_estructura.funcionarios f,
															g_estructura.responsables r
														WHERE 
															f.id_area=r.id_area and r.id_area='$identificador') );");
		return $res;
		
	}

	
	public function listarArea ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_estructura.area
											where
												estado = 1
											order by
												clasificacion, nombre asc;");
		
		/*"select
												*
											from
												g_estructura.area
											where
												clasificacion in ('Planta Central', 'Zona', 'Dirección Distrital A' ,'Dirección Distrital B', 'Oficina Técnica') and
												estado = 1
											order by
												clasificacion, nombre asc;"*/
		return $res;
	}
	
	

	//Cambiado
	public function listarMatrizRemitida ($conexion,$id,$subproceso,$asunto,$fechaInicio,$fechaFin, $anio){
	
		$subproceso = $subproceso!="" ? "" . $subproceso . "" : "null";
		$asunto = $asunto!="" ? "" . $asunto . "" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		
		/*echo "select
												p.id_item,
												s.descripcion,
												p.descripcion as actividad,
												p.identificar_usuario,
												sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)) as total,
												count(i.codigo_item),
												i.estado
											from
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,4, $anio) as p,
												g_poa.matriz_presupuesto as i,
												g_poa.subprocesos as s
											where
												p.id_item=i.id_item_planta and
												p.id_subproceso=s.id_subproceso and
												i.estado=2 and
												(p.identificar_usuario in (	SELECT
															f.identificador
														FROM
															g_estructura.funcionarios f
														WHERE
															f.id_area in (	SELECT
																				distinct a1.id_area
																			FROM
																				g_estructura.area a1
																			WHERE
																				id_area_padre IN (SELECT
																									a.id_area
																								FROM
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area_padre and
																									r.identificador='$id' and r.estado= 1
																									and a.id_area_padre not ilike ('Z%')
																								UNION
																								SELECT
																									a.id_area
																								FROM
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1
																									and a.id_area not ilike ('Z%'))
																				or id_area IN (SELECT
																									a.id_area
																								FROM
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1
																									and a.id_area not ilike ('Z%')))))
																									
											group by
												p.id_item,
												s.descripcion,
												p.descripcion,
												p.identificar_usuario,
												i.estado
											order by
												p.id_item;";*/
		
		/*$res = $conexion->ejecutarConsulta("select
												p.id_item,
												s.descripcion, 
												p.descripcion as actividad, 
												p.identificar_usuario,
												sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)) as total, 
												count(i.codigo_item), 
												i.estado
											from
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,4, $anio) as p, 
												g_poa.matriz_presupuesto as i,
												g_poa.subprocesos as s
											where 
												p.id_item=i.id_item_planta and 
												p.id_subproceso=s.id_subproceso and 
												i.estado=2 and 
												(p.identificar_usuario in (	SELECT 
															f.identificador
														FROM 
															g_estructura.funcionarios f
														WHERE 
															f.id_area in (	SELECT 
																				distinct a1.id_area
																			FROM
																				g_estructura.area a1
																			WHERE 
																				id_area_padre IN (SELECT 
																									a.id_area
																								FROM 
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE 
																									r.id_area = a.id_area_padre and
																									r.identificador='$id' and r.estado= 1 
																									and a.id_area_padre not ilike ('Z%')
																								UNION
																								SELECT 
																									a.id_area
																								FROM 
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE 
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1 
																									and a.id_area not ilike ('Z%'))
																				or id_area IN (SELECT 
																									a.id_area
																								FROM 
																									g_estructura.funcionarios r,
																									g_estructura.area a
																								WHERE 
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1 
																									and a.id_area not ilike ('Z%')))))
											
											group by 
												p.id_item, 
												s.descripcion, 
												p.descripcion,
												p.identificar_usuario, 
												i.estado
											order by 
												p.id_item;");*/
		
		/*$res = $conexion->ejecutarConsulta("select
												p.id_item,
												s.descripcion,
												p.descripcion as actividad,
												p.identificar_usuario,
												sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)) as total,
												count(i.codigo_item),
												i.estado
											from
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,4, $anio) as p,
												g_poa.matriz_presupuesto as i,
												g_poa.subprocesos as s
											where
												p.id_item=i.id_item_planta and
												p.id_subproceso=s.id_subproceso and
												i.estado=2 and
												(p.identificar_usuario in (	SELECT
															f.identificador
														FROM
															g_estructura.funcionarios f
														WHERE
															f.id_area in (	SELECT
																				distinct a1.id_area
																			FROM
																				g_estructura.area a1
																			WHERE
																				id_area_padre IN (SELECT
																									a.id_area
																								FROM
																									g_estructura.responsables r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area_padre and
																									r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																									and a.id_area_padre not ilike ('Z%')
																								UNION
																								SELECT
																									a.id_area
																								FROM
																									g_estructura.responsables r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																									and a.id_area not ilike ('Z%'))
																				or id_area IN (SELECT
																									a.id_area
																								FROM
																									g_estructura.responsables r,
																									g_estructura.area a
																								WHERE
																									r.id_area = a.id_area and
																									r.identificador='$id' and r.estado= 1 and r.responsable='TRUE'
																									and a.id_area not ilike ('Z%')))))
		    
											group by
												p.id_item,
												s.descripcion,
												p.descripcion,
												p.identificar_usuario,
												i.estado
											order by
												p.id_item;");*/
		
		$res = $conexion->ejecutarConsulta("select
												p.id_item,
												s.descripcion,
												p.descripcion as actividad,
												p.identificar_usuario,
												sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)) as total,
												count(i.codigo_item),
												i.estado
											from
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,4, $anio) as p,
												g_poa.matriz_presupuesto as i,
												g_poa.subprocesos as s
											where
												p.id_item=i.id_item_planta and
												p.id_subproceso=s.id_subproceso and
												i.estado=2 and
												(p.identificar_usuario in (	SELECT f.identificador FROM g_estructura.funcionarios f WHERE f.id_area in 
                                                    ( SELECT distinct a1.id_area FROM g_estructura.area a1 WHERE id_area_padre IN 
                                                    (SELECT a.id_area FROM g_estructura.responsables r, g_estructura.area a WHERE r.id_area = a.id_area_padre and r.identificador='$id' 
                                                    and 
                                                    r.estado= 1 and 
                                                    r.responsable='TRUE' and 
                                                    a.id_area_padre not ilike ('Z%') 
                                                    
                                                    UNION 
                                                    
                                                    SELECT a.id_area FROM g_estructura.responsables r, g_estructura.area a WHERE r.id_area = a.id_area and r.identificador='$id' 
                                                    and 
                                                    r.estado= 1 and 
                                                    r.responsable='TRUE' and 
                                                    a.id_area not ilike ('Z%'))) ))		    
											group by
												p.id_item,
												s.descripcion,
												p.descripcion,
												p.identificar_usuario,
												i.estado
											order by
												p.id_item;");
			return $res;
		}
	
	//Cambiada 
	public function listarMatrizAprobada ($conexion,$idArea,$subproceso,$asunto,$fechaInicio,$fechaFin,$estado, $anio){
			
			$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
			$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
			$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
			$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
			$estado = $estado!="" ? $estado : "null";
		
			$res = $conexion->ejecutarConsulta("SELECT
													p.id_item,s.descripcion, a.descripcion as actividad, 
													p.identificar_usuario,sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)) as total,
													 count(i.codigo_item), i.estado
												FROM
													g_poa.mostrar_poa_filtrados($subproceso,$asunto,$fechaInicio,$fechaFin,'4') as p,
													g_poa.matriz_presupuesto as i,
													g_poa.subprocesos as s,
													g_poa.actividades as a  
												WHERE
													p.id_item=i.id_item_planta and 
													p.id_subproceso=s.id_subproceso and 
													p.id_actividad=a.id_actividad
													and i.estado='$estado'
													 and (p.identificar_usuario in (SELECT f.identificador
													                                FROM g_estructura.funcionarios f
													                                WHERE f.id_area='$idArea'))
													group by 
														id_item, 
														s.descripcion,
														a.descripcion,
														p.identificar_usuario, 
														i.estado
													order by p.id_item;");
			return $res;
		}
		

		

	public function actualizarPlanta($conexion, $idusuario, $id,$valor){
	
		$res = $conexion->ejecutarConsulta("update g_poa.planta 
                                             set estado='$valor',
											observaciones=null
                                             where estado=1
				                             and identificar_usuario='$idusuario'
				                             and id_item=$id;");
						return $res;
	}

	public function actualizarEstado($conexion, $idusuario, $id,$valor,$observacion){
	
		$res = $conexion->ejecutarConsulta("update g_poa.planta
				set estado='$valor',
				observaciones='$observacion',
				id_coordinador='$idusuario',
				fecha_revision = now()
				where (estado=2 or estado=3)
				and id_item=$id;");
				return $res;
	}
	
	public function actualizarMetaPlanta($conexion,$id_Item, $meta1, $meta2,$meta3,$meta4){
	
		$res = $conexion->ejecutarConsulta("update g_poa.planta
				set meta1=$meta1,
				meta2=$meta2,
				meta3=$meta3,
				meta4=$meta4,
				estado=1
				where (estado=1 or estado=0)
				and id_item=$id_Item;");
				return $res;
	}
	
	public function actualizarMatrizPresupuesto($conexion, $codigo_item, $id_item_planta,$valor){
	
		$res = $conexion->ejecutarConsulta("update g_poa.matriz_presupuesto
				set estado='$valor'
				where estado=1
				and codigo_item='$codigo_item'
				and id_item_planta=$id_item_planta;");
				return $res;
	}
	
	public function actualizarEstadoMatrizPresupuesto($conexion, $codigo_item, $id_item_planta,$valor,$observacion, $coodinador, $id_presupuesto){
	
		$res = $conexion->ejecutarConsulta("update g_poa.matriz_presupuesto
                            				set estado='$valor',
                                				observaciones='$observacion',
                                				id_coordinador='$coodinador'
                            				where codigo_item='$codigo_item'
                                				and id_item_planta='$id_item_planta'
                                                and id_presupuesto= $id_presupuesto;");
		
		/*$res = $conexion->ejecutarConsulta("update g_poa.matriz_presupuesto
				set estado='$valor',
				observaciones='$observacion',
				id_coordinador='$coodinador'
				where codigo_item='$codigo_item'
				and id_item_planta='$id_item_planta';");*/
		
		return $res;
	}
	
	public function aprobarEstadoMatrizPresupuesto($conexion, $id_item_planta,$valor, $coodinador, $opcion){
		
		switch ($opcion){
			case 'coordinador': $consulta = 'estado=2'; break;
			case 'planta': $consulta = 'estado=3 '; break;
		}
	
		$res = $conexion->ejecutarConsulta("update 
												g_poa.matriz_presupuesto
											set 
												estado='$valor',
												id_coordinador='$coodinador',
												fecha_revision_presupuesto = now()
											where 
												id_item_planta='$id_item_planta' and 
												".$consulta.";");
		return $res;
	}
	

	public function abrirObjetivo ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.objetivos_estrategicos oe
				                            where 
												id_objetivo=$id;");
		return $res;
	}
	
	public function abrirItemPresupuestario ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.item_presupuestario
				where
				codigo='$id';");
				return $res;
	}
	
	public function abrirProceso ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.procesos p
				where
				id_proceso=$id;");
				return $res;
	}
	
	public function abrirIndicador ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.indicadores i
				where
				i.id_indicador=$id;");
		return $res;
	}
	
	public function abrirSubproceso ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.subprocesos sp
											where
												id_subproceso=$id;");
		return $res;
	}
	
	
	public function obtenerSubprocesoXProceso ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
											 	p.id_proceso, p.descripcion as descripcion_proceso, s.id_subproceso, s.descripcion as descripcion_subproceso 
											FROM 
												g_poa.procesos p, 
												g_poa.subprocesos s
											WHERE
											 	p.id_proceso=s.id_proceso and
												p.anio = s.anio and
												s.anio = $anio
											order by
											 	p.id_proceso");
		return $res;
	}
	
	public function obtenerComponenteXProceso ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												p.id_proceso, 
												p.descripcion, 
												c.id_proceso as codigo, 
												c.descripcion as componente,
												c.id_componente
											FROM
												g_poa.procesos p, 
												g_poa.componentes c
											WHERE
												 p.id_proceso=c.id_proceso and
												 p.anio = c.anio and
												 c.anio = $anio
											order by
												 p.id_proceso");
		return $res;
	}
	
	public function obtenerActividadesXSubProceso ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												a.id_actividad,
												s.id_subproceso, 
												s.descripcion as sub_proceso, 
												a.descripcion as descripcion_actividad 
											FROM 
												g_poa.subprocesos s, 
												g_poa.actividades a
											WHERE
												s.id_subproceso=a.id_subproceso and
												a.anio = a.anio and
												a.anio = $anio
											order by
												a.id_subproceso");
		
		return $res;
	}
	
	public function obtenerPresupuestoTrimestral ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select 
id_item_planta,sum((enero+febrero+marzo)) as trim1, sum((abril+mayo+junio)) as trim2, sum((julio+agosto+septiembre)) as trim3, sum((octubre+noviembre+diciembre)) as trim4
from
g_poa.matriz_presupuesto
where estado=4
and id_item_planta=$id
group by id_item_planta");
		return $res;
	}
	
	public function seleccionarComponentes($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.componentes c
				where
				id_proceso=$id;");
		return $res;
	}
	
	public function seleccionarActividades($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.actividades a
											where
												id_subproceso=$id;");
		return $res;
	}
	
	public function seleccionarItemsPresupuestarios($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
				id_presupuesto, codigo_item, id_item_planta,detalle_gasto,estado, (enero+ febrero+ marzo+ abril+mayo+junio+julio+agosto+ septiembre+ octubre+ noviembre+ diciembre) as total
				from
				g_poa.matriz_presupuesto
				where
				id_item_planta=$id;");
		
		/*$res = $conexion->ejecutarConsulta("select
				codigo_item, id_item_planta,detalle_gasto,estado, (enero+ febrero+ marzo+ abril+mayo+junio+julio+agosto+ septiembre+ octubre+ noviembre+ diciembre) as total
				from
				g_poa.matriz_presupuesto
				where
				id_item_planta=$id;");*/
		
		return $res;
	}
	
	public function desplegarItemsPresupuestarios($conexion,$id,$estado){
	
		$res = $conexion->ejecutarConsulta("select
                            				    id_presupuesto, codigo_item, id_item_planta,detalle_gasto, (enero+ febrero+ marzo+ abril+mayo+junio+julio+agosto+ septiembre+ octubre+ noviembre+ diciembre) as total
                            				from
                            				    g_poa.matriz_presupuesto
                            				where
                                				estado=$estado  and
                                				id_item_planta=$id;");
		
		/*$res = $conexion->ejecutarConsulta("select
				codigo_item, id_item_planta,detalle_gasto, (enero+ febrero+ marzo+ abril+mayo+junio+julio+agosto+ septiembre+ octubre+ noviembre+ diciembre) as total
				from
				g_poa.matriz_presupuesto
				where
				estado=$estado  and
				id_item_planta=$id;");*/
		
		return $res;
	}
	
	public function seleccionarItemXIdPOA($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.matriz_presupuesto as p,g_poa.item_presupuestario as i 
				where 
                p.codigo_item=i.codigo and 
				id_item_planta=$id;");
		return $res;
	}
		
	public function actualizarObjetivo ($conexion,$id,$descripcion){
	
		$res = $conexion->ejecutarConsulta("update 
								g_poa.objetivos_estrategicos
				set
				descripcion='$descripcion' 
				where
				id_objetivo=$id;");
				return $res;
	}
	
		
	public function actualizarProceso ($conexion,$id,$descripcion){
	
		$res = $conexion->ejecutarConsulta("update
				g_poa.procesos
				set
				descripcion='$descripcion'
				where
				id_proceso=$id;");
				return $res;
	}
	
	public function actualizarIndicador ($conexion, $id, $descripcion, $lineaBase, $metodoCalculo, $tipo){
	
		$res = $conexion->ejecutarConsulta("update
												g_poa.indicadores
											set
												descripcion='$descripcion',
												linea_base = $lineaBase,
												metodo_calculo='$metodoCalculo',
												tipo='$tipo'
											where
												id_indicador=$id;");
		return $res;
	}
	
	public function actualizarSubproceso ($conexion,$id,$descripcion){
	
		$res = $conexion->ejecutarConsulta("update
				g_poa.subprocesos
				set
				descripcion='$descripcion'
				where
				id_subproceso=$id;");
		return $res;
	}
	
	public function actualizaritemPresupuestario ($conexion,$id,$descripcion, $estado){
	
		$res = $conexion->ejecutarConsulta("update
				g_poa.item_presupuestario
				set
				descripcion='$descripcion',
				estado = $estado
				where
				codigo='$id';");
				return $res;
	}
	
	public function nuevoObjetivo ($conexion,$descripcion, $anio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.objetivos_estrategicos(
            									descripcion,
												fecha_creacion,
												anio
												)
										    VALUES ('".$descripcion."',
													now(),
													$anio);");
				return $res;
	}
	
	public function nuevoProceso ($conexion,$descripcion,$esProceso, $anio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.procesos(
            									descripcion,
				                                proyecto,
												fecha_creacion,
												anio
												)
										    VALUES ('".$descripcion."',
				                                    ".$esProceso. ",
													now(),
													$anio);");
		return $res;
	}
	
	public function nuevoComponente ($conexion,$id,$descripcion, $anio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.componentes(
            									descripcion,
												id_proceso,
				                                fecha_creacion,
												anio
												)
										    VALUES ('".$descripcion."',
													".$id.",now(), $anio);");
		return $res;
	}
	
	public function nuevoItemPresupuestario ($conexion,$codigo, $descripcion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.item_presupuestario(
            codigo, descripcion, estado)
    VALUES ('".$codigo."', '".$descripcion."', 1);");
		return $res;
	}
	
	public function eliminarObjetivoComponente ($conexion,$id, $texto){
	
		$res = $conexion->ejecutarConsulta("delete
				from 
					g_poa.componentes
				where
					id_proceso=$id and
				    descripcion='".$texto."';");
		return $res;
	}
	
	public function eliminarItemPresupuesto ($conexion,$id_planta,$codigo_item, $id_presupuesto){
	
		$res = $conexion->ejecutarConsulta("delete from
												g_poa.matriz_presupuesto
											where
												id_presupuesto=$id_presupuesto and
                                                id_item_planta=$id_planta and
												codigo_item='".$codigo_item."';");
		return $res;
	}
	
	/*public function eliminarItemPresupuesto ($conexion,$id_planta,$codigo_item){
	    
	    $res = $conexion->ejecutarConsulta("delete from
												g_poa.matriz_presupuesto
											where
												id_item_planta=$id_planta and
												codigo_item='".$codigo_item."';");
	    return $res;
	}*/
	
	public function eliminarActividadSubproceso ($conexion,$id){
			
		$res = $conexion->ejecutarConsulta("delete
				from
					g_poa.actividades
				where
					id_actividad=$id;");
		
		return $res;
	}
	
	public function nuevoSubproceso ($conexion,$descripcion,$id, $anio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.subprocesos(
            									descripcion,
				                                id_proceso,
												fecha_creacion,
												anio
												)
										    VALUES ('".$descripcion."',
				                                    ".$id.",
													now(),
													$anio);");
		return $res;
	}
	
	
	public function nuevaActividad ($conexion,$id,$descripcion, $anio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.actividades(
            									descripcion,
												id_subproceso,
				                                fecha_creacion,
												anio
												)
										    VALUES ('".$descripcion."',
													".$id.",now(), $anio) 
											returning id_actividad;");
		return $res;
	}
	
	
	
	
	public function nuevoIndicador ($conexion,$descripcion, $idActividad, $lineaBase, $metodoCalculo, $tipo, $anio){
		
			$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.indicadores(
	            									descripcion,
													fecha_creacion,
													id_actividad,
													linea_base,
													metodo_calculo,
													tipo,
													anio
													)
											    VALUES ('$descripcion',
														now(),
														$idActividad,
														$lineaBase,
														'$metodoCalculo',
														'$tipo',
														$anio) 
												returning id_indicador;");
			return $res;
		}
	
		public function nuevaPlanta ($conexion,$id_estrategico, $id_proceso, $id_subproceso, $id_componentes=0, $descripcion_componente='', $id_actividades, $descripcion_actividad, $id_listaIndicadores=0, $meta1=0, $meta2=0,$meta3=0,$meta4=0, $usuario, $detalle, $anio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.planta(
           															 id_objetivo, id_proceso, id_subproceso, id_componente, codigo_componente, id_actividad,codigo_actividad, id_indicadores,  
																	meta1, meta2, meta3, meta4, fecha_creacion,identificar_usuario, estado, detalle_actividad, revisado, anio)
													VALUES ($id_estrategico,$id_proceso,$id_subproceso,$id_componentes, '$descripcion_componente',$id_actividades,'$descripcion_actividad',".$id_listaIndicadores.",".$meta1.",".$meta2.",".$meta3.",".$meta4.",now(),'".$usuario."',1, '$detalle', false, $anio);");
		return $res;
	}
	
	
	
	
	
	public function nuevaRegistroMatrizPresupuesto($conexion, $codigo_item, $detalle_gasto,$id_item_planta,
			$enero,$febrero,$marzo,	$abril,$mayo,$junio,$julio,$agosto,$septiembre,$octubre, $noviembre, $diciembre, $anio)
	{
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.matriz_presupuesto(
            codigo_item, detalle_gasto, id_item_planta, enero, febrero, 
            marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, 
            noviembre, diciembre,estado,fecha_creacion, anio)
				 VALUES ('".$codigo_item."','".$detalle_gasto."',".$id_item_planta.",".$enero.",".$febrero.",".$marzo.",".$abril.
				",".$mayo.",".$junio.",".$julio.",".$agosto.",".$septiembre.",".$octubre.",".$noviembre.",".$diciembre.",1,now(), $anio)
            RETURNING id_presupuesto;");
		
		/*$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.matriz_presupuesto(
            codigo_item, detalle_gasto, id_item_planta, enero, febrero,
            marzo, abril, mayo, junio, julio, agosto, septiembre, octubre,
            noviembre, diciembre,estado,fecha_creacion, anio)
				 VALUES ('".$codigo_item."','".$detalle_gasto."',".$id_item_planta.",".$enero.",".$febrero.",".$marzo.",".$abril.
		    ",".$mayo.",".$junio.",".$julio.",".$agosto.",".$septiembre.",".$octubre.",".$noviembre.",".$diciembre.",1,now(), $anio);");*/
		    
		return $res;
	
	
	}
	
	public function buscarPresupuestoXNombre($conexion, $codigo_item, $detalle_gasto, $id_item_planta, $anio)
	{
	    $res = $conexion->ejecutarConsulta("SELECT 
                                                *
                                            FROM 
                                                g_poa.matriz_presupuesto
                                            WHERE
                                                codigo_item = '$codigo_item' and 
                                                upper(quitar_caracteres_especiales(detalle_gasto)) ilike upper(quitar_caracteres_especiales('$detalle_gasto')) and 
                                                id_item_planta = $id_item_planta and 
                                                anio = $anio;");
	    
	    return $res;
	}
	
	//Cambios
	public function nuevoRegistroMatriz ($conexion,$id_item,$idDivision,
			$programacion1,$programacion2,$programacion3,$programacion4,
			$coberturaTerritorial,$beneficiados,$descipcionPoblacion,$responsable,$mediosVerificacion, $anio)
	{
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.matriz_poa(
             id_item_poa, division, programacion1, 
            programacion2, programacion3, programacion4, cobertura, numero_beneficiados, 
            descripcion_poblacion_objetivo, responsable_subproceso, medios_verificacion,fecha_creacion, anio)
				 VALUES (".$id_item.",'".$idDivision."','".$programacion1."','".$programacion2."','".$programacion3."','".$programacion4.
				"','".$coberturaTerritorial."',".$beneficiados.",'".$descipcionPoblacion."','".$responsable."','".$mediosVerificacion."',now(), $anio);");
		return $res;
		
	
	}
	
	public function imprimirLineaActividad($idActividad, $descripcion){
		return '<tr id="R' . $idActividad . '">' .
				'<td width="100%">' .
				$descripcion .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="poa" data-opcion="abrirActividad" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idActividad" value="' . $idActividad . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="poa" data-opcion="quitarActividad">' .
				'<input type="hidden" name="idActividad" value="' . $idActividad . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function abrirActividad($conexion, $idActividad){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.actividades
											where
												id_actividad = $idActividad;");
		return $res;
	}
	
	public function listarIndicadorXActividad($conexion,$idActividad){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.indicadores i
											where
												i.id_actividad=$idActividad;");
		return $res;
	}
	
	public function actualizarActividad($conexion, $idActividad, $observacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_poa.actividades
											SET 
												descripcion='$observacion'
											WHERE 
												id_actividad=$idActividad;");
		return $res;
	}
	
	public function imprimirLineaIndicador($idIndicador, $descripcion, $lineaBase, $metodoCalculo, $tipo){
		return '<tr id="R' . $idIndicador . '">' .
				'<td width="25%">' .
				$descripcion .
				'</td>' .
				'<td width="25%">' .
				$lineaBase .
				'</td>' .
				'<td width="25%">' .
				$metodoCalculo .
				'</td>' .
				'<td width="25%">' .
				$tipo .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="poa" data-opcion="abrirIndicador" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idIndicador" value="' . $idIndicador . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="poa" data-opcion="quitarIndicador">' .
				'<input type="hidden" name="idIndicador" value="' . $idIndicador . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarIndicador($conexion, $idIndicador){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_poa.indicadores
 											WHERE
												id_indicador = $idIndicador;");
		
		return $res;
	}
	
	public function actualizarEstadoMatrizPapp($conexion, $id, $estado){
	
		$res = $conexion->ejecutarConsulta("update 
												g_poa.planta
											set
												estado=$estado													
											where 
												id_item=$id;");
		return $res;
	}
	
	public function eliminarSubproceso ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("delete
											from
												g_poa.subprocesos 
											where
												id_subproceso=$id;");
		return $res;
	}
	
	public function listarAreasConElementosPAPP ($conexion, $anio){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(f.id_area),
												a.nombre
											from
												g_poa.planta p,
												g_estructura.funcionarios f,
												g_estructura.area a
											where
												p.identificar_usuario = f.identificador and
												p.estado=3 and
												f.id_area = a.id_area and
												p.anio = $anio;");
		return $res;
	}
	
	
	public function listarPOARemitidosAdministrador ($conexion,$idArea,$subproceso,$asunto, $fechaInicio,$fechaFin, $estado, $anio){
	
		$subproceso = $subproceso!="" ? $subproceso : "null";
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											FROM
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado, $anio)
											WHERE
												identificar_usuario in (SELECT
																			f.identificador
																		FROM
																			g_estructura.funcionarios f--,
																			--g_estructura.responsables r
																		WHERE
																			--f.id_area=r.id_area and
																			f.id_area = '$idArea');");
	
		return $res;
	}
	
	public function revisarPAPP ($conexion,$id, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_poa.planta
											SET
												revisado=$estado
											WHERE
												id_item=$id;");
				return $res;
	}
	
	public function listarAreasConMatrizPresupuesto ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(f.id_area),
												a.nombre
											from
												g_poa.planta p,
												g_estructura.funcionarios f,
												g_estructura.area a,
												g_poa.matriz_presupuesto mp
											where
												p.identificar_usuario = f.identificador and
												p.estado=4 and
												f.id_area = a.id_area and
												mp.estado=3 and
												mp.id_item_planta = p.id_item
											order by
												a.nombre asc;");
		return $res;
	}
	
	//Cambiada
	public function listarMatrizPresupuestosRemitidosAdministrador ($conexion,$idArea,$subproceso,$asunto, $fechaInicio,$fechaFin, $estado, $anio){
	
		$subproceso = $subproceso!="" ? $subproceso : "null";
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											FROM
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado,$anio) rp,
												g_poa.matriz_presupuesto mp
											WHERE
												rp.identificar_usuario in (SELECT
																				f.identificador
																			FROM
																				g_estructura.funcionarios f
																			WHERE
																				f.id_area = '$idArea') and
																				rp.id_item = mp.id_item_planta and
																				mp.estado=3;");
	
		return $res;
	}
	
	public function sacarReporteMatrizPOAEtapas($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$indicador,$estado, $anio){
		$areaDireccion = $areaDireccion!="" ? "'" . $areaDireccion . "'" : "null";
		$objetivo = $objetivo!="" ? "'" . $objetivo . "'" : "null";
		$proceso = $proceso!="" ? "'" . $proceso . "'" : "null";
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$componente = $componente!="" ? "'" . $componente . "'" : "null";
		$actividades = $actividades!="" ? "'" . $actividades . "'" : "null";
		$indicador = $indicador!="" ? "'" . $indicador . "'" : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "4";
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_matriz_poa_etapas($areaDireccion,$objetivo,$proceso,$subproceso,$actividades,$estado, $anio);");
		
		/*$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_matriz_poa_etapas($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$indicador,$estado, $anio);");*/
		return $res;
	}
	
	public function sacarReporteActividades($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades, $anio){
		$areaDireccion = $areaDireccion!="" ? "'" . $areaDireccion . "'" : "null";
		$objetivo = $objetivo!="" ? "'" . $objetivo . "'" : "null";
		$proceso = $proceso!="" ? "'" . $proceso . "'" : "null";
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$componente = $componente!="" ? "'" . $componente . "'" : "null";
		$actividades = $actividades!="" ? "'" . $actividades . "'" : "null";
		$anio = $anio!="" ? "'" . $anio . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_actividades($areaDireccion,$objetivo,$proceso,$subproceso,$actividades, $anio);");
		
		/*$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_actividades($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades, $anio);");*/
		
		return $res;
	}
	
	public function sacarReporteActividadesPresupuesto($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades, $anio){
		$areaDireccion = $areaDireccion!="" ? "'" . $areaDireccion . "'" : "null";
		$objetivo = $objetivo!="" ? "'" . $objetivo . "'" : "null";
		$proceso = $proceso!="" ? "'" . $proceso . "'" : "null";
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$componente = $componente!="" ? "'" . $componente . "'" : "null";
		$actividades = $actividades!="" ? "'" . $actividades . "'" : "null";
		$anio = $anio!="" ? "'" . $anio . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_actividades_presupuesto($areaDireccion,$objetivo,$proceso,$subproceso,$actividades, $anio);");
		
		/*$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_actividades_presupuesto($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades, $anio);");*/
		
		return $res;
	}
	
	public function guardarNuevoSeguimiento($conexion,$idItem, $trimestre, $meta, $avanceMeta,$porcentajeAvance,$numeroItems, 
											$numeroPlanificados, $porcentajeCumplimiento, $observacionesMetas){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.seguimiento_trimestral(
										            trimestre, meta, avance_meta, porcentaje_avance, items_realizados, 
													items_solicitados, porcentaje_cumplimiento,
										            observacion_metas, id_planta, estado, fecha_creacion)
										    VALUES ($trimestre, $meta, $avanceMeta, $porcentajeAvance, $numeroItems, 
													$numeroPlanificados, $porcentajeCumplimiento, 
										            '$observacionesMetas', $idItem, 1, now());");
		return $res;
	}

	
	public function listarSeguimientoXTrimestre ($conexion, $idPlanta, $trimestre){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.seguimiento_trimestral
											where
												id_planta = $idPlanta and
												trimestre = $trimestre
											order by
												trimestre asc;");
	
		return $res;
	}
	
	
	public function presupuestoTrimestralXActividad ($conexion, $idPlanta){
	
		$res = $conexion->ejecutarConsulta("select
												sum(enero+febrero+marzo) as trimestre1,
												sum(abril+mayo+junio) as trimestre2,
												sum(julio+agosto+septiembre) as trimestre3,
												sum(octubre+noviembre+diciembre) as trimestre4,
												sum(enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) as total_presupuesto
											from
												g_poa.matriz_presupuesto
											where
												id_item_planta = $idPlanta;");
	
		return $res;
	}	
	
	
	public function actualizarSeguimiento($conexion,$idItem, $trimestre, $meta, $avanceMeta,$porcentajeAvance,$numeroItems, 
											$numeroPlanificados, $porcentajeRealizados, $observacionesMetas){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_poa.seguimiento_trimestral
										    SET 
												meta=$meta,
												avance_meta=$avanceMeta, 
												porcentaje_avance=$porcentajeAvance, 
										        items_realizados=$numeroItems, 
												items_solicitados = $numeroPlanificados,
												porcentaje_cumplimiento = $porcentajeRealizados,
												observacion_metas='$observacionesMetas'												
											WHERE
												trimestre = $trimestre and
												id_planta = $idItem and
												estado = 1;");
	
				return $res;
	}
	
	
	public function enviarSeguimiento($conexion,$idItem, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_poa.seguimiento_trimestral
											SET
												estado = $estado
											WHERE
												id_seguimiento = $idItem;");
				
		return $res;
	}
	
	public function listarRegistrosSeguimientoUsuario ($conexion,$trimestre,$identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												p.id_item, 
												p.detalle_actividad,
												a.descripcion, 
												s.descripcion as subproceso, 
												m.*
											FROM
												g_poa.planta as p,
												g_poa.actividades as a,
												g_poa.subprocesos as s,
												g_poa.seguimiento_trimestral m
											WHERE
												a.id_actividad = p.id_actividad and
												p.identificar_usuario = '$identificador'and
												m.estado = 1 and
												p.estado= 4 and
												p.id_subproceso = s.id_subproceso and
												p.id_item = m.id_planta and
												m.trimestre = $trimestre;");
				
		return $res;
	}
	

	public function listarSeguimientosRemitidos ($conexion,$identificador,$subproceso,$asunto,$fechaInicio,$fechaFin){
	
		$res = $conexion->ejecutarConsulta("select
												p.id_item,
												s.descripcion,
												p.descripcion as actividad,
												p.identificar_usuario,
												i.*
											from
												g_poa.filtrar_registros_poa(null,null,null,null,4) as p,
												g_poa.seguimiento_trimestral as i,
												g_poa.subprocesos as s
											where
												p.id_item=i.id_planta and
												p.id_subproceso=s.id_subproceso and
												i.estado=2 and
												(p.identificar_usuario in (
																			SELECT
																				f.identificador
																			FROM
																				g_estructura.funcionarios f,
																				g_estructura.responsables r
																			WHERE
																				f.id_area=r.id_area and
																				r.identificador='$identificador' and
																				r.responsable='TRUE'
																		)
												)
											group by
												id_item,
												s.descripcion,
												p.descripcion,
												p.identificar_usuario,
												i.id_seguimiento,
												i.trimestre,
												i.porcentaje_avance,
												i.estado
												order by
												p.id_item;");
				return $res;
	}
	
	public function revisionSeguimientoTrimestral($conexion, $idItemPlanta, $trimestre, $observacion, $coodinador, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_poa.seguimiento_trimestral
										    SET 
												estado=$estado, 
												id_coordinador='$coodinador', 
												observaciones='$observacion'
										    WHERE 
												trimestre=$trimestre and
												id_planta=$idItemPlanta;");
		return $res;
	}
	
	public function revisionSeguimientoTrimestralPlanta($conexion, $idSeguimiento, $coodinador, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_poa.seguimiento_trimestral
											SET
												estado=$estado,
												id_coordinador='$coodinador'
											WHERE
												id_seguimiento=$idSeguimiento;");
			
		return $res;
	}

	public function listarAreasConSeguimientoTrimestral ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(f.id_area),
												a.nombre
											from
												g_poa.planta p,
												g_estructura.funcionarios f,
												g_estructura.area a,
												g_poa.seguimiento_trimestral s
											where
												p.identificar_usuario = f.identificador and
												p.estado=4 and
												f.id_area = a.id_area and
												s.estado=3 and
												s.id_planta = p.id_item
											order by
												a.nombre asc;");
		
		return $res;
	}

public function listarSeguimientosRemitidosAdministrador ($conexion,$idArea,$subproceso,$asunto, $fechaInicio,$fechaFin, $estado){
	
		$subproceso = $subproceso!="" ? $subproceso : "null";
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											FROM
												g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,$estado) rp,
												g_poa.seguimiento_trimestral s
											WHERE
												rp.identificar_usuario in ( SELECT
																				f.identificador
																			FROM
																				g_estructura.funcionarios f,
																				g_estructura.responsables r
																			WHERE
																				f.id_area=r.id_area and
																				r.id_area = 'CPG') and
																				rp.id_item = s.id_planta and
																				s.estado=3;");
	
				return $res;
	}
	
	public function listarSeguimientosAprobados ($conexion,$idArea,$subproceso,$asunto,$fechaInicio,$fechaFin,$estado){
			
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
	
		$res = $conexion->ejecutarConsulta("SELECT
												p.id_item,s.descripcion, 
												a.descripcion as actividad, 
												p.identificar_usuario,
												i.*
											FROM
												g_poa.mostrar_poa_filtrados($subproceso,$asunto,$fechaInicio,$fechaFin,'4') as p,
												g_poa.seguimiento_trimestral as i,
												g_poa.subprocesos as s,
												g_poa.actividades as a  
											WHERE
												p.id_item=i.id_planta and 
												p.id_subproceso=s.id_subproceso and 
												p.id_actividad=a.id_actividad
												and i.estado=$estado
												and (p.identificar_usuario in (SELECT f.identificador
																FROM g_estructura.funcionarios f,
																     g_estructura.responsables r
																WHERE f.id_area=r.id_area and r.id_area='$idArea'))
											group by 
												id_item, 
												s.descripcion,
												a.descripcion,
												p.identificar_usuario, 
												i.id_seguimiento,
												i.porcentaje_avance,
												i.estado
											order by p.id_item;");
				return $res;
	}
	
	public function sacarReporteActividadesSeguimiento($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades){
		$areaDireccion = $areaDireccion!="" ? "'" . $areaDireccion . "'" : "null";
		$objetivo = $objetivo!="" ? "'" . $objetivo . "'" : "null";
		$proceso = $proceso!="" ? "'" . $proceso . "'" : "null";
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$componente = $componente!="" ? "'" . $componente . "'" : "null";
		$actividades = $actividades!="" ? "'" . $actividades . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_matriz_seguimiento_trimestral($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades);");
		return $res;
	}
	
	
	public function sacarReporteActividadesSinSeguimiento($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades){
		$areaDireccion = $areaDireccion!="" ? "'" . $areaDireccion . "'" : "null";
		$objetivo = $objetivo!="" ? "'" . $objetivo . "'" : "null";
		$proceso = $proceso!="" ? "'" . $proceso . "'" : "null";
		$subproceso = $subproceso!="" ? "'" . $subproceso . "'" : "null";
		$componente = $componente!="" ? "'" . $componente . "'" : "null";
		$actividades = $actividades!="" ? "'" . $actividades . "'" : "null";
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_matriz_sin_seguimiento($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades);");
		return $res;
	}
	
	
	public function areaPadreUsuario($conexion,$usuario){
		$res = $conexion->ejecutarConsulta("select 
												a.id_area_padre as id_area
											from 
												g_estructura.area as a,
												g_estructura.funcionarios as f
											where  
												a.id_area = f.id_area 
												and f.identificador = '$usuario'");
				
		return $res;
	}
	
	public function listarAreasHijas ($conexion, $area){
	
		$res = $conexion->ejecutarConsulta("select 
												a.id_area
											from 
												g_estructura.area as a
											where  
												a.id_area_padre = '$area';");
				
		return $res;
	}
}