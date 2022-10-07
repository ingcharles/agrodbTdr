<header>
	<nav><?php echo $this->panelBusquedaValijasReporteAdmin;?></nav>
</header>

<!-- Modal para datos del reporte -->
<div id="modalReporteValijas" role="dialog" class="modal fade" >
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reporte de Valijas</h4>
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
                    		    <th>Guía de Correo</th>
                    		    <th>Área Origen</th>
                    		    <th>Remitente</th>
                    		    <th>Destinatario</th>
                    		    <th>Dirección</th>
                    		    <th>Teléfono</th>
                    		    <th>País</th>
                    		    <th>Provincia</th>
                    		    <th>Cantón</th>
                    		    <th>Referencia</th>
                    		    <th>E-mail</th>
                    		    <th>Descripción</th>
                    		    <th>Estado</th>
                    		    <th>Persona que recibe</th>
                    		    <th>Fecha de Entrega</th>
                    		    <th>Observaciones</th>
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
                    			<form id="filtrar" action="aplicaciones/mvc/SeguimientoDocumental/Valijas/exportarListaExcelAdministrador" target="_blank" method="post">
                    				<input id="idVentanillaReporte" type="hidden" name="idVentanillaReporte" style="width: 100%" >
                    				<input id="numGuiaReporte" type="hidden" name="numGuiaReporte" style="width: 100%" >
                    				<input id="fechaInicioReporte" type="hidden" name="fechaInicioReporte" style="width: 100%" >
                    				<input id="fechaFinReporte" type="hidden" name="fechaFinReporte" style="width: 100%" >
                    				<input id="estadoEntregaReporte" type="hidden" name="estadoEntregaReporte" style="width: 100%" >
                    				                    				
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
			$("#numGuiaReporte").val($("#numGuia").val());
			$("#fechaInicioReporte").val($("#fechaInicio").val());
			$("#fechaFinReporte").val($("#fechaFin").val());
			$("#estadoEntregaReporte").val($("#estadoEntrega").val());
			
			$.post("<?php echo URL ?>SeguimientoDocumental/Reportes/mostrarReporteValijas",
			    	{
					  	idVentanillaFiltro: $("#idVentanillaFiltro").val(),
					  	numGuia: $("#numGuia").val(),
					  	fechaInicio: $("#fechaInicio").val(),
					  	fechaFin: $("#fechaFin").val(),
					  	estadoEntrega: $("#estadoEntrega").val()
			        },
			      	function (data) {
			        	if (data.estado === 'FALLO') {
	    	                mostrarMensaje(data.mensaje, "FALLO");
		                } else {
		                	construirPaginacion($("#modalReporteValijas tablaItems tbody"), JSON.parse(data.contenido));
		                	$('#modalReporteValijas').modal('show');
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