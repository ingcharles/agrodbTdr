<?php
class ControladorVacunacion{

	public function obtenerTipoUsuario($conexion,$usuario){
		$res = $conexion->ejecutarConsulta("SELECT
												p.codificacion_perfil
												,up.identificador
											FROM
												g_usuario.perfiles p,
												g_usuario.usuarios_perfiles up
											WHERE
												p.id_perfil=up.id_perfil and
												p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and
												up.identificador='".$usuario."';");
		return $res;
	}
	
	public function verificarTecnicoAgrocalidad($conexion, $usuario) {
		$res = $conexion->ejecutarConsulta ("SELECT
												fe.identificador , fe.nombre ||' '|| fe.apellido as nombres, dc.provincia
											FROM
												g_uath.ficha_empleado fe, g_uath.datos_contrato dc
											WHERE
												fe.identificador=dc.identificador and
												dc.estado=1 and 
												fe.identificador='".$usuario."' ;");
		return $res;
	}
	
	public function consultarEmpresaPorOperacion($conexion, $operacion ,$usuario) {
		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
												em.identificador identificador_empresa, em.id_empresa
											FROM
												g_usuario.empleados e
												,g_usuario.empresas em
												,g_operadores.operadores o
												,g_operadores.operaciones op
												,g_catalogos.tipos_operacion t
												,g_operadores.areas a
												,g_operadores.productos_areas_operacion pao
											WHERE
												o.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and pao.id_area = a.id_area
												and pao.id_operacion=op.id_operacion
												and op.estado = 'registrado'
												and t.codigo in $operacion
												and t.id_area='SA'
												and em.identificador=o.identificador
												and em.id_empresa=e.id_empresa
												and e.identificador='".$usuario."';");
		return $res;
	}
	
	public function consultarRelacionEmpleadoEmpresa($conexion,$usuario) {
		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
												em.identificador identificador_empresa
												,em.id_empresa
											FROM
												g_usuario.empleados e
												,g_usuario.empresas em
												
											WHERE
												em.id_empresa=e.id_empresa
												and e.estado='activo'
												and em.estado='activo'
												and e.identificador='".$usuario."';");
		return $res;
	}
	
	public function buscarNumeroCertificado($conexion,  $serie, $idEspecie){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												id_especie
												,numero_documento
												,estado
											FROM
												g_catalogos.certificados_vacunacion
											WHERE
												id_especie = '".$idEspecie."' and
												numero_documento ilike '%".$serie."%'");
		return $res;
	}

	public function filtrarSitiosVacunacion($conexion, $identificadorOperador, $nombreSitio){	
		$identificadorOperador = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "NULL";
		$nombreSitio = $nombreSitio!="" ? "'%" . $nombreSitio  . "%'" : "NULL";

		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												s.id_sitio
												,s.nombre_lugar
												,o.identificador
											FROM
												g_operadores.operadores o
												,g_operadores.sitios s
												,g_catalogos.tipos_operacion t
												,g_operadores.productos_areas_operacion pao
												,g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												dc.id_catastro=c.id_catastro
												and dc.estado_registro in ('activo', 'inactivo')
												and s.id_sitio=c.id_sitio
												and o.identificador = s.identificador_operador
												and c.id_tipo_operacion = t.id_tipo_operacion
												and s.id_sitio = c.id_sitio
												and s.estado='creado'
												and pao.id_area = c.id_area
												and pao.estado = 'registrado'
												and t.id_area = 'SA'
												and ($identificadorOperador is NULL or o.identificador = $identificadorOperador)
												and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio);");
		return $res;
	}

	public function listarAreasXsitiosOperacion($conexion, $idSitio, $idTipoOperacion){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
											    a.id_area,a.nombre_area
											FROM
												g_operadores.areas a,
												g_catastro.catastros c,
												g_catastro.detalle_catastros dc
											WHERE
												dc.id_catastro=c.id_catastro
												and dc.estado_registro in ('activo', 'inactivo')
												and c.id_area=a.id_area
												and a.estado='creado'
												and c.id_tipo_operacion='".$idTipoOperacion."'
												and c.id_sitio = '".$idSitio."';");
		return $res;
	}

	public function listaProductosVacunar($conexion, $idArea, $idEspecie, $idTipoOperacion,$aProductosActosVacunar){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												c.id_producto,p.nombre_comun nombre_producto, sp.nombre nombre_subtipo_producto
											FROM
												g_catalogos.productos p,
												g_catalogos.productos_animales pa,
												g_catastro.catastros c,
												g_catastro.detalle_catastros dc,
												g_catalogos.subtipo_productos sp
											WHERE
												sp.id_subtipo_producto = p.id_subtipo_producto
												and dc.id_catastro=c.id_catastro
												and dc.estado_registro='activo'
												and c.id_producto=p.id_producto
												and c.id_producto=pa.id_producto
												and c.id_producto IN $aProductosActosVacunar
												and c.id_tipo_operacion='$idTipoOperacion'
												and c.id_area = '$idArea'
												and pa.id_especie = '$idEspecie'
											ORDER BY 2 ASC;");
		return $res;
	}

	public function listaCantidadProducto($conexion,$idArea, $idProducto, $operacion, $unidadMedida, $identificadoresProducto){
		$res = $conexion->ejecutarConsulta("SELECT 
												COUNT(*) total
											FROM ( 
												SELECT 
													distinct (case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end) identif
												FROM 
													g_catastro.catastros c, g_catastro.detalle_catastros dc 
												WHERE 
													NOT EXISTS (
														SELECT di.identificador 
														FROM g_vacunacion.vacunacion v, g_vacunacion.detalle_vacunacion dv, g_vacunacion.detalle_identificadores di 
														WHERE v.id_vacunacion=dv.id_vacunacion and di.id_detalle_vacunacion=dv.id_detalle_vacunacion and v.estado='vigente' and 
						 									di.identificador= case when dc.identificador_producto is null then dc.identificador_unico_producto else dc.identificador_producto end and 
 															dv.id_tipo_operacion=c.id_tipo_operacion and dv.unidad_comercial=c.unidad_comercial and c.id_especie= v.id_especie
													) and 
													c.id_catastro=dc.id_catastro and c.id_area='$idArea' and c.id_producto='$idProducto' and c.id_tipo_operacion='$operacion' and c.unidad_comercial='$unidadMedida' and dc.estado_registro='activo'
												) as productosRPIP
											WHERE 
												row_to_json(productosRPIP) ->>'identif' NOT IN $identificadoresProducto 
											;");
		return $res;
	}
	
	public function listaLotesProductoVacunacion($conexion, $idArea, $idProducto,$operacion,$unidadMedida,$identificadoresProducto){
	    
		$res = $conexion->ejecutarConsulta("SELECT
													COUNT(row_to_json(productosRPIP) ->>'identif') total,
													row_to_json(productosRPIP) ->>'numero_lote' numero_lote,
													row_to_json(productosRPIP) ->>'id_producto' id_producto,
													row_to_json(productosRPIP) ->>'nombre_producto' nombre_producto	
												FROM (
													SELECT
														c.numero_lote, 
														COALESCE (dc.identificador_producto, dc.identificador_unico_producto) as identif, 
														c.id_producto, 
														c.nombre_producto
													FROM
													g_catastro.catastros c
													,g_catastro.detalle_catastros dc
												WHERE
													c.id_catastro=dc.id_catastro
													and c.id_area = '$idArea'
													and c.unidad_comercial = '$unidadMedida'
													and c.id_producto IN $idProducto
													and c.id_tipo_operacion = '$operacion'
													and	NOT EXISTS (SELECT 
																		di.identificador 
																	FROM 
																		g_vacunacion.vacunacion v, 
																		g_vacunacion.detalle_vacunacion dv, 
																		g_vacunacion.detalle_identificadores di 
																	WHERE 
																		v.id_vacunacion = dv.id_vacunacion
																		and di.id_detalle_vacunacion = dv.id_detalle_vacunacion 
																		and v.estado = 'vigente' 
																		and di.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto)  --case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end
																		and dv.id_tipo_operacion = c.id_tipo_operacion 
																		and dv.unidad_comercial = c.unidad_comercial 
																		and c.id_especie = v.id_especie)
													and c.numero_lote != ''
													) as productosRPIP
												WHERE
												row_to_json(productosRPIP) ->>'identif' NOT  IN ('')
											GROUP BY 2,3,4
											ORDER BY numero_lote asc;");
		return $res;
	}

	
	public function listaIdentificadoresProductoVacunacion($conexion, $idArea, $idProducto, $lote, $operacion, $unidadComercial, $identificadoresProducto){
		$lote = $lote != "" ? "'" . $lote . "'"  : "NULL";		

		$res = $conexion->ejecutarConsulta("SELECT 
												row_to_json(productosRPIP) ->>'id_detalle_catastro' id_detalle_catastro,
                                            	row_to_json(productosRPIP) ->>'id_catastro' id_catastro,
                                            	row_to_json(productosRPIP) ->>'id_producto' id_producto,
                                            	row_to_json(productosRPIP) ->>'identif' identificador_producto,
                                            	row_to_json(productosRPIP) ->>'estado_registro' estado_registro,
												row_to_json(productosRPIP) ->>'nombre_producto' nombre_producto
											FROM ( 
												SELECT
													dc.id_detalle_catastro
                                            		, c.id_catastro
                                            		, c.id_producto													
                                            		--, COALESCE (dc.identificador_producto, dc.identificador_unico_producto) as identif --//Se quita x que se deben vacunar solo areteados
													, dc.identificador_producto as identif --se agrega esto ya que deben estar areteados para vacunar
													, dc.estado_registro
													, c.nombre_producto
												FROM
													g_catastro.catastros c
													,g_catastro.detalle_catastros dc
													,g_catalogos.productos p
												WHERE
													c.id_producto = p.id_producto
													and c.id_catastro = dc.id_catastro
													and c.id_area = '$idArea'
													and c.id_producto IN $idProducto
													and c.id_tipo_operacion = '$operacion'
													and c.unidad_comercial = '$unidadComercial'
													and ($lote is NULL or c.numero_lote = $lote)
													and	NOT EXISTS (SELECT 
																		di.identificador 
																	FROM
																		g_vacunacion.vacunacion v
																		, g_vacunacion.detalle_vacunacion dv
																		, g_vacunacion.detalle_identificadores di 
																	WHERE 
																		v.id_vacunacion = dv.id_vacunacion
																		and di.id_detalle_vacunacion = dv.id_detalle_vacunacion 
																		and v.estado = 'vigente' 
																		and di.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto) --case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end
																		and dv.id_tipo_operacion = c.id_tipo_operacion 
																		and dv.unidad_comercial = c.unidad_comercial  
																		and c.id_especie = v.id_especie)
													) as productosRPIP
											WHERE
												row_to_json(productosRPIP) ->>'identif' NOT IN $identificadoresProducto 
											ORDER BY 
												row_to_json(productosRPIP) ->>'identif' ASC;");
				return $res;
	}


	public function filtrarTecnicoVacunador($conexion, $identificacionVacunador, $nombreVacunador){
		$identificacionVacunador = $identificacionVacunador!="" ? "'" . $identificacionVacunador . "'" : "NULL";
		$nombreVacunador = $nombreVacunador!="" ? "'%" . $nombreVacunador . "%'" : "NULL";
		$res = $conexion->ejecutarConsulta("SELECT
												fe.identificador 
												,fe.nombre ||' '|| fe.apellido as nombres
											FROM
												g_uath.ficha_empleado fe
												,g_uath.datos_contrato dc
											WHERE
												fe.identificador=dc.identificador
												and dc.estado='1'
												and ($identificacionVacunador is NULL or fe.identificador = $identificacionVacunador)
												and ($nombreVacunador is NULL or fe.nombre ||' '|| fe.apellido ilike $nombreVacunador)
											ORDER BY nombres ASC;");
		return $res;
	}

	public function guardarVacunacion($conexion, $idSitio,$idEspecie,$identificadorAdministrador,$identificadorDistribuidor,$identificadorVacunador,
										$idLoteVacuna, $idTipoVacuna,$costoVacuna, $numeroCertificado, $fechaVacunacion,$fechaVencimiento,$usuarioResponsable,$estado, $observacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_vacunacion.vacunacion(id_sitio, id_especie, identificador_operador_vacunacion, identificador_distribuidor,
												identificador_vacunador, id_lote_vacuna, id_tipo_vacuna,costo_vacuna, numero_certificado, fecha_vacunacion,fecha_vencimiento,usuario_responsable,estado,observacion )
											VALUES
												('$idSitio','$idEspecie','$identificadorAdministrador','$identificadorDistribuidor','$identificadorVacunador',
												'$idLoteVacuna', '$idTipoVacuna','$costoVacuna', '$numeroCertificado', '$fechaVacunacion','$fechaVencimiento','$usuarioResponsable','$estado','$observacion')  RETURNING id_vacunacion ;");						
		return $res;
	}

	public function guardarDetalleVacunacion($conexion,$idVacunacion, $idArea,$idProducto,$operacion,$cantidad,$idUnidadComercial,$numeroLote){
		$numeroLote = $numeroLote!="" ? "'".$numeroLote."'" : "null";
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_vacunacion.detalle_vacunacion(id_vacunacion,id_area, id_producto, id_tipo_operacion, cantidad, unidad_comercial, numero_lote )
											VALUES
												('$idVacunacion', '$idArea','$idProducto','$operacion','$cantidad','$idUnidadComercial',$numeroLote)  RETURNING id_detalle_vacunacion ;");
		return $res;
	}

	public function guardarDetalleIdentificadores($conexion,$idDetalleVacunacion, $identificador){
	    
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_vacunacion.detalle_identificadores(id_detalle_vacunacion,identificador)
											VALUES
												('$idDetalleVacunacion', '$identificador') ");
		return $res;
	}

	public function filtroVacunacionIndividual($conexion, $identificadorOperador,  $nombreSitio, $identificadorDigitador, $numeroCertificado, $fechaInicio, $fechaFin, $usuario,$identificadorProducto){
		$identificadorOperador  = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "NULL";
		$nombreSitio = $nombreSitio!="" ? "'%" . $nombreSitio . "%'" : "NULL";
		$identificadorDigitador = $identificadorDigitador!="" ? "'" . $identificadorDigitador . "'" : "NULL";
		$numeroCertificado = $numeroCertificado!="" ? "'" . $numeroCertificado . "'"  : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'"  : "NULL";

		if($fechaFin!=""){
			$fechaFin = str_replace("/","-",$fechaFin);
			$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
			$fechaFin=date('d-m-Y',$fechaFin);
			$fechaFin="'" .$fechaFin. "'";
		}else{
			$fechaFin="NULL";
		}

		if($identificadorProducto!=""){
			$busquedaTabla=" ,g_vacunacion.detalle_vacunacion dv ,g_vacunacion.detalle_identificadores di";
			$busquedaBusqueda=" and v.id_vacunacion=dv.id_vacunacion and dv.id_detalle_vacunacion=di.id_detalle_vacunacion and di.identificador='$identificadorProducto'";
		}else{
			$identificadorProducto="NULL";
		}
		
		if(($identificadorOperador=="NULL") && ($nombreSitio=="NULL") && ($identificadorDigitador=="NULL") && ($numeroCertificado=="NULL") && ($fechaInicio=="NULL") && ($fechaFin=="NULL")&& ($identificadorProducto=="NULL")){
			$busqueda = " and v.fecha_registro >= current_date and v.fecha_registro < current_date+1
			and v.usuario_responsable='".$usuario."'";
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												v.id_vacunacion
												,v.numero_certificado
												,o.identificador
												,(SELECT case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end) nombre_operador
												,s.nombre_lugar nombre_sitio
												,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
												,v.estado
											FROM
												g_vacunacion.vacunacion v
												,g_operadores.sitios s
												,g_operadores.operadores o
												".$busquedaTabla."
											WHERE
												s.id_sitio = v.id_sitio
												and o.identificador=s.identificador_operador
												and ($identificadorDigitador is NULL or (SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT rsv.identificador FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador  )
												else (SELECT ore.identificador FROM g_operadores.operadores ore WHERE v.usuario_responsable = ore.identificador   ) end
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.usuario_responsable)=$identificadorDigitador)
												and ($identificadorOperador is NULL or s.identificador_operador = $identificadorOperador)
												and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio)
												and ($numeroCertificado is NULL or v.numero_certificado = $numeroCertificado )		
												and ($fechaInicio is NULL or v.fecha_registro >=$fechaInicio)
												and ($fechaFin is NULL or v.fecha_registro <=$fechaFin )
												".$busqueda."
												".$busquedaBusqueda."
											ORDER by v.id_vacunacion DESC;");
		return $res;
	}

	public function abrirDetalleVacunacion($conexion, $idVacunacion){
		$res = $conexion->ejecutarConsulta("SELECT
												dv.id_detalle_vacunacion
												, ar.nombre_area area
												, ar.id_area
												, pr.id_producto
												, top.id_tipo_operacion
												, un.id_unidad_medida id_unidad_comercial
												, pr.nombre_comun producto
												, sp.nombre producto_subtipo
												, dv.cantidad
												, dv.numero_lote
												, un.nombre unidad_comercial
												, top.nombre tipo_operacion
											FROM
												g_vacunacion.detalle_vacunacion dv
												, g_operadores.areas ar
												, g_catalogos.productos pr
												, g_catalogos.subtipo_productos sp
												, g_catalogos.unidades_medidas un
												, g_catalogos.tipos_operacion top
											WHERE
												dv.id_area=ar.id_area
												and dv.id_producto=pr.id_producto
												and pr.id_subtipo_producto=sp.id_subtipo_producto
												and dv.unidad_comercial=un.id_unidad_medida
												and dv.id_tipo_operacion=top.id_tipo_operacion
												and dv.id_vacunacion='".$idVacunacion."';");
		return $res;
	}

	public function abrirDetalleVacunacionIdentificadores($conexion, $idDetalleVacunacion){
		$res = $conexion->ejecutarConsulta("SELECT
												id_detalle_vacunacion, identificador
											FROM 
												g_vacunacion.detalle_identificadores 
											WHERE id_detalle_vacunacion='".$idDetalleVacunacion."';");
		return $res;
	}
	
	public function abrirVacunacion($conexion, $idVacunacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												v.id_vacunacion
												,s.nombre_lugar nombre_sitio
												,v.id_especie
												,(SELECT nombre FROM g_catalogos.especies WHERE id_especies=v.id_especie) nombre_especie
												,v.identificador_operador_vacunacion
												,v.identificador_vacunador
												,v.identificador_distribuidor
												,op.identificador identificador_operador
												,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_operador
												,(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT ''::text AS nombre_administrador  ) else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end nombre_administradorr
												FROM g_operadores.operadores oa WHERE v.identificador_operador_vacunacion = oa.identificador   ) end nombre_administrador	FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
												WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.identificador_operador_vacunacion)
												,(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.identificador_distribuidor = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end	FROM g_operadores.operadores oa WHERE v.identificador_distribuidor = oa.identificador   ) end nombre_distribuidor
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.identificador_distribuidor)
												,(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.identificador_vacunador = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end	FROM g_operadores.operadores oa WHERE v.identificador_vacunador = oa.identificador   ) end nombre_vacunador
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.identificador_vacunador)
												,v.id_lote_vacuna
												,l.numero_lote
												,l.id_laboratorio
												,t.nombre_laboratorio
												,v.id_tipo_vacuna
												,tv.nombre_vacuna
												,v.numero_certificado
												,v.usuario_responsable
												,(SELECT sum(dc.cantidad) FROM g_vacunacion.detalle_vacunacion dc WHERE v.id_vacunacion=dc.id_vacunacion group by v.id_vacunacion) total_vacunado,
												v.costo_vacuna::decimal(10,2),
												(SELECT sum(dc.cantidad)*v.costo_vacuna::decimal(10,2) FROM g_vacunacion.detalle_vacunacion dc WHERE v.id_vacunacion=dc.id_vacunacion group by v.id_vacunacion) costo_total_vacuna
												,to_char(v.fecha_registro,'DD/MM/YYYY') fecha_registro
												,to_char(v.fecha_vacunacion,'DD/MM/YYYY')::date fecha_vacunacion
												,to_char(v.fecha_vencimiento,'DD/MM/YYYY')::date fecha_vencimiento
												,v.estado
											FROM 
												g_vacunacion.vacunacion v
												,g_operadores.operadores op
												,g_operadores.sitios s
												,g_catalogos.lotes l
												,g_catalogos.laboratorios t
												,g_catalogos.tipo_vacunas tv
											WHERE
												s.id_sitio = v.id_sitio
												and s.identificador_operador=op.identificador
												and v.id_lote_vacuna = l.id_lote
												and l.id_laboratorio = t.id_laboratorio
												and v.id_tipo_vacuna = tv.id_tipo_vacuna
												and v.id_vacunacion = '".$idVacunacion."';");
		return $res;
	}

	public function actualizarEstadoCertificadoVacunacion($conexion, $idEspecie, $numeroDocumento, $estado, $observacion,$usuarioModificacion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.certificados_vacunacion
											SET
												estado = '".$estado."'
												,observacion = '".$observacion."'
												,fecha_modificacion = now()
												,usuario_modificacion= '".$usuarioModificacion."'
											WHERE
												id_especie = '".$idEspecie."' and
												numero_documento = '".$numeroDocumento."';");
		return $res;
	}
	
	public function actualizarEstadoCertificadoAnulado($conexion, $idSerieDocumento, $estado, $observacion, $usuarioModificacion, $idProvincia){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.certificados_vacunacion
											SET
												estado = '".$estado."',
												observacion = '".$observacion."',
												fecha_modificacion = now(),
												usuario_modificacion='".$usuarioModificacion."',
                                                id_provincia = $idProvincia
											WHERE
												id_certificado_vacunacion = '".$idSerieDocumento."';");
		return $res;
	}

	public function listaEspeciesXvacunacion($conexion,$estadoVacunacion){
		$res = $conexion->ejecutarConsulta("SELECT
												id_especies,
												nombre,
												estado,
												codigo
											FROM 
												g_catalogos.especies
											WHERE
												estado_vacunacion='".$estadoVacunacion."' and
												estado = 'activo';");
		return $res;
	}

	public function actualizarVacunacion($conexion,$idVacunacion, $identificadorDistribuidor, $identificadorVacunador, $idTipoVacuna, $idLoteVacuna,$fechaVacunacion, $fechaVencimiento,$usuarioModificacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vacunacion.vacunacion
											SET
												identificador_distribuidor='".$identificadorDistribuidor."'
												,identificador_vacunador='".$identificadorVacunador."'
												,id_tipo_vacuna='".$idTipoVacuna."'
												,id_lote_vacuna='".$idLoteVacuna."'
												,fecha_vacunacion='".$fechaVacunacion."',
												fecha_vencimiento='".$fechaVencimiento."',
												usuario_modificacion='".$usuarioModificacion."',
												fecha_modificacion=now()
											WHERE 
												id_vacunacion='".$idVacunacion."';");
		return $res;
	}

	public function buscarProductosNoActosVacunacion($conexion, $tipoRequisito){
		$res = $conexion->ejecutarConsulta("SELECT
												id_producto
											FROM
												g_catalogos.requisitos_movilizacion_vacunacion
											WHERE
												estado='activo'
												and tipo='".$tipoRequisito."';");
		return $res;
	}
		

		
	public function listarTecnicosDistribuidores($conexion) {
		$res = $conexion->ejecutarConsulta ("SELECT
												fe.identificador
												,upper((fe.nombre::text || ' '::text) || fe.apellido::text) nombres
												,dc.provincia
											FROM
												g_vacunacion.tecnico_distribuidor td
												, g_uath.ficha_empleado fe
												, g_uath.datos_contrato dc
											WHERE
												td.identificador=fe.identificador
												and fe.identificador=dc.identificador
												and dc.estado=1
												and td.estado='activo'
											ORDER BY fe.nombre ASC;");
		return $res;
	}
		
	public function listarTecnicosVacunadores($conexion) {
		$res = $conexion->ejecutarConsulta ("SELECT
												fe.identificador 
												,upper((fe.nombre::text || ' '::text) || fe.apellido::text) nombres
												,lo.nombre provincia
											FROM
												g_uath.ficha_empleado fe
												,g_uath.datos_contrato dc 
												,g_catalogos.localizacion lo
											WHERE
												fe.identificador=dc.identificador
												and fe.id_localizacion_provincia=lo.id_localizacion
												and dc.estado='1'
											ORDER BY nombres ASC;");
		return $res;
	}
		


	public function consultarCertificadosVacunacionACaducar($conexion, $estado){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_vacunacion,
												numero_certificado
  											FROM 
												g_vacunacion.vacunacion
											WHERE 
												estado='".$estado."'
												and fecha_vencimiento<=current_date;");
		return $res;
	}

	public function listarOperacionesXoperadorYsitio($conexion,$idSitio){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												top.id_tipo_operacion, top.nombre
											FROM
												g_operadores.operaciones o,
												g_catalogos.tipos_operacion top,
												g_operadores.productos_areas_operacion pao,
												g_catastro.catastros c,
												g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and dc.estado_registro in ('activo', 'inactivo')
												and c.id_area=pao.id_area
												and c.id_tipo_operacion=top.id_tipo_operacion
												and c.id_area=pao.id_area 
												and pao.id_operacion=o.id_operacion
												and o.estado='registrado'
												and c.id_sitio='".$idSitio."'
												and top.id_area='SA'
											ORDER BY top.nombre ASC");
		return $res;
	}

	public function listaEmpresas($conexion , $idEmpresa=NULL) {
		$idEmpresa = $idEmpresa!=NULL ? "'" . $idEmpresa . "'" : "NULL";
		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
												em.identificador
												,em.id_empresa
												,case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_empresa
											FROM
												g_usuario.empresas em
												,g_operadores.operadores o
												,g_operadores.sitios s
												,g_operadores.operaciones op
												,g_catalogos.tipos_operacion t
												,g_operadores.areas a
												,g_operadores.productos_areas_operacion pao
											WHERE
												em.identificador=o.identificador
												and em.estado='activo'
												and em.identificador = op.identificador_operador
												and s.identificador_operador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and s.id_sitio = a.id_sitio
												and pao.id_area = a.id_area
												and pao.id_operacion=op.id_operacion
												and op.estado = 'registrado'
												and t.codigo in ('OPT','OPI')
												and ($idEmpresa is NULL or em.identificador = $idEmpresa)
												and (SELECT count(eml.id_empleado) FROM g_usuario.empleados eml where eml.id_empresa=em.id_empresa)>0
												and t.id_area='SA'
											ORDER BY nombre_empresa ASC;");
		return $res;
	}

	public function buscarEmpresasXidentificador($conexion,$identificadorEmpresa) {
		$res = $conexion->ejecutarConsulta ("SELECT
												em.id_empresa
												,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_empresa
												,em.tipo tipo_empresa
											FROM
												g_usuario.empresas em
												,g_operadores.operadores op
											WHERE
												em.identificador=op.identificador
												and em.identificador='".$identificadorEmpresa."'
												and em.estado='activo';");
		return $res;
	}
	
	function listarDistribuidoresEmpresa($conexion, $idEmpresa=NULL){
		$idEmpresa = $idEmpresa!=NULL ? "'" . $idEmpresa . "'" : "NULL";
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												opv.identificador
												,case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
												,em.id_empresa
												,em.estado
											FROM
												g_usuario.empleados em
												,g_usuario.empresas emp
												,g_operadores.operadores opv
												,g_operadores.operaciones op
												,g_catalogos.tipos_operacion t
												,g_operadores.productos_areas_operacion pao
												,g_operadores.areas a
												,g_operadores.sitios s
											WHERE
												s.identificador_operador=opv.identificador
												and s.id_sitio=a.id_sitio
												and pao.id_area=a.id_area
												and pao.id_operacion=op.id_operacion
												and	em.id_empresa=emp.id_empresa
												and opv.identificador=em.identificador
												and opv.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.codigo = 'DIS'
												and t.id_area = 'SA'
												and op.estado='registrado'
												and emp.estado='activo'
												and ($idEmpresa is NULL or emp.id_empresa = $idEmpresa)
											ORDER BY nombres ASC;");
		return $res;
	}
	
	function listarVacunadoresEmpresa($conexion, $idEmpresa=NULL){
		$idEmpresa = $idEmpresa!=NULL ? "'" . $idEmpresa . "'" : "NULL";
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												opv.identificador
												,case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
												,em.id_empresa
												,em.estado
											FROM
												 g_usuario.empleados em
												,g_usuario.empresas emp
												,g_operadores.operadores opv
												,g_operadores.operaciones op
												,g_catalogos.tipos_operacion t
												,g_operadores.productos_areas_operacion pao
												,g_operadores.areas a
												,g_operadores.sitios s
											WHERE
												s.identificador_operador=opv.identificador
												and s.id_sitio=a.id_sitio
												and pao.id_area=a.id_area
												and pao.id_operacion=op.id_operacion
												and	em.id_empresa=emp.id_empresa
												and opv.identificador=em.identificador
												and opv.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.codigo = 'VAC'
												and t.id_area = 'SA'
												and op.estado='registrado'
												and emp.estado='activo'
												and ($idEmpresa is NULL or emp.id_empresa = $idEmpresa)
											ORDER BY nombres ASC;");
		return $res;
	}
		
	public function imprimirReporteVacunacionUsuarioExterno($conexion, $identificadorEmpresa,$identificadorDistribuidor,$identificadorVacunador,$provincia, $fechaInicio, $fechaFin, $estado){
		if ($estado=="todos")
			$estado ="";
	
		$busqueda = '';
		$provincia = $provincia!="todos" ? "'" . $provincia . "'" : "NULL";
		$estado = $estado!="" ? "'" . $estado  . "'" : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'"  : "NULL";
	
		if($fechaFin!=""){
			$fechaFin = str_replace("/","-",$fechaFin);
			$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
			$fechaFin=date('d-m-Y',$fechaFin);
			$fechaFin="'" .$fechaFin. "'";
		}else{
			$fechaFin="NULL";
		}
	
		
		if($identificadorEmpresa=="OPT" || $identificadorEmpresa=="OPI" ){

		$res = $conexion->ejecutarConsulta("SELECT 
												tabla1.* 
											FROM 
												(SELECT
													upper(s.nombre_lugar::text) AS nombre_sitio,
													s.provincia,
													s.canton,
													s.parroquia,
													CASE WHEN oa.razon_social::text = ''::text THEN upper((oa.nombre_representante::text || ' '::text) || oa.apellido_representante::text)::character varying::text
													ELSE upper(oa.razon_social::text)END AS nombre_administrador,
													CASE WHEN od.razon_social::text = ''::text THEN upper((od.nombre_representante::text || ' '::text) || od.apellido_representante::text)::character varying::text
													ELSE upper(od.razon_social::text) END AS nombre_distribuidor,
													upper((ov.nombre_representante::text || ' '::text) || ov.apellido_representante::text) AS nombre_vacunador,
													upper((rs.nombre_representante::text || ' '::text) || rs.apellido_representante::text) AS nombre_responsable,
													upper((pp.nombre_representante::text || ' '::text) || pp.apellido_representante::text) AS nombre_propietario,
													tv.nombre_vacuna,
													t.nombre_laboratorio,
													l.numero_lote,
													v.numero_certificado,
													pp.identificador identificador_propietario,
													sum(dc.cantidad) total_vacunado,
													to_char(v.fecha_registro,'dd/mm/yyyy HH24:mi:ss') fecha_registro,
													to_char(v.fecha_vacunacion,'dd/mm/yyyy') fecha_vacunacion,
													v.id_vacunacion,
                                                    CASE WHEN (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) >= 1 and (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) <=15 THEN 'PROXIMO A CADUCAR' ELSE v.estado END as estado
												FROM
													g_operadores.operadores pp,
													g_catalogos.laboratorios t,
													g_vacunacion.vacunacion v
														FULL OUTER JOIN g_operadores.operadores as oa ON v.identificador_operador_vacunacion = oa.identificador
														FULL OUTER JOIN g_operadores.operadores as od ON v.identificador_distribuidor = od.identificador
														FULL OUTER JOIN g_operadores.operadores as ov ON v.identificador_vacunador = ov.identificador
														FULL OUTER JOIN g_operadores.operadores as rs ON v.usuario_responsable = rs.identificador
														FULL OUTER JOIN g_operadores.sitios as s ON v.id_sitio = s.id_sitio
														FULL OUTER JOIN g_catalogos.lotes as l ON v.id_lote_vacuna = l.id_lote
														FULL OUTER JOIN g_catalogos.tipo_vacunas as tv ON v.id_tipo_vacuna = tv.id_tipo_vacuna
														FULL OUTER JOIN g_vacunacion.detalle_vacunacion as dc ON v.id_vacunacion = dc.id_vacunacion	
												WHERE
												s.identificador_operador = pp.identificador
												and l.id_laboratorio = t.id_laboratorio 
											
												and ($provincia is NULL or s.provincia = $provincia)
												and ($estado is NULL or v.estado = $estado)
												and ($fechaInicio is NULL or v.fecha_registro >=$fechaInicio)
												and ($fechaFin is NULL or v.fecha_registro <=$fechaFin )
												 
												GROUP BY  1,2,3,4,5,6,7,8,9,10,11,12,13,14,16,17,18) as tabla1,
												(SELECT  distinct(vv.id_vacunacion) FROM 
													g_operadores.operaciones op
													INNER JOIN  g_catalogos.tipos_operacion AS t  ON t.id_tipo_operacion=op.id_tipo_operacion 
													INNER JOIN  g_vacunacion.vacunacion vv  ON vv.identificador_operador_vacunacion = op.identificador_operador
												WHERE 
													t.codigo ='$identificadorEmpresa' and t.id_area='SA'
				) as tabla2
											WHERE 
												tabla1.id_vacunacion=tabla2.id_vacunacion;");
		}else{
			$busqueda = "and v.identificador_operador_vacunacion = '".$identificadorEmpresa."'";
			if($identificadorDistribuidor!="todos")
				$busqueda.= "and v.identificador_distribuidor='".$identificadorDistribuidor."'";
			if($identificadorVacunador!="todos")
				$busqueda.= "and v.identificador_vacunador='".$identificadorVacunador."'";

			$res = $conexion->ejecutarConsulta("SELECT
												upper(s.nombre_lugar::text) AS nombre_sitio,
												s.provincia,
												s.canton,
												s.parroquia,
												CASE WHEN oa.razon_social::text = ''::text THEN upper((oa.nombre_representante::text || ' '::text) || oa.apellido_representante::text)::character varying::text
												ELSE upper(oa.razon_social::text)END AS nombre_administrador,
												CASE WHEN od.razon_social::text = ''::text THEN upper((od.nombre_representante::text || ' '::text) || od.apellido_representante::text)::character varying::text
												ELSE upper(od.razon_social::text) END AS nombre_distribuidor,
												upper((ov.nombre_representante::text || ' '::text) || ov.apellido_representante::text) AS nombre_vacunador,
												upper((rs.nombre_representante::text || ' '::text) || rs.apellido_representante::text) AS nombre_responsable,
												upper((pp.nombre_representante::text || ' '::text) || pp.apellido_representante::text) AS nombre_propietario,
												tv.nombre_vacuna,
												t.nombre_laboratorio,
												l.numero_lote,
												v.numero_certificado,
												pp.identificador identificador_propietario,
												sum(dc.cantidad) total_vacunado,
												(to_char(v.fecha_registro,'dd/mm/yyyy HH24:mi')) fecha_registro,
												(to_char(v.fecha_vacunacion,'dd/mm/yyyy')) fecha_vacunacion,
												v.id_vacunacion,
                                                CASE WHEN (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) >= 1 and (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) <=15 THEN 'PROXIMO A CADUCAR' ELSE v.estado END as estado
											FROM
												g_operadores.operadores pp,
												g_catalogos.laboratorios t,
												g_vacunacion.vacunacion v
													FULL OUTER JOIN g_operadores.operadores as oa ON v.identificador_operador_vacunacion = oa.identificador
													FULL OUTER JOIN g_operadores.operadores as od ON v.identificador_distribuidor = od.identificador
													FULL OUTER JOIN g_operadores.operadores as ov ON v.identificador_vacunador = ov.identificador
													FULL OUTER JOIN g_operadores.operadores as rs ON v.usuario_responsable = rs.identificador
													FULL OUTER JOIN g_operadores.sitios as s ON v.id_sitio = s.id_sitio
													FULL OUTER JOIN g_catalogos.lotes as l ON v.id_lote_vacuna = l.id_lote
													FULL OUTER JOIN g_catalogos.tipo_vacunas as tv ON v.id_tipo_vacuna = tv.id_tipo_vacuna
													FULL OUTER JOIN g_vacunacion.detalle_vacunacion as dc ON v.id_vacunacion = dc.id_vacunacion
											WHERE
											s.identificador_operador = pp.identificador
											and l.id_laboratorio = t.id_laboratorio
												".$busqueda."
											and ($provincia is NULL or s.provincia = $provincia)
											and ($estado is NULL or v.estado = $estado)
											and ($fechaInicio is NULL or v.fecha_registro >=$fechaInicio)
											and ($fechaFin is NULL or v.fecha_registro <=$fechaFin )
											group  by 1,2,3,4,5,6,7,8,9,10,11,12,13,14,16,17,18 ;");
			
		}	
		
				return $res;
	}

	public function imprimirReporteVacunacionUsuarioInterno($conexion,$identificadorDistribuidor,$identificadorVacunador,$provincia, $fechaInicio, $fechaFin, $estado){
		if ($estado=="todos")
			$estado = "";
		
		$busqueda = '';
		$provincia = $provincia!="todos" ? "'" . $provincia . "'" : "NULL";
		$estado = $estado!="" ? "'" . $estado  . "'" : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'"  : "NULL";
		
		if($fechaFin!=""){
			$fechaFin = str_replace("/","-",$fechaFin);
			$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
			$fechaFin=date('d-m-Y',$fechaFin);
			$fechaFin="'" .$fechaFin. "'";
		}else{
			$fechaFin="NULL";
		}
		
		if($identificadorDistribuidor=="todos"){
			$busqueda = "";
		}else{
			$busqueda = " and v.identificador_distribuidor='$identificadorDistribuidor'";
			if($identificadorVacunador=="todos"){
				$busqueda .= "";
			}else{
				$busqueda .= " and v.identificador_vacunador='$identificadorVacunador'";
			}
		}
	
		$res = $conexion->ejecutarConsulta("SELECT 
												upper(s.nombre_lugar::text) AS nombre_sitio,
												s.provincia,
												s.canton,
												s.parroquia,
												upper((rsd.nombre::text || ' '::text) || rsd.apellido::text) AS nombre_distribuidor,
												upper((rsv.nombre::text || ' '::text) || rsv.apellido::text) AS nombre_vacunador,
												upper((rsr.nombre::text || ' '::text) || rsr.apellido::text) AS nombre_responsable,
												upper((pp.nombre_representante::text || ' '::text) || pp.apellido_representante::text) AS nombre_propietario,
												tv.nombre_vacuna,
												t.nombre_laboratorio,
												l.numero_lote,
												v.numero_certificado,
												pp.identificador identificador_propietario,
												(SELECT sum(cantidad) FROM g_vacunacion.detalle_vacunacion dc WHERE v.id_vacunacion=dc.id_vacunacion group by v.id_vacunacion) total_vacunado,
												(to_char(v.fecha_registro,'dd/mm/yyyy HH24:mi:ss')) fecha_registro,
												(to_char(v.fecha_vacunacion,'dd/mm/yyyy')) fecha_vacunacion,
												 v.id_vacunacion,
                                                CASE WHEN (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) >= 1 and (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) <=15 THEN 'PROXIMO A CADUCAR' ELSE v.estado END as estado
											FROM
												g_vacunacion.vacunacion v,
												g_uath.ficha_empleado rsd,
												g_uath.ficha_empleado rsr,
												g_uath.ficha_empleado rsv,
												g_operadores.sitios s,
												g_operadores.operadores pp,
												g_catalogos.lotes l,
												g_catalogos.laboratorios t,
												g_catalogos.tipo_vacunas tv
												
											WHERE
												v.identificador_distribuidor::text = rsd.identificador::text
												and v.identificador_vacunador::text = rsv.identificador::text
												and v.usuario_responsable::text = rsr.identificador::text
												and s.id_sitio = v.id_sitio
												and s.identificador_operador::text = pp.identificador::text
												and v.id_lote_vacuna = l.id_lote 
												and l.id_laboratorio = t.id_laboratorio
												and v.id_tipo_vacuna = tv.id_tipo_vacuna 
												".$busqueda."
												and ($provincia is NULL or s.provincia = $provincia)
												and ($estado is NULL or v.estado = $estado)
												and ($fechaInicio is NULL or v.fecha_registro >=$fechaInicio)
												and ($fechaFin is NULL or v.fecha_registro <=$fechaFin )
											ORDER BY s.provincia ASC;");
		return $res;
	}

	public function buscarEmpleadoEmpresaRol($conexion,  $idEmpresa,$identificadorEmpleado,$nombresEmpleado){
		$identificadorEmpleado = $identificadorEmpleado!="" ? "'" . $identificadorEmpleado . "'" : "NULL";
		$nombresEmpleado = $nombresEmpleado!="" ? "'%" . $nombresEmpleado  . "%'" : "NULL";
		
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												em.id_empleado
												,opv.identificador
												,case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
											FROM
												g_usuario.empleados em
												,g_usuario.empresas emp
												,g_operadores.operadores opv
											WHERE
												em.id_empresa=emp.id_empresa
												and opv.identificador=em.identificador
												and ($identificadorEmpleado is NULL or em.identificador = $identificadorEmpleado)
												and ($nombresEmpleado is NULL or case when opv.razon_social = '' then coalesce(opv.nombre_representante ||' '|| opv.apellido_representante ) else opv.razon_social end ilike $nombresEmpleado)
												and emp.id_empresa='".$idEmpresa."'
											ORDER BY nombres ASC;");					
		return $res;	
	}

	public function consultarRolEmpleado($conexion, $rol, $idEmpleado){
		$res = $conexion->ejecutarConsulta("SELECT
												id_empleado, tipo, estado
											FROM
												g_usuario.roles_empleados
											WHERE
												id_empleado='".$idEmpleado."'
												and tipo='".$rol."';");
		return $res;
	}

	public function guardarNuevoRolEmpleado($conexion, $rol, $idEmpleado, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO	
												g_usuario.roles_empleados(id_empleado, tipo, estado)
											VALUES 
												('$idEmpleado', '$rol', '$estado');");
		return $res;
	}

	function listaRolEmpleadoEmpresa($conexion,$identificacionEmpleado,$nombresEmpleado,$operadorVacunacion,$tipoUsuario,$usuario){
		$identificacionEmpleado  = $identificacionEmpleado!="" ? "'" . $identificacionEmpleado . "'" : "NULL";
		$nombresEmpleado = $nombresEmpleado!="" ? "'%" . $nombresEmpleado . "%'" : "NULL";
		$operadorVacunacion = $operadorVacunacion!="" ? "'" . $operadorVacunacion . "'"  : "NULL";
		
		if($tipoUsuario=='PFL_USUAR_INT'){
			if(($identificacionEmpleado=="NULL") && ($nombresEmpleado=="NULL") && ($operadorVacunacion=="NULL")){
				$busqueda0 = " LIMIT 100";
			}
		}
		
		if($tipoUsuario=='PFL_USUAR_EXT'){
			$busqueda1 = " and emp.identificador='$usuario' ";
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												re.id_rol_empleado
												,case when ope.razon_social = '' then ope.nombre_representante ||' '|| ope.apellido_representante else ope.razon_social end operador_vacunacion
												,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end empleado
												,re.tipo
												,re.estado
											FROM
												g_usuario.empleados em , g_usuario.roles_empleados re, g_operadores.operadores op, g_operadores.operadores ope, g_usuario.empresas emp
											WHERE
												em.id_empleado=re.id_empleado
												and em.identificador=op.identificador
												and emp.id_empresa=em.id_empresa
												and emp.identificador=ope.identificador
												and emp.estado='activo'
												and em.estado='activo'
												and re.tipo='digitadorVacunacion'
												".$busqueda1." 
												and ($identificacionEmpleado is NULL or em.identificador = $identificacionEmpleado)
												and ($nombresEmpleado is NULL or case when op.razon_social = '' then coalesce(op.nombre_representante ||' '|| op.apellido_representante ) else op.razon_social end ilike $nombresEmpleado)
												and ($operadorVacunacion is NULL or emp.id_empresa = $operadorVacunacion)
											ORDER BY 1 DESC
												".$busqueda0." 
							;");
				return $res;
	}

	public function abrirRolEmpleado($conexion, $idRolEmpleado){
		$res = $conexion->ejecutarConsulta("SELECT
												re.id_rol_empleado
												,ope.identificador identificador_operador_vacunacion
												,op.identificador identificador_empleado
												,case when ope.razon_social = '' then ope.nombre_representante ||' '|| ope.apellido_representante else ope.razon_social end operador_vacunacion
												,case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end empleado
												,re.tipo
												,re.estado
												,re.usuario_modificacion
												,to_char(re.fecha_modificacion,'yyyy-mm-dd hh24:mi') fecha_modificacion
											FROM
												g_usuario.empleados em , g_usuario.roles_empleados re, g_operadores.operadores op, g_operadores.operadores ope, g_usuario.empresas emp
											WHERE
												em.id_empleado=re.id_empleado and em.identificador=op.identificador and emp.id_empresa=em.id_empresa and emp.identificador=ope.identificador and re.id_rol_empleado='$idRolEmpleado';");
		return $res;
	}
	public function actualizarRolEmpleado($conexion, $idRolEmpleado, $estado,$usuarioModificacion){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_usuario.roles_empleados
											SET
												estado = '$estado',
												usuario_modificacion='".$usuarioModificacion."',
												fecha_modificacion=now()
											WHERE
												id_rol_empleado = '".$idRolEmpleado."' ;");
		return $res;
	}

	public function listaAnularVacunacion($conexion, $identificadorOperador,  $nombreSitio, $identificadorDigitador, $numeroCertificado, $fechaInicio, $fechaFin, $usuario){
		$identificadorOperador  = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "NULL";
		$nombreSitio = $nombreSitio!="" ? "'%" . $nombreSitio . "%'" : "NULL";
		$identificadorDigitador = $identificadorDigitador!="" ? "'" . $identificadorDigitador . "'" : "NULL";
		$numeroCertificado = $numeroCertificado!="" ? "'" . $numeroCertificado . "'"  : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'"  : "NULL";

		if($fechaFin!=""){
			$fechaFin = str_replace("/","-",$fechaFin);
			$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
			$fechaFin=date('d-m-Y',$fechaFin);
			$fechaFin="'" .$fechaFin. "'";
		}else{
			$fechaFin="NULL";
		}
		$busqueda="";
		if(($identificadorOperador=="NULL") && ($nombreSitio=="NULL") && ($identificadorDigitador=="NULL") && ($numeroCertificado=="NULL") && ($fechaInicio=="NULL") && ($fechaFin=="NULL")){
			$busqueda = " and v.fecha_registro >= current_date and v.fecha_registro < current_date+1
			and (SELECT
			case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT rsv.identificador FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador  )
			else (SELECT ore.identificador FROM g_operadores.operadores ore WHERE v.usuario_responsable = ore.identificador   ) end
			FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
			WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.usuario_responsable)='$usuario'";
		}
				
		$res = $conexion->ejecutarConsulta("SELECT
												v.id_vacunacion
												,v.numero_certificado
												,o.identificador
												,(SELECT case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end) nombre_operador
												,s.nombre_lugar nombre_sitio
												,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
												,v.estado
												,s.provincia
											FROM
												g_vacunacion.vacunacion v
												,g_operadores.sitios s
												,g_operadores.operadores o
											WHERE
												s.id_sitio = v.id_sitio
												and o.identificador=s.identificador_operador
												and v.estado='vigente'
												and ($identificadorDigitador is NULL or (SELECT
												case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT rsv.identificador FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador  )
												else (SELECT ore.identificador FROM g_operadores.operadores ore WHERE v.usuario_responsable = ore.identificador   ) end
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
												WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=v.usuario_responsable)=$identificadorDigitador)
												and ($identificadorOperador is NULL or s.identificador_operador = $identificadorOperador)
												and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio)
												and ($numeroCertificado is NULL or v.numero_certificado = $numeroCertificado )
												and ($fechaInicio is NULL or  v.fecha_registro >= $fechaInicio)
												and ($fechaFin is NULL or  v.fecha_registro <= $fechaFin) 
												".$busqueda."
											ORDER BY 1 DESC	;");
		return $res;
	}

	public function buscarCertificadoAnuladoVacunacion($conexion, $idVacunaAnimal){
		$res = $conexion->ejecutarConsulta("SELECT
												numero_certificado
												, usuario_anulacion
												, motivo_anulacion
												, estado
												, to_char(fecha_anulacion,'DD/MM/YYYY') fecha_anulacion
											FROM
												g_vacunacion.vacunacion
											WHERE estado = 'anulado'
												and id_vacunacion = '".$idVacunaAnimal."';");
		return $res;
	}

	public function actualizarEstadoAnularVacunacion($conexion,$idVacunacion,  $motivoAnulacion, $estado, $usuarioAnulacion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vacunacion.vacunacion
											SET estado = '".$estado."'
												, motivo_anulacion = '".$motivoAnulacion."'
												, usuario_anulacion = '".$usuarioAnulacion."'
												, fecha_anulacion = 'now()'
											WHERE 
												id_vacunacion = '".$idVacunacion."'");
		return $res;
	}

	public function listaFiscalizacion($conexion, $identificadorOperador,$nombreOperador, $estadoFiscalizacion, $numeroCertificado,$fechaInicio,$fechaFin){
		$busqueda0 = '';
		$busqueda1 = '';
			
		if ($estadoFiscalizacion=="noFiscalizado"){
			$busqueda0 = " and v.estado_fiscalizacion is null";
			$estadoFiscalizacion="";
		}

		$identificadorOperador  = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "NULL";
		$nombreOperador = $nombreOperador!="" ? "'%" . $nombreOperador . "%'" : "NULL";
		$estadoFiscalizacion = $estadoFiscalizacion!="" ? "'" . $estadoFiscalizacion . "'" : "NULL";
		$numeroCertificado = $numeroCertificado!="" ? "'" . $numeroCertificado . "'"  : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'"  : "NULL";
		
		if($fechaFin!=""){
			$fechaFin = str_replace("/","-",$fechaFin);
			$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
			$fechaFin=date('d-m-Y',$fechaFin);
			$fechaFin="'" .$fechaFin. "'";
		}else{
			$fechaFin="NULL";
		}
		

		if(($identificadorOperador=="NULL") && ($nombreOperador=="NULL") && ($estadoFiscalizacion=="NULL") && ($numeroCertificado=="NULL") && ($fechaInicio=="NULL") && ($fechaFin=="NULL")){
			$busqueda1 = " and v.fecha_registro >= current_date and v.fecha_registro < current_date+1";
		}

		$res = $conexion->ejecutarConsulta("SELECT
												v.id_vacunacion
												,v.numero_certificado
												,s.provincia
												,s.nombre_lugar nombre_sitio
												,to_char(v.fecha_vacunacion,'DD/MM/YYYY') fecha_vacunacion
												,v.estado
											FROM
												g_vacunacion.vacunacion v
												,g_operadores.sitios s
												,g_operadores.operadores o
											WHERE
												s.id_sitio = v.id_sitio
												and o.identificador=s.identificador_operador
												and v.estado='vigente'
												and ($nombreOperador is NULL or case when o.razon_social = '' then coalesce(o.nombre_representante ||' '|| o.apellido_representante ) else o.razon_social end ilike $nombreOperador)
												and ($identificadorOperador is NULL or o.identificador = $identificadorOperador)
												and ($estadoFiscalizacion is NULL or v.estado_fiscalizacion = $estadoFiscalizacion)
												and ($numeroCertificado is NULL or v.numero_certificado = $numeroCertificado )
												and ($fechaInicio is NULL or  v.fecha_registro >= $fechaInicio)
												and ($fechaFin is NULL or  v.fecha_registro <= $fechaFin) 
												".$busqueda0."
												".$busqueda1."
											ORDER BY 1 DESC;");
		return $res;
	}
	
	public function abrirFiscalizacion($conexion, $idVacunaAnimal){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_vacunacion_fiscalizacion
												, id_vacunacion
												, numero_fiscalizacion
												, usuario_responsable
												, observacion
												, estado
												, fecha_registro
												, to_char(fecha_fiscalizacion,'DD/MM/YYYY') fecha_fiscalizacion
                                                , identificador_comerciante
								 			FROM 
												g_vacunacion.vacunacion_fiscalizaciones
											WHERE 
												id_vacunacion='".$idVacunaAnimal."';");
		return $res;
	}
	
	public function generarNumeroCertificadoFiscalizacion($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												max(numero_fiscalizacion)::numeric + 1 as numero
											FROM
												g_vacunacion.vacunacion_fiscalizaciones");
		if(pg_fetch_result($res, 0, 'numero') == ''){
			$res = 1;
		}else{
			$res = pg_fetch_result($res, 0, 'numero');
		}
		return $res;
	}
	
	public function guardarFiscalizacion($conexion, $idVacunacion, $numeroFiscalizacion, $usuarioResponsable, $observacionFiscalizacion, $estadoFiscalizacion, $fechaFiscalizacion, $identificadorComerciante){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_vacunacion.vacunacion_fiscalizaciones(id_vacunacion, numero_fiscalizacion, usuario_responsable,observacion, estado, fecha_registro, fecha_fiscalizacion, identificador_comerciante)
											VALUES ('$idVacunacion', '$numeroFiscalizacion', '$usuarioResponsable','$observacionFiscalizacion','$estadoFiscalizacion',now(),'$fechaFiscalizacion', '$identificadorComerciante')  RETURNING id_vacunacion_fiscalizacion");
		return $res;
	}
	
	public function actualizarEstadoVacunacionFiscalizacion($conexion, $idVacunacion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vacunacion.vacunacion
											SET 
												estado_fiscalizacion = 'fiscalizado'
											WHERE 
												id_vacunacion = '".$idVacunacion."';");
		return $res;
	}
	
	public function imprimirReporteFiscalizacionVacunacion($conexion, $provincia,$canton,$parroquia,$estado,$fechaInicio, $fechaFin){

		$provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";
		$canton = $canton!="" ? "'" . $canton . "'" : "NULL";
		$parroquia = $parroquia!="" ? "'" . $parroquia . "'" : "NULL";
		
		if ($estado=="todos")
			$estado = "";

		$estado = $estado!="" ? "'" . $estado . "'"  : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'"  : "NULL";
		
		if($fechaFin!=""){
			$fechaFin = str_replace("/","-",$fechaFin);
			$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
			$fechaFin=date('d-m-Y',$fechaFin);
			$fechaFin="'" .$fechaFin. "'";
		}else{
			$fechaFin="NULL";
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												v.numero_certificado
												,tv.nombre_vacuna tipo_vacunacion
												,f.usuario_responsable identificador_usuario_responsable
												,(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE f.usuario_responsable = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end FROM g_operadores.operadores oa WHERE f.usuario_responsable = oa.identificador ) end usuario_responsable
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=f.usuario_responsable)
												,v.identificador_operador_vacunacion identificador_operador_vacunacion 
												,(select case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end from g_operadores.operadores op where op.identificador=v.identificador_operador_vacunacion) operador_vacunacion
												,s.provincia
												,s.canton
												,s.parroquia
												,s.nombre_lugar nombre_sitio
												,o.identificador identificacion_propietario
												,o.nombre_representante || ' ' || o.apellido_representante nombre_propietario
												,f.estado estado_fiscalizacion
												,v.estado estado_vacunacion
												,f.observacion
												,to_char(f.fecha_registro,'yyyy-mm-dd HH24:mi:ss') fecha_registro
												,to_char(f.fecha_fiscalizacion,'yyyy-mm-dd') fecha_fiscalizacion
											FROM 
												g_vacunacion.vacunacion_fiscalizaciones f
												, g_vacunacion.vacunacion v
												, g_operadores.sitios s
												, g_operadores.operadores o
												, g_catalogos.tipo_vacunas tv
											WHERE 
												f.id_vacunacion=v.id_vacunacion 
												and s.id_sitio=v.id_sitio 
												and o.identificador=s.identificador_operador
												and tv.id_tipo_vacuna=v.id_tipo_vacuna
												and ($estado is NULL or f.estado = $estado)
												and ($provincia is NULL or s.provincia = $provincia)
												and ($canton is NULL or s.canton = $canton)
												and ($parroquia is NULL or s.parroquia = $parroquia)
												and ($fechaInicio is NULL or f.fecha_registro >=$fechaInicio) 
												and ($fechaFin is NULL or f.fecha_registro <=$fechaFin )
								 			ORDER BY s.provincia, f.numero_fiscalizacion DESC;");
		return $res;
	}
	
	public function eliminarDetalleVacunacionConIdentificadores ($conexion,$idDetalleVacuna){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_vacunacion.detalle_identificadores
											WHERE
												id_detalle_vacunacion = '".$idDetalleVacuna."';");
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_vacunacion.detalle_vacunacion
											WHERE
												id_detalle_vacunacion = '".$idDetalleVacuna."';");
		return $res;
	}
	
	public function eliminarFiscalizacionVacunacion($conexion, $idVacunacion){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_vacunacion.vacunacion_fiscalizaciones
											WHERE 
												id_vacunacion='".$idVacunacion."';");
		return $res;
	}

	public function eliminarVacunacion($conexion, $idVacunacion){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_vacunacion.vacunacion
											WHERE
												id_vacunacion = '".$idVacunacion."';");
		return $res;
	}
	
	public function listaCertificadoAanular($conexion, $idEspecie, $numeroCertificado){
		$idEspecie=$idEspecie!=""?$idEspecie:0;
		
		$res = $conexion->ejecutarConsulta("SELECT 
												id_certificado_vacunacion
												,(SELECT nombre	 FROM g_catalogos.especies where id_especies=id_especie) nombre_especie
												,numero_documento
												,estado
												,fecha_registro::date
											FROM 
												g_catalogos.certificados_vacunacion 
											WHERE  
												id_especie = $idEspecie
												and estado='creado'
												and numero_documento = '".$numeroCertificado."';");
		return $res;
	}
	
	public function buscarCertificadoVacunacion($conexion, $idSerieDocumento){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_certificado_vacunacion
												,(SELECT nombre FROM g_catalogos.especies where id_especies=id_especie) nombre_especie
												,numero_documento
												,estado
												,fecha_registro
												,fecha_modificacion
												,observacion
												,usuario_modificacion
											FROM 
												g_catalogos.certificados_vacunacion 
											WHERE
												id_certificado_vacunacion='".$idSerieDocumento."';");
		return $res;
	}
	
	public function actualizarEstadoVacunacion($conexion, $idVacunacion,$estado){
		$res = $conexion->ejecutarConsulta("UPDATE g_vacunacion.vacunacion
										   SET 
										       estado='$estado'
											 WHERE id_vacunacion='$idVacunacion';");
				return $res;
	}
	
	public function consultarCertificadoVacunacion($conexion, $numeroCertificado){
		$res = $conexion->ejecutarConsulta("SELECT numero_certificado
											FROM g_vacunacion.vacunacion
											WHERE numero_certificado='$numeroCertificado' and estado='vigente' ;");
		return $res;
	}

	public function listaUnidadComercialCatastro($conexion, $idProducto, $idOperacion,$idArea){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												um.id_unidad_medida, um.nombre nombre_unidad_medida
											FROM
												g_catastro.catastros c,
												g_catalogos.unidades_medidas um
											WHERE
												c.unidad_comercial=um.id_unidad_medida
												and c.id_producto ='$idProducto'
												and c.id_tipo_operacion='$idOperacion'
												and c.id_area = '$idArea';");
		return $res;
	}
	
	public function abrirDetalleVacunacionAgrupado($conexion, $idVacunacion){
		$res = $conexion->ejecutarConsulta("SELECT
 												sum(dv.cantidad) cantidad, pr.nombre_comun producto
												, dv.id_area
												, dv.id_producto
												, dv.id_tipo_operacion
												, dv.unidad_comercial
											FROM
												g_vacunacion.detalle_vacunacion dv,
												g_catalogos.productos pr
											WHERE
												dv.id_producto=pr.id_producto
												and dv.id_vacunacion='$idVacunacion' group by 2,3,4,5,6;");
		return $res;
	}
	
	public function verificarFechaRegistroIdentificadorMovilizacion($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												m.id_movilizacion,
												to_char(m.fecha_registro,'DD/MM/YYYY')::date fecha_registro
											FROM 
												g_movilizacion_producto.movilizacion m,
												g_movilizacion_producto.detalle_movilizacion dm,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim
											WHERE
												m.id_movilizacion=dm.id_movilizacion and 
												m.estado not in ('anulado') and
												dm.id_detalle_movilizacion=dim.id_detalle_movilizacion and 
												dim.identificador='$identificador' 
											ORDER BY 1 DESC 
											LIMIT 1; ");
		return $res;
	}
	
	public function verificarFechaRegistroIdentificadorVacunacionMovilizacion($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												m.id_movilizacion,
												to_char(m.fecha_registro,'DD/MM/YYYY')::date fecha_registro
											FROM 
												g_movilizacion_producto.movilizacion m,
												g_movilizacion_producto.detalle_movilizacion dm,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim,
												g_vacunacion.detalle_identificadores di
											WHERE
												m.id_movilizacion=dm.id_movilizacion and 
												m.estado not in ('anulado') and
												dm.id_detalle_movilizacion=dim.id_detalle_movilizacion and 
												dim.identificador=di.identificador and
												id_detalle_vacunacion='$identificador'
											ORDER BY 1 DESC 
											LIMIT 1; ");
				return $res;
	}
	
	public function imprimirReporteAretesVacunacion($conexion, $provincia,$canton,$parroquia,$subTipoProducto, $fechaInicio, $fechaFin) {
	
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";
		$canton = $canton!="" ? "'" . $canton . "'" : "NULL";
		$parroquia = $parroquia!="" ? "'" . $parroquia . "'" : "NULL";
	
		$fechaInicio = $fechaInicio != "" ? "'" . $fechaInicio . "'" : "NULL";
		if ($fechaFin != "") {
			$fechaFin = str_replace ( "/", "-", $fechaFin );
			$fechaFin = strtotime ( '+1 day', strtotime ( $fechaFin ) );
			$fechaFin = date ( 'd-m-Y', $fechaFin );
			$fechaFin = "'" . $fechaFin . "'";
		} else {
			$fechaFin = "NULL";
		}
	
		$res = $conexion->ejecutarConsulta ("SELECT
												v.numero_certificado,
												(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
												FROM g_operadores.operadores oa WHERE v.usuario_responsable = oa.identificador ) end digitador
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT')
												and up.identificador=v.usuario_responsable),
												(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.identificador_vacunador = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
												FROM g_operadores.operadores oa WHERE v.identificador_vacunador = oa.identificador ) end vacunador
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT')
												and up.identificador=v.identificador_vacunador),
												(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.identificador_distribuidor = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
												FROM g_operadores.operadores oa WHERE v.identificador_distribuidor = oa.identificador ) end distribuidor
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT')
												and up.identificador=v.identificador_distribuidor),
												s.provincia provincia_sitio,
												s.canton canton_sitio,
												s.parroquia parroquia_sitio,
												s.nombre_lugar nombre_sitio,
												od.identificador identificacion_propietario,
												CASE WHEN od.razon_social::text = ''::text THEN upper((od.nombre_representante::text || ' '::text) || od.apellido_representante::text)::character varying::text
												ELSE upper(od.razon_social::text)END AS nombre_propietario,
												pr.nombre_comun producto,
												di.identificador identificador_producto,
												tv.nombre_vacuna tipo_vacunacion,
												(to_char(v.fecha_registro,'dd/mm/yyyy HH24:mi:ss')) fecha_registro,
												(to_char(v.fecha_vacunacion,'dd/mm/yyyy')) fecha_vacunacion,
                                                v.estado as estado_vacunacion,
                                                CASE WHEN (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) >= 1 and (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) <=15 THEN 'PROXIMO A CADUCAR' ELSE v.estado END as estado
											FROM
												g_catalogos.productos pr,
												g_catalogos.subtipo_productos stp,
												g_vacunacion.vacunacion v
												LEFT OUTER JOIN g_operadores.operadores as oa ON v.identificador_operador_vacunacion = oa.identificador
												LEFT OUTER JOIN g_operadores.sitios as s ON v.id_sitio = s.id_sitio
												LEFT OUTER JOIN g_vacunacion.detalle_vacunacion as dc ON v.id_vacunacion = dc.id_vacunacion
												LEFT OUTER JOIN g_vacunacion.detalle_identificadores as di ON di.id_detalle_vacunacion = dc.id_detalle_vacunacion
												LEFT OUTER JOIN g_operadores.operadores as od ON od.identificador=s.identificador_operador
												LEFT OUTER JOIN g_catalogos.tipo_vacunas as tv ON tv.id_tipo_vacuna=v.id_tipo_vacuna
											WHERE
												pr.id_producto=dc.id_producto
												and pr.id_subtipo_producto=stp.id_subtipo_producto
												and stp.id_subtipo_producto=$subTipoProducto
												and ($provincia is NULL or s.provincia = $provincia)
												and ($canton is NULL or s.canton = $canton)
												and ($parroquia is NULL or s.parroquia = $parroquia)
												and ($fechaInicio is NULL or  v.fecha_registro >= $fechaInicio)
												and ($fechaFin is NULL or  v.fecha_registro <= $fechaFin);" );
		return $res;
	}
	
	public function abrirDetalleVacunacionIdentificadoresXIdVacunacion($conexion, $idVacunacion) {
	    
	    $consulta = "SELECT
                    	*
                    FROM
                    	g_vacunacion.vacunacion v
                    INNER JOIN
                    	g_vacunacion.detalle_vacunacion dv ON v.id_vacunacion = dv.id_vacunacion
                    WHERE
                        v.id_vacunacion = $idVacunacion
                    	and to_char(v.fecha_vacunacion,'YYYY-MM-DD')::date + interval '180 days' = current_date;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	
	public function obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, $tipoRol, $codigoTipoOperacion) {
	    
        $consulta = "SELECT
                    	distinct e.identificador as identificador_empresa
                    	, e.id_empresa
                    	, op.id_tipo_operacion
                    	, rem.tipo
                    	, top.codigo
                        , STRING_AGG(DISTINCT top.codigo ,', ') as codigo_tipo_operacion
                    FROM
                    	g_usuario.empleados em
                    INNER JOIN g_usuario.roles_empleados rem ON em.id_empleado = rem.id_empleado
                    INNER JOIN g_usuario.empresas e ON em.id_empresa = e.id_empresa
                    INNER JOIN g_operadores.operaciones op ON e.identificador = op.identificador_operador
                    INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE 
                        em.identificador = '$identificadorUsuario'
                        and em.estado = 'activo'
                        and rem.tipo in " . $tipoRol . "
                        and rem.estado = 'activo'
                        and op.estado in ('registrado', 'porCaducar')
                        and top.id_area = 'SA'
                        and top.codigo in " . $codigoTipoOperacion . "
                    GROUP BY 1, 2, 3, 4, 5;";

        $res = $conexion->ejecutarConsulta($consulta);
	       
        return $res;	    
	}
	
	public function obtenerOperacionesUsuario($conexion, $identificadorUsuario, $codigoTipoOperacion) {
	    
	    $consulta = "SELECT
                    	op.identificador_operador
                        , STRING_AGG(DISTINCT top.codigo ,', ') as codigo_tipo_operacion
                    FROM
                    	g_operadores.operaciones op
                    INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                    	op.estado in ('registrado', 'porCaducar')
                    	and top.id_area = 'SA'
                    	and top.codigo in " . $codigoTipoOperacion . "
                    	and op.identificador_operador = '$identificadorUsuario'
                    GROUP BY 1;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
}