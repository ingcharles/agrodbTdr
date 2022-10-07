/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * @param {type} thisCheck
 * @param {type} nombreClase
 * @returns {undefined}
 */
function fn_selectAllCmbByClass(thisCheck, nombreClase) {
    var isChecked = $(thisCheck).is(":checked");
    if (isChecked === true) {
        $("." + nombreClase + " option[value=SI]").prop('selected', true);
    } else {
        $("." + nombreClase + " option[value=NO]").prop('selected', true);
    }
}

/**
 * Permite validar los elementos que estan dentro de otro elemento (div, formulario, etc.)
 * @param {type} nombreElemento
 * @returns {Number}
 */
function fn_validar(nombreElemento) {
    var continuar = 1;
    $('#' + nombreElemento).find('select, textarea, input').each(function () {
        var inpObj = document.getElementById($(this).attr('id'));
        if (inpObj !== null) {
            try {
                if (!inpObj.checkValidity()) {
                    document.getElementById("sbm").click();
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
 * Funcion para repetir el texto exabezado de una tabla en los demas campos de la misma clase
 * @param {type} campo  elemento de entrada
 * @param {type} clase  elementos de salida
 * @returns {undefined}
 */
function fn_repetir(campo, clase) {
    var valor = "";
    $("." + clase).each(function () {
        valor = $(campo).val() || $(campo).text();
        if ($(this).is("input")) {
            $(this).val(valor);
        } else if ($(this).is("select")) {
            var selected = $(campo).find(':selected').attr('data-id');
            var id = $(this).attr('id');
            $("#" + id + " option[data-id='" + selected + "']").prop("selected", true);
        } else {    //textarea
            $(this).text(valor);
        }
    });
}

/**
 * Función que suma o resta días a una fecha, si el parámetro días es negativo restará los días
 * @param {type} fecha
 * @param {type} dias
 * @returns {unresolved}
 */
function sumarDias(fecha, dias) {
    fecha.setDate(fecha.getDate() + dias);
    return fecha;
}

/**
 * Funcion para mostrar las alertas cuando es una ventana modal
 * @param {type} texto
 * @param {type} tipo
 * @returns {undefined}
 */
function mostrarMensajeModal(texto, tipo) {
    var clase;

    switch (tipo) {
        case 'EXITO':
            clase = 'exito';
            break;
        case 'FALLO':
            clase = 'alerta';
            break;
        default:
            clase = '';
    }

    $("#estadoModal").html(texto);
    $("#estadoModal").addClass(clase);
}