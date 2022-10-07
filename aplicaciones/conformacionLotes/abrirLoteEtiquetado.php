<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$idLote= $_POST['id'];
$usuario=$_SESSION['usuario'];
?>

<header>
	<h1>Ver Lote Etiquetado</h1>

</header>

<div id="estado"></div>


<form id="LoteEtiquetado" data-rutaAplicacion="conformacionLotes">
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="opcion2" name="opcion2" value="visualizar">
	<input type="hidden" id="usuario" name="usuario" value=<?php echo $usuario?>> 
	<fieldset>
			<legend>Información del Lote:</legend>
			<div data-linea="1">
				<label for="numeroLote">Lote Número</label>
				<?php 
				//$conexion->ejecutarConsulta("begin;");
				$res=$cl->obtenerLoteEtiquetado($conexion, $idLote, $usuario);
				$filas = pg_fetch_assoc($res);
				
				?>
				<input type="text" id="numeroLote" name="numeroLote" value="<?php echo $filas['numero_lote'] ?>" disabled="disabled">	
				<input type="hidden" id="idLote" name="idLote" value="<?php echo $filas['id_lote']?>">
				<input type="hidden" id="exportador" name="exportador" value="<?php echo $filas["exportador"] ?>">
		
			</div>
			<div data-linea="1">
				<label for="loteCodigo" >Código Lote:</label>
				<input type="text" id="loteCodigo" name="loteCodigo" value="<?php echo $filas['codigo_lote']?>" disabled="disabled">
			</div>
			
			<div data-linea="2">
				<label for="fechaConformacion">Fecha Conformación:</label>
				<input type="text" id="fechaConformacion" name="fechaConformacion" value=<?php echo $filas['fecha_conformacion']?> disabled="disabled">
			</div>			
			
			<div data-linea="3">
				<label for="cantidad">Cantidad Lote:</label>
				<input type="text" id="cantidad" name="cantidad" value="<?php echo $filas['cantidad']?>" disabled="disabled">
			</div>
			
			<div data-linea="3">
				<label for="peso">Peso:</label>
				<input type="text" id="peso" name="peso" value="<?php echo $filas['peso']?>" disabled="disabled">
			</div>
			
			<div data-linea="4">
				<label for="fechaEtiqueta">Fecha Etiquetado:</label>
				<input type="text" id="fechaEtiqueta" name="fechaEtiqueta" value=<?php echo $filas['fecha_etiquetado']?> disabled="disabled">
			</div>
			
			<div data-linea="4">
				<label for="nroEtiqueta">Etiquetado por lote:</label>
				<input type="text" id="nroEtiqueta" name="nroEtiqueta" value="<?php echo $filas['numero_etiquetas']?>" disabled="disabled">
			</div>
			
			<div data-linea="5">
				<label for="descripcionLote" style="vertical-align:top;">Descripción Lote: </label>
				<textarea rows="4" style="width:100%;" id="descripcionLote" name="descripcionLote" style="font-family:arial;width:75%" disabled="disabled"><?php echo $filas['descripcion']?></textarea>
			</div>
			
			<div data-linea="6">
				<a href=<?php echo $filas['ruta']?> target="_blank" id="visualizar"> Visualizar etiquetas</a>
			</div>
									
		</fieldset>
		
	
	
</form>

<script type="text/javascript">

$("document").ready(function(event){
	distribuirLineas();
		
});



	
	
</script>
