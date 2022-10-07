<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCertificados.php';

	$identificador = $_SESSION['usuario'];
	
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
<h1>Subir archivo</h1>
<nav>

<form id="subirArchivoXml" action="aplicaciones/certificadosFitosanitarios/subirArchivo.php" method="post" enctype="multipart/form-data" target="archivoXml" onsubmit="upload()">
	<input type="file" name="archivo" id="archivo" accept="application/xml"/>
	<input name="identificador" value="<?php echo $identificador;?>" type="hidden"/> 
	<button type="submit" id="boton" name="boton" class="adjunto" >Subir archivo</button>	 
</form>
<iframe name="archivoXml" class="ventanaEmergente"></iframe>

<form id="reporteCertificados" data-rutaAplicacion="certificadosFitosanitarios" data-opcion="listaCertificadosReporte" data-destino="tabla">
	<table id="Certificados">
		<tr>
			<td colspan="1"><button>Mostrar certificados</button></td>
		</tr>
	</table>
</form>
</nav>
</header>
<div id="tabla"></div>

</body>

<script type="text/javascript"> 

	$("#subirArchivo button[type='submit']").click(function(e){

		abrir($(this),event,false);
	});

	$("#reporteCertificados").submit(function(e){
		abrir($(this),e,false);
	});

</script>
</html>
