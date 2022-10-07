<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/GoogleAnalitica.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
		
	/// INICIO EJAR
	$identificador = $_SESSION['usuario'];
	
	if($identificador==''){
		$sessionUsuario='inactivo';
	}else{
		$sessionUsuario='activo';
	} 
	
	$iva = pg_fetch_result($cc->listarDatosInstitucion($conexion, $identificador), 0, 'iva');
		
?>

<body>

<header>
	<h1>Nueva Orden</h1>
</header>

<div id="estado"></div>

<form id='nuevoDeposito' data-rutaAplicacion='financiero' data-opcion='guardarNuevoOrdenPago' data-destino="detalleItem">

	<input type="hidden" id="opcion" name="opcion" value="0">
	<input type="hidden" id="idGupoSolicitudes" name="idGupoSolicitudes" value="0">
	<input type="hidden" name="idSolicitud" value="0"/>
	<input type="hidden" name="tipoSolicitud" value="Otros"/>
	
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
				<input	type="text" id="observacion" name="observacion" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$"/>
		</div>
	</fieldset>	
	
	<fieldset>
		<legend>Detalle</legend>
		<div data-linea="4">
			<label>Área</label>
			<select id="area" name="area" >
				<option value="" selected="selected">Área....</option>
				<option value="SA">Sanidad Animal</option>
				<option value="SV">Sanidad Vegetal</option>
				<option value="IA">Inocuidad de los Alimentos</option>
				<option value="LT">Análisis de laboratorios</option>
				<option value="CGRIA">Control de Insumos Agropecuarios</option>
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
			<!-- input type="number" step="0.01" id="cantidad" name="cantidad" /-->
			<input type="text" step="0.001" id="cantidad" name="cantidad" placeholder="Ej: 1234.561" data-er="^[0-9]+(\.[0-9]{1,3})?$" title="Ejemplo: 999.999" />
			<input type="hidden" id="valorTotal" name="valorTotal" />
		</div>
		<div data-linea="5">
			<label>Descuento</label>
			<input	type="number" step="0.01" id="descuento" name="descuento"/>
		</div>	

		<div data-linea="6" class="info"></div>
			<button type="button" onclick="agregarItem()" class="mas">Agregar Item</button>
				
						<table id="tablaDetalle">
							<thead>
								<tr>
									<th></th>
									<th>Concepto</th>
									<th>Cantidad</th>
									<th>V Unit.</th>
									<th>SubTotal</th>
									<th>Desc.</th>
									<th>Subsidio</th>
									<th>Iva</th>
									<th>Total</th>								
								<tr>
							</thead> 
							
							<tbody id="detalles">
							</tbody>
					  </table>
	</fieldset>
		
	<button id="bGuardarOrdenPago" type="submit" class="guardar">Guardar solicitud</button>		
		
</form>

</body>

<script type="text/javascript">

var contador = 0; 
var iva=<?php echo json_encode($iva); ?>;
var session_usuario = <?php echo json_encode($sessionUsuario); ?>;
var aplicar_descuento = <?php echo json_encode($aplicarDescuento); ?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
	$("#descuento").numeric(".");
	$("#cantidad").numeric(".");
	$("#descuento").val('0');	

	if(session_usuario == 'inactivo'){
		$("#estado").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#bGuardarOrdenPago").attr("disabled", "disabled");
	}
	
});


function agregarItem(){

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;


	if(!$.trim($("#cantidad").val()) || !CampoValido("#cantidad")){
		error = true;
		$("#cantidad").addClass("alertaCombo");
	}

	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{

		numSecuencial = ++contador;	
		
		cantidad = $("#cantidad").val();
		precio = $("#transaccion option:selected").attr("data-precio")*1;
		descuento = $("#descuento").val();
		auxIva = $("#transaccion option:selected").attr("data-iva");
		subsidio = $("#transaccion option:selected").attr("data-subsidio");
	
		subTotalProducto = redondearNumero(Number(cantidad * precio));
		subTotalSubsidio = redondearNumero(Number(cantidad * subsidio));
		subTotalDescuento = subTotalProducto - descuento;
		
		if(auxIva === "t"){
			ivaProducto = redondearNumero(Number(subTotalDescuento)*(iva/100));
		}else {
			ivaProducto = 0;
		}
	
		totalProducto = subTotalDescuento + ivaProducto;

		var total = 0;
		var ivaTotal = 0;
		var subTotal = 0;
	
		if($("#area").val()!="" && $("#transaccion").val()!="" && $("#cantidad").val()!="" ){
	
			if($("#detalles #r_"+$("#transaccion").val()).length==0){
			
				$("#detalles").append("<tr id='r_"+$("#transaccion").val()+"'><td>"+
										"<button type='button' onclick='quitarItem(\"#r_"+$("#transaccion").val()+"\")' class='menos'>Quitar</button></td>"+
										"<td>"+$("#transaccion  option:selected").text()+"</td>"+
										"<td>"+$("#cantidad").val()+"</td>"+
										"<td>"+precio+"</td>"+
										"<td>"+subTotalProducto+"</td>"+
										"<td>"+descuento+"</td>"+
										"<td>"+subTotalSubsidio+"</td>"+
										"<td>"+ivaProducto+"</td>"+
										"<td>"+totalProducto+"</td>"+
										"<input id='idDeposito' name='idDeposito[]' value='"+$("#transaccion").val()+"' type='hidden'>"+
										"<input id='nombreDeposito' name='NombreDeposito[]' value='"+$("#transaccion  option:selected").text()+"' type='hidden'>"+
										"<input id='cantidadItem' name='cantidadItem[]' value='"+$("#cantidad").val()+"' type='hidden'>"+
										"<input id='precioUnitario' name='precioUnitario[]' value='"+$("#transaccion option:selected").attr("data-precio")+"' type='hidden'>"+
										"<input id='ivaIndividual' name='ivaIndividual[]' value='"+ivaProducto+"' type='hidden'>"+
										"<input id='totalIndividual' name='totalIndividual[]' value='"+totalProducto+"' type='hidden'>"+
										"<input id='descuentoUnidad' name='descuentoUnidad[]' value='"+descuento+"' type='hidden'>"+
										"<input id='subTotal' name='subTotal[]' value='"+subTotalProducto+"' type='hidden'>"+
										"<input id='subTotalDescuento' name='subTotalDescuento[]' value='"+subTotalDescuento+"' type='hidden'>"+
										"<input id='subsidio' name='subsidio[]' value='"+subTotalSubsidio+"' type='hidden'>"+
									"</tr>");
		
				total = sumarValor('totalIndividual');
				ivaTotal = sumarValor('ivaIndividual');
				subTotal = sumarValor('subTotalDescuento');
				
				
				$("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );
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
	ivaTotal = sumarValor('ivaIndividual');
	subTotal = sumarValor('subTotalDescuento');

	 $("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );
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



$("#nuevoDeposito").submit(function(event){
	event.preventDefault();
	 $('#nuevoDeposito').attr('data-opcion','guardarNuevoOrdenPago');
	 $('#nuevoDeposito').attr('data-destino','detalleItem');

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
		
		if(!$.trim($("#correo").val()) || !CampoValido("#correo")){
			error = true;
			$("#correo").addClass("alertaCombo");
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
				abrir($("#nuevoDeposito"),event,false); //Se ejecuta ajax, busqueda de sitio
					
			}
		}	 
});

$('#observacion').on('input', function (e) {
    if (!/^[ a-z0-9áéíóúüñ@#-:;\/]$/i.test(this.value)) {
        this.value = this.value.replace(/[^ a-z0-9áéíóúüñ@#-:;\/]+/ig,"");
    }
});

$("#btnBusquedaCliente").click(function(event){

	event.preventDefault();
	$('#nuevoDeposito').attr('data-opcion','accionesCliente');
	$('#nuevoDeposito').attr('data-destino','res_cliente');
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
		abrir($("#nuevoDeposito"),event,false); 
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
		$('#nuevoDeposito').attr('data-opcion','accionesCliente');
		$('#nuevoDeposito').attr('data-destino','res_cliente');
		$('#opcion').val('cliente');	
		abrir($("#nuevoDeposito"),event,false); //Se ejecuta ajax, busqueda de sitio
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
	$('#nuevoDeposito').attr('data-opcion','accionesCliente');
	$('#nuevoDeposito').attr('data-destino','res_tarifario');
	$('#opcion').val('tarifario');	
	abrir($("#nuevoDeposito"),event,false); //Se ejecuta ajax, busqueda de sitio
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
			    		sbusqueda += '<option title = "'+item.concepto+ ' - VALOR: '+item.valor+' - UNIDAD MEDIDA: '+item.medida+'" value="'+item.idServicio+'" data-precio="'+item.valor+'" data-subsidio="'+item.subsidio+'" data-iva="'+item.iva+'">'+item.codigo+'- '+parteConcepto+'</option>';
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

