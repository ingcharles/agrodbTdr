<?php
session_start();
require_once 'clases/Conexion.php';
require_once 'clases/ControladorAuditoria.php';

$conexion = new Conexion();

$ca = new ControladorAuditoria();

//---------------------------AUDITORIA

	//$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
	//$ca->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'), $_SESSION['usuario'], 'El usuario ' . $_SESSION['usuario'].'ha finalizado sesión');

//--------------------------------

session_destroy();

//header('Location: ../agrodbOut.html');							
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Salir Sistema GUIA</title>

<script src="aplicaciones/general/funciones/jquery-1.9.1.js"
	type="text/javascript"></script>
<link rel='stylesheet' href='aplicaciones/general/estilos/agrodb.css'>
</head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-97784251-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-97784251-2');
</script>											 
<body>
	<div id="ingreso">
		<table>

			<tr>
				<th><p>Su sesión ha finalizado con éxito</p></th>
			</tr>
			<tr>
				<td><p>
						Reingrese al sistema <b><a href="index.php">G.U.I.A.</a> </b>
					</p>
					<p>
						Acceda a la <a href="http://www.agrocalidad.gob.ec">página
							institucional</a>
					</p>
				</td>
			</tr>
			<tr>
				<td class="acerca">
					<p>Sistema Integrado</p>
					<p>Agrocalidad <?php echo date("Y")?></p>
					<p>Gestión tecnológica</p>
				</td>
			</tr>
		</table>
	</div>

</body>

</html>
