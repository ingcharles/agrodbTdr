<?php

class ControladorClv
{
	public function listarOpClv($conexion, $idClv, $idArea, $tipoOperacion){
	
		$res = array();
		
		$Titular = $conexion->ejecutarConsulta("select distinct
													d.identificador,
													d.razon_social as nombre,
													d.direccion,
													t.nombre as tipo_operador
												from
													g_clv.certificado_clv c,
													g_operadores.operaciones o,
													g_catalogos.tipos_operacion t,
													g_operadores.operadores d
												where
													c.id_producto = o.id_producto and
													--o.id_tipo_operacion = t.id_tipo_operacion and
													o.identificador_operador = d.identificador and
													t.id_area = c.tipo_producto and
													o.estado = 'registrado' and
													c.id_clv = $idClv and
													t.id_area = '$idArea' and
													t.nombre = '$tipoOperacion';");

		while ($fila = pg_fetch_assoc($Titular)){
			$res[] = array('identificador' => $fila['identificador'], 'nombre' => $fila['nombre'], 'direccion' => $fila['direccion'],
							'tipo_operador' => $fila['tipo_operador']);
		}
	
		return $res;
	
	}
	
	
	public function actualizarClvVeterinario($conexion,$nombre,$direccion,
												$subpartida_arancelaria,$nombre_comercial_producto,
												$presentacion,$clasificacion,$formulacion,$uso,$especies,
												$numero_registro_agrocalidad,
												$observacion_clv,$id_clv, $fechaInscripcion, $fechaVigencia, $codigoProducto){
	
			$res = $conexion->ejecutarConsulta("UPDATE 
												g_clv.certificado_clv
											SET
												nombre_datos_certificado = '$nombre',
							  					direccion_datos_certificado = '$direccion',
												subpartida_arancelaria= '$subpartida_arancelaria',
												nombre_comercial_producto= '$nombre_comercial_producto',
								 			 	presentacion_comercial_producto='$presentacion',
												clasificacion_producto= '$clasificacion',
												formulacion= '$formulacion',
												forma_farmaceutica= '$formulacion',
												uso_producto= '$uso',	
												especie_destino= '$especies',
												numero_registro_agrocalidad= '$numero_registro_agrocalidad',
												observacion_clv= '$observacion_clv',
												fecha_inscripcion_producto = '$fechaInscripcion',
												fecha_vigencia_producto = '$fechaVigencia',
												codigo_producto = '$codigoProducto'												
											WHERE 	
												id_clv = '$id_clv';");
		return $res;
	}
	
	public function actualizarClvPlaguicida($conexion, $nombre, $direccion, $subpartida_arancelaria,
												$nombre_comercial_producto, $codigo_producto, $formulacion,$numero_registro_agrocalidad,
												$observacion_clv,$id_clv, $fechaInscripcion, $fechaVigencia){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_clv.certificado_clv
											SET
												nombre_datos_certificado = '$nombre',
												direccion_datos_certificado = '$direccion',
												subpartida_arancelaria= '$subpartida_arancelaria',
												nombre_comercial_producto= '$nombre_comercial_producto',
								                codigo_producto= '$codigo_producto',
												formulacion= '$formulacion',
												forma_farmaceutica= '$formulacion',
												numero_registro_agrocalidad= '$numero_registro_agrocalidad',
												observacion_clv= '$observacion_clv',
												fecha_inscripcion_producto = '$fechaInscripcion',
												fecha_vigencia_producto = '$fechaVigencia'				
											WHERE
												id_clv = '$id_clv';");
				return $res;
	}
	
	
	//listado de titulares segun el tipo de operador => Formulador - Fabricante
	public function listarTitularClv($conexion){
		$Titular = $conexion->ejecutarConsulta("select distinct 
													d.identificador,
												    (d.nombre_representante || ' ' || d.apellido_representante) nombre,
													d.direccion, 
													t.nombre tipo_operador
												from 
													g_operadores.operaciones o,
													g_catalogos.tipos_operacion t,
													g_operadores.operadores d
												where 
													o.id_tipo_operacion = t.id_tipo_operacion
													and o.identificador_operador = d.identificador
													and t.id_area in ('IAP', 'IAV') and 
													t.nombre in ('Formulador','Fabricante')
													and o.estado = 'registrado'");
		
							while ($fila = pg_fetch_assoc($Titular)){
								$res[] = array(
										identificador => $fila['identificador'],
										nombre => $fila['nombre'],										
										direccion => $fila['direccion'],										
										tipo_operador => $fila['tipo_operador']);
									
							}
							
		return $res;
		
	}
	
	public function listarCertificadoClv($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("select 
													c.*
											from
													g_clv.certificado_clv as c
											where c.identificador_titulares = '$identificador';");
		return $res;
	}
	
	public function listarCertificadoClv2($conexion){
		$res = $conexion->ejecutarConsulta("select
													c.*
											from
													g_clv.certificado_clv as c
											where c.identificador_operador is not null");
		//where 	c.identificador_operador = '$identificador';");
		return $res;
	}
	
	
	
	public function listarCertificados($conexion, $id_clv){
		
		$res = array();
		
			$lc = $conexion->ejecutarConsulta("select 
													*
												from 
													g_clv.certificado_clv 
												where 
													id_clv = '$id_clv';");
			
			while ($fila = pg_fetch_assoc($lc)){
				$res[] = array(
						'idClv' => $fila['id_clv'],
						'idTitular' => $fila['identificador_titulares'],
						'tipoProducto' => $fila['tipo_producto'],
						'id_producto' => $fila['id_producto'],
						'nombre_producto' => $fila['nombre_producto'],
						'tipoDatoCertificado' => $fila['tipo_datos_certificado'],
						'nombreDatoCertificado' => $fila['nombre_datos_certificado'],
						'direccionDatoCertificado' => $fila['direccion_datos_certificado'],
						'fechaInscripcionProducto' => $fila['fecha_inscripcion_producto'],
						'fechaVigenciaProducto' => $fila['fecha_vigencia_producto'],
						'nombreComercial' => $fila['nombre_comercial_producto'],
						'presentacionComercial' => $fila['presentacion_comercial_producto'],
						'clasificacionProducto' => $fila['clasificacion_producto'],
						'formaFarmaceutica' => $fila['formulacion'],
						'usoProducto' => $fila['uso_producto'],
						'formulacion' => $fila['formulacion'],
						'codigo_certificado' => $fila['codigo_certificado'],
						'especie' => $fila['especie_destino'],
						'estado' => $fila['estado'],
						'idVue' => $fila['id_vue'],
						'subpartida' => $fila['subpartida_arancelaria']
						);
			}
			return $res;
		}
	
		public function listarDetalleCertificados($conexion, $id_clv){
			
			$res = array();
			
			$lc = $conexion->ejecutarConsulta("select 
														c.id_clv,
														c.id_producto,
					                                    c.nombre_producto,
														dc.id_clv,
														dc.ingrediente_activo,
														dc.concentracion,
														dc.composicion_declarada,
														dc.cantidad_composicion,
														dc.unidad_medida,
														dc.descripcion_composicion,
														dc.estado,
														dc.observacion,
														dc.id_certificado_clv_composicion_producto
													from
														g_clv.certificado_clv c,
														g_clv.certificado_clv_composicion_producto dc
													where
														c.id_clv= dc.id_clv
													and     dc.id_clv = '$id_clv';");
										
										while ($fila = pg_fetch_assoc($lc)){
											$res[] = array(
													'idClvComponente' => $fila['id_certificado_clv_composicion_producto'],
													'idClv' => $fila['id_clv'],
													'id_producto' => $fila['id_producto'],
													'nombre_producto' => $fila['nombre_producto'],
													'ingredienteActivo' => $fila['ingrediente_activo'],
													'concentracion' => $fila['concentracion'],
													'composicionDeclarada' => $fila['composicion_declarada'],
													'cantidadComposicion' => $fila['cantidad_composicion'],
													'unidadMedida' => $fila['unidad_medida'],
													'descripcionComposicion' => $fila['descripcion_composicion'],
													'estado' => $fila['estado'],
													'observacion' => $fila['observacion']);
										}
			return $res;
		}
		
		
		public function listarDocumentos($conexion, $id_clv){
			
			$res = array();
			
			   $lc = $conexion->ejecutarConsulta("select
											            *
											       from 
			   											g_clv.documentos_adjuntos
											       where  
			   											id_clv = '$id_clv';");
											  
											   while ($fila = pg_fetch_assoc($lc)){
											    $res[] = array(
											          'idClv'=>$fila['id_clv'],    
											          'tipoArchivo'=>$fila['tipo_archivo'],
											          'rutaArchivo'=>$fila['ruta_archivo'],
											          'area'=>$fila['area'],
											          'idVue'=>$fila['id_vue']);
			   }
			  
			   return $res;
		  }
		
		public function guardarCertificadoProducto($conexion, $identificador_titulares ,$tipo_producto,$tipo_datos_certificado,$codigoCertificado,
		            									 $id_producto,$nombre_producto,$estado, $formulacionPlaguicida=null, $idVue = null, $subPartidaProducto=null, $codigoProducto=null){
		   
		   $res = $conexion->ejecutarConsulta("INSERT INTO g_clv.certificado_clv(
								                   identificador_titulares, tipo_producto,
								                   tipo_datos_certificado, codigo_certificado,id_producto,nombre_producto,estado, 
								                   formulacion, id_vue, subpartida_producto_vue, codigo_producto_vue)
								                VALUES ('$identificador_titulares','$tipo_producto',
								                  '$tipo_datos_certificado','$codigoCertificado','$id_producto','$nombre_producto','$estado', 
								                  '$formulacionPlaguicida','$idVue', '$subPartidaProducto', '$codigoProducto')
								                RETURNING id_clv;");
		     return $res;
		  }
		
		public function actualizarCertificadoPlaguicida($conexion,$identificador_operador,$id_producto,$nombre_producto,
				$subpartida_arancelaria,$codigo_producto,$nombre_comercial_producto,$formulacion,$numero_registro_agrocalidad,
				$observacion_clv, $id_clv){
		
			$res = $conexion->ejecutarConsulta("UPDATE g_clv.certificado_clv
															   		SET 
																		identificador_operador= '$identificador_operador', 
																		id_producto= $id_producto,
																		nombre_producto= '$nombre_producto',
																		subpartida_arancelaria= '$subpartida_arancelaria',
																		codigo_producto= '$codigo_producto',
																		nombre_comercial_producto= '$nombre_comercial_producto', 
																		formulacion= '$formulacion',
																		numero_registro_agrocalidad= '$numero_registro_agrocalidad' ,	
																		observacion_clv= '$observacion_clv'
																 WHERE 	id_clv = '$id_clv';");
		return $res;			
		}
					
		public function actualizarCertificadoVeterinario($conexion, $idcProducto, $identificador_operador, $id_producto, $nombre_producto,
				$subpartida_arancelaria,$clasificacion_producto,$nombre_comercial_producto,$formulacion,
				$id_presentacion_comercial_producto,$presentacion_comercial_producto,$uso_producto,$especie_destino,
				$id_pais,$nombre_pais,$numero_registro_agrocalidad,$observacion_clv){
							
			$res = $conexion->ejecutarConsulta("UPDATE g_clv.certificado_clv
																SET
																		identificador_operador= '$identificador_operador', 
																		id_producto= '$id_producto',
																		nombre_producto= '$nombre_producto',
																		subpartida_arancelaria= '$subpartida_arancelaria',
																		clasificacion_producto= '$clasificacion_producto',
																		nombre_comercial_producto= '$nombre_comercial_producto',
																		formulacion= '$formulacion', 
																		id_presentacion_comercial_producto= '$id_presentacion_comercial_producto',
																		presentacion_comercial_producto= '$presentacion_comercial_producto',
																		uso_producto= '$uso_producto',
																		especie_destino= '$especie_destino', 
																		id_pais= '$id_pais', 
																		nombre_pais= '$nombre_pais',
																		numero_registro_agrocalidad= '$numero_registro_agrocalidad',
																		observacion_clv= '$observacion_clv'
																WHERE 	id_clv = '$idcProducto';");
			return $res;
		}
		
		public function guardarClvArchivos($conexion, $id_clv, $tipo_archivo, $ruta_archivo, $area, $idVue = null){
			
			$documento = $this->abrirClvArchivoIndividual($conexion, $id_clv, $tipo_archivo);
			
			if(pg_num_rows($documento)== 0){
				
				$res = $conexion->ejecutarConsulta("INSERT INTO g_clv.documentos_adjuntos(
						id_clv, tipo_archivo, ruta_archivo, area, id_vue)
						VALUES (
						'$id_clv', '$tipo_archivo', '$ruta_archivo', '$area', '$idVue');");
				
				return $res;
			}
			
		}
		
		public function abrirClvArchivoIndividual($conexion, $idClv, $tipoArchivo){
		
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_clv.documentos_adjuntos
												WHERE
													id_clv = $idClv
													and tipo_archivo = '$tipoArchivo';");
		
			return $res;
		}
		
	
		public function guardarDetalleCertificadoProductoP($conexion, $idCProducto,$ingrediente_activo,$concentracion, $unidad){
					$res = $conexion->ejecutarConsulta("INSERT INTO g_clv.certificado_clv_composicion_producto(
																				id_clv,ingrediente_activo,concentracion, unidad_medida)
																				VALUES ($idCProducto,'$ingrediente_activo',
																				'$concentracion', '$unidad') returning id_certificado_clv_composicion_producto;");
			return $res;
		}
		
		public function guardarDetalleCertificadoProductoV($conexion, $idCProducto,$composicion_declarada,$cantidad,$unidad,$descripcion_composicion){
					$res = $conexion->ejecutarConsulta("INSERT INTO g_clv.certificado_clv_composicion_producto(
																				id_clv,composicion_declarada,cantidad_composicion,unidad_medida, descripcion_composicion)
																				VALUES ($idCProducto,'$composicion_declarada',
																				'$cantidad','$unidad','$descripcion_composicion');");
			return $res;
		}		
		
		public function  generarNumeroCertificado($conexion,$codigo){
				$res = $conexion->ejecutarConsulta("SELECT
														MAX(codigo_certificado) as numero
														FROM
														    g_clv.certificado_clv
														WHERE
														codigo_certificado LIKE '$codigo';");
			return $res;
		}

	public function listarComposicionProducto ($conexion, $producto){
		$res = $conexion->ejecutarConsulta("select 
											        distinct c.id_producto,
											        c.composicion,
											        c.presentacion,
											        c.formulacion             
											from 
												g_catalogos.productos p, 
												g_catalogos.subtipo_productos s, 
												g_catalogos.tipo_productos t,
												g_catalogos.composicion_productos c
											where 
												p.id_subtipo_producto = s.id_subtipo_producto and 
												s.id_tipo_producto = t.id_tipo_producto and 
												t.id_area in ('IAP', 'IAV') and 
												p.id_producto = c.id_producto and
												p.id_producto = $producto 
											order by 
											    c.composicion asc");
		return $res;
	}
	
	public function listaPresentacionProductosInocuidadFiltro($conexion, $id_clv){
		$li = $conexion->ejecutarConsulta("select 
												distinct i.id_producto,
											    i.subcodigo,
											    i.presentacion,
												i.unidad_medida   
											from 
												g_clv.certificado_clv c, 
											    g_catalogos.codigos_inocuidad i
											where 
												i.id_producto = c.id_producto and
											    c.id_clv = '$id_clv';");
		
										while ($fila = pg_fetch_assoc($li)){
											$res[] = array(
													'id_producto'=>$fila['id_producto'],
													'subcodigo'=>$fila['subcodigo'],
													'presentacion'=>$fila['presentacion'],
													'unidad'=>$fila['unidad_medida']);
										}
		return $res;
	
	}
	
		
	public function listaComposicionProducto($conexion){
		$cComposicion = $conexion->ejecutarConsulta("SELECT id_composiciones_declarada, 
														       nombre
														  FROM g_catalogos.composiciones_declaradas;");
	
		while ($fila = pg_fetch_assoc($cComposicion)){
			$res[] = array(
					id_composiciones_declarada=>$fila['id_composiciones_declarada'],
					nombre=>$fila['nombre']
			);
		}
	
		return $res;
	
	}
	
public function listaProdInocuidad($conexion, $id_clv){
	
	$res = array();
				
		$cidProInocidad = $conexion->ejecutarConsulta("select 
															distinct t.nombre as tipo_producto, 
															p.id_producto,
															p.codigo_producto,
															p.partida_arancelaria subpartida,
															s.nombre clasificacion,
															p.nombre_comun producto,
															pi.composicion as composicion_guia,
															pi.formulacion as formulacion_guia,
															pi.numero_registro,
															pi.fecha_registro,
															t.id_area,
															c.formulacion
														from 
															g_clv.certificado_clv c,
															g_catalogos.productos p,
															g_catalogos.subtipo_productos s,
															g_catalogos.tipo_productos t,
															g_catalogos.productos_inocuidad pi
														where 
															c.id_producto = p.id_producto and
															p.id_subtipo_producto = s.id_subtipo_producto and
															s.id_tipo_producto = t.id_tipo_producto and
															t.id_area in ('IAP', 'IAV') and
															p.id_producto = pi.id_producto and
															c.id_clv = $id_clv");
	
		while ($fila = pg_fetch_assoc($cidProInocidad)){
			$res[] = array(
					'tipo_producto'=>$fila['tipo_producto'],
					'id_producto'=>$fila['id_producto'],
					'codigo_producto'=>$fila['codigo_producto'],
					'subpartida'=>$fila['subpartida'],
					'clasificacion'=>$fila['clasificacion'],
					'producto'=>$fila['producto'],
					'nombre_cientifico'=>$fila['nombre_cientifico'],
					'formulacion'=>$fila['formulacion'],
					'id_area'=>$fila['id_area'],
					'formulacionGuia'=>$fila['formulacion_guia'],
					'composicionGuia'=>$fila['composicion_guia'],
					'numero_registro' => $fila['numero_registro'],
					'fecha_registro' => $fila['fecha_registro']
			);
		}
	
		return $res;
	
	}
		
	
	////////////////////////////// REVISION DE CLV EN FINANCIERO ///////////////////////////////////
	
	/*filtro de clv por estado*/
	public function listarClvRevisionFinancieroRS ($conexion, $estado='pago'){
	
		$res = $conexion->ejecutarConsulta("select
									           distinct f.id_clv as id_solicitud,
									           f.identificador_titulares as identificador_operador,
									           f.nombre_pais as pais,
									           f.estado,
												f.id_vue
									         from
									           g_clv.certificado_clv f
									         where
									           f.estado in ('$estado')
											order by 1 asc;");
		return $res;
	}
	
	
	////////////////////// EVALUACION DE PRODUCTOS ZOO //////////////////////////
	/*OK*/
	public function enviarClv ($conexion, $idClv, $estado){

		$res = $conexion->ejecutarConsulta("update
											g_clv.certificado_clv
										set
											estado = '$estado'
										where
											id_clv = $idClv;");
			return $res;
	}
	
	////////////////////////////// ASIGNACION DE CLV A INSPECTORES PARA REVISION ///////////////////////////////////
	
	/*filtro de operaciones por provincia por asignar*/
	public function listarClvRevisionProvinciaRS ($conexion, $estado){
	 
	 $res = $conexion->ejecutarConsulta("select
								           distinct f.id_clv as id_solicitud,
								           f.identificador_titulares as identificador_operador,
								           f.nombre_pais as pais,
								           f.estado,
	 									   f.id_vue
								         from
								           g_clv.certificado_clv f
								         where
								           f.estado in ('$estado')
	 									order by 1 asc;");
	   return $res;
	 }
 
	public function listarClvAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoOperacion, $tipoInspector){
			
			$res = $conexion->ejecutarConsulta("select
													distinct f.id_clv as id_solicitud,
													f.identificador_titulares as identificador_operador,
													f.nombre_pais as pais,
													f.estado,
													f.id_vue
												from
													g_clv.certificado_clv f,
													--g_clv.certificado_clv_composicion_producto fp,
													g_revision_solicitudes.asignacion_coordinador ac
												where
													--f.id_clv = fp.id_clv and
													f.id_clv = ac.id_solicitud and
													ac.identificador_inspector = '$identificadorInspector' and
													ac.tipo_solicitud = '$tipoOperacion' and
													ac.tipo_inspector = '$tipoInspector' and
													f.estado in ('$estado')
												order by 1 asc;");
		return $res;
	}
	
	/*REVISAR CONSULTA PENDIENTE*/
	public function listarProductosVeterinarios ($conexion, $idSitio){
		$res = $conexion->ejecutarConsulta("select
											a.*,
											lo.unidad_medida
											from
											g_operadores.sitios s,
											g_operadores.areas a,
											g_catalogos.areas_operacion lo
											where
											a.id_sitio = s.id_sitio
											and s.id_sitio = $idSitio
											and a.tipo_area = lo.nombre
											group by
											a.id_area, lo.unidad_medida;");

		return $res;
	}
	
	
	public function buscarClvVUE($conexion, $id_vue){
			
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_clv.certificado_clv
											where
												id_vue = '$id_vue';");
	
		return $res;
	}
	
	public function eliminarArchivosAdjuntos($conexion, $idClv, $idVue){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_clv.documentos_adjuntos
											WHERE
												id_clv = $idClv
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	public function evaluarProductosCLV ($conexion, $idCLV, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_clv.certificado_clv_composicion_producto
											set
												estado = '$estado'
											where
												id_clv = $idCLV;");
		return $res;
	}
	
	public function enviarFechaVigenciaCLV ($conexion, $idCLV){
		$res = $conexion->ejecutarConsulta("update
												g_clv.certificado_clv
											set
												fecha_inicio = now(),
												fecha_vencimiento = now() +  '6 month'
											where
												id_clv = $idCLV;");
		return $res;
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function buscarClv ($conexion, $idVue){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_clv.certificado_clv
											WHERE
												id_vue = '$idVue';");
	
		return $res;
	}
	
	public function eliminarDetalleProductos ($conexion, $idComposicion){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_clv.certificado_clv_composicion_producto
											WHERE
												id_certificado_clv_composicion_producto = $idComposicion;");
	
				return $res;
	}
	
	public function eliminarDetalleProductosClv ($conexion, $idClv){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_clv.certificado_clv_composicion_producto
											WHERE
												id_clv = $idClv;");
		
		return $res;
	}
	
	public function actualizarClv ($conexion, $idClv, $identificadorTitulares, $areaProducto, $tipoDatosCertificado, $idProducto, $nombreProducto, $estado, $subPartidaProducto=null, $codigoProducto=null){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_clv.certificado_clv
											SET
												identificador_titulares='$identificadorTitulares', tipo_producto='$areaProducto',
												tipo_datos_certificado='$tipoDatosCertificado', id_producto=$idProducto, 
												nombre_producto='$nombreProducto', estado = '$estado',
												subpartida_producto_vue = '$subPartidaProducto', codigo_producto_vue = '$codigoProducto'
											WHERE
												id_clv=$idClv;");
	
		return $res;
	
	}
	
	public function imprimirLineaComponente($idClvComposicion, $ingredienteActivo, $concentracion, $unidad){
		return '<tr id="R' . $idClvComposicion . '">' .
				'<td width="100%">' .
				$ingredienteActivo . ' - ' . $concentracion.' '.$unidad.
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="certificadoLibreVenta" data-opcion="quitarConcentracion">' .
				'<input type="hidden" name="idClvConcentracion" value="' . $idClvComposicion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarConcentracion ($conexion, $idClv, $ingrediente, $concentracion, $unidad){
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_clv.certificado_clv_composicion_producto
											where
												id_clv = $idClv 
												and ingrediente_activo = '$ingrediente' 
												and concentracion = '$concentracion'
												and unidad_medida = '$unidad';");
	
		return $res;
	}
	
	public function abrirClvAsignacion ($conexion, $idSolicitud){
			
		$res = $conexion->ejecutarConsulta("select
												i.id_clv as id_solicitud,
												i.identificador_titulares as identificador,
												i.nombre_pais as pais,
												i.tipo_producto as tipo_solicitud,
												i.estado,
												case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
											from
												g_clv.certificado_clv i,
												g_operadores.operadores o
											where
												i.id_clv = $idSolicitud and
												i.identificador_titulares = o.identificador");
	
				return $res;
	}
	
	public function abrirClvProductosAsignacion ($conexion, $idSolicitud){
	
	$cid = $conexion->ejecutarConsulta("select
											nombre_producto
										from
											g_clv.certificado_clv
										where
											id_clv = $idSolicitud");
	
			while ($fila = pg_fetch_assoc($cid)){
			$prod[] = $fila['nombre_producto'];
	}
	
	$res = implode(', ',$prod);
	
	return $res;
	}
	
}	
	