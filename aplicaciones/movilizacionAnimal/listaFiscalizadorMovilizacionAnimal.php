<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
require_once '../../clases/ControladorAplicaciones.php';
$conexion = new Conexion();
$va = new ControladorMovilizacionAnimal();

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
			<form id="filtrarFiscalizacionMovilizacionAnimal" data-rutaAplicacion="movilizacionAnimal" data-opcion="listaFiscalizadorMovilizacionAnimal" data-destino="areaTrabajo #listadoItems" >
				<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<table class="filtro" style='width: 500px;' >
					<tbody>
					<tr>
						<th colspan="5">Buscar certificado de movilizacion para fiscalizar</th>					
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
						<th>Estado:</th>
						<th>
							<input type="radio" name="estado" id="estado1" value="2">Fiscalizado
						</th>
						<th colspan="2">
							<input type="radio" name="estado" id="estado2" value="1">No fiscalizado					
						</th>
					</tr>			
					<tr>
						<td colspan="3" id="mensajeError"></td>
						<td colspan="1"> <button id='buscar'>Buscar</button></td>
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
				<th>Lugar Emisión</th>
				<th>Sitio Origen</th>			
				<th>Sitio Destino</th>
				<th>F.Registro</th>	
			</tr>
		</thead>
		<?php 			
		    if($_POST['numeroCertificado']=='')
		    	$numeroCertificado = "0";
		  else
		    	$numeroCertificado = $_POST['numeroCertificado'];
		   
		    if($_POST['fechaInicio']=='')
		    	$fechaInicio = "0";
		    else
		    	$fechaInicio = $_POST['fechaInicio'];
		    
		    if($_POST['fechaFin']=='')
		    	$fechaFin = "0";
		    else
		    	$fechaFin = $_POST['fechaFin'];
		    
		    if($_POST['estado']=='')
		    	$estado = "0";
		   else
		    	$estado =  $_POST['estado'];
		   		   
	
			$contador = 0;
			$itemsFiltrados[] = array();
						
			$res = $va-> listaFiscalizacionMovilizacionAnimal($conexion, $_SESSION['usuario'], $numeroCertificado, $fechaInicio, $fechaFin, $estado);
			if(pg_num_rows($res) == 0){
				echo 'No hay registros.';
			}else{
				while($fila = pg_fetch_assoc($res)){	
		       	echo '<tr
						id="'.$fila['id_movilizacion_animal'].'"
						class="item"
						data-rutaAplicacion="movilizacionAnimal"
						data-opcion="abrirFiscalizadorMovilizacionAnimal"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td>No.'.$fila['numero_certificado'].'</td>
		       			<td>'.$fila['lugar_emision'].'</td>
						<td>'.$fila['sitio_origen'].'</td>
						<td>'.$fila['sitio_destino'].'</td>
						<td>'.$fila['fecha_registro'].'</td>
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
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un certificado para revisarlo.</div>');
				
	 });
		
		$("#filtrarFiscalizacionMovilizacionAnimal").submit(function(event){    	
			event.preventDefault();					
			if(($('#numeroCertificado').val()!='') || (($('#fechaInicio').val()!='') && ($('#fechaFin').val()!='')))
				abrir($('#filtrarFiscalizacionMovilizacionAnimal'),event, false);
			else
				$('#mensajeError').html('<span colspan="4" class="alerta">Por favor llene los campos de búsqueda</span>');		
		});	
	</script>