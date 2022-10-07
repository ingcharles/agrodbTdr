<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEmpleados.php';
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
<script src="../general/funciones/jquery-1.9.1.js" type="text/javascript"></script>
<script src="../general/funciones/agrdbfunc.js" type="text/javascript"></script>

</head>
<body>
<?php

	$conexion = new Conexion();
	$ce = new ControladorEmpleados();
	
	$archivo_nombre = $_FILES['archivo']['name'];
	$archivo_peso = $_FILES['archivo']['size'];
	$archivo_temporal = $_FILES['archivo']['tmp_name'];
	$archivo_error = $_FILES['archivo']['error'];
	$archivo_tipo = $_FILES['archivo']['type'];
	$extension = explode(".", $archivo_nombre);
	$nuevo_nombre = $_POST['usuario'].'.'.end($extension);
	$usuario = $_POST['usuario'];
	$opcion = $_POST['opcion'];

	
	//revisar si se pasa por parametro la ruta de la fotogfrafia
	$res = $ce-> obtenerDatosPersonales($conexion, $usuario);
	$empleado= pg_fetch_assoc($res);
	
	
	if($empleado['fotografia']!=""){
		$temp = explode("/", $empleado['fotografia']);
		$archivoBorrar = end($temp);
		unlink('../../aplicaciones/uath/fotos/'.$archivoBorrar);							
	}
	
	
	if (($archivo_tipo == "image/jpeg" ) && ($archivo_peso < 3145728)) {
		if ($archivo_error > 0) {
			echo $archivo_error . "<br />";
			} else {
				move_uploaded_file($archivo_temporal,  "fotos/".$nuevo_nombre);
				echo'<figure class="'.$_POST['clase'].'">';
				echo'<img id="foto_img" src="fotos/'.$nuevo_nombre.'"/>';
				echo'<figcaption>'.$opcion.'</figcaption>';
				echo'</figure>';
				$rutaArchivo =  "aplicaciones/uath/fotos/".$nuevo_nombre;
				$ce->actualizarFoto($conexion, $usuario, $rutaArchivo);								
			}
		} else {
		echo "El archivo seleccionado no es permitido o es muy grande.";
	}
	?>
	
</body>

</html>

