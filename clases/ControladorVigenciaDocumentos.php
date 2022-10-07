<?php

class ControladorVigenciaDocumentos{

	
	public function verificarNombreVigenciaDocumento ($conexion, $nombreVigencia){
	
		$res = $conexion->ejecutarConsulta("SELECT
												nombre_vigencia_documento
											FROM
												g_vigencia_documento.cabecera_vigencia_documento
											WHERE
												nombre_vigencia_documento = '$nombreVigencia';");
		return $res;
	}
	
	
	public function verificarCabeceraVigenciaDocumento ($conexion, $tipoDocumento, $areaTematica, $idTipoOperacion){
	
		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_vigencia_documento.cabecera_vigencia_documento
											WHERE
												tipo_documento = '$tipoDocumento'
												and area_tematica_vigencia_documento = '$areaTematica'
												and id_tipo_operacion = $idTipoOperacion;");
		return $res;
	}	
	
	public function guardarNuevoVigenciaDocumento ($conexion, $nombreVigencia, $tipoDocumento, $areaTematica, $idTipoOperacion, $identificadorUsuario, $identificadorUsuarioModifica, $nivelLista, $etapaVigencia){

		$res = $conexion->ejecutarConsulta("INSERT INTO g_vigencia_documento.cabecera_vigencia_documento(
            									nombre_vigencia_documento, tipo_documento, area_tematica_vigencia_documento, id_tipo_operacion, identificador_creacion_vigencia_documento, identificador_modificacion_vigencia_documento, nivel_lista, etapa_vigencia)
    										VALUES ('$nombreVigencia', '$tipoDocumento', '$areaTematica', $idTipoOperacion, '$identificadorUsuario', '$identificadorUsuarioModifica', '$nivelLista', '$etapaVigencia')RETURNING id_vigencia_documento;");
		return $res;
	}
	
	public function guardarNuevoDetalleVigenciaDocumento ($conexion, $idVigenciaDocumento, $idTipoProducto = null, $idSubtipoProducto=null, $idProducto=null){

		$res = $conexion->ejecutarConsulta("INSERT INTO g_vigencia_documento.detalle_vigencia_documento(id_vigencia_documento, id_tipo_producto, id_subtipo_producto, id_producto)
											VALUES ($idVigenciaDocumento, $idTipoProducto, $idSubtipoProducto, $idProducto);");
		return $res;
	}
	
	public function listarVigenciaDocumento ($conexion){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_vigencia_documento.cabecera_vigencia_documento;");
				return $res;
	}
	
	public function obtenerCabeceraVigenciaDocumentoPorIdVigencia ($conexion, $idVigenciaDocumento){

		$res = $conexion->ejecutarConsulta("SELECT
												cvd.id_vigencia_documento, cvd.nombre_vigencia_documento, cvd.tipo_documento, cvd.area_tematica_vigencia_documento, top.id_tipo_operacion, cvd.tipo_documento, top.nombre as nombre_tipo_operacion, cvd.nivel_lista, cvd.etapa_vigencia
											FROM
												g_vigencia_documento.cabecera_vigencia_documento cvd,
												g_catalogos.tipos_operacion top
											WHERE
												id_vigencia_documento = $idVigenciaDocumento
												and cvd.id_tipo_operacion = top.id_tipo_operacion;");
		return $res;
	}
	
	public function obtenerDetalleVigenciaDocumentoPorIdVigencia ($conexion, $idVigenciaDocumento){

		$res = $conexion->ejecutarConsulta("SELECT
												dvd.id_vigencia_documento, tp.id_tipo_producto, tp.nombre as nombre_tipo_producto, stp.id_subtipo_producto, stp.nombre as nombre_subtipo_producto, p.id_producto, p.nombre_comun  
											FROM												
												g_vigencia_documento.detalle_vigencia_documento dvd,
												g_catalogos.tipo_productos tp,
												g_catalogos.subtipo_productos stp,
												g_catalogos.productos p
											WHERE
												dvd.id_tipo_producto = tp.id_tipo_producto and
												dvd.id_subtipo_producto = stp.id_subtipo_producto and
												dvd.id_producto = p.id_producto and
												dvd.id_vigencia_documento = $idVigenciaDocumento");
		return $res;
	}
	
	public function obtenerDetalleVigenciaDocumentoPorIdVigenciaPorIdSubtipoProducto ($conexion, $idVigenciaDocumento, $idSubtipoProducto){

		$res = $conexion->ejecutarConsulta("SELECT 
												distinct p.id_producto, p.nombre_comun,
												dvd.id_vigencia_documento,
												CASE WHEN dvd.id_vigencia_documento is null THEN 'NO' ELSE 'SI' END as seleccion 
											FROM 
												g_catalogos.subtipo_productos stp  FULL JOIN g_catalogos.productos p ON p.id_subtipo_producto = stp.id_subtipo_producto 
												FULL JOIN (SELECT id_producto, id_vigencia_documento FROM g_vigencia_documento.detalle_vigencia_documento WHERE id_vigencia_documento = $idVigenciaDocumento) as dvd ON dvd.id_producto = p.id_producto											
											WHERE 
												stp.id_subtipo_producto = $idSubtipoProducto
											ORDER BY p.nombre_comun ASC");
		return $res;
	}
	
	public function actualizarNombreVigenciaDocumentoXIdVigenciaDocumento ($conexion, $idVigenciaDocumento, $nombreVigencia){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_vigencia_documento.cabecera_vigencia_documento
											SET
												nombre_vigencia_documento = '$nombreVigencia'
											WHERE
												id_vigencia_documento = $idVigenciaDocumento;");
				return $res;
	}
	
	public function actualizarTipoOperacionVigenciaDocumentoXIdVigenciaDocumento ($conexion, $idVigenciaDocumento, $idTipoOperacion){
	
		$res = $conexion->ejecutarConsulta("UPDATE
												g_vigencia_documento.cabecera_vigencia_documento
											SET
												id_tipo_operacion = '$idTipoOperacion'
											WHERE
												id_vigencia_documento = $idVigenciaDocumento;");
		return $res;
	}
	
	public function actualizarCabeceraVigenciaDocumentoXIdVigenciaDocumento ($conexion, $idVigenciaDocumento, $tipoDocumento, $areaTematica, $identificadorUsuarioModifica, $nivelLista, $etapaVigencia){

		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vigencia_documento.cabecera_vigencia_documento
											SET 
												tipo_documento = '$tipoDocumento',
												area_tematica_vigencia_documento = '$areaTematica', 
												identificador_modificacion_vigencia_documento = '$identificadorUsuarioModifica',
												nivel_lista = '$nivelLista',
												etapa_vigencia = '$etapaVigencia'
											WHERE 
												id_vigencia_documento = $idVigenciaDocumento;");
		return $res;
	}	
		
	public function eliminarDetalleVigenciaDocumentoPorIdVigencia ($conexion, $idVigenciaDocumento){
	
		$res = $conexion->ejecutarConsulta("DELETE 
											FROM 
												g_vigencia_documento.detalle_vigencia_documento
 											WHERE 
												id_vigencia_documento = $idVigenciaDocumento;");
		return $res;
	}

	
	public function imprimirLineaDeclararVigenciaDocumento ($idVigenciaDocumento, $idVigenciaDeclarada, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia, $estadoVigenciaDeclarada){
		
		if($valorTiempoVigencia == 1){
			switch ($tipoTiempoVigencia){
				
				case 'anio':
					$tipoTiempo = 'año';
				break;
				
				case 'mes':
					$tipoTiempo = 'mes';
				break;
					
				case 'dia':
					$tipoTiempo = 'día';
				break;
				
			}
		}elseif($valorTiempoVigencia > 1){
			switch ($tipoTiempoVigencia){
			
				case 'anio':
					$tipoTiempo = 'años';
					break;
			
				case 'mes':
					$tipoTiempo = 'meses';
					break;
						
				case 'dia':
					$tipoTiempo = 'días';
					break;
			
			}
		}		
				
		return '<tr id="R' . $idVigenciaDeclarada . '">' .
				'<td>' .
				$valorTiempoVigencia .' '. $tipoTiempo.
				'</td>' .
				'<td width="100%">' .
				$observacionVigencia.
				'</td>' .				
				'<td>' .
				'<form class="abrir" data-rutaAplicacion="administracionVigenciaDocumentos" data-opcion="abrirVigenciaDeclarada" data-destino="detalleItem" data-accionEnExito="NADA" >' .
				'<input type="hidden" name="idVigenciaDocumento" value="' . $idVigenciaDocumento . '" >' .
				'<input type="hidden" name="idVigenciaDeclarada" value="' . $idVigenciaDeclarada . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="'.$estadoVigenciaDeclarada.'" data-rutaAplicacion="administracionVigenciaDocumentos" data-opcion="actualizarEstadoVigenciaDeclarada">' .
				'<input type="hidden" name="idVigenciaDeclarada" value="' . $idVigenciaDeclarada . '" >' .
				'<input type="hidden" id="estadoRequisito" name="estadoRequisito" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'<td>' .
				'<form class="borrar" data-rutaAplicacion="administracionVigenciaDocumentos" data-opcion="eliminarVigenciaDeclarada">' .
				'<input type="hidden" name="idVigenciaDocumento" value="' . $idVigenciaDocumento . '" >' .
				'<input type="hidden" name="idVigenciaDeclarada" value="' . $idVigenciaDeclarada . '" >' .
				'<input type="hidden" name="valorTiempoVigencia" value="' . $valorTiempoVigencia . '" >' .
				'<input type="hidden" name="tipoTiempoVigencia" value="' . $tipoTiempoVigencia . '" >' .
				'<button type="submit" class="icono"></button>' .
				'</form>' .
				'</td>' .
				'</tr>';
	}
	
	public function obtenerVigenciaDeclaradaPorIdVigencia ($conexion, $idVigenciaDocumento){
		
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_vigencia_documento.vigencia_declarada
											WHERE
												id_vigencia_documento = $idVigenciaDocumento;");
		return $res;
	}
		
	public function buscarVigenciaDeclarada ($conexion, $idVigenciaDeclarada, $valorTiempoVigencia, $tipoTiempoVigencia){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_vigencia_documento.vigencia_declarada
											WHERE
												id_vigencia_documento = $idVigenciaDeclarada and
												valor_tiempo_vigencia_declarada = $valorTiempoVigencia and
												tipo_tiempo_vigencia_declarada = '$tipoTiempoVigencia';");
		return $res;
	}
	
	public function buscarVigenciaDeclaradaObservacion ($conexion, $idVigenciaDeclarada, $observacionVigencia){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_vigencia_documento.vigencia_declarada
											WHERE
												id_vigencia_declarada = $idVigenciaDeclarada and
												observacion_vigencia_declarada = '$observacionVigencia';");
		return $res;
	}
	
	public function actualizarVigenciaDeclaradaObservacion ($conexion, $idVigenciaDeclarada, $observacionVigencia){

		$res = $conexion->ejecutarConsulta("UPDATE g_vigencia_documento.vigencia_declarada
											 SET 
												observacion_vigencia_declarada = '$observacionVigencia'
											 WHERE 
												id_vigencia_declarada = '$idVigenciaDeclarada';");
		return $res;
	}
	
	public function guardarVigenciaDeclarada ($conexion, $idVigenciaDocumento, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia, $identificadorModificacion){

		$res = $conexion->ejecutarConsulta("INSERT INTO 
												g_vigencia_documento.vigencia_declarada(id_vigencia_documento, valor_tiempo_vigencia_declarada, tipo_tiempo_vigencia_declarada, observacion_vigencia_declarada, identificador_modificacion_vigencia_declarada)
    										VALUES ($idVigenciaDocumento, $valorTiempoVigencia, '$tipoTiempoVigencia', '$observacionVigencia', '$identificadorModificacion') RETURNING id_vigencia_declarada;");
		return $res;
	}
	
	public function eliminarVigenciaDeclarada ($conexion, $idVigenciaDeclarada){
	
		$res = $conexion->ejecutarConsulta("DELETE FROM 
												g_vigencia_documento.vigencia_declarada
 											WHERE
												id_vigencia_declarada = $idVigenciaDeclarada;");
				return $res;
	}
	
	public function actualizarEstadoVigenciaDeclarada ($conexion, $idVigenciaDeclarada, $estadoRequisito){
	
		$res = $conexion->ejecutarConsulta("UPDATE g_vigencia_documento.vigencia_declarada
											   SET estado_vigencia_declarada = '$estadoRequisito'
											 WHERE 
												id_vigencia_declarada = $idVigenciaDeclarada;");
				return $res;
	}
	
	public function obtenerVigenciaDeclaradaPorIdVigenciaDeclarada ($conexion, $idVigenciaDeclarada){
	
		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_vigencia_documento.vigencia_declarada
											WHERE
												id_vigencia_declarada = $idVigenciaDeclarada;");
		return $res;
	}
	
	public function actualizarVigenciaDeclarada ($conexion, $idVigenciaDeclarada, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia, $identificador_modificacion){

		$res = $conexion->ejecutarConsulta("UPDATE 
												g_vigencia_documento.vigencia_declarada
   											SET 
												id_vigencia_declarada = $idVigenciaDeclarada, valor_tiempo_vigencia_declarada = $valorTiempoVigencia, 
								       			tipo_tiempo_vigencia_declarada = '$tipoTiempoVigencia', observacion_vigencia_declarada = '$observacionVigencia', 
								       			identificador_modificacion_vigencia_declarada = '$identificador_modificacion'
											WHERE
												id_vigencia_declarada = $idVigenciaDeclarada;");
		return $res;
	}
	
	public function verificarVigenciaDeclarada ($conexion, $idVigenciaDeclarada, $idVigenciaDocumento, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia){

		$res = $conexion->ejecutarConsulta("SELECT 
												*
											FROM 
												g_vigencia_documento.vigencia_declarada
											WHERE
												id_vigencia_declarada = $idVigenciaDeclarada and
												id_vigencia_documento = $idVigenciaDocumento and
												valor_tiempo_vigencia_declarada = $valorTiempoVigencia and 
												tipo_tiempo_vigencia_declarada = '$tipoTiempoVigencia' and
												observacion_vigencia_declarada = '$observacionVigencia';");
		return $res;
	}
	
	public function buscarTipoOperacionCabeceraVigencia ($conexion, $idTipoOperacion){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_vigencia_documento.cabecera_vigencia_documento cvd,
												g_vigencia_documento.vigencia_declarada vd 
											WHERE 
												cvd.id_vigencia_documento = vd.id_vigencia_documento and
												id_tipo_operacion = $idTipoOperacion;");
		return $res;
	}
	
	public function buscarVigenciaProducto ($conexion,$idVigencia, $idProducto){

		$res = $conexion->ejecutarConsulta("SELECT
												dv.id_vigencia_documento
											FROM
												g_vigencia_documento.cabecera_vigencia_documento cv, 
												g_vigencia_documento.detalle_vigencia_documento dv				
											WHERE 
												cv.id_vigencia_documento = dv.id_vigencia_documento and 
												dv.id_vigencia_documento = $idVigencia and 
												dv.id_producto = $idProducto ;");
		return $res;
	}
	
	public function verificarDetalleVigenciaDeclarada ($conexion, $idTipoProducto, $idSubtipoProducto, $idProducto){

		$res = $conexion->ejecutarConsulta("SELECT 
												id_detalle_vigencia_documento
											 FROM 
												g_vigencia_documento.detalle_vigencia_documento 
											WHERE
											  	id_tipo_producto = $idTipoProducto and
												id_subtipo_producto = $idSubtipoProducto and
												id_producto = $idProducto;");
		return $res;
	}
	
	public function transformarvalorTipoVigencia ($tipoTiempoVigencia){
	
		switch ($tipoTiempoVigencia){
				
			case 'anio':
				$tipoTiempo = 'year';
				break;
					
			case 'mes':
				$tipoTiempo = 'month';
				break;
		
			case 'dia':
				$tipoTiempo = 'day';
				break;
					
		}		
		
		return $tipoTiempo;
	}
	
	public function obtenerVigenciaDeclaradaPorIdVigenciaXEtapaVigencia ($conexion, $idVigenciaDocumento, $etapaVigencia){

		$res = $conexion->ejecutarConsulta("SELECT
												*
											FROM
												g_vigencia_documento.cabecera_vigencia_documento cvd,
												g_vigencia_documento.vigencia_declarada vd
											WHERE
												cvd.id_vigencia_documento = vd.id_vigencia_documento
												and vd.id_vigencia_documento = $idVigenciaDocumento
												and cvd.etapa_vigencia = '$etapaVigencia';");
		return $res;
	}
	
}

?>