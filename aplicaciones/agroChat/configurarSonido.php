<?php 

	$usuario = $_POST['usuario'];
	$valor= $_POST['opcion'];
	$lectura = fopen('chatOpciones.txt', 'r');
	$escritura = fopen('chatOpciones.tmp', 'w');
	
	$reemplazo = false;
	
	while (!feof($lectura)) {
		$linea = fgets($lectura);
		if (stristr($linea,$usuario)) {
			$linea = "$usuario=$valor".chr(13).chr(10);
			$reemplazo = true;
		}
		fwrite($escritura, $linea);
	}
	fclose($lectura); fclose($escritura);
	
	if ($reemplazo){
		rename('chatOpciones.tmp', 'chatOpciones.txt');
	} else {
		$archivo = fopen("chatOpciones.txt", "a+");
		//fputs($archivo,chr(13).chr(10));	
		fwrite($archivo,$usuario."=false".chr(13).chr(10));
		fclose($archivo);
		unlink('chatOpciones.tmp');
	}


?>