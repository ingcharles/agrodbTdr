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
		
		$pruebasBrucelosisSangre = htmlspecialchars ($_POST['pruebasBrucelosisSangre'],ENT_NOQUOTES,'UTF-8');
		$resultadoBrucelosisSangre = htmlspecialchars ($_POST['resultadoBrucelosisSangre'],ENT_NOQUOTES,'UTF-8');
		$idPruebasLaboratorio = htmlspecialchars ($_POST['pruebasLaboratorio'],ENT_NOQUOTES,'UTF-8');
		$pruebasLaboratorio = htmlspecialchars ($_POST['nombrePruebasLaboratorio'],ENT_NOQUOTES,'UTF-8');
		$idLaboratorio = htmlspecialchars ($_POST['laboratorio'],ENT_NOQUOTES,'UTF-8');
		$laboratorio = htmlspecialchars ($_POST['nombreLaboratorio'],ENT_NOQUOTES,'UTF-8');
		$idDestinoAnimalesPositivos = htmlspecialchars ($_POST['destinoAnimalesPositivos'],ENT_NOQUOTES,'UTF-8');
		$destinoAnimalesPositivos = htmlspecialchars ($_POST['nombreDestinoAnimalesPositivos'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
                            if($pruebasBrucelosisSangre == 'No'){
                                    $resultadoBrucelosisSangre = 'No Aplica';
                                    $idPruebasLaboratorio = 0;
                                    $pruebasLaboratorio = 'No Aplica';
                                    $idLaboratorio = 0;
                                    $laboratorio = 'No Aplica';
                                    $idDestinoAnimalesPositivos = 0;
                                    $destinoAnimalesPositivos = 'No Aplica';
                            }else if(($pruebasBrucelosisSangre == 'Si') && ($resultadoBrucelosisSangre == 'Negativo')){
                                    $idDestinoAnimalesPositivos = 0;
                                    $destinoAnimalesPositivos = 'No Aplica';
                            }

                            //consultar si existe un no aplica para el certificado y segun el numero de inspeccion
                            $pruebaBrucelosisNoAplica = $cbt->buscarPruebaBrucelosisSangreCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion);
                            if(pg_num_rows($pruebaBrucelosisNoAplica) > 0){
                                $mensaje['estado'] = 'error';
                                $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que NO APLIQUEN.";
                            } else {
                                if($_POST['pruebasBrucelosisSangre']=='No'){
                                    //consultar si existen registros que aplican para el certificado y segun el numero de inspeccion
                                    $pruebaBrucelosisAplica = $cbt->buscarPruebaBrucelosisSangreCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion);
                                    if(pg_num_rows($pruebaBrucelosisAplica) > 0){
                                        $mensaje['estado'] = 'error';
                                        $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que APLIQUEN.";
                                    } else {
                                        //Guardar datos de no aplica
                                        $conexion->ejecutarConsulta("begin;");

                                        $idPruebaBrucelosisSangre = pg_fetch_result($cbt->guardarPruebaBrucelosisSangreCertificacionBT($conexion,
                                                                                                                                                                $idCertificacionBT, $identificador, $pruebasBrucelosisSangre,
                                                                                                                                                                $resultadoBrucelosisSangre, $idPruebasLaboratorio,
                                                                                                                                                                $pruebasLaboratorio, $idLaboratorio, $laboratorio,
                                                                                                                                                                $idDestinoAnimalesPositivos, $destinoAnimalesPositivos,
                                                                                                                                                                $numInspeccion),
                                                                                                                                                                0, 'id_certificacion_bt_prueba_brucelosis_sangre');


                                        $mensaje['estado'] = 'exito';
                                        $mensaje['mensaje'] = $cbt->imprimirLineaPruebaBrucelosisSangreCertificacionBT($idPruebaBrucelosisSangre,
                                                                                                                                                                $pruebasBrucelosisSangre, $resultadoBrucelosisSangre,
                                                                                                                                                                $pruebasLaboratorio, $laboratorio,
                                                                                                                                                                $destinoAnimalesPositivos, $ruta, $numInspeccion);

                                        $conexion->ejecutarConsulta("commit;");
                                    }
                                } else {
				
                                    $pruebaBrucelosis = $cbt->buscarPruebaBrucelosisSangreCertificacionBT($conexion, $idCertificacionBT,
                                                                                                                                    $pruebasBrucelosisSangre, $resultadoBrucelosisSangre,
                                                                                                                                    $idPruebasLaboratorio, $idLaboratorio,
                                                                                                                                    $idDestinoAnimalesPositivos, $numInspeccion);

                                    if(pg_num_rows($pruebaBrucelosis) == 0){
                                            $conexion->ejecutarConsulta("begin;");

                                            $idPruebaBrucelosisSangre = pg_fetch_result($cbt->guardarPruebaBrucelosisSangreCertificacionBT($conexion,
                                                                                                                                                                    $idCertificacionBT, $identificador, $pruebasBrucelosisSangre,
                                                                                                                                                                    $resultadoBrucelosisSangre, $idPruebasLaboratorio,
                                                                                                                                                                    $pruebasLaboratorio, $idLaboratorio, $laboratorio,
                                                                                                                                                                    $idDestinoAnimalesPositivos, $destinoAnimalesPositivos,
                                                                                                                                                                    $numInspeccion),
                                                                                                                                                                    0, 'id_certificacion_bt_prueba_brucelosis_sangre');


                                            $mensaje['estado'] = 'exito';
                                            $mensaje['mensaje'] = $cbt->imprimirLineaPruebaBrucelosisSangreCertificacionBT($idPruebaBrucelosisSangre,
                                                                                                                                                                    $pruebasBrucelosisSangre, $resultadoBrucelosisSangre,
                                                                                                                                                                    $pruebasLaboratorio, $laboratorio,
                                                                                                                                                                    $destinoAnimalesPositivos, $ruta, $numInspeccion);

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