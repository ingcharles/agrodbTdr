<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';
require_once '../../clases/ControladorMail.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$usuario=htmlspecialchars ($_POST['usuario'], ENT_NOQUOTES, 'UTF-8' );
	$idDestinacionAduanera = htmlspecialchars ($_POST['idDestinacionAduanera'], ENT_NOQUOTES, 'UTF-8' );
	$fechaElaboracion = htmlspecialchars ($_POST['fechaElaboracion'], ENT_NOQUOTES, 'UTF-8' );
	$coordenadaX = htmlspecialchars ($_POST['coordenadaX'], ENT_NOQUOTES, 'UTF-8' );
	$coordenadaY = htmlspecialchars ($_POST['coordenadaY'], ENT_NOQUOTES, 'UTF-8' );
	$coordenadaZ = htmlspecialchars ($_POST['coordenadaZ'], ENT_NOQUOTES, 'UTF-8' );
	$lote = htmlspecialchars ($_POST['lote'], ENT_NOQUOTES, 'UTF-8' );
	$csmt = htmlspecialchars ($_POST['csmt'], ENT_NOQUOTES, 'UTF-8' );
	$aic = htmlspecialchars ($_POST['aic'], ENT_NOQUOTES, 'UTF-8' );
	$fechaIngresoEcuador = htmlspecialchars ($_POST['fechaIngresoEcuador'], ENT_NOQUOTES, 'UTF-8' );
	$rutaInicioCuarentena = htmlspecialchars ($_POST['archivoInicioCuarentena'], ENT_NOQUOTES, 'UTF-8' );

	$resultadoInspeccion = htmlspecialchars ($_POST['resultadoInspeccion'], ENT_NOQUOTES, 'UTF-8' );
	$cantidadSanos = htmlspecialchars ($_POST['sanos'], ENT_NOQUOTES, 'UTF-8' );
	$cantidadEnfermos = htmlspecialchars ($_POST['enfermos'], ENT_NOQUOTES, 'UTF-8' );
	$cantidadMuertos = htmlspecialchars ($_POST['muertos'], ENT_NOQUOTES, 'UTF-8' );
	$cantidadTotal = htmlspecialchars ($_POST['total'], ENT_NOQUOTES, 'UTF-8' );
	$rutaSacrificioSanitario = htmlspecialchars ($_POST['archivoSacrificioSanitario'], ENT_NOQUOTES, 'UTF-8' );
	
	$contador=$_POST['contador'];
	$fechaRegistro=date('d/m/Y');
	$identificacionProducto = $_POST['dIdentificacion'];
	$cantidadProducto = $_POST['dCantidad'];
	$sexoProducto = $_POST['dSexo'];
	$edadProducto = $_POST['dEdad'];
	$duracionProducto = $_POST['dDuracion'];
	$sintamatologiaProducto = $_POST['dSintomatologia'];
	$observacionProducto = $_POST['dObservacion'];
	$idDetalleSeguimientoCuarentenarioSa=$_POST['idDetalleSeguimientoCuarentenarioSa'];

	for ($i=0; $i<count($identificacionProducto); $i++){
		$array[] = array('identificacionProducto' => $identificacionProducto[$i], 
						'cantidadProducto' => $cantidadProducto[$i],
						'sexoProducto' => $sexoProducto[$i],
						'edadProducto' => $edadProducto[$i],
						'duracionProducto' => $duracionProducto[$i],
						'sintamatologiaProducto' => $sintamatologiaProducto[$i],
						'observacionProducto' => $observacionProducto[$i]);
	}
	
	$DatosJson = json_encode($array,JSON_UNESCAPED_UNICODE);
	
	try {
		$conexion = new Conexion();
		$csc = new ControladorSeguimientoCuarentenario();
		$cMail = new ControladorMail();

		$conexion->ejecutarConsulta("begin;");
		$qConsultarSeguimientoSADDA=$csc->consultarSeguimientoSADDA($conexion, $idDestinacionAduanera);
		if(pg_num_rows($qConsultarSeguimientoSADDA)==0){
			
			$csc->actualizarEstadoSeguimientoDDA($conexion, $idDestinacionAduanera);
			$idSeguimientoCuarentenarioSa= pg_fetch_result($csc->guardarNuevoSeguimientoSADDA($conexion, $idDestinacionAduanera, 'abierto', $fechaElaboracion, $coordenadaX, $coordenadaY, $coordenadaZ, $lote, $csmt, $aic, $fechaIngresoEcuador, $rutaInicioCuarentena  ), 0, 'id_seguimiento_cuarentenario_sa');
		
			$idDetalleSeguimientoCuarentenarioSa= pg_fetch_result($csc->guardarNuevoDetalleSeguimientoSADDA($conexion, $idSeguimientoCuarentenarioSa, $resultadoInspeccion, $cantidadSanos,$cantidadEnfermos, $cantidadMuertos, $cantidadTotal ,$DatosJson, $rutaSacrificioSanitario, $usuario), 0, 'id_detalle_seguimientos_cuarentenarios_sa');
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $csc->imprimirLineaSeguimientosCuarentenariosSA($idDetalleSeguimientoCuarentenarioSa, $contador, $fechaRegistro, $cantidadTotal, $resultadoInspeccion );
		
		}else{
		
			if($_POST['opcion']=='modificar'){
				$csc->actualizarDetalleSeguimientoSADDA($conexion, $idDetalleSeguimientoCuarentenarioSa, $resultadoInspeccion, $cantidadSanos, $cantidadEnfermos, $cantidadMuertos, $cantidadTotal, $DatosJson, $rutaSacrificioSanitario,$usuario);
				$mensaje['estado'] = 'exito';
				$mensaje['idDetalle']=$idDetalleSeguimientoCuarentenarioSa;
				$mensaje['mensaje'] = $csc->imprimirLineaSeguimientosCuarentenariosSA($idDetalleSeguimientoCuarentenarioSa, $contador, $fechaRegistro, $cantidadTotal, $resultadoInspeccion );
				
			}else{
				$idSeguimientoCuarentenarioSa=pg_fetch_result($qConsultarSeguimientoSADDA, 0, 'id_seguimiento_cuarentenario_sa');
				$idDetalleSeguimientoCuarentenarioSa=pg_fetch_result($csc->guardarNuevoDetalleSeguimientoSADDA($conexion, $idSeguimientoCuarentenarioSa, $resultadoInspeccion, $cantidadSanos,$cantidadEnfermos, $cantidadMuertos, $cantidadTotal ,$DatosJson, $rutaSacrificioSanitario, $usuario), 0, 'id_detalle_seguimientos_cuarentenarios_sa');
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $csc->imprimirLineaSeguimientosCuarentenariosSA($idDetalleSeguimientoCuarentenarioSa, $contador, $fechaRegistro, $cantidadTotal, $resultadoInspeccion );
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