//Control de campos numeric
$(document).on("input", ".numeric", function() {
    this.value = this.value.replace(/[^\d.-]/g, '');
});

//Control de campos decimales
decimal_presicion = 4;
$(document).on("input", ".decimal", function() {
    if(!eval("/^\\d+(\\.\\d{0,"+decimal_presicion+"})?$/g").test(this.value)){
        this.value=this.value.substr(0,this.value.length-1);
    }
});

//La opción refrescar para ventanas Admin
refrescarAdmin = function (){
    this.ejecutar = function(msg){
        mostrarMensaje(msg.mensaje,"EXITO");
        $("#_actualizar").trigger("click");
        $("#_refrescar").trigger("click");
    };
};
var refAdmin = (refAdmin == null)? new refrescarAdmin():refAdmin;

resetFormulario = function (formulario){
    this.ejecutar = function(msg){
        formulario[0].reset();
        refAdmin.ejecutar(msg);
    };
};

validarRequeridos=function(formulario){
    result = true;
    if (formulario.is("form")) {
        formulario.find('input, select, textarea, file').each(function(){
           if(this.hasAttribute("data-required")){
               try{
                   //Que sea visible y que tenga valor
                   if($('#'+this.id).filter(':visible').length>0) {
                       if (this.value.length <= 0) {
                           $('#' + this.id).addClass("alertaCombo");
                           result = false;
                       } else
                           $('#' + this.id).removeClass("alertaCombo");
                   }
               }catch (e){
                   console.log(e.message);
               }
           }
        });
    }
    return result;
};


//estado para controlar errores
crearEstado=function() {
    var element = document.getElementById("estado");
    if(element == null) {
        $("#areaNotificacion").html("<div id='estado' style='text-align: center'></div>");
    }else{
        $("#estado").empty();
        $("#estado").removeClass();
    }
};

$(function () {
    $.getScript("aplicaciones/inocuidad/componentes/archivo-adjunto/js/attachmentLoader.js",function(){console.log("Attachments Enabled");});
    distribuirLineas();
    crearEstado();
    console.log("Ready");
});