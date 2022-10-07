<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorGestionAplicacionesPerfiles.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion();
	$va = new ControladorVacunacion();
	$cgap = new ControladorGestionAplicacionesPerfiles();
	$ca = new ControladorAplicaciones();
	$cu = new ControladorUsuarios();
	$cee = new ControladorEmpleadoEmpresa();
	$cmp = new ControladorMovilizacionProductos();

	$datos = array('id_rol_empleado' => htmlspecialchars ($_POST['idRolEmpleado'],ENT_NOQUOTES,'UTF-8'),
			'estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8'),
			'usuario_modificacion' => htmlspecialchars ($_SESSION['usuario'],ENT_NOQUOTES,'UTF-8'),
			'identificacion_empresa' => htmlspecialchars ($_SESSION['usuario'],ENT_NOQUOTES,'UTF-8')
	);
	try {

		$conexion->ejecutarConsulta("begin;");
		
		$identificadorEmpleado = pg_fetch_result($cmp->abrirRolEmpleado($conexion, $datos['id_rol_empleado']), 0, 'identificador_empleado');
		if($datos['estado'] == 'inactivo'){
		    
		    $qGrupoAplicacion = $cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
		    
		    while($filaAplicacion = pg_fetch_assoc($qGrupoAplicacion)){
		        
		        $qGrupoPerfiles = $cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], "('PFL_FISCA_MOVIL')");
		        
		        while($filaPerfil = pg_fetch_assoc($qGrupoPerfiles)){
		            $cu->eliminarPerfilUsuario($conexion, $identificadorEmpleado , $filaPerfil['id_perfil']);
		        }
		        
		    }
		    
		}
		
		if($datos['estado'] == 'activo'){
		    
		    $qOperacionesEmpresa = $cee->obtenerOperadorEmpresaOperacion($conexion, $datos['identificacion_empresa'],"('OPISA', 'FEASA', 'FERSA', 'FAEAI')");
		    
		    if (pg_num_rows($qOperacionesEmpresa) > 0){
		        
		        $perfiles = "('PFL_EMISO_MOVIL')";
		        
		        while($fila = pg_fetch_assoc($qOperacionesEmpresa)){
		            if($fila['operacion'] == 'FERSA'){
		                $perfiles = "('PFL_EMISO_MOVIL', 'PFL_FISCA_MOVIL' )";
		                break;
		            }
		        }
		        			
		        $qGrupoAplicacion = $cgap->obtenerGrupoAplicacion($conexion, "('PRG_MOVIL_PRODU')");
		    		
    			while($filaAplicacion = pg_fetch_assoc($qGrupoAplicacion)){
    				
    			    $qGrupoPerfiles = $cgap->obtenerGrupoPerfilXAplicacion($conexion, $filaAplicacion['id_aplicacion'], $perfiles);
    	            $perfilesArray = Array();
    	            
    				while($fila = pg_fetch_assoc($qGrupoPerfiles)){
    					$perfilesArray[] = array('idPerfil'=>$fila['id_perfil'],'codigoPerfil'=>$fila['codificacion_perfil']);
    				}
    				
    				if(pg_num_rows($ca->obtenerAplicacionPerfil($conexion, $filaAplicacion['id_aplicacion'] , $identificadorEmpleado)) == 0){
    					
    				    $cgap->guardarGestionAplicacion($conexion, $identificadorEmpleado, $filaAplicacion['codificacion_aplicacion']);
    					
    					foreach( $perfilesArray as $datosPerfil){
    						$qPerfil = $cu->obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $identificadorEmpleado);
    						if (pg_num_rows($qPerfil) == 0)
    							$cgap->guardarGestionPerfil($conexion, $identificadorEmpleado, $datosPerfil['codigoPerfil']);
    					}
    					
    				}else{
    					
    				    foreach( $perfilesArray as $datosPerfil){
    						$qPerfil = $cu->obtenerPerfilUsuario($conexion, $datosPerfil['idPerfil'], $identificadorEmpleado);
    						if (pg_num_rows($qPerfil) == 0)
    							$cgap->guardarGestionPerfil($conexion, $identificadorEmpleado, $datosPerfil['codigoPerfil']);
    					}
    					
    				}
    				
    			}
    			
		    }
			
		}
		
		$cmp-> actualizarRolEmpleado($conexion, $datos['id_rol_empleado'], $datos['estado'],$datos['usuario_modificacion']);

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