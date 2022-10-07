<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
    $conexion = new Conexion();
    $cro = new ControladorRegistroOperador();
    $cc = new ControladorCatalogos();
    
	$idProveedor = $_POST['idProveedor'];
	$idOperadorTipoOperacion = $_POST['idOperadorTipoOperacion'];
	$idTipoOperacion = $_POST['idTipoOperacion'];
	
	try {
	    
	    $conexion->ejecutarConsulta("begin;");
	    
	    $qProveedoresXIdOperadorTipoOperacion = $cro->obtenerProveedoresXIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion);
        
	    if(pg_num_rows($qProveedoresXIdOperadorTipoOperacion) <= 1){         
	        $mensaje['estado'] = 'error';
	        $mensaje['mensaje'] = 'No puede eliminar todos sus proveedores, debe tener registrado al menos un proveedor con un producto”.';
	    }else{	        
	                
	        $qTipoOperacion = $cc->obtenerDatosTipoOperacion($conexion, $idTipoOperacion);
	        $tipoOperacion = pg_fetch_assoc($qTipoOperacion);
	                
	        $cro->cambiarEstadoProveedor($conexion, $idProveedor, 'inactivo');	      
	        
	        switch ($tipoOperacion['codigo']){
	            
	            case 'COM':
	                
	                $qProveedor = $cro->abrirProveedoresOperador($conexion, $idProveedor);
	                $proveedor = pg_fetch_assoc($qProveedor);
	                
	                $proveedoresIdOperacion = $cro->obtenerProveedoresPorIdOperacionPorEstado($conexion, $proveedor['id_operacion'], 'activo');
	                
	                if(pg_num_rows($proveedoresIdOperacion) <= 1){
	                    $cro->actualizarEstadoOperacion($conexion, $proveedor['id_operacion'], 'noHabilitado');
	                    $cro->quitarOperacionesOrganico($conexion, $proveedor['id_operacion']);
	                }	                
	                
	            break;
	                
	        }
	        
	        $mensaje['estado'] = 'exito';
	        $mensaje['mensaje'] = $idProveedor;
	    }
	    
		$conexion->ejecutarConsulta("commit;");		
		
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