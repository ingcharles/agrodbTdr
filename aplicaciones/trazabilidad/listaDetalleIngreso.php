<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTrazabilidad.php';

$conexion = new Conexion();
$ct = new ControladorTrazabilidad();


$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
$identificadorProveedor = htmlspecialchars ($_POST['codproveedor'],ENT_NOQUOTES,'UTF-8');
$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
$idSitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
$idArea = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');

$res = $ct -> filtrarDetallesIngresosOperador($conexion, $identificadorOperador, $identificadorProveedor, $idProducto, $idSitio, $idArea, $fecha);

$contador = 0;
$itemsFiltrados[] = array();


	while($fila = pg_fetch_assoc($res)){

		$itemsFiltrados[] = array( "
			<tr id='r_".$fila['id_detalle_ingreso']."'>
				<td>
					<form id='f_".$fila['id_detalle_ingreso']."' data-rutaAplicacion='trazabilidad' data-opcion='quitarDetalle' data-accionEnExito='ACTUALIZAR'>
						<button type='submit' class='menos'>Quitar</button>
                		<input name='id_detalle_ingreso' value='".$fila['id_detalle_ingreso'] ."' type='hidden'>
						<input name='cantidadProducto' value='".$fila['cantidad_producto'] ."' type='hidden'>
						<input name='medida' value='".$fila['medida'] ."' type='hidden'>
						<input name='numeroBultos' value='".$fila['numero_bultos'] ."' type='hidden'>
						<input name='desBultos' value='".$fila['nombre_bultos'] ."' type='hidden'>
						<input name='variedad' value='".$fila['nombre_variedad'] ."' type='hidden'>
						<input name='calidad' value='".$fila['nombre_calidad'] ."' type='hidden'>
					</form>
				</td>
				<td>".$fila['cantidad_producto']."</td>
				<td>".$fila['medida']."</td>
				<td>".$fila['numero_bultos']."</td>
				<td>".$fila['nombre_bultos']."</td>
				<td>".$fila['nombre_variedad']."</td>
				<td>".$fila['nombre_calidad']."</td>

			</tr>");
	}

?>


	<fieldset>
		<legend>Registros ingresados</legend>
		<div>
			<div id="paginacion" class="normal">
			
			</div>
			
				<table id="tablaItems">
					<thead>
						<tr>
							<th>#</th>
							<th>Cantidad de Producto</th>
							<th>Unidad de Medida</th>
							<th>Numero de Bultos</th>
							<th>Descripción de Bultos</th>
							<th>Variedad</th>
							<th>Calidad</th>
						</tr>
					</thead>
					<tbody id="areas">	
					</tbody>
				</table>
			</div>
	</fieldset>

	
<script type="text/javascript"> 

	var itemInicial = 0;
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

	$("#tablaItems").on("submit","form",function(event){
		event.preventDefault();
		ejecutarJson($(this));
		var texto=$(this).attr('id').substring(2);
		texto=texto.replace(/ /g,'');
		texto="#r_"+texto;
		$("#areas tr").eq($(texto).index()).remove();
	});

</script>
