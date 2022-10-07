$(document).ready(function() {
    $("#fecha_inicio").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });

    $("#fecha_fin").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
    $("#link_360").on("click",function(){
        console.log("Click 360");
        var idReq = document.getElementById("ic_requerimiento_id").value;
        this.id = idReq;

    });
});
$.getScript("aplicaciones/inocuidad/js/globals.js",function(){console.log("globals loaded");});

loadInspectores = function () {
    var selectData = $("#provincia_id").val();
    $("#inspector_id").find('option').remove();
    $.ajax({
        type: "POST",
        url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
        data: {'catalogo': 'CARGAR_INSPECTORES', 'selectData': selectData},
        success: function (json) {
            json = JSON.parse(json);
            $("#inspector_id").append("<option value=\"\">Seleccione ....</option>");
            for (var i = 0; i < json.length; i++) {
                $("#inspector_id").append(json[i]);
            }
        }
    });
};

validar360 = function (callback) {
    var idReq = document.getElementById("ic_requerimiento_id").value;
    $.ajax({
        type: "POST",
        url: "./aplicaciones/inocuidad/servicios/ServiceReportes.php",
        data: {'tipo': '360', 'data': idReq},
        success: function (json) {
            if(json=="true")
                callback(true);
            else
                callback(false);
        }
    });
};

validarDetallado = function (record, callback) {
    $.ajax({
        type: "POST",
        url: "./aplicaciones/inocuidad/servicios/ServiceReportes.php",
        data: {'tipo': 'detallado', 'data': JSON.stringify(record)},
        success: function (json) {
            if(json=="true")
                callback(true);
            else
                callback(false);
        }
    });
};