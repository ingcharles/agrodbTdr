<?php

//require_once 'ControladorCatalogos.php';
//require_once 'ControladorRegistroOperador.php';
//require_once 'ControladorRequisitos.php';
//require_once 'RecepcionComprobantesService.php';
//require_once 'AutorizacionComprobantesService.php';
require_once 'RecepcionComprobantesOfflineService.php';
require_once 'AutorizacionComprobantesOfflineService.php';

class ControladorCertificados{
	
	
	public function abrirDeposito ($conexion,$ruc){
	
		$res = $conexion->ejecutarConsulta("SELECT o.identificador,
	  							        			o.razon_social,
													d.fecha_deposito, 
													d.valor_deposito,
													d.numero_papeleta,
													d.numero_factura,
													d.activo,
													d.conciliacion,
													d.valor_consumo
													FROM g_operadores.operadores o, g_operadores.depositos d
													WHERE o.identificador = d.identificador
													AND d.identificador= '$ruc' ");
		return $res;
	}
	
	
	public function guardarNuevoDeposito($conexion,$identificador,$fecha,$valor,$numeroPapeleta,$numeroFactura){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
													g_operadores.depositos(identificador,
													fecha_deposito,valor_deposito, 
													numero_papeleta,numero_factura)
					 						VALUES ('$identificador','$fecha','$valor','$numeroPapeleta','$numeroFactura');");
		return $res;
		
		
	}
	
	
	
	
public function abrirAgencias ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT o.*
											FROM g_operadores.operadores o, g_financiero.orden_pago f
											WHERE o.identificador = f.identificador_operador");
		return $res;
	}
	
	public function guardarNuevoArchivoFitosanitario ($conexion,$identificador_agencia,$nombre_agencia, $serialArchivo){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_certificados.archivos_fitosanitarios(
            										nombre_agencia, fecha,identificador_agencia, serial)
											VALUES ('$nombre_agencia',now(),'$identificador_agencia', $serialArchivo)
											RETURNING id_archivo_fitosanitario;");
		return $res;
	}

	public function guardarImportador ($conexion,$idArchivoFitosanitario,$idPaisDestino,$nombrePaisDestino,$nombreDestinatario,$direccionDestinatario){
			
		$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.importadores(
            									id_archivo_fitosanitario,id_pais_destino, 
												nombre_pais_destino,direccion_destinatario,nombre_destinatario)
											VALUES ( $idArchivoFitosanitario,'$idPaisDestino',
													'$nombrePaisDestino','$direccionDestinatario','$nombreDestinatario')
											RETURNING id_importador;");
		return $res;
	}
	
	public function guardarCabeceraFitosanitario ($conexion,$idImportador,$idExportador,$idPuertoEntrada,$idPaisOrigen,$idProvinciaOrigen,$idTransporte,$numeroBulto,$descripcionBulto,$declaracionAdicional,$fechaTratamiento,$tratamiento,$productoQuimico,$duracion,$descripcionDuracion,$temperatura,$descripcionTemperatura,$concentracion,$descripcionConcentracion){
			
	$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.tmp_fitosanitarios(
            										id_importador, identificador_exportador, 
            										id_puerto_entrada, id_pais_origen, id_medio_transporte, numero_bulto, 
            										descripcion_bulto, declaracion_adicional, fecha_tratamiento, 
            										tratamiento, producto_quimico, duracion, descripcion_duracion, 
            										temperatura, descripcion_temperatura, concentracion, descripcion_concentracion, 
            										estado, id_provincia_origen)
										VALUES ($idImportador,'$idExportador',
												 $idPuertoEntrada,$idPaisOrigen,$idTransporte,'$numeroBulto',
												 '$descripcionBulto','$declaracionAdicional',now(),
												 '$tratamiento','$productoQuimico','$duracion','$descripcionDuracion',
												 '$temperatura','$descripcionTemperatura','$concentracion','$descripcionConcentracion',
												  1,$idProvinciaOrigen)
										RETURNING id_tmp_fitosanitario;");
				return $res;
	}
	
	
	public function guardarDetalleFitosanitario ($conexion,$idTmpFitosanitario,$id_producto,$partidaArancelaria,$nombre_producto,$marca_producto,$cantidad_producto,$unidad_producto,$descripcion_producto){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.tmp_detalle_fitosanitarios(
														            id_tmp_fitosanitario, id_producto, 
														            partida_arancelaria, nombre_producto, 
																	marca_producto, cantidad_producto,unidad_producto,descripcion_producto)
											VALUES ($idTmpFitosanitario,$id_producto,
													'$partidaArancelaria','$nombre_producto',
													'$marca_producto','$cantidad_producto','$unidad_producto','$descripcion_producto');");
				return $res;
	}
	
	public function datosCertificadosTmp ($conexion,$identificador){
									
		$res = $conexion->ejecutarConsulta("select a.nombre_agencia,i.nombre_pais_destino,i.nombre_destinatario,tm.*
											from g_certificados.archivos_fitosanitarios a,
												g_certificados.importadores i,
												g_certificados.tmp_fitosanitarios tm
											where a.id_archivo_fitosanitario = i.id_archivo_fitosanitario
											and i.id_importador= tm.id_importador
											and a.identificador_agencia =  '$identificador';");
		return $res;
	}

	
	public function buscarDatosCertificadoOperador ($conexion,$idCertificado){
			
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM  
													g_certificados.tmp_fitosanitarios
											WHERE 
													id_tmp_fitosanitario = '$idCertificado';");
		return $res;
	}	
	
	public function buscarDatosCertificadoOperadorDetalle ($conexion,$idCertificado){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM  
												g_certificados.tmp_detalle_fitosanitarios
											WHERE 
												id_tmp_fitosanitario = '$idCertificado';");
		return $res;
	}
	
	public function buscarOperador ($conexion,$identificador_exportador){
	
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_operadores.operadores
											WHERE
													identificador='$identificador_exportador';");
					
			return $res;
	}
		
	public function buscarTipoCertificado($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_certificados.certificados;");
		return $res;
	}
	
	public function  generarNumeroFitosanitario($conexion,$codigo){
		
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_certificado) as numero
											FROM
												g_certificados.fitosanitarios
											WHERE numero_certificado LIKE '$codigo';");
		return $res;
	}
	
	public function nombreLocalizacion ($conexion, $id_localizacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												nombre
											FROM
												g_catalogos.localizacion
											WHERE
												id_localizacion = $id_localizacion;");
		return $res;
	}
	
	public function obtenerPuerto ($conexion, $id_puerto){
	
		$res = $conexion->ejecutarConsulta("SELECT
													nombre_puerto
											FROM
													g_catalogos.puertos
											WHERE
													id_puerto = $id_puerto;");
		return $res;
	}
	
	public function obtenerNombreAgencia ($conexion, $idAgencia){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificados.archivos_fitosanitarios
											WHERE
												identificador_agencia = '$idAgencia';");
		return $res;
	}
	
																																																																																																							
	public function guardarCabeceraFitosanitarioFinal ($conexion,$identificadorEmisor,$numero,$idLocalizacion,$nombreLocalizacion,$identificadorExportador,$nombreExportador,$idPuertoEntrada,$puertoEntrada,$idPaisOrigen,$nombrePaisOrigen,$idMedioTransporte,$medioTransporte,$numeroBulto,$descripcionBulto,$declaracionAdicional,$idAgencia,$nombreAgencia,$fechaTratamiento,$tratamiento,$productoQuimico,$duracion,$descripcionDuracion,$temperatura,$descripcionTemperatura,$concentracion,$descripcionConcentracion){
				
		$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.fitosanitarios(
														            id_certificado, identificador_emisor, numero_certificado, 
														            fecha_emision, id_lugar_emision, nombre_lugar_emision, 
																	identificador_exportador,nombre_exportador,  
																	id_puerto_entrada,nombre_puerto_entrada, 
																	id_pais_origen, 
																	nombre_pais_origen, id_medio_transporte, nombre_medio_transporte, 
														            numero_bulto, descripcion_bulto, declaracion_adicional, 
																	identificador_agencia,nombre_agencia, fecha_tratamiento,estado,
																	tratamiento,producto_quimico,duracion,descripcion_duracion,
																	temperatura,descripcion_temperatura,concentracion,descripcion_concentracion	)
															VALUES (1,'$identificadorEmisor','$numero',
																	now(),$idLocalizacion,'$nombreLocalizacion',
																	$identificadorExportador,'$nombreExportador',
																	$idPuertoEntrada,'$puertoEntrada',
																	$idPaisOrigen,
																	'$nombrePaisOrigen',$idMedioTransporte,'$medioTransporte',
																	'$numeroBulto','$descripcionBulto','$declaracionAdicional',
																	$idAgencia,'$nombreAgencia','$fechaTratamiento',1,
				                                                    '$tratamiento','$productoQuimico','$duracion','$descripcionDuracion',
																	'$temperatura','$descripcionTemperatura','$concentracion','$descripcionConcentracion')
														RETURNING id_fitosanitario;");
		return $res;
	}
	
	public function obtenerDatosDetalleFitosanitario ($conexion, $idFitosanitario){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificados.tmp_detalle_fitosanitarios
											WHERE
												id_tmp_fitosanitario = $idFitosanitario;");
		return $res;
	}
														
	public function guardarDetalleFitosaniatarioFinal ($conexion,$idFitosanitario,$idProducto,$partidaArancelaria,$nombreProducto,$marcaProducto,$cantidadProducto,$unidadProducto){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.detalle_fitosanitarios(
           														 	id_fitosanitario, id_producto, partida_arancelaria, 
            														nombre_producto, marca_producto, cantidad_producto, unidad_producto)
															VALUES ($idFitosanitario,$idProducto,'$partidaArancelaria',
																	'$nombreProducto','$marcaProducto','$cantidadProducto','$unidadProducto');");
		return $res;
	}
	
	public function filtrarCertificadosSeleccionados ($conexion, $idAgencia,$fechaInicio,$fechaFin,$estado){
		
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_certificados.fitosanitarios
											WHERE
													identificador_agencia = '$idAgencia'
											 
											AND     estado = $estado;");
		return $res;
	}
	
	public function obtenerIdServicioXarea ($conexion, $idArea, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT 
												id_servicio 
						    				FROM 
												g_financiero.servicios
											WHERE
												id_servicio_padre is null and
												estado = '$estado' and
												id_area = '$idArea';");
		return $res;
	}
	
	public function obtenerServicioXarea($conexion, $idServicio, $categoria){
		
		$busqueda = '';
		switch ($categoria){
			case 'TODO': $busqueda = ''; break;
			case 'UNIDAD': $busqueda = 'WHERE id_categoria_servicio = 1'; break;
			case 'DOCUMENTOS': $busqueda = 'WHERE id_categoria_servicio = 2'; break;
			case 'ITEMS': $busqueda = 'WHERE id_categoria_servicio = 3'; break;
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.buscar_servicio_recursivo($idServicio)
											" . $busqueda .";");
				return $res;
	}
	
	
	public function listarAreas($conexion){
		$res = $conexion->ejecutarConsulta("SELECT 
													*
											FROM g_estructura.area;");
		return $res;
	}
	
	public function listaArea($conexion,$nombreArea){
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM g_estructura.area
											WHERE nombre = '$nombreArea';");
		return $res;
	}
	
	public function listaCertificados($conexion){
		$res = $conexion->ejecutarConsulta("SELECT c.*, s.unidad,s.valor
											FROM   g_certificados.certificados c,
												   g_financiero.servicios s
											WHERE c.id_certificado= s.id_servicio;");
		return $res;
	}
	
	public function guardarNuevoServicio ($conexion,$concepto,$unidad,$valor,$idArea,$iva){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.servicios(concepto,unidad,valor,id_area,iva)
											VALUES ('$concepto','$unidad','$valor','$idArea','$iva');");
		return $res;
	}
		
	public function guardarNuevoServicioCertificado ($conexion,$idArea,$tipoCertificado){

		$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.certificados(id_area, tipo_certificado)
											VALUES ('$idArea','$tipoCertificado');");
		return $res;
	}
	
	
	
	public function guardarNuevoCertificado($conexion,$exportador,$direccion,$fecha,$deposito,$numero_cajas){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
													g_operadores.fitos(nombre_exportador,
													direccion_exportador,fecha_solicitud,
													id_deposito,numero_cajas)
											VALUES ('$exportador','$direccion','$fecha','$deposito','$numero_cajas');");
		return $res;
	
	}

	public function abrirServicios ($conexion,$idServicio){
		$res = $conexion->ejecutarConsulta("SELECT 
													s.*, a.nombre
											FROM 
													g_estructura.area a, 
													g_financiero.servicios s
											WHERE 
													s.id_area = a.id_area and
													s.id_servicio = '$idServicio';");
		return $res;
	}
	
	
	public function actualizarServicio($conexion, $idCertificado,$idArea,$tipoCertificado){
		
		$res = $conexion->ejecutarConsulta("UPDATE
													g_certificados.certificados
											SET
													id_area= '$idArea', 
													tipo_certificado= '$tipoCertificado'
											WHERE
													id_certificado = $idCertificado;");
		return $res;
	}
	
	public function actualizarValorServicio($conexion,$idServicio, $concepto,$unidad,$valor,$area,$iva){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_financiero.servicios
											SET		concepto = '$concepto',		
													unidad= '$unidad',
													valor= '$valor',
													id_area  = '$area',
													iva  = '$iva'
											WHERE
													id_servicio = $idServicio;");
		return $res;
	}
	
	
	public function generarNumeroDocumento($conexion,$codigo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_solicitud) as numero
											FROM 
												g_financiero.orden_pago
											WHERE 
												numero_solicitud LIKE '$codigo';");
		return $res;
	}
		
	public function guardarOrdenPago($conexion, $idOperador, $numeroSolicitud, $total_pagar, $observacion, $localizacion, $nombreProvincia, $idProvincia ,$identificadorUsuario, $idSolicitud = null, $tipoSolicitud = null, $idGrupoSolicitud=null, $estado = '3', $metodoPago = null){

		$idGrupoSolicitud = ($idGrupoSolicitud==null?0:$idGrupoSolicitud);
		$idSolicitud = ($idSolicitud==null?0:$idSolicitud);
		$tipoSolicitud = ($tipoSolicitud==null?0:$tipoSolicitud);
				
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.orden_pago(
											identificador_operador, numero_solicitud, 
            									        fecha_orden_pago,total_pagar,observacion,estado,localizacion, nombre_provincia, id_provincia, identificador_usuario, tipo_solicitud, id_grupo_solicitud, id_solicitud, metodo_pago)
											VALUES ('$idOperador','$numeroSolicitud',
												now(),'$total_pagar','$observacion',$estado,'$localizacion', '$nombreProvincia', $idProvincia,
												'$identificadorUsuario', '$tipoSolicitud', $idGrupoSolicitud, '$idSolicitud', '$metodoPago') RETURNING id_pago;");
		return $res;
	
	}
	
	public function guardarTotal($conexion,$idPago,$idServicio,$conceptoOrden,$cantidad,$descuento,$precioUnitario,$iva,$total, $subsidio = 0){
					
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.detalle_pago(
            										id_pago, id_servicio, concepto_orden, 
													cantidad, precio_unitario,descuento,iva,total, subsidio)
											VALUES ('$idPago','$idServicio','$conceptoOrden',
													'$cantidad','$precioUnitario','$descuento','$iva','$total','$subsidio') RETURNING id_detalle;");
		return $res;
	}
	
	public function listarOrdenPago ($conexion, $estado, $nombreProvincia, $tipoSolicitud){
	
		$busqueda = '';
		switch ($estado){
			case 'ABIERTOS': $busqueda = 'o.estado IN (1,2,3) and '; break;
		}
	
			$res = $conexion->ejecutarConsulta("SELECT 
													*
												FROM
													g_financiero.orden_pago o
												WHERE
													". $busqueda ."
													nombre_provincia = '$nombreProvincia' and
													tipo_solicitud = '$tipoSolicitud' 
												ORDER BY
													o.id_pago;");
		return $res;
	}
	
	public function abrirOrdenPago ($conexion, $id_pago){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_financiero.orden_pago o,
												g_financiero.clientes c
											where
												o.identificador_operador = c.identificador and
												o.id_pago = $id_pago;");
				return $res;
	}

	public function abrirDetallePago ($conexion, $id_pago){

		$res = $conexion->ejecutarConsulta("SELECT
												d.*,
												s.unidad_medida
											FROM
												g_financiero.detalle_pago d,
												g_financiero.servicios s
											WHERE
												d.id_servicio = s.id_servicio and
												d.id_pago = $id_pago;");
				return $res;
	}
	
	public function abrirLiquidarOrdenPago ($conexion,$idPago){
	
		$res = $conexion->ejecutarConsulta("SELECT 
													po.* 
											from
													g_financiero.orden_pago o,
													g_financiero.detalle_forma_pago po	
											WHERE
													o.id_pago = po.id_pago and
													o.id_pago = $idPago;");
		return $res;
	}

	public function finalizarOrdenPago($conexion,$idPago,$valorDepositado, $rucInstitucion, $numeroFactura, $numeroEstablecimiento, $puntoEmision){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												valor_deposito='$valorDepositado',
												fecha_facturacion = now(),
												ruc_institucion = '$rucInstitucion',
												numero_factura = '$numeroFactura',
												numero_establecimiento = '$numeroEstablecimiento',
												punto_emision = '$puntoEmision'
											WHERE
												id_pago= $idPago;");
		return $res;
	
	}

	public function actualizarEstadoClaveAcceso($conexion,$idPago, $claveAcceso, $estadoSRI){
	
		$res = $conexion->ejecutarConsulta("UPDATE
													g_financiero.orden_pago
											SET
													estado= '4',
													estado_sri = '$estadoSRI',
													clave_acceso = '$claveAcceso'
											WHERE
													id_pago = $idPago;");
		return $res;
	}
	

	public function actualizarObservacionSRIFactura($conexion,$idPago, $observacionSRI, $rutaArchivo=null){
			
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												observacion_sri= '$observacionSRI',
												ruta_xml = '$rutaArchivo'
											WHERE
												id_pago = $idPago;");
		return $res;
	}


	public function filtrarOrdenServicio($conexion, $operador,$numeroFactura, $fechaInicio, $fechaFin, $estado, $localizacion, $tipoSolicitud){
		$operador = $operador!="" ? "'" . $operador . "'" : "null";
		$numeroFactura = $numeroFactura!="" ? "'" . $numeroFactura . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$tipoSolicitud = $tipoSolicitud!="" ? "'" . $tipoSolicitud . "'" : "null";
				
		$res = $conexion->ejecutarConsulta("SELECT
												o.*,
												c.razon_social
											FROM
												g_financiero.mostrar_orden_pago_filtrados($operador, $numeroFactura, $fechaInicio, $fechaFin, $estado, $localizacion, $tipoSolicitud) o,
												g_financiero.clientes c
											WHERE
												o.identificador_operador = c.identificador;");
	
				return $res;
	}
	
	public function filtrarOrdenServicioSRI($conexion, $operador,$numeroFactura, $fechaInicio, $fechaFin, $estado,$localizacion){
		$operador = $operador!="" ? "'" . $operador . "'" : "null";
		$numeroFactura = $numeroFactura!="" ? "'" . $numeroFactura . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		$estado = $estado!="" ? "'" . $estado . "'" : "null";
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";

		
		$res = $conexion->ejecutarConsulta("SELECT
												o.*,
												c.razon_social
											FROM
												g_financiero.mostrar_orden_pago_filtrados_sri($operador, $numeroFactura, $fechaInicio, $fechaFin, $estado, $localizacion) o,
												g_financiero.clientes c
											WHERE
												o.identificador_operador = c.identificador;");
	
				return $res;
	}
	
	/*public function obtenerMaxSaldo($conexion,$idOperador){
		
		$res = $conexion->ejecutarConsulta("SELECT 	
												MAX(s.id_saldo) as id_saldo
											FROM 
												g_financiero.orden_pago o,g_financiero.saldos s
											WHERE 
												o.id_pago = s.id_pago
											AND 
												o.identificador_operador = '$idOperador';");
		return $res;
	}*/
	
	/*public function obtenerDatosOperador($conexion,$idSaldo){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.saldos
											WHERE 
												id_saldo = '$idSaldo';");
				return $res;
	}
	
	public function guardarSaldoOperador($conexion,$idPago,$fecha,$saldo, $nuevoSaldo,$idOperador){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.saldos(
														id_pago, fecha_deposito,valor_ingreso,saldo_disponible,identificador_operador)
												VALUES ($idPago,'$fecha','$saldo','$nuevoSaldo','$idOperador');");
		return $res;
	}
	
	
	public function guardarNuevoSaldo($conexion,$idPago,$fecha,$valorDepositado,$saldoDisponible,$idOperador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.saldos(
            										id_pago, fecha_deposito, 
													valor_ingreso,saldo_disponible,identificador_operador)
											VALUES ('$idPago','$fecha',
													'$valorDepositado','$saldoDisponible','$idOperador') ;");
		return $res;
	}*/
											
	/*public function guardarNuevoSaldoAgencia($conexion,$idPago,$valorIngresado,$registro,$saldoDisponible,$idOperador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.saldos(
            													    id_pago, 
																	fecha_deposito, valor_ingreso, 
																	valor_egreso,saldo_disponible,identificador_operador)
															VALUES ('$idPago',now(),'$valorIngresado',
																	'$registro','$saldoDisponible','$idOperador') ;");
		return $res;
	}*/
	
	public function actualizarEstadoCertificado($conexion,$id_tmp_fitosanitario){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_certificados.tmp_fitosanitarios
											SET
												estado= '2'
											WHERE
												id_tmp_fitosanitario = '$id_tmp_fitosanitario';");
		return $res;
	}
	
	public function abrirDatosFitosanitarioTmp ($conexion,$idFitosanitario){
			
		$res = $conexion->ejecutarConsulta("SELECT
											*
											FROM  g_certificados.tmp_fitosanitarios
											WHERE id_tmp_fitosanitario = $idFitosanitario;");
		return $res;
	}
	
	public function nombreTransporte ($conexion,$idTransporte){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM  g_catalogos.medios_transporte
											WHERE id_medios_transporte = $idTransporte;");
		return $res;
	}
	
	public function obtenerDatosImportador ($conexion,$idImportador){
			
		$res = $conexion->ejecutarConsulta("select
												i.id_pais_destino
											from
												g_certificados.tmp_fitosanitarios tm,g_certificados.importadores i
											where tm.id_importador = i.id_importador 
											and tm.id_importador = $idImportador;");
		return $res;
	}
	
	
	
	###################################################################
	
	/*public function validarDatosCertificado($conexion,$certificado, $detalleCertificado, $identificadorAgencia){
		
		$cc = new ControladorCatalogos();
		$cr = new ControladorRegistroOperador();
		$cf = new ControladorCertificados();
		$crc = new ControladorRequisitos();
				
		$resultado = array();
		$mensaje = '';
		$resultado[0] = true;

		for($i=0 ; $i< count($certificado); $i++){
			
		//Buscar datos de importador
			$qDatosImportador = $this->obtenerDatosImportador($conexion,$certificado['id_importador']);
			$datosImportador = pg_fetch_assoc($qDatosImportador);

		 //Validación registro operador
		 $operador = $cr->buscarOperador($conexion, $certificado['identificador_exportador']);
 		 if( pg_num_rows($operador) == 0 ){
			$resultado[0] = false;
			$mensaje = 'El exportador '.$certificado['identificador_exportador'].' no se encuentra registrado en Agrocalidad';
		 	break;
 		 }
 		 
		 
 		 //Validación de pais de destino
		 $paisDestino = $cc->obtenerCodigoLocalizacion($conexion, $datosImportador['id_pais_destino']);
		 if( pg_num_rows($paisDestino) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'El codigo del país de destino '.$certificado['id_pais_destino'].' no se encuentra registrado en Agrocalidad';
		 	break;
		 }
		 
		 //Validación de puerto de entrada
		 $puertoEntrada = $cc->obtenerPuertoCodigo($conexion,$certificado['id_puerto_entrada']);
		 if( pg_num_rows($puertoEntrada) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'El codigo del puerto de entrada '.$certificado['id_puerto_entrada'].' no se encuentra registrado en Agrocalidad';
		 	break;
		 }
		 
		 //Validación pais de origen
		 $paisOrigen = $cc->obtenerCodigoLocalizacion($conexion,$certificado['id_pais_origen']);
		 if( pg_num_rows($paisOrigen) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'El país de origen con código '.$certificado['id_pais_origen'].' no se encuentra registrado en Agrocalidad';
		 	break;
		 }
		 
		 //Validación provincia
		 $provinciaOrigen = $cc->obtenerCodigoLocalizacion($conexion,$certificado['id_provincia_origen']);
		 if( pg_num_rows($provinciaOrigen) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'La provincia con código '.$certificado['id_provincia_origen'].' no se encuentra registrado en Agrocalidad';
		 	break;
		 }
		 
		 //Validación que verifica si la agencia de carga tiene registrado como proveedor al exportador
		 $qProveedores = $cr->buscarProveedoresOperador($conexion, $identificadorAgencia, $certificado['identificador_exportador']);
		 
		 if( pg_num_rows($qProveedores) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'La agencia de carga '.$identificadorAgencia.' no ha registrado al exportador '.$certificado['identificador_exportador'].' como proveedor';
		 	break;
		 }
		 	 
		 
		 for ($i = 0; $i < count ($detalleCertificado); $i++) {
		 	
		 	//validar existencia de producto
		 
		 	//TODO: Hacer funcion en controlador catalogos que resiva los parametros de partida arancelaria y codigo produto (idProducto-> XML)  g_catalogos.productos
		 $qProducto = $cc->buscarProductoPartidaCodigo($conexion, $detalleCertificado[$i]['partida_arancelaria'],$detalleCertificado[$i]['id_producto'] );
		 if( pg_num_rows($producto) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'El producto con partida '.$detalleCertificado[$i]['partida_arancelaria'].' y codigo '.$detalleCertificado[$i]['id_producto'].'  no se encuentra registrado en Agrocalidad';
		 	break;
		 }
		 
		 //Obtener id de operacion de agencia de carga
		 $qOperacionAgencia = $cc -> buscarIdOperacion($conexionGUIA, 'SV', 'Agencia de carga');
		 $producto = pg_fetch_assoc($qProducto);
		 
		 //Validación que verifica si el exportador tiene una actividad de agencia de carga para el producto con estado registrado
		 
		 $qOperacionAgenciaCarga = $cr->buscarOperadorProductoActividad($conexion, $identificadorAgencia, $producto['id_producto'], pg_fetch_result($qOperacionAgencia, 0, 'id_tipo_operacion'), 'registrado');
		 if( pg_num_rows($qOperacionAgenciaCarga) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'La agencia de carga no posee registrado el producto '.$producto['nombre_comun'];
		 	break;
		 }
		 
		 //Obtener id de operacion de agencia de carga
		 $qOperacionExportador = $cc -> buscarIdOperacion($conexionGUIA, 'SV', 'Exportador');
		 
		 //Validación para verificar si el exportador tiene una actividad de exportacion para el pais, producto y en estado registrado.
		 $qOperacionExportador = $cr->buscarOperadorProductoPaisActividad($conexion, $certificado['identificador_exportador'], pg_fetch_result($paisDestino, 0, 'id_localizacion'), $producto['id_producto'], pg_fetch_result($qOperacionExportador, 0, 'id_tipo_operacion'), 'registrado');
		 if( pg_num_rows($qOperacionExportador) == 0 ){
		 	$resultado[0] = false;
		 	$mensaje = 'El exportador '.$certificado['identificador_exportador'].' no tiene registrado el producto '.$producto['nombre_comun'].' para el país '.pg_fetch_result($paisDestino, 0, 'nombre').'';
		 	break;
		 }
		  
	   }
	   
	}
	

		 
		 if (!$resultado[0]){
		 	$resultado[1] = $mensaje;
		  	return $resultado;
		 }else
		 	return true;
	}
	
	###################################################################
		
	public function insertarDatosCertificado($conexion,$certificado){
		$cf = new ControladorCertificados();
		$cc = new ControladorCatalogos();
		$resultado = array();
		
		for ($i=0;$i< count($certificado);$i++)  {
			
		//Obtener registros cabecera temporales
		$res = $cf->abrirDatosFitosanitarioTmp($conexion, $certificado);
		$idCertificados = pg_fetch_assoc($res);
			
		//Buscar certificado
		$respuesta = $cf->generarNumeroFitosanitario($conexion, '%'.$idCertificados['id_provincia_origen'].'%');
		$fitosanitario = pg_fetch_assoc($respuesta);
		$tmp= explode("-", $fitosanitario['numero']);
		$incremento = end($tmp)+1;
		$numero = 'FITO-'.$idCertificados['id_provincia_origen'].'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
		
		//Nombre provincia localizacion
		$res = $cf->nombreLocalizacion($conexion,$idCertificados['id_provincia_origen']);
		$nombreLocalizacion = pg_fetch_assoc($res);
		
		//Nombre pais localizacion
		$res = $cf->nombreLocalizacion($conexion,$idCertificados['id_pais_origen']);
		$nombrePaisLocalizacion = pg_fetch_assoc($res);
		
		//Datos operador
		$operadorCertificado = $cf ->buscarOperador($conexion,$idCertificados['identificador_exportador']);
		$operador = pg_fetch_assoc($operadorCertificado);
		
		//Nombre puertos
		$res = $cf -> obtenerPuerto($conexion,$idCertificados['id_puerto_entrada']);
		$nombrePuertoEntrada = pg_fetch_assoc($res);
		
		//Pais origen
		$nombrePaisO = $cf -> nombreLocalizacion($conexion,$idCertificados['id_pais_origen']);
		$nombrePaisOrigen = pg_fetch_assoc($nombrePaisO);
		
		//Medio de transporte
		$transporte = $cf -> nombreTransporte($conexion,$idCertificados['id_medio_transporte']);
		$nombreTransporte = pg_fetch_assoc($transporte);
		
		//Nombre agencia
		$res = $cf -> obtenerNombreAgencia($conexion,$_SESSION['usuario']);
		$nombreAgencia = pg_fetch_assoc($res);
		
		//Cabecera fitosanitario
		$idFitosanitarioCabecera = $cf -> guardarCabeceraFitosanitarioFinal($conexion,$_SESSION['usuario'],$numero,$idCertificados['id_provincia_origen'],$nombreLocalizacion['nombre'],$idCertificados['identificador_exportador'],$operador['razon_social'],$nombrePaisLocalizacion['nombre'],$idCertificados['id_puerto_entrada'],$nombrePuertoEntrada['nombre_puerto'],$idCertificados['id_pais_origen'],$nombrePaisOrigen['nombre'],$idCertificados['id_medio_transporte'],$nombreTransporte['tipo'],$idCertificados['numero_bulto'],$idCertificados['descripcion_bulto'],$idCertificados['declaracion_adicional'],$nombreAgencia['identificador_agencia'],$nombreAgencia['nombre_agencia'],$idCertificados['fecha_tratamiento'],$idCertificados['tratamiento'],$idCertificados['producto_quimico'],$idCertificados['duracion'],$idCertificados['descripcion_duracion'],$idCertificados['temperatura'],$idCertificados['descripcion_temperatura'],$idCertificados['concentracion'],$idCertificados['descripcion_concentracion']);
		$idFitosanitarioDetalle = pg_fetch_result($idFitosanitarioCabecera, 0, 'id_fitosanitario');
		
		//Detalle fitosanitario
		$resp = $cf -> obtenerDatosDetalleFitosanitario($conexion,$idCertificados['id_tmp_fitosanitario']);
				
		//Producto
		while ($detalleFitosanitario = pg_fetch_assoc($resp)){
			$res = $cf -> guardarDetalleFitosaniatarioFinal($conexion,$idFitosanitarioDetalle,$detalleFitosanitario['id_producto'],$detalleFitosanitario['partida_arancelaria'],$detalleFitosanitario['nombre_producto'],$detalleFitosanitario['marca_producto'],$detalleFitosanitario['cantidad_producto'],$detalleFitosanitario['unidad_producto']);
		}

		//Actualizar estado para mostrar certificados
		$cf -> actualizarEstadoCertificado($conexion,$idCertificados['id_tmp_fitosanitario']);
		
		//Descontar saldo operador
		$idSaldoOperador = $cf -> obtenerMaxSaldo($conexion,$_SESSION['usuario']);
		$idSaldo = pg_fetch_result($idSaldoOperador, 0, 'id_saldo');
		$saldoOperador = $cf -> obtenerDatosOperador($conexion,$idSaldo);
		$saldoDeOperador =  pg_fetch_assoc($saldoOperador);
			
		$saldoDisponible = $saldoDeOperador['saldo_disponible'] - count($certificado);
		$cf ->guardarNuevoSaldoAgencia($conexion,$saldoDeOperador['id_pago'],$saldoDeOperador['valor_ingreso'],count($certificado),$saldoDisponible,$_SESSION['usuario']);
		

		}	
	
	}*/
	
	public function buscarArchivo($conexion, $idAgencia, $serialAgencia){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificados.archivos_fitosanitarios
											WHERE
												identificador_agencia = '$idAgencia'
												and serial = $serialAgencia;");
				return $res;
	}
	
	public function buscarArchiParcial($conexion, $idArchivoFitosanitario, $parcialArchivoAgencia){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificados.archivo_parcial
											WHERE
												id_archivo_fitosanitario = $idArchivoFitosanitario
												and numero_parcial = $parcialArchivoAgencia ;");
		return $res;
	}
	
	public function buscarImportador($conexion,$idPaisDestino,$nombreDestinatario,$idArchivoFitosanitario){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_certificados.importadores
											WHERE
												id_archivo_fitosanitario = $idArchivoFitosanitario
												and id_pais_destino = '$idPaisDestino'
												and TRIM(UPPER(nombre_destinatario))= TRIM(UPPER('$nombreDestinatario'))");
		return $res;
	}

	
	public function guardarNuevoArchivoFitosanitarioParcial($conexion,$idArchivoFitosanitario,$parcialArchivoAgencia, $rutaArchivo){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_certificados.archivo_parcial 
											VALUES ($idArchivoFitosanitario,$parcialArchivoAgencia, '$rutaArchivo');");
		return $res;
	}
	
	Public function listarAreaServicio($conexion){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT a.id_area,a.nombre
											FROM g_estructura.area a,g_financiero.servicios s
											WHERE s.id_area = a.id_area;");
		return $res;
	}
	
	public function guardarNuevoCliente($conexion,$idnuevoCliente,$tipoIdentificador,$razonSocial,$direccion,$telefono,$correo){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.clientes(
													identificador, tipo_identificacion, razon_social,
													direccion,telefono,correo)
											VALUES ('$idnuevoCliente','$tipoIdentificador','$razonSocial',
													'$direccion','$telefono','$correo') RETURNING identificador;");
		return $res;
	
	}
	
	public function guardarRutaOrdenPago($conexion,$idPago,$rutaOrdenPago){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												orden_pago= '$rutaOrdenPago'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function listarDatosInstitucion($conexion, $identificacdor){
				
		$res = $conexion->ejecutarConsulta("SELECT 
												o.id_oficina_recaudacion,
												o.numero_establecimiento,
												o.ruta_firma,
												o.punto_emision,
												o.provincia,
												o.id_provincia,
												o.iva,
												o.fecha_caducidad_pfx,
												o.clave_pfx,
												d.*
											FROM  
												g_financiero.oficina_recaudacion o, 
												g_financiero.distritos d
											WHERE 
												o.ruc = d.ruc and
												--UPPER(o.provincia) = UPPER('$provincia')
												o.identificador_firmante = '$identificacdor'
												and d.estado = 'activo';");
		return $res;
	}
	
		public function listarDatosInstitucionAntiguo($conexion, $identificacdor){
	
		$res = $conexion->ejecutarConsulta("SELECT
												o.id_oficina_recaudacion,
												o.numero_establecimiento,
												o.ruta_firma,
												o.punto_emision,
												o.provincia,
												o.id_provincia,
												o.iva,
												o.fecha_caducidad_pfx,
												o.clave_pfx,
												d.*
											FROM
												g_financiero.oficina_recaudacion o,
												g_financiero.distritos d
											WHERE
												o.ruc = d.ruc and
												--UPPER(o.provincia) = UPPER('$provincia')
												o.identificador_firmante = '$identificacdor'
												and d.estado = 'inactivo';");
		return $res;
	}
	
	public function listaComprador ($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.clientes c
											WHERE
												identificador='$identificador';");
		return $res;
	}
	
	public function obtenerDatosDetalleFactura($conexion, $idPago){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.detalle_total_factura ($idPago)");
		return $res;
	}
	
	public function generarNumeroFactura($conexion,$rucInstitucion, $numeroEstablecimineto, $puntoEmision){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_factura)::integer +1 as numero
											FROM 
												g_financiero.orden_pago
											WHERE 
												ruc_institucion = '$rucInstitucion' and
												numero_establecimiento = '$numeroEstablecimineto' and
												punto_emision = '$puntoEmision' and
												tipo_solicitud != 'Ingreso Caja';");
		return $res;
	}
	
	public function guardarNumeroFactura($conexion,$idPago,$numeroFactura){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												numero_factura = '$numeroFactura'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function abrirDetalleFactura($conexion, $idPago){
		$df = $conexion->ejecutarConsulta("SELECT
												*
											FROM   
												g_financiero.detalle_pago
											WHERE  
												id_pago = $idPago;");
			
		while ($fila = pg_fetch_assoc($df)){
			$res[] = array(
					'idDetalle' => $fila['id_detalle'],
					'idPago' => $fila['id_pago'],
					'idServicio' => $fila['id_servicio'],
					'concepto' => $fila['concepto_orden'],
					'cantidad' => $fila['cantidad'],
					'precioUnitario' => $fila['precio_unitario'],
					'descuento' => $fila['descuento'],
					'iva' => $fila['iva'],
					'total' => $fila['total'],
					'subsidio' => $fila['subsidio']);
		}
		return $res;
	}
	
	public function abrirClientes ($conexion){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT
													c.*
											FROM
													g_financiero.clientes c, g_financiero.orden_pago f
											WHERE   c.identificador = f.identificador_operador;");
		return $res;
	}
	
	public function calcularDigito($codigo){
		if (is_numeric($codigo)){
			$x=2;
			$sumatorio=0;
			for ($i=strlen($codigo)-1;$i>=0;$i--){
				if ($x>7){$x=2;}
				$sumatorio=$sumatorio+($codigo[$i]*$x);
				$x++;
			}
			$digito=$sumatorio%11;
			$digito=11-$digito;
			switch ($digito){
				case 10:
					$digito="1";
					break;
				case 11:
					$digito="0";
					break;
			}
		}
		return $digito;
	}
	
	public function  firmarXML($pathProyecto, $rutaServidor, $nombreArchivoXML, $rutaFirma, $claveFirmante){
		
		$urlBase = 'http://localhost:8081/firmaXmlWS/rest/firma';
		$parametros = "";
		$datos = array(
				'fileInput' => $pathProyecto."/".$rutaServidor."/aplicaciones/financiero/archivoXml/generados/$nombreArchivoXML",
				'fileSignature'  => $pathProyecto."/".$rutaServidor."/".$rutaFirma,
				'passSignature'  => "$claveFirmante",
				'pathOutPut'  => $pathProyecto."/".$rutaServidor."/aplicaciones/financiero/archivoXml/firmados",
				'nameFileOutPut'  => "$nombreArchivoXML",
		        'nodo' => "factura"
		);
		
		foreach ($datos as $key => $value) {
			$parametros .= "$key=" . urlencode($value) . "&";
		}
		
		$url = "$urlBase?$parametros";
		
		$resultadoFirma = file_get_contents($url);
		
		return $resultadoFirma;
	}
	
	/*public function  enviarXMLSRI($pathProyecto, $rutaServidor, $nombreArchivoXML){
		
		$nombreComprobante = $pathProyecto.'/'.$rutaServidor.'/aplicaciones/financiero/archivoXml/firmados/'.$nombreArchivoXML;
		
		$comprobante = fopen($nombreComprobante, 'r');
		$contenidoComprobante = fread($comprobante, filesize($nombreComprobante));
		fclose($comprobante);
		
		try {
			$recepcion = new RecepcionComprobantesService();
			$xml = new validarComprobante();
			
			$xml->xml = $contenidoComprobante;
			$respuestaRecepcion = $recepcion->validarComprobante($xml);
				
			return $respuestaRecepcion;
		
		} catch (SoapFault $e) {
			 var_dump($e->getMessage());
			 die();
		}
		
	}*/
	
	public function  enviarXMLSRIOffline($pathProyecto, $rutaServidor, $nombreArchivoXML){
	
		$nombreComprobante = $pathProyecto.'/'.$rutaServidor.'/aplicaciones/financiero/archivoXml/firmados/'.$nombreArchivoXML;
	
		$comprobante = fopen($nombreComprobante, 'r');
		$contenidoComprobante = fread($comprobante, filesize($nombreComprobante));
		fclose($comprobante);
	
		try {
			$recepcion = new RecepcionComprobantesOfflineService();
			$xml = new validarComprobante();
				
			$xml->xml = $contenidoComprobante;
			$respuestaRecepcion = $recepcion->validarComprobante($xml);
	
			return $respuestaRecepcion;
	
		} catch (SoapFault $e) {
			var_dump($e->getMessage());
			die();
		}
	
	}
	
	/*public function  obtenerAutorizacionSRI($claveAccesoSRI){
		
		try {
			$autorizacion = new AutorizacionComprobantesService();
			$clave = new autorizacionComprobante();
			$clave->claveAccesoComprobante = $claveAccesoSRI;
				
			$respuestaAutorizacion = $autorizacion->autorizacionComprobante($clave);
			
			return $respuestaAutorizacion;			
				
		}catch (SoapFault $e){
			var_dump( $e->getMessage());
			die();
		}
		
	}*/
	
	public function  obtenerAutorizacionSRIOffline($claveAccesoSRI){
	
		try {
			$autorizacion = new AutorizacionComprobantesOfflineService;
			$clave = new autorizacionComprobante();
			$clave->claveAccesoComprobante = $claveAccesoSRI;
	
			$respuestaAutorizacion = $autorizacion->autorizacionComprobante($clave);
				
			return $respuestaAutorizacion;
	
		}catch (SoapFault $e){
			var_dump( $e->getMessage());
			die();
		}
	
	}
	
	public function actualizarDatosAutorizacionSRIFactura($conexion,$idPago, $estadoSRI, $numeroAutorizacion, $fechaAutorizacion, $rutaArchivo, $rutaFactura){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												estado_sri = '$estadoSRI',
												numero_autorizacion = '$numeroAutorizacion',
												fecha_autorizacion = '$fechaAutorizacion',
												ruta_xml = '$rutaArchivo',
												factura = '$rutaFactura'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function actualizarCliente($conexion,$idCliente,$tipoCliente,$razonSocial,$direccion,$telefono,$correo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.clientes
											SET
												tipo_identificacion= '$tipoCliente',
												razon_social = '$razonSocial',
												direccion = '$direccion',
												telefono = '$telefono',
												correo = '$correo'
											WHERE
												identificador = '$idCliente';");
		return $res;
	}
	
	public function actualizarOrdenPago($conexion,$idPago,$idCliente,$total_pagar,$observacion,$localizacion, $identificadorUsuario){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												identificador_operador = '$idCliente',
												total_pagar = '$total_pagar',
												observacion = '$observacion',
												localizacion = '$localizacion',
												identificador_usuario = '$identificadorUsuario'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function eliminarItemsOrdenPago($conexion,$idPago){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_financiero.detalle_pago
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function guardarPagoOrden($conexion,$idPago,$fechaDeposito,$idBanco=0,$nombreBanco,$papeletaBanco,$valorDepositado, $idNotaCredito=0, $idCuentaBancaria=0, $numeroCuentaBancaria=0){
					
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.detalle_forma_pago(id_pago, id_banco, institucion_bancaria, transaccion, valor_deposito, fecha_orden_pago, id_nota_credito, id_cuenta_bancaria, numero_cuenta)
											VALUES ($idPago,$idBanco,'$nombreBanco','$papeletaBanco','$valorDepositado','$fechaDeposito', $idNotaCredito, $idCuentaBancaria, $numeroCuentaBancaria) ;");
		return $res;
		
	}
	
	public function obtenerRegistrosPagoLaboratorios($conexion, $estado){
	    
	    $consulta = "SELECT distinct id_solicitud FROM g_laboratorios.pagos WHERE estado_financiero = '$estado';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function obtenerRegistrosPagoLaboratoriosPorSolicitud($conexion, $idSolicitud){
	    
	    $consulta = "SELECT id_pagos, p.id_cuenta_bancaria, cb.numero_cuenta, p.id_banco, eb.nombre, id_solicitud, numero_deposito, fecha_deposito, valor_depositado, estado_financiero
                     FROM g_laboratorios.pagos p INNER JOIN  g_catalogos.entidades_bancarias eb ON p.id_banco = eb.id_banco
                     INNER JOIN g_catalogos.cuentas_bancarias cb ON p.id_cuenta_bancaria = cb.id_cuenta_bancaria
                     WHERE id_solicitud = '$idSolicitud';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarEstadoRegistrosPagoLaboratorios($conexion, $idPago, $estado){
	    
	    $consulta = "UPDATE g_laboratorios.pagos SET estado_financiero = '$estado' WHERE id_pagos = '$idPago';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarFormaPago($conexion,$idPago,$fechaDeposito,$idBanco=0,$nombreBanco,$papeletaBanco,$valorDepositado, $idNotaCredito=0, $idCuentaBancaria=0, $numeroCuentaBancaria=0){
		
		$consulta ="UPDATE
						g_financiero.detalle_forma_pago
					SET
						id_banco = $idBanco,
						institucion_bancaria = '$nombreBanco',
						transaccion = '$papeletaBanco',
						valor_deposito = $valorDepositado,
						fecha_orden_pago = '$fechaDeposito',
						id_nota_credito = $idNotaCredito,
						id_cuenta_bancaria = $idCuentaBancaria,
						numero_cuenta = '$numeroCuentaBancaria'
					WHERE
						id_pago = $idPago";
					
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	
	}
	
	public function listarNotaCredito ($conexion, $estado, $localizacion){
	
		$busqueda = '';
		switch ($estado){
			case 'ABIERTOS': $busqueda = 'n.estado IN (1,2,3,4) and '; break;
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.nota_credito n
											WHERE
												". $busqueda ."
				n.localizacion = '$localizacion'
				order by n.id_nota_credito;");
				return $res;
	}
	
	public function abrirNotaCredito ($conexion, $idNotaCredito){
			
		$res = $conexion->ejecutarConsulta("SELECT
												nc.*,o.numero_factura,c.razon_social,c.correo,c.direccion
											FROM
												g_financiero.orden_pago o,
												g_financiero.nota_credito nc,
												g_financiero.clientes c
											WHERE
												nc.identificador_operador = c.identificador and
												nc.id_pago = o.id_pago and
												nc.id_nota_credito = $idNotaCredito 
												order by nc.id_nota_credito ASC;");
				return $res;
	}
	
	public function abrirDetalleNotaCredito($conexion, $idNotaCredito){
				
		$dnc = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.detalle_nota_credito
											WHERE
												id_nota_credito = $idNotaCredito;");
			
		while ($fila = pg_fetch_assoc($dnc)){
			$res[] = array(
					'idDetalleNC' => $fila['id_detalle_nota_credito'],
					'idNC' => $fila['id_nota_credito'],
					'idServicio' => $fila['id_servicio'],
					'concepto' => $fila['concepto_nota_credito'],
					'cantidad' => $fila['cantidad'],
					'precioUnitario' => $fila['precio_unitario'],
					'descuento' => $fila['descuento'],
					'iva' => $fila['iva'],
					'total' => $fila['total']);
		}
		return $res;
	}
	
	public function abrirDatosEmisor($conexion, $identificador){
	
		$res = $conexion->ejecutarConsulta("select
												o.*,d.*
											from
												g_financiero.oficina_recaudacion o,
												g_financiero.distritos d
											where
												o.ruc = d.ruc
												and o.identificador_firmante = '$identificador'
												and d.estado = 'activo'");
				return $res;
	}
	
	public function abrirDatosEmisorAntiguo($conexion, $identificador){
	
		$res = $conexion->ejecutarConsulta("select
				o.*,d.*
				from
				g_financiero.oficina_recaudacion o,
				g_financiero.distritos d
				where
				o.ruc = d.ruc
				and o.identificador_firmante = '$identificador'
				and d.estado = 'inactivo'");
				return $res;
	}
	
	
	public function generarNumeroNotaCredito($conexion,$rucInstitucion, $numeroEstablecimiento, $puntoEmision){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_nota_credito)::integer +1 as numero
											FROM
												g_financiero.nota_credito
											WHERE
												ruc_institucion = '$rucInstitucion' and
												numero_establecimiento = '$numeroEstablecimiento' and
												punto_emision = '$puntoEmision';");
		return $res;
	}
	
	public function guardarNotaCredito($conexion,$idPago,$idCliente,$numeroNotaCredito,$valorTotal,$motivo,$localizacion,$rucInstitucion,$identificadorUsuario, $numeroEstablecimiento, $puntoEmsion, $idProvincia, $nombreProvincia){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.nota_credito(
														id_pago, identificador_operador, numero_nota_credito,
														fecha_nota_credito, total_pagar, motivo,
														estado, localizacion, ruc_institucion, identificador_usuario, numero_establecimiento, punto_emision, id_provincia, nombre_provincia)
											VALUES ($idPago,'$idCliente','$numeroNotaCredito',
														now(),'$valorTotal','$motivo',
														3,'$localizacion','$rucInstitucion','$identificadorUsuario','$numeroEstablecimiento','$puntoEmsion', $idProvincia, '$nombreProvincia') RETURNING id_nota_credito;");
		return $res;
	
	}
	
	public function guardarTotalNotaCredito($conexion,$idNotaCredito,$idServicio,$conceptoOrden,$cantidad,$descuento,$precioUnitario,$iva,$total){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.detalle_nota_credito(
													id_nota_credito, id_servicio, concepto_nota_credito,
													cantidad, precio_unitario,descuento,iva,total)
											VALUES ($idNotaCredito,$idServicio,'$conceptoOrden','$cantidad',
													'$precioUnitario','$descuento','$iva','$total') RETURNING id_detalle_nota_credito;");
		return $res;
	}
	
	public function obtenerDatosNotaCredito ($conexion,$idNotaCredito){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.nota_credito
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	}
	
	public function obtenerDatosDetalleNotaCredito($conexion, $idNotaCredito){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.detalle_total_nota_credito ($idNotaCredito)");
		return $res;
	}
	
	public function actualizarObservacionSRINotaCredito($conexion,$idNotaCredito, $observacionSRI, $rutaArchivo=null, $estadoSRI=null){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito
											SET
												observacion= '$observacionSRI',
												ruta_xml = '$rutaArchivo',
												estado_sri = '$estadoSRI'
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	}
	
	public function actualizarEstadoClaveAccesoNotaCredito($conexion,$idNotaCredito, $claveAcceso, $estadoSRI){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito
											SET
												estado= '4',
												estado_sri = '$estadoSRI',
												clave_acceso = '$claveAcceso'
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	}
	
	public function actualizarDatosAutorizacionSRINotaCredito($conexion,$idNotaCredito, $estadoSRI, $numeroAutorizacion, $fechaAutorizacion, $rutaArchivo, $rutaNotaCredito){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito
											SET
												estado_sri = '$estadoSRI',
												numero_autorizacion = '$numeroAutorizacion',
												fecha_autorizacion = '$fechaAutorizacion',
												ruta_xml = '$rutaArchivo',
												ruta_nota_credito = '$rutaNotaCredito'
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	}
	
	public function cargarDocumentosPoratenderEnvioSRI($conexion, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT
												op.id_pago as id_comprobante,
												op.numero_factura as numero_comprobante,
												op.estado_sri as estado_sri,
												op.clave_acceso as clave_acceso,
												op.identificador_operador as identificador,
												c.correo as correo_electronico,
												op.fecha_facturacion,
												op.numero_establecimiento,
												'factura' as tipo
											FROM
												g_financiero.orden_pago op,
												g_financiero.clientes c
											WHERE
												op.identificador_operador = c.identificador and
												estado_sri not in ('AUTORIZADO', 'W', 'NO AUTORIZADO', 'DEVUELTA','','FINALIZADO','WA','ANULADO','R',$estado)
											
											UNION
											
											SELECT
												nc.id_nota_credito as id_comprobante,
												nc.numero_nota_credito as numero_comprobante,
												nc.estado_sri as estado_sri,
												nc.clave_acceso as clave_acceso,
												nc.identificador_operador as identificador,
												c.correo as correo_electronico,
												nc.fecha_nota_credito as fecha_facturacion,
												nc.numero_establecimiento,
												'notaCredito' as tipo
											FROM
												g_financiero.nota_credito nc,	
												g_financiero.clientes c
											WHERE
												nc.identificador_operador = c.identificador and
												estado_sri not in ('AUTORIZADO', 'W', 'NO AUTORIZADO', 'DEVUELTA','','FINALIZADO','WA','ANULADO','R',$estado)
											ORDER BY 7
											LIMIT 10;");
		return $res;
	}
	
	public function cargarDocumentosPorReprocesar($conexion){
			
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_financiero.orden_pago op
											SET
												estado_sri = 'POR ATENDER'
											WHERE
												estado_sri = 'W' and
												fecha_facturacion <= (SELECT max(op1.fecha_facturacion) - interval '5 minute' FROM g_financiero.orden_pago op1 WHERE  op1.estado_sri in ('W'));");

		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago op
											SET
												estado_sri = 'RECIBIDA'
											WHERE
												estado_sri = 'WA' and
												fecha_facturacion <= (SELECT max(op1.fecha_facturacion) - interval '5 minute' FROM g_financiero.orden_pago op1 WHERE  op1.estado_sri in ('WA'));");
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito nc
											SET
												estado_sri = 'POR ATENDER'
											WHERE
												estado_sri = 'W' and
												fecha_facturacion <= (SELECT max(nc1.fecha_facturacion) - interval '5 minute' FROM g_financiero.nota_credito nc1 WHERE  nc1.estado_sri in ('W'));");
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito nc
											SET
												estado_sri = 'RECIBIDA'
											WHERE
												estado_sri = 'WA' and
												fecha_facturacion <= (SELECT max(nc1.fecha_facturacion) - interval '5 minute' FROM g_financiero.nota_credito nc1 WHERE  nc1.estado_sri in ('WA'));");
										
				return $res;
	}
	
	public function cambiarEstadoComprobantesElectronicos($conexion, $tipo, $estado, $idComprobante){
		
		$tabla='';
		$busqueda='';
		
		switch ($tipo){
			case 'factura':
				$tabla = "g_financiero.orden_pago";
				$busqueda = "id_pago = $idComprobante";
			break;
			
			case 'notaCredito':
				$tabla = 'g_financiero.nota_credito';
				$busqueda = "id_nota_credito = $idComprobante";
			break;
			
		}
					
		$res = $conexion->ejecutarConsulta("UPDATE
												".$tabla."
											SET
												estado_sri = '$estado'
											WHERE
												".$busqueda.";");
		return $res;
	}
	
	public function cambiarEstadoMailComprobantesElectronicos($conexion, $tipo, $resultadoEnvioMail, $idComprobante){
	
		$tabla='';
		$busqueda='';
	
		switch ($tipo){
			case 'factura':
				$tabla = "g_financiero.orden_pago";
				$busqueda = "id_pago = $idComprobante";
				break;
					
			case 'notaCredito':
				$tabla = 'g_financiero.nota_credito';
				$busqueda = "id_nota_credito = $idComprobante";
				break;
					
		}
			
		$res = $conexion->ejecutarConsulta("UPDATE
												".$tabla."
											SET
												estado_mail = '$resultadoEnvioMail'
											WHERE
												".$busqueda.";");
				return $res;
	}
	
	public function actualizarXmlComprobanteFactura($conexion,$idPago,$rutaArchivoFirmado, $tipoEmision){
		
		$tipoEmision = ($tipoEmision == '1'? 'NORMAL':'CONTINGENCIA');
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												estado = 4,
												ruta_xml = '$rutaArchivoFirmado',
												tipo_emision = '$tipoEmision'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function actualizarXmlNotaCredito($conexion,$idNotaCredito,$rutaArchivoFirmado, $tipoEmision){
		
		$tipoEmision = ($tipoEmision == '1'? 'NORMAL':'CONTINGENCIA');
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito
											SET
												estado = 4,
												ruta_xml = '$rutaArchivoFirmado',
												tipo_emision = '$tipoEmision'
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	}
	
	public function actualizarComprobanteFactura($conexion,$idPago,$estado,$rutaArchivo, $claveAcceso, $identificadorFirmante){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												estado_sri = '$estado',
												comprobante_factura = '$rutaArchivo',
												clave_acceso = '$claveAcceso',
												identificador_firmante = '$identificadorFirmante'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function actualizarComprobanteNotaCredito($conexion,$idNotaCredito,$estado,$rutaArchivo,$claveAcceso){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito
											SET
												estado_sri = '$estado',
												comprobante_nota_credito = '$rutaArchivo',
												clave_acceso = '$claveAcceso'
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	}
	
	public function obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo, $idSolicitud, $tipoSolicitud, $estado = 3){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.orden_pago o
											WHERE
												id_grupo_solicitud = $idGrupo and
												--id_solicitud = '$idSolicitud' and
												tipo_solicitud = '$tipoSolicitud' and
												estado = $estado;");
		return $res;
	}
	
	public function obtenerIdOrdenPagoXtipoOperacionSinGrupo($conexion, $idSolicitud, $tipoSolicitud, $estado = 3){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.orden_pago o
											WHERE
												id_solicitud = '$idSolicitud' and
												tipo_solicitud = '$tipoSolicitud' and
												estado = $estado;");
		return $res;
	}
	
	public function obtenerOrdenPagoXEstadoImportacion($conexion, $tipoSolicitud, $estado){
			
		$res = $conexion->ejecutarConsulta("SELECT
											distinct i.id_importacion  as id_solicitud,
											i.identificador_operador,
											i.estado,
											i.tipo_certificado,
											i.pais_exportacion as pais,
											o.razon_social, o.nombre_representante, o.apellido_representante,
											i.id_vue
										FROM
											g_financiero.orden_pago op,
											g_importaciones.importaciones i,
											g_operadores.operadores o
										WHERE
											i.id_importacion = op.id_solicitud::int and
											i.identificador_operador = o.identificador and
											op.tipo_solicitud = '$tipoSolicitud' and
											op.estado_sri = '$estado' and
											op.estado = 4;");
				return $res;
	}
	
	public function filtrarRevisionFacturacion($conexion, $numeroOrdenPago, $fechaInicio, $fechaFin, $estado,$localizacion, $tipoSolicitud){
		
		$numeroOrdenPago = $numeroOrdenPago!="" ? "'" . $numeroOrdenPago . "'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . " 00:00:00'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . " 24:00:00'" : "null";
		$estado = $estado!="" ? "'" .$estado. "'" : "null";
		$localizacion = $localizacion!="" ? "'" . $localizacion . "'" : "null";
		$tipoSolicitud = $tipoSolicitud!="" ? "'" . $tipoSolicitud . "'" : "null";
					
		$res = $conexion->ejecutarConsulta("SELECT
												o.*,
												c.razon_social
											FROM
												g_financiero.verificar_estado_sri($numeroOrdenPago, $fechaInicio, $fechaFin, $estado, $localizacion, $tipoSolicitud) o,
												g_financiero.clientes c
											WHERE
												o.identificador_operador = c.identificador;");
	
				return $res;
	}
	
	public function actualizarMailCliente($conexion,$identificador, $razonSocial, $direccion, $telefono, $correo){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.clientes
											SET
												correo = '$correo',
												razon_social = '$razonSocial',
												direccion = '$direccion',
												telefono = '$telefono'
											WHERE
												identificador = '$identificador';");
		return $res;
	}
	
	public function listarDistritos($conexion){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.distritos
											WHERE
												estado = 'activo';");
		return $res;
	}
	
	public function listarDistritosAntiguo($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.distritos
											WHERE
												estado = 'inactivo';");
		return $res;
	}
	
	public function listarTodosDistritos($conexion){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.distritos;");
		return $res;
	}
	
	public function listarTodosEstablecimientos($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT distinct
												o.numero_establecimiento,
												o.ruc,
												o.provincia,
												o.oficina
											FROM
												g_financiero.oficina_recaudacion o,
												g_financiero.distritos d
											WHERE
												o.ruc = d.ruc
											ORDER BY
												o.numero_establecimiento;");
		return $res;
	}
	
	
	public function listarEstablecimientos($conexion){
		
		$res = $conexion->ejecutarConsulta("SELECT distinct
												o.numero_establecimiento, 
												o.ruc,
												o.provincia,
												o.oficina
											FROM
												g_financiero.oficina_recaudacion o,
												g_financiero.distritos d
											WHERE
												o.ruc = d.ruc
												and o.estado_recaudador='activo' and
												d.estado = 'activo' 
											ORDER BY 
												o.numero_establecimiento;");
		return $res;
	}
	
	public function listarEstablecimientosAntiguo($conexion){
	
	$res = $conexion->ejecutarConsulta("SELECT distinct
											numero_establecimiento,
											ruc,
											provincia,
											oficina
											FROM
											g_financiero.oficina_recaudacion
										WHERE
											ruc = '1768105720001';");
		return $res;
	}

	
	public function listarPtoEmision($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct o.punto_emision, 
												o.ruc, 
												o.numero_establecimiento
											FROM
												g_financiero.oficina_recaudacion o,
												g_financiero.distritos d
											WHERE
												o.ruc = d.ruc
											ORDER BY 
												o.punto_emision;");
		return $res;
	}
	
public function filtraPorPuntoRecaudacion($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $ruc, $valor){
	
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";	
		
		$tabla='';
		$busqueda='';
		
		if($valor == 0 && $localidad !='todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad !='todas')
			$columna = " and op.numero_establecimiento = '$localidad'";
	
		switch ($tipo){
			case 'factura':
				
				$tabla = 'g_financiero.orden_pago op, g_financiero.clientes c';
				$busqueda = "op.estado= 4 
							--and op.estado_sri='AUTORIZADO' 
							 and op.tipo_solicitud != 'Ingreso Caja'
							 and c.identificador = op.identificador_operador 
							 and op.fecha_facturacion >= '$fechaInicio 00:00:00' 
							 and op.fecha_facturacion <= '$fechaFin 24:00:00'
							 and ($ruc is NULL or op.ruc_institucion = $ruc)";
				break;
					
			case 'notaCredito':
				$tabla = 'g_financiero.nota_credito nc,g_financiero.clientes c';
				$busqueda = "nc.estado= 4 
							 --and nc.estado_sri='AUTORIZADO' 
							 and c.identificador = nc.identificador_operador 
							 and nc.fecha_nota_credito >= '$fechaInicio 00:00:00' 
							 and nc.fecha_nota_credito <= '$fechaFin 24:00:00' 
							 and nc.ruc_institucion $ruc";
				break;
			
			case 'ingresoCaja':
				$tabla = 'g_financiero.orden_pago op, g_financiero.clientes c';
				$busqueda = "op.estado = 4
							 and op.estado_sri='FINALIZADO' 
							 and op.tipo_solicitud = 'Ingreso Caja'
							 and c.identificador = op.identificador_operador 
							 and op.fecha_orden_pago >= '$fechaInicio 00:00:00' 
							 and op.fecha_orden_pago <= '$fechaFin 24:00:00'
							 and ($ruc is NULL or op.ruc_institucion = $ruc)";
			break;
					
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												".$tabla."
											WHERE	
												".$busqueda." ".$columna."
											ORDER BY nombre_provincia;");
		return $res;
	}
	
	public function filtrarRecaudacionPorPuntoEmision($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $ruc, $valor){
	
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$tabla='';
		$busqueda='';
		
		if($valor == 0 && $localidad !='todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad !='todas')
			$columna = " and op.numero_establecimiento = '$localidad' ";
		
		switch ($tipo){
		case 'factura':

			$tabla = 'g_financiero.orden_pago op, g_financiero.detalle_pago dp, g_financiero.clientes c, g_financiero.oficina_recaudacion r';
			$tabla1 = 'g_financiero.orden_pago op, g_financiero.detalle_pago dp, g_financiero.clientes c,g_financiero.detalle_forma_pago fp, g_financiero.oficina_recaudacion r';
			
			$busqueda = "op.estado = 4
			and op.estado_sri = 'AUTORIZADO'
			and c.identificador = op.identificador_operador
			and op.id_pago = dp.id_pago
			and op.identificador_firmante = r.identificador_firmante
            and op.ruc_institucion = r.ruc
			and op.fecha_facturacion >= '$fechaInicio 00:00:00'
			and op.fecha_facturacion <= '$fechaFin 24:00:00'
			and ($ruc is NULL or op.ruc_institucion = $ruc)";
			
			$busqueda1 = "op.estado = 4
			and op.estado_sri = 'AUTORIZADO'
			and c.identificador = op.identificador_operador
			and op.id_pago = dp.id_pago
			and op.id_pago = fp.id_pago
			and op.identificador_firmante = r.identificador_firmante
            and op.ruc_institucion = r.ruc
			and op.fecha_facturacion >= '$fechaInicio 00:00:00'
			and op.fecha_facturacion <= '$fechaFin 24:00:00'
			and ($ruc is NULL or op.ruc_institucion = $ruc)";
		
		break;
		
	
	}
	
	$res = $conexion->ejecutarConsulta("SELECT  op.id_pago, c.identificador as identificador_operador, c.razon_social,
			op.numero_establecimiento, op.punto_emision, op.total_pagar,
			op.observacion, op.localizacion, op.numero_factura, op.estado_sri, op.fecha_facturacion,
			op.numero_autorizacion,	op.fecha_autorizacion, op.nombre_provincia,
			dp.concepto_orden, dp.cantidad, dp.precio_unitario::double precision, dp.descuento,
			dp.iva, dp.total, null as nombre_banco, null as numero_transaccion,
			null as valor_depositado, null as fecha_depositada,null as provincia,null as oficina, null as numero_cuenta
			FROM
			".$tabla."
			WHERE
			".$busqueda." ".$columna."
	
			UNION
	
			SELECT
			op.id_pago, c.identificador as identificador_operador, c.razon_social,
			op.numero_establecimiento, op.punto_emision,null,
			op.observacion, op.localizacion, op.numero_factura, op.estado_sri, op.fecha_facturacion,
			op.numero_autorizacion, op.fecha_autorizacion, op.nombre_provincia,
			null, null, null, null, null, null, fp.institucion_bancaria, fp.transaccion,
			fp.valor_deposito, fp.fecha_orden_pago, r.provincia,r.oficina, fp.numero_cuenta
	
			FROM
			".$tabla1."
			WHERE
			".$busqueda1." ".$columna."
			ORDER BY nombre_provincia, id_pago,concepto_orden;");
	return $res;
	}
	
/*public function filtrarRecaudacionPorPuntoEmision($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $valor){
	
		$tabla='';
		$busqueda='';
		
		if($valor == 0 && $localidad !='todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad !='todas')
			$columna = " and op.numero_establecimiento = '$localidad' ";

		switch ($tipo){
			case 'factura':
				$tabla = 'g_financiero.orden_pago op INNER JOIN g_financiero.detalle_pago dp ON op.id_pago = dp.id_pago INNER JOIN g_financiero.clientes c 
						  ON c.identificador = op.identificador_operador INNER JOIN g_financiero.oficina_recaudacion r ON op.identificador_firmante = r.identificador_firmante 
						  LEFT JOIN g_financiero.detalle_forma_pago fp ON op.id_pago = fp.id_pago';
				$busqueda = "op.estado = 4 
							--and op.estado_sri = 'AUTORIZADO'	
							and op.fecha_facturacion >= '$fechaInicio 00:00:00' 
							and op.fecha_facturacion <= '$fechaFin 24:00:00'";
				
				break;								
		}
						
		$res = $conexion->ejecutarConsulta("SELECT  op.id_pago, c.identificador as identificador_operador, c.razon_social, 
													op.numero_establecimiento, op.punto_emision, op.total_pagar, 
													op.observacion, op.localizacion, op.numero_factura, op.estado_sri, op.fecha_facturacion, 
													op.numero_autorizacion,	op.fecha_autorizacion, op.nombre_provincia, 
													dp.concepto_orden, dp.cantidad, dp.precio_unitario::double precision, dp.descuento, 
													dp.iva, dp.total, null as nombre_banco, null as numero_transaccion, 
													null as valor_depositado, null as fecha_depositada,r.provincia,r.oficina, fp.numero_cuenta
											FROM
													".$tabla."	
											WHERE
													".$busqueda." ".$columna."
												
											UNION

											SELECT 
													op.id_pago, c.identificador as identificador_operador, c.razon_social, 
													op.numero_establecimiento, op.punto_emision,null,
													op.observacion, op.localizacion, op.numero_factura, op.estado_sri, op.fecha_facturacion, 
													op.numero_autorizacion, op.fecha_autorizacion, op.nombre_provincia, 
													null, null, null, null, null, null, fp.institucion_bancaria, fp.transaccion, 
													fp.valor_deposito, fp.fecha_orden_pago, r.provincia,r.oficina, fp.numero_cuenta
												
											FROM
													".$tabla."
											WHERE
													".$busqueda." ".$columna."
											ORDER BY nombre_provincia, id_pago,concepto_orden;");
			return $res;
	}*/
	
public function filtrarRecaudacionPorItem($conexion, $tipo, $fechaInicio, $fechaFin, $provincia, $ruc){

		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
	
		$tabla='';
		$busqueda='';
		$columna = '';
		
		if($provincia != 'todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$provincia') ";
		
		switch ($tipo){
			case 'factura':
				$tabla = 'g_financiero.orden_pago op, g_financiero.detalle_pago dp,
						  g_financiero.servicios s, g_financiero.clientes c, g_estructura.area a';
				$busqueda = "op.id_pago = dp.id_pago 
							 and op.estado = 4 
							 and op.estado_sri = 'AUTORIZADO' 
							 and dp.id_servicio = s.id_servicio  
							 and s.id_area = a.id_area 
							 and c.identificador = op.identificador_operador 
							 and op.fecha_facturacion >= '$fechaInicio 00:00:00'
							 and op.fecha_facturacion <= '$fechaFin 24:00:00' 
							 and ($ruc is NULL or op.ruc_institucion = $ruc)
							 ".$columna."";
				break;
					
				
					
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												op.id_pago, op.identificador_operador,
												c.razon_social, op.numero_factura, op.fecha_facturacion::DATE,
												op.estado_sri, op.nombre_provincia,op.numero_establecimiento,
												op.punto_emision, a.nombre,
												s.codigo, dp.concepto_orden,
												dp.cantidad, dp.precio_unitario,
												dp.descuento, dp.iva, dp.total
											FROM   
												".$tabla."
											WHERE  
												".$busqueda."
											ORDER BY 
												op.nombre_provincia, op.id_pago;");
		return $res;
		
		
	}
		
	
	public function filtrarRecaudacionPorBanco($conexion, $tipo, $fechaInicio, $fechaFin, $provincia, $ruc){
		
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
	
		$tabla='';
		$busqueda='';
		$columna = '';
		
		if($provincia != 'todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$provincia') ";
	
		switch ($tipo){
			case 'factura':
					$tabla = 'g_financiero.orden_pago op, g_financiero.detalle_forma_pago fp, g_financiero.clientes c';
					$busqueda = "op.id_pago = fp.id_pago
								and c.identificador = op.identificador_operador
								and op.estado = 4
								and op.estado_sri = 'AUTORIZADO' 
								and op.fecha_facturacion >= '$fechaInicio 00:00:00'
								and op.fecha_facturacion <= '$fechaFin 24:00:00'
								and ($ruc is NULL or op.ruc_institucion = $ruc)
								".$columna."";
					break;
							
								
		}

		$res = $conexion->ejecutarConsulta("SELECT 
												op.id_pago,	op.numero_establecimiento,
												op.punto_emision, op.nombre_provincia, op.numero_factura, 
												op.identificador_operador, c.razon_social,  
												fp.institucion_bancaria, fp.transaccion,
												fp.valor_deposito, fp.fecha_orden_pago
											FROM   
												".$tabla."
											WHERE  
												".$busqueda."
											ORDER BY 
												op.nombre_provincia, op.id_pago");
				return $res;
	
	
	}
	
public function filtrarRecaudacionPorFactura($conexion, $tipo, $fechaInicio, $fechaFin, $provincia, $ruc){
	
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
	
		$tabla='';
		$busqueda='';
		$columna = '';
		
		if($provincia != 'todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$provincia') ";
	
		switch ($tipo){
			case 'factura':
				$tabla = 'g_financiero.orden_pago op, g_financiero.detalle_pago dp, g_financiero.servicios s, g_financiero.clientes c, g_estructura.area a';
				$busqueda = "op.id_pago = dp.id_pago
							and dp.id_servicio = s.id_servicio
							and s.id_area = a.id_area
							and c.identificador = op.identificador_operador
							and op.estado = 4 
							and op.estado_sri = 'AUTORIZADO'
							and op.fecha_facturacion >= '$fechaInicio 00:00:00'
							and op.fecha_facturacion <= '$fechaFin 24:00:00'
							and ($ruc is NULL or op.ruc_institucion = $ruc)
							".$columna."";
			break;
					
								
		}

		$res = $conexion->ejecutarConsulta("SELECT
												dp.id_pago, op.identificador_operador, c.razon_social,
												op.numero_factura, op.estado_sri, a.nombre,
												s.codigo, dp.concepto_orden, dp.cantidad,
												dp.precio_unitario, dp.descuento, dp.iva, dp.total
											FROM
												".$tabla."
											WHERE
												".$busqueda."
											ORDER BY
												a.nombre, op.id_pago");
		return $res;
	
	
	}
	
	public function filtrarRecaudacionPorPartida($conexion, $tipo, $fechaInicio, $fechaFin, $provincia, $ruc){
		
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$tabla='';
		$busqueda='';
		$columna = '';
		
		if($provincia != 'todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$provincia') ";
	
		switch ($tipo){
			case 'factura':
				$tabla = 'g_financiero.detalle_pago dp, g_financiero.servicios s, g_financiero.orden_pago op';
				$busqueda = "dp.id_servicio = s.id_servicio
							and op.id_pago = dp.id_pago
							and op.estado = 4
							and op.estado_sri = 'AUTORIZADO'
							and op.fecha_facturacion >= '$fechaInicio 00:00:00'
							and op.fecha_facturacion <= '$fechaFin 24:00:00'
							and ($ruc is NULL or op.ruc_institucion = $ruc)
							".$columna."";
				break;
					
								
		}

	
		$res = $conexion->ejecutarConsulta("SELECT s.partida_presupuestaria, 
												COUNT(op.numero_factura) as cantidad,
												SUM(dp.cantidad*dp.precio_unitario)::decimal(12,2) as subtotal,
												SUM(dp.iva) iva,
												SUM(dp.descuento) descuento,
												SUM(dp.total) total,
												to_char(op.fecha_facturacion,'yyyy-mm-dd') as fecha
											FROM
												".$tabla."
											WHERE
												".$busqueda."
											GROUP BY
												s.partida_presupuestaria,fecha");
		return $res;
	
	
	}
	
public function filtrarNotaCreditoPorPuntoEmision($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $ruc, $valor){
	
	$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
	
		$tabla='';
		$busqueda='';
	
		if($valor == 0 && $localidad != 'todas')
			$columna = " and n.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad != 'todas')
			$columna = " and n.numero_establecimiento = '$localidad'";
	
		switch ($tipo){
			case 'notaCredito':
				$tabla = 'g_financiero.nota_credito n, g_financiero.orden_pago op, g_financiero.clientes c, g_financiero.oficina_recaudacion r';
				$busqueda = "n.id_pago = op.id_pago
							 and n.estado = 4 
							 and n.estado_sri = 'AUTORIZADO' 
							 and n.identificador_usuario = r.identificador_firmante
							 and c.identificador = n.identificador_operador 
                             and op.ruc_institucion = r.ruc
							 and n.fecha_nota_credito >= '$fechaInicio 00:00:00'
							 and n.fecha_nota_credito <= '$fechaFin 24:00:00'
							 and ($ruc is NULL or n.ruc_institucion = $ruc)";
	
				break;
		}

		$res = $conexion->ejecutarConsulta("SELECT  n.id_nota_credito,n.id_pago, n.identificador_operador,
													c.razon_social, n.numero_nota_credito,
													n.fecha_nota_credito, n.total_pagar,
													n.motivo, n.localizacion,op.numero_factura,
													n.fecha_facturacion, n.numero_autorizacion,
													n.identificador_usuario, n.numero_establecimiento,
													n.punto_emision, n.nombre_provincia, r.provincia, r.oficina
											FROM
												".$tabla."
											WHERE
												".$busqueda." ".$columna.";");
		return $res;
	}
	
	public function actualizarIngresoCaja($conexion,$idPago,$rutaArchivo, $identificadorFirmante){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												estado = 4,
												estado_sri = 'FINALIZADO',
												comprobante_factura = '$rutaArchivo',
												factura = '$rutaArchivo',
												identificador_firmante = '$identificadorFirmante'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
public function listarRecaudacionExcedentes($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $valor, $cliente, $ruc, $tipoSaldo='saldoAgr'){
	
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
	
		$tabla='';
		$busqueda='';
		$columna = '';
		$buscaCliente = '';
		$limite = '';
	
		if($valor == 0 && $localidad !='todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad !='todas')
			$columna = " and op.numero_establecimiento = '$localidad' ";
		
		if($cliente != ''){
		  $buscaCliente = "and   op.identificador_operador = '$cliente'";
		  $limite = "LIMIT 1";
		}
	
		switch ($tipo){
			case 'factura':
				$tabla = 'g_financiero.orden_pago op, g_financiero.clientes c, g_financiero.saldos s';
				$busqueda = " op.identificador_operador = c.identificador
							and op.id_pago = s.id_pago
							and op.fecha_facturacion >= '$fechaInicio 00:00:00'
							and op.fecha_facturacion <= '$fechaFin 24:00:00'
							and tipo_saldo = '$tipoSaldo'
							and ($ruc is NULL or op.ruc_institucion = $ruc)";
			break;
		}
	   
	    $res = $conexion->ejecutarConsulta("SELECT 
												op.id_pago, 
												op.identificador_operador, 
												c.razon_social, 
												op.fecha_facturacion, 
												s.saldo_disponible , 
												op.numero_factura
											FROM
												".$tabla."
											WHERE
												".$busqueda." ".$columna." ".$buscaCliente."
											ORDER BY identificador_operador,fecha_facturacion desc
												".$limite.";");
			return $res;
	}
	
	/*public function filtroRecaudacionExcedentes($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $valor){
	
		$tabla='';
		$busqueda='';
		$columna = '';
	
		if($valor == 0 && $localidad !='todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad !='todas')
			$columna = " and op.numero_establecimiento = '$localidad' ";
	
		switch ($tipo){
			case 'factura':
				$tabla = 'g_financiero.orden_pago op, g_financiero.clientes c, g_financiero.detalle_forma_pago dp, g_financiero.saldos s';
				$busqueda = "c.identificador = op.identificador_operador
				and op.id_pago = s.id_pago
				and op.fecha_facturacion >= '$fechaInicio 00:00:00'
				and op.fecha_facturacion <= '$fechaFin 24:00:00'";
				$opcion = "op.id_pago = dp.id_pago and";
				break;
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
				op.id_pago, op.fecha_facturacion,
													op.numero_factura, op.identificador_operador,
													c.razon_social, op.total_pagar,
													op.numero_establecimiento, op.punto_emision,
													dp.institucion_bancaria, dp.transaccion,
													dp.valor_deposito,  null as valor_ingreso, null as valor_egreso,
													null as saldo_disponible, null as id_saldo
											FROM
													".$tabla."
											WHERE
				".$opcion." ".$busqueda." ".$columna."
	
				UNION
	
				SELECT
				op.id_pago, op.fecha_facturacion,
													op.numero_factura, op.identificador_operador,
													c.razon_social, op.total_pagar,
													op.numero_establecimiento, op.punto_emision,
													null, null,
													null, s.valor_ingreso, s.valor_egreso,
													s.saldo_disponible, id_saldo
	
											FROM
													".$tabla."
											WHERE
													".$busqueda." ".$columna."
											ORDER BY  identificador_operador, numero_factura, institucion_bancaria;");
												return $res;
	
	
	}*/
	
	public function filtrarIngresoCajaPorPuntoEmision($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $ruc, $valor){
	
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$tabla='';
		$busqueda='';
	
		if($valor == 0 && $localidad !='todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad !='todas')
			$columna = " and op.numero_establecimiento = '$localidad' ";
	
		switch ($tipo){
			case 'ingresoCaja':
				 $tabla = 'g_financiero.orden_pago op, g_financiero.detalle_pago dp, g_financiero.clientes c,g_financiero.detalle_forma_pago fp, g_financiero.oficina_recaudacion r';
				 $busqueda = "op.estado = 4
							 and op.estado_sri = 'FINALIZADO'
							 and op.tipo_solicitud = 'Ingreso Caja'
							 and op.id_pago = dp.id_pago
							 and op.id_pago = fp.id_pago 
							 and c.identificador = op.identificador_operador
							 and op.identificador_firmante = r.identificador_firmante
                             and op.ruc_institucion = r.ruc
							 and op.fecha_facturacion >= '$fechaInicio 00:00:00'
							 and op.fecha_facturacion <= '$fechaFin 24:00:00'
				 			 and ($ruc is NULL or op.ruc_institucion = $ruc)";
				break;
		}
		
		$res = $conexion->ejecutarConsulta("SELECT  
												op.id_pago, c.identificador as identificador_operador, c.razon_social,
												op.numero_establecimiento, op.punto_emision, op.total_pagar,
												op.observacion, op.localizacion, op.numero_factura, op.estado_sri,op.fecha_facturacion,
												op.nombre_provincia,
												dp.concepto_orden, dp.cantidad, dp.precio_unitario::double precision, dp.descuento,
												dp.iva, dp.total, null as nombre_banco, null as numero_transaccion,
												null as valor_depositado, null as fecha_depositada,r.provincia,r.oficina
											FROM
												".$tabla."
											WHERE
												".$busqueda." ".$columna."
									
											UNION
		
											SELECT
												op.id_pago, c.identificador as identificador_operador, c.razon_social,
												op.numero_establecimiento, op.punto_emision, op.total_pagar,
												op.observacion, op.localizacion, op.numero_factura, op.estado_sri,op.fecha_facturacion,
												op.nombre_provincia,
												null, null, null, null, null, null, fp.institucion_bancaria, fp.transaccion,
												fp.valor_deposito, fp.fecha_orden_pago,r.provincia,r.oficina
									
											FROM
												".$tabla."
											WHERE
												".$busqueda." ".$columna."
											ORDER BY nombre_provincia, id_pago,concepto_orden;");
		return $res;
	}	
	
	public function generarNumeroFacturaIngresoCaja($conexion,$rucInstitucion, $numeroEstablecimineto, $puntoEmision){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_factura)::integer +1 as numero
											FROM
												g_financiero.orden_pago
											WHERE
												ruc_institucion = '$rucInstitucion' and
												numero_establecimiento = '$numeroEstablecimineto' and
												punto_emision = '$puntoEmision' and
												tipo_solicitud = 'Ingreso Caja';");
				return $res;
	}
	
	public function finalizarOrdenIngresoCaja($conexion,$idPago,$valorDepositado, $rucInstitucion, $numeroFactura, $numeroEstablecimiento, $puntoEmision){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												valor_deposito='$valorDepositado',
												fecha_facturacion = now(),
												ruc_institucion = '$rucInstitucion',
												numero_factura = '$numeroFactura',
												numero_establecimiento = '$numeroEstablecimiento',
												punto_emision = '$puntoEmision',
												tipo_emision = 'NORMAL'
											WHERE
												id_pago= $idPago;");
		return $res;
	
	}
	
	public function listaFormasPago($conexion, $idPago ){
					
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM  
												g_financiero.detalle_forma_pago dp
											WHERE
												dp.id_pago = $idPago					
											ORDER BY 
												dp.fecha_orden_pago asc;");
				return $res;
	}
	
	/*public function listaOrdenPagoSaldo($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $valor, $cliente){
	
		$tabla='';
		$busqueda='';
		$columna = '';
		$buscaCliente = '';
		$limite = '';
	
		if($valor == 0 && $localidad !='todas')
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		else if($localidad !='todas')
			$columna = " and op.numero_establecimiento = '$localidad' ";
	
		if($cliente != '')
		{
			$buscaCliente = "and   op.identificador_operador = '$cliente'";
			$limite = "LIMIT 1";
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												op.id_pago,
												op.identificador_operador,
												c.razon_social,
												op.numero_establecimiento,
												op.punto_emision,
												op.numero_factura,
												op.fecha_facturacion,
												op.total_pagar,
												s.saldo_disponible,
												s.fecha_deposito
											FROM 
												g_financiero.orden_pago op, 
												g_financiero.clientes c, 
												g_financiero.saldos s
											WHERE
												op.identificador_operador = c.identificador
												and op.id_pago = s.id_pago
												and op.fecha_facturacion >= '$fechaInicio 00:00:00'
												and op.fecha_facturacion <= '$fechaFin 24:00:00'
												".$columna." ".$buscaCliente."
											ORDER BY 
												identificador_operador,fecha_facturacion desc
												".$limite.";");
				return $res;
	
	}*/
	
	public function listaOrdenPagoSaldo($conexion, $tipo, $fechaInicio, $fechaFin, $localidad, $valor, $cliente, $ruc, $tipoSaldo = 'saldoAgr'){
	
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$tabla='';
		$columna = '';
		$busqueda='';
		$buscaCliente = '';
	
		if($valor == 0 && $localidad !='todas'){
			$columna = " and op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= '$localidad') ";
		}else if($localidad !='todas'){
			$columna = " and op.numero_establecimiento = '$localidad' ";
		}
	
		if($cliente != ''){
			$buscaCliente = "and op.identificador_operador = '$cliente'";
		}
	
		switch ($tipo){
			case 'factura':
				$tabla = 'g_financiero.orden_pago op, g_financiero.clientes c, g_financiero.detalle_forma_pago dp, g_financiero.saldos s';
				$busqueda = "op.id_pago = dp.id_pago
				and op.id_pago = s.id_pago
				and c.identificador = op.identificador_operador
				and op.fecha_facturacion >= '$fechaInicio 00:00:00'
				and op.fecha_facturacion <= '$fechaFin 24:00:00'
				and tipo_saldo = '$tipoSaldo'
				and ($ruc is NULL or op.ruc_institucion = $ruc)";
				break;
		}
			
				$res = $conexion->ejecutarConsulta("SELECT
						op.id_pago,
												op.fecha_facturacion, op.numero_factura, op.identificador_operador, c.razon_social, op.total_pagar,
												op.numero_establecimiento, op.punto_emision, dp.institucion_bancaria, dp.transaccion, dp.valor_deposito,
												null as valor_ingreso, null as valor_egreso, null as saldo_disponible, null as id_saldo, dp.id_detalle_pago
											FROM
												".$tabla."
											WHERE
						".$busqueda." ".$columna." ".$buscaCliente."
			
						UNION
							
						SELECT
						op.id_pago,
												op.fecha_facturacion, op.numero_factura, op.identificador_operador, c.razon_social, op.total_pagar,
												op.numero_establecimiento, op.punto_emision, null, null, null, s.valor_ingreso, s.valor_egreso,
												s.saldo_disponible, id_saldo, null
											FROM
												".$tabla."
											WHERE
												".$busqueda." ".$columna." ".$buscaCliente."
											ORDER BY  identificador_operador, numero_factura, institucion_bancaria;");
					return $res;
	
	}

	public function obtenerNumeroFactura($conexion, $idPago){
	
		$res = $conexion->ejecutarConsulta("SELECT
													numero_factura
											FROM
													g_financiero.orden_pago
											WHERE
													id_pago = $idPago;");
		return $res;
	}
	
	public function obtenerValorIva($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												iva
											FROM
												g_financiero.distritos;");
		return $res;
	}
	
	public function obtenerFormaPagoNotaCredito($conexion, $idPago){
	
		$res = $conexion->ejecutarConsulta("SELECT
												dfp.transaccion, dfp.valor_deposito, dfp.fecha_orden_pago, dfp.institucion_bancaria, dfp.numero_cuenta
											FROM
												g_financiero.detalle_forma_pago dfp,
												g_financiero.detalle_forma_pago dfp1,
												g_financiero.nota_credito nc
											WHERE
												dfp.id_pago = nc.id_pago and
												nc.id_nota_credito = dfp1.id_nota_credito and
												dfp1.id_pago = $idPago");
		return $res;
	}
	
	public function filtrarRecaudacionXEstablecimientoXItem ($conexion, $establecimiento, $area, $codigoItem, $fechaInicio, $fechaFin, $provincia, $ruc, $comprobante = 'factura'){
	
		$busqueda = '';
		$establecimiento = $establecimiento!='todos' ? $establecimiento==''?"NULL":"'" . $establecimiento . "'" : "NULL";
		$area = $area!="todos" ? "'" . $area . "'"  : "NULL";
		$codigoItem = $codigoItem!="todos" ? "'" . $codigoItem . "'"  : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . " 00:00:00'"  : "NULL";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . " 24:00:00'"  : "NULL";
		$provincia = $provincia!='todos' ? "'" . $provincia . "'" : "NULL"; 
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		if($comprobante == 'factura'){
			$busqueda = "and op.estado_sri = 'AUTORIZADO'";
		}else{
			$busqueda = "and op.estado_sri = 'FINALIZADO' and tipo_proceso= 'comprobanteFactura'";
		}		
		
		$res = $conexion->ejecutarConsulta("SELECT
												op.numero_establecimiento,
												op.punto_emision,
												sum(dp.cantidad) as cantidad,
												a.nombre,
												dp.precio_unitario,
												sum(dp.descuento) as descuento,
												sum(dp.iva) as iva,
												sum(dp.subsidio) as subsidio,
												sum(dp.total) as total,
												s.codigo,
												dp.concepto_orden
											FROM
												g_financiero.orden_pago op, g_financiero.detalle_pago dp,
												g_financiero.servicios s, g_estructura.area a
											WHERE
												op.id_pago = dp.id_pago
												and op.estado = 4
												and dp.id_servicio = s.id_servicio
												and s.id_area = a.id_area
												and  op.numero_establecimiento in (SELECT numero_establecimiento from g_financiero.oficina_recaudacion where provincia= $provincia and estado_recaudador='activo')
												and ($area is NULL or s.id_area = $area)
												and ($codigoItem is NULL or dp.id_servicio = $codigoItem)
												and ($fechaInicio is NULL or fecha_facturacion >= $fechaInicio)
												and ($fechaFin is NULL or fecha_facturacion <= $fechaFin)
												and ($establecimiento is NULL or op.numero_establecimiento = $establecimiento)
												and ($ruc is NULL or op.ruc_institucion = $ruc)
												$busqueda
											GROUP BY
												op.nombre_provincia,
												op.numero_establecimiento,
												op.punto_emision,
												a.nombre,
												dp.precio_unitario,
												s.codigo,
												dp.concepto_orden
											ORDER BY
												op.nombre_provincia, 
												numero_establecimiento, 
												codigo");
				return $res;
	}
	
	public function obtenerAreasServicios($conexion){
	
	$res = $conexion->ejecutarConsulta("SELECT
											id_servicio, id_area, concepto
										FROM
											g_financiero.servicios
										WHERE
											id_categoria_servicio = 1
											and estado = 'activo'
											and id_area != 'GENER'
											order by 1");
		return $res;
	}
	
	public function actualizarTipoProcesoOrdenPago($conexion, $idPago, $tipoProceso){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												tipo_proceso = '$tipoProceso'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function generarNumeroComprobanteFactura($conexion,$rucInstitucion, $numeroEstablecimineto, $puntoEmision){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(numero_factura)::integer +1 as numero
											FROM
												g_financiero.orden_pago
											WHERE
												ruc_institucion = '$rucInstitucion' and
												numero_establecimiento = '$numeroEstablecimineto' and
												punto_emision = '$puntoEmision' and
												tipo_solicitud = 'recargaSaldo';");
				return $res;
	}
	
	public function actualizarComprobanteFacturaVue($conexion,$idPago,$rutaArchivo, $identificadorFirmante){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												estado = 4,
												estado_sri = 'FINALIZADO',
												comprobante_factura = '$rutaArchivo',
												factura = '$rutaArchivo',
												identificador_firmante = '$identificadorFirmante',
												tipo_emision = 'NORMAL'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function actualizarPorcentajeIvaOrdenPago($conexion,$idPago, $porcentajeIva){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												porcentaje_iva = '$porcentajeIva'
											WHERE
												id_pago = $idPago;");
		return $res;
		
	}
	
	public function actualizarPorcentajeIvaNotaCredito($conexion,$idNotaCredito, $porcentajeIva){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.nota_credito
											SET
												porcentaje_nota_iva = '$porcentajeIva'
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	
	}
	
	public function obtenerFacturaPorOperador($conexion, $identificadorOperador, $numeroFactura, $fechaInicio, $fechaFin, $tipoSolicitud, $numeroSolicitud, $numeroOrdenVue, $numeroOrdenGuia){
	
		$numeroSolicitud = $numeroSolicitud!='' ? "'" . $numeroSolicitud . "'" : "NULL";		
		$numeroFactura = $numeroFactura!='' ? "'" . $numeroFactura . "'" : "NULL";
		$numeroOrdenVue = $numeroOrdenVue!='' ? "'" . $numeroOrdenVue . "'" : "NULL";
		$numeroOrdenGuia = $numeroOrdenGuia!='' ? "'" . $numeroOrdenGuia . "'" : "NULL";		
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . " 00:00:00'"  : "NULL";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . " 24:00:00'"  : "NULL";
					
		switch ($tipoSolicitud){
			
			case "Importación": 
				$tabla = 'g_financiero.orden_pago op,
							g_importaciones.importaciones i';
				$busqueda = 'and i.id_importacion = op.id_solicitud::int
							and	op.tipo_solicitud = '."'".$tipoSolicitud."'".'
							and ('.$numeroSolicitud.' is NULL or i.id_vue = '.$numeroSolicitud.') 
							and ('.$numeroOrdenVue.' is NULL or op.numero_orden_vue = '.$numeroOrdenVue.')';
				break;
			case "Fitosanitario":
				$tabla = 'g_financiero.orden_pago op,
						g_fito_exportacion.fito_exportaciones e';
				$busqueda = 'and e.id_fito_exportacion = op.id_solicitud::int
							and	op.tipo_solicitud = '."'".$tipoSolicitud."'".'
							and ('.$numeroSolicitud.' is NULL or e.id_vue = '.$numeroSolicitud.') 
							and ('.$numeroOrdenVue.' is NULL or op.numero_orden_vue = '.$numeroOrdenVue.')';
				break;
				
			case "Otros":
				$tabla = 'g_financiero.orden_pago op';
				$busqueda = 'and ('.$numeroFactura.' is NULL or op.numero_factura = '.$numeroFactura.') 
							and ('.$numeroOrdenGuia.' is NULL or op.numero_solicitud = '.$numeroOrdenGuia.')';
				break;
				
		}
		
		$res = $conexion->ejecutarConsulta("SELECT
												op.id_pago, op.identificador_operador, op.numero_solicitud, op.numero_establecimiento, op.punto_emision, op.numero_factura, op.total_pagar, op.estado_sri,
												to_char(op.fecha_facturacion,'DD/MM/YYYY') fecha_facturacion
											FROM
												$tabla
											WHERE
												op.identificador_operador='$identificadorOperador'
												and op.estado_sri IN  ('AUTORIZADO','NO AUTORIZADO')
												and ($fechaInicio is NULL or op.fecha_facturacion >=$fechaInicio)
												and ($fechaFin is NULL or op.fecha_facturacion <=$fechaFin ) $busqueda");
		return $res;
	}
	
	public function obtenerOrdenPagoPorNumeroOrdenVue($conexion, $numeroOrdenVue){
		
		$consulta = "SELECT
						*
					FROM
						g_financiero.orden_pago op,
						g_financiero.clientes c
					WHERE
						op.identificador_operador = c.identificador and 
						numero_orden_vue = '$numeroOrdenVue'
						and estado = 4 and 
						tipo_solicitud IN ('Importación', 'Fitosanitario','FitosanitarioExportacion','saldoVue') and
						tipo_proceso ='factura';";
	
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	
	}
	
	public function guardarHistorialFinanciero($conexion, $idPago, $descripcion, $identificador){
		
		$consulta = "INSERT INTO g_financiero.historial_financiero(id_pago, descripcion, identificador) 
															VALUES ($idPago, '$descripcion', '$identificador');";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerRegistrosDocumento($conexion){
	    
	    $consulta = "SELECT id_pago, 
                        to_char(fecha_orden_pago,'yyyy') ||'/'|| to_char(fecha_orden_pago,'mm') ||'/'|| to_char(fecha_orden_pago, 'dd') as fecha_orden_pago,
                        to_char(fecha_facturacion,'yyyy') ||'/'|| to_char(fecha_facturacion, 'mm')||'/'|| to_char(fecha_facturacion,'dd') as fecha_facturacion, 
                        orden_pago, factura, clave_acceso, comprobante_factura, tipo_solicitud
                    FROM 
                        g_financiero.orden_pago 
                    WHERE
                        actuafin is null
                    UNION
                        SELECT id_nota_credito,
                        to_char(fecha_nota_credito,'yyyy') ||'/'|| to_char(fecha_nota_credito,'mm') ||'/'|| to_char(fecha_nota_credito, 'dd') as fecha_orden_pago,
                        to_char(fecha_nota_credito,'yyyy') ||'/'|| to_char(fecha_nota_credito,'mm') ||'/'|| to_char(fecha_nota_credito, 'dd') as fecha_facturacion,
                        comprobante_nota_credito, ruta_nota_credito, clave_acceso, comprobante_nota_credito, 'Nota de credito'
                    FROM
                       g_financiero.nota_credito
                    WHERE
                        actuafin is null";	    
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function actualizarRutaCertificados($conexion, $tabla, $campo, $ruta, $idtabla, $valor){
	    
	    $consulta = "UPDATE $tabla SET $campo = '$ruta' WHERE $idtabla = '$valor'";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	}
	
	public function listarTodosEstablecimientosConsumoComprobante($conexion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT distinct
												o.numero_establecimiento,
												o.ruc,
												o.provincia
											FROM
												g_financiero.oficina_recaudacion o,
												g_financiero.distritos d
											WHERE
												o.ruc = d.ruc
											ORDER BY
												o.numero_establecimiento;");
	    return $res;
	    
	}
	
	public function verificarSecuencialFacturaXNumeroEstablecimientoXRuc ($conexion, $idPago, $numeroEstablecimiento, $puntoEmision, $rucInstitucion){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
                                                *
                                            FROM
                                                g_financiero.orden_pago
                                            WHERE
                                                id_pago = $idPago
                                                and numero_establecimiento = '$numeroEstablecimiento'
                                                and punto_emision = '$puntoEmision'
                                                and numero_factura::integer = (SELECT 
                                                                                    max(numero_factura)::integer+1 
                                                                               FROM 
                                                                                    g_financiero.orden_pago  
                                                                                WHERE 
                                                                                    ruc_institucion = '$rucInstitucion'
                                                                                    and numero_establecimiento='$numeroEstablecimiento' 
                                                                                    and punto_emision = '$puntoEmision'
                                                                                    and tipo_solicitud != 'Ingreso Caja'
                                                                                    and id_pago not in ($idPago))
                                                and ruc_institucion = '$rucInstitucion'
                                                ;");
	    return $res;
	    
	}

	public function verificarSecuencialFacturaXNumeroEstablecimientoXRucXNumeroSolicitud ($conexion, $idPago, $numeroEstablecimiento, $puntoEmision, $rucInstitucion, $numeroSolicitud){
	    
	    $res = $conexion->ejecutarConsulta("SELECT
                                                *
                                            FROM
                                                g_financiero.orden_pago
                                            WHERE
                                                id_pago = $idPago
                                                and $numeroSolicitud::integer = (SELECT
                                                                                    max(numero_factura)::integer+1
                                                                               FROM
                                                                                    g_financiero.orden_pago
                                                                                WHERE
                                                                                    ruc_institucion = '$rucInstitucion'
                                                                                    and numero_establecimiento='$numeroEstablecimiento'
                                                                                    and punto_emision = '$puntoEmision'
                                                                                    and tipo_solicitud != 'Ingreso Caja'
                                                                                    and id_pago not in ($idPago));");
	    return $res;
	    
	}
	
	/*public	function libxml_display_errors() {
		$errors = libxml_get_errors();
		foreach ($errors as $error) {
			print $this->libxml_display_error($error);
		}
	}
	
	public	function libxml_display_error($error){
		$return = "<br/>\n";
		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$return .= "<b>Warning $error->code</b>: ";
				break;
			case LIBXML_ERR_ERROR:
				$return .= "<b>Error $error->code</b>: ";
				break;
			case LIBXML_ERR_FATAL:
				$return .= "<b>Fatal Error $error->code</b>: ";
				break;
		}
		$return .= trim($error->message);
		if ($error->file) {
			$return .=    " in <b>$error->file</b>";
		}
		$return .= " on line <b>$error->line</b>\n";
	
		return $return;
	}*/
	
	public function filtrarRecaudacionCuadreCaja($conexion, $fechaInicio, $fechaFin, $localidad, $ruc){
		
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$consulta = "SELECT
						op.id_pago,
						c.identificador as identificador_operador,
						c.razon_social,
						op.nombre_provincia,
						op.localizacion,
						null as oficina,
						op.numero_establecimiento || '-' || op.punto_emision || '-' || op.numero_factura as numero_factura,
						op.estado_sri,
						op.numero_autorizacion,
						op.observacion,
						TO_CHAR(op.fecha_facturacion, 'yyyy-mm-dd') as fecha_facturacion,
						TO_CHAR(op.fecha_autorizacion, 'yyyy-mm-dd')as fecha_autorizacion,
						s.codigo,
						dp.concepto_orden,
						dp.cantidad,
						dp.precio_unitario::double precision,
						dp.descuento,
						dp.total,
						dp.iva,
						op.total_pagar,
						null as nombre_banco,
						null as numero_cuenta_banco,
						null as numero_transaccion,
						null as valor_depositado,
						null as fecha_deposito,
						sa.valor_ingreso as excedente
					FROM
						g_financiero.orden_pago op
						INNER JOIN g_financiero.detalle_pago dp ON dp.id_pago = op.id_pago
						INNER JOIN g_financiero.clientes c ON c.identificador = op.identificador_operador
						INNER JOIN g_financiero.oficina_recaudacion r ON r.identificador_firmante = op.identificador_firmante and r.ruc = op.ruc_institucion
						INNER JOIN g_financiero.servicios s ON s.id_servicio = dp.id_servicio
						LEFT OUTER JOIN g_financiero.saldos sa ON sa.id_pago = op.id_pago 
					WHERE
						CASE WHEN op.tipo_solicitud = 'Ingreso Caja' THEN op.estado = 4 and op.estado_sri = 'FINALIZADO'
						ELSE op.estado = 4 and op.estado_sri = 'AUTORIZADO' END			
						and op.fecha_facturacion >= '$fechaInicio 00:00:00'
						and op.fecha_facturacion <= '$fechaFin 24:00:00'
						and ($ruc is NULL or op.ruc_institucion = $ruc)
						and op.numero_establecimiento = '$localidad'
					UNION
					SELECT
						op.id_pago,
						c.identificador as identificador_operador,
						c.razon_social,
						op.nombre_provincia,
						op.localizacion,
						r.oficina,
						op.numero_establecimiento || '-' || op.punto_emision || '-' || op.numero_factura as numero_factura,
						op.estado_sri,
						op.numero_autorizacion,
						op.observacion,
						TO_CHAR(op.fecha_facturacion, 'yyyy-mm-dd') as fecha_facturacion,
						TO_CHAR(op.fecha_autorizacion, 'yyyy-mm-dd') as fecha_autorizacion,
						null,
						null,
						null,
						null,
						null,
						null,
						null,
						null,
						fp.institucion_bancaria,
						cb.numero_cuenta as numero_cuenta_banco,
						fp.transaccion,
						fp.valor_deposito,
						TO_CHAR(fp.fecha_orden_pago, 'yyyy-mm-dd'),
						null as excedente
					FROM
						g_financiero.orden_pago op
						INNER JOIN g_financiero.detalle_pago dp ON dp.id_pago = op.id_pago
						INNER JOIN g_financiero.clientes c ON c.identificador = op.identificador_operador
						INNER JOIN g_financiero.detalle_forma_pago fp ON fp.id_pago = op.id_pago
						INNER JOIN g_financiero.oficina_recaudacion r ON r.identificador_firmante = op.identificador_firmante and r.ruc = op.ruc_institucion
						INNER JOIN g_financiero.servicios s ON s.id_servicio = dp.id_servicio
						LEFT JOIN g_catalogos.cuentas_bancarias cb ON cb.id_cuenta_bancaria = fp.id_cuenta_bancaria
					WHERE
						CASE WHEN op.tipo_solicitud = 'Ingreso Caja' THEN op.estado = 4 and op.estado_sri = 'FINALIZADO'
						ELSE op.estado = 4 and op.estado_sri = 'AUTORIZADO' END			
						and op.fecha_facturacion >= '$fechaInicio 00:00:00'
						and op.fecha_facturacion <= '$fechaFin 24:00:00'
						and ($ruc is NULL or op.ruc_institucion = $ruc)
						and op.numero_establecimiento = '$localidad'
					ORDER BY 
					nombre_provincia, 
					numero_factura, 
					concepto_orden;";

		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	
	}
	
	public function actualizarMetodoPagoPorIdPago($conexion, $idPago, $metodoPago){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												metodo_pago = '$metodoPago'
											WHERE
												id_pago = $idPago;");
	    return $res;
	    
	}
	

}

?>
