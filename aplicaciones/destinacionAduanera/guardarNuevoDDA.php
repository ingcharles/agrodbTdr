<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorDestinacionAduanera.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
	$proposito = htmlspecialchars ($_POST['proposito'],ENT_NOQUOTES,'UTF-8');
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$categoriaProducto = htmlspecialchars ($_POST['categoriaProducto'],ENT_NOQUOTES,'UTF-8');
	$permisoImportacion = htmlspecialchars ($_POST['permisoImportacion'],ENT_NOQUOTES,'UTF-8');
	$permisoExportacion = htmlspecialchars ($_POST['permisoExportacion'],ENT_NOQUOTES,'UTF-8');
	
	$carga = htmlspecialchars ($_POST['carga'],ENT_NOQUOTES,'UTF-8');
	$documentoTransporte = htmlspecialchars ($_POST['documentoTransporte'],ENT_NOQUOTES,'UTF-8');
	$idLugarInspeccion = htmlspecialchars ($_POST['lugarInspeccion'],ENT_NOQUOTES,'UTF-8');
	$nombreLugarInspeccion = htmlspecialchars ($_POST['nombreLugarInspeccion'],ENT_NOQUOTES,'UTF-8');
	$observacionOperador = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	
	//////// DETALLE DE DOCUMENTOS ADJUNTOS ///////////////
	
	//Sanidad Animal
	$certificadoPredioCuarentenaSA = $_POST['archivoCertificadoPredioCuarentenaSA'];

	//Sanidad Vegetal
	$certificadoPredioCuarentenaSV = $_POST['archivoCertificadoPredioCuarentenaSV'];
	
	
	try {
			$conexion = new Conexion();
			$ci = new ControladorImportaciones();
			$cd = new ControladorDestinacionAduanera();
			$cc = new ControladorCatalogos();
			
			//Crear código de identificación de solicitud para agrupar productos
			$res = $cd->generarNumeroSolicitud($conexion, '%'.$identificador.'%');
			$solicitud = pg_fetch_assoc($res);
			$tmp= explode("-", $solicitud['numero']);
			$incremento = end($tmp)+1;
			
			$codigoCertificado = 'DDA-'.$identificador.'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
			
			//Obtener datos de registro de importacion seleccionado
			$qImportacion = $ci -> abrirImportacion($conexion, $identificador, $permisoImportacion);
			
			//Guardar datos de registro destinacion aduanera
			$qIdDestinacionAduanera = $cd->guardarNuevoDDA($conexion, $identificador, $qImportacion[0]['nombreExportador'], $qImportacion[0]['direccionExportador'], $qImportacion[0]['idPaisExportacion'], 
					$qImportacion[0]['paisExportacion'], $proposito, $tipoSolicitud, $categoriaProducto, $permisoImportacion, $permisoExportacion, $qImportacion[0]['idPuertoDestino'], 
					$qImportacion[0]['puertoDestino'], $carga, $qImportacion[0]['tipoTransporte'], $documentoTransporte, $idLugarInspeccion, $nombreLugarInspeccion, $observacionOperador, 
					$codigoCertificado, $qImportacion[0]['idVue']);
			
			$idDestinacionAduanera = pg_fetch_result($qIdDestinacionAduanera, 0, 'id_destinacion_aduanera');
			
			//Guardar Productos
			for ($i = 0; $i < count ($qImportacion); $i++) {
				$cd ->guardarDDAProductos($conexion, $idDestinacionAduanera, $qImportacion[$i]['idProducto'], $qImportacion[$i]['nombreProducto'], $qImportacion[$i]['unidad'], '');
			}
			
			//Guardar archivos adjuntos
			
			//Sanidad Animal
			if($certificadoPredioCuarentenaSA != '0'){
				$cd ->guardarDDAArchivos($conexion, $idDestinacionAduanera, 'Certificado predio cuarentena', $certificadoPredioCuarentenaSA, 'SA');
			}
			
			//Sanidad Vegetal
			if($certificadoPredioCuarentenaSV != '0'){
				$cd ->guardarDDAArchivos($conexion, $idDestinacionAduanera, 'Certificado predio cuarentena', $certificadoPredioCuarentenaSV, 'SV');
			}
				
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';
			
			$conexion->desconectar();
			echo json_encode($mensaje);
			
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>