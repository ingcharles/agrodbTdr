<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();

	try {
		$nombre= htmlspecialchars ($_POST['txtNombreCatalogo'],ENT_NOQUOTES,'UTF-8');
		$item = $_POST['dtxtItem'];
		$descripcion = $_POST['dtxtDescripcion'];
		$codigo = $_POST['txtCodigo'];

		$conexion->ejecutarConsulta("begin;");
		
		$valor=pg_fetch_row($cac->obtenerCatalogoXnombre($conexion, $nombre));
		
		if($valor==0){
    		
    		$valor2=pg_fetch_row($cac->obtenerCodigoCatalogo($conexion, $codigo,$idCatalogo));
    		
    		if($valor2==0){
    		    $idCatalogo=pg_fetch_row($cac->guardarCatalogo($conexion,$nombre,$codigo));
    		    
    		    $guardarDetalle="";
    		    for($i=0; $i<count($item);$i++){
    		        $guardarDetalle.= "('".$item[$i] ."','".$descripcion[$i] ."',1,".$idCatalogo[0]."),";
    		    }
    		    
    		    $trim = rtrim($guardarDetalle,",");
    		    $cac->guardarDetalle($conexion,$trim);
    		    
    		    $mensaje['estado'] = 'exito';
    		    $mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
    		} else{
    		    $mensaje['estado'] = 'fallo';
    		    $mensaje['mensaje'] = "Ya se encuentra un catálogo registrado con el código ($codigo)";
    		}
		
		} else{
		    $mensaje['estado'] = 'vacio';
		    $mensaje['mensaje'] = "Ya se encuentra un catálogo registrado con el nombre ($nombre)";
		}
		
		$conexion->ejecutarConsulta("commit;"); 

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