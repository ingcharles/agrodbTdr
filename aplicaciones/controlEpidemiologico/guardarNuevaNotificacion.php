<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorControlEpidemiologico.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorCatalogos.php';

	try{
		
		$identificadorNotificante = htmlspecialchars (trim($_POST['identificadorNotificante']),ENT_NOQUOTES,'UTF-8');
		$nombreNotificante = htmlspecialchars (trim($_POST['nombreNotificante']),ENT_NOQUOTES,'UTF-8');
		$apellidoNotificante = htmlspecialchars (trim($_POST['apellidoNotificante']),ENT_NOQUOTES,'UTF-8');
		$telefonoNotificante = htmlspecialchars (trim($_POST['telefonoNotificante']),ENT_NOQUOTES,'UTF-8');
		$celularNotificante = htmlspecialchars (trim($_POST['celularNotificante']),ENT_NOQUOTES,'UTF-8');
		$identificadorOperador = htmlspecialchars (trim($_POST['identificadorOperador']),ENT_NOQUOTES,'UTF-8');
		$codigoSitio = htmlspecialchars (trim($_POST['sitio']),ENT_NOQUOTES,'UTF-8');
		$idEspecie = htmlspecialchars (trim($_POST['especie']),ENT_NOQUOTES,'UTF-8');
		$especie = htmlspecialchars (trim($_POST['nombreEspecie']),ENT_NOQUOTES,'UTF-8');
		$poblacionAfectada = htmlspecialchars (trim($_POST['poblacionAfectada']),ENT_NOQUOTES,'UTF-8');
		$patologia = htmlspecialchars (trim($_POST['patologia']),ENT_NOQUOTES,'UTF-8');
		
		try {
			$conexion = new Conexion();
			$cr = new ControladorRegistroOperador();
			$ce = new ControladorControlEpidemiologico();
			$cc = new ControladorCatalogos();
			
			
			$operador = $cr->buscarOperador($conexion, $identificadorOperador);
			
			if( pg_num_rows($operador) != 0){
				$ce -> guardarNuevaNotificacion($conexion, $identificadorNotificante, $nombreNotificante, $apellidoNotificante, $telefonoNotificante, $celularNotificante, $identificadorOperador, $codigoSitio, $idEspecie, $especie, $poblacionAfectada, $patologia);
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'El registro ha sido ingresado exitosamente.';
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "Por favor debe registrar al operador antes de continuar.";
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