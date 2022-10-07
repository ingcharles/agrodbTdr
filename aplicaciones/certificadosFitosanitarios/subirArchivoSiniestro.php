<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
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
	$cv = new ControladorVehiculos();



	$numero_aleatorio = rand(1,1000);
	
	$archivo_nombre = $_FILES['archivo']['name'];
	$archivo_peso = $_FILES['archivo']['size'];
	$archivo_temporal = $_FILES['archivo']['tmp_name'];
	$archivo_error = $_FILES['archivo']['error'];
	$archivo_tipo = $_FILES['archivo']['type'];
	$extension = explode(".", $archivo_nombre);
	
	$nuevo_nombre = $numero_aleatorio.'_'.$_POST['id_siniestro'].'_'.$_POST['boton'].'.'.end($extension);
	$nuevo_nombre_factura = $numero_aleatorio.'_'.$_POST['id'].'_'.$_POST['boton'].'.'.end($extension);
	$id_siniestro = $_POST['id_siniestro'];
	$opcion = $_POST['boton'];
	
	/*	
		if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
				if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else if ($opcion == 'documentacion'){
					move_uploaded_file($archivo_temporal,  "siniestro/documentacion/".$nuevo_nombre_factura);
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/siniestro/documentacion/".$nuevo_nombre_factura;
					$cv->actualizarDocumentacionSiniestro($conexion, $_POST['id'], $rutaArchivo);
					
				} else if ($opcion == 'informe'){
					move_uploaded_file($archivo_temporal,  "siniestro/informe/".$nuevo_nombre_factura);
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/siniestro/informe/".$nuevo_nombre_factura;
					$cv->actualizarInformeSiniestro($conexion, $_POST['id'], $rutaArchivo);
					
				} else if ($opcion == 'factura'){
					move_uploaded_file($archivo_temporal,  "siniestro/factura/".$nuevo_nombre_factura);
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/siniestro/factura/".$nuevo_nombre_factura;
					$cv->actualizarFacturaSiniestro($conexion, $_POST['id'], $rutaArchivo);
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}
				
		
		if (($archivo_tipo == "image/jpeg" ) && ($archivo_peso < 3145728)) {
					if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else {
					move_uploaded_file($archivo_temporal,  "siniestro/fotos/".$nuevo_nombre);
					echo'<figure>';
					echo'<img src="siniestro/fotos/'.$nuevo_nombre.'"/>';
					echo'<figcaption>'.$opcion.'</figcaption>';
					echo'</figure>';
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/siniestro/fotos/".$nuevo_nombre;
					$cv->actualizarFotoSiniestro($conexion, $id_siniestro, $rutaArchivo, $opcion);
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}	*/
	
	
	print_r($_FILES);
	?>
	
</body>


</html>

