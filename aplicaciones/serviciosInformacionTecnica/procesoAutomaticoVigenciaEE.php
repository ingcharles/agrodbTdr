<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$conexion = new Conexion();
$csit = new ControladorServiciosInformacionTecnica();

set_time_limit(600);
$contador=0;
define ( 'IN_MSG', '<br/> >>> ' );
define ( 'OUT_MSG', '<br/> <<< ' );
define ( 'PRO_MSG', '<br/> ... ' );

echo '<center><h1>PROCESO AUTOMATICO CAMBIO ESTADO VIGENCIA ENFERMEDAD EXOTICA</h1></center>';
$qFinVigencia=$csit->consultarEnfermedadesExoticasFinVigencia($conexion, 'activo');

while($fila=pg_fetch_assoc($qFinVigencia)){

	$fecha = date("Y-m-d h:m:s");
	$finVigencia=$fila['fin_vigencia'];
	$idEnfermedadExotica=$fila['id_enfermedad_exotica'];
	$nombreEnfermedad=$fila['nombre_enfermedad'];
	$usuarioResponsable=$fila['usuario_responsable'];
	echo IN_MSG .'<b><strong>INICIO PROCESO</strong></b>';
	echo PRO_MSG .'<b>PROCESO #'.($contador+1).'</b><br>';
	echo '<b>Fecha proceso: </b>'.$fecha.'<br>';
	echo '<b>Fecha fin vigencia: </b>'.$finVigencia.'<br>';
	echo '<b>Solicitud: </b>'.$idEnfermedadExotica.'<br>';
	echo '<b>Enfermedad: </b>'.$nombreEnfermedad;
	echo OUT_MSG .'<b><strong>FIN PROCESO</strong></b><br><br>';
	
	$contador++;
	
	$csit->actualizarCambioEstadoEnfermedadExoticaSinProducto($conexion, $idEnfermedadExotica, $usuarioResponsable);
	
}
?>