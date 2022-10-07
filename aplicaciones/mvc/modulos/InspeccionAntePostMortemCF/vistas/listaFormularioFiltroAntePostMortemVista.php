<header>
<script src="<?php echo URL ?>modulos/InspeccionAntePostMortemCF/vistas/js/funcionCf.js"></script>
	<nav><?php
	echo $this->panelBusqueda;
	?></nav><br/>
</header>
<br>
    <div >
	    <h1><?php echo $this->detalleFormulario; ?></h1>
		<div class="elementos"></div>
	</div>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>C. Faenamiento</th>
			<th>Fecha</th>
			<th>Código formulario</th>
			<th>Nro. GUIA</th>
			<th>T. Aves/Especie</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>
	
<script>
	var idProceso = <?php echo json_encode($this->idProceso);?>;
	var fechaInical = <?php echo json_encode($this->fechaInicial);?>;
	var fechaFinal = <?php echo json_encode($this->fechaFinal);?>;
	var opcion = <?php echo json_encode($this->opcion);?>;
	
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); 
	    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	    establecerFechasMes('fecha',fechaInical,fechaFinal);
	    $("#csmi").numeric();
	    });

	//Cuando se presiona en Filtrar lista, debe cargar los datos
    $("#btnFiltrar").click(function () {
		fn_filtrar();
	});
	// Función para filtrar
	function fn_filtrar() {
		if(opcion){
		    var prov = $("#id_provincia option:selected").text();
		}else{
			var prov = 'Seleccionar...';
			}
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
	    $.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioReporteAntePostMortem/filtrarInformacion",
	    	{
	        	provincia: prov,
	        	cFaenamiento: $("#cFaenamiento").val(),
	        	fecha: $("#fecha").val(),
	        	csmi: $("#csmi").val(),
	        	codFormulario: $("#codFormulario").val(),
	        	tipo_especie: $("#tipo_especie").val(),
	        	idProceso: idProceso
	        },
	      	function (data) {
	        	 $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	            construirPaginacion($("#paginacion"), JSON.parse(data));
	        });
	    }
	
</script>
