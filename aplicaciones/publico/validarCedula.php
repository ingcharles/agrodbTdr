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
	//require_once '../../clases/ControladorUsuarios.php';
	//require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorValidacion.php';
	//require_once '../../clases/ControladorServiciosGubernamentales.php';
	require_once '../../clases/ControladorValidarIdentificacion.php';

	define('IN_MSG','<br/> >>> ');
	define('OUT_MSG','<br/> <<< ');
	define('PRO_MSG', '<br/> ... ');
	
	set_time_limit(172800);

	$conexion = new Conexion('localhost', '5432', 'saite', 'postgres', 'admin');
	//$cu = new ControladorUsuarios();
	//$cro = new ControladorRegistroOperador();
	//$webServices = new ControladorServiciosGubernamentales();
	$cv = new ControladorValidacion();
	$cvi = new ControladorValidarIdentificacion();


	//$validacionDatos = $cro->obtenerRegistrosOperadorValidar($conexion);
	//$validacionDatos = $cu->obtenerUsuariosValidar($conexion);
	$validacionDatos = $cv->validarDatosFuncion($conexion);

	while ($registroValidar = pg_fetch_assoc($validacionDatos)){

		echo '<p> <strong>INICIO VERIFICACIÓN ' . $registroValidar['identificador'] . '</strong>' . IN_MSG . 'Inicio';
			if($cvi->validarCedula($registroValidar['identificador'])){
				//$nombre =  str_replace("'", " ", $resultadoConsulta['Nombre']);
				//$cro->actualizarRegistrosOperadorValidar($conexion, $registroValidar['identificador'], 'TRUE');
				$cv->actualizarDatosFuncion($conexion, 'TRUE', $registroValidar['id']);
				//$cu->actualizarUsuariosValidar($conexion, $registroValidar['identificador'], 'TRUE');
			}else{
				$cv->actualizarDatosFuncion($conexion, $cvi->getMessage(), $registroValidar['id']);
				//$cro->actualizarRegistrosOperadorValidar($conexion, $registroValidar['identificador'], $resultadoConsulta['Error']);
				//$cu->actualizarUsuariosValidar($conexion, $registroValidar['identificador'], $resultadoConsulta['Error']);
			}
		//}else{
			//$cro->actualizarRegistrosOperadorValidar($conexion, $registroValidar['identificador'], 'Identificación no existe.');
			//$cu->actualizarUsuariosValidar($conexion, $registroValidar['identificador'], 'Identificación no existe.');
		//}

		echo OUT_MSG . 'Se ha finalizado la tarea.';
		echo '<br/><strong>FIN</strong></p>';
	}

	?>

</body>
</html>
