<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEventoSanitario.php';
require_once '../../clases/ControladorEventoSanitario.php';


$estado = 'Cerrado';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cpco = new ControladorNotificacionEventoSanitario();
	$ces = new ControladorEventoSanitario();

	$identificador = $_SESSION['usuario'];

	$idNotificacionEventoSanitario = htmlspecialchars ($_POST['idNotificacionEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$fechaNuevaInspeccion = htmlspecialchars ($_POST['fechaNuevaInspeccion'],ENT_NOQUOTES,'UTF-8');

	try {

		if(($identificador != null) || ($identificador != '')){
		
			$tiposPatologiaEspecieAfectada = $cpco->listarTipoPatologiaEspecieAfectada($conexion, $idNotificacionEventoSanitario);
			
			if((pg_num_rows($tiposPatologiaEspecieAfectada) != 0)){	
				$nuevaInspeccion == 'No';
				$esEventoSanitario = 1;
				$justificacionEventoSanitario ='Evento sanitario por notificación';
				$estado = 'Cerrado';
				$esEventoSanitario = 1;
				$eventoSanitario = pg_fetch_assoc($cpco->abrirNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario));
				$codigoParroquia = substr($eventoSanitario['numero'],3,-5); 
				$conexion->ejecutarConsulta("begin;");
				$numero = pg_fetch_result($ces->generarNumeroEventoSanitario($conexion, 'ES-'.$codigoParroquia), 0, 'num_solicitud');
				$tmp= explode("-", $numero);
				$incremento = end($tmp)+1;
				$numeroSolicitud = 'ES-'.$codigoParroquia.'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
				$conexion->ejecutarConsulta("commit;");
				$conexion->ejecutarConsulta("begin;");
				$idEventoSanitarioCon = $cpco->generarEventoSanitario( $conexion, 
														$identificador,
														$numeroSolicitud, $eventoSanitario['fecha'], $eventoSanitario['id_origen'], $eventoSanitario['nombre_origen'],
														$eventoSanitario['id_canal'], $eventoSanitario['nombre_canal'], $eventoSanitario['id_provincia'], $eventoSanitario['provincia'],
														$eventoSanitario['id_canton'], $eventoSanitario['canton'],$eventoSanitario['id_parroquia'],$eventoSanitario['parroquia'], 
														$eventoSanitario['sitio_predio']);
															
				$conexion->ejecutarConsulta("commit;");

				$conexion->ejecutarConsulta("begin;");		
					
				$cpco->cierreNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario, $identificador, $fechaNuevaInspeccion, $estado, 
															$esEventoSanitario, $justificacionEventoSanitario, $numeroSolicitud);
										
				$conexion->ejecutarConsulta("commit;");
			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos se han guardado exitosamente' ;
				
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Debe ingresar por lo menos un resultado de inspección para poder continuar.";
			}
				
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.";
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
}
?>