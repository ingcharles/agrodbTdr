<?php
session_start ();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorEmpleados.php';

$conexion = new Conexion ();
// $conexion->verificarSesion();

$ca = new ControladorAreas ();
$ce = new ControladorEmpleados();

$areas = $ca->listarAreas ( $conexion );
$auditoresInternos = $ce->obtenerUsuariosPorPerfil($conexion,'Auditor interno');

?>

<header>
	<h1>Nuevo hallazgo</h1>
</header>

<div id="estado"></div>

<form id="nuevaConformidad" data-rutaAplicacion="gestionCalidad"
	data-opcion="guardarHallazgo" data-destino="detalleItem">
	<fieldset>
		<legend>Detalle del hallazgo</legend>
		<div data-linea="1">
			<label for="area">Área</label> <select id="area" name="area">
			<?php
			while ( $area = pg_fetch_assoc ( $areas ) ) {
				echo '<option value="' . $area ['id_area'] . '">' . $area ['nombre'] . '</option>';
			}
			?>
			</select>
		</div>
		<div data-linea="2">
			<label for="tipo">Tipo de hallazgo</label> <select id="tipo"
				name="tipo">
				<option value="Observación">Observación</option>
				<option value="No conformidad">No conformidad</option>
			</select>
		</div>
		<div data-linea="2">
			<label for="fecha">Fecha de reporte de auditoría</label> <input
				id="fecha" name="fecha" />
		</div>
		<hr />
		<div data-linea="3">
			<label for="hallazgo">Hallazgo</label>
		</div>
		<div data-linea="4">

			<textarea name="hallazgo" rows="5"></textarea>
		</div>
		<div data-linea="5">
			<label for="norma">Norma y clausula</label>
		</div>
		<div data-linea="6">

			<textarea name="norma" rows="5"></textarea>
		</div>
		<hr />
		<div data-linea="10">
			<label for="auditor">Auditor</label>
		</div>
		<div data-linea="11">
			<input type="radio" name="tipoAuditor" value="Interno" checked="checked">Interno <select
				name="auditorInterno">
				<?php
			while ($auditorInterno = pg_fetch_assoc ($auditoresInternos)) {
				echo '<option value="' . $auditorInterno['apellido'] . ', ' . $auditorInterno ['nombre'] . '"> ' . $auditorInterno['apellido'] . ', ' . $auditorInterno ['nombre'] . ' </option>';
			}
			?>
				</select>
		</div>
		<div data-linea="11">
			<input type="radio" name="tipoAuditor" value="Externo">Externo <input
				name="auditorExterno" />
		</div>
	</fieldset>

	<button type="submit" class="guardar">Guardar</button>
</form>
<script type="text/javascript">

	$("document").ready(function(){
		distribuirLineas();
		$( "#fecha" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
	});

	$("#nuevaConformidad").submit(function(event){
		abrir($(this),event,false);
	});
	
</script>
