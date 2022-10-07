<header>
	<h1>Administración de Catálogos</h1>
</header>

<h2>Generales</h2>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='UnidadesMedidas/listarAdministracionUnidadesMedidas' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Unidades de Medida</span>
</article>




<h2>Registro de Productos RIA</h2>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='DeclaracionVenta/listarAdministracionDeclaracionVenta' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Declaración de Venta</span>
</article>

<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='CategoriaToxicologica/listarAdministracionCategoriaToxicologica' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Categoría Toxicológica</span>
</article>

<article id="2" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='IngredienteActivoInocuidad/listarAdministracionIngredienteActivo' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Nombre de Componente (Ingrediente Activo)</span>
</article>

<article id="3" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Usos/listarAdministracionUsos' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Usos</span>
</article>


<article id="4" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='TipoComponente/listarAdministracionTipoComponente' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Tipo de Componente</span>
</article>


<h2>Dossier Pecuario</h2>

<article id="0" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Clasificacion/listarAdministracionClasificacion' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Clasificación</span>
</article>

<article id="1" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='Formulacion/listarAdministracionFormulacion' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Forma Física, Farmacéutica, Cosmética (Formulación)</span>
</article>

<article id="2" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='EfectosBiologicos/listarAdministracionEfectosBiologicos' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Efectos Biológicos no deseados</span>
</article>

<article id="3" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='ProductosConsumibles/listarAdministracionProductosConsumibles' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Productos Consumibles</span>
</article>

<article id="4" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='ViaAdministracion/listarAdministracionViaAdministracion' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Vías de Administración</span>
</article>

<article id="5" class="item" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>Catalogos' data-opcion='AnexosPecuarios/listarAdministracionAnexosPecuarios' draggable="true"
	ondragstart="drag(event)" data-destino="listadoItems">
	<span>Anexos Pecuarios (Documentos anexos)</span>
</article>

<script>
	$(document).ready(function(){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
		$("#listadoItems").addClass("comunes");
	});
</script>
