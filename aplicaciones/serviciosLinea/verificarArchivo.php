<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';
$localizacion = $_POST['localizacion'];
$fecha = $_POST['fecha'];

$conexion = new Conexion();
$csl = new ControladorServiciosLinea();

		$res = $csl->verificarArchivoExistente($conexion, $localizacion, $fecha);
		if(pg_num_rows($res)==0)
			$bandera=true;
		else 
			$bandera=false;
		
		echo json_encode($bandera);
?>