<?php
// Realiza consulta clientes

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cf = new ControladorFinanciero();
$cc = new ControladorCertificados();
$contador = 0;
$resultado = "";

$tipoCliente = htmlspecialchars ($_POST['tipoBusquedaCliente'],ENT_NOQUOTES,'UTF-8');
$varCliente = htmlspecialchars ($_POST['txtClienteBusqueda'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

$tipoDocumento = htmlspecialchars ($_POST['tipoBusquedaDocumento'],ENT_NOQUOTES,'UTF-8');
$varDocumento = htmlspecialchars ($_POST['txtDocumentoBusqueda'],ENT_NOQUOTES,'UTF-8');
$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
$idNotaCredito = htmlspecialchars ($_POST['txtNotaCredito'],ENT_NOQUOTES,'UTF-8');

$numeroEstablecimiento = htmlspecialchars ($_POST['numeroEstablecimiento'],ENT_NOQUOTES,'UTF-8');
$puntoEmision = htmlspecialchars ($_POST['puntoEmision'],ENT_NOQUOTES,'UTF-8');
$rucDistrito = htmlspecialchars ($_POST['rucDistrito'],ENT_NOQUOTES,'UTF-8');

$usuarioPermitidoFinalizar = htmlspecialchars($_POST['usuarioPermitidoFinalizar'],ENT_NOQUOTES,'UTF-8');

	switch ($opcion){
		case 'cliente':
			
			if ($tipoCliente != '' && $varCliente != '' ){
				
				$operador = $cf->listaOperadores($conexion,$tipoCliente, $varCliente);
				$operadores = pg_fetch_assoc($operador);
				
				$cliente = $cf->listaClientes($conexion,$tipoCliente, $varCliente);
				$clientes = pg_fetch_assoc($cliente);
				
				if(pg_num_rows($operador) == 0) {
				
					if(pg_num_rows($cliente) != 0){
						
						if($tipoCliente == '01'){
							echo '<div data-linea="1">
									<label>Ruc:</label>
										<input type="text" id="ruc" name="ruc" value="'.$clientes['identificador'].'" disabled="disabled" readonly="readonly" data-er="[0-9]{1,15}" />
								</div>';
						}
						echo'<div data-linea="2">
								<label>Razón Social: </label> 
								<input type="hidden" id="idCliente" name="idCliente" value="'.$clientes['identificador'].'" >
								<input type="text" id="razonSocial" name="razonSocial" value="'.$clientes['razon_social'].'" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" style="width: 82%;margin: 2px;" />
							</div>
							<div data-linea="3">
								<label>Dirección: </label> 
								<input type="text" id="direccion" name="direccion" value="'.$clientes['direccion'].'" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" style="width: 85%;margin: 2px;" />
							</div>
							<div data-linea="4">
								<label>Teléfono: </label> 
								<input type="text" id="telefono" name="telefono" value="'.$clientes['telefono'].'" disabled="disabled" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" style="width: 86%;margin: 2px;" />
							</div>
							<div data-linea="5">
								<label>Correo: </label> 
								<input type="text" id="correo" name="correo" value="'.$clientes['correo'].'" disabled="disabled" title="99" style="width: 88%;margin: 2px;" />
							</div>';
							
				 			if($tipoCliente != '07'){
				 				echo '<div><p>
				 				<button id="modificarCliente" type="button" class="editar">Editar</button>
				 				</p></div>';
				 			}
					
						}else{
							if($tipoCliente == '01'){
								echo '<div data-linea="6">
								<label>Ruc:</label>
								<input type="text" id="ruc" name="ruc" required="required" data-er="[0-9]{1,15}" style="width: 82%;margin: 2px;"/>
							</div>';
							}
							
						echo'<div data-linea="7">
								<label>Razón Social:</label>
								<input type="text" id="razonSocial" name="razonSocial" required="required" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" style="width: 82%;margin: 2px;"/>
							</div>
							<div data-linea="8">
								<label>Dirección:</label>
								<input type="text" id="direccion" name="direccion" required="required" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" style="width: 85%;margin: 2px;"/>
							</div>
							<div data-linea="9">
								<label>Teléfono:</label>
								<input type="text" id="telefono" name="telefono" required="required" data-er="[0-9]{1,15}" title="99" style="width: 86%;margin: 2px;"/>
							</div>
							<div data-linea="10">
								<label>Correo: </label>
								<input type="text" id="correo" name="correo" required="required" title="99" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$"  style="width: 88%;margin: 2px;"/>
							</div>';
						}
				}else{
					if($tipoCliente == '01'){
						echo '<div data-linea="1">
						<label>Ruc:</label>
						<input type="text" id="ruc" name="ruc" value="'.$operadores['identificador'].'" disabled="disabled" readonly="readonly" data-er="[0-9]{1,15}" />
						</div>';
					}
					
					echo'<div data-linea="2">
						<label>Razón Social: </label>
						<input type="hidden" id="idCliente" name="idCliente" value="'.$operadores['identificador'].'" >
						<input type="text" id="razonSocial" name="razonSocial" value="'.$operadores['razon_social'].'" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" style="width: 82%;margin: 2px;" />
						</div>
						<div data-linea="3">
						<label>Dirección: </label>
						<input type="text" id="direccion" name="direccion" value="'.$operadores['direccion'].'" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" style="width: 85%;margin: 2px;" />
						</div>
						<div data-linea="4">
						<label>Teléfono: </label>
						<input type="text" id="telefono" name="telefono" value="'.$operadores['telefono_uno'].'" disabled="disabled" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" style="width: 86%;margin: 2px;" />
						</div>
						<div data-linea="5">
						<label>Correo: </label>
						<input type="text" id="correo" name="correo" value="'.$clientes['correo'].'" disabled="disabled" title="99" style="width: 88%;margin: 2px;" />
						</div>';
				}
				
			}
						
		break;
		
		case 'tarifario':
			
			$idServicio = pg_fetch_assoc($cc->obtenerIdServicioXarea($conexion, $area, 'activo'));
			$tarifario = $cc->obtenerServicioXarea($conexion, $idServicio['id_servicio'], 'TODO');
				
			while($fila = pg_fetch_assoc($tarifario)){
				$arrayTarifario[]= array('idServicio'=>$fila['id_servicio'], 'concepto' =>$fila['concepto'], 'codigo'=>$fila['codigo'], 'idCategoria'=>$fila['id_categoria_servicio'], 'valor'=>$fila['valor']*1, 'iva'=>$fila['iva'], 'medida'=>$fila['unidad_medida'], 'subsidio'=>$fila['subsidio']);
			}
				
			echo'<label>Tipo de transacción</label>
					<select id="transaccion" name="transaccion">
					</select>';
		break;
			
		case 'tarifarioNotaCredito':
			
			if ($tipoDocumento != '' && $varDocumento != '' && $numeroEstablecimiento != '' && $puntoEmision != '' && $rucDistrito != ''){
			
				$numeroDocumento = str_pad($varDocumento, 9, "0", STR_PAD_LEFT);
				$datosFactura = $cf->listaDocumento($conexion, $tipoDocumento, $numeroDocumento, $numeroEstablecimiento, $puntoEmision, $rucDistrito);
				$idServicio = pg_fetch_assoc($cc->obtenerIdServicioXarea($conexion, $area, 'activo'));
				$tarifario = $cc->obtenerServicioXarea($conexion, $idServicio['id_servicio'], 'TODO');
			
				while($fila = pg_fetch_assoc($tarifario)){
					$arrayTarifario[]= array('idServicio'=>$fila['id_servicio'], 'concepto' =>$fila['concepto'], 'codigo'=>$fila['codigo'], 'idCategoria'=>$fila['id_categoria_servicio'], 'valor'=>$fila['valor']*1, 'iva'=>$fila['iva'], 'medida'=>$fila['unidad_medida']);
				}
				
				echo'<label>Tipo de transacción</label>
						<select id="transaccion" name="transaccion">
					</select>';
			}else{
				echo'<fieldset><legend>Datos de factura ha modificar</legend><label>No existe documento o se encuentra en estado consumida.</label></fieldset>';
			}
		break;
			
		case 'documento':
				
			if ($tipoDocumento != '' && $varDocumento != '' && $numeroEstablecimiento != '' && $puntoEmision != '' && $rucDistrito != ''){
				// Busqueda de documento
				$numeroDocumento = str_pad($varDocumento, 9, "0", STR_PAD_LEFT);
				$datosFactura = $cf->listaDocumento($conexion, $tipoDocumento, $numeroDocumento, $numeroEstablecimiento, $puntoEmision, $rucDistrito);
				
				if(count($datosFactura) != 0){
					
					$iva =  $datosFactura[0]['porcentajeIva'];
				
				echo'<fieldset><legend>Datos de factura ha modificar</legend>
					<div data-linea="1">
						<label>Localización: </label> '.$datosFactura[0]['localizacion'].'
						<input type="hidden" id="idPago" name="idPago" value="'.$datosFactura[0]['idPago'].'" >
					</div>
					<div data-linea="1">
						<label>Identificación: </label> '.$datosFactura[0]['identificadorOperador'].'
					</div>
					<div data-linea="2">
						<label>Razón Social: </label> '.$datosFactura[0]['razonSocial'].'
					</div>
					<div data-linea="3">
						<label>Fecha facturación: </label> '.$datosFactura[0]['fechaFacturacion'].'
					</div>
					<div data-linea="3">
						<label>Total pagar: </label> '.$datosFactura[0]['totalPagar'].'
						<input type="hidden" id="totalFactura" name="totalFactura" value="'.$datosFactura[0]['totalPagar'].'"/>	
					</div>
					<div data-linea="4">
						<label>Observación: </label> '.$datosFactura[0]['observacion'].'
					</div><hr/>';
			
				
					echo '<table id="tablaDetalleOrdenPago">
							<thead>
								<tr>
								<th>Concepto</th>
								<th>Cantidad</th>
								<th>V. Unitario</th>
								<th>Descuento</th>
								<th>IVA</th>
								<th>Total</th>
								</tr>
							</thead>
							<tbody id="detallesOrden">';
					//while($fila = pg_fetch_assoc($detalleFactura)){
					foreach ($datosFactura as $detalle){

						echo "<tr>
								<td>".$detalle['conceptoOrden']." </td>
								<td>".$detalle['cantidad']."</td>
								<td>".$detalle['precioUnitario']*'1'."</td>
								<td>".$detalle['descuento']."</td>
								<td>".$detalle['iva']."</td>
								<td>".$detalle['total']."</td>
								</tr></fieldset>";
						
						$servicio .= "'".$detalle['idServicio']."',";												
					}
					
					$servicio = "(".rtrim($servicio,',').")";					
					
					$datosArea = $cf->obtenerAreaServicioNotaCredito($conexion, $servicio);
					
					$opcionArea = "<option selected='selected'>Área....</option>";
					
					while ($fila = pg_fetch_assoc($datosArea)){
					
						switch ($fila['id_area']){
							case 'SA':
								$opcionArea .= "<option value = 'SA'>Sanidad Animal</option>";
							break;
							case 'SV':
								$opcionArea .= "<option value='SV'>Sanidad Vegetal</option>";
							break;
							case 'IA':
								$opcionArea .= "<option value='IA'>Inocuidad de los Alimentos</option>";
							break;
							case 'LT':
								$opcionArea .= "<option value='LT'>Análisis de laboratorios</option>";
							break;
							case 'CGRIA':
								$opcionArea .= "<option value='CGRIA'>Control de Insumos Agropecuarios</option>";
							break;
							case 'AGR':
								$opcionArea .= "<option value='AGR'>Otros ingresos</option>";
							break;
							case 'GENER':
								$opcionArea .= "<option value='GENER'>General</option>";
							break;
						}
					}		

					echo '<script type="text/javascript">var iva="'.$iva.'"; $("#area").html("'.$opcionArea.'");</script>';
					
					//echo '<script type="text/javascript"></script>';				
					
				}else{
					echo'<fieldset><legend>Datos de factura ha modificar</legend><label>No existe documento o se encuentra en estado consumida.</label></fieldset>';
				}
					
			}else{
				echo'<fieldset><legend>Datos de factura ha modificar</legend><label>No existe documento o se encuentra en estado consumida.</label></fieldset>';
			}
					
			break;	
			
			case 'notaCredito':
				
				if ($idNotaCredito != '' && $numeroEstablecimiento != '' && $puntoEmision != '' && $rucDistrito != ''){
					
					$numeroNotaCredito = str_pad($idNotaCredito, 9, "0", STR_PAD_LEFT);
					$notaCredito = $cf->listaNotaCredito($conexion, $numeroNotaCredito, $numeroEstablecimiento, $puntoEmision, $rucDistrito);
					$datosNotaCredito = pg_fetch_assoc($notaCredito);
						
					if(pg_num_rows($notaCredito) != 0){
						$notaCreditoFormaPago = $cf ->obtenerPagoNotaCredito($conexion,$datosNotaCredito['id_nota_credito'] );
						echo '<input type="hidden" id="idNotaCredito" name="idNotaCredito" value="'.$datosNotaCredito['id_nota_credito'].'" >';
						if(pg_num_rows($notaCreditoFormaPago) != 0){
								
							while($fila = pg_fetch_assoc($notaCreditoFormaPago)){
								$valorUtilizado +=  $fila['valor_deposito'];
							}
								
							$saldoActual = $datosNotaCredito['total_pagar'] -  $valorUtilizado;
								
							echo '<div data-linea="1">
								<label id= lmonto>Monto</label>
								<input type="text" id="monto" name="monto" value="'.$saldoActual.'" readonly = "readonly" />
								</div>';
						}else{
								
							echo '<div data-linea="1">
							<label id=lmonto>Monto:</label>
							<input type="text" id="monto" name="monto" value="'.$datosNotaCredito['total_pagar'].'" readonly = "readonly" />
							</div>';
						}
					}else{
						echo'No existe nota de crédito';
					}
					
				}else{
					echo'<label>No existe nota de crédito</label>';
				}
			
				
			
			break;
			
			case 'numeroTransaccion':
				
				$idBanco = htmlspecialchars ($_POST['banco'],ENT_NOQUOTES,'UTF-8');
				$fechaDeposito = htmlspecialchars($_POST['fecha_deposito'],ENT_NOQUOTES,'UTF-8');
				$numeroPapeleta = htmlspecialchars ($_POST['papeletaBanco'],ENT_NOQUOTES,'UTF-8');
				$valorDepositado = htmlspecialchars ($_POST['valor_depositado'],ENT_NOQUOTES,'UTF-8');
				$formaPago = htmlspecialchars ($_POST['fpago'],ENT_NOQUOTES,'UTF-8');
				
				if($formaPago == 'Deposito'){
					
					$numeroTransaccion = $cf->obtenerDatosTransferenciaBancaria($conexion, $idBanco, $fechaDeposito, trim($numeroPapeleta), $valorDepositado);
					
					if(pg_num_rows($numeroTransaccion)){
						echo 'Los datos de deposito ya han sido registrados.';
						echo '<script type="text/javascript">$("#botonAgregar").attr("disabled","disabled");</script>';
					}else{
						echo '<script type="text/javascript">$("#botonAgregar").removeAttr("disabled");$("#res_numero_transaccion").html("");</script>';
					}
				}else{
					echo '<script type="text/javascript">$("#botonAgregar").removeAttr("disabled");$("#res_numero_transaccion").html("");</script>';
				}
					
			break;		
			
			default:
			echo 'Acción desconocida';
	}

?>

<script type="text/javascript">  

var vOpcion = <?php echo json_encode($opcion);?>;
var array_tarifario = <?php echo json_encode($arrayTarifario); ?>;
var tipoCliente = <?php echo json_encode($tipoCliente);?>;
var array_servicio = <?php echo json_encode($datosFactura);?>

if(tipoCliente == '01'){
	$("#ruc").attr("maxlength","13");
}
$("#ruc").change(function(event){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($('#ruc').val() == ''){
		error = true;
		$("#ruc").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese Ruc").addClass("alerta");
	}

	if(!esCampoValido("#ruc") || $("#ruc").val().length != $("#ruc").attr("maxlength")){
		error = true;
		$("#ruc").addClass("alertaCombo");
	}

	if (error){
		$("#estado").html("Ruc debe tener 13 digitos numéricos.!").addClass('alerta');
		$("#ruc").focus();
		
	}else{
		$("#estado").html("").removeClass('alerta');
			
	}

});

function esCampoValido(elemento){
	var patron = new RegExp("^[0-9]+$","g");
	return patron.test($(elemento).val());
}

if(vOpcion == 'tarifario'){

	distribuirLineas();	
		
		sdatos ='0';
		for(var i=0;i<array_tarifario.length;i++){
		    if (array_tarifario[i]['idCategoria'] != '1'){
		    	switch(array_tarifario[i]['idCategoria']){
			    	case '2':
				    	sdatos += '<optgroup label="'+array_tarifario[i]['codigo']+'- '+array_tarifario[i]['concepto']+'">';
			    	break;
			    	case '3':
			    		var concepto = array_tarifario[i]['concepto'];
			    		if(concepto.length > 100){
			    			var parteConcepto = concepto.substring(0, 100)+'...';
					    }else{
					    	var parteConcepto = concepto;
						}		    	    
			    		sdatos += '<option title = "'+array_tarifario[i]['concepto']+ ' - VALOR: '+array_tarifario[i]['valor']+' - UNIDAD MEDIDA: '+array_tarifario[i]['medida']+'" value="'+array_tarifario[i]['idServicio']+'" data-precio="'+array_tarifario[i]['valor']+'" data-subsidio="'+array_tarifario[i]['subsidio']+'" data-iva="'+array_tarifario[i]['iva']+'">'+array_tarifario[i]['codigo']+'- '+parteConcepto+'</option>';
			    	break;
		    	}
			}
		}
		
		$('#transaccion').html(sdatos);
		
}else if(vOpcion == 'tarifarioNotaCredito'){

	distribuirLineas();	
	
	sdatos ='0';
	for(var i=0;i<array_tarifario.length;i++){
	    if (array_tarifario[i]['idCategoria'] != '1'){
	    	switch(array_tarifario[i]['idCategoria']){
		    	case '2':
			    	sdatos += '<optgroup label="'+array_tarifario[i]['codigo']+'- '+array_tarifario[i]['concepto']+'">';
		    	break;
		    	case '3':
		    		for(var j=0;j<array_servicio.length;j++){

			    		if(array_tarifario[i]['idServicio'] == array_servicio[j]['idServicio']){

			    			var concepto = array_tarifario[i]['concepto'];
				    		if(concepto.length > 100){
				    			var parteConcepto = concepto.substring(0, 100)+'...';
						    }else{
						    	var parteConcepto = concepto;
							}		    	    
				    		sdatos += '<option title = "'+array_tarifario[i]['concepto']+ ' - VALOR: '+array_tarifario[i]['valor']+' - UNIDAD MEDIDA: '+array_tarifario[i]['medida']+'" value="'+array_tarifario[i]['idServicio']+'" data-precio="'+array_tarifario[i]['valor']+'" data-iva="'+array_tarifario[i]['iva']+'">'+array_tarifario[i]['codigo']+'- '+parteConcepto+'</option>';
							
				    	}

			    	}
		    	break;
	    	}
		}
	}
	$('#transaccion').html(sdatos);
}

$("#modificarCliente").click(function(){
	$("input").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#datosCliente").submit(function(event){
	event.preventDefault();
	ejecutarJson($(this));
});

$(document).ready(function(){

	$('#telefono').attr('data-inputmask', "'mask': '(99) 999-9999'");
	
	distribuirLineas();
	construirValidador();

});

</script>
