<?php 
  session_start();
  require_once '../../clases/Conexion.php';
  require_once '../../clases/ControladorAplicaciones.php';
  require_once '../../clases/ControladorCertificados.php';
	
 $conexion = new Conexion();
  
 $idOpcionArea = explode('-', $_POST['id']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Servicios</h1>
		<nav>
		<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $idOpcionArea[1], $_SESSION['usuario']);
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
				<th>Codigo</th>
				<th></th>
				<th>Descripción</th>
			</tr>
		</thead>
	
	
	<?php 
	$cc = new ControladorCertificados();
	$idServicio = pg_fetch_assoc($cc->obtenerIdServicioXarea($conexion, $idOpcionArea[0], 'activo'));
	$res = $cc->obtenerServicioXarea($conexion, $idServicio['id_servicio'], 'TODO');
	
	while($fila = pg_fetch_assoc($res)){
		if($fila['id_categoria_servicio'] == 2){
			echo '<tr
					id="'.$fila['id_servicio'].'-'. $idOpcionArea[0].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirSubDocumento"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
				<td style="font-weight: bold;">'.$fila['codigo'].'</td>
				<td></td>
				<td style="font-weight: bold;">'.$fila['concepto'].'</td>
				</tr>';
		}else if($fila['id_categoria_servicio'] == 3){
			echo '<tr
					id="'.$fila['id_servicio'].'-'. $idOpcionArea[0].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirItem"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem">
				<td></td>
				<td style="font-size: smaller;">'.$fila['codigo'].'</td>
				<td style="font-size: smaller;">'.$fila['concepto'].'</td>
				</tr>';
		}		
	}
	?>
	</table>
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una item para revisarlo.</div>');
		$('#_nuevo').addClass('_nuevo');
		$('#_nuevo').attr('id', <?php echo json_encode($idOpcionArea[0]);?>);
	});
	
</script>
</html>
