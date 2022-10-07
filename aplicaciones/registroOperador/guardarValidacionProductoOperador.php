<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cr = new ControladorRegistroOperador();

	$tipoProceso = false;

	$idSolicitud = htmlspecialchars($_POST['idOperacion'], ENT_NOQUOTES, 'UTF-8');
	$datosActualizacion = $_POST['datosTransaccion'];
	$identificadorOperador = htmlspecialchars($_POST['identificadorOp'], ENT_NOQUOTES, 'UTF-8');

	$qOperacion = $cr->abrirOperacionXid($conexion, $idSolicitud);
	$operacion = pg_fetch_assoc($qOperacion);
	
	$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $operacion['id_operador_tipo_operacion']);
	$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

	$qcodigoTipoOperacion = $cc->obtenerCodigoTipoOperacion($conexion, $idSolicitud);
	$opcionArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
	$idArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

	$idflujoOperacion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $idSolicitud));
	$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'cargarProducto'));
	$estado = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));

	try{
		if ($operacion['id_producto'] != null){
		    if (is_array($datosActualizacion) && count($datosActualizacion) != 0){
				foreach ($datosActualizacion as $dato){

					$registro = explode('-', $dato);
					$idOperacion = $registro[0];
					$idProducto = $registro[1];

					if ($operacion['estado'] != 'registrado'){
						$variedad = $cr->buscarVariedadOperacionProducto($conexion, $operacion['id_tipo_operacion'], $idProducto);
						$flujoVariedad = (pg_num_rows($variedad) == '0' ? 'flujoNormal' : 'variedad');

						if ($flujoVariedad == 'flujoNormal'){
							switch ($estado['estado']) {
								case 'registrado':
									$fechaActual = date('Y-m-d H-i-s');
									$cr->enviarOperacion($conexion, $idOperacion, 'registrado', 'Solicitud registrada ' . $fechaActual);
									$cr->cambiarEstadoAreaXidSolicitud($conexion, $idOperacion, 'registrado', 'Solicitud registrada ' . $fechaActual);
									$tipoProceso = true;
								break;
							}
						}else{
							$estado['estado'] = 'cargarIA';
							$cr->enviarOperacion($conexion, $idOperacion, 'cargarIA');
						}
					}else{
						$fechaActual = date('Y-m-d H-i-s');
						$cr->enviarOperacion($conexion, $idOperacion, 'registrado', 'Solicitud registrada ' . $fechaActual);
						$cr->cambiarEstadoAreaXidSolicitud($conexion, $idOperacion, 'registrado', 'Solicitud registrada ' . $fechaActual);
						$estado['estado'] = 'registrado';
						$tipoProceso = true;
					}
				}
				
				if($operacion['estado'] == 'subsanacionProducto'){
				    switch ($estado['estado']){
				        case 'cargarAdjunto':
				            $estado['estado'] = 'subsanacion';
				        break;
				        case 'cargarProducto':
				            $estado['estado'] = 'subsanacionProducto';
				        break;
				        case 'declararIMercanciaPecuaria':
				            $estado['estado'] = 'declararIMercanciaPecuaria';
				        break;
						case 'cargarRendimiento':
				        	$estado['estado'] = 'cargarRendimiento';
				        break;
						case 'declararIColmenar':
                            $estado['estado'] = 'declararIColmenar';
                        break;
				        default:
				            $estado['estado'] = $operacion['estado_anterior'];
				    }
				}else{
				    $cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion']);
				}
				
				$cr->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $operacion['id_operador_tipo_operacion'], $estado['estado']);
				$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
				if($tipoProceso){
				    $cr->actualizarProcesoActualizacionOperacion($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion']);
				}
			}else{
			    
			    if($operacion['estado'] == 'subsanacionProducto'){
			        switch ($estado['estado']){
			            case 'cargarAdjunto':
			                $estado['estado'] = 'subsanacion';
			            break;
			            case 'cargarProducto':
			                $estado['estado'] = 'subsanacionProducto';
			            break;
			            case 'declararIMercanciaPecuaria':
			                $estado['estado'] = 'declararIMercanciaPecuaria';
			            break;
						case 'cargarRendimiento':
				        	$estado['estado'] = 'cargarRendimiento';
				        break;
						case 'declararIColmenar':
						    $estado['estado'] = 'declararIColmenar';
						break;
			            default:
			                $estado['estado'] = $operacion['estado_anterior'];
			        }
			    }else{
			        $cr->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion']);
			    }
			    
				switch ($estado['estado']){
					
					case 'cargarAdjunto':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'inspeccion':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'documental':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'pago':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'cargarRendimiento':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'declararProveedor':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'cargarProducto':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'declararICentroAcopio':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'declararDVehiculo':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'representanteTecnico':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'cargarIA':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'declararIMercanciaPecuaria':
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
						break;
					case 'subsanacion':
					    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
					    break;
					case 'subsanacionProducto':
					    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
					    break;
					case 'declararIColmenar':
					    $cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado']);
					    break;
					case'registrado':
						$fechaActual = date('Y-m-d H-i-s');
						$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion'], $estado['estado'], 'Solicitud aprobada '.$fechaActual);
						$cr->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion']);
						$cr->actualizarProcesoActualizacionOperacion($conexion, $operacion['id_operador_tipo_operacion'], $historialOperacion['id_historial_operacion']);
						break;
				}
			}
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Por favor ingrese por lo menos un producto.';
		}
		
		if ($operacion['id_vigencia_documento'] != 0){
		    $cr->actualizarFechaAprobacionFinalizacionOperaciones($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion'], $operacion['fecha_aprobacion'], $operacion['fecha_finalizacion'], $operacion['id_vigencia_documento']);
		}
		
		if ($tipoProceso){
			$modulosAgregados = "";
			$perfilesAgregados = "";

			switch ($idArea) {

				case 'SA':
					switch ($opcionArea) {
						case 'MVB':
						case 'MVC':
						case 'MVE':
							$qOperaciones = $cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador, "('$opcionArea')", "('$idArea')");

							if (pg_num_rows($qOperaciones) > 0){
								$modulosAgregados .= "('PRG_NOTIF_ENF'),";
								$perfilesAgregados .= "('PFL_NOTIF_ENF'),";
							}

						break;
						case 'FER':
							$qOperaciones = $cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador, "('$opcionArea')", "('$idArea')");

							if (pg_num_rows($qOperaciones) > 0){
								$modulosAgregados .= "('PRG_MOVIL_PRODU'),";
								$perfilesAgregados .= "('PFL_FISCA_MOVIL'),";
							}

						break;
						case 'OPT':
                                $qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador,"('$opcionArea')","('$idArea')");
                                
                                if(pg_num_rows($qOperaciones)>0){
                                    $modulosAgregados.="('PRG_CATAS_PRODU'),";
                                    $perfilesAgregados.="('PFL_ADM_ACT_PORC'),";
                                }
                        break;
						case 'PRO':
							$modulosAgregados .= "('PRG_CERT_BPA'),";
							$perfilesAgregados .= "('PFL_USR_CERT_BPA'),";
						break;
						case 'OCC':
						    require_once '../../clases/ControladorCatastroProducto.php';
						    $ccp = new ControladorCatastroProducto();
						    
						    $identificadorOperador = $operacion['identificador_operador'];
						    $qOperadorModificacionIdentificador = $ccp->buscarOperadorModificacionIdentificador($conexion, $identificadorOperador);
						    
						    if(pg_num_rows($qOperadorModificacionIdentificador) == 0){
						        $ccp->insertarOperadorModificacionIdentificador($conexion, $identificadorOperador);
						    }
					   break;
					}
				break;

				case 'SV':
					$contador = 0;
					switch ($opcionArea) {
						case 'ACO':
							$qOperaciones = $cr->buscarOperacionesPorCodigoyAreaOperacionFloresFollajes($conexion, $identificadorOperador, "('$opcionArea')", "('$idArea')");

							if (pg_num_rows($qOperaciones) > 0){
								$modulosAgregados .= "('PRG_EMISI_ETIQU'),";
								$perfilesAgregados .= "('PFL_SOLIC_ETIQU'),";
							}
							
							$modulosAgregados .= "('PRG_CERT_FITO'),";
							$perfilesAgregados .= "('PFL_USR_CERT_FIT'),";
							
							$qOperacionesCacao = $cr->buscarOperacionesPorCodigoyAreaOperacionCacao($conexion, $identificadorOperador, "('$opcionArea')", "('$idArea')");

							if (pg_num_rows($qOperacionesCacao) > 0){
								$modulosAgregados .= "('PRG_CONFO_LOTE'),";
								$perfilesAgregados .= "('PFL_CONFO_LOTE'),";
							}

							$qOperacionesPitahaya = $cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador, "('$opcionArea')", "('$idArea')");

							if (pg_num_rows($qOperacionesPitahaya) > 0){
								$modulosAgregados .= "('PRG_CONFO_LOTE'),";
								$perfilesAgregados .= "('PFL_CONFO_LOTE'),";
							}

						break;

						case 'TRA':

							$qOperacionesCacao = $cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador, "('$opcionArea')", "('$idArea')");

							if (pg_num_rows($qOperacionesCacao) > 0){
								$modulosAgregados .= "('PRG_CONFO_LOTE'),";
								$perfilesAgregados .= "('PFL_CONFO_LOTE'),";
							}

						break;

						case 'COM':
							$qOperaciones = $cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $identificadorOperador, "('$opcionArea')", "('$idArea')");

							if (pg_num_rows($qOperaciones) > 0){
								$modulosAgregados .= "('PRG_EMISI_ETIQU'),";
								$perfilesAgregados .= "('PFL_SOLIC_ETIQU'),";
							}
							
							$modulosAgregados .= "('PRG_CERT_FITO'),";
							$perfilesAgregados .= "('PFL_USR_CERT_FIT'),";
							
						break;
						
						case 'CFE':
						case 'AGE':
							$modulosAgregados .= "('PRG_CERT_FITO'),";
							$perfilesAgregados .= "('PFL_USR_CERT_FIT'),";
						break;
						case 'PRO':
							$modulosAgregados .= "('PRG_CERT_BPA'),";
							$perfilesAgregados .= "('PFL_USR_CERT_BPA'),";
						break;
					}
				break;
				case 'IAF':
					switch ($opcionArea) {
						case 'DIS':
						case 'ENV':
						case 'ALM':
						case 'FIE':
						case 'IDE':
							$cMail = new ControladorMail();
							$codigoModulo = 'PRG_REG_OPERADOR';
							$tablaModulo = 'cargar_producto';

							$verificacionMail = $cMail->buscarIngresoPrevioMail($conexion, $codigoModulo, $tablaModulo, $operacion['id_operador_tipo_operacion']);

							if (pg_num_rows($verificacionMail) == 0){

								$controladorRevisionSolicitudes = new ControladorRevisionSolicitudesVUE();
								$ce = new ControladorEmpleados();

								$datosOperacion = $cr->abrirOperacion($conexion, $identificadorOperador, $idOperacion);
								$datosOperador = pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion, $identificadorOperador));

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

								$cuerpoMensaje .= '<table class="titulo">
									<thead>
									<tr><th>Estimado/a,</th></tr>
									</thead>
									<tbody>
									<tr><td class="lineaDos lineaEspacio">Se le comunica que usted tiene pendiente la generación del certificado de registro de operador que ha realizado el proceso de cargar producto:</td></tr>
									<tr><td class="lineaDos lineaEspacio"><b>Nombre Operador: </b>' . $datosOperador['nombre_operador'] . '</td></tr>
									<tr><td class="lineaDos lineaEspacio"><b>Identificación Operador: </b>' . $identificadorOperador . '</td></tr>
									<tr><td class="lineaDos lineaEspacio"><b>Sitio: </b>' . $datosOperacion[0]['nombreSitio'] . '</td></tr>
									<tr><td class="lineaDos lineaEspacio"><b>Operación: </b>' . $datosOperacion[0]['tipoOperacion'] . '</td></tr>
									<tr><td class="lineaDos lineaEspacio">Ingrese al siguiente link para revisar dicho registro: </td></tr>
									<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
									</tbody>
									<tfooter>
									<tr><td class="lineaEspacioMedio"></td></tr>
									<tr><td class="lineaDos lineaLeft espacioLeft"><span style="font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
									<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
									</tfooter>
									</table>';

								$asunto = 'Proceso de carga de producto realizado por el operador.';
								$destinatarios = array();

								$tipoInspector = 'Documental';

								$tecnicoRevision = pg_fetch_assoc($controladorRevisionSolicitudes->buscarEstadoSolicitudXtipoInspector($conexion, $idOperacion, 'Operadores', $tipoInspector));
								$datosTecnico = pg_fetch_assoc($ce->obtenerFichaEmpleado($conexion, $tecnicoRevision['identificador_inspector']));

								if ($datosTecnico['mail_institucional'] != ''){
									array_push($destinatarios, $datosTecnico['mail_institucional']);
								}

								if ($datosTecnico['mail_personal'] != ''){
									array_push($destinatarios, $datosTecnico['mail_personal']);
								}

								$qGuardarCorreo = $cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $operacion['id_operador_tipo_operacion']);
								$idCorreo = pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
								$cMail->guardarDestinatario($conexion, $idCorreo, $destinatarios);
							}
							
							if($opcionArea == 'FIE' || $opcionArea == 'IDE'){
									$modulosAgregados.="('PRG_IMP_FERTILI'),";
									$perfilesAgregados.="('PFL_SOL_IMP_FERT'),";
							}

						break;
					}
				break;
				case 'CGRIA':
					switch ($opcionArea) {
						case 'ODI':
							$modulosAgregados.="('PRG_DOSSIER_PEC'),";
							$perfilesAgregados.="('PFL_EE_OI'),";
						break;
					}
				break;
				case 'AI':
					switch ($opcionArea) {
						case 'FAE':
							$modulosAgregados.="('PRG_EMI_CERT_ORI'),";
							$perfilesAgregados.="('PFL_EMI_CERT'),";
							break;
					}
				break;
				case 'IAV':
				    switch ($opcionArea) {
				        case 'DIS':
				        case 'FOR':
				        case 'FRA':
				            $modulosAgregados.="('PRG_PROV_EXTE'),";
				            $perfilesAgregados.="('PFL_USR_PROV_EXT'),";
				            break;
				    }
				break;
			}

			if (strlen($modulosAgregados) == 0){
				$modulosAgregados = "''";
				$perfilesAgregados = "''";
			}

			$cu = new ControladorUsuarios();
			$ca = new ControladorAplicaciones();
			$cgap = new ControladorGestionAplicacionesPerfiles();

			$qGrupoAplicacion = $cgap->obtenerGrupoAplicacion($conexion, '(' . rtrim($modulosAgregados, ',') . ')');

			if (pg_num_rows($qGrupoAplicacion) > 0){
				while ($filaAplicacion = pg_fetch_assoc($qGrupoAplicacion)){
					if (pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'], $identificadorOperador)) == 0){
						$cgap->guardarGestionAplicacion($conexion, $identificadorOperador, $filaAplicacion['codificacion_aplicacion']);
						$qGrupoPerfiles = $cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '(' . rtrim($perfilesAgregados, ',') . ')');
						while ($filaPerfil = pg_fetch_assoc($qGrupoPerfiles)){
							$cgap->guardarGestionPerfil($conexion, $identificadorOperador, $filaPerfil['codificacion_perfil']);
						}
					}else{
						$qGrupoPerfiles = $cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], '(' . rtrim($perfilesAgregados, ',') . ')');
						while ($filaPerfil = pg_fetch_assoc($qGrupoPerfiles)){
							$qPerfil = $cu->obtenerPerfilUsuario($conexion, $filaPerfil['id_perfil'], $identificadorOperador);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $identificadorOperador, $filaPerfil['codificacion_perfil']);
						}
					}
				}
			}
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido registrados con éxito.';
		}else{
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido registrados con éxito.';
		}

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
