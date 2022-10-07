<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$usuario = $_SESSION['usuario'];
$cl = new ControladorLotes();
$cat = new ControladorAdministrarCatalogos();
$idProducto= $_POST['id'];
$parametro=pg_fetch_assoc($cl->obtenerParametroxIDProducto($conexion, $idProducto));
?>

<header>
	<h1> Modificar Parametrización Conformación de Lotes</h1>
</header>

<div id="estado"></div>

<form id="nuevoParametro" data-rutaAplicacion="administracionProductos" >
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="usuario" value=<?php echo $usuario;?> name="usuario">
	<input type="hidden" name="idParametro" value="<?php echo $parametro['id_parametro']?>">
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
		<legend>Parámetros para Conformación de lote</legend>
		<div data-linea="1" >
			<label for="cbAreaRequrida">Requiere Áreas de Acopio:</label>
			<select id="cbAreaRequrida" name="cbAreaRequrida">
					<option value="">Seleccione....</option>
					<option value="1">Si</option>
					<option value="2">No</option>
			</select>
		</div>
		<div data-linea="2" >
			<label for="cbProveedores">Proveedores que Conforman el Lote:</label>
			<select id="cbProveedores" name="cbProveedores">
					<option value="">Seleccione....</option>
					<option value="1">Uno</option>
					<option value="2">Varios</option>
			</select>
		</div>
		<div data-linea="4" >
			<label for="cbAreasPorProveedor">Cuántas Áreas por Proveedor:</label>
			<select id="cbAreasPorProveedor" name="cbAreasPorProveedor">
					<option value="">Seleccione....</option>
					<option value="1">Una</option>
					<option value="2">Varias</option>
					<option value="3">Ninguna</option>
			</select>
		</div>	
	
	</fieldset>

	<button type="submit" class="guardar" id="btnGuardar">Actualizar</button>
</form>
<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();
	cargarValorDefecto("cbAreaRequrida","<?php echo $parametro['areas'];?>");
	cargarValorDefecto("cbProveedores","<?php echo  $parametro['proveedores'];?>");
	cargarValorDefecto("cbAreasPorProveedor","<?php echo  $parametro['areas_proveedor'];?>");
	
});


$("#cbArea").change(function(event){
	event.preventDefault();	
	$('#nuevoParametro').attr('data-opcion','comboParametro');
	$('#nuevoParametro').attr('data-destino','resultadoTipoProducto');
	$('#opcion').val('tipoProducto');
	abrir($("#nuevoParametro"),event,false);	
});

$("#cbCatalogo").change(function(event){
	event.preventDefault();	
	$('#nuevoParametro').attr('data-opcion','comboParametro');
	$('#nuevoParametro').attr('data-destino','cuerpoItems');
	$('#opcion').val('items');
	abrir($("#nuevoParametro"),event,false);	
});


$("#nuevoParametro").submit(function(event){
	event.preventDefault();	

	$(".alertaCombo").removeClass("alertaCombo");	
	var error = false;
	
	if($.trim($("#cbAreaRequrida").val())==""){
		error=true;
		$("#cbAreaRequrida").addClass("alertaCombo");		
	}	

	if($.trim($("#cbProveedores").val())==""){
		error=true;
		$("#cbProveedores").addClass("alertaCombo");				
	}

	if($.trim($("#cbAreasPorProveedor").val())==""){
		error=true;
		$("#cbAreasPorProveedor").addClass("alertaCombo");				
	}

	if(!error){
    	$("#nuevoParametro").attr('data-destino', 'detalleItem');
        $("#nuevoParametro").attr('data-opcion', 'actualizarParametro');
        //$("#frmCatalogo").attr('data-accionEnExito', 'ACTUALIZAR');    
        ejecutarJson($(this));
	} else{
		mostrarMensaje("Por favor revise los campos obligatorios","FALLO");
	}
});

	
</script>
