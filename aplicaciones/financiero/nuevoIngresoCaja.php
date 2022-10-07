<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$cc = new ControladorCertificados();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' href='../general/estilos/agrodb_papel.css' >
<link rel='stylesheet' href='../general/estilos/agrodb.css'>
</head>
<body>

<header>
	<h1>Nuevo ingreso de caja</h1>
</header>

<div id="estado"></div>

<form id='nuevoIngresoCaja' data-rutaAplicacion='financiero' data-opcion='guardarNuevoIngresoCaja' data-destino="detalleItem">

	<input type="hidden" id="opcion" name="opcion" value="0">
	<input type="hidden" name="tipoSolicitud" value="Ingreso Caja"/>
	
	<fieldset>
		<legend>Datos del comprador</legend>
		<div id="div1" data-linea="1">
				<label id= ltipoBusquedaCliente >Cliente</label> 
				<select id="tipoBusquedaCliente" name="tipoBusquedaCliente">
					<option value = ''>Seleccione....</option>
					<option value="04">Ruc</option>
					<option value="05">Cédula</option>
					<option value="06">Pasaporte</option>
					<option value="07">Consumidor final</option>
					<!-- option value="08">Identificacion del exterior</option-->
					<option value="01">Razón Social</option>
				</select>
		</div>
		
		<div id="div2" data-linea="1">
			<input type="text" id="txtClienteBusqueda" name="txtClienteBusqueda"/>									
		</div>
		
		<div id="div3" data-linea="1">
			<button type="button" id="btnBusquedaCliente" name="btnBusquedaCliente">Buscar</button>
		</div>	
		
		<div id="res_cliente" data-linea="2"></div>
		
	</fieldset>
	<fieldset>
		<legend>Información adicional</legend>
		<div data-linea="3">
			<label>Motivo</label>
				<input	type="text" id="observacion" name="observacion" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" maxlength="100"/>
		</div>
	</fieldset>	
	
	<fieldset>
		<legend>Detalle</legend>
		<div data-linea="4">
			<label>Área</label>
			<select id="area" name="area" >
				<option value="" selected="selected">Área....</option>
				<option value="AGR">Otros ingresos</option>
			</select>
		</div>
		
		<div data-linea="4">
			<label>Buscar codigo</label>
			<input type="search" id="codigo" name="codigo" />
		</div>
		
		<div id="res_tarifario" data-linea="3"></div>
		
		<div data-linea="5">
			<label>Cantidad</label> 
			<!--input type="number" step="0.01" id="cantidad" name="cantidad" /-->
			<input type="text" step="0.001" id="cantidad" name="cantidad" placeholder="Ej: 1234.561" data-er="^[0-9]+(\.[0-9]{1,3})?$" title="Ejemplo: 999.999" />
			<input type="hidden" id="valorTotal" name="valorTotal" />
		</div>
		
		<div data-linea="5">
			<label>Valor unitario</label> 
			<!-- input type="number" step="0.01" id="valorUnitario" name="valorUnitario" /-->
			<input type="text" step="0.001" id="valorUnitario" name="valorUnitario" placeholder="Ej: 1234.561" data-er="^[0-9]+(\.[0-9]{1,3})?$" title="Ejemplo: 999.999" />
			<!-- input type="hidden" id="valorUnitario" name="valorUnitario" /-->
		</div>
		
		<div data-linea="6" class="info"></div>
			<button type="button" onclick="agregarItem()" class="mas">Agregar Item</button>
				
						<table id="tablaDetalle">
							<thead>
								<tr>
									<th></th>
									<th>Concepto</th>
									<th>Cantidad</th>
									<th>Valor Unitario</th>
									<th>SubTotal</th>
									<th>Total</th>								
								<tr>
							</thead> 
							
							<tbody id="detalles">
							</tbody>
					  </table>
	</fieldset>
		
	<button type="submit" class="guardar">Guardar solicitud</button>		
		
</form>

</body>

<script type="text/javascript">

var contador = 0; 

function agregarItem(){

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;


	if(!$.trim($("#cantidad").val()) || !CampoValido("#cantidad") || Number( $("#cantidad").val()) <= 0 ){
		error = true;
		$("#cantidad").addClass("alertaCombo");
	}

	if(!$.trim($("#valorUnitario").val()) || !CampoValido("#valorUnitario") || Number( $("#valorUnitario").val()) <= 0 ){
		error = true;
		$("#valorUnitario").addClass("alertaCombo");
	}

	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{

		numSecuencial = ++contador;	
		
		cantidad = $("#cantidad").val();
		precio = $("#valorUnitario").val(); 
		
		subTotalProducto =redondearNumero(Number(cantidad * precio));
		subTotalDescuento = subTotalProducto;
		
		totalProducto = subTotalDescuento;
	
		var total = 0;
		var subTotal = 0;
	
		if($("#area").val()!="" && $("#transaccion").val()!="" && $("#cantidad").val()!="" && $("#valorUnitario").val()!="" ){
	
			if($("#detalles #r_"+$("#transaccion").val()).length==0){
			
				$("#detalles").append("<tr id='r_"+$("#transaccion").val()+"'><td><button type='button' onclick='quitarItem(\"#r_"+$("#transaccion").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#transaccion  option:selected").text()+"</td><td>"+$("#cantidad").val()+"</td><td>"+precio+"</td><td>"+subTotalProducto+"</td><td>"+totalProducto+"</td><input id='idDeposito' name='idDeposito[]' value='"+$("#transaccion").val()+"' type='hidden'><input id='nombreDeposito' name='NombreDeposito[]' value='"+$("#transaccion  option:selected").text()+"' type='hidden'><input id='cantidad' name='cantidad[]' value='"+$("#cantidad").val()+"' type='hidden'><input id='precioUnitario' name='precioUnitario[]' value='"+precio+"' type='hidden'><input id='totalIndividual' name='totalIndividual[]' value='"+totalProducto+"' type='hidden'><input id='subTotal' name='subTotal[]' value='"+subTotalProducto+"' type='hidden'><input id='subTotalDescuento' name='subTotalDescuento[]' value='"+subTotalDescuento+"' type='hidden'></tr>");
		
				total = sumarValor('totalIndividual');
				subTotal = sumarValor('subTotalDescuento');
				$("div.info").html('Total : '+subTotal+ '='+total );
				$("#valorTotal").val(total);
				$("#estado").html("").removeClass('alerta');
			}
		}
	}
 }

function redondearNumero(num) {    
    return +(Math.round(num + "e+2")  + "e-2");
}

function quitarItem(fila){

	$("#detalles tr").eq($(fila).index()).remove();

	total = sumarValor('totalIndividual');
	subTotal = sumarValor('subTotalDescuento');

	 $("div.info").html('Total : '+subTotal+ '='+total );
	 $("#valorTotal").val(total);
	
	 distribuirLineas();
}

function sumarValor(campo){
	var valor = 0; 
	
	$('input[id="'+campo+'"]').each(function(e){   
		valor += Number($(this).val());
		valor = redondearNumero(valor);
    });

    return valor;
}

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
	$("#cantidad").numeric(".");
		
});

$("#nuevoIngresoCaja").submit(function(event){
	event.preventDefault();
	 $('#nuevoIngresoCaja').attr('data-opcion','guardarNuevoIngresoCaja');
	 $('#nuevoIngresoCaja').attr('data-destino','detalleItem');

	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;	

		if($('#razonSocial').length == 0 ){
			error = true;
			$("#estado").html("Por favor realizar click en el boton buscar de la sección datos del comprador.").addClass("alerta");
		}

		if($('#razonSocial').val() == ''){
			error = true;
			$("#razonSocial").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la razón social").addClass("alerta");
		}

		if($('#direccion').val() == ''){
			error = true;
			$("#direccion").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la dirección").addClass("alerta");
		}

		if($('#telefono').val() == ''){
			error = true;
			$("#telefono").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el teléfono").addClass("alerta");
		}
		if($('#correo').val() == ''){
			error = true;
			$("#correo").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese correo eléctronico").addClass("alerta");
		}
		
	 	if($('#txtClienteBusqueda').val() == ''){
			error = true;
			$("#txtClienteBusqueda").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el número de identificación del usuario").addClass("alerta");
		}

	 	if($('#tipoBusquedaCliente').val() == ''){
			error = true;
			$("#tipoBusquedaCliente").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el tipo de identificación del usuario").addClass("alerta");
		}

		if($('#idDeposito').length == 0 ){
			error = true;
			$("#estado").html("Por favor ingrese uno o varios detalles").addClass("alerta");
		}

		if($("#tipoBusquedaCliente").val() == '01'){
			
			if(!esCampoValido("#ruc") || $("#ruc").val().length != $("#ruc").attr("maxlength")){
				error = true;
				$("#ruc").addClass("alertaCombo");
				$("#estado").html("Ruc debe tener 13 digitos numéricos.!").addClass('alerta');
			}

		}

		if(Number($("#valorTotal").val()) > Number(200) && $('#txtClienteBusqueda').val()=='9999999999999'){
			$("#estado").html("No se permite emitir notas de pago a CONSUMIDOR FINAL por montos mayores a 200 dolares.").addClass("alerta");
		}else{
			if (!error){
				$("#estado").html("").removeClass('alerta');
				abrir($("#nuevoIngresoCaja"),event,false); //Se ejecuta ajax, busqueda de sitio
					
			}
		}	 
});

$("#btnBusquedaCliente").click(function(event){

	event.preventDefault();
	$('#nuevoIngresoCaja').attr('data-opcion','accionesCliente');
	$('#nuevoIngresoCaja').attr('data-destino','res_cliente');
	$('#opcion').val('cliente');

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#tipoBusquedaCliente").val())){
		error = true;
		$("#tipoBusquedaCliente").addClass("alertaCombo");
	}


	if($('#tipoBusquedaCliente').val() == '04' || $('#tipoBusquedaCliente').val() == '05'){
		if(!esCampoValido("#txtClienteBusqueda") || $("#txtClienteBusqueda").val().length != $("#txtClienteBusqueda").attr("maxlength")){
			error = true;
			$("#txtClienteBusqueda").addClass("alertaCombo");
		}
	}

	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.!").addClass('alerta');
		quitarAlertas();
	}else{
		$("#estado").html("").removeClass('alerta');
		abrir($("#nuevoIngresoCaja"),event,false); 
	}
	distribuirLineas();
});


function esCampoValido(elemento){
	var patron = new RegExp("^[0-9]+$","g");
	return patron.test($(elemento).val());
}


$("#tipoBusquedaCliente").change(function(event){
	
	quitarAlertas();
	
	if($('#tipoBusquedaCliente').val() == '07'){
		$('#txtClienteBusqueda').val('9999999999999');
		$('#nuevoIngresoCaja').attr('data-opcion','accionesCliente');
		$('#nuevoIngresoCaja').attr('data-destino','res_cliente');
		$('#opcion').val('cliente');	
		abrir($("#nuevoIngresoCaja"),event,false); //Se ejecuta ajax, busqueda de sitio
		distribuirLineas();
	}

	if($('#tipoBusquedaCliente').val() == '04'){
		$("#txtClienteBusqueda").attr("maxlength","13");
	}

	if($('#tipoBusquedaCliente').val() == '05'){
		$("#txtClienteBusqueda").attr("maxlength","10");
	}

	if($('#tipoBusquedaCliente').val() == '01'){
		$("#ruc").attr("maxlength","13");
	}

});

function quitarAlertas(){
	$("#razonSocial").val('');
	$("#direccion").val('');
	$("#telefono").val('');
	$("#correo").val('');
	$("#txtClienteBusqueda").val('');
}

$("#area").change(function(event){
	$('#nuevoIngresoCaja').attr('data-opcion','accionesCliente');
	$('#nuevoIngresoCaja').attr('data-destino','res_tarifario');
	$('#opcion').val('tarifario');	
	abrir($("#nuevoIngresoCaja"),event,false); //Se ejecuta ajax, busqueda de sitio
	$("#codigo").val('');
	distribuirLineas();

 });

$("#codigo").change(function(event){        	
	sbusqueda ='0';
	$.map(array_tarifario, function(item) {
	    if (item.codigo.indexOf($("#codigo").val()) >= 0) {

	    	if (item.idCategoria != '1'){
		    	
		    	switch(item.idCategoria){
			    	case '2':
			    		sbusqueda += '<optgroup label="'+item.codigo+'- '+item.concepto+'">';
			    	break;
			    	case '3':
			    		var concepto = item.concepto;
			    		if(concepto.length > 100){
			    			var parteConcepto = concepto.substring(0, 100)+'...';
					    }else{
					    	var parteConcepto = concepto;
						}
			    		sbusqueda += '<option title = "'+item.concepto+ ' - VALOR: '+item.valor+' - UNIDAD MEDIDA: '+item.medida+'" value="'+item.idServicio+'">'+item.codigo+'- '+parteConcepto+'</option>';
			    	break;
		    	}
			}
	    	
	    }
	});

	$('#transaccion').html(sbusqueda);
	
});

function CampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
</script>
<style type="text/css">
#tablaDetalle td, #tablaDetalle th
{
	font-size:1em;
	border:1px solid rgba(0,0,0,.1);
	padding:3px 7px 2px 7px;
}
</style>
</html>

