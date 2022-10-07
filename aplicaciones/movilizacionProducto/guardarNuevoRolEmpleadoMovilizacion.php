<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
    
	$conexion = new Conexion ();
	$cgap = new ControladorGestionAplicacionesPerfiles();
	$ca = new ControladorAplicaciones();
	$cu = new ControladorUsuarios();
	$cee = new ControladorEmpleadoEmpresa();
	
	$cmp = new ControladorMovilizacionProductos();

	$datos = array (
			'rol' => htmlspecialchars ( $_POST ['rol'], ENT_NOQUOTES, 'UTF-8' ),
			'idEmpleado' => htmlspecialchars ( $_POST ['empleado'], ENT_NOQUOTES, 'UTF-8' ),
			'identificadorEmpleado' => htmlspecialchars ($_POST ['identificacionEmpleado'], ENT_NOQUOTES, 'UTF-8' ),
			'estado' => 'activo'
	);

	try {
	    
		if(pg_num_rows($cmp->consultarRolEmpleado($conexion, $datos['rol'], $datos['idEmpleado'])) == 0){
			
		    $conexion->ejecutarConsulta("begin;");

		    $identificadorEmpresa = pg_fetch_result($cee->obtenerIdentificadorEmpresaEmpleado($conexion, $datos['idEmpleado']), 0, 'identificador_empresa');
			
			$qOperacionesEmpresa = $cee->obtenerOperadorEmpresaOperacion($conexion, $identificadorEmpresa,"('OPISA', 'FEASA', 'FERSA', 'FAEAI')");
			
			if (pg_num_rows($qOperacionesEmpresa) > 0){
			    
			    $perfiles = "('PFL_EMISO_MOVIL')";
			    
			    while($fila = pg_fetch_assoc($qOperacionesEmpresa)){
                    if($fila['operacion'] == 'FERSA'){
                        $perfiles = "('PFL_EMISO_MOVIL', 'PFL_FISCA_MOVIL' )";
                        break;
                    }
                }
			    
			    $cmp->guardarNuevoRolEmpleado($conexion, $datos['rol'], $datos['idEmpleado'], $datos['estado']);
			    
			    $qGrupoAplicacion = $cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
			    
			    while($filaAplicacion = pg_fetch_assoc($qGrupoAplicacion)){
			        
			        $qGrupoPerfiles = $cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], $perfiles);
			        $perfilesArray = Array();
			        
			        while($fila = pg_fetch_assoc($qGrupoPerfiles)){
			            $perfilesArray[] = array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
			        }
			        
			        if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $datos['identificadorEmpleado'])) == 0){
			            
			            $cgap->guardarGestionAplicacion($conexion, $datos['identificadorEmpleado'], $filaAplicacion['codificacion_aplicacion']);
			            
			            foreach( $perfilesArray as $datosPerfil){
			                $qPerfil = $cu->obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['identificadorEmpleado']);
			                if (pg_num_rows($qPerfil) == 0)
			                    $cgap->guardarGestionPerfil($conexion, $datos['identificadorEmpleado'], $datosPerfil['codigoPerfil']);
			            }
			            
			        }else{
			            
			            foreach( $perfilesArray as $datosPerfil){
			                $qPerfil = $cu->obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $datos['identificadorEmpleado']);
			                if (pg_num_rows($qPerfil) == 0)
			                    $cgap->guardarGestionPerfil($conexion, $datos['identificadorEmpleado'], $datosPerfil['codigoPerfil']);
			            }
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