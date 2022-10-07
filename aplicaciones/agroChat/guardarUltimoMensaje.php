<?php 

	$usuario = $_POST['usuario'];
	$valor= $_POST['opcion'];
	$lectura = fopen('chatOpciones.txt', 'r');
	$escritura = fopen('chatOpciones.tmp', 'w');	
	$reemplazo = false;
	
	while (!feof($lectura)) {
		$line = fgets($lectura);
		if (stristr($line,$usuario)) {
			$line = $line."$usuario=$valor\n";
			$reemplazo = true;
		}
		fwrite($escritura, $line);
	}
	fclose($lectura); fclose($escritura);
	
	if ($reemplazo){
		rename('chatOpciones.tmp', 'chatOpciones.txt');
	} else {
		$archivo = fopen("chatOpciones.txt", "a+");
		fputs($archivo,chr(13).chr(10));	
		fwrite($archivo,$usuario."=false");
		fclose($archivo);
		unlink('chatOpciones.tmp');
	}
?>