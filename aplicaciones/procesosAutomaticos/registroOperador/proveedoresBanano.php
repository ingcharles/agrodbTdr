<?php

//if ($_SERVER['REMOTE_ADDR'] == '') {
if(1){
	

	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorRegistroOperador.php';
	require_once '../../../clases/ControladorMonitoreo.php';

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cm = new ControladorMonitoreo();
	
	set_time_limit(6000);
	
	define('PRO_MSG', '<br/> ');
	define('IN_MSG', '<br/> >>> ');
	$fecha = date("Y-m-d h:m:s");
	$numero = '1';
	
	//$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_DATO_PROVEEDORES_BANANO');
	
	//if($resultadoMonitoreo){
	if(1){
	
		echo IN_MSG . '<b>INICIO PROCESO DE INGRESO DE PROVEEDORES DE REGISTRO DE OPERADOR' . $fecha . '</b>';
		
		$proveedores = $cr->obtenerProveedoresBanano($conexion);
		
		while ($proveedor = pg_fetch_assoc($proveedores)) {
		
			echo IN_MSG . $numero ++ . '.- Identificador operador: ' . $proveedor['exportador'] . ' con id: ' . $proveedor['id_proveedor'];
		
			$idRegistro = $proveedor['id_proveedor'];
		    
			$cr->actulizarEstadoProveedorBanano($conexion, $idRegistro, 'W');
		
		    $datos = array(
		    	'id' => trim(htmlspecialchars($proveedor['id_proveedor'], ENT_NOQUOTES, 'UTF-8')),
		    	'codigoProveedor' => trim(htmlspecialchars($proveedor['productor'], ENT_NOQUOTES, 'UTF-8')),
		    	'identificadorOperador' => trim(htmlspecialchars($proveedor['exportador'], ENT_NOQUOTES, 'UTF-8')),
		        'operacionOperador' => '121',
		        'idPais' => '66',
		        'nombrePais' => 'Ecuador',
		    	'nombreProducto' => trim(htmlspecialchars($proveedor['producto'], ENT_NOQUOTES, 'UTF-8')),
		        'nombreOperacion' => 'Exportador bananero');
		    
		    $producto = pg_fetch_assoc($cr->obtenerCodigoProducto($conexion, 'Frutas, hortalizas y tubérculos frescos', 'Fruta', $datos['nombreProducto']));
		
		    $validacionProveedor = '';
		    
		    $validacionProveedor = $cr->buscarProductoProveedorTipoOperacion($conexion, $datos['identificadorOperador'], $datos['codigoProveedor'], $producto['id_producto'], $datos['idPais'], $datos['operacionOperador']);
		    
		    if(pg_num_rows($validacionProveedor) == '0'){
		    	$cr->guardarNuevoProveedorComercioExterior($conexion, $datos['codigoProveedor'], $datos['identificadorOperador'], $datos['operacionOperador'], $datos['nombreOperacion'], $producto['id_producto'], $producto['nombre_comun'], $datos['idPais'], $datos['nombreProducto']);
		    	echo IN_MSG . 'Creación de proveedor.';
		    }else{
		    	echo IN_MSG . 'El operador ya posee registrado el proveedor.';
		    }
		    echo '</br>';
		    $cr->actulizarEstadoProveedorBanano($conexion, $idRegistro, 'Atendida');
		    
		
		    echo IN_MSG. 'FIN DE PROCESO.';
		}
		
		
		echo IN_MSG . '<b>INICIO PROCESO DE INACTIVACION DE PROVEEDORES DE REGISTRO DE OPERADOR' . $fecha . '</b>';
		
		$proveedores = $cr->obtenerProveedoresBanano($conexion, 'Por inactivar');
		
		while ($proveedor = pg_fetch_assoc($proveedores)) {
			
			echo IN_MSG . $numero ++ . '.- Identificador operador: ' . $proveedor['exportador'] . ' con id: ' . $proveedor['id_proveedor'];
			
			$idRegistro = $proveedor['id_proveedor'];
			
			$cr->actulizarEstadoProveedorBanano($conexion, $idRegistro, 'W');
			
			$datos = array(
				'id' => trim(htmlspecialchars($proveedor['id_proveedor'], ENT_NOQUOTES, 'UTF-8')),
				'codigoProveedor' => trim(htmlspecialchars($proveedor['productor'], ENT_NOQUOTES, 'UTF-8')),
				'identificadorOperador' => trim(htmlspecialchars($proveedor['exportador'], ENT_NOQUOTES, 'UTF-8')),
				'operacionOperador' => '121',
				'idPais' => '66',
				'nombrePais' => 'Ecuador',
				'nombreProducto' => trim(htmlspecialchars($proveedor['producto'], ENT_NOQUOTES, 'UTF-8')),
				'nombreOperacion' => 'Exportador bananero');
			
			$producto = pg_fetch_assoc($cr->obtenerCodigoProducto($conexion, 'Frutas, hortalizas y tubérculos frescos', 'Fruta', $datos['nombreProducto']));
			
			$validacionProveedor = '';
			
			$validacionProveedor = $cr->buscarProductoProveedorTipoOperacion($conexion, $datos['identificadorOperador'], $datos['codigoProveedor'], $producto['id_producto'], $datos['idPais'], $datos['operacionOperador']);
			
			if(pg_num_rows($validacionProveedor) != '0'){
				$cr->actualizarEstadoProductoProveedorTipoOperacion($conexion, $datos['identificadorOperador'], $datos['codigoProveedor'], $producto['id_producto'], $datos['idPais'], $datos['operacionOperador']);
				echo IN_MSG . 'Inactivacion de proveedor.';
			}else{
				echo IN_MSG . 'El operador no posee registrado el proveedor.';
			}
			echo '</br>';
			$cr->actulizarEstadoProveedorBanano($conexion, $idRegistro, 'Inactivado');
			
			
			echo IN_MSG. 'FIN DE PROCESO.';
		}
	}
}else{
	
	$minutoS1 = microtime(true);
	$minutoS2 = microtime(true);
	$tiempo = $minutoS2 - $minutoS1;
	$xcadenota = "FECHA " . date("d/m/Y") . " " . date("H:i:s");
	$xcadenota .= "; IP REMOTA " . $_SERVER['REMOTE_ADDR'];
	$xcadenota .= "; SERVIDOR HTTP " . $_SERVER['HTTP_REFERER'];
	$xcadenota .= "; SEGUNDOS " . $tiempo . "\n";
	$arch = fopen("../../../aplicaciones/logs/cron/automatico_datos_proveedores_banano" . date("d-m-Y") . ".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);
}

?>