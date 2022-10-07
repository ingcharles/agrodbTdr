$.getScript("aplicaciones/inocuidad/js/globals.js",function(){console.log("globals loaded");});

loadInspectores = function () {
    var selectData = $("#provincia_id").val();
    if($("#provincia_id").filter(':visible').length<=0)//Cuando se trata de denuncias
        selectData = $("#provincia_denuncia_id").val();
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

loadSelectedInspector = function(selectData){
    $("#inspector_id").find('option').remove();
    showInspectorProperties(selectData,true);
};

showInspectorProperties = function (selectData,render) {
    if (selectData && selectData.length > 0) {
        //Cargamos datos de la finca
        $.ajax({
            type: "POST",
            url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
            data: {'catalogo': 'DATOS_INSPECTOR', 'selectData': selectData},
            success: function (json) {
                json = JSON.parse(json);
                var html;
                if(render){
                    $("#inspector_id").append("<option value='"+json.identificador+"'>"+json.nombre_completo+"</option>");
                }
                for (var prop in json) {
                    try {
                        if(json[prop]!=null && json[prop].toString().length>0 ) {
                            html = html + "<tr><td style='font-weight: bold'>" + prop.toString().replace("_", " ").toLowerCase().replace(/\b[a-z]/g, function (letter) {
                                return letter.toUpperCase();
                            }) + "</td><td>" + json[prop] + "</td>";
                        }
                    } catch (e) {
                        console.log(e.message);
                    }
                }
                $("#inspector_props").html(html);
            }
        });
    }
};