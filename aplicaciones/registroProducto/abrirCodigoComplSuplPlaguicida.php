<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idCodigoCompSupl = $_POST['idCodigoCompSupl'];
	$idProducto = $_POST['idProducto'];
	$idPartida = $_POST['idPartida'];
	$partidaArancelaria = $_POST['partidaArancelaria'];
	$codigoComplementario = $_POST['codigoComplementario'];
	$codigoSuplementario = $_POST['codigoSuplementario'];
	$areaProducto = $_POST['areaProducto'];

	$conexion = new Conexion();	
	$cr = new ControladorRequisitos();
	$cc = new ControladorCatalogos();
	
	$codigo = pg_fetch_assoc($cr->buscarCodigoCompSuplXPartidaArancelaria($conexion, $idCodigoCompSupl));
	$presentaciones = $cr->buscarPresentacionesXCodigoCompSupl($conexion,$idCodigoCompSupl);
	
	$unidades = $cc->listarUnidadesMedida($conexion);
	while($fila = pg_fetch_assoc($unidades)){
	    $unidad[]= array('identificador'=>$fila['id_unidad_medida'], 'codigo'=>$fila['codigo'], 'nombre'=>$fila['nombre'], 'tipo'=>$fila['tipo_unidad']);
	}
?>

<body>
	<header>
		<h1>Detalle de Código Complementario y Suplementario</h1>
	</header>
	
	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirPartidaPlaguicida" data-destino="detalleItem">
		<input type="hidden" name="idProducto" value="<?php echo $idProducto;?>"/>
		<input type="hidden" name="idPartida" value="<?php echo $idPartida;?>"/>
		<input type="hidden" name="partidaArancelaria" value="<?php echo $partidaArancelaria;?>"/>
		<input type="hidden" name="areaProducto" value="<?php echo $areaProducto;?>"/>
		<input type="hidden" name="numeroPestania" value="2"/>
		<button class="regresar">Regresar a Partida Arancelaria</button>
	</form>
	
	<fieldset>
		<legend>Código Complementario y Suplementario</legend>
			
			<div data-linea="1">
				<label>Código complementario: </label> <?php echo $codigo['codigo_complementario'];?>
			</div>
			
			<div data-linea="1">
				<label>Código suplementario: </label> <?php echo $codigo['codigo_suplementario'];?>
			</div>
		
	</fieldset>
	
	<form id="nuevaPresentacion" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevaPresentacionPlaguicida" >
		<input type="hidden" id="idCodigoCompSupl" name="idCodigoCompSupl" value="<?php echo $idCodigoCompSupl;?>">
		<input type="hidden" name="idProducto" value="<?php echo $idProducto;?>"/>
		<input type="hidden" name="idPartida" value="<?php echo $idPartida;?>"/>
		<input type="hidden" name="partidaArancelaria" value="<?php echo $partidaArancelaria;?>"/>
		<input type="hidden" name="codigoComplementario" value="<?php echo $codigoComplementario;?>"/>
		<input type="hidden" name="codigoSuplementario" value="<?php echo $codigoSuplementario;?>"/>
						
		<fieldset>
			<legend>Presentación</legend>	
			
				<div data-linea="1">
					<label>Presentación: </label>
						<input name="presentacion" id="presentacion" type="text"  required="required"/>
				</div>
				
				<div data-linea="2">
					<label>Unidad: </label>
					<select id="idUnidad" name="idUnidad" required>
						<option value="" selected="selected">Unidad....</option>
						<?php 
							for($i=0;$i<count($unidad);$i++)
								echo '<option value="' . $unidad[$i]['identificador'] . '" data-codigo="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['nombre'] .'</option>';
						?>
					</select>
					
					<input type="hidden" id="unidad" name="unidad" >
					<input type="hidden" id="codigoUnidad" name="codigoUnidad" >
				</div>
				
				<div data-linea="3">
					<button type="submit" class="mas">Añadir</button>
				</div>
				
		</fieldset>
	</form>
		
	<fieldset>
		<legend>Presentación ingresada</legend>
			<table id="presentacionTabla">
				<?php 
				    while ($presentacion = pg_fetch_assoc($presentaciones)){
				        echo $cr->imprimirLineaPresentacionPlaguicida($idProducto, $presentacion['id_presentacion'], $presentacion['presentacion'], $presentacion['unidad'], $presentacion['codigo_presentacion'], $presentacion['estado'], 'registroProducto');
					}
				?>
			</table>									
	</fieldset>			
	

<script type="text/javascript">
	$('document').ready(function(){
		actualizarBotonesOrdenamiento();
		acciones("#nuevaPresentacion","#presentacionTabla");

		distribuirLineas();
		construirValidador();		   
 	});

	$('#idUnidad').change(function(){
		if($("#idUnidad option:selected").val() != ""){
			$("#unidad").val($("#idUnidad option:selected").text());
			$("#codigoUnidad").val($("#idUnidad option:selected").attr('data-codigo'));
		}else{
			$("#unidad").val("");
			$("#codigoUnidad").val("");
		}
	});

</script>