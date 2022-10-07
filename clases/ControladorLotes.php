<?php
class ControladorLotes{
	
	public function listarRegistros($conexion, $idProveedor, $nProveedor, $fecha,$estado, $operador,$producto){
		$idProveedor  = $idProveedor!="" ? "'" . $idProveedor . "'" : "NULL";
		$nProveedor = $nProveedor!="" ? "'%" . $nProveedor . "%'" : "NULL";		
		$fecha = $fecha!="" ? "'" . $fecha . "'"  : "NULL";
		$estado = $estado!="" ? "'" . $estado. "'"  : "NULL";
		$producto= $producto!="" ? "'" . $producto. "'"  : "NULL";
	
		
		if(($idProveedor=="NULL") && ($nProveedor=="NULL") && ($fecha=="NULL")){
			$busqueda = "and re.fecha_ingreso >= current_date and re.fecha_ingreso < current_date+1
			and re.identificador_operador='".$operador."'";
		}
		
		$consulta = "SELECT
						re.id_registro, re.codigo_registro,re.fecha_ingreso,
						rtrim(  (case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end )||' - '|| re.identificador_proveedor||'.'|| area_proveedor ,'.') as nombre_proveedor, 
						re.nombre_producto, re.cantidad, (case when re.estado='1' then 'Disponible' else 'Utilizado' end ) estado
						FROM
						g_trazabilidad.registro re, g_operadores.operadores op
					WHERE
						re.identificador_proveedor=op.identificador and
						($idProveedor is NULL or re.identificador_proveedor = $idProveedor) and 
						($nProveedor is NULL or (case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end) ilike $nProveedor) and
						($fecha is NULL or to_char(re.fecha_ingreso,'dd/mm/yyyy') = $fecha ) and
						($estado is NULL or re.estado = $estado ) and
						($producto is NULL or re.id_producto = $producto) and
						re.identificador_operador = '$operador' ".$busqueda." and
						(re.estado=1 or re.estado=2) 
				order by 
						1, 5 asc ";
		
		$res = $conexion->ejecutarConsulta($consulta);		
		
		return $res;
	}
	
	public  function ObtenerRegistro($conexion,$registro){
		$consulta="SELECT
						re.id_registro, re.identificador_operador, re.codigo_registro, re.fecha_ingreso, re.id_producto, pr.nombre_comun nombre_producto, re.identificador_proveedor, 
						case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_proveedor, re.id_variedad, 
						(case when re.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = re.id_variedad ) else 'Sin variedad' end ) variedad, 
						re.cantidad, re.estado, re.id_unidad, re.nombre_unidad,area_proveedor,id_area_proveedor
					FROM
						g_trazabilidad.registro re, g_catalogos.productos pr, g_operadores.operadores op --, g_catalogos.variedades va
					WHERE
						pr.id_producto = re.id_producto
						and op.identificador = re.identificador_proveedor
						--and va.id_variedad = re.id_variedad
						and id_registro = '$registro'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function obtenerMax($conexion){	
		
		$res = $conexion->ejecutarConsulta("SELECT 
												max(codigo_registro) 
											FROM 
											g_trazabilidad.registro");
		return $res;
	}
		
	
	public function listarProveedoresPorProducto($conexion,$idProducto,$idOperador){
		
		$consulta="SELECT  
						distinct
						pr.codigo_proveedor identificador_proveedor, 
						case when trim( leading ' ' from op.razon_social) = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_proveedor
						
					FROM 
						 g_operadores.proveedores pr, 	
						 g_operadores.operadores op,
						 g_operadores.operaciones ope
						 
					WHERE 		
						pr.identificador_operador='$idOperador'
						and pr.id_producto='$idProducto'
						and ope.estado in ('registrado','registradoObservacion') 						
						and ope.identificador_operador = pr.codigo_proveedor 
						and op.identificador = pr.codigo_proveedor
						order by nombre_proveedor;";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;

	}
	
	public function obtenerCamposCaracteristicas($conexion,$producto,$formulario){
	    $consulta="SELECT 
                        id_elemento, etiqueta
                  FROM 
                        g_administracion_caracteristicas_productos.elemento
                  WHERE
                        id_producto in ($producto)
                        and estado='1'
                        and id_formulario='$formulario' ORDER BY 2;";	    
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}

		
	public function listarProductosTrazabilidad($conexion,$operador){
			
		$consulta="SELECT 
						id_tipo_operacion, codigo, id_area
			  		FROM
						g_catalogos.tipos_operacion
			  		where 
						id_area='SV' and
			  			(codigo='ACO' or codigo='EXP')
						ORDER BY 1";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		$cont = 0;
		$val1="";
		$val2="";
		
		while ($filas = pg_fetch_assoc($res)){
			if($cont==0){
				$val1=$filas["id_tipo_operacion"];
			}
			else{
				$val2=$filas["id_tipo_operacion"];
			}
			$cont=$cont+1;
		}
		
		$consulta="SELECT
						count (distinct op.id_tipo_operacion) total, pr.id_producto, pr.nombre_comun, max(op.id_tipo_operacion) tipo
					FROM
						g_catalogos.productos pr,
						g_operadores.operaciones op
					WHERE
						pr.trazabilidad = 'SI'
						and pr.id_producto = op.id_producto
						and op.identificador_operador='$operador'
						and op.id_tipo_operacion in($val1, $val2) 
					GROUP BY 2,3";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerCodigoTipoOperacion($conexion,$area,$tipo){
		$consulta="SELECT
						id_tipo_operacion, codigo, id_area
			  		FROM
						g_catalogos.tipos_operacion
			  		where
						id_area='$area' and
			  			codigo='$tipo'
						ORDER BY 1";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
		
	
	public function listarVariedadesProductos($conexion,$producto){	
				
		$res = $conexion->ejecutarConsulta("SELECT
												pva.id_variedad, va.nombre
											FROM 
												g_catalogos.productos_variedades pva,
												g_catalogos.variedades va,
												g_catalogos.productos_vegetales pve
											WHERE
												pva.id_producto = $producto and
												pva.id_variedad = va.id_variedad and
												pve.id_producto = pva.id_producto");
		return $res;
	}
	
	public function autogenerarNumeroRegistro($conexion, $identificadorOperador){
		
		$consulta="SELECT
						MAX(codigo_registro)::numeric + 1 as numero
					FROM
						g_trazabilidad.registro r
					WHERE
						r.identificador_operador='$identificadorOperador' and
						r.estado in(1,2);";		

		$res = $conexion->ejecutarConsulta($consulta);
	
		if(pg_fetch_result($res, 0, 'numero') == ''){
			$res = 1;
		}else{
				$res = pg_fetch_result($res, 0, 'numero');
		}
	
		return $res;
	}
	
	public function guardarRegistroNuevo($conexion,$codigo,$operador,$producto,$nproducto,$proveedor,$nproveedor,$variedad,$nvariedad,$cantidad,$area,$nombreArea,$sitio,$NombreSitio,$idUnidad,$nUnidad,$areaProveedor,$idAreaProveedor){
		$fecha_registro = date('d-m-Y H:i:s');
		if($idAreaProveedor=='')
			$idAreaProveedor='NULL';
		if($variedad=='')
			$variedad='NULL';
		$consulta="INSERT INTO 
							g_trazabilidad.registro(codigo_registro,identificador_operador, fecha_ingreso, id_producto, nombre_producto, identificador_proveedor, nombre_proveedor, cantidad, estado, id_unidad, nombre_unidad, area_proveedor, id_area_proveedor )
							VALUES ('$codigo','$operador','$fecha_registro','$producto','$nproducto','$proveedor','$nproveedor','$cantidad',1,'$idUnidad','$nUnidad','$areaProveedor',$idAreaProveedor) RETURNING id_registro";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}	
	
	public function guardarRegistroDivision($conexion,$operador,$codigo,$producto,$nproducto,$proveedor,$nproveedor,$variedad,$nvariedad,$cantidad,$fecha_registro,$idUnidad,$nUnidad,$areaProveedor='',$idAreaProveedor){
		//$fecha_registro = date('d-m-Y H:i:s');
	    $idAreaProveedor  = $idAreaProveedor!="" ? "'" . $idAreaProveedor . "'" : "NULL";
	    
		$consulta="INSERT INTO
						g_trazabilidad.registro(codigo_registro,identificador_operador, fecha_ingreso, id_producto, nombre_producto, identificador_proveedor, nombre_proveedor, cantidad, estado, id_unidad, nombre_unidad, area_proveedor, id_area_proveedor )
					VALUES ('$operador','$codigo','$fecha_registro','$producto','$nproducto','$proveedor','$nproveedor','$cantidad',1,'$idUnidad','$nUnidad','$areaProveedor',$idAreaProveedor) RETURNING id_registro;";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarRegistro($conexion,$registro,$cantidad,$idUnidad,$nUnidad){
		$consulta="UPDATE 
						g_trazabilidad.registro
			   	   SET  
						cantidad='".$cantidad."', id_unidad='".$idUnidad."', nombre_unidad='".$nUnidad."'
	   			   WHERE 
			   			id_registro='".$registro."'";
		
		$res = $conexion->ejecutarConsulta($consulta);
	
	}
	
	public function eliminarRegistro($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												max(codigo_registro)
											FROM
											g_trazabilidad.registro");
		return $res;
	}
	
	public function listarProveedoresRegistrados($conexion,$id){
		$consulta="UPDATE 
						g_trazabilidad.registro
					   SET 
						estado=9
					 WHERE 
						id_registro=$id;";

		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function listarRegistrosProveedor($conexion,$operador,$idProveedor,$producto){
		
		$consulta="SELECT
						r.id_registro,r.codigo_registro,r.fecha_ingreso, case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_proveedor, r.id_variedad,
						(case when r.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = r.id_variedad ) else 'Sin variedad' end ) variedad,r.cantidad 
					FROM
						g_trazabilidad.registro r, g_operadores.operadores op  
					WHERE
						r.identificador_proveedor = op.identificador						
						and r.identificador_proveedor='$idProveedor' and
						r.identificador_operador='$operador' and
						id_producto='$producto'	and 
						r.estado='1'
					order by r.id_registro";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function pivotearColumnas($conexion,$producto,$formulario){
	    $consulta="SELECT 
                	public.pivotearColumnas('tmp_c', 'select * from g_trazabilidad.vista_caracteristicas where id_producto in($producto) and id_formulario=$formulario',
                	array['id_registro'], array['etiqueta'], '#.nombre',null);";	    
	   
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarRegistrosProveedorArea($conexion,$operador,$idProveedor,$producto,$area){
		
		$consulta="SELECT
						r.id_registro,r.codigo_registro,r.fecha_ingreso, 
                        case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_proveedor,
						r.cantidad
					FROM
						g_trazabilidad.registro r, g_operadores.operadores op
					WHERE
						r.identificador_proveedor = op.identificador
						
						and r.identificador_proveedor='$idProveedor' and
						r.identificador_operador='$operador' and
						id_producto='$producto'	and
						area_proveedor='$area'	and
						r.estado='1'
					ORDER BY 
						r.id_registro;";	
	
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function listarRegistrosProveedorAreaMasCaracteristicas($conexion,$operador,$idProveedor,$producto,$area){
	    $consulta="SELECT
                    	r.id_registro,r.codigo_registro,r.fecha_ingreso,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_proveedor,
                    	r.cantidad , tmp.*
                    FROM
                    	tmp_c tmp RIGHT OUTER JOIN g_trazabilidad.registro r on tmp.id_registro = r.id_registro
                    	INNER JOIN g_operadores.operadores o on o.identificador = r.identificador_proveedor
                    WHERE
                    	r.identificador_proveedor='$idProveedor'
                    	and r.identificador_operador='$operador'
                    	and r.id_producto='$producto'
                    	and r.area_proveedor='$area'
                    	and r.estado='1'
                    ORDER BY r.id_registro;";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	
	public function listarTipoLote($conexion, $producto){
				
		$consulta="SELECT 
						ti.id_tipo_lote, concat(ti.nombre,' - ',ti.descripcion) descripcion
					FROM 
						g_catalogos.productos pr,
						g_catalogos.tipo_lote ti,
						g_catalogos.producto_tipo_lote pti
					where
						pti.id_tipo_lote=ti.id_tipo_lote and
						pti.id_producto=pr.id_producto and
						pti.id_producto=$producto";		
		
		
		$res=$conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
			
	public function autogenerarNumeroLote($conexion, $identificadorOperador,$anioActual,$producto){
		$consulta="SELECT
						MAX(serie_lote)::numeric + 1 as numero
					FROM
						g_trazabilidad.lotes r
					WHERE
						r.identificador_operador='$identificadorOperador' and
						r.id_producto='$producto' and
						r.anio='$anioActual';";
			
		$res = $conexion->ejecutarConsulta($consulta);
	
		if(pg_fetch_result($res, 0, 'numero') == '')
			$res = 1;
		else
			$res = pg_fetch_result($res, 0, 'numero');
	
		return $res;
	}
	
	public function obtenerMaxLote($conexion,$anio,$operador){
		$consulta="SELECT
						max(id_lote)
					FROM
						g_trazabilidad.lotes
					WHERE
						anio=$anio and 
						identificador_operador='$operador'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerMaxAño($conexion, $identificadorOperador){
		$consulta="SELECT 
						MAX(anio) anio
					FROM 
						g_trazabilidad.lotes ";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;		
	}
	
	public function guardarLote($conexion,$operador,$anio,$serie_lote,$nLote,$fecha,$codigoLote,$cantidad,$idTipoLote,$tipo,$idPais,$nPais,$tipoProdu,$descri,$idProducto,$nProducto, $areaOperador, $areaProveedor,$sitioProveedor, $proveedor=""){
		$fecha_registro = date('d-m-Y H:i:s');		
		$idTipoLote= $idTipoLote !="" ? "'" . $idTipoLote . "'" : "NULL";
		if($idVariedad==''){
			$idVariedad='NULL';
			$nVariedad='';
		}		
		
		if($sitioProveedor=='')
			$sitioProveedor='NULL';
		
		$anio = date("Y");
		/*$consulta="INSERT INTO 
						g_trazabilidad.lotes(
				            identificador_operador, anio, serie_lote, numero_lote, fecha_conformacion, codigo_lote, cantidad, id_variedad, variedad, id_tipo_lote, tipo, id_localizacion, pais, tipo_producto, descripcion, id_producto, estado, producto)
						VALUES ('$operador','$anio','$serie_lote','$nLote','$fecha_registro','$codigoLote','$cantidad',$idVariedad,'$nVariedad',$idTipoLote,'$tipo','$idPais','$nPais','$tipoProdu','$descri','$idProducto','1','$nProducto') RETURNING id_lote";
		*/
		$consulta="INSERT INTO
						g_trazabilidad.lotes(
							identificador_operador, anio, serie_lote, numero_lote, fecha_conformacion, codigo_lote, cantidad, id_tipo_lote, tipo, id_localizacion, pais, tipo_producto, descripcion, id_producto, estado, producto, area_operador, area_proveedor, id_area_proveedor, identificador_proveedor)
					VALUES ('$operador','$anio','$serie_lote','$nLote','$fecha_registro','$codigoLote','$cantidad',$idTipoLote,'$tipo','$idPais','$nPais','$tipoProdu','$descri','$idProducto','1','$nProducto','$areaOperador','$areaProveedor',$sitioProveedor, '$proveedor') RETURNING id_lote;";
		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	
	}
	
	public function guardarDetalleLote($conexion,$idLote,$idRegistro){
		$consulta="INSERT INTO 
						g_trazabilidad.lotes_registro(id_lote, id_registro)
   					 VALUES ($idLote, $idRegistro);";		
		
		$res = $conexion->ejecutarConsulta($consulta);		
		
	}
	
	public function estadoRegistro($conexion,$estado,$idRegistro){
		
		$consulta="UPDATE 
						g_trazabilidad.registro
				   SET 
						estado='$estado'
				   WHERE 
						id_registro='$idRegistro';";
		
		$res = $conexion->ejecutarConsulta($consulta);
	}
	
	
	public function listarLotes($conexion, $loteNro, $codigoLote, $fecha, $operador, $producto){
		
		$loteNro  = $loteNro!="" ? "'" . $loteNro . "'" : "NULL";
		$codigoLote = $codigoLote!="" ? "'%" . $codigoLote . "%'" : "NULL";
		$fecha = $fecha!="" ? "'" . $fecha . "'"  : "NULL";
		$producto= $producto!="" ? "'" . $producto. "'"  : "NULL";
	
	
		if(($loteNro=="NULL") && ($codigoLote=="NULL") && ($fecha=="NULL")){
			$busqueda = "and l.fecha_conformacion >= current_date and l.fecha_conformacion < current_date+1
			and l.identificador_operador='".$operador."'";
		}
	
		$consulta = "SELECT
						l.id_lote,l.numero_lote,l.codigo_lote,l.fecha_conformacion,l.producto,
						(case when l.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = l.id_variedad ) else 'Sin variedad' end ) variedad, l.cantidad  
					FROM
						g_trazabilidad.lotes l
					WHERE						
						($loteNro is NULL or l.numero_lote = $loteNro)
						and ($codigoLote is NULL or l.codigo_lote ilike $codigoLote)
						and ($fecha is NULL or to_char(l.fecha_conformacion,'dd/mm/yyyy') = $fecha )
						and ($producto is NULL or l.id_producto = $producto)
						and l.identificador_operador = '$operador' ".$busqueda." 
					ORDER BY
						l.producto,l.fecha_conformacion";
		
		$res = $conexion->ejecutarConsulta($consulta);
	
		return $res;
	}
	
	
	public  function ObtenerLote($conexion,$lote){
		$consulta="SELECT
						l.id_lote, l.identificador_operador, l.anio, l.serie_lote, l.numero_lote, l.fecha_conformacion, l.codigo_lote, l.cantidad, l.id_variedad,
 						(case when l.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = l.id_variedad ) else 'Sin variedad' end ) variedad, 
						l.id_tipo_lote,l.tipo, l.id_localizacion, lo.nombre pais, l.tipo_producto, l.descripcion, 
					    l.id_producto, l.estado, pr.nombre_comun producto,area_operador,area_proveedor,id_area_proveedor, identificador_proveedor
					FROM
						g_trazabilidad.lotes l,
 						g_catalogos.localizacion lo, g_catalogos.productos pr
					WHERE						
					    l.id_localizacion = lo.id_localizacion
					    and l.id_producto = pr.id_producto
						and l.id_lote = '$lote'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function ObtenerRegistrosConformados($conexion,$idLote,$operador){
		$consulta="SELECT 
						r.id_registro, r.codigo_registro,
						case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_proveedor,
						r.id_variedad, 
						(case when r.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = r.id_variedad ) else 'Sin variedad' end ) variedad, r.cantidad
			  		FROM 
						g_trazabilidad.registro r,g_trazabilidad.lotes_registro l, g_operadores.operadores op--, g_catalogos.variedades va
				  	WHERE
						r.id_registro = l.id_registro
						and r.identificador_proveedor = op.identificador
						--and r.id_variedad = va.id_variedad
						and l.id_lote='$idLote'
						and r.identificador_operador='$operador' 
					order by r.codigo_registro; ";		
		
		$consulta="SELECT
						r.id_registro, r.codigo_registro,
						case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_proveedor, r.cantidad, identificador_proveedor, id_area_proveedor
			  		FROM
						g_trazabilidad.registro r,g_trazabilidad.lotes_registro l, g_operadores.operadores op
				  	WHERE
						r.id_registro = l.id_registro
						and r.identificador_proveedor = op.identificador						
						and l.id_lote='$idLote'
						and r.identificador_operador='$operador'
					order by r.codigo_registro; ";
		
	
		$res = $conexion->ejecutarConsulta($consulta);		
		return $res;
	}
	
	public function ObtenerRegistrosConformadosMasCaracteristicas($conexion,$idLote,$operador){
	    $consulta="SELECT 
                    	r.id_registro, r.codigo_registro,
                    	case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_proveedor,	
                    	r.cantidad, identificador_proveedor, id_area_proveedor, tmp.* 
                    FROM 
                    	tmp_c tmp RIGHT OUTER JOIN g_trazabilidad.registro r on tmp.id_registro = r.id_registro 
                    	INNER JOIN g_operadores.operadores o on o.identificador = r.identificador_proveedor 
                    	INNER JOIN g_trazabilidad.lotes_registro l on r.id_registro = l.id_registro
                    WHERE	
                    	l.id_lote='$idLote'
                    	and r.identificador_operador='$operador' 
                    ORDER BY r.codigo_registro;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}

	public function actualizarLote($conexion,$idLote,$codigoLote,$cantidad,$idTipoLote,$tipo,$idPais,$nPais,$tipoProdu,$descri){
		$idTipoLote= $idTipoLote !="" ? "'" . $idTipoLote . "'" : "NULL";
		$consulta="UPDATE 
						g_trazabilidad.lotes
				   SET 
					   codigo_lote='$codigoLote', cantidad='$cantidad',  
				       id_tipo_lote=$idTipoLote, tipo='$tipo', id_localizacion=$idPais, pais='$nPais', 
				       tipo_producto='$tipoProdu', descripcion='$descri'
				 WHERE id_lote=$idLote";		
		
		$res = $conexion->ejecutarConsulta($consulta);
	}
	
	public function eliminarDetalleLote($conexion,$idLote){
		$consulta="DELETE FROM 
						g_trazabilidad.lotes_registro
				   WHERE 
						id_lote=$idLote";
				
		$res = $conexion->ejecutarConsulta($consulta);
	
	}
	
	public function obtenerLotes($conexion,$operador){
		$consulta="SELECT
						lo.id_lote, lo.identificador_operador, lo.anio, lo.serie_lote, lo.numero_lote, lo.fecha_conformacion, lo.codigo_lote, lo.cantidad, lo.id_variedad, 
						(case when lo.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = lo.id_variedad ) else 'Sin variedad' end ) variedad, 
						lo.id_tipo_lote, (select concat(nombre,' - ',descripcion) as tipo from g_catalogos.tipo_lote where id_tipo_lote= lo.id_tipo_lote),
						lo.id_localizacion, pa.nombre pais, lo.tipo_producto, lo.descripcion, lo.id_producto, lo.estado, 
						pr.nombre_comun producto, case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_exportador 
			  		FROM
						g_trazabilidad.lotes lo, g_operadores.operadores op, g_catalogos.localizacion pa, g_catalogos.productos pr
					WHERE 
						op.identificador = lo.identificador_operador
						and lo.id_localizacion = pa.id_localizacion
						and lo.id_producto = pr.id_producto
						and lo.identificador_operador='$operador' and lo.estado=1
					ORDER BY 
						lo.producto, lo.numero_lote asc";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerProductosLotes($conexion,$operador){
	    $consulta="SELECT DISTINCT
                        id_producto
                    FROM 
                        g_trazabilidad.lotes
                    WHERE
                        identificador_operador='$operador' and estado=1";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerLoteID($conexion,$id){
		$consulta="SELECT 
					   id_lote, identificador_operador, anio, serie_lote, numero_lote, fecha_conformacion, codigo_lote, cantidad, id_variedad, variedad, id_tipo_lote, tipo, id_localizacion, pais, tipo_producto, descripcion, id_producto, estado, producto
			  		FROM 
						g_trazabilidad.lotes
				  WHERE
						id_lote=$id";
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerRegistroUnicoDeLote($conexion, $idLote){
	    $consulta="SELECT
                    	lr.id_registro
                    FROM
                    	g_trazabilidad.lotes_registro lr
                    WHERE
                    	lr.id_lote='$idLote'
                        limit 1	;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function guardarLoteEtiquetado($conexion,$idLote,$numeroLote,$codigoLote,$fechaEtiquetado,$cantidad,$peso,$numeroEtiquetas,$operador,$ruta,$idEtiqueta){
		$fecha = date('d-m-Y H:i:s');
		$hora = date('H:i:s');		
		$fechaEtiquetado=$fechaEtiquetado." ".$hora;
		$consulta="INSERT INTO 
						g_trazabilidad.lotes_etiquetado(
						id_lote, numero_lote, codigo_lote, fecha_etiquetado, cantidad, peso, numero_etiquetas, identificador_operador,ruta,id_etiqueta,fecha_registro)
					VALUES 
						($idLote, '$numeroLote', '$codigoLote','$fechaEtiquetado', $cantidad, $peso, $numeroEtiquetas,'$operador','$ruta','$idEtiqueta','$fecha') returning id_etiquetado";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	
	}
	
	public function estadoLote($conexion,$idLote){
		
		$consulta="UPDATE 
						g_trazabilidad.lotes
				   SET 
						estado=2
				   WHERE 
						id_lote=$idLote";
		
		$res = $conexion->ejecutarConsulta($consulta);
	
	}
	
	public function listarLotesEtiquetados($conexion, $loteNro, $codigoLote, $fechaConformacion, $fechaEtiquetado, $operador,$producto){
		
		$loteNro  = $loteNro!="" ? "'" . $loteNro . "'" : "NULL";
		$codigoLote = $codigoLote!="" ? "'%" . $codigoLote . "%'" : "NULL";
		$fechaConformacion = $fechaConformacion!="" ? "'" . $fechaConformacion . "'"  : "NULL";
		$fechaEtiquetado = $fechaEtiquetado!="" ? "'" . $fechaEtiquetado . "'"  : "NULL";
		$producto = $producto!="" ? "'" . $producto. "'"  : "NULL";
	
	
		if(($loteNro=="NULL") && ($codigoLote=="NULL") && ($fechaConformacion=="NULL") && ($fechaEtiquetado=="NULL")){
			
			$busqueda = "and e.fecha_etiquetado >= current_date 
						 and e.fecha_etiquetado < current_date+1
						 and e.identificador_operador='".$operador."'";
			
		}
	
		$consulta = "SELECT
						l.id_lote,l.numero_lote,l.codigo_lote,l.fecha_conformacion,e.fecha_etiquetado,
						(case when l.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = l.id_variedad ) else 'Sin variedad' end ) variedad, l.cantidad
					FROM
						g_trazabilidad.lotes l, g_trazabilidad.lotes_etiquetado e
					WHERE
						($producto is NULL or l.id_producto = $producto)
						and ($loteNro is NULL or l.numero_lote = $loteNro)
						and ($codigoLote is NULL or l.codigo_lote ilike $codigoLote)
						and ($fechaConformacion is NULL or to_char(l.fecha_conformacion,'dd/mm/yyyy') = $fechaConformacion )
						and ($fechaEtiquetado is NULL or to_char(e.fecha_etiquetado,'dd/mm/yyyy') = $fechaEtiquetado )						
						and e.identificador_operador = '$operador' ".$busqueda." and l.id_lote = e.id_lote order by l.fecha_conformacion asc ";	
		
		$res = $conexion->ejecutarConsulta($consulta);
	
		return $res;
	}
	
	public function obtenerLoteEtiquetado($conexion,$idLote,$operador){
	
		$consulta="SELECT
						l.id_lote,l.numero_lote,l.codigo_lote,l.fecha_conformacion,
						(case when l.variedad != '' then (select va.nombre variedad  from g_catalogos.variedades va where va.id_variedad = l.id_variedad ) else 'Sin variedad' end ) variedad, l.cantidad,
						l.descripcion,e.fecha_etiquetado, e.peso, e.numero_etiquetas, 
						(case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end ) as exportador, e.ruta
					FROM
						g_trazabilidad.lotes l, g_trazabilidad.lotes_etiquetado e, g_operadores.operadores op
					WHERE
						op.identificador = e.identificador_operador 						
						and e.identificador_operador = '$operador'
						and l.id_lote = e.id_lote
						and e.id_lote=$idLote";		
		
		$res = $conexion->ejecutarConsulta($consulta);		
		return $res;
	
	}
	
	public function comprobarCodigo($conexion,$operador,$codigo,$idProducto){
		
		$consulta="SELECT 
						id_lote,codigo_lote
				  	FROM 
						g_trazabilidad.lotes
				  	WHERE 
						identificador_operador='$operador'
						and id_producto='$idProducto'
				  		and codigo_lote='$codigo'";
			
		$res = $conexion->ejecutarConsulta($consulta);
		/*
		if (pg_num_rows($res)>0)
		{
			return 1;
		} else {
			return 0;
		}
		*/		
		return $res;
	}
	
	public function listaReporteLotesConformados($conexion,$idOperador,$nOperador,$numeroLote,$codigoLote, $fechaInicio, $fechaFin, $producto, $tabla){
	                                            //($conexion,$operador,$nOperador,$numeroLote,$codigoLote, $fechaInicio, $fechaFin,$producto,'tmp_c');
					
		$idOperador  = $idOperador!="" ? "'" . $idOperador . "'" : "NULL";
		$nOperador = $nOperador!="" ? "'%" . $nOperador . "%'" : "NULL";
		$numeroLote = $numeroLote!="" ? "'" . $numeroLote . "'"  : "NULL";
		$codigoLote = $codigoLote!="" ? "'" . $codigoLote . "'"  : "NULL";
		//$producto= $producto!="" ? "'%" . $producto. "%'"  : "NULL";
		
		$producto= $producto!="" ? "'" . $producto. "'"  : "NULL";
		 		
		if ($fechaFin != "") {
			$fechaFin = str_replace ( "/", "-", $fechaFin );
			$fechaFin = strtotime ( '+1 day', strtotime ( $fechaFin ) );
			$fechaFin = date ( 'd-m-Y', $fechaFin );
			$fechaFin = "'" . $fechaFin . "'";
		}
		$fechas="and l.fecha_conformacion >= '$fechaInicio' and l.fecha_conformacion <= $fechaFin";
		if($tabla==null){
		
		    /*if($numeroLote == "NULL" && $codigoLote == "NULL" )	{
			     $fechas="and l.fecha_conformacion >= '$fechaInicio' and l.fecha_conformacion <= $fechaFin";
		    }*/
		
		$consulta="SELECT 
						   l.id_lote, l.identificador_operador,(case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end ) nombre_operador, 
						   l.numero_lote, l.codigo_lote, l.fecha_conformacion, l.cantidad, l.producto,
						   lc.nombre pais,
					       l.tipo_producto, p.nombre_comun, l.estado, (case when l.estado = 2 then 'Si' else 'No' end) etiquetado,
					       (select le.numero_etiquetas from g_trazabilidad.lotes_etiquetado le where le.id_lote = l.id_lote),
					       (SELECT rtrim(array_to_string(array_agg(distinct case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end), ', '), ', ') as nombre_proveedor from g_operadores.operadores op , g_trazabilidad.registro re, g_trazabilidad.lotes_registro lr
					       where op.identificador= re.identificador_proveedor and lr.id_registro = re.id_registro and lr.id_lote = l.id_lote)
					FROM 
						g_trazabilidad.lotes l, g_catalogos.localizacion lc, g_catalogos.productos p, g_operadores.operadores op
					WHERE
						l.identificador_operador = op.identificador and
						lc.id_localizacion = l.id_localizacion and
						p.id_producto = l.id_producto and
						($idOperador is NULL or l.identificador_operador = $idOperador) and
						($nOperador is NULL or (case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end) ilike $nOperador) and
						($numeroLote is NULL or l.numero_lote = $numeroLote) and
						($codigoLote is NULL or l.codigo_lote = $codigoLote) and
						($producto is NULL or l.id_producto = $producto)
						$fechas
					ORDER BY 4;";
		} else{
		
		$consulta="SELECT 						
                    	l.id_lote, l.identificador_operador,(case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end ) nombre_operador, 
                    	l.numero_lote, l.codigo_lote, l.fecha_conformacion, l.cantidad, l.producto, 	
                    	lc.nombre pais,
                    	l.tipo_producto, p.nombre_comun, l.estado, (case when l.estado = 2 then 'Si' else 'No' end) etiquetado,	
                    	(select le.numero_etiquetas from g_trazabilidad.lotes_etiquetado le where le.id_lote = l.id_lote),	
                    	(SELECT rtrim(array_to_string(array_agg(distinct case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end), ', '), ', ') as nombre_proveedor from g_operadores.operadores op ,
                    	g_trazabilidad.registro re, g_trazabilidad.lotes_registro lr	
                    	where op.identificador= re.identificador_proveedor and lr.id_registro = re.id_registro and lr.id_lote = l.id_lote)		
                    	, tmp.*
                    
                    FROM 		
                    	$tabla tmp RIGHT OUTER JOIN g_trazabilidad.lotes l on tmp.id_lote = l.id_lote inner join g_catalogos.localizacion lc on lc.id_localizacion = l.id_localizacion
                    	inner join g_operadores.operadores op on op.identificador = l.identificador_operador
                    	inner join g_catalogos.productos p on p.id_producto = l.id_producto
                    WHERE
                    	($idOperador is NULL or l.identificador_operador = $idOperador) and
                    	($nOperador is NULL or (case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end) ilike $nOperador) and
                    	($numeroLote is NULL or l.numero_lote = $numeroLote) and
                    	($codigoLote is NULL or l.codigo_lote = $codigoLote) and
                    	($producto is NULL or l.id_producto = $producto)
                    	$fechas
                    ORDER BY 4;";
		
		}
		
		$res = $conexion->ejecutarConsulta($consulta);		
		return $res;
	}
	
	public function proveedoresXlote($conexion,$lote, $op=null){		
				
		$consulta="select l.id_lote,
					(SELECT distinct case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end as nombre_proveedor
					from g_operadores.operadores op , g_trazabilidad.registro re, g_trazabilidad.lotes_registro lr where op.identificador= re.identificador_proveedor and lr.id_registro = re.id_registro and lr.id_lote = l.id_lote),
					(SELECT distinct r.identificador_proveedor from g_trazabilidad.lotes_registro lte, g_trazabilidad.registro r,  g_trazabilidad.lotes lo where r.id_registro= lte.id_registro and lo.id_lote=lte.id_lote and lo.id_lote=$lote )
					
					from g_trazabilidad.lotes l
					where l.id_lote=$lote";		
		
		$consulta="select 
                        lr.id_lote,
                         rtrim(array_to_string(array_agg(distinct re.identificador_proveedor ||'.' || re.area_proveedor ||'-' || case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end), ', '), ', ') as nombre_proveedor 
                    from 
                    	 g_trazabilidad.lotes_registro lr, g_operadores.operadores op , g_trazabilidad.registro re
                    where 
                         lr.id_lote=$lote
                         and lr.id_registro = re.id_registro 
                         and op.identificador = re.identificador_proveedor
                         group by 1;";	
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		
	}
	
	public function sitiosXidProductoAcopiador($conexion,$producto,$proveedor,$op="",$operador=""){
		
	    if($op==''){
	        
		$consulta="SELECT distinct						
						o.identificador || '.' || s.codigo_provincia || s.codigo ||''|| a.codigo ||''|| a.secuencial as codigo,
						a.nombre_area || ' - ' || o.identificador || '.' || s.codigo_provincia || s.codigo ||''|| a.codigo ||''|| a.secuencial as nombre_area, a.id_area
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
						and a.estado='creado'
						and o.identificador ='$proveedor'						
						and op.id_producto='$producto'
						and t.id_area='SV'						
						and op.estado in ('registrado','registradoObservacion')
						;";
	    } else{
	        
		$consulta="SELECT distinct						
                    	o.identificador || '.' || s.codigo_provincia || s.codigo ||''|| a.codigo ||''|| a.secuencial as codigo,
                    	a.nombre_area || ' - ' || o.identificador || '.' || s.codigo_provincia || s.codigo ||''|| a.codigo ||''|| a.secuencial as nombre_area, a.id_area
                    FROM 
                    	 g_operadores.operadores o
                    	, g_operadores.sitios s
                    	, g_operadores.operaciones op
                    	, g_catalogos.tipos_operacion t
                    	, g_operadores.areas a
                    	,g_operadores.productos_areas_operacion pao
                    	,g_trazabilidad.registro re
                    WHERE 
                    	o.identificador = op.identificador_operador
                    	and op.id_tipo_operacion = t.id_tipo_operacion
                    	and s.id_sitio = a.id_sitio
                    	and pao.id_area = a.id_area 
                    	and pao.id_operacion=op.id_operacion
                    	and s.estado='creado'
                    	and a.estado='creado'
                    	and o.identificador ='$proveedor'						
                    	and op.id_producto='$producto'
                    	and t.id_area='SV'						
                    	and op.estado in ('registrado','registradoObservacion')
                    	and re.id_producto = '$producto'
                    	and re.id_producto = op.id_producto
                    	and re.id_area_proveedor = a.id_area
                    	and re.identificador_operador='$operador'
                        and re.estado=1
                    	;";
	    }
	    
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerProveedorLote($conexion,$idLote){
	    $consulta="SELECT 
                            r.identificador_proveedor
                            , case when op.razon_social = '' then op.nombre_representante ||' '|| op.apellido_representante else op.razon_social end nombre_proveedor	
                    FROM
                    	   g_trazabilidad.registro r, g_trazabilidad.lotes_registro lr, g_operadores.operadores op
                    WHERE                    
                        	r.id_registro= lr.id_registro
                        	and lr.id_lote=$idLote
                        	and op.identificador = r.identificador_proveedor
                        	limit 1";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function areasXlote($conexion,$idLote){
		$consulta="SELECT 
						id_lote, area_operador, area_proveedor, nombre_area
					FROM 
					g_trazabilidad.lotes, g_operadores.areas				
				  WHERE 
				  id_lote=$idLote
				  and id_area = id_area_proveedor";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerProveedoresRegistro($conexion,$idLote){
		$consulta="SELECT
						distinct r.identificador_proveedor
					FROM
						g_trazabilidad.registro r, g_trazabilidad.lotes l, g_trazabilidad.lotes_registro lr
					WHERE
						r.id_registro = lr.id_registro
						and l.id_lote = lr.id_lote
						and l.id_lote=$idLote";
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;		
	}
	
	
	public function obtenerProductosLotesXoperador($conexion, $operador){
	    $consulta="SELECT DISTINCT                    	
                         rtrim(array_to_string(array_agg(distinct id_producto), ', '), ', ') as id_producto 
                    FROM
                    	g_trazabilidad.lotes
                    WHERE
                    	identificador_operador='$operador';";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function listarProductosTrazabilidadTodos($conexion){
	    $consulta="SELECT
						p.id_producto, p.nombre_comun || ' -> ' || sp.nombre || ' -> ' || tp.nombre nombre_comun
					FROM
						g_catalogos.productos p 
						INNER JOIN g_catalogos.subtipo_productos sp on p.id_subtipo_producto = sp.id_subtipo_producto
						INNER JOIN  g_catalogos.tipo_productos tp on sp.id_tipo_producto = tp.id_tipo_producto
					WHERE
						P.trazabilidad='SI'
					ORDER BY 
						p.nombre_comun";	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	

				
	/////////////////// parametrizacion //////////////////////
	
	
	function guardarParametros($conexion,$areas,$proveedores,$areProveedor,$producto){
		$consulta="INSERT INTO g_trazabilidad.parametros(
					areas, proveedores, areas_proveedor, id_producto)
					VALUES
					($areas,$proveedores,$areProveedor,$producto)";
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}

	function guardarOperaciones($conexion, $detalle, $bandera){
	    
	    $consulta="INSERT INTO 
                            g_administracion_caracteristicas_productos.operaciones_producto(
                            id_producto, operacion, condicion, id_tipo_operacion)
                    VALUES $detalle;";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;	    
	}
	
	function obtenerOperacionesXPorducto($conexion, $producto){
	    $consulta="SELECT
                    	op.id_operacion_producto, op.id_producto, op.operacion, op.condicion, op.id_tipo_operacion , tp.nombre
                      FROM
                    	g_administracion_caracteristicas_productos.operaciones_producto op, g_catalogos.tipos_operacion tp 
                    WHERE
                    	id_producto=$producto
                    	and tp.id_tipo_operacion = op.id_tipo_operacion;;";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;	    
	}
	
	function eliminarOperaciones($conexion, $producto){
	    $consulta="DELETE FROM 
                        g_administracion_caracteristicas_productos.operaciones_producto
                    WHERE
                        id_producto=$producto";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	function listarProductoParametrizacion($conexion,$producto){
		$producto= $producto!= "" ? $producto: 'NULL';
		$consulta="SELECT DISTINCT
					   p.id_producto, p.nombre_comun, pa.estado, tp.id_area
					FROM
    					g_trazabilidad.parametros pa,
    					g_catalogos.productos p,
    					g_catalogos.subtipo_productos dp,
    					g_catalogos.tipo_productos tp
					WHERE
    					dp.id_tipo_producto = tp.id_tipo_producto and
    					dp.id_subtipo_producto = p.id_subtipo_producto
    					and pa.id_producto = p.id_producto
    					and ($producto is null or pa.id_producto = $producto)
    					and pa.estado=1
					ORDER BY 2;";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	function obtenerParametroxIDProducto($conexion,$idProducto, $estado=1){
		$consulta="SELECT
					   id_parametro, areas, proveedores, areas_proveedor, id_producto, estado
					FROM
					   g_trazabilidad.parametros
					WHERE
					   id_producto= '$idProducto'
		               and estado='$estado'";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	function actualizarParametro($conexion,$area,$proveedores,$areaProveedor,$idParametro){
		$consulta="UPDATE
					   g_trazabilidad.parametros
					SET
					   areas=$area, proveedores=$proveedores, areas_proveedor=$areaProveedor
					WHERE
					   id_parametro= $idParametro";
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	function listarOperacionesTrazabilidad($conexion,$area,$estado){
	    $consulta="SELECT 
                	id_tipo_operacion, nombre, codigo, id_area, estado, requiere_anexo, 
                       id_flujo_operacion, trazabilidad_tipo_operacion
                  FROM 
                	g_catalogos.tipos_operacion
                  WHERE
                	id_area='$area'
                	and trazabilidad_tipo_operacion='$estado'
                  ORDER BY 2";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	function listarOperacionesTrazabilidadMasAgregadas($conexion,$area,$estado, $producto){
	    $consulta="SELECT
                    	t.id_tipo_operacion, t.nombre, t.codigo, t.id_area, t.estado, t.requiere_anexo,
                           t.id_flujo_operacion, t.trazabilidad_tipo_operacion,
                           (select id_operacion_producto from g_administracion_caracteristicas_productos.operaciones_producto p where t.id_tipo_operacion = p.id_tipo_operacion
                           and p.id_producto=$producto) id_operacion_producto
                      FROM
                    	g_catalogos.tipos_operacion t
                      WHERE
                    	t.id_area='$area'
                    	and t.trazabilidad_tipo_operacion='$estado'
                      ORDER BY 2";
	    
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	
	///////////////// administrar etiquetas //////////////////////////
	
	
	function guardarPlantilla($conexion,$producto,$plantilla,$hoja,$cantidad,$orientacion,$nombre){
		$consulta="INSERT INTO
						g_trazabilidad.plantilla(
						id_producto, plantilla, hoja, cantidad, orientacion,nombre)
					VALUES 
						($producto,'$plantilla','$hoja',$cantidad,'$orientacion','$nombre');";
		
	$res=$conexion->ejecutarConsulta($consulta);
	return $res;
	}
	
	function listarPlantillas($conexion,$producto){
		
		$producto= $producto!= "" ? $producto: 'NULL';
		
		$consulta="SELECT DISTINCT
							pl.id_producto,pr.nombre_comun, tp.id_area
					  FROM 
							g_trazabilidad.plantilla pl, 
							g_catalogos.productos pr,
							g_catalogos.subtipo_productos dp,
							g_catalogos.tipo_productos tp
					  WHERE
							dp.id_tipo_producto = tp.id_tipo_producto and
							dp.id_subtipo_producto = pr.id_subtipo_producto and
							pl.id_producto= pr.id_producto
							and ($producto is null or pl.id_producto = $producto)
							;";
		
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	
	function obtenerPlantillasXidProducto($conexion,$idProducto,$estado){
		$consulta="SELECT 
						id_plantilla, id_producto, plantilla, hoja, cantidad, orientacion, nombre, estado
				  	FROM 
						g_trazabilidad.plantilla
				 	WHERE
						id_producto=$idProducto
						and estado in ($estado)
					ORDER BY 3;";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	function obtenerPlantillasXidProductoHoja($conexion,$idProducto,$hoja,$estado){
	    $consulta="SELECT
						id_plantilla, id_producto, plantilla, hoja, cantidad, orientacion, nombre, estado
				  	FROM
						g_trazabilidad.plantilla
				 	WHERE
						id_producto=$idProducto
						and hoja = '$hoja'
						--and estado in ($estado)                    
					ORDER BY 3
                    ;";
	   
	    $res=$conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	function obtenerPlantillaxID($conexion,$idPlantilla){
		$consulta="SELECT
						id_plantilla, id_producto, plantilla, hoja, cantidad, orientacion, nombre, estado
				  FROM
						g_trazabilidad.plantilla
				  WHERE
						id_plantilla= $idPlantilla;"	;
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	
	
	public function actualizarEstadoPlantilla($conexion,$idPlantilla,$estado){
		switch ($estado){
			case 'activo':
				$estado="1";
				break;
				
			case 'inactivo':
				$estado="2";
				break;	
		}	
		
		$consulta="UPDATE
						g_trazabilidad.plantilla
					SET
						estado='$estado'
					WHERE
						id_plantilla=$idPlantilla;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarPlantilla($conexion,$idPlantilla,$plantilla,$hoja,$cantidad,$orientacion,$nombre){
		$consulta="UPDATE
							g_trazabilidad.plantilla
					   SET 
							plantilla='$plantilla', hoja='$hoja', cantidad=$cantidad, 
					        orientacion='$orientacion', nombre='$nombre'
					 WHERE 
						 id_plantilla=$idPlantilla;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarPlantillaProducto($conexion,$plantilla,$idProducto){
	    $consulta="UPDATE 
                        g_trazabilidad.plantilla
                      SET 
                        plantilla='$plantilla'
                    WHERE 
                        id_producto='$idProducto';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}	
	
	public function obtenerPlantillasRuta($conexion,$tipo){
	    $consulta="SELECT 
                    	id_plantilla_imagen,nombre, codigo, tipo, ruta
                      FROM 
                    	g_trazabilidad.plantilla_imagen
                    WHERE
                    	tipo=$tipo;";
	   
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function obtenerPlantillasRutaXcodigoYtipo($conexion,$codigo,$tipo){
	    $consulta="SELECT
                    	id_plantilla_imagen,nombre, codigo, tipo, ruta
                      FROM
                    	g_trazabilidad.plantilla_imagen
                    WHERE
                    	codigo='$codigo'
	                    and tipo=$tipo;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	
	////// OPERACIONES PRODUCTOS
	
	public function sitiosXidTipoOperacion($conexion,$producto,$operador,$tipoOperacion){	    
	        
	        
	        $consulta="SELECT
						o.identificador || '.' || s.codigo_provincia || s.codigo ||''|| a.codigo ||''|| a.secuencial as codigo,
						a.nombre_area || ' - ' || o.identificador || '.' || s.codigo_provincia || s.codigo ||''|| a.codigo ||''|| a.secuencial as nombre_area, a.id_area
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
						and a.estado='creado'
						and o.identificador ='$operador'
						and op.id_producto='$producto'
						and t.id_area='SV'
						and op.estado in ('registrado','registradoObservacion')
						and t.id_tipo_operacion='$tipoOperacion'";
						
						$res = $conexion->ejecutarConsulta($consulta);
						return $res;
	}
	
	
	public function obtenerOperacionesProducto($conexion,$idProducto){
	    $consulta="SELECT 
                    	id_operacion_producto, id_producto, operacion, id_tipo_operacion, condicion
                      FROM 
                    	g_administracion_caracteristicas_productos.vista_operacion_producto
                      WHERE
                    	id_producto=$idProducto";
	    
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function OperacionSin($conexion,$operador,$producto,$operacion){
	    $consulta="SELECT
                    	count ( distinct op.id_tipo_operacion)
                    FROM
                    	g_operadores.operaciones op
                    WHERE
                    	op.identificador_operador='$operador'
                    	and op.id_producto=$producto
                    	and op.estado in ('registrado','registradoObservacion')
                        and op.id_tipo_operacion=$operacion";
                        
                        $res = $conexion->ejecutarConsulta($consulta);
                        return $res;
	}
	
	public function OperacionAnd($conexion,$operador,$producto,$condicion){
	    $consulta="SELECT
                    	count ( distinct op.id_tipo_operacion)
                    FROM	
                    	g_operadores.operaciones op
                    WHERE		
                    	op.identificador_operador='$operador'                    	
                    	and op.id_producto=$producto
                    	and op.estado in ('registrado','registradoObservacion')
                        $condicion";
                       
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
	public function OperacionOr($conexion,$operador,$producto,$condicion){
	    $consulta="SELECT
                    	 distinct op.id_tipo_operacion
                    FROM
                    	g_operadores.operaciones op
                    WHERE
                    	op.identificador_operador='$operador'
                    	and op.id_producto=$producto
                    	and op.estado in ('registrado','registradoObservacion')
                        $condicion";
                        
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
	}
	
	public function obtenerProductoCondicion($conexion,$idProducto,$condicion=null){
	    
	    if($condicion==null){
	        $busqueda = "and condicion is null";
	    } else {
	        $busqueda = "and condicion = '$condicion'";
	    }
	    
	    $consulta="SELECT 
                    	id_operacion_producto, id_producto, operacion, condicion, id_tipo_operacion
                      FROM 
                    	g_administracion_caracteristicas_productos.operaciones_producto
                      WHERE
                    	id_producto=$idProducto 
                    	$busqueda;";
	   
    	$res = $conexion->ejecutarConsulta($consulta);
    	return $res;
	}
	
}