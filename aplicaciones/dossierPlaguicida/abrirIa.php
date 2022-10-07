<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';

	$id_solicitud = $_POST['id_solicitud'];
	
	$id_protocolo= $_POST['id_protocolo'];
	$id_protocolo_ia= $_POST['id_protocolo_ia'];

	$id_ingrediente_activo = $_POST['id_ingrediente_activo'];

	$normativa = $_POST['normativa'];

	$esDossierBloqueado=$_POST['esDossierBloqueado'];
	if($esDossierBloqueado==null)
		$esDossierBloqueado=0;
	$idFlujo=$_POST['idFlujo'];
	$nombreOpcion = $_POST['nombreOpcion'];
	$paginaRegreso = $_POST['paginaRegreso'];

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cg= new ControladorDossierPlaguicida();
	$ingrediente=array();
	$fabricantes=array();
	$fabricantesLista='';
	$datosGenerales=array();
	$id_solicitud_ia=0;
	//busca si el ingrediente activo ya se encuentra declarado
	if($id_solicitud!=null && $id_ingrediente_activo!=null){
		$datosGenerales=$cg->obtenerIngredienteSolicitudDeclarado($conexion,$id_solicitud,$id_ingrediente_activo);
		if($datosGenerales!=null){
			$id_solicitud_ia = $datosGenerales['id_solicitud_ia'];
		}

		$fabricantes=$cg->obtenerFabricantes($conexion,$id_solicitud,'F');
		foreach($fabricantes as $key=>$value){
			$fabricantesLista.=', '.$value['nombre'].'-'.$value['pais'];
            }
		if(strlen($fabricantesLista)>1){
			$fabricantesLista=substr($fabricantesLista,2);
            }

		$ingrediente=$ce->obtenerIngredienteActivo($conexion,$id_ingrediente_activo);
	}
	else{
		echo 'Ingrediene activo no encontrado';
	}

	//para evaluacion del Ingrediente
	$doc=$ce->obtenerFormatoDocumento($conexion,'GI');
	if($id_solicitud>0 && $id_solicitud_ia>0){
		$subsanados=$ce->obtenerObservacionesDelDocumento($conexion,$id_solicitud,'DG',null,$id_solicitud_ia);
	}
	//****************** ANEXOS **************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
	$pathAnexo=$paths['ruta'];

?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
</head>

<body>
   <header>
      <h1>
         Dossier : <?php echo $id_solicitud ;?>
      </h1>
   </header>
   <div id="estado"></div>

   <form id="regresar" data-rutaAplicacion="dossierPlaguicida" data-opcion="abrirSolicitudDossier" data-destino="detalleItem" data-accionEnExito="NADA">
      <input type="hidden" id="id" name="id" value="<?php echo $id_solicitud;?>" />
		<input type="hidden" name="numeroPestania" value="5" />

      <button type="button" class="regresar">Regresar al dossier</button>
   </form>

   <div class="pestania" id="P1" style="display: block;">
      <form id="frmNuevoIngrediente1" data-rutaaplicacion="dossierPlaguicida" data-opcion="guardarIngrediente">
         <input type="hidden" id="pasoOpcion" name="pasoOpcion" value="1" />
         <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
         <input type="hidden" id="id_solicitud_ia" name="id_solicitud_ia" value="<?php echo $id_solicitud_ia;?>" />
         <input type="hidden" id="id_protocolo_ia" name="id_protocolo_ia" value="<?php echo $id_protocolo_ia;?>" />
         <input type="hidden" id="id_ingrediente_activo" name="id_ingrediente_activo" value="<?php echo $id_ingrediente_activo;?>" />

         <fieldset>
            <legend>Ingrediente activo</legend>
            <div class="normativaVer">
               <label for="tiene_carta_acceso">Incluye carta de acceso ?</label>
               <div>
                  SI
                  <input type="radio" id="tiene_carta_accesoSI" name="tiene_carta_acceso" value="SI" <?php if($datosGenerales['tiene_carta_acceso']=='t') echo "checked=true"?> />
                  NO
                  <input type="radio" id="tiene_carta_accesoNO" name="tiene_carta_acceso" value="NO" <?php if($datosGenerales['tiene_carta_acceso']==null || $datosGenerales['tiene_carta_acceso']=='f') echo "checked=true"?> />
               </div>
            </div>
            <div class="justificado normativaVer" id="div_tiene_carta_acceso">
               <label for="carta_acceso">Referencia de la carta de acceso:</label>
               <input value="<?php echo $datosGenerales['carta_acceso'];?>" name="carta_acceso" type="text" id="carta_acceso" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>

            <div data-linea="1" class="verItemIA">
               <label for="fabricante">Fabricante y país de origen</label>
               <input value="<?php echo $fabricantesLista;?>" name="fabricante" id="fabricante" type="text" disabled="disabled" />
            </div>

            <div data-linea="2" class="verItemIA">
               <label for="nombreComun">Nombre común: Aceptado por ISO, o equivalente</label>
               <input value="<?php echo $ingrediente['ingrediente_activo'];?>" name="nombreComun" id="nombreComun" type="text" disabled="disabled" />
            </div>
            <div data-linea="3" class="verItemIA">
               <label for="nombreCientifico">Nombre químico: Aceptado o propuesto por IUPAC</label>
               <input value="<?php echo $ingrediente['ingrediente_quimico'];?>" name="nombreCientifico" id="nombreCientifico" type="text" disabled="disabled" />
            </div>

            <div data-linea="4" class="verItemIA">
               <label for="cas">Número de código experimental que fue asignado por el fabricante</label>
               <input value="<?php echo $ingrediente['cas'];?>" name="cas" id="cas" type="text" disabled="disabled" />
            </div>
            <div data-linea="5" class="verItemIA">
               <label for="formula">Fórmula empírica</label>
               <input value="<?php echo $ingrediente['formula_quimica'];?>" name="formula" id="formula" type="text" disabled="disabled" />
            </div>
            <div data-linea="6" class="verItemIA">
               <label for="peso">Peso molecular</label>
               <input value="<?php echo $datosGenerales['peso_molecular'];?>" name="peso" id="peso" type="text" placeholder="peso molecualr del ingrediente" />
            </div>
				<div data-linea="7" class="verItemIA">
					<label for="peso_molecular_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['peso_molecular_ref'];?>" name="peso_molecular_ref" type="text" id="peso_molecular_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>

            <div data-linea="10" class="verItemIA">
               <label for="formula_estructural">Fórmula estructural</label>
               <input value="<?php echo $datosGenerales['formula_estructural'];?>" name="formula_estructural" id="formula_estructural" placeholder="referencia para la fórmula" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="11" class="verItemIA">
					<label for="formula_estructural_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['formula_estructural_ref'];?>" name="formula_estructural_ref" type="text" id="formula_estructural_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>

            <div>
               <img id="formula_estructural_ruta" src="<?php echo $datosGenerales['formula_estructural_ruta'] ?>" border="0" width="70px" height="auto">
            </div>

            <div data-linea="12" class="verItemIA">
               <input type="hidden" id="rutaArchivo" class="rutaArchivo" name="rutaArchivo" value="<?php echo $datosGenerales['formula_estructural_ruta'] ?>" />
               <input type="file" id="idFileFormula" class="archivo" accept=".png, .jpg, .jpeg" onchange="if(!this.value.length) return false; activarCargaImagen();" />
               <div class="estadoCarga">Carge la fórmula estructural en formato: png, jpg, jpeg (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
               <button type="button" class="subirImagen adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Cargar imagen</button>
            </div>

            <div data-linea="13" class="verItemIA">
               <label for="grupo_quimico">Grupo químico</label>
               <input value="<?php echo $ingrediente['grupo_quimico'];?>" name="grupo_quimico" id="grupo_quimico" type="text" disabled="disabled" />
            </div>
            <div data-linea="14" class="verItemIA">
               <label for="grado_pureza">Grado de pureza (de acuerdo con el origen químico)</label>
               <input value="<?php echo $datosGenerales['grado_pureza'];?>" name="grado_pureza" id="grado_pureza" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="15" class="verItemIA">
					<label for="grado_pureza_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['grado_pureza_ref'];?>" name="grado_pureza_ref" type="text" id="grado_pureza_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="16" class="verItemIA">
               <label for="isomeros">Isómeros (identificarlos)</label>
               <textarea name="isomeros" id="isomeros" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
						<?php echo $datosGenerales['isomeros'];?>
					</textarea>
            </div>
				<div data-linea="17" class="verItemIA">
					<label for="isomeros_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['isomeros_ref'];?>" name="isomeros_ref" type="text" id="isomeros_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="18" class="verItemIA">
               <label for="impurezas">Impurezas (identificarlas)</label>
               <textarea name="impurezas" id="impurezas" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
						<?php echo $datosGenerales['impurezas'];?>
					</textarea>
            </div>
				<div data-linea="19" class="verItemIA">
					<label for="impurezas_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['impurezas_ref'];?>" name="impurezas_ref" type="text" id="impurezas_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="20" class="verItemIA">
               <label for="aditivos">Aditivos (Ejemplo: estabilizantes) (identificarlos)</label>
               <textarea name="aditivos" id="aditivos" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
						<?php echo $datosGenerales['aditivos'];?>
					</textarea>
            </div>
				<div data-linea="21" class="verItemIA">
					<label for="aditivos_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['aditivos_ref'];?>" name="aditivos_ref" type="text" id="aditivos_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>

            
         </fieldset>

         <button type="submit" class="mas">Guardar</button>

      </form>
   </div>

   <div class="pestania" id="P2" style="display: block;">
      <form id="frmNuevoIngrediente3" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarIngrediente">
         <input type="hidden" id="pasoOpcion" name="pasoOpcion" value="3" />
         <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
         <input type="hidden" id="id_protocolo_ia" name="id_protocolo_ia" value="<?php echo $id_protocolo_ia;?>" />
         <input type="hidden" id="id_solicitud_ia" name="id_solicitud_ia" value="<?php echo $id_solicitud_ia;?>" />
         <fieldset class="verItemIA">
            <legend>Propiedades físicas y Químicas</legend>

           
            <div data-linea="2">
               <label for="estado_fisico">Estado físico</label>
               <select name="estado_fisico" id="estado_fisico">
                  <option value="">Seleccione....</option><?php
            $estadoFisico=$ce->listarElementosCatalogo($conexion,'P2C4');
            foreach ($estadoFisico as $key=>$item){
            if(strtoupper($item['codigo']) == strtoupper($datosGenerales['estado_fisico'])){
            echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
            }else{
            echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
            }
            }
            ?>
               </select>
            </div>
				<div data-linea="3">
					<label for="estado_fisico_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['estado_fisico_ref'];?>" name="estado_fisico_ref" type="text" id="estado_fisico_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="4">
               <label for="color">Color</label>
               <input value="<?php echo $datosGenerales['color'];?>" name="color" id="color" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="5">
					<label for="color_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['color_ref'];?>" name="color_ref" type="text" id="color_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="7">
               <label for="olor">Olor</label>
               <input value="<?php echo $datosGenerales['olor'];?>" name="olor" id="olor" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="8">
					<label for="olor_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['olor_ref'];?>" name="olor_ref" type="text" id="olor_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="9">
               <label for="punto_fusion">Punto fusión [ ºC ]</label>
               <input value="<?php echo $datosGenerales['punto_fusion'];?>" name="punto_fusion" id="punto_fusion" type="text" maxlength="10" data-er="^[0-9]{10}+$" />               
            </div>
				<div data-linea="10">
					<label for="punto_fusion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['punto_fusion_ref'];?>" name="punto_fusion_ref" type="text" id="punto_fusion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <br />
            <div data-linea="11" class="verEstadoLiquido">
               <label for="punto_ebullicion">Punto ebullición [ ºC ]</label>
               <input value="<?php echo $datosGenerales['punto_ebullicion'];?>" name="punto_ebullicion" id="punto_ebullicion" type="text" maxlength="10" data-er="^[0-9]{10}+$" />               
            </div>
				<div data-linea="14" class="verEstadoLiquido">
					<label for="punto_ebullicion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['punto_ebullicion_ref'];?>" name="punto_ebullicion_ref" type="text" id="punto_ebullicion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="15">
               <label for="densidad">Densidad</label>
               <input value="<?php echo $datosGenerales['densidad'];?>" name="densidad" id="densidad" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="16">
					<label for="densidad_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['densidad_ref'];?>" name="densidad_ref" type="text" id="densidad_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="17">
               <label for="presion_vapor">Presión de vapor</label>
               <input value="<?php echo $datosGenerales['presion_vapor'];?>" name="presion_vapor" id="presion_vapor" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="18">
					<label for="presion_vapor_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['presion_vapor_ref'];?>" name="presion_vapor_ref" type="text" id="presion_vapor_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="19">
               <label for="espectro_absorcion">Espectro de absorción</label>
               <input value="<?php echo $datosGenerales['espectro_absorcion'];?>" name="espectro_absorcion" id="espectro_absorcion" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="20">
					<label for="espectro_absorcion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['espectro_absorcion_ref'];?>" name="espectro_absorcion_ref" type="text" id="espectro_absorcion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="21">
               <label for="solubilidad_agua">Solubilidad en agua</label>
               <input value="<?php echo $datosGenerales['solubilidad_agua'];?>" name="solubilidad_agua" id="solubilidad_agua" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="22">
					<label for="solubilidad_agua_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['solubilidad_agua_ref'];?>" name="solubilidad_agua_ref" type="text" id="solubilidad_agua_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="23">
               <label for="solubilidad_agua">Solubilidad en disolventes orgánicos</label>
               <input value="<?php echo $datosGenerales['solubilidad_disolventes'];?>" name="solubilidad_disolventes" id="solubilidad_disolventes" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="24">
					<label for="solubilidad_disolventes_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['solubilidad_disolventes_ref'];?>" name="solubilidad_disolventes_ref" type="text" id="solubilidad_disolventes_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="25">
               <label for="coeficiente_particion">Coeficiente de partición en n-octanol/agua</label>
               <input value="<?php echo $datosGenerales['coeficiente_particion'];?>" name="coeficiente_particion" id="coeficiente_particion" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="26">
					<label for="coeficiente_particion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['coeficiente_particion_ref'];?>" name="coeficiente_particion_ref" type="text" id="coeficiente_particion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="37">
               <label for="punto_ignicion">Punto de ignición [ ºC ]</label>
               <input value="<?php echo $datosGenerales['punto_ignicion'];?>" name="punto_ignicion" id="punto_ignicion" type="text" maxlength="10" data-er="^[0-9]{10}+$" />
            </div>
				<div data-linea="28">
					<label for="punto_ignicion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['punto_ignicion_ref'];?>" name="punto_ignicion_ref" type="text" id="punto_ignicion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="29" class="verEstadoLiquido">
               <label for="tension_superficial">Tensión superficial</label>
               <input value="<?php echo $datosGenerales['tension_superficial'];?>" name="tension_superficial" id="tension_superficial" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="30" class="verEstadoLiquido">
					<label for="tension_superficial_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['tension_superficial_ref'];?>" name="tension_superficial_ref" type="text" id="tension_superficial_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="31">
               <label for="propiedades_explosivas">Propiedades explosivas</label>
               <textarea name="propiedades_explosivas" id="propiedades_explosivas" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['propiedades_explosivas'];?></textarea>
            </div>
				<div data-linea="32">
					<label for="propiedades_explosivas_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['propiedades_explosivas_ref'];?>" name="propiedades_explosivas_ref" type="text" id="propiedades_explosivas_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="33">
               <label for="propiedades_oxidantes">Propiedades oxidantes</label>
               <textarea name="propiedades_oxidantes" id="propiedades_oxidantes" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['propiedades_oxidantes'];?></textarea>
            </div>
				<div data-linea="34">
					<label for="propiedades_oxidantes_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['propiedades_oxidantes_ref'];?>" name="propiedades_oxidantes_ref" type="text" id="propiedades_oxidantes_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="35">
               <label for="reactividad_envase">Reactividad con el material de envase</label>
               <textarea name="reactividad_envase" id="reactividad_envase" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['reactividad_envase'];?></textarea>
            </div>
				<div data-linea="36">
					<label for="reactividad_envase_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['reactividad_envase_ref'];?>" name="reactividad_envase_ref" type="text" id="reactividad_envase_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="37" class="verEstadoLiquido">
               <label for="viscosidad">Viscosidad</label>
               <input value="<?php echo $datosGenerales['viscosidad'];?>" name="viscosidad" id="viscosidad" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				<div data-linea="38" class="verEstadoLiquido">
					<label for="viscosidad_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['viscosidad_ref'];?>" name="viscosidad_ref" type="text" id="viscosidad_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>

         </fieldset>

         <button type="submit" class="mas">Guardar</button>

      </form>
   </div>

   <div class="pestania" id="P3" style="display: block;">
      <form id="frmNuevoIngrediente4" data-rutaaplicacion="dossierPlaguicida" data-opcion="guardarIngrediente">
         <input type="hidden" id="pasoOpcion" name="pasoOpcion" value="4" />
         <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
         <input type="hidden" id="id_protocolo_ia" name="id_protocolo_ia" value="<?php echo $id_protocolo_ia;?>" />
         <input type="hidden" id="id_solicitud_ia" name="id_solicitud_ia" value="<?php echo $id_solicitud_ia;?>" />

         <fieldset class="verItemIA">
            <legend>Aspectos relacionados a su utilidad</legend>
            <div data-linea="1">
               <label for="modo_accion">Modo de acción sobre las plagas Efecto sobre los organismos-plagas (Ejemplo: tóxico por inhalación, contacto, sistémico u otras formas)</label>
               <textarea name="modo_accion" id="modo_accion" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['modo_accion'];?></textarea>
            </div>
				<div data-linea="4">
					<label for="modo_accion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['modo_accion_ref'];?>" name="modo_accion_ref" type="text" id="modo_accion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="5">
               <label for="organismos_nocivos">Organismos nocivos controlados</label>
               <textarea name="organismos_nocivos" id="organismos_nocivos" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['organismos_nocivos'];?></textarea>
            </div>
				<div data-linea="6">
					<label for="organismos_nocivos_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['organismos_nocivos_ref'];?>" name="organismos_nocivos_ref" type="text" id="organismos_nocivos_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="7">
               <label for="mecanismo_accion">Mecanismo de acción</label>
               <textarea name="mecanismo_accion" id="mecanismo_accion" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['mecanismo_accion'];?></textarea>
            </div>
				<div data-linea="8">
					<label for="mecanismo_accion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['mecanismo_accion_ref'];?>" name="mecanismo_accion_ref" type="text" id="mecanismo_accion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="9">
               <label for="ambito_aplicacion">Ambito de aplicación previsto (Ejemplo: campo, invernadero u otros)</label>
               <textarea name="ambito_aplicacion" id="ambito_aplicacion" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php $str=$datosGenerales['ambito_aplicacion']; $str==''?'N/A':$str; echo $str;?></textarea>
            </div>
				<div data-linea="10">
					<label for="ambito_aplicacion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['ambito_aplicacion_ref'];?>" name="ambito_aplicacion_ref" type="text" id="ambito_aplicacion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="11">
               <label for="condiciones_fitosanitarias">Condiciones fitosanitarias y ambientales para ser usado</label>
               <textarea name="condiciones_fitosanitarias" id="condiciones_fitosanitarias" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php $str=$datosGenerales['condiciones_fitosanitarias']; $str==''?'N/A':$str; echo $str;?></textarea>
            </div>
				<div data-linea="12">
					<label for="condiciones_fitosanitarias_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['condiciones_fitosanitarias_ref'];?>" name="condiciones_fitosanitarias_ref" type="text" id="condiciones_fitosanitarias_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="13">
               <label for="resistencia">Resistencia (información sobre desarrollo de resistencia y estrategias de monitoreo)</label>
               <textarea name="resistencia" id="resistencia" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['resistencia'];?></textarea>
            </div>
				<div data-linea="14">
					<label for="resistencia_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['resistencia_ref'];?>" name="resistencia_ref" type="text" id="resistencia_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="15">
               <label for="hoja_seguridad">Referencia de hoja de seguridad</label>
               <input value="<?php echo $datosGenerales['hoja_seguridad'];?>" name="hoja_seguridad" id="hoja_seguridad" placeholder="Ingrese referencia de la hoja de seguridad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
				
         </fieldset>

         <button type="submit" class="mas">Guardar</button>

      </form>
   </div>

   <div class="pestania" id="P4" style="display: block;">
      <form id="frmNuevoIngrediente5" data-rutaaplicacion="dossierPlaguicida" data-opcion="guardarIngrediente">
         <input type="hidden" id="pasoOpcion" name="pasoOpcion" value="5" />
         <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
         <input type="hidden" id="id_protocolo_ia" name="id_protocolo_ia" value="<?php echo $id_protocolo_ia;?>" />
         <input type="hidden" id="id_solicitud_ia" name="id_solicitud_ia" value="<?php echo $id_solicitud_ia;?>" />

         <fieldset class="verItemIA">
            <legend>Métodos analíticos</legend>
            <div data-linea="1">
               <label for="metodo_sustancia">Determinación de la sustancia activa pura</label>
               <textarea name="metodo_sustancia" id="metodo_sustancia" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['metodo_sustancia'];?></textarea>
            </div>
				<div data-linea="2">
					<label for="metodo_sustancia_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['metodo_sustancia_ref'];?>" name="metodo_sustancia_ref" type="text" id="metodo_sustancia_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="3">
               <label for="metodo_degradacion">Determinación de productos de degradación, isómeros, impurezas (de importancia toxicológica y ecotoxicológica) y de aditivos (Ej.: estabilizantes)</label>
               <textarea name="metodo_degradacion" id="metodo_degradacion" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['metodo_degradacion'];?></textarea>
            </div>
				<div data-linea="4">
					<label for="metodo_degradacion_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['metodo_degradacion_ref'];?>" name="metodo_degradacion_ref" type="text" id="metodo_degradacion_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="5">
               <label for="metodo_residuos">Determinación de residuos en plantas tratadas, productos agrícolas, alimentos procesados, suelo y agua. Se incluirá la tasa de recuperación y los límites de sensibilidad metodológica</label>
               <textarea name="metodo_residuos" id="metodo_residuos" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['metodo_residuos'];?></textarea>
            </div>
				<div data-linea="6">
					<label for="metodo_residuos_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['metodo_residuos_ref'];?>" name="metodo_residuos_ref" type="text" id="metodo_residuos_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
            <div data-linea="7">
               <label for="metodo_aire">Métodos analíticos para aire, tejidos y fluidos animales o humanos (cuando estén disponibles)</label>
               <textarea name="metodo_aire" id="metodo_aire" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['metodo_aire'];?></textarea>
            </div>
				<div data-linea="8">
					<label for="metodo_aire_ref">Referencia:</label>
					<input value="<?php echo $datosGenerales['metodo_aire_ref'];?>" name="metodo_aire_ref" type="text" id="metodo_aire_ref" placeholder="Ingrese referencia relacionada al punto superior" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>

         </fieldset>

         <button type="submit" class="mas">Guardar</button>

      </form>
   </div>

</body>


<script>
	var normativa=<?php echo json_encode($normativa); ?>;
	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	var id_solicitud_ia=<?php echo json_encode($id_solicitud_ia); ?>;

	var esDossierBloqueado=<?php echo json_encode($esDossierBloqueado); ?>;
	var paginaRegreso=<?php echo json_encode($paginaRegreso); ?>;
	
	var doc=<?php echo json_encode($doc); ?>;
	var subsanados=<?php echo json_encode($subsanados); ?>;
	var id_tramite_flujo = <?php echo json_encode($nombreOpcion); ?>; 

	$('document').ready(function(){

		if(normativa!=null && normativa=="NA"){
			$('.normativaVer').show();
			verItemsDeIa(solicitud.tiene_carta_acceso=='t');
		}
		else{
			$('.normativaVer').hide();
		}

		//verifica si es bloqueado
		if(esDossierBloqueado==1){
			//desactiva las entradas de datos	
			$('section#detalleItem').find("input:not([type=hidden])").attr('disabled', 'disabled');		
			$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');		
			$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPrevia,.regresar)').hide();
		
			var form=$('#regresar');
			form.append("<input type='hidden' id='idFlujo' name='idFlujo' value='"+<?php echo json_encode($idFlujo); ?>+"' />");
			form.append("<input type='hidden' id='nombreOpcion' name='nombreOpcion' value='"+id_tramite_flujo+"' />");
			form.attr('data-opcion', paginaRegreso);

		}
		else if(esDossierBloqueado==2){
			var form=$('#regresar');
			form.append("<input type='hidden' id='idFlujo' name='idFlujo' value='"+<?php echo json_encode($idFlujo); ?>+"' />");
			form.append("<input type='hidden' id='nombreOpcion' name='nombreOpcion' value='"+<?php echo json_encode($nombreOpcion); ?>+"' />");
			form.attr('data-opcion', paginaRegreso);
		}

		construirAnimacion(".pestania");

		//**************** ACTIVACION DE ANALISIS DEL INGREDIENTE ACTIVO **************************
		switch(paginaRegreso){
			case "abrirAnalizarDossier":	
				mostrarPuntosSubsanados();
				visualizarObservaciones();
				var div=$("#P1");
				div.append('<button id="btnObservar1" type="button" class="mas guardarObservaciones" disabled="disabled" >Guardar observaciones</button>');
				var div=$("#P2");
				div.append('<button id="btnObservar2" type="button" class="mas guardarObservaciones" disabled="disabled" >Guardar observaciones</button>');
				var div=$("#P3");
				div.append('<button id="btnObservar3" type="button" class="mas guardarObservaciones" disabled="disabled" >Guardar observaciones</button>');
				var div=$("#P4");
				div.append('<button id="btnObservar4" type="button" class="mas guardarObservaciones" disabled="disabled" >Guardar observaciones</button>');
				break;
			case "abrirAprobacionDossier":
				mostrarPuntosSubsanados();
				break;
			case "abrirSubsanarDossier":
				//mostrarPuntosSubsanados();
				activarObservaciones();
				activarSubsanacion();
				break;

		}
		
		distribuirLineas();
	});


	//******************************************************************************************************************

	$("#formula_estructural").keyup(function(){
		
		if($(this).val().trim()!=""){
			$("#idFileFormula").removeAttr("disabled");
			$("button.subirArchivo").removeAttr("disabled");
		}
		else{
			$("#idFileFormula").attr("disabled", "disabled");
			$("button.subirArchivo").attr("disabled", "disabled");
		}
	});

	function activarCargaImagen(){
		$('button.subirImagen').show();
		$('button.subirImagen').removeAttr("disabled");
	}

	$('button.subirImagen').click(function (event) {
		event.preventDefault();
		var boton = $(this);

		var archivo = boton.parent().find(".archivo");

        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");
        var filtroFile='png,jpg,gif,jpeg,bmp';
        if (filtroFile.toUpperCase().search( extension[extension.length - 1].toUpperCase()>0)) {
        	
        	var nombre_archivo="DG_formula_"+solicitud.id_solicitud+"_"+id_solicitud_ia;
        		
            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton,rutaArchivo)
            );
        } else {
        	estado.html('Formato incorrecto, solo se admite archivos en formato png, jpg, gif, jpeg,bmp');
            archivo.val("");
        }
	});
	
	function carga(estado, archivo, boton,rutaArchivo) {
		this.esperar = function (msg) {
			estado.html("Cargando el archivo...");
			archivo.addClass("amarillo");
		};

		this.exito = function (msg) {
			estado.html("Archivo listo para ser guardado");
			archivo.removeClass("amarillo");
			archivo.addClass("verde");
			boton.attr("disabled", "disabled");

			guardarArchivo(boton);

		};

		this.error = function (msg) {
			estado.html(msg);
			archivo.removeClass("amarillo");
			archivo.addClass("rojo");
		};
	}

	function guardarArchivo(boton){
		var rutaArchivo = boton.parent().find(".rutaArchivo");
		
		var str=rutaArchivo.val();
		
		
		rutaArchivo.val(str);
		
		$('#formula_estructural_ruta').attr('src','');
		$('#formula_estructural_ruta').attr('src',str);
		
	}

	//******************************************************************************************************************


	$('.bsig').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();
	});

	$('.bant').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();
	});

	$('.regresar').click(function (event) {
		event.preventDefault();
		var form = $(this).parent();
		
		abrir(form, event, true);
	});

	//**************************************** campos numericos **********************************
	$('#partida_arancelaria').numeric();
	$('#punto_fusion').numeric();
	$('#punto_ebullicion').numeric();
	$('#punto_ignicion').numeric();

	$('#ad_cantidad').numeric();


	//*********************************  CARTAS ACCESO ************************************************

	$('input[name="tiene_carta_acceso"]').click(function () {
		verItemsDeIa($(this).val()== "SI");

	});

	function verItemsDeIa(tieneCartaAcceso){
		if(tieneCartaAcceso){
			$('#div_tiene_carta_acceso').show();
			$('.verItemIA').hide();
			$('.bsig').attr("disabled", "disabled");
		}
		else{
			$('#div_tiene_carta_acceso').hide();
			$('.verItemIA').show();
			
			if(id_solicitud_ia>0)
				$('.bsig').removeAttr("disabled");
			else
				$('.bsig').attr("disabled", "disabled");
		}
	}


	//*************************
	$('#estado_fisico').change(function(){
		if($(this).val()=='EFC_LIQU'){
			$('.verEstadoLiquido').show();
		}
		else{
			$('.verEstadoLiquido').hide();
		}
	});


	//**************************************** SUBMIT ****************************************

	$("#frmNuevoIngrediente1").submit(function(event){
		event.preventDefault();

		var error = false;
		if($("#tiene_carta_accesoSI").is(":checked")){
			if(!esNoNuloEsteCampo("#carta_acceso"))
				error = true;
		}
		else{
			if(!esNoNuloEsteCampo("#peso"))
				error = true;
			if(!esNoNuloEsteCampo("#formula_estructural"))
				error = true;
			if(!esNoNuloEsteCampo("#grado_pureza"))
				error = true;
			if(!esNoNuloEsteCampo("#isomeros"))
				error = true;
			if(!esNoNuloEsteCampo("#impurezas"))
				error = true;
			if(!esNoNuloEsteCampo("#aditivos"))
				error = true;

		}

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}

		if(!esNoNuloEsteCampo("#rutaArchivo")){
			mostrarMensaje("Favor adjunte la formula estructural","FALLO");
			return;
		}
			

		borrarMensaje();

		if (!error){
			ejecutarJson($(this),new exitoGuardar1());
		}
	});

	function exitoGuardar1(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			id_solicitud_ia=msg.id;
			if((id_solicitud_ia>0) && (!$('#tiene_carta_accesoSI').is(':checked')))
				$('.bsig').removeAttr("disabled");
			else
				$('.bsig').attr("disabled", "disabled");
		};
	}



	$("#frmNuevoIngrediente3").submit(function(event){
		event.preventDefault();

		var error = false;
		
		if(!esNoNuloEsteCampo("#estado_fisico"))
			error = true;
		if(!esNoNuloEsteCampo("#color"))
				error = true;
		if(!esNoNuloEsteCampo("#olor"))
				error = true;
		if(!esNoNuloEsteCampo("#punto_fusion"))
			error = true;
		if($('#estado_fisico').val()=='EFC_LIQU'){
			if(!esNoNuloEsteCampo("#punto_ebullicion"))
				error = true;
			if(!esNoNuloEsteCampo("#viscosidad"))
				error = true;
			if(!esNoNuloEsteCampo("#tension_superficial"))
				error = true;
		}
		if(!esNoNuloEsteCampo("#densidad"))
				error = true;
		if(!esNoNuloEsteCampo("#presion_vapor"))
			error = true;
		if(!esNoNuloEsteCampo("#espectro_absorcion"))
			error = true;
		if(!esNoNuloEsteCampo("#solubilidad_agua"))
			error = true;
		if(!esNoNuloEsteCampo("#solubilidad_disolventes"))
			error = true;

		if(!esNoNuloEsteCampo("#coeficiente_particion"))
			error = true;
		if(!esNoNuloEsteCampo("#punto_ignicion"))
			error = true;

		if(!esNoNuloEsteCampo("#propiedades_explosivas"))
			error = true;
		if(!esNoNuloEsteCampo("#propiedades_oxidantes"))
			error = true;
		if(!esNoNuloEsteCampo("#reactividad_envase"))
			error = true;


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		if (!error){
			ejecutarJson($(this));
		}
	});

	$("#frmNuevoIngrediente4").submit(function(event){
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#modo_accion"))
			error = true;
		if(!esNoNuloEsteCampo("#organismos_nocivos"))
			error = true;
		if(!esNoNuloEsteCampo("#mecanismo_accion"))
				error = true;
		if(!esNoNuloEsteCampo("#ambito_aplicacion"))
				error = true;
		if(!esNoNuloEsteCampo("#condiciones_fitosanitarias"))
				error = true;
		if(!esNoNuloEsteCampo("#resistencia"))
				error = true;
		if(!esNoNuloEsteCampo("#hoja_seguridad"))
				error = true;


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		if (!error){
			ejecutarJson($(this));
		}
	});

	$("#frmNuevoIngrediente5").submit(function(event){
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#metodo_sustancia"))
			error = true;
		if(!esNoNuloEsteCampo("#metodo_sustancia_ref"))
			error = true;
		if(!esNoNuloEsteCampo("#metodo_degradacion"))
			error = true;
		if(!esNoNuloEsteCampo("#metodo_degradacion_ref"))
			error = true;
		if(!esNoNuloEsteCampo("#metodo_residuos"))
			error = true;
		if(!esNoNuloEsteCampo("#metodo_residuos_ref"))
			error = true;
		if(!esNoNuloEsteCampo("#metodo_aire"))
			error = true;
		if(!esNoNuloEsteCampo("#metodo_aire_ref"))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		if (!error){
			ejecutarJson($(this));
		}
	});

	//**************************************** Acciones de evaluacion del Ingrediente activo *******************************

	function visualizarObservaciones(){
		for(var i in doc){
			var elemento=doc[i];
			if(elemento.es_observable=="S"){
				try{
					colocarObservaciones(elemento.elemento);
				}catch(e){}
			}
		}
	}

	function colocarObservaciones(elemento){
		var div = $('section#detalleItem').find('#' + elemento).parent();
		if (div.is(":visible")) {
			if(div.find(".observador").length==0){
				div.append('<button type="button" class="observador observar" data-id="'+elemento+'" />');
				div.append('<button type="button" class="observador quitar" disabled="disabled" />');
				
			}
		}
	}

	$("body").off("click", ".observar").on("click", ".observar", function (event) {
		event.preventDefault();
		var div = $(this).parent();

		var ids=$(this).data("id");
		var id='';
		if(ids!=null || ids!='undefined' || ids!=''){

			id="obs_GI_"+ids;
		}
		div.append('<input type="text" value"" class="observacionRealizada" data-distribuir="no" id="'+id+'" name="'+id+'"/>');
		$(this).prop("disabled", true);
		div.find("button.quitar").prop("disabled", false);
		gestionarBotonesDesicion();
	});

	$("body").off("click", ".quitar").on("click", ".quitar", function (event) {
		event.preventDefault();
		var div = $(this).parent();
		div.find("input.observacionRealizada").remove();
		$(this).prop("disabled", true);
		div.find("button.observar").prop("disabled", false);
		gestionarBotonesDesicion();
	});

	function gestionarBotonesDesicion(){
		var items=$('#detalleItem').find("input.observacionRealizada:enabled");
		if(items.length>0){
			$('.guardarObservaciones').removeAttr('disabled');
			
		}
		else{
			$('.guardarObservaciones').attr("disabled", "disabled");
		}
	}

	//*******************************************************
	$("body").off("click", ".guardarObservaciones").on("click", ".guardarObservaciones", function (event) {
		event.preventDefault();
		
		var param={opcion_llamada:'guardarObservacionesIA',
			id_solicitud_ia:id_solicitud_ia,
			id_tramite_flujo:id_tramite_flujo
		};

		//envia las observaciones recogidas	
		
		$('#detalleItem').find("input.observacionRealizada").each(function(j) {
			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param[item.attr("id")]=item.val();

			}

		});
		
		mostrarMensaje("Enviando observaciones...","");
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,resultadoFlujo);

	});

	function resultadoFlujo(items){
		if(items!=null){
			mostrarMensaje('Observaciones han si registradas','EXITO');		
			//envia las observaciones mas recientes
			var form=$('#regresar');
			//verifica si ya existe el item			
			if(form.find('#observaciones_ia').length>0){
				return;
			}
			else{
				var param={};
				$('#detalleItem').find("input.observacionRealizada").each(function(j) {
					if ($(this).attr("id") !== undefined) {
						item=$(this);
						param[item.attr("id")]=item.val();
					}
				});

				form.append("<input type='hidden' id='observaciones_ia' name='observaciones_ia' value='"+JSON.stringify(param)+"' />");
			}
		}else{
			mostrarMensaje('Errores al registrar las observaciones','FALLO');
		}
	}

	function mostrarPuntosSubsanados(){
		if(subsanados!=null && subsanados.length>0){
			for(var i in subsanados){
				var elem=subsanados[i].elemento;
				$('#'+elem).css({backgroundColor: '#2BC253'});
				var obs=subsanados[i];
				$('#'+obs.elemento).css({backgroundColor: '#2BC253'});

				var div = $('#'+obs.elemento).parent();
				
				var id="obs_antes_GI_"+obs.elemento+"_"+obs.id_tramite_flujo;
				var str='<input type="text" value"" class="observacionRealizadaVer" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';

				div.append(str);

				$('#'+id).val("Observacion: "+obs.observacion);
			}
		}
	}

	function activarObservaciones() {
		if(subsanados!=null && subsanados.length>0){
			for(var i in subsanados){
				var obs=subsanados[i];
				var elemento=$('#'+obs.elemento);
				var div = elemento.parent();
				var id="obs_GI_"+obs.elemento+"_"+obs.id_tramite_flujo;
				var str='<input type="text" value"" class="observacionRealizadaVer" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';
				div.append(str);
				$('#'+id).val("Observacion: "+obs.observacion);
				switch(obs.elemento_tipo){
					case null:
					case '':
						if(elemento.prop("disabled") == true){
							elemento.prop('disabled', false);
							//elemento.off('click');					//Para impedir modificar el resto del documento al click
							//elemento.off('change');					//Para impedir modificar el resto del documento al cambio
						}
						break;
					case 'referencia':
						var strElementos=obs.ver.trim();
						
						elementos=$(strElementos);
						elementos.show();
						elementos.prop('disabled', false);
						break;
					case 'subgrupo':
						var strElementos=obs.ver.trim();
						var padre=elemento.parent().parent();
						elementos=padre.find(strElementos);
						elementos.show();
						elementos.prop('disabled', false);
						break;
				}

			}
		}
	}

	//*******************************************************

	function activarSubsanacion(){
		$('.pestania').each(function(){
			$(this).append('<button type="button" class="guardar btnSubsanar">Guardar subsanación</button>');
		});
		
	}

	$("body").off("click", ".btnSubsanar").on("click", ".btnSubsanar", function (event) {
		event.preventDefault();
	
		borrarMensaje();
		
		var param={opcion_llamada:'guardarSubsanacionesIA',
			id_flujo:id_flujo,
			id_tramite_flujo:id_tramite_flujo,
			id_solicitud_ia:id_solicitud_ia,
			id_solicitud:solicitud.id_solicitud	
		};

		$('section#detalleItem').find("input:enabled").each(function(j) {

			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();
			}
		});

		$('section#detalleItem').find("textarea:enabled").each(function(j) {
			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();
			}
		});
		$('section#detalleItem').find("select:enabled").each(function(j) {
			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();
			}

		});

		mostrarMensaje('Guardando subsanaciones ...','');
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,resultadoSubsanacion);

	});

	function resultadoSubsanacion(items){
		if(items!=null){
			mostrarMensaje('Subsanaciones han sido guardadas','EXITO');			
		}else{
			mostrarMensaje('Favor revise si todos los puntos subsanados, no pudo guardar la información','FALLO');
		}
	}

	//*******************************************************

	//******************************* COLOCAR ELEMENTOS PARA OBSERVAR ************************************

	$.each(["show"], function(){
		var _oldFn = $.fn[this];
		$.fn[this] = function(){
			var hidden = this.find(":hidden").add(this.filter(":hidden"));
			var result = _oldFn.apply(this, arguments);
			hidden.filter(":visible").each(function(){
				$(this).triggerHandler("show"); //No bubbling
			});
			return result;
		};
	});

	$(".pestania").bind("show", function(){
		if(paginaRegreso=='abrirAnalizarDossier'){
			visualizarObservaciones();
		}
	});

</script>

</html>



