<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../aplicaciones/uath/models/salidas.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$area=$_POST['area'];
	$identificador = $_POST['identificador'];
	$responsable = $_POST['responsable'];
	$identificadorSubrogacion = $_POST['identificadorSubrogacion'];
	$fechaSalida = $_POST['fechaSalida'];
	$fechaRetorno = $_POST['fechaRetorno'];
	$idResponsable = $_POST['idResponsable'];
	
	$fechaIni = strtotime($fechaSalida);	
	$fechaFin = strtotime($fechaRetorno);
	$fechaActual=strtotime(date('Y-m-d'));
	if($identificadorSubrogacion == '')$mensaje['mensaje'] = 'No ha seleccionado un responsable...!';
	if($fechaFin < $fechaActual)$mensaje['mensaje'] = 'La fecha fin no debe ser menor a la actual...!';
	
		try {
					$conexion = new Conexion();
					$cc = new ControladorCatastro();
					$conexion->ejecutarConsulta("begin;");
					if($identificador != '' and $identificadorSubrogacion != '' and $area != ''){
					if($identificadorSubrogacion != '' && $fechaActual <= $fechaFin ){
						
						if($fechaActual>=$fechaIni && $fechaActual <=$fechaFin){
								$estado='activo';
										if($idResponsable != ''){
										   if($consultaSubrogar = pg_fetch_assoc($cc->obtenerSubrogacionesFuncionarios($conexion, $area,'',$estado))){
	    										$consulAplicacion = $cc->devolverAplicacionSubrogar($conexion, $idResponsable);
	    										while($consultaApli = pg_fetch_assoc($consulAplicacion)){
	    											$cc->eliminarAplicacionUsuario($conexion, $consultaApli['id_aplicacion'], $consultaSubrogar['identificador_subrogador']);
	    										}							
	    										$consulPerfil = $cc->devolverPerfilSubrogar($conexion, $idResponsable);
	    										while($consultaPerf = pg_fetch_assoc($consulPerfil)){						
	    											$cc->elminarPerfilUsuario($conexion, $consultaPerf['id_perfil'], $consultaSubrogar['identificador_subrogador']);						
	    										}
										   }
											$cc->elminarPerfilSubrogar($conexion, $idResponsable);
											$cc->eliminarAplicacionSubrogar($conexion, $idResponsable);
											$cc->actualizarResponsables($conexion, $identificador,$identificadorSubrogacion,$fechaSalida, $fechaRetorno, $area, $estado, $idResponsable);
											
										}else {
											    $fila = pg_fetch_assoc($cc->asignarResponsable($conexion, $identificador,$identificadorSubrogacion,$fechaSalida, $fechaRetorno, $area, $estado));
											    $idResponsable = $fila['id_responsable'];
										}
								//----------------asignar aplicaciones a funcionario------------------------------------------------------------------
								$aplicacion=$cc->devolverAplicacionesNuevas($conexion, $identificador,$identificadorSubrogacion);
								while($consultaAplicacion = pg_fetch_assoc($aplicacion)){
									$cc->asignarAplicacionSubrogacion($conexion, $consultaAplicacion['id_aplicacion'],$idResponsable);
									$cc->asignarAplicacionResponsable($conexion, $identificadorSubrogacion,$consultaAplicacion['id_aplicacion'],$consultaAplicacion['mensaje_notificacion']);
								}
								//---------------asignar perfiles a funcionario-------------------------------------------------------------------						
								$perfil=$cc->devolverPerfilesNuevos($conexion, $identificador,$identificadorSubrogacion);
								while($consultaPerfil = pg_fetch_assoc($perfil)){
									$cc->asignarPerfilSubrogacion($conexion, $idResponsable,$consultaPerfil['id_perfil']);
									$cc->asignarPerfilResponsable($conexion, $identificadorSubrogacion,$consultaPerfil['id_perfil']);
								}							
		
							//-------------------------------inactivar funcionario responsable-------------------------------------------------------------					
							$cc->inactivarActivarResponsables($conexion, $area,0,'false',0,'',3);
							
							//-------------------------------activar funcionario resonsable-----------------------------------------------------------------
							if(pg_num_rows($cc->verificarExisteResponsable($conexion, $area, $identificadorSubrogacion))){
								$cc->inactivarActivarResponsables($conexion, $area,1,'true',1,$identificadorSubrogacion,2);							
							}else {
								$cc->crearResponsable($conexion, $area,$identificadorSubrogacion,2,'true',1,1);		
							}				
		
							//------------crear la subrogacion con las aplicaciones y perfiles al funcionario-------------------------------
							}else {
								$estado='creado';
								if($idResponsable != ''){
									$cc->actualizarResponsables($conexion, $identificador,$identificadorSubrogacion,$fechaSalida, $fechaRetorno, $area, $estado, $idResponsable);
									$cc->elminarPerfilSubrogar($conexion, $idResponsable);
									$cc->eliminarAplicacionSubrogar($conexion, $idResponsable);
								}
								else {
									$fila = pg_fetch_assoc($cc->asignarResponsable($conexion, $identificador,$identificadorSubrogacion,$fechaSalida, $fechaRetorno, $area, $estado));
									$idResponsable = $fila['id_responsable'];
								}
								//-----------------------------------------------------------------------------------
								$perfil=$cc->devolverPerfilesNuevos($conexion, $identificador,$identificadorSubrogacion);
								while($consultaPerfil = pg_fetch_assoc($perfil)){
									$cc->asignarPerfilSubrogacion($conexion, $idResponsable,$consultaPerfil['id_perfil']);
								}						
								//----------------------------------------------------------------------------------
								$aplicacion=$cc->devolverAplicacionesNuevas($conexion, $identificador,$identificadorSubrogacion);
								while($consultaAplicacion = pg_fetch_assoc($aplicacion)){
									$cc->asignarAplicacionSubrogacion($conexion, $consultaAplicacion['id_aplicacion'], $idResponsable);
								}							
							} 
							//-------------------------------------------------------------------------------------------------	
							$conexion->ejecutarConsulta("commit;");
							$mensaje['estado'] = 'exito';
							$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';	
							mensajesSalidas($mensaje);
						 }elseif($responsable == 'Subrogante' && $fechaActual <= $fechaFin && $idResponsable != ''){
						 	    $cc->actualizarResponsables($conexion, $identificador,'',$fechaSalida, $fechaRetorno, $area, 'activo', $idResponsable);
						 	    $conexion->ejecutarConsulta("commit;");
						 	    $mensaje['estado'] = 'exito';
						 		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
						 		mensajesSalidas($mensaje);
						 }else{
							mensajesSalidas($mensaje);
						 }
					 
					}else{
						$mensaje['estado'] = 'error';
						$mensaje['mensaje'] = 'Error en realizar la subrogación, seleccionar correctamente la información...!!';
						mensajesSalidas($mensaje);
					}
					 
				} catch (Exception $ex){
					//echo $ex;
					$conexion->ejecutarConsulta("rollback;");
					mensajesSalidas($mensaje);
				}finally {
					$conexion->desconectar();
				}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	mensajesSalidas($mensaje);
}
?>