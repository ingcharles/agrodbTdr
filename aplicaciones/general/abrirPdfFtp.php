<?php

	$rutaArchivo = $_POST['ruta'];

	$host = '192.168.1.52';
	$user = 'anonymous';
	$pass = '';
	$remote_file = $rutaArchivo;
	$local_file = 'archivo.pdf';

	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename="archivo.pdf"');
	
	//conectarse al host
	$conn = @ftp_connect($host);
	
	//Comprobar que la conexión ha tenido éxito
	if (!$conn) {
		echo 'Error al tratar de conectar con ' . $host . "</br>";
		exit();
	}
	echo 'Conectado con ' . $host . "</br>";
	
	//Iniciamos sesión
	$login = @ftp_login($conn, $user, $pass);
	if (!$login) {
		echo 'Error al intentar acceder con el usuario ' . $user;
		ftp_quit($conn);
		exit();
	}
	echo 'Conectado con el usuario ' . $user . "</br>";
	
	//obtenemos el archivo del servidor
	if (ftp_get($conn, $local_file, $remote_file, FTP_BINARY)) {
		readfile($local_file);
		unlink($local_file);
		echo 'El archivo ' . $local_file . ' se ha guardado.' . "</br>";
	} else {
		echo 'El archivo ' . $local_file . ' NO se ha guardado.' . "</br>";
	}
	
	//Cerramos la conexion
	ftp_close($conn);

?>