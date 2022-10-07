<?php

class ControladorServiciosLinea{

	public function guardarNuevaConfirmacionPago($conexion,$identificadorResponsable,$localizacion,$rutaExcel,$fecha){

		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_servicios_linea.confirmacion_pagos(
												identificador_responsable, localizacion, ruta, fecha_registro, fecha_documento, estado)
											VALUES 
												('$identificadorResponsable', '$localizacion','$rutaExcel','now()','$fecha','activo') RETURNING id_confirmacion_pago;");
		return $res;
	}
	
	public function guardarNuevaRutasTransporte($conexion,$identificadorResponsable,$nombreRuta,$idProvincia,$provincia,$idCanton,$canton,$idOficina,$oficina,$sector,$conductor,$telefono,$administradorGrupo,$telefonoAdministrador,$capacidadVehiculo,$numeroPasajeros,$placaVehiculo,$descripcionVehiculo ){
			$res = $conexion->ejecutarConsulta("INSERT INTO g_servicios_linea.rutas_transporte(
													identificador_responsable,	nombre_ruta, id_provincia, provincia, id_canton, canton, id_oficina, oficina, sector, conductor, telefono, estado, administrador_grupo, telefono_administrador, capacidad_vehiculo, numero_pasajeros,
      												placa_vehiculo, descripcion_vehiculo)
												VALUES ('$identificadorResponsable', '$nombreRuta',$idProvincia,'$provincia',$idCanton,'$canton',$idOficina,'$oficina','$sector','$conductor','$telefono','activo','$administradorGrupo','$telefonoAdministrador','$capacidadVehiculo','$numeroPasajeros','$placaVehiculo','$descripcionVehiculo') RETURNING id_ruta_transporte;");
		
		return $res;
	}
	
	public function guardarNuevoDetalleRutasTransporte($conexion,$idRutaTransporte, $identificadorResponsable,$latitud,$longitud,$direccion,$horaAproximada,$recorrido,$orden){
			$res = $conexion->ejecutarConsulta("INSERT INTO g_servicios_linea.detalle_rutas_transporte(
													id_ruta_transporte,	identificador_responsable, latitud, longitud,referencia_parada,	hora_aproximada, recorrido, orden)
												VALUES ('$idRutaTransporte', '$identificadorResponsable','$latitud','$longitud','$direccion','$horaAproximada','$recorrido','$orden') RETURNING id_detalle_rutas_transporte;");
		return $res;
	}
	
	
	public function guardarNuevoDetalleConfirmacionPagos($conexion,$idConfirmacionPago,$numCur,$descripcion,$identificadorBeneficiario,$nombreBeneficiario,$fechaPago,$montoPago,$banco){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_servicios_linea.detalle_confirmacion_pagos(
												id_confirmacion_pago,  num_trans_cur, descripcion,identificador_beneficiario, nombre_beneficiario, fecha_pago, monto_pago, banco, correo_enviado, estado)
											VALUES ($idConfirmacionPago , '$numCur','$descripcion','$identificadorBeneficiario','$nombreBeneficiario','$fechaPago','$montoPago','$banco','FALSE','activo')");
		return $res;
	}
	
	public function obtenerRegistroConfirmacionPagoIndividual ($conexion, $localizacion ,$fechaInicio, $fechaFinn,$filtro=null){
		$localizacion = $localizacion != "" ? "'" .  $localizacion  . "'" : "NULL";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'"  : "NULL";
		if($fechaFin!=""){
			$fechaFin = str_replace("/","-",$fechaFin);
			$fechaFin = strtotime ( '+1 day' , strtotime ( $fechaFin ) ) ;
			$fechaFin=date('d-m-Y',$fechaFin);
			$fechaFin="'" .$fechaFin. "'";
		}else{
			$fechaFin="NULL";
		}
		if($filtro==null)
			$busqueda = " limit 0";

		$res = $conexion->ejecutarConsulta("SELECT 
												c.id_confirmacion_pago,
												c.localizacion,
												to_char(c.fecha_documento,'DD') fecha_documento_dia,
												to_char(c.fecha_documento,'MM') fecha_documento_mes,
												to_char(c.fecha_documento,'YYYY') fecha_documento_anio
											FROM 
												g_servicios_linea.confirmacion_pagos c
											WHERE
												($localizacion is NULL or c.localizacion = $localizacion) and
												($fechaInicio is NULL or c.fecha_documento >=$fechaInicio) and
												($fechaFin is NULL or c.fecha_documento <=$fechaFin ) and
												c.estado='activo' and
												(SELECT 
													count(d.id_detalle_confirmacion_pago)
  												FROM 
													g_servicios_linea.detalle_confirmacion_pagos d 
												WHERE 
													d.id_confirmacion_pago=c.id_confirmacion_pago and 
													d.estado='activo')>0 
											ORDER BY 1  ".$busqueda.";");
		return $res;
	}
	
	public function obtenerRegistroConfirmacionPagoUsuario ($conexion, $mes, $anio, $identificador,$filtro=null){
		$mes = $mes != "" ? "'" .  $mes  . "'" : "NULL";
		$anio = $anio!="" ? "'" . $anio . "'"  : "NULL";
		if($filtro==null)
			$busqueda = " limit 0";
		$res = $conexion->ejecutarConsulta("SELECT 
												to_char(cp.fecha_documento,'MM') fecha_documento_mes,
												to_char(cp.fecha_documento,'YYYY') fecha_documento_anio
											FROM 
												g_servicios_linea.confirmacion_pagos cp, g_servicios_linea.detalle_confirmacion_pagos dcp
											WHERE
												cp.id_confirmacion_pago=dcp.id_confirmacion_pago and 
												cp.estado='activo' and
												dcp.estado='activo' and
												dcp.identificador_beneficiario = '$identificador' and
												($mes is NULL or to_char(cp.fecha_documento,'MM') =$mes) and
												($anio is NULL or to_char(cp.fecha_documento,'YYYY') =$anio)
											GROUP BY 1,2 
											ORDER BY 2,1 ".$busqueda." ;");
		return $res;
	}
	
	public function obtenerRegistroConfirmacionPagoConsolidado ($conexion, $localizacion ,$mes, $anio){
		$localizacion = $localizacion != "" ? "'" .  $localizacion  . "'" : "NULL";
		$mes = $mes != "" ? "'" .  $mes  . "'" : "NULL";
		$anio = $anio!="" ? "'" . $anio . "'"  : "NULL";
		$res = $conexion->ejecutarConsulta("SELECT 
												localizacion,
												to_char(fecha_documento,'MM') fecha_documento_mes,
												to_char(fecha_documento,'YYYY') fecha_documento_anio
											FROM 
												g_servicios_linea.confirmacion_pagos 
											WHERE
												($localizacion is NULL or  localizacion = $localizacion) and
												($mes is NULL or to_char(fecha_documento,'MM') =$mes) and
												($anio is NULL or to_char(fecha_documento,'YYYY') =$anio) and
												estado='activo'
											GROUP BY 1,2,3 
											ORDER BY 2,3,1;");
						return $res;
	}
	
	public function buscarDatosConfirmacionPago($conexion,  $idConfirmacionPago){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_confirmacion_pago, identificador_responsable, localizacion, 
       											ruta, fecha_registro, to_char(fecha_documento,'DD/MM/YYYY') fecha_documento, 
												estado, identificador_cambio_estado
										 	FROM 
												g_servicios_linea.confirmacion_pagos
											WHERE
												id_confirmacion_pago=$idConfirmacionPago ;");
		return $res;
	}
	
	public function buscarDetalleConfirmacionPago ($conexion,  $idConfirmacionPago,$usuario){
		$res = $conexion->ejecutarConsulta("SELECT
												mc.id_detalle_confirmacion_pago, mc.id_confirmacion_pago, mc.num_trans_cur, 
       											mc.descripcion, mc.identificador_beneficiario, mc.nombre_beneficiario, 
       											to_char(mc.fecha_pago,'DD/MM/YYYY') fecha_pago,mc.monto_pago, mc.banco,
												--mc.tipo_pago,
												 mc.correo_enviado
											FROM
												g_servicios_linea.confirmacion_pagos ma,
												g_servicios_linea.detalle_confirmacion_pagos mc
											WHERE
												ma.id_confirmacion_pago=mc.id_confirmacion_pago and
												ma.estado='activo' and mc.estado='activo' and
												ma.id_confirmacion_pago=$idConfirmacionPago and 
												mc.identificador_beneficiario='$usuario';");
		return $res;
	}
	
	public function buscarTipoPagoPorIdMatrizUsuario($conexion,  $idConfirmacionPago,$usuario){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_detalle_confirmacion_pago
											FROM
												g_servicios_linea.confirmacion_pagos ma,
												g_servicios_linea.detalle_confirmacion_pagos mc
											WHERE
												ma.id_confirmacion_pago=mc.id_confirmacion_pago and
												ma.id_confirmacion_pago=$idConfirmacionPago and 
												mc.identificador_beneficiario='$usuario';");
		return $res;
	}
	
	public function buscarConfirmacionPagoConsolidado ($conexion,  $localizacion,$fecha){
		$res = $conexion->ejecutarConsulta("SELECT DISTINCT 
												mc.identificador_beneficiario,
												mc.nombre_beneficiario
											FROM 
 												g_servicios_linea.confirmacion_pagos ma, 
												g_servicios_linea.detalle_confirmacion_pagos mc 
  											WHERE 
												ma.id_confirmacion_pago=mc.id_confirmacion_pago and 
												localizacion = '$localizacion' and 
  												to_char(fecha_documento,'MM-YYYY') ='$fecha' and 
												ma.estado='activo' and 
												mc.estado='activo';");
		return $res;
	}
	
	public function buscarDetalleConfirmacionPagoConsolidado ($conexion,  $localizacion,$fecha,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT 
												mc.id_detalle_confirmacion_pago, mc.id_confirmacion_pago, mc.num_trans_cur, 
												mc.descripcion, mc.identificador_beneficiario, mc.nombre_beneficiario, 
												to_char(mc.fecha_pago,'DD/MM/YYYY') fecha_pago,mc.monto_pago, mc.banco, --mc.tipo_pago, 
												mc.correo_enviado
											FROM 
												g_servicios_linea.confirmacion_pagos ma, g_servicios_linea.detalle_confirmacion_pagos mc
											WHERE
												ma.id_confirmacion_pago=mc.id_confirmacion_pago and
												localizacion = '$localizacion' and
												to_char(fecha_documento,'MM-YYYY') ='$fecha' and
												mc.identificador_beneficiario='$identificador' and
												ma.estado='activo' and
												mc.estado='activo'
											ORDER BY 
												5,1;");
		return $res;
	}
	
	public function buscarDetalleConfirmacionPagoUsuario ($conexion,  $mes,$anio,$usuario){
		$res = $conexion->ejecutarConsulta("SELECT 
												mc.id_detalle_confirmacion_pago, mc.id_confirmacion_pago, mc.num_trans_cur, 
      											mc.descripcion,  mc.nombre_beneficiario, to_char(mc.fecha_pago,'DD/MM/YYYY') fecha_pago,
												mc.monto_pago, mc.banco, --mc.tipo_pago, 
												mc.correo_enviado 
											FROM 
  												g_servicios_linea.confirmacion_pagos ma, g_servicios_linea.detalle_confirmacion_pagos mc 
  											WHERE 
												ma.id_confirmacion_pago=mc.id_confirmacion_pago and
  												to_char(fecha_documento,'MM-YYYY') ='$mes-$anio' and 
												ma.estado='activo' and mc.estado='activo' and 
												mc.identificador_beneficiario='$usuario';");
		return $res;
	}
	
	
	public function verificarArchivoExistente ($conexion,  $localizacion,$fecha){
		$res = $conexion->ejecutarConsulta("SELECT 
												id_confirmacion_pago, identificador_responsable, localizacion, ruta, fecha_registro, fecha_documento
											FROM 
												g_servicios_linea.confirmacion_pagos 
											WHERE 
												localizacion='$localizacion' and 
												to_char(fecha_documento,'DD/MM/YYYY')='$fecha' and 
												estado='activo';");
		return $res;
	}
	
	public function actualizarEstadoConfirmacionPago ($conexion,  $localizacion,$fecha,$estado,$identificador){
		$conexion->ejecutarConsulta("UPDATE 
												g_servicios_linea.confirmacion_pagos
											SET 
												estado='$estado', identificador_cambio_estado='$identificador'
	 										WHERE
												localizacion='$localizacion' and
												to_char(fecha_documento,'DD/MM/YYYY')='$fecha' and estado='activo';");
	}
	
	public function actualizarEstadoDetalleConfirmacionPago($conexion,$idDetallConfirmacionPago,$identificador){
		$conexion->ejecutarConsulta("UPDATE
												g_servicios_linea.detalle_confirmacion_pagos
										   SET 
												estado='inactivo', identificador_cambio_estado='$identificador'
											 WHERE 
												id_detalle_confirmacion_pago=$idDetallConfirmacionPago;");
	}
	
	public function actualizarEstadoEliminarConfirmacionPago($conexion,$idConfirmacionPago,$identificador){
		
		$conexion->ejecutarConsulta("UPDATE 
										g_servicios_linea.confirmacion_pagos
									SET  
										estado='inactivo', identificador_cambio_estado='$identificador'
									WHERE 
										id_confirmacion_pago=$idConfirmacionPago;");
		
		$conexion->ejecutarConsulta("UPDATE
										g_servicios_linea.detalle_confirmacion_pagos
									SET 
										estado='inactivo', identificador_cambio_estado='$identificador'
									WHERE
										id_confirmacion_pago=$idConfirmacionPago and estado='activo';");
	
	}
	
	public function obtenerIdConfirmacionPagoConsolidado($conexion,$fecha,$localizacion){
		$res=$conexion->ejecutarConsulta("SELECT 
											id_confirmacion_pago
										FROM 
											g_servicios_linea.confirmacion_pagos 
										WHERE
											localizacion='$localizacion' and
				 							to_char(fecha_documento,'MM-YYYY')='$fecha' and 
											estado='activo';");
		return $res;
					
	}
	
	public function buscarGARutasTransporte ($conexion, $provincia, $canton, $oficina, $estado=null, $filtro=null, $idRutaTransporte=null){
		$provincia = $provincia != "" ? "'" .  $provincia  . "'" : "NULL";
		$canton = $canton != "" ? "'" .  $canton  . "'" : "NULL";
		$oficina = $oficina != "" ? "'" .  $oficina  . "'" : "NULL";
		$estado = $estado != "" ? "'" .  $estado  . "'" : "NULL";
		$idRutaTransporte = $idRutaTransporte != "" ? "'" .  $idRutaTransporte  . "'" : "NULL";
		if($filtro==null)
			$busqueda = " limit 0";

		
		$res = $conexion->ejecutarConsulta("SELECT 
												id_ruta_transporte,	nombre_ruta, id_provincia, provincia, id_canton, canton, id_oficina,oficina, sector, conductor, telefono, 
												administrador_grupo, telefono_administrador, capacidad_vehiculo, numero_pasajeros, placa_vehiculo, descripcion_vehiculo, estado
									 		FROM 
												g_servicios_linea.rutas_transporte
											WHERE
												($provincia is NULL or  id_provincia = $provincia) and
												($canton is NULL or  id_canton = $canton) and
												($oficina is NULL or  id_oficina = $oficina) and
												($estado is NULL or  estado = $estado) and 
												estado not in ('eliminado') and
												($idRutaTransporte is NULL or  id_ruta_transporte = $idRutaTransporte)
												ORDER BY 1 DESC ".$busqueda." ;");
		return $res;
	}
	
	public function buscarRutasTransporte ($conexion, $provincia, $canton, $oficina, $filtro=null, $idRutaTransporte=null){
		$provincia = $provincia != "" ? "'" .  $provincia  . "'" : "NULL";
		$canton = $canton != "" ? "'" .  $canton  . "'" : "NULL";
		$oficina = $oficina != "" ? "'" .  $oficina  . "'" : "NULL";
		$idRutaTransporte = $idRutaTransporte != "" ? "'" .  $idRutaTransporte  . "'" : "NULL";
		
		
		if($filtro==null)
			$busqueda = " limit 0";

		$res = $conexion->ejecutarConsulta("SELECT 
												id_ruta_transporte,	nombre_ruta, id_provincia, provincia, id_canton, canton, id_oficina, oficina, sector, conductor, telefono, 
												administrador_grupo, telefono_administrador, capacidad_vehiculo, numero_pasajeros, placa_vehiculo, descripcion_vehiculo, estado
											FROM 
												g_servicios_linea.rutas_transporte
											WHERE
												($provincia is NULL or  id_provincia = $provincia) and
												($canton is NULL or  id_canton = $canton) and
												($oficina is NULL or  id_oficina = $oficina) and
												estado not in ('eliminado','inactivo') and
												($idRutaTransporte is NULL or  id_ruta_transporte = $idRutaTransporte)
											ORDER BY 4 DESC ".$busqueda." ;");
		return $res;
	}
	
	public function buscarDetalleRutaTransporte ($conexion, $idRutaTransporte){
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_servicios_linea.detalle_rutas_transporte dr 
											WHERE 
												dr.id_ruta_transporte='$idRutaTransporte' 
											ORDER BY 
												dr.orden 
											ASC;");
				return $res;
	}
	
	
	public function actualizarRutasTransporte($conexion,$identificadorResponsable,$nombreRuta,$idProvincia,$provincia,$idCanton,$canton,$idOficina,$oficina,$sector,$conductor, $telefono, $idRutaTransporte, $administradorGrupo,$telefonoAdministrador,$capacidadVehiculo,$numeroPasajeros,$placaVehiculo,$descripcionVehiculo,$estado){
		$conexion->ejecutarConsulta("UPDATE 
												g_servicios_linea.rutas_transporte
										  	SET  
												identificador_cambio_estado='$identificadorResponsable', 
										       	nombre_ruta='$nombreRuta', id_provincia='$idProvincia', provincia='$provincia', id_canton='$idCanton', canton='$canton', 
										       	id_oficina='$idOficina', oficina='$oficina', sector='$sector', conductor='$conductor', telefono='$telefono', administrador_grupo='$administradorGrupo', telefono_administrador='$telefonoAdministrador', capacidad_vehiculo='$capacidadVehiculo', numero_pasajeros='$numeroPasajeros' , placa_vehiculo='$placaVehiculo', descripcion_vehiculo='$descripcionVehiculo' , estado='$estado'
										 	WHERE 
												id_ruta_transporte=$idRutaTransporte;");
	
	}
	
	public function eliminarDetalleRutasTransporteXid($conexion,$idDetalleRuta){
		$conexion->ejecutarConsulta("DELETE FROM
												g_servicios_linea.detalle_rutas_transporte
											WHERE 
												id_detalle_rutas_transporte=$idDetalleRuta;");
	
	}

	public function eliminarRutasTransporte($conexion,$idRutaTransporte){
		$conexion->ejecutarConsulta("UPDATE 
										g_servicios_linea.rutas_transporte
   									SET 
										estado='eliminado', 
       									identificador_cambio_estado='$identificador'
 									WHERE 		
										id_ruta_transporte=$idRutaTransporte;");
	}
	
	public function buscarIdentificadorBeneficiarioMatriz($conexion,$idConfirmacionPago){
		$res = $conexion->ejecutarConsulta("SELECT
												distinct identificador_beneficiario, nombre_beneficiario
											FROM
												g_servicios_linea.confirmacion_pagos ma,
												g_servicios_linea.detalle_confirmacion_pagos mc
											WHERE
												ma.id_confirmacion_pago=mc.id_confirmacion_pago and
												ma.id_confirmacion_pago=$idConfirmacionPago and mc.estado='activo'
											ORDER BY 
												2 
											ASC;");
	
				return $res;
	}
	
	public function imprimirLineaDetalleConfirmacionPagos($idDetalleConfirmacionPago, $numTransCur,$descripcion,$fechaPago,$montoPago,$banco){
		return '<tr id="R'.$idDetalleConfirmacionPago . '">' .
					'<td>'.$numTransCur.'</td>' .
					'<td>'.$descripcion.'</td>' .
					'<td>'.$fechaPago.'</td>' .
					'<td>'.$montoPago.'</td>' .
					'<td>'.$banco.'</td>' .
					'<td align="center">' .
					'<form id="imprimirVariedad" class="borrar" data-rutaAplicacion="serviciosLinea" data-opcion="eliminarGFConfirmacionPagoDetalle">' .
					'<input type="hidden" name="idDetalleConfirmacionPago" value="' . $idDetalleConfirmacionPago . '" >' .
					'<button type="submit" class="icono"></button>' .
					'</form>'.
					'</td>'.
				'</tr>';
	}
	
	public function imprimirLineaDetalleConfirmacionPagoss($idProducto, $nombreComun,$idTipoOperacion, $multipleVariedad){
		return '<tr class="hola" id="R' . $idProducto . '-'.$idTipoOperacion.'">' .
				'<td>' . $idProducto . '</td>' .
				'<td>' . $nombreComun . '</td>' .
				'<td>' . $multipleVariedad . '</td>' .
				'<td>' .
				'<form id="imprimirVariedad" class="borrar" data-rutaAplicacion="administracionProductos" data-opcion="eliminarListaVariedadProducto"  >' .
				'<input type="hidden" name="idProducto" value="' . $idProducto . '" >' .
				'<input type="hidden" name="idTipoOperacion" value="' . $idTipoOperacion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function obtenerDatosUsuarioAgrocalidad($conexion,$identificador){
		$res = $conexion->ejecutarConsulta("SELECT
												identificador,
												Upper(nombre ||' '||apellido) as nombre_completo,
												mail_personal,
												mail_institucional,
												fecha_nacimiento
											FROM
												g_uath.ficha_empleado
											WHERE
												identificador='$identificador';");
	
		return $res;
	}
	
	public function imprimirLineaDetalleRutasTransporte($idDetalle,$latitud,$longitud,$direccion,$hora,$recorrido,$orden){
		return '<tr id="R' . $idDetalle . '">' .
				'<td class="contador">'.$orden.'</td>' .
					'<td>'.$latitud.'</td>' .
					'<td>'.$longitud.'</td>' .
					'<td>'.$direccion.'</td>' .
					'<td>'.$recorrido.'</td>'.
					'<td>'.$hora.'</td>' .
					'<td>'.
						'<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
						'<input type="hidden" name="idRegistro" value="' . $idDetalle . '" >' .
						'<input type="hidden" name="accion" value="BAJAR" >' .
						'<input type="hidden" name="tabla" value="detalleRutasTransporte" >' .
						'<button class="icono"></button>' .
						'</form>'.
					'</td>' .
					'<td>' .
						'<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
						'<input type="hidden" name="idRegistro" value="' . $idDetalle . '" >' .
						'<input type="hidden" name="accion" value="SUBIR" >' .
						'<input type="hidden" name="tabla" value="detalleRutasTransporte" >' .
						'<button class="icono"></button>' .
						'</form>'.
					'</td>' .
					'<td>' .
						'<form class="borrar" data-rutaAplicacion="serviciosLinea" data-opcion="eliminarGARecorridosDetalle">' .
						'<input type="hidden" name="idDetalleRuta" value="' . $idDetalle . '" >' .
						'<button type="submit" class="icono" ></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}
	
	public function autogenerarSecuenciaOrdenRecorridosDetalle($conexion,$tabla,$campo,$campoId,$valorId){
		$res = $conexion->ejecutarConsulta("SELECT
												MAX($campo)::numeric + 1 as numero
											FROM
												". $tabla ."
											WHERE
												".$campoId."=".$valorId.";");
		$res=pg_fetch_result($res, 0, 'numero') == ''?1:pg_fetch_result($res, 0, 'numero');
		return $res;
	}
	
}
?>
