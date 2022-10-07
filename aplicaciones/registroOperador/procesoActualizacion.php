<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php

	set_time_limit(444000);

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';

	define('IN_MSG','<br/> >>> ');

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();

	echo IN_MSG.'Obtención de registros de tabla operadores tipo operacion.';
	$qOperadoresTIpoOperacion = $cr->obtenerOperacionesProcesar($conexion);
			
	while ($operadoreTipoOperacion = pg_fetch_assoc($qOperadoresTIpoOperacion)){
		
		echo IN_MSG. 'Identificador operador: '.$operadoreTipoOperacion['identificador_operador'].' Identificador sitio: '.$operadoreTipoOperacion['id_sitio'].' Identificador tipo operador: '. $operadoreTipoOperacion['id_tipo_operacion'].' Identificador operacion '.$operadoreTipoOperacion['id_operacion'];
		
		$qIdOperadorTipoOperacion = $cr->guardarTipoOperacionPorIndentificadorSitioOperacion($conexion, $operadoreTipoOperacion['identificador_operador'], $operadoreTipoOperacion['id_sitio'], $operadoreTipoOperacion['id_tipo_operacion'], $operadoreTipoOperacion['id_operacion']);
		$idOperadorTipoOperacion = pg_fetch_assoc($qIdOperadorTipoOperacion);
		
		$qHistorialOperacion = $cr->guardarDatosHistoricoOperacion($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion']);
		$historicoOperacion = pg_fetch_assoc($qHistorialOperacion);
		
		$cadenaArea = '';
		
		$areas = $cr->obtenerAreasOperacion($conexion, $operadoreTipoOperacion['id_operacion']);
		
		foreach ($areas as $operadorAreaOperacion){
			$cr->guardarAreaPorIdentificadorTipoOperacion($conexion, $operadorAreaOperacion['idArea'], $idOperadorTipoOperacion['id_operador_tipo_operacion']);
			$cadenaArea .= $operadorAreaOperacion['idArea'].', ';
		}

		$cadenaArea = rtrim($cadenaArea, ', ');
		
		$qOperacionesActualizar = $cr->obtenerOperacionesAsociadasPorAreaTipoOperacion($conexion, $operadoreTipoOperacion['id_tipo_operacion'], $cadenaArea);
		
		while ($operacionesActualizar = pg_fetch_assoc($qOperacionesActualizar)){
			$cr->actualizarOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion['id_operador_tipo_operacion'], $historicoOperacion['id_historial_operacion'], $operacionesActualizar['id_operacion']);
		}
		
	}
	
	?>
</body>
</html>
