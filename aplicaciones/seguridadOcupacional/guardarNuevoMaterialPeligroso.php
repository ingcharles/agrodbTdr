<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$so = new ControladorSeguridadOcupacional ();

	$opcion = htmlspecialchars ( $_POST['opcion'], ENT_NOQUOTES, 'UTF-8' );
	$idMaterialPeligroso = htmlspecialchars ( $_POST ['idMaterialPeligroso'], ENT_NOQUOTES, 'UTF-8' );
	$nombreMaterialPeligroso = htmlspecialchars ( $_POST ['nombreProductoUno'], ENT_NOQUOTES, 'UTF-8' );
	$numeroUnMaterialPeligroso = htmlspecialchars ( $_POST['numeroUnUno'], ENT_NOQUOTES, 'UTF-8' );
	$numeroCasMaterialPeligroso = htmlspecialchars ( $_POST['numeroCasUno'], ENT_NOQUOTES, 'UTF-8' );
	$idGuiaMaterialPeligroso = htmlspecialchars ( $_POST['guia'], ENT_NOQUOTES, 'UTF-8' );
	$rutaMsdsMaterialPeligroso = htmlspecialchars ( $_POST['archivo'], ENT_NOQUOTES, 'UTF-8' );
	$descripcionMaterialPeligroso = htmlspecialchars ( $_POST['descripcion'], ENT_NOQUOTES, 'UTF-8' );
		
	
	function quitar_tildes($cadena) {
		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹"," ");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}
	
	try {
		$conexion->ejecutarConsulta("begin;");
		if($opcion=='Nuevo'){
			if(pg_num_rows($so->buscarMaterialPeligrosoExiste($conexion,mb_strtoupper(quitar_tildes($nombreMaterialPeligroso))))==0){
				$idMaterialPeligros=$so->guardarMaterialPeligroso($conexion, mb_strtoupper($nombreMaterialPeligroso), $numeroUnMaterialPeligroso, $numeroCasMaterialPeligroso, $rutaMsdsMaterialPeligroso, $descripcionMaterialPeligroso,$idGuiaMaterialPeligroso);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				$mensaje['idMaterialPeligroso'] = pg_fetch_result($idMaterialPeligros, 0, 'id_material_peligroso');
			}else{
				$mensaje['mensaje'] = 'Ya existe material peligroso con nombre '.mb_strtoupper($nombreMaterialPeligroso) ;
			}	
		}

		if($opcion=='Actualizar'){
		
			$so->actualizarMaterialPeligroso($conexion, $idMaterialPeligroso, mb_strtoupper($nombreMaterialPeligroso), $numeroUnMaterialPeligroso, $numeroCasMaterialPeligroso, $rutaMsdsMaterialPeligroso, $descripcionMaterialPeligroso,$idGuiaMaterialPeligroso);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			
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
?>