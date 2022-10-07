function closeConfiguracion() {
    if($("#__icConfiguracion")[0]) {
        $("#__icConfiguracion")[0].setAttribute("status", "close");
        $("#__icConfiguracion").removeClass("config_open");
        $("#__icConfiguracion").addClass("config_close");
        $("#__icAdminInsumos").hide();
        $("#__icAdminLmrs").hide();
        $("#__icAdminProductos").hide();
    }
}

function openConfiguracion() {
    if($("#__icConfiguracion")[0]) {
        $("#__icConfiguracion")[0].setAttribute("status", "open");
        $("#__icConfiguracion").removeClass("config_close");
        $("#__icConfiguracion").addClass("config_open");
        $("#__icAdminInsumos").show();
        $("#__icAdminLmrs").show();
        $("#__icAdminProductos").show();
    }
}

function toogleConfiguracion() {
    if($("#__icConfiguracion")[0]) {
        if ($("#__icConfiguracion")[0].getAttribute("status") == "open")
            closeConfiguracion();
        else
            openConfiguracion();
    }
}

$('document').ready(function(){
    if($("#__icConfiguracion")[0]){
        $("#__icConfiguracion").unbind('click');
        $("#__icConfiguracion")[0].removeAttribute("data-destino");
        $("#__icConfiguracion")[0].removeAttribute("data-rutaaplicacion");
        $("#__icConfiguracion")[0].removeAttribute("data-idopcion");
        $("#__icConfiguracion")[0].removeAttribute("data-opcion");
        $("#__icConfiguracion")[0].removeAttribute("data-flujo");
        $("#__icConfiguracion")[0].removeAttribute("data-nombre");
        if(!$("#__icConfiguracion")[0].getAttribute("status")){
            closeConfiguracion();
        }

        if (!$("#__icConfiguracion")[0].onclick) {
            $("#__icConfiguracion").on("click", toogleConfiguracion);
        }
    }
});