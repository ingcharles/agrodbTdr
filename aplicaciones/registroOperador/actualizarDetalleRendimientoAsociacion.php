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
$idTipoOperacion = $_POST['operacion'];
$idProducto = $_POST['idProducto'];
$idSitio = $_POST['sitio'];
$idArea = $_POST['idArea'];
$rendimiento = $_POST['rendimiento'];
$identificadorMiembroAsociacion = $_POST['identificadorMiembroAsociacion'];
$idMiembro = $_POST['idMiembroAsociacion'];
$idDetalleMiembro = $_POST['idDetalleMiembro'];
$idSitioAnterior = $_POST['idSitioAterior'];
$idProductoAnterior = $_POST['idProductoAnterior'];
$rendimientoAnterior = $_POST['rendimientoAnterior'];

$operacionProducto =  htmlspecialchars ($_POST['nombreOperacionProducto'],ENT_NOQUOTES,'UTF-8');
list($nombreOperacion, $nombreProducto) = explode(" - ", $operacionProducto);

$sitioArea =  htmlspecialchars ($_POST['nombreSitioArea'],ENT_NOQUOTES,'UTF-8');
list($nombreSitio, $nombreArea) = explode(" - ", $sitioArea);

$datosGenerales =  htmlspecialchars ($_POST['datosSAOPAterior'],ENT_NOQUOTES,'UTF-8');
list($nombreSitioAnterior, $nombreAreaAnterior, $nombreOperacionAnterior, $nombreProductoAnterior) = explode("@", $datosGenerales);

//$bandera = 0;

try {
	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	$ca = new ControladorAuditoria();

	$qDatosMiembroAsociacion = $cro->obtenerDatosMiembroAsociacionXIdMiembro($conexion, $idMiembro);
	$datosMiembro = pg_fetch_assoc($qDatosMiembroAsociacion);
	
	//$miembroAsociacion = $cro -> buscarMiembroAsociacion($conexion, $identificadorMiembroAsociacion, $usuario);
	
	$idOperacion = pg_fetch_result($cro->obtenerOperacionXIdentificadorTipoProductoYSitio($conexion, $usuario, $idTipoOperacion, $idProducto, $idSitio), 0, 'id_operacion');
	
	$qExisteSitio = $cro -> buscarExisteSitio($conexion, $idSitio);

	
	if(pg_num_rows($qExisteSitio)!=0){
		
		$qDuenioSitio = $cro -> buscarMiembroDuenioSitio($conexion, $idSitio);
		$duenioSitio = pg_fetch_assoc($qDuenioSitio);
			
		if ($duenioSitio['identificador_miembro_asociacion'] == $identificadorMiembroAsociacion){
				
			$cro->quitarOperacionesOrganico($conexion, $idOperacion);
				
			$cro -> actualizarDetalleRendimientoAsociacion($conexion, $idDetalleMiembro, $idMiembro, $idOperacion, $idArea, $idSitio, $rendimiento);
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos fueron actualizados';
			
		}/*else{
						
		}*/
	}else{
	
		$cro->quitarOperacionesOrganico($conexion, $idOperacion);
	
		$cro -> actualizarDetalleRendimientoAsociacion($conexion, $idDetalleMiembro, $idMiembro, $idOperacion, $idArea, $idSitio, $rendimiento);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';
		
		
	}

	
	if($idSitioAnterior != $idSitio || $idProductoAnterior != $idProducto || $rendimientoAnterior != $rendimiento){
				
		$observacionAuditoria.= 'El operador '.$usuario. ' ';
		
		if($idSitioAnterior != $idSitio){
			
			$observacionAuditoria.='ha modificado de sitio al miembro de asociacion '.$identificadorMiembroAsociacion.' - '.$datosMiembro['nombre_miembro_asociacion'].' '.$datosMiembro['apellido_miembro_asociacion'].': del sitio '.$nombreSitioAnterior.' área '.$nombreAreaAnterior.' con la operación de '
					.$nombreOperacionAnterior.' y producto '.$nombreProductoAnterior.' al sitio '.$nombreSitio.' área '.$nombreArea.' con la operación de '.$nombreOperacion.' y producto '.$nombreProducto.' ';				
			
		}
		
		if($idProductoAnterior != $idProducto){
			
			$observacionAuditoria.='ha modificado la operación de '.$nombreOperacionAnterior.' con producto '.$nombreProductoAnterior.' en el sitio '.$nombreSitioAnterior.' área '.$nombreAreaAnterior.' por la operación '.$nombreOperacion.' con producto '.$nombreProducto.' en el sitio '.$nombreSitio.' área '.$nombreArea.' ';
			
		}
		
		if($rendimientoAnterior != $rendimiento){
			
			$observacionAuditoria.= 'ha modificado el rendimiento estimado de '.$rendimientoAnterior.' a '.$rendimiento.' ';
		
		}
		
		$ca->actualizarAuditoriaXIdMiembroASociacion($conexion, $idMiembro);
		
		$ca->guardarAuditoriaAsociacion($conexion, $idMiembro, $datosMiembro['codigo_miembro_asociacion'],$identificadorMiembroAsociacion, $usuario, $datosMiembro['nombre_miembro_asociacion'], $datosMiembro['apellido_miembro_asociacion'], $datosMiembro['codigo_magap'], $idOperacion, $idArea, $idSitio, $rendimiento, $observacionAuditoria, 'activo');
		
		//TODO: REINICIAR PROCESO A DOCUMENTAL.
		$cro->enviarOperacionEstadoAnterior($conexion, $idOperacion);
		$cro->enviarOperacion($conexion, $idOperacion, 'subsanacion');
		
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


