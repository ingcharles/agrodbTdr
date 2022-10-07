<?php
class ControladorAdministrarCaracteristicas{
	
	function listarProductoCaracteristicas($conexion ,$producto){
		
		$producto= $producto!= "" ? $producto: 'NULL';
		
		$consulta="SELECT DISTINCT	
					        p.id_producto, p.nombre_comun, tp.id_area
					  FROM 
							g_administracion_caracteristicas_productos.elemento e, g_catalogos.productos p,
							g_catalogos.subtipo_productos dp,
							g_catalogos.tipo_productos tp
					WHERE	
							dp.id_tipo_producto = tp.id_tipo_producto and
							dp.id_subtipo_producto = p.id_subtipo_producto 
							and e.id_producto = p.id_producto
							and ($producto is null or e.id_producto = $producto)
					ORDER BY 2 ;";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	function listarFormualrios($conexion){	
		$consulta="SELECT 
						id_formulario, nombre_formulario, archivo, id_aplicacion
  					FROM
						g_administracion_caracteristicas_productos.formularios;";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	function guardarCaracteristica($conexion,$detalle){
		$consulta="INSERT INTO 
						g_administracion_caracteristicas_productos.elemento(etiqueta, 
						tipo, id_formulario, id_catalogo_negocios,id_producto)
					VALUES
					$detalle;";
	
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}	
	
	function agregarCaracteristica($conexion,$etiqueta,$tipo,$idFormulario,$idCatalogo,$idProducto){
		$consulta="INSERT INTO
						g_administracion_caracteristicas_productos.elemento(
							etiqueta,tipo, id_formulario, id_catalogo_negocios,id_producto)
					VALUES('$etiqueta','$tipo',$idFormulario,$idCatalogo,$idProducto)
					returning id_elemento ;";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerCaracteristica($conexion,$idProducto,$idFormulario){
	    $consulta="SELECT
                    	id_elemento, etiqueta, tipo, id_formulario, id_catalogo_negocios,
                           id_producto, estado
                      FROM
                    	g_administracion_caracteristicas_productos.elemento
                    WHERE
                    	id_producto='$idProducto'
                    	and id_formulario='$idFormulario'
                        and estado=1
                        order by 1;";
	    //echo "<br> >> $consulta << <br>";
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerCaracteristicaXnombre($conexion,$nombre,$idProducto){
	    $consulta="SELECT
                    	id_elemento
                     FROM
                    	g_administracion_caracteristicas_productos.elemento
                    WHERE
                    	upper(etiqueta) = upper ('$nombre')
                    	and id_producto = '$idProducto';";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerCaracteristicaXnombreYformulario($conexion,$nombre,$idProducto,$idFormulario){
	    $consulta="SELECT
                    	id_elemento
                     FROM
                    	g_administracion_caracteristicas_productos.elemento
                    WHERE
                    	upper(etiqueta) = upper ('$nombre')
                    	and id_producto = '$idProducto'
                        and id_formulario = '$idFormulario';";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	
	public function verificarCaracteristicaXnombreYformulario($conexion,$nombre,$idProducto,$idFormulario){
	    $consulta="SELECT
                    	id_elemento
                     FROM
                    	g_administracion_caracteristicas_productos.elemento
                    WHERE
                    	upper(etiqueta) = upper ('$nombre')
                    	and id_producto = '$idProducto'
                        and id_formulario != '$idFormulario';";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerItemCaracteristicaXnombreYregistro($conexion,$key, $nombre, $registro, $tabla, $formulario){
	    $consulta="SELECT
                    	nombre, descripcion
                    FROM
                    	g_trazabilidad.vista_caracteristicas
                    WHERE
                    	id_registro = '$registro'
                    	and upper(etiqueta ) = upper('$nombre');";
	    
	    
	    $consulta="SELECT
                    	nombre, descripcion
                    FROM
                    	$tabla
                    WHERE
                    	$key = '$registro'
                        and id_formulario = $formulario
                    	and upper(etiqueta ) = upper('$nombre');";	    
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function imprimirElemento($idElemento,$idProducto,$con,$etiqueta,$formulario,$catalogo,$tipo, $estado){
						return '<tr id="R'.$idElemento.'">
								<td>'.$con.'</td>
								<td>'.$etiqueta.'</td>
								<td>'.$formulario.'</td>
								<td>'.$catalogo.'</td>
								<td>'.$tipo.'</td>
								<td style="text-align:center;display: flex;justify-content: center;">
								<form class="abrir" data-rutaAplicacion="administracionProductos" data-opcion="editarCaracteristicaProducto" data-destino="detalleItem"  >' .
								'<input type="hidden" name="idCatalogo" value="'.$idProducto.'" >'.
								'<input type="hidden" name="idElemento" value="'.$idElemento.'" >'.
								'<button class="icono" type="submit" ></button>' .
								'</form>
										
								<form class="'.$estado.'" data-rutaAplicacion="administracionProductos" data-opcion="actualizarEstadoCaracteristica">'.
								'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
								'<input type="hidden" name="idServicioProducto" value="' . $idElemento. '" >' .
								'<input type="hidden" name="idCatalogo" value="'.$idProducto.'" >'.
								'<button type="submit" class="icono"></button>'.
								'</form>
										
								</td>
								</tr>
						';
	}
	
	
	function listarCaracteristicasXProducto($conexion,$idProducto){
		$consulta="SELECT 
						e.id_elemento, e.etiqueta, f.nombre_formulario, c.nombre catalogo
						, (case when e.tipo='CB' then 'ComboBox' else (case when e.tipo='CH' then 'CheckBox' else 'RadioButtom' end) end ) tipo
						, e.id_producto, e.estado
					  FROM 
						g_administracion_caracteristicas_productos.elemento e, g_administracion_caracteristicas_productos.formularios f,
						g_administracion_catalogos.catalogos_negocio c
					WHERE
						e.id_producto=$idProducto
						and e.id_formulario = f.id_formulario
						and c.id_catalogo_negocios = e.id_catalogo_negocios
						and e.estado in(1,2)
					ORDER BY 3,2;";
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	function obtenerElementoxIDProducto($conexion,$idProducto){
		$consulta="SELECT
		e.id_elemento, e.etiqueta, f.nombre_formulario, c.nombre catalogo
		, (case when e.tipo='CB' then 'ComboBox' else (case when e.tipo='CH' then 'CheckBox' else 'RadioButtom' end) end ) tipo
		, e.id_producto, e.estado
		FROM
		g_administracion_caracteristicas_productos.elemento e, g_administracion_caracteristicas_productos.formularios f,
		g_administracion_catalogos.catalogos_negocio c
		WHERE
		e.id_producto=$idProducto
		and e.id_formulario = f.id_formulario
		and c.id_catalogo_negocios = e.id_catalogo_negocios
		and e.estado in(1,2)
		ORDER BY 2;";
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarEstadoCaracteristica($conexion,$idElemento,$estado){
		switch ($estado){
			case 'activo':
				$estado="1";
				break;
				
			case 'inactivo':
				$estado="2";
				break;
				
				/*default:
				 $estado="2";
				 break;*/
		}
			
		$consulta="UPDATE g_administracion_caracteristicas_productos.elemento
				   SET estado=$estado
				 WHERE 
					id_elemento=$idElemento;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerCaracteristicaXID($conexion,$idElemento){
		$consulta="SELECT 
					e.id_elemento, e.etiqueta,e.tipo, e.id_formulario, f.nombre_formulario, e.id_catalogo_negocios, 
				        e.id_producto, e.estado
				  FROM
					g_administracion_caracteristicas_productos.elemento e, g_administracion_caracteristicas_productos.formularios f
				  WHERE
					id_elemento=$idElemento
					and e.id_formulario = f.id_formulario";	
		
	$res = $conexion->ejecutarConsulta($consulta);
	return $res;
	}
	
	public function actualizarCaracteristica($conexion,$etiqueta,$tipo,$idCatalogo,$idElemento){
		$consulta="UPDATE 
						g_administracion_caracteristicas_productos.elemento
				   SET 
				   		etiqueta='$etiqueta', tipo='$tipo', id_catalogo_negocios='$idCatalogo'      
				 WHERE
						 id_elemento=$idElemento;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function estructurarTabla($conexion, $tablaResultado, $tabla, $campo){
	    $consulta="CREATE TEMP TABLE $tablaResultado ON COMMIT DROP AS
                     SELECT r.$campo,
                        r.id_producto,
                        f.id_formulario,
                        c.id_elemento,
                        e.etiqueta,
                        c.id_item,
                        ca.nombre,
                        ca.descripcion
                       FROM $tabla r,
                        g_administracion_caracteristicas_productos.caracteristica c,
                        g_administracion_caracteristicas_productos.elemento e,
                        g_administracion_caracteristicas_productos.formularios f,
                        g_administracion_catalogos.items_catalogo ca
                      WHERE 
                      r.$campo = c.id_registro 
                      AND e.id_elemento = c.id_elemento 
                      AND f.id_formulario = e.id_formulario 
                      AND e.id_producto = r.id_producto
                      AND ca.id_item = c.id_item
                      AND e.estado = 1;";
        
	    $res = $conexion->ejecutarConsulta($consulta);	    
	    return $res;
	}
	
	public function obtenerProductosXCaracteristica($conexion,$tabla,$caracteristica){
	    $consulta="select distinct 
                        rtrim(array_to_string(array_agg(distinct id_producto), ', '), ', ') as id_producto 
                    from 
                        $tabla where upper(etiqueta) = upper('$caracteristica');";
        
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerProductosConCaracteristicaXformulario($conexion,$tabla,$formulario){
	    $consulta="SELECT DISTINCT 
                        rtrim(array_to_string(array_agg(distinct t.id_producto), ', '), ', ') as id_producto 
                    FROM 
                        $tabla t
                    WHERE 
                        id_formulario='$formulario';";
        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
	}
	
	public function pivotearColumnas($conexion,$tablaResultado,$tabla,$producto,$formulario, $camposResultado,$pivote,$valor){
	    $consulta="SELECT
                	public.pivotearColumnas('$tablaResultado', 'select * from $tabla where id_producto in($producto) and id_formulario=$formulario',
                	array[$camposResultado], array[$pivote], '#.$valor',null);";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerRegistrosTabla($conexion,$tablaResultado, $producto){
	    $producto= $producto!="" ? "'" . $producto. "'"  : "NULL";
	    
	    if($producto!="NULL"){
	        $busqueda=" WHERE id_producto=$producto" ;
	    }
	    
	    $consulta="SELECT 
                        * 
                    FROM 
                        $tablaResultado 
                    ".$busqueda.";";	    
        
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerCaracteristicasXregistroYformulario($conexion,$registro,$formulario){
	    $consulta="SELECT 
                        id_registro, id_elemento, id_item, id_formulario
                  FROM 
                        g_administracion_caracteristicas_productos.caracteristica
                  WHERE
                    	id_registro = $registro
                    	and id_formulario=$formulario;";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerFormulario($conexion,$formulario){
	    $consulta="SELECT
                    	id_formulario, nombre_formulario, archivo, id_aplicacion, nombre_seccion
                      FROM
                    	g_administracion_caracteristicas_productos.formularios
                     WHERE
                    	archivo='$formulario';";
	    //echo "<br> >> $consulta << <br>";
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerFormularioXidModulo($conexion,$formulario,$modulo){
	    $consulta="SELECT
                    	id_formulario, nombre_formulario, archivo, id_aplicacion, nombre_seccion
                      FROM
                    	g_administracion_caracteristicas_productos.formularios
                     WHERE
                    	archivo='$formulario'
                        and id_aplicacion='$modulo';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	/*public function obtenerCaracteristica($conexion,$idProducto,$idFormulario){
	 $consulta="SELECT
	 id_elemento, etiqueta, tipo, id_formulario, id_catalogo_negocios,
	 id_producto, estado
	 FROM
	 g_administracion_caracteristicas_productos.elemento
	 WHERE
	 id_producto='$idProducto'
	 and id_formulario='$idFormulario'
	 and estado=1";
	 
	 $res = $conexion->ejecutarConsulta($consulta);
	 return $res;
	 }*/
	
	public function guardarCaracteristicaRegistro($conexion,$idRegistro,$idElemento,$idItem,$formulario){
	    $consulta="INSERT INTO g_administracion_caracteristicas_productos.caracteristica(
                        id_registro, id_elemento, id_item, id_formulario)
                     VALUES ($idRegistro, $idElemento, $idItem, $formulario);";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	    
	}
	
	public function obtenerModulo($conexion,$codigo){
	    $consulta="SELECT
                        id_aplicacion
                    FROM
                        g_programas.aplicaciones
                    WHERE
            	       codificacion_aplicacion='$codigo';";
	    
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	    
	}
	
	public function obtenerCaracteristicaXNombreYregistro($conexion, $nombre, $registro){
	    $consulta="SELECT
                    	nombre
                    FROM
                    	g_trazabilidad.vista_caracteristicas
                    WHERE
                    	id_registro = '$registro'
                    	and upper(etiqueta ) = upper('$nombre');";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	
	public function obtenerUsuariosXmodulo($conexion,$idModulo){
	    $consulta="SELECT
                    	id_aplicacion, identificador
                    FROM
                    	g_programas.aplicaciones_registradas
                   WHERE
                    	id_aplicacion='$idModulo';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}	
	
	public function mostrarCaracteristicasGuardadas($conexion,$idRegistro,$idFormulario){
	    
	    $consulta="SELECT  
                    	c.id_registro,e.id_elemento, e.etiqueta, e.tipo, cn.id_item, cn.nombre
                    FROM 
                    	g_administracion_caracteristicas_productos.caracteristica c,
                    	g_administracion_caracteristicas_productos.elemento e,
                    	g_administracion_catalogos.items_catalogo cn
                    WHERE 
                    	c.id_registro= '$idRegistro'
                    	and c.id_formulario='$idFormulario'
                    	and e.id_elemento = c.id_elemento
                    	and cn.id_item = c.id_item
                    ORDER BY 3;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function comprobarEtiqueta($conexion,$idProducto,$etiqueta,$formulario){
	    $consulta="SELECT
                    	e.id_elemento
                    FROM
                    	g_administracion_caracteristicas_productos.elemento e
                    WHERE
                    	e.id_producto='$idProducto'
                    	and upper(e.etiqueta)=upper('$etiqueta')
                    	and e.id_formulario='$formulario';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function comprobarCatalogo($conexion,$idProducto,$catalogo,$formulario){
	    $consulta="SELECT
                    	e.id_elemento
                    FROM
                    	g_administracion_caracteristicas_productos.elemento e
                    WHERE
                    	e.id_producto=$idProducto
                    	and e.id_catalogo_negocios='$catalogo'
                    	and e.id_formulario='$formulario';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
}