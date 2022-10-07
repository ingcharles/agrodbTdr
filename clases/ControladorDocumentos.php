<?php

include_once('tbszip.php');

class ControladorDocumentos{

	//private $plantilla; //-- fichero de origen
	//private $nombre_archivo; //-- nombre del nuevo fichero
	//private $directorio_salida; //-- directorio del nuevo fichero
	//private $nombre_salida; //-- fichero (retorna el fichero modo descarga)| nombre (retorna el nombre del fichero)
	//private $prefijo; //-- prefijo del nuevo fichero
	//private $valores; //-- valores a reemplazar
	private $error; //-- retorna los errores

	/*function __construct() {//-- CARGAMOS VALORES POR DEFECTO
		$this->nombre_archivo = 'new.doc';
	$this->retorno = 'fichero';

	$this->valores = array();
	$this->error = '';
	}*/


	public function obtenerPlatillasDisponibles ($conexion, $area){
		
		$res = $conexion->ejecutarConsulta("select 
												pd.codigo_plantilla,
												vp.version_plantilla,
												pd.tipo,
												pd.descripcion,
												pd.id_area,
												vp.nombre_archivo || '_' || vp.version_plantilla as archivo
											from 
												g_documentacion.plantilla_documento pd,
												g_documentacion.versiones_plantilla vp							
											where 
												pd.codigo_plantilla=vp.codigo_plantilla and
												vp.estado=1 and
												pd.id_area IN $area												
											order by
												2;");
		return $res;
	}



	private function leerPlantilla($plantilla) {//-- CARGAMOS EL FICHERO EN UNA VARIABLE
		if (is_file($plantilla)) {
			$texto = file($plantilla);
			$ntexto = sizeof($texto);
			for ($n = 0; $n < $ntexto; $n++) {
				$todo = $todo . $texto[$n];
			}
			return $todo;
		} else {
			echo "El archivo de origen no existe";
			return false;
		}
	}

	function generarNombreArchivo($conexion,$idDocumento){
		$res = $conexion->ejecutarConsulta("
				SELECT 
					vp.version_plantilla as version, 
					vp.codigo_plantilla as plantilla, 
					vp.nombre_archivo || '_' || vp.version_plantilla as archivo
  				FROM 
 					g_documentacion.documentos_generados dg 
					INNER JOIN g_documentacion.versiones_plantilla vp
 					ON 
						(dg.version_plantilla = vp.version_plantilla) and 
						(dg.codigo_plantilla=vp.codigo_plantilla)
				WHERE
					id_documento='$idDocumento'");
		$documento = pg_fetch_assoc($res);
		$version=$documento['version'];
		$plantilla=$documento['plantilla'];
		$archivo=$documento['archivo'];
		
		$tmp= explode("_", $archivo);
		$area= strtoupper($tmp[sizeof($tmp)-3]);
		$numeroPlantilla=str_pad($tmp[sizeof($tmp)-2], 2, "0", STR_PAD_LEFT);
		$numeroVersion=str_pad($tmp[sizeof($tmp)-1], 2, "0", STR_PAD_LEFT);
		$anio=date('Y');
		$mesYdia=strtoupper(dechex(date('m')*100+date('d')));
		
		$res = $conexion->ejecutarConsulta("select
												(count(dg.id_documento)+1) as numero
											from
												g_documentacion.documentos_generados dg
											where
												estado IN (2,3) and
												dg.version_plantilla=$version and
												dg.codigo_plantilla=$plantilla and
												id_documento like '%-'||date_part('year',now())||'%'");
		$incremental= pg_fetch_assoc($res);
		$incremental = str_pad($incremental['numero'], 4, "0", STR_PAD_LEFT);
		
		
		return $area . '-'. $anio . $mesYdia .'-' . $numeroPlantilla . $numeroVersion . '.' . $incremental;

	}

	function rtf($plantilla, $nombre_archivo, $valores, $directorio_plantilla='plantillas/', $retorno='descarga', $directorio_salida='generados/') {
		/*if ($txtplantilla = $this->leerPlantilla($directorio_plantilla.$plantilla.'.rtf')) {//-- COMPROBAMOS SI SE CARGO BIEN EL FICHERO
			$punt = fopen($directorio_salida . $nombre_archivo.'.rtf', "w"); //-- CREAMOS EL NUEVO FICHERO
			if (is_array($valores) and count($valores) > 0) {
				foreach ($valores as $k => $v) {//-- REEMPLAZAMOS LAS VARIABLES
					$v = utf8_decode($v);
					$txtplantilla = str_replace($k, $v, $txtplantilla);
				}
			}
			fputs($punt, $txtplantilla); //-- AGREGAMOS EL CONTENIDO AL NUEVO FICHERO
			fclose($punt); //- CERRAMOS LA CONEXION DEL FICHERO
			if ($retorno == "descargar") {//-- RETORNA EN MODO DE DESCARGA
				header("Content-Disposition: attachment; filename=" . $nombre_archivo . "\n\n");
				header("Content-Type: application/octet-stream");
				readfile($directorio_salida . $nombre_archivo);

				echo hash_file('md5', $directorio_salida . $nombre_archivo);

			} elseif ($retorno == "nombre") {//-- RETORNA EL NOMBRE DEL FICHERO
				return $nombre_archivo;
			}
		}*/
			$zip = new clsTbsZip();
			$zip->Open($directorio_plantilla . $plantilla . '.docx');
			
			$contenido = $zip->FileRead('word/document.xml');
			$encabezado = $zip->FileRead('word/header1.xml');
			$pie = $zip->FileRead('word/footer1.xml');
			
			$contenido = $this->reemplazo($contenido, $valores);	
			$encabezado = $this->reemplazo($encabezado, $valores);
			$pie = $this->reemplazo($pie, $valores);
			
			$zip->FileReplace('word/document.xml', $contenido, TBSZIP_STRING);
			$zip->FileReplace('word/header1.xml', $encabezado, TBSZIP_STRING);
			$zip->FileReplace('word/footer1.xml', $pie, TBSZIP_STRING);
			
			
			$zip->Flush(TBSZIP_FILE, $directorio_salida.$nombre_archivo . '.docx');
			
			//function reemplazo($seccion, $textoViejo, $textoNuevo)		
	}
	
	function reemplazo($seccion, $arreglo){
		foreach ($arreglo as $k => $v) {
			$v=utf8_decode($v);
			$posicionDePalabra = strpos($seccion, $k);	
			if ($posicionDePalabra != null){
				$seccion = substr_replace($seccion, $v, $posicionDePalabra, strlen($k));
			}		
		}
		return $seccion;
	}
	
	
	function actualizarRtf($archivoAActualizar, $valores, $directorio_plantilla = 'finales_rtf/') {
		//if ($txtplantilla = $this->leerPlantilla('finales/'.$archivoAActualizar.'.rtf')) {
		/*if ($txtplantilla = $this->leerPlantilla('finales_rtf/'.$archivoAActualizar.'.rtf')) {//-- COMPROBAMOS SI SE CARGO BIEN EL FICHERO
		//	$punt = fopen('finales/'.$archivoAActualizar.'.rtf', "w"); //-- CREAMOS EL NUEVO FICHERO
			$punt = fopen('finales_rtf/'.$archivoAActualizar.'.rtf', "w"); 
			if (is_array($valores) and count($valores) > 0) {
				foreach ($valores as $k => $v) {//-- REEMPLAZAMOS LAS VARIABLES
					$v = utf8_decode($v);
					$txtplantilla = str_replace($k, $v, $txtplantilla);
				}
			}
			fputs($punt, $txtplantilla); //-- AGREGAMOS EL CONTENIDO AL NUEVO FICHERO
			fclose($punt); //- CERRAMOS LA CONEXION DEL FICHERO
			if ($retorno == "descargar") {//-- RETORNA EN MODO DE DESCARGA
			 header("Content-Disposition: attachment; filename=" . $nombre_archivo . "\n\n");
			header("Content-Type: application/octet-stream");
			readfile($directorio_salida . $nombre_archivo);
	
			echo hash_file('md5', $directorio_salida . $nombre_archivo);
	
			} elseif ($retorno == "nombre") {//-- RETORNA EL NOMBRE DEL FICHERO
			return $nombre_archivo;
			}
		}*/
		
		
		$zip = new clsTbsZip();
		
		copy($directorio_plantilla.$archivoAActualizar . '.docx', $directorio_plantilla.$archivoAActualizar.'_bak.docx');
		
		$zip->Open($directorio_plantilla.$archivoAActualizar.'_bak.docx');
			
		$contenido = $zip->FileRead('word/document.xml');
		$encabezado = $zip->FileRead('word/header1.xml');
		$pie = $zip->FileRead('word/footer1.xml');
			
		$contenido = $this->reemplazo($contenido, $valores);
		$encabezado = $this->reemplazo($encabezado, $valores);
		$pie = $this->reemplazo($pie, $valores);
			
		$zip->FileReplace('word/document.xml', $contenido, TBSZIP_STRING);
		$zip->FileReplace('word/header1.xml', $encabezado, TBSZIP_STRING);
		$zip->FileReplace('word/footer1.xml', $pie, TBSZIP_STRING);

		$zip->Flush(TBSZIP_FILE, $directorio_plantilla . $archivoAActualizar . '.docx');
		
		$zip->close();
		
		unlink($directorio_plantilla.$archivoAActualizar.'_bak.docx');
		
	}

	/*function obtenerNuevoNumeroDocumento($conexion,$version,$plantilla){
		$res = $conexion->ejecutarConsulta("select
												(count(dg.id_documento)+1) as numero
											from
												g_documentacion.documentos_generados dg
											where
												dg.version_plantilla=".$version." and
												dg.codigo_plantilla=".$plantilla." and
												date_part('year',dg.fecha_creacion)=date_part('year',now())");
		return $res;
	}*/
	
	//cambiar esto a SOLICITUDES!!!!!!!!!!!!!!!!!!!
	function ingresaSolicitud($conexion,$tipo){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_solicitudes.solicitudes(
            									tipo,fecha_creacion,condicion)
										    VALUES ('".$tipo."',now(),'creado') RETURNING id_solicitud;");
		return $res;
	}
	
	
	
	function ingresarNuevoDocumento($conexion,$idDocumento,$datos,$idSolicitud){
		$res = $conexion->ejecutarConsulta("INSERT INTO g_documentacion.documentos_generados(
            									id_documento, 
												identificador, 
												version_plantilla, 
												codigo_plantilla,
												asunto, 
												fecha_creacion,
												documento_inicial,
												id_solicitud)
										    VALUES ('".$idDocumento."',
													'".$datos['identificadorUsuario']."',
													".$datos['codigoVersion'].",
													".$datos['codigoPlantilla'].", 
										            '".$datos['descripcionDocumento']."',
													now(),
													'aplicaciones/documentos/generados/".$idDocumento.".docx',
													".$idSolicitud.");");
		
		return $idDocumento;
	}
	
	
	
	//cambiar esto a SOLICITUDES!!!!!!!!!!!!!!!!!!!!
	function ingresaRegistradores($conexion,$idSolicitud,$registrador,$accion){
		
		switch ($accion){
			case 'Revisor': $busqueda = 'Revisor'; break;
			case 'Aprobador': $busqueda = 'Aprobador'; break;
		}
		
		$res = $conexion->ejecutarConsulta("INSERT INTO g_solicitudes.revisores(
            									id_solicitud,
												identificador,
												estado,
												comentario, 
												accion
												)
										    VALUES ('".$idSolicitud."',
													'".$registrador."',
													'Sin_notificar',' ','$busqueda');");
		return $registrador;
	}
		
	
	public function listarDocumentos ($conexion,$identificador,$estado){
		$busqueda = '';
		switch ($estado){
			case 'ABIERTOS': $busqueda = 'dg.estado IN (1,2) and '; break;
		}
		
		$res = $conexion->ejecutarConsulta("select 
												dg.*, s.condicion 
											from 
												g_documentacion.documentos_generados dg,
												g_solicitudes.solicitudes s
											where 
												s.id_solicitud = dg.id_solicitud and
												" . $busqueda ."
												dg.identificador = '".$identificador."'
											order by
												dg.fecha_creacion,
												dg.id_documento;");
		return $res;
	}
	
	public function abrirDocumento ($conexion,$idDocumento){


		
		
		
		$res = $conexion->ejecutarConsulta("SELECT distinct g_uath.cambia_formato_nombre(A.nombre,A.apellido) AS nombres_completos,
															A.id_documento, 
															A.documento_inicial, 
															A.documento_borrador, 
															A.documento_final, 
															A.id_solicitud, 
															A.asunto, 
															A.estado,
															A.tipo,
															vp.version_plantilla, 
															A.fecha_creacion
											FROM (g_uath.ficha_empleado fe  INNER JOIN (g_documentacion.documentos_generados dg 
												 INNER JOIN g_documentacion.plantilla_documento pd ON dg.codigo_plantilla=pd.codigo_plantilla) as T
												 ON fe.identificador=T.identificador) as A 
												INNER JOIN g_documentacion.versiones_plantilla vp ON A.version_plantilla= vp.version_plantilla
											WHERE A.id_documento = '$idDocumento';");
		return $res;
	}
	
	public function abrirDocumentoPorSolicitud ($conexion,$idSolicitud){
	
		/*$res = $conexion->ejecutarConsulta("SELECT g_uath.cambia_formato_nombre(fe.nombre,fe.apellido) AS nombres_completos,
												dg.id_documento,
												dg.documento_borrador,
												dg.documento_final,
												dg.id_solicitud,
												dg.asunto,
												pd.tipo,
												vp.version_plantilla,
												dg.fecha_creacion
											FROM
												g_documentacion.documentos_generados dg
	
											INNER JOIN
												g_documentacion.versiones_plantilla vp ON dg.version_plantilla=vp.version_plantilla
											INNER JOIN
												g_documentacion.plantilla_documento pd ON vp.codigo_plantilla=pd.codigo_plantilla
											INNER JOIN
												g_uath.ficha_empleado fe ON dg.identificador=fe.identificador
											WHERE dg.id_solicitud = '".$idSolicitud."';");*/
		$res = $conexion->ejecutarConsulta("SELECT distinct g_uath.cambia_formato_nombre(A.nombre,A.apellido) AS nombres_completos,
								A.id_documento, A.documento_borrador, A.documento_final, A.id_solicitud, A.asunto, A.tipo,
								vp.version_plantilla, A.fecha_creacion
								FROM (g_uath.ficha_empleado fe  INNER JOIN (g_documentacion.documentos_generados dg INNER JOIN g_documentacion.plantilla_documento pd ON dg.codigo_plantilla=pd.codigo_plantilla) as T
								ON fe.identificador=T.identificador) as A INNER JOIN g_documentacion.versiones_plantilla vp ON A.version_plantilla= vp.version_plantilla
								WHERE A.id_solicitud = '".$idSolicitud."';");
		return $res;
	}
	
	
	
	public function actualizarBorradorDocumento($conexion,$idDocumento, $ruta){
	
		$res = $conexion->ejecutarConsulta("update 	
													g_documentacion.documentos_generados
											set     
													documento_borrador = '".$ruta."'
											where 	
													id_documento = '".$idDocumento."';");
		return $res;
	}
	
	public function actualizarDocumentoPreFinal($conexion,$idDocumento,$nuevoIdDocumento, $ruta,$idSolicitud){
	
		$res = $conexion->ejecutarConsulta("update
													g_documentacion.documentos_generados
											set
													id_documento = '$nuevoIdDocumento',
													documento_borrador = '".$ruta."',
													estado = 2
											where 	
													id_documento = '".$idDocumento."'
													and id_solicitud = $idSolicitud ;");
		return $res;
	}
	
	//public function actualizarDocumentoFinal($conexion,$idDocumento,$nuevoIdDocumento, $ruta){
	public function actualizarDocumentoFinal($conexion,$idDocumento, $ruta, $estadoMail=null){	
		$res = $conexion->ejecutarConsulta("update 	g_documentacion.documentos_generados
											set     documento_final = '$ruta',
													estado = 3,
													estado_mail = '$estadoMail'
											where 	id_documento = '$idDocumento';");
		return $res;
	}
	
	public function filtrarDocumentos($conexion,$identificador,$archivo,$asunto,$fechaInicio,$fechaFin,$estado){
		
		$vocales = array('a','e','i','o','u','á','é','í','ó','ú','A','E','I','O','U','Á','É','Í','Ó','Ú');
		$sustitucion = '_';
		$asunto = str_replace($vocales, $sustitucion, $asunto);
		
		
		$identificador = $identificador!="" ? "'" . $identificador . "'" : "null";
		$archivo = $archivo!="" ? "'%" . $archivo . "%'" : "null";
		$asunto = $asunto!="" ? "'%" . $asunto . "%'" : "null";
		$fechaInicio = $fechaInicio!="" ? "'" . $fechaInicio . "'" : "null";
		$fechaFin = $fechaFin!="" ? "'" . $fechaFin . "'" : "null";
		$estado = $estado!="" ? $estado : "null";
		
		$res = $conexion->ejecutarConsulta("
					select 
						* 
					from 
						g_documentacion.mostrar_documentos_filtrados($identificador,$archivo,$asunto,$fechaInicio,$fechaFin,$estado)");
		return $res;
	}	
	
	public function obtenerCreadorDocumento ($conexion,$idSolicitud){
	

		$res = $conexion->ejecutarConsulta("SELECT identificador 
											FROM g_documentacion.documentos_generados 
											WHERE id_solicitud = $idSolicitud;");
		return $res;
	}
	
}
