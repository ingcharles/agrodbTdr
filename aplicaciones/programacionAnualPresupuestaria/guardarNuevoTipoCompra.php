<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$nombreTipoCompra = $_POST['nombreTipoCompra'];
	$identificador = $_SESSION['usuario'];
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$conexion->ejecutarConsulta("begin;");
	$idTipoCompra = $cpp->nuevoTipoCompra($conexion, $nombreTipoCompra, $identificador);
	$conexion->ejecutarConsulta("commit;");

	echo '<input type="hidden" id="' . pg_fetch_result($idTipoCompra, 0, 'id_tipo_compra') . '" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirTipoCompra" data-destino="detalleItem"/>'
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>