<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';
require_once '../../clases/ControladorAplicaciones.php';
$conexion = new Conexion();	

?>
<header>
	<h1>Lista fiscalización</h1>
	<nav>
		<?php
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			while($fila = pg_fetch_assoc($res)){
				if($fila['estilo']!='_nuevo'){
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
<header>
		<nav>
			<form id="filtrarFiscalizacion" data-rutaAplicacion="vacunacionAnimal" data-opcion="listaFiscalizador" data-destino="areaTrabajo #listadoItems" >
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<!-- <input type="hidden" id="estado" name="estado" value="0" />  -->
			<table class="filtro" style='width: 100%;' >
				<tbody>
				<tr>
					<th colspan="4">Buscar certificado de vacunación para fiscalizar</th>					
				</tr>
				<tr>
					<th>No.Certificado:</th>
					<th colspan="3"><input id="numeroCertificado" type="text"  size="49%" name="numeroCertificado"></th>
				</tr>
				<tr>
					<th>Fecha inicio:</th>
					<th><input id="fechaInicio" type="text" size="16%" name="fechaInicio"></th>
					<th>Fecha fin:</th>
					<th><input id="fechaFin" type="text" size="16%" name="fechaFin"></th>					
				</tr>
				<tr>
					<th>Estado:</th>
					<th>
						<input type="radio" name="estado" id="estado1" value="2">Fiscalizado
					</th>
					<th></th>
					<th >
						<input type="radio" name="estado" id="estado2" value="1">No fiscalizado					
					</th>
				</tr>			
				<tr>					
					<td colspan="4"><button type="submit">Buscar certificados de vacunación</button></td>
				</tr>
				<tr>
					<td colspan="4"  align="center" id="estado" ></td>
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
				<th>No. Certificado</th>
				<th>Sitio/Área</th>
				<th>Nombre digitador</th>			
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
		    
		    if($_POST['estado']=='')
		    	$estado = "0";
		    if($_POST['estado']!='')
		    	$estado =  $_POST['estado'];
		   		   
			$conexion = new Conexion();
			$ppc = new ControladorVacunacionAnimal();
			$contador = 0;
			$itemsFiltrados[] = array();
						
			$res = $ppc-> listaVacunacionFiscalizacion($conexion, $_SESSION['usuario'], $numeroCertificado, $fechaInicio, $fechaFin, $estado);
			if(pg_num_rows($res) == 0){
				echo 'No hay registros.';
			}else{
				while($fila = pg_fetch_assoc($res)){	
		       	echo '<tr
						id="'.$fila['id_vacuna_animal'].'"
						class="item"
						data-rutaAplicacion="vacunacionAnimal"
						data-opcion="abrirFiscalizador"
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
		$("#estado2").attr('checked', true);	
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#numeroCertificado').numeric();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un certificado para revisarlo.</div>');
				
	 });

	 $("#filtrarFiscalizacion").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#numeroCertificado").val()=="" && ($('#fechaInicio').val()=="") && ($('#fechaFin').val()=="") ){
			error = true;
			$("#numeroCertificado").addClass("alertaCombo");
			$("#fechaInicio").addClass("alertaCombo");
			$("#fechaFin").addClass("alertaCombo");
		}
	
		if (error){
			$("#estado").html("Por favor digite los criterios de búsquedas.").addClass('alerta');
			event.preventDefault();
		}else{                
			$("#estado").html("").removeClass('alerta');  
			abrir($('#filtrarFiscalizacion'),event, false); 
		}
	});

</script>