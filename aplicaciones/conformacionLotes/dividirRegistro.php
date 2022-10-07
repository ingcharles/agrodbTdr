<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cl = new ControladorLotes();
	$ca = new ControladorAdministrarCaracteristicas();
	try {
		
		$producto = htmlspecialchars($_POST['productos'], ENT_NOQUOTES, 'UTF-8');
		$nproducto = htmlspecialchars($_POST['nproducto'], ENT_NOQUOTES, 'UTF-8');
		$proveedor = htmlspecialchars($_POST['proveedores'], ENT_NOQUOTES, 'UTF-8');
		$nproveedor = htmlspecialchars($_POST['nproveedor'], ENT_NOQUOTES, 'UTF-8');
		$variedad = htmlspecialchars($_POST['variedad'], ENT_NOQUOTES, 'UTF-8');
		$nvariedad = htmlspecialchars($_POST['nvariedad'], ENT_NOQUOTES, 'UTF-8');
		$idRegistro = htmlspecialchars($_POST['idRegistro'], ENT_NOQUOTES, 'UTF-8');
		$unidad= $_POST['unidad'];
		$nUnidad= $_POST['nUnidad'];
		$operador= $_POST['usuario'];
		$fecha = $_POST['nFecha'];
		$nuevaCantidad = $_POST['nuevaCantidad'];
		$nuevoCodigo= $_POST['nuevoCodigo'];
		$areaProvedor=$_POST['areaProveedor'];
		$idAreaProvedor=$_POST['idAreaProveedor'];
			
		$conexion->ejecutarConsulta("begin;");
		
		$formulario=pg_fetch_assoc($ca->obtenerFormulario($conexion, "nuevoProductoProveedor"));
		
		for($i=0; $i< count($nuevaCantidad);$i++){
			if($i==0){
				$cl->actualizarRegistro($conexion,$idRegistro, $nuevaCantidad[$i],$unidad,$nUnidad);
			} else{
			    $registro=pg_fetch_row($cl->guardarRegistroDivision($conexion, $nuevoCodigo[$i],$operador, $producto, $nproducto,$proveedor,$nproveedor,$variedad,$nvariedad,$nuevaCantidad[$i],$fecha,$unidad,$nUnidad,$areaProvedor,$idAreaProvedor));				
				
			    $fila=$ca->obtenerCaracteristicasXregistroYformulario($conexion, $idRegistro, $formulario['id_formulario']);
			    $cont=0;
			    while($caracteristica = pg_fetch_assoc($fila)){
			        $con+=1;
			        $ca->guardarCaracteristicaRegistro($conexion, $registro[0],$caracteristica['id_elemento'], $caracteristica['id_item'],$caracteristica['id_formulario']);
			    }
			}
		}			
		
		$conexion->ejecutarConsulta("commit;");		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";

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