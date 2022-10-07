<script src="<?php echo URL ?>modulos/ProcesosAdministrativosJuridico/vistas/js/juridico.js"></script>
<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico'	
data-opcion='procesoAdministrativo/reporteGeneral' draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte General</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
	
	<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico'	
	data-opcion='procesoAdministrativo/reporteDocumentos' draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte por Documentos</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>
<br/>
<script>
$(document).ready(function(){
	$("#listadoItems").removeClass("programas");
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un reporte para visualizar.</div>');
});
	
</script>
