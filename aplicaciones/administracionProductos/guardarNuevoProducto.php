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
	
	$idTipoProducto = htmlspecialchars ($_POST['idTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$idSubtipoProducto = htmlspecialchars ($_POST['idSubtipoProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreProducto = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreCientifico = htmlspecialchars ($_POST['nombreCientifico'],ENT_NOQUOTES,'UTF-8');
	//$codigoProducto = htmlspecialchars ($_POST['codigoProducto'],ENT_NOQUOTES,'UTF-8');
	//$subcodigoProducto = htmlspecialchars ($_POST['subcodigoProducto'],ENT_NOQUOTES,'UTF-8');
	$partidaArancelaria = htmlspecialchars ($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$composicion = htmlspecialchars ($_POST['composicion'],ENT_NOQUOTES,'UTF-8');
	$formulacion = htmlspecialchars ($_POST['formulacion'],ENT_NOQUOTES,'UTF-8');
	$unidadMedida = htmlspecialchars ($_POST['unidadMedida'],ENT_NOQUOTES,'UTF-8');
	$trazabilidad = $_POST['trazabilidad'];
	$identificadorCreacion = $_POST['identificadorCreacion'];
	$movilizacion = $_POST['movilizacion'];
	$numPiezas = htmlspecialchars ($_POST['numPiezas'],ENT_NOQUOTES,'UTF-8');
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	$clasificacionProductoSV = htmlspecialchars (trim($_POST['clasificacionProductoSV']),ENT_NOQUOTES,'UTF-8');

	$programa = htmlspecialchars ($_POST['pertenecePrograma'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$producto = $cc->buscarProductoXNombre($conexion, $idSubtipoProducto, $nombreProducto);
		
		if(pg_num_rows($producto) == 0){
			if($partidaArancelaria != '' || $partidaArancelaria != 0){
				$qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
				$codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
			}else{
				$codigoProducto = 0;
			}
			
			$opTrazabilidad= "NO";
			if($trazabilidad=="SI"){
				$opTrazabilidad= "SI";
			} else{
				$opTrazabilidad="NO";
			}
			
			$opMovilizacion= "NO";
			if($movilizacion=="SI"){
			    $opMovilizacion= "SI";
			} else{
			    $opMovilizacion="NO";
			}
			
			$idProducto = pg_fetch_row($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto, $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida, $programa, $opTrazabilidad, $identificadorCreacion, $opMovilizacion, $numPiezas));
			
			if($area == 'SV' && $clasificacionProductoSV != ''){
			    $cr->actualizarClasificacionProducto($conexion, $idProducto[0], $clasificacionProductoSV);
			}
			
			if($area == 'IAP' || $area == 'IAV' || $area == 'IAF' || $area == 'IAPA'){
				$cr->guardarProductoInocuidad($conexion, $idProducto[0], $composicion, $formulacion);
			}
			
			
			/**AUDITORIA***/
			
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion,$idProducto[0] , pg_fetch_result($qLog, 0, 'id_log'));
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el producto '.$nombreProducto.' del subtipo con código '.$idSubtipoProducto.' con la partida '.$partidaArancelaria);
			
			/**FIN AUDITORIA***/
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaProducto($idProducto[0], $nombreProducto, $idSubtipoProducto,$area, 'administracionProductos');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El producto seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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