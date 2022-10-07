<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorAdministracionDeTrampas.php';
	
$conexion = new Conexion();	
$cc = new ControladorCatalogos();
$cat = new ControladorAdministracionDeTrampas();

$identificadorOPerador = $_SESSION['usuario'];

$contador = 0;
$itemsFiltrados[] = array();

if(isset($_POST['bNombreAreaTrampa']) || isset($_POST['bCodigoTrampa'])||isset($_POST['bEstadoTrampa'])||isset($_POST['bProvincia'])||isset($_POST['bFechaInicioTrampa'])||isset($_POST['bFechaFinTrampa'])){

	$qAdministracionTrampa = $cat->obtenerListaAdministracionTrampas($conexion, $_POST['bNombreAreaTrampa'], $_POST['bCodigoTrampa'], $_POST['bEstadoTrampa'], $_POST['bProvincia'], $_POST['bFechaInicioTrampa'], $_POST['bFechaFinTrampa']);
	
	while($administracionTrampa = pg_fetch_assoc($qAdministracionTrampa)){
		$itemsFiltrados[] = array('<tr id="'.$administracionTrampa['id_administracion_trampa'].'"
				class="item"
				data-rutaAplicacion="administracionDeTrampas"
				data-opcion="abrirAdministracionDeTrampas"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td>'.$administracionTrampa['nombre_area_trampa'].'</td>
				<td>'.$administracionTrampa['codigo_trampa'].'</td>
				<td>'.$administracionTrampa['estado_trampa'].'</td>
				<td>'.$administracionTrampa['nombre'].'</td>
				<td>'.$administracionTrampa['fecha_registro'].'</td>
				</tr>');
	}

}
?>
<header>
	<h1>Lista Administración de Trampas</h1>

	<nav>
		<form id="listaAdministracionDeTrampas" data-rutaAplicacion="administracionDeTrampas"
			data-opcion="listaAdministracionDeTrampas"
			data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />

			<table class="filtro">
				<tbody>
					<tr>
						<th colspan="4">Buscar:</th>
					</tr>
					<tr >
						<th>Nombre de área:</th>
						<td>
						<select id="bNombreAreaTrampa" name="bNombreAreaTrampa" style ="width: 100%;">
							<option value="" >Seleccione...</option>
							<?php 
							$qAreaTrampa = $cc-> listarAreasTrampas($conexion);
							while ($areaTrampa = pg_fetch_assoc($qAreaTrampa)){
							    echo '<option value="'.$areaTrampa['id_area_trampa']. '">'. $areaTrampa['nombre_area_trampa'] .'</option>';
							}
							?>
						</select>
						</td>
	
						<th>Código de trampa:</th>
						<td>
							<input id="bCodigoTrampa" type="text" name="bCodigoTrampa" maxlength="128" style ="width: 100%;" >
						</td>
					</tr>
					<tr>
						<th>Estado:</th>
						<td>
						<select id="bEstadoTrampa" name="bEstadoTrampa" style ="width: 100%;" >
							<option value="">Seleccione...</option>
							<option value="activo">Activa</option>
							<option value="inactivo">Inactiva</option>
						</select>
						</td>
						<th>Provincia:</th>
						<td>
						<select id="bProvincia" name="bProvincia" style ="width: 100%;" >
							<option value="">Provincia....</option>
							<?php 
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provincia){
									echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
								}
							?>
						</select>
						</td>
					</tr>
				<tr>
				<th>F. Inicio:</th>
					<td> 
						<input id="bFechaInicioTrampa" type="text" name="bFechaInicioTrampa" maxlength="128">
					</td>
				<th>F. Fin:</th>
					<td >  
						<input id="bFechaFinTrampa" type="text" name="bFechaFinTrampa" maxlength="128"> 
					</td>
				</tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="4">
							<button id="buscar">Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>

	</nav>

	<nav>
		<?php			
		$contador = 0;
		$itemsFiltrados[] = array();
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

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Área</th>
			<th>Código trampa</th>
			<th>Estado</th>
			<th>Provincia</th>
			<th>Fecha</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	});

	$("#bFechaInicioTrampa").datepicker({
	    changeMonth: true,
	    changeYear: true,
	    onSelect: function(dateText, inst) {
   		 $('#bFechaFinTrampa').datepicker('option', 'minDate', $("#bFechaInicioTrampa" ).val()); 
       } 
	});

	$("#bFechaFinTrampa").datepicker({
	    changeMonth: true,
	    changeYear: true
	});


	$("#listaAdministracionDeTrampas").submit(function(event){
	        
   		event.preventDefault();
    	$(".alertaCombo").removeClass("alertaCombo");
  		var error = false;  		
  		
		if($("#bNombreAreaTrampa").val()==""){	
			error = true;		
			$("#bNombreAreaTrampa").addClass("alertaCombo");
		}

		if($("#bProvincia").val()==""){	
			error = true;		
			$("#bProvincia").addClass("alertaCombo");
		}

		if (!error){
			event.preventDefault();
			abrir($('#listaAdministracionDeTrampas'),event, false);                          
		}
	});

	
</script>



