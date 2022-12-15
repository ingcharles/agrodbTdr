<?php

class ControladorRegistroOperador{
	
	function reemplazarCaracteres($cadena){
		$cadena = str_replace('á', 'a', $cadena);
		$cadena = str_replace('é', 'e', $cadena);
		$cadena = str_replace('í', 'i', $cadena);
		$cadena = str_replace('ó', 'o', $cadena);
		$cadena = str_replace('ú', 'u', $cadena);
		$cadena = str_replace('ñ', 'n', $cadena);
	
		$cadena = str_replace('Á', 'A', $cadena);
		$cadena = str_replace('É', 'E', $cadena);
		$cadena = str_replace('Í', 'I', $cadena);
		$cadena = str_replace('Ó', 'O', $cadena);
		$cadena = str_replace('Ú', 'U', $cadena);
		$cadena = str_replace('Ñ', 'N', $cadena);
	
		return $cadena;
	}
	
	public function actualizarDatosOperador($conexion, $identificador,$razon,$nombreLegal,$apellidoLegal,$nombreTecnico,$apellidoTecnico,$provincia,$canton,
				$parroquia,$direccion,$telefono1,$telefono2,$celular1,$celular2,$fax,$correo,$registroOrquideas,$registroMadera, $tipoActividad = ''){
		$res = $conexion->ejecutarConsulta("update
												g_operadores.operadores
											set
												razon_social='$razon',
												nombre_representante='$nombreLegal',
												apellido_representante='$apellidoLegal',
												nombre_tecnico='$nombreTecnico',
												apellido_tecnico='$apellidoTecnico',
												direccion='$direccion',
												provincia='$provincia',
												canton='$canton',
												parroquia='$parroquia',
												telefono_uno='$telefono1',
												telefono_dos='$telefono2',
												celular_uno='$celular1',
												celular_dos='$celular2',
												fax='$fax',
												correo='$correo',
												registro_orquideas='$registroOrquideas',
												registro_madera='$registroMadera',
												tipo_actividad = '$tipoActividad'
											where
												identificador='$identificador';");
		return $res;
	}
	
	
	public function guardarRegistroOperador ($conexion, $clasificacion, $ruc,$razon,$nombreLegal,$apellidoLegal,$nombreTecnico,$apellidoTecnico,
											$provincia,$canton,$parroquia,$direccion,$telefono1,$telefono2,$celular1,$celular2,$fax,$correo,$clave, $idVue=null){
												
	switch($clasificacion){
			
			case "Cédula":
			case "Pasaporte":
			case "Refugiado":
				$tipoActividad = 'individual';
			break;
			case "operadorOrganico":
				$tipoActividad = 'grupal';
			break;
			default:
				$tipoActividad = null;
		}
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_operadores.operadores (tipo_operador, identificador, razon_social, nombre_representante, apellido_representante, 
									            nombre_tecnico, apellido_tecnico, provincia, canton, parroquia, direccion, telefono_uno, telefono_dos, 
									            celular_uno, celular_dos, fax, correo, clave, tipo_actividad)
											VALUES
												('$clasificacion','$ruc','$razon','$nombreLegal','$apellidoLegal','$nombreTecnico','$apellidoTecnico',
												'$provincia','$canton','$parroquia','$direccion','$telefono1','$telefono2','$celular1','$celular2','$fax',
												'$correo','$clave', '$tipoActividad');");
	
	
		/*$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_usuario.usuarios
											VALUES ('$ruc','$ruc','$clave', 3);");*/
	
		/*if($idVue == null){
			$res = $conexion->ejecutarConsulta("INSERT INTO
					g_usuario.usuarios_perfiles(identificador, id_perfil)
					SELECT identificador, id_perfil FROM g_usuario.usuarios u ,g_usuario.perfiles p WHERE u.identificador = '$ruc' and p.nombre= 'Operadores';");
			
		}else{
			$res = $conexion->ejecutarConsulta("INSERT INTO
					g_usuario.usuarios_perfiles(identificador, id_perfil)
					SELECT identificador, id_perfil FROM g_usuario.usuarios u ,g_usuario.perfiles p WHERE u.identificador = '$ruc' and p.nombre= 'Operadores de Comercio Exterior';");
			
		}*/
	
		
		return $res;
	}

	public function listarSitios ($conexion,$identificador){
	
		$res = $conexion->ejecutarConsulta("select
													s.*, (select count(a.id_sitio) from g_operadores.areas a where a.id_sitio = s.id_sitio) as num_areas
											from
													g_operadores.sitios s
											where 
													s.identificador_operador = '$identificador'
													and s.estado not in ('eliminado','inactivo');");
		return $res;
	}
	
	public function listarSitiosAprobados ($conexion,$identificador){
	
		$res = $conexion->ejecutarConsulta("select
													*
											from
													g_operadores.sitios
											where
													identificador_operador = '$identificador' and
													estado = 'registrado'
											;");
		return $res;
	}
	
	public function abrirSitio ($conexion, $idSitio){
	    		
		$res = $conexion->ejecutarConsulta("select
													*
											from
													g_operadores.sitios
											where
													id_sitio = $idSitio;");
		return $res;
	}
	
	public function listarAreaOperador ($conexion, $idSitio){
		$res = $conexion->ejecutarConsulta("select
													a.*,
													lo.unidad_medida
											from
													g_operadores.sitios s,
													g_operadores.areas a,
													g_catalogos.areas_operacion lo
											where
													a.id_sitio = s.id_sitio 
													and s.id_sitio = $idSitio 
													and a.tipo_area = lo.nombre
													and a.estado not in ('eliminado','inactivo')
											group by
													a.id_area, lo.unidad_medida;");
		return $res;
	}
	
	public function eliminarArea ($conexion,$nombreLugar, $idSitio){
	
		$res = $conexion->ejecutarConsulta("delete
											from
												g_operadores.areas
											where
												nombre_area='$nombreLugar' and
												id_sitio=$idSitio;");
						return $res;
	}
	
	public function guardarNuevaArea ($conexion,$nombreArea,$tipoArea, $superficie, $idSitio, $codigoArea, $secuencial, $codigoTransaccion = null){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_operadores.areas(nombre_area, tipo_area, superficie_utilizada, id_sitio, estado, codigo, secuencial, codigo_transaccional)
   											 VALUES ('$nombreArea','$tipoArea',$superficie,$idSitio, 'creado', '$codigoArea', '$secuencial', '$codigoTransaccion') RETURNING id_area;");
		return $res;
	}
	
	public function actualizarSitio ($conexion,$nombreSitio,$superficie,$provincia,$canton,$parroquia, $direccion, $referencia, $latitud, $longitud, $zona, $telefono, $idSitio, $identificador, $codigoProvincia, $codigoSitio){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_operadores.sitios
											SET
												nombre_lugar = '$nombreSitio',
												provincia = '$provincia',
												canton = '$canton',
												parroquia = '$parroquia',
												direccion = '$direccion',
												referencia = '$referencia',
												latitud = '$latitud',
												longitud = '$longitud',
												zona = '$zona',
												superficie_total = $superficie,
												telefono = '$telefono',
												codigo_provincia = '$codigoProvincia',
												codigo = '$codigoSitio'
											WHERE
												id_sitio = $idSitio 
												and identificador_operador = '$identificador';");
				return $res;
	}
	
	/*ok*/
	public function guardarInformeOperacion($conexion,$idOperacion, $ruta){
	
		$res = $conexion->ejecutarConsulta( "UPDATE g_operadores.operaciones SET
												informe = '$ruta' WHERE
												id_operacion = $idOperacion;");
		
		return $res;
	}
	
	
	public function guardarNuevoSitio ($conexion,$nombreSitio,$provincia,$canton,$parroquia,$direccion,$referencia,$superficie,$identificador,$telefono,$latitud, $longitud, $codigoSitio, $croquis, $zona, $codigoProvincia){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.sitios(
            												nombre_lugar, provincia, canton, parroquia, direccion, referencia, superficie_total, identificador_operador, estado, telefono,latitud, longitud, codigo, croquis, zona, codigo_provincia)
    										VALUES ('$nombreSitio','$provincia','$canton','$parroquia','$direccion', '$referencia', $superficie,'$identificador','creado','$telefono','$latitud', '$longitud', '$codigoSitio', '$croquis', '$zona' , '$codigoProvincia') RETURNING id_sitio;");
				return $res;
	}
	
	
	/*ok*/
	public function listarOperaciones($conexion){
		$cid = $conexion->ejecutarConsulta("select
												t.id_tipo_operacion,
												t.id_area,
												t.nombre as nombre_operacion,
												a.nombre as nombre_area
											from
												g_catalogos.tipos_operacion t,
												g_catalogos.areas_operacion a
											where
												a.id_tipo_operacion = t.id_tipo_operacion
												and t.estado = 1;");
		while($fila = pg_fetch_assoc($cid)){
			$res[]= array(idTipoOperacion=>$fila['id_tipo_operacion'], idArea=>$fila['id_area'], nombreOperacion=>$fila['nombre_operacion'], nombreArea=>$fila['nombre_area']);
		}
		
		return $res;
	}
	
	public function listarRequerimientosOperaciones($conexion, $idTipoOperacion){
		$cid = $conexion->ejecutarConsulta("select
												t.id_tipo_operacion,
												t.id_area,
												t.nombre as nombre_operacion,
												a.nombre as nombre_area
											from
												g_operadores.tipos_operacion t,
												g_operadores.areas_operacion a
											where
												a.id_tipo_operacion = t.id_tipo_operacion and
												t.id_tipo_operacion = $idTipoOperacion;");
		while($fila = pg_fetch_assoc($cid)){
			$res[]= array('idTipoOperacion'=>$fila['id_tipo_operacion'], 'idArea'=>$fila['id_area'], 'nombreOperacion'=>$fila['nombre_operacion'], 'nombreArea'=>$fila['nombre_area']);
		}
	
		return $res;
	}
	
	public function listarOperacionesPorProducto($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("select
												t.id_tipo_operacion,
												t.id_area,
												t.nombre as nombre_operacion
											from
												g_catalogos.tipos_operacion t,
												g_catalogos.areas_operacion a,
												g_catalogos.productos p,
												g_catalogos.tipo_productos tp,
												g_catalogos.subtipo_productos sp
											where
												a.id_tipo_operacion = t.id_tipo_operacion and
												p.id_producto = $idProducto and
												tp.id_tipo_producto = sp.id_tipo_producto and
												sp.id_subtipo_producto = p.id_subtipo_producto and
												t.id_area = tp.id_area;");						
		
		/*$res = $conexion->ejecutarConsulta("select
												t.id_tipo_operacion,
												t.id_area,
												t.nombre as nombre_operacion
											from
												g_operadores.tipos_operacion t,
												g_operadores.areas_operacion a,
												g_catalogos.productos p,
												g_catalogos.tipo_producto tp
											where
												a.id_tipo_operacion = t.id_tipo_operacion and
												p.id_producto = $idProducto and
												p.id_tipo_producto = tp.id_tipo_producto and
												t.id_area = tp.id_area;");*/
		
		return $res;
	}
	
	/*Revisar funcion*/
	public function listarTipoOperacionPermitidas ($conexion, $identificador, $idArea, $idSitio){
						
		$res = $conexion->ejecutarConsulta("select * from g_operadores.operaciones_habilitadas('$identificador', '$idArea', '$idSitio')");
		
		while($fila = pg_fetch_assoc($res)){
			$tipoOperacion[]= array('idTipoOperacion'=>$fila['id_tipo_operacion'], 'nombre'=>$fila['nombre'], 'area'=>$fila['id_area'], 'idFlujo'=>$fila['id_flujo_operacion']);
		}
				
		return $tipoOperacion;
	}
	
	/*ok*/
	public function listarAreasOperador($conexion, $identificador){
		$cid = $conexion->ejecutarConsulta("select
												sitios.nombre_lugar as sitio,
												areas.id_area,
												areas.nombre_area as area,
												areas.tipo_area
											from
												g_operadores.sitios,
												g_operadores.areas
											where
												sitios.identificador_operador = '$identificador'
												and sitios.id_sitio = areas.id_sitio;");
	
				while ($fila = pg_fetch_assoc($cid)){
						$res[] = array(codigo=>$fila['id_area'],nombreSitio=>$fila['sitio'],nombreArea=>$fila['area'],tipoArea=>$fila['tipo_area']);
		}
	
			return $res;
	}
		
	/*ok*/
	public function guardarNuevaOperacion ($conexion,$operacion,$identificador, $idProducto, $nombreProducto, $idOperadorTipoOperacion, $idHistoricoOperacion, $moduloProvee='moduloExterno', $idPais=0, $nombrePais=NULL, $idVue=NULL, $subPartidaProducto=NULL, $codigoProducto=NULL){
  
		$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.operaciones(
				id_tipo_operacion, identificador_operador, estado, id_producto, nombre_producto, id_vue, fecha_creacion, id_pais, id_operador_tipo_operacion, id_historial_operacion, nombre_pais, subpartida_producto_vue, codigo_producto_vue, modulo_provee)
				VALUES ($operacion,'$identificador','creado', $idProducto, rtrim('$nombreProducto'), '$idVue', now(),$idPais, $idOperadorTipoOperacion, $idHistoricoOperacion, '$nombrePais', '$subPartidaProducto', '$codigoProducto', '$moduloProvee') returning id_operacion");

     return $res;
  	}
		
		/*no se usa*/
		public function guardarProductoSolicitud ($conexion,$solicitud,$producto){
		
			$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.productos_areas(
														id_solicitud, id_producto)
												VALUES ($solicitud,$producto);");
			return $res;
		}
		
		/*ok*/
		public function guardarAreaOperacion ($conexion,$area, $operacion){
					
			$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.productos_areas_operacion(id_area, id_operacion)
												SELECT $area,$operacion  WHERE NOT EXISTS (SELECT id_operacion FROM g_operadores.productos_areas_operacion 
												WHERE id_operacion = $operacion and id_area = $area ) returning id_producto_area_operacion;");
			return $res;
		}
		
		/*ok*/
		public function listarOperacionesOperador ($conexion,$identificador, $estado, $incremento, $datoIncremento, $tipoEstado = null){
		    
		    $busqueda = '';
		    if($tipoEstado == 'cargarProducto'){
		        $busqueda = "and t.codigo NOT IN ('IMP','EXP')";
		    }else if($tipoEstado == 'representanteTecnico'){
		        $busqueda = "and fo.estado = 'representanteTecnico'";
		    }
		    
		    $res = $conexion->ejecutarConsulta("select
													distinct min(s.id_operacion) as id_operacion,
													s.identificador_operador,
													s.estado,
													s.id_tipo_operacion,
													t.nombre as nombre_tipo_operacion,
													st.provincia,
													st.id_sitio,
													st.nombre_lugar,
													t.codigo
												from
													g_operadores.operaciones s,
													g_catalogos.tipos_operacion t,
													g_operadores.operadores o,
													g_operadores.productos_areas_operacion sa,
													g_operadores.areas a,
													g_operadores.sitios st,
													g_operadores.flujos_operaciones fo
												where
													s.identificador_operador = '$identificador' and
													s.id_tipo_operacion = t.id_tipo_operacion and
													s.identificador_operador = o.identificador and
													s.id_operacion = sa.id_operacion and
													sa.id_area = a.id_area and
													a.id_sitio = st.id_sitio and
													s.estado $estado and
													t.id_flujo_operacion = fo.id_flujo
			                                        ".$busqueda."
												group by s.identificador_operador, s.estado, s.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area, t.codigo
												order by id_operacion
													offset $datoIncremento rows
													fetch next $incremento rows only;");
		    return $res;
		}
		
		public function listarOperacionesOperadorPorArea ($conexion,$identificador, $estado, $codigoArea){
		    		    		    		    
		    $res = $conexion->ejecutarConsulta("select
													distinct min(s.id_operacion) as id_operacion,
													s.identificador_operador,
													s.estado,
													s.id_tipo_operacion,
		    										s.id_operador_tipo_operacion,
													t.nombre as nombre_tipo_operacion,
													st.provincia,
													st.id_sitio,
													st.nombre_lugar
												from
													g_operadores.operaciones s,
													g_catalogos.tipos_operacion t,
													g_operadores.operadores o,
													g_operadores.productos_areas_operacion sa,
													g_operadores.areas a,
													g_operadores.sitios st
												where
													s.identificador_operador = '$identificador' and
													s.id_tipo_operacion = t.id_tipo_operacion and
													s.identificador_operador = o.identificador and
													s.id_operacion = sa.id_operacion and
													sa.id_area = a.id_area and
													a.id_sitio = st.id_sitio and
													s.estado $estado and
			                                        t.codigo||'-'||t.id_area $codigoArea
												group by s.identificador_operador, s.estado, s.id_tipo_operacion, s.id_operador_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area
												order by id_operacion;");
		    return $res;
		}
		
		public function listarOperacionesOperadorPorProducto ($conexion,$identificador, $estado, $incremento, $datoIncremento){
		
			$res = $conexion->ejecutarConsulta("select
													distinct s.id_operacion, s.identificador_operador, s.estado, t.nombre,
													o.razon_social, o.nombre_representante, o.apellido_representante,
													s.nombre_producto, st.provincia, st.id_sitio, s.id_tipo_operacion, 
													t.nombre as nombre_tipo_operacion, st.nombre_lugar
												from
													g_operadores.operaciones s,
													g_catalogos.tipos_operacion t,
													g_operadores.operadores o,
													g_operadores.productos_areas_operacion sa,
													g_operadores.areas a,
													g_operadores.sitios st
												where
													s.identificador_operador = '$identificador' and
													s.id_tipo_operacion = t.id_tipo_operacion and
													s.identificador_operador = o.identificador and
													s.id_operacion = sa.id_operacion and
													sa.id_area = a.id_area and
													a.id_sitio = st.id_sitio and
													s.estado $estado
												order by 1
													offset $datoIncremento rows
													fetch next $incremento rows only;");
			return $res;
		}
		
		/*ok*/
		public function abrirOperacion ($conexion, $identificador, $idOperacion){
			
		$cid = $conexion->ejecutarConsulta("select 
												o.id_operacion, 
												o.id_tipo_operacion,
												o.identificador_operador, 
												o.id_producto,
												o.nombre_producto,
												o.estado, 
												o.id_producto, 
												o.nombre_producto,
												o.observacion,
												o.nombre_pais,
												o.fecha_aprobacion,
												o.fecha_finalizacion,
												o.id_operador_tipo_operacion,
												o.id_historial_operacion,
												o.proceso_modificacion,
												t.nombre,
												t.id_area as codigo_area,
												t.codigo as codigo_tipo_operacion,
												a.nombre_area as area, 
												a.tipo_area, 
												a.superficie_utilizada,
												ss.provincia,
												ss.canton,
												ss.parroquia,
												ss.id_sitio,
												ss.nombre_lugar as sitio,
												ss.direccion,
												ss.referencia,
												ss.croquis,
                                                ss.codigo_provincia,
												pao.estado as estado_area,
												pao.ruta_archivo,
												pao.id_area,  
												pao.observacion as observacion_area,
												ss.identificador_operador||'.'||ss.codigo_provincia || ss.codigo || a.codigo||a.secuencial as codificacion_area
											from 
												g_operadores.operaciones o,
												g_operadores.productos_areas_operacion pao,
												g_operadores.areas a,
												g_catalogos.tipos_operacion t,
												g_operadores.sitios ss
											where
												o.identificador_operador = '$identificador' and
												o.id_operacion = $idOperacion and
												o.id_operacion = pao.id_operacion and
												pao.id_area = a.id_area and
												o.id_operacion = pao.id_operacion and
												o.id_tipo_operacion = t.id_tipo_operacion and
												a.id_sitio = ss.id_sitio 
											order by
												o.id_producto;");

			while ($fila = pg_fetch_assoc($cid)){
				$res[] = array('idSolicitud'=>$fila['id_operacion'],'idTipoOperacion'=>$fila['id_tipo_operacion'],'tipoOperacion'=>$fila['nombre'],
								'identificador'=>$fila['identificador_operador'],'estado'=>$fila['estado'],'nombrePais'=>$fila['nombre_pais'], 'informe'=>$fila['ruta_archivo'], 'observacion'=>$fila['observacion'], 
								'referencia'=>$fila['referencia'], 'croquis'=>$fila['croquis'],'idArea'=>$fila['id_area'],'nombreArea'=>$fila['area'],'tipoArea'=>$fila['tipo_area'],
								'superficieArea'=>$fila['superficie_utilizada'],'provincia'=>$fila['provincia'],'canton'=>$fila['canton'],'parroquia'=>$fila['parroquia'],
								'idProducto'=>$fila['id_producto'],'producto'=>$fila['nombre_producto'],'idSitio'=>$fila['id_sitio'],
								'nombreSitio'=>$fila['sitio'],'direccionSitio'=>$fila['direccion'],'estadoArea'=>$fila['estado_area'],'observacionArea'=>$fila['observacion_area'],
								'fechaAprobacion'=>$fila['fecha_aprobacion'], 'fechaFinalizacion'=>$fila['fecha_finalizacion'], 'nombreOperacion'=>$fila['nombre_operacion'],
								'codigoArea'=>$fila['codigo_area'], 'codigoTipoOperacion'=>$fila['codigo_tipo_operacion'], 'idOperadorTipoOperacion'=>$fila['id_operador_tipo_operacion'],
								'idHistorialOperacion'=>$fila['id_historial_operacion'], 'procesoModificacion'=>$fila['proceso_modificacion'], 'codificacionArea'=>$fila['codificacion_area'],
            				    'codigoProvincia'=>$fila['codigo_provincia']
				);
			}
			
			return $res;
		}	
		
		/*ok*/
public function abrirOperacionRevision ($conexion, $idOperacion){
			$cid = $conexion->ejecutarConsulta("select distinct
													s.id_operacion, s.id_tipo_operacion, t.nombre, s.identificador_operador, s.estado, s.nombre_pais,
													sa.id_area, a.nombre_area as area, a.tipo_area, a.superficie_utilizada, ss.provincia, ss.canton,
													ss.parroquia, ss.referencia, s.id_producto, s.nombre_producto, ss.id_sitio, ss.nombre_lugar as sitio,
													ss.direccion, sa.estado as estado_area, sa.observacion as observacion_area, ss.croquis,
													s.informe, o.razon_social, o.nombre_representante, o.apellido_representante, s.id_vue
												from
													g_operadores.operaciones s,
													g_operadores.productos_areas_operacion sa,
													g_operadores.areas a,
													g_catalogos.tipos_operacion t,
													g_operadores.sitios ss,
													g_catalogos.localizacion l,
													g_operadores.operadores o
												where
													s.id_operacion = $idOperacion and
													s.id_operacion = sa.id_operacion and
													sa.id_area = a.id_area and
													s.id_tipo_operacion = t.id_tipo_operacion and
													a.id_sitio = ss.id_sitio and
													ss.canton = l.nombre and
													o.identificador = s.identificador_operador
												order by
													s.nombre_producto;");
										
				while ($fila = pg_fetch_assoc($cid)){
											$res[] = array(idSolicitud=>$fila['id_operacion'],idTipoOperacion=>$fila['id_tipo_operacion'],tipoOperacion=>$fila['nombre'],
											identificador=>$fila['identificador_operador'],estado=>$fila['estado'],nombrePais=>$fila['nombre_pais'],
											idArea=>$fila['id_area'],nombreArea=>$fila['area'],tipoArea=>$fila['tipo_area'],
									superficieArea=>$fila['superficie_utilizada'],provincia=>$fila['provincia'],canton=>$fila['canton'],parroquia=>$fila['parroquia'],
										referencia=>$fila['referencia'], idProducto=>$fila['id_producto'],producto=>$fila['nombre_producto'],idProducto=>$fila['id_producto'],idSitio=>$fila['id_sitio'],
									nombreSitio=>$fila['sitio'],direccionSitio=>$fila['direccion'],estadoArea=>$fila['estado_area'],observacionArea=>$fila['observacion_area'],
									idLocalizacion=>$fila['id_localizacion'],croquis=>$fila['croquis'],informe=>$fila['informe'],
									ruc=>$fila['razon_social'],nombreRepresentante=>$fila['nombre_representante'],apellidoRepresentante=>$fila['apellido_representante'],
									idVue=>$fila['id_vue']);
				}
				
			return $res;
			}
			
			/*ok*/
			public function abrirAreasOperacion ($conexion, $idOperacion){
				$cid = $conexion->ejecutarConsulta("select * from g_operadores.productos_areas_operacion where id_operacion=$idOperacion;");
			
						while ($fila = pg_fetch_assoc($cid)){
						$res[] = array(idOperacion=>$fila['id_operacion'],idArea=>$fila['id_area'],estado=>$fila['estado'],
						observacion=>$fila['observacion']);
				}
			
				return $res;
			}
		
		/*ok*/
		public function obtenerAreasOperacion ($conexion, $idSolicitud){
			$cid = $conexion->ejecutarConsulta("select 
													sa.id_area 
												from 
													g_operadores.productos_areas_operacion sa, 
													g_operadores.operaciones s,
													g_operadores.areas a
												where
													s.id_operacion = sa.id_operacion and
													s.id_operacion = $idSolicitud and
													sa.id_area = a.id_area;");
			
			while ($fila = pg_fetch_assoc($cid)){
				$res[] = array('idArea'=>$fila['id_area']);
			}
				
			return $res;
		}
		
		/**/
		public function enviarOperacion ($conexion, $idOperacion, $estado, $observacion=null){
		    
		    $fechaActualizacion = '';
		    
		    if($estado == 'registrado' || $estado == 'registradoObservacion'){
		        $fechaActualizacion = 'fecha_aprobacion = now()';
		    }else{
		        $fechaActualizacion = 'fecha_modificacion = now()';
            }
			
			$res = $conexion->ejecutarConsulta("update 
													g_operadores.operaciones 
												set 
													estado = '$estado',
													observacion = '$observacion',
													".$fechaActualizacion."
												where 
													id_operacion = $idOperacion;");
			return $res;
		}
		
		public function enviarOperacionEstadoAnterior ($conexion, $idOperacion){
							
			$res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones o
												set
													estado_anterior = op.estado
												from
													g_operadores.operaciones op
												where
													o.id_operacion = op.id_operacion and
													op.id_operacion = $idOperacion;");
					return $res;
		}
			
		public function listarOperadores($conexion){
			$res = $conexion->ejecutarConsulta("SELECT
														identificador,
														case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador
												FROM
														g_operadores.operadores
												ORDER BY
														2");
					return $res;
		}
		
		/*ok*/
		public function enviarAreas ($conexion, $idArea, $estado, $observacion = null){
			
			
			$res = $conexion->ejecutarConsulta("update
													g_operadores.areas
												set
													estado = '$estado',
													observacion = '$observacion'
												where
													id_area = $idArea;");
			return $res;
		}
		
		/*ok - no se usara con el cambio de interfaz*/
		/*public function listarOperacionesRevision ($conexion){
		
			$res = $conexion->ejecutarConsulta("select
													distinct s.id_operacion, 
													s.identificador_operador, 
													s.estado, t.nombre,
													o.razon_social, o.nombre_representante, o.apellido_representante,
													p.nombre_comun as producto
												from
													g_operadores.operaciones s,
													g_operadores.tipos_operacion t,
													g_operadores.operadores o,
													g_catalogos.productos p,
													g_operadores.productos_areas_operacion sa,
													g_operadores.sitios si,
													g_operadores.areas a
												where
													s.id_tipo_operacion = t.id_tipo_operacion and
													s.identificador_operador = o.identificador and
													s.id_operacion = sa.id_operacion and
													s.id_producto = p.id_producto and
													sa.id_area = a.id_area and
													a.id_sitio = si.id_sitio and
													s.estado in ('enviado','proceso','finalizado');");
			return $res;
		}
		/*filtro de operaciones por provincia por asignar
		public function listarOperacionesRevisionProvincia ($conexion, $provinciaInspector){
						  
						   $res = $conexion->ejecutarConsulta("select
														             distinct op.id_operacion, 
														             op.identificador_operador, 
														             op.estado, 
														             t.nombre,
														             o.razon_social, o.nombre_representante, o.apellido_representante,
														             op.nombre_producto as producto
														       from
														             g_operadores.operaciones op,
														             g_catalogos.tipos_operacion t,
														             g_operadores.operadores o,
														             g_operadores.productos_areas_operacion sa,
														             g_operadores.sitios si,
														             g_operadores.areas a
														      where
														             op.id_tipo_operacion = t.id_tipo_operacion and
														             op.identificador_operador = o.identificador and
														             op.id_operacion = sa.id_operacion and
														             sa.id_area = a.id_area and
														             a.id_sitio = si.id_sitio and
														             si.provincia = '$provinciaInspector' and
														             op.estado in ('enviado');");
						   return $res;
		}*/
		
		
		
		public function obtenerRequerimientosTipoOperacion ($conexion,$idOperacion){
		
			$cid = $conexion->ejecutarConsulta("select 
													t.id_tipo_operacion as tipo_operacion, 
													t.nombre as nombre_operacion,
													lo.id_area_operacion as lugar_operacion,
													lo.nombre as nombre_lugar, 
													count(lo.nombre) as requisito
												from 
													g_operadores.tipos_operacion t,
													g_operadores.lugar_operacion lo
												where
													t.id_tipo_operacion = lo.id_tipo_operacion and
													t.id_tipo_operacion = $idOperacion
												group by
													t.id_tipo_operacion, lo.id_area_operacion;");
				
			while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(tipoOperacion=>$fila['tipo_operacion'],nombreOperacion=>$fila['nombre_operacion'],lugarOperacion=>$fila['lugar_operacion'],nombreLugar=>$fila['nombre_lugar'],requisito=>$fila['requisito']);
			}
				
			return $res;
		}
		
	
		public function obtenerDatosProveedor ($conexion, $identificador, $idProducto){
			$res = $conexion->ejecutarConsulta("select
													distinct o.id_producto
												from
													g_operadores.operaciones o
												where
													o.identificador_operador = '$identificador' and
													o.estado IN ('registrado','registradoObservacion') and
													o.id_producto = $idProducto;");
					return $res;
		}
		
		//proveedores
		public function obtenerOperadoresAprobados ($conexion, $idProducto, $proveedor){
		
			$cid = $conexion->ejecutarConsulta("select 
													s.id_solicitud,
													s.identificador_operador, 
													s.estado, 
													sp.id_producto, 
													s.id_tipo_operacion
												from 
													g_operadores.solicitudes s, 
													g_operadores.areas_solicitud sp
												where 
													s.id_solicitud = sp.id_solicitud and 
													s.estado = 'registrado' and 
													s.id_tipo_operacion in (1, 2) and
													sp.id_producto = $idProducto and
													s.identificador_operador = '$proveedor'
												group by
													s.id_solicitud, sp.id_producto;");//poner código de requisito para comercializador y exportador
		
			while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(operador=>$fila['identificador_operador'],estado=>$fila['estado'],producto=>$fila['id_producto']);
			}
		
			return $res;
		}
		
		public function listarTiposAreas ($conexion){
			$cid = $conexion->ejecutarConsulta("select 
													distinct (nombre) nombre, unidad_medida
												from 
													g_operadores.lugar_operacion;");
			
			while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(nombre=>$fila['nombre'], unidad=>$fila['unidad_medida']);
			}
			
			return $res;
		}
		
		/*ok*/
		/*public function listarAreasRegistradasXSolicitud($conexion, $identificador){
			$res = $conexion->ejecutarConsulta("select
													o.id_tipo_operacion,
													o.id_producto,
													sa.id_area
												from
													g_operadores.operaciones o,
													g_operadores.productos_areas_operacion sa
												where
													o.identificador_operador = '$identificador' and
													o.id_operacion = sa.id_operacion
													o.estado != 'eliminado';");

			while ($fila = pg_fetch_assoc($res)){
				$areasRegistradas[] =  $fila['id_producto'].$fila['id_tipo_operacion'].$fila['id_area'];
			}			
			
			return $areasRegistradas;
		}*/	
		
		/*ok*/
		public function guardarProductoArea ($conexion,$producto, $areaOperacion){
		
			$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.productos_areas(id_producto, id_area_operacion)
												VALUES ($producto,$areaOperacion);");
			return $res;
		}
		
		public function listarAreas ($conexion){

			$res = $conexion->ejecutarConsulta("select 
													distinct(ao.nombre), ao.unidad_medida, ao.codigo
												from 
													g_catalogos.areas_operacion ao,
													g_catalogos.tipos_operacion top
												WHERE
													ao.id_tipo_operacion = top.id_tipo_operacion and
													top.estado not in (9)
												order by 1;");
			return $res;
		}
		
		/*
		public function evaluarOperacion ($conexion, $idSolicitud, $estado, $observacion){
			$res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													estado= '$estado',
													observacion = '$observacion'
												where
													id_operacion = $idSolicitud;");
					return $res;
		}*/
		
		/*ok*/
		public function evaluarAreasOperacion ($conexion, $idSolicitud, $idArea, $estado, $observacion, $informe){
			$res = $conexion->ejecutarConsulta("update
													g_operadores.productos_areas_operacion
												set
													estado = '$estado',
													observacion ='$observacion',
													ruta_archivo = '$informe'
												where
													id_area = $idArea and
													id_operacion = $idSolicitud;");
			return $res;
		}
		
		public function cambiarEstadoSolicitudArea ($conexion, $idSolicitudArea, $estado){
			$res = $conexion->ejecutarConsulta("update
													g_operadores.productos_areas_operacion
												set
													estado = '$estado'
												where
													id_producto_area_operacion = $idSolicitudArea;");
			return $res;
		}
		
		/*ok*/
		public function obtenerResultadoAreasOperacion ($conexion, $idSolicitud){
			$cid = $conexion->ejecutarConsulta("select 
													distinct (sa.estado) as estado,
													a.tipo_area
												from 
													g_operadores.productos_areas_operacion sa,
													g_operadores.areas a
												where 
													sa.id_operacion = $idSolicitud and
													sa.id_area = a.id_area;");
		while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(tipoArea=>$fila['tipo_area'], estado=>$fila['estado']);
			}
			
			return $res;
		}
			
		/*ok*/
		public function buscarOperador ($conexion,$identificador){
								
			$res = $conexion->ejecutarConsulta("Select 
													* 
												from 
													g_operadores.operadores 
												where 
													identificador='$identificador';");
			
			return $res;
		}
		
		/*
		public function finalizarSolicitud($conexion, $idSolicitud, $resultado, $observacion){
			$res = $conexion->ejecutarConsulta("UPDATE 
													g_operadores.operaciones
												SET
													estado = '$resultado',
													observacion = '$observacion'
												WHERE
													id_operacion = $idSolicitud;");
			return $res;	
		}*/
		
		public function listarSolicitudesRecibidas ($conexion){
		
			$res = $conexion->ejecutarConsulta("select
													distinct s.id_solicitud,
													s.identificador_operador,
													s.estado, t.nombre,
													o.razon_social, o.nombre_representante, o.apellido_representante,
													p.nombre_comun as producto
												from
													g_operadores.solicitudes s,
													g_operadores.tipos_operacion t,
													g_operadores.operadores o,
													g_catalogos.productos p,
													g_operadores.areas_solicitud sa,
													g_operadores.productos_areas ps,
													g_operadores.sitios si,
													g_operadores.areas a,
													g_catalogos.localizacion l
												where
													s.id_tipo_operacion = t.id_tipo_operacion and
													s.identificador_operador = o.identificador and
													s.id_solicitud = sa.id_solicitud and
													ps.id_area_solicitud = sa.id_area_solicitud and
													ps.id_producto = p.id_producto and
													sa.id_area = a.id_area and
													a.id_sitio = si.id_sitio 
												order by 1
												;");
					return $res;
		}
		
		public function filtrarSolicitudes($conexion, $idSolicitud, $identificador, $tipoOperacion, $estado){
				$idSolicitud = $idSolicitud!="" ? "'" . $idSolicitud . "'" : "null";
				$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
				$estado = $estado!="" ? "'" . $estado . "'" : "null";
				$tipoOperacion = $tipoOperacion!="" ? $tipoOperacion : "null";
				
				$res = $conexion->ejecutarConsulta("select
														s.id_solicitud,
														s.identificador_operador,
														s.id_tipo_operacion,
														s.estado,
														ot.nombre
													from
														g_operadores.mostrar_solicitudes_filtradas($idSolicitud, $identificador, $tipoOperacion, $estado) as s,
														g_operadores.tipos_operacion ot
													where
														ot.id_tipo_operacion = s.id_tipo_operacion
													order by 1, 3
													");
				return $res;
			}
			
			public function listarTiposOperacion($conexion){
				$res = $conexion->ejecutarConsulta("select
														t.id_tipo_operacion,
														t.id_area,
														t.nombre as nombre_operacion
													from
														g_operadores.tipos_operacion t
													order by 1;");
						return $res;
			}
			
			public function  obtenerSecuencialSitio($conexion, $provincia, $identificadorOperador){
								
				$res = $conexion->ejecutarConsulta("SELECT
														COALESCE(MAX(CAST(codigo as  numeric(5))),0)+1 valor
													FROM
														g_operadores.sitios s
													WHERE
														s.identificador_operador = '$identificadorOperador'
														and upper(s.provincia) = upper('$provincia')");
				
				return $res;
			}
			
			public function actualizarCroquisSitio ($conexion, $identificador, $idSitio, $croquis){
			
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.sitios
													SET
														croquis = '$croquis'
													WHERE
														id_sitio = $idSitio
														and identificador_operador = '$identificador';");
					return $res;
			}
			
			
			public function eliminarAreas ($conexion, $idSitio){
			
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.areas
													SET
														estado = 'eliminado',
													WHERE
														estado = 'creado'
														AND id_sitio = $idSitio ;");
				return $res;
			}
			
			public function eliminarSitio ($conexion, $idSitio){
					
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.sitios
													SET
														estado = 'eliminado',
													WHERE
														estado = 'creado'
														AND id_sitio = $idSitio ;");
				return $res;
			}
			
			public function verificarAreaOperacion ($conexion,$nombreLugar, $idSitio){
			
				$res = $conexion->ejecutarConsulta("select 
														* 
													from 
														g_operadores.productos_areas_operacion pao,
														g_operadores.areas a
													where 
														a.id_sitio=$idSitio and
														a.nombre_area = '$nombreLugar' and
														a.id_area = pao.id_area;");
				return $res;
			}
			
			
			/*revisar*/
			public function listarProveedoresOperador ($conexion, $identificador){
				$res = $conexion->ejecutarConsulta("select
														pr.*
													from
														g_operadores.proveedores pr
													where
														pr.identificador_operador = '$identificador'
                                                        and estado_proveedor = 'activo'
                                                        and id_operador_tipo_operacion is null;");

						return $res;
			}
			
			/*ok*/
			public function abrirProveedoresOperador ($conexion, $idProveedores){
				$res = $conexion->ejecutarConsulta("select
														*
													from
														g_operadores.proveedores pr
													where
														pr.id_proveedor = $idProveedores;");
				
				/*$res = $conexion->ejecutarConsulta("select
														pr.id_operacion,
														pr.codigo_proveedor,
														pr.operacion_operador,
														o.nombre,
														pr.id_producto,
														p.nombre_comun,
														pr.id_pais,
														pr.nombre_pais
													from
														g_operadores.proveedores pr,
														g_catalogos.productos p,
														g_operadores.tipos_operacion o
													where
														pr.id_proveedores = $idProveedores and
														pr.id_producto = p.id_producto and
														o.id_tipo_operacion = pr.operacion_operador;");*/
				return $res;
			}
			
			/*MODIFICADO EJAR*/
			public function listarTipoOperacionComercioExterior ($conexion, $identificador){
				$res = $conexion->ejecutarConsulta("select 
														distinct o.id_tipo_operacion, 
														ot.nombre,
														ot.id_area
													from 
														g_operadores.operaciones o,
														g_catalogos.tipos_operacion ot 
													where 
														identificador_operador='$identificador' and 
														ot.id_tipo_operacion=o.id_tipo_operacion and
														ot.nombre in ('Importador','Exportador')");
			
				while($fila = pg_fetch_assoc($res)){
					$tipoOperacion[]= array(idTipoOperacion=>$fila['id_tipo_operacion'], nombre=>$fila['nombre'], area=>$fila['id_area']);
				}
			
				return $tipoOperacion;
			}
			
			/*ok*/
			public function guardarNuevoProveedorComercioExterior ($conexion,$proveedor,$identificador,$operacion,$nombreOperacion,$producto,$nombreProducto,$pais, $nombrePais, $id_vue = null){
			
				if($id_vue == null){
					$res = $conexion->ejecutarConsulta("INSERT INTO
							g_operadores.proveedores(codigo_proveedor, identificador_operador, operacion_operador, nombre_operacion,id_producto, nombre_producto,id_pais, nombre_pais)
							VALUES ('$proveedor','$identificador',$operacion,'$nombreOperacion', $producto,'$nombreProducto',$pais, '$nombrePais');");
					
				}else{
					$res = $conexion->ejecutarConsulta("INSERT INTO
							g_operadores.proveedores(codigo_proveedor, identificador_operador, operacion_operador, nombre_operacion,id_producto, nombre_producto,id_pais, nombre_pais, id_vue)
							VALUES ('$proveedor','$identificador',$operacion,'$nombreOperacion', $producto,'$nombreProducto',$pais, '$nombrePais' , '$id_vue');");
				}
					
				return $res;
			}
			
			public function guardarNuevoProveedor ($conexion,$proveedor,$identificador,$idTipoOperacion,$producto, $nombreProducto,$idPais, $nombrePais, $nombreExportador = null, $idOperacion = null, $idTipoTransicion = null, $tipo = null, $idOperadorTipoOperacion = null){
				
				$idTipoOperacion = ($idTipoOperacion == '' ? 'null': $idTipoOperacion);
				$idOperacion = ($idOperacion == '' ? 'null': $idOperacion);
				$idTipoTransicion = ($idTipoTransicion == '' ? 'null': $idTipoTransicion);
				$idOperadorTipoOperacion = ($idOperadorTipoOperacion == '' ? 'null': $idOperadorTipoOperacion);
				
				$res = $conexion->ejecutarConsulta("INSERT INTO
						g_operadores.proveedores(codigo_proveedor, identificador_operador, operacion_operador, id_producto, nombre_producto, id_pais, nombre_pais, nombre_exportador, id_operacion, id_tipo_transicion, tipo, id_operador_tipo_operacion)
						VALUES ('$proveedor','$identificador',$idTipoOperacion,$producto, '$nombreProducto', $idPais, '$nombrePais', '$nombreExportador', $idOperacion, $idTipoTransicion, '$tipo', $idOperadorTipoOperacion) RETURNING id_proveedor;");
				return $res;
			}
			
			/*ok*/
			public function actualizarProveedorComercioExterior ($conexion,$idProveedor, $proveedor,$identificador,$operacion,$nombreOperacion,$producto,$nombreProducto,$pais, $nombrePais){
					
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.proveedores 
													SET 
														codigo_proveedor='$proveedor',
														identificador_operador='$identificador',
														operacion_operador=$operacion,
														nombre_operacion='$nombreOperacion', 
														id_producto=$producto,
														nombre_producto='$nombreProducto',
														id_pais=$pais,
														nombre_pais='$nombrePais'
													WHERE 
														id_proveedor = $idProveedor;");
				return $res;
			}
				
			public function actualizarProveedor ($conexion,$idProveedor,$proveedor,$identificador,$producto, $nombreProducto,$idPais, $nombrePais){
					
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.proveedores 
													SET
														codigo_proveedor = '$proveedor',
														identificador_operador = '$identificador',
														id_producto = $producto, 
														nombre_producto = '$nombreProducto',
														id_pais = $idPais, 
														nombre_pais = '$nombrePais'
													WHERE
														id_proveedor = $idProveedor;");
				return $res;
			}
			
			public function  generarNumeroInforme($conexion,$codigo){
					
				$res = $conexion->ejecutarConsulta("SELECT
														MAX(ruta_archivo) as numero
													FROM
														g_operadores.productos_areas_operacion
													WHERE
														ruta_archivo LIKE '$codigo';");
				return $res;
			}
			
			////////////////////////////////////////////////////////
			
			public function listaProductoProveedor($conexion, $identificadorProveedor, $idProducto){
											
				$res = $conexion->ejecutarConsulta("SELECT
				 										*
													FROM
														g_operadores.operaciones o
													WHERE
														o.identificador_operador = '$identificadorProveedor' 
														and o.id_producto = $idProducto
														and estado not IN ('eliminado','anulado','cancelado','rechazado','inactivo','noHabilitado');");
				
				return $res;
			}
			
			public function buscarAreaOperador ($conexion, $identificador, $tipoArea){
					
				$res = $conexion->ejecutarConsulta("select
														a.*
													from
														g_operadores.sitios s,
														g_operadores.areas a
													where
														a.id_sitio = s.id_sitio
														and s.identificador_operador = '$identificador'
														and a.tipo_area = '$tipoArea';");
				
						return $res;
			}
			
			public function buscarOperacionProductoPais ($conexion, $identificador, $operacion,$idProducto, $idPais){
				
				
				$res = $conexion->ejecutarConsulta("select
														*
													from
														g_operadores.operaciones 
													where
														identificador_operador = '$identificador'
														and id_tipo_operacion = $operacion
														and id_producto = $idProducto
														and id_pais = $idPais
														and estado not in ('anulado','cancelado','inactivo','noHabilitado');");
				return $res;
			}
			
			public function buscarOperacionVue($conexion, $identificador, $idVue){
				
				$res = $conexion->ejecutarConsulta("SELECT
														id_operacion
													FROM
														g_operadores.operaciones
													WHERE
														identificador_operador = '$identificador'
														and id_vue = '$idVue';");
				
				return $res;
			}
			
			public function actualizaOperadorVUE ($conexion, $identificador, $provincia,$canton, $parroquia, $direccion, $telefono, $celular, $correo){
					
				$res = $conexion->ejecutarConsulta("UPDATE  
														g_operadores.operadores
													SET
														provincia  = '$provincia',
														canton = '$canton',
														parroquia = '$parroquia',
														direccion = '$direccion',
														telefono_uno = '$telefono',
														celular_uno = '$celular',
														correo = '$correo'
													WHERE
														identificador = '$identificador';");
				return $res;
			}
			
			public function actualizarSitioVUE ($conexion, $idSitio, $provincia,$canton, $parroquia, $direccion, $telefono){
				
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.sitios
													SET
														provincia  = '$provincia',
														canton = '$canton',
														parroquia = '$parroquia',
														direccion = '$direccion',
														telefono = '$telefono'
													WHERE
														id_sitio = '$idSitio';");
						return $res;
			}
			
			public function actualizarNumeroSolicitud ($conexion, $identificador, $operacion,$idProducto, $idVue){
			
			
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.operaciones 
													SET
														id_vue = '$idVue'
													WHERE
														identificador_operador = '$identificador'
														and id_tipo_operacion = $operacion
														and id_producto = $idProducto;");
				return $res;
			}
			
			public function verificarSitioOperacion ($conexion, $idSitio){
				 
				$res = $conexion->ejecutarConsulta("select
														*
													from
														g_operadores.productos_areas_operacion pao,
														g_operadores.areas a
													where
														a.id_sitio=$idSitio and
														a.id_area = pao.id_area;");
						return $res;
			}
			
			public function actualizarSitioEnUso ($conexion,$nombreSitio,$superficie,$referencia,$telefono, $idSitio, $identificador){
			
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.sitios
													SET
														nombre_lugar = '$nombreSitio',
														referencia = '$referencia',
														superficie_total = $superficie,
														telefono = '$telefono'
													WHERE
														id_sitio = $idSitio
														and identificador_operador = '$identificador';");
				return $res;
			}
			
			public function actualizarFechaModificacionOperacion ($conexion,$idOperacion){
					
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.operaciones
													SET
														fecha_modificacion = now()
													WHERE
														id_operacion = $idOperacion;");
				return $res;
			}
			
			public function anularSolicitudVUE ($conexion, $idVue, $estado){
				$res = $conexion->ejecutarConsulta("update
														g_operadores.operaciones
													set
														estado = '$estado'
													where
														id_vue = '$idVue';");
						return $res;
			}
			
			public function buscarOperadorProductos ($conexion, $identificador, $idVue){
									
				$res = $conexion->ejecutarConsulta("SELECT 
														*
													FROM 
														g_operadores.operaciones
													WHERE
														identificador_operador = '$identificador'
														and id_vue = '$idVue';");
				return $res;
			}
			
			public function cambiarEstadoOperacionProducto ($conexion, $identificador , $idOperacion, $idProducto, $idPais, $idVue, $estado){
				$res = $conexion->ejecutarConsulta("update
														g_operadores.operaciones
													set
														estado = '$estado'
													where
														identificador_operador = '$identificador'
														and id_tipo_operacion = $idOperacion
														and id_producto = $idProducto
														and id_pais = $idPais
														and id_vue = '$idVue';");
				return $res;
			}
			
			public function buscarOperadorProductoPaisActividad ($conexion, $identificador, $idPais, $idProducto, $idActividad, $estado){
				
				$res = $conexion->ejecutarConsulta("SELECT 
														* 
													FROM  
														g_operadores.operaciones
													WHERE
														identificador_operador = '$identificador'
														and id_pais = $idPais 
														and id_producto = $idProducto
														and id_tipo_operacion = $idActividad
														and estado = '$estado';");
				return $res;
			}	
			
			public function buscarProveedoresOperadorProducto ($conexion,$identificador, $idActividad, $idProducto, $estado){
							 	
			$res = $conexion->ejecutarConsulta("SELECT 
													op.identificador_operador,
													op.id_tipo_operacion,
													op.estado,
													op.id_producto,
													top.nombre
												FROM
													g_operadores.proveedores pr,
													g_operadores.operaciones op,
													g_catalogos.tipos_operacion top
												WHERE
													pr.identificador_operador = '$identificador'
													and pr.codigo_proveedor = op.identificador_operador
													and op.id_tipo_operacion = top.id_tipo_operacion
													and pr.id_producto = op.id_producto
													and top.nombre not in ('Exportador', 'Importador')
													and pr.id_producto = $idProducto
													and op.estado IN $estado;");
				
			return $res;
			}
			
			
			public function eliminarProveedoresVUE ($conexion,$idVue){
					
				$res = $conexion->ejecutarConsulta("DELETE FROM
														g_operadores.proveedores
													WHERE
														id_vue = '$idVue';");
			
						return $res;
			}
			
			public function buscarOperadorVUE ($conexion,$idVue){
							
				$res = $conexion->ejecutarConsulta("Select 
														o.*
													from 
														g_operadores.operadores o,
														g_operadores.operaciones op
													where 
														o.identificador = op.identificador_operador
														and id_vue = '$idVue';");
							
						return $res;
			}
			
			public function buscarSitios($conexion,$identificador,$codigoSitio){
				
				/*$codigo = explode('.', $codigoSitio);
				$identificadorOperador = $codigo[0];
				$codigoProvincia = substr($codigo[1], 0,2);
				$codigoSecuencial = substr($codigo[1], -2);
				
				$res = $conexion->ejecutarConsulta("SELECT 
														*
													FROM 
														g_operadores.sitios
													WHERE 
														identificador_operador = '$identificadorOperador'
														AND codigo= '$codigoSecuencial'
														AND codigo_provincia = '$codigoProvincia';");*/
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_operadores.sitios
													WHERE
														--identificador_operador = '$identificador' and
														identificador_operador||'.'||codigo_provincia||codigo = '$codigoSitio';");
				
				return $res;
			}
			
			
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
			
			public function buscarOperacionAreaProducto($conexion, $tipoOperacion, $producto){
				
				
				$res = $conexion->ejecutarConsulta("select 
															*
														from
															g_operadores.operaciones
														where
															id_producto = '$producto' 
															and id_tipo_operacion = $tipoOperacion
															and estado = 'registrado';");
				return $res;
			
			}
			
		public function listarOperacionesRevisionFinancieroRS ($conexion, $provinciaInspector,$estado='pago'){
			
				$res = $conexion->ejecutarConsulta("select
														distinct i.id_operacion  as id_solicitud,
														i.identificador_operador,
														i.estado,
														i.nombre_pais as pais,
														o.razon_social, o.nombre_representante, o.apellido_representante,
														i.id_vue
													from
														g_operadores.operaciones i,
														g_operadores.operadores o,
														g_operadores.productos_areas_operacion sa,
														g_operadores.sitios si,
														g_operadores.areas a
													where
														i.identificador_operador = o.identificador and
														i.id_operacion = sa.id_operacion and
														sa.id_area = a.id_area and
														a.id_sitio = si.id_sitio and
														UPPER(si.provincia) = UPPER('$provinciaInspector') and														
														i.estado in ('$estado')
													order by 1 asc;");
				return $res;
			}
			
			public function listarOperacionesRevisionProvinciaRS ($conexion, $estado, $provinciaInspector){
			
				$res = $conexion->ejecutarConsulta("select
														distinct op.id_operacion as id_solicitud,
														op.identificador_operador,
														op.estado,
														t.nombre,
														o.razon_social, o.nombre_representante, o.apellido_representante,
														op.nombre_producto as producto,
														nombre_pais as pais,
														op.id_vue
													from
														g_operadores.operaciones op,
														g_catalogos.tipos_operacion t,
														g_operadores.operadores o,
														g_operadores.productos_areas_operacion sa,
														g_operadores.sitios si,
														g_operadores.areas a
													where
														op.id_tipo_operacion = t.id_tipo_operacion and
														op.identificador_operador = o.identificador and
														op.id_operacion = sa.id_operacion and
														sa.id_area = a.id_area and
														a.id_sitio = si.id_sitio and
														UPPER(si.provincia) = UPPER('$provinciaInspector') and
														op.estado in ('$estado')
													order by 1 asc;");
				return $res;
			}
			
			public function listarOperacionesAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoSolicitud, $tipoInspector, $tipo, $identificadorOperador = null, $idTipoOperacion=null){
				
				$columnas = '';
				$busqueda = '';
				
				switch ($tipo){

				    case 'SITIOS': $busqueda="and op.identificador_operador = '$identificadorOperador' and t.id_tipo_operacion = $idTipoOperacion ";
				        $columnas = "distinct si.id_sitio, si.nombre_lugar";
				    break;
				    
				    case 'OPERACIONES': $busqueda="and op.identificador_operador = '$identificadorOperador' and t.id_tipo_operacion = $idTipoOperacion GROUP BY si.id_sitio, si.nombre_lugar, op.id_tipo_operacion, t.nombre";
				        $columnas = "distinct si.id_sitio, si.nombre_lugar, min(op.id_operacion) as id_operacion, op.id_tipo_operacion, t.nombre, min(op.fecha_creacion) as fecha_creacion";
				    break;
				}

				$res = $conexion->ejecutarConsulta("select
														".$columnas."
													from
														g_operadores.operaciones op,
														g_catalogos.tipos_operacion t,
														g_operadores.operadores o,
														g_operadores.productos_areas_operacion sa,
														g_operadores.sitios si,
														g_operadores.areas a,
														g_revision_solicitudes.asignacion_coordinador ac
													where
														op.id_tipo_operacion = t.id_tipo_operacion and
														op.identificador_operador = o.identificador and
														op.id_operacion = sa.id_operacion and
														op.id_operacion = ac.id_solicitud and
														sa.id_area = a.id_area and
														a.id_sitio = si.id_sitio and
														op.estado in ('$estado') and
														ac.identificador_inspector = '$identificadorInspector' and
														ac.tipo_solicitud = '$tipoSolicitud' and
														ac.tipo_inspector = '$tipoInspector'
														".$busqueda.";");
				return $res;
			}

			public function abrirOperacionAreasAsignacion ($conexion, $idSolicitud){
			
			$cid = $conexion->ejecutarConsulta("select
													a.nombre_area,
													a.tipo_area
												from
													g_operadores.productos_areas_operacion pao,
													g_operadores.areas a
												where
													pao.id_operacion = $idSolicitud and
													pao.id_area = a.id_area");
			
					while ($fila = pg_fetch_assoc($cid)){
					$prod[] = $fila['nombre_area'].' - '.$fila['tipo_area'];
			}
			
					$res = implode(', ',$prod);
			
					return $res;
			}
			
			public function buscarAreaOperacionXCodigoSitio ($conexion, $identificador, $codigoSitio, $idProducto){
				$cid = $conexion->ejecutarConsulta("select
														a.*
													from
														g_operadores.sitios s,
														g_operadores.areas a,
														g_operadores.operaciones o,
														g_operadores.productos_areas_operacion pao
													where
														s.identificador_operador = '$identificador' and
														s.identificador_operador||'.'||s.codigo_provincia||s.codigo = '$codigoSitio' and
														s.id_sitio = a.id_sitio and
														a.id_area = pao.id_area and
														pao.id_operacion = o.id_operacion and
														o.id_producto = $idProducto and
														o.estado = 'registrado';");
							
						while ($fila = pg_fetch_assoc($cid)){
						$res[] = array(idArea=>$fila['id_area'], nombreArea=>$fila['nombre_area'],
							tipoArea=>$fila['tipo_area']);
					}
												return $res;
			}
			
			public function buscarAreasXCodigoSitio ($conexion, $identificador, $codigoSitio){
				
				/*$codigo = explode('.', $codigoSitio);
				$identificadorOperador = $codigo[0];
				$codigoProvincia = substr($codigo[1], 0,1);
				$codigoSecuencial = substr($codigo[1], -2);
				
				
				$cid = $conexion->ejecutarConsulta("select
														a.*
													from
														g_operadores.sitios s,
														g_operadores.areas a
													where
														s.identificador_operador = '$identificadorOperador' and
														s.codigo = '$codigoSecuencial' and
														s.codigo_provincia = '$codigoProvincia' and
														s.id_sitio = a.id_sitio;");*/
				
				$cid = $conexion->ejecutarConsulta("select
														a.*
													from
														g_operadores.sitios s,
														g_operadores.areas a
													where
														s.identificador_operador||'.'||s.codigo_provincia||s.codigo = '$codigoSitio' and
														--s.identificador_operador = '$identificador' and
														s.id_sitio = a.id_sitio;");
				
				
						 
				while ($fila = pg_fetch_assoc($cid)){
						$res[] = array(idArea=>$fila['id_area'], nombreArea=>$fila['nombre_area'],
  	 									tipoArea=>$fila['tipo_area'], idSitio=>$fila['id_sitio']);
  				}
			
			  return $res;
			}
			
			public function listarNombresProveedoresOperador ($conexion, $identificador){
				$res = $conexion->ejecutarConsulta("SELECT
														distinct(p.codigo_proveedor),
														o.razon_social
													FROM
														g_operadores.operadores o,
														 g_operadores.proveedores p
													WHERE
														o.identificador = p.codigo_proveedor
														and p.identificador_operador = '$identificador';");
						return $res;
			}
			
			public function listarProductoArea ($conexion, $idArea){
				$res = $conexion->ejecutarConsulta("SELECT
														p.id_producto,
														p.nombre_comun
													FROM
														 g_operadores.productos_areas_operacion pao,
														 g_operadores.operaciones o,
														 g_catalogos.productos p
													WHERE
														pao.id_operacion = o.id_operacion
														and o.id_producto = p.id_producto
														and o.estado = 'registrado'
														and id_area = $idArea;");
						return $res;
			}
			
			/*Registro masivo*/
			
	public function listarOperadoresXProvincia ($conexion, $identificador, $provincia){
				$busqueda0 = '';
				$busqueda1 = '';
							
				if ($identificador!="0")
					$busqueda0 = " o.identificador = '".$identificador."'";
				if ($provincia!="0"){
					if($identificador!="0")
					    $busqueda1 = " and UPPER(o.provincia) = UPPER('$provincia')"; 
					else
					    $busqueda1 = " UPPER(o.provincia) = UPPER('$provincia')";
				}				
							  
				$res = $conexion->ejecutarConsulta("select
														distinct o.*
													from
														g_operadores.operadores o
													where
														".$busqueda0."
														".$busqueda1."
													order by o.identificador asc;
												    ");
			
						return $res;
			}
			
			public function guardarNuevoOperador ($conexion, $clasificacion, $ruc,$razon,$nombreLegal,$apellidoLegal,
				$provincia,$canton,$parroquia,$direccion,$telefono1=null,$celular1=null){
					
				$res = $conexion->ejecutarConsulta("INSERT INTO
														g_operadores.operadores (tipo_operador, identificador, razon_social, nombre_representante, apellido_representante,
														provincia, canton, parroquia, direccion, telefono_uno, celular_uno, clave)
													VALUES
														('$clasificacion','$ruc','$razon','$nombreLegal','$apellidoLegal',
														'$provincia','$canton','$parroquia','$direccion','$telefono1','$celular1', md5('$ruc'));");
					
				return $res;
			}
			
			
			////////////////////////////////////////////////////CERTIDICADOS FITOSANITARIOS ///////////////////////////////////////////////////////////////////////
			
			/*public function buscarOperadorProductoActividad ($conexion, $identificador, $idProducto, $idActividad, $estado){
			
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_operadores.operaciones
													WHERE
														identificador_operador = '$identificador'
														and id_producto = $idProducto
														and id_tipo_operacion = $idActividad
														and estado = '$estado';");
						return $res;
			}*/
			
			public function buscarProveedoresOperador($conexion,$identificadorAgencia, $identificadorExportador){
					
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_operadores.proveedores pr
													WHERE
														identificador_operador = '$identificadorAgencia'
														and codigo_proveedor = '$identificadorExportador';");
			
						return $res;
			}
			
			public function listarAreasOperadorXProducto($conexion, $identificador, $idProducto, $estado){
				
				$res = $conexion->ejecutarConsulta("select
														sa.id_area,
														a.nombre_area,
														s.nombre_lugar
													from
														g_operadores.operaciones o,
														g_operadores.productos_areas_operacion sa,
														g_operadores.areas a,
														g_operadores.sitios s
													where
														o.identificador_operador = '$identificador' and
														o.id_producto = '$idProducto' and
														o.id_operacion = sa.id_operacion and
														a.id_area = sa.id_area and
														a.id_sitio = s.id_sitio and
														o.estado = '$estado';");
			
			
									
						return $res;
			}
			
			public function listarOperacionXIdentificadorAreaProducto($conexion, $identificador, $idArea,$idProducto, $estado){
			
				$res = $conexion->ejecutarConsulta("SELECT
														tp.id_tipo_operacion,
														tp.nombre
													FROM
														g_operadores.operaciones o,
														g_operadores.productos_areas_operacion pao,
														g_catalogos.tipos_operacion tp
													WHERE
														o.id_tipo_operacion = tp.id_tipo_operacion
														and o.id_operacion = pao.id_operacion
														and pao.id_area = $idArea
														and o.id_producto = $idProducto
														and o.identificador_operador = '$identificador'
														and o.estado = '$estado';");
				return $res;
			}
			
			
			public function obtenerSecuencialArea ($conexion, $identificador, $codigoArea, $nombreProvincia){
												
				$res = $conexion->ejecutarConsulta("SELECT
														COALESCE(MAX(CAST(secuencial as  numeric(5))),0)+1 as valor 
													FROM
														g_operadores.sitios s,
														g_operadores.areas a	
													WHERE
														a.id_sitio = s.id_sitio
														and s.identificador_operador = '$identificador'
														and upper(s.provincia) = upper('$nombreProvincia')
														and a.codigo = '$codigoArea';");
						return $res;
			}
			
			public function listarDatosXtipoArea($conexion,$identificador, $tipoArea, $estado, $tipo){
				
				$busqueda = '';
				switch ($tipo){
					case 'PROVINCIAS': $busqueda = 'distinct (id_localizacion), l.nombre'; break;
					case 'AREAS': $busqueda = 'distinct(a.id_area), a.nombre_area, s.nombre_lugar, l.id_localizacion'; break;
					case 'PRODUCTOS': $busqueda = 'o.id_producto, o.nombre_producto, a.id_area, l.id_localizacion, p.unidad_medida'; break;
				}
								
				$res = $conexion->ejecutarConsulta("SELECT
														" . $busqueda ."
													FROM
														g_operadores.operaciones o,
														g_operadores.productos_areas_operacion pao,
														g_operadores.areas a,
														g_operadores.sitios s,
														g_catalogos.localizacion l,
														g_catalogos.productos p														
													WHERE
														o.id_operacion = pao.id_operacion														
														and p.id_producto = o.id_producto
														and a.id_area = pao.id_area
														and a.id_sitio = s.id_sitio
														and s.provincia = l.nombre
														and l.categoria = '1'
														and o.identificador_operador = '$identificador'
														and o.estado = '$estado'
														and a.tipo_area = '$tipoArea';");
				return $res;
			}
			
			public function obtenerAreasOperadorPorNombreAreaYsitio($conexion, $idSitio, $nombreArea){
					
				$res = $conexion->ejecutarConsulta("SELECT
														a.id_area,
														a.nombre_area,
														a.tipo_area
													FROM
														g_operadores.sitios s,
														g_operadores.areas a
													WHERE
														s.id_sitio = a.id_sitio
														and s.id_sitio = $idSitio		
														and a.tipo_area = '$nombreArea'
														and a.estado not in ('eliminado','inactivo');");
				return $res;
			}

			public function imprimirLineaOperacion($idSolicitud, $nombreOperacion, $nombreArea){
								
				return '<tr id="R'.$idSolicitud.'">' .
						'<td width="100%">' .
						'<b><u>'.$nombreOperacion.'</u></b> '.$nombreArea.
						'</td>' .
						'<td>' .
						'<form class="borrar" data-rutaAplicacion="registroOperador" data-opcion="eliminarOperacion">' .
						'<input type="hidden" name="idSolicitud" value="' . $idSolicitud . '" >' .
						'<button type="submit" class="icono"></button>' .
						'</form>' .
						'</td>' .
						'</tr>';
			}

			public function ObtenerDatosAreaOperador ($conexion, $idArea){
				
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_operadores.areas 
													WHERE
														id_area = $idArea;");
						return $res;
			}
			
			//TODO: Revisar de que manera se maneja la validación para el reingreso de la operacion.
			public function buscarAreasOperacionProductoXSolicitud($conexion, $idOperacion, $idProducto, $idArea, $identificadorOperador, $estado = 'eliminado'){
								
				$res = $conexion->ejecutarConsulta("select
														*, o.estado as estado_operacion,o.nombre_producto, o.estado_anterior,o.id_operacion
													from
														g_operadores.operaciones o,
														g_operadores.productos_areas_operacion sa
													where
														o.id_tipo_operacion = $idOperacion and
														o.identificador_operador = '$identificadorOperador' and
														o.id_producto IN $idProducto and
														sa.id_area = $idArea and
														o.id_operacion = sa.id_operacion and
														o.estado != '$estado';");
			
									
						return $res;
			}
			
			public function eliminarDatosOperacion ($conexion, $idSolicitud){
				$res = $conexion->ejecutarConsulta("DELETE 
													FROM
														g_operadores.productos_areas_operacion
													WHERE
														id_operacion = $idSolicitud;");
				
				$res = $conexion->ejecutarConsulta("DELETE
													FROM
														g_operadores.operaciones
													WHERE
														id_operacion = $idSolicitud;");
				return $res;
			}
			
			public function obtenerSolicitudesOperadores($conexion, $provincia, $estado, $tipo, $estadoActual, $tipoSolicitud, $identificador = null, $revisionUbicacion=null, $idTipoOperacion=null){

				$columnas = '';
				$busqueda = '';
				
				if($revisionUbicacion == 'provincia'){
				    $busqueda = " and tp.id_tipo_operacion = $idTipoOperacion and UPPER(s.provincia) IN ". $provincia;
				}else if($revisionUbicacion == 'planta_central'){
				    $busqueda = " and tp.id_tipo_operacion =". $idTipoOperacion ;
				}
				
				if($estadoActual == 'financiero'){
					$busqueda = " and UPPER(s.provincia) = UPPER('$provincia')";
				}
				
				switch ($tipo){
					case 'OPERADORES': $busqueda .= " GROUP BY o.identificador ORDER BY MIN(op.fecha_creacion) asc"; 
									   $columnas = "distinct (o.identificador), case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador, MIN(op.fecha_creacion) as fecha_creacion"; 
					break;
					case 'OPERACIONES':  $busqueda .= " and op.identificador_operador= '$identificador' GROUP BY s.id_sitio, s.nombre_lugar, op.id_tipo_operacion, tp.nombre, op.id_vigencia_documento, a.id_area"; 
										 $columnas = "distinct s.id_sitio, s.nombre_lugar, min(op.id_operacion) as id_operacion, op.id_tipo_operacion, tp.nombre, min(op.fecha_creacion) as fecha_creacion, op.id_vigencia_documento"; 
					break;
					case 'SITIOS':  $busqueda .= " and op.identificador_operador= '$identificador'"; 
									$columnas = "distinct s.id_sitio, s.nombre_lugar"; 
					break;
				}

				$res = $conexion->ejecutarConsulta("SELECT
														" . $columnas ."
													FROM
															g_operadores.operadores o,
															g_operadores.operaciones op,
															g_operadores.productos_areas_operacion pao,
															g_operadores.areas a,
															g_operadores.sitios s,
															g_catalogos.tipos_operacion tp
															
														WHERE
															o.identificador = op.identificador_operador and
															op.id_operacion = pao.id_operacion and
															pao.id_area = a.id_area and
															a.id_sitio = s.id_sitio and
															tp.id_tipo_operacion = op.id_tipo_operacion and
															op.estado = '$estado' 
															".$busqueda.";");
				return $res;
			}
			
			
			public function obtenerSolicitudesOperadoresXOrdenPago($conexion, $provincia, $estado, $tipoSolicitud){					
			
				$res = $conexion->ejecutarConsulta("SELECT
														distinct (o.identificador), 
														case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador, 
														MIN(op.fecha_creacion)
													FROM
														g_operadores.operadores o,
														g_operadores.operaciones op,
														g_operadores.productos_areas_operacion pao,
														g_operadores.areas a,
														g_operadores.sitios s,
														g_catalogos.tipos_operacion tp,
														g_financiero.orden_pago orp
													WHERE
														o.identificador = op.identificador_operador and
														op.id_operacion = pao.id_operacion and
														pao.id_area = a.id_area and
														a.id_sitio = s.id_sitio and
														tp.id_tipo_operacion = op.id_tipo_operacion and
														op.estado = '$estado' and
														UPPER(s.provincia) = UPPER('$provincia') and
														orp.identificador_operador = op.identificador_operador and
														orp.estado = 3 and
														orp.tipo_solicitud = '$tipoSolicitud'
													GROUP BY 
														o.identificador 
													ORDER BY 
														MIN(op.fecha_creacion) asc;");
				return $res;
			}
			
			public function obtenerOperadorProductoOperacion($conexion, $idSolicitudes){
				
				if($idSolicitudes == '_agrupar'){
					$idSolicitudes = 0;
				}

				$res = $conexion->ejecutarConsulta("SELECT
														op.id_operacion,
                                                    	o.identificador, 
                                                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador,
                                                    	top.nombre nombre_tipo_operacion,
                                                    	COALESCE(p.nombre_comun,'S/N') as nombre_producto,
                                                    	op.estado,
                                                    	op.id_vue,
                                                    	COALESCE(sp.nombre,'S/N') as nombre_subtipo,
                                                    	COALESCE(tp.nombre,'S/N') as nombre_tipo
													FROM
														g_operadores.operaciones op 
                                                        INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
                                                        INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                                                        LEFT JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                                                        LEFT JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                                                        LEFT JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
													WHERE
														op.id_operacion IN ($idSolicitudes);");
				return $res;
			}
			
			public function obtenerOperadorFinancieroVerificacion($conexion, $identificador, $estado, $provincia, $tipoSolicitud){
								
				$tabla = '';
				$busqueda = '';
				
				if($estado == 'verificacionVUE'){
					$estado = 'verificacion';
					$tabla = ", g_financiero.orden_pago orp";
					$busqueda = "and orp.id_grupo_solicitud = ai.id_grupo and orp.estado = 3 and orp.tipo_solicitud = '$tipoSolicitud'";
				}
				
				/*else if($estado == 'verificacion'){
					if($tipoEstadoOrdenPago == 'estadoComprobante' && $estadoOrdenPago != ''){
						$tabla = ", g_financiero.orden_pago orp";
						$busqueda = "and orp.id_grupo_solicitud = ai.id_grupo and orp.estado = $estadoOrdenPago and orp.tipo_solicitud = '$tipoSolicitud'";
					}else if($tipoEstadoOrdenPago == 'estadoComprobante' && $estadoOrdenPago == ''){
						$tabla = ", g_financiero.orden_pago orp";
						$busqueda = "and orp.id_grupo_solicitud = ai.id_grupo and orp.tipo_solicitud = '$tipoSolicitud'";
					}else if($tipoEstadoOrdenPago == 'estadoSRI'){
						$tabla = ", g_financiero.orden_pago orp";
						$busqueda = "and orp.id_grupo_solicitud = ai.id_grupo and orp.estado_sri = '$estadoOrdenPago' and orp.tipo_solicitud = '$tipoSolicitud'";
					}
					
					if($numeroOrdenPago !=''){
						$busqueda .= " and numero_solicitud = '$numeroOrdenPago'";
					}
				}*/				
				
				$res = $conexion->ejecutarConsulta("SELECT 
														distinct ai.id_grupo, 
														array_to_string(ARRAY(
													            SELECT
													                id_solicitud
													            FROM
													                g_revision_solicitudes.grupos_solicitudes gss
													            WHERE
													                gss.id_grupo = gs.id_grupo ),', ') as id_solicitud,
													   	op.identificador_operador																	
													FROM 
														g_revision_solicitudes.grupos_solicitudes gs,
														g_revision_solicitudes.asignacion_inspector ai,
														g_operadores.operaciones op,
														g_operadores.productos_areas_operacion pao,
														g_operadores.areas a,
														g_operadores.sitios s
														".$tabla."
													WHERE
														gs.id_grupo = ai.id_grupo and
														op.id_operacion = gs.id_solicitud and
														op.id_operacion = pao.id_operacion and
														pao.id_area = a.id_area and
														a.id_sitio = s.id_sitio and
														ai.tipo_solicitud = '$tipoSolicitud' and
														UPPER(s.provincia) = UPPER('$provincia') and 
														op.estado = '$estado' and
														op.identificador_operador = '$identificador'
														".$busqueda.";");
							return $res;
			}
			
			
			public function listarOperacionesVerificacionFinanciero($conexion, $identificador, $estado){
									
				$res = $conexion->ejecutarConsulta("SELECT
														distinct ai.id_grupo,
														array_to_string(ARRAY(
																				SELECT
																					id_solicitud
																				FROM
																					g_revision_solicitudes.grupos_solicitudes gss
																				WHERE
																					gss.id_grupo = gs.id_grupo ),', ') as id_solicitud,
														op.identificador_operador
													FROM
														g_revision_solicitudes.grupos_solicitudes gs,
														g_revision_solicitudes.asignacion_inspector ai,
														g_operadores.operaciones op
													WHERE
														gs.id_grupo = ai.id_grupo and
														op.id_operacion = gs.id_solicitud and
														op.estado = '$estado' and
														op.identificador_operador = '$identificador';");
						return $res;
			}
			
			public function obtenerOperadorSitioInspeccion($conexion, $idSolicitudes){
				
				if($idSolicitudes == '_agrupar'){
					$idSolicitudes = 0;
				}
				
				$res = $conexion->ejecutarConsulta("SELECT
														distinct o.identificador, 
														case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador,
														s.id_sitio,
														s.provincia,
														s.canton,
														s.parroquia,
														s.direccion,
														s.nombre_lugar,
														s.codigo_provincia,
														s.latitud,
														s.longitud,
														s.zona,
														o.tipo_operador,
														op.id_operador_tipo_operacion,
														op.id_historial_operacion
													FROM
														g_operadores.operaciones op,
														g_operadores.productos_areas_operacion pao,
														g_operadores.operadores o,
														g_operadores.sitios s,
														g_operadores.areas a
													WHERE
														op.id_operacion = pao.id_operacion and
														pao.id_area = a.id_area and
														a.id_sitio = s.id_sitio and
														op.identificador_operador = o.identificador and
														op.id_operacion IN ($idSolicitudes);");
				return $res;
			}
			
			public function obtenerOperadorOperacionAreaInspeccion($conexion, $idSolicitudes){
				
				$res = $conexion->ejecutarConsulta("SELECT
														a.nombre_area,
														a.tipo_area,
														tp.nombre as nombre_operacion,
                                                        tp.id_area as area_operacion,
														a.id_area,
														a.superficie_utilizada,
														op.id_operacion
													FROM
														g_operadores.operaciones op,
														g_operadores.productos_areas_operacion pao,
														g_operadores.areas a,
														g_catalogos.tipos_operacion tp														
													WHERE
														op.id_operacion = pao.id_operacion and
														pao.id_area = a.id_area and
														op.id_tipo_operacion = tp.id_tipo_operacion and
														op.id_operacion = $idSolicitudes;");
				return $res;
			}
			
			public function buscarEstadoOperacionArea($conexion, $idOperacion, $identificadorOperador, $idArea, $estado = 'registrado'){
							
				$res = $conexion->ejecutarConsulta("SELECT
														o.*
													FROM
														g_operadores.operaciones o,
														g_operadores.productos_areas_operacion sa
													WHERE
														o.id_tipo_operacion = $idOperacion and
														o.identificador_operador = '$identificadorOperador' and
														sa.id_area IN ($idArea) and
														o.estado = '$estado' and
														o.id_operacion = sa.id_operacion;");
					
				return $res;
			}
			
			public function cambiarEstadoAreaXidSolicitud ($conexion, $idSolicitud, $estado, $observacion){
				
				$res = $conexion->ejecutarConsulta("UPDATE
														g_operadores.productos_areas_operacion
													SET
														observacion ='$observacion',
														estado = '$estado'
													WHERE
														id_operacion = $idSolicitud;");
				return $res;
			}

			public function jsonBuscarOperadores($conexion, $parametroDeBusqueda, $provincia){
				$res = $conexion->ejecutarConsulta("
													select row_to_json(operadores)
													from (
														select array_to_json(array_agg(row_to_json(listado)))
														from (select
															distinct--o.*
															o.identificador
															,o.razon_social
															,concat_ws(', ', o.apellido_representante, o.nombre_representante) as representante
															,concat_ws(', ', o.apellido_tecnico, o.nombre_tecnico) as tecnico
															,o.direccion
															,concat_ws(', ', o.parroquia, o.canton, o.provincia) as localizacion
															,concat_ws('; ', o.telefono_uno, o.telefono_dos) as telefonos
															,concat_ws('; ', o.celular_uno, o.celular_dos) as celulares
															,o.fax
															,o.correo
															------------------------------------
															,o.id_saniflores
															,o.gs1
															,o.registro_orquideas
															,o.registro_madera
														from
															g_operadores.operadores o
															,g_operadores.operaciones op
															,g_operadores.productos_areas_operacion pao
															,g_operadores.areas a
															,g_operadores.sitios s
														where
															(o.identificador like '%$parametroDeBusqueda%'
															or o.razon_social like '%$parametroDeBusqueda%')
											
															and (o.identificador = op.identificador_operador
															and op.id_operacion = pao.id_operacion
															and pao.id_area = a.id_area
															and a.id_sitio = s.id_sitio
															and s.provincia = '$provincia')
														order by
															2
												) as listado)
											as operadores;");
						$json = pg_fetch_assoc($res);
						return json_decode($json[row_to_json],true);
			}
			
/*public function jsonBuscarOperacionesPorProvincia($conexion, $provincia, $area, $canton){

        //$a = ;
        $res = $conexion->ejecutarConsulta("
												select row_to_json(operaciones)
													from (
														select array_to_json(array_agg(row_to_json(listado)))
														from (

														    select
                                                                opr.identificador_operador,
                                                                ac.id_asignacion_coordinador,
                                                                ac.identificador_inspector as inspector_asignado,
                                                                ac.estado as estado_asignacion,
                                                                to_char(ac.fecha_asignacion, 'dd-mm-yyyy') as fecha_asignacion,
                                                                opr.id_operacion,
                                                                opr.id_tipo_operacion,
                                                                tpop.nombre as nombre_operacion,
                                                                tpop.codigo as codigo_operacion,
                                                                tpop.id_area as area_tecnica,
                                                                opr.estado as estado_operacion,
                                                                opr.observacion,
                                                                opr.nombre_pais,
                                                                opr.nombre_producto,
                                                                to_char(opr.fecha_creacion, 'dd-mm-yyyy') as fecha,
                                                                (sit.codigo_provincia || sit.id_sitio) codigo_sitio
                                                            from
                                                                g_catalogos.tipos_operacion tpop,
                                                                g_operadores.operaciones opr left join
                                                                (
                                                                    select *
                                                                    from g_revision_solicitudes.asignacion_coordinador
                                                                    where estado = 'En curso' and tipo_solicitud = 'Operadores' and tipo_inspector = 'Técnico'
                                                                ) ac on (opr.id_operacion = ac.id_solicitud),
                                                                g_operadores.areas are,
                                                                g_operadores.sitios sit,
                                                                g_operadores.productos_areas_operacion pao
                                                            where
                                                                tpop.id_tipo_operacion = opr.id_tipo_operacion and
                                                                pao.id_operacion = opr.id_operacion and
                                                                pao.id_area = are.id_area and
                                                                are.id_sitio = sit.id_sitio and
                                                                tpop.id_area = '$area' and
                                                                upper(sit.provincia) = upper('$provincia') and
                                                                upper(sit.canton) = upper('$canton') and
																opr.id_tipo_operacion not in (28,29,30,31,32,33,38,39)
                                                            order by
                                                                tpop.id_area,
                                                                opr.estado,
                                                                ac.estado,
                                                                opr.fecha_aprobacion
												) as listado)
											as operaciones;");
        $json = pg_fetch_assoc($res);
        return json_decode($json[row_to_json],true);
    }*/		
		
	public function jsonBuscarOperacionesPorProvincia($conexion, $provincia, $area, $canton){

        $res = $conexion->ejecutarConsulta("
												select row_to_json(operaciones)
													from (
														select array_to_json(array_agg(row_to_json(listado)))
														from (
														    select
                                                                distinct opr.identificador_operador,
                                                                null as id_asignacion_coordinador,
                                                                null as inspector_asignado,
                                                                null as estado_asignacion,
                                                                null as fecha_asignacion,
                                                                max(opr.id_operacion) as id_operacion,
                                                                opr.id_tipo_operacion,
                                                                tpop.nombre as nombre_operacion,
                                                                tpop.codigo as codigo_operacion,
                                                                tpop.id_area as area_tecnica,
                                                                null as estado_operacion,
																null as observacion,
                                                                opr.nombre_pais,
																null as nombre_producto,
																null as fecha,
                                                                (sit.codigo_provincia || sit.codigo) codigo_sitio
                                                            from
                                                                g_catalogos.tipos_operacion tpop,
                                                                g_operadores.operaciones opr,
                                                                g_operadores.areas are,
                                                                g_operadores.sitios sit,
                                                                g_operadores.productos_areas_operacion pao
                                                            where
                                                                tpop.id_tipo_operacion = opr.id_tipo_operacion and
                                                                pao.id_operacion = opr.id_operacion and
                                                                pao.id_area = are.id_area and
                                                                are.id_sitio = sit.id_sitio and
                                                                tpop.id_area = '$area' and
                                                                upper(sit.provincia) = upper('$provincia') and
																upper(sit.canton) IN $canton and
																opr.id_tipo_operacion not in (28,29,30,31,32,33,38,39)
																and opr.estado in ('registrado','registradoObservacion', 'inspeccion')
														    group by 
																opr.identificador_operador,
																opr.id_tipo_operacion,
																tpop.nombre,
																tpop.codigo,
																tpop.id_area,
																opr.nombre_pais,
																codigo_sitio,
        														are.id_area
                                                            order by
                                                                opr.identificador_operador,
                                                                opr.id_tipo_operacion
												) as listado)
											as operaciones;");
        $json = pg_fetch_assoc($res);
        return json_decode($json[row_to_json],true);
    }
			
    /*public function jsonBuscarAreasPorProvincia($conexion, $provincia, $area, $canton){
    $res = $conexion->ejecutarConsulta("
													select row_to_json(areas)
													from (
														select array_to_json(array_agg(row_to_json(listado)))
														from (
														    select
                                                                distinct
                                                                    pao.id_operacion,
                                                                    sit.id_sitio,
                                                                    are.id_area,
                                                                    are.nombre_area,
                                                                    are.tipo_area,
                                                                    are.superficie_utilizada
                                                                from
                                                                    g_operadores.areas are,
                                                                    g_operadores.sitios sit,
                                                                    g_operadores.productos_areas_operacion pao,
                                                                    g_operadores.operaciones o,
								                                    g_catalogos.tipos_operacion tope
                                                                where
                                                                    pao.id_area = are.id_area and
                                                                    upper(sit.provincia) = upper('$provincia') and
    																upper(sit.canton) = upper('$canton') and
                                                                    are.id_sitio = sit.id_sitio  and
                                                                    pao.id_operacion = o.id_operacion and
                                                                    o.id_tipo_operacion = tope.id_tipo_operacion and
                                                                    tope.id_area = '$area'

                                                                order by
                                                                    pao.id_operacion,
                                                                    sit.id_sitio,
                                                                    are.nombre_area
												) as listado)
											as areas;");
    $json = pg_fetch_assoc($res);
    return json_decode($json[row_to_json],true);
}*/

	public function jsonBuscarAreasPorProvincia($conexion, $provincia, $area, $canton){
		$res = $conexion->ejecutarConsulta("
													select row_to_json(areas)
													from (
														select array_to_json(array_agg(row_to_json(listado)))
														from (
														    select
                                                                distinct
                                                                    max(pao.id_operacion) as id_operacion,
                                                                    sit.id_sitio,
                                                                    are.id_area,
                                                                    --are.nombre_area ||' (Estado: '||CASE WHEN are.estado = 'creado' THEN 'Registrado' ELSE  'No habilitado' END||', Código: '||(are.codigo || are.secuencial)||')' as nombre_area,
																	are.nombre_area,
                                                                    are.tipo_area,
                                                                    are.superficie_utilizada,
																	CASE WHEN are.estado = 'creado' THEN 'Registrado' ELSE  'No habilitado' END as estado_area,
																	(are.codigo || are.secuencial) as codigo_area,
																	o.id_tipo_operacion
                                                                from
                                                                    g_operadores.areas are,
                                                                    g_operadores.sitios sit,
                                                                    g_operadores.productos_areas_operacion pao,
                                                                    g_operadores.operaciones o,
								                                    g_catalogos.tipos_operacion tope
                                                                where
                                                                    pao.id_area = are.id_area and
                                                                    upper(sit.provincia) = upper('$provincia') and
																	upper(sit.canton) IN $canton and
                                                                    are.id_sitio = sit.id_sitio  and
                                                                    pao.id_operacion = o.id_operacion and
                                                                    o.id_tipo_operacion = tope.id_tipo_operacion and
                                                                    tope.id_area = '$area' and
																	o.id_tipo_operacion not in (28,29,30,31,32,33,38,39)
																	and o.estado in ('registrado','registradoObservacion','inspeccion')
																group by
																	sit.id_sitio,
																	are.id_area,
																	o.id_tipo_operacion
                                                                order by
                                                                    --pao.id_operacion,
                                                                    sit.id_sitio,
                                                                   	are.nombre_area
												) as listado)
											as areas;");
		$json = pg_fetch_assoc($res);
		return json_decode($json[row_to_json],true);
	}

    public function jsonBuscarSitiosPorProvincia($conexion, $provincia, $area, $canton){
        $res = $conexion->ejecutarConsulta("
												select row_to_json(areas)
													from (
														select array_to_json(array_agg(row_to_json(listado)))
														from (
														    select
                                                                distinct
                                                                    sit.identificador_operador,
                                                                    sit.id_sitio,
                                                                    sit.nombre_lugar as nombre_sitio,
                                                                    sit.canton,
                                                                    sit.parroquia,
                                                                    sit.direccion,
                                                                    sit.referencia,
                                                                    sit.longitud,
                                                                    sit.latitud,
                                                                    sit.telefono
                                                                    --,sit.archivo
                                                                from
                                                                    g_operadores.areas are,
                                                                    g_operadores.sitios sit,
                                                                    g_operadores.productos_areas_operacion pao,
                                                                    g_operadores.operaciones o,
								                                    g_catalogos.tipos_operacion tope
                                                                where
                                                                    pao.id_area = are.id_area and
                                                                    upper(sit.provincia) = upper('$provincia') and
        															upper(sit.canton) IN $canton and
                                                                    are.id_sitio = sit.id_sitio  and
                                                                    pao.id_operacion = o.id_operacion and
                                                                    o.id_tipo_operacion = tope.id_tipo_operacion
                                                                    and tope.id_area = '$area'
        															and o.id_tipo_operacion not in (28,29,30,31,32,33,38,39)
																	and o.estado in ('registrado','registradoObservacion','inspeccion')
                                                                order by
                                                                    1
												) as listado)
											as areas;");
        $json = pg_fetch_assoc($res);
        return json_decode($json[row_to_json],true);
    }
			
			public function obtenerPaisXtipoOperacionArea($conexion, $codigoOperacion, $idArea){
			
				$res = $conexion->ejecutarConsulta("SELECT 
														o.identificador_operador,
														o.id_producto,
														o.nombre_producto,
														o.id_pais,
														o.nombre_pais,
														tp.id_tipo_operacion,
														tp.nombre as nombre_tipo_operacion
													FROM 
														g_operadores.operaciones o,
														g_catalogos.tipos_operacion tp
													WHERE
														o.id_tipo_operacion = tp.id_tipo_operacion and
														tp.codigo = '$codigoOperacion' and
														tp.id_area = '$idArea';");
				return $res;
			}
			
			public function listarOperadoresEmpresa($conexion, $identificador){
									
				$res = $conexion->ejecutarConsulta("SELECT
														identificador,
														case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador
													FROM
														g_operadores.operadores o
													WHERE 
														identificador = '$identificador'
													ORDER BY
															2");
				return $res;
			}
			
	public function obtenerOperador($conexion, $identificador, $area, $provincia, $idTipoOperacion='Todas') {
			
            $res = $conexion->ejecutarConsulta("
                        SELECT row_to_json (operador)
                        FROM (
                            SELECT
                                o1.* ,
                                (
                                    SELECT array_to_json(array_agg(row_to_json(operaciones_n2)))
                                    FROM (
                                            select
                                                distinct on(topc2.id_area, topc2.nombre, topc2.id_tipo_operacion) topc2.*

                                            from
                                                g_operadores.operadores opr2
                                                , g_operadores.operaciones opc2
                                                , g_catalogos.tipos_operacion topc2
                                            where
                                                opr2.identificador = opc2.identificador_operador
                                                and opc2.id_tipo_operacion = topc2.id_tipo_operacion
                                                and opr2.identificador = o1.identificador" .
                                                ($area <> "Todas" ? " and topc2.id_area='$area'" : "") .
                                                ($idTipoOperacion <> "Todas" ? " and topc2.id_tipo_operacion='$idTipoOperacion'" : "") .
                                           "
                                            order by
                                                topc2.id_area, topc2.nombre ) operaciones_n2
                                ) operaciones
                            FROM
                                g_operadores.operadores o1
                            WHERE
                                o1.identificador = '$identificador'
                        ) as operador");
            
            return pg_fetch_assoc($res);
        }
        
 public function obtenerDatosPorOperacion($conexion, $identificador, $tipo_operacion,$area) {
 	
 	set_time_limit (120);
 	 			
           $res = $conexion->ejecutarConsulta("
                            SELECT array_to_json(array_agg(row_to_json (areas)))
                            FROM (
                                select
                                    distinct on (a3.id_area) a3.*
                                    , a3.codigo codigo_area
                                    , s3.*
                                    , s3.codigo codigo_sitio
                                    , aop.unidad_medida
									,
                                    (
                                        SELECT array_to_json(array_agg(row_to_json(productos_n4)))
                                        FROM (
                                           select
                                                p.*
                                                , pao.estado estado_area
                                                , opr.id_operacion
                                                , opr.estado estado_operacion
                                                , to_char(opr.fecha_creacion,'DD-MM-YYYY (HH24:MI)') as fecha_creacion
                                                , sp.nombre as subtipo
                                                , tp.nombre as tipo
                                                , tp.id_area as area
                                                , COALESCE(opr.nombre_pais,'N/A')
                                           from
                                                g_operadores.productos_areas_operacion pao INNER JOIN g_operadores.operaciones opr ON pao.id_operacion = opr.id_operacion
                                                LEFT JOIN  g_catalogos.productos p ON  opr.id_producto = p.id_producto
                                                LEFT JOIN g_catalogos.subtipo_productos sp  ON  p.id_subtipo_producto = sp.id_subtipo_producto
                                                LEFT JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
                                           where
           										opr.id_tipo_operacion = $tipo_operacion
                                                and pao.id_area = a3.id_area
                                                ".($area!='Todas'?" and tp.id_area='$area'":'').") productos_n4

                                    ) productos
                                from
                                    g_operadores.operaciones opc3
                                    , g_operadores.productos_areas_operacion pao3
                                    , g_operadores.areas a3
                                    , g_operadores.sitios s3
									, g_catalogos.areas_operacion aop
                                where
                                    s3.id_sitio = a3.id_sitio
                                    and opc3.id_operacion = pao3.id_operacion
                                    and pao3.id_area = a3.id_area
									and a3.tipo_area = aop.nombre
                                    and opc3.id_tipo_operacion = $tipo_operacion
                                    and opc3.identificador_operador = '$identificador'
                                order by
                                    a3.id_area
                            ) as areas");
            return pg_fetch_assoc($res);
        }
        
        public function obtenerDatosPorArea($conexion, $identificador, $idAreaSeguimiento) {
        	
        	set_time_limit (120);
        	
        	$res = $conexion->ejecutarConsulta("
                            SELECT array_to_json(array_agg(row_to_json (areas)))
                            FROM (
                                select
                                    distinct on (a3.id_area) a3.*
                                    , a3.codigo codigo_area
                                    , s3.*
                                    , s3.codigo codigo_sitio
                                    , aop.unidad_medida
									,
                                    (
                                        SELECT array_to_json(array_agg(row_to_json(productos_n4)))
                                        FROM (
                                           select
                                                p.*
                                                , pao.estado estado_area
                                                , opr.id_operacion
                                                , opr.estado estado_operacion
                                                , to_char(opr.fecha_creacion,'DD-MM-YYYY (HH24:MI)') as fecha_creacion
                                                , sp.nombre as subtipo
                                                , tp.nombre as tipo
                                                , tp.id_area as area
                                                , COALESCE(opr.nombre_pais,'N/A')
                                           from
                                                g_operadores.productos_areas_operacion pao 
												INNER JOIN g_operadores.operaciones opr ON pao.id_operacion = opr.id_operacion
                                                LEFT JOIN  g_catalogos.productos p ON  opr.id_producto = p.id_producto
                                                LEFT JOIN g_catalogos.subtipo_productos sp  ON  p.id_subtipo_producto = sp.id_subtipo_producto
                                                LEFT JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
                                           where
           										pao.id_area = a3.id_area) productos_n4
        		
                                    ) productos
                                from
                                    g_operadores.operaciones opc3
                                    , g_operadores.productos_areas_operacion pao3
                                    , g_operadores.areas a3
                                    , g_operadores.sitios s3
									, g_catalogos.areas_operacion aop
                                where
                                    s3.id_sitio = a3.id_sitio
                                    and opc3.id_operacion = pao3.id_operacion
                                    and pao3.id_area = a3.id_area
									and a3.tipo_area = aop.nombre
									and s3.identificador_operador || '' || '.' || '' || s3.codigo_provincia || '' || s3.codigo || '' || a3.codigo || '' || a3.secuencial = '$idAreaSeguimiento'
                                    and opc3.identificador_operador = '$identificador'
                                order by
                                    a3.id_area
                            ) as areas");
        	return pg_fetch_assoc($res);
        }
		
		/*public function filtrarOperadores($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area) {
			
			$sql = "select
			distinct opr.*
			from
			g_operadores.operadores opr
                        , g_operadores.sitios s
                        , g_operadores.operaciones opc
                        , g_catalogos.tipos_operacion topc
                    where
                        opr.identificador = s.identificador_operador
                        and opr.identificador = opc.identificador_operador
                        and opc.id_tipo_operacion = topc.id_tipo_operacion
                        and "
                . ($tipoDeBusqueda == 'ruc' ? "opr.identificador = '$textoDeBusqueda'" : "upper(opr.razon_social) like upper('%$textoDeBusqueda%')")
			                . ($provincia != 'Todas' ? " and s.provincia = '$provincia'" : "")
			                		. ($area != 'Todas' ? " and topc.id_area = '$area'" : "")
			                				. " order by
			                				opr.identificador";
			                				$res = $conexion->ejecutarConsulta($sql);
			
			                				return $res;
        }*/
        
        public function listarDocumentosAnexos($conexion, $usuario) {
        	$res = $conexion->ejecutarConsulta("
								        		select
								        			oda.id_documento_anexo as id,
								        			oda.descripcion as descr,
								        			*
								        		from
								        			g_operadores.documentos_anexos oda
								        			, g_catalogos.documentos_anexos cda
								        		where
								        			oda.identificador_operador = '$usuario'
								        			and oda.id_documento = cda.id_documento_anexo 
        											and cda.estado = 'activo'
        											and oda.estado = 'activo'
								        		order by
								        			nombre_documento, fecha;
								        			");
        			return $res;
        }
        
        public function guardarDocumento($conexion, $usuario, $tipo, $descripcion, $archivo) {
        	$res = $conexion->ejecutarConsulta("
							        			insert into g_operadores.documentos_anexos
							        						(identificador_operador, id_documento, descripcion, ruta_documento, estado)
							        			values
							        						('$usuario', $tipo, '$descripcion', '$archivo', 'activo');");
        	return $res;
        }			
        
        public function abrirDocumento($conexion, $documento) {
        	$res = $conexion->ejecutarConsulta("
								        		select
								        			oda.id_documento_anexo as id,
								        			oda.descripcion as descr,
								        			*
								        		from
								        			g_operadores.documentos_anexos oda
								        			, g_catalogos.documentos_anexos cda
								        		where
								        			oda.id_documento = cda.id_documento_anexo and
								        			oda.id_documento_anexo = $documento;");
        	return $res;
        }
        
        public function listarOperacionesQueRequierenAnexos($conexion, $usuario) {
        $res = $conexion->ejecutarConsulta("select
												distinct min(o.id_operacion) as id_operacion, 
												o.identificador_operador, 
												o.estado, 
												o.id_tipo_operacion, 
												top.nombre as nombre_tipo_operacion, 
												st.provincia, 
												st.id_sitio, 
												st.nombre_lugar 
											from
												g_operadores.operaciones o,
												g_catalogos.tipos_operacion top,
												g_catalogos.tipos_operacion_requerimientos topr,
												g_operadores.productos_areas_operacion sa,
												g_operadores.areas a,
												g_operadores.sitios st
											where
												topr.id_tipo_operacion = top.id_tipo_operacion and
												topr.id_tipo_operacion = o.id_tipo_operacion and
												o.id_operacion = sa.id_operacion and
												sa.id_area = a.id_area and
												a.id_sitio = st.id_sitio and
												o.identificador_operador = '$usuario' and
        										topr.estado = 'activo' and
												o.estado IN ('cargarAdjunto','subsanacion')
        									group by o.identificador_operador, o.estado, o.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area");
        	return $res;
        }

        public function guardarDocumentoOperacion($conexion, $idDocumento, $idOperacion, $idTipoDomentoOperacion, $idOperadorTipoOperacion, $idHistorialOperacion){
        	
        	$res = $conexion->ejecutarConsulta("INSERT INTO 
        												g_operadores.operaciones_anexos(id_documento_anexo, id_operacion, id_tipo_operacion_requisito, id_operador_tipo_operacion, id_historial_operacion, estado)
    											VALUES 
        												($idDocumento, $idOperacion, $idTipoDomentoOperacion, $idOperadorTipoOperacion, $idHistorialOperacion, 'activo');");
        	return $res;
        }
        
        public function obtenerDocumentosAdjuntoXoperacion($conexion, $idOperacion){
        	        	
        	$res = $conexion->ejecutarConsulta("SELECT
													topr.titulo,
													topr.descripcion,
													da.ruta_documento
												FROM
													g_operadores.operaciones o,
													g_catalogos.tipos_operacion_requerimientos topr,
													g_operadores.operaciones_anexos oa,
													g_operadores.documentos_anexos da
												WHERE
												
													oa.id_operacion = o.id_operacion and
													oa.id_documento_anexo = da.id_documento_anexo and
													oa.id_tipo_operacion_requisito = topr.id_tipo_operacion_requisito
        											and da.estado = 'activo'
        											and oa.estado = 'activo'
        											and topr.estado = 'activo'
													and o.id_operacion = $idOperacion;");
        	
        	return $res;
        	
        }
        
        public function obtenerDocumentosAdjuntoPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion){
        
        	$res = $conexion->ejecutarConsulta("SELECT
								        			topr.titulo,
								        			topr.descripcion,
								        			da.ruta_documento
								        		FROM
								        			g_operadores.operaciones o,
								        			g_catalogos.tipos_operacion_requerimientos topr,
								        			g_operadores.operaciones_anexos oa,
								        			g_operadores.documentos_anexos da
								        		WHERE
								        			oa.id_operacion = o.id_operacion and
								        			oa.id_documento_anexo = da.id_documento_anexo and
								        			oa.id_tipo_operacion_requisito = topr.id_tipo_operacion_requisito
								        			and da.estado = 'activo'
								        			and oa.estado = 'activo'
								        			and topr.estado = 'activo'
								        			and o.id_operador_tipo_operacion = $idOperadorTipoOperacion;");
        	 
        	return $res;
        	 
        }
        
        public function buscarProductoProveedor ($conexion,$identificadorOperador, $identificadorProveedor, $idProducto, $idPais){

        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.proveedores pr
								        		WHERE
        											codigo_proveedor = '$identificadorProveedor' and
        											identificador_operador = '$identificadorOperador' and
        											id_producto = '$idProducto' and
        											id_pais = '$idPais';");
        
        			return $res;
        }
        
        public function  verificarCorreoElectronicoUsuarioExterno($conexion, $identificador, $mail){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.operadores
								        		WHERE
								        			identificador = '$identificador' and
								        			correo like '%$mail%'");
        	
        	return $res;
        }
        
        public function filtrarOperadoresPorTexto($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area, $idTipoOperacion) {

        	$sql = "select
						distinct opr.*
					from
						g_operadores.operadores opr
                        , g_operadores.sitios s
                        , g_operadores.operaciones opc
                        , g_catalogos.tipos_operacion topc
                    where
                        opr.identificador = s.identificador_operador
                        and opr.identificador = opc.identificador_operador
                        and opc.id_tipo_operacion = topc.id_tipo_operacion
                        and "
        			. ($tipoDeBusqueda == 'ruc' ? "opr.identificador = '$textoDeBusqueda'" : "upper(opr.razon_social) like upper('%$textoDeBusqueda%')")
        			. ($provincia != 'Todas' ? " and s.provincia = '$provincia'" : "")
        			. ($area != 'Todas' ? " and topc.id_area = '$area'" : "")
        			. ($idTipoOperacion != 'Todas' ? " and topc.id_tipo_operacion = '$idTipoOperacion'" : "")
        			. " order by opr.identificador";

        	$res = $conexion->ejecutarConsulta($sql);
        
        	return $res;
        }
        
        
        public function filtrarOperadoresPorOperacion($conexion, $provincia, $area ,$operacion) {
            
        	$sql = "
                SELECT
                    distinct opr.identificador
                    ,opr.razon_social
                    ,topc.nombre as operacion
                    ,topc.id_area
                    --,opc.id_operacion
                    --,opc.estado as estado_operacion
                    --,pao.estado as estado_area
                    ,s.provincia
                FROM
                    g_operadores.operadores opr
                    , g_operadores.operaciones opc
	                , g_catalogos.tipos_operacion topc
                    , g_operadores.productos_areas_operacion pao
                    , g_operadores.areas a
                    , g_operadores.sitios s
                WHERE
                    opr.identificador = opc.identificador_operador
                    and opc.id_operacion = pao.id_operacion
	                and topc.id_tipo_operacion = opc.id_tipo_operacion
                    and pao.id_area = a.id_area
                    and a.id_sitio = s.id_sitio"
        			. ($operacion != 'Todas'?" and opc.id_tipo_operacion = $operacion":"")
        			. ($area != 'Todas'?" and topc.id_area = '$area'":"")
        			. ($provincia != 'Todas'?" and s.provincia = '$provincia'":"")
        			."
                ORDER BY
                    opr.razon_social
                    --,estado_operacion
                    --,estado_area
                    ,provincia
                    ,operacion
            ";
        
        
        	$res = $conexion->ejecutarConsulta($sql);
        
        	return $res;
        }
		
		 public function listarTipoOperacionArea($conexion){
        	
        	$query = $conexion->ejecutarConsulta("select distinct
        											id_area,(CASE id_area
												    WHEN 'IAP' THEN 'Registro de insumos plaguicidas'
												    WHEN 'SV' THEN 'Sanidad vegetal'
												    WHEN 'LT' THEN 'Laboratorios'
												    WHEN 'SA' THEN 'Sanidad animal'
												    WHEN 'IAV' THEN 'Registro de insumos veterinarios'
        			 								WHEN 'IAF' THEN 'Registro de insumos fertilizantes'
        											WHEN 'IAPA' THEN 'Registro de insumos para plantas de autoconsumo'
													WHEN 'AI' THEN 'Inocuidad de alimentos'
												    END) as area_operacion from g_catalogos.tipos_operacion order by id_area;");
        
        	while ($fila = pg_fetch_assoc($query)){
        		$res[] = array(area_operacion=>$fila['area_operacion'],
        				id_area=>$fila['id_area']);
        	}
        	return $res;
        
        }
        
        public function actualizarDatosOperadorMasivo($conexion, $identificador,$razon,$nombreLegal,$apellidoLegal){
        	
        	$res = $conexion->ejecutarConsulta("update
							        				g_operadores.operadores
							        			set
								        			razon_social='$razon',
								        			nombre_representante='$nombreLegal',
								        			apellido_representante='$apellidoLegal'
							        			where
							        				identificador='$identificador';");
        	return $res;
        	
        }
        
        public function actualizarRazonSocialOperador($conexion, $identificador,$razon){
        	
        	$res = $conexion->ejecutarConsulta("update
							        				g_operadores.operadores
							        			set
								        			razon_social='$razon'
							        			where
							        				identificador='$identificador';");
        	return $res;
        	
        }
        
        public function actualizarRepresentanteOperador($conexion, $identificador,$nombreLegal,$apellidoLegal){
        	
        	$res = $conexion->ejecutarConsulta("update
							        				g_operadores.operadores
							        			set
								        			nombre_representante='$nombreLegal',
								        			apellido_representante='$apellidoLegal'
							        			where
							        				identificador='$identificador';");
        	return $res;
        	
        }
        
        public function buscarAreaOperacionXSitio ($conexion, $identificador, $sitio){
        	
        	$res = $conexion->ejecutarConsulta("select
								        			pao.id_producto_area_operacion, pao.id_area,pao.id_operacion,ar.nombre_area,
								        			ar.tipo_area,ar.secuencial, op.identificador_operador ,top.nombre ,
								        			si.id_sitio,si.nombre_lugar, si.codigo,pr.id_producto, pr.nombre_comun
							        			from
								        			g_operadores.areas ar ,g_operadores.productos_areas_operacion pao, g_operadores.operaciones op,
								        			g_operadores.sitios si, g_catalogos.productos pr, g_catalogos.tipos_operacion top
							        			where
								        			op.identificador_operador=si.identificador_operador and ar.id_sitio=si.id_sitio and
								        			op.id_operacion= pao.id_operacion and top.id_tipo_operacion= op.id_tipo_operacion
								        			and ar.id_area=pao.id_area and pr.id_producto=op.id_producto
								        			and si.id_sitio=$sitio and si.identificador_operador='$identificador';");
			return $res;
			
        }
       
        public function buscarAreaOperacionXidSitio ($conexion, $tipoArea, $idSitio){
        	 
        	$res = $conexion->ejecutarConsulta("select
													a.*
												from
													g_operadores.areas a
												where
													a.id_sitio=$idSitio and
													tipo_area = '$tipoArea';");
        	return $res;
        		
        }
        
        public function buscarOperacionXidProducto($conexion, $identificador, $idSitio, $idTipoOperacion,$idProducto, $estado = 'eliminado'){
        	
        	$res = $conexion->ejecutarConsulta("select
													a.*
												from
													g_operadores.sitios s,
													g_operadores.areas a,
													g_operadores.operaciones o,
													g_operadores.productos_areas_operacion pao
												where
													s.id_sitio = a.id_sitio and
													a.id_area = pao.id_area and
													pao.id_operacion = o.id_operacion and
													o.id_tipo_operacion= $idTipoOperacion and
													s.id_sitio= $idSitio and
													o.id_producto = $idProducto and
													s.identificador_operador = '$identificador' and
													o.estado != '$estado';");
                	 
        	return $res;
        }
        
        public function obtenerEstadoFlujoOperacion($conexion, $idFlujo, $idFase){
        	        	 
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.flujos_operaciones
								        		WHERE
								        			id_flujo = $idFlujo and
        											id_fase = $idFase;");
        
        			return $res;
        }
        
        public function obtenerEstadoActualFlujoOperacion($conexion, $idFlujo, $estado){
        
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.flujos_operaciones
								        		WHERE
								        			id_flujo = $idFlujo and
								        			estado = '$estado';");
        
        	return $res;
        }
        
        public function obtenerIdFlujoXOperacion($conexion, $idOperacion){
        
        	$res = $conexion->ejecutarConsulta("SELECT
								        			top.id_flujo_operacion
								        		FROM
								        			g_operadores.operaciones op,
        											g_catalogos.tipos_operacion top
								        		WHERE
        											op.id_tipo_operacion = top.id_tipo_operacion 
        											and op.id_operacion = $idOperacion;");
        
        	return $res;
        }
        
        public function abrirOperacionXid($conexion, $idOperacion){
        	        
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.operaciones 
								        		WHERE
								        			id_operacion = $idOperacion;");
        
        	return $res;
        }
        
        public function notificarEliminarDocumentoAnexoOperacion($conexion, $idDocumento) {
        	        	
        	$res = $conexion->ejecutarConsulta("SELECT
													oda.id_documento_anexo,
													op.estado
												FROM
													g_operadores.documentos_anexos oda
													, g_operadores.operaciones_anexos opa
													, g_operadores.operaciones op
												WHERE
													oda.id_documento_anexo = opa.id_documento_anexo 
													and opa.id_operacion = op.id_operacion 
													and oda.id_documento_anexo = $idDocumento;");
        	return $res;
        }
        
        public function eliminarDocumentoAnexo($conexion, $idDocumento) {
        	$res = $conexion->ejecutarConsulta("UPDATE 
        											g_operadores.documentos_anexos
        										SET 
        											estado = 'inactivo'
        										WHERE 
        											id_documento_anexo = $idDocumento ;");
        	return $res;
        }
        
        public function actualizarEstadoDocumentoXoperacion($conexion, $idOperacion){
        	 
        	$res = $conexion->ejecutarConsulta("UPDATE 
        											g_operadores.operaciones_anexos 
        										SET
        											estado = 'inactivo'
        										WHERE
        											id_operacion = $idOperacion;");
        			return $res;
        }
        
        public function obtenerRegistrosOperadorValidar ($conexion){
        
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.operadores
								        		WHERE
								        			--validacion_sri is null
													identificador in ('1722551049','1001540713001','1001540713')
        										LIMIT 20;");
        				
        			return $res;
        }
        
        public function actualizarRegistrosOperadorValidar($conexion,$identificador,$resultado){
        
        	$res = $conexion->ejecutarConsulta("UPDATE
								        			g_operadores.operadores
								        		SET
								        			validacion_sri = '$resultado'
								        		WHERE
								        			identificador = '$identificador';");
        
        	return $res;
        }
        
 		public function guardarOperacionVariedad($conexion, $idOperacion, $idVariedad){
        	$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.operaciones_variedades(id_operacion, id_variedad)
        										VALUES('$idOperacion','$idVariedad');");
        	return $res;
        }
        
        public function imprimirOperacionVariedad($idOperacion, $idVariedad, $nombreTipoOperacion, $nombreVariedad){
        	return '<tr id="R' . $idOperacion . '-'.$idVariedad.'">' .
        			'<td width="100%">' .
        			'<b>Operación:</b> '.$nombreTipoOperacion .' <b>Variedad: </b>'. $nombreVariedad.
        			'</td>' .
        			'<td>' .
        			'<form class="borrar" data-rutaAplicacion="registroOperador" data-opcion="eliminarOperacionVariedad">' .
        			'<input type="hidden" name="idOperacion" value="' . $idOperacion . '" >' .
        			'<input type="hidden" name="idVariedad" value="' . $idVariedad . '" >' .
        			'<button type="submit" class="icono"></button>' .
        			'</form>' .
        			'</td>' .
        			'</tr>';
        }
        
        public function listarVariedadesOperaciones ($conexion,$idOperacion){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			opv.id_operacion_variedad,
								        			opv.id_operacion, opv.id_variedad,
								        			top.nombre nombretipooperacion,
								        			v.nombre nombrevariedad
								        		FROM
								        			g_operadores.operaciones_variedades opv,
								        			g_catalogos.variedades v,
								        			g_operadores.operaciones op,
								        			g_catalogos.tipos_operacion top
								        		WHERE
								        			opv.id_operacion=op.id_operacion
								        			and opv.id_variedad=v.id_variedad
								        			and op.id_tipo_operacion=top.id_tipo_operacion
								        			and op.id_operacion='$idOperacion';");
        			return $res;
        }
        
        public function quitarVariedadOperacion($conexion, $idOperacion,$idVariedad){
        	$res = $conexion->ejecutarConsulta("DELETE FROM
								        			g_operadores.operaciones_variedades
								        		WHERE
								        			id_operacion='$idOperacion'
								        			and id_variedad='$idVariedad';");
        			return $res;
        			 
        }
        
        public function buscarOperacionesVariedades ($conexion, $idOperacion, $idVariedad){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.operaciones_variedades
								        		WHERE
								        			id_operacion = $idOperacion
								        			and id_variedad= $idVariedad;");
        	return $res;
        }
        
        public function buscarOperacionesMultiplesVariedades ($conexion, $idOperacion){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.operaciones op,
								        			g_catalogos.productos_multiples_variedades pmv
								        		WHERE
								        			op.id_tipo_operacion=pmv.id_tipo_operacion
								        			and op.id_producto=pmv.id_producto
								        			and op.id_operacion='$idOperacion'");
        			return $res;
        }
        
        public function buscarExisteOperacionVariedad ($conexion, $idOperacion){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.operaciones_variedades ov
								        		WHERE
								        			ov.id_operacion='$idOperacion';");
        			return $res;
        }
        
        public function buscarVariedadOperacionProducto($conexion, $idtipoOperacion , $idProducto){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_catalogos.productos_multiples_variedades pmv
								        		WHERE
								        			pmv.id_tipo_operacion='$idtipoOperacion'
								        			and pmv.id_producto='$idProducto';");
        	return $res;
        	 
        }
        
        public function buscarListaOperacionesEstadoCargarIA($conexion,$idOperacion){
        	$res = $conexion->ejecutarConsulta("SELECT
								        			p.id_producto,
								        			p.nombre_comun,
								        			o.id_operacion,
								        			top.id_tipo_operacion,
								        			top.nombre nombretipooperacion,
								        			stp.id_subtipo_producto,
								        			stp.nombre nombresubtipoproducto,
								        			tp.id_tipo_producto,
								        			tp.nombre nombretipoproducto,
        											top.id_flujo_operacion,
        											tp.id_area
								        		FROM
								        			g_operadores.operaciones o,
								        			g_catalogos.tipos_operacion top,
								        			g_catalogos.productos p,
								        			g_catalogos.subtipo_productos stp,
								        			g_catalogos.tipo_productos tp
								        		WHERE
								        			o.estado='cargarIA'
								        			and top.id_tipo_operacion=o.id_tipo_operacion
								        			and o.id_producto = p.id_producto
								        			and p.id_subtipo_producto = stp.id_subtipo_producto
								        			and stp.id_tipo_producto = tp.id_tipo_producto
								        			and id_operacion='$idOperacion'");
        	return $res;
        }
		
		public function obtenerAreaXIdOperacion ($conexion, $idOperacion){
		    
        	$res = $conexion->ejecutarConsulta("SELECT
										        	pao.id_area
										        FROM
											        g_operadores.operaciones op,
											        g_operadores.productos_areas_operacion pao
										        WHERE
											        op.id_operacion=pao.id_operacion
											        and op.id_operacion='$idOperacion'");
        	return $res;
        }
        
		public function listarOperacionesEnvioMasivo ($conexion, $idOperador){
        	$res = $conexion->ejecutarConsulta("SELECT
										       
										        	distinct top.id_tipo_operacion, top.nombre, top.id_area
										        FROM
											        g_operadores.operaciones o,
											        g_catalogos.tipos_operacion top,
											        g_operadores.operadores op,
											        g_catalogos.areas_operacion aop,
											        g_operadores.productos_areas_operacion pao,
											        g_operadores.areas a
										        WHERE
											        o.identificador_operador='$idOperador' and
											        top.id_tipo_operacion=o.id_tipo_operacion and
											        op.identificador=o.identificador_operador and
											        aop.id_tipo_operacion = top.id_tipo_operacion and 
											        pao.id_operacion=o.id_operacion and 
											        a.codigo=aop.codigo 
											        order by top.nombre asc");
        	return $res;
        }
        
        public function listarAreasEnvioMasivo ($conexion, $idOperador){
        	$resul = $conexion->ejecutarConsulta("SELECT 
        											distinct top.id_tipo_operacion, a.id_area,a.nombre_area,s.nombre_lugar
											    FROM 
        											g_operadores.operadores op, 
        											g_operadores.sitios s, 
        											g_operadores.areas a, 
        											g_operadores.operaciones ope, 
        											g_catalogos.tipos_operacion top,
											        g_catalogos.areas_operacion aop,
											        g_operadores.productos_areas_operacion pao
												WHERE
        											op.identificador = s.identificador_operador
											        and op.identificador = ope.identificador_operador
											        and s.identificador_operador = ope.identificador_operador
											        and ope.id_tipo_operacion = top.id_tipo_operacion
											        and s.id_sitio = a.id_sitio
											        and a.codigo=aop.codigo 
													and aop.id_tipo_operacion = top.id_tipo_operacion 
											      	and a.id_area=pao.id_area
											       	and pao.id_operacion=ope.id_operacion 
											        and s.identificador_operador = '$idOperador'");
        	
        	while ($fila = pg_fetch_assoc($resul)){
        		$res[] = array(id_tipo_operacion=>$fila['id_tipo_operacion'],nombre_area=>$fila['nombre_area'],id_area=>$fila['id_area'],nombre_lugar=>$fila['nombre_lugar']);
        	}
        	
        	return $res;
        }
       	
       public function listarProductosXOperacionXAreas ($conexion,$idArea,$idOperacion){
       		$res = $conexion->ejecutarConsulta("SELECT
													o.nombre_producto, o.id_producto, o.estado , o.id_operacion, st.provincia,t.nombre
												FROM
													g_operadores.operaciones o,
													g_operadores.areas a,
													g_operadores.productos_areas_operacion pao,
													g_catalogos.tipos_operacion t,
													g_operadores.sitios st
												WHERE
													a.id_area=$idArea and
													o.id_tipo_operacion=$idOperacion and
													o.id_operacion=pao.id_operacion and
													a.id_area=pao.id_area and
													o.id_operacion=pao.id_operacion and
													o.id_tipo_operacion=t.id_tipo_operacion and
													a.id_sitio = st.id_sitio
													order by 1");
       				return $res;
       	}
       	
       	public function variedadesXOperacionesXProductos ($conexion,$idOperacion){
       		$res = $conexion->ejecutarConsulta("SELECT
								       				v.nombre
								       			FROM
								       				g_operadores.operaciones_variedades ov,
								       				g_catalogos.variedades v,
								       				g_operadores.operaciones ope
								       			WHERE
								       				ov.id_operacion=ope.id_operacion and
								       				ov.id_variedad=v.id_variedad and
								       				ope.id_operacion='$idOperacion'
								       				order by 1");
       				return $res;
       	}
       	
       	public function jsonListarHistoricoPorProvincia($conexion, $provincia, $area, $canton)
       	
       	{
       	
       		$res = $conexion->ejecutarConsulta("select row_to_json(historico)
							       	  				from (
							       	       				select array_to_json(array_agg(row_to_json(listado)))
							       	       				from (
							       		       				select
							       		       					(fe.apellido || ', ' || fe.nombre) as inspector,
							       		       					b1.id_item_inspeccion as id_area,
							       		       					b1.fecha_inspeccion,
							       		       					b1.observacion
							       		       				from
							       			       				g_revision_solicitudes.inspeccion_observaciones b1,
							       			       				g_revision_solicitudes.inspeccion i,
							       			       				g_uath.ficha_empleado fe
							       		       				where
							       			       				b1.tipo_elemento = 'Área' and
							       			       				i.id_inspeccion = b1.id_inspeccion
							       			       				and i.identificador_inspector = identificador
       															and b1.observacion != ''
							       			       				and b1.id_inspeccion_observacion in (
							       		   	    				select
							       				       				b2.id_inspeccion_observacion
							       		       					from
							       				       				g_revision_solicitudes.inspeccion_observaciones b2
							       			       				where
							       				       				b2.id_item_inspeccion = b1.id_item_inspeccion
       																and b2.observacion != ''
							       				       				and b2.id_item_inspeccion in (
							       			       				select
							       				       				distinct are.id_area
							       			       				from
							       				       				g_operadores.areas are,
							       				       				g_operadores.sitios sit,
							       				       				g_operadores.productos_areas_operacion pao,
							       				       				g_operadores.operaciones o,
							       				       				g_catalogos.tipos_operacion tope
							       			       				where
							       				       				pao.id_area = are.id_area and
							       				       				upper(sit.provincia) = upper('$provincia') and
       																upper(sit.canton) IN $canton and
							       				       				are.id_sitio = sit.id_sitio  and
							       				       				pao.id_operacion = o.id_operacion and
							       				       				o.id_tipo_operacion = tope.id_tipo_operacion and
							       				       				tope.id_area = '$area')
							       			       				order by b2.fecha_inspeccion desc
							       	  		)
							       	) as listado) as historico;");
       	
       				$json = pg_fetch_assoc($res);
       	
       				return json_decode($json[row_to_json],true);
       	
       	}
       	
       	////////////////////////////////////////////////////////REGISTRO OPERADOR ORGANICOS ///////////////////////////////////////////////////////////////
       	
       	public function obtenerTipoProduccion($conexion){
       		$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_catalogos.tipo_produccion;");
       		return $res;
       	}
       	
       	public function obtenerTipoTransicion($conexion){
       		$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_catalogos.tipo_transicion;");
       		return $res;
       	}
       	
       	public function obtenerAgenciaCertificadora($conexion){
       		$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_catalogos.agencia_certificadora;");
       		return $res;
       	}
       	
       	public function buscarOperacionTipoOperacionXIdOperacion($conexion,$idOperacion) {
       		       		
       		$res = $conexion->ejecutarConsulta("SELECT
								       				*
								       			FROM
								       				g_operadores.operaciones o,
								       				g_catalogos.tipos_operacion top
								       			WHERE
								       				o.id_tipo_operacion=top.id_tipo_operacion
								       				and o.id_operacion IN ($idOperacion);");
       		return $res;
       	}
       	
       	
       	public function guardarOperacionesOrganico($conexion,$idOperacion, $idOperadorTipoOperacion, $idHistorialOperacion, $idTipoProduccion, $idTipoTransicion, $idAgenciaCertificadora, $idProducto, $alcance = null ){
       	    
       	    $res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.operaciones_organico
													(id_operacion, id_operador_tipo_operacion, id_historial_operacion, id_tipo_produccion, id_tipo_transicion, id_agencia_certificadora, id_producto, alcance)
												SELECT $idOperacion,$idOperadorTipoOperacion,$idHistorialOperacion, $idTipoProduccion, $idTipoTransicion, $idAgenciaCertificadora, $idProducto, '$alcance'
                                                WHERE NOT EXISTS (SELECT id_operacion FROM g_operadores.operaciones_organico WHERE id_operacion = $idOperacion);");
       	    
       	    return $res;
       	}
       	
       	public function buscarCodigoPoa($conexion,$idTipoOperacion,$idAgenciaCertificadora,$idOperador) {
       		 
       		$res = $conexion->ejecutarConsulta("SELECT
								       				oo.codigo_poa
								       			FROM
								       				g_operadores.operaciones_organico oo,
								       				g_operadores.operaciones op
								       			WHERE
								       				oo.id_tipo_operador='$idTipoOperacion'
								       				and oo.id_agencia_certificadora='$idAgenciaCertificadora'
								       				and op.id_operacion=oo.id_operacion
								       				and op.identificador_operador='$idOperador';");
       		return $res;
       	}
       	
       	
       	public function verificarCodigoPoa($conexion,$codigoPoa) {

       		$res = $conexion->ejecutarConsulta("SELECT
								       				codigo_poa
								       			FROM
								       				g_operadores.operaciones_organico
								       			WHERE
								       				codigo_poa='$codigoPoa';");
       		return $res;
       	}
       	
		public function obtenerDatosRegistroOrganicos($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado){

					$res = $conexion->ejecutarConsulta("SELECT
                                                        	ac.nombre_agencia_certificadora,
                                                        	tt.nombre_tipo_transicion,
                                                        	tp.nombre_tipo_produccion,
                                                        	o.id_operacion,
                                                        	oo.id_operacion_organico,
                                                        	o.nombre_producto
                                                        FROM
                                                        	g_operadores.operaciones o
                                                        	INNER JOIN g_operadores.operaciones_organico oo ON o.id_operacion = oo.id_operacion
                                                        	INNER JOIN g_catalogos.agencia_certificadora ac ON oo.id_agencia_certificadora = ac.id_agencia_certificadora
                                                        	LEFT JOIN g_catalogos.tipo_produccion tp ON oo.id_tipo_produccion = tp.id_tipo_produccion
                                                        	LEFT JOIN g_catalogos.tipo_transicion tt ON oo.id_tipo_transicion = tt.id_tipo_transicion	
                                                        WHERE
															o.id_operador_tipo_operacion = $idOperadorTipoOperacion 
                                                            and o.id_historial_operacion = $idHistorialOperacion
                                                            and o.estado = '" . $estado . "'
															ORDER BY 6 asc;");
		
						return $res;
		}
		
		public function obtenerDatosRegistroOrganicosXidOperacion($conexion, $idOperacion){
		
					$res = $conexion->ejecutarConsulta("SELECT 
                                                        	ac.nombre_agencia_certificadora, 
                                                        	tt.nombre_tipo_transicion,
                                                        	tp.nombre_tipo_produccion,
                                                        	o.id_operacion, 
                                                        	oo.id_operacion_organico,
                                                        	o.nombre_producto
                                                        FROM 
                                                        	g_operadores.operaciones o
                                                        INNER JOIN g_operadores.operaciones_organico oo ON o.id_operacion = oo.id_operacion
                                                        INNER JOIN g_catalogos.agencia_certificadora ac ON oo.id_agencia_certificadora = ac.id_agencia_certificadora
                                                        LEFT JOIN g_catalogos.tipo_produccion tp ON oo.id_tipo_produccion = tp.id_tipo_produccion 
                                                        LEFT JOIN g_catalogos.tipo_transicion tt ON oo.id_tipo_transicion = tt.id_tipo_transicion 
                                                        WHERE
                                                        	o.id_operacion = $idOperacion
                                                        	ORDER BY 6 asc;");
		
						return $res;
		}
       	
       	public function buscarAreaXCodigo ($conexion, $codigoArea){
       		       	
       		$res = $conexion->ejecutarConsulta("SELECT
								       				*
								       			FROM
								       				g_operadores.sitios s,
								       				g_operadores.areas a
								       			WHERE
								       				s.id_sitio = a.id_sitio and
								       				s.identificador_operador||'.'||s.codigo_provincia||s.codigo||a.codigo||a.secuencial = '$codigoArea';");
       				return $res;
       	}
       	
       	public function buscarProveedorPorOperador ($conexion, $identificadorOperador, $identificadorProveedor, $tipoActividad){
       		
      	
       		$res = $conexion->ejecutarConsulta("SELECT
													pr.*
												FROM
													g_operadores.proveedores pr
												WHERE
													pr.codigo_proveedor = '$identificadorProveedor' and
													pr.identificador_operador = '$identificadorOperador' and
       												pr.nombre_operacion = '$tipoActividad';");
       		return $res;
       	}
       	
       	public function listarSitiosXCodigo ($conexion, $codigoSitio){
       	
       		 
       		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
								       				s.id_sitio, s.nombre_lugar
								       			FROM
								       				g_operadores.operadores o,
								       				g_operadores.sitios s,
								       				g_operadores.operaciones op,
								       				g_operadores.areas a,
								       				g_operadores.productos_areas_operacion pao
								       			WHERE
								       				o.identificador = s.identificador_operador
								       				and o.identificador = op.identificador_operador
								       				and s.identificador_operador = op.identificador_operador
								       				and s.id_sitio = a.id_sitio
								       				and op.estado = 'registrado'
								       				and pao.id_operacion=op.id_operacion
								       				and pao.id_area=a.id_area
								       				and s.identificador_operador || '' || '.' || '' || s.codigo_provincia || '' || s.codigo='$codigoSitio';");
       		return $res;
       	}
       	
       	public function listarAreasXSitio ($conexion, $idSitio){
       	
       		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
								       				a.id_area
								       				,a.nombre_area,
													op.id_tipo_operacion,
								       				s.identificador_operador || '' || '.' || '' || s.codigo_provincia || '' || s.codigo || '' || a.codigo || '' || a.secuencial narea
								       			FROM
								       				g_operadores.operadores o
								       				, g_operadores.sitios s
								       				, g_operadores.operaciones op
								       				, g_catalogos.tipos_operacion t
								       				, g_operadores.areas a
								       				, g_operadores.productos_areas_operacion pao
								       			WHERE
								       				o.identificador = s.identificador_operador
								       				and o.identificador = op.identificador_operador
								       				and s.identificador_operador = op.identificador_operador
								       				and op.id_tipo_operacion = t.id_tipo_operacion
								       				and s.id_sitio = a.id_sitio
								       				and op.estado in ('registrado', 'registradoObservacion')
								       				and pao.id_operacion=op.id_operacion
								       				and pao.id_area=a.id_area
								       				and s.id_sitio=$idSitio;");
       		return $res;
       	}
       	
       	public function listarOperacionesXArea ($conexion, $idArea){
       			
       		$res = $conexion->ejecutarConsulta("SELECT  DISTINCT t.nombre,
								       				t.id_tipo_operacion,
								       				a.nombre_area,t.id_area,(SELECT CASE WHEN t.id_area='SV' THEN 'Sanidad Vegetal' WHEN t.id_area='SA' THEN 'Sanidad Animal' END) tipo_area,
								       				a.id_area
								       			FROM
								       				g_operadores.operaciones op
								       				, g_catalogos.tipos_operacion t
								       				, g_operadores.areas a
								       				, g_operadores.productos_areas_operacion pao
								       			WHERE
								       				op.id_tipo_operacion = t.id_tipo_operacion
								       				and op.estado in ('registrado', 'registradoObservacion')
								       				and pao.id_operacion=op.id_operacion
								       				and pao.id_area=a.id_area
								       				and a.id_area=$idArea;");
       		return $res;
       	}
       	
       	public function buscarProductoXArea ($conexion, $idArea, $idTipoOperacion){
       	
       		$res = $conexion->ejecutarConsulta("SELECT
								       				stp.nombre, p.nombre_comun, p.id_producto
								       			FROM
								       				g_operadores.operadores o
								       				, g_operadores.sitios s
								       				, g_operadores.operaciones op
								       				, g_catalogos.tipos_operacion t
								       				, g_operadores.areas a
								       				, g_operadores.productos_areas_operacion pao
								       				,g_catalogos.productos p
								       				,g_catalogos.subtipo_productos stp
								       			WHERE
								       				o.identificador = s.identificador_operador
								       				and o.identificador = op.identificador_operador
								       				and s.identificador_operador = op.identificador_operador
								       				and op.id_tipo_operacion = t.id_tipo_operacion
								       				and s.id_sitio = a.id_sitio
								       				and op.estado = 'registrado'
								       				and pao.id_operacion=op.id_operacion
								       				and pao.id_area=a.id_area
								       				and p.id_producto=op.id_producto
								       				and stp.id_subtipo_producto=p.id_subtipo_producto
								       				and a.id_area=$idArea
								       				and op.id_tipo_operacion=$idTipoOperacion;");
       		return $res;
       	}
       	
       	public function actualizarPartidaYCodigoProductoVUE($conexion, $partidaArancelariaVUE, $codigoProductoVUE, $idVue, $idProducto){
       		       		
       		$res = $conexion->ejecutarConsulta("UPDATE
       												g_operadores.operaciones
       											SET
       												subpartida_producto_vue = '$partidaArancelariaVUE',
       												codigo_producto_vue = '$codigoProductoVUE',
       												fecha_modificacion = now()
       											WHERE
       												id_producto = $idProducto and
       												id_vue = '$idVue';");
       		
       		return $res;
       		
       	}
       	
       	public function listarOperacionXIdentificadorAreaProductoTipoOperacion($conexion, $identificador, $idArea,$idProducto, $tipoOperacion, $estado){
       			
       		$res = $conexion->ejecutarConsulta("SELECT
								       				tp.id_tipo_operacion,
								       				tp.nombre
								       			FROM
								       				g_operadores.operaciones o,
								       				g_operadores.productos_areas_operacion pao,
								       				g_catalogos.tipos_operacion tp
								       			WHERE
								       				o.id_tipo_operacion = tp.id_tipo_operacion
								       				and o.id_operacion = pao.id_operacion
								       				and pao.id_area = $idArea
								       				and o.id_producto = $idProducto
								       				and o.identificador_operador = '$identificador'
       												and o.id_tipo_operacion = $tipoOperacion
								       				and o.estado = '$estado';");
       		return $res;
       	}
       	
       	public function listarOperacionXIdentificadorAreaProductoTipoOperacionProveedor($conexion, $identificador, $idArea,$idProducto, $tipoOperacion, $identificadorProveedor, $estado){
       	
       		$res = $conexion->ejecutarConsulta("SELECT
													tp.id_tipo_operacion,
													tp.nombre
												FROM
													g_operadores.operaciones o,
													g_operadores.productos_areas_operacion pao,
													g_catalogos.tipos_operacion tp,
													g_operadores.proveedores pr
												WHERE
													o.id_tipo_operacion = tp.id_tipo_operacion
													and o.id_operacion = pao.id_operacion
													and pr.identificador_operador = o.identificador_operador
													and pr.codigo_proveedor = '$identificadorProveedor'	
													and pao.id_area = $idArea
													and o.id_producto = $idProducto
													and o.identificador_operador = '$identificador'
													and o.id_tipo_operacion = $tipoOperacion
													and o.estado = '$estado';");
       		return $res;
       	}
       	
       	public function listarSitiosXOperador ($conexion, $idOperador){
       		$res = $conexion->ejecutarConsulta("SELECT
								       				s.id_sitio, s.identificador_operador||'.'||s.codigo_provincia||s.codigo||' - '||s.nombre_lugar as nombre_sitio
								       			FROM
								       				g_operadores.sitios s,
								       				g_operadores.operadores op
								       			WHERE
								       				s.identificador_operador=op.identificador
								       				and identificador_operador='$idOperador';");
       		return $res;
       	}
       	
       	public function obtenerOperadoresPorTipoOperacionYarea($conexion, $tipoOperacion, $areaOperacion){
       		 
       		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
								       				op.nombre_representante || ' ' || op.apellido_representante nombres,
								       				op.correo,
								       				op.identificador
								       			FROM
								       				g_operadores.operaciones o,
								       				g_catalogos.tipos_operacion tp,
								       				g_operadores.operadores op
								       			WHERE
								       				o.id_tipo_operacion = tp.id_tipo_operacion and
								       				op.identificador=o.identificador_operador and
								       				o.estado='registrado' and
								       				tp.codigo in $tipoOperacion and
								       				tp.id_area in $areaOperacion;");
       	
       				return $res;
       	}
       	
       	public function obtenerOperadoresPorIdentificadorMasivo($conexion, $identificador){
       		
       		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
								       				op.nombre_representante || ' ' || op.apellido_representante nombres,
								       				op.correo,
								       				op.identificador
								       			FROM
								       				--g_operadores.operaciones o,
								       				--g_catalogos.tipos_operacion tp,
								       				g_operadores.operadores op
								       			WHERE
								       				--o.id_tipo_operacion = tp.id_tipo_operacion and
								       				--op.identificador=o.identificador_operador and
								       				--o.estado='registrado' and
								       				op.identificador in $identificador;");
       		
       		return $res;
       	}
		
		public function buscarOperacionesPorCodigoyAreaOperacionFloresFollajes($conexion,$identificador,$codigoOperacion,$areaOperacion){
			
       		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
							       					op.id_operacion
							       				FROM
								       				g_operadores.operadores opv
								       				,g_operadores.operaciones op
								       				,g_catalogos.tipos_operacion t
								       				,g_catalogos.productos p
								       				,g_catalogos.subtipo_productos stp
								       				,g_catalogos.tipo_productos tp
								       				,g_catalogos.areas_operacion ao
								       				,g_operadores.productos_areas_operacion pao
								       				,g_operadores.sitios si
								       				,g_operadores.areas ar
							       				WHERE
								       				opv.identificador = op.identificador_operador and
								       				op.id_tipo_operacion = t.id_tipo_operacion and
								       				t.codigo IN $codigoOperacion and
								       				t.id_area IN $areaOperacion and
								       				op.estado='registrado' and
								       				p.id_producto=op.id_producto and
								       				p.id_subtipo_producto=stp.id_subtipo_producto and
								       				stp.id_tipo_producto=tp.id_tipo_producto and
								       				t.id_tipo_operacion=op.id_tipo_operacion and
								       				ao.id_tipo_operacion=op.id_tipo_operacion and
								       				si.identificador_operador=opv.identificador and
								       				ar.id_sitio=si.id_sitio and
								       				pao.id_operacion=op.id_operacion and
								       				pao.id_area=ar.id_area and
								       				tp.nombre='Flores y follajes cortados' and
								       				op.identificador_operador='$identificador';");
       				return $res;
       	}
       	
       	public function buscarOperacionesPorAreasDeOperacion($conexion,$idOperacion){
       		
       		$res = $conexion->ejecutarConsulta("SELECT
													distinct op.id_operacion
												FROM
													g_operadores.productos_areas_operacion pao,
													g_operadores.operaciones op
												WHERE
													pao.id_operacion = op.id_operacion
													and (id_vue = '' or id_vue is null)
       												and id_tipo_operacion IN (SELECT 
																					distinct op1.id_tipo_operacion 
																			  FROM 
																					g_operadores.operaciones op1 
																			  WHERE 
																					op1.id_operacion IN $idOperacion)
													and pao.id_area IN (SELECT 
       																		distinct pao1.id_area 
       																	FROM 
       																		g_operadores.productos_areas_operacion pao1  
       																	WHERE 
       																		pao1.id_operacion IN $idOperacion)");
	       		
	    	return $res;
		}
		
		public function buscarMaximoOperacionPorArea($conexion, $idArea){
			
			$res = $conexion->ejecutarConsulta("select
													max(opr.id_operacion) as id_operacion
												 from
													g_operadores.operaciones opr,
													g_operadores.productos_areas_operacion pao,
													g_operadores.areas are	
												 where
													are.id_area = pao.id_area  
													and pao.id_operacion = opr.id_operacion
													and are.id_area =$idArea;");
			
			return $res;
		}
	          
		public function buscarOperacionesPorCodigoyAreaOperacionCacao($conexion,$identificador,$codigoOperacion,$areaOperacion){		
					
			
			$res = $conexion->ejecutarConsulta("SELECT DISTINCT
							       					op.id_operacion
							       				FROM
								       				
								       				g_operadores.operaciones op
								       				,g_catalogos.tipos_operacion t
								       				,g_catalogos.productos p
								       				
							       				WHERE
								       				op.id_tipo_operacion = t.id_tipo_operacion and
								       				t.codigo IN $codigoOperacion and
								       				t.id_area IN $areaOperacion and
								       				op.estado='registrado' and
								       				p.nombre_comun='cacao' and
								       				op.identificador_operador='$identificador';");
					return $res;
		}
		
		public function actualizarEstadoOperacion ($conexion, $idOperacion, $estado, $observacion=null){
				
			$res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													estado = '$estado',
													observacion = '$observacion',
													fecha_modificacion = now(),
                                                    fecha_aprobacion = now()
												where
													id_operacion = $idOperacion;");
					return $res;
		}
		
		public function buscarIdAreasPorGrupoOperacion($conexion,$idOperacion){
			 
			$res = $conexion->ejecutarConsulta("SELECT
													distinct id_area
												FROM
													g_operadores.productos_areas_operacion
												WHERE
													id_operacion IN $idOperacion");
		
			return $res;
		}
		
		public function buscarCantidadOperacionesPorIdAreas($conexion,$idArea){
		
			$res = $conexion->ejecutarConsulta("SELECT
													count(op.id_operacion) as cantidad
												FROM
													g_operadores.productos_areas_operacion pao,
													g_operadores.operaciones op
												WHERE
													pao.id_operacion = op.id_operacion and
													pao.estado != 'noHabilitado' and 
													pao.id_area = $idArea");
		
			return $res;
		}
		
		public function obtenerAsociacionXNombreCorreoFecha($conexion, $nombreAsociacion, $correoAsociacion, $fechaRegistro){
		
			$nombreAsociacion = $nombreAsociacion != "" ? "'%" . $nombreAsociacion . "%'" : "NULL";
			$correoAsociacion = $correoAsociacion != "" ? "'%" . $correoAsociacion . "%'" : "NULL";
			$fechaRegistro = $fechaRegistro != "" ? "'" . $fechaRegistro . "'" : "NULL";
		
			$res = $conexion->ejecutarConsulta("SELECT
													identificador, razon_social, correo, to_char(fecha_operador,'DD/MM/YYYY') fecha_registro
												FROM
													g_operadores.operadores
												WHERE
													tipo_operador = 'operadorOrganico'
													and ($nombreAsociacion is NULL or razon_social ilike $nombreAsociacion)
													and ($correoAsociacion is NULL or correo ilike $correoAsociacion)
													and ($fechaRegistro is NULL or fecha_operador >= $fechaRegistro)
													and ($fechaRegistro is NULL or fecha_operador < $fechaRegistro);");
		
			return $res;
		}
		
		public function obtenerSitiosXMiembroAsociacion ($conexion, $idMiembroAsociacion, $nombreMiembroAsociacion){
				
			$idMiembroAsociacion = $idMiembroAsociacion != "" ? "'" . $idMiembroAsociacion . "'" : "NULL";
			$nombreMiembroAsociacion = $nombreMiembroAsociacion != "" ? "'%" . $nombreMiembroAsociacion . "%'" : "NULL";
		
			$res = $conexion->ejecutarConsulta("SELECT
													o.identificador, ma.identificador_miembro_asociacion, ma.nombre_miembro_asociacion || ' ' || ma.apellido_miembro_asociacion as nombre_miembro_asociacion, s.nombre_lugar, a.nombre_area, s.superficie_total,
													case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else razon_social end nombre_operador
												FROM
													g_operadores.operadores o,
													g_operadores.miembros_asociacion ma,
													g_operadores.detalle_miembros_asociacion dma,
													g_operadores.sitios s,
													g_operadores.areas a
												WHERE
													o.identificador = ma.identificador_asociacion
													and ma.id_miembro_asociacion = dma.id_miembro_asociacion
													and s.id_sitio =  dma.id_sitio
													and a.id_area = dma.id_area
													and ($idMiembroAsociacion is NULL or ma.identificador_miembro_asociacion = $idMiembroAsociacion)
													and ($nombreMiembroAsociacion is NULL or ma.nombre_miembro_asociacion ||' '|| ma.apellido_miembro_asociacion ilike $nombreMiembroAsociacion);");
		
			return $res;
		}
		
		public function obtenerRendimientoXIdentificacionNombre ($conexion, $idMiembroAsociacion, $nombreMiembroAsociacion, $nombreSitio, $identificadorAsociacion){
		
			$idMiembroAsociacion = $idMiembroAsociacion != "" ? "'" . $idMiembroAsociacion . "'" : "NULL";
			$nombreMiembroAsociacion = $nombreMiembroAsociacion != "" ? "'%" . $nombreMiembroAsociacion . "%'" : "NULL";
			$nombreSitio = $nombreSitio != "" ? "'%" . $nombreSitio . "%'" : "NULL";

			$res = $conexion->ejecutarConsulta("SELECT
													distinct s.id_sitio, s.nombre_lugar, a.nombre_area, a.id_area, op.id_tipo_operacion, 
                                                    top.nombre, t1.identificador_miembro_asociacion, t1.nombre_miembro, op.estado, t1.rendimiento 
												FROM
													g_operadores.sitios s
												    INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio)
												    INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
												    INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
												    INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion
												    FULL OUTER JOIN (
                                                    SELECT ma.identificador_miembro_asociacion, ma.nombre_miembro_asociacion ||' '|| ma.apellido_miembro_asociacion as nombre_miembro, id_sitio, id_area, sum(dma.rendimiento)as rendimiento 
                                                    FROM 
                                                    g_operadores.miembros_asociacion ma INNER JOIN g_operadores.detalle_miembros_asociacion dma ON ma.id_miembro_asociacion = dma.id_miembro_asociacion WHERE identificador_asociacion = '$identificadorAsociacion' GROUP BY ma.identificador_miembro_asociacion, nombre_miembro, id_sitio, id_area 
                                                    ) as t1 ON t1.id_sitio = s.id_sitio and t1.id_area = a.id_area
												WHERE
													op.identificador_operador = '$identificadorAsociacion' and op.estado in ('registrado', 'cargarRendimiento', 'subsanacion', 'subsanacionProducto')
													and ($idMiembroAsociacion is NULL or t1.identificador_miembro_asociacion = $idMiembroAsociacion)
													and ($nombreMiembroAsociacion is NULL or t1.nombre_miembro ilike $nombreMiembroAsociacion)
                                                    and top.id_area = 'AI'
                                                    and top.codigo in ('PRO','REC')");
					return $res;
		}
		
		public function obtenerOperacionesXOperador($conexion, $identificadorOperador){

			$res = $conexion->ejecutarConsulta("SELECT
													DISTINCT top.id_tipo_operacion, top.nombre, p.nombre_comun, p.id_producto
												FROM
													g_operadores.operaciones op,
													g_catalogos.tipos_operacion top,
													g_operadores.productos_areas_operacion pao,
													g_catalogos.productos p
												WHERE
													op.id_tipo_operacion = top.id_tipo_operacion
													and op.identificador_operador='$identificadorOperador'
													and op.id_producto = p.id_producto
													and top.id_area='AI'
													and top.codigo in ('PRO', 'PRC', 'COM')
													and op.estado IN ('registrado','cargarRendimiento')
													and pao.id_operacion = op.id_operacion
													and NOT EXISTS (SELECT id_operacion FROM g_operadores.detalle_miembros_asociacion dma WHERE op.id_operacion = dma.id_operacion);");
					return $res;
		}
		
		public function obtenerOperacionesXOperadorXIdAreaXCodigoOperacion($conexion, $identificadorOperador, $idArea, $codigoOperacion){

			$res = $conexion->ejecutarConsulta("SELECT
													STRING_AGG(distinct top.codigo,', ') as codigo
												FROM
													g_operadores.operaciones op
													INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
												WHERE 
													top.id_area = '$idArea' 
													and top.codigo $codigoOperacion
													and op.identificador_operador='$identificadorOperador'
													and op.estado in ('registrado', 'porCaducar');");
					return $res;
		}
		
		public function  obtenerDatosOperador($conexion, $identificadorOperador){
		
			$res = $conexion->ejecutarConsulta("SELECT 
													identificador, nombre_representante, apellido_representante, tipo_operador, tipo_actividad, razon_social
												FROM
													g_operadores.operadores
												WHERE
													identificador = '$identificadorOperador';");
											
			return $res;
		}
		
		public function obtenerOperacionXIdentificadorTipoProductoYSitio ($conexion, $identificadorOperador, $idTipoOperacion, $idProducto, $idSitio){
					
			$res = $conexion->ejecutarConsulta("SELECT
													op.id_operacion
												FROM
													g_operadores.sitios s,
													g_operadores.areas a,
													g_operadores.productos_areas_operacion pao,
													g_operadores.operaciones op
												WHERE
													s.id_sitio = a.id_sitio
													and a.id_area = pao.id_area
													and op.id_operacion = pao.id_operacion
													and s.identificador_operador='$identificadorOperador'
													and op.id_tipo_operacion = $idTipoOperacion
													and id_producto = $idProducto
													and s.id_sitio = $idSitio;");
			return $res;
		}
		
		public function buscarSitioMiembroAsociacion($conexion, $idMiembro, $idSitio){
						 
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.miembros_asociacion ma,
													g_operadores.detalle_miembros_asociacion dma
												WHERE
													ma.identificador_miembro_asociacion = '$idMiembro'
													and ma.id_miembro_asociacion = dma.id_miembro_asociacion
													and id_sitio = $idSitio");
			return $res;
		}
		
		public function buscarDetalleMiembroAsociacion($conexion, $idSitio, $idArea, $idOperacion){
						
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.detalle_miembros_asociacion
												WHERE
													id_sitio = $idSitio
													and id_area = $idArea
													and id_operacion = $idOperacion");
			return $res;
		
		}
		
		public function buscarExisteSitio($conexion, $idSitio){
			 
			$res = $conexion->ejecutarConsulta("SELECT 
													*
												FROM
													g_operadores.detalle_miembros_asociacion dma
												WHERE
												id_sitio = $idSitio");
			return $res;
		}
		
		public function  generarCodigoMiembroAsociacion($conexion, $codigoMiembro){
				
			$res = $conexion->ejecutarConsulta("SELECT 
													MAX(codigo_miembro_asociacion) valor
												FROM
													g_operadores.miembros_asociacion
												WHERE
													codigo_miembro_asociacion LIKE '$codigoMiembro';");
		
			return $res;
		}
		
		public function guardarRendimientoAsociacion($conexion, $codigoMiembro, $identificador, $usuario, $nombre, $apellido, $codigoMagap){
		
			$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.miembros_asociacion
													(codigo_miembro_asociacion, identificador_miembro_asociacion, identificador_asociacion, nombre_miembro_asociacion, apellido_miembro_asociacion, codigo_magap)
												VALUES
													('$codigoMiembro', '$identificador', '$usuario', '$nombre', '$apellido', '$codigoMagap')
													RETURNING id_miembro_asociacion;");
		
					return $res;
		}
		
		public function guardarRendimientoAsociacionDetalle($conexion, $idMiembro, $idOperacion, $idArea, $rendimiento, $superficieSitio, $superficieMiembro){

			$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.detalle_miembros_asociacion
													(id_miembro_asociacion, id_operacion, id_area, rendimiento, superficie_sitio, superficie_miembro)
												VALUES
													('$idMiembro', '$idOperacion', '$idArea', '$rendimiento', '$superficieSitio', '$superficieMiembro');");
		
			return $res;
		}
		
		
		public function actualizarEstadoMiembroAsociacion($conexion, $idMiembro, $estado){
		
			$res = $conexion->ejecutarConsulta("UPDATE 
													g_operadores.miembros_asociacion
												SET
													estado_miembro_asociacion = $estado
												WHERE
													id_miembro_asociacion = $idMiembro;");
		
			return $res;
		}
		
		
		public function obtenerDetalleMiembroXIdentificadorXSitio ($conexion, $idMiembroAsociacion, $idSitio){
					
			$res = $conexion->ejecutarConsulta("SELECT
													top.id_tipo_operacion,
													top.nombre as nombre_tipo_operacion,
													op.id_operacion, op.id_producto,
													op.nombre_producto, s.id_sitio,
													s.nombre_lugar, a.id_area,
													a.nombre_area, dma.rendimiento,
													dma.id_detalle_miembro_asociacion,
													ma.identificador_miembro_asociacion
												FROM
													g_operadores.miembros_asociacion ma,
													g_operadores.detalle_miembros_asociacion dma,
													g_operadores.sitios s,
													g_operadores.areas a,
													g_operadores.operaciones op,
													g_catalogos.tipos_operacion top
												WHERE
													ma.id_miembro_asociacion = dma.id_miembro_asociacion
													and dma.id_miembro_asociacion = $idMiembroAsociacion
													and dma.id_sitio = $idSitio
													and dma.id_sitio = s.id_sitio
													and dma.id_area = a.id_area
													and dma.id_operacion = op.id_operacion
													and op.id_tipo_operacion = top.id_tipo_operacion;");
					return $res;
		}
		
		public function imprimirLineaDetalleMiembroAsociacion($idTipoOperacion, $nombreTipoOperacion, $idOperacion, $idProducto, $nombreProducto, $idSitio, $nombreSitio, $idArea, $nombreArea, $rendimiento, $idMiembroAsociacion, $idDetalleMiembro, $identificadorMiembroAsociacion){
			 
			return '<tr id="R' . $idDetalleMiembro . '">' .
					'<td width="100%">
       				<b>Área: </b>' . $nombreArea . '<br>' .
		       				'<b>Operación y producto: </b>' . $nombreTipoOperacion . ' - ' . $nombreProducto . '<br>' .
		       				'<b>Rendimiento: </b>' . $rendimiento . ' Kg</td>' .
		       				'<td>' .
		       				'<form class="abrir" data-rutaAplicacion="registroOperador" data-opcion="abrirDetalleRendimientoAsociacion" data-destino="detalleItem" data-accionEnExito="NADA" >' .
		       				'<input type="hidden" id="idTipoOperacion" name="idTipoOperacion" value="' . $idTipoOperacion . '" >' .
		       				'<input type="hidden" id="nombreTipoOperacion" name="nombreTipoOperacion" value="' . $nombreTipoOperacion . '" >' .
		       				'<input type="hidden" id="idOperacion" name="idOperacion" value="' . $idOperacion . '" >' .
		       				'<input type="hidden" id="idProducto" name="idProducto" value="' . $idProducto . '" >' .
		       				'<input type="hidden" id="nombreProducto" name="nombreProducto" value="' . $nombreProducto . '" >' .
		       				'<input type="hidden" id="idSitio" name="idSitio" value="' . $idSitio . '" >' .
		       				'<input type="hidden" id="nombreSitio" name="nombreSitio" value="' . $nombreSitio . '" >' .
		       				'<input type="hidden" id="idArea" name="idArea" value="' . $idArea . '" >' .
		       				'<input type="hidden" id="nombreArea" name="nombreArea" value="' . $nombreArea . '" >' .
		       				'<input type="hidden" id="rendimiento" name="rendimiento" value="' . $rendimiento . '" >' .
		       				'<input type="hidden" id="idMiembroAsociacion" name="idMiembroAsociacion" value="' . $idMiembroAsociacion . '" >' .
		       				'<input type="hidden" id="idDetalleMiembro" name="idDetalleMiembro" value="' . $idDetalleMiembro . '" >' .
		       				'<input type="hidden" id="identificadorMiembroAsociacion" name="identificadorMiembroAsociacion" value="' . $identificadorMiembroAsociacion . '" >' .
		       				'<button class="icono" type="submit" ></button>' .
		       				'</form>' .
		       				'</td>' .
		       				'<td>' .
		       				'<form class="borrar" id="imprimirDetalle" data-rutaAplicacion="registroOperador" data-opcion="eliminarDetalleMiembroAsociacion">' .
		       				'<input type="hidden" id="idTipoOperacion" name="idTipoOperacion" value="' . $idTipoOperacion . '" >' .
		       				'<input type="hidden" id="nombreTipoOperacion" name="nombreTipoOperacion" value="' . $nombreTipoOperacion . '" >' .
		       				'<input type="hidden" id="idOperacion" name="idOperacion" value="' . $idOperacion . '" >' .
		       				'<input type="hidden" id="idProducto" name="idProducto" value="' . $idProducto . '" >' .
		       				'<input type="hidden" id="nombreProducto" name="nombreProducto" value="' . $nombreProducto . '" >' .
		       				'<input type="hidden" id="idSitio" name="idSitio" value="' . $idSitio . '" >' .
		       				'<input type="hidden" id="nombreSitio" name="nombreSitio" value="' . $nombreSitio . '" >' .
		       				'<input type="hidden" id="idMiembro" name="idMiembro" value="' . $idMiembroAsociacion . '" >' .
		       				'<input type="hidden" id="idDetalleMiembro" name="idDetalleMiembro" value="' . $idDetalleMiembro . '" >' .
		       				'<input type="hidden" id="idArea" name="idArea" value="' . $idArea . '" >' .
		       				'<input type="hidden" id="nombreArea" name="nombreArea" value="' . $nombreArea . '" >' .
		       				'<input type="hidden" id="rendimiento" name="rendimiento" value="' . $rendimiento . '" >' .
		       				'<button type="submit" class="icono iconoE"></button>' .
		       				'</form>' .
		       				'</td>' .
		       				'</tr>';
		}
		
		public function actualizarCabeceraMiembroAsociacionXIdMiembro ($conexion, $idMiembro, $identificadorMiembro, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap){

			$res = $conexion->ejecutarConsulta("UPDATE
													g_operadores.miembros_asociacion
												SET												  
													identificador_miembro_asociacion = '$identificadorMiembro',
													nombre_miembro_asociacion = '$nombreMiembroAsociacion',
													apellido_miembro_asociacion = '$apellidoMiembroAsociacion',
													codigo_magap = '$codigoMagap'
												WHERE
													id_miembro_asociacion = $idMiembro;");
			return $res;
		}		
		
		function obtenerDatosMiembroAsociacionXIdMiembro($conexion, $idMiembro){
		
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.miembros_asociacion
												WHERE
													id_miembro_asociacion = $idMiembro");
			return $res;
		}		
		
		function obtenerDatosDetalleMiembroAsociacionXIdMiembro($conexion, $idMiembro){
		
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.detalle_miembros_asociacion
												WHERE
													id_miembro_asociacion = $idMiembro");
			return $res;
		}		
		
		public function buscarMiembroAsociacion($conexion, $identificador, $identificadorAsociacion){
					
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.miembros_asociacion
												WHERE
													identificador_miembro_asociacion='$identificador'
													and identificador_asociacion = '$identificadorAsociacion'");
			return $res;
		}
		
		
		public function buscarMiembroDuenioSitio($conexion, $idSitio){
				
			$res = $conexion->ejecutarConsulta("SELECT
													distinct (ma.identificador_miembro_asociacion)
												FROM
													g_operadores.miembros_asociacion ma,
													g_operadores.detalle_miembros_asociacion dma
												WHERE
													ma.id_miembro_asociacion = dma.id_miembro_asociacion and
													id_sitio = $idSitio");
			
					return $res;
		}
		
		public function actualizarDetalleRendimientoAsociacion($conexion, $idDetalleMiembro, $idMiembro, $idOperacion, $idArea, $idSitio, $rendimiento){

		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.detalle_miembros_asociacion
											SET
												id_miembro_asociacion = $idMiembro,
												id_operacion = $idOperacion,
												id_area = $idArea,
												id_sitio = $idSitio,
												rendimiento = $rendimiento
											WHERE
												id_detalle_miembro_asociacion='$idDetalleMiembro';");
		
				return $res;
		}
		
		public function quitarAreaOperacionMiembroAsociacion($conexion, $idOPeracion){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_operadores.detalle_miembros_asociacion
											WHERE
												id_operacion = $idOPeracion;");
		
		return $res;
		
		}
		
		
		public function eliminarDetalleMiembroAsociacion($conexion, $idMiembro, $idSitio){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_operadores.detalle_miembros_asociacion
											WHERE
												id_miembro_asociacion = $idMiembro
												and id_sitio = $idSitio;");
		
			return $res;
		}
		
	
		public function obtenerCodigoPoaOperador ($conexion, $identificadorOperador, $idTipoOperacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_operadores.operaciones op,
												g_operadores.codigos_poa cpoa,
												g_operadores.subcodigos_poa scpoa
											WHERE
												op.identificador_operador = cpoa.identificador_operador
												and scpoa.estado = 'habilitado'
												and cpoa.id_codigo_poa = scpoa.id_codigo_poa
												and op.identificador_operador = '$identificadorOperador'
												and scpoa.id_tipo_operacion in ($idTipoOperacion);");
				
			return $res;
		}
		
	
		public function actualizarRutaPOA ($conexion, $idSubcodigoPoa, $idTipoOperacion, $rutaPoaUno, $rutaPoaDos, $rutaPoaTres, $rutaPoa = null){

		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.subcodigos_poa
											SET
												ruta_poa_uno ='$rutaPoaUno',
                                                ruta_poa_dos ='$rutaPoaDos',
                                                ruta_poa_tres ='$rutaPoaTres',
												ruta_poa = '$rutaPoa'
											WHERE
												id_subcodigo_poa = $idSubcodigoPoa
                                                and id_tipo_operacion = $idTipoOperacion;");
			return $res;
		}
		
		public function obtenerSitioYAreaXOperadorXTipoOperacionXProducto ($conexion, $identificadorOperador, $idTipoOperacion, $idProducto){

			$res = $conexion->ejecutarConsulta("SELECT
													distinct(s.identificador_operador), s.nombre_lugar, a.nombre_area, s.id_sitio, a.id_area as identificador_area, op.id_tipo_operacion, op.id_producto, a.superficie_utilizada
												FROM
													g_operadores.sitios s,
													g_operadores.areas a,
													g_operadores.productos_areas_operacion pao,
													g_operadores.operaciones op
												WHERE
													s.id_sitio = a.id_sitio
													and a.id_area = pao.id_area
													and op.id_operacion = pao.id_operacion
													and s.identificador_operador='$identificadorOperador'
													and op.id_tipo_operacion = $idTipoOperacion
													and op.id_producto = $idProducto;");
		
			return $res;
		}
		
		public function obtenerDatosOperadoresOMiembrosAsociacionXIdentificador($conexion, $identificador){
		
			$res = $conexion->ejecutarConsulta("SELECT
													distinct nombre_miembro_asociacion, apellido_miembro_asociacion
												FROM
													g_operadores.miembros_asociacion
												WHERE
													identificador_miembro_asociacion = '$identificador'
												UNION
												SELECT
													nombre_representante , apellido_representante
												FROM
													g_operadores.operadores
												WHERE
													identificador = '$identificador';");
			return $res;
		}
		
		public function buscarCodigoPoaOperador($conexion, $idOperador) {
		
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.codigos_poa
												WHERE
													identificador_operador ='$idOperador';");
			
			return $res;
		}
		
		public function  generarCodigoPOA($conexion){
		
			$res = $conexion->ejecutarConsulta("SELECT
													MAX(codigo_poa) as valor
												FROM
													g_operadores.codigos_poa;");
			return $res;
		}
		
		public function guardarCodigoPoaOperador($conexion, $idOperador, $codigoPOA) {
		
			$res = $conexion->ejecutarConsulta("INSERT INTO
													g_operadores.codigos_poa(identificador_operador, codigo_poa)
												VALUES('$idOperador', '$codigoPOA') RETURNING id_codigo_poa;");
			return $res;
		}
		
		public function buscarSubcodigoPoaOperador($conexion, $idCodigoPOA, $subcodigoPOA) {
		
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.subcodigos_poa
												WHERE
													id_codigo_poa = $idCodigoPOA
													and subcodigo_poa = '$subcodigoPOA';");
			
			return $res;
		}
		
		public function guardarSubcodigoPoaOperador($conexion, $codigoPOA, $subcodigoPOA, $idTipoOperacion, $estado) {
		
			$res = $conexion->ejecutarConsulta("INSERT INTO
													g_operadores.subcodigos_poa(id_codigo_poa, subcodigo_poa, id_tipo_operacion, estado)
												VALUES('$codigoPOA', '$subcodigoPOA', $idTipoOperacion, '$estado') RETURNING id_subcodigo_poa;");
			return $res;
		}

		public function guardarDetalleOperacionesOrganico($conexion, $idOperacionOrganico, $idTipoProduccion, $idTipoTransicion, $idAgenciaCertificadora, $idProducto){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.detalle_operaciones_organico(id_operacion_organico, id_tipo_produccion, id_tipo_transicion, id_agencia_certificadora, id_producto)
											VALUES ($idOperacionOrganico, $idTipoProduccion, $idTipoTransicion, $idAgenciaCertificadora, $idProducto);");
		
				return $res;
		}
		
		public function generarPOA($val) {
		
			$longitud = strlen($val);
			$vu=0;
			$total=0;
		
			for ($i=0; $i<$longitud ;$i++){
		
				switch ($i){
		
					case '0':
						$vu = $val[$i]*2;
						$vu>9 ? $vu = $vu - 9 : $vu;
						$total+= $vu;
						//echo "$val[$i]*2".'='.$vu.'|';
						break;
							
					case '1':
						$vu = $val[$i]*1;
						$vu>9 ? $vu = $vu - 9 : $vu;
						$total+= $vu;
						//echo "$val[$i]*1".'='.$vu.'|';
						break;
							
					case '2':
						$vu = $val[$i]*1;
						$vu>9 ? $vu = $vu - 9 : $vu;
						$total+= $vu;
						//echo "$val[$i]*1".'='.$vu.'|';
						break;
							
					case '3':
						$vu = $val[$i]*2;
						$vu>9 ? $vu = $vu - 9 : $vu;
						$total+= $vu;
						//echo "$val[$i]*2".'='.$vu.'|';
						break;
		
				}
		
			}
		
			$total = $total%10;
			$total = (10-$total)%10;
		
			return $val.'-'.$total;
		}
		
		
		public function generarSubcodigoPoa($codigoPOA, $codigoTipoOperacion) {
		
			$subcodigoPOA = "";
			
			switch ($codigoTipoOperacion){
		
				case 'PRO':
					$subcodigoPOA = $codigoPOA."A";
					break;
		
				case 'PRC':
				    $subcodigoPOA = $codigoPOA."B";
					break;
		
				case 'COM':
					$subcodigoPOA = $codigoPOA."C";
					break;
		
				case 'REC':
					$subcodigoPOA = $codigoPOA."D";
					break;
		
			}
		
			return $subcodigoPOA;
		}

		public function buscarAreasOperacionPorSolicitud($conexion, $idOperacion, $idArea, $identificadorOperador, $estado = 'eliminado'){
			
			$consulta = "SELECT
							*, o.estado as estado_operacion,o.estado_anterior
						FROM
							g_operadores.operaciones o,
							g_operadores.productos_areas_operacion sa
						WHERE
							o.id_tipo_operacion = $idOperacion and
							o.identificador_operador = '$identificadorOperador' and
							sa.id_area = $idArea and
							o.id_operacion = sa.id_operacion and
							o.estado != '$estado';";

			$res = $conexion->ejecutarConsulta($consulta);				
				
			return $res;
		}
		
		public function guardarNuevaOperacionPorTipoOperacion($conexion,$idTipoOperacion,$identificadorOperador, $idOperadorTipoOperacion, $idHistoricoOperacion, $estado, $idVigenciaDocumento){
			
			$consulta = "INSERT INTO g_operadores.operaciones(
					id_tipo_operacion, identificador_operador, estado, fecha_creacion, id_operador_tipo_operacion, id_historial_operacion, id_vigencia_documento)
					VALUES ($idTipoOperacion,'$identificadorOperador','$estado', now(), $idOperadorTipoOperacion, $idHistoricoOperacion, $idVigenciaDocumento) returning id_operacion";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}
		
		public function buscarNombreAreaPorSitioPorTipoOperacion($conexion, $idTipoOperacion, $identificadorOperador, $idSitio, $idOperacion){
			
			$consulta = "SELECT array_to_string(ARRAY(
													SELECT
														distinct a.nombre_area 
													FROM 
														g_operadores.areas a INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area 
														INNER JOIN g_operadores.operaciones o ON pao.id_operacion = o.id_operacion
													WHERE  
														o.id_operacion = $idOperacion and a.id_sitio = $idSitio and o.id_tipo_operacion = $idTipoOperacion
													),', ') as nombre_area;";
								
			$res = $conexion->ejecutarConsulta($consulta);
			/*$nombreArea = '';
						
			while ($resultado = pg_fetch_assoc($res1)){
				$nombreArea .= $resultado['nombre_area'].', ';
			}
			
			$nombreArea = rtrim($nombreArea, ', ');*/
			
			//return $nombreArea;
			return pg_fetch_result($res, 0, 'nombre_area');
			
		}
		
		public function buscarSitiosPorOperadorPorTipoOperacion($conexion,$identificadorOperador, $estado){
		
			$res = $conexion->ejecutarConsulta("SELECT
													distinct s.id_sitio, s.nombre_lugar, o.estado
												FROM
													g_operadores.operaciones o,
													g_operadores.productos_areas_operacion pao,
													g_operadores.areas a,
													g_operadores.sitios s
												WHERE
													o.identificador_operador = '$identificadorOperador' and
													o.id_operacion = pao.id_operacion and
													pao.id_area = a.id_area and
													a.id_sitio = s.id_sitio and
													o.estado $estado 
												order by nombre_lugar");
					return $res;
		}
		
		/*public function buscarIdentificadorOperadorPorTipoOperacionArea($conexion, $identificadorOperador, $idArea, $idTipoOperacion, $estado= 'eliminado'){
				
			$consulta = "SELECT
							*
						FROM
							g_operadores.operadores_tipo_operaciones oto,
							g_operadores.operadores_area_operaciones oao
						WHERE
							oto.id_operador_tipo_operacion = oao.id_operador_tipo_operacion
							and oao.id_area = $idArea
							and oto.identificador_operador = '$identificadorOperador'
							and oto.id_tipo_operacion = $idTipoOperacion
							and estado != '$estado'";
				
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
				
		}*/
		
		public function guardarTipoOperacionPorIndentificadorSitio($conexion, $identificadorOperador, $idSitio, $idTipoOperacion, $estado = 'creado'){
		
			$consulta = "INSERT INTO g_operadores.operadores_tipo_operaciones(id_sitio, id_tipo_operacion, identificador_operador, estado)
										VALUES ($idSitio, $idTipoOperacion, '$identificadorOperador', '$estado') RETURNING id_operador_tipo_operacion;";
						
			$res = $conexion->ejecutarConsulta($consulta);
			
			return $res;
		
		}
		
		public function guardarAreaPorIdentificadorTipoOperacion($conexion, $idArea, $idOperadorTipoOperacion){
		
			$consulta = "INSERT INTO g_operadores.operadores_area_operaciones(id_area, id_operador_tipo_operacion)
									VALUES ($idArea, $idOperadorTipoOperacion);";
			
			$res = $conexion->ejecutarConsulta($consulta);
			
			return $res;
		
		}
		
		public function actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado){
		
			$consulta = "UPDATE
							g_operadores.operadores_tipo_operaciones
						SET
							estado = '$estado',
							fecha_modificacion = 'now()'
						WHERE
							id_operador_tipo_operacion = $idOperadorTipoOperacion;";
					
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		
		}
		
		public function actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion, $idOperacion){
		
			$consulta = "UPDATE
							g_operadores.operadores_tipo_operaciones
						SET
							id_operacion = '$idOperacion'
						WHERE
							id_operador_tipo_operacion = $idOperadorTipoOperacion;";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		
		}
		
		public function eliminarDatosTipoOperacionPorIndentificadorSitio ($conexion, $idOperadorTipoOperacion){
			
		$consulta = "DELETE FROM g_operadores.operadores_area_operaciones WHERE id_operador_tipo_operacion = $idOperadorTipoOperacion;";
		$consulta1 = "DELETE FROM g_operadores.operadores_tipo_operaciones WHERE id_operador_tipo_operacion = $idOperadorTipoOperacion;";
					
		$res = $conexion->ejecutarConsulta($consulta);
		$res = $conexion->ejecutarConsulta($consulta1);
		
				return $res;
		}
		
		public function guardarDatosHistoricoOperacion ($conexion, $idOperadorTipoOperacion){
		
			$consulta = "INSERT INTO g_operadores.historial_operaciones(id_operador_tipo_operacion) 
						VALUES ($idOperadorTipoOperacion) RETURNING id_historial_operacion;";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}
		
		/*public function actualizarFechaHistoricoOperacion ($conexion, $idHistorialOperacion){
		
			$consulta = "UPDATE 
							g_operadores.historial_operaciones
						SET 
							fecha = '$fecha'
						WHERE 
							id_historial_operacion = $idHistorialOperacion;";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}*/
		
		public function eliminarDatosHistoricoOperacion ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion){
				
			$consulta = "DELETE FROM 
							g_operadores.historial_operaciones 
						WHERE 
							id_historial_operacion = $idHistorialOperacion 
							and id_operador_tipo_operacion = $idOperadorTipoOperacion;";
				
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}
		
		public function obtenerMaximoIdentificadorHistoricoOperacion ($conexion, $idOperadorTipoOperacion){
		
			$consulta = "SELECT 
							max(id_historial_operacion) as id_historial_operacion
						FROM
							g_operadores.historial_operaciones
						WHERE
							 id_operador_tipo_operacion = $idOperadorTipoOperacion;";
					
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}
		
		public function guardarNuevoRepresentanteTecnico($conexion,$idOperadorTipoOperacion,$idOperacion,$idArea, $idHistorialOperacion){
		
				
			$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.representantes_tecnicos(
															id_operador_tipo_operacion,id_operacion, id_area, id_historial_operacion)
												VALUES ($idOperadorTipoOperacion,$idOperacion, '$idArea', $idHistorialOperacion)
															RETURNING id_representante_tecnico;");
						
					return $res;
		}
		
		public function consultarRepresentanteTecnicoOperacion($conexion,$idOperadorTipoOperacion, $idHistorialOperacion, $idOperacion, $idAreaOperacion){
		
			$res = $conexion->ejecutarConsulta("SELECT
													id_representante_tecnico
												FROM
													g_operadores.representantes_tecnicos
												WHERE
													id_operador_tipo_operacion='$idOperadorTipoOperacion' and
													id_historial_operacion = '$idHistorialOperacion' and
													id_area = '$idAreaOperacion' and
													id_operacion='$idOperacion';");
		
					return $res;
		}
		
		public function inactivarRepresentanteTecnico($conexion, $idDetalleRepresentanteTecnico, $estado = 'inactivo'){
		
			$conexion->ejecutarConsulta("UPDATE g_operadores.detalle_representantes_tecnicos 
											SET
												estado = '$estado',
												fecha_modificacion_representante = 'now()'
											WHERE
												id_detalle_representante_tecnico='$idDetalleRepresentanteTecnico';");
		}
		
		public function guardarNuevoDetalleRepresentanteTecnico($conexion, $idRepresentanteTecnico, $idTipoProducto, $identificadorRepresentante, $nombreRepresentante, $tituloAcademico, $numeroRegistro, $idAreaRepresentante){
			
			$consulta = "INSERT INTO g_operadores.detalle_representantes_tecnicos(
														id_representante_tecnico, id_tipo_producto,
														identificacion_representante, nombre_representante, titulo_academico, numero_registro_titulo, id_area_representante)
												VALUES ($idRepresentanteTecnico, $idTipoProducto, '$identificadorRepresentante', '$nombreRepresentante', '$tituloAcademico', '$numeroRegistro', '$idAreaRepresentante') RETURNING id_detalle_representante_tecnico;";
			
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}
		
		public function consultarDatosRepresentanteTecnicoOperacion($conexion,$idOperadorTipoOperacion,$idOperacion){
		
			$res = $conexion->ejecutarConsulta("SELECT DISTINCT
													rt.id_representante_tecnico,
													drt.id_tipo_producto,
													drt.id_subtipo_producto, rt.id_area
												FROM
													g_operadores.representantes_tecnicos  rt,
													g_operadores.detalle_representantes_tecnicos drt
												WHERE
													drt.id_representante_tecnico=rt.id_representante_tecnico and
													rt.id_operador_tipo_operacion='$idOperadorTipoOperacion' and rt.id_operacion='$idOperacion';");
		
			return $res;
		}
		
		public function actualizarEstadoPorOperadorTipoOperacionHistorial ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado, $observacion=null, $idVigenciaDocumento=null){
		
			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";

			$res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													estado = '$estado',
													observacion = '$observacion',
                                                    fecha_modificacion = now()
												where
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion and
													($idVigenciaDocumento is NULL or id_vigencia_documento = $idVigenciaDocumento)
													and estado not in ('noHabilitado');");
			return $res;
		}
		
		public function actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idVigenciaDocumento = null){
			
			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";
					
			$res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones o
												set
													estado_anterior = op.estado
												from
													g_operadores.operaciones op
												where
													o.id_operacion = op.id_operacion and
													op.id_operador_tipo_operacion = $idOperadorTipoOperacion and
													op.id_historial_operacion = $idHistorialOperacion and
													($idVigenciaDocumento is NULL or op.id_vigencia_documento = $idVigenciaDocumento)
													and op.estado not in ('noHabilitado');");
			return $res;
		}
		
		public function actualizarFechaAprobacionOperaciones ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idVigenciaDocumento=null){
			
			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";
			
			$res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													fecha_aprobacion = now()
												where
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion and
													($idVigenciaDocumento is NULL or id_vigencia_documento = $idVigenciaDocumento)
													and estado not in ('noHabilitado');");
			return $res;
		}
		
		public function actualizarFechaAprobacionOperacionesProcesoModificacion ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idVigenciaDocumento=null){
			
			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";
			
			$res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													fecha_aprobacion = now()
												where
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion and
													($idVigenciaDocumento is NULL or id_vigencia_documento = $idVigenciaDocumento)
													and estado not in ('noHabilitado')
													and fecha_aprobacion is null;");
			return $res;
		}
		
		public function cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idVigenciaDocumento = null){
		
			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";
			
			$res = $conexion->ejecutarConsulta("UPDATE 
													g_operadores.productos_areas_operacion as pao
												SET 
													estado =o.estado,
													observacion = o.observacion
												FROM 
													g_operadores.operaciones o 
												WHERE 
													pao.id_operacion = o.id_operacion
													and id_operador_tipo_operacion = $idOperadorTipoOperacion
													and id_historial_operacion = $idHistorialOperacion and
													($idVigenciaDocumento is NULL or id_vigencia_documento = $idVigenciaDocumento)
													and o.estado not in ('noHabilitado');");
			return $res;
		}
		
		public function obtenerProductosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado, $idVigenciaDocumento = null){

			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";

			$res = $conexion->ejecutarConsulta("SELECT 
													o.id_operacion, p.id_producto, nombre_comun, sp.nombre as nombre_subtipo, codificacion_subtipo_producto, tp.nombre as nombre_tipo 
												FROM
													g_operadores.operaciones o,
													g_catalogos.productos p,
													g_catalogos.subtipo_productos sp,
													g_catalogos.tipo_productos tp
												WHERE
													o.id_producto = p.id_producto 
													and p.id_subtipo_producto = sp.id_subtipo_producto 
													and sp.id_tipo_producto = tp.id_tipo_producto
													and id_operador_tipo_operacion in ($idOperadorTipoOperacion)
													and id_historial_operacion in ($idHistorialOperacion)
			                                        and o.estado = '$estado'
													and ($idVigenciaDocumento is NULL or o.id_vigencia_documento = $idVigenciaDocumento);");
	
			return $res;
		}
		
		public function obtenerOperadorTipoOperacionPorIdentificador($conexion, $idOperadorTipoOperacion){
			
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.operadores_tipo_operaciones
												WHERE
													id_operador_tipo_operacion = $idOperadorTipoOperacion;");
			
			return $res;
		}
		
		public function actualizarProductoOperacion($conexion, $idOperacion, $idProducto, $nombreProducto, $idVigencia = 0){
						
			$res = $conexion->ejecutarConsulta("UPDATE
													g_operadores.operaciones 
												SET
													id_producto = $idProducto,
													nombre_producto = '$nombreProducto',
													id_vigencia_documento = $idVigencia
												WHERE
													id_operacion = $idOperacion;");
			
			return $res;
			
		}
		
		public function imprimirLineaProductoOperacion($idSolicitud, $nombreTipoProducto, $nombreSubtipoProducto, $nombreProducto, $idProduto, $validacionProducto, $procesoEliminacion){
		
			$condicion = '';
			$condicionIngresoProducto = '';
			
			if($procesoEliminacion == '0'){
				$condicion = '<button type="submit" class="icono"></button>';
			}
			
			if($procesoEliminacion == '0' || $procesoEliminacion == '1'){
				$condicionIngresoProducto = ' class="ingresoProducto"';
			}
			
			$cadena = '<tr id="R'.$idSolicitud.'">' .
					'<td>'.$nombreTipoProducto.'</td>'.
					'<td>'.$nombreSubtipoProducto.'</td>'.
					'<td>'.$nombreProducto.'</td>';
						if($validacionProducto == 'SI'){
							$cadena .='<input type="hidden" name="datoProceso[]" value="' . $idSolicitud . '-'.$idProduto.'" >';
						}
						$cadena .='<td style="text-align:center">'.
						'<form class="borrar" data-rutaAplicacion="registroOperador" data-opcion="eliminarProducto">' .
							'<input type="hidden" name="idSolicitud" value="' . $idSolicitud . '" '.$condicionIngresoProducto.'>' .
							$condicion .
						'</form>' .
					'</td>' .
					'</tr>';
						
			return $cadena;
		}
		
		/*public function listarTipoOperacionPorIndentificadorSitio ($conexion, $identificador, $estado){
		
			$res = $conexion->ejecutarConsulta("SELECT
													distinct oto.id_operador_tipo_operacion, oto.identificador_operador, oto.estado, t.nombre,
													o.razon_social, o.nombre_representante, o.apellido_representante, st.provincia
												FROM
													g_operadores.operadores_tipo_operaciones oto,
													g_catalogos.tipos_operacion t,
													g_operadores.operadores o,
													g_operadores.operadores_area_operaciones oao,
													g_operadores.areas a,
													g_operadores.sitios st
												WHERE
													oto.identificador_operador = '$identificador' and
													oto.id_tipo_operacion = t.id_tipo_operacion and
													oto.identificador_operador = o.identificador and
													oto.id_operador_tipo_operacion = oao.id_operador_tipo_operacion and
													oao.id_area = a.id_area and
													a.id_sitio = st.id_sitio and
													oto.estado $estado");
			return $res;
		}*/
		
		public function obtenerOperacionesProcesar ($conexion){
			
			$res = $conexion->ejecutarConsulta("SELECT 
													distinct min(s.id_operacion) as id_operacion, s.identificador_operador, s.id_tipo_operacion, t.nombre as nombre_tipo_operacion, st.id_sitio, st.nombre_lugar
												FROM
													g_operadores.operaciones s, g_catalogos.tipos_operacion t, g_operadores.operadores o, g_operadores.productos_areas_operacion sa, g_operadores.areas a,g_operadores.sitios st
												WHERE
													s.id_tipo_operacion = t.id_tipo_operacion and s.identificador_operador = o.identificador and s.id_operacion = sa.id_operacion and sa.id_area = a.id_area and a.id_sitio = st.id_sitio
												GROUP BY 
													s.identificador_operador, s.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area
												ORDER BY
													id_operacion");
			
			return $res;
		}
		
		public function guardarTipoOperacionPorIndentificadorSitioOperacion($conexion, $identificadorOperador, $idSitio, $idTipoOperacion, $idOperacion, $estado = 'creado'){
		
			$consulta = "INSERT INTO g_operadores.operadores_tipo_operaciones(id_sitio, id_tipo_operacion, identificador_operador, id_operacion, estado)
			VALUES ($idSitio, $idTipoOperacion, '$identificadorOperador', $idOperacion ,'$estado') RETURNING id_operador_tipo_operacion;";
		
			$res = $conexion->ejecutarConsulta($consulta);
				
			return $res;
		
		}
		
		public function obtenerOperacionesAsociadasPorAreaTipoOperacion($conexion, $idTipoOperacion, $area){
			
			$consulta = "SELECT 
							distinct s.id_operacion 
						FROM
							g_operadores.productos_areas_operacion sa, g_operadores.operaciones s, g_operadores.areas a
	               		where 
							s.id_operacion = sa.id_operacion and sa.id_area = a.id_area and a.id_area IN ($area) 
	               			and s.id_tipo_operacion = $idTipoOperacion";
			
			$res = $conexion->ejecutarConsulta($consulta);
			
			return $res; 
		}
		
		public function actualizarOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idOperacionHistorial, $idOperacion){
			
			$consulta = "UPDATE 
							g_operadores.operaciones 
						SET 
							id_operador_tipo_operacion = $idOperadorTipoOperacion, 
							id_historial_operacion = $idOperacionHistorial 
						WHERE 
							id_operacion = $idOperacion;";
				
			$res = $conexion->ejecutarConsulta($consulta);
			
			return $res;
		}
		
		public function obtenerMaximoDocumentoOperador($conexion, $identificadorOperador, $tipo){
		    
		    $identificadorOperador = $identificadorOperador != "" ? "'$identificadorOperador'" : "NULL";
			
			$consulta = "SELECT 
							COALESCE(MAX(CAST(secuencial as  numeric(5))),0)+1 as secuencial
						FROM
							g_operadores.documentos_operador
						WHERE
                            tipo = '$tipo'
							and ($identificadorOperador is NULL or identificador_operador = $identificadorOperador );";
						
			$res = $conexion->ejecutarConsulta($consulta);
				
			return $res;
			
		}
		
		public function guardarDocumentoOperador($conexion, $idOperacion, $idOperadorTipoOperacion, $rutaArchivo, $tipo, $secuencia, $identificadorOperador, $nombre){
			
			$consulta = "INSERT INTO g_operadores.documentos_operador
							(id_operacion, id_operador_tipo_operacion, ruta_archivo, tipo, secuencial, identificador_operador, nombre)
						VALUES 
						($idOperacion, $idOperadorTipoOperacion, '$rutaArchivo', '$tipo', $secuencia, '$identificadorOperador', '$nombre');";
				
			$res = $conexion->ejecutarConsulta($consulta);
			
			return $res;
			
		}
		
		public function actualizarFechaFinalizacionOperacionesNuevos ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento = null){
			
			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";
			
		    $res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													fecha_finalizacion = now() + interval '" . $valorVigencia . "' " . $tipoTiempoVigencia . "
												where
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion and
		    										($idVigenciaDocumento is NULL or id_vigencia_documento = $idVigenciaDocumento);");
		    return $res;
		}
		
		
		public function actualizarFechaFinalizacionOperacionesAntiguos ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $fechaFinalizacionAntigua, $valorVigencia, $tipoTiempoVigencia, $idVigenciaDocumento){

		    $res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													fecha_finalizacion = '$fechaFinalizacionAntigua'::date + interval '" . $valorVigencia . "' " . $tipoTiempoVigencia . "
												where
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion and
		    										id_vigencia_documento = $idVigenciaDocumento;");
			return $res;
		}
		
		public function obtenerDocumentoGeneradoInspeccionPorIdentificador($conexion, $idOperacion){
				
			$consulta = "SELECT
							*
						FROM
							g_operadores.documentos_operador
						WHERE
							id_operacion = '$idOperacion'
							and estado = 'activo';";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
				
		}
		
		public function obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion){
		
			$consulta = "SELECT
							*
						FROM
							g_operadores.documentos_operador
						WHERE
							id_operador_tipo_operacion = '$idOperadorTipoOperacion'
							and estado = 'activo';";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		
		}
		
		public function obtenerRegistrosCaducidadVigenciaRegistroOperador($conexion, $meses){
			
			$consulta = "SELECT
                        	distinct identificador, razon_social, correo
                        FROM
                        	g_operadores.operaciones op,
                        	g_operadores.operadores o
                        WHERE
                        	op.identificador_operador = o.identificador 
                        	and (to_char(now()+ interval '$meses' month,'YYYY-MM-DD') = to_char(fecha_finalizacion,'YYYY-MM-DD') 
                        	or  (to_char(fecha_finalizacion,'YYYY-MM-DD') <= to_char(now()+ interval '$meses' month,'YYYY-MM-DD')   
                        	and estado = 'registrado'));";
			
			$res = $conexion->ejecutarConsulta($consulta);
			
			return $res;
			
		}
		
		public function consultarDatosRepresentanteTecnicoPorOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idOperacionHistorial){
			
			$res = $conexion->ejecutarConsulta("SELECT 
													drt.*
												FROM
													g_operadores.representantes_tecnicos  rt,
													g_operadores.detalle_representantes_tecnicos drt
												WHERE
													drt.id_representante_tecnico = rt.id_representante_tecnico and
													rt.id_operador_tipo_operacion='$idOperadorTipoOperacion' and 
													rt.id_historial_operacion='$idOperacionHistorial' and
													drt.estado = 'registrado';");
		
			return $res;
		}
		
		public function obtenerIdOperadorTipoOperacionHistorialXIdentificadorTipoOperacionSitio($conexion, $identificador, $tipoOperacion, $idSitio, $estado){
		    
		    $consulta = "select
                        	distinct min(s.id_operacion) as id_operacion, 
                        	s.id_operador_tipo_operacion,
                        	s.id_historial_operacion
                        from
                        	g_operadores.operaciones s,
                        	g_catalogos.tipos_operacion t,
                        	g_operadores.operadores o,
                        	g_operadores.productos_areas_operacion sa,
                        	g_operadores.areas a,
                        	g_operadores.sitios st
                        where
                        	s.identificador_operador = '$identificador' and
                        	s.id_tipo_operacion = t.id_tipo_operacion and
                        	s.identificador_operador = o.identificador and
                        	s.id_operacion = sa.id_operacion and
                        	sa.id_area = a.id_area and
                        	a.id_sitio = st.id_sitio and
                        	s.estado $estado and
                        	s.id_tipo_operacion = $tipoOperacion and 
                        	st.id_sitio = $idSitio
                        group by s.identificador_operador, s.estado, s.id_tipo_operacion, s.id_operador_tipo_operacion, s.id_historial_operacion
                        order by id_operacion";
		    		    		    
		    $res = $conexion->ejecutarConsulta($consulta);		    
		    
		    return $res;
		    
		}
		
		public function obtenerOperacionesCaducidadVigenciaRegistroOperadorXOperador($conexion, $identificador){
		
			$consulta = "SELECT
                        	op.id_operacion, op.id_operador_tipo_operacion, op.id_historial_operacion, op.fecha_finalizacion
                        FROM
                        	g_operadores.operaciones op
                        WHERE
                        	op.identificador_operador = '$identificador'
                        	and (to_char(now()+ interval '1' month,'YYYY-MM-DD') = to_char(fecha_finalizacion,'YYYY-MM-DD')
                        	or (to_char(fecha_finalizacion,'YYYY-MM-DD') <= to_char(now()+ interval '1' month,'YYYY-MM-DD')   
                        	and estado = 'registrado'))";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		
		}

		public function obtenerOperacionesACaducar($conexion, $estadoOperacion){
			
			$res = $conexion->ejecutarConsulta("SELECT
												    id_operacion, id_operador_tipo_operacion, id_historial_operacion, fecha_finalizacion
												FROM
												    g_operadores.operaciones
												WHERE
												    estado = '$estadoOperacion'
												    and to_char(fecha_finalizacion,'YYYY-MM-DD')::date = current_date;");
			
			return $res;
			
		}
		
		
		public function verificarExistenciaOperaciones($conexion, $identificadorOperador, $idTipoOperacion, $idArea, $estado = 'porCaducar', $idVigenciaDocumento = null){
		    
			$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";
			
		    $consulta = "SELECT
							*
						FROM
							g_operadores.operaciones op,
							g_operadores.productos_areas_operacion pao
						WHERE
							op.id_operacion = pao.id_operacion and
							op.id_tipo_operacion = $idTipoOperacion and
							pao.id_area = $idArea and
                            op.identificador_operador = '$identificadorOperador' and 
                            op.estado = '$estado' and
							($idVigenciaDocumento is NULL or op.id_vigencia_documento = $idVigenciaDocumento)
                        ORDER BY 1
                        LIMIT 1;";
		
		    $res = $conexion->ejecutarConsulta($consulta);
		    
		    return $res;
		    
		}
		
		public function actualizarEstadoOperacionesCaducadas ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idOperacion, $estado, $observacion=null){
			
			$res = $conexion->ejecutarConsulta("UPDATE
													g_operadores.operaciones
												SET
													estado = '$estado',
													observacion = '$observacion'
												WHERE
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion and
													id_operacion not in ($idOperacion) returning id_operacion;");
			return $res;
		}
		
		public function actualizarFechaAprobacionFinalizacionOperaciones ($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $fechaAprobacion, $fechaFinalizacion, $idVigencia){

		    $fechaFinalizacion = $fechaFinalizacion!="" ? "'" . $fechaFinalizacion . "'" : "null";
		    $fechaAprobacion = $fechaAprobacion!="" ? "'" . $fechaAprobacion . "'" : "null";
		    
		    $res = $conexion->ejecutarConsulta("update
													g_operadores.operaciones
												set
													fecha_aprobacion = $fechaAprobacion,
                                                    fecha_finalizacion = $fechaFinalizacion
												where
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion and
		    										id_vigencia_documento = $idVigencia and 
													estado not in ('noHabilitado');");
		    return $res;
		}
		///NUEVO REGISTRO OPERADOR LECHE
		
		public function guardarInformacionCentroAcopio($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $numeroTrabajadores, $idLaboratorio, $numeroProveedores, $idOperadorTipoOperacion, $horaRecoleccionManiana, $horaRecoleccionTarde, $perteneceMag){
				
			$res = $conexion->ejecutarConsulta("INSERT INTO
													g_operadores.centros_acopio (id_area, id_tipo_operacion, capacidad_instalada, codigo_unidad_medida, numero_trabajadores, id_laboratorio_leche, numero_proveedores, fecha_creacion, id_operador_tipo_operacion, hora_recoleccion_maniana, hora_recoleccion_tarde, pertenece_mag)
												VALUES
													($idArea, $idTipoOperacion, $capacidadInstalada, '$codigoUnidadMedida', $numeroTrabajadores, $idLaboratorio, $numeroProveedores, 'now()', $idOperadorTipoOperacion, '$horaRecoleccionManiana', '$horaRecoleccionTarde', '$perteneceMag') RETURNING id_centro_acopio;");
		
			return $res;
		}
		
		public function guardarInformacionDatosVehiculo($conexion, $idArea, $idTipoOperacion, $marca=null, $modelo=null, $tipo=null, $color=null, $clase=null, $placa=null,$registroContenedorVehiculo=null, $tipoTanque, $anio, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion, $horaInicioRecoleccion, $horaFinRecoleccion, $tipoContenedor = null, $caracteristicaContenedor=null,$servicio=null){

			$anio = $anio !="" ? "'" . $anio . "'" : "null";
			
			$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.datos_vehiculos(
												id_area, id_tipo_operacion, nombre_marca_vehiculo, nombre_modelo_vehiculo, nombre_tipo_vehiculo, nombre_color_vehiculo, nombre_clase_vehiculo, placa_vehiculo, registro_contenedor_vehiculo, id_tipo_tanque_vehiculo, anio_vehiculo, 
												capacidad_vehiculo, codigo_unidad_medida, fecha_creacion, id_operador_tipo_operacion, hora_inicio_recoleccion, hora_fin_recoleccion, tipo_contenedor,caracteristica_contenedor, servicio)
												VALUES
												($idArea, $idTipoOperacion, '$marca', '$modelo', '$tipo', '$color', '$clase', '$placa', '$registroContenedorVehiculo', '$tipoTanque', $anio, $capacidadInstalada, '$codigoUnidadMedida', 'now()', $idOperadorTipoOperacion, '$horaInicioRecoleccion', '$horaFinRecoleccion','$tipoContenedor','$caracteristicaContenedor', '$servicio') RETURNING id_dato_vehiculo;");
			  return $res;
		}
		
		public function buscarNombreAreaTematicaPorSitioPorTipoOperacion($conexion, $idTipoOperacion, $idSitio, $idOperacion, $estado){
			
			$consulta = "SELECT
							are.nombre as area_tematica, a.nombre_area, top.codigo||top.id_area as tipo_operacion_area
						FROM
							g_operadores.areas a,
							g_operadores.productos_areas_operacion pao,
							g_operadores.operaciones o,
							g_catalogos.tipos_operacion top,
							g_estructura.area are
						WHERE
							a.id_area = pao.id_area
							and pao.id_operacion = o.id_operacion
							and o.id_tipo_operacion = top.id_tipo_operacion
							and top.id_area = are.id_area			
							and o.id_operacion = $idOperacion
							and a.id_sitio = $idSitio
							and o.id_tipo_operacion = $idTipoOperacion
							and are.id_area $estado";
							
				$res = $conexion->ejecutarConsulta($consulta);

			return $res;
				
		}
		
		public function obtenerDatosCentroAcopioXIdArea($conexion, $idArea){		

			$consulta = "SELECT
							*
						FROM
							g_operadores.centros_acopio
						WHERE
							id_area = $idArea";
			
			$res = $conexion->ejecutarConsulta($consulta);
			
			return $res;
		}
		
		public function actualizarDatosCentroAcopioXidAreaXidTipoOperacion($conexion, $idCentroAcopio, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $numeroTrabajadores, $laboratorio, $numeroProveedores, $horaRecoleccionManiana, $horaRecoleccionTarde, $perteneceMag){
		
			$consulta = "UPDATE g_operadores.centros_acopio
						   SET 
						   		capacidad_instalada = $capacidadInstalada, 
						      	codigo_unidad_medida = '$codigoUnidadMedida', 
						      	numero_trabajadores = $numeroTrabajadores, 
						       	id_laboratorio_leche = $laboratorio, 
						       	numero_proveedores = $numeroProveedores,
						       	hora_recoleccion_maniana = '$horaRecoleccionManiana', 
						       	hora_recoleccion_tarde = '$horaRecoleccionTarde', 
						       	pertenece_mag = '$perteneceMag'
						 WHERE	
						 		id_centro_acopio = 	$idCentroAcopio and
								id_area = $idArea and
								id_tipo_operacion  = $idTipoOperacion";
	
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}
		
		
		public function verificarInformacionCentroAcopio($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion){

			$consulta = "SELECT
							*
						FROM
							g_operadores.centros_acopio
						WHERE
							id_area = $idArea
							and id_tipo_operacion = $idTipoOperacion
							and capacidad_instalada = $capacidadInstalada
       						and codigo_unidad_medida = '$codigoUnidadMedida'
							and id_operador_tipo_operacion = $idOperadorTipoOperacion";
				
			$res = $conexion->ejecutarConsulta($consulta);
				
			return $res;
		}		
		
		public function actualizarInformacionCentroAcopio($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion){

			$res = $conexion->ejecutarConsulta("UPDATE 
													g_operadores.centros_acopio
   												SET 
													capacidad_instalada = $capacidadInstalada, 
													codigo_unidad_medida = '$codigoUnidadMedida'
												 WHERE 
													id_area = $idArea
													and id_tipo_operacion = $idTipoOperacion
													and id_operador_tipo_operacion = $idOperadorTipoOperacion;");
		
			return $res;
		}
		
		public function obtenerDatosVehiculoXIdArea($conexion, $idArea){
		
			$consulta = "SELECT
							*
						FROM
							g_operadores.datos_vehiculos
						WHERE
							id_area = $idArea";
				
			$res = $conexion->ejecutarConsulta($consulta);
				
			return $res;
		}	
		
		public function actualizarDatosVehiculoXIdAreaXidTipoOperacion($conexion, $idDatoVehiculo, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $horaInicioRecoleccion, $horaFinRecoleccion,$tipoContenedor=null,$caracteristicaContenedor=null,$servicio=null){
		
			$consulta = "UPDATE g_operadores.datos_vehiculos
						   SET 
						       
						       	capacidad_vehiculo = $capacidadInstalada, 
						       	codigo_unidad_medida = '$codigoUnidadMedida',
						       	hora_inicio_recoleccion = '$horaInicioRecoleccion',
						       	hora_fin_recoleccion = '$horaFinRecoleccion',
                                tipo_contenedor = '$tipoContenedor',
                                caracteristica_contenedor = '$caracteristicaContenedor',
                                servicio = '$servicio'
						 WHERE		
								id_dato_vehiculo = $idDatoVehiculo and
								id_area = $idArea and
								id_tipo_operacion  = $idTipoOperacion";
			
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}

		public function verificarInformacionVehiculo($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion){
		
			$consulta = "SELECT
								*
						FROM
							g_operadores.datos_vehiculos
						WHERE
							id_area = $idArea
							and id_tipo_operacion = $idTipoOperacion
							and capacidad_vehiculo = $capacidadInstalada
							and codigo_unidad_medida = '$codigoUnidadMedida'
							and id_operador_tipo_operacion = $idOperadorTipoOperacion";
							
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		}
		
		public function actualizarInformacionVehiculo($conexion, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion){

			$res = $conexion->ejecutarConsulta("UPDATE
													g_operadores.datos_vehiculos
												SET
													capacidad_vehiculo = $capacidadInstalada, 
													codigo_unidad_medida = '$codigoUnidadMedida'
												WHERE
													id_area = $idArea
													and id_tipo_operacion = $idTipoOperacion
													and id_operador_tipo_operacion = $idOperadorTipoOperacion;");
										
					return $res;
		}
		
		public function obtenerOperacionesXIdOperadorTipoOperacionXHistorialOperacion($conexion, $idOperadorTipoOperacion, $idHistorialOperacion){

		    $res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.operaciones
												WHERE
													id_operador_tipo_operacion = $idOperadorTipoOperacion and
													id_historial_operacion = $idHistorialOperacion;");
		
			return $res;
		
		}
		
		public function verificarDetalleOperadorOrganico($conexion, $idOperacionOrganico, $idTipoOperador, $idTipoProduccion, $idTipoTransicion, $idAgenciaCertificadora, $idProducto){

		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM		
											g_operadores.detalle_operaciones_organico
											WHERE										
											id_operacion_organico = $idOperacionOrganico and
											id_tipo_operador = $idTipoOperador and
											id_tipo_produccion = $idTipoProduccion and
											id_tipo_transicion = $idTipoTransicion and
											id_agencia_certificadora = $idAgenciaCertificadora and
											id_producto = $idProducto;");
		
				return $res;
		}
		
		public function quitarOperacionesOrganico($conexion, $idOperacion){
			
			$res = $conexion->ejecutarConsulta("DELETE FROM
													g_operadores.operaciones_organico
												WHERE
													id_operacion = $idOperacion;");
		
			return $res;
		
		}
		
		public function obtenerProductosOrganicosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado){

			$res = $conexion->ejecutarConsulta("SELECT 
													p.id_producto, 
													nombre_comun, 
													sp.nombre as nombre_subtipo, 
													tp.nombre as nombre_tipo , 
													o.id_operacion,
													dma.id_miembro_asociacion,
													dma.rendimiento,
													dma.superficie_miembro,
													dma.superficie_sitio 
												FROM 
													g_operadores.operaciones o 
												INNER JOIN g_catalogos.productos p ON o.id_producto = p.id_producto 
												INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = sp.id_subtipo_producto 
												INNER JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
												INNER JOIN g_operadores.detalle_miembros_asociacion dma ON dma.id_operacion = o.id_operacion
												WHERE 
													o.id_operador_tipo_operacion = $idOperadorTipoOperacion 
													and o.id_historial_operacion = $idHistorialOperacion 
													and o.estado = '$estado' 
													and o.id_operacion not in (SELECT id_operacion FROM g_operadores.operaciones_organico oo WHERE oo.id_operacion = o.id_operacion);");
		
			return $res;
		}
		
		public function eliminarDocumentoXIdOperadorTipoOperacion ($conexion, $solicitud, $idOperadorTipoOperacion){

			$res = $conexion->ejecutarConsulta("DELETE 
												FROM
													g_operadores.documentos_operador
												WHERE
													id_operacion = $solicitud and
													id_operador_tipo_operacion = $idOperadorTipoOperacion;");
			return $res;
		}
		
		public function buscarOperacionesPorCodigoyAreaOperacion($conexion,$identificador,$codigoOperacion,$areaOperacion, $estado = 'registrado'){
		    
		    $res = $conexion->ejecutarConsulta("SELECT DISTINCT
                                                	op.id_operacion
                                                FROM
                                                	g_operadores.operaciones op
                                                	,g_catalogos.tipos_operacion t
                                                	,g_operadores.productos_areas_operacion pao
                                                	,g_operadores.sitios si
                                                	,g_operadores.areas ar
                                                WHERE
		        
                                                	op.id_tipo_operacion = t.id_tipo_operacion and
                                                	t.codigo IN $codigoOperacion and
                                                	t.id_area IN $areaOperacion and
                                                	op.estado='$estado' and
                                                	t.id_tipo_operacion=op.id_tipo_operacion and
                                                	si.identificador_operador=op.identificador_operador and
                                                	ar.id_sitio=si.id_sitio and
                                                	pao.id_operacion=op.id_operacion and
                                                	pao.id_area=ar.id_area and
                                                	op.identificador_operador='$identificador'
                                                ;");
		    return $res;
		}
		
		public function obtenerDatosVehiculoArea($conexion, $idOperadorTipoOperacion){

			$res = $conexion->ejecutarConsulta("SELECT
			                  	    distinct dv.registro_contenedor_vehiculo, 
									dv.placa_vehiculo, 
									dv.anio_vehiculo, 
									dv.capacidad_vehiculo, 
									dv.nombre_marca_vehiculo,
									dv.nombre_modelo_vehiculo,
									dv.nombre_tipo_vehiculo,
									dv.nombre_color_vehiculo,
									dv.nombre_clase_vehiculo,
			
									ttv.nombre nombre_tipo_tanque_vehiculo, 
									um.nombre,
									hora_inicio_recoleccion,
									hora_fin_recoleccion,
									dv.servicio,
									dv.tipo_contenedor, 
									dv.caracteristica_contenedor
		FROM
			g_operadores.operadores op,
			g_operadores.operaciones ope,
			g_operadores.productos_areas_operacion pao,
			g_operadores.areas a,
			g_operadores.sitios s,
			g_operadores.datos_vehiculos dv,
			g_administracion_catalogos.items_catalogo ttv,
			g_catalogos.unidades_medidas um											
		WHERE
			op.identificador = ope.identificador_operador and
			ope.id_operacion = pao.id_operacion and
			pao.id_area = a.id_area and
			a.id_sitio = s.id_sitio and
			a.id_area = dv.id_area and
			um.codigo = dv.codigo_unidad_medida and
			ttv.id_item = dv.id_tipo_tanque_vehiculo and
			ope.id_tipo_operacion = dv.id_tipo_operacion and
			ope.id_operador_tipo_operacion = dv.id_operador_tipo_operacion and
			dv.id_dato_vehiculo = (SELECT max(id_dato_vehiculo) FROM g_operadores.datos_vehiculos WHERE id_operador_tipo_operacion = $idOperadorTipoOperacion);");
			return $res;
		}
		
		public function obtenerDatosCentroAcopioArea($conexion, $idOperadorTipoOperacion){

			$res = $conexion->ejecutarConsulta("SELECT
													distinct ca.capacidad_instalada, 
													um.nombre, 
													ca.numero_trabajadores,
													ll.nombre nombre_laboratorio_leche,
													ca.numero_proveedores,
													ca.hora_recoleccion_maniana,
													ca.hora_recoleccion_tarde,
													ca.pertenece_mag
												FROM
													g_operadores.operadores op,
													g_operadores.operaciones ope,
													g_operadores.productos_areas_operacion pao,
													g_operadores.areas a,
													g_operadores.sitios s,
													g_operadores.centros_acopio ca,
													g_catalogos.unidades_medidas um,
													g_administracion_catalogos.items_catalogo ll
												
												WHERE
													op.identificador = ope.identificador_operador and
													ope.id_operacion = pao.id_operacion and
													pao.id_area = a.id_area and
													a.id_sitio = s.id_sitio and
													a.id_area = ca.id_area and
													ope.id_operador_tipo_operacion = ca.id_operador_tipo_operacion and						   
													um.codigo = ca.codigo_unidad_medida and
													ll.id_item = ca.id_laboratorio_leche and 
													ca.id_centro_acopio = (SELECT max(id_centro_acopio) FROM g_operadores.centros_acopio WHERE id_operador_tipo_operacion = $idOperadorTipoOperacion);");
			
			return $res;
		}

	public function obtenerSitioOperacionOperador($conexion, $idSitio, $tipoOperacion, $identificadorOperador){
		
		$res = $conexion->ejecutarConsulta("SELECT
												distinct o.id_operacion
											FROM
												g_operadores.sitios ss,
												g_operadores.areas a,
												g_operadores.productos_areas_operacion pao,
												g_operadores.operaciones o
											WHERE
												ss.id_sitio = a.id_sitio and
												a.id_area = pao.id_area and
												pao.id_operacion = o.id_operacion and
												ss.id_sitio = $idSitio and
												o.id_tipo_operacion = $tipoOperacion and
												o.identificador_operador = '$identificadorOperador' and
												o.estado not in ('noHabilitado','porCaducar');");
		return $res;
	}

	public function obtenerCodigoXIdOperadorTipoOperacion($conexion,$idOperadorTipoOperacion){
		
			$res = $conexion->ejecutarConsulta("SELECT
													MAX(op.id_operacion),
													top.codigo, 
													top.id_tipo_operacion,
													top.id_area,
													top.nombre
												FROM
													g_operadores.operaciones op,
													g_catalogos.tipos_operacion top
												WHERE
													op.id_tipo_operacion= top.id_tipo_operacion
													and op.id_operador_tipo_operacion= $idOperadorTipoOperacion
												GROUP BY 
													top.codigo, top.id_tipo_operacion, top.id_area;");
			return $res;
	}
			
	public function cambiarEstadoCentroAcopioXIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion, $estado){

	$res = $conexion->ejecutarConsulta("UPDATE
											g_operadores.centros_acopio
										SET
											estado_centro_acopio = '$estado'
										WHERE
											id_operador_tipo_operacion = '$idOperadorTipoOperacion';");
			return $res;
	}

	public function cambiarEstadoVehiculoRecolectorXIdOperadorTipoOperacion($conexion,$idOperadorTipoOperacion, $estado){

		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.datos_vehiculos
											SET
												estado_dato_vehiculo = '$estado'
											WHERE
												id_operador_tipo_operacion = '$idOperadorTipoOperacion';");
			return $res;
	}
		
	public function inactivarVehiculoRecolectorXAreaXIdOperadorTipoOperacion($conexion, $idArea, $idOperadorTipoOperacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.datos_vehiculos
											SET
												estado_dato_vehiculo = 'inactivo'
											WHERE
												id_area = '$idArea' and
												id_operador_tipo_operacion NOT IN ('$idOperadorTipoOperacion');");
		return $res;
	}
	
	public function obtenerCodigoOperadorLeche($conexion, $idOperador, $identificadorArea){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_operadores.codigos_operadores_leche
											WHERE
												identificador_operador = '$idOperador'
												and id_area = $identificadorArea;");
		return $res;
	}
	
	public function generarCodigoOperadorLeche($conexion, $inicialCodigoLeche){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(split_part(codigo_operador_leche, '$inicialCodigoLeche' , 2)::int)+1 as numero
											FROM
												g_operadores.codigos_operadores_leche
											WHERE
												codigo_operador_leche ilike '%$inicialCodigoLeche%';");																 
		return $res;
	}
	
	public function guardarCodigoOperadorLeche($conexion, $idOperador, $identificadorArea, $codigoOperadorLeche){

		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_operadores.codigos_operadores_leche(identificador_operador, id_area, codigo_operador_leche)
				VALUES ('$idOperador', $identificadorArea, '$codigoOperadorLeche');");
		
		return $res;
	}	
	
	public function obtenerDatosOperadorLecheXProviciaXFecha($conexion, $idProvincia, $fechaInicio, $fechaFin, $tipoOperacion){
		
		$fechaInicio = $fechaInicio." 00:00:00" ;
		$fechaFin = $fechaFin." 24:00:00" ;
		
		switch($tipoOperacion){
			
			case 'AI-ACO';
			$columnas = ", ca.numero_proveedores , ca.capacidad_instalada , ca.pertenece_mag, ca.hora_recoleccion_maniana, ca.hora_recoleccion_tarde";
			$tablas = " LEFT JOIN (SELECT
										ca1.numero_proveedores , ca1.capacidad_instalada
										, ca1.pertenece_mag, ca1.hora_recoleccion_maniana, ca1.hora_recoleccion_tarde, ca1.id_operador_tipo_operacion, ca1.id_area
									FROM
										g_operadores.centros_acopio ca1
									WHERE
										(SELECT max(id_centro_acopio) FROM g_operadores.centros_acopio WHERE id_operador_tipo_operacion = ca1.id_operador_tipo_operacion) = ca1.id_centro_acopio
										) ca ON ca.id_operador_tipo_operacion  = opc.id_operador_tipo_operacion and a.id_area = ca.id_area";
			$condicion = "topc.id_area||'-'||topc.codigo = 'AI-ACO'";
			break;
			case 'AI-MDT';
			$columnas = ", dv.placa_vehiculo, tv.nombre as tipo_vehiculo , mv.nombre as marca_vehiculo, cv.nombre as color_vehiulo, dv.anio_vehiculo, ttv.nombre as tipo_tanque_vehiculo, dv.capacidad_vehiculo, dv.hora_inicio_recoleccion, dv.hora_fin_recoleccion, opc.observacion";
			$tablas = " LEFT JOIN (SELECT
										dv1.id_tipo_tanque_vehiculo, dv1.id_color_vehiculo, dv1.id_marca_vehiculo,
										dv1.id_tipo_vehiculo, dv1.placa_vehiculo, dv1.anio_vehiculo, dv1.capacidad_vehiculo, dv1.hora_inicio_recoleccion, dv1.hora_fin_recoleccion, dv1.id_operador_tipo_operacion, dv1.id_area
									FROM
										g_operadores.datos_vehiculos dv1
									WHERE
										(SELECT max(id_dato_vehiculo) FROM g_operadores.datos_vehiculos WHERE id_operador_tipo_operacion = dv1.id_operador_tipo_operacion) = dv1.id_dato_vehiculo
										) dv ON dv.id_operador_tipo_operacion  = opc.id_operador_tipo_operacion and a.id_area = dv.id_area
						LEFT JOIN g_administracion_catalogos.items_catalogo tv ON dv.id_tipo_vehiculo = tv.id_item
						LEFT JOIN g_administracion_catalogos.items_catalogo mv ON dv.id_marca_vehiculo = mv.id_item
						LEFT JOIN g_administracion_catalogos.items_catalogo cv ON dv.id_color_vehiculo = cv.id_item
						LEFT JOIN g_administracion_catalogos.items_catalogo ttv ON dv.id_tipo_tanque_vehiculo = ttv.id_item";
			$condicion = "topc.id_area||'-'||topc.codigo = 'AI-MDT'";
			break;
			
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
											distinct opr.identificador
											,col.codigo_operador_leche
											,opc.estado
											,opc.nombre_producto
											,opc.fecha_creacion
											,opc.fecha_aprobacion
											,opc.fecha_finalizacion
											,opc.observacion
											,s.provincia
											,s.canton
											,s.parroquia
											,a.nombre_area
											,s.direccion
											,s.telefono
											,CASE WHEN opr.razon_social = '' THEN opr.nombre_representante ||' '|| opr.apellido_representante ELSE opr.razon_social END nombre_operador
											,topc.nombre as operacion
											,topc.id_area
											,opr.correo
											,s.latitud
											,s.longitud
											".$columnas."
										FROM
											g_operadores.operadores opr
											INNER JOIN g_operadores.operaciones opc ON opr.identificador = opc.identificador_operador
											INNER JOIN g_operadores.productos_areas_operacion pao ON opc.id_operacion = pao.id_operacion
											INNER JOIN g_catalogos.tipos_operacion topc ON topc.id_tipo_operacion = opc.id_tipo_operacion
											INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
											INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
											LEFT JOIN g_operadores.codigos_operadores_leche col ON opr.identificador = col.identificador_operador and a.id_area = col.id_area
											".$tablas."
										WHERE
											".$condicion."
											and s.provincia = '$idProvincia'
											and CASE WHEN opc.estado = 'registrado' THEN opc.fecha_aprobacion WHEN opc.estado = 'porCaducar' OR opc.estado = 'noHabilitado' THEN opc.fecha_modificacion ELSE opc.fecha_creacion END >= '$fechaInicio'
											and CASE WHEN opc.estado = 'registrado' THEN opc.fecha_aprobacion WHEN opc.estado = 'porCaducar' OR opc.estado = 'noHabilitado' THEN opc.fecha_modificacion ELSE opc.fecha_creacion END <= '$fechaFin'
										ORDER BY
											nombre_operador ,provincia ,operacion;");
		
		return $res;
	}
	
	function crearCodigoOperadorLeche($conexion, $tipoOperacion, $codigoProvinciaSitio, $identificadorArea, $idOperador){
			
		$anioCertificado = date("Y");
			
		$qBuscarCodigoLeche = $this->obtenerCodigoOperadorLeche($conexion, $idOperador, $identificadorArea);
		$buscarCodigoLeche = pg_fetch_assoc($qBuscarCodigoLeche);
	
		if(pg_num_rows($qBuscarCodigoLeche) != 0){
			$codigoOperadorLeche = $buscarCodigoLeche['codigo_operador_leche'];
		}else{
			switch($tipoOperacion){
				case "ACO":
					$codigoOperacion = 'CA';
				break;
				case "MDT":
					$codigoOperacion = 'MT';
				break;
			}
	
			$inicialCodigoLeche = 'AGRO-'.$codigoOperacion.'-'.$codigoProvinciaSitio.'-';

			$qCodigoOperadorLeche = pg_fetch_assoc($this->generarCodigoOperadorLeche($conexion, $inicialCodigoLeche));
			$finalCodigoLeche = $qCodigoOperadorLeche['numero'];

			if($finalCodigoLeche != ""){	
				$codigoOperadorLeche = $inicialCodigoLeche.str_pad($finalCodigoLeche, 4, "0", STR_PAD_LEFT);
			}else{
				$codigoOperadorLeche = $inicialCodigoLeche.'0001';
			}
			$this->guardarCodigoOperadorLeche($conexion, $idOperador, $identificadorArea, $codigoOperadorLeche);
		}

		return $codigoOperadorLeche.'-'.$anioCertificado;
	}
	
	public function obtenerDatosOperadorLecheConsolidadoXProvicia($conexion, $idProvincia,  $tipoOperacion, $idProducto){
			
		switch($tipoOperacion){
				
			case 'AI-ACO';				
				$top = "'$tipoOperacion'";
				$provincia = "'$idProvincia'";
				$ct = 'agrupar text, mes text, anio text, asignadoDocumental text, asignadoInspeccion text, cargarAdjunto text, cargarProducto text, declararICentroAcopio text, documental text, inspeccion text, noHabilitado text, porCaducar text, registrado text, subsanacion text, subsanacionProducto text';
			break;
			case 'AI-MDT';
				$top = "'$tipoOperacion'";
				$provincia = "'$idProvincia'";
				$ct = 'agrupar text, mes text, anio text, asignadoDocumental text, asignadoInspeccion text, cargarAdjunto text, cargarProducto text, declararDVehiculo text, documental text, inspeccion text, noHabilitado text, porCaducar text, registrado text, subsanacion text, subsanacionProducto text';
			break;
	
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM crosstab
											('SELECT 
													 CASE WHEN opc.estado = ''registrado'' THEN to_char(opc.fecha_aprobacion,''YYYY-MM'') WHEN opc.estado = ''porCaducar'' OR opc.estado = ''noHabilitado'' THEN to_char(opc.fecha_modificacion,''YYYY-MM'') ELSE to_char(opc.fecha_creacion,''YYYY-MM'') END, 
                                            		 CASE WHEN opc.estado = ''registrado'' THEN to_char(opc.fecha_aprobacion,''TMMonth'') WHEN opc.estado = ''porCaducar'' OR opc.estado = ''noHabilitado'' THEN to_char(opc.fecha_modificacion,''TMMonth'') ELSE to_char(opc.fecha_creacion,''TMMonth'') END, 
                                            		 CASE WHEN opc.estado = ''registrado'' THEN date_part(''year''::text, opc.fecha_aprobacion) WHEN opc.estado = ''porCaducar'' OR opc.estado = ''noHabilitado'' THEN date_part(''year''::text, opc.fecha_modificacion) ELSE date_part(''year''::text, opc.fecha_creacion) END, 
                                            		 opc.estado, count(opc.id_operacion)						  
												FROM 
													g_operadores.operadores opr ,
													g_operadores.operaciones opc ,
													g_catalogos.tipos_operacion topc ,
													g_operadores.productos_areas_operacion pao ,
													g_operadores.areas a , g_operadores.sitios s
													
												WHERE 
													opr.identificador = opc.identificador_operador and 
													opc.id_operacion = pao.id_operacion and 
													topc.id_tipo_operacion = opc.id_tipo_operacion and 
													pao.id_area = a.id_area and a.id_sitio = s.id_sitio and 
													topc.id_area||''-''||topc.codigo = '$top'
													and s.provincia = '$provincia' and
                                                    opc.id_producto = $idProducto
                                                    GROUP BY 1, 2, 3, opc.estado ORDER BY 1', 
												'SELECT * FROM ((SELECT fo.estado FROM g_operadores.flujos_operaciones fo, g_catalogos.tipos_operacion top WHERE fo.id_flujo in (SELECT id_flujo_operacion FROM g_catalogos.tipos_operacion WHERE id_area || ''-'' || codigo in ('$top'))) UNION (SELECT ''noHabilitado'') UNION (SELECT ''asignadoInspeccion'') UNION (SELECT ''asignadoDocumental'') UNION (SELECT ''porCaducar'') UNION (SELECT ''subsanacion'') UNION (SELECT ''subsanacionProducto''))as flujo ORDER BY 1') 
												AS ct($ct);");
		
		return $res;
	}
	
	public function obtenerSitioyCodigoPorProvinciaOperadoryOperacion($conexion, $codigo, $area, $identificadorOperador, $nombreProvincia){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												distinct s.id_sitio, s.identificador_operador||'.'||s.codigo_provincia||s.codigo ||' - '|| s.nombre_lugar as sitio 
											FROM
												g_operadores.operaciones o,
												g_catalogos.tipos_operacion tp,	
												g_catalogos.areas_operacion ao,
												g_operadores.productos_areas_operacion pao,
												g_operadores.sitios s,
												g_operadores.areas a
											WHERE
												o.id_tipo_operacion = tp.id_tipo_operacion and
												s.id_sitio=a.id_sitio and
												ao.id_tipo_operacion=o.id_tipo_operacion and 
												pao.id_operacion = o.id_operacion and
												pao.id_area=a.id_area and
												o.identificador_operador=s.identificador_operador and
												tp.codigo IN ('$codigo') and
												tp.id_area IN ('$area') and
												a.estado='creado' and
												s.estado='creado' and
												o.estado IN ('registrado','registradoObservacion') and
												upper(s.provincia) = upper('$nombreProvincia') and 
												s.identificador_operador='$identificadorOperador';");
		return $res;
	}
	
	public function obtenerAreayCodigoPorSitioProvinciaOperadoryOperacion($conexion, $codigo, $area, $identificadorOperador, $nombreProvincia, $idSitio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct a.id_area, s.identificador_operador||'.'||s.codigo_provincia||s.codigo||a.codigo||a.secuencial ||' - '|| a.nombre_area as area
											FROM
												g_operadores.operaciones o,
												g_catalogos.tipos_operacion tp,
												g_catalogos.areas_operacion ao,
												g_operadores.productos_areas_operacion pao,
												g_operadores.sitios s,
												g_operadores.areas a
											WHERE
												o.id_tipo_operacion = tp.id_tipo_operacion and
												s.id_sitio=a.id_sitio and
												ao.id_tipo_operacion=o.id_tipo_operacion and
												pao.id_operacion = o.id_operacion and
												pao.id_area=a.id_area and
												o.identificador_operador=s.identificador_operador and
												tp.codigo IN ('$codigo') and
												tp.id_area IN ('$area') and
												a.estado='creado' and
												s.estado='creado' and
												s.id_sitio='$idSitio' and 
												o.estado IN ('registrado','registradoObservacion') and
												upper(s.provincia) = upper('$nombreProvincia') and
												s.identificador_operador='$identificadorOperador';");
				return $res;
	}
		
	public function obtenerCodigoSitioAreaXidSitioIdArea($conexion, $idArea){
	
		$res = $conexion->ejecutarConsulta("SELECT
												s.id_sitio, 
												s.identificador_operador||'.'||s.codigo_provincia||s.codigo ||' - '|| s.nombre_lugar as sitio, 
												upper(s.provincia) provincia,
												a.id_area, 
												s.identificador_operador||'.'||s.codigo_provincia||s.codigo||a.codigo||a.secuencial ||' - '|| a.nombre_area as area
											FROM
												g_operadores.sitios s,
												g_operadores.areas a
											WHERE
												s.id_sitio=a.id_sitio and
												a.id_area='$idArea';");
		return $res;
	}
	
	public function listarCentroAcopioXIdAreaXidTipoOperacion($conexion, $idArea, $idTipoOperacion, $idOperadorTipoOperacion, $estado){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_operadores.centros_acopio
											WHERE
												id_area = $idArea and
												id_tipo_operacion = $idTipoOperacion and
												id_operador_tipo_operacion = $idOperadorTipoOperacion and
												estado_centro_acopio = '$estado'
												and id_centro_acopio = (SELECT
																			max(id_centro_acopio)
																		FROM
																			g_operadores.centros_acopio
																		WHERE
																			id_area = $idArea and
																			id_tipo_operacion = $idTipoOperacion and
																			id_operador_tipo_operacion = $idOperadorTipoOperacion and
																			estado_centro_acopio = '$estado');");
		return $res;
	}
	
	public function listarDatosVehiculoXIdAreaXidTipoOperacion($conexion, $idArea, $idTipoOperacion, $idOperadorTipoOperacion, $estado){
	

		
		$res = $conexion->ejecutarConsulta("SELECT
		dv.id_dato_vehiculo,
		dv.id_area,
		id_tipo_operacion,
		CASE WHEN (dv.nombre_marca_vehiculo = '') THEN
			'N/A'
		ELSE
			dv.nombre_marca_vehiculo
		END AS nombre_marca_vehiculo,
		CASE WHEN (dv.nombre_modelo_vehiculo = '') THEN
			'N/A'
		ELSE
		   dv.nombre_tipo_vehiculo
		END AS nombre_modelo_vehiculo,
		 CASE WHEN (dv.nombre_tipo_vehiculo = '') THEN
			'N/A'
		ELSE
		   dv.nombre_tipo_vehiculo
		END AS nombre_tipo_vehiculo,
		CASE WHEN (dv.nombre_color_vehiculo = '') THEN
			'N/A'
		ELSE
			dv.nombre_color_vehiculo
		END AS nombre_color_vehiculo,
		CASE WHEN (dv.nombre_clase_vehiculo = '') THEN
			'N/A'
		ELSE
		   dv.nombre_clase_vehiculo
		END AS nombre_clase_vehiculo,
		CASE WHEN (dv.placa_vehiculo = '') THEN
			'N/A'
		ELSE
			dv.placa_vehiculo
		END AS placa_vehiculo,
		dv.registro_contenedor_vehiculo,
		dv.id_tipo_tanque_vehiculo,
		COALESCE (dv.anio_vehiculo::varchar, 'N/A') AS anio_vehiculo,
		dv.capacidad_vehiculo,
		dv.codigo_unidad_medida,
		dv.fecha_creacion,
		dv.id_operador_tipo_operacion,
		dv.estado_dato_vehiculo,
		dv.hora_inicio_recoleccion,
		dv.hora_fin_recoleccion,
		dv.tipo_contenedor,
		dv.caracteristica_contenedor,
		CASE WHEN (dv.servicio = '') THEN
			'N/A'
		ELSE
			dv.servicio
		END AS servicio
		FROM g_operadores.datos_vehiculos dv
		WHERE
												id_area = $idArea and
												id_tipo_operacion = $idTipoOperacion and
												id_operador_tipo_operacion = $idOperadorTipoOperacion and
												estado_dato_vehiculo = '$estado'
												and id_dato_vehiculo = (SELECT
																			max(id_dato_vehiculo)
																		FROM
																			g_operadores.datos_vehiculos
																		WHERE
																			id_area = $idArea and
																			id_tipo_operacion = $idTipoOperacion and
																			id_operador_tipo_operacion = $idOperadorTipoOperacion and
																			estado_dato_vehiculo = '$estado');");

		
		return $res;
	}
		
	public function obtenerSuperficieUtilizadaPorIdArea ($conexion, $idArea){
		
		$res = $conexion->ejecutarConsulta("SELECT
					sum(superficie_miembro) superficie_miembro
				FROM
					g_operadores.detalle_miembros_asociacion
				WHERE
					id_area = $idArea;");
						
		return $res;		
	}
	
	public function obtenerTipoOperacionUbicacionPorEstadoArea($conexion, $idArea, $estado){

	    $consulta = "SELECT
                        distinct top.id_tipo_operacion,
                        top.nombre as nombre_operacion,
                        ubicacion_revision
					FROM
                        g_catalogos.tipos_operacion top 
                        INNER JOIN g_operadores.operaciones op ON top.id_tipo_operacion = op.id_tipo_operacion	
                    WHERE 
                        top.id_area = '$idArea' and 
                        op.estado = '$estado' and 
                        top.estado not in (2)	
                    ORDER BY 2;";

	    $res = $conexion->ejecutarConsulta($consulta);

	    return $res;
	}
	
	public function obtenerTipoOperacionUbicacionPorEstadoAreaProvincia($conexion, $idArea, $estado, $provincia, $tipoOperacion){
	    
	    $consulta = "SELECT 
                    	distinct top.id_tipo_operacion,
                    	top.nombre as nombre_operacion,
                        top.ubicacion_revision,
						top.codigo
                    FROM
                    	g_catalogos.tipos_operacion top INNER JOIN g_operadores.operaciones op ON top.id_tipo_operacion = op.id_tipo_operacion
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_operacion = op.id_operacion 
                    	INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area 
                    	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio 
                    WHERE 
                    	top.id_area = '$idArea' and 
                    	op.estado = '$estado' and 
                    	top.estado not in (2) and 
                    	upper(s.provincia) IN $provincia and 
                    	top.id_tipo_operacion IN $tipoOperacion
                    ORDER BY 2;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerOperadorPorTipoOperacionProvinciaEstado($conexion, $provincia, $estado, $tipoOperacion, $revisionUbicacion){

	    $busqueda = '';
	    
	    if($revisionUbicacion == 'provincia'){
	        $busqueda = " and UPPER(s.provincia) IN ". $provincia;
	    }

	    $consulta = "SELECT
                    	distinct (o.identificador), case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador, MIN(op.fecha_modificacion) as fecha_creacion
                    FROM
                    	g_operadores.operadores o
                    	INNER JOIN g_operadores.operaciones op ON o.identificador = op.identificador_operador
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                    	INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                    	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                    WHERE
                    	id_tipo_operacion = $tipoOperacion
                    	and op.estado = '$estado'
                        ".$busqueda."
                    GROUP BY o.identificador ORDER BY MIN(op.fecha_modificacion) asc";

        $res = $conexion->ejecutarConsulta($consulta);

	    return $res;
	}
	
	public function obtenerAsignacionTipoOperacionPorEstadoAreaIdentificador($conexion, $identificador, $estado, $tipoSolicitud, $tipoInspector, $idArea, $tipoProceso, $idTipoOperacion = null){
	    
	    $columnas = '';
	    $busqueda = '';

	    if($tipoProceso == 'tipoOperacion'){
	        $columnas = 'distinct top.id_tipo_operacion, top.nombre as nombre_operacion, top.ubicacion_revision';
	    }else{
	        $busqueda = "and top.id_tipo_operacion = '$idTipoOperacion' GROUP BY o.identificador, top.id_area ORDER BY MIN(op.fecha_creacion) asc";
	        $columnas = "distinct (o.identificador), case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador, MIN(op.fecha_creacion) as fecha_creacion, top.id_area";
	    }

	    $consulta = "SELECT
                    	".$columnas."
                    FROM
                    	g_operadores.operaciones op,
                    	g_catalogos.tipos_operacion top,
                    	g_operadores.operadores o,
                    	g_revision_solicitudes.asignacion_coordinador ac
                    WHERE
                    	op.id_tipo_operacion = top.id_tipo_operacion and
                    	op.identificador_operador = o.identificador and
                    	op.id_operacion = ac.id_solicitud and
                    	op.estado = '$estado' and
                    	ac.identificador_inspector = '$identificador' and
                    	ac.tipo_solicitud = '$tipoSolicitud' and
                    	ac.tipo_inspector = '$tipoInspector'
                    	and top.id_area = '$idArea'
                        ".$busqueda.";";

	    $res = $conexion->ejecutarConsulta($consulta);

	    return $res;

	}
	
	public function obtenerIdOperadorTipoOperacionPorTipoOperacionAreaEstado($conexion, $identificador, $estado, $idTipoOperacion, $idArea){

	    $consulta = "SELECT
                    	op.id_operador_tipo_operacion
                    FROM
                    	g_operadores.operaciones op 
                        INNER JOIN g_operadores.documentos_operador od ON op.id_operador_tipo_operacion = od.id_operador_tipo_operacion
                        INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                    WHERE
                    	op.id_tipo_operacion = $idTipoOperacion
                    	and pao.id_area = $idArea
                    	and op.identificador_operador = '$identificador'
                    	and op.estado in $estado;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function obtenerMiembroAsociacionXIdSitio($conexion, $idSitio){
	
		$res = $conexion->ejecutarConsulta("SELECT
											distinct
												ma.identificador_miembro_asociacion, ma.id_miembro_asociacion, ma.nombre_miembro_asociacion, ma.apellido_miembro_asociacion, ma.codigo_magap, ma.id_sitio
											FROM
												g_operadores.miembros_asociacion ma LEFT JOIN g_operadores.detalle_miembros_asociacion dma ON ma.id_miembro_asociacion = dma.id_miembro_asociacion WHERE ma.id_sitio = $idSitio");
													
				return $res;
	}
	
	public function obtenerOperacionesRendimientoXIdArea($conexion, $idArea){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct op.id_operacion, op.id_operador_tipo_operacion, op.id_historial_operacion, a.id_sitio, a.id_area, a.superficie_utilizada, op.id_tipo_operacion, op.estado, op.id_producto, op.nombre_producto, op.id_operador_tipo_operacion, op.observacion, dma.id_miembro_asociacion, dma.superficie_miembro, dma.rendimiento
											FROM
												g_operadores.areas a
											INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
											INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
											FULL OUTER JOIN g_operadores.detalle_miembros_asociacion dma ON dma.id_operacion = op.id_operacion
											WHERE
												a.id_area = $idArea
												and op.estado in ('cargarRendimiento', 'registrado', 'subsanacion', 'subsanacionProducto')
											ORDER BY op.id_operacion asc");
					
				return $res;
	}
	
	public function obtenerNumeroOperacionesPorArea($conexion, $idArea){
	
		$res = $conexion->ejecutarConsulta("SELECT
												count(op.id_operacion) as numero_operaciones
											FROM
												g_operadores.operaciones op ,
												g_operadores.areas a ,
												g_operadores.productos_areas_operacion pao
											WHERE
												pao.id_operacion = op.id_operacion
												and pao.id_area = a.id_area and a.id_area = $idArea
												and op.estado in ('cargarRendimiento', 'registrado', 'subsanacion', 'subsanacionProducto')");
					
				return $res;
	}
	
	public function verificarIdentificadorMiembro($conexion, $identificadorMiembroAsociacion, $identificadorAsociacion){

		$res = $conexion->ejecutarConsulta("SELECT
												distinct codigo_miembro_asociacion, identificador_miembro_asociacion, nombre_miembro_asociacion, apellido_miembro_asociacion
											FROM
												g_operadores.miembros_asociacion
											WHERE
												identificador_miembro_asociacion = '$identificadorMiembroAsociacion'
												and identificador_asociacion = '$identificadorAsociacion';");
									
		return $res;
	}
	
	public function verificarDatosMiembroAsociacion($conexion, $idMiembroAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_operadores.miembros_asociacion
											WHERE
												id_miembro_asociacion = '$idMiembroAsociacion'																					
												and nombre_miembro_asociacion = '$nombreMiembroAsociacion'
												and apellido_miembro_asociacion = '$apellidoMiembroAsociacion'
												and codigo_magap = '$codigoMagap';");
									
				return $res;
	}
	
	public function guardarMiembroAsociacion($conexion, $codigoMiembro, $identificador, $usuario, $nombre, $apellido, $codigoMagap, $idSitio){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.miembros_asociacion
												(codigo_miembro_asociacion, identificador_miembro_asociacion, identificador_asociacion, nombre_miembro_asociacion, apellido_miembro_asociacion, codigo_magap, id_sitio)
											VALUES
												('$codigoMiembro', '$identificador', '$usuario', '$nombre', '$apellido', '$codigoMagap', $idSitio)
											RETURNING id_miembro_asociacion;");
									
				return $res;
	}
	
	public function buscarOperacionesRendimientoXidOperacion($conexion, $idOperacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_operadores.detalle_miembros_asociacion
											WHERE
												id_operacion = $idOperacion");
	
				return $res;
	}
	
	public function actualizarRendimientoAsociacionDetalle($conexion, $idOperacion, $rendimiento, $superficieMiembro){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.detalle_miembros_asociacion
											SET
												rendimiento = $rendimiento,
												superficie_miembro = $superficieMiembro
											WHERE
												id_operacion = $idOperacion;");
									
				return $res;
	}
	
	public function verificarOperacionOrganico($conexion, $idOperacion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_operadores.operaciones_organico oo
											WHERE
												oo.id_operacion = $idOperacion;");
		
		return $res;
	}
	
	public function eliminarOperacionOrganicoXidOperacion($conexion, $idOperacion){
	
		$res = $conexion->ejecutarConsulta("DELETE
											FROM
												g_operadores.operaciones_organico oo
											WHERE
												oo.id_operacion = $idOperacion;");
			
		return $res;
	}
	
	public function obtenerSitioMiembroAsociacionXidSitio($conexion, $idSitio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												id_miembro_asociacion, identificador_asociacion, codigo_miembro_asociacion, identificador_miembro_asociacion, nombre_miembro_asociacion || ' ' || apellido_miembro_asociacion as nombre_miembro, id_sitio, codigo_magap
											FROM
												g_operadores.miembros_asociacion
											WHERE
												id_sitio = $idSitio");
	
		return $res;
	}
	
	public function obtenerMiembroXIdentificadorXIdSitio($conexion, $identificadorMiembroAsociacion, $idSitio){//TODO:ORGANICOS

		$res = $conexion->ejecutarConsulta("SELECT
												id_miembro_asociacion, codigo_miembro_asociacion, identificador_miembro_asociacion, nombre_miembro_asociacion || ' ' || apellido_miembro_asociacion as nombre_miembro, id_sitio
											FROM
												g_operadores.miembros_asociacion
											WHERE
												identificador_miembro_asociacion = '$identificadorMiembroAsociacion'
												and id_sitio = $idSitio");
	
		return $res;
	}
	
	public function actualizarDatosMiembroAsociacionXIdentificadorMiembro ($conexion, $identificadorMiembroAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion){//TODO:ORGANICOS
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.miembros_asociacion
											SET
												nombre_miembro_asociacion = '$nombreMiembroAsociacion',
												apellido_miembro_asociacion = '$apellidoMiembroAsociacion'
											WHERE
												identificador_miembro_asociacion = '$identificadorMiembroAsociacion';");
		
		return $res;		
	}
	
	public function actualizarRegistroMiembroAsociacionXIdSitio ($conexion, $codigoMiembroAsociacion, $identificadorMiembroAsociacion, $identificadorAsociacion, $nombreMiembroAsociacion, $apellidoMiembroAsociacion, $codigoMagap, $idSitio){//TODO:ORGANICOS
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.miembros_asociacion
											SET
												codigo_miembro_asociacion = '$codigoMiembroAsociacion',
												identificador_miembro_asociacion = '$identificadorMiembroAsociacion',
												identificador_asociacion = '$identificadorAsociacion',
												nombre_miembro_asociacion = '$nombreMiembroAsociacion',
												apellido_miembro_asociacion = '$apellidoMiembroAsociacion',
												codigo_magap = '$codigoMagap'
											WHERE
												id_sitio = $idSitio;");
										
		return $res;
	}
	
	public function obtenerAgenciasMiembroAsociacion($conexion, $identificadorMiembroAsociacion, $identificadorAsociacion){//TODO:ORGANICOS

		$res = $conexion->ejecutarConsulta("SELECT
												distinct ma.identificador_asociacion, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
											FROM
												g_operadores.miembros_asociacion ma
											INNER JOIN g_operadores.operadores o ON ma.identificador_asociacion = o.identificador
											WHERE
												ma.identificador_miembro_asociacion = '$identificadorMiembroAsociacion' and
                                                identificador_asociacion not in ('$identificadorAsociacion')");
	
		return $res;
	}
	
	public function guardarTipoInspectorFaenador($conexion, $identificadorOperador, $estado, $tipoOperacion, $observacion, $idOperadorTipoOperacion, $identificadorTecnico){
	    
	    $consulta = "INSERT INTO g_centros_faenamiento.tipo_inspector(identificador_operador, resultado, tipo_inspector, 
                                    observacion, id_operador_tipo_operacion, identificador_registro)
                        SELECT '$identificadorOperador', '$estado', '$tipoOperacion', '$observacion', $idOperadorTipoOperacion, '$identificadorTecnico'
                        WHERE NOT EXISTS (SELECT identificador_operador FROM g_centros_faenamiento.tipo_inspector WHERE identificador_operador= '$identificadorOperador' and tipo_inspector = '$tipoOperacion')";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
        
	    return $res;
	}
	
	public function obtenerOperacionesXOperadorXIdTipoOperacion($conexion, $identificadorOperador, $idTipoOperacion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
													DISTINCT op.id_tipo_operacion
												FROM
													g_operadores.operaciones op
												WHERE
													op.identificador_operador='$identificadorOperador' and
                                                    op.id_tipo_operacion = $idTipoOperacion and 
													op.estado = 'registrado';");
	    return $res;
	}
	
	public function obtenerDatosAreaXIdOperacion ($conexion, $idOperacion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
										        	a.id_area, a.nombre_area
										        FROM
											        g_operadores.operaciones op,
											        g_operadores.productos_areas_operacion pao,
                                                    g_operadores.areas a
										        WHERE
											        op.id_operacion=pao.id_operacion
                                                    and pao.id_area = a.id_area
											        and op.id_operacion='$idOperacion'");
	    return $res;
	}
	
	public function listarTipoProductosOperacionesOrganicoXIdentificadorProveedor($conexion, $identificadorProveeedor){
	    
	    $consulta="SELECT
                        distinct tp.id_tipo_producto,
                        tp.nombre
                    FROM
                    g_operadores.operaciones op
                    INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                    INNER JOIN g_catalogos.subtipo_productos stp ON p.id_subtipo_producto = stp.id_subtipo_producto
                    INNER JOIN g_catalogos.tipo_productos tp ON stp.id_tipo_producto = tp.id_tipo_producto
                    INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                    op.identificador_operador = '$identificadorProveeedor'
                    and op.estado = 'registrado'
                    and top.id_area || '-' || top.codigo in ('AI-PRO', 'AI-COM', 'AI-PRC', 'AI-REC');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function listarProductosOperacionesOrganicoXIdentificadorProveedorXIdSubtipoProducto($conexion, $identificadorProveedor, $idSubtipoProducto){
	    
	    $consulta="SELECT
                        distinct op.id_producto,
                        tt.id_tipo_transicion,
                        op.nombre_producto,
                        tt.nombre_tipo_transicion
                	FROM
                    	g_operadores.operaciones op
                    	INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                    	INNER JOIN g_operadores.operaciones_organico oo ON op.id_operacion = oo.id_operacion
                    	INNER JOIN g_catalogos.tipo_transicion tt ON oo.id_tipo_transicion = tt.id_tipo_transicion
                	WHERE
                    	op.identificador_operador = '$identificadorProveedor'
                    	and op.estado = 'registrado'
                    	and p.id_subtipo_producto = $idSubtipoProducto;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function imprimirProductosProveedoresOrganicos($idProveedor, $nombreProveedor, $nombreProducto, $nombreTipoTransicion, $idOperadorTipoOperacion, $idTipoOperacion){
		 return '<tr id="R' . $idProveedor . '">' .	 
		 '<td>' . $nombreProveedor . '</td>' .
		 '<td>' . $nombreProducto . '</td>' .
		 '<td>' . $nombreTipoTransicion . '</td>' .
		 '<td align="center">' .
		 '<form class="borrar" data-rutaAplicacion="registroOperador" data-opcion="eliminarProveedorOrganico">' .
		 '<input type="hidden" name="idProveedor" value="' . $idProveedor . '" >' .
		 '<input type="hidden" name="idOperadorTipoOperacion" value="' . $idOperadorTipoOperacion . '" >' .
		 '<input type="hidden" name="idTipoOperacion" value="' . $idTipoOperacion . '" >' .
		 '<button type="submit" class="icono"></button>' .
		 '</form>' .
		 '</td>' .
		 '</tr>';
	 }
	
    public function cambiarEstadoProveedor($conexion, $idProveedor, $estadoProveedor){
    
    $consulta="UPDATE 
                    g_operadores.proveedores
                SET 
                    estado_proveedor = '$estadoProveedor'
                WHERE 
                    id_proveedor = $idProveedor;";
    
    $res = $conexion->ejecutarConsulta($consulta);
    
    return $res;
    }
	
	 public function buscarProductoProveedorOrganico ($conexion, $identificadorOperador, $identificadorProveedor, $idProducto, $idTipoTransicion, $idOperadorTipoOperacion, $nombreProveedor){
	    
        $busqueda = "";
        if($identificadorProveedor != "" || $identificadorProveedor != null){
            $busqueda = "codigo_proveedor = '$identificadorProveedor' and";
        }else{
            $busqueda = "nombre_exportador = '$nombreProveedor' and";
        }
        
	    $consulta="SELECT
	        			*
	        		FROM
	        			g_operadores.proveedores pr
	        		WHERE
						" . $busqueda . "
                        identificador_operador = '$identificadorOperador' and
						id_producto = '$idProducto' and
                        id_tipo_transicion = $idTipoTransicion and
                        id_operador_tipo_operacion = $idOperadorTipoOperacion";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function buscarProveedoresXIdProveedorXIdOperacionXIdProducto ($conexion,$idProveedor){
	 
	    $consulta="SELECT 
                    	*
                    FROM 
                    	g_operadores.proveedores prv
                    INNER JOIN 
                    	g_operadores.operaciones op ON prv.id_operacion = op.id_operacion and prv.id_producto = op.id_producto
                    WHERE
                    	prv.id_proveedor = $idProveedor";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerProveedoresXIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion){
	    
	    $consulta ="SELECT 
                    	*
                    FROM 
                    	g_operadores.proveedores 
                    WHERE
                    	id_operador_tipo_operacion = $idOperadorTipoOperacion
                        and estado_proveedor = 'activo';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerOperacionXIdentificadorTipoOperacionXIdArea ($conexion, $identificadorOperador, $idTipoOperacion, $idProducto, $idArea){
	    
	    $consulta="SELECT 
                        op.id_operacion, 
                        op.id_tipo_operacion, 
                        op.identificador_operador, 
                        op.estado, 
                        op.id_operador_tipo_operacion, 
                        op.id_historial_operacion
                    FROM 
                        g_operadores.operaciones op
                    INNER JOIN 
                        g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                    WHERE
                        op.identificador_operador = '$identificadorOperador'
                        and op.id_tipo_operacion = $idTipoOperacion
                        and op.id_producto = $idProducto
                        and pao.id_area = $idArea;";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function listarProveedoresOperadorXIdOperadorTipoOperacion ($conexion, $identificador, $idOperadorTipoOperacion){
	    
	    $consulta="SELECT
						*
					FROM
						g_operadores.proveedores
					WHERE
						identificador_operador = '$identificador'
                        and id_operador_tipo_operacion in ($idOperadorTipoOperacion)
                        and estado_proveedor = 'activo';";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerProveedoresPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $codigoOperacion, $estado){
	    
	    $condicion = "";
	    
	    switch ($codigoOperacion){
	        case "COM":
	            $condicion = "op.id_operacion = prv.id_operacion";
	        break;
	        
	        case "PRC":
	            $condicion = "op.id_operador_tipo_operacion = prv.id_operador_tipo_operacion";
	        break;
	    }
	    
	    	
		$consulta = "SELECT 
                    DISTINCT prv.id_proveedor,
					CASE WHEN prv.codigo_proveedor != '' THEN (SELECT o.razon_social FROM g_operadores.operadores o WHERE prv.codigo_proveedor = o.identificador) ELSE prv.nombre_exportador END as nombre_proveedor,
					CASE WHEN cp.codigo_poa != '' THEN cp.codigo_poa ELSE (SELECT 'N/A'||' - '||l.nombre FROM g_catalogos.localizacion l WHERE prv.id_pais = l.id_localizacion) END as codigo_poa,
					prv.id_producto,
					stp.nombre,
					prv.nombre_producto,
					prv.id_operacion,
					prv.id_tipo_transicion,
					tt.nombre_tipo_transicion
				FROM
					g_operadores.operaciones op
					INNER JOIN g_operadores.proveedores prv ON " . $condicion . "
					INNER JOIN g_catalogos.tipo_transicion tt ON prv.id_tipo_transicion = tt.id_tipo_transicion
					INNER JOIN g_catalogos.productos p ON prv.id_producto = p.id_producto
					INNER JOIN g_catalogos.subtipo_productos stp ON p.id_subtipo_producto = stp.id_subtipo_producto
					LEFT JOIN g_operadores.codigos_poa cp ON prv.codigo_proveedor = cp.identificador_operador
				WHERE
					op.id_operador_tipo_operacion  = $idOperadorTipoOperacion
					and op.id_historial_operacion = $idHistorialOperacion
					and prv.estado_proveedor = 'activo'
                    and op.estado = '" . $estado . "' 
				ORDER BY 
					nombre_proveedor;";
					
		$res = $conexion->ejecutarConsulta($consulta);
	
		return $res;
	}
		
	public function guardarMercadoDestinoXIdOperacion($conexion, $idOperacion, $idMercadoDestino){
	    
	    $consulta = "INSERT INTO 
                        g_operadores.mercado_destino(id_operacion, id_localizacion)
                     VALUES
                        ($idOperacion, $idMercadoDestino)";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function eliminarMercadoDestinoXidOperacion($conexion, $idOperacion){
	    
	    $consulta = "DELETE
					FROM
						g_operadores.mercado_destino
					WHERE
						id_operacion = $idOperacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function buscarRutasPoaOperador($conexion, $idCodigoPoa, $idTipoOperacion) {
	    
	    $consulta = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_operadores.subcodigos_poa
												WHERE
													id_codigo_poa = $idCodigoPoa
                                                    and id_tipo_operacion = $idTipoOperacion;");
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function listarOperacionesOperadorXAreaTematicaOperacionXCodigoTipoOperacion ($conexion, $identificador, $estado, $incremento, $datoIncremento, $idAreaOperacion, $codigoTipoOperacion, $tipoEstado = null){
	    
	    $busqueda = '';
	    if($tipoEstado != null){
	        $busqueda = "and t.codigo NOT IN ('IMP','EXP')";
	    }
  	    
	    $consulta = "SELECT
												distinct min(s.id_operacion) as id_operacion,
												s.identificador_operador,
												s.estado,
												s.id_tipo_operacion,
												t.nombre as nombre_tipo_operacion,
												st.provincia,
												st.id_sitio,
												st.nombre_lugar
											FROM
												g_operadores.operaciones s,
												g_catalogos.tipos_operacion t,
												g_operadores.operadores o,
												g_operadores.productos_areas_operacion sa,
												g_operadores.areas a,
												g_operadores.sitios st
											WHERE
												s.identificador_operador = '$identificador' and
												s.id_tipo_operacion = t.id_tipo_operacion and
												s.identificador_operador = o.identificador and
												s.id_operacion = sa.id_operacion and
												sa.id_area = a.id_area and
												a.id_sitio = st.id_sitio and
                                                s.id_tipo_operacion = t.id_tipo_operacion and
												s.estado $estado
		                                        ".$busqueda." and
                                                t.id_area = '$idAreaOperacion' and
                                                t.codigo $codigoTipoOperacion
											GROUP BY s.identificador_operador, s.estado, s.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area
											ORDER BY id_operacion
												offset $datoIncremento rows
												fetch next $incremento rows only";
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;	
	}
	
	public function obtenerProductosAreaSitioPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado){

	    $consulta = "SELECT
													*
												FROM
													g_operadores.operaciones o,
                                                    g_operadores.productos_areas_operacion pao,
                                                    g_operadores.areas a,
                                                    g_operadores.sitios s
												WHERE
                                                    o.id_operacion = pao.id_operacion
                                                    and a.id_area = pao.id_area         
                                                    and a.id_sitio = s.id_sitio
													and id_operador_tipo_operacion in ($idOperadorTipoOperacion)
													and id_historial_operacion in ($idHistorialOperacion)
			                                        and o.estado = '$estado'
                                                    and o.id_operacion not in (SELECT id_operacion FROM g_operadores.operaciones_organico oo WHERE oo.id_operacion = o.id_operacion);";
																																
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerProveedoresImportacionXIdOperadorTipoOperacionXidHistorialOperacion($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion){
	    
	    $consulta = "SELECT
                        *
                     FROM
                        g_operadores.proveedores p
                     INNER JOIN g_operadores.operaciones op ON p.id_operacion = op.id_operacion and p.tipo = 'importador'
                     WHERE
                        op.id_operador_tipo_operacion = $idOperadorTipoOperacion 
                        and op.id_historial_operacion = $idHistoricoOperacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function listarProductosOperacionesOrganicoXIdentificadorProveedorXIdTipoProducto($conexion, $identificador, $codigoTipoProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
	                                        distinct stp.id_subtipo_producto,
                                	        stp.nombre
                                	        FROM
                                	        g_operadores.operaciones op
                                	        INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                                	        INNER JOIN g_catalogos.subtipo_productos stp ON p.id_subtipo_producto = stp.id_subtipo_producto
                                	        INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                                	        WHERE
                                	        op.identificador_operador = '$identificador'
                                	        and op.estado = 'registrado'
                                	        and stp.id_tipo_producto = $codigoTipoProducto
                                	        and top.id_area || '-' || top.codigo in ('AI-PRO', 'AI-COM', 'AI-PRC', 'AI-REC')");
	    
	    return $res;
	}
	
	////PORCINOS
	
	public function registrarLogRegistroOperadorMasivo($conexion, $identificadorUsuario, $identificadorOperador, $idSitio, $idArea, $idTipoOperacion, $idProducto){
	    
	    $consulta = "SELECT g_operadores.registarlogregistrooperadormasivo('$identificadorUsuario', '$identificadorOperador', $idSitio , '$idArea', $idTipoOperacion ,'$idProducto');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function eliminarDatosLogRegistroOperador($conexion){
	    
	    $consulta = "DELETE FROM 
                        g_operadores.log_registro_operador
                     WHERE 
                        to_char(fecha_registro,'YYYY-MM-DD')::date + interval '6 month' <= current_date" ;
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function listarSitiosXIdentificadorXProvincia($conexion, $identificadorOperador, $idProvincia){
	    
	     if ($idProvincia != "0"){
	            $busqueda = " and UPPER(o.provincia) = UPPER('$idProvincia')";
	    }
	    
	    $consulta = "SELECT
							s.provincia, s.nombre_lugar, s.identificador_operador || '.' || s.codigo_provincia || '.' || s.codigo as codigo_sitio, s.identificador_operador, s.id_sitio,
                            case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end razon_social,
                            nombre_representante ||' '|| apellido_representante nombre_representante
					FROM
							g_operadores.sitios s
                    INNER JOIN g_operadores.operadores o ON s.identificador_operador = o.identificador
					WHERE
							s.identificador_operador = '$identificadorOperador'
                            ".$busqueda."
							and s.estado not in ('eliminado','inactivo');";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarCoordenadasSitio($conexion, $idSitio, $latitudSitio, $longitudSitio, $supervisarUbicacion, $observacionSitio){
	    
	    $consulta = "UPDATE 
                        g_operadores.sitios 
                     SET
						latitud = '$latitudSitio',
                        longitud = '$longitudSitio',
                        supervisar_ubicacion = '$supervisarUbicacion',
                        observacion = '$observacionSitio'
                     WHERE
						id_sitio = $idSitio;";
	        
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarVigenciaXOperacion($conexion, $idOperacion, $idVigenciaDocumento){
	    
	    $consulta = "UPDATE
                        g_operadores.operaciones
                     SET
						id_vigencia_documento = $idVigenciaDocumento
                     WHERE
						id_operacion = $idOperacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarCoordenadasTipoSitio ($conexion, $tipoSitio, $latitud, $longitud, $zona, $idSitio){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.sitios
											SET
												tipo_sitio = '$tipoSitio',
												latitud = '$latitud',
												longitud = '$longitud',
												zona = '$zona'
											WHERE
												id_sitio = $idSitio;");
		return $res;
	}
	
	public function guardarOperacionFechaEvento ($conexion, $idOperadorTipoOperacion, $fechaEvento, $cantidadDias){
		
		$consulta = "INSERT INTO g_operadores.operaciones_eventos(id_operador_tipo_operacion, fecha_evento, cantidad_dia)
											VALUES ($idOperadorTipoOperacion, '$fechaEvento', $cantidadDias);";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function imprimirLineaRepresentanteTecnico($idDetalleRepresentanteTecnico, $identificador, $nombreRepresentante, $titulo, $numeroRegistro, $tipoProducto){
		return '<tr id="R' .$idDetalleRepresentanteTecnico. '">' .
			'<td>'.$identificador.'</td>'.
			'<td>'.$nombreRepresentante.'</td>'.
			'<td>'.$titulo.'</td>'.
			'<td>'.$numeroRegistro.'</td>'.
			'<td>'.$tipoProducto.'</td>'.
			'<td>' .
			'<form class="borrar" data-rutaAplicacion="registroOperador" data-opcion="inactivarRepresentanteTecnico">' .
			'<input type="hidden" name="idDetalleRepresentanteTecnico" value="' . $idDetalleRepresentanteTecnico . '" >' .
			'<button type="submit" class="icono"></button>' .
			'</form>' .
			'</td>' .
			'</tr>';
	}
	
	public function obtenerRepresentanteTecnicoPorIdOperacionIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idOperacion, $idAreaOperacion) {
		
		$consulta = "SELECT
							drt.*, tp.nombre
						FROM
							g_operadores.representantes_tecnicos rt
							INNER JOIN g_operadores.detalle_representantes_tecnicos drt ON rt.id_representante_tecnico = drt.id_representante_tecnico
							LEFT JOIN g_catalogos.tipo_productos tp ON drt.id_tipo_producto = tp.id_tipo_producto
						WHERE
							id_operador_tipo_operacion='$idOperadorTipoOperacion' and
							id_historial_operacion = '$idHistorialOperacion' and
							rt.id_area = '$idAreaOperacion' and
							id_operacion='$idOperacion'
							and drt.estado IN ('registrado', 'creado');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actualizarEstadoRepresentanteTecnicoPorIdOperacionIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $idOperacion, $idAreaOperacion, $estado = 'registrado') {
		
		$consulta = "UPDATE
							g_operadores.detalle_representantes_tecnicos drt
					SET
						estado = '$estado',
						fecha_modificacion_representante = 'now()'
					FROM
							g_operadores.representantes_tecnicos rt
					WHERE
						rt.id_representante_tecnico = drt.id_representante_tecnico and 
						id_operador_tipo_operacion='$idOperadorTipoOperacion' and
						id_historial_operacion = '$idHistorialOperacion' and
						rt.id_area = '$idAreaOperacion' and
						id_operacion='$idOperacion' and
						drt.estado not in ('inactivo');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function verificarRepresentanteTecnico($conexion, $idRepresentanteTecnico, $idTipoProducto, $identificadorRepresentante, $nombreRepresentante, $tituloAcademico, $numeroRegistro, $idAreaRepresentante) {
		
		if($idTipoProducto == 'null'){
			$condicion = ' is ';
		}else{
			$condicion = ' = ';
		}
		
		$consulta = "SELECT
							*
						FROM
							g_operadores.detalle_representantes_tecnicos
						WHERE
							id_representante_tecnico = '$idRepresentanteTecnico' and
							id_tipo_producto $condicion $idTipoProducto and 
							identificacion_representante  = '$identificadorRepresentante' and
							nombre_representante  = '$nombreRepresentante' and
							titulo_academico = '$tituloAcademico' and 
							numero_registro_titulo = '$numeroRegistro' and
  							id_area_representante = '$idAreaRepresentante' and
							estado IN ('registrado', 'creado');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerEstadoOperacionHistorico($conexion, $idOperadorTipoOperacion, $estadoAnterior, $estadoActual = null) {
		
		$busqueda = '';
		if($estadoActual != null){
			$busqueda = "and estado_actual = '$estadoActual'";
		}
		
		$consulta = "SELECT 
						* 
					FROM 
						g_operadores.auditoia_operaciones 
					WHERE 
						id_operador_tipo_operacion = $idOperadorTipoOperacion and 
						estado_anterior = '$estadoAnterior'
						".$busqueda.";";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $secuencial, $identificadorOperador, $tipo){

		$consulta = "SELECT
							*
						FROM
							g_operadores.documentos_operador
						WHERE
							secuencial = '$secuencial'
							and identificador_operador = '$identificadorOperador'
							and tipo = '$tipo'
							and estado = 'activo';";

		$res = $conexion->ejecutarConsulta($consulta);

		return $res;

	}
	
	public function actualizarEstadoDocumentoOperador($conexion, $identificadorOperador, $idOperadorTipoOperacion, $estado){

	    $consulta = "UPDATE
						g_operadores.documentos_operador
					SET
						estado = '$estado'
					WHERE
                        estado = 'activo'
					    and id_operador_tipo_operacion = $idOperadorTipoOperacion
                        and identificador_operador = '$identificadorOperador';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function obtenerOperadoresCGRIAtemp($conexion, $tipo){
		
		$busqueda ='';
		$columnas = '';
		$grupo = '';
		
		if($tipo == 'Almacenista'){
			$busqueda=" IN ";
			$columnas = " distinct op.identificador_operador, s.id_sitio, min(op.id_operacion) as id_operacion, min(op.id_operador_tipo_operacion) as id_operador_tipo_operacion ";
			$grupo = " op.identificador_operador, s.id_sitio ";
		}else{
			$busqueda=" NOT IN ";
			$columnas = " distinct op.identificador_operador, min(op.id_operacion) as id_operacion, min(op.id_operador_tipo_operacion) as id_operador_tipo_operacion";
			$grupo = " op.identificador_operador ";
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												".$columnas."
											FROM
												g_operadores.sitios s
												INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
												INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
												INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
												INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
											WHERE
												top.id_area IN ('IAP','IAV','IAF','CGRIA')
												and top.codigo".$busqueda." ('ALM')
												and op.estado IN ('registrado','cargarProducto')
											GROUP BY 
												".$grupo."
											ORDER BY 
												identificador_operador;");
		
		return $res;
		
	}
	
	public function obtenerMinimoFechaPorIdentificador($conexion, $tipo, $idSitio, $identificadorOperador){
		
		$busqueda ='';
		$sitio = '';
		
		if($tipo == 'Almacenista'){
			$busqueda=" IN ";
			$sitio = "and s.id_sitio = ".$idSitio;
		}else{
			$busqueda=" NOT IN ";
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												min(op.fecha_aprobacion) as fecha_aprobacion
											FROM
												g_operadores.sitios s
												INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
												INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
												INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
												INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
											WHERE
												top.id_area IN ('IAP','IAV','IAF','CGRIA')
												and top.codigo".$busqueda." ('ALM')
												and op.estado = 'registrado'
												and op.identificador_operador = '$identificadorOperador'
												".$sitio.";");
		
		return $res;
		
	}
	
	public function obtenerDatosOperadorXIdAreaXCodigoOperacion($conexion, $identificadorOperador, $idArea, $codigoOperacion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT 
                                                    distinct (o.identificador), case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
                                                    FROM g_operadores.operaciones op 
                                                    INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion 
                                                    INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
												WHERE
													top.id_area = '$idArea'
													and top.codigo $codigoOperacion
													and op.identificador_operador='$identificadorOperador';");
	    return $res;
	}
	
	public function obtenerOperacionesPorIdentificadorAreaTipoOperacion($conexion, $identificadorOperador, $idTipoOperacion, $idArea, $condicion) {
		
		
		switch ($idArea){
			case 'IAV':
			case 'IAP':
			case 'IAF':
			case 'CGRIA':
				$area = "'IAV','IAP','IAF','CGRIA'";
			break;
			default:
				$area = "'.$idArea.'";
				
		}
		
		if($condicion == 'noMultiple'){
			$consulta = "SELECT 
							distinct op.id_tipo_operacion
						FROM 
							g_operadores.operaciones op 
							INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
						WHERE
							top.id_tipo_operacion NOT IN ($idTipoOperacion)
							and top.id_area IN ($area)
							and op.identificador_operador = '$identificadorOperador'
                            and op.estado not in ('noHabilitado');";
		}else{
			$consulta = "SELECT
						distinct op.id_tipo_operacion
					FROM
						g_operadores.operaciones op
						INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
					WHERE
						top.id_tipo_operacion IN (SELECT top1.id_tipo_operacion FROM g_catalogos.tipos_operacion top1 WHERE operacion_multiple = false and top1.id_area IN ($area))
						and op.identificador_operador = '$identificadorOperador'
                        and op.estado not in ('noHabilitado');";
		}
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerProductoPorIdOperadorTipoOperacionIdHistorialOperacion($conexion, $identificadorOperador, $idOperadorTipoOperacion, $idHistorialOperacion, $estado = 'noHabilitado') {
		
		$consulta = "SELECT
							op.id_operacion, tp.nombre as nombre_tipo_producto, stp.nombre as nombre_subtipo_producto, p.nombre_comun as nombre_producto, p.id_producto, coalesce(pr.id_operacion, 0) as id_operacion_proveedor
						FROM
							g_operadores.operaciones op
							INNER JOIN g_catalogos.productos p ON p.id_producto = op.id_producto
							INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = p.id_subtipo_producto
							INNER JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = stp.id_tipo_producto
							LEFT JOIN g_operadores.proveedores pr ON pr.id_operacion = op.id_operacion
						WHERE
							op.id_operador_tipo_operacion='$idOperadorTipoOperacion' and
							op.id_historial_operacion = '$idHistorialOperacion' and
							op.identificador_operador = '$identificadorOperador' and
							op.estado NOT IN ('$estado');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
		
	}
	
	public function obtenerAreaRegistroCuarentena($conexion, $codigoRegistroCuarentena, $identificadorOperador){

		$busqueda = "";
	    
	    if($area == 'SV'){
	        $busqueda = "and s.identificador_operador = '$identificadorOperador'";
		}
		
		$consulta = "SELECT
							distinct(a.id_area), 
							top.nombre, 
							top.id_tipo_operacion, 
							s.codigo_provincia, 
							s.provincia, 
							s.id_sitio, 
							s.nombre_lugar, 
							a.id_area, 
							a.nombre_area 
						FROM
							g_operadores.sitios s
						INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
						INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
						INNER JOIN g_operadores.operaciones o ON o.id_operacion  = pao.id_operacion
						INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = o.id_tipo_operacion
						WHERE
							s.identificador_operador||'.'||s.codigo_provincia || s.codigo ||a.codigo||a.secuencial = '$codigoRegistroCuarentena'
							and top.id_area in ('SA', 'SV') 
							and top.codigo in ('CUA','DMR')
							and o.estado in ('registrado', 'registradoObservacion', 'porCaducar')
							".$busqueda.";";

		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function verificarRegistroMercanciasPecuaria($conexion, $idProducto, $idPais, $uso, $identificadorOperador, $idOperacion, $idOperadorTipoOperacion, $estado = 'activo') {

		$consulta = "SELECT
							*
						FROM
							g_operadores.centros_pecuario
						WHERE
							id_producto = $idProducto
							and id_pais = $idPais
							and uso = '$uso'
							and id_operacion = $idOperacion
							and id_operador_tipo_operacion = $idOperadorTipoOperacion
							and identificador_operador = '$identificadorOperador'
							and estado = '$estado';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function guardarRegistroMercanciasPecuaria($conexion, $idProducto, $idPais, $uso, $identificadorOperador, $idOperacion, $idOperadorTipoOperacion) {
		
		$consulta = "INSERT INTO g_operadores.centros_pecuario(id_producto, id_pais, uso, id_operacion, id_operador_tipo_operacion, identificador_operador) 
					VALUES ($idProducto, $idPais, '$uso', $idOperacion, $idOperadorTipoOperacion, '$identificadorOperador') RETURNING id_centro_pecuario;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function imprimirLineaMercanciasPecuaria($idCentroPecuario, $nombreProducto, $nombrePais, $nombreUso){
		return '<tr id="R' .$idCentroPecuario. '">' .
			'<td>'.$nombreProducto.'</td>'.
			'<td>'.$nombrePais.'</td>'.
			'<td>'.$nombreUso.'</td>'.
			'<td>' .
			'<form class="borrar" data-rutaAplicacion="registroOperador" data-opcion="inactivarRegistroMercanciaPecuaria">' .
			'<input type="hidden" name="idCentroPecuario" value="' . $idCentroPecuario . '" >' .
			'<button type="submit" class="icono"></button>' .
			'</form>' .
			'</td>' .
			'</tr>';
	}
	
	public function obtenerRegistroMercanciasPecuaria($conexion, $identificadorOperador, $idOperadorTipoOperacion, $estado = 'activo') {
		
		$consulta = "SELECT
							cp.id_centro_pecuario, cp.uso, p.nombre_comun as nombre_producto, l.nombre as nombre_pais
						FROM
							g_operadores.centros_pecuario cp
							INNER JOIN g_catalogos.productos p ON cp.id_producto = p.id_producto 
							INNER JOIN g_catalogos.localizacion l ON cp.id_pais = l.id_localizacion
						WHERE
							id_operador_tipo_operacion = $idOperadorTipoOperacion
							and identificador_operador = '$identificadorOperador'
							and cp.estado = '$estado';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function inactivarRegistroMercanciasPecuaria($conexion, $idCentroPecuario, $estado = 'inactivo') {
		
		$consulta = "UPDATE
							g_operadores.centros_pecuario
					SET
						estado = '$estado',
						fecha_modificacion = 'now()'
					WHERE
							id_centro_pecuario = $idCentroPecuario;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerAreaTipoOperacionXIdentificadorOperador($conexion, $identificadorOperador){	    

		$consulta = "SELECT DISTINCT
								STRING_AGG(DISTINCT t.id_area || t.codigo ,', ') as codigo_tipo_operacion
							FROM
								g_usuario.empleados e
								,g_usuario.empresas em
								,g_operadores.operaciones op
								,g_catalogos.tipos_operacion t
								,g_operadores.areas a
								,g_operadores.sitios s
								,g_operadores.productos_areas_operacion pao
							WHERE
								op.id_tipo_operacion = t.id_tipo_operacion
								and pao.id_area = a.id_area
								and a.id_sitio = s.id_sitio
								and pao.id_operacion = op.id_operacion
								and op.estado in ('registrado', 'porCaducar')
								and ((t.id_area || t.codigo = 'SAFER') OR (t.id_area || t.codigo = 'AIFAE'))
								and em.identificador = op.identificador_operador
								and em.id_empresa = e.id_empresa
								and e.estado = 'activo'
								and em.estado = 'activo'
								and e.identificador='$identificadorOperador'";
						
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerCondicionTipoOperacion($conexion, $idTipoOperacion, $estadoCondicion){
	    
	    $consulta = "SELECT
                    	*
                    FROM
                    	g_catalogos.tipos_operacion_condicion
                    WHERE
                    	id_tipo_operacion = $idTipoOperacion
                        and condicion_aprobacion = '$estadoCondicion';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarProcesoActualizacionOperacion($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado = ''){
	    
	    $estado = $estado!="" ? "'" . $estado . "'" : "null";
	    
	    $consulta = "UPDATE 
                            g_operadores.operaciones 
                    SET 
                            proceso_modificacion = $estado 
                    WHERE
                            id_operador_tipo_operacion = $idOperadorTipoOperacion and
							id_historial_operacion = $idHistorialOperacion
							and estado not in ('noHabilitado');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	/////////////////////////////////PROCESO BANANEROS/////////////////////////////////////////////////////////////
	
	public function obtenerOperadoresBanano($conexion){
	    
	    $consulta = "SELECT * FROM public.operadores_banano WHERE estado = 'Por atender';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actulizarEstadoOperadorBanano($conexion, $identificador, $estado){
	    
	    $consulta = "UPDATE public.operadores_banano SET estado = '$estado' WHERE identificador = '$identificador';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerLocalizacionPorNombre ($conexion, $nombre, $categoria, $tipoConsulta, $idLocalizacionPadre=null){
	    
	    $busqueda = '';
	    
	    switch ($tipoConsulta){
	        case 'canton':
	           $busqueda = " and id_localizacion_padre = $idLocalizacionPadre";
	        break;
	        case 'parroquia':
	            $busqueda = " and id_localizacion_padre = $idLocalizacionPadre";
	        break;
	    }
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.localizacion
											WHERE
												unaccent(nombre) ilike unaccent('%$nombre%')
                                                and categoria = '$categoria'
                                                ".$busqueda.";");
	    return $res;
	}
	
	public function obtenerOperacionesBanano($conexion, $estado = 'Por atender'){
	    
	    $consulta = "SELECT * FROM public.operaciones_banano WHERE estado = '$estado';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerProveedoresBanano($conexion, $estado = 'Por atender'){
		
		$consulta = "SELECT * FROM public.proveedores_banano WHERE estado_contrato = '$estado';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actulizarEstadoOperacionesBanano($conexion, $id, $estado){
	    
	    $consulta = "UPDATE public.operaciones_banano SET estado = '$estado' WHERE id = '$id';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actulizarEstadoProveedorBanano($conexion, $id, $estado){
		
		$consulta = "UPDATE public.proveedores_banano SET estado_contrato = '$estado' WHERE id_proveedor = '$id';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function bucarSitioPorNombreIdentificador($conexion, $identificador, $nombre, $superficie){
	    
	    $consulta = "SELECT * FROM g_operadores.sitios WHERE nombre_lugar = '$nombre' 
                    and identificador_operador = '$identificador' and superficie_total = '$superficie';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function bucarAreaPorNombreSitioTipoArea($conexion, $idSitio, $nombre, $tipoArea, $superficie, $codigoTransaccional){
	    
	    $consulta = "SELECT * FROM g_operadores.areas WHERE nombre_area = '$nombre' 
                     and id_sitio = '$idSitio' and tipo_area = '$tipoArea' and superficie_utilizada = '$superficie' and codigo_transaccional = '$codigoTransaccional';";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function bucarCodigoCatalogoAreaPorNombre($conexion, $nombre){
	    
	    $consulta = "SELECT distinct codigo, nombre FROM g_catalogos.areas_operacion WHERE upper(nombre) = upper('$nombre');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function bucarCodigoCatalogoTipoOperacionPorCodigo($conexion, $idArea, $codigoTipoOperacion){
	    
	    $consulta = "SELECT id_tipo_operacion FROM g_catalogos.tipos_operacion WHERE id_area = '$idArea' and codigo = '$codigoTipoOperacion';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerCodigoProducto($conexion, $nombreTipo, $nombreSubtipo, $nombreProducto){
	    
	    $consulta = "select
						p.*
					from
						g_catalogos.subtipo_productos dp,
						g_catalogos.tipo_productos tp,
						g_catalogos.productos p
					where
						dp.id_tipo_producto = tp.id_tipo_producto and
						dp.id_subtipo_producto = p.id_subtipo_producto and
						unaccent(upper(p.nombre_comun)) = unaccent(upper('$nombreProducto')) and
                        dp.nombre = '$nombreSubtipo' and
                        tp.nombre = '$nombreTipo'
                        and codigo_producto = '0001';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function buscarProductoProveedorTipoOperacion ($conexion,$identificadorOperador, $identificadorProveedor, $idProducto, $idPais, $idTipoOperacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
								        			*
								        		FROM
								        			g_operadores.proveedores pr
								        		WHERE
        											codigo_proveedor = '$identificadorProveedor' and
        											identificador_operador = '$identificadorOperador' and
        											id_producto = '$idProducto' and
        											id_pais = '$idPais' and 
													operacion_operador = '$idTipoOperacion';");
		
		return $res;
	}
	
	public function actualizarEstadoProductoProveedorTipoOperacion ($conexion,$identificadorOperador, $identificadorProveedor, $idProducto, $idPais, $idTipoOperacion, $estado = 'inactivo'){
		
		$res = $conexion->ejecutarConsulta("UPDATE
								        			g_operadores.proveedores pr
								        	SET
								        			estado_proveedor = '$estado'
								        	WHERE
        											codigo_proveedor = '$identificadorProveedor' and
        											identificador_operador = '$identificadorOperador' and
        											id_producto = '$idProducto' and
        											id_pais = '$idPais' and
													operacion_operador = '$idTipoOperacion';");
		
		return $res;
	}
	
	public function verificarRegistroInformacionColmenar($conexion, $idOperacion, $idOperadorTipoOperacion) {
	    
	    $consulta = "SELECT
							*
						FROM
							g_operadores.datos_colmenares
						WHERE
                            id_operacion = $idOperacion
							and id_operador_tipo_operacion = $idOperadorTipoOperacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function guardarRegistroInformacionColmenar($conexion, $idOperacion, $idOperadorTipoOperacion, $duenioSitio, $numeroColmenares, $numeroPromedioColmenas) {
	    
	    $consulta = "INSERT INTO g_operadores.datos_colmenares(id_operacion, id_operador_tipo_operacion, duenio_sitio_colmenar, numero_colmenar, numero_promedio_colmenas)
					VALUES ($idOperacion, $idOperadorTipoOperacion, '$duenioSitio', $numeroColmenares, $numeroPromedioColmenas);";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
		
	public function listarInformacionColmenar($conexion, $idOperacion, $idOperadorTipoOperacion) {
	    
	    $consulta = "SELECT
                        	DISTINCT dc.id_dato_colmenar
                        	, dc.id_operacion
                        	, dc.id_operador_tipo_operacion
                        	, dc.duenio_sitio_colmenar
                        	, dc.numero_colmenar
                        	, dc.numero_promedio_colmenas
                        	, dc.fecha_creacion
                        	, s.id_sitio
                            , s.nombre_lugar
                        	, s.latitud
                        	, s.longitud
                        	, s.zona
                        FROM
                        	g_operadores.datos_colmenares dc
                        INNER JOIN g_operadores.operaciones op ON dc.id_operacion = op.id_operacion
                        INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                        INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                    WHERE
                    	dc.id_operacion = $idOperacion
                    	and dc.id_operador_tipo_operacion = $idOperadorTipoOperacion";
	    
        $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarRegistroInformacionColmenarPorIdColmenar($conexion, $idDatoColmenar, $duenioSitio, $numeroColmenares, $numeroPromedioColmenas){
	    
	   $consulta = "UPDATE 
                        g_operadores.datos_colmenares
	                 SET 
                        duenio_sitio_colmenar = '$duenioSitio'
                        , numero_colmenar = $numeroColmenares
                        , numero_promedio_colmenas = $numeroPromedioColmenas
	                  WHERE 
                        id_dato_colmenar = $idDatoColmenar;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarCoordenadasSitioPorIdSitio($conexion, $idSitio, $latitud, $longitud, $zona){
	    
	    $consulta = "UPDATE
                        g_operadores.sitios
                    SET
                        latitud = '$latitud'
                        , longitud = '$longitud'
                        , zona = '$zona'
                    WHERE id_sitio = $idSitio;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function obtenerOperacionesAreaTematicoPorOperador($conexion, $identificadorOperador){
	    
	    $identificadorOperadorCorto = substr($identificadorOperador, 0, 10);

	    $consulta = "SELECT 
                        distinct 
                        s.identificador_operador
                        , case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
                        , s.id_sitio
                        , s.nombre_lugar
                        , a.id_area
                        , a.nombre_area
                        , op.id_operador_tipo_operacion
                        , op.id_tipo_operacion
                        , top.nombre as nombre_tipo_operacion
                        , ar.nombre
                     FROM 
                        g_operadores.operadores o
                        INNER JOIN g_operadores.sitios s ON o.identificador = s.identificador_operador
                        INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
                        INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                        INNER JOIN g_estructura.area ar ON top.id_area = ar.id_area
                     WHERE 
                        (o.identificador = '$identificadorOperadorCorto' or o.identificador = '$identificadorOperador')
                    ORDER BY op.id_tipo_operacion asc";
                        	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;

	}
	
	public function obtenerOperadoresXIdAreaXCodigoOperacion($conexion, $idArea, $codigoOperacion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
													distinct (op.identificador_operador)
												FROM
													g_operadores.operaciones op
													INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
												WHERE
													top.id_area = '$idArea'
													and top.codigo $codigoOperacion
                                                    and op.estado in ('registrado', 'porCaducar');");
	    return $res;
	}
	
	public function guardarCrearOperador($conexion, $arrayDatos){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO
													g_operadores.crear_operador(
                                                        identificador,
                                                        razon,
                                                        nombres,
                                                        apellidos,
                                                        codigo_verificacion)
   											 VALUES ('".$arrayDatos['identificador']."','".$arrayDatos['razon']."','".$arrayDatos['nombres']."','".$arrayDatos['apellidos']."', '".$arrayDatos['codigo']."') RETURNING id_crear_operador;");
	    return $res;
	}
	
	public function obtenerPreguntasCrearOperador($conexion, $tipo){
	    $consulta = "SELECT
                    	*
                    FROM
                    	g_operadores.preguntas_crear_operador
                    WHERE
                    	tipo = '".$tipo."' and estado='activo'";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function guardarDetalleCrearOperador($conexion, $arrayDatos){
	    
	   $res = $conexion->ejecutarConsulta("INSERT INTO
													g_operadores.detalle_crear_operador(
                                                        id_crear_operador,
                                                        id_preguntas_crear_operador,
                                                        respuesta_pregunta)
   											 VALUES (".$arrayDatos['idCrearOperador'].",".$arrayDatos['idPreguntasCrearOperador'].",'".$arrayDatos['respuestaPregunta']."');");
	    return $res;
	}
	
	public function obtenerDetalleCrearOperador($conexion, $arrayDatos){
	   $consulta = "SELECT
                    	*
                    FROM
                    	g_operadores.detalle_crear_operador
                    WHERE
                    	id_crear_operador = ".$arrayDatos['idCrearOperador']." and 
                        id_preguntas_crear_operador = ".$arrayDatos['idPreguntasCrearOperador']." and
                        respuesta_pregunta like upper('".$arrayDatos['respuestaPregunta']."') ";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerCrearOperador($conexion, $idCrearOperador){
	    $consulta = "SELECT
                    	codigo_verificacion
                    FROM
                    	g_operadores.crear_operador
                    WHERE
                    	id_crear_operador = ".$idCrearOperador."; ";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	/**
	 * Método para generar código con encriptación md5
	 */
	public function generarCodigoValidarCorreo($longitud){
		$cadena="[^A-Z0-9]";
		return substr(preg_replace ($cadena, "", md5(rand())) .
				preg_replace ($cadena, "", md5(rand())) .
				preg_replace ($cadena, "", md5(rand())),
				0, $longitud);
	}

	
	/**
	 * Método que inserta o actualiza el código de verificación para ingreso de correo de facturación
	 */
	public function actualizarCodigoTemporal($conexion,$identificador,$correo,$codigo){
	    		
	    $res = $conexion->ejecutarConsulta("SELECT 
												identificador, correo
											FROM
												g_operadores.codigo_temporal_acceso
											WHERE
												identificador='$identificador'
												and correo='$correo';");
		
		if(pg_fetch_row($res) == 0){

			$conexion->ejecutarConsulta("INSERT INTO 
												g_operadores.codigo_temporal_acceso(
												identificador, correo, codigo, fecha)
										VALUES
												('$identificador','$correo' , '$codigo', 'now()');");
		} else{
			$conexion->ejecutarConsulta("UPDATE 
												g_operadores.codigo_temporal_acceso
											SET 
												codigo='$codigo', fecha='now()'
										  WHERE 
										  		identificador='$identificador'
												and correo='$correo';");
		}

		return $res;
	}

	public function comprobarCodigo($conexion, $identificador, $correo, $codigo){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												identificador, correo
											FROM
												g_operadores.codigo_temporal_acceso
											WHERE
												identificador='$identificador'
												and correo='$correo'
												and codigo='$codigo';");

		return $res;
	}
	
	//// control de cambio material reproductivo

	public function guardarPlanificcionInspeccion($conexion, $tecnico, $fecha, $hora, $operacion, $idOperadorTipoOperacion){
		
		$consulta = "INSERT INTO g_revision_solicitudes.planificacion_inspeccion(
								nombre_tecnico, fecha_inspeccion, hora_inspeccion, id_operacion, id_operador_tipo_operacion)
						VALUES ('$tecnico', '$fecha', '$hora','$operacion','$idOperadorTipoOperacion');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;

	}


	public function obtenerPlanificacionInspeccion($conexion,$idOperadorTipoOperacion){

		$consulta="SELECT 
						id_planificacion, nombre_tecnico, fecha_inspeccion, hora_inspeccion, 
						id_operacion, id_operador_tipo_operacion
					FROM 
						g_revision_solicitudes.planificacion_inspeccion
					WHERE
						id_operador_tipo_operacion='$idOperadorTipoOperacion'
					ORDER BY 1 DESC LIMIT 1;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function actualizarTecnicoPlanificacionInspeccion($conexion,$tecnico,$idOperadorTipoOperacion){

		$consulta="UPDATE 
						g_revision_solicitudes.planificacion_inspeccion
					  SET
						nombre_tecnico='$tecnico'						
					WHERE
						id_operador_tipo_operacion='$idOperadorTipoOperacion'
						and id_planificacion = (select max(p.id_planificacion) from g_revision_solicitudes.planificacion_inspeccion p where p.id_operador_tipo_operacion='$idOperadorTipoOperacion');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function guardarResultadoRevision($conexion,$identificadorOperador,$idOperadorTipoOperacion,$resultado,$tiempo){

		$consulta="INSERT INTO g_revision_solicitudes.inspeccion_material_reproductivo(
								identificador_inspector, id_operador_tipo_operacion, 
								fecha_inspeccion, resultado, tiempo_aprobacion)
						VALUES ('$identificadorOperador', '$idOperadorTipoOperacion', 'now()','$resultado','$tiempo');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function obtenerResultadoRevision($conexion,$idOperadorTipoOperacion){

		$consulta="SELECT id_inspeccion_reproductivo, identificador_inspector, id_operador_tipo_operacion, 
							fecha_inspeccion, resultado, tiempo_aprobacion
					FROM 
						g_revision_solicitudes.inspeccion_material_reproductivo
					WHERE
						id_operador_tipo_operacion='$idOperadorTipoOperacion';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function quitarFechaFinalizacion($conexion, $idOperadorTipoOperacion){
		$consulta = "UPDATE 
						g_operadores.operaciones
					SET 	
						fecha_aprobacion=
							(select 
								(case when op.fecha_finalizacion is null then now()
								when op.fecha_finalizacion is not null and TO_DATE(op.fecha_finalizacion::Text,'YYYY-MM-DD') = current_date then 
								(SELECT fecha_inspeccion FROM g_revision_solicitudes.inspeccion_material_reproductivo where id_operador_tipo_operacion=$idOperadorTipoOperacion and resultado='subsanacionRepresentanteTecnico' order by 1 desc limit 1)	
								when op.fecha_finalizacion is not null and TO_DATE(op.fecha_finalizacion::Text,'YYYY-MM-DD') > current_date then 	 
								(SELECT fecha_inspeccion FROM g_revision_solicitudes.inspeccion_material_reproductivo where id_operador_tipo_operacion=$idOperadorTipoOperacion and resultado='subsanacionRepresentanteTecnico' order by 1 desc limit 1)
								else now() end)
								from g_operadores.operaciones op
							WHERE 
								op.id_operador_tipo_operacion=$idOperadorTipoOperacion
								group by op.id_operacion,op.id_operador_tipo_operacion,op.fecha_finalizacion order by op.id_operacion asc limit 1),
						fecha_finalizacion=null
					WHERE 
						id_operador_tipo_operacion=$idOperadorTipoOperacion;";
	   
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function obtenerOperacionesMaterilReproductivoSAporCaducar($conexion){

		$consulta = "SELECT
						distinct id_operador_tipo_operacion, identificador_operador
					FROM
						g_operadores.operaciones
					WHERE   
						to_char(fecha_finalizacion,'YYYY-MM-DD')::date = current_date
						and id_tipo_operacion in (
						(select id_tipo_operacion from g_catalogos.tipos_operacion where codigo='PMR' and id_area='SA'),
						(select id_tipo_operacion from g_catalogos.tipos_operacion where codigo='CPM' and id_area='SA'),
						(select id_tipo_operacion from g_catalogos.tipos_operacion where codigo='DMR' and id_area='SA'),
						(select id_tipo_operacion from g_catalogos.tipos_operacion where codigo='AMR' and id_area='SA')
						);";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
					
	public function actualizarEstadoDocumentoOperadorPorLista($conexion,$valores){

		$consulta = "UPDATE g_operadores.documentos_operador as t1 set
						estado = t2.estado                        
					FROM 
						(values
							$valores
						) as t2(id_operador_tipo_operacion, operador, estado)
					WHERE
						t1.id_operador_tipo_operacion = t2.id_operador_tipo_operacion and
						t1.identificador_operador = t2.operador;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarEstadoAnteriorMaterialReproductivo($conexion, $idOperacion){
							
		$res = $conexion->ejecutarConsulta("update
												g_operadores.operaciones o
											set
												estado_anterior = op.estado
											from
												g_operadores.operaciones op
											where
												o.id_operador_tipo_operacion in ($idOperacion)
												and op.id_operacion = o.id_operacion;");
				return $res;
	}

	public function inactivarOperacionesMaterialReproductivo($conexion, $idOperacion){
							
		$consulta = "update
						g_operadores.operaciones o
					set
						estado = 'noHabilitado',	
						observacion = 'Operación caducada el '|| o.fecha_finalizacion 
					from
						g_operadores.operaciones op
					where
						o.id_operador_tipo_operacion in ($idOperacion)	
						and o.id_operacion = op.id_operacion;";

		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}


	public function inactivarAreaOperacionMaterialReproductivo($conexion, $idOperacion){
							
		$consulta = "update
						g_operadores.productos_areas_operacion o
					set
						estado = 'noHabilitado',	
						observacion = 'Operación caducada el '|| op.fecha_finalizacion 
					from
						g_operadores.operaciones op
					where
						op.id_operador_tipo_operacion in ($idOperacion)					
						and o.id_operacion = op.id_operacion;";

		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	/// fin control de cambio material reproductivo
	
	/**
	 * certificación de origen
	 */
	public function buscarCentroFaenamiento($conexion,$arrayParametros)
	{
	    $busqueda = '';
	    if (array_key_exists('identificador_operador', $arrayParametros)) {
	        $busqueda .= " and o.identificador = '" . $arrayParametros['identificador_operador'] . "'";
	    }
	    if (array_key_exists('razon_social', $arrayParametros)) {
	        $busqueda .= " and o.razon_social = '" . $arrayParametros['razon_social'] . "'";
	    }
	    
	    $consulta = "SELECT
                    	o.identificador as identificador_operador,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
                    	s.provincia,
                        string_agg(distinct stp.nombre,', ') as especie,
                        s.id_sitio, s.nombre_lugar,
                        a.id_area,
                        op.id_operador_tipo_operacion,
                        cf.id_centro_faenamiento,
                        cf.criterio_funcionamiento,
                        cf.codigo,
                        cf.tipo_centro_faenamiento,
                        cf.tipo_habilitacion
                    FROM
                    	g_operadores.operadores o
                    	INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                        INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                        INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion
                        INNER JOIN g_catalogos.productos p ON p.id_producto = op.id_producto
                        INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = p.id_subtipo_producto
                        LEFT JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_sitio = s.id_sitio and cf.id_area = a.id_area and cf.id_operador_tipo_operacion = op.id_operador_tipo_operacion
                    WHERE
                        top.id_area = '" . $arrayParametros['id_area_tipo_operacion'] . "'
                        and top.codigo = '" . $arrayParametros['codigo'] . "'
                        and op.estado in ('registrado')
                        and cf.criterio_funcionamiento in ('Habilitado','Activo') 
                        and upper(s.provincia) = upper('". $arrayParametros['provincia'] ."')
                        " . $busqueda . "
                    GROUP BY
                        o.identificador, s.provincia, s.id_sitio, a.id_area, op.id_operador_tipo_operacion, cf.id_centro_faenamiento;";
	    
	    return $conexion->ejecutarConsulta($consulta);
	}
	/*
	 * obtener las areas de centros de faenamiento
	 */
	public function buscarAreaXSitioCentroFaenamiento($conexion,$arrayParametros)
	{
	    $consulta = "
                    SELECT 
                        a.id_area, 
                        a.nombre_area,
                        cf.id_centro_faenamiento
                    FROM  g_operadores.areas a 
                    INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area 
                    INNER JOIN g_operadores.operaciones op ON op.id_operacion = pao.id_operacion 
                    INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion 
                    INNER JOIN g_centros_faenamiento.centros_faenamiento cf ON cf.id_sitio = " . $arrayParametros['id_sitio'] ."
                    and cf.id_area = a.id_area and cf.id_operador_tipo_operacion = op.id_operador_tipo_operacion 
                    WHERE 
                        top.id_area = '" . $arrayParametros['id_area_tipo_operacion'] . "'
                        and top.codigo = '" . $arrayParametros['codigo'] . "'
                        and op.estado in ('registrado') 
                        and cf.criterio_funcionamiento in ('Habilitado','Activo') 
                    GROUP BY a.id_area, cf.id_centro_faenamiento;";
	    return $conexion->ejecutarConsulta($consulta);
	}
	/*
	 * obtener las areas de centros de faenamiento
	 */
	public function buscarCentroFaenamientoXid($conexion,$arrayParametros)
	{
	    $consulta = "
                    SELECT
                        o.identificador as identificador_operador,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end razon_social,
						s.nombre_lugar,
						a.nombre_area,
						s.provincia
                    FROM 
                        g_centros_faenamiento.centros_faenamiento cf 
                        INNER JOIN g_operadores.operadores o ON o.identificador = cf.identificador_operador
                        INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                        INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
                    WHERE
                        s.id_sitio = cf.id_sitio and
                        a.id_area = cf.id_area and 
                        cf.id_centro_faenamiento = ".$arrayParametros['id_centro_faenamiento']." ;";
	    return $conexion->ejecutarConsulta($consulta);
	}
	public function guardarCentroFaenamienTransporte($conexion,$arrayParametros){
	    
	    $consulta="INSERT INTO g_operadores.centros_faenamiento_transporte(
								id_centro_faenamiento, id_operacion,id_area)
						VALUES (".$arrayParametros['id_centro_faenamiento'].", ".$arrayParametros['id_operacion'].", ".$arrayParametros['id_area'].");";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	public function consultarCentroFaenamienTransporte($conexion,$arrayParametros){
	    $busqueda = 'true';
	    if (array_key_exists('id_centro_faenamiento', $arrayParametros)) {
	        $busqueda .= " and id_centro_faenamiento = " . $arrayParametros['id_centro_faenamiento'];
	    }
	    if (array_key_exists('id_operacion', $arrayParametros)) {
	        $busqueda .= " and id_operacion = " . $arrayParametros['id_operacion'];
	    }
	    if (array_key_exists('id_area', $arrayParametros)) {
	        $busqueda .= " and id_area = " . $arrayParametros['id_area'];
	    }
	   $consulta="
                    SELECT 
                        id_centro_faenamiento, id_centros_faenamiento_transporte
                    FROM
                        g_operadores.centros_faenamiento_transporte
                    WHERE       
                        ".$busqueda.";";
                        
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}

	public function consultarUltimoCentroFaenamienTransporteXOperacioXArea($conexion, $arrayParametros){
	    $consulta="
					SELECT 
					max(id_centro_faenamiento) as id_centro_faenamiento
				FROM 
					g_operadores.centros_faenamiento_transporte 
				WHERE 
					id_operacion = ".$arrayParametros['id_operacion'] ." and id_area = ".$arrayParametros['id_area'].";";
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;		
	}
	
	public function eliminarCentroFaenamienTransporte($conexion,$arrayParametros){
	    $consulta="
                    DELETE FROM g_operadores.centros_faenamiento_transporte
	                WHERE id_centros_faenamiento_transporte=".$arrayParametros['id_centros_faenamiento_transporte'].";";
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	/**
	 * fin de certificación de origen
	 */
	 public function obtenerFechaCreacionRegistroOrganicos($conexion, $idOperador, $idTipoOperacion){

	    $res = $conexion->ejecutarConsulta("SELECT
											to_char(min(rd.fecha_inspeccion),'YYYY-MM-DD') as fecha_creacion, to_char(max(rd.fecha_inspeccion),'YYYY-MM-DD') as fecha_actualizacion
											FROM 
											g_revision_solicitudes.revision_documental rd
											INNER JOIN (SELECT id_grupo
													FROM g_revision_solicitudes.asignacion_inspector ai
													INNER JOIN (SELECT
																	min(id_operacion)as id_operacion, id_operador_tipo_operacion, id_historial_operacion
																FROM
																	g_operadores.operaciones
																WHERE
																	identificador_operador = '" . $idOperador . "'
																	and id_tipo_operacion = " . $idTipoOperacion . "
																	and id_operacion = (
																				SELECT
																					min(id_operacion)
																				FROM
																					g_operadores.operaciones
																				WHERE
																					identificador_operador = '" . $idOperador . "'
																					and id_tipo_operacion = " . $idTipoOperacion . "
																					and estado='registrado'
																				)
																GROUP BY id_operador_tipo_operacion, id_historial_operacion) as id_hi_op
													ON ai.id_operador_tipo_operacion = id_hi_op.id_operador_tipo_operacion and ai.id_historial_operacion = id_hi_op.id_historial_operacion
												WHERE
													ai.tipo_solicitud = 'Operadores'
													and ai.tipo_inspector ='Documental') as re_so
											ON rd.id_grupo in (re_so.id_grupo)
											WHERE
											rd.estado = 'registrado'");
	    
	    return $res;
	}
	
	public function obtenerFechaActualizacionRegistroOrganicos($conexion, $idOperador, $idTipoOperacion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
											to_char(min(rd.fecha_inspeccion),'YYYY-MM-DD') as fecha_creacion, to_char(max(rd.fecha_inspeccion),'YYYY-MM-DD') as fecha_actualizacion
											FROM
											g_revision_solicitudes.revision_documental rd
											INNER JOIN (SELECT id_grupo
													FROM g_revision_solicitudes.asignacion_inspector ai
													INNER JOIN (SELECT
																	min(id_operacion)as id_operacion, id_operador_tipo_operacion, id_historial_operacion
																FROM
																	g_operadores.operaciones
																WHERE
																	identificador_operador = '" . $idOperador . "'
																	and id_tipo_operacion = " . $idTipoOperacion . "
																	and id_operacion = (
																				SELECT
																					max(id_operacion)
																				FROM
																					g_operadores.operaciones
																				WHERE
																					identificador_operador = '" . $idOperador . "'
																					and id_tipo_operacion = " . $idTipoOperacion . "
																					and estado='registrado'
																				)
																GROUP BY id_operador_tipo_operacion, id_historial_operacion) as id_hi_op
													ON ai.id_operador_tipo_operacion = id_hi_op.id_operador_tipo_operacion and ai.id_historial_operacion = id_hi_op.id_historial_operacion
												WHERE
													ai.tipo_solicitud = 'Operadores'
													and ai.tipo_inspector ='Documental') as re_so
											ON rd.id_grupo in (re_so.id_grupo)
											WHERE
											rd.estado = 'registrado'");
	    
	    return $res;
	}
	
	public function obtenerProveedoresPorIdOperacionPorEstado($conexion, $idOperacion, $estado){
	    
	    $consulta = "SELECT
						*
                    FROM 
                        g_operadores.proveedores
                    WHERE
                        id_operacion = $idOperacion
                        and estado_proveedor = '" . $estado . "';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerOperacionesActualizarCertificadoPorEstado($conexion, $estado){
	    
	    $consulta = "SELECT 
                                DISTINCT min(op.id_operacion) as id_operacion
										
                                , op.identificador_operador
                                , op.id_operador_tipo_operacion
                                , top.id_area
                                , top.codigo
                                , top.id_tipo_operacion
                                , s.codigo_provincia
                                , a.id_area as id_codigo_area
                                , s.provincia
                           FROM g_operadores.operaciones op 											
                                INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                                INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                                INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                                INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                           WHERE
                                actualizar_certificado = '" . $estado . "'
                                GROUP BY op.identificador_operador, op.id_operador_tipo_operacion, top.id_area, top.codigo, top.id_tipo_operacion, s.codigo_provincia, a.id_area, s.provincia
                                ORDER BY op.id_operador_tipo_operacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerInspectorUltimaRevisionDocumental($conexion, $idOperadorTipoOperacion, $idHistorialOperacion){
	    
	    $consulta = "SELECT
                       rd.identificador_inspector
                    FROM 
                    g_revision_solicitudes.revision_documental rd
                    INNER JOIN (SELECT 
                    				max(id_grupo) as id_grupo
                    			FROM 
                    				g_revision_solicitudes.asignacion_inspector ai
                    			WHERE
                    				id_operador_tipo_operacion = '" . $idOperadorTipoOperacion . "'
                    				and id_historial_operacion = '" . $idHistorialOperacion . "'
                    				and tipo_solicitud = 'Operadores'
                    				and tipo_inspector ='Documental') as re_so
                    ON rd.id_grupo = re_so.id_grupo;";
                    	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function cambiarEstadoActualizarCertificado($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $actualizarCertificado){
	    
	    $consulta = "UPDATE 
                        g_operadores.operaciones
                     SET 
                        actualizar_certificado = '" . $actualizarCertificado . "'
                     WHERE
                        id_operador_tipo_operacion = $idOperadorTipoOperacion
                        and id_historial_operacion = $idHistorialOperacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}

	public function obtenerEstadosOperacionesPorOperadorPorIdTipoOperacion($conexion, $identificadorOperador, $idTipoOperacion){
	    
	    $consulta = "SELECT
                        DISTINCT estado
                     FROM
                        g_operadores.operaciones
                     WHERE
                        identificador_operador = '" . $identificadorOperador . "'
                        and id_tipo_operacion = $idTipoOperacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function verificarCertificadoOrganico($conexion, $identificadorOperador, $idTipoOperacion){
	    
	    $consulta = "SELECT 
                        identificador_operador
                        , cp.id_codigo_poa
                        , scp.id_subcodigo_poa
                        , scp.estado
                     FROM 
                        g_operadores.codigos_poa cp
                        INNER JOIN g_operadores.subcodigos_poa scp ON cp.id_codigo_poa = scp.id_codigo_poa
                     WHERE 
                        cp.identificador_operador = '" . $identificadorOperador . "'
                        and scp.id_tipo_operacion = $idTipoOperacion;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarEstadoCertificadoOrganico($conexion, $idSubcodigoPOA, $estado){
	    
	    $consulta = "UPDATE 
                    	g_operadores.subcodigos_poa
                    SET 
                    	estado = '" .$estado . "'
                    WHERE 
                    	id_subcodigo_poa = $idSubcodigoPOA;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function verificarOperacionesBanano($conexion, $identificador,$estado ='registrado') {
		
		$consulta="SELECT id_operacion FROM g_operadores.operaciones op INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
	                WHERE top.codigo = 'EXB' and id_area = 'SV' and op.identificador_operador = '$identificador' and op.estado = '$estado';";

		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;

	}
	
	public function verificarOperacionesBananoPostRegistro($conexion, $grupoOperaciones, $tipoOperacion, $idArea) {
		
		$consulta="SELECT 
						id_operacion 
					FROM 
						g_operadores.operaciones op 
						INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
	                WHERE 
						op.id_operacion IN $grupoOperaciones 
						and top.codigo IN $tipoOperacion   
						and id_area = '$idArea';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
		
	}
	
	public function cambiarEstadoActualizarCertificadoPorIdentificadorOperacion($conexion, $idOperacion, $actualizarCertificado){
		
		$consulta = "UPDATE
                        g_operadores.operaciones
                     SET
                        actualizar_certificado = '" . $actualizarCertificado . "'
                     WHERE
                        id_operacion = $idOperacion;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerInformacionVehiculoTransporteAnimalesVivosPorPlaca($conexion, $placa){
	    
	    $consulta = "SELECT
								*
						FROM
							g_operadores.datos_vehiculo_transporte_animales
						WHERE
							placa_vehiculo = '" . $placa . "';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function generarCodigoTransporteAnimalesVivos($conexion, $seccionCodigo){
	    
	    $consulta = "SELECT
                    	COALESCE(MAX(SPLIT_PART(SUBSTRING(codigo_certificado, 1, 15), '" . $seccionCodigo . "' , 2)), '0') as numero
                    FROM
                    	g_operadores.datos_vehiculo_transporte_animales
                    WHERE codigo_certificado LIKE '%" . $seccionCodigo . "%';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function guardarInformacionDatosVehiculoTransporteAnimalesVivos($conexion, $idArea, $idTipoOperacion, $idOperadorTipoOperacion, $idHistorialOperacion, $idCodigoProvincia, $anioCertificado, $placa, $identificadorPropietario, $marca, $modelo, $anio, $color, $clase, $tipo, $tamanioContenedor, $caracteristicaContenedor){
	    
	    //Se crea una funcion en base de datos para guardar el registro del vehiculo y para generar el código secuencial sin repetirlo
		$consulta = "SELECT g_operadores.insertar_datos_transporte_animales_vivos($idArea, $idTipoOperacion, $idOperadorTipoOperacion, $idHistorialOperacion, $idCodigoProvincia, $anioCertificado, '" . $placa . "', '" . $identificadorPropietario . "', '" . $marca . "', '" . $modelo . "', '" . $anio . "', '" . $color . "', '" . $clase . "', '" . $tipo . "', '" . $tamanioContenedor . "', '" . $caracteristicaContenedor . "')";
																																															
        $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function obtenerDatosMedioTrasporteAnimalesVivosPorIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion){
	    
	    $consulta = "SELECT 
                            id_dato_vehiculo_transporte_animales
                            , id_area
                            , id_tipo_operacion
                            , id_operador_tipo_operacion
                            , id_historial_operacion
                            , codigo_certificado
                            , placa_vehiculo
                            , identificador_propietario_vehiculo
                            , marca_vehiculo
                            , modelo_vehiculo
                            , anio_vehiculo
                            , color_vehiculo
                            , clase_vehiculo
                            , tipo_vehiculo
                            , tamanio_contenedor_vehiculo
                            , caracteristica_contenedor_vehiculo
                            , fecha_modificacion
                            , fecha_creacion
                            , estado_vehiculo
                       FROM 
                            g_operadores.datos_vehiculo_transporte_animales
                       WHERE
                            id_operador_tipo_operacion = '" . $idOperadorTipoOperacion . "';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function obtenerDatosMedioTrasporteAnimalesVivosPorPropietarioPorPlaca($conexion, $identificadorPropietario, $placa){
	    
	    $consulta = "SELECT
                    	id_dato_vehiculo_transporte_animales
                    	, placa_vehiculo
                    	, identificador_propietario_vehiculo
                    FROM
                    	g_operadores.datos_vehiculo_transporte_animales
                    WHERE
                    	identificador_propietario_vehiculo = '" . $identificadorPropietario . "'
                    	and placa_vehiculo = '". $placa . "'
                        and estado_vehiculo = 'activo';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function actualizarDatosMedioTrasporteAnimalesVivosPorIdDatoVehiculo($conexion, $idDatoVehiculo, $tamanioContenedor, $caracteristicaContenedor){
	    
	    $consulta = "UPDATE 
                    	g_operadores.datos_vehiculo_transporte_animales
                    SET 
                        tamanio_contenedor_vehiculo = '" . $tamanioContenedor . "'
                        , caracteristica_contenedor_vehiculo = '" . $caracteristicaContenedor . "' 
                    WHERE
                        id_dato_vehiculo_transporte_animales = $idDatoVehiculo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function listarDatosVehiculoTransporteAnimalesPorIdProvinciaPorEstado($conexion, $identificadorOperador, $nombreProvincia, $estado){
	    
	    if ($nombreProvincia != ""){
	        $busqueda = " and UPPER(o.provincia) = UPPER('$nombreProvincia')";
	    }
	    
	    $consulta = "SELECT 
                    	DISTINCT MIN (op.id_operacion) as id_operacion 
                    	, op.identificador_operador
                    	, CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                    	, s.id_sitio
                        , s.provincia as provincia_sitio
                    	, s.nombre_lugar as nombre_sitio
                    	, a.nombre_area as nombre_area
                    	, s.identificador_operador||'.'||s.codigo_provincia || s.codigo ||a.codigo||a.secuencial as codigo_area
                    FROM 
                    	g_operadores.sitios s
                    	INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
                    	INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                    	INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
                    	INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                    	op.identificador_operador ='" . $identificadorOperador . "'
                    	" . $busqueda . "
                    	and top.id_area || top.codigo in ('SATAV')
                    	and op.estado = '" . $estado . "'
                    GROUP BY 
                    	op.identificador_operador, nombre_operador, s.id_sitio, provincia_sitio, nombre_sitio, nombre_area, codigo_area;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarFechaAprobacionMedioTrasporteAnimalesVivosPorIdDatoVehiculo($conexion, $idDatoVehiculo){
	    
	    $consulta = "UPDATE 
                        g_operadores.datos_vehiculo_transporte_animales
                     SET 
                        fecha_aprobacion = 'now()'
                     WHERE 
                        id_dato_vehiculo_transporte_animales = $idDatoVehiculo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function inactivarMedioTrasporteAnimalesVivosPorIdDatoVehiculo($conexion, $idDatoVehiculo){
	    
	    $consulta = "UPDATE
                        g_operadores.datos_vehiculo_transporte_animales
                     SET
                        estado_vehiculo = 'inactivo'
                        , fecha_modificacion = 'now()'
                     WHERE
                        id_dato_vehiculo_transporte_animales = $idDatoVehiculo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function insertarDatosVehiculoTransporteAnimalesExpirado($conexion, $idDatoVehiculoAntiguo, $idDatoVehiculoNuevo){
	    
	    $consulta = "INSERT INTO 
                    	g_operadores.vehiculo_transporte_animales_expirado(id_dato_vehiculo_antiguo, id_dato_vehiculo_nuevo)
                    VALUES ($idDatoVehiculoAntiguo, $idDatoVehiculoNuevo);";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerOperacionesOperadorMasivo ($conexion, $identificador, $estado, $idArea, $idTipoOperacion){

	    $res = $conexion->ejecutarConsulta("SELECT
                                            	DISTINCT MIN(op.id_operacion) as id_operacion
                                            	, op.identificador_operador
                                            	, op.estado
                                            	, op.id_tipo_operacion
                                            	, top.nombre as nombre_tipo_operacion
                                            	, st.provincia
                                            	, st.id_sitio
                                            	, st.nombre_lugar
                                            	, top.codigo
                                            	, a.id_area
                                            FROM
                                            	g_operadores.operaciones op
                                            INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                                            INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
                                            INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                                            INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                                            INNER JOIN g_operadores.sitios st ON a.id_sitio = st.id_sitio
                                            INNER JOIN g_operadores.flujos_operaciones fo ON top.id_flujo_operacion = fo.id_flujo
                                            WHERE
                                            	op.identificador_operador = '" . $identificador . "'
                                            	and op.estado  in ('cargarProducto', 'registrado', 'subsanacionProducto')
                                            	and top.codigo NOT IN ('IMP', 'EXP')
                                            	and top.id_tipo_operacion = '" . $idTipoOperacion . "'
                                            	and a.id_area = '" . $idArea . "'
                                            GROUP BY op.identificador_operador, op.estado, op.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area, top.codigo
                                            ORDER BY id_operacion;");
	    return $res;
	}
	
	public function eliminarOperacionMercadoDestinoXIdOperacion($conexion, $idOperacion){
	    
	    $res = $conexion->ejecutarConsulta("DELETE
											FROM
												g_operadores.mercado_destino
											WHERE
												id_operacion = $idOperacion;");
	    
	    return $res;
	}
	
	//Registro de operador de laboratorios
	
	public function obtenerRangosLaboratorioProdcuto($conexion, $idOperacion, $idOperadorTipoOperacion, $idHistorialOperacion, $idRango, $estado = 'Activo'){
		
		$consulta = "SELECT
						*
					FROM
						g_operadores.operaciones_parametro_laboratorios
					WHERE
						id_operacion = '$idOperacion'
						and id_operador_tipo_operacion = '$idOperadorTipoOperacion'
						and id_historial_operacion = '$idHistorialOperacion'
						and id_rango = '$idRango'
						and estado = '$estado';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function guardarOperacionesParametrosLaboratorio($conexion, $idOperacion, $idParametro, $nombreParametro, $idMetodo, $nombreMetodo, $idRango, $nombreRango, $idOperadorTipoOperacion, $idHistorialOperacion){
		
		$consulta = "INSERT INTO 
							g_operadores.operaciones_parametro_laboratorios(id_operacion, id_parametro, nombre_parametro, id_metodo, nombre_metodo, id_rango, descripcion_rango, id_operador_tipo_operacion, id_historial_operacion)
					VALUES ('$idOperacion','$idParametro','$nombreParametro','$idMetodo','$nombreMetodo','$idRango','$nombreRango','$idOperadorTipoOperacion','$idHistorialOperacion') RETURNING id_operacion_parametro_laboratorio;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function imprimirLineaProductoLaboratorio($idSolicitud, $nombreParametro, $nombreMetodo, $nombreRango, $nombreProducto, $procesoEliminacion){
		
		$condicion = '';
		
		if($procesoEliminacion == '0'){
			$condicion = '<button type="button" class="icono"></button>';
		}
		
		$cadena = '<tr id="RL'.$idSolicitud.'">' .
			'<td>'.$nombreProducto.'</td>'.
			'<td>'.$nombreParametro.'</td>'.
			'<td>'.$nombreMetodo.'</td>'.
			'<td>'.$nombreRango.'</td>';
		
		/*$cadena .='<td style="text-align:center">'.
			'<input type="hidden" name="idSolicitud" value="' . $idSolicitud . '">' .
			$condicion .
			'</td>' .
			'</tr>';*/
			
			return $cadena;
	}
	
	public function guardarOperacionesLaboratorio($conexion, $idOperacion, $idOperadorTipoOperacion, $idHistoricoOperacion, $procesoPago, $rutaPago, $procesoSancion, $rutaSancion, $certificadoSae){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
		g_operadores.operaciones_laboratorios(id_operacion, id_operador_tipo_operacion, id_historial_operacion, proceso_pago, ruta_pago, proceso_sancion, ruta_sancion, certificado_sae)
		SELECT $idOperacion,$idOperadorTipoOperacion,$idHistoricoOperacion,'$procesoPago','$rutaPago','$procesoSancion','$rutaSancion','$certificadoSae'
		WHERE NOT EXISTS (SELECT id_operacion, id_operador_tipo_operacion, id_historial_operacion FROM g_operadores.operaciones_laboratorios WHERE 
		id_operacion = $idOperacion AND id_operador_tipo_operacion = $idOperadorTipoOperacion AND id_historial_operacion = $idHistoricoOperacion);");
		return $res;
	}

	public function obtenerParamtrosLaboratorioOperaciones($conexion, $idOperadorTipoOperacion, $estado = 'Activo'){
		
		$consulta = "SELECT
						opl.nombre_parametro, opl.nombre_metodo, opl.descripcion_rango, o.nombre_producto
					FROM
						g_operadores.operaciones o 
						INNER JOIN g_operadores.operaciones_parametro_laboratorios opl ON o.id_operador_tipo_operacion = opl.id_operador_tipo_operacion and o.id_historial_operacion = opl.id_historial_operacion
					WHERE
						opl.id_operador_tipo_operacion = '$idOperadorTipoOperacion'										  									  
						and opl.estado = '$estado';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actualizarOperacionesLaboratorio($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion, $rutaConvenio, $fechaConvenio, $codigoLaboratorio){
		
		$consulta = "UPDATE
						g_operadores.operaciones_laboratorios
					SET
						ruta_convenio = '$rutaConvenio',
						fecha_firma_convenio = '$fechaConvenio',
						codigo_laboratorio = '$codigoLaboratorio'
					WHERE
						id_operador_tipo_operacion = '$idOperadorTipoOperacion'
						and id_historial_operacion = '$idHistoricoOperacion';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actualizarFechaFinalizacionOperaciones ($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion, $valorVigencia, $tipoTiempoVigencia, $fechaAprobacion, $idVigenciaDocumento = null){
		
		$idVigenciaDocumento = $idVigenciaDocumento!="" ? "'" . $idVigenciaDocumento . "'" : "NULL";
		
		$consulta = "UPDATE
						g_operadores.operaciones
					SET
						fecha_aprobacion = '$fechaAprobacion',
						fecha_finalizacion = '$fechaAprobacion'::TIMESTAMP + interval '" . $valorVigencia . " " . $tipoTiempoVigencia . "'
					WHERE
						id_operador_tipo_operacion = $idOperadorTipoOperacion and
						id_historial_operacion = $idHistoricoOperacion and
    					($idVigenciaDocumento is NULL or id_vigencia_documento = $idVigenciaDocumento);";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function buscarOperadorProductoActividad ($conexion, $identificador, $idProducto, $idActividad, $estado){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_operadores.operaciones
													WHERE
														identificador_operador = '$identificador'
														and id_producto = $idProducto
														and id_tipo_operacion = $idActividad
														and estado = '$estado';");
	    return $res;
	}
	
	public function obtenerProductosLaboratorios($conexion, $idOperadorTipoOperacion){
			
		$res = $conexion->ejecutarConsulta("SELECT
											p.nombre_comun, opl.nombre_parametro, opl.nombre_metodo, opl.descripcion_rango, opl.id_operacion, opl.id_operacion_parametro_laboratorio, op.id_tipo_operacion
											
											FROM
											g_operadores.operaciones_parametro_laboratorios opl
											INNER JOIN g_operadores.operaciones op ON op.id_operacion = opl.id_operacion
											INNER JOIN  g_catalogos.productos p ON p.id_producto = op.id_producto
											WHERE
											opl.id_operador_tipo_operacion =$idOperadorTipoOperacion and opl.estado = 'Activo';");
		
		
		return $res;
	}
	public function eliminarProductoLaboratorio ($conexion, $idSolicitud,$idParamatroLaboratorio){

		$res = $conexion->ejecutarConsulta("DELETE
											FROM
												g_operadores.operaciones_parametro_laboratorios
											WHERE
												id_operacion = $idSolicitud
												and id_operacion_parametro_laboratorio=$idParamatroLaboratorio;");
		return $res;
	}

	public function obtenerDatosVehiculoXIdOperadorTipoOperacionPorEstado($conexion, $idOperadorTipoOperacion, $estadoDatoVehiculo){
		    
		$consulta = "SELECT
						*
					FROM
						g_operadores.datos_vehiculos
						
					WHERE
						id_operador_tipo_operacion = $idOperadorTipoOperacion
						and estado_dato_vehiculo = '$estadoDatoVehiculo'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function verificarVehiculoRegistradoEstado($conexion, $placa, $idOperadorTipoOperacion){

		$placa = $placa !="" ? "'" . $placa . "'" : "null";
		$consulta = "SELECT string_agg(DISTINCT(op.estado), ', ') as estado , dv.id_operador_tipo_operacion, op.id_operador_tipo_operacion,dv.id_dato_vehiculo
				   FROM 
				   g_operadores.operaciones op
				   INNER JOIN g_operadores.datos_vehiculos dv 
				   ON dv.id_operador_tipo_operacion = op.id_operador_tipo_operacion
				   WHERE
				   dv.estado_dato_vehiculo ='activo' and
				   (dv.placa_vehiculo = $placa or dv.id_operador_tipo_operacion= $idOperadorTipoOperacion) and 
				   op.estado NOT in ('noHabilitado') 
				   GROUP BY dv.id_operador_tipo_operacion, op.id_operador_tipo_operacion,dv.id_dato_vehiculo";
	   $res = $conexion->ejecutarConsulta($consulta);
	   
	   return $res;
    }
	
	public function inactivarVehiculoMedioTransporte($conexion, $idDatoVehiculo){
		    
	$res = $conexion->ejecutarConsulta("UPDATE
										g_operadores.datos_vehiculos
										
									SET
										estado_dato_vehiculo = 'inactivo'
									WHERE
										id_dato_vehiculo IN ($idDatoVehiculo);");
	    return $res;
    }
	
	public function inactivarCentroAcopio($conexion, $idDatoVehiculo, $idOperadorTipoOperacion){
			
		$res = $conexion->ejecutarConsulta("UPDATE
												g_operadores.centros_acopio
											SET
												estado_centro_acopio = 'inactivo'
											WHERE
                                                id_operador_tipo_operacion in ($idOperadorTipoOperacion)
												and id_centro_acopio NOT IN ($idDatoVehiculo);");
		return $res;
	}
	
	public function obtenerDatosCentroAcopioXIdOperadorTipoOperacionPorEstado($conexion, $idOperadorTipoOperacion, $estadoDatoVehiculo){
	    
		$consulta = "SELECT
							*
						FROM
							g_operadores.centros_acopio
						WHERE
							id_operador_tipo_operacion = $idOperadorTipoOperacion
							and estado_centro_acopio = '$estadoDatoVehiculo'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}

	public function inactivarVehiculoMedioTransporteXAreayOperacion($conexion, $idOperadorTipoOperacion,$idAreas){
		    
		$res = $conexion->ejecutarConsulta("UPDATE
											g_operadores.datos_vehiculos
											
										SET
											estado_dato_vehiculo = 'inactivo'
										WHERE
											id_area IN ($idAreas) and id_operador_tipo_operacion in ($idOperadorTipoOperacion);");
		return $res;
	}
	
	public function inactivarVehiculo($conexion, $idOperadorTipoOperacion){
		    
		$res = $conexion->ejecutarConsulta("UPDATE
											g_operadores.datos_vehiculos
											
										SET
											estado_dato_vehiculo = 'inactivo'
										WHERE
											id_operador_tipo_operacion IN ($idOperadorTipoOperacion) AND estado_dato_vehiculo = 'activo';");
		return $res;
	}

	public function obtenerOperacionesXIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion, $estado = ''){
		$estado = $estado != "" ? $estado : "NULL";
		$consulta = "SELECT 
			id_operacion
		FROM 
			g_operadores.operaciones 
		WHERE
			id_operador_tipo_operacion = $idOperadorTipoOperacion
			and (($estado) is NULL or estado in ($estado));";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarCentrosAcopioInspeccion($conexion, $idOperadorTipoOperacion, $identificadorRevisor, $origenInspeccion, $estadoChecklist){
	    
	    $consulta = "UPDATE
                        g_operadores.centros_acopio
                    SET
                        origen_inspeccion = '" . $origenInspeccion . "'
                        , estado_checklist = '" . $estadoChecklist . "'
                    WHERE
                        id_operador_tipo_operacion = '" . $idOperadorTipoOperacion . "'
                        and estado_centro_acopio = 'activo' RETURNING id_centro_acopio;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}

	public function actualizarDatosVehiculoInspeccion($conexion, $idOperadorTipoOperacion, $identificadorRevisor, $origenInspeccion, $estadoChecklist){
	    
	    $consulta = "UPDATE
                        g_operadores.datos_vehiculos
                    SET
                        origen_inspeccion = '" . $origenInspeccion . "'
                        , estado_checklist = '" . $estadoChecklist . "'
                    WHERE
                        id_operador_tipo_operacion = '" . $idOperadorTipoOperacion . "'
                        and estado_dato_vehiculo = 'activo' RETURNING id_dato_vehiculo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}

	public function obtenerInspectorUltimaInspeccion($conexion, $idOperadorTipoOperacion, $idHistorialOperacion){
	    
	    $consulta = "SELECT
                       i.identificador_inspector
                    FROM
                    g_revision_solicitudes.inspeccion i
                    INNER JOIN (SELECT
                    				max(id_grupo) as id_grupo
                    			FROM
                    				g_revision_solicitudes.asignacion_inspector ai
                    			WHERE
									id_operador_tipo_operacion = '" . $idOperadorTipoOperacion . "'
									and id_historial_operacion = '" . $idHistorialOperacion . "'
                    				and tipo_solicitud = 'Operadores'
                    				and tipo_inspector ='Técnico') as re_so
                    				ON i.id_grupo = re_so.id_grupo;";
  
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
}