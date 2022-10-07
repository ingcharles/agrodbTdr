<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTrazabilidad.php';
require_once '../../clases/ControladorCatalogos.php';


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<form id="datosRegistro" data-rutaAplicacion="trazabilidad" data-opcion="listaDetalleIngreso" data-destino="tabla">
		
		<?php
		
			$conexion = new Conexion();
			$ct = new ControladorTrazabilidad();
			$cc = new ControladorCatalogos();

			$codproveedor = htmlspecialchars ($_POST['codproveedor'],ENT_NOQUOTES,'UTF-8'); 
			$nombreSitio = htmlspecialchars ($_POST['nombreSitioProveedor'],ENT_NOQUOTES,'UTF-8');
			$sitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
			$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
			$nombreArea = htmlspecialchars ($_POST['nombreAreaProveedor'],ENT_NOQUOTES,'UTF-8');
			$producto = htmlspecialchars ($_POST['producto'],ENT_NOQUOTES,'UTF-8');
			$nombreProducto = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
			$idOperacionProveedor = htmlspecialchars ($_POST['operacionProveedor'],ENT_NOQUOTES,'UTF-8');
			$nombreOperacionProveedor = htmlspecialchars ($_POST['nombreOperacionProveedor'],ENT_NOQUOTES,'UTF-8');
			$variedad = htmlspecialchars ($_POST['variedad'],ENT_NOQUOTES,'UTF-8');
			$calidad = htmlspecialchars ( $_POST['calidad'],ENT_NOQUOTES,'UTF-8');
			$cantidad = htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8');
			$unidadmedida = htmlspecialchars ($_POST['unidadmedida'],ENT_NOQUOTES,'UTF-8');
			$bultos = htmlspecialchars ($_POST['bultos'],ENT_NOQUOTES,'UTF-8');
			$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
			$operador=htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');
			$areaOperador=htmlspecialchars ($_POST['areaOperador'],ENT_NOQUOTES,'UTF-8');
			$nombreAreaOperador=htmlspecialchars ($_POST['nombreAreaOperador'],ENT_NOQUOTES,'UTF-8');
			$idOperacionOperador=htmlspecialchars ($_POST['operacionOperador'],ENT_NOQUOTES,'UTF-8');
			$nombreOperacionOperador=htmlspecialchars ($_POST['nombreOperacionOperador'],ENT_NOQUOTES,'UTF-8');
			
           	
			
			$unidad=$cc->obtenerUnidadMedida($conexion, $unidadmedida);
			$cantidadConvertida=$ct->convertirCantidad($cantidad, pg_fetch_result($unidad, 0, 'codigo'));
			$idUnidadMedida=$cc->obtenerIdUnidadMedida($conexion, 'KG');
			$idRegistro = $ct->guardarRegistroIngreso($conexion, $codproveedor, $calidad, $variedad, $producto, $nombreProducto, $area, $nombreArea, $sitio, $nombreSitio, $idOperacionProveedor, $nombreOperacionProveedor, $operador, $areaOperador, $nombreAreaOperador, $idOperacionOperador, $nombreOperacionOperador);
			$ct->guardarDetalleRegistro($conexion, $cantidadConvertida, pg_fetch_result($idUnidadMedida, 0, 'id_unidad_medida'), $bultos, $tipo, pg_fetch_result($idRegistro, 0, 'id_registro_ingreso'));

?>
	</form>
</body>
<script type="text/javascript">

	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	});
		
		
</script>
</html>
