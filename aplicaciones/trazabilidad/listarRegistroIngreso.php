<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorTrazabilidad.php';

$conexion = new Conexion();
$ct = new ControladorTrazabilidad();

?>

	<header>
		<h1>Registros Ingreso</h1>
		<nav>

			<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
				>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';

			}
			?>
		</nav>
	</header>
	
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Código Proveedor</th>
				<th>Producto</th>
				<th>Cantidad</th>
				<th>Unidad Medida</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	

	<?php 
	$res = $ct->datosRegistro($conexion,$_SESSION['usuario']);
	$contador = 0;
	
	while($registro = pg_fetch_assoc($res)){

			//$categoria = $registro['tipo'];
			//$_SESSION['nombre_comun']=$registro['nombre_comun'];
			echo '<tr
					id="'.$registro['id_codigo_proveedor'].'"
					class="item"
					data-rutaAplicacion="trazabilidad"
					data-opcion="abrirRegistroIngreso"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$registro['id_codigo_proveedor'].'</b></td>
				<td>'.$registro['nombre_comun'].'</td>
				<td>'.$registro['cantidad_producto'].'</td>
				<td>'.$registro['codigo'].'</td>
				</tr>';
			
			}

			?>

	</table>	

<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
	});
</script>
</html>
