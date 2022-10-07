<?php 
  session_start();
  require_once '../../clases/Conexion.php';
  require_once '../../clases/ControladorAplicaciones.php';
  require_once '../../clases/ControladorCertificados.php';
  
  $conexion = new Conexion();
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Unidad administrativa</h1>
</header>

	<article id="SV-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero"	data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Sanidad vegetal</span>
		<aside></aside>
	</article>
	
	<article id="SA-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero" data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Sanidad animal</span>
		<aside></aside>
	</article>
	
	<article id="IA-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero"	data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Inocuidad de los alimentos</span>
		<aside></aside>
	</article>
	
	<article id="LT-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero"	data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Analisis laboratorio</span>
		<aside></aside>
	</article>

	<article id="CGRIA-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero"	data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Control registro de insumos agropecuarios</span>
		<aside></aside>
	</article>
	
	<article id="AGR-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero"	data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Otros ingresos</span>
		<aside></aside>
	</article>
	
	<article id="COMEX-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero"	data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Comercio exterior</span>
		<aside></aside>
	</article>
	
	<article id="GENER-<?php echo $_POST["opcion"];?>" class="item" data-rutaAplicacion="financiero"	data-opcion="listaServicio" draggable="true" ondragstart="drag(event)" data-destino="listadoItems">
		<div></div>
		<span>Tarifario antiguo</span>
		<aside></aside>
	</article>	
	
</body>
<script>

$(document).ready(function(){

	$("#listadoItems").removeClass("programas");
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para visualizar.</div>');
	
});

</script>
</html>
