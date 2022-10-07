<?php
class ControladorAdministrarCatalogos{
    public function listarCatalogos($conexion,$nombre=NULL, $estado=null){
		
		$nombre = $nombre != "" ? $nombre : NULL;		
		
		if ($estado!=null){
		    $busqueda=" and estado=1";
		}
		
		$consulta = "SELECT 
							id_catalogo_negocios, nombre, descripcion, estado
					  FROM 
							g_administracion_catalogos.catalogos_negocio
					 WHERE  
                            '$nombre' is null or nombre ilike '%$nombre%'
                            $busqueda                            
					ORDER BY 2 ASC;
					";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function listarCatalogosxID($conexion,$idCatalogo){
		$consulta = "SELECT
							id_catalogo_negocios, nombre, descripcion, estado
					   FROM
							g_administracion_catalogos.catalogos_negocio
					  WHERE
							id_catalogo_negocios=$idCatalogo
					ORDER BY 2 ASC;
					";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}	
	
	public function abrirCatalogo($conexion,$idCatalogo){
		$consulta = "SELECT
							id_catalogo_negocios, nombre, descripcion, estado, codigo
					  FROM
							g_administracion_catalogos.catalogos_negocio
					WHERE
							id_catalogo_negocios=$idCatalogo;
					";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}	
	
	public function imprimirItem($conexion,$idItem,$con,$nombre,$idCatalogo,$estado,$estadoCatalogo){
	    
	    switch ($estado){
	        case '1':
	            $estado="activo";
	            break;
	            
	        case '2':
	            $estado="inactivo";
	            break;
	            
	        default:
	            $estado="inactivo";
	            break;
	    }
	    
	    return '<tr id="R'.$idItem.'"><td>'.$con.'</td><td>'. $nombre .'</td>'.
	   	    '<td style="text-align:center;width:100%">' .
	   	    '<form class="abrir" data-rutaAplicacion="administracionCatalogos" data-opcion="abrirItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
	   	    '<input type="hidden" name="idCatalogo" value="'.$idCatalogo.'" >'.
	   	    '<input type="hidden" name="idItem" value="'.$idItem.'" >'.
	   	    '<button class="icono" type="submit" ></button>' .
	   	    '</form>' .
	   	    '</td>
			<td>'.
			'<form class="'.$estado.'" data-rutaAplicacion="administracionCatalogos" data-opcion="actualizarEstadoItem">'.
			'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
			'<input type="hidden" name="idServicioProducto" value="' . $idItem. '" >' .
			'<input type="hidden" name="idCatalogo" value="'.$idCatalogo.'" >'.
			'<input type="hidden" name="estadoCatalogo" value="'.$estadoCatalogo.'" >'.
			'<button type="submit" class="icono"></button>'.
			'</form>'.
			'</td>
			</tr>'.
			'</td>';
	}
	
	public function obtenerItemXnombre($conexion,$nombre,$idCatalogo){
	    $consulta="SELECT
                        id_item
                  FROM 
                        g_administracion_catalogos.items_catalogo
                  WHERE
                	   upper(nombre) = upper('$nombre')
                	   and id_catalogo_negocios='$idCatalogo' ;";	    
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function verificarItemXnombreYid($conexion,$nombre,$idCatalogo){
	    $consulta="SELECT
                        id_item
                  FROM 
                        g_administracion_catalogos.items_catalogo
                  WHERE
                	   upper(nombre) = upper('$nombre')
                	   and id_item!='$idCatalogo'";                    	

	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function guardarItem($conexion,$nombre,$descripcion,$idCatalogo){
		$consulta="INSERT INTO g_administracion_catalogos.items_catalogo(
			            id_item, nombre, descripcion, estado, id_catalogo_negocios)
			    VALUES ('$nombre', '$descripcion', 1, $idCatalogo);";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarNombreCatalogo($conexion,$idCatalogo,$nombre,$codigo){
		$consulta = "UPDATE 
							g_administracion_catalogos.catalogos_negocio
					SET
						nombre='$nombre', codigo='$codigo'
		
					WHERE
					id_catalogo_negocios=$idCatalogo;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function guardarCatalogo($conexion,$nombre,$codigo){
		$consulta="INSERT INTO
						g_administracion_catalogos.catalogos_negocio(
						nombre, estado, codigo)
					VALUES 
						('$nombre', 1, '$codigo') returning id_catalogo_negocios;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerCatalogoXnombre($conexion,$nombre,$idCatalogo=null){
	    
	    if ($idCatalogo!=null){
	        $busqueda = "and id_catalogo_negocios != '$idCatalogo';";
	    } else{
	        $busqueda=";";
	    }
	    
	    $consulta="SELECT 
                        id_catalogo_negocios, nombre, descripcion, estado
                    FROM 
                        g_administracion_catalogos.catalogos_negocio
                    WHERE
                        upper(nombre) = upper('$nombre')
                        ".$busqueda;
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerCodigoCatalogo($conexion,$codigo,$idCatalogo=null){
	    
	    if ($idCatalogo!=null){
	        $busqueda = "and id_catalogo_negocios != '$idCatalogo';";
	    } else{
	        $busqueda=";";
	    }
	    
	    $consulta="SELECT
                        id_catalogo_negocios, nombre, descripcion, estado
                    FROM
                        g_administracion_catalogos.catalogos_negocio
                    WHERE
                        upper(codigo) = upper('$codigo')
                        ".$busqueda;
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function guardarDetalle($conexion,$detalle){
		$consulta="INSERT INTO 
						g_administracion_catalogos.items_catalogo(nombre, descripcion, estado, id_catalogo_negocios) 
					VALUES 
					$detalle RETURNING id_item;";
		
		$res = $conexion->ejecutarConsulta($consulta);		
		return $res;
	}
	
	public function listarItems($conexion,$idCatalogo, $estado=null){
	 
	    if($estado!=null){
	        $busqueda="and estado in ($estado)";
	    } 
	    
		$consulta="SELECT 
						id_item, nombre, descripcion, estado, id_catalogo_negocios
					FROM
						g_administracion_catalogos.items_catalogo
					WHERE                        
                        id_catalogo_negocios='$idCatalogo'
                        ".$busqueda."
					ORDER BY 2 asc;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerItemxID($conexion,$idItem){
		$consulta="SELECT 
						id_item, nombre, descripcion, estado, id_catalogo_negocios
					FROM
						g_administracion_catalogos.items_catalogo
					WHERE
						id_item=$idItem";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarItemCatalogo($conexion,$idItem,$nombre,$descripcion){
		$consulta="UPDATE 
						g_administracion_catalogos.items_catalogo
				   SET 
						nombre='$nombre', descripcion='$descripcion'
				 WHERE 
						id_item=$idItem;";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarEstadoItem($conexion,$idItem,$estado,$idCatalogo,$estadoCatalogo){
		switch ($estado){
			case 'activo':
				$estado="1";
				break;
				
			case 'inactivo':
				$estado="2";
				break;
		}
		
		if($estadoCatalogo==2){
			$consulta="UPDATE
							 g_administracion_catalogos.catalogos_negocio 
						SET 
							estado=1
					  WHERE 
							id_catalogo_negocios = $idCatalogo;";
			$res = $conexion->ejecutarConsulta($consulta);
		}
		
		$consulta="UPDATE
						g_administracion_catalogos.items_catalogo
					SET
						estado='$estado'
					WHERE
						id_item=$idItem;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function inactivarcatalgo($conexion,$id){			
		
		$consulta="SELECT g_administracion_catalogos.inactivarcatalgo(array[$id]);";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function listarItemsPorCodigo($conexion,$codigo, $estado=null){
	    
	    if($estado!=null){
	        $busqueda="and c.estado in ($estado)";
	    }
	    
	    $consulta="SELECT
						c.id_item, c.nombre, c.descripcion, c.estado, c.id_catalogo_negocios
					FROM
                        g_administracion_catalogos.catalogos_negocio cn, 
						g_administracion_catalogos.items_catalogo c
					WHERE
                        cn.id_catalogo_negocios = c.id_catalogo_negocios and 
                        codigo = '$codigo'
                        ".$busqueda."
					ORDER BY 2 asc;";
	   	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarCatalogosDisponibles($conexion,$idCatalogo){
	    $consulta="SELECT 
                    	id_catalogo_negocios, nombre, estado, codigo
                    FROM 
                    	g_administracion_catalogos.catalogos_negocio
                    WHERE
                    	id_catalogo_negocios != $idCatalogo
                    	and estado=1;";
						
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarCatalogosSinCatalogoPadre($conexion, $idCatalogoPadre){
	    $consulta="SELECT
                    	id_catalogo_negocios, nombre
                    FROM
                    	g_administracion_catalogos.catalogos_negocio
                    WHERE
                    	id_catalogo_negocios not in ($idCatalogoPadre)
                    	and estado=1;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarCatalogosAsignadosPorItem($conexion, $idCatalogoPadre, $idItemPadre, $nivel, $idSubitemCatalogoPadre, $estadoCatalogo = 'activo'){
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="SELECT
                    	distinct id_catalogo_padre, id_catalogo_hijo, id_item_padre, nombre
                    FROM
                    	g_administracion_catalogos.subitems_catalogo sc 
                        INNER JOIN g_administracion_catalogos.catalogos_negocio cn ON cn.id_catalogo_negocios = sc.id_catalogo_hijo
                    WHERE
                    	id_catalogo_padre  = $idCatalogoPadre
                    	and id_item_padre = $idItemPadre
                        and nivel = '$nivel'
                        and id_subitem_catalogo_padre $condicion  $idSubitemCatalogoPadre
                        and sc.estado_catalogo = '$estadoCatalogo';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function guardarCatalogoHijoAsignado($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $identificador, $nivel, $idSubitemCatalogoPadre, $estadoCatalogo = 'activo') {
	    
	    $consulta="INSERT INTO 
                            g_administracion_catalogos.subitems_catalogo(id_catalogo_padre, id_catalogo_hijo, id_item_padre, estado_catalogo, identificador_creacion, fecha_creacion, nivel, id_subitem_catalogo_padre)
                    VALUES ($idCatalogoPadre, $idCatalogoHijo, $idItemPadre, '$estadoCatalogo', '$identificador', 'now()', $nivel, $idSubitemCatalogoPadre) returning id_subitem_catalogo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function imprimirLineaCatalogoHijo($idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nombreCatalogoHijo, $nivel, $idExclusionCatalogo, $etapaProceso, $idSubitemCatalogoPadre){
	    return '<tr id="R' . $idCatalogoPadre .$idCatalogoHijo.$idItemPadre. '">' .
	   	    '<td width="100%">'.$nombreCatalogoHijo.'</td>' .
	   	    '<td>' .
	   	    '<form class="abrir" data-rutaAplicacion="administracionCatalogos" data-opcion="abrirSubItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
	   	    '<input type="hidden" name="idCatalogoPadre" value="' . $idCatalogoPadre . '" >' .
	   	    '<input type="hidden" name="idCatalogoHijo" value="' . $idCatalogoHijo. '" >' .
	   	    '<input type="hidden" name="idItemPadre" value="' . $idItemPadre . '" >' .
	   	    '<input type="hidden" name="nivel" value="' . $nivel . '" >' .
	   	    '<input type="hidden" name="idExclusionCatalogo" value="'.$idExclusionCatalogo.'" >'.
	   	    '<input type="hidden" name="etapaProceso" value='.serialize($etapaProceso).' >'.
	   	    '<input type="hidden" name="idSubitemCatalogoPadre" value="'.$idSubitemCatalogoPadre.'" >'.
	   	    '<input type="hidden" name="tipoProceso" value="carga" >'.
	   	    '<button class="icono" type="submit" ></button>' .
	   	    '</form>' .
	   	    '</td>' .
	   	    '<td>' .
	   	    '<form class="borrar" data-rutaAplicacion="administracionCatalogos" data-opcion="inactivarSubCatalogo">' .
	   	    '<input type="hidden" name="idCatalogoPadre" value="' . $idCatalogoPadre . '" >' .
	   	    '<input type="hidden" name="idCatalogoHijo" value="' . $idCatalogoHijo. '" >' .
	   	    '<input type="hidden" name="idItemPadre" value="' . $idItemPadre . '" >' .
	   	    '<input type="hidden" name="nivel" value="' . $nivel . '" >' .
	   	    '<input type="hidden" name="idSubitemCatalogoPadre" value="'.$idSubitemCatalogoPadre.'" >'.
	   	    '<button type="submit" class="icono"></button>' .
	   	    '</form>' .
	   	    '</td>' .
	   	    '</tr>';
	}
	
	public function buscarCatalogoPorIdCatalogoPadreIdCatalogoHijoIdItemPadre($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nivel, $idSubitemCatalogoPadre, $estadoCatalogo= 'activo'){
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="SELECT
                    	*
                    FROM
                    	g_administracion_catalogos.subitems_catalogo
                    WHERE
                    	id_catalogo_padre  = $idCatalogoPadre
                        and id_catalogo_hijo = $idCatalogoHijo
                    	and id_item_padre = $idItemPadre
                        and nivel = $nivel
                        and estado_catalogo = '$estadoCatalogo'
                        and id_subitem_catalogo_padre $condicion $idSubitemCatalogoPadre;";

	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function buscarItemPorIdCatalogoPadreIdCatalogoHijoIdItemPadre($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $idItemHijo, $nivel, $idSubitemCatalogoPadre, $estadoCatalogo= 'activo', $estadoItem = 'activo'){
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="SELECT
                    	*
                    FROM
                    	g_administracion_catalogos.subitems_catalogo
                    WHERE
                    	id_catalogo_padre  = $idCatalogoPadre
                        and id_catalogo_hijo = $idCatalogoHijo
                    	and id_item_padre = $idItemPadre
                        and id_item_hijo ".$idItemHijo."
                        and nivel = $nivel
                        and id_subitem_catalogo_padre $condicion $idSubitemCatalogoPadre
                        and estado_catalogo = '$estadoCatalogo'
                        and estado_item ".$estadoItem.";";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function inactivarSubCatalogo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nivel, $idSubitemCatalogoPadre, $identificador, $estadoCatalogo= 'inactivo'){
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="UPDATE 
                        g_administracion_catalogos.subitems_catalogo 
                    SET 
                        estado_catalogo = '$estadoCatalogo',
                        identificador_modificacion = '$identificador',
                        fecha_modificacion = 'now()'
                    WHERE 
                        id_catalogo_padre  = $idCatalogoPadre
                        and id_catalogo_hijo = $idCatalogoHijo
                    	and id_item_padre = $idItemPadre
                        and id_subitem_catalogo_padre $condicion $idSubitemCatalogoPadre
                        and nivel = $nivel;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function inactivarSubItem($conexion, $idSubitemCatalogoPadre, $identificador, $estadoItem= 'inactivo'){
	    
	    $consulta="UPDATE
                        g_administracion_catalogos.subitems_catalogo
                    SET
                        estado_item = '$estadoItem',
                        identificador_modificacion = '$identificador',
                        fecha_modificacion = 'now()'
                    WHERE
                        id_subitem_catalogo = $idSubitemCatalogoPadre;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function actualizarItemHijo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $idItemHijo, $nivel, $idSubitemCatalogoPadre, $estadoCatalogo= 'activo', $estadoItem = 'activo') {
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="UPDATE
                        g_administracion_catalogos.subitems_catalogo
                    SET
                        id_item_hijo = $idItemHijo,
                        estado_item = '$estadoItem'
                    WHERE
                        id_catalogo_padre  = $idCatalogoPadre
                        and id_catalogo_hijo = $idCatalogoHijo
                    	and id_item_padre = $idItemPadre
                        and estado_catalogo = '$estadoCatalogo'
                        and id_subitem_catalogo_padre $condicion $idSubitemCatalogoPadre
                        and nivel = $nivel
                    returning id_subitem_catalogo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	    
	}
	
	public function guardarItemHijo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $idItemHijo, $identificador, $nivel, $idSubitemCatalogoPadre, $estadoCatalogo = 'activo', $estadoItem = 'activo') {
	    
	    $consulta="INSERT INTO
                            g_administracion_catalogos.subitems_catalogo(id_catalogo_padre, id_catalogo_hijo, id_item_padre, estado_catalogo, identificador_creacion, fecha_creacion, nivel, id_item_hijo, estado_item, id_subitem_catalogo_padre)
                    VALUES ($idCatalogoPadre, $idCatalogoHijo, $idItemPadre, '$estadoCatalogo', '$identificador', 'now()', $nivel, $idItemHijo, '$estadoItem', $idSubitemCatalogoPadre) returning id_subitem_catalogo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function imprimirLineaItemoHijo($idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $idItemHijo, $nombreItemHijo, $nivel, $idExclusionCatalogo, $idSubitemCatalogoPadre, $etapaProceso){
	    
	    ++$nivel;
	    
	    return '<tr id="R' . $idCatalogoPadre .$idCatalogoHijo.$idItemPadre.$idItemHijo. '">' .
	   	    '<td width="100%">'.$nombreItemHijo.'</td>' .
	   	    '<td>' .
	   	    '<form class="abrir" data-rutaAplicacion="administracionCatalogos" data-opcion="abrirItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
	   	    '<input type="hidden" name="idCatalogoPadre" value="' . $idCatalogoHijo . '" >' .
	   	    '<input type="hidden" name="idCatalogoHijo" value="" >' .
	   	    '<input type="hidden" name="idItemPadre" value="' . $idItemHijo . '" >' .
	   	    '<input type="hidden" name="nivel" value="' . $nivel . '" >' .
	   	    '<input type="hidden" name="idExclusionCatalogo" value="'.$idExclusionCatalogo.'" >'.
	   	    '<input type="hidden" name="idSubitemCatalogoPadre" value="'.$idSubitemCatalogoPadre.'" >'.
	   	    '<input type="hidden" name="etapaProceso" value='.serialize($etapaProceso).' >'.
	   	    '<input type="hidden" name="tipoProceso" value="carga" >'.
	   	    '<button class="icono" type="submit" ></button>' .
	   	    '</form>' .
	   	    '</td>' .
	   	    '<td>' .
	   	    '<form class="borrar" data-rutaAplicacion="administracionCatalogos" data-opcion="inactivarSubItem">' .
	   	    '<input type="hidden" name="idCatalogoPadre" value="' . $idCatalogoPadre . '" >' .
	   	    '<input type="hidden" name="idCatalogoHijo" value="' . $idCatalogoHijo. '" >' .
	   	    '<input type="hidden" name="idItemPadre" value="' . $idItemPadre . '" >' .
	   	    '<input type="hidden" name="idItemHijo" value="' . $idItemHijo . '" >' .
	   	    '<input type="hidden" name="nivel" value="' . $nivel . '" >' .
	   	    '<input type="hidden" name="idSubitemCatalogoPadre" value="'.$idSubitemCatalogoPadre.'" >'.
	   	    '<button type="submit" class="icono"></button>' .
	   	    '</form>' .
	   	    '</td>' .
	   	    '</tr>';
	}
	
	public function listarItemAsignadosPorCatalogo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nivel, $idSubitemCatalogoPadre, $estadoCatalogo= 'activo', $estadoItem = 'activo' ){
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="SELECT
                    	id_subitem_catalogo, id_catalogo_padre, id_catalogo_hijo, id_item_padre, id_item_hijo, nombre
                    FROM
                    	g_administracion_catalogos.subitems_catalogo sc
                        INNER JOIN g_administracion_catalogos.items_catalogo ic ON ic.id_item = sc.id_item_hijo
                    WHERE
                    	id_catalogo_padre  = $idCatalogoPadre
                        and id_catalogo_hijo = $idCatalogoHijo
                    	and id_item_padre = $idItemPadre
                        and estado_catalogo = '$estadoCatalogo'
                        and estado_item = '$estadoItem'
                        and id_subitem_catalogo_padre $condicion $idSubitemCatalogoPadre
                        and nivel = $nivel
                        and ic.estado = 1;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerSubCatalogosPorIdentificador($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nivel, $idSubitemCatalogoPadre, $estadoItem = 'activo'){
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="SELECT
                        *
                    FROM
                        g_administracion_catalogos.subitems_catalogo
                    WHERE
                        id_catalogo_padre  = $idCatalogoPadre
                        and id_catalogo_hijo = $idCatalogoHijo
                    	and id_item_padre = $idItemPadre
                        and estado_item = '$estadoItem'
                        and id_subitem_catalogo_padre $condicion $idSubitemCatalogoPadre
                        and nivel = $nivel;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerSubItemsPorIdentificador($conexion, $idSubitemCatalogoPadre, $estadoCatalogo= 'activo'){
	    
	    if($idSubitemCatalogoPadre == 'null'){
	        $condicion = ' is ';
	    }else{
	        $condicion = ' = ';
	    }
	    
	    $consulta="SELECT
                        *
                    FROM
                        g_administracion_catalogos.subitems_catalogo
                    WHERE
                        id_subitem_catalogo_padre $condicion $idSubitemCatalogoPadre
                        and estado_catalogo = '$estadoCatalogo' ;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerSubcatalogosPorCatalogoPadreCatalogoHijoItemPadre($conexion, $codigoCatalogoPadre, $codigoCatalogoHijo, $idItemPadre) {
	    
	    $consulta="SELECT 
                    	ic.id_item,ic.nombre
                    FROM 
                    	g_administracion_catalogos.subitems_catalogo sc
                    	INNER JOIN g_administracion_catalogos.catalogos_negocio cn ON sc.id_catalogo_padre = cn.id_catalogo_negocios
                    	INNER JOIN g_administracion_catalogos.catalogos_negocio cn1 ON sc.id_catalogo_hijo = cn1.id_catalogo_negocios
                    	INNER JOIN g_administracion_catalogos.items_catalogo ic ON sc.id_item_hijo = ic.id_item
                    WHERE 
                    	cn.codigo = '$codigoCatalogoPadre'
                    	and cn1.codigo = '$codigoCatalogoHijo'
                    	and sc.id_item_padre = $idItemPadre
                        and ic.estado = 1;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
}