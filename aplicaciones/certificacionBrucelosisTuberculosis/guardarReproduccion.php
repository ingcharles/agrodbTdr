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
	
		$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
		$numInspeccion = htmlspecialchars ($_POST['numInspeccion'],ENT_NOQUOTES,'UTF-8');
		
		$idSistemaEmpleado = htmlspecialchars ($_POST['sistemaEmpleado'],ENT_NOQUOTES,'UTF-8');
		$sistemaEmpleado = htmlspecialchars ($_POST['nombreSistemaEmpleado'],ENT_NOQUOTES,'UTF-8');
		$idProcedenciaPajuelas = htmlspecialchars ($_POST['procedenciaPajuelas'],ENT_NOQUOTES,'UTF-8');
		$procedenciaPajuelas = htmlspecialchars ($_POST['nombreProcedenciaPajuelas'],ENT_NOQUOTES,'UTF-8');
		$idLugarPariciones = htmlspecialchars ($_POST['lugarPariciones'],ENT_NOQUOTES,'UTF-8');
		$lugarPariciones = htmlspecialchars ($_POST['nombreLugarPariciones'],ENT_NOQUOTES,'UTF-8');		
		$realizaDesinfeccion = htmlspecialchars ($_POST['realizaDesinfeccion'],ENT_NOQUOTES,'UTF-8');
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
	
		try {
				
			if(($identificador != null) || ($identificador != '')){
				
				$reproduccion = $cbt->buscarReproduccionCertificacionBT($conexion, $idCertificacionBT, 
											$idSistemaEmpleado, $idProcedenciaPajuelas, $idLugarPariciones,
											$realizaDesinfeccion, $numInspeccion);
		
				if(pg_num_rows($reproduccion) == 0){
					$conexion->ejecutarConsulta("begin;");
					
					$idReproduccion = pg_fetch_result($cbt->guardarReproduccionCertificacionBT ($conexion, 
																		$idCertificacionBT, $identificador,
																		$idSistemaEmpleado, $sistemaEmpleado,
																		$idProcedenciaPajuelas, $procedenciaPajuelas,
																		$idLugarPariciones, $lugarPariciones,
																		$realizaDesinfeccion, $numInspeccion), 
																		0, 'id_certificacion_bt_reproduccion');				
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cbt->imprimirLineaReproduccionCertificacionBT($idReproduccion, 
																		$sistemaEmpleado, $procedenciaPajuelas, 
																		$lugarPariciones, $realizaDesinfeccion, $ruta,
																		$numInspeccion);
					
					$conexion->ejecutarConsulta("commit;");
				
				}else{
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "La información de reproducciónen el predio ya ha sido ingresada.";
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