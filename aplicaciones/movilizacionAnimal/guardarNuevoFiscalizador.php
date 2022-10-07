<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php

$datos = array(
		'id_vacuna_animal' => htmlspecialchars ($_POST['id_vacuna_animal'],ENT_NOQUOTES,'UTF-8'),			
	    'usuario_reponsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'),
	    'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
		'estado' => htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8'),
		'fecha_fiscalizacion' => htmlspecialchars ($_POST['fecha_fiscalizacion'],ENT_NOQUOTES,'UTF-8')
		);
			
$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();


//Crear código de identificación de solicitud para agrupar productos
$fis = $vdr->generarCertificadoFiscalizacion($conexion);   
$fiscalizacion = pg_fetch_assoc($fis);
$secuencial = ($fiscalizacion['numero'])+1;
$codigoCertificadoFiscalizacion = 'N°'.str_pad($secuencial, 9, "0", STR_PAD_LEFT);

//Guardar datos del fiscalizador
$dFiscalizador = $vdr->guardarDatosFiscalizador($conexion, $datos['id_vacuna_animal'], $secuencial, $codigoCertificadoFiscalizacion, $datos['usuario_reponsable'], $datos['observacion'], $datos['estado'], $datos['fecha_fiscalizacion']);
$idFiscalizador = pg_fetch_result($dFiscalizador, 0, 'id_vacuna_fiscalizacion');	

$estadoFiscalizador = $vdr->actualizarDatosFiscalizador($conexion, $datos['id_vacuna_animal']);

$conexion->desconectar();	

?>
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	</script>
</html>






