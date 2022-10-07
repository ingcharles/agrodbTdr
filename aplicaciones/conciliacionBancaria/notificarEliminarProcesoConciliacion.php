<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$idProcesoConciliacion = $_POST['elementos'];

$qProcesoConciliacion = $cb -> abrirProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idProcesoConciliacion);
$procesoConciliacion= pg_fetch_assoc($qProcesoConciliacion);

?>
<div id="estado"></div>

<p>El <b>proceso de conciliación</b> a ser eliminado es: </p>
		
<fieldset id="informacionProceso">
	<legend>Información de Proceso</legend>
	<div data-linea="1">
		<label>Nombre de proceso: </label><?php echo $procesoConciliacion['nombre_registro_proceso_conciliacion'];?>
	</div>
	<hr>
	<div data-linea="2">
		<label>Año: </label><?php echo $procesoConciliacion['anio_proceso_conciliacion'];?>
	</div>
	<div data-linea="2">
		<label>Mes: </label><?php echo $procesoConciliacion['mes_proceso_conciliacion'];?>
	</div>	
	<div data-linea="2">
		<label>Día: </label><?php echo $procesoConciliacion['dia_proceso_conciliacion'];?>
	</div>		
</fieldset>
		
<form id="notificarEliminarProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarProcesoConciliacion" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" id="idProcesoConciliacion" name="idProcesoConciliacion" value="<?php echo $idProcesoConciliacion;?>" />
	<button id="eliminar" type="submit" class="eliminar" >Eliminar documento</button>	
</form>


<script type="text/javascript">

$(document).ready(function(){	
	distribuirLineas();	  
});

$("#notificarEliminarProcesoConciliacion").submit(function(event){
	  	event.preventDefault();
		ejecutarJson($(this));
});

</script>