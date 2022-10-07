<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEventoSanitario.php';
require_once '../../clases/ControladorEventoSanitario.php';
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
		$cpco = new ControladorNotificacionEventoSanitario();
		$ces = new ControladorEventoSanitario();

		$numero_aleatorio = rand(1,1000);
		
		$archivo_nombre = $_FILES['archivo']['name'];
		$archivo_peso = $_FILES['archivo']['size'];
		$archivo_temporal = $_FILES['archivo']['tmp_name'];
		$archivo_error = $_FILES['archivo']['error'];
		$archivo_tipo = $_FILES['archivo']['type'];
		$extension = explode(".", $archivo_nombre);
		$aplicacion = $_POST['aplicacion'];
		$numVisita = $_POST['numeroVisita'];
		
		$nuevo_nombre = $numero_aleatorio.'_'.$_POST['id'].'.'.end($extension);
		
		
		if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
			if ($archivo_error > 0) {
				echo $archivo_error . "<br />";
			} else {
							
				switch ($aplicacion){ 
					//Archivos de Informes
					case 'idNotificacionEventoSanitario':{
						move_uploaded_file($archivo_temporal,  "informe/notificacionEventoSanitario/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
							
						$rutaArchivo =  "aplicaciones/seguimientoEventosSanitarios/informe/notificacionEventoSanitario/".$nuevo_nombre;
						$cpco->actualizarInformeNotificacionEventoSanitario($conexion, $_POST['id'], $rutaArchivo);
						break;
					}
					//Archivos de Mapa
					case 'archivoMapa':{
						move_uploaded_file($archivo_temporal,  "eventoSanitario/mapa/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
							
						$rutaArchivo =  "aplicaciones/seguimientoEventosSanitarios/eventoSanitario/mapa/".$nuevo_nombre;
						$ces->actualizarMapaPVEventoSanitario($conexion, $_POST['id'], $rutaArchivo);
						break;
					}
					//Archivo Imagen
					case 'archivoImagen':{
						move_uploaded_file($archivo_temporal,  "eventoSanitario/imagenes/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
							
						$rutaArchivo =  "aplicaciones/seguimientoEventosSanitarios/eventoSanitario/imagenes/".$nuevo_nombre;
						$ces->actualizarImagenesPVEventoSanitario($conexion, $_POST['id'], $rutaArchivo);
						break;
					}
					//Archivos Documentos
					case 'archivoDocumentos':{
						move_uploaded_file($archivo_temporal,  "eventoSanitario/documentos/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
							
						$rutaArchivo =  "aplicaciones/seguimientoEventosSanitarios/eventoSanitario/documentos/".$nuevo_nombre;
						$ces->actualizarDocumentosEventoSanitario($conexion, $_POST['id'], $numVisita, $rutaArchivo);
						break;
					}
					//Archivos de Acta
					case 'archivoActa':{
						move_uploaded_file($archivo_temporal,  "acta/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
							
						$rutaArchivo =  "aplicaciones/seguimientoEventosSanitarios/acta/".$nuevo_nombre;
						$ces->actualizarActaEventoSanitario($conexion, $_POST['id'], $rutaArchivo);
						break;
					}
					//Archivo Informe Final
					case 'archivoInformeCierre':{
						move_uploaded_file($archivo_temporal,  "informeCierre/".$nuevo_nombre);
						echo'<p class="exito">¡Archivo cargado!</p>';
							
						$rutaArchivo =  "aplicaciones/seguimientoEventosSanitarios/informeCierre/".$nuevo_nombre;
						$ces->actualizarInformeCierreEventoSanitario($conexion, $_POST['id'], $rutaArchivo);
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

