<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];

$datos=explode('-', $_POST['id']);
$idOperacion = $datos[0];

$qOperacion = $cro->abrirOperacionXid($conexion, $idOperacion);
$operacion = pg_fetch_assoc($qOperacion);

$qOperacionesXidTipoOperacion = $cro->obtenerOperacionesXIdOperadorTipoOperacionXHistorialOperacion($conexion, $operacion['id_operador_tipo_operacion'], $operacion['id_historial_operacion']);

while($operacionesTipoOperacion = pg_fetch_assoc($qOperacionesXidTipoOperacion)){
    $idOperaciones .= $operacionesTipoOperacion['id_operacion'].', ';  
}

$idOperaciones = trim($idOperaciones,', ');

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>

<header>
	<h1>Registro de proveedores orgánicos</h1>
</header>
<div id="estado"></div>
    <form id="nuevoProveedorOrganico" data-rutaAplicacion="registroOperador" data-opcion="agregarNuevoProveedorOrganico" >
    	
    	<input type="hidden"name="identificadorOperador" value="<?php echo $identificador;?>" />
    	<input type="hidden" id="opcion" name="opcion" />
 		<input type="hidden" id="nombreProducto" name="nombreProducto" />
    	<input type="hidden" id="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" />
    	<input type="hidden" id="idTipoTransicion" name="idTipoTransicion" />
    	<input type="hidden" id="idTipoOperacion" name="idTipoOperacion" value="<?php echo $operacion['id_tipo_operacion'];?>" />
    	<input type="hidden" id="idOperadorTipoOperacion" name="idOperadorTipoOperacion" value="<?php echo $operacion['id_operador_tipo_operacion'];?>" />
	       
    		<fieldset>
    			<legend>Información básica</legend>
    				<p class="nota">Ingrese la información de su proveedor. Seleccione el producto que la persona le ofrece para su actividad.</p>
    				
    			<div data-linea="1">	
    				<input type="checkbox" name="importador" id="importador" value="importador"><label>Importador</label>
    			</div>
    			<hr/>	
    			<div data-linea="2" id="resutadoImportador">
					<label>CI/RUC Proveedor: </label>
					<input type="text" id="identificadorProveedor" name="identificadorProveedor" placeholder="Ej: 1815161213" data-er="^[0-9]+$" />
    			</div>
    			<div data-linea="3" id="resultadoIdentificadorProveedor">
        			<label>Tipo de producto: </label>
    				<select id="tipoProducto" name="tipoProducto" >
    				<option value="" selected="selected" >Seleccione....</option>
    				</select>
    			</div>		
    			<div data-linea="4" id="resultadoTipoProducto">
    				<label>Subtipo de producto: </label>
    				<select id="subtipoProducto" name="subtipoProducto" >
    				<option value="" selected="selected" >Seleccione....</option>
    				</select>
    			</div>			
    			<div data-linea="5" id="resultadoSubtipoProducto">
    				<label>Producto: </label>
    				<select id="producto" name="producto" >
    				<option value="" selected="selected" >Seleccione....</option>
    				</select>
    			</div>	
    			<div data-linea="6" id="resultadoProducto">
    			</div>	
    			<div data-linea="7" id="resultadoTransicion">
    			</div>	
    			<button type="submit" class="mas">Agregar proveedor</button>
    		</fieldset>
    				
    </form>
	<fieldset>
		<legend>Productos declarados</legend>
		
		<table id="proveedoresDeclarados" width="100%" >
			<thead>
				<tr>
					<th>Nombre proveedor</th><th>Producto</th><th>Status</th><th>Opciones</th>
				<tr>
			</thead> 
			<tbody>
    			<?php 
    			$productosProveedores = $cro->listarProveedoresOperadorXIdOperadorTipoOperacion($conexion, $identificador, $operacion['id_operador_tipo_operacion']);
        			
                    while ($producto = pg_fetch_assoc($productosProveedores)){
                        
                        if($producto['codigo_proveedor'] == ""){
                            $nombreProveedor = $producto['nombre_exportador'];
                        }else{
                            $qProveedor = $cro->obtenerDatosOperador($conexion, $producto['codigo_proveedor']);
                            $proveedor = pg_fetch_assoc($qProveedor);
                            if($proveedor['razon_social'] != "" || $proveedor['razon_social'] != ""){                                
                                $nombreProveedor = $proveedor['razon_social'];
                            }else{
                                $nombreProveedor = $proveedor['nombre_representante'] . ' ' . $proveedor['apellido_representante'];
                            }
                            
                        }                        
                        
                        $nombreTipoTransicion = pg_fetch_result($cc->obtenerTipoTransicionXIdTipoTransicion($conexion, $producto['id_tipo_transicion']), 0, 'nombre_tipo_transicion');
                         
                        echo $cro->imprimirProductosProveedoresOrganicos($producto['id_proveedor'], $nombreProveedor, $producto['nombre_producto'], $nombreTipoTransicion, $operacion['id_operador_tipo_operacion'], $operacion['id_tipo_operacion']);
        			}
    			?>
			</tbody>
		</table>
	</fieldset>
</body>

<form id="enviarProveedorOrganico" data-rutaAplicacion="registroOperador" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idOperacion" name="idOperacion" value=" <?php echo $idOperacion;?>"/>
	<button type="submit" class="guardar">Guardar proveedores</button>
</form>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
		$("#idTipoTransicionProducto").hide();
	});

	acciones("#nuevoProveedorOrganico","#proveedoresDeclarados", null, null, new exitoDeclararProveedor(), null, null, new verificarInputsDeclararProveedor());
		
	$("#importador").change(function(event){

		$("#nombreProducto").val("");
		$("#idTipoTransicion").val("");
		$("#tipoProducto").empty();
		$("#tipoProducto").append('<option selected="selected" value="">Seleccione...</option>');
		$("#subtipoProducto").empty();
		$("#subtipoProducto").append('<option selected="selected" value="">Seleccione...</option>');
		$('#producto').empty();
		$('#producto').append('<option selected="selected" value="">Seleccione...</option>');
		event.preventDefault();
		event.stopImmediatePropagation();
		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
	    $("#nuevoProveedorOrganico").attr('data-destino', 'resutadoImportador');
	    if($('#importador').prop('checked')){
	    	$("#opcion").val('esImportador');
	    }else{
	    	$("#opcion").val('noImportador');
	    	$("#resultadoProducto").hide();
	    	$("#resultadoTransicion").hide();
		}
	    abrir($("#nuevoProveedorOrganico"), event, false);	    
	});

	$("#identificadorProveedor").change(function(event){
		$("#subtipoProducto").empty();
		$("#subtipoProducto").append('<option selected="selected" value="">Seleccione...</option>');
		$('#producto').empty();
		$('#producto').append('<option selected="selected" value="">Seleccione...</option>');
		event.preventDefault();
		event.stopImmediatePropagation();
 		$("#nuevoProveedorOrganico").attr('data-destino','resultadoIdentificadorProveedor');
 		$("#nuevoProveedorOrganico").attr('data-opcion', 'combosProveedorOrganico');
 		$("#opcion").val('verificarProveedor');
 	 	abrir($("#nuevoProveedorOrganico"),event,false);
	});

	function verificarInputsDeclararProveedor() {
		this.ejecutar = function () {
			var error = false;
			$(".alertaCombo").removeClass("alertaCombo");
			
			if ($("#identificadorProveedor").val() == "") {
				$("#identificadorProveedor").addClass("alertaCombo");
				error = true;
			}

			if ($("#tipoProducto").val() == "") {
				$("#tipoProducto").addClass("alertaCombo");
				error = true;
			}

			if ($("#subtipoProducto").val() == "") {
				$("#subtipoProducto").addClass("alertaCombo");
				error = true;
			}

			if ($("#producto").val() == "") {
				$("#producto").addClass("alertaCombo");
				error = true;
			}

			if($('#importador').prop('checked')){
				if ($("#idTipoTransicionProducto").val() == "") {
					$("#idTipoTransicionProducto").addClass("alertaCombo");
					error = true;	
				}					
			}
			
			return !error;		
		};
	
		this.mensajeError = function () {
			mostrarMensaje("Llene todos los datos del formulario", "FALLO");
		}
	}
	
	function exitoDeclararProveedor() {
		this.ejecutar = function (msg) {
			mostrarMensaje("Nuevo registro agregado", "EXITO");
			var fila = msg.mensaje;
			$("#proveedoresDeclarados").append(fila);
		};
	}
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#enviarProveedorOrganico").submit(function(event){
		event.preventDefault();		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($('#proveedoresDeclarados tbody tr').length == 0){
			error = true;
		}		
		
		if (error){
			$("#estado").html("Por favor ingrese por lo menos un proveedor.").addClass('alerta');
		}else{
			$("#enviarProveedorOrganico").attr('data-opcion', 'guardarProveedorOrganico');
		    $("#enviarProveedorOrganico").attr('data-destino', 'detalleItem');		    
			ejecutarJson($(this));
		}
	});
	
</script>
</html>