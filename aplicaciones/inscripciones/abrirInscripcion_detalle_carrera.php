<?php
// session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorInscripciones.php';
// require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion ();
$ci = new ControladorInscripciones();
// $ca = new ControladorAuditoria();

// Validar sesion
// $conexion->verificarSesion();
$idInscripcion = htmlspecialchars ( $_POST ['id'], ENT_NOQUOTES, 'UTF-8' );

$inscripcion = pg_fetch_assoc($ci->abrirInscripcionCarrera($conexion, $idInscripcion));

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Inscripción a evento</h1>
	</header>

	<div id="estado"></div>

	<fieldset>
		<legend>Detalle del evento</legend>
		<div data-linea="0">
			<label for="evento">Evento</label> <span id="evento"
				><?php echo $inscripcion['nombre'];?></span>
		</div>
		
		<div data-linea="1">
			
			<?php echo $inscripcion['detalle'];?>
		</div>
		<hr />
		<div data-linea="2">
			<label for="fecha_inicio">Fecha</label> 
			<?php echo $inscripcion['fecha_inicio'] . ' - ' . $inscripcion['fecha_fin'];?>
		</div>
		<hr />
		<div data-linea="4">
			<img class="portada" src="<?php echo $inscripcion['ruta_portada'];?>"/>
		</div>
	</fieldset>

	<form id="guardarInscripcion" data-rutaAplicacion="inscripciones"
		data-opcion="guardarInscripcion_detalle_carrera" data-accionEnExito="ACTUALIZAR">
		<fieldset>
			<legend>Detalles de la inscripción</legend>
			<input name="inscripcion" type="hidden"
				value="<?php echo $idInscripcion;?>" />
			<div data-linea="1">
				<label for="equipo">Nombre del equipo</label>
				<input name="equipo" type="text"/>
			</div>
			<div data-linea="2">
				<label for="tipoCarrera">Tipo de carrera</label>
				<select name="tipoCarrera">
					<option value="Postas">Circuito postas 2,5K (en equipo)</option>
					<option value="Deportistas">Circuito deportistas 5k (individual)</option>
					<option value="Especiales">Especiales</option>
				</select>
			</div>
			<hr />
			<div data-linea="8">	
				<input name="estado" type="radio" value="Aceptar" id="estado1"><label for="estado1">Asistiré al evento</label>
				<br />
				<input name="estado" type="radio" value="Rechazar" id="estado2"><label for="estado2">No asistiré al evento</label>		
			</div>
			<hr />
			<div data-linea="9">
				<button type="submit" class="guardar">Guardar inscripción</button>
			</div>
		</fieldset>
	</form>
	</body>
<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();
});

$("#guardarInscripcion").submit(function(event){
	//$("#guardarInscripcion button").prop("disabled",true);
	event.preventDefault();
	ejecutarJson($(this));		
});

</script>
</html>