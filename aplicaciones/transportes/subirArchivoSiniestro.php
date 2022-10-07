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
	$opcion = $_POST['boton'];
	
	$res = $cv-> abrirSiniestro($conexion, $_POST['id_siniestro']);
	$siniestro= pg_fetch_assoc($res);
	
	
		switch ($opcion){
			case 'Frontal':   	if($siniestro['imagen_frontal']!=""){
									unlink('../../'.$siniestro['imagen_frontal']);
									break;
								}else break;
								
			case 'Posterior': 	if($siniestro['imagen_trasera']!=""){
									unlink('../../'.$siniestro['imagen_trasera']);
									break;
								}else break;
								
			case 'Derecha':   	if($siniestro['imagen_derecha']!=""){
									unlink('../../'.$siniestro['imagen_derecha']);
									break;
								}else break;
			case 'Izquierda': 	if($siniestro['imagen_izquierda']!=""){
									unlink('../../'.$siniestro['imagen_izquierda']);
									break;
								}else break;
		}
		
		if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
				if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else if ($opcion == 'documentacion'){
						move_uploaded_file($archivo_temporal,  "siniestro/documentacion/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
						$rutaArchivo =  "aplicaciones/transportes/siniestro/documentacion/".$nuevo_nombre;
						$cv->actualizarDocumentacionSiniestro($conexion, $_POST['id_siniestro'], $rutaArchivo);
							
					} else if ($opcion == 'informe'){
						move_uploaded_file($archivo_temporal,  "siniestro/informe/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
						$rutaArchivo =  "aplicaciones/transportes/siniestro/informe/".$nuevo_nombre;
						$cv->actualizarInformeSiniestro($conexion, $_POST['id_siniestro'], $rutaArchivo);
							
					} else if ($opcion == 'factura'){
						move_uploaded_file($archivo_temporal,  "siniestro/factura/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
						$rutaArchivo =  "aplicaciones/transportes/siniestro/factura/".$nuevo_nombre;
						$cv->actualizarFacturaSiniestro($conexion, $_POST['id_siniestro'], $rutaArchivo);
					}
				
			}
				
		
		else if (($archivo_tipo == "image/jpeg" ) && ($archivo_peso < 3145728)) {
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
					$cv->actualizarFotoSiniestro($conexion, $_POST['id_siniestro'], $rutaArchivo, $opcion);
				}
			} 
			
			else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}	
	?>
	
</body>


</html>

