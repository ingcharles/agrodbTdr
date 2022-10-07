<?php

class ControladorImportaciones{
	/*OK*/
	public function listarPaisesAutorizadosOperador ($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("select 
												distinct (op.id_pais),
												op.nombre_pais
											from
												g_operadores.operaciones op
											where
												op.id_tipo_operacion in 
													(select 
														id_tipo_operacion 
													from 
														g_catalogos.tipos_operacion 
													where 
														nombre like 'Importador') and
												op.estado = 'registrado' and
												op.identificador_operador='$identificador'
											order by 2;");
		
		return $res;
	}
	/*OK*/
	public function listarProductosOperador($conexion, $identificador){
		//AGREGAR LOS CAMPOS NUEVOS DE UNIDAD(CENTIMETROS CUBICO, KILOGRAMO BRUTO, LITRO, METRO CUBICO REAL, NUMERO DE UNIDADES) Y PESO(tonelada, kg, lb)
		$res = $conexion->ejecutarConsulta("select 
												p.id_producto,
												p.nombre_comun,
												p.partida_arancelaria,
												p.certificado_semillas,
												p.licencia_magap,
												op.id_pais,
												t.id_area
											from
												g_operadores.operaciones op,
												g_catalogos.productos p,
												g_catalogos.subtipo_productos sp,
												g_catalogos.tipo_productos t
											where
												op.id_tipo_operacion in 
													(select 
														id_tipo_operacion 
													from 
														g_catalogos.tipos_operacion 
													where 
														nombre like 'Importador') and
												p.id_producto = op.id_producto and
												p.id_subtipo_producto = sp.id_subtipo_producto and
												sp.id_tipo_producto = t.id_tipo_producto and
												op.identificador_operador='$identificador' and
												op.estado = 'registrado';");
		return $res;
	}
	
	
	/*OK*//*
	public function listarPaisesAutorizadosOperador ($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("select 
												distinct (rc.id_localizacion),
												rc.nombre_pais,
												ra.tipo
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
													where 
														nombre like 'Importador') and
												rc.id_producto = op.id_producto and
												rc.id_requisito_comercio = ra.id_requisito_comercio and
												ra.tipo = 'importacion' and
												op.estado = 'aprobado' and
												op.identificador_operador='$identificador'
											order by 2;");
		
		return $res;
	}*/
	/*OK*/
	/*
	public function listarProductosOperador($conexion, $identificador){
		//AGREGAR LOS CAMPOS NUEVOS DE UNIDAD(CENTIMETROS CUBICO, KILOGRAMO BRUTO, LITRO, METRO CUBICO REAL, NUMERO DE UNIDADES) Y PESO(tonelada, kg, lb)
		$res = $conexion->ejecutarConsulta("select 
												p.id_producto,
												p.nombre_comun,
												p.partida_arancelaria,
												p.certificado_semillas,
												p.licencia_magap,
												rc.tipo,
												rc.id_localizacion
											from
												g_operadores.operaciones op,
												g_catalogos.productos p,
												g_requisitos.requisitos_comercializacion rc
											where
												op.id_tipo_operacion in 
													(select 
														id_tipo_operacion 
													from 
														g_catalogos.tipos_operacion 
													where 
														nombre like 'Importador') and
												p.id_producto = op.id_producto and
												op.id_producto = rc.id_producto and
												op.identificador_operador='$identificador';");
		return $res;
	}
*/
	
	public function  generarNumeroSolicitud($conexion,$codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(codigo_certificado) as numero
											FROM
												g_importaciones.importaciones
											WHERE
												codigo_certificado LIKE '$codigo';");
		return $res;
	}
	
	/*OK*/
	public function guardarNuevaImportacion($conexion, $identificador, $nombreExportador, $direccionExportador, $idPaisExportador, $nombrePaisExportador, $nombreEmbarcador, 
											$idPaisEmbarque, $nombrePaisEmbarque, $idPuertoEmbarque, $nombrePuertoEmbarque, $idPuertoDestino, $nombrePuertoDestino, 
		$codigoCertificado, $idVue, $estado, $tipoCertificado, $regimenAduanero, $moneda, $tipoTransporte, $idArea, $idCiudad, $nombreCiudad, $idProvincia, $nombreProvincia, $numeroCuarentena, $codigoSolicitudFertilizante = null, $nombreSolicitudFertilizante = null){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_importaciones.importaciones(
										            identificador_operador, nombre_exportador, direccion_exportador, id_pais_exportacion, pais_exportacion,
													nombre_embarcador, 
										            id_localizacion, pais_embarque, id_puerto_embarque, puerto_embarque, 
										            id_puerto_destino, puerto_destino, codigo_certificado, id_vue, 
										            estado, tipo_certificado, regimen_aduanero, moneda, tipo_transporte, id_area, id_ciudad, nombre_ciudad, id_provincia, nombre_provincia, numero_cuarentena, codigo_solicitud_fertilizantes, nombre_solicitud_fertilizantes)
										    VALUES ('$identificador', '$nombreExportador', $$$direccionExportador$$, $idPaisExportador, '$nombrePaisExportador', '$nombreEmbarcador', 
										            $idPaisEmbarque, '$nombrePaisEmbarque', $idPuertoEmbarque, '$nombrePuertoEmbarque', 
										            $idPuertoDestino, '$nombrePuertoDestino', '$codigoCertificado', '$idVue', 
										            '$estado', '$tipoCertificado', '$regimenAduanero', '$moneda', '$tipoTransporte', '$idArea', $idCiudad, '$nombreCiudad', $idProvincia, '$nombreProvincia', '$numeroCuarentena', '$codigoSolicitudFertilizante', '$nombreSolicitudFertilizante')
											RETURNING id_importacion;");
		return $res;
	}
	
	public function guardarImportacionesProductos($conexion, $idImportacion, $idProducto, $nombreProducto, $unidad, $peso, $valorFob, $valorCif, $licenciaMagap, $registroSemillas, $estado, $unidadMedida, $presentacionProducto,$partidaVue=null, 
												  $codigoVue=null, $nombreProductoVue = null, $composicion = null, $produtoFormular = null, $nombreProductoPaisOrigen = null){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_importaciones.importaciones_productos(
										            id_importacion, id_producto, nombre_producto, unidad, peso, valor_fob, 
										            valor_cif, licencia_magap, registro_semillas, estado, unidad_medida, partida_producto_vue, 
													codigo_producto_vue, presentacion_producto, nombre_producto_vue, composicion, producto_formular, nombre_producto_pais_origen)
										    VALUES ($idImportacion, $idProducto, '$nombreProducto', $unidad, $peso, $valorFob, 
										            $valorCif, '$licenciaMagap', '$registroSemillas', '$estado', '$unidadMedida', '$partidaVue', '$codigoVue', '$presentacionProducto', 
													'$nombreProductoVue', '$composicion', '$produtoFormular', '$nombreProductoPaisOrigen');
											");
		
		return $res;
	}
	
	/*OK*/
	public function guardarImportacionesArchivos($conexion, $idImportacion, $tipoArchivo, $rutaArchivo, $area, $id_vue = null){
		
		$documento = $this->abrirImportacionesArchivoIndividual($conexion, $idImportacion, $tipoArchivo);
		
		if(pg_num_rows($documento)== 0){
			$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.documentos_adjuntos(
														id_importacion, tipo_archivo, ruta_archivo, area, id_vue)
												VALUES ($idImportacion, '$tipoArchivo', '$rutaArchivo', '$area', '$id_vue');");
		}
		
		
	
		return $res;
	}
	
	public function listarImportacionesOperador($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("select 
												i.*, (select count(ip.id_importacion) from g_importaciones.importaciones_productos ip where ip.id_importacion = i.id_importacion) as num_productos
												
											from
												g_importaciones.importaciones i
											where
												i.identificador_operador='$identificador'
											order by i.codigo_certificado;");
		
		return $res;
	}
	
public function abrirImportacionReporte ($conexion, $idImportacion){
		$cid = $conexion->ejecutarConsulta("select
												i.*,
												ip.*,
												o.*,
												p.partida_arancelaria,
												p.nombre_cientifico,
												i.estado as estado_importacion,
												ip.estado as estado_producto,
												ip.observacion as observacion_producto
											from
												g_importaciones.importaciones i,
												g_importaciones.importaciones_productos ip,
												g_operadores.operadores o,
												g_catalogos.productos p
											where
												i.identificador_operador = o.identificador and
												i.id_importacion = ip.id_importacion and
												i.id_importacion = $idImportacion and
												ip.id_producto = p.id_producto;");
	
			while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(
					'idImportacion'=>$fila['id_importacion'],
					'identificador'=>$fila['identificador_operador'],
					'nombreEmbarcador'=>$fila['nombre_embarcador'],
					'idPaisEmbarque'=>$fila['id_localizacion'],
					'paisEmbarque'=>$fila['pais_embarque'],
					'puertoEmbarque'=>$fila['puerto_embarque'],
					'puertoDestino'=>$fila['puerto_destino'],
					'codigoCertificado'=>$fila['codigo_certificado'],
					'idVue'=>$fila['id_vue'],
					'estadoImportacion'=>$fila['estado_importacion'],
					'tipoCertificado'=>$fila['tipo_certificado'],
					'observacionImportacion'=>$fila['observacion_importacion'],
					'idArea'=>$fila['id_area'],
					'fechaInicio'=>$fila['fecha_inicio'],
					'fechaVigencia'=>$fila['fecha_vigencia'],
					'tipoTransporte'=>$fila['tipo_transporte'],
					'tipoCertificado'=>$fila['tipo_certificado'],
							
					'idProducto'=>$fila['id_producto'],
					'nombreProducto'=>$fila['nombre_producto'],
					'nombreCientifico'=>$fila['nombre_cientifico'],
					'unidad'=>$fila['unidad'],
					'peso'=>$fila['peso'],
					'unidadMedida'=>$fila['unidad_medida'],
					'valorFob'=>$fila['valor_fob'],
					'valorCif'=>$fila['valor_cif'],
					'licenciaMagap'=>$fila['licencia_magap'],
					'registroSemillas'=>$fila['registro_semillas'],
					'estadoProducto'=>$fila['estado_producto'],
					'observacionProducto'=>$fila['observacion_producto'],
					'archivoProducto'=>$fila['ruta_archivo'],
			
					'nombreExportador'=>$fila['nombre_exportador'],
					'direccionExportador'=>$fila['direccion_exportador'],
					'idPais'=>$fila['id_pais_exportacion'],
					'pais'=>$fila['pais_exportacion'],
					'regimenAduanero'=>$fila['regimen_aduanero'],
					'moneda'=>$fila['moneda'],
			
					'razonSocial'=>$fila['razon_social'],
					'nombreRepresentante'=>$fila['nombre_representante'],
					'apellidoRepresentante'=>$fila['apellido_representante'],
					'partidaArancelaria'=>$fila['partida_arancelaria']
			);
		}
	
		return $res;
	}
	

	/*OK*/
	public function abrirImportacion ($conexion, $identificador, $idImportacion){
				
		$cid = $conexion->ejecutarConsulta("select 
												i.*, 
												ip.*,
												o.*,
												p.partida_arancelaria,
												i.estado as estado_importacion,
												ip.estado as estado_producto,
												ip.observacion as observacion_producto
											from 
												g_importaciones.importaciones i,
												g_importaciones.importaciones_productos ip,
												g_operadores.operadores o,
												g_catalogos.productos p
											where 
												i.identificador_operador = '$identificador' and
												i.identificador_operador = o.identificador and 
												i.id_importacion = ip.id_importacion and
												i.id_importacion = $idImportacion and
												ip.id_producto = p.id_producto;");
		
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
					'idImportacion'=>$fila['id_importacion'],
					'identificador'=>$fila['identificador_operador'],
					'nombreEmbarcador'=>$fila['nombre_embarcador'],
					'paisEmbarque'=>$fila['pais_embarque'],
					'puertoEmbarque'=>$fila['puerto_embarque'],
					'idPuertoDestino'=>$fila['id_puerto_destino'], 
					'puertoDestino'=>$fila['puerto_destino'],
					'codigoCertificado'=>$fila['codigo_certificado'], 
					'idVue'=>$fila['id_vue'],
					'estadoImportacion'=>$fila['estado_importacion'], 
					'tipoCertificado'=>$fila['tipo_certificado'],
					'informeRequisitos'=>$fila['informe_requisitos'],
					'archivoFactura'=>$fila['archivo_factura'],
					'monto'=>$fila['monto'],
					'tipoTransporte'=>$fila['tipo_transporte'],
					
					'idProducto'=>$fila['id_producto'],
					'nombreProducto'=>$fila['nombre_producto'],
					'unidad'=>$fila['unidad'],
					'unidadMedida'=>$fila['unidad_medida'],
					'peso'=>$fila['peso'],
					'valorFob'=>$fila['valor_fob'],
					'valorCif'=>$fila['valor_cif'],
					'licenciaMagap'=>$fila['licencia_magap'],
					'registroSemillas'=>$fila['registro_semillas'],
					'estadoProducto'=>$fila['estado_producto'],
					'observacionProducto'=>$fila['observacion_producto'],
					'archivoProducto'=>$fila['ruta_archivo'],
					
					'nombreExportador'=>$fila['nombre_exportador'],
					'direccionExportador'=>$fila['direccion_exportador'],
					'idPaisExportacion'=>$fila['id_pais_exportacion'],
					'paisExportacion'=>$fila['pais_exportacion'],					
					'regimenAduanero'=>$fila['regimen_aduanero'],
					'moneda'=>$fila['moneda'],
					
					'razonSocial'=>$fila['razon_social'],
					'nombreRepresentante'=>$fila['nombre_representante'],
					'apellidoRepresentante'=>$fila['apellido_representante'], 
					'partidaArancelaria'=>$fila['partida_arancelaria'],
					
					'presentacion'=>$fila['presentacion_producto']
					);
		}

		return $res;
	}
	
	public function abrirImportacionEnviada ($conexion, $idImportacion){
				
		$cid = $conexion->ejecutarConsulta("select
												i.*,
												ip.*,
												o.*,
												p.partida_arancelaria,
												p.estado as estado_vigencia_producto,
												i.estado as estado_importacion,
												ip.estado as estado_producto,
												ip.observacion as observacion_producto
											from
												g_importaciones.importaciones i,
												g_importaciones.importaciones_productos ip,
												g_operadores.operadores o,
												g_catalogos.productos p
											where
												i.identificador_operador = o.identificador and
												i.id_importacion = ip.id_importacion and
												i.id_importacion = $idImportacion and
												ip.id_producto = p.id_producto;");
	
			while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(
					'idImportacion'=>$fila['id_importacion'],
					'identificador'=>$fila['identificador_operador'],
					'nombreEmbarcador'=>$fila['nombre_embarcador'],
					'paisEmbarque'=>$fila['pais_embarque'],
					'puertoEmbarque'=>$fila['puerto_embarque'],
					'puertoDestino'=>$fila['puerto_destino'],
					'codigoCertificado'=>$fila['codigo_certificado'],
					'idVue'=>$fila['id_vue'],
					'estadoImportacion'=>$fila['estado_importacion'],
					'tipoCertificado'=>$fila['tipo_certificado'],
					'informeRequisitos'=>$fila['informe_requisitos'],
					'archivoFactura'=>$fila['archivo_factura'],
					'monto'=>$fila['monto'],
					'tipoTransporte'=>$fila['tipo_transporte'],	  
					'idProducto'=>$fila['id_producto'],
					'nombreProducto'=>$fila['nombre_producto'],
					'unidad'=>$fila['unidad'],
					'unidadMedida'=>$fila['unidad_medida'],
					'peso'=>$fila['peso'],
					'valorFob'=>$fila['valor_fob'],
					'valorCif'=>$fila['valor_cif'],
					'licenciaMagap'=>$fila['licencia_magap'],
					'registroSemillas'=>$fila['registro_semillas'],
					'estadoProducto'=>$fila['estado_producto'],
					'observacionProducto'=>$fila['observacion_producto'],
					'archivoProducto'=>$fila['ruta_archivo'],						
					'nombreExportador'=>$fila['nombre_exportador'],
					'direccionExportador'=>$fila['direccion_exportador'],
					'paisExportacion'=>$fila['pais_exportacion'],
					'regimenAduanero'=>$fila['regimen_aduanero'],
					'moneda'=>$fila['moneda'],	  
					'razonSocial'=>$fila['razon_social'],
					'direccion'=>$fila['direccion'],
					'telefono'=>$fila['telefono_uno'],
					'correo'=>$fila['correo'],
					'nombreRepresentante'=>$fila['nombre_representante'],
					'apellidoRepresentante'=>$fila['apellido_representante'],
					'partidaArancelaria'=>$fila['partida_arancelaria'],
					'presentacion' =>$fila['presentacion_producto'],
					'idArea' =>$fila['id_area'],
					'numeroCuarentena' =>$fila['numero_cuarentena'],
					'idAreaSeguimiento'=>$fila['id_area_seguimiento'],
					'nombreSolicitudFertilizantes' => $fila['nombre_solicitud_fertilizantes'],
					'composicion' => $fila['composicion'],
					'productoFormular' => $fila['producto_formular'],
					'nombreProductoPaisOrigen' => $fila['nombre_producto_pais_origen'],
					'estadoVigenciaProducto' => $fila['estado_vigencia_producto']
					);
				}
	
				return $res;
				}
	
	public function abrirImportacionesArchivos($conexion, $idImportacion){
		$cid = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_importaciones.documentos_adjuntos
											WHERE
												id_importacion = $idImportacion;");
		
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
					'idImportacion'=>$fila['id_importacion'],
					'tipoArchivo'=>$fila['tipo_archivo'],
					'rutaArchivo'=>$fila['ruta_archivo'],
					'area'=>$fila['area'],
					'idVue'=>$fila['id_vue']);
		}
		
		return $res;
	}
	
	public function abrirImportacionesArchivoIndividual($conexion, $idImportacion, $nombreArchivo){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_importaciones.documentos_adjuntos
											WHERE
												id_importacion = $idImportacion
												and tipo_archivo = '$nombreArchivo';");
		
		return $res;
	}
	
	
	////////////////////// EVALUACION DE PRODUCTOS IMPORTACION //////////////////////////
	/*OK*/
	public function enviarImportacion ($conexion, $idImportacion, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												estado = '$estado'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	public function evaluarProductosImportacion ($conexion, $idImportacion, $idProducto, $estado, $observacion, $informe){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones_productos
											set
												estado = '$estado',
												observacion ='$observacion',
												ruta_archivo = '$informe'
											where
												id_producto = $idProducto and
												id_importacion = $idImportacion;");
		return $res;
	}
	
	/*OK*/
	public function enviarProductosImportacion ($conexion, $idImportacion, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones_productos
											set
												estado = '$estado'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	/*OK*/
	public function guardarDatosInspeccionDocumental ($conexion, $idAsignacion, $identificador, $observacion, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.revision_documental(
												id_asignacion, identificador_inspector, fecha_inspeccion, observacion, estado)
											VALUES ($idAsignacion, '$identificador', now(), '$observacion', '$estado');");
		return $res;
	}
	
	public function guardarDatosInspeccion ($conexion, $idImportacion, $idProducto, $identificador, $archivo, $estado, $observacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.inspeccion_productos(
												id_importacion, identificador, id_producto, fecha_inspeccion, ruta_archivo,
												estado, observacion)
											VALUES ($idImportacion, '$identificador', $idProducto, now(), '$archivo', '$estado', '$observacion');");
		return $res;
	}
	
	/*OK revisar*/
	public function abrirProductosImportacion ($conexion, $idImportacion){
		$cid = $conexion->ejecutarConsulta("select 
												* 
											from 
												g_importaciones.importaciones_productos 
											where 
												id_importacion = $idImportacion;");
			
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array('idOperacion'=>$fila['id_importacion'],'estado'=>$fila['estado'],
					'observacion'=>$fila['observacion']);
		}
			
		return $res;
	}
	
	public function evaluarImportacion ($conexion, $idImportacion, $estado, $observacion, $informeRequisitos=null){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												estado= '$estado',
												observacion = '$observacion',
												informe_requisitos = '$informeRequisitos'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	public function asignarDocumentoRequisitosImportacion ($conexion, $idImportacion, $informeRequisitos){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												informe_requisitos = '$informeRequisitos'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	////////////////////////////// ASIGNACION DE IMPORTACIONES A INSPECTORES PARA REVISION ///////////////////////////////////
	
		/*OK*/
	public function listarImportacionesAsignadasInspectorRS ($conexion, $estadoSolicitud, $identificadorInspector, $idArea, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("SELECT
												distinct i.id_importacion as id_solicitud,
												i.identificador_operador,
												i.estado,
												i.tipo_certificado,
												i.pais_exportacion as pais,
												o.razon_social,
												o.nombre_representante,
												o.apellido_representante,
												i.id_vue
											FROM
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip,
												g_revision_solicitudes.asignacion_coordinador ac
											WHERE
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												i.id_importacion = ac.id_solicitud and
												ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
												i.id_area = '$idArea' and
												i.estado in ('$estadoSolicitud');");
				return $res;
		}
	/*OK*/
	/*filtro de operaciones por provincia por asignar*/
	public function listarImportacionesRevisionProvincia ($conexion, $provinciaInspector){
	
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion,
												i.identificador_operador,
												i.estado, 
												i.tipo_certificado,
												i.pais_exportacion,
												o.razon_social, o.nombre_representante, o.apellido_representante
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip
											where
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												o.provincia = '$provinciaInspector' and
												i.estado in ('$estado');");
		return $res;
	}
	
	/*OK*/
	public function listarImportacionesAsignadasInspector ($conexion, $provinciaInspector, $identificadorInspector){
	
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion,
												i.identificador_operador,
												i.estado, 
												i.tipo_certificado,
												i.pais_exportacion,
												o.razon_social, 
												o.nombre_representante, 
												o.apellido_representante
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip,
												g_importaciones.asignacion_inspector ai
											where
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												o.provincia = '$provinciaInspector' and
												ai.identificador_inspector = '$identificadorInspector' and
												i.estado in ('asignado', 'inspeccion');");
				return $res;
	}
		
	
	////////////////////////////// REVISION DE IMPORTACIONES EN FINANCIERO ///////////////////////////////////
	
	/*filtro de importaciones por estado*/
	
	/*OK*/
	public function listarImportacionesRevisionFinancieroRS ($conexion, $nombreProvincia, $estado='pago'){
		
		/*$tabla = '';
		$busqueda = '';
		
		if($estado == 'verificacion'){
			if($tipoEstadoOrdenPago == 'estadoComprobante' && $estadoOrdenPago != ''){
				$tabla = ", g_revision_solicitudes.asignacion_inspector ai, g_revision_solicitudes.grupos_solicitudes gs, g_financiero.orden_pago orp";
				$busqueda = "and i.id_importacion = gs.id_solicitud and ai.id_grupo = gs.id_grupo and ai.tipo_solicitud = '$tipoSolicitud' and ai.tipo_inspector = 'Financiero' and gs.estado != 'Verificación' and orp.id_grupo_solicitud = ai.id_grupo and orp.estado = $estadoOrdenPago and orp.tipo_solicitud = '$tipoSolicitud'";
			}else if($tipoEstadoOrdenPago == 'estadoComprobante' && $estadoOrdenPago == ''){
				$tabla = ", g_revision_solicitudes.asignacion_inspector ai, g_revision_solicitudes.grupos_solicitudes gs, g_financiero.orden_pago orp";
				$busqueda = "and i.id_importacion = gs.id_solicitud and ai.id_grupo = gs.id_grupo and ai.tipo_solicitud = '$tipoSolicitud' and ai.tipo_inspector = 'Financiero' and gs.estado != 'Verificación' and orp.id_grupo_solicitud = ai.id_grupo and orp.tipo_solicitud = '$tipoSolicitud'";
			}else if($tipoEstadoOrdenPago == 'estadoSRI'){
				$tabla = ", g_revision_solicitudes.asignacion_inspector ai, g_revision_solicitudes.grupos_solicitudes gs, g_financiero.orden_pago orp";
				$busqueda = "and i.id_importacion = gs.id_solicitud and ai.id_grupo = gs.id_grupo and ai.tipo_solicitud = '$tipoSolicitud' and ai.tipo_inspector = 'Financiero' and gs.estado != 'Verificación' and orp.id_grupo_solicitud = ai.id_grupo and orp.estado_sri = '$estadoOrdenPago' and orp.tipo_solicitud = '$tipoSolicitud'";
			}
				
			if($numeroOrdenPago !=''){
				$busqueda .= " and numero_solicitud = '$numeroOrdenPago'";
			}
		}*/
			
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion  as id_solicitud,
												i.identificador_operador,
												i.estado,
												i.tipo_certificado,
												i.pais_exportacion as pais,
												o.razon_social, o.nombre_representante, o.apellido_representante,
												i.id_vue
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip
											where
												i.id_importacion = ip.id_importacion and
												UPPER(i.nombre_provincia) = UPPER('$nombreProvincia') and
												i.identificador_operador = o.identificador and
												i.estado in ('$estado')
											order by 1 asc;");
		return $res;
	}
	
	public function obtenerImportacionFinancieroVerificacion ($conexion, $estado, $nombreProvincia, $tipoSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct i.id_importacion  as id_solicitud,
												i.identificador_operador,
												i.estado,
												i.tipo_certificado,
												i.pais_exportacion as pais,
												o.razon_social, o.nombre_representante, o.apellido_representante,
												i.id_vue
											FROM
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip,
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs,
												g_financiero.orden_pago orp
											WHERE
												i.id_importacion = ip.id_importacion and
												i.id_importacion = gs.id_solicitud and
												ai.id_grupo = gs.id_grupo and
												i.identificador_operador = o.identificador and
												UPPER(i.nombre_provincia) = UPPER('$nombreProvincia') and
												i.estado in ('$estado') and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.tipo_inspector = 'Financiero' and
												gs.estado != 'Verificación' and 
												orp.id_grupo_solicitud = ai.id_grupo and 
												orp.estado = 3 and 
												orp.tipo_solicitud = '$tipoSolicitud';");
				return $res;
	}
	
	/*OK*/
	/*filtro de operaciones por provincia por asignar*/
	public function listarImportacionesRevisionProvinciaRS ($conexion, $estado, $idArea, $nombreProvincia){
			
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion as id_solicitud,
												i.identificador_operador,
												i.estado,
												i.tipo_certificado,
												i.pais_exportacion as pais,
												o.razon_social,
												o.nombre_representante,
												o.apellido_representante,
												i.id_vue,
												i.fecha_creacion
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip
											where
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												UPPER(i.nombre_provincia) = UPPER('$nombreProvincia') and
												i.id_area = '$idArea' and
												i.estado in ('$estado')
											order by 
												i.fecha_creacion desc;");
		return $res;
	}
	
	
	/*OK*/
	/*public function listarImportacionesRevisionFinanciero ($conexion, $estado='pago'){
	
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_importacion,
												i.identificador_operador,
												i.estado, 
												i.tipo_certificado,
												i.pais_exportacion,
												o.razon_social, o.nombre_representante, o.apellido_representante
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o,
												g_importaciones.importaciones_productos ip
											where
												i.id_importacion = ip.id_importacion and
												i.identificador_operador = o.identificador and
												i.estado in ('$estado')
											order by 1 asc;");
				return $res;
	}*/
	
	
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
	
	public function guardarNuevoInspectorFinanciero ($conexion,$idImportacion,$identificadorFinanciero, $idCoordinador){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.importaciones_financiero(
												id_importacion, identificador, fecha_asignacion, identificador_coordinador)
											VALUES ($idImportacion,'$identificadorFinanciero',now(), '$idCoordinador');");
		return $res;
	}
	
	/*OK*/
	public function guardarDatosResultadoFinanciero ($conexion, $idAsignacion, $identificador, $estado, $observacion, $transaccion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_importaciones.financiero 
											SET
												fecha_inspeccion = now(),
												estado = '$estado',
												observacion = '$observacion',
												numero_transaccion = '$transaccion'
											WHERE 
												id_asignacion = $idAsignacion and 
												identificador_inspector = '$identificador';");
		return $res;
	}
	
	/*OK*/
	public function asignarMontoImportacion ($conexion, $idAsignacion, $idInspector, $monto){
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_importaciones.financiero (id_asignacion, identificador_inspector, monto, fecha_asignacion_monto)
											VALUES($idAsignacion, '$idInspector', $monto, now());");
		return $res;
	}
	
	///// FECHA DE VIGENCIA /////
	/*OK*/
	public function enviarFechaVigenciaImportacion ($conexion, $idImportacion, $idArea){
		
		$fechaVigencia = '';
		switch ($idArea){
			case 'SV':
			case 'SA':  $fechaVigencia = "now() + interval '3' month"; 	break;
			
			case 'IAP':
			case 'IAF':
			case 'IAPA':
			case 'IAV': $fechaVigencia = "now() + interval '6' month"; 	break;	
			default:
				echo 'Área desconocida.';
		}
		
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												fecha_inicio = now(),
												fecha_vigencia = " . $fechaVigencia ." 
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	public function enviarFechaVigenciaAmpliacion ($conexion, $idImportacion, $fechaVigencia){
		
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												fecha_ampliacion =now(),
												fecha_vigencia = '$fechaVigencia'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	///// TRANSACCION BANCARIA /////
	/*public function obtenerMontoImportacion ($conexion, $idImportacion, $tipoSolicitud){
		$res = $conexion->ejecutarConsulta("select 
												f.*
											from 
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.financiero f
											where
												ai.id_solicitud = $idImportacion and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.id_asignacion = f.id_asignacion;")
		return $res;
	}*/
	
	
	public function enviarTransaccionImportacion ($conexion, $idImportacion, $transaccion){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												transaccion = '$transaccion'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	/*OK*/
	////////// ASIGNAR A INSPECTOR ///////////////
	public function abrirImportacionInspeccion ($conexion, $idImportacion){
		$cid = $conexion->ejecutarConsulta("select
												i.*,
												o.*
											from
												g_importaciones.importaciones i,
												g_operadores.operadores o
											where
												i.identificador_operador = o.identificador and
												i.id_importacion = $idImportacion;");
	
				while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(
				'idImportacion'=>$fila['id_importacion'],
				'identificador'=>$fila['identificador_operador'],
				'tipoCertificado'=>$fila['tipo_certificado'],
				'estado'=>$fila['estado'],
		
				'nombreExportador'=>$fila['nombre_exportador'],
				'paisExportacion'=>$fila['pais_exportacion'],
		
				'razonSocial'=>$fila['razon_social'],
				'nombreRepresentante'=>$fila['nombre_representante'],
				'apellidoRepresentante'=>$fila['apellido_representante']
			);
		}
	
		return $res;
	}
	
	/*OK*/ //Se utiliza para asignacion de técnicos y financieros
	public function guardarNuevoInspector ($conexion,$idImportacion,$identificadorInspector, $idCoordinador, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.asignacion_inspector(
				identificador_inspector, fecha_asignacion, identificador_asignante, tipo_solicitud, id_solicitud, tipo_inspector)
				VALUES ('$identificadorInspector',now(), '$idCoordinador', '$tipoSolicitud', $idImportacion, '$tipoInspector')
				RETURNING id_asignacion;");
		return $res;
	}
	
	/*OK*/
	public function listarInspectoresAsignados ($conexion,$idImportacion, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("select
												ii.*,
												fe.nombre,
												fe.apellido
											from
												g_importaciones.asignacion_inspector ii,
												g_uath.ficha_empleado fe
											where
												ii.id_solicitud = $idImportacion and
												ii.tipo_solicitud= '$tipoSolicitud' and
												ii.tipo_inspector= '$tipoInspector' and
												ii.identificador_inspector = fe.identificador;");
		return $res;
	}
	
	public function buscarInspectorAsignado ($conexion,$idImportacion, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		$res = $conexion->ejecutarConsulta("select
												ii.*,
												fe.nombre,
												fe.apellido
											from
												g_importaciones.asignacion_inspector ii,
												g_uath.ficha_empleado fe
											where
												ii.id_solicitud = $idImportacion and
												ii.tipo_solicitud= '$tipoSolicitud' and
												ii.tipo_inspector= '$tipoInspector' and
												ii.identificador_inspector = '$identificadorInspector'and
												ii.identificador_inspector = fe.identificador;");
		
		return $res;
	}
	
	public function eliminarInspectorAsignado ($conexion,$idImportacion,$identificadorInspector, $tipoSolicitud, $tipoTecnico){
			
		$res = $conexion->ejecutarConsulta("delete from
												g_importaciones.asignacion_inspector
											where
												id_solicitud = $idImportacion and
												identificador_inspector = '$identificadorInspector' and
												tipo_solicitud = '$tipoSolicitud' and
												tipo_inspector = '$tipoTecnico';");
		return $res;
	}
	
	//////////////////////// PARA DDA //////////////////////////
	
	/*public function buscarCertificadoImportacion ($conexion, $identificador, $codigoImportacion, $idVue){

		$res = $conexion->ejecutarConsulta("select 
												i.*, 
												ip.*,
												e.*
											from 
												g_importaciones.importaciones i,
												g_importaciones.importaciones_productos ip,
												g_importaciones.exportadores e
											where
												(i.codigo_certificado = '$codigoImportacion' or
												i.id_vue = '$idVue') and
												i.identificador_operador = '$identificador'
												i.id_importacion = ip.id_importacion and
												i.id_exportadores = e.id_exportador and
												i.estado = 'aprobado'");
		
		return $res;
	}*/
	
	public function buscaridImportacionCertificado ($conexion, $identificador, $codigoImportacion){
		$res = $conexion->ejecutarConsulta("select 
												i.id_importacion
											from 
												g_importaciones.importaciones i
											where
												i.codigo_certificado = '$codigoImportacion' and
												i.identificador_operador = '$identificador' and
												i.estado = 'aprobado'");
	
		return $res;
	}
	
	public function buscarCodigoImportacionVue ($conexion, $identificador, $idVue){
	
		$res = $conexion->ejecutarConsulta("select
												i.*,
												ip.*
											from
												g_importaciones.importaciones i,
												g_importaciones.importaciones_productos ip
											where
												i.id_vue = '$idVue'and
												i.identificador_operador = '$identificador' and
												i.id_importacion = ip.id_importacion and
												i.estado = 'aprobado'");
	
		return $res;
	}
	
	public function listarHistorialSolicitudes ($conexion, $idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select
												ip.*
											from
												g_importaciones.importaciones_productos ip
											where
												ip.id_importacion = $idSolicitud");
	
		return $res;
	}
	
	public function obtenerSumaValoresTotalesProductoImportacion ($conexion, $idSolicitud){
		
		$res = $conexion->ejecutarConsulta("select
												sum(valor_fob) as valor_fob, sum(valor_cif) as valor_cif, sum(peso) as peso
											from
												g_importaciones.importaciones_productos
											where
												id_importacion = $idSolicitud");
		
		return $res;
	}
	
	public function buscarImportacionVUE ($conexion, $idVue){
		
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_importaciones.importaciones
											where
												id_vue = '$idVue';");
	
		return $res;
	}
	
	public function buscarImportacionImportadorVUE ($conexion, $identificador, $idVue){
		
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_importaciones.importaciones
											where
												identificador_operador = '$identificador'
												and id_vue = '$idVue';");
		
		return $res;
	}
	
	public function obtenerImportacion ($conexion, $idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_importaciones.importaciones
											where
												id_importacion = $idSolicitud;");
	
		return $res;
	}
	
	public function eliminarArchivosAdjuntos($conexion, $idImportacion, $idVue){
			$res = $conexion->ejecutarConsulta("DELETE FROM 
													g_importaciones.documentos_adjuntos
												WHERE 
													id_importacion = $idImportacion
													and id_vue = '$idVue';");
	
		return $res;
	}
	
	public function actualizarDatosImportacion($conexion, $idImportacion, $nombreExportador, $direccionExportador, $idPaisExportador, $nombrePaisExportador, $nombreEmbarcador, 
												$idPaisEmbarque, $nombrePaisEmbarque, $codigoPuertoEmbarque,$nombrePuertoEmbarque, $codigoPuertoDestino, $nombrePuertoDestino, 
												$idVue, $estado, $tipoCertificado, $regimenAduanero, $moneda, $tipoTransporte, $idArea, $idCiudad, $nombreCiudad, $idProvincia, $nombreProvincia, 
												$numeroCuarentena, $codigoSolicitudFertilizante = null, $nombreSolicitudFertilizante = null){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_importaciones.importaciones
											SET
												nombre_exportador  = '$nombreExportador', 
												direccion_exportador = $$$direccionExportador$$, 
												id_pais_exportacion = $idPaisExportador, 
												pais_exportacion = '$nombrePaisExportador',
												nombre_embarcador = '$nombreEmbarcador',
												id_localizacion = $idPaisEmbarque, 
												pais_embarque = '$nombrePaisEmbarque', 
												id_puerto_embarque = $codigoPuertoEmbarque, 
												puerto_embarque = '$nombrePuertoEmbarque',
												id_puerto_destino = $codigoPuertoDestino, 
												puerto_destino  = '$nombrePuertoDestino',
												estado = 'enviado', 
												tipo_certificado = '$tipoCertificado', 
												regimen_aduanero = $regimenAduanero, 
												moneda = $moneda, 
												tipo_transporte = '$tipoTransporte',
												id_area = '$idArea',
												id_ciudad = $idCiudad,
												nombre_ciudad = '$nombreCiudad',
												id_provincia = $idProvincia,
												nombre_provincia = '$nombreProvincia',
												numero_cuarentena = '$numeroCuarentena',
												codigo_solicitud_fertilizantes = '$codigoSolicitudFertilizante',
												nombre_solicitud_fertilizantes = '$nombreSolicitudFertilizante'
											WHERE
												id_importacion = $idImportacion
												and id_vue = '$idVue';");
				return $res;
	}
	
	public function actualizarDatosExportador($conexion, $idImportacion, $nombreExportador, $direccionExportador, $idPaisEmbarque, $nombrePaisEmbarque, $idPuertoEmbarque, $nombrePuertoEmbarque, $idPuertoDestino, 
											$nombrePuertoDestino, $nombreEmbarcador, $medioTransporte, $idCiudad, $nombreCiudad, $idProvincia, $nombreProvincia, $idVue, $idArea){

		$columnas = '';
		
		switch ($idArea){
			case 'SV':
				$columnas = "nombre_exportador  = '$nombreExportador', direccion_exportador = $$$direccionExportador$$,  id_localizacion = $idPaisEmbarque,
							 pais_embarque = '$nombrePaisEmbarque', id_puerto_embarque = $idPuertoEmbarque, puerto_embarque = '$nombrePuertoEmbarque',
							 id_puerto_destino = $idPuertoDestino,  puerto_destino = '$nombrePuertoDestino', nombre_embarcador = '$nombreEmbarcador',
							 tipo_transporte = '$medioTransporte', id_ciudad = $idCiudad, nombre_ciudad = '$nombreCiudad', id_provincia = $idProvincia, 
							 nombre_provincia = '$nombreProvincia', fecha_modificacion= now()";
			break;
			
			case 'SA':
				$columnas = "id_localizacion = $idPaisEmbarque, pais_embarque = '$nombrePaisEmbarque', id_puerto_embarque = $idPuertoEmbarque, 
							 puerto_embarque = '$nombrePuertoEmbarque', id_puerto_destino = $idPuertoDestino,  puerto_destino = '$nombrePuertoDestino', 
							 nombre_embarcador = '$nombreEmbarcador', tipo_transporte = '$medioTransporte', id_ciudad = $idCiudad, nombre_ciudad = '$nombreCiudad', 
							 id_provincia = $idProvincia, nombre_provincia = '$nombreProvincia', fecha_modificacion= now()";
				
			break;
			
			case 'IAV':
			case 'IAP':
			case 'IAF':
			case 'IAPA':
				$columnas = "id_localizacion = $idPaisEmbarque, pais_embarque = '$nombrePaisEmbarque', id_puerto_embarque = $idPuertoEmbarque, 
							 puerto_embarque = '$nombrePuertoEmbarque', id_puerto_destino = $idPuertoDestino,  puerto_destino = '$nombrePuertoDestino', 
							 nombre_embarcador = '$nombreEmbarcador', tipo_transporte = '$medioTransporte', fecha_modificacion= now()";
				
			break;
			
			
		}
				
		$res = $conexion->ejecutarConsulta("UPDATE
												g_importaciones.importaciones
											SET
												".$columnas."
											WHERE
												id_importacion = $idImportacion
												and id_vue = '$idVue';");
				return $res;
	}
	
	
	
	public function eliminarProductosImportacion($conexion, $idImportacion){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_importaciones.importaciones_productos
											WHERE
												id_importacion = $idImportacion;");
	
				return $res;
	}
	
	public function buscarOperadorImportacionXidVue ($conexion, $identificador, $idVue){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_importaciones.importaciones
											where
												identificador_operador = '$identificador'
												and id_vue = '$idVue';");
	
				return $res;
	}
	
	public function buscarVigenciaImportacion ($conexion, $idVue){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_importaciones.importaciones
											where
												id_vue = '$idVue';");
	
				return $res;
	}
	
	public function verificarVigenciaImportacion ($conexion, $idVue, $fechaInicioVigencia){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_importaciones.importaciones
											where
												id_vue = '$idVue'
												and fecha_inicio ='$fechaInicioVigencia';");
	
				return $res;
	}
	
	public function buscarUtilizacionImportacion($conexion, $idImportacion){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_dda.destinacion_aduanera
											where
												permiso_importacion = '$idImportacion'
												and estado in ('aprobado');");
	
				return $res;
	}
	
	public function buscarImportacionProductoVUE ($conexion, $identificador, $idVue, $producto, $partidaArancelaria = NULL, $codigoProducto = NULL){
		
		$partidaArancelaria = $partidaArancelaria!="" ? "'" . $partidaArancelaria . "'" : "null";
		$codigoProducto = $codigoProducto!="" ? "'" . $codigoProducto . "'" : "null";
							
		$res = $conexion->ejecutarConsulta("select
												p.*
											from
												g_importaciones.importaciones i,
												g_importaciones.importaciones_productos p
											where
												i.id_vue = '$idVue' and
												i.id_importacion = p.id_importacion and
												p.id_producto = $producto
												and ($partidaArancelaria is NULL or partida_producto_vue = $partidaArancelaria)
												and ($codigoProducto is NULL or codigo_producto_vue = $codigoProducto);");
	
		return $res;
	}
	
	public function abrirImportacionAsignacion ($conexion, $idSolicitud){
				
			$res = $conexion->ejecutarConsulta("select
													i.id_importacion as id_solicitud,
													i.identificador_operador as identificador,
													i.pais_exportacion as pais,
													i.tipo_certificado as tipo_solicitud,
													i.estado,
													case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
												from
													g_importaciones.importaciones i,
													g_operadores.operadores o
												where
													i.id_importacion = $idSolicitud and
													i.identificador_operador = o.identificador");
		
					return $res;
		}
		
	public function abrirImportacionProductosAsignacion ($conexion, $idSolicitud){
		
		$cid = $conexion->ejecutarConsulta("select
												nombre_producto
											from
												g_importaciones.importaciones_productos
											where
												id_importacion = $idSolicitud");
		
				while ($fila = pg_fetch_assoc($cid)){
				$prod[] = $fila['nombre_producto'];
		}
		
		$res = implode(', ',$prod);
		
			return $res;
	}
	
	public function abrirRevisionDocumentalImportacionReporte ($conexion, $idImportacion){
		$cid = $conexion->ejecutarConsulta("select
												rd.id_revision_documental,
												i.id_importacion,
												rd.observacion,
												rd.fecha_inspeccion,
												rd.identificador_inspector,
												fe.nombre,
												fe.apellido
											from
												g_importaciones.importaciones i,
												g_revision_solicitudes.grupos_solicitudes g,
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.revision_documental rd,
												g_uath.ficha_empleado fe
											where
												i.id_importacion = $idImportacion and
												g.id_solicitud = i.id_importacion and
												ai.id_grupo = g.id_grupo and
												ai.tipo_solicitud = 'Importación' and
												ai.tipo_inspector = 'Documental' and
												rd.id_grupo = g.id_grupo and
												rd.identificador_inspector = fe.identificador
											order by
												rd.id_revision_documental desc;");
	
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
				'idImportacion'=>$fila['id_importacion'],
				'observacionInspeccionDocumental'=>$fila['observacion'],
				'fechaInspeccionDocumental'=>$fila['fecha_inspeccion'],
				'idInspector'=>$fila['identificador_inspector'],
				'nombreInspector'=>$fila['nombre'],
				'apellidoInspector'=>$fila['apellido']
				);
		}

		return $res;
	}

	public function obtenerCabeceraImportacion ($conexion, $idSolicitud){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_importaciones.importaciones
											WHERE
												id_importacion = $idSolicitud;");
	
		return $res;
	
	}

	public function  listarImportacionPorEstado($conexion, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_importaciones.importaciones
											WHERE
												estado = '$estado';");
		return $res;
	}
	
	public function buscarImportacionProducto ($conexion, $idVue){
	    
	    $res = $conexion->ejecutarConsulta("select
												p.*
											from
												g_importaciones.importaciones i,
												g_importaciones.importaciones_productos p
											where
												i.id_vue = '$idVue' and
												i.id_importacion = p.id_importacion");
	    
	    return $res;
	}
	
	public function  obtenerRegistros($conexion){

		/*$res = $conexion->ejecutarConsulta("SELECT 
											to_char(fecha_inicio,'YYYY/MM/DD') as fecha_inicio, informe_requisitos
										FROM 
											g_importaciones.importaciones i,
											g_importaciones.importaciones_productos ip,
											g_catalogos.productos p,
											g_catalogos.subtipo_productos sp
										WHERE
											i.id_importacion = ip.id_importacion
											and ip.id_producto = p.id_producto 
											and p.id_subtipo_producto = sp.id_subtipo_producto
											and sp.nombre ilike '%Hortaliza%'
											and i.estado = 'aprobado';");*/
											
		$res = $conexion->ejecutarConsulta("SELECT 
											to_char(fecha_inicio,'YYYY/MM/DD') as fecha_inicio, informe_requisitos, id_vue
										FROM 
											g_importaciones.importaciones i
										WHERE
											fecha_inicio >= '2021-06-01 00:00:00'
											and i.estado = 'aprobado';");
											
		return $res;
	}
	
	public function  actualizarValorFobCifProductoImportacion($conexion, $idImportacion, $IdProduto, $partidaProductoVue, $partidaCodigoVue, $valorCif, $valorFob){
	    
	    $consulta = "UPDATE 
                        g_importaciones.importaciones_productos
                    SET 
                        valor_fob = $valorFob,
                        valor_cif = $valorCif
                    WHERE
                        id_importacion = $idImportacion
                        and id_producto = $IdProduto
                        and partida_producto_vue = '$partidaProductoVue'
                        and codigo_producto_vue = '$partidaCodigoVue';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    return $res;
	}
	
}