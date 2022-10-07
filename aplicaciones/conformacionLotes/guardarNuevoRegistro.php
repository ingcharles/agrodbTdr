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

		$codigo = htmlspecialchars($_POST['codigoIngreso'], ENT_NOQUOTES, 'UTF-8');
		$producto = htmlspecialchars($_POST['productos'], ENT_NOQUOTES, 'UTF-8');
		$nproducto = explode(" -&gt;",htmlspecialchars($_POST['nproducto'], ENT_NOQUOTES, 'UTF-8'));
		$proveedor = htmlspecialchars($_POST['proveedores'], ENT_NOQUOTES, 'UTF-8');
		$nproveedor = htmlspecialchars($_POST['nproveedor'], ENT_NOQUOTES, 'UTF-8');
		$variedad = htmlspecialchars($_POST['variedad'], ENT_NOQUOTES, 'UTF-8');
		$nvariedad = htmlspecialchars($_POST['nvariedad'], ENT_NOQUOTES, 'UTF-8');
		$cantidad = htmlspecialchars($_POST['cantidad'], ENT_NOQUOTES, 'UTF-8');
		$area = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');
		$nombreArea = htmlspecialchars($_POST['nombreArea'], ENT_NOQUOTES, 'UTF-8');
		$sitio = htmlspecialchars($_POST['sitio'], ENT_NOQUOTES, 'UTF-8');
		$NombreSitio = htmlspecialchars($_POST['nombreSitio'], ENT_NOQUOTES, 'UTF-8');
		$idRegistro = htmlspecialchars($_POST['idRegistro'], ENT_NOQUOTES, 'UTF-8');		
		$unidad= $_POST['unidad'];
		$codigoUnidad= $_POST['codigoUnidad'];
		$nUnidad= $_POST['nUnidad'];
		$operador= $_POST['usuario'];
		$opcion = $_POST['opcion'];
		$fecha = $_POST['nFecha'];
		$areaProveedor = htmlspecialchars($_POST['nAreaProveedor'], ENT_NOQUOTES, 'UTF-8');
		$idAreaProveedor = htmlspecialchars($_POST['areaProveedor'], ENT_NOQUOTES, 'UTF-8');
		
		$nuevaCantidad = $_POST['nuevaCantidad'];
		$nuevoCodigo= $_POST['nuevoCodigo'];
		
		$caracteristica = $_POST['elCaracteristica'];
		$idElemento = $_POST['idElemento'];
		
		
		$conexion->ejecutarConsulta("begin;");
		$id=pg_fetch_row($cl->guardarRegistroNuevo($conexion, $codigo,$operador, $producto, $nproducto[0],$proveedor,$nproveedor,$variedad,$nvariedad,$cantidad,$area,$nombreArea,$sitio,$NombreSitio,$unidad,$codigoUnidad,$areaProveedor,$idAreaProveedor));
		
		$res=$ca->obtenerFormulario($conexion, "nuevoProductoProveedor");
		$fila=pg_fetch_assoc($res);
		
		for($i=0; $i< count($caracteristica);$i++){
		    $ca->guardarCaracteristicaRegistro($conexion, $id[0],$idElemento[$i], $caracteristica[$i],$fila['id_formulario']);		   
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