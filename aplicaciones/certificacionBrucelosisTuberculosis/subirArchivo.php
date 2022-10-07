<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';
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
	$cbt = new ControladorBrucelosisTuberculosis();

	$numero_aleatorio = rand(1,1000);
	
	$archivo_nombre = $_FILES['archivo']['name'];
	$archivo_peso = $_FILES['archivo']['size'];
	$archivo_temporal = $_FILES['archivo']['tmp_name'];
	$archivo_error = $_FILES['archivo']['error'];
	$archivo_tipo = $_FILES['archivo']['type'];
	$extension = explode(".", $archivo_nombre);
	$aplicacion = $_POST['aplicacion'];
	
	$nuevo_nombre = $numero_aleatorio.'_'.$_POST['id'].'.'.end($extension);
	
	if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
		if ($archivo_error > 0) {
			echo $archivo_error . "<br />";
		} else {
						
			switch ($aplicacion){ 
				//Archivos de Mapa
				case 'certificacionBT':{
					move_uploaded_file($archivo_temporal,  "mapa/certificacionBT/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
					
					$rutaArchivo =  "aplicaciones/certificacionBrucelosisTuberculosis/mapa/certificacionBT/".$nuevo_nombre;
					$cbt->actualizarImagenMapaCertificacionBT($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				
				//Archivos de Informes
				case 'InformeCertificacionBT':{
					move_uploaded_file($archivo_temporal,  "informe/certificacionBT/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
						
					$rutaArchivo =  "aplicaciones/certificacionBrucelosisTuberculosis/informe/certificacionBT/".$nuevo_nombre;
					$cbt->actualizarInformeCertificacionBT($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				
				case 'InformeRecertificacionBT':{
					move_uploaded_file($archivo_temporal,  "informe/recertificacionBT/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
				
					$rutaArchivo =  "aplicaciones/certificacionBrucelosisTuberculosis/informe/recertificacionBT/".$nuevo_nombre;
					$cbt->actualizarInformeRecertificacionBT($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				
				//Archivos de Informes de Laboratorios
				case 'InformeCertificacionBTLaboratorios':{
					move_uploaded_file($archivo_temporal,  "informeLaboratorios/CertificacionBTLaboratorios/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
						
					$rutaArchivo =  "aplicaciones/certificacionBrucelosisTuberculosis/informeLaboratorios/CertificacionBTLaboratorios/".$nuevo_nombre;
					$cbt->actualizarInformeCertificacionBT($conexion, $_POST['id'], $rutaArchivo);
					break;
				}fault:{
					break;
				}
			}
			
		}
	} else {
		echo "El archivo seleccionado no es permitido o es muy grande.";
	}
		
	?>
	
</body>

</html>