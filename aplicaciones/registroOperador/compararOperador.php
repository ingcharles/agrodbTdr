<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php


$identificador= ($_POST['identificador']); 

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();



$vue = $cr->listarDatosVUE($conexion, $identificador);
$operador = $cr->listarOperadoresVUE($conexion, $vue[0]['identificadorSolicitante'], $vue[0]['nombreProducto']);
//$operador = $cr->listarOperadoresVUE($conexion, $vue[0]['identificadorImportador'], $vue[0]['nombreProducto']);

echo 'VUE<pre>';
print_r($vue);
echo '</pre>';

echo 'OPERADOR<br/><pre>';
print_r($operador);
echo '</pre>';
/*
//ARRAY FILTRADO
for ($i=0; $i<count($_POST['hDatos']['producto']); $i++) {
	$arreglos[] = array (area=>$_POST['hDatos']['area'][$i], producto=>$_POST['hDatos']['producto'][$i]);
}

$arrayAreaProducto = uniqueArrayMultidimensional($arreglos);


//SOLICITUDES
for ($i = 0; $i < count ($productos); $i++) {
	$qIdSolicitud= $cr->guardarNuevaSolicitud($conexion, $tipoOperacion, $_SESSION['usuario']);
	$idSolicitud = pg_fetch_assoc($qIdSolicitud);
	$listaIdSolicitud[] = array(id =>$idSolicitud['id_solicitud'],producto=>$productos[$i]);
}

//AREAS Y PRODUCTOS
foreach($arrayAreaProducto as $key=>$value){
	for ($j = 0; $j < count ($listaIdSolicitud); $j++) {
		if($value['producto']==$listaIdSolicitud[$j][producto]){
			$idAreas = $cr->guardarAreaSolicitud($conexion, $value['area'], $listaIdSolicitud[$j]['id']);
			$cr->guardarProductoArea($conexion, $value['producto'], pg_fetch_result($idAreas, 0, 'id_area_solicitud'));
		}
	}
}*/
?>

</body>
<script type="text/javascript">

	//$("document").ready(function(){
		//abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);	
		//abrir($("input:hidden"),null,false);
	//});
		
		
</script>
</html>