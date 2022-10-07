<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cc = new ControladorCatastro();
$cu = new ControladorUsuarios();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

	<?php
		 $funcionarios=explode(",",$_POST['elementos']);
	
		for ($i = 0; $i < count ($funcionarios); $i++) {
                $cu->desactivarCuenta($conexion, $funcionarios[$i]);
				$cc->eliminarFuncionario($conexion, $funcionarios[$i]);
		}
	?>

</body>

<script type="text/javascript">

	//$('#_actualizar').click();
	       $("#filtrar").submit();	
	</script>
</html>
