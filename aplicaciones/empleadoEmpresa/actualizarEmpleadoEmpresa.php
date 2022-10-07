<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAplicaciones.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$datos = array('id_empleado' => htmlspecialchars ($_POST['idEmpleado'],ENT_NOQUOTES,'UTF-8'),				   
				   'estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8'),
					'identificacion_empresa' => htmlspecialchars ($_POST['identificacionEmpresa'],ENT_NOQUOTES,'UTF-8'));
	
	$conexion = new Conexion();
	$cee = new ControladorEmpleadoEmpresa();
	$cu = new ControladorUsuarios();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	$ca= new ControladorAplicaciones();
	
	try {
		$conexion->ejecutarConsulta("begin;");
		$identificadorEmpleado=pg_fetch_result($cee->abrirEmpleado($conexion, $datos['id_empleado']), 0, 'identificador_empleado');
			
		if($datos['estado']=='inactivo'){		    
		    //Elimino los roles del empleado inactivo (roles de vacunación y movilización)
		    $qDatosEmpleado = $cee->obtenerDatosEmpleadoPorIdentificadorEmpleado($conexion, $identificadorEmpleado);
		    $datosEmpleado = pg_fetch_assoc($qDatosEmpleado);
		    $cee->eliminarRolesEmpleadoPorIdEmpleado($conexion, $datosEmpleado['id_empleado']);
		    	    
			$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA','PRG_OPERADORMASI')");
			while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
				$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_VACUN','PFL_FISCA_VACUN','PFL_ADMIN_ROLEM','PFL_INCRI_MASIV')");
				while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
					$cu->eliminarPerfilUsuario($conexion,$identificadorEmpleado , $filaPerfil['id_perfil']);
				}
				$cu->eliminarAplicacionUsuario($conexion, $identificadorEmpleado, $filaAplicacion['id_aplicacion']);
			}
			$cee->actualizarRolEmpleado($conexion, $datos['id_empleado'], $datos['estado'], $_SESSION['usuario']);
		}	
		
		if($datos['estado']=='activo'){
			$qMovilizacionEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['identificacion_empresa'],"('OPMSA')");
			if (pg_num_rows($qMovilizacionEmpresa)>0){
				$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_OPERADORMASI')");
				while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
					$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_INCRI_MASIV')");
					$perfilesArray=Array();
					while($fila=pg_fetch_assoc($qGrupoPerfiles)){
						$perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
					}
					if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $identificadorEmpleado))==0){
						$cgap->guardarGestionAplicacion($conexion, $identificadorEmpleado,$filaAplicacion['codificacion_aplicacion']);
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $identificadorEmpleado);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $identificadorEmpleado,$datosPerfil['codigoPerfil']);
						}
					}else{
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $identificadorEmpleado);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $identificadorEmpleado,$datosPerfil['codigoPerfil']);
						}
					}
				}
			}
			
			
			$qFeriaFaenadorEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['identificacion_empresa'],"('FERSA','FAEAI')");
			if (pg_num_rows($qFeriaFaenadorEmpresa)>0){
				$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
				while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
					$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_FISCA_MOVIL')");
					$perfilesArray=Array();
					while($fila=pg_fetch_assoc($qGrupoPerfiles)){
						$perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
					}
					if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $identificadorEmpleado))==0){
						$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $identificadorEmpleado,$filaAplicacion['codificacion_aplicacion']);
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $identificadorEmpleado);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $identificadorEmpleado,$datosPerfil['codigoPerfil']);
						}
					}else{
						foreach( $perfilesArray as $datosPerfil){
							$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $identificadorEmpleado);
							if (pg_num_rows($qPerfil) == 0)
								$cgap->guardarGestionPerfil($conexion, $identificadorEmpleado,$datosPerfil['codigoPerfil']);
						}
					}
				}
			}
		}
		
		$cee-> actualizarEmpleadoEmpresa($conexion, $datos['id_empleado'], $datos['estado']);	
		
		$conexion->ejecutarConsulta("commit;");
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		
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