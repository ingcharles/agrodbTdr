<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorFinanciero.php';
	require_once '../../clases/ControladorCertificados.php';

	
$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$cf = new ControladorFinanciero();
	$cct = new ControladorCatalogos();
	
	if($_POST['id']!= '' ){
		
		$idItem = $_POST['id'];
		$tmp= explode("-", $idItem);
		$idItem = $tmp[0];
		$idArea = $tmp[1];
		$idServicio = pg_fetch_result($cf -> buscarIdPadre($conexion,$idItem,$idArea, 'activo'), 0, 'id_servicio_padre');
		
		$tipoDocumento = pg_fetch_assoc($cf->abrirSubDocumento($conexion, $idServicio, $idArea));
		
		$codigo = $tipoDocumento['codigo'];
		$concepto = $tipoDocumento['concepto'];
				
	}else {
	
		$idItem = $_POST['idItem'];
		$idArea = $_POST['idArea'];
		$idServicio = $_POST['idServicio'];
		$codigo = $_POST['codigoPadre'];
		$concepto = $_POST['conceptoPadre'];
	}
	
	$items = pg_fetch_assoc($cf->abrirSubDocumento($conexion, $idItem,$idArea));
	
	//INICIO EJAR
	$itemsDocumento = $cf->listaItems($conexion, $idServicio, $idArea);
		
	$listaItems = array();
	$opcionesItemsExceso = array();
	
	while ($fila = pg_fetch_assoc($itemsDocumento)){
		$listaItems[] = array(idServicio => $fila['id_servicio'], codigo => $fila['codigo'], concepto =>$fila['concepto']);
	}
	
	$unidades = $cct->listarUnidadesMedida($conexion);
	
	$tipoProducto = $cct->listarTipoProductosXarea($conexion, $idArea);
	
	$servicioPorProducto = $cf->obtenerServicioProducto($conexion, $idItem);
	//FIN EJAR	
	
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
</head>

<body>
	<header>
		<h1>Detalle de subdocumento</h1>
	</header>
	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="financiero" data-opcion="abrirSubDocumento" data-destino="detalleItem">
		<input type="hidden" name="idArea" value="<?php echo $idArea;?>"/>
		<input type="hidden" name="idServicio" value="<?php echo $idServicio;?>"/>
		<input type="hidden" name="codigo" value="<?php echo $codigo;?>"/>
		<input type="hidden" name="concepto" value="<?php echo $concepto;?>"/>
		
		<button class="regresar">Regresar a Item</button>
	</form>
	
	<div class="pestania" id="ParteI">
	
		<table class="soloImpresion">
			<tr>
				<td>
					<form id="actualizarItem" data-rutaAplicacion="financiero" data-opcion="modificarItem">
						<input type="hidden" id="idItem" name="idItem" value="<?php echo $items['id_servicio'];?>">
						<input type="hidden" name="id" value="<?php echo $idArea;?>"/>
						<fieldset>
							<legend>Item</legend>	
							
								<label>Concepto</label> 	
							<div data-linea="1">
								<textarea id="concepto" name="concepto" disabled="disabled" ><?php echo $items['concepto'];?></textarea>
							</div>
																	
							<div data-linea="2">
								<label for="unidad">Unidad</label>
								<input name="unidad" id="unidad" type="text" value="<?php echo $items['unidad'];?>" disabled="disabled"/>
							</div>
							
							<div data-linea="2">
								<label for="valor">Valor</label>
								<input name="valor" id="valor" type="text" value="<?php echo $items['valor'] * 1;?>" disabled="disabled"/>
							</div>
							
							<div data-linea="2">
								<label id="liva">Iva</label> 
									<select id="iva" name="iva">
										<option value="0">Seleccione....</option>
										<option value="TRUE">Iva 12%</option>
										<option value="FALSE">Excepto de iva</option>
									</select>
							</div> 
							
							<div data-linea="2">
								<label for="subsidio">Subsidio</label>
								<input name="subsidio" id="subsidio" type="text" value="<?php echo $items['subsidio'] * 1;?>" disabled="disabled"/>
							</div>
							
							<div data-linea="3">
								<label for="partidaPresupuestaria">Partida presupuestaria</label>
								<input name="partidaPresupuestaria" id="partidaPresupuestaria" type="text" value="<?php echo $items['partida_presupuestaria'];?>" disabled="disabled"/>
							</div>
							
							<div data-linea="3">
								<label for="unidadMedida">Unidad de medida</label>							
								<select id="unidadMedida" name="unidadMedida" disabled="disabled" required>
										<option value="" selected="selected">Seleccione una unidad....</option>
										<?php 
											while ($fila = pg_fetch_assoc($unidades)){
														echo '<option value="' . $fila['codigo'] . '" >'. $fila['nombre'] .'</option>';
											}
										?>
								</select>
							</div>
							
							<div data-linea="4">
								<label>Cobro por exceso</label> 
									<select id="exceso" name="exceso" disabled="disabled" required>
										<option value="">Seleccione....</option>
										<option value="Si">Si</option>
										<option value="No">No</option>
									</select>
							</div>
							
							<div data-linea="4">
								<label>Usado para</label> 
									<select id="itemUsadoPara" name="itemUsadoPara" disabled="disabled" >
										<option value="">Seleccione....</option>
										<option value="Fitosanitario">Fitosanitario</option>
									</select>
							</div>
							
							<div data-linea="5">
								<label id="lIdItemExceso">Item exceso</label> 
									<select id="idItemExceso" name="idItemExceso" disabled="disabled">
										<option value="">Seleccione....</option>
										<?php 
											foreach ($listaItems as $fila){
												$opcionesItemsExceso[]  = '<option value="' . $fila['idServicio'] . '" ><b>'.$fila['codigo'].'</b> - '. $fila['concepto'] .'</option>';
											}
										?>
									</select>
							</div>
							
						</fieldset>	
							
								<button id="modificar" type="button" class="editar">Editar</button>
								<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</form>	
					
					
				    
				</td>
			</tr>
		</table>
	</div>
	<div class="pestania" id="ParteII">
		<form id="nuevoItemProducto" data-rutaAplicacion="financiero" data-opcion="guardarNuevoItemProducto">
			
			<input type="hidden" name="idArea" value="<?php echo $idArea;?>"/>
			<input type="hidden" id="opcion" name="opcion" />
			<input type="hidden" id="idServicioProducto" name="idServicioProducto" value="<?php echo $items['id_servicio'];?>">
		
			<fieldset>
				<legend>Asignación de productos</legend>
				<div data-linea="1">
					<label>Tipo de producto</label>
						<select id="tipoProducto" name="tipoProducto" required>
							<option value="" selected="selected" >Seleccione....</option>
								<?php 
									while ($fila = pg_fetch_assoc($tipoProducto)){
										echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
									}
								?>
						</select>
					</div>
					
				<div data-linea="2">			
					<div id="dSubTipoProducto"></div>
				</div>
				
				<div data-linea="3">
					<div id="dProducto"></div>			
				</div>	
				
				<button type="submit" class="mas">Añadir producto</button>
					
			</fieldset>
		</form>
		
		<fieldset>
			<legend>Productos</legend>
				<table id="itemsProducto">
				<tr style="font-weight: bold;">						
							<th>#</th>	
							<th>Producto</th>	
							<th>Exoneración</th>
							<th>Eliminar</th>
				</tr>
				
					<?php 
						while ($fila = pg_fetch_assoc($servicioPorProducto)){
							$nombreProducto = pg_fetch_assoc($cct->obtenerNombreProducto($conexion, $fila['id_producto']));
							
													
							switch ($fila['exoneracion']){

								case 't';
									$exoneracion = 'activo';
								break;
								
								case 'f':
									$exoneracion = 'inactivo';
								break;
								
							}
							
							echo $cf->imprimirLineaServicioProducto($fila['id_servicio_producto'], $fila['id_producto'], $nombreProducto['nombre_comun'], $exoneracion);
						}
					?>
				</table>
		</fieldset>
	</div>
</body>
<script>
var iva = <?php echo json_encode($items['iva']); ?>;
var usado_para = <?php echo json_encode($items['usado_para']); ?>;
var id_servicio_exceso = <?php echo json_encode($items['id_servicio_exceso']); ?>;
var cobro_exceso = <?php echo json_encode($items['cobro_exceso']); ?>;
var unidad_medida = <?php echo json_encode($items['unidad_medida']); ?>;
var array_opcionesExceso = <?php echo json_encode($opcionesItemsExceso);?>;
var valorIva;

$('document').ready(function(){		
	distribuirLineas();
	
		if(iva == 't'){
			valorIva = 'TRUE';
		}else{
			valorIva = 'FALSE';
		}
	
	$("#unidad").numeric(".");
	$("#valor").numeric(".");
	$("#subsidio").numeric(".");
	$("#idItemExceso").hide();
	$("#lIdItemExceso").hide();

	for(var i=0; i<array_opcionesExceso.length; i++){
		 $('#idItemExceso').append(array_opcionesExceso[i]);
  	}

	cargarValorDefecto("iva",valorIva);
	cargarValorDefecto("itemUsadoPara",usado_para);
	cargarValorDefecto("idItemExceso",id_servicio_exceso);
	cargarValorDefecto("exceso",cobro_exceso);
	cargarValorDefecto("unidadMedida",unidad_medida);

	if($("#exceso").val() == 'Si'){
		$("#idItemExceso").show();
		$("#lIdItemExceso").show();
		$("#idItemExceso").attr('required','required');
	}else{
		$("#idItemExceso").hide();
		$("#lIdItemExceso").hide();
		$("#idItemExceso").removeAttr('required');
	}

	construirAnimacion($(".pestania"));	
});

$("#actualizarItem").submit(function(event){

	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#unidad").val()==""){
		error = true;
		$("#unidad").addClass("alertaCombo");
	}

	if($("#valor").val()==""){
		error = true;
		$("#valor").addClass("alertaCombo");
	}

	if($("#subsidio").val()==""){
		error = true;
		$("#subsidio").addClass("alertaCombo");
	}

	
	if(error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson($(this));
	}
	
});

$("#modificar").click(function(){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("textarea").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#regresar").submit(function(event){
	event.preventDefault();
	abrir($(this),event,false);
});

$("#exceso").change(function(){
	if($("#exceso").val() == 'Si'){
		$("#idItemExceso").show();
		$("#lIdItemExceso").show();
		$("#idItemExceso").attr('required','required');
	}else{
		$("#idItemExceso").hide();
		$("#lIdItemExceso").hide();
		$("#idItemExceso").removeAttr('required');
		$("#idItemExceso").val("");
	}
});

acciones("#nuevoItemProducto","#itemsProducto");

$("#tipoProducto").change(function(event){

	if($("#subtipoProducto").length != 0){
		$("#dProducto").html('');
 	 }

	$("#estado").html("").removeClass("alerta");
	$(".alertaCombo").removeClass("alertaCombo");
	
	$("#nuevoItemProducto").attr('data-opcion', 'combosProducto');
    $("#nuevoItemProducto").attr('data-destino', 'dSubTipoProducto');
    $("#opcion").val('subTipoProducto');

	if($("#tipoProducto").val() == ''){
		$("#tipoProducto").addClass("alertaCombo");
		$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");
	}else{
		event.stopImmediatePropagation();
		abrir($("#nuevoItemProducto"), event, false); 
	}    
});
</script>
</html>
