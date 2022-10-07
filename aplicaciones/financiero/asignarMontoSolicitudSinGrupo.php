<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cr = new ControladorRegistroOperador();
	
	$datos = explode('-', $_POST['id']);
	
	$idSolicitud = str_replace(' ', '', $datos[0]);
	$idOperador = $datos[1];
	$estadoActual =  $datos[2];
	$tipoSolicitud = $datos[3];
	$tipoTarifario = $datos[4];	
	
	$identificadorInspector = $_SESSION['usuario'];
	//$idGrupo = $_POST['nombreOpcion'];
	//$idVUE = $_POST['opcion'];
	
	$listaCliente =  $cc->listaComprador($conexion,$idOperador);
	$datosCliente = pg_fetch_assoc($listaCliente);
	$datosOperador = pg_fetch_assoc($cr->buscarOperador($conexion,$idOperador));
	
	if(pg_num_rows($listaCliente) == 0){	
		
		$identificadorOperador = $datosOperador['identificador'];
		$razonSocial = $datosOperador['razon_social'];
		$direccion = $datosOperador['direccion'];
		$telefono = $datosOperador['telefono_uno'];			
	}else{
		$identificadorOperador = $datosCliente['identificador'];
		$razonSocial = $datosCliente['razon_social'];
		$direccion = $datosCliente['direccion'];
		$telefono = $datosCliente['telefono'];	
	}
	
	$correo = $datosCliente['correo'];
	
	
		
	switch ($tipoSolicitud){
		case 'Emisión de Etiquetas':
			$estado = 'verificacion';
			$cantidadEtiquetas = $datos[5];
		break;
	}
	
	//echo number_format(100,2,'.','');
	
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

<header>
		<h1>Datos de facturación</h1>
</header>


<div id="estado"></div>

<form id="asignarMonto"  data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
	<input type="hidden" name="tipoSolicitud" value="<?php echo $tipoSolicitud;?>"/>
	<input type="hidden" name="estado" value="<?php echo $estado;?>"/>
	<input type="hidden" name="idOperador" value="<?php echo $idOperador;?>"/>
	<input type="hidden" id="opcion" name="opcion" value="0">
	<input type="hidden" id="tipoBusquedaCliente" name="tipoBusquedaCliente" value="01">

	<fieldset id="modificarDatosOperador">
			<legend>Datos de facturación</legend>
			
			<div data-linea="1">
				<label>Identificador</label>
					<input type="text" id="ruc" name="ruc" value="<?php echo $identificadorOperador;?>"  readonly="readonly">
			</div>
			
			<div data-linea="2">
				<label>Razón Social: </label> 
						<input type="text" id="razonSocial" name="razonSocial" value="<?php echo $razonSocial;?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
			</div>
			
			<div data-linea="3">
				<label>Dirección: </label> 
					<input type="text" id="direccion" name="direccion" value="<?php echo $direccion;?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
			</div>
			
			<div data-linea="4">
				<label>Teléfono: </label> 
					<input type="text" id="telefono" name="telefono" value="<?php echo $telefono;?>" disabled="disabled" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?"  data-inputmask="'mask': '(99) 999-9999'"/>
			</div>
			
			<div data-linea="5">
				<label>Correo: </label> 
					<input type="text" id="correo" name="correo" value="<?php echo $correo;?>" disabled="disabled" title="99" />
			</div>
			
			
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
					
				</select>
			</div>
			
			<div data-linea="4">
				<label>Buscar codigo</label>
				<input type="search" id="codigo" name="codigo" />
			</div>
			
			<div id="res_tarifario" data-linea="3"></div>
			
			<div data-linea="5">
				<label>Cantidad</label> 
				<input type="text" step="0.001" id="cantidad" name="cantidad" value="<?php echo $cantidadEtiquetas; ?>" placeholder="Ej: 1234.561" data-er="^[0-9]+(\.[0-9]{1,3})?$" title="Ejemplo: 999.999" />
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
					
					<tbody id="detalleTransaccion">
					
					<?php 
					
					if($estadoActual != 'pago'){

						$ordenPago = pg_fetch_assoc($cc->obtenerIdOrdenPagoXtipoOperacionSinGrupo($conexion, $idSolicitud, $tipoSolicitud));
						
						$idOrdenPago = $ordenPago['id_pago'];
						
						$contador = 0;
						$res = $cc->abrirDetallePago($conexion, $ordenPago['id_pago']);
						
						while($fila = pg_fetch_assoc($res)){
							$numSecuencial = ++$contador;
							$transaccion = $fila['concepto_orden'];
							$cantidad = $fila['cantidad'];
							$precio = $fila['precio_unitario']*1;
							$descuento = $fila['descuento'];
							$ivaProducto = $fila['iva'];
							$subsidio = $fila['subsidio'];
						
							$subTotalProducto = round($cantidad * $precio,2);
							$subTotalDescuento = $subTotalProducto - $descuento;
						
							$totalProducto = $subTotalDescuento + $ivaProducto;
								
							echo "<tr id='r_".$fila['id_servicio']."'>
											<td><button type='button' onclick='quitarTransaccion(\"#r_".$fila['id_servicio']."\")' class='menos'>Quitar</button></td>
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
					}
					
					?>
					
											
					</tbody>
			  </table>
			  
			  <input type="hidden" name="idOrdenPago" value="<?php echo $idOrdenPago;?>"/>
			  
	</fieldset>
	
	<button type="submit" class="guardar">Autorizar pago</button>
	
</form>

<script type="text/javascript">

	var tipo_tarifario = <?php echo json_encode($tipoTarifario); ?>;
	var iva=<?php echo json_encode($iva); ?>;
	var session_usuario = <?php echo json_encode($sessionUsuario); ?>;
	var tipoSolicitud = <?php echo json_encode($tipoSolicitud); ?>;
	

	$(document).ready(function(){
		distribuirLineas();
		$("#descuento").numeric(".");
		$("#cantidad").numeric(".");
		$("#descuento").val('0');	

	
		/*if(tipoSolicitud=="Emisión de Etiquetas"){
			$("#cantidad").attr('readOnly','readOnly');
		}*/
		
		total = sumarValor('totalIndividual');
		ivaTotal = sumarValor('ivaIndividual');
		subTotal = sumarValor('subTotalDescuento');
	
		$("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );

		$("#valorTotal").val(total);

		if(tipo_tarifario == 'tarifarioNuevo'){
			$("#area").html('<option value="" selected="selected">Área....</option><option value="SA">Sanidad Animal</option><option value="SV">Sanidad Vegetal</option><option value="IA">Inocuidad de los Alimentos</option><option value="LT">Análisis de laboratorios</option><option value="CGRIA">Control de Insumos Agropecuarios</option><option value="AGR">Otros ingresos</option>');
		}else{
			$("#area").html('<option value="" selected="selected">Área....</option><option value="GENER">General</option>');
		}

		if(session_usuario == 'inactivo'){
			$("#estado").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
			$("#bGuardarOrdenPago").attr("disabled", "disabled");
		}
		
	});

	$("#area").change(function(event){
		$('#asignarMonto').attr('data-opcion','accionesCliente');
		$('#asignarMonto').attr('data-destino','res_tarifario');
		$('#asignarMonto').attr('data-rutaAplicacion','financiero');
		$('#opcion').val('tarifario');	
		abrir($("#asignarMonto"),event,false); //Se ejecuta ajax, busqueda de sitio
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

var contador = 0; 

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
			if($("#detalleTransaccion #r_"+$("#transaccion").val()).length==0){
			
				$("#detalleTransaccion").append("<tr id='r_"+$("#transaccion").val()+"'>"+
													"<td><button type='button' onclick='quitarTransaccion(\"#r_"+$("#transaccion").val()+"\")' class='menos'>Quitar</button></td>"+
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
			}else{
				$("#estado").html("No se permiten items similares.").addClass('alerta');
			}
		}
	}
 }

function redondearNumero(num) {    
    return +(Math.round(num + "e+2")  + "e-2");
} 

function quitarTransaccion(fila){

	$("#detalleTransaccion tr").eq($(fila).index()).remove();

	total = sumarValor('totalIndividual');
	ivaTotal = sumarValor('ivaIndividual');
	subTotal = sumarValor('subTotalDescuento');

	 $("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );
	 $("#valorTotal").val(total);
	
	// distribuirLineas();
}

	function sumarValor(campo){
	
		var valor = 0; 
		
		$('input[id="'+campo+'"]').each(function(e){   
			valor += Number($(this).val());
			valor = redondearNumero(valor);
	    });
	
	    return valor;
	}

	$("#asignarMonto").submit(function(event){
		event.preventDefault();

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

		if($('#idDeposito').length == 0 ){
			error = true;
			$("#estado").html("Por favor ingrese uno o varios detalles").addClass("alerta");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{

			 $("#modificarDatosOperador input").removeAttr("disabled");
			 $('#asignarMonto').attr('data-opcion','guardarNuevoOrdenPago');
			 $('#asignarMonto').attr('data-destino','detalleItem');
			 $('#asignarMonto').attr('data-rutaAplicacion','financiero');

			abrir($("#asignarMonto"),event,false);
		}
	});

	function CampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

</script>
</html>

