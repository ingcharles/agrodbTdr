<?php 
class ControladorEtiquetas{
	
	public function autogenerarNumeroSolicitudEtiquetasOrnamentales($conexion, $identificadorOperador,$anioActual){
		$parametros=array($identificadorOperador,$anioActual);
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(e.secuencial)::numeric + 1 as numero
											FROM
												g_etiquetas.etiquetas e
											WHERE
												e.identificador_operador=$1 and
												e.anio=$2 ;",$parametros);
		
		if(pg_fetch_result($res, 0, 'numero') == '')
			$res = 1;
		else
			$res = pg_fetch_result($res, 0, 'numero');
		
		return $res;
	}
	
	public function guardarNuevaSolicitudEtiquetas($conexion, $numeroSolicitud,$anio,$secuencial,$identificadorOperador,$nombreOperador,$idProvincia,$nombreProvincia,$saldoEtiqueta,$estado){
		$parametros=array($numeroSolicitud,$anio,$secuencial,$identificadorOperador,$nombreOperador,$idProvincia,$nombreProvincia,$saldoEtiqueta,$estado);
		$res = $conexion->ejecutarConsulta("INSERT INTO g_etiquetas.etiquetas(
												numero_solicitud, anio, secuencial, identificador_operador,
												nombre_operador, id_provincia, nombre_provincia, saldo_etiqueta, fecha_registro,estado,total_etiqueta)
											VALUES 
												($1,$2,$3,$4,$5,$6,$7,$8 ,now(),$9,$8)
											RETURNING 
												id_etiqueta ;",$parametros);
		return $res;
	}
	
	public function listarSolicitudesEtiquetas($conexion,$identificadorOperador,$numeroSolicitud,$estado,$fecha){
		$numeroSolicitud  = $numeroSolicitud!="" ?  $numeroSolicitud  : "NULL";
		$estado  = $estado!="" ? "%" . $estado .  "%" : "NULL";
		$fecha  = $fecha!="" ?  $fecha : "NULL";
		
		$busqueda="";
		if(($numeroSolicitud=="NULL") && ($estado=="NULL") && ($fecha=="NULL") ){
			$busqueda = " and estado='Aprobado'";
		}

		$parametros=array($identificadorOperador,$numeroSolicitud,$estado,$fecha);
		$res = $conexion->ejecutarConsulta("SELECT 
												id_etiqueta,
												numero_solicitud, 
       											case when estado = 'Por Pagar' then 0 when estado='Enviado' then 0 else saldo_etiqueta end saldo_etiqueta,
												estado
 											FROM 
												g_etiquetas.etiquetas 
											WHERE  
												identificador_operador= $1 and 
												($2 = 'NULL' or numero_solicitud = $2) and
												($3 = 'NULL' or estado ilike $3 ) and
												($4 = 'NULL' or to_char(fecha_registro,'DD/MM/YYYY') = $4 )
												".$busqueda."
											ORDER BY 1 DESC ;",$parametros);
		return $res;
	}
	
	public function listarSolicitudesEtiquetasPorEstado ($conexion, $estado,$provincia){
		$parametros=array($estado,$provincia);
		$res = $conexion->ejecutarConsulta("SELECT 
												id_etiqueta id_solicitud, 
												numero_solicitud, 
												identificador_operador,			
        										fecha_registro, estado
 											FROM 
												g_etiquetas.etiquetas 
											WHERE 
												estado= $1 and 
												nombre_provincia ilike $2
											ORDER BY 1 DESC",$parametros);
				return $res;
	}
	
	
	public function abrirSolicitudEtiquetasEnviada ($conexion, $idEtiqueta){
		$parametros=array($idEtiqueta);
		$cid = $conexion->ejecutarConsulta("SELECT
												o.identificador,
												o.razon_social,
												o.direccion,
												o.telefono_uno,
												o.correo,
												e.saldo_etiqueta,
												e.estado,
												o.nombre_representante,
												o.apellido_representante,
												e.numero_solicitud
											FROM 
												g_etiquetas.etiquetas e, 
												g_operadores.operadores o
											WHERE 
												id_etiqueta=$1 and
												o.identificador=e.identificador_operador FOR UPDATE;
											",$parametros);
	
				while ($fila = pg_fetch_assoc($cid)){
					$res[] = array( identificador=>$fila['identificador'],
									razonSocial=>$fila['razon_social'],
									direccion=>$fila['direccion'],
									telefono=>$fila['telefono_uno'],
									correo=>$fila['correo'],
									cantidadEtiqueta=>$fila['saldo_etiqueta'],
									estado=>$fila['estado'],
									numeroSolicitud=>$fila['numero_solicitud'],
									nombreRepresentante=>$fila['nombre_representante'],
									apellidoRepresentante=>$fila['apellido_representante']);
				}
	
				return $res;
		}
		
		public function buscarOrdenPagoPorTipoYidSolicitud($conexion,$tipoSolicitud,$idSolicitud,$identificador){
			$parametros=array($tipoSolicitud,$idSolicitud,$identificador);
			$res = $conexion->ejecutarConsulta("SELECT
													total_pagar
												FROM 
													g_financiero.orden_pago 
												WHERE 
													tipo_solicitud=$1 and
													id_solicitud=$2 and
													identificador_operador=$3
												;",$parametros);
			return $res;
		}
		
		public function buscarSitiosOperadoresPorCodigoyAreaOperacion($conexion,$identificador,$codigoOperacion,$areaOperacion,$idSitio=null){
			$campos="";
			$busqueda="";
		
			if($idSitio==null){
				$campos=" si.id_sitio, si.codigo_provincia||''||si.codigo codigo_sitio,	si.nombre_lugar nombre_sitio,
				si.provincia, si.canton, si.parroquia, si.direccion";
			}else{
				$campos=" ar.id_area, ar.codigo||''||ar.secuencial codigo_area,	ar.nombre_area ";
				$busqueda=" si.id_sitio=$idSitio and ";
			}
			$parametros=array($identificador,$codigoOperacion,$areaOperacion);
			$res = $conexion->ejecutarConsulta("SELECT DISTINCT
													".$campos."
												FROM 
													g_operadores.operadores opv
													,g_operadores.operaciones op
													,g_catalogos.tipos_operacion t
													,g_catalogos.productos p
													,g_catalogos.subtipo_productos stp
													,g_catalogos.tipo_productos tp
													,g_catalogos.tipos_operacion top
													,g_catalogos.areas_operacion ao
													,g_operadores.productos_areas_operacion pao
													,g_operadores.sitios si
													,g_operadores.areas ar 
												WHERE 	
													opv.identificador = op.identificador_operador and 
													op.id_tipo_operacion = t.id_tipo_operacion and 
													t.codigo = ANY ($2) and 
													t.id_area = ANY ($3) and 
													op.estado in ('registrado','registradoObservacion') and 
													p.id_producto=op.id_producto and 
													p.id_subtipo_producto=stp.id_subtipo_producto and 
													stp.id_tipo_producto=tp.id_tipo_producto and 
													top.id_tipo_operacion=op.id_tipo_operacion and 
													ao.id_tipo_operacion=op.id_tipo_operacion and 
													si.identificador_operador=opv.identificador and 
													ar.id_sitio=si.id_sitio and 
													pao.id_operacion=op.id_operacion and 
													pao.id_area=ar.id_area and 
													tp.nombre='Flores y follajes cortados' and 
													".$busqueda."
													op.identificador_operador=$1
												ORDER BY 1 ASC;",$parametros);
			
			
			return $res;
		}

		
		public function eliminarSolicitudEtiqueta($conexion,$idSolicitudEtiqueta){
			$parametros=array($idSolicitudEtiqueta);
			$res = $conexion->ejecutarConsulta("DELETE 
												FROM 
													g_etiquetas.etiquetas
 												WHERE 
													id_etiqueta=$1;",$parametros);
			
			return $res;
		}
		
		public function guardarEtiquetasDetalle($conexion,$idSolicitud,$cantidadEtiqueta,$estado,$idEtiquetaSitio){
			$parametros=array($idSolicitud,$cantidadEtiqueta,$estado,$idEtiquetaSitio);
			$res = $conexion->ejecutarConsulta("INSERT INTO g_etiquetas.etiquetas_detalle(
		 											id_etiqueta,  fecha_registro,cantidad_etiqueta,estado,id_etiqueta_sitio)
												VALUES (
													$1, now(), $2,$3,$4)
												RETURNING id_etiqueta_detalle
												;",$parametros);
			return $res;
		}
		
		public function guardarEtiquetasImpresas($conexion,$idEtiquetaDetalle,$numeroEtiqueta){
			$parametros=array($idEtiquetaDetalle,$numeroEtiqueta);
			$res = $conexion->ejecutarConsulta("INSERT INTO g_etiquetas.etiquetas_impresas(
             										id_etiqueta_detalle, numero_etiqueta)
   												VALUES (
													$1, $2) 
												RETURNING id_etiqueta_impresa
												;",$parametros);
			return $res;
		}
		
		public function actualizarDatosSolicitudEtiqueta($conexion,$campo,$valor,$idEtiqueta){
			$parametros=array($valor,$idEtiqueta);
			$res = $conexion->ejecutarConsulta("UPDATE 
													g_etiquetas.etiquetas
												SET 
													$campo=$1
												WHERE
													id_etiqueta=$2
												;",$parametros);
			return $res;
		}
		
		public function autogenerarNumeroEtiquetasOrnamentales($conexion){
			$res = $conexion->ejecutarConsulta("SELECT
													max(numero_etiqueta)::numeric + 1 as numero
												FROM
													g_etiquetas.etiquetas_impresas
												;");
		
			if(pg_fetch_result($res, 0, 'numero') == '')
				$res = 1;
			else
				$res = pg_fetch_result($res, 0, 'numero');
		
			return $res;
		}
		
		public function obtenerEtiquetaImprimir($conexion,$idEtiquetaDetalle){
			$parametros=array($idEtiquetaDetalle);
			$res = $conexion->ejecutarConsulta("SELECT 
													ei.id_etiqueta_impresa,
													e.identificador_operador ||'.'|| es.codigo_sitio ||''|| es.codigo_area identificador_operador,
													op.razon_social,
													to_char(e.fecha_aprobacion,'YYYY/MM/DD HH24:MI') fecha_aprobacion,
													ei.numero_etiqueta,
													to_char(ed.fecha_registro,'YYYY/MM/DD HH24:MI') fecha_registro
  												FROM 
													g_etiquetas.etiquetas e,
													g_etiquetas.etiquetas_detalle ed,
													g_etiquetas.etiquetas_impresas ei,
													g_operadores.operadores op,
													g_etiquetas.etiquetas_sitios es
  												WHERE 
													e.id_etiqueta=ed.id_etiqueta and 
													ed.id_etiqueta_detalle=ei.id_etiqueta_detalle and 
													e.identificador_operador=op.identificador and 
													es.id_etiqueta=e.id_etiqueta and 
													es.id_etiqueta_sitio=ed.id_etiqueta_sitio and  
													ei.id_etiqueta_detalle=$1
												;",$parametros);
			return $res;
		}
		
		public function imprimirReporteEtiquetas($conexion, $estado,$fechaInicio, $fechaFin,$identificacionOperador){
		
		
		
			if ($estado=="todos")
				$estado = "";
		
	
			$estado = $estado!="" ? $estado   : "NULL";
			$fechaInicio = $fechaInicio!="" ?  $fechaInicio   : "NULL";
		
			if($fechaFin!=""){
				$fechaFin = str_replace("/","-",$fechaFin);
				$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
				$fechaFin=date('d-m-Y',$fechaFin);
				
			}else{
				$fechaFin="NULL";
			}
			$parametros=array($identificacionOperador,$estado,$fechaInicio,$fechaFin);
			$res = $conexion->ejecutarConsulta("SELECT
													et.numero_solicitud,
													si.nombre_lugar nombre_sitio,
													to_char(det.fecha_registro,'YYYY/MM/DD HH24:MI') fecha_registro_detalle,
													op.identificador identificador_operador,
													op.razon_social,
													et.saldo_etiqueta saldo_etiqueta,
													det.cantidad_etiqueta,
													(SELECT 
														RTRIM(array_to_string(array_agg(eti.numero_etiqueta), ', '),', ') as etiqueta_detalle
													FROM
														g_etiquetas.etiquetas_impresas eti
													WHERE
														det.id_etiqueta_detalle = eti.id_etiqueta_detalle),
													det.estado
												FROM
												    g_etiquetas.etiquetas et , g_etiquetas.etiquetas_detalle det, g_etiquetas.etiquetas_sitios es, g_operadores.sitios si, g_operadores.operadores op
												WHERE
													et.id_etiqueta=det.id_etiqueta	and
													et.id_etiqueta=es.id_etiqueta and
													det.id_etiqueta_sitio=es.id_etiqueta_sitio and
													si.id_sitio=es.id_sitio and
													op.identificador=et.identificador_operador and
													op.identificador=$1 and
													($2 = 'NULL' or det.estado = $2 ) and
													($3 = 'NULL' or det.fecha_registro >=$3::timestamp without time zone) and
													($4 = 'NULL' or det.fecha_registro <=$4::timestamp without time zone )
												ORDER BY 3; ",$parametros);
			return $res;
		}
		
		
		public function obtenerImagenEtiqueta($conexion,$numero,$categoria){
			$parametros=array($numero,$categoria);
			$res = $conexion->ejecutarConsulta("SELECT
													ruta
												FROM 
													g_etiquetas.etiquetas_imagen
												WHERE 
													numero_categoria_fecha=$1 and 
													categoria_fecha=$2
												;",$parametros);
			return $res;
		}
		
		public function guardarNuevaSolicitudEtiquetasSitios($conexion, $idEtiqueta,$idSitio,$codigoSitio,$idArea,$codigoArea,$totalEtiqueta){
			$parametros=array($idEtiqueta,$idSitio,$codigoSitio,$idArea,$codigoArea,$totalEtiqueta);
			$res = $conexion->ejecutarConsulta("INSERT INTO g_etiquetas.etiquetas_sitios(
            										id_etiqueta, id_sitio, codigo_sitio,id_area, codigo_area, total_etiqueta, saldo_etiqueta)
    											VALUES ($1,$2,$3,$4,$5,$6,$6);",$parametros);
			return $res;
		}
		
		public function obtenerSolicitudesEtiquetasSitios($conexion, $idEtiqueta){
			$parametros=array($idEtiqueta);
			$res = $conexion->ejecutarConsulta("SELECT 
													et.numero_solicitud,
													si.id_sitio,
													si.nombre_lugar nombre_sitio,
													ar.id_area,
													ar.nombre_area,
													es.saldo_etiqueta
												FROM 
													g_etiquetas.etiquetas et, 
													g_etiquetas.etiquetas_sitios es, 
													g_operadores.sitios si,
													g_operadores.areas ar
												WHERE
													et.id_etiqueta=$1 and 
													et.id_etiqueta=es.id_etiqueta and 
													es.id_sitio=si.id_sitio and 
													es.id_area=ar.id_area and 
													es.saldo_etiqueta>0
												ORDER BY 3 ;",$parametros);
					return $res;
		}
		
		public function obtenerSolicitudesEtiquetasXSitio($conexion, $idEtiqueta, $idSitio, $idArea){
			$parametros=array($idEtiqueta,$idArea,$idSitio);
			$res = $conexion->ejecutarConsulta("SELECT 
													et.saldo_etiqueta,
													et.numero_solicitud,
													es.id_etiqueta_sitio,
													si.id_sitio,
													si.nombre_lugar nombre_sitio,
													es.saldo_etiqueta saldo_etiqueta_sitio
												FROM 
													g_etiquetas.etiquetas et, 
													g_etiquetas.etiquetas_sitios es, 
													g_operadores.sitios si 
												WHERE 
													et.id_etiqueta=$1 and 
													et.id_etiqueta=es.id_etiqueta and 
													es.id_sitio=si.id_sitio and
													es.id_area=$2 and 
													si.id_sitio=$3 ;",$parametros);
			return $res;
		}
		
		public function obtenerSolicitudesEtiquetasXEtiquetaSitio($conexion, $idEtiqueta, $idEtiquetaSitio){
			$parametros=array($idEtiqueta,$idEtiquetaSitio);
			$res = $conexion->ejecutarConsulta("SELECT
													et.saldo_etiqueta,
													et.numero_solicitud,
													es.id_etiqueta_sitio,
													si.id_sitio,
													si.nombre_lugar nombre_sitio,
													es.saldo_etiqueta saldo_etiqueta_sitio
												FROM
													g_etiquetas.etiquetas et,
													g_etiquetas.etiquetas_sitios es,
													g_operadores.sitios si
												WHERE
													et.id_etiqueta=$1 and
													et.id_etiqueta=es.id_etiqueta and
													es.id_sitio=si.id_sitio and
													es.id_etiqueta_sitio=$2 FOR UPDATE ",$parametros);
			return $res;
		}
		
		public function actualizarSaldoEtiquetaSitio($conexion,$campo,$valor,$idEtiqueta){
			$parametros=array($valor,$idEtiqueta);
			$res = $conexion->ejecutarConsulta("UPDATE
													g_etiquetas.etiquetas_sitios
												SET
													$campo=$1
												WHERE
													id_etiqueta_sitio=$2
												;",$parametros);
			return $res;
		}
		
		public function listarEtiquetasAprobadas($conexion){
	
			$res = $conexion->ejecutarConsulta("SELECT et.id_etiqueta, et.numero_solicitud, et.anio, et.secuencial, et.identificador_operador, 
											       nombre_operador, id_provincia, nombre_provincia, et.saldo_etiqueta, 
											       fecha_registro, estado, fecha_aprobacion, et.total_etiqueta, es.codigo_sitio
											 	 FROM 
													g_etiquetas.etiquetas et full join g_etiquetas.etiquetas_sitios es on  et.id_etiqueta=es.id_etiqueta 
												WHERE 
													estado in ('Aprobado')
													and et.saldo_etiqueta!=0 and es.codigo_sitio is null --and et.id_etiqueta<4000 
											  order by
													 es.codigo_sitio;");
					return $res;
		}
		
		public function listarSitioAreaOperadorEtiqueta($conexion, $identificador, $idEtiqueta){
			
			$res = $conexion->ejecutarConsulta("SELECT DISTINCT
													si.id_sitio,si.codigo_provincia||''||si.codigo codigo_sitio ,si.nombre_lugar nombre_sitio, ar.id_area,ar.codigo||''||ar.secuencial codigo_area,ar.nombre_area
												FROM 
													g_operadores.operadores opv
													,g_operadores.operaciones op
													,g_catalogos.tipos_operacion t
													,g_catalogos.productos p
													,g_catalogos.subtipo_productos stp
													,g_catalogos.tipo_productos tp
													,g_catalogos.tipos_operacion top
													,g_catalogos.areas_operacion ao
													,g_operadores.productos_areas_operacion pao
													,g_operadores.sitios si
													,g_operadores.areas ar 
													, g_etiquetas.etiquetas et
												WHERE 	
													opv.identificador = op.identificador_operador and 
													op.id_tipo_operacion = t.id_tipo_operacion and 
													t.codigo IN ('ACO','COM') and 
													t.id_area IN ('SV') and 
													p.id_producto=op.id_producto and 
													p.id_subtipo_producto=stp.id_subtipo_producto and 
													stp.id_tipo_producto=tp.id_tipo_producto and 
													top.id_tipo_operacion=op.id_tipo_operacion and 
													ao.id_tipo_operacion=op.id_tipo_operacion and 
													si.identificador_operador=opv.identificador and 
													ar.id_sitio=si.id_sitio and 
													pao.id_operacion=op.id_operacion and 
													pao.id_area=ar.id_area and 
													tp.nombre='Flores y follajes cortados' and 
													et.identificador_operador=opv.identificador and
													
													et.id_etiqueta='$idEtiqueta' and 
													op.identificador_operador='$identificador'
												ORDER BY 1 DESC
												 LIMIT 1;");
			return $res;
		}
		
		public function guardarSitioAreas($conexion,$idEtiqueta, $idSitio,$codigoSitio,$idArea,$codigoArea,$totalEtiqueta,$saldoEtiqueta){
			$res = $conexion->ejecutarConsulta("INSERT INTO g_etiquetas.etiquetas_sitios(
													id_etiqueta, id_sitio, codigo_sitio, id_area,codigo_area, total_etiqueta, saldo_etiqueta)
												VALUES ('$idEtiqueta', '$idSitio', '$codigoSitio', '$idArea','$codigoArea','$totalEtiqueta','$saldoEtiqueta');");
			return $res;
		}
		
		
	
		
}