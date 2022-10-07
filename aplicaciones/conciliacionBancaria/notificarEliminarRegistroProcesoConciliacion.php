<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$idRegistroProcesoConciliacion = $_POST['elementos'];

$qRegistroProcesoConciliacion = $cb -> abrirRegistroProcesoConciliacionXIdRegistroProcesoConciliacion($conexion, $idRegistroProcesoConciliacion);
$registroProcesoConciliacion= pg_fetch_assoc($qRegistroProcesoConciliacion);

?>
<div id="estado"></div>

<p>El <b>registro de proceso de conciliación</b> a ser eliminado es: </p>
		
<fieldset id="informacionRegistroProceso">
	<legend>Información de Registro de Proceso</legend>
	<div data-linea="1">
		<label>Nombre de registro de proceso: </label><?php echo $registroProcesoConciliacion['nombre_registro_proceso_conciliacion'];?>
	</div>
	<hr>
	<div data-linea="2">
		<label>Facturas GUIA: </label><?php echo $registroProcesoConciliacion['factura_registro_proceso_conciliacion'];?>
	</div>
	<div data-linea="3">
		<label>Tipo de revisión: </label><?php echo $registroProcesoConciliacion['tipo_revision_registro_proceso_conciliacion'];?>
	</div>			
</fieldset>
		
<form id="notificarEliminarRegistroProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarRegistroProcesoConciliacion" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" id="idRegistroProcesoConciliacion" name="idRegistroProcesoConciliacion" value="<?php echo $idRegistroProcesoConciliacion;?>" />
	<button id="eliminar" type="submit" class="eliminar" >Eliminar documento</button>	
</form>


<script type="text/javascript">

$(document).ready(function(){	
	distribuirLineas();	  
});

$("#notificarEliminarRegistroProcesoConciliacion").submit(function(event){
	  	event.preventDefault();
		ejecutarJson($(this));
});

</script>