/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function fn_cambiarEtiquetaNumero() {
    if ($('#labIngredienteActivo').val() === 'true') {
        $("#lbl_numero").text("Número de ingredientes activos");
        $("#div_estadoFisicoMuestra").css('display', 'block');
    } else {
        $("#div_estadoFisicoMuestra").css('display', 'none');
        if ($('#serMarbetes').val() === 'true') {
            $("#lbl_numero").text("Total de marbetes");
            $("#div_cantidadLotes").css('display', 'block');
            $("#cantidadLotes").attr('required', 'required');
            $("#cantidadLotes").focus();
        } else {
            $("#lbl_numero").text("Número de muestras");
            $("#div_cantidadLotes").css('display', 'none');
            $("#cantidadLotes").removeAttr('required');
        }
    }
    distribuirLineas();
}

/**
 * Controlar el total de marbetes ingresados en paso 1
 * @param {type} pestaniaActual
 * @returns {Number}
 */
function fn_controlTotalMarbetes(pestaniaActual) {
    var continuar = 1;
    if ($('#servicio').val() === $('#serMarbetes').val()) {
        var cantidadTotal = 0;
        $('.clsNumMarbetes').each(function () {
            cantidadTotal = cantidadTotal + parseInt($(this).val());
        });
        if (parseInt($("#cantidad").val()) !== cantidadTotal) {
            mostrarMensaje("El total de marbetes no coincide con la cantidad ingresada en el paso 1 (" + $("#cantidad").val() + ")", "FALLO");
            continuar = 0;
            $(pestaniaActual).show('fast');
            $(pestaniaActual).next().hide('fast', distribuirLineas);
        }
    }
    return continuar;
}

/**
 * Controlar el permitir subir Excel para servicios de FIEBRE AFTOSA (499 = FIEBRE AFTOSA -EITB, 500 = FIEBRE AFTOSA INMUNIDAD)
 * @param {type} idServicio         id del servicio último nivel seleccionado
 * @param {type} idsServiciosFA     ids de los servicio que se permite subir el excel de muestras configurado en Constantes
 * @returns {undefined}
 */
function fn_subirExcelFA(idServicio) {
    if ($("#serFAExcel").val() === 'true') {
        var respuesta = confirm("Clic en Aceptar si es con Muestreo Nacional.");
        if (respuesta) {
            continuarFA = true;
            var url = "Solicitudes/nuevoFA";
            $("#formulario").attr('data-opcion', url);
            vistaSolicitudes = false;
            $("#formulario").submit();
        }
    }
}

/****************POST-REGISTRO***************************/
$("#modal-btn-si").click(function () {
    $('#modalConfirmacion').modal('hide');
});
$("#modal-btn-no").click(function () {
    fn_agregarServicioPredeterminado();
    $('#modalConfirmacion').modal('hide');
});
$("#tipo_solicitud").change(function () {
    $('#estadoFisicoMuestra option[value=""]').prop('selected', true);
    //Para registro y post-registro no existe exoneración
    if ($("#tipo_solicitud").val() === 'REGISTRO' | $("#tipo_solicitud").val() === 'POSTREGISTRO') {
        $('#exoneracion option[value="NO"]').prop('selected', true);
        $('#exoneracion option[value="SI"]').attr('disabled', 'disabled');
    } else {    //si es OTROS
        $('#exoneracion option[value="SI"]').removeAttr('disabled');
        $("#exoneracion").removeAttr("disabled");
    }
});

$("#estadoFisicoMuestra").change(function () {
    if ($("#tipo_solicitud").val() === 'REGISTRO' & $("#estadoFisicoMuestra").val() === 'LIQUIDO') {
        $('#modalConfirmacion').modal('show');
    } else if ($("#tipo_solicitud").val() === 'POSTREGISTRO' & $("#estadoFisicoMuestra").val() === 'LIQUIDO') {
        fn_agregarServicioPredeterminado();
    }
});

/**
 * Agregar el servicio predeterminado, por ejemplo DETERMINACION DE DENSIDAD
 * @returns {undefined}
 */
function fn_agregarServicioPredeterminado() {
    var array = $('#serPredeterminados').val().split(',');
    $.each(array, function (index, value) {
        if ($("#servicio option[value='" + value + "']").length > 0) {
            $("#servicio option[value='" + value + "']").prop('selected', true);
            fn_datosServicio();
            $('#cantidad').val(1);
            agregar();
        }
    });
}