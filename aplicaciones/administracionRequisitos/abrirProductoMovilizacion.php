<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorAuditoria.php';
	
	$idRequisitoComercio = $_POST['id'];
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	$ca = new ControladorAuditoria();
	
	$requisitosComercio = pg_fetch_assoc($cr->abrirRequisitosComercio($conexion, $idRequisitoComercio));
	
	$nombreProducto = pg_fetch_result($cc->obtenerNombreProducto($conexion, $requisitosComercio['id_producto']), 0, 'nombre_comun');	
	$datosTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $requisitosComercio['id_producto']);	
	$nombreTipoProducto = pg_fetch_result($datosTipoSubtipo, 0, 'nombre_tipo');	
	$nombreSubtipoProducto = pg_fetch_result($datosTipoSubtipo, 0, 'nombre_subtipo');
	
	$requisitosAsignados = $cr->listarRequisitosMovilizacionAsignados ($conexion, $idRequisitoComercio, 'Movilización');
	
	$qRequisitos = $cr->listarRequisitosMovilizacionXArea($conexion, 'Movilización', $requisitosComercio['tipo']);
	
	while($fila = pg_fetch_assoc($qRequisitos)){
	    $requisitos[]= array(idRequisito=>$fila['id_requisito'], nombre=>$fila['nombre'], idTipo=>$fila['tipo'], codigo =>$fila['codigo']);
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de Países</h1>
	</header>
	
	<table class="soloImpresion">
		<tr>
			<td>
					<input type="hidden" id="producto" name="producto" value="<?php echo $requisitosComercio['id_producto'];?>">
						<fieldset>
							<legend>Detalle del Producto</legend>	
							<div data-linea="1">			
								<label>Tipo producto</label> 
								<input type="text" id="nombreTipoProducto" name="nombreTipoProducto" value="<?php echo $nombreTipoProducto;?>" readonly="readonly"/>	
							</div>
							
							<div data-linea="2">			
								<label>Subtipo producto</label> 
								<input type="text" id="nombreSubtipoProducto" name="nombreSubtipoProducto" value="<?php echo $nombreSubtipoProducto;?>" readonly="readonly"/>	
							</div>
							
							<div data-linea="3">			
								<label>Producto</label> 
								<input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo $nombreProducto;?>" readonly="readonly"/>	
							</div>	
					</fieldset>

				<form id="nuevoRequisito" data-rutaAplicacion="administracionRequisitos" data-opcion="asignarRequisito" >
					<input type="hidden" id="idRequisitoComercio" name="idRequisitoComercio" value="<?php echo $requisitosComercio['id_requisito_comercio'];?>">
							
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
						<?php
							while ($requisito = pg_fetch_assoc($requisitosAsignados)){
								echo $cr->imprimirLineaRequisito($requisito['id_requisito_comercio'], $requisito['requisito'], $requisito['nombre'], $requisito['tipo'], $requisito['estado']);
							}
						?>
					</table>
				</fieldset>

			</td>
		</tr>
	</table>
	
	<fieldset>
		<legend>Historial de Cambios</legend>
			
			<button type="button" id='mostrarHistorial'>Mostrar/Ocultar</button>   
				<table id="historial">
			   		<thead>
			   			<tr>
			   				<th colspan =2>Primera modificación del Registro</th>
			   			</tr>
						<tr>
					    	<th>Fecha</th>
					     	<th>Acción realizada</th>
					    </tr>
				 	</thead>
					<tbody>
					 	<tr>
					     	<?php 
					     		$qHistorial = $ca->listaHistorial($conexion, $requisitosComercio['id_requisito_comercio'], $_SESSION['idAplicacion'], 'ASC', 1);
						     	
				      			while($historial = pg_fetch_assoc($qHistorial)){
							        echo ' <td>'.date('j/n/Y (G:i:s)',strtotime($historial['fecha'])).'</td>
							            <td>'.$historial['accion'].'</td></tr><tr>';
							    }
					     	?>
					    </tr>
					</tbody>
			  	</table>
			  	
			  	<table id="historial1">
			  	<thead>
			   			<tr>
			   				<th colspan =2>Última modificación del Registro</th>
			   			</tr>
						<tr>
					    	<th>Fecha</th>
					     	<th>Acción realizada</th>
					    </tr>
				 	</thead>
					<tbody>
					 	<tr>
					     	<?php 
					     		$qHistorial = '';
					     		$historial = '';
					     		
					     		$qHistorial = $ca->listaHistorial($conexion, $requisitosComercio['id_requisito_comercio'], $_SESSION['idAplicacion'], 'DESC', 1);
							    while($historial = pg_fetch_assoc($qHistorial)){
							    	echo ' <td>'.date('j/n/Y (G:i:s)',strtotime($historial['fecha'])).'</td>
							            <td>'.$historial['accion'].'</td></tr><tr>';
							    }
					     	?>
					    </tr>
					</tbody>
			  	</table>
 	</fieldset>	
</body>

<script>
	var array_requisito= <?php echo json_encode($requisitos); ?>;

	$('document').ready(function(){
		actualizarBotonesOrdenamiento();
		acciones("#nuevoRequisito", "#requisitos");
		distribuirLineas();
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

	$("#mostrarHistorial").click(function(){
		 $("#historial").slideToggle();
		 $("#historial1").slideToggle();			 
	});
</script>
</html>