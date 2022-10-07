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
	$conexion = new Conexion ();
	$va = new ControladorVacunacion ();
	$cgap= new ControladorGestionAplicacionesPerfiles();
	$ca= new ControladorAplicaciones();
	$cu= new ControladorUsuarios();
	$cee = new ControladorEmpleadoEmpresa();

	$datos = array (
			'rol' => htmlspecialchars ( $_POST ['rol'], ENT_NOQUOTES, 'UTF-8' ),
			'idEmpleado' => htmlspecialchars ( $_POST ['empleado'], ENT_NOQUOTES, 'UTF-8' ),
			'identificadorEmpleado' => htmlspecialchars ($_POST ['identificacionEmpleado'], ENT_NOQUOTES, 'UTF-8' ),
			'estado' => 'activo'
	);

	try {
		if(pg_num_rows($va->consultarRolEmpleado($conexion, $datos['rol'], $datos['idEmpleado']))==0){
			$conexion->ejecutarConsulta("begin;");
			$va->guardarNuevoRolEmpleado($conexion, $datos['rol'], $datos['idEmpleado'], $datos['estado']);
			$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA')");
				
			$identificadorEmpresa = pg_fetch_result($cee->obtenerIdentificadorEmpresaEmpleado($conexion, $datos['idEmpleado']),0,'identificador_empresa');
			$qTraspatioEmpresa=$cee->obtenerOperadorEmpresa($conexion, $identificadorEmpresa,"('OPTSA')");
			if (pg_num_rows($qTraspatioEmpresa)>0){
				$qGrupoAplicacion=$cgap->obtenerGrupoAplicacion($conexion, "('PRG_VACUN_ANIMA','PRG_OPERADORMASI', 'PRG_CATAS_PRODU')");
			}
				
			while($filaAplicacion=pg_fetch_assoc($qGrupoAplicacion)){
				$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_VACUN')");
				if (pg_num_rows($qTraspatioEmpresa)>0){
					$qGrupoPerfiles=$cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_ADMIN_VACUN','PFL_INCRI_MASIV', 'PFL_ADM_ACT_PORC')");
				}

				$perfilesArray=Array();
				while($fila=pg_fetch_assoc($qGrupoPerfiles)){
					$perfilesArray[]=array(idPerfil=>$fila['id_perfil'],codigoPerfil=>$fila['codificacion_perfil']);
				}
				if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['identificadorEmpleado']))==0){
					$qAplicacionVacunacion=$cgap->guardarGestionAplicacion($conexion, $datos['identificadorEmpleado'],$filaAplicacion['codificacion_aplicacion']);
					foreach( $perfilesArray as $datosPerfil){
						$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'],  $datos['identificadorEmpleado']);
						if (pg_num_rows($qPerfil) == 0)
							$cgap->guardarGestionPerfil($conexion, $datos['identificadorEmpleado'],$datosPerfil['codigoPerfil']);
					}
				}else{
					foreach( $perfilesArray as $datosPerfil){
						$qPerfil = $cu-> obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['identificadorEmpleado']);
						if (pg_num_rows($qPerfil) == 0)
							$cgap->guardarGestionPerfil($conexion, $datos['identificadorEmpleado'],$datosPerfil['codigoPerfil']);
					}
				}
			}
				
			$conexion->ejecutarConsulta("commit;");
			$mensaje ['estado'] = 'exito';
			$mensaje ['mensaje'] = 'Los datos han sido guardado satisfactoriamente';
		}else{
			$mensaje ['estado'] = 'error';
			$mensaje ['mensaje'] = 'El rol para el empleado ya ha sido registrado';
		}
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