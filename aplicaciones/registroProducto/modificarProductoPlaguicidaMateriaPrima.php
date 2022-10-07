<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

function reemplazarCaracteres($cadena){
	$cadena = str_replace('á', 'a', $cadena);
	$cadena = str_replace('é', 'e', $cadena);
	$cadena = str_replace('í', 'i', $cadena);
	$cadena = str_replace('ó', 'o', $cadena);
	$cadena = str_replace('ú', 'u', $cadena);
	$cadena = str_replace('ñ', 'n', $cadena);
	$cadena = strtoupper(str_replace(' ', '', $cadena));

	return $cadena;
}

try{
    $idSubtipoProducto = htmlspecialchars ($_POST['idSubtipoProducto'],ENT_NOQUOTES,'UTF-8');
    $areaSubProducto = htmlspecialchars ($_POST['areaSubProducto'],ENT_NOQUOTES,'UTF-8');    
    
	$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreSubtipoProducto = htmlspecialchars ($_POST['nombreSubtipoProducto'],ENT_NOQUOTES,'UTF-8');
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	
	$nombreProducto = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
	
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$identificadorModificacion = $_POST['identificadorModificacion'];	
	
	$estabilidad = htmlspecialchars ($_POST['estabilidad'],ENT_NOQUOTES,'UTF-8');
	$idformulacion = htmlspecialchars ($_POST['formulacion'],ENT_NOQUOTES,'UTF-8');
	$nombreFormulacion = htmlspecialchars ($_POST['nombreFormulacion'],ENT_NOQUOTES,'UTF-8');
	
	$numeroRegistro = htmlspecialchars ($_POST['numeroRegistro'],ENT_NOQUOTES,'UTF-8');
	
	$unidadMedidaDosis = htmlspecialchars ($_POST['unidadMedidaDosis'],ENT_NOQUOTES,'UTF-8');
	$idCategoriaToxicologica = htmlspecialchars ($_POST['caToxicologica'],ENT_NOQUOTES,'UTF-8');
	$categoriaToxicologica = htmlspecialchars ($_POST['nombreCategoria'],ENT_NOQUOTES,'UTF-8');
	$periodoReingreso = htmlspecialchars ($_POST['periodoReingreso'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['status'],ENT_NOQUOTES,'UTF-8');
	$observacion = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$fechaRegistro = htmlspecialchars ($_POST['fecha_registro'],ENT_NOQUOTES,'UTF-8');
	$fechaRevaluacion = htmlspecialchars ($_POST['fecha_revaluacion'],ENT_NOQUOTES,'UTF-8');
	$idDeclaracionVenta = htmlspecialchars ($_POST['idDeclaracionVenta'],ENT_NOQUOTES,'UTF-8');
	$declaracionVenta = htmlspecialchars ($_POST['declaracionVenta'],ENT_NOQUOTES,'UTF-8');

    $subTipoProducto = htmlspecialchars ($_POST['subTipoProducto'],ENT_NOQUOTES,'UTF-8');
    $subTipoInicial = htmlspecialchars ($_POST['subTipoInicial'],ENT_NOQUOTES,'UTF-8');
	
	$empresa = htmlspecialchars ($_POST['empresa'],ENT_NOQUOTES,'UTF-8');
	$razonSocial = htmlspecialchars ($_POST['razonSocial'],ENT_NOQUOTES,'UTF-8');
    
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	if ($idCategoriaToxicologica == ''){
	    $idCategoriaToxicologica = 0;
	}
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$programa = 'NO';
		$trazabilidad = 'NO';
		$movilizacion = 'NO';
		
		$codigoProducto = 0;
		$nombreCientifico = null;
		$partidaArancelaria = null;
		$unidadMedida = null;
		
		$dosis = null;
		$unidadMedidaDosis = null;
		$periodoCarencia = null;

		$categoria = reemplazarCaracteres($numeroRegistro);		
		

				

    		
    		$cr->guardarProductoInocuidadTMP($conexion, $idProducto);
    		
    		$cr->actualizarProducto ($conexion, $idProducto, $nombreProducto, $nombreCientifico, $codigoProducto,$partidaArancelaria, $archivo, $unidadMedida, $programa, 
    		                                      $trazabilidad, $movilizacion, $identificadorModificacion, $estado);
    		
    		if ($idCategoriaToxicologica == 0){
    		    $idCategoriaToxicologica = 0;
    		}
    		
    		if ($idformulacion == 0){
    		    $idformulacion = 0;
    		}
    		
    		if ($idDeclaracionVenta == 0){
    		    $idDeclaracionVenta = 'null';
    		    $declaracionVenta = null;
    		}
    		
    		$cr->actualizarProductoInocuidad($conexion, $idProducto, $idformulacion, $nombreFormulacion, $numeroRegistro, $dosis, $periodoCarencia, $periodoReingreso, 
    		                                              $observacion, $unidadMedidaDosis, $idCategoriaToxicologica, $categoriaToxicologica, $fechaRegistro, $idDeclaracionVenta, $declaracionVenta, 
    		                                              $empresa, $estabilidad, $razonSocial);
    		
    		if($fechaRevaluacion != ''){
    			$cr -> actualizarFechaReevalacionPI($conexion,$idProducto,$fechaRevaluacion);
    		}
    		
    		/*AUDITORIA*/
    			
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
