<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csl = new ControladorServiciosLinea();

	try {
		
		$idRutaTransporte = $_POST['idRutaTransporte'];
		$identificadorResponsable = $_POST['identificadorResponsable'];
		$latitud = $_POST['latitud'];
		$longitud = $_POST['longitud'];
		$direccion = $_POST['direccion'];
		$horaAproximada = $_POST['horaAproximada'];
		$recorrido = $_POST['recorrido'];
		$contador = $_POST['contador'];
		
		$conexion->ejecutarConsulta("begin;");
		$orden=$csl->autogenerarSecuenciaOrdenRecorridosDetalle($conexion, 'g_servicios_linea.detalle_rutas_transporte', 'orden','id_ruta_transporte', $idRutaTransporte);		
		$idDetalleRuta=pg_fetch_row($csl->guardarNuevoDetalleRutasTransporte($conexion, $idRutaTransporte, $identificadorResponsable, $latitud, $longitud, $direccion,$horaAproximada, $recorrido,$orden));
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $csl->imprimirLineaDetalleRutasTransporte($idDetalleRuta[0], $latitud, $longitud, $direccion, $horaAproximada, $recorrido, $contador);
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