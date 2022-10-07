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
		$('#estado').html('<span class="alerta">Debe llenar mínimo un campo (RUC / CI ó Razón Social )...!');
		break;
	case 3: 
		$('#estado').html('<span class="alerta">Debe seleccionar una provincia...!');
		break;
	case 4: 
		$('#estado').html('<span class="alerta">Verifique la información...!');
		break;
	case 5: 
		$('#estado').html('<span class="alerta">Debe seleccionar los campos obligatorios...!');
		break;
	case 4: 
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