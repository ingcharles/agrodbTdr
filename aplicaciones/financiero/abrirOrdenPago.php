<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	
	$res = $cc->abrirOrdenPago($conexion, $_POST['id']);
	$ordenPago = pg_fetch_assoc($res);
		
	$qoperador = $cc->buscarOperador($conexion, $ordenPago['identificador_operador']);
	$operadores = pg_fetch_assoc($qoperador);
	
	$qcliente = $cc->listaComprador($conexion, $ordenPago['identificador_operador']);
	$clientes = pg_fetch_assoc($qcliente);
	
	if(pg_num_rows($qoperador) == 0){
		$tipoUsuario='Cliente';
		$telefono = $clientes['telefono'];	
	} else {
		$tipoUsuario='Operador';
		$telefono = $operadores['telefono_uno'];
	}
	
	$correo = $clientes['correo'];
	

	/// INICIO EJAR
	$identificador = $_SESSION['usuario'];
	
	if($identificador==''){
		$sessionUsuario='inactivo';
	}else{
		$sessionUsuario='activo';
	}
	
	$iva = pg_fetch_result($cc->listarDatosInstitucion($conexion, $identificador), 0, 'iva');	
	///FIN EJAR

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
	<h1>Actualizar Orden</h1>
</header>

<div id="estado"></div>

<form id="datosDeposito" data-rutaAplicacion="financiero" data-opcion="actualizarOrdenPago" data-accionEnExito="ACTUALIZAR">
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<input type="hidden" id="opcion" name="opcion" value="0">
	<input type="hidden" id="idPago" name="idPago" value="<?php echo $ordenPago['id_pago'];?>" />
	
	<fieldset id="datosUsuario"  name="datosUsuario">
		<legend>Cabecera</legend>
		
		<div id="div1" data-linea="1">
				<label>Cliente</label> 
				<select id="tipoBusquedaCliente" name="tipoBusquedaCliente" disabled="disabled">
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
				<input type="text" id="txtClienteBusqueda" name="txtClienteBusqueda" disabled="disabled"/>							
		</div>
		
		<div id="div3" data-linea="1">
				<button type="button" id="btnBusquedaCliente" name="btnBusquedaCliente" disabled="disabled">Buscar</button>
		</div>	
		
		<div id="res_cliente" data-linea="4">
		<?php
		
			if(pg_num_rows($qoperador) == 0) {
				$valor = $clientes;
			}else{
				$valor = $operadores;
			}	
		
			echo'<div data-linea="5">
					<label>Razón Social: </label> 
					<input type="hidden" id="idCliente" name="idCliente" value="'.$valor['identificador'].'" >
					<input type="hidden" id="tipoIdentificacion" name="tipoIdentificacion" value="'.$valor['tipo_identificacion'].'" >
					<input id="razonSocial" name="razonSocial" value="'. $valor['razon_social'].'" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
				 </div>
				 <div data-linea="6">
					<label>Dirección: </label> 
					<input id="direccion" name="direccion" value="'.$valor['direccion'].'" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
				 </div>
				 <div data-linea="7">
					<label>Teléfono: </label> 
					<input id="telefono" name="telefono" value="'.$telefono.'" disabled="disabled" placeholder="Ej. 2222222" data-er="[0-9]{1,15}" title="99" />
				 </div>
				 <div data-linea="8">
					<label>Correo: </label> 
					<input id="correo" name="correo" value="'.$correo.'" disabled="disabled" placeholder="Ej. cuenta@gob.ec" title="99"/>
				 </div>';
		?>
		
		</div>
	</fieldset>
	<fieldset>
		<legend>Información adicional</legend>	
		<div data-linea="20">
			<label>Motivo</label>
				<input type="text" id="observacion" name="observacion" placeholder="Ej: observación" value="<?php echo $ordenPago['observacion'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
		</div>
	</fieldset>	
	
	<fieldset>
		<legend>Detalle</legend>
		<div data-linea="1">
			<label>Área</label>
			<select id="area" name="area" disabled="disabled">
				<option value="" selected="selected">Área....</option>
				<option value="SA">Sanidad Animal</option>
				<option value="SV">Sanidad Vegetal</option>
				<option value="IA">Inocuidad de los Alimentos</option>
				<option value="LT">Análisis de laboratorios</option>
				<option value="CGRIA">Control de Insumos Agropecuarios</option>
				<option value="AGR">Otros ingresos</option>
			</select>
		</div>
		
		<div data-linea="1">
			<label>Buscar codigo</label>
				<input type="search" id="codigo" name="codigo" disabled="disabled"/>
		</div>
		
		<div id="res_tarifario" data-linea="3"></div>
		
		<div data-linea="4">
			<label>Cantidad</label> 
				<!-- input type="number" step="0.01" id="cantidad" name="cantidad" disabled="disabled"/-->
				<input type="text" step="0.001" id="cantidad" name="cantidad" placeholder="Ej: 1234.561" data-er="^[0-9]+(\.[0-9]{1,3})?$" title="Ejemplo: 999.999" />
				<input type="hidden" id="valorTotal" name="valorTotal" value="<?php echo $ordenPago['total_pagar'];?>"/>
		</div>
		<div data-linea="4">
			<label>Descuento</label>
				<input	type="number" step="0.01" id="descuento" name="descuento" disabled="disabled"/>
		</div>	

		<div data-linea="5" class="info"></div>
				
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
							
							<?php 
							$contador = 0;
								$res = $cc->abrirDetallePago($conexion, $_POST['id']);
												
									while($fila = pg_fetch_assoc($res)){
										$numSecuencial = ++$contador;
										$transaccion = $fila['concepto_orden'];
										$cantidad = $fila['cantidad'];
										$precio = $fila['precio_unitario']*1;
										$descuento = $fila['descuento'];
										$ivaProducto = $fila['iva']; 
										$subsidio = $fila['subsidio'];
										
										$subTotalProducto = round($cantidad * $precio,2);
										//$subTotalProducto = $cantidad * $precio;
										$subTotalDescuento = $subTotalProducto - $descuento;
										
										$totalProducto = $subTotalDescuento + $ivaProducto;
															
											echo "<tr id='r_".$fila['id_servicio']."'>
													<td><button type='button' onclick='quitarItem(\"#r_".$fila['id_servicio']."\")' class='menos'>Quitar</button></td>
													<td>".$transaccion."</td>
													<td>".$cantidad."</td>
													<td>".$precio."</td>
													<td>".$subTotalProducto."</td>
													<td>".$descuento."</td>
													<td>".$subsidio."</td>
													<td>".$ivaProducto."</td>
													<td>".$totalProducto."</td> 
														<input id='idDeposito' name='idDeposito[]' value='".$fila['id_servicio']."' type='hidden'>
													    <input id='nombreDeposito' name='NombreDeposito[]' value='".$transaccion."' type='hidden'>
													    <input id='cantidadItem' name='cantidadItem[]' value='".$cantidad."' type='hidden'>
														<input id='precioUnitario' name='precioUnitario[]' value='".$precio."' type='hidden'>
														<input id='ivaIndividual' name='ivaIndividual[]' value='".$ivaProducto."' type='hidden'>
														<input id='totalIndividual' name='totalIndividual[]' value='".$totalProducto."' type='hidden'>
														<input id='descuentoUnidad' name='descuentoUnidad[]' value='".$descuento."' type='hidden'>
														<input id='subTotal' name='subTotal[]' value='".$subTotalProducto."' type='hidden'>
														<input id='subTotalDescuento' name='subTotalDescuento[]' value='".$subTotalDescuento."' type='hidden'>
														<input id='subsidio' name='subsidio[]' value='".$subsidio."' type='hidden'>
													
												</tr>";
									}   
							?>
							
													
							</tbody>
					  </table>
	</fieldset>
	
</form>

</body>

<script type="text/javascript">

var contador = 0;
var iva=<?php echo json_encode($iva); ?>;
var session_usuario = <?php echo json_encode($sessionUsuario); ?>;

$(document).ready(function(){
	distribuirLineas();
	$("#descuento").numeric(".");
	$("#cantidad").numeric(".");
	$("#descuento").val('0');	

	cargarValorDefecto("tipoBusquedaCliente","<?php echo $clientes['tipo_identificacion'];?>");

	total = sumarValor('totalIndividual');
	ivaTotal = sumarValor('ivaIndividual');
	subTotal = sumarValor('subTotalDescuento');

	$("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );

	$("#valorTotal").val(total);

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
		subsidio = $("#transaccion option:selected").attr("data-subsidio")*1;
	
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
				
				$("#detalles").append("<tr id='r_"+$("#transaccion").val()+"'>"+
											"<td><button type='button' onclick='quitarItem(\"#r_"+$("#transaccion").val()+"\")' class='menos'>Quitar</button></td>"+
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


$("#datosDeposito").submit(function(event){
	event.preventDefault();
	 $('#datosDeposito').attr('data-opcion','actualizarOrdenPago');
	 $('#datosDeposito').attr('data-destino','detalleItem');

	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;

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
	 /*	
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
	*/
		if($('#idDeposito').length == 0 ){
			error = true;
			$("#estado").html("Por favor ingrese uno o varios items").addClass("alerta");
		}

		if(Number($("#valorTotal").val()) > Number(200) && $('#txtClienteBusqueda').val()=='9999999999999'){
			$("#estado").html("No se permite emitir notas de pago a CONSUMIDOR FINAL por montos mayores a 200 dolares.").addClass("alerta");
		}else{
			if (!error){

				$("#datosUsuario input").removeAttr("disabled");
				$("#datosUsuario select").removeAttr("disabled");
				
				abrir($("#datosDeposito"),event,false); //Se ejecuta ajax, busqueda de sitio	
			}
		}	 
});

$("#btnBusquedaCliente").click(function(event){
	chequearCampos(this);
   	distribuirLineas();

});

function chequearCampos(form){	
	event.preventDefault();
	$('#datosDeposito').attr('data-opcion','accionesCliente');
	$('#datosDeposito').attr('data-destino','res_cliente');
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
		abrir($("#datosDeposito"),event,false); //Se ejecuta ajax, busqueda de sitio
	}
}

function esCampoValido(elemento){
	//var patron = /^[0-9]+$/g;
	//var patron = new RegExp($(elemento).attr("data-er"),"g");
	var patron = new RegExp("^[0-9]+$","g");
	return patron.test($(elemento).val());
}

$("#tipoBusquedaCliente").change(function(event){
	
	quitarAlertas();
	
	if($('#tipoBusquedaCliente').val() == '07'){
		$('#txtClienteBusqueda').val('9999999999999');
		$('#datosDeposito').attr('data-opcion','accionesCliente');
		$('#datosDeposito').attr('data-destino','res_cliente');
		$('#opcion').val('cliente');	
		abrir($("#datosDeposito"),event,false); //Se ejecuta ajax, busqueda de sitio
		distribuirLineas();
	}

	if($('#tipoBusquedaCliente').val() == '04'){
		$("#txtClienteBusqueda").attr("maxlength","13");
	}

	if($('#tipoBusquedaCliente').val() == '05'){
		$("#txtClienteBusqueda").attr("maxlength","10");
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
	
		$('#datosDeposito').attr('data-opcion','accionesCliente');
		$('#datosDeposito').attr('data-destino','res_tarifario');
		$('#opcion').val('tarifario');	
		abrir($("#datosDeposito"),event,false); //Se ejecuta ajax, busqueda de sitio
		distribuirLineas();
		$("#codigo").val('');

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

var tipoUsuario= <?php echo json_encode($tipoUsuario); ?>;

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");

	if(tipoUsuario == 'Operador'){
		$("#datosUsuario input").attr("disabled","disabled");
		$("#datosUsuario select").attr("disabled","disabled");
	}else{
		$("#datosUsuario button").removeAttr("disabled");
	}
	
});

function CampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

</script>
</html>

