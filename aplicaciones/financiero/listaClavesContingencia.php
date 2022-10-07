<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorFinanciero.php';
	
	$conexion = new Conexion();
	$cf = new ControladorFinanciero();
	$lista = $cf->listarClavesContingencia($conexion);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Claves contingencia</h1>
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
			<th>Fecha inicio</th>
			<th>Fecha fin</th>
			<th>Observación</th>
		</tr>
	</thead>

	<?php 
	
		$contador = 0;
		while($fila = pg_fetch_assoc($lista)){
			echo '<tr 
					id="'.$fila['id_clave_contingencia'].'"
					class="item"
					data-rutaAplicacion="financiero"
					data-opcion="abrirClavesContingencia" 
					ondragstart="drag(event)" 
					draggable="true" 
					data-destino="detalleItem">
					<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
					<td>'.$fila['fecha_desde'].'</td>
					<td>'.$fila['fecha_hasta'].'</td>
					<td>'.$fila['observacion'].'</td>
				</tr>';
		}
	?>
</table>	

</body>

<script>
$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	
});

$("#_eliminar").click(function(){
	if($("#cantidadItemsSeleccionados").text()>1){
			alert('Por favor seleccione un item  a la vez');
			return false;
		}
	});
</script>
</html>