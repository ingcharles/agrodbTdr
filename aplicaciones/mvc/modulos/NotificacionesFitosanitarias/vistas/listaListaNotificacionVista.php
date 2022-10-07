<header>
    <nav><?php echo $this->panelBusquedaNotificaciones; ?></nav>
    <nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
 <div id="article"><?php echo $this->article; ?></div>

<script>
    $(document).ready(function () {
        $("#listadoItems").addClass("comunes");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
        
    });

    $("#btnFiltrar").click(function (event) { 
        event.preventDefault(); 
        fn_filtrar();
        $("#article").html(''); 
    });
    
    function fn_filtrar() { 
    $("#article").html(''); 
    $("#elementos").html(''); 
    fn_limpiar();
    error = false;
    if(!$.trim($("#nombreNotificacion").val())){
        error = true;
        $("#nombreNotificacion").addClass("alertaCombo");
        $("#estado").html();
        mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
    }else{
        $("#paginacion").html("<div id='cargando'>Cargando...</div>");
        $.post("<?php echo URL ?>NotificacionesFitosanitarias/ListaNotificacion/filtrarDatos",
    {
        nombreNotificacion: $("#nombreNotificacion").val()
    },
    function (data) {
        $("#article").html(data.contenido);
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
        }, 'json'); 
        }
    }
    
    function fn_limpiar() {
        $(".alertaCombo").removeClass("alertaCombo");
        $('#estado').html('');
    }
</script>
