<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$conexion = new Conexion();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Operaciones</h1>
		<nav>

		<?php 
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
		?>
		</nav>
	</header>
	
	<pre><?php //print_r($_SESSION);?></pre>
	
	<table id="enviado">
		<thead>
			<tr>
				<th>POR ASIGNAR</th>
			</tr>
			<tr>
				<th>#</th>
				<th>RUC</th>
				<th>#Operación</th>
				<th>Tipo de Operación</th>
				<th>Producto</th>
				<th>Estado</th>
			</tr>
		</thead>
	</table>

	<?php 
	
		$cr = new ControladorRegistroOperador();
		//$res = $cr->listarOperacionesRevision($conexion);
		$res = $cr->listarOperacionesRevisionProvincia($conexion, $_SESSION['nombreProvincia']);
		$contador = 0;
		while($operaciones = pg_fetch_assoc($res)){

			$categoria = $operaciones['estado'];

			$contenido = '<tr 
						id="'.$operaciones['id_operacion'].'"
						class="item"
						data-rutaAplicacion="registroOperador"
						data-opcion="abrirOperacionCoordinador" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$operaciones['razon_social'].'</b></td>
					<td>'.$operaciones['id_operacion'].'</td>
					<td>'.$operaciones['nombre'].'</td>
					<td>'.$operaciones['producto'].'</td>
					<td><span class="n'.($operaciones['estado']=='enviado'?'Recibido':($vehiculo['estado']=='asignado'?'Asignado':'Finalizado')).'"></span></td>			
				</tr>';
		?>
			<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#"+categoria+"").append(contenido);
			</script>
			<?php 		
			
		}
		
	?>
	
	<!-- asignaciones a inspectores -->
	<?php 
	
		$cu = new ControladorUsuarios();
		
		$area = $cu->obtenerAreaUsuario($conexion, $_SESSION['usuario']);
		
		$inspectores = $cu->obtenerUsuariosXareaPerfil($conexion, pg_fetch_result($area, 0, 'id_area'), 'Inspector Técnico');
		
		
		while($operaciones = pg_fetch_assoc($inspectores)){
			echo'
					<table id="'.$operaciones['identificador'].'">
						<thead>
							<tr>
								<th>'.$operaciones['apellido'].', '.$operaciones['nombre'].'</th>
							</tr>
							<tr>
								<th>#</th>
								<th>RUC</th>
								<th>#Operación</th>
								<th>Tipo de Operación</th>
								<th>Producto</th>
								<th>Estado</th>
							</tr>
						</thead>
					</table>
			';
			}
		
		$res = $cr->listarOperacionesRevisionProvinciaAsignadas($conexion, $_SESSION['nombreProvincia']);
		$contador = 0;
		while($operaciones = pg_fetch_assoc($res)){
			$categoria1 = $operaciones['identificador_inspector'];

			$contenido1 = '
					<tr 
						id="'.$operaciones['id_operacion'].'"
						class="item"
						data-rutaAplicacion="registroOperador"
						data-opcion="abrirOperacionEnviada" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$operaciones['razon_social'].'</b></td>
					<td>'.$operaciones['id_operacion'].'</td>
					<td>'.$operaciones['nombre'].'</td>
					<td>'.$operaciones['producto'].'</td>
					<td><span class="n'.($operaciones['estado']=='enviado'?'Recibido':($vehiculo['estado']=='asignado'?'Asignado':'Finalizado')).'"></span></td>			
				</tr>';
		?>
			<script type="text/javascript">
					var contenido1 = <?php echo json_encode($contenido1);?>;
					var categoria1 = <?php echo json_encode($categoria1);?>;
					$("#"+categoria1+"").append(contenido1);
			</script>
			<?php 		
			
		}
		
	?>
	
	
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#COMISION tbody tr").length == 0 ? $("#COMISION").remove():"";
	});

	$("#_eliminar").click(function(){
		if($("#cantidadItemsSeleccionados").text()>1){
				alert('Por favor seleccione una operación a la vez');
				return false;
			}
	});
	
</script>
</html>