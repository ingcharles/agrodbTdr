<?php

class ControladorDestinacionAduanera{
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
												op.estado = 'registrado' and
												op.identificador_operador='$identificador'
											order by 2;");
		
		return $res;
	}
	public function listarProductosOperador($conexion, $identificador){
		//AGREGAR LOS CAMPOS NUEVOS DE UNIDAD(CENTIMETROS CUBICO, KILOGRAMO BRUTO, LITRO, METRO CUBICO REAL, NUMERO DE UNIDADES) Y PESO(tonelada, kg, lb)
		$res = $conexion->ejecutarConsulta("select 
												p.id_producto,
												p.nombre_comun,
												p.partida_arancelaria,
												p.certificado_semillas,
												p.licencia_magap,
												p.cfr,
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
	
	/*ok*/
	public function  generarNumeroSolicitud($conexion,$codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(codigo_certificado) as numero
											FROM
												g_dda.destinacion_aduanera
											WHERE
												codigo_certificado LIKE '$codigo';");
		return $res;
	}
	
	/*ok*/
	public function guardarNuevoExportador($conexion, $nombreExportador, $direccionExportador, $idPais, $nombrePais){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_dda.exportadores(
													nombre_exportador, 
													direccion_exportador, 
													id_localizacion, 
										            pais_exportacion)
										    VALUES ('$nombreExportador', '$direccionExportador', $idPais, '$nombrePais')
											RETURNING id_exportador;");
		return $res;
	}
	
	/*OK*/
	public function guardarNuevoDDA($conexion, $identificador, $nombreExportador, $direccionExportador, $idPaisExportador, $nombrePaisExportador, $proposito, $tipoCertificado, 
									$categoriaProducto, $permisoImportacion, $permisoExportacion, $idPuertoDestino, $nombrePuertoDestino, $numeroCarga, $tipoTransporte, 
									$numeroTransporte, $idLugarInspeccion, $nombreLugarInspeccion, $observacionOperador, $codigoCertificado, $idVue){

		$res = $conexion->ejecutarConsulta("INSERT INTO g_dda.destinacion_aduanera(
										            identificador_operador, nombre_exportador, 
										            direccion_exportador, id_pais_exportador, pais_exportacion, proposito, 
										            tipo_certificado, categoria_producto, permiso_importacion, permiso_exportacion, 
										            id_puerto_destino, puerto_destino, numero_carga, tipo_transporte, 
										            numero_transporte, lugar_inspeccion, nombre_lugar_inspeccion, 
										            observacion_operador, codigo_certificado, id_vue, estado)
										    VALUES ('$identificador', '$nombreExportador', 
													$$$direccionExportador$$, $idPaisExportador, '$nombrePaisExportador', '$proposito', 
													'$tipoCertificado', '$categoriaProducto', '$permisoImportacion', '$permisoExportacion', 
													$idPuertoDestino, '$nombrePuertoDestino', '$numeroCarga', '$tipoTransporte', 
													'$numeroTransporte', $idLugarInspeccion, '$nombreLugarInspeccion', 
										            '$observacionOperador', '$codigoCertificado', '$idVue', 'enviado') 
											RETURNING id_destinacion_aduanera;");
		return $res;
	}
	
	/*OK*/
	public function guardarDDAProductos($conexion, $idDestinacionAduanera, $idProducto, $nombreProducto, $unidad, $unidadMedida, $partidaVue = null, $codigoVue = null){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_dda.destinacion_aduanera_productos(
										            id_destinacion_aduanera, id_producto, nombre_producto, unidad, unidad_medida, partida_producto_vue, codigo_producto_vue)
										    VALUES ($idDestinacionAduanera, $idProducto, '$nombreProducto', $unidad, '$unidadMedida', '$partidaVue', '$codigoVue');
											");
		
		return $res;
	}
	
	/*OK*/
	public function guardarDDAArchivos($conexion, $idDestinacionAduanera, $tipoArchivo, $rutaArchivo, $area, $idVue = null){
		
		$documento = $this->abrirDDAArchivoIndividual($conexion, $idDestinacionAduanera, $tipoArchivo);
		
		if(pg_num_rows($documento)== 0){
			
			$res = $conexion->ejecutarConsulta("INSERT INTO g_dda.documentos_adjuntos(
																				id_destinacion_aduanera, tipo_archivo, ruta_archivo, area, id_vue)
												VALUES ($idDestinacionAduanera, '$tipoArchivo', '$rutaArchivo', '$area', '$idVue');");
			
			return $res;
		}
		
		
	}
	
	public function abrirDDAArchivoIndividual($conexion, $idDestinacionAduanera, $tipoArchivo){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_dda.documentos_adjuntos
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera
												and tipo_archivo = '$tipoArchivo';");
	
		return $res;
	}
	
	public function listarDDAOperador($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("select 
												d.*, (select count(dp.id_destinacion_aduanera) from g_dda.destinacion_aduanera_productos dp where dp.id_destinacion_aduanera = d.id_destinacion_aduanera) as num_productos
												
											from
												g_dda.destinacion_aduanera d
											where
												d.identificador_operador='$identificador'
											order by d.codigo_certificado;");
		
		return $res;
	}
	
	/*OK*/
	public function abrirDDA ($conexion, $idDestinacionAduanera){
		$cid = $conexion->ejecutarConsulta("select 
												d.*, 
												da.*,
												o.*,
												p.partida_arancelaria,
												d.estado as estado_dda,
												da.estado as estado_producto,
												d.observacion as observacion_importacion,
												da.observacion as observacion_producto
											from 
												g_dda.destinacion_aduanera d,
												g_dda.destinacion_aduanera_productos da,
												g_operadores.operadores o,
												g_catalogos.productos p
											where 
												d.identificador_operador = o.identificador and
												d.id_destinacion_aduanera = da.id_destinacion_aduanera and
												d.id_destinacion_aduanera = $idDestinacionAduanera and
												da.id_producto = p.id_producto;");
		
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
					idDestinacionAduanera=>$fila['id_destinacion_aduanera'],
					identificador=>$fila['identificador_operador'],
					proposito=>$fila['proposito'],
					tipoCertificado=>$fila['tipo_certificado'],
					categoriaProducto=>$fila['categoria_producto'],
					permisoImportacion=>$fila['permiso_importacion'], 
					permisoExportacion=>$fila['permiso_exportacion'], 
					idPuertoDestino=>$fila['id_puerto_destino'],
					nombrePuertoDestino=>$fila['puerto_destino'],
					numeroCarga=>$fila['numero_carga'], 
					tipoTransporte=>$fila['tipo_transporte'],
					numeroTransporte=>$fila['numero_transporte'],
					idLugarInspeccion=>$fila['id_lugar_inspeccion'],
					nombreLugarInspeccion=>$fila['nombre_lugar_inspeccion'],
					observacionImportacion=>$fila['observacion_importacion'],
					codigoCertificado=>$fila['codigo_certificado'],
					idVue=>$fila['id_vue'],
					estado=>$fila['estado_dda'],
					
					idProducto=>$fila['id_producto'],
					nombreProducto=>$fila['nombre_producto'],
					unidad=>$fila['unidad'],
					unidadMedida=>$fila['unidad_medida'],					
					estadoProducto=>$fila['estado_producto'],
					observacionProducto=>$fila['observacion_producto'],
					rutaArchivo=>$fila['ruta_archivo'],					
					partidaArancelaria=>$fila['partida_arancelaria'],
					
					nombreExportador=>$fila['nombre_exportador'],
					direccionExportador=>$fila['direccion_exportador'],
					paisExportacion=>$fila['pais_exportacion'],
					
					razonSocial=>$fila['razon_social'],
					nombreRepresentante=>$fila['nombre_representante'],
					apellidoRepresentante=>$fila['apellido_representante'], 
					provincia=>$fila['provincia'],
					canton=>$fila['canton'],
					parroquia=>$fila['parroquia'],
					direccion=>$fila['direccion']
					);
		}

		return $res;
	}
	
	public function abrirDDAArchivos($conexion, $idDestinacionAduanera){
		$cid = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_dda.documentos_adjuntos
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera;");
		
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
					idDestinacionAduanera=>$fila['id_destinacion_aduanera'],
					identificador=>$fila['identificador'],
					tipoArchivo=>$fila['tipo_archivo'],
					rutaArchivo=>$fila['ruta_archivo'],
					area=>$fila['area'],
					idVue=>$fila['id_vue']);
		}
		
		return $res;
	}
	
	
	////////////////////// EVALUACION DE PRODUCTOS DDA //////////////////////////
	/*OK*/
	public function enviarDDA ($conexion, $idDestinacionAduanera, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_dda.destinacion_aduanera
											set
												estado = '$estado'
											where
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
	
	public function enviarProdctosDDA ($conexion, $idDestinacionAduanera, $estado){
		$res = $conexion->ejecutarConsulta("update
												g_dda.destinacion_aduanera_productos
											set
												estado = '$estado'
											where
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
	
	/*OK*/
	public function evaluarProductosDDA ($conexion, $idDestinacionAduanera, $idProducto, $estado, $observacion, $informe, $peso){
		
		$res = $conexion->ejecutarConsulta("update
												g_dda.destinacion_aduanera_productos
											set
												estado = '$estado',
												observacion ='$observacion',
												ruta_archivo = '$informe',
												peso = $peso,
												unidad_peso = 'KG'
											where
												id_producto = $idProducto and
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
	
	public function evaluarProductosDDAPorNombre ($conexion, $idDestinacionAduanera, $nombreProducto, $estado, $observacion, $peso){
	
		$res = $conexion->ejecutarConsulta("update
												g_dda.destinacion_aduanera_productos
											set
												estado = '$estado',
												observacion ='$observacion',
												peso = $peso,
												unidad_peso = 'KG'
											where
												UPPER(unaccent(nombre_producto)) = upper(unaccent('$nombreProducto')) and
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
	
	public function guardarDatosInspeccion ($conexion, $idImportacion, $idProducto, $identificador, $archivo, $estado, $observacion){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.inspeccion_productos(
												id_importacion, identificador, id_producto, fecha_inspeccion, ruta_archivo,
												estado, observacion)
											VALUES ($idImportacion, '$identificador', $idProducto, now(), '$archivo', '$estado', '$observacion');");
		return $res;
	}
	
	/*OK*/
	public function abrirProductosDDA ($conexion, $idDestinacionAduanera){
		$cid = $conexion->ejecutarConsulta("select 
												* 
											from 
												g_dda.destinacion_aduanera_productos 
											where 
												id_destinacion_aduanera = $idDestinacionAduanera;");
			
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(idOperacion=>$fila['id_destinacion_aduanera'],estado=>$fila['estado'],
					observacion=>$fila['observacion'], peso=>$fila['peso'], unidadPeso=>$fila['unidad_peso'],
					partidaProductoVue=>$fila['partida_producto_vue'], codigoProductoVue=>$fila['codigo_producto_vue']);
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
	
	////////////////////////////// ASIGNACION DE DDA A INSPECTORES PARA REVISION ///////////////////////////////////
	
	/*filtro de operaciones por provincia por asignar*/
	public function listarDDARevisionProvinciaRS ($conexion, $estado, $provincia, $medioTransporte){
		
		/*if($estado == 'enviado'){
			$contadorInspeccion = '1';
		}else{
			$contadorInspeccion = '2';
		}*/
	
		$res = $conexion->ejecutarConsulta("select
												distinct d.id_destinacion_aduanera as id_solicitud,
												d.identificador_operador,
												d.pais_exportacion as pais,
												d.estado, 
												d.tipo_certificado,
												o.razon_social, o.nombre_representante, o.apellido_representante,
												l.nombre,
												d.id_vue,
                                                d.contador_inspeccion,
                                                d.tipo_certificado
											from
												g_dda.destinacion_aduanera d,
												g_operadores.operadores o,
												g_dda.destinacion_aduanera_productos da,
												g_catalogos.lugares_inspeccion li,
												g_catalogos.localizacion l
											where
												d.id_destinacion_aduanera = da.id_destinacion_aduanera and
												d.identificador_operador = o.identificador and
												d.estado in ('$estado') and
												d.lugar_inspeccion = li.id_lugar and
												li.id_provincia = l.id_localizacion and
												UPPER(l.nombre) = UPPER('$provincia') and
												tipo_transporte = '$medioTransporte';");
		return $res;
	}
	
	public function listarDDAAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoSolicitud, $tipoInspector){
		
		/*if($estado == 'asignadoDocumental'){
			$contadorInspeccion = '1';
		}else{
			$contadorInspeccion = '2';
		}*/
	
		$res = $conexion->ejecutarConsulta("select
												distinct d.id_destinacion_aduanera as id_solicitud,
												d.identificador_operador,
												d.pais_exportacion as pais,
												d.estado, 
												d.tipo_certificado,
												o.razon_social, o.nombre_representante, o.apellido_representante,
												d.id_vue,
                                                d.contador_inspeccion,
                                                d.tipo_certificado                    
											from
												g_dda.destinacion_aduanera d,
												g_operadores.operadores o,
												g_dda.destinacion_aduanera_productos da,
												g_revision_solicitudes.asignacion_coordinador ac
											where
												d.id_destinacion_aduanera = da.id_destinacion_aduanera and
												d.identificador_operador = o.identificador and
												d.id_destinacion_aduanera = ac.id_solicitud and
												ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
												d.estado in ('$estado');");
				return $res;
	}
	
	
	////////////////////////////// REVISION DE IMPORTACIONES EN FINANCIERO ///////////////////////////////////
	
	/*filtro de importaciones por estado*/
	public function listarImportacionesRevisionFinancieroRS ($conexion, $estado='pago'){
	
		$res = $conexion->ejecutarConsulta("select
												distinct i.id_destinacion_aduanera as id_solicitud,
												i.identificador_operador,
												i.estado, 
												i.tipo_certificado,
												i.pais_exportacion as pais,
												o.razon_social, o.nombre_representante, o.apellido_representante
											from
												g_dda.destinacion_aduanera i,
												g_operadores.operadores o,
												g_dda.destinacion_aduanera_productos ip
											where
												i.id_destinacion_aduanera = ip.id_destinacion_aduanera and
												i.identificador_operador = o.identificador and
												i.estado in ('$estado');");
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
	
	public function guardarNuevoInspectorFinanciero ($conexion,$idImportacion,$identificadorFinanciero, $idCoordinador){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.importaciones_financiero(
												id_importacion, identificador, fecha_asignacion, identificador_coordinador)
											VALUES ($idImportacion,'$identificadorFinanciero',now(), '$idCoordinador');");
		return $res;
	}
	
	public function guardarDatosMontoFinanciero ($conexion, $idImportacion, $identificador, $monto){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_importaciones.inspeccion_financiero(
												id_importacion, identificador, fecha_inspeccion, monto)
											VALUES ($idImportacion, '$identificador', now(), $monto);");
		return $res;
	}
	
	public function guardarDatosResultadoFinanciero ($conexion, $idImportacion, $identificador, $estado, $observacion, $transaccion){
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_importaciones.inspeccion_financiero 
											SET
												fecha_inspeccion = now(),
												estado = '$estado',
												observacion = '$observacion',
												transaccion = '$transaccion'
											WHERE 
												id_importacion = $idImportacion and 
												identificador = '$identificador';");
		return $res;
	}
	
	public function asignarMontoImportacion ($conexion, $idImportacion, $monto){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												monto = $monto
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	///// FECHA DE VIGENCIA /////
	
	/*public function enviarFechaVigenciaImportacion ($conexion, $idImportacion){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												fecha_inicio = now(),
												fecha_vigencia = now() + interval '120' day
											where
												id_importacion = $idImportacion;");
		return $res;
	}*/
	
	///// TRANSACCION BANCARIA /////
	
	public function enviarTransaccionImportacion ($conexion, $idImportacion, $transaccion){
		$res = $conexion->ejecutarConsulta("update
												g_importaciones.importaciones
											set
												transaccion = '$transaccion'
											where
												id_importacion = $idImportacion;");
		return $res;
	}
	
	////////// ASIGNAR A INSPECTOR ///////////////
	public function abrirDDAInspeccion ($conexion, $idDestinacionAduanera){
		$cid = $conexion->ejecutarConsulta("select
												d.*,
												e.*,
												o.*
											from
												g_dda.destinacion_aduanera d,
												g_dda.exportadores e,
												g_operadores.operadores o
											where
												d.identificador_operador = o.identificador and
												d.id_exportadores = e.id_exportador and
												d.id_destinacion_aduanera = $idDestinacionAduanera;");
	
				while ($fila = pg_fetch_assoc($cid)){
				$res[] = array(
				idDestinacionAduanera=>$fila['id_destinacion_aduanera'],
				identificador=>$fila['identificador_operador'],
				tipoCertificado=>$fila['tipo_certificado'],
				estado=>$fila['estado'],
		
				nombreExportador=>$fila['nombre_exportador'],
				paisExportacion=>$fila['pais_exportacion'],
		
				razonSocial=>$fila['razon_social'],
				nombreRepresentante=>$fila['nombre_representante'],
				apellidoRepresentante=>$fila['apellido_representante']
			);
		}
	
		return $res;
	}
	
	public function guardarNuevoInspector ($conexion,$idDestinacionAduanero,$identificadorInspector, $idCoordinador){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_dda.destinacion_aduanera_inspector(
				id_destinacion_aduanera, identificador, fecha_asignacion, identificador_coordinador)
				VALUES ($idDestinacionAduanera,'$identificadorInspector',now(), '$idCoordinador');");
		return $res;
	}
	
	public function listarInspectoresAsignados ($conexion,$idDestinacionAduanera){
			
		$res = $conexion->ejecutarConsulta("select
												di.*,
												fe.nombre,
												fe.apellido
											from
												g_dda.destinacion_aduanera_inspector di,
												g_uath.ficha_empleado fe
											where
												di.id_destinacion_aduanera = $idDestinacionAduanera and
												di.identificador = fe.identificador;");
				return $res;
	}
	
	/*OK*/
	public function listarHistorialSolicitudes ($conexion, $idSolicitud){
	
		$res = $conexion->ejecutarConsulta("select
												ip.*
											from
												g_dda.destinacion_aduanera_productos ip
											where
												ip.id_destinacion_aduanera = $idSolicitud");
	
		return $res;
	}
	
	public function buscarDDAVUE ($conexion, $identificador, $idVue){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_dda.destinacion_aduanera
											where
												identificador_operador = '$identificador'
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	public function buscarDDAProductoVUE ($conexion, $identificador, $idVue, $producto, $unidad, $unidadMedida){
	
		$res = $conexion->ejecutarConsulta("select
												p.*
											from
												g_dda.destinacion_aduanera i,
												g_dda.destinacion_aduanera_productos p
											where
												i.identificador_operador = '$identificador' and
												i.id_vue = '$idVue' and
												i.id_destinacion_aduanera = p.id_destinacion_aduanera and
												p.id_producto = $producto and
												p.unidad = $unidad and
												p.unidad_medida = '$unidadMedida';");
	
		return $res;
	}
	
	public function actualizarDatosDDA($conexion, $idDestinacionAduanera, $identificador, $nombreExportador, $direccionExportador, $idPaisExportador, $nombrePaisExportador, $proposito, $tipoCertificado, 
									$categoriaProducto, $permisoImportacion, $permisoExportacion, $idPuertoDestino, $nombrePuertoDestino, $numeroCarga, $tipoTransporte, 
									$numeroTransporte, $idLugarInspeccion, $nombreLugarInspeccion, $observacionOperador, $codigoCertificado, $idVue, $estado){

		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												identificador_operador = '$identificador', nombre_exportador = '$nombreExportador', 
										        direccion_exportador = '$direccionExportador', id_pais_exportador = $idPaisExportador, pais_exportacion='$nombrePaisExportador', 
												proposito = '$proposito', tipo_certificado = '$tipoCertificado', categoria_producto = '$categoriaProducto', permiso_importacion = '$permisoImportacion', 
												permiso_exportacion = '$permisoExportacion', id_puerto_destino = $idPuertoDestino, puerto_destino = '$nombrePuertoDestino', numero_carga= '$numeroCarga', tipo_transporte = '$tipoTransporte', 
										        numero_transporte = '$numeroTransporte', lugar_inspeccion= $idLugarInspeccion, nombre_lugar_inspeccion = '$nombreLugarInspeccion', 
										        observacion_operador = '$observacionOperador', codigo_certificado = '$codigoCertificado', estado = '$estado'
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	public function eliminarProductosDDA($conexion, $idDDA){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_dda.destinacion_aduanera_productos
											WHERE
												id_destinacion_aduanera = $idDDA;");
	
		return $res;
	}
	
	public function eliminarArchivosAdjuntos($conexion, $idDDA, $idVue){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_dda.documentos_adjuntos
											WHERE
												id_destinacion_aduanera = $idDDA
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	public function actualizarDatosInspeccionDDA($conexion, $idSolicitud, $fechaEmbarque, $fechaArribo, $numeroContenedores){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												fecha_embarque = '$fechaEmbarque',
												fecha_arribo = '$fechaArribo',
												numero_contenedores = $numeroContenedores
											WHERE
												id_destinacion_aduanera = $idSolicitud;");
	
		return $res;
	}
	
	public function abrirDDAAsignacion ($conexion, $idSolicitud){
			
		$res = $conexion->ejecutarConsulta("select
												i.id_destinacion_aduanera as id_solicitud,
												i.identificador_operador as identificador,
												i.pais_exportacion as pais,
												i.tipo_certificado as tipo_solicitud,
												i.estado,
												case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
											from
												g_dda.destinacion_aduanera i,
												g_operadores.operadores o
											where
												i.id_destinacion_aduanera = $idSolicitud and
												i.identificador_operador = o.identificador");
	
				return $res;
	}
	
	public function abrirDDAProductosAsignacion ($conexion, $idSolicitud){
	
	$cid = $conexion->ejecutarConsulta("select
											nombre_producto
										from
											g_dda.destinacion_aduanera_productos
										where
											id_destinacion_aduanera = $idSolicitud");
	
			while ($fila = pg_fetch_assoc($cid)){
			$prod[] = $fila['nombre_producto'];
	}
	
	$res = implode(', ',$prod);
	
	return $res;
	}
	
	public function obtenerCantidadProductoXimportacion($conexion, $idVue, $idProducto){
				
		$res = $conexion->ejecutarConsulta("SELECT
												SUM(unidad) as cantidad_producto,
												SUM(peso) as peso_producto
											FROM
												g_dda.destinacion_aduanera da,
												g_dda.destinacion_aduanera_productos dp
											WHERE
												da.id_destinacion_aduanera = dp.id_destinacion_aduanera and
												permiso_importacion = '$idVue' and
												id_producto = $idProducto and
												dp.estado IN ('aprobado','rechazado');");
		
		return $res;
	}
	
	public function enviarFechaVigenciaDDA ($conexion, $idDestinacionAduanera){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												fecha_inicio = 'now()'
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera;");
		return $res;
	}
	
	public function actualizarContadorInspeccionDDA($conexion, $idDestinacionAduanera, $contador){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												contador_inspeccion = $contador
											WHERE
												id_destinacion_aduanera = $idDestinacionAduanera;");
		
		return $res;
	}
	
	public function buscarDDAPorIdentificadorVUE ($conexion, $idVue){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_dda.destinacion_aduanera
											where
												id_vue = '$idVue';");
	
		return $res;
	}
	
	public function actualizarPesoInspeccionDDA($conexion, $idSolicitud, $pesoTotal){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												peso_total = $pesoTotal,
												unidad_peso_total = 'KG'
											WHERE
												id_destinacion_aduanera = $idSolicitud;");
	
		return $res;
	}
	
	public function actualizarSeguimientoCuarentenario($conexion, $idSolicitud, $provincia = null){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_dda.destinacion_aduanera
											SET
												estado_seguimiento = 'TRUE',
												provincia_seguimiento = '$provincia'
											WHERE
												id_destinacion_aduanera = $idSolicitud;");
	
		return $res;
	}
	
}