<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorProtocolos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cp = new ControladorProtocolos();
	$cc = new ControladorCatalogos();
	
	$tipoProducto = $cp->listarTipoProductoPrograma($conexion);
	
	$qSubtipoProducto = $cp->listarSubtipoProductoPrograma($conexion);
	
	while($fila = pg_fetch_assoc($qSubtipoProducto)){
		$subtipoProducto[]= array(idSubtipoProducto=>$fila['id_subtipo_producto'], nombre=>$fila['nombre'], idTipoProducto=>$fila['id_tipo_producto']);
	}
	
	$qProducto = $cp -> listarProductosPrograma($conexion);
	
	
	while($fila = pg_fetch_assoc($qProducto)){
		$producto[]= array(idProducto=>$fila['id_producto'], nombre=>$fila['nombre_comun'], idSubtipoProducto=>$fila['id_subtipo_producto']);
	}

	$pais = $cc->listarSitiosLocalizacion($conexion,'PAIS');

	//set_time_limit(1000);
	
	ini_set('max_execution_time', 600);
	
	
	
?>

<header>
	<h1>Nuevo protocolo de comercio</h1>
</header>

<div id="estado"></div>

<form id="nuevoProtocoloComercio" data-rutaAplicacion="administracionRequisitos" data-opcion="guardarNuevoProtocoloPais" data-accionEnExito="ACTUALIZAR" >
	<fieldset id="grupoProducto">
		<legend>Producto</legend>
			<div data-linea="1">			
				<label>Tipo de producto</label> 
				<select id="tipoProducto" name="tipoProducto" required>
					<option value="">Tipo de producto....</option>
						<?php 
							while ($fila = pg_fetch_assoc($tipoProducto)){
								$opcionesTipoPorducto[] =  '<option value="'.$fila['id_tipo_producto']. '">'. $fila['nombre'] .'</option>';
							}
						?>
				</select>
				
			</div>
			
			<div data-linea="2">			
				<label>Subtipo de producto</label> 
				<select id="subtipoProducto" name="subtipoProducto" required>
					<option value="">Subtipo de producto....</option>
				</select>
			</div>
			
			<div data-linea="3">
				<label>Producto</label> 
				<select id="producto" name="producto" required>
					<option value="">Producto....</option>	
				</select>	
						
			</div>	
	</fieldset>
	<fieldset>
		<legend>País</legend>
			<div data-linea="4">			
				<label>Nombre</label> 
				<select id="pais" name="pais" required>
					<option value="">País....</option>
				</select>
			</div>	
			<button type="submit" class="mas">Añadir Protocolo</button>
	</fieldset>
</form>

<fieldset>
	<legend>Protocolos asignados</legend>
	<table id="protocoloComercio">
	</table>
</fieldset>

<script type="text/javascript">
	var array_SubtipoProducto= <?php echo json_encode($subtipoProducto); ?>;
	var array_producto= <?php echo json_encode($producto); ?>;
	var array_pais= <?php echo json_encode($pais); ?>;
	var array_opcionesTipoProducto = <?php echo json_encode($opcionesTipoPorducto);?>;
						
	$('document').ready(function(){	
		acciones("#nuevoProtocoloComercio","#protocoloComercio");
		distribuirLineas();

		for(var i=0; i<array_opcionesTipoProducto.length; i++){
			 $('#tipoProducto').append(array_opcionesTipoProducto[i]);
	    	}
	});

	$("#tipoProducto").change(function(){	
		sproducto ='0';
		sproducto = '<option value="">Subtipo de producto....</option>';
		for(var i=0; i<array_SubtipoProducto.length; i++){
		    if ($("#tipoProducto").val()==array_SubtipoProducto[i]['idTipoProducto']){
		    	sproducto += '<option value="'+array_SubtipoProducto[i]['idSubtipoProducto']+'">'+array_SubtipoProducto[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#subtipoProducto').html(sproducto);
	    $('#tipo').val($('#tipoProducto option:selected').attr('data-grupo'));
	});

	$("#subtipoProducto").change(function(){	
		sproducto ='0';
		sproducto = '<option value="">Producto....</option>';
		for(var i=0; i<array_producto.length; i++){
		    if ($("#subtipoProducto").val()==array_producto[i]['idSubtipoProducto']){
		    	sproducto += '<option value="'+array_producto[i]['idProducto']+'">'+array_producto[i]['nombre']+'</option>';
			    } 
	    	}	
	    $('#producto').html(sproducto);
	});
	
	$("#producto").change(function(){
		spais ='0';
		spais = '<option value="">País....</option>';
		for(var i=0; i<array_pais.length; i++){
		    spais += '<option value="'+array_pais[i]['codigo']+'">'+array_pais[i]['nombre']+'</option>';
	    	}
	    $('#pais').html(spais);

		$('#idProducto').val($('#producto option:selected').val());
	});
	
</script>