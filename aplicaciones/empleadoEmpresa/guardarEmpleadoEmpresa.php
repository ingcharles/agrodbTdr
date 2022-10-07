<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$cee = new ControladorEmpleadoEmpresa();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	$cr = new ControladorRegistroOperador();

	$datos = array (
			'empresa' => htmlspecialchars ( $_POST ['empresa'], ENT_NOQUOTES, 'UTF-8' ),
			'empleado' => htmlspecialchars ( $_POST ['empleado'], ENT_NOQUOTES, 'UTF-8' ),
			'estado' => 'activo'
	);

	try {

		$conexion->ejecutarConsulta("begin;");
		$qEmpresa=$cee->obtenerEmpresa($conexion,  $datos['empresa']);

		if(pg_num_rows($qEmpresa)==0){
		    $qEmpleadoEmpresa = $cee->verificarEmpleadoEmpresa($conexion, $datos['empresa']);
		    
		    if(pg_num_rows($qEmpleadoEmpresa) == 0){
				$empresa = $cee->guardarEmpresa($conexion, $datos['empresa']);
				$idEmpresa = pg_fetch_result($empresa, 0, 'id_empresa');
				$empleado = $cee->guardarEmpleadoEmpresa($conexion, $idEmpresa ,$datos['empresa'], $datos['estado']);
				$idEmpleado = pg_fetch_result($empleado, 0, 'id_empleado');
				$qTraspatioEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['empresa'],"('OPTSA')");
				if (pg_num_rows($qTraspatioEmpresa)>0){
				    $cee->guardarNuevoRolEmpleado($conexion, $idEmpleado, 'digitadorVacunacion', $datos['estado']);
					//ASIGNAR MODULO PARA LA EMPRESA
					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA','PRG_OPERADORMASI')");
					while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
						$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_VACUN','PFL_ADMIN_ROLEM','PFL_INCRI_MASIV')");
						$perfilesArray=Array();
						while($fila=pg_fetch_assoc($qGrupoPerfiles)){
							$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
						}
						if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empresa']))==0){
							$cgap->guardarGestionAplicacion($conexion, $datos['empresa'],$filaAplicacion['codificacion_aplicacion']);
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empresa']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
							}
						}else{
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empresa']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
							}
						}
					}
				}
					
				$qIndustrialEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['empresa'],"('OPISA')");
				if (pg_num_rows($qIndustrialEmpresa)>0){
				    $cee->guardarNuevoRolEmpleado($conexion, $idEmpleado, 'digitadorVacunacion', $datos['estado']);
				    $cee->guardarNuevoRolEmpleado($conexion, $idEmpleado, 'digitadorMovilizacion', $datos['estado']);
					//ASIGNAR MODULO PARA LA EMPRESA
					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA', 'PRG_MOVIL_PRODU')");
					while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
						$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_VACUN','PFL_ADMIN_ROLEM', 'PFL_EMISO_MOVIL', 'PFL_FISCA_MOVIL', 'PFL_ADM_ROL_EMO')");
						$perfilesArray=Array();
						while($fila=pg_fetch_assoc($qGrupoPerfiles)){
							$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
						}
						if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empresa']))==0){
							$cgap->guardarGestionAplicacion($conexion, $datos['empresa'],$filaAplicacion['codificacion_aplicacion']);
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empresa']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
							}
						}else{
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empresa']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
							}
						}
					}
				}
					
				$qMovilizacionEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['empresa'],"('OPMSA')");
				if (pg_num_rows($qMovilizacionEmpresa)>0){
					//ASIGNAR MODULO PARA LA EMPRESA
					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_OPERADORMASI')");
					while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
						$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_INCRI_MASIV')");
						$perfilesArray=Array();
						while($fila=pg_fetch_assoc($qGrupoPerfiles)){
							$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
						}
						if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empresa']))==0){
							$cgap->guardarGestionAplicacion($conexion, $datos['empresa'],$filaAplicacion['codificacion_aplicacion']);
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empresa']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
							}
						}else{
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empresa']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
							}
						}
					}

				}
				                
                $qMovilizacionEmpresa=$cee->obtenerOperadorEmpresaOperacion($conexion, $datos['empresa'],"('FERSA','FAEAI')");
                if (pg_num_rows($qMovilizacionEmpresa)>0){
                    
                    $perfiles = "";
                    
                    while($fila = pg_fetch_assoc($qMovilizacionEmpresa)){
                        if($fila['operacion'] == 'FAEAI'){
                            $cee->guardarNuevoRolEmpleado($conexion, $idEmpleado, 'digitadorFaenador', $datos['estado']);
                            $perfiles = "('PFL_EMISO_MOVIL', 'PFL_FISCA_MOVIL', 'PFL_ADM_ROL_EFI')";
                        }else{
                            $cee->guardarNuevoRolEmpleado($conexion, $idEmpleado, 'digitadorMovilizacion', $datos['estado']);
                            $perfiles = "('PFL_EMISO_MOVIL', 'PFL_ADM_ROL_EMO', 'PFL_FISCA_MOVIL')";
                        }
                    }
                    
                    //TODO: Verificar si las ferias tbn fiscalizan
                //ASIGNAR MODULO PARA LA EMPRESA
                    $qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
                    while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
                        $qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], $perfiles);
                        $perfilesArray=Array();
                        while($fila=pg_fetch_assoc($qGrupoPerfiles)){
                            $perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
                        }
                        if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empresa']))==0){
                            $cgap->guardarGestionAplicacion($conexion, $datos['empresa'],$filaAplicacion['codificacion_aplicacion']);
                            foreach( $perfilesArray as $datosPerfil){
                                $qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empresa']);
                                if (pg_num_rows($qPerfil) == 0)
                                    $cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
                            }
                        }else{
                            foreach( $perfilesArray as $datosPerfil){
                                $qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empresa']);
                                if (pg_num_rows($qPerfil) == 0)
                                    $cgap->guardarGestionPerfil($conexion, $datos['empresa'],$datosPerfil['codigoPerfil']);
                            }
                        }
                    }
                    
                }
					
                $qEmpleadoEmpresa = $cee->verificarEmpleadoEmpresa($conexion, $datos['empleado']);
                
                if(pg_num_rows($qEmpleadoEmpresa) == 0){
					$cee->guardarEmpleadoEmpresa($conexion, $idEmpresa ,$datos['empleado'], $datos['estado']);
					//ASIGNAR MODULO PARA EL EMPLEADO
					$qMovilizacionEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['empresa'],"('OPMSA')");
					if (pg_num_rows($qMovilizacionEmpresa)>0){
						$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_OPERADORMASI')");
						while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
							$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_INCRI_MASIV')");
							$perfilesArray=Array();
							while($fila=pg_fetch_assoc($qGrupoPerfiles)){
								$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
							}
							if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empleado']))==0){
								$cgap->guardarGestionAplicacion($conexion, $datos['empleado'],$filaAplicacion['codificacion_aplicacion']);
								foreach( $perfilesArray as $datosPerfil){
									$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empleado']);
									if (pg_num_rows($qPerfil) == 0)
										$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
								}
							}else{
								foreach( $perfilesArray as $datosPerfil){
									$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empleado']);
									if (pg_num_rows($qPerfil) == 0)
										$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
								}
							}
						}
					}
						
					$qFeriaFaenadorEmpresa = $cee->obtenerOperadorEmpresa($conexion, $datos['empresa'],"('FERSA','FAEAI')");
					if (pg_num_rows($qFeriaFaenadorEmpresa) > 0){
					    					    
						//ASIGNAR MODULO PARA EL EMPLEADO
					    $qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
					    while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
					        $qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_EMISO_MOVIL')");
					        $perfilesArray=Array();
							while($fila=pg_fetch_assoc($qGrupoPerfiles)){
								$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
							}
							if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empleado']))==0){
								$cgap->guardarGestionAplicacion($conexion, $datos['empleado'],$filaAplicacion['codificacion_aplicacion']);
								foreach( $perfilesArray as $datosPerfil){
									$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empleado']);
									if (pg_num_rows($qPerfil) == 0)
										$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
								}
							}else{
								foreach( $perfilesArray as $datosPerfil){
									$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empleado']);
									if (pg_num_rows($qPerfil) == 0)
										$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
								}
							}
						}
					}			
					
					$mensaje ['estado'] = 'exito';
					$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				}else{
				    //Verifico el estado del empleado
				    $empleadoEmpresa = pg_fetch_assoc($qEmpleadoEmpresa);
				    $estadoEmpleadoEmpresa = $empleadoEmpresa['estado'];
				    
				    if($estadoEmpleadoEmpresa == 'inactivo'){
				        $identificadorEmpleado = $datos['empleado'];
				        //Elimino los roles del empleado inactivo (roles de vacunación y movilización)
				        $qDatosEmpleado = $cee->obtenerDatosEmpleadoPorIdentificadorEmpleado($conexion, $identificadorEmpleado);
				        $datosEmpleado = pg_fetch_assoc($qDatosEmpleado);
				        $cee->eliminarRolesEmpleadoPorIdEmpleado($conexion, $datosEmpleado['id_empleado']);
				        //Actualizo la empresa del empleado y le pongo en estado activo
				        $cee->actualizarEmpresaPorIdentificadorEmpleado($conexion, $idEmpresa, 'activo', $identificadorEmpleado);
				        $mensaje ['estado'] = 'exito';
				        $mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				    }else{
				        $mensaje ['estado'] = 'error';
				        $mensaje ['mensaje'] = 'El empleado ya ha sido registrado';
				    }		
				}
			}else{
			    //Verifico el estado del empleado
			    $empleadoEmpresa = pg_fetch_assoc($qEmpleadoEmpresa);
			    $estadoEmpleadoEmpresa = $empleadoEmpresa['estado'];
			    
			    if($estadoEmpleadoEmpresa == 'inactivo'){
			        $identificadorEmpleado = $datos['empleado'];
			        //Elimino los roles del empleado inactivo (roles de vacunación y movilización)
			        $qDatosEmpleado = $cee->obtenerDatosEmpleadoPorIdentificadorEmpleado($conexion, $identificadorEmpleado);
			        $datosEmpleado = pg_fetch_assoc($qDatosEmpleado);
			        $cee->eliminarRolesEmpleadoPorIdEmpleado($conexion, $datosEmpleado['id_empleado']);
			        //Actualizo la empresa del empleado y le pongo en estado activo
			        $cee->actualizarEmpresaPorIdentificadorEmpleado($conexion, $idEmpresa, 'activo', $identificadorEmpleado);
			        $mensaje ['estado'] = 'exito';
			        $mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
			    }else{
			        $mensaje ['estado'] = 'error';
			        $mensaje ['mensaje'] = 'El empleado ya ha sido registrado';
			    }
			}
		}else{
			$idEmpresa=pg_fetch_result($qEmpresa, 0, 'id_empresa');
			$qEmpleadoEmpresa = $cee->verificarEmpleadoEmpresa($conexion, $datos['empleado']);
			
			if(pg_num_rows($qEmpleadoEmpresa) == 0){
				$cee->guardarEmpleadoEmpresa($conexion, $idEmpresa ,$datos['empleado'], $datos['estado']);
				$qMovilizacionEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['empresa'],"('OPMSA')");
				if (pg_num_rows($qMovilizacionEmpresa)>0){
					//ASIGNAR MODULO PARA EL EMPLEADO
					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_OPERADORMASI')");
					while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
						$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_INCRI_MASIV')");
						$perfilesArray=Array();
						while($fila=pg_fetch_assoc($qGrupoPerfiles)){
							$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
						}
						if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empleado']))==0){
							$cgap->guardarGestionAplicacion($conexion, $datos['empleado'],$filaAplicacion['codificacion_aplicacion']);
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empleado']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
							}
						}else{
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empleado']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
							}
						}
					}
				}

				$qFeriaFaenadorEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['empresa'],"('FERSA','FAEAI')");
				if (pg_num_rows($qFeriaFaenadorEmpresa)>0){
					//ASIGNAR MODULO PARA EL EMPLEADO
					$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
					while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
						$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_FISCA_MOVIL')");
						$perfilesArray=Array();
						while($fila=pg_fetch_assoc($qGrupoPerfiles)){
							$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
						}
						if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['empleado']))==0){
							$cgap->guardarGestionAplicacion($conexion, $datos['empleado'],$filaAplicacion['codificacion_aplicacion']);
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['empleado']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
							}
						}else{
							foreach( $perfilesArray as $datosPerfil){
								$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['empleado']);
								if (pg_num_rows($qPerfil) == 0)
									$cgap->guardarGestionPerfil($conexion, $datos['empleado'],$datosPerfil['codigoPerfil']);
							}
						}
					}
				}

				$mensaje ['estado'] = 'exito';
				$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
			}else{
			    //Verifico el estado del empleado
			    $empleadoEmpresa = pg_fetch_assoc($qEmpleadoEmpresa);
			    $estadoEmpleadoEmpresa = $empleadoEmpresa['estado'];
			    
			    if($estadoEmpleadoEmpresa == 'inactivo'){
			        $identificadorEmpleado = $datos['empleado'];
			        //Elimino los roles del empleado inactivo (roles de vacunación y movilización)
			        $qDatosEmpleado = $cee->obtenerDatosEmpleadoPorIdentificadorEmpleado($conexion, $identificadorEmpleado);
			        $datosEmpleado = pg_fetch_assoc($qDatosEmpleado);
			        $cee->eliminarRolesEmpleadoPorIdEmpleado($conexion, $datosEmpleado['id_empleado']);
			        //Actualizo la empresa del empleado y le pongo en estado activo
			        $cee->actualizarEmpresaPorIdentificadorEmpleado($conexion, $idEmpresa, 'activo', $identificadorEmpleado);
			        $mensaje ['estado'] = 'exito';
			        $mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
			    }else{
			        $mensaje ['estado'] = 'error';
			        $mensaje ['mensaje'] = 'El empleado ya ha sido registrado';
			    }		
			}
		}
		$conexion->ejecutarConsulta("commit;");
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