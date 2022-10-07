<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';

	$id_solicitud_manufacturador = $_POST['id_solicitud_manufacturador'];
	$nombreFabricante = $_POST['nombreFabricante'];
	
	$conexion = new Conexion();
	$cc=new ControladorCatalogos();
	$cg = new ControladorDossierPlaguicida();
	$manufacturador=$cg->obtenerManufacturador($conexion,$id_solicitud_manufacturador);
	$id_solicitud_fabricante=$manufacturador['id_solicitud_fabricante'];

	$items=$cc->listarLocalizacion($conexion,'PAIS');
	$paises=array();
	while ($fila = pg_fetch_assoc($items)){
		$paises[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
	}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Datos del manufacturadores de <?php echo $nombreFabricante; ?> </h1>
	</header>
	
	<div id="estado"></div>
   <form id="regresar" data-rutaAplicacion="dossierPlaguicida" data-opcion="abrirFabricante" data-destino="detalleItem">
      <input type="hidden" name="id_solicitud_fabricante" value="<?php echo $id_solicitud_fabricante;?>" />
      <button class="regresar">Regresar</button>
   </form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<div >	
			
               <form id="actualizarItem" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarFabricante">

                  <button id="modificar" type="button" class="editar">Editar</button>
                  <button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>

                  <input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="<?php echo $id_solicitud_fabricante; ?>" />
						<input type="hidden" id="id_solicitud_manufacturador" name="id_solicitud_manufacturador" value="<?php echo $id_solicitud_manufacturador; ?>" />                  
                  <input type="hidden" id="paso_opcion" name="paso_opcion" value="guardarManufacturador" />

                  <fieldset>
                     <legend>Datos del manufacturador</legend>
                     <div data-linea="10" class="fabricanteManufacturador">
								<label for="manufacturador_nombre" >Nombre:</label>
								<input value="<?php echo $manufacturador['nombre'];?>" name="manufacturador_nombre" type="text" id="manufacturador_nombre" placeholder="Nombre del Manufacturador" required disabled="disabled" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
							</div>
							<div data-linea="11" class="fabricanteManufacturador">
								<label for="manufacturador_pais">País de origen del ingrediene activo</label>
								<select name="manufacturador_pais" id="manufacturador_pais" disabled="disabled" required>
									<option value="">Seleccione....</option>
									
									<?php
										foreach ($paises as $key=>$item){
											if(strtoupper($item['codigo']) == strtoupper($manufacturador['id_pais'])){
											echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
											}else{
											echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
											}
										}
										?>
								</select>
							</div>
							<div data-linea="12" class="fabricanteManufacturador">
								<label for="manufacturador_direccion" class="opcional">Dirección:</label>
								<input value="<?php echo $manufacturador['direccion'];?>" name="manufacturador_direccion" type="text" id="manufacturador_direccion" placeholder="Dirección" disabled="disabled" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
							</div>
							<div data-linea="13" class="fabricanteManufacturador">
								<label for="manufacturador_representante" class="opcional">Representante legal:</label>
								<input value="<?php echo $manufacturador['representante_legal'];?>" name="manufacturador_representante" type="text" id="manufacturador_representante" placeholder="" disabled="disabled" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
							</div>
							<div data-linea="14" class="fabricanteManufacturador">
								<label for="manufacturador_correo" class="opcional">Correo electrónico:</label>
								<input value="<?php echo $manufacturador['correo'];?>" name="manufacturador_correo" type="text" id="manufacturador_correo" placeholder="" disabled="disabled" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
							</div>
							<div data-linea="15" class="fabricanteManufacturador">
								<label for="manufacturador_telefono" class="opcional">Telefono:</label>
								<input value="<?php echo $manufacturador['telefono'];?>" name="manufacturador_telefono" type="text" id="manufacturador_telefono" placeholder="" disabled="disabled" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
							</div>

                  </fieldset>

               </form>	
				
				</div>
				
			</td>
		</tr>	
	</table>		
</body>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">

	var tipo=<?php echo json_encode($tipo); ?>;

	$('document').ready(function(){
		
	   distribuirLineas();
		construirValidador();

  });

	$("#modificar").click(function () {
		$("input").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("select").removeAttr("disabled");
		

		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled", "disabled");
	});

	$("#actualizarItem").submit(function (event) {
		event.preventDefault();

		error = false;

		verificarCamposVisiblesNulos(['#manufacturador_nombre','#manufacturador_pais','#manufacturador_direccion','#manufacturador_representante','#manufacturador_correo','#manufacturador_telefono']);
		
		if (error) {
			mostrarMensaje("Por favor, llenar el formulario.",'FALLO');
		} else {
			borrarMensaje();
			ejecutarJson($(this));
		}

	});

	

	$("#regresar").submit(function (event) {
		abrir($(this), event, false);
	});


</script>
</html>
