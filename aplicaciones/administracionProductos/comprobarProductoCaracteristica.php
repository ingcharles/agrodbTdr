<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';


$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

//$data  = json_decode($_POST['data'], true);

$producto= $_POST['producto'];
$etiqueta= $_POST['etiqueta'];
$catalogo= $_POST['catalogo'];
$formulario= $_POST['formulario'];

$conexion = new Conexion();
$cac = new ControladorAdministrarCaracteristicas();

try{
		
	try {
		
		$items = array();	
		$val1=0;
		$val2=0;
		
		$res= $cac->comprobarEtiqueta($conexion,$producto,$etiqueta,$formulario);
		$fila=pg_num_rows($res);		
		
		if($fila>0){		    
		    $val1=1;
		}
		
		$res= $cac->comprobarCatalogo($conexion,$producto,$catalogo,$formulario);
		$fila=pg_num_rows($res);
		
		if($fila>0){		    
		    $val2=1;
		}
		
		if($val1==1 && $val2==0){
		    $contenido = "etiqueta";
		} else if ($val1==0 && $val2==1){
		    $contenido = "catalogo";
		} else if ($val1==1 && $val2==1){
		    $contenido = "ambos";
		} else if ($val1==0 && $val2==0){
		    $contenido = "ninguno";
		}
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $contenido;
		
			
			
		echo json_encode($mensaje);
	} catch (Exception $ex){
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error al ejecutar sentencia';
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>