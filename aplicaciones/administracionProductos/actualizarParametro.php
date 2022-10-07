<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';

$producto= $_POST['idProducto'];
$area = $_POST['cbAreaRequrida'];
$proveedores = $_POST['cbProveedores'];
$areaProveedor= $_POST['cbAreasPorProveedor'];
$idParametro = $_POST['idParametro'];
$operacion= $_POST['idOperacion'];
$codigo= $_POST['codigo'];


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new ControladorLotes();
	
	try{		
		$conexion->ejecutarConsulta("begin;");
		$cac->actualizarParametro($conexion,$area,$proveedores,$areaProveedor,$idParametro);
		
		    $cac->eliminarOperaciones($conexion, $producto);
		    
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
		    $cac->guardarOperaciones($conexion, $trim, $bandera);
		
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Parámetrización actualizada con éxito";

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