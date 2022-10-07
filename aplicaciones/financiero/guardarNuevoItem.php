<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFinanciero.php';

	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$idDocumento = ($_POST['idDocumento']);
	$concepto = htmlspecialchars ($_POST['concepto'],ENT_NOQUOTES,'UTF-8');
	$unidad = ($_POST['unidad']);
	$valor = ($_POST['valor']);
	$iva = htmlspecialchars ($_POST['iva'],ENT_NOQUOTES,'UTF-8');
	$partidaPresupuestaria = htmlspecialchars ($_POST['partidaPresupuestaria'],ENT_NOQUOTES,'UTF-8');
	$unidadMedida = htmlspecialchars ($_POST['unidadMedida'],ENT_NOQUOTES,'UTF-8');
	$idArea = htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
	$codigoPadre = htmlspecialchars ($_POST['codigoPadre'],ENT_NOQUOTES,'UTF-8');
	$subsidio = ($_POST['subsidio']);
	//INICIO EJAR
	$poseeExceso = $_POST['exceso'];
	$itemsUsuadoPara = $_POST['itemUsadoPara'];
	$idItemExceso = ($_POST['idItemExceso'] == "" ? 'NULL' : $_POST['idItemExceso']);
	$conceptoPadre = $_POST['conceptoPadre'];
	//FIN EJAR
		
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		
		$res = $cf -> generaNumeroDocumento($conexion,$idDocumento,3,$idArea);
		$documento = pg_fetch_assoc($res);
		$tmp= explode(".", $documento['numero']);
		$incremento = end($tmp)+1;
		$codigo =  $codigoPadre.'.'.str_pad($incremento, 3, "0", STR_PAD_LEFT);
		
		$idItem = pg_fetch_result($cf->guardarNuevoItem($conexion,$concepto, $unidad, $valor, $idArea, $codigo, $iva, $idDocumento ,$partidaPresupuestaria, $unidadMedida, $subsidio, $poseeExceso, $itemsUsuadoPara, $idItemExceso),0,'id_servicio');
					
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cf->imprimirLineaItem($idItem, $codigo, $concepto,'financiero',$idArea, $idDocumento, $codigoPadre, $conceptoPadre);
//		$mensaje['mensaje'] = $idArea;
			
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


