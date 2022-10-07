<?php 
session_start();

	require_once '../../clases/Conexion.php';	
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';
	require_once '../../clases/ControladorCatalogos.php';


	$numeroPestania = $_POST['numeroPestania'];
	
	$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud
	$id_solicitud = $_POST['id'];
	$identificador=$idUsuario;					//Es el duenio del documento, puede variar si ya hay un protocolo y el usuario es alguien de revision, aprobacion, etc..

	$conexion = new Conexion();
	$cr = new ControladorRegistroOperador();
	$ce = new ControladorEnsayoEficacia();	
	$cg=new ControladorDossierPlaguicida();
	$cc = new ControladorCatalogos();
	
	
	$datosGenerales=array();	
	$fabricantes=array();
	$formuladores=array();
	$anexosSolicitud=array();
	$operador = array();
		
	$paises=$ce->listarLocalizacion($conexion);
	$informesFinales=array();
	$presentaciones=array();
	$cultivos=array();
	$plagas=array();
	$contieneParaquat=false;

	if($id_solicitud!=null && $id_solicitud!='_nuevo'){

		$datosGenerales=$cg->obtenerModificacion($conexion, $id_solicitud);
		$identificador=$datosGenerales['identificador'];						//El duenio del documento
		
		 $fabricantes=$cg->obtenerFabricantesModificacion($conexion,$id_solicitud,'F');
		 $formuladores=$cg->obtenerFabricantesModificacion($conexion,$id_solicitud,'R');

		 $presentaciones=$cg->obtenerPresentaciones($conexion,$id_solicitud);
		
		 $anexosSolicitud=$cg->obtenerArchivosAnexos($conexion,$id_solicitud);
	}
	
	//busca los datos del operador
	$res = $cr->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);
		
	$registroProductosMatriz=$ce->obtenerProductosMatrizRegistrados($conexion,$identificador);	//para clones
	$clonesRegistrados=$ce->obtenerClonesRegistrados($conexion,$identificador);	//para clones
	
	$cultivosNombres = $ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');

	

	$declaracionLegal=$ce->obtenerTitulo($conexion,'EP');

	//****************** ANEXOS **************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
	$pathAnexo=$paths['ruta'];
	
	
?>

<header>
	<h1>Solicitud de modificación de registros</h1>
</header>

<div id="estado"></div>



<div class="pestania" id="P1" style="display: block;">
	<form id='frmNuevaSolicitud' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosModificacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		<input type="hidden"  id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>"/>
      <input type="hidden" id="opcion" name="opcion" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P1" />
		
		<fieldset>
			<legend>Solicitud de modificación de registro</legend>
			<div data-linea="1">
				<label for="tipo_modificacion">Seleccione la modificación de registro:</label> 
				<select name="tipo_modificacion" id="tipo_modificacion" required>
					<option value="">Seleccione....</option>
					<?php 
						$normativaLista = $ce->listarElementosCatalogo($conexion,'M1C1');
						foreach ($normativaLista as $key=>$item){
							if(strtoupper($item['codigo']) == strtoupper($datosGenerales['tipo_modificacion'])){
								echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
							}
						}
                    ?> 
				</select>				
			</div>
			 <div data-linea="2">
            <label for="registro">Número de registro y nombre</label>            
				 <select name="registro" id="registro" required>
					<option value="">Seleccione....</option>
					<?php 
					
					foreach ($registroProductosMatriz as $key=>$item){
						if(strtoupper($item['numero_registro']) == strtoupper($datosGenerales['registro'])){
							echo '<option value="' . $item['numero_registro'] . '" data-id="' . $item['id_producto'] . '" selected="selected">(' .$item['numero_registro'].') '. $item['nombre_comun'] . '</option>';
						}else{
							echo '<option value="' . $item['numero_registro'] . '" data-id="' . $item['id_producto'] . '">(' .$item['numero_registro'].') '.$item['nombre_comun'] . '</option>';
						}
					}
                    ?>
				</select>
         </div>
			

			<div data-linea="3">
				<label for="clones_modificados">Clones que cambiarán de titularidad</label> 
				<ol id="clones_modificados">
					
					
				</ol>
			</div>
				
        
		</fieldset>
			
		<button id="btnGuardarPrimero" type="button" class="guardar">Guardar solicitud</button>

	</form>
</div>


<div class="pestania" id="P2" style="display: block;">
   <form id='frmNuevaSolicitud2' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosModificacion' class="verRegistroNoClon">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P2" />

      <fieldset>
         <legend>Etiquetado del producto formulado</legend>
              
            <div data-linea="2">
               <label for="presentacion_tipo">Ingrese la presentación comercial:</label>
               <select name="presentacion_tipo" id="presentacion_tipo" class="col-20">
                  <option value="">Seleccione....</option><?php
																			 $items=$ce->listarElementosCatalogo($conexion,'P2C0');
																			 foreach ($items as $key=>$item){
																				 echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
																			 }
                                                          ?>
               </select>
            </div>
            <div data-linea="3">
               <label for="presentacion_cantidad">Contenido:</label>
               <input value="" name="presentacion_cantidad" type="text" id="presentacion_cantidad" maxlength="3" data-er="^[0-9.]+$" />
            </div>
            <div data-linea="3">
               <label for="presentacion_unidad">Unidad:</label>
               <select name="presentacion_unidad" id="presentacion_unidad">
                  <option value="">Seleccione....</option><?php
																			 $items=$ce->obtenerUnidadesMedida($conexion,'DG_PRE');
																			 foreach ($items as $key=>$item){
																				 echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
																			 }
                                                          ?>
               </select>

            </div>
				<div data-linea="5">
               <label for="partida_arancelaria">Partida arancelaria:</label>
               <input value="" name="partida_arancelaria" type="text" id="partida_arancelaria" maxlength="10" data-er="^[0-9.]+$" />
            </div>
				<div data-linea="6">
               <label for="codigo_complementario">Código complementario:</label>
               <select name="codigo_complementario" id="codigo_complementario">
                  <option value="">Seleccione....</option>
						<?php								
						for ($i=0;$i<6;$i++){
							echo '<option value="' . $i . '">' . str_pad($i, 4, '0', STR_PAD_LEFT). '</option>';
						}
                        ?>
               </select>
            </div>
				<div data-linea="6">
               <label for="codigo_suplementario">Código suplementario:</label>
               <select name="codigo_suplementario" id="codigo_suplementario">
                  <option value="">Seleccione....</option>
						<?php								
						for ($i=0;$i<11;$i++){
							echo '<option value="' . $i . '">' . str_pad($i, 4, '0', STR_PAD_LEFT). '</option>';
						}
                        ?>
               </select>
            </div>
           
         <button type="button" id="btnAddPresentacion" class="mas">Agregar</button>
         <table id="tblPresentacion" style="width:100%">
            <thead>
					<tr>								
						<th>Tipo</th>
						<th>Presentación</th>
						<th>Unidad</th>
						<th>P. arancelaria</th>
						<th>C. complementario</th>
						<th>C. suplementario</th>
						<th></th>
					</tr>
            </thead>
								                      
            <tbody></tbody>
         </table>
              
         <div class="justificado">
            <label for="precaucion_uso">Precauciones y advertencias de uso y aplicación:</label>
            <input value="<?php echo $datosGenerales['precaucion_uso'];?>" name="precaucion_uso" type="text" id="precaucion_uso" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div class="justificado">
            <label for="almacenamieno_manejo">Almacenamiento y manejo del producto:</label>
            <input value="<?php echo $datosGenerales['almacenamieno_manejo'];?>" name="almacenamieno_manejo" type="text" id="almacenamieno_manejo" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <hr />

         <div class="justificado">
            <label for="aux_ingestion">Medidas relativas a primeros auxilios:</label>
            
            <textarea name="aux_ingestion" id="aux_ingestion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" >
					<?php echo htmlspecialchars($datosGenerales['aux_ingestion']); ?>
					
				</textarea>
         </div>

         <div class="justificado">
            <label for="aux_telefono">Teléfono de la empresa en caso de intoxicación:</label>
            <input value="<?php echo $datosGenerales['aux_telefono'];?>" name="aux_telefono" type="text" id="aux_telefono" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
         </div>

         <div class="justificado">
            <label for="aux_disposicion">Medidas relativas para la disposición de envases vacíos:</label>
            <textarea name="aux_disposicion" id="aux_disposicion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" >
					<?php
					$items=$ce->listarElementosCatalogo($conexion,'P2C5');
					foreach ($items as $key=>$item){
						echo  $item['nombre'] ;
					}
                    ?>
				</textarea>
						
         </div>

         <div class="justificado">
            <label for="aux_ambiente">Medidas para la protección del medio ambiente:</label>
            <input value="<?php echo $datosGenerales['aux_ambiente'];?>" name="aux_ambiente" type="text" id="aux_ambiente" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
         </div>

         <div class="justificado">
            <label for="aux_instrucciones">Instrucciones de uso y manejo:</label>
            <textarea name="aux_instrucciones" id="aux_instrucciones" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" >
					
					<?php echo htmlspecialchars($datosGenerales['aux_instrucciones']); ?>
				</textarea>
         </div>
         <div class="justificado">
            <label for="frecuencia_aplicacion">Epoca y frecuencia de aplicación:</label>
            <input value="<?php echo $datosGenerales['frecuencia_aplicacion'];?>" name="frecuencia_aplicacion" type="text" id="frecuencia_aplicacion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
         </div>
         <div class="justificado">
            <label for="responsabilidad">Responsabilidad:</label>
            <textarea name="responsabilidad" id="responsabilidad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" >
					<?php 
					if($datosGenerales['responsabilidad']==null || $datosGenerales['responsabilidad']='' ){
						echo "El titular del Registro garantiza que las características físico químicas del producto contenido en este envase corresponden a las anotadas en la etiqueta y es eficaz para los fines aquí recomendados, si se usa y maneja de acuerdo con las condiciones e instrucciones dadas";
					}
					else{
						echo $datosGenerales['responsabilidad'];
					}
                    ?>
				</textarea>
         </div>
         <div class="justificado ingrediente_paraquat">
            <label for="paraquat">Frase para Paraquat:</label>
            <textarea name="paraquat" id="paraquat" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" >
						
					<?php echo htmlspecialchars($datosGenerales['paraquat']); ?>
				</textarea>
         </div>
					
         <div data-linea="10">
            <label for="tiene_hoja_informativa">Tiene hoja informativa ?:</label>
            SI<input type="radio" id="tiene_hoja_informativaSI" name="tiene_hoja_informativa" value="SI" />
            NO<input type="radio" id="tiene_hoja_informativaNO" name="tiene_hoja_informativa" value="NO" />
         </div>
         <div class="justificado">
            <label for="hoja_informativa">Descripción de la hoja informativa:</label>
            <textarea name="hoja_informativa" id="hoja_informativa" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['hoja_informativa']); ?>
					
				</textarea>
            <input value="<?php echo $datosGenerales['hoja_informativa_ref'];?>" name="hoja_informativa_ref" type="text" id="hoja_informativa_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P3" style="display: block;">

	<form id='frmNuevaSolicitud3' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosModificacion'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P3" />

      <fieldset>
         <legend>Datos del nuevo titular</legend>

         <div data-linea="1">
            <label for="ruc_nuevo">RUC del nuevo titular:</label>
            <input value="" name="ruc_nuevo" type="text" id="ruc_nuevo" placeholder="Ingrese quién es el nuevo titular" maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div class="justificado">
            <label>Formato para la modificación del Registro Nacional (Firmado por los representantres legales de la empresa que transfiere el registro y de la empresa que acepta la transferencia)</label>
            <input id="ruta_13" type="hidden" class="rutaArchivo" name="rutaArchivo_1" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*"  />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" >Subir archivo</button>
         </div>
         <div class="justificado">
            <label>Carta da aceptación de transferencia del registro del producto</label>
            <input id="ruta_23" type="hidden" class="rutaArchivo" name="rutaArchivo_2" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*"  />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" >Subir archivo</button>
         </div>
         <div class="justificado">
            <label>Última etiqueta aprobada por AGROCALIDAD</label>
            <input id="ruta_33" type="hidden" class="rutaArchivo" name="rutaArchivo_3" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*"  />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" >Subir archivo</button>
         </div>


      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

   <form id='frmNuevaSolicitud4' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosModificacion'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P4" />

      <fieldset>
         <legend>Datos sobre el cambio, adición o retiro de usos, cultivos o plagas</legend>
			<div data-linea="1">
				<label for="informe_final">Seleccione el informe final aprobado a usar:</label>
				<select name="informe_final" id="informe_final" required>
					<option value="">Seleccione....</option>

				</select>
			</div>

         <div data-linea="3">
            <label for="cultivo_cientifico">Nombre cientifico del cultivo</label>
            <select name="cultivo_cientifico" id="cultivo_cientifico" required>
               <option value="">Seleccione....</option>
					<?php
						foreach ($cultivosNombres as $key=>$item){
							if(strtoupper($item['id_producto']) == strtoupper($datosGenerales['cultivo'])){
								echo '<option value="' . $item['id_producto'] . '" selected="selected">' . $item['nombre_cientifico'] . '</option>';
							}else{
								echo '<option value="' . $item['id_producto'] . '">' . $item['nombre_cientifico'] . '</option>';
							}
						}
                    ?>
            </select>
         </div>
         <div data-linea="4">
            <label for="cultivo_comun">Nombre común del cultivo</label>
            <select name="cultivo_comun" id="cultivo_comun" disabled="disabled">
               <option value="">Seleccione....</option>
					<?php
						foreach ($cultivosNombres as $key=>$item){
							if(strtoupper($item['id_producto']) == strtoupper($datosGenerales['cultivo'])){
								echo '<option value="' . $item['id_producto'] . '" selected="selected">' . $item['nombre_comun'] . '</option>';
							}else{
								echo '<option value="' . $item['id_producto'] . '">' . $item['nombre_comun'] . '</option>';
							}
						}
                    ?>
            </select>
         </div>

         <div data-linea="5">
            <label for="dosis">Dosis:</label>
            <input value="" name="dosis" type="text" id="dosis"  maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />

         </div>
			<div data-linea="5"  id="disUnidadDosis">
				<label for="dosis_unidad"> Unidad</label>
				<select name="dosis_unidad" id="dosis_unidad">
					<option value="">Seleccione....</option>
					<?php
					
					$respuesta=$cc->listarUnidadesMedidaXTipo($conexion,'composicion');      																			 
					while ($item = pg_fetch_assoc($respuesta)){
						if(strtoupper($item['id_unidad_medida']) == strtoupper($datosGenerales['unidad_dosis'])){
							echo '<option value="' . $item['id_unidad_medida'] . '" selected="selected" data-codigo="'.$item['codigo'].'">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['id_unidad_medida'] . '" data-codigo="'.$item['codigo'].'">' . $item['nombre'] . '</option>';
						}
					}
                    ?>
				</select>
			</div>

			<div data-linea="7">
            <label for="gasto_agua">Gasto de agua:</label>
            <input value="" name="gasto_agua" type="text" id="gasto_agua"  maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />

         </div>
			<div data-linea="7"  id="disUnidadDosis">
				<label for="gasto_agua_unidad"> Unidad</label>
				<select name="gasto_agua_unidad" id="gasto_agua_unidad">
					<option value="">Seleccione....</option>
					<?php
					

					$respuesta=$cc->listarUnidadesMedidaXTipo($conexion,'composicion');      																			 
					while ($item = pg_fetch_assoc($respuesta)){
						if(strtoupper($item['id_unidad_medida']) == strtoupper($datosGenerales['unidad_dosis'])){
							echo '<option value="' . $item['id_unidad_medida'] . '" selected="selected" data-codigo="'.$item['codigo'].'">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['id_unidad_medida'] . '" data-codigo="'.$item['codigo'].'">' . $item['nombre'] . '</option>';
						}
					}

                    ?>
				</select>
			</div>

			<div data-linea="9">
            <label for="perido_carencia">Período de carencia:</label>
            <input value="" name="perido_carencia" type="text" id="perido_carencia"  maxlength="13" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
			<div data-linea="9"  id="disUnidadDosis">
				<label for="perido_carencia_unidad"> Unidad</label>
				<select name="perido_carencia_unidad" id="perido_carencia_unidad">
					<option value="">Seleccione....</option>
					<?php
					

					$respuesta=$cc->listarUnidadesMedidaXTipo($conexion,'composicion');      																			 
					while ($item = pg_fetch_assoc($respuesta)){
						if(strtoupper($item['id_unidad_medida']) == strtoupper($datosGenerales['unidad_dosis'])){
							echo '<option value="' . $item['id_unidad_medida'] . '" selected="selected" data-codigo="'.$item['codigo'].'">' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['id_unidad_medida'] . '" data-codigo="'.$item['codigo'].'">' . $item['nombre'] . '</option>';
						}
					}
                    ?>
				</select>
			</div>

			<div data-linea="10">
				<input type="hidden"  id="eliminar_usos" name="eliminar_usos" value="<?php echo $datosGenerales['eliminar_usos'];?>"/>
				<label >Eliminar usos aprobados</label>

			</div>
			<div data-linea="11">
				<input type="hidden"  id="eliminar_plagas" name="eliminar_plagas" value="<?php echo $datosGenerales['eliminar_plagas'];?>"/>
				<label >Eliminar plagas aprobadas</label>

				<div id="usos_plagas">

				</div>
			</div>
			<hr/>
         <div class="justificado">
            <label>Formato para la modificación del Registro Nacional (Firmado por el Representante Legal)</label>
            <input id="ruta_1" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>
         <hr />
         <div class="justificado">
            <label>Sustento del perio de carencia (PHI)</label>
            <input id="ruta_2" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>
         <hr />
         <div class="justificado">
            <label>Sustento de LMR's</label>
            <input id="ruta_3" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>
         <hr />
         <div class="justificado">
            <label>Última etiqueta aprobada por AGROCALIDAD</label>
            <input id="ruta_4" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>
         <hr />
         <div class="justificado">
            <label>Informe de evaluación de Riesgo por nuevo uso (Aplica para dosis mayores a las ya registradas)</label>
            <input id="ruta_5" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>



      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

   <form id='frmNuevaSolicitud5' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosModificacion'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P5" />

      <fieldset>
         <legend>Adición de fabricante, formulador, país de origen</legend>

         <div class="justificado">
            <label>Certificado de análisis y composición del ingrediente activo (De cada fabricante declarado)</label>
            <input id="ruta_1" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>
         <div class="justificado">
            <label>Carta(s) de autorización originales debidamente legalizada, apostillada o consularizada según corresponda emitida por el/los frabicantes del/los ingredientes activos</label>
            <input id="ruta_2" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>
         <div class="justificado">
            <label>Carta(s) de autorización originales debidamente legalizada, apostillada o consularizada según corresponda emitida por el/los formuladores.</label>
            <input id="ruta_3" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>


      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>

	
		<fieldset>
			<legend>Datos de Fabricantes</legend>
         <input type="hidden" value="" name="fabricante_id"  id="fabricante_id" />
					
         <div data-linea="1">
            <label for="fabricante_nombre" class="opcional">Nombre:</label>
            <input value="" name="fabricante_nombre" type="text" id="fabricante_nombre" placeholder="Nombre del fabricante" class="cuadroTextoCompleto" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
			<div data-linea="3" >
				<label for="fabricante_pais">País de origen del ingrediene activo</label>
				<select name="fabricante_pais" id="fabricante_pais">
					<option value="">Seleccione....</option>
					<?php
					foreach ($paises as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
					}
                    ?>
				</select>
			</div>
			<div data-linea="4" >
                  <label for="fabricante_direccion" class="opcional">Dirección:</label>
                  <input value="" name="fabricante_direccion" type="text" id="fabricante_direccion" placeholder="Dirección del fabricante" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="5" class="fabricanteNacional">
                  <label for="fabricante_representante" class="opcional">Representante legal:</label>
                  <input value="" name="fabricante_representante" type="text" id="fabricante_representante" placeholder="Dirección del fabricante" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="6" class="fabricanteNacional">
                  <label for="fabricante_correo" >Correo electrónico:</label>
                  <input value="" name="fabricante_correo" type="text" id="fabricante_correo" placeholder="Correo electrónico"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="7" class="fabricanteNacional">
                  <label for="fabricante_telefono" class="opcional">Teléfono:</label>
                  <input value="" name="fabricante_telefono" type="text" id="fabricante_telefono" placeholder="Teléfono" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
			<div class="justificado">
            <label for="fabricante_carta" class="opcional">Referecia de carta(s) de autorización original(es) debidamente legalizada, apostillada o consularizada</label>
            <input value="" name="fabricante_carta" type="text" id="fabricante_carta" placeholder="Ingrese referencia" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
				
			<hr/>
			<div>
				<label for="tiene_contrato">Tiene contratos de manufactura ?</label>
				SI<input type="radio" id="tiene_contratoSI" name="tiene_contrato" value="SI"  />
				NO<input type="radio" id="tiene_contratoNO" name="tiene_contrato" value="NO"  />
			</div>
			<hr/>
												
				<div data-linea="10" class="fabricanteManufacturador">
					<label for="manufacturador_nombre" >Nombre:</label>
					<input value="" name="manufacturador_nombre" type="text" id="manufacturador_nombre" placeholder="Nombre del Manufacturador" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="11" class="fabricanteManufacturador">
					<label for="manufacturador_pais">País de origen del ingrediene activo</label>
					<select name="manufacturador_pais" id="manufacturador_pais">
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
					<input value="" name="manufacturador_direccion" type="text" id="manufacturador_direccion" placeholder="Dirección" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="13" class="fabricanteManufacturador">
					<label for="manufacturador_representante" class="opcional">Representante legal:</label>
					<input value="" name="manufacturador_representante" type="text" id="manufacturador_representante" placeholder="" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="14" class="fabricanteManufacturador">
					<label for="manufacturador_correo" class="opcional">Correo electrónico:</label>
					<input value="" name="manufacturador_correo" type="text" id="manufacturador_correo" placeholder="" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="15" class="fabricanteManufacturador">
					<label for="manufacturador_telefono" class="opcional">Telefono:</label>
					<input value="" name="manufacturador_telefono" type="text" id="manufacturador_telefono" placeholder="" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
			<div data-linea="16" class="fabricanteManufacturador">
				<button id="btnAddManufacturador" type="button" class="mas">Agregar manufacturador</button>
			</div>
			<div class="fabricanteManufacturador" style="width:98%">
				<table id="tblManufacturador" style="width:98%">
               <thead>
                  <tr>
                     <th style="width:20%;">Nombre</th>
							<th style="width:10%;">País</th>
							<th style="width:20%;">Dirección</th>
							<th style="width:20%;">Representante legal</th>
							<th style="width:10%;">Correo</th>
							<th style="width:10%;">Telefono</th>
							<th style="width:10%;"></th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
			</div>

			<div>
				<button id="btnAddFabricante" type="button" class="mas">Agregar fabricante</button>
			</div>
				
		</fieldset>
		
		<fieldset>
			<legend>Listado de Fabricantes</legend>
				
            <table id="tblFabricantes" style="width:98%">
               <thead>
                  <tr>
							<th style="width:10%;"></th>
                     <th style="width:70%;">Fabricante</th>
                     <th style="width:10%;"></th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>					
		</fieldset>
	

		<fieldset>
			<legend>Datos de Formuladores</legend>
         <input type="hidden" value="" name="formulador_id"  id="formulador_id" />
					
         <div data-linea="1">
            <label for="formulador_nombre" class="opcional">Nombre:</label>
            <input value="" name="formulador_nombre" type="text" id="formulador_nombre" placeholder="Nombre del formulador" class="cuadroTextoCompleto" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
			<div data-linea="3" >
				<label for="formulador_pais">País de origen del ingrediene activo</label>
				<select name="formulador_pais" id="formulador_pais">
					<option value="">Seleccione....</option>
					<?php
					foreach ($paises as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
					}
                    ?>
				</select>
			</div>
			<div data-linea="4" >
                  <label for="formulador_direccion" class="opcional">Dirección:</label>
                  <input value="" name="formulador_direccion" type="text" id="formulador_direccion" placeholder="Dirección" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="5" class="fabricanteNacional">
                  <label for="formulador_representante" class="opcional">Representante legal:</label>
                  <input value="" name="formulador_representante" type="text" id="formulador_representante" placeholder="nombre y aplellido " class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="6" class="fabricanteNacional">
                  <label for="formulador_correo" class="opcional">Correo electrónico:</label>
                  <input value="" name="formulador_correo" type="text" id="formulador_correo" placeholder="Correo electrónico" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="7" class="fabricanteNacional">
                  <label for="formulador_telefono" class="opcional">Teléfono:</label>
                  <input value="" name="formulador_telefono" type="text" id="formulador_telefono" placeholder="Teléfono" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
			<div class="justificado">
            <label for="formulador_carta" class="opcional">Referecia de carta(s) de autorización original(es) debidamente legalizada, apostillada o consularizada</label>
            <input value="" name="formulador_carta" type="text" id="formulador_carta" placeholder="Ingrese referencia" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
										
			<hr/>
			<div>
				<label for="f_tiene_contrato">Tiene contratos de manufactura ?</label>
				SI<input type="radio" id="f_tiene_contratoSI" name="f_tiene_contrato" value="SI"  />
				NO<input type="radio" id="f_tiene_contratoNO" name="f_tiene_contrato" value="NO"  />
			</div>
			<hr/>
					
									
				<div data-linea="10" class="formuladorManufacturador">
					<label for="f_manufacturador_nombre" >Nombre:</label>
					<input value="" name="f_manufacturador_nombre" type="text" id="f_manufacturador_nombre" placeholder="Nombre del Manufacturador" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="11" class="formuladorManufacturador">
					<label for="f_manufacturador_pais">País de origen del ingrediene activo</label>
					<select name="f_manufacturador_pais" id="f_manufacturador_pais">
						<option value="">Seleccione....</option>
						<?php
						foreach ($paises as $key=>$item){
							echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
						}
                        ?>
					</select>
				</div>
				<div data-linea="12" class="formuladorManufacturador">
					<label for="f_manufacturador_direccion" class="opcional">Dirección:</label>
					<input value="" name="f_manufacturador_direccion" type="text" id="f_manufacturador_direccion" placeholder="Dirección" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="13" class="formuladorManufacturador">
					<label for="f_manufacturador_representante" class="opcional">Representante legal:</label>
					<input value="" name="f_manufacturador_representante" type="text" id="f_manufacturador_representante" placeholder="" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="14" class="formuladorManufacturador">
					<label for="f_manufacturador_correo" class="opcional">Correo electrónico:</label>
					<input value="" name="f_manufacturador_correo" type="text" id="f_manufacturador_correo" placeholder="" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="15" class="formuladorManufacturador">
					<label for="f_manufacturador_telefono" class="opcional">Teléfono:</label>
					<input value="" name="f_manufacturador_telefono" type="text" id="f_manufacturador_telefono" placeholder="" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				<div data-linea="16" class="formuladorManufacturador">
					<button id="f_btnAddManufacturador" type="button" class="mas">Agregar manufacturador</button>
				</div>
						
					
			<div class="formuladorManufacturador" style="width:98%">
				<table id="f_tblManufacturador" style="width:98%">
               <thead>
                  <tr>
							<th style="width:20%;">Nombre</th>
							<th style="width:10%;">País</th>
							<th style="width:20%;">Dirección</th>
							<th style="width:20%;">Representante legal</th>
							<th style="width:10%;">Correo</th>
							<th style="width:10%;">Télefono</th>
							<th style="width:10%;"></th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
			</div>

			<div>
				<button id="btnAddFormulador" type="button" class="agregar">Agregar formulador</button>
				
			</div>
				
		</fieldset>
		
		<fieldset>
			<legend>Listado de Formuladores</legend>
				
            <table id="f_tblFabricantes" style="width:98%">
               <thead>
                  <tr>
							<th style="width:10%;"></th>
                     <th style="width:70%;">Formuladores</th>
                     <th style="width:10%;"></th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>					
		</fieldset>
	

   </form>

   <form id='frmNuevaSolicitud6' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosModificacion'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P6" />

      <fieldset>
         <legend>Cambio de razón social del fabricante o formulador</legend>
         <div data-linea="1">
            <label for="cambio_actividad">Actividad:</label>
            <select name="cambio_actividad" id="cambio_actividad" required>
               <option value="F">Fabricante</option>
               <option value="R">Formulador</option>
            </select>
         </div>
			<div data-linea="2">
            <label for="cambio_razon_actual">Razon social actual</label>
            <select name="cambio_razon_actual" id="cambio_razon_actual" required>
               <option value="">Seleccione....</option>
					<?php
					foreach ($cultivosNombres as $key=>$item){
						if(strtoupper($item['id_fabricante_formulador']) == strtoupper($datosGenerales['cambio_razon_actual'])){
							echo '<option value="' . $item['id_fabricante_formulador'] . '" selected="selected">' . $item['nombre'].' ('.$item['pais_origen'] . ')</option>';
						}else{
							echo '<option value="' . $item['id_fabricante_formulador'] . '">' . $item['nombre'] .' ('.$item['pais_origen'] . ')</option>';
						}
					}
                    ?>
            </select>
         </div>

         <div data-linea="3">
            <label for="cambio_razon">Razon social nueva:</label>
            <input value="" name="cambio_razon" type="text" id="cambio_razon" placeholder="Ingrese la nueva razón social" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div class=" justificado">
            <label>Copia protocolizada de la resotución de la superintendencia de compañias en la cual se aprobó la reformas de estatutos y el cambio de denominación, y  copia certificada de la escritura pública, de la reforma de estatutos y  del cambio de denominación, debidamento inserta en el reglamento mercantil</label>
            <input id="ruta_1" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*"  />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>" >Subir archivo</button>
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

	
   <form id='frmNuevaSolicitud8' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosModificacion'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P8" />

      <fieldset>
         <legend>Actualización de etiqueta de evaluación final</legend>
         
         <div class=" justificado">
            <label>Etiqueta aprobada por MAE</label>
            <input id="ruta_18" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>
         <div class=" justificado">
            <label>Etiqueta aprobada por SALUD</label>
            <input id="ruta_28" type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
            <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" />
            <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            <button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo $pathAnexo;?>">Subir archivo</button>
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
	
   </form>

   <form id="frmAnexos" data-rutaAplicacion="dossierPecuario" data-opcion="guardarArchivoAnexo">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="fase" name="fase" value="solicitud">
      <input type="hidden" id="a_referencia" name="a_referencia" value="">
      <input type="hidden" id="a_rutaArchivo" name="a_rutaArchivo" value="">
      <input type="hidden" id="a_tipoArchivo" name="a_tipoArchivo" value="">
	</form>

</div>

<div class="pestania" id="P12" style="display: block;">
	<form id="frmFinalizarSolicitud12" data-rutaAplicacion="dossierPlaguicida" data-opcion="finalizarModificacion" data-accionEnExito = 'ACTUALIZAR'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_subtipo_producto" name="id_subtipo_producto" value="<?php echo $datosGenerales['id_subtipo_producto'];?>" />


		<fieldset>
			<legend>Finalizar solicitud</legend>

		<div class="justificado">
				<label for="observacion" >Condiciones de la información:</label>
				<br/>
            <label id="observacion" >
					<?php
					echo $declaracionLegal['pie'];
                    ?>
            </label>
			</div>
			<div data-linea="2">
				<label>
					<?php
					echo '<a href='.$declaracionLegal['encabezado'].' target="_blank">Lea información confidencial</a>';
                    ?>
				</label>
         </div>
			<div data-linea="3">
				<label>
					UNA VEZ REALIZADO EL CAMBIO DE TITULARIDAD USTED NO PODRÁ VER MÁS LA INFORMACIÓN DEL PRODUCTO
				</label>
         </div>
			<hr/>
			<div data-linea="4">
				<label for="boolAcepto">Acepto las condiciones</label>
					SI<input type="radio" id="boolFinalizoSI" name="boolAcepto" value="SI" />
					NO<input type="radio" id="boolFinalizoNO" name="boolAcepto" value="NO" />
			</div>
		</fieldset>
		<button id="btnFinalizar" type="button" class="guardar">Finalizar</button>
	</form>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>


<script type="text/javascript">

	var numeroPestania = <?php echo json_encode($numeroPestania);?>;
	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	
	var registroProductos=<?php echo json_encode($registroProductos); ?>;
	var registroProductosMatriz=<?php echo json_encode($registroProductosMatriz); ?>;
	var clonesRegistrados=<?php echo json_encode($clonesRegistrados); ?>;

	

	var fabricantes=<?php echo json_encode($fabricantes); ?>;
	var formuladores=<?php echo json_encode($formuladores); ?>;
	var presentaciones=<?php echo json_encode($presentaciones); ?>;

	var codigoFormulacion='';
	var protocolo={};

	//**************************  VARIABLES GENERALES *****************************

	var txtFabricantes="";
	var txtFormuladores="";
	var txtFormuladoresMan="";
	var txtFabricantesForm="";

	$('#tipo_modificacion').change(function(){
		verTipoModificacion($(this).val());
		$('#registro').change();
	});

	function verTipoModificacion(tipo){
		$('#frmNuevaSolicitud3').hide();
		$('#frmNuevaSolicitud4').hide();
		$('#frmNuevaSolicitud5').hide();
		$('#frmNuevaSolicitud6').hide();
		$('#frmNuevaSolicitud8').hide();

		switch(tipo){
			case 'MRG_ETIQ':
				$('#frmNuevaSolicitud8').show();
				break;
			case 'MRG_AFFP':
				$('#frmNuevaSolicitud5').show();
				break;
			case 'MRG_CRS':
				$('#frmNuevaSolicitud6').show();
				break;
			case 'MRG_CTR':
				$('#frmNuevaSolicitud3').show();
				break;
			case 'MRG_CUCP':
				$('#frmNuevaSolicitud4').show();
				break;

		}

	}

	$("#cultivo_cientifico").change(function(){
		$('#cultivo_comun').val($(this).val());
		$('#cultivo_comun').change();
	});


	//****************** CARGA *************************************
	$("document").ready(function(){

		construirAnimacion($(".pestania"),numeroPestania);
		distribuirLineas();

		//habilita los botones según estado del documento
		try{
			if(solicitud!=null){

				reconocerNivel(solicitud.nivel);

				try{
					verClonesAfectados(solicitud.registro);
				}catch(e){}

				try{
					verTipoModificacion(solicitud.tipo_modificacion);
				}catch(e){}
				try{
					verAccionesRegistro(solicitud.registro,solicitud.id_producto);
				}catch(e){}

			}
			valoresRecuperados();
		}catch(e){}

	});


	$('#registro').change(function(){
		var selected = $(this).find('option:selected');
		var id_producto = selected.data('id');
		
		verAccionesRegistro($(this).val(),id_producto);


	});

	function verAccionesRegistro(registro,id_producto){
		switch($('#tipo_modificacion').val()){
			case 'MRG_ETIQ':
				break;
			case 'MRG_AFFP':
				break;
			case 'MRG_CRS':
				break;
			case 'MRG_CTR':
				verClonesAfectados(registro);
				break;
			case 'MRG_CUCP':

				var param={opcion_llamada:'recuperarInformesFinales',id_registro:registro,id_producto:id_producto
				};
				llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verResultadoInformesUsos);
				break;

		}
	}

	function verResultadoInformesUsos(datos){
		if(datos!=null){
			verInformesFinales(datos['informes']);
			verUsosAprobados(datos['usos']);
	}
	}

	function verInformesFinales(datos){
		var el=$('#informe_final');
		el.children('option').remove();
		el.append($("<option></option>").attr("value","").text("Seleccione...."));
		if(datos!=null){
			for(var i in datos){
				el.append($("<option></option>").attr("value",datos[i].id_informe).text(datos[i].id_expediente));
			}
		}
	}

	function verUsosAprobados(datos){
		
		var el=$('#usos_plagas');
		el.children().remove();

		if(datos!=null){
			for(var i in datos){
				el.append('<input type="checkbox" name="plagasAprobados[]" value="'+datos[i].id_producto_uso+'">'+datos[i].nombre_uso+"-"+datos[i].nombre+'</input>');
				el.append('<br/>');
			}
		}
	}




	function verClonesAfectados(registro){
		var el=$('#clones_modificados');
		el.find("li").remove();

		if(clonesRegistrados!=null){
			for(var i in clonesRegistrados){
				if(clonesRegistrados[i].numero_registro.includes(registro)){
					el.append($("<li></li>").text("("+clonesRegistrados[i].numero_registro+") "+clonesRegistrados[i].nombre_comun));
				}
			}
		}
	}

	function valoresRecuperados(){
		try{
			$('.fabricanteManufacturador').hide();		//Inicializa los fabricantes
			$('.formuladorManufacturador').hide();		//Inicializa los formuladores
			verFabricantes(fabricantes);
			verFormuladores(formuladores);
		}catch(e){}


	}



	//**************************************** campos numericos **********************************
	$('#partida_arancelaria').numeric();
	$('#punto_inflamacion').numeric();
	$('#ph').numeric();
	$('#presentacion_cantidad').numeric();
	$('#ad_cantidad').numeric();


	//***************************** VISTA PREVIA ***************************************
	$('button.btnVistaPrevia').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='id_protocolo' name='id_protocolo' value='"+$('#protocolo').val()+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='producto_nombre' name='producto_nombre' value='"+$('#producto_nombre').val()+"' />");
		form.append("<input type='hidden' id='normativa' name='normativa' value='"+$('#normativa').val()+"' />");
		form.append("<input type='hidden' id='ingrediente_activo' name='ingrediente_activo' value='"+$('#producto_ia').html()+"' />");
		form.append("<input type='hidden' id='ingredientes_paises' name='ingredientes_paises' value='"+$('#producto_pais').val()+"' />");
		form.append("<input type='hidden' id='usos' name='usos' value='"+$('#producto_uso').val()+"' />");
		form.append("<input type='hidden' id='formulacion' name='formulacion' value='"+$('#producto_formulacion').val()+"' />");
		form.append("<input type='hidden' id='formuladores_paises' name='formuladores_paises' value='"+$('#producto_pais_producto').val()+"' />");

		form.attr('data-opcion', 'crearSolicitudRegistro');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporte').hide();
		ejecutarJson(form,new exitoVistaPrevia());

	});


	function exitoVistaPrevia(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporte').show();
			$('#verReporte').attr('href',msg.datos);
		};
	}


	$('input[name="tiene_contrato"]').click(function(){
		if($(this).val()=="SI"){
			$('.fabricanteManufacturador').show();
		}
		else{
			$('.fabricanteManufacturador').hide();
		}

	});


	//*********************  FABRICANTES ************************************************************************
	var itemsManufacturador=[];

	$("#btnAddFabricante").click(function (event) {
		event.preventDefault();
		var error = false;
		if(!esNoNuloEsteCampo("#fabricante_nombre"))
			error = true;
		if(!esNoNuloEsteCampo("#fabricante_pais"))
			error = true;
		if(!esNoNuloEsteCampo("#fabricante_direccion"))
			error = true;
		if(!esNoNuloEsteCampo("#fabricante_representante"))
			error = true;
		if(!esNoNuloEsteCampo("#fabricante_correo"))
			error = true;
		if(!esNoNuloEsteCampo("#fabricante_telefono"))
			error = true;
		if(!esNoNuloEsteCampo("#fabricante_carta"))
			error = true;
		

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var param={opcion_llamada:'guardarFabricanteManufacturadorModificacion',id_solicitud:solicitud.id_modificacion,
			tipo_fabricante:'F',
			nombre:$('#fabricante_nombre').val(),
			id_pais:$('#fabricante_pais').val(),
			direccion:$('#fabricante_direccion').val(),
			representante_legal:$('#fabricante_representante').val(),
			correo:$('#fabricante_correo').val(),
			telefono:$('#fabricante_telefono').val(),
			carta:$('#fabricante_carta').val(),
			
			manufacturadores:JSON.stringify(itemsManufacturador)
		};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,nuevoFabricantes);
	});

	function nuevoFabricantes(datos){
		$('#fabricante_id').val(datos.id);
		verFabricantes(datos.datos);
		encerarFabricantes();				//Encera los datos del fabricante
	}

	function verFabricantes(items){
		$('#tblFabricantes tbody tr').remove();
		txtFabricantes='';
		txtFabricantesForm="";
		arrFabricantes={};
		arrFabricantesMan={};
		if(!jQuery.isEmptyObject(items)){
			
			for(var i in items){
				var item=items[i];
				arrFabricantes[item.pais]=item.pais;

				var nuevaFila='<td colspan="2">'+item.nombre+','+item.pais+'</td>';
				var tdEliminar='<form id="borrarFilaFabricante" class="borrar borrarFilaEfectos" data-rutaAplicacion="dossierPlaguicida" data-opcion="borrarFabricante"  >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_modificacion + '" />' +
								'<input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="' + item.id_modificacion_fabricante + '" />' +
								'<input type="hidden" id="tipo_fabricante" name="tipo_fabricante" value="' + item.tipo_fabricante + '" />' +
								'<button type="button" class="icono btnBorraFilaFabricante"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#tblFabricantes").append('<tr>'+nuevaFila+'</tr>');
				if(item.manufacturadores!=null){
					for(var k in item.manufacturadores){
						var it=item.manufacturadores[k];
						arrFabricantesMan[it.pais]=it.pais;
						
						nuevaFila='<td>...</td>';
						nuevaFila+='<td>'+it.nombre+','+it.pais+'</td>';
						

						$("#tblFabricantes").append('<tr>'+nuevaFila+'</tr>');
					}

				}

			}

			if(arrFabricantes!=null){
				txtFabricantes=Object.keys(arrFabricantes).join(', ');

			}

			if(arrFabricantesMan!=null)
				txtFabricantesForm=Object.keys(arrFabricantesMan).join(', ');
		}
		
		agregarFabricantesPais();
	}

	function encerarFabricantes(){
		$('#fabricante_nombre').val('');
		cargarValorDefecto('fabricante_pais','');
		$('#fabricante_direccion').val('');
		$('#fabricante_representante').val('');
		$('#fabricante_correo').val('');
		$('#fabricante_telefono').val('');
		$('#fabricante_carta').val('');
		$('#fabricante_certificado').val('');
		itemsManufacturador.length=0;

		verManufacturador(itemsManufacturador);
	}
	
	$("#tblFabricantes").off("click",".btnBorraFilaFabricante").on("click",".btnBorraFilaFabricante",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarFabricanteModificacion',id_solicitud:solicitud.id_modificacion,id_solicitud_fabricante:form.find("#id_solicitud_fabricante").val(),tipo_fabricante:form.find("#tipo_fabricante").val()};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verFabricantes);
	});



		$("#btnAddManufacturador").click(function (event) {
			event.preventDefault();
			var error = false;
			if(!esNoNuloEsteCampo("#manufacturador_nombre"))
				error = true;
			if(!esNoNuloEsteCampo("#manufacturador_pais"))
				error = true;

			if(!esNoNuloEsteCampo("#manufacturador_direccion"))
				error = true;
			if(!esNoNuloEsteCampo("#manufacturador_representante"))
				error = true;
			if(!esNoNuloEsteCampo("#manufacturador_correo"))
				error = true;
			if(!esNoNuloEsteCampo("#manufacturador_telefono"))
				error = true;

			if(error){
				mostrarMensaje("Llene los campos obligatorios","FALLO");
				return;
			}

			var param={nombre:$('#manufacturador_nombre').val(),
				id_pais:$('#manufacturador_pais').val(),
				pais:$('#manufacturador_pais option:selected').text(),
				direccion:$('#manufacturador_direccion').val(),
				representante_legal:$('#manufacturador_representante').val(),
				correo:$('#manufacturador_correo').val(),
				telefono:$('#manufacturador_telefono').val()
			};

			itemsManufacturador.push(param);

			verManufacturador(itemsManufacturador);
		});

	function verManufacturador(items){
		$('#tblManufacturador tbody tr').remove();
		txtFabricantesForm="";
		var arrFabricantesMan={};
		if(!jQuery.isEmptyObject(items)){
			for(var i in items){
				var item=items[i];
				arrFabricantesMan[item.pais]=item.pais;
				
				var nuevaFila='<td>'+item.nombre+'</td>';
				nuevaFila+='<td>'+item.pais+'</td>';
				nuevaFila+='<td>'+item.direccion+'</td>';
				nuevaFila+='<td>'+item.representante_legal+'</td>';
				nuevaFila+='<td>'+item.correo+'</td>';
				nuevaFila+='<td>'+item.telefono+'</td>';
				var tdEliminar='<form id="borrarFilaManufacturador" class="borrar borrarFilaEfectos" data-rutaAplicacion="" data-opcion=""  >' +
								'<input type="hidden" id="id_pais" name="id_pais" value="' + item.id_pais + '" />' +
								'<input type="hidden" id="nombre" name="nombre" value="' + item.nombre + '" />' +
								'<button type="button" class="icono btnBorraFilaManufacturador"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#tblManufacturador").append('<tr>'+nuevaFila+'</tr>');
			}

			if(arrFabricantesMan!=null)
				txtFabricantesForm=Object.keys(arrFabricantesMan).join(', ');
		}
		
		agregarFabricantesPais();
	}

	$("#tblManufacturador").off("click",".btnBorraFilaManufacturador").on("click",".btnBorraFilaManufacturador",function(event){
		event.preventDefault();
		var form=$(this).parent();
		for(var i in itemsManufacturador){
			var item=itemsManufacturador[i];
			if(item.id_pais==form.find("#id_pais").val() && item.nombre==form.find("#nombre").val()){
				itemsManufacturador.splice(i,1);
			}
		}
		
		verManufacturador(itemsManufacturador);
	
	});

	function agregarFabricantesPais(){
		$('#producto_pais').html("FABRICANTES: "+txtFabricantes+" MANUFACTURADORES: "+txtFabricantesForm);
	}

	//******************************************* FORMULADORES **********************************************************



	$('input[name="f_tiene_contrato"]').click(function(){
		if($(this).val()=="SI"){
			$('.formuladorManufacturador').show();
		}
		else{
			$('.formuladorManufacturador').hide();
		}

	});


	$("#btnAddFormulador").click(function (event) {
		event.preventDefault();
		var error = false;
		if(!esNoNuloEsteCampo("#formulador_nombre"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_pais"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_direccion"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_representante"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_correo"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_telefono"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_carta"))
			error = true;
		

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();
		var param={opcion_llamada:'guardarFabricanteManufacturadorModificacion',id_solicitud:solicitud.id_modificacion,
			tipo_fabricante:'R',		//R=Formulador
			nombre:$('#formulador_nombre').val(),
			id_pais:$('#formulador_pais').val(),
			direccion:$('#formulador_direccion').val(),
			representante_legal:$('#formulador_representante').val(),
			correo:$('#formulador_correo').val(),
			telefono:$('#formulador_telefono').val(),
			carta:$('#formulador_carta').val(),

			manufacturadores:JSON.stringify(itemsManufacturador)
		};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,nuevoFormuladores);
	});

	function nuevoFormuladores(datos){
		$('#formulador_id').val(datos.id);
		verFormuladores(datos.datos);

		encerarFormuladores();				//Encera los formuladores
	}


	function verFormuladores(items){
		$('#f_tblFabricantes tbody tr').remove();
		txtFormuladores="";
		txtFormuladoresMan="";
		var arr={};
		var arrMan={};
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];
				arr[item.pais]=item.pais;
				
				var nuevaFila='<td colspan="2">'+item.nombre+','+item.pais+'</td>';
				var tdEliminar='<form id="borrarFilaFabricante" class="borrar borrarFilaEfectos" data-rutaAplicacion="dossierPlaguicida" data-opcion="borrarFabricante"  >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_modificacion + '" />' +
								'<input type="hidden" id="id_solicitud_fabricante" name="id_solicitud_fabricante" value="' + item.id_modificacion_fabricante + '" />' +
								'<input type="hidden" id="tipo_fabricante" name="tipo_fabricante" value="' + item.tipo_fabricante + '" />' +
								'<button type="button" class="icono f_btnBorraFilaFabricante"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#f_tblFabricantes").append('<tr>'+nuevaFila+'</tr>');
				if(item.manufacturadores!=null){
					for(var k in item.manufacturadores){
						var it=item.manufacturadores[k];
						arrMan[it.pais]=it.pais;
						
						nuevaFila='<td>...</td>';
						nuevaFila+='<td>'+it.nombre+','+it.pais+'</td>';
						

						$("#f_tblFabricantes").append('<tr>'+nuevaFila+'</tr>');
					}

				}
			}

			if(arr!=null){
				txtFormuladores=Object.keys(arr).join(', ');

			}

			if(arrMan!=null)
				txtFormuladoresMan=Object.keys(arrMan).join(', ');
		}
		
		agregarFormuladoresPais();
	}

	function encerarFormuladores(){
		$('#formulador_nombre').val('');
		cargarValorDefecto('formulador_pais','');
		$('#formulador_direccion').val('');
		$('#formulador_representante').val('');
		$('#formulador_correo').val('');
		$('#formulador_telefono').val('');
		$('#formulador_acreditacion').val('');
		$('#formulador_certificado').val('');
		itemsManufacturador.length=0;
		f_verManufacturador(itemsManufacturador);
	}

	function agregarFormuladoresPais(){
		$('#producto_pais_producto').html("FORMULADORES: "+txtFormuladores+" MANUFACTURADORES: "+txtFormuladoresMan);
	}

	$(".f_btnBorraFilaFabricante").click(function(event){

		event.preventDefault();

		var form=$(this).parent();
		var param={opcion_llamada:'borrarFabricanteModificacion',id_solicitud:solicitud.id_modificacion,id_solicitud_fabricante:form.find("#id_solicitud_fabricante").val(),tipo_fabricante:form.find("#tipo_fabricante").val()};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verFormuladores);
	});


	$("#f_tblFabricantes").on("click",".f_btnBorraFilaFabricante",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarFabricanteModificacion',id_solicitud:solicitud.id_modificacion,id_solicitud_fabricante:form.find("#id_solicitud_fabricante").val(),tipo_fabricante:form.find("#tipo_fabricante").val()};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verFormuladores);
	});



	//************************************************ MANUFACTURADOR DEL FORMULADOR ********************************************



	$("#f_btnAddManufacturador").click(function (event) {
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#f_manufacturador_nombre"))
			error = true;
		if(!esNoNuloEsteCampo("#f_manufacturador_pais"))
			error = true;
		if(!esNoNuloEsteCampo("#f_manufacturador_direccion"))
			error = true;
		if(!esNoNuloEsteCampo("#f_manufacturador_representante"))
			error = true;
		if(!esNoNuloEsteCampo("#f_manufacturador_correo"))
			error = true;
		if(!esNoNuloEsteCampo("#f_manufacturador_telefono"))
			error = true;
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var param={nombre:$('#f_manufacturador_nombre').val(),
			id_pais:$('#f_manufacturador_pais').val(),
			pais:$('#f_manufacturador_pais option:selected').text(),

			direccion:$('#f_manufacturador_direccion').val(),
			representante_legal:$('#f_manufacturador_representante').val(),
			correo:$('#f_manufacturador_correo').val(),
			telefono:$('#f_manufacturador_telefono').val()
		};
		itemsManufacturador.push(param);

		
		f_verManufacturador(itemsManufacturador);
	});

	function f_verManufacturador(items){
		$('#f_tblManufacturador tbody tr').remove();
		txtFormuladoresMan="";
		var arrMan={};
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];
				arrMan[tem.pais]=tem.pais;
				
				var nuevaFila='<td>'+item.nombre+'</td>';
				 nuevaFila+='<td>'+item.pais+'</td>';
				 nuevaFila+='<td>'+item.direccion+'</td>';
				 nuevaFila+='<td>'+item.representante_legal+'</td>';
				 nuevaFila+='<td>'+item.correo+'</td>';
				 nuevaFila+='<td>'+item.telefono+'</td>';
				 tdEliminar='<form id="borrarFilaManufacturador" class="borrar borrarFilaEfectos" data-rutaAplicacion="" data-opcion=""  >' +
								'<input type="hidden" id="id_pais" name="id_pais" value="' + item.id_pais + '" />' +
								'<input type="hidden" id="nombre" name="nombre" value="' + item.nombre + '" />' +
								'<button type="button" class="icono f_btnBorraFilaManufacturador"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#f_tblManufacturador").append('<tr>'+nuevaFila+'</tr>');
			}
			if(arrMan!=null)
				txtFormuladoresMan=Object.keys(arrMan).join(', ');
		}
		
		agregarFormuladoresPais();
	}

	$("#f_tblManufacturador").off("click",".f_btnBorraFilaManufacturador").on("click",".f_btnBorraFilaManufacturador",function(event){
		event.preventDefault();
		
		var form=$(this).parent();
		for(var i in itemsManufacturador){
			var item=itemsManufacturador[i];
			if(item.id_pais==form.find("#id_pais").val() && item.nombre==form.find("#nombre").val()){
				
				itemsManufacturador.splice(i,1);
				
				}
		}
		
		f_verManufacturador(itemsManufacturador);

	});


	//******************************************* ANEXOS **********************************************************


	$('button.subirArchivo').click(function (event) {
		event.preventDefault();

        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var str=archivo.val().trim();

		str=str.replace(/[^a-zA-Z0-9.]+/g,'');
		str = str.replace('Cfakepath','');
		try{
			str=str.replace('.'+str.replace(/^.*\./, ''),'');
		}catch(e){}
        var nombre_archivo = solicitud.identificador+"_MRG_"+solicitud.id_modificacion+"_"+str;
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton,rutaArchivo)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
	});

	function carga(estado, archivo, boton,rutaArchivo) {
		this.esperar = function (msg) {
			estado.html("Cargando el archivo...");
			archivo.addClass("amarillo");
		};

		this.exito = function (msg) {
			estado.html("El archivo ha sido cargado.");
			archivo.removeClass("amarillo");
			archivo.addClass("verde");
			boton.attr("disabled", "disabled");

		};

		this.error = function (msg) {
			estado.html(msg);
			archivo.removeClass("amarillo");
			archivo.addClass("rojo");
		};
	}




	//**************************************** GUARDAR FORMULARIOS ****************************

	$('#btnGuardarPrimero').click(function(event){
		event.preventDefault();
		$("#estado").html("");

		var error = false;
		if(!esNoNuloEsteCampo("#tipo_modificacion"))
			error = true;
		if(!esNoNuloEsteCampo("#registro"))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var form=$(this).parent();

		if(jQuery.isEmptyObject(solicitud)){
			//nuevo registro
			form.attr('data-opcion', 'guardarNuevaModificacion');
			form.attr('data-destino', 'detalleItem');
			form.attr('data-accionEnExito', 'ACTUALIZAR');
			form.append("<input type='hidden' id='nivel' name='nivel' value='1' />"); // añade el nivel del formulario
			abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios
		}
		else{
			//es actualización

			incrementarNivel(form,solicitud.nivel);
			form.attr('data-opcion', 'guardarPasosModificacion');
			form.attr('data-destino', 'detalleItem');
			form.attr('data-accionEnExito', '');
			form.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario
			ejecutarJson(form);
			actualizaBotonSiguiente(form, nivelActual,solicitud.nivel);
		}


	});


	//************************************* GUARDADO DE LOS PASOS ***************************************

	$("#frmNuevaSolicitud3").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		var error = false;

		if(!esNoNuloEsteCampo("#ruc_nuevo"))
			error = true;
		if(!esNoNuloEsteCampo("#ruta_13"))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
			pasoGuardado=nivelActual;
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud4").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();
		var error = false;

		if(!esNoNuloEsteCampo("#informe_final"))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
			pasoGuardado=nivelActual;
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud5").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		var error = false;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});


	$("#frmNuevaSolicitud6").submit(function(event){

		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		var error = false;

		if(!esNoNuloEsteCampo("#cambio_razon_actual"))
			error = true;
		if(!esNoNuloEsteCampo("#cambio_razon"))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson($(this));
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});



	$("#frmNuevaSolicitud8").submit(function(event){
		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#ruta_18"))
			error = true;
		if(!esNoNuloEsteCampo("#ruta_28"))
			error = true;
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson($(this));
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud11").submit(function(event){
		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		var error = false;


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson($(this));
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$('#btnFinalizar').click(function (event) {
		event.preventDefault();

		if($("#boolFinalizoSI").is(':checked')){
			borrarMensaje();
			var form=$(this).parent();

			
			form.attr('data-destino', 'detalleItem');
			

			abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios
		}
		else
			mostrarMensaje('Para finalizar acepte las condiciones','FALLO');
	});


	$("#ph").change(function(){

		var ph=parseFloat($("#ph").val());

		if(ph<0 || ph>14){
			mostrarMensaje('El valor debe estar entre 0 y 14','FALLO');
			$("#ph").focus();
			$("#ph").addClass("alertaCombo");
		}
		else{
			$("#ph").removeClass("alertaCombo");
			}

	});



	//************************************* PRESENTACION DE ENVASES ********************************

	$("#btnAddPresentacion").click(function (event) {
		event.preventDefault();
		var param={opcion_llamada:'agregarPresentacion',id_solicitud:solicitud.id_solicitud,presentacion:$('#presentacion_tipo').val(),cantidad:$('#presentacion_cantidad').val(),id_unidad_medida:$('#presentacion_unidad').val(),
			partida_arancelaria:$('#partida_arancelaria').val(),codigo_complementario:$('#codigo_complementario').val(),codigo_suplementario:$('#codigo_suplementario').val()};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verPresentacion);
	});

	function verPresentacion(items){
		$('#tblPresentacion tbody tr').remove();
		if(!jQuery.isEmptyObject(items)){
			
			for(var i in items){
				var item=items[i];
				
				var nuevaFila='<td>'+item.presentacion_nombre+'</td>';
				nuevaFila+='<td>'+item.cantidad+'</td>';
				nuevaFila+='<td>'+item.unidad_medida+'</td>';
				nuevaFila+='<td>'+item.partida_arancelaria+'</td>';
				nuevaFila+='<td>'+("0000" + item.codigo_complementario).slice(-4)+'</td>';
				nuevaFila+='<td>'+("0000" + item.codigo_suplementario).slice(-4)+'</td>';
				var tdEliminar='<form id="borrarFila" class="borrar" data-rutaAplicacion="dossierPlaguicida" data-opcion=""  >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_solicitud + '" />' +
								'<input type="hidden" id="id_solicitud_presentacion" name="id_solicitud_presentacion" value="' + item.id_solicitud_presentacion + '" />' +
								'<button type="button" class="icono btnBorraFilaPresentacion"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdEliminar+'</td>';

				$("#tblPresentacion").append('<tr>'+nuevaFila+'</tr>');
			}
		}
		
	}



	$("#tblPresentacion").off("click",".btnBorraFilaPresentacion").on("click",".btnBorraFilaPresentacion",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarPresentacion',id_solicitud:solicitud.id_solicitud,id_solicitud_presentacion:form.find("#id_solicitud_presentacion").val()};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verPresentacion);
	});



</script>

