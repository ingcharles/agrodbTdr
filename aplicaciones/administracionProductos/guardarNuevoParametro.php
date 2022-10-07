<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cl = new ControladorLotes();
	
	try {
		
		$producto= $_POST['cbProducto'];
		$area = $_POST['cbAreaRequrida'];
		$proveedores = $_POST['cbProveedores'];
		$areaProveedor= $_POST['cbAreasPorProveedor'];
		$operacion= $_POST['idOperacion'];
		$codigo= $_POST['codigo'];

		
		$conexion->ejecutarConsulta("begin;");
		
		$valor=pg_fetch_row($cl->obtenerParametroxIDProducto($conexion, $producto));
		
		if($valor==0){
    		$cl->guardarParametros($conexion,$area,$proveedores,$areaProveedor,$producto);
    		
        		$guardarDetalle="";
        		$cont=0;
        		$condicion=",null,";    		
        		for($i=0; $i<count($codigo);$i++){    		    
        		    if($cont>=1){
        		        $condicion=",'and',";
        		        $bandera=1;    		        
        		    }
        		    $cont+=1;    		    
        		    $guardarDetalle.= "('".$producto ."','".$codigo[$i]."'".$condicion.$operacion[$i]."),";
        		}
        		
        		$trim = rtrim($guardarDetalle,",");
        		$cl->guardarOperaciones($conexion, $trim, $bandera);    		
    		
    		$mensaje['estado'] = 'exito';
    		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente.";
		} else{
		    $mensaje['estado'] = 'error';
		    $mensaje['mensaje'] = "El producto seleccionado ya posee parametrización de Conformación de Lotes.";
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