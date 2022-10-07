<?php
class ControladorProtocolos{
	
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
	
	
	
	public function buscarProtocoloPaisProducto($conexion, $idPais, $idProducto, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_protocolos.protocolos_comercializacion pc,
												g_protocolos.protocolos_asignados pa
											WHERE
												pa.id_protocolo_comercio = pc.id_protocolo_comercio and
												pc.id_localizacion = $idPais and
												pc.id_producto = $idProducto and
												pa.estado = '$estado';");
		return $res;
	}
	
	
	public function buscarProtocoloXCodigoAreaProductoPais ($conexion, $idArea, $idProducto, $idPais, $estado){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_protocolos.protocolos_areas_asignados paa,
												g_protocolos.protocolos_areas pa
											WHERE
												paa.id_protocolo_area = pa.id_protocolo_area and
												pa.id_area = '$idArea'  and
												pa.id_producto = '$idProducto' and
												pa.id_pais = '$idPais' and
												paa.estado_protocolo_asignado='$estado';");
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
	 
	
	public function buscarExportadorProductoPaisSancion ($conexion, $idExportador, $idProducto, $idPais, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_notificaciones_sanciones.sanciones
											WHERE
												identificador_exportador = '$idExportador' and
												id_producto = $idProducto and
												id_pais = $idPais and
												estado_sancion = '$estado'");
		return $res;
	}
	
	///---------------PARA PROTOCOLO CFE--------------------------------------------------------///


	
//---------------------------------	
	
	/*public function obtenerTipoProductoXProtocoloProductoPais($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (tp.id_tipo_producto),
												tp.nombre,
												id_area
											FROM
												g_protocolos.protocolos_comercializacion pc,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												pc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto
											ORDER BY 
												tp.nombre asc;");
		return $res;
	
	
	}*/
	
	
	
	
////////PROTOCOLO COMERCIO///////////
	
//------------------1--------------//

	public function obtenerTipoProductoXProtocolo($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (tp.id_tipo_producto), tp.nombre
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp,
												g_protocolos.protocolos_comercializacion pc
											WHERE
												pc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto
											ORDER BY
												tp.nombre asc;");
	
	
		return $res;
	
	
	}	


//------------------2--------------//

	public function obtenerSubtipoProductoXProtocolo($conexion, $idTipoProducto){

		$res = $conexion->ejecutarConsulta("SELECT
												distinct (sp.id_subtipo_producto), sp.nombre
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp,
												g_protocolos.protocolos_comercializacion pc
											WHERE
												pc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												tp.id_tipo_producto = $idTipoProducto
											ORDER BY
												sp.nombre asc;");
	
	
		return $res;
	
	
	}
	

	
//------------------3--------------//	

	public function obtenerProductoXProtocolo($conexion, $idSubtipoProducto){

		$res = $conexion->ejecutarConsulta("SELECT
												distinct (p.id_producto), p.nombre_comun
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp,
												g_protocolos.protocolos_comercializacion pc
											WHERE
												pc.id_producto = p.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												sp.id_subtipo_producto = $idSubtipoProducto
											ORDER BY
												p.nombre_comun asc;");
	
	
				return $res;
	
	
	}
	
	

//------------------4--------------//	
	
	public function listarProtocoloComercio ($conexion, $identificador, $tipoBusqueda){
	
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
														a.id_area		
													FROM
														g_protocolos.protocolos_comercializacion rq,
														g_estructura.area as a,
														g_catalogos.productos p,
														g_catalogos.subtipo_productos sp,
														g_catalogos.tipo_productos tp
													WHERE
														a.id_area=tp.id_area and
														rq.id_producto = p.id_producto and
														p.id_subtipo_producto = sp.id_subtipo_producto and
														sp.id_tipo_producto = tp.id_tipo_producto and
														".$busqueda.";");
		return $res;
	}
		

	
//PARA GUARDAR PROTOCOLO PAIS///


//------------------1--------------//	
	
	public function listarPaisesProtocoloXProducto ($conexion, $idProducto){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_protocolos.protocolos_comercializacion
											WHERE
												id_producto = $idProducto;");
		return $res;
	}
		

	
//------------------2--------------//	
	
	public function imprimirLineaProtocoloPais($idComercioPais, $nombrePais, $idPais, $nombreProducto){
		return '<tr id="R' . $idComercioPais . '">' .
				'<td width="100%">' .
				$nombrePais . ' - ' . $nombreProducto.
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="administracionRequisitos" data-opcion="abrirPaisProtocolo" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idProtocoloComercio" value="' . $idComercioPais . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="administracionRequisitos" data-opcion="eliminarProtocoloPais">' .
				'<input type="hidden" name="idProtocoloComercio" value="' . $idComercioPais . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	
//------------------2--------------//	
	
	public function buscarProtocoloPais ($conexion,$idProducto, $idPais){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_protocolos.protocolos_comercializacion
											WHERE
												id_producto = $idProducto and
												id_localizacion = $idPais;");
		return $res;
	}
	

//------------------3--------------//
	
	public function guardarProtocoloComercio($conexion, $producto, $nombreProducto, $pais, $nombrePais, $identificadorCreacionProtocoloComercio){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO g_protocolos.protocolos_comercializacion(id_producto, nombre_producto, id_localizacion, nombre_pais, identificador_creacion_protocolo_comercializacion)
										VALUES ($producto, '$nombreProducto', $pais, '$nombrePais', '$identificadorCreacionProtocoloComercio')
										RETURNING id_protocolo_comercio;");
	    return $res;
	}
	
//------------------4--------------//
	
	public function quitarPaisProtocolo($conexion, $idProtocoloComercio){
	
		$res = $conexion->ejecutarConsulta("delete from
				g_protocolos.protocolos_comercializacion
				where
				id_protocolo_comercio=$idProtocoloComercio;");
		return $res;
	
	}


//------------------5--------------//
		
	public function abrirProtocolosComercio ($conexion,$idProtocoloComercio){
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_protocolos.protocolos_comercializacion
				WHERE
				id_protocolo_comercio=$idProtocoloComercio;");
		return $res;
	}
	
	

//------------------6--------------//
	
	public function listarProtocolosAsignados ($conexion,$idProtocoloComercio){
		$res = $conexion->ejecutarConsulta("SELECT
												pa.id_protocolo_comercio,
												pa.id_protocolo,
												p.nombre_protocolo,
												pa.estado
											FROM
												g_protocolos.protocolos_asignados pa,
												g_protocolos.protocolos p
											WHERE
												pa.id_protocolo_comercio=$idProtocoloComercio and
												pa.id_protocolo = p.id_protocolo
											ORDER BY
												pa.orden;");
				return $res;
	}
	

//------------------7--------------//	
	
	public function listarProtocolos ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_protocolos.protocolos;");
		return $res;
	}
	
		
//------------------8--------------//	
	
	public function imprimirLineaProtocolo($idProtocoloComercio, $idProtocolo, $nombreProtocolo, $estado){
			
		return '<tr id="R' . $idProtocolo . '">' .
				'<td width="100%">' .
				$nombreProtocolo .
				'</td>' .
				'<td>' .
				'<form class="'.$estado.'" data-rutaAplicacion="administracionRequisitos" data-opcion="actualizarEstadoProtocolo">' .
				'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
				'<input type="hidden" name="idProtocoloComercio" value="' . $idProtocoloComercio . '" >' .
				'<input type="hidden" name="idProtocolo" value="' . $idProtocolo . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="administracionRequisitos" data-opcion="quitarProtocolo">' .
				'<input type="hidden" name="idProtocoloComercio" value="' . $idProtocoloComercio . '" >' .
				'<input type="hidden" name="idProtocolo" value="' . $idProtocolo . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	


//------------------9--------------//
		
	public function buscarProductoProtocolo ($conexion, $idProtocoloComercio, $protocolo){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_protocolos.protocolos_asignados
											WHERE
												id_protocolo_comercio = $idProtocoloComercio and
												id_protocolo = $protocolo;");
		return $res;
	}
	

	
//------------------10--------------//
		
	public function guardarNuevoProtocoloAsignado($conexion, $idProtocoloComercio, $protocolo, $estado, $identificadorCreacionProtocoloAsignado){
	    
	    $res = $conexion->ejecutarConsulta("INSERT INTO
			g_protocolos.protocolos_asignados(id_protocolo_comercio, id_protocolo, estado, identificador_creacion_protocolo_asignado)
			VALUES ($idProtocoloComercio, $protocolo, '$estado', '$identificadorCreacionProtocoloAsignado');");
	    return $res;
	}


//------------------11--------------//

	public function quitarProtocoloAsignado($conexion, $idProtocoloComercio, $idProtocolo){

		$res = $conexion->ejecutarConsulta("DELETE FROM
				g_protocolos.protocolos_asignados
				WHERE
				id_protocolo_comercio = $idProtocoloComercio and
				id_protocolo = $idProtocolo;");
		return $res;
	
	}	
	

//------------------12--------------//
	
	public function actualizarEstadoProtocolo ($conexion, $idProtocoloComercio, $idProtocolo, $estado, $identificadorModificacionProtocoloAsignado){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
                                    			g_protocolos.protocolos_asignados
                                    		SET
                                    			estado = '$estado',
                                    			fecha_modificacion = now(),
                                    			identificador_modificacion_protocolo_asignado = '$identificadorModificacionProtocoloAsignado'
                                    		WHERE
                                    			id_protocolo_comercio= $idProtocoloComercio
                                    			and id_protocolo = $idProtocolo;");
	    return $res;
	}
		

	
//------------------13--------------//
	
	public function listarTipoProductoPrograma ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (tp.id_tipo_producto), tp.nombre
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												p.programa='SI'
												ORDER BY
												tp.nombre asc;");
		return $res;
	}	
	
	

//------------------14--------------//
	
	public function listarSubtipoProductoPrograma ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (sp.id_subtipo_producto), sp.nombre, tp.id_tipo_producto
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												p.programa='SI'
												ORDER BY
												sp.nombre asc;");
		return $res;
	}
	


//------------------15--------------//
			
	public function listarProductosPrograma ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT
												distinct (p.id_producto), p.nombre_comun, sp.id_subtipo_producto
											FROM
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos tp
											WHERE
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = tp.id_tipo_producto and
												p.programa='SI'
												ORDER BY
												p.nombre_comun asc;");
		return $res;
	}


//------------------16--------------//	
	
	public function actualizarProtocoloComercio($conexion, $idProtocoloComercio, $declaracion, $numeroResolucion, $observacion, $archivo, $fecha, $identificadorModificacionProtocoloComercio){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
											g_protocolos.protocolos_comercializacion
										SET
											declaracion = '$declaracion',
											numero_resolucion = '$numeroResolucion',
											observacion = '$observacion',
											ruta_archivo = '$archivo',
											fecha = '$fecha',
											fecha_modificacion = now(),
											identificador_modificacion_protocolo_comercializacion = '$identificadorModificacionProtocoloComercio'
										WHERE
											id_protocolo_comercio = $idProtocoloComercio;");
	    return $res;
	}
	
	

	//PARA INSPECCIONES DE PROTOCOLO///	

	
//------------------1--------------//
	
	public function filtroAreasProtocolos ($conexion, $identificadorOperador, $razonSocial, $codigoSitio){
	    
	    $identificadorOperador = $identificadorOperador != "" ? "'" .  $identificadorOperador  . "'" : "NULL";
	    $razonSocial = $razonSocial != "" ? "'%" . $razonSocial . "%'" : "NULL";
	    $codigoSitio = $codigoSitio != "" ? "'" . $codigoSitio . "'" : "NULL";
	    	    
        $consulta = "SELECT
                                                pa.id_protocolo_area
                                                , o.identificador as identificador_operador
                                                , CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social end nombre_operador
                                                , s.id_sitio
                                                , s.nombre_lugar
                                                , a.id_area
                                                , pa.codigo_area
                                                , a.nombre_area
                                                , pa.id_tipo_operacion
                                           FROM
                                                g_protocolos.protocolos_areas pa
                                                INNER JOIN g_operadores.areas a ON pa.id_area = a.id_area
                                                INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                                                INNER JOIN g_operadores.operadores o ON s.identificador_operador = o.identificador
                                           WHERE
                                                ($identificadorOperador is NULL or o.identificador = $identificadorOperador)
                                                and ($razonSocial is NULL or o.razon_social ilike $razonSocial)
                                                and ($codigoSitio is NULL or s.identificador_operador||'.'||s.codigo_provincia || s.codigo = $codigoSitio)
                                                ORDER BY a.nombre_area ASC;";
        
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
		
	
	

	public function listarProtocolosXPaisXProducto ($conexion, $idLocalizacion, $idProducto){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_protocolos.protocolos_comercializacion pc,
												g_catalogos.localizacion lo,
												g_protocolos.protocolos_asignados pa,
												g_protocolos.protocolos p
											WHERE
												pc.id_localizacion=lo.id_localizacion and
												pc.id_protocolo_comercio=pa.id_protocolo_comercio and
												p.id_protocolo= pa.id_protocolo
												and lo.id_localizacion=$idLocalizacion
												and pc.id_producto=$idProducto;");
		return $res;
	}
	
	
	public function buscarProtocoloArea ($conexion, $idArea, $idTipoOperacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
                                				*
                                			FROM
                                				g_protocolos.protocolos_areas
                                			WHERE
                                				id_area = $idArea
                                				and id_tipo_operacion = $idTipoOperacion;");
		return $res;
	}
	
	public function buscarProtocoloAreaAsignado ($conexion, $idProtocoloArea, $idProtocolo){
	
		$res = $conexion->ejecutarConsulta("SELECT
                                				*
                                			FROM
                                				g_protocolos.protocolos_areas_asignados
                                			WHERE
                                				id_protocolo_area = $idProtocoloArea
                                				and id_protocolo = $idProtocolo;");
		return $res;
	}
	
	
	
	
	public function guardarProtocoloArea($conexion, $nombreAreaCodigo, $codigoArea, $nombreOperacion, $operacion){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_protocolos.protocolos_areas(
				codigo_area, id_area, nombre_tipo_operacion, id_tipo_operacion)
				VALUES ('$nombreAreaCodigo', '$codigoArea', '$nombreOperacion', $operacion)
				RETURNING id_protocolo_area;");
				return $res;
	}
	
	
	public function obtenerIdProtocoloArea ($conexion, $codigoArea, $producto, $operacion, $pais){
		
		$res = $conexion->ejecutarConsulta("SELECT
				*
				FROM
				g_protocolos.protocolos_areas pa,
				WHERE
				id_area = $idArea
				and id_producto = $idProducto
				and id_tipo_operacion = $idTipoOperacion
				and id_pais = $idPais;");
		return $res;
	}
	
	
	
	public function guardarProtocoloAreaAsignado($conexion, $idProtocoloArea, $idProtocoloAsignado, $estadoProtocoloArea){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_protocolos.protocolos_areas_asignados(
				id_protocolo_area, id_protocolo, estado_protocolo_asignado)
				VALUES ($idProtocoloArea, $idProtocoloAsignado, '$estadoProtocoloArea') RETURNING id_protocolo_area_asignado;");
				return $res;
	}
	
	
	public function imprimirLineaProtocoloAreaAsignado($idProtocoloAreaAsignado, $nombreProtocolo, $nombreEstado){

		return '<tr id="R' . $idProtocoloAreaAsignado . '">' .
				'<td>' . $nombreProtocolo . '</td>' .
				'<td style="text-align:center">' . $nombreEstado . '</td>' .
				'<td style="text-align:center">' . 
				'<form class="abrir" data-rutaAplicacion="inspeccionesDeProtocolo" data-opcion="abrirProtocoloAreaAsignado" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idProtocoloAreaAsignado" value="' . $idProtocoloAreaAsignado . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<td style="text-align:center">' .
				'<form id="imprimirProtocolo" class="borrar" data-rutaAplicacion="inspeccionesDeProtocolo" data-opcion="quitarNuevoInspeccionprotocolo" >' .
				'<input type="hidden" name="idProtocoloAreaAsignado" value="' . $idProtocoloAreaAsignado . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
				
	}
	
	
	
	public function quitarProtocoloAreaAsignado($conexion, $idProtocoloAreaAsignado){
				
		$res = $conexion->ejecutarConsulta("DELETE FROM
				g_protocolos.protocolos_areas_asignados
				WHERE
				id_protocolo_area_asignado = $idProtocoloAreaAsignado;");
		return $res;
	
	}
		
	public function obtenerAreasProtocolosAsignados ($conexion, $idProtocoloArea){
				
		$res = $conexion->ejecutarConsulta("SELECT 
												p.nombre_protocolo, paa.id_protocolo_area_asignado, paa.estado_protocolo_asignado
											FROM
												g_protocolos.protocolos_areas pa,
												g_protocolos.protocolos_areas_asignados paa,
												g_protocolos.protocolos p
											WHERE
												pa.id_protocolo_area=paa.id_protocolo_area
												and pa.id_protocolo_area=$idProtocoloArea
												and p.id_protocolo = paa.id_protocolo 
											ORDER BY 1;");
		return $res;
	}
	
	
	public function obtenerAreasProtocolos ($conexion, $idProtocoloArea){
		$res = $conexion->ejecutarConsulta("SELECT 
                                                s.nombre_lugar as nombre_sitio
                                                , a.nombre_area
												, s.identificador_operador ||'.'||s.codigo_provincia||''||s.codigo codigo_sitio
                                                , CASE when o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                                                , pa.codigo_area
                                                , pa.nombre_tipo_operacion
											FROM
												g_protocolos.protocolos_areas pa,
												g_operadores.sitios s,
												g_operadores.areas a,
                                                g_operadores.operadores o
											WHERE
												pa.id_area=a.id_area
												and a.id_sitio=s.id_sitio
                                                and s.identificador_operador = o.identificador
												and id_protocolo_area=$idProtocoloArea;");
		return $res;
	}
	
///-----------------------FIN PARA PROTOCOLO CFE-------------------------------------------///
	
	public function obtenerProtocolosProductos ($conexion, $idLocalizacion, $idArea){

	    $res = $conexion->ejecutarConsulta("SELECT 
                                            apo.id_area, apo.productos_operador, ptc.id_protocolo_comercio, ptc.id_localizacion, ptc.nombre_pais, ptc.id_producto, ptc.nombre_producto, ptc.id_protocolo, ptc.nombre_protocolo
                                            FROM
                                            (SELECT 
                                            DISTINCT pao.id_area, op.id_producto as productos_operador
                                            FROM 
                                            g_operadores.productos_areas_operacion pao
                                            INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                                            INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                                            WHERE
                                            pao.id_area = " . $idArea . "
                                            and top.id_area || top.codigo in ('SVCOM', 'SVACO')) as apo
                                            INNER JOIN 
                                            (SELECT 
                                            pc.id_protocolo_comercio, pc.id_localizacion, pc.nombre_pais, pc.id_producto, pc.nombre_producto, p.id_protocolo, p.nombre_protocolo
                                            FROM 
                                            g_protocolos.protocolos_comercializacion pc
                                            INNER JOIN g_protocolos.protocolos_asignados pa ON pc.id_protocolo_comercio = pa.id_protocolo_comercio
                                            INNER JOIN g_protocolos.protocolos p ON pa.id_protocolo = p.id_protocolo) as ptc on ptc.id_producto = apo.productos_operador
                                            WHERE ptc.id_localizacion = " . $idLocalizacion . "
                                            ORDER BY 1, 5, 7 ,9;");
	    return $res;
	}
	
	public function obtenerProtocoloAreaAsignadoXId ($conexion, $idProtocoloAreaAsignado){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
                                				*
                                			FROM
                                				g_protocolos.protocolos_areas_asignados pa
                                            INNER JOIN g_protocolos.protocolos p ON pa.id_protocolo = p.id_protocolo
                                			WHERE
                                				id_protocolo_area_asignado = $idProtocoloAreaAsignado;");
	    return $res;
	}
	
	public function actualizarProtocoloAreaAsignado ($conexion, $idProtocoloAreaAsignado, $estadoProtocoloAsignado){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE 
                                                g_protocolos.protocolos_areas_asignados
                                            SET 
                                                estado_protocolo_asignado = '" . $estadoProtocoloAsignado . "'
                                                , fecha_modificacion_protocolo_area_asignado = now()
                                            WHERE 
                                                id_protocolo_area_asignado = $idProtocoloAreaAsignado;");
	    return $res;
	}
	
	public function imprimirReporteInspeccionesProtocolo ($conexion, $estado, $fechaInicio, $fechaFin){

	    $res = $conexion->ejecutarConsulta("SELECT
                                    	        o.identificador
                                    	        , CASE when o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                                    	        , s.identificador_operador||'.'||s.codigo_provincia || s.codigo as codigo_sitio
                                    	        , s.nombre_lugar
                                                , s.identificador_operador||'.'||s.codigo_provincia || s.codigo || a.codigo||a.secuencial as codigo_area
                                    	        , a.nombre_area
                                                , p.nombre_protocolo
                                    	        , paa.estado_protocolo_asignado
                                                , to_char(paa.fecha_modificacion_protocolo_area_asignado,'YYYY-MM-DD') as fecha_modificacion_protocolo_area_asignado
                                    	   FROM
                                    	        g_protocolos.protocolos_areas_asignados paa
                                    	        INNER JOIN g_protocolos.protocolos p ON paa.id_protocolo = p.id_protocolo
                                    	        INNER JOIN g_protocolos.protocolos_areas pa ON paa.id_protocolo_area = pa.id_protocolo_area
                                    	        INNER JOIN g_operadores.areas a ON pa.id_area = a.id_area
                                    	        INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                                    	        INNER JOIN g_operadores.operadores o ON s.identificador_operador = o.identificador
                                    	   WHERE
                                    	        paa.estado_protocolo_asignado = '" . $estado. "'
                                                and fecha_modificacion_protocolo_area_asignado >= '" . $fechaInicio. " 00:00:00'
                                                and fecha_modificacion_protocolo_area_asignado <= '" . $fechaFin. " 24:00:00';");
	    return $res;
	}	
	
	public function listarSitiosConProtocolosAprobados ($conexion, $identificadorOperador){

	    $res = $conexion->ejecutarConsulta("SELECT
                                            	DISTINCT s.identificador_operador
                                                , s.nombre_lugar
                                            	, s.identificador_operador||'.'||s.codigo_provincia || s.codigo as codigo_sitio
                                            FROM
                                            	g_protocolos.protocolos_areas_asignados paa
                                            	INNER JOIN g_protocolos.protocolos p ON paa.id_protocolo = p.id_protocolo
                                            	INNER JOIN g_protocolos.protocolos_areas pa ON paa.id_protocolo_area = pa.id_protocolo_area
                                            	INNER JOIN g_operadores.areas a ON pa.id_area = a.id_area
                                            	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                                            WHERE
                                            	s.identificador_operador = '" . $identificadorOperador ."';");
	    return $res;
	}
	
}