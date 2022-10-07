<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos;
	$cc = new ControladorCatalogos();
	$cu = new ControladorUsuarios();
	
	$tipoProducto = $cr->listarTipoProductoMovilizacion($conexion);
	
	
	$qSubtipoProducto = $cr->listarSubtipoProductoMovilizacion($conexion);
	
	while($fila = pg_fetch_assoc($qSubtipoProducto)){
		$subtipoProducto[]= array(idSubtipoProducto=>$fila['id_subtipo_producto'], nombre=>$fila['nombre'], idTipoProducto=>$fila['id_tipo_producto']);
	}
	
	
	$qProducto = $cr -> listarProductosMovilizacion($conexion);	
	
	while($fila = pg_fetch_assoc($qProducto)){
		$producto[]= array(idProducto=>$fila['id_producto'], nombre=>$fila['nombre_comun'], idSubtipoProducto=>$fila['id_subtipo_producto']);
	}

	
	$qRequisitos = $cr->listarRequisitosMovilizacionXArea($conexion, 'Movilización', 'SV');
	
	while($fila = pg_fetch_assoc($qRequisitos)){
	    $requisitos[]= array(idRequisito=>$fila['id_requisito'], nombre=>$fila['nombre'], idTipo=>$fila['tipo'], codigo =>$fila['codigo']);
	}
?>

<header>
	<h1>Nuevo requisito de movilización</h1>
</header>


<form id="nuevoProductoMovilizacion" data-rutaAplicacion="administracionRequisitos" data-opcion="guardarNuevoProductoMovilizacion" data-accionEnExito="ACTUALIZAR" >
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
		<legend>Requisitos</legend>	
		<div data-linea="1">
			<label for="tipoRequisito">Tipo</label>
			<select id="tipoRequisito" name="tipoRequisito" required>
				<option value="">Seleccione....</option> 
				<option value="Movilización">Movilización</option>
			</select>
		</div>
		
		<div data-linea="2">
			<label for="requisito">Requisitos</label>
			<select id="requisito" name="requisito" required>
				<option value="">Seleccione....</option>
			</select>
			
			<input type="hidden" id="nombreRequisito" name="nombreRequisito" />
		</div>
		
		<div>
			<button type="submit" class="mas">Añadir requisito</button>
		</div>
	</fieldset>				
</form>


<fieldset>
	<legend>Requisitos asignados</legend>
	<table id="requisitos">

	</table>
</fieldset>


<script type="text/javascript">
	var array_SubtipoProducto= <?php echo json_encode($subtipoProducto); ?>;
	var array_producto= <?php echo json_encode($producto); ?>;
	var array_opcionesTipoProducto = <?php echo json_encode($opcionesTipoPorducto);?>;
	var array_requisito= <?php echo json_encode($requisitos); ?>;
						
	$('document').ready(function(){	
		acciones("#nuevoProductoMovilizacion","#requisitos");
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
		$('#idProducto').val($('#producto option:selected').val());
	});

	$("#tipoRequisito").change(function(){
		srequisito ='0';
		srequisito = '<option value="">Seleccione....</option>';
		
		for(var i=0; i<array_requisito.length; i++){
		    if ($("#tipoRequisito option:selected").val() == array_requisito[i]['idTipo']){
		    	srequisito += '<option value="'+array_requisito[i]['idRequisito']+'">'+(array_requisito[i]['codigo']==null?'':array_requisito[i]['codigo']+' - ')+array_requisito[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#requisito').html(srequisito);
	});

	$("#requisito").change(function(){
		$('#nombreRequisito').val($('#requisito option:selected').text());
		distribuirLineas();
	});
	
</script>