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
	$idResponsable = $_POST['idResponsable'];
	
		try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				$conexion->ejecutarConsulta("begin;");
				if($identificadorSubrogacion != ''){
					
						//----------------asignar aplicaciones a funcionario------------------------------------------------------------------
						$aplicacion=$cc->devolverAplicacionesNuevas($conexion, $identificador,$identificadorSubrogacion);
						while($consultaAplicacion = pg_fetch_assoc($aplicacion)){
							$cc->asignarAplicacionResponsable($conexion, $identificadorSubrogacion,$consultaAplicacion['id_aplicacion'],$consultaAplicacion['mensaje_notificacion']);
							$cc->eliminarAplicacionUsuario($conexion, $consultaAplicacion['id_aplicacion'], $identificador);
							
						}
						//---------------asignar perfiles a funcionario-------------------------------------------------------------------						
						$perfil=$cc->devolverPerfilesNuevos($conexion, $identificador,$identificadorSubrogacion);
						while($consultaPerfil = pg_fetch_assoc($perfil)){
							$cc->asignarPerfilResponsable($conexion, $identificadorSubrogacion,$consultaPerfil['id_perfil']);
							$cc->elminarPerfilUsuario($conexion, $consultaPerfil['id_perfil'], $identificador);
						}							
	
						//-------------------------------inactivar funcionario responsable-------------------------------------------------------------					
						$cc->inactivarActivarResponsables($conexion, $area,0,'false',0,'',3);
						
						//-------------------------------activar funcionario resonsable-----------------------------------------------------------------
						if(pg_num_rows($cc->verificarExisteResponsable($conexion, $area, $identificadorSubrogacion))){
								$resulta=$cc->inactivarActivarResponsables($conexion, $area,1,'true',1,$identificadorSubrogacion,1);							
						}else {
								$cc->crearResponsable($conexion, $area,$identificadorSubrogacion,1,'true',1,1);		
							}				
						//---------------------------------verificar y activar zona----------------------------------------------------------------------
						$consultaArea=verificarDistrital($area);
						if($consultaArea != ''){
						$cc->inactivarActivarResponsables($conexion, $consultaArea,0,'false',0,'',3);
						
						if(pg_num_rows($cc->verificarExisteResponsable($conexion, $consultaArea, $identificadorSubrogacion))){
								$resulta=$cc->inactivarActivarResponsables($conexion, $consultaArea,1,'true',1,$identificadorSubrogacion,1);
							}else {
								$cc->crearResponsable($conexion, $consultaArea,$identificadorSubrogacion,1,'true',1,1);
							}
						}	
						//-------------------------------------------------------------------------------------------------------------------------------	
						$conexion->ejecutarConsulta("commit;");
						$mensaje['estado'] = 'exito';
						$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';	
						mensajesSalidas($mensaje);
				 }else{
				 	$mensaje['mensaje'] = 'No ha seleccionado un responsable..!!';
					mensajesSalidas($mensaje);
				 }
				} catch (Exception $ex){
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