<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministracionDeTrampas.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cat = new ControladorAdministracionDeTrampas();


$opcion = $_POST['opcion'];

	switch ($opcion){

		case '1':
			$res = $cat -> generarCodigoTrampa($conexion, '%VF-%');
			$documento = pg_fetch_assoc($res);
			$tmp= explode("-", $documento['codigo_trampa']);		
			$incremento = end($tmp)+1;
			$codigoTrampa = 'VF-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
		break;

		case '2':
			$res = $cat -> generarCodigoTrampa($conexion, '%MF-%');
			$documento = pg_fetch_assoc($res);
			$tmp= explode("-", $documento['codigo_trampa']);		
			$incremento = end($tmp)+1;
			$codigoTrampa = 'MF-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
		break;
	}
	
	echo '<label>Código trampa: </label><input type="text" name="codigoTrampa" id="codigoTrampa" value="'.$codigoTrampa.'" readonly="readonly"/>';



?>

<script type="text/javascript"> 

	$(document).ready(function(){		
		distribuirLineas(); 

	});
	
</script>