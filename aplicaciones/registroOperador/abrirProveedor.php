<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();


$proveedor = pg_fetch_assoc($cr->abrirProveedoresOperador($conexion, $_POST['id'])); 

$tipoSubtipoProducto = pg_fetch_assoc($cc->obtenerTipoSubtipoXProductos($conexion, $proveedor['id_producto']));

$datosProveedor = pg_fetch_assoc($cr->listarOperadoresEmpresa($conexion, $proveedor['codigo_proveedor']));


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>


<header>
	<h1>Datos del proveedor</h1>
</header>

<form id="datosProveedor" data-rutaAplicacion="registroOperador" data-opcion="actualizarProveedor" data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>

		<fieldset>
			<legend>Datos generales</legend>
				
				<div data-linea="1">
					<label>Código proveedor: </label> <?php echo $proveedor['codigo_proveedor'];?>
				</div>
				
				<div data-linea="2">
					<label>Razón social: </label> <?php echo $datosProveedor['nombre_operador'];?>
				</div>
				
				<div data-linea="3">
					<label>Tipo producto: </label> <?php echo $tipoSubtipoProducto['nombre_tipo'];?>
				</div>
				
				<div data-linea="4">
					<label>Subtipo producto: </label> <?php echo $tipoSubtipoProducto['nombre_subtipo'];?>
				</div>
				
				<div data-linea="5">
					<label>Producto: </label> <?php echo $proveedor['nombre_producto'];?>
				</div>
				
				<div data-linea="6">
					<label>País: </label> <?php echo $proveedor['nombre_pais'];?>
				</div>
				
				
		</fieldset>
		

</form>

</body>
	<script type="text/javascript">
	
		$(document).ready(function(){
			distribuirLineas();
		});
	</script>
</html>