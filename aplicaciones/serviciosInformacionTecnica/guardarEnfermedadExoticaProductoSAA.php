<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csit = new ControladorServiciosInformacionTecnica();
	$cc = new ControladorCatalogos();
	try {
		$idTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
		$idSubTipoProducto = htmlspecialchars ($_POST['subtipoProducto'],ENT_NOQUOTES,'UTF-8');
		$idProductos = $_POST['producto'];
		$idEnfermedad = $_POST['idEnfermedadC'];
		$idEnfermedadExotica = $_POST['idEnfermedadExotica'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$seleccionarProducto = true;
		$ingreso = true;
		$conexion->ejecutarConsulta("begin;");
		if(count($idProductos) != 0){
			$todosProductos = implode(',', $idProductos);
			$todosProductos = "(".rtrim($todosProductos,',').")";
			$qProducto = $csit->buscarRegistroEnfermedadExoticaProducto($conexion, $idEnfermedadExotica, $todosProductos);
			if(pg_num_rows($qProducto)!= 0){
				$ingreso = false;
				while ($fila = pg_fetch_assoc($qProducto)){
					$productosIngresados .= $fila['nombre_producto'].', ';
				}
			}
		}else{
			$seleccionarProducto = false;
		}

		if($ingreso && $seleccionarProducto){
			for($i = 0; $i < count($idProductos); $i++){
				$qProducto = $cc->obtenerNombreProducto($conexion, $idProductos[$i]);
				$idEnfermedadProducto=pg_fetch_row($csit->guardarEnfermedadesProductos($conexion, $idProductos[$i], pg_fetch_result($qProducto, 0, 'nombre_comun'), $idSubTipoProducto, $idTipoProducto, $idEnfermedadExotica,$usuarioResponsable,pg_fetch_result($qProducto, 0, 'partida_arancelaria')));
				$imprimirOperacion.= $csit->imprimirLineaEnfermedadExoticaProducto($idEnfermedadProducto[0], pg_fetch_result($qProducto, 0, 'nombre_comun'), 'activo',$usuarioResponsable, $idEnfermedadExotica, pg_fetch_result($qProducto, 0, 'partida_arancelaria'));
			}
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $imprimirOperacion;
		}else{
			$mensaje['estado'] = 'error';
			if(!$seleccionarProducto){
				$mensaje['mensaje'] = 'Seleccione al menos un producto.';
			}else{
				$mensaje['mensaje'] = 'Los productos '.trim($productosIngresados,', ').' ya han sido ingresadas previamente para la enfermedad escogida.';
			}	
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