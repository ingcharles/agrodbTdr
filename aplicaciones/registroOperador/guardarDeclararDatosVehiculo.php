<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';


$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {

	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();

	$idArea = $_POST['idArea'];
	$idOperacion = $_POST['idOperacion'];
	$tipoContenedor= $caracteristicaContenedor = $servicio = '';
	
	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];
	$tipo = $_POST['tipo'];
	$color = $_POST['color'];
	$clase = $_POST['clase'];
	$placa = strtoupper($_POST['placa']);
	$registroContenedorVehiculo = $_POST['registroContenedorVehiculo'];
	$anio = $_POST['anio'];
	$capacidadInstalada = $_POST['capacidadInstalada'];
	$codigoUnidadMedida = $_POST['unidadMedida'];
	
	if(isset($_POST['carnicos'])){
	    $tipoTanque = 90;
	    $horaInicioRecoleccion = '';
	    $horaFinRecoleccion = '';
	    $tipoContenedor = $_POST['tipoContenedor'];
	    $caracteristicaContenedor = $_POST['caracteristicaContenedor'];
	    $servicio = $_POST['servicio'];
	}else{
	    $tipoTanque = $_POST['tipoTanque'];
	    $horaInicioRecoleccion = $_POST['horaInicioRecoleccion'];
	    $horaFinRecoleccion = $_POST['horaFinRecoleccion'];
	    $tipoContenedor = '';
	    $caracteristicaContenedor = '';
	    $servicio = '';
	}

	try {
	
		$conexion->ejecutarConsulta("begin;");	
	
		$operacion = pg_fetch_assoc($cro->abrirOperacionXid($conexion, $idOperacion));
		$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
		$idHistorialOperacion = $operacion['id_historial_operacion'];
		$idTipoOperacion = $operacion['id_tipo_operacion'];
		
		$idflujoOperacion = pg_fetch_assoc($cro->obtenerIdFlujoXOperacion($conexion, $idOperacion));
		$idFlujoActual = pg_fetch_assoc($cro->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], 'declararDVehiculo'));
		$estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']));
		$banderaRegistroVehiculo=false;
		
		$verificarVehiculoRegistrado = $cro->verificarVehiculoRegistradoEstado($conexion, $placa, $idOperadorTipoOperacion);	
		$datos = pg_fetch_assoc($verificarVehiculoRegistrado);
	
		$idDatoVehiculoObtenido=$datos['id_dato_vehiculo'];

		$banderaGeneral=false;
			
		if(pg_num_rows($verificarVehiculoRegistrado)>0 ){

			if($datos['estado']=='declararDVehiculo'){
		 
				$idDatoVehiculo = $cro->guardarInformacionDatosVehiculo($conexion, $idArea, $idTipoOperacion, $marca, $modelo, $tipo, $color, $clase, $placa, $registroContenedorVehiculo, $tipoTanque, $anio, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion, $horaInicioRecoleccion, $horaFinRecoleccion,$tipoContenedor, $caracteristicaContenedor,$servicio);
				//$idDatoVehiculo = pg_fetch_result($verificarVehiculoRegistrado, 2, 'id_dato_vehiculo');
				$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $idOperacion);
				$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
				$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
			   
					switch ($idArea){		    
						case 'AI':
							switch ($opcionArea){
								case 'MDT':
									case 'MDC':
											$cro->inactivarVehiculoMedioTransporte($conexion, $idDatoVehiculoObtenido);
								break;
							}
						break;
					}
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
			}else{
				
				$mensaje['estado'] = 'Error';
				$mensaje['mensaje'] = "El vehículo ya se encuentra registrado en una operacíon";
				$banderaGeneral = true;
			}
		}else{
			$idDatoVehiculo = $cro->guardarInformacionDatosVehiculo($conexion, $idArea, $idTipoOperacion, $marca, $modelo, $tipo, $color, $clase, $placa, $registroContenedorVehiculo, $tipoTanque, $anio, $capacidadInstalada, $codigoUnidadMedida, $idOperadorTipoOperacion, $horaInicioRecoleccion, $horaFinRecoleccion,$tipoContenedor, $caracteristicaContenedor,$servicio);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
		}

		//TODO: Preguntar Eddy
		if($operacion['modulo_provee'] == 'moduloExterno' && $estado['estado'] == 'cargarProducto'){
			$estado = pg_fetch_assoc($cro->obtenerEstadoFlujoOperacion($conexion, $idflujoOperacion['id_flujo_operacion'], $idFlujoActual['predecesor']+1));
		}
		
		$cro->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion);
		
		//TODO:Preguntar Eddy
		
			switch ($estado['estado']){
				 
				case 'pago':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'inspeccion':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'cargarAdjunto':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'cargarProducto':
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
				break;
				case 'documental':
					if ($banderaGeneral==false){
					$cro->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $idHistorialOperacion, $estado['estado']);
					}
				break;
				
			}
			
		$cro->actualizarEstadoTipoOperacionPorIndentificadorSitio($conexion, $idOperadorTipoOperacion, $estado['estado']);
			
		// $mensaje['estado'] = 'exito';
		// $mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
		
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