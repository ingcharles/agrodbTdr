<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cv = new ControladorCatastro();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

	<?php
		$academico=explode(",",$_POST['elementos']);
	
		for ($i = 0; $i < count ($academico); $i++) {
				$cv->eliminarDatosAcademicos($conexion, $academico[$i]);
		}
	?>

</body>

<script type="text/javascript">

	$('#_actualizar').click();
	
</script>
</html>