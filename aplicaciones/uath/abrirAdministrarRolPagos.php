<?php 
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ca = new ControladorCatastro();

$idFucionarioRol=$_POST['id'];	
$rutaArchivo=pg_fetch_result($ca->obtenerRolPagos($conexion, "","", $idFucionarioRol),0,"ruta_archivo"); 
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<header>
	<h1>Rol de Pagos</h1>
</header>
<body>
	<embed id="visor" src="<?php echo $rutaArchivo; ?>" width="540" height="490">
</body>

<script type="text/javascript">
</script>
</html>

