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

	$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreProducto = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreCientifico = htmlspecialchars ($_POST['nombreCientifico'],ENT_NOQUOTES,'UTF-8');
	$codigoProducto = htmlspecialchars ($_POST['codigoProducto'],ENT_NOQUOTES,'UTF-8');
	$partidaArancelaria = htmlspecialchars ($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$identificadorModificacion = $_POST['identificadorModificacion'];
	
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	
	$composicion = htmlspecialchars ($_POST['composicion'],ENT_NOQUOTES,'UTF-8');
	$formulacion = htmlspecialchars ($_POST['formulacion'],ENT_NOQUOTES,'UTF-8');
	
	$partidaOriginal = htmlspecialchars($_POST['partidaOriginal'], ENT_NOQUOTES, 'UTF-8');
	
	$unidadMedida = htmlspecialchars ($_POST['unidadMedida'],ENT_NOQUOTES,'UTF-8');
	
	$subTipoProducto = htmlspecialchars ($_POST['subTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$subTipoInicial = htmlspecialchars ($_POST['subTipoInicial'],ENT_NOQUOTES,'UTF-8');
	
	$trazabilidad = $_POST['trazabilidad'];
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	$movilizacion = $_POST['movilizacion'];
	
	$programa = htmlspecialchars ($_POST['pertenecePrograma'],ENT_NOQUOTES,'UTF-8');
	
	$clasificacionProductoSV = htmlspecialchars (trim($_POST['clasificacionProductoSV']),ENT_NOQUOTES,'UTF-8');
	$numPiezas = htmlspecialchars ($_POST['numPiezas'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		
		if($partidaOriginal != $partidaArancelaria){
			
			$qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
			$codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
		}
		
		if($subTipoInicial  != $subTipoProducto){
			$cr->actualizarIDProductosubtipo($conexion, $idProducto, $subTipoProducto);
		}
		
		$opTrazabilidad= "NO";
		if($trazabilidad=="SI"){
			$opTrazabilidad="SI";
		} else{
			$opTrazabilidad="NO";
		}
		
		$opMovilizacion= "NO";
		if($movilizacion=="SI"){
		    $opMovilizacion= "SI";
		} else{
		    $opMovilizacion="NO";
		}
		
		$cr->actualizarProducto ($conexion, $idProducto, $nombreProducto, $nombreCientifico, $codigoProducto,$partidaArancelaria, $archivo, $unidadMedida, $programa, $opTrazabilidad, $opMovilizacion, $identificadorModificacion,1,$numPiezas);
		
		if($area == 'SV' && $clasificacionProductoSV != ''){
		    $cr->actualizarClasificacionProducto($conexion, $idProducto, $clasificacionProductoSV);
		}

		if($area == 'IAP' || $area == 'IAV' || $area == 'IAF' || $area == 'IAPA'){
			$cr->guardarProductoInocuidadTMP($conexion, $idProducto);
			$cr->actualizarProductoInocuidad($conexion, $idProducto, $composicion, $formulacion);
		}
		
		/*AUDOTORIA*/
			
		$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
		$transaccion = pg_fetch_assoc($qTransaccion);
			
		if($transaccion['id_transaccion'] == ''){
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
		}
			
		$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el producto por '.$nombreProducto);
		
		/*FIN AUDITORIA*/
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

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