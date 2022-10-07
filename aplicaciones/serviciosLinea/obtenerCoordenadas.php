<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion();;
$csl = new ControladorServiciosLinea();
$arrayRecorrido=array();

$idRutaTransporte=$_POST['ruta'];
$qDetalleRutaTransporte=$csl->buscarDetalleRutaTransporte($conexion, $idRutaTransporte);
$i = 0;
while($fila=pg_fetch_assoc($qDetalleRutaTransporte)){
	$arrayRecorrido[$i]=array(lat=>floatval($fila['latitud']) ,lng=>floatval($fila['longitud']),dir=>$fila['referencia_parada'],hor=>$fila['hora_aproximada'],ord=>$fila['orden'],recorrido=>$fila['recorrido'],idDetalle=>$fila['id_detalle_rutas_transporte']);
	$i=$i + 1;
}

echo json_encode( $arrayRecorrido);
?>
