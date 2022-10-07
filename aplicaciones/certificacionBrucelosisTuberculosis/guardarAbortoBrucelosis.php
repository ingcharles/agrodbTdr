<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';	
	
	$ruta='certificacionBrucelosisTuberculosis';
	
	try{
	
		$identificador = $_SESSION['usuario'];
	
		$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
		$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
		
		$abortos = htmlspecialchars ($_POST['abortos'],ENT_NOQUOTES,'UTF-8');
		$numeroAbortos = htmlspecialchars ($_POST['numeroAbortos'],ENT_NOQUOTES,'UTF-8');
		$idTejidosAbortados = ($_POST['tejidosAbortados'] > 0) ? $_POST['tejidosAbortados'] : 0;		
		$tejidosAbortados = htmlspecialchars ($_POST['nombreTejidosAbortados'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
                            
                            //consultar si existe un no aplica para el certificado y segun el numero de inspeccion
                            $abortoBrucelosisNoAplica = $cbt->buscarAbortoBrucelosisCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion);
                            if(pg_num_rows($abortoBrucelosisNoAplica) > 0){
                                $mensaje['estado'] = 'error';
                                $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que NO APLIQUEN.";
                            } else { 
                                if($_POST['abortos']=='No'){
                                    //consultar si existen registros que aplican para el certificado y segun el numero de inspeccion
                                    $abortoBrucelosisAplica = $cbt->buscarAbortoBrucelosisCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion);
                                    if(pg_num_rows($abortoBrucelosisAplica) > 0){
                                        $mensaje['estado'] = 'error';
                                        $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que APLIQUEN.";
                                    } else {
                                        //Guardar datos de no aplica
                                        $conexion->ejecutarConsulta("begin;");

                                        $idAbortosBrucelosis = pg_fetch_result($cbt->guardarAbortosBrucelosisCertificacionBT($conexion, 
                                                                                                                                                $idCertificacionBT, $identificador, $abortos, 
                                                                                                                                                $numeroAbortos, $idTejidosAbortados, $tejidosAbortados,
                                                                                                                                                $numInspeccion), 
                                                                                                                                                0, 'id_certificacion_bt_abortos_brucelosis');				

                                        $mensaje['estado'] = 'exito';
                                        $mensaje['mensaje'] = $cbt->imprimirLineaAbortosBrucelosisCertificacionBT($idAbortosBrucelosis, 
                                                                                                                                                $abortos, $numeroAbortos, $tejidosAbortados, $ruta,
                                                                                                                                                $numInspeccion);

                                        $conexion->ejecutarConsulta("commit;");
                                    }
                                } else {
				
                                    $abortoBrucelosis = $cbt->buscarAbortoBrucelosisCertificacionBT($conexion, $idCertificacionBT, 
                                                                                                                                                    $abortos, $idTejidosAbortados, $numInspeccion);

                                    if(pg_num_rows($abortoBrucelosis) == 0){
                                            $conexion->ejecutarConsulta("begin;");

                                            $idAbortosBrucelosis = pg_fetch_result($cbt->guardarAbortosBrucelosisCertificacionBT($conexion, 
                                                                                                                                                    $idCertificacionBT, $identificador, $abortos, 
                                                                                                                                                    $numeroAbortos, $idTejidosAbortados, $tejidosAbortados,
                                                                                                                                                    $numInspeccion), 
                                                                                                                                                    0, 'id_certificacion_bt_abortos_brucelosis');				

                                            $mensaje['estado'] = 'exito';
                                            $mensaje['mensaje'] = $cbt->imprimirLineaAbortosBrucelosisCertificacionBT($idAbortosBrucelosis, 
                                                                                                                                                    $abortos, $numeroAbortos, $tejidosAbortados, $ruta,
                                                                                                                                                    $numInspeccion);

                                            $conexion->ejecutarConsulta("commit;");

                                    }else{
                                            $mensaje['estado'] = 'error';
                                            $mensaje['mensaje'] = "La información de abortos por brucelosis el predio ya ha sido ingresada.";
                                    }
                                }
                            }
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
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