var nivelActual = '0';  //indica el paso actual de las pestanias desplazables

function mostrarAdvertencia(texto) {    
    $("#estado").html(texto);
    $("#estado").addClass("advertencia");
}

function borrarMensaje() {
    $("#estado").removeClass();
    $("#estado").html('');
}

function esValidoEsteCampo(elemento) {
    if (!$.trim($(elemento).val()) || !esCampoValido(elemento)) {
        $(elemento).addClass("alertaCombo");
        return false;
    }
    $(elemento).removeClass("alertaCombo");
    return true;
}

function esNoNuloEsteCampo(elemento) {
    if (!$.trim($(elemento).val())) {
        $(elemento).addClass("alertaCombo");
        return false;
    }
    $(elemento).removeClass("alertaCombo");
    return true;
}

function esNoNuloEsteElemento(elemento) {
    if (!$.trim(elemento.val())) {
        elemento.addClass("alertaCombo");
        return false;
    }
    elemento.removeClass("alertaCombo");
    return true;
}

var error = false;
function verificarCamposVisiblesNulos(campos) {

    for (var i in campos) {
        var campo = campos[i];
        if ($(campo).is(":visible")) {
            if (!esNoNuloEsteCampo(campo)) {
                error = true;
            }
            if (!error) {
                var limite = parseInt($(campo).attr('maxlength'));
                if (isNaN(limite)) {
                    continue;
                }
                if ($(campo).is("textarea")) {
                    var text = $(campo).val();
                    var arrSaltos = text.split(/\r?\n/);
                    var numSaltos = 4 * arrSaltos.length;
                    var numeroActual = text.length + numSaltos;
                    if (numeroActual > limite) {
                        var textoCortado = text.substr(0, limite - numSaltos);
                        $(campo).val(textoCortado);
                        $(campo).addClass("alertaCombo");
                        error = true;
                    }
                }
            }
        }
    }
}

function normalizarString(str) {
    if (str == null || str=='0')
        return '';
    return str;
}


//Ejecuta el [archivo] en el [modulo] con los parámetros a procesar, retorna el resultado en una [funcion] con los resultados en el elemento [mensaje]
//[elemento] es el nombre de algún elemento que se podrá procesar despues de la llamada dentro de la [funcion]
//la funcion se ejecuta despues de la llamada en caso de exito=OK y si no retorna mensaje [null]
function llamarServidor(modulo, archivo, parametros, funcion, elemento) {
    $.ajax({
        type: "POST",
        url: "aplicaciones/" + modulo + "/" + archivo + ".php",
        data: parametros,
        dataType: "text",
        async: false,           //para resolver problemas de inicio
        
        contentType: "application/x-www-form-urlencoded; charset=latin1",
        success: function (html) {
            //alert(html);
            var response = $.parseJSON(html);
            if (response.estado == "OK" || response.estado == "exito") {
                funcion(response.mensaje, elemento);
            }
            else {
              
                funcion(null);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
           
            funcion(null);
           
        },
        complete: function () {

        }
    });
    //return obj;
}

//Incrementa el nivel de la vista desplegable que esta desde las pestanias desplazables, la cual debe tener el [id]
//en formto (Xn), donde [X]un caracter y [n] el numero de fase-nivel
//este supone que el padre de [elemento] contiene el div con el [id] mensionado.
//[nivelDocumento] es el nivel guardado y a comparar para habilitar acceso a la vista
function incrementarNivel(elemento, nivelDocumento) {
    var v = elemento.parent().attr('id');
    var pos = parseInt(v.substring(1));
    
    if (nivelDocumento == 'undefined' || nivelDocumento ==null || nivelDocumento == '')
        nivelDocumento = '0';
    var nivel = 1 + parseInt(nivelDocumento);
    if (pos < nivel)
        nivelActual = nivelDocumento;
    else
        nivelActual = pos;
}

//compara el paso de la pestania desplazable con el nivel del documento, y lo habilita
function reconocerNivel(nivelDocumento) {
    $('.pestania').each(function () {
        var v = $(this).attr('id');
        var pos = parseInt(v.substring(1));
       
        var nivel = parseInt(nivelDocumento);
        if (pos <= nivel) {
            $(this).find('.navegacionPestanias .bsig').removeAttr('disabled');
        }

    });
}


function actualizaBotonSiguiente(elemento, nivelGuardado, nivelDocumento) {
    var v = elemento.parent().attr('id');
    var pos = parseInt(v.substring(1));
    var nivel = parseInt(nivelGuardado);
    if (pos >= nivel) {
       
        nivelDocumento = pos.toString();
        reconocerNivel(nivelDocumento);
    }

}

$('.bsig').click(function () {
    $("#estado").html('');
    $("#estado").removeClass();
   
});

$('.bant').click(function () {
    $("#estado").html('');
    $("#estado").removeClass();
   
});

function primeraMayuscula(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function prevenirCaracteresMaximos(elemento) {
    $(elemento).keyup(function () {
        var limite = parseInt($(this).attr('maxlength'));
        if (isNaN(limite)) {
            return;
        }
        var text = $(this).val();
        var arrSaltos = text.split(/\r?\n/);
        var numSaltos = 4 * arrSaltos.length;
        var numeroActual = text.length + numSaltos;
        if (numeroActual > limite) {
            $(this).addClass("alertaCombo");
            var textoCortado = text.substr(0, limite - numSaltos);
            $(this).val(textoCortado);
            $(this).removeClass("alertaCombo");
            mostrarAdvertencia("Máximo " + limite + " caracteres en este campo");
        }

    });

    //***************************
    $(elemento).on("click", function () {
        borrarMensaje();
    });
}

function fechaFormato(datoFecha) {
    var dfecha = new Date(datoFecha);
    var d = dfecha.getDate();
    var m = dfecha.getMonth();
    m += 1;  
    var y = dfecha.getFullYear();
    var fecha = y + "-" + m + "-" + d;
    return fecha;
}