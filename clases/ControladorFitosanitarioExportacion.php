<?php

require_once 'ControladorCatalogos.php';

class ControladorFitosanitarioExportacion{	
	
	public function listarFitosanitarioExportacionPorProvincia($conexion, $provincia, $estado){
		
		$res = $conexion->ejecutarConsulta("SELECT
												id_fitosanitario_exportacion as id_solicitud,							
												id_vue,
												numero_identificacion_solicitante as identificador_operador,
												nombre_pais_destino as pais												
											FROM
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											WHERE
												estado = '$estado' and
												UPPER(nombre_provincia_revision) = UPPER('$provincia');");
		return $res;
	}
	
	public function listarFitosanitarioExportacionPorPorInspectorAsignado($conexion, $estadoSolicitud, $identificadorInspector, $tipoSolicitud, $tipoInspector){
			
		$res = $conexion->ejecutarConsulta("SELECT
												id_fitosanitario_exportacion as id_solicitud,							
												id_vue,
												numero_identificacion_solicitante as identificador_operador,
												nombre_pais_destino as pais
											FROM
												g_fitosanitario_exportacion.fitosanitario_exportaciones fe,
												g_revision_solicitudes.asignacion_coordinador ac
											WHERE
												fe.id_fitosanitario_exportacion = ac.id_solicitud and
												ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitud' and
												ac.tipo_inspector = '$tipoInspector' and
												fe.estado in ('$estadoSolicitud');");
		return $res;
	}
	
	public function obtenerCabeceraFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*												
											FROM
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
		
		return $res;
		
	}
	
	public function obtenerArchivosAdjuntosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
		
		$cid = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.documentos_adjuntos
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		while ($fila = pg_fetch_assoc($cid)){
			$res[] = array(
					idImportacion=>$fila['id_importacion'],
					tipoArchivo=>$fila['tipo_archivo'],
					rutaArchivo=>$fila['ruta_archivo'],
					area=>$fila['area'],
					idVue=>$fila['id_vue']);
		}
	
		return $res;
	}
	
	public function obtenerExportadoresFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_exportadores
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		return $res;
	
	}
	
	public function obtenerProductosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $idFitosanitarioExportador){
					
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_productos
											WHERE
												id_fitosanitario_exportador = $idFitosanitarioExportador and
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		return $res;
	
	}
	
	public function obtenerAreasFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $idFitosanitarioExportador, $idFitosanitarioProducto){
					
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_areas
											WHERE
												id_fitosanitario_exportador = $idFitosanitarioExportador and
												id_fitosanitario_exportacion = $idFitosanitarioExportacion and
												id_fitosanitario_producto = $idFitosanitarioProducto;");
	
		return $res;
	
	}
	
	public function obtenerTransitoFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
	
		$cidFito = $conexion->ejecutarConsulta("SELECT
														*
												FROM
													g_fitosanitario_exportacion.fitosanitario_transportes
												WHERE
													id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		while ($fila = pg_fetch_assoc($cidFito)){
			$res[] = array(
					idPais=>$fila['id_pais'],
					nombrePais=>$fila['nombre_pais'],
					idPuerto=>$fila['id_puerto'],
					nombrePuerto=>$fila['nombre_puerto'],
					tipoTransporte=>$fila['descripcion_tipo_transporte']);
		}
	
		return $res;
	}
	
	public function actualizarEstadoFitosanitarioExportacion ($conexion, $idFitosanitarioExportacion, $estado, $estadoAnterior, $observacion){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											SET
												estado = '$estado',
												estado_anterior = '$estadoAnterior',
												observacion = '$observacion'
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
		return $res;
	}
	
	public function actualizarArchivoInspeccionFitosanitarioExportacion ($conexion, $idFitosanitarioExportacion, $archivoInspeccion, $nombreAprobador, $cargoAprobador, $observacionAprobador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											SET
												archivo_inspeccion = '$archivoInspeccion',
												nombre_aprobador = '$nombreAprobador',
												cargo_aprobador = '$cargoAprobador',
												observacion_aprobador = '$observacionAprobador'
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
		return $res;
	}
	
	public function actualizarProvinciaRevisionFitosanitarioExportacion ($conexion, $idFitosanitarioExportacion, $nombreProvincia, $idProvincia){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											SET
												id_provincia_revision = $idProvincia,
												nombre_provincia_revision = '$nombreProvincia'
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
		return $res;
	}
	
	public function obtenerProgramaProductosPorIdentificadorFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
			
		$res = $conexion->ejecutarConsulta("SELECT
												p.id_producto,
												p.nombre_comun,
												p.partida_arancelaria,
												p.programa,
												fp.cantidad_cobro,
												fp.unidad_cobro,
												fp.exoneracion
											FROM
												g_fitosanitario_exportacion.fitosanitario_productos fp,
												g_catalogos.productos p
											WHERE
												fp.id_producto = p.id_producto and
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		return $res;
	
	}
	
	public function listarFitosanitarioExportacionfinancieroVerificacion($conexion, $estado, $provincia, $tipoSolicitud){
	
		$res = $conexion->ejecutarConsulta("SELECT
												id_fitosanitario_exportacion as id_solicitud,
												id_vue,
												numero_identificacion_solicitante as identificador_operador,
												nombre_pais_destino as pais
											FROM
												g_fitosanitario_exportacion.fitosanitario_exportaciones fe,
												g_revision_solicitudes.asignacion_inspector ai,
												g_revision_solicitudes.grupos_solicitudes gs,
												g_financiero.orden_pago orp
											WHERE
												fe.id_fitosanitario_exportacion = gs.id_solicitud and
												ai.id_grupo = gs.id_grupo and
												ai.tipo_solicitud = '$tipoSolicitud' and
												ai.tipo_inspector = 'Financiero' and
												gs.estado != 'Verificación' and
												orp.id_grupo_solicitud = ai.id_grupo and
												orp.estado = 3 and
												orp.tipo_solicitud = '$tipoSolicitud' and
												fe.estado = '$estado' and
												UPPER(fe.nombre_provincia_revision) = UPPER('$provincia');");
		return $res;
	}
	
	
	public function buscarFitosanitarioExportacionVUE ($conexion, $idVue){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											WHERE
												id_vue = '$idVue';");
		return $res;
	}
	
	
	public function guardarFitosanitarioExportacion($conexion, $idVue=null, $numeroDocumento,
			$nombreDocumento, $codigoFuncionDocumento, $idCiudadSolictud, $codigoCiudadSolicitud, $nombreCiudadSolicitud,
			$codigoTipoCfe, $nombreTipoCfe, $codigoIdioma, $codigoClasificacionSolicitante,
			$numeroIdentificacionSolicitante, $razonSocialSolicitante, $nombreRepresentanteLegalSolicitante, $direccionSolicitante, $telefonoSolicitante,
			$correoSolicitante, $nombreImportador, $direccionImportador, $productoOrganico, $certificadoOrganico, $numeroBultos, $unidadBultos,
			$idPaisOrigen, $nombrePaisOrigen, $idProvinciaOrigenProducto, $nombreProvinciaOrigenProducto, $identificacionAgenciaCarga, $nombreAgenciaCarga,
			$idPaisDestino, $nombrePaisDestino, $idPuertoDestino, $nombrePuertoDestino, $fechaEmbarque, $idPaisEmbarque, $nombrePaisEmbarque,
			$idPuertoEmbarque, $nombrePuertoEmbarque, $codigoMedioTransporte, $nombreMedioTransporte, $nombreMarca, $numeroViaje, $informacionAdicional,
			$descuento, $motivoDescuento, $nombreAprobador, $cargoAprobador, $observacionAprobador, $usoCiudadTransito, $lugarInspeccion){
									
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fitosanitario_exportacion.fitosanitario_exportaciones(id_vue, numero_documento,
												nombre_documento, codigo_funcion_documento, id_ciudad_solicitante, codigo_ciudad_solicitud, nombre_ciudad_solicitud,
												codigo_tipo_cfe, nombre_tipo_cfe, codigo_idioma, codigo_clasificacion_solicitante,
												numero_identificacion_solicitante, razon_social_solicitante, nombre_representante_legal_solicitante, direccion_solicitante, telefono_solicitante,
												correo_electronico_solicitante, nombre_importador, direccion_importador, producto_organico, certificado_organico, numero_bultos, unidad_bultos,
												id_pais_origen, nombre_pais_origen, id_provincia_origen_producto, nombre_provincia_origen_producto, identificacion_agencia_carga, nombre_agencia_carga,
												id_pais_destino, nombre_pais_destino, id_puerto_destino, nombre_puerto_destino, fecha_embarque, id_pais_embarque, nombre_pais_embarque,
												id_puerto_embarque, nombre_puerto_embarque, codigo_medio_transporte, nombre_medio_transporte, nombre_marca, numero_viaje, informacion_adicional,
												descuento, motivo_descuento, nombre_aprobador, cargo_aprobador, observacion_aprobador, uso_ciudad_transito, lugar_inspeccion)
											VALUES ('$idVue','$numeroDocumento',
												'$nombreDocumento','$codigoFuncionDocumento',$idCiudadSolictud,'$codigoCiudadSolicitud','$nombreCiudadSolicitud',
												'$codigoTipoCfe','$nombreTipoCfe','$codigoIdioma', '$codigoClasificacionSolicitante',
												'$numeroIdentificacionSolicitante', '$razonSocialSolicitante', '$nombreRepresentanteLegalSolicitante', '$direccionSolicitante', '$telefonoSolicitante',
												'$correoSolicitante','$nombreImportador','$direccionImportador','$productoOrganico','$certificadoOrganico', $numeroBultos, '$unidadBultos',
												$idPaisOrigen, '$nombrePaisOrigen', $idProvinciaOrigenProducto, '$nombreProvinciaOrigenProducto', '$identificacionAgenciaCarga', '$nombreAgenciaCarga',
												$idPaisDestino, '$nombrePaisDestino', $idPuertoDestino, '$nombrePuertoDestino', '$fechaEmbarque', $idPaisEmbarque, '$nombrePaisEmbarque',
												$idPuertoEmbarque, '$nombrePuertoEmbarque', '$codigoMedioTransporte', '$nombreMedioTransporte', '$nombreMarca', '$numeroViaje', '$informacionAdicional',
												'$descuento', '$motivoDescuento', '$nombreAprobador', '$cargoAprobador', '$observacionAprobador', '$usoCiudadTransito', '$lugarInspeccion') RETURNING id_fitosanitario_exportacion;");
			
		return $res;
	}
	
	public function actualizarFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $idVue, $numeroDocumento,
			$nombreDocumento, $codigoFuncionDocumento, $idCiudadSolictud, $codigoCiudadSolicitud, $nombreCiudadSolicitud,
			$codigoTipoCfe, $nombreTipoCfe, $codigoIdioma,  $codigoClasificacionSolicitante,
			$numeroIdentificacionSolicitante, $razonSocialSolicitante, $nombreRepresentanteLegalSolicitante, $direccionSolicitante, $telefonoSolicitante,
			$correoSolicitante, $nombreImportador, $direccionImportador, $productoOrganico, $certificadoOrganico, $numeroBultos, $unidadBultos,
			$idPaisOrigen, $nombrePaisOrigen, $idProvinciaOrigenProducto, $nombreProvinciaOrigenProducto, $identificacionAgenciaCarga, $nombreAgenciaCarga,
			$idPaisDestino, $nombrePaisDestino, $idPuertoDestino, $nombrePuertoDestino, $fechaEmbarque, $idPaisEmbarque, $nombrePaisEmbarque,
			$idPuertoEmbarque, $nombrePuertoEmbarque, $codigoMedioTransporte, $nombreMedioTransporte, $nombreMarca, $numeroViaje, $informacionAdicional,
			$descuento, $motivoDescuento, $nombreAprobador, $cargoAprobador, $observacionAprobador, $usoCiudadTransito, $lugarInspeccion){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											SET
												numero_documento='$numeroDocumento', nombre_documento='$nombreDocumento', codigo_funcion_documento='$codigoFuncionDocumento', 
												id_ciudad_solicitante=$idCiudadSolictud, codigo_ciudad_solicitud='$codigoCiudadSolicitud', nombre_ciudad_solicitud='$nombreCiudadSolicitud',
												codigo_tipo_cfe='$codigoTipoCfe', nombre_tipo_cfe='$nombreTipoCfe', codigo_idioma='$codigoIdioma', codigo_clasificacion_solicitante='$codigoClasificacionSolicitante',
												numero_identificacion_solicitante='$numeroIdentificacionSolicitante', razon_social_solicitante='$razonSocialSolicitante',
												nombre_representante_legal_solicitante='$nombreRepresentanteLegalSolicitante', direccion_solicitante='$direccionSolicitante', telefono_solicitante='$telefonoSolicitante',
												correo_electronico_solicitante='$correoSolicitante', nombre_importador='$nombreImportador', direccion_importador='$direccionImportador', producto_organico='$productoOrganico', 
												certificado_organico='$certificadoOrganico', numero_bultos=$numeroBultos, unidad_bultos='$unidadBultos',
												id_pais_origen=$idPaisOrigen, nombre_pais_origen='$nombrePaisOrigen', id_provincia_origen_producto=$idProvinciaOrigenProducto, 
												nombre_provincia_origen_producto='$nombreProvinciaOrigenProducto', identificacion_agencia_carga='$identificacionAgenciaCarga', nombre_agencia_carga='$nombreAgenciaCarga',
												id_pais_destino=$idPaisDestino, nombre_pais_destino='$nombrePaisDestino', id_puerto_destino=$idPuertoDestino, nombre_puerto_destino='$nombrePuertoDestino', 
												fecha_embarque='$fechaEmbarque', id_pais_embarque=$idPaisEmbarque, nombre_pais_embarque='$nombrePaisEmbarque',
												id_puerto_embarque=$idPuertoEmbarque, nombre_puerto_embarque='$nombrePuertoEmbarque', codigo_medio_transporte='$codigoMedioTransporte', 
												nombre_medio_transporte='$nombreMedioTransporte', nombre_marca='$nombreMarca', numero_viaje='$numeroViaje', informacion_adicional='$informacionAdicional',
												descuento='$descuento', motivo_descuento='$motivoDescuento', nombre_aprobador='$nombreAprobador', cargo_aprobador='$cargoAprobador', observacion_aprobador='$observacionAprobador', 
												uso_ciudad_transito='$usoCiudadTransito', lugar_inspeccion = '$lugarInspeccion'
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		return $res;
	}
	
	public function eliminarExportadoresFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fitosanitario_exportacion.fitosanitario_exportadores
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
			
		return $res;
	}
	
	
	public function eliminarProductosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fitosanitario_exportacion.fitosanitario_productos
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
		return $res;
	}
	
	
	
	public function eliminarAreasFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fitosanitario_exportacion.fitosanitario_areas
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		return $res;
	}
	
	
	public function eliminaTransitoFitosanitarioExportacion($conexion, $idFitosanitarioExportacion){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fitosanitario_exportacion.fitosanitario_transportes
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		return $res;
	}
	
	public function eliminarArchivosAdjuntosFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $idVue){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_fitosanitario_exportacion.documentos_adjuntos
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion
												and id_vue = '$idVue';");
	
		return $res;
	}
	
	
	public function guardarFitosanitarioExportacionExportadores($conexion, $id_fitosanitario_exportacion, $idVue, $codigoClasificacionIdentificacionExportador, $codigoTipoNumeroIdentificacionExportador,
			$numeroIdentificacionExportador, $nombreExportador, $direccionExportador) {
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fitosanitario_exportacion.fitosanitario_exportadores(
														id_fitosanitario_exportacion, id_vue, codigo_clasificacion_identificacion_exportador, codigo_tipo_numero_identificacion_exportador,
														numero_identificacion_exportador, nombre_exportador, direccion_exportador)
													VALUES (
														'$id_fitosanitario_exportacion', '$idVue', '$codigoClasificacionIdentificacionExportador', '$codigoTipoNumeroIdentificacionExportador',
														'$numeroIdentificacionExportador', '$nombreExportador', '$direccionExportador')RETURNING id_fitosanitario_exportador;");
	
		return $res;
	}
	
	
	public function guardarFitosanitarioExportacionProductos($conexion, $idFitosanitarioExportador, $idFitosanitarioExportacion, $idVue, $subpartidaArancelaria, $codigoProducto, $idProducto, $nombreProducto,
			$cantidadCobro, $unidadCobro, $cantidadPesoNeto, $unidadPesoNeto, $cantidadPesoBruto, $unidadPesoBruto, $cantidadComercial, $unidadCantidadComercial,
			$codigoTipoTratamiento, $descripcionTipoTratamiento, $codigoNombreTratamiento, $descripcionNombreTratamiento, $duracionTratamiento, $unidadTratamiento,
			$temperaturaTratamiento, $unidadTemperaturaTratamiento, $concentracionProductoQuimico, $fechaTratamiento, $productoQuimico, $informacionAdicional,
			$requisitoFitosanitario, $exoneracion) {
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fitosanitario_exportacion.fitosanitario_productos(
												id_fitosanitario_exportador, id_fitosanitario_exportacion, id_vue, subpartida_arancelaria, codigo_producto, id_producto, nombre_producto,
												cantidad_cobro, unidad_cobro, cantidad_peso_neto, unidad_peso_neto, cantidad_peso_bruto, unidad_peso_bruto, cantidad_comercial, unidad_cantidad_comercial,
												codigo_tipo_tratamiento, descripcion_tipo_tratamiento, codigo_nombre_tratamiento, descripcion_nombre_tratamiento, duracion_tratamiento, unidad_tratamiento,
												temperatura_tratamiento, unidad_temperatura_tratamiento, concentracion_producto_quimico, fecha_tratamiento, producto_quimico, informacion_adicional,
												requisito_fitosanitario, exoneracion)
											VALUES (
												$idFitosanitarioExportador, $idFitosanitarioExportacion, '$idVue', '$subpartidaArancelaria', '$codigoProducto', $idProducto, '$nombreProducto',
												$cantidadCobro, '$unidadCobro', $cantidadPesoNeto, '$unidadPesoNeto', $cantidadPesoBruto, '$unidadPesoBruto', $cantidadComercial, '$unidadCantidadComercial',
												'$codigoTipoTratamiento', '$descripcionTipoTratamiento', '$codigoNombreTratamiento', '$descripcionNombreTratamiento', '$duracionTratamiento', '$unidadTratamiento',
												'$temperaturaTratamiento', '$unidadTemperaturaTratamiento', '$concentracionProductoQuimico', $fechaTratamiento, '$productoQuimico', '$informacionAdicional',
											    '$requisitoFitosanitario', '$exoneracion')RETURNING id_fitosanitario_producto;");
	
		return $res;
	}
		
		
	public function guardarFitosanitarioAreasExportadores($conexion, $idFitosanitarioExportador, $idFitosanitarioExportacion, $idFitosanitarioProducto, $idVue, $idArea, $codigoAreaAgrocalidad, $numeroAucp ) {
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fitosanitario_exportacion.fitosanitario_areas(id_fitosanitario_exportador, id_fitosanitario_exportacion, 
														id_fitosanitario_producto, id_vue, id_area, codigo_area_agrocalidad_unibanano, numero_aucp)
												VALUES ($idFitosanitarioExportador, $idFitosanitarioExportacion, $idFitosanitarioProducto, '$idVue', $idArea, 
														'$codigoAreaAgrocalidad', '$numeroAucp');");
	
		return $res;
	}
	
	
	public function guardarFitosanitarioAreasTrasportes($conexion, $idFitosanitarioExportacion, $idVue, $codigoTipoTransporte, $descripcionTipoTransporte, $idPais, $nombrePais, $idPuerto, $nombrePuerto, $requisitoFitosanitarioTransito) {
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_fitosanitario_exportacion.fitosanitario_transportes(id_fitosanitario_exportacion, id_vue, codigo_tipo_transporte, 
														descripcion_tipo_transporte, id_pais, nombre_pais, id_puerto, nombre_puerto, requisito_fitosanitario_transito)
											VALUES ($idFitosanitarioExportacion, '$idVue', '$codigoTipoTransporte', '$descripcionTipoTransporte', $idPais, '$nombrePais', 
													$idPuerto, '$nombrePuerto', '$requisitoFitosanitarioTransito');");
			
		return $res;
	}
	
	public function buscarFitosanitarioExportacionExportador($conexion, $idFitosanitarioExportacion, $idExportador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_exportadores
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion and
												numero_identificacion_exportador = '$idExportador';");
		return $res;
	}
	
	public function buscarFitosanitarioExportacionTransporte ($conexion, $idFitosanitarioExportacion, $idPais, $idPuerto, $codigoTransporte){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_transportes
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion and
												id_pais = $idPais and
												id_puerto = $idPuerto and
												codigo_tipo_transporte = '$codigoTransporte';");
		return $res;
	}
	
	public function buscarFitosanitarioExportacionProducto($conexion, $idFitosanitarioExportador, $idFitosanitarioExportacion, $idProducto){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_productos
											WHERE
												id_fitosanitario_exportador = $idFitosanitarioExportador and
												id_fitosanitario_exportacion = $idFitosanitarioExportacion and
												id_producto = $idProducto;");
		return $res;
	}
	
	public function buscarFitosanitarioExportacionArea($conexion, $idFitosanitarioExportador, $idFitosanitarioExportacion, $idFitosanitarioProducto, $idArea){
					
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.fitosanitario_areas
											WHERE
												id_fitosanitario_exportador = $idFitosanitarioExportador and
												id_fitosanitario_exportacion = $idFitosanitarioExportacion and
												id_fitosanitario_producto = $idFitosanitarioProducto and
												id_area = $idArea;");
		return $res;
	}
	
	public function modificarCabeceraFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $nombreImportador, $direccionImportador, $idPuertoDestino, $puertoDestino, $informacionAdicional){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											SET
												nombre_importador='$nombreImportador', 
												direccion_importador='$direccionImportador',
												id_puerto_destino=$idPuertoDestino, 
												nombre_puerto_destino='$puertoDestino', 
												informacion_adicional='$informacionAdicional'
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
	
		return $res;
	}
	
	public function modificarProductoFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $idProducto, $identificadorExportador, $informacionAdicional){
			
		$res = $conexion->ejecutarConsulta("UPDATE
												g_fitosanitario_exportacion.fitosanitario_productos
											SET
												informacion_adicional='$informacionAdicional'
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion and
												id_fitosanitario_exportador = $identificadorExportador and
												id_producto = $idProducto;");
									
		return $res;
	}
	
	public function guardarFitosanitarioExportacionDocumentosAdjuntos($conexion, $idFitosanitarioExportacion, $tipoArchivo, $rutaArchivo, $area,$idVue = null){
	
		$documento = $this->abrirFitosanitarioExportacionArchivoIndividual($conexion, $idFitosanitarioExportacion, $tipoArchivo);
	
		if(pg_num_rows($documento)== 0){
	
			$res = $conexion->ejecutarConsulta("INSERT INTO g_fitosanitario_exportacion.documentos_adjuntos(
													id_fitosanitario_exportacion, tipo_archivo, ruta_archivo, area, id_vue)
												VALUES ('$idFitosanitarioExportacion', '$tipoArchivo', '$rutaArchivo', '$area', '$idVue');");
	
			return $res;
		}
	}
	
	public function abrirFitosanitarioExportacionArchivoIndividual($conexion, $idFitosanitarioExportacion, $tipoArchivo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_fitosanitario_exportacion.documentos_adjuntos
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion
												and tipo_archivo = '$tipoArchivo';");
	
		return $res;
	}
	

	public function guardarSolicitanteFitosanitarioExportacion($conexion, $identificacionSolicitante, $tipoIdentificacion, $razonSocial, $direccionSolicitante, $telefonoSolicitante, $correoElectronicoSolicitante){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.clientes (identificador,  tipo_identificacion,  razon_social,  direccion,  telefono,  correo)
												SELECT '$identificacionSolicitante', '$tipoIdentificacion', '$razonSocial', '$direccionSolicitante', '$telefonoSolicitante', '$correoElectronicoSolicitante'  WHERE NOT EXISTS (SELECT identificador FROM g_financiero.clientes
											WHERE identificador = '$identificacionSolicitante')");
			
		return $res;
	}
	
	public function actualizarFechasAprobacionFitosanitarioExportacion ($conexion, $idFitosanitarioExportacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_fitosanitario_exportacion.fitosanitario_exportaciones
											SET
												fecha_inicio_vigencia_certificado = now(),
												fecha_fin_vigencia_certificado = now() + '10 year'
											WHERE
												id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
		return $res;
	}
	

	///////////////////////////////
	
	public function guardarNotificacionesCFE($conexion, $numeroNotificacion, $fechaNotificacion, $motivoNotificacion, $observacionNotificacion, $numeroCFE, $identificadorExportador, $razonSocial, $pais, $estadoCFE, $idTipoProducto, $idSubtipoProducto, $idProducto, $nombreProducto, $idPaisDestino) {
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_notificaciones_sanciones.notificaciones(numero_notificacion, fecha_notificacion, motivo_notificacion, observacion_notificacion, numero_cfe,
													identificador_exportador, razon_social, pais, estado, id_tipo_producto, id_subtipo_producto, id_producto, nombre_producto, id_pais)
											VALUES (
											'$numeroNotificacion', '$fechaNotificacion', '$motivoNotificacion', '$observacionNotificacion',
											'$numeroCFE', '$identificadorExportador', '$razonSocial', '$pais', '$estadoCFE', $idTipoProducto, $idSubtipoProducto, $idProducto, '$nombreProducto', $idPaisDestino);");
	
		return $res;
	}
	
	public function guardarSancionesCFE($conexion, $identificadorExportador, $razonSocial, $idTipoProducto, $idSubtipoProducto, $idProducto, $nombreProducto, $idPais, $fechaInicioSancion, $fechaFinSancion, $motivoSancion, $estadoSancion, $observacionSancion, $nombrePais) {
	
				$res = $conexion->ejecutarConsulta("INSERT INTO g_notificaciones_sanciones.sanciones(identificador_exportador,  razon_social,  id_tipo_producto,  id_subtipo_producto, id_producto, nombre_producto, id_pais,
														fecha_inicio_sancion, fecha_fin_sancion, motivo_sancion, estado_sancion, observacion_sancion, nombre_pais)
													VALUES (
														'$identificadorExportador', '$razonSocial', $idTipoProducto, $idSubtipoProducto, $idProducto, '$nombreProducto', $idPais, '$fechaInicioSancion', '$fechaFinSancion', '$motivoSancion', '$estadoSancion', '$observacionSancion', '$nombrePais');");
		return $res;
	}
	
	public function obtenerNotificacionesXExportador($conexion, $identificadorExportador){
	
	$res = $conexion->ejecutarConsulta("SELECT
											*
										FROM
											g_notificaciones_sanciones.notificaciones
										WHERE
											identificador_exportador = '$identificadorExportador';");
	
			return $res;
	}
	
	public function obtenerSancionesXExportador($conexion, $identificadorExportador){
	
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_notificaciones_sanciones.sanciones
												WHERE
													identificador_exportador = '$identificadorExportador';");
	
			return $res;
	}
	
	
	public function obtenerPaisesSancionesXExportador($conexion, $identificadorExportador){
	
			$res = $conexion->ejecutarConsulta("SELECT
													distinct (fe.id_pais_destino), fe.nombre_pais_destino
												FROM
													g_fitosanitario_exportacion.fitosanitario_exportaciones fe,
													g_fitosanitario_exportacion.fitosanitario_exportadores fex,
													g_fitosanitario_exportacion.fitosanitario_productos p,
													g_notificaciones_sanciones.notificaciones n
												WHERE
													fe.id_fitosanitario_exportacion = fex.id_fitosanitario_exportacion and
													fex.numero_identificacion_exportador='$identificadorExportador' and
													fex.numero_identificacion_exportador = n.identificador_exportador and
													n.id_producto = p.id_producto;");
	
		return $res;
	}
	

	public function obtenerTipoProductoXExportador($conexion, $idFitosanitarioExportacion, $idFitosanitarioExportador){
	
				$res = $conexion->ejecutarConsulta("SELECT 
														*
													FROM
														g_catalogos.subtipo_productos dp,
														g_catalogos.tipo_productos tp,
														g_catalogos.productos p,
														g_fitosanitario_exportacion.fitosanitario_productos fp
													WHERE
														dp.id_tipo_producto = tp.id_tipo_producto and
														dp.id_subtipo_producto = p.id_subtipo_producto and
														fp.id_fitosanitario_exportador = $idFitosanitarioExportador and
														fp.id_fitosanitario_exportacion = $idFitosanitarioExportacion and
														p.id_producto = fp.id_producto;");
		return $res;
	}
	
			
	public function obtenerSubtipoProductoXExportadorXTipo($conexion, $idFitosanitarioExportacion, $idFitosanitarioExportador, $idTipoProducto){
						
					$res = $conexion->ejecutarConsulta("SELECT
															dp.id_subtipo_producto, dp.nombre
														FROM
															g_catalogos.subtipo_productos dp,
															g_catalogos.tipo_productos tp,
															g_catalogos.productos p,
															g_fitosanitario_exportacion.fitosanitario_productos fp
														WHERE
															dp.id_tipo_producto = tp.id_tipo_producto and
															dp.id_subtipo_producto = p.id_subtipo_producto and
															fp.id_fitosanitario_exportador = $idFitosanitarioExportador and
															fp.id_fitosanitario_exportacion = $idFitosanitarioExportacion and
															tp.id_tipo_producto = $idTipoProducto and
															p.id_producto = fp.id_producto;");
		return $res;
	}
	
	public function obtenerProductoXExportadorXSubtipo($conexion, $idFitosanitarioExportacion, $idFitosanitarioExportador, $idSubtipoProducto){
								
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_catalogos.productos p,
														g_catalogos.subtipo_productos stp,
														g_fitosanitario_exportacion.fitosanitario_productos fp
													WHERE
														p.id_subtipo_producto = stp.id_subtipo_producto	and
														p.id_subtipo_producto = $idSubtipoProducto and
														fp.id_fitosanitario_exportador = $idFitosanitarioExportador and
														fp.id_fitosanitario_exportacion = $idFitosanitarioExportacion and
														p.id_producto = fp.id_producto;");
		return $res;
	}
	
	
	public function obtenerTipoProductoXExportadorSancion($conexion, $identificadorExportador){
	
				$res = $conexion->ejecutarConsulta("SELECT
														distinct(tp.id_tipo_producto),
														tp.nombre
													FROM
														g_catalogos.subtipo_productos dp,
														g_catalogos.tipo_productos tp,
														g_catalogos.productos p,
														g_notificaciones_sanciones.notificaciones nt
													WHERE
														dp.id_tipo_producto = tp.id_tipo_producto and
														dp.id_subtipo_producto = p.id_subtipo_producto and
														p.id_producto = nt.id_producto and
														nt.identificador_exportador = '$identificadorExportador';");
		return $res;
	}
	
	public function obtenerSubtipoProductoXExportadorXTipoSancion($conexion, $idTipoProducto){
										
				$res = $conexion->ejecutarConsulta("SELECT
														distinct (dp.id_subtipo_producto), dp.nombre
													FROM
														g_catalogos.subtipo_productos dp,
														g_catalogos.tipo_productos tp,
														g_catalogos.productos p,
														g_notificaciones_sanciones.notificaciones nt
													WHERE
														dp.id_tipo_producto = tp.id_tipo_producto and
														dp.id_subtipo_producto = p.id_subtipo_producto and
														p.id_producto = nt.id_producto and
														tp.id_tipo_producto = $idTipoProducto;");
		return $res;
	}
	
	public function obtenerProductoXExportadorXSubtipoSancion($conexion, $idSubtipoProducto){
										
				$res = $conexion->ejecutarConsulta("SELECT
														distinct (p.id_producto), p.nombre_comun
													FROM
														g_catalogos.productos p,
														g_catalogos.subtipo_productos stp,
														g_notificaciones_sanciones.notificaciones nt
													WHERE
														p.id_subtipo_producto = stp.id_subtipo_producto	and
														p.id_subtipo_producto = $idSubtipoProducto and
														p.id_producto = nt.id_producto;");
				return $res;
		}
	
	
			///ESTA ESTA EN VEREMOS--PREGUNTAR SI SE VALIDA VARIAS NOTIFICACIONES A UN MISMO EXPORTADOR EN UN MISMO CFE
		public function buscarNotificacionesXSolicitudXExportador($conexion, $numeroCFE, $identificadorExportador){
				
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_notificaciones_sanciones.notificaciones
												WHERE
													numero_cfe = '$numeroCFE'	and
													identificador_exportador = '$identificadorExportador';");
				return $res;
		}
	
	
		public function buscarNotificacionesXRucXRazonsocialXPais($conexion, $identificadorExportador, $razonSocial, $idPais){
				
			$identificadorExportador = $identificadorExportador!="" ? "'" . $identificadorExportador . "'" : "null";
			$razonSocial = $razonSocial!="" ? "'%" . $razonSocial . "%'" : "null";
			$idPais = $idPais!="" ?  $idPais : "null";
	
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_notificaciones_sanciones.notificaciones
												WHERE
													identificador_exportador = $identificadorExportador or
													razon_social ilike $razonSocial or
													id_pais = $idPais;");
				return $res;
		}
	
		public function buscarSancionesXRucXRazonsocialXPais($conexion, $identificadorExportador, $razonSocial, $idPais){
		
			$identificadorExportador = $identificadorExportador!="" ? "'" . $identificadorExportador . "'" : "null";
			$razonSocial = $razonSocial!="" ? "'%" . $razonSocial . "%'" : "null";
			$idPais = $idPais!="" ? $idPais : "null";
	
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_notificaciones_sanciones.sanciones
												WHERE
													identificador_exportador = $identificadorExportador or
													razon_social ilike $razonSocial or
													id_pais = $idPais;");
			return $res;
		}
	
		public function buscarNotificacionesXId($conexion, $idNotificacion){
				
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_notificaciones_sanciones.notificaciones
												WHERE
													id_notificacion = $idNotificacion;");
			return $res;
		}
	
		public function buscarSancionesXId($conexion, $idSancion){
					
				$res = $conexion->ejecutarConsulta("SELECT
														*
													FROM
														g_notificaciones_sanciones.sanciones
													WHERE
														id_sancion = $idSancion;");
				return $res;
		}
		
		public function actualizarFechaInspeccionFitosanitarioExportacion($conexion, $idFitosanitarioExportacion, $fechaInspeccion){
				
			$res = $conexion->ejecutarConsulta("UPDATE
													g_fitosanitario_exportacion.fitosanitario_exportaciones
												SET
													fecha_inspeccion='$fechaInspeccion'
												WHERE
													id_fitosanitario_exportacion = $idFitosanitarioExportacion;");
				
			return $res;
		}
		
		public function consultarSancionACaducar($conexion, $estadoSancion){
			$res = $conexion->ejecutarConsulta("SELECT
										            id_sancion,
										            identificador_exportador
										        FROM
										            g_notificaciones_sanciones.sanciones
										        WHERE
										            estado_sancion='".$estadoSancion."'
										            and fecha_fin_sancion=current_date;");
			return $res;
		}
		
		public function actualizarEstadoSancion($conexion, $idSancion,$estadoSancion){
			$res = $conexion->ejecutarConsulta("UPDATE 
													g_notificaciones_sanciones.sanciones
												SET
													estado_sancion = '$estadoSancion'
												WHERE
													id_sancion = '$idSancion';");
			return $res;
		}
		
		//---------------------------------------------------------------------------FUNCIONES DE WEB SERVICES -----------------------------------------------------------------------------------------------
		
		public function obtenerFitosanitarioExportacionWebServicesEstado($conexion, $estadoWebServices){
		    
		    $consulta = "SELECT
							*												
						FROM
							g_fitosanitario_exportacion.fitosanitario_exportaciones
						WHERE
							estado_web_services = '$estadoWebServices';";
		    
		    $res = $conexion->ejecutarConsulta($consulta);
		    
		    return $res;
		    
		}
		
		public function obtenerFitosanitarioExportadorWebServicesXId($conexion, $idFitosanitarioExportacion){
		
			$consulta = "SELECT 
							*
  						FROM 
  							g_fitosanitario_exportacion.fitosanitario_exportadores
						WHERE
							id_fitosanitario_exportacion = '$idFitosanitarioExportacion';";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		
		}
		
		public function obtenerFitosanitarioProductosWebServicesXId($conexion, $idFitosanitarioExportacion){
		
			$consulta = "SELECT
                			*
                		FROM
                			g_fitosanitario_exportacion.fitosanitario_productos
                		WHERE
                			id_fitosanitario_exportacion = '$idFitosanitarioExportacion';";
		
			$res = $conexion->ejecutarConsulta($consulta);
		
			return $res;
		
		}
		
		public function obtenerCodigoMedioTrasnporte($codigoTransporte){
		    
		    switch ($codigoTransporte){
		        case 'AE':
		            $codigo = '4';
		        break;
		        case 'FL':
		        case 'MA':
		            $codigo = '1';
		        break;
		        case 'TE':
		            $codigo = '3';
		        break;
		        default:
		            $codigo = '0';
		    }
		  		    
		    return $codigo;
		    
		}
		
		public function obtenerNombreMedioTransporteHub($codigoTransporte){
		
			switch ($codigoTransporte){
				case '4':
					$codigo = 'Aéreo';
					break;
				case '1':
					$codigo = 'Marítimo';
					break;
				case '3':
					$codigo = 'Terrestre';
					break;
				default:
					$codigo = 'Ninguno';
			}
		
			return $codigo;
		
		}
		
		public function obtenerCodigoUnidadMedida($nomenclatura){
		    
		    $nomenclatura = strtoupper($nomenclatura);
		    
		    switch ($nomenclatura){
		        case 'KG':
		            $codigo = 'KGM';
		        break;
		        case 'M3':
		            $codigo = 'MTQ';
		        break;
		        case 'C':
		            $codigo = 'Celsius';
		        break;
		        case 'F':
		            $codigo = 'Fahrenheit';
		        break;
		        
		        default:
		            $codigo = $nomenclatura;
		    }
		    
		    return $codigo;
		    
		}
		
		public function obtenerCodigoTratamiento($nomenclatura){
		    
		    $nomenclatura = strtoupper($nomenclatura);
		    
		    switch ($nomenclatura){
		        case 'HORAS':
		            $codigo = 'HUR';
		            break;
		            
		        default:
		            $codigo = $nomenclatura;
		    }
		    
		    return $codigo;
		    
		}
		
		public function buscarFitosanitarioExportacionPorFechaEstado($conexion, $datos){
		    		    
		    $fecha_inicio = $datos['fecha_desde'];
		    $fecha_fin = $datos['fecha_hasta'];
		    $estado = $datos['estado'];
		    
		    $busqueda = '';
		    
		    if($fecha_inicio != ''){
		        $busqueda .= " and fecha_inicio_vigencia_certificado >=  '$fecha_inicio 00:00:00'";
		    }
		      
		    if($fecha_fin != ''){
		        $busqueda .= " and fecha_inicio_vigencia_certificado <=  '$fecha_fin 24:00:00'";
		    }
		    
		    $solicitudes = array();
		    		    
		    $consulta = "SELECT
                			id_vue
                		FROM
                			g_fitosanitario_exportacion.fitosanitario_exportaciones
                		WHERE
                            estado = '$estado'
                            ".$busqueda."                            
                            and estado_web_services = 'POR ATENDER';";
		    		    		    
		    $res = $conexion->ejecutarConsulta($consulta);
		    
		    while ($fila = pg_fetch_assoc($res)){
		        $solicitudes[] = $fila['id_vue'];
		    }
		    		    		    		    
		    return $solicitudes;
		    
		}
		
		public function buscarFitosanitarioExportacionPorIdentificador($conexion, $numeroCertificado, $requiereFirma= false){
						
			$idVue = $numeroCertificado['numero_certificado'];
			
			$consulta = "SELECT
							*
						FROM
							g_fitosanitario_exportacion.fitosanitario_exportaciones
						WHERE
							id_vue = '$idVue';";
						
			$res = $conexion->ejecutarConsulta($consulta);
			
			if(pg_num_rows($res)!=0){
			    $rutaXML = $this->generarXMLFitosanitarioExportacion($conexion, $res);
			    
			    if($requiereFirma){
			        $rutaXML = $this-> firmarDocumentoFitosanitarioExportacion($rutaXML);
			    }
			    
			    $xml = file_get_contents($rutaXML);
			    
			    return $xml;
			}else{
			    return 'Documento no encontrado';
			}
			
			
			
		}
		
		public function firmarDocumentoFitosanitarioExportacion($rutaXml){
		    
		    //TODO: Realziar proceso de firmado
		    
		}
		
		public function generarXMLFitosanitarioExportacion($conexion, $datosFitosanitario){
		    
            $cc = new ControladorCatalogos();
		    
            $fitosanitario = pg_fetch_assoc($datosFitosanitario);
		    
		 	$idVue=$fitosanitario['id_vue'];
            //Generar archivo xml
            $xml = new DomDocument('1.0', 'UTF-8');
            
            //Nodo principal
            $root = $xml->createElement('rsm:SPSCertificate');
            $root = $xml->appendChild($root);
            
                $atributo = $xml->createAttribute('xsi:schemaLocation');
                $root->appendChild($atributo);
                $atributoValor = $xml->createTextNode('urn:un:unece:uncefact:data:standard:SPSCertificate:17 SPSCertificate_17p0.xsd');
                $atributo->appendChild($atributoValor);
                
                $atributo = $xml->createAttribute('xmlns:xsi');
                $root->appendChild($atributo);
                $atributoValor = $xml->createTextNode('http://www.w3.org/2001/XMLSchema-instance');
                $atributo->appendChild($atributoValor);
                
                $atributo = $xml->createAttribute('xmlns:ram');
                $root->appendChild($atributo);
                $atributoValor = $xml->createTextNode('urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:21');
                $atributo->appendChild($atributoValor);
                
                $atributo = $xml->createAttribute('xmlns:rsm');
                $root->appendChild($atributo);
                $atributoValor = $xml->createTextNode('urn:un:unece:uncefact:data:standard:SPSCertificate:17');
                $atributo->appendChild($atributoValor);
                
                $atributo = $xml->createAttribute('xmlns:ns1');
                $root->appendChild($atributo);
                $atributoValor = $xml->createTextNode('urn:un:unece:uncefact:data:standard:UnqualifiedDataType:21');
                $atributo->appendChild($atributoValor);
                
               //SECCION 1 rsm:SPSExchangedDocument
             
                $exchangeDocument = $xml->createElement('rsm:SPSExchangedDocument');
                $exchangeDocument =$root->appendChild($exchangeDocument);
                         
                    $eDName = $xml->createElement('ram:Name','CERTIFICADO FITOSANITARIO DE EXPORTACIÓN');
                    $eDName =$exchangeDocument->appendChild($eDName);
                    
                    $eDId = $xml->createElement('ram:ID',$idVue);
                    $eDId =$exchangeDocument->appendChild($eDId);
                    
                    $eDTypeCode = $xml->createElement('ram:TypeCode','851');
                    $eDTypeCode =$exchangeDocument->appendChild($eDTypeCode);    
                    
                    $eDStatusCode = $xml->createElement('ram:StatusCode','70');
                    $eDStatusCode =$exchangeDocument->appendChild($eDStatusCode);
                    
                   //Inicio - fecha inicio vigencia del certificado
                    $eDIssueDateTime = $xml->createElement('ram:IssueDateTime');
                    $eDIssueDateTime =$exchangeDocument->appendChild($eDIssueDateTime);
                    
                    	$fechaVigencia = date('c',strtotime($fitosanitario['fecha_inicio_vigencia_certificado']));
                    	
                    	$eDIDateTime = $xml->createElement('ns1:DateTimeString',$fechaVigencia);
                   		$eDIssueDateTime->appendChild($eDIDateTime);
                   	//Fin - fecha inicio vigencia del certificado
                    
                    $eDIssuerSPSParty = $xml->createElement('ram:IssuerSPSParty');
                    $eDIssuerSPSParty =$exchangeDocument->appendChild($eDIssuerSPSParty);
                        
                        $eDIspName = $xml->createElement('ram:Name', 'Organización de Protección Fitosanitaria de Ecuador');
                        $eDIspName =$eDIssuerSPSParty->appendChild($eDIspName);
                    
                   	$eDUnoIncludedSPSNoteOne = $xml->createElement('ram:IncludedSPSNote');
                    $exchangeDocument->appendChild($eDUnoIncludedSPSNoteOne);
                        
                        $eDISNSubjectOne = $xml->createElement('ram:Subject','SPSFL');
                        $eDUnoIncludedSPSNoteOne->appendChild($eDISNSubjectOne);
                        
                        $eDISNContentOne = $xml->createElement('ram:Content',5);
                        $eDUnoIncludedSPSNoteOne->appendChild($eDISNContentOne);
                        
              		//Inicio - información adicional
		           	$eDUnoIncludedSPSNote = $xml->createElement('ram:IncludedSPSNote');
		            $exchangeDocument->appendChild($eDUnoIncludedSPSNote);
		            	
		           		$eDISNSubject = $xml->createElement('ram:Subject','ADEDL');
		            	$eDUnoIncludedSPSNote->appendChild($eDISNSubject);
		            	
		            	$eDISNContent = $xml->createElement('ram:Content',$fitosanitario['informacion_adicional']);
		            	$eDUnoIncludedSPSNote->appendChild($eDISNContent);
		            	
			            	$eDUnoIncludedSPSNoteAtributo = $xml->createAttribute('languageID');
			            	$eDISNContent->appendChild($eDUnoIncludedSPSNoteAtributo);
			            	$eDUnoIncludedSPSNoteValorAtributo = $xml->createTextNode('es');
			            	$eDUnoIncludedSPSNoteAtributo->appendChild($eDUnoIncludedSPSNoteValorAtributo);
		            
			        //Inicio - fecha inspección
		            $eDDosIncludedSPSNote = $xml->createElement('ram:IncludedSPSNote');
		            $exchangeDocument->appendChild($eDDosIncludedSPSNote);
		            	
		            	$eDDosISNSubject = $xml->createElement('ram:Subject','ADDIEDL');
		            	$eDDosIncludedSPSNote->appendChild($eDDosISNSubject);
		            	
		            	$fechaInspeccion = date('c',strtotime($fitosanitario['fecha_inspeccion']));
		            	$eDISNDosContent = $xml->createElement('ram:Content',$fechaInspeccion);
		            	$eDDosIncludedSPSNote->appendChild($eDISNDosContent);
		            //Fin - fecha Inspección
	            
		            
		            //Inicio - fecha expedición (fecha fin de vigencia)
	            	$eDSignatorySPSAuthentication = $xml->createElement('ram:SignatorySPSAuthentication');
	            	$eDSignatorySPSAuthentication =$exchangeDocument->appendChild($eDSignatorySPSAuthentication);
		            	
		            	
		            	$eDSSAActualDateTime = $xml->createElement('ram:ActualDateTime');
		            	$eDSSAActualDateTime=$eDSignatorySPSAuthentication->appendChild($eDSSAActualDateTime);
			            	 
			            	$fechaFinVigencia = date('c',strtotime($fitosanitario['fecha_fin_vigencia_certificado']));
			            	$eDSSAADateTimeString = $xml->createElement('ns1:DateTimeString',$fechaFinVigencia);
			            	$eDSSAActualDateTime->appendChild($eDSSAADateTimeString);
			       	//Fin - fecha expedición (fecha fin de vigencia)
			        
			        //Inicio - nombre lugar de emisión del certificado 
		            $eDSSAIssueSPSLocation = $xml->createElement('ram:IssueSPSLocation');
		            $eDSSAIssueSPSLocation =$eDSignatorySPSAuthentication->appendChild($eDSSAIssueSPSLocation);
		            	
			        	$eDSSAPSPISLName = $xml->createElement('ram:Name', $fitosanitario['nombre_ciudad_solicitud']);
			            $eDSSAPSPISLName = $eDSSAIssueSPSLocation->appendChild($eDSSAPSPISLName);
			        //Fin - nombre lugar de emisión del certificado
			        
			        //Inicio - nombre del técnico que aprueba el certificado
		            $eDSSAProviderSPSParty = $xml->createElement('ram:ProviderSPSParty');
		            $eDSSAProviderSPSParty =$eDSignatorySPSAuthentication->appendChild($eDSSAProviderSPSParty);
		            	
			           	$eDSSAPSPName = $xml->createElement('ram:Name','Ninguno');
			           	$eDSSAPSPName = $eDSSAProviderSPSParty->appendChild($eDSSAPSPName);
			           	
			            $eDSSAPSPSpecifiedSPSPerson = $xml->createElement('ram:SpecifiedSPSPerson');
			            $eDSSAPSPSpecifiedSPSPerson =$eDSSAProviderSPSParty->appendChild($eDSSAPSPSpecifiedSPSPerson);
			            
				            $eDSSAPSPSPSName = $xml->createElement('ram:Name', $fitosanitario['nombre_aprobador']);
				            $eDSSAPSPSPSName = $eDSSAPSPSpecifiedSPSPerson->appendChild($eDSSAPSPSPSName);
			        //Fin - nombre del técnico que aprueba el certificado
			        
			        //Inicio de texto leyenda en casillero 7
		            $eDSSAIncludedSPSClause = $xml->createElement('ram:IncludedSPSClause');
		            $eDSignatorySPSAuthentication->appendChild($eDSSAIncludedSPSClause);
		            
		            	$eDSSAIPCID = $xml->createElement('ram:ID',1);
		            	$eDSSAIncludedSPSClause->appendChild($eDSSAIPCID);
		            	
		            	$eDSSAIPCContent = $xml->createElement('ram:Content', 'Por la presente se certifica que las plantas, productos vegetales u otros artículos reglamentados descritos aquí se han inspeccionado y/o sometido a ensayo de acuerdo con los procedimientos oficiales adecuados y se considera que están libres de las plagas cuarentenarias especificadas por la parte contrante importadora y que cumplan los requisitos fitosanitarios vigentes de la parte contratante importadora, incluidos los relativos a las plagas no cuarentenarias reglamentadas.');
		            	$eDSSAIncludedSPSClause->appendChild($eDSSAIPCContent);
		            //Fin de texto leyenda en casillero 7
		            
		     //*****SECCION 2 SPSConsignment
		            	
		       	$consignment = $xml->createElement('rsm:SPSConsignment');
		       	$root->appendChild($consignment);
		        
		       		//Inicio - Datos del exportador
			       	$cConsignorSPSParty = $xml->createElement('ram:ConsignorSPSParty');
			       	$consignment->appendChild($cConsignorSPSParty);
			       	
			       		//varios exportadores
			       		$qExportador = $this->obtenerFitosanitarioExportadorWebServicesXId($conexion,$fitosanitario['id_fitosanitario_exportacion']);
			       		
			       		while($fila = pg_fetch_assoc($qExportador)){
			       			$listaExportadores.= $fila['nombre_exportador'].', ';
			       			$direccionExportadores.=$fila['direccion_exportador'].', ';
			       		}
			       		
					       	$cCSPName = $xml->createElement('ram:Name',rtrim ($listaExportadores, ' ,' ));
					       	$cConsignorSPSParty->appendChild($cCSPName);
					 	  
					       	$cCSPSpecifiedSPSAddress = $xml->createElement('ram:SpecifiedSPSAddress');
					       	$cConsignorSPSParty->appendChild($cCSPSpecifiedSPSAddress);
					       	
					       	$cCSPSSSALineOne = $xml->createElement('ram:LineOne',rtrim ($direccionExportadores, ' ,' ));
					       	$cCSPSpecifiedSPSAddress->appendChild($cCSPSSSALineOne);
				      	
				   	//Fin - datos del exportador
				    	
				   	//Inicio - datos del importador
			       	$cConsigneeSPSParty = $xml->createElement('ram:ConsigneeSPSParty');
			       	$consignment->appendChild($cConsigneeSPSParty);
				       	
					       	$cCSPName = $xml->createElement('ram:Name',$fitosanitario['nombre_importador']);
					       	$cConsigneeSPSParty->appendChild($cCSPName);
				
					       	$cCSPOSpecifiedSPSAddress = $xml->createElement('ram:SpecifiedSPSAddress');
					       	$cConsigneeSPSParty->appendChild($cCSPOSpecifiedSPSAddress);
					       	
					       	$cCSPOSSSALineOne = $xml->createElement('ram:LineOne',$fitosanitario['direccion_importador']);
					       	$cCSPOSpecifiedSPSAddress->appendChild($cCSPOSSSALineOne);
				 	//Fin - datos del importador
				    
					//Inicio - país exportador
					$cExportSPSCountry = $xml->createElement('ram:ExportSPSCountry');
		        	$consignment->appendChild($cExportSPSCountry);
		        	
		        	$paisOrigen = pg_fetch_assoc($cc->obtenerNombreLocalizacion($conexion, $fitosanitario['id_pais_origen']));
		        		
		        	    $cESCLId = $xml->createElement('ram:ID',$paisOrigen['codigo']);
		        		$cExportSPSCountry->appendChild($cESCLId);
		        		
		        		//se manda null el nombre del pais porque recomienda argentina que no leido y no es parte del standar
		        		$cESCLName = $xml->createElement('ram:Name',$paisOrigen['nombre']);
		        		$cExportSPSCountry->appendChild($cESCLName);
		        	//Fin - país exportador
		        	
		        	//Inicio - país importador
		        	$cImportSPSCountry = $xml->createElement('ram:ImportSPSCountry');
		        	$consignment->appendChild($cImportSPSCountry);
		        	
		        	$paisDestino = pg_fetch_assoc($cc->obtenerNombreLocalizacion($conexion, $fitosanitario['id_pais_destino']));
		        			
		        	    $cISCId = $xml->createElement('ram:ID',$paisDestino['codigo']);
		        		$cImportSPSCountry->appendChild($cISCId);
		        		
		        		//se manda null el nombre del pais porque recomienda argentina que no leido y no es parte del standar
		        		$cISCName = $xml->createElement('ram:Name',$paisDestino['nombre']);
		        		$cImportSPSCountry->appendChild($cISCName);
		        	//Fin - país importador
		        	
		        //Inicio - puerto de destino (punto entrada declarado)
		        $cUnloadingBaseportSPSLocation = $xml->createElement('ram:UnloadingBaseportSPSLocation');
		        $consignment->appendChild($cUnloadingBaseportSPSLocation);
		        
			        $cUBSLName = $xml->createElement('ram:Name',$fitosanitario['nombre_puerto_destino']);
			        $cUnloadingBaseportSPSLocation->appendChild($cUBSLName);
		        //Fin - puerto de destino (punto entrada declarado)
		        
		        //TODO: Inicio - campo no necesario en certificado pero el validador pide ram:ExaminationSPSEvent
		        $cExaminationSPSEvent = $xml->createElement('ram:ExaminationSPSEvent');
		        $consignment->appendChild($cExaminationSPSEvent);
		        
			        $cESEOccurrenceSPSLocation = $xml->createElement('ram:OccurrenceSPSLocation');
			        $cExaminationSPSEvent->appendChild($cESEOccurrenceSPSLocation);
			         
				        $cESEOSLName = $xml->createElement('ram:Name','Ninguno');
				        $cESEOccurrenceSPSLocation->appendChild($cESEOSLName);
		      	//Inicio - campo no necesario en certificado pero el validador pide ram:ExaminationSPSEvent
		       
			  	//Inicio - medio de transporte declarado
	       	  	$cMainCarriageSPSTransportMovement = $xml->createElement('ram:MainCarriageSPSTransportMovement');
	       	  	$consignment->appendChild($cMainCarriageSPSTransportMovement);
	       	  	$codigoMedioTransporte =$this->obtenerCodigoMedioTrasnporte($fitosanitario['codigo_medio_transporte']);
	       	  		
	       	  		$cMCSTMModeCode = $xml->createElement('ram:ModeCode',$codigoMedioTransporte);
	       	  		$cMainCarriageSPSTransportMovement->appendChild($cMCSTMModeCode);
	       	  			
       	  			$cMCSTMUsedSPSTransportMeans = $xml->createElement('ram:UsedSPSTransportMeans');
       	  			$cMainCarriageSPSTransportMovement->appendChild($cMCSTMUsedSPSTransportMeans);
	       	  		
	       	  		$cMCSTMUSTMName = $xml->createElement('ram:Name',$fitosanitario['nombre_medio_transporte']);
	       	  		$cMCSTMUsedSPSTransportMeans->appendChild($cMCSTMUSTMName);
	       	  			
	       	  			$cMCSTMUSTMNameAtributo = $xml->createAttribute('languageID');
	       	  			$cMCSTMUSTMName->appendChild($cMCSTMUSTMNameAtributo);
	       	  			$cMCSTMUSTMNameValorAtributo = $xml->createTextNode('es');
	       	  			$cMCSTMUSTMNameAtributo->appendChild($cMCSTMUSTMNameValorAtributo);
	       		//Fin - medio de transporte declarado
	       		
	       	  	//Inicio - detalle del producto  		
	       	  	$qProductos = $this->obtenerFitosanitarioProductosWebServicesXId($conexion,$fitosanitario['id_fitosanitario_exportacion']);
	       	  	$contador=1;
	       	  	while ($productos= pg_fetch_assoc($qProductos)){
		       	 
		       	$cIncludedSPSConsignmentItemt = $xml->createElement('ram:IncludedSPSConsignmentItem');
		       	$consignment->appendChild($cIncludedSPSConsignmentItemt);
		       	  	
		       	  		$cISCIIncludedSPSTradeLineItem = $xml->createElement('ram:IncludedSPSTradeLineItem');
		       	  		$cIncludedSPSConsignmentItemt->appendChild($cISCIIncludedSPSTradeLineItem);
		       	  			
		       	  			//Inicio - contador producto
		       	  			$cISCIISTLISequenceNumeric = $xml->createElement('ram:SequenceNumeric',$contador);
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLISequenceNumeric);
		       	  			//Fin contador producto
		       	  			
		       	  			//TODO: Inicio - Descripción producto el validador pide ram:Descripción
		       	  			$cISCIISTLOSCDescription = $xml->createElement('ram:Description','Ninguno');
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLOSCDescription);
		       	  			//Fin - Descripción producto el validador pide ram:Descripción
		       	  			
		       	  			//Inicio - nombre producto
		       	  			$cISCIISTLOSCCommonName = $xml->createElement('ram:CommonName',$productos['nombre_producto']);
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLOSCCommonName);
		       	  				
			       	  			$cISCIISTLOSCCommonNameAtributo = $xml->createAttribute('languageID');
			       	  			$cISCIISTLOSCCommonName->appendChild($cISCIISTLOSCCommonNameAtributo);
			       	  			$cISCIISTLOSCCommonNameValorAtributo = $xml->createTextNode('es');
			       	  			$cISCIISTLOSCCommonNameAtributo->appendChild($cISCIISTLOSCCommonNameValorAtributo);
			       	  			
			       	  		//Fin - nombre producto
			       	  		
		       	  			//TODO: Inicio - Nombre cientifico
		       	  			//$nombreCientifico=$cc->obtenerNombreProducto($conexion, $productos['id_producto']);
		       	  		
		       	  			//$cISCIISTLOSCIntendedUse = $xml->createElement('ram:ScientificName',pg_fetch_result($nombreCientifico, '0', 'nombre_cientifico'));
		       	  			$cISCIISTLOSCIntendedUse = $xml->createElement('ram:ScientificName',$productos['nombre_botanico']);
		       	  			 
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLOSCIntendedUse);
		       	  			
		       	  			//TODO: Inicio - Intensión de Uso
		       	  			$cISCIISTLOSCIntendedUse = $xml->createElement('ram:IntendedUse','Ninguno');
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLOSCIntendedUse);
			       	  			
			       	  			$cISCIISTLOSCIntendedUseAtributo = $xml->createAttribute('languageID');
			       	  			$cISCIISTLOSCIntendedUse->appendChild($cISCIISTLOSCIntendedUseAtributo);
			       	  			$cISCIISTLOSCIntendedUseValorAtributo = $xml->createTextNode('es');
			       	  			$cISCIISTLOSCIntendedUseAtributo->appendChild($cISCIISTLOSCIntendedUseValorAtributo);
		       	  			
			       	  			
			       	  		//Inicio - unidad peso neto
		       	  			$unidadPesoNeto = $this->obtenerCodigoUnidadMedida($productos['unidad_peso_neto']);
		       	  			$cISCIISTLOSCNetWeightMeasure = $xml->createElement('ram:NetWeightMeasure',$productos['cantidad_peso_neto']);
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLOSCNetWeightMeasure);
		       	  			 
			       	  			$cISCIISTLOSCNWMAtributo = $xml->createAttribute('unitCode');
			       	  			$cISCIISTLOSCNetWeightMeasure->appendChild($cISCIISTLOSCNWMAtributo);
			       	  			$cISCIISTLOSCNWMValorAtributo = $xml->createTextNode($unidadPesoNeto);
			       	  			$cISCIISTLOSCNWMAtributo->appendChild($cISCIISTLOSCNWMValorAtributo);
			       	  		//Fin - unidad peso neto
			       	  		
			       	  		//Inicio - unidad peso bruto
		       	  			$unidadPesoBruto = $this->obtenerCodigoUnidadMedida($productos['unidad_peso_bruto']);
		       	  			$cISCIISTLOSCGrossWeightMeasure = $xml->createElement('ram:GrossWeightMeasure',$productos['cantidad_peso_bruto']);
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLOSCGrossWeightMeasure);
		       	  			 
			       	  			$cISCIISTLOSCGWMAtributo = $xml->createAttribute('unitCode');
			       	  			$cISCIISTLOSCGrossWeightMeasure->appendChild($cISCIISTLOSCGWMAtributo);
			       	  			$cISCIISTLOSCGWMValorAtributo = $xml->createTextNode($unidadPesoBruto);
			       	  			$cISCIISTLOSCGWMAtributo->appendChild($cISCIISTLOSCGWMValorAtributo);
			       	  		//Fin - unidad peso bruto
			       	  		
			       	  		//Inicio - cantidad comercial
		       	  			$cISCIISTLOSCNetVolumeMeasure = $xml->createElement('ram:NetVolumeMeasure',$productos['cantidad_comercial']);
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLOSCNetVolumeMeasure);
		       	  			 
		       	  			$unidadCantidadComercial = $this->obtenerCodigoUnidadMedida($productos['unidad_cantidad_comercial']);
		       	  			 
			       	  			$cISCIISTLOSCNVMAtributo = $xml->createAttribute('unitCode');
			       	  			$cISCIISTLOSCNetVolumeMeasure->appendChild($cISCIISTLOSCNVMAtributo);
			       	  			$cISCIISTLOSCNVMValorAtributo = $xml->createTextNode($unidadCantidadComercial);
			       	  			$cISCIISTLOSCNVMAtributo->appendChild($cISCIISTLOSCNVMValorAtributo);
		       	  			//Fin - cantidad comercial
		       	  			
			       	  		//Inicio - información adicional
			       	  		
			       	  			$cISCIISTLIDosAdditionalInformationSPSNote = $xml->createElement('ram:AdditionalInformationSPSNote');
			       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLIDosAdditionalInformationSPSNote);
			       	  			 
			       	  			$cISCIISTLIASCDosSubject = $xml->createElement('ram:Subject','ADTLIL');
			       	  			$cISCIISTLIDosAdditionalInformationSPSNote->appendChild($cISCIISTLIASCDosSubject);
			       	  			 
			       	  			$cISCIISTLIASCDosContent = $xml->createElement('ram:Content',$productos['informacion_adicional']);
			       	  			$cISCIISTLIDosAdditionalInformationSPSNote->appendChild($cISCIISTLIASCDosContent);
			       	  			 
				       	  			$cISCIISTLIASCDosContentAtributo = $xml->createAttribute('languageID');
				       	  			$cISCIISTLIASCDosContent->appendChild($cISCIISTLIASCDosContentAtributo);
				       	  			$cISCIISTLIASCCDosValorAtributo = $xml->createTextNode('es');
				       	  			$cISCIISTLIASCDosContentAtributo->appendChild($cISCIISTLIASCCDosValorAtributo);
				       	  			
			       	  			$cISCIISTLICincoAdditionalInformationSPSNote = $xml->createElement('ram:AdditionalInformationSPSNote');
			       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLICincoAdditionalInformationSPSNote);
			       	  			 
			       	  			$cISCIISTLIASCCincoSubject = $xml->createElement('ram:Subject','ADDITLIL');
			       	  			$cISCIISTLICincoAdditionalInformationSPSNote->appendChild($cISCIISTLIASCCincoSubject);
			       	  			 
			       	  			$fechaInicioVigenciaCert = date('c',strtotime($fitosanitario['fecha_inspeccion']));
			       	  			 
			       	  			$cISCIISTLIASCCincoContent = $xml->createElement('ram:Content',$fechaInicioVigenciaCert);
			       	  			$cISCIISTLICincoAdditionalInformationSPSNote->appendChild($cISCIISTLIASCCincoContent);
			       	  		//Fin - información adicional
			       	  		
			       	  		//Inicio tipo producto
		       	  			$cISCIISTLIDosApplicableSPSClassification = $xml->createElement('ram:ApplicableSPSClassification');
		       	  			$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLIDosApplicableSPSClassification);
		       	  			
			       	  			$cISCIISTLIASCDosSystemName = $xml->createElement('ram:SystemName','IPPCPCVP');
			       	  			$cISCIISTLIDosApplicableSPSClassification->appendChild($cISCIISTLIASCDosSystemName);
			       	  			 
			       	  			$tipoProducto = pg_fetch_assoc($cc->obtenerTipoSubtipoXProductos($conexion, $productos['id_producto']));
			       	  			
			       	  			$cISCIISTLIASCDosClassName = $xml->createElement('ram:ClassName',$tipoProducto['nombre_tipo']);
			       	  			$cISCIISTLIDosApplicableSPSClassification->appendChild($cISCIISTLIASCDosClassName);
			       	  			
				       	  			$cISCIISTLIASCDosClassNameAtributo = $xml->createAttribute('languageID');
				       	  			$cISCIISTLIASCDosClassName->appendChild($cISCIISTLIASCDosClassNameAtributo);
				       	  			$cISCIISTLIASCDosClassNameValorAtributo = $xml->createTextNode('es');
				       	  			$cISCIISTLIASCDosClassNameAtributo->appendChild($cISCIISTLIASCDosClassNameValorAtributo);
			       	  		//Fin - tipo producto
			       	  		
    		       	  		//Inicio - Información física del paquete <ram:TypeCode>CN</ram:TypeCode>
    		       	  		$cISCIISTLIPhysicalSPSPackage = $xml->createElement('ram:PhysicalSPSPackage');
    		       	  		$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLIPhysicalSPSPackage);
    		       	  			 
    		       	  			$cISCIISTLIPSPLevelCode = $xml->createElement('ram:LevelCode','1');
    		       	  			$cISCIISTLIPhysicalSPSPackage->appendChild($cISCIISTLIPSPLevelCode);
    		       	  			
    		       	  			//TODO: PREGUNTAR HA QUE CODIGO CORRESPONDE EL KG EN TIPO DE PAQUETE
    		       	  			$cISCIISTLIPSPTypeCode = $xml->createElement('ram:TypeCode','NA');
    		       	  			$cISCIISTLIPhysicalSPSPackage->appendChild($cISCIISTLIPSPTypeCode);
    		       	  			
    		       	  			$cISCIISTLIPSPItemQuantity = $xml->createElement('ram:ItemQuantity',$productos['cantidad_cobro']);
    		       	  			$cISCIISTLIPhysicalSPSPackage->appendChild($cISCIISTLIPSPItemQuantity);
    		       	  		//Fin - Información física del paquete
    		       	  		
    		       	  			
    		       	  		//Inicio - país origen	
    		       	  		$cISCIISTLIOriginSPSCountry = $xml->createElement('ram:OriginSPSCountry');
    		       	  		$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLIOriginSPSCountry);
    		       	  			
    		       	  			$cISCIISTLOSCId = $xml->createElement('ram:ID',$paisOrigen['codigo']);
    		       	  			$cISCIISTLIOriginSPSCountry->appendChild($cISCIISTLOSCId);
    		       	  			
    		       	  			$cISCIISTLOSCName = $xml->createElement('ram:Name',$paisOrigen['nombre']);
    		       	  			$cISCIISTLIOriginSPSCountry->appendChild($cISCIISTLOSCName);
    		       	  		
    		       	  			
    		       	  			$cISCIISTLOSCSubordinateSPSCountrySubDivision = $xml->createElement('ram:SubordinateSPSCountrySubDivision');
    		       	  			$cISCIISTLIOriginSPSCountry->appendChild($cISCIISTLOSCSubordinateSPSCountrySubDivision);
    		       	  			 
    		       	  				$cISCIISTLOSCSSCSDName = $xml->createElement('ram:Name',$fitosanitario['nombre_provincia_origen_producto']);
    		       	  				$cISCIISTLOSCSubordinateSPSCountrySubDivision->appendChild($cISCIISTLOSCSSCSDName);
    		       	  			 
    		       	  				$cISCIISTLOSCSSCSDHierarchicalLevelCode = $xml->createElement('ram:HierarchicalLevelCode','0');
    		       	  				$cISCIISTLOSCSubordinateSPSCountrySubDivision->appendChild($cISCIISTLOSCSSCSDHierarchicalLevelCode);
    		       	  		//Fin - país origen
    		       	  		 
    		       	  		
				       	  	$cISCIISTLIAppliedSPSProcess = $xml->createElement('ram:AppliedSPSProcess');
				       	  	$cISCIIncludedSPSTradeLineItem->appendChild($cISCIISTLIAppliedSPSProcess);
				       	  	
					       	  	//TODO: Inicio - ram:TypeCode el validador pide este campo - en  mapping no hay codigo
					       	  	$cISCIISTLIASPUnoTypeCode = $xml->createElement('ram:TypeCode','ZZZ');
					       	  	$cISCIISTLIAppliedSPSProcess->appendChild($cISCIISTLIASPUnoTypeCode);
					       	  	//Fin - ram:TypeCode el validador pide este campo 
					       	  	
				       	  		//Inicio - fecha de tratamineto
					       	  	$cISCIISTLIASPUnoCompletionSPSPeriod = $xml->createElement('ram:CompletionSPSPeriod');
					       	  	$cISCIISTLIAppliedSPSProcess->appendChild($cISCIISTLIASPUnoCompletionSPSPeriod);
					       	  	 
						       	  	$cISCIISTLIASPCSPStartDateTime = $xml->createElement('ram:StartDateTime');
						       	  	$cISCIISTLIASPUnoCompletionSPSPeriod->appendChild($cISCIISTLIASPCSPStartDateTime);
						       	  	
							       	  	$fechaTratamiento = date('Y-m-d',strtotime($productos['fecha_tratamiento']));
							       	  	$cISCIISTLIASPUCSPSDateTimeString = $xml->createElement('ns1:DateTimeString',$fechaTratamiento);
							       	  	$cISCIISTLIASPCSPStartDateTime->appendChild($cISCIISTLIASPUCSPSDateTimeString);
						       	  	
						       	  	
						       	  	$cISCIISTLIASPCSPEndDateTime = $xml->createElement('ram:EndDateTime');
						       	  	$cISCIISTLIASPUnoCompletionSPSPeriod->appendChild($cISCIISTLIASPCSPEndDateTime);
						       	  	
							       	  	$cISCIISTLIASPUCSPEDateTimeString = $xml->createElement('ns1:DateTimeString',$fechaTratamiento);
							       	  	$cISCIISTLIASPCSPEndDateTime->appendChild($cISCIISTLIASPUCSPEDateTimeString);
			       	  			//Fin - fecha tratamiento

									//Inicio - duración del tratamiento
						       	  
							       	  	$cISCIISTLIASPCSPDosDurationMeasure  = $xml->createElement('ram:DurationMeasure',$productos['duracion_tratamiento']);
							       	  	$cISCIISTLIASPUnoCompletionSPSPeriod->appendChild($cISCIISTLIASPCSPDosDurationMeasure);
							       	  	
								       	  	$cISCIISTLIASPDosContentAtributo = $xml->createAttribute('unitCode');
								       	  	$cISCIISTLIASPCSPDosDurationMeasure->appendChild($cISCIISTLIASPDosContentAtributo);
								       	  	$codigoUnidadTratamiento =$this->obtenerCodigoTratamiento($productos['unidad_tratamiento']);
								       	  	$cISCIISTLIASPDosValorAtributo = $xml->createTextNode($codigoUnidadTratamiento);
								       	  	$cISCIISTLIASPDosContentAtributo->appendChild($cISCIISTLIASPDosValorAtributo);
							       	//Fin - duración del tratamiento
	
							    //Inicio - temperatura del tratamiento   
					       	  	$cISCIISTLIASPUnoApplicableSPSProcessCharacteristic = $xml->createElement('ram:ApplicableSPSProcessCharacteristic');
					       	  	$cISCIISTLIAppliedSPSProcess->appendChild($cISCIISTLIASPUnoApplicableSPSProcessCharacteristic);
						       	  //TODO: no existe en el mapping la descripcion se puso porque el validador lo pide
						       	  	$cISCIISTLIASPDosTresASPCCDescripcion = $xml->createElement('ram:Description','TTTM');
						       	  	$cISCIISTLIASPUnoApplicableSPSProcessCharacteristic->appendChild($cISCIISTLIASPDosTresASPCCDescripcion);
						       	  	
						       	  	$cISCIISTLIASPDosTresASPCValueMeasure = $xml->createElement('ram:ValueMeasure',$productos['temperatura_tratamiento']);
						       	  	$cISCIISTLIASPUnoApplicableSPSProcessCharacteristic->appendChild($cISCIISTLIASPDosTresASPCValueMeasure);
						       	  	$unidadTemperatura = $this->obtenerCodigoUnidadMedida($productos['unidad_temperatura_tratamiento']);
						       	  	$cISCIISTLIASPVMDosTresAtributo = $xml->createAttribute('unitCode');
						       	  	$cISCIISTLIASPDosTresASPCValueMeasure->appendChild($cISCIISTLIASPVMDosTresAtributo);
						       	  	$cISCIISTLIASPVMDosTresValorAtributo = $xml->createTextNode($unidadTemperatura);
						       	  	$cISCIISTLIASPVMDosTresAtributo->appendChild($cISCIISTLIASPVMDosTresValorAtributo);
					       	  	//Fin - temperatura del tratamiento
					       	  	
						       	//Inicio - concentración química del producto
					       	  	$cISCIISTLIASPTresTresApplicableSPSProcessCharacteristic = $xml->createElement('ram:ApplicableSPSProcessCharacteristic');
					       	  	$cISCIISTLIAppliedSPSProcess->appendChild($cISCIISTLIASPTresTresApplicableSPSProcessCharacteristic);
					       	  	 	
					       	  	  	$cISCIISTLIASPTresTresASPCCDescripcion = $xml->createElement('ram:Description','TTCO');
						       	  	$cISCIISTLIASPTresTresApplicableSPSProcessCharacteristic->appendChild($cISCIISTLIASPTresTresASPCCDescripcion);
						       	  	//TODO: se puso comentario
						       	  	$cISCIISTLIASPTresTresASPCValueMeasure = $xml->createElement('ram:ValueMeasure',$productos['concentracion_producto_quimico']);
						       	  	$cISCIISTLIASPTresTresApplicableSPSProcessCharacteristic->appendChild($cISCIISTLIASPTresTresASPCValueMeasure);
						       	  	
							       	 
					       	  	//Fin - concentración química del producto
						       	  	$cISCIISTLIASPTresTressApplicableSPSProcessCharacteristic = $xml->createElement('ram:ApplicableSPSProcessCharacteristic');
						       	  	$cISCIISTLIAppliedSPSProcess->appendChild($cISCIISTLIASPTresTressApplicableSPSProcessCharacteristic);
						       	  		
						       	  	$cISCIISTLIASPTresTressASPCCDescripcion = $xml->createElement('ram:Description','TTFT');
						       	  	$cISCIISTLIASPTresTressApplicableSPSProcessCharacteristic->appendChild($cISCIISTLIASPTresTressASPCCDescripcion);
						       	  	
						       	  	$cISCIISTLIASPTresTressASPCValueMeasure = $xml->createElement('ram:Description','Descripción tipo: '.$productos['descripcion_tipo_tratamiento'].'; Descripción nombre: '.$productos['descripcion_nombre_tratamiento'].'; Fecha tratamiento: '.$productos['fecha_tratamiento'].'; Duración: '.$productos['duracion_tratamiento'].' '.$productos['unidad_tratamiento'].'; Temperatura: '.$productos['temperatura_tratamiento'].' '.$productos['unidad_temperatura_tratamiento'].'; Concentración: '.$productos['concentracion_producto_quimico'].'; Información Adicional: No hay información adicional disponible');
						       	  	$cISCIISTLIASPTresTressApplicableSPSProcessCharacteristic->appendChild($cISCIISTLIASPTresTressASPCValueMeasure);
						      			
							       	  	$cISCIISTLIASPTresTressASPCValueMeasureAtributo = $xml->createAttribute('languageID');
							       	  	$cISCIISTLIASPTresTressASPCValueMeasure->appendChild($cISCIISTLIASPTresTressASPCValueMeasureAtributo);
							       	  	$cISCIISTLIASPTresTressASPCValueMeasureValorAtributo = $xml->createTextNode('es');
							       	  	$cISCIISTLIASPTresTressASPCValueMeasureAtributo->appendChild($cISCIISTLIASPTresTressASPCValueMeasureValorAtributo);
							    	
						  
					  			
		       	  			$contador++;
		       	  		
                        }
            
            $xml->formatOutput = true;  //poner los string en la variable $strings_xml:
    		$xml->saveXML();
    
    		$nombreArchivoXML = 'generado/'.$idVue.'.xml';
    
    		$xml->save($nombreArchivoXML);
    
    		return $nombreArchivoXML;
    
    	}	    
		
		public function actualizarEstadoRecepcionCertificadoFitosanitarioExportacion($conexion, $numeroCertificado){
		    
		    $idVue = $numeroCertificado['numero_certificado'];
		    
		   $consulta = "UPDATE 
							g_fitosanitario_exportacion.fitosanitario_exportaciones
                        SET
                            estado_web_services = 'RECIBIDO',
                            fecha_web_services_recepcion = 'now()'
						WHERE
							id_vue = '$idVue'
                            and estado_web_services = 'POR ATENDER'
                        RETURNING id_fitosanitario_exportacion;";
		    
		    $res = $conexion->ejecutarConsulta($consulta);
		    
		    if(pg_num_rows($res) == 0){
		        $mensaje = 'Documento no encontrado.';
		    }else{
		        $mensaje = 'Documento fitosanitario de exportación consumido.';
		    }
		    
		    return $mensaje;
		    
		}
		
		function xml2array($xml){
		    $opened = array();
		    $opened[1] = 0;
		    $xml_parser = xml_parser_create();
		    xml_parse_into_struct($xml_parser, $xml, $xmlarray);
		    $array = array_shift($xmlarray);
		    unset($array["level"]);
		    unset($array["type"]);
		    $arrsize = sizeof($xmlarray);
		    for($j=0;$j<$arrsize;$j++){
		        $val = $xmlarray[$j];
		        switch($val["type"]){
		            case "open":
		                $opened[$val["level"]]=0;
		            case "complete":
		                $index = "";
		                for($i = 1; $i < ($val["level"]); $i++)
		                    $index .= "[" . $opened[$i] . "]";
		                    $path = explode('][', substr($index, 1, -1));
		                    $value = &$array;
		                    foreach($path as $segment)
		                        $value = &$value[$segment];
		                        $value = $val;
		                        unset($value["level"]);
		                        unset($value["type"]);
		                        if($val["type"] == "complete")
		                            $opened[$val["level"]-1]++;
		                            break;
		            case "close":
		                $opened[$val["level"]-1]++;
		                unset($opened[$val["level"]]);
		                break;
		        }
		    }
		    return $array;
		}
		

            public function listarFitosanitarioExportacionPorEstado($conexion, $estado, $paisDestino=null){

            	if($paisDestino=='Holanda')
            		$busqueda=" and nombre_pais_destino='$paisDestino' ";
            	
                $consulta = "SELECT
                			*
                		FROM
                			g_fitosanitario_exportacion.fitosanitario_exportaciones
                		WHERE
                            estado = '$estado'
                            ".$busqueda."
                            and estado_web_services = 'POR ATENDER';";
                
                $res = $conexion->ejecutarConsulta($consulta);
                
                return $res;
                
            }
            
            public function listarFitosanitarioExportacionRecibidos($conexion, $estado, $tipo = 'Holanda'){
                
                $consulta = "SELECT
                			*
                		FROM
                			g_fitosanitario_exportacion.recepcion_fitosanitario_exportaciones
                		WHERE
                            estado = '$estado'
                            and tipo = '$tipo';";
                
                $res = $conexion->ejecutarConsulta($consulta);
                
				return $res;
			}
			
			public function guardarFitosanitarioExportacionRecibidos($conexion, $numeroSolicitud, $rutaXml, $rutaPdf, $estado = 'RECIBIDO', $tipo = 'Holanda', $numeroCertificado = ''){
			    
				$consulta = "INSERT INTO 
                                g_fitosanitario_exportacion.recepcion_fitosanitario_exportaciones(codigo, ruta_xml, estado, tipo, ruta_pdf, numero_certificado)
                            VALUES
                                ('$numeroSolicitud', '$rutaXml', '$estado', '$tipo', '$rutaPdf', '$numeroCertificado');";
			    
			   $res = $conexion->ejecutarConsulta($consulta);
			    
			    return $res;
			}
			
			public function confirmacionRecepcionFitosanitarioExportacion($conexion, $numeroSolicitud, $estado = 'CONFIRMADO', $tipo = 'Holanda'){
			    
			    $consulta = "UPDATE
                                g_fitosanitario_exportacion.recepcion_fitosanitario_exportaciones
                            SET 
                                estado = '$estado'
                            WHERE
                                codigo = '$numeroSolicitud'
                                and tipo = '$tipo';";
			    
			    $res = $conexion->ejecutarConsulta($consulta);
			    
			    return $res;
			}
			
			public function obtenerFitosanitarioExportacionRecibidosPorCodigo($conexion, $codigoSolicitud){
			    
			    $consulta = "SELECT
                			*
                		FROM
                			g_fitosanitario_exportacion.recepcion_fitosanitario_exportaciones
                		WHERE
                            id_recepcion = '$codigoSolicitud';";
			    
			    $res = $conexion->ejecutarConsulta($consulta);
			    
			    return $res;
			}
			
			public function obj2array($obj) {
			    $out = array();
			    foreach ($obj as $key => $val) {
			        switch (true) {
			            case is_object($val):
			                $out[$key] = $this->obj2array($val);
			                break;
			            case is_array($val):
			                $out[$key] = $this->obj2array($val);
			                break;
			            default:
			                $out[$key] = $val;
			        }
			    }
			    return $out;
			}
			
			function validateDateEs($date){
			   if($date != ''){
    			    $pattern="/^(0?[1-9]|[12][0-9]|3[01])[\/|-](0?[1-9]|[1][012])[\/|-]((19|20)?[0-9]{2})$/";
    			    if(preg_match($pattern,$date)){
    			        $values=preg_split("[\/|-]",$date);
    			        if(checkdate($values[1],$values[0],$values[2]))
    			            return true;
    			    }
    			    return false;
			   }else{
			       return true;
			   }
			}
			
			function xml2array2($contents, $get_attributes=1) {
			    if(!$contents) return array();
			    
			    if(!function_exists('xml_parser_create')) {
			        //print "'xml_parser_create()' function not found!";
			        return array();
			    }
			    //Get the XML parser of PHP - PHP must have this module for the parser to work
			    $parser = xml_parser_create();
			    xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
			    xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
			    xml_parse_into_struct( $parser, $contents, $xml_values );
			    xml_parser_free( $parser );
			    
			    if(!$xml_values) return;//Hmm...
			    
			    //Initializations
			    $xml_array = array();
			    $parents = array();
			    $opened_tags = array();
			    $arr = array();
			    
			    $current = &$xml_array;
			    
			    //Go through the tags.
			    foreach($xml_values as $data) {
			        unset($attributes,$value);//Remove existing values, or there will be trouble
			        
			        //This command will extract these variables into the foreach scope
			        // tag(string), type(string), level(int), attributes(array).
			        extract($data);//We could use the array by itself, but this cooler.
			        
			        $result = '';
			        if($get_attributes) {//The second argument of the function decides this.
			            $result = array();
			            if(isset($value)) $result['value'] = $value;
			            
			            //Set the attributes too.
			            if(isset($attributes)) {
			                foreach($attributes as $attr => $val) {
			                    if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
			                    /**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
			                }
			            }
			        } elseif(isset($value)) {
			            $result = $value;
			        }
			        
			        //See tag status and do the needed.
			        if($type == "open") {//The starting of the tag '<tag>'
			            $parent[$level-1] = &$current;
			            
			            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
			                $current[$tag] = $result;
			                $current = &$current[$tag];
			                
			            } else { //There was another element with the same tag name
			                if(isset($current[$tag][0])) {
			                    array_push($current[$tag], $result);
			                } else {
			                    $current[$tag] = array($current[$tag],$result);
			                }
			                $last = count($current[$tag]) - 1;
			                $current = &$current[$tag][$last];
			            }
			            
			        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
			            //See if the key is already taken.
			            if(!isset($current[$tag])) { //New Key
			                $current[$tag] = $result;
			                
			            } else { //If taken, put all things inside a list(array)
			                if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
			                    or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) {
			                        array_push($current[$tag],$result); // ...push the new element into that array.
			                    } else { //If it is not an array...
			                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
			                    }
			            }
			            
			        } elseif($type == 'close') { //End of tag '</tag>'
			            $current = &$parent[$level-1];
			        }
			    }
			    
			    return($xml_array);
			}
			
			function buscarArrayPorKey($tab, $key){
			    foreach($tab as $k => $value){
			        if($k==$key) return $value;
			        if(is_array($value)){
			            $find = $this->buscarArrayPorKey($value, $key);
			            if($find) return $find;
			        }
			    }
			    return null;
			}
			
			public function obtenerFitosanitarioExportacionRecibidosPorNumeroCertificado($conexion, $numeroCertificado){
			    
			    $consulta = "SELECT
                			*
                		FROM
                			g_fitosanitario_exportacion.recepcion_fitosanitario_exportaciones
                		WHERE
                            numero_certificado = '$numeroCertificado';";
			    
			    $res = $conexion->ejecutarConsulta($consulta);
			    
			    return $res;
			}
			
}