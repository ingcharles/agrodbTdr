<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';

$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();

$rr=array();
$busquedaTexto =  $_GET['searchText'];
$resultadoMaximo =  $_GET['maxResults'];
$qResultado=$cmp->consultarSolicitanteCertificadoMovilizacion($conexion, $busquedaTexto, $resultadoMaximo);
while($fila=pg_fetch_assoc($qResultado)){
	$rr[]=array (value => $fila['identificador_operador'],label => $fila['nombre_operador']);
}
echo json_encode($rr);
?>