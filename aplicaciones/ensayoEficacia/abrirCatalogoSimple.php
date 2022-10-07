<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';


$conexion = new Conexion();
$ce=new ControladorEnsayoEficacia();



$clase = $_POST['id'];
$tabla = $_POST['opcion'];
$nombre = $_POST['nombreOpcion'];
$tipo = $_POST['idFlujo'];

switch($tipo){
	case 'simple':
	case 'configuracion':
		$items=$ce->listarElementosCatalogo($conexion,$clase);
		break;
	case 'extendido':
		$items=$ce->listarElementosCatalogoEx($conexion,$clase);
		break;
}

?>

<div id="estado"></div>

<div id="P0" class="pestania" style="display: block;">

   <form id="nuevoItem" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarCatalogoOpciones">
      <input type="hidden" id="paso_catalogo" name="paso_catalogo" value="C1" />
		<input type="hidden" id="clase" name="clase" value="<?php echo $clase; ?>" />
      <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>" />
      <fieldset>
         <legend>Nuevo item para <?php echo $nombre;?></legend>
         <div data-linea="1">
				<label for="codigoCatalogo">Código</label>
				<input id="codigoCatalogo" name="codigoCatalogo" type="text" maxlength="8" required="required" />

         </div>
			<label for="nombreCatalogo">Nombre</label>
         <div data-linea="2">

				<textarea id="nombreCatalogo" name="nombreCatalogo"  maxlength="1024" required="required"></textarea>

         </div>
         <label for="nombre2" class="extendido">Descripción</label>
         <div data-linea="3" class="extendido">

            <textarea id="nombre2" name="nombre2"  maxlength="256"></textarea>

         </div>
         <label for="nombre3" class="extendido">Dato complementarios</label>
         <div data-linea="4" class="extendido">

            <textarea id="nombre3" name="nombre3"  maxlength="256"></textarea>

         </div>
         <button type="submit" class="mas">Añadir item del catálogo</button>
      </fieldset>
   </form>

	<fieldset>
		<legend>
			Items de <?php	echo $nombre; ?>
		</legend>
		<table id="tblItems">
			
			<?php
				foreach($items as $item){
					$fila=$ce->imprimirLineaCatalogo($nombre,$tipo,$clase,$item);

				
					echo $fila;


				}
			?>

			
		</table>
	</fieldset>


</div>



<script type="text/javascript">
	var tipo=<?php echo json_encode($tipo); ?>;

	$("document").ready(function () {

		acciones("#nuevoItem", "#tblItems");

		distribuirLineas();
		construirValidador();

		if (tipo == 'extendido')
			$('.extendido').show();
		else
			$('.extendido').hide();

	});





</script>
