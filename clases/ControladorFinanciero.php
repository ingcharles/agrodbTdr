<?php

class ControladorFinanciero{
	
	public function obtenerMaxSaldo($conexion, $idOperador, $tipoSaldo = 'saldoAgr'){
			
		$res = $conexion->ejecutarConsulta("SELECT
												s.id_saldo,
												s.saldo_disponible,
												op.*
											FROM
												g_financiero.saldos s,
												g_financiero.orden_pago op
											WHERE
												s.identificador_operador = '$idOperador'
												and s.id_pago = op.id_pago
												and id_saldo = (SELECT 
																	max(s1.id_saldo)
																FROM
																	g_financiero.saldos s1
																WHERE
																	s1.identificador_operador = '$idOperador' and
																	s1.tipo_saldo = '$tipoSaldo');");
				return $res;
	}
	
	public function guardarNuevoSaldoOperadorEgreso($conexion,$idPago,$valorEgreso,$saldoDisponible, $idOperador, $tipoSaldo = 'saldoAgr'){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.saldos(
													id_pago,fecha_deposito, valor_egreso, saldo_disponible, identificador_operador, tipo_saldo)
											VALUES ($idPago,now(),$valorEgreso,$saldoDisponible, '$idOperador', '$tipoSaldo') ;");
		return $res;
	}
	
	public function guardarNuevoSaldoOperadorIngreso($conexion, $idPago, $valorIngresado, $saldoDisponible, $idOperador, $tipoSaldo = 'saldoAgr'){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.saldos(
													id_pago, fecha_deposito,valor_ingreso, saldo_disponible, identificador_operador, tipo_saldo)
											VALUES ('$idPago',now(),'$valorIngresado', '$saldoDisponible','$idOperador', '$tipoSaldo') ;");
		return $res;
	}
	
	public function listarServiciosAsignados($conexion, $identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios s,
												g_financiero.descuento_cupos dc
											WHERE
												s.id_servicio = dc.id_servicio and
												dc.identificador_operador = '$identificador';");
			
		return $res;
	}
	
	public function obtenerServiciosXTipo($conexion, $tipo){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_financiero.servicios
											WHERE
												tipo_servicio = '$tipo';");
		return $res;
	}
	
	public function imprimirLineaServicio($idDescuentoCupo, $idServicio, $conceptoServicio, $estado){
		return '<tr id="R' . $idDescuentoCupo . '">' .
				'<td width="100%">' .
				$conceptoServicio .
				'</td>' .
				'<td>' .
				'<form class="'.$estado.'" data-rutaAplicacion="financiero" data-opcion="actualizarEstadoServicio">' .
				'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
				'<input type="hidden" id="idDescuentoCupo" name="idDescuentoCupo" value="' . $idDescuentoCupo . '" >' .
				'<input type="hidden" id="idServicio" name="idServicio" value="' . $idServicio . '" >' .
				'<input type="hidden" id="estado" name="estado" value="' . $estado . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="financiero" data-opcion="quitarServicio">' .
				'<input type="hidden" id="idDescuentoCupo" name="idDescuentoCupo" value="' . $idDescuentoCupo . '" >' .
				'<input type="hidden" id="idServicio" name="idServicio" value="' . $idServicio . '" >' .
				'<input type="hidden" id="estado" name="estado" value="' . $estado . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarServicioAsignado($conexion, $identificador, $tipoServicio){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.descuento_cupos
											WHERE
												identificador_operador = '$identificador' and
												id_servicio = $tipoServicio;");
		return $res;
	}
	
	public function guardarNuevoServicioAsignado($conexion, $identificador, $tipoServicio, $estado){
		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_financiero.descuento_cupos(identificador_operador, id_servicio, estado)
    										VALUES 
												('$identificador', $tipoServicio, '$estado')
											RETURNING
												id_descuento_cupo;");
		return $res;
	}
	
	public function actualizarEstadoServicio ($conexion, $idDescuentoCupo, $idServicio, $identificador, $estado){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.descuento_cupos
											SET
												estado = '$estado'
											WHERE
												id_descuento_cupo= $idDescuentoCupo and 
												identificador_operador= '$identificador' and
												id_servicio = $idServicio;");
		return $res;
	}
	
	public function quitarServicioAsignado($conexion, $idDescuentoCupo, $idServicio, $identificador){
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_financiero.descuento_cupos
 											WHERE 
												id_descuento_cupo = $idDescuentoCupo and
												identificador_operador= '$identificador' and
												id_servicio = $idServicio;");
		return $res;
	
	}
	
	public function obtenerIdServicio($conexion, $codigoServicio){
				
		$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_financiero.servicios
												WHERE
													codigo = '$codigoServicio';");
		return $res;
	}
	
	public function obtenerEstadoServicioOperador($conexion, $idServicio, $identificadorOperador){
				
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.descuento_cupos
											WHERE
												id_servicio = $idServicio and
												identificador_operador = '$identificadorOperador';");
		return $res;
	}
	
	public function listaClientes($conexion, $tipoCliente, $txtCliente){
		
		$busquedaCliente = '';
		switch ($tipoCliente){
			case "04": $busquedaCliente = "identificador = '$txtCliente' and tipo_identificacion = '$tipoCliente'" ; break;
			case "05": $busquedaCliente = "identificador = '$txtCliente' and tipo_identificacion = '$tipoCliente'" ; break;
			case "07": $busquedaCliente = "identificador = '$txtCliente' and tipo_identificacion = '$tipoCliente'"; break;
			case "06": $busquedaCliente = "identificador = '$txtCliente' and tipo_identificacion = '$tipoCliente'"; break;
			case "01": $busquedaCliente = "UPPER(c.razon_social) = UPPER('".strtoupper($txtCliente)."') and tipo_identificacion = '04'"; break;
			
		}
		
		$res = $conexion->ejecutarConsulta("select
												c.*
											from
												g_financiero.clientes c
											where ".$busquedaCliente." ;");
			
		return $res;
	}
	
	public function imprimirLineaDocumento ($idServicio, $codigo, $concepto, $ruta, $idArea){
		return '<tr id="R' . $idServicio . '-'.$codigo.'-'.$concepto.'">' .
				'<td width="100%">' .
				'<b>'.$codigo.'-'.'</b>'. $concepto.'</b>'.
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirSubDocumento" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idServicio" value="' . $idServicio . '" >' .
				'<input type="hidden" name="codigo" value="' . $codigo . '" >' .
				'<input type="hidden" name="concepto" value="' . $concepto . '" >' .
				'<input type="hidden" name="idArea" value="' . $idArea . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function abrirSubDocumento ($conexion,$idServicio, $idArea){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios
											WHERE
												id_servicio = $idServicio
												AND  id_area = '$idArea'
												AND  estado = 'activo';");
				return $res;
	}
	
	public function listaItems ($conexion, $idServicio, $idArea){
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios
											WHERE id_servicio_padre = $idServicio
												AND  id_area = '$idArea'
												AND  estado = 'activo'
												ORDER BY id_servicio ASC;");
				return $res;
	}
	
	public function imprimirLineaItem($idItem, $codigo, $concepto, $ruta, $idArea, $idServicio, $codigoPadre, $conceptoPadre){
		return '<tr id="R' . $idItem . '">' .
				'<td width="100%">' .
				'<b>'.$codigo.'-'.'</b>'. $concepto.'</b>'.
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="'.$ruta.'" data-opcion="abrirItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idItem" value="' . $idItem . '" >' .
				'<input type="hidden" id="idArea" name="idArea" value="' . $idArea . '" >' .
				'<input type="hidden" id="idServicio" name="idServicio" value="' . $idServicio . '" >' .
				'<input type="hidden" id="codigoPadre" name="codigoPadre" value="' . $codigoPadre . '" >' .
				'<input type="hidden" id="conceptoPadre" name="conceptoPadre" value="' . $conceptoPadre . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="'.$ruta.'" data-opcion="eliminarItem">' .
				'<input type="hidden" name="idItem" value="' . $idItem . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function buscarIdPadre ($conexion, $idServicio, $idArea, $estado){
		$res = $conexion->ejecutarConsulta("SELECT
												id_servicio_padre
											FROM
												g_financiero.servicios
											WHERE 
												id_servicio = $idServicio
												AND  id_area = '$idArea'
												AND  estado = '$estado';");
				return $res;
	}
	
	public function generaNumeroDocumento ($conexion,$idPadre,$categoria,$area){
	
		$res = $conexion->ejecutarConsulta("SELECT
												MAX(codigo) as numero
											FROM
												g_financiero.servicios
											WHERE 
												id_categoria_servicio = $categoria
												AND   id_servicio_padre = $idPadre
												AND   id_area = '$area';");
		return $res;
	}
	
	public function guardarNuevoDocumento($conexion,$concepto,$area,$nuevoCodigo,$idPadre){
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_financiero.servicios(concepto, unidad, valor, id_area, tipo_servicio,codigo, iva, id_categoria_servicio, id_servicio_padre,estado)
											VALUES
												('$concepto',0,0,'$area','Interno','$nuevoCodigo','FALSE',2,$idPadre,'activo')
											RETURNING
												id_servicio;");
				return $res;
	}
	
	public function guardarNuevoItem ($conexion,$concepto, $unidad, $valor, $idArea, $codigo, $iva, $idDocumento ,$partidaPresupuestaria, $unidadMedida, $subsidio, $cobroExceso, $usuadoPara, $idServicioExcesos){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_financiero.servicios (concepto,unidad,valor,id_area,tipo_servicio, codigo,iva,id_categoria_servicio,id_servicio_padre,
												partida_presupuestaria,unidad_medida,estado, subsidio, cobro_exceso, usado_para, id_servicio_exceso)
											VALUES
												('$concepto',$unidad,$valor,'$idArea','Interno','$codigo','$iva',3,$idDocumento,
												'$partidaPresupuestaria','$unidadMedida','activo', $subsidio, '$cobroExceso', '$usuadoPara', $idServicioExcesos)
											RETURNING id_servicio;");
				return $res;
	}
	
	public function actualizarItem ($conexion, $idItem, $concepto, $unidad, $valor, $iva, $partidaPresupuestaria, $unidadMedida, $subsidio, $cobroExceso, $usuadoPara, $idServicioExcesos){
				
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.servicios
											SET
												concepto = '$concepto',
												unidad = $unidad,
												valor = $valor,
												iva = '$iva',
												partida_presupuestaria = '$partidaPresupuestaria',
												unidad_medida = '$unidadMedida',
												subsidio = $subsidio,
												usado_para = '$usuadoPara',
												id_servicio_exceso = $idServicioExcesos,
												cobro_exceso = '$cobroExceso'
											WHERE
												id_servicio = $idItem;");
		return $res;
	}
	
	public function quitarItem($conexion, $idItem){
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_financiero.servicios
											WHERE
												id_servicio = $idItem;");
		return $res;
	}
	
	public function abrirOrdenPago($conexion, $idPago){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from 	
												g_financiero.orden_pago
											where 	
												id_pago = $idPago;");
		return $res;
	}
	
	public function buscarEstadoOrdenPago ($conexion,$idPago){
	
		$res = $conexion->ejecutarConsulta(" select 
												*
											from 
												g_financiero.orden_pago
											where 
												id_pago = $idPago
												and estado not in (3,9); ");
				return $res;
	}
	
	public function darBajaOrdenPago ($conexion, $idPago, $observacion){
			
		$res = $conexion->ejecutarConsulta("update
												g_financiero.orden_pago
											set
												estado = 9,
												observacion_eliminacion = '$observacion'
											where
												id_pago = $idPago;");
		return $res;
	}
	
	public function listaDocumento($conexion, $tipoDocumento, $txtDocumento, $numeroEstablecimiento, $puntoEmision, $rucDistrito ){
	
		$busquedaDocumento = '';
		switch ($tipoDocumento){
			case "02":
			case "01": $busquedaDocumento = "o.numero_factura = '$txtDocumento' and o.numero_establecimiento = '$numeroEstablecimiento' and o.punto_emision = '$puntoEmision' and o.ruc_institucion = '$rucDistrito'"; break;
	
		}
									
		$reg = $conexion->ejecutarConsulta("SELECT
												o.localizacion,
												o.id_pago,
												o.identificador_operador,
												c.razon_social,
												o.fecha_facturacion,
												o.total_pagar,
												o.observacion,
												d.concepto_orden,
												d.cantidad,
												d.precio_unitario,
												d.iva,
												d.total,
												d.id_servicio,
												d.descuento,
												o.numero_establecimiento,
												o.porcentaje_iva
											FROM
												g_financiero.orden_pago o,
												g_financiero.detalle_pago d,
												g_financiero.clientes c
											WHERE 	
												".$busquedaDocumento." and
												o.id_pago = d.id_pago and
												o.identificador_operador = c.identificador and
												o.estado = '4' and
												o.estado_sri = 'AUTORIZADO' and
												o.utilizado = 'false' and
												o.id_pago not in (SELECT id_pago FROM g_financiero.nota_credito nc WHERE nc.id_pago = o.id_pago and nc.estado = 4);");
				
		while ($fila = pg_fetch_assoc($reg)){
			$res[] = array(
					localizacion=>$fila['localizacion'],
					identificadorOperador=>$fila['identificador_operador'],
					razonSocial=>$fila['razon_social'],
					fechaOrdenPago=>$fila['fecha_facturacion'],
					totalPagar=>$fila['total_pagar'],
					observacion=>$fila['observacion'],
					conceptoOrden=>$fila['concepto_orden'],
					cantidad=>$fila['cantidad'],
					precioUnitario=>$fila['precio_unitario'],
					descuento=>$fila['descuento'],
					iva=>$fila['iva'],
					total=>$fila['total'],
					idServicio =>$fila['id_servicio'],
					idPago =>$fila['id_pago'],
					numeroEstablecimiento =>$fila['numero_establecimiento'],
					porcentajeIva =>$fila['porcentaje_iva']
			);
		}
		
		
		return $res;
	}
	
	public function guardarNuevoClaveContingencia($conexion, $fechaDesde, $fechaHasta,$observacion, $identificador){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_financiero.apertura_clave_contingencia(fecha_desde, fecha_hasta, observacion, estado, identificador)
											VALUES ('$fechaDesde','$fechaHasta','$observacion', 'activo', '$identificador');");
				return $res;
	}
	
	public function listarClavesContingencia($conexion){
	$res = $conexion->ejecutarConsulta("SELECT
											*
										FROM
											g_financiero.apertura_clave_contingencia
										WHERE 
												estado = 'activo'
										ORDER BY 
												id_clave_contingencia;");
				return $res;
	}
	
	public function abrirClavesContingencia($conexion,$idClaveContingencia){
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_financiero.apertura_clave_contingencia
												WHERE
													id_clave_contingencia = $idClaveContingencia;");
			return $res;
	}
	
	public function actualizarClaveContingencia ($conexion, $idClave, $fechaDesde, $fechaHasta, $observacion, $identificador){
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.apertura_clave_contingencia
											SET
												fecha_desde = '$fechaDesde',
												fecha_hasta = '$fechaHasta',
												observacion = '$observacion',
												identificador = '$identificador'
											WHERE
												id_clave_contingencia = $idClave;");
				return $res;
	}
	
	public function buscarEstadoClaveContingencia ($conexion,$idClave){
	
			$res = $conexion->ejecutarConsulta("SELECT
													*
												FROM
													g_financiero.apertura_clave_contingencia
												WHERE
													id_clave_contingencia = $idClave and
													fecha_desde <= 'now()' and
													fecha_hasta >= 'now()'; ");
		
			return $res;
	}
	
	public function darBajaClaveContingencia ($conexion,$idClave,$observacion, $identificador){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.apertura_clave_contingencia
											SET
												estado = 'inactivo',
												observacion = '$observacion',
												identificador = '$identificador'
											WHERE
												id_clave_contingencia = $idClave;");
		 return $res;
	}
	
	public function obtenerFechasContigenciaVigentes ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.apertura_clave_contingencia
											WHERE 
												id_clave_contingencia = (SELECT 
																			max(id_clave_contingencia)
																		FROM 
																			g_financiero.apertura_clave_contingencia
																		WHERE estado = 'activo');");
		return $res;
	}
	
	public function obtenerClaveContingencia($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.claves_contigencia
											WHERE
												id_clave_contingencia = (SELECT
																			max(id_clave_contingencia)+1
																		FROM
																			g_financiero.claves_contigencia
																		WHERE 
																			estado = 'FALSE');");
		
		
		return $res;
	}
	
	public function actualizarEstadoClaveContingencia($conexion, $idClaveContingencia, $idComprobante, $tipoComprobante){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_financiero.claves_contigencia
											SET
												id_comprobante = '$idComprobante',
												tipo_comprobante = '$tipoComprobante',
												estado = 'FALSE'
											WHERE
												id_clave_contingencia = $idClaveContingencia;");
	
	
		return $res;
	}
	
	
	public function obtenerClaveContigenciaPorIdComprobante($conexion, $idComprobante, $tipoComprobante){
	
		$res = $conexion->ejecutarConsulta("SELECT
													*
											FROM
													g_financiero.claves_contigencia
											WHERE
													id_comprobante = $idComprobante and
													tipo_comprobante = '$tipoComprobante';");
	
	
				return $res;
	}
	
	public function listaNotaCredito($conexion, $numeroNotaCredito, $numeroEstablecimiento, $puntoEmision, $rucDistrito){
		
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.nota_credito
											WHERE 	
												numero_nota_credito = '$numeroNotaCredito' and
												numero_establecimiento = '$numeroEstablecimiento' and 
												punto_emision = '$puntoEmision' and 
												ruc_institucion = '$rucDistrito' and
												estado = '4' and 
												estado_sri = 'AUTORIZADO';");
		return $res;
	}
	
	public function obtenerPagoNotaCredito($conexion, $idNotaCredito){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.detalle_forma_pago
											WHERE
												id_nota_credito = $idNotaCredito;");
		return $res;
	}
	
	public function obtenerAreaServicioNotaCredito($conexion, $servicio){
	
		$res = $conexion->ejecutarConsulta("SELECT
												distinct id_area	
											FROM
												g_financiero.servicios s
											WHERE
												id_servicio IN $servicio;");
		return $res;
	}
	
	public function obtenerDatosTransferenciaBancaria($conexion, $idBanco, $fechaDeposito, $numeroPapeleta, $valorDepositado){
					
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.detalle_forma_pago
											WHERE
												id_banco = $idBanco and
												fecha_orden_pago = '$fechaDeposito' and
												transaccion = '$numeroPapeleta' and
												valor_deposito = $valorDepositado;");
		return $res;
	}
	
	public function listaOperadores($conexion, $tipoCliente, $txtCliente){
	
		$busquedaCliente = '';
		switch ($tipoCliente){
			case "04":
			case "05":
			case "07":
			case "06":
				$busquedaCliente = "o.identificador = '$txtCliente' ";
				break;
			case "01":
				$busquedaCliente = "UPPER(o.razon_social) = UPPER('.$txtCliente.')";
				break;
	
		}
	
		$res = $conexion->ejecutarConsulta("select
												o.*
											from
												g_operadores.operadores  o
												where ".$busquedaCliente." ;");
			
		return $res;
	}
	
	public function actualizarEstadoAperturaContingencia ($conexion,$idClave, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.apertura_clave_contingencia
											SET
												estado = '$estado'
											WHERE
												id_clave_contingencia = $idClave;");
		return $res;
	}
	
	public function obtenerDatosRecaudador($conexion,$identificador){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.oficina_recaudacion
											WHERE
												identificador_firmante = '$identificador';");
		return $res;
	}
	
	public function listarComprobantesXdarBaja($conexion, $tipoDocumento, $txtDocumento, $numeroEstablecimiento, $puntoEmision, $rucDistrito ){
	
		$busquedaDocumento = '';
		switch ($tipoDocumento){
			case "02":
			case "01": $busquedaDocumento = "o.numero_factura = '$txtDocumento' and o.numero_establecimiento = '$numeroEstablecimiento' and o.punto_emision = '$puntoEmision' and o.ruc_institucion = '$rucDistrito'"; break;
	
		}
	
		$res = $conexion->ejecutarConsulta("SELECT
												o.id_pago,
												o.identificador_operador,
												c.razon_social,
												o.fecha_facturacion,
												o.total_pagar,
												o.numero_solicitud,
												o.estado_sri,
												o.utilizado
											FROM
												g_financiero.orden_pago o,
												g_financiero.clientes c
											WHERE
												".$busquedaDocumento." and
												o.identificador_operador = c.identificador and
												o.estado = 4 and
												o.tipo_solicitud not in ('Ingreso Caja') and
												o.estado_sri not in ('ANULADO');");
	
		return $res;
	}
	
	public function abrirUsoFactura ($conexion, $id_pago){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_consumo_comprobante.uso_factura
											WHERE
												id_pago = $id_pago;");
		return $res;
	}
	
	public function guardarUsoDetalleFactura($conexion,$idPago,$identificador,$provincia,$idArea,$observacion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_consumo_comprobante.uso_factura(id_pago, identificador, provincia, id_area, fecha, observacion)
											VALUES
												($idPago,'$identificador','$provincia','$idArea', now(),'$observacion');");
				return $res;
	}
	

	public function actualizarEstadoUsoFactura ($conexion,$idPago){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												utilizado = 'TRUE'
											WHERE
												id_pago = $idPago;");
		return $res;
	}
	
	public function actualizarDocumento($conexion, $idDocumento, $nombreDocumento){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.servicios
											SET
												concepto='$nombreDocumento'
											WHERE
												id_servicio=$idDocumento;");
									
				return $res;
	}
	
	public function obtenerServicioPorProducto($conexion, $idServicio, $idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios_productos
											WHERE
												id_servicio=$idServicio 
												and id_producto IN $idProducto;");
			
		return $res;
	}
	
	public function guardarNuevoServicioPorProducto($conexion, $idServicio, $idProducto, $exoneracion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero.servicios_productos(id_producto, id_servicio, exoneracion)
											 VALUES ($idProducto, $idServicio, $exoneracion) RETURNING id_servicio_producto;");
					
		return $res;
	}
	
	public function imprimirLineaServicioProducto($idServicioProducto, $idProducto, $nombreProducto, $estado){
	
		return '<tr id="R'.$idServicioProducto.'">' .
					'<td>'.$idProducto.'</td>' .
					'<td width="100%">'.$nombreProducto.'</td>' . $estado.
					'<td>' .
					'<form class="'.$estado.'" data-rutaAplicacion="financiero" data-opcion="actualizarEstadoServicioProducto">' .
					'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
					'<input type="hidden" name="idServicioProducto" value="' . $idServicioProducto . '" >' .
					'<button type="submit" class="icono"></button>' .
					'</form>' .
					'</td>' .					
					'<td>' .
						'<form class="borrar" data-rutaAplicacion="financiero" data-opcion="eliminarServicioProducto">' .
							'<input type="hidden" name="idServicioProducto" value="' . $idServicioProducto . '" >' .
							'<button type="submit" class="icono"></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function obtenerServicioProducto($conexion, $idServicio){
							
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios_productos
											WHERE
												id_servicio=$idServicio;");
					
				return $res;
	}
	
	public function eliminarServicioProductoPorIdentificadorServicio($conexion, $idServicioProducto){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM g_financiero.servicios_productos
											WHERE id_servicio_producto=$idServicioProducto");
					
				return $res;
	}
	
	public function obtenerUnidadMedidaCobro ($conexion, $idProducto, $usuadoPara){
						
		$res = $conexion->ejecutarConsulta("SELECT 
												* 
											FROM
												g_financiero.servicios s,
												g_financiero.servicios_productos sp
											WHERE
												s.id_servicio = sp.id_servicio and
												s.usado_para = '$usuadoPara' and
												sp.id_producto = $idProducto");
		
		return $res;
	}
	
	public function obtenerServicio($conexion, $idServicio){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios
											WHERE
												id_servicio=$idServicio;");
			
		return $res;
	}
	
	public function obtenerDatosRecaudadorPorProvinciaEstadoFirma($conexion,$idProvincia, $habilitadoFirma){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.oficina_recaudacion
											WHERE
												id_provincia = '$idProvincia' and
												firma_automatica = '$habilitadoFirma';");
		return $res;
	}
	
	
	/*public function desencriptarClaveUsuario ($input,$key) {
		$output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		return $output;
	}*/
	
	public static function desencriptarClaveUsuario ($input,$Key) {
	    $encryption_key = base64_decode($Key);
	    list($encrypted_data, $iv) = explode('::', base64_decode($input), 2);
	    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}
	
	public function buscarServicioProducto ($conexion,$idProducto){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios_productos
											WHERE
												id_producto = '$idProducto';");
	
		return $res;
	}
	
	public function actualizarEstadoServicioProducto ($conexion, $idServicioProducto, $exoneracion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.servicios_productos
											SET
												exoneracion = '$exoneracion'
											WHERE
												id_servicio_producto = $idServicioProducto");
		return $res;
	}
	
	public function obtenerIdServicioPorCodigoArea($conexion, $codigoServicio, $area){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.servicios
											WHERE
												codigo = '$codigoServicio'
												and id_area = '$area';");
		return $res;
	}
	
	public function obtenerOrdenPagoPorIdentificadorSolicitud($conexion, $idSolicitud, $tipo){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero.orden_pago
											WHERE
												id_solicitud like ('%$idSolicitud%')
												and tipo_solicitud = '$tipo'
												and estado = 3;");
		return $res;
	}
	
	public function abrirOrdenDetallePago($conexion, $idPago){
	
		$res = $conexion->ejecutarConsulta("select
												*
											from
												g_financiero.detalle_pago
											where
												id_pago = $idPago;");
		return $res;
	}
	
	public function actualizarNumeroOrdenSolicitudVue ($conexion,$idSolicitud, $idGrupo, $tipoSolicitud, $numeroOrdenPago){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago
											SET
												numero_orden_vue = '$numeroOrdenPago'
											WHERE
												id_solicitud = '$idSolicitud'
												and id_grupo_solicitud = $idGrupo 
												and tipo_solicitud = '$tipoSolicitud';");
		return $res;
	}
	
	public function obtenerCantidadOrdenPagoPorTipoSolicitudFechas($conexion, $identificador, $tipoSolicitud, $fechaInicio, $fechaFin, $tipoConsulta){
		
		$fechaInicio = $fechaInicio." 00:00:00" ;
		$fechaFin = $fechaFin." 24:00:00" ;
		
		$campos = '';
		$agrupamiento = '';
		if($tipoConsulta == 'campoGrupal'){
			$campos = 'identificador_operador, razon_social, count(op.id_pago) as facturas_generadas';
			$agrupamiento = 'GROUP BY identificador_operador,razon_social';
		}else{
			$campos = 'identificador_operador, razon_social, numero_factura, fecha_facturacion, dp.concepto_orden, total_pagar, numero_establecimiento, punto_emision, factura';	
		}
				
		$res = $conexion->ejecutarConsulta("SELECT
												$campos
											FROM
												g_financiero.orden_pago op,
												g_financiero.detalle_pago dp,
												g_financiero.clientes c
											WHERE
												dp.id_pago = op.id_pago and
												op.identificador_operador = c.identificador and 
												identificador_operador = '$identificador' and
												tipo_solicitud = '$tipoSolicitud' and 
												fecha_facturacion between '$fechaInicio' and '$fechaFin' 
											$agrupamiento;");
		
		return $res;
	}
	
	public function obtenerCantidadOrdenPagoPorTipoProcesoFechas($conexion, $identificador, $tipoProceso, $fechaInicio, $fechaFin, $tipoConsulta){
		
		$fechaInicio = $fechaInicio." 00:00:00" ;
		$fechaFin = $fechaFin." 24:00:00" ;
		
		$campos = '';
		if($tipoConsulta == 'campoGrupal'){
			$campos = 'count(id_pago) as comprobantes_generados, sum(total_pagar) as consumo_comprobantes';
		}else{
			$campos = 'numero_establecimiento, punto_emision, numero_factura, factura, fecha_facturacion, total_pagar, tipo_solicitud, id_solicitud';
		}
				
		$res = $conexion->ejecutarConsulta("SELECT
												 $campos
											FROM
												g_financiero.orden_pago op,
												g_financiero.clientes c
											WHERE
												op.identificador_operador = c.identificador and 
												identificador_operador = '$identificador' and
												tipo_proceso = '$tipoProceso' and 
												fecha_facturacion between '$fechaInicio' and '$fechaFin';");
		
		return $res;
		
	}
	
	public function obtenerMaxSaldoPorIdentificadorFechas($conexion, $identificador, $tipoSaldo, $fechaInicio, $fechaFin){
		
		$fechaInicio = $fechaInicio." 00:00:00" ;
		$fechaFin = $fechaFin." 24:00:00" ;
				
		$res = $conexion->ejecutarConsulta("SELECT
												saldo_disponible,
												fecha_deposito
											FROM
												g_financiero.saldos
											WHERE 
												id_saldo = (SELECT 
																max(s1.id_saldo) 
															FROM 
																g_financiero.saldos s1 
															WHERE 
																s1.identificador_operador = '$identificador' and 
																s1.tipo_saldo = '$tipoSaldo' and 
																s1.fecha_deposito between '$fechaInicio' and '$fechaFin');");
		
		return $res;
		
	}
		
	public function obtenerIdentificadorPorEstadoSriTipoProceoNumeroEstablecimiente($conexion, $establecimiento, $fechaInicio, $fechaFin, $tipoSaldo, $ruc){
		
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$fechaInicio = $fechaInicio." 00:00:00" ;
		$fechaFin = $fechaFin." 24:00:00" ;

		$res = $conexion->ejecutarConsulta("SELECT 
												distinct op.identificador_operador,
												razon_social
											FROM 
												g_financiero.orden_pago op, 
												g_financiero.clientes c,
												g_financiero.saldos s
											WHERE
												op.identificador_operador = c.identificador and
												op.id_pago = s.id_pago and
												estado = 4 and
												s.tipo_saldo = '$tipoSaldo' and
												fecha_facturacion between '$fechaInicio' and '$fechaFin' and
												numero_establecimiento = '$establecimiento'
												and ($ruc is NULL or op.ruc_institucion = $ruc)");
		
		return $res;
		
	}
	
	public function filtrarConsumoSaldoDisponible($conexion, $establecimiento, $fechaInicio, $fechaFin, $tipoSaldo, $ruc){
		
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$fechaInicio = $fechaInicio." 00:00:00" ;
		$fechaFin = $fechaFin." 24:00:00" ;		

		$res = $conexion->ejecutarConsulta("SELECT
												op.identificador_operador,
												c.razon_social,
												op.numero_establecimiento,
												op.punto_emision,
												op.numero_factura, 
												op.numero_orden_vue,
												op.fecha_facturacion,
												dp.concepto_orden,
												dp.total,
												op.tipo_solicitud,
												op.tipo_proceso,
												s.valor_ingreso,
												s.valor_egreso,
												s.saldo_disponible,
												op.numero_orden_vue
											FROM
												g_financiero.orden_pago op,
												g_financiero.detalle_pago dp,
												g_financiero.clientes c,
												g_financiero.saldos s
											WHERE
												op.id_pago = dp.id_pago
												and op.identificador_operador = c.identificador
												and op.id_pago = s.id_pago
												and op.estado = 4
												and s.tipo_saldo = '$tipoSaldo'
												and fecha_facturacion between '$fechaInicio' and '$fechaFin'
												and numero_establecimiento = '$establecimiento'
												and ($ruc is NULL or op.ruc_institucion = $ruc)
											ORDER BY
												fecha_facturacion");
		
		return $res;
		
	}
	
	public function obtenerComprovantesVuePorFechas($conexion, $tipoProceso, $fechaInicio, $fechaFin, $establecimiento, $tipoConsulta, $ruc){
	
		$ruc = $ruc!='' ? "'" . $ruc . "'" : "NULL";
		
		$fechaInicio = $fechaInicio." 00:00:00" ;
		$fechaFin = $fechaFin." 24:00:00" ;
		$campos = '';
		
		if($tipoConsulta == 'individual'){
			$campos = 'distinct op.identificador_operador, razon_social';
		}else{
			$campos = '	distinct op.id_pago, op.identificador_operador, c.razon_social, o.provincia, op.numero_establecimiento, op.punto_emision,
						op.numero_factura, op.numero_orden_vue, op.observacion, op.fecha_orden_pago, dp.concepto_orden, dp.cantidad,
						dp.precio_unitario, dp.descuento, dp.iva, dp.total, op.total_pagar, dfp.transaccion, dfp.valor_deposito, dfp.fecha_orden_pago as fecha_pago, 
						s.saldo_disponible';
		}
				
		$res = $conexion->ejecutarConsulta("SELECT
												$campos 
											FROM
												g_financiero.orden_pago op,
												g_financiero.detalle_pago dp,
												g_financiero.detalle_forma_pago dfp,
												g_financiero.oficina_recaudacion o,
												g_financiero.saldos s,
												g_financiero.clientes c
											WHERE
												op.id_pago = dp.id_pago and
												op.id_pago = dfp.id_pago and
												op.identificador_usuario = o.identificador_firmante and
												op.id_pago = s.id_pago and
												op.identificador_operador = c.identificador and
												op.tipo_proceso = '$tipoProceso' and
												op.numero_establecimiento = '$establecimiento' and
												op.estado = 4 and
												op.estado_sri = 'FINALIZADO' and
												op.fecha_facturacion between '$fechaInicio' and '$fechaFin'
												and ($ruc is NULL or op.ruc_institucion = $ruc);");
	
		return $res;
	
	}
	
	public function abrirCamposOrdenPagoXFechaFacturacion($conexion, $fechaConciliacionInicio, $fechaConciliacionFin){
	
		$fechaConciliacionInicio = $fechaConciliacionInicio.' 00:00:00';
		$fechaConciliacionFin = $fechaConciliacionFin.' 24:00:00';		

		$res = $conexion->ejecutarConsulta("SELECT
												op.numero_orden_vue
											FROM
												g_financiero.orden_pago op
												INNER JOIN g_financiero_automatico.financiero_cabecera fc ON op.id_pago = fc.id_orden_pago
												INNER JOIN dblink ('hostaddr=192.168.200.8 dbname=Solicitudes_Dev user=vue_gateway password=6a7e34yV8','select fecha, solicitud from agrocalidad.solicitudes_atender where fecha >= ''$fechaConciliacionInicio'' and fecha <= ''$fechaConciliacionFin'' and codigo_procesamiento = ''130''')
												AS s(fecha timestamp without time zone, solicitud character varying) ON s.solicitud = fc.id_vue
											WHERE
												op.estado = 4
												and (op.fecha_facturacion >= '$fechaConciliacionInicio'
												and op.fecha_facturacion <= '$fechaConciliacionFin'
												and op.tipo_solicitud IN ('Importación','Fitosanitario'))
												or estado_conciliacion = 'noConciliado'--pendiente
											ORDER BY
												nombre_provincia, id_pago;");
		return $res;
	}
	
	public function abrirOrdenPagoPorFechaConciliacion ($conexion,  $fechaConciliacionInicio, $fechaConciliacionFin){
	
		$fechaConciliacionInicio = $fechaConciliacionInicio.' 00:00:00';
		$fechaConciliacionFin = $fechaConciliacionFin.' 24:00:00';

		$res = $conexion->ejecutarConsulta("SELECT
												distinct op.id_pago, op.identificador_operador, op.numero_solicitud, op.fecha_orden_pago, op.total_pagar, op.observacion, op.estado, op.localizacion,
												op.institucion_bancaria, op.numero_papeleta, op.valor_deposito, op.orden_pago, op.factura, op.numero_factura, op.fecha_facturacion, op.ruc_institucion,
												op.ruta_xml, op.numero_autorizacion, op.fecha_autorizacion, op.clave_acceso, op.identificador_usuario,
												op.observacion_eliminacion, op.estado_mail, op.comprobante_factura, op.identificador_firmante, op.tipo_emision, op.tipo_solicitud,
												op.id_grupo_solicitud, op.id_solicitud, op.nombre_provincia, op.id_provincia, op.numero_establecimiento, op.punto_emision, op.utilizado,
												op.notificacion_dinero_electronico, CASE WHEN op.tipo_proceso='factura' THEN 'electronico' ELSE 'anticipado' END as tipo_liquidacion, op.porcentaje_iva, op.numero_orden_vue, op.estado_conciliacion, to_char(s.fecha,'YYYY/MM/DD HH24:MI:SS') as fecha_hora_pago_vue												
											FROM
												g_financiero.orden_pago op
												INNER JOIN g_financiero_automatico.financiero_cabecera fc ON op.id_pago = fc.id_orden_pago
												INNER JOIN dblink ('hostaddr=192.168.200.8 dbname=Solicitudes_Dev user=vue_gateway password=6a7e34yV8','select fecha, solicitud from agrocalidad.solicitudes_atender where fecha >= ''$fechaConciliacionInicio'' and fecha <= ''$fechaConciliacionFin'' and codigo_procesamiento = ''130''')
												AS s(fecha timestamp without time zone, solicitud character varying) ON s.solicitud = fc.id_vue
											WHERE
												op.estado = 4
												and (op.fecha_facturacion >= '$fechaConciliacionInicio' 
												and op.fecha_facturacion <= '$fechaConciliacionFin'  
												and op.tipo_solicitud IN ('Importación','Fitosanitario'))
												or estado_conciliacion = 'noConciliado'--pendiente
											ORDER BY
												nombre_provincia, id_pago;");
	
		return pg_fetch_all($res);
		
	}

	public function abrirCamposOrdenPagoXFechaFacturacionXNumeroOrdenVue($conexion, $fechaConciliacionInicio, $fechaConciliacionFin, $numeroOrdenVue){
	
		$fechaConciliacionInicio = $fechaConciliacionInicio.' 00:00:00';
		$fechaConciliacionFin = $fechaConciliacionFin.' 24:00:00';
		
		$res = $conexion->ejecutarConsulta("SELECT
												id_pago, identificador_operador, numero_solicitud, fecha_orden_pago, 
										       total_pagar, observacion, estado, localizacion, institucion_bancaria, 
										       numero_papeleta, valor_deposito, orden_pago, factura, numero_factura, 
										       to_char(fecha_facturacion,'DD/MM/YYYY')as fecha_facturacion, ruc_institucion, observacion_sri, ruta_xml, 
										       estado_sri, numero_autorizacion, fecha_autorizacion, clave_acceso, 
										       identificador_usuario, observacion_eliminacion, estado_mail, 
										       comprobante_factura, identificador_firmante, tipo_emision, tipo_solicitud, 
										       id_grupo_solicitud, id_solicitud, nombre_provincia, id_provincia, 
										       numero_establecimiento, punto_emision, utilizado, notificacion_dinero_electronico, 
										       tipo_proceso, porcentaje_iva, numero_orden_vue, estado_conciliacion
											FROM 	
												g_financiero.orden_pago
											WHERE
												fecha_facturacion >= '$fechaConciliacionInicio'
												and fecha_facturacion <= '$fechaConciliacionFin'
												and numero_orden_vue = '$numeroOrdenVue';");
		return $res;
	}
}

?>
