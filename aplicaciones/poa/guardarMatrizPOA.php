<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id_item = $_POST['idItem'];
	$idDivision = $_POST['estructura'];
	$prog1 = $_POST['progra_1'];
	$prog2 = $_POST['progra_2'];
	$prog3 =$_POST['progra_3'];
	$prog4 =$_POST['progra_4'];
	$coberturaTerritorial = $_POST['coberturaTerritorial'];
	$beneficiados = $_POST['beneficiados'];
	$descripcionPoblacion = $_POST['descripcionPoblacion'];
	$responsable=$_POST['responsableProceso'];
	$mediosVerificacion = $_POST['mediosVerificacion'];
			
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
	   
	    $cpoa->nuevoRegistroMatriz($conexion,$id_item,$idDivision,$prog1,$prog2,$prog3,$prog4,$coberturaTerritorial,$beneficiados,$descripcionPoblacion,$responsable,$mediosVerificacion, $fecha['year']);
		
	    $mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El Registro en la Matriz de Proforma ha sido generado satisfactoriamente';
		
		$conexion->desconectar();			
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
