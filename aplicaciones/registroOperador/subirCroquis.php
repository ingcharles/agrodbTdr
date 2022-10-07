<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
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
		$cr = new ControladorRegistroOperador();
	
		//$numero_aleatorio = rand(1,100000);
		
		$archivo_nombre = $_FILES['archivo']['name'];
		$archivo_peso = $_FILES['archivo']['size'];
		$archivo_temporal = $_FILES['archivo']['tmp_name'];
		$archivo_error = $_FILES['archivo']['error'];
		$archivo_tipo = $_FILES['archivo']['type'];
		$extension = explode(".", $archivo_nombre);
		
		//$nuevo_nombre = $numero_aleatorio.'_'.$_POST['identificador'].'.'.end($extension);
		$nuevo_nombre = $_POST['identificador'].'-'.preg_replace('[\s+]','', $_POST['nombreSitio']).'.'.end($extension);
		
		$sitio = $cr-> abrirSitio($conexion, $_POST['idSitio']);
		
		
		
			if($sitio['croquis']!=""){
				unlink('../../'.$sitio['croquis']);
			}
				
			if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
				if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else {
					move_uploaded_file($archivo_temporal,  "croquisSitio/".$nuevo_nombre);
					
					$rutaArchivo =  "aplicaciones/registroOperador/croquisSitio/".$nuevo_nombre;
					$cr->actualizarCroquisSitio($conexion, $_POST['identificador'], $_POST['idSitio'], $rutaArchivo);
					
					echo'<p class="exito">¡Archivo cargado!</p>';
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}
	?>
	
	</body>

</html>