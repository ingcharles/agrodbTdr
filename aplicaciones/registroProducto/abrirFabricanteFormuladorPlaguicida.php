<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idFabricanteFormulador = $_POST['idFabricanteFormulador'];
	$idProducto = $_POST['idProducto'];
	$areaProducto = $_POST['area'];

	$conexion = new Conexion();	
	$cr = new ControladorRequisitos();
	$cc = new ControladorCatalogos();
	
	$fabForm = pg_fetch_assoc($cr->abrirFabricanteFormulador($conexion, $idFabricanteFormulador));
	$manufacturadores = $cr->buscarManufacturaforesXFabricanteFormulador($conexion,$idFabricanteFormulador);
?>

	<header>
		<h1>Detalle de Fabricante/Formulador</h1>
	</header>
	
	<div id="estado"></div>
	
	<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirProductoPlaguicida" data-destino="detalleItem">
		<input type="hidden" name="idProducto" value="<?php echo $idProducto;?>"/>
		<input type="hidden" name="areaProducto" value="<?php echo $areaProducto;?>"/>
		<input type="hidden" name="numeroPestania" value="3"/>
		<button class="regresar">Regresar a Detalle de producto plaguicida</button>
	</form>
	
	<fieldset>
		<legend><?php echo $fabForm['tipo'];?></legend>
			
			<div data-linea="1">
				<label><?php echo $fabForm['tipo'];?>: </label> <?php echo $fabForm['nombre'];?>
			</div>
			
			<div data-linea="2">
				<label>País origen: </label> <?php echo $fabForm['pais_origen'];?>
			</div>
		
	</fieldset>
	
	<form id="nuevoManufacturador" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoManufacturadorPlaguicida" >
		<input type="hidden" id="idFabricanteFormulador" name="idFabricanteFormulador" value="<?php echo $idFabricanteFormulador;?>">
		<input type="hidden" name="idProducto" value="<?php echo $idProducto;?>"/>
								
		<fieldset>
			<legend>Manufacturador</legend>	
			
				<div data-linea="3">
					<label>Manufacturador: </label>
						<input type="text" name="manufacturador" id="manufacturador" required="required"/>
				</div>
				
				<div data-linea="4">
					<label>País origen: </label>
					<select id="pais" name="pais" required>
							<option value="">País....</option>
							<?php 
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PAIS');
								
								foreach ($provincias as $provincia){
									echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>
						</select>
						
						<input type="hidden" name="nombrePais" id="nombrePais" />
				</div>
				
				<div data-linea="5">
					<button type="submit" class="mas">Añadir</button>
				</div>
				
		</fieldset>
	</form>
		
	<fieldset>
		<legend>Manufacturador ingresado</legend>
			<table id="manufacturadorTabla">
				<?php 
				    while ($manufacturador = pg_fetch_assoc($manufacturadores)){
				        echo $cr->imprimirLineaManufacturadorPlaguicida($idProducto, $manufacturador['id_manufacturador'], $manufacturador['manufacturador'], $manufacturador['pais_origen'], $manufacturador['estado'], 'registroProducto');
					}
				?>
			</table>									
	</fieldset>			
	

<script type="text/javascript">
	$('document').ready(function(){
		actualizarBotonesOrdenamiento();
		acciones("#nuevoManufacturador","#manufacturadorTabla");

		distribuirLineas();
		construirValidador();		   
 	});

	$('#pais').change(function(){
		if($("#pais option:selected").val() != ""){
			$("#nombrePais").val($("#pais option:selected").text());
		}else{
			$("#nombrePais").val("");
		}
	});
</script>