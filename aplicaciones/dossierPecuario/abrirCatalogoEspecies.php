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

$especies=$ce->listarElementosCatalogoEx($conexion,'P_ESPECI');	
$consumibles=$ce->listarElementosCatalogo($conexion,'PPC_06');
$items=$cp->obtenerCatalogoEspeciesConsumibles($conexion);

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
            <input type="hidden" id="nombre_especie" name="nombre_especie" value="" />
            <label for="id_especie">Especie pecuaria</label>
            <select name="id_especie" id="id_especie" required>
               <option value="">Seleccione....</option>
					<?php
																		 foreach ($especies as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
                    ?>
            </select>
         </div>
			<div data-linea="2">
            <input type="hidden" id="nombre_consumible" name="nombre_consumible" value="" />
            <label for="id_consumible">Producto consumible</label>
            <select name="id_consumible" id="id_consumible" required>
               <option value="">Seleccione....</option>
					<?php
					foreach ($consumibles as $key=>$item){
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
					$nombreLista=$item['especie'].' : '.$item['consumible'];
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

	$('#id_especie').change(function(){
		$('#nombre_especie').val($('#id_especie :selected').text());
	});
	$('#id_consumible').change(function(){
		$('#nombre_consumible').val($('#id_consumible :selected').text());
	});



</script>
