<?php

class ControladorRequisitos{
	
	public function listarTipoProducto ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT tp.*, a.nombre as nombre_area  
											FROM 
												g_catalogos.tipo_productos as tp,
												g_estructura.area a
											WHERE
												tp.id_area = a.id_area
                                                and tp.estado not in (9)
											ORDER BY 2;");
		return $res;
	}
	
	public function listarTipoProductoAreaCodificacion ($conexion){
	    $res = $conexion->ejecutarConsulta("SELECT tp.*, a.nombre as nombre_area
											FROM
												g_catalogos.tipo_productos as tp,
												g_estructura.area a
											WHERE
												tp.id_area = a.id_area
                                                and tp.estado not in (9) 
                                                and tp.id_area in ('IAF','IAV','IAP')
                                                and tp.codificacion_tipo_producto in ('TIPO_VETERINARIO','TIPO_PLAGUICIDA','TIPO_MATERIA') 
											ORDER BY 2;");
	    return $res;
	}
	
	public function guardarNuevoTipoProducto ($conexion,$nombre,$area){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.tipo_productos (nombre, estado, id_area)
											VALUES
												('$nombre',1,'$area')
											RETURNING 
												id_tipo_producto;");
		return $res;
	}
	
	public function abrirTipoProducto ($conexion,$idTipo){
		$res = $conexion->ejecutarConsulta("SELECT *				
											FROM
												g_catalogos.tipo_productos
											WHERE
											 	id_tipo_producto='$idTipo';");
				return $res;
	}
	
	public function actualizarTipoProducto ($conexion,$idTipo,$nombre,$area){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.tipo_productos
											SET
												nombre = '$nombre',
												id_area = '$area',
												fecha_modificacion = now()
											WHERE
												id_tipo_producto=$idTipo;");
				return $res;
	}
	
	public function listarProductos ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
													p.id_producto,p.nombre_comun, p.partida_arancelaria,p.id_subtipo_producto ,p.estado, tp.nombre , tp.id_tipo_producto
											FROM 	
													g_catalogos.productos p,
													g_catalogos.subtipo_productos sp,
     												g_catalogos.tipo_productos tp
											WHERE 	
													tp.id_tipo_producto = sp.id_tipo_producto and
													sp.id_subtipo_producto = p.id_subtipo_producto
											order by 1;");
				return $res;
	}
	
	public function guardarNuevoProducto ($conexion,$nombreComun, $nombreCientifico, $codigoProducto,
	    $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida, $programa, $opTrazabilidad, $identificadorCreacion, $opMovilizacion = 'NO', $numPiezas = ''){
			
			$numPiezas = ($numPiezas == '' ? 'null': $numPiezas);
	        
	        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.productos (nombre_comun, nombre_cientifico, codigo_producto,
												partida_arancelaria, estado, id_subtipo_producto, ruta, unidad_medida, programa, trazabilidad, identificador_creacion, movilizacion, numero_piezas)
											VALUES
												('$nombreComun','$nombreCientifico','$codigoProducto',
												'$partidaArancelaria',1,'$idSubtipoProducto', '$archivo', '$unidadMedida', '$programa','$opTrazabilidad', '$identificadorCreacion', '$opMovilizacion', $numPiezas)
											RETURNING id_producto;");
	        return $res;
	}
	
	public function abrirProducto ($conexion,$id_producto){
		$res = $conexion->ejecutarConsulta("SELECT 
													*
											FROM
													g_catalogos.productos
											WHERE
													id_producto=$id_producto;");
				return $res;
	}
	
	public function actualizarImagenProducto ($conexion,$id_producto,$ruta_archivo){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.productos
											SET
												ruta_archivo = '$ruta_archivo'
											WHERE
												id_producto='$id_producto';");
				return $res;
	}
	
	public function actualizarProducto ($conexion,$id_producto, $nombreComun, $nombreCientifico, $codigoProducto, $partidaArancelaria, $archivo, $unidadMedida, $programa, $opTrazabilidad, $opMovilizacion, $identificadorModificacion, $estado=1, $numPiezas=null){
		
		$insert='';
	    if(!is_null($numPiezas)){
	        $insert = ",numero_piezas = $numPiezas";
	    }
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
											g_catalogos.productos
										SET
											nombre_comun ='$nombreComun',
											nombre_cientifico='$nombreCientifico',
											partida_arancelaria='$partidaArancelaria',
											codigo_producto = '$codigoProducto',
											ruta='$archivo',
											fecha_modificacion = now(),
											unidad_medida = '$unidadMedida',
											estado = $estado,
											programa = '$programa',
											trazabilidad = '$opTrazabilidad',
											identificador_modificacion = '$identificadorModificacion',
											movilizacion = '$opMovilizacion'
											".$insert."
										WHERE
											id_producto=$id_producto;");
	    return $res;
	}
	
	public function actualizarIDProductosubtipo ($conexion, $id_producto, $idSubtipoProducto){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.productos
											SET
												id_subtipo_producto = $idSubtipoProducto
											WHERE
												id_producto = $id_producto;");
		return $res;
	}
	
	public function listarRequisitosGenerales ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
												rg.id_requisito, rg.nombre as requisito, a.nombre as area, a.id_area
											FROM
												g_requisitos.requisitos_generales as rg,
												g_estructura.area as a
											WHERE
												rg.id_area = a.id_area;");
				return $res;
	}
	
	public function guardarNuevoRequisitoGeneral ($conexion,$nombre,$area, $idProductoPais){
		$res = $conexion->ejecutarConsulta("INSERT INTO
													g_requisitos.requisitos_generales (nombre, id_area, estado, id_requisito_comercio)
											VALUES
													('$nombre','$area', 1, $idProductoPais)
											RETURNING id_requisito;");
				return $res;
	}
	
	public function abrirRequisito ($conexion,$id_requisito){
		$res = $conexion->ejecutarConsulta("SELECT 
													*
											FROM
												g_requisitos.requisitos
											WHERE
												id_requisito='$id_requisito';");
				return $res;
	}
	
	public function actualizarRequisitoGeneral ($conexion,$id_requisito,$nombre, $area){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_requisitos.requisitos_generales
											SET
												nombre = '$nombre',
												id_area = '$area'
											WHERE
												id_requisito='$id_requisito';");
				return $res;
	}
	
	public function listarRequisitos ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												re.*, a.nombre as area
											FROM
												g_requisitos.requisitos as re,
												g_estructura.area as a
											WHERE
												re.id_area = a.id_area
											ORDER BY 
												re.id_area desc,
												re.codigo,
												re.nombre;");
				return $res;
	}
	
	public function listarRequisitosArea ($conexion, $idArea){
		$res = $conexion->ejecutarConsulta("SELECT
												re.*, a.nombre as area
											FROM
												g_requisitos.requisitos as re,
												g_estructura.area as a
											WHERE
												re.id_area = a.id_area and
												re.estado = 1 and
												re.id_area = '$idArea'
											ORDER BY 
												re.nombre;");
		return $res;
	}
	
	public function guardarNuevoRequisito ($conexion,$nombre, $documento, $area, $tipo, $detalle, $detalleImpresion, $codigo, $identificadorCreacionRequisito){
	    $res = $conexion->ejecutarConsulta("INSERT INTO
											g_requisitos.requisitos(nombre, ruta_archivo, estado, id_area, tipo, detalle, detalle_impreso, codigo, identificador_creacion_requisito)
										VALUES
											('$nombre', '$documento', 1,'$area', '$tipo', '$detalle', '$detalleImpresion', '$codigo', '$identificadorCreacionRequisito') RETURNING id_requisito;");
	    return $res;
	}	
	
	public function actualizarRequisito ($conexion, $id_requisito, $nombre, $documento, $tipo ,$area, $detalle, $detalleImpreso, $codigo, $identificadorModificacionRequisito){
	    $res = $conexion->ejecutarConsulta("UPDATE
											g_requisitos.requisitos
										SET
											nombre = '$nombre',
											tipo = '$tipo',
											ruta_archivo = '$documento',
											id_area = '$area',
											detalle = '$detalle',
											detalle_impreso = '$detalleImpreso',
											fecha_modificacion = now(),
											codigo = '$codigo',
											identificador_modificacion_requisito = '$identificadorModificacionRequisito'
										WHERE
											id_requisito='$id_requisito';");
	    return $res;
	}
	
	public function abrirRequisitosComercio ($conexion,$idProductoPais){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion
											WHERE
												id_requisito_comercio=$idProductoPais;");
		return $res;
	}
	
	public function buscarRequisitosComercioXPaisProducto ($conexion, $idLocalizacion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion
											WHERE
												id_localizacion = $idLocalizacion and
												id_producto = $idProducto;");
		return $res;
	}
	
	public function guardarRequisitoComercio($conexion, $tipo, $producto, $nombreProducto, $pais, $nombrePais, $identificadorCreacionRequisitoComercio){
	    $res = $conexion->ejecutarConsulta("INSERT INTO g_requisitos.requisitos_comercializacion(
												tipo, id_producto, nombre_producto, id_localizacion, nombre_pais, identificador_creacion_requisito_comercializacion)
										VALUES ('$tipo', $producto, '$nombreProducto', $pais, '$nombrePais', '$identificadorCreacionRequisitoComercio')
										RETURNING id_requisito_comercio;");
	    return $res;
	}
	
	/*
		 public function guardarRequisitoComercio($conexion,$declaracion,$numeroResolucion,$fecha,$observacion,$ruta_archivo){
			$res = $conexion->ejecutarConsulta("INSERT INTO g_requisitos.requisitos_comercializacion(
											            declaracion, numero_resolucion, fecha, 
											            observacion, ruta_archivo)
											    VALUES ('$declaracion', '$numeroResolucion', '$fecha',
														'$observacion', '$ruta_archivo')
												RETURNING id_requisito_comercio;");
					return $res;
		}	  
	 */
	public function guardarRequisitoGeneralEspecificoComercio ($conexion,$id_requisito_comercio,$requisito,$tipo){
		
		/*switch ($tipo){
			case 'General': $opcion = 'RG'; break;
			case 'Especifico': $opcion = 'RE'; break;
		}*/
		
			$res = $conexion->ejecutarConsulta("INSERT INTO g_requisitos.requisitos (
            												id_requisito_comercio, requisito,tipo)	
												VALUES ('$id_requisito_comercio','$requisito','$opcion');");
		return $res;
	}
	
	
	
	public function actualizarArchivoRequisitoComercio ($conexion,$id_requisito,$ruta_archivo){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_requisitos.requisitos_comercializacion
											SET
												ruta_archivo = '$ruta_archivo'
											WHERE
												id_requisito_comercio='$id_requisito';");
		return $res;
	}
	
	
	/*public function eliminarRequisitoEspecifico ($conexion,$requisitoEspecifico, $requisitoComercio){
	
		$res = $conexion->ejecutarConsulta("delete
											from
												g_requisitos.requisitos
											where
												requisito = $requisitoEspecifico 
												and id_requisito_comercio = $requisitoComercio
												and tipo = 'RE';");
		return $res;
	}*/
	
	public function actualizarRequisitoComercio($conexion, $idRequisitoComercio, $declaracion, $numeroResolucion, $observacion, $archivo, $fecha, $identificadorModificacionRequisitoComercio){
	    $res = $conexion->ejecutarConsulta("UPDATE
											g_requisitos.requisitos_comercializacion
										SET
											declaracion = '$declaracion',
											numero_resolucion = '$numeroResolucion',
											observacion = '$observacion',
											ruta_archivo = '$archivo',
											fecha = '$fecha',
											fecha_modificacion = now(),
											identificador_modificacion_requisito_comercializacion = '$identificadorModificacionRequisitoComercio'
										WHERE
											id_requisito_comercio = $idRequisitoComercio;");
	    return $res;
	}
	
	public function consultarCategoriaPais($conexion){
		$res = $conexion->ejecutarConsulta("select distinct
												rc.id_localizacion,
												l.nombre,
												rc.tipo
											from
												g_requisitos.requisitos_comercializacion as rc,
												g_catalogos.localizacion as l
											where
												rc.id_localizacion = l.id_localizacion
											order by 2;");
		return $res;
	}
	
	
	public function consultarCategoriaPaisProducto($conexion, $categoria, $pais){
		$res = $conexion->ejecutarConsulta("select 
												rc.id_requisito_comercio,
												p.id_producto,
												p.nombre_comun
											from
												g_requisitos.requisitos_comercializacion as rc,
												g_catalogos.productos as p
											where
												rc.id_producto= p.id_producto
												and rc.id_localizacion = '$pais'
												and rc.tipo = '$categoria';");
		return $res;
	}
	
/*	public function ConsultaProductoRequisitoGeneral($conexion,$idRequisitoComercio){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												rg.nombre
											FROM
												g_requisitos.requisitos_comercializacion as rc,
												g_requisitos.requisitos as r,
												g_requisitos.requisitos_generales rg
											WHERE
												rc.id_requisito_comercio = r.id_requisito_comercio
												and r.requisito = rg.id_requisito
												and r.tipo = 'RG'
												and rc.id_requisito_comercio = $idRequisitoComercio;");
		return $res;
	}
	
	public function ConsultaProductoRequisitoEspecifico($conexion,$idRequisitoComercio){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												r.nombre, 
												r.ruta_archivo
											FROM
												g_requisitos.requisitos_comercializacion as rc,
												g_requisitos.requisitos_asignados ra,
												g_requisitos.requisitos r
											WHERE
												rc.id_requisito_comercio = ra.id_requisito_comercio
												and ra.requisito = r.id_requisito
												and rc.id_requisito_comercio = $idRequisitoComercio;");
															return $res;
	}*/
	
	/*ok*/
	public function consultaProductoPaisPermitido($conexion){
	
		$res = $conexion->ejecutarConsulta("select distinct
												rc.id_localizacion,
												l.nombre,
												rc.id_producto
											from
												g_requisitos.requisitos_comercializacion as rc,
												g_catalogos.localizacion as l
											where
												rc.id_localizacion = l.id_localizacion
											order by 2;");
				return $res;
	}
	
	public function  consultarProductoPais($conexion, $idPais, $idProducto, $actividad){
	
		$res = $conexion->ejecutarConsulta("SELECT
												* 
											 FROM
												g_requisitos.requisitos_comercializacion rc,
												g_requisitos.requisitos_asignados ra
											 WHERE rc.id_requisito_comercio = ra.id_requisito_comercio 
												AND ra.tipo = '$actividad'
												AND rc.id_localizacion = $idPais
												AND rc.id_producto = '$idProducto';");
		
		return $res;
	}
	
	public function  consultarEstadoProductoPaisRequisito($conexion, $idPais, $idProducto, $actividad, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_requisitos.requisitos_asignados ra
											WHERE 
												rc.id_requisito_comercio = ra.id_requisito_comercio
												AND ra.tipo = '$actividad'
												AND rc.id_localizacion = $idPais
												AND rc.id_producto = '$idProducto'
												AND ra.estado = '$estado';");
	
		return $res;
	}
	
	
	public function listarSubtipoProducto ($conexion, $idTipoProducto){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_catalogos.subtipo_productos
											WHERE
												id_tipo_producto = $idTipoProducto
											order by 2;");
		return $res;
	}
	
	public function listarSubtipoProductoPorCodigo ($conexion, $codificacionTipoProducto, $idArea, $codificacionSubtipoProducto){
	   
	    $res = $conexion->ejecutarConsulta("SELECT * FROM g_catalogos.tipo_productos 
                                            INNER JOIN g_catalogos.subtipo_productos
                                            on tipo_productos.id_tipo_producto = subtipo_productos.id_tipo_producto
                                            where  tipo_productos.codificacion_tipo_producto =  '".$codificacionTipoProducto."'
                                            AND tipo_productos.id_area = '".$idArea."'
                                            AND subtipo_productos.codificacion_subtipo_producto =  '".$codificacionSubtipoProducto."';");
	    return $res;
	    
	}
	
	public function abrirSubtipoProducto ($conexion,$idSubtipoProducto){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_catalogos.subtipo_productos
											WHERE
												id_subtipo_producto='$idSubtipoProducto';");
		return $res;
	}
	
	public function actualizarClasificacionSubTipoProducto($conexion, $idSubtipoProducto, $clasificacion){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.subtipo_productos
											SET
												clasificacion = '$clasificacion'
											WHERE
												id_subtipo_producto = $idSubtipoProducto;");
		
		return $res;
	}
	
	public function actualizarClasificacionProducto($conexion, $idProducto, $clasificacion){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.productos
											SET
												clasificacion = '$clasificacion'
											WHERE
												id_producto = $idProducto;");
		
		return $res;
	}
	
	
	public function guardarNuevoSubtipoProducto ($conexion,$nombre,$tipoProducto){
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.subtipo_productos (nombre, estado, id_tipo_producto)
											VALUES
												('$nombre',1,'$tipoProducto')
											RETURNING id_subtipo_producto;");
		return $res;
	}
	
	public function actualizarSubtipoProducto ($conexion,$idSubtipo,$nombre){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.subtipo_productos
											SET
												nombre = '$nombre',
												fecha_modificacion = now()
											WHERE
												id_subtipo_producto=$idSubtipo;");
		return $res;
	}
	
	public function imprimirLineaSubtipoProducto($idSubtipoProducto, $nombreSubtipo, $idTipoProducto, $area, $ruta, $opcion=null){
	    
	    if($opcion === null){
	       $opcionAbrir = 'abrirSubtipoProducto';
	       $opcionEliminar = 'eliminarSubtipoProducto';
	    }else{
	        $opcionAbrir = 'abrirSubtipoProducto'.$opcion;
	        $opcionEliminar = 'eliminarSubtipoProducto'.$opcion;
	    }
	    
		return '<tr id="R' . $idSubtipoProducto . '">' .
					'<td width="100%">' .
					$nombreSubtipo .
					'</td>' .
					'<td>' .
					'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="'.$opcionAbrir.'" data-destino="detalleItem" data-accionEnExito="NADA" >' .
					'<input type="hidden" name="idSubtipoProducto" value="' . $idSubtipoProducto . '" >' .
					'<input type="hidden" id="areaSubProducto" name="areaSubProducto" value="' . $area . '" >' .
					'<button class="icono" type="submit" ></button>' .
					'</form>' .
					'</td>' .
					'<!--td>' .
					'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="'.$opcionEliminar.'">' .
					'<input type="hidden" name="idSubtipoProducto" value="' . $idSubtipoProducto . '" >' .
					'<button type="submit" class="icono"></button>' .
					'</form>' .
					'</td-->' .
				'</tr>';
	}
	
	public function listaProductos ($conexion, $idSubtipoProducto, $estadoProducto = 1){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_catalogos.productos
											WHERE
												id_subtipo_producto = $idSubtipoProducto
												and estado IN ($estadoProducto)
											order by 2;");
		return $res;
	}
	
	public function imprimirLineaProducto($idProducto, $nombreProducto, $idSubtipoProducto, $area, $ruta, $opcion=null){
		
		 if($opcion === null){
	        $opcionAbrir = 'abrirProducto';
	        $opcionEliminar = 'eliminarProducto';
	    }else{
	        $opcionAbrir = 'abrirProducto'.$opcion;
	        $opcionEliminar = 'eliminarProducto'.$opcion;
	    }
		
		return '<tr id="R' . $idProducto . '">' .
				'<td width="100%">' .
				$nombreProducto .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="'.$opcionAbrir.'" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
				'<input type="hidden" id="areaProducto" name="areaProducto" value="' . $area . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listaPaises ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion
											WHERE
												id_producto = $idProducto
											order by 10;");
		return $res;
	}
	
	public function imprimirLineaProductoPais($idComercioPais, $nombrePais, $idPais, $nombreProducto){
		return '<tr id="R' . $idComercioPais . '">' .
				'<td width="100%">' .
				$nombrePais . ' - ' . $nombreProducto.
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="administracionRequisitos" data-opcion="abrirPaisRequisito" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idRequisitoComercio" value="' . $idComercioPais . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="administracionRequisitos" data-opcion="eliminarProductoPais">' .
				'<input type="hidden" name="idRequisitoComercio" value="' . $idComercioPais . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarProductoPaises ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion r
											WHERE
												r.id_producto = $idProducto
											ORDER BY nombre_pais ASC;");
		return $res;
	}
	
	public function listarRequisitoGeneral ($conexion,$id_requisito){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_generales
											WHERE
												id_requisito_comercio=$id_requisito;");
		return $res;
	}
	
	public function listarRequisitosAsignados ($conexion,$idRequisitoComercio, $tipo){
		$res = $conexion->ejecutarConsulta("SELECT
												ra.id_requisito_comercio,
												ra.requisito,
												r.nombre,
												r.tipo,
												ra.estado
											FROM
												g_requisitos.requisitos_asignados ra,
												g_requisitos.requisitos r
											WHERE
												ra.id_requisito_comercio=$idRequisitoComercio and
												ra.requisito = r.id_requisito and 
												r.tipo in ($tipo)
											ORDER BY
												ra.orden;");
		return $res;
	}
	
	public function imprimirLineaRequisito($idRequisitoComercio, $idRequisito, $nombreRequisito, $tipoRequisito, $estado){
		return '<tr id="R' . $idRequisito . '">' .
				'<td width="75%">' .
				$nombreRequisito .
				'</td>' .
				'<td width="25%">' .
				$tipoRequisito .
				'</td>' .
				'<td>' .
				'<form class="'.$estado.'" data-rutaAplicacion="administracionRequisitos" data-opcion="actualizarEstadoRequisito">' .
				'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
				'<input type="hidden" name="idRequisitoComercio" value="' . $idRequisitoComercio . '" >' .
				'<input type="hidden" name="idRequisito" value="' . $idRequisito . '" >' .
				'<input type="hidden" name="tipo" value="' . $tipoRequisito . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="administracionRequisitos" data-opcion="quitarRequisito">' .
				'<input type="hidden" name="idRequisitoComercio" value="' . $idRequisitoComercio . '" >' .
				'<input type="hidden" name="idRequisito" value="' . $idRequisito . '" >' .
				'<input type="hidden" name="tipo" value="' . $tipoRequisito . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarRequisitosComercializacion ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion;");
		return $res;
	}

	public function guardarNuevoRequisitoAsignado($conexion, $idRequisitoComercio, $requisito, $idTipo, $estado, $identificadorCreacionRequisitoAsignado){
	    $res = $conexion->ejecutarConsulta("INSERT INTO
										g_requisitos.requisitos_asignados(id_requisito_comercio, requisito, tipo, estado, identificador_requisito)
										VALUES ($idRequisitoComercio, $requisito, '$idTipo', '$estado', '$identificadorCreacionRequisitoAsignado');");
	    return $res;
	}
	
	public function quitarRequisitoAsignado($conexion, $idRequisitoComercio, $idRequisito){
		$res = $conexion->ejecutarConsulta("delete from
												g_requisitos.requisitos_asignados
											where
												id_requisito_comercio = $idRequisitoComercio and
												requisito = $idRequisito;");
		return $res;
	
	}
	
	public function listarRequisitosComercializacionProducto ($conexion, $identificador, $tipoBusqueda){
		
		$busqueda = '';
		
		switch ($tipoBusqueda){
			case 'tipoProducto':
				$busqueda = " tp.id_tipo_producto  = $identificador";
			break;
			
			case 'subTipoProducto':
				$busqueda = " sp.id_subtipo_producto  = $identificador";
			break;
			
			case 'producto':
				$busqueda = " p.id_producto  = $identificador";
			break;
		}
				
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (rq.id_producto), 
												rq.nombre_producto, 
												rq.tipo, 
												a.nombre as nombre_area, 
												a.id_area
											FROM
												g_requisitos.requisitos_comercializacion rq,
												g_estructura.area as a,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rq.tipo = a.id_area and
												rq.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												".$busqueda.";");
		return $res;
	}
	
	public function listarPaisesProducto ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
	
	public function quitarPaisAsignado($conexion, $idRequisitoComercio){
		$res = $conexion->ejecutarConsulta("delete from
												g_requisitos.requisitos_comercializacion
											where
												id_requisito_comercio = $idRequisitoComercio;");
		return $res;
	
	}
	
	public function buscarProductoPais ($conexion,$idProducto, $idPais){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_comercializacion
											WHERE
												id_producto = $idProducto and
												id_localizacion = $idPais;");
		return $res;
	}
	
	
	public function buscarPaisRequisito ($conexion, $idRequisitoComercio, $requisito, $tipoRequisito){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_asignados
											WHERE
												id_requisito_comercio = $idRequisitoComercio and
												requisito = $requisito and
												tipo = '$tipoRequisito';");
		return $res;
	}
	
	public function listarCodigoComplementarioSuplementario ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.codigos_adicionales_partidas
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
		
	public function imprimirCodigoComplementarioSuplementario($idProducto, $codigoComplementario, $codigoSuplementario){
													return '<tr id="R' . $idProducto . '-'.$codigoComplementario.'-'.$codigoSuplementario.'">' .
															'<td width="100%">' .
															'<b>Complementario:</b> '.$codigoComplementario .' <b>Suplementario: </b>'. $codigoSuplementario.
															'</td>' .
															'<td>' .
															'<form class="borrar" data-rutaAplicacion="administracionProductos" data-opcion="eliminarCodigoCS">' .
															'<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
															'<input type="hidden" name="idCodigoComplementario" value="' . $codigoComplementario . '" >' .
															'<input type="hidden" name="idCodigoSuplementario" value="' . $codigoSuplementario . '" >' .
															'<button type="submit" class="icono"></button>' .
															'</form>' .
															'</td>' .
															'</tr>';
	}
	
	public function guardarNuevoCodigoComplementarioSuplementario ($conexion, $idProducto, $codigoComplementario, $codigoSuplemetario){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.codigos_adicionales_partidas
											VALUES
												($idProducto, '$codigoComplementario', '$codigoSuplemetario');");
		return $res;
	}
	
	public function quitarCodigoComplementarioSuplementario($conexion, $idProducto,$idCodigoComplementario, $idCodigoSuplementario){
		$res = $conexion->ejecutarConsulta("delete from
												g_catalogos.codigos_adicionales_partidas
											where
												id_producto = $idProducto
												and codigo_complementario = '$idCodigoComplementario'
												and codigo_suplementario = '$idCodigoSuplementario';");
		return $res;
	
	}
	
	public function obtenerSubProductoXarea($conexion, $idSutipoPoducto){
		$res = $conexion->ejecutarConsulta("SELECT
												id_area
											FROM
												g_catalogos.tipo_productos tp,
												g_catalogos.subtipo_productos sp	
											WHERE
												sp.id_tipo_producto = tp.id_tipo_producto
												and sp.id_subtipo_producto  = '$idSutipoPoducto';");
		return $res;
	
	}
	
	/*public function guardarProductoInocuidad ($conexion,$idProducto, $composicion, $formulacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO
													g_catalogos.productos_inocuidad 
											VALUES
													($idProducto,'$composicion','$formulacion')
													RETURNING id_producto;");
				return $res;
	}*/
	
	
	
	
	/*public function actualizarProductoInocuidad ($conexion,$idProducto, $composicion, $formulacion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.productos_inocuidad
											SET 
												composicion = '$composicion',
												formulacion = '$formulacion',
												fecha_modificacion = now()
											WHERE
												id_producto = $idProducto;");
				return $res;
	}*/
	
	public function buscarProductoInocuidad ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos_inocuidad
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
	
	
	public function listarCodigoInocuidad ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.codigos_inocuidad
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
	
	public function imprimirCodigoInocuidad($idProducto, $subcodigo, $presentacion, $unidad, $nombreUnidadMedida, $areaProducto){
		return '<tr id="R' . $idProducto . '-'.$subcodigo.'">' .
				'<td width="100%">' .
				'<b>Subcodigo:</b> '.$subcodigo .' <b>Presentación: </b>'. $presentacion.' '. 
				(($unidad == 'Otro')?$nombreUnidadMedida:$unidad).
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="administracionProductos" data-opcion="eliminarCodigoInocuidad">' .
					'<input type="hidden" name="idProducto" value="' . $idProducto . '" >'.
					'<input type="hidden" name="subcodigo" value="' . $subcodigo . '" >'.
					'<button type="submit" class="icono"></button>'.
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	
	public function guardarNuevoCodigoInocuidad ($conexion, $idProducto,$subcodigo, $presentacion, $unidad, $nombreUnidadMedida){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_catalogos.codigos_inocuidad(id_producto, subcodigo, presentacion, unidad_medida, nombre_unidad_medida)
											VALUES
												($idProducto, '$subcodigo', '$presentacion', '$unidad', '$nombreUnidadMedida');");
		return $res;
	}
	
	public function guardarProductoInocuidadTMP ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.productos_inocuidad
											(SELECT	($idProducto) WHERE NOT EXISTS (SELECT id_producto FROM g_catalogos.productos_inocuidad WHERE id_producto = $idProducto)) ;");
				return $res;
	}
	
	/*public function quitarProducto($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_catalogos.productos 
											SET 
												estado = 9
											WHERE
												id_producto= $idProducto;");
		return $res;
	}*/
	
	public function quitarProducto($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.productos
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
	

	
	public function quitarCodigoInocuidad($conexion, $idProducto,$subCodigo){
		$res = $conexion->ejecutarConsulta("delete from
												g_catalogos.codigos_inocuidad
											where
												id_producto = $idProducto
												and subcodigo = '$subCodigo';");
		return $res;
	
	}
	
	public function buscarCodigoComplementarioSuplementario ($conexion, $idProducto, $codigoComplementario, $codigoSuplemetario){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												g_catalogos.codigos_adicionales_partidas
											WHERE
												id_producto = $idProducto
												and codigo_complementario = '$codigoComplementario'
												and codigo_suplementario = '$codigoSuplemetario';");
				return $res;
	}
	
	public function listarRequisitosProducto($conexion, $idPais, $idProducto, $idTipo){
		
		$cid = $conexion->ejecutarConsulta("SELECT
												r.*
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_requisitos.requisitos_asignados ra,
												g_requisitos.requisitos r
											WHERE
												rc.id_requisito_comercio = ra.id_requisito_comercio and
												ra.requisito = r.id_requisito and
												rc.id_localizacion = $idPais and
												rc.id_producto = $idProducto and
												r.tipo = '$idTipo'
											ORDER BY 
												ra.orden asc;");
	
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array('idRequisito'=>$fila['id_requisito'],'nombre'=>$fila['nombre'],'rutaArchivo'=>$fila['ruta_archivo'], 'idArea'=>$fila['id_area'], 'tipo'=>$fila['tipo'], 'detalleImpreso'=>$fila['detalle_impreso']);
		}
	
		return $res;
	}
	
	public function listarRequisitoProductoPaisUnidoImpreso($conexion, $idPais, $idProducto, $idTipo){
		
		$res = $conexion->ejecutarConsulta("SELECT
													distinct 
														array_to_string(ARRAY(
													            SELECT
													               'R) ' || r1.detalle_impreso
													            FROM
															g_requisitos.requisitos_asignados ra1, 
															g_requisitos.requisitos r1
													            WHERE
													                ra1.requisito = r1.id_requisito and
																	r1.tipo =  '$idTipo' and
																	ra1.estado = 'activo' and
																	r1.detalle_impreso != '' and
																	ra1.id_requisito_comercio = rc.id_requisito_comercio),' ') as detalle_impreso
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_requisitos.requisitos_asignados ra
											WHERE
												rc.id_requisito_comercio = ra.id_requisito_comercio and
												rc.id_localizacion = $idPais and
												rc.id_producto = $idProducto and
												ra.tipo = '$idTipo';");

		return $res;
	}
	
	public function categoriaProductosPorArea($conexion){
		$res = $conexion->ejecutarConsulta("select *
											from g_catalogos.tipo_productos
											where estado = 1
											order by id_area, nombre;");
		return $res;
	}
	
	public function productosPorCatgoria($conexion, $tipoProducto){
		$res = $conexion->ejecutarConsulta("
							select *
							from g_catalogos.subtipo_productos
							where estado = 1 and id_tipo_producto = $tipoProducto
							order by nombre;");
		return $res;
	}
	
	
	public function productos($conexion, $subTipoProducto){
		$res = $conexion->ejecutarConsulta("
								select id_producto, nombre_comun, partida_arancelaria, codigo_producto
								from g_catalogos.productos
								 where estado = 1 and id_subtipo_producto = $subTipoProducto and (partida_arancelaria is not null or partida_arancelaria <> '0')
								order by nombre_comun;");
		return $res;
	}
	
public function mostrarRequisitos($conexion, $producto){
		
        $res = $conexion->ejecutarConsulta("
                                    select row_to_json(requisitos)
                                    from (
                                        select
                                            rc.id_producto,
                                            rc.id_requisito_comercio,
                                            rc.declaracion,
                                            rc.numero_resolucion,
                                            rc.fecha as fecha_resolucion,
                                            rc.ruta_archivo as archivo_resolucion,
                                            rc.nombre_pais
                                            ,(select array_to_json(array_agg(row_to_json(r_p)))
                                            from (

                                                        select
                                                            ra.orden,
                                                            ra.tipo,
                                                            r.detalle,
                                                            r.detalle_impreso
                                                        from
                                                            g_requisitos.requisitos_asignados ra,
                                                            g_requisitos.requisitos r
                                                        where
                                                            rc.id_requisito_comercio = ra.id_requisito_comercio and
                                                            r.id_requisito = ra.requisito and
                                                            r.estado = 1
                                                        order by
                                                            ra.orden
                                            ) r_p) as requisito_pais

                                        from
                                            g_requisitos.requisitos_comercializacion rc
                                        where
                                            rc.id_producto = $producto
                                        order by rc.nombre_pais
                                    ) as requisitos;");
        
        return $res;
	}
	
	public function actualizarEstadoRequisito ($conexion, $idRequisitoComercio, $idRequisito, $tipoRequisito, $estado, $identificadorModificacionRequisitoAsignado){
	    $res = $conexion->ejecutarConsulta("UPDATE
											g_requisitos.requisitos_asignados
										SET
											estado = '$estado',
											fecha_modificacion = now(),
											identificador_modificacion_requisito_asignado = '$identificadorModificacionRequisitoAsignado'
										WHERE
											id_requisito_comercio= $idRequisitoComercio
											and requisito = $idRequisito
											and tipo = '$tipoRequisito';");
	    return $res;
	}
	
	public function buscarRequisitoAsignado ($conexion, $idRequisitoComercio, $idRequisito, $tipoRequisito){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos_asignados
											WHERE
												id_requisito_comercio= $idRequisitoComercio
												and requisito = $idRequisito
												and tipo = '$tipoRequisito';");
		return $res;
	}
	
	public function listarCategoriaToxicologica($conexion,$areaSubProducto){
		$res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									            g_catalogos.categoria_toxicologica
											WHERE 
												id_area = '$areaSubProducto' and
                                                estado_categoria_toxicologica = 'Activo'
											ORDER BY 
												categoria_toxicologica;");
		return $res;
	}
	
	public function guardarProductoInocuidad ($conexion,$idProducto,$idFormulacion ,$formulacion, $numeroRegistro, $dosis, $unidadMedidaDosis, $periodoCarencia,$periodoReingreso, $observaciones, $idCategoriaToxicologica,$CategoriaToxicologica,$fechaRegistro,$empresa, $idDeclaracionVenta, $declaracionVenta, $razonSocial=null, $estabilidad=null){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.productos_inocuidad (id_producto, id_formulacion, formulacion,fecha_creacion,numero_registro, dosis, periodo_carencia_retiro,periodo_reingreso, observacion, unidad_dosis,id_categoria_toxicologica,categoria_toxicologica,fecha_registro,id_operador,fecha_vencimiento, id_declaracion_venta, declaracion_venta, razon_social, estabilidad)
											VALUES($idProducto,$idFormulacion,'$formulacion',now(),'$numeroRegistro','$dosis','$periodoCarencia','$periodoReingreso','$observaciones','$unidadMedidaDosis',$idCategoriaToxicologica,'$CategoriaToxicologica','$fechaRegistro','$empresa',date('$fechaRegistro') + interval '10 years', $idDeclaracionVenta, '$declaracionVenta', '$razonSocial', '$estabilidad')
											RETURNING id_producto;");
				return $res;
	}
	
	public function listarFabricanteFormulador ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.fabricante_formulador
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
	
	
	public function listarComposicionProductosInocuidad($conexion,$idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												ia.*,
												id_producto,
												ci.concentracion,
												ci.unidad_medida,
												ci.id_composicion,
												ci.ingrediente_activo,
												ci.tipo_componente
											FROM
												g_catalogos.composicion_inocuidad ci,
												g_catalogos.ingrediente_activo_inocuidad ia
											WHERE
												ci.id_ingrediente_activo = ia.id_ingrediente_activo
											AND 	
												id_producto = $idProducto;");
		return $res;
	}
	
	public function listarUsos($conexion,$idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												pu.*,u.nombre_uso,e.nombre, pu.nombre_especie
											FROM
												g_catalogos.producto_inocuidad_uso pu,
												g_catalogos.usos u,
												g_catalogos.especies e
												WHERE 
												     pu.id_uso = u.id_uso
												AND e.id_especies = pu.id_especie
												AND  pu.id_producto = $idProducto												
											UNION 
											SELECT
												pu.*,u.nombre_uso,p.nombre_comun, pu.nombre_especie
												
											FROM
												g_catalogos.producto_inocuidad_uso pu, 
												g_catalogos.usos u,
												g_catalogos.productos p
											WHERE 
												     pu.id_uso = u.id_uso
												AND  pu.id_aplicacion_producto = p.id_producto
												AND  pu.id_producto = $idProducto
                                            UNION 
                                            SELECT
                                            	pu.*,u.nombre_uso,pu.instalacion, pu.nombre_especie
                                            FROM
                                            	g_catalogos.producto_inocuidad_uso pu,
                                            	g_catalogos.usos u
                                            	WHERE 
                                            	     pu.id_uso = u.id_uso
                                            	AND pu.aplicado_a = 'Instalacion'
												AND  pu.id_producto = $idProducto;");
		return $res;
	}
	
	public function listarIngredienteActivo($conexion,$areaSubProducto){
		$res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									            g_catalogos.ingrediente_activo_inocuidad
											WHERE 
												id_area = '$areaSubProducto'and
                                                estado_ingrediente_activo = 'Activo'
											ORDER BY 
												ingrediente_activo ASC;");
		return $res;
	}
	
	public function imprimirLineaComposicion( $idProducto, $idComposicion, $tipoComposicion, $nombreComposicion, $concentracion, $unidadMedida, $idArea){
		
	    if($idArea == 'IAV' || $idArea == 'IAP'){
	        $composicion = '<b> '.$tipoComposicion.': </b>';
	    }else{
	        $composicion = '';
	    }
	    
		return '<tr id="R'. $idProducto . '-' . $idComposicion.'">' .
			'<td width="100%">' .
			$composicion. $nombreComposicion.' '.$concentracion.' '.$unidadMedida.
			'</td>' .
			'<td>' .
			'<form class="borrar" data-rutaAplicacion="registroProducto" data-opcion="eliminarComposicion">' .
			'<input type="hidden" name="idComposicion" value="' . $idComposicion . '" >' .
			'<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
			'<button type="submit" class="icono"></button>' .
			'</form>' .
			'</td>' .
			'</tr>';
	}
	
	public function imprimirfabricanteFormulador($idProductoInocuidad,$idFabricanteFormulador,$formulador,$idPaisOrigen,$paisOrigen, $tipoFabricante=null, $areaProducto=null){
		return '<tr id="R' . $idProductoInocuidad . '-'.$idFabricanteFormulador.'-'.$idPaisOrigen.'">' .
				'<td width="100%">' .
				'<b> Fabricante/formulador: </b>'. $formulador .' <b>País: </b>'. $paisOrigen. 
				(($areaProducto == 'IAV')? " <b> Tipo: </b>". $tipoFabricante :'').
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="registroProducto" data-opcion="eliminarProductoFabricante">' .
				'<input type="hidden" name="idProductoInocuidad" value="' . $idProductoInocuidad . '" >' .
				'<input type="hidden" name="idFabricanteFormulador" value="' . $idFabricanteFormulador . '" >' .
				'<input type="hidden" name="idpaisOrigen" value="' . $idPaisOrigen . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}

	public function imprimirProductoPaisOrigen($idProductoIncouidad,$idPaisOrigen ,$nombrePais){
		return '<tr id="R' . $idProductoIncouidad . '-'.$idPaisOrigen.'">' .
				'<td width="100%">' .
				'<b> País Fabricante/formulador: </b>'. $nombrePais.
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="registroProducto" data-opcion="eliminarProductoPaisOrigen">' .
				'<input type="hidden" name="idProductoInocuidad" value="' . $idProductoIncouidad . '" >' .
				'<input type="hidden" name="idPaisOrigen" value="' . $idPaisOrigen . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function listarUsosProductos($conexion,$areaProducto){
		$res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									            g_catalogos.usos
											WHERE 
												id_area = '$areaProducto' and
                                                estado_uso = 'Activo'
											ORDER BY 
												nombre_uso;");
		return $res;
	}
	
	public function listarAplicacionProductos($conexion,$areaProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												p.id_producto, p.nombre_comun
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE 
												p.id_subtipo_producto = sp.id_subtipo_producto
												and sp.id_tipo_producto = tp.id_tipo_producto 
												and tp.id_area = '$areaProducto'
										ORDER BY 
												p.nombre_comun;");
		return $res;
	}
	
	public function imprimirUso ($idProducto, $idUso,$nombreUso,$idAplicacion,$nombreAplicacion, $idProductoUso, $nombreEspecie=null, $areaProducto=null){   
	    return '<tr id="R'.$idProducto.'-'.$idUso.'-'.$idAplicacion.'-'.$idProductoUso.'">' .
		    	'<td width="100%">' .
				'<b>Uso:</b> '.$nombreUso .' <b>Aplicado a: </b>'. $nombreAplicacion.
				(($areaProducto == 'IAV')? " - ". $nombreEspecie :'').
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="registroProducto" data-opcion="eliminarUsoProducto">' .
				'<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
				'<input type="hidden" name="idUso" value="' . $idUso . '" >' .
				'<input type="hidden" name="idAplicacion" value="' . $idAplicacion . '" >' .
				'<input type="hidden" name="idProductoUso" value="' . $idProductoUso . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function quitarComposicionProducto($conexion, $idComposicion){
		
		$res = $conexion->ejecutarConsulta("DELETE from
													g_catalogos.composicion_inocuidad
												WHERE
													id_composicion = $idComposicion;");
		return $res;
		
	}
	
	public function quitarProductoFabricante($conexion, $idProductoInocuidad,$idFabricanteFormulador, $idPaisOrigen){
		
		$res = $conexion->ejecutarConsulta("DELETE from
													g_catalogos.fabricante_formulador
											WHERE
													id_producto = $idProductoInocuidad
													and id_fabricante_formulador = $idFabricanteFormulador
													and id_pais_origen = $idPaisOrigen;");
		return $res;
	
	}
	
	public function quitarUsoProducto ($conexion, $idProductoUso){
	    
		$res = $conexion->ejecutarConsulta("DELETE from
												g_catalogos.producto_inocuidad_uso
											WHERE
												id_producto_uso = $idProductoUso;");
		return $res;
	
	}
	
	public function actualizarProductoInocuidad ($conexion, $idProducto, $idformulacion, $formulacion, $numeroRegistro, $dosis, $periodoCarencia, $periodoReingreso, $observacion, $unidadMedidaDosis, $idCategoriaToxicologica, $categoriaToxicologica, $fechaRegistro, $idDeclaracionVenta, $declaracionVenta, $empresa=null, $estabilidad=null, $razonSocial=null){
				
	    if($empresa != null){
	       $query = "id_operador= '$empresa',";
	    }
		if($razonSocial!= null){
	        $query .= "razon_social= '$razonSocial',";
	    }
	    
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.productos_inocuidad
											SET
												id_formulacion = '$idformulacion',
												formulacion = '$formulacion',
												fecha_modificacion = now(),
												numero_registro = '$numeroRegistro',
												dosis = '$dosis',
												periodo_carencia_retiro = '$periodoCarencia',
												periodo_reingreso = '$periodoReingreso',
												observacion = '$observacion',
												unidad_dosis = '$unidadMedidaDosis',
												id_categoria_toxicologica= '$idCategoriaToxicologica',
												categoria_toxicologica = '$categoriaToxicologica',
												fecha_registro = '$fechaRegistro',
												".$query."
												id_declaracion_venta = $idDeclaracionVenta,
												declaracion_venta = '$declaracionVenta',
                                                estabilidad = '$estabilidad'
											WHERE
												id_producto = $idProducto;");
				return $res;
	}
	
	public function actualizarFechaReevalacionPI ($conexion,$idProducto,$fechaRevaluacion){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.productos_inocuidad
											SET
												fecha_revaluacion = '$fechaRevaluacion'
											WHERE
												id_producto = $idProducto;");
		return $res;	
	}
	
	public function listarFormulacion($conexion,$areaSubProducto){
		$res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									            g_catalogos.formulacion
											WHERE
												id_area = '$areaSubProducto' and
                                                estado_formulacion = 'Activo'
											ORDER BY 
												formulacion;");
		return $res;
	}
	
	public function buscarNombreRegistroProducto($conexion,$categoria){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM 
													g_catalogos.productos_inocuidad
											WHERE 
													numero_registro  = '$categoria';");
		return $res;
	}
	
	public function buscarComposicion ($conexion, $idProducto, $idIngredienteActivo, $idTipoComponente){
		
		if($idTipoComponente == null){
	        $componente= " id_tipo_componente is null";
	    }else{
	        $componente= " id_tipo_componente = $idTipoComponente";
	    }
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.composicion_inocuidad
											WHERE
												id_producto = $idProducto
												AND id_ingrediente_activo = $idIngredienteActivo and
												$componente;");
												
		return $res;
	}
	
	public function guardarComposicion ($conexion, $idProductoInocuidad, $idIngredienteActivo, $nombreIngredienteActivo, $concentracion, $unidadMedida, $idTipoComponente, $tipoComponente, $nombreUMedConcentracion){
        
		if($idTipoComponente == null){
	        $idTipoComponente= 0;
	    }
		
	    $res = $conexion->ejecutarConsulta("INSERT INTO
                                                g_catalogos.composicion_inocuidad(id_producto, id_ingrediente_activo, concentracion, ingrediente_activo, unidad_medida, id_tipo_componente, tipo_componente, nombre_unidad_medida)
											VALUES
												($idProductoInocuidad, $idIngredienteActivo,'$concentracion', '$nombreIngredienteActivo','$unidadMedida', $idTipoComponente, '$tipoComponente', '$nombreUMedConcentracion')
                                            RETURNING id_composicion;");
		
		return $res;
	}
	
	
	public function actualizarComposicionProducto($conexion, $idProducto,$ingredienteActivo){
				
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.productos_inocuidad
											SET
												--composicion = '$nombreComposicion',
												ingrediente_activo = '$ingredienteActivo'
											WHERE
												id_producto= $idProducto;");
		return $res;
	}
	
	public function buscarCodigoInocuidad ($conexion, $idProductoIncouidad, $presentacion,$unidad){
		
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.codigos_inocuidad
											WHERE
												id_producto = $idProductoIncouidad
												AND  unidad_medida = '$unidad'
												AND     presentacion = '$presentacion';");
		return $res;
	}
	
	
	public function guardarNuevoFabricanteFormulador ($conexion, $idProductoIncouidad, $formulador,$idPaisOrigen, $nombrePaisFabricante, $tipoFabricante){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.fabricante_formulador(id_producto, nombre,id_pais_origen,pais_origen, tipo)
											VALUES ($idProductoIncouidad, '$formulador',$idPaisOrigen,'$nombrePaisFabricante', '$tipoFabricante') RETURNING id_fabricante_formulador;");
	
				return $res;
	}
	
	
	public function buscarPaisformuladorFabricante ($conexion, $formualdor, $idPaisOrigen, $idProducto){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.fabricante_formulador
											WHERE
												UPPER(nombre) = UPPER('$formualdor')
												and id_pais_origen = $idPaisOrigen
												and id_producto = $idProducto ;");
		return $res;
	}
	
	public function buscarUsoProducto ($conexion, $idProducto,$idUso, $idAplicacion){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.producto_inocuidad_uso
											WHERE
												id_producto = $idProducto
												AND id_uso = $idUso
												AND id_aplicacion_producto = $idAplicacion;");
		return $res;
	}
	
	public function guardarNuevoUso ($conexion, $idProducto, $idUso,$idAplicacion){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.producto_inocuidad_uso(id_producto, id_uso,id_aplicacion_producto)
											VALUES 	($idProducto, $idUso,$idAplicacion ) RETURNING id_producto_uso;");
		return $res;
	}
	
	public function abrirRequisitoXCodigo ($conexion,$codigo){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos
											WHERE
												codigo='$codigo';");
		return $res;
	}
	
	public function listarNuevaComposicionProductosInocuidad($conexion,$idProducto){
		
		$res = $conexion->ejecutarConsulta("SELECT
												ia.*
											FROM
												g_catalogos.composicion_inocuidad ci,
												g_catalogos.ingrediente_activo_inocuidad ia
											WHERE
												ci.id_ingrediente_activo = ia.id_ingrediente_activo
												AND  id_producto = $idProducto;");
		return $res;
	}
	
	public function listarTipoIngredienteActivo($conexion, $incremento, $datoIncremento){
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM
												g_catalogos.ingrediente_activo_inocuidad
											ORDER BY 
												ingrediente_activo ASC
											offset 
												$datoIncremento rows
											fetch next 
												$incremento rows only;");
		return $res;
	}
	
	public function abrirIngredienteActivo($conexion,$idIngredienteActivo){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.ingrediente_activo_inocuidad
											WHERE
												id_ingrediente_activo =  $idIngredienteActivo;");
				return $res;
	}
	
	public function actualizarIngredienteActivoQuimico($conexion, $idIngredienteActivo,$ingredienteActivo ,$ingredienteQuimico, $cas, $formulaQuimica, $grupoQuimico){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.ingrediente_activo_inocuidad
											SET
												ingrediente_activo	= '$ingredienteActivo',
												ingrediente_quimico = '$ingredienteQuimico',
												cas = '$cas',
												formula_quimica = '$formulaQuimica',
												grupo_quimico = '$grupoQuimico',
												fecha_modificacion_ingrediente_activo = 'now()'
											WHERE
												id_ingrediente_activo= $idIngredienteActivo;");
		return $res;
	}
	
	public function guardarNuevoIngredienteActivo ($conexion, $ingredienteActivo, $idArea, $ingredienteQuimico, $cas, $formulaQuimica, $grupoQuimico){
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_catalogos.ingrediente_activo_inocuidad(ingrediente_activo, id_area,ingrediente_quimico, cas, formula_quimica, grupo_quimico)
											VALUES 	('$ingredienteActivo', '$idArea', '$ingredienteQuimico', '$cas', '$formulaQuimica', '$grupoQuimico' );");
				return $res;
	}
	
	
	public function abrirUsoInocuidad($conexion,$idUso){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.usos
											WHERE 
												id_uso = $idUso;");
		return $res;
	}
	
	public function actualizarUsoInocuidad($conexion, $idUso, $nombreCientifico, $nombreComun, $idArea){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.usos
											SET
												nombre_uso	= '$nombreCientifico',
												nombre_comun_uso = '$nombreComun',
												id_area = '$idArea',
												fecha_modificacion_uso = 'now()'
											WHERE
												id_uso = $idUso;");
		return $res;
	}
	
	public function listarUsoArea($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.usos
											ORDER BY
												nombre_uso ASC;");
		return $res;
	}
	
	public function guardarNuevoUsoInocuidad ($conexion,$idArea, $nombreCientifico, $nombreComun){
		$res = $conexion->ejecutarConsulta("INSERT INTO
											g_catalogos.usos(id_area, nombre_uso, nombre_comun_uso)
											VALUES 	('$idArea', '$nombreCientifico', '$nombreComun');");
		return $res;
	}
	
	 public function mostrarDatosGeneralesDeProducto($conexion, $producto){
		$res = $conexion->ejecutarConsulta("
											SELECT
												tp.nombre tipo
												, stp.nombre subtipo
												, p.id_producto
												, p.nombre_comun producto
												, p.nombre_cientifico cientifico
												, p.partida_arancelaria
												, p.codigo_producto
												, p.unidad_medida
												, tp.id_area
											FROM
												g_catalogos.productos p
												, g_catalogos.subtipo_productos stp
												, g_catalogos.tipo_productos tp
											WHERE
												p.id_producto = $producto
												and p.estado = 1
												and stp.id_subtipo_producto = p.id_subtipo_producto
												and tp.id_tipo_producto = stp.id_tipo_producto
											ORDER BY
												p.nombre_comun;");
				return $res;
	}
	
	public function listarProductoUsoEspecie($conexion,$idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												pu.*
											FROM
												g_catalogos.producto_inocuidad_uso pu
											WHERE  
												pu.id_producto = $idProducto;");
		return $res;
	}
	
	public function quitarUsoProductoEspecie ($conexion, $idProducto, $idUso, $idEspecie){
		$res = $conexion->ejecutarConsulta("DELETE from
												g_catalogos.producto_inocuidad_uso
											WHERE
												id_producto = $idProducto
												and id_uso = $idUso
												and id_especie = $idEspecie;");
		return $res;
	
	}
	
	public function buscarUsoProductoEspecie ($conexion, $idProducto,$idUso, $idEspecie, $nombreEspecie, $aplicado){
		$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_catalogos.producto_inocuidad_uso
												WHERE
													id_producto = $idProducto
													and id_uso = $idUso
													and id_especie = $idEspecie
                                                    and upper(nombre_especie) ilike upper('$nombreEspecie')
                                                    and aplicado_a = '$aplicado';");
		return $res;
	}
	
	public function guardarNuevoUsoEspecie ($conexion, $idProducto, $idUso, $idEspecie, $nombreEspecie, $aplicado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.producto_inocuidad_uso(id_producto, id_uso, id_especie, nombre_especie, aplicado_a)
											VALUES 	($idProducto, $idUso,$idEspecie,'$nombreEspecie', '$aplicado') RETURNING id_producto_uso;");
		return $res;
	}
	
	public function buscarUsoProductoInstalacion ($conexion, $idProducto,$idUso, $instalacion, $aplicado){

	    $res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_catalogos.producto_inocuidad_uso
												WHERE
													id_producto = $idProducto
													and id_uso = $idUso
													and quitar_caracteres_especiales(instalacion) = quitar_caracteres_especiales('$instalacion')
                                                    and aplicado_a = '$aplicado';");
	    return $res;
	}
	
	public function guardarNuevoUsoInstalacion ($conexion, $idProducto, $idUso, $instalacion, $aplicado){
	    $res = $conexion->ejecutarConsulta("INSERT INTO g_catalogos.producto_inocuidad_uso(id_producto, id_uso, instalacion, aplicado_a)
											VALUES 	($idProducto, $idUso,'$instalacion', '$aplicado') RETURNING id_producto_uso;");
	    return $res;
	}
	
public function listarProductosOperadores ($conexion, $producto,$tipo,$subtipo){
		
    $res = $conexion->ejecutarConsulta("select
													distinct
													 p.id_producto,
													 trim(p.nombre_comun),
													 '(Cultivo)' as tipo
												from
													g_catalogos.productos p
													, g_catalogos.subtipo_productos stp
													, g_catalogos.tipo_productos tp
												where
													p.id_subtipo_producto = stp.id_subtipo_producto
													and stp.id_tipo_producto = tp.id_tipo_producto
													and trim(upper(unaccent(p.nombre_comun))) like trim(upper(unaccent('%$producto%')))
													and upper(tp.nombre) = upper('$tipo')
													and upper(stp.nombre) = upper('$subtipo')	
												union
												select
													distinct
													 p.id_producto, 
													 trim(p.nombre_comun),
													 '(Otros)' as tipo
												from
													g_catalogos.productos p
													, g_catalogos.subtipo_productos stp
													, g_catalogos.tipo_productos tp
												where
													p.id_subtipo_producto = stp.id_subtipo_producto
													and stp.id_tipo_producto = tp.id_tipo_producto
													and trim(upper(unaccent(p.nombre_comun))) like trim(upper(unaccent('%$producto%')))
													and tp.nombre <> ''
													and trim(upper(unaccent(p.nombre_comun))) not in (
															select
																distinct
																 trim(upper(unaccent(p.nombre_comun)))
																 
															from
																g_catalogos.productos p
																, g_catalogos.subtipo_productos stp
																, g_catalogos.tipo_productos tp
															where
																p.id_subtipo_producto = stp.id_subtipo_producto
																and stp.id_tipo_producto = tp.id_tipo_producto
																and trim(upper(unaccent(p.nombre_comun))) like trim(upper(unaccent('%$producto%')))
																and upper(tp.nombre) = upper('$tipo')
																and upper(stp.nombre) = upper('$subtipo')
												
													) 
												order by
													2,1 ;");
		return $res;
	}
	
	public function obtenerCodigoOtroCultivo ($conexion, $tipoProducto,$subTipoProducto){
		
		$res = $conexion->ejecutarConsulta("select
												tp.id_tipo_producto,tp.nombre,stp.id_subtipo_producto,stp.nombre 
											from
												g_catalogos.tipo_productos tp, g_catalogos.subtipo_productos stp
											where
												upper(tp.nombre) = upper('$tipoProducto')
												and upper(stp.nombre) = upper('$subTipoProducto')
												and stp.id_tipo_producto = tp.id_tipo_producto;");
		return $res;
	}
	
	public function buscarProductoOtrosCultivo ($conexion, $nombreProducto,$idTipoProducto,$idSubTipoProducto){
		
		$res = $conexion->ejecutarConsulta("SELECT 
													p.* 
											FROM  	
													g_catalogos.productos p, 
												  	g_catalogos.subtipo_productos stp, 
												 	g_catalogos.tipo_productos tp
											WHERE 
													p.id_subtipo_producto = stp.id_subtipo_producto
													and stp.id_tipo_producto = tp.id_tipo_producto
													and trim(upper(unaccent(p.nombre_comun))) = trim(upper(unaccent('$nombreProducto')))
													and p.id_subtipo_producto = $idSubTipoProducto
													and stp.id_tipo_producto = $idTipoProducto");
		return $res;
	}
	
	
	public function guardarProductoOtroCultivos ($conexion, $producto, $productoCientifico, $estado,$idSubtipoProducto ,$unidadMedida){
		$res = $conexion->ejecutarConsulta("insert into g_catalogos.productos(nombre_comun, nombre_cientifico, estado, 
														id_subtipo_producto,unidad_medida, fecha_creacion)
											values('$producto','$productoCientifico',$estado,
													$idSubtipoProducto,'$unidadMedida',now()) returning id_producto;");
		return $res;
	}
	
	public function obtenerTipoProductoXrequisitoProductoPais($conexion, $area){
				
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (tp.id_tipo_producto),
												tp.nombre,
												id_area
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto
												and rc.nombre_pais != 'Ecuador'
												and tp.id_area IN $area
											ORDER BY 
												tp.nombre asc;");
		
		
		return $res;
		
		
	}
	
	public function obtenerSubTipoProductoXrequisitoProductoPais($conexion, $idTipoProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (sp.id_subtipo_producto),
												sp.nombre
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												tp.id_tipo_producto = $idTipoProducto
											ORDER BY
												sp.nombre asc;");
	
	
		return $res;
	
	
	}
	
	public function obtenerProductoXrequisitoProductoPais($conexion, $idSubTipoProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (p.id_producto),
												p.nombre_comun
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												sp.id_subtipo_producto = $idSubTipoProducto
											ORDER BY
												p.nombre_comun asc;");
	
	
		return $res;
	
	
	}
	
	public function autogenerarSecuencialCodigoVariedad($conexion,$idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(CAST(pv.codigo_variedad as  numeric(4))) secuencial	
											FROM
												g_catalogos.productos_variedades pv
											WHERE
												pv.id_producto=$idProducto;");
		return $res;
	}
	
	public function guardarVariedadProducto ($conexion, $idProducto, $idVariedad, $codigoVariedad){
		$res = $conexion->ejecutarConsulta("insert into g_catalogos.productos_variedades(id_producto, id_variedad, codigo_variedad)
				values($idProducto,$idVariedad,'$codigoVariedad');");
		return $res;
	}
	
	public function guardarProductoVegetal ($conexion, $idProducto){
	
		$res = $conexion->ejecutarConsulta("insert into g_catalogos.productos_vegetales(id_producto)
				SELECT '$idProducto' WHERE NOT EXISTS(SELECT id_producto FROM g_catalogos.productos_vegetales WHERE id_producto='$idProducto');");
		return $res;
	}
	
	public function buscarVariedadProducto ($conexion, $idProducto, $idVariedad,$codigoVariedad){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos_variedades
											WHERE
												id_producto = $idProducto
												and id_variedad= $idVariedad;");
		return $res;
	}
	
	public function imprimirVariedad($idProducto, $idVariedad,$nombreVariedad, $codigoVariedad){
		return '<tr id="R' . $idProducto . '-'.$idVariedad.'-'.$codigoVariedad.'">' .
				'<td width="100%">' .
				'<b>Variedad:</b> '.$nombreVariedad .' <b>Código variedad: </b>'. $codigoVariedad.
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="administracionProductos" data-opcion="eliminarVariedadProducto">' .
				'<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
				'<input type="hidden" name="idVariedad" value="' . $idVariedad . '" >' .
				'<input type="hidden" name="nombreVariedad" value="' . $nombreVariedad . '" >' .
				'<input type="hidden" name="codigoVariedad" value="' . $codigoVariedad . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function ListarVariedadesXProducto ($conexion,$idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												pv.id_producto, pv.id_variedad,
												pv.codigo_variedad, v.nombre
											FROM
												g_catalogos.productos_variedades pv,
												g_catalogos.variedades v
											WHERE
												pv.id_variedad=v.id_variedad and
												pv.id_producto = '$idProducto';");
		return $res;
	}
	
	public function quitarVariedadProducto($conexion, $idProducto,$idVariedad, $codigoVariedad){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.productos_variedades
											WHERE
												id_producto = $idProducto
												and id_variedad = '$idVariedad'
												and codigo_variedad = '$codigoVariedad';");
		return $res;
	
	}
	
	public function guardarMultiplesVariedades($conexion, $idProducto, $idOperacion,$siNoMultiple){
		$res = $conexion->ejecutarConsulta("insert into g_catalogos.productos_multiples_variedades(id_producto, id_tipo_operacion, multiple_variedad)
				values('$idProducto','$idOperacion','$siNoMultiple');");
		return $res;
	}
	
	public function buscarMultiplesVariedades ($conexion, $idProducto, $idOperacion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.productos_multiples_variedades
											WHERE
												id_producto = $idProducto
												and id_tipo_operacion= $idOperacion;");
		return $res;
	}
	
	public function listaOperacionesConVariedades($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												distinct(top.id_tipo_operacion),
												top.nombre
											FROM
												g_catalogos.tipos_operacion top,
												g_catalogos.productos_multiples_variedades pmv
											WHERE
												top.id_tipo_operacion=pmv.id_tipo_operacion");
		return $res;
	}
	
	public function imprimirTipoOperacionMultiplesVariedades($idProducto, $nombreComun,$idTipoOperacion, $multipleVariedad){
	
		return '<tr class="hola" id="R' . $idProducto . '-'.$idTipoOperacion.'">' .
				'<td>' . $idProducto . '</td>' .
				'<td>' . $nombreComun . '</td>' .
				'<td>' . $multipleVariedad . '</td>' .
				'<td>' .
				'<form id="imprimirVariedad" class="borrar" data-rutaAplicacion="administracionProductos" data-opcion="eliminarListaVariedadProducto"  >' .
				'<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
				'<input type="hidden" name="idTipoOperacion" value="' . $idTipoOperacion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function quitarProductoMultipleVariedad($conexion, $idProducto,$idTipoOPeracion){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.productos_multiples_variedades
											WHERE
												id_producto = $idProducto
												and id_tipo_operacion = '$idTipoOPeracion';");
		return $res;
	
	}
	
public function mostrarRequisitosFiltro($conexion, $areaTematica,$pais,$tipoRequisito,$idProducto,$nombreProducto=NULL,$partidaArancelaria=NULL){
	
		$pais = $pais!="" ?  $pais  : 'NULL';
		$idProducto = $idProducto!="" ?  $idProducto  : 'NULL';
		//$tipoRequisito = $tipoRequisito!="" ? "'" . $tipoRequisito . "'" : "NULL";
		$nombreProducto = $nombreProducto!="" ? "'%" . $nombreProducto . "%'" : "NULL";
		$partidaArancelaria = $partidaArancelaria!="" ? $partidaArancelaria : "NULL";
	
		$res = $conexion->ejecutarConsulta("
				 select row_to_json(requisitos)
                                    from (
                                        select
                                       		tp.nombre tipo,
											st.nombre subtipo,
											pr.nombre_comun,
                                            rc.id_producto,
                                            rc.id_requisito_comercio,
                                            rc.declaracion,
                                            rc.numero_resolucion,
                                            rc.fecha as fecha_resolucion,
                                            rc.ruta_archivo as archivo_resolucion,
                                            rc.nombre_pais
                                            ,(select array_to_json(array_agg(row_to_json(r_p)))
                                            from (
													select
                                                    	ra.orden,
                                                        ra.tipo,
                                                        r.detalle,
                                                        r.detalle_impreso
                                                   	from
                                                    	g_requisitos.requisitos_asignados ra,
                                                        g_requisitos.requisitos r
                                                    where
                                                    	rc.id_requisito_comercio = ra.id_requisito_comercio and
                                                        r.id_requisito = ra.requisito and
                                                        ra.estado='activo' and
														($tipoRequisito is NULL or r.tipo in $tipoRequisito) and
                                                        r.estado = 1
                                                     order by
                                                        ra.orden
                                            ) r_p) as requisito_pais
                                        from
                                            g_requisitos.requisitos_comercializacion rc , g_catalogos.productos pr, g_catalogos.subtipo_productos st,g_catalogos.tipo_productos tp, g_catalogos.localizacion l
                                        where
											pr.id_producto=rc.id_producto and
											pr.id_subtipo_producto=st.id_subtipo_producto and
											rc.id_localizacion = l.id_localizacion and 
											l.categoria not in (5) and
											st.id_tipo_producto=tp.id_tipo_producto and ($idProducto is NULL or rc.id_producto = $idProducto)
											and ($nombreProducto is NULL or quitar_caracteres_especiales_sin_espacio(pr.nombre_comun) ilike $nombreProducto)
											and ($partidaArancelaria is NULL or pr.partida_arancelaria = $partidaArancelaria)                         
											and ($pais is NULL or rc.id_localizacion = $pais)
                                            and rc.tipo='$areaTematica' 
             							order by rc.nombre_pais
                                    ) as requisitos;");
				return $res;
	}
	
	public function obtenerProductosConRequisitos($conexion, $areaTematica,$pais,$actividadComercial,$idProducto,$nombreProducto,$uso, $partidaArancelaria=NULL ){
	
		$pais = $pais!="" ?  $pais  : 'NULL';
		$idProducto = $idProducto!="" ?  $idProducto  : 'NULL';
		$partidaArancelaria = $partidaArancelaria!="" ?  "'" .$partidaArancelaria. "'"   : "NULL";
		$uso = $uso!="" ?  "'" .$uso. "'"   : "NULL";
		
		if($uso != 'NULL' && $areaTematica === 'IAP'){
		    $busqueda = ", g_catalogos.usos_productos_plaguicidas upp";
		    $parametro = "and pr.id_producto = upp.id_producto  and quitar_caracteres_especiales_sin_espacio(upp.cultivo_nombre_comun) ilike '%$uso%'";
		}
		
if($areaTematica === 'IAP'){
	
    		$res = $conexion->ejecutarConsulta("SELECT 
    												distinct rc.id_producto
    											FROM 
    												g_requisitos.requisitos_asignados ra,
    												g_requisitos.requisitos r,
    												g_requisitos.requisitos_comercializacion rc,
    												g_catalogos.productos pr,
                                                    g_catalogos.partidas_arancelarias pa
                                                    ".$busqueda."
    											 WHERE
    												rc.id_requisito_comercio = ra.id_requisito_comercio
    												and r.id_requisito = ra.requisito
    												and pr.id_producto=rc.id_producto
                                                    and pr.id_producto = pa.id_producto 
    												and ra.estado='activo' 
    												and r.estado = 1 
    												and r.tipo in $actividadComercial 
    												and ($idProducto is NULL or rc.id_producto = $idProducto)
    												and quitar_caracteres_especiales_sin_espacio(pr.nombre_comun) ilike '%$nombreProducto%'
    												and ($partidaArancelaria is NULL or pa.partida_arancelaria = $partidaArancelaria)
    												and ($pais is NULL or rc.id_localizacion = $pais) 
    												and rc.tipo='$areaTematica' 
                                                    ".$parametro."
    											ORDER BY
    												rc.id_producto");
		}else{
		    $res = $conexion->ejecutarConsulta("SELECT
    												distinct rc.id_producto
    											FROM
    												g_requisitos.requisitos_asignados ra,
    												g_requisitos.requisitos r,
    												g_requisitos.requisitos_comercializacion rc,
    												g_catalogos.productos pr
    											 WHERE
    												rc.id_requisito_comercio = ra.id_requisito_comercio
    												and r.id_requisito = ra.requisito
    												and pr.id_producto=rc.id_producto
    												and ra.estado='activo'
    												and r.estado = 1
    												and r.tipo in $actividadComercial
    												and ($idProducto is NULL or rc.id_producto = $idProducto)
    												and quitar_caracteres_especiales_sin_espacio(pr.nombre_comun) ilike '%$nombreProducto%'
    												and ($partidaArancelaria is NULL or pr.partida_arancelaria = $partidaArancelaria)
    												and ($pais is NULL or rc.id_localizacion = $pais)
    												and rc.tipo='$areaTematica'
    											ORDER BY
    												rc.id_producto");
		}
		
		return $res;
	}
	
	
	public function mostrarDatosGeneralesDeProductoSinRequisito($conexion,$tipoArea, $producto, $nombreProducto, $partidaArancelaria, $uso=NULL){
	    
		$dato = '';
		$busqueda = '';
		$parametro = '';
		
		$partidaArancelaria = $partidaArancelaria!="" ?  "'" .$partidaArancelaria. "'"   : "NULL";
		$uso = $uso!="" ?  "'" .$uso. "'"   : "NULL";
		
		if($uso != 'NULL' && $tipoArea === 'IAP'){
		    $dato = ", upp.cultivo_nombre_comun";
		    $busqueda = ", g_catalogos.usos_productos_plaguicidas upp";
		    $parametro = "and p.id_producto = upp.id_producto and quitar_caracteres_especiales_sin_espacio(upp.cultivo_nombre_comun) ilike '%$uso%'";
		}
		
		if($partidaArancelaria != 'NULL' && $tipoArea === 'IAP'){
			$busqueda += ", g_catalogos.partidas_arancelarias pa";
			$parametro += "and p.id_producto = pa.id_producto and pa.partida_arancelaria = $partidaArancelaria";
		}
		
		if($tipoArea === 'IAP'){
		    
		    $res = $conexion->ejecutarConsulta("SELECT
    												distinct tp.nombre tipo
    												, stp.nombre subtipo
    												, p.id_producto
    												, p.nombre_comun producto
    												, p.nombre_cientifico cientifico
    												, p.codigo_producto
    												, p.unidad_medida
                                                    ".$dato."
    											FROM
    												g_catalogos.productos p
    												, g_catalogos.subtipo_productos stp
    												, g_catalogos.tipo_productos tp
                                                    ".$busqueda."
    											WHERE
    												p.estado = 1
    												and stp.id_subtipo_producto = p.id_subtipo_producto
    												and tp.id_tipo_producto = stp.id_tipo_producto
    												and p.id_producto not in $producto
    												and  quitar_caracteres_especiales_sin_espacio(p.nombre_comun) ilike '%$nombreProducto%'
    												and tp.id_area='$tipoArea'
                                                    ".$parametro."
    											ORDER BY
    												p.nombre_comun;");
		}else{
		    
		    $res = $conexion->ejecutarConsulta("SELECT
    												tp.nombre tipo
    												, stp.nombre subtipo
    												, p.id_producto
    												, p.nombre_comun producto
    												, p.nombre_cientifico cientifico
    												, p.partida_arancelaria
    												, p.codigo_producto
    												, p.unidad_medida
    											FROM
    												g_catalogos.productos p
    												, g_catalogos.subtipo_productos stp
    												, g_catalogos.tipo_productos tp
    											WHERE
    												p.estado = 1
    												and stp.id_subtipo_producto = p.id_subtipo_producto
    												and tp.id_tipo_producto = stp.id_tipo_producto
    												and p.id_producto not in $producto
    												and  quitar_caracteres_especiales_sin_espacio(p.nombre_comun) ilike '%$nombreProducto%'
    												and ($partidaArancelaria is NULL or p.partida_arancelaria = $partidaArancelaria)
    												and tp.id_area='$tipoArea'
    											ORDER BY
    												p.nombre_comun;");		
		}
		
		return $res;
	}
	
	public function listarRequisitosXidArea ($conexion, $area){
		$res = $conexion->ejecutarConsulta("SELECT
												re.*, a.nombre as area
											FROM
												g_requisitos.requisitos as re,
												g_estructura.area as a
											WHERE
												re.id_area = a.id_area
												and a.id_area IN $area
											ORDER BY
												re.codigo asc;");
				return $res;
	}
	
	
	
	public function listarTipoProductoXAreas ($conexion, $areas){
	
	
	$res = $conexion->ejecutarConsulta("SELECT 
											tp.*, a.nombre as nombre_area
										FROM
											g_catalogos.tipo_productos as tp,
											g_estructura.area a
										WHERE
											tp.id_area = a.id_area and
											tp.id_area IN $areas
										ORDER BY 2;");
			return $res;
	}
	
	public function buscarDatosEspecificosProductosIAVIAP ($conexion, $idArea,$idProducto){
		
		$busqueda = '';
		
		if($idArea=='IAV')
			$busqueda=" limit 5";
	
		if($idArea === 'IAP'){
		    $res = $conexion->ejecutarConsulta("SELECT row_to_json(productosR)
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
                                                				unidad as unidad_medida
                                                			FROM
                                                				g_catalogos.presentaciones_plaguicidas pp
                                                				INNER JOIN g_catalogos.codigos_comp_supl css ON pp.id_codigo_comp_supl = css.id_codigo_comp_supl
                                                				INNER JOIN g_catalogos.partidas_arancelarias pa ON css.id_partida_arancelaria = pa.id_partida_arancelaria
                                                			WHERE
                                                				pa.id_producto = p.id_producto 
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
                                                            	g_catalogos.fabricante_formulador ff
                                                            	INNER JOIN g_catalogos.manufacturador m ON ff.id_fabricante_formulador = m.id_fabricante_formulador
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
														FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
                                                	WHERE
                                                		tp.id_area = '$idArea' and p.id_producto='$idProducto'
											     ) as productosR;");
		    
		}else{	
		$res = $conexion->ejecutarConsulta("SELECT row_to_json(productosR)
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
															u.nombre_uso,e.nombre as nombre_producto_inocuidad
														FROM
															g_catalogos.producto_inocuidad_uso pu,
															g_catalogos.usos u,
															g_catalogos.especies e
														WHERE 
															 pu.id_uso = u.id_uso
															AND e.id_especies = pu.id_especie
															AND  pu.id_producto = p.id_producto
														UNION 
														SELECT
															u.nombre_uso,pr.nombre_comun as nombre_producto_inocuidad
														FROM
															g_catalogos.producto_inocuidad_uso pu, 
															g_catalogos.usos u,
															g_catalogos.productos pr
														WHERE 
															pu.id_uso = u.id_uso
															AND  pu.id_aplicacion_producto = pr.id_producto
															AND  pu.id_producto = p.id_producto
														UNION 
														SELECT
															u.nombre_uso,pu.instalacion as nombre_producto_inocuidad
														FROM
															g_catalogos.producto_inocuidad_uso pu,
															g_catalogos.usos u
														WHERE 
															 pu.id_uso = u.id_uso
															AND pu.aplicado_a = 'Instalacion'
															AND  pu.id_producto  = p.id_producto
														ORDER BY
															nombre_uso
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
																ingrediente_activo ".$busqueda."
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
													END as estado
												FROM g_catalogos.productos as p
													FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
													FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
													FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
													FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
												WHERE
													tp.id_area = '$idArea' and p.id_producto='$idProducto'
											) as productosR;");
		}
		
		return $res;
	}
	
	///Requisitos de Movilización
	public function obtenerTipoProductoMovilizacion($conexion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												distinct (tp.id_tipo_producto),
												tp.nombre,
												id_area
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto
                                                and p.movilizacion = 'SI'
											ORDER BY
												tp.nombre asc;");
	    
	    
	    return $res;
	    
	    
	}
	
	public function obtenerSubtipoProductoMovilizacion($conexion, $idTipoProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												distinct (sp.id_subtipo_producto),
												sp.nombre
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
                                                p.movilizacion = 'SI' and
												tp.id_tipo_producto = $idTipoProducto
											ORDER BY
												sp.nombre asc;");
	    
	    
	    return $res;
	    
	    
	}
	
	public function obtenerProductoMovilizacion($conexion, $idSubTipoProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												distinct (p.id_producto),
												p.nombre_comun
											FROM
												g_requisitos.requisitos_comercializacion rc,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
                                                p.movilizacion = 'SI' and
												sp.id_subtipo_producto = $idSubTipoProducto
											ORDER BY
												p.nombre_comun asc;");
	    
	    
	    return $res;
	    
	    
	}
	
	public function listarRequisitosMovilizacionProducto ($conexion, $identificador, $tipoBusqueda){
	    
	    $busqueda = '';
	    
	    switch ($tipoBusqueda){
	        case 'tipoProducto':
	            $busqueda = " tp.id_tipo_producto  = $identificador";
	            break;
	            
	        case 'subTipoProducto':
	            $busqueda = " sp.id_subtipo_producto  = $identificador";
	            break;
	            
	        case 'producto':
	            $busqueda = " p.id_producto  = $identificador";
	            break;
	    }
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												distinct (rq.id_requisito_comercio),
                                                rq.id_producto,
												rq.nombre_producto,
												rq.tipo,
												a.nombre as nombre_area,
												a.id_area
											FROM
												g_requisitos.requisitos_comercializacion rq,
												g_estructura.area as a,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												rq.tipo = a.id_area and
												rq.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
                                                p.movilizacion = 'SI' and
												upper(rq.nombre_pais) = upper('Ecuador') and
												".$busqueda.";");
	    return $res;
	}
	
	public function listarTipoProductoMovilizacion ($conexion){
	    
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												distinct (tp.id_tipo_producto), tp.nombre
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												p.movilizacion='SI' and
                                                tp.id_area = 'SV'
											ORDER BY
												tp.nombre asc;");
	    return $res;
	}
	
	public function listarSubtipoProductoMovilizacion ($conexion){
	    $res = $conexion->ejecutarConsulta("SELECT
												distinct (sp.id_subtipo_producto), sp.nombre, tp.id_tipo_producto
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												p.movilizacion='SI' and
                                                tp.id_area = 'SV'
											ORDER BY
												sp.nombre asc;");
	    return $res;
	}
	
	public function listarProductosMovilizacion ($conexion){
	    $res = $conexion->ejecutarConsulta("SELECT
												distinct (p.id_producto), p.nombre_comun, sp.id_subtipo_producto
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												p.movilizacion='SI' and
                                                tp.id_area = 'SV'
											ORDER BY
												p.nombre_comun asc;");
	    return $res;
	}
	
	public function listarRequisitosMovilizacionXArea ($conexion, $tipo, $area){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_requisitos.requisitos r
											WHERE
												r.id_area  = '".$area."' and
                                                r.tipo = '".$tipo."'
											ORDER BY
												r.nombre;");
	    return $res;
	}
	
	public function listarRequisitosMovilizacionAsignados ($conexion,$idRequisitoComercio, $tipo){
	    $res = $conexion->ejecutarConsulta("SELECT
												ra.id_requisito_comercio,
												ra.requisito,
												r.nombre,
												r.tipo,
												ra.estado
											FROM
												g_requisitos.requisitos_asignados ra,
												g_requisitos.requisitos r
											WHERE
												ra.id_requisito_comercio=$idRequisitoComercio and
												ra.requisito = r.id_requisito and
                                                r.tipo = '$tipo'
											ORDER BY
												ra.orden;");
	    return $res;
	}
	
	/**** PRODUCTOS PLAGUICIDAS ****/
	
	/********** PARTIDA ARANCELARIA ************/
	
	public function buscarPartidaArancelaria ($conexion, $idProducto, $partidaArancelaria){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.partidas_arancelarias
											WHERE
												id_producto = $idProducto and
												partida_arancelaria = '$partidaArancelaria';");
	    return $res;
	}
	
	public function guardarPartidaArancelaria($conexion, $idProducto, $partidaArancelaria, $codigoProducto){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO
                                                g_catalogos.partidas_arancelarias(id_producto, partida_arancelaria, codigo_producto)
                                            VALUES (".$idProducto.", '".$partidaArancelaria."', '".$codigoProducto."')
                                            RETURNING
                                                id_partida_arancelaria;");
	    return $res;
	}
	
	public function obtenerCodigoProductoPlaguicidas ($conexion, $partida){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												COALESCE(
													MAX(
														CAST(codigo_producto as  numeric(5))),0)+1 as codigo
											FROM
												g_catalogos.partidas_arancelarias
											WHERE
												partida_arancelaria = '$partida';");
	    return $res;
	}
	
	public function actualizarEstadoPartida ($conexion, $idPartida, $estado, $identificador){
	    $res = $conexion->ejecutarConsulta("UPDATE
    											g_catalogos.partidas_arancelarias
    										SET
    											estado = '$estado',
    											fecha_modificacion = now(),
    											identificador_modificacion = '$identificador'
    										WHERE
    											id_partida_arancelaria = $idPartida;");
	    return $res;
	}
	
	public function listarPartidasArancelarias($conexion, $idProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.partidas_arancelarias
											WHERE
												id_producto = $idProducto
											ORDER BY
												2;");
	    return $res;
	}
	
	public function buscarPartidaArancelariaXProductoPlaguicida($conexion, $idPartida){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.partidas_arancelarias
											WHERE
												id_partida_arancelaria = $idPartida
											ORDER BY
												2;");
	    return $res;
	}
	
	/********** CÓDIGO COMPLEMENTARIO Y SUPLEMENTARIO ************/
	
	public function buscarCodigoCompSuplPlaguicida ($conexion, $idPartidaArancelaria, $codigoComplementario, $codigoSuplemetario){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.codigos_comp_supl
											WHERE
												id_partida_arancelaria = $idPartidaArancelaria and
												codigo_complementario = '$codigoComplementario' and
												codigo_suplementario = '$codigoSuplemetario';");
	    return $res;
	}
	
	public function guardarNuevoCodigoCompSuplPlaguicida ($conexion, $idPartidaArancelaria, $codigoComplementario, $codigoSuplemetario){
	    $res = $conexion->ejecutarConsulta("INSERT INTO
                                                g_catalogos.codigos_comp_supl(id_partida_arancelaria, codigo_complementario, codigo_suplementario)
                                            VALUES ($idPartidaArancelaria, '$codigoComplementario', '$codigoSuplemetario')
                                            RETURNING
                                                id_codigo_comp_supl;");
	    return $res;
	}
	
	public function buscarCodigoCompSuplXproductoPartidaPlaguicida ($conexion, $idPartida){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.codigos_comp_supl
											WHERE
												id_partida_arancelaria = $idPartida
                                            ORDER BY
                                                4, 5 ASC;");
	    return $res;
	}
	
	public function actualizarEstadoCodigoCompSupl($conexion, $idCodigoCompSupl, $estado, $identificador){
	    $res = $conexion->ejecutarConsulta("UPDATE
    											g_catalogos.codigos_comp_supl
    										SET
    											estado = '$estado',
    											fecha_modificacion = now(),
    											identificador_modificacion = '$identificador'
    										WHERE
    											id_codigo_comp_supl = $idCodigoCompSupl;");
	    
	    return $res;
	}
	
	public function buscarCodigoCompSuplXPartidaArancelaria ($conexion, $idCodigoCompSupl){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.codigos_comp_supl
											WHERE
												id_codigo_comp_supl = $idCodigoCompSupl
                                            ORDER BY
                                                4, 5 ASC;");
	    return $res;
	}
	
	/********** PRESENTACIONES ************/
	
	public function buscarPresentacionesXCodigoCompSupl ($conexion, $idCodigoCompSupl){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.presentaciones_plaguicidas
											WHERE
												id_codigo_comp_supl = $idCodigoCompSupl
                                            ORDER BY
                                                4, 5 ASC;");
	    return $res;
	}
	
	public function actualizarEstadoPresentacion($conexion, $idPresentacion, $estado, $identificador){
	    $res = $conexion->ejecutarConsulta("UPDATE
    											g_catalogos.presentaciones_plaguicidas
    										SET
    											estado = '$estado',
    											fecha_modificacion = now(),
    											identificador_modificacion = '$identificador'
    										WHERE
    											id_presentacion = $idPresentacion;");
	    
	    return $res;
	}
	
	public function buscarPresentacionPlaguicida ($conexion, $idCodigoCompSupl, $presentacion, $idUnidad){
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.presentaciones_plaguicidas
											WHERE
												id_codigo_comp_supl = $idCodigoCompSupl and
												upper(presentacion) = upper('$presentacion') and
												id_unidad = $idUnidad;");
	    return $res;
	}
	
	public function obtenerCodigoPresentacionPlaguicida ($conexion, $idProducto, $idPartida, $idCodigoCompSupl){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
                                            	COALESCE(
                                            		MAX(
                                            			CAST(codigo_presentacion as  numeric(5))),0)+1 as codigo
                                            FROM
                                            	g_catalogos.presentaciones_plaguicidas pp
                                            	INNER JOIN g_catalogos.codigos_comp_supl ccs ON pp.id_codigo_comp_supl = ccs.id_codigo_comp_supl
                                            	INNER JOIN g_catalogos.partidas_arancelarias pa ON ccs.id_partida_arancelaria = pa.id_partida_arancelaria
                                            WHERE
                                            	pa.id_producto = $idProducto and
                                            	pa.id_partida_arancelaria = $idPartida and
                                            	ccs.id_codigo_comp_supl = $idCodigoCompSupl;");
	    return $res;
	}
	
	public function guardarNuevaPresentacionPlaguicida ($conexion, $presentacion, $idUnidad, $unidad, $codigoPresentacion, $idCodigoCompSupl){
	    $res = $conexion->ejecutarConsulta("INSERT INTO
                                                g_catalogos.presentaciones_plaguicidas(id_codigo_comp_supl, presentacion, codigo_presentacion, id_unidad, unidad)
                                            VALUES ($idCodigoCompSupl, '$presentacion', '$codigoPresentacion', $idUnidad, '$unidad')
                                            RETURNING
                                                id_presentacion;");
	    return $res;
	}
	
	
	/*---------------------- VALIDACIONES CERTIFICADO -----------------------*/
	
	public function listarPartidasCodigosPresentaciones($conexion, $idProducto){
	    $res = $conexion->ejecutarConsulta("SELECT
                                            	pa.id_partida_arancelaria,
                                            	ccs.id_codigo_comp_supl,
                                            	pp.id_presentacion
                                            FROM
                                            	g_catalogos.partidas_arancelarias pa
                                            	INNER JOIN g_catalogos.codigos_comp_supl ccs ON pa.id_partida_arancelaria = ccs.id_partida_arancelaria
                                            	INNER JOIN g_catalogos.presentaciones_plaguicidas pp ON ccs.id_codigo_comp_supl = pp.id_codigo_comp_supl
                                            WHERE
                                            	pa.id_producto = $idProducto and
                                            	pa.estado = 'activo' and
                                            	ccs.estado = 'activo' and
                                            	pp.estado = 'activo';");
	    return $res;
	}
	
	public function listarFabricanteManufacturador($conexion, $idProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
                                            	ff.id_fabricante_formulador
                                            FROM
                                            	g_catalogos.fabricante_formulador ff															
                                            WHERE
                                            	ff.id_producto = $idProducto and
                                            	ff.estado = 'activo';");
	    return $res;
	}
	
	public function listarCompuestosProducto($conexion, $idProducto, $tipoCompuesto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
                                            	*
                                            FROM
                                            	g_catalogos.composicion_inocuidad
                                            WHERE
                                            	tipo_componente = '$tipoCompuesto' and
                                            	id_producto =  $idProducto;");
	    return $res;
	}
	
	/********************************************************/
	public function imprimirLineaPartidasArancelarias($idPartida, $idProducto, $partidaArancelaria, $codigoProducto, $estado, $ruta, $area){
	    
	    return '<tr id="R' . $idPartida . '">' .
        	   	    '<td width="50%"><b>Partida: </b>' .
        	   	       $partidaArancelaria .
        	   	    '</td>' .
        	   	    '<td width="50%"><b>Código producto: </b>' .
        	   	       $codigoProducto .
        	   	    '</td>' .
        	   	    '<td>' .
            	   	    '<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirPartidaPlaguicida" data-destino="detalleItem" data-accionEnExito="NADA" >' .
                	   	    '<input type="hidden" id="idPartida" name="idPartida" value="' . $idPartida . '" >' .
                	   	    '<input type="hidden" id="idProducto" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<input type="hidden" id="partidaArancelaria" name="partidaArancelaria" value="' . $partidaArancelaria . '" >' .
                	   	    '<input type="hidden" id="areaProducto" name="areaProducto" value="' . $area . '" >' .
                	   	    '<button class="icono" type="submit" ></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
        	   	    '<td>' .
            	   	    '<form class="'.$estado.'" data-rutaAplicacion="'.$ruta.'" data-opcion="actualizarEstadoPartidaPlaguicida">' .
            	   	        '<input type="hidden" id="idPartida" name="idPartida" value="' . $idPartida . '" >' .
                	   	    '<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
                	   	    '<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<input type="hidden" name="partidaArancelaria" value="' . $partidaArancelaria . '" >' .
                	   	    '<input type="hidden" id="areaProducto" name="areaProducto" value="' . $area . '" >' .
                	   	    '<button type="submit" class="icono"></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
    	   	    '</tr>';
	}
	
	public function imprimirCodigoCompSuplPlaguicida($idCodCompSupl, $idProducto, $idPartida, $partidaArancelaria, $codigoComplementario, $codigoSuplementario, $areaProducto, $estado, $ruta){
	    
	    return '<tr id="R' . $idCodCompSupl.'">' .
        	   	    '<td width="50%">' .
        	   	       '<b>Código Complementario:</b> '.$codigoComplementario .
        	   	    '</td>' .
        	   	    '<td width="50%">'.
        	   	       '<b>Código Suplementario: </b>'. $codigoSuplementario.
        	   	    '</td>'.
        	   	    '<td>' .
            	   	    '<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirCodigoComplSuplPlaguicida" data-destino="detalleItem" data-accionEnExito="NADA" >' .
            	   	        '<input type="hidden" id="idCodigoCompSupl" name="idCodigoCompSupl" value="' . $idCodCompSupl . '" >' .
                	   	    '<input type="hidden" id="idProducto" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<input type="hidden" id="idPartida" name="idPartida" value="' . $idPartida . '" >' .
                	   	    '<input type="hidden" id="partidaArancelaria" name="partidaArancelaria" value="' . $partidaArancelaria . '" >' .
                	   	    '<input type="hidden" id="codigoComplementario" name="codigoComplementario" value="' . $codigoComplementario . '" >' .
                	   	    '<input type="hidden" id="codigoSuplementario" name="codigoSuplementario" value="' . $codigoSuplementario . '" >' .
                	   	    '<input type="hidden" name="areaProducto" value="'. $areaProducto  .'"/>'.
                	   	    '<button class="icono" type="submit" ></button>' .
        	   	       '</form>' .
        	   	    '</td>' .
        	   	    '<td>' .
            	   	    '<form class="'.$estado.'" data-rutaAplicacion="'.$ruta.'" data-opcion="actualizarEstadoCodigoCompSuplPlaguicida">' .
                	   	    '<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
                	   	    '<input type="hidden" id="idCodigoCompSupl" name="idCodigoCompSupl" value="' . $idCodCompSupl . '" >' .
                	   	    '<input type="hidden" id="idProducto" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<input type="hidden" id="partidaArancelaria" name="partidaArancelaria" value="' . $partidaArancelaria . '" >' .
                	   	    '<input type="hidden" id="codigoComplementario" name="codigoComplementario" value="' . $codigoComplementario . '" >' .
                	   	    '<input type="hidden" id="codigoSuplementario" name="codigoSuplementario" value="' . $codigoSuplementario . '" >' .
                	   	    '<button type="submit" class="icono"></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
    	   	    '</tr>';
	}
	
	public function imprimirLineaPresentacionPlaguicida($idProducto, $idPresentacion, $presentacion, $codigoUnidad, $codigoPresentacion, $estado, $ruta){
	    
	    return '<tr id="R' . $idPresentacion.'">' .
        	   	    '<td width="50%">' .
        	   	       '<b>Presentación:</b> '.$presentacion . ' ' . $codigoUnidad .
            	   	'</td>' .
            	   	'<td width="50%">'.
            	   	   '<b>Código Presentación: </b>'. $codigoPresentacion.
            	   	'</td>'.
            	   	'<td>' .
            	   	    '<form class="'.$estado.'" data-rutaAplicacion="'.$ruta.'" data-opcion="actualizarEstadoPresentacionPlaguicida">' .
                	   	    '<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
                	   	    '<input type="hidden" id="idProducto" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<input type="hidden" id="idPresentacion" name="idPresentacion" value="' . $idPresentacion . '" >' .
                	   	    '<input type="hidden" id="presentacion" name="presentacion" value="' . $presentacion . '" >' .
                	   	    '<button type="submit" class="icono"></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
    	   	    '</tr>';
	}
	
	public function listarTipoIngredienteActivoXArea($conexion, $idArea, $incremento, $datoIncremento){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.ingrediente_activo_inocuidad
                                            WHERE
                                                id_area = '$idArea'
											ORDER BY
												ingrediente_activo ASC
											offset
												$datoIncremento rows
											fetch next
												$incremento rows only;");
	    return $res;
	}
	
	public function buscarFormuladorFabricantePlaguicida ($conexion, $tipo, $nombreFabFor, $idPais, $idProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.fabricante_formulador
											WHERE
												UPPER(nombre) = UPPER('$nombreFabFor') and
												id_pais_origen = $idPais and
												id_producto = $idProducto and
                                                tipo = '$tipo';");
	    return $res;
	}
	
	public function guardarNuevoFabricanteFormuladorPlaguicida ($conexion, $idProducto, $nombreFabFor, $idPais, $nombrePais, $tipo){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO 
                                                g_catalogos.fabricante_formulador(id_producto, nombre, id_pais_origen, pais_origen, tipo)
											VALUES 
                                                ($idProducto, '$nombreFabFor', $idPais,'$nombrePais', '$tipo') 
                                            RETURNING 
                                                id_fabricante_formulador;");
	    
	    return $res;
	}
	
	public function imprimirFabricanteFormuladorPlaguicida($idFabFor, $idProducto, $area, $tipo, $nombreFabFor, $nombrePais, $estado, $ruta){
	    
	    return '<tr id="R' . $idFabFor .'">' .
        	   	    '<td width="5%">' .
        	   	       $idFabFor .
        	   	    '</td>' .
        	   	    '<td width="45%">' .
        	   	       '<b>'.$tipo.': </b>'. $nombreFabFor .
    	   	       '</td>' .
    	   	       '<td width="45%">' .
        	   	       '<b>País: </b>'. $nombrePais.
        	   	    '</td>' .
        	   	    '<td>' .
            	   	    '<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirFabricanteFormuladorPlaguicida" data-destino="detalleItem" data-accionEnExito="NADA" >' .
                	   	    '<input type="hidden" name="idFabricanteFormulador" value="' . $idFabFor . '" >' .
                	   	    '<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<input type="hidden" name="area" value="' . $area . '" >' .
                	   	    '<button type="submit" class="icono"></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
        	   	    '<td>' .
            	   	    '<form class="'.$estado.'" data-rutaAplicacion="'.$ruta.'" data-opcion="actualizarEstadoFabricanteFormuladorPlaguicida">' .
                	   	    '<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
                	   	    '<input type="hidden" name="idFabricanteFormulador" value="' . $idFabFor . '" >' .
                	   	    '<input type="hidden" name="nombreFabricanteFormulador" value="' . $nombreFabFor . '" >' .
                	   	    '<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<button type="submit" class="icono"></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
    	   	    '</tr>';
	}
	
	public function actualizarEstadoFabricanteFormulador($conexion, $idFabForm, $estado, $identificador){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
    											g_catalogos.fabricante_formulador
    										SET
    											estado = '$estado',
    											fecha_modificacion = now(),
    											identificador_modificacion = '$identificador'
    										WHERE
    											id_fabricante_formulador = $idFabForm;");
	    
	    return $res;
	}
	
	public function abrirFabricanteFormulador ($conexion, $idFabForm){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.fabricante_formulador
											WHERE
												id_fabricante_formulador = $idFabForm;");
	    return $res;
	}
	
	public function buscarManufacturadorPlaguicida ($conexion, $idFabricanteFormulador, $manufacturador, $idPais){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.manufacturador
											WHERE
												id_fabricante_formulador = $idFabricanteFormulador and
												upper(manufacturador) = upper('$manufacturador') and
												id_pais_origen = $idPais;");
	    return $res;
	}
	
	public function guardarNuevoManufacturadorPlaguicida ($conexion, $idFabricanteFormulador, $manufacturador, $idPais, $nombrePais){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO
                                                g_catalogos.manufacturador(id_fabricante_formulador, manufacturador, id_pais_origen, pais_origen)
                                            VALUES ($idFabricanteFormulador, '$manufacturador', $idPais, '$nombrePais')
                                            RETURNING
                                                id_manufacturador;");
	    return $res;
	}
	
	public function imprimirLineaManufacturadorPlaguicida($idProducto, $idManufacturador, $manufacturador, $nombrePais, $estado, $ruta){
	    
	    return '<tr id="R' . $idManufacturador.'">' .
        	   	    '<td width="5%">' .
        	   	       $idManufacturador .
        	   	    '</td>' .
        	   	    '<td width="50%">' .
        	   	       '<b>Manufacturador:</b> '.$manufacturador .
        	   	    '</td>' .
        	   	    '<td width="50%">'.
        	   	       '<b>País: </b>'. $nombrePais.
        	   	    '</td>'.
        	   	    '<td>' .
            	   	    '<form class="'.$estado.'" data-rutaAplicacion="'.$ruta.'" data-opcion="actualizarEstadoManufacturadorPlaguicida">' .
                	   	    '<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
                	   	    '<input type="hidden" id="idManufacturador" name="idManufacturador" value="' . $idManufacturador . '" >' .
                	   	    '<input type="hidden" id="idProducto" name="idProducto" value="' . $idProducto . '" >' .
                	   	    '<button type="submit" class="icono"></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
        	  '</tr>';
	}
	
	public function buscarManufacturaforesXFabricanteFormulador ($conexion, $idFabricanteFormulador){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.manufacturador
											WHERE
												id_fabricante_formulador = $idFabricanteFormulador
                                            ORDER BY
                                                manufacturador, pais_origen ASC;");
	    return $res;
	}
	
	public function actualizarEstadoManufacturador($conexion, $idManufacturador, $estado, $identificador){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
    											g_catalogos.manufacturador
    										SET
    											estado = '$estado',
    											fecha_modificacion = now(),
    											identificador_modificacion = '$identificador'
    										WHERE
    											id_manufacturador = $idManufacturador;");
	    
	    return $res;
	}
	
	public function buscarUsoPlaguicida ($conexion, $idProducto, $idUsoPlaga, $idCultivo){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.usos_productos_plaguicidas
											WHERE
												id_producto = $idProducto and
                                                id_plaga = $idUsoPlaga and
                                                id_cultivo = $idCultivo;");
	    return $res;
	}
	
	public function guardarNuevoUsoPlaguicida ($conexion, $idProducto, $idUsoPlaga, $plagaComun, $plagaCientifico, $idCultivo, $cultivoComun, $cultivoCientifico,
	                                               $dosis, $unidadDosis, $periodoCarencia, $gastoAgua, $unidadAgua){

	    $res = $conexion->ejecutarConsulta("INSERT INTO
                                                g_catalogos.usos_productos_plaguicidas(id_producto, id_plaga, plaga_nombre_comun, 
                                                    plaga_nombre_cientifico, id_cultivo, cultivo_nombre_comun, cultivo_nombre_cientifico, 
                                                    dosis, unidad_dosis, periodo_carencia, gasto_agua, unidad_gasto_agua)
                                            VALUES ($idProducto, $idUsoPlaga, '$plagaComun', 
                                                    '$plagaCientifico', $idCultivo, '$cultivoComun', '$cultivoCientifico',
                                                    '$dosis', '$unidadDosis', '$periodoCarencia', '$gastoAgua', '$unidadAgua')
                                            RETURNING
                                                id_uso;");
	    return $res;
	}
	
	public function imprimirLineaUsoPlaguicida($idUso, $idProducto, $plagaComun, $plagaCientifico, $cultivoComun, $cultivoCientifico, $dosis, $unidadDosis, 
	                                               $periodoCarencia, $gastoAgua, $unidadAgua, $ruta){
	    
	    return '<tr id="R' . $idUso.'">' .
        	   	    '<td>' .
        	   	       $cultivoComun .
        	   	    '</td>' .
        	   	    '<td>'.
        	   	       $cultivoCientifico.
        	   	    '</td>'.
        	   	    '<td>' .
        	   	       $plagaComun .
        	   	    '</td>' .
        	   	    '<td>'.
        	   	       $plagaCientifico.
        	   	    '</td>'.
        	   	    '<td>' .
        	   	       $periodoCarencia .
        	   	    '</td>' .
        	   	    '<td>'.
        	   	       $gastoAgua. ' ' . $unidadAgua.
        	   	    '</td>'.
        	   	    '<td>'.
        	   	       $dosis . ' '. $unidadDosis.
        	   	    '</td>'.
        	   	    '<td>' .
            	   	    '<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarUsoPlaguicida"  >' .
            	   	       '<input type="hidden" name="idUso" value="' . $idUso . '" >' .
            	   	       '<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
                	   	   '<button type="submit" class="icono"></button>' .
            	   	    '</form>' .
        	   	    '</td>' .
    	   	    '</tr>';
	}
	
	public function eliminarUsoPlaguicida ($conexion, $idUso){
	    
	    $res = $conexion->ejecutarConsulta("DELETE from
												g_catalogos.usos_productos_plaguicidas
											WHERE
												id_uso = $idUso;");
	    return $res;
	    
	}
	
	public function listarUsoPlaguicida($conexion,$idProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.usos_productos_plaguicidas
											WHERE
												id_producto = $idProducto;");
	    return $res;
	}
	
	//------------
	
	public function guardarRutaCertificado($conexion,$idProducto,$ruta){
    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.productos
											SET
												ruta_certificado = '$ruta'
											WHERE
												id_producto = $idProducto;");
	    
	    return $res;
	}
	
	public function obtenerCertificado($conexion,$idProducto){
	    
	    $res=$conexion->ejecutarConsulta("  SELECT
                        						ruta_certificado
                        					FROM
                        						g_catalogos.productos
                        					WHERE
                        						id_producto = $idProducto;");
	    
	    return $res;
	}
	
	public function listarTipoComponente($conexion, $idArea){
	    $res = $conexion->ejecutarConsulta("SELECT
									             *
									        FROM
									            g_catalogos.tipo_componente
											WHERE
												estado_tipo_componente = 'Activo'
												and id_area = '$idArea'
											ORDER BY
												tipo_componente;");
	    return $res;
	}
	
	public function listarComposicionPlaguicida($conexion, $idProducto){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_catalogos.composicion_inocuidad
											WHERE
												id_producto = $idProducto;");
	    return $res;
	}
}