<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$cc = new ControladorCatalogos();
$ca = new ControladorAdministrarCaracteristicas();
$operador = $_SESSION['usuario'];
?>

<header>
	<h1>Nuevo Registro de Ingreso</h1>
</header>

<div id="estado"></div>

<form id="nuevoProductoProveedor" data-rutaAplicacion="conformacionLotes" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="usuario" value=<?php echo $operador;?> name="usuario">
	<fieldset>
		<legend>Ingreso de Proveedores</legend>
		<div data-linea="1">
			<label for="codigoIngreso">Código de ingreso: </label> 
			<?php 
				$codigo= $cl->autogenerarNumeroRegistro($conexion,$operador);				
				$ncodigo = $codigo;
				$formato=str_pad($ncodigo, 11, "0", STR_PAD_LEFT);				
			?>
			<input type="text" id="codigoIngreso" name="codigoIngreso" value=<?php echo $formato ?> disabled="disabled" readOnly>
		</div>
		
		<div data-linea="1">
		   <?php		   
		   $fecha_registro = date('Y-m-d');	
		   ?>
			<label for="fechaIngresos">Fecha de ingreso: </label>
			<input type="text" id="fechaIngresos" name="fechaIngresos" disabled="disabled" value="<?php echo $fecha_registro;?>"  readOnly>
		</div>
		
		<div data-linea="2">
			<label for="productos">Producto: </label>
			<select id="productos" name="productos">
					<option value="">Seleccione....</option>
					<?php 
					$res= $cl->listarProductosTrazabilidadTodos($conexion);
					
					while ($produFila = pg_fetch_assoc($res)){					    
				        echo '<option value="' . $produFila['id_producto'] . '">' . $produFila['nombre_comun'].'</option>';
				    }	
					
					?>					
			</select>						
			<input type="hidden" id="nproducto" name="nproducto"/>
		</div>				
		<div id="productoFlujo" style="width:100%">
    		<div data-linea="4" id="resultadoProveedor" >
    			<label for="proveedores">Nombre del Proveedor: </label>
    			<select id="proveedores" name="proveedores" disabled>
    					<option value="">Seleccione....</option>
    			</select>
    			<input type="hidden" id="nproveedor" name="nproveedor"/>			
    		</div>
		</div>
		
		<div data-linea="7" id="resultadoSitioProveedor" >
			
		</div>
		
		<div data-linea="8"> 
			<label for="identificacionProveedor">Identificación Proveedor: </label>
			<input type="text" id="identificacionProveedor" name="identificacionProveedor" disabled="disabled" readOnly>
		</div>
		
		
		<div data-linea='9'>
			<label for=cantidad>Cant. a Registrar: </label>	
		  	<input type=text id=cantidad name=cantidad data-er="/^[0-9]+$/">
        </div>";
		
		<div data-linea='9'>
			<label for=unidad>Unidad: </label>
			<select id=unidad name=unidad>
			<option value=>Seleccione....</option>
			<?php 
			$res= $cc->listarUnidadesMedida($conexion);
			while($fila=pg_fetch_assoc($res)){
				echo '<option value="' . $fila['id_unidad_medida'] . '" >'. $fila['nombre'] .'</option>';
			}
			?>
			</select>
		  <div id="resultadoUnidad"></div>
		 </div>
		
		  
		<div id="resultadoCantidad" style="width:100%;">
			
		</div>
		<div id="mensajeError" style="width:100%; margin-top:10px; text-align: center;"></div>
		
	</fieldset>
	
	<div id="resultadoCaracteristica">
	
	</div>
	
	<?php
	
	$nombre = basename($_SERVER['PHP_SELF']);
	$nombre = explode(".", $nombre);
	$archivo = $nombre[0];
	
	$res=$ca->obtenerFormulario($conexion, $archivo);
	if(pg_fetch_row($res)>0){
	    $fila=pg_fetch_assoc($res);	    
	    /*$res=$cl->obtenerCaracteristica($conexion, $idProducto, $fila['id_formulario']);
	    if(pg_fetch_row($res)>0){
	    
	    }*/
	}
	?>

	<button type="submit" class="guardar" id="guardarRegistro">Guardar Ingreso</button>
	
	
</form>
<script type="text/javascript">

$("document").ready(function(){
	var fecha = new Date();
	var dd=("00" + fecha.getDate()).slice (-2); 
	var mm=("00" + (fecha.getMonth()+1)).slice (-2); 
	var yy=fecha.getFullYear();	
	//$("#fechaIngresos").val(yy+"-"+mm+"-"+dd);	
	$("#resultadoSitioProveedor").hide();
	distribuirLineas();
	var total=$('#productos option').length;	
	if(total==1){
		$("#estado").html("Debe poseer productos con Trazabilidad, Operación en Centro de acopio y Operación de Comercio Exterior para hacer uso del Módulo.").addClass("alerta");
		$("#guardarRegistro").attr("disabled",true);
		$("#productos").attr("disabled",true);
		desactivar();
	}
	desactivar();
	
});


$("#productos").change(function(event){

	$("#cantidad").val("");

	if($.trim($("#productos").val())!=""){

		
		$("#variedad").attr('disabled',true);
		$("#cantidad").attr('disabled',true);
		$("#unidad").attr('disabled',true);
		
		$("#estado").html("");
		$("#nproducto").val($("#productos option:selected").text());

		$("#nuevoProductoProveedor").attr('data-destino', 'productoFlujo');
        $("#nuevoProductoProveedor").attr('data-opcion', 'comboProveedor');
        $("#opcion").val('proveedor');
       	abrir($("#nuevoProductoProveedor"), event, false);	

       	$("#nuevoProductoProveedor").attr('data-destino', 'resultadoCaracteristica');
        $("#nuevoProductoProveedor").attr('data-opcion', 'comboProveedor');
        $("#opcion").val('caracteristica');
       	abrir($("#nuevoProductoProveedor"), event, false);

       	$("#identificacionProveedor").val("");
       	activar();
       	
	} else{
		$('#proveedores')
	    .find('option')
	    .remove()
	    .end()
	    .append('<option value="">Seleccione....</option>')
	    .val('')
	    .attr("disabled","disabled");

		desactivar();
		$("#estado").html("");
		$("#areaProveedor").val("");
		$("#nAreaProveedor").val("");
		$("#resultadoSitioProveedor").hide();
	
	}

	$("#variedad").attr('disabled',true);
});

$("#unidad").change(function(event){	
	$("#nuevoProductoProveedor").attr('data-destino', 'resultadoUnidad');
    $("#nuevoProductoProveedor").attr('data-opcion', 'comboProveedor');
	$("#opcion").val("unidad");
	abrir($("#nuevoProductoProveedor"), event, false);	
});

function desactivar(){	
	$("#identificacionProveedor").val("");
	$("#variedad").val("").attr('disabled',true);
	$("#cantidad").val("").attr('disabled',true);
	$("#unidad").val("").attr('disabled',true);	
}

function activar(){	
	$("#variedad").removeAttr('disabled');
	$("#cantidad").removeAttr('disabled');
	$("#unidad").removeAttr('disabled');
}

$("#nuevoProductoProveedor").submit(function(e) {
    e.preventDefault();
    $(".alertaCombo").removeClass("alertaCombo");
    var error = false;
    if ($.trim($("#detalleItem #productos").val()) == "" ) {
        error = true;
        $("#detalleItem #productos").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
    }
    if ($.trim($("#detalleItem #proveedores").val()) == "" ) {
        error = true;
        $("#detalleItem #proveedores").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
    }
    /*****************************************/
    
    var str =$("#nproducto").val();
	var producto;
	var index;	
	    

    if ($.trim($("#detalleItem #unidad").val()) == "" ) {
        error = true;
        $("#detalleItem #unidad").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
    }

   
		if($("#CAreaProveedor").val()!="3"){
			if ($.trim($("#detalleItem #areaProveedor").val()) == "" ) {
		        error = true;
		        $("#detalleItem #areaProveedor").addClass("alertaCombo");
		        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
		    }
	        
		} else{
			$("#areaProveedor").val("");
			$("#nAreaProveedor").val("");
			$("#resultadoSitioProveedor").hide();
		}
	
    if ($.trim($("#detalleItem #cantidad").val()) == "" ) {
        error = true;
        $("#detalleItem #cantidad").addClass("alertaCombo");
        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
    } else{
    	valor = $("#detalleItem #cantidad").val();        

        if (valor <= 0) {
            error = true;
            $("#estado").html("La cantidad a registrar debe ser mayor a 0").addClass('alerta');
        } 

        if (valor > 999999.99) {
            error = true;
            $("#estado").html("La cantidad a registrar no puede ser mayor a 999999.99").addClass('alerta');
        }

        if (isNaN (valor)) {
            error = true;
            $("#estado").html("La cantidad a registrar debe ser un valor numérico sin caracteres ni letras.").addClass('alerta');
            $("#cantidad").addClass("alertaCombo");
        }
    }

    if (!error){
    	$("#opcion").val("nuevo");
        $("#identificacionProveedor").removeAttr("disabled","disabled");
        $("#codigoIngreso").removeAttr("disabled","disabled");        
    	$("#nuevoProductoProveedor").attr('data-destino', 'detalleItem');    	
        $("#nuevoProductoProveedor").attr('data-opcion', 'guardarNuevoRegistro');
        ejecutarJson($(this));
    }
});

	
</script>
