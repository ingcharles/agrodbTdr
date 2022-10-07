<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$nombreSubtipo = htmlspecialchars ($_POST['nombreSubtipo'],ENT_NOQUOTES,'UTF-8');
	$idTipoProducto = htmlspecialchars ($_POST['idTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$producto = $cc->buscarSubtipoProductoXNombre($conexion, $idTipoProducto, $nombreSubtipo);
		
		if(pg_num_rows($producto) == 0){
			$idSubtipoProducto = pg_fetch_row($cr->guardarNuevoSubtipoProducto($conexion, $nombreSubtipo, $idTipoProducto));
		
			/***********************
			 ******* AUDITORIA*****
			**********************/
			
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idSubtipoProducto[0], pg_fetch_result($qLog, 0, 'id_log'));
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el subTipo producto '.$nombreSubtipo);
			
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaSubtipoProducto($idSubtipoProducto[0], $nombreSubtipo, $idTipoProducto, $area, 'registroProducto');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El subtipo de producto seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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