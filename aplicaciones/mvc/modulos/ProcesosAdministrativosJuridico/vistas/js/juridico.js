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

$("#fecha_creacion").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
  });

$("#fecha_creacion").click(function () {
	 $(this).val('');
});
$("#fecha_desde").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {

    	var fecha=new Date($('#fecha_desde').datepicker('getDate')); 	 
    	var fechaSalida=fecha.setDate(fecha.getDate());
    	
    	fecha.setDate(fecha.getDate());
	    fecha.setMonth(fecha.getMonth()+5);
	    fecha.setUTCFullYear(fecha.getUTCFullYear());  
	
    	$('#fecha_hasta').datepicker('option', 'minDate', $("#fecha_desde" ).val());
		$('#fecha_hasta').datepicker('option', 'maxDate', fecha);
	  	
    }
  });


$("#fecha_hasta").datepicker({
	changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
  });


//***************************************************************