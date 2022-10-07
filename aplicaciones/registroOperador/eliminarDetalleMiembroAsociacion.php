<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$usuario = $_SESSION['usuario'];
	$idTipoOperacion = $_POST['idTipoOperacion'];
	$idOperacion = $_POST['idOperacion'];
	$idProducto = $_POST['idProducto'];
	$idSitio = $_POST['idSitio'];
	$idArea = $_POST['idArea'];
	$idMiembro = $_POST['idMiembro'];
	$rendimiento = $_POST['rendimiento'];
	$idDetalleMiembro = $_POST['idDetalleMiembro'];
	
	$nombreTipoOperacion = $_POST['nombreTipoOperacion'];
	$nombreProducto = $_POST['nombreProducto'];
	$nombreSitio = $_POST['nombreSitio'];
	$nombreArea = $_POST['nombreArea'];
	$identificadorMiembroAsociacion = $POST['identificadorMiembroAsociacion'];
	
	try {
		$conexion = new Conexion();
		$cro = new ControladorRegistroOperador();
		$ca = new ControladorAuditoria();

		$idOperacion = pg_fetch_result($cro->obtenerOperacionXIdentificadorTipoProductoYSitio($conexion, $usuario, $idTipoOperacion, $idProducto, $idSitio), 0, 'id_operacion');
		
		$cro->quitarAreaOperacionMiembroAsociacion($conexion, $idOperacion);
		
		$qCabeceraMiembro = $cro->obtenerDatosMiembroAsociacionXIdMiembro($conexion, $idMiembro);
		$cabeceraMiembro = pg_fetch_assoc($qCabeceraMiembro);
		
		
		$ca->actualizarAuditoriaXIdMiembroASociacion($conexion, $idMiembro);
		$ca->guardarAuditoriaAsociacion($conexion, $idMiembro, $cabeceraMiembro['codigo_miembro_asociacion'], $cabeceraMiembro['identificador_miembro_asociacion'], $usuario, $cabeceraMiembro['nombre_miembro_asociacion'], $cabeceraMiembro['apellido_miembro_asociacion'], $cabeceraMiembro['codigo_magap'], $idOperacion, $idArea, $idSitio, $rendimiento, 
				'El operador '.$usuario.' ha eliminado al miembro '.$cabeceraMiembro['identificador_miembro_asociacion'].' - '.$cabeceraMiembro['nombre_miembro_asociacion'].' '.$cabeceraMiembro['apellido_miembro_asociacion'].' del sitio ' .$nombreSitio.' área '.$nombreArea.' con la operación de '.$nombreOperacion.' y producto '.$nombreProducto.'.', 'activo');
		
		
		$qDetalelMiembroAsociacion = $cro->obtenerDetalleMiembroXIdentificadorXSitio($conexion, $idMiembro, $idSitio);
		
		if(pg_num_rows($qDetalelMiembroAsociacion)==0){
			
			$ca->actualizarAuditoriaXIdMiembroASociacion($conexion, $idMiembro);
			$cro->actualizarEstadoMiembroAsociacion($conexion, $idMiembro, 3);
			$ca->guardarAuditoriaAsociacion($conexion, $idMiembro, $cabeceraMiembro['codigo_miembro_asociacion'], $cabeceraMiembro['identificador_miembro_asociacion'], $usuario, $cabeceraMiembro['nombre_miembro_asociacion'], $cabeceraMiembro['apellido_miembro_asociacion'], $cabeceraMiembro['codigo_magap'], 0, 0, 0, 0,
					'El operador '.$usuario.' ha inhabilitado de la asociación al miembro '.$cabeceraMiembro['identificador_miembro_asociacion'].' - '.$cabeceraMiembro['nombre_miembro_asociacion'].' '.$cabeceraMiembro['apellido_miembro_asociacion'].'.', 'activo');
			
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idDetalleMiembro;

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