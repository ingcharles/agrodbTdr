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
		
		$pruebasTuberculina = htmlspecialchars ($_POST['pruebasTuberculina'],ENT_NOQUOTES,'UTF-8');
		$resultadoTuberculina = htmlspecialchars ($_POST['resultadoTuberculina'],ENT_NOQUOTES,'UTF-8');
		$idPruebasLaboratorio = htmlspecialchars ($_POST['pruebasLaboratorio'],ENT_NOQUOTES,'UTF-8');
		$pruebasLaboratorio = htmlspecialchars ($_POST['nombrePruebasLaboratorio'],ENT_NOQUOTES,'UTF-8');		
		$idLaboratorioTuberculina = htmlspecialchars ($_POST['laboratorioTuberculina'],ENT_NOQUOTES,'UTF-8');
		$laboratorioTuberculina = htmlspecialchars ($_POST['nombreLaboratorioTuberculina'],ENT_NOQUOTES,'UTF-8');
		$idDestinoAnimalesPositivosTuberculina = htmlspecialchars ($_POST['destinoAnimalesPositivosTuberculina'],ENT_NOQUOTES,'UTF-8');
		$destinoAnimalesPositivosTuberculina = htmlspecialchars ($_POST['nombreDestinoAnimalesPositivosTuberculina'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				if($pruebasTuberculina == 'No'){
					$resultadoTuberculina = 'No Aplica';
					$idPruebasLaboratorio = 0;
					$pruebasLaboratorio = 'No Aplica';
					$idLaboratorioTuberculina = 0;
					$laboratorioTuberculina = 'No Aplica';
					$idDestinoAnimalesPositivosTuberculina = 0;
					$destinoAnimalesPositivosTuberculina = 'No Aplica';
				}else if(($pruebasTuberculina == 'Si') && ($resultadoTuberculina == 'Negativo')){
					$idDestinoAnimalesPositivosTuberculina = 0;
					$destinoAnimalesPositivosTuberculina = 'No Aplica';
				}
				
                                //consultar si existe un no aplica para el certificado y segun el numero de inspeccion
                            $pruebaBrucelosisNoAplica = $cbt->buscarPruebaTuberculinaCertificacionBTNoAplica($conexion, $idCertificacionBT, $numInspeccion);
                            if(pg_num_rows($pruebaBrucelosisNoAplica) > 0){
                                $mensaje['estado'] = 'error';
                                $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que NO APLIQUEN.";
                            } else {
                                if($_POST['pruebasTuberculina']=='No'){
                                    //consultar si existen registros que aplican para el certificado y segun el numero de inspeccion
                                    $pruebaBrucelosisAplica = $cbt->buscarPruebaTuberculinaCertificacionBTAplica($conexion, $idCertificacionBT, $numInspeccion);
                                    if(pg_num_rows($pruebaBrucelosisAplica) > 0){
                                        $mensaje['estado'] = 'error';
                                        $mensaje['mensaje'] = "No se puede agregar el registro. Eliminar registros que APLIQUEN.";
                                    } else {
                                        //Guardar datos de no aplica
                                        $conexion->ejecutarConsulta("begin;");

                                        $idPruebaTuberculina = pg_fetch_result($cbt->guardarPruebaTuberculinaCertificacionBT($conexion, $idCertificacionBT, 
                                                                                                                                                                $identificador, $pruebasTuberculina, 
                                                                                                                                                                $resultadoTuberculina, $idLaboratorioTuberculina, 
                                                                                                                                                                $laboratorioTuberculina, $idDestinoAnimalesPositivosTuberculina, 
                                                                                                                                                                $destinoAnimalesPositivosTuberculina, $numInspeccion,
                                                                                                                                                                $idPruebasLaboratorio, $pruebasLaboratorio),
                                                                                                                                                                0, 'id_certificacion_bt_prueba_tuberculina');


                                        $mensaje['estado'] = 'exito';
                                        $mensaje['mensaje'] = $cbt->imprimirLineaPruebaTuberculinaCertificacionBT($idPruebaTuberculina, $pruebasTuberculina, 
                                                                                                                                                                $resultadoTuberculina, $laboratorioTuberculina, 
                                                                                                                                                                $destinoAnimalesPositivosTuberculina, $ruta, $numInspeccion,
                                                                                                                                                                $pruebasLaboratorio);

                                        $conexion->ejecutarConsulta("commit;");
                                    }
                                } else {
				
                                    $pruebaTuberculina = $cbt->buscarPruebaTuberculinaCertificacionBT($conexion, $idCertificacionBT, $pruebasTuberculina, 
                                                                                                                                                                            $resultadoTuberculina, $idLaboratorioTuberculina, 
                                                                                                                                                                            $idDestinoAnimalesPositivosTuberculina, $numInspeccion,
                                                                                                                                                                            $idPruebasLaboratorio);

                                    if(pg_num_rows($pruebaTuberculina) == 0){
                                            $conexion->ejecutarConsulta("begin;");

                                            $idPruebaTuberculina = pg_fetch_result($cbt->guardarPruebaTuberculinaCertificacionBT($conexion, $idCertificacionBT, 
                                                                                                                                                                    $identificador, $pruebasTuberculina, 
                                                                                                                                                                    $resultadoTuberculina, $idLaboratorioTuberculina, 
                                                                                                                                                                    $laboratorioTuberculina, $idDestinoAnimalesPositivosTuberculina, 
                                                                                                                                                                    $destinoAnimalesPositivosTuberculina, $numInspeccion,
                                                                                                                                                                    $idPruebasLaboratorio, $pruebasLaboratorio),
                                                                                                                                                                    0, 'id_certificacion_bt_prueba_tuberculina');


                                            $mensaje['estado'] = 'exito';
                                            $mensaje['mensaje'] = $cbt->imprimirLineaPruebaTuberculinaCertificacionBT($idPruebaTuberculina, $pruebasTuberculina, 
                                                                                                                                                                    $resultadoTuberculina, $laboratorioTuberculina, 
                                                                                                                                                                    $destinoAnimalesPositivosTuberculina, $ruta, $numInspeccion,
                                                                                                                                                                    $pruebasLaboratorio);

                                            $conexion->ejecutarConsulta("commit;");

                                    }else{
                                            $mensaje['estado'] = 'error';
                                            $mensaje['mensaje'] = "La información de prueba de tuberculina el predio ya ha sido ingresada.";
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