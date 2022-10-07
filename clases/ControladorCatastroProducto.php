<?php
class ControladorCatastroProducto {
	
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
												fe.identificador=dc.identificador
												and dc.estado=1 and fe.identificador='".$usuario."'
											ORDER BY nombres ASC;");
		return $res;
	}
	
	public function consultarRelacionEmpleadoEmpresa($conexion,$usuario) {
		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
												em.identificador identificador_empresa
											FROM
												g_usuario.empleados e
												,g_usuario.empresas em
	
											WHERE
												 em.id_empresa=e.id_empresa
												and em.estado='activo'
												and e.estado='activo'
												and e.identificador='".$usuario."';");
		return $res;
	}
	
	public function consultarEmpresaPorOperacion($conexion, $operacion ,$usuario) {
		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
												em.identificador identificador_empresa
											FROM
												g_usuario.empleados e
												,g_usuario.empresas em
												,g_operadores.operadores o
												, g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
												, g_operadores.areas a
												,g_operadores.productos_areas_operacion pao
											WHERE
												o.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and pao.id_area = a.id_area
												and pao.id_operacion=op.id_operacion
												and op.estado in ('registrado', 'porCaducar')
												and t.codigo in $operacion
												and t.id_area='SA'
												and e.estado='activo'
												and em.identificador=o.identificador
												and em.id_empresa=e.id_empresa
												and e.identificador='".$usuario."';");
		return $res;
	}
	
	
	public function obtenerIdentificadorTipoEmpresa($conexion, $tipo ,$usuario) {
		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
												em.identificador identificador_empresa
											FROM
												g_usuario.empleados e
												,g_usuario.empresas em
												,g_operadores.operadores o
												, g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
												, g_operadores.areas a
												,g_operadores.productos_areas_operacion pao
											WHERE
												o.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and pao.id_area = a.id_area
												and pao.id_operacion=op.id_operacion
												and op.estado in ('registrado', 'porCaducar')
												and t.codigo='".$tipo."'
												and t.id_area='SA'
												and em.identificador=o.identificador
												and em.id_empresa=e.id_empresa
												and e.identificador='".$usuario."';");
		return $res;
	}
	
	public function listaTipoAreaOperacion($conexion) {
		$res = $conexion->ejecutarConsulta ( "SELECT distinct
        										id_area
												,(CASE id_area 
												 WHEN 'IAP' THEN 'Registro de insumos plaguicidas'
												 WHEN 'SV' THEN 'Sanidad vegetal'
												 WHEN 'LT' THEN 'Labortorios'
												 WHEN 'SA' THEN 'Sanidad animal'
												 WHEN 'IAV' THEN 'Registro de insumos venterinarios'
												 WHEN 'AI' THEN 'Inocuidad de alimentos'
											    END) as area_operacion
											FROM 
												g_catalogos.tipos_operacion 
											ORDER BY
												id_area;" );
		return $res;
	}
	
	public function filtrarSitiosCatastro($conexion, $identificadorEmisor, $nombreSitio, $idAreaOperacion) {
		$identificadorEmisor = $identificadorEmisor != "" ? "'" . $identificadorEmisor . "'" : "null";
		$nombreSitio = $nombreSitio != "" ? "'%" . $nombreSitio . "%'" : "null";
				
		$res = $conexion->ejecutarConsulta ("SELECT  DISTINCT 
												s.id_sitio 
												,s.nombre_lugar
												,o.identificador
											FROM 
												 g_operadores.operadores o
												, g_operadores.sitios s
												, g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
												, g_operadores.areas a
												,g_operadores.productos_areas_operacion pao
											WHERE 
												o.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and s.id_sitio = a.id_sitio
												and pao.id_area = a.id_area 
												and pao.id_operacion=op.id_operacion
												and s.estado='creado'
												and ($identificadorEmisor is NULL or o.identificador = $identificadorEmisor) 
												and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio)												 
												and op.estado in ('registrado', 'porCaducar')
												and t.id_area = '".$idAreaOperacion."'
												and t.codigo in ('COM', 'PRO')												
												order by s.nombre_lugar asc;" );
		return $res;
	}
	
	public function listarAreasXsitiosOperacion($conexion, $idSitio, $idTipoOperacion) {
	    
		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
                                            	a.id_area,a.nombre_area
                                            FROM
                                            	g_operadores.sitios s,
                                            	g_operadores.areas a,
                                            	g_operadores.productos_areas_operacion pao,
                                            	g_operadores.operaciones ope,
                                            	g_catalogos.tipos_operacion top   
                                            WHERE
                                            	s.id_sitio = '" . $idSitio . "'
                                            	and s.id_sitio = a.id_sitio
                                            	and a.id_area = pao.id_area
                                            	and a.estado = 'creado'
                                            	and pao.id_operacion = ope.id_operacion
                                            	and ope.id_tipo_operacion = top.id_tipo_operacion
                                            	and ope.id_tipo_operacion = '" . $idTipoOperacion . "'
                                            	and ope.estado in ('registrado', 'porCaducar');");
		return $res;
	}
	
	public function listarProductosXareas($conexion, $idArea, $idAreaTematica) {
		if ($idAreaTematica == 'SA') {
			$busqueda1 = "and top.id_area='$idAreaTematica' and pa.estado_producto='activo' ";
		} else {
			$busqueda1 = "and top.id_area='$idAreaTematica'";
		}
		
		$res = $conexion->ejecutarConsulta ( "SELECT DISTINCT
												 pr.id_producto
												,pr.nombre_comun
												,sp.nombre nombre_subtipo
												,pr.unidad_medida
												,pa.id_especie
												,(select codigo from g_catalogos.especies where id_especies=pa.id_especie) codigo_especie
												,pa.dias_inicio_etapa
												,pa.dias_fin_etapa
												,pa.codigo codigo_producto
											FROM 
												g_catalogos.productos pr
												full outer join g_catalogos.productos_animales pa on pr.id_producto=pa.id_producto
												full outer join g_catalogos.productos_vegetales pv on pr.id_producto=pv.id_producto
												full outer join g_catalogos.productos_inocuidad pi on pr.id_producto=pi.id_producto
												JOIN g_catalogos.subtipo_productos sp ON sp.id_subtipo_producto = pr.id_subtipo_producto
												JOIN g_operadores.operaciones op ON pr.id_producto=op.id_producto
												JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion=op.id_tipo_operacion
												JOIN g_operadores.productos_areas_operacion pao ON pao.id_operacion=op.id_operacion
											WHERE 
												op.estado in ('registrado', 'porCaducar') 
												and pr.estado='1'
												and pao.id_area='$idArea'
												" . $busqueda1 . "	
											ORDER BY 
												3,2;" );
		return $res;
	}
	
	public function guardarCatastroProducto($conexion, $idSitio, $idArea, $idProducto, $nombreProducto, $cantidad, $idUnidadMedidaCantidad, $identificadorResponsable, $unidadMedidaPeso, $idEspecie, $fechaNacimiento, $numeroLote, $diasInicioEtapa, $diasFinEtapa, $nombreArea, $idOperacion) {
		
		$numeroLote = $numeroLote != '' ? "'$numeroLote'" : 'null';

		if ($fechaNacimiento != '') {
			$fechaNacimientoRegistroX = str_replace ( "/", "-", $fechaNacimiento );
			$fechaNacimientoRegistro = "'$fechaNacimientoRegistroX'";
		} else {
			if ($diasInicioEtapa == "" && $diasFinEtapa == "") {
				$fechaNacimientoRegistro = 'null';
			} else {
				$fechaRegistro = date ( 'd-m-Y' );
				$nuevafecha = strtotime ( '-' . $diasInicioEtapa . ' day', strtotime ( $fechaRegistro ) );
				$nuevafecha = date ( 'd-m-Y', $nuevafecha );
				$fechaNacimientoRegistro = "'$nuevafecha'";
			}
		}
		
		$res = $conexion->ejecutarConsulta ( "INSERT INTO
												g_catastro.catastros(id_sitio, id_area, id_producto, nombre_producto,cantidad,
												unidad_comercial,identificador_responsable, unidad_medida_peso, 
												id_especie,fecha_nacimiento,numero_lote, id_tipo_operacion)
											VALUES 
												('$idSitio', '$idArea','$idProducto','$nombreProducto','$cantidad','$idUnidadMedidaCantidad',
												'$identificadorResponsable', '$unidadMedidaPeso',
												$idEspecie,$fechaNacimientoRegistro,$numeroLote, '$idOperacion')  RETURNING id_catastro" );
		return $res;
	}
	
	public function guardarDetalleCatastroProducto($conexion, $idCatastro, $identificadorProducto, $secuencialProducto, $identificadorUnicoProducto, $estadoRegistro) {
		$identificadorProducto = $identificadorProducto != '' ? "'$identificadorProducto'" : 'null';
		

		$res = $conexion->ejecutarConsulta ("INSERT INTO 
												g_catastro.detalle_catastros(id_catastro, identificador_producto, secuencial,identificador_unico_producto , estado_registro)
											VALUES 
												('$idCatastro',$identificadorProducto,'$secuencialProducto','$identificadorUnicoProducto','$estadoRegistro')" );
		return $res;
	}
	
	public function autogenerarSecuencialDetalleCatastroProducto($conexion, $idArea) {
		$anoActual = date('y');
		$busqueda = $idArea . $anoActual;
		$res = $conexion->ejecutarConsulta ( "SELECT
												MAX(dc.secuencial)::numeric + 1 as secuencial
											FROM
												 g_catastro.catastros ca
												,g_catastro.detalle_catastros dc
											WHERE
												ca.id_catastro=dc.id_catastro
												and ca.id_area||''||to_char(ca.fecha_registro,'YY') = '$busqueda'
											GROUP BY
												to_char(ca.fecha_registro,'YY')
												,ca.id_area ;" );
		
		if(pg_fetch_result($res, 0, 'secuencial') == '' || pg_fetch_result($res, 0, 'secuencial') == null){
			$res = 1;
		}else{
		   $res = pg_fetch_result($res, 0, 'secuencial');
		}
		
		return $res;
	}
	
	public function guardarCatastroTransaccion($conexion,$idCatastro, $idArea, $idConceptoCatastro, $idProducto, $cantidadIngreso, $cantidadTotal, $idUnidadMedidaCantidad, $identificadorResponsable, $idOperacion) {
		$res = $conexion->ejecutarConsulta ( "INSERT INTO 
												g_catastro.transaccion_catastro(id_catastro,id_area,id_concepto_catastro,id_producto,
												cantidad_ingreso,cantidad_total, unidad_comercial,identificador_responsable, id_tipo_operacion)
											VALUES 
												('$idCatastro','$idArea','$idConceptoCatastro','$idProducto',
												'$cantidadIngreso','$cantidadTotal','$idUnidadMedidaCantidad','$identificadorResponsable', '$idOperacion') " );
		return $res;
	}
	
	public function guardarCatastroTransaccionResta($conexion,$idCatastro ,$idArea, $idConceptoCatastro, $idProducto, $cantidadEgreso, $cantidadTotal, $idUnidadMedidaCantidad, $identificadorResponsable,$idOperacion) {
		
		$res = $conexion->ejecutarConsulta ( "INSERT INTO
												g_catastro.transaccion_catastro(id_catastro,id_area,id_concepto_catastro,id_producto,
												cantidad_egreso,cantidad_total, unidad_comercial,identificador_responsable, id_tipo_operacion)
											VALUES
												('$idCatastro','$idArea','$idConceptoCatastro','$idProducto',
												'$cantidadEgreso','$cantidadTotal','$idUnidadMedidaCantidad','$identificadorResponsable', '$idOperacion') " );
		return $res;
	}
	
	public function listarAreas ($conexion){
		$res = $conexion->ejecutarConsulta("select
													distinct(nombre), unidad_medida, codigo
												from
													g_catalogos.areas_operacion
												order by 1;");
		return $res;
	}
	
	public function consultaConceptoCatastroXCodigo($conexion, $codigo) {
		$res = $conexion->ejecutarConsulta ( "SELECT 
												id_concepto_catastro
		 									FROM 
												g_catastro.concepto_catastros
											WHERE 
												codigo='$codigo';" );
		return $res;
	}
	
	public function consultarCantidadTotalProducto($conexion, $idArea, $idProducto, $idUnidadMedidaCantidad, $idOperacion) {
	    
	    $res = $conexion->ejecutarConsulta ( "SELECT
                                            	tc1.cantidad_total
                                            FROM
                                            	g_catastro.transaccion_catastro tc1										 	
                                            INNER JOIN 
                                            	(SELECT 
                                            		max(id_transaccion_catastro) as id_transaccion_catastro
                                            	 FROM 
                                            		g_catastro.transaccion_catastro
                                            	 WHERE
                                            		id_area = '$idArea'
                                            		and id_producto = '$idProducto'
                                            		and unidad_comercial = '$idUnidadMedidaCantidad'
                                            		and id_tipo_operacion = '$idOperacion') as tc2 ON tc1.id_transaccion_catastro = tc2.id_transaccion_catastro;" );
		return $res;
	}
	
	public function actualizarIdentificadorProducto($conexion, $identificadorProducto, $estado) {
		$res = $conexion->ejecutarConsulta ( "UPDATE 
												g_catalogos.serie_aretes
	   										SET	
												estado='$estado'
	 										WHERE 
												numero_arete='$identificadorProducto';" );
		return $res;
	}
	
	public function filtroCatastroArea($conexion, $identificador, $nombreOperador, $nombreSitio, $provincia) {
		$identificador = $identificador != "" ? "'" . $identificador . "'" : "null";
		$nombreOperador = $nombreOperador != "" ? "'" . $nombreOperador . "'" : "null";
		$nombreSitio = $nombreSitio != "" ? "'" . $nombreSitio . "'" : "null";
		$provincia = $provincia != "0" ? "'" . $provincia . "'" : "null";
		
		$res = $conexion->ejecutarConsulta ( "SELECT DISTINCT
												ca.id_sitio
												,s.nombre_lugar nombre_sitio
												,op.identificador identificador_operador 
												, case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social  end::character varying(512) nombre_operador
												,s.provincia
											FROM
												g_catastro.catastros ca
												,g_catastro.detalle_catastros dc
												,g_operadores.sitios s
												,g_operadores.operadores op
											WHERE
												ca.id_catastro=dc.id_catastro
												and ca.id_sitio=s.id_sitio
												and op.identificador=s.identificador_operador
												and dc.estado_registro='activo'
												and ($identificador is NULL or op.identificador = $identificador)  
												and ($nombreOperador is NULL or case when op.razon_social = '' then coalesce(op.nombre_representante ||' '|| op.apellido_representante ) else op.razon_social end ilike $nombreOperador)
												and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio) 
												and ($provincia is NULL or s.provincia = $provincia) ");
													
		return $res;
	}

	public function consultaCatastroIndividual($conexion, $identificadorOperador, $nombreOperador, $nombreSitio, $provincia, $fechaInicio, $fechaFin,$usuarioResponsable,$identificadorProducto, $numeroLote = null) {
		$identificadorOperador = $identificadorOperador != "" ? "'" .  $identificadorOperador  . "'" : "NULL";
		$nombreOperador = $nombreOperador != "" ? "'%" . $nombreOperador . "%'" : "NULL";
		$nombreSitio = $nombreSitio != "" ? "'%" . $nombreSitio . "%'" : "NULL";
	
		$provincia = $provincia != '' ? "'" . $provincia . "'" : "NULL";
		$fechaInicio = $fechaInicio != "" ? "'" . $fechaInicio . "'" : "NULL";
		$numeroLote = $numeroLote != "" ? "'%" . $numeroLote . "%'" : "NULL";
	
		if ($fechaFin != "") {
			$fechaFin = str_replace ( "/", "-", $fechaFin );
			$fechaFin = strtotime ( '+1 day', strtotime ( $fechaFin ) );
			$fechaFin = date ( 'd-m-Y', $fechaFin );
			$fechaFin = "'" . $fechaFin . "'";
		} else {
			$fechaFin = "NULL";
		}
		$identificadorProducto = $identificadorProducto != "" ? "'" .  $identificadorProducto  . "'" : "NULL";
	
		if(($identificadorOperador=="NULL") && ($nombreOperador=="NULL") && ($nombreSitio=="NULL") && ($provincia=="NULL") && ($fechaInicio=="NULL") && ($fechaFin=="NULL") && ($identificadorProducto=="NULL")){
			$busqueda = " and ca.fecha_registro >= current_date and ca.fecha_registro < current_date+1
			and  ca.identificador_responsable='$usuarioResponsable'";
		}

		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT(ca.id_catastro)
				,s.nombre_lugar  || ' - ' || a.nombre_area lugar
				,op.identificador || ' - ' || case when op.razon_social = '' then op.nombre_representante || ' '|| op.apellido_representante else op.razon_social  end operador
				,p.nombre_comun producto
				,count(dc.id_detalle_catastro)::integer cantidad
				,(to_char(ca.fecha_registro,'dd-mm-yyyy HH24:mi:ss')) fecha_registro
				FROM
				g_catastro.catastros ca
				,g_catastro.detalle_catastros dc
				,g_operadores.sitios s
				,g_operadores.areas a
				,g_operadores.operadores op
				,g_catalogos.productos p
				WHERE
				p.id_producto=ca.id_producto
				and ca.id_area=a.id_area
				and ca.id_catastro=dc.id_catastro
				and ca.id_sitio=s.id_sitio
				and op.identificador=s.identificador_operador
				and dc.estado_registro='activo'
				and ($identificadorOperador is NULL or op.identificador = $identificadorOperador)
				and ($nombreOperador is NULL or coalesce(case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social  end ) ilike $nombreOperador)
				and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio)
				and ($provincia is NULL or s.provincia = $provincia)
				and ($fechaInicio is NULL or  ca.fecha_registro >= $fechaInicio)
				and ($fechaFin is NULL or  ca.fecha_registro <= $fechaFin)
				and ($identificadorProducto is NULL or (case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end )=$identificadorProducto)
                and ($numeroLote is NULL or ca.numero_lote ilike $numeroLote)				
                ".$busqueda."
				GROUP BY
					ca.id_catastro
					,s.nombre_lugar
					,p.nombre_comun
					,a.nombre_area
					, op.identificador || ' - ' || case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social  end
				ORDER BY 1 DESC;" );
		return $res;
	}
	
	public function consultarProductoAnimal($conexion, $idProducto) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												*
											FROM
												g_catalogos.productos_animales
											WHERE
												id_producto='$idProducto' ;" );
		return $res;
	}
	
	public function imprimirIdentificadoresProducto($idProducto, $identificadorProducto, $idDetalleCatastro, $idConceptoCatastro, $idCatastro,$contador) {
		return '<tr  id="R' . $idDetalleCatastro . '">' . 
					'<td>' . $contador . '</td>' . 
					'<td>' . $identificadorProducto . '</td>' . 
					'<td align="center" >' . '<form id="imprimirIdentificadoresProducto" class="borrar" data-rutaAplicacion="catastroProducto" data-opcion="darBajaDetalleCatastro" data-accionEnExito="ACTUALIZAR">' . '<input type="hidden" name="idDetalleCatastro" value="' . $idDetalleCatastro . '" >' . '<input type="hidden" name="idCatastro" value="' . $idCatastro . '" >' . '<input type="hidden" name="idConceptoCatastro" value="' . $idConceptoCatastro . '" >' . '<button type="submit" class="icono"></button>' . '</form>' . '</td>' .
				'</tr>';
	}
	
	public function actualizarCantidadCatastro($conexion, $cantidad, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "UPDATE
												g_catastro.catastros
											SET 
												cantidad='$cantidad'
											WHERE 
												id_catastro='$idCatastro';" );
		return $res;
	}
	
	public function consultarDatosDetalleCatastro($conexion, $idDetalleCatastro) {
	    
		$res = $conexion->ejecutarConsulta ( "SELECT
												c.id_area
												,c.id_producto
												,c.id_catastro
												,dc.id_detalle_catastro
												,c.unidad_comercial
												,id_tipo_operacion
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and dc.id_detalle_catastro='$idDetalleCatastro';" );
		return $res;
	}
	
	public function actualizarEstadoDetalleCatastroEliminado($conexion, $idDetalleCatastro, $observacion) {
		$res = $conexion->ejecutarConsulta ( "UPDATE 
												g_catastro.detalle_catastros
										   SET  
												estado_registro='eliminado',
												observacion = '$observacion'
										   WHERE 
												id_detalle_catastro='$idDetalleCatastro'
												and estado_registro in ('activo', 'inactivo', 'temporal');" );
		return $res;
	}
	
	public function eliminarCatastro($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "DELETE FROM 
												g_catastro.catastros
											WHERE 
												id_catastro='$idCatastro';" );
		return $res;
	}
	
	public function eliminarDetalleCatastro($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "DELETE FROM 
												g_catastro.detalle_catastros
											WHERE
												id_catastro='$idCatastro';" );
		return $res;
	}
	
	public function listaProductosCatastro($conexion, $idSitio,$idArea) {
		$res = $conexion->ejecutarConsulta ( "SELECT 
												 c.id_producto
												,c.nombre_producto
												,count(c.id_producto) cantidad
												,c.unidad_comercial
												,(select u.nombre from g_catalogos.unidades_medidas u where u.id_unidad_medida=c.unidad_comercial::integer) nombre_unidad_comercial
												,sp.nombre nombre_subtipo
												,tp.nombre nombre_tipo_producto
												,c.id_tipo_operacion
												,top.nombre nombre_operacion
												, c.id_area
												,a.nombre_area
											FROM
												 g_catastro.catastros c
												,g_catastro.detalle_catastros dc
												,g_catalogos.productos p
												,g_catalogos.subtipo_productos sp
												,g_catalogos.tipo_productos tp
												,g_catalogos.tipos_operacion top
												,g_operadores.areas a
											WHERE
												c.id_area=a.id_area
												and	c.id_producto = p.id_producto
												and p.id_subtipo_producto=sp.id_subtipo_producto
												and sp.id_tipo_producto=tp.id_tipo_producto
												and c.id_catastro=dc.id_catastro
												and c.id_tipo_operacion=top.id_tipo_operacion
												and c.id_sitio = '$idSitio'
												and c.id_area = '$idArea'
												and dc.estado_registro='activo'
											GROUP BY 
												1,2,4,5,6,7,8,9,10,11
											ORDER BY 
												6,2
											ASC;" );
	
		return $res;
	}
	
	public function listaProductosCatastro1($conexion, $idSitio) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												c.id_area
												,upper(a.nombre_area) as nombre_area
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
												,g_operadores.areas a
											WHERE
												c.id_area=a.id_area
												and c.id_catastro=dc.id_catastro
												and c.id_sitio = '$idSitio'
												and dc.estado_registro='activo'
											GROUP BY 1,2
											ORDER BY 2 ASC;" );
	
				return $res;
	}
	
	public function procesoActualizacionCatastroAutomatico($conexion) {
		$res = $conexion->ejecutarConsulta ("SELECT
												c.id_area
												,c.identificador_responsable
												,c.id_catastro
												,(to_char(c.fecha_nacimiento,'dd-mm-yyyy')) fecha_nacimiento
												,(select current_date - (to_date(to_char(c.fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy')) ) dias_transcurridos
												,c.id_producto
												,c.unidad_comercial
												,p.nombre_comun producto
												,count(dc.id_detalle_catastro) cantidad
												,pa.codigo
												,c.id_tipo_operacion
												,s.identificador_operador
												,s.id_sitio
											FROM 
												 g_catastro.detalle_catastros dc
												,g_catastro.catastros c
												,g_catalogos.productos p
												,g_catalogos.productos_animales pa
												,g_catalogos.etapa_animales ea
												,g_operadores.sitios s
												,g_catalogos.tipos_operacion tp
											WHERE
												p.id_producto=pa.id_producto
												and c.id_producto = pa.id_producto
												and dc.id_catastro=c.id_catastro
												and dc.estado_registro='activo'
												and c.estado_etapa is null
												and c.id_sitio=s.id_sitio
												and c.id_tipo_operacion=tp.id_tipo_operacion
												and tp.codigo!='FAE'
												and tp.id_area!='AI'
												and pa.codigo in ('PORHON','POROTE','PORACO','PORDRE','PORATE','PORONA','EQUTRO','EQULLO','EQUTRA','EQUGUA',
														  'BOVERO','BOVERA','BOVETE','BOVONA','BOVORO','BOVACA','PDEPBE','PDEPEN','PAVPBE','PAVPEN',
														  'GAPPBP','GAPPPO','GAPGPO','GRPABR','GRPPAR','GRPGAR','GRPPBR','GRPPOR','GRPGOR',
														  'GRLABR','GRLPAR','GRLGAR','GRLOBR','GRLPOR','GRLGOR')
												and ( ((select current_date - (to_date(to_char(c.fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy')) )=ea.dias_cambio_etapa and pa.id_producto=ea.id_producto) or (ea.id_producto_etapa=c.id_producto and ea.estado_etapa='etapaFinal'))
											GROUP BY 
												c.id_area
												,c.identificador_responsable
												,c.cantidad
												,c.id_catastro
												,(to_char(c.fecha_registro,'dd-mm-yyyy'))						
												,(select current_date - (to_date(to_char(c.fecha_modificacion_etapa,'dd-mm-yyyy'),'dd-mm-yyyy')) ) 
												,c.id_producto
												,c.unidad_comercial
												,p.nombre_comun 
												,pa.codigo
												,s.identificador_operador
												,s.id_sitio
											ORDER BY c.id_catastro asc;" );
		return $res;
	}
	
	public function consultarActualizacionCatastroAutomatico($conexion, $idProducto) {
		$res = $conexion->ejecutarConsulta ( "SELECT 
												a.id_producto_etapa
												,p.nombre_comun nombre_producto_etapa
												,a.dias_cambio_etapa
												,a.estado_etapa
											FROM 
												g_catalogos.etapa_animales a,
												g_catalogos.productos p
											WHERE 
												a.id_producto_etapa=p.id_producto
												and a.id_producto='$idProducto';" );
		return $res;
	}
	
	public function actualizarCatastroAutomatico($conexion, $idProductoActualizado, $productoActualizado, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "UPDATE 
												g_catastro.catastros
  											SET 
												id_producto='$idProductoActualizado'
												,nombre_producto='$productoActualizado'
												,fecha_modificacion_etapa=(to_char(current_date,'dd-mm-yyyy'))::date
											WHERE 
												id_catastro='$idCatastro';" );
		return $res;
	}
	
	public function actualizarEtapaFinalCatastroAutomatico($conexion, $idCatastro, $estadoFinal) {
		$res = $conexion->ejecutarConsulta ( "UPDATE 
												g_catastro.catastros
  											SET 
												estado_etapa = '$estadoFinal'
											WHERE 
												id_catastro='$idCatastro';" );
		return $res;
	}
	
	public function abrirDetalleCatatroIndividualProducto($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "select
												dc.id_detalle_catastro,c.id_catastro
												,c.id_producto
												,p.nombre_comun producto
												,case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end identificador_producto
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
												, g_catalogos.productos p
											WHERE
												c.id_producto = p.id_producto
												and c.id_catastro=dc.id_catastro
												and c.id_catastro='$idCatastro'
												and dc.estado_registro='activo'
											ORDER BY
												 case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto::text end asc;" );
		return $res;
	}
	
	public function abrirDetalleCatatroIndividualIdentificadorProducto($conexion, $idDetalleCatastro) {
		$res = $conexion->ejecutarConsulta ( "select
				dc.id_detalle_catastro,c.id_catastro
				,c.id_producto
				,p.nombre_comun producto
				, dc.identificador_unico_producto identificador_producto
				FROM
				g_catastro.catastros c
				,g_catastro.detalle_catastros dc
				, g_catalogos.productos p
				WHERE
				c.id_producto = p.id_producto
				and c.id_catastro=dc.id_catastro
				and dc.id_detalle_catastro='$idDetalleCatastro'
				and dc.estado_registro='activo'
				and dc.identificador_producto is null
				ORDER BY
				dc.identificador_unico_producto  asc;" );
				return $res;
	}
	
	public function abrirCatatroIndividualProducto($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												c.id_producto
												,c.numero_lote
												,c.id_especie
												,c.unidad_medida_peso
												,c.fecha_modificacion_etapa
												,c.fecha_nacimiento
												,c.nombre_producto
												,c.peso,to_char(fecha_registro,'yyyy-mm-dd HH24:mi:ss') fecha_registro
												,s.nombre_lugar nombre_sitio
												,a.nombre_area
												,tp.id_area
												,top.nombre nombre_operacion
												,oo.identificador identificador_operador
												,case when oo.razon_social = '' then oo.nombre_representante ||' '|| oo.apellido_representante else oo.razon_social end nombre_operador
												,(select nombre from g_catalogos.unidades_medidas where id_unidad_medida=c.unidad_comercial::integer) nombre_unidad_comercial
												,(select codigo from g_catalogos.especies where id_especies=c.id_especie) codigo_especie,(select nombre from g_catalogos.especies where id_especies=c.id_especie) nombre_especie
											FROM
												 g_catastro.catastros c
												,g_operadores.sitios s
												,g_operadores.areas a
												,g_operadores.operadores oo 
												,g_catalogos.productos p
												,g_catalogos.tipo_productos tp
												,g_catalogos.subtipo_productos sp
												,g_catalogos.tipos_operacion top
									
											WHERE
									
												tp.id_tipo_producto = sp.id_tipo_producto 
												and sp.id_subtipo_producto = p.id_subtipo_producto 
												and oo.identificador=s.identificador_operador 
												and c.id_producto=p.id_producto
												and c.id_sitio=s.id_sitio 
												and c.id_area=a.id_area 
												and top.id_tipo_operacion=c.id_tipo_operacion 
												and id_catastro='$idCatastro';" );
		return $res;
	}
	
	public function cantidadDetalleCatastro($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												count(id_detalle_catastro) cantidad
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and c.id_catastro='$idCatastro'
												and dc.estado_registro='activo';" );
		return $res;
	}
	
	public function actualizarCatastro($conexion, $idCatastro, $peso, $unidadMedidaPeso, $numeroLote) {
		$peso = $peso != '' ? "'$peso'" : 'null';
		$unidadMedidaPeso = $unidadMedidaPeso != '' ? "'$unidadMedidaPeso'" : 'null';
		$numeroLote = $numeroLote != '' ? "'$numeroLote'" : 'null';
		
		$res = $conexion->ejecutarConsulta ( "UPDATE 
												g_catastro.catastros
											SET 
												
												peso=$peso
												,unidad_medida_peso=$unidadMedidaPeso
												,numero_lote=$numeroLote
											WHERE
												id_catastro='$idCatastro';" );
		return $res;
	}
	public function notificarEliminarCatastro($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												c.id_producto
												,dc.id_detalle_catastro
												,dc.estado_registro
												,case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto::text end identificador_producto
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and c.id_catastro='$idCatastro'
										
				;" );
		return $res;
	}
	
	public function listaConceptoCatastro($conexion) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												id_concepto_catastro
												,nombre_concepto
												,coeficiente
												,estado
												,codigo
											FROM
												g_catastro.concepto_catastros
											WHERE
												estado = 'activo'
											ORDER BY 
												nombre_concepto asc;" );
		
		return $res;
	}
	
	public function listaConceptoCatastroDarDeBaja($conexion,$motivo) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												id_concepto_catastro
												,nombre_concepto
												,coeficiente
												,estado
												,codigo
											FROM
												g_catastro.concepto_catastros
											WHERE
												estado = 'activo' and motivo='$motivo'
											ORDER BY
												nombre_concepto asc;" );
	
		return $res;
	}
	
	public function cantidadCatastroIndividualActivo($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												ca.id_catastro
												,ca.id_area
												,ca.id_sitio
												,ca.id_producto
												,ca.unidad_comercial
												,ca.id_tipo_operacion
												,count(dc.id_detalle_catastro)::integer cantidad
											FROM
												g_catastro.catastros ca
												,g_catastro.detalle_catastros dc
											WHERE
												ca.id_catastro=dc.id_catastro
												and dc.estado_registro='activo'
												and ca.id_catastro='$idCatastro'			
											GROUP BY 
												ca.id_catastro;" );
		
		return $res;
	}
	
	public function consultarIdentificadoresIdCatastro($conexion, $idCatastro, $identificador) {
		$res = $conexion->ejecutarConsulta ( "SELECT
												dc.id_detalle_catastro
												,case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto::text end identificador_producto
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and dc.estado_registro='activo'
												and dc.id_catastro='$idCatastro'
												and case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto::text end ='$identificador'
											ORDER BY 
												case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto::text end asc;" );
		
		return $res;
	}
	
	public function actualizarIdentificadoresProducto($conexion, $idCatastro) {
		$res = $conexion->ejecutarConsulta ( "SELECT identificador_producto FROM
												g_catastro.detalle_catastros
											WHERE
												id_catastro='$idCatastro'  ;" );
		return $res;
	}
	
	public function listarOperacionesXoperadorYsitio($conexion, $identificador, $idArea, $idSitio, $tipoOperacion){
			
		$res = $conexion->ejecutarConsulta("SELECT
							               		distinct top.id_tipo_operacion, top.nombre
							          		FROM
							                   g_operadores.operaciones o,
							                   g_catalogos.tipos_operacion top,
							                   g_operadores.productos_areas_operacion pao,
							                   g_operadores.areas a,
							                   g_operadores.sitios s
							               	WHERE
							                   a.id_sitio=s.id_sitio and
							                   pao.id_area=a.id_area and
							                   o.identificador_operador='$identificador' and
							                   top.id_tipo_operacion=o.id_tipo_operacion and
							                   pao.id_operacion=o.id_operacion and 
							                   o.estado in ('registrado', 'porCaducar') and
									   		   s.id_sitio='$idSitio' and
							                   top.id_area='$idArea' and
                                               top.codigo in $tipoOperacion
							                   order by top.nombre asc");
		return $res;
	}
	
	public function buscarOperaciones($conexion, $idTipoOperacion, $identificadorOperador,$idProducto, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM 
												g_operadores.operaciones 
											WHERE 
												id_tipo_operacion='$idTipoOperacion' 
												and identificador_operador='$identificadorOperador' 
												and id_producto='$idProducto' and estado='$estado';");
		return $res;
	}
	
	public function buscarAreasOperaciones($conexion, $idOperacion, $idArea){
			
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												id_area
												,id_operacion
												,estado
												,observacion
											    ,ruta_archivo
											FROM 
												g_operadores.productos_areas_operacion 
											WHERE 
												id_area='$idArea' 
												and  id_operacion in $idOperacion ;");
		return $res;
	}
	
	public function consultarOperacion ($conexion,$operacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
										  	FROM 
												g_operadores.operaciones 
											WHERE 
												id_operacion='$operacion';");
	
		return $res;
	}
	
	public function guardarNuevaOperacion ($conexion,$idTipoOperacion,$identificadorOperador, $estado,$observacion,$informe,$idProducto, $nombreProducto, $idVue,$fechaCreacion, $idPais, $nombrePais, $subPartidaProducto, $codigoProducto,$fechaAprobacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.operaciones(
												id_tipo_operacion, identificador_operador, estado, observacion,informe,	id_producto, nombre_producto, 
												id_vue, fecha_creacion, id_pais, nombre_pais, subpartida_producto_vue, codigo_producto_vue, fecha_aprobacion)
											VALUES ('$idTipoOperacion','$identificadorOperador','$estado', '$observacion' , '$informe','$idProducto', '$nombreProducto', '$idVue', '$fechaCreacion','$idPais','$nombrePais', '$subPartidaProducto', 
												'$codigoProducto','$fechaAprobacion') returning id_operacion");
		return $res;
	}
	
	public function guardarAreaOperacion($conexion,$area, $operacion,$estado,$observacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_operadores.productos_areas_operacion(id_area, id_operacion,estado,observacion)
											VALUES ($area,$operacion,'$estado','$observacion')");
		return $res;
	}
	
	public function actualizarDetalleCatastroIdentificador($conexion,$idDetalleCatastro,$identificadorProducto){
		$identificadorProducto = $identificadorProducto != '' ? "'$identificadorProducto'" : 'null';
		
		$res = $conexion->ejecutarConsulta("UPDATE g_catastro.detalle_catastros
	SET  identificador_producto=$identificadorProducto
	WHERE id_detalle_catastro=$idDetalleCatastro;");
		return $res;
	
	}
	
	public function actualizarDetalleVacunacionIdentificador($conexion,$identificadorAntiguo,$identificadorProducto){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vacunacion.detalle_identificadores
											SET 
												identificador='$identificadorProducto'
											WHERE 
												identificador='$identificadorAntiguo';");
		return $res;
	
	}
	
	public function actualizarDetalleMovilizacionIdentificador($conexion,$identificadorAntiguo,$identificadorProducto){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_movilizacion_producto.detalle_identificadores_movilizacion
											SET
												identificador='$identificadorProducto',
												control_identificador=TRUE
											WHERE
												identificador='$identificadorAntiguo';");
		return $res;
	
	}
	
	public function listarAretesXespecie($conexion,$idEspecie){

		$res = $conexion->ejecutarConsulta("SELECT 
												numero_arete
											FROM 
												g_catalogos.serie_aretes 
											WHERE
												id_especie='$idEspecie' 
												and estado='creado';");
		return $res;
		
	}
	
	public function obtenerMaximoControlReproduccion($conexion, $identificadorOperador, $idProducto){

		$res = $conexion->ejecutarConsulta("SELECT 
												id_control_reproduccion, cupo_cria, cantidad_cria
											FROM 
												g_catastro.control_reproduccion
											WHERE 
												id_control_reproduccion = (select max(id_control_reproduccion) from g_catastro.control_reproduccion where id_producto='$idProducto' 
											and identificador_operador = '$identificadorOperador');");
		return $res;
	
	}
	
	public function guardarControlReproduccion($conexion,$identificadorOperador, $idProducto,$cupoCria,$cantidadCria=null){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catastro.control_reproduccion(identificador_operador, id_producto, cupo_cria, fecha_registro, cantidad_cria) 
											VALUES ('$identificadorOperador', $idProducto,$cupoCria , 'now()',$cantidadCria) ;");
		return $res;
	
	}
	
	
	public function obtenerIdProductoXCodigoProducto($conexion, $codigoProducto) {
		$busqueda1 = "and pa.estado_producto = 'activo' ";

		$res = $conexion->ejecutarConsulta ("SELECT DISTINCT
												pr.id_producto
											FROM
												g_catalogos.productos pr
												full outer join g_catalogos.productos_animales pa on pr.id_producto = pa.id_producto
												full outer join g_catalogos.productos_vegetales pv on pr.id_producto = pv.id_producto
												full outer join g_catalogos.productos_inocuidad pi on pr.id_producto = pi.id_producto
											WHERE
												pr.estado = '1'
												and pa.codigo = '$codigoProducto'
												" . $busqueda1 . " ;" );
		return $res;
	}
	
	public function abrirSitio ($conexion, $idSitio){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_operadores.sitios
											WHERE
												id_sitio = $idSitio;");
		return $res;
	}
	
	public function obtenerCantidadCatastroXOperador ($conexion, $identificadorOperador,$idProducto){

		$res = $conexion->ejecutarConsulta("SELECT
												count(id_detalle_catastro) cantidad
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
												,g_operadores.sitios s
											WHERE
												c.id_catastro=dc.id_catastro
												and s.id_sitio=c.id_sitio
												and s.identificador_operador='$identificadorOperador'
												and c.id_producto in $idProducto
												and dc.estado_registro='activo';");
		return $res;
	}
	
	public function buscarCatastroAnoMadresCria ($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												cc.id_catastro,
												(SELECT	s.identificador_operador FROM g_operadores.sitios s WHERE s.id_sitio=cc.id_sitio),
												(SELECT count(id_detalle_catastro)*28 cantidad FROM g_catastro.catastros c	,g_catastro.detalle_catastros dc
												WHERE c.id_catastro=dc.id_catastro and c.id_producto =$idProducto and dc.estado_registro='activo' and c.id_catastro=cc.id_catastro   ) existentes,
												(case when cc.numero_actualizacion_cupo is null then 0 else cc.numero_actualizacion_cupo end) numero_actualizacion_cupo
											FROM
												g_catastro.catastros cc
											WHERE
												(cc.fecha_registro::date + ((case when cc.numero_actualizacion_cupo is null then 0 else cc.numero_actualizacion_cupo end)+1 || ' year')::interval)::date<=current_date
												and (SELECT count(id_detalle_catastro)*28 cantidad FROM g_catastro.catastros c	,g_catastro.detalle_catastros dc
												WHERE c.id_catastro=dc.id_catastro and c.id_producto =$idProducto and dc.estado_registro='activo' and c.id_catastro=cc.id_catastro   )>0 ;");
				return $res;
	}
	
	public function actualizarNumeroVecesCupo($conexion, $idCatastro,$numeroActualizacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catastro.catastros
   											SET
												numero_actualizacion_cupo='$numeroActualizacion'
											WHERE 
												id_catastro='$idCatastro';");
		return $res;
	}
	
	public function imprimirReporteRegistroCatastro($conexion, $provincia,$canton,$parroquia, $operacion, $fechaInicio, $fechaFin) {
		
		$provincia = $provincia!="" ? "''" . $provincia . "''" : "NULL";
		$canton = $canton!="" ? "''" . $canton . "''" : "NULL";
		$parroquia = $parroquia!="" ? "''" . $parroquia . "''" : "NULL";
		$operacion = $operacion!="" ? "''" . $operacion . "''" : "NULL";
		$fechaInicio = $fechaInicio != "" ? "''" . $fechaInicio . "''" : "NULL";
		if ($fechaFin != "") {
			$fechaFin = str_replace ( "/", "-", $fechaFin );
			$fechaFin = strtotime ( '+1 day', strtotime ( $fechaFin ) );
			$fechaFin = date ( 'd-m-Y', $fechaFin );
			$fechaFin = "''" . $fechaFin . "''";
		} else {
			$fechaFin = "NULL";
		}		

		$res = $conexion->ejecutarConsulta ("SELECT *
                                                    FROM crosstab('SELECT
                                                	CONCAT (tp.nombre,''-'',s.id_sitio,''-'',op.identificador,''-'',p.nombre_comun) as llave,
                                                	s.provincia, 
                                                	s.canton, 
                                                	s.parroquia, 
                                                	tp.nombre, 
                                                	s.nombre_lugar, 
                                                	s.id_sitio,
                                                	op.identificador, 
                                                	case when op.razon_social = '''' then op.nombre_representante || '' ''|| op.apellido_representante else op.razon_social end,
                                                	p.nombre_comun,
                                                	dc.estado_registro, count(dc.id_detalle_catastro)::integer cantidad 
                                                FROM 
                                                	g_catastro.catastros ca ,
                                                	g_catastro.detalle_catastros dc ,
                                                	g_operadores.sitios s ,
                                                	g_operadores.areas a ,
                                                	g_operadores.operadores op ,
                                                	g_catalogos.productos p ,
                                                	g_catalogos.tipos_operacion tp ,
                                                	g_catalogos.subtipo_productos stp 
                                                WHERE 
                                                	p.id_producto=ca.id_producto 
                                                	and ca.id_area=a.id_area 
                                                	and ca.id_catastro=dc.id_catastro 
                                                	and ca.id_sitio=s.id_sitio 
                                                	and op.identificador=s.identificador_operador 
                                                	and dc.estado_registro in (''activo'', ''inactivo'') 
                                                	and tp.id_tipo_operacion=ca.id_tipo_operacion 
                                                	and p.id_subtipo_producto=stp.id_subtipo_producto 
    												and ($operacion is NULL or tp.id_tipo_operacion = $operacion)
    												and ($provincia is NULL or s.provincia = $provincia)
    												and ($canton is NULL or s.canton = $canton)
    												and ($parroquia is NULL or s.parroquia = $parroquia)
    												and ($fechaInicio is NULL or  ca.fecha_registro >= $fechaInicio)
    												and ($fechaFin is NULL or  ca.fecha_registro <= $fechaFin)
											GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11
                                            ORDER BY 1 DESC', ".'$$VALUES'." ('activo'::text), ('inactivo')$$) as (llave text, provincia text, canton text, parroquia text, tipo_operacion text, nombre_sitio text, id_sitio integer, identificacion_propietario text, nombre_propietario text, producto text, activo text, inactivo text);");
		return $res;
	}
	
	public function imprimirReporteTransaccionesCatastro($conexion, $provincia,$identificacionOperador) {		
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";

		$res = $conexion->ejecutarConsulta ("SELECT 
												op.identificador identificador_propietario, 
												case when op.razon_social = '' then op.nombre_representante || ' '|| op.apellido_representante else op.razon_social  end nombre_propietario,
												si.nombre_lugar nombre_sitio, 
												tc.cantidad_ingreso,
												tc.cantidad_egreso,
												tc.cantidad_total, 
												cc.nombre_concepto motivo, 
												top.nombre tipo_operacion, 
												stp.nombre subtipo_producto,
												pr.nombre_comun producto,
												tc.identificador_responsable identificacion_responsable,
												(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)
						      					   FROM g_uath.ficha_empleado rsv WHERE  tc.identificador_responsable= rsv.identificador )
													else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
													FROM g_operadores.operadores oa WHERE  tc.identificador_responsable = oa.identificador ) end nombre_responsable
										 				FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=tc.identificador_responsable),
												to_char(tc.fecha_registro,'dd-mm-yyyy HH24:mi:ss') fecha_registro
											FROM 
												g_catastro.transaccion_catastro tc, 
												g_operadores.operadores op,
												g_operadores.sitios si,
												g_operadores.areas ar,
												g_catalogos.productos pr,
												g_catastro.concepto_catastros cc,
						  						g_catalogos.tipos_operacion top,
												g_catalogos.subtipo_productos stp
											WHERE 
												ar.id_area=tc.id_area and 
												ar.id_sitio=si.id_sitio and 
												si.identificador_operador=op.identificador and
						  						top.id_tipo_operacion=tc.id_tipo_operacion and
						  						pr.id_subtipo_producto=stp.id_subtipo_producto and
						  						pr.id_producto=tc.id_producto and 
												cc.id_concepto_catastro=tc.id_concepto_catastro and
												op.identificador='$identificacionOperador' and
												($provincia is NULL or si.provincia = $provincia)			
											ORDER BY  
												tc.fecha_registro 
											ASC;");
		return $res;
	}

	public function buscarIdentificadorProductoMovilizado ($conexion, $identificadorProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												m.id_movilizacion
											FROM
												g_movilizacion_producto.movilizacion m,
												g_movilizacion_producto.detalle_movilizacion dm,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim
											WHERE
												m.id_movilizacion=dm.id_movilizacion and
												m.estado not in ('anulado') and
												dm.id_detalle_movilizacion=dim.id_detalle_movilizacion and
												dim.identificador='$identificadorProducto'
											 ;");
		return $res;
	}
	
	public function buscarIdentificadorProductoVacunado ($conexion, $identificadorProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												di.identificador
 											FROM 
												g_vacunacion.vacunacion va,
												g_vacunacion.detalle_vacunacion dv,
												g_vacunacion.detalle_identificadores di 
											WHERE 
												va.id_vacunacion=dv.id_vacunacion and
												va.estado not in ('anulado') and 
 												dv.id_detalle_vacunacion=di.id_detalle_vacunacion and
												di.identificador='$identificadorProducto' ;");
		return $res;
	}
		
	public function listaCatastroManual($conexion,$operador,$tipoOperacion) {
		$res = $conexion->ejecutarConsulta ( "SELECT distinct (ca.id_catastro)
  												FROM g_catastro.catastros ca, 
													g_catastro.detalle_catastros dc, 
													g_operadores.sitios si 
												where ca.id_catastro=dc.id_catastro and 
													 dc.estado_registro='activo' and  
													ca.id_sitio=si.id_sitio and si.identificador_operador='$operador' and 
													ca.id_tipo_operacion='$tipoOperacion' and ca.fecha_registro<'2017-01-27' --and ca.id_sitio='103564' 
						
				;" );
	
		return $res;
	}
	
	public function verificarCatastroIdentificador($conexion,$identificador) {
		$res = $conexion->ejecutarConsulta ("SELECT 
												ca.id_catastro
											FROM 
												g_catastro.catastros ca,
												g_catastro.detalle_catastros dc
											WHERE	
												ca.id_catastro=dc.id_catastro and 
												dc.estado_registro='activo' and
												(case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end )='$identificador';" );
	
				return $res;
	}	
	
	public function abrirDetalleCatatroVacunacionPorEstadoCatastroXEstadoVacunacion($conexion, $estadoCatastro, $estadoVacunacion) {
	    
	    $consulta = "SELECT
                        	v.id_vacunacion, v.id_sitio, v.numero_certificado, v.fecha_vacunacion, v.estado, dv.id_producto, di.id_detalle_vacunacion, di.identificador
                        FROM
                        	g_catastro.detalle_catastros dc
                        INNER JOIN 
                        	g_vacunacion.detalle_identificadores di ON dc.identificador_producto = di.identificador OR dc.identificador_unico_producto = di.identificador
                        INNER JOIN 
                        	g_vacunacion.detalle_vacunacion dv ON di.id_detalle_vacunacion = dv.id_detalle_vacunacion
                        INNER JOIN 
                        	g_vacunacion.vacunacion v ON dv.id_vacunacion = v.id_vacunacion
                        WHERE
                        	dc.estado_registro = '$estadoCatastro'
                        	and v.estado = '$estadoVacunacion'
                        	and to_char(v.fecha_vacunacion,'YYYY-MM-DD')::date + interval '16 days' = current_date;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarEstadoDetalleCatastroXIdentificadorProducto($conexion, $identificadorProducto, $estadoCatastro, $observacion = null) {
	    
	    $consulta = "UPDATE
    						g_catastro.detalle_catastros
    				   SET
    						estado_registro = '$estadoCatastro',
							observacion = '$observacion'
    				   WHERE
    						identificador_producto = '$identificadorProducto'
                            or identificador_unico_producto = '$identificadorProducto';";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function abrirDetalleCatastroXEliminarXIdentificadorProducto($conexion) {
	    
	    $consulta = "SELECT 
						v.id_vacunacion, v.fecha_vacunacion, v.fecha_registro, mv.identificador, current_date - to_char(v.fecha_vacunacion, 'YYYYMMDD')::date as dias_sin_vacunacion
					FROM
						g_vacunacion.vacunacion v,
						(SELECT 
							max(v.id_vacunacion) as id_vacunacion, di.identificador
						FROM 
							g_vacunacion.vacunacion v
						INNER JOIN g_vacunacion.detalle_vacunacion dv ON v.id_vacunacion = dv.id_vacunacion
						INNER JOIN g_vacunacion.detalle_identificadores di ON dv.id_detalle_vacunacion = di.id_detalle_vacunacion
						INNER JOIN g_catastro.detalle_catastros dc ON di.identificador = CASE WHEN dc.identificador_producto is null THEN dc.identificador_unico_producto ELSE dc.identificador_producto END 
						GROUP BY 2
						) as mv
					WHERE 
						v.id_vacunacion = mv.id_vacunacion
						and current_date - to_char(v.fecha_vencimiento, 'YYYYMMDD')::date > 425;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function buscarCatastroIdentificadorProductoXIdentificadorProductoXEstado($conexion, $identificadorProducto, $estado) {
	    
	    $consulta = "SELECT 
                        c.id_catastro, 
                        c.unidad_comercial, 
                        c.id_tipo_operacion,
                        c.nombre_producto,
                        c.id_producto,
                        dc.estado_registro,
                        dc.identificador_producto, 
                        dc.secuencial, 
                        dc.identificador_producto,
                        dc.estado_registro,
                        dc.observacion, 
                        s.id_sitio,  
                        s.nombre_lugar, 
                        s.provincia,
                        a.id_area,
                        s.identificador_operador,
                        (SELECT 
                            v.fecha_vencimiento
                        FROM 
                            g_vacunacion.vacunacion v
                        INNER JOIN g_vacunacion.detalle_vacunacion dv ON v.id_vacunacion = dv.id_vacunacion 
                        WHERE 
                            dv.id_detalle_vacunacion  = (SELECT MAX(id_detalle_vacunacion) FROM g_vacunacion.detalle_identificadores div WHERE div.identificador = '$identificadorProducto')) as fecha_vencimiento 
                    FROM 
                        g_catastro.detalle_catastros dc 
                    INNER JOIN g_catastro.catastros c ON dc.id_catastro = c.id_catastro
                    INNER JOIN g_operadores.areas a ON c.id_area = a.id_area  
                    INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio 
                    WHERE 
                        dc.identificador_producto = '$identificadorProducto';";
	  
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	} 
	
	public function buscarSitiosXIdentificadorOperadorXProvincia($conexion, $identificadorOperador, $provincia) {
	    
	    $consulta = "SELECT  DISTINCT
                        	s.id_sitio
                        	,s.nombre_lugar
                        	,o.identificador
                     FROM
                        	g_operadores.operadores o
                        	, g_operadores.sitios s
                        	, g_operadores.operaciones op
                        	, g_catalogos.tipos_operacion t
                        	, g_operadores.areas a
                        	,g_operadores.productos_areas_operacion pao
                     WHERE
                        	o.identificador = op.identificador_operador
                        	and op.id_tipo_operacion = t.id_tipo_operacion
                        	and s.id_sitio = a.id_sitio
                        	and pao.id_area = a.id_area
                        	and pao.id_operacion = op.id_operacion
                        	and s.estado = 'creado'
                        	and o.identificador = '$identificadorOperador'
                        	and s.provincia = '$provincia'
                        	and op.estado in ('registrado', 'porCaducar')
                        	and t.id_area = 'SA'
                        	and t.codigo in ('PRO','COM', 'OPT', 'OPI')
	                ORDER BY s.nombre_lugar asc;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	} 
	
	public function guardarLogActivarProducto($conexion, $idSitioOrigen, $idAreaOrigen, $idSitioDestino, $idAreaDestino, $identificadorProducto, $identificadorResponsable, $tipoActivacion, $motivoActivacion, $observacionActivacion) {
	    
	    $consulta = "INSERT INTO g_catastro.log_activacion_producto(id_sitio_origen, id_area_origen, id_sitio_destino, id_area_destino, identificador_producto, identificador_responsable, fecha_registro, tipo_activacion, motivo_activacion, observacion_activacion)
                    VALUES ($idSitioOrigen, $idAreaOrigen, $idSitioDestino, $idAreaDestino, '$identificadorProducto', '$identificadorResponsable', 'now', '$tipoActivacion', '$motivoActivacion', '$observacionActivacion');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function cambiarEstadoIdentificadorProductoDetalleCatastroXIdCatastro($conexion, $identificadorProducto, $estadoDetalleCatastro) {
	    
	    $consulta = "UPDATE 
                        g_catastro.detalle_catastros
                     SET 
                        estado_registro = '$estadoDetalleCatastro',
                        fecha_actualizacion = now()
                     WHERE 
                        (CASE WHEN identificador_producto != '' THEN identificador_producto ELSE identificador_unico_producto END) = '$identificadorProducto';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function imprimirReporteAretesDadosBaja($conexion, $provincia, $fechaInicio, $fechaFin) {
	    
	    $provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";
	    $fechaInicio = $fechaInicio != "" ? "'" . $fechaInicio . "'" : "NULL";
	    $fechaFin = $fechaFin != "" ? "'" . $fechaFin . "'" : "NULL";
	    
	    $consulta = "SELECT
                        distinct 
                    	tc.id_catastro,
                    	tc.identificador_responsable identificacion_responsable,
                    	(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then 'Técnico PPC'
                    		else 'Operador vacunación' end tipo_usuario
                    		 FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=tc.identificador_responsable),	
                    	(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)
                    		 FROM g_uath.ficha_empleado rsv WHERE  tc.identificador_responsable= rsv.identificador )
                    		else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
                    		 FROM g_operadores.operadores oa WHERE  tc.identificador_responsable = oa.identificador ) end nombre_responsable
                    		 FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=tc.identificador_responsable),
                    	op.identificador identificador_propietario,
                    	case when op.razon_social = '' then op.nombre_representante || ' '|| op.apellido_representante else op.razon_social  end nombre_propietario,
                    	(to_char(tc.fecha_registro,'dd-mm-yyyy HH24:mi')) fecha_baja,
                    	cc.nombre_concepto motivo, 
                    	COUNT(ldca.identificador_unico_producto) as cantidad_aretes,
                    	si.provincia
                    FROM
                        g_catastro.transaccion_catastro tc
                    INNER JOIN g_catastro.catastros ca ON tc.id_catastro = ca.id_catastro
                    INNER JOIN g_catastro.concepto_catastros cc ON cc.id_concepto_catastro = tc.id_concepto_catastro 
                    INNER JOIN g_catalogos.tipos_operacion top ON tc.id_tipo_operacion = top.id_tipo_operacion
                    INNER JOIN g_operadores.areas ar ON tc.id_area = ar.id_area
                    INNER JOIN g_operadores.sitios si ON ar.id_sitio = si.id_sitio 
                    INNER JOIN g_operadores.operadores op ON si.identificador_operador = op.identificador
                    INNER JOIN g_catastro.log_detalle_catastros ldca ON ca.id_catastro = ldca.id_catastro
                    WHERE
                        ldca.estado_registro = 'eliminado'
                        and ($provincia is NULL or si.provincia = $provincia)
						and ($fechaInicio is NULL or  tc.fecha_registro >= $fechaInicio)
						and ($fechaFin is NULL or  tc.fecha_registro <= $fechaFin)
                        and tc.id_concepto_catastro in (5,6,7,8,9,12)
                        GROUP BY 1,2,3,4,5,6,7,8,10;";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerCatastroPorNumeroLotePorIdAreaPorIdProducto($conexion, $idArea, $numeroLote, $idProducto, $cantidad) {
		
		$consulta = "SELECT
						c.id_catastro,
						COALESCE (dc.identificador_producto, dc.identificador_unico_producto) as identificador_producto
					  FROM 
						g_catastro.catastros c
					  INNER JOIN g_catastro.detalle_catastros dc ON c.id_catastro = dc.id_catastro
					  WHERE
						NOT EXISTS (
								SELECT 
									di.identificador 
								FROM 
									g_vacunacion.vacunacion v, 
									g_vacunacion.detalle_vacunacion dv, 
									g_vacunacion.detalle_identificadores di 
								WHERE 
									v.id_vacunacion = dv.id_vacunacion 
									and di.id_detalle_vacunacion = dv.id_detalle_vacunacion 
									and v.estado = 'vigente' 
									and di.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto) --case when dc.identificador_producto is null then dc.identificador_unico_producto else dc.identificador_producto end 
									and dv.id_tipo_operacion = c.id_tipo_operacion 
									and dv.unidad_comercial = c.unidad_comercial 
									and c.id_especie = v.id_especie
						)
					  	and c.id_area = $idArea					  	
					  	and c.numero_lote = '$numeroLote'
						and c.id_producto = $idProducto
					  	and dc.estado_registro = 'activo'
						  LIMIT $cantidad;";
						  
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function inactivarDetalleCatastroTemporal($conexion){
	    
	    $consulta = "UPDATE 
                        	g_catastro.detalle_catastros 
                        SET 
                        	estado_registro = 'inactivo', 
                        	observacion = 'De temporal a inactivo',
                        	fecha_actualizacion = 'now()'
                        WHERE 
                        	estado_registro = 'temporal'
                        	and fecha_actualizacion + interval '24 hour' <= now()" ;
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function listarAreasOperacionesPorSitio($conexion, $idSitio){

	    $res = $conexion->ejecutarConsulta("SELECT DISTINCT
												a.id_area,a.nombre_area || ' - ' || top.nombre nombre_area ,
												top.id_tipo_operacion,
												top.id_area id_area_tematica,
												top.codigo,
                                                top.nombre
											FROM
												g_operadores.areas a,
												g_operadores.operaciones ope,
												g_catalogos.tipos_operacion top,
												g_operadores.productos_areas_operacion pao
											WHERE
												ope.id_tipo_operacion = top.id_tipo_operacion
												and a.id_area=pao.id_area
												and pao.id_operacion=ope.id_operacion
												and ope.estado in ('registrado', 'porCaducar')
                                                and a.id_sitio = '$idSitio';");
	        return $res;
	}
	
	public function procesoActualizacionCatastroAutomaticoX($conexion) {
	    $res = $conexion->ejecutarConsulta ("SELECT
												c.id_area
												,c.identificador_responsable
												,c.id_catastro
												,(to_char(c.fecha_nacimiento,'dd-mm-yyyy')) fecha_nacimiento
												,(select current_date - (to_date(to_char(c.fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy')) ) dias_transcurridos
												,c.id_producto
												,c.unidad_comercial
												,p.nombre_comun producto
												,count(dc.id_detalle_catastro) cantidad
												,pa.codigo
												,c.id_tipo_operacion
												,s.identificador_operador
												,s.id_sitio
											FROM
												 g_catastro.detalle_catastros dc
												,g_catastro.catastros c
												,g_catalogos.productos p
												,g_catalogos.productos_animales pa
												,g_catalogos.etapa_animales ea
												,g_operadores.sitios s
												,g_catalogos.tipos_operacion tp
											WHERE
												p.id_producto=pa.id_producto
												and c.id_producto = pa.id_producto
												and dc.id_catastro=c.id_catastro
												and dc.estado_registro='activo'
												and c.estado_etapa is null
												and c.id_sitio=s.id_sitio
												and c.id_tipo_operacion=tp.id_tipo_operacion
												and tp.codigo!='FAE'
												and tp.id_area!='AI'
												and pa.codigo in ('PORHON','POROTE','PORACO','PORDRE','PORATE','PORONA','EQUTRO','EQULLO','EQUTRA','EQUGUA',
														  'BOVERO','BOVERA','BOVETE','BOVONA','BOVORO','BOVACA','PDEPBE','PDEPEN','PAVPBE','PAVPEN',
														  'GAPPBP','GAPPPO','GAPGPO','GRPABR','GRPPAR','GRPGAR','GRPPBR','GRPPOR','GRPGOR',
														  'GRLABR','GRLPAR','GRLGAR','GRLOBR','GRLPOR','GRLGOR')
												--and ( ((select current_date - (to_date(to_char(c.fecha_nacimiento,'dd-mm-yyyy'),'dd-mm-yyyy')) )=ea.dias_cambio_etapa and pa.id_producto=ea.id_producto) or (ea.id_producto_etapa=c.id_producto and ea.estado_etapa='etapaFinal'))
												and dc.identificador_unico_producto in ('17612-19-003055',
'17612-19-004822'


)
											GROUP BY
												c.id_area
												,c.identificador_responsable
												,c.cantidad
												,c.id_catastro
												,(to_char(c.fecha_registro,'dd-mm-yyyy'))
												,(select current_date - (to_date(to_char(c.fecha_modificacion_etapa,'dd-mm-yyyy'),'dd-mm-yyyy')) )
												,c.id_producto
												,c.unidad_comercial
												,p.nombre_comun
												,pa.codigo
												,s.identificador_operador
												,s.id_sitio
											ORDER BY c.id_catastro asc;" );
	    return $res;
	}
	
	public function imprimirReporteCatastroCero($conexion, $provincia,$canton,$parroquia, $operacion, $fechaInicio, $fechaFin) {
	    
	    $provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";
	    $canton = $canton!="" ? "'" . $canton . "'" : "NULL";
	    $parroquia = $parroquia!="" ? "'" . $parroquia . "'" : "NULL";
	    $operacion = $operacion!="" ? "'" . $operacion . "'" : "NULL";
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
                                                	s.provincia, 
                                                	s.canton, 
                                                	s.parroquia, 
                                                	tp.nombre tipo_operacion, 
                                                	s.nombre_lugar nombre_sitio, 
                                                	s.id_sitio, 
                                                	op.identificador identificacion_propietario, 
                                                	case when op.razon_social = '''' then op.nombre_representante || ' '|| op.apellido_representante else op.razon_social end nombre_propietario, 
                                                	count(dc.id_detalle_catastro)::integer cantidad 
                                                FROM 
                                                	g_catastro.catastros ca
                                                INNER JOIN g_operadores.sitios s ON ca.id_sitio=s.id_sitio 
                                                INNER JOIN g_operadores.areas a ON ca.id_area=a.id_area 
                                                INNER JOIN g_catalogos.tipos_operacion tp ON tp.id_tipo_operacion=ca.id_tipo_operacion
                                                INNER JOIN g_operadores.operadores op ON op.identificador=s.identificador_operador
                                                LEFT JOIN g_catastro.detalle_catastros dc ON ca.id_catastro=dc.id_catastro
                                                WHERE                                                	
    												($operacion is NULL or tp.id_tipo_operacion = $operacion)
    												and ($provincia is NULL or s.provincia = $provincia)
    												and ($canton is NULL or s.canton = $canton)
    												and ($parroquia is NULL or s.parroquia = $parroquia)
    												and ($fechaInicio is NULL or  ca.fecha_registro >= $fechaInicio)
    												and ($fechaFin is NULL or  ca.fecha_registro <= $fechaFin)
                                                    and dc.id_catastro IS NULL
											GROUP BY 
                                            	1, 2, 3, 4, 5, 6, 7, 8
                                            ORDER BY 1 
                                            DESC;");
	    return $res;
	}
	
	public function obtenerIdentificadoresPorCantidadCatastro($conexion, $cantidadCatastro, $identificadorInicial){

		$consulta = "SELECT 
							numero_arete
						FROM 
							g_catalogos.serie_aretes
						WHERE
							numero_arete >= '$identificadorInicial'
							and numero_arete < (SELECT 
												'EC'||lpad((split_part(numero_arete, 'EC', 2)::integer + $cantidadCatastro + (SELECT g_catastro.proceso_nuevacantidadcatastro($cantidadCatastro, '$identificadorInicial')))::text, 9, '0')
											FROM 
												g_catalogos.serie_aretes
											WHERE
												numero_arete = '$identificadorInicial')
							and estado = 'creado'
							ORDER BY numero_arete asc";

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
                    	DISTINCT top.id_tipo_operacion
                        , top.id_area id_area_tematica
                        , top.codigo
                        , top.nombre
                    FROM
                    	g_operadores.operaciones op
                    INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                    	op.estado in ('registrado', 'porCaducar')
                    	and top.id_area = 'SA'
                    	and top.codigo in " . $codigoTipoOperacion . "
                    	and op.identificador_operador = '$identificadorUsuario';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}	
	
	public function verificarSecuenciaCatastro($conexion, $idArea, $anio) {
	    
	    $consulta = "SELECT
                    	max(coalesce(secuencia_final, 0)) + 1 as secuencia_final
                     FROM
                    	g_catastro.secuencia_catastro
                     WHERE
                        id_area = $idArea
                        and anio = $anio;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function insertarSecuencialCatastroSecuencia($conexion, $idArea, $anio, $secuenciaInicial, $secuenciaFinal) {
	    
	    $consulta = "INSERT INTO
                            	g_catastro.secuencia_catastro(id_area, anio, secuencia_inicial, secuencia_final)
                            VALUES ($idArea, $anio, $secuenciaInicial, $secuenciaFinal) RETURNING id_secuencia_catastro;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}	
		
	public function buscarOperadorModificacionIdentificador($conexion, $identificadorOperador) {
	    
	    $consulta = "SELECT 
                        mi.identificador_operador
                    	, mi.id_modificacion_identificador
                    	, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
                    	, mi.habilitar_modificacion_identificador
                        , mi.estado_modificacion_identificador
                    FROM 
                    	g_catastro.modificacion_identificador mi
                    INNER JOIN g_operadores.operadores o ON mi.identificador_operador = o.identificador
                    WHERE
                    mi.identificador_operador = '" . $identificadorOperador . "' and mi.estado_modificacion_identificador='activo';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function insertarOperadorModificacionIdentificador($conexion, $identificadorOperador) {
	    
	    $consulta = "INSERT INTO g_catastro.modificacion_identificador(identificador_operador) VALUES ('" . $identificadorOperador . "');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}	
	
	public function obtenerOperadorModificacionIdentificadorPorId($conexion, $idModificacionIdentificador) {
	    
	    $consulta = "SELECT
                        mi.identificador_operador
                    	, mi.id_modificacion_identificador
                    	, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
                    	, mi.habilitar_modificacion_identificador
                        , mi.observacion_modificacion_identificador
                        , mi.estado_modificacion_identificador
                    FROM
                    	g_catastro.modificacion_identificador mi
                    INNER JOIN g_operadores.operadores o ON mi.identificador_operador = o.identificador
                    WHERE
                    mi.id_modificacion_identificador = " . $idModificacionIdentificador . " and mi.estado_modificacion_identificador='activo';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarHabilibitacionOperadorModificacionIdentificador($conexion, $idModificacionIdentificador, $habilitarModificacionIdentificador, $observacionModificacion, $identificadorResponsable) {
	    
	    $consulta = "UPDATE 
                    	g_catastro.modificacion_identificador
                    SET 
                    	habilitar_modificacion_identificador = '" . $habilitarModificacionIdentificador . "'
                    	, observacion_modificacion_identificador = '" . $observacionModificacion . "'
                    	, identificador_responsable_modificacion_identificador = '" . $identificadorResponsable . "'
                    	, fecha_registro_modificacion_identificador = 'now()'
                    WHERE 
                    	id_modificacion_identificador = " . $idModificacionIdentificador . ";";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerOperadorModificacionIdentificador($conexion) {
	    
	    $consulta = "SELECT DISTINCT
                        mi.identificador_operador
                    	, mi.estado_modificacion_identificador
                    FROM
                    	g_catastro.modificacion_identificador mi ;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarEstadoOperadorModificacionIdentificador($conexion,  $ModificacionIdentificador, $estado) {
	    
	   $consulta = "UPDATE
                    	g_catastro.modificacion_identificador
                    SET
                    	estado_modificacion_identificador = '".$estado."'
                    WHERE
                    	identificador_operador = '" . $ModificacionIdentificador . "';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function guardarDetalleCatastroProductoNRegistros($conexion, $valores) {
	    
	    $res = $conexion->ejecutarConsulta ("INSERT INTO
												g_catastro.detalle_catastros(id_catastro, identificador_producto, secuencial, identificador_unico_producto, estado_registro)
											VALUES 
												 ".$valores.";" );
	    return $res;
	}
	
	public function actualizarIdentificadorProductoNRegistros($conexion, $identificadorProducto, $idEspecie, $estado) {

	    $res = $conexion->ejecutarConsulta ( "UPDATE
												g_catalogos.serie_aretes
	   										SET
												estado='$estado'
	 										WHERE
												numero_arete = ANY(".$identificadorProducto.") AND id_especie = '$idEspecie'; " );
	    return $res;
	}
}
?>