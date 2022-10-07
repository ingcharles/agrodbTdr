<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php
	$datos = array('nombreSitio' => htmlspecialchars ($_POST['nombreSitio'],ENT_NOQUOTES,'UTF-8'), 
			    'superficieTotal' => htmlspecialchars ($_POST['superficieTotal'],ENT_NOQUOTES,'UTF-8'),
				'provincia' => htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8'),
				'canton' => htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8'),
				'parroquia' => htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8'),
				'direccion' => htmlspecialchars ($_POST['direccion'],ENT_NOQUOTES,'UTF-8'),
				'referencia' => htmlspecialchars ($_POST['referencia'],ENT_NOQUOTES,'UTF-8'),
				'telefono' => htmlspecialchars ($_POST['telefono'],ENT_NOQUOTES,'UTF-8'),
				'archivo' => htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8'),
				'latitud' => htmlspecialchars ($_POST['latitud'],ENT_NOQUOTES,'UTF-8'),
				'longitud' => htmlspecialchars ($_POST['longitud'],ENT_NOQUOTES,'UTF-8'),
				'archivo' => htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8'));
	
	$nombreArea= ($_POST['hNombreArea']);
	$tipoArea= ($_POST['hTipoArea']);
	$superficie= ($_POST['hSuperficie']);
	
	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	
	$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['provincia']);
	$provincia = pg_fetch_assoc($res);
	
	$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['canton']);
	$canton = pg_fetch_assoc($res);
	
	$res = $cc -> obtenerNombreLocalizacion($conexion, $datos['parroquia']);
	$parroquia = pg_fetch_assoc($res);
	
	$res = $cr->generarNumeroSitio($conexion, '%'.$datos['provincia'].'%');
	$sitio = pg_fetch_assoc($res);
	$tmp= explode("-", $sitio['numero']);
	$incremento = end($tmp)+1;
	
	$codigoSitio = 'S-'.$datos['provincia'].'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
	
	$qIdSitio = $cr->guardarNuevoSitio($conexion, $datos['nombreSitio'], $provincia['nombre'], $canton['nombre'], $parroquia['nombre'], $datos['direccion'], $datos['referencia'], $datos['superficieTotal'], $_SESSION['usuario'], $datos['telefono'], $datos['latitud'], $datos['longitud'], $codigoSitio, $datos['archivo']);
	$idSitio = pg_fetch_assoc($qIdSitio);
	
	
	for ($i = 0; $i < count ($nombreArea); $i++) {
		$sitios = $cr -> guardarNuevaArea($conexion, $nombreArea[$i], $tipoArea[$i], $superficie[$i], $idSitio['id_sitio']);
	}
	?>
	
</body>
<script type="text/javascript">

	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);	
		abrir($("input:hidden"),null,false);
	});
				
</script>
</html>
