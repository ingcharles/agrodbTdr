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

$proveedor = pg_fetch_assoc($cr->abrirProveedoresOperador($conexion, $_POST['id'])); 

$tipoSubtipoProducto = pg_fetch_assoc($cc->obtenerTipoSubtipoXProductos($conexion, $proveedor['id_producto']));


///Obtener lista de tipo de productos
$tipoProducto = $cc->listarTipoProductos($conexion);

$subtipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $tipoSubtipoProducto['id_tipo_producto'])

/*$qPais = $cre->consultaProductoPaisPermitido($conexion);
while($fila = pg_fetch_assoc($qPais)){
	$pais[]= array(idLocalizacion=>$fila['id_localizacion'], nombre=>$fila['nombre'], idProducto=>$fila['id_producto']);
}*/

//Listado de operaciones permitidas para el operador de acuerdo a sus áreas registradas
//$tipoOperacionPermitidas = $cr -> listarTipoOperacionComercioExterior($conexion, $_SESSION['usuario']);

/*if($proveedor['nombre_operacion']!=''){
	$exterior=1;
}else{
	$exterior=0;
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
<pre><?php //print_r($_POST);print_r($proveedor);?></pre>
<form id="datosProveedor" data-rutaAplicacion="registroOperador" data-opcion="actualizarProveedor" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="id" name="id" value="<?php echo $_POST['id']; ?>" />
	<input type="hidden"name="idOperador" value="<?php echo $_SESSION['usuario'];?>"/>
	<input type="hidden" id="opcion" name="opcion" />
	<input type="hidden" id="nombreProducto" name="nombreProducto" value="<?php echo $proveedor['nombre_producto'];?>" />	
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<div id="estado"></div>

		<fieldset>
			<legend>Información básica</legend>
				<p class="nota">Ingrese la información de su proveedor. Seleccione el producto que la persona le ofrece para su actividad.</p>
				
				<div data-linea="1">
					<label>Código proveedor: </label> 
						<input type="text" id="idProveedor" name="idProveedor" value="<?php echo $proveedor['codigo_proveedor'];?>" placeholder="Ej: 1815161213"  disabled="disabled" data-er="^[0-9]+$"/>
				</div>
				
				<div data-linea="2">	
					<label id="lTipoProducto">Tipo de producto</label> 
						<select id="tipoProducto" name="tipoProducto" disabled="disabled">
							<option value="">Tipo de producto....</option>
								<?php 
									while ($fila = pg_fetch_assoc($tipoProducto)){
										$opcionesTipoProducto[] =  '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
									}
								?>
						</select>
				</div>
				
				<div data-linea="3">
					<div id="dSubTipoProducto">
						<label>Subtipo de producto</label> 
							<select id="subtipoProducto" name="subtipoProducto" disabled="disabled">
								<option value="">Subtipo de producto....</option>
									<?php 
										while ($fila = pg_fetch_assoc($subtipoProducto)){
											$opcionesSubTipoProducto[] =  '<option value="'.$fila['id_subtipo_producto']. '" >'. $fila['nombre'] .'</option>';
										}
									?>
							</select>
					</div>			
					
				</div>
				
				<div data-linea="4">
					<div id="dProducto">
						<label>Producto</label> 
							<select id="producto" name="producto" disabled="disabled">
							</select>
					</div>	
				</div>	
		</fieldset>
		
		<!-- fieldset id="comExterior" name="comExterior">
			<legend>Información de comercio exterior</legend>
				<p class="nota">Seleccione el país y la actividad que realizará.</p>
				
				<div data-linea="5">		
					<label>Mi operación</label> 
						<select id="operacion" name="operacion" disabled="disabled">
						</select>
						<input type="hidden" id="nombreOperacion" name="nombreOperacion" value="< ?php echo $proveedor['nombre_operacion'];?>" />
				</div>	
				
				<div data-linea="5">	
					<label id="lPais">País: </label> 
						<select id="pais" name="pais" disabled="disabled">
						</select>
						<input type="hidden" id="nombrePais" name="nombrePais" value="< ?php echo $proveedor['nombre_pais'];?>" />
				</div>
		</fieldset-->
</form>

</body>
<script type="text/javascript">
	//var array_producto= < ?php echo json_encode($producto); ?>;
	//var array_pais= < ?php echo json_encode($pais); ?>;
	//var array_tipoOperacionPermitidas= < ?php echo json_encode($tipoOperacionPermitidas); ?>;
	//var array_SubtipoProducto= < ?php echo json_encode($subtipoProducto); ?>;
	//var exterior = 0;
	
	var array_opcionesTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
	var array_opcionesSubTipoProducto = <?php echo json_encode($opcionesSubTipoProducto);?>;

	$(document).ready(function(){
		distribuirLineas();

		for(var i=0; i<array_opcionesTipoProducto.length; i++){
			 $('#tipoProducto').append(array_opcionesTipoProducto[i]);
	    }

		for(var i=0; i<array_opcionesSubTipoProducto.length; i++){
			 $('#subtipoProducto').append(array_opcionesSubTipoProducto[i]);
	    }

		//$("#comExterior").hide();
		$('<option value="<?php echo $proveedor['id_producto'];?>"><?php echo $proveedor['nombre_producto'];?></option>').appendTo('#producto');
		cargarValorDefecto('tipoProducto', <?php echo $tipoSubtipoProducto['id_tipo_producto'];?>);
		cargarValorDefecto('subtipoProducto', <?php echo $tipoSubtipoProducto['id_subtipo_producto'];?>);		
	    
		//$('<option value="< ?php echo $proveedor['operacion_operador'];?>">< ?php echo $proveedor['nombre_operacion'];?></option>').appendTo('#operacion');
		//$('<option value="< ?php echo $proveedor['id_pais'];?>">< ?php echo $proveedor['nombre_pais'];?></option>').appendTo('#pais');

		/*if(< ?php echo $exterior;?>==1){
			$("#comExterior").show();
			exterior=1;
		}*/
	});

	$("#tipoProducto").change(function(){	
		$("#datosProveedor").attr('data-opcion', 'combosProveedor');
	    $("#datosProveedor").attr('data-destino', 'dSubTipoProducto');
	    $("#opcion").val('subTipoProducto');
	    abrir($("#datosProveedor"), event, false);
	});
	
	$("#subtipoProducto").change(function(){	
		$("#datosProveedor").attr('data-destino','dProducto');
 		$("#datosProveedor").attr('data-opcion', 'combosProveedor');
 		$("#opcion").val('producto');
 	 	abrir($("#datosProveedor"),event,false);
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
		    	if ($("#grupoProducto option:selected").attr("data-grupo")==array_tipoOperacionPermitidas[i]['area']){
		   	 		sTipoOperacion += '<option value="'+array_tipoOperacionPermitidas[i]['idTipoOperacion']+'">'+array_tipoOperacionPermitidas[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#operacion').html(sTipoOperacion);
	});*/

	/*$("#operacion").change(function(){	
	    $('#nombreOperacion').val($("#operacion option:selected").text());
	});

	$("#pais").change(function(){	
	    $('#nombrePais').val($("#pais option:selected").text());
	});*/

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});	

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

		/*if(exterior == 1){
			if(!$.trim($("#operacion").val()) || !esCampoValido("#operacion")){
				error = true;
				$("#operacion").addClass("alertaCombo");
			}
	
			if(!$.trim($("#pais").val()) || !esCampoValido("#pais")){
				error = true;
				$("#pais").addClass("alertaCombo");
			}
		}*/
		
		if (error == true){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

</script>
</html>