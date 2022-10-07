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
	
	$nuevo_nombre = $numero_aleatorio.'_'.$_POST['idVehiculo'].'_'.$_POST['boton'].'.'.end($extension);
	$idVehiculo = $_POST['idVehiculo'];
	$opcion = $_POST['boton'];
	
	$res = $cv-> abrirVehiculo($conexion, $_POST['placa']);
	$vehiculo= pg_fetch_assoc($res);
	
	
		switch ($opcion){
			case 'Frontal':   	if($vehiculo['imagen_frontal']!=""){
									unlink('../../'.$vehiculo['imagen_frontal']);
									break;
								}else break;
								
			case 'Posterior': 	if($vehiculo['imagen_trasera']!=""){
									unlink('../../'.$vehiculo['imagen_trasera']);
									break;
								}else break;
								
			case 'Derecha':   	if($vehiculo['imagen_derecha']!=""){
									unlink('../../'.$vehiculo['imagen_derecha']);
									break;
								}else break;
			case 'Izquierda': 	if($vehiculo['imagen_izquierda']!=""){
									unlink('../../'.$vehiculo['imagen_izquierda']);
									break;
								}else break;
		}
	
		if($opcion == "factura"){
			
			if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
				if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else {
					move_uploaded_file($archivo_temporal,  "factura/".$nuevo_nombre);
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/factura/".$nuevo_nombre;
					$cv->actualizarFactura($conexion, $_POST['id'], $rutaArchivo);
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}
			
		}
		
		else if($opcion == "lavado"){
				
			if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
				if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else {
					
					$lavada= $_POST['id'];
					
					for ($i = 0; $i < count ($lavada); $i++) {
						$tmp= explode("-",$lavada[$i]);
						$numeroLavado = $numeroLavado.'-'.trim(end($tmp),'0');
					}
					
					move_uploaded_file($archivo_temporal,  "factura/lavado".$numeroLavado.".pdf");
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/factura/lavado".$numeroLavado.'.pdf';
					
					for ($i = 0; $i < count ($lavada); $i++) {
						$cv->actualizarFactura($conexion, $lavada[$i], $rutaArchivo);
					}
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}
				
		}
		
		else if($opcion == "comprobanteGasolinera"){
		
			if (($archivo_tipo == "application/pdf" ) && ($archivo_peso < 3145728)) {
				if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else {
						
					$comprobanteGasolinera= $_POST['id'];
						
					/*for ($i = 0; $i < count ($comprobanteGasolinera); $i++) {
						$tmp= explode("-",$comprobanteGasolinera[$i]);
						$numeroComprobante = $numeroComprobante.'-'.trim(end($tmp),'0');
					}*/
						
					move_uploaded_file($archivo_temporal,  "comprobante/combustible/".$comprobanteGasolinera.".pdf");
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/comprobante/combustible/".$comprobanteGasolinera.'.pdf';
						
					//for ($i = 0; $i < count ($comprobanteGasolinera); $i++) {
						$cv->actualizarComprobanteGasolinera($conexion, $comprobanteGasolinera/*[$i]*/, $rutaArchivo);
					//}
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}
		
		}
		
		else{
			
				if (($archivo_tipo == "image/jpeg" ) && ($archivo_peso < 3145728)) {
					if ($archivo_error > 0) {
					echo $archivo_error . "<br />";
				} else {
					move_uploaded_file($archivo_temporal,  "fotos/".$nuevo_nombre);
					echo'<figure>';
					echo'<img src="fotos/'.$nuevo_nombre.'"/>';
					echo'<figcaption>'.$opcion.'</figcaption>';
					echo'</figure>';
					echo'<p class="exito">¡Archivo cargado!</p>';
					$rutaArchivo =  "aplicaciones/transportes/fotos/".$nuevo_nombre;
					$cv->actualizarFoto($conexion, $idVehiculo, $rutaArchivo, $opcion);
				}
			} else {
				echo "El archivo seleccionado no es permitido o es muy grande.";
			}	
		}
	?>
	
</body>


</html>

