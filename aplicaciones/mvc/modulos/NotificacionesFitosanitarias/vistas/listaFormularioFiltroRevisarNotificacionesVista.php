<header>
<script src="<?php echo URL ?>modulos/NotificacionesFitosanitarias/vistas/js/funcionCf.js"></script>
	<nav><?php echo $this->panelBusqueda; ?></nav>
        <nav><?php echo $this->botones;?></nav>
</header>
<br>
    <div>
        <h1><?php echo $this->detalleFormulario; ?></h1>
	<div class="elementos"></div>
    </div>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Cód. Documento</th>
			<th>País notifica</th>
			<th>Producto</th>
			<th>F.notificación</th>
			<th>F.cierre</th>
			
		</tr>
	</thead>
	<tbody></tbody>
</table>
<script>
    $(document).ready(function () {
        construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
	$("#listadoItems").removeClass("comunes"); 
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
        $("#btnExcel").hide(); 
    });
    
    $("#btnFiltrarLista").click(function (event) {
		event.preventDefault();
		//fn_filtrar();
	});
    
    function fn_filtrar() { 
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>NotificacionesFitosanitarias/ListaNotificacion/listarNotificacionesFiltradas",
	    	{
			    idListaNotificacion: $("#idListaNotificacion").val(),
                            codDocumento: $("#codDocumento").val(),
			    fechaNotificacion: $("#fechaNotificacion").val(),
			    idPais: $("#idPais").val(),
			    tipoDocumento: $("#tipoDocumento").val()
	        },
	      	function (data) {
	        	if (data.estado === 'FALLO') {
                	mostrarMensaje(data.mensaje, "FALLO");
                } else {
                	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
                	$("#btnExcel").show();
                }
	        }, 'json');
     }    
        
        
        
    
    function fn_filtrar_datos() {
		event.preventDefault();
		fn_limpiar();

		var error = false;
//		var f = new Date();
//
//		$("#fechaInicio").val(f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear()); 
//                $("#fechaFin").val(f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear());

		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>NotificacionesFitosanitarias/listaNotificacion/listarTramitesFiltrados",
		    	{
				    idVentanillaFiltro: $("#idVentanillaFiltro").val(),
				    numTramite: $("#numTramite").val(),
				    nombreRemitente: $("#nombreRemitente").val(),
				    nombreDestinatario: $("#nombreDestinatario").val(),
				    numQuipux: $("#numQuipux").val(),
				    numFactura: $("#numFactura").val(),
				    idUnidadDestinoFiltro: $("#idUnidadDestinoFiltro").val(),
				    fechaInicio: $("#fechaInicio").val(),
				    fechaFin: $("#fechaFin").val(),
				    estadoTramite: $("#estadoTramite").val()
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                	$("#btnExcel").show();
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

