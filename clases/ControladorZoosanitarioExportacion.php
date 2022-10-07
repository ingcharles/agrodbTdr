<?php

class ControladorZoosanitarioExportacion{
	
	
	public function listarPaisesAutorizadosOperador ($conexion){
		
		
		$res = $conexion->ejecutarConsulta("select distinct op.id_pais,
										            op.nombre_pais  
											from
										            g_operadores.operaciones op,
										            g_requisitos.requisitos_comercializacion rc,
										            g_requisitos.requisitos_asignados ra
											where
										            op.id_tipo_operacion in
										             (select
										               	id_tipo_operacion
										             from 
										               	g_catalogos.tipos_operacion 
										             where nombre = 'Exportador' and id_area = 'SA') 
											and
										           	rc.id_producto = op.id_producto and
										           	rc.id_requisito_comercio = ra.id_requisito_comercio 
											and
										           ra.tipo = 'Exportación' 
											and
										           op.estado = 'registrado'            
										           order by 2 asc");
		
		return $res;
	}
	public function listarProductos($conexion){
				
			$cidProductos = $this->Productos($conexion);
			while ($fila = pg_fetch_assoc($cidProductos)){
				$res[] = array(id_producto=>$fila['id_producto'], nombre_producto=>$fila['nombre_producto'], id_pais=>$fila['id_pais']);
			}
			return $res;
	}
	
	
	public function Productos($conexion)
	{
		$res = $conexion->ejecutarConsulta("select distinct op.id_producto,
												               	op.nombre_producto,
												            	op.id_pais
												           from
												              g_operadores.operaciones op,
												               g_requisitos.requisitos_comercializacion rc,
												            	g_requisitos.requisitos_asignados ra
												           where
												            	op.id_tipo_operacion in
												             	(select
												               		id_tipo_operacion
												           from
												               	g_catalogos.tipos_operacion
												           where nombre = 'Exportador' and id_area = 'SA') and
												           			 rc.id_producto = op.id_producto and
												            		rc.id_requisito_comercio = ra.id_requisito_comercio and
												            		ra.tipo = 'Exportacion' and
												            		op.estado = 'registrado' ");
			return $res;
	}
	
	
	
	
	public function  generarNumeroSolicitud($conexion,$codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(codigo_certificado) as numero
												FROM
													g_zoo_exportacion.zoo_exportaciones
												WHERE
													codigo_certificado LIKE '$codigo';");
		return $res;
	}
	
	public function guardarNuevaExportacion($conexion, $identificadorOperador, $nombreTecnico, $apellidoTecnico, $idPuertoEmbarque, $puertoEmbarque, 
											$transporte, $numeroBultos, $descripcionBultos, $codigoSitio, $codigoCertificado, 
											$observacion, $nombreImportador, $direccionImportador,  $idPaisDestino, 
											 $paisDestino, $usoProducto, $idPaisEmbarque, $paisEmbarque, $idPuertoDestino, $puertoDestino, $fechaInspeccion ,$idVue=null){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_zoo_exportacion.zoo_exportaciones(
									                     identificador_operador, nombre_tecnico, apellido_tecnico, 
									                     id_puerto_embarque, puerto_embarque, transporte, numero_bultos, 
									                     descripcion_bultos, codigo_sitio, codigo_certificado, 
									                     observacion, nombre_importador, direccion_importador, 
									                     id_pais_destino, pais_destino, uso_producto, id_pais_embarque, pais_embarque, 
														 id_puerto_destino, puerto_destino, estado,fecha_inspeccion, id_vue)
									           VALUES ('$identificadorOperador', '$nombreTecnico', '$apellidoTecnico', 
									                     $idPuertoEmbarque, '$puertoEmbarque', '$transporte', $numeroBultos, 
									                     '$descripcionBultos', '$codigoSitio', '$codigoCertificado', 
									                     '$observacion', '$nombreImportador', '$direccionImportador', 
									                     $idPaisDestino, '$paisDestino', $usoProducto,
														 $idPaisEmbarque, '$paisEmbarque', $idPuertoDestino, 
									                     '$puertoDestino','enviado','$fechaInspeccion','$idVue') RETURNING id_zoo_exportacion;");
	  return $res;
	 }
		
	public function guardarExportacionesProductos($conexion,$id_zoo_exportacion ,$idProducto, $nombreProducto, $raza, $sexo, $edad, $cantidadFisica, $unidadFisica, $idPaisOrigen, $nombrePaisOrigen, $subPartidaProducto=null, $codigoProducto=null){
		
		$edad = ($edad == ''?0:$edad);
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_zoo_exportacion.zoo_exportaciones_productos(id_zoo_exportacion,id_producto,nombre_producto,
																	raza, sexo, edad, cantidad_fisica,unidad_fisica, id_pais_origen, pais_origen, subpartida_producto_vue, codigo_producto_vue)
										    VALUES ($id_zoo_exportacion,$idProducto, '$nombreProducto',
																	'$raza', '$sexo', $edad, $cantidadFisica, '$unidadFisica', $idPaisOrigen, '$nombrePaisOrigen', '$subPartidaProducto', '$codigoProducto'); ");
		
		return $res;
	}
	
	public function guardarExportacionesArchivos($conexion, $idExportacion,$tipoArchivo, $rutaArchivo, $area, $idVue = null){
		
		$documento = $this->abrirZoosanitarioArchivoIndividual($conexion, $idExportacion, $tipoArchivo);
		
		if(pg_num_rows($documento)== 0){
		
			$res = $conexion->ejecutarConsulta("INSERT INTO g_zoo_exportacion.documentos_adjuntos(id_zoo_exportacion,tipo_archivo, ruta_archivo, area, id_vue)
											   				 VALUES 		($idExportacion,'$tipoArchivo', '$rutaArchivo', '$area', '$idVue');");
	
		return $res;
		}
	}
	
	public function abrirZoosanitarioArchivoIndividual($conexion, $idExportacion, $tipoArchivo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_zoo_exportacion.documentos_adjuntos
											WHERE
												id_zoo_exportacion = $idExportacion
												and tipo_archivo = '$tipoArchivo';");
	
		return $res;
	}
	
	public function listarExportacionesOperador($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("select 
												e.*, 
												(select count(ex.id_zoo_exportacion_producto) 
												from g_zoo_exportacion.zoo_exportaciones_productos ex 
												where ex.id_zoo_exportacion = e.id_zoo_exportacion) as num_productos
											from
												g_zoo_exportacion.zoo_exportaciones e
											where
												e.identificador_operador='$identificador'
											order by e.codigo_certificado;");
		
		return $res;
	}
	
		
		
/*public function abrirExportacion ($conexion, $idZoo){
		$cid = $conexion->ejecutarConsulta("select
												e.*,
												ex.*,
												o.*,
												p.partida_arancelaria,
												e.estado as estado_exportacion,
												ex.estado as estado_producto,
												e.observacion as observacion_exportacion,
												ex.observacion as observacion_producto,
												u.nombre_uso as nombre_uso,
												(t.rango_edad  || ' ' || t.unidad_rango) as nombre_edad
											from
												g_zoo_exportacion.zoo_exportaciones e,
												g_zoo_exportacion.zoo_exportaciones_productos ex,
												g_operadores.operadores o,
												g_catalogos.productos p,
												g_catalogos.usos u,
												g_catalogos.rangos_edades t
												
											where
												e.identificador_operador = o.identificador and
												e.id_zoo_exportacion = ex.id_zoo_exportacion and
												e.id_zoo_exportacion = $idZoo and
												e.uso_producto = u.id_uso and
												t.id_rango_edad = ex.edad and
												ex.id_producto = p.id_producto;");
	
				while ($fila = pg_fetch_assoc($cid)){
													$res[] = array(
													idVue=>$fila['id_vue'],
													idExportacion=>$fila['id_zoo_exportacion'],
													identificador=>$fila['identificador_operador'],
													representanteTecnico =>$fila['nombre_tecnico'],
																
													nombreImportador =>$fila['nombre_importador'],
													paisImportador =>$fila['pais_destino'],
													direccionImportador =>$fila['direccion_importador'],
													puertoEmbarque =>$fila['puerto_embarque'],
													medioTransporte =>$fila['transporte'],
													usoProducto =>$fila['nombre_uso'],
													bultos =>$fila['numero_bultos'],
													descripcion =>$fila['descripcion_bultos'],
													codigositio =>$fila['codigo_sitio'],
													fechaInspeccion =>$fila['fecha_inspeccion'],
													observacionInspeccion =>$fila['observacion_exportacion'],
													idProducto =>$fila['id_producto'],
													nombreProducto =>$fila['nombre_producto'],
													raza =>$fila['raza'],
													sexo =>$fila['sexo'],
													edad =>$fila['nombre_edad'],
													cantidadFisica =>$fila['cantidad_fisica'],
													unidadFisica =>$fila['nombre_unidad_fisica'],
													estadoExportacion =>$fila['estado_exportacion'],
													estadoProducto =>$fila['estado_producto'],
													rutaArchivo =>$fila['ruta_archivo'],
													observacionProducto =>$fila['observacion_producto'],
													archivoProducto =>$fila['ruta_archivo']
											);
				}
	
		return $res;
	}*/
	
	
	public function abrirZoo ($conexion, $idZoo){
		$res = $conexion->ejecutarConsulta("select
												e.*,
												u.*
											from
												g_zoo_exportacion.zoo_exportaciones e,
												g_catalogos.usos u
											where
												e.id_zoo_exportacion = $idZoo and
												e.uso_producto = u.id_uso;");
	
				return $res;
	}
	
	
	public function abrirZooProductos($conexion, $idZoo){
		$cid = $conexion->ejecutarConsulta("select
												zp.*,
												p.partida_arancelaria
											from
												g_zoo_exportacion.zoo_exportaciones_productos zp,
												g_catalogos.productos p
											where
												zp.id_zoo_exportacion = $idZoo and
												p.id_producto = zp.id_producto;");
	
				while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(idZoo=>$fila['id_zoo_exportacion'], idProducto=>$fila['id_producto'],
						nombreProducto=>$fila['nombre_producto'], raza=>$fila['raza'],
						sexo=>$fila['sexo'], edad=>$fila['edad'], cantidadFisica=>$fila['cantidad_fisica'],
							unidadFisica=>$fila['unidad_fisica'], paisOrigen=>$fila['pais_origen'],
							estado=>$fila['estado'], partidaArancelaria=>$fila['partida_arancelaria']);
		}
		return $res;
				}
	
		
	
	public function abrirExportacionesArchivos($conexion, $idExportacion){
		$cid = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_zoo_exportacion.documentos_adjuntos
											WHERE
												id_zoo_exportacion = $idExportacion;");
		
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
					idImportacion=>$fila['id_zoo_exportacion'],
					tipoArchivo=>$fila['tipo_archivo'],
					rutaArchivo=>$fila['ruta_archivo'],
					area=>$fila['area'],
					idVue=>$fila['id_vue']);
		}
		
		return $res;
	}
		
	
	////////////////////////////// ASIGNACION DE ZOO A INSPECTORES PARA REVISION ///////////////////////////////////
	
	/*filtro de operaciones por provincia por asignar*/
	public function listarZooRevisionProvinciaRS ($conexion, $estado, $provincia){
	 
	  $res = $conexion->ejecutarConsulta("select
									            distinct f.id_zoo_exportacion as id_solicitud,
									            f.identificador_operador,
									            f.pais_destino as pais,
									            f.estado,
									            o.razon_social, o.nombre_representante, o.apellido_representante,
									            s.provincia,
	  											f.id_vue
									       from
									            g_zoo_exportacion.zoo_exportaciones f,
									            g_operadores.operadores o,
									            g_zoo_exportacion.zoo_exportaciones_productos fp,
									            g_operadores.sitios s
									        where
									            f.id_zoo_exportacion = fp.id_zoo_exportacion and
									            f.identificador_operador = o.identificador and
									            f.estado in ('$estado') and
									            f.codigo_sitio = s.identificador_operador||'.'||s.codigo_provincia||s.codigo and
									            UPPER(s.provincia) = UPPER('$provincia');");
	  return $res;
	 }
		
	////////////////////// EVALUACION DE PRODUCTOS ZOO //////////////////////////
	/*OK*/
	public function enviarZoo ($conexion, $idZoosanitario, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_zoo_exportacion.zoo_exportaciones
											set
												estado = '$estado'
											where
												id_zoo_exportacion = $idZoosanitario;");
		return $res;
	}
	
	public function enviarZoosanitarioProductos ($conexion, $idZoosanitario, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_zoo_exportacion.zoo_exportaciones_productos
											set
												estado = '$estado'
											where
												id_zoo_exportacion = $idZoosanitario;");
		return $res;
	}
	
	/*OK*/
	public function evaluarProductosZoo ($conexion, $idZoo, $idProducto, $estado, $observacion, $informe){
		$res = $conexion->ejecutarConsulta("update
												g_zoo_exportacion.zoo_exportaciones_productos
											set
												estado = '$estado',
												observacion ='$observacion',
												ruta_archivo = '$informe'
											where
												id_producto = $idProducto and
												id_zoo_exportacion = $idZoo;");
		return $res;
	}
	
	/*OK*/
	public function abrirProductosZoo ($conexion, $idZoo){
		$cid = $conexion->ejecutarConsulta("select
												*
											from
												g_zoo_exportacion.zoo_exportaciones_productos
											where
												id_zoo_exportacion = $idZoo;");
					
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(idOperacion=>$fila['id_zoo_exportacion'],estado=>$fila['estado'],
			observacion=>$fila['observacion']);
		}
			
		return $res;
	}
	
	////////////////////////////// REVISION DE ZOO EN FINANCIERO ///////////////////////////////////
	
	/*filtro de zoo por estado*/
	public function listarZooRevisionFinancieroRS ($conexion, $nombreProvincia ,$estado='pago'){
		
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_zoo_exportacion as id_solicitud,
												i.identificador_operador,
												i.estado,
												i.pais_destino as pais,
												o.razon_social, o.nombre_representante, o.apellido_representante,
												i.id_vue
											from
												g_zoo_exportacion.zoo_exportaciones i,
												g_operadores.operadores o,
												g_zoo_exportacion.zoo_exportaciones_productos ip,
												g_operadores.sitios s
											where
												i.id_zoo_exportacion = ip.id_zoo_exportacion and
												i.identificador_operador = o.identificador and
												i.estado in ('$estado') and
												i.codigo_sitio = s.identificador_operador||'.'||s.codigo_provincia||s.codigo and
									            UPPER(s.provincia) = UPPER('$nombreProvincia')
											order by 1 asc");
		return $res;
	}
	
	public function listarInspectoresFinancierosAsignados ($conexion,$idImportacion){
			
		$res = $conexion->ejecutarConsulta("select
												ii.*,
												fe.nombre,
												fe.apellido
											from
												g_importaciones.importaciones_financiero ii,
												g_uath.ficha_empleado fe
											where
												ii.id_importacion = $idImportacion and
												ii.identificador = fe.identificador;");
				return $res;
	}
	
	public function eliminarProductosExportacionZoosanitario($conexion, $idZoosanitario){
		$res = $conexion->ejecutarConsulta("DELETE FROM
													g_zoo_exportacion.zoo_exportaciones_productos
											WHERE
													id_zoo_exportacion = $idZoosanitario;");
	
		return $res;
	}
	
	public function buscarZooVUE($conexion, $identificador, $idVue){
						
		$res = $conexion->ejecutarConsulta("SELECT 
												id_zoo_exportacion, 
												informe_requisitos,
												observacion,
												fecha_inicio,
												fecha_vigencia
											FROM
												g_zoo_exportacion.zoo_exportaciones
											WHERE
												identificador_operador = '$identificador'
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	
	public function eliminarArchivosAdjuntos($conexion, $idZoosanitario, $idVue){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_zoo_exportacion.documentos_adjuntos
											WHERE
												id_zoo_exportacion = $idZoosanitario
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	public function actualizarZoosanitario($conexion, $idZoo, $nombreTecnico, $apellidoTecnico, $idPuertoEmbarque, $puertoEmbarque, $transporte, $numeroBultos,
											$descripcionBultos, $codigoSitio, $fechaInspeccion, $nombreImportador, $direccionImportador, $idPaisDestino, $paisDestino,
											$usoProducto, $idPaisEmbarque, $paisEmbarque, $idPuertoDestino, $puertoDestino, $estado){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_zoo_exportacion.zoo_exportaciones
											SET
												nombre_tecnico='$nombreTecnico', apellido_tecnico='$apellidoTecnico', id_puerto_embarque=$idPuertoEmbarque,
												puerto_embarque='$puertoEmbarque', transporte='$transporte', numero_bultos=$numeroBultos,
												descripcion_bultos='$descripcionBultos', codigo_sitio='$codigoSitio',fecha_inspeccion='$fechaInspeccion',
												nombre_importador='$nombreImportador', direccion_importador='$direccionImportador',
												id_pais_destino=$idPaisDestino, pais_destino='$paisDestino', uso_producto=$usoProducto,
												id_pais_embarque=$idPaisEmbarque, pais_embarque='$paisEmbarque', id_puerto_destino=$idPuertoDestino,
												puerto_destino='$puertoDestino', estado = '$estado'
											WHERE
												id_zoo_exportacion = $idZoo;");
				return $res;
	}
	
	public function abrirZooAsignacion ($conexion, $idSolicitud){
			
		$res = $conexion->ejecutarConsulta("select
												i.id_zoo_exportacion as id_solicitud,
												i.identificador_operador as identificador,
												i.pais_destino as pais,
												i.estado,
												case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
											from
												g_zoo_exportacion.zoo_exportaciones i,
												g_operadores.operadores o
											where
												i.id_zoo_exportacion = $idSolicitud and
												i.identificador_operador = o.identificador");
	
				return $res;
	}
	
	public function abrirZooProductosAsignacion ($conexion, $idSolicitud){
	
	$cid = $conexion->ejecutarConsulta("select
											nombre_producto
										from
											g_zoo_exportacion.zoo_exportaciones_productos
										where
											id_zoo_exportacion = $idSolicitud");
	
			while ($fila = pg_fetch_assoc($cid)){
			$prod[] = $fila['nombre_producto'];
	}
	
	$res = implode(', ',$prod);
	
	return $res;
	}
	
public function abrirZooExportacionReporte ($conexion, $idZoo){
		$cid = $conexion->ejecutarConsulta("select
												i.*,
												ip.*,
												p.partida_arancelaria,
												i.estado as estado_exportacion,
												ip.estado as estado_producto,
												ip.observacion as observacion_producto
											from
												g_zoo_exportacion.zoo_exportaciones i,
												g_zoo_exportacion.zoo_exportaciones_productos ip,
												g_catalogos.productos p
											where
												i.id_zoo_exportacion = ip.id_zoo_exportacion and
												i.id_zoo_exportacion = $idZoo and
												ip.id_producto = p.id_producto;");
	
				while ($fila = pg_fetch_assoc($cid)){
					$res[] = array(
							idZoosanitario=>$fila['id_zoo_exportacion'],
							identificador=>$fila['identificador_operador'],
							paisEmbarque=>$fila['pais_embarque'],
							puertoEmbarque=>$fila['puerto_embarque'],
							puertoDestino=>$fila['puerto_destino'],
							idVue=>$fila['id_vue'],
							fechaInicio=>$fila['fecha_inicio'],
							fechaVigencia=>$fila['fecha_vigencia'],
		
							idProducto=>$fila['id_producto'],
							nombreProducto=>$fila['nombre_producto'],
							estadoProducto=>$fila['estado_producto'],
							observacionProducto=>$fila['observacion_producto'],
		
							nombreImportador=>$fila['nombre_importador'],
							direccionImportador=>$fila['direccion_importador'],
							idPais=>$fila['id_pais_destino'],
							pais=>$fila['pais_destino'],
		
							partidaArancelaria=>$fila['partida_arancelaria']
					);
			}
	
						return $res;
	}
	
	
	public function listarZooAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoSolicitud, $tipoInspector){
	 
	 	$res = $conexion->ejecutarConsulta("select
									 			distinct f.id_zoo_exportacion as id_solicitud,
									 			f.identificador_operador,
									 			f.pais_destino as pais,
									 			f.estado,
									 			o.razon_social, o.nombre_representante, o.apellido_representante,
									 			f.id_vue
	 										from
									 			g_zoo_exportacion.zoo_exportaciones f,
									 			g_operadores.operadores o,
									 			g_zoo_exportacion.zoo_exportaciones_productos fp,
									 			g_revision_solicitudes.asignacion_coordinador ac
									 		where
									 			f.id_zoo_exportacion = fp.id_zoo_exportacion and
									 			f.identificador_operador = o.identificador and
	 											f.id_zoo_exportacion = ac.id_solicitud and
									 			ac.identificador_inspector = '$identificadorInspector' and
	 											ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
									 			f.estado in ('$estado');");
	 	return $res;
	 }
	
	public function asignarDocumentoRequisitosZoosanitario ($conexion, $idZoo, $informeRequisitos){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_zoo_exportacion.zoo_exportaciones
											SET
												informe_requisitos = '$informeRequisitos'
											WHERE
												id_zoo_exportacion = $idZoo;");
		return $res;
	}
	
	public function actualizarFechaInspeccion ($conexion, $idZoo, $fechaInspeccion, $observacion){
				
		$res = $conexion->ejecutarConsulta("UPDATE
												g_zoo_exportacion.zoo_exportaciones
											SET
												fecha_inspeccion_realizada = '$fechaInspeccion',
												observacion = '$observacion'
											WHERE
												id_zoo_exportacion = $idZoo;");
		return $res;
	}
	
	public function evaluarProductosAreasZoo ($conexion, $idZoo, $idSitio, $idArea, $idProducto, $estado, $observacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_zoo_exportacion.zoo_exportaciones_inspeccion(
												id_solicitud, id_sitio, id_area, id_producto, estado, observacion)
											VALUES ($idZoo, $idSitio, $idArea, $idProducto, '$estado', '$observacion');");
		return $res;
	}
	
	public function actualizarEstadoSolicitud ($conexion, $idZoo, $estado){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_zoo_exportacion.zoo_exportaciones
											SET
												estado = '$estado'
											WHERE
												id_zoo_exportacion = $idZoo;");
		return $res;
	}
	
	public function listarHistorialSolicitudes ($conexion, $idSolicitud){
 
	  	$res = $conexion->ejecutarConsulta("select
												*
										from
												g_zoo_exportacion.zoo_exportaciones_productos p
										where
												p.id_zoo_exportacion = $idSolicitud");
 
  		return $res;
 	}
 	
 	public function actualizarFechaZoosanitario ($conexion, $idZoo){
 	
 		$res = $conexion->ejecutarConsulta("UPDATE
								 				g_zoo_exportacion.zoo_exportaciones
								 			SET
								 				fecha_inicio = now(),
								 				fecha_vigencia = now() + interval '3' month
								 			WHERE
								 				id_zoo_exportacion = $idZoo;");
 		return $res;
 	}
	
}