<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorRequisitos.php';
	
	$conexion = new Conexion();
	$identificador = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Tipo Producto</h1>
		<nav>
		<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);
			
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
			<th colspan="6">
					<input id="mostrarListaPlaguicida" type="checkbox" checked />
					<label id="listaPlaguicida">Plaguicidas</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Ingrediente activo</th>
			<th>Ingrediente químico</th>
			<th>CAS</th>
			<th>Formula química</th>
			<th>Grupo químico</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<!-- table id="Veterinario">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarListaVeterinario" type="checkbox" checked />
					<label id="listaVeterinario">Veterinarios</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Ingrediente activo</th>
			<th>Ingrediente químico</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table-->

<table id="Fertilizante">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarListaFertilizante" type="checkbox" checked />
					<label id="listaFertilizante">Fertilizantes</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Ingrediente activo</th>
			<th>Ingrediente químico</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<table id="PlantasAutoconsumo">
	<thead>
		<tr>
			<th colspan="5">
					<input id="mostrarPlantasAutoconsumo" type="checkbox" checked />
					<label id="listaPlantasAutoconsumo">Plantas de autoconsumo</label>
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Ingrediente activo</th>
			<th>Ingrediente químico</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
	
	<?php  
		$cr = new ControladorRequisitos();
		$res = $cr->listarTipoIngredienteActivo($conexion, 1000, 0);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){

			switch ($fila['id_area']){
				case 'IAP':
					$categoria = 'Plaguicida';
					$datosAdicionales = '<td>'.$fila['cas'].'</td>
										 <td>'.$fila['formula_quimica'].'</td>
										 <td>'.$fila['grupo_quimico'].'</td>';
				break;
				/*case 'IAV':
					$categoria = 'Veterinario';
					$datosAdicionales = '';
				break;*/
				case 'IAF':
					$categoria = 'Fertilizante';
					$datosAdicionales = '';
				break;
				case 'IAPA':
					$categoria = 'PlantasAutoconsumo';
					$datosAdicionales = '';
				break;
			}
				
			$contenido = '<tr 
								id="'.$fila['id_ingrediente_activo'].'"
								class="item"
								data-rutaAplicacion="registroProducto"
								data-opcion="abrirIngredienteActivo" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td>'.$fila['ingrediente_activo'].'</td>
							<td>'.$fila['ingrediente_quimico'].'</td>
							'.$datosAdicionales.'
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

<div id="mensajeCargando"></div>

<script>
$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	//$("#Plaguicida tbody tr").length == 0 ? $("#Plaguicida").remove():"";
	//$("#Veterinario tbody tr").length == 0 ? $("#Veterinario").remove():"";
	//$("#Fertilizante tbody tr").length == 0 ? $("#Fertilizante").remove():"";
	$("#PlantasAutoconsumo tbody tr").length == 0 ? $("#PlantasAutoconsumo").remove():"";
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una requisito para revisarlo.</div>');

	incremento = 1000;
	datoIncremento = 1000;
	
});

$("#mostrarListaPlaguicida").change(function () {
    if ($(this).is(':checked'))
        $("#Plaguicida tbody tr").show();
    else
        $("#Plaguicida tbody tr").hide();
});

/*$("#mostrarListaVeterinario").change(function () {
    if ($(this).is(':checked'))
        $("#Veterinario tbody tr").show();
    else
        $("#Veterinario tbody tr").hide();
});*/

$("#mostrarListaFertilizante").change(function () {
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

sinDato = true;
$("#listadoItems").scroll(function(event){
	
	if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight-1) {

		event.preventDefault();
		event.stopImmediatePropagation();

		var data = new Array();

		 var $formulario = $(this);
		
    	data.push({
    		name : 'incremento',
    		value : incremento
    	}, {
    		name : 'datoIncremento',
    		value : datoIncremento
    	});
    	
    	url = "aplicaciones/registroProducto/cargarDatosIngredienteActivo.php";
    	if(sinDato){

        	if ($formulario.data('locked') == undefined || !$formulario.data('locked')){
        		resultado = $.ajax({
        		    url: url,
        		    type: "post",
        		    data: data,
        		    dataType: "json",
        		    async:   true,
        		    beforeSend: function(){
        		    	$("#estado").html('').removeClass();
        		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
        		    	$formulario.data('locked', true);
        			},
        			
        		    success: function(msg){
        		    	if(msg.estado=="exito"){
	        		    	if(msg.mensaje.length != 0){
	        		    		$(msg.mensaje).each(function(i){						
	        						$("#"+this.categoria+" tbody").append(this.contenido); 
	        		    	    });
	        		    	}else{
	        		    		sinDato = false;
	        			    }		    		
        		    	}else{
        		    		mostrarMensaje(msg.mensaje,"FALLO");
	        		    }
        		    		
        		   },
        		    error: function(jqXHR, textStatus, errorThrown){
        		    	$("#cargando").delay("slow").fadeOut();
        		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
        		    },
        	        complete: function(){
        	        	datoIncremento = datoIncremento +1000;    	        	
        	        	$("#cargando").delay("slow").fadeOut();
        	        	$formulario.data('locked', false);      
        	        }
        		});
            }
		}
	}	                        
});

</script>
</html>
