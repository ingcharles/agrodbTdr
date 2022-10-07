<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();

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

	$numero_aleatorio = rand(1,1000);
	$archivo_nombre = $_FILES['archivo']['name'];
	$archivo_peso = $_FILES['archivo']['size'];
	$archivo_temporal = $_FILES['archivo']['tmp_name'];
	$archivo_error = $_FILES['archivo']['error'];
	$archivo_tipo = $_FILES['archivo']['type'];
	$extension = explode(".", $archivo_nombre);
	$fecha = date("Y-m-d-H-i-s");
	$nuevo_nombre = $numero_aleatorio.'-'.$_POST['identificador'].'-'.$fecha.'.'.end($extension);
	$idOperador = $_POST['identificador'];
	
	if (strtoupper((end($extension))) == "XML"  && ($archivo_peso < 3145728) ) {
		if ($archivo_error > 0) {
			echo '<p class="exito">Se produjo el error al cargar el archivo'. $archivo_error.'</p>';
		} else {
						
			move_uploaded_file($archivo_temporal,"documentos/".$nuevo_nombre);
			
			$rutaArchivo =  "aplicaciones/certificadosFitosanitarios/documentos/".$nuevo_nombre;
			$xmlFitosanitario = @simplexml_load_file("documentos/".$nuevo_nombre);
											

			 $idAgencia= $xmlFitosanitario->idAgencia;
			 $nombreAgencia= $xmlFitosanitario->nombreAgencia;
			 $serialArchivo = $xmlFitosanitario->serial;
			 $parcialArchivoAgencia = $xmlFitosanitario->parcialArchivo;
			 
			 $estado = true;
			 
			 
		if($idAgencia == $idOperador) {
				
				$qIdArchivo = $cc->buscarArchivo($conexion, $idAgencia, $serialArchivo);
				
			if(pg_num_rows($qIdArchivo) == 0){
				
				$idArchivo = $cc -> guardarNuevoArchivoFitosanitario($conexion,$idAgencia,$nombreAgencia, $serialArchivo);
				$idArchivoFitosanitario = pg_fetch_result($idArchivo, 0, 'id_archivo_fitosanitario');
				
			}else{
				$idArchivoFitosanitario = pg_fetch_result($qIdArchivo, 0, 'id_archivo_fitosanitario');
			}
			
			$qparcialArchivo = $cc->buscarArchiParcial($conexion, $idArchivoFitosanitario, $parcialArchivoAgencia);
			
			if(pg_num_rows($qparcialArchivo)==0){
					
				$idArchivoPacial = $cc -> guardarNuevoArchivoFitosanitarioParcial($conexion,$idArchivoFitosanitario,$parcialArchivoAgencia, $rutaArchivo);
					
			}else{
				$estado = false;
			}
			
			if($estado){
				
				foreach ($xmlFitosanitario->importador as $objImportador){
					
					$qIdImportador = $cc -> buscarImportador($conexion,$objImportador->idPaisDestino,$objImportador->nombreDestinatario,$idArchivoFitosanitario);
					
					if(pg_num_rows($qIdImportador)==0){
						$idTmpImportador = $cc -> guardarImportador($conexion,$idArchivoFitosanitario,$objImportador->idPaisDestino,$objImportador->nombrePaisDestino,$objImportador->nombreDestinatario,$objImportador->direccionDestinatario);
						$idImportador = pg_fetch_result($idTmpImportador, 0, 'id_importador');
					}else{
						$idImportador = pg_fetch_result($qIdImportador, 0, 'id_importador');
					}

					foreach ($objImportador->exportador as $objExportador){
						$idCabeceraFitosanitario = $cc -> guardarCabeceraFitosanitario($conexion,$idImportador,$objExportador->idExportador,$objExportador->idPuertoEntrada,$objExportador->idPaisOrigen,$objExportador->idProvinciaOrigen,$objExportador->idTransporte,$objExportador->numeroBulto,$objExportador->descripcionBulto,$objExportador->declaracionAdicional,$objExportador->fechaTratamiento,$objExportador->tratamiento,$objExportador->productoQuimico,$objExportador->duracion,$objExportador->descripcionDuracion,$objExportador->temperatura,$objExportador->descripcionTemperatura,$objExportador->concentracion,$objExportador->descripcionConcentracion);
						$idTmpFitosanitario = pg_fetch_result($idCabeceraFitosanitario, 0, 'id_tmp_fitosanitario');
				
						foreach ($objExportador->productos->producto as $objProducto){
				
							$idDetalleFitosanitario = $cc ->guardarDetalleFitosanitario($conexion,$idTmpFitosanitario,$objProducto->idProducto,$objProducto->partidaArancelaria,$objProducto->nombreProducto,$objProducto->marca,$objProducto->cantidad,$objProducto->unidad,$objProducto->descripcion);
						}
				
					}
				
				}

			}else{
				echo '<p class="alerta">El archivo que intenta subir, ya ha sido cargado al sistema.</p>';
			}
			 
		 	 echo'<p class="exito">Archivo cargado exitosamente.</p>';
			  
		}else 
			echo '<p class="alerta">El archivo subido no le corresponde a esta agencia.</p>'; 				 		
	   }   
	}
	
	else {
		echo "El archivo seleccionado no es permitido o es muy grande.";
	}	

?>

</body>
</html>




