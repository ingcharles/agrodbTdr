<?php

class ControladorFinancieroAutomatico{
		
	public function guardarFinancieroAutomaticoCabecera ($conexion, $totalPagar, $idVue, $tipoSolicitud, $metodoPago = null){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero_automatico.financiero_cabecera(total_pagar, id_vue, tipo_solicitud, metodo_pago) 
											VALUES( $totalPagar, '$idVue', '$tipoSolicitud', '$metodoPago') RETURNING id_financiero_cabecera;");
		
		return $res;
	}
	
	public function guardarFinancieroAutomaticoDetalle ($conexion, $idFinancieroCabecera, $idServicio, $conceptoOrden, $cantidad, $precioUnitario, $descuento, $iva, $total){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO g_financiero_automatico.financiero_detalle(id_financiero_cabecera, id_servicio, 
														concepto_orden,  cantidad, precio_unitario, descuento, iva, total)
											VALUES( $idFinancieroCabecera, $idServicio, '$conceptoOrden', $cantidad, $precioUnitario, $descuento, $iva, $total);");
	
				return $res;
	}
	
	public function obtenerCabeceraFinancieroAutomatico ($conexion, $idFinancieroCabecera){
		
		$consulta = "SELECT * FROM g_financiero_automatico.financiero_cabecera WHERE id_financiero_cabecera = $idFinancieroCabecera";
	
		$res = $conexion->ejecutarConsulta($consulta);
				
		return $res;
	}
	
	public function actualizarEstadoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, $estado){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_financiero_automatico.financiero_cabecera 
											SET
												estado = '$estado'
											WHERE
												id_financiero_cabecera = $idFinancieroCabecera;");
		
		return $res;
	}
	
	public function actualizarEstadoFacturaFinancieroAutomaticoCabecera($conexion, $idVue, $estado){
		
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_financiero_automatico.financiero_cabecera 
											SET
												estado_factura = '$estado'
											WHERE
												id_vue = '$idVue';");
		
		return $res;
	}
	
	public function actualizarTipoProcesoFacturaFinancieroAutomaticoCabecera($conexion, $idVue, $tipoProceso){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												tipo_proceso = '$tipoProceso'
											WHERE
												id_vue = '$idVue';");
	
				return $res;
	}
	
	public function actualizarTipoProcesoFacturaFinancieroAutomaticoCabeceraPorIdentificador($conexion, $idFinancieroCabecera, $tipoProceso){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												tipo_proceso = '$tipoProceso'
											WHERE
												id_financiero_cabecera = '$idFinancieroCabecera';");
	    
	    return $res;
	}
	
	public function actualizarEstadoFinancieroAutomaticoCabeceraPorIdVue($conexion, $idVue, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												estado = '$estado'
											WHERE
												id_vue = '$idVue';");
	
				return $res;
	}
	
	public function obtenerDatosGenerarOrdenPagoAutomatico($conexion, $estado){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero_automatico.financiero_cabecera
											WHERE
												estado $estado;");
		
		return $res;
		
	}
	
	public function obtenerDatosGenerarFacturaAutomatico($conexion, $estado){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_financiero_automatico.financiero_cabecera
											WHERE
												estado_factura $estado;");
	
		return $res;
	
	}
	
	public function  obtenerDatosDetalleOrdenPagoAutomaticaPorIdentificador($conexion, $idFinancieroCabecera){
		
		$res = $conexion->ejecutarConsulta("SELECT
												id_servicio, 
												concepto_orden, 
												sum(cantidad) as cantidad, 
												sum(precio_unitario) as precio_unitario, 
												sum(descuento)as descuento, 
												sum(iva) as iva, 
												sum(total) as total
											FROM
												g_financiero_automatico.financiero_detalle
											WHERE
												id_financiero_cabecera = $idFinancieroCabecera
											GROUP BY
												id_servicio, concepto_orden");
		
		return $res;
	}
	
	public function ingresarSolicitudesXatenderGUIA($conexion, $numeroFormulario,$codigoProcesamiento,$codigoVerificacion,$idVUE, $estado, $observacion = null){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO 
													g_vue.solicitudes_atender(formulario, codigo_procesamiento, codigo_verificacion, solicitud, estado, observacion)
											VALUES ('$numeroFormulario', '$codigoProcesamiento', '$codigoVerificacion', '$idVUE','$estado', '$observacion');");
		
		return $res;
	
	}
	
	public function actualizarIdOrdenPagoFinancieroAutomaticoCabecera($conexion, $idFinancieroCabecera, $idOrdenPago){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												id_orden_pago = '$idOrdenPago'
											WHERE
												id_financiero_cabecera = $idFinancieroCabecera;");
	
				return $res;
	}
	
	public function actualizarObservacionFacturaAutomatico($conexion, $idFinancieroCabecera, $observacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												observacion = '$observacion'
											WHERE
												id_financiero_cabecera = $idFinancieroCabecera;");
	
		return $res;
	
	}
	
	public function actualizarEstadoFacturaPorIdentificadorFinancieroAutomatico($conexion, $idFinancieroCabecera, $estado){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												estado_factura = '$estado'
											WHERE
												id_financiero_cabecera = $idFinancieroCabecera;");
	
		return $res;
	
	}
	
	public function eliminarOrdenFinancieroAutomatico($conexion, $idVue){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_financiero_automatico.financiero_cabecera
											WHERE
												id_vue = '$idVue';");
	
		return $res;
	}
	
	public function actualizarFechaFacturaFinancieroAutomaticoCabecera($conexion, $idVue){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												fecha_ingreso_factura = 'now()'
											WHERE
												id_vue = '$idVue';");
	
				return $res;
	}
	
	public function obtenerTipoSolicitudPorEstadoFinancieroAutomatico($conexion, $tipoSolicitud, $condicion){
		
		//$parametros = array($tipoSolicitud,$condicion);
				
		$consulta = "SELECT
						fa.id_financiero_cabecera, fa.id_orden_pago, fa.id_vue, op.total_pagar, op.numero_solicitud
					FROM
						g_financiero_automatico.financiero_cabecera fa,
						g_financiero.orden_pago op
					WHERE
							fa.id_orden_pago = op.id_pago
							and fa.tipo_solicitud = '$tipoSolicitud'
							and fa.estado_factura $condicion;";
						
		$res = $conexion->ejecutarConsulta($consulta);
	
		return $res;
	}
	
	public function guardarHistorialFinancieroAutomatico($conexion, $idFinancieroCabecera, $descripcion, $identificador){
		
		$consulta = "INSERT INTO g_financiero_automatico.historial_financiero_automatico(id_financiero_cabecera, descripcion, identificador)
					VALUES ($idFinancieroCabecera, '$descripcion', '$identificador') ";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerDatosOrdenPagoFacturacionAutmatica($conexion, $idSolicitud, $tabla){
	    
        $consulta = "SELECT
        				id_orden_pago, id_financiero_cabecera
        			FROM
        				g_financiero_automatico.financiero_cabecera
        			WHERE
        				tabla_modulo = '$tabla'
                        and id_solicitud_tabla = $idSolicitud;";

        $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function obtenerCodigoLaboratorioPorIdentificador($conexion, $idSolicitud){
	    
	    $consulta = "SELECT
        				id_solicitud, codigo
        			FROM
        				g_laboratorios.solicitudes
        			WHERE
        				id_solicitud = '$idSolicitud';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function ActualizarNumeroFacturaLaboratorioPorIdentificador($conexion, $idSolicitud, $numeroFactura){
	    
	    $consulta = "UPDATE 
                        g_laboratorios.solicitudes
        			SET
        			     num_factura = '$numeroFactura'
        			WHERE
        				id_solicitud = '$idSolicitud';";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}
	
	public function obtenerTotalOrdenesGeneradasXIdentificadorOperador($conexion, $totalPagar, $identificadorOperador, $tipoSaldo = 'saldoAgr'){
	    
	    $consulta = "SELECT 
                        s.id_saldo
                        , s.saldo_disponible
                     FROM 
                        g_financiero.saldos s 
                     WHERE 
                        s.identificador_operador = '" . $identificadorOperador . "' 
                        and id_saldo = (SELECT max(s1.id_saldo) 
                                        FROM 
                                            g_financiero.saldos s1 
                                        WHERE 
                                            s1.identificador_operador = '" . $identificadorOperador . "' 
                                            and s1.tipo_saldo = '" . $tipoSaldo . "') 
                    GROUP BY 1, 2;";
	    
	    $res = $conexion->ejecutarConsulta($consulta);
	    
	    return $res;
	    
	}	
	
	public function actualizarEstadoYFechaFacturaFinancieroAutomaticoCabeceraXIdPago($conexion, $idPago, $estado){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												estado_factura = '$estado',
                                                fecha_ingreso_factura = 'now()'
											WHERE
												id_orden_pago = $idPago;");
	    
	    return $res;
	}
	
	public function actualizarMetodoPagoPorIdPago($conexion, $idPago, $metodoPago){
	    
	    $res = $conexion->ejecutarConsulta("UPDATE
												g_financiero_automatico.financiero_cabecera
											SET
												metodo_pago = '$metodoPago'
											WHERE
												id_orden_pago = $idPago;");
	    return $res;
	    
	}

}

?>
