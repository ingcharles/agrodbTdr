<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorAplicaciones.php';
require_once '../../../clases/ControladorGestionAplicacionesPerfiles.php';

define ( 'IN_MSG', '<br/> >>> ' );
define ( 'OUT_MSG', '<br/> <<< ' );
define ( 'PRO_MSG', '<br/> ... ' );

$conexion = new Conexion();
$ca = new ControladorAplicaciones();
$cgap= new ControladorGestionAplicacionesPerfiles();

echo '<h1>ACTUALIZACION GESTION APLICACION PERFILES </h1>';
echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION APLICACION</strong>';

$qAplicacionAActivar=$cgap->obtenerGestionAplicacionAActivar($conexion, 'FALSE');

$contadorAplicacion=1;
while($filaAplicacion=pg_fetch_assoc($qAplicacionAActivar) ){
	echo '<br/><b> Proceso #' . $contadorAplicacion ++ . ' - ID GESTION APLICACION ' . $filaAplicacion ['id_aplicacion'] . ' - USUARIO '. $filaAplicacion['identificador'] .'</b>';
	echo IN_MSG . 'Envio solicitud de aplicacion';
	echo PRO_MSG . 'Ejecución Proceso' . '</b>';
	
$ca->guardarGestionAplicacion($conexion, pg_fetch_result($ca->obtenerIdAplicacion($conexion, $filaAplicacion['codificacion_aplicacion']), 0, 'id_aplicacion') , $filaAplicacion['identificador'], 0, 'notificaciones');	
echo OUT_MSG . 'Fin del envio de solicitud de la aplicacion';
echo IN_MSG . 'Envio estado solicitud de actualizacion en gestion aplicacion';
echo PRO_MSG . 'Ejecución Proceso' . '</b>';
$cgap->actualizarGestionAplicacionEstado($conexion, $filaAplicacion['id_aplicacion'], 'TRUE');
echo OUT_MSG . 'Fin del envio de solicitud en gestion aplicacion';

}
echo '<br/><strong>FIN</strong></p>';
echo '<p> <strong>INICIO PROCESO DE ACTUALIZACION PERFIL</strong>';
$qPerfilAActivar=$cgap->obtenerGestionPerfilAActivar($conexion, 'FALSE');

$contadorPerfil=1;
while($filaPerfil=pg_fetch_assoc($qPerfilAActivar) ){
	echo '<br/><b> Proceso #' . $contadorPerfil ++ . ' - ID GESTION PERFIL ' . $filaPerfil ['id_perfil'] . ' - USUARIO '. $filaPerfil['identificador'] .'</b>';
	echo IN_MSG . 'Envio solicitud de perfil';
	echo PRO_MSG . 'Ejecución Proceso' . '</b>';
	
	$ca->guardarGestionPerfil($conexion,  $filaPerfil['identificador'], pg_fetch_result($ca->obtenerIdPerfil($conexion, $filaPerfil['codificacion_perfil']), 0, 'id_perfil'));
	echo OUT_MSG . 'Fin del envio de solicitud de la perfil';
	echo IN_MSG . 'Envio estado solicitud de actualizacion en gestion perfil';
	echo PRO_MSG . 'Ejecución Proceso' . '</b>';
	$cgap->actualizarGestionPerfilEstado($conexion, $filaPerfil['id_perfil'], 'TRUE');
	echo OUT_MSG . 'Fin del envio de solicitud en gestion perfil';

}


echo '<br/><strong>FIN</strong></p>';
//$conexion->desconectar ();
?>