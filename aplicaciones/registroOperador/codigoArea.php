<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$identificador = $_SESSION['usuario'];
$codigoArea = $_POST['codigoTipoArea'];

$res = $cc -> obtenerNombreLocalizacion($conexion, $_POST['codigoProvincia']);
$provincia = pg_fetch_assoc($res);

$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $identificador, $codigoArea, $provincia['nombre']);
$secuencialArea = pg_fetch_assoc($qSecuencialArea);
$codigoProvincia = substr($provincia['codigo_vue'],1);
$secuencial = str_pad($secuencialArea['valor'], 2, "0", STR_PAD_LEFT);
			
echo '<input type="hidden" id="codigoArea" name="codigoArea" value="'.$identificador.'.S'.$codigoArea.$codigoProvincia.$secuencial.'" />';




?>


<script type="text/javascript">
	 
</script>