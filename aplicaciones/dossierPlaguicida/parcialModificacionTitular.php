


<div class="pestania" id="P4" style="display: block;">
	<form id='frmNuevaSolicitud2' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud'>
		<input type="hidden"  id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>"/>
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P2" />
		
				<fieldset>
					<legend>Datos del nuevo titular</legend>
             
               <div data-linea="1">
                  <label for="ruc_nuevo" >RUC del nuevo titular:</label>
                  <input value="" name="ruc_nuevo" type="text" id="ruc_nuevo" placeholder="Ingrese quién es el nuevo titular" maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>

               <div data-linea="2">
                  <label >Formato para la modificación del Registro Nacional (Firmado por los representantres legales de la empresa que transfiere el registro y de la empresa que acepta la transferencia)</label>
                  <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
                  <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
                  <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
                  <button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/dossierPlaguicida/anexos" disabled="disabled">Subir archivo</button>
               </div>
               <div data-linea="3">
                  <label>Carta da aceptación de transferencia del registro del producto</label>
                  <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
                  <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
                  <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
                  <button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/dossierPlaguicida/anexos" disabled="disabled">Subir archivo</button>
               </div>
               <div data-linea="4">
                  <label>Última etiqueta aprobada por AGROCALIDAD</label>
                  <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
                  <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
                  <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
                  <button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/dossierPlaguicida/anexos" disabled="disabled">Subir archivo</button>
               </div>

				
				</fieldset>

		<button type="submit" class="guardar">Guardar solicitud</button>
	</form>
</div>

