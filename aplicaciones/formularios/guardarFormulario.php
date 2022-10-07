<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorFormularios.php';
	//require_once '../../clases/ControladorAuditoria.php';
	
	
	$nombreFormulario = htmlspecialchars ($_POST['nombre'],ENT_NOQUOTES,'UTF-8');
	$codigoFormulario = htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8');
	$descripcionFormulario = htmlspecialchars ($_POST['descripcion'],ENT_NOQUOTES,'UTF-8');
	$identificador = $_SESSION['usuario'];
	
	$conexion = new Conexion();
	$cf = new ControladorFormularios();
	//$ca = new ControladorAuditoria();
	
	$formulario = pg_fetch_row($cf->guardarFormulario($conexion, $codigoFormulario, $nombreFormulario, $descripcionFormulario, $identificador));
	
	//$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
	//$qTransaccion = $ca ->guardarTransaccion($conexion, $fila['id_solicitud'], pg_fetch_result($qLog, 0, 'id_log'));
	/*$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'el usuario '.$_SESSION['usuario'].' crea un archivo temporal y asigna a '.(count($registrador_id)).((count($registrador_id)>1?' revisores':' revisor')));
	 */
	
	echo '<input type="hidden" id="' . $formulario[0] . '" data-rutaAplicacion="formularios" data-opcion="abrirFormulario" data-destino="detalleItem"/>'

?>

<script type="text/javascript">
		$('document').ready(function(){
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
			abrir($("#detalleItem input"),null,true);
		});
		
</script>

