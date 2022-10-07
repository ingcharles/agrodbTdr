$.getScript("aplicaciones/inocuidad/js/globals.js",function(){console.log("globals loaded");});

$(document).ready(function() {

    //Verificamos tipo de Muestra para deshabilitar check tecnico_filtrado

    $("#provincia_id").on("change", function () {
        var selectData = $(this).val();
        if (selectData && selectData.length > 0) {
            $("#canton_id").val("");
            $("#canton_id").prop('disabled', false);
            $("#parroquia_id").val("");
            $("#parroquia_id").prop('disabled', 'disabled');
            clearFinca();
            refreshOpciones(selectData, $('#canton_id'), 'CANTONES');
        }
    });

    $("#canton_id").on("change", function () {
        var selectData = $(this).val();
        if (selectData && selectData.length > 0) {
            $("#parroquia_id").val("");
            $("#parroquia_id").prop('disabled', false);
            clearFinca();
            refreshOpciones(selectData, $('#parroquia_id'), 'PARROQUIAS');
        }
    });

    $("#parroquia_id").on("change", function () {
        clearFinca();
        var selectData = $(this).val();
        if (selectData && selectData.length > 0) {
            cargarFincas();
        }
    });

    clearFinca = function () {
        $("#utm_x").val('');
        $("#utm_y").val('');
        $("#finca_props").html("");
        $("#finca_id").prop('disabled', 'disabled');
    };

    $("#finca_id").on("change", function () {
        var selectData = $(this).val();
        showFincaProperties(selectData);
    });

    if($("#tecnica_muestreo").val().length>0 && $("#medio_refrigeracion").val().length>0)
        $("#enviar").prop('disabled', false);

    $("#fecha_envio_lab").datepicker({
        changeMonth: true,
        changeYear: true
    });

    $("#fecha_ultima_aplicacion").datepicker({
        changeMonth: true,
        changeYear: true
    });

    showFincaProperties = function (selectData) {
        if (selectData && selectData.length > 0) {
            //Cargamos datos de la finca
            $.ajax({
                type: "POST",
                url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
                data: {'catalogo': 'DATOS_FINCA', 'selectData': selectData},
                success: function (json) {
                    json = JSON.parse(json);
                    var lat=json.latitud;
                    var lon=json.longitud;
                    var zone=json.zona;
                    $("#utm_x").val("");
                    $("#utm_y").val("");
                    if(lat && lon && zone){
                        try {
                            zone=zone.length==0?"17":zone;
                            var xy = UTM2Lat(Number(lat), Number(lon), Number(zone));
                            $("#utm_x").val(xy[0]);
                            $("#utm_y").val(xy[1]);
                        }catch (e) {
                            console.log(e.message);
                        }
                    }
                    var html;
                    for (var prop in json) {
                        try {
                            if (prop != "id_sitio" && prop != "latitud" && prop != "longitud" && prop != "croquis"
                                && prop != "codigo" && prop != "zona" && prop != "codigo_provincia") {
                                html = html + "<tr><td style='font-weight: bold'>" + prop.toString().replace("_", " ").toLowerCase().replace(/\b[a-z]/g, function (letter) {
                                    return letter.toUpperCase();
                                }) + "</td><td>" + json[prop] + "</td>";
                            }
                        } catch (e) {
                            console.log(e.message);
                        }
                    }
                    $("#finca_props").html(html);
                }
            });
        }
    };

    cargarFincas = function (callback) {
        $("#finca_id").prop('disabled', false);
        var objFinca = {};
        objFinca.provincia = $("#provincia_id option:selected").text();
        objFinca.canton = $("#canton_id option:selected").text();
        objFinca.parroquia = $("#parroquia_id option:selected").text();
        refreshOpciones(JSON.stringify(objFinca), $('#finca_id'), 'FINCAS', callback);
    };

    refreshOpciones = function (selectData, element, service, callback) {
        element.find('option').remove();
        $.ajax({
            type: "POST",
            url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
            data: {'catalogo': service, 'selectData': selectData},
            success: function (json) {
                json = JSON.parse(json);
                element.append("<option value=\"\">Seleccione ....</option>");
                for (var i = 0; i < json.length; i++) {
                    element.append(json[i]);
                }
                if (callback)
                    callback();
            }
        });
    };

    //Permiso Fitosanitario
    $("#permiso_fitosanitario").on("change", function () {
        $("#importador_props").html("");
        var selectData = $(this).val();
        validarFitosanitario(selectData);
    });
    $("#permiso_fitosanitario").on("keydown", function (e) {
        if(e.which == 13) {
            $("#importador_props").html("");
            var selectData = $(this).val();
            validarFitosanitario(selectData);
        }
    });
    validarFitosanitario = function (selectData) {
        if (selectData && selectData.length > 0) {
            //Cargamos datos de la finca
            $.ajax({
                type: "POST",
                url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
                data: {'catalogo': 'DATOS_FITOSANITARIO', 'selectData': selectData},
                success: function (json) {
                    json = JSON.parse(json);
                    if(json){
                        $("#nombre_rep_legal").val(json.nombre_exportador);
                        $("#registro_importador").val(json.identificador_operador);
                        var html;
                        for (var prop in json) {
                            try {
                                if (prop != "id_importacion" && prop != "id_pais_exportacion" && prop != "id_localizacion" && prop != "id_puerto_embarque"
                                    && prop != "id_puerto_destino" && prop != "id_vue" && prop != "codigo_provincia"&& prop != "id_area" && prop!="regimen_aduanero"
                                    && prop != "id_provincia"&& prop != "id_ciudad" && prop != "moneda" && prop != "informe_requisitos")  {
                                    if(json[prop]!=null)
                                        html = html + "<tr><td style='font-weight: bold'>" + prop.toString().replace("_", " ").toLowerCase().replace(/\b[a-z]/g, function (letter) {
                                            return letter.toUpperCase();
                                        }) + "</td><td>" + json[prop] + "</td>";
                                }
                            } catch (e) {
                                console.log(e.message);
                            }
                        }
                        $("#importador_props").html(html);
                    }else{
                        $("#permiso_fitosanitario").val("");
                        mostrarMensaje("El permiso ingresado no es válido. Reintente","FALLO");
                    }

                }
            });
        }
    };

    //MAPA
    var current_marker=null;
    var map = null;
    $( "#dialog" ).dialog({
        autoOpen: false,
        height: 440,
        width: 540,
        modal: true,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "blind",
            duration: 500
        },
        close: function(event, ui){
            console.log("Close MAP");
            map = null;
        },
        open: function( event, ui ) {
            setTimeout(function(){
                console.log("Open MAP");
                map = new GMaps({
                    div: '#map_canvas',
                    lat: -0.1760602,
                    lng: -78.4718821,
                    zoom: 12
                });

                if(current_marker)
                    map.removeMarker(current_marker);
                var latitude = $( "#utm_x" ).val();
                var longitude = $( "#utm_y" ).val();
                current_marker = map.addMarker({
                    lat: latitude,
                    lng: longitude,
                    title:  $("#finca_id option:selected").text(),
                    infoWindow: {
                        content: '<p> '+$("#finca_id option:selected").text()+'</p>'
                    },
                    click: function(e) {
                        map.map.panTo(e.position);
                    }
                });
                map.setCenter(latitude, longitude);

            }, 200);
        },
    });
    var bounds = new google.maps.LatLngBounds();
    $( "#open_mapa" ).on( "click", function() {
        if($( "#utm_x" ).val() && $( "#utm_x" ).val().length>0
            && $( "#utm_y" ).val() && $( "#utm_y" ).val().length>0)
                $( "#dialog" ).dialog( "open" );
        else
            mostrarMensaje("No es posible obtener el mapa.","FALLO");
    });

    //Cambio de vista depende del tipo de requerimiento
    var selected = $("#ic_tipo_requerimiento_id").val();
    $("#ic_tipo_requerimiento_id > option").each(function() {
        try{
            if(selected && this.value==selected)
                document.getElementById("section_"+this.value).style.display='block';
            else
                document.getElementById("section_"+this.value).style.display='none';
        }catch (e){
            console.log(e);
        }
    });
    $("#fecha_muestreo").datepicker({
        changeMonth: true,
        changeYear: true
    });
    $('input[name="tipo_empresa"]').on('click change', function(e) {
        document.getElementById("section_NC").style.display=(e.target.value=="NC"?"block":"none");
        document.getElementById("section_IM").style.display=(e.target.value=="IM"?"block":"none");
        verificarFiltro();
    });

    verificarFiltro = function () {
        if( $('input[name="tipo_empresa"]:checked') && $('input[name="tipo_empresa"]:checked').val() == "IM") {
            $("#tecnico_filtrado").attr("checked", false);
            $("#tecnico_filtrado").attr("disabled", "disabled");
        }else{
            $("#tecnico_filtrado").attr("disabled", false);
        }
    };

    ///TECNICO
    verificarFiltro();
    $("#tecnico_id").on("change", function () {
        var selectData = $(this).val();
        showTecnicoProperties(selectData);
    });

    loadTecnicos = function () {
        var selectData = "";
        if($("#tecnico_filtrado").prop('checked')){
            selectData = $("#provincia_id").val();
        }

        $("#tecnico_id").find('option').remove();
        $.ajax({
            type: "POST",
            url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
            data: {'catalogo': 'CARGAR_TECNICOS', 'selectData': selectData},
            success: function (json) {
                json = JSON.parse(json);
                $("#tecnico_id").append("<option value=\"\">Seleccione ....</option>");
                for (var i = 0; i < json.length; i++) {
                    $("#tecnico_id").append(json[i]);
                }
            }
        });
    };

    loadSelectedTecnico = function(selectData){
        $("#tecnico_id").find('option').remove();
        showTecnicoProperties(selectData,true);
    };

    showTecnicoProperties = function (selectData,render) {
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
                        $("#tecnico_id").append("<option value='"+json.identificador+"'>"+json.nombre_completo+"</option>");
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
                    $("#tecnico_props").html(html);
                }
            });
        }
    };
});

