<header>
	<nav><?php echo $this->panelBusquedaNotificacionesReporteAdmin;?></nav>
</header>

<!-- Modal para datos del reporte -->
<div id="modalReporteNotificaciones" role="dialog" class="modal fade">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Reporte de Notificaciones</h4>
			</div>

			<div class="modal-body">

				<div id="divProforma">
					<table id="tablaItems">
						<thead>
							<tr>
								<th>Código de documento</th>
								<th>País que notifica</th>
								<th>Tipo de documento</th>
								<th>Fecha de notificación</th>
								<th>Productos</th>
								<th>Palabra clave</th>
								<th>Descripción</th>
								<th>Enlace</th>
								<th>Área temática</th>
								<th>Estado</th>
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
							<form id="filtrar" action="aplicaciones/mvc/NotificacionesFitosanitarias/DescargarNotificacion/exportarListaExcel" target="_blank" method="post">
								<input id="fechaNotificacionInicio" type="hidden" name="fechaNotificacionInicio" style="width: 100%">
								<input id="fechaNotificacionFin" type="hidden" name="fechaNotificacionFin" style="width: 100%">  
								<input id="fechaCierre" type="hidden" name="fechaCierre" style="width: 100%"> 
								<input id="idPais" type="hidden" name="idPais" style="width: 100%"> 
								<input id="tipoDocumento" type="hidden" name="tipoDocumento" style="width: 100%"> 
								<input id="productoNotificacion" type="hidden" name="productoNotificacion" style="width: 100%">
								<input id="areaTematica" type="hidden" name="areaTematica" style="width: 100%"> 
								<input id="estadoReporte" type="hidden" name="estadoReporte" style="width: 100%"> 
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
		 $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	});

	
    $("#btnFiltrar").click(function (event) {
     event.preventDefault(); 
     fn_limpiar();
        fn_filtrar();
    });

    function fn_filtrar() {   
    	event.preventDefault();
    	 var error = false;
    
         if(!$.trim($("#fechaNotificacionInicioFiltro").val())){
			error = true;
			$("#fechaNotificacionInicioFiltro").addClass("alertaCombo");
		}

         if(!$.trim($("#fechaNotificacionFinFiltro").val())){
 			error = true;
 			$("#fechaNotificacionFinFiltro").addClass("alertaCombo");
 		}
    		
         if (!$.trim($("#idPaisFiltro").val())) {
         	error = true;
    			$("#idPaisFiltro").addClass("alertaCombo");
         }
      
    	if (!error) { 
            $("#fechaNotificacionInicio").val($("#fechaNotificacionInicioFiltro").val());
            $("#fechaNotificacionFin").val($("#fechaNotificacionFinFiltro").val());
            $("#fechaCierre").val($("#fechaCierreFiltro").val());
           
            $("#idPais").val($("#idPaisFiltro").val());
            $("#tipoDocumento").val($("#tipoDocumentoFiltro").val());
            $("#productoNotificacion").val($("#productoNotificacionFiltro").val());
            $("#areaTematica").val($("#areaTematicaFiltro").val());
            $("#estadoReporte").val($("#estadoReporteFiltro").val());
            
    		$.post("<?php echo URL ?>NotificacionesFitosanitarias/DescargarNotificacion/mostrarReporteNotificaciones",    		  
                {
                        fechaNotificacionInicio: $("#fechaNotificacionInicioFiltro").val(),
                        fechaNotificacionFin: $("#fechaNotificacionFinFiltro").val(),
                        fechaCierre: $("#fechaCierreFiltro").val(),
                      
                        idPais: $("#idPaisFiltro").val(),
                        tipoDocumento: $("#tipoDocumentoFiltro").val(),
                        productoNotificacion: $("#productoNotificacionFiltro").val(),
                        areaTematica: $("#areaTematicaFiltro").val(),
                        estadoReporte: $("#estadoReporteFiltro").val()
		        },
    		    function (data) {
    	        	if (data.estado === 'FALLO') {
    	                mostrarMensaje(data.mensaje, "FALLO");
                    } else {
                    	construirPaginacion($("#modalReporteNotificaciones tablaItems tbody"), JSON.parse(data.contenido));
                    	$('#modalReporteNotificaciones').modal('show');
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

	$("#fechaNotificacionInicioFiltro").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaNotificacionInicioFiltro').datepicker('getDate')); 
        	var fechaCierre=new Date($('#fechaNotificacionInicioFiltro').datepicker('getDate'));
        	
        	fecha.setDate(fecha.getDate()+30);	 
      		$('#fechaNotificacionFinFiltro').datepicker('option', 'minDate', $("#fechaNotificacionInicioFiltro" ).val());
      		$('#fechaNotificacionFinFiltro').datepicker('option', 'maxDate', fecha);
      		
      		fechaCierre.setDate(fecha.getDate()+60);
      		$('#fechaRevisionFiltro').datepicker('option', 'minDate', $("#fechaNotificacionInicioFiltro" ).val());
      		$('#fechaRevisionFiltro').datepicker('option', 'maxDate', fechaCierre);
      		
      		$('#fechaCierreFiltro').datepicker('option', 'minDate', $("#fechaNotificacionInicioFiltro" ).val());
      		$('#fechaCierreFiltro').datepicker('option', 'maxDate', fechaCierre);
	    }
	 });

	$("#fechaNotificacionFinFiltro").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaNotificacionFiltro').datepicker('getDate')); 
	    }
	 });

	$("#fechaCierreFiltro").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
	    	var fecha=new Date($('#fechaCierreFiltro').datepicker('getDate')); 
	    }
	 });
         
    $("#fechaRevisionFiltro").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
	    	var fecha=new Date($('#fechaRevisionFiltro').datepicker('getDate')); 
	    }
	});
</script>