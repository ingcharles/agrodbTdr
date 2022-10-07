<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idSolicitud = htmlspecialchars ($_POST['idSolicitud'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		
		$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $idSolicitud));
		$cr->eliminarDatosOperacion($conexion, $idSolicitud);
		$cr->eliminarDatosHistoricoOperacion($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion']);
		$cr->eliminarDatosTipoOperacionPorIndentificadorSitio($conexion, $operacion['id_operador_tipo_operacion']);
				
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idSolicitud;
		
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