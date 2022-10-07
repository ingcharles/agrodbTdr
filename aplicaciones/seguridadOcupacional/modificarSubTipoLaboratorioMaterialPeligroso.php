<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

$opcion = htmlspecialchars ( $_POST['opcion'], ENT_NOQUOTES, 'UTF-8' );
	$idLaboratorioMaterialPeligroso = htmlspecialchars ( $_POST ['idLaboratorioMaterialPeligroso'], ENT_NOQUOTES, 'UTF-8' );
	$nombreLaboratorioMaterialPeligroso = htmlspecialchars ( $_POST ['nombreLaboratorioUno'], ENT_NOQUOTES, 'UTF-8' );
		

	try {
		$conexion = new Conexion ();
		$so = new ControladorSeguridadOcupacional ();
		

		$so->actualizarLaboratorioMaterialPeligroso($conexion, $idLaboratorioMaterialPeligroso, $nombreLaboratorioMaterialPeligroso);
			
		

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