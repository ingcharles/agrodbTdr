<?php

class ControladorCatalogos{
	
	public function listarLocalizacion($conexion,$categoria){
		
		$busqueda = '';
		switch ($categoria){
			case 'PAIS': $busqueda = 'categoria = 0'; break;
			case 'PROVINCIAS': $busqueda = 'categoria = 1'; break;
			case 'CANTONES': $busqueda = 'categoria = 2'; break;
			case 'SITIOS': $busqueda = 'categoria = 3'; break;
			case 'PARROQUIAS': $busqueda = 'categoria = 4'; break;
			case 'GRUPOS_PAISES': $busqueda = 'categoria = 5'; break;
		}
		
		$res = $conexion->ejecutarConsulta("select * 
											from g_catalogos.localizacion
											where " . $busqueda ."
											order  by 3;"); 
				
															
		return $res;
	}
	
	public function listarSitiosLocalizacion($conexion,$tipo){
		$cid = $this->listarLocalizacion($conexion, $tipo);
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array('codigoProvincia'=>$fila['codigo'],'codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre'],'categoria'=>$fila['categoria'],'padre'=>$fila['id_localizacion_padre'],'latitud'=>$fila['latitud'],'longitud'=>$fila['longitud'],'zona'=>$fila['zona'], 'geografico'=>$fila['geografico_mfin']);
		}
		return $res;
	}
	
	public function listarTitulosOCarrera($conexion,$categoria){
	
		$busqueda = '';
		switch ($categoria){
			case 'TITULOS': $busqueda = "es_titulo_profesional = 'SI'"; break;
			case 'CARRERAS': $busqueda = "es_titulo_profesional = 'NO'"; break;
		}
	
		$res = $conexion->ejecutarConsulta("select *
											from g_catalogos.profesiones
											where " . $busqueda ."
											order by 2;");
		return $res;
	}
	
	public function listarDireccionyProvincia($conexion){
	
		$resp = $conexion->ejecutarConsulta("select distinct(provincia) as nombre
											from g_uath.datos_contrato
											where provincia!=''
											order by 1;");
	
		while ($fila = pg_fetch_assoc($resp)){
			$res[] = array(nombre=>$fila['nombre'], tipo=>'provincia');
		}
	
		$resp2 = $conexion->ejecutarConsulta("select distinct(direccion) as nombre
											from g_uath.datos_contrato
											where direccion!='';");
		while ($fila2 = pg_fetch_assoc($resp2)){
			$res[] = array(nombre=>$fila2['nombre'],tipo=>'dirección');
		}
	
		return $res;
	
	}
	
	public function obtenerNombreLocalizacion ($conexion, $id_localizacion){
	
		$res = $conexion->ejecutarConsulta("select
													*
											from	
													g_catalogos.localizacion
											where 
													id_localizacion = $id_localizacion;");
		return $res;
	}
	
	public function obtenerIdLocalizacion ($conexion, $nombre_localizacion, $categoria){
		
		$busqueda = '';
		switch ($categoria){
			case 'PAIS': $busqueda = 'categoria = 0'; break;
			case 'PROVINCIAS': $busqueda = 'categoria = 1'; break;
			case 'CANTONES': $busqueda = 'categoria = 2'; break;
			case 'SITIOS': $busqueda = 'categoria = 3'; break;
			case 'PARROQUIAS': $busqueda = 'categoria = 4'; break;
			case 'GRUPOS_PAISES': $busqueda = 'categoria = 5'; break;
		}
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.localizacion
											where " . $busqueda ." 
												and UPPER(nombre) = UPPER('$nombre_localizacion');");
		return $res;
	}
	
	public function obtenerHijosLocalizacion ($conexion, $nombreLocalizacionPadre, $categoriaPadre, $categoriaHijo, $nombreLocalizacionMaestro, $categoriaMaestro){
		
		switch ($categoriaPadre){
			case 'PAIS': $categoriaPadre = '0'; break;
			case 'PROVINCIAS': $categoriaPadre = '1'; break;
			case 'CANTONES': $categoriaPadre = '2'; break;
			case 'SITIOS': $categoriaPadre = '3'; break;
			case 'PARROQUIAS': $categoriaPadre = '4'; break;
			case 'GRUPOS_PAISES': $categoriaPadre = '5'; break;
		}
		
		switch ($categoriaHijo){
			case 'PAIS': $categoriaHijo = '0'; break;
			case 'PROVINCIAS': $categoriaHijo = '1'; break;
			case 'CANTONES': $categoriaHijo = '2'; break;
			case 'SITIOS': $categoriaHijo = '3'; break;
			case 'PARROQUIAS': $categoriaHijo = '4'; break;
			case 'GRUPOS_PAISES': $categoriaPadre = '5'; break;
		}
		
		switch ($categoriaMaestro){
			case 'PAIS': $categoriaMaestro = '0'; break;
			case 'PROVINCIAS': $categoriaMaestro = '1'; break;
			case 'CANTONES': $categoriaMaestro = '2'; break;
			case 'SITIOS': $categoriaMaestro = '3'; break;
			case 'PARROQUIAS': $categoriaMaestro = '4'; break;
			case 'GRUPOS_PAISES': $categoriaMaestro = '5'; break;
		}
				
		$datos = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.localizacion
											WHERE
												categoria = $categoriaHijo
												and id_localizacion_padre = (SELECT
																					id_localizacion
																			  FROM
																					g_catalogos.localizacion
																			  WHERE
																					categoria = $categoriaPadre
																					and UPPER(nombre) = UPPER('$nombreLocalizacionPadre')
																					and id_localizacion_padre = (SELECT
																													id_localizacion
																											  FROM
																													g_catalogos.localizacion
																											  WHERE
																													categoria = $categoriaMaestro
																													and UPPER(nombre) = UPPER('$nombreLocalizacionMaestro')))
											ORDER BY 2;");
		
		while ($fila = pg_fetch_assoc($datos)){
			$res[] = array(codigo=>$fila['id_localizacion'],nombre=>$fila['nombre'],categoria=>$fila['categoria'],padre=>$fila['id_localizacion_padre'],latitud=>$fila['latitud'],longitud=>$fila['longitud'],zona=>$fila['zona']);
		}
		
		return $res;
	}
	

	
	/*ok*/
	public function listarSubProductos($conexion){
		$res = $conexion->ejecutarConsulta("select 
												dp.id_subtipo_producto,
												dp.nombre,
												tp.id_area,
												dp.id_tipo_producto
											from
												g_catalogos.subtipo_productos dp,
												g_catalogos.tipo_productos tp
											where
												dp.id_tipo_producto = tp.id_tipo_producto
											order by 2;");
		
		/*$res = $conexion->ejecutarConsulta("select
													*
											from
													g_catalogos.tipo_productos
											order by 2;");*/
		return $res;
	}
	
	/*ok*/
	public function listarProductos($conexion){
		$res = $conexion->ejecutarConsulta("select
													*
											from
													g_catalogos.productos
											where
													estado = 1
											order by 2;");
	
		return $res;
	}
	
	public function obtenerIdProducto ($conexion, $nombreProducto){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.productos
											where
												UPPER(RTRIM(nombre_comun)) = UPPER(RTRIM('$nombreProducto'));");
		return $res;
	}
	
	public function obtenerAreaProductos($conexion, $idProducto){
		
		$res = $conexion->ejecutarConsulta("select
												dp.id_subtipo_producto,
												dp.nombre,
												tp.id_area
											from
												g_catalogos.subtipo_productos dp,
												g_catalogos.tipo_productos tp,
												g_catalogos.productos p
											where
												dp.id_tipo_producto = tp.id_tipo_producto and
												dp.id_subtipo_producto = p.id_subtipo_producto and
												p.id_producto = $idProducto;");
	
		return $res;
	}
	
	public function listarTipoProductos($conexion){
		$res = $conexion->ejecutarConsulta("select
									 			*
											from
												g_catalogos.tipo_productos
											order by 2;");
		return $res;
	}
	
	public function listarTipoProductosXarea($conexion, $idArea){
		$res = $conexion->ejecutarConsulta("select
									 			*
											from
												g_catalogos.tipo_productos
											where 
												id_area = '$idArea'
											order by 2;");
		return $res;
	}
	
	public function obtenerNombreProducto ($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
	
	public function obtenerCodigoProducto ($conexion, $partida){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												COALESCE(
													MAX(
														CAST(codigo_producto as  numeric(5))),0)+1 as codigo 
											FROM 
												g_catalogos.productos 
											WHERE 
												partida_arancelaria = '$partida';");
		return $res;
	}
	
	public function obtenerCodigoInocuidad ($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												COALESCE(
													MAX(
														CAST(subcodigo as  numeric(5))),0)+1 as codigo
											FROM
												g_catalogos.codigos_inocuidad
											WHERE
												id_producto = '$idProducto';");
		return $res;
	}
	
	public function listarRegimenAduanero ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.regimen_aduanero
												order by 2;");
		return $res;
	}
	
	public function obtenerNombreRegimenAduanero ($conexion, $idRegimen){
	
		$res = $conexion->ejecutarConsulta("SELECT
												descripcion,
												codigo
											FROM
												g_catalogos.regimen_aduanero
											WHERE
												id_regimen = $idRegimen;");
		return $res;
	}
	
	public function obtenerIdRegimenAduanero ($conexion, $nombreRegimen){
	
		$res = $conexion->ejecutarConsulta("SELECT
												id_regimen
											FROM
												g_catalogos.regimen_aduanero
											WHERE
												nombre = '$nombreRegimen';");
		return $res;
	}
	
	public function listarMoneda ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.monedas
											order by 3;");
		return $res;
	}
	
	public function obtenerNombreMoneda ($conexion, $idMoneda){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
													g_catalogos.monedas
											WHERE
													id_moneda = $idMoneda;");
		return $res;
	}
	
	public function obtenerIdMoneda ($conexion, $nombreMoneda){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.monedas
											WHERE
												nombre = '$nombreMoneda';");
		return $res;
	}
	
	public function listarPuertosPorPais ($conexion, $idPais){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.puertos
											WHERE
												id_pais = $idPais;");
		return $res;
	}
	
	
	public function obtenerLocalizacion ($conexion, $idLocalizacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.localizacion
											WHERE
												id_localizacion = $idLocalizacion;");
		return $res;
	}
	
	public function buscarProductoXCodigo ($conexion, $partidaArancelariaVUE, $codigoProductoVUE, $area=null){
		
		$partidaArancelaria =  substr($partidaArancelariaVUE,0,10);
		$codigoComplementario = substr($partidaArancelariaVUE,10,4);
		$codigoSuplementario = substr($partidaArancelariaVUE,14,4);
		$codigo = substr($codigoProductoVUE, 1,4);
		$codigoInocuidad = substr($codigoProductoVUE, 5,4);
		$cantidad = strlen($codigoProductoVUE);
	
		if($cantidad == '5'){
												
			$res = $conexion->ejecutarConsulta("SELECT
													p.id_producto,
													p.nombre_comun,
													p.nombre_cientifico,
													p.partida_arancelaria,
													p.codigo_producto,
													p.licencia_magap,
													cap.codigo_complementario,
													cap.codigo_suplementario,
													tp.id_area,
													UPPER(p.unidad_medida) as unidad_medida,
													sp.nombre,
													sp.id_subtipo_producto,
													p.programa,
													p.proceso_banano,
                                                    tp.nombre as nombre_tipo,
													tp.id_tipo_producto
												FROM
													g_catalogos.productos p,
													g_catalogos.subtipo_productos sp,
													g_catalogos.codigos_adicionales_partidas cap,
													g_catalogos.tipo_productos tp
												WHERE
													p.id_producto = cap.id_producto
													AND sp.id_subtipo_producto = p.id_subtipo_producto
													AND tp.id_tipo_producto = sp.id_tipo_producto
													AND p.partida_arancelaria = '$partidaArancelaria'
													AND p.codigo_producto = '$codigo'
													AND cap.codigo_complementario = '$codigoComplementario'
													AND cap.codigo_suplementario = '$codigoSuplementario'
													AND p.estado = 1;");
		}else if($cantidad == '9'){
			
			if($area === 'IAP'){
    			//Productos Inocuidad Plaguicidas
    		    $res = $conexion->ejecutarConsulta("SELECT
                                                    	p.id_producto,
                                                    	p.nombre_comun,
                                                    	p.nombre_cientifico,
                                                    	pa.partida_arancelaria,
                                                    	pa.codigo_producto,
                                                    	p.licencia_magap,
                                                    	ccs.codigo_complementario,
                                                    	ccs.codigo_suplementario,
                                                    	tp.id_area,
                                                    	UPPER(p.unidad_medida) as unidad_medida,
                                                    	sp.nombre,
                                                    	sp.id_subtipo_producto,
                                                    	p.programa,
														pi.id_operador,
                                                        tp.nombre as nombre_tipo,
    													tp.id_tipo_producto
                                                    FROM
                                                    	g_catalogos.tipo_productos tp,
                                                    	g_catalogos.subtipo_productos sp,
                                                    	g_catalogos.productos p,
														g_catalogos.productos_inocuidad pi,
                                                    	g_catalogos.partidas_arancelarias pa,
                                                    	g_catalogos.codigos_comp_supl ccs,
                                                    	g_catalogos.presentaciones_plaguicidas pp	
                                                    WHERE
														tp.id_tipo_producto = sp.id_tipo_producto
														AND sp.id_subtipo_producto = p.id_subtipo_producto
														AND p.id_producto = pi.id_producto
                                                  		AND p.id_producto = pa.id_producto
														AND pa.id_partida_arancelaria = ccs.id_partida_arancelaria
                                                    	AND ccs.id_codigo_comp_supl = pp.id_codigo_comp_supl
                                                    	AND pa.partida_arancelaria = '$partidaArancelaria'
                                                    	AND pa.codigo_producto = '$codigo'
                                                    	AND ccs.codigo_complementario = '$codigoComplementario'
                                                    	AND ccs.codigo_suplementario = '$codigoSuplementario'
                                                    	AND pp.codigo_presentacion = '$codigoInocuidad'
                                                    	AND p.estado = 1;");
		    }else{
		        //Productos Inocuidad excepto Plaguicidas
			$res = $conexion->ejecutarConsulta("SELECT
													p.id_producto,
													p.nombre_comun,
													p.nombre_cientifico,
													p.partida_arancelaria,
													p.codigo_producto,
													p.licencia_magap,
													cap.codigo_complementario,
													cap.codigo_suplementario,
													tp.id_area,
													UPPER(p.unidad_medida) as unidad_medida,
													sp.nombre,
													sp.id_subtipo_producto,
													p.programa,
													pi.id_operador,
                                                    tp.nombre as nombre_tipo,
													tp.id_tipo_producto
												FROM
													g_catalogos.tipo_productos tp,
													g_catalogos.subtipo_productos sp,
													g_catalogos.productos p,
													g_catalogos.productos_inocuidad pi,
													g_catalogos.codigos_adicionales_partidas cap,
													g_catalogos.codigos_inocuidad ci
												WHERE
													tp.id_tipo_producto = sp.id_tipo_producto
													AND sp.id_subtipo_producto = p.id_subtipo_producto
													AND p.id_producto = pi.id_producto													
													AND p.id_producto = cap.id_producto
													AND p.id_producto = ci.id_producto
													AND p.partida_arancelaria = '$partidaArancelaria'
													AND p.codigo_producto = '$codigo'
													AND cap.codigo_complementario = '$codigoComplementario'
													AND cap.codigo_suplementario = '$codigoSuplementario'
													AND ci.subcodigo = '$codigoInocuidad'
													AND p.estado = 1;");
			}
		}
			
		return $res;
	}
	
	public function buscarCodigoInocuidad ($conexion, $idProducto, $subCodigo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.codigos_inocuidad
											WHERE
												id_producto = $idProducto
												AND subcodigo='$subCodigo';");
				return $res;
	}
	
	
	public function obtenerCodigoLocalizacion ($conexion, $codigo){
			
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.localizacion
											where
												codigo_vue = '$codigo';");
		return $res;
	}
	
	public function obtenerCodigoLocalizacionImportacion ($conexion, $codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.localizacion
											WHERE
												otros = '$codigo';");
		return $res;
	}
	
	public function listarUsos($conexion){
		$res = $conexion->ejecutarConsulta("select
									             *
									        from
									             g_catalogos.usos ;");
		return $res;
	}
	
	public function listarUsosPorArea($conexion, $idArea, $estado = 'Activo'){
		
		$res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									             g_catalogos.usos 
											WHERE
												id_area = '$idArea'
												and estado_uso = '$estado'
											ORDER BY 
												nombre_uso;");
		return $res;
	}
	
	public function listarUnidadesMedida($conexion){
		
		$res = $conexion->ejecutarConsulta("select
									             *
									        from
									             g_catalogos.unidades_medidas
											where
												clasificacion  != 'sercop'
                                                and tipo_unidad not in ('tiempo')
                                                and estado = 'Activo'
											order by
												nombre;");
		return $res;
	}
	
	public function listarUnidadesMedidaXTipo($conexion, $tipo){
		$res = $conexion->ejecutarConsulta("select
									             *
									        from
									             g_catalogos.unidades_medidas
											where
												tipo_unidad = '$tipo'
											order by
												codigo;");
		return $res;
	}
	
	public function obtenerUnidadMedida($conexion,$idUnidad){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.unidades_medidas
											WHERE
												id_unidad_medida='$idUnidad';");
	
				return $res;
	}
	
	public function obtenerIdUnidadMedida($conexion,$codigoMedida){
		$res = $conexion->ejecutarConsulta("SELECT
												id_unidad_medida
											FROM
												g_catalogos.unidades_medidas
											WHERE
												codigo='$codigoMedida';");
	
		return $res;
	}
	
	
	public function listaRangoEdadesAnimal($conexion){
		$res = $conexion->ejecutarConsulta("select
									           	id_rango_edad,
												(rango_edad  || ' ' || unidad_rango)  nombre
									        from 
												g_catalogos.rangos_edades;");
		return $res;
	}
	
	public function listarMediosTrasporte($conexion){
		$res = $conexion->ejecutarConsulta("select
                                   				id_medios_transporte,
                                   				tipo
                               				from
                                   				g_catalogos.medios_transporte");
		return $res;
	}
	
	public function obtenerCodigoMedioTransporte($conexion, $codigoMedioTransporte){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.medios_transporte
											WHERE
												UPPER(codigo) = UPPER('$codigoMedioTransporte');");
	    return $res;
	}
			
	public function obtenerCodigoMoneda ($conexion, $codigoMoneda){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.monedas
											WHERE
												codigo = '$codigoMoneda';");
		return $res;
	}
	
	public function obtenerCodigoAduanero($conexion, $regimenAduanero){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.regimen_aduanero
											WHERE
												UPPER(codigo) = UPPER('$regimenAduanero');");
		return $res;
	}
	
	public function obtenerPuertoCodigo ($conexion, $codigoPuerto){
												
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.puertos
											where
												codigo_puerto = '$codigoPuerto';");
		return $res;
	}
	
	public function buscarIdOperacion ($conexion, $idArea, $nombreOperacion){
		
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.tipos_operacion
											where
												id_area = '$idArea'
												and nombre = '$nombreOperacion';");
		return $res;
	}
	
	public function obtenerUsoVUE ($conexion, $codigo_vue){
		
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.usos
											where  
												codigo_vue = '" .$codigo_vue . "';");
		return $res;
	}
	
	public function buscarTipoProdcutoVue($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("select
												tp.nombre as tipoPorducto
											from
												g_catalogos.subtipo_productos dp,
												g_catalogos.tipo_productos tp,
												g_catalogos.productos p
											where
												dp.id_tipo_producto = tp.id_tipo_producto and
												dp.id_subtipo_producto = p.id_subtipo_producto and
												p.id_producto = $idProducto;");
	
		return $res;
	}
	
	public function buscaLocalizacionVue($conexion, $cod_provincia, $cod_canton, $cod_parroquia){
	
		$res = $conexion->ejecutarConsulta("select
												id_localizacion cod_provincia,
												nombre provincia,
												(select id_localizacion from g_catalogos.localizacion l1 where l1.codigo_vue = '" . $cod_canton . "') cod_canton,
												(select nombre from g_catalogos.localizacion l1 where l1.codigo_vue = '" . $cod_canton . "') canton,
												(select id_localizacion from g_catalogos.localizacion l1 where l1.codigo_vue = '" . $cod_parroquia . "') cod_parroquia,
												(select nombre from g_catalogos.localizacion l1 where l1.codigo_vue = '" . $cod_parroquia . "') parroquia
											from
												g_catalogos.localizacion l
											where
												codigo_vue = '" . $cod_provincia . "';");
		return $res;
	}
	
		public function buscarRangoEdadesAnimal($conexion, $idRango){
		$res = $conexion->ejecutarConsulta("select
												id_rango_edad,
												(rango_edad  || ' ' || unidad_rango)  nombre
											from
												g_catalogos.rangos_edades
											where
												id_rango_edad = $idRango;");
		return $res;
	}
	
	public function listarProductosInocuidad($conexion){			
		$cidProInocidad = $conexion->ejecutarConsulta("select distinct t.nombre tipo_producto, 
														    p.id_producto,
															p.codigo_producto,
															p.partida_arancelaria subpartida,
														    s.nombre clasificacion,
															p.nombre_comun producto,
															p.nombre_cientifico, 
															pi.composicion,
															pi.formulacion,
															t.id_area  
														from 
															g_catalogos.productos p, 
															g_catalogos.subtipo_productos s, 
															g_catalogos.tipo_productos t,
															g_catalogos.productos_inocuidad pi
														where 
															p.id_subtipo_producto = s.id_subtipo_producto and 
															s.id_tipo_producto = t.id_tipo_producto and 
															t.id_area in ('IAP', 'IAV', 'IAF', 'IAPA') and 
															p.id_producto = pi.id_producto				                                
														order by 
															2 asc;");	

		while ($fila = pg_fetch_assoc($cidProInocidad)){
			$res[] = array(
					tipo_producto=>$fila['tipo_producto'],
					id_producto=>$fila['id_producto'],
					codigo_producto=>$fila['codigo_producto'],
					subpartida=>$fila['subpartida'],
					clasificacion=>$fila['clasificacion'],										
					producto=>$fila['producto'],
					nombre_cientifico=>$fila['nombre_cientifico'],
					composicion=>$fila['composicion'],
					formulacion=>$fila['formulacion'],
					id_area=>$fila['id_area']
			);
		}
		
		return $res;
		
	}	

	// *** Proyecto vacunación Animal ***
	
	//Funciones para Especie
	
	public function listaEspecies($conexion){
		$res = $conexion->ejecutarConsulta("select 
												id_especies
												, nombre
												, estado
												,codigo
											 from g_catalogos.especies
											 where 
												estado = 'activo';
											 ");																					
		return $res;
	
	}
	
	// Funciones para el vacunador
	
	public function especiesVacunacion($conexion){
		$res = $conexion->ejecutarConsulta("select
												id_especies
												, nombre
												, estado
											from g_catalogos.especies
											where
												estado = 'activo'
												and estado_vacunacion = 'si'
											 ");
		return $res;
	
	}
	
	public function especiesMovilizacion($conexion){
		$res = $conexion->ejecutarConsulta("select
												id_especies
												, nombre
												, estado
											from g_catalogos.especies
											where
												estado = 'activo'												
											 ");
		return $res;
	
	}
	
	public function obtenerEspecieXid($conexion,$idEspecie){
		$res = $conexion->ejecutarConsulta("select
												id_especies,
												nombre,
												estado
											from
												g_catalogos.especies
											where
												estado = 'activo'
												and id_especies = $idEspecie ");
		return $res;
	
	}
	

	
	public function lugarEmisionMovilizacion($conexion){
		$res = $conexion->ejecutarConsulta("select 
												id_lugar_emision
												, nombre_lugar_emision
												, direccion_lugar_emision
												, id_provincia
												, provincia
												, id_canton
												, canton												
  											from g_catalogos.lugar_emisiones				
											where
												estado = 'activo';												
											 ");
		return $res;
	
	}
	
	
	public function listaCboOpVacunacion($conexion, $especie){
		$OperadorVacunacion = $conexion->ejecutarConsulta("select o.identificador identificador_administrador
												, o.razon_social nombre_administrador
												, o.provincia
												, o.canton
												, t.nombre nombre_operacion
											from g_operadores.operaciones op
												, g_catalogos.tipos_operacion t
												, g_operadores.operadores o
											where o.identificador = op.identificador_operador
												and op.id_tipo_operacion = t.id_tipo_operacion
												and t.nombre = 'Operador de vacunación'
												and t.id_area = 'SA'
											");
	
		while ($fila = pg_fetch_assoc($OperadorVacunacion)){
			$res[] = array(identificador_administrador=>$fila['identificador_administrador'],
					nombre_administrador=>$fila['nombre_administrador'],
					provincia=>$fila['provincia'],
					canton=>$fila['canton'],
					nombre_operacion=>$fila['nombre_operacion']
			);
		}
	
		return $res;
	}
	
	
	// Función para el operador EspeciesVacunacion/administrador de vacunación
	public function listaEspecieOperadorVacunador($conexion){
		$opEspecieVacunador = $conexion->ejecutarConsulta("select distinct id_especie
														, nombre_especie
													from g_vacunacion_animal.administrador_vacunacion 
													where estado = 'activo'
													");
			
		while ($fila = pg_fetch_assoc($opEspecieVacunador)){
			$res[] = array(id_especie=>$fila['id_especie']					
					,nombre_especie=>$fila['nombre_especie']
			);
		}
	
		return $res;
	}
	
	
	// Funciones para el Lote
	public function listaLotes($conexion, $estado = "in ('activo')"){
		
		$Lote = $conexion->ejecutarConsulta("select
												  lo.id_lote,
										       	  lo.id_laboratorio,
										      	  lo.numero_lote,
										          to_char(lo.fecha_vencimiento,'MM/YY') fecha_vencimiento,
												  la.codigo
											 from
													g_catalogos.lotes lo, g_catalogos.laboratorios la
											 where
													lo.estado " . $estado . "
                                                    and lo.id_laboratorio=la.id_laboratorio
											order by 3 asc;");
	
		while ($fila = pg_fetch_assoc($Lote)){
			$res[] = array(
					'id_lote'=>$fila['id_lote'],
					'id_laboratorio'=>$fila['id_laboratorio'],
					'numero_lote'=>$fila['numero_lote'],
					'fecha_vigencia'=>$fila['fecha_vigencia'],
					'fecha_vencimiento'=>$fila['fecha_vencimiento'],
				    'codigo'=>$fila['codigo']);
		}
		return $res;
	
	}
	
		// Función para el Almacen
		
	// Función para el laboratorio
	public function listaLaboratorios($conexion, $idEspecie){
		
		$Laboratorio = $conexion->ejecutarConsulta("select *
													from
						                                g_catalogos.laboratorios
													where
														id_especie= $idEspecie and  estado='activo';");
		while ($fila = pg_fetch_assoc($Laboratorio)){
			$res[] = array(
					'id_laboratorio'=>$fila['id_laboratorio'],
					'nombre_laboratorio'=>$fila['nombre_laboratorio'],
					'id_especie'=>$fila['id_especie'],
					'nombre_especie'=>$fila['nombre_especie']);
		}
		return $res;
	}
	
	// Función para el tipo vacuna
	public function listaTipoVacuna($conexion)
	{
		$res = $conexion->ejecutarConsulta("select
				                                *
											from
				                               g_catalogos.tipo_vacunas
											where
				                                estado='activo';");
		return $res;
	}
	
	
	// Función para el lugar del Control areteo, no va a ser areteado
	public function listaLugarControlAreteo($conexion)
	{
		$res = $conexion->ejecutarConsulta("select
												l.id_localizacion,
												l.nombre
											from
												g_catalogos.localizacion l,
												g_vacunacion_animal.control_areteo_animales a
											where
												l.id_localizacion = a.id_provincia
											group by
												l.id_localizacion, l.nombre
											order by
												l.nombre asc;");
		return $res;
	}
	
		
	public function obtenerTipoSubtipoXProductos($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("select
												dp.nombre as nombre_subtipo,
												dp.id_subtipo_producto,
												tp.nombre as nombre_tipo,
												tp.id_tipo_producto
											from
												g_catalogos.subtipo_productos dp,
												g_catalogos.tipo_productos tp,
												g_catalogos.productos p
											where
												dp.id_tipo_producto = tp.id_tipo_producto and
												dp.id_subtipo_producto = p.id_subtipo_producto and
												p.id_producto = $idProducto;");
	
		return $res;
	}
	
	public function buscarCatalogoLugarInspeccion($conexion, $codigoLugar){
		$res = $conexion->ejecutarConsulta("select
													*
											from
													g_catalogos.lugares_inspeccion
											where
													codigo_vue = '$codigoLugar';");
	
		return $res;
	}
	
	public function listarEntidadesBancarias($conexion){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.entidades_bancarias
											order by 
												nombre;");
	
		return $res;
	}
	
	public function buscarProductoXNombre($conexion, $idSubtipoProducto, $nombreProducto){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos
											WHERE
												quitar_caracteres_especiales(nombre_comun)
												ILIKE quitar_caracteres_especiales('$nombreProducto') and
												id_subtipo_producto = $idSubtipoProducto;");
	
				return $res;
	}
	
	public function buscarSubtipoProductoXNombre($conexion, $idTipoProducto, $nombreSubtipo){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.subtipo_productos
											WHERE
												quitar_caracteres_especiales(nombre)
												ILIKE quitar_caracteres_especiales('%$nombreSubtipo%') and
												id_tipo_producto = $idTipoProducto;");
		
				return $res;
	}
	
	// Funciones para el operador Vacunacion/administrador de vacunación
	public function listaOperadorVacunador($conexion){
		$opVacunador = $conexion->ejecutarConsulta("select a.id_administrador_vacunacion
														, a.identificador_administrador
														, case when o.razon_social = '' then o.identificador ||' - '|| o.nombre_representante ||' '|| o.apellido_representante else o.identificador ||' - '|| o.razon_social end nombre_administrador
														, a.id_especie
														, a.nombre_especie
													from g_vacunacion_animal.administrador_vacunacion a,
														g_operadores.operadores o
														where o.identificador = a.identificador_administrador
														and a.estado = 'activo'
													");
			
		while ($fila = pg_fetch_assoc($opVacunador)){
			$res[] = array(id_administrador_vacunacion=>$fila['id_administrador_vacunacion']
					,identificador_administrador=>$fila['identificador_administrador']
					,nombre_administrador=>$fila['nombre_administrador']
					,id_especie=>$fila['id_especie']
					,nombre_especie=>$fila['nombre_especie']
			);
		}
	
		return $res;
	}
	
	public function ListarBultos ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.descripcion_bultos");
		return $res;
	}
	
	public function ListarVariedades ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.variedades");
		return $res;
	}
	
	public function ListarCalidades ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.calidad_producto");
		return $res;
	}
	
	public function listarPuertosPaisTipo ($conexion, $idPais, $tipoPuerto){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.puertos
											WHERE
												id_pais = $idPais
												and tipo_puerto = '$tipoPuerto';");
		return $res;
	}
	
	public function buscarAreaOperadorXNombre ($conexion, $nombreArea){
			
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (codigo)	
											FROM
												g_catalogos.areas_operacion
											WHERE
												nombre = '$nombreArea';");
		return $res;
	}
	
	
	public function listarSubTipoProductoXtipoProducto($conexion, $codigoSubTipoProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												dp.id_subtipo_producto,
												dp.nombre,
												tp.id_area,
												dp.id_tipo_producto
											FROM
												g_catalogos.subtipo_productos dp,
												g_catalogos.tipo_productos tp
											WHERE
												dp.id_tipo_producto = tp.id_tipo_producto
												and tp.id_tipo_producto = $codigoSubTipoProducto
												and dp.estado = 1
											order by 2;");
	
		return $res;
	}
	
	public function listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto){
		$res = $conexion->ejecutarConsulta("select
													*
											from
													g_catalogos.productos p,
													g_catalogos.subtipo_productos stp
											where
													p.id_subtipo_producto = stp.id_subtipo_producto
													and p.id_subtipo_producto = $codigoSubTipoProducto
													and p.estado = 1
											order by 2;");
	
		return $res;
	}
	
	public function obtenerAreasXtipoOperacion($conexion, $tipoOperacion){
			
		$res = $conexion->ejecutarConsulta("SELECT
												aop.*
											FROM
												g_catalogos.tipos_operacion top,
												g_catalogos.areas_operacion aop
											WHERE
												aop.id_tipo_operacion = top.id_tipo_operacion
												and top.id_tipo_operacion = $tipoOperacion;");
		
		while ($fila = pg_fetch_assoc($res)){
			$areas[] = array(
					'id_area'=>$fila['id_area'],
					'codigo'=>$fila['codigo'],
					'nombre'=>$fila['nombre']);
		}
	
				return $areas;
	}
	
	public function obtenerDatosTipoOperacion ($conexion, $idTipoOperacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.tipos_operacion
											WHERE
												id_tipo_operacion = $idTipoOperacion;");
		return $res;
	}
	
	public function listarEntidadesBancariasAgrocalidad($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.entidades_bancarias
											WHERE
												cuenta_agrocalidad = true
											order by
												nombre;");
	
		return $res;
	}
	
	public function listarTiposTramites($conexion, $area){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.tipos_tramites
											WHERE
												id_area in ($area)
											ORDER BY
												1;");
	
		return $res;
	}
	
	public function listarOperaciones($conexion, $area = ''){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.tipos_operacion t
											where
												t.id_area like '%$area%' and
												t.estado = 1
											order by
												t.id_area,
												t.nombre;");
	
				return $res;
	}
	
	public function obtenerOperacion ($conexion, $idOperacion){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.tipos_operacion t
											where
												t.id_tipo_operacion = $idOperacion;");
	
		return $res;
	}
	
	public function obtenerTiposDeDocumento($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												distinct da.*
											FROM
												g_catalogos.documentos_anexos da 
												INNER JOIN g_catalogos.tipos_operacion_requerimientos tor ON da.id_documento_anexo = tor.id_documento_anexo
												INNER JOIN g_operadores.operaciones o ON tor.id_tipo_operacion = o.id_tipo_operacion
											WHERE
												da.estado = 'activo'
												and identificador_operador = '$identificador'
											ORDER BY 
											    nombre_documento;
											");
		return $res;
	}
	
	public function obtenerListaDeVerificacion($conexion, $operacion) {
		$res = $conexion->ejecutarConsulta("select
												distinct (tor.*)
											from
												g_catalogos.tipos_operacion_requerimientos tor
												, g_operadores.operaciones opr
											where
												opr.id_tipo_operacion = tor.id_tipo_operacion
												and opr.id_operacion IN ($operacion)
												and tor.estado = 'activo'
											order by
												tor.titulo;");
				return $res;
	}
	
	public function buscarIdLaboratoriosDiagnosticoXprovincia($conexion, $nombreProvincia){
				
		$res = $conexion->ejecutarConsulta("select
												*
											 from 
												g_catalogos.laboratorios_diagnostico_provincias
											 where
												nombre_provincia = '$nombreProvincia';");
		return $res;
	
	}
	
	public function obtenerProvinciasXIdLaboratorioDIagnostico($conexion, $idLaboratorioDiagnostico){
			
		$res = $conexion->ejecutarConsulta("select
												*,
												upper(nombre_provincia) as provincia
											from
												g_catalogos.laboratorios_diagnostico_provincias
											where
												id_laboratorio_diagnostico = $idLaboratorioDiagnostico;");
		
		$registroProvincia = pg_fetch_all($res);
		
		foreach ($registroProvincia as $fila)
			$provincia .= "'".$fila['provincia']."',";
		
		$provincia = rtrim($provincia,',');
		
		return $provincia;
	
	}
	
	public function obtenerUnidadMedidaAreas($conexion, $idArea){
	
		$res = $conexion->ejecutarConsulta("select
												distinct(a.unidad_medida)
											from
												g_catalogos.areas_operacion a,
												g_operadores.areas ar
											where
												ar.id_area = $idArea and
												ar.tipo_area = a.nombre;");
	
		return $res;
	
	}

	
	public function listarTiposCapacitacion($conexion){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.tipos_capacitacion;");
		return $res;
	
	}
	
	public function listarActividadesMovilizacion ($conexion, $idCategoria){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.actividades_movilizacion
											WHERE
												estado = 1 and
												id_categoria = $idCategoria
											ORDER BY
												nombre_actividad asc;");
		return $res;
	}
	
	public function listarSubActividadesMovilizacion ($conexion, $idCategoria, $idPadre){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.actividades_movilizacion
											WHERE
												estado = 1 and
												id_categoria = $idCategoria and
												id_actividad_padre = $idPadre
											ORDER BY
												nombre_actividad asc;");
	
		return $res;
	}
	
	public function obtenerResponsableFirmaVUE ($conexion, $formulario, $area, $provincia = null, $tipoPuerto = null){
		
		$busqueda = '';
		
		switch ($formulario){
			
			case '101-002':
				$busqueda = "and nombre_provincia = '$provincia'";
			break;
			
			case '101-024':
				$busqueda = " and nombre_provincia = '$provincia' and tipo_puerto = '$tipoPuerto'";
			break;

			case '101-034':
				$busqueda = " and id_provincia = '$provincia'";
			break;
			
			case '101-061':
			    $busqueda = "and upper(nombre_provincia) = upper('$provincia')";
			break;
		}
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.responsables_vue
											WHERE
												formulario = '$formulario' and
												id_area = '$area'
												".$busqueda.";");
	
				return $res;
	}
	
	//REPORTES DE INOCUIDAD VETERINARIA DE LA APLICACIÓN---->"Registro de productos inocuidad"
	
	public function reporteImprimirProductosInocuidadPlaguicidaNuevo($conexion)
	{
	    $res = $conexion->ejecutarConsulta("SELECT 
                                                row_to_json(productosRPIP)
                                            FROM (
                                            	SELECT
                                            		pin.numero_registro,
                                            		pin.id_operador,
													(CASE WHEN count(o.identificador) >= 1
                                        				THEN
                                        					o.razon_social
                                        				ELSE
                                        					epp.razon_social
                                        				END) as razon_social,
                                            		p.nombre_comun,
                                            		to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha,
                                            		(SELECT array_to_json(array_agg(row_to_json(r_p)))
                                            		FROM (
                                            			SELECT
                                            				presentacion,
                                            				unidad as unidad_medida
                                            			FROM
                                            				g_catalogos.presentaciones_plaguicidas pp
                                            				INNER JOIN g_catalogos.codigos_comp_supl ccs ON ccs.id_codigo_comp_supl = pp.id_codigo_comp_supl
                                            				INNER JOIN g_catalogos.partidas_arancelarias pa ON ccs.id_partida_arancelaria = pa.id_partida_arancelaria
                                            			WHERE
                                            				pa.id_producto =  p.id_producto and
                                            				pp.estado = 'activo' and
                                            				ccs.estado = 'activo' and
                                            				pa.estado = 'activo'
                                            		) r_p) as presentacion,
                                            		(SELECT array_to_json(array_agg(row_to_json(r_p)))
                                            		FROM (
                                            			SELECT
                                            				*
                                            			FROM
                                            				g_catalogos.usos_productos_plaguicidas upp
                                            			WHERE
                                            				upp.id_producto = p.id_producto
                                            			ORDER BY
                                            				upp.cultivo_nombre_comun
                                            		) r_p) as usos,
                                            		(SELECT array_to_json(array_agg(row_to_json(r_p)))
                                            		FROM (
                                            			SELECT
                                            				tipo_componente,
                                            			   ingrediente_activo,
                                            				unidad_medida,
                                            				concentracion
                                            			FROM
                                            			   g_catalogos.composicion_inocuidad
                                            			WHERE
                                            				id_producto =  p.id_producto
                                            			ORDER BY
                                            					ingrediente_activo
                                            		) r_p) as composicion,
                                            		(SELECT array_to_json(array_agg(row_to_json(r_p)))
                                                		FROM (
                                                			SELECT
                                                                tipo,
                                                				nombre as nombre_ff,
                                                				pais_origen
                                                			FROM
                                                				g_catalogos.fabricante_formulador
                                                			WHERE
                                                				id_producto =  p.id_producto 
                                                			ORDER BY
                                                				nombre
                                                		) r_p) as formulador,
                                                    (SELECT array_to_json(array_agg(row_to_json(r_p)))
                                                		FROM (
                                                			SELECT
                                                				m.manufacturador,
                                                				m.pais_origen
                                                			FROM
                                                				g_catalogos.manufacturador m
                                                				FULL OUTER JOIN g_catalogos.fabricante_formulador ff ON ff.id_fabricante_formulador = m.id_fabricante_formulador
                                                			WHERE
                                                				ff.id_producto =  p.id_producto 
                                                			ORDER BY
                                                				nombre
                                                		) r_p) as manufacturador,
                                            		sp.nombre as subtipo_producto,
                                            		pin.formulacion,
                                            		pin.dosis ||' '|| pin.unidad_dosis as dosis,
                                            		pin.categoria_toxicologica,
                                            		pin.periodo_reingreso,
                                            		pin.periodo_carencia_retiro,
                                            		pin.observacion,
                                            		CASE WHEN p.estado=1 THEN 'Vigente'
                                            		WHEN p.estado=2 THEN 'Suspendido'
                                            		WHEN p.estado=3 THEN 'Caducado'
                                            		END as estado
                                            	FROM g_catalogos.productos as p
                                            		FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                                            		FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                                            		FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
													LEFT JOIN g_operadores.operadores o ON pin.id_operador = o.identificador
                                        			LEFT JOIN g_catalogos.empresa_producto_plaguicida epp ON pin.id_operador = epp.identificador
                                            	WHERE
                                            		tp.id_area = 'IAP' and tp.nombre!='Cultivo'
                                            		and p.estado not in (9)
												GROUP BY pin.numero_registro, pin.id_operador, o.razon_social, epp.razon_social, p.nombre_comun, pin.fecha_registro, p.id_producto,
                                        		sp.nombre, sp.nombre, pin.formulacion, pin.dosis, pin.unidad_dosis, pin.categoria_toxicologica, pin.periodo_reingreso, pin.periodo_carencia_retiro,
                                        		pin.observacion
                                            ) as productosRPIP;");
	    
	    return $res;
	}
	
	public function reporteImprimirProductosInocuidadPlaguicida($conexion)
	{
		$res = $conexion->ejecutarConsulta("SELECT row_to_json(productosRPIP)
			                                FROM (
												SELECT
													pin.numero_registro,
													pin.id_operador,
													o.razon_social,
													p.nombre_comun,
													to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															presentacion,
															unidad_medida
														FROM
															g_catalogos.codigos_inocuidad
														WHERE
															id_producto =  p.id_producto
													) r_p) as presentacion,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															u.nombre_uso,
															COALESCE(pr.nombre_comun, piu.instalacion) nombre_producto_inocuidad
														FROM
															g_catalogos.producto_inocuidad_uso piu
															INNER JOIN g_catalogos.usos u ON piu.id_uso = u.id_uso
															LEFT JOIN g_catalogos.productos pr ON piu.id_aplicacion_producto = pr.id_producto
														WHERE
															piu.id_producto = p.id_producto
														ORDER BY
															u.nombre_uso
													) r_p) as usos,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
														   ingrediente_activo,
															unidad_medida,
															concentracion
														FROM
														   g_catalogos.composicion_inocuidad
														WHERE
															id_producto =  p.id_producto
														ORDER BY
																ingrediente_activo
													) r_p) as composicion,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															nombre as nombre_ff,
															pais_origen
														FROM
															g_catalogos.fabricante_formulador
														WHERE
															id_producto =  p.id_producto
														ORDER BY
															nombre
													) r_p) as formulador,
													sp.nombre as subtipo_producto,
													pin.formulacion,
													pin.dosis ||' '|| pin.unidad_dosis as dosis,
													pin.categoria_toxicologica,
													pin.periodo_reingreso,
													pin.periodo_carencia_retiro,
													pin.observacion,
													CASE WHEN p.estado=1 THEN 'Vigente'
													WHEN p.estado=2 THEN 'Suspendido'
													WHEN p.estado=3 THEN 'Caducado'
													WHEN p.estado=4 THEN 'Cancelado'
													END as estado
												FROM g_catalogos.productos as p
													FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
													FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
													FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
													FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
												WHERE
													tp.id_area = 'IAP' and tp.nombre!='Cultivo'
													and p.estado not in (9)
											) as productosRPIP;");
	
		return $res;
	}
	
public function reporteImprimirProductosInocuidadVeterinaria($conexion)
	{
		$res = $conexion->ejecutarConsulta("SELECT row_to_json(productosRPIV)
											FROM (SELECT
													pin.numero_registro,
													to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha,
													CASE WHEN p.estado=1 THEN 'Vigente'
														WHEN p.estado=2 THEN 'Suspendido'
														WHEN p.estado=3 THEN 'Caducado'
														WHEN p.estado=4 THEN 'Cancelado'
														WHEN p.estado=9 THEN 'Eliminado'
													END as estado,
													pin.id_operador,
													o.razon_social,
													sp.nombre subtipo_producto,
													p.nombre_comun,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															presentacion,
															unidad_medida
														FROM
														   g_catalogos.codigos_inocuidad
														WHERE
															id_producto =  p.id_producto 
													) r_p) as presentacion,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															ingrediente_activo,
															unidad_medida,
															concentracion
														FROM
															g_catalogos.composicion_inocuidad 
														WHERE
															id_producto =  p.id_producto 
														ORDER BY
															ingrediente_activo
													) r_p) as composicion,
													pin.formulacion,
													pin.dosis ||' '|| pin.unidad_dosis as dosis,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
														   u.nombre_uso,
														   es.nombre as nombre_especie
														FROM
															g_catalogos.producto_inocuidad_uso piu,
															g_catalogos.usos u,
															g_catalogos.especies es 
														WHERE
															piu.id_uso = u.id_uso and
															piu.id_producto = p.id_producto and
															piu.id_especie = es.id_especies
														ORDER BY
															u.nombre_uso
													) r_p) as usos,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
														   nombre as nombre_ff,
															pais_origen
														FROM
														   g_catalogos.fabricante_formulador
														WHERE
															id_producto =  p.id_producto 
														ORDER BY
															nombre
													) r_p) as formulador,
													pin.declaracion_venta,
													pin.observacion,
                                                    fe.apellido ||' '|| fe.nombre as nombre_empleado
												FROM 
													g_catalogos.productos as p
													FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto 
													FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
													FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
													FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
                                                    FULL OUTER JOIN g_uath.ficha_empleado fe ON fe.identificador = p.identificador_creacion
												WHERE
													tp.id_area = 'IAV' and tp.nombre!='Cultivo'
													--and p.estado not in (9)
												ORDER BY 1) as productosRPIV;");
		
		return $res;
	}
	
	//REPORTES DE INOCUIDAD PLAGUICIDA DE LA APLICACIÓN---->"Registro de productos inocuidad"
	
	public function reporteImprimirProductosInsumosFertilizantes($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT row_to_json(productosRPIF)
											FROM (SELECT
													pin.numero_registro,
													to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha,
													CASE WHEN p.estado=1 THEN 'Vigente'
														WHEN p.estado=2 THEN 'Suspendido'
														WHEN p.estado=3 THEN 'Caducado'
														WHEN p.estado=4 THEN 'Cancelado'
													END as estado,
													pin.id_operador,
													o.razon_social,
													sp.nombre subtipo_producto,
													p.nombre_comun,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															presentacion,
															unidad_medida
														FROM
														   g_catalogos.codigos_inocuidad
														WHERE
															id_producto =  p.id_producto
													) r_p) as presentacion,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															ingrediente_activo,
															unidad_medida,
															concentracion
														FROM
															g_catalogos.composicion_inocuidad
														WHERE
															id_producto =  p.id_producto
														ORDER BY
															ingrediente_activo
													) r_p) as composicion,
													pin.formulacion,
													pin.dosis ||' '|| pin.unidad_dosis as dosis,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
														FROM (
															SELECT
															   u.nombre_uso,
															   es.nombre as nombre_especie,
															   pap.nombre_comun as producto_aplicado
															FROM
																g_catalogos.producto_inocuidad_uso piu INNER JOIN g_catalogos.usos u ON piu.id_uso = u.id_uso
																LEFT JOIN g_catalogos.especies es ON piu.id_especie = es.id_especies
																LEFT JOIN g_catalogos.productos pap ON piu.id_aplicacion_producto = pap.id_producto
															WHERE
																piu.id_producto = p.id_producto					
															ORDER BY
																u.nombre_uso
													) r_p) as usos,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
														   nombre as nombre_ff,
															pais_origen
														FROM
														   g_catalogos.fabricante_formulador
														WHERE
															id_producto =  p.id_producto
														ORDER BY
															nombre
													) r_p) as formulador,
													pin.declaracion_venta,
													pin.observacion,
                                                    tp.nombre as tipo_producto,
													p.identificador_creacion,
													fe.apellido ||' '|| fe.nombre as nombre_empleado,
                                                    pin.fecha_revaluacion
												FROM
													g_catalogos.productos as p
													FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
													FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
													FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
													FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
                                                    FULL OUTER JOIN g_uath.ficha_empleado fe ON fe.identificador = p.identificador_creacion
												WHERE
													tp.id_area = 'IAF' and tp.nombre!='Cultivo'
													and p.estado not in (9)
												ORDER BY 1) as productosRPIF;");
	
		return $res;
	}
		
	public function listaTipoProducto($conexion, $tipo=null, $tipoProducto=null, $subtipoProducto=null){
	
		$columnas = '';
		$busqueda = '';
	
		switch ($tipo){
	
			case 'tipoProducto':
				$columnas= " distinct (tp.id_tipo_producto), tp.nombre, tp.id_area";
				$busqueda="and tp.id_area='SV'";
				break;
			case 'subtipoProducto':
				$columnas= " distinct (stp.id_subtipo_producto), stp.nombre";
				$busqueda = "and tp.id_tipo_producto = '$tipoProducto'";
				break;
			case 'producto':
				$columnas = "distinct (p.id_producto), p.nombre_comun";
				$busqueda = "and stp.id_subtipo_producto = '$subtipoProducto'";
				break;
	
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												".$columnas."
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos stp,
												g_catalogos.tipo_productos tp,
												g_catalogos.productos_variedades pv,
												g_catalogos.variedades v
											WHERE
												v.id_variedad=pv.id_variedad and
												pv.id_producto = p.id_producto and
												p.id_subtipo_producto = stp.id_subtipo_producto and
												stp.id_tipo_producto = tp.id_tipo_producto
												".$busqueda.";");
		return $res;
	
	}
		
	public function buscarOperacionesProductosVariedades($conexion,$idTipoOperacion){
		$res=$conexion->ejecutarConsulta("SELECT
											pmv.id_producto,
											p.nombre_comun,
											pmv.id_tipo_operacion,
											top.nombre,
											CASE   WHEN pmv.multiple_variedad = 'true'
											 THEN 'SI'
															ELSE 'NO'

										     END AS multiple_variedad
										FROM
											g_catalogos.productos_multiples_variedades pmv,
											g_catalogos.productos p,
											g_catalogos.tipos_operacion top
										WHERE
											pmv.id_tipo_operacion=top.id_tipo_operacion and
											pmv.id_producto=p.id_producto and
											top.id_tipo_operacion='$idTipoOperacion';");
				return $res;
	}
	
	public function obtenerRegimenLaboral ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.regimen_laboral
											WHERE
												estado = 1
											ORDER BY
												id_regimen_laboral asc");
	
		return $res;
	}
	
	public function obtenerGrupoOcupacional ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.grupo_ocupacional
											WHERE
												estado = 1
											ORDER BY
												nombre_grupo asc");
	
		return $res;
	}
	
	public function obtenerModalidadContratoXRegimen ($conexion, $idRegimen){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.modalidad_contrato
											WHERE
												id_regimen_laboral in ($idRegimen) and
												estado = 1
											ORDER BY
												nombre asc");
	
				return $res;
	}
	
	public function obtenerPresupuestoXRegimen ($conexion, $idRegimen){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.presupuestos
											WHERE
												id_regimen_laboral = $idRegimen and
												estado = 1
											ORDER BY
												nombre asc");
	
				return $res;
	}
	
	public function listarVariedadesxProducto($conexion,$idOperacion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos_variedades pv,
												g_catalogos.variedades v,
												g_operadores.operaciones op
												
											WHERE
												pv.id_variedad=v.id_variedad
												and op.id_producto=pv.id_producto
													and op.id_operacion='$idOperacion'");
		return $res;
	}
	
	public function listarCuentasBancarias($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.cuentas_bancarias
											WHERE
												estado = 'Activo';");
		return $res;
	}
	
	public function obtenerTipoSubtipoProductoOperacionMasivo ($conexion,$idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												tp.id_tipo_producto,
												tp.nombre nombretipoproducto,
												stp.id_subtipo_producto,
												stp.nombre nombresubtipoproducto,
												p.id_producto,
												p.nombre_comun,
												tp.id_area
											FROM
												g_catalogos.tipo_productos tp,
												g_catalogos.subtipo_productos stp,
												g_catalogos.productos p
											WHERE
												tp.id_tipo_producto=stp.id_tipo_producto
												and p.id_subtipo_producto=stp.id_subtipo_producto
												and p.id_producto IN ($idProducto)
												order by 1");
				return $res;
	}
	
	public function obtenerPuertoXid ($conexion,$idPuerto){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.puertos
											WHERE
												id_puerto = '$idPuerto';");
		return $res;
	}
	
	public function buscarSerieArete($conexion, $idEspecie, $identificadorProducto){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
												numero_arete, 
												estado
											FROM 
												g_catalogos.serie_aretes 
											WHERE
												numero_arete='$identificadorProducto'
												and id_especie='$idEspecie' ;");
		return $res;
	}
	
	public function obtenerCodigoTipoOperacionXOperacion ($conexion,$idOperacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												(top.codigo||top.id_area)as codigo, top.id_tipo_operacion
											FROM
												g_operadores.operaciones op,
												g_catalogos.tipos_operacion top
											WHERE
												op.id_tipo_operacion= top.id_tipo_operacion
												and op.id_operacion=$idOperacion;");
		return $res;
	}
	
	
	//***********nuevas vacunacion
	public function listaLaboratoriosVacuna($conexion){
		$Laboratorio = $conexion->ejecutarConsulta("select 
														*
													from
														g_catalogos.laboratorios
													where
														estado='activo';");
		while ($fila = pg_fetch_assoc($Laboratorio)){
			$res[] = array(
					'id_laboratorio'=>$fila['id_laboratorio'],
					'nombre_laboratorio'=>$fila['nombre_laboratorio'],
					'id_especie'=>$fila['id_especie'],
					'nombre_especie'=>$fila['nombre_especie'],
					'codigo'=>$fila['codigo']);
		}
		return $res;
	}
	
	public function listaTipoVacunas($conexion){
		$query = $conexion->ejecutarConsulta("select
				                                *
											from
				                               g_catalogos.tipo_vacunas
											where
				                                estado='activo' ;");
	
		while ($fila = pg_fetch_assoc($query)){
			$res[] = array(
					'id_tipo_vacuna'=>$fila['id_tipo_vacuna'],
					'id_especie'=>$fila['id_especie'],
					'costo'=>$fila['costo'],
					'nombre_vacuna'=>$fila['nombre_vacuna']
			);
		}
	
		return $res;
	
	}
	
	/////agregar 
	public function obtenerEspecieXcodigo($conexion,$codigo){
		$res = $conexion->ejecutarConsulta("select
												id_especies,
												nombre,
												estado
											from
												g_catalogos.especies
											where
												estado = 'activo'
												and codigo = '$codigo' ");
		return $res;
	
	}
	
	public function buscarDireccionGestionPuesto($conexion, $idArea){
	
		$res = $conexion->ejecutarConsulta("SELECT
												(SELECT a1.nombre from g_estructura.area a1 where a1.id_area = a.id_area_padre) as nombre_area_padre, a.nombre,p.nombre_puesto, p.id_puesto
												--a.id_area_padre,a.id_area,p.nombre_puesto, p.id_puesto
											FROM
												g_catalogos.puestos p,
												g_estructura.area a
											WHERE
												a.id_area = p.id_area
												and p.estado=1
												and p.id_area in (select
												id_area
											FROM
												g_estructura.area
											WHERE
												id_area_padre = '$idArea'
											UNION
											SELECT
												id_area
											FROM
												g_estructura.area
											WHERE
												id_area = '$idArea'
												order by
												id_area asc);");
				return $res;
	}
	
	public function listarFunciones ($conexion){
	$res = $conexion->ejecutarConsulta("SELECT
											*
										FROM
											g_catalogos.funciones;");
		return $res;
	}
	
	public function ListarFuncionesXPuesto ($conexion, $idPuesto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.puestos_funciones pf,
												g_catalogos.puestos p,
												g_catalogos.funciones f
											WHERE
												f.id_funcion=pf.id_funcion
												and p.id_puesto=pf.id_puesto
												and p.id_puesto=$idPuesto;");
			return $res;
		}
	
	
		public function imprimirFuncionesXPuesto($idPuesto,$idFuncion,$funcion){
		return '<tr id="R' . $idPuesto . '-'.$idFuncion.'">' .
					'<td width="100%">' . $funcion .
					'</td>' .
					'<td>' .
						'<form class="borrar" data-rutaAplicacion="uath" data-opcion="eliminarFunciones">' .
							'<input type="hidden" name="idPuesto" value="' . $idPuesto . '" >' .
							'<input type="hidden" name="idFuncion" value="' . $idFuncion . '" >' .
							'<button type="submit" class="icono"></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function guardarFunciones ($conexion,$descripcion){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.funciones (descripcion)
											VALUES
												('$descripcion')RETURNING id_funcion;");
	
	return $res;
	}
	
		public function guardarDetallePuestoFuncion ($conexion,$idPuesto,$idFuncion){
			
			$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.puestos_funciones(id_puesto, id_funcion)
											VALUES
												('$idPuesto','$idFuncion');");
	
		return $res;
	}
	
	public function buscarDetallePuestoFuncion ($conexion, $idPuesto, $idFuncion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.puestos_funciones
											WHERE
												id_puesto='$idPuesto'
												and id_funcion='$idFuncion';");
	return $res;
	
	}
	
	public function quitarPuestosFunciones ($conexion,$idPuesto,$idFuncion){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.puestos_funciones
											WHERE
												id_puesto='$idPuesto'
												and id_funcion=$idFuncion;");
	
	return $res;
	}
	
	
	public function obtenerPuestos ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.puestos
											WHERE
												estado=1;");
		return $res;
	}
	
	public function buscarProductoProgramaNulo($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos
											WHERE
												id_producto = $idProducto and
												programa is NOT NULL;");
				return $res;
	}
	
	public function ObtenerProductoPorId($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos
											WHERE
												id_producto = $idProducto;");
				return $res;
	}
	
	public function obtenerDatosBancarioPorNombre($conexion,$nomrbeEntidadBancaria){
				
			$res = $conexion->ejecutarConsulta("SELECT
													e.id_banco,
													e.nombre,
													c.id_cuenta_bancaria,
													c.numero_cuenta
											    FROM 
													g_catalogos.cuentas_bancarias c,
													g_catalogos.entidades_bancarias e
												WHERE 
													c.id_banco=e.id_banco
													and e.nombre='$nomrbeEntidadBancaria';");
 		return $res;
		
	}
	
	public function obtenerDatosBancarioPorCodigoVue($conexion,$codigoVue){
	
		$res = $conexion->ejecutarConsulta("SELECT
												e.id_banco,
												e.nombre,
												c.id_cuenta_bancaria,
												c.numero_cuenta
											FROM
												g_catalogos.cuentas_bancarias c,
												g_catalogos.entidades_bancarias e
											WHERE
												c.id_banco=e.id_banco
												and e.codigo_vue='$codigoVue';");
		return $res;
	
	}
	
	public function buscarIdOperacionPorCodigoOperacion ($conexion, $codigoArea){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_catalogos.tipos_operacion
											where
												codigo||id_area = '$codigoArea';");
		return $res;
	}
	
	public function listaLaboratoriosMaterialesPeligrosos ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												id_laboratorio,
												nombre_laboratorio
											FROM
												g_catalogos.laboratorios_materiales_peligrosos where id_laboratorio_padre is  null
											ORDER BY 2 asc;");
		return $res;
	}
	
	public function listaGuiasMaterialesPeligrosos ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												id_guia_material_peligroso,
												numero_guia_material_peligroso,
												nombre_guia_material_peligroso, 
      											ruta_guia_material_peligroso
  											FROM 
												g_catalogos.guias_materiales_peligrosos 
											ORDER BY 2 ASC;");
		return $res;
	}

	public function listaClasificacionRiegosMaterialesPeligrosos ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
  											FROM 
												g_catalogos.clasificacion_riesgos_materiales_peligrosos 
											ORDER BY 2 ASC;");
		return $res;
	}
	
	public function listaMaterialesPeligrosos ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
  												FROM g_catalogos.materiales_peligrosos
											ORDER BY 2 ASC;");
		return $res;
	}
	
	/*public function jsonListarCantonesPorProvincia($conexion, $provincia, $categoria){
		
		$busqueda = '';
		switch ($categoria){
			case 'PAIS': $busqueda = 'categoria = 0'; break;
			case 'PROVINCIAS': $busqueda = 'categoria = 1'; break;
			case 'CANTONES': $busqueda = 'categoria = 2'; break;
			case 'SITIOS': $busqueda = 'categoria = 3'; break;
			case 'PARROQUIAS': $busqueda = 'categoria = 4'; break;
		}
		
		$res = $conexion->ejecutarConsulta("select row_to_json(cantones)
												from (
													select array_to_json(array_agg(row_to_json(listado)))
													from (select
															nombre
														from
															g_catalogos.localizacion
														where
															" . $busqueda ." 
															and id_localizacion_padre = (SELECT id_localizacion FROM  g_catalogos.localizacion WHERE categoria = 1 and UPPER(nombre) = UPPER('$provincia')) 
														order by 1
													) as listado)
											as cantones;");
				$json = pg_fetch_assoc($res);
		return json_decode($json[row_to_json],true);
	}*/
	
	public function obtenerLocalizacionHijo ($conexion, $categoriaHijo,$categoriaPadre, $idLocalizacionPadre){
	
		switch ($categoriaHijo){
			case 'PAIS': $categoriaHijo = 'categoria = 0'; break;
			case 'PROVINCIAS': $categoriaHijo = 'categoria = 1'; break;
			case 'CANTONES': $categoriaHijo = 'categoria = 2'; break;
			case 'SITIOS': $categoriaHijo = 'categoria = 3'; break;
			case 'PARROQUIAS': $categoriaHijo = 'categoria = 4'; break;
		}
	
		switch ($categoriaPadre){
			case 'PAIS':$categoriaPadre = 'categoria = 0'; break;
			case 'PROVINCIAS':$categoriaPadre = 'categoria = 1'; break;
			case 'CANTONES':$categoriaPadre = 'categoria = 2'; break;
			case 'SITIOS':$categoriaPadre = 'categoria = 3'; break;
			case 'PARROQUIAS':$categoriaPadre = 'categoria = 4'; break;
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.localizacion
											WHERE
												" . $categoriaHijo ."
												and id_localizacion_padre = (SELECT
																					id_localizacion
																			  FROM
																					g_catalogos.localizacion
																			  WHERE
																					" . $categoriaPadre ."
																					and id_localizacion = $idLocalizacionPadre)
											ORDER BY 2;");
				return $res;
	}
	
	public function obtenerCodigoTipoOperacion($conexion,$idOperacion){		
	
		$res = $conexion->ejecutarConsulta("SELECT
												top.codigo, 
												top.id_tipo_operacion,
												top.id_area,
												top.nombre
											FROM
												g_operadores.operaciones op,
												g_catalogos.tipos_operacion top
											WHERE
												op.id_tipo_operacion= top.id_tipo_operacion
												and op.id_operacion=$idOperacion;");
		return $res;
	}
	
	public function obtenerCodigoTipoOperacionxIdTipoOperacion($conexion,$idTipoOperacion){
		
	
		$res = $conexion->ejecutarConsulta("SELECT 
												codigo
											FROM 
												g_catalogos.tipos_operacion 
											WHERE 
												id_tipo_operacion=$idTipoOperacion;");
		return $res;
	}
		
	public function reporteImprimirProductosInsumosPlantasAutoconsumo($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT row_to_json(productosRPIAPA)
											FROM (SELECT
													pin.numero_registro,
													to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha,
													CASE WHEN p.estado=1 THEN 'Vigente'
														WHEN p.estado=2 THEN 'Suspendido'
														WHEN p.estado=3 THEN 'Caducado'
													END as estado,
													pin.id_operador,
													o.razon_social,
													sp.nombre subtipo_producto,
													p.nombre_comun,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															presentacion,
															unidad_medida
														FROM
														   g_catalogos.codigos_inocuidad
														WHERE
															id_producto =  p.id_producto
													) r_p) as presentacion,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
															ingrediente_activo,
															unidad_medida,
															concentracion
														FROM
															g_catalogos.composicion_inocuidad
														WHERE
															id_producto =  p.id_producto
														ORDER BY
															ingrediente_activo
													) r_p) as composicion,
													pin.formulacion,
													pin.dosis ||' '|| pin.unidad_dosis as dosis,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
														   u.nombre_uso,
														   es.nombre as nombre_especie
														FROM
															g_catalogos.producto_inocuidad_uso piu,
															g_catalogos.usos u,
															g_catalogos.especies es
														WHERE
															piu.id_uso = u.id_uso and
															piu.id_producto = p.id_producto and
															piu.id_especie = es.id_especies
														ORDER BY
															u.nombre_uso
													) r_p) as usos,
													(SELECT array_to_json(array_agg(row_to_json(r_p)))
													FROM (
														SELECT
														   nombre as nombre_ff,
															pais_origen
														FROM
														   g_catalogos.fabricante_formulador
														WHERE
															id_producto =  p.id_producto
														ORDER BY
															nombre
													) r_p) as formulador,
													pin.declaracion_venta,
													pin.observacion
												FROM
													g_catalogos.productos as p
													FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
													FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
													FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
													FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
												WHERE
													tp.id_area = 'IAPA' and tp.nombre!='Cultivo'
												ORDER BY 1) as productosRPIAPA;");
	
		return $res;
	}
	
	public function listarCatalogoLugarInspeccion($conexion, $tipo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.lugares_inspeccion
											WHERE
												tipo_lugar_inspeccion = '$tipo'
											ORDER BY 
													2;");
	
		return $res;
	}
	
	public function listarTipoProductosXAreaCodificacion($conexion, $idArea, $codificacion){
	
		$consulta="SELECT
						*
					FROM
						g_catalogos.tipo_productos
					WHERE
						id_area = '$idArea' and
						codificacion_tipo_producto in($codificacion)
						ORDER BY 2;";
	
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function listarMediosTrasporteXid($conexion,$idMedio){
		$res = $conexion->ejecutarConsulta("SELECT
												id_medios_transporte,
												tipo
											FROM
												g_catalogos.medios_transporte
											WHERE
												id_medios_transporte='$idMedio';");
		return $res;
	}
	
	public function listarSubProductosXareas($conexion,$idArea){
		
		$res = $conexion->ejecutarConsulta("SELECT
												dp.id_subtipo_producto,
												dp.nombre,
												tp.id_area,
												dp.id_tipo_producto
											FROM
												g_catalogos.subtipo_productos dp,
												g_catalogos.tipo_productos tp
											WHERE
												dp.id_tipo_producto = tp.id_tipo_producto
												and tp.id_area $idArea
												order by 2;");

		return $res;
	}

	public function listarTipoProductosXareas($conexion, $idArea){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.tipo_productos
											WHERE
												id_area $idArea
												order by 2;");
		return $res;
	}
	
	public function obtenerTipoProductosXid($conexion, $idTipoProducto){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.tipo_productos
											WHERE
												id_tipo_producto='$idTipoProducto';");
				return $res;
	}
	
	public function obtenerSubTipoProductosXidTipo($conexion, $idProducto){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_catalogos.subtipo_productos								
											WHERE
												id_tipo_producto = $idProducto;");
				return $res;
	}
	
	public function obtenerSubTipoProductosXid($conexion, $idSubTipoProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.subtipo_productos
											WHERE
												id_subtipo_producto = $idSubTipoProducto;");
				return $res;
	}
	
	public function obtenerSubTipoProductosPorCodigo($conexion, $codigoSubtipoProducto){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.subtipo_productos
											WHERE
												codificacion_subtipo_producto = '$codigoSubtipoProducto';");
	    return $res;
	}
	
	public function listarLugarInstalacion($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.lugar_instalacion;");
		return $res;
	}
	
	public function listarPlagaMonitoreada($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.plaga;");
		return $res;
	}
	
	public function listarTipoTrampa($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.tipo_trampa;");
		return $res;
	}
	
	public function listarTipoAtrayente($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.tipo_atrayente;");
		return $res;
	}
	
	
	public function listarTrampasLocalizacion($conexion,$tipo){
		$cid = $this->listarLocalizacion($conexion, $tipo);
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(codigoProvincia=>$fila['codigo'],codigo=>$fila['id_localizacion'],nombre=>$fila['nombre']);
		}
		return $res;
	}
	
	public function listarAreasTrampas($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.areas_trampas;");
		return $res;
	}
	
	public function guardarRequerimientoRevisionIngreso($conexion,$nombre,$descripcion,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.requerimiento_revision_ingreso(nombre, descripcion, estado, fecha_registro,usuario_responsable)
    										VALUES 
											('$nombre', '$descripcion', 'activo', now(),'$usuarioResponsable') RETURNING id_requerimiento;");
				return $res;
	}
	
	public function listarRequerimientoRevisionIngreso($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_requerimiento, nombre, descripcion, estado, to_char(fecha_registro,'YYYY-MM-DD') fecha_registro
											FROM 
												g_catalogos.requerimiento_revision_ingreso 
											WHERE estado='activo' ORDER BY 2;");
		return $res;
	}
	
	public function guardarRequerimientoElemento($conexion,$idRequerimiento,$nombre,$descripcion,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.requerimiento_elementos(
        										id_requerimiento, nombre, descripcion, fecha_registro, estado, usuario_responsable)
   											VALUES ( $idRequerimiento, '$nombre', '$descripcion', now(),'activo','$usuarioResponsable') RETURNING id_requerimiento_elemento;");
		return $res;
	}
	
	public function guardarEnfermedadAnimal($conexion,$nombre,$descripcion, $observacion,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.enfermedades_animales(
             									nombre, descripcion, observacion, estado, fecha_registro, usuario_responsable)
    										VALUES ('$nombre', '$descripcion', '$observacion', 'activo', now(),'$usuarioResponsable') RETURNING id_enfermedad;");
		return $res;
	}
	
	public function listarEnfermedadesAnimales($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad, nombre, descripcion, observacion, estado, to_char(fecha_registro,'YYYY-MM-DD') fecha_registro
										  	FROM 
												g_catalogos.enfermedades_animales
											WHERE 
												estado='activo' ORDER BY 2;");
		return $res;
	}
	
	public function guardarEnfermedadAnimalProducto($conexion,$idProducto,$idSubTipoProducto, $idTipoProducto,$idEnfermedad,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.enfermedad_animal_producto(
									      		id_producto, id_subtipo_producto, id_tipo_producto, estado, fecha_registro, id_enfermedad, usuario_responsable)
									    	VALUES 
												($idProducto, $idSubTipoProducto, $idTipoProducto, 'activo', now(), $idEnfermedad,'$usuarioResponsable') RETURNING id_enfermedad_producto;");
		return $res;
	}
	
	public function guardarZonas($conexion,$nombre,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.zonas(
           										nombre, estado, fecha_registro,usuario_responsable)
    										VALUES 
												('$nombre', 'activo',now(),'$usuarioResponsable') RETURNING id_zona;");
		return $res;
	}
	
	public function listarZonas($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_zona, nombre, estado, to_char(fecha_registro,'YYYY-MM-DD') fecha_registro
  											FROM 
												g_catalogos.zonas
											where estado='activo' ORDER BY 2;");
		return $res;
	}
	
	public function guardarZonasPaises($conexion,$idZona,$nombre,$idPais,$usuarioResponsable){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.paises_zonas(
            									id_zona, nombre, estado, fecha_registro,id_pais,usuario_responsable)
    										VALUES 
												( $idZona, '$nombre', 'activo', now(),$idPais,'$usuarioResponsable') RETURNING id_pais_zona ;");
		return $res;
	}
	
	public function listarPaisesZonas($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_pais_zona, id_zona, nombre, estado, fecha_registro, id_pais
  											FROM 
												g_catalogos.paises_zonas
											WHERE
												estado='activo' ORDER BY 3;");
		return $res;
	}
	
	public function listarEnfermedadesAnimalesProducto($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_enfermedad_producto, id_producto, id_subtipo_producto, id_tipo_producto, 
      											estado, fecha_registro, id_enfermedad
 											FROM 
												g_catalogos.enfermedad_animal_producto
											WHERE 
												estado='activo';");
		return $res;
	}
	
	public function obtenerTiposOperacionPorIdAreaTematica($conexion, $idAreaTematica){

		$res = $conexion->ejecutarConsulta("SELECT
												id_tipo_operacion, nombre
											FROM
												g_catalogos.tipos_operacion
											WHERE
												id_area = '$idAreaTematica'
                                                and estado = 1;");
		return $res;
	
	}
	
	public function buscarTipoOperacionporIdareaOperador($conexion,$idArea,$producto,$operador){
	    
	    $consulta="SELECT distinct 
                    	tp.id_tipo_operacion, tp.nombre, tp.codigo, tp.id_area
                    FROM 
                    	g_catalogos.tipos_operacion tp, g_operadores.operaciones op, g_catalogos.productos p 
                    WHERE 
                    	tp.id_area='$idArea' 
                    	and op.id_producto=$producto 
                    	and op.id_tipo_operacion= tp.id_tipo_operacion 
                    	and op.estado IN ('registrado','registradoObservacion')
                    	and (p.id_producto=$producto 
                    	and p.trazabilidad ='SI' )
                    	and p.id_producto = op.id_producto
                    	and op.identificador_operador='$operador';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	    
	}
	
	public function buscarNombreAreaporTipoOperacion($conexion,$tipoOperacion){
	    
	    $consulta="SELECT
						aop.*
					FROM
						g_catalogos.tipos_operacion top,
						g_catalogos.areas_operacion aop
					WHERE
						aop.id_tipo_operacion = top.id_tipo_operacion
						and top.id_tipo_operacion = $tipoOperacion;";    
	    
	    $res = $conexion->ejecutarConsulta($consulta);
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
												o.identificador_operador = '$identificadorOperador';");
		return $res;
	}
	
	public function buscarTipoOperacionMultiple($conexion, $tipoOperacion){

		$res = $conexion->ejecutarConsulta("SELECT 
												operacion_multiple
  											FROM 
												g_catalogos.tipos_operacion
											WHERE
												id_tipo_operacion = $tipoOperacion;");
		return $res;
	}
	
	public function obtenerDatosCursoPorAreaGestionNomenclaturaEstado($conexion, $idArea, $idGestion, $nomenclatura, $estado){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
  											FROM
												g_catalogos.cursos
											WHERE
												id_area = '$idArea' and
                                                id_gestion = '$idGestion' and 
                                                nomenclatura = '$nomenclatura' and 
                                                estado = '$estado';");
	    return $res;
	}

	public function obtenerFirmasResponsablePorProvincia($conexion, $nombreProvincia, $idArea = 'SA'){
		
		$consulta = "SELECT
						*
  					FROM
						g_catalogos.responsables_certificados
					WHERE 
						'$idArea' = ANY (id_area)
						and nombre_provincia ilike '%$nombreProvincia%' 
						and estado = 'Activo';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerTipoTransicionXIdTipoTransicion($conexion, $idTipoTransicion){
	    $res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_catalogos.tipo_transicion
                                            WHERE
                                                    id_tipo_transicion = $idTipoTransicion;");
	    return $res;
	}
	
	public function obtenerTipoTransicionXCodigo($conexion, $codigoTipoTransicion){
	    $res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_catalogos.tipo_transicion
                                            WHERE
                                                    codigo_tipo_transicion = '$codigoTipoTransicion';");
	    return $res;
	}
	
	//Grupos de Países
	public function obtenerLocalizacionesGrupo($conexion, $idLocalizacionGrupo){
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_catalogos.localizacion_grupo
                                            WHERE
                                                    id_localizacion_grupo = $idLocalizacionGrupo and
													estado = 'Activo';");
		return $res;
	}
	
	function obtenerFechaFinalDiasLaborables($fechaInicial, $dias){
		
		$diasFestivos = array(
			'01-01' => 'Año nuevo',
			'05-01' => 'Día del trabajo',
			'05-24' => 'Batalla de pichincha',
			'08-10' => 'Primer grito de independencia',
			'09-09' => 'Independencia de Guayaquil',
			'11-02' => 'Día de los difuntos',
			'11-03' => 'Independencia de Cuenca',
			'12-25' => 'Navidad');
		
		$finSemana = array(
			'Sun' => '',
			'Sat' => '');
		
		$fechaInicio = new DateTime($fechaInicial); // recuerda solo mes y dia
		$fechaSiguiente = clone $fechaInicio;
		$i = 0;
		$fechaFinal = '';
		
		while ($i < $dias){
			$fechaSiguiente->add(new DateInterval('P1D'));
			if (isset($diasFestivos[$fechaSiguiente->format('m-d')]))
				continue;
				if (isset($finSemana[$fechaSiguiente->format('D')]))
					continue;
					$fechaFinal = $fechaSiguiente->format('Y-m-d');
					$i ++;
		}
		
		return $fechaFinal;
	}
	
	/********** CATÁLOGO EMPRESAS *************/
	public function listarEmpresa($conexion, $identificador){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												identificador,
												razon_social as nombre_operador
											FROM
												g_catalogos.empresa_producto_plaguicida
											WHERE
												identificador = '$identificador'
											ORDER BY
												2");
	    return $res;
	}
	
	public function guardarEmpresa($conexion, $identificador, $razonSocial){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO
                                                g_catalogos.empresa_producto_plaguicida(identificador, razon_social)
                                            VALUES ('".$identificador."', '".$razonSocial."');");
	    return $res;
	}
	
	/********** CATÁLOGO ADITIVOS **********/	
	
	public function listarAditivos($conexion){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.aditivo_toxicologico
											ORDER BY
												area, nombre_comun ASC;");
	    return $res;
	}
	
	public function guardarNuevoAditivo ($conexion, $idArea, $nombreComun, $nombreQuimico, $cas, $formulaQuimica, $grupoQuimico, $identificador){
	    $res = $conexion->ejecutarConsulta("INSERT INTO
											  g_catalogos.aditivo_toxicologico(identificador, area, nombre_comun, nombre_quimico, cas, formula_quimica, grupo_quimico)
											VALUES 	('$identificador', '$idArea', '$nombreComun', '$nombreQuimico', '$cas', '$formulaQuimica', '$grupoQuimico');");
	    return $res;
	}
	
	public function abrirAditivo($conexion, $idAditivo){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.aditivo_toxicologico
											WHERE
												id_aditivo_toxicologico = $idAditivo;");
	    return $res;
	}
	
	public function actualizarAditivo($conexion, $idAditivo, $idArea, $nombreComun, $nombreQuimico, $cas, $formulaQuimica, $grupoQuimico, $identificador){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.aditivo_toxicologico
											SET
												identificador	= '$identificador',
												area = '$idArea',
												nombre_comun = '$nombreComun',
                                                nombre_quimico	= '$nombreQuimico',
												cas = '$cas',
												formula_quimica = '$formulaQuimica',
                                                grupo_quimico = '$grupoQuimico'
											WHERE
												id_aditivo_toxicologico = $idAditivo;");
	    return $res;
	}
	
	public function listarAditivosXArea($conexion, $idArea){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.aditivo_toxicologico
                                            WHERE
                                                area = '$idArea'
											ORDER BY
												area, nombre_comun ASC;");
	    return $res;
	}
	
	
	
	/****** CATALOGO CULTIVOS ********/
	public function buscarCultivoPlaguicida ($conexion, $area,$nombreComun, $nombreCientifico){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.cultivos
											WHERE
												upper(nombre_comun_cultivo) ilike upper('$nombreComun') and
												upper(nombre_cientifico_cultivo) ilike upper('$nombreCientifico') and
												id_area = '$area';");
	    return $res;
	}
	
	public function guardarNuevoCultivo ($conexion, $idArea, $nombreComun, $nombreCientifico){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO
											  g_catalogos.cultivos(nombre_comun_cultivo, nombre_cientifico_cultivo, id_area)
											VALUES 	('$nombreComun', '$nombreCientifico', '$idArea');");
	    return $res;
	}
	
	public function listarCultivos($conexion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.cultivos
											ORDER BY
												nombre_comun_cultivo ASC;");
	    return $res;
	}
	
	public function abrirCultivo($conexion, $idCultivo){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.cultivos
											WHERE
												id_cultivo = $idCultivo;");
	    return $res;
	}
	
	public function actualizarCultivo($conexion, $idCultivo, $nombreCientifico, $nombreComun, $idArea){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.cultivos
											SET
												nombre_cientifico_cultivo = '$nombreCientifico',
												nombre_comun_cultivo = '$nombreComun',
												id_area = '$idArea'
											WHERE
												id_cultivo = $idCultivo;");
	    return $res;
	}
	
	public function listarCultivosXArea($conexion, $area){
	    $res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									            g_catalogos.cultivos
											WHERE
												id_area = '$area'
											ORDER BY
												nombre_cientifico_cultivo ASC;");
	    return $res;
	}
	
	/************* REPORTE PRODUCTO PLAGUICIDA **************/
	public function reporteProductoPlaguicidaVUE($conexion, $idProducto)
	{
	    $res = $conexion->ejecutarConsulta("SELECT
                                            	pa.partida_arancelaria,
                                            	ccs.codigo_complementario,
                                            	ccs.codigo_suplementario,
                                            	pa.codigo_producto,
                                            	p.nombre_comun	
                                            FROM
                                            	g_catalogos.productos p
                                            	INNER JOIN g_catalogos.partidas_arancelarias pa ON p.id_producto = pa.id_producto
                                            	INNER JOIN g_catalogos.codigos_comp_supl ccs ON pa.id_partida_arancelaria = ccs.id_partida_arancelaria
                                                INNER JOIN g_catalogos.presentaciones_plaguicidas pp ON ccs.id_codigo_comp_supl = pp.id_codigo_comp_supl
                                            WHERE
                                            	p.id_producto = $idProducto and
                                            	p.estado not in (9) and
                                                pa.estado = 'activo' and
	                                            ccs.estado = 'activo' and
                                                pp.estado = 'activo';");
	    
	    return $res;
	}
	
	public function reporteProductoPresentacionPlaguicidaVUE($conexion, $idProducto, $partidaArancelaria, $codComp, $codSupl)
	{
	    $res = $conexion->ejecutarConsulta("SELECT
                                            	pa.partida_arancelaria,
                                            	ccs.codigo_complementario,
                                            	ccs.codigo_suplementario,
                                            	pa.codigo_producto,
                                            	pp.codigo_presentacion,
                                            	p.nombre_comun,	
                                            	pp.presentacion,
                                            	pp.unidad	
                                            FROM
                                            	g_catalogos.productos p
                                            	INNER JOIN g_catalogos.partidas_arancelarias pa ON p.id_producto = pa.id_producto
                                            	INNER JOIN g_catalogos.codigos_comp_supl ccs ON pa.id_partida_arancelaria = ccs.id_partida_arancelaria
                                            	INNER JOIN g_catalogos.presentaciones_plaguicidas pp ON ccs.id_codigo_comp_supl = pp.id_codigo_comp_supl
                                            WHERE
                                            	p.id_producto = $idProducto and
                                                pa.partida_arancelaria = '$partidaArancelaria' and
                                                ccs.codigo_complementario = '$codComp' and
                                                ccs.codigo_suplementario = '$codSupl' and
                                            	p.estado not in (9) and
                                                pa.estado = 'activo' and
                                            	ccs.estado = 'activo' and
                                                pp.estado = 'activo';");
	    
	    return $res;
	}
	
		//**************************obtener Datos firma electrónica  ******************************************
	public function obtenerFirmaElectronica($conexion,$identificador){
		$consulta = "SELECT
                        id_firma_digital_funcionarios,
                    	nombre_firma,
                        ubicacion,
                        razon,
                        info_contacto,
                        ruta_certificado,
                        clave
                    FROM
                    	g_catalogos.firma_digital_funcionarios
                    WHERE
                        identificador = '" . $identificador . "' and
                        estado = 'activo';";
		
		return $conexion->ejecutarConsulta( $consulta );
	}
	// ************************** obtener datos certificado ******************************************
	public function obtenerDatosCertificado($conexion,$identificador){
		$consulta = pg_fetch_assoc($this->obtenerFirmaElectronica($conexion,$identificador));
		$id = rtrim($identificador);
		$scr = crc32($id);
		$key = hash('sha256', $scr);
		$claveCifrada = $consulta['clave'];
		$password = Encriptar::decryptOpen($claveCifrada, $key);
		$certificate = 'file://' . $consulta['ruta_certificado'];
		$info = array(
			'Name' => $consulta['nombre_firma'],
			'Location' => $consulta['ubicacion'],
			'Reason' => $consulta['razon'],
			'ContactInfo' => $consulta['info_contacto']
		);
		$datos = array();
		$datos['rutaCertificado'] = $certificate;
		$datos['info'] = $info;
		$datos['password'] = $password;
		
		return $datos;
	}
	// ------------------------validar si existen firmas digitales-------------------------------------------
	public function verificarFirmaDigitalFuncionarios($conexion, $identificador){
		$sqlScript = "select
							*
						from
							g_catalogos.firma_digital_funcionarios fdf
						where
							fdf.estado = 'activo' and
							fdf.identificador = '".$identificador."';";
		$res = $conexion->ejecutarConsulta($sqlScript);
		return $res;
	}
	//*******************************************************************************************************
	
	public function obtenerGuiaBuenasPracticas($conexion, $tipo){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.guias_buenas_practicas
                                            WHERE
                                                estado = 'Activo' and
                                                tipo in ($tipo)
                                            ORDER BY
                                                numero_resolucion, nombre_resolucion ASC;");
		return $res;
	}
	
	public function obtenerZonaLocalizacion ($conexion, $idLocalizacion, $categoria){
		
		$res = $conexion->ejecutarConsulta("select
												zona
											from
												g_catalogos.localizacion
											where
												id_localizacion = $idLocalizacion and
                                                categoria = $categoria;");
		return $res;
	}
	
	 public function listarTipoOperacionSV($conexion){
		 
        $consulta = "SELECT 
                            id_tipo_operacion, nombre, codigo, id_area, estado, requiere_anexo, 
                            id_flujo_operacion, trazabilidad_tipo_operacion, 
                            ubicacion_revision, operacion_multiple
                        FROM 
                            g_catalogos.tipos_operacion
                        where 
                            id_area = 'SV'
                        order by 2";

        $res = $conexion->ejecutarConsulta($consulta);
		
        return $res;
    }

   
    public function listarProdcutoSV($conexion){
		
        $consulta = "SELECT 
                        id_tipo_producto, nombre, estado, id_area, fecha_creacion, fecha_modificacion, 
                        codificacion_tipo_producto
                    FROM 
                        g_catalogos.tipo_productos
                    WHERE
                        id_area = 'SV'
                    ORDER BY 2";    

        $res = $conexion->ejecutarConsulta($consulta);
		
        return $res;
    }
	
	public function buscarRangoSerieArete($conexion, $idEspecie, $identificadorProducto, $estado){
        
        $res = $conexion->ejecutarConsulta("SELECT COUNT(DISTINCT numero_arete) cantidad_arete
											FROM
												g_catalogos.serie_aretes
											WHERE
												numero_arete = ANY(".$identificadorProducto.") AND estado = '$estado'
												AND id_especie ='$idEspecie';");
        return $res;
    }
	
	public function listarDeclaracionVenta($conexion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									             g_catalogos.declaracion_venta
											WHERE
												estado_declaracion_venta = 'Activo'
											ORDER BY
												declaracion_venta;");
	    return $res;
	}
	
	public function listarParametrosMetodoRangoPorProducto($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("select
													pa.id_parametro, pa.descripcion as descripcion_parametro, 
													m.id_metodo, m.descripcion as descripcion_metodo, r.id_rango, r.descripcion as descripcion_rango 
											from
													g_catalogos.productos p
													INNER JOIN g_catalogos.parametros pa ON p.id_producto = pa.id_producto
													INNER JOIN g_catalogos.metodos m ON pa.id_parametro = m.id_parametro
													INNER JOIN g_catalogos.rangos r ON m.id_metodo = r.id_metodo
											where
													p.id_producto = '$idProducto'
													and pa.estado = 'Activo'
													and m.estado = 'Activo'
													and r.estado = 'Activo'
											order by 2;");
		
		return $res;
	}
	
	public function listarProductoParametrosMetodoPorRango($conexion, $idRango){
		$res = $conexion->ejecutarConsulta("select
													pa.id_parametro, pa.descripcion as descripcion_parametro,
													m.id_metodo, m.descripcion as descripcion_metodo, r.id_rango, r.descripcion as descripcion_rango
											from
													g_catalogos.productos p
													INNER JOIN g_catalogos.parametros pa ON p.id_producto = pa.id_producto
													INNER JOIN g_catalogos.metodos m ON pa.id_parametro = m.id_parametro
													INNER JOIN g_catalogos.rangos r ON m.id_metodo = r.id_metodo
											where
													r.id_rango = '$idRango'
													and pa.estado = 'Activo'
													and m.estado = 'Activo'
													and r.estado = 'Activo'
											order by 2;");
		
		return $res;
	}
	
	//Programas de control Oficial
    //Catastro Predio de Équidos
	
	public function listarEspeciesXCodigo($conexion, $codigo){

	    $res = $conexion->ejecutarConsulta("SELECT 
												id_especies, 
                                                nombre,
                                                codigo
											 FROM 
                                                g_catalogos.especies
											 WHERE 
												estado = 'activo' and
                                                codigo in ($codigo);");																					
	    return $res;
	}
	
	public function listarRazaXCodigoEspecie($conexion, $codigo){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												r.id_raza,
                                                r.raza
											 FROM
                                                g_catalogos.raza r
                                                INNER JOIN g_catalogos.especies e ON r.id_especie = e.id_especies
											 WHERE
												r.estado_raza = 'Activo' and
                                                e.codigo in ($codigo);");
	    return $res;
	}
	
	public function listarCategoriaXCodigoEspecie($conexion, $codigo){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												ce.id_categoria_especie,
                                                ce.categoria_especie
											 FROM
                                                g_catalogos.categoria_especie ce
                                                INNER JOIN g_catalogos.especies e ON ce.id_especie = e.id_especies
											 WHERE
												ce.estado_categoria_especie = 'Activo' and
                                                e.codigo in ($codigo);");
	    return $res;
	}
	
	public function buscarLocalizacionXNombre ($conexion, $nombreLocalizacion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.localizacion
											WHERE
                                                quitar_caracteres_especiales(upper(nombre)) ilike quitar_caracteres_especiales(upper('$nombreLocalizacion')) AND
												categoria =2;");
	    return $res;
	}
																			
}