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

if(isset($_POST['bNombreAreaTrampa']) || isset($_POST['bCodigoTrampa'])||isset($_POST['bFechaTrampa'])){

$qAdministracionTrampa = $cat->obtenerListaHistoriaAdministracionTrampas($conexion, $_POST['bNombreAreaTrampa'], $_POST['bCodigoTrampa'], $_POST['bProvincia'], $_POST['bFechaTrampa']);

	while($administracionTrampa = pg_fetch_assoc($qAdministracionTrampa)){
		$itemsFiltrados[] = array('<tr id="'.$administracionTrampa['id_administracion_trampa'].'"
				class="item"
				data-rutaAplicacion="administracionDeTrampas"
				data-opcion="abrirHistoriaAdministracionDeTrampas"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td>'.$administracionTrampa['nombre_area_trampa'].'</td>
				<td>'.$administracionTrampa['codigo_trampa'].'</td>
				<td>'.$administracionTrampa['nombre'].'</td>
				</tr>');
	}

}

?>
<header>
	<h1>Lista Histórico Administración de Trampas</h1>

	<nav>
		<form id="listaHistoricoAdministracionDeTrampas" data-rutaAplicacion="administracionDeTrampas"
			data-opcion="listaHistoricoAdministracionDeTrampas"
			data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion"
				value="<?php echo $_POST['opcion']; ?>" />

			<table class="filtro">
				<tbody>
					<tr>
						<th colspan="3">Buscar:</th>
					</tr>
					<tr>
						<th>Nombre de área:</th>
						<td>
						<select id="bNombreAreaTrampa" name="bNombreAreaTrampa" style ="width: 100%;" >
							<option value="">Seleccione...</option>
							<?php 
							$qAreaTrampa = $cc-> listarAreasTrampas($conexion);
							while ($areaTrampa = pg_fetch_assoc($qAreaTrampa)){
							    echo '<option value="'.$areaTrampa['id_area_trampa']. '">'. $areaTrampa['nombre_area_trampa'] .'</option>';
							}
							?>
						</select>
						</td>
					</tr>
					<tr>
						<th>Provincia:</th>
						<td>
						<select id="bProvincia" name="bProvincia" style ="width: 100%;">
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
						<th>Código de trampa:</th>
						<td><input id="bCodigoTrampa" type="text" name="bCodigoTrampa" maxlength="128" style ="width: 100%;" >
						</td>
					</tr>					
					<tr>
						<th>Fecha:</th>
						<td><input id="bFechaTrampa" type="text" name="bFechaTrampa" maxlength="128" style ="width: 100%;" >
						</td>
					</tr>					
					<tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="5">
							<button id="buscar">Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>

	</nav>
</header>

<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Área</th>
			<th>Código trampa</th>
			<th>Provincia</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){		
    	distribuirLineas();	   
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	});	

	$("#bFechaTrampa").datepicker({
	      changeMonth: true,
	      changeYear: true
	}).datepicker('setDate');

	$("#listaHistoricoAdministracionDeTrampas").submit(function(event){
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
				abrir($('#listaHistoricoAdministracionDeTrampas'),event, false);                          
			}
	});
	
	
</script>
