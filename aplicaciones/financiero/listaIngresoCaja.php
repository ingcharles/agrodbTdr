<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCertificados.php';
	
	$itemsFiltrados[] = array();
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Ingreso de caja</h1>
		<nav>
		<?php 

			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
			while($fila = pg_fetch_assoc($res)){
				if($fila['estilo'] != '_agrupar'){
					echo '<a href="#"
							id="' . $fila['estilo'] . '"
							data-destino="detalleItem"
							data-opcion="' . $fila['pagina'] . '"
							data-rutaAplicacion="' . $fila['ruta'] . '"
							>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
					}
			}
		?>
		</nav>
</header>

	<?php 
		$cc = new ControladorCertificados();
		$res = $cc->listarOrdenPago($conexion,'ABIERTOS', $_SESSION['nombreProvincia'], 'Ingreso Caja');
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){

			$itemsFiltrados[] = array('<tr 
											id="'.$fila['id_pago'].'"
											class="item"
											data-rutaAplicacion="financiero"
											data-opcion="abrirIngresoDeCaja"
											ondragstart="drag(event)"  
											draggable="true" 
											data-destino="detalleItem">
										<td>'.++$contador.'</td>
										<td>'.$fila['identificador_operador'].'</td>
										<td>'.$fila['numero_solicitud'].'</td>
										<td>'.date('d/m/Y (G:i)',strtotime($fila['fecha_orden_pago'])).'</td>
									  </tr>');
		}
	?>
	
	<div id="paginacion" class="normal"></div>
	
	<table id="tablaItems">
		<thead>
			<tr>
				<th>#</th>
				<th>Identificador</th>
				<th>#Solicitud</th>
				<th>Fecha solicitud</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
</body>

<script>
$(document).ready(function(){
	construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	$("#listadoItems").removeClass("comunes");
});

$("#_eliminar").click(function(){
	if($("#cantidadItemsSeleccionados").text()>1){
			alert('Por favor seleccione un ingreso de caja a la vez');
			return false;
		}
	});
</script>
</html>