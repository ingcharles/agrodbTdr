$(document).ready(function() {
    $('#file-attach').on("click",function(){
        $("#includedAdjunto").load("aplicaciones/inocuidad/componentes/archivo-adjunto/global.php",function () {
            attachClick($('#file-attach')[0]);
        });
    });

    //Boton que se define en el formulario para hacer la llamada al componente.
    attachClick = function(button){
        console.log("Attach Clicked");
        limpiarFormulario();
        llenarTablaArchivos(button);
    };

    limpiarFormulario = function() {
        $("#dataTable").html("");
        $("#adjunto_nombre").val("");
        $("#adjunto_descripcion").val("");
        $("#adjunto_fecha_carga").val("");
        $("#adjunto_etiqueta").val("");
        $("#adjunto_file").val(null);
    };

    llenarTablaArchivos = function(button){
        if(!button)
            button = $('#file-attach')[0]
        $("#dataTable").html("");
        if(button.hasAttribute("data-view")){
            var objViews = button.getAttribute("data-view");
            objViews = JSON.parse(objViews);
            for(var i=0;i<objViews.length;i++){
                var objView = objViews[i];
                recuperarRegistros(objView.tabla,objView.registro,function(resp){
                    if(resp.error){
                        alert(resp.error);
                    }else{
                        var innerHTML = $("#dataTable").html();
                        innerHTML += resp.data;
                        $("#dataTable").html(innerHTML);
                    }
                });
            }
            $( "#file_dialog" ).dialog( "open" );
        }else
            alert("Revise su parametrización");
    };

    recuperarRegistros = function (tabla,registro,callback) {
        $.ajax({
            type: 'post',
            url: 'aplicaciones/inocuidad/componentes/archivo-adjunto/recuperarRegistros.php',
            data: {
                'tabla': tabla,
                'registro': registro
            },
            success: function (response) {
                callback({data:response});
            },
            error: function () {
                callback({error:"Error"});
            }
        });
    };

});