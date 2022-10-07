<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	
	if(isset($_GET['archivo'])){
		$archivo = $_GET['archivo'];
	}else{
		$archivo = "noexiste";
	}
		
	$fichero = "C:/xampp/htdocs/agrodb/aplicaciones/certificadosFitosanitarios/documentos/$archivo";
			
	if (file_exists($fichero)) {
		header('Content-Type: application/pdf');
		if(isset($_GET['descargar'])){
				header("Content-Disposition:attachment ; filename=[$archivo.pdf");
		}
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		ob_clean();
		flush();
		readfile($fichero);
		exit;
	}else{
		echo "Archivo inexistente";
	 }
	
?>	
	
