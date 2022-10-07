<?php

class ControladorPOA{


	public function listarObjetivosEstrategicos ($conexion){
				
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.objetivos_estrategicos oe
											
											order by
												oe.descripcion;");
		return $res;
	}
	
	
	public function listarProcesos ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.procesos p
						
											order by
												p.descripcion;");
		return $res;
	}
	
	public function listarSubprocesos ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.subprocesos sp
	
											order by
												sp.descripcion;");
		return $res;
	}
	
	public function listarIndicadores ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.indicadores i
	
											order by
												i.descripcion;");
		return $res;
	}
	

	public function listarComponentes($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.componentes c
				;");
		return $res;
	}
	
	public function listarActividades($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.actividades a;");
		return $res;
	}

    public function listarPoblacionObjetivo($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				distinct(descripcion_poblacion_objetivo) as poblacion
		from 
				g_poa.matriz_poa order by descripcion_poblacion_objetivo;");
		return $res;
	}
	
	public function listarResponsable($conexion){
	
		$res = $conexion->ejecutarConsulta("select
                distinct(responsable_subproceso) as responsable
		from
				 g_poa.matriz_poa order by responsable_subproceso;");
		return $res;
	}
	
	public function listarMediosVerificacion($conexion){
	
		$res = $conexion->ejecutarConsulta("select
			distinct(medios_verificacion) as medios
		from 
				g_poa.matriz_poa order by medios_verificacion;");
		return $res;
	}
	
	public function obtenerDatosMatriz ($conexion,$id_item){
		$res = $conexion->ejecutarConsulta(" select *
	from g_poa.matriz_poa
	where id_item_poa='$id_item';");
		return $res;
		
	}
	
	public function sacarReporteMatrizPresupuesto($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin,$item,$gasto){
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
		
		$res = $conexion->ejecutarConsulta("select 
						* 
					from 
						g_poa.mostrar_presupuestos($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin,$item,$gasto);");
		return $res;
	}
	
	public function sacarReporteMatrizPOA($conexion,$areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin, $indicador,$cobertura,$poblacion,$responsable,$medios){
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
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.mostrar_matriz_poa($areaDireccion,$objetivo,$proceso,$subproceso,$componente,$actividades,$fechaInicio,$fechaFin,$indicador,$cobertura,$poblacion,$responsable,$medios);");
				return $res;
	}
	
	
	public function obtenerNombreArea($conexion,$usuario){
		$res = $conexion->ejecutarConsulta(" Select 
				          area.nombre,area.id_area
                     from 
				           g_estructura.area, g_estructura.funcionarios
                     where 
				           area.id_area=funcionarios.id_area and funcionarios.identificador='$usuario';");
		return $res;
		
		
	}
	
	public function obtenerDatosPOA ($conexion,$id_item){
	
		$res = $conexion->ejecutarConsulta("SELECT
	objetivos_estrategicos.descripcion as objetivo,procesos.descripcion as proceso,
				subprocesos.descripcion as subproceso,indicadores.descripcion as indicador,componentes.descripcion as componente, actividades.descripcion as actividad,
				planta.meta1,planta.meta2, planta.meta3, planta.meta4, planta.estado, planta.observaciones  
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
	(actividades.descripcion||actividades.id_subproceso) = planta.codigo_actividad AND
	(componentes.descripcion||componentes.id_proceso) = planta.codigo_componente and planta.id_item=$id_item;");
		return $res;
	}
	
	
	
	
	
	public function listarRegistrosPOA ($conexion,$usuario){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_poa.planta p where identificar_usuario='$usuario';");
		return $res;
	}
	
	public function listarItemPresupuestario ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_poa.item_presupuestario
				where estado=1  
				order by codigo;");
		return $res;
	}
	
	public function listarDetalleGasto ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				 distinct(detalle_gasto)
				from g_poa.matriz_presupuesto order by detalle_gasto;");
		return $res;
	}
	

	public function listarNombreResponsable ($conexion,$id_area){
	
		$res = $conexion->ejecutarConsulta("select f.identificador,fe.nombre||' '|| fe.apellido as nombre_apellido
from g_estructura.funcionarios f, g_uath.ficha_empleado fe
where id_area='$id_area' and f.identificador=fe.identificador;");
		return $res;
	}
	
	public function listarPOAAprobadosPlanta($conexion,$estado,$usuario){
	
		$res = $conexion->ejecutarConsulta("select
				distinct(id_item), pr.descripcion as proceso,sp.descripcion as subproceso, a.descripcion as actividad
				from
				g_poa.planta p,g_poa.procesos pr,g_poa.subprocesos sp, g_poa.actividades a
				where
				p.identificar_usuario='$usuario' and
				p.id_proceso=pr.id_proceso and
				p.id_subproceso=sp.id_subproceso and
				p.codigo_actividad=(a.descripcion||a.id_subproceso) and
				p.estado=$estado
				order by id_item;");
		return $res;
	}
	
public function listarRegistrosPOAAprobados($conexion,$estado,$usuario){
	
		$res = $conexion->ejecutarConsulta("select
distinct(id_item), pr.descripcion as proceso,sp.descripcion as subproceso, a.descripcion as actividad
from
g_poa.planta p, g_poa.matriz_presupuesto i,g_poa.procesos pr,g_poa.subprocesos sp, g_poa.actividades a
where
not exists  (select  mp.id_item_poa from g_poa.matriz_poa as mp where mp.id_item_poa=p.id_item) and				
p.identificar_usuario='$usuario' and
p.id_item=i.id_item_planta and
p.id_proceso=pr.id_proceso and
p.id_subproceso=sp.id_subproceso and
p.codigo_actividad=(a.descripcion||a.id_subproceso) and
p.estado=4 and i.estado=$estado;");
		return $res;
}

public function listarRegistrosCerrados($conexion,$estado,$usuario){

	$res = $conexion->ejecutarConsulta("select
distinct(id_item), pr.descripcion as proceso,sp.descripcion as subproceso, a.descripcion as actividad, id_matriz
from
g_poa.planta p, g_poa.matriz_presupuesto i,g_poa.procesos pr,g_poa.subprocesos sp, g_poa.actividades a, g_poa.matriz_poa as mp
where
p.identificar_usuario='$usuario' and	p.id_item=i.id_item_planta and	p.id_proceso=pr.id_proceso and
p.id_subproceso=sp.id_subproceso and	p.codigo_actividad=(a.descripcion||a.id_subproceso) and
p.estado=4 and i.estado=$estado and p.id_item=mp.id_item_poa order by id_item");
	return $res;
}
	
	public function listarRegistrosPOAUSuario ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select  
p.id_item, a.descripcion, p.meta1, p.meta2, p.meta3, p.meta4, s.descripcion as subproceso
from g_poa.planta as p, g_poa.actividades as a, g_poa.subprocesos as s
where ((a.descripcion||a.id_subproceso)=p.codigo_actividad) and p.identificar_usuario='$id'and estado='1' and p.id_subproceso=s.id_subproceso;");
		return $res;
	}
	
	public function listarRegistrosMatrizUSuario ($conexion,$id){
	
		$res = $conexion->ejecutarConsulta("select 
				id_item,sp.descripcion, codigo_actividad,codigo_item, detalle_gasto, (enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre) as total
from 
g_poa.planta as p, g_poa.matriz_presupuesto as i, g_poa.subprocesos as sp
where p.estado=4 and p.id_item=i.id_item_planta
and p.id_subproceso=sp.id_subproceso
and p.identificar_usuario='$id'and i.estado='1'
				order by id_item;");
			return $res;
		}
	
	public function listarPOARemitidos ($conexion,$id,$subproceso,$asunto,$fechaInicio,$fechaFin){
	
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$subproceso = $subproceso!="" ? $subproceso : "null";
		$res = $conexion->ejecutarConsulta("
				select
				*
				from
				g_poa.filtrar_registros_poa($subproceso,$asunto,$fechaInicio,$fechaFin,2)
				where  identificar_usuario in (SELECT f.identificador
	FROM g_estructura.funcionarios f,
	g_estructura.responsables r
	WHERE f.id_area=r.id_area and
	r.identificador='$id' and r.responsable='TRUE');");
		return $res;
	}
	
	public function FiltrarSubProceso($conexion,$id){
		$res = $conexion->ejecutarConsulta("
		select distinct(s.id_subproceso), s.descripcion
		from g_poa.planta as p, g_poa.subprocesos as s
		where p.id_subproceso=s.id_subproceso and p.estado=2 and (p.identificar_usuario in (SELECT f.identificador
	FROM g_estructura.funcionarios f,
	g_estructura.responsables r
	WHERE f.id_area=r.id_area and
	r.identificador='$id' and r.responsable='TRUE')) 
		order by  descripcion;");
				return $res;
	} 
	
	
	public function listarPOAAprobados ($conexion,$idusuario, $identificador,$asunto,$fechaInicio,$fechaFin,$estado){
		
		$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$res = $conexion->ejecutarConsulta("
				select
				p.id_item, a.descripcion, p.meta1, p.meta2, p.meta3, p.meta4, s.descripcion as subproceso
				from
				g_poa.mostrar_poa_filtrados($asunto,$fechaInicio,$fechaFin,$estado) as p, g_poa.actividades as a, g_poa.subprocesos as s
				where ((a.descripcion||a.id_subproceso)=p.codigo_actividad) and p.id_subproceso=s.id_subproceso and (p.identificar_usuario in (SELECT f.identificador
FROM g_estructura.funcionarios f,
g_estructura.responsables r
WHERE f.id_area=r.id_area and r.id_area='$identificador') );");
		return $res;
		
	}
	
	public function listarArea ($conexion){
	
		$res = $conexion->ejecutarConsulta("select
				*
				from
				g_estructura.area;");
		return $res;
	}
	
	

	public function listarMatrizRemitida ($conexion,$id,$subproceso,$asunto,$fechaInicio,$fechaFin){
	
		$res = $conexion->ejecutarConsulta("select
				p.id_item,s.descripcion, p.descripcion as actividad, p.identificar_usuario,sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)) as total, count(i.codigo_item)
				from
				g_poa.filtrar_registros_poa(null,null,null,null,4) as p, g_poa.matriz_presupuesto as i,g_poa.subprocesos as s
				where p.id_item=i.id_item_planta and p.id_subproceso=s.id_subproceso 
				and i.estado=2
				and (p.identificar_usuario in (SELECT f.identificador
				FROM g_estructura.funcionarios f,
				g_estructura.responsables r
				WHERE f.id_area=r.id_area and
				r.identificador='$id' and r.responsable='TRUE') )
				group by id_item, s.descripcion, p.descripcion,p.identificar_usuario
				order by p.id_item;");
			return $res;
		}
	
		
public function listarMatrizAprobada ($conexion,$idusuario,$identificador,$asunto,$fechaInicio,$fechaFin,$estado){
			
			$asunto = $asunto!="" ? "'" . $asunto . "'" : "null";
			$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
			$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
			$estado = $estado!="" ? $estado : "null";
		
			$res = $conexion->ejecutarConsulta("select
p.id_item,s.descripcion, a.descripcion as actividad, p.identificar_usuario,sum((enero+febrero+marzo+abril+mayo+junio+julio+agosto+septiembre+octubre+noviembre+diciembre)) as total, count(i.codigo_item)
from
g_poa.mostrar_poa_filtrados($asunto,$fechaInicio,$fechaFin,'4') as p,g_poa.matriz_presupuesto as i,g_poa.subprocesos as s,g_poa.actividades as a  
where p.id_item=i.id_item_planta and p.id_subproceso=s.id_subproceso and (p.codigo_actividad=(a.descripcion||a.id_subproceso))
					and i.estado='$estado'
 and (p.identificar_usuario in (SELECT f.identificador
                                FROM g_estructura.funcionarios f,
                                     g_estructura.responsables r
                                WHERE f.id_area=r.id_area and r.id_area='$identificador'))
group by id_item, s.descripcion,a.descripcion,p.identificar_usuario
order by p.id_item;");
			return $res;
		}
		

	public function actualizarPlanta($conexion, $idusuario, $id,$valor){
	
		$res = $conexion->ejecutarConsulta("update g_poa.planta 
                                             set estado='$valor'
                                             where estado=1
				                             and identificar_usuario='$idusuario'
				                             and id_item=$id;");
						return $res;
	}

	public function actualizarEstado($conexion, $idusuario, $id,$valor,$observacion){
	
		$res = $conexion->ejecutarConsulta("update g_poa.planta
				set estado='$valor',
				observaciones='$observacion',
				id_coordinador='$idusuario'
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
	
	public function actualizarEstadoMatrizPresupuesto($conexion, $codigo_item, $id_item_planta,$valor,$observacion, $coodinador){
	
		$res = $conexion->ejecutarConsulta("update g_poa.matriz_presupuesto
				set estado='$valor',
				observaciones='$observacion',
				id_coordinador='$coodinador'
				where codigo_item='$codigo_item'
				and id_item_planta='$id_item_planta';");
		return $res;
	}
	
	public function aprobarEstadoMatrizPresupuesto($conexion, $id_item_planta,$valor, $coodinador, $opcion){
		
		switch ($opcion){
			case 'coordinador': $consulta = 'estado=2'; break;
			case 'planta': $consulta = 'estado=3 '; break;
		}
	
		$res = $conexion->ejecutarConsulta("update g_poa.matriz_presupuesto
				set estado='$valor',
				id_coordinador='$coodinador'
				where id_item_planta='$id_item_planta'
						and ".$consulta.";");
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
	
	
	public function obtenerSubprocesoXProceso ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
				 	g_poa.procesos.id_proceso, g_poa.procesos.descripcion as descripcion_proceso, g_poa.subprocesos.id_subproceso, g_poa.subprocesos.descripcion as descripcion_subproceso 
				FROM 
					g_poa.procesos, g_poa.subprocesos
				WHERE
				 g_poa.procesos.id_proceso=g_poa.subprocesos.id_proceso
				order by
				 g_poa.procesos.id_proceso");
		return $res;
	}
	
	public function obtenerComponenteXProceso ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
				g_poa.procesos.id_proceso, g_poa.procesos.descripcion, g_poa.componentes.id_proceso as codigo, g_poa.componentes.descripcion as componente 
			FROM
				 g_poa.procesos, g_poa.componentes
			WHERE
				 g_poa.procesos.id_proceso=g_poa.componentes.id_proceso
			order by
				 g_poa.procesos.id_proceso");
		return $res;
	}
	
	public function obtenerActividadesXSubProceso ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
      g_poa.subprocesos.id_subproceso, g_poa.subprocesos.descripcion as sub_proceso, g_poa.actividades.descripcion as descripcion_actividad 
FROM 
     g_poa.subprocesos, g_poa.actividades
WHERE
      g_poa.subprocesos.id_subproceso=g_poa.actividades.id_subproceso
order by
      g_poa.actividades.id_subproceso");
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
				codigo_item, id_item_planta,detalle_gasto,estado, (enero+ febrero+ marzo+ abril+mayo+junio+julio+agosto+ septiembre+ octubre+ noviembre+ diciembre) as total
				from
				g_poa.matriz_presupuesto
				where
				id_item_planta=$id;");
		return $res;
	}
	
	public function desplegarItemsPresupuestarios($conexion,$id,$estado){
	
		$res = $conexion->ejecutarConsulta("select
				codigo_item, id_item_planta,detalle_gasto, (enero+ febrero+ marzo+ abril+mayo+junio+julio+agosto+ septiembre+ octubre+ noviembre+ diciembre) as total
				from
				g_poa.matriz_presupuesto
				where
				estado=$estado  and
				id_item_planta=$id;");
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
	
	public function actualizarIndicador ($conexion,$id,$descripcion){
	
		$res = $conexion->ejecutarConsulta("update
				g_poa.indicadores
				set
				descripcion='$descripcion'
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
	
	public function actualizaritemPresupuestario ($conexion,$id,$descripcion){
	
		$res = $conexion->ejecutarConsulta("update
				g_poa.item_presupuestario
				set
				descripcion='$descripcion'
				where
				codigo='$id';");
				return $res;
	}
	
	public function nuevoObjetivo ($conexion,$descripcion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.objetivos_estrategicos(
            									descripcion,
												fecha_creacion
												)
										    VALUES ('".$descripcion."',
													now());");
				return $res;
	}
	
	public function nuevoProceso ($conexion,$descripcion,$esProceso){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.procesos(
            									descripcion,
				                                proyecto,
												fecha_creacion
												)
										    VALUES ('".$descripcion."',
				                                    ".$esProceso. ",
													now());");
		return $res;
	}
	
	public function nuevoComponente ($conexion,$id,$descripcion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.componentes(
            									descripcion,
												id_proceso,
				                                fecha_creacion
												)
										    VALUES ('".$descripcion."',
													".$id.",now());");
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
	
	public function eliminarItemPresupuesto ($conexion,$id_planta,$codigo_item){
	
		$res = $conexion->ejecutarConsulta("delete
				from
				g_poa.matriz_presupuesto
				where
				id_item_planta=$id_planta and
				codigo_item='".$codigo_item."';");
						return $res;
	}
	
	public function eliminarActividadSubproceso ($conexion,$id, $texto){
	
		$res = $conexion->ejecutarConsulta("delete
				from
				g_poa.actividades
				where
				id_subproceso=$id and
				descripcion='".$texto."';");
						return $res;
	}
	
	public function nuevoSubproceso ($conexion,$descripcion,$id){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.subprocesos(
            									descripcion,
				                                id_proceso,
												fecha_creacion
												)
										    VALUES ('".$descripcion."',
				                                      ".$id.",
													now());");
		return $res;
	}
	
	
	public function nuevaActividad ($conexion,$id,$descripcion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.actividades(
            									descripcion,
												id_subproceso,
				                                fecha_creacion
												)
										    VALUES ('".$descripcion."',
													".$id.",now());");
		return $res;
	}
	
	
	
	
	public function nuevoIndicador ($conexion,$descripcion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.indicadores(
            									descripcion,
												fecha_creacion
												)
										    VALUES ('".$descripcion."',
													now());");
		return $res;
	}
	
	public function nuevaPlanta ($conexion,$id_estrategico, $id_proceso, $id_subproceso, $id_componentes, $id_actividades, $id_listaIndicadores, $meta1, $meta2,$meta3,$meta4, $usuario){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.planta(
            id_objetivo, id_proceso, id_subproceso,
				 codigo_componente, 
            codigo_actividad, id_indicadores,  meta1, meta2, meta3, meta4, fecha_creacion,identificar_usuario, estado)
				 VALUES (".$id_estrategico.",".$id_proceso.",".$id_subproceso.
				",'".$id_componentes."','".$id_actividades."',".$id_listaIndicadores.",".$meta1.",".$meta2.",".$meta3.",".$meta4.",now(),'".$usuario."',1);");
		return $res;
	}
	
	
	
	
	
	public function nuevaRegistroMatrizPresupuesto($conexion, $codigo_item, $detalle_gasto,$id_item_planta,
			$enero,$febrero,$marzo,	$abril,$mayo,$junio,$julio,$agosto,$septiembre,$octubre, $noviembre, $diciembre)
	{
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.matriz_presupuesto(
            codigo_item, detalle_gasto, id_item_planta, enero, febrero, 
            marzo, abril, mayo, junio, julio, agosto, septiembre, octubre, 
            noviembre, diciembre,estado,fecha_creacion)
				 VALUES ('".$codigo_item."','".$detalle_gasto."',".$id_item_planta.",".$enero.",".$febrero.",".$marzo.",".$abril.
				",".$mayo.",".$junio.",".$julio.",".$agosto.",".$septiembre.",".$octubre.",".$noviembre.",".$diciembre.",1,now());");
		return $res;
	
	
	}
	
	
	
	
	public function nuevoRegistroMatriz ($conexion,$id_item,$idDivision,
			$programacion1,$programacion2,$programacion3,$programacion4,
			$coberturaTerritorial,$beneficiados,$descipcionPoblacion,$responsable,$mediosVerificacion)
	{
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_poa.matriz_poa(
             id_item_poa, division, programacion1, 
            programacion2, programacion3, programacion4, cobertura, numero_beneficiados, 
            descripcion_poblacion_objetivo, responsable_subproceso, medios_verificacion,fecha_creacion)
				 VALUES (".$id_item.",'".$idDivision."','".$programacion1."','".$programacion2."','".$programacion3."','".$programacion4.
				"','".$coberturaTerritorial."',".$beneficiados.",'".$descipcionPoblacion."','".$responsable."','".$mediosVerificacion."',now());");
		return $res;
		
	
	}
	
	
	
	
	
	
}