<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();


$partidaArancelaria = htmlspecialchars ($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');

$qCodigoPartida = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);

$codigo = str_pad(pg_fetch_result($qCodigoPartida, 0, 'codigo'), 4, "0", STR_PAD_LEFT);

/*echo '<div data-linea="2">
			<label for="codigoProducto">Código</label>
			<input name="codigoProducto" id="codigoProducto" type="text"  value = "'.$codigo.'" />
		</div>'; */

?>
