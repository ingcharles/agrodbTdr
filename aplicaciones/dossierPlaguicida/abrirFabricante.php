<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

require_once '../../clases/ControladorDossierPlaguicida.php';
$conexion = new Conexion();
$cc=new ControladorCatalogos();

$cg=new ControladorDossierPlaguicida();

$id_solicitud_fabricante=$_POST['id_solicitud_fabricante'];
$fabricante=$cg->obtenerFabricante($conexion,$id_solicitud_fabricante);
$id_solicitud=$fabricante['id_solicitud'];
$regresaPestania=3;
if($fabricante['tipo_fabricante']=='F')
	$regresaPestania=2;

$manufacturadores=$cg->obtenerManufacturadores($conexion,$id_solicitud_fabricante);

if(sizeof($manufacturadores)>0){
	$fabricante['tiene_contrato']='t';
}
else{
	$fabricante['tiene_contrato']='f';
}


$items=$cc->listarLocalizacion($conexion,'PAIS');
	$paises=array();
	while ($fila = pg_fetch_assoc($items)){
		$paises[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
	}

?>

<div id="estado"></div>

<form id="regresar" data-rutaAplicacion="dossierPlaguicida" data-opcion="abrirSolicitudDossier" data-destino="detalleItem" data-accionEnExito="NADA">
      <input type="hidden" id="id" name="id" value="<?php echo $id_solicitud;?>" />

      <input type="hidden" name="numeroPestania" value="<?php echo $regresaPestania;?>" />

      <button type="button" class="regresar">Regresar al dossier</button>
   </form>

<div id="P0" class="pestania" style="display: block;">

   <form id="nuevoItem" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarFabricante">
		 <button id="modificar" type="button" class="editar">Editar</button>
       <button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>

      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="<?php echo $id_solicitud_fabricante;?>" />
		<input type="hidden" id="tipo_fabricante" name="tipo_fabricante" value="<?php echo $fabricante['tipo_fabricante'];?>" />
      <input type="hidden" id="paso_opcion" name="paso_opcion" value="guardar" />


      <fieldset>
         <legend>
            Datos
            de : <?php echo $fabricante['nombre'];?>
         </legend>
         <div data-linea="1">
            <label for="fabricante_nombre" class="opcional">Nombre:</label>
            <input value="<?php echo $fabricante['nombre'];?>" name="fabricante_nombre" type="text" id="fabricante_nombre" placeholder="Razon social" required disabled="disabled" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3">
            <label for="fabricante_pais">País de origen del ingrediene activo</label>
            <select name="fabricante_pais" id="fabricante_pais"  disabled="disabled" required>
               <option value="">Seleccione....</option><?php
               foreach ($paises as $key=>$item){
               if(strtoupper($item['codigo']) == strtoupper($fabricante['id_pais'])){
               echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
               }else{
               echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
               }
               }
               ?>
            </select>
         </div>
         <div data-linea="4">
            <label for="fabricante_direccion" class="opcional">Dirección:</label>
            <input value="<?php echo $fabricante['direccion'];?>" name="fabricante_direccion" type="text" id="fabricante_direccion" placeholder="Dirección actual" disabled="disabled" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5" class="fabricanteNacional">
            <label for="fabricante_representante" class="opcional">Representante legal:</label>
            <input value="<?php echo $fabricante['representante_legal'];?>" name="fabricante_representante" type="text" id="fabricante_representante" placeholder="Nombre y apellido" disabled="disabled" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6" class="fabricanteNacional">
            <label for="fabricante_correo">Correo electrónico:</label>
            <input value="<?php echo $fabricante['correo'];?>" name="fabricante_correo" type="text" id="fabricante_correo" placeholder="Correo electrónico" disabled="disabled" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="7" class="fabricanteNacional">
            <label for="fabricante_telefono" class="opcional">Telefono:</label>
            <input value="<?php echo $fabricante['telefono'];?>" name="fabricante_telefono" type="text" id="fabricante_telefono" placeholder="Teléfono" disabled="disabled" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div class="justificado">
            <label for="fabricante_carta" class="opcional">Referecia de carta(s) de autorización original(es) debidamente legalizada, apostillada o consularizada</label>
            <input value="<?php echo $fabricante['carta'];?>" name="fabricante_carta" type="text" id="fabricante_carta" placeholder="Ingrese referencia" disabled="disabled" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <hr />
         <div data-linea="9">
            <label for="tiene_contrato">Tiene contratos de manufactura ?</label>
            SI<input type="radio" id="tiene_contratoSI" name="tiene_contrato" value="SI" <?php if($fabricante['tiene_contrato']=='t') echo "checked=true"?> />
            NO<input type="radio" id="tiene_contratoNO" name="tiene_contrato" value="NO" <?php if($fabricante['tiene_contrato']=='f') echo "checked=true"?> />
         </div>

      </fieldset>
  
	</form>
	
   <form id="nuevoSubItem" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarFabricante">
      <input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="<?php echo $id_solicitud_fabricante; ?>" />			                  
      <input type="hidden" id="paso_opcion" name="paso_opcion" value="guardarManufacturador" />

      <fieldset>
         <legend>Nuevo registro de manufacturador</legend>
         <div data-linea="10" class="fabricanteManufacturador">
				<label for="manufacturador_nombre" >Nombre:</label>
				<input value="" name="manufacturador_nombre" type="text" id="manufacturador_nombre" placeholder="Nombre del Manufacturador" required maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="11" class="fabricanteManufacturador">
				<label for="manufacturador_pais">País de origen del ingrediene activo</label>
				<select name="manufacturador_pais" id="manufacturador_pais" required>
					<option value="">Seleccione....</option>
					<?php
					foreach ($paises as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
					}
								?>
				</select>
			</div>
			<div data-linea="12" class="fabricanteManufacturador">
				<label for="manufacturador_direccion" class="opcional">Dirección:</label>
				<input value="" name="manufacturador_direccion" type="text" id="manufacturador_direccion" placeholder="Dirección" class="cuadroTextoCompleto" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="13" class="fabricanteManufacturador">
				<label for="manufacturador_representante" class="opcional">Representante legal:</label>
				<input value="" name="manufacturador_representante" type="text" id="manufacturador_representante" placeholder="" class="cuadroTextoCompleto" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="14" class="fabricanteManufacturador">
				<label for="manufacturador_correo" class="opcional">Correo electrónico:</label>
				<input value="" name="manufacturador_correo" type="text" id="manufacturador_correo" placeholder="" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="15" class="fabricanteManufacturador">
				<label for="manufacturador_telefono" class="opcional">Telefono:</label>
				<input value="" name="manufacturador_telefono" type="text" id="manufacturador_telefono" placeholder="" class="cuadroTextoCompleto" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>
			<div data-linea="17">
				<button id="btnAgregarFabricante" type="submit" class="mas">Agregar manufacturador</button>
			</div>

      </fieldset>

   </form>	
	
	<fieldset>
		<legend>Manufacturadores</legend>
	
		<table id="tblManufacturadores">
			<thead>
				<tr>
					<th width="30%">Nombre-País de origen</th>
					<th width="30%">Dirección</th>
					<th width="20%">Representante legal</th>
					<th width="15%">Correo</th>
					<th width="5%">Teléfono</th>				
				</tr>
			</thead>
			<?php
			foreach($manufacturadores as $item){
			$fila=$cg->imprimirLineaManufacturador($item);
			echo $fila;
			}
			?>

		</table>
	</fieldset>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">

	var tipo=<?php echo json_encode($tipo); ?>;
	var fabricante = <?php echo json_encode($fabricante);?>;

	$("document").ready(function () {

		if(fabricante.tiene_contrato=='t'){
			$("#nuevoSubItem").show();			
		}
		else{
			$("#nuevoSubItem").hide();			
		}

		distribuirLineas();
		
	});

	$("#modificar").click(function () {
		$("input").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("select").removeAttr("disabled");
		

		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled", "disabled");
	});
	
	$("input[name=tiene_contrato]:radio").click(function() {
		if($(this).attr("value")=="SI") {
			$("#nuevoSubItem").show();
		}
		if($(this).attr("value")=="NO") {
			$("#nuevoSubItem").hide();
		}
		distribuirLineas();
	});

	

	$("#nuevoItem").submit(function (event) {
		event.preventDefault();

		error = false;

		verificarCamposVisiblesNulos(['#fabricante_nombre','#fabricante_pais','#fabricante_direccion','#fabricante_representante','#fabricante_correo','#fabricante_telefono','#fabricante_carta']);
		
		if (error) {
			mostrarMensaje("Por favor, llenar el formulario.",'FALLO');
		} else {
			borrarMensaje();
			ejecutarJson($(this));
		}

	});

	acciones("#nuevoSubItem", "#tblManufacturadores");

	
	$('.regresar').click(function (event) {
		event.preventDefault();
		var form = $(this).parent();
		abrir(form, event, true);
	});


</script>
