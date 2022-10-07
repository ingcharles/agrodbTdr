<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$idFormulario= htmlspecialchars($_POST['cbFormulario'], ENT_NOQUOTES, 'UTF-8');
$etiqueta=  htmlspecialchars ($_POST['txtEtiqueta'],ENT_NOQUOTES,'UTF-8');
$tipo=  htmlspecialchars ($_POST['cbTipoElemento'],ENT_NOQUOTES,'UTF-8');
$idCatalogo= htmlspecialchars ($_POST['cbCatalogo'],ENT_NOQUOTES,'UTF-8');
$idProducto = htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
$nFormulario= htmlspecialchars ($_POST['nFormulario'],ENT_NOQUOTES,'UTF-8');
$nCatalogo = htmlspecialchars ($_POST['nCatalogo'],ENT_NOQUOTES,'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new controladorAdministrarCaracteristicas();
	
	try{		
		
		$conexion->ejecutarConsulta("begin");
		
		$valor=0;
		$val1=0;
		$val2=0;
		
		
		$res= $cac->comprobarEtiqueta($conexion,$idProducto,$etiqueta,$idFormulario);
		$fila=pg_num_rows($res);
		
		if($fila>0){
		    $val1=1;
		}
		
		$res= $cac->comprobarCatalogo($conexion,$idProducto,$idCatalogo,$idFormulario);
		$fila=pg_num_rows($res);
		
		if($fila>0){
		    $val2=1;
		}
		
		if($val1==1 && $val2==0){
		    $contenido = "Ya se encuentra una característica registrada con el nombre de etiqueta ($etiqueta) para este producto con el formulario seleccionado";
		    $valor=1;
		} else if ($val1==0 && $val2==1){
		    $contenido = "Ya se encuentra una característica registrada con el catálogo y formulario seleccionados para este producto";
		    $valor=1;
		} else if ($val1==1 && $val2==1){
		    $contenido = "Ya se encuentra una característica registrada con el nombre de etiqueta ($etiqueta) con el catálogo y formulario seleccionados para este producto";
		    $valor=1;
		} else if ($val1==0 && $val2==0){
		    $contenido = "";
		    $valor=0;
		}		
		
		if($valor==0){	    		
		    $idElemento=pg_fetch_row($cac->agregarCaracteristica($conexion,$etiqueta,$tipo,$idFormulario,$idCatalogo,$idProducto));
		
		switch ($tipo){
		    case 'CB':
		        $elemento="ComboBox";
	        break;
		    case 'CH':
		        $elemento="CheckBox";
	        break;
		    case 'RB':
		        $elemento="RadioButton";
	        break;
		}
				
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $cac->imprimirElemento($idElemento[0], $idProducto, "1", $etiqueta, $nFormulario, $nCatalogo, $elemento, "activo");
		
		} else{
		    $mensaje['estado'] = 'vacio';
		    $mensaje['mensaje'] = $contenido;
		}
		
		$conexion->ejecutarConsulta("commit");

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