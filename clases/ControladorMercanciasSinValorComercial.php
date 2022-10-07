<?php

class ControladorMercanciasSinValorComercial{
	
	
	
	public function numeracionDocumentos($conexion,$campo,$operador){
		
		$consulta="SELECT 
						count ($campo) +1 numeracion
					FROM
						 g_mercancias_valor_comercial.documentos
					WHERE
						 identificador_operador='$operador'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
		
	}
	
	public function guardarNuevaExportacion($conexion,$operador,$tipoSolicitud,$nPropietario,$identificadorPropietario,$direccionPropietario,$nDestinatario,$direccionDestinatario,$idLocalizacion,$npais,$idUso,$nUso,
											$fechaEmbarque,$idControl,$nControl,$idProvinciaControl,$nProvinciaControl, $tipoIdentificacion, $telefonoPropietario, $correoPropietario){

		$fechaSolicitud = date('d-m-Y H:i:s');
		$consulta="";

		$consulta="INSERT INTO 
						g_mercancias_valor_comercial.solicitudes(fecha_solicitud, identificador_operador, tipo_solicitud, nombre_propietario, 
						identificador_propietario, direccion_propietario,nombre_destinatario, direccion_destinatario, id_localizacion_origen_destino, 
						pais_origen_destino, id_uso, nombre_uso,fecha_embarque, id_lugar_control, puesto_control,id_pronvincia_control,nombre_provincia_control,estado,
						tipo_identificacion_propietario, telefono_propietario, correo_propietario)
			    	VALUES 
						('$fechaSolicitud', '$operador', '$tipoSolicitud', '$nPropietario', '$identificadorPropietario', '$direccionPropietario', '$nDestinatario', '$direccionDestinatario', 
						'$idLocalizacion', '$npais', '$idUso', '$nUso', '$fechaEmbarque', '$idControl', '$nControl', '$idProvinciaControl', '$nProvinciaControl', 'enviado',
						'$tipoIdentificacion', '$telefonoPropietario', '$correoPropietario') RETURNING id_solicitud;";
		
		$res = $conexion->ejecutarConsulta($consulta);

		return $res;
	}
	
	public function guardarDocumentos($conexion,$rutaVacuna,$rutaVeterinario,$rutaAnticuerpo, $idSolicitud, $rutaAutMinAmb, $rutaZoosanitario=null){

		$consulta="INSERT INTO
						g_mercancias_valor_comercial.documentos_adjuntos( ruta_vacuna, ruta_veterinario, ruta_anticuerpo, id_solicitud, ruta_autorizacion_min_ambiente, ruta_zoosanitario_exp)
				   VALUES
						('$rutaVacuna', '$rutaVeterinario', '$rutaAnticuerpo', '$idSolicitud', '$rutaAutMinAmb', '$rutaZoosanitario');";

		$res = $conexion->ejecutarConsulta($consulta);

		return $res;

	}

	public function guardarNuevaImportacion($conexion, $operador, $tipoSolicitud, $nPropietario, $idPropietario, $direccionPropietario, $idPais, $ndPais, $idUso, $nUso, $idPuerto, $nPuerto, $residencia, $fechaEmbarque,
											$idControl, $nControl, $idProvinciaControl, $nProvinciaControl, $idTransporte, $tipoIdentificacion, $telefonoPropietario, $correoPropietario){

		$fechaSolicitud = date('d-m-Y H:i:s');

			$consulta="INSERT INTO
				g_mercancias_valor_comercial.solicitudes(fecha_solicitud, identificador_operador, tipo_solicitud, nombre_propietario,
				identificador_propietario, direccion_propietario,id_localizacion_origen_destino,
				pais_origen_destino, id_uso, nombre_uso, id_puerto, nombre_puerto, direccion_ecuador,fecha_embarque, id_lugar_control, puesto_control, id_pronvincia_control, 
				nombre_provincia_control, id_medios_transporte, estado, tipo_identificacion_propietario, telefono_propietario, correo_propietario)
			VALUES
				('$fechaSolicitud', '$operador', '$tipoSolicitud', '$nPropietario', '$idPropietario', '$direccionPropietario',
				'$idPais', '$ndPais','$idUso','$nUso', '$idPuerto', '$nPuerto','$residencia', '$fechaEmbarque', '$idControl', '$nControl', '$idProvinciaControl', '$nProvinciaControl','$idTransporte','enviado'
				, '$tipoIdentificacion', '$telefonoPropietario', '$correoPropietario') RETURNING id_solicitud;";
	
		$res = $conexion->ejecutarConsulta($consulta);

		return $res;
	}
	
	public function obtenerProvinciaControl($conexion,$idLugar){
		$consulta="select 
						id_provincia 
					from
						g_catalogos.lugares_inspeccion
					where
						id_lugar=$idLugar";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function obtenerNombreProvinciaControl($conexion,$idProvincia){
		$consulta="select 
						nombre 
					from
						g_catalogos.localizacion
					where	
						id_localizacion=$idProvincia";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function verificarProductoExistente($conexion, $idProducto, $identicacionProducto, $idSolicitud){

		$consulta="SELECT 
						* 
					FROM 
						g_mercancias_valor_comercial.producto_solicitudes 
					WHERE 
						id_producto = '$idProducto'
						and identificacion_producto = '$identicacionProducto'
						and id_solicitud = '$idSolicitud';";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function guardarDetalleSolicitud($conexion,$idTipoPorducto,$nTipoPorducto,$idSubtipoPorducto,$nSubtipoPorducto,$idProducto,$nProducto,$sexo,$raza,$edad,$color,$identicacionProducto,$idSolicitud){

		$consulta="INSERT INTO
								g_mercancias_valor_comercial.producto_solicitudes(
					            id_tipo_producto, nombre_tipo, id_subtipo_producto, nombre_subtipo,	id_producto,
								nombre_producto, sexo, raza, edad, color, identificacion_producto,id_solicitud)
					    VALUES 
					   			('$idTipoPorducto','$nTipoPorducto','$idSubtipoPorducto','$nSubtipoPorducto', '$idProducto','$nProducto',
								'$sexo','$raza','$edad','$color','$identicacionProducto','$idSolicitud') RETURNING id_producto_solicitud;";

		$res = $conexion->ejecutarConsulta($consulta);

		return $res;
	}

	public function comprobarCodigo($conexion,$numero){

		$consulta="SELECT 
						 numero_lote, identificacion_producto
				  	 FROM
						 g_mercancias_valor_comercial.producto_solicitudes
				  	WHERE
						 numero_lote='$numero' or identificacion_producto='$numero'";

		$res = $conexion->ejecutarConsulta($consulta);
		
		 if (pg_num_rows($res)>0){
		 	echo "true";
		 }else{
		 	echo "false";
		 }
	}
	
	public function listaSolicitudes($conexion,$tipo,$identificacion,$numeroSolicitud,$fecha){
		
		$identificacion  = $identificacion!="" ? "'" . $identificacion. "'" : "NULL";
		$numeroSolicitud = $numeroSolicitud!="" ? "'" . $numeroSolicitud. "'" : "NULL";
		$fecha = $fecha!="" ? "'" . $fecha . "'"  : "NULL";
		
		if(($identificacion=="NULL") && ($numeroSolicitud=="NULL") && ($fecha=="NULL")){
			$busqueda = "and fecha_solicitud >= current_date and fecha_solicitud < current_date+1";
			
		}
				
		
		$consulta=" SELECT 
						  id_solicitud,
						  nombre_propietario as solicitante,
						  case when estado ='enviado' then 'Revisión Documental' when estado ='subsanacion' then 'Subsanar' 
						  when estado ='rechazado' then 'Rechazado' when estado ='pago' then 'Asignación de Tasa'
						  when estado ='inspeccion' then 'Inspección'  when estado ='aprobado' then 'Aprobado'
						  when estado ='verificacion' then 'Por pagar' when estado ='asignadoDocumental' then 'Revisión documental asignada'
						  when estado ='asignadoInspeccion' then 'Asignado a Inspector'
						  end estado, pais_origen_destino

					  FROM
						  g_mercancias_valor_comercial.solicitudes
					WHERE 
						  ($identificacion is NULL or ((case when identificador_propietario = '' then identificador_operador else identificador_propietario end) = $identificacion))
						  and ($numeroSolicitud is NULL or id_solicitud = $numeroSolicitud)
						  and ($fecha is NULL or to_char(fecha_solicitud,'dd/mm/yyyy') = $fecha)
						  $busqueda
						  and tipo_solicitud='$tipo'
						  ORDER BY 1;
					";		
	  
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
		
	public function obtenerSolicitud($conexion,$idSolicitud){
		$consulta="SELECT 
						*, case when estado ='enviado' then 'Revisión Documental' when estado ='subsanacion' then 'Subsanar' 
						  when estado ='rechazado' then 'Rechazado' when estado ='pago' then 'Asignación de Tasa'
						  when estado ='inspeccion' then 'Inspección'  when estado ='aprobado' then 'Aprobado'
						  when estado ='verificacion' then 'Por pagar' when estado ='asignadoDocumental' then 'Revisión documental asignada'
						  when estado ='asignadoInspeccion' then 'Asignado a Inspector'
						  end estado_solicitud
				  FROM 
						g_mercancias_valor_comercial.solicitudes
				  WHERE 
						id_solicitud='$idSolicitud'";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function abrirSolicitudAsignacion($conexion, $idSolicitud){

		$consulta="SELECT
						*,
						nombre_propietario as nombre_operador,
						case when estado ='enviado' then 'Revisión Documental' when estado ='subsanacion' then 'Subsanar' 
						when estado ='rechazado' then 'Rechazado' when estado ='pago' then 'Asignación de Tasa'
						when estado ='inspeccion' then 'Inspección'  when estado ='aprobado' then 'Aprobado'
						when estado ='verificacion' then 'Por pagar' when estado ='asignadoDocumental' then 'Revisión documental asignada'
						when estado ='asignadoInspeccion' then 'Asignado a Inspector'
						end estado_solicitud
					FROM
						g_mercancias_valor_comercial.solicitudes s
					WHERE
						id_solicitud='$idSolicitud'";

		$res = $conexion->ejecutarConsulta($consulta);

		return $res;

	}

	public function obtenerSolicitudProductosAsignacion ($conexion, $idSolicitud){
	
		$cid = $conexion->ejecutarConsulta("select
												nombre_producto
											from
												g_mercancias_valor_comercial.producto_solicitudes
											where
												id_solicitud = $idSolicitud");
	
		while ($fila = pg_fetch_assoc($cid)){
			$prod[] = $fila['nombre_producto'];
		}
	
		$res = implode(', ',$prod);
	
		return $res;
	}
	
	public function obtenerDetalleSolicitud($conexion,$idSolicitud){

		$consulta="SELECT
						ps.id_producto_solicitud, ps.id_tipo_producto, ps.nombre_tipo, ps.id_subtipo_producto,
						ps.nombre_subtipo, ps.id_producto, pr.nombre_comun,  ps.sexo,
						case when ps.sexo='M' then 'Macho' when ps.sexo='H' then 'Hembra' end sexo_completo,
						ps.raza, ps.edad, ps.color, ps.identificacion_producto, ps.id_solicitud
					FROM
						g_mercancias_valor_comercial.producto_solicitudes ps, g_catalogos.productos pr
					WHERE
						pr.id_producto=ps.id_producto
						and id_solicitud='$idSolicitud'
					ORDER BY 1";
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function obtenerDetallexId($conexion,$idDetalle){		
		$consulta="SELECT
						* 
					FROM
						g_mercancias_valor_comercial.producto_solicitudes
					WHERE
						id_producto_solicitud=$idDetalle";		
		
		$res = $conexion->ejecutarConsulta($consulta);
		return $res;
	}

	public function eliminarDetalle($conexion, $idDetalle){
		$consulta="DELETE FROM 
						g_mercancias_valor_comercial.producto_solicitudes
					WHERE
						id_producto_solicitud='$idDetalle'";
		$res = $conexion->ejecutarConsulta($consulta);
	}
	
	public function actualizarSolicitud($conexion,$idSolicitud,$tipoSolicitud,$nPropietario,$identificadorPropietario,$direccionPropietario,$nDestinatario,$direccionDestinatario,$idLocalizacion,$npais,$idUso,$nUso,
		$fechaEmbarque,$idControl,$nControl,$idProvinciaControl,$nProvinciaControl, $tipoIdentificacion, $telefonoPropietario, $correoPropietario){
		
			$consulta="UPDATE 
							g_mercancias_valor_comercial.solicitudes
						SET
							nombre_propietario='$nPropietario', identificador_propietario='$identificadorPropietario', direccion_propietario='$direccionPropietario',
							nombre_destinatario='$nDestinatario', direccion_destinatario='$direccionDestinatario', id_localizacion_origen_destino='$idLocalizacion',
							pais_origen_destino='$npais', id_uso='$idUso', nombre_uso='$nUso', fecha_embarque='$fechaEmbarque', id_lugar_control='$idControl',
							puesto_control='$nControl', id_pronvincia_control='$idProvinciaControl', nombre_provincia_control='$nProvinciaControl', estado='enviado',
							tipo_identificacion_propietario = '$tipoIdentificacion', telefono_propietario = '$telefonoPropietario', correo_propietario= '$correoPropietario'
						WHERE
							id_solicitud='$idSolicitud';";
		
		$res=$conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actualizarImportacion($conexion,$idSolicitud,$tipoSolicitud,$nPropietario,$identificadorPropietario,$direccionPropietario,$idLocalizacion,$npais,$idPuerto,$nPuerto,
		$residencia,$fechaEmbarque,$idControl,$nControl,$idProvinciaControl,$nProvinciaControl,$idTransporte,$idUso,$nUso, $tipoIdentificacion, $telefonoPropietario, $correoPropietario){
		
		$consulta="UPDATE
					g_mercancias_valor_comercial.solicitudes
					SET
						nombre_propietario='$nPropietario', identificador_propietario='$identificadorPropietario', direccion_propietario='$direccionPropietario',
						id_localizacion_origen_destino='$idLocalizacion', pais_origen_destino='$npais', id_uso='$idUso', nombre_uso='$nUso', id_puerto='$idPuerto', nombre_puerto='$nPuerto',
						direccion_ecuador='$residencia', fecha_embarque='$fechaEmbarque', id_lugar_control='$idControl',
						puesto_control='$nControl', id_pronvincia_control='$idProvinciaControl', nombre_provincia_control='$nProvinciaControl', id_medios_transporte='$idTransporte',
						estado='enviado', tipo_identificacion_propietario = '$tipoIdentificacion', telefono_propietario = '$telefonoPropietario', correo_propietario= '$correoPropietario'
					WHERE
						id_solicitud='$idSolicitud';";
		
		$res=$conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function cargarDocumentos($conexion,$idSolicitud){
		$consulta="SELECT 
						 *
				   FROM 
						g_mercancias_valor_comercial.documentos_adjuntos
				   WHERE
						 id_solicitud='$idSolicitud'";

		$res=$conexion->ejecutarConsulta($consulta);

		return $res;
	}
	
	public function actualizarDocumentos($conexion, $rutaVacuna, $rutaVeterinario, $rutaAnticuerpo, $rutaAutMinAmb, $idSolicitud, $rutaZoosanitario=null){

		$consulta="UPDATE 
						g_mercancias_valor_comercial.documentos_adjuntos
					SET
						ruta_vacuna='$rutaVacuna', 
						ruta_veterinario='$rutaVeterinario', 
						ruta_anticuerpo='$rutaAnticuerpo',
						ruta_autorizacion_min_ambiente = '$rutaAutMinAmb',
						ruta_zoosanitario_exp = '$rutaZoosanitario'
					WHERE
						id_solicitud='$idSolicitud';";

		$res=$conexion->ejecutarConsulta($consulta);

		return $res;
	}

	public function listarImportacionExportacionRevisionProvinciaRS ($conexion, $estado, $nombreProvincia, $tipoSolicitud){

		$res = $conexion->ejecutarConsulta("SELECT 
												distinct s.id_solicitud,
												identificador_operador,
												estado,
												tipo_solicitud as tipo_certificado,
												pais_origen_destino as pais,
												nombre_propietario as razon_social,
												fecha_solicitud as fecha_creacion
											FROM 
												g_mercancias_valor_comercial.solicitudes s,
												g_mercancias_valor_comercial.producto_solicitudes sp
											WHERE
												s.id_solicitud = sp.id_solicitud
												and UPPER(nombre_provincia_control) = UPPER('$nombreProvincia')
												and estado = '$estado'
												and tipo_solicitud IN $tipoSolicitud;");
		return $res;
	}
	
	public function listarImportacionExportacionRevisionRS ($conexion, $estado, $tipoSolicitud){
		
		$res = $conexion->ejecutarConsulta("SELECT
												distinct s.id_solicitud,
												identificador_operador,
												estado,
												tipo_solicitud as tipo_certificado,
												pais_origen_destino as pais,
												nombre_propietario as razon_social,
												fecha_solicitud as fecha_creacion
											FROM
												g_mercancias_valor_comercial.solicitudes s,
												g_mercancias_valor_comercial.producto_solicitudes sp
											WHERE
												s.id_solicitud = sp.id_solicitud
												and estado = '$estado'
												and tipo_solicitud IN $tipoSolicitud
											ORDER BY
												s.id_solicitud ASC;");
		return $res;
	}
	
	public function listarImportacionExportacionAsignadasInspectorRS ($conexion, $estado, $identificadorInspector, $tipoSolicitudCoordinador, $tipoInspector, $tipoSolicitud){		
			
		$res = $conexion->ejecutarConsulta("SELECT
												distinct s.id_solicitud,
												identificador_operador,
												s.estado,
												s.tipo_solicitud as tipo_certificado,
												pais_origen_destino as pais,
												nombre_propietario as razon_social,
												fecha_solicitud as fecha_creacion
											FROM
												g_mercancias_valor_comercial.solicitudes s,
												g_mercancias_valor_comercial.producto_solicitudes sp,
												g_revision_solicitudes.asignacion_coordinador ac
											WHERE
												s.id_solicitud = sp.id_solicitud and
												s.id_solicitud = ac.id_solicitud and
												ac.identificador_inspector = '$identificadorInspector' and
												ac.tipo_solicitud = '$tipoSolicitudCoordinador' and
												ac.tipo_inspector = '$tipoInspector' and
												s.estado = '$estado' and
												s.tipo_solicitud = '$tipoSolicitud';");
		return $res;
	}
	
	public function actualizarEstadoMercanciaSV($conexion, $estado, $idSolicitud){
			
		$res = $conexion->ejecutarConsulta("UPDATE
												g_mercancias_valor_comercial.solicitudes
											SET
												estado = '$estado'
											WHERE
												id_solicitud = $idSolicitud;");
		
		return $res;
		
	}
	
	public function generarFechaEmision($conexion,$idSolicitud){
		$fecha = date('d-m-Y H:i:s');		
		$consulta="UPDATE
						g_mercancias_valor_comercial.solicitudes
					SET
						fecha_emision = '$fecha'
					WHERE
						id_solicitud = $idSolicitud;";
		
		$res = $conexion->ejecutarConsulta($consulta);
		
		return $res;
	}
	
	public function actualizarObservacionMercanciaSV($conexion, $observacion, $idSolicitud){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_mercancias_valor_comercial.solicitudes
											SET
												observacion = '$observacion'
											WHERE
												id_solicitud = $idSolicitud;");
	
		return $res;
	
	}
	
	public function actualizarDetallePago($conexion, $detallePago, $idSolicitud){
		
		$res = $conexion->ejecutarConsulta("UPDATE
												g_mercancias_valor_comercial.solicitudes
											SET
												detalle_pago = '$detallePago'
											WHERE
												id_solicitud = $idSolicitud;");
		
		return $res;
		
	}
	
	public function obtenerOrdenPagoFactura($conexion,$idSolicitd){
		$consulta="SELECT
						orden_pago,factura
					FROM
						g_financiero.orden_pago
					WHERE
						id_solicitud='$idSolicitd'
						and (tipo_solicitud = 'mercanciasSinValorComercialExportacion' OR tipo_solicitud = 'mercanciasSinValorComercialImportacion')
						and estado IN (3,4)";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function guardarCertificadoZoosanitario($conexion,$ruta,$idSolicitd){
		$consulta="SELECT
						id_documento					
					FROM
						g_mercancias_valor_comercial.documentos_adjuntos
					WHERE
						id_solicitud=$idSolicitd";
		$res=$conexion->ejecutarConsulta($consulta);
		$fila = pg_fetch_row($res);
		
		if($fila>0){
		
			$consulta="UPDATE
							 g_mercancias_valor_comercial.documentos_adjuntos
					   SET 						 
					        ruta_zoosanitario='$ruta'
					   WHERE 
							id_solicitud='$idSolicitd'";
			
			$res=$conexion->ejecutarConsulta($consulta);
			return $res;
		
		} else{
			$consulta="INSERT INTO 
							g_mercancias_valor_comercial.documentos_adjuntos( ruta_zoosanitario, id_solicitud)
				   	   VALUES 
							('$ruta',$idSolicitd)";
			
			$res=$conexion->ejecutarConsulta($consulta);
			return $res;
		}
	}
	
	public function obtenerCertificadoZoosanitario($conexion,$idSolicitud){
		
		$consulta="SELECT
						ruta_zoosanitario
					FROM
						g_mercancias_valor_comercial.documentos_adjuntos da, g_mercancias_valor_comercial.solicitudes s
					WHERE
						da.id_solicitud='$idSolicitud'
						and s.estado in ('pago', 'verificacion', 'aprobado')
						and da.id_solicitud = s.id_solicitud";
		
		$res=$conexion->ejecutarConsulta($consulta);
		return $res;
	}
	
	public function actualizarComprobanteVeterinario($conexion, $idSolicitud, $rutaArchivo) {

		$consulta="UPDATE
						g_mercancias_valor_comercial.documentos_adjuntos
					SET
						ruta_comprobante_veterinario = '$rutaArchivo'
					WHERE
						id_solicitud='$idSolicitud';";
		
		$res=$conexion->ejecutarConsulta($consulta);

		return $res;
	}
	
	public function imprimirLineaProducto($idProductoSolicitud, $nombreTipoProducto, $nombreComun, $identificacionProducto){
		return '<tr id="R' . $idProductoSolicitud. '">' .
					'<td>'.$nombreTipoProducto.'</td>' .
					'<td>'.$nombreComun.'</td>' .
					'<td>'.$identificacionProducto.'</td>' .
					'<td>'.
						'<form class="borrar" data-rutaAplicacion="mercanciasSinValorComercial" data-opcion="eliminarDetalleSolicitud">' .
							'<input type="hidden" name="idProductoSolicitud" value="' . $idProductoSolicitud . '" >' .
							'<button type="submit" class="icono"></button>' .
						'</form>' .
					'</td>' .
				'</tr>';
	}

}