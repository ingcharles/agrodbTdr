<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$ce = new ControladorMercanciasSinValorComercial();
$conexion = new Conexion();

$idSolicitud = $_POST['idSolicitud'];

?>

<body>

<?php
	$res = $ce->obtenerCertificadoZoosanitario($conexion, $idSolicitud);
	$certificado = pg_fetch_assoc($res);
?>

<embed id="visor" src="<?php echo  $certificado['ruta_zoosanitario']; ?>" width="540" height="550">
</body>