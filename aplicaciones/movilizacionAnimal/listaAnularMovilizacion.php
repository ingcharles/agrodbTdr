<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
require_once '../../clases/ControladorAplicaciones.php';
$conexion = new Conexion();	
?>
<header>
	<h1>Lista de anulaciones y traspasos</h1>
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
			<form id="filtrarAnulacion" data-rutaAplicacion="movilizacionAnimal" data-opcion="listaAnularMovilizacion" data-destino="areaTrabajo #listadoItems" >
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro" style='width: 500px;' >
				<tbody>
				<tr>
					<th colspan="5">Buscar certificado de movilización para anular</th>					
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
					<td colspan="5"> 
						<button id='buscar'>Buscar certificado de movilización</button>
					</td>
				</tr>
				<tr>
					<td colspan="5" id="mensajeError" align="center"></td>
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
				<th>Numero certificado</th>
				<th>Sitio Origen</th>
				<th>Sitio Destino</th>							
				<th>Estado certificado</th>
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
		$cm = new ControladorMovilizacionAnimal();
		$contador = 0;
		$itemsFiltrados[] = array();
			
		$rese=$cm->listaMovilizacionAnulacionEmpresa($conexion, $_SESSION['usuario']);

		$idOperadorEmpresa=array();
		while($fil = pg_fetch_assoc($rese)){
			$idOperadorEmpresa[]=$fil['identificador_empresa'];
		}

		$identificadorEmpresa = implode("','", $idOperadorEmpresa);
	
			$res = $cm-> listaFiltroAnulacion($conexion, $identificadorEmpresa, $numeroCertificado, $fechaInicio, $fechaFin);

			if(pg_num_rows($res) == 0){
				echo 'No hay registros.';
			}else{
				while($fila = pg_fetch_assoc($res)){	
		       	echo '<tr
						id="'.$fila['id_movilizacion_animal'].'"
						class="item"
						data-rutaAplicacion="movilizacionAnimal"
						data-opcion="abrirAnularMovilizacion"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
						<td>'.++$contador.'</td>
						<td><b>No.'.$fila['numero_certificado'].'</b></td>
		       			<td align="center">'.$fila['nombre_sitio_origen'].'</td>
						<td align="center">'.$fila['nombre_sitio_destino'].'</td>						
						<td align="center">'.$fila['estado'].'</td>
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
		$("#trFecha").hide();
		$("#trCertificado").hide();		
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#numeroCertificado').ForceNumericOnly();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un certificado para revisarlo.</div>');
				
	 });

	 $("#filtrarAnulacion").submit(function(event){    	
			event.preventDefault();					
			if(($('#numeroCertificado').val()!='') || (($('#fechaInicio').val()!='') && ($('#fechaFin').val()!='')))
			{		
				abrir($('#filtrarAnulacion'),event, false);
			}
			else
			{			
				$('#mensajeError').html('<span colspan="5" class="alerta">Seleccione correctamente los criterios de búsqueda</span>');
			}			
		});	

</script>
