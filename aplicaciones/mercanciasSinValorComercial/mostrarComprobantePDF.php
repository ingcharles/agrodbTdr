<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorMercanciasSinValorComercial.php';
	
	$ce = new ControladorMercanciasSinValorComercial();
	$conexion = new Conexion();
	
	$idSolicitud = $_POST['idSolicitud'];
?>

<html>
	<body>
		<?php
			$res = $ce->cargarDocumentos($conexion,$idSolicitud);
			$certificado= pg_fetch_assoc($res);	
		?>
		
		<embed id="visor" src="<?php echo  $certificado['ruta_comprobante_veterinario']; ?>" width="540" height="550">
	</body>
	
</html>