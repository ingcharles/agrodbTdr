<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorRequisitos.php';
	
	$conexion = new Conexion();
		
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Uso</h1>
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
	
	
<table id="Plaguicida">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarListaUsoPlaguicida" for="listaUsoPlaguicida" type="checkbox" checked />
					<label id="listaUsoPlaguicida">Plaguicidas</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre científico</th>
			<th>Nombre común</th>
			
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<!-- table id="Veterinario">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarListaUsoVeterinario" for="listaUsoVeterinario" type="checkbox" checked />
					<label id="listaUsoVeterinario">Veterinarios</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre científico</th>
			<th>Nombre común</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table-->

<table id="Fertilizante">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarListaUsoFertilizante" for="listaUsoFertilizante" type="checkbox" checked />
					<label id="listaUsoFertilizante">Fertilizantes</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre científico</th>
			<th>Nombre común</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<table id="PlantasAutoconsumo">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarListaPlantasAutoconsumo" for="listaPlantaAutoconsumo" type="checkbox" checked />
					<label id="listaUsoFertilizante">Plantas de autoconsumo</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre científico</th>
			<th>Nombre común</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
	

	<?php  
		$cr = new ControladorRequisitos();
		$res = $cr->listarUsoArea($conexion);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			
			switch ($fila['id_area']){
				case 'IAP':
					$categoria = 'Plaguicida';
					break;
				/*case 'IAV':
					$categoria = 'Veterinario';
					break;*/
				case 'IAF':
					$categoria = 'Fertilizante';
					break;
				case 'IAPA':
					$categoria = 'PlantasAutoconsumo';
					break;
			}
			/*if($fila['id_area']=='IAP'){
				$categoria = 'Plaguicida';
			}else if ($fila['id_area']=='IAV'){
				$categoria = 'Veterinario';
			}else{
				$categoria = 'Fertilizante';
			}*/
				
			$contenido = '<tr 
								id="'.$fila['id_uso'].'"
								class="item"
								data-rutaAplicacion="registroProducto"
								data-opcion="abrirUso" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td>'.$fila['nombre_uso'].'</td>
							<td>'.$fila['nombre_comun_uso'].'</td>
							
					</tr>';
    ?>
	<script type="text/javascript">
			var contenido = <?php echo json_encode($contenido);?>;
			var categoria = <?php echo json_encode($categoria);?>;
			$("#"+categoria+" tbody").append(contenido);
	</script>
	<?php 				
		}
	?>	
</body>
<script>
$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	$("#Plaguicida tbody tr").length == 0 ? $("#Plaguicida").remove():"";
	//$("#Veterinario tbody tr").length == 0 ? $("#Veterinario").remove():"";
	$("#Fertilizante tbody tr").length == 0 ? $("#Fertilizante").remove():"";
	$("#PlantasAutoconsumo tbody tr").length == 0 ? $("#PlantasAutoconsumo").remove():"";
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una requisito para revisarlo.</div>');
});

$("#mostrarListaUsoPlaguicida").change(function () {
    if ($(this).is(':checked'))
        $("#Plaguicida tbody tr").show();
    else
        $("#Plaguicida tbody tr").hide();
});

/*$("#mostrarListaUsoVeterinario").change(function () {
    if ($(this).is(':checked'))
        $("#Veterinario tbody tr").show();
    else
        $("#Veterinario tbody tr").hide();
});*/

$("#mostrarListaUsoFertilizante").change(function () {
    if ($(this).is(':checked'))
        $("#Fertilizante tbody tr").show();
    else
        $("#Fertilizante tbody tr").hide();
});

$("#mostrarPlantasAutoconsumo").change(function () {
    if ($(this).is(':checked'))
        $("#PlantasAutoconsumo tbody tr").show();
    else
        $("#PlantasAutoconsumo tbody tr").hide();
});


</script>
</html>