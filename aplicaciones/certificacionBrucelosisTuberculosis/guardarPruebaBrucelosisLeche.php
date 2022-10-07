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
		
		$pruebasBrucelosisLeche = htmlspecialchars ($_POST['pruebasBrucelosisLeche'],ENT_NOQUOTES,'UTF-8');
		$resultadoBrucelosisLeche = htmlspecialchars ($_POST['resultadoBrucelosisLeche'],ENT_NOQUOTES,'UTF-8');		
		$idPruebasLaboratorioLeche = htmlspecialchars ($_POST['pruebasLaboratorioLeche'],ENT_NOQUOTES,'UTF-8');
		$pruebasLaboratorioLeche = htmlspecialchars ($_POST['nombrePruebasLaboratorioLeche'],ENT_NOQUOTES,'UTF-8');
		$idLaboratorioLeche = htmlspecialchars ($_POST['laboratorioLeche'],ENT_NOQUOTES,'UTF-8');
		$laboratorioLeche = htmlspecialchars ($_POST['nombreLaboratorioLeche'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
                            if($pruebasBrucelosisLeche == 'No'){
                                    $resultadoBrucelosisLeche = 'No Aplica';
                                    $idPruebasLaboratorioLeche = 0;
                                    $pruebasLaboratorioLeche = 'No Aplica';
                                    $idLaboratorioLeche = 0;
                                    $laboratorio = 'No Aplica';
                            }

                            //consultar si existe un no aplica para el certificado y segun el numero de inspeccion
                            $pruebaBrucelosisNoAplica = $cbt->buscarPruebaBrucelosisLecheCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion);
                            if(pg_num_rows($pruebaBrucelosisNoAplica) > 0){
                                $mensaje['estado'] = 'error';
                                $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que NO APLIQUEN.";
                            } else {
                                if($_POST['pruebasBrucelosisLeche']=='No'){
                                    //consultar si existen registros que aplican para el certificado y segun el numero de inspeccion
                                    $pruebaBrucelosisAplica = $cbt->buscarPruebaBrucelosisLecheCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion);
                                    if(pg_num_rows($pruebaBrucelosisAplica) > 0){
                                        $mensaje['estado'] = 'error';
                                        $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que APLIQUEN.";
                                    } else {
                                        //Guardar datos de no aplica
                                        $conexion->ejecutarConsulta("begin;");

                                        $idPruebaBrucelosisLeche = pg_fetch_result($cbt->guardarPruebaBrucelosisLecheCertificacionBT($conexion, 
                                                                                                                                                $idCertificacionBT, $identificador, 
                                                                                                                                                $pruebasBrucelosisLeche, $resultadoBrucelosisLeche,
                                                                                                                                                $numInspeccion, $idPruebasLaboratorioLeche, $pruebasLaboratorioLeche,
                                                                                                                                                $idLaboratorioLeche, $laboratorioLeche), 
                                                                                                                                                0, 'id_certificacion_bt_prueba_brucelosis_leche');				

                                        $mensaje['estado'] = 'exito';
                                        $mensaje['mensaje'] = $cbt->imprimirLineaPruebaBrucelosisLecheCertificacionBT($idPruebaBrucelosisLeche, 
                                                                                                                                                $pruebasBrucelosisLeche, $resultadoBrucelosisLeche, $ruta,
                                                                                                                                                $numInspeccion, $pruebasLaboratorioLeche, $laboratorioLeche);

                                        $conexion->ejecutarConsulta("commit;");
                                    }
                                } else {
                                    $pruebaBrucelosis = $cbt->buscarPruebaBrucelosisLecheCertificacionBT($conexion, $idCertificacionBT, 
                                                                                                                                                    $pruebasBrucelosisLeche, $resultadoBrucelosisLeche,
                                                                                                                                                    $numInspeccion, $idPruebasLaboratorioLeche,
                                                                                                                                                    $idLaboratorioLeche);

                                    if(pg_num_rows($pruebaBrucelosis) == 0){
                                            $conexion->ejecutarConsulta("begin;");

                                            $idPruebaBrucelosisLeche = pg_fetch_result($cbt->guardarPruebaBrucelosisLecheCertificacionBT($conexion, 
                                                                                                                                                    $idCertificacionBT, $identificador, 
                                                                                                                                                    $pruebasBrucelosisLeche, $resultadoBrucelosisLeche,
                                                                                                                                                    $numInspeccion, $idPruebasLaboratorioLeche, $pruebasLaboratorioLeche,
                                                                                                                                                    $idLaboratorioLeche, $laboratorioLeche), 
                                                                                                                                                    0, 'id_certificacion_bt_prueba_brucelosis_leche');				

                                            $mensaje['estado'] = 'exito';
                                            $mensaje['mensaje'] = $cbt->imprimirLineaPruebaBrucelosisLecheCertificacionBT($idPruebaBrucelosisLeche, 
                                                                                                                                                    $pruebasBrucelosisLeche, $resultadoBrucelosisLeche, $ruta,
                                                                                                                                                    $numInspeccion, $pruebasLaboratorioLeche, $laboratorioLeche);

                                            $conexion->ejecutarConsulta("commit;");

                                    }else{
                                            $mensaje['estado'] = 'error';
                                            $mensaje['mensaje'] = "La información de pruebas de brucelosis en leche el predio ya ha sido ingresada.";
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