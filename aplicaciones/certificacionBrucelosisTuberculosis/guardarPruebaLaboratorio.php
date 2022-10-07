<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	
	try{
	
		$identificador = $_SESSION['usuario'];
	
		$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
		$numeroSolicitud = htmlspecialchars ($_POST['numeroSolicitud'],ENT_NOQUOTES,'UTF-8');
		
		$resultadoAnalisisLaboratorio = htmlspecialchars ($_POST['resultadoAnalisisLaboratorio'],ENT_NOQUOTES,'UTF-8');
		$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
		$informe = htmlspecialchars ($_POST['archivoInforme'],ENT_NOQUOTES,'UTF-8');
		
		$muestra = $_POST['arrayMuestra'];
		$fechaMuestra = $_POST['arrayFechaMuestra'];
		$enfermedad = $_POST['arrayEnfermedad'];
		$cantidadMuestras = $_POST['arrayCantidadMuestras'];
		$numPositivos = $_POST['arrayNumPositivos'];
		$numNegativos = $_POST['arrayNumNegativos'];
		$numIndeterminados = $_POST['arrayNumIndeterminados'];
		$numReactivos = $_POST['arrayNumReactivos'];
		$numSospechosos = $_POST['arrayNumSospechosos'];
		$idPruebaLaboratorio = $_POST['arrayIdPruebaLaboratorio'];
		$pruebaLaboratorio = $_POST['arrayPruebaLaboratorio'];
		$resultadoLaboratorio = $_POST['arrayResultadoLaboratorio'];
		$observacionesMuestra = $_POST['arrayObservacionesMuestra'];
		$numInspeccion = $_POST['numInspeccion'];
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
	
				$numero = pg_fetch_result($cbt->generarNumeroMuestraCertificacionBT($conexion, $numeroSolicitud), 0, 'num_muestra');
				$incremento = ($numero)+1;
				$numeroMuestra = str_pad($incremento, 4, "0", STR_PAD_LEFT);
					
				$idResultadoLaboratorio = pg_fetch_result($cbt -> nuevoResultadoLaboratorio($conexion, $idCertificacionBT, $identificador, $numeroSolicitud, $numeroMuestra, 
													$resultadoAnalisisLaboratorio, $informe, $observaciones, $numInspeccion), 0, 'id_certificacion_bt_resultado_laboratorio');
				
				for ($i = 0; $i < count ($muestra); $i++) {
					$cbt -> nuevoDetalleResultadoLaboratorio($conexion, $idResultadoLaboratorio, $idCertificacionBT, 
																$identificador, $muestra[$i], $fechaMuestra[$i], $enfermedad[$i], 
																$cantidadMuestras[$i], $numPositivos[$i], $numNegativos[$i], 
																$numIndeterminados[$i], $numReactivos[$i], $numSospechosos[$i],																
																$idPruebaLaboratorio[$i], $pruebaLaboratorio[$i], 
																$resultadoLaboratorio[$i], $observacionesMuestra[$i], $numInspeccion);
				}
				
				if($resultadoAnalisisLaboratorio == "negativo"){
					//Incrementa el número de inspección
					$numInspeccionC = pg_fetch_result($cbt->generarNumeroInspeccionCertificacionBT($conexion, 'Inspección ', $numeroSolicitud), 0, 'num_inspeccion');
					$tmpInspeccion= explode(" ", $numInspeccionC);
					$incrementoInspeccion = end($tmpInspeccion)+1;
					$numInspeccion = 'Inspección '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);
					
					//Regresa al tecnico para el cierre
					$cbt->cambiarEstadoCertificadoBT($conexion, $idCertificacionBT, $identificador, 'inspeccion', $numInspeccion);

				}else{
					$cbt->cambiarEstadoCertificadoBT($conexion, $idCertificacionBT, $identificador, 'plantaCentral', $numInspeccion);
				}
			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				
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