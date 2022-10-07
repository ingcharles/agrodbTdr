$.getScript("aplicaciones/inocuidad/js/globals.js",function(){console.log("globals loaded");});

$(document).ready(function() {

    $('#actualizaEvaluacion').submit(function(event) {
        event.preventDefault();
        if(validarRequeridos($("#actualizaEvaluacion"))){
            ejecutarJson($(this),new resetFormulario($("#actualizaEvaluacion")));
        }else
            mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");

    });
    $('#resultadoDecision').on("change",function(e){
        var selectData = $(this).val();
        console.log(selectData);
        if(selectData && selectData.length>0)
            $("#ic_resultado_decision_id").val(selectData);
    });
    $("#enviar").on("click",function(){
        console.log("Guarda Evaluacion");
        $("#enviarEvaluacion").submit();
    });

    $("#enviarEvaluacion").submit(function(event){
        event.preventDefault();
        ejecutarJson($(this),new resetFormulario($("#enviarEvaluacion")));
        $("#enviar").prop('disabled', 'disabled');
    });
});