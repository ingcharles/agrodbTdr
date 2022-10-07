<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$datos = array(
			'clasificacion' => htmlspecialchars ($_POST['clasificacion'],ENT_NOQUOTES,'UTF-8'),
			'identificador' => htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8'),
			'nombreSitio' => htmlspecialchars ($_POST['nombreSitio'],ENT_NOQUOTES,'UTF-8'),
			'razon' => htmlspecialchars ($_POST['razonSocial'],ENT_NOQUOTES,'UTF-8'),
			'nombreLegal' => htmlspecialchars ($_POST['nombreLegal'],ENT_NOQUOTES,'UTF-8'),
			'apellidoLegal' => htmlspecialchars ($_POST['apellidoLegal'],ENT_NOQUOTES,'UTF-8'),
			'idProvincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
			'idCanton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
			'idParroquia' => htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8'),
			'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
			'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'),
			'celular' => htmlspecialchars ($_POST['celular'],ENT_NOQUOTES,'UTF-8'),
			'idProducto' => $_POST['iProducto'],
			'tipoOperacion' => htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8'),
			'areaOperacion' => htmlspecialchars ($_POST['areaOperacion'],ENT_NOQUOTES,'UTF-8'),
			'idFlujo' => htmlspecialchars ($_POST['idFlujo'],ENT_NOQUOTES,'UTF-8'),
			'latitud' => htmlspecialchars ($_POST['latitud'],ENT_NOQUOTES,'UTF-8'),
			'longitud' => htmlspecialchars ($_POST['longitud'],ENT_NOQUOTES,'UTF-8'),
			'zona' => htmlspecialchars ($_POST['zona'],ENT_NOQUOTES,'UTF-8')
	);

	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		$cu = new ControladorUsuarios();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAplicaciones();
		$cgap= new ControladorGestionAplicacionesPerfiles();
		$cvd = new ControladorVigenciaDocumentos();


		$arrayIdArea= array();
		$conexion->ejecutarConsulta("begin;");

		$operador = $cr->buscarOperador($conexion, $datos['identificador']);
		$usuario = $cu->verificarUsuario($conexion,$datos['identificador']);

		$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['idProvincia']);
		$provincia = pg_fetch_assoc($res);

		$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['idCanton']);
		$canton = pg_fetch_assoc($res);

		$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['idParroquia']);
		$parroquia = pg_fetch_assoc($res);

		if( pg_num_rows($operador) > 0 || pg_num_rows($usuario) > 0){
			$mensaje['estado'] = 'error';
			(pg_num_rows($operador) > 0? $mensaje['mensaje'] = 'El operador ya se encuentra registrado en Agrocalidad.': $mensaje['mensaje'] = 'El usuario ya se encuentra registrado en Agrocalidad.');
		}else{
			
			if(count($datos['idProducto']) != 0){
				//Crear nuevo operador
				$cr -> guardarNuevoOperador($conexion, $datos['clasificacion'], $datos['identificador'], $datos['razon'], $datos['nombreLegal'], $datos['apellidoLegal'], $provincia['nombre'],$canton['nombre'],$parroquia['nombre'], $datos['direccion'], $datos['telefono'], $datos['celular']);
					
				//Crear Cuenta de usuario
				$cu->crearUsuario($conexion, $datos['identificador'], md5($datos['identificador']));
					
				//Activacion de la cuenta del nuevo operador
				$cu->activarCuenta($conexion, $datos['identificador'], md5($datos['identificador']));
					
				//Asignar perfil a usuario externo
				$qPerfilExterno = $cu->buscarPerfilUsuario($conexion, $datos['identificador'], 'Usuario externo');
				if(pg_num_rows($qPerfilExterno) == 0){
					$cu->crearPerfilUsuario($conexion,  $datos['identificador'], 'Usuario externo');
				}
					
				//Asignar perfil a usuario operador
				$qPerfilOperador = $cu->buscarPerfilUsuario($conexion, $datos['identificador'], 'Operadores');
				if(pg_num_rows($qPerfilOperador)==0){
					$cu->crearPerfilUsuario($conexion,  $datos['identificador'], 'Operadores');
				}
					
				//Asignacion de la aplicacion de "registro de operador" al operador
				$qAplicacion = $ca->obtenerIdAplicacion($conexion, 'PRG_REGISTROOPER');
				$aplicacion = pg_fetch_result($qAplicacion, 0, 'id_aplicacion');
					
				$aplicacionOperadorRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacion,  $datos['identificador']);
					
				if (pg_num_rows($aplicacionOperadorRegistro) == 0){
					$ca->guardarAplicacionPerfil($conexion, $aplicacion,  $datos['identificador'], 0, 'notificaciones');
				}
					
				//agregar modulo catastro para todos los operadores
				$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_CATAS_PRODU','PRG_MOVIL_PRODU','PRG_LABORATORIOS')");
				while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
					$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_CATAG','PFL_EMISO_MOVIL')");
					$perfilesArray=Array();
					while($fila=pg_fetch_assoc($qGrupoPerfiles)){
						$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
					}

					if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['identificador']))==0){
						$qAplicacion=$cgap->guardarGestionAplicacion($conexion, $datos['identificador'],$filaAplicacion['codificacion_aplicacion']);
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['identificador']);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
						}
					}else{
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['identificador']);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
						}
					}
				}
					
				$areasOperacion = $cc->obtenerAreasXtipoOperacion($conexion, $datos['tipoOperacion']);
					
				//Generar código de sitio
				$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, $provincia['nombre'], $datos['identificador']);
				$secuencialSitio = str_pad(pg_fetch_result($qSecuencialSitio, 0, 'valor'), 2, "0", STR_PAD_LEFT);

				//TODO: Guardar Nuevo sitio de operacion
				$qIdSitio = $cr->guardarNuevoSitio($conexion, $datos['nombreSitio'], $provincia['nombre'],
						$canton['nombre'], $parroquia['nombre'], $datos['direccion'], '', 0, $datos['identificador'], $datos['telefono'],
						$datos['latitud'], $datos['longitud'], $secuencialSitio, '0',$datos['zona'],substr($provincia['codigo_vue'],1));
				
				$idSitio = pg_fetch_result($qIdSitio, 0, 'id_sitio');

				foreach ($areasOperacion as $areaOperacion){
					//Generar código de área
					$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $datos['identificador'], $areaOperacion['codigo'],$provincia['nombre']);
					$secuencial = str_pad(pg_fetch_result($qSecuencialArea, 0, 'valor'), 2, "0", STR_PAD_LEFT);

					//TODO: Poner el nombre del Área -> Area (nombre del Área)
					$areaOperacionNombreAutomatico= "Área ".substr($secuencial,1)." ".$areaOperacion['nombre'];

					//TODO: Guardar Area para el sitio
					$qIdArea = $cr -> guardarNuevaArea($conexion, $areaOperacionNombreAutomatico, $areaOperacion['nombre'], 0, $idSitio, $areaOperacion['codigo'], $secuencial);

					//Crear un array con los id de area creados.
					$arrayIdArea[]=pg_fetch_result($qIdArea, 0, 'id_area');
				}
				
				//Guardar los nuevos identificadores de operador_tipo_operacion e historial de la solicitud
				
				$qIdOperadorTipoOperacion = $cr->guardarTipoOperacionPorIndentificadorSitio($conexion, $datos['identificador'], $idSitio, $datos['tipoOperacion']);
				$idOperadorTipoOperacion = pg_fetch_assoc($qIdOperadorTipoOperacion);
				
				$qHistorialOperacion = $cr->guardarDatosHistoricoOperacion($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion']);
				$historicoOperacion = pg_fetch_assoc($qHistorialOperacion);				

				for($i = 0; $i < count($datos['idProducto']); $i++){
				    
				    
				    //NUEVO VIGENCIA DOCUMENTO
				    
				    $qCabeceraVigencia = $cvd->buscarTipoOperacionCabeceraVigencia($conexion, $datos['tipoOperacion']);
				    
				    $idVigenciaDocumento = 0;
				    
				    if(pg_num_rows($qCabeceraVigencia) > 0){
				        
				        $cabeceraVigencia = pg_fetch_assoc($qCabeceraVigencia);
				        if($cabeceraVigencia['nivel_lista']=='operacion'){
				            
				            //$idVigenciaDocumento = $operacion['id_vigencia_documento'];
				            $idVigenciaDocumento = $cabeceraVigencia['id_vigencia_documento'];
				            
				        }else{
				            $qDetalleVigencia = $cvd->buscarVigenciaProducto($conexion, $cabeceraVigencia['id_vigencia_documento'],$datos['idProducto'][$i]);
				            if(pg_num_rows($qDetalleVigencia) > 0){
				                $detalleVigencia = pg_fetch_assoc($qDetalleVigencia);
				                $idVigenciaDocumento = $detalleVigencia['id_vigencia_documento'];
				            }
				        }
				    }
				    
				    //NUEVO VIGENCIA DOCUMENTO				    
				    
					$qProducto = $cc->obtenerNombreProducto($conexion, $datos['idProducto'][$i]);

					//TODO: Guardar la operacion
					$qIdSolicitud= $cr->guardarNuevaOperacion($conexion, $datos['tipoOperacion'], $datos['identificador'],$datos['idProducto'][$i], pg_fetch_result($qProducto, 0, 'nombre_comun'), $idOperadorTipoOperacion['id_operador_tipo_operacion'], $historicoOperacion['id_historial_operacion']);
					$idSolicitud = pg_fetch_assoc($qIdSolicitud);
					
					$cr->actualizarVigenciaXOperacion($conexion, $idSolicitud['id_operacion'], $idVigenciaDocumento);
										
					if($i == 0){
						$cr->actualizarIdentificadorOperacionPorOperadorTipoOperacion($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion'], $idSolicitud['id_operacion']);
					}
					
					//TODO: Recorrer array de id areas.
					foreach($arrayIdArea as $posicion=>$idArea){
						//TODO: Guardar relaciÃ³n entre area y operacion
						$idAreas = $cr->guardarAreaOperacion($conexion,  $idArea, $idSolicitud['id_operacion']);
						if($i == 0){
						  $cr->guardarAreaPorIdentificadorTipoOperacion($conexion, $idArea, $idOperadorTipoOperacion['id_operador_tipo_operacion']);
						}
					}

					$valores = array();
					$resultado = array();

					//AGREGADO PARA EL FLUJO
					//TODO: VAMOS A CONSULTAR CON EL ID DE PRODUCTO Y EL ID TIPOOPERACION A LA TABLA DE PRODUCTO_MULTIPLE_VARIEDADES
					$variedad = $cr->buscarVariedadOperacionProducto($conexion, $datos['tipoOperacion'] , $datos['idProducto'][$i]);
					$valores[] = (pg_num_rows($variedad) == '0'?'flujoNormal':'variedad');
					$resultado = array_unique($valores);

					if(count($resultado) == 1){
						//echo $resultado[0];
						if($resultado[0]=='flujoNormal'){
							$estadoFlujo = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $datos['idFlujo'], '1'));
							
							if($estadoFlujo['estado'] == 'cargarProducto'){
								$estadoFlujo = pg_fetch_assoc($cr->obtenerEstadoFlujoOperacion($conexion, $datos['idFlujo'], '2'));
							}

							switch ($estadoFlujo['estado']){
									
								case 'cargarAdjunto':
									$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],$estadoFlujo['estado']);
									break;
								case 'pago':
									$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],$estadoFlujo['estado']);
									break;
								case 'inspeccion':
									$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],$estadoFlujo['estado']);
									break;
								case 'declararDVehiculo':
								    $res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],$estadoFlujo['estado']);
								    break;
								case'registrado':
									$fechaActual = date('Y-m-d H-i-s');
									$cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'registrado', 'No se realizó proceso de inspección, ni cobro de tasas. Proceso ejecutado por sistema GUIA '.$fechaActual.' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M');
									$cr -> cambiarEstadoAreaXidSolicitud($conexion, $idSolicitud['id_operacion'], 'registrado', 'No se realizó proceso de inspección, ni cobro de tasas. Proceso ejecutado por sistema GUIA '.$fechaActual.' en base a memorando MAGAP-DSV/AGROCALIDAD-2014-001427-M');
									break;								
							}

							$cargarInformacion = 'FALSE';

						}else{
							$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'cargarIA');
							$cargarInformacion = 'TRUE';
						}
					}else{
						$res = $cr -> enviarOperacion($conexion, $idSolicitud['id_operacion'],'cargarIA');
						$cargarInformacion = 'TRUE';
					}
					
					
					$areasOperacionB = implode(',', $arrayIdArea);
					$estadoOperacion = $cr->buscarEstadoOperacionArea($conexion, $datos['tipoOperacion'], $datos['identificador'], $areasOperacionB);
					$estado = pg_fetch_assoc($estadoOperacion);
					
					if ($estado['estado']=='registrado'){
							
						$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacionXOperacion($conexion, $idSolicitud['id_operacion']);
						$codigoTipoOperacion=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
						if($codigoTipoOperacion == 'ACOSV'){
							$qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $datos['identificador'],"('ACO','COM')","('SV')");
							if(pg_fetch_row($qOperaciones)>0){
								$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_EMISI_ETIQU')");
								while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
									$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_SOLIC_ETIQU')");
									$perfilesArray=Array();
									while($fila=pg_fetch_assoc($qGrupoPerfiles)){
										$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
									}
									if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['identificador']))==0){
										$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $datos['identificador'],$filaAplicacion['codificacion_aplicacion']);
										foreach( $perfilesArray as $datosPerfil){
											$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['identificador']);
											if (pg_num_rows($qPerfil) == 0)
												$cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
										}
									}else{
										foreach( $perfilesArray as $datosPerfil){
											$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['identificador']);
											if (pg_num_rows($qPerfil) == 0)
												$cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
										}
									}
								}
							}
						}else if($codigoTipoOperacion == 'COMSV' || $codigoTipoOperacion == 'CFESV' || $codigoTipoOperacion == 'AGESV'){
						    $qOperaciones=$cr->buscarOperacionesPorCodigoyAreaOperacion($conexion, $datos['identificador'],"('COM', 'CFE', 'AGE')","('SV')");
						    if(pg_fetch_row($qOperaciones)>0){
						        $qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_EMISI_ETIQU', 'PRG_CERT_FITO')");
						        while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
						            $qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_SOLIC_ETIQU', 'PFL_USR_CERT_FIT')");
						            $perfilesArray=Array();
						            while($fila=pg_fetch_assoc($qGrupoPerfiles)){
						                $perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
						            }
						            if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['identificador']))==0){
						                $qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $datos['identificador'],$filaAplicacion['codificacion_aplicacion']);
						                foreach( $perfilesArray as $datosPerfil){
						                    $qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['identificador']);
						                    if (pg_num_rows($qPerfil) == 0)
						                        $cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
						                }
						            }else{
						                foreach( $perfilesArray as $datosPerfil){
						                    $qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['identificador']);
						                    if (pg_num_rows($qPerfil) == 0)
						                        $cgap->guardarGestionPerfil($conexion, $datos['identificador'],$datosPerfil['codigoPerfil']);
						                }
									}
								}					
							}
						}
						
					}
					
				}
				
				$cr-> actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion'], $estadoFlujo['estado']);
					
				$areasOperacion = implode(", ", $arrayIdArea);
				$productosRegistrados = implode(", ", $datos['idProducto']);
				
				$cr->registrarLogRegistroOperadorMasivo($conexion, $_SESSION['usuario'], $datos['identificador'], $idSitio, $areasOperacion, $datos['tipoOperacion'], $productosRegistrados);
				
				$conexion->ejecutarConsulta("commit;");
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Seleccione al menos un producto.';
			}
		}
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}

} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}
?>