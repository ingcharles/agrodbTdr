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

//***************** función para limpiar mensaje en panel de busqueda***************************
function fn_limpiar() {
	$(".alertaCombo").removeClass("alertaCombo");
	$('#estado').html('');
}
//******************funcion para limpiar detalle******************
function fn_limpiar_detalle(){
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
} 

//********************formatear campo de fecha*******************************************
$("#fecha_inicio").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {
    	$("#fecha_fin").removeAttr("disabled");
	  	$('#fecha_fin').datepicker('option', 'minDate', $("#fecha_inicio" ).val()); 
    }
    	
  });

$("#fecha_produccion_suero").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    maxDate: 0 
  });

$("#fecha_fin").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {
    	var fecha=new Date($('#fecha_inicio').datepicker('getDate'));
	  	$('#fecha_fin').datepicker('option', 'minDate', $("#fecha_inicio" ).val()); 
		fecha.setDate(fecha.getDate());
	  	fecha.setMonth(fecha.getMonth());
		fecha.setUTCFullYear(fecha.getUTCFullYear());  
    }
  });
//***************************************************************