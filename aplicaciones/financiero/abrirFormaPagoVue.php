<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cca = new ControladorCatalogos();
	
	$cabeceraOrdenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $_POST['id']));
	$detalleOrdenPago = $cc->abrirDetallePago($conexion, $_POST['id']);
	$detalleFormaOrdenPago = $cc->abrirLiquidarOrdenPago($conexion, $_POST['id']);	
	$banco = $cca->listarEntidadesBancariasAgrocalidad($conexion);
	$identificador = $_SESSION['usuario'];
	
	if($identificador==''){
		$sessionUsuario='inactivo';
	}else{
		$sessionUsuario='activo';
	}
			
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
			<h1>Datos orden pago</h1>
	</header>
	<div id="estado"></div>

<fieldset id="ordenPago">
	<legend>Orden de pago <?php echo ' N°: '.$cabeceraOrdenPago['numero_solicitud'];?> </legend>
	<?php
		echo'<div data-linea="1">
			<label>Localización: </label> '.$cabeceraOrdenPago['localizacion'].'
		</div>
		<div data-linea="1">
			<label>Identificación: </label> '.$cabeceraOrdenPago['identificador_operador'].'
		</div>
		<div data-linea="2">
			<label>Razón social: </label> '.$cabeceraOrdenPago['razon_social'].'
		</div>
		<div data-linea="3">
			<label>Dirección: </label> '.$cabeceraOrdenPago['direccion'].'
		</div>
		<div data-linea="4">
			<label>Fecha de orden: </label> '.$cabeceraOrdenPago['fecha_orden_pago'].'
		</div>
		<div data-linea="5">
			<label>Observación: </label> '.$cabeceraOrdenPago['observacion'].'
		</div>
			<div data-linea="6">
			<label>Total a pagar $  </label> '.$cabeceraOrdenPago['total_pagar'].'
			<input type="hidden" id="totalPagarOrden" name="totalPagarOrden" value="'.$cabeceraOrdenPago['total_pagar'].'" >
		</div>
		<div data-linea="7">
			<label>Orden de pago: </label><a download="'.$cabeceraOrdenPago['numero_solicitud'].'.pdf" href="'.$cabeceraOrdenPago['orden_pago'].'" data-id="'.$cabeceraOrdenPago['id_pago'].'" target= "_blank">Descargar orden de pago</a>
		</div>';
		
		if($cabeceraOrdenPago['comprobante_factura'] != ''){
			echo '<div data-linea="8"><label>Comprobante de factura: </label><a href="'.$cabeceraOrdenPago['comprobante_factura'].'" target= "_blank">Descargar comprobante factura</a></div>';
		}
	
	?>
</fieldset>

<fieldset>
	<legend>Detalle</legend>
	   <table id="tablaOrdenPago">
		<thead>
			<tr>
			<th>Concepto</th>
			<th>Cantidad</th>
			<th>V Unit.</th>
			<th>Desc.</th>
			<th>Subsidio</th>
			<th>Iva</th>
			<th>Total</th>								
			</tr>
		</thead> 
		<?php
			while($fila = pg_fetch_assoc($detalleOrdenPago)){ 
				echo'<tr>
					<td>'.$fila['concepto_orden'].' <b>UNIDAD MEDIDA:</b> '.$fila['unidad_medida'].'</td>	
					<td>'.$fila['cantidad'].'</td>	
					<td>'.$fila['precio_unitario']*'1'.'</td>
					<td>'.$fila['descuento'].'</td>
					<td>'.$fila['subsidio']*'1'.'</td>
					<td>'.$fila['iva'].'</td>
					<td>'.$fila['total'].'</td>
					</tr>';
			}
		 ?> 
		</table>	
</fieldset>

<fieldset id="datosDetalleDeposito">
		<legend>Datos deposito</legend>
			 <table id="tablaOrdenPago">
				<thead>
					<tr>   		
					<th>Forma de pago</th>
					<th>Entidad recaudadora</th>
					<th>Número de transacción</th>
					<th>Monto</th>
					</tr>
				</thead> 
				<?php
					while($fila = pg_fetch_assoc($detalleFormaOrdenPago)){ 
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

<form id="finalizarFormaPagoVue" data-rutaAplicacion="financiero" data-opcion="guardarFormaPagoVue" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="opcion" name="opcion" value="0">

	<fieldset>
		<legend>Generar factura</legend>
			<input type="hidden" name="id_pago" value="<?php echo $cabeceraOrdenPago['id_pago'];?>"/>
			<input type="hidden" name="identificador" value="<?php echo $identificador;?>"/>
						
			<div data-linea="1">
					<label for="fpago">Forma de pago</label>
						<select id="fpago" name="fpago">
								<option value="" selected="selected">Seleccione una opción...</option>
								<option value="Deposito">Depósito</option>
						</select>
			</div>
			<div data-linea="2">			
			<label id = "lbanco">Institución bancaria</label>
				<select name="banco" id="banco"	>
					<option value="">Seleccione un banco...</option>
						<?php 
							while($fila = pg_fetch_assoc($banco)){
								echo '<option value="' . $fila['id_banco'] . '">' . $fila['nombre'] . '</option>';
							}
						?>
				</select>
				<input type="hidden" name="nombreBanco" id="nombreBanco"/>
			</div>
			
			
			<div data-linea="3">
				<label id = "lcuentaBancaria">Cuenta bancaria</label>
				<select id="cuentaBancaria" name="cuentaBancaria">
				</select>
				<input type="hidden" name="numeroCuenta" id="numeroCuenta"/>
			</div>
					
			<div data-linea="5">				
			<label>Número de papeleta</label>
				<input type="text" name="papeletaBanco" id="papeletaBanco"/> 
			</div>
									
			<div data-linea="4">
			<label id = "lfecha_deposito">Fecha depósito</label>
				<input type="text" id="fecha_deposito" name="fecha_deposito" /> 
			</div>		
			
			<div data-linea="4">
				<label>Valor depositado</label>
					<input type="text" name="valor_depositado" id="valor_depositado" placeholder="Ej: 10.56" title="Ejemplo: 999.99"/>
			</div>			
			
			<div class="info"></div>
						
			<p id="resNumeroTransaccion"></p>
			
			<button id="botonAgregar" type="submit">Guardar forma pago</button>
								  
	</fieldset>
	
</form>

</body>

<script type="text/javascript">

	var array_cuenta= <?php echo json_encode($detalleCuentaBancaria); ?>;
	var session_usuario = <?php echo json_encode($identificador);?>;

	var total_valor_depositado = Number(<?php echo json_encode($cabeceraOrdenPago['total_pagar']);?>);
			
	$(document).ready(function(){
		distribuirLineas();
		$("#valor_depositado").numeric(".");

		if(session_usuario == 'inactivo'){
			$("#estado").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#btnClaveCertificado").attr("disabled", "disabled");
		}

		$("#lcuentaBancaria").hide();
		$("#cuentaBancaria").hide();
		
	});
	
	$("#fecha_deposito").datepicker({
	    changeMonth: true,
	    changeYear: true
  	});
	

	$('#banco').change(function(event){

			$("#lcuentaBancaria").show();
			$("#cuentaBancaria").show();

			$("#nombreBanco").val($("#banco option:selected").text());

			scuenta = '<option value="">Cuentas....</option>';

			for(var i=0;i<array_cuenta.length;i++){

				if(array_cuenta[i]['idBanco'] == $('#banco').val()){
					scuenta += '<option value="'+array_cuenta[i]['idCuenta']+'" data-numero= "'+array_cuenta[i]['numeroCuenta']+'">Cuenta '+array_cuenta[i]['tipoCuenta']+' -> '+array_cuenta[i]['numeroCuenta']+'</option>';
				}   
		    }	
			
			$('#cuentaBancaria').html(scuenta);
			

		if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){
			
			$('#finalizarFormaPagoVue').attr('data-opcion','accionesCliente');
			$('#finalizarFormaPagoVue').attr('data-destino','resNumeroTransaccion');
			$('#opcion').val('numeroTransaccion');
			abrir($("#finalizarFormaPagoVue"),event,false);

		}
	});

	$("#finalizarFormaPagoVue").submit(function(event){
		event.preventDefault();
		
	 	$('#finalizarFormaPagoVue').attr('data-opcion','finalizarOrdenPago');
	 	$('#finalizarFormaPagoVue').attr('data-destino','detalleItem');
	
		$("#estado").html("").removeClass('alerta');

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#fpago').val() == "" ){
			error = true;
			$("#fpago").addClass("alertaCombo");
		}

		if($('#banco').val() == "" ){
			error = true;
			$("#banco").addClass("alertaCombo");
		}

		if($('#papeletaBanco').val() == "" ){
			error = true;
			$("#papeletaBanco").addClass("alertaCombo");
		}

		if($('#fecha_deposito').val() == "" ){
			error = true;
			$("#fecha_deposito").addClass("alertaCombo");
		}

		if($('#cuentaBancaria').val() == "" ){
			error = true;
			$("#cuentaBancaria").addClass("alertaCombo");
		}

	if(total_valor_depositado == $('#valor_depositado').val()){
		
		 if(!error){
			 $('#finalizarFormaPagoVue').attr('data-opcion','guardarFormaPagoVue');
			 $('#finalizarFormaPagoVue').attr('data-destino','detalleItem');
			 
			 ejecutarJson($(this)); 
			 	
		}else{
			 $("#estado").html("Error en los campos ingresados.").addClass("alerta");
		}
	}else{
			$("#valor_depositado").addClass("alertaCombo");
			$("#estado").html("El monto ingresado no corresponde, favor verifique los campos.!").addClass("alerta");
		 }   
		 
});

$('#valor_depositado').change(function(event){

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){
		$('#finalizarFormaPagoVue').attr('data-opcion','accionesCliente');
		$('#finalizarFormaPagoVue').attr('data-destino','resNumeroTransaccion');
		$('#opcion').val('numeroTransaccion');

		abrir($("#finalizarFormaPagoVue"),event,false);	
	}
});


$('#papeletaBanco').change(function(event){
	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){			
		$('#finalizarFormaPagoVue').attr('data-opcion','accionesCliente');
		$('#finalizarFormaPagoVue').attr('data-destino','resNumeroTransaccion');
		$('#opcion').val('numeroTransaccion');
		abrir($("#finalizarFormaPagoVue"),event,false);
	}	
});

$('#fecha_deposito').change(function(event){
	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){			
			$('#finalizarFormaPagoVue').attr('data-opcion','accionesCliente');
			$('#finalizarFormaPagoVue').attr('data-destino','resNumeroTransaccion');
			$('#opcion').val('numeroTransaccion');
			abrir($("#finalizarFormaPagoVue"),event,false);
	}
	
});	

$('#cuentaBancaria').change(function(event){
	$("#numeroCuenta").val($("#cuentaBancaria option:selected").attr('data-numero'));
});	


</script>
<style type="text/css">
#tablaOrdenPago td, #tablaOrdenPago th,#tablaDetalle td, #tablaDetalle th
{
	font-size:1em;
	border:1px solid rgba(0,0,0,.1);
	padding:3px 7px 2px 7px;
}
</style>
</html>
