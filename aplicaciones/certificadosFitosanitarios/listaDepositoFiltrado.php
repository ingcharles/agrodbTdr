<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();


$contador = 0;
$itemsFiltrados[] = array();
$_SESSION['ruc']  = $_POST['ruc'];

	echo'<header> <nav>';
	$ca = new ControladorAplicaciones();
	$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
	
	while($fila = pg_fetch_assoc($res)){
		echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
	}
		 
	echo'</nav></header>';

	
	$res = $cc -> abrirDeposito($conexion,$_POST['ruc']);
	while($fila = pg_fetch_assoc($res)){

		$itemsFiltrados[] = array('<tr
				id="'.$fila['identificador'].'"
				class="item"
				data-rutaAplicacion="certificadosFitosanitarios"
				data-opcion="abrirDeposito"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['razon_social'].'</b></td>
					<td style="white-space:nowrap;"><b>'.$fila['fecha_deposito'].'</b></td>
					<td>'.$fila['numero_papeleta'].'</td>
					<td>'.$fila['valor_deposito'].'</td>
					
				</tr>');

	}
	

	
?>
	

<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Razón social</th>
			<th>Fecha depósito</th>
			<th>Número papeleta</th>
			<th>Depósito</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script type="text/javascript"> 

	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});
	
	

</script>