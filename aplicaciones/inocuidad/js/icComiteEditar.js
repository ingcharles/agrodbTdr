$.getScript("aplicaciones/inocuidad/js/globals.js",function(){console.log("globals loaded");});

$(document).ready(function() {

    $('#actualizarComite').submit(function(event) {
        event.preventDefault();
        if(validarRequeridos($("#actualizarComite"))){
            ejecutarJson($(this),new resetFormulario($("#actualizarComite")));
        }else
            mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");

    });
    /*
    $("#enviar").on("click",function(){
        console.log("Guarda Comite");
        $("#enviarComite").submit();
    });

    $("#enviarComite").submit(function(event){
        event.preventDefault();
        ejecutarJson($(this),refAdmin);
    });
    if($("#observaciones").val("").length>0)
        $("#enviar").prop('disabled', false);

    */
});