<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<?php

$prueba = (array) json_decode($_POST['prueba']);


echo '<pre>';
	print_r($prueba);
echo '</pre>';


/*

$exportador = $_POST['exportador'];
$direccion = $_POST['direccion'];
$fecha = $_POST['fecha'];
$deposito = $_POST['deposito'];
$caja = $_POST['caja'];
$agencia = $_SESSION['usuario'];

$conexion = new Conexion();
$cc = new ControladorCertificados();

	for($i=0; $i<count($exportador); $i++)
	{	
		$cc -> guardarNuevoCertificado($conexion,$exportador[$i],$direccion[$i],$fecha[$i],$deposito[$i],$caja[$i],$agencia);	
		
	}
*/	
?>

</body>

<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
</script>
</html>


