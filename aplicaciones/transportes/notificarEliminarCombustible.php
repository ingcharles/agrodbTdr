<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>



<div id="estado"></div>

	<p>Las<b>ordenes de combustible</b> no se puede eliminar</p>

	<?php 
	$combustibles = explode(",",$_POST['elementos']);
	?>
	
</body>

<script type="text/javascript">

var array_combustible= <?php echo json_encode($combustibles); ?>;		
			
$(document).ready(function(){
	if(array_combustible == ''){
		$("#detalleItem").html('<div class="mensajeInicial">Las ordenes de combustible no se pueden eliminar.</div>');
	}

	});
	
</script>

</html>
