<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$ca = new ControladorCatastro();

 $idExcelRol=$_POST['id'];

$contador = 0;
$itemsFiltrados[] = array();
$res = $ca->obtenerRolPagosXmesExcel($conexion, $idExcelRol);


while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_funcionario_rol_pago'].'"
		class="item"
		data-rutaAplicacion="uath"
		data-opcion="abrirAdministrarRolPagos"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
		<td>'.$fila['nombre_completo'].'</td>
		<td>'.$fila['mes_rol'].'</td>
		<td>'.$fila['anio'].'</td>	
		</tr>');
}

?>

 <div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Funcionarios</th>	
			<th>Mes</th>
			<th>Año</th>
			
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);			
	});

</script>

