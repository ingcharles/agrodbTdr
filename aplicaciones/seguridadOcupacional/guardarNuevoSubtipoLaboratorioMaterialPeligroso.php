<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$nombreSubtipo = htmlspecialchars ($_POST['nombreSubtipo'],ENT_NOQUOTES,'UTF-8');
	$idLaboratorio = htmlspecialchars ($_POST['idLaboratorioMaterialPeligroso'],ENT_NOQUOTES,'UTF-8');

	
	try {
		$conexion = new Conexion ();
		$so = new ControladorSeguridadOcupacional ();
		
		$laboratorio = $so->buscarSubtipoLaboratorioXNombre($conexion, $idLaboratorio, mb_strtoupper($nombreSubtipo));
		
		if(pg_num_rows($laboratorio) == 0){
			$idSubtipoLaboratorio = pg_fetch_row($so->guardarSubTipoLaboratorioMaterialPeligroso($conexion, mb_strtoupper($nombreSubtipo), $idLaboratorio));
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $so->imprimirLineaSubtipoLaboratorio($idSubtipoLaboratorio[0], mb_strtoupper($nombreSubtipo), $idLaboratorio, 'seguridadOcupacional');
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El subtipo de laboratorio ingresado ya existe dentro de esta clasificación, por favor verificar en el listado.";
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