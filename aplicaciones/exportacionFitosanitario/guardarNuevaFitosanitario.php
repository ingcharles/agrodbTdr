<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFitosanitario.php';

$conexion = new Conexion();
$fi = new ControladorFitosanitario();

print_r($_POST);


$identificador = $_POST['identificador'];

// Guarda datos del Fitor	
	$id_puerto_embarque = 66; //codigo del pais de origen Ecuador
	$puerto_embarque = 'ECUADOR'; //nombre del pais de origen Ecuador	
	
	$datosExFito = array(
			'identificador' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
			'nombre_importador' => htmlspecialchars ($_POST['nombreImportador'],ENT_NOQUOTES,'UTF-8'),
			'direccion_importador' => htmlspecialchars ($_POST['direccionImportador'],ENT_NOQUOTES,'UTF-8'),
			'id_pais' => htmlspecialchars ($_POST['paisImportador'],ENT_NOQUOTES,'UTF-8'),
			'pais_importacion' => htmlspecialchars ($_POST['nomPaisImportador'],ENT_NOQUOTES,'UTF-8'),			
			'nombre_embarcador' => htmlspecialchars ($_POST['nombreEmbarcador'],ENT_NOQUOTES,'UTF-8'),
			'nombre_marcas' => htmlspecialchars ($_POST['nombreMarca'],ENT_NOQUOTES,'UTF-8'),
			'id_puerto_destino' => htmlspecialchars ($_POST['puertoDestino'],ENT_NOQUOTES,'UTF-8'),
			'puerto_destino' => htmlspecialchars ($_POST['nombrePuertoDestino'],ENT_NOQUOTES,'UTF-8'),
			'id_puerto_embarque' => $id_puerto_embarque,
			'puerto_embarque' => $puerto_embarque,
			'transporte' => htmlspecialchars ($_POST['transporte'],ENT_NOQUOTES,'UTF-8'),
			'fecha_embarque' => htmlspecialchars ($_POST['fecha_embarque'],ENT_NOQUOTES,'UTF-8'),
			'numero_viaje' => htmlspecialchars ($_POST['numViaje'],ENT_NOQUOTES,'UTF-8'),
			'tratamiento_realizado' => htmlspecialchars ($_POST['tratamientoRealizado'],ENT_NOQUOTES,'UTF-8'),
			'duracion_tratamiento' => htmlspecialchars ($_POST['duracion'],ENT_NOQUOTES,'UTF-8'),
			'temperatura_tratamiento' => htmlspecialchars ($_POST['temperatura'],ENT_NOQUOTES,'UTF-8'),
			'fecha_tratamiento' => htmlspecialchars ($_POST['fecha_realizacion'],ENT_NOQUOTES,'UTF-8'),
			'quimico_tratamiento' => htmlspecialchars ($_POST['productoQuimico'],ENT_NOQUOTES,'UTF-8'),
			'concentracion_producto' => htmlspecialchars ($_POST['concentracion'],ENT_NOQUOTES,'UTF-8'),
			'id_provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
			'provincia' => htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8'), 
			'id_ciudad' => htmlspecialchars ($_POST['ciudad'],ENT_NOQUOTES,'UTF-8'),
			'ciudad' => htmlspecialchars ($_POST['nombreCiudad'],ENT_NOQUOTES,'UTF-8'), 
			'observacion_operador' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
			'reporte_inspeccion' => htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8'),			
			'id_vue' => '',
			'estado' => 'enviado' // Estado inicial
			); 
	//Guarda datos del Fito
	
	//Generar numero de solicitud
	$res = $fi->generarNumeroSolicitud($conexion, '%'.$datosExFito['identificador'].'%');
	$solicitud = pg_fetch_assoc($res);
	$tmp= explode("-", $datosExFito['numero']);
	$incremento = end($tmp)+1;
	
	$codigo_certificado = 'FITO-'.$datosExFito['identificador'].'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);

	
	$resFito = $fi -> guardarFitoExportacion($conexion, $datosExFito['nombre_importador'], $datosExFito['direccion_importador'],
			$datosExFito['id_pais'], $datosExFito['pais_importacion'],
			$datosExFito['identificador'], $datosExFito['nombre_embarcador'],
			$datosExFito['nombre_marcas'], $datosExFito['id_puerto_destino'],
			$datosExFito['puerto_destino'], $datosExFito['id_puerto_embarque'],
			$datosExFito['puerto_embarque'], $datosExFito['transporte'],
			$datosExFito['fecha_embarque'], $datosExFito['numero_viaje'],
			$datosExFito['tratamiento_realizado'], $datosExFito['duracion_tratamiento'],
			$datosExFito['temperatura_tratamiento'], $datosExFito['fecha_tratamiento'],
			$datosExFito['quimico_tratamiento'], $datosExFito['concentracion_producto'],
			$datosExFito['id_provincia'], $datosExFito['provincia'],
			$datosExFito['id_ciudad'], 	$datosExFito['ciudad'],
			$datosExFito['observacion_operador'], $datosExFito['reporte_inspeccion'],
			$codigo_certificado, $datosExFito['id_vue'],
			$datosExFito['estado']);
	
	// Guarda datos del Fito Detalle
	
	while ($filaFito = pg_fetch_assoc($resFito))
	{
		$id_fito_exportacion = $filaFito["id_fito_exportacion"];
		echo ($id_fito_exportacion);
		
		$identificador_operador = $_POST['hproveedor'];
		$id_producto = $_POST['hCodProductos'];
		$nombre_producto = $_POST['hproducto'];
		$numero_bultos = $_POST['hbultos'];
		$unidad_bultos = $_POST['hunidades'];
		$cantidad_producto = $_POST['hcantidad'];
		$permiso_musaceas = $_POST['hmusaceas'];
		$unidad_cantidad_producto = 'Kg.';

		for($i=0; $i<count($id_producto); $i++)
		{								
			$res = $fi -> guardarFitoProductos($conexion, $id_fito_exportacion, $identificador_operador[$i], $id_producto[$i], $nombre_producto[$i], $numero_bultos[$i], $unidad_bultos[$i],
					$cantidad_producto[$i], $unidad_cantidad_producto, $permiso_musaceas[$i]);
		}
	}
	
	//$res = $fi -> guardarFitoDocumentos($conexion, $id_fito_exportacion, 'FitoExportacion', 'aplicaciones/fitosanitario/archivosAdjuntos/1713335188.pdf', '');
	// Condiciones Fitos para los diferentes Tipos productos
	// 1=>Si el producto es diferente de CACAO y BANANO
	// 2=>Si el producto es CACAO
	// 3=>Si el producto es BANANO
	
	$datosExFitoDoc = array(
		'doc1' => htmlspecialchars ($_POST['archivoReporteInspeccion'],ENT_NOQUOTES,'UTF-8'),
		'doc2' => htmlspecialchars ($_POST['archivoManifiestoCarga'],ENT_NOQUOTES,'UTF-8'),
		'doc3' => htmlspecialchars ($_POST['archivoCertificadoCalidadCacao'],ENT_NOQUOTES,'UTF-8'),
		'doc4' => htmlspecialchars ($_POST['archivoManifiestoUnidadBanano'],ENT_NOQUOTES,'UTF-8'),
		'doc5' => htmlspecialchars ($_POST['archivoRegistroOperadorUnibananao'],ENT_NOQUOTES,'UTF-8'));		
		
		if($datosExFitoDoc['doc1'] != '0')
		{
			$tipoArchivo = 'Reporte de Inspeccion';
			$res = $fi -> guardarFitoDocumentos($conexion, $id_fito_exportacion, $tipoArchivo, $datosExFitoDoc['doc1'], 'SV');
		}
		if($datosExFitoDoc['doc2'] != '0')
		{
			$tipoArchivo = 'Manifiesto de Carga';
			$res = $fi -> guardarFitoDocumentos($conexion, $id_fito_exportacion, $tipoArchivo, $datosExFitoDoc['doc2'], 'SV');
		}
		if($datosExFitoDoc['doc3'] != '0')
		{
			$tipoArchivo = 'Certificado de Calidad del Cacao';
			$res = $fi -> guardarFitoDocumentos($conexion, $id_fito_exportacion, $tipoArchivo, $datosExFitoDoc['doc3'], 'SV');
		}
		if($datosExFitoDoc['doc4'] != '0')
		{
			$tipoArchivo = 'Manifiesto de Unidad del Banano';
			$res = $fi -> guardarFitoDocumentos($conexion, $id_fito_exportacion, $tipoArchivo, $datosExFitoDoc['doc4'], 'SV');
		}
		if($datosExFitoDoc['doc5'] != '0')
		{
			$tipoArchivo = 'Registro del Operador de Unibananao';
			$res = $fi -> guardarFitoDocumentos($conexion, $id_fito_exportacion, $tipoArchivo, $datosExFitoDoc['doc5'], 'SV');
		}
		
echo "Se grabó todo correcto OK";

?>