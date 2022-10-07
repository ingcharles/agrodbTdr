//var elementosSeleccionados = new Array();
//var items = new Array();


//Ejecuta el [archivo] en el [modulo] con los parámetros a procesar, retorna el resultado en una [funcion] con los resultados en el elemento [mensaje]
//[elemento] es el nombre de algún elemento que se podrá procesar despues de la llamada dentro de la [funcion]
//la funcion se ejecuta despues de la llamada en caso de exito=OK y si no retorna mensaje [null]
function llamarServidor(modulo, archivo, parametros, funcion, elemento) {
    $.ajax({
        type: "POST",
        url: "aplicaciones/" + modulo + "/" + archivo + ".php",
        data: parametros,
        dataType: "text",
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
            //alert("Error al procesar archivo " + archivo);
        },
        complete: function () {

        }
    });
    //return obj;
}
