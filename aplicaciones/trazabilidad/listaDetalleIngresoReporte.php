<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorTrazabilidad.php';

$conexion = new Conexion();
$ct = new ControladorTrazabilidad();

$identificador = $_POST['identificador'];
$codproveedor=$_POST['codproveedor'];
$productoId=$_POST['producto'];
$sitioId=$_POST['sitio'];
$areaId=$_POST['area'];

$fechaInicio=$_POST['fi'];
$fechaFin=$_POST['ff'];


$res = $ct -> filtarReporteIngreso($conexion, $identificador, $codproveedor, $productoId, $sitioId, $areaId, $fechaInicio,$fechaFin);

$contador = 0;
$itemsFiltrados[] = array();
if($res!=null){
	while($fila = pg_fetch_assoc($res)){
		$itemsFiltrados[] = array('<tr
				class="item"
				data-rutaAplicacion="trazabilidad"
				ondragstart="drag(event)"
				draggable="true"
				<td>'.++$contador.'</td>
				telefono
				<td style="white-space:nowrap;"><b>'.$contador.'</b></td>
				<td>'.$fila['cantidad_producto'].'</td>
				<td>'.$fila['medida'].'</td>
				<td>'.$fila['numero_bultos']	.'</td>
				<td>'.$fila['nombre_bultos'].'</td>
				<td>'.$fila['nombre_variedad'].'</td>
				<td>'.$fila['nombre_calidad'].'</td>
				<!--td>'.$fila['fecha_ingreso'].'</td-->
				</tr>');

	}
}
?>
<div id="paginacion" class="normal"></div>

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
			<!-- th>Fecha Ingreso</th-->
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script type="text/javascript"> 
	var itemInicial = 0;
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>

