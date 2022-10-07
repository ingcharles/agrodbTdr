$.getScript("aplicaciones/inocuidad/js/globals.js",function(){console.log("globals loaded");});

$(document).ready(function() {

    $('#actualizaLaboratorio').submit(function(event) {
        event.preventDefault();
        if(validarRequeridos($("#actualizaLaboratorio"))){
            ejecutarJson($(this),new resetFormulario($("#actualizaLaboratorio")));
        }else
            mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");

    });

    $('#labSolicitud').click(function(){
        $.post("aplicaciones/mvc/laboratorios/solicitudes/aplicacion/app1/475", function(data){
            console.log(data);
        });
    });

});