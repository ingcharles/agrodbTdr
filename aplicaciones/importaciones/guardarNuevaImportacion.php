<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorImportaciones.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];


try{
	$datos = array('identificador' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
				'nombreExportador' => htmlspecialchars ($_POST['nombreExportador'],ENT_NOQUOTES,'UTF-8'), 
			    'idPaisExportador' => htmlspecialchars ($_POST['paisExportador'],ENT_NOQUOTES,'UTF-8'),
				'direccionExportador' => htmlspecialchars ($_POST['direccionExportador'],ENT_NOQUOTES,'UTF-8'),
				'nombreEmbarcador' => htmlspecialchars ($_POST['nombreEmbarcador'],ENT_NOQUOTES,'UTF-8'),
				'regimenAduanero' => htmlspecialchars ($_POST['regimenAduanero'],ENT_NOQUOTES,'UTF-8'),
				'moneda' => htmlspecialchars ($_POST['moneda'],ENT_NOQUOTES,'UTF-8'),
				'idPaisEmbarque' => htmlspecialchars ($_POST['paisEmbarque'],ENT_NOQUOTES,'UTF-8'),
				'idPuertoEmbarque' => htmlspecialchars ($_POST['puertoEmbarque'],ENT_NOQUOTES,'UTF-8'),
				'nombrePuertoEmbarque' => htmlspecialchars ($_POST['nombrePuertoEmbarque'],ENT_NOQUOTES,'UTF-8'),
				'idPuertoDestino' => htmlspecialchars ($_POST['puertoDestino'],ENT_NOQUOTES,'UTF-8'),
				'nombrePuertoDestino' => htmlspecialchars ($_POST['nombrePuertoDestino'],ENT_NOQUOTES,'UTF-8'),
				'tipoCertificado' => htmlspecialchars ($_POST['tipoCertificado'],ENT_NOQUOTES,'UTF-8'),
				'transporte' => htmlspecialchars ($_POST['transporte'],ENT_NOQUOTES,'UTF-8'));
	
	$idProducto= ($_POST['hIdProducto']);
	$nombreProducto= ($_POST['hNombreProducto']);
	$unidades= ($_POST['hUnidades']);
	$peso= ($_POST['hPeso']);
	$valorFob= ($_POST['hValorFob']);
	$valorCif= ($_POST['hValorCif']);
	$licenciaMagap= ($_POST['hLicenciaMagap']);
	$registroSemillas= ($_POST['hRegistroSemillas']);
	
	//Aprobación automática para certificado de importación en Sanidad Vegetal
	/*if($datos['tipoCertificado'] == 'Permiso Fitosanitario de Importación'){
		$estado = 'pago';
	}else{
		$estado = 'enviado';
	}*/
	$estado = 'enviado';
	
	//////// DETALLE DE DOCUMENTOS ADJUNTOS ///////////////
	
	//Sanidad Animal
	$certificadoPredioCuarentenaSA = $_POST['archivoCertificadoPredioCuarentenaSA'];
	$informeGanaderiaSA = $_POST['archivoInformeGanaderiaSA'];
	
	//Sanidad Vegetal
	$registroSemillasSV = $_POST['archivoRegistroSemillasSV'];
	
	//Inocuidad Veterinarios
	$facturaProformaIAV = $_POST['archivoFacturaProformaIAV'];
	$notaPedidoIAV = $_POST['archivoNotaPedidoIAV'];
	$cartaAutorizacionIAV = $_POST['archivoCartaAutorizacionIAV'];
	
	//Inocuidad Plaguicidas
	$facturaProformaIAP = $_POST['archivoFacturaProformaIAP'];
	$notaPedidoIAP = $_POST['archivoNotaPedidoIAP'];
	$cartaAutorizacionIAP = $_POST['archivoCartaAutorizacionIAP'];
	
	try {
		/*if(count($idProducto) != 0){*/
			$conexion = new Conexion();
			$ci = new ControladorImportaciones();
			$cc = new ControladorCatalogos();
			
			//Nombre Pais Exportador
			$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['idPaisExportador']);
			$nombrePaisExportador = pg_fetch_result($res, 0, 'nombre');
			
			//Nombre Pais Embarque
			$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['idPaisEmbarque']);
			$nombrePaisEmbarque = pg_fetch_result($res, 0, 'nombre');
			
			//Crear código de identificación de solicitud para agrupar productos
			$res = $ci->generarNumeroSolicitud($conexion, '%'.$datos['identificador'].'%');
			$solicitud = pg_fetch_assoc($res);
			$tmp= explode("-", $solicitud['numero']);
			$incremento = end($tmp)+1;
			
			$codigoSolicitud = 'IMP-'.$datos['identificador'].'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
			
			//Guardar datos de exportador
			/*$qIdExportador = $ci->guardarNuevoExportador($conexion, $datos['nombreExportador'], $datos['direccionExportador'], $datos['idPaisExportador'], $nombrePaisExportador);
			$idExportador = pg_fetch_result($qIdExportador, 0, 'id_exportador');*/
			
			//Guardar datos de registro importacion
			$qIdImportacion = $ci->guardarNuevaImportacion($conexion, $datos['identificador'], $datos['nombreExportador'], $datos['direccionExportador'], $datos['idPaisExportador'], $nombrePaisExportador, $datos['nombreEmbarcador'], $datos['idPaisEmbarque'], $nombrePaisEmbarque, $datos['idPuertoEmbarque'], $datos['nombrePuertoEmbarque'], $datos['idPuertoDestino'], $datos['nombrePuertoDestino'], $codigoSolicitud, $datos['registroVue'], $estado, $datos['tipoCertificado'], $datos['regimenAduanero'], $datos['moneda'], $datos['transporte']);
			$idImportacion = pg_fetch_result($qIdImportacion, 0, 'id_importacion');
			
			//Guardar Productos
			for ($i = 0; $i < count ($idProducto); $i++) {
				$productos = $ci -> guardarImportacionesProductos($conexion, $idImportacion, $idProducto[$i], $nombreProducto[$i], $unidades[$i], $peso[$i], $valorFob[$i], $valorCif[$i], $licenciaMagap[$i], $registroSemillas[$i], '');
			}
			
			//Guardar archivos adjuntos
			
			//Sanidad Animal
			if($certificadoPredioCuarentenaSA != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, 'Certificado predio cuarentena', $certificadoPredioCuarentenaSA, 'SA');
			}
			
			if($informeGanaderiaSA != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, 'Informe ganadería', $informeGanaderiaSA, 'SA');
			}
			
			//Sanidad Vegetal
			if($registroSemillasSV != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, 'Registro semillas', $registroSemillasSV, 'SV');
			}
			
			//Inocuidad Veterinarios
			if($facturaProformaIAV != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, $datos['identificador'], 'Factura proforma', $facturaProformaIAV, 'IAV');
			}
				
			if($notaPedidoIAV != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, $datos['identificador'], 'Nota pedido', $notaPedidoIAV, 'IAV');
			}
			
			if($cartaAutorizacionIAV != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, $datos['identificador'], 'Carta autorización', $cartaAutorizacionIAV, 'IAV');
			}
			
			//Inocuidad Plaguicidas
			if($facturaProformaIAP != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, $datos['identificador'], 'Factura proforma', $facturaProformaIAP, 'IAP');
			}
				
			if($notaPedidoIAP != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, $datos['identificador'], 'Nota pedido', $notaPedidoIAP, 'IAP');
			}
			
			if($cartaAutorizacionIAP != '0'){
				$ci ->guardarImportacionesArchivos($conexion, $idImportacion, $datos['identificador'], 'Carta autorización', $cartaAutorizacionIAP, 'IAP');
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';
			
			$conexion->desconectar();
		/*}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Debe ingresar por lo menos un producto para la solicitud. ".count($idProducto);
		}*/
		
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