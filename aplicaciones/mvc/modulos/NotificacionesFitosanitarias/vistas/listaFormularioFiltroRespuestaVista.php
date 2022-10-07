<header>
    <nav><?php echo $this->panelBusqueda; ?></nav>
    <nav><?php echo $this->botones; ?></nav>
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
            <th>Cód. Documento</th>
            <th>País notifica</th>
            <th>Producto</th>
            <th>Área temática</th>
            <th>F.notificación</th>
            <th>F.cierre</th>
            <!-- th id="respuesta">Respuesta</th-->
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
var perfil = <?php echo json_encode($this->perfilUsuario); ?>;

    $(document).ready(function () {
    	if(perfil === 'PFL_OPE_PRE_NOTI'){
    		$("#respuesta").hide();
        }else if(perfil === 'PFL_TEC_RES_NOTI'){
        	$("#respuesta").show();
        }
        
        construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
        $("#listadoItems").removeClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
    });

    $("#btnFiltrarLista").click(function (event) {
        event.preventDefault();
        fn_filtrar();
    });

    function fn_filtrar() {
        fn_limpiar();
        error = false;

        if (!$.trim($("#codDocumento").val())) {
            if (!$.trim($("#idPais option:selected").val())) {
                if (!$.trim($("#fechaNotificacion").val())) {
                    if (!$.trim($("#tipoDocumento").val())) {
                        error = true;
                        $("#codDocumento").addClass("alertaCombo");
                    }
                }
            }
        }


        if (!error) {
            $.post("<?php echo URL ?>NotificacionesFitosanitarias/RespuestaNotificacion/listarNotificacionesFiltradas",
                    {
                        idListaNotificacion: $("#idListaNotificacion").val(),
                        codDocumento: $("#codDocumento").val(),
                        fechaInicioNotificacion: $("#fechaInicioNotificacion").val(),
                        idPais: $("#idPais").val(),
                        tipoDocumento: $("#tipoDocumento").val(),
                        producto: $("#productoNotificacion").val(),
                        estadoRespuesta: $("#estadoRespuesta").val(),
                        areaTematica: $("#areaTematica").val(),
                        fechaFinNotificacion: $("#fechaFinNotificacion").val()
                    },
                    function (data) {
                        if (data.estado === 'FALLO') {
                            mostrarMensaje(data.mensaje, "FALLO");
                        } else {
                            $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
                            construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
                            $("#btnExcel").show();
                        }
                    }, 'json');
        } else {
            $("#estado").html();
            mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
        }
    }
    ;

    function fn_limpiar() {
        $(".alertaCombo").removeClass("alertaCombo");
        $('#estado').html('');
    }
    
    $("#fechaInicioNotificacion").datepicker({
	yearRange: "c:c",
	changeMonth: false,
        changeYear: false,
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText, inst) {
      		$('#fechaFinNotificacion').datepicker('option', 'minDate', $("#fechaInicioNotificacion" ).val());
	    }
  });
    $("#fechaFinNotificacion").datepicker({
    	changeMonth: true,
 	    changeYear: true,
 	    dateFormat: 'yy-mm-dd',
 	 
      });
	 
</script>
