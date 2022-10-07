$(document).ready(function() {
    $("#adjunto_fecha_carga").datepicker({
        changeMonth: true,
        changeYear: true
    });

    if($('#file-attach')[0].hasAttribute("data-tabla") && $('#file-attach')[0].hasAttribute("data-registro")){
        var tabla = $('#file-attach')[0].getAttribute("data-tabla");
        var registro = $('#file-attach')[0].getAttribute("data-registro");
        $('#adjunto_tabla').val(tabla);
        $('#adjunto_registro').val(registro);
    }

    validarTamanio = function(){
        var result = false;
        if($("#adjunto_file") && $("#adjunto_file")[0] && $("#adjunto_file")[0].files
        && $("#adjunto_file")[0].files[0]){
            var realSize = $("#adjunto_file")[0].files[0].size;
            result = realSize<=MAX_FILE_SIZE;
        }
        return result;
    };

    $("#adjunto_file").on("change",function () {
        if(!validarTamanio()){
            mensajeLocal("El archivo excede el tamaño máximo permitido de "+(MAX_FILE_SIZE/1000000)+"MB.","FALLO");
        }
    });


    $("#fileUpButt").on("click",function () {
        if(validarRequeridos($("#adjuntoForm"))){
            if(validarTamanio()){
                var form = $("#adjuntoForm")[0];
                var formData = new FormData(form);
                console.log(formData);
                $.ajax({
                    url: 'aplicaciones/inocuidad/componentes/archivo-adjunto/guardarArchivo.php',
                    data: formData,
                    type: "POST",
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function(){
                        $("#file_msg_box").removeClass();
                    },
                    success: function (data) {
                        var objResult = JSON.parse(data);
                        if(objResult.result=='success'){
                            form.reset();
                            limpiarFormulario();
                            llenarTablaArchivos();
                            mensajeLocal('Archivo guardado',"EXITO");
                        }else if(objResult.result=='extension') {
                            var permitidas = JSON.stringify(objResult.permitted);
                            mensajeLocal('Extensión no permitida '+permitidas.toString().replace("\"",""),"FALLO");
                        }else {
                            mensajeLocal('Existe problemas con la ruta del archivo, por favor revise parametrización',"FALLO");
                        }
                    },
                    error: function (data) {
                        mensajeLocal("Existe un error al guardar el archivo","FALLO");
                    }

                });
            }else{
                mensajeLocal("El archivo excede el tamaño máximo permitido de "+(MAX_FILE_SIZE/1000000)+"MB.","FALLO");
            }
        }else{
            mensajeLocal("Por favor revise los campos obligatorios.","FALLO");
        }
    });

    mensajeLocal = function (texto,tipo) {
        var clase;

        switch (tipo){
            case 'EXITO': clase = 'exito'; break;
            case 'FALLO': clase = 'alerta'; break;
            default: clase = '';
        }

        $("#file_msg_box").html(texto);
        $("#file_msg_box").addClass(clase);
    };
});