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
		$experiencia=explode(",",$_POST['elementos']);
	
		for ($i = 0; $i < count ($experiencia); $i++) {
				$cv->eliminarExperienciaLaboral($conexion, $experiencia[$i]);
		}
	?>

</body>

<script type="text/javascript">

	$('#_actualizar').click();
	
</script>
</html>