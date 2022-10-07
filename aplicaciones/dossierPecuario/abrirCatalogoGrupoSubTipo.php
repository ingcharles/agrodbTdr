<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPecuario.php';


$conexion = new Conexion();
$ce=new ControladorEnsayoEficacia();
$cp=new ControladorDossierPecuario();



$clase = $_POST['id'];
$tabla = $_POST['opcion'];
$nombre = $_POST['nombreOpcion'];
$tipo = $_POST['idFlujo'];

$subtipoProductos=$ce->obtenerSubTiposProductos ($conexion, 'IAV','TIPO_VETERINARIO');	
$grupos=$ce->listarElementosCatalogo($conexion,'IA_GRUPO');
$items=$cp->obtenerSubtiposGrupos($conexion);

?>

<div id="estado"></div>

<div id="P0" class="pestania" style="display: block;">

   <form id="nuevoItem" data-rutaAplicacion="dossierPecuario" data-opcion="guardarCatalogoOpcionesPC">
      <input type="hidden" id="paso_catalogo" name="paso_catalogo" value="guardarStandar" />
      <input type="hidden" id="clase" name="clase" value="<?php echo $clase; ?>" />
      <input type="hidden" id="tabla" name="tabla" value="<?php echo $tabla; ?>" />
      <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>" />
      <fieldset>
         <legend>Nuevo item para <?php echo $nombre;?></legend>
			<div data-linea="1">
            <input type="hidden" id="nombre_subtipo" name="nombre_subtipo" value="" />
            <label for="codificacion_subtipo_producto">Sub tipos de producto</label>
            <select name="codificacion_subtipo_producto" id="codificacion_subtipo_producto" required>
               <option value="">Seleccione....</option>
					<?php
					foreach ($subtipoProductos as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
					}
                    ?>
            </select>
         </div>
			<div data-linea="2">
            <input type="hidden" id="nombre_grupo" name="nombre_grupo" value="" />
            <label for="grupo">Grupos</label>
            <select name="grupo" id="grupo" required>
               <option value="">Seleccione....</option>
					<?php
					foreach ($grupos as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
					}
                    ?>
            </select>
         </div>
         
			

         <button type="submit" class="mas">Añadir</button>
      </fieldset>
   </form>

	<fieldset>
		<legend>
			Items de <?php	echo $nombre; ?>
		</legend>
		<table id="tblItems">
			
			<?php
				foreach($items as $item){
					$nombreLista=$item['nombre_subtipo'].' : '.$item['nombre'];
					$fila=$cp->imprimirLineaCatalogoPecuario($nombreLista,$tipo,$tabla,$clase,$item);	
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


	});

	$('#codificacion_subtipo_producto').change(function(){
		$('#nombre_subtipo').val($('#codificacion_subtipo_producto :selected').text());
	});
	$('#grupo').change(function(){
		$('#nombre_grupo').val($('#grupo :selected').text());
	});



</script>
