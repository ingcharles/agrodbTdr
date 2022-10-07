<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idProducto = $_POST['idProducto'];
	$idPartida = $_POST['idPartida'];
	$partidaArancelaria = $_POST['partidaArancelaria'];
	$areaProducto = $_POST['areaProducto'];

	$conexion = new Conexion();	
	$cr = new ControladorRequisitos();
	$cc = new ControladorCatalogos();
	
	$partida = pg_fetch_assoc($cr->buscarPartidaArancelariaXProductoPlaguicida($conexion, $idPartida));
	$codigos = $cr->buscarCodigoCompSuplXproductoPartidaPlaguicida($conexion,$idPartida);
?>

<body>
	<header>
		<h1>Detalle de partida arancelaria</h1>
	</header>
	
	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirProductoPlaguicida" data-destino="detalleItem">
		<input type="hidden" name="idProducto" value="<?php echo $idProducto;?>"/>
		<input type="hidden" name="areaProducto" value="<?php echo $areaProducto;?>"/>
		<input type="hidden" name="numeroPestania" value="2"/>
		<button class="regresar">Regresar a Producto Plaguicida</button>
	</form>
	
	<fieldset>
		<legend>Partida Arancelaria</legend>
			
			<div data-linea="1">
				<label>Partida: </label> <?php echo $partida['partida_arancelaria'];?>
			</div>
			
			<div data-linea="1">
				<label>Código producto: </label> <?php echo $partida['codigo_producto'];?>
			</div>
		
	</fieldset>
	
	<form id="nuevoCodigoSC" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoCodigoCSPlaguicida" >
		<input type="hidden" id="idPartida" name="idPartida" value="<?php echo $idPartida;?>"/>
		<input type="hidden" name="idProducto" value="<?php echo $idProducto;?>"/>
		<input type="hidden" name="partidaArancelaria" value="<?php echo $partidaArancelaria;?>"/>
		<input type="hidden" name="areaProducto" value="<?php echo $areaProducto;?>"/>
						
		<fieldset>
			<legend>Código complementario y suplementario</legend>
	
    			<div data-linea="2">
    				<label>Código complementario</label>
    				<select id="codigoComplementario" name="codigoComplementario" required>
    					<option value="0000">0000</option>
    				</select>
    			</div>
    			
    			<div data-linea="2">
    				<label>Código suplementario</label>
    				<select id="codigoSuplementario" name="codigoSuplementario" required>
    					<option value="0000">0000</option>
    					<option value="0001">0001</option>
    					<option value="0002">0002</option>
    					<option value="0003">0003</option>
    					<option value="0004">0004</option>
    					<option value="0005">0005</option>
    					<option value="0006">0006</option>
    					<option value="0007">0007</option>
    					<option value="0008">0008</option>
    					<option value="0009">0009</option>
    					<option value="0010">0010</option>
    					<option value="0011">0011</option>
    					<option value="0012">0012</option>
    					<option value="0013">0013</option>
    					<option value="0014">0014</option>
    					<option value="0015">0015</option>
    					<option value="0016">0016</option>
    					<option value="0017">0017</option>
    					<option value="0018">0018</option>
    					<option value="0019">0019</option>
    					<option value="0020">0020</option>
    				</select>
    			</div>
    			
    			<div data-linea="4">
    				<button type="submit" class="mas">Añadir</button>
    			</div>
		</fieldset>				
	</form>
	
	<fieldset>	
		<legend>Códigos ingresados</legend>
			<table id="codigoSC">
					<?php 
					   while ($codigoAdicionales = pg_fetch_assoc($codigos)){
					       echo $cr->imprimirCodigoCompSuplPlaguicida($codigoAdicionales['id_codigo_comp_supl'], $idProducto, $idPartida, $partidaArancelaria, $codigoAdicionales['codigo_complementario'], $codigoAdicionales['codigo_suplementario'], $areaProducto, $codigoAdicionales['estado'], 'registroProducto');
					   }
					?>
			</table>
	</fieldset>			
	

<script type="text/javascript">

	$('document').ready(function(){
		actualizarBotonesOrdenamiento();
		acciones("#nuevoCodigoSC","#codigoSC");

	    distribuirLineas();
		construirValidador();		   
 	});
	 
</script>