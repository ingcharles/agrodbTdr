<?php
class ControladorExpedienteDigital {

//-------------------------------validar de servicios de clientes----------------------------------------------
	public function listarServicioCliente($conexion,$servicio,$identificador,$provincia){
		switch ($servicio){
			case 1:
			    $consulta="SELECT 
                        	ops.fecha_creacion as fecha
                          FROM 
                        	g_operadores.operaciones ops 
                        	INNER JOIN g_catalogos.tipos_operacion op ON op.id_tipo_operacion = ops.id_tipo_operacion
                        	INNER JOIN g_operadores.productos_areas_operacion pap ON pap.id_operacion = ops.id_operacion
                        	INNER JOIN g_operadores.areas ar ON pap.id_area = ar.id_area
                        	INNER JOIN g_operadores.sitios s ON ar.id_sitio = s.id_sitio
                        	LEFT JOIN g_catalogos.productos pro ON pro.id_producto = ops.id_producto
                        	LEFT JOIN g_catalogos.subtipo_productos sub ON sub.id_subtipo_producto = pro.id_subtipo_producto
                        	
                           where
                        	ops.identificador_operador= '$identificador'
                        	and upper(s.provincia) = upper('$provincia')
                        ORDER BY 1;";
				   break;
				
			case 2: $consulta="SELECT 
									dda.fecha_creacion as fecha
				           		FROM 
				               		g_dda.destinacion_aduanera  dda
				               		, g_catalogos.lugares_inspeccion li
				           		where 
				                	 dda.lugar_inspeccion = li.id_lugar
				                	 and dda.identificador_operador = '$identificador'
				                	 and upper(li.nombre_provincia)=upper('$provincia') ORDER BY 1; " ;
				     break;
			case 3: $consulta="SELECT 
									fecha_creacion as fecha
				           		FROM 
				           		     g_fito_exportacion.fito_exportaciones 
				          		where 
				          		     identificador_solicitante= '$identificador' 
				          		     AND upper(provincia) = upper('$provincia') ORDER BY 1; " ;
				     break;
			case 4:  $consulta="SELECT 
									 fecha_creacion as fecha 
				           		FROM 
				           		     g_clv.certificado_clv 
				           		where 
				           		     identificador_titulares= '$identificador' ORDER BY 1; " ;
				     break;
            case 5:  $consulta="SELECT 
               						 fecha_creacion as fecha           						  
     			           		FROM 
				           			 g_importaciones.importaciones 
				                where 
				                     identificador_operador= '$identificador' 
				                     AND upper(nombre_provincia) = upper('$provincia') ORDER BY 1; " ;
				     break;
            case 6:	 $consulta="SELECT 
            						zoo.fecha_creacion as fecha
				     			FROM 
				        		    g_zoo_exportacion.zoo_exportaciones zoo
				         		    , g_operadores.sitios s
				        		    , g_operadores.areas a
				     			where 
					  				s.identificador_operador||'.'||s.codigo_provincia||s.codigo = zoo.codigo_sitio
					  				and s.id_sitio = a.id_sitio
					 				and zoo.identificador_operador= '$identificador'
					 				and upper(s.provincia)=upper('$provincia') ORDER BY 1; " ;
				     break;	
				     
            case 7:	 $consulta="SELECT
            						bpa.fecha_creacion as fecha
				     			FROM
				        		    g_certificacion_bpa.solicitudes bpa
				     			WHERE
					  				identificador_operador= '$identificador' 
				                    AND upper(provincia_unidad_produccion) = upper('$provincia') 
                                ORDER BY 1; " ;
            break;
            case 8:	 $consulta="SELECT
            						fecha_creacion_solicitud as fecha
				     			FROM
				        		   g_proveedores_exterior.proveedor_exterior
				     			WHERE
					  				identificador_operador= '$identificador'
				                    AND upper(nombre_provincia_operador) = upper('$provincia')
                                ORDER BY 1; " ;
            break;
            case 9:  $consulta="SELECT
               						 fecha_creacion as fecha
     			           		FROM
				           			 g_transito_internacional.transito_internacional
				                where
				                     identificador_importador= '$identificador'
				                     AND upper(provincia_revision) = upper('$provincia') ORDER BY 1; " ;
            break;
		}	
		$res = $conexion->ejecutarConsulta($consulta);			
		return $res;
	}
//-------------------------------devolver ruc de cliente----------------------------------------------
	public function devolverRucCliente($conexion,$textoDeBusqueda,$provincia,$tipoDeBusqueda){
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
				        . ($tipoDeBusqueda == 'ruc' ? "opr.identificador = '$textoDeBusqueda'" : "upper(opr.razon_social) like upper('$textoDeBusqueda')")
        			    ."  and opr.provincia='$provincia'";
		
		$res = $conexion->ejecutarConsulta($sql);		
		return $res;
	}						
//------------------devolver detalle de servicios segun opcion----------------------------------------------------------	
	public function listarDetalleServicios($conexion,$servicio,$identificador,$provincia,$contador,$limit,$offset){
		$offset--;
		switch ($servicio){
			case 1:$consulta="SELECT 
									".($contador == 0 ? " count(distinct ops.id_operacion) as contador ": 
									" distinct ops.id_operacion,
    								ops.identificador_operador as identificador,
   									sub.nombre as subtipo,
    								pro.nombre_comun as producto,
    								op.nombre as operacion,
    								ops.id_vue,
    								ops.id_tipo_operacion,
    								ops.fecha_creacion as fecha,
                                    op.id_flujo_operacion")."
							  FROM 
	    							g_operadores.operaciones ops
                                    INNER JOIN g_catalogos.tipos_operacion op ON op.id_tipo_operacion = ops.id_tipo_operacion
                                    INNER JOIN g_operadores.productos_areas_operacion pap ON pap.id_operacion = ops.id_operacion
	    							INNER JOIN g_operadores.areas ar ON pap.id_area = ar.id_area
									INNER JOIN g_operadores.sitios s ON ar.id_sitio = s.id_sitio
         					  		LEFT JOIN g_catalogos.productos pro ON pro.id_producto = ops.id_producto
         					  		LEFT JOIN g_catalogos.subtipo_productos sub ON  sub.id_subtipo_producto = pro.id_subtipo_producto
							  where
									ops.identificador_operador= '$identificador'
	  								and upper(s.provincia) = upper('$provincia')
	  						   ORDER BY 1
	  								".($contador == 0 ? " ":" limit $limit offset $offset ").";";
			break;
		
			case 2: $consulta="SELECT 
			                        ".($contador == 0 ? " count(distinct dda.id_destinacion_aduanera) as contador " : 
									"distinct dda.id_destinacion_aduanera,
  									dda.tipo_transporte,
  									dda.tipo_certificado,
  									dda.id_vue,
  									dda.identificador_operador as identificador,
  									dda.fecha_creacion as fecha ")."
				           		FROM 
				               		g_dda.destinacion_aduanera  dda
				               		, g_catalogos.lugares_inspeccion li
				           		where 
				                	 dda.lugar_inspeccion = li.id_lugar
				                	 and dda.identificador_operador = '$identificador'
				                	 and upper(li.nombre_provincia)=upper('$provincia') ORDER BY 1
				                	 ".($contador == 0 ? " ":" limit $limit offset $offset ").";" ;
			break;
			case 3: $consulta="SELECT
									".($contador == 0 ? " count(fito.id_fito_exportacion) as contador " : 
									" fito.id_fito_exportacion,
									fito.transporte,
									fito.pais_destino,
									fito.identificador_solicitante as identificador,
									fito.id_vue,
									fito.fecha_creacion as fecha ")."
				           		FROM 
				           		     g_fito_exportacion.fito_exportaciones fito
				          		where 
				          		     fito.identificador_solicitante= '$identificador' 
				          		     AND upper(fito.provincia) = upper('$provincia') ORDER BY 1
				          		     ".($contador == 0 ? " ":" limit $limit offset $offset ")."; " ;
			break;
			case 4:  $consulta="SELECT 
									".($contador == 0 ? " count(distinct clv.id_clv) as contador " : 
									" distinct clv.id_clv,
									sub.nombre as subtipo,
 									prd.nombre_comun as producto,
 									clv.tipo_datos_certificado as certificado,
 									clv.identificador_titulares as identificador,
 									clv.id_vue,
 									clv.fecha_creacion as fecha,
 									clv.nombre_pais as pais ")."
								FROM 
									g_clv.certificado_clv clv
									, g_catalogos.productos prd
									, g_catalogos.subtipo_productos sub
								where 
								    clv.identificador_titulares= '$identificador'
								    and clv.id_producto = prd.id_producto
								    and sub.id_subtipo_producto = prd.id_subtipo_producto 
								    ".($contador == 0 ? " ":"ORDER BY 6 limit $limit offset $offset ")."; " ;
			break;
			case 5:  $consulta="SELECT 
									  ".($contador == 0 ? " count(identificador_operador) as contador ": 
									  " identificador_operador as identificador,
               						  id_importacion,
               						  id_vue,
               						  tipo_transporte,
               						  puerto_embarque,
               						  fecha_creacion as fecha ")."  
								FROM 
									g_importaciones.importaciones
								where 
									identificador_operador= '$identificador' 
									AND upper(nombre_provincia) = upper('$provincia') 
									".($contador == 0 ? " ":"ORDER BY 2 limit $limit offset $offset ")." ;";
			break;
			case 6:	 $consulta="SELECT 
									".($contador == 0 ? " count(distinct (zoo.id_zoo_exportacion)) as contador " : 
									"distinct zoo.identificador_operador as identificador,
									zoo.id_zoo_exportacion,
									zoo.id_vue,
									zoo.transporte,
									zoo.pais_destino,
									zoo.fecha_creacion as fecha ")." 									
				     			FROM 
				        		    g_zoo_exportacion.zoo_exportaciones zoo
				         		    , g_operadores.sitios s
				        		    , g_operadores.areas a
				     			where 
					  				s.identificador_operador||'.'||s.codigo_provincia||s.codigo = zoo.codigo_sitio
					  				and s.id_sitio = a.id_sitio
					 				and zoo.identificador_operador= '$identificador'
					 				and upper(s.provincia)=upper('$provincia') 
					 				".($contador == 0 ? " ":" ORDER BY 2 limit $limit offset $offset ").";";
			break;
			case 7:  $consulta="SELECT
									  ".($contador == 0 ? " count(identificador_operador) as contador ":
									  " identificador_operador as identificador,
               						  id_solicitud,
               						  numero_certificado,
               						  tipo_solicitud,
               						  tipo_explotacion,
               						  fecha_creacion as fecha,
                                      es_asociacion ")."
								FROM
									g_certificacion_bpa.solicitudes
								where
									identificador_operador= '$identificador'
									AND upper(provincia_unidad_produccion) = upper('$provincia')
									".($contador == 0 ? " ":"ORDER BY 2 limit $limit offset $offset ")." ;";
			break;
			case 8:  $consulta="SELECT
									  ".($contador == 0 ? " count(identificador_operador) as contador ":
									  " identificador_operador as identificador,
               						  id_proveedor_exterior,
               						  codigo_creacion_solicitud,
                                      estado_solicitud,
               						  fecha_creacion_solicitud as fecha")."
								FROM
									g_proveedores_exterior.proveedor_exterior
								where
									identificador_operador= '$identificador'
									AND upper(nombre_provincia_operador) = upper('$provincia')
									".($contador == 0 ? " ":"ORDER BY 2 limit $limit offset $offset ")." ;";
			break;
			case 9:  $consulta="SELECT
									  ".($contador == 0 ? " count(identificador_importador) as contador ":
									  " identificador_importador as identificador,
               						  id_transito_internacional,
                                      nombre_importador,
               						  req_no,
                                      provincia_revision,
               						  fecha_creacion as fecha")."
								FROM
									g_transito_internacional.transito_internacional
								where
									identificador_importador= '$identificador'
									AND upper(provincia_revision) = upper('$provincia')
									".($contador == 0 ? " ":"ORDER BY 2 limit $limit offset $offset ")." ;";
			break;
			
		   }
		$res = $conexion->ejecutarConsulta($consulta);		
		return $res;
	}	
	
//-------------volcar todos los detalle servicios--------------------------------------------------------------------------------------------------------

	//------------------devolver detalle de servicios segun opcion----------------------------------------------------------
	public function listarDetalleServiciosxxx($conexion,$servicio,$identificador,$provincia){
		switch ($servicio){
			case 1:$consulta="SELECT
							 distinct ops.id_operacion,
							ops.identificador_operador as identificador,
							sub.nombre as subtipo,
							pro.nombre_comun as producto,
							op.nombre as operacion,
							ops.id_vue,
							ops.id_tipo_operacion,
							ops.fecha_creacion as fecha 
							FROM
							g_operadores.operaciones ops
							, g_operadores.areas ar
							, g_operadores.productos_areas_operacion pap
							, g_operadores.sitios s
							, g_catalogos.productos pro
							, g_catalogos.subtipo_productos sub
							, g_catalogos.tipos_operacion op
							where
							ar.id_sitio = s.id_sitio
							and pap.id_area = ar.id_area
							and pap.id_operacion = ops.id_operacion
							and s.identificador_operador = ops.identificador_operador
							and pro.id_producto = ops.id_producto
							and ops.identificador_operador= '$identificador'
							and upper(s.provincia) = upper('$provincia')
							and pro.id_producto = ops.id_producto
							and sub.id_subtipo_producto = pro.id_subtipo_producto
							and op.id_tipo_operacion = ops.id_tipo_operacion ORDER BY 1;";
			break;
	
			case 2: $consulta="SELECT
							distinct dda.id_destinacion_aduanera,
							dda.tipo_transporte,
							dda.tipo_certificado,
							dda.id_vue,
							dda.identificador_operador as identificador,
							dda.fecha_creacion as fecha 
							FROM
							g_dda.destinacion_aduanera  dda
							, g_catalogos.lugares_inspeccion li
							where
							dda.lugar_inspeccion = li.id_lugar
							and dda.identificador_operador = '$identificador'
							and upper(li.nombre_provincia)=upper('$provincia') ORDER BY 1;" ;
			break;
			case 3: $consulta="SELECT
							fito.id_fito_exportacion,
							fito.transporte,
							fito.pais_destino,
							fito.identificador_solicitante as identificador,
							fito.id_vue,
							fito.fecha_creacion as fecha 
							FROM
							g_fito_exportacion.fito_exportaciones fito
							where
							fito.identificador_solicitante= '$identificador'
							AND upper(fito.provincia) = upper('$provincia') ORDER BY 1; " ;
			break;
			case 4:  $consulta="SELECT
							distinct clv.id_clv,
							sub.nombre as subtipo,
							prd.nombre_comun as producto,
							clv.tipo_datos_certificado as certificado,
							clv.identificador_operador as identificador,
							clv.id_vue,
							clv.fecha_creacion as fecha,
							clv.nombre_pais as pais 
							FROM
							g_clv.certificado_clv clv
							, g_catalogos.productos prd
							, g_catalogos.subtipo_productos sub
							where
							clv.identificador_operador= '$identificador'
							and clv.id_producto = prd.id_producto
							and sub.id_subtipo_producto = prd.id_subtipo_producto ORDER BY 1; " ;
			break;
			case 5:  $consulta="SELECT
							identificador_operador as identificador,
							id_importacion,
							id_vue,
							tipo_transporte,
							puerto_embarque,
							fecha_creacion as fecha 
							FROM
							g_importaciones.importaciones
							where
							identificador_operador= '$identificador'
							AND upper(nombre_provincia) = upper('$provincia')ORDER BY 2 ;";
			break;
			case 6:	 $consulta="SELECT
							distinct zoo.identificador_operador as identificador,
							zoo.id_zoo_exportacion,
							zoo.id_vue,
							zoo.transporte,
							zoo.pais_destino,
							zoo.fecha_creacion as fecha 
							FROM
							g_zoo_exportacion.zoo_exportaciones zoo
							, g_operadores.sitios s
							, g_operadores.areas a
							where
							s.identificador_operador||'.'||s.codigo_provincia||s.codigo = zoo.codigo_sitio
							and s.id_sitio = a.id_sitio
							and zoo.identificador_operador= '$identificador'
							and upper(s.provincia)=upper('$provincia')
							ORDER BY 2;";
			break;
	
		}
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
		}	
	
//-----------------------------devolver detalle de operaciones realizadas----------------------------------------------	
	public function listarResultadoServicio($conexion,$servicio,$identificador,$solicitud){
		
		 $solicitud = $solicitud==''?'0':$solicitud;
		 
		$consulta=" SELECT *
					FROM
						g_revision_solicitudes.grupos_solicitudes s, 
						g_revision_solicitudes.asignacion_inspector a
					WHERE 
						s.id_solicitud='$solicitud' 
						AND s.id_grupo = a.id_grupo AND
						a.tipo_solicitud='$servicio' order by 1;";	

		$res = $conexion->ejecutarConsulta($consulta);	
		return $res;	
	}
//-----------------------------devolver detalle de operaciones realizadas----------------------------------------------
	public function verificarAgrupacion($conexion,$idGrupo){
		$consulta=" SELECT *
					FROM
						g_revision_solicitudes.grupos_solicitudes s						
					WHERE
						s.id_grupo = $idGrupo ;";

		$res = $conexion->ejecutarConsulta($consulta);
		return $res;	
	}
//------------------------------devolver datos de operador-------------------------------------------
	public function datosOperador($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("
								SELECT *
								FROM
				  					 g_uath.ficha_empleado
								WHERE 
				  					identificador='$identificador';");
		return $res;	
	}	
//-----------------------------devolver operaciones realizadas----------------------------------------------
  public function listarResultadoInspector($conexion,$servicio,$idGrupo,$opcion,$idOperacion){
				
			switch ($servicio){
				case 'Financiero':
					switch ($opcion){
						  case 1:
					            $consulta="SELECT *
										   FROM
				                                g_financiero.orden_pago o
										   WHERE 
				                                 o.id_grupo_solicitud = '$idGrupo' order by 1;";
					       break;
					       case 2:
					            $consulta=" SELECT *
											  FROM
				                                 g_revision_solicitudes.financiero f
											  WHERE 
				                                 f.id_grupo = '$idGrupo' order by 1;";
					       break;
					              }
				break;		
				case 'Documental':
					switch ($opcion){
						  case 1:$consulta=" SELECT *
											  FROM
				                                 g_revision_solicitudes.revision_documental d
											  WHERE 
				                                 d.id_grupo = '$idGrupo' order by 1;";
						  break;						  
						  }
				break;
				case 'Técnico': 
					switch ($opcion){
						  case 1:$consulta=" SELECT 
						  						distinct t.fecha_inspeccion
						  						, t.identificador_inspector
						  						, t.estado
						  						
						  						, t.ruta_archivo
						  						, t.observacion
											  FROM
				                                 g_revision_solicitudes.inspeccion t 
				                                -- , g_revision_solicitudes.inspeccion_observaciones tOb 
											  WHERE 
											     --t.id_inspeccion = tOb.id_inspeccion
											    -- and t.id_item_inspeccion = tOb.id_item_inspeccion and
				                                 t.id_grupo = '$idGrupo' order by 1;"; 
						  break;
						  case 2:$consulta=" SELECT 
						  						 distinct t.fecha_inspeccion
						  						 , t.identificador_inspector
						  						 , t.estado
						  						 
						  						 , t.ruta_archivo
						  						 , t.observacion
											  FROM
				                                 g_revision_solicitudes.inspeccion t 
				                                -- , g_revision_solicitudes.inspeccion_observaciones tOb 
											  WHERE 
											    -- t.id_inspeccion = tOb.id_inspeccion
											    -- and t.id_item_inspeccion = tOb.id_item_inspeccion and 
				                                   t.id_grupo = '$idGrupo'
				                                 --and tOb.id_solicitud = $idOperacion 
				                             order by 1;";
						  break;
						  
			
							}
				break;
				case 'Aprobación':
				    switch ($opcion){
				        case 1:$consulta=" SELECT 
						  						distinct t.fecha_inspeccion
						  						, t.identificador_inspector
						  						, t.estado
						  						
						  						, t.ruta_archivo
						  						, t.observacion
											  FROM
				                                 g_revision_solicitudes.inspeccion t  
											  WHERE 
											     t.id_grupo = '$idGrupo' order by 1;";
				        break;
				    }
				    break;
			}
			$res = $conexion->ejecutarConsulta($consulta);
			return $res;
		
		}

//-----------------------------devolver observaciones realizadas----------------------------------------------
 public function devolverObservacion($conexion,$idGrupo,$idOperacion){
		
				$consulta="SELECT 
								tOb.observacion
				  		   FROM
	 							g_revision_solicitudes.inspeccion t 
	 							, g_revision_solicitudes.inspeccion_observaciones tOb 
						   WHERE 
				 			  t.id_inspeccion = tOb.id_inspeccion 
	 						  and t.id_grupo = '$idGrupo'
							  and tOb.id_solicitud = $idOperacion;";
					
			$res = $conexion->ejecutarConsulta($consulta);
			return $res;
		
   }
  //-----------------------------devolver observaciones realizadas----------------------------------------------
   public function devolverObservacionArea($conexion,$idOperacion){
   try {
   	$consulta="SELECT
   					tOb.observacion
   				FROM
   					g_revision_solicitudes.inspeccion t
   					, g_revision_solicitudes.inspeccion_observaciones tOb
   				WHERE
   					t.id_inspeccion = tOb.id_inspeccion
   					and tOb.id_solicitud = $idOperacion;";
   	$res = $conexion->ejecutarConsulta($consulta);
   	return $res;
   } catch (Exception $e) {
   
   }
   
   
   }
//--------------migrar datos y eliminar datos duplicados----------------------------

 public function migrarDatos($conexion,$servicio,$idGrupo,$idInspeccion,$idItemInspeccion,$fechaInspeccion, $observacion, $tipo_elemento,$idSolicitud)
	{
	switch ($servicio){
		case 1:$consulta="SELECT
								*
							FROM
								g_revision_solicitudes.inspeccion
							order by 2 ;";
		break;
		case 2:$consulta="INSERT INTO 
								g_revision_solicitudes.inspeccion_observaciones(
            					id_inspeccion, id_item_inspeccion,fecha_inspeccion, observacion, tipo_elemento, id_solicitud)
            			  VALUES ($idInspeccion,$idItemInspeccion,'$fechaInspeccion','$observacion','$tipo_elemento',$idSolicitud);";
		break;
	}
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;	
	}		
 public function eliminarDuplicados($conexion)
	{
		$res = $conexion->ejecutarConsulta("
				DELETE FROM 
				       g_revision_solicitudes.inspeccion 
			    WHERE id_inspeccion not in 
				      (select id_inspeccion 
				       from g_revision_solicitudes.inspeccion_observaciones 
				      order by 1);	
			");
		return $res;
	}

//-----------------------------------obtener datos operacion por idsolicitud--------------------------------------------
public function obtenerDatosOperacionSolicitud($conexion, $identificador, $tipo_operacion,$area,$idOperacion) {
			
				$res = $conexion->ejecutarConsulta("
					SELECT array_to_json(array_agg(row_to_json (areas)))
						FROM (
							select
							distinct on (a3.id_area) a3.*
							, a3.codigo codigo_area
							, s3.*
							, s3.codigo codigo_sitio
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
									, opr.nombre_pais as nombre_pais
									, opr.observacion
                                    , to_char(opr.fecha_finalizacion,'DD-MM-YYYY (HH24:MI)') as fecha_finalizacion
								from
									g_operadores.productos_areas_operacion pao
									, g_operadores.operaciones opr
									, g_catalogos.productos p
									, g_catalogos.subtipo_productos sp
									, g_catalogos.tipo_productos tp
								where
									pao.id_operacion = opr.id_operacion
									and opr.id_tipo_operacion = $tipo_operacion
									and opr.id_operacion='$idOperacion'
									and opr.id_producto = p.id_producto
									and p.id_subtipo_producto = sp.id_subtipo_producto
									and sp.id_tipo_producto = tp.id_tipo_producto
									and pao.id_area = a3.id_area
									".($area!='Todas'?" and tp.id_area='$area'":'')."
								) productos_n4
			
						) productos
							from
								g_operadores.operaciones opc3
								, g_operadores.productos_areas_operacion pao3
								, g_operadores.areas a3
								, g_operadores.sitios s3
							where
								s3.id_sitio = a3.id_sitio
								and opc3.id_operacion = pao3.id_operacion
								and opc3.id_operacion='$idOperacion'
								and pao3.id_area = a3.id_area
								and opc3.id_tipo_operacion = $tipo_operacion
								and opc3.identificador_operador = '$identificador'
							order by
							a3.id_area
					) as areas");
						return pg_fetch_assoc($res);
			}
   //-------------------listar detalle vue----------------------------
	public function listarDetalleServiciosVue($conexion,$servicio,$idVue){
						
				switch ($servicio){
					case 'Operadores':
					    
					    $tabla = "";
					    $busqueda = "";
					    
					    if(is_numeric($idVue)){
					        $busqueda = " ops.id_operacion";
					    }else{
					        
					        $tipoOperacion = "";
					        
					        if(strpos($idVue, "A") != false) {
					            $tipoOperacion = 'AIPRO';
					        }else if(strpos($idVue, "B") != false){
					            $tipoOperacion = 'AIPRC';
					        }else if(strpos($idVue, "C") != false){
					            $tipoOperacion = 'AICOM';
					        }else if(strpos($idVue, "D") != false){
					            $tipoOperacion = 'AIREC';
					        }
					        					        
					        $tabla = "INNER JOIN g_operadores.codigos_poa cp ON ops.identificador_operador = cp.identificador_operador
                                      INNER JOIN g_operadores.subcodigos_poa scp ON cp.id_codigo_poa = scp.id_codigo_poa";
					        $busqueda = " op.id_area || op.codigo = '" . $tipoOperacion . "' and scp.subcodigo_poa";
					    }
					    
						         $consulta="SELECT
                                            		distinct ops.id_operacion,
                                            		ops.identificador_operador as identificador,
                                            		sub.nombre as subtipo,
                                            		pro.nombre_comun as producto,
                                            		op.nombre as operacion,
                                            		ops.id_vue,
                                            		ops.id_tipo_operacion,
                                            		ops.fecha_creacion as fecha,
                                                    op.id_flujo_operacion
                                            FROM
                                            	g_operadores.operaciones ops
                                            INNER JOIN g_operadores.sitios s ON ops.identificador_operador = s.identificador_operador
                                            INNER JOIN g_catalogos.productos pro ON ops.id_producto = pro.id_producto
                                            INNER JOIN g_catalogos.subtipo_productos sub ON pro.id_subtipo_producto = sub.id_subtipo_producto
                                            INNER JOIN g_catalogos.tipos_operacion op ON ops.id_tipo_operacion = op.id_tipo_operacion
                                            " . $tabla . "
                                            WHERE " . $busqueda ." = '" . $idVue . "';";
                                            							
					break;
					case 'ROCE':
						$consulta="SELECT
											distinct ops.id_operacion,
											ops.identificador_operador as identificador,
											sub.nombre as subtipo,
											pro.nombre_comun as producto,
											op.nombre as operacion,
											ops.id_vue,
											ops.id_tipo_operacion,
											ops.fecha_creacion as fecha
										
										
											FROM
											g_operadores.operaciones ops
											, g_operadores.sitios s
											, g_catalogos.productos pro
											, g_catalogos.subtipo_productos sub
											, g_catalogos.tipos_operacion op
											where
											s.identificador_operador = ops.identificador_operador
												
											and pro.id_producto = ops.id_producto
											and sub.id_subtipo_producto = pro.id_subtipo_producto
											and op.id_tipo_operacion = ops.id_tipo_operacion
											and ops.id_vue='$idVue';";
							
						break;
			
					case 'DDA': $consulta="SELECT
												dda.id_destinacion_aduanera,
												dda.tipo_transporte,
												dda.tipo_certificado,
												dda.id_vue,
												dda.identificador_operador as identificador,
												dda.fecha_creacion as fecha,
												li.nombre_provincia as provincia
											FROM
												g_dda.destinacion_aduanera  dda
												, g_catalogos.lugares_inspeccion li
											where
												dda.lugar_inspeccion = li.id_lugar
												".(strlen($idVue) > 18 ?" and dda.id_vue='$idVue'":" and dda.id_destinacion_aduanera='$idVue'").";" ;
					break;
					case 'Fitosanitario':
						        $consulta="SELECT
											    fito.id_fito_exportacion,
											    fito.transporte,
											    fito.pais_destino,
											    fito.identificador_solicitante as identificador,
												fito.id_vue,
												fito.fecha_creacion as fecha,
												fito.provincia
											FROM
												g_fito_exportacion.fito_exportaciones fito
											where
												".(strlen($idVue) > 18 ?"  fito.id_vue='$idVue'":" fito.id_fito_exportacion='$idVue'").";";
					break;
					case 'CLV':  $consulta="SELECT
												clv.id_clv,
												sub.nombre as subtipo,
												prd.nombre_comun as producto,
												clv.tipo_datos_certificado as certificado,
												clv.identificador_titulares as identificador,
												clv.id_vue,
												clv.fecha_creacion as fecha,
												clv.nombre_pais as pais
											FROM
												g_clv.certificado_clv clv
												, g_catalogos.productos prd
												, g_catalogos.subtipo_productos sub
											where
												clv.id_producto = prd.id_producto
												and sub.id_subtipo_producto = prd.id_subtipo_producto
												".(strlen($idVue) > 18 ?" and clv.id_vue='$idVue'":" and clv.id_clv='$idVue'").";" ;
					break;
					case 'Importación':$consulta="SELECT
													identificador_operador as identificador,
													id_importacion,
													id_vue,
													tipo_transporte,
													puerto_embarque,
													fecha_creacion as fecha,
													nombre_provincia
												FROM
													g_importaciones.importaciones
												where
													".(strlen($idVue) > 18 ?" id_vue='$idVue'":" id_importacion='$idVue'")."; " ;
					break;
					case 'Zoosanitario':$consulta="SELECT
														zoo.identificador_operador as identificador,
														zoo.id_zoo_exportacion,
														zoo.id_vue,
														zoo.transporte,
														zoo.pais_destino,
														zoo.fecha_creacion as fecha
													FROM
														g_zoo_exportacion.zoo_exportaciones zoo
														
													where
														".(strlen($idVue) > 18 ?" zoo.id_vue='$idVue'":" zoo.id_zoo_exportacion='$idVue'")."; " ;
					break;
					
					case 'certificacionBPA':$consulta="SELECT
														s.identificador_operador as identificador,
														s.id_solicitud,
														s.numero_certificado,
														s.tipo_solicitud,
														s.tipo_explotacion,
														s.fecha_creacion as fecha,
                                                        s.es_asociacion
													FROM
														g_certificacion_bpa.solicitudes s
					    
													where
														s.id_solicitud='$idVue'; " ;
					break;
					case 'proveedorExterior':  $consulta="SELECT
        									  identificador_operador as identificador,
                       						  id_proveedor_exterior,
                       						  codigo_creacion_solicitud,
                                              estado_solicitud,
                       						  fecha_creacion_solicitud as fecha
        								FROM
        									g_proveedores_exterior.proveedor_exterior
        								where
        									codigo_creacion_solicitud = '$idVue';";
					break;
					case 'TransitoInternacional':$consulta="SELECT
													identificador_importador as identificador,
                                                    nombre_importador,
													id_transito_internacional,
													req_no as id_vue,
													nombre_punto_ingreso,
													nombre_punto_salida,
													fecha_creacion as fecha,
													provincia_revision as nombre_provincia
												FROM
													g_transito_internacional.transito_internacional
												where
													".(strlen($idVue) > 18 ?" req_no='$idVue'":" id_transito_internacional='$idVue'")."; " ;
					break;
				}
				try {
					$res = $conexion->ejecutarConsulta($consulta);
					return $res;
				} catch (Exception $e) {
					$error='';
					return $error;
				}
				
		}
			
//----------------------------------------------------------------------------------------------------------
public function filtrarOperadorRucCi($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area) {
		
			$sql = "select
				distinct opr.*
			from
				g_operadores.operadores opr
				, g_operadores.sitios s
				, g_operadores.areas ar
				, g_operadores.operaciones opc
				, g_catalogos.tipos_operacion topc
				, g_operadores.productos_areas_operacion pap
			where
				ar.id_sitio = s.id_sitio
				and opr.identificador = opc.identificador_operador 
				and opc.identificador_operador = s.identificador_operador				
				and opc.id_tipo_operacion = topc.id_tipo_operacion
				and pap.id_area = ar.id_area
            	and pap.id_operacion = opc.id_operacion
				and upper(opr.razon_social) like upper('%$textoDeBusqueda%')
				and upper(s.provincia) = upper('$provincia');";
			
			$res = $conexion->ejecutarConsulta($sql);
		
			return $res;
		}

//----------------------------------------------------------------------------------------------------------
	public function filtrarOperadorRucCiNumero($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area, $limit, $offset,$contador) {
		    $offset--;
			$sql = "select			
			".($contador == 0 ? " distinct opr.identificador, opr.razon_social,  opr.nombre_representante, opr.apellido_representante " : "count(distinct opr.*) as contador")."
			from
			g_operadores.operadores opr
			, g_operadores.sitios s
			, g_operadores.areas ar
			, g_operadores.operaciones opc
			, g_catalogos.tipos_operacion topc
			, g_operadores.productos_areas_operacion pap
			where
			ar.id_sitio = s.id_sitio
			and opr.identificador = opc.identificador_operador
			and opc.identificador_operador = s.identificador_operador
			and opc.id_tipo_operacion = topc.id_tipo_operacion
			and pap.id_area = ar.id_area
			and pap.id_operacion = opc.id_operacion
			and upper(opr.razon_social) like upper('%$textoDeBusqueda%')
			and upper(s.provincia) = upper('$provincia')
			".($contador == 0 ? " limit $limit offset $offset ":""). ";";	
			
			$res = $conexion->ejecutarConsulta($sql);		
			return $res;
		}
		
		public function filtrarOperadorRucCiNumeroBPA($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area, $limit, $offset,$contador) {
		    $offset--;
		    $sql = "select
			".($contador == 0 ? " distinct a.identificador, a.razon_social,  a.nombre_representante_legal as nombre_representante, null as apellido_representante" : "count(distinct a.*) as contador")."
			from
			g_certificacion_bpa.asociaciones a
			, g_certificacion_bpa.solicitudes s
			where
			a.identificador = s.identificador_operador
			and upper(a.razon_social) like upper('%$textoDeBusqueda%')
			and upper(s.provincia_unidad_produccion) = upper('$provincia')
			".($contador == 0 ? " limit $limit offset $offset ":""). ";";
		    
		    $res = $conexion->ejecutarConsulta($sql);
		    return $res;
		}
		
		public function filtrarOperadorRucCiNumeroTransito($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area, $limit, $offset,$contador) {
		    $offset--;
		    
		    $sql = "select
			".($contador == 0 ? " distinct ti.identificador_importador as identificador, ti.nombre_importador as razon_social,  null as nombre_representante, null as apellido_representante " : "count(distinct ti.*) as contador")."
			from
			g_transito_internacional.transito_internacional ti
			where
			upper(ti.nombre_importador) like upper('%$textoDeBusqueda%')
			and upper(ti.provincia_revision) = upper('$provincia')
			".($contador == 0 ? " limit $limit offset $offset ":""). ";";
		    
		    $res = $conexion->ejecutarConsulta($sql);
		    return $res;
		}
		
		public function filtrarRazonSocialUsuariosRucCiNumero($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area, $limit, $offset,$contador) {
		    $offset--;
		    
		    $sql = "select
			".($contador == 0 ? " distinct opr.identificador, opr.razon_social,  opr.nombre_representante, opr.apellido_representante " : "count(distinct opr.*) as contador")."
			from
			g_operadores.operadores opr
			, g_operadores.sitios s
			, g_operadores.areas ar
			, g_operadores.operaciones opc
			, g_catalogos.tipos_operacion topc
			, g_operadores.productos_areas_operacion pap
			where
			ar.id_sitio = s.id_sitio
			and opr.identificador = opc.identificador_operador
			and opc.identificador_operador = s.identificador_operador
			and opc.id_tipo_operacion = topc.id_tipo_operacion
			and pap.id_area = ar.id_area
			and pap.id_operacion = opc.id_operacion
			and upper(opr.razon_social) like upper('%$textoDeBusqueda%')
			and upper(s.provincia) = upper('$provincia')

            UNION 

            select
			".($contador == 0 ? " distinct a.identificador, a.razon_social,  a.nombre_representante_legal as nombre_representante, null as apellido_representante" : "count(distinct a.*) as contador")."
			from
			g_certificacion_bpa.asociaciones a
			, g_certificacion_bpa.solicitudes s
			where
			a.identificador = s.identificador_operador
			and upper(a.razon_social) like upper('%$textoDeBusqueda%')
			and upper(s.provincia_unidad_produccion) = upper('$provincia')

            UNION

            select
			".($contador == 0 ? " distinct ti.identificador_importador as identificador, ti.nombre_importador as razon_social,  null as nombre_representante, null as apellido_representante " : "count(distinct ti.*) as contador")."
			from
			g_transito_internacional.transito_internacional ti
			where
			upper(ti.nombre_importador) like upper('%$textoDeBusqueda%')
			and upper(ti.provincia_revision) = upper('$provincia')

			".($contador == 0 ? " limit $limit offset $offset ":""). ";";
		    
		    $res = $conexion->ejecutarConsulta($sql);
		    return $res;
		}
//-----------------------------------------------------------------------------------------------		
	Public function devolverFecha($fecha){
		
 	        $fechaN = explode(" ", $fecha);
 	        $hora = explode(".",$fechaN[1]);
 	        $fechaNu=date('d/m/Y',strtotime($fechaN[0]));

 	        $fechaNew = $fechaNu.' ('.$hora[0].')';	        
 	        if($hora[0]=='')$fechaNew=$fechaNu;
 	        if($fechaN[0]=='')$fechaNew='';
 	     	        		
			return $fechaNew;
		}
//----------------------------traer documentos adjuntos segun tipo de servicio---------------------------------------------------------------------------------------------
		public function abrirArchivos($conexion, $idservicio,$tipoServicio){
			switch ($tipoServicio){
				case 'Operadores':
					$consulta="";
					/*select * from g_operadores.operaciones_anexos a
					, g_operadores.operaciones op
					, g_operadores.documentos_anexos da
					where op.id_operacion = a.id_operacion
					and a.id_documento_anexo = da.id_documento_anexo
					and da.identificador_operador = op.identificador_operador
					and op.id_operacion=$idservicio*/
					break;	
					case 'ROCE':
						$consulta="";
						
						break;
					case 'DDA': $cid = $conexion->ejecutarConsulta("SELECT
										*
										FROM
										g_dda.documentos_adjuntos
										WHERE
										id_destinacion_aduanera = $idservicio;");
							
										while ($fila = pg_fetch_assoc($cid)){
											$res[] = array(
												idDestinacionAduanera=>$fila['id_destinacion_aduanera'],
												identificador=>$fila['identificador'],
												tipoArchivo=>$fila['tipo_archivo'],
												rutaArchivo=>$fila['ruta_archivo'],
												area=>$fila['area'],
												idVue=>$fila['id_vue']);
										}
					break;
					case 'Fitosanitario':$cid = $conexion->ejecutarConsulta("SELECT
										*	
										FROM
										g_fito_exportacion.documentos_adjuntos
										WHERE
										id_fito_exportacion = $idservicio;");
							
										while ($fila = pg_fetch_assoc($cid)){
											$res[] = array(
												idDestinacionAduanera=>$fila['id_fito_exportacion'],
												identificador=>$fila['identificador'],
												tipoArchivo=>$fila['tipo_archivo'],
												rutaArchivo=>$fila['ruta_archivo'],
												area=>$fila['area'],
												idVue=>$fila['id_vue']);
											}
					break;
					case 'CLV':$cid = $conexion->ejecutarConsulta("SELECT
											*
											FROM
											g_clv.documentos_adjuntos
											WHERE
											id_clv = $idservicio;");
								
											while ($fila = pg_fetch_assoc($cid)){
												$res[] = array(
													idDestinacionAduanera=>$fila['id_clv'],
													identificador=>$fila['identificador'],
													tipoArchivo=>$fila['tipo_archivo'],
													rutaArchivo=>$fila['ruta_archivo'],
													area=>$fila['area'],
													idVue=>$fila['id_vue']);
												}
					break;
					case 'Importación':$cid = $conexion->ejecutarConsulta("SELECT
											*
											FROM
											g_importaciones.documentos_adjuntos
											WHERE
											id_importacion = $idservicio;");
								
											while ($fila = pg_fetch_assoc($cid)){
												$res[] = array(
													idDestinacionAduanera=>$fila['id_importacion'],
													identificador=>$fila['identificador'],
													tipoArchivo=>$fila['tipo_archivo'],
													rutaArchivo=>$fila['ruta_archivo'],
													area=>$fila['area'],
													idVue=>$fila['id_vue']);
												}
					break;
					case 'Zoosanitario':$cid = $conexion->ejecutarConsulta("SELECT
											*
											FROM
											g_zoo_exportacion.documentos_adjuntos
											WHERE
											id_zoo_exportacion = $idservicio;");
								
											while ($fila = pg_fetch_assoc($cid)){
												$res[] = array(
													idDestinacionAduanera=>$fila['id_zoo_exportacion'],
													identificador=>$fila['identificador'],
													tipoArchivo=>$fila['tipo_archivo'],
													rutaArchivo=>$fila['ruta_archivo'],
													area=>$fila['area'],
													idVue=>$fila['id_vue']);
												}
					break;					
			}
			return $res;
		}

//---------------------------------devolver un json de la lista de usuarios ------------------------------------------------------------------------------------------
	public function listarUsuarios($operadores, $provincia, $area, $offset){
			$contador = $offset-1; 
			$itemsFiltrados[] = array();
			while ($operador = pg_fetch_assoc($operadores)) {
				$itemsFiltrados[] = array('<tr
						id="' . $operador['identificador'] . '"
						class="item"
						data-rutaAplicacion="expedienteDigital"
						data-opcion="listadoServicio"
						data-idOpcion="'.$area.'.'.$provincia.'"
						data-destino="respuesta"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td>' . ++$contador . '</td>
						<td style="white-space:nowrap;"><b>' . $operador['identificador'] . '</b></td>
						<td>' . $operador['razon_social'] . '</td>
						<td>' . $operador['apellido_representante'] . ', ' . $operador['nombre_representante'] . '</td>
						<td></td>
						</tr>');
			}
			return json_encode($itemsFiltrados);
		}
//---------------------------------------------------------------------------------------------------------------------------
	public function listarDetallesxxx($consulta,$tipoServicio,$offset,$provincia,$area){
		$itemsFiltrados[] = array();
		$contador = $offset-1; 
		while ($servicio = pg_fetch_assoc($consulta)) {
			$fecha=$this->devolverFecha($servicio['fecha']);
			/*$fechaN = explode(" ", $servicio['fecha']);
			$hora = explode(".",$fechaN[1]);
			$fechaNu=date('d/m/Y',strtotime($fechaN[0]));		
			$fechaNew = $fechaNu.' ('.$hora[0].')';
			if($hora[0]=='')$fechaNew=$fechaNu;
			if($fechaN[0]=='')$fechaNew='';
			$fecha = $fechaNew;
			*/
			switch ($tipoServicio){
				case 'Operadores':
					$datos=$area.'.'.$provincia.'.'.$servicio['id_operacion'].'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$servicio['id_tipo_operacion'].'.'.$fecha;
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_operacion'];
					$columna1=$servicio['subtipo'];
					$columna2=$servicio['producto'];
					$columna3=$servicio['operacion'];
					break;
				case 'ROCE':
						$datos=$area.'.'.$provincia.'.'.$servicio['id_operacion'].'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$servicio['id_tipo_operacion'].'.'.$fecha;
						$identificador=$servicio['identificador'];
						$idOperacion= $servicio['id_operacion'];
						$columna1=$servicio['subtipo'];
						$columna2=$servicio['producto'];
						$columna3=$servicio['operacion'];
						break;
				case 'DDA':
					$datos=$area.'.'.$provincia.'.'.$servicio['id_destinacion_aduanera'].'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_destinacion_aduanera'];
					$columna1=$servicio['id_vue'];
					$columna2=$servicio['tipo_transporte'];
					$columna3=$servicio['tipo_certificado'];
					break;
				case 'Fitosanitario':
					$datos=$area.'.'.$provincia.'.'.$servicio['id_fito_exportacion'].'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_fito_exportacion'];
					$columna1=$servicio['id_vue'];
					$columna2=$servicio['transporte'];
					$columna3=$servicio['pais_destino'];
					break;
				case 'CLV':
					$datos=$area.'.'.$provincia.'.'.$servicio['id_clv'].'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_clv'];
					$columna1=$servicio['subtipo'];
					$columna2=$servicio['producto'];
					$columna3=$servicio['certificado'];
					break;
				case 'Importación':
					$datos=$area.'.'.$provincia.'.'.$servicio['id_importacion'].'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_importacion'];
					$columna1=$servicio['id_vue'];
					$columna2=$servicio['tipo_transporte'];
					$columna3=$servicio['puerto_embarque'];
					break;
				case 'Zoosanitario':
					$datos=$area.'.'.$provincia.'.'.$servicio['id_zoo_exportacion'].'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_zoo_exportacion'];
					$columna1=$servicio['id_vue'];
					$columna2=$servicio['transporte'];
					$columna3=$servicio['pais_destino'];
					break;
			}
			$itemsFiltrados[] = array('<tr
					id="' . $identificador . '"
					class="item"
					data-rutaAplicacion="expedienteDigital"
					data-opcion="abrirUsuario"
					data-idOpcion="'.$datos.'"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>' . ++$contador . '</td>
					<td style="white-space:nowrap;">'.$idOperacion.'</td>
					<td>' . $columna1 . '</td>
					<td>' . $columna2 . '</td>
					<td>' . $columna3 . '</td>
					</tr>');
		}return json_encode($itemsFiltrados);
		
	}
//------------------------------------------------------------------------------------
public function encabezadoDetalleServicio($tipoServicio)
	{
		$tabla1 = '<table id="tablaItems">
		<thead>
		<tr>';
		switch ($tipoServicio){
			case 'Operadores':
				$tabla2 ='<th>#</th>
				<th>Id Operación</th>
				<th>Subtipo</th>
				<th>Producto</th>
				<th>Operación</th>';
				break;
			case 'ROCE':
					$tabla2 ='<th>#</th>
					<th>Id Operación</th>
					<th>Subtipo</th>
					<th>Producto</th>
					<th>Operación</th>';
					break;
			case 'DDA':
				$tabla2 ='<th>#</th>
				<th>RUC</th>
				<th>#Solicitud</th>
				<th>Tipo de Certificado</th>
				<th>Transporte</th>';
				break;
			case 'Fitosanitario':
				$tabla2 ='<th>#</th>
				<th>RUC</th>
				<th>#Solicitud</th>
				<th>Tipo de Certificado</th>
				<th>Transporte</th>';
				break;
			case 'CLV':
				$tabla2 ='<th>#</th>
				<th>RUC</th>
				<th>#Solicitud</th>
				<th>Tipo de Certificado</th>
				<th>País</th>';
				break;
			case 'Importación':
				$tabla2 ='<th>#</th>
				<th>Id Operación</th>
				<th>#Solicitud</th>
				<th>Tipo de Certificado</th>
				<th>Transporte</th>';
				break;
			case 'Zoosanitario':
				$tabla2 = '<th>#</th>
				<th>RUC</th>
				<th>#Solicitud</th>
				<th>Tipo de Certificado</th>
				<th>Transporte</th>';
				break;
			case 'certificacionBPA':
			    $tabla2 = '<th>#</th>
				<th>Cédula/RUC</th>
				<th>#Solicitud</th>
				<th>Tipo de Solicitud</th>
				<th>Área de Explotación</th>';
			    break;
			case 'proveedorExterior':
			    $tabla2 = '<th>#</th>
				<th>Cédula/RUC</th>
				<th>#Solicitud</th>
				<th>Código creación solicitud</th>
			    <th>Estado solicitud</th>';
			    break;
			case 'TransitoInternacional':
			    $tabla2 = '<th>#</th>
				<th>Razón social Importador</th>
				<th>#Solicitud</th>
				<th>Tipo de Certificado</th>
			    <th>Provincia revisión</th>';
			    break;
		}
		$tabla3= '</tr>
		</thead>
		<tbody>
		</tbody>
		</table>';
	 return $tabla1.$tabla2.$tabla3;
	}	
//---------------------------------------------------------------------------------------------------------------------------------------
	public function listarDetalles($consulta,$tipoServicio,$contador,$provincia,$area){
		$itemsFiltrados[] = array();
		--$contador;
		while ($servicio = pg_fetch_assoc($consulta)) {
			$fecha = $this->devolverFecha($servicio['fecha']);
			switch ($tipoServicio){
				case 'Operadores':
					
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_operacion'];
					$columna0=$idOperacion;
					$columna1=$servicio['subtipo'];
					$columna2=$servicio['producto'];
					$columna3=$servicio['operacion'];
					$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$servicio['id_tipo_operacion'].'.'.$fecha.'.'.$servicio['id_flujo_operacion'];
					break;
				case 'ROCE':
							
						$identificador=$servicio['identificador'];
						$idOperacion= $servicio['id_operacion'];
						$columna0=$idOperacion;
						$columna1=$servicio['subtipo'];
						$columna2=$servicio['producto'];
						$columna3=$servicio['operacion'];
						$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$servicio['id_tipo_operacion'].'.'.$fecha;
						break;
				case 'DDA':
					
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_destinacion_aduanera'];
					$columna0=$identificador;
					$columna1=$servicio['id_vue'];
					$columna2=$tipoServicio;
					$columna3=$servicio['tipo_transporte'];
					$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					break;
				case 'Fitosanitario':
					
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_fito_exportacion'];
					$columna0=$identificador;
					$columna1=$servicio['id_vue'];
					$columna2=$tipoServicio;
					$columna3=$servicio['transporte'];
					$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					break;
				case 'CLV':
					
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_clv'];
					$columna0=$identificador;
					$columna1=$servicio['id_vue'];
					$columna2='Certificado de Libre Venta';
					$columna3=$servicio['pais'];
					$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					break;
				case 'Importación':
					
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_importacion'];
					$columna0=$idOperacion;
					$columna1=$servicio['id_vue'];
					$columna2=$tipoServicio;
					$columna3=$servicio['tipo_transporte'];
					$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					break;
				case 'Zoosanitario':
					
					$identificador=$servicio['identificador'];
					$idOperacion= $servicio['id_zoo_exportacion'];
					$columna0=$identificador;
					$columna1=$servicio['id_vue'];
					$columna2=$tipoServicio;
					$columna3=$servicio['transporte'];
					$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
					break;
				case 'certificacionBPA':
				    
				    $identificador=$servicio['identificador'];
				    $idOperacion= $servicio['id_solicitud'];
				    $columna0=$identificador;
				    $columna1=$servicio['id_solicitud'];
				    $columna2=$servicio['tipo_solicitud'];
				    $columna3=($servicio['tipo_explotacion']=='SA'?'Sanidad Animal':($servicio['tipo_explotacion']=='SV'?'Sanidad Vegetal':'Inocuidad de los Alimentos'));
				    $datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_solicitud'].'.'.$tipoServicio.'.'.$fecha.'.'.$servicio['es_asociacion'];
				    break;
				case 'proveedorExterior':
				    
				    $identificador=$servicio['identificador'];
				    $idOperacion= $servicio['id_proveedor_exterior'];
				    $columna0=$identificador;
				    $columna1=$servicio['id_proveedor_exterior'];
				    $columna2=$servicio['codigo_creacion_solicitud'];
				    $columna3=$servicio['estado_solicitud'];
				    $datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_solicitud'].'.'.$tipoServicio.'.'.$fecha;
				    break;
				case 'TransitoInternacional':
				    
				    $identificador=$servicio['identificador_importador'];
				    $idOperacion= $servicio['id_transito_internacional'];
				    $columna0=$servicio['nombre_importador'];
				    $columna1=$servicio['req_no'];
				    $columna2=$tipoServicio;
				    $columna3=$servicio['provincia_revision'];
				    $datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_solicitud'].'.'.$tipoServicio.'.'.$fecha;
				    break;
			}
			$itemsFiltrados[] = array('<tr
					id="' . $idOperacion . '"
					class="item"
					data-rutaAplicacion="expedienteDigital"
					data-opcion="abrirUsuario"
					data-idOpcion="'.$datos.'"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
					<td>' . ++$contador . '</td>
					<td style="white-space:nowrap;">'.$columna0.'</td>
					<td>' . $columna1 . '</td>
					<td>' . $columna2 . '</td>
					<td>' . $columna3 . '</td>
					</tr>');
		}return json_encode($itemsFiltrados);
	
	}
	//-----------------imprimir datos del cliente -------------------------------------------------------------------	

public function datosCliente($operador)
	{
		
		        $telf1=$operador['telefono_dos']==''? '':'  |  [TF2]: <u>' . $operador['telefono_dos'] . '</u>';
				$telf2=$operador['fax']==''? '':'  |  [FAX]: <u>' . $operador['fax'] . '</u>';
				$telf3=$operador['celular_uno']==''? '':'  |  [CEL1]: <u>' . $operador['celular_uno'] . '</u>';
				$telf4=$operador['celular_dos']==''? '':'  |  [CEL2]: <u>' . $operador['celular_dos'] . '</u>';
				$email= $operador['correo']==''? '':'<hr/><label>Correo electrónico: </label>' . $operador['correo'];
				$orqui=$operador['registro_orquideas']==''? '':'<label>Registro de orquídeas: </label>' . $operador['registro_orquideas']; 
				$mad=$operador['registro_madera']==''? '':'<label>Registro de madera: </label>' . $operador['registro_madera'];
				$gs1=$operador['gs1']==''? '':'<label>Código GS1: </label>' . $operador['gs1'];
				
	$datosCliente = '<fieldset>
		<legend>
		 Datos del Cliente
		</legend>
		<div data-linea="1">
		<h2>Razón social: '. $operador['razon_social'].'</h2>
		    </div>
		    <div data-linea="3">
		        <label>RUC/CI:</label>
		        <span>'. $operador['identificador'].'</span>
		        <span>(Persona'.$operador['tipo_operador'].')</span>
		    </div>
		    <div data-linea="5">
		        <label>Representante legal: </label>
		        <span>'.$operador['apellido_representante'] . ', ' . $operador['nombre_representante'].'</span>
		    </div>
		    <div data-linea="7">
		        <label>Dirección (según RUC): </label>
		        <span>'.$operador['provincia'] . ' - ' . $operador['canton'] . ' (' . $operador['parroquia'] . '), ' . $operador['direccion'].'</span>
		    </div>
		    <hr/>
		    <div data-linea="9">
	 
		       <label>Teléfonos:</label>
		       <span>[TF1]: <u>' . $operador['telefono_uno'] .'</u>'.
		                     $telf1.
		                     $telf2.
		                     $telf3.
		                     $telf4.'
		       </span>
		    </div>  
		    <div data-linea="11">      
		        <span>'.$email.'</u></span>
		    </div>
		    <div data-linea="13">      
		        <span>'.$orqui. '</u></span>
		    </div>
		    <div data-linea="13">        
		        <span>'.$mad. '</u></span>
		    </div>
		    <div data-linea="13">        
		        <span>'.$gs1. '</u></span>
		    </div>
		    <hr/>    
		</fieldset>';
	return $datosCliente;
	}
	
	public function datosAsociacion($operador)
	{
	    $datosCliente = '<fieldset>
		<legend>
		 Datos del Cliente
		</legend>
		<div data-linea="1">
		<h2>Razón social: '. $operador['razon_social'].'</h2>
		    </div>
		    <div data-linea="2">
		        <label>RUC/CI:</label>
		        <span>'. $operador['identificador'].'</span>
		        <span>(Asociación)</span>
		    </div>
		    <div data-linea="3">
		        <label>Representante legal: </label>
		        <span>'.$operador['identificador_representante_legal'].' - '.$operador['nombre_representante_legal'].'</span>
		    </div>
		    <div data-linea="4">
		        <label>Dirección (según RUC): </label>
		        <span>'.$operador['provincia'] . ' - ' . $operador['canton'] . ' (' . $operador['parroquia'] . '), ' . $operador['direccion'].'</span>
		    </div>
		    <div data-linea="5">		            
		       <label>Teléfono:</label>
		       <span><u>' . $operador['telefono'] .'</u></span>
		    </div>
		    <div data-linea="6">
		        <label>Correo electrónico: </label>' . $operador['correo'].'
		    </div>
		    <hr/>

		</fieldset>';
		       return $datosCliente;
	}
	
	public function obtenerFechaInicoEstadoOperacion($conexion, $idOperadorTipoOperacion, $idOperacion, $estadoActual, $estadoAnterior, $fecha){
	    
	    $consulta = "SELECT 
                    	* 
                    FROM 
                    	g_operadores.auditoia_operaciones
                    WHERE 
                    	id_auditoria_operacion = (SELECT  
                    					max(id_auditoria_operacion)
                    				FROM 
                    					g_operadores.auditoia_operaciones aop1
                    				WHERE 
                    					aop1.id_operador_tipo_operacion = $idOperadorTipoOperacion 
                    					and aop1.id_operacion = $idOperacion
                    					and aop1.id_auditoria_operacion < (SELECT 
                                                                                id_auditoria_operacion 
                                    									   FROM 
                                                                                g_operadores.auditoia_operaciones aop2
                                    									   WHERE
                                        										aop2.estado_anterior = '$estadoAnterior' and
                                        										aop2.estado_actual = '$estadoActual' and 
                                        										aop2.id_operador_tipo_operacion = $idOperadorTipoOperacion and 
                                        										aop2.id_operacion = $idOperacion and
                                        										to_char(aop2.fecha, 'YYYY-MM-DD HH24:MI') = to_char('$fecha'::timestamp, 'YYYY-MM-DD HH24:MI')))";

	    $res = $conexion->ejecutarConsulta($consulta);

	    return $res;
	}
	
	public function obtenerFlujoOperacionEstadoActualEstadoAnterior($conexion, $idFlujoOperacion, $estado){
	    
	    $consulta = "SELECT
                        (SELECT estado FROM g_operadores.flujos_operaciones fo1 WHERE fo1.id_fase = fo.antecesor and fo1.id_flujo = fo.id_flujo) as anterior,
                        estado as actual,
                        (SELECT estado FROM g_operadores.flujos_operaciones fo1 WHERE fo1.id_fase = fo.predecesor and fo1.id_flujo = fo.id_flujo) as predecesor
                    FROM 
                        g_operadores.flujos_operaciones fo
                    WHERE 
                        id_flujo = $idFlujoOperacion
                        and estado = '$estado';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerFechasAuditoriaRegistroOperador($conexion, $idOperadorTipoOperacion, $estadoActual, $estadoAnterior){
	    
	    $consulta = " SELECT
                    	    *
                        FROM
                    	    g_operadores.auditoia_operaciones
                    	WHERE
                    	    estado_anterior = '$estadoAnterior' and
                    	    estado_actual = '$estadoActual' and
                    	    id_operador_tipo_operacion = '$idOperadorTipoOperacion';";

	    $res = $conexion->ejecutarConsulta($consulta);

	    return $res;

	}
	
	public function obtenerGrupoUltimaRevisionXTipo($conexion, $idSolicitud, $tipoSolicitud, $tipoInspector){
	    
	    $res = $conexion->ejecutarConsulta("SELECT 
                                                *
                        					FROM
                        						g_revision_solicitudes.grupos_solicitudes s,
                        						g_revision_solicitudes.asignacion_inspector a
                        					WHERE
                        						s.id_solicitud='$idSolicitud' AND
                        						s.id_grupo = a.id_grupo AND
                        						a.tipo_solicitud='$tipoSolicitud' AND
                        						a.tipo_inspector='$tipoInspector'
                        					ORDER BY
                                                1 desc
                        					LIMIT 1;");
	    
	    return $res;
	    
	}
}