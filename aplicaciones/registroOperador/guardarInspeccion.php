<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<pre>
<?php
	print_r ($_POST);
	
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idOperacion = ($_POST['idSolicitud']);
	$listaAreas = ($_POST['listaAreas']);
	$archivo = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$resultadoOperacion = htmlspecialchars ($_POST['resultado'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	
	
		for ($i=0; $i<count($listaAreas);$i++){
			//Guarda estado de área
			echo $idOperacion;
			echo $listaAreas[$i];
			echo $resultadoOperacion;
			echo $observaciones . '<br/><br/>';
				
			//Guarda inspector, calificación y fecha
			//$cr->guardarDatosInspeccion($conexion, $idOperacion, $listaAreas[$i], $inspector, $archivo, 'aprobado', $observaciones);
		}
	?>
</pre>
</body>
<script type="text/javascript">

	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);	
		abrir($("input:hidden"),null,false);
	});
				
</script>
</html>
