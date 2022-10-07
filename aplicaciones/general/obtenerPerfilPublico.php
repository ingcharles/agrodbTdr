<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cc = new ControladorCatastro();

$identificador = $_POST['identificador'];
$qDatoPerfilPublico = pg_fetch_assoc($cc->obtenerDatosPerfilPublicoPorIdentificador($conexion, $identificador));
$rutaPerfilPublico = $qDatoPerfilPublico['ruta_perfil_publico'];
$rutaQrPerfilPublico = $qDatoPerfilPublico['ruta_qr_perfil_publico'];

$datoRutaPerfilPublico = '<label>Link: </label><a href="' . $rutaPerfilPublico . '" target="_blank">Enlance perfil público</a><hr/>';
$datoRutaQrPerfilPublico = '<img src="' . $rutaQrPerfilPublico . '" />';

$array = array('rutaPerfilPublico'=>$datoRutaPerfilPublico, 'rutaQrPerfilPublico' => $datoRutaQrPerfilPublico);
echo json_encode($array);

?>