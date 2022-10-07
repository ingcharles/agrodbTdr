<?php
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSolicitudes.php';
require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion();
$cs = new ControladorSolicitudes();
$ca = new ControladorAuditoria();

$conexion->verificarSesion();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$estado = $_POST['nuevoEstado'];
	$revisor = $_POST['revisor'];
	$id_solicitud = $_POST['id_solicitud'];
	$usuario = $_SESSION['usuario'];
	$comentario = $_POST['comentario'];
	$nombreUsuario = $_POST['iNombreUsuario'];
		
	try {

		
		if($comentario!= ''){
			
				/**Inicio Auditoria**/
			
				$qTransaccion = $ca -> buscarTransaccion($conexion, $id_solicitud, 'Documentos');
				$transaccion = pg_fetch_assoc($qTransaccion);
			
				if($transaccion['id_transaccion'] == ''){
					$qLog = $ca -> guardarLog($conexion,$_SESSION['idAplicacion']);
					$qTransaccion = $ca ->guardarTransaccion($conexion, $id_solicitud, pg_fetch_result($qLog, 0, 'id_log'));
				}
			
				/**Fin Auditoria **/
			
				$cs->cambioEstadoRevisor($conexion, $id_solicitud, $usuario, $comentario, $estado);

				if($estado == 'Delegado'){
				
					$res = $cs-> buscarUsuarioSolicitud($conexion, $id_solicitud, $revisor);
					$consultaUsuario = pg_fetch_assoc($res);
				
					$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'], '<b>'.$_SESSION['datosUsuario'].'</b>, solicita revisión a '. $nombreUsuario );
				
					if ($consultaUsuario['identificador']==''){
						$cs->solicitarRevision($conexion, $id_solicitud,$usuario, $revisor, $estado);
					}else{
						$cs->actualizarEstadoReasignado($conexion, $id_solicitud, $revisor);
					}
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'La socilictud ha sido reasignada satisfactoriamente';
				}else{
				    $ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'], '<b>'.$_SESSION['datosUsuario'].'</b>, actualiza el estado del archivo a '. $estado );
				}
			
				$res_q = $cs -> buscarUsuarioSolicitud($conexion, $id_solicitud, $usuario);
				$aprobador = pg_fetch_assoc($res_q);
			
				if(pg_num_rows($res_q)!= 1){
					$aprobador['accion'] = 'Aprobador';
				}
			
				$_SESSION['prueba'] = $aprobador['accion'];
			
				$r_condicion = $cs -> condicionSolicitud($conexion, $id_solicitud);
				$condicion = pg_fetch_assoc($r_condicion);
			
				$total_q = $cs ->solicitudesTotales($conexion, $id_solicitud);
				$totales = pg_fetch_assoc($total_q);
			
				$aprobadas_q = $cs ->solicitudesAprobadas($conexion,$id_solicitud);
				$aprobadas = pg_fetch_assoc($aprobadas_q);
			
				$rechazadas_q = $cs ->solicitudesRechazadas($conexion,$id_solicitud);
				$rechazadas = pg_fetch_assoc($rechazadas_q);
			
				$delegadas_q = $cs ->solicitudesDelegadas($conexion, $id_solicitud);
				$delegadas = pg_fetch_assoc($delegadas_q);
			
			
				if(($totales['total'] == $aprobadas['aprobadas']+$delegadas['delegadas']) && $estado =='Aprobado' && $aprobador['accion'] != 'Aprobador'  ){
					if($condicion['condicion']=='atentidoResponsable'){
						$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'APROBADO');
					}
					else{	
						$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'ASIGNARRESPONSABLE');
					}
				}
			
				else if( ($totales['total'] == $aprobadas['aprobadas']+$delegadas['delegadas']+$rechazadas['rechazadas']) && $aprobador['accion'] != 'Aprobador'){
					/*if($condicion['condicion']=='atendidoReponsable'){
						$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'ATENDIDORESPONSABLE');
					}else{*/
						$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'ATENDIDO');
					//}
				}
				
				else if($estado =='Aprobado' && $aprobador['accion'] == 'Aprobador'){
					$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'APROBADO');
				}
			
				else if($estado !='Aprobado' && $aprobador['accion'] == 'Aprobador'){
					$cs->actualizarEstadoSolicitud($conexion, $id_solicitud, 'ATENDIDORESPONSABLE');
				}
			
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Sus comentarios han sido enviados satisfactoriamente';
			
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Por favor ingrese un comentario.';
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
