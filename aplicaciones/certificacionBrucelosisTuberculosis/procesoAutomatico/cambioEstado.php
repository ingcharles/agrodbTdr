<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorBrucelosisTuberculosis.php';

$conexion = new Conexion();
$cbt = new ControladorBrucelosisTuberculosis();

$solicitudesAprobadas = $cbt->listarCertificadosPorExpirar($conexion, "aprobado");

define('IN_MSG','<br/>');

while ($fila = pg_fetch_assoc($solicitudesAprobadas)){
	$datetime1 = new DateTime($fila['fecha_aprobacion']);
	$datetime2 = new DateTime("now");
	
	$interval = $datetime1->diff($datetime2);
	
	echo IN_MSG;
	echo "Solicitud N°: ".$fila['num_solicitud'];
	echo IN_MSG;
	echo "Fecha aprobación: ".$datetime1->format('Y-m-d H:i:s');
	echo IN_MSG;
	echo "N° días transcurridos: ".$interval->format('%R%a días');
	echo IN_MSG;
	echo "Estado: ";
	
	
	//Revisar la condicion para poner el cambio de estado al estar 30 dias de la fecha de expiracion
	if($interval->format('%a')==365){
		echo 'Expirado';
		$cbt->actualizarEstadoCertificacionBT($conexion, $fila['id_certificacion_bt'], 'expirado');
	}else if($interval->format('%a')==(365-30)){
		echo 'Por Expirar';
		$cbt->actualizarEstadoCertificacionBT($conexion, $fila['id_certificacion_bt'], 'porExpirar');
	}else if($interval->format('%a')>(365)){
		echo 'Expirado sin renovación';
	}else if($interval->format('%a')<(365-30)){
		echo 'Vigente';
	}
	echo IN_MSG;
}

//$datetime1 = new DateTime('2009-10-11');
//$datetime2 = new DateTime('2009-10-13');
//$interval = $datetime1->diff($datetime2);
//echo $interval->format('%R%a días');
?>