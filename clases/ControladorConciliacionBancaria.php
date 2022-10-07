<?php
class ControladorConciliacionBancaria{
	
	////////ADMINISTRACION TRAMAS
	
	public function guardarNuevoRegistroTrama ($conexion, $nombreTrama, $separadorTrama, $formatoEntradaTrama, $formatoSalidaTrama){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.trama(nombre_trama, separador_trama, formato_entrada_trama, formato_salida_trama)
											VALUES ('$nombreTrama','$separadorTrama','$formatoEntradaTrama', '$formatoSalidaTrama') RETURNING id_trama;");
			
		return $res;
	}
	
	public function guardarNuevoCabeceraTrama ($conexion, $idTrama, $codigoSegmentoCabeceraTrama, $tamanioSegmentoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.cabecera_trama(id_trama, codigo_segmento_cabecera_trama, tamanio_segmento_cabecera_trama)
											VALUES ($idTrama, '$codigoSegmentoCabeceraTrama','$tamanioSegmentoCabeceraTrama') RETURNING id_cabecera_trama;");
										
		return $res;
	}
	
	public function guardarNuevoDetalleTrama ($conexion, $idTrama, $codigoSegmentoDetalleTrama, $tamanioSegmentoDetalleTrama){	
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.detalle_trama(id_trama, codigo_segmento_detalle_trama, tamanio_segmento_detalle_trama)
											VALUES ($idTrama, '$codigoSegmentoDetalleTrama','$tamanioSegmentoDetalleTrama');");
			
		return $res;
	}
	
	public function actualizarRegistroTrama ($conexion, $idTrama, $nombreTrama, $separadorTrama, $formatoEntradaTrama, $formatoSalidaTrama){
	
		$res = $conexion->ejecutarConsulta("UPDATE 
												g_conciliacion_bancaria.trama SET nombre_trama = '$nombreTrama', separador_trama = '$separadorTrama', formato_entrada_trama = '$formatoEntradaTrama', formato_salida_trama = '$formatoSalidaTrama'
											WHERE
												id_trama = $idTrama;");
			
		return $res;
	}

	public function actualizarRegistroCabeceraTrama ($conexion, $idCabeceraTrama, $codigoSegmentoCabeceraTrama, $tamanioSegmentoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.cabecera_trama SET codigo_segmento_cabecera_trama = '$codigoSegmentoCabeceraTrama', tamanio_segmento_cabecera_trama = '$tamanioSegmentoCabeceraTrama'
											WHERE
												id_cabecera_trama = $idCabeceraTrama;");
													
				return $res;
	}
	
	public function actualizarCampoCabeceraTrama ($conexion, $idCampoCabeceraTrama, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, $tipoCampoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.campo_cabecera SET nombre_campo_cabecera = '$nombreCampoCabeceraTrama', posicion_inicial_campo_cabecera = '$posicionInicialCampoCabeceraTrama', posicion_final_campo_cabecera = '$posicionFinalCampoCabeceraTrama', 
												longitud_segmento_campo_cabecera = '$longitudSegmentoCampoCabeceraTrama', tipo_campo_cabecera = '$tipoCampoCabeceraTrama'
											WHERE
												id_campo_cabecera = $idCampoCabeceraTrama;");
											
		return $res;
	}
	
	public function actualizarRegistroDetalleTrama ($conexion, $idDetalleTrama, $codigoSegmentoDetalleTrama, $tamanioSegmentoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.detalle_trama SET codigo_segmento_detalle_trama = '$codigoSegmentoDetalleTrama', tamanio_segmento_detalle_trama = '$tamanioSegmentoDetalleTrama'
											WHERE
												id_detalle_trama = $idDetalleTrama;");
			
		return $res;
	}
	
	public function actualizarCampoDetalleTrama ($conexion, $idCampoDetalleTrama, $nombreCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, $tipoCampoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.campo_detalle SET nombre_campo_detalle = '$nombreCampoDetalleTrama', posicion_inicial_campo_detalle = '$posicionInicialCampoDetalleTrama', posicion_final_campo_detalle = '$posicionFinalCampoDetalleTrama',
												longitud_segmento_campo_detalle = '$longitudSegmentoCampoDetalleTrama', tipo_campo_detalle = '$tipoCampoDetalleTrama'
											WHERE
												id_campo_detalle = $idCampoDetalleTrama;");
			
		return $res;
	}
	
	
	///-----------------------------------
	
	public function listadoTramas ($conexion){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.trama
											WHERE
											estado_trama NOT IN ('eliminado');");
	
		return $res;
	}
	
	public function abrirTramaXIdTrama ($conexion, $idTrama){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.trama
											WHERE
												id_trama = $idTrama;");
	
		return $res;
	}
	
	public function abrirCabeceraTramaXIdTrama ($conexion, $idTrama){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.cabecera_trama
											WHERE
												id_trama = $idTrama;");
	
		return $res;
	}
	
	public function abrirDetalleTramaXIdTrama ($conexion, $idTrama){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.detalle_trama
											WHERE
												id_trama = $idTrama;");
									
		return $res;
	}
	
	public function verificarCampoCabeceraTrama ($conexion, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_cabecera
											WHERE
												nombre_campo_cabecera =  '$nombreCampoCabeceraTrama'
												or posicion_inicial_campo_cabecera = $posicionInicialCampoCabeceraTrama
												or posicion_final_campo_cabecera = $posicionFinalCampoCabeceraTrama;");
	
		return $res;
	}
	
	public function guardarCampoCabeceraTrama ($conexion, $idCabeceraTrama, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, $tipoCampoCabeceraTrama){

		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.campo_cabecera(id_cabecera_trama, nombre_campo_cabecera, posicion_inicial_campo_cabecera, posicion_final_campo_cabecera, longitud_segmento_campo_cabecera, tipo_campo_cabecera)
											VALUES 
												($idCabeceraTrama, '$nombreCampoCabeceraTrama', $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, '$tipoCampoCabeceraTrama') RETURNING id_campo_cabecera;");
										
		return $res;
	}
	
	public function verificarCampoDetalleTrama ($conexion, $nombreCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $campoFormaPagoCampoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM 
												g_conciliacion_bancaria.campo_detalle
											WHERE
												nombre_campo_detalle = '$nombreCampoDetalleTrama'
												or posicion_inicial_campo_detalle = $posicionInicialCampoDetalleTrama
												or posicion_final_campo_detalle = $posicionFinalCampoDetalleTrama
												and campo_forma_pago = '$campoFormaPagoCampoCabeceraTrama';");
	
		return $res;
	}
	
	public function guardarCampoDetalleTrama ($conexion, $idDetalleTrama, $nombreCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, $tipoCampoDetalleTrama, $campoFormaPagoCampoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.campo_detalle(id_detalle_trama, nombre_campo_detalle, posicion_inicial_campo_detalle, posicion_final_campo_detalle, longitud_segmento_campo_detalle, tipo_campo_detalle, campo_forma_pago)
											VALUES
												($idDetalleTrama, '$nombreCampoDetalleTrama',$posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, '$tipoCampoDetalleTrama', '$campoFormaPagoCampoCabeceraTrama') RETURNING id_campo_detalle;");
	
		return $res;
	}
	

	public function obtenerCamposCabeceraXIdCabeceraTrama ($conexion, $idCabeceraTrama){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_cabecera
											WHERE
												id_cabecera_trama = $idCabeceraTrama
											ORDER BY orden;");
									
		return $res;
	}
	
	public function imprimirLineaCampoCabecera ($idCampoCabeceraTrama, $nombreCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $tipoCampoCabeceraTrama, $idTrama){
		return '<tr id="R' . $idCampoCabeceraTrama . '">' .
				'<td width="100%">' .
				$nombreCampoCabeceraTrama .
				'</td>' .
				'<td width="100%">' .
				$longitudSegmentoCampoCabeceraTrama .
				'</td>' .
				'<td width="100%">' .
				$posicionInicialCampoCabeceraTrama .
				'</td>' .
				'<td width="100%">' .
				$posicionFinalCampoCabeceraTrama .
				'</td>' .
				'<td width="100%">' .
				$tipoCampoCabeceraTrama .
				'</td>' .
				'<td>' .
				'<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoCabeceraTrama . '" >' .
				'<input type="hidden" name="accion" value="BAJAR" >' .
				'<input type="hidden" name="tabla" value="campo_cabecera" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoCabeceraTrama . '" >' .
				'<input type="hidden" name="accion" value="SUBIR" >' .
				'<input type="hidden" name="tabla" value="campo_cabecera" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="conciliacionBancaria" data-opcion="nuevoCatalogoCabeceraTrama" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idCampoCabeceraTrama" value="' . $idCampoCabeceraTrama . '" >' .
				'<input type="hidden" name="idTrama" value="' . $idTrama . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarCamposCabecera">' .
				'<input type="hidden" name="opcion" value="campoCabeceraTrama" >' .
				'<input type="hidden" name="idCampoCabeceraTrama" value="' . $idCampoCabeceraTrama . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarCampoCabeceraTrama ($conexion, $idCampoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.campo_cabecera
											WHERE
												id_campo_cabecera = $idCampoCabeceraTrama;");
			
		return $res;
	}

	public function eliminarCampoDetalleTrama ($conexion, $idCampoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.campo_detalle
											WHERE
												id_campo_detalle = $idCampoDetalleTrama;");
			
		return $res;
	}	
	
	public function obtenerCamposCabeceraXIdCampoCabeceraTrama ($conexion, $idCampoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_cabecera
											WHERE
												id_campo_cabecera = $idCampoCabeceraTrama;");
											
		return $res;
	}	
	
	public function obtenerCamposDetalleXIdCampoDetalleTrama ($conexion, $idCampoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_detalle
											WHERE
												id_campo_detalle = $idCampoDetalleTrama;");
											
		return $res;
	}
		
	////CATALOGOS CABECERA-------------------------------------------
	
	public function obtenerCatalogosCampoCabeceraXIdCampo ($conexion, $idCampoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.catalogo_campo_cabecera
											WHERE
												id_campo_cabecera = $idCampoCabeceraTrama;");
			
		return $res;
	}
	
	public function obtenerCatalogosCampoDetalleXIdCampo ($conexion, $idCampoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.catalogo_campo_detalle
											WHERE
												id_campo_detalle = $idCampoDetalleTrama;");
			
		return $res;
	}

	public function verificarCatalogoCampoCabeceraTrama ($conexion, $idCampoCabeceraTrama, $codigoCatalogoCabeceraTrama, $nombreCatalogoCabeceraTrama){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.catalogo_campo_cabecera
											WHERE
												id_campo_cabecera = $idCampoCabeceraTrama
												or codigo_catalogo_campo_cabecera = '$codigoCatalogoCabeceraTrama'
												or nombre_catalogo_campo_cabecera = '$nombreCatalogoCabeceraTrama';");
			
		return $res;
	}
	
	public function guardarCatalogoCampoCabeceraTrama ($conexion, $idCampoCabeceraTrama, $codigoCatalogoCabeceraTrama, $nombreCatalogoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.catalogo_campo_cabecera(id_campo_cabecera, codigo_catalogo_campo_cabecera, nombre_catalogo_campo_cabecera)
											VALUES
												($idCampoCabeceraTrama, '$codigoCatalogoCabeceraTrama','$nombreCatalogoCabeceraTrama') RETURNING id_catalogo_campo_cabecera;");
									
		return $res;
	}

	public function verificarCatalogoCampoDetalleTrama ($conexion, $idCampoDetalleTrama, $codigoCatalogoDetalleTrama, $nombreCatalogoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.catalogo_campo_detalle
											WHERE
												id_campo_detalle = $idCampoDetalleTrama
												or codigo_catalogo_campo_detalle = '$codigoCatalogoDetalleTrama'
												or nombre_catalogo_campo_detalle = '$nombreCatalogoDetalleTrama';");
			
		return $res;
	}
	
	public function guardarCatalogoCampoDetalleTrama ($conexion, $idCampoDetalleTrama, $codigoCatalogoDetalleTrama, $nombreCatalogoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.catalogo_campo_detalle(id_campo_detalle, codigo_catalogo_campo_detalle, nombre_catalogo_campo_detalle)
											VALUES
												($idCampoDetalleTrama, '$codigoCatalogoDetalleTrama','$nombreCatalogoDetalleTrama') RETURNING id_catalogo_campo_detalle;");
			
		return $res;
	}	
	
	public function imprimirLineaCatalogoCampoCabecera ($idCatalogoCampoCabeceraTrama, $codigoCatalogoCabeceraTrama, $nombreCatalogoCabeceraTrama){
		return '<tr id="R' . $idCatalogoCampoCabeceraTrama . '">' .
				'<td width="100%">' .
				$codigoCatalogoCabeceraTrama .
				'</td>' .
				'<td width="100%">' .
				$nombreCatalogoCabeceraTrama .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarCatalogosCampo">' .
				'<input type="hidden" name="opcion" value="catalogoCampoCabeceraTrama" >' .
				'<input type="hidden" name="idCatalogoCampoCabeceraTrama" value="' . $idCatalogoCampoCabeceraTrama . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarCatalogoCampoCabeceraTrama ($conexion, $idCatalogoCampoCabeceraTrama){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.catalogo_campo_cabecera
											WHERE
												id_catalogo_campo_cabecera = $idCatalogoCampoCabeceraTrama;");
			
		return $res;
	}	
	
	public function eliminarCatalogoCampoDetalleTrama ($conexion, $idCatalogoCampoDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.catalogo_campo_detalle
											WHERE
												id_catalogo_campo_detalle = $idCatalogoCampoDetalleTrama;");
			
		return $res;
	}
	

	public function imprimirLineaCatalogoCampoDetalle ($idCatalogoCampoDetalleTrama, $codigoCatalogoDetalleTrama, $nombreCatalogoDetalleTrama){
		return '<tr id="R' . $idCatalogoCampoDetalleTrama . '">' .
				'<td width="100%">' .
				$codigoCatalogoDetalleTrama .
				'</td>' .
				'<td width="100%">' .
				$nombreCatalogoDetalleTrama .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarCatalogosCampo">' .
				'<input type="hidden" name="opcion" value="catalogoCampoDetalleTrama" >' .
				'<input type="hidden" name="idCatalogoCampoDetalleTrama" value="' . $idCatalogoCampoDetalleTrama . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
///------------------------------------PARA DETALLE DE CABECERA--------------------------------------------///

	public function obtenerCamposDetalleXIdDetalleTrama ($conexion, $idDetalleTrama){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_detalle
											WHERE
												id_detalle_trama = $idDetalleTrama
											ORDER BY orden;");
			
		return $res;
	}
	
	public function imprimirLineaCampoDetalle ($idCampoDetalleTrama, $nombreCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoDetalleTrama, $tipoCampoDetalleTrama/*, $idCampoCabeceraTrama*/, $idTrama = null){
		return '<tr id="R' . $idCampoDetalleTrama . '">' .
				'<td width="100%">' .
				$nombreCampoDetalleTrama .
				'</td>' .
				'<td width="100%">' .
				$longitudSegmentoCampoDetalleTrama .
				'</td>' .
				'<td width="100%">' .
				$posicionInicialCampoCabeceraTrama .
				'</td>' .
				'<td width="100%">' .
				$posicionFinalCampoDetalleTrama .
				'</td>' .
				'<td width="100%">' .
				$tipoCampoDetalleTrama .
				'</td>' .
				'<td>' .
				'<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoDetalleTrama . '" >' .
				'<input type="hidden" name="accion" value="BAJAR" >' .
				'<input type="hidden" name="tabla" value="campo_detalle" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoDetalleTrama . '" >' .
				'<input type="hidden" name="accion" value="SUBIR" >' .
				'<input type="hidden" name="tabla" value="campo_detalle" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="conciliacionBancaria" data-opcion="nuevoCatalogoDetalleTrama" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idCampoDetalleTrama" value="' . $idCampoDetalleTrama . '" >' .
				'<input type="hidden" name="idTrama" value="' . $idTrama . '" >' .
				'<button class="icono" type="submit" ></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarCamposCabecera">' .
				'<input type="hidden" name="opcion" value="campoDetalleTrama" >' .
				'<input type="hidden" name="idCampoDetalleTrama" value="' . $idCampoDetalleTrama . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	

/////////DOCUMENTOS/////////////////////////////////////

	public function guardarNuevoRegistroDocumento ($conexion, $nombreDocumento, $tipoDocumento, $formatoEntradaDocumento, $numeroColumnasDocumento, $filaInicioLecturaDocumento, $columnaInicioLecturaDocumento){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.documento(nombre_documento, tipo_documento, formato_entrada_documento, numero_columnas_documento, fila_inicio_lectura_documento, columna_inicio_lectura_documento)
											VALUES 
												('$nombreDocumento','$tipoDocumento', '$formatoEntradaDocumento', $numeroColumnasDocumento, $filaInicioLecturaDocumento, $columnaInicioLecturaDocumento) RETURNING id_documento;");
			
		return $res;
	}
	
	
	public function listadoDocumentos ($conexion){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.documento
											WHERE
											estado_documento NOT IN ('eliminado');");
	
		return $res;
	}
	
	public function abrirDocumentoXIdDocumento ($conexion, $idDocumento){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.documento
											WHERE
												id_documento = $idDocumento;");
	
		return $res;
	}
	
	public function actualizarRegistroDocumento ($conexion, $idDocumento, $nombreDocumento, $tipoDocumento, $formatoEntradaDocumento, $numeroColumnasDocumento, $filaInicioLecturaDocumento, $columnaInicioLecturaDocumento){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.documento SET nombre_documento = '$nombreDocumento', tipo_documento = '$tipoDocumento', formato_entrada_documento = '$formatoEntradaDocumento', numero_columnas_documento = '$numeroColumnasDocumento', fila_inicio_lectura_documento = '$filaInicioLecturaDocumento', columna_inicio_lectura_documento = '$columnaInicioLecturaDocumento'
											WHERE
												id_documento = $idDocumento;");
					
				return $res;
	}

	public function verificarCampoDocumento ($conexion, $nombreCampoDocumento, $posicionCampoDocumento){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_documento
											WHERE
												nombre_campo_documento = '$nombreCampoDocumento'
												or posicion_campo_documento = '$posicionCampoDocumento';");
			
		return $res;
	}
	
	public function guardarCampoDocumento ($conexion, $idDocumento, $nombreCampoDocumento, $posicionCampoDocumento, $tipoCampoDocumento){

		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.campo_documento(id_documento, nombre_campo_documento, posicion_campo_documento, tipo_campo_documento)
											VALUES
												($idDocumento, '$nombreCampoDocumento',$posicionCampoDocumento, '$tipoCampoDocumento') RETURNING id_campo_documento;");
									
		return $res;
	}
	
	public function obtenerCamposDocumentoXIdDocumento ($conexion, $idDocumento){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*, 'documento'::text as tipo_campo, nombre_campo_documento as nombre_campo
											FROM
												g_conciliacion_bancaria.campo_documento
											WHERE
												id_documento = $idDocumento
											ORDER BY orden;");
			
		return $res;
	}	
	
	public function imprimirLineaCampoDocumento ($idCampoDocumento, $nombreCampoDocumento, $posicionCampoDocumento, $tipoCampoDocumento){
		return '<tr id="R' . $idCampoDocumento . '">' .
				'<td width="100%">' .
				$nombreCampoDocumento .
				'</td>' .
				'<td width="100%">' .
				$posicionCampoDocumento .
				'</td>' .
				'<td width="100%">' .
				$tipoCampoDocumento .
				'</td>' .
				'<td>' .
				'<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoDocumento . '" >' .
				'<input type="hidden" name="accion" value="BAJAR" >' .
				'<input type="hidden" name="tabla" value="campo_documento" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoDocumento . '" >' .
				'<input type="hidden" name="accion" value="SUBIR" >' .
				'<input type="hidden" name="tabla" value="campo_documento" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarCamposDocumento">' .
				'<input type="hidden" name="idCampoDocumento" value="' . $idCampoDocumento . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarCampoDocumento ($conexion, $idCampoDocumento){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.campo_documento
											WHERE
												id_campo_documento = $idCampoDocumento;");
											
		return $res;
	}
	
	///////REGISTRO PROCESO CONCILIACION------------
	
	public function guardarNuevoRegistroProcesoConciliacion ($conexion, $nombreRegistroProcesoConciliacion, $facturaRegistroProcesoConciliacion, $tipoRevisionRegistroProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.registro_proceso_conciliacion(nombre_registro_proceso_conciliacion, factura_registro_proceso_conciliacion, tipo_revision_registro_proceso_conciliacion)
											VALUES
												('$nombreRegistroProcesoConciliacion','$facturaRegistroProcesoConciliacion','$tipoRevisionRegistroProcesoConciliacion') RETURNING id_registro_proceso_conciliacion;");
			
		return $res;
	}
	
	public function listadoRegistroProcesoConciliacion ($conexion){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.registro_proceso_conciliacion
											WHERE
												estado_registro_proceso_conciliacion NOT IN ('eliminado');");
	
		return $res;
	}
	
	
	public function listadoProcesoConciliacion ($conexion, $tipoProceso, $anio, $mes, $dia){
	    
	    $tipoProceso = $tipoProceso != "" ? "'" . $tipoProceso ."'" : "NULL";
	    $anio = $anio != "" ? "'" . $anio ."'" : "NULL";
	    $mes = $codigoTrampa != "" ? "'%" . str_pad($mes, 2, "0", STR_PAD_LEFT) . "%'" : "NULL";
	    $dia = $dia != "" ? "'" . str_pad($dia, 2, "0", STR_PAD_LEFT) . "'" : "NULL";
	    
	    $res = $conexion->ejecutarConsulta("SELECT
												pc.id_proceso_conciliacion,  rpc.nombre_registro_proceso_conciliacion, anio_proceso_conciliacion||'-'||LPAD(mes_proceso_conciliacion::text,2,'0')||'-'||LPAD(dia_proceso_concilicacion::text,2,'0') as fecha_conciliacion
											FROM
												g_conciliacion_bancaria.proceso_conciliacion pc,
												g_conciliacion_bancaria.registro_proceso_conciliacion rpc
											WHERE
												pc.id_registro_proceso_conciliacion = rpc.id_registro_proceso_conciliacion
												and estado_proceso_conciliacion NOT IN ('eliminado')
												and ($tipoProceso is NULL or rpc.factura_registro_proceso_conciliacion = $tipoProceso)
												and ($anio is NULL or pc.anio_proceso_conciliacion = $anio)
												and ($mes is NULL or pc.mes_proceso_conciliacion = $mes)
												and ($dia is NULL or pc.dia_proceso_concilicacion = $dia);");
	    
	    return $res;
	}
	
	public function abrirProcesoConciliacionXidProcesoConciliacion ($conexion, $idProcesoConciliacion){
			
		$res = $conexion->ejecutarConsulta("SELECT
												rpc.nombre_registro_proceso_conciliacion, pc.anio_proceso_conciliacion,LPAD(pc.mes_proceso_conciliacion::text,2,'0') as mes_proceso_conciliacion, LPAD(pc.dia_proceso_concilicacion::text,2,'0') as dia_proceso_concilicacion, pc.total_recaudado
											FROM
												g_conciliacion_bancaria.proceso_conciliacion pc,
												g_conciliacion_bancaria.registro_proceso_conciliacion rpc
											
											WHERE
												pc.id_registro_proceso_conciliacion = rpc.id_registro_proceso_conciliacion
												and pc.id_proceso_conciliacion = $idProcesoConciliacion
												and estado_registro_proceso_conciliacion NOT IN ('eliminado');");
	
		return $res;
	}
	
	public function abrirTotalesBancosXidProcesoConciliacion ($conexion, $idProcesoConciliacion){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.proceso_conciliacion pc,
												g_conciliacion_bancaria.totales_bancos_proceso_conciliacion tbpc,
												g_conciliacion_bancaria.bancos_proceso_conciliacion bpc,
												g_catalogos.entidades_bancarias eb 
											WHERE
												pc.id_proceso_conciliacion = tbpc.id_proceso_conciliacion
												and tbpc.id_banco_proceso_conciliacion = bpc.id_banco_proceso_conciliacion
												and bpc.id_banco = eb.id_banco
												and pc.id_proceso_conciliacion = $idProcesoConciliacion;");
	
				return $res;
	}
	
	public function abrirResultadoProcesoConciliacionXidProcesoConciliacion ($conexion, $idProcesoConciliacion){
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.resultado_proceso_conciliacion
											WHERE
												id_proceso_conciliacion = $idProcesoConciliacion;");
	
		return $res;
	}
	
	
	public function abrirRegistroProcesoConciliacionXIdRegistroProcesoConciliacion ($conexion, $idRegistroProcesoConciliacion){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.registro_proceso_conciliacion
											WHERE
												id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion;");
	
		return $res;
	}
	
	
	public function actualizarRegistroProcesoConciliacion ($conexion, $idRegistroProcesoConciliacion, $nombreRegistroProcesoConciliacion, $facturaRegistroProcesoConciliacion, $tipoRevisionRegistroProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.registro_proceso_conciliacion SET nombre_registro_proceso_conciliacion = '$nombreRegistroProcesoConciliacion', factura_registro_proceso_conciliacion = '$facturaRegistroProcesoConciliacion', tipo_revision_registro_proceso_conciliacion = '$tipoRevisionRegistroProcesoConciliacion'
											WHERE
												id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion;");
			
		return $res;
	}
	
	
	public function obtenerDocumentosProcesoConciliacionXIdRegistroProcesoConciliacion ($conexion, $idRegistroProcesoConciliacion){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM
												(SELECT
												dpc1.id_documento_proceso_conciliacion, dpc1.id_documento_entrada_proceso_conciliacion, dpc1.id_registro_proceso_conciliacion, dpc1.tipo_documento_proceso_conciliacion, tr.nombre_trama as nombre_documento_entrada_proceso_conciliacion, tr.formato_entrada_trama as formato_documento_entrada_proceso_conciliacion
													FROM
												g_conciliacion_bancaria.trama tr,
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc1
													WHERE
												tr.id_trama = dpc1.id_documento_entrada_proceso_conciliacion
												and dpc1.tipo_documento_proceso_conciliacion = 'trama'
												and dpc1.id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion) as tablaTrama
												
											UNION
											
											SELECT 
												* 
											FROM
												(SELECT
													dpc1.id_documento_proceso_conciliacion, dpc1.id_documento_entrada_proceso_conciliacion, dpc1.id_registro_proceso_conciliacion, dpc1.tipo_documento_proceso_conciliacion, dc.nombre_documento as nombre_documento_entrada_proceso_conciliacion, dc.formato_entrada_documento as formato_documento_entrada_proceso_conciliacion
												FROM
													g_conciliacion_bancaria.documento dc,
													g_conciliacion_bancaria.documentos_proceso_conciliacion dpc1
												WHERE
													dc.id_documento = dpc1.id_documento_entrada_proceso_conciliacion
													and dpc1.tipo_documento_proceso_conciliacion = 'documento'
													and dpc1.id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion) as tablaDocumento;");
																
				return $res;
	}	
	
	//TODO: VERIFICAR SI NO BORRAR
	
	/*public function comprobarCamposCabeceraTrama ($conexion, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama){ TODO: PARA CUANDO SE VALIDE LSO DATOS DE INGRESO
			
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_cabecera
											WHERE
												nombre_campo_cabecera = '$nombreCampoCabeceraTrama'
												or posicion_inicial_campo_cabecera = $posicionInicialCampoCabeceraTrama
												or posicion_final_campo_cabecera = $posicionFinalCampoCabeceraTrama;");
	
		return $res;
	}*/

	public function verificarDocumentosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $tipoDocumentoUtilizarProcesoConciliacion, $documentoEntradaUtilizarProcesoConciliacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.documentos_proceso_conciliacion
											WHERE
												id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion
												and tipo_documento_proceso_conciliacion = '$tipoDocumentoUtilizarProcesoConciliacion'
												and id_documento_entrada_proceso_conciliacion = $documentoEntradaUtilizarProcesoConciliacion;");
			
		return $res;
	}
	
	public function guardarDocumentosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $tipoDocumentoUtilizarProcesoConciliacion, $documentoEntradaUtilizarProcesoConciliacion){

		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.documentos_proceso_conciliacion(id_registro_proceso_conciliacion, tipo_documento_proceso_conciliacion, id_documento_entrada_proceso_conciliacion)
											VALUES
												($idRegistroProcesoConciliacion, '$tipoDocumentoUtilizarProcesoConciliacion',$documentoEntradaUtilizarProcesoConciliacion) RETURNING id_documento_proceso_conciliacion;");
			
		return $res;
	}
			
	public function imprimirListaDocumentosUtilizarProcesoConciliacion ($idDocumentoUtilizadoProcesoConciliacion, $tipoDocumentoUtilizadoProcesoConciliacion, $documentoEntradaUtilizadoProcesoConciliacion, $nombreDocumentoEntradaUtilizadoProcesoConciliacion){
		return '<tr id="R' . $idDocumentoUtilizadoProcesoConciliacion . '">' .
				'<td>' .
				$tipoDocumentoUtilizadoProcesoConciliacion .
				'</td>' .
				'<td><input type="hidden" name="idDocumentoUtilizadoProcesoConciliacion" value="' . $documentoEntradaUtilizadoProcesoConciliacion . '">' .
				$nombreDocumentoEntradaUtilizadoProcesoConciliacion .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarDocumentosUtilizadosProcesoConciliacion">' .
				'<input type="hidden" name="idDocumentoUtilizadoProcesoConciliacion" value="' . $idDocumentoUtilizadoProcesoConciliacion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarDocumentoUtilizadoProcesoConciliacion ($conexion, $idDocumentoUtilizadoProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.documentos_proceso_conciliacion
											WHERE
												id_documento_proceso_conciliacion = $idDocumentoUtilizadoProcesoConciliacion;");
			
		return $res;
	}	

	public function verificarBancosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $entidadBancariaUtilizarProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.bancos_proceso_conciliacion
											WHERE
												id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion
												and id_banco = $entidadBancariaUtilizarProcesoConciliacion;");
			
		return $res;
	}
	
	public function guardarBancosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $entidadBancariaUtilizarProcesoConciliacion){

		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.bancos_proceso_conciliacion(id_registro_proceso_conciliacion, id_banco)
											VALUES
												($idRegistroProcesoConciliacion, $entidadBancariaUtilizarProcesoConciliacion) RETURNING id_banco_proceso_conciliacion;");
			
		return $res;
	}
	
	public function imprimirListaBancosProcesoConciliacion ($idBancoUtilizarProcesoConciliacion, $entidadBancariaUtilizarProcesoConciliacion){
		return '<tr id="R' . $idBancoUtilizarProcesoConciliacion . '">' .
				'<td width="100%">' .
				$entidadBancariaUtilizarProcesoConciliacion .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarBancosUtilizadosProcesoConciliacion">' .
				'<input type="hidden" name="idBancoUtilizadoProcesoConciliacion" value="' . $idBancoUtilizarProcesoConciliacion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function eliminarBancoUtilizadoProcesoConciliacion ($conexion, $idBancoUtilizadoProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.bancos_proceso_conciliacion
											WHERE
												id_banco_proceso_conciliacion = $idBancoUtilizadoProcesoConciliacion;");
			
		return $res;
	}
	
	public function obtenerBancoProcesoConciliacionXIdRegistroProcesoConciliacion ($conexion, $idRegistroProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.bancos_proceso_conciliacion bpc,
												g_catalogos.entidades_bancarias eb
											WHERE
												eb.id_banco = bpc.id_banco
												and id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion;");
			
		return $res;
	}
	
	public function obtenerTramasDocumentos ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												id_trama as id_documento, nombre_trama as nombre_documento, 'compararTrama'::text as tipo_documento 
											FROM
												g_conciliacion_bancaria.trama
											UNION
											SELECT 
												id_documento as id_documento, nombre_documento as nombre_documento, 'compararDocumento'::text as tipo_documento 
											FROM
												g_conciliacion_bancaria.documento;");
			
		return $res;
	}
	
	public function obtenerCamposTramasCabeceraDetalleXIdTrama ($conexion, $idDocumento){
		
		$res = $conexion->ejecutarConsulta("SELECT
												cctr.id_campo_cabecera as id_campo, ctr.codigo_segmento_cabecera_trama ||' - '|| cctr.nombre_campo_cabecera as nombre_campo_tipo, 'campoCabecera'::text as tipo_campo, cctr.nombre_campo_cabecera as nombre_campo  
											FROM
												g_conciliacion_bancaria.cabecera_trama ctr,
												g_conciliacion_bancaria.campo_cabecera cctr
											WHERE
												ctr.id_cabecera_trama = cctr.id_cabecera_trama
												and ctr.id_trama = $idDocumento
											UNION
											SELECT
												cdtr.id_campo_detalle as id_campo, dtr.codigo_segmento_detalle_trama ||' - '|| cdtr.nombre_campo_detalle  as nombre_campo_tipo, 'campoDetalle'::text as tipo_campo, cdtr.nombre_campo_detalle as nombre_campo  
											FROM
												g_conciliacion_bancaria.detalle_trama dtr,
												g_conciliacion_bancaria.campo_detalle cdtr
											WHERE
												dtr.id_detalle_trama = cdtr.id_detalle_trama
												and dtr.id_trama = $idDocumento;");
			
		return $res;
	}
	
	public function verificarCampoDocumentoCompararProcesoConciliacion ($conexion,  $idDocumentoProcesoConciliacion, $sistemaGuiaCamposComparar, $datosColumnaGuiaCamposComparar, $documentoReporteCamposComparar, $datosColumnaDocumentosCamposComparar){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion
											WHERE
												id_documento_proceso_conciliacion = $idDocumentoProcesoConciliacion
												and tipo_guia_comparar_proceso_conciliacion = '$sistemaGuiaCamposComparar'
												and campo_guia_comparar_proceso_conciliacion = '$datosColumnaGuiaCamposComparar'
												and id_documento_comparar_proceso_conciliacion = $documentoReporteCamposComparar
												and id_campo_documento_comparar_proceso_conciliacion = $datosColumnaDocumentosCamposComparar;");
											
		return $res;
	}
	
	public function guardarCampoDocumentoCompararProcesoConciliacion ($conexion, $idDocumentoProcesoConciliacion, $sistemaGuiaCamposComparar, $datosColumnaGuiaCamposComparar, $documentoReporteCamposComparar, $datosColumnaDocumentosCamposComparar, $actividadEjecutarCamposComparar, $tipoCampoProcesoConciliacion){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion(id_documento_proceso_conciliacion, tipo_guia_comparar_proceso_conciliacion, campo_guia_comparar_proceso_conciliacion, id_documento_comparar_proceso_conciliacion, id_campo_documento_comparar_proceso_conciliacion, tipo_comparacion_proceso_conciliacion, tipo_campo_proceso_conciliacion)
											VALUES
												($idDocumentoProcesoConciliacion, '$sistemaGuiaCamposComparar', '$datosColumnaGuiaCamposComparar', $documentoReporteCamposComparar, $datosColumnaDocumentosCamposComparar, '$actividadEjecutarCamposComparar', '$tipoCampoProcesoConciliacion') RETURNING id_campo_comparar_proceso_conciliacion;");
			
		return $res;
	}
	
	public function imprimirLineaCampoDocumentoCompararProcesoConciliacion ($idCampoDocumentoCompararProcesoConciliacion, $sistemaGuiaCamposComparar, $datosColumnaGuiaCamposComparar, $documentoReporteCamposComparar, $datosColumnaDocumentosCamposComparar, $actividadEjecutarCamposComparar){
		return '<tr id="R' . $idCampoDocumentoCompararProcesoConciliacion . '">' .
				'<td width="100%">' .
				$sistemaGuiaCamposComparar .
				'</td>' .
				'<td width="100%">' .
				$datosColumnaGuiaCamposComparar .
				'</td>' .
				'<td width="100%">' .
				$documentoReporteCamposComparar .
				'</td>' .
				'<td width="100%">' .
				$datosColumnaDocumentosCamposComparar .
				'</td>' .
				'<td width="100%">' .
				$actividadEjecutarCamposComparar .
				'</td>' .
				'<td>' .
				'<form class="bajar" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoDocumentoCompararProcesoConciliacion . '" >' .
				'<input type="hidden" name="accion" value="BAJAR" >' .
				'<input type="hidden" name="tabla" value="campo_documento" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="subir" data-rutaAplicacion="general" data-opcion="modificarOrdenamiento" >' .
				'<input type="hidden" name="idRegistro" value="' . $idCampoDocumentoCompararProcesoConciliacion . '" >' .
				'<input type="hidden" name="accion" value="SUBIR" >' .
				'<input type="hidden" name="tabla" value="campo_documento" >' .
				'<button class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarCamposCompararDocumentosProcesoConciliacion">' .
				'<input type="hidden" name="idCampoDocumentoCompararProcesoConciliacion" value="' . $idCampoDocumentoCompararProcesoConciliacion . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function obtenerColumnasCompararProcesoConciliacion ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion
											WHERE
												id_documento_proceso_conciliacion = $idDocumento;");
			
		return $res;
	}
	
	
	////ELIMINACONES
	
	public function eliminarRegistroTrama ($conexion, $idTrama){

		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.trama
											SET
												estado_trama = 'eliminado'
											WHERE
												id_trama = $idTrama;");
		return $res;
	}
	
	
	public function eliminarRegistroDocumento ($conexion, $idDocumento){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.documento
											SET
												estado_documento = 'eliminado'
											WHERE
												id_documento = $idDocumento;");
		return $res;
	}
	
	public function eliminarRegistroProcesoConciliacion ($conexion, $idRegistroProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.registro_proceso_conciliacion
											SET
												estado_registro_proceso_conciliacion = 'eliminado'
											WHERE
												id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion;");
		return $res;
	}
	
	public function obtenerNombresCamposDocumentosCompararXIdRegistroProcesoConciliacion ($conexion, $idRegistroProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												ccpc.id_campo_comparar_proceso_conciliacion, tipo_guia_comparar_proceso_conciliacion, campo_guia_comparar_proceso_conciliacion, id_documento_comparar_proceso_conciliacion, tipo_comparacion_proceso_conciliacion, (CASE WHEN ccpc.tipo_campo_proceso_conciliacion = 'campoDetalle' THEN (SELECT cd.nombre_campo_detalle FROM g_conciliacion_bancaria.campo_detalle cd WHERE cd.id_campo_detalle = ccpc.id_campo_documento_comparar_proceso_conciliacion)
												ELSE 
												   (CASE WHEN ccpc.tipo_campo_proceso_conciliacion = 'campoCabecera' THEN (SELECT cc.nombre_campo_cabecera FROM g_conciliacion_bancaria.campo_cabecera cc WHERE cc.id_campo_cabecera = ccpc.id_campo_documento_comparar_proceso_conciliacion)
												ELSE
												   (CASE WHEN ccpc.tipo_campo_proceso_conciliacion = 'documento' THEN (SELECT cdoc.nombre_campo_documento FROM g_conciliacion_bancaria.campo_documento cdoc WHERE cdoc.id_campo_documento = ccpc.id_campo_documento_comparar_proceso_conciliacion)	
												END)END)END) as nombre_campo,
												(CASE WHEN ccpc.tipo_campo_proceso_conciliacion = 'campoCabecera' OR ccpc.tipo_campo_proceso_conciliacion = 'campoDetalle'THEN (SELECT tr.nombre_trama FROM g_conciliacion_bancaria.trama tr WHERE tr.id_trama = dpc.id_documento_entrada_proceso_conciliacion)
												ELSE 
												   (CASE WHEN ccpc.tipo_campo_proceso_conciliacion = 'documento' THEN (SELECT doc.nombre_documento FROM g_conciliacion_bancaria.documento doc WHERE doc.id_documento = dpc.id_documento_entrada_proceso_conciliacion)	
												END)END) as nombre_documento  
											FROM 
												g_conciliacion_bancaria.registro_proceso_conciliacion rpc, 
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc, 
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion ccpc 
											WHERE 
												rpc.id_registro_proceso_conciliacion = dpc.id_registro_proceso_conciliacion 
												and dpc.id_documento_proceso_conciliacion = ccpc.id_documento_proceso_conciliacion 
												and rpc.id_registro_proceso_conciliacion = $idRegistroProcesoConciliacion;");
			return $res;
			
	}
	
	
	public function eliminarCampoDocumentoCompararProcesoConciliacion ($conexion, $idCampoDocumentoCompararProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion
											WHERE
												id_campo_comparar_proceso_conciliacion = $idCampoDocumentoCompararProcesoConciliacion;");
			
		return $res;
	}
	
	
	public function listadoBancosRegistroProcesoConciliacion ($conexion, $idProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.bancos_proceso_conciliacion bpc,
												g_catalogos.entidades_bancarias eb
											WHERE
												bpc.id_banco = eb.id_banco
												and bpc.id_registro_proceso_conciliacion = $idProcesoConciliacion;");
	
		return $res;
	}
	
	///PROCESO DE CONCILIACION
	
	public function guardarProcesoConciliacion ($conexion, $idRegistroProcesoConciliacion, $anioProcesoConciliacion, $mesProcesoConciliacion, $diaProcesoConciliacion, $totalRecaudado){

		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.proceso_conciliacion(id_registro_proceso_conciliacion, anio_proceso_conciliacion, mes_proceso_conciliacion, dia_proceso_concilicacion, total_recaudado)
											VALUES
												($idRegistroProcesoConciliacion, '$anioProcesoConciliacion', '$mesProcesoConciliacion', '$diaProcesoConciliacion', '$totalRecaudado') RETURNING id_proceso_conciliacion;");
			
		return $res;
	}		
	
	public function guardarTotalBancoProcesoConciliacion ($conexion, $idProcesoConciliacion, $idBancoProcesoConciliacion, $valor){
		
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.totales_bancos_proceso_conciliacion(id_proceso_conciliacion, id_banco_proceso_conciliacion, total_banco_proceso_conciliacion)
											VALUES
												($idProcesoConciliacion, $idBancoProcesoConciliacion, '$valor');");
		return $res;
	}

	public function guardarRutaDocumentoProcesoConciliacion ($conexion, $idProcesoCociliacion, $idDocumentoProcesoConciliacion, $ruta){

		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.rutas_documentos_proceso_conciliacion(id_proceso_conciliacion, id_documento_proceso_conciliacion, ruta_documento_proceso_conciliacion)
											VALUES
												($idProcesoCociliacion, $idDocumentoProcesoConciliacion, '$ruta');");
		return $res;
	}
	
	public function obtenerRutasDocumentosProcesoConciliacionXidProcesoConciliacion ($conexion, $idProcesoCociliacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.rutas_documentos_proceso_conciliacion
											WHERE
												id_proceso_conciliacion = '$idProcesoCociliacion';");
		return $res;
	}
	
	public function obtenerDocumentosRutasProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idProcesoCociliacion){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												(SELECT
													dpc.id_documento_proceso_conciliacion, dpc.id_documento_entrada_proceso_conciliacion, dpc.id_registro_proceso_conciliacion, dpc.tipo_documento_proceso_conciliacion, tr.nombre_trama as nombre_documento_entrada_proceso_conciliacion, tr.formato_entrada_trama as formato_documento_entrada_proceso_conciliacion, rdpc.id_ruta_documento_proceso_conciliacion, rdpc.ruta_documento_proceso_conciliacion
												FROM
													g_conciliacion_bancaria.trama tr,
													g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
													g_conciliacion_bancaria.rutas_documentos_proceso_conciliacion rdpc,
													g_conciliacion_bancaria.proceso_conciliacion pc
												WHERE
													tr.id_trama = dpc.id_documento_entrada_proceso_conciliacion
													and dpc.id_documento_proceso_conciliacion = rdpc.id_documento_proceso_conciliacion
													and dpc.tipo_documento_proceso_conciliacion = 'trama'
													and pc.id_proceso_conciliacion = rdpc.id_proceso_conciliacion
													and pc.id_proceso_conciliacion = $idProcesoCociliacion) as tablaTrama
										
											UNION
										
											SELECT 
												* 
											FROM
												(SELECT
													dpc.id_documento_proceso_conciliacion, dpc.id_documento_entrada_proceso_conciliacion, dpc.id_registro_proceso_conciliacion, dpc.tipo_documento_proceso_conciliacion, dc.nombre_documento as nombre_documento_entrada_proceso_conciliacion, dc.formato_entrada_documento as formato_documento_entrada_proceso_conciliacion, rdpc.id_ruta_documento_proceso_conciliacion, rdpc.ruta_documento_proceso_conciliacion
												FROM
													g_conciliacion_bancaria.documento dc,
													g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
													g_conciliacion_bancaria.rutas_documentos_proceso_conciliacion rdpc,
													g_conciliacion_bancaria.proceso_conciliacion pc
												WHERE
													dc.id_documento = dpc.id_documento_entrada_proceso_conciliacion
													and dpc.id_documento_proceso_conciliacion = rdpc.id_documento_proceso_conciliacion
													and dpc.tipo_documento_proceso_conciliacion = 'documento'
													and pc.id_proceso_conciliacion = rdpc.id_proceso_conciliacion
													and pc.id_proceso_conciliacion = $idProcesoCociliacion) as tablaDocumento");
		return $res;
	}	
	
	public function abrirDatosCamposCompararXIdProcesoConciliacion ($conexion, $idProcesoCociliacion, $codigoSegmento){
	
						
		$res = $conexion->ejecutarConsulta("/*SELECT
												*
											FROM
												g_conciliacion_bancaria.trama tr,
												g_conciliacion_bancaria.cabecera_trama ctr,
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion ccpc,
												g_conciliacion_bancaria.proceso_conciliacion pc												
											WHERE
												pc.id_registro_proceso_conciliacion = dpc.id_registro_proceso_conciliacion
												and dpc.id_documento_entrada_proceso_conciliacion = tr.id_trama
												and tr.id_trama = ctr.id_cabecera_trama
												and dpc.id_documento_proceso_conciliacion = ccpc.id_documento_proceso_conciliacion
												and dpc.tipo_documento_proceso_conciliacion = 'trama'
												and ccpc.tipo_campo_proceso_conciliacion = 'campoCabecera'
												and pc.id_proceso_conciliacion = $idProcesoCociliacion	
												
				
											
											UNION*/
											
											SELECT
												*
											FROM
												g_conciliacion_bancaria.trama tr,
												g_conciliacion_bancaria.detalle_trama dtr,
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion ccpc,
												g_conciliacion_bancaria.proceso_conciliacion pc												
											WHERE
												pc.id_registro_proceso_conciliacion = dpc.id_registro_proceso_conciliacion
												and dpc.id_documento_entrada_proceso_conciliacion = tr.id_trama
												and tr.id_trama = dtr.id_detalle_trama
												and dpc.id_documento_proceso_conciliacion = ccpc.id_documento_proceso_conciliacion
												and dpc.tipo_documento_proceso_conciliacion = 'trama'
												and ccpc.tipo_campo_proceso_conciliacion = 'campoDetalle'
												and pc.id_proceso_conciliacion = $idProcesoCociliacion
												and dtr.codigo_segmento_detalle_trama = '$codigoSegmento';");
													
		return $res;
	}
	
	
	public function abrirDatosCamposCompararXIdProcesoConciliacionXXX ($conexion, $idProcesoCociliacion, $codigoSegmento){

		$res = $conexion->ejecutarConsulta("								
											SELECT
												*
											FROM
												g_conciliacion_bancaria.trama tr,
												g_conciliacion_bancaria.detalle_trama dtr,
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion ccpc,
												g_conciliacion_bancaria.proceso_conciliacion pc,
												g_conciliacion_bancaria.campo_detalle cdt	
											WHERE
												pc.id_registro_proceso_conciliacion = dpc.id_registro_proceso_conciliacion
												and dpc.id_documento_entrada_proceso_conciliacion = tr.id_trama
												and tr.id_trama = dtr.id_detalle_trama
												and dpc.id_documento_proceso_conciliacion = ccpc.id_documento_proceso_conciliacion
												and dpc.tipo_documento_proceso_conciliacion = 'trama'
												and ccpc.tipo_campo_proceso_conciliacion = 'campoDetalle'
												and pc.id_proceso_conciliacion = $idProcesoCociliacion
												and dtr.codigo_segmento_detalle_trama = '$codigoSegmento'
												and dtr.id_detalle_trama = cdt.id_detalle_trama
												and ccpc.id_campo_documento_comparar_proceso_conciliacion = cdt.id_campo_detalle;");
					
				return pg_fetch_all($res);
	}
		
	
	
	public function abrirDatosCamposDetalleXIdProcesoConciliacion ($conexion, $idProcesoCociliacion, $codigoSegmento){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.trama tr,
												g_conciliacion_bancaria.detalle_trama dtr,
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
												g_conciliacion_bancaria.proceso_conciliacion pc,
												g_conciliacion_bancaria.campo_detalle cdt
											WHERE
												pc.id_registro_proceso_conciliacion = dpc.id_registro_proceso_conciliacion
												and dpc.id_documento_entrada_proceso_conciliacion = tr.id_trama
												and tr.id_trama = dtr.id_detalle_trama
												and dpc.tipo_documento_proceso_conciliacion = 'trama'
												and pc.id_proceso_conciliacion = $idProcesoCociliacion
												and dtr.codigo_segmento_detalle_trama = '$codigoSegmento'
												and dtr.id_detalle_trama = cdt.id_detalle_trama");
					
		return pg_fetch_all($res);
	}
	
	
	public function abrirDatosCatalogosCampos ($conexion, $idCampoDetalle, $valorCampo){
	
		$res = $conexion->ejecutarConsulta("SELECT
												ccdtr.nombre_catalogo_campo_detalle
											FROM
												g_conciliacion_bancaria.campo_detalle cdt,
												g_conciliacion_bancaria.catalogo_campo_detalle ccdtr
											WHERE
												cdt.id_campo_detalle = ccdtr.id_campo_detalle
												and cdt.id_campo_detalle = $idCampoDetalle 
												and ccdtr.codigo_catalogo_campo_detalle = '$valorCampo'");
					
		return $res;
	}
	
	
	
	public function abrirCamposCompararXIdCampo ($conexion, $idCampo, $idDocumento, $tipoCampo){
		
		$tabla = '';
		$busqueda = '';
		$campos = '';
		
		switch($tipoCampo){			
			case "campoCabecera": $campos = 'cc.*'; $tabla = 'g_conciliacion_bancaria.campo_cabecera cc,'; $busqueda = 'cc.id_campo_cabecera ='. $idCampo . ' and cc.id_campo_cabecera = ccpc.id_campo_documento_comparar_proceso_conciliacion and ccpc.tipo_campo_proceso_conciliacion = '. "'$tipoCampo'"; break;			
			case "campoDetalle": $campos = 'cd.*'; $tabla = 'g_conciliacion_bancaria.campo_detalle cd,'; $busqueda = 'cd.id_campo_detalle ='. $idCampo . ' and cd.id_campo_detalle = ccpc.id_campo_documento_comparar_proceso_conciliacion and ccpc.tipo_campo_proceso_conciliacion = '. "'$tipoCampo'"; break;
			
		}
	
			$res = $conexion->ejecutarConsulta("SELECT
													" . $campos . ", ccpc.tipo_guia_comparar_proceso_conciliacion, ccpc.campo_guia_comparar_proceso_conciliacion 
												FROM
													" . $tabla . "
													g_conciliacion_bancaria.campo_comparar_proceso_conciliacion ccpc													
												WHERE
													" . $busqueda . "
												
												and ccpc.id_documento_comparar_proceso_conciliacion = $idDocumento;");		
			return $res;
	}
	
	
	public function obtenerDatosConciliacionGuia($conexion, $datosConsultaGUIA, $numeroOrdenVue){
		
		$res = $conexion->ejecutarConsulta("SELECT 
												".$datosConsultaGUIA."
											FROM
												g_financiero.orden_pago
											WHERE
												numero_orden_vue = '$numeroOrdenVue';");
		
		return pg_fetch_all($res);
		
	}
	
	
	public function obtenerDatosCabeceraTrama($conexion, $idProcesoConciliacion){
		
			$res = $conexion->ejecutarConsulta( "SELECT
							distinct ctr.codigo_segmento_cabecera_trama
							FROM
							g_conciliacion_bancaria.cabecera_trama ctr,
							--g_conciliacion_bancaria.detalle_trama dtr,
							g_conciliacion_bancaria.trama tr,
							g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
							g_conciliacion_bancaria.proceso_conciliacion pc
							WHERE
							tr.id_trama = ctr.id_trama
							--and tr.id_trama = dtr.id_trama
							and dpc. id_documento_entrada_proceso_conciliacion = tr.id_trama
							and dpc.id_registro_proceso_conciliacion = pc.id_registro_proceso_conciliacion
							and pc.id_proceso_conciliacion = 24;");
	
			return $res;
	}
	
	public function obtenerDatosDetalleTrama($conexion, $idProcesoConciliacion){
		
		$res = $conexion->ejecutarConsulta( "SELECT
												distinct dtr.codigo_segmento_detalle_trama
											FROM
												--g_conciliacion_bancaria.cabecera_trama ctr,
												g_conciliacion_bancaria.detalle_trama dtr,
												g_conciliacion_bancaria.trama tr,
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
												g_conciliacion_bancaria.proceso_conciliacion pc
											WHERE
												--tr.id_trama = ctr.id_trama
												--and 
												tr.id_trama = dtr.id_trama
												and dpc. id_documento_entrada_proceso_conciliacion = tr.id_trama
												and dpc.id_registro_proceso_conciliacion = pc.id_registro_proceso_conciliacion
												and pc.id_proceso_conciliacion = $idProcesoConciliacion;");
	
		return $res;
	}
	
	
	public function abrirDatosCamposCompararXIdProcesoConciliacionDocumentos ($conexion, $idProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.documento dc,
												g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
												g_conciliacion_bancaria.campo_comparar_proceso_conciliacion ccpc,
												g_conciliacion_bancaria.proceso_conciliacion pc,
												g_conciliacion_bancaria.campo_documento cdc												
											WHERE
												pc.id_registro_proceso_conciliacion = dpc.id_registro_proceso_conciliacion
												and dpc.id_documento_entrada_proceso_conciliacion = dc.id_documento
												and dpc.id_documento_proceso_conciliacion = ccpc.id_documento_proceso_conciliacion
												and dc.id_documento = cdc.id_documento
												and ccpc.id_campo_documento_comparar_proceso_conciliacion = cdc.id_campo_documento
												and dpc.tipo_documento_proceso_conciliacion = 'documento'
												and ccpc.tipo_campo_proceso_conciliacion = 'documento'
												and pc.id_proceso_conciliacion = $idProcesoConciliacion;");
		return pg_fetch_all($res);
	}
	
	
	public function buscarIdPagoYFechaXIdVue ($conexion, $numeroOrdenVue){
		
		$res = $conexion->ejecutarConsulta("SELECT
												id_pago, fecha_orden_pago
											FROM
												g_financiero.orden_pago
											WHERE												
												numero_orden_vue = '$numeroOrdenVue';");
		return $res;
	}
	
	public function insertarFormaPagoGuia($conexion, $idPago, $idBanco, $banco, $transaccion, $valorDeposito, $fechaOrdenPago, $notaCredito, $idCuentaBancaria, $numeroCuenta=''){

	$res = $conexion->ejecutarConsulta("INSERT INTO
												g_financiero.detalle_forma_pago (id_pago, id_banco, institucion_bancaria, transaccion, valor_deposito, fecha_orden_pago, id_nota_credito, id_cuenta_bancaria, numero_cuenta) 
											VALUES 
												($idPago, $idBanco, '$banco', '$transaccion', '$valorDeposito', '$fechaOrdenPago', $notaCredito, $idCuentaBancaria, '$numeroCuenta');");
	
		return $res;
	
	}	
	
	public function actualizarFormaPagoGuia($conexion, $idPago, $idBanco, $banco, $transaccion, $valorDeposito, $fechaOrdenPago, $notaCredito, $idCuentaBancaria, $numeroCuenta){	
	
		$res = $conexion->ejecutarConsulta("UPDATE 
													g_financiero.detalle_forma_pago SET id_banco = $idBanco, institucion_bancaria = '$banco', transaccion = '$transaccion', valor_deposito = '$valorDeposito', fecha_orden_pago = '$fechaOrdenPago', id_nota_credito = $notaCredito, id_cuenta_bancaria = $idCuentaBancaria, numero_cuenta = '$numeroCuenta'
												WHERE
													id_pago = $idPago;");
		
		return $res;
	
	}
	
	public function buscarIdPagoFormaPago($conexion, $idPago){	

		$res = $conexion->ejecutarConsulta("SELECT
												id_pago
											FROM
												g_financiero.detalle_forma_pago
											WHERE												
												id_pago = $idPago;");
	
		return $res;
	
	}
	
	
	public function actualizarEstadoConciliacion($conexion, $numeroOrdenVue, $estadoConciliacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_financiero.orden_pago SET estado_conciliacion = '$estadoConciliacion'
											WHERE
												numero_orden_vue = '$numeroOrdenVue';");
	
				return $res;
	
	}
	
	public function guardarResultadoConciliacion ($conexion, $idProcesoConciliacion, $resultadoProcesoConciliacion, $rutaArchivoConciliacion){
	
		$res = $conexion->ejecutarConsulta("INSERT INTO
												g_conciliacion_bancaria.resultado_proceso_conciliacion (id_proceso_conciliacion, resultado, ruta_archivo_conciliacion)
											VALUES 
												($idProcesoConciliacion, '$resultadoProcesoConciliacion', '$rutaArchivoConciliacion');");
		return $res;
	}
	
	public function abrirProcesoConciliacionXIdRegistroProcesoConciliacion ($conexion, $idProcesoConciliacion){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_conciliacion_bancaria.proceso_conciliacion pc,
												g_conciliacion_bancaria.registro_proceso_conciliacion rpc
											WHERE
												pc.id_registro_proceso_conciliacion = rpc.id_registro_proceso_conciliacion
												and pc.id_proceso_conciliacion = $idProcesoConciliacion;");
									
		return $res;
	}
	
	public function eliminarProcesoConciliacion ($conexion, $idProcesoConciliacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_conciliacion_bancaria.proceso_conciliacion
											SET
												estado_proceso_conciliacion = 'eliminado'
											WHERE
												id_proceso_conciliacion = $idProcesoConciliacion;");
		return $res;
	}
	
	public function eliminarBorrarProcesoConciliacion ($conexion, $idProcesoConciliacion){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.proceso_conciliacion
											WHERE
												id_proceso_conciliacion = $idProcesoConciliacion;");
		return $res;

	}
	
	public function eliminarBorrarRutasProcesoConciliacion ($conexion, $idProcesoConciliacion){
		
		$res = $conexion->ejecutarConsulta("DELETE FROM
												g_conciliacion_bancaria.rutas_documentos_proceso_conciliacion
											WHERE
												id_proceso_conciliacion = $idProcesoConciliacion;");
		return $res;
	
	}
	
	public function abrirCampoDetalle($conexion, $idDetalleTrama, $campoFormaPago, $codigoCatalogo){
	    
		$res = $conexion->ejecutarConsulta( "SELECT
												*
											FROM				
												g_conciliacion_bancaria.campo_detalle cd,
												g_conciliacion_bancaria.catalogo_campo_detalle ccd
											WHERE
												cd.id_campo_detalle = ccd.id_campo_detalle and
												id_detalle_trama = $idDetalleTrama and
												campo_forma_pago = '$campoFormaPago' and codigo_catalogo_campo_detalle = '$codigoCatalogo';");
	
		return $res;
	}
	
	
	public function obtenerDatosCampoBanco($conexion, $idProcesoConciliacion, $codigoSegmentoDetalletrama){
	    
	    
	    $res = $conexion->ejecutarConsulta( "SELECT
                                            	distinct cdt.campo_forma_pago, tr.*, pc.*, pc.*, cdt.*, dtr.*
                                            FROM
                                            	g_conciliacion_bancaria.trama tr,
                                            	g_conciliacion_bancaria.detalle_trama dtr,
                                            	g_conciliacion_bancaria.documentos_proceso_conciliacion dpc,
                                            	g_conciliacion_bancaria.proceso_conciliacion pc,
                                            	g_conciliacion_bancaria.campo_detalle cdt
                                            WHERE
                                            	pc.id_registro_proceso_conciliacion = dpc.id_registro_proceso_conciliacion and
                                            	dpc.id_documento_entrada_proceso_conciliacion = tr.id_trama and
                                            	tr.id_trama = dtr.id_detalle_trama and                                            	
                                            	dpc.tipo_documento_proceso_conciliacion = 'trama' and                                            	
                                            	pc.id_proceso_conciliacion = $idProcesoConciliacion and dtr.codigo_segmento_detalle_trama = '$codigoSegmentoDetalletrama' and
                                            	dtr.id_detalle_trama = cdt.id_detalle_trama and cdt.campo_forma_pago = 'banco';");
	    return $res;
	}
	
	
	public function obtenerFacturasConciliadas($conexion, $estadoConciliacion){
	    
	    
	    $res = $conexion->ejecutarConsulta( "SELECT 
                                                count(*)
                                        	FROM 
                                                g_financiero.orden_pago
                                        	WHERE 
                                                estado_conciliacion = '$estadoConciliacion';");
	    return $res;
	}
	
	    
}
?>