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
?>

<header>
	<h1>Nueva Parametrización Conformación de Lotes</h1>
</header>

<div id="estado"></div>

<form id="nuevoParametro" data-rutaAplicacion="administracionProductos" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="usuario" value=<?php echo $usuario;?> name="usuario">
	<fieldset>
		<legend>Información del Producto</legend>
		<div data-linea="1">
				<label for="cbArea">Área:</label>
				<select id="cbArea" name="cbArea">
						<option value="">Seleccione....</option>
						<option value="SA">Sanidad Animal</option>
						<option value="SV">Sanidad Vegetal</option>
						<option value="LT">Laboratorios</option>
						<option value="AI">Inocuidad de los alimentos</option>
				</select>
		</div>
		<div data-linea="2" id="resultadoTipoProducto">
		</div>
		<div data-linea="3" id="resultadoSubTipoProducto">
		</div>
		<div data-linea="4" id="resultadoProducto">
		</div>
	</fieldset>
	
	
	<fieldset>
		<legend>Parámetros para Conformación de Lote</legend>
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

	<button type="submit" class="guardar" id="btnGuardar" disabled="disabled">Guardar</button>
</form>
<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();
	
});


$("#cbArea").change(function(event){
	event.preventDefault();	
	if($.trim($("#cbArea").val())!=""){
    	$('#nuevoParametro').attr('data-opcion','comboParametro');
    	$('#nuevoParametro').attr('data-destino','resultadoTipoProducto');
    	$('#opcion').val('tipoProducto');
    	abrir($("#nuevoParametro"),event,false);	
    	$("#cbSubTipoProducto").html('<option value="">Seleccione....</option>');
    	$("#cbProducto").html('<option value="">Seleccione....</option>');	
    } else{
    	$("#cbTipoProducto").html('<option value="">Seleccione....</option>');
    	$("#cbSubTipoProducto").html('<option value="">Seleccione....</option>');
    	$("#cbProducto").html('<option value="">Seleccione....</option>');
    }
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
        $("#nuevoParametro").attr('data-opcion', 'guardarNuevoParametro');        
        ejecutarJson($(this));
        
	} else{
		mostrarMensaje("Por favor revise los campos obligatorios","FALLO");
	}
		
});

	
</script>
