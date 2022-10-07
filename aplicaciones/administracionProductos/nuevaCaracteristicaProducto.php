<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$usuario = $_SESSION['usuario'];
$cac = new ControladorAdministrarCaracteristicas();
$cat = new ControladorAdministrarCatalogos();
?>

<header>
	<h1>Nuevas Características del Producto</h1>
</header>

<div id="estado"></div>

<form id="nuevaCaracteristica" data-rutaAplicacion="administracionProductos" data-accionEnExito="ACTUALIZAR">
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
		<legend>Parámetros</legend>
		<div data-linea="1" >
		<label for="cbFormulario">Formulario:</label>
			<select id="cbFormulario" name="cbFormulario">
						<option value="">Seleccione....</option>
						<?php 
						$res=$cac->listarFormualrios($conexion);
							while ($fila=pg_fetch_assoc($res)){
								echo '<option value="'.$fila['id_formulario'].'">'.$fila['nombre_formulario'].'</option>';								
							}
						?>
			</select>
		</div>		
		<div data-linea="3" >
			<label for="txtEtiqueta">Etiqueta</label>
			<input type="text" id="txtEtiqueta" name="txtEtiqueta">
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
				</tbody>
			</table>
		</div>	
	</fieldset>
	<button id="btnAgregarItem" class="mas" onclick="agregar();return false;" disabled="disabled"> Agregar</button>
	<fieldset>
		<div data-linea="1">
			<table id="tbCaracteristicas" style="width:100%; text-align:center;">
				<thead>
					<tr>
						<th >#</th>
						<th >Etiqueta</th>
						<th >Formulario</th>						
						<th>Catálogo</th>
						<th>Tipo Elemento</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody id="cuerpoItems">
				</tbody>
			</table>
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
    	$('#nuevaCaracteristica').attr('data-opcion','comboCaracteristicas');
    	$('#nuevaCaracteristica').attr('data-destino','resultadoTipoProducto');
    	$('#opcion').val('tipoProducto');
    	abrir($("#nuevaCaracteristica"),event,false);    	
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
	if($.trim($("#cbCatalogo").val())!=""){
    	$('#nuevaCaracteristica').attr('data-opcion','comboCaracteristicas');
    	$('#nuevaCaracteristica').attr('data-destino','cuerpoItems');
    	$('#opcion').val('items');
    	abrir($("#nuevaCaracteristica"),event,false);
	} else{
		$('#cuerpoItems').html("");		
	}
});


function agregar(){

	var producto = $("#cbProducto").val();
	var etiqueta = $("#txtEtiqueta").val();
	var catalogoValor = $("#cbCatalogo").val();
	var formulario = $("#cbFormulario").val();	
	var mensajeComprobacion; 
	
	$.ajax({
	    url: 'aplicaciones/administracionProductos/comprobarProductoCaracteristica.php',
	    method: 'post',
	    data: {producto: producto, etiqueta: etiqueta, catalogo: catalogoValor, formulario: formulario},
	    dataType: "json",
	    async: false,
	    success: function(msg){
		    	if(msg.estado=="exito"){
		    		mensajeComprobacion=msg.mensaje; 				
		    	}		  
		    },
		    error: function(jqXHR, textStatus, errorThrown){
		    	jsonContactos="vacio";
		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
		    }
	});

	if(mensajeComprobacion=="etiqueta"){
		$("#estado").html("La etiqueta ya se encuentra asignada para el producto y el formulario seleccionado").addClass("alerta");
		return false;
	} else if(mensajeComprobacion=="catalogo"){
		$("#estado").html("El catálogo ya se encuentra asignado para el producto y el formulario seleccionado").addClass("alerta");
		return false;
	} else if(mensajeComprobacion=="ambos"){
		$("#estado").html("La etiqueta y el catálogo ya se encuentran asignadas para el producto y el formulario seleccionado").addClass("alerta");
		return false;
	} else{
		$("#estado").html("");
	}
	
	
	
	$(".alertaCombo").removeClass("alertaCombo");
	
	var error = false;
	var error2 = false;
	var dulpicado = false;	
	
	var etiqueta = $("#txtEtiqueta").val();
	var formulario = $("#cbFormulario option:selected").text();
	var catalogo = $("#cbCatalogo option:selected").text();
	var tipoElemento = $("#cbTipoElemento option:selected").text();

	var dFormulario = $("#cbFormulario").val();
	var dCatalogo = $("#cbCatalogo").val();
	var dtipoElemento = $("#cbTipoElemento").val();
	var dProducto = $("#cbProducto").val();
	
	var con=0;

	$(".alertaCombo").removeClass("alertaCombo");
	error=false;
	if($("#cbFormulario").val()==""){
		error=true;
		$("#cbFormulario").addClass("alertaCombo");
	}
	
	if($("#txtEtiqueta").val()==""){
		error=true;
		$("#txtEtiqueta").addClass("alertaCombo");
	}
	if($("#cbTipoElemento").val()==""){
		error=true;
		$("#cbTipoElemento").addClass("alertaCombo");
	}
	if($("#cbCatalogo").val()==""){
		error=true;
		$("#cbCatalogo").addClass("alertaCombo");
	}	
	
	if($("#cbProducto").val()=="" || $("#cbProducto").length == 0){
		error2=true;
		$("#cbProducto").addClass("alertaCombo");
	}
	
		
	if(!error){

		$('#tbCaracteristicas tbody tr').each(function (rows){
			var rd=$(this).find('td').eq(0).find('input[id="dtxtEtiqueta"]').val();		
			var rc=$(this).find('td').eq(0).find('input[id="dtxtCatalogo"]').val();
			var rp=$(this).find('td').eq(0).find('input[id="dtxtProducto"]').val();
				
			if(rd == etiqueta && rp == producto){
				dulpicado=true;	
				$("#estado").html("Ya se agregó una característica con el mismo nombre de etiqueta para el producto seleccionado.").addClass('alerta');	
				$("#txtEtiqueta").addClass("alertaCombo");
		    	return false;
	    	}

			if(rc == catalogoValor && rp == producto){
				dulpicado=true;	
				$("#estado").html("Ya se agregó una característica con el mismo catálogo para el producto seleccionado.").addClass('alerta');	
				$("#cbCatalogo").addClass("alertaCombo");
		    	return false;
	    	}
		});

		if(!error2){
	   
	    	con=con+1;
	    	if(!dulpicado){    
		    	
				var cadena= '<tr><td><input type="hidden" id="dtxtEtiqueta" name="dtxtEtiqueta[]" value="'+etiqueta+'" style="width:30px">'+
							'<input type="hidden" id="dtxtFormulario" name="dtxtFormulario[]" value="'+dFormulario+'" style="width:30px">'+							
							'<input type="hidden" id="dtxtCatalogo" name="dtxtCatalogo[]" value="'+dCatalogo+'" style="width:30px">'+
							'<input type="hidden" id="dtxtTipoElemento" name="dtxtTipoElemento[]" value="'+dtipoElemento+'" style="width:30px">'+
							'<input type="hidden" id="dtxtProducto" name="dtxtProducto[]" value="'+dProducto+'" style="width:30px">'+					
							+con+'</td>'+
							'<td>'+etiqueta+'</td>'+'<td>'+formulario+'</td>'+'<td>'+catalogo+'</td>'+'<td>'+tipoElemento+'</td>'+														
							'<td class="borrar"><button class="icono" onClick="delFilaActual(this);return false;"></td></tr>';						
				$("#tbCaracteristicas tbody").append(cadena);
				if($("#cbProducto").length > 0 && $("#tbCaracteristicas tr").size() > 1){
					if($("#cbProducto").val()!=""){
						$("#btnGuardar").removeAttr("disabled");
					}
				}
				
	    	}
		} else{
			$("#estado").html("Debe tener seleccionado un producto en la sección Información del Producto.").addClass("alerta");
			return false;
		}
	    		
	} else{
		$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");		
	}
	
}


function delFilaActual(r){
	var i = r.parentNode.parentNode.rowIndex;		    	    
    var table = document.getElementById('tbCaracteristicas');
    table.deleteRow(i);

    var filas = table.rows.length;
    
	if(filas == 1){
 		$("#estado").html(""); 		
 		$("#btnGuardar").attr("disabled",true);
 		//$("#seccionDocumentos").hide("200"); 		
	}
	enumerar(r);
}

function enumerar(e){
	
	var i = e.parentNode.parentNode.rowIndex;		    	    
    var tabla = document.getElementById('tbItems');
    con=0;   
    $("#tbItems tbody tr").each(function(row){        
    	con+=1;    	
    	$(this).find('td').eq(0).html(con);
    	console.log(con);    	
    });
}


$("#nuevaCaracteristica").submit(function(event){
	event.preventDefault();	

	var error=false;
	
	if($("#cbProducto").val()=="" || $("#cbProducto").length == 0){
		error=true;
		$("#cbProducto").addClass("alertaCombo");
	}
	
	$("#nuevaCaracteristica").attr('data-destino', 'detalleItem');
    $("#nuevaCaracteristica").attr('data-opcion', 'guardarNuevaCaracteristica');
    //$("#frmCatalogo").attr('data-accionEnExito', 'ACTUALIZAR');    
    if (!error){
    	ejecutarJson($(this));
    }else {
    	$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
    }
});

	
</script>
