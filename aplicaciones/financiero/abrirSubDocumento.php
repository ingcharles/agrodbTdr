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
		$idServicio = $tmp[0];
		$idArea = $tmp[1];
		
		$tipoDocumento = pg_fetch_assoc($cf->abrirSubDocumento($conexion, $idServicio, $idArea));
		
		$codigo = $tipoDocumento['codigo'];
		$concepto = $tipoDocumento['concepto'];
			
	}else {
	
		$idServicio = $_POST['idServicio']; //Identificador servicio padre
		$codigo = $_POST['codigo'];  //Codigo de servicio padre
		$concepto = $_POST['concepto']; // Concepto servicio padre
		$idArea = $_POST['idArea']; //Área
	
	}
	
	$itemsDocumento = $cf->listaItems($conexion, $idServicio, $idArea);
	
	//INICIO EJAR
	$listaItems = array();
	$opcionesItemsExceso = array();
	
	while ($items = pg_fetch_assoc($itemsDocumento)){
		$listaItems[] = array(idServicio => $items['id_servicio'], codigo => $items['codigo'], concepto =>$items['concepto']);
	}
	
	$unidades = $cct->listarUnidadesMedida($conexion);
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
	
	<form id="regresar" data-rutaAplicacion="financiero" data-opcion="nuevoServicio" data-destino="detalleItem">
		<input type="hidden" name="idServicioDocumento" value="<?php echo $idServicio;?>"/>
		<input type="hidden" name="id" value="<?php echo $idArea;?>"/>
		<button class="regresar">Regresar a Tipo de documento</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarDocumento" data-rutaAplicacion="financiero" data-opcion="modificarDocumento">
					<input type="hidden" id="idDocumento" name="idDocumento" value="<?php echo $idServicio;?>">
					<input type="hidden" name="id" value="<?php echo $idArea;?>"/>
					<fieldset>
						<legend>Documento</legend>	
						<div data-linea="1">
							<label for="nombreDocumento" >Concepto</label>
							<input id="nombreDocumento" name="nombreDocumento" type="text" value="<?php echo $concepto;?>" disabled="disabled"/>
							
							<button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						</div>
					</fieldset>
				</form>	
				
				<form id="nuevoItem" data-rutaAplicacion="financiero" data-opcion="guardarNuevoItem" >
					<input type="hidden" id="idDocumento" name="idDocumento" value="<?php echo $idServicio;?>">
					<input type="hidden" name="id" value="<?php echo $idArea;?>" />
					<input type="hidden" name="codigoPadre" value="<?php echo $codigo;?>" />
					<input type="hidden" name="conceptoPadre" value="<?php echo $concepto;?>" />
					<fieldset>
						<legend>Item</legend>	
						<label>Concepto</label> 	
						<div data-linea="1">
							<textarea id="concepto" name="concepto" required="required" ></textarea>
						</div>
																		
						<div data-linea="2">
							<label for="unidad">Unidad</label>
							<input name="unidad" id="unidad" type="text" required="required" />
						</div>
						
						<div data-linea="2">
							<label for="valor">Valor</label>
							<input name="valor" id="valor" type="text" required="required" />
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
							<input name="subsidio" id="subsidio" type="text" required="required"/>
						</div>
						
						<div data-linea="3">
							<label for="partidaPresupuestaria">Partida presupuestaria</label>
							<input name="partidaPresupuestaria" id="partidaPresupuestaria" type="text"  required="required" />
						</div>
						
						<div data-linea="3">
							<label for="unidadMedida">Unidad de medida</label>							
							<select id="unidadMedida" name="unidadMedida" required>
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
								<select id="exceso" name="exceso" required>
									<option value="">Seleccione....</option>
									<option value="Si">Si</option>
									<option value="No">No</option>
								</select>
						</div>
						
						<div data-linea="4">
							<label>Usado para</label> 
								<select id="itemUsadoPara" name="itemUsadoPara" required>
									<option value="">Seleccione....</option>
									<option value="Fitosanitario">Fitosanitario</option>
								</select>
						</div>
						
						<div data-linea="5">
							<label id="lIdItemExceso">Item exceso</label> 
								<select id="idItemExceso" name="idItemExceso">
									<option value="" selected="selected">Seleccione....</option>
									<?php 
										foreach ($listaItems as $fila){
											$opcionesItemsExceso[]  = '<option value="' . $fila['idServicio'] . '" ><b>'.$fila['codigo'].'</b> - '. $fila['concepto'] .'</option>';
										}
									?>
								</select>
						</div>
						
					</fieldset>	
						
						<div>
							<button type="submit" class="mas">Añadir item</button>
						</div>
					
			    </form>
			    <fieldset>
					<legend>Items</legend>
					<table id="items">
						<?php 
							foreach ($listaItems as $items){
								echo $cf->imprimirLineaItem($items['idServicio'], $items['codigo'], $items['concepto'], 'financiero', $idArea, $idServicio, $codigo, $concepto);
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</body>
<script>

var array_opcionesExceso = <?php echo json_encode($opcionesItemsExceso);?>;

$('document').ready(function(){		
	distribuirLineas();

	$("#unidad").numeric(".");
	$("#valor").numeric(".");
	$("#subsidio").numeric(".");
	$("#idItemExceso").hide();
	$("#lIdItemExceso").hide();

	for(var i=0; i<array_opcionesExceso.length; i++){
		 $('#idItemExceso').append(array_opcionesExceso[i]);
   }
	   
});

$("#actualizarDocumento").submit(function(event){

	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#nombreDocumento").val()==""){
		error = true;
		$("#nombreDocumento").addClass("alertaCombo");
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
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});
	
acciones("#nuevoItem","#items");

$("#exceso").change(function(){
	if($("#exceso").val() == 'Si'){
		$("#idItemExceso").show();
		$("#lIdItemExceso").show();
		$("#idItemExceso").attr('required','required');
	}else{
		$("#idItemExceso").hide();
		$("#lIdItemExceso").hide();
		$("#idItemExceso").removeAttr('required');
	}
});
</script>
</html>
