<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();		
?>

<header>
		<h1>Aditivo de Importancia Toxicológica</h1>
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
			<th>Nombre químico</th>
			<th>Nombre común</th>
			
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<table id="Veterinario">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarListaUsoVeterinario" for="listaUsoVeterinario" type="checkbox" checked />
					<label id="listaUsoVeterinario">Veterinarios</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Nombre químico</th>
			<th>Nombre común</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

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
			<th>Nombre químico</th>
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
			<th>Nombre químico</th>
			<th>Nombre común</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
	

	<?php  
		$cc = new ControladorCatalogos();
		$res = $cc->listarAditivos($conexion);
		$contador = 0;
		
		while($fila = pg_fetch_assoc($res)){
			
			switch ($fila['area']){
				case 'IAP':
					$categoria = 'Plaguicida';
					break;
				case 'IAV':
					$categoria = 'Veterinario';
					break;
				case 'IAF':
					$categoria = 'Fertilizante';
					break;
				case 'IAPA':
					$categoria = 'PlantasAutoconsumo';
					break;
			}
				
			$contenido = '<tr 
								id="'.$fila['id_aditivo_toxicologico'].'"
								class="item"
								data-rutaAplicacion="registroProducto"
								data-opcion="abrirAditivo" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td>'.$fila['nombre_quimico'].'</td>
							<td>'.$fila['nombre_comun'].'</td>
							
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
    	$("#Veterinario tbody tr").length == 0 ? $("#Veterinario").remove():"";
    	$("#Fertilizante tbody tr").length == 0 ? $("#Fertilizante").remove():"";
    	$("#PlantasAutoconsumo tbody tr").length == 0 ? $("#PlantasAutoconsumo").remove():"";
    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un elemento para revisarlo.</div>');
    });
    
    $("#mostrarListaUsoPlaguicida").change(function () {
        if ($(this).is(':checked'))
            $("#Plaguicida tbody tr").show();
        else
            $("#Plaguicida tbody tr").hide();
    });
    
    $("#mostrarListaUsoVeterinario").change(function () {
        if ($(this).is(':checked'))
            $("#Veterinario tbody tr").show();
        else
            $("#Veterinario tbody tr").hide();
    });
    
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