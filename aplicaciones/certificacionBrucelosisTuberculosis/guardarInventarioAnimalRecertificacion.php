<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorBrucelosisTuberculosis.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';	
	
	$ruta='certificacionBrucelosisTuberculosis';
	
	try{
	
		$identificador = $_SESSION['usuario'];
	
		$idRecertificacionBT = htmlspecialchars ($_POST['idRecertificacionBT'],ENT_NOQUOTES,'UTF-8');
		$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
		
		$idAnimalesPredio = htmlspecialchars ($_POST['animalesPredio'],ENT_NOQUOTES,'UTF-8');
		$animalesPredio = htmlspecialchars ($_POST['nombreAnimalesPredio'],ENT_NOQUOTES,'UTF-8');
		$numeroAnimalesPredio = htmlspecialchars ($_POST['numeroAnimalesPredio'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$inventarioAnimal = $cbt->buscarInventarioAnimalRecertificacionBT($conexion, $idRecertificacionBT, $idAnimalesPredio, $numInspeccion);
		
				if(pg_num_rows($inventarioAnimal) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idInventarioAnimal = pg_fetch_result($cbt->guardarInventarioAnimalRecertificacionBT($conexion, 
																	$idRecertificacionBT, $identificador,
																	$idAnimalesPredio, $animalesPredio, $numeroAnimalesPredio,
																	$numInspeccion), 
																	0, 'id_recertificacion_bt_inventario_animal');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaInventarioAnimalRecertificacionBT($idInventarioAnimal,  
																	$animalesPredio, $numeroAnimalesPredio, $ruta,
																	$numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información del inventario animal ya ha sido ingresada.";
				}							
				
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
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