<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!'.$_POST['boton'];

try{
	$datos = array('identificador' => htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8'),
					'nombreImportador' => htmlspecialchars ($_POST['nombreImportador'],ENT_NOQUOTES,'UTF-8'), 
			    	'direccionImportador' => htmlspecialchars ($_POST['direccionImportador'],ENT_NOQUOTES,'UTF-8'),
					'id_localizacion' => htmlspecialchars ($_POST['paisImportador'],ENT_NOQUOTES,'UTF-8'),
					'representanteTecnico' => htmlspecialchars ($_POST['representanteTecnico'],ENT_NOQUOTES,'UTF-8'),
					'id_puerto_embarque' => htmlspecialchars ($_POST['puertoEmbarque'],ENT_NOQUOTES,'UTF-8'),
					'medioTransporte' => htmlspecialchars ($_POST['medioTransporte'],ENT_NOQUOTES,'UTF-8'),
					'usoProducto' => htmlspecialchars ($_POST['usoProducto'],ENT_NOQUOTES,'UTF-8'),
					'bultos' => htmlspecialchars ($_POST['bultos'],ENT_NOQUOTES,'UTF-8'),
					'descripcion' => htmlspecialchars ($_POST['descripcion'],ENT_NOQUOTES,'UTF-8'),
					'tipoSitio' => htmlspecialchars ($_POST['tipoSitio'],ENT_NOQUOTES,'UTF-8'),
					'fechaInspeccion' => htmlspecialchars ($_POST['fechaInspeccion'],ENT_NOQUOTES,'UTF-8'),
					'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'));
	
					 $idProducto= ($_POST['hIdProducto']);
					 $nombreProducto= ($_POST['hNombreProducto']);
					 $raza = ($_POST['raza']);
					 $sexo = ($_POST['sexo']);
					 $edad = ($_POST['edad']);
					 $cantidadFisica = ($_POST['cantidadFisica']);
					 $unidadFisica = ($_POST['unidadFisica']);
					
					
	//////// DETALLE DE DOCUMENTOS ADJUNTOS ///////////////
	
	//Sanidad Animal
	$archivopermisoImportacionPaisDestino = ($_POST['archivoPermisoImportacionPaisDestino']);
	$archivofacturaMercancia = ($_POST['archivoFacturaMercancia']);
	$archivomanifiestoCarga = ($_POST['archivoManifiestoCarga']);
	
	try {
		/*if(count($idProducto) != 0){*/
			$conexion = new Conexion();
			$ci = new ControladorZoosanitarioExportacion();
			$cc = new ControladorCatalogos();
			
			//Nombre Pais Importador
			$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['id_localizacion']);
			$nombrePaisImportador = pg_fetch_result($res, 0, 'nombre');
			
					
			//Nombre Puerto Embarque
			$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['id_puerto_embarque']);
			$nombrePuertoEmbarque = pg_fetch_result($res, 0, 'nombre');
			
			//Crear código de identificación de solicitud para agrupar productos
			$res = $ci->generarNumeroSolicitud($conexion, '%'.$datos['identificador'].'%');
			$solicitud = pg_fetch_assoc($res);
			$tmp= explode("-", $solicitud['numero']);
			$incremento = end($tmp)+1;
			
			$codigoSolicitud = 'ZOO-'.$datos['identificador'].'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
	
			//Guardar datos de Importador
			//$qIdImportador = $ci->guardarNuevoImportador($conexion, $datos['nombreImportador'], $datos['direccionImportador'],$datos['id_localizacion'], $nombrePaisImportador);
			//$idImportador = pg_fetch_result($qIdImportador, 0, 'id_importador');
			
			
			//Guardar datos de Exportador
			$qIdExportador = $ci->guardarNuevaExportacion($conexion, $datos['identificador'], $datos['representanteTecnico'], $datos['id_puerto_embarque'],$nombrePuertoEmbarque, $datos['medioTransporte'], $datos['usoProducto'], $datos['bultos'],$datos['descripcion'], $datos['tipoSitio'], $codigoSolicitud, $datos['observacion'], $datos['fechaInspeccion'], $datos['nombreImportador'], $datos['direccionImportador'],$datos['id_localizacion'], $nombrePaisImportador);
			$idExportacion = pg_fetch_result($qIdExportador, 0, 'id_zoo_exportacion');
			
			//Guardar Productos
			for ($i = 0; $i < count ($idProducto); $i++) {
				$productos = $ci -> guardarExportacionesProductos($conexion,$idExportacion, $idProducto[$i], $nombreProducto[$i], $raza[$i], $sexo[$i], $edad[$i], $cantidadFisica[$i], $unidadFisica[$i]);
			
			}
		
			//Guardar archivos adjuntos
			
			//Sanidad Animal
			if($archivopermisoImportacionPaisDestino != '0'){
				$ci ->guardarExportacionesArchivos($conexion, $idExportacion,'Permiso de Importación del país de destino', $archivopermisoImportacionPaisDestino,'SA');
			}
			
			if($archivofacturaMercancia != '0'){
				$ci ->guardarExportacionesArchivos($conexion, $idExportacion,'Factura de mercancía',$archivofacturaMercancia,'SA');
			}
			
			if($archivomanifiestoCarga != '0'){
				$ci ->guardarExportacionesArchivos($conexion, $idExportacion,'Manifiesto de carga',$archivomanifiestoCarga,'SA');
			}
			
								
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido grabados satisfactoriamente.';
			
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