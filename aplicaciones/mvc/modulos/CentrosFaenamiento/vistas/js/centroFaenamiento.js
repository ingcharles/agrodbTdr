/* 
 * funciones genericas de javascript
 * 
 * 
 */

//***************************************************************
function esCampoValidoExp(elemento,exp){ 
	if(exp==0)var patron = new RegExp($(elemento).attr("data-er"),"g");
	if(exp==1)var patron = new RegExp("^[0-9]{13}");
	if(exp==2)var patron = new RegExp("^[0-9]{10}");
	if(exp==3)var patron = new RegExp("^[0-9]");
   	return patron.test($(elemento).val());
    }
//***************** función para mensajes**************************
function fn_mensajes(validacion) {
	switch (validacion) { 
	case 1: 
		$('#estado').html('<span class="alerta">Campo obligatorio...!');
		break;
	case 2: 
		$('#estado').html('<span class="alerta">Debe ingresar 13 digitos...!');
		break;
	case 3: 
		$('#estado').html('<span class="alerta">Debe ingresar 10 digitos...!');
		break;
	case 4: 
		$('#estado').html('<span class="alerta">Verifique la información...!');
		break;
		break;
	default:
		$('#estado').html('');
	}
}

//***************** función para limpiar mensaje en panel de busqueda***************************
function fn_limpiar() {
	$(".alertaCombo").removeClass("alertaCombo");
	$('#estado').html('');
}
//******************funcion para limpiar detalle******************
function fn_limpiar_detalle(){
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
} 

//***************************************************************
//***************************************************************