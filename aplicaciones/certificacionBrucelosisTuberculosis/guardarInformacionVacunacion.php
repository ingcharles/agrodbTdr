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
		
		$idMotivoVacunacion = htmlspecialchars ($_POST['motivoVacunacion'],ENT_NOQUOTES,'UTF-8');
		$motivoVacunacion = htmlspecialchars ($_POST['nombreMotivoVacunacion'],ENT_NOQUOTES,'UTF-8');
		$idVacunasAplicadas = htmlspecialchars ($_POST['vacunasAplicadas'],ENT_NOQUOTES,'UTF-8');
		$vacunasAplicadas = htmlspecialchars ($_POST['nombreVacunasAplicadas'],ENT_NOQUOTES,'UTF-8');
		$idProcedenciaVacunas = htmlspecialchars ($_POST['procedenciaVacunas'],ENT_NOQUOTES,'UTF-8');
		$procedenciaVacunas = htmlspecialchars ($_POST['nombreProcedenciaVacunas'],ENT_NOQUOTES,'UTF-8');
		$fechaVacunacion = htmlspecialchars ($_POST['fechaVacunacion'],ENT_NOQUOTES,'UTF-8');
		$calendarioVacunacion = htmlspecialchars ($_POST['calendarioVacunacion'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
                            if($calendarioVacunacion == 'No'){
                                    $idMotivoVacunacion = 0;
                                    $motivoVacunacion = 'No Aplica';
                                    $idVacunasAplicadas = 0;
                                    $vacunasAplicadas = 'No Aplica';
                                    $idProcedenciaVacunas = 0;
                                    $procedenciaVacunas = 'No Aplica';
                                    $fechaVacunacion = 'now()';
                            } 
                                
                            //consultar si existe un no aplica para el certificado y segun el numero de inspeccion
                            $infoVacunacionNoAplica = $cbt->buscarInformacionVacunacionCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion);
                            if(pg_num_rows($infoVacunacionNoAplica) > 0){
                                $mensaje['estado'] = 'error';
                                $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que NO APLIQUEN.";
                            } else {
                                if($calendarioVacunacion == 'No'){
                                    //consultar si existen registros que aplican para el certificado y segun el numero de inspeccion
                                    $infoVacunacionAplica = $cbt->buscarInformacionVacunacionCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion);
                                    if(pg_num_rows($infoVacunacionAplica) > 0){
                                        $mensaje['estado'] = 'error';
                                        $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que APLIQUEN.";
                                    } else {
                                        //Guardar datos de no aplica
                                        $conexion->ejecutarConsulta("begin;");

                                        $idInformacionVacunacion = pg_fetch_result($cbt->guardarInformacionVacunacionCertificacionBT($conexion, 
                                                                                                                                        $idCertificacionBT, $identificador, $idMotivoVacunacion,
                                                                                                                                        $motivoVacunacion, $idVacunasAplicadas, 
                                                                                                                                        $vacunasAplicadas, $idProcedenciaVacunas, 
                                                                                                                                        $procedenciaVacunas, $fechaVacunacion,
                                                                                                                                        $numInspeccion, $calendarioVacunacion), 
                                                                                                                                        0, 'id_certificacion_bt_informacion_vacunacion');				

                                        if($calendarioVacunacion == 'No'){
                                                $fecha = getdate();
                                                $fechaVacunacion = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
                                        }

                                        $mensaje['estado'] = 'exito';
                                        $mensaje['mensaje'] = $cbt->imprimirLineaInformacionVacunacionCertificacionBT($idInformacionVacunacion,
                                                                                                                                        $motivoVacunacion, $vacunasAplicadas, 
                                                                                                                                        $procedenciaVacunas, $fechaVacunacion, $ruta,
                                                                                                                                        $numInspeccion, $calendarioVacunacion);

                                        $conexion->ejecutarConsulta("commit;");
                                    }
                                } else {

                                    $infoVacunacion = $cbt->buscarInformacionVacunacionCertificacionBT($conexion, $idCertificacionBT, 
                                                                                            $idMotivoVacunacion, $idVacunasAplicadas, $idProcedenciaVacunas,
                                                                                            $numInspeccion);

                                    if(pg_num_rows($infoVacunacion) == 0){
                                            $conexion->ejecutarConsulta("begin;");

                                            $idInformacionVacunacion = pg_fetch_result($cbt->guardarInformacionVacunacionCertificacionBT($conexion, 
                                                                                                                                            $idCertificacionBT, $identificador, $idMotivoVacunacion,
                                                                                                                                            $motivoVacunacion, $idVacunasAplicadas, 
                                                                                                                                            $vacunasAplicadas, $idProcedenciaVacunas, 
                                                                                                                                            $procedenciaVacunas, $fechaVacunacion,
                                                                                                                                            $numInspeccion, $calendarioVacunacion), 
                                                                                                                                            0, 'id_certificacion_bt_informacion_vacunacion');				

                                            if($calendarioVacunacion == 'No'){
                                                    $fecha = getdate();
                                                    $fechaVacunacion = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
                                            }

                                            $mensaje['estado'] = 'exito';
                                            $mensaje['mensaje'] = $cbt->imprimirLineaInformacionVacunacionCertificacionBT($idInformacionVacunacion,
                                                                                                                                            $motivoVacunacion, $vacunasAplicadas, 
                                                                                                                                            $procedenciaVacunas, $fechaVacunacion, $ruta,
                                                                                                                                            $numInspeccion, $calendarioVacunacion);

                                            $conexion->ejecutarConsulta("commit;");

                                    }else{
                                            $mensaje['estado'] = 'error';
                                            $mensaje['mensaje'] = "La información del manejo animal en el predio ya ha sido ingresada.";
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