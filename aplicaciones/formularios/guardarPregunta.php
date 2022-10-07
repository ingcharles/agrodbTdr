<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFormularios.php';
//require_once '../../clases/ControladorAuditoria.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<?php

	$codigoPregunta = htmlspecialchars ($_POST['id_pregunta'],ENT_NOQUOTES,'UTF-8');
	$tipoPregunta = htmlspecialchars ($_POST['tipo_pregunta'],ENT_NOQUOTES,'UTF-8');
	$Pregunta = htmlspecialchars ($_POST['pregunta'],ENT_NOQUOTES,'UTF-8');
    $ayuda = htmlspecialchars ($_POST['ayuda'],ENT_NOQUOTES,'UTF-8');

    $conexion = new Conexion();
	$cf = new ControladorFormularios();
	//$ca = new ControladorAuditoria();
		
	$pregunta = pg_fetch_row($cf->guardarPregunta($conexion, $codigoPregunta, $tipoPregunta, $Pregunta, $ayuda));
	
	//$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
	//$qTransaccion = $ca ->guardarTransaccion($conexion, $fila['id_solicitud'], pg_fetch_result($qLog, 0, 'id_log'));
	/*$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'el usuario '.$_SESSION['usuario'].' crea un archivo temporal y asigna a '.(count($registrador_id)).((count($registrador_id)>1?' revisores':' revisor')));
	*/
	
	echo '<input type="hidden" id="' . $pregunta[0] . '" data-rutaAplicacion="preguntas" data-opcion="abrirPregunta" data-destino="detalleItem"/>'
	?>
</body>
<script type="text/javascript">
		$('document').ready(function(){
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
			alert("La pregunta se ha creado con exito");
			abrir($("#detalleItem input"),null,true);
		});
		
</script>
</html>
