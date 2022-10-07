<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';


$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion();
	$va = new ControladorVacunacion();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	$ca= new ControladorAplicaciones();
	$cu= new ControladorUsuarios();
	$cee = new ControladorEmpleadoEmpresa();

	$datos = array('id_rol_empleado' => htmlspecialchars ($_POST['idRolEmpleado'],ENT_NOQUOTES,'UTF-8'),
			'estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8'),
			'usuario_modificacion' => htmlspecialchars ($_SESSION['usuario'],ENT_NOQUOTES,'UTF-8'),
			'identificacion_empresa' => htmlspecialchars ($_SESSION['usuario'],ENT_NOQUOTES,'UTF-8')
	);
	try {

		$conexion->ejecutarConsulta("begin;");
		$identificadorEmpleado=pg_fetch_result($va->abrirRolEmpleado($conexion, $datos['id_rol_empleado']), 0, 'identificador_empleado');
		$qTraspatioEmpresa=$cee->obtenerOperadorEmpresa($conexion, $datos['identificacion_empresa'],"('OPTSA')");

		if($datos['estado']=='inactivo'){
			$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA','PRG_OPERADORMASI', 'PRG_CATAS_PRODU')");
			while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
				$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_VACUN','PFL_FISCA_VACUN','PFL_INCRI_MASIV', 'PFL_ADM_ACT_PORC')");
				while($filaPerfil=pg_fetch_assoc($qGrupoPerfiles)){
					$cu->eliminarPerfilUsuario($conexion,$identificadorEmpleado , $filaPerfil['id_perfil']);
				}
				if($filaAplicacion['codificacion_aplicacion'] != 'PRG_CATAS_PRODU'){
				    $cu->eliminarAplicacionUsuario($conexion, $identificadorEmpleado, $filaAplicacion['id_aplicacion']);
				}
			}
		}

		if($datos['estado']=='activo'){
			$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA')");
			if (pg_num_rows($qTraspatioEmpresa)>0){
				$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA','PRG_OPERADORMASI')");
			}
			while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
				$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_VACUN')");
				if (pg_num_rows($qTraspatioEmpresa)>0){
					$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'],"('PFL_ADMIN_VACUN','PFL_INCRI_MASIV')");
				}
				$perfilesArray=Array();
				while($fila=pg_fetch_assoc($qGrupoPerfiles)){
					$perfilesArray[]=array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
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
						$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $identificadorEmpleado);
						if (pg_num_rows($qPerfil) == 0)
							$cgap->guardarGestionPerfil($conexion,  $identificadorEmpleado,$datosPerfil['codigoPerfil']);
					}
				}
			}

		}
		$va-> actualizarRolEmpleado($conexion, $datos['id_rol_empleado'], $datos['estado'],$datos['usuario_modificacion']);

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