var elementosSeleccionados = new Array();
var items = new Array();

function ajustarFoto(){ //sin usar
	var tamanoVentana = $("#foto_img").parent().width();
	var tamanoFoto = $("#foto_img").width();
	var propiedadLeft = 0;
	if(tamanoVentana > tamanoFoto);
		propiedadLeft = ((tamanoVentana-tamanoFoto)/2);
	$("#foto_img").css("left",propiedadLeft + "px");
}

function redimensionarVentanaTrabajo() {
	
	if (typeof anchoBarraOpcionesAplicacion != 'undefined') {
		altoBarraNotificacion = 30;
		
		altoBarraGeneral = $("#barraGeneral").height()
				+ parseInt($("#barraGeneral").css("border-top-width"))
				+ parseInt($("#barraGeneral").css("border-bottom-width"))
				+ parseInt($("#barraGeneral").css("padding-top"))
				+ parseInt($("#barraGeneral").css("padding-bottom"))
				+ parseInt($("#barraGeneral").css("margin-top"))
				+ parseInt($("#barraGeneral").css("margin-bottom"));
		$("#ventanaAplicacion").height($(window).height() - altoBarraGeneral - altoBarraNotificacion);
		$("#ventanaAplicacion").css("margin-top",altoBarraGeneral);
		$("#ventanaAplicacion").css("margin-bottom",altoBarraNotificacion);
		// Estas dos lineas es para que haga recalculo en opera e internet explorer.
		// Para chrome y firefox no es necesario
		$("#opcionesAplicacion h1").css("left",10+$("#navegacionPrincipal").width()+"px");
		$("#listadoItems h1").css("left",30+$("#navegacionPrincipal").width()+$("#opcionesAplicacion h1").width()+"px");
		$("#opcionesAplicacion").height($("#ventanaAplicacion").height());
	
		$("#areaTrabajo").height($("#ventanaAplicacion").height());
	
		$("#areaTrabajo").width($(window).width() - anchoBarraOpcionesAplicacion);
		$("#areaTrabajo").css("left", anchoBarraOpcionesAplicacion + "px");
		// $("#areaTrabajo").css("padding-left",anchoBarraOpcionesAplicacion+"px");
		// $("#ventanaAplicacion").css("max-height",$(window).height()-altoBarraGeneral+"px");
		$("header.acerca").css("bottom", altoBarraGeneral + "px");
		// $("#listadoItems").css("padding-top", $("#listadoItems
		// header").height()+30 + "px");
		$("#opcionesAplicacion").width(
				anchoBarraOpcionesAplicacion
						- parseInt($("#opcionesAplicacion")
								.css("border-left-width"))
						- parseInt($("#opcionesAplicacion").css(
								"border-right-width"))
						- parseInt($("#opcionesAplicacion").css("padding-left"))
						- parseInt($("#opcionesAplicacion").css("padding-right"))
						- parseInt($("#opcionesAplicacion").css("margin-left"))
						- parseInt($("#opcionesAplicacion").css("margin-right")));
		$("header.acerca").width(anchoBarraOpcionesAplicacion);
	}
}

function redimensionarAnchoBarraOpciones() {

	//alert(anchoBarraOpcionesAplicacion);
	/*
	 * $("#areaTrabajo").css("margin-left",anchoBarraOpcionesAplicacion+"px");
	 * $("#areaTrabajo").css("margin-left",anchoBarraOpcionesAplicacion+"px");
	 */

};

$(window).resize(function(){
	redimensionarVentanaTrabajo();
	cambioBotonResize();
	distribuirLineaResize();
	distribuirLineas();
});

function seleccionar(objeto) {
	if (objeto.hasClass("seleccionado")) {
		objeto.removeClass("seleccionado");
		elementosSeleccionados.splice(elementosSeleccionados.indexOf(objeto.attr("id")), 1);
		elementosSeleccionados.sort();
	} else {
		objeto.addClass("seleccionado");
		elementosSeleccionados.push(objeto.attr("id"));
		elementosSeleccionados.sort();
		//alert(elementosSeleccionados.length);
	}
	$("#cantidadItemsSeleccionados").html(elementosSeleccionados.length);
	
}

function abrir(aplicacion, evento, seleccionar) {
	
	if (aplicacion.attr("data-opcion") == "index") {
        $("#contenedorTh").css("display", "none");
    } else if (aplicacion.attr("data-opcion") == "programas") {
        $("#contenedorTh").css("display", "block");
    }
	
	var elementoDestino = "#" + aplicacion.attr("data-destino");
	$(".seleccionado").removeClass("seleccionado");
	elementosSeleccionados = [];
	$("#cantidadItemsSeleccionados").html(elementosSeleccionados.length);
	// $("#listadoItems .abierto").removeClass("abierto");
	if (seleccionar){
		aplicacion.siblings().removeClass("abierto");
		$("#areaTrabajo .abierto").removeClass("abierto");
	}
	// $("#barraGeneral .abierto").removeClass("abierto");
	// $(".abierto").removeClass("abierto");
	var url = "";
	var data=null;
	// alert(aplicacion.attr("id") + " " +
	// aplicacion.attr("data-rutaAplicacion") );
	if (aplicacion.is("form")) {
		evento.preventDefault();
		//var $form = $(this);
		//alert("formulario");
        //$inputs = $form.find("input, select, hidden"),
        data = aplicacion.serialize();
		url = "aplicaciones/" + aplicacion.attr("data-rutaAplicacion")
		+ "/" + aplicacion.attr("data-opcion") + ".php";
	} else {
		if (elementoDestino == "#ventanaAplicacion") {
			//url = "aplicaciones/general/ventanaAplicacion.php?identificadorSSO="+$("#identificadorSSO").val();
			url = "aplicaciones/general/ventanaAplicacion.php";
			data = {
				app : aplicacion.attr("data-rutaAplicacion"),
				idAplicacion : aplicacion.attr("id"),
				nombre : aplicacion.attr("data-nombreAplicacion")
			};
		} else {
			url = "aplicaciones/" + aplicacion.attr("data-rutaAplicacion")
					+ "/" + aplicacion.attr("data-opcion") + ".php";
			data = {
				id : aplicacion.attr("id"),
				opcion : aplicacion.attr("data-idOpcion"),
				elementos: aplicacion.attr("data-elementos"),
				nombreOpcion: aplicacion.attr("data-nombre"),
				idFlujo : aplicacion.attr("data-flujo")
			};
		}
	}
	if (aplicacion.attr("data-destino") == "EXT") {
        var new_window = window.open(url + "?id=" + aplicacion.attr("id"), "nw");
        /*$.post(url, data, function (d) {
            //window.open(url+ "?id=" + aplicacion.attr("id"), "nw");
            //var new_window = window.open(url + "?id=" + aplicacion.attr("id"), "nw");
            //var new_window = window.open(null, "nw");
            //$(new_window.document.body).append(d);
        }, "html");*/
    } else {
    		$.ajax({
    			type : "POST",
    			url : url,
    			data : data,
    			dataType : "text",
    			// data: ({ParamUserID:idUser}),// here we def wich variabe is
    			// assiciated
    			contentType : "application/x-www-form-urlencoded; charset=latin1",
    			beforeSend : function() {
    				if (seleccionar)
    					aplicacion.addClass("abierto");
    				$(elementoDestino).html("<div id='cargando'>Cargando...</div>").fadeIn();
    				$("#listadoItems").off("scroll");
    				$("#estado").attr('class','');
    			},
    			success : function(html) {
    				    				
    				if(elementoDestino == '#areaTrabajo #listadoItems'){
    					$(elementoDestino).html('<div id="estado"></div>'+html);
    				}else{
    					$(elementoDestino).html(html);
    				}
    				
    				redimensionarVentanaTrabajo();
    			},
    			error : function(jqXHR, textStatus, errorThrown) {
    				$(elementoDestino).html(
    						"<div id='error'>¡Ups!... algo no anda bien.<br />"
    								+ "Se produjo un " + textStatus + " "
    								+ jqXHR.status
    								+ ".<br />Disculpe los inconvenientes causados.</div>");
    			},
    			complete : function() {
    				// $("#cargando").delay("slow").fadeOut();
    				// $(elementoDestino).html("<div id='cargando'></div>");
    				// alert(aplicacion.attr("data-defecto"));
    				// abrir($("#"+aplicacion.attr("data-defecto")),"#areaTrabajo");
    			}
    		});
	}
	/*
	 * switch (this.id){ case "documentos":
	 * $("#ventanaAplicacion").load("aplicaciones/"+$(this).attr("data-codigoAplicacion")+"/index.php",function(responseTxt,statusTxt,xhr){test2(responseTxt,statusTxt,xhr);});
	 * break; default: alert("No existe aplicaci�n para este acceso"); }
	 */
	//event.preventDefault();
}

$("#barraGeneral nav a").on("click",function(e) {
	e.preventDefault();
					
	if ($(this).attr("id")!="salir"){
							
		$("head").html("");
		$("head").append("<meta charset='utf-8'>");
		$("head").append("<title>Panel de control GUIA</title>");
		$("head").append("<link rel='stylesheet' href='aplicaciones/general/estilos/agrodb.css'>");
		$("head").append("<link rel='stylesheet' href='aplicaciones/general/estilos/agrodb_papel.css'>");	
		$("head").append("<link rel='stylesheet' href='aplicaciones/general/estilos/jquery-ui-1.10.2.custom.css'>");
		/*$("head")
		.append(
		"<link href='http://fonts.googleapis.com/css?family=Text+Me+One|Poiret+One|Open+Sans' rel='stylesheet' type='text/css'>");*/
		abrir($(this), null, true);
	}
});

$("#ventanaAplicacion").on("click", "#listadoItems nav a", function(e) {
	e.preventDefault();
	var estilo = $(this).attr("id");
	switch (estilo) {
	case "_nuevo":
		abrir($(this), "#" + $(this).attr("data-destino"), false);
		break;
	case "_actualizar":
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		break;
		
	case "_seleccionar": // alert($(".item").length);
		$(".item").each(function(i, e) {
			// alert(e.id);
			seleccionar($("#listadoItems #" + (e.id).replace(".","\\.")));
		});
		break;
		
	case "_eliminar":
		//alert("eliminando");
		$(this).attr("data-elementos",elementosSeleccionados);
		abrir($(this), null, false);
		break;
		
	/*case "_pago":
		$(this).attr("data-elementos",elementosSeleccionados);
		abrir($(this), null, false);
		break;
		
			var datos = [];
		$(".item").each(function(i, e) {
			if($("#" + (e.id).replace(".","\\.")).hasClass("seleccionado"))
				datos.push(e.id);
		});*/
	default:
		//alert("haciendo otra cosa");
		$(this).attr("data-elementos",elementosSeleccionados);
		abrir($(this), null, false);
	}

	// abrir($(this),"#ventanaAplicacion");
});

$("#ventanaAplicacion").on("click", ".item", function(e) {
	seleccionar($(this));
});


/*$("#ventanaAplicacion").on("dblclick",".programas .item",function (){
	alert('UNO'+this.nodeName); 
	abrir($(this),"#ventanaAplicacion",true); 
});*/

$("#ventanaAplicacion").on("dblclick"," .item",function (){ 
	abrir($(this),"#" + $(this).attr("data-destino"),true); 
});
 

$("#ventanaAplicacion").on("click", "#opcionesAplicacion a", function(e) {

	if ($(this).attr("data-nivel")!=='0'){
		e.preventDefault();
		$("title").html($(this).attr('data-nombre'));
		abrir($(this), "#areaTrabajo #listadoItems", true);
	}
});

function allowDrop(ev) {
	ev.preventDefault();
}

function drag(ev) {
	ev.dataTransfer.setData("itemId", ev.target.id);
	
}

function drop(ev) {
	ev.preventDefault();
	var item = $("#listadoItems #" + (ev.dataTransfer.getData("itemId")).replace(".","\\."));
	//alert(ev.dataTransfer.getData("itemId")+" :  " +$(item).attr("id"));
	//alert($(item).attr("id")+" . #"+$(item).attr("data-destino"));
	abrir($(item), "#" + $(item).attr("data-destino"), true);
}



function cargarValorDefecto(combo,valor){
	$('select[name="'+combo+'"]').find('option[value="'+valor+'"]').prop("selected","selected");
	
	/*combo.each(function(i){
		alert($(this).attr("value")+" + "+valor + " = " + ($(this).attr("value")==valor));
		if($(this).attr("value")==valor){
			$(this).attr("selected","selected");
			return false;
		}
	});*/
}


function ejecutarJson(form,metodoExito,metodoFallo){
	
	var $botones = $(form).find("button[type='submit']"),
    	serializedData = $(form).serialize(),
    	//url = "aplicaciones/"+$(form).attr("data-rutaAplicacion")+"/"+$(form).attr("data-opcion")+".php";
    	url = "aplicaciones/"+$(form).attr("data-rutaAplicacion")+"/"+$(form).attr("data-opcion")+".php";
	
	//$("#clave").val($.md5($("#clave").val()));
	
    $botones.attr("disabled", "disabled");
    var resultado = $.ajax({
	    url: url,
	    type: "post",
	    data: serializedData,
	    dataType: "json",
	    async:   false,
	    beforeSend: function(){
	    	$("#estado").removeClass();
	    	$("#estado").attr('class','');
		},
	    success: function(msg){
	    	
	    	if(msg.estado=="exito"){
	    		switch ($(form).attr("data-accionEnExito")){
		    	case "REDIRECCIONAR": 
			    	window.location.replace("index.php"); 
			    	break;
		    	case "ACTUALIZAR": 
			    	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	    			break;
	    		default:
	    			$($(form).attr("data-accionEnExito")).submit();
		    	}
	    		if(metodoExito!=null){
	    			metodoExito.ejecutar(msg);
	    		} else {
	    			mostrarMensaje(msg.mensaje,"EXITO");
	    		}
	    		
	    	} else {
	    		if(metodoFallo!=null){
	    			metodoFallo.ejecutar(msg);
	    		} else {
	    			mostrarMensaje(msg.mensaje,"FALLO");
					if(typeof msg.error != "undefined"){
                        console.log(msg.error);
                    }
	    		}
	    	}
	   },
	   error: function(jqXHR, textStatus, errorThrown){
	    	//mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
			mostrarMensaje("Ocurrió un error al procesar su solicitud, por favor comunicarse con el equipo de atención al usuario de Agrocalidad.","FALLO");
	    },
        complete: function(){
           $botones.removeAttr("disabled");
           //$("#clave").val("");
        }
	});
    return resultado;
}

/*##########################PAGINACION################################*/

function construirPaginacion(elemento,itemsFiltrados){
	items = new Array();
	$(elemento).html(
		'<span>' +
			'Mostrar' + 
			'<select id="itemsAMostrar">' +
			'	<option value="10">10 items</option>' +
			'	<option value="15">15 items</option>' +
			'	<option value="30">30 items</option>' +
			'	<option value="*">Todos</option>' +
			'</select>' +
			'en pantalla.' +
		'</span>' +
		'<span style="float:right;margin-top:-10px;">' +
		'	Items del ' +
		'	<select id="pagina">' +
		'	</select>' +
		'	de <span id="totalItems"></span>' +
		'	<button id="pagAnterior" type="button">&lt;</button>' +
		'	<button id="pagSiguiente"type="button">&gt;</button>' +
		'</span>'
	);
	
	if(itemsFiltrados.length != 0){
		if(itemsFiltrados[0].length === 0){
			itemsFiltrados.splice(0,1);
		}
	}	
		
	items = itemsFiltrados;
	$("#itemsAMostrar option[value='*']").attr("value",items.length);
	$("#totalItems").html(items.length);
	//$("#totalItems").html(items.length-1);
	construirListaPaginas();
	mostrarItems(0);
}

function mostrarItems(itemInicial){	
	$("#tablaItems tbody").html("");
	desplazamiento = parseInt($("#itemsAMostrar").val());
	itemFinal = ((itemInicial+desplazamiento < items.length)?itemInicial+desplazamiento:items.length)-1;
	for(var contador = itemInicial;contador<=itemFinal;contador++)
		$("#tablaItems tbody").append(items[contador]);
}

function construirListaPaginas(){
	$("#pagina").html("");
	numeroOpciones = items.length / parseInt($("#itemsAMostrar").val());
	desplazamiento = parseInt($("#itemsAMostrar").val());
	for (var contador = 0,itemInicial=1; contador<numeroOpciones; contador++,itemInicial=itemInicial+desplazamiento){
		itemFinal = (itemInicial+desplazamiento < items.length)?itemInicial+desplazamiento-1:items.length;
		$("#pagina").append("<option value='"+(itemInicial-1)+"'>"+(itemInicial)+"-"+(itemFinal)+"</option>");
	}
}

$("#ventanaAplicacion").on("change"," #itemsAMostrar",function (){
	construirListaPaginas();
	mostrarItems(parseInt($("#pagina").val()));
});

$("#ventanaAplicacion").on("change"," #pagina",function (){
	mostrarItems(parseInt($("#pagina").val()));
});

$("#ventanaAplicacion").on("click"," #pagSiguiente",function (){
	mostrarNuevaPagina($("#pagina option[value='"+$("#pagina").val()+"']").next().attr("value"));
});

$("#ventanaAplicacion").on("click"," #pagAnterior",function (){
	mostrarNuevaPagina($("#pagina option[value='"+$("#pagina").val()+"']").prev().attr("value"));
});

function mostrarNuevaPagina(nuevaOpcion){
	if (nuevaOpcion != undefined){
		$("#pagina").val(nuevaOpcion);
		mostrarItems(parseInt($("#pagina").val()));
	}
}


$("#ventanaAplicacion").on("click","#tablaItems tbody tr",function(e) {
if($("#valores").length){
		if($("#valores #r_"+this.id+"").length==0){
			$("#valores").append("<input id='r_"+this.id+"' name='valoresFiltrados[]' value='"+this.id+"' type='hidden'>");
			$(this).addClass("reporte");
			$(this).removeClass("item");	
		}else{
			$("#valores input").eq($("#r_"+this.id+"").index()).remove();
			$(this).removeClass("reporte");
			$(this).removeClass("seleccionado");
			$(this).addClass("item");
		}
	}
});


jQuery.fn.ForceNumericOnly =
	function(){
	    return this.each(function(){
	        $(this).keydown(function(e){
	            var key = e.charCode || e.keyCode || 0;
	            // allow backspace, tab, delete, arrows, numbers and keypad numbers ONLY
	            // home, end, period, and numpad decimal
	            return (
	                key == 8 || 
	                key == 9 ||
	                key == 46 ||
	                key == 110 ||
	                key == 190 ||
	                (key >= 35 && key <= 40) ||
	                (key >= 48 && key <= 57) ||
	                (key >= 96 && key <= 105)||
	                e.ctrlKey==true
	                
	                );
	        });
	    });
	};

	/*function uploadAjax(item, prefijo){
		
		var prefijoString=prefijo.toString();
		var archivo = document.getElementById(item);
		var file = archivo.files[0];
		var data = new FormData();
		data.append('archivo',file);
		var url = 'aplicaciones/general/upload.php?prefijo='+prefijo;

		$.ajax({
					url:url,
					type:'POST',
					contentType:false,
					data:data,
					processData:false,
					cache:false,
					success:function(msg) {$('#archivo').val(msg);},
					error: function(msg) { $('#archivo').val('0'); }
			});
	}*/
	
	
	function subirArchivo(item, identificador, carpeta, componente, funcion){
		
   //var archivo = document.getElementById(item);
	   var file = item[0].files[0];
	   var data = new FormData();
	   var rutaCarpeta = carpeta;
	   data.append('archivo',file);
	   var url = 'aplicaciones/general/subirArchivo.php?identificador='+identificador+'&rutaCarpeta='+rutaCarpeta;
	   var elemento = componente;

	   $.ajax({
		  url:url,
		  type:'POST',
		  contentType:false,
		  data:data,
		  processData:false,
		  cache:false,
							beforeSend:function(msg){
							  funcion.esperar("");
							},
							success:function(msg){
								if(msg != 'archivoNoSoportado'){
									elemento.val(msg);
									funcion.exito(msg);
								}else{
									elemento.val('0');
									funcion.error("El archivo supera el tamaño permitido");
								}
								
							},
		  error: function(msg) {
								elemento.val('0');
								funcion.error(msg);
							}
		});
	}
	
	
	function carga(estado, archivo, boton, div) {		
		
	        this.esperar = function (msg) {
	            estado.html("Cargando el archivo...");
	            archivo.removeClass("rojo");
	            archivo.addClass("amarillo");
	        };

	        this.exito = function (msg) {
	            estado.html("El archivo ha sido cargado.");
	            archivo.removeClass("amarillo");
	            archivo.removeClass("rojo");
	            archivo.addClass("verde");
	            
	            if($(div).length){
	            	$(div).show();
	            	$(div +" iframe").attr("src",msg);
	        	}
	            
	            if(boton.is('button')){
	            	boton.attr("disabled", "disabled");
	            }
	            if ($("button.subirArchivo[disabled]").length == 10) {
	                $("#guardarDocumentos button.guardar").removeAttr("disabled");
	            }
	        };

	        this.error = function (msg) {
	            estado.html(msg);
	            archivo.removeClass("amarillo");
	            archivo.addClass("rojo");
	        };
	}
	
	
	function construirAnimacion(elemento, numeroPestania) {
		
		$(elemento).append('<div class="navegacionPestanias"><button class="bant" type="button"><< Anterior</button><span class="numeroPestania"></span><button class="bsig" type="button">Siguiente >></button>');
		var n = $(elemento).length;
		$(elemento).each(function(i){
			$(this).find('.navegacionPestanias .numeroPestania').html("Paso " + (i+1) + " de " + n);
		});
		
		$(elemento).first().find('button.bant').attr("disabled","disabled");
		$(elemento).last().find('button.bsig').attr("disabled","disabled");
		$(elemento).hide();	 

		$('.bsig').click(function(e){
			//e.stopImmediatePropagation();
			var pestaniaActual = $(this).parent().parent();
			$(pestaniaActual).hide('fast');
			$(pestaniaActual).next().show('fast', distribuirLineas);
		});

		$('.bant').click(function(e){
			//e.stopImmediatePropagation();
			var pestaniaActual = $(this).parent().parent();
			$(pestaniaActual).hide('fast');
			$(pestaniaActual).prev().show('fast', distribuirLineas);
		});
		
		if(numeroPestania != null){
			$(elemento).eq(numeroPestania-1).show();
		}else{
			$(elemento).first().show();
		}
		
	}
	
function distribuirLineas(){

	    /*var dim = 5;
	    //TODO: calcular

	    $("#estado").width($("#detalleItem").width() - (65 + dim));
	    $("#estado").css("right", (dim + 18) + "px");*/

		//var dim = $("#areaTrabajo").width() - $("#listadoItems").width();
	    $('fieldset').each(function(i){
	    	
    	var altoLegen=$(this).find("legend").height();
    	var resultadoAltoLegen=altoLegen-20;
    			    
    	if(resultadoAltoLegen>0){
    		//alert(resultadoAltoLegen);
    		resultadoAltoLegen+=33;
    		$(this).css("padding-top",resultadoAltoLegen+"px");
    	}

	     $(this).find("div").each(function(j){
	          //alert(i+","+j);
	               if ($(this).attr("data-linea") !== undefined ) {
	                   var linea = $(this).attr("data-linea");
	                   var items = ($(this).siblings("[data-linea='"+linea+"']").length)+1;
	                   //alert(items);
	                   var longitudPadre = $(this).parent().width();
					   //longitudPadre = dim;
	                   //alert($(this).parent().innerWidth() + ', ' +$(this).parent().width());
	                   $(this).css("width",(Math.floor(longitudPadre/items)-4)+"px");
	                   var logitudRestante = ($(this).width() - $(this).find("label").width()) - 15;
	                   //alert(logitudRestante);
	                   $(this).find("input:not(:checkbox,:radio,[data-distribuir='no'])").css("width",logitudRestante+"px");
	                   $(this).find("textarea:not([data-distribuir='no'])").css("width",logitudRestante+"px");
	                   $(this).find("select:not([data-distribuir='no'])").css("width",logitudRestante+"px");

	               }
	     });
		 
		 $(this).find("fieldset").each(function(){
	    	 $(this).addClass('fieldsetInterno');
	    	 $(this).find("legend").addClass('legendInterno');
    		 var fieldsetInterno=$(this);
    		 $(this).find("div").each(function(){
    			 var linea = $(this).attr("data-linea");
    			 fieldsetInterno.find("div[data-linea='"+linea+"']:first").addClass('divPrimeroFilaInterno');
    		 });
	     });
		 
	  });
}
	
	function mostrarMensaje(texto,tipo){
		  var clase;
		  
		  switch (tipo){
		    case 'EXITO': clase = 'exito'; break;
			case 'FALLO': clase = 'alerta'; break;
			default: clase = '';
		  }
		  
		  $("#estado").html(texto);
		  $("#estado").attr('class','');
		  $("#estado").addClass(clase);
	 }

function actualizarBotonesOrdenamiento(){
	$('.bajar button').removeAttr('disabled');
	$('.subir button').removeAttr('disabled');
	$('.bajar').first().find('button').attr('disabled',true);
	$('.subir').last().find('button').attr('disabled',true);
}
	
function acciones(nuevo,seccion,bajar,subir,ingreso,borrado,activo, previo){
		
	nuevo = (nuevo == null)? "#nuevoRegistro":nuevo; //formulario de nuevo registro
	seccion = (seccion == null)? "#registros":seccion; //tabla en donde añadir los nuevos registros
	
	bajar = (bajar == null)? new exitoBajar():bajar;
	subir = (subir == null)? new exitoSubir():subir;
	ingreso = (ingreso == null)? new exitoIngreso():ingreso;
	borrado = (borrado == null)? new exitoBorrado():borrado;
	activo = (activo == null)? new exitoActivo():activo;
		
	previo = (previo == null) ? new validar() : previo; //Agregación por Carlos para lectro de tramas cuando se requeire en una misma pagina tener dos o mas secciones de botones de ordenamiento.
		/***********************/
	function validar() {
		this.ejecutar = function(){
			return true;
		}
	}

	$(nuevo).submit(function(event){
		event.preventDefault();
		if(previo.ejecutar()){
			ejecutarJson($(this), ingreso);
			actualizarBotonesOrdenamiento();
		} else {
			previo.mensajeError();
		}
	});

		$(seccion).on("submit","form.borrar",function(event){
			event.preventDefault();
			ejecutarJson($(this),borrado);
			actualizarBotonesOrdenamiento();
		});

		$(seccion).on("submit","form.subir",function(event){
			event.preventDefault();
			ejecutarJson($(this),subir);
		});


		$(seccion).on("submit","form.bajar",function(event){
			event.preventDefault();
			ejecutarJson($(this),bajar);
		});

		$(seccion).on("submit","form.abrir",function(event){
			event.stopImmediatePropagation();
			abrir($(this),event,false);
		});
		
		$(seccion).on("submit","form.activo",function(event){
			event.preventDefault();
			$("form.activo #estadoRequisito").val('inactivo');
			ejecutarJson($(this),activo);
		});
		
		$(seccion).on("submit","form.inactivo",function(event){
			event.preventDefault();
			$("form.inactivo #estadoRequisito").val('activo');
			ejecutarJson($(this),activo);
		});

		/***********************/

		$("#actualizarRegistro").submit(function(event){
			event.preventDefault();
			ejecutarJson($(this),null);
		});

		$("#regresar").submit(function(event){
			event.stopImmediatePropagation();
			abrir($(this),event,false);
		});

		function exitoIngreso(){
			this.ejecutar = function(msg){
				mostrarMensaje("Nuevo registro agregado","EXITO");
				var fila = msg.mensaje;
				$(seccion).append(fila);	
				$(nuevo + " fieldset input:not(:hidden,[data-resetear='no'])").val('');
				$(nuevo + " fieldset select:not(:hidden,[data-resetear='no'])").val('');
			    $(nuevo + " fieldset textarea").text(''); //TODO: Revisar efecto
			};
		}

		function exitoBorrado(){
			this.ejecutar = function(msg){
			var registro = " #R";
            if(typeof msg.registro != "undefined") {
                registro = " " + msg.registro;
            }
			$(seccion + registro + msg.mensaje).fadeOut("fast",function(){
			        $(this).remove();
			    });
				mostrarMensaje("Elemento borrado","EXITO");
			};
		}
		
		function exitoActivo(){
			this.ejecutar = function(msg){
				
				if ($(seccion + " #R" + msg.mensaje +" form.activo").length!=0){
					$(seccion + " #R" + msg.mensaje +" form.activo").addClass('inactivo');
					$(seccion + " #R" + msg.mensaje +" form.inactivo").removeClass('activo');
					$estado = 'inactivo';
			    }else{
			    	$(seccion + " #R" + msg.mensaje +" form.inactivo").addClass('activo');
			    	$(seccion + " #R" + msg.mensaje +" form.activo").removeClass('inactivo');
			    	$estado = 'activo';
			    }
				
				mostrarMensaje("Elemento "+$estado,"EXITO");
			};
		}

		function exitoSubir(){
			this.ejecutar = function(msg){
				var fila = $(seccion + " #R" + msg.mensaje);
				$(seccion + " #R" + msg.mensaje).clone(true).insertAfter($(seccion + " #R" + msg.mensaje).next());
				$(fila).remove();
				mostrarMensaje("Elementos reordenados","EXITO");
				actualizarBotonesOrdenamiento();
			};
		}

		function exitoBajar(){
			this.ejecutar = function(msg){
				var fila = $(seccion + " #R" + msg.mensaje);
				$(seccion + " #R" + msg.mensaje).clone(true).insertBefore($(seccion + " #R" + msg.mensaje).prev());
				$(fila).remove();
				mostrarMensaje("Elementos reordenados","EXITO");
				actualizarBotonesOrdenamiento();
			};
		}
	}
	
	function construirValidador(){
		$(":input").inputmask();
		$("[required]").addClass("camposRequeridos");
	}
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	function UTM2Lat(lat, lon, zona){  		                                
	      latlon = new Array(2);
	      var x, y, zone, southhemi;   
	      southhemi = southhemi_mp(lat, lon, zona);
	      x = parseFloat (lat);     
	      y = parseFloat (lon);
	      zone = parseFloat (zona); 	         
	      UTMXYToLatLon (x, y, zone, southhemi, latlon);     
	      Valxy = new Array(2); 
	      Valxy[0] = RadToDeg(latlon[0]); 
	      Valxy[1] = RadToDeg(latlon[1]);	       	         
	      return Valxy;
	  }    

	  function Lat2UTM(lat, lon){					    	  		     	   
	      var xy = new Array(2); 
	      lat = parseFloat (lat);
	      lon = parseFloat (lon);		     		    
	      zone = Math.floor ((lon + 180.0) / 6) + 1;             		             
	      zone = LatLonToUTMXY (DegToRad (lat), DegToRad (lon), zone, xy);
	      var _xy = new Array(2);  
	      _xy[0] = roundNumber(xy[0],4);
	      _xy[1] = roundNumber(xy[1],4);
	      _xy[2] = zone;
	      return _xy;		    		   
	  }
	  
	  function NuevaLat2UTM(lat, lon){					    	  		     	   
	       var xy = new Array(2); 
	       lat = parseFloat (lat);
	       lon = parseFloat (lon);		     		    
	       zone = Math.floor ((lon + 180.0) / 6) + 1;             		             
	       zone = LatLonToUTMXY (DegToRad (lat), DegToRad (lon), zone, xy);		     
	       var cxy = new Array(3);  
	       cxy[0] = xy[0];
	       cxy[1] = xy[1];
	       cxy[2] = zone;		     
	       return cxy;		    		   
	  }

	  //Revisa el hemisferio en que se encuentra
	  function southhemi_mp(lat, lon, zona){
		  var hemisferio = true; 
		  if (lon>=1000000 && lon<= 9999999 && zona>=15 && zona<=22){
		   hemisferio = true;   
		  }else{
		   hemisferio = false;
		  }
		  
		  return hemisferio;
	    }
		
	function distribuirLineaResize(){
		$('fieldset').each(function(i){
			$(this).find("div").each(function(j){
				if ($(this).attr("data-linea") !== undefined ) {
					var linea = $(this).attr("data-linea");
					var items = ($(this).siblings("[data-linea='"+linea+"']").length)+1;
					var longitudPadre = $(this).parent().width();
					$(this).css("width",(Math.floor((longitudPadre/2)/items)-4)+"px");   
				}
			});
		});
	}
	
	function crearBarraResize(){
		$("#listadoItems").wrap('<div>').parent().css({'display':'inline-block',
			'height':'100%',
			'width':'50%',
			'maxWidth':'80%',
			'minWidth':'17%',
		}).resizable( {
			handles: "e",
		}).find('#listadoItems').css({'width':'100%','overflow-y':'scroll'});
		
		$(".ui-resizable").prepend('<div id="imgMain"><span class="ui-icon ui-icon-arrowthickstop-1-w">');
		
		$("#imgMain").click(function(){
			if ($("#imgMain span").hasClass("ui-icon-arrowthickstop-1-w")) {
				$("#imgMain").addClass('imgMainRight');
				$("#imgMain span").removeClass('ui-icon-arrowthickstop-1-w');
				$("#imgMain span").addClass('ui-icon-arrowthickstop-1-e');
				$(".ui-resizable").css({'width':'17%'});
				$("#detalleItem").css({'width':'83%'});
				distribuirLineas();
			}else{
				$("#imgMain").removeClass('imgMainRight');
				$("#imgMain span").addClass('ui-icon-arrowthickstop-1-w');
				$("#imgMain span").removeClass('ui-icon-arrowthickstop-1-e');
				$(".ui-resizable").css({'width':'50%'});
				$("#detalleItem").css({'width':'50%'});
				distribuirLineaResize();
				distribuirLineas();
			}
		});	
	}
	
	function cambioBotonResize(){
		$('#detalleItem').width($("#areaTrabajo").width()-32-$("#listadoItems").width());
		var porcentaje = $("#areaTrabajo").width()/$('#detalleItem').width();
		porcentaje =Math.round(25*porcentaje);
		if(porcentaje<39){
			$("#imgMain").addClass('imgMainRight');
			$("#imgMain span").removeClass('ui-icon-arrowthickstop-1-w');
			$("#imgMain span").addClass('ui-icon-arrowthickstop-1-e');
		}else{
			$("#imgMain").removeClass('imgMainRight');
			$("#imgMain span").addClass('ui-icon-arrowthickstop-1-w');
			$("#imgMain span").removeClass('ui-icon-arrowthickstop-1-e');
		}	
	}
	
	/*
	*Función para detectar el navegador del usuario.	
	*/

	function detectarNavegador() {
    let nav = navigator.appVersion,
        client = (() => {
            let agent = navigator.userAgent,
                engine = agent.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [],
                build;

            if(/trident/i.test(engine[1])){
                build = /\brv[ :]+(\d+)/g.exec(agent) || [];
                return {browser:'IE', version:(build[1] || '')};
            }
            
            var edge = agent.indexOf('Edge'); 
            if(edge > -1){ 
            	var versionEdge=parseInt(agent.substring(edge + 5, agent.indexOf('.', edge)), 10); 
            	return {browser: 'Edge', version: versionEdge}; 
            }

            if(engine[1] === 'Chrome'){
                build = agent.match(/\bOPR\/(\d+)/);

                if(build !== null) {
                    return {browser: 'Opera', version: build[1]};
                }
            }

            engine = engine[2] ? [engine[1], engine[2]] : [navigator.appName, nav, '-?'];

            if((build = agent.match(/version\/(\d+)/i)) !== null) {
                engine.splice(1, 1, build[1]);
            }

            return {
              browser: engine[0],
              version: engine[1]
            };
        })();

		return client;
	}
	
		/*
		*Menú desplegable
		*/
	
	function cerrarMenu(e) {
	    if(e){
	    	var opcion = $(e).attr("data-idopcion");
	        $(e).attr("status", "close");
	        $(e).removeClass("config_open");
	        $(e).addClass("config_close");
	        $(e).removeClass('abiertoMenu')
	        $("#opcionesAplicacion div a").each(function(){        	
	        	if($(this).attr("data-padre")==opcion){
	        		$(this).hide("50");
	        	}
				$(this).removeClass("abierto");
	        });
	        
	        if( $("#listadoItems header h1")){
	        	$("#listadoItems header").html("");
	        	$("#listadoItems").html('<div class="mensajeInicial">Seleccione una opción para revisarla.</div>');
	        	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
	        } 
	    }
	}

	function abrirMenu(e) {
	    if(e){
	    	var opcion = $(e).attr("data-idopcion");
	        $(e).attr("status", "open");
	        
	        $(e).removeClass("config_close");
	        $(e).addClass("config_open");
	        $(e).addClass('abiertoMenu')
	        $("#opcionesAplicacion div a").each(function(){
	        	if($(this).attr("data-padre")==opcion){
	        		$(this).show("50");
	        	}
	        });
	    }
	}

	function desplegarMenu(e) {	
	    if(e) {
	        if ($(e).attr("status") == "open"){
	        	cerrarMenu($(e));
	        } else{
	        	abrirMenu($(e));
	        }
	    }
	}
