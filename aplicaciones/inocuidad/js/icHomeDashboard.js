var optsRvD = {
    angle: 0, // The span of the gauge arc
    lineWidth: 0.4, // The line thickness
    radiusScale: 0.9, // Relative radius
    pointer: {
        length: 0.6, // // Relative to gauge radius
        strokeWidth: 0.062, // The thickness
        color: '#000000' // Fill color
    },
    limitMax: false,     // If false, max value increases automatically if value > maxValue
    limitMin: false,     // If true, the min value of the gauge will be fixed
    strokeColor: '#EEEEEE',  // to see which ones work best for you
    generateGradient: true,
    highDpiSupport: true,     // High resolution support,
    staticZones: [
        {strokeStyle: "#c2263b", min: 0, max: objIndice.recibido*.25}, // Red from 100 to 130
        {strokeStyle: "#f9bf30", min: objIndice.recibido*.25, max: objIndice.recibido*.75}, // Yellow
        {strokeStyle: "#3ea851", min: objIndice.recibido*.75, max: objIndice.recibido}, // Green
    ],
    staticLabels: {
        font: "10px sans-serif",  // Specifies font
        labels: [0,objIndice.recibido*.25, objIndice.recibido*.5, objIndice.recibido*.75, objIndice.recibido],  // Print labels at these values
        color: "#000000",  // Optional: Label text color
        fractionDigits: 0  // Optional: Numerical precision. 0=round off.
    }

};
var objRecibidos = document.getElementById('gauge_recibidos'); // your canvas element
var gaugeRecibidos = new Gauge(objRecibidos).setOptions(optsRvD); // create sexy gauge!
gaugeRecibidos.maxValue = objIndice.recibido; // set max gauge value
gaugeRecibidos.setMinValue(0);  // Prefer setter over gauge.minValue = 0
gaugeRecibidos.animationSpeed = 6; // set animation speed (32 is default value)
gaugeRecibidos.set(objIndice.atendido); // set actual value
$("#recibidos-textfield").html(objIndice.atendido);

var optsDes = {
    angle: 0, // The span of the gauge arc
    lineWidth: 0.4, // The line thickness
    radiusScale: 0.9, // Relative radius
    pointer: {
        length: 0.6, // // Relative to gauge radius
        strokeWidth: 0.062, // The thickness
        color: '#000000' // Fill color
    },
    limitMax: false,     // If false, max value increases automatically if value > maxValue
    limitMin: false,     // If true, the min value of the gauge will be fixed
    strokeColor: '#EEEEEE',  // to see which ones work best for you
    generateGradient: true,
    highDpiSupport: true,     // High resolution support,
    staticZones: [
        {strokeStyle: "#c2263b", min: 0, max: objIndice.atendido*.25}, // Red from 100 to 130
        {strokeStyle: "#f9bf30", min: objIndice.atendido*.25, max: objIndice.atendido*.75}, // Yellow
        {strokeStyle: "#3ea851", min: objIndice.atendido*.75, max: objIndice.atendido}, // Green
    ],
    staticLabels: {
        font: "10px sans-serif",  // Specifies font
        labels: [0,objIndice.atendido*.25, objIndice.atendido*.5, objIndice.atendido*.75, objIndice.atendido],  // Print labels at these values
        color: "#000000",  // Optional: Label text color
        fractionDigits: 0  // Optional: Numerical precision. 0=round off.
    }

};
var objDespachados = document.getElementById('gauge_despachados'); // your canvas element
var gaugeDespachados = new Gauge(objDespachados).setOptions(optsDes); // create sexy gauge!
gaugeDespachados.maxValue = objIndice.atendido; // set max gauge value
gaugeDespachados.setMinValue(0);  // Prefer setter over gauge.minValue = 0
gaugeDespachados.animationSpeed = 6; // set animation speed (32 is default value)
gaugeDespachados.set(objIndice.despachado); // set actual value
$("#despachados-textfield").html(objIndice.despachado);


cancelarRegistro = function(ic_requerimiento_id){
    $("#detalle_cancelacion").val("");
    $( "#dialogCancelar" ).dialog({
        resizable: false,
        modal: true,
        buttons: {
            Aceptar: function() {
                enviarCancelacion(ic_requerimiento_id)
            },
            Cancelar:function(){
                $( "#dialogCancelar" ).dialog("close");
            }}});
};

enviarCancelacion = function (ic_requerimiento_id) {
    var detalleTxt = $("#detalle_cancelacion").val();
    if(detalleTxt && detalleTxt.length>1){
        if(detalleTxt.length<500){
            var objCancelar = {};
            objCancelar.ic_requerimiento_id = ic_requerimiento_id;
            objCancelar.mensaje = detalleTxt;
            $.ajax({
                type: "POST",
                url: "./aplicaciones/inocuidad/servicios/ServiceCatalogos.php",
                data: {'catalogo': 'CANCELAR_REGISTRO', 'selectData': JSON.stringify(objCancelar)},
                success: function (json) {
                    if(json){
                        mostrarMensaje("Error: "+json,"FALLO");
                    }else{
                        $( "#dialogCancelar" ).dialog("close");
                        $("#_actualizar").trigger("click");
                    }
                }
            });
        }else
            alert("El Mensaje no puede tener más de 500 caracteres.");
    }else
        alert("El Detalle de la cancelación es obligatorio");

};
