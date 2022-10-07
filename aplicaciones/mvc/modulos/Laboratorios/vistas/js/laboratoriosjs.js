/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Funcion para ver los datos de la muestra en modal
 * @param {type} idRecepcionMuestras
 * @param {type} url
 * @returns {undefined}
 */
function fn_verDatosMuestraModal(idRecepcionMuestras, url) {
    var data = {
        idRecepcionMuestras: idRecepcionMuestras
    };
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "text",
        contentType: "application/x-www-form-urlencoded; charset=latin1",
        beforeSend: function () {

        },
        success: function (html) {
            $("#modalDatosMuestra").remove();
            $('#detalleItem').append(html);
            $('#modalDatosMuestra').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {

        },
        complete: function () {

        }
    });
}

/**
 * Funcion para ver los datos de la solicitud
 * @param {type} idSolicitud
 * @param {type} url
 * @returns {undefined}
 */
function fn_verDatosSolicitud(idSolicitud, url) {
    var elementoDestino = "#detalleItem";
    $.ajax({
        type: "POST",
        url: url + "/" + idSolicitud,
        dataType: "text",
        contentType: "application/x-www-form-urlencoded; charset=latin1",
        beforeSend: function () {
            $(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
        },
        success: function (html) {
            $(elementoDestino).html(html);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(elementoDestino).html(
                    "<div id='error'>¡Ups!... algo no anda bien.<br />"
                    + "Se produjo un " + textStatus + " "
                    + jqXHR.status
                    + ".<br />Disculpe los inconvenientes causados.</div>");
        },
        complete: function () {
        }
    });
}

/**
 * Funcion para abrir la vista de informes
 * @param {type} idSolicitud
 * @param {type} idOrdenTrabajo
 * @param {type} url
 * @returns {undefined}
 */
function fn_abrirVistaInformes(idSolicitud, idOrdenTrabajo, url) {
    var elementoDestino = "#detalleItem";
    var data = {
        idSolicitud: idSolicitud,
        idOrdenTrabajo: idOrdenTrabajo
    };
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "text",
        contentType: "application/x-www-form-urlencoded; charset=latin1",
        beforeSend: function () {
            $(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
        },
        success: function (html) {
            $(elementoDestino).html(html);
            redimensionarVentanaTrabajo();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(elementoDestino).html(
                    "<div id='error'>¡Ups!... algo no anda bien.<br />"
                    + "Se produjo un " + textStatus + " "
                    + jqXHR.status
                    + ".<br />Disculpe los inconvenientes causados.</div>");
        },
        complete: function () {
        }
    });
}

/**
 * Para validar los campos de cada paso se la solicitud 
 * @param {type} paso
 * @param {type} pestaniaActual
 * @returns {Number}
 */
function fn_validarSolicitud(paso, pestaniaActual) {
    var continuar = 1;
    $('#div_paso' + paso).find('select, textarea, input').each(function () {
        var inpObj = document.getElementById($(this).attr('id'));
        if (inpObj !== null) {
            try {
                if (!inpObj.checkValidity()) {
                    if ($(this).is("select") & $(this).hasClass("checklist") === true) {
                        var objParent = $(this).parent();
                        $(objParent).children('.fs-label-wrap').addClass("alertaCombo");
                    }
                    document.getElementById("sbm").click();
                    $(pestaniaActual).show('fast');
                    $(pestaniaActual).next().hide('fast', distribuirLineas);
                    continuar = 0;
                    return false;
                }
            }
            catch (err) {
            }
        }
    });
    return continuar;
}

/**
 * Para validar que se hayan subido los anexos de la solicitud que son obligatorios
 * @returns {Number}
 */
function fn_validarAnexosSubidos() {
    var continuar = 1;
    $(".clsParametroServicio").each(function () {
        if (!$(this).prop('required')) {

        } else {    //tiene la propiedad requerido
            if ($(this).val() === "") {
                continuar = 0;
            }
        }
    });
    if (continuar === 0) {
        mostrarMensaje("Existen anexos obligatorios que aún no han sido subidos.", "FALLO");
    }
    return continuar;
}

/**
 * Permite mostrar los campos configurados como visibles/requeridos del Laboratorio
 * Estos campos se encuentran en Configuracion General del Laboratorio
 * @param {type} atributosJson
 * @returns {undefined}
 */
function fn_habilitarCamposLaboratorio(atributosJson) {
    var jsonObj = jQuery.parseJSON(atributosJson);
    $.each(jsonObj, function (key, value) {
        $("#div_" + value.id).css('display', value.display);
        if (value.id === 'longitud' & value.display === 'block') {
            $("#div_bntBuscarMapa").css('display', 'block');
        } else if (value.id === 'longitud' & value.display === 'none') {
            $("#div_bntBuscarMapa").css('display', 'none');
        }
        if (value.required === 'true')
        {
            $("#" + value.id).attr('required', value.required);
        }
        else {
            $("#" + value.id).removeAttr('required');
        }
    });
}

/**
 * Verificar la fecha de caducidad
 * @param {type} campoFechaSeleccionada
 * @param {type} diasRegistro
 * @param {type} diasPostregistro
 * @returns {undefined}
 */
function fn_limiteFechaCaducidad(campoFechaSeleccionada, diasRegistro, diasPostregistro) {
    var diasAtras = '';
    if ($("#tipo_solicitud").val() === 'REGISTRO') {
        diasAtras = diasRegistro;
    } else if ($("#tipo_solicitud").val() === 'POSTREGISTRO') {
        diasAtras = diasPostregistro;
    }
    if (diasAtras !== '') {
        var fechaSeleccionada = new Date($(campoFechaSeleccionada).val());
        var hoy = new Date();
        var fecha_limite = sumarDias(hoy, -diasAtras);
        if (fechaSeleccionada > fecha_limite) {
            mostrarMensaje("La fecha de caducidad debe ser mayor a " + diasAtras + " días", "FALLO");
            $(campoFechaSeleccionada).val('');
        }
    }
}

/**
 * Funcion para validar que no se repita un codigo de muestra para un mismo analisis
 * @param {type} campo
 * @param {type} idServicio
 * @returns {Boolean}
 */
function fn_verificar(campo, idServicio) {
    $("#estado").removeClass();
    var arrayValores = new Array();
    var i = 0;
    $(".verificar_" + idServicio).each(function () {
        $(this).removeClass("alertaCombo");
        var val = $(this).val().replace(/\s+/g, " ").trim();
        if (val !== '') {
            arrayValores[i] = val.toUpperCase();
            i = i + 1;
        }
    });

    for (var i = 0; i < arrayValores.length - 1; i++) {
        for (var j = i + 1; j < arrayValores.length; j++) {
            if (arrayValores[i] === arrayValores[j]) {
                $(campo).addClass("alertaCombo");
                $(campo).val("");
                mostrarMensaje("La muestra " + arrayValores[i] + " no puede repetirse para un mismo análisis.", "FALLO");
                $(campo).focus();
                return false;
            }
        }
    }
}

/**
 * Funcion para desplegar el modal de agregar servicios desde la solicitud
 * @param {type} idSolicitud
 * @param {type} idDetalleSolicitud
 * @param {type} idServicio
 * @param {type} cantidad
 * @param {type} idLaboratorio
 * @param {type} url
 * @returns {undefined}
 */
function fn_agregarServiciosModal(idSolicitud, idDetalleSolicitud, idServicio, cantidad, idLaboratorio, url) {
    var data = {
        idSolicitud: idSolicitud,
        servicios: idServicio,
        cantidades: idServicio + "-" + cantidad
    };
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        dataType: "text",
        contentType: "application/x-www-form-urlencoded; charset=latin1",
        beforeSend: function () {
            $("#paginacionSolicitados").html("<div id='cargando'>Cargando...</div>");
        },
        success: function (html) {
            $("#paginacionSolicitados").html("");
            $("#idDetalleS").val(idDetalleSolicitud);
            $("#numM").val(cantidad);
            $('#detalleServicios').html(html);
            $('#modalDatosMuestra').modal('show');
            $("#idLaboratorio2").val(idLaboratorio);
            $("#idSolicitud").val(idSolicitud);
            $("#estadoModal").removeClass();
            fn_llenarServicios();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $("#paginacionSolicitados").html("");
        },
        complete: function () {

        }
    });
}