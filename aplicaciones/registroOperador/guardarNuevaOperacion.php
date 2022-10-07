<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$identificadorOperador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
	$idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
	$tipoOperacion = htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8');
	$idFlujo = htmlspecialchars ($_POST['idFlujo'],ENT_NOQUOTES,'UTF-8');
	$idAreaProducto = $_POST['areaProducto'];

	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cr = new ControladorRegistroOperador();
		$cvd = new ControladorVigenciaDocumentos();

		$ingreso = true;
		$imprimirOperacion = '';
		$tmpArea = array();
		$bandera = array();
		$ingresoMultiple = true;

		$multipleOperacion = pg_fetch_result($cc->buscarTipoOperacionMultiple($conexion, $tipoOperacion), 0, 'operacion_multiple');
		$areasTipoOperacion = $cc -> obtenerAreasXtipoOperacion($conexion, $tipoOperacion);

		foreach ($areasTipoOperacion as $areaOperacion){
			$vAreaProductoOperacion = $cr->buscarAreasOperacionPorSolicitud($conexion, $tipoOperacion,$_POST[$areaOperacion['codigo']], $identificadorOperador);
			$tmpArea[] = $_POST[$areaOperacion['codigo']];
			if(pg_num_rows($vAreaProductoOperacion)!= 0){

				while ($fila = pg_fetch_assoc($vAreaProductoOperacion)){

					if(($fila['estado_operacion'] == 'porCaducar' && $fila['estado_anterior'] == 'registrado') || ($fila['estado_operacion'] == 'noHabilitado' && $fila['estado_anterior'] == 'porCaducar')){
					    $ingreso = true;
					    $bandera[] = $ingreso;
					}else{
						$datosOperacion = pg_fetch_assoc($cc->obtenerDatosTipoOperacion($conexion, $tipoOperacion));
						
						switch ($datosOperacion['id_area']){
							case 'LT':
								switch ($datosOperacion['codigo']){
									case 'LAL':
									case 'LDV':
										if($fila['estado_operacion'] == 'noHabilitado'){
											$ingreso = true;
											$bandera[] = $ingreso;
										}else{
											$ingreso = false;
											$bandera[] = $ingreso;
										}
									break;
									default:
									$ingreso = false;
									$bandera[] = $ingreso;
								}
							break;
							default:
								$ingreso = false;
								$bandera[] = $ingreso;
						}
					}
				}
			}
		}

		$resultado = array_unique($bandera);

		if(count($resultado) == 1){
			if($resultado[0]){
				$ingreso = true;
			}else{
				$ingreso = false;
			}			
		}else if(count($resultado) == 2){
		    $ingreso = false;
		}
		
		if($multipleOperacion =="f"){
			$cantidadOperaciones = $cr->obtenerOperacionesPorIdentificadorAreaTipoOperacion($conexion, $identificadorOperador, $tipoOperacion, $idAreaProducto, 'noMultiple');
		}else{
			$cantidadOperaciones = $cr->obtenerOperacionesPorIdentificadorAreaTipoOperacion($conexion, $identificadorOperador, $tipoOperacion, $idAreaProducto, 'multiple');
		}
		
		if(pg_num_rows($cantidadOperaciones)!= 0){
			$ingresoMultiple = false;
		}

		if($ingresoMultiple){
			if($ingreso){
				$resultado = array();
				$nombreAreaImpresion = '';
				$areaUtilizada = '';

				$qOperacion = pg_fetch_assoc($cc->obtenerDatosTipoOperacion($conexion, $tipoOperacion));
				$qSitio = $cr->abrirSitio($conexion, $idSitio);

				$qIdOperadorTipoOperacion = $cr->guardarTipoOperacionPorIndentificadorSitio($conexion, $identificadorOperador, $idSitio, $tipoOperacion);
				$idOperadorTipoOperacion = pg_fetch_assoc($qIdOperadorTipoOperacion);

				$qHistorialOperacion = $cr->guardarDatosHistoricoOperacion($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion']);
				$historicoOperacion = pg_fetch_assoc($qHistorialOperacion);

				//NUEVO VIGENCIA DOCUMENTO

				$qCabeceraVigencia = $cvd->buscarTipoOperacionCabeceraVigencia($conexion, $tipoOperacion);
				$cabeceraVigencia = pg_fetch_assoc($qCabeceraVigencia);

				$idVigenciaDocumento = 0;

				if(pg_num_rows($qCabeceraVigencia) > 0){

					$idVigenciaDocumento = $cabeceraVigencia['id_vigencia_documento'];

				}

				//NUEVO VIGENCIA DOCUMENTO

				$qIdSolicitud= $cr->guardarNuevaOperacionPorTipoOperacion($conexion, $tipoOperacion, $identificadorOperador, $idOperadorTipoOperacion['id_operador_tipo_operacion'], $historicoOperacion['id_historial_operacion'], 'creado', $idVigenciaDocumento);
				$idSolicitud = pg_fetch_assoc($qIdSolicitud);

				$cr->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion'], $idSolicitud['id_operacion']);

				foreach ($areasTipoOperacion as $areaOperacion){
					$idAreas = $cr->guardarAreaOperacion($conexion, $_POST[$areaOperacion['codigo']], $idSolicitud['id_operacion']);
					$datosArea = pg_fetch_assoc($cr->ObtenerDatosAreaOperador($conexion, $_POST[$areaOperacion['codigo']]));
					$cr->guardarAreaPorIdentificadorTipoOperacion($conexion, $_POST[$areaOperacion['codigo']], $idOperadorTipoOperacion['id_operador_tipo_operacion']);
					$areaUtilizada .= $datosArea['nombre_area'].', ';
				}

				$nombreAreaImpresion .= '</br><b><em>Sitio:</em></b> '. pg_fetch_result($qSitio, 0, 'nombre_lugar').'</br><b><i>Área:</i></b> '.$areaUtilizada;

				//TODO: TENER EN CUENTA CUANDO SE AGREGUE EL PRODUCTO VAMOS A CONSULTAR CON EL ID DE PRODUCTO Y EL ID TIPOOPERACION A LA TABLA DE PRODUCTO_MULTIPLE_VARIEDADES CAMBIAR FLUJOS
				$estadoFlujo = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idFlujo, '1'));
				$cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],$estadoFlujo['estado']);
				$cr-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion'], $estadoFlujo['estado']);

				$nombreAreaImpresion = trim($nombreAreaImpresion, ', ');
				$imprimirOperacion = $cr->imprimirLineaOperacion($idSolicitud['id_operacion'], $qOperacion['nombre'], $nombreAreaImpresion);

				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $imprimirOperacion;
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'La operación ya ha sido ingresada previamente en el área y sitio seleccionado.';
			}
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'No es posible ingresar mas de un tipo de operación.';
		}
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