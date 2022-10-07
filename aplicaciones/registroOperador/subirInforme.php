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
		$nuevo_nombre = 'INF-'.$_POST['idSolicitud'].'-'.$_SESSION['idLocalizacionPadre'].'-'.$_POST['identificador'].'.'.end($extension);
		
		$solicitud = $cr-> abrirOperacion($conexion, $_POST['identificador'], $_POST['idSolicitud']);
		
		
		
			if($solicitud['informe']!=""){
				unlink('../../'.$solicitud['informe']);
			}
				
			if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
				if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else {
					move_uploaded_file($archivo_temporal,  "informeSolicitud/".$nuevo_nombre);
					$rutaArchivo =  "aplicaciones/registroOperador/informeSolicitud/".$nuevo_nombre;
					$cr->guardarInformeOperacion($conexion, $_POST['idSolicitud'], $rutaArchivo);
					echo'<p class="exito">¡Archivo cargado!</p>';
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}
	?>
	
	</body>

</html>