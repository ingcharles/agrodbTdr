<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$identificador=$_SESSION['usuario'];
$idOperacion = $_POST['id'];
$nombreArea = '';

$operacion=$cr->abrirOperacion($conexion, $identificador,$idOperacion);

if(count($operacion) > 1){
	foreach ($operacion as $datos){
		$nombreArea .= $datos['nombreArea'].', ';
	}
}else{
	$nombreArea = $operacion[0]['nombreArea'];
}
$nombreArea = trim($nombreArea, ', ');

$idOperadorTipoOperacion = $operacion[0]['idOperadorTipoOperacion'];
$idHistorialOperacion = $operacion[0]['idHistorialOperacion'];

$qRepresentanteTecnico=$cr->consultarDatosRepresentanteTecnicoOperacion($conexion,$idOperadorTipoOperacion, $idOperacion);
$qTipoOperacion = $cc->obtenerDatosTipoOperacion($conexion, $operacion[0]['idTipoOperacion']);
$tipoOperacion = pg_fetch_assoc($qTipoOperacion);

$opcionesTipoProducto = array();
$opcionesTipoProducto[]='<option value="">Seleccione...</option>';

	if(pg_num_rows($qRepresentanteTecnico)!=0){
		
		$idArea= pg_fetch_result($qRepresentanteTecnico, 0, 'id_area');
		$idTipoProducto =pg_fetch_result($qRepresentanteTecnico, 0, 'id_tipo_producto');
		
		switch ($idArea){
			case 'SA':
			case 'SV':
			case 'LT':
				
				$qTipoProducto= $cc->obtenerTipoProductosXid($conexion,$idTipoProducto);
				$qSubtipoProducto = $cc->obtenersubtipoProductosXidTipo($conexion,$idTipoProducto);
				while($fila = pg_fetch_assoc($qSubtipoProducto)){
				        $subtipoProducto[]= array('idsubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
				}
			break;
			case 'IAV':
			case 'IAP':
			case 'IAF':
				$qTipoProducto= $cc->listarTipoProductosXareas($conexion," in ('".$idArea."')");
				$qSubtipoProducto = $cc->listarSubProductosXareas($conexion," in ('".$idArea."')");
				while($fila = pg_fetch_assoc($qSubtipoProducto)){
					$subtipoProducto[]= array('idsubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
				}
			break;
			case 'CGRIA':
				$idArea = "'IAV','IAP','IAF'";
				$qTipoProducto= $cc->listarTipoProductosXareas($conexion," in (".$idArea.")");
				$qSubtipoProducto = $cc->listarSubProductosXareas($conexion," in (".$idArea.")");
				while($fila = pg_fetch_assoc($qSubtipoProducto)){
					$subtipoProducto[]= array('idsubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
				}
			break;
		}
	}else{
		$qTipoProducto= $cc->listarTipoProductosXareas($conexion," in ('".$tipoOperacion['id_area']."')");
		$qSubtipoProducto = $cc->listarSubProductosXareas($conexion," in ('".$tipoOperacion['id_area']."')");
		while($fila = pg_fetch_assoc($qSubtipoProducto)){
			$subtipoProducto[]= array('idsubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
		}
	}
	
	$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $idOperacion);
	$opcionArea = pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
	$idAreaTematica = pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

	$banderaComercializadorInocuidad = false;
	
	switch ($idAreaTematica){
	    case 'AI':
	        switch ($opcionArea){
				 case 'COM':
					$banderaComercializadorInocuidad = true;
	            break;
	        }
	    break;
	}	
	
?>



<header>
	<h1>Nuevo producto</h1>
</header>

<?php 

if(!$banderaComercializadorInocuidad){
	
	$requiereEliminarProducto = 'SI';

	if($tipoOperacion['requiere_eliminar_producto'] == 'f'){
		$requiereEliminarProducto = 'NO';
	}

?>
		
		<form id='nuevaSolicitud' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevoProducto' data-destino="detalleItem">
		
			<input type="hidden" id="identificadorOperador" name="identificadorOperador" value="<?php echo $identificador;?>" />
			<input type="hidden" id="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" />
			<input type="hidden" id="opcion" name="opcion" />
			<input type="hidden" id="idArea" name="idArea" />
			
			<div id="estado"></div>
			
			<fieldset>
				<legend>Registro de Operador</legend>
					<div data-linea="1">			
						<label>Tipo de Producto: </label>
						<select id="tipoProducto" name="tipoProducto" >
							<?php
								while ($fila = pg_fetch_assoc($qTipoProducto)){
								    if($fila['codificacion_tipo_producto']!='PRD_CULTIVO_IAP'){
									     $opcionesTipoProducto[] =  '<option data-area="'.$fila['id_area']. '"  value="'.$fila['id_tipo_producto']. '" >'. $fila['nombre'] .'</option>';
								    }
								}
							?>
						</select>
						<input type="hidden" id="nombreTipoProducto" name="nombreTipoProducto" />
					</div>
					<div data-linea="2">
						<label>Subtipo de Producto: </label>
						<select id="subtipoProducto" name="subtipoProducto" >
						</select>
						<input type="hidden" id="nombreSubtipoProducto" name="nombreSubtipoProducto" />
					</div>
					<div data-linea="3">
						<div id="dProducto"></div>
					</div>	
					
					<button type="submit" class="mas">Agregar producto</button>
			</fieldset>
			<p class="nota">Por favor revise que la información ingresada sea correcta. Si ya posee productos agregados previamente, puede enviar la solicitud sin agregar más productos.</p>
		</form> 
		
			<fieldset>
				<legend>Productos agregados</legend>
				<div data-linea="1"><label>Tipo operación: </label><?php echo $tipoOperacion['nombre'];?></div>
				<div data-linea="2"><label>Nombre sitio: </label><?php echo $operacion[0]['nombreSitio'];?></div>
				<div data-linea="3"><label>Nombre área: </label><?php echo $nombreArea;?></div>

				<div data-linea="5">
					<table id="operaciones" style="width:100%">
						<thead>
							<tr>
								<th>Tipo producto</th>
								<th>Subtipo producto</th>
								<th>Producto</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php 
							$qProductos = $cr->obtenerProductoPorIdOperadorTipoOperacionIdHistorialOperacion($conexion, $identificador, $idOperadorTipoOperacion, $idHistorialOperacion);
							
							while ($fila = pg_fetch_assoc($qProductos)){
								
								$eliminarProducto = ($requiereEliminarProducto == 'NO' ? '1': $fila['id_operacion_proveedor']); 
								
								echo $cr->imprimirLineaProductoOperacion($fila['id_operacion'], $fila['nombre_tipo_producto'], $fila['nombre_subtipo_producto'], $fila['nombre_producto'], $fila['id_producto'], 'NO', $eliminarProducto);
							}
						?>
						</tbody>
					</table>
				</div>
			</fieldset>
		
		<form id="enviarProductosOperador" data-rutaAplicacion="registroOperador" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" id="idOperacion" name="idOperacion" value=" <?php echo $idOperacion;?>"/>
			<input type="hidden" id="identificadorOp" name="identificadorOp" value="<?php echo $identificador;?>" />
			<div id="numeroTransaccion"></div>
			<button type="submit" class="guardar" id="enviarSolicitud">Enviar solicitud</button>
		</form>
			
<?php 
		  
}else{
	echo  '<p class="nota">Por favor diríjase a la opción de "Declarar proveedores" para registrar un producto.</p>';
}
?>

<script type="text/javascript">

	var array_tipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
	var array_subTipoProducto = <?php echo json_encode($subtipoProducto);?>;

	$(document).ready(function(){
		distribuirLineas();	
		for(var i=0; i<array_tipoProducto.length; i++){
			$('#tipoProducto').append(array_tipoProducto[i]);
		}
		acciones("#nuevaSolicitud","#operaciones");
	});

	$("#tipoProducto").change(function(){
		if($("#tipoProducto").val()==''){
			$("#tipoProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");
			$("#subtipoProducto").html('<option value="">Seleccione...</option>');
		}else{
			subTipo = '<option value="">Seleccione...</option>';
			for(var i=0; i<array_subTipoProducto.length; i++){
		    	if (array_subTipoProducto[i]['idTipoProducto'] == $("#tipoProducto").val())
		    		subTipo += '<option value="'+array_subTipoProducto[i]['idsubtipoProducto']+'">'+array_subTipoProducto[i]['nombre']+'</option>';
			}
			$('#subtipoProducto').html(subTipo);
			$("#idArea").val($("#tipoProducto option:selected").attr('data-area'));
			$("#nombreTipoProducto").val($("#tipoProducto option:selected").text());
		}
	});

	$("#subtipoProducto").change(function(event){
		$("#estado").html("").removeClass("alerta");
		$(".alertaCombo").removeClass("alertaCombo");
 		$("#nuevaSolicitud").attr('data-destino','dProducto');
 		$("#nuevaSolicitud").attr('data-opcion', 'combosOperador');
 		$("#opcion").val('producto');
 		if($("#subtipoProducto").val() == ''){
 			$("#subtipoProducto").addClass("alertaCombo");
			$("#estado").html("Por favor seleccione un subtipo de producto.").addClass("alerta");
		}else{	 
			event.stopImmediatePropagation();
 	 		abrir($("#nuevaSolicitud"),event,false);
 	 		$("#nuevaSolicitud").removeAttr('data-destino');
	 		$("#nuevaSolicitud").attr('data-opcion', 'guardarNuevoProducto');
	 		$("#nombreSubtipoProducto").val($("#subtipoProducto option:selected").text());
	 		
		}
	 });

	$("#enviarProductosOperador").submit(function(event){
		event.preventDefault();
		$("#numeroTransaccion").html('');

		if($("#operaciones >tbody >tr").find(".ingresoProducto").length !=0){

				$("#operaciones >tbody >tr").find('input[name="datoProceso[]"]').each(
					function (i) {
						$("#numeroTransaccion").append('<input type="hidden" name="datosTransaccion[]" value ="'+$(this).val()+'"/>');
				});

			$("#enviarProductosOperador").attr('data-opcion', 'guardarValidacionProductoOperador');
			$("#enviarProductosOperador").attr('data-destino', 'detalleItem');
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor ingrese por lo menos un producto").addClass("alerta");
		}
	});

</script>