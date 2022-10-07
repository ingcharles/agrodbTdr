<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRequisitos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$cre = new controladorRequisitos();

$identificador = $_SESSION['usuario'];

//Obtener lista de tipo de productos
$tipoProducto = $cc->listarTipoProductos($conexion);

/*$qPais = $cre->consultaProductoPaisPermitido($conexion);
while($fila = pg_fetch_assoc($qPais)){
	$pais[]= array(idLocalizacion=>$fila['id_localizacion'], nombre=>$fila['nombre'], idProducto=>$fila['id_producto']);
}*/

//Listado de operaciones permitidas para el operador de acuerdo a sus áreas registradas
/*$tipoOperacionPermitidas = $cr -> listarTipoOperacionComercioExterior($conexion, $identificador);
if(($tipoOperacionPermitidas)!=null){
	//print_r ($tipoOperacionPermitidas);
	$comexval=1;
}else{
	//echo'hola';
	$comexval=0;
}*/

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>


<header>
	<h1>Datos de proveedor</h1>
</header>

<form id="datosProveedor" data-rutaAplicacion="registroOperador" data-opcion="guardarNuevoProveedor" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	
	<input type="hidden"name="idOperador" value="<?php echo $identificador;?>"/>
	<input type="hidden" id="opcion" name="opcion" />
	<input type="hidden" id="nombreProducto" name="nombreProducto" />
	
		<div id="estado"></div>

		<fieldset>
			<legend>Información básica</legend>
				<p class="nota">Ingrese la información de su proveedor. Seleccione el producto que la persona le ofrece para su actividad.</p>
				
				<div data-linea="1">
					<label>Código proveedor: </label> 
						<input type="text" id="idProveedor" name="idProveedor" value="<?php echo $producto['nombre_comun'];?>" placeholder="Ej: 1815161213" data-er="^[0-9]+$"/>
				</div>
				
				<div data-linea="2">			
					<label>Tipo de producto</label> 
					<select id="tipoProducto" name="tipoProducto">
						<option value="">Tipo de producto....</option>
							<?php 
								while ($fila = pg_fetch_assoc($tipoProducto)){
									$opcionesTipoProducto[] =  '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
								}
							?>
					</select>
				</div>
				
				<div data-linea="3">			
					<div id="dSubTipoProducto"></div>
				</div>
			
				<div data-linea="4">
					<div id="dProducto"></div>			
				</div>
				
				<!-- table>	
					<tr>
						<td>
							<input type="checkbox" id="requiereComex" name="requiereComex" value="requiereComex" />
						</td>
						<td>
							<label id="lRequiereComex" for="requiereComex">Es un proveedor para actividades de comercio exterior.</label>
						</td>
					</tr>
				</table-->
				
		</fieldset>
		
		<!-- fieldset id="comExterior" name="comExterior">
			<legend>Información de comercio exterior</legend>
				<p class="nota">Seleccione el país y la actividad que realizará.</p>
				
				<div data-linea="5">		
					<label>Mi operación</label> 
						<select id="operacion" name="operacion" >
							<option value="">Seleccione....</option>
						</select>
						<input type="hidden" id="nombreOperacion" name="nombreOperacion" />
				</div>	
				
				<div data-linea="5">	
					<label id="lPais">País: </label> 
						<select id="pais" name="pais" >
							<option value="">País....</option>
						</select>
						<input type="hidden" id="nombrePais" name="nombrePais" />
				</div>
		</fieldset-->
		
		<button type="submit" class="guardar">Guardar proveedor</button>
</form>

</body>
<script type="text/javascript">
	//var array_producto= < ?php echo json_encode($producto); ?>;
	//var array_pais= < ?php echo json_encode($pais); ?>;
	//var array_tipoOperacionPermitidas= < ?php echo json_encode($tipoOperacionPermitidas); ?>;
	//var array_SubtipoProducto= < ?php echo json_encode($subtipoProducto); ?>;
	
	var array_opcionesTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;

	$(document).ready(function(){
		distribuirLineas();
		
		/*$("#comExterior").hide();
		if(< ?php echo $comexval;?>==0){
			$("#requiereComex").hide();
			$("#lRequiereComex").hide();
		}*/

		for(var i=0; i<array_opcionesTipoProducto.length; i++){
			 $('#tipoProducto').append(array_opcionesTipoProducto[i]);
	    }

	});

	/*$('#requiereComex').change(function() {
        if($(this).is(":checked")) {
        	$('#comExterior').fadeIn();
        }else{
        	$('#comExterior').hide();
        }        
    });*/

	$("#tipoProducto").change(function(event){	
		$("#datosProveedor").attr('data-opcion', 'combosProveedor');
	    $("#datosProveedor").attr('data-destino', 'dSubTipoProducto');
	    $("#opcion").val('subTipoProducto');
	    abrir($("#datosProveedor"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	});

	$("#idProveedor").change(function(event){	
		$("#datosProveedor").attr('data-opcion', 'combosProveedor');
	    $("#datosProveedor").attr('data-destino', 'estado');
	    $("#opcion").val('verificarProveedor');
	    abrir($("#datosProveedor"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	});
	
	

	/*$("#producto").change(function(){	
		$('#nombreProducto').val($("#producto option:selected").text());
		
		spais ='0';
		spais = '<option value="">País....</option>';
		for(var i=0; i<array_pais.length; i++){
		    if ($("#producto").val()==array_pais[i]['idProducto']){
		    	spais += '<option value="'+array_pais[i]['idLocalizacion']+'">'+array_pais[i]['nombre']+'</option>';
			} 
	    }
	    $('#pais').html(spais);

		sTipoOperacion ='0';
		sTipoOperacion = '<option value="">Tipo de operación....</option>';
		for(var i=0; i<array_tipoOperacionPermitidas.length; i++){
		    	if ($("#tipoProducto option:selected").attr("data-grupo")==array_tipoOperacionPermitidas[i]['area']){
		   	 		sTipoOperacion += '<option value="'+array_tipoOperacionPermitidas[i]['idTipoOperacion']+'">'+array_tipoOperacionPermitidas[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#operacion').html(sTipoOperacion);
 
	});

	$("#operacion").change(function(){	
	    $('#nombreOperacion').val($("#operacion option:selected").text());
	});

	$("#pais").change(function(){	
	    $('#nombrePais').val($("#pais option:selected").text());
	});*/

	$("#datosProveedor").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#idProveedor").val()) || !esCampoValido("#idProveedor")){
			error = true;
			$("#idProveedor").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoProducto").val()) || !esCampoValido("#tipoProducto")){
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#subtipoProducto").val()) || !esCampoValido("#subtipoProducto")){
			error = true;
			$("#subtipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#producto").val()) || !esCampoValido("#producto")){
			error = true;
			$("#producto").addClass("alertaCombo");
		}

		/*if($("input:checkbox[name=requiereComex]:checked").val() != null){
			if(!$.trim($("#operacion").val()) || !esCampoValido("#operacion")){
				error = true;
				$("#operacion").addClass("alertaCombo");
			}
	
			if(!$.trim($("#pais").val()) || !esCampoValido("#pais")){
				error = true;
				$("#pais").addClass("alertaCombo");
			}
		}*/
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#datosProveedor").attr('data-opcion', 'guardarNuevoProveedor');
		    $("#datosProveedor").attr('data-destino', 'detalleItem');
		    
			ejecutarJson(form);
		}
	}
</script>
</html>