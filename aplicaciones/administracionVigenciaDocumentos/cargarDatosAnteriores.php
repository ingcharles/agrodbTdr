<?php

session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';

$opcion = $_POST['opcionCargar'];

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

switch($opcion){

	case 'datosAnteriores':
		
		$datosVigencia =  htmlspecialchars ($_POST['datosVigencia'],ENT_NOQUOTES,'UTF-8');
		list($nombreVigenciaAntiguo, $tipoOperacionAntiguo) = explode("-", $datosVigencia);
				
		echo '<input type="hidden" value="'.$nombreVigenciaAntiguo.'" id="nombreVigenciaAntiguo" name="nombreVigenciaAntiguo" />
			<input type="hidden" value="'.tipoOperacionAntiguo.'" id="$tipoOperacionAntiguo" name="$tipoOperacionAntiguo" />';		
		
		break;
		
}

?>