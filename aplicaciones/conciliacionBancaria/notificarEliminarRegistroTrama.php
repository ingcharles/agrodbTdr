<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$idTrama = $_POST['elementos'];

$qTrama = $cb -> abrirTramaXIdTrama($conexion, $idTrama);
$trama = pg_fetch_assoc($qTrama);

?>
<div id="estado"></div>

<p>La <b>trama</b> a ser eliminada es: </p>
		
<fieldset id="informacionTrama">
				<legend>Información de Trama</legend>
				<div data-linea="1">
					<label>Nombre trama: </label><?php echo $trama['nombre_trama'];?>
				</div>
				<div data-linea="1">
					<label>Separador: </label><?php echo $trama['separador_trama'];?>
				</div>
				<div data-linea="2">
					<label>Formato de entrada: </label><?php echo $trama['formato_entrada_trama'];?>
				</div>
				<div data-linea="2">
					<label>Formato de salida: </label><?php echo $trama['formato_salida_trama'];?>
				</div>
			</fieldset>
		
<form id="notificarEliminarRegistroTrama" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarRegistroTrama" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" id="idTrama" name="idTrama" value="<?php echo $idTrama;?>" />
	<button id="eliminar" type="submit" class="eliminar" >Eliminar trama</button>	
</form>

<script type="text/javascript">

$(document).ready(function(){	
	distribuirLineas();	  
});

$("#notificarEliminarRegistroTrama").submit(function(event){
  	event.preventDefault();
	ejecutarJson($(this));
});

</script>