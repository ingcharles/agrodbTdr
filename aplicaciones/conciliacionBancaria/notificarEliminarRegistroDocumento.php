<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cb = new ControladorConciliacionBancaria();

$idDocumento = $_POST['elementos'];

$qDocumento = $cb -> abrirDocumentoXIdDocumento($conexion, $idDocumento);
$documento = pg_fetch_assoc($qDocumento);

?>
<div id="estado"></div>

<p>El <b>documento</b> a ser eliminado es: </p>


		
<fieldset id="informacionDocumento">
				<legend>Información de Documento</legend>
				<div data-linea="1">
					<label>Nombre Documento: </label><?php echo $documento["nombre_documento"];?>
				</div>
				<div data-linea="2">
					<label>Tipo de documento: </label><?php echo $documento["tipo_documento"];?>
				</div>
			</fieldset>
			
			<fieldset id="parametrosLectura">
				<legend>Parámetros de lectura</legend>
					<div data-linea="3">
						<label>Formato de entrada: </label><?php echo $documento["formato_entrada_documento"];?>
					</div>
					<div data-linea="3">
						<label>Número de columnas: </label><?php echo $documento["numero_columnas_documento"];?>
					</div>
					<div data-linea="4">
						<label>Fila inicio lectura: </label><?php echo $documento["fila_inicio_lectura_documento"];?>
					</div>
					<div data-linea="4">
						<label>Columna inicio lectura: </label><?php echo $documento["columna_inicio_lectura_documento"];?>
					</div>
			</fieldset>
		
<form id="notificarEliminarRegistroDocumento" data-rutaAplicacion="conciliacionBancaria" data-opcion="eliminarRegistroDocumento" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" id="idDocumento" name="idDocumento" value="<?php echo $idDocumento;?>" />
	<button id="eliminar" type="submit" class="eliminar" >Eliminar documento</button>	
</form>


<script type="text/javascript">

$(document).ready(function(){	
	distribuirLineas();	  
});

$("#notificarEliminarRegistroDocumento").submit(function(event){
	  	event.preventDefault();
		ejecutarJson($(this));
});

</script>