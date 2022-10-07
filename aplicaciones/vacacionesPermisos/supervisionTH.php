<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorVacaciones.php';

$conexion = new Conexion();	
$cc = new ControladorCatalogos();
$cv = new ControladorVacaciones();

if($_POST['fecha_desde']!='' )
	$fecha_desde=$_POST['fecha_desde'];
if($_POST['fecha_hasta']!='')
	$fecha_hasta=$_POST['fecha_hasta'];
$estado_requerimiento='Aprobado';
if($_POST['estado_requerimiento'])
$estado_requerimiento=$_POST['estado_requerimiento'];

$contador = 0;
$itemsFiltrados[] = array();

	
	$res = $cv->obtenerSolicitudes ($conexion,'',$fecha_desde,$fecha_hasta,'','','',$estado_requerimiento);
	while($fila = pg_fetch_assoc($res)){
		$opcionAbrir="paginaAbrir";	
		$itemsFiltrados[] = array('<tr
				id="'.$fila['id_permiso_empleado'].'"
				class="item"
				data-rutaAplicacion="vacacionesPermisos"
				data-opcion="'.$opcion_abrir.'"
				ondragstart="drag(event)"
				draggable="true"
				data-destino="detalleItem">
				<td>'.++$contador.'</td>
				<td style="white-space:nowrap;"><b>'.$fila['apellido'].' '.$fila['nombre'].'</b></td>
       			<td> Desde: '.$fila['fecha_inicio'].'<br/> Hasta: '.$fila['fecha_fin'].'</td>
				<td>'.$fila['estado'].'</td>
			</tr>');
	}

?>
<header>
	<h1>Administración capacitación</h1>
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
		<nav>
		<form id="filtrar" data-rutaAplicacion="vacacionesPermisos" data-opcion="supervisionTH" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<table class="filtro" style='width: 400px;'>
					<tbody>
					<tr>
						<th colspan="3">Buscar Capacitación:</th>
					</tr>
						<tr>
						<td>Provincia:</td>
						<td> 
								<select id="provincia" name="provincia">
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
						<td>Fecha inicio:</td>
						<td> <input id="fecha_desde" type="text" name="fecha_desde" maxlength="10" value="<?php echo $_POST['fecha_desde'];?>">	</td>
					</tr>
					<tr>
						<td>Fecha inicio:</td>
						<td> <input id="fecha_hasta" type="text" name="fecha_hasta" maxlength="10" value="<?php echo $_POST['fecha_hasta'];?>">	</td>
					</tr>
					<tr>
						<td>Estado:</td>
						<td> <select
						name="estado_requerimiento" id="estado_requerimiento">
						<option value="">Seleccione un estado....</option>
						<option value="Aprobado">Aprobados por jefe inmediato</option>
						
						</select></td>
					</tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="5"> <button id='buscar'>Buscar</button>	</td>
					</tr>
					</tbody>
					</table>
				</form>
</nav>
</header>
	
 <div id="paginacion" class="normal">
 </div>
 
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Funcionario</th>
			<th>Fechas</th>
			<th>Estado</th>				
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
					
	});

	$( "#fecha_desde" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      yearRange: '-100:+0'
	});
	$( "#fecha_hasta" ).datepicker({
	      changeMonth: true,
	      changeYear: true,
	      yearRange: '-100:+0'
	});

	$("#filtrar").submit(function(event){
		event.preventDefault();
		
		if( $('#fecha_desde').val().length!=0 ||$('#fecha_hasta').val().length!=0 || $('#estado_requerimiento').val().length!=0)
		{		
			abrir($('#filtrar'),event, false);

		}
		
	});

</script>

