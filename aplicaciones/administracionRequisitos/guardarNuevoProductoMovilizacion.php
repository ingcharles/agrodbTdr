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
	
	$producto = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
	$identificadorCreacionRequisitoComercio = $_SESSION['usuario'];
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$pais = pg_fetch_result($cc->obtenerIdLocalizacion($conexion, 'Ecuador', 0), 0, 'id_localizacion');	
		$nombrePais = pg_fetch_result($cc->obtenerNombreLocalizacion($conexion, $pais), 0, 'nombre');
		$tipo = pg_fetch_result($cc->obtenerAreaProductos($conexion, $producto), 0, 'id_area');
		$nombreProducto = pg_fetch_result($cc->obtenerNombreProducto($conexion, $producto), 0, 'nombre_comun');				
		
		
		if(pg_num_rows($cr->buscarProductoPais($conexion, $producto, $pais))==0){

			$idRequisitoComercio = pg_fetch_row($cr -> guardarRequisitoComercio($conexion, $tipo, $producto, $nombreProducto, $pais, $nombrePais, $identificadorCreacionRequisitoComercio));
			
			
			/**AUDITORIA***/
				
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion,$idRequisitoComercio[0] , pg_fetch_result($qLog, 0, 'id_log'));
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el requisito de comercialización con el producto '.$nombreProducto.' código '.$producto.' al país '.$nombrePais.' código '.$pais);
		
			/**FIN AUDITORIA***/
			

			///////////////REQUISITO
			
			$tipoRequisito = htmlspecialchars ($_POST['tipoRequisito'],ENT_NOQUOTES,'UTF-8');
			$requisito = htmlspecialchars ($_POST['requisito'],ENT_NOQUOTES,'UTF-8');
			$nombreRequisito = htmlspecialchars ($_POST['nombreRequisito'],ENT_NOQUOTES,'UTF-8');
		
			
    			if(pg_num_rows($cr->buscarPaisRequisito($conexion, $idRequisitoComercio[0], $requisito, $tipoRequisito)) == 0){
    			    
    			    $requisitoAsignado = pg_fetch_row($cr->guardarNuevoRequisitoAsignado($conexion, $idRequisitoComercio[0], $requisito, $tipoRequisito, 'activo', $identificadorCreacionRequisitoComercio));			    
    			    
    			    /*AUDITORIA*/
    			    
    			    $qTransaccion = $ca -> buscarTransaccion($conexion, $idRequisitoComercio[0], $_SESSION['idAplicacion']);
    			    $transaccion = pg_fetch_assoc($qTransaccion);
    			    
    			    if($transaccion['id_transaccion'] == ''){
    			        $qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
    			        $qTransaccion = $ca ->guardarTransaccion($conexion, $idRequisitoComercio[0], pg_fetch_result($qLog, 0, 'id_log'));
    			    }
    			    
    			    $ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asignado el requisito de '.$tipoRequisito.' '.$nombreRequisito.' con código '.$requisito);
    			    
    			    /*FIN AUDITORIA*/
    			    
    			    
    			    $mensaje['estado'] = 'exito';
    			    $mensaje['mensaje'] = $cr->imprimirLineaRequisito($idRequisitoComercio[0], $requisito, $nombreRequisito, $tipoRequisito, 'activo');
    			}else{
    			    $mensaje['mensaje'] = 'El requisito elegido ya ha sido asignado.';
    			}
    			
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