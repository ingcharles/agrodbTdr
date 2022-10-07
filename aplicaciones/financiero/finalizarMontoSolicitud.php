<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorFinanciero.php';

	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$ca = new ControladorAreas();
	$cca = new ControladorCatalogos();
	$cf = new ControladorFinanciero();
	
	//print_r($_POST);
	
	$datos = explode('-', $_POST['id']);
	
	$idSolicitud = str_replace(' ', '', $datos[0]);
	$idOperador = $datos[1];
	$estadoActual =  $datos[2];
	$tipoSolicitud = $datos[3];
	$idOrdenPago = $datos[4];
	
	$idGrupo = $_POST['nombreOpcion'];
	$idVUE = $_POST['opcion'];
	
	
	
	
	$cabeceraOrdenPago = pg_fetch_assoc($cc->abrirOrdenPago($conexion, $idOrdenPago));
	$detalleLiquidarOrdenPago = $cc->abrirLiquidarOrdenPago($conexion, $idOrdenPago);
	$detalleOrdenPago = $cc->abrirDetallePago($conexion, $idOrdenPago);
	$banco = $cca->listarEntidadesBancariasAgrocalidad($conexion);
	$identificador = $_SESSION['usuario'];
	
	$saldoDisponible = pg_fetch_assoc($cf->obtenerMaxSaldo($conexion,$cabeceraOrdenPago['identificador_operador']));
	
	switch ($tipoSolicitud){
		case 'Operadores':
			$estado = 'inspeccion';
		break;
		case 'Importación':
			$estado = 'aprobado';
		break;
		case 'Fitosanitario':
			$estado = 'aprobado';
		break;
		case 'FitosanitarioExportacion':
			$estado = 'aprobado';
		break;
		case 'mercanciasSinValorComercialImportacion':
		case 'mercanciasSinValorComercialExportacion':
			$estado = 'aprobado';
		break;
		case 'certificacionBPA':
			$estado = 'inspeccion';
		break;
		case 'certificadoFito':
		    $estado = 'Aprobado';
		break;
		case 'dossierPecuario':
		    $estado = 'Recibido';
		break;
	}
	
	$distritos = $cc -> listarDistritos($conexion);
	
	$institucion = pg_fetch_assoc($cc->listarDatosInstitucion($conexion,$identificador));
	$numeroEstablecimientos = $cc -> listarEstablecimientos($conexion);
	
	while($fila = pg_fetch_assoc($numeroEstablecimientos)){
		$establecimiento[]= array('numEstablecimiento'=>$fila['numero_establecimiento'], 'ruc'=>$fila['ruc']);
	}
	
	$cuentasBancarias = $cca->listarCuentasBancarias($conexion);
	while($fila = pg_fetch_assoc($cuentasBancarias)){
		$detalleCuentaBancaria[]= array('idCuenta'=>$fila['id_cuenta_bancaria'], 'idBanco'=>$fila['id_banco'], 'numeroCuenta'=>$fila['numero_cuenta'], 'tipoCuenta'=>$fila['tipo_cuenta']);
	}
	
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<header>
			<h1>Liquidar Orden</h1>
	</header>
	<div id="estado"></div>

<fieldset id="ordenPago">
	<legend>Orden de pago <?php echo ' N°: '.$cabeceraOrdenPago['numero_solicitud'];?> </legend>
	<?php
		echo'<div data-linea="1">
			<label>Localización: </label> '.$cabeceraOrdenPago['localizacion'].'
		</div>
		<div data-linea="2">
			<label>Razón social: </label> '.$cabeceraOrdenPago['razon_social'].'
		</div>
		<div data-linea="2">
			<label>Identificación: </label> '.$cabeceraOrdenPago['identificador_operador'].'
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
			<label>Impresión: </label><a download="'.$cabeceraOrdenPago['numero_solicitud'].'.pdf" href="'.$cabeceraOrdenPago['orden_pago'].'" data-id="'.$cabeceraOrdenPago['id_pago'].'" target= "_blank">Orden de pago</a>
		</div>';
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
					while($fila = pg_fetch_assoc($detalleLiquidarOrdenPago)){ 
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

<!-- form id="solicitarAutorizacion" data-rutaAplicacion="financiero" data-opcion="solicitarAutorizacion"  data-accionEnExito="detalleItem">

	<input type="hidden" name="idPago" value="< ?php echo $cabeceraOrdenPago['id_pago'];?>"/>
	<input type="hidden" name="claveAcceso" value="< ?php echo $cabeceraOrdenPago['clave_acceso'];?>"/>
	<input type="hidden" name="correoCliente" value="< ?php echo $cabeceraOrdenPago['correo'];?>"/>
	
	<fieldset>
		<legend>Solicitar autorización</legend>
		
		<p class="nota">Es necesario realizar la autorización por parte del SRI para finalizar el proceso de facturación electronica.</p>
		
		<button type="submit" class="guardar" >Pedir autorización</button>
			
	</fieldset>
	
</form-->

<form id="finalizarOrdenFactura" data-rutaAplicacion="financiero" data-opcion="finalizarOrdenPago" data-destino="detalleItem">

<!-- Agregar nuevos campos para esquema revisión solicitudes -->

	<input type="hidden" name="inspector" value="<?php echo $identificador;?>"/> <!-- INSPECTOR -->
	<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
	<input type="hidden" name="tipoSolicitud" value="<?php echo $tipoSolicitud;?>"/>
	<input type="hidden" name="tipoInspector" value="Financiero"/>
	<input type="hidden" name="idVue" value="<?php echo $idVUE;?>"/>
	<input type="hidden" name="idGrupo" value="<?php echo $idGrupo;?>"/>
	<input type="hidden" name="resultado" value="<?php echo $estado;?>"/>
	<input type="hidden" name="observacion" value="Proceso realiado en sistema de facturación electronica"/>
	
<!-- FIN -->

	<fieldset>
		<legend>Generar factura</legend>
			<input type="hidden" name="id_pago" value="<?php echo $cabeceraOrdenPago['id_pago'];?>"/>
			<input type="hidden" id="totalPagar" name="totalPagar" value="<?php echo $cabeceraOrdenPago['total_pagar'];?>"/>
			<input type="hidden" id="idOperador" name="idOperador" value="<?php echo $cabeceraOrdenPago['identificador_operador'];?>"/>
			<input type="hidden" id="numeroFactura" name="numeroFactura" value="<?php echo $cabeceraOrdenPago['numero_factura'];?>"/>
			<input name="identificador" value="<?php echo $identificador;?>" type="hidden"/>
			<input name="tipoDocumento" value="factura" type="hidden"/> 
			<input type="hidden" id="opcion" name="opcion" value="0">
			<input type="hidden" id="saldo" name="saldo" value = "0"/> 
			<input type="hidden" id="saldoDisponibleCLiente" name="saldoDisponibleCLiente" value = "<?php echo $saldoDisponible['saldo_disponible'];?>"/> 
			
			<div data-linea="1">
					<label for="fpago">Forma de pago</label>
						<select id="fpago" name="fpago">
								<option value="" selected="selected">Seleccione una opción...</option>
								<option value="Deposito">Depósito</option>
								<option value="Efectivo">Efectivo</option>
								<option value="SaldoSENAE">Saldo SENAE</option>
								<option value="NotaCredito">Nota de Crédito</option>
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
			
			<div data-linea="29">
				<label id = "lrucDistrito">Ruc</label> 
					<select id="rucDistrito" name="rucDistrito">
						<option value="" selected="selected">Seleccione un RUC</option>
							<?php 
								while($fila = pg_fetch_assoc($distritos)){
									echo '<option value="' . $fila['ruc'] . '">' . $fila['ruc'] . '</option>';
								}
							?>
					</select> 
			</div>	

			<div data-linea="29">
				<label id = "lestablecimiento">Establecimiento</label>
					<select id="numeroEstablecimiento" name="numeroEstablecimiento">
					</select>
			</div>
			
			<div data-linea="30">
				<label id = "lpuntoEmision">P. Emisión</label>
			 		<select id="puntoEmision" name="puntoEmision">
			 			<option value="001">001</option>
			 			<option value="002">002</option>
					</select>
			</div>
			
			<div data-linea="30">
				<label id = "lnotaCredito"># Nota de crédito</label> 
				<input type="text" id="txtNotaCredito" name="txtNotaCredito"/>
			</div>
			
			<div data-linea="21" id="res_notaCredito"></div>
			
			<div data-linea="4">
			<label id = "lfecha_deposito">Fecha depósito</label>
				<input type="text" id="fecha_deposito" name="fecha_deposito" /> 
			</div>		
			
			<div data-linea="4" class="cambioValor">
				<label id = "lvalor_depositado">Valor depositado</label>
					<input type="text" name="valor_depositado" id="valor_depositado" placeholder="Ej: 10.56" title="Ejemplo: 999.99"/>
			</div>			
			
			<div class="info"></div>
			
			<p id="res_numero_transaccion"></p>
						
			<button id="botonAgregar" name="botonAgregar" type="button" onclick="agregarItem()" class="mas">Agregar Item</button>
				
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
	<fieldset>
	
		<div data-linea="5">
			<label id = "lclaveCertificado">Clave del certificado</label>
			<input type="password" id="txtClaveCertificado" name="txtClaveCertificado" />									
		</div>
		
		<div id="div6">
			<button type="submit" id="btnClaveCertificado" name="btnClaveCertificado">Firmar</button>
		</div>	
	
	</fieldset>
	
</form>

<fieldset id="datosAutorizacion">
	<legend>Datos de autorización SRI</legend>
		<div data-linea="1"><label>Estado: </label><?php echo $cabeceraOrdenPago['estado_sri'];?></div>
		<div data-linea="2"><label>Número autorización: </label><?php echo ($cabeceraOrdenPago['numero_autorizacion']=='' ? 'Número no disponible': $cabeceraOrdenPago['numero_autorizacion']);?></div>
		<div data-linea="3"><label>Fecha: </label><?php echo ($cabeceraOrdenPago['fecha_autorizacion']==''?'Fecha no disponible':date('j/n/Y (G:i:s)',strtotime($cabeceraOrdenPago['fecha_autorizacion'])));?></div>
		<div data-linea="4"><label>Observación: </label><?php echo ($cabeceraOrdenPago['observacion_sri'] == 'null' ? 'Observación no disponible': $cabeceraOrdenPago['observacion_sri']);?></div>
		<?php 
			if($cabeceraOrdenPago['numero_autorizacion'] != ''){
				echo '<div data-linea="5"><label>Factura: </label><a href="'.$cabeceraOrdenPago['factura'].'" target= "_blank">Descargar RIDE(SRI)</a></div>';
			}
		?>
				
</fieldset>

</body>

<script type="text/javascript">

	var array_establecimiento= <?php echo json_encode($establecimiento); ?>;
	var estado = <?php  echo json_encode($cabeceraOrdenPago['estado']);?>;
	var estadoSRI = <?php  echo json_encode($cabeceraOrdenPago['estado_sri']);?>;
	var saldoDisponible = <?php  echo json_encode($saldoDisponible['saldo_disponible']);?>;
	var idSaldo = <?php  echo json_encode($saldoDisponible['id_saldo']);?>;
	var array_cuenta= <?php echo json_encode($detalleCuentaBancaria); ?>;
	
	var contador = 0; 
	var numSecuencial = 0;
	var totalValorDepositado = 0;
	var valorRestante = 0;
	
	$(document).ready(function(){
		distribuirLineas();
		//$("#solicitarAutorizacion").hide();
		$("#datosDetalleDeposito").hide();
		$("#finalizarOrdenFactura").hide();
		$("#datosAutorizacion").hide();
		$("#lbanco").hide();
		$("#lpapeletaBanco").hide();
		$("#lfecha_deposito").hide();
		$("#lvalor_depositado").hide();
		$("#banco").hide();
		$("#papeletaBanco").hide();
		$("#fecha_deposito").hide();
		$("#valor_depositado").hide();
		$("#lnotaCredito").hide();
		$("#txtNotaCredito").hide();
		$("#lrucDistrito").hide();
		$("#rucDistrito").hide();
		$("#lestablecimiento").hide();
		$("#numeroEstablecimiento").hide();
		$("#lpuntoEmision").hide();
		$("#puntoEmision").hide();

		$("#lcuentaBancaria").hide();
		$("#cuentaBancaria").hide();

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
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
  	});

	if (estado=="3"){
		$("#finalizarOrdenFactura").show();
	}

	if (estado=="4"){
		$("#datosDetalleDeposito").show();
	}

	if (estado=="4" && estadoSRI == 'AUTORIZADO'){
		$("#datosAutorizacion").show();
	}

	if (estado=="4" && estadoSRI == 'NO AUTORIZADO'){
		$("#datosAutorizacion").show();
	}

	if (estado=="4" && estadoSRI == 'RECIBIDA'){
		$("#datosAutorizacion").show();
	}

	if (estado=="4" && estadoSRI == 'DEVUELTA'){
		$("#datosAutorizacion").show();
	}

	
/*	$("#solicitarAutorizacion").submit(function(event){
		event.preventDefault();
		$('#solicitarAutorizacion').attr('data-opcion','solicitarAutorizacion');
	 	$('#solicitarAutorizacion').attr('data-destino','detalleItem');
		abrir($("#solicitarAutorizacion"),event,false); 
	});*/

	$('#fpago').change(function(){
		
		if ($("#fpago").val() =='Deposito'){
			$("#lfecha_deposito").hide();
			$("#fecha_deposito").hide();
			$("#lvalor_depositado").hide();
			$("#valor_depositado").hide();
			$("#lnotaCredito").hide();
			$("#txtNotaCredito").hide();
			$("#monto").hide();
			$("#lmonto").hide();
			$("#lbanco").show();
			$("#banco").show();
			$("#lrucDistrito").hide();
			$("#rucDistrito").hide();
			$("#lestablecimiento").hide();
			$("#numeroEstablecimiento").hide();
			$("#lpuntoEmision").hide();
			$("#puntoEmision").hide();

			$("#lcuentaBancaria").hide();
			$("#cuentaBancaria").hide();

			$(".cambioValor").attr('data-linea','4');
		} 

		if ($("#fpago").val() =='Efectivo' || $("#fpago").val() =='SaldoSENAE'){
				$("#banco").val(0);
				$("#lbanco").hide();
				$("#lpapeletaBanco").hide();
				$("#lvalor_depositado").show();
				$("#banco").hide();
				$("#papeletaBanco").hide();
				$("#lnotaCredito").hide();
				$("#txtNotaCredito").hide();
				$("#monto").hide();
				$("#lmonto").hide();
				$("#valor_depositado").show();
				$("#fecha_deposito").show();
				$("#lfecha_deposito").show();
				$("#lrucDistrito").hide();
				$("#rucDistrito").hide();
				$("#lestablecimiento").hide();
				$("#numeroEstablecimiento").hide();
				$("#lpuntoEmision").hide();
				$("#puntoEmision").hide();

				$("#lcuentaBancaria").hide();
				$("#cuentaBancaria").hide();

				$(".cambioValor").attr('data-linea','4');
			}
		
		if ($("#fpago").val() == 'NotaCredito'){
			$("#lbanco").hide();
			$("#banco").hide();
			$("#lpapeletaBanco").hide();
			$("#papeletaBanco").hide();
			$("#lfecha_deposito").hide();
			$("#fecha_deposito").hide();
			$("#lnotaCredito").show();
			$("#txtNotaCredito").show();
			$("#lvalor_depositado").show();
			$("#valor_depositado").show();
			$("#lrucDistrito").show();
			$("#rucDistrito").show();
			$("#lestablecimiento").show();
			$("#numeroEstablecimiento").show();
			$("#lpuntoEmision").show();
			$("#puntoEmision").show();

			$("#lcuentaBancaria").hide();
			$("#cuentaBancaria").hide();

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

			$(".cambioValor").attr('data-linea','50');			
		}

		if ($("#fpago").val() =='SaldoDisponible'){

			if (saldoDisponible > 0){
				$("#lbanco").hide();
				$("#banco").hide();
				$("#lpapeletaBanco").hide();
				$("#papeletaBanco").hide();
				$("#txtNotaCredito").hide();
				$("#lnotaCredito").hide();
				$("#lvalor_depositado").show();
				$("#valor_depositado").show();
				$("#lfecha_deposito").show();
				$("#fecha_deposito").show();
				$("#monto").hide();
				$("#lmonto").hide();
				$("#txtNotaCredito").hide();
				$("#lnotaCredito").hide();
				$("#lrucDistrito").hide();
				$("#rucDistrito").hide();
				$("#lestablecimiento").hide();
				$("#numeroEstablecimiento").hide();
				$("#lpuntoEmision").hide();
				$("#puntoEmision").hide();

				$("#lcuentaBancaria").hide();
				$("#cuentaBancaria").hide();
				
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
				$("#txtNotaCredito").hide();
				$("#lnotaCredito").hide();
				$("#monto").hide();
				$("#lmonto").hide();
				$("#txtNotaCredito").hide();
				$("#lnotaCredito").hide();
				$("#lrucDistrito").hide();
				$("#rucDistrito").hide();
				$("#lestablecimiento").hide();
				$("#numeroEstablecimiento").hide();
				$("#lpuntoEmision").hide();
				$("#puntoEmision").hide();

				$("#lcuentaBancaria").hide();
				$("#cuentaBancaria").hide();
				
				$("#estado").html("No dispone de saldo.").addClass("alerta");
			 }

			$(".cambioValor").attr('data-linea','4');
		}
		
		distribuirLineas();
	});
	

	$('#banco').change(function(){
		
		/*$("#lpapeletaBanco").show();
		$("#lfecha_deposito").show();
		$("#lvalor_depositado").show();
		$("#papeletaBanco").show();
		$("#fecha_deposito").show();
		$("#valor_depositado").show();
		$("#lnotaCredito").hide();
		$("#txtNotaCredito").hide();*/

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
				
				$('#finalizarOrdenFactura').attr('data-opcion','accionesCliente');
				$('#finalizarOrdenFactura').attr('data-destino','res_numero_transaccion');
				$('#opcion').val('numeroTransaccion');

				abrir($("#finalizarOrdenFactura"),event,false);
					
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
			$("#lnotaCredito").hide();
			$("#txtNotaCredito").hide();
		}
		
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

			if( ($('#banco option:selected').text() != 'Sin entidad financiera') && ($('#banco option:selected').text() != 'Recaudación por VUE')){
				
				if($.trim($("#cuentaBancaria").val())=="" ){
					error = true;
					$("#cuentaBancaria").addClass("alertaCombo");
				}

			}
		
			if($("#papeletaBanco").val()==""){
				error = true;
				$("#papeletaBanco").addClass("alertaCombo");
			}
		}

		if($("#fpago").val()== "Deposito" || $("#fpago").val()== "Efectivo" || $("#fpago").val() == "SaldoDisponible" || $("#fpago").val() == "SaldoSENAE"){
			if($("#fecha_deposito").val()==""){
				error = true;
				$("#fecha_deposito").addClass("alertaCombo");
			}
		
		}

		if($("#fpago").val()== "NotaCredito" ){
			if(Number($('#valor_depositado').val()) > Number($('#monto').val())){
				error = true;
				$("#valor_depositado").addClass("alertaCombo");
			}

			if($("#idNotaCredito").length == 0 ){
				error = true;
				$("#valor_depositado").addClass("alertaCombo");
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
							$("#detalles").append("<tr id='e_"+numSecuencial+"'><td><button type='button' onclick='quitarItem(\"#e_"+numSecuencial+"\")' class='menos'>Quitar</button></td><td>"+$("#fpago option:selected").val()+"</td><td>Cobro en oficina</td><td>"+numeroCuentaBanco+"</td><td>0</td><td>"+$("#valor_depositado").val()+"</td><input id='formaPago' name='formaPago[]' value='"+$("#fpago option:selected").val()+"' type='hidden'><input id='valorDepositado' name='valorDepositado[]' value='"+$("#valor_depositado").val()+"' type='hidden'><input id='idBanco' name='idBanco[]' value='0' type='hidden'><input id='fechaDeposito' name='fechaDeposito[]' value='"+$("#fecha_deposito").val()+"' type='hidden'><input id='aIdNotaCredito' name='aIdNotaCredito[]' value='0' type='hidden'><input id='nombreBanco' name='nombreBanco[]' value='0' type='hidden'><input id='aPapeletaBanco' name='aPapeletaBanco[]' value='0' type='hidden'><input id='idCuentaBanco' name='idCuentaBanco[]' value='"+identificadorCuenta+"' type='hidden'><input id='numeroCuentaBanco' name='numeroCuentaBanco[]' value='"+numeroCuentaBanco+"' type='hidden'></tr>");
						}

						if($("#fpago").val() == 'SaldoSENAE'){
							$("#detalles").append("<tr id='e_"+numSecuencial+"'><td><button type='button' onclick='quitarItem(\"#e_"+numSecuencial+"\")' class='menos'>Quitar</button></td><td>"+$("#fpago option:selected").val()+"</td><td>Saldo SENAE</td><td>"+numeroCuentaBanco+"</td><td>0</td><td>"+$("#valor_depositado").val()+"</td><input id='formaPago' name='formaPago[]' value='"+$("#fpago option:selected").val()+"' type='hidden'><input id='valorDepositado' name='valorDepositado[]' value='"+$("#valor_depositado").val()+"' type='hidden'><input id='idBanco' name='idBanco[]' value='0' type='hidden'><input id='fechaDeposito' name='fechaDeposito[]' value='"+$("#fecha_deposito").val()+"' type='hidden'><input id='aIdNotaCredito' name='aIdNotaCredito[]' value='0' type='hidden'><input id='nombreBanco' name='nombreBanco[]' value='0' type='hidden'><input id='aPapeletaBanco' name='aPapeletaBanco[]' value='0' type='hidden'><input id='idCuentaBanco' name='idCuentaBanco[]' value='"+identificadorCuenta+"' type='hidden'><input id='numeroCuentaBanco' name='numeroCuentaBanco[]' value='"+numeroCuentaBanco+"' type='hidden'></tr>");
						}

						if($("#fpago").val() == 'NotaCredito'){

							totalValorDepositado = sumarValor('valorDepositado');
							var valorRestante = Number($('#totalPagar').val()) - Number(totalValorDepositado);

							if(Number($("#valor_depositado").val()) <= Number(valorRestante)){

								if($("#detalles #n_"+$('#txtNotaCredito').val()).length==0){
									$("#detalles").append("<tr id='n_"+$('#txtNotaCredito').val()+"'><td><button type='button' onclick='quitarItem(\"#n_"+$('#txtNotaCredito').val()+"\")' class='menos'>Quitar</button></td><td>"+$("#fpago option:selected").val()+"</td><td>Cobro en oficina</td><td>"+numeroCuentaBanco+"</td><td>0</td><td>"+$("#valor_depositado").val()+"</td><input id='formaPago' name='formaPago[]' value='"+$("#fpago option:selected").val()+"' type='hidden'><input id='valorDepositado' name='valorDepositado[]' value='"+$("#valor_depositado").val()+"' type='hidden'><input id='idBanco' name='idBanco[]' value='0' type='hidden'><input id='nombreBanco' name='nombreBanco[]' value='"+$("#banco option:selected").text()+"' type='hidden'><input id='aPapeletaBanco' name='aPapeletaBanco[]' value='"+$("#papeletaBanco").val()+"' type='hidden'><input id='fechaDeposito' name='fechaDeposito[]' value='"+$("#fecha_deposito").val()+"' type='hidden'><input id='aIdNotaCredito' name='aIdNotaCredito[]' value='"+$('#idNotaCredito').val()+"' type='hidden'><input id='idCuentaBanco' name='idCuentaBanco[]' value='"+identificadorCuenta+"' type='hidden'><input id='numeroCuentaBanco' name='numeroCuentaBanco[]' value='"+numeroCuentaBanco+"' type='hidden'></tr>");
								}else{
									$("#estado").html("No puede ingresar una misma nota de crédito, favor verifique.!").addClass("alerta");
								}

							}else{
								$("#estado").html("El valor ingresado es mayor al valor de la orden de pago").addClass('alerta');	
							}								
						}

						if($("#fpago").val() == 'SaldoDisponible'){							
							totalValorDepositado = sumarValor('valorDepositado');
							var valorRestante = Number($('#totalPagar').val()) - Number(totalValorDepositado);								
							if(Number($("#valor_depositado").val()) <= Number(valorRestante)){
								if(Number($("#valor_depositado").val()) <= Number(saldoDisponible)){										
									if($("#detalles #s_"+idSaldo).length==0){
										$("#detalles").append("<tr id='s_"+idSaldo+"'><td><button type='button' onclick='quitarItem(\"#s_"+idSaldo+"\")' class='menos'>Quitar</button></td><td>"+$("#fpago option:selected").val()+"</td><td>Cobro en oficina</td><td>"+numeroCuentaBanco+"</td><td>0</td><td>"+$("#valor_depositado").val()+"</td><input id='formaPago' name='formaPago[]' value='"+$("#fpago option:selected").val()+"' type='hidden'><input id='valorDepositado' name='valorDepositado[]' value='"+$("#valor_depositado").val()+"' type='hidden'><input id='idBanco' name='idBanco[]' value='0' type='hidden'><input id='fechaDeposito' name='fechaDeposito[]' value='"+$("#fecha_deposito").val()+"' type='hidden'><input id='aIdNotaCredito' name='aIdNotaCredito[]' value='0' type='hidden'><input id='nombreBanco' name='nombreBanco[]' value='0' type='hidden'><input id='aPapeletaBanco' name='aPapeletaBanco[]' value='0' type='hidden'><input id='idCuentaBanco' name='idCuentaBanco[]' value='"+identificadorCuenta+"' type='hidden'><input id='numeroCuentaBanco' name='numeroCuentaBanco[]' value='"+numeroCuentaBanco+"' type='hidden'></tr>");	
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

		$('#papeletaBanco').val('');
		$('#fecha_deposito').val('');
		$('#valor_depositado').val('');
		
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

	$("#finalizarOrdenFactura").submit(function(event){
		event.preventDefault();

	 	$('#finalizarOrdenFactura').attr('data-opcion','finalizarOrdenPago');
	 	$('#finalizarOrdenFactura').attr('data-destino','detalleItem');
	
		$("#estado").html("").removeClass('alerta');

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#valorDepositado').length == 0 ){
			error = true;
		}

		if($("#txtClaveCertificado").val()==""){
			error = true;
			$("#txtClaveCertificado").addClass("alertaCombo");
		}

		totalValorDepositado = sumarValor('valorDepositado');

		if(Number(totalValorDepositado) > Number($('#totalPagar').val())){
						
			saldoDisponible = 	Math.round((Number(totalValorDepositado) - Number($('#totalPagar').val()))*100)/100	;
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

			 $('#finalizarOrdenFactura').attr('data-opcion','finalizarOrdenPago');
			 $('#finalizarOrdenFactura').attr('data-destino','detalleItem');
			 $('#finalizarOrdenFactura').attr('data-rutaAplicacion','financiero');

			 ejecutarJson($(this));

			 var resultado = $("#estado").html().split('-');
			 
			 //alert(resultado[0]);

			 if( resultado[0] == 'Documento XML firmado correctamente.'){

				 $('#finalizarOrdenFactura').attr('data-opcion','verificarPagoSolicitud');
				 $('#finalizarOrdenFactura').attr('data-destino','detalleItem');
				 $('#finalizarOrdenFactura').attr('data-rutaAplicacion','revisionFormularios');
				 
				  ejecutarJson($(this));

			if($("#estado").html()=='La operación se ha guardado satisfactoriamente.'){
					   
				$('#finalizarOrdenFactura').attr('data-opcion','mostrarDocumentoPDF');
				$('#finalizarOrdenFactura').attr('data-destino','detalleItem');
				$('#finalizarOrdenFactura').attr('data-rutaAplicacion','financiero');

				abrir($("#finalizarOrdenFactura"),event,false);
					   
			}							 
		}else{
			$("#numeroFactura").val(resultado[1]);
		}	
				
		
		 		 	
	}else{
		 $("#estado").html("Error en los campos ingresados.").addClass("alerta");
	  }
	}else{
			$("#fpago").addClass("alertaCombo");
			$("#estado").html("El monto ingresado no corresponde, favor verifique los campos.!").addClass("alerta");
		 }   
		 
});

$("#txtNotaCredito").change(function(event){
	 if($("#txtNotaCredito").val() != ''){
		 $('#finalizarOrdenFactura').attr('data-opcion','accionesCliente');
		 $('#finalizarOrdenFactura').attr('data-destino','res_notaCredito');
		 $('#opcion').val('notaCredito');	
		abrir($("#finalizarOrdenFactura"),event,false); //Se ejecuta ajax, busqueda de sitio
		
	}else{
		$("#txtNotaCredito").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese un número de nota de credito.").addClass("alerta");
		}

});

$('#valor_depositado').change(function(event){

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){

    	if($('#fpago').val() == 'Deposito'){
    		
    		$('#finalizarOrdenFactura').attr('data-opcion','accionesCliente');
    		$('#finalizarOrdenFactura').attr('data-destino','res_numero_transaccion');
    		$('#opcion').val('numeroTransaccion');
    
    		abrir($("#finalizarOrdenFactura"),event,false);
    			
    	}else{
    		$('#res_numero_transaccion').html('');
    	}
    	
	}
	
});


$('#papeletaBanco').change(function(event){

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){

		if($('#fpago').val() == 'Deposito'){
			
			$('#finalizarOrdenFactura').attr('data-opcion','accionesCliente');
			$('#finalizarOrdenFactura').attr('data-destino','res_numero_transaccion');
			$('#opcion').val('numeroTransaccion');

			abrir($("#finalizarOrdenFactura"),event,false);
				
		}else{
			$('#res_numero_transaccion').html('');
		}

	}
	
});

$('#fecha_deposito').change(function(event){

	if($('#banco').val() != '' && $('#papeletaBanco').val() != '' && $('#fecha_deposito').val() != '' && $('#valor_depositado').val() != ''){

		if($('#fpago').val() == 'Deposito'){
			
			$('#finalizarOrdenFactura').attr('data-opcion','accionesCliente');
			$('#finalizarOrdenFactura').attr('data-destino','res_numero_transaccion');
			$('#opcion').val('numeroTransaccion');

			abrir($("#finalizarOrdenFactura"),event,false);
				
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
#tablaOrdenPago td, #tablaOrdenPago th,#tablaDetalle td, #tablaDetalle th
{
	font-size:1em;
	border:1px solid rgba(0,0,0,.1);
	padding:3px 7px 2px 7px;
}
</style>
</html>
