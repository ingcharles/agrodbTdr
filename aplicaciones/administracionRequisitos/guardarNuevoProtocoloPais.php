<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	//$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
	$producto = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
	$pais = htmlspecialchars ($_POST['pais'],ENT_NOQUOTES,'UTF-8');
	$identificadorCreacionProtocoloComercio = $_SESSION['usuario'];
	//$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cp = new ControladorProtocolos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		//$tipo = pg_fetch_result($cc->obtenerAreaProductos($conexion, $producto), 0, 'id_area');
		
		
		$nombreProducto = pg_fetch_result($cc->obtenerNombreProducto($conexion, $producto), 0, 'nombre_comun');
		$nombrePais = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $pais), 0, 'nombre');
		
		if(pg_num_rows($cp->buscarProtocoloPais($conexion, $producto, $pais))==0){
			//echo 'hola';
			$idProtocoloComercio = pg_fetch_row($cp -> guardarProtocoloComercio($conexion, $producto, $nombreProducto, $pais, $nombrePais, $identificadorCreacionProtocoloComercio));
			
			/**AUDITORIA
				
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion,$idRequisitoComercio[0] , pg_fetch_result($qLog, 0, 'id_log'));
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el requisito de comercialización con el producto '.$nombreProducto.' código '.$producto.' al país '.$nombrePais.' código '.$pais);
				
			FIN AUDITORIA***/
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cp->imprimirLineaProtocoloPais($idProtocoloComercio[0], $nombrePais, $pais, $nombreProducto);
		}else{
			$mensaje['mensaje'] = 'El producto y país elegidos ya han sido registrados.';
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>