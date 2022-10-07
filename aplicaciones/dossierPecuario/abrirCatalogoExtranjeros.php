<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorCatalogos.php';


$conexion = new Conexion();
$ce=new ControladorEnsayoEficacia();
$cp=new ControladorDossierPecuario();
$cc=new ControladorCatalogos();


$clase = $_POST['id'];
$tabla = $_POST['opcion'];
$nombre = $_POST['nombreOpcion'];
$tipo = $_POST['idFlujo'];

$items=$cc->listarLocalizacion($conexion,'PAIS');
$paises=array();
while ($fila = pg_fetch_assoc($items)){
	$paises[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
}

$items=$cp->obtenerFabricantesExtranjeros($conexion,null);

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
            <label for="identificador">RUC representante en Ecuador</label>
            <input type="text" id="identificador" name="identificador" value="" maxlength="13" required />
         </div>
         <div data-linea="2">
            <label for="identificadorNombre">Razón social</label>
            <input type="text" id="identificadorNombre" name="identificadorNombre" value="" disabled="disabled" />
         </div>
         <div data-linea="3">
            <label for="nombre">Nombre fabricante extranjero</label>
            <input type="text" id="nombre" name="nombre" value="" maxlength="256" required />
         </div>
         <div data-linea="4">
            <input type="hidden" id="pais" name="pais" value="" />
            <label for="id_pais">País de origen</label>
            <select name="id_pais" id="id_pais" required>
               <option value="">Seleccione....</option>
					<?php
						foreach ($paises as $key=>$item){
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
                                                       ?>
            </select>
         </div>
			<div data-linea="5">
            <label for="direccion">Dirección</label>
            <input type="text" id="direccion" name="direccion" value="" maxlength="256"  />
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
					$nombreLista=$item['identificador'].' : '.$item['nombre'].' - '.$item['pais'];
					$fila=$cp->imprimirLineaCatalogoPecuario($nombreLista,$tipo,$tabla,$clase,$item);	
					echo $fila;
				}
			?>

			
		</table>
	</fieldset>


</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var tipo=<?php echo json_encode($tipo); ?>;
	var fabricantes=<?php echo json_encode($items); ?>;
	

	$("document").ready(function () {

		acciones("#nuevoItem", "#tblItems");

		distribuirLineas();
		construirValidador();


	});

	$('#identificador').change(reconocerOperador);

	$('#id_pais').change(function(){
		$('#pais').val($('#id_pais :selected').text());
	});
	
	
	function reconocerOperador(){
		var idRuc=$("#identificador").val();		
		var param={opcion_llamada:'obtenerOperador',ruc:idRuc};
		llamarServidor('dossierPecuario','atenderLlamadaServidor',param,llenarRazonSocial);

	}

	function llenarRazonSocial(items){
		if(jQuery.isEmptyObject(items) || items.razon_social===undefined){
			$('#identificadorNombre').val("");			
		}
		else{
			$('#identificadorNombre').val(items.razon_social);
		}
	}

</script>
