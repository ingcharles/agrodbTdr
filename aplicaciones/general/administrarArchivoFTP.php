<?php

class administrarArchivoFTP{
	
	public function conexionFTP(){
	
		$rutaArchivo = $_POST['ruta'];
	
		//$host = '192.168.1.52';
		//$user = 'anonymous';
		//$pass = '';
		
		/*$host = '192.168.1.7';
		$user = 'FtpAGR';
		$pass = 'MAGAPAdmin13';*/
		
		$host = '192.168.200.9';
		$user = 'ftpuser';
		$pass = 'Agrocalidad8';
		
		/*$host = '192.168.200.8';
		 $user = 'ftpuser';
		$pass = 'Agro2016*';*/
		
		//ftp://FtpAGR:MAGAPAdmin13@192.168.1.7/
	
		//conectarse al host
		$conn = @ftp_connect($host);
	
		//Comprobar que la conexión ha tenido éxito
		if (!$conn) {
			exit();
		}
	
		//Iniciamos sesión
		$login = @ftp_login($conn, $user, $pass);
		if (!$login) {
			ftp_quit($conn);
			exit();
		}
		
		ftp_pasv($conn, true);
		
		return $conn;
	
	}
	
	public function enviarArchivo($ruta, $nombreArchivo, $formulario){
		
		$conn = $this->conexionFTP();
		
		$rutaRemoto = 'documentosGUIA/'.$formulario.'/'.$nombreArchivo;
		$rutaLocal= '../../'.$ruta;
		
		ftp_put($conn, $rutaRemoto, $rutaLocal, FTP_BINARY);
		
		ftp_close($conn);
		
	}
	
}


?>