<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$nombrePrograma = $_POST['nombrePrograma'];
	$codigoPrograma = $_POST['codigoPrograma'];
	$identificador = $_SESSION['usuario'];
	
	$conexion = new Conexion();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$conexion->ejecutarConsulta("begin;");
	$idPrograma = $cpp->nuevoPrograma($conexion, $nombrePrograma, $codigoPrograma, $identificador);
	$conexion->ejecutarConsulta("commit;");

	echo '<input type="hidden" id="' . pg_fetch_result($idPrograma, 0, 'id_programa') . '" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirPrograma" data-destino="detalleItem"/>'
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>