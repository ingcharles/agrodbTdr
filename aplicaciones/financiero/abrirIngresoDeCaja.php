<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorUsuarios.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorFinanciero.php';

	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$ca = new ControladorAreas();
	$cu = new ControladorUsuarios();
	$cca = new ControladorCatalogos();
	$cf = new ControladorFinanciero();
	
	$cabeceraIngresoCaja = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $_POST['id']));
	$detalleIngresoCaja = $cc->abrirDetallePago($conexion, $_POST['id']);
	$detalleFormaIngresoCaja = $cc->abrirLiquidarOrdenPago($conexion, $_POST['id']);	
	$banco = $cca->listarEntidadesBancariasAgrocalidad($conexion);
	$identificador = $_SESSION['usuario'];
	
	$saldoDisponible = pg_fetch_assoc($cf->obtenerMaxSaldo($conexion,$cabeceraIngresoCaja['identificador_operador']));
	
	$cuentasBancarias = $cca->listarCuentasBancarias($conexion);
	while($fila = pg_fetch_assoc($cuentasBancarias)){
		$detalleCuentaBancaria[]= array(idCuenta=>$fila['id_cuenta_bancaria'], idBanco=>$fila['id_banco'], numeroCuenta=>$fila['numero_cuenta'], tipoCuenta=>$fila['tipo_cuenta']);
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<header>
			<h1>Liquidar Ingreso de caja</h1>
	</header>
	<div id="estado"></div>

<fieldset id="ingresoCaja">
	<legend>Ingreso de caja <?php echo ' N°: '.$cabeceraIngresoCaja['numero_solicitud'];?> </legend>
	<?php
		echo'<div data-linea="1">
			<label>Localización: </label> '.$cabeceraIngresoCaja['localizacion'].'
		</div>
		<div data-linea="2">
			<label>Razón social: </label> '.$cabeceraIngresoCaja['razon_social'].'
		</div>
		<div data-linea="2">
			<label>Identificación: </label> '.$cabeceraIngresoCaja['identificador_operador'].'
		</div>
		<div data-linea="3">
			<label>Dirección: </label> '.$cabeceraIngresoCaja['direccion'].'
		</div>
		<div data-linea="4">
			<label>Fecha de ingreso: </label> '.$cabeceraIngresoCaja['fecha_orden_pago'].'
		</div>
		<div data-linea="5">
			<label>Observación: </label> '.$cabeceraIngresoCaja['observacion'].'
		</div>
			<div data-linea="6">
			<label>Total a pagar: $  </label> '.$cabeceraIngresoCaja['total_pagar'].'
			<input type="hidden" id="totalPagarOrden" name="totalPagarOrden" value="'.$cabeceraIngresoCaja['total_pagar'].'" >
		</div>
		<div data-linea="7">
			<label>Impresión: </label><a download="'.$cabeceraIngresoCaja['numero_solicitud'].'.pdf" href="'.$cabeceraIngresoCaja['orden_pago'].'" data-id="'.$cabeceraIngresoCaja['id_pago'].'" target= "_blank">Orden Ingreso de caja</a>
		</div>';
	?>
</fieldset>

<fieldset>
	<legend>Detalle</legend>
	   <table id="tablaIngresoCaja">
		<thead>
			<tr>
			<th>Concepto</th>
			<th>Cantidad</th>
			<th>Valor Unitario</th>
			<th>IVA</th>
			<th>Total</th>								
			</tr>
		</thead> 
		<?php
			while($fila = pg_fetch_assoc($detalleIngresoCaja)){ 
				echo'<tr>
						<td>'.$fila['concepto_orden'].' <b>UNIDAD MEDIDA:</b> '.$fila['unidad_medida'].'</td>	
						<td>'.$fila['cantidad'].'</td>	
						<td>'.$fila['precio_unitario']*'1'.'</td>
						<td>'.$fila['iva'].'</td>
						<td>'.$fila['total'].'</td>
					</tr>';
			}
		 ?> 
		</table>	
</fieldset>

<fieldset id="datosDetalleDeposito">
		<legend>Datos deposito</legend>
			 <table id="tablaIngresoCaja">
				<thead>
					<tr>   		
					<th>Forma de pago</th>
					<th>Entidad recaudadora</th>
					<th>Número de transacción</th>
					<th>Monto</th>
					</tr>
				</thead> 
				<?php
					while($fila = pg_fetch_assoc($detalleFormaIngresoCaja)){ 
					echo'<tr>
							<td>'.($fila['institucion_bancaria']!= ''? 'Institución Bancaria':'Pago efectivo').'</td>	
							<td>'.($fila['institucion_bancaria']!= ''? $fila['institucion_bancaria']:'').'</td>
							<td>'.($fila['transaccion']!= ''? $fila['transaccion']:'').'</td>
							<td>'.($fila['valor_deposito']).'</td>
						</tr>';
					}
		 		?> 
			</table>
	
</fieldset>

<form id="finalizarIngresoCaja" data-rutaAplicacion="financiero" data-opcion="finalizarIngresoCajaPago" data-destino="detalleItem">

	<fieldset>
		<legend>Generar factura</legend>
			<input type="hidden" name="id_pago" value="<?php echo $cabeceraIngresoCaja['id_pago'];?>"/>
			<input type="hidden" id="totalPagar" name="totalPagar" value="<?php echo $cabeceraIngresoCaja['total_pagar'];?>"/>
			<input type="hidden" id="idOperador" name="idOperador" value="<?php echo $cabeceraIngresoCaja['identificador_operador'];?>"/>
			<input type="hidden" id="numeroFactura" name="numeroFactura" value="<?php echo $cabeceraIngresoCaja['numero_factura'];?>"/>
			<input name="identificador" value="<?php echo $identificador;?>" type="hidden"/>
			<input name="tipoDocumento" value="factura" type="hidden"/> 
			<input type="hidden" id="opcion" name="opcion" value="0">
			<input type="hidden" id="saldo" name="saldo" /> 
			<input type="hidden" id="saldoDisponibleCLiente" name="saldoDisponibleCLiente" value = "<?php echo $saldoDisponible['saldo_disponible'];?>"/>
							
			<div data-linea="1">
					<label for="fpago">Forma de pago</label>
						<select id="fpago" name="fpago">
								<option value="" selected="selected">Seleccione una opción...</option>
								<option value="Deposito">Depósito</option>
								<option value="Efectivo">Efectivo</option>
								<option value="SaldoDisponible">Saldo disponible</option>
						</select>
			</div>
			<div data-linea="2">			
			<label id = "lbanco">Institución bancaria</label>
				<select name="banco" id="banco"	>
					<option value="0">Seleccione un banco...</option>
						<?php 
							while($fila = pg_fetch_assoc($banco)){
								echo '<option value="' . $fila['id_banco'] . '">' . $fila['nombre'] . '</option>';
							}
						?>
				</select>
			</div>
			
			<div data-linea="20">
				<input name="nombreBancoVerificacion" id="nombreBancoVerificacion" type="hidden" />
			</div>
			
			<div data-linea="3">
				<label id = "lcuentaBancaria">Cuenta bancaria</label>
				<select id="cuentaBancaria" name="cuentaBancaria">
				</select>
			</div>
			
								
			<div data-linea="5">				
			<label id = "lpapeletaBanco">Número de papeleta</label>
				<input type="text" name="papeletaBanco" id="papeletaBanco" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" /> 
			</div>	
							
			<div data-linea="6">
			<label id = "lfecha_deposito">Fecha depósito</label>
				<input type="text" id="fecha_deposito" name="fecha_deposito" /> 
			</div>		
			
			<div data-linea="6" class="cambioValor">
				<label id = "lvalor_depositado">Valor depositado</label>
					<input type="text" name="valor_depositado" id="valor_depositado" placeholder="Ej: 10.56" title="Ejemplo: 999.99"/>
			</div>			
			
			<div class="info"></div>
			
			<p id="res_numero_transaccion"></p>
						
			<button type="button" onclick="agregarItem()" class="mas">Agregar Item</button>
						<table id="tablaDetalle">
							<thead>
								<tr>
								<th></th>	
									<th>Forma de Pago</th>
									<th>Entidad recaudadora</th>
									<th>Cuenta</th>
									<th>Número de transacción</th>
									<th>Monto</th>
								<tr>
							</thead> 
							
							<tbody id="detalles">
							</tbody>
					  </table>
	</fieldset>
					  
		<button type="submit" id="btnClaveCertificado" name="btnClaveCertificado">Guardar ingreso</button>
	
</form>

<fieldset id="datosAutorizacion">
	<legend>Datos de autorización</legend>
		<div data-linea="1"><label>Estado: </label><?php echo $cabeceraIngresoCaja['estado_sri'];?></div>
		<div data-linea="4"><label>Observación: </label><?php echo ($cabeceraIngresoCaja['observacion_sri'] == 'null' ? 'Observación no disponible': $cabeceraIngresoCaja['observacion_sri']);?></div>
		
		<?php 
			if($cabeceraIngresoCaja['estado_sri'] = 'FINALIZADO'){
				echo '<div data-linea="5"><label>Factura Ingreso de Caja: </label><a href="'.$cabeceraIngresoCaja['factura'].'" target= "_blank">Descargar factura</a></div>';
			}
		?>
</fieldset>

</body>

<script type="text/javascript">

	var estado = <?php  echo json_encode($cabeceraIngresoCaja['estado']);?>;
	var estadoSRI = <?php  echo json_encode($cabeceraIngresoCaja['estado_sri']);?>;
	var saldoDisponible = <?php  echo json_encode($saldoDisponible['saldo_disponible']);?>;
	var idSaldo = <?php  echo json_encode($saldoDisponible['id_saldo']);?>;
	var array_cuenta= <?php echo json_encode($detalleCuentaBancaria); ?>;
	
	var contador = 0; 
	var numSecuencial = 0;
	var totalValorDepositado = 0;
	var valorRestante = 0;
		
	$(document).ready(function(){
		distribuirLineas();
		$("#datosDetalleDeposito").hide();
		$("#finalizarIngresoCaja").hide();
		$("#datosAutorizacion").hide();
		$("#lbanco").hide();
		$("#lpapeletaBanco").hide();
		$("#lfecha_deposito").hide();
		$("#lvalor_depositado").hide();
		$("#banco").hide();
		$("#papeletaBanco").hide();
		$("#fecha_deposito").hide();
		$("#valor_depositado").hide();
		
		$("#valor_depositado").numeric(".");

		 $("#papeletaBanco").on('paste', function(e){
			e.preventDefault();
	     });
	          
	     $("#papeletaBanco").on('copy', function(e){
			e.preventDefault();
	     });
	});
	
	$("#fecha_deposito").datepicker({
	    changeMonth: true,
	    changeYear: true
  	});

	if (estado=="3"){
		$("#finalizarIngresoCaja").show();
	}

	if (estado=="4"){
		$("#datosDetalleDeposito").show();

	}

	if (estado=="4" && estadoSRI == 'FINALIZADO'){
		$("#datosAutorizacion").show();
	}

	$('#fpago').change(function(){
		
		if ($("#fpago").val() =='Deposito'){
			$("#lfecha_deposito").hide();
			$("#fecha_deposito").hide();
			$("#lvalor_depositado").hide();
			$("#valor_depositado").hide();
				
			$("#monto").hide();
			$("#lmonto").hide();
			$("#lbanco").show();
			$("#banco").show();

			$(".cambioValor").attr('data-linea','6');
		} 

		if ($("#fpago").val() =='Efectivo' || $("#fpago").val() =='SaldoSENAE'){
				$("#banco").val(0);
				$("#lbanco").hide();
				$("#lpapeletaBanco").hide();
				$("#papeletaBanco").hide();				
				$("#lvalor_depositado").show();
				$("#banco").hide();
				$("#papeletaBanco").hide();
							
				$("#monto").hide();
				$("#lmonto").hide();
				$("#valor_depositado").show();
				$("#fecha_deposito").show();
				$("#lfecha_deposito").show();

				$("#lcuentaBancaria").hide();
				$("#cuentaBancaria").hide();

				$(".cambioValor").attr('data-linea','6');
			}

		if ($("#fpago").val() =='SaldoDisponible'){

			if (saldoDisponible > 0){
				$("#lbanco").hide();
				$("#banco").hide();
				$("#lpapeletaBanco").hide();
				$("#papeletaBanco").hide();
				
				$("#lvalor_depositado").show();
				$("#valor_depositado").show();
				$("#lfecha_deposito").show();
				$("#fecha_deposito").show();
				$("#monto").hide();
				$("#lmonto").hide();
				$("#estado").html('Saldo disponible $: '+saldoDisponible ).addClass('alerta');
				
			}else{
				$("#lbanco").hide();
				$("#banco").hide();
				$("#lpapeletaBanco").hide();
				$("#papeletaBanco").hide();
				$("#lvalor_depositado").hide();
				$("#valor_depositado").hide();
				$("#lfecha_deposito").hide();
				$("#fecha_deposito").hide();
				
				$("#monto").hide();
				$("#lmonto").hide();
				$("#estado").html("No dispone de saldo.").addClass("alerta");
			 }

			$(".cambioValor").attr('data-linea','6');
		}
		
		
		distribuirLineas();
	});

	$('#banco').change(function(){
		$("#lpapeletaBanco").show();
		$("#lfecha_deposito").show();
		$("#lvalor_depositado").show();
		$("#papeletaBanco").show();
		$("#fecha_deposito").show();
		$("#valor_depositado").show();
		$("#lnotaCredito").hide();
		$("#txtNotaCredito").hide();
	});
	
	function agregarItem(){
		
		$(".alertaCombo").removeClass("alertaCombo");
		$("#estado").html("").removeClass('alerta');
		var error = false;

		var identificadorCuenta = 0;
		var numeroCuentaBanco = 0;
		
		if(!$.trim($("#fpago").val())){
			error = true;
			$("#fpago").addClass("alertaCombo");
		}
		
		if(!$.trim($("#valor_depositado").val())){			
			error = true;
			$("#valor_depositado").addClass("alertaCombo");
		}
		
		if($("#fpago").val()== "Deposito"){

			if($("#banco").val() == '0' || !esCampoValido("#banco")){
				error = true;
				$("#banco").addClass("alertaCombo");
			}
		
			if($("#papeletaBanco").val()==""){
				error = true;
				$("#papeletaBanco").addClass("alertaCombo");
			}
		}

		if($("#fpago").val()== "Deposito" || $("#fpago").val()== "Efectivo" || $("#fpago").val() == "SaldoDisponible"){
			if($("#fecha_deposito").val()==""){
				error = true;
				$("#fecha_deposito").addClass("alertaCombo");
			}
		
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			
			numSecuencial = ++contador;
	
			if(Number($('#valor_depositado').val()) > 0 ){ 

				if($("#fpago").val() == 'Deposito'){

					if(($("#banco option:selected").text() == 'Sin entidad financiera') || ($("#banco option:selected").text() == 'Recaudación por VUE')){ 
						identificadorCuenta = 0;
						numeroCuentaBanco = 0; 
					}else{ 
						identificadorCuenta = $("#cuentaBancaria").val();
						numeroCuentaBanco = $("#cuentaBancaria option:selected").attr("data-numero");
					}	
					
					if($("#detalles #d_"+$('#papeletaBanco').val()+$('#banco').val()).length==0){
						$("#detalles").append("<tr id='d_"+$('#papeletaBanco').val()+$('#banco').val()+"'><td><button type='button' onclick='quitarItem(\"#d_"+$('#papeletaBanco').val()+$('#banco').val()+"\")' class='menos'>Quitar</button></td><td>"+$("#fpago option:selected").val()+"</td><td>"+$("#banco option:selected").text()+"</td><td>"+numeroCuentaBanco+"</td><td class= papeleta>"+$("#papeletaBanco").val()+"</td><td class = totalItems >"+$("#valor_depositado").val()+"</td><input id='formaPago' name='formaPago[]' value='"+$("#fpago option:selected").val()+"' type='hidden'><input id='valorDepositado' name='valorDepositado[]' value='"+$("#valor_depositado").val()+"' type='hidden'><input id='idBanco' name='idBanco[]' value='"+$("#banco").val()+"' type='hidden'><input id='nombreBanco' name='nombreBanco[]' value='"+$("#banco option:selected").text()+"' type='hidden'><input id='aPapeletaBanco' name='aPapeletaBanco[]' value='"+$("#papeletaBanco").val()+"' type='hidden'><input id='fechaDeposito' name='fechaDeposito[]' value='"+$("#fecha_deposito").val()+"' type='hidden'><input id='aIdNotaCredito' name='aIdNotaCredito[]' value='0' type='hidden'><input id='idCuentaBanco' name='idCuentaBanco[]' value='"+identificadorCuenta+"' type='hidden'><input id='numeroCuentaBanco' name='numeroCuentaBanco[]' value='"+numeroCuentaBanco+"' type='hidden'></tr>");
					}else{
						$("#estado").html("No puede ingresar dos transaciones similares, favor verifique.!").addClass("alerta");
					}
				}

						if($("#fpago").val() == 'Efectivo'){
							$("#detalles").append("<tr id='e_"+numSecuencial+"'><td><button type='button' onclick='quitarItem(\"#e_"+numSecuencial+"\")' class='menos'>Quitar</button></td><td>"+$("#fpago option:selected").val()+"</td><td>Cobro en oficina</td><td>0</td><td>"+$("#valor_depositado").val()+"</td><input id='formaPago' name='formaPago[]' value='"+$("#fpago option:selected").val()+"' type='hidden'><input id='valorDepositado' name='valorDepositado[]' value='"+$("#valor_depositado").val()+"' type='hidden'><input id='idBanco' name='idBanco[]' value='0' type='hidden'><input id='fechaDeposito' name='fechaDeposito[]' value='"+$("#fecha_deposito").val()+"' type='hidden'><input id='aIdNotaCredito' name='aIdNotaCredito[]' value='0' type='hidden'><input id='nombreBanco' name='nombreBanco[]' value='0' type='hidden'><input id='aPapeletaBanco' name='aPapeletaBanco[]' value='0' type='hidden'></tr>");
						}

						if($("#fpago").val() == 'SaldoDisponible'){							
							totalValorDepositado = sumarValor('valorDepositado');
							
							var valorRestante = Math.round((Number($('#totalPagar').val()) - Number(totalValorDepositado))*100)/100;

							if(Number($("#valor_depositado").val()) <= Number(valorRestante)){
								if(Number($("#valor_depositado").val()) <= Number(saldoDisponible)){										
									if($("#detalles #s_"+idSaldo).length==0){
										$("#detalles").append("<tr id='s_"+idSaldo+"'><td><button type='button' onclick='quitarItem(\"#s_"+idSaldo+"\")' class='menos'>Quitar</button></td><td>"+$("#fpago option:selected").val()+"</td><td>Cobro en oficina</td><td>0</td><td>"+$("#valor_depositado").val()+"</td><input id='formaPago' name='formaPago[]' value='"+$("#fpago option:selected").val()+"' type='hidden'><input id='valorDepositado' name='valorDepositado[]' value='"+$("#valor_depositado").val()+"' type='hidden'><input id='idBanco' name='idBanco[]' value='0' type='hidden'><input id='fechaDeposito' name='fechaDeposito[]' value='"+$("#fecha_deposito").val()+"' type='hidden'><input id='aIdNotaCredito' name='aIdNotaCredito[]' value='0' type='hidden'><input id='nombreBanco' name='nombreBanco[]' value='0' type='hidden'><input id='aPapeletaBanco' name='aPapeletaBanco[]' value='0' type='hidden'></tr>");	
										}else{
											$("#estado").html("No puede ingresar dos transacciones similares, favor verifique.!").addClass("alerta");
										}
									}else{
										$("#estado").html("Valor supera el saldo disponible").addClass('alerta');										
									}
							}else{
								$("#estado").html("El valor ingresado es mayor al valor de la orden de pago").addClass('alerta');	
							}	
						}

					totalValorDepositado = sumarValor('valorDepositado');
					$("div.info").html('Total : '+totalValorDepositado );
	
			}else{
				$("#valor_depositado").addClass("alertaCombo");
				$("#estado").html("Ingrese valores mayor a cero.!").addClass("alerta");
			}
		}
	}

	function sumarValor(campo){
		var valor = 0; 

		$('input[id="'+campo+'"]').each(function(e){   
			valor += Number($(this).val());
			valor = Math.round((valor)*100)/100;
	    });

	    return valor;
	}

	function quitarItem(fila){
		$("#detalles tr").eq($(fila).index()).remove();
		 
		totalValorDepositado = sumarValor('valorDepositado');
		$("div.info").html('Total : '+totalValorDepositado );	
	  	distribuirLineas();
	}
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#finalizarIngresoCaja").submit(function(event){
		event.preventDefault();

	 	$('#finalizarIngresoCaja').attr('data-opcion','finalizarIngresoCaja');
	 	$('#finalizarIngresoCaja').attr('data-destino','detalleItem');
	
		$("#estado").html("").removeClass('alerta');

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#valorDepositado').length == 0 ){
			error = true;
		}
		
		totalValorDepositado = sumarValor('valorDepositado');
		
		if(Number(totalValorDepositado) >= Number($('#totalPagar').val())){

			saldoDisponible = 	Math.round((Number(totalValorDepositado) - Number($('#totalPagar').val()))*100)/100;
			$("#saldo").val(saldoDisponible);
		}

	
		if($("#saldo").val() > 0){

			confirmar = confirm('Tiene un saldo adicional de: $ ' +$("#saldo").val()); 
		 	if(!confirmar){
				error = true;
							 		
	     	} 
 		}

	if(totalValorDepositado >= $('#totalPagar').val()){
	 if(!error){
		 ejecutarJson($(this));

			 $('#finalizarIngresoCaja').attr('data-opcion','mostrarDocumentoPDF');
			 $('#finalizarIngresoCaja').attr('data-destino','detalleItem');

			 abrir($("#finalizarIngresoCaja"),event,false);			 
		 	
	}else{
		 $("#estado").html("Por favor confirmar forma de pago.").addClass("alerta");
	  }
	}else{
			$("#fpago").addClass("alertaCombo");
			$("#estado").html("El monto ingresado no corresponde, favor verifique los campos.!").addClass("alerta");
		 }   
		 
});


$('#valor_depositado').change(function(event){

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){

		if($('#fpago').val() == 'Deposito'){
			
			$('#finalizarIngresoCaja').attr('data-opcion','accionesCliente');
			$('#finalizarIngresoCaja').attr('data-destino','res_numero_transaccion');
			$('#opcion').val('numeroTransaccion');

			abrir($("#finalizarIngresoCaja"),event,false);
				
		}else{
			$('#res_numero_transaccion').html('');
		}
	}
		
});	

$('#banco').change(function(event){

	$("#nombreBancoVerificacion").val($("#banco option:selected").text());


	if( ($('#banco option:selected').text() != 'Sin entidad financiera') && ($('#banco option:selected').text() != 'Recaudación por VUE')){

		$("#lcuentaBancaria").show();
		$("#cuentaBancaria").show();

		scuenta = '<option value="">Cuentas....</option>';

		for(var i=0;i<array_cuenta.length;i++){

			if(array_cuenta[i]['idBanco'] == $('#banco').val()){
				scuenta += '<option value="'+array_cuenta[i]['idCuenta']+'" data-numero= "'+array_cuenta[i]['numeroCuenta']+'">Cuenta '+array_cuenta[i]['tipoCuenta']+' -> '+array_cuenta[i]['numeroCuenta']+'</option>';
			}   
	    }	
		
		$('#cuentaBancaria').html(scuenta);
		
	}else{

		$("#lcuentaBancaria").hide();
		$("#cuentaBancaria").hide();

	}

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){

		if($('#fpago').val() == 'Deposito'){
			
			$('#finalizarIngresoCaja').attr('data-opcion','accionesCliente');
			$('#finalizarIngresoCaja').attr('data-destino','res_numero_transaccion');
			$('#opcion').val('numeroTransaccion');

			abrir($("#finalizarIngresoCaja"),event,false);
				
		}else{
			$('#res_numero_transaccion').html('');
		}

	}else{
		$("#lpapeletaBanco").show();
		$("#lfecha_deposito").show();
		$("#lvalor_depositado").show();
		$("#papeletaBanco").show();
		$("#fecha_deposito").show();
		$("#valor_depositado").show();
		$("#valor_depositado").show();
	}
	
});

$('#papeletaBanco').change(function(event){

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){

		if($('#fpago').val() == 'Deposito'){
			
			$('#finalizarIngresoCaja').attr('data-opcion','accionesCliente');
			$('#finalizarIngresoCaja').attr('data-destino','res_numero_transaccion');
			$('#opcion').val('numeroTransaccion');

			abrir($("#finalizarIngresoCaja"),event,false);
				
		}else{
			$('#res_numero_transaccion').html('');
		}

	}
	
});

$('#fecha_deposito').change(function(event){

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){

		if($('#fpago').val() == 'Deposito'){
			
			$('#finalizarIngresoCaja').attr('data-opcion','accionesCliente');
			$('#finalizarIngresoCaja').attr('data-destino','res_numero_transaccion');
			$('#opcion').val('numeroTransaccion');

			abrir($("#finalizarIngresoCaja"),event,false);
				
		}else{
			$('#res_numero_transaccion').html('');
		}
	}
	
});

$("#papeletaBanco").keydown(function(e) {
	if (e.keyCode == 32) { return false; } 
});

</script>

<style type="text/css">
	#tablaIngresoCaja td, #tablaIngresoCaja th,#tablaDetalle td, #tablaDetalle th
	{
		font-size:1em;
		border:1px solid rgba(0,0,0,.1);
		padding:3px 7px 2px 7px;
	}
</style>
</html>
