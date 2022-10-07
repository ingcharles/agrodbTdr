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
		case 16:
			echo '<h1>Reporte de consumo de saldo disponible</h1>';
		break;
	}
?>
	<nav>
	
	<form id="listarRecaudacionSaldo" data-rutaAplicacion="financiero" data-opcion="listaRecaudacion" data-destino="tabla">
		<input type="hidden" name="opcionReporte" value="<?php echo $item; ?>"/>
		
		
		<table class="filtro" style="width:500px;">		
			
			<tr>
			<th>Provincia finalización</th>
				<td colspan="3">
					<select id="provincia" name="provincia" style="width: 100%;">
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
				<option value="">Seleccione...</option>
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
				<select id="establecimiento" name="establecimiento" style="width: 100%;">
				</select>
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
	
	soficina ='0';
	soficina = '<option value="">Establecimiento...</option>';
    for(var i=0;i<array_oficina.length;i++){
	    if ($("#provincia").val()==array_oficina[i]['provincia'] && $("#ruc").val()==array_oficina[i]['ruc']){
	    	soficina += '<option value="'+array_oficina[i]['numeroEstablecimiento']+'">'+array_oficina[i]['numeroEstablecimiento']+'->'+array_oficina[i]['oficina']+'</option>';
		    }
   		}
    $('#establecimiento').html(soficina);
      
	 cargarValorDefecto("establecimiento","<?php echo $recaudador['numero_establecimiento'];?>");

});

$("#listarRecaudacionSaldo").submit(function(event){
	event.preventDefault();

	$('#listarRecaudacionSaldo').attr('data-opcion','listaRecaudacionSaldo');
	$('#listarRecaudacionSaldo').attr('data-destino','tabla');

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

	if($("#provincia").val() != "todas"){
		if(!$.trim($("#establecimiento").val())){
			error = true;
			$("#establecimiento").addClass("alertaCombo");
		}
	}	
	
	if(!error){
		abrir($(this),event,false);				
	}
	
});

$("#ruc").change(function(){

		soficina ='0';
		soficina = '<option value="">Establecimiento...</option>';
	    for(var i=0;i<array_oficina.length;i++){
		    if ($("#provincia").val()==array_oficina[i]['provincia'] && $("#ruc").val()==array_oficina[i]['ruc']){
		    	soficina += '<option value="'+array_oficina[i]['numeroEstablecimiento']+'">'+array_oficina[i]['numeroEstablecimiento']+'->'+array_oficina[i]['oficina']+'</option>';
			    }
	   		}
	    $('#establecimiento').html(soficina);
});

</script>	

	