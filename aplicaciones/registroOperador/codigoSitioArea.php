<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$identificador = $_SESSION['usuario'];
$codigoArea = htmlspecialchars ($_POST['codigoTipoArea'],ENT_NOQUOTES,'UTF-8');
$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
$codigoSitio = htmlspecialchars (($_POST['codigoSitio']==''? $_POST['codigoSitioIncial']:$_POST['codigoSitio']),ENT_NOQUOTES,'UTF-8');

//print_r($_POST);

$res = $cc -> obtenerNombreLocalizacion($conexion, $_POST['codigoProvincia']);
$provincia = pg_fetch_assoc($res);
$codigoProvincia = substr($provincia['codigo_vue'],1);

switch ($tipo){
	case 'area':
	
		$qSecuencialArea = $cr-> obtenerSecuencialArea($conexion, $identificador, $codigoArea, $provincia['nombre']);
		$secuencialArea = pg_fetch_assoc($qSecuencialArea);
		$secuencialArea = str_pad($secuencialArea['valor'], 2, "0", STR_PAD_LEFT);
			
		echo '<input type="hidden" id="codigoArea" name="codigoArea" value="'.$identificador.'.'.$codigoSitio.$codigoArea.$secuencialArea.'" />';
		
		
	break;
	
	case 'sitio':
		$qSecuencialSitio = $cr->obtenerSecuencialSitio($conexion, $provincia['nombre'], $identificador);
		$secuencialSitio = pg_fetch_assoc($qSecuencialSitio);
		$secuencialSitio = str_pad($secuencialSitio['valor'], 2, "0", STR_PAD_LEFT);
		echo '<input type="hidden" id="codigoSitio" name="codigoSitio" value="'.$codigoProvincia.$secuencialSitio.'" />';
	break;
	
	default:
		echo 'Acción desconocida';
}







?>


<script type="text/javascript">
	 
</script>