<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Last-Modified" content="0">
<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
<meta http-equiv="Pragma" content="no-cache">
<link rel='stylesheet' href='../general/estilos/agrodb_papel.css' >
<link rel='stylesheet' href='../general/estilos/agrodb.css'>
</head>
<body>


<?php

	$conexion = new Conexion();
	$cv = new ControladorVacaciones();

	$numero_aleatorio = rand(1,1000);
	
	$archivo_nombre = $_FILES['archivo']['name'];
	$archivo_peso = $_FILES['archivo']['size'];
	$archivo_temporal = $_FILES['archivo']['tmp_name'];
	$archivo_error = $_FILES['archivo']['error'];
	$archivo_tipo = $_FILES['archivo']['type'];
	$extension = explode(".", $archivo_nombre);
	
	$nuevo_nombre = $numero_aleatorio.'_'.$_POST['id'].'.'.end($extension);
	
	if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
		if ($archivo_error > 0) {
			echo $archivo_error . "<br />";
		} else {
			move_uploaded_file($archivo_temporal,  "certificados/".$nuevo_nombre);
			echo'<p class="exito">¡Archivo cargado!</p>';
			$rutaArchivo =  "aplicaciones/vacacionesPermisos/certificados/".$nuevo_nombre;
			$cv->actualizarCertificadoPermiso($conexion, $_POST['id'], $rutaArchivo);
		}
	} else {
		echo "El archivo seleccionado no es permitido o es muy grande.";
	}
		
	?>
	
</body>


</html>

