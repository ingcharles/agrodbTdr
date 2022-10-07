<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificacionBPA.php';

$ccb = new ControladorCertificacionBPA();
$conexion = new Conexion();

$idSolicitud = $_POST['idSolicitud'];

?>

<body>

<?php
    $res = $ccb->obtenerCertificadoBPA($conexion, $idSolicitud);
	$certificado = pg_fetch_assoc($res);
?>

<embed id="visor" src="<?php echo  $certificado['ruta_certificado']; ?>" width="540" height="550">
</body>