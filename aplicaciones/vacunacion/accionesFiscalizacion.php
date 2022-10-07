<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();

$cro = new ControladorRegistroOperador();
set_time_limit(1000);

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
	case 'buscarOperador':
	    
	    $identificadorComerciante = htmlspecialchars ($_POST['identificadorComerciante'],ENT_NOQUOTES,'UTF-8');
	    $qOperacionesOperador = $cro->obtenerDatosOperadorXIdAreaXCodigoOperacion($conexion, $identificadorComerciante, 'SA', " in ('COM')");
	    
	    if(pg_num_rows($qOperacionesOperador) == 0){
	        
	        echo "No existen registros con el operador ingresado";
	    }else{
	        echo"bien";
	        
	    }
	break;
}

?>
