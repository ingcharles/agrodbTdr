<?php 
session_start();

	require_once '../../clases/Conexion.php';	
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorRegistroOperador.php';
   require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';

	$numeroPestania = $_POST['numeroPestania'];
	
	$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud
	$id_solicitud = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];

	if($id_flujo==null){
		$id_flujo=$_SESSION['idAplicacion'];
	}



	$identificador=$idUsuario;					//Es el duenio del documento, puede variar si ya hay un protocolo y el usuario es alguien de revision, aprobacion, etc..

	$conexion = new Conexion();
	$cc=new ControladorCatalogos();
	$ce = new ControladorEnsayoEficacia();
	$co = new ControladorRegistroOperador();
	$cr = new ControladorRequisitos();
      
	$cg=new ControladorDossierPlaguicida();
	
	
	$datosGenerales=array();	
	$fabricantes=array();
	$formuladores=array();
	$anexosSolicitud=array();
	$operador = array();
	$protocolosAprobados=array();


	$informesFinales=array();
	$presentaciones=array();
	$cultivos=array();
	$plagas=array();
	$contieneParaquat=false;

	$items=$cc->listarLocalizacion($conexion,'PAIS');
	$paises=array();
	while ($fila = pg_fetch_assoc($items)){
		$paises[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
	}

	if($id_solicitud!=null && $id_solicitud!='_nuevo'){

		$datosGenerales=$cg->obtenerSolicitud($conexion, $id_solicitud);
		$identificador=$datosGenerales['identificador'];						//El duenio del documento
		
		 $fabricantes=$cg->obtenerFabricantes($conexion,$id_solicitud,'F');
		 $formuladores=$cg->obtenerFabricantes($conexion,$id_solicitud,'R');

		 $presentaciones=$cg->obtenerPresentaciones($conexion,$id_solicitud);
		
		 $anexosSolicitud=$cg->obtenerArchivosAnexos($conexion,$id_solicitud);
	}
	
	//busca los datos del operador
	$res = $co->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);

	$protocolosAprobados=$ce->listarProtocolosAprobados($conexion,$identificador,'MOT_REG');
	//filtra los protocolos que ya estan utilizados
	foreach($protocolosAprobados as $key=>$item){
		if(($item['estado_dossier']=='C')||($item['estado_dossier']=='P')){
			if($datosGenerales['protocolo']!=$item['id_expediente'])
				unset($protocolosAprobados[$key]);
		}
	}

	$registroProductos=$ce->obtenerProductosRegistrados($conexion,$identificador);
	$registroProductosMatriz=$ce->obtenerProductosMatrizRegistrados($conexion,$identificador);	//para clones

	$declaracionLegal=$ce->obtenerTitulo($conexion,'EP');
		
	//****************** ANEXOS **************************************
	$maxArchivoEE=2000;		//tamaño maximo de los archivos a subir en KB
	$tipoAnexos=$ce->listarElementosCatalogoEx($conexion,'ANEXO_PG');

	$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
	$pathAnexo=$paths['ruta'];
	
?>

<header>
	<h1>Solicitud de dossier plaguicida</h1>
</header>

<div id="estado"></div>

<div class="pestania" id="P1" style="display: block;">
   <form id='frmNuevaSolicitud' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarNuevaSolicitud' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />

      <input type="hidden" id="opcion" name="opcion" />

      <fieldset>
         <legend>Informacion del solicitante</legend>

         <div data-linea="2">
            <label for="razon" class="opcional">Razón social</label>
            <input value="<?php echo $operador['razon_social'];?>" name="razon" type="text" id="razon"  disabled="disabled"  />
         </div>

         <div data-linea="3">
            <label for="ruc" class="opcional">CI/RUC/PASS</label>
            <input value="<?php echo $operador['identificador'];?>" name="ruc" type="text" id="ruc"  disabled="disabled"  />
         </div>
         <div class="listasCirculo justificado">
            <label>Actividades del solicitante : </label>
            <ul type="circle">            <?php
            $items = $ce->obtenerOperacionesDelOperador($conexion,$identificador,'IAP');
            foreach ($items as $key=>$item){
            echo '<li>'.$sret=$item['operacion'].'</li>';
            }
            ?>
            </ul>
         </div>

         <div data-linea="4">
            <label for="direccion" class="opcional">Dirección</label>
            <input value="<?php echo $operador['direccion'];?>" name="direccion" type="text" id="direccion" disabled="disabled"  />
         </div>
         <div data-linea="5">
            <label for="provincia" class="opcional">Provincia</label>
            <input value="<?php echo $operador['provincia'];?>" name="provincia" type="text" id="provincia" disabled="disabled"  />
         </div>
         <div data-linea="6">
            <label for="canton" class="opcional">Cantón</label>
            <input value="<?php echo $operador['canton'];?>" name="canton" type="text" id="canton" disabled="disabled"  />
         </div>
         <div data-linea="7">
            <label for="parroquia" class="opcional">Parroquia</label>
            <input value="<?php echo $operador['parroquia'];?>" name="parroquia" type="text" id="parroquia" disabled="disabled"  />
         </div>
         <div data-linea="9">
            <label for="telefono" class="opcional">Telefono</label>
            <input value="<?php echo $operador['telefono_uno'];?>" name="telefono" type="text" id="telefono" disabled="disabled"  />
         </div>
         <div data-linea="10">
            <label for="celular" class="opcional">Celular</label>
            <input value="<?php echo $operador['celular_uno'];?>" name="celular" type="text" id="celular" disabled="disabled"  />
         </div>
         <div data-linea="11">
            <label for="correo" class="opcional">Correo</label>
            <input value="<?php echo $operador['correo'];?>" name="correo" type="text" id="correo" disabled="disabled"  />
         </div>

      </fieldset>

      <fieldset>
         <legend>Tipo de solicitud</legend>
         <div data-linea="1">
            <label for="normativa">Normativa Aplicada</label>
            <select name="normativa" id="normativa" required>
               <option value="">Seleccione....</option>            
					<?php
            $normativaLista = $ce->listarElementosCatalogo($conexion,'P1C30');
            foreach ($normativaLista as $key=>$item){
            if(strtoupper($item['codigo']) == strtoupper($datosGenerales['normativa'])){
            echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
            }else{
            echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
            }
            }
            ?>
            </select>
         </div>
         <div data-linea="2">
            <label for="motivo">Objetivo</label>
            <select name="motivo" id="motivo" required>
               <option value="">Seleccione....</option>            <?php
            $items=$ce->listarElementosCatalogo($conexion,'P1C2');
            foreach ($items as $key=>$item){
            if($item['codigo']!='MOT_REG')
            continue;
            if(strtoupper($item['codigo']) == strtoupper($datosGenerales['motivo'])){
            echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
            }else{
            echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
            }
            }
            ?>
            </select>
         </div>

         <div data-linea="3" id="verPreguntaClon">
            <label>Solicitará el registro de un CLON ?</label>
            <div>
               SI<input type="radio" id="es_clonSI" name="es_clon" value="SI" <?php if($datosGenerales['es_clon']=='t') echo "checked=true"?> />
               NO<input type="radio" id="es_clonNO" name="es_clon" value="NO" <?php if($datosGenerales['es_clon']=='f') echo "checked=true"?> />
            </div>
         </div>

         <div data-linea="4" class="verRegistroNoClon">
            <label for="protocolo">Producto a registrar</label>
            <select name="protocolo" id="protocolo"></select>
         </div>

         <div data-linea="5" class="verRegistroClon">
            <label for="clon_registro_madre" class="opcional">No. de registro producto matriz:</label>
            <select name="clon_registro_madre" id="clon_registro_madre">
               <option value="">Seleccione....</option>            <?php
            foreach ($registroProductosMatriz as $key=>$item){
            if(strtoupper($item['numero_registro']) == strtoupper($datosGenerales['clon_registro_madre'])){
            echo '<option value="' . $item['numero_registro'] . '" selected="selected">' .'('.$item['numero_registro'].')'. $item['nombre_comun'] . '</option>';
            }else{
            echo '<option value="' . $item['numero_registro'] . '">' . '('.$item['numero_registro'].')'.$item['nombre_comun'] . '</option>';
            }
            }
                                                                   ?>
            </select>
         </div>
         <div data-linea="6" class="verRegistroClon">
            <label for="clon_nombre_madre">Nombre producto matriz</label>
            <input value="" name="clon_nombre_madre" type="text" id="clon_nombre_madre" placeholder="nombre" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="7" class="verRegistroClon">
            <label for="clon_numero">Clones registrados</label>
            <input value="" name="clon_numero" type="text" id="clon_numero" maxlength="256" disabled="disabled" />
         </div>
			
         <div data-linea="10">
            <label for="motivo">Categoría toxicológica</label>
            <select name="id_categoria_toxicologica" id="id_categoria_toxicologica" required>
               <option value="">Seleccione....</option>            <?php
            $items=$cr->listarCategoriaToxicologica($conexion,'IAP');
            while ($item = pg_fetch_assoc($items))
            {
            if(strtoupper($item['id_categoria_toxicologica']) == strtoupper($datosGenerales['id_categoria_toxicologica'])){
            echo '<option value="' . $item['id_categoria_toxicologica'] . '" selected="selected">' . $item['categoria_toxicologica'] . '</option>';
            }else{
            echo '<option value="' . $item['id_categoria_toxicologica'] . '">' . $item['categoria_toxicologica'] . '</option>';
            }
            }
            ?>
            </select>
         </div>

      </fieldset>

      <button id="btnGuardarPrimero" type="button" class="guardar">Guardar solicitud</button>

   </form>
</div>

<div class="pestania" id="P2" style="display: block;">
	
	<form id='frmNuevoFabricante' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarFabricante'>
		<input type="hidden"  id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>"/>
		<input type="hidden" id="paso_opcion" name="paso_opcion" value="guardar" />
      <input type="hidden" id="tipo_fabricante" name="tipo_fabricante" value="F" />
		
		<fieldset>
			<legend>Datos de Fabricantes</legend>
         <input type="hidden" value="" name="fabricante_id"  id="fabricante_id" />
					
         <div data-linea="1">
            <label for="fabricante_nombre">Nombre:</label>
            <input value="" name="fabricante_nombre" type="text" id="fabricante_nombre" class="obsFabricantes" placeholder="Nombre del fabricante" required maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
			<div data-linea="3" >
				<label for="fabricante_pais">País de origen del ingrediene activo</label>
				<select name="fabricante_pais" id="fabricante_pais" class="obsFabricantes" required>
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
                  <input value="" name="fabricante_direccion" type="text" id="fabricante_direccion" placeholder="Dirección del fabricante" class="obsFabricantes" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="5" class="fabricanteNacional">
                  <label for="fabricante_representante" class="opcional">Representante legal:</label>
                  <input value="" name="fabricante_representante" type="text" id="fabricante_representante" placeholder="Nombre y apellido" class="obsFabricantes" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="6" class="fabricanteNacional">
                  <label for="fabricante_correo" >Correo electrónico:</label>
                  <input value="" name="fabricante_correo" type="text" id="fabricante_correo" class="obsFabricantes" placeholder="Correo electrónico"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="7" class="fabricanteNacional">
                  <label for="fabricante_telefono" class="opcional">Telefono:</label>
                  <input value="" name="fabricante_telefono" type="text" id="fabricante_telefono"  placeholder="Teléfono" class="obsFabricantes" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
			<div class="justificado">
            <label for="fabricante_carta" class="opcional">Referecia de carta(s) de autorización original(es) debidamente legalizada, apostillada o consularizada</label>
            <input value="" name="fabricante_carta" type="text" id="fabricante_carta" placeholder="Ingrese referencia" class="obsFabricantes" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
				
			<div data-linea="9">
				<!--<button id="btnAddFabricante" type="button" class="mas">Agregar fabricante</button>-->
				<button id="btnAgregarFabricante" type="submit" class="mas obsFabricantes">Agregar fabricante</button>
			</div>
		</fieldset>

	</form>

	<fieldset >
		<legend>Fabricantes</legend>
	
		<table id="tblFabricantesManufacturadores">
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
			foreach($fabricantes as $item){
			$fila=$cg->imprimirLineaFabricante($item);
			echo $fila;
			}
			?>

		</table>
		<div data-linea="1">
			<label id="tablaFabricantes"></label>
		</div>
	</fieldset>

	<form id='frmNuevaSolicitud2' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud'>
		<input type="hidden"  id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>"/>
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P2" />
		<button type="submit" class="guardar">Guardar solicitud</button>
	</form>
</div>

<div class="pestania" id="P3" style="display: block;">

	<form id='frmNuevoFormulador' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarFabricante'>
		<input type="hidden"  id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>"/>
		<input type="hidden" id="paso_opcion" name="paso_opcion" value="guardar" />
      <input type="hidden" id="tipo_fabricante" name="tipo_fabricante" value="R" />
		
		<fieldset>
			<legend>Datos de Formuladores</legend>
         <input type="hidden" value="" name="fabricante_id"  id="fabricante_id" />
					
         <div data-linea="1">
            <label for="fabricante_nombre" class="opcional">Nombre:</label>
            <input value="" name="fabricante_nombre" type="text" id="fabricante_nombre" class="obsFormuladores" placeholder="Nombre del formulador" required maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
			<div data-linea="3" >
				<label for="fabricante_pais">País de origen del ingrediene activo</label>
				<select name="fabricante_pais" id="fabricante_pais" class="obsFormuladores" required>
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
                  <input value="" name="fabricante_direccion" type="text" id="fabricante_direccion" placeholder="Dirección del formulador" class="obsFormuladores" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="5" class="fabricanteNacional">
                  <label for="fabricante_representante" class="opcional">Representante legal:</label>
                  <input value="" name="fabricante_representante" type="text" id="fabricante_representante" placeholder="Nombre y apellido" class="obsFormuladores" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="6" class="fabricanteNacional">
                  <label for="fabricante_correo" >Correo electrónico:</label>
                  <input value="" name="fabricante_correo" type="text" id="fabricante_correo" class="obsFormuladores" placeholder="Correo electrónico"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
				<div data-linea="7" class="fabricanteNacional">
                  <label for="fabricante_telefono" class="opcional">Telefono:</label>
                  <input value="" name="fabricante_telefono" type="text" id="fabricante_telefono" placeholder="Teléfono" class="obsFormuladores" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
               </div>
			<div class="justificado">
            <label for="fabricante_carta" class="opcional">Referecia de carta(s) de autorización original(es) debidamente legalizada, apostillada o consularizada</label>
            <input value="" name="fabricante_carta" type="text" id="fabricante_carta" placeholder="Ingrese referencia" class="obsFormuladores" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
				
			<div data-linea="9">
				
				<button id="btnAgregarFabricante" type="submit" class="mas obsFormuladores">Agregar formulador</button>
			</div>
		</fieldset>

	</form>

	<fieldset >
		<legend>Formuladores</legend>
	
		<table id="tblFormuladorManufacturadores">
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
			foreach($formuladores as $item){
			$fila=$cg->imprimirLineaFabricante($item);
			echo $fila;
			}
			?>

		</table>
		<div data-linea="1">
			<label id="tablaFormuladores"></label>
		</div>
	</fieldset>

  
	<form id='frmNuevaSolicitud3' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P3" />
		
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P4" style="display: block;">
   <form id='frmNuevaSolicitud4' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P4" />

            <fieldset>
               <legend>Identificación del producto</legend>
					<div data-linea="1">
                  <label for="producto_nombre">Nombre del producto:</label>
                  <input value="<?php echo $datosGenerales['producto_nombre'];?>" name="producto_nombre" type="text" id="producto_nombre" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
					</div>
               <div class="justificado">
                  <label for="producto_ia">Nombre del ingrediene activo:</label>
                  <textarea name="producto_ia" id="producto_ia" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled ></textarea>
               </div>
               <div class="justificado">
                  <label for="producto_pais">País(es) de origen del ingrediente activo:</label>
                  <textarea name="producto_pais" id="producto_pais" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled ></textarea>
               </div>
               <div class="justificado">
                  <label for="producto_uso">Uso(s) propuesto(s):</label>
                  <textarea name="producto_uso" id="producto_uso" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled ></textarea>
               </div>
               <div data-linea="5">
                  <label for="producto_formulacion">Tipo y código de formulación:</label>
                  <input name="producto_formulacion" id="producto_formulacion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled />
               </div>
               <div class="justificado">
                  <label for="producto_pais_producto">País de procedencia del producto formulado:</label>
                  <textarea name="producto_pais_producto" id="producto_pais_producto" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled ></textarea>
               </div>
                 					
            </fieldset>

      
            <fieldset>
               <legend>Generación de solicitud</legend>               
              <div class="justificado">
				<label for="observacion" >Condiciones de la información:</label>
				<br/>
				
            <label  id="observacion" >
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
					<hr/>
					<div data-linea="3" class="ocultarOtros">
					
						<label for="acepar_solicitud">Acepto las condiciones</label> 
						<input type="checkbox" id="acepar_solicitud" name="acepar_solicitud" value="NO">
									
					</div>
					
            </fieldset> 

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
	<form id='frmVistaPrevia' data-rutaAplicacion='dossierPlaguicida' data-opcion=''>		
		<button id="btnVistaPrevia" type="button" class="adjunto btnVistaPrevia">Generar vista previa</button>
		<a id="verReporte" href="" target="_blank" style="display:none">Ver archivo</a>
	</form>
</div>

<div class="pestania" id="P5" style="display: block;">
			
   <fieldset class="verRegistroNoClon">
      <legend>Ingredientes activos del producto</legend>
     
         <table id="tblIa" style="width:100%">
           <thead>
               <tr>
                  <th width="90%">Ingrediente activo</th>
                           
                  <th width="10%"></th>
               </tr>
            </thead>
            <tbody></tbody> 
         </table>
     
		
		<div data-linea="2">
			<label id="tablaIngredienteActivos"></label>
		</div>
   </fieldset>
    

	<form id='frmNuevaSolicitud5' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud' >
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P5" />
		<fieldset>
			<legend>Documentos habilitantes</legend>
         <div data-linea="1">
            <label for="fabricante_certificado" class="opcional">Referencia del certificado de análisis y composición del ingrediente activo grado tecnico original:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['fabricante_certificado'];?>" name="fabricante_certificado" type="text" id="fabricante_certificado" placeholder="Ingrese referencia"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="2">
            <label for="formulador_certificado" class="opcional">Referencia del certificado de análisis y composición del producto formulado original:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['formulador_certificado'];?>" name="formulador_certificado" type="text" id="formulador_certificado" placeholder="Referencia del certificado"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3">
            <label for="formulador_acreditacion" class="opcional">Referecia de la acreditación del laboratorio o reconocimiento vigente del laboratorio por la ANC acorde la norma andina 436 y sus modificatorias. Manual técnico andino o Normativa vigente</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['formulador_acreditacion'];?>" name="formulador_acreditacion" type="text" id="formulador_acreditacion" placeholder="Referencia de la acreditación"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

			<div data-linea="4">
				<label for="informe_analisis">Referencia del informe de análisis de control de calidad original emitido por AGROCALIDAD para cada ingrediente activo:</label>
				<input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['informe_analisis'];?>" name="informe_analisis" type="text" id="informe_analisis" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />                  
			</div>
			<div  class="justificado listasCirculo">
				<label>Informes finales aprobados:</label>
				<ul id="ulInformesFinales">
							
				</ul>
			</div>

			<div data-linea="6">
				<label id="tipo_declaracion_juramentada">Referecnia para declaracion juramentada:</label>
				<input value="<?php echo $datosGenerales['declaracion_juramentada'];?>" name="declaracion_juramentada" type="text" id="declaracion_juramentada" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>
			<div data-linea="7" class="verNormaNN">
				<label for="libre_venta">Referencia para cerfificado de libre venta:</label>
				<input value="<?php echo $datosGenerales['libre_venta'];?>" name="libre_venta" type="text" id="libre_venta" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"  />
			</div>

		</fieldset>
		<button type="submit" class="guardar">Guardar solicitud</button>
	</form>
	 

</div>

<div class="pestania" id="P6" style="display: block;">
   <form id='frmNuevaSolicitud6' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud' class="verRegistroNoClon">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P6" />

      <fieldset>
         <legend>Composición del producto formulado</legend>
         <div data-linea="1">
            <label for="composicion_sustancias">Contenido de sustancia(s) activa(s), grado técnico, expresado en % p/p o p/v. Certificado analítico de composición, expedido por un laboratorio reconocido por la Autoridad Nacional Competente o acreditado a nivel nacional o subregional, según corresponda, o por el laboratorio del fabricante:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['composicion_sustancias'];?>" name="composicion_sustancias" type="text" id="composicion_sustancias" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="2">
            <label for="composicion_sustancias_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['composicion_sustancias_ref'];?>" name="composicion_sustancias_ref" type="text" id="composicion_sustancias_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3">
            <label for="composicion_naturaleza">Contenido y naturaleza de los demás componentes incluidos en la formulación. Certificado analítico de composición, expedido por un laboratorio reconocido por la Autoridad Nacional Competente o acreditado:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['composicion_naturaleza'];?>" name="composicion_naturaleza" type="text" id="composicion_naturaleza" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4">
            <label for="composicion_naturaleza_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['composicion_naturaleza_ref'];?>" name="composicion_naturaleza_ref" type="text" id="composicion_naturaleza_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="composicion_metodo">Método de análisis para determinación del contenido de sustancia(s) activa(s):</label>
            <textarea name="composicion_metodo" id="composicion_metodo" maxlength="2048" class="justificado" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo htmlspecialchars($datosGenerales['composicion_metodo']); ?></textarea>            
         </div>
         <div data-linea="6">
            <label for="composicion_metodo">Referencia:</label>            
            <input value="<?php echo $datosGenerales['composicion_metodo_ref'];?>" name="composicion_metodo_ref" type="text" id="composicion_metodo_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

      </fieldset>
      
      <fieldset>
         <legend>Propiedades físicas y químicas</legend>
         <label>ASPECTO</label>
         <hr />
         <div data-linea="1">
            <label for="estado_fisico">Estado físico:</label>
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
         <div data-linea="2">
            <label for="color">Color:</label>
            <input value="<?php echo $datosGenerales['color'];?>" name="color" type="text" id="color" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3">
            <label for="color_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['color_ref'];?>" name="color_ref" type="text" id="color_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4">
            <label for="olor">Olor:</label>
            <input value="<?php echo $datosGenerales['olor'];?>" name="olor" type="text" id="olor" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="olor_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['olor_ref'];?>" name="olor_ref" type="text" id="olor_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6">
            <label for="estabilidad">Estabilidad en el almacenamiento (respecto de su composición y a las propiedades físicas relacionadas con el uso):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['estabilidad'];?>" name="estabilidad" type="text" id="estabilidad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="7">
            <label for="estabilidad_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['estabilidad_ref'];?>" name="estabilidad_ref" type="text" id="estabilidad_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8">
            <label for="densidad">Densidad relativa:</label>
            <input value="<?php echo $datosGenerales['densidad'];?>" name="densidad" type="text" id="densidad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="9">
            <label for="densidad_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['densidad_ref'];?>" name="densidad_ref" type="text" id="densidad_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <hr />
         <div data-linea="10">
            <label>INFLAMABILIDAD</label>
         </div>


         <div data-linea="11" class="inflamacion-liquido">
            <label for="punto_inflamacion">Punto de inflamación [ºC]:</label>
            <input value="<?php echo $datosGenerales['punto_inflamacion'];?>" name="punto_inflamacion" type="text" id="punto_inflamacion" maxlength="10" data-er="^[0-9]+$" />
         </div>
         <div data-linea="12" class="inflamacion-solido">
            <label for="inflamacion_es_solido">Es producto inflamable ?:</label>
            SI<input data-distribuir='no' type="radio" id="inflamacion_es_solidoSI" name="inflamacion_es_solido" value="SI" <?php if($datosGenerales['inflamacion_es_solido']=='t') echo "checked=true"?> />
            NO<input data-distribuir='no' type="radio" id="inflamacion_es_solidoNO" name="inflamacion_es_solido" value="NO" <?php if($datosGenerales['inflamacion_es_solido']=='f') echo "checked=true"?> />
         </div>
         <div class="verticalPadre">
            <label id="inflamacion_tipo" class="verticalHijo"></label>  
            <div id="icon_inflamacion_tipo" class="verticalHijo"></div>
         </div>
        
         <div data-linea="14">
            <label for="inflamacion_adjunto">Referencia:</label>
            <input value="<?php echo $datosGenerales['inflamacion_adjunto'];?>" name="inflamacion_adjunto" type="text" id="inflamacion_adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <hr />
         <div data-linea="16">
            <label for="ph">pH:</label>
            <input value="<?php echo $datosGenerales['ph'];?>" name="ph" type="number" id="ph" min="0" max="14" step="0.1" data-er="^[0-9]+$" />
         </div>
         <div data-linea="17">
            <label for="ph_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['ph_ref'];?>" name="ph_ref" type="text" id="ph_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="18">
            <label for="es_explosivo">Es producto explosivo ?:</label>
            SI<input type="radio" id="es_explosivoSI" name="es_explosivo" value="SI" <?php if($datosGenerales['es_explosivo']=='t') echo "checked=true"?> />
            NO<input type="radio" id="es_explosivoNO" name="es_explosivo" value="NO" <?php if($datosGenerales['es_explosivo']=='f') echo "checked=true"?> />
         </div>
         <div data-linea="19" >
            <label id="es_explosivo_logo" class="es_explosivo_logo"></label>
         </div>
         <div data-linea="20">
            <label for="explosivo_referencia">Referencia:</label>
            <input value="<?php echo $datosGenerales['explosivo_referencia'];?>" name="explosivo_referencia" type="text" id="explosivo_referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

      </fieldset>
    
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

   <fieldset class="verRegistroClon">
      <legend>Seccion no aplica</legend>
      <div data-linea="1">
         <label >Esta sección no aplica para registro de productos clones (pase al siguiente paso)</label>
        
      </div>
   </fieldset>
</div>

<div class="pestania" id="P7" style="display: block;">
   <form id='frmNuevaSolicitud7' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud' class="verRegistroNoClon">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P7" />

      <fieldset>
         <legend>Propiedades físicas del produco formulado, relacionadas con su uso</legend>
         <div data-linea="1" class="estado-solido">
            <label for="humedad">Humedad y humectabilidad (para los polvos dispersables):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['humedad'];?>" name="humedad" type="text" id="humedad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="2" class="estado-solido">
            <label for="humedad_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['humedad_ref'];?>" name="humedad_ref" type="text" id="humedad_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3">
            <label for="persistencia">Persistencia de espuma (para los formulados que se aplican en el agua):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['persistencia'];?>" name="persistencia" type="text" id="persistencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="4">
            <label for="persistencia_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['persistencia_ref'];?>" name="persistencia_ref" type="text" id="persistencia_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5" class="estado-solido">
            <label for="suspensibilidad">Suspensibilidad para los polvos dispersables y los concentrados en suspensión:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['suspensibilidad'];?>" name="suspensibilidad" type="text" id="suspensibilidad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="6" class="estado-solido">
            <label for="suspensibilidad_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['suspensibilidad_ref'];?>" name="suspensibilidad_ref" type="text" id="suspensibilidad_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="7" class="estado-solido">
            <label for="granulometria_humedo"> Análisis granulométricos en húmedo/tenor de polvo (para los polvos dispersables y los concentrados en suspensión):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['granulometria_humedo'];?>" name="granulometria_humedo" type="text" id="granulometria_humedo" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="8" class="estado-solido">
            <label for="granulometria_humedo_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['granulometria_humedo_ref'];?>" name="granulometria_humedo_ref" type="text" id="granulometria_humedo_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="9" class="estado-solido">
            <label for="granulometria_seco"> Análisis granulométrico en seco (para gránulos y polvos):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['granulometria_seco'];?>" name="granulometria_seco" type="text" id="granulometria_seco" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="10" class="estado-solido">
            <label for="granulometria_seco_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['granulometria_seco_ref'];?>" name="granulometria_seco_ref" type="text" id="granulometria_seco_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="11">
            <label for="estabilidad_emulsion">Estabilidad de la emulsión (para los concentrados emulsionables):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['estabilidad_emulsion'];?>" name="estabilidad_emulsion" type="text" id="estabilidad_emulsion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="12">
            <label for="estabilidad_emulsion_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['estabilidad_emulsion_ref'];?>" name="estabilidad_emulsion_ref" type="text" id="estabilidad_emulsion_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="14">
            <label for="corrosivo_ref">Corrosividad:</label>
            <input value="<?php echo $datosGenerales['corrosivo_ref'];?>" name="corrosivo_ref" type="text" id="corrosivo_ref" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="15">
            <label for="es_corrosivo">Es producto corrosivo ?:</label>
            SI<input type="radio" id="es_corrosivoSI" name="es_corrosivo" value="SI" <?php if($datosGenerales['es_corrosivo']=='t') echo "checked=true"?> />
            NO<input type="radio" id="es_corrosivoNO" name="es_corrosivo" value="NO" <?php if($datosGenerales['es_corrosivo']=='f') echo "checked=true"?> />            
         </div>
			<div data-linea="16">
            <label id="es_corrosivo_logo" class="es_corrosivo_logo"></label>

         </div>

         <div data-linea="18">
            <label for="incompatibilidad">Incompatibilidad conocida con otros productos (Ej.: fitosanitarios y fertilizantes):</label>
            <textarea class="justificado" data-distribuir="no" name="incompatibilidad" id="incompatibilidad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">					
					<?php echo htmlspecialchars($datosGenerales['incompatibilidad']); ?>
				</textarea>            
         </div>
			<div data-linea="19">
            <label for="incompatibilidad_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['incompatibilidad_ref'];?>" name="incompatibilidad_ref" type="text" id="incompatibilidad_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="20" class="estado-liquido">
            <label for="viscosidad">Viscosidad (para suspensiones y emulsiones):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['viscosidad'];?>" name="viscosidad" type="text" id="viscosidad" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="21" class="estado-liquido">
            <label for="viscosidad_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['viscosidad_ref'];?>" name="viscosidad_ref" type="text" id="viscosidad_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="22" class="miscible-esparcible">
            <label for="sulfonacion">Indice de sulfonación (aceites):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['sulfonacion'];?>" name="sulfonacion" type="text" id="sulfonacion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="23" class="miscible-esparcible">
            <label for="sulfonacion_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sulfonacion_ref'];?>" name="sulfonacion_ref" type="text" id="sulfonacion_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="24" class="estado-solido">
            <label for="dispersion">Dispersión (para gránulos dispersables):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['dispersion'];?>" name="dispersion" type="text" id="dispersion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="25" class="estado-solido">
            <label for="dispersion_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['dispersion_ref'];?>" name="dispersion_ref" type="text" id="dispersion_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="26" class="estado-solido">
            <label for="desprendimiento">Desprendimiento de gas (sólo para gránulos generadores de gas u otros productos similares):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['desprendimiento'];?>" name="desprendimiento" type="text" id="desprendimiento" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="27" class="estado-solido">
            <label for="desprendimiento_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['desprendimiento_ref'];?>" name="desprendimiento_ref" type="text" id="desprendimiento_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="28" class="estado-solido">
            <label for="soltura">Soltura o fluidez para polvos secos:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['soltura'];?>" name="soltura" type="text" id="soltura" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="29" class="estado-solido">
            <label for="soltura_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['soltura_ref'];?>" name="soltura_ref" type="text" id="soltura_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="30" class="miscible-esparcible">
            <label for="indice_yodo">Indice de yodo e índice de saponificación (para aceites vegetales):</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['indice_yodo'];?>" name="indice_yodo" type="text" id="indice_yodo" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />            
         </div>
			<div data-linea="31" class="miscible-esparcible">
            <label for="indice_yodo_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['indice_yodo_ref'];?>" name="indice_yodo_ref" type="text" id="indice_yodo_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>


      </fieldset>

      <fieldset>
         <legend>Datos sobre aplicación del producto formulado</legend>

         <div data-linea="1">
            <label for="id_informe_final">Informe final para la dosis</label>
            <select name="id_informe_final" id="id_informe_final" required></select>
         </div>


         <div data-linea="3">
            <label for="reingreso">Tiempo de reingreso al area tratada:</label>
            <input value="<?php echo $datosGenerales['reingreso'];?>" name="reingreso" type="text" id="reingreso" placeholder="reingreso" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4">
            <label for="carencia">Período de carencia o espera:</label>
            <textarea class="justificado" data-distribuir="no" name="carencia" id="carencia" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['carencia']); ?>
				</textarea>            
         </div>
			<div data-linea="5">
            <label for="carencia_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['carencia_ref'];?>" name="carencia_ref" type="text" id="carencia_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="7">
            <label for="efectos_cultivos">Efectos sobre cultivos sucesivos:</label>
            <textarea class="justificado" data-distribuir="no" name="efectos_cultivos" id="efectos_cultivos" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['efectos_cultivos']); ?>
				</textarea>            
         </div>
			<div data-linea="8">
            <label for="efectos_cultivos_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['efectos_cultivos_ref'];?>" name="efectos_cultivos_ref" type="text" id="efectos_cultivos_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

   <fieldset class="verRegistroClon">
      <legend>Seccion no aplica</legend>
      <div data-linea="1">
         <label>Esta sección no aplica para registro de productos clones (pase al siguiente paso)</label>

      </div>
   </fieldset>
</div>

<div class="pestania" id="P8" style="display: block;">
   <form id='frmNuevaSolicitud8' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud' class="verRegistroNoClon">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P8" />

      <fieldset>
         <legend>Etiquetado del producto formulado</legend>

         <div data-linea="2">
            <label for="presentacion_tipo">Ingrese la presentación comercial:</label>
            <select name="presentacion_tipo" id="presentacion_tipo" class="obsPresentacion">
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
            <input value="" name="presentacion_cantidad" type="text" id="presentacion_cantidad" class="obsPresentacion" maxlength="3" data-er="^[0-9.]+$" />
         </div>
         <div data-linea="3">
            <label for="presentacion_unidad">Unidad:</label>
            <select name="presentacion_unidad" id="presentacion_unidad" class="obsPresentacion">
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
            <input value="" name="partida_arancelaria" type="text" id="partida_arancelaria" class="obsPresentacion" maxlength="10" data-er="^[0-9.]+$" />
         </div>
         <div data-linea="6">
            <label for="codigo_complementario">Codigo complementario:</label>
            <select name="codigo_complementario" id="codigo_complementario" class="obsPresentacion">
               <option value="">Seleccione....</option>               <?php
               for ($i=0;$i<6;$i++){
               echo '<option value="' . $i . '">' . str_pad($i, 4, '0', STR_PAD_LEFT). '</option>';
               }
               ?>
            </select>
         </div>
         <div data-linea="6">
            <label for="codigo_suplementario">Codigo suplementario:</label>
            <select name="codigo_suplementario" id="codigo_suplementario" class="obsPresentacion">
               <option value="">Seleccione....</option>               <?php
               for ($i=0;$i<11;$i++){
               echo '<option value="' . $i . '">' . str_pad($i, 4, '0', STR_PAD_LEFT). '</option>';
               }
               ?>
            </select>
         </div>

         <button type="button" id="btnAddPresentacion" class="mas obsPresentacion">Agregar</button>
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
         <div data-linea="8">
            <label id="tablaPresentacionEtiqueta"></label>
         </div>
         <hr />
         <div data-linea="10">
            <label for="precaucion_uso">Precauciones y advertencias de uso y aplicación:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['precaucion_uso'];?>" name="precaucion_uso" type="text" id="precaucion_uso" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="11">
            <label for="almacenamieno_manejo">Almacenamiento y manejo del producto:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['almacenamieno_manejo'];?>" name="almacenamieno_manejo" type="text" id="almacenamieno_manejo" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <hr />

         <div data-linea="12">
            <label for="aux_ingestion">Medidas relativas a primeros auxilios:</label>

            <textarea class="justificado" data-distribuir="no" name="aux_ingestion" id="aux_ingestion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['aux_ingestion']); ?>
				</textarea>
         </div>

         <div data-linea="13">
            <label for="aux_telefono">Teléfono de la empresa en caso de intoxicación:</label>
            <input value="<?php echo $datosGenerales['aux_telefono'];?>" name="aux_telefono" type="text" id="aux_telefono" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="14">
            <label for="aux_disposicion">Medidas relativas para la disposición de envases vacíos:</label>
            <textarea class="justificado" data-distribuir="no" name="aux_disposicion" id="aux_disposicion" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php
                  $items=$ce->listarElementosCatalogo($conexion,'P2C5');
						foreach ($items as $key=>$item){
							echo  $item['nombre'] ;
						}
                        ?>
				</textarea>

         </div>

         <div data-linea="15">
            <label for="aux_ambiente">Medidas para la protección del medio ambiente:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['aux_ambiente'];?>" name="aux_ambiente" type="text" id="aux_ambiente" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="16">
            <label for="aux_instrucciones">Instrucciones de uso y manejo:</label>
            <textarea class="justificado" data-distribuir="no" name="aux_instrucciones" id="aux_instrucciones" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['aux_instrucciones']); ?>
				</textarea>
         </div>
         <div data-linea="17">
            <label for="frecuencia_aplicacion">Epoca y frecuencia de aplicación:</label>
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['frecuencia_aplicacion'];?>" name="frecuencia_aplicacion" type="text" id="frecuencia_aplicacion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="18">
            <label for="responsabilidad">Responsabilidad:</label>
            <textarea class="justificado" data-distribuir="no" name="responsabilidad" id="responsabilidad" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
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
         <div data-linea="20" class="ingrediente_paraquat">
            <label for="paraquat">Frase para Paraquat:</label>
            <textarea class="justificado" data-distribuir="no" name="paraquat" id="paraquat" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['paraquat']); ?>	
				</textarea>
         </div>

         <div data-linea="21">
            <label for="tiene_hoja_informativa">Tiene hoja informativa ?:</label>
            SI<input type="radio" id="tiene_hoja_informativaSI" name="tiene_hoja_informativa" value="SI" <?php if($datosGenerales['tiene_hoja_informativa']=='t') echo "checked=true"?> />
            NO<input type="radio" id="tiene_hoja_informativaNO" name="tiene_hoja_informativa" value="NO" <?php if($datosGenerales['tiene_hoja_informativa']=='f') echo "checked=true"?> />
         </div>
			 <div data-linea="22">
				 <label id="obs_tiene_hoja_informativa"></label>
			 </div>
         <div data-linea="24" class="verHojaInformativa">
            <label for="hoja_informativa">Descripción de la hoja informativa:</label>
            <textarea class="justificado" data-distribuir="no" name="hoja_informativa" id="hoja_informativa" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['hoja_informativa']); ?>
				</textarea>            
         </div>
			<div data-linea="25" class="verHojaInformativa">
            <label for="hoja_informativa_ref">Referencia:</label>            
            <input class="justificado" data-distribuir="no" value="<?php echo $datosGenerales['hoja_informativa_ref'];?>" name="hoja_informativa_ref" type="text" id="hoja_informativa_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

      </fieldset>

      <fieldset>
         <legend>Envases y embalajes propuestos para el producto formulado</legend>
         <label>ENVASES</label><hr />
         <div data-linea="1">
            <label for="envase_tipo">Tipo:</label>
            <input value="<?php echo $datosGenerales['envase_tipo'];?>" name="envase_tipo" type="text" id="envase_tipo" placeholder="Describa los tipos de envases" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="2">
            <label for="envase_material">Material:</label>
            <textarea class="justificado" data-distribuir="no"  name="envase_material" id="envase_material" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['envase_material']); ?>
				</textarea>
         </div>
         <div data-linea="3">
            <label for="envase_capacidad">Capacidad:</label>
            <input value="<?php echo $datosGenerales['envase_capacidad'];?>" name="envase_capacidad" type="text" id="envase_capacidad" placeholder="Capacidad los tipos de envases" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4">
            <label for="envase_resistencia">Resistencia:</label>
            <textarea class="justificado" data-distribuir="no"  name="envase_resistencia" id="envase_resistencia" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['envase_resistencia']); ?>
				</textarea>            
         </div>
			<div data-linea="5">
            <label for="envase_resistencia_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['envase_resistencia_ref'];?>" name="envase_resistencia_ref" type="text" id="envase_resistencia_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <hr />
         <label>EMBALAJES</label>
         <hr />
         <div data-linea="7">
            <label for="embalaje_tipo">Tipo:</label>
            <input value="<?php echo $datosGenerales['embalaje_tipo'];?>" name="embalaje_tipo" type="text" id="embalaje_tipo" placeholder="Describa los tipos de envases" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8">
            <label for="embalaje_material">Material:</label>
            <textarea class="justificado" data-distribuir="no" name="embalaje_material" id="embalaje_material" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['embalaje_material']); ?>
				
			</textarea>
         </div>
         <div data-linea="9">
            <label for="embalaje_capacidad">Capacidad:</label>
            <input value="<?php echo $datosGenerales['embalaje_capacidad'];?>" name="embalaje_capacidad" type="text" id="embalaje_capacidad" placeholder="Capacidad los tipos de envases" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="10">
            <label for="embalaje_resistencia">Resistencia:</label>
            <textarea class="justificado" data-distribuir="no" name="embalaje_resistencia" id="embalaje_resistencia" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['embalaje_resistencia']); ?>
					
				</textarea>            
         </div>
			<div data-linea="11">
            <label for="embalaje_resistencia_ref">Referencia:</label>
            <input value="<?php echo $datosGenerales['embalaje_resistencia_ref'];?>" name="embalaje_resistencia_ref" type="text" id="embalaje_resistencia_ref" placeholder="Ingrese la referencia del adjunto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <hr />
         <div data-linea="12">
            <label for="accion_envases">Acción del producto sobre el material de los envases:</label>
            <textarea class="justificado" data-distribuir="no" name="accion_envases" id="accion_envases" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['accion_envases']); ?>
				
			</textarea>
         </div>
         <div data-linea="13">
            <label for="destruccion_envaces">Procedimientos para la descontaminación y destrucción de los envases:</label>
            <textarea class="justificado" data-distribuir="no"  name="destruccion_envaces" id="destruccion_envaces" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['destruccion_envaces']); ?>
				
			</textarea>
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>

   <fieldset class="verRegistroClon">
      <legend>Seccion no aplica</legend>
      <div data-linea="1">
         <label>Esta sección no aplica para registro de productos clones (pase al siguiente paso)</label>

      </div>
   </fieldset>

</div>

<div class="pestania" id="P9" style="display: block;">


   <form id='frmNuevaSolicitud9' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud' class="verRegistroNoClon">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P9" />
     
		<fieldset>
         <legend>Datos sobre el manejo de sobrantes del producto formulado</legend>
         <div data-linea="1">
            <label for="sobra_destruccion">Procedimientos para la destrucción de la sustancia activa y para la descontaminación:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_destruccion" id="sobra_destruccion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_destruccion']); ?>
				</textarea>            
         </div>
			<div data-linea="2">
            <label for="sobra_destruccion_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_destruccion_ref'];?>" name="sobra_destruccion_ref" type="text" id="sobra_destruccion_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="3">
            <label for="sobra_residuos">Métodos de disposición final de los residuos:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_residuos" id="sobra_residuos" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['sobra_residuos']); ?>
					
				</textarea>            
         </div>
			<div data-linea="4">
            <label for="sobra_residuos_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_residuos_ref'];?>" name="sobra_residuos_ref" type="text" id="sobra_residuos_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="sobra_recuperacion">Posibilidades de recuperación (si se dispone):</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_recuperacion" id="sobra_recuperacion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_recuperacion']); ?>
				</textarea>            
         </div>
			<div data-linea="6">
            <label for="sobra_recuperacion_ref">Referencia:</label>           
            <input value="<?php echo $datosGenerales['sobra_recuperacion_ref'];?>" name="sobra_recuperacion_ref" type="text" id="sobra_recuperacion_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="7">
            <label for="sobra_neutralizacion">Posibilidades de neutralización:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_neutralizacion" id="sobra_neutralizacion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_neutralizacion']); ?>
				</textarea>            
         </div>
			<div data-linea="8">
            <label for="sobra_neutralizacion_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_neutralizacion_ref'];?>" name="sobra_neutralizacion_ref" type="text" id="sobra_neutralizacion_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="9">
            <label for="sobra_incineracion">Incineración controlada (condiciones):</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_incineracion" id="sobra_incineracion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_incineracion']); ?>
				</textarea>            
         </div>
			<div data-linea="10">
            <label for="sobra_incineracion_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_incineracion_ref'];?>" name="sobra_incineracion_ref" type="text" id="sobra_incineracion_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="11">
            <label for="sobra_depuracion">Depuración de las aguas:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_depuracion" id="sobra_depuracion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_depuracion']); ?>
				</textarea>            
         </div>
			<div data-linea="12">
            <label for="sobra_depuracion_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_depuracion_ref'];?>" name="sobra_depuracion_ref" type="text" id="sobra_depuracion_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="13">
            <label for="sobra_precauciones">Métodos recomendados y precauciones de manejo durante su manipulación, almacenamiento, transporte y en caso de incendio:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_precauciones" id="sobra_precauciones" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_precauciones']); ?>
				</textarea>            
         </div>
			<div data-linea="14">
            <label for="sobra_precauciones_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_precauciones_ref'];?>" name="sobra_precauciones_ref" type="text" id="sobra_precauciones_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="16">
            <label for="sobra_incendio">En caso de incendio, productos de reacción y gases de combustión:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_incendio" id="sobra_incendio" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_incendio']); ?>
				</textarea>            
         </div>
			<div data-linea="17">
            <label for="sobra_incendio_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_incendio_ref'];?>" name="sobra_incendio_ref" type="text" id="sobra_incendio_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="18">
            <label for="sobra_equipo">Información sobre equipo de protección individual:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_equipo" id="sobra_equipo" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_equipo']); ?>
				</textarea>            
         </div>
			<div data-linea="19">
            <label for="sobra_equipo_ref">Referencia:</label>           
            <input value="<?php echo $datosGenerales['sobra_equipo_ref'];?>" name="sobra_equipo_ref" type="text" id="sobra_equipo_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="21">
            <label for="sobra_limpieza">Procedimientos de limpieza del equipo de aplicación:</label>
            <textarea class="justificado" data-distribuir="no" name="sobra_limpieza" id="sobra_limpieza" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['sobra_limpieza']); ?>
				</textarea>            
         </div>
			<div data-linea="22">
            <label for="sobra_limpieza_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['sobra_limpieza_ref'];?>" name="sobra_limpieza_ref" type="text" id="sobra_limpieza_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
		</fieldset>

		<fieldset>
			<legend>Datos sobre los residuos del producto formulado</legend>
         <div data-linea="1">
            <label for="residuo_obtenidos">Datos de residuos obtenidos en base a ensayos protocolizados, según las normas internacionales (Directrices de FAO para el establecimiento de Límites Máximos de Residuos (LMRs). (Según lo establecido en el Manual Técnico):</label>
            <textarea class="justificado" data-distribuir="no" name="residuo_obtenidos" id="residuo_obtenidos" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['residuo_obtenidos']); ?>
				</textarea>            
         </div>
			<div data-linea="2">
            <label for="residuo_obtenidos_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['residuo_obtenidos_ref'];?>" name="residuo_obtenidos_ref" type="text" id="residuo_obtenidos_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         
         <div data-linea="3">
            <label for="residuo_hoja">Hoja de seguridad en español elaborada por el fabricante o formulador:</label>
            <textarea class="justificado" data-distribuir="no" name="residuo_hoja" id="residuo_hoja" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['residuo_hoja']); ?>
				</textarea>            
         </div>
			<div data-linea="4">
            <label for="residuo_hoja_ref">Referencia:</label>            
            <input value="<?php echo $datosGenerales['residuo_hoja_ref'];?>" name="residuo_hoja_ref" type="text" id="residuo_hoja_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="residuo_evaluacion">Resumen de la evaluación del producto (grado técnico y formulado). Síntesis de la interpretación técnica-científica de la información química del plaguicida agrícola, correlacionada con la información resultante de los estudios de eficacia toxicológicos, ecotoxicológicos y ambientales:</label>
            <textarea class="justificado" data-distribuir="no" name="residuo_evaluacion" id="residuo_evaluacion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					
					<?php echo htmlspecialchars($datosGenerales['residuo_evaluacion']); ?>
				</textarea>            
         </div>
			<div data-linea="6">
            <label for="residuo_evaluacion_ref">Referencia:</label>           
            <input value="<?php echo $datosGenerales['residuo_evaluacion_ref'];?>" name="residuo_evaluacion_ref" type="text" id="residuo_evaluacion_ref" placeholder="Ingrese la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

			 <div data-linea="7">
            <label for="f_tiene_contrato">Presenta aditivos de importancia toxicológica ?</label>
            SI<input type="radio" id="tiene_aditivos_toxicosSI" name="tiene_aditivos_toxicos" value="SI" <?php if($datosGenerales['tiene_aditivos_toxicos']=='t') echo "checked=true"?>/>
            NO<input type="radio" id="tiene_aditivos_toxicosNO" name="tiene_aditivos_toxicos" value="NO" <?php if($datosGenerales['tiene_aditivos_toxicos']=='f') echo "checked=true"?>/>
         </div>

      </fieldset>
		
   </form>

	<form id='frmNuevaAditivo' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosSolicitud' class="verRegistroNoClon verAditivosToxicos">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P11" />
		<fieldset>
			<legend>Nuevo aditivo toxicológico</legend>
        
			<div data-linea="1" class="verAditivosToxicos">
            <label for="ad_nombre">Nombre</label>
            <input value="" name="ad_nombre" id="ad_nombre" class="obsAditivosToxicos" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="2" class="verAditivosToxicos">
            <label for="ad_cantidad">Cantidad</label>
            <input value="" name="ad_cantidad" id="ad_cantidad" class="obsAditivosToxicos" type="text" maxlength="10" data-er="^[0-9]{10}+$" />
         </div>
         <div data-linea="2" class="verAditivosToxicos">
            <label for="ad_unidad">Unidad</label>
            <select name="ad_unidad" id="ad_unidad" class="obsAditivosToxicos">
               <option value="">Seleccione....</option>
					<?php
							$items=$ce->obtenerUnidadesMedida($conexion,'DP_COMP');
							foreach ($items as $key=>$item){
								echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
							}
                     ?>
            </select>
         </div>
			<button type="button" id="btnAddAditivos" class="mas verAditivosToxicos obsAditivosToxicos">Agregar</button>

		</fieldset>
	</form>

	<fieldset class="verRegistroNoClon verAditivosToxicos">
		<legend>Aditivos toxicológicos reportados</legend>
		<table id="tblAditivos" style="width:100%" class="verAditivosToxicos">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Cantidad</th>
					<th>Unidad</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				<?php
				if($id_solicitud!=null && $id_solicitud!=_nuevo){
					echo $cg->imprimirAditivosToxicologicos($conexion,$id_solicitud);
				}
						?>
			</tbody>
      </table>
		<div data-linea="1">
			<label id="tablaAditivosToxicos"></label>
		</div>
	</fieldset>

	<fieldset class="verRegistroClon">
      <legend>Seccion no aplica</legend>
      <div data-linea="1">
         <label >Esta sección no aplica para registro de productos clones (pase al siguiente paso)</label>
        
      </div>
   </fieldset>

	<form class="verRegistroNoClon">
		<button id="guardarPaso9" type="button" class="guardar">Guardar solicitud</button>
	</form>
</div>

<div class="pestania" id="P10" style="display: block;">
	
	<form id="frmAnexos" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarPasosSolicitud" >
		<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="fase" name="fase" value="solicitud">            
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P10" />

		<fieldset>
		   	<legend>Carga de archivos anexos</legend>
				<div data-linea="1">
					<label for="tipoArchivo" class="opcional">Tipo de archivo:</label> 
					<select name="tipoArchivo" id="tipoArchivo" class="obsArchivosAnexos" required>
						<option value="">Seleccione....</option>
						<?php 								
						foreach ($tipoAnexos as $key=>$item){
							echo '<option value="' . $item['codigo'] . '" data-tamano="' . $item['nombre2'] . '">' . $item['nombre'] . '</option>';
						}
                        ?>
					</select>
				</div>
             
				<div data-linea="3">
					<label for="referencia" class="opcional">Referencia para el documento:</label>
					<input value="" type="text" id="referencia" name="referencia"  placeholder="ponga la referencia" class="obsArchivosAnexos" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required/>
				</div>
				<div data-linea="4">
					<input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />

					<input type="hidden" id="maxCapacidad" class="maxCapacidad" value="<?php echo $maxArchivoEE*1024; ?>" />
                  
					<input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled"/>
					<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $maxArchivoEE.'K'; ?>B)</div>							
					<button type="button" class="subirArchivo adjunto obsArchivosAnexos" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
				</div>
				
		</fieldset>
           
	</form>
	
	<fieldset>
		<legend>Archivos subidos</legend>
      <table id="tabla_archivos" style="width:100%">
         <thead>
            <tr>
               <th width="20%">Tipo archivo</th>
               <th width="60%">Referencia</th>
               <th width="18%"></th>
            </tr>
         </thead>
			<tbody>
				<?php 
					if($id_solicitud!=null && $id_solicitud!=_nuevo){
						echo $cg->imprimirArchivosAnexos($conexion,$id_solicitud);
					}
					?>
         </tbody>             
      </table>
		<div data-linea="1">
			<label id="tablaArchivosAnexos"></label>
		</div>
					
	</fieldset>

   <form id="frmNuevaSolicitud10" data-rutaAplicacion="dossierPlaguicida" data-opcion="guardarPasosSolicitud">
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="P11" />
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P12" style="display: block;">
   
	<form id="frmFinalizarSolicitud12" data-rutaAplicacion="dossierPlaguicida" data-opcion="finalizarSolicitud" data-accionEnExito='ACTUALIZAR'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />

      <input type="hidden" id="id_subtipo_producto" name="id_subtipo_producto" value="<?php echo $datosGenerales['id_subtipo_producto'];?>" />


      <fieldset>
         <legend>Finalizar solicitud</legend>

         <div class="justificado">
            <label for="observacion">Condiciones de la información:</label>
            <br />
            <label id="observacion">            
            <?php
            echo $declaracionLegal['pie'];
            ?>
            </label>
         </div>
         <div data-linea="2">
            <label>            <?php
            echo '<a href='.$declaracionLegal['encabezado'].' target="_blank">Lea información confidencial</a>';
                               ?>
            </label>
         </div>
         <hr />
         <div data-linea="3" class="noRevision">
            <label for="boolAcepto">Acepto las condiciones</label>
				<input type="checkbox" id="boolAcepto" name="boolAcepto" value="NO">
            
         </div>
      </fieldset>
      <button id="btnFinalizar" type="button" class="guardar">Finalizar</button>
   </form>

	<form id='frmVistaPreviaDossier' data-rutaAplicacion='dossierPlaguicida' data-opcion='' class="verRegistroNoClon">		
		<button id="btnVistaPreviaDossier" type="button" class="adjunto btnVistaPreviaDossier">Generar vista previa</button>
		<a id="verReporteDossier" href="" target="_blank" style="display:none">Ver archivo</a>
	</form>
	

</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>


<script type="text/javascript">

	var numeroPestania = <?php echo json_encode($numeroPestania);?>;
	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	var protocolosAprobados=<?php echo json_encode($protocolosAprobados); ?>;
	var registroProductos=<?php echo json_encode($registroProductos); ?>;
	var registroProductosMatriz=<?php echo json_encode($registroProductosMatriz); ?>;
	

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

	acciones("#frmNuevoFabricante", "#tblFabricantesManufacturadores");
	acciones("#frmNuevoFormulador", "#tblFormuladorManufacturadores");
	

	$("#btnSolicitudEtiqueta").click(function(event){
		

		event.preventDefault();

		var form=$(this).parent();

		var error = false;

		if (!error){


			form.attr('data-destino', 'detalleItem');


			abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios

		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});


	//****************** CARGA *************************************
	$("document").ready(function(){

		

		construirAnimacion($(".pestania"),numeroPestania);

		distribuirLineas();

		//deshabilita el boton siguiente de desplamiento para obligar a guardar el documento
		$('.bsig').attr("disabled", "disabled");

		//habilita los botones según estado del documento

		try{
			if(solicitud!=null){
				reconocerNivel(solicitud.nivel);
				identificarNormativa(solicitud.normativa=='NA');
				if(solicitud.protocolo!=null && solicitud.protocolo!='')
				{
					cargarValorDefecto('protocolo',solicitud.protocolo);
					obtenerInformesFinales(solicitud.protocolo);
					identificarProducto();
				}
				
				verRegistroClones(solicitud.es_clon=="t");

				if(solicitud.clon_registro_madre!=null && solicitud.clon_registro_madre.trim().length>0)
					actualizarDatosDelClon(solicitud.clon_registro_madre,true);
			}
			
			verFabricantes(fabricantes);
			verFormuladores(formuladores);



			valoresRecuperados();

		}catch(e){}

		
	});

	$('.bsig').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();

	});

	$('.bant').click(function () {
		$("#estado").html('');
		$("#estado").removeClass();

	});

	function valoresRecuperados(){
		try{

			$('#icon_inflamacion_tipo').removeClass();
			$('#inflamacion_tipo').html('');
			verEstadoInflamacion(solicitud.estado_fisico);
			if(solicitud.estado_fisico=='EFC_SOLI'){

				if(solicitud.inflamacion_es_solido=='t'){
					$('#inflamacion_tipo').html('Inflamable');
					$('#icon_inflamacion_tipo').addClass('solido-inflamable');
				}
				else
					$('#icon_inflamacion_tipo').removeClass();
				
			}
			else{
				verPictogramaInflamacion(solicitud.punto_inflamacion);
			}
		}catch(e){}

		try{
			$('#es_explosivo_logo').removeClass();
			if(solicitud.es_explosivo=='t')
				$('#es_explosivo_logo').addClass('es_explosivo_logo');

		}catch(e){}


		try{
			$('#es_corrosivo_logo').removeClass();
			if(solicitud.es_corrosivo=='t')
				$('#es_corrosivo_logo').addClass('es_corrosivo_logo');

		}catch(e){}


		try{
			if(solicitud.estado_fisico!='')
				verCamposEstado(solicitud.estado_fisico);

		}catch(e){}

		try{
			verPresentacion(presentaciones);
		}catch(e){}

		try{
			verHojaInformativa(solicitud.tiene_hoja_informativa=="t");
		}catch(e){}

		try{
			verAditivosToxicos(solicitud.tiene_aditivos_toxicos=='t');

			}catch(e){}
	}

	function verEstadoInflamacion(estado){
		try{
			$('#icon_inflamacion_tipo').removeClass();
			if(estado==null){
				$('.inflamacion-solido').hide();
				$('.inflamacion-liquido').hide();
			}
			else{
				if(estado=='EFC_SOLI'){
					$('.inflamacion-solido').show();
					$('.inflamacion-liquido').hide();


				}
				else{
					$('.inflamacion-solido').hide();
					$('.inflamacion-liquido').show();

				}
			}
			distribuirLineas();

		}catch(e){}
	}

	function verCamposEstado(estado){
		if(estado==null){
			$('.estado-solido').hide();
			$('.estado-liquido').hide();
		}
		else if(estado=='EFC_SOLI'){
			$('.estado-solido').show();
			$('.estado-liquido').hide();
		}else{
			$('.estado-solido').hide();
			$('.estado-liquido').show();
		}

	}

	$('#estado_fisico').change(function(){

		$('#inflamacion_es_solidoNO').prop( "checked", true );
		$('#punto_inflamacion').val('');
		$('#icon_inflamacion_tipo').removeClass();
		$('#inflamacion_tipo').html('');
		$('#inflamacion_adjunto').val('');
		
		verEstadoInflamacion($(this).val());

		verCamposEstado($(this).val());
	});

	function recuperarProtocolo(){
		var strp='PRODUCTO DE USO RESTRINGIDO, SOLO PARA CONTROL DE MALEZAS '+ splagas+' EN CULTIVO DE '+ scultivos;
	}

	//**************************************** campos numericos **********************************
	$('#partida_arancelaria').numeric();
	$('#punto_inflamacion').numeric();
	$('#ph').numeric();
	$('#presentacion_cantidad').numeric();
	$('#ad_cantidad').numeric();


	//***************************** VISTA PREVIA REGISTRO***************************************
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



	//***************************** VISTA PREVIA DOSSIER***************************************
	$('button.btnVistaPreviaDossier').click(function (event) {

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

		form.attr('data-opcion', 'crearSolicitudDossier');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteDossier').hide();
		ejecutarJson(form,new exitoVistaPreviaDossier());


	});


	function exitoVistaPreviaDossier(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteDossier').show();
			$('#verReporteDossier').attr('href',msg.datos);
		};
	}


	//************************** NORMA APLICADA *****************************************************


	$('#normativa').change(function(){
		identificarNormativa($(this).val()=='NA');

	});

	function identificarNormativa(condicion){
		if(condicion==true)	{
			$('#verPreguntaClon').show();
			verRegistroClones($('input[name="es_clon"]:radio:checked').val()=="SI");
			llenarComboEnsayos(2);
			$('.verNormaNN').hide();
		}
		else{
			llenarComboEnsayos(1);
			$('#verPreguntaClon').hide();
			verRegistroClones(false);
			$('.verRegistroNoClon').show();
			identificarProducto();
			$('.verNormaNN').show();
		}
	}

	function llenarComboEnsayos(numeroInformes){
		var el=$('#protocolo');
		el.children('option').remove();
		el.append($("<option></option>").attr("value","").text("Seleccione...."));
		if(protocolosAprobados!=null){
			for(var i in protocolosAprobados){
				if(protocolosAprobados[i].numero_informes>=numeroInformes){
					el.append($("<option></option>").attr("value",protocolosAprobados[i].id_expediente).text(protocolosAprobados[i].plaguicida_nombre));
				}
			}
		}
	}



	$('input[name="es_clon"]').click(function(){
		verRegistroClones($(this).val()=="SI");
	});

	function verRegistroClones(condicion){
		if(condicion==true){
			$('.verRegistroClon').show();
			$('.verRegistroNoClon').hide();
			$('#tipo_declaracion_juramentada').html('Referencia de declaración juramentada para CLON');

			$('#protocolo').val('');
		}
		else{
			$('.verRegistroClon').hide();
			$('.verRegistroNoClon').show();
			$('#tipo_declaracion_juramentada').html('Referencia de declaración juramentada');
			$('#clon_registro_madre, #clon_nombre_madre, #clon_numero').val('');
		}

	}

	$('#protocolo').change(function(){
		identificarProducto();
		
		var txtNombre=$('#protocolo option:selected').text();
		$('#producto_nombre').val(txtNombre);
		ponerProductoFormulado();

		obtenerInformesFinales($(this).val());
	});

	function identificarProducto(){
		if(solicitud.producto_nombre==null || solicitud.producto_nombre.length==0)	{			
			var txtNombre=$('#protocolo option:selected').text();
			$('#producto_nombre').val(txtNombre);

			ponerProductoFormulado();
		}
	}


	$('#producto_nombre').change(function(){
		ponerProductoFormulado();
	});

	$('#concentracion').change(function(){
		ponerProductoFormulado();
	});


	function ponerProductoFormulado(){
		var txtProducto=$('#producto_nombre').val()+", "+$('#concentracion').val()+", "+ codigoFormulacion;
		$('#producto_formulado').html(txtProducto);
	}

	//********************************* INFORMES FINALES *******


	function  obtenerInformesFinales(id_expediente){
		var id_protocolo='';
		if(protocolosAprobados!=null){
			for(var i in protocolosAprobados){
				if(protocolosAprobados[i].id_expediente==id_expediente){
					id_protocolo=protocolosAprobados[i].id_protocolo;
					break;
				}
			}
		}
		if(id_protocolo=='')
			return;
		var param={opcion_llamada:'obtenerInformesFinales',id_protocolo:id_protocolo
		};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verDatosProtocolo);
	}

	function verDatosProtocolo(datos){
		if(datos!=null){
			verInformesFinales(datos.informes);
			verIA(datos.ia);
			verFormulacion(datos.formulacion);
			protocolo=datos.protocolo;
			$('#producto_uso').html(protocolo.uso_propuesto);
			//verifica si el ingrediente es paraquat
			if(datos.tieneParaquat){
				$('.ingrediente_paraquat').show();
			}
			else
				$('.ingrediente_paraquat').hide();
		}

	}

	function verInformesFinales(datos){
		var cboInforme=$('#id_informe_final');
		cboInforme.children('option').remove();
		cboInforme.append($("<option></option>").attr("value","").text("Seleccione...."));
		var ul=$('#ulInformesFinales');
		ul.children('li').remove();
		if(datos!=null){
			for(var i in datos){
				ul.append('<li>'+datos[i].id_expediente+'</li>');
				var htmlDosis=datos[i].id_expediente;
				if(datos[i].dosis!=null)
					htmlDosis=htmlDosis+': Dosis = '+datos[i].dosis+" "+datos[i].dosis_unidad;
				if(datos[i].dosis!=null)
					htmlDosis=htmlDosis+': Gasto de Agua = '+datos[i].gasto_agua+' l/ha';
				cboInforme.append($("<option></option>").attr("value",datos[i].id_expediente).text(htmlDosis));
			}
		}
		cargarValorDefecto('id_informe_final',solicitud.id_informe_final);
	}

	function verFormulacion(datos){
		if(datos!=null){
			var txtForm="";

			try{txtForm=datos.sigla;}catch(e){}
			codigoFormulacion=txtForm;

			$('#producto_formulacion').val(datos.formulacion);
		}
	}



	//********************************** INGREDIENTES ACTIVOS **********************************

	$(".frmAbrirIa").submit(function(event){
		event.preventDefault();
		abrir($(this),event,true);
	});

	function verIA(datos){
		var tbl=$('#tblIa tbody');
		tbl.children().remove();
		var txtIa="";
		var tieneParaquat=false;
		if(datos!=null){
			for(var i in datos){

				if(txtIa=="")
					txtIa=datos[i].ingrediente_activo;
				else
					txtIa=txtIa+" + "+datos[i].ingrediente_activo;
				var item=datos[i];

				var nuevaFila='<td>'+item.ingrediente_activo+'</td>';
				var tdVer='<form class="abrir frmAbrirIa" data-rutaAplicacion="dossierPlaguicida" data-opcion="abrirIa" data-destino="detalleItem" data-accionEnExito="NADA" >' +
								'<input type="hidden" id="id_solicitud" name="id_solicitud" value="' + solicitud.id_solicitud + '" />' +
								'<input type="hidden" id="normativa" name="normativa" value="' + $('#normativa').val() + '" />' +
								
								'<input type="hidden" id="id_protocolo" name="id_protocolo" value="' + item.id_protocolo + '" />' +
								'<input type="hidden" id="id_protocolo_ia" name="id_protocolo_ia" value="' + item.id_protocolo_ia + '" />' +
								'<input type="hidden" id="id_ingrediente_activo" name="id_ingrediente_activo" value="' + item.id_ingrediente_activo + '" />' +

								'<button type="submit" class="icono derecha verCampoObservar obsIngredienteActivo"></button>' +
							'</form>';
				nuevaFila+='<td>'+tdVer+'</td>';

				tbl.append('<tr>'+nuevaFila+'</tr>');
			}
		}
		$('#producto_ia').html(txtIa);
		
	}



	$("#clon_registro_madre").change(function(){
		actualizarDatosDelClon($(this).val(),false);

	});

	function actualizarDatosDelClon(numeroRegistro,esInicio){
		if(registroProductosMatriz!=null ){
			for(var i in registroProductosMatriz){

				if(registroProductosMatriz[i].numero_registro==numeroRegistro){
					$('#clon_nombre_madre').val(registroProductosMatriz[i].nombre_comun);
					var numClones=parseInt(registroProductosMatriz[i].clones);
					$('#clon_numero').val(numClones);

					borrarMensaje();
					switch(parseInt(registroProductosMatriz[i].id_categoria_toxicologica)){
						case 1:
						case 2:
							mostrarMensaje('El número máximo permitido de clones para éste producto se ha alcanzado','FALLO');
							$("#clon_registro_madre").val('');
							break;
						case 3:
							if(numClones>=2){
								mostrarMensaje('El número máximo permitido de clones para éste producto se ha alcanzado','FALLO');
								$("#clon_registro_madre").val('');
							}
							break;
						case 5:
							if(numClones>=4){
								mostrarMensaje('El número máximo permitido de clones para éste producto se ha alcanzado','FALLO');
								$("#clon_registro_madre").val('');
							}
							break;
					}

					if(esInicio){
						if(solicitud.producto_nombre==null || solicitud.producto_nombre.length==0)	{
							numClones++;
							$('#producto_nombre').val(registroProductosMatriz[i].nombre_comun+"-"+numClones);
							$('#producto_formulado').html(registroProductosMatriz[i].nombre_comun+"-"+numClones);
						}
					}
					else{
						numClones++;
						$('#producto_nombre').val(registroProductosMatriz[i].nombre_comun+"-"+numClones);
						$('#producto_formulado').html(registroProductosMatriz[i].nombre_comun+"-"+numClones);
					}
					
					//verifica si encontro datos en informes finales
					verIngredientesProductoMadre(numeroRegistro);
					break;
				}
			}
		}
	}

	//llena datos del producto madre en caso de clon.
	function verIngredientesProductoMadre(numeroRegistro){

		if(solicitud.es_clon=='t'){
			
			//verifica si no existen datos datos de ingredinetes de informes finales
			if($('#tblIa >tbody >tr').length == 0){

				if(numeroRegistro==null || numeroRegistro=='')
					return;

				var param={opcion_llamada:'obtenerDatosProductoMadre',
					id_solicitud:solicitud.id_solicitud,
					registro:numeroRegistro
				};
				
				llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,llenarDatosProductoMadre);
			}

		}
	}

	function llenarDatosProductoMadre(items){
		
		var ingredientes=items.ias;
		var txtIa="";
		var txtFormulacion='';
		for(var i in ingredientes){
			txtIa=', '+ingredientes[i].ingrediente_activo+' '+ingredientes[i].concentracion+' '+ingredientes[i].codigo;
			txtFormulacion=ingredientes[i].formulacion;
		}
		txtIa=txtIa.substring(2);
		
		$('#producto_ia').html(txtIa);
		$('#producto_formulacion').val(txtFormulacion);
		//datos del uso
		var usos=items.usos;
		var txtUso="";
		for(var i in usos){
			txtUso=txtUso+', '+usos[i].subtipo;
		}
		txtUso=txtUso.substring(2);
		$('#producto_uso').html(txtUso);

	}


	


	//*********************  FABRICANTES ************************************************************************
	var itemsManufacturador=[];

	

	function verFabricantes(items){
		
		txtFabricantes='';
		txtFabricantesForm="";
		arrFabricantes={};
		arrFabricantesMan={};
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];
				arrFabricantes[item.pais]=item.pais;

				
				if(item.manufacturadores!=null){
					for(var k in item.manufacturadores){
						var it=item.manufacturadores[k];
						arrFabricantesMan[it.pais]=it.pais;

				
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

	

	function verManufacturador(items){
		
		txtFabricantesForm="";
		var arrFabricantesMan={};
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];
				arrFabricantesMan[item.pais]=item.pais;

			
			}

			if(arrFabricantesMan!=null)
				txtFabricantesForm=Object.keys(arrFabricantesMan).join(', ');
		}
		
		agregarFabricantesPais();
	}

	

	function agregarFabricantesPais(){
		$('#producto_pais').html("FABRICANTES: "+txtFabricantes+" MANUFACTURADORES: "+txtFabricantesForm);
	}

	//******************************************* FORMULADORES **********************************************************




	function verFormuladores(items){
		
		txtFormuladores="";
		txtFormuladoresMan="";
		var arr={};
		var arrMan={};
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];
				arr[item.pais]=item.pais;

			
				if(item.manufacturadores!=null){
					for(var k in item.manufacturadores){
						var it=item.manufacturadores[k];
						arrMan[it.pais]=it.pais;

						
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

	

	function agregarFormuladoresPais(){
		$('#producto_pais_producto').html("FORMULADORES: "+txtFormuladores+" MANUFACTURADORES: "+txtFormuladoresMan);
	}

	



	//************************************************ MANUFACTURADOR ********************************************



	function f_verManufacturador(items){
		
		txtFormuladoresMan="";
		var arrMan={};
		if(!jQuery.isEmptyObject(items)){

			for(var i in items){
				var item=items[i];
				arrMan[tem.pais]=tem.pais;

			
			}
			if(arrMan!=null)
				txtFormuladoresMan=Object.keys(arrMan).join(', ');
		}
		
		agregarFormuladoresPais();
	}

	


	//******************************************* ANEXOS **********************************************************

	$('#tipoArchivo').change(function(){
		var medidaArchivo=$("#tipoArchivo option:selected").data('tamano');
		var tamano=parseInt(medidaArchivo);
		tamano=tamano*1024;
		$('#maxCapacidad').val(tamano);
		$('.estadoCarga').html("En espera de archivo... (Tamaño máximo "+medidaArchivo+"KB)");

	});

	$('button.subirArchivo').click(function (event) {
		event.preventDefault();
		if($('#tipoArchivo').val()==''){
			mostrarMensaje("Favor especifique el tipo de archivo", "FALLO");
			return;
		}
		var str=$("#referencia").val().trim();

		str=str.replace(/[^a-zA-Z0-9.]+/g,'');


		var nombre_archivo = solicitud.identificador+"_DG_"+solicitud.id_solicitud+"_"+str;

        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        var maximaCapacidad = boton.parent().find(".maxCapacidad").val();

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

			ejecutarJson($('#frmAnexos'), new exitoAnexo());


		};

		this.error = function (msg) {
			estado.html(msg);
			archivo.removeClass("amarillo");
			archivo.addClass("rojo");
		};
	}


	function exitoAnexo(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$("#referencia").val('');
			$(".archivo").val('');
			$(".rutaArchivo").val('');
			$(".archivo").attr("disabled", "disabled");
			$(".subirArchivo").attr("disabled", "disabled");

			verArchivosAnexos(msg.datos);

		};
	}

	function verArchivosAnexos(items){
		$('#tabla_archivos tbody > tr').remove();
		$('#tabla_archivos tbody').append(items);
	}

	$("#tabla_archivos").off("click",".btnBorraFilaArchivoAnexo").on("click",".btnBorraFilaArchivoAnexo",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borrarFilaArchivoAnexo',id_solicitud:solicitud.id_solicitud,id_solicitud_anexo:form.find("#id_solicitud_anexo").val()};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verArchivosAnexos);
	});

	$("#referencia").keyup(function(){
		if($(this).val().trim()!=""){
			$(".archivo").removeAttr("disabled");
			$("button.subirArchivo").removeAttr("disabled");
		}
		else{
			$(".archivo").attr("disabled", "disabled");
			$("button.subirArchivo").attr("disabled", "disabled");
		}
	});


	//**************************************** GUARDAR FORMULARIOS ****************************

	$('#btnGuardarPrimero').click(function(event){
		event.preventDefault();
		$("#estado").html("");

		var error = false;
		if(!esNoNuloEsteCampo("#normativa"))
			error = true;
		if(!esNoNuloEsteCampo("#motivo"))
			error = true;

		if($('#es_clonSI').is(":checked")){
			if(!esNoNuloEsteCampo("#clon_registro_madre"))
				error = true;
		}
		else{

			if(!esNoNuloEsteCampo("#protocolo"))
				error = true;
		}

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var form=$(this).parent();

		if(jQuery.isEmptyObject(solicitud)){
			//nuevo registro
			form.attr('data-opcion', 'guardarNuevaSolicitud');
			form.attr('data-destino', 'detalleItem');
			form.attr('data-accionEnExito', 'ACTUALIZAR');
			form.append("<input type='hidden' id='nivel' name='nivel' value='1' />"); // añade el nivel del formulario
			abrir(form, event, true); //Se ejecuta ajax, busqueda de sitios
		}
		else{
			//es actualización

			incrementarNivel(form,solicitud.nivel);
			form.attr('data-opcion', 'guardarSolicitudDossier');
			form.attr('data-destino', 'detalleItem');
			form.attr('data-accionEnExito', '');
			form.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario
			ejecutarJson(form);
			actualizaBotonSiguiente(form, nivelActual,solicitud.nivel);
		}


	});


	//************************************* GUARDADO DE LOS PASOS ***************************************

	$("#frmNuevaSolicitud2").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		var error = false;


		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud3").submit(function(event){

		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();
		var error = false;

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud4").submit(function(event){
		incrementarNivel($(this),solicitud.nivel);
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#producto_nombre"))
			error = true;
		
		if(!$("#acepar_solicitud").is(':checked')){
			mostrarMensaje("Favor aceptar las condiciones","FALLO");
			return;
		}

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

	$("#frmNuevaSolicitud5").submit(function(event){
		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		var error = false;

		if(!esNoNuloEsteCampo("#fabricante_certificado"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_certificado"))
			error = true;
		if(!esNoNuloEsteCampo("#formulador_acreditacion"))
			error = true;
		if(!esNoNuloEsteCampo("#informe_analisis"))
			error = true;
		if(!esNoNuloEsteCampo("#declaracion_juramentada"))
			error = true;
		if($('#normativa').val()=='NN'){
			if(!esNoNuloEsteCampo("#libre_venta"))
				error = true;
		}
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		if($('#es_clonSI').is(":checked")){
			solicitud.nivel=10;
			nivelActual=10;
		}



		elInferior.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson(elInferior);
			if($('#es_clonSI').is(":checked")){
				reconocerNivel(nivelActual);
			}
			else{
				actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
			}
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud6").submit(function(event){

		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();
		
		var error = false;

		if(!esNoNuloEsteCampo("#composicion_sustancias"))
			error = true;
		if(!esNoNuloEsteCampo("#composicion_sustancias_ref"))
			error = true;
		if(!esNoNuloEsteCampo("#composicion_naturaleza"))
			error = true;
		if(!esNoNuloEsteCampo("#composicion_naturaleza_ref"))
			error = true;
		if(!esNoNuloEsteCampo("#composicion_metodo"))
			error = true;
		if(!esNoNuloEsteCampo("#composicion_metodo_ref"))
			error = true;
		if(!esNoNuloEsteCampo("#estado_fisico"))
			error = true;
		if(!esNoNuloEsteCampo("#color"))
			error = true;
		if(!esNoNuloEsteCampo("#olor"))
			error = true;
		if(!esNoNuloEsteCampo("#estabilidad"))
			error = true;
		if(!esNoNuloEsteCampo("#densidad"))
			error = true;
		if($("#estado_fisico").val()=='EFC_LIQU'){
			if(!esNoNuloEsteCampo("#punto_inflamacion"))
				error = true;
			if(!esNoNuloEsteCampo("#inflamacion_adjunto"))
				error = true;
		}
		if(!esNoNuloEsteCampo("#ph"))
			error = true;
		if(!esNoNuloEsteCampo("#explosivo_referencia"))
			error = true;


		if($('input[name="es_explosivo"]:checked').val()===undefined){
			error = true;
		}

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		elInferior.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson(elInferior);
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud7").submit(function(event){

		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();
		borrarMensaje();

		var error = false;
		if($("#estado_fisico").val()=='EFC_SOLI'){
			if(!esNoNuloEsteCampo("#humedad"))
				error = true;
			if(!esNoNuloEsteCampo("#suspensibilidad"))
				error = true;
			if(!esNoNuloEsteCampo("#granulometria_humedo"))
				error = true;
			if(!esNoNuloEsteCampo("#granulometria_seco"))
				error = true;
			if(!esNoNuloEsteCampo("#dispersion"))
				error = true;
			if(!esNoNuloEsteCampo("#desprendimiento"))
				error = true;
			if(!esNoNuloEsteCampo("#soltura"))
				error = true;
		}
		if($("#estado_fisico").val()=='EFC_LIQU'){
			if(!esNoNuloEsteCampo("#viscosidad"))
				error = true;
		}
		if(!esNoNuloEsteCampo("#persistencia"))
			error = true;
		if(!esNoNuloEsteCampo("#estabilidad_emulsion"))
			error = true;
		if(!esNoNuloEsteCampo("#corrosivo_ref"))
			error = true;
		if(!esNoNuloEsteCampo("#incompatibilidad"))
			error = true;
		if(!esNoNuloEsteCampo("#reingreso"))
			error = true;
		if(!esNoNuloEsteCampo("#carencia"))
			error = true;
		if(!esNoNuloEsteCampo("#efectos_cultivos"))
			error = true;

		if($('input[name="es_corrosivo"]:checked').val()===undefined){
			error = true;
		}

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		elInferior.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson(elInferior);
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
		if(!esNoNuloEsteCampo("#precaucion_uso"))
			error = true;
		if(!esNoNuloEsteCampo("#almacenamieno_manejo"))
			error = true;
		if(!esNoNuloEsteCampo("#aux_ingestion"))
					error = true;
		if(!esNoNuloEsteCampo("#aux_telefono"))
					error = true;
		if(!esNoNuloEsteCampo("#aux_disposicion"))
					error = true;
		if(!esNoNuloEsteCampo("#aux_ambiente"))
					error = true;
		if(!esNoNuloEsteCampo("#aux_instrucciones"))
					error = true;
		if(!esNoNuloEsteCampo("#frecuencia_aplicacion"))
					error = true;
		if(!esNoNuloEsteCampo("#paraquat") && ($("#paraquat").is(":visible")))
			error = true;

		if($('input[name="tiene_hoja_informativa"]:checked').val()===undefined){
			error = true;
		}
		if($("#tiene_hoja_informativaSI").is(":checked")){
			if(!esNoNuloEsteCampo("#hoja_informativa"))
				error = true;
		}

		if(!esNoNuloEsteCampo("#envase_tipo"))
			error = true;
		if(!esNoNuloEsteCampo("#envase_material"))
			error = true;
		if(!esNoNuloEsteCampo("#envase_capacidad"))
			error = true;
		if(!esNoNuloEsteCampo("#envase_resistencia"))
			error = true;
		if(!esNoNuloEsteCampo("#embalaje_tipo"))
			error = true;
		if(!esNoNuloEsteCampo("#embalaje_material"))
			error = true;
		if(!esNoNuloEsteCampo("#embalaje_resistencia"))
			error = true;
		if(!esNoNuloEsteCampo("#accion_envases"))
			error = true;
		if(!esNoNuloEsteCampo("#destruccion_envaces"))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		elInferior.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson(elInferior);
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	

	$("#guardarPaso9").click(function(event){

		var elInferior=$('#frmNuevaSolicitud9');
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		var error = false;

		if(!esNoNuloEsteCampo("#sobra_destruccion"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_residuos"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_recuperacion"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_neutralizacion"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_incineracion"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_depuracion"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_precauciones"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_incendio"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_equipo"))
			error = true;
		if(!esNoNuloEsteCampo("#sobra_limpieza"))
			error = true;
		if(!esNoNuloEsteCampo("#residuo_obtenidos"))
			error = true;
		if(!esNoNuloEsteCampo("#residuo_hoja"))
			error = true;
		if(!esNoNuloEsteCampo("#residuo_evaluacion"))
			error = true;
		if($('input[name="tiene_aditivos_toxicos"]:checked').val()===undefined){
			error = true;
		}


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		elInferior.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson(elInferior);
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#frmNuevaSolicitud10").submit(function(event){
		var elInferior=$(this);
		incrementarNivel(elInferior,solicitud.nivel);
		event.preventDefault();

		var error = false;

		var filas=$('#tabla_archivos >tbody >tr').length;
		if(filas<=0){
			mostrarMensaje("Debe tener al menos un archivo adjunto","FALLO");
			return;
		}

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		elInferior.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){

			ejecutarJson(elInferior);
			actualizaBotonSiguiente(elInferior, nivelActual,solicitud.nivel);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$('#btnFinalizar').click(function (event) {
		event.preventDefault();

		if($("#boolAcepto").is(':checked')){
			borrarMensaje();
			var form=$(this).parent();
			var esClon='f';
			if($('#es_clonSI').is(":checked")){
				esClon='t';
			}
			form.append("<input type='hidden' id='es_clon' name='es_clon' value='"+esClon+"' />");

			mostrarMensaje('Generando documentación','');

			form.attr('data-destino', 'detalleItem');


			abrir(form, event, true); 
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
								'<button type="button" class="icono btnBorraFilaPresentacion obsPresentacion"></button>' +
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

	//************************** INFLAMABILIDAD **************************************************

	$('#punto_inflamacion').change(function(){
		verPictogramaInflamacion($(this).val());
	});

	function verPictogramaInflamacion(valor){
		var punto=Number(valor==''?'0':valor);
		$('#icon_inflamacion_tipo').removeClass();
		if(punto<21){
			$('#icon_inflamacion_tipo').addClass('punto-muy-inflamable');
			$('#inflamacion_tipo').html('Muy Inflamable');
		}
		else if(punto <=55){
			$('#icon_inflamacion_tipo').addClass('punto-inflamable');
			$('#inflamacion_tipo').html('Inflamable');
		}
		else{
			$('#inflamacion_tipo').html('');
		}
	}

	$('input[name="inflamacion_es_solido"]').click(function(){
		if($(this).val()=="SI"){
			$('#icon_inflamacion_tipo').addClass('solido-inflamable');
			$('#inflamacion_tipo').html('Inflamable');
		}
		else{
			$('#icon_inflamacion_tipo').removeClass();
			$('#inflamacion_tipo').html('');
		}

	});

	//****************************** EXPLOSIVIDAD *************
	$('input[name="es_explosivo"]').click(function(){
		if($(this).val()=="SI"){
			$('#es_explosivo_logo').addClass('es_explosivo_logo');
		}
		else{
			$('#es_explosivo_logo').removeClass();
		}

	});

	//******************************** CORROSIVO *********************

	$('input[name="es_corrosivo"]').click(function(){
		if($(this).val()=="SI"){
			$('#es_corrosivo_logo').addClass('es_corrosivo_logo');
		}
		else{
			$('#es_corrosivo_logo').removeClass();
		}

	});


	//************************* HOJA INFORMATIVA ************************
	$('input[name="tiene_hoja_informativa"]').click(function(){
		verHojaInformativa($(this).val()=="SI");


	});

	function verHojaInformativa(tieneHoja){
		if(tieneHoja){
			$('.verHojaInformativa').show();
		}
		else{
			$('.verHojaInformativa').hide();
		}
	}

	//******************** ADITIVOS TOXICOLOGICOS ****************

	$('input[name="tiene_aditivos_toxicos"]').click(function(){
		verAditivosToxicos($(this).val()=="SI");
	});

	function verAditivosToxicos(siTiene){
		if(siTiene){
			$('.verAditivosToxicos').show();
			distribuirLineas();
		}
		else
			$('.verAditivosToxicos').hide();
	}

	$('#btnAddAditivos').click(function(){

		var error = false;
		if(!esNoNuloEsteCampo("#ad_nombre"))
			error = true;
		if(!esNoNuloEsteCampo("#ad_cantidad"))
			error = true;
		if(!esNoNuloEsteCampo("#ad_unidad"))
			error = true;


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var param={opcion_llamada:'agregarAditivoToxicologico',id_solicitud:solicitud.id_solicitud,
			nombre:$('#ad_nombre').val(),
			cantidad:$('#ad_cantidad').val(),
			id_unidad:$('#ad_unidad').val()

		};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verAditivosToxicologicos);

	});

	function verAditivosToxicologicos(items){
		$('#tblAditivos tbody').html(items);
	}

	$("#tblAditivos").off("click",".btnBorrarAditivoToxicologico").on("click",".btnBorrarAditivoToxicologico",function(event){
		event.preventDefault();

		var form=$(this).parent();

		var param={opcion_llamada:'borrarAditivoToxicologico',id_solicitud:form.find("#id_solicitud").val(),id_solicitud_aditivo:form.find("#id_solicitud_aditivo").val()};
		llamarServidor('dossierPlaguicida','atenderLlamadaServidor',param,verAditivosToxicologicos);
	});


	//***************************** VISTA PREVIA CERTIFICADO***************************************
	$('button.btnVistaPreviaCertificado').click(function (event) {

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

		form.attr('data-opcion', 'crearCertificado');


		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteCertificado').hide();
		ejecutarJson(form,new exitoVistaPreviaCertificado());

	});


	function exitoVistaPreviaCertificado(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteCertificado').show();
			$('#verReporteCertificado').attr('href',msg.datos);
		};
	}

	//***************************** VISTA PREVIA ETIQUETA ***************************************
	$('button.btnVistaPreviaEtiqueta').click(function (event) {

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


		form.attr('data-opcion', 'crearPuntosEtiqueta');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteEtiqueta').hide();
		ejecutarJson(form,new exitoVistaPreviaEtiqueta());

	});


	function exitoVistaPreviaEtiqueta(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteEtiqueta').show();
			$('#verReporteEtiqueta').attr('href',msg.datos);
		};
	}

</script>

