<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorCatalogos.php';

try{

	$inspector = htmlspecialchars($_POST['inspector'], ENT_NOQUOTES, 'UTF-8');
	$tipoSolicitud = htmlspecialchars($_POST['tipoSolicitud'], ENT_NOQUOTES, 'UTF-8');
	$tipoInspector = htmlspecialchars($_POST['tipoInspector'], ENT_NOQUOTES, 'UTF-8');
	$resultadoDocumento = htmlspecialchars($_POST['resultadoDocumento'], ENT_NOQUOTES, 'UTF-8');
	$observacionesDocumento = htmlspecialchars($_POST['observacionDocumento'], ENT_NOQUOTES, 'UTF-8');
	$idOperador = $_POST['identificadorOperador'];
	$idSolicitud = ($_POST['idSolicitud']);
	$idGrupoSolicitudes = explode(",", $idSolicitud);
	$codigoProvinciaSitio = htmlspecialchars($_POST['codigoProvinciaSitio'], ENT_NOQUOTES, 'UTF-8');

	$arrayResultados = array(
		'noHabilitado',
		'subsanacion',
		'subsanacionRepresentanteTecnico',
		'subsanacionProducto');

	try{
		$conexion = new Conexion();
		$crs = new ControladorRevisionSolicitudesVUE();
		$cc = new ControladorCatalogos();

		// Guardar resultado solicitud (cambio de estado)
		switch ($tipoSolicitud) {

			case 'Operadores':

				$cr = new ControladorRegistroOperador();

				if (in_array($resultadoDocumento, $arrayResultados)){

					foreach ($idGrupoSolicitudes as $solicitud){

						$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud));
						$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];

						$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
						$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

						$inspectorAsignado = $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

						$crs->guardarGrupo($conexion, $solicitud, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);

						$ordenInspeccion = $crs->buscarSerialOrden($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);

						$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento, pg_fetch_result($ordenInspeccion, 0, 'orden'));

						$cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $resultadoDocumento, $observacionesDocumento);

						$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $resultadoDocumento);

						$cr->cambiarEstadoActualizarCertificado($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], 'SI');

						$qcodigoTipoOperacion = $cc->obtenerCodigoTipoOperacion($conexion, $solicitud);
						$opcionArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
						$idArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
						//$idTipoOperacion = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_tipo_operacion');
					}

					switch ($idArea) {
						case 'AI':
							
                            switch ($opcionArea) {
								case 'PRO':
								case 'REC':
								case 'PRC':
								case 'COM':
                                	
                                	$cMail = new ControladorMail();
									$cr = new ControladorRegistroOperador();

									$cuerpoMensaje = '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
                					<style type="text/css">
                                		
                						.titulo  {
                							margin-top: 30px;
                							width: 800px;
                							text-align: center;
                							font-size: 14px;
                							font-weight: bold;
                							font-family:Times New Roman;
                						}
                                		
                						.lineaDos{
                							font-style: oblique;
                							font-weight: normal;
                						}
                                		
                						.lineaLeft{
                							text-align: left;
                						}
                                		
                						.lineaEspacio{
                							height: 35px;
                						}
                						.lineaEspacioMedio{
                							height: 50px;
                						}
                						.espacioLeft{
                							padding-left: 15px;
                						}
                					</style>';

									$qDatosOperador = $cr->buscarOperador($conexion, $idOperador);
									$datosOperador = pg_fetch_assoc($qDatosOperador);

									$nombreOperador = ($datosOperador['razon_social'] == "") ? $datosOperador['nombre_representante'] . ' ' . $datosOperador['apellido_representante'] : $datosOperador['razon_social'];

									$cuerpoMensaje .= '<table class="titulo">
                					<thead>
                					<tr><th></th></tr>
                					</thead>
                					<tbody>
                                    <tr><td class="lineaLeft lineaEspacio">Estimados Srs. <b>' . $nombreOperador . '</b></td></tr>
                					<tr><td class="lineaLeft lineaEspacio">Por medio del presente se comunica que su solicitud de registro de operador Orgánico Nro. ' . $idSolicitud . ' ha sido atendida.</td></tr>
                					<tr><td class="lineaLeft lineaEspacio"><b>RESULTADO REVISIÓN: </b>' . $resultadoDocumento . '</td></tr>
                                    <tr><td class="lineaLeft lineaEspacio"><b>OBSERVACIÓN: </b>' . $observacionesDocumento . '</td></tr>
                					</tbody>
                					<tfooter>
                					<tr><td class="lineaEspacioMedio"></td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft">Por favor ingresar a su perfil del Sistema GUIA y revisar con mejor detalle su registro, en el módulo Inscripción de operadores. </td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
                					<tr><td class="lineaDos lineaLeft espacioLeft">Saludos cordiales</td></tr>
									</tfooter>
                					</table>';

									$asunto = 'Resultado de revisión de Registro de Operador ORGÁNICO.';
									$codigoModulo = '';
									$tablaModulo = '';
									$destinatarios = array();

									array_push($destinatarios, $datosOperador['correo']);

									$qGuardarCorreo = $cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, '');
									$idCorreo = pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
									$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);

								break;
							}

						break;

						case "SA":

						break;

						case "SV":

						break;
					}
				}

			break;
		}

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';

		$conexion->desconectar();
		echo json_encode($mensaje);
	}catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}catch (Exception $ex){
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>