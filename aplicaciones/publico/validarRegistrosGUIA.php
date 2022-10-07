<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sistema GUIA</title>
</head>
<body>
	<h1>Verificación de registros realizados en el sistema</h1>

	<?php
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorServiciosGubernamentales.php';

	define('IN_MSG','<br/> >>> ');
	define('OUT_MSG','<br/> <<< ');
	define('PRO_MSG', '<br/> ... ');

	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$cro = new ControladorRegistroOperador();
	$webServices = new ControladorServiciosGubernamentales();


	$validacionDatos = $cro->obtenerRegistrosOperadorValidar($conexion);
	//$validacionDatos = $cu->obtenerUsuariosValidar($conexion);

	while ($registroValidar = pg_fetch_assoc($validacionDatos)){

		echo '<p> <strong>INICIO VERIFICACIÓN ' . $registroValidar['identificador'] . '</strong>' . IN_MSG . 'Inicio';

		$cantidadCaracteres = strlen($registroValidar['identificador']);

		switch ($cantidadCaracteres){

			case '10':
				$tipoAcceso = true;
				$rutaWebervices = 'https://www.bsg.gob.ec/sw/RC/BSGSW01_Consultar_Cedula?wsdl';
				break;

			case '13':
				$tipoAcceso = true;
				$rutaWebervices = 'https://www.bsg.gob.ec/sw/SRI/BSGSW01_Consultar_RucSRI?wsdl';
				break;

			default:
				$tipoAcceso = false;
		}

		if($tipoAcceso){
			try {
				$resultadoAutenticacion = $webServices->consultarWebServicesAutenticacion($rutaWebervices);	
			} catch (Exception $e) {
				echo $e;
			}

			$cabeceraSeguridad = $webServices->crearCabeceraSeguridadWebServices($resultadoAutenticacion);

			switch ($cantidadCaracteres){
					
				case '10':
					$resultadoConsulta = $webServices->consultarWebServicesCedula($cabeceraSeguridad, $registroValidar['identificador']);
				break;
				
				case '13':
					//Tipos de funciones del SRI: obtenerCompleto, obtenerDatos, obtenerSimple
					$resultadoConsulta = $webServices->consultarWebServicesRUC($cabeceraSeguridad, $registroValidar['identificador'], 'obtenerCompleto');
				break;
			}

			if($resultadoConsulta['CodigoError'] == '000'){
				$cro->actualizarRegistrosOperadorValidar($conexion, $registroValidar['identificador'], 'TRUE');
				//$cu->actualizarUsuariosValidar($conexion, $registroValidar['identificador'], 'TRUE');
			}else{
				$cro->actualizarRegistrosOperadorValidar($conexion, $registroValidar['identificador'], $resultadoConsulta['Error']);
				//$cu->actualizarUsuariosValidar($conexion, $registroValidar['identificador'], $resultadoConsulta['Error']);
			}
		}else{
			$cro->actualizarRegistrosOperadorValidar($conexion, $registroValidar['identificador'], 'Identificación no existe.');
			//$cu->actualizarUsuariosValidar($conexion, $registroValidar['identificador'], 'Identificación no existe.');
		}

		echo OUT_MSG . 'Se ha finalizado la tarea.';
		echo '<br/><strong>FIN</strong></p>';
	}

	?>

</body>
</html>
