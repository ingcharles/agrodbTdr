<header>
	<nav><?php echo $this->panelBusquedaTramitesReporteAnalista;?></nav>
</header>

<!-- Modal para datos del reporte -->
<div id="modalReporteTramites" role="dialog" class="modal fade" >
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reporte de Trámites</h4>
            </div>
            
            <div class="modal-body">

                <div id="divProforma">
					<table id="tablaItems">
                    	<thead>
                    		<tr>
                    			<th>Id</th>
                    		    <th>Número</th>
                    		    <th>Fecha creación</th>
                    		    <th>Ventanilla</th>
                    		    <th>Técnico</th>
                    		    <th>Remitente</th>
                    		    <th>Oficio - Memo</th>
                    		    <th>Factura</th>
                    		    <th>Guía - Correo</th>
                    		    <th>Asunto</th>
                    		    <th>Anexos</th>
                    		    <th>Destinatario</th>
                    		    <th>Unidad de Destino</th>
                    		    <th>Quipux Agrocalidad</th>
                    		    <th>Derivado</th>
                    		    <th>Estado</th>
                    		    <th>Documentos entregados</th>
                    		    <th>Fecha de entrega</th>
                    		    <th>Observaciones</th>
                    		    <th>Origen trámite</th>
                    		</tr>
                    	</thead>
                    	<tbody></tbody>
                    </table>
                </div>
            </div>
            
            <div class="modal-footer">
            		<table>
                    	<tr>
                    		<td>
                    			<form id="filtrar" action="aplicaciones/mvc/SeguimientoDocumental/Tramites/exportarListaExcelAdministrador" target="_blank" method="post">
                    				<input id="idVentanillaReporte" type="hidden" name="idVentanillaReporte" style="width: 100%" >
                    				<input id="idUnidadDestinoReporte" type="hidden" name="idUnidadDestinoReporte" style="width: 100%" >
                    				<input id="fechaInicioReporte" type="hidden" name="fechaInicioReporte" style="width: 100%" >
                    				<input id="fechaFinReporte" type="hidden" name="fechaFinReporte" style="width: 100%" >
                    				<input id="estadoTramiteReporte" type="hidden" name="estadoTramiteReporte" style="width: 100%" >
                    				                    				
                            		<button id="btnExcel">Exportar xls</button>
                            	</form>
                    		</td>
                    		
                    		<td>
                    			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    		</td>
                    	</tr>
                    </table>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function () {
		construirValidador();
	});

	$("#btnFiltrar").click(function () {
		event.preventDefault();
		fn_filtrar();
	});

	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;
		
		if(!$.trim($("#fechaInicio").val())){
			error = true;
			$("#fechaInicio").addClass("alertaCombo");
		}
		
        if (!$.trim($("#fechaFin").val())) {
        	error = true;
			$("#fechaFin").addClass("alertaCombo");
        }
        
		if (!error) {
			$("#idVentanillaReporte").val($("#idVentanillaFiltro").val());
			$("#idUnidadDestinoReporte").val($("#idUnidadDestinoFiltro").val());
			$("#fechaInicioReporte").val($("#fechaInicio").val());
			$("#fechaFinReporte").val($("#fechaFin").val());
			$("#estadoTramiteReporte").val($("#estadoTramite").val()); 
			
			$.post("<?php echo URL ?>SeguimientoDocumental/Reportes/mostrarReporteTramites",
			    	{
					  	idVentanillaFiltro: $("#idVentanillaFiltro").val(),
					  	idUnidadDestinoFiltro: $("#idUnidadDestinoFiltro").val(),
					  	fechaInicio: $("#fechaInicio").val(),
					  	fechaFin: $("#fechaFin").val(),
					  	estadoTramite: $("#estadoTramite").val()
			        },
			      	function (data) {
			        	if (data.estado === 'FALLO') {
	    	                mostrarMensaje(data.mensaje, "FALLO");
		                } else {
		                	construirPaginacion($("#modalReporteTramites tablaItems tbody"), JSON.parse(data.contenido));
		                	$('#modalReporteTramites').modal('show');
		                }
			        }, 'json');
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}				
	};

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}

	$("#fechaInicio").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+180);	 
      		$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val());
      		$('#fechaFin').datepicker('option', 'maxDate', fecha);
	    }
	 });

	$("#fechaFin").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
	    }
	 });
</script>