var nivelActual = '0';  //indica el paso actual de las pestanias desplazables

function borrarMensaje() {
    $("#estado").html('');
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
            var response = $.parseJSON(html);
            if (response.estado == "OK") {
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
    
}

//Incrementa el nivel de la vista desplegable que esta desde las pestanias desplazables, la cual debe tener el [id]
//en formto (Xn), donde [X]un caracter y [n] el numero de fase-nivel
//este supone que el padre de [elemento] contiene el div con el [id] mensionado.
//[nivelDocumento] es el nivel guardado y a comparar para habilitar acceso a la vista
function incrementarNivel(elemento, nivelDocumento) {
    var v = elemento.parent().attr('id');
    var pos = parseInt(v.substring(1));
    //var nivel=1+parseInt(protocolo.nivel);
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


