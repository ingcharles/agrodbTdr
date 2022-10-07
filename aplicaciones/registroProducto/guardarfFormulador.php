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

	
	$idProductoIncouidad = htmlspecialchars ($_POST['idProductoInocuidad'],ENT_NOQUOTES,'UTF-8');
	$formulador = htmlspecialchars ($_POST['formulador'],ENT_NOQUOTES,'UTF-8');
	$idPaisOrigen = htmlspecialchars ($_POST['paisOrigen'],ENT_NOQUOTES,'UTF-8');
	$nombrePaisFabricante = htmlspecialchars ($_POST['nombrePaisFabricante'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['idAreaF'],ENT_NOQUOTES,'UTF-8');
	$tipoFabricante = htmlspecialchars ($_POST['tipoFabricante'],ENT_NOQUOTES,'UTF-8');
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		if(pg_num_rows($cr->buscarPaisformuladorFabricante($conexion,$formulador, $idPaisOrigen, $idProductoIncouidad))==0){
			
			$cr -> guardarProductoInocuidadTMP($conexion, $idProductoIncouidad);
			$codigos = $cr -> guardarNuevoFabricanteFormulador($conexion, $idProductoIncouidad,$formulador,$idPaisOrigen, $nombrePaisFabricante, $tipoFabricante);
			$codigoFabricate = pg_fetch_result($codigos, 0, 'id_fabricante_formulador');
								
			/*AUDITORIA*/
				
			$qTransaccion = $ca -> buscarTransaccion($conexion, $idProductoIncouidad, $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
				
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProductoIncouidad, pg_fetch_result($qLog, 0, 'id_log'));
			}
				
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.$idProductoIncouidad.' al formulador '.$formulador);
				
			/*FIN AUDITORIA*/
		
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirfabricanteFormulador($idProductoIncouidad,$codigoFabricate,$formulador,$idPaisOrigen,$nombrePaisFabricante, $tipoFabricante, $idArea );
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El código Fabricante/formulador ya ha sido ingresado.';
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