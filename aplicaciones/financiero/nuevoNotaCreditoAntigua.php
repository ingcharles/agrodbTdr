<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
		
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
	$cabeceraNotaCredito = pg_fetch_assoc($cc->abrirDatosEmisorAntiguo($conexion, $identificadorUsuarioRegistro));
	
	$distritos = $cc -> listarDistritosAntiguo($conexion);
	$institucion = pg_fetch_assoc($cc->listarDatosInstitucionAntiguo($conexion,$identificadorUsuarioRegistro));
	
	$numeroEstablecimientos = $cc -> listarEstablecimientosAntiguo($conexion);
	
	while($fila = pg_fetch_assoc($numeroEstablecimientos)){
		$establecimiento[]= array(numEstablecimiento=>$fila['numero_establecimiento'], ruc=>$fila['ruc']);
	}	
	
	/// INICIO EJAR
	
	if($identificadorUsuarioRegistro==''){
		$sessionUsuario='inactivo';
	}else{
		$sessionUsuario='activo';
	}
		
	///FIN EJAR

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>

<header>
	<h1>Nueva nota de crédito</h1>
</header>
<div id="estado"></div>

<fieldset>
	<legend>Datos del emisor</legend>
	<?php
		echo'<div data-linea="1">
			<label>RUC: </label> '.$cabeceraNotaCredito['ruc'].'
			</div>
			<div data-linea="2">
			<label>Nombre comercial: </label> '.$cabeceraNotaCredito['nombre_comercial'].'
			</div>
			<div data-linea="3">
			<label>Razón social: </label> '.$cabeceraNotaCredito['razon_social'].'
			</div>
			<div data-linea="4">
			<label>Dirección establecimiento: </label> '.$cabeceraNotaCredito['direccion'].'
			</div>';
	?>
</fieldset>

<form id='nuevoNotaCredito' data-rutaAplicacion='financiero' data-opcion='guardarNuevoNotaCreditoAntigua' data-destino="detalleItem">

	<input type="hidden" id="opcion" name="opcion" value="0">
	<input name="tipoDocumento" value="notaCredito" type="hidden"/> 
	<input name="idNotaCredito" id="idNotaCredito" type="hidden"/>
	 
	<fieldset>
		<legend>Datos del comprador</legend>
		
		<div id="div1" data-linea="1">
				<label>Cliente</label> 
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
		
		<div id="res_cliente" data-linea="4"></div>
		
	</fieldset>	
	<fieldset>
		<legend>Comprobante de venta que se modifica</legend>
		
		<div id="div1" data-linea="1">
				<label>Documento</label> 
				<select name="tipoBusquedaDocumento" id="tipoBusquedaDocumento">
					<option value="01">Factura</option>
				</select>
		</div>
		
		<div id="div2" data-linea="1">
			<label>Ruc</label> 
				<select id="rucDistrito" name="rucDistrito">
				<option value="" selected="selected">Seleccione un RUC</option>
					<?php 
						while($fila = pg_fetch_assoc($distritos)){
							echo '<option value="' . $fila['ruc'] . '">' . $fila['ruc'] . '</option>';
						}
					?>
				</select> 
		</div>
		
		<div id="div3" data-linea="2">
				<label>Establecimiento</label>
		 		<select id="numeroEstablecimiento" name="numeroEstablecimiento">
				</select>
		</div>
		<div id="div4" data-linea="2">
				<label>P. Emisión</label>
		 		<select id="puntoEmision" name="puntoEmision">
		 			<option value="001">001</option>
		 			<!-- option value="002">002</option-->
				</select>
		</div>
		
		<div id="div5" data-linea="2">
				<label># Factura</label>
				<input type="text" id="txtDocumentoBusqueda" name="txtDocumentoBusqueda" placeholder=" Ej.: 000000001"/>	
						
		</div>
		<div id="div6" data-linea="3">
				<button type="button" id="btnBusquedaDocumento" name="btnBusquedaDocumento">Buscar</button>
		</div>	
		
				
				
		<div data-linea="20">
			<label>Motivo</label>
				<input	type="text" id="motivo" name="motivo" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$"/>
		</div>
	</fieldset>	
	
	<div id="res_documento"></div>
	
	<fieldset>
		<legend>Detalle de la nota de crédito</legend>
		<div data-linea="1">
			<label>Área</label>
			<select id="area" name="area" >
				<!-- option value="" selected="selected">Área....</option-->
				<!-- option value="SA">Sanidad Animal</option>
				<option value="SV">Sanidad Vegetal</option>
				<option value="IA">Inocuidad de los Alimentos</option>
				<option value="LT">Análisis de laboratorios</option>
				<option value="CGRIA">Control de Insumos Agropecuarios</option>
				<option value="AGR">Otros ingresos</option-->
			</select>
		</div>
		
		<!-- div data-linea="1">
			<label>Buscar codigo</label>
				<input type="search" id="codigo" name="codigo" />
		</div-->
		
		<div id="res_tarifario" data-linea="3"></div>
		
		<div data-linea="4">
			<label>Cantidad</label> 
				<!-- input type="number" step="0.01" id="cantidad" name="cantidad" /-->
				<input type="text" step="0.001" id="cantidad" name="cantidad" placeholder="Ej: 1234.561" data-er="^[0-9]+(\.[0-9]{1,3})?$" title="Ejemplo: 999.999" />
				<input type="hidden" id="valorTotal" name="valorTotal" />
		</div>
		<div data-linea="4">
			<label>Descuento</label>
				<input	type="number" step="0.01" id="descuento" name="descuento"/>
		</div>	

		<div class="info"></div>
				
			<button type="button" onclick="agregarItem()" class="mas">Agregar Item</button>
				
						<table id="tablaDetalle">
							<thead>
								<tr>
									<th></th>
									<th>Concepto</th>
									<th>Cantidad</th>
									<th>Valor Unitario</th>
									<th>SubTotal</th>
									<th>Descuento</th>
									<th>IVA</th>
									<th>Total</th>								
								<tr>
							</thead> 
							
							<tbody id="detalles">
							</tbody>
					  </table>
	</fieldset>
	
	<fieldset>
		<div data-linea="5">
			<label id = "lclaveCertificado">Clave del certificado</label>
			<input type = "password" id="txtClaveCertificado" name="txtClaveCertificado" />									
		</div>
		<div id="div6">
			<button type="submit" id="btnClaveCertificado" name="btnClaveCertificado">Firmar</button>
		</div>	
	</fieldset>
		
</form>


</body>

<script type="text/javascript">

var contador = 0; 
var array_establecimiento= <?php echo json_encode($establecimiento); ?>;
var session_usuario = <?php echo json_encode($sessionUsuario); ?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
	$("#descuento").numeric(".");
	$("#cantidad").numeric(".");
	$("#descuento").val('0');

	cargarValorDefecto("rucDistrito","<?php echo $institucion['ruc'];?>");	

	sestablecimiento = '<option value="">Establecimiento....</option>';
	
	for(var i=0;i<array_establecimiento.length;i++){
		if(array_establecimiento[i]['ruc'] == $('#rucDistrito').val()){
			sestablecimiento += '<option value="'+array_establecimiento[i]['numEstablecimiento']+'">'+array_establecimiento[i]['numEstablecimiento']+'</option>';
		}   
    }	
	
	$('#numeroEstablecimiento').html(sestablecimiento);
	cargarValorDefecto("numeroEstablecimiento","<?php echo $institucion['numero_establecimiento'];?>");

    cargarValorDefecto("puntoEmision","<?php echo $institucion['punto_emision'];?>");

    if(session_usuario == 'inactivo'){
		$("#estado").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		$("#bGuardarOrdenPago").attr("disabled", "disabled");
	}
	 	
});


function agregarItem(){

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;



	if(!$.trim($("#cantidad").val()) || !CampoValido("#cantidad") || Number( $("#cantidad").val()) <= 0 ){
		error = true;
		$("#cantidad").addClass("alertaCombo");
	}

	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{

		numSecuencial = ++contador;	
		
		cantidad = $("#cantidad").val();
		precio = $("#transaccion option:selected").attr("data-precio");
		descuento = $("#descuento").val();
		auxIva = $("#transaccion option:selected").attr("data-iva");
	
		subTotalProducto = redondearNumero(Number(cantidad * precio));
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
			
				$("#detalles").append("<tr id='r_"+$("#transaccion").val()+"'><td><button type='button' onclick='quitarItem(\"#r_"+$("#transaccion").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#transaccion  option:selected").text()+"</td><td>"+$("#cantidad").val()+"</td><td>"+$("#transaccion option:selected").attr("data-precio")+"</td><td>"+subTotalProducto+"</td><td>"+descuento+"</td><td>"+ivaProducto+"</td><td>"+totalProducto+"</td><input id='idDeposito' name='idDeposito[]' value='"+$("#transaccion").val()+"' type='hidden'><input id='nombreDeposito' name='NombreDeposito[]' value='"+$("#transaccion  option:selected").text()+"' type='hidden'><input id='cantidad' name='cantidad[]' value='"+$("#cantidad").val()+"' type='hidden'><input id='precioUnitario' name='precioUnitario[]' value='"+$("#transaccion option:selected").attr("data-precio")+"' type='hidden'><input id='ivaIndividual' name='ivaIndividual[]' value='"+ivaProducto+"' type='hidden'><input id='totalIndividual' name='totalIndividual[]' value='"+totalProducto+"' type='hidden'><input id='descuentoUnidad' name='descuentoUnidad[]' value='"+descuento+"' type='hidden'><input id='subTotal' name='subTotal[]' value='"+subTotalProducto+"' type='hidden'><input id='subTotalDescuento' name='subTotalDescuento[]' value='"+subTotalDescuento+"' type='hidden'></tr>");
		
				total = sumarValor('totalIndividual');
				ivaTotal = sumarValor('ivaIndividual');
				subTotal = sumarValor('subTotalDescuento');
				
				$("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );
				$("#valorTotal").val(total);
				$("#estado").html("").removeClass('alerta');
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

$("#nuevoNotaCredito").submit(function(event){
	event.preventDefault();
	 $('#nuevoNotaCredito').attr('data-opcion','guardarNuevoNotaCreditoAntigua');
	 $('#nuevoNotaCredito').attr('data-destino','detalleItem');

	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(Number($("#valorTotal").val()) > Number($('#totalFactura').val())){
			error = true;
			$("#estado").html("El valor de la nota de credito es mayor al valor de la factura emitida.").addClass("alerta");
		}

		if($('#razonSocial').val() == ''){
			error = true;
			$("#razonSocial").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la razón social").addClass("alerta");
		}

		if($('#idPago').length == 0 ){
			error = true;
			$("#estado").html("Por favor realizar click en el boton buscar de la sección comprobante de venta.").addClass("alerta");
		}

	 	if($('#razonSocial').length == 0 ){
			error = true;
			$("#estado").html("Por favor realizar click en el boton buscar de la sección datos del comprador.").addClass("alerta");
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
			$("#estado").html("Por favor ingrese un solo correo eléctronico").addClass("alerta");
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

	 	if($('#tipoBusquedaDocumento').val() == ''){
			error = true;
			$("#tipoBusquedaDocumento").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el tipo de documento").addClass("alerta");
		}
	 	if($('#txtDocumentoBusqueda').val() == ''){
			error = true;
			$("#txtDocumentoBusqueda").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese el número de documento").addClass("alerta");
		}
	 	if($('#motivo').val() == ''){
			error = true;
			$("#motivo").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese un motivo").addClass("alerta");
		}
	
	 	if($('#idDeposito').length == 0 ){
			error = true;
			$("#estado").html("Por favor ingrese uno o varios detalles").addClass("alerta");
		}

	 	if($('#txtClaveCertificado').val() == ''){
			error = true;
			$("#txtClaveCertificado").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese la clave del pfx. ").addClass("alerta");
		}

	 	


	 	if (!error){
			ejecutarJson($(this));

			var resultado = $("#estado").html().split('-');
			

			if(resultado[0] =='Documento XML firmado correctamente.'){

				 $("#idNotaCredito").val(resultado[1]);				 
				 $('#nuevoNotaCredito').attr('data-opcion','mostrarDocumentoPDF');
				 $('#nuevoNotaCredito').attr('data-destino','detalleItem');

				 abrir($("#nuevoNotaCredito"),event,false);	
				 		 
				}else{

					// var resultado = $("#estado").html().split('-');
					 //$("#estado").html("Error al firmar el documento, clave incorrecta.");
					 $("#idNotaCredito").val(resultado[1]);
				}		
			 
			
		}else{
		 $("#estado").addClass("alerta");
	  }	

	/*
		if(Number($("#valorTotal").val()) > Number(200) && $('#txtClienteBusqueda').val()=='9999999999999'){
			$("#estado").html("No se permite emitir notas de credito a CONSUMIDOR FINAL por montos mayores a 200 dolares.").addClass("alerta");
			if (!error){
				ejecutarJson($(this));
				 if($("#estado").html()=='Documento XML firmado correctamente.'){
					 $('#nuevoNotaCredito').attr('data-opcion','mostrarDocumentoPDF');
					 $('#nuevoNotaCredito').attr('data-destino','detalleItem');
					abrir($("#nuevoNotaCredito"),event,false); //Se ejecuta ajax, busqueda de sitio	
				}
		}else{
			 $("#estado").html("Error en los campos ingresados.").addClass("alerta");
		  }	
	 }

		 */
});

/*$("#txtClienteBusqueda").change(function(event){
	chequearCampos(this);
   	distribuirLineas();
	
});*/

$('#motivo').on('input', function (e) {
    if (!/^[ a-z0-9áéíóúüñ@#-:;\/]$/i.test(this.value)) {
        this.value = this.value.replace(/[^ a-z0-9áéíóúüñ@#-:;\/]+/ig,"");
    }
});

$("#btnBusquedaCliente").click(function(event){

    $('#nuevoNotaCredito').attr('data-opcion','accionesCliente');
	 $('#nuevoNotaCredito').attr('data-destino','res_cliente');
	 $('#opcion').val('cliente');

	 event.preventDefault();

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

	if(!error){
		$("#estado").html("").removeClass('alerta');
		abrir($("#nuevoNotaCredito"),event,false); 
	}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.!").addClass("alerta");
	}

  	distribuirLineas();
	
});


function esCampoValido(elemento){
	//var patron = /^[0-9]+$/g;
	//var patron = new RegExp($(elemento).attr("data-er"),"g");
	var patron = new RegExp("^[0-9]+$","g");
	return patron.test($(elemento).val());
}

$("#btnBusquedaDocumento").click(function(event){
	 $('#nuevoNotaCredito').attr('data-opcion','accionesCliente');
	 $('#nuevoNotaCredito').attr('data-destino','res_documento');
	 $('#opcion').val('documento');

	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
	 if($('#tipoBusquedaDocumento').val() == ''){
			error = true;
			$("#tipoBusquedaDocumento").addClass("alertaCombo");
		}

		if(!error){
			$("#estado").html("").removeClass('alerta');
			abrir($("#nuevoNotaCredito"),event,false);

		}else{
			$("#estado").html("Por favor ingrese un tipo de documento.!").addClass("alerta");
		}
			
	 distribuirLineas();
});



$("#tipoBusquedaCliente").change(function(event){
	
	quitarAlertas();
	
	if($('#tipoBusquedaCliente').val() == '07'){
		$('#txtClienteBusqueda').val('9999999999999');
		$('#nuevoNotaCredito').attr('data-opcion','accionesCliente');
		$('#nuevoNotaCredito').attr('data-destino','res_cliente');
		$('#opcion').val('cliente');	
		abrir($("#nuevoNotaCredito"),event,false); //Se ejecuta ajax, busqueda de sitio
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
		$('#nuevoNotaCredito').attr('data-opcion','accionesCliente');
		$('#nuevoNotaCredito').attr('data-destino','res_tarifario');
		$('#opcion').val('tarifarioNotaCredito');	
		abrir($("#nuevoNotaCredito"),event,false); //Se ejecuta ajax, busqueda de sitio
		distribuirLineas();
		//$("#codigo").val('');

 });

/*$("#codigo").change(function(event){        	

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
			    		sbusqueda += '<option title = "'+item.concepto+ ' - VALOR: '+item.valor+' - UNIDAD MEDIDA: '+item.medida+'" value="'+item.idServicio+'" data-precio="'+item.valor+'" data-iva="'+item.iva+'">'+item.codigo+'- '+parteConcepto+'</option>';
			    	break;
		    	}
			}
	    	
	    }
	});

	$('#transaccion').html(sbusqueda);
	
});*/

$("#rucDistrito").change(function(){
	sestablecimiento ='0';
	sestablecimiento = '<option value="">Establecimiento...</option>';
    for(var i=0;i<array_establecimiento.length;i++){
	    if ($("#rucDistrito").val()==array_establecimiento[i]['ruc']){
	    	sestablecimiento += '<option value="'+array_establecimiento[i]['numEstablecimiento']+'">'+array_establecimiento[i]['numEstablecimiento']+'</option>';
		    }
   		}
    $('#numeroEstablecimiento').html(sestablecimiento);
    $('#numeroEstablecimiento').removeAttr("disabled");
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

