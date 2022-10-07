<header>
	<h1>Reportes Administrativos</h1>
</header>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='reportes/listarReporteTramitesAna' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Trámites Generados <?php echo $datosUsuario['ventanillaUsuario'];?></span>
</article>


<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='reportes/listarReporteValijasAna' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Valijas de Correo enviadas <?php echo $datosUsuario['ventanillaUsuario'];?></span>
</article>

<article id="2" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='reportes/listarReporteMensualValijasAna' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Informe mensual de Correspondiencia <?php echo $datosUsuario['ventanillaUsuario'];?></span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>