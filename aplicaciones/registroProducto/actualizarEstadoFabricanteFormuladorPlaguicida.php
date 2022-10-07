<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAuditoria.php';
require_once '../../clases/ControladorRequisitos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
    $estado = htmlspecialchars ($_POST['estadoRequisito'],ENT_NOQUOTES,'UTF-8');
    $idFabricanteFormulador = htmlspecialchars($_POST['idFabricanteFormulador'],ENT_NOQUOTES,'UTF-8');
    $nombreFabricanteFormulador = htmlspecialchars($_POST['nombreFabricanteFormulador'],ENT_NOQUOTES,'UTF-8');
    $idProducto = htmlspecialchars($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
	$identificador = $_SESSION['usuario'];
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$ca = new ControladorAuditoria();
		$cr = new ControladorRequisitos();
		
		$cr->actualizarEstadoFabricanteFormulador($conexion, $idFabricanteFormulador, $estado, $identificador);
		
		/*AUDITORIA*/
			
		$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
		$transaccion = pg_fetch_assoc($qTransaccion);
			
		if($transaccion['id_transaccion'] == ''){
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
		}
			
		$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el estado del fabricante/formulador '.$nombreFabricanteFormulador.' a '.$estado);
		
		/*FIN AUDITORIA*/
		
		
		//Verificar todos los registros hijos
		//Buscar presentaciones por código complementario y suplementario
		$manufacturadores = $cr->buscarManufacturaforesXFabricanteFormulador($conexion,$idFabricanteFormulador);
		
		if(pg_num_rows($manufacturadores) > 0){
		    while($manufacturador = pg_fetch_assoc($manufacturadores)){
		        //Actualizar estado de Manufacturadores
		        $cr->actualizarEstadoManufacturador($conexion, $manufacturador['id_manufacturador'], $estado, $identificador);
		        
		        /*AUDITORIA*/
		        
		        $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
		        $transaccion = pg_fetch_assoc($qTransaccion);
		        
		        if($transaccion['id_transaccion'] == ''){
		            $qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
		            $qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
		        }
		        
		        $ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el estado del manufacturador con ID' .$manufacturador['id_manufacturador'].' a '.$estado);
		        
		        /*FIN AUDITORIA*/
		    }
		}
			
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idFabricanteFormulador;
		
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