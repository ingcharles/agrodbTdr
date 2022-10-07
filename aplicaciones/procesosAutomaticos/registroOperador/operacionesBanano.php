<?php
//if ($_SERVER['REMOTE_ADDR'] == '') {
if(1){

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorUsuarios.php';
require_once '../../../clases/ControladorMonitoreo.php';
require_once '../../../clases/ControladorAplicaciones.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorGestionAplicacionesPerfiles.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cu = new ControladorUsuarios();
$cm = new ControladorMonitoreo();
$ca = new ControladorAplicaciones();
$cgap = new ControladorGestionAplicacionesPerfiles();

set_time_limit(60000);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('PRO_MSG', '<br/> ');
define('IN_MSG', '<br/> >>> ');
$fecha = date("Y-m-d h:m:s");
$numero = '1';

//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_DATO_OPERACIONES_BANANO');

	//if($resultadoMonitoreo){
	if(1){
		
		echo IN_MSG . '<b>INICIO PROCESO DE CREACION DE SITIOS, AREAS Y OPERACIONES A OPERADORES ' . $fecha . '</b>';
		
		$operaciones = $cr->obtenerOperacionesBanano($conexion);

		while ($operacion = pg_fetch_assoc($operaciones)) {

			echo IN_MSG . $numero ++ . '.- Identificador operador: ' . $operacion['identificador'] . ' con id: ' . $operacion['id'];

			$idRegistro = $operacion['id'];
			
			$cr->actulizarEstadoOperacionesBanano($conexion, $idRegistro, 'W');

			$datos = array(
				'id' => trim(htmlspecialchars($operacion['id'], ENT_NOQUOTES, 'UTF-8')),
				'identificador' => trim(htmlspecialchars($operacion['identificador'], ENT_NOQUOTES, 'UTF-8')),
				'nombreSitio' => trim(htmlspecialchars($operacion['nombre_sitio'], ENT_NOQUOTES, 'UTF-8')),
				'superficieTotal' => trim(htmlspecialchars($operacion['superficie_total'], ENT_NOQUOTES, 'UTF-8')),
				'provincia' => trim(htmlspecialchars($operacion['provincia'], ENT_NOQUOTES, 'UTF-8')),
				'canton' => trim(htmlspecialchars($operacion['canton'], ENT_NOQUOTES, 'UTF-8')),
				'parroquia' => trim(htmlspecialchars($operacion['parroquia'], ENT_NOQUOTES, 'UTF-8')),
				'direccion' => trim(htmlspecialchars($operacion['direccion'], ENT_NOQUOTES, 'UTF-8')),
				'telefono' => trim(htmlspecialchars($operacion['telefono'], ENT_NOQUOTES, 'UTF-8')),
				'latitud' => trim(htmlspecialchars($operacion['latitud'], ENT_NOQUOTES, 'UTF-8')),
				'longitud' => trim(htmlspecialchars($operacion['longitud'], ENT_NOQUOTES, 'UTF-8')),
				'tipoArea' => trim(htmlspecialchars($operacion['tipo_area'], ENT_NOQUOTES, 'UTF-8')),
				'superficieUtilizada' => trim(htmlspecialchars($operacion['superficie_utilizada'], ENT_NOQUOTES, 'UTF-8')),
				'nombreArea' => trim(htmlspecialchars($operacion['nombre_area'], ENT_NOQUOTES, 'UTF-8')),
				'tipoOperacion' => trim(htmlspecialchars($operacion['tipo_operacion'], ENT_NOQUOTES, 'UTF-8')),
				'producto' => trim(htmlspecialchars($operacion['producto'], ENT_NOQUOTES, 'UTF-8')),
				'codigoTransaccion' => trim(htmlspecialchars($operacion['codigo_hacienda'], ENT_NOQUOTES, 'UTF-8'))
			);

			$vOperador = $cr->buscarOperador($conexion, $datos['identificador']);
			$usuario = $cu->verificarUsuario($conexion, $datos['identificador']);

			if (pg_num_rows($vOperador) == 0 || pg_num_rows($usuario) == 0) {

				if (pg_num_rows($vOperador) == 0) {
					echo IN_MSG . 'El operador no se encuentra registrado en Agrocalidad.';
				}

				if (pg_num_rows($usuario) == 0) {
					echo IN_MSG . 'El usuario no se encuentra registrado en Agrocalidad.';
				}
				echo '</br>';
			} else {

				$provincia = pg_fetch_assoc($cr->obtenerLocalizacionPorNombre($conexion, $datos['provincia'], 1, 'provincia'));
				$canton = pg_fetch_assoc($cr->obtenerLocalizacionPorNombre($conexion, $datos['canton'], 2, 'canton', ($provincia['id_localizacion'] == '' ? 0 : $provincia['id_localizacion'])));
				$parroquia = pg_fetch_assoc($cr->obtenerLocalizacionPorNombre($conexion, $datos['parroquia'], 3, 'parroquia', ($canton['id_localizacion'] == '' ? 0 : $canton['id_localizacion'])));

				echo IN_MSG . 'Búsqueda  de sitio de operador.';

				$qSitio = $cr->bucarSitioPorNombreIdentificador($conexion, $datos['identificador'], $datos['nombreSitio'], $datos['superficieTotal']);

				if (pg_num_rows($qSitio) == 0) {

					echo IN_MSG . 'Generación código de sitio de operador.';
					$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, $provincia['nombre'], $datos['identificador']);
					$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);

					echo IN_MSG . 'Creación de sitio de operador.';
					$qIdSitio = $cr->guardarNuevoSitio($conexion, $datos['nombreSitio'], $provincia['nombre'], $canton['nombre'], $parroquia['nombre'], $datos['direccion'], '', $datos['superficieTotal'], $datos['identificador'], $datos['telefono'], $datos['latitud'], $datos['longitud'], $secuencialSitio, '', '17', substr($provincia['codigo_vue'], 1));
					$idSitio = pg_fetch_assoc($qIdSitio);
				} else {
					echo IN_MSG . 'Posee un sitio creado se asocia el id.';
					$idSitio = pg_fetch_assoc($qSitio);
				}
				
				if ($datos['tipoArea'] == 'Lugar de producción') {
					$codigoArea = pg_fetch_assoc($cr->bucarCodigoCatalogoAreaPorNombre($conexion, $datos['tipoArea']));
					$idTipoOperacion = pg_fetch_result($cr->bucarCodigoCatalogoTipoOperacionPorCodigo($conexion, 'SV', 'PRB'), 0, 'id_tipo_operacion');
					//$codigoArea = '06';
				} else if ($datos['tipoArea'] == 'Domicilio tributario') {
					//$codigoArea = '09';
					$codigoArea = pg_fetch_assoc($cr->bucarCodigoCatalogoAreaPorNombre($conexion, $datos['tipoArea']));
					$idTipoOperacion = pg_fetch_result($cr->bucarCodigoCatalogoTipoOperacionPorCodigo($conexion, 'SV', 'EXB'), 0, 'id_tipo_operacion');
				}

				$qArea = $cr->bucarAreaPorNombreSitioTipoArea($conexion, $idSitio['id_sitio'], $datos['nombreArea'], $codigoArea['nombre'], $datos['superficieUtilizada'], $datos['codigoTransaccion']);

				if (pg_num_rows($qArea) == 0) {

					echo IN_MSG . 'Generación código de área de operador.';
					$qSecuencialArea = $cr->obtenerSecuencialArea($conexion, $datos['identificador'], $codigoArea['codigo'], $provincia['nombre']);
					$secuencialArea = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);

					echo IN_MSG . 'Creación de área de operador.';
					$area = $cr->guardarNuevaArea($conexion, $datos['nombreArea'], $codigoArea['nombre'], $datos['superficieUtilizada'], $idSitio['id_sitio'], $codigoArea['codigo'], $secuencialArea, $datos['codigoTransaccion']);
					$idArea = pg_fetch_assoc($area);
				} else {
					echo IN_MSG . 'Posee una área creada se asocia el id.';
					$idArea = pg_fetch_assoc($qArea);
				}
				
				$vAreaProductoOperacion = $cr->buscarAreasOperacionPorSolicitud($conexion, $idTipoOperacion, $idArea['id_area'], $datos['identificador']);
				
				if(pg_num_rows($vAreaProductoOperacion) == 0){
					echo IN_MSG . 'Generación del operador tipo operación del operador.';
					
					$qIdOperadorTipoOperacion = $cr->guardarTipoOperacionPorIndentificadorSitio($conexion, $datos['identificador'], $idSitio['id_sitio'], $idTipoOperacion);
					$idOperadorTipoOperacion = pg_fetch_assoc($qIdOperadorTipoOperacion);
					
					echo IN_MSG . 'Generación del historial de tipo operación del operador.';
					$qHistorialOperacion = $cr->guardarDatosHistoricoOperacion($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion']);
					$historicoOperacion = pg_fetch_assoc($qHistorialOperacion);
					
					$idVigenciaDocumento = 0;
					
					echo IN_MSG . 'Creación de la operación del operador.';
					$qIdSolicitud= $cr->guardarNuevaOperacionPorTipoOperacion($conexion, $idTipoOperacion, $datos['identificador'], $idOperadorTipoOperacion['id_operador_tipo_operacion'], $historicoOperacion['id_historial_operacion'], 'creado', $idVigenciaDocumento);
					$idSolicitud = pg_fetch_assoc($qIdSolicitud);
					
					$cr->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion'], $idSolicitud['id_operacion']);
					
					$cr->guardarAreaOperacion($conexion, $idArea['id_area'], $idSolicitud['id_operacion']);
					$cr->guardarAreaPorIdentificadorTipoOperacion($conexion, $idArea['id_area'], $idOperadorTipoOperacion['id_operador_tipo_operacion']);

					echo IN_MSG . 'Actualización de estado de operación del operador.';
					$cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'cargarProducto');
					
					$producto = pg_fetch_assoc($cr->obtenerCodigoProducto($conexion, 'Frutas, hortalizas y tubérculos frescos', 'Fruta', $datos['producto']));
					$todosProductos = "(" . rtrim($producto['id_producto'], ',') . ")";
					
					$vAreaProductoOperacionS = $cr->buscarAreasOperacionProductoXSolicitud($conexion, $idTipoOperacion, $todosProductos, $idArea['id_area'], $datos['identificador']);
					
					if(pg_num_rows($vAreaProductoOperacionS) ==0){
						
						$qOperacion = $cr->abrirOperacionXid($conexion, $idSolicitud['id_operacion']);
						$operacion = pg_fetch_assoc($qOperacion);
						
						if ($operacion['id_producto'] == null){
							
							$cr->actualizarProductoOperacion($conexion, $idSolicitud['id_operacion'], $producto['id_producto'], $producto['nombre_comun']);
							$idSolicitud = $idSolicitud['id_operacion'];
						}else{
							
							$qIdSolicitud = $cr->guardarNuevaOperacionPorTipoOperacion($conexion, $operacion['id_tipo_operacion'], $datos['identificador'], $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion'], 'cargarProducto', $idVigenciaDocumento);
							$idSolicitud = pg_fetch_result($qIdSolicitud, 0, 'id_operacion');
							$cr->actualizarProductoOperacion($conexion, $idSolicitud, $producto['id_producto'], $producto['nombre_comun'], $idVigenciaDocumento);
							$cr->guardarAreaOperacion($conexion, $idArea['id_area'], $idSolicitud);
						}
						
						$cr->enviarOperacionEstadoAnterior($conexion, $idSolicitud);
						
						$fechaActual = date('Y-m-d H-i-s');
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion'], 'registrado', 'Solicitud aprobada '.$fechaActual);
						$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion']);
						
						$cr-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion'], 'registrado');
						
					}else{
						echo IN_MSG . 'Posee una operación con el producto deseado.';
					}
					
				}else{
					echo IN_MSG . 'Ya posee operación.';
					
					echo IN_MSG . 'Agregacion de nuevo producto.';
					$idVigenciaDocumento = 0;
					$producto = pg_fetch_assoc($cr->obtenerCodigoProducto($conexion, 'Frutas, hortalizas y tubérculos frescos', 'Fruta', $datos['producto']));
					$todosProductos = "(" . rtrim($producto['id_producto'], ',') . ")";
					
					$vAreaProductoOperacionS = $cr->buscarAreasOperacionProductoXSolicitud($conexion, $idTipoOperacion, $todosProductos, $idArea['id_area'], $datos['identificador']);
					
					if(pg_num_rows($vAreaProductoOperacionS) ==0){
						
						$qOperacion = $cr->abrirOperacionXid($conexion, pg_fetch_result($vAreaProductoOperacion, 0, 'id_operacion'));
						$operacion = pg_fetch_assoc($qOperacion);
						
						if ($operacion['id_producto'] == null){
							
							$cr->actualizarProductoOperacion($conexion, $idSolicitud['id_operacion'], $producto['id_producto'], $producto['nombre_comun']);
							$idSolicitud = $idSolicitud['id_operacion'];
						}else{
							
							$qIdSolicitud = $cr->guardarNuevaOperacionPorTipoOperacion($conexion, $operacion['id_tipo_operacion'], $datos['identificador'], $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion'], 'cargarProducto', $idVigenciaDocumento);
							$idSolicitud = pg_fetch_result($qIdSolicitud, 0, 'id_operacion');
							$cr->actualizarProductoOperacion($conexion, $idSolicitud, $producto['id_producto'], $producto['nombre_comun'], $idVigenciaDocumento);
							$cr->guardarAreaOperacion($conexion, $idArea['id_area'], $idSolicitud);
						}
						
						$cr->enviarOperacionEstadoAnterior($conexion, $idSolicitud);
						
						$fechaActual = date('Y-m-d H-i-s');
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion'], 'registrado', 'Solicitud aprobada '.$fechaActual);
						$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion']);
						
						$cr-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $operacion['id_operador_tipo_operacion'], 'registrado');
					}else{
						echo IN_MSG . 'Posee una operación con el producto deseado.';
					}
				}
				
				echo IN_MSG . 'Creación de operación del operador finalizada.</br>';
				$cr->actulizarEstadoOperacionesBanano($conexion, $idRegistro, 'Atendida');
				
				echo IN_MSG . 'Asignacion de aplicacion registro de musaceas.</br>';
				
				$modulosAgregados = "('PRG_INSP_MUS'),";
				$perfilesAgregados = "('PFL_EXT_MUS'),";
				
				$qGrupoAplicacion = $cgap->obtenerGrupoAplicacion($conexion, '(' . rtrim($modulosAgregados, ',') . ')');
				
				if (pg_num_rows($qGrupoAplicacion) > 0){
					while ($filaAplicacion = pg_fetch_assoc($qGrupoAplicacion)){
						if (pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'], $datos['identificador'])) == 0){
							$cgap->guardarGestionAplicacion($conexion, $datos['identificador'], $filaAplicacion['codificacion_aplicacion']);
							$qGrupoPerfiles = $cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '(' . rtrim($perfilesAgregados, ',') . ')');
							while ($filaPerfil = pg_fetch_assoc($qGrupoPerfiles)){
								$cgap->guardarGestionPerfil($conexion, $datos['identificador'], $filaPerfil['codificacion_perfil']);
							}
						}else{
							$qGrupoPerfiles = $cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '(' . rtrim($perfilesAgregados, ',') . ')');
							while ($filaPerfil = pg_fetch_assoc($qGrupoPerfiles)){
								$qPerfil = $cu->obtenerPerfilUsuario($conexion, $filaPerfil['id_perfil'], $datos['identificador']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['identificador'], $filaPerfil['codificacion_perfil']);
							}
						}
					}
				}
				
				echo IN_MSG . 'Fin asignacion de aplicacion registro de musaceas.</br>';
			}
		}
		
		echo IN_MSG . '<b>INICIO PROCESO DE INACTIVACION DE SITIOS, AREAS Y OPERACIONES A OPERADORES ' . $fecha . '</b>';
		
		$operaciones = $cr->obtenerOperacionesBanano($conexion, 'Por inactivar');
		
		while ($operacion = pg_fetch_assoc($operaciones)) {
			
			$idRegistro = $operacion['id'];
			
			$cr->actulizarEstadoOperacionesBanano($conexion, $idRegistro, 'W');
			
			$datos = array(
				'id' => trim(htmlspecialchars($operacion['id'], ENT_NOQUOTES, 'UTF-8')),
				'identificador' => trim(htmlspecialchars($operacion['identificador'], ENT_NOQUOTES, 'UTF-8')),
				'nombreSitio' => trim(htmlspecialchars($operacion['nombre_sitio'], ENT_NOQUOTES, 'UTF-8')),
				'superficieTotal' => trim(htmlspecialchars($operacion['superficie_total'], ENT_NOQUOTES, 'UTF-8')),
				'provincia' => trim(htmlspecialchars($operacion['provincia'], ENT_NOQUOTES, 'UTF-8')),
				'canton' => trim(htmlspecialchars($operacion['canton'], ENT_NOQUOTES, 'UTF-8')),
				'parroquia' => trim(htmlspecialchars($operacion['parroquia'], ENT_NOQUOTES, 'UTF-8')),
				'direccion' => trim(htmlspecialchars($operacion['direccion'], ENT_NOQUOTES, 'UTF-8')),
				'telefono' => trim(htmlspecialchars($operacion['telefono'], ENT_NOQUOTES, 'UTF-8')),
				'latitud' => trim(htmlspecialchars($operacion['latitud'], ENT_NOQUOTES, 'UTF-8')),
				'longitud' => trim(htmlspecialchars($operacion['longitud'], ENT_NOQUOTES, 'UTF-8')),
				'tipoArea' => trim(htmlspecialchars($operacion['tipo_area'], ENT_NOQUOTES, 'UTF-8')),
				'superficieUtilizada' => trim(htmlspecialchars($operacion['superficie_utilizada'], ENT_NOQUOTES, 'UTF-8')),
				'nombreArea' => trim(htmlspecialchars($operacion['nombre_area'], ENT_NOQUOTES, 'UTF-8')),
				'tipoOperacion' => trim(htmlspecialchars($operacion['tipo_operacion'], ENT_NOQUOTES, 'UTF-8')),
				'producto' => trim(htmlspecialchars($operacion['producto'], ENT_NOQUOTES, 'UTF-8')),
				'codigoTransaccion' => trim(htmlspecialchars($operacion['codigo_hacienda'], ENT_NOQUOTES, 'UTF-8'))
			);
			
			$qSitio = $cr->bucarSitioPorNombreIdentificador($conexion, $datos['identificador'], $datos['nombreSitio'], $datos['superficieTotal']);
			
			if (pg_num_rows($qSitio) != 0) {
				echo IN_MSG . 'Posee un sitio creado se asocia el id.';
				$idSitio = pg_fetch_assoc($qSitio);
				
				if ($datos['tipoArea'] == 'Lugar de producción') {
					$codigoArea = pg_fetch_assoc($cr->bucarCodigoCatalogoAreaPorNombre($conexion, $datos['tipoArea']));
					$idTipoOperacion = pg_fetch_result($cr->bucarCodigoCatalogoTipoOperacionPorCodigo($conexion, 'SV', 'PRB'), 0, 'id_tipo_operacion');
					//$codigoArea = '06';
				} else if ($datos['tipoArea'] == 'Domicilio tributario') {
					//$codigoArea = '09';
					$codigoArea = pg_fetch_assoc($cr->bucarCodigoCatalogoAreaPorNombre($conexion, $datos['tipoArea']));
					$idTipoOperacion = pg_fetch_result($cr->bucarCodigoCatalogoTipoOperacionPorCodigo($conexion, 'SV', 'EXB'), 0, 'id_tipo_operacion');
				}
				
				$qArea = $cr->bucarAreaPorNombreSitioTipoArea($conexion, $idSitio['id_sitio'], $datos['nombreArea'], $codigoArea['nombre'], $datos['superficieUtilizada'], $datos['codigoTransaccion']);
				
				if (pg_num_rows($qArea) != 0) {
					echo IN_MSG . 'Posee una área creada se asocia el id.';
					$idArea = pg_fetch_assoc($qArea);
					
					$producto = pg_fetch_assoc($cr->obtenerCodigoProducto($conexion, 'Frutas, hortalizas y tubérculos frescos', 'Fruta', $datos['producto']));
					$todosProductos = "(" . rtrim($producto['id_producto'], ',') . ")";
					
					$vAreaProductoOperacionS = $cr->buscarAreasOperacionProductoXSolicitud($conexion, $idTipoOperacion, $todosProductos, $idArea['id_area'], $datos['identificador']);
					
					
					if(pg_num_rows($vAreaProductoOperacionS) !=0){
						echo IN_MSG . 'Posee una operacion creada se asocia el id.';
						
						$idOperacion = pg_fetch_result($vAreaProductoOperacionS, 0, 'id_operacion');
						
						$cr->enviarOperacionEstadoAnterior($conexion, $idOperacion);
						
						$cr->enviarOperacion($conexion, $idOperacion, 'noHabilitado', 'Operación no habilitada, por proceso de actualización MAG');
						
						$cr->cambiarEstadoAreaXidSolicitud($conexion, $idOperacion, 'noHabilitado', 'Operación no habilitada, por proceso de actualización MAG');
						
						echo IN_MSG . 'Eliminacion de aplicacion registro de musaceas.</br>';
						
						$verificacionOperacion = $cr->verificarOperacionesBanano($conexion,  $datos['identificador']);
						
						if(pg_num_rows($verificacionOperacion) == 0){
							
							$modulosAgregados = "('PRG_INSP_MUS'),";
							
							$qGrupoAplicacion = $cgap->obtenerGrupoAplicacion($conexion, '(' . rtrim($modulosAgregados, ',') . ')');
							
							$ca->eliminarAplicacion($conexion, $datos['identificador'], pg_fetch_result($qGrupoAplicacion, 0, 'id_aplicacion'));
							
							echo IN_MSG . 'Ingreso eliminacion de aplicacion registro de musaceas.</br>';
							
						}
						
						echo IN_MSG . 'Fin eliminacion de aplicacion registro de musaceas.</br>';
						
					}else{
						echo IN_MSG . 'No posee una operacion creada.';
					}
				} else {
					echo IN_MSG . 'No posee una área creada.';
				}
			} else {
				echo IN_MSG . 'No posee un sitio creado.';
			}
			
			echo IN_MSG . 'Inactivación de operación del operador finalizada.</br>';
			$cr->actulizarEstadoOperacionesBanano($conexion, $idRegistro, 'Inactivado');
		}
		
		echo IN_MSG. 'FIN DE PROCESO.';
		
		
	}
}else{
	
	$minutoS1 = microtime(true);
	$minutoS2 = microtime(true);
	$tiempo = $minutoS2 - $minutoS1;
	$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
	$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
	$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
	$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
	$arch = fopen("../../../aplicaciones/logs/cron/automatico_datos_operaciones_banano" . date("d-m-Y") . ".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}

?>