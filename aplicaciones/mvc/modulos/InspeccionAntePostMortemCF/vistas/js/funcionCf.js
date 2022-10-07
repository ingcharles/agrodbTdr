//******fecha del formulario reportes
$("#fecha").datepicker({
	yearRange: "c:c",
	changeMonth: false,
    changeYear: false,
    dateFormat: 'yy-mm-dd',
  });
//******fecha del formulario
$("#fecha_formulario").datepicker({
	yearRange: "c:c",
	changeMonth: false,
    changeYear: false,
    dateFormat: 'yy-mm-dd',
  });

//******fecha del formulario detalle
$("#fecha_formulario_detalle").datepicker({
	yearRange: "c:c",
	changeMonth: false,
    changeYear: false,
    dateFormat: 'yy-mm-dd',
  });


//******establecer dia lunes a domingo de la semana presente
function establecerFechas(campo, fechaActual){
	var fecha=new Date(fechaInical); 
	fecha.setDate(fecha.getDate()+7);
	fecha.setMonth(fecha.getMonth());
	fecha.setUTCFullYear(fecha.getUTCFullYear());
	$('#'+campo).datepicker('option', 'minDate', fechaInical); 
	$('#'+campo).datepicker('option', 'maxDate', fecha);
	$('#'+campo).val(fechaActual);
}  

//******establecer por mes
function establecerFechasMes(campo, fechaActual, fechaFinal){
	//var fecha=new Date(fechaInical); 
	//fecha.setDate(fecha.getDate()+31);
	//fecha.setMonth(fecha.getMonth());
	//fecha.setUTCFullYear(fecha.getUTCFullYear());
	$('#'+campo).datepicker('option', 'minDate', fechaActual); 
	$('#'+campo).datepicker('option', 'maxDate', fechaFinal);
	//$('#'+campo).val(fechaActual);
}
//*******setear campo y agregar placeholder
function setearCampo(campo, opt){
	
	switch (opt) { 
	case 1: 
		$("#"+campo+"").attr("placeholder", $("#"+campo+"").val());
		$("#"+campo+"").val('');
		break;
	case 2: 
		$("#"+campo+"").removeAttr('readonly');
		$("#"+campo+"").val('');
		break;
	case 3: 
		$("#"+campo+"").attr("placeholder", $("#"+campo+"").val());
		$("#"+campo+"").val('');
		$("#"+campo+"").focus();
	case 4:
		$("#"+campo+"").addClass("alertaCombo");;
		$("#"+campo+"").focus();
		break;		
	}
}

//*******calcular porcentajes o cantidad 
function calcularCantidad(campoCP,campoT,opt){
	var campoTxt = parseFloat($("#"+campoCP+"").val());
	var total = parseFloat($("#"+campoT+"").val())
	if(opt == 1){
		//****devolver porcentaje
		var resultado = (campoTxt/total) * 100;
	}else{
		//****devolver cantidad
		var resultado = (campoTxt*total)/ 100;
	}
	var resultado = resultado.toFixed(3); 
	return resultado;
}
//**************validar ingreso de informacion
function validarIngresoInfo(campoC,campoP,campoT,opt,msg='principal'){
	if(msg == 'detalle' ){
		fn_limpiar_detalle_cf();
	}else{
		fn_limpiar();
	}
	if(opt == 1){
		if(!$.trim($("#"+campoT+"").val())){
			if(msg == 'detalle' ){
				$("#"+campoT+"").addClass("alert-danger");
			}else{
				$("#"+campoT+"").addClass("alertaCombo");
			}
			$("#"+campoT+"").focus();
			setearCampo(campoC, 1);
			if(msg == 'detalle' ){
				$("#estadoDetalle").html("Debe ingresar primero el total de aves...!!").addClass("alerta");
			}else{
				mostrarMensaje("Debe ingresar primero el total de aves...!!", "FALLO");
			}
		    }else {
		    	if(!$.trim($("#"+campoC+"").val())){
		    	    setearCampo(campoP, 2);
		    	}else{
		  	    	if(parseFloat($("#"+campoC+"").val()) <= parseFloat($("#"+campoT+"").val()) ){ 
		    			$("#"+campoP+"").val(calcularCantidad(campoC,campoT,opt));
		        		$("#"+campoP+"").attr('readonly','readonly');
		  	    	}else{
		  	    		 setearCampo(campoC, 3);
		  	    		if(msg == 'detalle' ){
		  	    			$("#estadoDetalle").html("La cantidad ingresada no puede ser mayor al TOTAL ingresado...!!").addClass("alerta");
		  	    		}else{
		  	    			mostrarMensaje("La cantidad ingresada no puede ser mayor al TOTAL ingresado...!!", "FALLO");
		  	    		}
		  	  	    }	
		        }
		}
	}else{
		if(!$.trim($("#"+campoT+"").val())){ 
			if(msg == 'detalle' ){
				$("#"+campoT+"").addClass("alert alert-danger");
			}else{
				$("#"+campoT+"").addClass("alertaCombo");
			}
			$("#"+campoT+"").focus();
			setearCampo(campoP, 1);
				if(msg == 'detalle' ){
					$("#estadoDetalle").html("Debe ingresar primero el total de aves...!!!!").addClass("alerta");
				}else{
					mostrarMensaje("Debe ingresar primero el total de aves...!!", "FALLO");
				}
		    }else {
		    	if(!$.trim($("#"+campoP+"").val())){
		    	    setearCampo(campoC, 2);
		    	}else{
		  	    	if($("#"+campoP+"").val() <= 100 ){ 
		    			$("#"+campoC+"").val(calcularCantidad(campoP,campoT,opt));
		        		$("#"+campoC+"").attr('readonly','readonly');
		  	    	}else{
		  	    		 setearCampo(campoP, 3);
		  	    		if(msg == 'detalle' ){
		  	    			$("#estadoDetalle").html("La cantidad ingresada no puede ser mayor al %100...!!").addClass("alerta");
		  	    		}else{
		  	    			mostrarMensaje("La cantidad ingresada no puede ser mayor al %100...!!", "FALLO");
		  	    		}
		  	    	     
		  	  	    }	
		        }
		}
	}
}

//***************** función para limpiar mensaje de detalle***************************
function fn_limpiar_detalle_cf() {
   // $(".form-control alert-danger").removeClass("alert-danger");
	$('#estadoDetalle').html('');
}
//***************** función para limpiar mensaje en panel de busqueda***************************
function fn_limpiar() {
	$(".alertaCombo").removeClass("alertaCombo");
//	$(".form-control alert-danger").removeClass("alert-danger");
	$('#estado').html('');
	//$('#estadoDetalle').html('');
}
//******************funcion para limpiar detalle******************
function fn_limpiar_detalle(){
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
} 

//**************validar ingreso de informacion
function validarIngresoInfoPost(campoC,campoT){
	
		if(!$.trim($("#"+campoT+"").val())){
			$("#"+campoT+"").addClass("alertaCombo");
			$("#"+campoT+"").focus();
			setearCampo(campoC, 1);
			mostrarMensaje("Debe ingresar primero el total de animales...!!", "FALLO");
		}else {
  	    	if(parseFloat($("#"+campoC+"").val()) <= parseFloat($("#"+campoT+"").val()) ){ 
  	    		$("#"+campoC+"").removeClass("alertaCombo");
  	    		mostrarMensaje("", "EXITO");
  	    	}else{
  	    		 setearCampo(campoC, 3);
  	    			mostrarMensaje("La cantidad ingresada no puede ser mayor al TOTAL de animales...!!", "FALLO");
  	  	    }	
		}

}






/**
 * jquery.wait - insert simple delays into your jquery method chains
 * @author Matthew Lee matt@madleedesign.com
 */

(function ($) {
    function jQueryDummy ($real, delay, _fncQueue) {
        // A Fake jQuery-like object that allows us to resolve the entire jQuery
        // method chain, pause, and resume execution later.

        var dummy = this;
        this._fncQueue = (typeof _fncQueue === 'undefined') ? [] : _fncQueue;
        this._delayCompleted = false;
        this._$real = $real;

        if (typeof delay === 'number' && delay >= 0 && delay < Infinity)
            this.timeoutKey = window.setTimeout(function () {
                dummy._performDummyQueueActions();
            }, delay);

        else if (delay !== null && typeof delay === 'object' && typeof delay.promise === 'function')
            delay.then(function () {
                dummy._performDummyQueueActions();
            });

        else if (typeof delay === 'string')
            $real.one(delay, function () {
                dummy._performDummyQueueActions();
            });

        else
            return $real;
    }

    jQueryDummy.prototype._addToQueue = function(fnc, arg){
        // When dummy functions are called, the name of the function and
        // arguments are put into a queue to execute later

        this._fncQueue.unshift({ fnc: fnc, arg: arg });

        if (this._delayCompleted)
            return this._performDummyQueueActions();
        else
            return this;
    };

    jQueryDummy.prototype._performDummyQueueActions = function(){
        // Start executing queued actions.  If another `wait` is encountered,
        // pass the remaining stack to a new jQueryDummy

        this._delayCompleted = true;

        var next;
        while (this._fncQueue.length > 0) {
            next = this._fncQueue.pop();

            if (next.fnc === 'wait') {
                next.arg.push(this._fncQueue);
                return this._$real = this._$real[next.fnc].apply(this._$real, next.arg);
            }

            this._$real = this._$real[next.fnc].apply(this._$real, next.arg);
        }

        return this;
    };

    $.fn.wait = function(delay, _queue) {
        // Creates dummy object that dequeues after a times delay OR promise

        return new jQueryDummy(this, delay, _queue);
    };

    for (var fnc in $.fn) {
        // Add shadow methods for all jQuery methods in existence.  Will not
        // shadow methods added to jQuery _after_ this!
        // skip non-function properties or properties of Object.prototype

        if (typeof $.fn[fnc] !== 'function' || !$.fn.hasOwnProperty(fnc))
            continue;

        jQueryDummy.prototype[fnc] = (function (fnc) {
            return function(){
                var arg = Array.prototype.slice.call(arguments);
                return this._addToQueue(fnc, arg);
            };
        })(fnc);
    }
})(jQuery);