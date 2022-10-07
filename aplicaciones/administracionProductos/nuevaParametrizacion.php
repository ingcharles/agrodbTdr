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
			<label for="cbAreaRequrida">Requiere Incluir Tipo de Área:</label>
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
		<div data-linea="3" >
			<label for="cbAreasPorProveedor">Cuántas Áreas por Proveedor:</label>
			<select id="cbAreasPorProveedor" name="cbAreasPorProveedor">
					<option value="">Seleccione....</option>
					<option value="1">Una</option>
					<option value="2">Varias</option>
					<option value="3">Ninguna</option>
			</select>
		</div>	
				
		<div id="resultadoOperaciones">
			<?php	
			$resultado=$cl->listarOperacionesTrazabilidad($conexion, 'SV', 't');
			
			echo '<br><label>Seleccione uno o varios Tipos de Operaciones</label>
    
					<div class="seleccionTemporal">
						<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
				    	<label for="cTemporal">Seleccionar todos </label>
					</div>
    
				<hr>
			 <div id="contenedorProducto"><table style="border-collapse: initial;"><tr>';
			$agregarDiv = 0;
			$cantidadLinea = 0;
			while ($fila = pg_fetch_assoc($resultado)){
			    
			    echo '<td style="text-align:left;"><input id="'.$fila['id_tipo_operacion'].'" type="checkbox" class="productoActivar" data-resetear="no" value="'.$fila['id_tipo_operacion'].'" />
        			 	<label for="'.$fila['id_tipo_operacion'].'">'.$fila['nombre'].'</label>
                        <input type="hidden" name="codigoOperacion[]" value="'.$fila['codigo'].'"></td>';
			    $agregarDiv++;
			    
			    if(($agregarDiv % 2) == 0){
			        echo '</tr><tr>';
			        $cantidadLinea++;
			    }
			    
			    if($cantidadLinea == 9){
			        echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
			    }
			}
			echo '</tr></table></div>
             <button type="button"  onclick="agregarFilas();return false;" class="mas">Agregar Item</button>';
			    			
		  ?>
		</div>
	
	</fieldset>
	
	<div id="contenedorTablaOeraciones">
    	<fieldset>
    	<legend>Registro de Áreas incluidas</legend>
    		<table style="width: 100%" id="tablaOperaciones">
    			<thead>
    				<tr>
    			 		<th>Nro</th>
    			 		<th>Tipo de operación</th>
    			 		<th>Eliminar</th>
    				</tr>
    			</thead>
    			<tbody id="bodyOperaciones">
    			</tbody>
    		</table>
    	</fieldset>
	</div>

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

		
		if($("#tablaOperaciones tbody tr").length==0){			
			mostrarMensaje("Agregue al menos un Tipo de Operación","FALLO");			
			return false;		
		}				
		
		
    	$("#nuevoParametro").attr('data-destino', 'detalleItem');
        $("#nuevoParametro").attr('data-opcion', 'guardarNuevoParametro');        
        ejecutarJson($(this));
        
	} else{
		mostrarMensaje("Por favor revise los campos obligatorios","FALLO");
	}
		
});

var rDuplicado=false;
function agregarFilas(){

	if($('#contenedorProducto input:checkbox:checked').length < 1){
		$("#estado").html("Seleccione uno o más Tipos de Operación").addClass("alerta");
	}
	
	else{
		$("#estado").html("");
	}
	
	$('#contenedorProducto input:checkbox:checked').each(function(){
		verificarRegistro($(this).val());		
		var rd=$(this).parent().find('input[name="codigoOperacion[]"]').val();		
		if(!rDuplicado){    			
        	var cadena = '<tr><td></td><td>'+$(this).next('label').text()+'<input type="hidden" id="codigo" name="codigo[]" value="'+rd+'">'+
        	'<input type="hidden" id="idOperacion" name="idOperacion[]" value="'+$(this).val()+'">'+
            '</td><td class="borrar"><button class="icono" onclick="delFilaActual(this);return false"></button></td></tr>';    			    
        	$("#tablaOperaciones tbody").append(cadena);
		}
	
	});

	enumerar();
	return false;		
}


function verificarRegistro(produ){
	$('#tablaOperaciones tbody tr').each(function (rows) {		
		var rd= $(this).find('td').eq(1).find('input[id="idOperacion"]').val();
		filas=$('#tablaOperaciones tbody tr').length;
		if (filas>0){
			if(rd == produ){
				rDuplicado=true;
		    	return false;
		    } else{
		    	rDuplicado=false;		    			    		
		    }			        
		}	    
	});
}

function enumerar(){			    	    
    var tabla = document.getElementById('tablaOperaciones');
    con=0;   
    $("#tablaOperaciones tbody tr").each(function(row){        
    	con+=1;    	
    	$(this).find('td').eq(0).html(con);    	  	
    });
}

function delFilaActual(r){
	var i = r.parentNode.parentNode.rowIndex;		    	    
    var table = document.getElementById('tablaOperaciones');
    table.deleteRow(i);

    var filas = table.rows.length;
  
	enumerar();
}

$("#cTemporal").click(function(e){
	if($('#cTemporal').is(':checked')){
		$('.productoActivar').prop('checked', true);
	}else{
		$('.productoActivar').prop('checked', false);		
	}
});
	   
</script>
