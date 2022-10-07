<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$tipo_aplicacion = ($_SESSION['idAplicacion']);

try{
	
	try {
		$conexion = new Conexion();
		$cro = new ControladorRegistroOperador();
		$ca = new ControladorAuditoria();
		
		
		$usuario = $_SESSION['usuario'];
		$idMiembroAsociacion = $_POST['idMiembroAsociacion'];
		$idSitio = $_POST['idSitio'];
		
		$qCabeceraMiembro = $cro->obtenerDatosMiembroAsociacionXIdMiembro($conexion, $idMiembroAsociacion);
		$cabeceraMiembro = pg_fetch_assoc($qCabeceraMiembro);
		
		$cro->eliminarDetalleMiembroAsociacion($conexion, $idMiembroAsociacion, $idSitio);		
		
		$qDetalelMiembroAsociacion = $cro->obtenerDetalleMiembroXIdentificadorXSitio($conexion, $idMiembroAsociacion, $idSitio);
		
				
		$ca->actualizarAuditoriaXIdMiembroASociacion($conexion, $idMiembroAsociacion);
		$cro->actualizarEstadoMiembroAsociacion($conexion, $idMiembroAsociacion, 3);
		$ca->guardarAuditoriaAsociacion($conexion, $idMiembroAsociacion, $cabeceraMiembro['codigo_miembro_asociacion'], $cabeceraMiembro['identificador_miembro_asociacion'], $usuario, $cabeceraMiembro['nombre_miembro_asociacion'], $cabeceraMiembro['apellido_miembro_asociacion'], $cabeceraMiembro['codigo_magap'], 0, 0, 0, 0,
					'El operador '.$usuario.' ha inhabilitado de la asociación al miembro '.$cabeceraMiembro['identificador_miembro_asociacion'].' - '.$cabeceraMiembro['nombre_miembro_asociacion'].' '.$cabeceraMiembro['apellido_miembro_asociacion'].'.', 'activo');
					
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos del miembro de asociación se han eliminado';	
		
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