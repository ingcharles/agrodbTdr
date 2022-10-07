<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$nombreObjetivoEstrategico = $_POST['nombreObjetivoEstrategico'];
	$identificador = $_SESSION['usuario'];
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$conexion->ejecutarConsulta("begin;");
	$idObjetivoEstrategico = $cpp->nuevoObjetivoEstrategico($conexion, $nombreObjetivoEstrategico, $identificador);
	$conexion->ejecutarConsulta("commit;");

	echo '<input type="hidden" id="' . pg_fetch_result($idObjetivoEstrategico, 0, 'id_objetivo_estrategico') . '" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirObjetivoEstrategico" data-destino="detalleItem"/>'
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>