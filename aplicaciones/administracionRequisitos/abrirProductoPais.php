<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idProducto = $_POST['id'];
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	
	$nombreProducto = pg_fetch_result($cc->obtenerNombreProducto($conexion, $idProducto), 0, 'nombre_comun');
	
	$datosTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $idProducto);
	
	$nombreTipoProducto = pg_fetch_result($datosTipoSubtipo, 0, 'nombre_tipo');
	
	$nombreSubtipoProducto = pg_fetch_result($datosTipoSubtipo, 0, 'nombre_subtipo');
	
	$pais = $cc->listarSitiosLocalizacion($conexion,'PAIS');
	$gruposPais = $cc->listarSitiosLocalizacion($conexion,'GRUPOS_PAISES');
	
	$qPaises = $cr->listarPaisesProducto($conexion, $idProducto);
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
	<div id="estado"></div>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="nuevoRegistro" data-rutaAplicacion="administracionRequisitos" data-opcion="guardarNuevoProductoPais" >
					<input type="hidden" id="producto" name="producto" value="<?php echo $idProducto;?>">
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
					
						<fieldset>
							<legend>País</legend>	
							<div data-linea="2">			
								<label>Nombre</label> 
								<select id="pais" name="pais" >
									<option value="">País....</option>
										<?php 
											for ($i=0; $i<count($gruposPais); $i++){
												echo '<option value="'.$gruposPais[$i]['codigo'] . '">'. $gruposPais[$i]['nombre'] .'</option>';
											}
											
											for ($i=0; $i<count($pais); $i++){
												echo '<option value="'.$pais[$i]['codigo'] . '">'. $pais[$i]['nombre'] .'</option>';
											}
										?>
								</select>
								<input type="hidden" id="nombrePais" name="nombrePais" />		
							</div>
						
						<div>
							<button type="submit" class="mas">Añadir requisito</button>
						</div>
					</fieldset>
				</form>
				
				<fieldset>
					<legend>Requisitos asignados</legend>
					<table id="registros">
						<?php
							while ($paises = pg_fetch_assoc($qPaises)){
								if ($paises['nombre_pais'] != 'Ecuador'){
									echo $cr->imprimirLineaProductoPais($paises['id_requisito_comercio'], $paises['nombre_pais'], $paises['id_pais'], $nombreProducto);
								}
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</body>
<script>
	$('document').ready(function(){
		actualizarBotonesOrdenamiento();
		acciones();
		distribuirLineas();
	});

	$("#requisito").change(function(){
		$('#nombreRequisito').val($('#requisito option:selected').attr('nombre'));
		$('#tipoRequisito').val($('#requisito option:selected').attr('tipo'));
	});

	$("#fecha").datepicker({
	    changeMonth: true,
	    changeYear: true
	  });
</script>
</html>