<?php
class ControladorMovilizacionProductos{
	public function obtenerTipoUsuario($conexion,$usuario){
		$res = $conexion->ejecutarConsulta("SELECT
												p.codificacion_perfil
												,up.identificador
											FROM
												g_usuario.perfiles p,
												g_usuario.usuarios_perfiles up
											WHERE
												p.id_perfil=up.id_perfil and
												p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT', 'PFL_USUAR_CIV_PR') and
												up.identificador='".$usuario."';");
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
												em.identificador identificador_empresa,
												s.provincia
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
												and pao.id_operacion=op.id_operacion
												and op.estado in ('registrado', 'porCaducar')
												and t.codigo in $operacion
												and t.id_area='SA'
												and em.identificador=op.identificador_operador
												and em.id_empresa=e.id_empresa
												and e.estado='activo'
												and em.estado='activo'
												and e.identificador='".$usuario."';");
		return $res;
	}
	
	public function filtrarSitio($conexion, $identificadorEmisor, $nombreSitio, $provincia, $nombresPropietario, $nombreArea=null, $listaSitiosDestino=null, $tipoDestino=null){
		
	    $buscarPorArea = '';
	    $busqueda = '';
	   
	    if($tipoDestino=="matadero"){
	        $buscarPorArea = " and t.id_area='AI' and t.codigo in ('FAE')";
	    }else if($tipoDestino=="feria"){
	        $buscarPorArea = " and t.id_area='SA' and t.codigo in ('FER', 'FEA')";
	    }else if($tipoDestino=="evento"){
	        $buscarPorArea = " and t.id_area='SA' and t.codigo in ('EDR')";
	    }else{
	        $buscarPorArea = " and t.id_area='SA'";
	    }
	    
	    /*Validación para el bloqueo de un determinado sitio de destino de una determida provincia en el caso de que exista una alerta zoosanitaria
		/*if($listaSitiosDestino=='listaSitiosDestino' && $provincia=='Imbabura'){
			$busqueda=" and t.codigo='FAE' and t.id_area='AI' ";
		}*/
		
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";
		$identificadorEmisor = $identificadorEmisor!="" ? "'" . $identificadorEmisor . "'" : "NULL";
		$nombreSitio = $nombreSitio!="" ? "'%" . $nombreSitio . "%'" : "NULL";
		
		    $res = $conexion->ejecutarConsulta("SELECT DISTINCT
                                            		s.id_sitio
                                            		,s.nombre_lugar
                                            		,s.codigo_provincia
                                            		,o.identificador
                                                FROM
                                            		g_operadores.operadores o
                                                    , g_operadores.sitios s
                                                    , g_operadores.operaciones op
                                                    , g_catalogos.tipos_operacion t
                                            		, g_operadores.areas a
                                            		, g_operadores.productos_areas_operacion pao
                                    		  WHERE
                                            		o.identificador = s.identificador_operador
                                                    and s.id_sitio = a.id_sitio
                                                    and a.id_area = pao.id_area
                                                    and pao.id_operacion=op.id_operacion
                                                    and op.id_tipo_operacion = t.id_tipo_operacion
                                                    and op.estado in ('registrado', 'porCaducar')
                                                    and s.estado='creado'".$buscarPorArea.
                                                    $busqueda."
                                                    and ($provincia is NULL or s.provincia ilike $provincia)
                                                    and ($identificadorEmisor is NULL or o.identificador = $identificadorEmisor)
                                                    and ($nombreSitio is NULL or s.nombre_lugar ilike $nombreSitio);");
		    return $res;
	}

	public function listarAreasOperacionesPorSitio($conexion, $idSitio, $idArea=null, $nombreArea=null,$idProducto=null, $tipoDestino=null){
		
		$buscarPorArea = '';
		
		if($tipoDestino=="matadero"){
		    $buscarPorArea = " and top.id_area='AI' and top.codigo in ('FAE')";
		}else if($tipoDestino=="feria"){
		    $buscarPorArea = " and top.id_area='SA' and top.codigo in ('FER', 'FEA')";
		}else if($tipoDestino=="evento"){
		    $buscarPorArea = " and top.id_area='SA' and top.codigo in ('EDR')";
		}else if($tipoDestino=="operador" || $nombreArea == null){
		    $buscarPorArea = " and top.id_area='SA'";
		}
		
		$idArea = $idArea!="" ? "'" . $idArea . "'" : "NULL" ;
		$idProducto = $idProducto!="" ? " and id_producto in (". $idProducto .")" : "" ;
		
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
												and ope.estado in ('registrado', 'porCaducar') ".$buscarPorArea.
		                                          $idProducto.
												"and a.id_sitio = '$idSitio';");
		return $res;
	}

	public function listaProductosActosMovilizar($conexion, $idArea, $idTipoOperacion, $tipoDestino = null){

		if($tipoDestino == "matadero"){
			
	        $busqueda = " and dc.estado_registro in ('activo', 'temporal') ";
	        
	    }else{
			
	        $busqueda = " and dc.estado_registro='activo' ";
	        
	    }

	    $res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												p.id_producto,
												p.nombre_comun nombre_producto,
												sp.nombre nombre_subtipo_producto,
												(SELECT es.codigo FROM g_catalogos.especies es, g_catalogos.productos_animales pa where pa.id_especie=es.id_especies and pa.id_producto=c.id_producto ) as codigo_especie
											FROM 
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
								                g_operadores.operaciones ope, 
								                g_operadores.productos_areas_operacion pao,
												g_catastro.catastros c,
												g_catastro.detalle_catastros dc
								            WHERE
												c.id_catastro = dc.id_catastro"
												. $busqueda .
												"and c.id_producto=p.id_producto
												and ope.id_producto=p.id_producto
										 	    and c.id_area=pao.id_area
								                and pao.id_operacion=ope.id_operacion
												and p.id_subtipo_producto = sp.id_subtipo_producto
												and ope.estado in ('registrado', 'porCaducar')							               								               
												and ope.id_tipo_operacion='$idTipoOperacion'
								                and c.id_area = '$idArea'
								              ORDER BY 2 ASC;");
		return $res;
	}
	
	public function productosActosMovilizacion($conexion, $idArea, $idProducto, $lote, $operacion, $unidadComercial, $identificadoresProducto){

		$lote = ($lote == "") ?  "NULL" : "'" . $lote . "'" ;

		$res = $conexion->ejecutarConsulta("SELECT 
												   row_to_json(productosRPIP) ->>'id_catastro' id_catastro,
                                        	       row_to_json(productosRPIP) ->>'identif' identificador_producto,
                                        	       row_to_json(productosRPIP) ->>'estado_registro' estado_registro,
                                        	       row_to_json(productosRPIP) ->>'id_area' id_area,
                                        	       row_to_json(productosRPIP) ->>'id_tipo_operacion' id_tipo_operacion,
                                        	       row_to_json(productosRPIP) ->>'nombre_producto' nombre_producto,
                                                   row_to_json(productosRPIP) ->>'id_producto' id_producto
										    FROM ( 
												SELECT 
													c.id_catastro
													, COALESCE (dc.identificador_producto, dc.identificador_unico_producto) identif --//Se filtran todos los cerdos (lechones), ya que estos no se vacunan
													, dc.estado_registro
													, c.id_area
													, c.id_tipo_operacion
													, c.nombre_producto
													, c.id_producto
							   					FROM 
													g_catastro.catastros c
													, g_catastro.detalle_catastros dc 
												WHERE 
							     					c.id_catastro = dc.id_catastro
													and c.id_tipo_operacion = '$operacion'
													and c.id_area = '$idArea'
													and c.id_producto $idProducto
													and c.unidad_comercial = '$unidadComercial'
													and dc.estado_registro = 'activo'
													and ($lote is NULL or c.numero_lote = $lote)
											) as productosRPIP 
										WHERE
											row_to_json(productosRPIP)->>'identif' NOT IN $identificadoresProducto
										ORDER BY 2;");
		return $res;
	}
	
	public function productosActosMovilizacionVacunados($conexion,  $idArea, $idProducto, $lote , $operacion, $unidadMedida, $identificadoresProducto, $tipoDestino = null, $banderaCicloCerrado){
		
		$lote = ($lote == "") ?  "NULL" : "'" . $lote . "'" ;

		$busquedaCicloCerradoIdentificador = $banderaCicloCerrado ? " " : "and dc.identificador_producto is not null ";
		$cicloCerradoIdentificador = $banderaCicloCerrado ? "COALESCE (dc.identificador_producto, dc.identificador_unico_producto) " : "dc.identificador_producto ";
		
		
		if($tipoDestino == "matadero"){
			
			$busqueda = "SELECT
                            	c.id_catastro
                            	, " . $cicloCerradoIdentificador . " as identif
                            	, dc.estado_registro
                            	, c.id_area, c.id_tipo_operacion
                            	, c.nombre_producto, c.id_producto
                            FROM
                            	g_catastro.catastros c,g_catastro.detalle_catastros dc
                                , (SELECT 
        									di.identificador 
        								FROM 
        									g_vacunacion.vacunacion v
        									, g_vacunacion.detalle_vacunacion dv
        									, g_vacunacion.detalle_identificadores di 
        								WHERE 
        									v.id_vacunacion = dv.id_vacunacion
        									and di.id_detalle_vacunacion = dv.id_detalle_vacunacion
        									and v.estado = 'vigente'
        									and (SELECT extract(days FROM (current_date - to_char(v.fecha_vacunacion,'YYYY-MM-DD')::timestamp))) > 15
        								) as vac
                            WHERE
                                    dc.estado_registro = 'activo'
                                    " . $busquedaCicloCerradoIdentificador . "
                                    and vac.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto) 
        							and c.id_catastro = dc.id_catastro
        							and c.id_tipo_operacion = '$operacion'
                                	and c.id_area = '$idArea'
                                	and c.id_producto $idProducto
                                	and c.unidad_comercial = '$unidadMedida'
                                    and ($lote is NULL or c.numero_lote = $lote)
                            UNION
                            SELECT
                            	c.id_catastro
                            	, " . $cicloCerradoIdentificador . " as identif
                            	, dc.estado_registro
                            	, c.id_area, c.id_tipo_operacion
                            	, c.nombre_producto, c.id_producto
                            FROM
                            	g_catastro.catastros c,g_catastro.detalle_catastros dc
                            WHERE
                            
                            	dc.estado_registro = 'temporal'						
                            	and c.id_catastro = dc.id_catastro
                            	and c.id_tipo_operacion = '$operacion'
                            	and c.id_area = '$idArea'
                            	and c.id_producto $idProducto
                            	and c.unidad_comercial = '$unidadMedida'
                                and ($lote is NULL or c.numero_lote = $lote)";

		}else{

		    $busqueda = "SELECT
                            	c.id_catastro
                            	, " . $cicloCerradoIdentificador . " as identif
                            	, dc.estado_registro
                            	, c.id_area, c.id_tipo_operacion
                            	, c.nombre_producto, c.id_producto
                            FROM
                            	g_catastro.catastros c,g_catastro.detalle_catastros dc
                                , (SELECT
        									di.identificador
        								FROM
        									g_vacunacion.vacunacion v
        									, g_vacunacion.detalle_vacunacion dv
        									, g_vacunacion.detalle_identificadores di
        								WHERE
        									v.id_vacunacion = dv.id_vacunacion
        									and di.id_detalle_vacunacion = dv.id_detalle_vacunacion
        									and v.estado = 'vigente'
        									and (SELECT extract(days FROM (current_date - to_char(v.fecha_vacunacion,'YYYY-MM-DD')::timestamp))) > 15
        								) as vac
                            WHERE
                                    dc.estado_registro = 'activo'
                                    " . $busquedaCicloCerradoIdentificador . "
                                    and vac.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto)
        							and c.id_catastro = dc.id_catastro
        							and c.id_tipo_operacion = '$operacion'
                                	and c.id_area = '$idArea'
                                	and c.id_producto $idProducto
                                	and c.unidad_comercial = '$unidadMedida'
                                    and ($lote is NULL or c.numero_lote = $lote)";
											
		}

		$res = $conexion->ejecutarConsulta("SELECT
                                            	row_to_json(productosRPIP) ->>'id_catastro' id_catastro,
                                            	row_to_json(productosRPIP) ->>'identif' identificador_producto,
                                            	row_to_json(productosRPIP) ->>'estado_registro' estado_registro,
                                            	row_to_json(productosRPIP) ->>'id_area' id_area,
                                            	row_to_json(productosRPIP) ->>'id_tipo_operacion' id_tipo_operacion,
                                            	row_to_json(productosRPIP) ->>'nombre_producto' nombre_producto,
                                                row_to_json(productosRPIP) ->>'id_producto' id_producto
                                            FROM (
                                            	". $busqueda ."
    												) as productosRPIP
    											WHERE
    												row_to_json(productosRPIP) ->>'identif' NOT IN $identificadoresProducto
    											ORDER BY 2;");
		return $res;
	}

	public function listaLotesProductoMovilizacion($conexion, $idArea, $idProducto, $idTipoOperacion, $unidadMedida, $identificadoresProducto/*, $banderaLote, $tipoDestino*/){

	    $res = $conexion->ejecutarConsulta("SELECT 
												COUNT(row_to_json(productosRPIP) ->>'identif') total,
												row_to_json(productosRPIP) ->>'numero_lote' numero_lote,
                                                row_to_json(productosRPIP) ->>'id_producto' id_producto,
                                                row_to_json(productosRPIP) ->>'nombre_producto' nombre_producto
										   	FROM (
												SELECT 
													c.numero_lote
													, COALESCE (dc.identificador_producto, dc.identificador_unico_producto) identif
													, c.id_producto, c.nombre_producto
							    				FROM 
													g_catastro.catastros c
													, g_catastro.detalle_catastros dc 
												WHERE 
							     					c.id_catastro = dc.id_catastro
													and c.id_tipo_operacion = '$idTipoOperacion'
													and c.id_area = '$idArea'
													and c.id_producto $idProducto
													and c.unidad_comercial = '$unidadMedida'
													and dc.estado_registro = 'activo'
													and c.numero_lote != ''
							    				) as productosRPIP 
											WHERE 
												row_to_json(productosRPIP) ->>'identif' NOT IN $identificadoresProducto
									      	GROUP BY 2, 3, 4;");

        return $res;
	}
	
	public function listaLotesProductoMovilizacionVacunados($conexion, $idArea, $idProducto, $idTipoOperacion, $unidadMedida, $identificadoresProducto/*, $banderaLote*/, $tipoDestino, $banderaCicloCerrado){
		
	    $busqueda = "";
	    $busquedaCicloCerradoIdentificador = "";
	    $cicloCerradoIdentificador = "";
		//$valor = "";

		$busquedaCicloCerradoIdentificador = $banderaCicloCerrado ? " " : "and dc.identificador_producto is not null ";
		$cicloCerradoIdentificador = $banderaCicloCerrado ? "COALESCE (dc.identificador_producto, dc.identificador_unico_producto) " : "dc.identificador_producto ";
		
		if($tipoDestino == "matadero"){
			
			$busqueda = " SELECT
                    		c.numero_lote
                    		, " . $cicloCerradoIdentificador . " as identif
                    		, c.id_producto
                    		, c.nombre_producto
                    	FROM
                    		g_catastro.catastros c
                    		, g_catastro.detalle_catastros dc
                            , (SELECT 
                    				di.identificador 
                    			FROM 
                    				g_vacunacion.vacunacion v
                    				, g_vacunacion.detalle_vacunacion dv
                    				, g_vacunacion.detalle_identificadores di 
                    			WHERE 
                    				v.id_vacunacion = dv.id_vacunacion
                    				and di.id_detalle_vacunacion = dv.id_detalle_vacunacion
                    				and v.estado = 'vigente'
                    				and (SELECT extract(days FROM (current_date - to_char(v.fecha_vacunacion,'YYYY-MM-DD')::timestamp))) > 15
                    			) as vac 
                    	WHERE
                    		dc.estado_registro = 'activo'
                            " . $busquedaCicloCerradoIdentificador . "
                            and vac.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto)
                    		and c.id_catastro = dc.id_catastro
                    		and c.id_tipo_operacion = '$idTipoOperacion'
                    		and c.id_area = '$idArea'
                    		and c.id_producto $idProducto
                    		and c.unidad_comercial = '$unidadMedida'
                    		and c.numero_lote != ''
                    		UNION
                    		SELECT
                    			c.numero_lote
                    			, " . $cicloCerradoIdentificador . " as identif
                    			, c.id_producto
                    			, c.nombre_producto
                    		FROM
                    			g_catastro.catastros c
                    			, g_catastro.detalle_catastros dc
                    		WHERE		
                    			dc.estado_registro = 'temporal'
                    			and c.id_catastro = dc.id_catastro
                    			and c.id_tipo_operacion = '$idTipoOperacion'
                        		and c.id_area = '$idArea'
                        		and c.id_producto $idProducto
                        		and c.unidad_comercial = '$unidadMedida'
                    			and c.numero_lote != '' ";

		}else{

		    $busqueda = " SELECT
                    		c.numero_lote
                    		, " . $cicloCerradoIdentificador . " as identif
                    		, c.id_producto
                    		, c.nombre_producto
                    	FROM
                    		g_catastro.catastros c
                    		, g_catastro.detalle_catastros dc
                            , (SELECT
                    				di.identificador
                    			FROM
                    				g_vacunacion.vacunacion v
                    				, g_vacunacion.detalle_vacunacion dv
                    				, g_vacunacion.detalle_identificadores di
                    			WHERE
                    				v.id_vacunacion = dv.id_vacunacion
                    				and di.id_detalle_vacunacion = dv.id_detalle_vacunacion
                    				and v.estado = 'vigente'
                    				and (SELECT extract(days FROM (current_date - to_char(v.fecha_vacunacion,'YYYY-MM-DD')::timestamp))) > 15
                    			) as vac
                    	WHERE
                    		dc.estado_registro = 'activo'
                            " . $busquedaCicloCerradoIdentificador . "
                            and vac.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto)
                    		and c.id_catastro = dc.id_catastro
                    		and c.id_tipo_operacion = '$idTipoOperacion'
                    		and c.id_area = '$idArea'
                    		and c.id_producto $idProducto
                    		and c.unidad_comercial = '$unidadMedida'
                    		and c.numero_lote != '' ";
											
		}

	    $res = $conexion->ejecutarConsulta("SELECT
												COUNT(row_to_json(productosRPIP) ->>'identif') total,
												row_to_json(productosRPIP) ->>'numero_lote' numero_lote,
                                                row_to_json(productosRPIP) ->>'id_producto' id_producto,
                                                row_to_json(productosRPIP) ->>'nombre_producto' nombre_producto
										  	FROM ( 
												 " . $busqueda . "
                                                   ) as productosRPIP 
							    			WHERE 
												row_to_json(productosRPIP) ->>'identif'  NOT IN $identificadoresProducto
									      GROUP BY 2, 3, 4;");
	    
		return $res;
	}
	
	public function listaCantidadProducto($conexion,$idArea, $idProducto, $operacion, $unidadMedida, $identificadoresProducto){

		$res = $conexion->ejecutarConsulta("SELECT 
												count(*) total
			                               	FROM ( 
												SELECT 
													c.id_catastro,(case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end) identif
     											FROM 
													g_catastro.catastros c,g_catastro.detalle_catastros dc 
												WHERE 
     												c.id_catastro=dc.id_catastro
													and c.id_tipo_operacion='$operacion'
													and c.id_area = '$idArea'
													and c.id_producto='$idProducto'
													and c.unidad_comercial='$unidadMedida'
													and dc.estado_registro='activo'
												) as productosRPIP
     										WHERE row_to_json(productosRPIP) ->>'identif' NOT IN $identificadoresProducto ;");

		return $res;
	}
	
	public function listaCantidadProductoVacunado($conexion,$idArea, $idProducto, $operacion, $unidadMedida, $identificadoresProducto){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												count(*) total
				                           	FROM ( 
												SELECT 
													c.id_catastro,
													COALESCE (dc.identificador_producto, dc.identificador_producto end) identif
	     										FROM 
													g_catastro.catastros c,g_catastro.detalle_catastros dc
                                                    , (SELECT 
															di.identificador 
														FROM 
															g_vacunacion.vacunacion v,
															g_vacunacion.detalle_vacunacion dv,
															g_vacunacion.detalle_identificadores di 
														WHERE 
															v.id_vacunacion=dv.id_vacunacion
															and di.id_detalle_vacunacion=dv.id_detalle_vacunacion
															and v.estado='vigente'
                                                        ) 
												WHERE
													di.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto)
	     											and c.id_catastro = dc.id_catastro
													and c.id_tipo_operacion = '$operacion'
													and c.id_area = '$idArea'
													and c.id_producto ='$idProducto'
													and c.unidad_comercial = '$unidadMedida'
													and dc.estado_registro = 'activo'
												) as productosRPIP
	     									WHERE 
												row_to_json(productosRPIP) ->>'identif' NOT IN $identificadoresProducto ;");
		return $res;
	}
	
	public function buscarProductosConVacuna($conexion, $tipoRequisito,$idProducto=NULL){
		$idProducto = $idProducto!=NULL ?  "'" . $idProducto . "'" : "NULL";
		$res = $conexion->ejecutarConsulta("SELECT
												id_requisito
											FROM
												g_catalogos.requisitos_movilizacion_vacunacion
											WHERE
												estado='activo'
												and tipo='".$tipoRequisito."'
												and ($idProducto is NULL or id_producto = $idProducto);");
		return $res;
	}
	
	public function autogenerarNumerosCertificadosMovilizacion($conexion, $idProvinciaOrigen,$idProvinciaDestino){
		$fechaActual= date('dmy');
		$res = $conexion->ejecutarConsulta("SELECT
												COALESCE(MAX(secuencial)::numeric, 0)+1 AS numero
											FROM
												g_movilizacion_producto.movilizacion
											WHERE
												codigo_provincia_origen='$idProvinciaOrigen' and 
												codigo_provincia_destino='$idProvinciaDestino' and
												to_char(fecha_registro,'DDMMYY')='$fechaActual' ;");
		
		return pg_fetch_result($res, 0, 'numero');
	}
	
	public function guardarTicketMovilizacion($conexion,$sentencia){
		$res = $conexion->ejecutarConsulta(rtrim($sentencia, ","));
		return $res;
	}
	
	public function consultarEspecieProdutoConTicket($conexion, $idProducto){
	    
		$res = $conexion->ejecutarConsulta("SELECT 
												es.codigo
 											FROM 
												g_catalogos.productos_animales pa, 
												g_catalogos.especies_ticket_movilizacion es
											WHERE 
												es.id_especie=pa.id_especie and 
												es.estado_ticket = TRUE and 
												pa.id_producto='$idProducto';");
		return $res;
	}
	
	public function consultarNumeroCortoTicket($conexion, $numeroCortoTicket){
		$res = $conexion->ejecutarConsulta("SELECT
												numero_corto_ticket
											FROM
												g_movilizacion_producto.movilizacion m,
												g_movilizacion_producto.detalle_movilizacion dm,
												g_movilizacion_producto.ticket_movilizacion t
											WHERE
												m.id_movilizacion=dm.id_movilizacion
												and dm.id_detalle_movilizacion=t.id_detalle_movilizacion 
												and current_timestamp - interval '7 hours' <=m.fecha_registro
												and t.numero_corto_ticket='$numeroCortoTicket' ;");
		return $res;
	}
	
	public function guardarMovilizacion($conexion,$numeroCertificado,$lugarEmision,$oficinaEmision,$sitioOrigen,$sitioDestino,$placaTransporte,$identificadorConductor,$usuarioResponsable,  $estadoMovilizacion,$rutaCertificado, 
										$fechaInicioVigencia, $fechaFinVigencia,$codigoProvinciaOrigen, $codigoProvinciaDestino, $secuencialCertificado, $nombreConductor, $medioTranporte, $tipoSolicitud, $observacion ,$idMovilizacionDos=null,$identificadorSolicitante,$totalProductos,$estadoFiscalizacion,$fechaRegistro){

		$medioTranporte = $medioTranporte!="" ? $medioTranporte:'null';
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_movilizacion_producto.movilizacion(numero_certificado, provincia_emision,oficina_emision, sitio_origen,sitio_destino, placa_transporte, identificador_conductor, usuario_responsable, estado,ruta_certificado,
												fecha_registro,fecha_inicio_vigencia, fecha_fin_vigencia, codigo_provincia_origen, codigo_provincia_destino, secuencial, nombre_conductor, medio_transporte,tipo_solicitud, observacion, id_movilizacion_dos,identificador_solicitante,cantidad_total,estado_fiscalizacion)
											VALUES
												('$numeroCertificado','$lugarEmision','$oficinaEmision','$sitioOrigen','$sitioDestino','$placaTransporte','$identificadorConductor','$usuarioResponsable','$estadoMovilizacion','$rutaCertificado', 
												'$fechaRegistro', '$fechaInicioVigencia', '$fechaFinVigencia','$codigoProvinciaOrigen', '$codigoProvinciaDestino', '$secuencialCertificado', '$nombreConductor', $medioTranporte,'$tipoSolicitud','$observacion',$idMovilizacionDos,'$identificadorSolicitante',$totalProductos,'$estadoFiscalizacion')  RETURNING id_movilizacion");
		return $res;
	}
	
	public function guardarMovilizacionTrigger($conexion,$lugarEmision,$oficinaEmision,$sitioOrigen,$sitioDestino,$placaTransporte,$identificadorConductor,$usuarioResponsable,  $estadoMovilizacion,$rutaCertificado,
			$fechaInicioVigencia, $fechaFinVigencia,$codigoProvinciaOrigen, $codigoProvinciaDestino, $nombreConductor, $medioTranporte, $tipoSolicitud, $observacion ,$idMovilizacionDos=null,$identificadorSolicitante,$totalProductos,$estadoFiscalizacion,$fechaRegistro){
	
		$medioTranporte = $medioTranporte!="" ? $medioTranporte:'null';
		$res = $conexion->ejecutarConsulta("INSERT INTO
				g_movilizacion_producto.movilizacion(provincia_emision,oficina_emision, sitio_origen,sitio_destino, placa_transporte, identificador_conductor, usuario_responsable, estado,ruta_certificado,
				fecha_registro,fecha_inicio_vigencia, fecha_fin_vigencia, codigo_provincia_origen, codigo_provincia_destino, nombre_conductor, medio_transporte,tipo_solicitud, observacion, id_movilizacion_dos,identificador_solicitante,cantidad_total,estado_fiscalizacion)
				VALUES
				('$lugarEmision','$oficinaEmision','$sitioOrigen','$sitioDestino','$placaTransporte','$identificadorConductor','$usuarioResponsable','$estadoMovilizacion','$rutaCertificado',
				'$fechaRegistro', '$fechaInicioVigencia', '$fechaFinVigencia','$codigoProvinciaOrigen', '$codigoProvinciaDestino', '$nombreConductor', $medioTranporte,'$tipoSolicitud','$observacion',$idMovilizacionDos,'$identificadorSolicitante',$totalProductos,'$estadoFiscalizacion')  RETURNING id_movilizacion");
		return $res;
	}
	
	public function guardarDetalleMovilizacion($conexion,$idMovilizacion,$areaOrigen,$areaDestino,$producto,$cantidad,$numeroLote,$unidadComercial,$tipoOperacionOrigen, $tipoOperacionDestino){
		$numeroLote = $numeroLote!="" ? "'".$numeroLote."'" : "null";

		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_movilizacion_producto.detalle_movilizacion(id_movilizacion, area_origen, area_destino,producto, cantidad, numero_lote, unidad_comercial, tipo_operacion_origen, tipo_operacion_destino)
											VALUES
												('$idMovilizacion','$areaOrigen','$areaDestino','$producto','$cantidad',$numeroLote,'$unidadComercial','$tipoOperacionOrigen','$tipoOperacionDestino')  RETURNING id_detalle_movilizacion");
		return $res;
	}

	public function guardarDetalleIdentificadores($conexion,$sentencia){
		$res = $conexion->ejecutarConsulta(rtrim($sentencia, ","));
	
		return $res;
	}

	public function validarIdentificadorMovilizar($conexion, $idCatastro,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												dc.id_detalle_catastro
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
												,g_catalogos.productos p
											WHERE
												c.id_producto = p.id_producto
												and c.id_catastro=dc.id_catastro
												and dc.estado_registro='activo'
												and c.id_catastro='".$idCatastro."'
												and case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end ='".$identificador."'; ");
		return $res;
	}
	
	public function actualizarEstadoRegistroDetalleCatastro($conexion, $sentencia){	
		$res = $conexion->ejecutarConsulta($sentencia);
		
		return $res;
	}
	
	public function buscarNumeroCertificado($conexion, $numeroCertificado){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_movilizacion
											FROM 
												g_movilizacion_producto.movilizacion 
											WHERE 
												numero_certificado='$numeroCertificado';");
		return $res;
	}
	
	public function consultarCatastro($conexion, $idCatastro){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_catastro.catastros 
											WHERE
												id_catastro='".$idCatastro."';");
		return $res;
	}
	
	public function guardarCatastroProducto($conexion, $idSitio, $idArea, $idProducto, $cantidad  , $identificadorResponsable, $unidadMedidaPeso, $fechaModificacionEtapa, $unidadComercial,$nombreProducto, $peso, $idEspecie, $fechaNacimiento, $numeroLote, $estadoEtapa, $tipoOperacion) {
		$peso = $peso != '' ? "'$peso'" : 'null';
		$numeroLote = $numeroLote != '' ? "'$numeroLote'" : 'null';
		$unidadMedidaPeso = $unidadMedidaPeso != '' ? "'$unidadMedidaPeso'" : 'null';
		$idEspecie = $idEspecie != '' ? "'$idEspecie'" : 'null';
		$unidadComercial = $unidadComercial != '' ? "'$unidadComercial'" : 'null';
		$fechaModificacionEtapa=$fechaModificacionEtapa != '' ? "'$fechaModificacionEtapa'" : 'null';
		$fechaVencimientoVacuna=$fechaVencimientoVacuna != '' ? "'$fechaVencimientoVacuna'" : 'null';
		$estadoEtapa=$estadoEtapa != '' ? "'$estadoEtapa'" : 'null';
		$fechaNacimiento=$fechaNacimiento != '' ? "'$fechaNacimiento'" : 'null';
		
		$res = $conexion->ejecutarConsulta ("INSERT INTO
												g_catastro.catastros(id_sitio, id_area, id_producto, cantidad, identificador_responsable, unidad_medida_peso,fecha_modificacion_etapa,
												unidad_comercial,nombre_producto,peso,id_especie,fecha_nacimiento,numero_lote, estado_etapa,id_tipo_operacion )
											VALUES
												('$idSitio', '$idArea','$idProducto','$cantidad','$identificadorResponsable',$unidadMedidaPeso,$fechaModificacionEtapa,
												$unidadComercial,'$nombreProducto',$peso,$idEspecie,$fechaNacimiento,$numeroLote,$estadoEtapa,'$tipoOperacion')  RETURNING id_catastro" );
		return $res;
	}

	public function guardarDetalleCatastroProducto($conexion, $sentencia) {
		$res = $conexion->ejecutarConsulta(rtrim($sentencia, ","));
		return $res;
	}
	
	public function consultarCantidadTotalProducto($conexion, $idArea, $idProducto, $idUnidadMedidaCantidad, $idOperacion) {
		$res = $conexion->ejecutarConsulta ("SELECT
												cantidad_total
											FROM
												g_catastro.transaccion_catastro
											WHERE
												id_area = '$idArea'
												and id_producto = '$idProducto'
												and unidad_comercial = '$idUnidadMedidaCantidad'
												and id_tipo_operacion = '$idOperacion'				
											ORDER BY
												id_transaccion_catastro DESC
											LIMIT 1;" );
		return $res;
	}
	
	public function consultaConceptoCatastroXCodigo($conexion, $codigo) {
		$res = $conexion->ejecutarConsulta ("SELECT
												id_concepto_catastro
											FROM
												g_catastro.concepto_catastros
											WHERE
												codigo='$codigo';" );
		return $res;
	}
	
	public function guardarCatastroTransaccion($conexion,$idCatastro, $idArea, $idConceptoCatastro, $idProducto, $cantidadIngreso, $cantidadTotal, $idUnidadMedidaCantidad, $identificadorResponsable, $idOperacion) {
		$res = $conexion->ejecutarConsulta ("INSERT INTO
												g_catastro.transaccion_catastro(id_catastro,id_area,id_concepto_catastro,id_producto,cantidad_ingreso,cantidad_total, unidad_comercial,identificador_responsable, id_tipo_operacion)
											VALUES
												('$idCatastro','$idArea','$idConceptoCatastro','$idProducto','$cantidadIngreso','$cantidadTotal','$idUnidadMedidaCantidad','$identificadorResponsable', '$idOperacion') " );
		return $res;
	}
	
	
	public function consultarIdentificadoresIdCatastro($conexion, $idCatastro, $identificador, $estadoRegistro = null) {
	    
	    if($estadoRegistro == null){
	        $estadoRegistro = 'activo';
	    }else{
	        $estadoRegistro = $estadoRegistro;
	    }
	   
	    $res = $conexion->ejecutarConsulta ("SELECT
												 dc.identificador_producto
												,dc.identificador_unico_producto
												,dc.estado_registro
											FROM
												g_catastro.detalle_catastros dc
											WHERE
												dc.estado_registro= '$estadoRegistro'
												and dc.id_catastro='$idCatastro'
												and case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end ='$identificador';");			
				return $res;
	}
	
	public function consultarIdentificadoresIdCatastroFiscalizacion($conexion, $identificador) {
	
		$res = $conexion->ejecutarConsulta ("SELECT
												dc.id_catastro
												,dc.identificador_producto
												,dc.identificador_unico_producto
												,dc.estado_registro
											FROM
												g_catastro.detalle_catastros dc
											WHERE
												dc.estado_registro='activo'
												and case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end ='$identificador';");
		return $res;
	}
	
	public function autogenerarSecuencialDetalleCatastroProducto($conexion, $idArea) {
		$fechaActual = date ( 'y' );
		$busqueda = $idArea.$fechaActual;
		$res = $conexion->ejecutarConsulta ("SELECT
												COALESCE(MAX(dc.secuencial)::numeric, 0)+1 AS numero
											FROM
												g_catastro.catastros ca
												,g_catastro.detalle_catastros dc
											WHERE
												ca.id_catastro=dc.id_catastro
												and ca.id_area||''||to_char(ca.fecha_registro,'YY') = '$busqueda'
											GROUP BY
												to_char(ca.fecha_registro,'YY')
												,ca.id_area ;");
		
		return pg_fetch_result($res, 0, 'numero');
	}

	public function guardarCatastroTransaccionResta($conexion,$idCatastro ,$idArea, $idConceptoCatastro, $idProducto, $cantidadEgreso, $cantidadTotal, $idUnidadMedidaCantidad, $identificadorResponsable,$idOperacion) {
	  
		$res = $conexion->ejecutarConsulta ("INSERT INTO
												g_catastro.transaccion_catastro(id_catastro,id_area,id_concepto_catastro,id_producto,cantidad_egreso,cantidad_total, unidad_comercial,identificador_responsable, id_tipo_operacion)
											VALUES	('$idCatastro','$idArea','$idConceptoCatastro','$idProducto','$cantidadEgreso','$cantidadTotal','$idUnidadMedidaCantidad','$identificadorResponsable', '$idOperacion') " );
		return $res;
	}
	
	public function listaMovilizacionProducto($conexion, $identificadorOperador, $nombreOperador , $nombreSitio, $numeroCertificado, $fechaInicio, $fechaFin, $usuario,$identificadorProducto){
		$identificadorOperador  = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "NULL";
		$nombreOperador = $nombreOperador!="" ? "'%" . $nombreOperador . "%'" : "NULL";
		$nombreSitio = $nombreSitio!="" ? "'%" . $nombreSitio . "%'" : "NULL";
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
			$busquedaTabla=" ,g_movilizacion_producto.detalle_movilizacion dm ,g_movilizacion_producto.detalle_identificadores_movilizacion dim";
			$busquedaBusqueda=" and m.id_movilizacion=dm.id_movilizacion and dm.id_detalle_movilizacion=dim.id_detalle_movilizacion and dim.identificador='$identificadorProducto'";
		}else{
			$identificadorProducto="NULL";
		}
		
		if(($identificadorOperador=="NULL") && ($nombreOperador=="NULL") && ($nombreSitio=="NULL") && ($numeroCertificado=="NULL") && ($fechaInicio=="NULL") && ($fechaFin=="NULL") && ($identificadorProducto=="NULL")){
			$busqueda = " and m.fecha_registro >= current_date and m.fecha_registro < current_date+1
			and m.usuario_responsable='".$usuario."'";
		}
		
		$res = $conexion->ejecutarConsulta ( "SELECT
												m.id_movilizacion
												,m.numero_certificado
												,so.nombre_lugar sitio_origen
												,sd.nombre_lugar sitio_destino
												,m.estado
											FROM
												g_movilizacion_producto.movilizacion m
												,g_operadores.sitios so
												,g_operadores.sitios sd
												,g_operadores.operadores o
												".$busquedaTabla."
											WHERE
												so.id_sitio = m.sitio_origen
												and sd.id_sitio = m.sitio_destino
												and o.identificador=so.identificador_operador
												and ($nombreOperador is NULL or case when o.razon_social = '' then coalesce(o.nombre_representante ||' '|| o.apellido_representante ) else o.razon_social end ilike $nombreOperador)
												and ($identificadorOperador is NULL or o.identificador = $identificadorOperador)
												and ($nombreSitio is NULL or so.nombre_lugar ilike $nombreSitio)
												and ($numeroCertificado is NULL or m.numero_certificado = $numeroCertificado )
												and ($fechaInicio is NULL or m.fecha_registro >=$fechaInicio)
												and ($fechaFin is NULL or m.fecha_registro <=$fechaFin )
												".$busqueda."
												".$busquedaBusqueda."
											ORDER by m.id_movilizacion DESC;");
				return $res;
	}
	
	public function abrirMovilizacionProducto($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta ("SELECT
												m.id_movilizacion,
												m.tipo_solicitud,
												m.provincia_emision,
												m.oficina_emision,
												m.numero_certificado,
												m.usuario_responsable identificador_responsable,
												(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE m.usuario_responsable = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
												FROM g_operadores.operadores oa WHERE m.usuario_responsable = oa.identificador   ) end nombre_responsable
												FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up
												WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=m.usuario_responsable),
												to_char(m.fecha_registro,'DD/MM/YYYY HH24:MI:SS') fecha_registro,
												to_char(m.fecha_inicio_vigencia,'DD/MM/YYYY HH24:MI') fecha_inicio_vigencia,
												to_char(m.fecha_fin_vigencia,'DD/MM/YYYY HH24:MI') fecha_fin_vigencia,
												so.identificador_operador identificador_operador_origen,
												(SELECT case when oo.razon_social = '' then oo.nombre_representante ||' '|| oo.apellido_representante else oo.razon_social end) nombre_operador_origen,
												so.identificador_operador || '.' || so.codigo_provincia || so.codigo  AS codigo_sitio_origen,
												so.nombre_lugar AS nombre_sitio_origen,
												so.provincia provincia_sitio_origen,
												so.canton canton_sitio_origen,
												so.parroquia parroquia_sitio_origen,
												sd.identificador_operador || '.' || sd.codigo_provincia || sd.codigo   AS codigo_sitio_destino,
												so.id_sitio AS sitio_origen,
												sd.nombre_lugar AS nombre_sitio_destino,
												sd.provincia provincia_sitio_destino,
												sd.canton canton_sitio_destino,
												sd.parroquia parroquia_sitio_destino,
												sd.identificador_operador identificador_operador_destino,
												(SELECT case when od.razon_social = '' then od.nombre_representante ||' '|| od.apellido_representante else od.razon_social end) nombre_operador_destino,
												(SELECT tipo FROM g_catalogos.medios_transporte where id_medios_transporte=m.medio_transporte ) as medio_transporte,
												m.placa_transporte,
												m.identificador_conductor,
												m.nombre_conductor,
												m.ruta_certificado,
												m.estado,
												m.observacion,
												m.observacion_anulacion,
												m.motivo_anulacion,
												m.ruta_ticket,
												(select nombre_representante ||' '|| apellido_representante   AS nombre_solicitante from g_operadores.operadores os where os.identificador=m.identificador_solicitante),
												m.identificador_solicitante,
												m.estado_fiscalizacion
											FROM
												g_movilizacion_producto.movilizacion m ,
												g_operadores.sitios so,
												g_operadores.sitios sd,
												g_operadores.operadores oo,
												g_operadores.operadores od
												
											WHERE
												m.sitio_origen = so.id_sitio
												and m.sitio_destino = sd.id_sitio
												and oo.identificador = so.identificador_operador
												and od.identificador = sd.identificador_operador
												and m.id_movilizacion =$idMovilizacion ;" );
		return $res;
	}
	
	public function abrirDetalleMovilizacion($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												dm.id_detalle_movilizacion
												,too.nombre operacion_origen
												,tod.nombre operacion_destino
												,sp.nombre ||'-'|| pr.nombre_comun producto
												,dm.cantidad
												,(select convertir_numero_letras(dm.cantidad)) as letras
												,un.nombre unidad_comercial
												,too.codigo || too.id_area codigo_area_operacion
												,tod.codigo || tod.id_area codigo_area_operacion_destino
												,dm.area_destino
												,dm.unidad_comercial unidad
												,dm.producto id_producto
												,dm.tipo_operacion_origen
												,dm.tipo_operacion_destino
												,dm.area_origen
												,dm.area_destino
											FROM
												g_movilizacion_producto.detalle_movilizacion dm
												, g_catalogos.tipos_operacion too
												, g_catalogos.tipos_operacion tod
												, g_catalogos.productos pr
												, g_catalogos.subtipo_productos sp
												, g_catalogos.unidades_medidas un
											WHERE
												sp.id_subtipo_producto=pr.id_subtipo_producto
												and too.id_tipo_operacion=dm.tipo_operacion_origen
												and tod.id_tipo_operacion=dm.tipo_operacion_destino
												and dm.producto=pr.id_producto
												and dm.unidad_comercial=un.id_unidad_medida
												and dm.id_movilizacion=$idMovilizacion and dm.cantidad!=0;");
		return $res;
	}

	public function abrirDetalleMovilizacionIdentificadores($conexion, $idDetalleMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												identificador,id_catastro
											FROM
												g_movilizacion_producto.detalle_identificadores_movilizacion
											WHERE 
												id_detalle_movilizacion=$idDetalleMovilizacion;");
		return $res;
	}
	
	public function consultarIdentificadorProductoNoIdentificadorSistemaCatastro($conexion, $identificadorProducto){
	    
		$res = $conexion->ejecutarConsulta("SELECT 
												identificador_producto
  											FROM 
												g_catastro.detalle_catastros 
											WHERE 
												estado_registro='activo' and 
												identificador_producto='$identificadorProducto';");
		return $res;
	}
	
	public function guardarRutaTicketMovilizacion($conexion,$idMovilizacion,$rutaTicket){
	    
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_movilizacion_producto.movilizacion
  											SET 
												ruta_ticket='$rutaTicket'
 											WHERE 
												id_movilizacion='$idMovilizacion' ;");
		return $res;
	}
	
	public function actualizarCantidadTotalCertificadoMovilizacion($conexion,$idMovilizacion,$cantidadTotal){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_movilizacion_producto.movilizacion
											SET
												cantidad_total='$cantidadTotal'
											WHERE
												id_movilizacion='$idMovilizacion' ;");
				return $res;
	}
	
	public function consultarCodigoAreaOperacion($conexion,$idOperacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												(codigo||''||id_area) as codigo_area 
											FROM 
												g_catalogos.tipos_operacion 
											WHERE 
												id_tipo_operacion='$idOperacion';");
		return $res;
	}
	
	public function consultarEquivalenciaProducto($conexion,$idProducto,$areaUno,$areaDos){

		$res = $conexion->ejecutarConsulta("SELECT
												id_producto_dos
											FROM 
												g_catalogos.equivalencia_productos 
											WHERE 
												id_producto_uno $idProducto and 
												area_uno= '$areaUno' and  
												area_dos='$areaDos';");
		return $res;
	}

	public function listaUnidadComercialCatastro($conexion, $idProducto, $idOperacion,$idArea){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												um.id_unidad_medida, 
												um.nombre nombre_unidad_medida
											FROM
												g_catastro.catastros c,
												g_catalogos.unidades_medidas um
											WHERE
												c.unidad_comercial = um.id_unidad_medida 
												and c.id_producto ='$idProducto'
												and c.id_tipo_operacion='$idOperacion'
												and c.id_area = '$idArea';");
		return $res;
	}
	
	public function listaOficinasXprovincias($conexion, $codigoProvincia, $categoria){
	    
		$res = $conexion->ejecutarConsulta("SELECT 
												nombre
											FROM 
												g_catalogos.localizacion 
											WHERE 
												codigo like '$codigoProvincia%' 
												and categoria='$categoria' 
											ORDER BY nombre ASC;");
		return $res;
	}
	
	public function consultarCertificadosMovilizacionCambioEstado($conexion, $estado,$campo){		
		$res = $conexion->ejecutarConsulta("SELECT
												id_movilizacion,
												numero_certificado
  											FROM
												g_movilizacion_producto.movilizacion
											WHERE
												estado in $estado
												and	to_char($campo,'YYYY-MM-DD HH24:MI')<=to_char(current_timestamp,'YYYY-MM-DD HH24:MI');");
		
		return $res;
	}
	
	public function actualizarEstadoCertificadosMovilizacion($conexion, $idMovilizacion,$estado){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_movilizacion_producto.movilizacion
											SET 
												estado='$estado'
											WHERE
												id_movilizacion='$idMovilizacion';");
		return $res;
	}
	
	public function actualizarDatosCertificadoMovilizacion($conexion, $idMovilizacion,$estado,$motivo,$observacion){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_movilizacion_producto.movilizacion
											SET
												estado='$estado',
												observacion_anulacion='$observacion',
												motivo_anulacion='$motivo',
												fecha_anulacion=now()
											WHERE
												id_movilizacion='$idMovilizacion';");
		return $res;
	}
	
	public function listaCertificadosMovilizacionFiscalizacion($conexion, $identificadorOperador, $nombreOperador , $nombreSitio, $numeroCertificado, $fechaInicio, $fechaFin, $usuario,$tipoUsuario,$identificadorProducto,$datos='origen'){
		$identificadorOperador  = $identificadorOperador!="" ? "'" . $identificadorOperador . "'" : "NULL";
		$nombreOperador = $nombreOperador!="" ? "'%" . $nombreOperador . "%'" : "NULL";
		$nombreSitio = $nombreSitio!="" ? "'%" . $nombreSitio . "%'" : "NULL";
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
			$busquedaTabla=" ,g_movilizacion_producto.detalle_movilizacion dm ,g_movilizacion_producto.detalle_identificadores_movilizacion dim";
			$busquedaBusqueda=" and m.id_movilizacion=dm.id_movilizacion and dm.id_detalle_movilizacion=dim.id_detalle_movilizacion and dim.identificador='$identificadorProducto'";
		}else{
			$identificadorProducto="NULL";
		}
		
		

		switch ($tipoUsuario){
			case 'PFL_USUAR_INT':
			case 'PFL_USUAR_CIV_PR':					 
				
			if($datos=='origen'){
			$busqueda1="and ($nombreOperador is NULL or case when oo.razon_social = '' then coalesce(oo.nombre_representante ||' '|| oo.apellido_representante ) else oo.razon_social end ilike $nombreOperador)
				and ($identificadorOperador is NULL or oo.identificador = $identificadorOperador)
				and ($nombreSitio is NULL or so.nombre_lugar ilike $nombreSitio)";
			}else{
				$busqueda1="and ($nombreOperador is NULL or case when od.razon_social = '' then coalesce(od.nombre_representante ||' '|| od.apellido_representante ) else od.razon_social end ilike $nombreOperador)
				and ($identificadorOperador is NULL or od.identificador = $identificadorOperador)
				and ($nombreSitio is NULL or sd.nombre_lugar ilike $nombreSitio)";
					
			}
			if(($identificadorOperador=="NULL") && ($nombreOperador=="NULL") && ($nombreSitio=="NULL") && ($numeroCertificado=="NULL") && ($fechaInicio=="NULL") && ($fechaFin=="NULL") && ($identificadorProducto=="NULL")){
				$busqueda = " and m.fecha_registro >= current_date and m.fecha_registro < current_date+1
				and m.usuario_responsable='".$usuario."'";
			}
			break;
		
			case 'PFL_USUAR_EXT':
				
				$busqueda1="and ($nombreOperador is NULL or case when od.razon_social = '' then coalesce(od.nombre_representante ||' '|| od.apellido_representante ) else od.razon_social end ilike $nombreOperador)
				and ($identificadorOperador is NULL or od.identificador = $identificadorOperador)
				and ($nombreSitio is NULL or sd.nombre_lugar ilike $nombreSitio)
				and m.estado in('creado','vigente','caducado')";
				
				if(($identificadorOperador=="NULL") && ($nombreOperador=="NULL") && ($nombreSitio=="NULL") && ($numeroCertificado=="NULL") && ($fechaInicio=="NULL") && ($fechaFin=="NULL") && ($identificadorProducto=="NULL")){
					$busqueda = " and m.fecha_registro >= current_date and m.fecha_registro < current_date+1
					and od.identificador='".$usuario."'
					and m.estado in ('creado','vigente','caducado')";
				}
				
			break;
			
		}
	
	
		$res = $conexion->ejecutarConsulta ("SELECT
												m.id_movilizacion
												,m.numero_certificado
												,so.nombre_lugar sitio_origen
												,sd.nombre_lugar sitio_destino
												,m.estado
												,m.estado_fiscalizacion
											FROM
												g_movilizacion_producto.movilizacion m
												,g_operadores.sitios so
												,g_operadores.sitios sd
												,g_operadores.operadores oo
												,g_operadores.operadores od
												".$busquedaTabla."
											WHERE
												so.id_sitio = m.sitio_origen
												and sd.id_sitio = m.sitio_destino
												and oo.identificador=so.identificador_operador
												and od.identificador=sd.identificador_operador
												and ($numeroCertificado is NULL or m.numero_certificado = $numeroCertificado )
												and ($fechaInicio is NULL or m.fecha_registro >=$fechaInicio)
												and ($fechaFin is NULL or m.fecha_registro <=$fechaFin )
												".$busqueda1."
												".$busqueda."
												".$busquedaBusqueda."
											ORDER by m.id_movilizacion DESC;");
		return $res;
	}
	
	
	public function listaFiscalizacionXmovilizacion($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												mf.id_movilizacion_fiscalizacion, 
												mf.id_movilizacion, 
												mf.numero_fiscalizacion,
												mf.fecha_fiscalizacion,
												(SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE mf.usuario_responsable = rsv.identificador )
												else (SELECT case when oa.razon_social = '' then UPPER(( oa.nombre_representante::TEXT||' '::TEXT) || oa.apellido_representante::TEXT ) else UPPER(oa.razon_social) end FROM g_operadores.operadores oa WHERE mf.usuario_responsable = oa.identificador   ) end fiscalizador
											FROM 
												g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=mf.usuario_responsable),
												mf.resultado_fiscalizacion, mf.accion_correctiva,
												mf.observacion
												FROM g_movilizacion_producto.movilizacion_fiscalizaciones mf
											WHERE 
												mf.estado='activo'
												and mf.id_movilizacion='$idMovilizacion'
											ORDER BY 1 ASC;");
				return $res;
	}
	
	public function autogenerarNumerosFiscalizacionMovilizacion($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												COALESCE(MAX(numero_fiscalizacion)::numeric, 0)+1 AS numero
											FROM
												g_movilizacion_producto.movilizacion_fiscalizaciones
											WHERE
												id_movilizacion='$idMovilizacion';");
		
		return pg_fetch_result($res, 0, 'numero');
	}

	public function guardarNuevoFiscalizacion($conexion, $idMovilizacion, $numeroFiscalizacion, $fechaFiscalizacion, $resultadoFiscalizacion, $accionCorrectiva, $estado , $usuarioResponsable, $observacion, $lugarFiscalizacion, $numeroAnimales, $justificacion = NULL){
		
	    $res = $conexion->ejecutarConsulta("INSERT INTO 
												g_movilizacion_producto.movilizacion_fiscalizaciones(id_movilizacion, numero_fiscalizacion, fecha_fiscalizacion, resultado_fiscalizacion, accion_correctiva,estado, usuario_responsable,observacion, lugar_fiscalizacion, cantidad_animales, justificacion)
											VALUES ($idMovilizacion, $numeroFiscalizacion,'$fechaFiscalizacion','$resultadoFiscalizacion','$accionCorrectiva', '$estado','$usuarioResponsable', '$observacion', '$lugarFiscalizacion', '$numeroAnimales', '$justificacion');");
		return $res;
	}
	
	public function actualizarCantidadDetalleMovilizacion($conexion, $idDetalleMovilizacion, $cantidad){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_movilizacion_producto.detalle_movilizacion
											SET 
												cantidad='$cantidad'
											WHERE 
												id_detalle_movilizacion='$idDetalleMovilizacion';");
		return $res;		
	}
	
	public function eliminarDetalleIdentificadorMovilizacion($conexion, $idDetalleMovilizacion,$idDetalleIdentificadorMovilizacion){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_movilizacion_producto.detalle_identificadores_movilizacion
											WHERE 
												id_detalle_movilizacion=$idDetalleMovilizacion and 
												identificador in $idDetalleIdentificadorMovilizacion ;");
		return $res;
	}
	
	
	public function eliminarDetalleIdentificadorTicketMovilizacion($conexion,$idDetalleMovilizacion, $idDetalleIdentificadorMovilizacion){

	    $res = $conexion->ejecutarConsulta("DELETE FROM 
												g_movilizacion_producto.ticket_movilizacion
											WHERE 
												id_detalle_movilizacion=$idDetalleMovilizacion and
												identificador_producto in $idDetalleIdentificadorMovilizacion;");
		return $res;
	}

	public function consultarDatosOrigenDestinoMovilizacion($conexion, $idDetalleIdentificadoresMovilizacion,$idDetalleMovilizacion=NULL){
		$busqueda=$idDetalleMovilizacion!=null ? 'and dm.id_detalle_movilizacion='.$idDetalleMovilizacion : '';
		
		$res = $conexion->ejecutarConsulta("SELECT 
												dim.id_detalle_identificadores_movilizacion,
												dim.identificador,
												dim.id_catastro,
												m.sitio_origen,
												m.sitio_destino,
												dm.area_origen,
												dm.area_destino, 
      											dm.producto,
												dm.numero_lote,
												dm.unidad_comercial,
												dm.tipo_operacion_origen, 
      											dm.tipo_operacion_destino
  											FROM 
												g_movilizacion_producto.detalle_movilizacion dm ,
 												g_movilizacion_producto.detalle_identificadores_movilizacion dim,
  												g_movilizacion_producto.movilizacion m 
											WHERE m.id_movilizacion=dm.id_movilizacion 
												and dm.id_detalle_movilizacion=dim.id_detalle_movilizacion
												".$busqueda."
 												and dim.identificador in $idDetalleIdentificadoresMovilizacion ;");
		return $res;
	}
	
	public function consultarIdDetalleIdentificadoresProducto($conexion, $idMovilizacion, $idDetalleIdentificadoresMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT distinct
												--dim.id_detalle_identificadores_movilizacion,
												dm.id_detalle_movilizacion
											FROM 
												g_movilizacion_producto.detalle_movilizacion dm ,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim,
												g_movilizacion_producto.movilizacion m 
											WHERE m.id_movilizacion=dm.id_movilizacion and 
												dm.id_detalle_movilizacion=dim.id_detalle_movilizacion and
												dim.identificador in $idDetalleIdentificadoresMovilizacion and 
												m.id_movilizacion='$idMovilizacion';");
		return $res;
	}
	
	public function consultarDatosDestinoMovilizacion($conexion, $identificador, $idMovilizacionDos){
		$res = $conexion->ejecutarConsulta("SELECT 
												m.sitio_origen, 
												m.sitio_destino, 
												dm.area_origen, 
												dm.area_destino, 
								      		 	dm.producto,
												dm.numero_lote, 
												dm.unidad_comercial, 
												dm.tipo_operacion_origen, 
								       			dm.tipo_operacion_destino, 
												dim.identificador,
												dm.id_detalle_movilizacion
											FROM 
												g_movilizacion_producto.detalle_movilizacion dm ,
											  	g_movilizacion_producto.detalle_identificadores_movilizacion dim,
											  	g_movilizacion_producto.movilizacion m 
											WHERE m.id_movilizacion=dm.id_movilizacion 
												and dm.id_detalle_movilizacion=dim.id_detalle_movilizacion
											  	and dim.identificador in $identificador 
												and m.id_movilizacion='$idMovilizacionDos';");
				return $res;
	}
	
	
	public function consultarDatosCatastro($conexion, $idArea,$idUnidadComercial,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT	
												c.*,
												dc.id_detalle_catastro,
												dc.secuencial,
												dc.estado_registro,
												dc.identificador_unico_producto,
												dc.identificador_producto
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and c.id_area='$idArea'
												and c.unidad_comercial='$idUnidadComercial'
												and dc.estado_registro='activo'
												and case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end ='$identificador';");
		return $res;
	}
	
	public function consultarDatosCatastroValidar($conexion, $idArea,$idUnidadComercial,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												c.id_catastro
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and c.id_area='$idArea'
												and c.unidad_comercial='$idUnidadComercial'
												and dc.estado_registro='activo'
												and case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end ='$identificador';");
				return $res;
	}
	
	public function verificarCatastro($conexion, $idArea,$idUnidadComercial,$idCatastro,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												c.id_catastro
											FROM
												g_catastro.catastros c
												,g_catastro.detalle_catastros dc
											WHERE
												c.id_catastro=dc.id_catastro
												and c.id_area='$idArea'
												and c.unidad_comercial='$idUnidadComercial'
												and c.id_catastro='$idCatastro'
												and dc.estado_registro='activo'
												and case when dc.identificador_producto is null then  dc.identificador_unico_producto else dc.identificador_producto end ='$identificador';");
				return $res;
	}
	
	public function obtenerRutaCertificadoMovilizacion($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												ruta_certificado
											FROM 
												g_movilizacion_producto.movilizacion 
											WHERE 
												id_movilizacion='$idMovilizacion';");
		return $res;
	}
	
	public function obtenerRutaTicketMovilizacion($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
											ruta_ticket
										FROM
											g_movilizacion_producto.movilizacion
										WHERE
											id_movilizacion='$idMovilizacion';");
		return $res;
	}
	public function obtenerIdVueltaCertificadoMovilizacion($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												id_movilizacion_dos
											FROM
												g_movilizacion_producto.movilizacion
											WHERE
												id_movilizacion='$idMovilizacion';");
		return $res;
	}
	public function actualizarFechaCertificadoMovilizacion($conexion,$idMovilizacion,$idMovilizacionDos,$fechaFinVigencia){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_movilizacion_producto.movilizacion
											SET
												id_movilizacion_dos='$idMovilizacionDos',
												fecha_fin_vigencia='$fechaFinVigencia'
											WHERE
												id_movilizacion='$idMovilizacion' ;");
		return $res;
	}
	
	public function cantidadDetalleMovilizacion($conexion,$idMovilizacion,$identificadores=NULL){
		
		$buscarIdentificadores = $identificadores!=NUll ? 'and dim.identificador in '.$identificadores  : '';
	
		$res = $conexion->ejecutarConsulta("SELECT 
												dm.id_detalle_movilizacion 
												,dm.cantidad
												,dm.producto id_producto
												,count(dm.cantidad) cantidad_restada
								  			FROM 
												g_movilizacion_producto.detalle_movilizacion dm ,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim
											WHERE 
												dm.id_detalle_movilizacion=dim.id_detalle_movilizacion
												and dm.id_movilizacion='$idMovilizacion' 
												".$buscarIdentificadores." 
											GROUP BY dm.id_detalle_movilizacion ;");
		
		return $res;
	}
	
	
	public function cantidadTotalDetalleMovilizacion($conexion,$idDetalleMovilizacion){
	
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												dm.id_detalle_movilizacion, 
												dm.cantidad,
												dm.producto
											FROM 
												g_movilizacion_producto.detalle_movilizacion dm ,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim
											WHERE
												dm.id_detalle_movilizacion=dim.id_detalle_movilizacion
												and dm.id_detalle_movilizacion='$idDetalleMovilizacion';");
		return $res;
	}
	
	public function obtenerMaximoFiscalizacionOperador($conexion,$idOperador){
		$res = $conexion->ejecutarConsulta("SELECT 
													max(mf.id_movilizacion_fiscalizacion) id_movilizacion_fiscalizacion
												FROM 
													g_movilizacion_producto.movilizacion_fiscalizaciones mf,
													g_movilizacion_producto.movilizacion m,
													g_operadores.sitios si
												WHERE 
													si.identificador_operador='$idOperador'
													and si.id_sitio=m.sitio_origen
													and mf.id_movilizacion=m.id_movilizacion
												ORDER BY 1 DESC
												LIMIT 1 ;");
		return $res;
	}
	
	public function consultarFiscalizacionAccionCorrectiva($conexion,$idFiscalizacion,$accionCorrectiva){
		$res = $conexion->ejecutarConsulta("SELECT  
												accion_correctiva
											FROM 
												g_movilizacion_producto.movilizacion_fiscalizaciones 
											WHERE
												id_movilizacion_fiscalizacion='$idFiscalizacion' and 
												accion_correctiva='$accionCorrectiva';");
				return $res;
	}

	public function consultarDatosDetalleMovilizacion ($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT 
												dim.identificador, 
												dm.area_destino,
												dm.unidad_comercial,
												dm.tipo_operacion_origen,
												dm.tipo_operacion_destino
											FROM 
												g_movilizacion_producto.detalle_movilizacion dm ,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim
											WHERE 
												dm.id_detalle_movilizacion=dim.id_detalle_movilizacion
												and dm.id_movilizacion= $idMovilizacion;");
		return $res;
	}
	
	public function consultarDatosDetalleMovilizacionXIdDetalle ($conexion, $idDetalleMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												dm.id_detalle_movilizacion, 
												dim.identificador,
												m.sitio_origen,
												m.sitio_destino,
												dm.area_origen,
												dm.area_destino,
												dm.cantidad,
												dm.producto,
												dm.numero_lote,
												dm.unidad_comercial,
												dm.tipo_operacion_origen,
												dm.tipo_operacion_destino
											FROM
												g_movilizacion_producto.detalle_movilizacion dm ,
												g_movilizacion_producto.detalle_identificadores_movilizacion dim,
												g_movilizacion_producto.movilizacion m
											WHERE
												m.id_movilizacion=dm.id_movilizacion
												and dm.id_detalle_movilizacion=dim.id_detalle_movilizacion
												and dm.id_detalle_movilizacion= $idDetalleMovilizacion;");
		return $res;
	}
	
	public function imprimirReporteCertificadosMovilizacion($conexion, $provincia,$canton,$parroquia, $operacion, $estado,$fechaInicio, $fechaFin) {
	
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";
		$canton = $canton!="" ? "'" . $canton . "'" : "NULL";
		$parroquia = $parroquia!="" ? "'" . $parroquia . "'" : "NULL";
		$estado = $estado!="" ? "'" . $estado . "'" : "NULL";
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

		$res = $conexion->ejecutarConsulta("SELECT 
                                                mo.numero_certificado, 
                                                mo.provincia_emision,
                                                mo.oficina_emision, 
                                                too.nombre operacion_origen, 
                                                sio.provincia provincia_origen, 
                                                sio.codigo_provincia codigo_provincia_origen, 
                                                sio.canton canton_origen, 
                                                sio.parroquia parroquia_origen, 
                                                sio.nombre_lugar sitio_origen, 
                                                opo.identificador identificador_operador_origen, 
                                                opo.razon_social razon_social_operador_origen, 
                                                opo.nombre_representante || ' ' || opo.apellido_representante nombre_operador_origen, 
                                                tod.nombre operacion_destino, 
                                                sid.provincia provincia_destino, 
                                                sid.codigo_provincia  codigo_provincia_destino,
                                                sid.canton canton_destino, 
                                                sid.parroquia parroquia_destino, 
                                                sid.nombre_lugar sitio_destino, 
                                                opd.identificador identificador_operador_destino, 
                                                opd.razon_social razon_social_operador_destino, 
                                                opd.nombre_representante || ' ' || opd.apellido_representante nombre_operador_destino, 
                                                mo.usuario_responsable identificacion_usuario_responsable, 
                                                (SELECT 
                                                	case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text) 
                                                FROM 
                                                	g_uath.ficha_empleado rsv 
                                                WHERE 
                                                	mo.usuario_responsable= rsv.identificador ) 
                                                	else 
                                                	(SELECT 
                                                		case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end 
                                                	FROM 
                                                		g_operadores.operadores oa 
                                                	WHERE 
                                                		mo.usuario_responsable = oa.identificador ) 
                                                		end nombre_usuario_responsable 
                                                	FROM 
                                                		g_usuario.perfiles p, 
                                                		g_usuario.usuarios_perfiles up 
                                                	WHERE 
                                                		p.id_perfil=up.id_perfil 
                                                		and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') 
                                                		and up.identificador=mo.usuario_responsable), 
                                                pr.nombre_comun producto, 
                                                dm.cantidad, 
                                                mo.identificador_conductor identificacion_conductor, 
                                                mo.nombre_conductor, 
                                                (SELECT 
                                                	tipo 
                                                FROM 
                                                	g_catalogos.medios_transporte 
                                                WHERE 
                                                	id_medios_transporte=mo.medio_transporte) medio_transporte, 
                                                	mo.observacion, 
                                                	mo.placa_transporte placa_transporte, 
                                                	(to_char(mo.fecha_registro,'dd-mm-yyyy HH24:mi:ss')) fecha_registro, 
                                                	(to_char(mo.fecha_inicio_vigencia,'dd-mm-yyyy HH24:mi')) fecha_inicio_vigencia, 
                                                	(to_char(mo.fecha_fin_vigencia,'dd-mm-yyyy HH24:mi')) fecha_fin_vigencia, mo.estado, 
                                                	(to_char(mo.fecha_anulacion,'dd-mm-yyyy HH24:mi')) fecha_anulacion, mo.observacion_anulacion, mo.motivo_anulacion, 
                                                	(select nombre_representante ||' '|| apellido_representante AS nombre_solicitante from g_operadores.operadores os where os.identificador=mo.identificador_solicitante), 
                                                	mo.identificador_solicitante 
                                                FROM 
                                                	g_movilizacion_producto.movilizacion mo, 
                                                	g_operadores.operadores opo, 
                                                	g_operadores.operadores opd, 
                                                	g_operadores.sitios sio,
                                                	g_operadores.sitios sid, 
                                                	g_movilizacion_producto.detalle_movilizacion dm, 
                                                	g_catalogos.productos pr,
                                                	g_catalogos.subtipo_productos stp, 
                                                	g_catalogos.tipos_operacion too, 
                                                	g_catalogos.tipos_operacion tod 
                                                WHERE 
                                                	mo.sitio_origen=sio.id_sitio 
                                                	and sio.identificador_operador=opo.identificador 
                                                	and mo.sitio_destino=sid.id_sitio 
                                                	and sid.identificador_operador=opd.identificador 
                                                	and dm.id_movilizacion=mo.id_movilizacion 
                                                	and pr.id_producto=dm.producto 
                                                	and too.id_tipo_operacion=dm.tipo_operacion_origen 
                                                	and tod.id_tipo_operacion=dm.tipo_operacion_destino 
                                                	and pr.id_subtipo_producto=stp.id_subtipo_producto 
                                                	and stp.id_subtipo_producto= (SELECT id_subtipo_producto FROM g_catalogos.subtipo_productos WHERE codificacion_subtipo_producto = 'SUB_TIPO_SA_PORC') and
    												($operacion is NULL or too.id_tipo_operacion = $operacion) and
    												($provincia is NULL or sio.provincia = $provincia) and
    												($canton is NULL or sio.canton = $canton) and
    												($parroquia is NULL or sio.parroquia = $parroquia) and
    												($estado is NULL or mo.estado = $estado) and
    												($fechaInicio is NULL or  mo.fecha_registro >= $fechaInicio) and
    												($fechaFin is NULL or  mo.fecha_registro <= $fechaFin);");				
		return $res;
	}
	
	public function imprimirReporteFiscalizacionesMovilizacion($conexion, $provincia,$canton,$parroquia,$resultado,$fechaInicio, $fechaFin) {
	
		$provincia = $provincia!="" ? "'" . $provincia . "'" : "NULL";
		$canton = $canton!="" ? "'" . $canton . "'" : "NULL";
		$parroquia = $parroquia!="" ? "'" . $parroquia . "'" : "NULL";
		$resultado = $resultado!="" ? "'" . $resultado . "'" : "NULL";
		$fechaInicio = $fechaInicio != "" ? "'" . $fechaInicio . "'" : "NULL";
		if ($fechaFin != "") {
			$fechaFin = str_replace ( "/", "-", $fechaFin );
			$fechaFin = strtotime ( '+1 day', strtotime ( $fechaFin ) );
			$fechaFin = date ( 'd-m-Y', $fechaFin );
			$fechaFin = "'" . $fechaFin . "'";
		} else {
			$fechaFin = "NULL";
		}	
	
		$res = $conexion->ejecutarConsulta("SELECT 
                                            	mo.numero_certificado, 
                                            	(SELECT 
                                            		case when p.codificacion_perfil='PFL_USUAR_INT' then 'usuario interno' else ('usuario externo' ) end tipo_usuario 
                                            	FROM 
                                            		g_usuario.perfiles p, 
                                            		g_usuario.usuarios_perfiles up 
                                            	WHERE 
                                            		p.id_perfil = up.id_perfil 
                                            		and p.codificacion_perfil 
                                            		in ('PFL_USUAR_INT','PFL_USUAR_EXT') 
                                            		and up.identificador = mf.usuario_responsable), 
                                            	mf.usuario_responsable identificacion_responsable_fiscalizacion, 
                                            	(SELECT 
                                            		case when p.codificacion_perfil = 'PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text) 
                                            	FROM 
                                            		g_uath.ficha_empleado rsv 
                                            	WHERE 
                                            		mf.usuario_responsable= rsv.identificador ) else 
                                            	(SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end 
                                            	FROM 
                                            		g_operadores.operadores oa 
                                            	WHERE 
                                            		mf.usuario_responsable = oa.identificador ) end nombre_responsable_fiscalizacion 
                                            	FROM 
                                            		g_usuario.perfiles p, g_usuario.usuarios_perfiles up 
                                            	WHERE 
                                            		p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=mf.usuario_responsable), 
                                            	(SELECT 
                                            		case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT rsv.provincia FROM g_uath.datos_contrato rsv 
                                            	WHERE 
                                            		mf.usuario_responsable= rsv.identificador and estado=1 ) else 
                                            	(SELECT 
                                            		oa.provincia 
                                            	FROM 
                                            		g_operadores.operadores oa 
                                            	WHERE 
                                            		mf.usuario_responsable = oa.identificador ) end provincia_responsable_fiscalizacion 
                                            	FROM 
                                            		g_usuario.perfiles p, g_usuario.usuarios_perfiles up 
                                            	WHERE 
                                            		p.id_perfil=up.id_perfil 
                                            		and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') 
                                            		and up.identificador=mf.usuario_responsable), 
                                            		sio.provincia provincia_sitio_origen, 
                                            		sio.codigo_provincia codigo_provincia_origen,
                                            		sio.canton canton_sitio_origen, 
                                            		sio.parroquia parroquia_sitio_origen, 
                                            		sio.nombre_lugar nombre_sitio_origen, 
                                            		sio.identificador_operador identificacion_propietario, 
                                            		CASE WHEN opo.razon_social::text = ''::text 
                                            		THEN upper((opo.nombre_representante::text || ' '::text) || opo.apellido_representante::text)::character varying::text 
                                            		ELSE upper(opo.razon_social::text)END AS nombre_propietario, 
                                            		mf.resultado_fiscalizacion resultado, 
                                            		mf.accion_correctiva, 
                                            		mf.observacion, 
                                            		to_char(mf.fecha_registro,'yyyy-mm-dd hh24:mi:ss') fecha_registro, 
                                            		mf.fecha_fiscalizacion, 
                                            		mo.estado_fiscalizacion, 
                                            		mf.lugar_fiscalizacion, 
                                            		mf.cantidad_animales, 
                                            		mo.cantidad_total 
                                            FROM 
                                            	g_movilizacion_producto.movilizacion_fiscalizaciones mf, 
                                            	g_movilizacion_producto.movilizacion mo , 
                                            	g_operadores.sitios sio, 
                                            	g_operadores.operadores opo 
                                            WHERE 
                                            	mo.id_movilizacion = mf.id_movilizacion 
                                            	and sio.id_sitio = sitio_origen 
                                            	and opo.identificador = sio.identificador_operador and
								 				($provincia is NULL or sio.provincia = $provincia) and
												($canton is NULL or sio.canton = $canton) and
												($parroquia is NULL or sio.parroquia = $parroquia) and
												($resultado is NULL or mf.resultado_fiscalizacion = $resultado) and
												($fechaInicio is NULL or mf.fecha_registro >= $fechaInicio) and
												($fechaFin is NULL or mf.fecha_registro <= $fechaFin);");
	
		return $res;
	}
	
	public function abrirDetalleMovilizacionIdentificadoresAgregados($conexion, $idDetalleMovilizacion,$identificadoresAgregados){
		
		
		$res = $conexion->ejecutarConsulta("SELECT
												id_detalle_identificadores_movilizacion,
												id_detalle_movilizacion,
												identificador
											FROM
												g_movilizacion_producto.detalle_identificadores_movilizacion
											WHERE
												id_detalle_movilizacion=$idDetalleMovilizacion
												and identificador not in $identificadoresAgregados ;");
		return $res;
	}
	
	public function imprimirIdentificadoresProducto($idDetalleDetalleMovilizacion,$idDetalleIdentificadorMovilizacion, $identificador,$contador) {
		return '<tr  id="r_' . $idDetalleIdentificadorMovilizacion . '">' .
				'<td >' . $contador . '<input type=hidden id=hCodigoDetalleMovilizacion name=hCodigoDetalleMovilizacion[] value='.$idDetalleDetalleMovilizacion.' /><input type=hidden id=hCodigoIdentificadorMovilizacion name=hCodigoIdentificadorMovilizacion[] value='.$idDetalleIdentificadorMovilizacion.' /></td>' .
				'<td>' . $identificador . '<input type=hidden id=hDetalleIdentificador name=hDetalleIdentificador[] value='.$identificador.' /></td>' .
				'<td class="borrar">'.
				"<button type='button' onclick='quitarDetalleIdentificadoresMovilizacion(\"#r_".$idDetalleIdentificadorMovilizacion."\")' class='icono'></button></td>".
				'</td>'.
				'</tr>';
	}
	
	public function consultarSolicitanteCertificadoMovilizacion($conexion, $busquedaTexto, $resultadoMaximo) {
		$res = $conexion->ejecutarConsulta ("SELECT 
												identificador identificador_operador,
												(case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end) nombre_operador
											FROM 
												g_operadores.operadores 
											WHERE 
												identificador ilike '$busquedaTexto%' 
											ORDER BY 1 ASC LIMIT $resultadoMaximo ;" );
		return $res;
	}
	
	public function actualizarEstadoFiscalizacionMovilizacion($conexion, $idMovilizacion,$estado){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_movilizacion_producto.movilizacion
											SET
												estado_fiscalizacion='$estado'
											WHERE
												id_movilizacion='$idMovilizacion';");
		return $res;
	}
	
	public function consultarCertificadosReimpresion($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_movilizacion, numero_certificado, ruta_certificado, SUBSTRING(ruta_certificado,52) nombre_archivo ,ruta_ticket,
												SUBSTRING(ruta_ticket,53) nombre_ticket , date_part('YEAR',fecha_registro) ||'/'|| date_part('MONTH'::text,fecha_registro) ||'/'|| date_part('DAYS'::text,fecha_registro) fecha
											FROM 
												g_movilizacion_producto.movilizacion 
											
											WHERE 
												SUBSTRING(ruta_certificado,0,64)='aplicaciones/movilizacionProducto/documentos/guias/Zoosanitaria' order by 1 asc limit 4 ");
		
		return $res;
	}
	
	public function actualizarRutaArchivoMovilizacion($conexion, $idMovilizacion,$ruta){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_movilizacion_producto.movilizacion
											SET
												ruta_certificado='$ruta'
											WHERE
												id_movilizacion='$idMovilizacion';");
		return $res;
	}
	
	public function actualizarRutaArchivoTicketMovilizacion($conexion, $idMovilizacion,$ruta){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_movilizacion_producto.movilizacion
											SET
												ruta_ticket='$ruta'
											WHERE
												id_movilizacion='$idMovilizacion';");
		return $res;
	}
	
	public function obtenerNumeroCertificado($conexion, $idMovilizacion){
		$res = $conexion->ejecutarConsulta("SELECT
												numero_certificado
											FROM
												g_movilizacion_producto.movilizacion
											WHERE
												id_movilizacion='$idMovilizacion';");
				return $res;
	}
	public function listarProductosConVacuna($conexion, $tipoRequisito){
	   
	    $consulta = "SELECT
												id_producto
											FROM
												g_catalogos.requisitos_movilizacion_vacunacion
											WHERE
												estado='activo'
												and tipo='$tipoRequisito';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerProductosSinVacunacionPorLote($conexion, $idArea, $idProducto, $idTipoOperacion, $unidadMedida, $numeroLote, $cantidad){
	    
	    $consulta = "SELECT
                        c.numero_lote
						, COALESCE (dc.identificador_producto, dc.identificador_unico_producto) identificador_producto
						, c.id_producto
                    FROM
                        g_catastro.catastros c
						, g_catastro.detalle_catastros dc
                    WHERE
                        c.id_catastro = dc.id_catastro
                        and c.id_tipo_operacion = $idTipoOperacion
                        and c.id_area = $idArea
                        and c.id_producto = $idProducto
                        and c.unidad_comercial = '$unidadMedida'
                        and dc.estado_registro = 'activo'
						and c.numero_lote = '$numeroLote'
					LIMIT $cantidad;";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	
	public function obtenerProductosConVacunacionPorLote($conexion, $idArea, $idProducto, $idTipoOperacion, $unidadMedida, $numeroLote, $cantidad, $tipoDestino, $banderaCicloCerrado){

	    $busquedaCicloCerradoIdentificador = $banderaCicloCerrado ? " " : "and dc.identificador_producto is not null ";
	    $cicloCerradoIdentificador = $banderaCicloCerrado ? "COALESCE (dc.identificador_producto, dc.identificador_unico_producto) " : "dc.identificador_producto ";
	    
	    
		if($tipoDestino == "matadero"){
		    
		    $busqueda = "SELECT
                            c.numero_lote
                            , " . $cicloCerradoIdentificador . " as identificador_producto
                            , c.id_producto 
                            FROM
                            g_catastro.catastros c
                            , g_catastro.detalle_catastros dc
                            , (SELECT 
                            		di.identificador 
                            	FROM 
                            		g_vacunacion.vacunacion v
                            		, g_vacunacion.detalle_vacunacion dv
                            		, g_vacunacion.detalle_identificadores di 
                            	WHERE 
                            		v.id_vacunacion = dv.id_vacunacion
                            		and di.id_detalle_vacunacion = dv.id_detalle_vacunacion
                            		and v.estado = 'vigente'
                            		and (SELECT extract(days FROM (current_date - to_char(v.fecha_vacunacion,'YYYY-MM-DD')::timestamp))) > 15
                            	) vac
                            WHERE
                                dc.estado_registro = 'activo'
                                " . $busquedaCicloCerradoIdentificador . "
                                and vac.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto)
                                and c.id_catastro = dc.id_catastro
                                and c.id_tipo_operacion = $idTipoOperacion
        						and c.id_area = $idArea
        						and c.id_producto = $idProducto
        						and c.unidad_comercial = '$unidadMedida'
        						and c.numero_lote = '$numeroLote' 
                            UNION
                            SELECT
                            	c.numero_lote
                            	, dc.identificador_producto
                            	, c.id_producto
                            FROM
                            	g_catastro.catastros c
                            	, g_catastro.detalle_catastros dc
                            WHERE		
                            	dc.estado_registro = 'temporal'
                            	and c.id_catastro = dc.id_catastro
                            	and c.id_tipo_operacion = $idTipoOperacion
        						and c.id_area = $idArea
        						and c.id_producto = $idProducto
        						and c.unidad_comercial = '$unidadMedida'
        						and c.numero_lote = '$numeroLote'  
                            ORDER BY identificador_producto ASC
                            LIMIT $cantidad";

		}else{

		    $busqueda = "SELECT
                            c.numero_lote
                            , " . $cicloCerradoIdentificador . " as identificador_producto
                            , c.id_producto
                            FROM
                            g_catastro.catastros c
                            , g_catastro.detalle_catastros dc
                            , (SELECT
                            		di.identificador
                            	FROM
                            		g_vacunacion.vacunacion v
                            		, g_vacunacion.detalle_vacunacion dv
                            		, g_vacunacion.detalle_identificadores di
                            	WHERE
                            		v.id_vacunacion = dv.id_vacunacion
                            		and di.id_detalle_vacunacion = dv.id_detalle_vacunacion
                            		and v.estado = 'vigente'
                            		and (SELECT extract(days FROM (current_date - to_char(v.fecha_vacunacion,'YYYY-MM-DD')::timestamp))) > 15
                            	) vac
                            WHERE
                                dc.estado_registro = 'activo'
                                " . $busquedaCicloCerradoIdentificador . "
                                and vac.identificador = COALESCE (dc.identificador_producto, dc.identificador_unico_producto)
                                and c.id_catastro = dc.id_catastro
                                and c.id_tipo_operacion = $idTipoOperacion
        						and c.id_area = $idArea
        						and c.id_producto = $idProducto
        						and c.unidad_comercial = '$unidadMedida'
        						and c.numero_lote = '$numeroLote'
                            ORDER BY identificador_producto ASC
                            LIMIT $cantidad";
											
		}

	    $consulta = $busqueda;

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
		
	public function guardarMovilizacionProceso($conexion, $sentencia){
	
	    $consulta = $sentencia;
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function guardarProcesoCambioDuenio($conexion, $sentencia){
	    
	    $consulta = $sentencia;
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerDuenioTrasporteXPlaca($conexion, $placa){
	    
	    $consulta = "SELECT 
                        placa_transporte, nombre_duenio_transporte
                     FROM 
                        g_movilizacion_producto.transporte
                     WHERE placa_transporte = '$placa';";

	    $res = $conexion->ejecutarConsulta($consulta);

	    return $res;
	}	
	
	public function obtenerConductorXIdentificador($conexion, $identificadorConductor){
	    
	    $consulta = "SELECT 
                        identificador_conductor, nombre_conductor
                    FROM 
                        g_movilizacion_producto.conductor
                    WHERE identificador_conductor = '$identificadorConductor';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}	
	
	public function verificarSitioDestino($conexion, $idSitio){
		
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
												and top.id_area='SA' and top.codigo in ('FER', 'FEA')
												and a.id_sitio = '$idSitio';");
			return $res;
	}
	
	function listaRolEmpleadoEmpresa($conexion, $identificacionEmpleado, $nombresEmpleado, $operadorMovilizacion, $tipoUsuario, $usuario, $rol){
	    
	    $identificacionEmpleado  = $identificacionEmpleado != "" ? "'" . $identificacionEmpleado . "'" : "NULL";
	    $nombresEmpleado = $nombresEmpleado != "" ? "'%" . $nombresEmpleado . "%'" : "NULL";
	    $operadorMovilizacion = $operadorMovilizacion != "" ? "'" . $operadorMovilizacion . "'"  : "NULL";
	    
	    if($tipoUsuario == 'PFL_USUAR_INT'){
	        if(($identificacionEmpleado == "NULL") && ($nombresEmpleado == "NULL") && ($operadorMovilizacion == "NULL")){
	            $busqueda0 = " LIMIT 100";
	        }
	    }
	    
	    if($tipoUsuario == 'PFL_USUAR_EXT'){
	        $busqueda1 = " and emp.identificador = '$usuario' ";
	    }
	    
	    $consulta = "SELECT
						re.id_rol_empleado
						, case when ope.razon_social = '' then ope.nombre_representante ||' '|| ope.apellido_representante else ope.razon_social end operador_movilizacion
						, case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end empleado
						, re.tipo
						, re.estado
					FROM
						g_usuario.empleados em 
                        , g_usuario.roles_empleados re
                        , g_operadores.operadores op
                        , g_operadores.operadores ope
                        , g_usuario.empresas emp
					WHERE
						em.id_empleado = re.id_empleado
						and em.identificador = op.identificador
						and emp.id_empresa = em.id_empresa
						and emp.identificador = ope.identificador
						and emp.estado = 'activo'
						and em.estado = 'activo'
						and re.tipo = '" . $rol . "'
						" . $busqueda1 . "
						and ($identificacionEmpleado is NULL or em.identificador = $identificacionEmpleado)
						and ($nombresEmpleado is NULL or case when op.razon_social = '' then coalesce(op.nombre_representante ||' '|| op.apellido_representante ) else op.razon_social end ilike $nombresEmpleado)
						and ($operadorMovilizacion is NULL or emp.id_empresa = $operadorMovilizacion)
					ORDER BY 1 DESC
						" . $busqueda0 . ";";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function listaEmpresas($conexion , $idEmpresa = NULL) {
	    
	    $idEmpresa = $idEmpresa != NULL ? "'" . $idEmpresa . "'" : "NULL";
	    
	    $consulta = "SELECT DISTINCT
						em.identificador
						, em.id_empresa
						, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_empresa
					FROM
						g_usuario.empresas em
						, g_operadores.operadores o
						, g_operadores.sitios s
						, g_operadores.operaciones op
						, g_catalogos.tipos_operacion t
						, g_operadores.areas a
						, g_operadores.productos_areas_operacion pao
					WHERE
						em.identificador = o.identificador
						and em.estado = 'activo'
						and em.identificador = op.identificador_operador
						and s.identificador_operador = op.identificador_operador
						and op.id_tipo_operacion = t.id_tipo_operacion
						and s.id_sitio = a.id_sitio
						and pao.id_area = a.id_area
						and pao.id_operacion = op.id_operacion
						and op.estado in ('registrado', 'porCaducar')
						and t.codigo || t.id_area in ('OPISA', 'FEASA', 'FERSA', 'FAEAI')
						and ($idEmpresa is NULL or em.identificador = $idEmpresa)
						and (SELECT count(eml.id_empleado) FROM g_usuario.empleados eml where eml.id_empresa = em.id_empresa) > 0
					ORDER BY nombre_empresa ASC;";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function buscarEmpleadoEmpresaRol($conexion,  $idEmpresa, $identificadorEmpleado, $nombresEmpleado){
	    
	    $identificadorEmpleado = $identificadorEmpleado!="" ? "'" . $identificadorEmpleado . "'" : "NULL";
	    $nombresEmpleado = $nombresEmpleado!="" ? "'%" . $nombresEmpleado  . "%'" : "NULL";
	    
	    $consulta = "SELECT DISTINCT
						em.id_empleado
						, opv.identificador
						, case when opv.razon_social = '' then opv.nombre_representante ||' '|| opv.apellido_representante else opv.razon_social end nombres
					FROM
						g_usuario.empleados em
						, g_usuario.empresas emp
						, g_operadores.operadores opv
					WHERE
						em.id_empresa = emp.id_empresa
						and opv.identificador = em.identificador  
                        and emp.estado = 'activo'
						and em.estado = 'activo'
						and ($identificadorEmpleado is NULL or em.identificador = $identificadorEmpleado)
						and ($nombresEmpleado is NULL or case when opv.razon_social = '' then coalesce(opv.nombre_representante ||' '|| opv.apellido_representante ) else opv.razon_social end ilike $nombresEmpleado)
						and emp.id_empresa = '" . $idEmpresa . "'
					ORDER BY nombres ASC;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function consultarRolEmpleado($conexion, $rol, $idEmpleado){
	    
	    $consulta = "SELECT
						id_empleado
                        , tipo
                        , estado
					FROM
						g_usuario.roles_empleados
					WHERE
						id_empleado = $idEmpleado
						and tipo = '$rol';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function guardarNuevoRolEmpleado($conexion, $rol, $idEmpleado, $estado){
	    
	    $consulta = "INSERT INTO
						g_usuario.roles_empleados(id_empleado, tipo, estado)
					VALUES
						('$idEmpleado', '$rol', '$estado');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function abrirRolEmpleado($conexion, $idRolEmpleado){
	    
	    $consulta = "SELECT
						re.id_rol_empleado
						, ope.identificador identificador_operador_movilizacion
						, op.identificador identificador_empleado
						, case when ope.razon_social = '' then ope.nombre_representante ||' '|| ope.apellido_representante else ope.razon_social end operador_movilizacion
						, case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end empleado
						, re.tipo
						, re.estado
						, re.usuario_modificacion
						, to_char(re.fecha_modificacion,'yyyy-mm-dd hh24:mi') fecha_modificacion
					FROM
						g_usuario.empleados em 
                        , g_usuario.roles_empleados re
                        , g_operadores.operadores op
                        , g_operadores.operadores ope
                        , g_usuario.empresas emp
					WHERE
						em.id_empleado = re.id_empleado 
                        and em.identificador = op.identificador 
                        and emp.id_empresa = em.id_empresa 
                        and emp.identificador = ope.identificador 
                        and re.id_rol_empleado = $idRolEmpleado;";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarRolEmpleado($conexion, $idRolEmpleado, $estado, $usuarioModificacion){
	    
	    $consulta = "UPDATE
						g_usuario.roles_empleados
					SET
						estado = '$estado',
						usuario_modificacion = '$usuarioModificacion',
						fecha_modificacion = now()
					WHERE
						id_rol_empleado = $idRolEmpleado;";
	    
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
                        and top.codigo || top.id_area in " . $codigoTipoOperacion . "
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
	
	public function obtenerOperacionesUsuarioFiscalizacion($conexion, $identificadorUsuario, $codigoTipoOperacion) {
	    
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
                    	and top.codigo || top.id_area in " . $codigoTipoOperacion . "
                    	and op.identificador_operador = '$identificadorUsuario';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}

}
?>