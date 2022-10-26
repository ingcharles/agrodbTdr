<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

$conexion = new Conexion();
$cv = new ControladorVacaciones();

$idPermiso=$_POST['id_registro'];

$filaSolicitud = pg_fetch_assoc($cv->obtenerPermisoSolicitado($conexion,$idPermiso));
$rutaArchivo = $filaSolicitud['ruta_informe'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>

	<embed id="visor" src="<?php echo  $filaSolicitud['ruta_informe']; ?>" width="540" height="490">

</body>

<script type="text/javascript">

 
</script>
</html>