<header>
	<nav><?php echo $this->panelBusquedaRevisiones;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Tipo</th>
			<th>CFE</th>
			<th>País</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
var combo = "<option>Seleccione....</option>";

    $(document).ready(function () {
    	construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
    	$("#listadoItems").removeClass("comunes");
    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
    });

	$("#tipoRevisionFiltro").change(function () {
    	$("#identificadorSolicitanteFiltro").html(combo);
    	
        if ($("#tipoRevisionFiltro option:selected").val() !== "") {
        	fn_cargarSolicitantes();
        }else{
        	$("#identificadorSolicitanteFiltro").html(combo);
        }
    });

	$("#identificadorSolicitanteFiltro").change(function () {
    	$("#idPaisDestinoFiltro").html(combo);
    	
        if ($("#tipoRevisionFiltro option:selected").val() !== "" && $("#identificadorSolicitanteFiltro option:selected").val() !== "") {
        	fn_cargarPaisesSolicitante();
        }else{
        	$("#idPaisDestinoFiltro").html(combo);
        }
    });

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});

	

	//Lista de operadores por fase de revisión
	function fn_cargarSolicitantes() {
        var revision = $("#tipoRevisionFiltro option:selected").val();
        
        if (revision !== '') {
        	$.post("<?php echo URL ?>CertificadoFitosanitario/Revisiones/comboSolicitantesPorFaseRevision/", 
			{
        		faseRevision : revision
			},
            function (data) {
                $("#identificadorSolicitanteFiltro").html(combo+data);
            });
        }else{
        	$("#identificadorSolicitanteFiltro").html(combo);
        }
    }

	//Lista de países por solicitudes de operadores por fase de revisión
	function fn_cargarPaisesSolicitante() {
        var revision = $("#tipoRevisionFiltro option:selected").val();
        var solicitante = $("#identificadorSolicitanteFiltro option:selected").val();
        
        if (revision !== '' && solicitante !== '') {
        	$.post("<?php echo URL ?>CertificadoFitosanitario/Revisiones/comboPaisesSolicitantePorFaseRevision/", 
			{
        		faseRevision : revision,
        		identificadorSolicitante : solicitante
			},
            function (data) {
                $("#idPaisDestinoFiltro").html(combo+data);
            });
        }else{
        	$("#idPaisDestinoFiltro").html(combo);
        }
    }

	//Función para realizar la búsqueda de solicitudes CFE con los parámetros de búsqueda especificados
	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;
		
		if(!$.trim($("#tipoRevisionFiltro").val())){
			$("#tipoRevisionFiltro").addClass("alertaCombo");
			error = true;
		}
		        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>CertificadoFitosanitario/Revisiones/listarSolicitudesRevisionFiltradas",
		    	{
				  	faseRevision: $("#tipoRevisionFiltro option:selected").val(),
				  	identificadorSolicitante: $("#identificadorSolicitanteFiltro option:selected").val(),
				  	numeroCertificado: $("#numeroCertificadoFiltro").val(),
				  	idPaisDestino: $("#idPaisDestinoFiltro option:selected").val(),
				  	idMedioTransporte: $("#idMedioTransporteFiltro option:selected").val()
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                }
		        }, 'json');
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}		
	}

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
</script>