<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorAplicaciones.php';
$conexion = new Conexion();

?>
<header>
	<h1>Contratos</h1>
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
<header>
		<nav>
			<form id="filtrar" data-rutaAplicacion="vacunacionAnimal" data-opcion="listaVacunacionAnimales" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 100%;' >
				<tbody>
				<tr>
					<th colspan="4">Buscar certificado de vacunación</th>					
				</tr>
				<tr >
					<th>Identificación:</th>
					<th colspan="3" ><input id="identificadorOperador" type="text" size="50%" name="identificadorOperador"></th>
				</tr>
				<tr>
					<th>No.Certfificado:</th>
					<th colspan="3" ><input id="numeroCertificado" type="text" size="50%" name="numeroCertificado"></th>
				</tr>
				<tr>
					<th>Fecha inicio:</th>
					<th><input id="fechaInicio" type="text" size="16%" name="fechaInicio"></th>
					<th>Fecha fin:</th>
					<th><input id="fechaFin" type="text" size="16%" name="fechaFin"></th>					
				</tr>				
				<tr>					
					<td colspan="4"><button type="submit">Buscar certificados de vacunación</button></td>
				</tr>
				<tr>
					<td colspan="4" id="estado1" align="center"></td>
				</tr>
				</tbody>
				</table>
			</form>
		</nav>
</header>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>No.Certificado</th>
				<th>Sitio/Área</th>
				<th>Nombre digitador</th>			
				<th>F.Vacunación</th>	
			</tr>
		</thead>
		<?php

			if($_POST['identificadorOperador']=='')
				$identificadorOperador = "0";
			
			if($_POST['identificadorOperador']!='')
				$identificadorOperador = $_POST['identificadorOperador'];
			
		    if($_POST['numeroCertificado']=='')
		    	$numeroCertificado = "0";
		    
		    if($_POST['numeroCertificado']!='')
		    	$numeroCertificado = $_POST['numeroCertificado'];
		   
		    if($_POST['fechaInicio']=='')
		    	$fechaInicio = "0";
		    
		    if($_POST['fechaInicio']!='')
		    	$fechaInicio = $_POST['fechaInicio'];
		    
		    if($_POST['fechaFin']=='')
		    	$fechaFin = "0";
		    
		    if($_POST['fechaFin']!='')
		    	$fechaFin = $_POST['fechaFin'];
		   
			$conexion = new Conexion();
			$ppc = new ControladorVacunacionAnimal();
			$contador = 0;
			$itemsFiltrados[] = array();
						
			$res = $ppc->listaVacunacionAnimalTodos($conexion,$identificadorOperador, $numeroCertificado, $fechaInicio, $fechaFin);
			
			if(pg_num_rows($res) == 0){
				echo 'No hay registros.';
			}else{
				while($fila = pg_fetch_assoc($res)){	
		       	echo '<tr
						id="'.$fila['id_vacuna_animal'].'"
						class="item"
						data-rutaAplicacion="vacunacionAnimal"
						data-opcion="abrirVacunacion"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td>'.$fila['num_certificado'].'</td>
		       			<td>'.$fila['nombre_sitio'].' - '.$fila['nombre_area'].'</td>
						<td>'.$fila['nombre_digitador'].'</td>
						<td>'.$fila['fecha_vacunacion'].'</td>
					</tr>';
		       	}
		   }
		?>
	</table>

<script>
	$(document).ready(function(){
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		});	
		$('#numeroCertificado').numeric();
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un certificado para revisarlo.</div>');		
	});

	
	$("#filtrar").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(($('#numeroCertificado').val()=="") && (($('#fechaInicio').val()=="") && ($('#fechaFin').val()=="") && ($('#identificadorOperador').val()==""))){
			error = true;
			$("#numeroCertificado").addClass("alertaCombo");
			$("#fechaInicio").addClass("alertaCombo");
			$("#fechaFin").addClass("alertaCombo");
			$("#identificadorOperador").addClass("alertaCombo");
		}
	
		if (error){
			$("#estado1").html("Por favor digite los criterios de búsquedas.").addClass('alerta');
			event.preventDefault();
		}else{                
			$("#estado1").html("").removeClass('alerta');  
			abrir($('#filtrar'),event, false); 
		}
	});
</script>
