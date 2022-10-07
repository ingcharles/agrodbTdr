<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$cc = new ControladorCatastro();
$cd = new ControladorVacaciones();
$ca = new ControladorAreas();

$consul = $cc->obtenerDatosUsuarioAgrocalidad($conexion, $_POST["identificador"]);


if(pg_num_rows($consul) != 0){
	$valores_datos=pg_fetch_array($consul);
	$resultadoConsulta=$cd->devolverJefeImnediato($conexion, $_POST["identificador"]);
	
	if($_POST['idarea'] == $resultadoConsulta['idarea'] ){
	
	$nombre_area=pg_fetch_result($ca->buscarPadreSubprocesos($conexion, $resultadoConsulta['idarea']), 0, 'nombre');
	
	$return = array(
			'nombre'=>$valores_datos['nombre_completo'],
			'zona'=>$nombre_area
			);
	}else $return = array('error'=>'Debe seleccionar un servidor de la misma zona..!!!');
}else{

	$return = array('error'=>'No existe ningun registro..!!!');
}
die(json_encode($return));

?>