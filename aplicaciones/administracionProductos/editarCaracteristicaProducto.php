<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cac = new ControladorAdministrarCaracteristicas();
$cat = new ControladorAdministrarCatalogos();
$idProducto= $_POST['idCatalogo'];
$idElemento= $_POST['idElemento'];
$usuario=$_SESSION['usuario'];
$elemento=pg_fetch_assoc($cac->obtenerCaracteristicaXID($conexion, $idElemento));
?>

<header>
	<h1>Modificar Parámetro</h1>
</header>

<div id="estado"></div>
<form id="regresar" data-rutaAplicacion="administracionProductos" data-opcion="abrirProductoCaracteristicas" data-destino="detalleItem">
	<input type="hidden" name="id" value="<?php echo $idProducto;?>"/>	
	<button class="regresar">Regresar a Características del Producto</button>
</form>

<form id="frmCaracteristica" data-rutaAplicacion="administracionProductos" data-opcion="actualizarItem" >	
	<input type="hidden" id="idItem" name="idItem" value="<?php echo $idElemento;?>"/>
	<input type="hidden" id="opcion" name="opcion"/>
	<input type="hidden" id="idFormulario" name="idFormulario" value="<?php echo $elemento['id_formulario'];?>"/>
	<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $elemento['id_producto'];?>"/>
	<fieldset>
		<?php
		$res=$cc->obtenerTipoSubtipoProductoOperacionMasivo($conexion, $idProducto);
		$fila=pg_fetch_assoc($res);
		switch ($fila['id_area']){
			case'SV':
				$area="Sanidad Vegetal";
			break;
			
			case'SA':
				$area="Sanidad Animal";
			break;
			
			case'LT':
				$area="Laboratorios";
			break;
			
			case'AI':
				$area="Inocuidad de los Alimentos";
			break;
		}
		?>
		<legend>Información del Producto</legend>
		<div data-linea="1">
			<label>Área:</label>
			<input type="text" value="<?php echo $area?>" disabled>
		</div>
		<div data-linea="2" id="resultadoTipoProducto">
			<label>Tipo Producto:</label>
			<input type="text" value="<?php echo $fila['nombretipoproducto']?>" disabled>
		</div>
		<div data-linea="3" id="resultadoSubTipoProducto">
			<label>Subtipo Producto:</label>
			<input type="text" value="<?php echo $fila['nombresubtipoproducto']?>" disabled>
		</div>
		<div data-linea="4" id="resultadoProducto">
			<label>Producto:</label>
			<input type="text" value="<?php echo $fila['nombre_comun']?>" disabled>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Parámetros</legend>
		<div data-linea="1" >
		<label for="cbFormulario">Formulario:</label>
			<input type="text" id="txtEtiqueta" name="txtEtiqueta" value="<?php echo $elemento['nombre_formulario'];?>" disabled>
		</div>
		<div data-linea="3" >
			<label for="txtEtiqueta">Etiqueta</label>
			<input type="text" id="txtEtiqueta" name="txtEtiqueta" value="<?php echo $elemento['etiqueta'];?>" >
		</div>
		<div data-linea="4" >
			<label for="cbTipoElemento">Tipo Elemento:</label>
			<select id="cbTipoElemento" name="cbTipoElemento">
					<option value="">Seleccione....</option>
					<option value="CB">ComboBox</option>					
			</select>
		</div>
		<div data-linea="5" >
		<label for="cbCatalogo">Seleccionar Catálogo:</label>
			<select id="cbCatalogo" name="cbCatalogo">
						<option value="">Seleccione....</option>
						<?php 
						$res=$cat->listarCatalogos($conexion,'',1);
							while ($fila=pg_fetch_assoc($res)){
								echo '<option value="'.$fila['id_catalogo_negocios'].'">'.$fila['nombre'].'</option>';								
							}
						?>
			</select>
		</div>
		<div data-linea="6">
			<table id="tbItems" style="width:100%; text-align:center;">
				<thead>
					<tr>
						<th style="width: 10%;">#</th>
						<th style="width: 90%;">Ítem</th>						
					</tr>
				</thead>
				<tbody id="cuerpoItems">
				<?php 
				
				$res=$cat->listarItems($conexion, $elemento['id_catalogo_negocios']);
				while($fila=pg_fetch_assoc($res)){
					$con+=1;
					echo'<tr>
					<td>'.$con.'</td><td>'.'<input type="hidden" id="dtxtItem" name="dtxtItem[]" value="'.$fila['id_item'].'">'.$fila['nombre'].'</td>
					</tr>';
				}
				
				?>
				</tbody>
			</table>
		</div>	
	</fieldset>
	<button id="btnAgregarItem" class="guardar">Actualizar</button>
	
</form>

<script type="text/javascript">

$("document").ready(function(event){
	distribuirLineas();
	acciones("#abrirCaracteristica");
	cargarValorDefecto("cbTipoElemento","<?php echo $elemento['tipo'];?>");
	cargarValorDefecto("cbCatalogo","<?php echo $elemento['id_catalogo_negocios'];?>");	
});


$("#cbCatalogo").change(function(event){
	event.preventDefault();	
	if($.trim($("#cbCatalogo").val())!=""){
    	$('#frmCaracteristica').attr('data-opcion','comboEditarCaracteristicas');
    	$('#frmCaracteristica').attr('data-destino','cuerpoItems');
    	$('#opcion').val('items');
    	abrir($("#frmCaracteristica"),event,false);
	} else{
		$("#cuerpoItems").html("");
	}
});


$("#frmCaracteristica").submit(function(event){

	event.preventDefault();

	var error = false;
	if($("#cbCatalogo").val()=="" || $("#cbCatalogo").length == 0){
		error=true;
		$("#cbCatalogo").addClass("alertaCombo");
	} 

	if(!error){
    	$("#frmCaracteristica").attr('data-destino','abrirProductoCaracteristicas');
    	$("#frmCaracteristica").attr('data-opcion', 'actualizarCaracteristica');	
    	ejecutarJson($("#frmCaracteristica"));
	} else{
		$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
	}
});

	
</script>
