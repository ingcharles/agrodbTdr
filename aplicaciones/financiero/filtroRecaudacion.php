<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cca = new ControladorCatalogos();
	$cf = new ControladorFinanciero();
	
	$identificador = $_SESSION['usuario'];
	
	$recaudador = pg_fetch_assoc($cf->obtenerDatosRecaudador($conexion, $identificador));
	
	$qProvincias = $cca->listarLocalizacion($conexion, 'PROVINCIAS');
	
	$item = $_POST['id'];
	
	$qOficinas= $cc->listarTodosEstablecimientos($conexion);
	while($fila = pg_fetch_assoc($qOficinas)){
		$oficinas[]= array('numeroEstablecimiento'=>$fila['numero_establecimiento'], 'provincia'=>$fila['provincia'], 'oficina'=>$fila['oficina'], 'ruc'=>$fila['ruc']);
	}
?>

<header>
	<?php 
	switch ($item){
		case 1:
			echo '<h1>Reporte de facturación por provincia</h1>';
			break;
		case 2:
			echo '<h1>Reporte por item</h1>';
			break;
		case 3:
			echo '<h1>Reporte por depósito</h1>';
			break;
		case 4:
			echo '<h1>Reporte por número de factura</h1>';
			break;
		case 5:
			echo '<h1>Reporte consolidado por partida presupuestaria</h1>';
			break;
		case 6:
			echo '<h1>Reporte de facturación por diferentes puntos de recaudación</h1>';
			break;
		case 8:
			echo '<h1>Reporte de facturación por punto de recaudación</h1>';
			break;
		case 11:
			echo '<h1>Reporte de excedentes por provincia</h1>';
			break;
		case 12:
			echo '<h1>Reporte de excedentes por punto de recaudación</h1>';
			break;
		case 13:
			echo '<h1>Reporte ingreso de caja por provincia</h1>';
			break;
		case 14:
			echo '<h1>Reporte ingreso de caja por punto de recaudación</h1>';
			break;
		case 15:
			echo '<h1>Reporte de items facturados por punto de recaudación</h1>';
			break;
		case 17:
			echo '<h1>Reporte de comprobantes de saldo disponible</h1>';
			break;
		case 18:
			echo '<h1>Reporte de cuadre de caja diario</h1>';
			break;
	}
?>
	<nav>
	
	<form id="listarRecaudacionPorPuntoEmision" data-rutaAplicacion="financiero" data-opcion="listaRecaudacion" data-destino="tabla">
		<input type="hidden" name="opcionReporte" value="<?php echo $item; ?>"/>
		<input type="hidden" name="opcionServicio" id="opcionServicio"/>
		<table class="filtro" style="width:500px;">
		
			<tr>
			<th>Comprobante</th>
				<td>
					<select id="comprobante" name="comprobante" style="width: 100%;">
							<!-- option value="" >Seleccione....</option -->
							<option value="factura" >Factura</option>
							<option value="ingresoCaja" >Ingreso de caja</option>
 				   </select>	
				</td>
			<th>Provincia finalización</th>
				<td>
					<select id="provincia" name="provincia">
						<option value="todas">Todas las provincias</option>
								<?php 
									while ($fila = pg_fetch_assoc($qProvincias)){
										if($_SESSION['nombreProvincia'] == $fila['nombre']){
											echo '<option value="'.$fila['nombre'].'" selected="selected">'.$fila['nombre'].'</option>';
										}else{
											echo '<option value="'.$fila['nombre'].'">'.$fila['nombre'].'</option>';
										}
									}
								?>
					</select>
				</td>		
			</tr>			
			<tr id="fRuc">
			<th id="lruc">RUC</th>
				<td colspan="3">
				<select id="ruc" name="ruc" style="width: 100%;">
				<option value="">Todos...</option>
				<?php 
				$dDistritos = $cc->listarTodosDistritos($conexion);
				
				while($distritos = pg_fetch_assoc($dDistritos)){
					echo '<option value="'.$distritos['ruc'].'">'.$distritos['ruc'].'</option>';
				}
				
				?>
				</select>
				</td>
			</tr>
			<tr id="fEstablecimiento">
				<th id="lestablecimiento">Punto de venta</th>
				<td colspan="3">
				<select id="establecimiento" name="establecimiento" disabled="disabled" style="width: 100%;">
				</select>
				</td>
			</tr>
			
			
			<!-- //INICIO MGNM  -->
			<tr id="fArea">
			<th id="lArea">Área</th>
				<td>				
				<select id="area" name="area" style ="width: 100px;">
						<option value="todos" selected="selected">Todas</option>
						<?php 
							$area= $cc-> obtenerAreasServicios($conexion);
							
							while ($fila = pg_fetch_assoc($area)){
					    		echo '<option value="'.$fila['id_area']. '">'. $fila['concepto'] .'</option>';
					    	}
						?>
				</select>
				</td>
			<th id="lCodigo">Código</th>
				<td>
					<input type="search" id="codigo" name="codigo" />
				</td>
			</tr>
			
			<tr id="fTarifario">
			
			</tr>	
			<!-- //FIN MGNM  -->
			
			
			<tr id="fcliente">
				<th id="lcliente">Cliente</th>
				<td colspan="4">
					<input id="cliente" name="cliente" type="text"  style="width: 100%;"/>
				</td>
			</tr>
			
			<tr id="fFecha">
				<th>Fecha inicio</th>
					<td>
						<input id="fechaInicio" name="fechaInicio" type="text" required="required" style="width: 100%;"/>
					</td>
					
				<th>Fecha fin</th>
				
					<td>
						<input id="fechaFin" name="fechaFin" type="text" required="required" style="width: 100%;"/>
					</td>	
			</tr>
								
			<tr>	
				<td colspan="5">
					<button>Filtrar lista</button>
				</td>
			</tr>

		</table>
		
	</form>
		
	</nav>

</header>

<div id="tabla"></div>

<script type="text/javascript">

var array_oficina= <?php echo json_encode($oficinas); ?>;								

$('document').ready(function(){

	$("#fcliente").hide();
	$("#fArea").hide();
	$("#lCodigo").hide();
	$("#codigo").hide();
	
	$("#fechaInicio").datepicker({
	    changeMonth: true,
	    changeYear: true,
	    onSelect: function(dateText, inst) {
   		 $('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val()); 
       } 
	});

	$("#fechaFin").datepicker({
	    changeMonth: true,
	    changeYear: true
	});
	
	if (<?php echo $item;?>!="8" ){
		$("#fEstablecimiento").hide();		
	}else{
		$("#provincia").attr('disabled','disabled');
	}

	soficina ='0';
	soficina = '<option value="">Establecimiento...</option>';
    for(var i=0;i<array_oficina.length;i++){
	    if ($("#provincia").val()==array_oficina[i]['provincia'] && $("#ruc").val()==array_oficina[i]['ruc']){
	    	soficina += '<option value="'+array_oficina[i]['numeroEstablecimiento']+'" data-ruc="'+array_oficina[i]['ruc']+'">'+array_oficina[i]['numeroEstablecimiento']+'->'+array_oficina[i]['oficina']+'->'+array_oficina[i]['ruc']+'</option>';
		    }
   		}
    $('#establecimiento').html(soficina);
     
    if (<?php echo $item;?> =="6"){		     
		$("#fEstablecimiento").show();	
		$("#establecimiento").removeAttr("disabled");
		$("#provincia").find("option[value='todas']").remove();	
	}

    if (<?php echo $item;?> =="17"){		     
		$("#fEstablecimiento").show();	
		$("#establecimiento").removeAttr("disabled");
		$("#provincia").find("option[value='todas']").remove();	
		$('#comprobante').html('<option value="comprobanteFactura" >Comprobante pago VUE</option>');
	}

	if (<?php echo $item;?> =="11" ){		     
		$("#fcliente").show();	
	}

    if (<?php echo $item;?> =="12" ){		     		
		$("#fEstablecimiento").show();	
		$("#establecimiento").attr('disabled','disabled');
		$("#provincia").attr('disabled','disabled');
		$("#fcliente").show();		
	}


	if (<?php echo $item;?> =="13" ){
    	
		$("#comprobante").find("option[value='factura']").remove();
		$("#comprobante").find("option[value='comprobanteFactura']").remove();
    } else if (<?php echo $item;?> =="14" ) {
    	$("#fEstablecimiento").show();	
		$("#establecimiento").attr('disabled','disabled');
		$("#provincia").attr('disabled','disabled');
    	$("#comprobante").find("option[value='factura']").remove();
		
    }else
        
      	$("#comprobante").find("option[value='ingresoCaja']").remove();
 
	 cargarValorDefecto("establecimiento","<?php echo $recaudador['numero_establecimiento'];?>");

	if (<?php echo $item;?> =="15" ){

		$("#provincia").find("option[value='todas']").remove();	
		$("#establecimiento").removeAttr("disabled");
		
		$('#provincia').prepend('<option value="0">Provincia...</option>'); 
		$('#comprobante').append('<option value="comprobanteFactura" >Comprobante pago VUE</option>');
		$("#provincia").find("option[value='0']").attr("selected",true);

		$("#fFecha").hide();
	}

	if (<?php echo $item;?> =="18"){		     
		$("#fEstablecimiento").show();	
		$("#establecimiento").removeAttr("disabled");
		$("#provincia").find("option[value='todas']").remove();	
		$("#comprobante").html(''); 
		$("#comprobante").prepend('<option value="factura">Todos...</option>'); 
	}
	
});

$("#listarRecaudacionPorPuntoEmision").submit(function(event){
	event.preventDefault();

	$('#listarRecaudacionPorPuntoEmision').attr('data-opcion','listaRecaudacion');
	$('#listarRecaudacionPorPuntoEmision').attr('data-destino','tabla');

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	
	if(!$.trim($("#fechaInicio").val())){
		error = true;
		$("#fechaInicio").addClass("alertaCombo");
	}

	if(!$.trim($("#fechaFin").val())){
		error = true;
		$("#fechaFin").addClass("alertaCombo");
	}

	if (<?php echo $item;?>=="6" || <?php echo $item;?>=="17"){
		if(!$.trim($("#establecimiento").val())){
			error = true;
			$("#establecimiento").addClass("alertaCombo");
		}
	}

	if (<?php echo $item;?>=="8"){
		if(!$.trim($("#establecimiento").val())){
			error = true;
			$("#establecimiento").addClass("alertaCombo");
		}
	}

	if(!error){
		$("#establecimiento").removeAttr("disabled");
		$("#provincia").removeAttr("disabled");
		$("#comprobante").removeAttr("disabled");
		abrir($(this),event,false);
		if (<?php echo $item;?>=="8" || <?php echo $item;?>=="12" || <?php echo $item;?>=="14"){	
			$("#establecimiento").attr("disabled","disabled");
			$("#provincia").attr("disabled","disabled");
			$("#comprobante").attr("disabled","disabled");
		}
				
	}
	
});

$("#ruc").change(function(){

	if (<?php echo $item;?> =="15" ){
		soficina ='0';
		soficina = '<option value="">Establecimiento...</option><option value="todos">Todos</option>';
	    for(var i=0;i<array_oficina.length;i++){
		    if ($("#provincia").val()==array_oficina[i]['provincia'] && $("#ruc").val()==array_oficina[i]['ruc']){
		    	soficina += '<option value="'+array_oficina[i]['numeroEstablecimiento']+'" data-ruc="'+array_oficina[i]['ruc']+'">'+array_oficina[i]['numeroEstablecimiento']+'->'+array_oficina[i]['oficina']+'</option>';
			    }
	   		}

	    if($('#provincia').val()!=0){		    
			$('#fEstablecimiento').show();
			$("#fFecha").show();
		    $('#establecimiento').html(soficina);
		    $("#establecimiento").removeAttr("disabled");
		    
		}else{
			$("#fFecha").hide();
			$('#fEstablecimiento').hide();
			$('#fArea').hide();
			$('#fTransaccion').hide();
			$('#fTarifario').hide();
		}
		
	}else{
		soficina ='0';
		soficina = '<option value="">Establecimiento...</option>';
	    for(var i=0;i<array_oficina.length;i++){
		    if ($("#provincia").val()==array_oficina[i]['provincia'] && $("#ruc").val()==array_oficina[i]['ruc']){
		    	soficina += '<option value="'+array_oficina[i]['numeroEstablecimiento']+'" data-ruc="'+array_oficina[i]['ruc']+'">'+array_oficina[i]['numeroEstablecimiento']+'->'+array_oficina[i]['oficina']+'</option>';
			    }
	   		}
	    $('#establecimiento').html(soficina);
	    $("#establecimiento").removeAttr("disabled");
	}


});

$("#establecimiento").change(function(){
	
	if($("#establecimiento").val()!='' && <?php echo $item;?> =="15"){
		$("#fArea").show();
	}else{
		$("#fArea").hide();
		$("#fTarifario").hide();
	}
});   

$("#area").change(function(event){

	$('#listarRecaudacionPorPuntoEmision').attr('data-opcion','accionesReporte');
	$('#listarRecaudacionPorPuntoEmision').attr('data-destino','fTarifario');
	$('#opcionServicio').val('tarifario');	
	abrir($("#listarRecaudacionPorPuntoEmision"),event,false);

/////REVISO
	$('#transaccion').prepend('<option value="todos">Todos</option>'); 
	$("#transaccion").find("option[value='0']").attr("selected",true);

	$('#transaccion').val("");
	
	if($("#area").val()=="todos"){
		$("#lCodigo").hide();
		$("#codigo").hide();
	}else{
		$("#lCodigo").show();
		$("#codigo").show();
		$("#codigo").val("");
	}
	event.stopPropagation();
	distribuirLineas();
 });

$("#codigo").change(function(event){    

	if($("#codigo").val() == 0){		sbusqueda ='0';
		sbusqueda+='<option value="todos">Todos</option>';		
	}else{
		sbusqueda ='0';
	}
	
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
	
	
	if(sbusqueda!=0){
		$('#fTarifario').show();
		$("#transaccion").find("option[value='todas']").remove();
		$('#transaccion').html(sbusqueda);
	}else{
		$('#fTarifario').hide();
		$('#codigo').val("");
	}
});


$("#transaccion").change(function(event){   
	$("#codigo").val("");
});

$("#provincia").change(function(event){   
	$("#ruc").val("");
	$("#establecimiento").val("");
});

</script>	

	