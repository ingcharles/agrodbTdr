$(document).ready(function() {
    if (!$("#ic_requerimiento_id").val()) {
        $("#fecha_solicitud").val($.datepicker.formatDate('dd/mm/yy', new Date()));
        $("#ic_tipo_requerimiento_id").prop("disabled", false);
        $("#ic_producto_id").prop("disabled", false);
        $("#fecha_solicitud").prop("disabled", false);
    }


    if ($("#ic_requerimiento_id").val()) {
        habilitarSecciones($("#ic_tipo_requerimiento_id").val());
        $("#enviar").prop("disabled", false);
        $("#file-attach").prop("disabled", false);
    }
    $("#ic_tipo_requerimiento_id").on("change", function () {
        var selected = this.value;
        $("#ic_tipo_requerimiento_id > option").each(function () {

            try {
                if (selected && this.value == selected)
                    document.getElementById("section_" + this.value).style.display = 'block';
                else
                    document.getElementById("section_" + this.value).style.display = 'none';
            } catch (e) {
                console.log(e);
            }
        });
        if (selected != null && selected != '') {
            document.getElementById("section_OBS").style.display = 'block';
        }
    });
    $("#fecha_inspeccion").datepicker({
        changeMonth: true,
        changeYear: true
    });
    $("#fecha_notificacion").datepicker({
        changeMonth: true,
        changeYear: true
    });
    $("#fecha_denuncia").datepicker({
        changeMonth: true,
        changeYear: true
    });

    $("#enviar").on("click",function(){
        console.log("Guarda Laboratorio");
        document.getElementById("section_" + $("#ic_tipo_requerimiento_id").val()).style.display = 'none';
        document.getElementById("section_OBS").style.display = 'none';
        $("#enviarCaso").submit();
    });

    $("#enviarCaso").submit(function(event){
        event.preventDefault();
        ejecutarJson($(this),new resetFormulario($("#enviarCaso")));
        abrir($("#"+aplicacion.attr("data-defecto")),"#areaTrabajo");
    });

    function habilitarSecciones(elem) {

        try {
            document.getElementById("section_" + elem).style.display = 'block';
            document.getElementById("section_OBS").style.display = 'block';
            switch (elem) {
                case 'PV':

                    break;
            }

        } catch (e) {
            console.log(e);
        }
    }

    $('#actualizarCaso').submit(function (event) {
        event.preventDefault();

        if (validarRequeridos($("#actualizarCaso"))) {
            if (formularioValido()) {
                document.getElementById("section_" + $("#ic_tipo_requerimiento_id").val()).style.display = 'none';
                document.getElementById("section_OBS").style.display = 'none';
                ejecutarJson($(this), new resetFormulario($("#actualizarCaso")));
            }
        } else
            mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");

    });

    formularioValido = function () {
        ret = false;
        if ($("#numero_muestras").val() && $("#numero_muestras").val() > 0)
            ret = true;
        else {
            $("#numero_muestras").val("1");
            ret = true;
        }


        return ret;
    };

    $("#inspector_id").on("change", function () {
        var selectData = $(this).val();
        showInspectorProperties(selectData);
    });

});