<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';
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
	$cpco = new ControladorProgramasControlOficial();

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
				case 'MurcielagosHematofagos':{
					move_uploaded_file($archivo_temporal,  "mapa/MurcielagosHematofagos/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
					
					$rutaArchivo =  "aplicaciones/programasControlOficial/mapa/MurcielagosHematofagos/".$nuevo_nombre;
					$cpco->actualizarImagenMapaMurcielagosHematofagos($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				case 'ControlVectores':{
					move_uploaded_file($archivo_temporal,  "mapa/ControlVectores/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
					
					$rutaArchivo =  "aplicaciones/programasControlOficial/mapa/ControlVectores/".$nuevo_nombre;
					$cpco->actualizarImagenMapaControlVectores($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				case 'InspeccionOCCS':{
					move_uploaded_file($archivo_temporal,  "mapa/InspeccionOCCS/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
					
					$rutaArchivo =  "aplicaciones/programasControlOficial/mapa/InspeccionOCCS/".$nuevo_nombre;
					$cpco->actualizarImagenMapaInspeccionOCCS($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				case 'CatastroPredioEquidos':{
					move_uploaded_file($archivo_temporal,  "mapa/CatastroPredioEquidos/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
					
					$rutaArchivo =  "aplicaciones/programasControlOficial/mapa/CatastroPredioEquidos/".$nuevo_nombre;
					$cpco->actualizarImagenMapaCatastroPredioEquidos($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				
				//Archivos de Informes
				case 'InformeMurcielagosHematofagos':{
					move_uploaded_file($archivo_temporal,  "informe/MurcielagosHematofagos/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
						
					$rutaArchivo =  "aplicaciones/programasControlOficial/informe/MurcielagosHematofagos/".$nuevo_nombre;
					$cpco->actualizarInformeMurcielagosHematofagos($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				case 'InformeControlVectores':{
					move_uploaded_file($archivo_temporal,  "informe/ControlVectores/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
						
					$rutaArchivo =  "aplicaciones/programasControlOficial/informe/ControlVectores/".$nuevo_nombre;
					$cpco->actualizarInformeControlVectores($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				case 'InformeInspeccionOCCS':{
					move_uploaded_file($archivo_temporal,  "informe/InspeccionOCCS/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
						
					$rutaArchivo =  "aplicaciones/programasControlOficial/informe/InspeccionOCCS/".$nuevo_nombre;
					$cpco->actualizarInformeInspeccionOCCS($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				case 'InformeCatastroPredioEquidos':{
					move_uploaded_file($archivo_temporal,  "informe/CatastroPredioEquidos/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
						
					$rutaArchivo =  "aplicaciones/programasControlOficial/informe/CatastroPredioEquidos/".$nuevo_nombre;
					$cpco->actualizarInformeCatastroPredioEquidos($conexion, $_POST['id'], $rutaArchivo);
					break;
				}
				default:{
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

