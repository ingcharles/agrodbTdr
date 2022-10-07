<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorAuditoria.php';
	
	$nombre = htmlspecialchars (trim($_POST['nombreTipo']),ENT_NOQUOTES,'UTF-8');
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();
	$ca = new ControladorAuditoria();
	
	
	$idTipoProducto = $cr -> guardarNuevoTipoProducto($conexion, $nombre, $area);
	
	/***********************
	 ******* AUDITORIA*****
	 **********************/
	
	$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
	$qTransaccion = $ca ->guardarTransaccion($conexion, pg_fetch_result($idTipoProducto, 0, 'id_tipo_producto'), pg_fetch_result($qLog, 0, 'id_log'));
	$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el tipo producto '.$nombre);
	
	
	echo '<input type="hidden" id="' . pg_fetch_result($idTipoProducto, 0, 'id_tipo_producto') . '" data-rutaAplicacion="registroProducto" data-opcion="abrirTipoProducto" data-destino="detalleItem"/>'
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("input:hidden"),null,true);
	});	
</script>