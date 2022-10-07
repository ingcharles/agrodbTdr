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
			<form id="filtrar" data-rutaAplicacion="vacunacionAnimal" data-opcion="listaVacunacionAnimales" data-destino="areaTrabajo #listadoItems" >
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 500px;' >
				<tbody>
				<tr>
					<th colspan="2">Buscar certificado de vacunación</th>					
				</tr>
				<tr>
					<th>No.Certfificado:</th>
					<th><input id="numeroCertificado" type="text" name="numeroCertificado"></th>
				</tr>
				<tr>
					<th>Fecha inicio:</th>
					<th><input id="fechaInicio" type="text" name="fechaInicio"></th>
					<th>Fecha fin:</th>
					<th><input id="fechaFin" type="text" name="fechaFin"></th>					
				</tr>				
				<tr>
					<td id="mensajeError"></td>
					<td colspan="5"> <button id='buscar'>Buscar</button></td>
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
				<th>Op.Vacunador</th>
				<th>Nombre sitio</th>
				<th>Distribuidor</th>			
				<th>F.Vacunación</th>	
			</tr>
		</thead>
		<?php 			
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
						
			$res = $ppc->listaVacunacionAnimal($conexion, $_SESSION['usuario'], $numeroCertificado, $fechaInicio, $fechaFin);
			if(pg_num_rows($res) == 0){// && $identificador !='' && pg_num_rows($qDatosPersonales)== 0){
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
						<td>'.$fila['nombre_administrador'].'</td>
		       			<td>'.$fila['nombre_sitio'].' - '.$fila['nombre_area'].'</td>
						<td>'.$fila['nombre_distribuidor'].'</td>
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
		$("#trFecha").hide();
		$("#trCertificado").hide();
		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#identificador').ForceNumericOnly();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un contrato para revisarlo.</div>');		
	});
	
    $("#filtrar").submit(function(event){    	
		event.preventDefault();		
		if($('#identificador').val()!='')
		{		
			abrir($('#filtrar'),event, false);
		}
		else
		{
			if($('#identificador').val()!='')
			{
				$('#mensajeError').html('<span class="alerta">Error </span>');
			}
		}
		
	});	

</script>
