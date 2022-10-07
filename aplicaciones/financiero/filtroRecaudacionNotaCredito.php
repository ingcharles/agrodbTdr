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
			case 7:
				echo '<h1>Reporte nota de credito por provincia y punto de venta</h1>';
			break;
			case 9:
				echo '<h1>Reporte notas de crédito por punto de venta</h1>';
			break;
			case 10:
				echo '<h1>Reporte nota de crédito por provincia</h1>';
			break;
			}
	?>
	
	<nav>
	
	<form id="listarRecaudacionPorPuntoEmision" data-rutaAplicacion="financiero" data-opcion="listaRecaudacionNotaCredito" data-destino="tabla">
		<input type="hidden" name="opcionReporte" value="<?php echo $item; ?>"/>
		<table class="filtro">
		
			<tr>
			<th>Comprobante</th>
				<td>
					<select id="comprobante" name="comprobante" style="width: 100%;">
						<!-- option value="" >Seleccione....</option -->
						<!-- option value="factura" >Factura</option-->
						<option value="notaCredito" >Nota credito</option>
 				   </select>	
				</td>
			<th>Provincia</th>
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
			
			<tr>
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

	if (<?php echo $item;?>!="9" ){		     
		//$("#lestablecimiento").hide();
		//$("#establecimiento").hide();
		$("#fEstablecimiento").hide();		
	}else{
		$("#provincia").attr('disabled','disabled');
	}

	soficina ='0';
	soficina = '<option value="">Establecimiento...</option>';
    for(var i=0;i<array_oficina.length;i++){
	
		if ($("#provincia").val()==array_oficina[i]['provincia'] && $("#ruc").val()==array_oficina[i]['ruc']){
	    	soficina += '<option value="'+array_oficina[i]['numeroEstablecimiento']+'">'+array_oficina[i]['numeroEstablecimiento']+'->'+array_oficina[i]['oficina']+'</option>';
		    }
   		}
    $('#establecimiento').html(soficina);
   // $("#establecimiento").removeAttr("disabled");
   
    if (<?php echo $item;?> =="7" ){		     
		//$("#lestablecimiento").hide();
		//$("#establecimiento").hide();
		$("#fEstablecimiento").show();	
		$("#establecimiento").removeAttr("disabled");
		$("#provincia").find("option[value='todas']").remove();		
	}

    cargarValorDefecto("establecimiento","<?php echo $recaudador['numero_establecimiento'];?>");
	
	
});

$("#listarRecaudacionPorPuntoEmision").submit(function(event){
	event.preventDefault();
	
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

	if (<?php echo $item;?>=="7"){
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
		if (<?php echo $item;?>=="9"){	
			$("#establecimiento").attr("disabled","disabled");
			$("#provincia").attr("disabled","disabled");
			$("#comprobante").attr("disabled");
		}
	}
	
});

$("#ruc").change(function(){
	soficina ='0';
	soficina = '<option value="">Seleccione...</option>';
    for(var i=0;i<array_oficina.length;i++){

	    if ($("#provincia").val()==array_oficina[i]['provincia'] && $("#ruc").val()==array_oficina[i]['ruc']){
	    	soficina += '<option value="'+array_oficina[i]['numeroEstablecimiento']+'">'+array_oficina[i]['numeroEstablecimiento']+'->'+array_oficina[i]['oficina']+'</option>';
		    }
   		}
    $('#establecimiento').html(soficina);
    $("#establecimiento").removeAttr("disabled");
});

$("#provincia").change(function(event){   
	$("#ruc").val("");
	$("#establecimiento").val("");
});

</script>	

	