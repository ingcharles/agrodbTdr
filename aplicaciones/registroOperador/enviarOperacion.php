<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

	try{
	
	$datos = array('estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8'),
				   'idSolicitud' => htmlspecialchars ($_POST['idSolicitud'],ENT_NOQUOTES,'UTF-8'),
				   'operador' => htmlspecialchars ($_POST['operador'],ENT_NOQUOTES,'UTF-8'),
					'tipoOperacion' => htmlspecialchars ($_POST['tipoOperacion'],ENT_NOQUOTES,'UTF-8'));

	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
	
		if ($datos['tipoOperacion']=='Importador' || $datos['tipoOperacion']=='Exportador' || $datos['tipoOperacion']=='Comercializador'){
			$res = pg_num_rows($cr->listarProveedoresOperador($conexion, $datos['idSolicitud']));
			
			if ($res>0){
				$cr->enviarOperacion($conexion, $datos['idSolicitud'], $datos['estado']);
		
				$qAreas = $cr->obtenerAreasOperacion($conexion, $datos['idSolicitud']);
				
				foreach ($qAreas as $areas){
					$cr->enviarAreas($conexion, $areas['idArea'], $datos['estado']);
				}
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'La solicitud se ha enviado satisfactoriamente';
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Debe ingresar por lo menos un proveedor.';
			}
		}else{
			$cr->enviarOperacion($conexion, $datos['idSolicitud'], $datos['estado']);
			
			$qAreas = $cr->obtenerAreasOperacion($conexion, $datos['idSolicitud']);
			
			foreach ($qAreas as $areas){
				$cr->enviarAreas($conexion, $areas['idArea'], $datos['estado']);
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La solicitud se ha enviado satisfactoriamente';
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