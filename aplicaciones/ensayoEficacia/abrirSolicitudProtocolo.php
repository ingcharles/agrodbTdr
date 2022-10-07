<?php 
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorCatalogos.php';


	$id_flujo = $_POST['idFlujo'];
	$idUsuario= $_SESSION['usuario'];
	$idProtocolo = $_POST['id'];
	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();

	$datosGenerales=array();
	$tratamientos=array();
	for($i=1;$i<9;$i++){
		$tratamientos[strval($i)]=0;
      }
	//Busca el protocolo
	$esCultivoMenor=false;
	$zonaGeo=array();
	$registroPlaguicida=array();
	$ingredientesActivos=array();
	
	$plagasDelProtocolo=array();
	$plaguicidaReferencia=array();
	$coadyuvante=array();
	$arhivosProtocolo=array();
	$evaluacionesPlagas=array();
	

	if($idProtocolo!=null && $idProtocolo!='_nuevo'){

		$datosGenerales=$ce->obtenerProtocolo($conexion, $idProtocolo);
		$identificador=$datosGenerales['identificador'];		//El usuario actual
	
		if($datosGenerales['cultivo_menor']=='t')
			$esCultivoMenor=true;
		if($datosGenerales['motivo']!="MOT_REG"){
			$registroPlaguicida=$ce->obtenerProductoRegistrado($conexion, $datosGenerales['plaguicida_registro']);
			$ingredientesActivos=$ce->obtenerIaXregistro($conexion, $datosGenerales['plaguicida_registro']);
		}
		else{
			$ingredientesActivos=$ce->obtenerIngredientesActivos($conexion,$idProtocolo);
		}
		$arhivosProtocolo=$ce->listarArchivosAnexos($conexion,$idProtocolo);
	
		$plagasDelProtocolo=$ce->obtenerPlagasProtocolo($conexion,$idProtocolo);
		
		//obtiene las zonas declaradas
		$zonaGeo=$ce->obtenerProtocoloZonas($conexion,$idProtocolo);
		//verifica si usa plaguicida de referencia
		if($datosGenerales['pr_registro']!=null && $datosGenerales['pr_registro']!='0')
			$plaguicidaReferencia=$ce->obtenerProductoRegistrado($conexion, $datosGenerales['pr_registro']);
		//verifica si coadyuvante
		if($datosGenerales['cp_registro']!=null && $datosGenerales['cp_registro']!='0')
			$coadyuvante=$ce->obtenerProductoRegistrado($conexion, $datosGenerales['cp_registro']);
		//cargar tratamientos
		if($datosGenerales['tratamientos']==null || $datosGenerales['tratamientos']==0 || $datosGenerales['tratamientos']=='')
			$datosGenerales['tratamientos']=5;
		else{
			//verifico si ya hay guardado
			$tratamientos=$ce->obtenerTratamientos($conexion,$idProtocolo);			
		}
		//relleno los tratamientos con valores hasta 8
		for($i=1;$i<=8;$i++){
			$tratamientos[$i]=isset($tratamientos[$i]) ? $tratamientos[$i] : 0;
		}

		//lleno de plaga		
		$evaluacionesPlagas=$ce->obtenerEvaluacionesPlagas($conexion,$idProtocolo);
		
	}
	

	$res = $cr->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);

	
	$tecnicosReconocidos=$ce->obtenerTecnicosReconocidos($conexion);
	$ciTecnicosReconocidos=array();
	foreach ($tecnicosReconocidos as $key=>$item){
		$a=array();
		$a['value']=$item['identificador'];
		$a['label']='('.$item['identificador'].')'.$item['nombres'];
		$ciTecnicosReconocidos[]=$a;
	}	
	
	$listaProductosRegistradosDelOperador=$ce->obtenerProductosRegistrados($conexion,$identificador);
	$productosRegistradosDelOperador=array();
	foreach ($listaProductosRegistradosDelOperador as $key=>$item){
		$a=array();
		$a['value']=$item['numero_registro'];
		$a['label']='('.$item['numero_registro'].')'.$item['nombre_comun'];
		$productosRegistradosDelOperador[]=$a;
	}

	$cultivosMenores=$ce->obtenerProductosMenores($conexion);
	$cultivosNombres = $ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');	
	
	$items=$cc->listarLocalizacion($conexion,'PROVINCIAS');
	$catalogoProvincias=array();
	while ($fila = pg_fetch_assoc($items)){
		$catalogoProvincias[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
	}

	$items=$ce->obtenerSubTiposXcodigo($conexion,'RIA-%');
	$catalogoSubTipos=array();
	foreach ($items as $item){
		$subTipo=array();
		$subTipo['id_subtipo_producto']=$item['id_subtipo_producto'];
		$subTipo['codigo']=$item['codificacion_subtipo_producto'];
		$subTipo['nombre']=$item['nombre'];
		$catalogoSubTipos[]=$subTipo;
	}

	$catalogoPlagas=$ce->obtenerPlagas($conexion,'IAP','');
	
	$catalogoPlagasFungico=$ce->obtenerPlagas($conexion,'IAP','CF');

	$unidadesMedida = $ce->obtenerUnidadesMedida($conexion,null);
	$formulaciones=$ce->obtenerFormulaciones($conexion,'SI');
	$tipoAnexos=$ce->listarElementosCatalogoEx($conexion,'ANEXOS');

	

	$declaracionLegal=$ce->obtenerTitulo($conexion,'EP');

	//*********************ANEXOS ***********************************************
	$maxArchivoEE=2000;		//tamaño maximo de los archivos a subir en KB
	$paths=$ce->obtenerRutaAnexos($conexion,'ensayoEficacia');
	$pathAnexo=$paths['ruta'];		//Ruta para los documentos adjuntos
	
?>

<header>
	<h1>Solicitud de ensayo de eficacia</h1>
</header>

<div id="estado"></div>

<div class="pestania" id="P1" style="display: block;">
   <form id='frmNuevaSolicitud' data-rutaAplicacion='ensayoEficacia' data-opcion='guardarNuevaSolicitud'>
      <input type="hidden" id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>" />

      <fieldset>
         <legend>Información del solicitante</legend>

         <div data-linea="1">
            <label for="tipoRazon" class="opcional">Tipo razón social</label>
            <input value="<?php echo $operador['tipo_operador'];?>" name="tipoRazon" type="text" id="tipoRazon" placeholder="Tipo de razon social" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="2">
            <label for="razon" class="opcional">Razón social</label>
            <input value="<?php echo $operador['razon_social'];?>" name="razon" type="text" id="razon" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="3">
            <label for="ruc" class="opcional">CI/RUC/PASS</label>
            <input value="<?php echo $operador['identificador'];?>" name="ruc" type="text" id="ruc" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="4">
            <label for="direccion" class="opcional">Dirección</label>
            <input value="<?php echo $operador['direccion'];?>" name="direccion" type="text" id="direccion" placeholder="Direccion" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="provincia" class="opcional">Provincia</label>
            <input value="<?php echo $operador['provincia'];?>" name="provincia" type="text" id="provincia" placeholder="Provincia" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6">
            <label for="canton" class="opcional">Cantón</label>
            <input value="<?php echo $operador['canton'];?>" name="canton" type="text" id="canton" placeholder="Canton" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="7">
            <label for="parroquia" class="opcional">Parroquia</label>
            <input value="<?php echo $operador['parroquia'];?>" name="parroquia" type="text" id="parroquia" placeholder="Parroquia" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8">
            <label for="dirReferencia" class="opcional">Dirección de referencia</label>
            <input value="<?php echo $ce->chequearStringNulo( $datosGenerales['direccion_referencia']);?>" name="dirReferencia" type="text" id="dirReferencia" placeholder="Dirección de referencia" class="cuadroTextoCompleto" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>
         <div data-linea="9">
            <label for="telefono" class="opcional">Teléfono</label>
            <input value="<?php echo $operador['telefono_uno'];?>" name="telefono" type="text" id="telefono" placeholder="Telefono" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="10">
            <label for="celular" class="opcional">Celular</label>
            <input value="<?php echo $operador['celular_uno'];?>" name="celular" type="text" id="celular" placeholder="Celular" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="11">
            <label for="correo" class="opcional">Correo</label>
            <input value="<?php echo $operador['correo'];?>" name="correo" type="text" id="correo" placeholder="Correo" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="12">
            <label for="ciLegal" class="opcional">Cédula del representante legal</label>
            <input value="<?php echo $ce->chequearStringNulo( $datosGenerales['ci_representante_legal']);?>" name="ciLegal" type="text" id="ciLegal" placeholder="Cédula" maxlength="10" data-er="^[0-9]+$" required />
         </div>
         <div data-linea="13">
            <label for="nombreLegal">Nombres representante legal</label>
            <input value="<?php echo $operador['nombre_representante'];?>" name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="14">
            <label for="apellidoLegal">Apellidos representante legal</label>
            <input value="<?php echo $operador['apellido_representante'];?>" name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />
         </div>
         <div data-linea="15">
            <label for="correoLegal" class="opcional">Correo del representante legal</label>
            <input value="<?php echo $ce->chequearStringNulo( $datosGenerales['email_representante_legal']);?>" name="correoLegal" type="text" id="correoLegal" placeholder="Correo" class="cuadroTextoCompleto" maxlength="128" data-er="^[a-z0-9._%+-]+@[a-z0-9._-]+\.[a-z]{2,3}$" required />
         </div>

      </fieldset>

      <fieldset>
         <legend>Datos generales del ensayo</legend>
         <div data-linea="1">
            <label for="normativa">Normativa aplicada</label>
            <select name="normativa" id="normativa" required>
               <option value="">Seleccione....</option><?php
								$normativaLista = $ce->listarElementosCatalogo($conexion,'P1C30');
								foreach ($normativaLista as $key=>$item){
									if($item['codigo'] != 'NN'){
										if(strtoupper($item['codigo']) == strtoupper($datosGenerales['normativa'])){
											echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
										}else{
											echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
										}
									}
								}
                                                       ?>
            </select>
         </div>
         <div class="listasCirculo justificado">
            <label>Objetivo del ensayo : </label>
				<div>
					<ul><?php
									$items = $ce->listarElementosCatalogo($conexion,'P1C1');
									foreach ($items as $key=>$item){
										echo '<li>'.$sret=$item['nombre'].'</li>';
									}
						 ?>
					</ul>
				</div>
         </div>
         <div data-linea="2">
         </div>
         <div data-linea="3">
            <input type="hidden" id="varMotivo" name="varMotivo" value="" />
            <label for="motivo">Motivo del Ensayo</label>
            <select name="motivo" id="motivo" required>
               <option value="">Seleccione....</option><?php
								$items=$ce->listarElementosCatalogo($conexion,'P1C2');
								foreach ($items as $key=>$item){
									if(strtoupper($item['codigo']) == strtoupper($datosGenerales['motivo'])){
										echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
									}else{
										echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
									}
								}
							?>
            </select>
            <div id="cultivoMenor" style="display:none;">
               <label for="aplicaModalidad">Aplica modalidad de cultivos menores</label>
               SI<input type="radio" id="boolSI" name="boolModalidad" value="SI" <?php if($esCultivoMenor==true) echo "checked=true"?> />
               NO<input type="radio" id="boolNO" name="boolModalidad" value="NO" <?php if($esCultivoMenor==false) echo "checked=true"?> />

            </div>
         </div>
         <hr />
         <div data-linea="4">
            <label for="ciTecnico">No de cédula del técnico reconocido por ANC</label>
            <input value="<?php echo $datosGenerales['ci_tecnico_reconocido'];?>" name="ciTecnico" type="text" id="ciTecnico" placeholder="Cédula" maxlength="10" data-er="^[0-9]+$" required />

         </div>
         <div data-linea="5">
            <label for="nombreTecnico">Nombres del técnico reconocido por ANC</label>
            <input value="<?php echo $datosGenerales['tecnico_reconocido'];?>" name="nombreTecnico" type="text" id="nombreTecnico" placeholder="Nombres" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" disabled="disabled" />

         </div>
         <hr />
         <div data-linea="6">
            <label for="cultivoNomCien">Nombre científico del cultivo</label>
            <select name="cultivoNomCien" id="cultivoNomCien" required>
               <option value="">Seleccione....</option><?php
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
         <div data-linea="7">
            <label for="cultivoNomComun">Nombre común del cultivo</label>
            <select name="cultivoNomComun" id="cultivoNomComun" disabled="disabled">
               <option value="">Seleccione....</option><?php
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
         <div data-linea="8">
            <label for="subTipoProducto">Uso propuesto del producto</label>
            <select name="subTipoProducto" id="subTipoProducto" required>
               <option value="">Seleccione....</option><?php
								foreach ($catalogoSubTipos as $key=>$item){
									if(strtoupper($item['codigo']) == strtoupper($datosGenerales['uso'])){
										echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
									}else{
										echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
									}
								}
							?>
            </select>
            <div id="evaluarFungico" style="display:none;">
               <label for="evaluarFungico">Evaluará complejo fúngico? :</label>
               SI<input type="radio" id="boolFungicoSI" name="boolFungico" value="SI" <?php if($datosGenerales['complejo_fungico']=='t') echo "checked=true"?> />
               NO<input type="radio" id="boolFungicoNO" name="boolFungico" value="NO" <?php if($datosGenerales['complejo_fungico']=='f') echo "checked=true"?> />

            </div>
         </div>

      </fieldset>

      <button id="btnGuardarPrimero" type="button" class="guardar">Guardar solicitud</button>
     
   </form>
 
</div>

<div class="pestania" id="P2" style="display: block;">
	
	<fieldset>						
		<legend>Declaración de plagas</legend>
					
		<div data-linea="1">
			<label for="elegirPlagaDeclarada">Nombre científico : </label>
			<select name="elegirPlagaDeclarada" id="elegirPlagaDeclarada" class="obsPlagasDeclaradas">
				<option value="">Seleccione....</option>
				<?php 								
					$items = $catalogoPlagas;				
					foreach ($items as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' .$item['nombre']. '</option>';
					}
				?>
			</select>								
		</div>
		<div data-linea="2">
			<label for="elegirPlagaComun">Nombre común :</label>
			<select name="elegirPlagaComun" id="elegirPlagaComun"  disabled>
				<option value="">Seleccione....</option>
				<?php 								
					$items = $catalogoPlagas;				
					foreach ($items as $key=>$item){
						echo '<option value="' . $item['codigo'] . '">' .$item['nombre2']. '</option>';
					}
                ?>
			</select>								
		</div>
		<div class="justificado">
			<label>Plagas declaradas:</label>
			<label id="resultadoPlagasDeclaradas" ></label>													
		</div>
									
		<div data-linea="4" >
			<label id="observarPlagasDeclaradas"></label>
			<button id="btnAddPlaga" type="button" class="mas obsPlagasDeclaradas">Agregar</button>
									
		</div>
						
						
	</fieldset>
			
	<form id="frmRegistroPlagas" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarPlagasSolicitud" >
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
      
		<div id="tablaPlagas">
		
			
		</div>	
		<button type="submit" class="guardar">Guardar datos de las plagas</button>	
	</form>
</div>

<div class="pestania" id="P3" style="display: block;">
	<form id="frmRegistroExperimento" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarRegistroExperimento">
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
   					
		<fieldset>
			<legend>Ubicación geográfica y características agro ecológicas : Zona/Campaña 1</legend>	
         
			<div data-linea="1">
				<label for="ubicaAgoProvincia">Provincia:</label>
				<select name="ubicaAgoProvincia" id="ubicaAgoProvincia" required>	</select>
														
			</div>
			<div data-linea="2">
				<label for="ubicaAgoCanton">Cantón:</label>
				<select name="ubicaAgoCanton" id="ubicaAgoCanton"></select>								
			</div>
			<div data-linea="3">
				<label for="ubicaAgoParroquia">Parroquia:</label>
				<select name="ubicaAgoParroquia" id="ubicaAgoParroquia"></select>									
			</div>
         <div data-linea="4">
            <label id="zonaUbicaAgoProvincia"></label>
         </div>
         
		</fieldset>
		
		<div id="zonaGeo2">
			<fieldset>
				<legend>Ubicación geográfica y características agro ecológicas : Zona/Campaña 2</legend>	
           
				<div data-linea="1">
					<label for="ubicaAgoProvincia2">Provincia:</label>
					<select name="ubicaAgoProvincia2" id="ubicaAgoProvincia2"></select>
							
				</div>
				<div data-linea="2">
					<label for="ubicaAgoCanton2">Cantón:</label>
					<select name="ubicaAgoCanton2" id="ubicaAgoCanton2"></select>									
				</div>
				<div data-linea="3">
					<label for="ubicaAgoParroquia2">Parroquia:</label>
					<select name="ubicaAgoParroquia2" id="ubicaAgoParroquia2"></select>									
				</div>
            <div data-linea="4">
               <label id="zonaUbicaAgoProvincia2"></label>
            </div>
			</fieldset>	
		</div>	
						
							
      <fieldset>
         <legend>Diseño del experimento</legend>
         <div data-linea="1">
            <label for="condExperimento" style="text-align: left;">Condición del experimento</label>
            <select name="condExperimento" id="condExperimento" required>
               <option value="">Seleccione....</option>         <?php
         $items = $ce->listarElementosCatalogo($conexion,'P1C9');
         foreach ($items as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['condicion_experimento'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div data-linea="2">
            <label for="expTipoDis">Diseño del experimento:</label>
            <select name="expTipoDis" id="expTipoDis">         <?php
         $disExpCat = $ce->listarElementosCatalogo($conexion,'P1U1');
         foreach ($disExpCat as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['diseno_experimento'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
                                                               ?>
            </select>
         </div>
         <div data-linea="3" class="experimentoTipo" style="display:none;">
            <label for="expTipoOtro">Nombre del experimento:</label>
            <input value="<?php echo $datosGenerales['diseno_otro'];?>" name="expTipoOtro" type="text" id="expTipoOtro" placeholder="Nombre" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
         </div>
         <div class="experimentoTipo justificado" style="display:none;">
            <label for="expTipoOtroDes">Justificación para ser otro tipo de diseño del experimento:</label>
            <textarea name="expTipoOtroDes" id="expTipoOtroDes" placeholder="Justificacion" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$">
						<?php echo htmlspecialchars($datosGenerales['diseno_otro_text']); ?>
						
					</textarea>
         </div>

         
         <div data-linea="4" class="verTamanoParcela">
            <hr />
            <label style="font-weight: bold;text-decoration: underline">Tamaño de la parcela experimental</label>
         </div>

               <div data-linea="5" class="verTamanoParcela">
                  <label for="parcelaTotal">Área Total:  [m2] </label>
                  <input value="<?php echo $datosGenerales['parcela_total'];?>" name="parcelaTotal" type="text" id="parcelaTotal" placeholder="mayor a 300" maxlength="15" data-er="^[0-9]+$" required />
               </div>

               <div data-linea="6" class="verTamanoParcela">
                  <label for="parcelaUnidad">Área de la unidad:  [m2] </label>
                  <input value="<?php echo $datosGenerales['parcela_unidad'];?>" name="parcelaUnidad" type="text" id="parcelaUnidad" placeholder="mayor a 15" maxlength="15" data-er="^[0-9]+$" required />

               </div>

               <div data-linea="7" class="verTamanoParcela">
                  <label for="parcelaUtil">Área útil:  [m2] </label>
                  <input value="<?php echo $datosGenerales['parcela_util'];?>" name="parcelaUtil" type="text" id="parcelaUtil" placeholder="mayor a 10" maxlength="15" data-er="^[0-9]+$" required />
               </div>
           
         

         <hr />
         <div data-linea="10">
            <label for="noTratamientos">Número de tratamientos:</label>
            <input value="<?php echo $datosGenerales['tratamientos'];?>" name="noTratamientos" type="number" id="noTratamientos" class="obsNumeroTratamientos" placeholder="Al menos 5" min="2" max="8" data-er="^[0-9]+$" required />
         </div>
         <div data-linea="11" id="verRepeticiones">
            <label for="noRepeticiones">Número de repeticiones:</label>
            <input value="<?php echo $datosGenerales['repeticiones'];?>" name="noRepeticiones" type="text" id="noRepeticiones" placeholder="Recomendado 4" maxlength="15" data-er="^[0-9]+$" />
         </div>
         <div data-linea="12" id="verObservaciones">
            <label for="noObservaciones">Número de observaciones:</label>
            <input value="<?php echo $datosGenerales['observaciones'];?>" name="noObservaciones" type="text" id="noObservaciones" placeholder="valor" maxlength="15" data-er="^[0-9]+$" />
         </div>
         <div data-linea="13" >
            <label for="otraConsideracion">Otra información no considerada:</label>
            <textarea name="otraConsideracion" id="otraConsideracion" placeholder="Otra consideracion" data-distribuir="no" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$">	
					<?php echo htmlspecialchars($datosGenerales['experimento_otra_info']); ?>							
					
				</textarea>
         </div>
      </fieldset>

		<button type="submit" class="guardar">Guardar solicitud</button>
	</form>
</div>

<div class="pestania" id="P4" style="display: block;">
	
	<form id="frmComposicion" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarComposiciones">
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
      

      <fieldset>
         <legend>Plaguicida en prueba (bajo evaluación)</legend>

         <div data-linea="1">
            <label for="tipoPlaguicida" class="opcional">Tipo de plaguicida:</label>
            <input value="<?php echo $datosGenerales['plaguicida_tipo'];?>" name="tipoPlaguicida" type="text" id="tipoPlaguicida" placeholder="Tipo de plaguicida" disabled="disabled" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="2" id="verPlaguicidaComercial">
            <label for="noRegistro" class="opcional">Número de registro:</label>
            <input value="<?php echo $datosGenerales['plaguicida_registro'];?>" name="noRegistro" type="text" id="noRegistro" placeholder="Numero de registro" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="3">
            <label for="nombreProducto" class="opcional">Nombre del producto:</label>
            <input value="<?php echo $datosGenerales['plaguicida_nombre'];?>" name="nombreProducto" type="text" id="nombreProducto" placeholder="Nombre del producto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required />
         </div>


         <div class="plaguicidaExperimentalView">
            <hr />
         </div>


         <div data-linea="6" class="plaguicidaExperimentalView">
            <label for="iaNombre" >Ingrediente activo:</label>
            <select name="iaNombre" id="iaNombre" class="obsPlaguicidaExperimental"></select>

         </div>
         <div data-linea="7" class="plaguicidaExperimentalView">
            <label for="grupoQuimico" class="opcional">Grupo químico</label>
            <select name="grupoQuimico" id="grupoQuimico" disabled="disabled"></select>
         </div>
         <div data-linea="8" class="plaguicidaExperimentalView">
            <label for="iaConcentracion" class="opcional">Concentración:</label>
            <input value="" name="iaConcentracion" type="text" id="iaConcentracion" placeholder="Concentracion" class="obsPlaguicidaExperimental" maxlength="15" data-er="^[0-9]+$" />
         </div>
         <div data-linea="8" class="plaguicidaExperimentalView">
            <label for="iaUnidad" class="opcional">Unidad de medida:</label>
            <select name="iaUnidad" id="iaUnidad" class="obsPlaguicidaExperimental">
               <option value="">Seleccione....</option>         <?php
         foreach ($unidadesMedida as $key=>$item){
         if(strpos($item['clasificacion'],'DOSIS_IA')!==FALSE)
         echo '<option value="' . $item['id_unidad_medida'] . '">' . $item['nombre'] . '</option>';
         }
         ?>
            </select>
         </div>
         <hr />
         <div data-linea="9" class="plaguicidaExperimentalView">
            <label for="iaFormulacion">Tipo de formulación:</label>
            <select name="iaFormulacion" id="iaFormulacion">
               <option value="">Seleccione....</option>         <?php
         foreach ($formulaciones as $key=>$item){
         if(strtoupper($item['id_formulacion']) == strtoupper($datosGenerales['plaguicida_formulacion'])){
         echo '<option value="' . $item['id_formulacion'] . '" selected="selected">' . $item['formulacion'] . '</option>';
         }else{
         echo '<option value="' . $item['id_formulacion'] . '">' . $item['formulacion'] . '</option>';
         }
         }
         ?>
            </select>
         </div>

         <div data-linea="10" class="plaguicidaExperimentalView">
            <div>
               <button type="button" id="btnSaveIa" class="mas obsPlaguicidaExperimental">Agregar</button>
               <button type="button" id="btnClearIa" class="menos obsPlaguicidaExperimental">Borrar</button>
            </div>

         </div>

         <div data-linea="12">
            <label for="plagPruebaIa" class="opcional">Composición:</label>
            <input value="" name="plagPruebaIa" type="hidden" id="plagPruebaIa" class="cuadroTextoCompleto" disabled="disabled" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />

         </div>
         <div data-linea="13">
            <textarea name="listaIa" id="listaIa" maxlength="1024" readonly></textarea>
         </div>
         <div data-linea="14">
            <label for="plagPruebaQuimico" class="opcional">Grupo químico:</label>
            <input value="" name="plagPruebaQuimico" type="text" id="plagPruebaQuimico" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div class="plaguicidaExperimentalView">
            <hr />
         </div>
         <div data-linea="15" id="formuladorNombreExp">
            <label for="formuladorNombre" class="opcional">Formulador:</label>
            <input value="<?php echo $datosGenerales['plaguicida_formulador'];?>" name="formuladorNombre" type="text" id="formuladorNombre" placeholder="Nombre del formulador" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="16" class="plaguicidaComercialView">
            <label for="formuladorNombreCom" class="opcional">Formulador:</label>
            <select name="formuladorNombreCom" id="formuladorNombreCom"></select>
         </div>


         <div data-linea="17" id="formuladorPaisExp">
            <label for="formuladorPais" class="opcional">País de origen:</label>
            <select name="formuladorPais" id="formuladorPais">
               <option value="">Seleccione....</option>         <?php
         $items=$cc->listarLocalizacion($conexion,'PAIS');
         while ($item = pg_fetch_assoc($items)){
         if(strtoupper($item['id_localizacion']) == strtoupper($datosGenerales['plaguicida_pais_origen'])){
         echo '<option value="' . $item['id_localizacion'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['id_localizacion'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>

         </div>
         <div data-linea="18" class="plaguicidaComercialView">
            <label for="formuladorPaisCom" class="opcional">País de origen:</label>
            <input value="" name="formuladorPaisCom" type="text" id="formuladorPaisCom" class="cuadroTextoCompleto" disabled="disabled" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="19" id="formuladorLoteExp">
            <label for="formuladorLote" class="opcional">No. de lote:</label>
            <input value="<?php echo $datosGenerales['plaguicida_no_lote'];?>" name="formuladorLote" type="text" id="formuladorLote" placeholder="Numero de lote" maxlength="64" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="20" id="refImportacionMuestra" style="display: block;">
            <label for="docImportacionMuestra" class="opcional">Oficio de importacion de muestra:</label>
            <input value="<?php echo $datosGenerales['plaguicida_permiso_importacion'];?>" name="docImportacionMuestra" type="text" id="docImportacionMuestra" placeholder="Referencia para documento" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="21">
            <input type="hidden" id="plaguicida_modo_accion" name="plaguicida_modo_accion" value="<?php echo $datosGenerales['plaguicida_modo_accion'];?>" />
            <label for="modoAccion">Modo de Acción:</label>
				<?php
   $items = $ce->listarElementosCatalogo($conexion,'P1C14');
   foreach ($items as $key=>$item){
   echo '<input type="checkbox" name="modoAccion[]" value="'.$item['codigo'].'">'.$item['nombre'].'</input>';
   }
   ?>
         </div>
         <div data-linea="22">
            <label id="obs_plaguicida_modo_accion"></label>
         </div>
            <div data-linea="23" id="verOtroModoAccion" style="display: none;">
               <label for="otroModoAccion">Describa modo de acción:</label>
               <input value="<?php echo $datosGenerales['plaguicida_modo_accion_otro'];?>" name="otroModoAccion" type="text" id="otroModoAccion" placeholder="descripcion" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
            </div>
            <div data-linea="24">
               <label for="mecanismoAccion">Mecanismo de acción:</label>
               <textarea name="mecanismoAccion" id="mecanismoAccion" placeholder="Mecanismo de accion" maxlength="1024" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['plaguicida_mecanismo']); ?>
				</textarea>
            </div>
      </fieldset>
	         	
      <fieldset>
         <legend>Datos del plaguicida de referencia</legend>

         <div data-linea="1">
            <label for="tienePlaguicidaReferencia">Considera usar plaguicida de referencia:</label>
            SI<input type="radio" id="siTienePlaguicidaReferencia" name="tienePlaguicidaReferencia" value="t" <?php if($datosGenerales['pr_tiene']=='t') echo "checked=true"?> />
            NO<input type="radio" id="noTienePlaguicidaReferencia" name="tienePlaguicidaReferencia" value="f" <?php if($datosGenerales['pr_tiene']=='f') echo "checked=true"?> />
           
         </div>
         <div data-linea="2">           
            <label id="obsTienePlaguicidaReferencia"></label>
         </div>
         <div id="disRazonPlaguicidaReferencia" class="justificado" style="display: none;">
            <label for="razonPlaguicidaReferencia" >Indique las razones para no considerarlo:</label>
            <input value="<?php echo $ce->chequearStringNulo($datosGenerales['pr_tiene_razon']);?>" name="razonPlaguicidaReferencia" type="text" id="razonPlaguicidaReferencia" placeholder="indique porque no" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="4" class="seccionPlaguicidaReferencia">
            <label for="plagRefNoRegistro">No. de registro:</label>
            <input value="<?php echo $datosGenerales['pr_registro'];?>" name="plagRefNoRegistro" type="text" id="plagRefNoRegistro" placeholder="Número de registro" class="cuadroTextoCompleto" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5" class="seccionPlaguicidaReferencia">
            <label for="plagRefNombre" class="opcional">Nombre del producto:</label>
            <input value="" name="plagRefNombre" type="text" id="plagRefNombre" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>


         <div data-linea="6" class="seccionPlaguicidaReferencia">
            <label for="plagRefIa" class="opcional">Composición:</label>
            <input value="" name="plagRefIa" type="text" id="plagRefIa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />

         </div>
         <div data-linea="7" class="seccionPlaguicidaReferencia">
            <label for="plagRefQuimico" class="opcional">Grupo químico:</label>
            <input value="" name="plagRefQuimico" type="text" id="plagRefQuimico" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8" class="seccionPlaguicidaReferencia">
            <label for="plagRefFormulador" class="opcional">Formulador:</label>
            <select name="plagRefFormulador" id="plagRefFormulador"></select>

         </div>
         <div data-linea="9" class="seccionPlaguicidaReferencia">
            <label for="plagRefPais" class="opcional">País de origen:</label>
            <input value="" name="plagRefPais" type="text" id="plagRefPais" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="10" class="seccionPlaguicidaReferencia plaguicidaCoadyuvante">
            <label for="pr_dosis">Dosis:</label>
            <input value="<?php echo $datosGenerales['pr_dosis'];?>" name="pr_dosis" type="text" id="pr_dosis" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="13" class="seccionPlaguicidaReferencia">
            <input type="hidden" id="pr_modo_accion" name="pr_modo_accion" value="<?php echo $datosGenerales['pr_modo_accion'];?>" />
            <label for="plagRefModoAccion">Modo de acción:</label>      <?php
      $items = $ce->listarElementosCatalogo($conexion,'P1C14');
      foreach ($items as $key=>$item){
      echo '<input type="checkbox" name="plagRefModoAccion[]" value="'.$item['codigo'].'">'.$item['nombre'].'</input>';
      }
      ?>

         </div>
         <div data-linea="14" class="seccionPlaguicidaReferencia">
            <label id="obs_pr_modo_accion"></label>
         </div>
         <div data-linea="15" id="verPlagRefOtroModoAccion" class="seccionPlaguicidaReferencia" style="display: none;">
            <label for="plagRefOtroModoAccion" class="opcional">Descripción del modo de acción:</label>
            <input value="<?php echo $datosGenerales['pr_modo_accion_otro'];?>" name="plagRefOtroModoAccion" type="text" id="plagRefOtroModoAccion" placeholder="descripcion" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="16" class="seccionPlaguicidaReferencia">
            <label for="plagRefMecanismoAccion" class="opcional">Mecanismo de acción:</label>
            <textarea name="plagRefMecanismoAccion" id="plagRefMecanismoAccion" maxlength="1024" data-distribuir="no">
					<?php echo htmlspecialchars($datosGenerales['pr_mecanismo']); ?>
				</textarea>
         </div>

      </fieldset>
				
      <fieldset>
         <legend id="titleCoadyuvante">Datos del coadyuvante/producto</legend>

         <div data-linea="1">
            <label for="tienePlaguicidaCoadyuvante">Usará un coadyuvante/producto:</label>
            SI<input type="radio" id="siTienePlaguicidaCoadyuvante" name="tienePlaguicidaCoadyuvante" value="t" <?php if($datosGenerales['cp_tiene']=='t') echo "checked=true"?> />
            NO<input type="radio" id="noTienePlaguicidaCoadyuvante" name="tienePlaguicidaCoadyuvante" value="f" <?php if($datosGenerales['cp_tiene']=='f') echo "checked=false"?> />

         </div>
         <div data-linea="2">
            <label id="obsTienePlaguicidaCoadyuvante"></label>
         </div>
         <div data-linea="4" class="disPlaguicidaCoadyuvante">
            <label for="cyNoRegistro" class="opcional">No. de registro:</label>
            <input value="<?php echo $datosGenerales['cp_registro'];?>" name="cyNoRegistro" type="text" id="cyNoRegistro" placeholder="Número de registro" class="cuadroTextoCompleto" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5" class="disPlaguicidaCoadyuvante">
            <label for="cyNombre" class="opcional">Nombre del producto:</label>
            <input value="" name="cyNombre" type="text" id="cyNombre" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6" class="disPlaguicidaCoadyuvante">
            <label for="cyIa" class="opcional">Composición:</label>
            <input value="" name="cyIa" type="text" id="cyIa" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="7" class="disPlaguicidaCoadyuvante">
            <label for="cyQuimico" class="opcional">Grupo químico:</label>
            <input value="" name="cyQuimico" type="text" id="cyQuimico" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />

         </div>
         <div data-linea="8" class="disPlaguicidaCoadyuvante">
            <label for="cyDosis" class="opcional">Dosis:</label>
            <input value="" name="cyDosis" type="text" id="cyDosis" placeholder="" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8" class="disPlaguicidaCoadyuvante">
            <label for="cyDosisUnidad" class="opcional">Unidad:</label>
            <input value="" name="cyDosisUnidad" type="text" id="cyDosisUnidad" placeholder="" class="cuadroTextoCompleto" disabled="disabled" maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>


      </fieldset>
     
		<fieldset>
         <legend>Enlaces para consultar</legend>
         <div>
            <hr />
            <a href="http://www.agrocalidad.gob.ec/wp-content/uploads/2013/11/plaguicidas-registrados-19-05-2017.pdf" target="_blank">Base de datos de plaguicias registrados</a>
            <hr />
            <a href="https://guia.agrocalidad.gob.ec/agrodb/aplicaciones/publico/productos1/consultaRequisitoComercio.php" target="_blank">Consulta pública</a>
         </div>
      </fieldset>
	

		 <button type="submit" class="guardar">Guardar solicitud</button>
		 </form>
    
</div>

<div class="pestania" id="P5" style="display: block;">
	<form id="frmAplicacion" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarAplicaciones" data-destino="detalleItem">
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
      <input value="<?php echo $datosGenerales['tratamientos'];?>" name="tratamientos" type="hidden" id="tratamientos" required />								

      <fieldset>
         <legend>Modo de aplicación</legend>

         <div data-linea="1">
            <label for="tipoAplicacion" class="opcional">Tipo de aplicación</label>
            <select name="tipoAplicacion" id="tipoAplicacion">
               <option value="">Seleccione....</option><?php
         $items = $ce->listarElementosCatalogo($conexion,'P1C15');
         foreach ($items as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['tipo_aplicacion'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>

         <div data-linea="2" id="disTipoAplicacion" style="display: none;">
            <label for="tipoAplicacionOtro">Describa:</label>
            <input value="<?php echo $datosGenerales['tipo_aplicacion_otro'];?>" name="tipoAplicacionOtro" type="text" id="tipoAplicacionOtro" placeholder="Otro tipo de aplicacion" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <div data-linea="3">
            <label for="tipoEquipoUso" class="opcional">Tipo de equipo usado:</label>
            <select name="tipoEquipoUso" id="tipoEquipoUso">
               <option value="">Seleccione....</option><?php
         $items = $ce->listarElementosCatalogo($conexion,'P1C16');
         foreach ($items as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['equipo_usado'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div data-linea="4" id="disTipoEquipoUsoOtro" style="display: none;">
            <label for="tipoEquipoUsoOtro" class="opcional">Describa:</label>
            <input value="<?php echo $datosGenerales['equipo_usado_otro'];?>" name="tipoEquipoUsoOtro" type="text" id="tipoEquipoUsoOtro" placeholder="Otro tipo de equipo usado" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5">
            <label for="tipoBoquilla" class="opcional">Tipo de boquilla:</label>
            <input value="<?php echo $datosGenerales['tipo_boquilla'];?>" name="tipoBoquilla" type="text" id="tipoBoquilla" placeholder="Tipo de boquilla" class="cuadroTextoCompleto" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <hr />
         <label style="font-weight: bold;text-decoration: underline" >Momento de aplicación</label>


         <div data-linea="6">
            <label for="cantidadAplicacion" class="opcional">Número de aplicaciones</label>
            <select name="cantidadAplicacion" id="cantidadAplicacion">
               <option value="">Seleccione....</option><?php
         $items = $ce->listarElementosCatalogo($conexion,'P1C17');
         foreach ($items as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['momento_aplicacion'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>

         <div data-linea="7">
            <label for="aplicacionFenologia" class="opcional">Fenología del cultivo:</label>
            <input value="<?php echo $datosGenerales['aplicacion_fenologia'];?>" name="aplicacionFenologia" type="text" id="aplicacionFenologia" placeholder="Fenología del cultivo" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="8">
            <label for="aplicacionUmbral" class="opcional">Umbral económico de la plaga:</label>
            <input value="<?php echo $datosGenerales['aplicacion_umbral'];?>" name="aplicacionUmbral" type="text" id="aplicacionUmbral" placeholder="Umbral" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="9">
            <label for="aplicacionIntervalo" class="opcional">Intervalo de aplicación [días]:</label>
            <input value="<?php echo $datosGenerales['aplicacion_intervalo'];?>" name="aplicacionIntervalo" type="number" id="aplicacionIntervalo" min="0" max="9999999" data-er="^[0-9]+$" />
         </div>
         <hr />
         <div data-linea="10">
            <label for="tieneUnidadDosis">Usará unidad dosis estandarizada:</label>
            SI<input type="radio" id="siTieneUnidadDosis" name="tieneUnidadDosis" value="t" <?php if($datosGenerales['tiene_unidad_dosis']==true) echo "checked=true"?> />
            NO<input type="radio" id="noTieneUnidadDosis" name="tieneUnidadDosis" value="f" <?php if($datosGenerales['tiene_unidad_dosis']==false) echo "checked=true"?> />


         </div>
         <div data-linea="11" id="disUnidadDosis">
            <label for="unidadDosis" class="opcional">Unidades de la dosis:</label>
            <select name="unidadDosis" id="unidadDosis">
               <option value="">Seleccione....</option>         <?php
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
         <div data-linea="12" id="disUnidadDosisOtra" style="display: none;">
            <label for="unidadDosisOtra" class="opcional">Otra unidad:</label>
            <input value="<?php echo $datosGenerales['unidad_dosis_otro'];?>" name="unidadDosisOtra" type="text" id="unidadDosisOtra" placeholder="Otra unidad" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>

         <table id="tablaTratamientos">
            <thead>
               <tr>
                  <th>Tratamiento</th>
                  <th id="nombreDosis">Dosis</th>
               </tr>
            </thead>
            <tbody></tbody>
            <tr>
               <td>Tratamiento (T1)</td>
               <td><input value="<?php echo $tratamientos['1'];?>" name="tratamientoT1" type="text" id="tratamientoT1" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="valor" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
            <tr>
               <td>Tratamiento (T2)</td>
               <td><input value="<?php echo $tratamientos['2'];?>" name="tratamientoT2" type="text" id="tratamientoT2" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="valor" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
            <tr id="tratamientoT3view" style="display: none;">
               <td>Tratamiento (T3)</td>
               <td><input value="<?php echo $tratamientos['3'];?>" name="tratamientoT3" type="text" id="tratamientoT3" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="T2+(T2-T1)" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
            <tr id="tratamientoT4view" style="display: none;">
               <td>Tratamiento (T4)</td>
               <td><input value="<?php echo $tratamientos['4'];?>" name="tratamientoT4" type="text" id="tratamientoT4" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="valor" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
            <tr id="tratamientoT5view" style="display: none;">
               <td>Tratamiento (T5)</td>
               <td><input value="<?php echo $tratamientos['5'];?>" name="tratamientoT5" type="text" id="tratamientoT5" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="valor" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
            <tr id="tratamientoT6view" style="display: none;">
               <td>Tratamiento (T6)</td>
               <td><input value="<?php echo $tratamientos['6'];?>" name="tratamientoT6" type="text" id="tratamientoT6" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="valor" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
            <tr id="tratamientoT7view" style="display: none;">
               <td>Tratamiento (T7)</td>
               <td><input value="<?php echo $tratamientos['7'];?>" name="tratamientoT7" type="text" id="tratamientoT7" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="valor" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
            <tr id="tratamientoT8view" style="display: none;">
               <td>Tratamiento (T8)</td>
               <td><input value="<?php echo $tratamientos['8'];?>" name="tratamientoT8" type="text" id="tratamientoT8" class="elementoNumerico tresDecimales obsTratamientos obsNumeroTratamientos" placeholder="valor" maxlength="15" data-er="^[0-9]+$" /></td>
            </tr>
         </table>

         <div data-linea="13">
            <label id="dosisVolumen"></label>
         </div>
			<button type="button" id="btnTratamientos" class="guardar obsTratamientos obsNumeroTratamientos" hidden>Guardar tratamientos</button>

         <div data-linea="14">
            <label for="tratamientosInfo" class="opcional">Comentarios:</label>
            <textarea name="tratamientosInfo" id="tratamientosInfo" placeholder="sobre los tratamientos" maxlength="256" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
							<?php echo htmlspecialchars($datosGenerales['tratamientos_info']); ?>

						</textarea>
         </div>
      </fieldset>
		
		<fieldset>
			<legend>Condiciones para la aplicación</legend>
			
         <div class="listasTitulo justificado">
            <input type="hidden" id="equipo_proteccion" name="equipo_proteccion" value="<?php echo $datosGenerales['equipo_proteccion'];?>" />
            <label for="equipoProteccion" class="opcional">Equipo de protección:</label>   <?php
					$items = $ce->listarElementosCatalogo($conexion,'P1C20');
					$itemsLinea='';
					foreach ($items as $key=>$item){
					$itemsLinea=$itemsLinea. ', '.$item['nombre'] ;
					}
					$itemsLinea=substr($itemsLinea,2);
					echo $itemsLinea;
					?>

         </div>	
			<div data-linea="1"  >
				<label for="equipoProteccionOtro" class="opcional">Otro equipo de protección:</label> 						
				<input value="<?php echo $ce->chequearStringNulo($datosGenerales['equipo_proteccion_otro']);?>" name="equipoProteccionOtro" type="text" id="equipoProteccionOtro" placeholder="Otro equipo de protección"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />				
			</div>
			<div data-linea="2" id="disEstadioInsecto">
				<label for="estadioInsecto">Aplicación según estadío del insecto o ácaro:</label> 
				<select name="estadioInsecto" id="estadioInsecto">
					<option value="">Seleccione....</option>
					<?php 
						$items = $ce->listarElementosCatalogo($conexion,'P1C21');
						foreach ($items as $key=>$item){
							if(strtoupper($item['codigo']) == strtoupper($datosGenerales['estadio'])){
								echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
							}
						}
					?>
				</select>													
			</div>
			<div data-linea="3" id="disEstadioInsectoOtro" >
				<label for="estadioInsectoOtro" class="opcional">Otro estadío de aplicación:</label> 						
				<input value="<?php echo $ce->chequearStringNulo($datosGenerales['estadio_otro']);?>" name="estadioInsectoOtro" type="text" id="estadioInsectoOtro" placeholder="otro estadio"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />				
			</div>
			<div data-linea="4" id="disAplicarFungicida">
				<label for="aplicarFungicida">Aplicación del fungicida:</label> 
				<select name="aplicarFungicida" id="aplicarFungicida">
					<option value="">Seleccione....</option>
					<?php 
						$items = $ce->listarElementosCatalogo($conexion,'P1C22');
						foreach ($items as $key=>$item){
							if(strtoupper($item['codigo']) == strtoupper($datosGenerales['aplicacion_funguicida'])){
								echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
							}
						}
                    ?>
				</select>													
			</div>
			<hr/>
			<div data-linea="5" id="disAplicarHerbicida">
					<label for="aplicarHerbicida">Aplicación del herbicida:</label> 
					<select name="aplicarHerbicida" id="aplicarHerbicida">
						<option value="">Seleccione....</option>
						<?php 
							$items = $ce->listarElementosCatalogo($conexion,'P1C23');
							foreach ($items as $key=>$item){
								if(strtoupper($item['codigo']) == strtoupper($datosGenerales['aplicacion_herbicida'])){
									echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
								}else{
									echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
								}
							}
                        ?>
					</select>													
			
			</div>
						
			<hr/>
         <div data-linea="6">
            <label for="otraInformacion" class="opcional">Otra información no considerada:</label>
            <textarea name="otraInformacion" id="otraInformacion" maxlength="256" data-distribuir="no">
					<?php echo htmlspecialchars($datosGenerales['modo_aplicacion_info']); ?>
						
				</textarea>
         </div>
		</fieldset>

		<button type="submit" class="guardar">Guardar solicitud</button>	
	</form>
</div>

<div class="pestania" id="P6" style="display: block;">
	<form id="frmEvaluacion" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarEvaluaciones" data-destino="detalleItem">
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
      
		<fieldset>
			<legend>Datos meteorológicos del aire y suelo</legend>
					
			<div data-linea="1">
				<input type="hidden"  id="condicion_suelo" name="condicion_suelo" value="<?php echo $datosGenerales['condicion_suelo'];?>"/>
				<label for="condicionSuelo" class="opcional">Condiciones del suelo</label> 						
				<?php 
					$items = $ce->listarElementosCatalogo($conexion,'P1C24');
					foreach ($items as $key=>$item){
						echo '<input type="checkbox" name="condicionSuelo[]" value="'.$item['codigo'].'">'.$item['nombre'].'</input>';								
					}
                ?>
			</div>
			<div data-linea="2">
				<label id="obs_condicion_suelo"></label>
			</div>
			<div data-linea="3" id="disCondicionSueloOtro" style="display: none;" >
				<label for="condicionSueloOtro" class="opcional">Otra condición del suelo:</label> 						
				<input value="<?php echo $ce->chequearStringNulo($datosGenerales['condicion_suelo_otro']);?>" name="condicionSueloOtro" type="text" id="condicionSueloOtro" placeholder="Otra condicion del suelo"  maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />				
			</div>
			<div data-linea="4">
				<input type="hidden"  id="condicion_ambiental" name="condicion_ambiental" value="<?php echo $datosGenerales['condicion_ambiental'];?>"/>
				<label for="condicionAmbiental" class="opcional">Condiciones ambientales</label> 						
				<?php 
					$items = $ce->listarElementosCatalogo($conexion,'P1C25');
					if($items!=null){
						foreach ($items as $key=>$item){
							echo '<input type="checkbox" name="condicionAmbiental[]" value="'.$item['codigo'].'">'.$item['nombre'].'</input>';								
						}
					}
                ?>
			</div>
			<div data-linea="5">
				<label id="obs_condicion_ambiental"></label>
			</div>
			<div data-linea="6" id="disCondicionAmbientalOtro" style="display: none;" >
				<label for="condicionAmbientalOtro" class="opcional">Otra condición ambiental:</label> 						
				<input value="<?php echo $ce->chequearStringNulo($datosGenerales['condicion_ambiental_otro']);?>" name="condicionAmbientalOtro" type="text" id="condicionAmbientalOtro" placeholder="Otro condicion ambiental"   maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />				
			</div>				
		</fieldset>
				
		<fieldset>
			<legend>Método, momento y frecuencia de evaluación</legend>
					
			<div data-linea="1">
				<label for="mmEvaluacion" class="opcional">Unidad de muestreo</label> 						
				<select name="mmEvaluacion" id="mmEvaluacion">
					<option value="">Seleccione....</option>
					<?php 
						$items = $ce->listarElementosCatalogo($conexion,'P1C32');
						foreach ($items as $key=>$item){
							if(strtoupper($item['codigo']) == strtoupper($datosGenerales['muestreo_unidad'])){
								echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
							}else{
								echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
							}
						}
					?>
				</select>
			</div>
			<div data-linea="2" id="disMmEvaluacionOtro" >
				<label for="mmEvaluacionOtro" class="opcional">Otra unidad de muestreo:</label> 						
				<input value="<?php echo $ce->chequearStringNulo($datosGenerales['muestreo_unidad_otro']);?>" name="mmEvaluacionOtro" type="text" id="mmEvaluacionOtro" placeholder="otra unidad"   maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />				
			</div>
			<div data-linea="3"  >
				<label for="mmNumeroPlanta" class="opcional">Número de unidades de muestreo considerado por planta:</label> 						
				<input value="<?php echo $datosGenerales['muestreo_planta'];?>" name="mmNumeroPlanta" type="text" id="mmNumeroPlanta" placeholder="1" class="cuadroTextoCompleto"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />				
			</div>
			<div data-linea="4"  >
				<label for="mmNumeroUnidad" class="opcional">Número de unidades de muestreo por unidad experimental:</label> 						
				<input value="<?php echo $datosGenerales['muestreo_experimento'];?>" name="mmNumeroUnidad" type="text" id="mmNumeroUnidad" placeholder="1" class="cuadroTextoCompleto"  maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />				
			</div>									
		</fieldset>

      <fieldset>
         <legend>Evaluación de las plagas</legend>
       
         <div data-linea="2">
            <label for="tieneEscalaEvaluacion">Considerará escala de evaluación:</label>
            SI<input type="radio" id="tieneEscalaEvaluacionSi" name="tieneEscalaEvaluacion" value="t" <?php if($datosGenerales['plaga_eval_escala']=='t') echo "checked=true"?> />
            NO<input type="radio" id="tieneEscalaEvaluacionNo" name="tieneEscalaEvaluacion" value="f" <?php if($datosGenerales['plaga_eval_escala']=='f') echo "checked=true"?> />


         </div>
         <div data-linea="3">
            <label id="obs_plaga_eval_escala"></label>
         </div>

         <div data-linea="4" id="tieneEscalaEvaluacionView1">
            <label for="escalaEvaluacion" class="opcional">Escala de evaluación:</label>
            <input value="<?php echo $ce->chequearStringNulo($datosGenerales['plaga_eval_escala_ref']);?>" name="escalaEvaluacion" type="text" id="escalaEvaluacion" placeholder="Escala" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="5" id="tieneEscalaEvaluacionView2">
            <label for="escalaEvaluacionDis" class="opcional">Diseño de la escala:</label>
            <input value="<?php echo $ce->chequearStringNulo($datosGenerales['plaga_eval_escala_diseno']);?>" name="escalaEvaluacionDis" type="text" id="escalaEvaluacionDis" placeholder="Diseño" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="6">
            <label for="plaga_eval_escala_describir" class="opcional">Descripción de la escala (en caso de utilizarla):</label>
            <textarea name="plaga_eval_escala_describir" id="plaga_eval_escala_describir" placeholder="Describa la escala" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['plaga_eval_escala_describir']); ?>
							
				</textarea>
         </div>
         <hr />
         <div data-linea="8">
            <label for="varEvaluar" class="opcional">Variables para avaluar</label>
            <select name="varEvaluar" id="varEvaluar">
               <option value="">Seleccione....</option>         <?php
         $items = $ce->listarElementosCatalogo($conexion,'P1C27');
         foreach ($items as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['plaga_eval_variable'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div data-linea="9">
            <label for="evalEficacia" class="opcional">Eficacia</label>
            <select name="evalEficacia" id="evalEficacia">
               <option value="">Seleccione....</option>         <?php
         $items = $ce->listarElementosCatalogo($conexion,'P1C28');
         foreach ($items as $key=>$item){
         if(strtoupper($item['codigo']) == strtoupper($datosGenerales['plaga_eval_eficacia'])){
         echo '<option value="' . $item['codigo'] . '" selected="selected">' . $item['nombre'] . '</option>';
         }else{
         echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
         }
         }
         ?>
            </select>
         </div>
         <div data-linea="10" id="evalEficaciaOtraView" style="display: none;">
            <label for="evalEficaciaOtra" class="opcional">Que eficacia?:</label>
            <input value="<?php echo $ce->chequearStringNulo($datosGenerales['plaga_eval_eficacia_otro']);?>" name="evalEficaciaOtra" type="text" id="evalEficaciaOtra" placeholder="Describa" maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
         </div>
         <div data-linea="11">
            <label for="evalOtraInfo" class="opcional">Otra información no considerada:</label>
            <textarea name="evalOtraInfo" id="evalOtraInfo" placeholder="Otra información" maxlength="2048" data-distribuir="no" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo htmlspecialchars($datosGenerales['plaga_eval_info']); ?>
							
				</textarea>
         </div>

      </fieldset>

      <fieldset>
         <legend>Número de evaluaciones e intervalo de las mismas, expresado en días</legend>
			<div data-linea="1">
				<label for="plagaNoEvaluacion" class="opcional">Numero de evaluaciones:</label>
				<input value="<?php echo $datosGenerales['plaga_eval_numero'];?>" name="plagaNoEvaluacion" type="number" id="plagaNoEvaluacion" class="verEvaluaciones"  min="2" max="10" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>

            <table id="tablaEvaluaciones" class="evaluacionPlagas" >
               <thead>
                  <tr>
                     <th width="25%">Nombre</th>
                     <th width="10%">Intervalo</th>
                     <th width="65%">Observaciones</th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
				<div data-linea="4">
               <label id="obsEvaluaciones"></label>
            </div>
			<button type="button" id="btnEvaluaciones" class="guardar verEvaluaciones" hidden>Guardar evaluaciones</button>
        
		</fieldset>
		<button type="submit" class="guardar">Guardar solicitud</button>
	</form>
   
</div>

<div class="pestania" id="P7" style="display: block;">	

			<form id="frmAnexos" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarArchivoAnexo" >
				<input type="hidden" id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>">
            <input type="hidden" id="fase" name="fase" value="solicitud">
            
				<fieldset>
		   			<legend>Carga de archivos anexos</legend>
               
						<div data-linea="1">
							<label for="tipoArchivo" class="opcional">Tipo de archivo:</label> 
							<select name="tipoArchivo" id="tipoArchivo" class="obsArchivosAnexos" required>
								<option value="">Seleccione....</option>
								<?php 								
								foreach ($tipoAnexos as $key=>$item){
									echo '<option value="' . $item['codigo'] . '">' . $item['nombre'] . '</option>';
								}
                                ?>
							</select>
						</div>
					<hr/>
						<div class="justificado">
							<label for="referencia" class="opcional">Referencia para el documento:</label>
							<input value="" type="text" id="referencia" name="referencia" class="obsArchivosAnexos"  placeholder="ponga la referencia" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required/>
						</div>
                  <div data-linea="3">
                     <input type="hidden" class="rutaArchivo" name="rutaArchivo" value="0" />
                     <input type="hidden" class="maxCapacidad" value="<?php echo $maxArchivoEE*1024; ?>" />
                     <input type="file" class="archivo" accept="application/msword | application/pdf | image/*" disabled="disabled" />
                     
                     <div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo $maxArchivoEE.'K'; ?>B)</div>

                     <button type="submit" class="subirArchivo adjunto obsArchivosAnexos" data-rutaCarga="<?php echo $pathAnexo;?>" disabled="disabled">Subir archivo</button>
                  </div>
				
				</fieldset>
           
			</form>
	
         <fieldset>
            <legend>Archivos subidos</legend>
            <div data-linea="2">
               <input type="hidden" id="tabla_archivos_codigos" name="tabla_archivos_codigos" />
				</div>
            <table id="tabla_archivos" class="tabla">
               <thead>
                  <tr>
                     <th style="display:none">Codigo</th>
                     <th width="40%">Tipo Documento</th>
                     <th width="40%">Referencia</th>
                     <th width="18%"></th>
                  </tr>
               </thead>
               <tbody></tbody>

            </table>
            <div data-linea="4">
               <label id="obs_tabla_archivos"></label>
            </div>

         </fieldset>

   <form id="frmFinAnexos" data-rutaAplicacion="ensayoEficacia" data-opcion="guardarFaseArchivosAnexos">
      <input type="hidden" id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>">
      <button type="submit" class="guardar">Guardar solicitud</button>
   </form>
</div>

<div class="pestania" id="P8" style="display: block;">
	<form id="frmFinalizarProtocolo" data-rutaAplicacion="ensayoEficacia" data-opcion="finalizarProtocolo" data-accionEnExito = 'ACTUALIZAR'>
		<input type="hidden"  id="id_protocolo" name="id_protocolo" value="<?php echo $idProtocolo;?>"/>
      
      <input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
      
		<fieldset id="fieldCondiciones">
			<legend>Información y evaluaciones adicionales que se remitirá en el informe final</legend>
			<div data-linea="1" class="listasCirculo justificado" >					
				<input type="hidden"  id="plaga_eval_adicional" name="plaga_eval_adicional" value="<?php echo $datosGenerales['plaga_eval_adicional'];?>"/>
				<label  class="opcional"></label>
				<ul>
					<?php 
					$items = $ce->listarElementosCatalogo($conexion,'P1C29');
					foreach ($items as $key=>$item){
						echo '<li>' . $item['nombre'] . '</li>';
					}
                    ?>
				</ul>
			</div>
			</fieldset>
		
		<fieldset >
			<legend>Finalizar protocolo</legend>
			<div class="justificado">
				<label for="titulo">Título del ensayo:</label>
				
				<textarea hidden="hidden" name="titulo"  id="titulo"  maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">             
				</textarea>
				<br/>
				<label id="titulo1"></label> 
			</div>
			<hr/>

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
				<label for="boolAcepto">Acepto las condiciones</label> 
				<input type="checkbox" id="boolAcepto" name="boolAcepto" value="NO">
								
			</div>	
		</fieldset>
				
		<button id="btnFinalizar" type="button" class="guardar">Finalizar</button>
	</form>
	<form id='frmVistaPrevia' data-rutaAplicacion='ensayoEficacia' data-opcion='crearSolicitudProtocolo'>
      <button id="btnVistaPrevia" type="button" class="documento btnVistaPrevia">Generar vista previa</button>
		<a id="verReporte" href="" target="_blank" style="display:none">Ver archivo</a>
	</form>
</div>

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>

<script type="text/javascript">
	var protocolo=<?php echo json_encode($datosGenerales); ?>;
	var catalogoProvincias=<?php echo json_encode($catalogoProvincias); ?>;
	var tratamientosDatos=<?php echo json_encode($tratamientos); ?>;
	var catalogoSubTipos=<?php echo json_encode($catalogoSubTipos); ?>;

	var evaluacionesPlagas=<?php echo json_encode($evaluacionesPlagas);?>;
	var plagasDelProtocolo=<?php echo json_encode($plagasDelProtocolo); ?>;

	var formulaciones=<?php echo json_encode($formulaciones); ?>;

	var tecnicosReconocidos=<?php echo json_encode($ciTecnicosReconocidos); ?>;
	var productosRegistradosDelOperador=<?php echo json_encode($productosRegistradosDelOperador); ?>;


	var arhivosProtocolo=<?php echo json_encode($arhivosProtocolo); ?>;

	var esCultivoMenor=<?php echo json_encode($esCultivoMenor); ?>;
	var ingredientesActivos=<?php echo json_encode($ingredientesActivos); ?>;
	var registroPlaguicida=<?php echo json_encode($registroPlaguicida); ?>;

	var plaguicidaReferencia=<?php echo json_encode($plaguicidaReferencia); ?>;
	var coadyuvante=<?php echo json_encode($coadyuvante); ?>;

	var fabricantesPrueba={};
	var fabricantesReferencia={};

	var nivelActual='0';


	var vmotivo="MOT_REG";

	$("document").ready(function(){

		construirAnimacion(".pestania");

		distribuirLineas();

		//deshabilita el boton siguiente de desplamiento para obligar a guardar el documento
		$('.bsig').attr("disabled", "disabled");
		//habilita los botones según estado del documento

		try{
			reconocerNivel();

			llenarCatalogoUnidadesFormulacion();

			try{
				motivoConstruir();
			}catch(e){}


			try{
				llenarCatalogoSubTipos();
			}catch(e){}

			

			if(esCultivoMenor==true){
				ponerComboCultivos('SI');
			}
			else{
				ponerComboCultivos('NO');
			}
			$("#cultivoNomCien option[value="+ protocolo.cultivo +"]").attr("selected",true);
			$("#cultivoNomComun option[value="+ protocolo.cultivo +"]").attr("selected",true);

			

			try{
				actualizarSubtipoProducto(protocolo.uso);
			}catch(e){}

			try{
				valoresRecuperados();
			}catch(e){}
			
			llenarTablaIdenPlagaDatos(plagasDelProtocolo);
			
			

			try{
				habilitarPlagicidaCoadyuvante(protocolo.uso,protocolo.pr_tiene);
			}catch(e){}

			try{
				actualizarZonasGeo();
			}catch(e){}
			

			llenarTablaEvaluacionPlaga();

			try{
				verArchivosAnexos(arhivosProtocolo);
			}
			catch(e){}
			//pone titulo
			ponerTitulo();
		}catch(e){}

		
		construirValidador();

	});

	//funcion generales
	function valoresRecuperados(){
		//Recupera las condiciones experimentales

		try{
			verCondicionExperimento(protocolo.condicion_experimento);
			verTipoExperimento(protocolo.diseno_experimento);
		}catch(e){}

		//mira los tratamientos
		var noTratamientos=5;
		if(protocolo.tratamientos!=null && protocolo.tratamientos>=2 && protocolo.tratamientos<9){
			noTratamientos=protocolo.tratamientos;
		}

		//actualiza numero de tratamientos para guardar
		$("#noTratamientos").val(noTratamientos);
		$("#tratamientos").val(noTratamientos);
		mostrarItemsTratamientos(noTratamientos);

		//Carga datos para ingr. activos
		if(protocolo.motivo=='MOT_REG')
			actualizarComposicion(ingredientesActivos);


		//verifica los modos de accion
		mostrarCheckboxRecuperados(protocolo.plaguicida_modo_accion,"[name='modoAccion[]']","MAC_OTRO","#verOtroModoAccion");

		//Mira si tiene plaguicida de referencia
		try{
			if(protocolo.pr_tiene!=null && protocolo.pr_tiene=='t'){
				$(".seccionPlaguicidaReferencia").show();
				$('#disRazonPlaguicidaReferencia').hide();

			}
			else{
				$(".seccionPlaguicidaReferencia").hide();
				$('#disRazonPlaguicidaReferencia').show();
			}
		}catch(e){}


		//verifica datos del plaguicida de referencia
		if(protocolo.pr_registro!=null && protocolo.pr_registro.trim()!="" && protocolo.pr_registro.trim()!='0' &&  plaguicidaReferencia!=null && plaguicidaReferencia.producto!=null)
		{

			llenarPlagicidaReferencia(plaguicidaReferencia);
			//verifica los modos de accion del plaguicida de referencia
			mostrarCheckboxRecuperados(protocolo.pr_modo_accion,"[name='plagRefModoAccion[]']","MAC_OTRO","#verPlagRefOtroModoAccion");
			//selecciona el formulador
			if(protocolo.pr_formulador!=null && protocolo.pr_formulador!="" && protocolo.pr_formulador!='0'){

				$("#plagRefFormulador option[value="+ protocolo.pr_formulador +"]").attr("selected",true);
				$("#plagRefPais").val(fabricantesReferencia[protocolo.pr_formulador]);

			}
		}
		//verifica datos del coadyuvante
		//Mira si tiene plaguicida de referencia
		try{

			if(protocolo.cp_tiene!=null && protocolo.cp_tiene=='t')
				$(".disPlaguicidaCoadyuvante").show();
			else
				$(".disPlaguicidaCoadyuvante").hide();
		}catch(e){}

		if(protocolo.cp_registro!=null && protocolo.cp_registro.trim()!="" && protocolo.cp_registro.trim()!='0' && coadyuvante!=null && coadyuvante.producto!=null)
			llenarCy(coadyuvante);

		//actualiza los valores de los combos de Aplicacion
		verSeccionOtro("#tipoAplicacion","TAP_OTRO","#disTipoAplicacion");
		verSeccionOtro("#tipoEquipoUso","TEU_OTRO","#disTipoEquipoUsoOtro");


		mostrarUnidadDosis();

		//condiciones del suelo
		mostrarCheckboxRecuperados(protocolo.condicion_suelo,"[name='condicionSuelo[]']","CDS_OTRO","#disCondicionSueloOtro");
		mostrarCheckboxRecuperados(protocolo.condicion_ambiental,"[name='condicionAmbiental[]']","CAM_OTRO","#disCondicionAmbientalOtro");

		//evaluaciion de plagas
		verSeccionOtro("#evalEficacia","VEE_OTRO","#evalEficaciaOtraView");
		verSeccionOtro("#mmEvaluacion","UMC_OTRO","#disMmEvaluacionOtro");

		try{
			if(protocolo.plaga_eval_escala!=null && protocolo.plaga_eval_escala=='t')
				verEscalaEvaluacion(true);
			else
				verEscalaEvaluacion(false);
		}catch(e){}

	}

	function habilitarPlagicidaCoadyuvante(subTipoProducto,tienePlaguicidaReferencia){
		$(".plaguicidaCoadyuvante").hide();

		if(subTipoProducto=="RIA-COAD" ){

			if(tienePlaguicidaReferencia!=null && tienePlaguicidaReferencia=='t'){

				$(".plaguicidaCoadyuvante").show();
			}
		}
	}

	function ponerTitulo(){
		var str=[];
		str[0]='Evaluación de la eficacia del producto ';
		str[1]=$('#nombreProducto').val()+' (<i>'+$('#listaIa').html()+'</i>)';// 37 (40.2)
		str[2]=', como ';
		var subTipoProducto=$('#subTipoProducto').val();
		str[3]=$('#subTipoProducto :selected').text();	//21,
		if($("input:radio[name='tienePlaguicidaCoadyuvante']:checked").val()=="t"){
			if(subTipoProducto=="RIA-COAD"){
				str[4]=', en mezcla con el producto (<i>'+ $('#cyNombre').val()+'</i>)';
			}
		}
		if($('#resultadoPlagasDeclaradas').html().trim().length>0){
			str[5]=' para el control de ';
			str[6]=$('#resultadoPlagasDeclaradas').html();	// 23.2
		}
		str[7]=' en el cultivo de ';
		str[8]=$('#cultivoNomComun :selected').text()+' (<i>'+$('#cultivoNomCien :selected').text()+'</i>)';	// 20 (19)';

		$('#titulo').html(str.join(''));
		$('#titulo1').html(str.join(''));
	}

	//****************  Guardar las evaluaciones ******************

	$("#btnEvaluaciones").click(function (event) {
		event.preventDefault();

		borrarMensaje();

		var param={opcion_llamada:'guardarSubsanacionesEvaluaciones',	id_protocolo:protocolo.id_protocolo,plagaNoEvaluacion:$('#plagaNoEvaluacion').val()};

		$('#tablaEvaluaciones tbody tr').find("input:enabled").each(function(j) {

			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param[item.attr("id")]=item.val();
			}
		});

		mostrarMensaje('Generando solicitud...','');
		
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,resultadoGuardarEvaluacion);

		
	});

	function resultadoGuardarEvaluacion(items){
		borrarMensaje();
		if(items==null){
			mostrarMensaje("Error al guardar los datos de las evaluaciones","FALLO");
		}
		else{
			mostrarMensaje("Datos de las evaluaciones fueron guardados","EXITO");
		}
		
	}

	//**************************************************************


	$('#plagaNoEvaluacion').change(function(){
		var inum=$('#plagaNoEvaluacion').val();
		if(inum<2 || inum>10){
			$('#plagaNoEvaluacion').val('');
			$("#plagaNoEvaluacion").focus();
			$("#plagaNoEvaluacion").addClass("alertaCombo");
			mostrarMensaje('El numero de evaluaciones debe estar entre 2 y 10','FALLO');
		}
		else{
			$("#plagaNoEvaluacion").removeClass("alertaCombo");
			borrarMensaje();
			llenarTablaEvaluacionPlaga();
			}
	});

	function llenarTablaEvaluacionPlaga(){
		$('#tablaEvaluaciones tbody tr').each(function(){
			$(this).remove();
		});
		var numEval=$('#plagaNoEvaluacion').val();
		var str="";
		for(var i=0;i<numEval;i++){
			var nombre="Evaluación "+i;
			var intervalo=0;
			var observacion="";
			if(typeof(evaluacionesPlagas) != "undefined" && typeof(evaluacionesPlagas[i]) != "undefined"){
				nombre=evaluacionesPlagas[i].nombre;
				intervalo=evaluacionesPlagas[i].intervalo;
				observacion=normalizarString(evaluacionesPlagas[i].observacion);

			}
			var tret="";
			try{
				tret=tret+'<tr>';

				tret=tret+'<td><input class="verEvaluaciones" value="'+nombre+'" id="evalPlaga_nombre_'+i+'" name="evalPlaga_nombre_'+i+'" type="text"  maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/></td>';

				tret=tret+'<td><input class="campoNumerico verEvaluaciones" value="'+intervalo+'" id="evalPlaga_intervalo_'+i+'" name="evalPlaga_intervalo_'+i+'" type="number"  min="0" max="9999999" data-er="^[0-9]+$"/></td>';

				tret=tret+'<td><input class="verEvaluaciones" value="'+observacion+'" id="evalPlaga_observacion_'+i+'" name="evalPlaga_observacion_'+i+'" type="text"  maxlength="256" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/></td>';

				tret=tret+'</tr>';
			}catch(e){
				tret="";
			}
			str=str+tret;
		}

		$('#tablaEvaluaciones > tbody:last').append(str);
		//pone solo valores numericos en evaluaciones
		$('.campoNumerico').numeric();
	}

	//*********************************************************************
	$('#btnGuardarPrimero').click(function(event){
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#dirReferencia"))
			error = true;
		if(!esValidoEsteCampo("#ciLegal"))
			error = true;
		if(!esValidoEsteCampo("#correoLegal"))
			error = true;
		if(!esValidoEsteCampo("#normativa"))
			error = true;
		if(!esValidoEsteCampo("#motivo"))
			error = true;
		if(!esValidoEsteCampo("#ciTecnico"))
			error = true;
		if(!esValidoEsteCampo("#cultivoNomCien"))
			error = true;
		if(!esValidoEsteCampo("#subTipoProducto"))
			error = true;
		
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		var form=$(this).parent();

		if(jQuery.isEmptyObject(protocolo)){
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
			form.attr('data-opcion', 'guardarSolicitudProtocolo');
			form.attr('data-destino', 'detalleItem');
			form.attr('data-accionEnExito', '');
			form.append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario
			ejecutarJson(form);
			actualizaBotonSiguiente(form, nivelActual,solicitud.nivel);
		}


	});
	//*********************************************************************
	
	function actualizarZonasGeo(){
		var zonaGeo=<?php echo json_encode($zonaGeo); ?>;

		for(var i in zonaGeo){
			var zona=zonaGeo[i];
			if(zona==null)
				continue;
			if(zona.provincia <1)
				continue;
			var pos=zona.zona.substring(1);
			var elemento='#ubicaAgoProvincia';
			if(pos=='2')
				elemento=elemento+pos;
			//pongo valor recuprado
			$(elemento+" option[value="+ zona.provincia +"]").attr("selected",true);
			//carga el combo acorde a la provincia
			var canton='#ubicaAgoCanton';
			if(pos=='2')
				canton=canton+pos;

			try{
				obtenerLocalidades(zona.provincia,canton,'CANTONES');
			}catch(e){}

			try{

				if(zona.canton>0){

					//pongo valor recuperado
					$(canton+" option[value="+ zona.canton +"]").attr("selected",true);
					//carga las parroquias acorde el canton
					var parroquia='#ubicaAgoParroquia';
					if(pos=='2')
						parroquia=parroquia+pos;
					obtenerLocalidades(zona.canton,parroquia,'PARROQUIAS');
					if(zona.parroquia>0){
						//pongo valor recuperado de la parroquia
						$(parroquia+" option[value="+ zona.parroquia +"]").attr("selected",true);
					}

				}
			}catch(e){}
		}
	}

	//funciones del nivel del protocolo por botones de seccion
	function reconocerNivel(){
		$('.pestania').each(function (){
			var v=$(this).attr('id');
			var pos=parseInt(v.substring(1));
			var nivel=parseInt(protocolo.nivel);
			if(pos<=nivel){
				$(this).find('.navegacionPestanias .bsig').removeAttr('disabled');
			}

		});
	}

	function actualizaBotonSiguiente(elemento,nivelGuardado){
		var v=elemento.parent().attr('id');
		var pos=parseInt(v.substring(1));
		var nivel=parseInt(nivelGuardado);
		if(pos>=nivel){
			protocolo.nivel=pos.toString();
			reconocerNivel();
		}

	}


	//********************************************************* CAMPOS NUMERICOS  **********************
	$('#ciLegal').numeric();
	$('#parcelaTotal').numeric();
	$('#parcelaUnidad').numeric();
	$('#parcelaUtil').numeric();

	$('#parcelaTotal').change(verificarValorMinimo);
	$('#parcelaUnidad').change(verificarValorMinimo);
	$('#parcelaUtil').change(verificarValorMinimo);

	$('#iaConcentracion').numeric();
	$('#aplicacionIntervalo').numeric();

	$('#plagaNoEvaluacion').numeric();
	$('#plagaIntervalo').numeric();

	$('.elementoNumerico').numeric();

	$('.tresDecimales').blur(function() {
		var amt = parseFloat(this.value);
		$(this).val(amt.toFixed(3));
	});




	//*********************** SUBMITS*********

	function incrementarNivel(elemento){
		var v=elemento.parent().attr('id');
		var pos=parseInt(v.substring(1));
		var nivel=1+parseInt(protocolo.nivel);
		if(pos<nivel)
			nivelActual=protocolo.nivel;
		else
			nivelActual=pos;
	}

	$("#frmNuevaSolicitud").submit(function(event){
		incrementarNivel($(this));
		event.preventDefault();

		var error = false;

		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});


	$("#frmRegistroPlagas").submit(function(event){
		incrementarNivel($(this));
		event.preventDefault();

		var error = false;
		//verifica si hay por lo menos una plaga
		var sprod=$("#subTipoProducto").val();
		if(sprod!="RIA-RC")
		{
			var nFilas = $("#tablaPlagas").children('form').length;
			if(nFilas==0){
				mostrarMensaje('Debe declarar al menos una plaga','FALLO');
				error=true;
			}
		}

		if (!error){
			borrarMensaje();
			$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual);
		}else{
			mostrarMensaje('Debe declarar al menos una plaga','FALLO');
		}
	});

	$("#frmRegistroExperimento").submit(function(event){
		incrementarNivel($(this));
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#ubicaAgoProvincia"))
			error = true;
		if(($("#normativa").val()=='NA') && (vmotivo=="MOT_REG")){
			if(!esNoNuloEsteCampo("#ubicaAgoProvincia2"))
				error = true;
		}
		if(!esNoNuloEsteCampo("#condExperimento"))
			error = true;
		if(!esNoNuloEsteCampo("#expTipoDis"))
			error = true;
		if($('#expTipoDis').val()=='DEX_OTRO'){
			if(!esNoNuloEsteCampo("#expTipoOtro"))
				error = true;
		}

		if(!esNoNuloEsteCampo("#noTratamientos"))
			error = true;
		
		if($('#expTipoDis').val()=='DEX_DCA'){
			if(!esNoNuloEsteCampo("#noObservaciones"))
				error = true;
		}
		else{
			if(!esNoNuloEsteCampo("#parcelaTotal"))
				error = true;
			if(!esNoNuloEsteCampo("#parcelaUnidad"))
				error = true;
			if(!esNoNuloEsteCampo("#parcelaUtil"))
				error = true;
			if(!esNoNuloEsteCampo("#noRepeticiones"))
				error = true;

		}
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();


		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}


	});


	$("#frmComposicion").submit(function(event){
		incrementarNivel($(this));
		event.preventDefault();

		var error = false;

		if(!esNoNuloEsteCampo("#nombreProducto"))
			error = true;
		if ($("#iaFormulacion").is(":visible")) {
            if (!esNoNuloEsteCampo("#iaFormulacion")) {
                error = true;
            }
        }


		if($('#noTienePlaguicidaReferencia').is(":checked")){
			if(!esNoNuloEsteCampo("#razonPlaguicidaReferencia"))
				error = true;
		}


		if(vmotivo=="MOT_REG"){				//Plaguicida experimental

		}
		else{
			if(!esNoNuloEsteCampo("#noRegistro"))
				error = true;
			if(!esNoNuloEsteCampo("#plaguicida_modo_accion"))
				error = true;
			if($("#plaguicida_modo_accion").val().indexOf("MAC_OTRO")!=-1){
				if(!esNoNuloEsteCampo("#otroModoAccion"))
					error = true;
			}
		}


		if(!esNoNuloEsteCampo("#mecanismoAccion"))
			error = true;
		if($('#siTienePlaguicidaReferencia').is(":checked")){

			if(!esNoNuloEsteCampo("#plagRefNoRegistro"))
				error = true;

			if(!esNoNuloEsteCampo("#pr_modo_accion"))
				error = true;
			if($("#pr_modo_accion").val().indexOf("MAC_OTRO")!=-1){
				if(!esNoNuloEsteCampo("#plagRefOtroModoAccion"))
					error = true;
			}
			if(!esNoNuloEsteCampo("#plagRefMecanismoAccion"))
				error = true;
		}

		if($('#siTienePlaguicidaCoadyuvante').is(":checked")){
			if(!esNoNuloEsteCampo("#cyNoRegistro"))
				error = true;
		}


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();

		$("#tipoPlaguicida").removeAttr('disabled');
		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		$("#tipoPlaguicida").attr("disabled","disabled");

	});


	$("#frmAplicacion").submit(function(event){
		incrementarNivel($(this));
		event.preventDefault();

		var error = false;
		if(!esNoNuloEsteCampo("#tipoAplicacion"))
			error = true;
		if($('#tipoAplicacion').val()=='TAP_OTRO'){
			if(!esNoNuloEsteCampo("#tipoAplicacionOtro"))
				error = true;
		}
		if(!esNoNuloEsteCampo("#tipoEquipoUso"))
			error = true;
		if($('#tipoEquipoUso').val()=='TEU_OTRO'){
			if(!esNoNuloEsteCampo("#tipoEquipoUsoOtro"))
				error = true;
		}

		if(!esNoNuloEsteCampo("#tipoBoquilla"))
				error = true;
		if(!esNoNuloEsteCampo("#cantidadAplicacion"))
			error = true;
		if(!esNoNuloEsteCampo("#aplicacionUmbral"))
			error = true;

		if(!esNoNuloEsteCampo("#aplicacionFenologia"))
			error = true;
		if(!esNoNuloEsteCampo("#aplicacionIntervalo"))
			error = true;
		if($('#siTieneUnidadDosis').is(":checked")){
			if(!esNoNuloEsteCampo("#unidadDosis"))
				error = true;
		}
		else{
			if(!esNoNuloEsteCampo("#unidadDosisOtra"))
				error = true;
		}

		var sprod=$("#subTipoProducto").val();
		if(sprod=="RIA-I" || sprod=="RIA-A"){
			if(!esNoNuloEsteCampo("#estadioInsecto"))
				error = true;
		}
		if(sprod=="RIA-F"){
			if(!esNoNuloEsteCampo("#aplicarFungicida"))
				error = true;
		}
		if(sprod=="RIA-H"){
			if(!esNoNuloEsteCampo("#aplicarHerbicida"))
				error = true;
		}


		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();


		$("#tipoPlaguicida").removeAttr('disabled');
		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		$("#tipoPlaguicida").attr("disabled","disabled");

	});

	$("#frmEvaluacion").submit(function(event){
		incrementarNivel($(this));
		event.preventDefault();


		var error = false;
		if(!esNoNuloEsteCampo("#condicion_suelo"))
			error = true;
		if(!esNoNuloEsteCampo("#condicion_ambiental"))
			error = true;
		if(!esNoNuloEsteCampo("#mmEvaluacion"))
			error = true;
		if(!esNoNuloEsteCampo("#mmNumeroPlanta"))
			error = true;
		if(!esNoNuloEsteCampo("#mmNumeroUnidad"))
			error = true;
		if(!esNoNuloEsteCampo("#plagaNoEvaluacion"))
			error = true;
		if($('#tieneEscalaEvaluacionSi').is(":checked")){
			if(!esNoNuloEsteCampo("#escalaEvaluacionDis"))
				error = true;
			if(!esNoNuloEsteCampo("#escalaEvaluacion"))
				error = true;
		}
		if(!esNoNuloEsteCampo("#varEvaluar"))
			error = true;
		if(!esNoNuloEsteCampo("#evalEficacia"))
			error = true;
		if($('#evalEficacia').val()=='VEE_OTRO'){
		 	if(!esNoNuloEsteCampo("#evalEficaciaOtra"))
				error = true;
		}

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();


		$("#tipoPlaguicida").removeAttr('disabled');
		$(this).append("<input type='hidden' id='nivel' name='nivel' value='"+nivelActual+"' />"); // añade el nivel del formulario

		if (!error){
			ejecutarJson($(this));
			actualizaBotonSiguiente($(this), nivelActual);
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		$("#tipoPlaguicida").attr("disabled","disabled");

	});


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
		var strCodigos='';
		for(var i in items){
				var item=items[i];
				var fila='<tr class="modo2">'+
				'<td style="display:none">'+item.codigo+'</td>'+
				'<td>'+item.nombre+'</td>'+
				'<td><a href="'+ item.path+'" target="_blank">'+item.referencia+'</a></td>'+
				'<td>' +
					'<form id="borrarAnexoEE" class="borrar" data-rutaAplicacion="ensayoEficacia" data-opcion="eliminarArchivoAnexo"  >' +
						'<input type="hidden" id="archivo" name="archivo" value="' + item.path + '" >' +
						'<input type="hidden" id="id_protocolo" name="id_protocolo" value="' + item.id_protocolo + '" >' +
						'<button type="button" class="icono btnBorraFilaArchivoAnexo obsArchivosAnexos"></button>' +
					'</form>' +
				'</td>'+
				'</tr>';

				$('#tabla_archivos tbody').append(fila);
				strCodigos=strCodigos+','+item.codigo;
		}
		$('#tabla_archivos_codigos').val(strCodigos);
	}

	$("#tabla_archivos").off("click",".btnBorraFilaArchivoAnexo").on("click",".btnBorraFilaArchivoAnexo",function(event){
		event.preventDefault();
		var form=$(this).parent();
		var param={opcion_llamada:'borraFilaArchivoAnexo',id_protocolo:form.find("#id_protocolo").val(),archivo:form.find("#archivo").val()};
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,verArchivosAnexos);
	});


	$("#frmFinAnexos").submit(function(event){

		incrementarNivel($(this));
		event.preventDefault();

		var error = false;
		var strCodigos=$('#tabla_archivos_codigos').val();

		var nFilas =$("#tabla_archivos tbody>tr").length;
		if(nFilas==0){
			mostrarMensaje('Debe adjuntar al menos un archivo','FALLO');
			return;
		}

		//La ficha técnica es requerida
		var tieneFicha=false;
		$('#tabla_archivos tbody>tr').each(function() {
			var idCodigo = $(this).find("td:first").html();
			
			if(idCodigo=="A_FICHA")
				tieneFicha=true;
		});

		if(!tieneFicha){
			mostrarMensaje('Adjunto de la ficha técnica es requerido, favor subir al sistema','FALLO');
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
			actualizaBotonSiguiente($(this), nivelActual);

		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}

	});


	$('#btnFinalizar').click(function (event) {
		event.preventDefault();

		if($("#boolAcepto").is(':checked')){
			borrarMensaje();
			var form=$(this).parent();

			var esCultivoMenor='f';
			if($("[name='boolModalidad']:checked").val()=='SI'){
				esCultivoMenor='t';
			}

			form.append('<input type="hidden"  id="cultivo_menor" name="cultivo_menor" value="'+esCultivoMenor+'"/>');
			form.append("<input type='hidden' id='motivo' name='motivo' value='"+$("#motivo").val()+"' />");
			form.append("<input type='hidden' id='tituloPrevio' name='tituloPrevio' value='"+$('#titulo').html()+"' />");

			form.attr('data-destino', 'detalleItem');

			mostrarMensaje('Generando documentación','');
			abrir(form, event, true);
		}
		else
			mostrarMensaje('Para finalizar acepte las condiciones','FALLO');
	});


	$('button.btnVistaPrevia').click(function (event) {

		event.preventDefault();
		var form=$(this).parent();
		form.append("<input type='hidden' id='id_protocolo' name='id_protocolo' value='"+protocolo.id_protocolo+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='tituloPrevio' name='tituloPrevio' value='"+$('#titulo').html()+"' />");
		form.append("<input type='hidden' id='esDocumentoLegal' name='esDocumentoLegal' value='NO' />");
		
		form.attr('data-opcion', 'crearSolicitudProtocolo');

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



	//*********************************************** ANEXOS ***********************

	$('button.subirArchivo').click(function (event) {
		event.preventDefault();


		if($('#tipoArchivo').val()==''){
			mostrarMensaje("Seleccione el tipo de archivo","FALLO");
			return;
		}
		borrarMensaje();

		var str=$("#referencia").val().trim();

		str=str.replace(/[^a-zA-Z0-9.]+/g,'');

		var nombre_archivo = protocolo.identificador+"_EE_"+protocolo.id_protocolo+"_"+str;
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




	//************************ FIN SUBMIT ***

	//eventos generales

	$("#ciTecnico").autocomplete({
		source: tecnicosReconocidos,
		minLength: 2
	});


	$("#noRegistro").autocomplete({
		source: productosRegistradosDelOperador,
		minLength: 2
	});
	//fin eventos generales

	function verificarValorMinimo(){
		var val=$(this).val();
		var m=this.min;
		if(isNaN(val))
			mostrarMensaje('Solo numeros','FALLO');
		else
			{
				if(Number(val)<Number(m)){
					$(this).val('');
					this.focus();
					mostrarMensaje('El valor debe ser mayor a '+m,'FALLO');

				}
				else
					borrarMensaje();
			}
	}

	$("#normativa").change(function(){
		//actualiza el catalogo de tipos de formulación
		llenarCatalogoSubTipos();
		llenarCatalogoUnidadesFormulacion();

	});

	function llenarCatalogoUnidadesFormulacion(){
		var formulaciones=<?php echo json_encode($formulaciones); ?>;

		$('#iaFormulacion').children('option').remove();
		$('#iaFormulacion').append($("<option></option>").attr("value","").text("Seleccione...."));
		var normativa=$("#normativa").val().trim().toUpperCase();
		var shtml="";
		for(var i in formulaciones) {
			var obj=formulaciones[i];
			if(normativa=="NN"){
				if((obj.norma!=null) && (obj.norma=='NN' || obj.norma=='N')){
					$('#iaFormulacion').append($("<option></option>").attr("value",obj.id_formulacion).text(obj.formulacion));
				}
			}
			else{
				if((obj.norma!=null) && (obj.norma=='NA' || obj.norma=='N')){
					$('#iaFormulacion').append($("<option></option>").attr("value",obj.id_formulacion).text(obj.formulacion));
				}
			}

		}
		$("#iaFormulacion option[value="+ protocolo.plaguicida_formulacion +"]").attr("selected",true);
	}

	function llenarCatalogoSubTipos(){

		$('#subTipoProducto').children('option').remove();
		$('#subTipoProducto').append($("<option></option>").attr("value","").text("Seleccione...."));
		var normativa=$("#normativa").val().trim().toUpperCase();
		var shtml="";

		for(var i in catalogoSubTipos) {
			var obj=catalogoSubTipos[i];

			if(normativa=="NN"){
				if(obj.codigo!=null && obj.codigo=='RIA-RC'){
					continue;
				}
			}
			$('#subTipoProducto').append($("<option></option>").attr("value",obj.codigo).text(obj.nombre));
		}
		$("#subTipoProducto option[value="+ protocolo.uso +"]").attr("selected",true);

		habilitarPlagas(protocolo.uso);
	}


	$("#motivo").change(function(){
		borrarMensaje();
		motivoConstruir();
	});

	function motivoConstruir(){
		vmotivo=$("#motivo").val();
		$("#varMotivo").val(vmotivo);

		if (vmotivo=="MOT_AMP"){
			$("#cultivoMenor").show();
			ponerComboCultivos($("[name='boolModalidad']:checked").val());
		}
		else{
			$("#cultivoMenor").hide();
			ponerComboCultivos('NO');
			$('#boolNO').prop("checked", true);
		}

		//Zonas o Campañas
		//Mira si es Norma Andina
		llenarZonasGeograficas();
		if($("#normativa").val()=="NA" && vmotivo=="MOT_REG")
			$("#zonaGeo2").show();
		else
			$("#zonaGeo2").hide();


		if(vmotivo=="MOT_REG")
		{
			$("#tipoPlaguicida").val("PLAGUICIDA EXPERIMENTAL");
			$("#formuladorNombreExp").show();
			$("#formuladorPaisExp").show();
			$("#formuladorLoteExp").show();
			$("#formuladorNombreCom").hide();
			$("#formuladorPaisCom").hide();

			$("#verPlaguicidaComercial").hide();
			$('#noRegistro').removeAttr('required');

			$("#nombreProducto").prop( "readonly", false );

			$(".plaguicidaComercialView").hide();
			$(".plaguicidaExperimentalView").show();

		}
		else{
			$("#tipoPlaguicida").val("PLAGUICIDA COMERCIAL");
			$("#formuladorNombreExp").hide();
			$("#formuladorPaisExp").hide();
			$("#formuladorLoteExp").hide();
			$("#formuladorNombreCom").show();
			$("#formuladorPaisCom").show();

			$("#verPlaguicidaComercial").show();
			$('#noRegistro').attr("required","required");
			$("#nombreProducto").prop( "readonly", true );

			$(".plaguicidaComercialView").show();
			$(".plaguicidaExperimentalView").hide();
			//carga valores del producto registrado


			if($('#noRegistro').val()!="")
				llenarPlagicidaComercial(registroPlaguicida);

		}

		//Permiso de importación de muestra
		if($("#normativa").val()=="NN" && vmotivo=="MOT_REG")
			$("#refImportacionMuestra").show();
		else
			$("#refImportacionMuestra").hide();

	}

	function llenarZonasGeograficas(){
		var sr='<option value="">Seleccione....</option>';
		for(var i in catalogoProvincias)
		{
			sr=sr+'<option value="' + catalogoProvincias[i].codigo + '">' + catalogoProvincias[i].nombre + '</option>';
		}
		$("#ubicaAgoProvincia").html(sr);
		$("#ubicaAgoProvincia2").html(sr);

	}

	function obtenerLocalidades(provincia,elemento,categoria){
		var param={opcion_llamada:'obtenerLocalizacion',codigo:provincia,categoria:categoria};
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,mostrarLocalidades,elemento);

	}

	function mostrarLocalidades(items,elemento){
		var sr='<option value="">Seleccione....</option>';
		for(var i in items)
		{
			sr=sr+'<option value="' + items[i].codigo + '">' + items[i].nombre + '</option>';
		}
		$(elemento).html(sr);
	}

	$("#ubicaAgoProvincia").change(function(){
		llenarZonaSiguiente($(this),"#ubicaAgoCanton",'CANTONES');

	});
	$("#ubicaAgoProvincia2").change(function(){
		llenarZonaSiguiente($(this),"#ubicaAgoCanton",'CANTONES');

	});

	function llenarZonaSiguiente(padre,elemento,categoria){
		var vid=padre.attr("id");
		var idItem=vid.trim().substring(vid.length-1);
		//
		if(idItem=="2")
			elemento=elemento+idItem;
		obtenerLocalidades(padre.val(),elemento,categoria);
	}

	$("#ubicaAgoCanton").change(function(){
		llenarZonaSiguiente($(this),"#ubicaAgoParroquia",'PARROQUIAS');
	});
	$("#ubicaAgoCanton2").change(function(){
		llenarZonaSiguiente($(this),"#ubicaAgoParroquia",'PARROQUIAS');
	});

	//seleccion de cultivos
	$("[name='boolModalidad']").change(function(){
		ponerComboCultivos($(this).val());

	});

	function ponerComboCultivos(esCultivoMenor){
		if(esCultivoMenor=="SI"){	//cultivos menores
			var items=<?php echo json_encode($cultivosMenores); ?>;
			var src='<option value="">Seleccione....</option>';
			var sr='<option value="">Seleccione....</option>';
			for(var i in items)
			{
				src=src+'<option value="' + items[i].id_producto + '">' + items[i].nombre_cientifico + '</option>';
				sr=sr+'<option value="' + items[i].id_producto + '">' + items[i].nombre_comun + '</option>';

			}
			$("#cultivoNomCien").html(src);
			$("#cultivoNomComun").html(sr);
		}
		else{
			var items=<?php echo json_encode($cultivosNombres); ?>;
			var src='<option value="">Seleccione....</option>';
			var sr='<option value="">Seleccione....</option>';
			for(var i in items)
			{
				src=src+'<option value="' + items[i].id_producto + '">' + items[i].nombre_cientifico + '</option>';
				sr=sr+'<option value="' + items[i].id_producto + '">' + items[i].nombre_comun + '</option>';

			}
			$("#cultivoNomCien").html(src);
			$("#cultivoNomComun").html(sr);
		}
		ponerTitulo();
	}

	//diseño del experimento
	$("#condExperimento").change(function(){
		var cond=$(this).val();
		//borra condicion de otro experimento
		$('.experimentoTipo').hide();
		if(cond=="CEX_IN" || cond=="CEX_CA"){
			$('#expTipoDis').children('option').remove();
			$('#expTipoDis').append($("<option selected='selected'></option>").attr("value","DEX_DBCA").text("DBCA"));
			$('#expTipoDis').append($("<option></option>").attr("value","DEX_OTRO").text("Otro"));
			ponerTratamientos('DEX_DBCA');
		}
		else{
			$('#expTipoDis').children('option').remove();
			$('#expTipoDis').append($("<option selected='selected'></option>").attr("value","DEX_DCA").text("DCA"));
			ponerTratamientos('DEX_DCA');
		}
		//tamaño de la parcela
		ponerAtributosParcela(cond);
		if(!(cond=="CEX_CA" || cond=="CEX_IN") )
			$('.verTamanoParcela').hide();
		distribuirLineas();
	});

	function verCondicionExperimento(cond){
		//borra condicion de otro experimento
		$('.experimentoTipo').hide();
		if(cond=="CEX_IN" || cond=="CEX_CA"){
			$('#expTipoDis').children('option').remove();
			$('#expTipoDis').append($("<option selected='selected'></option>").attr("value","DEX_DBCA").text("DBCA"));
			$('#expTipoDis').append($("<option></option>").attr("value","DEX_OTRO").text("Otro"));

		}
		else{
			$('#expTipoDis').children('option').remove();
			$('#expTipoDis').append($("<option selected='selected'></option>").attr("value","DEX_DCA").text("DCA"));

		}
		cargarValorDefecto('expTipoDis',protocolo.diseno_experimento);
		//tamaño de la parcela

		if(!(cond=="CEX_CA" || cond=="CEX_IN") )
			$('.verTamanoParcela').hide();
	}

	function ponerTratamientos(tipoExp){
		if(tipoExp=="DEX_DBCA"){
			$('#noTratamientos').val("5");
			$('#noTratamientos').attr("required","required");
		}
		else{
			$('#noTratamientos').val("").attr("placeholder","valor");
			$('#noTratamientos').removeAttr('required');
		}
		if(tipoExp=="DEX_DCA"){
			$('#verRepeticiones').hide();
			$('#verObservaciones').show();
			$('#noObservaciones').val("3");
		}
		else{
			$('#verRepeticiones').show();
			$('#noRepeticiones').val("4");
			$('#verObservaciones').hide();
		}

	}


	function verTipoExperimento(tipoExp){
		if(tipoExp=="DEX_DBCA"){
			$('#noTratamientos').attr("required","required");
		}
		else{
			$('#noTratamientos').removeAttr('required');
		}
		if(tipoExp=="DEX_DCA"){
			$('#verRepeticiones').hide();
			$('#verObservaciones').show();
		}
		else{
			$('#verRepeticiones').show();
			$('#verObservaciones').hide();
		}
	}

	$('#expTipoDis').change(function(){
		var tipo=$('#expTipoDis').val();

		if(tipo=="DEX_OTRO"){
			$(".experimentoTipo").show();
			$('.verTamanoParcela').show();
			ponerAtributosParcela(tipo);

		}
		else{
			var b=$('#condExperimento').val();
			ponerAtributosParcela(b);
			$(".experimentoTipo").hide();

		}
		distribuirLineas();
	});

	function ponerAtributosParcela(tamano){

		if(tamano=="CEX_CA"){
			$('.verTamanoParcela').show();
			$('#parcelaTotal').attr("placeholder","mayor a 300").attr("min","300").attr("required","required").val('');
			$('#parcelaUnidad').attr("placeholder","mayor a 15").attr("min","15").attr("required","required").val('');
			$('#parcelaUtil').attr("placeholder","mayor a 10").attr("min","10").attr("required","required").val('');

		}
		else if(tamano=="CEX_IN"){
			$('.verTamanoParcela').show();
			$('#parcelaTotal').attr("placeholder","mayor a 200").attr("min","200").attr("required","required").val('');
			$('#parcelaUnidad').attr("placeholder","mayor a 10").attr("min","10").attr("required","required").val('');
			$('#parcelaUtil').attr("placeholder","mayor a 5").attr("min","5").attr("required","required").val('');

		}
		else{
			//no aplica
			$('#parcelaTotal').attr("placeholder","").removeAttr('min').removeAttr('required').val('');
			$('#parcelaUnidad').attr("placeholder","").removeAttr('min').removeAttr('required').val('');
			$('#parcelaUtil').attr("placeholder","").removeAttr('min').removeAttr('required').val('');

		}
	}

	$("#ciTecnico").change(function(){
		mostrarTecnicoANC();

	});

	function mostrarTecnicoANC(){
		var items=<?php echo json_encode($tecnicosReconocidos); ?>;
		var iden=$("#ciTecnico").val();
		var nombres="";
		for(var i in items){
			if(items[i].identificador==iden){
				nombres=items[i].nombres;
				break;
			}

		}
		if(nombres==""){
			mostrarMensaje('El técnico no esta registrado en la ANC','FALLO');

			$("#ciTecnico").val('');
			$("#ciTecnico").focus();
		}
		else{
			$('#nombreTecnico').val(nombres);
			borrarMensaje();
		}
	}

	$("#cultivoNomCien").change(function(){
		$('#cultivoNomComun').val($("#cultivoNomCien").val());
		$('#cultivoNomComun').change();
	});

	function habilitarPlagas(subtTipoProducto){
		if(subtTipoProducto=="RIA-RC" )
			$("#btnAddPlaga").attr('disabled','disabled');
		else
			$("#btnAddPlaga").removeAttr('disabled');
	}

	function encerarPlagas(){
		var tipoProd=$("#subTipoProducto").val();
		$("#resultadoPlagasDeclaradas").html("");


		$("#elegirPlagaDeclarada").val("");
		$("#elegirPlagaComun").val("");

		habilitarPlagas(tipoProd);

		if(tipoProd=="RIA-F" && $("#boolFungicoSI").is(":checked"))
			llenarPlagasComunesFungico();
		else
			llenarPlagasComunes();
		//borra taxones
		$("#tablaPlagas").html('');
	}

	function llenarPlagasComunes(){
		$('#elegirPlagaComun').children('option').remove();
		$('#elegirPlagaComun').append($("<option></option>").attr("value","").text("Seleccione...."));
		var selectValues=<?php echo json_encode($catalogoPlagas); ?>;
		$.each(selectValues, function(key, value) {
		$('#elegirPlagaComun')
			.append($("<option></option>")
			.attr("value",value.codigo)
			.text(value.nombre2));
		});
		$('#elegirPlagaComun').prop('disabled', 'disabled');
	}

	function llenarPlagasComunesFungico(){
		$('#elegirPlagaComun').children('option').remove();
		$('#elegirPlagaComun').append($("<option></option>").attr("value","").text("Seleccione...."));
		var selectValues=<?php echo json_encode($catalogoPlagasFungico); ?>;
		$.each(selectValues, function(key, value) {
			$('#elegirPlagaComun')
				.append($("<option></option>")
				.attr("value",value.codigo)
				.text(value.nombre));
		});
		$('#elegirPlagaComun').removeAttr("disabled");
	}

	$("#subTipoProducto").change(function(){
		var sprod=$("#subTipoProducto").val();
		actualizarSubtipoProducto(sprod);

		encerarPlagas();

		ponerTitulo();

		if($('#siTienePlaguicidaReferencia').is(':checked'))
			habilitarPlagicidaCoadyuvante($("#subTipoProducto").val(),'t');
	});

	//ver funcion para reemplazar
	function actualizarSubtipoProducto(sprod){

		if (sprod=="RIA-F"){
			$("#evaluarFungico").show();
			$("#disAplicarFungicida").show();	//para aplicacion del fungicida

		}
		else{
			$("#evaluarFungico").hide();
			$("#disAplicarFungicida").hide();	//para aplicacion de fungicida

		}
		if (sprod=="RIA-COAD" || sprod=="RIA-RP"){
			$("#titleCoadyuvante").html("Datos del producto");
		}
		else{
			$("#titleCoadyuvante").html("Datos del coadyuvante ");
		}

		//estadío de la plaga
		if(sprod=="RIA-I" || sprod=="RIA-A"){
			$("#disEstadioInsecto").show();
			$("#disEstadioInsectoOtro").hide();
		}
		else{
			$("#disEstadioInsecto").hide();
			$("#disEstadioInsectoOtro").hide();
		}

		//para aplicacion del herbicida
		if(sprod=="RIA-H"){
			$("#disAplicarHerbicida").show();
		}
		else{
			$("#disAplicarHerbicida").hide();
		}

		//Resetea las plagas

		if(sprod=="RIA-F" && $("#boolFungicoSI").is(":checked"))
			llenarPlagasComunesFungico();
		else
			llenarPlagasComunes();

		//

		//activa los ingredientes activos

		llamarIa('IAP',sprod,llenarIngredienteActivo);
	}

	function llamarIa(area,tipoProducto,func){

		var param={area:area};
		llamarServidor('ensayoEficacia','obtenerIngredienteActivo',param,func);
	}

	function llenarIngredienteActivo(items){
		var sr='<option value="">Seleccione....</option>';
		var sq='<option value="">Seleccione....</option>';
		for(var i in items)
		{
			var map=items[i];
			sr=sr+'<option value="' + map["codigo"] + '">' + map['nombre'] + '</option>';
			sq=sq+'<option value="' + map["codigo"] + '">' + map['quimico'] + '</option>';
		}
		$("#iaNombre").html(sr);
		$("#grupoQuimico").html(sq);
	}


	$("#boolFungicoSI").change(function(){
		encerarPlagas();
		llenarPlagasComunesFungico();
	});

	$("#boolFungicoNO").change(function(){
		encerarPlagas();
		llenarPlagasComunes();
	});

	$("#elegirPlagaDeclarada").change(function(){
		$("#elegirPlagaComun").val($(this).val());
	});

	//***************************************** PLAGAS ************************************

	$("#btnAddPlaga").click(function (event) {
		event.preventDefault();

		mostrarMensaje('','EXITO');

		var boolGuardar=true;
		var sprod=$("#subTipoProducto").val();
		if((sprod=="RIA-COAD")||(sprod=="RIA-H") || (sprod=="RIA-F" && $("#boolFungicoSI").is(":checked")))
		{
			//n plagas
		}
		else{	//una sola plaga
			var nFilas = $("#tablaPlagas").children('form').length;
			if(nFilas>0){
				mostrarMensaje('Máximo una plaga para el uso declarado','FALLO');
				boolGuardar=false;
			}
		}

		if(boolGuardar && $("#elegirPlagaDeclarada").val().trim()!=''){
			borrarMensaje();
			var param={opcion_llamada:'agregarPlagaDeclarada',id_protocolo:protocolo.id_protocolo,plaga_codigo:$('#elegirPlagaDeclarada').val(),plaga_codigo_comun:$('#elegirPlagaComun').val()};
			llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,llenarTablaIdenPlagaDatos);
		}
	});

	function llenarTablaIdenPlagaDatos(items){
		var sprod=$("#subTipoProducto").val();

		var oplaga=[];

		if(items!=null && items.length>0){
			oplaga=items;
		}
		
		
		//************ Pone leyenda de plagas declaradas *****************
		//iteractua por cada elemento
		var sp="";
		$.each(oplaga, function(key,value) {
			var comun="";
			if(sprod=="RIA-F" && $("#boolFungicoSI").is(":checked") ){
				
				if(value.nombre_fungico!=null)
					comun=primeraMayuscula(value.nombre_fungico.toLowerCase());
			}
			else{
				if(value.nombre2!=null)
					comun=primeraMayuscula(value.nombre2.toLowerCase());
			}
			if(sp==""){
				
				if(value.nombre!=null)					
					sp=comun+" (<i>"+value.nombre+"</i>)";
			}
			else{
				
				if(value.nombre!=null)
					sp=sp+", " + comun+" (<i>"+value.nombre+"</i>)";
			}
			

		});

		$("#resultadoPlagasDeclaradas").html(sp);

		//********************Construye plgas *********
		$("#tablaPlagas").empty();

		if(oplaga.length>0){

			var sret="";
			
			$.each(oplaga, function(key,value) {
				var iden=value.codigo+"PlagaSeparador";
				var iden='';

				sret=sret+'<form id="frmPlagaDeclaradaItem" name="frmPlagaDeclaradaItem" class="borrarFilaEfectos" data-rutaAplicacion="ensayoEficacia" data-opcion=""  >' +
								'<input type="hidden" id="id_protocolo" name="id_protocolo" value="' + value.id_protocolo + '" />' +
								'<input type="hidden" id="plaga_codigo" name="plaga_codigo" value="' + value.plaga_codigo + '" />' +
								'<input type="hidden" id="id_protocolo_plagas" name="id_protocolo_plagas" value="' + value.id_protocolo_plagas + '" />';


				sret=sret+"<fieldset><legend>Identificación de la plaga ( "+value.nombre+" ) taxones principales y biología </legend>";
				if(sprod=="RIA-H"){

					sret=sret+"<div data-linea='1'>";
					sret=sret+"<label>Clase:</label>";
					sret=sret+'<input class="obsPlagasDeclaradas" value="'+normalizarString(value.clase)+'" name="'+iden+'clase"  id="'+iden+'clase" maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>';
					sret=sret+"</div>";
				}
				sret=sret+"<div data-linea='1'>";
				sret=sret+"<label>Orden:</label>";

				sret=sret+'<input class="obsPlagasDeclaradas" value="'+normalizarString(value.orden)+'" name="'+iden+'orden"  id="'+iden+'orden" maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>';
				sret=sret+"</div>";
				sret=sret+"<div data-linea='1'>";
				sret=sret+"<label>Familia:</label>";

				sret=sret+'<input class="obsPlagasDeclaradas" value="'+normalizarString(value.familia)+'" name="'+iden+'familia"  id="'+iden+'familia" maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>';
				sret=sret+"</div>";
				sret=sret+"<div data-linea='1'>";
				sret=sret+"<label>Genero:</label>";

				sret=sret+'<input class="obsPlagasDeclaradas" value="'+normalizarString(value.genero)+'" name="'+iden+'genero"  id="'+iden+'genero" maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>';
				sret=sret+"</div>";

				sret=sret+"<hr/>";

				sret=sret+"<label>Ciclo de vida:</label>";

				sret=sret+'<textarea class="obsPlagasDeclaradas" value="" name="'+iden+'ciclo"  id="'+iden+'ciclo" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$">'+normalizarString(value.ciclo)+'</textarea>';
				sret=sret+"<label>Hábitos alimenticios:</label>";

				sret=sret+'<textarea class="obsPlagasDeclaradas" value="" name="'+iden+'habito"  id="'+iden+'habito" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$">'+normalizarString(value.habito)+'</textarea>';
				sret=sret+"<label>Comportamiento de la misma:</label>";

				sret=sret+'<textarea class="obsPlagasDeclaradas" value="" name="'+iden+'comportamiento"  id="'+iden+'comportamiento" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$">'+normalizarString(value.comportamiento)+'</textarea>';
				sret=sret+"<label>Estadío en el que ataca:</label>";

				sret=sret+'<textarea class="obsPlagasDeclaradas" value="" name="'+iden+'estadio"  id="'+iden+'estadio" maxlength="1024" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$">'+normalizarString(value.estadio)+'</textarea>';

				sret=sret+'<button type="button" class="guardar btnActualizarFilaPlaga obsPlagasDeclaradas">Actualizar</button>';
				sret=sret+'<button type="button" class="menos btnBorraFilaPlaga obsPlagasDeclaradas">Eliminar</button>';

				sret=sret+"</fieldset>";
				//***************Botones ****

				sret=sret+'</form>';

			});
		}
		$("#tablaPlagas").append(sret);

		ponerTitulo();
	}

	$("#tablaPlagas").off("click",".btnBorraFilaPlaga").on("click",".btnBorraFilaPlaga",function(event){
		event.preventDefault();
		var form=$(this).parent().parent();
		var param={opcion_llamada:'borrarPlagaDeclarada',id_protocolo:form.find("#id_protocolo").val(),id_protocolo_plagas:form.find("#id_protocolo_plagas").val()};
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,verPlagasDespuesEliminar);
	});

	function verPlagasDespuesEliminar(items){
		mostrarMensaje('La plaga fue eliminada','EXITO');
		llenarTablaIdenPlagaDatos(items);
	}



	$("#tablaPlagas").off("click",".btnActualizarFilaPlaga").on("click",".btnActualizarFilaPlaga",function(event){
		event.preventDefault();

		var form=$(this).parent().parent();
		var sprod=$("#subTipoProducto").val();
		var error = false;
		if(sprod=="RIA-H"){
			if(!esNoNuloEsteElemento(form.find("#clase")))
				error = true;
		}
		if(!esNoNuloEsteElemento(form.find("#orden")))
			error = true;
		if(!esNoNuloEsteElemento(form.find("#familia")))
			error = true;
		if(!esNoNuloEsteElemento(form.find("#genero")))
			error = true;
		if(!esNoNuloEsteElemento(form.find("#ciclo")))
			error = true;
		if(!esNoNuloEsteElemento(form.find("#habito")))
			error = true;
		if(!esNoNuloEsteElemento(form.find("#comportamiento")))
			error = true;
		if(!esNoNuloEsteElemento(form.find("#estadio")))
			error = true;

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();


		var param={opcion_llamada:'actualizarPlagaDeclarada',id_protocolo:form.find("#id_protocolo").val(),id_protocolo_plagas:form.find("#id_protocolo_plagas").val(),
			clase:form.find("#clase").val(),
			orden:form.find("#orden").val(),
			familia:form.find("#familia").val(),
			genero:form.find("#genero").val(),
			ciclo:form.find("#ciclo").val(),
			habito:form.find("#habito").val(),
			comportamiento:form.find("#comportamiento").val(),
			estadio:form.find("#estadio").val()
		};
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,verPlagasDespuesAgregar);
	});

	function verPlagasDespuesAgregar(items){
		mostrarMensaje('Datos de la plaga fueron actualizados','EXITO');
		llenarTablaIdenPlagaDatos(items);
	}


	//*************************************** plaguicida en prueba *****************

	$("#nombreProducto").change(function(){
		if(vmotivo=="MOT_REG"){
			var noReg=$(this).val();
			if(noReg!=""){
				var param={nombreProducto:noReg.trim()};
				llamarServidor('ensayoEficacia','consultarNombreProducto',param,verificarNombrePlagicida);
			}
		}
		ponerTitulo();
	});

	function verificarNombrePlagicida (items){
		if(items==null){
			//desabilitado por cambio en especificacioens
			borrarMensaje();
		}
		else{
			mostrarMensaje("Nombre ya existe, intente otro nombre",'FALLO');
			$("#nombreProducto").val('');
			$("#nombreProducto").focus();
		}
	}

	$("#noRegistro").change(function(){
		$("#nombreProducto").val('');		//Limpia el nombre del producto
		$('#listaIa').text('');
		$('#plagPruebaQuimico').val('');
		$("#formuladorNombreCom").html('');
		$("#formuladorPaisCom").val('');

		var noReg=$(this).val();
		if(noReg!=""){
			var param={noRegistro:noReg};
			llamarServidor('ensayoEficacia','consultarNoRegistroProducto',param,llenarPlagicidaComercial);
		}
	});



	function llenarPlagicidaComercial (items){
		var formulacion='';
		if(items==null){

			mostrarMensaje("El número de registro introducido no existe",'FALLO');
			$("#noRegistro").val('');
			$("#noRegistro").focus();
		}
		else{
			if(items.producto==null)
				mostrarMensaje("Favor elija un número de regristro válido",'FALLO');
			else{
				borrarMensaje();
				$("#nombreProducto").val(items.producto[0].nombre_comun);
				formulacion=items.producto[0].id_formulacion;
			}


			if(items.composicion!=null){
				var sfor="";
				var quim='';
				$.each(items.composicion, function(key, value) {

					if(sfor==""){
						sfor=value.ingrediente_activo+" "+value.concentracion + (value.unidad_medida==null?"":value.unidad_medida).toLowerCase();
						quim=(value.grupo_quimico==null?"":value.grupo_quimico);
					}
					else{
						sfor=sfor+" + "+value.ingrediente_activo+" "+value.concentracion + (value.unidad_medida==null?"":value.unidad_medida).toLowerCase();
						quim=quim+", "+(value.grupo_quimico==null?"":value.grupo_quimico);
					}
				});

				$('#plagPruebaIa').val(sfor);

				$("#plagPruebaQuimico").val(quim);

				mostrarComposicion(formulacion);
			}

			if(items.fabricantes!=null){

				fabricantesPrueba.length=0;

				var sr='<option value="">Seleccione....</option>';
				for(var i in items.fabricantes)
				{
					var map=items.fabricantes[i];
					

						sr=sr+'<option value="' + map["id_fabricante_formulador"] + '"   data-pais="' + map["id_pais_origen"] + '" >' + map['nombre'] + '</option>';
						fabricantesPrueba[map["id_fabricante_formulador"]]=map["pais_origen"];
					
				}
				$("#formuladorNombreCom").html(sr);

				cargarValorDefecto('formuladorNombreCom',protocolo.plaguicida_formulador);
				$("#formuladorPaisCom").val(fabricantesPrueba[protocolo.plaguicida_formulador]);

			}
		}

	}

	$("#formuladorNombreCom").change(function(){
		var indice=$(this).val();
		$("#formuladorPaisCom").val(fabricantesPrueba[indice]);

		$("#formuladorNombre").val(indice);
		cargarValorDefecto('formuladorPais',$(this).find(':selected').data().pais);
	});

	$("#iaNombre").change(function(){
		var ia=$("#iaNombre").val();
		$("#grupoQuimico").val(ia);

	});

	$("#btnClearIa").click(function(){
		var param={id_protocolo:protocolo.id_protocolo};
		llamarServidor('ensayoEficacia','eliminarIngredientesActivos',param,actualizarComposicion);
		$("#iaCodigo").val("");
		$("#iaCodigoNombre").val("");
		$("#listaIa").html("");
		$("#plagPruebaQuimico").val("");

	});


	$("#btnSaveIa").click(function(){
		//Pongo los datos en parámetros
		var id_protocolo=<?php echo json_encode($idProtocolo); ?>;
		var codigo=$("#iaNombre").val();
		var concentracion=$("#iaConcentracion").val();
		var unidad=$("#iaUnidad").val();
		//verifico que no esten vacios
		if(id_protocolo==null || codigo==null || concentracion==null || unidad==null){

		}
		else{

			var param={id_protocolo:id_protocolo,codigo:codigo,concentracion:concentracion,unidad:unidad};
			llamarServidor('ensayoEficacia','guardarIngredienteActivo',param,actualizarComposicion);
		}

	});

	function actualizarComposicion(datos){
		contruirComposicion(datos);
		mostrarComposicion($("#iaFormulacion").val());
	}

	function contruirComposicion(datos){
		var sret="";
		var quim="";
		if(datos!=null || datos.length==0){
			$.each(datos, function(key, value) {
				if(value.ingrediente_activo!=null){
					var unidad=value.codigo==null?"":value.codigo.toLowerCase();
					if(sret==""){
						quim=value.grupo_quimico;
						sret=value.ingrediente_activo+" "+value.concentracion+" "+unidad;
					}

					else{
						quim=quim+", "+value.grupo_quimico;
						sret=sret+" + "+value.ingrediente_activo+" "+value.concentracion+" "+unidad;
					}
				}
			});
		}
		$("#plagPruebaIa").val(sret);
		$("#plagPruebaQuimico").val(quim);
	}

	function mostrarComposicion(id_formulacion){

		var formulacion=buscarVectorEnMatriz(formulaciones,'id_formulacion',id_formulacion);
		var sret=$("#plagPruebaIa").val();
		var fn="";
		if(formulacion!=null && formulacion.sigla!=undefined ){
			fn=formulacion.sigla;
			sret=sret+", "+fn;
		}
		$("#listaIa").html(sret);
		ponerTitulo();
	}


	function buscarVectorEnMatriz(matriz,clave,valor){
		var sret="";
		for(var i in matriz) {
			var obj=matriz[i];
			try{
				if(obj[clave]==valor){
					sret=obj;
					break;
				}
			}catch(err){}
		}
		return sret;
	}

	$("#iaFormulacion").change(function(){
		mostrarComposicion($("#iaFormulacion").val());
	});

	$("[name='modoAccion[]']").change(function(){
		atenderPulsarCheckbox("[name='modoAccion[]']",$(this), "#plaguicida_modo_accion","MAC_OTRO","#verOtroModoAccion");

	});

	function atenderPulsarCheckbox(elementos,elementoPulsado,campoActualizar,valorValidar,campoMostrarSiValido){
		var vc = $(elementos+":checked").map(function () {
			return this.value;
		}).get();
		vc=vc.join(',');
		$(campoActualizar).val(vc);

		if(elementoPulsado.val()==valorValidar ){
			if(elementoPulsado.is(":checked"))
				$(campoMostrarSiValido).show();
			else
				$(campoMostrarSiValido).hide();
		}
	}

	function mostrarCheckboxRecuperados(cadenaComas, elemento,valorValidar,campoMostrarSiValido){
		if(cadenaComas!=null && cadenaComas.trim()!="" && cadenaComas.trim()!='0'){
			var arr=cadenaComas.split(',');
			var boolValidar=false;
			$(elemento).each(function(){
				var ck=$(this);
				$(this).prop("checked", false );

				$.each(arr, function(index, value) {
					if(value==ck.val()){
						ck.prop("checked", true );

						if(valorValidar!=null && value==valorValidar)
							boolValidar=true;
					}
				});
			});

			if(campoMostrarSiValido!=null)
			{
				if(boolValidar==true)
					$(campoMostrarSiValido).show();
				else
					$(campoMostrarSiValido).hide();
			}
		}
	}

	$("#noTienePlaguicidaReferencia").change(function(){
		if($(this).is(':checked')){
			$("#disRazonPlaguicidaReferencia").show();
			$(".seccionPlaguicidaReferencia").hide();
		}
	});

//***** Plagicida de referencia
	$("#siTienePlaguicidaReferencia").change(function(){
		if($(this).is(':checked')){
			$("#disRazonPlaguicidaReferencia").hide();
			$(".seccionPlaguicidaReferencia").show();
			habilitarPlagicidaCoadyuvante($("#subTipoProducto").val(),'t');

		}
	});

	$("#plagRefNoRegistro").autocomplete({
		source: productosRegistradosDelOperador,
		minLength: 2
	});



	$("#plagRefNoRegistro").change(function(){

		$("#plagRefNombre").val('');
		$('#plagRefIa').val('');
		$('#plagRefQuimico').val('');
		$("#plagRefFormulador").html('');
		$("#plagRefPais").val('');

		var noReg=$(this).val();
		if(noReg!=""){
			var param={noRegistro:noReg};
			llamarServidor('ensayoEficacia','consultarNoRegistroProducto',param,llenarPlagicidaReferencia);
		}
	});


	function llenarPlagicidaReferencia (items){
		var formulacion='';

		if(items==null){
			mostrarMensaje("El número de registro introducido no existe",'FALLO');
			$("#plagRefNoRegistro").val('');
			$("#plagRefNoRegistro").focus();
		}
		else{
			if(items.producto==null)
				mostrarMensaje("Producto nulo",'FALLO');
			else{
				borrarMensaje();
				var fs=buscarVectorEnMatriz(formulaciones,'id_formulacion',items.producto[0].id_formulacion);
				$("#plagRefNombre").val(items.producto[0].nombre_comun);
				try{
					formulacion=fs.sigla;
				}catch(err){
					formulacion='';
				}

			}

			if(items.composicion!=null){
				var sfor="";
				var quim="";
				$.each(items.composicion, function(key, value) {
					if(sfor==""){
						sfor=value.ingrediente_activo+" "+value.concentracion + (value.unidad_medida==null?"":value.unidad_medida).toLowerCase();
						quim=(value.grupo_quimico==null?"":value.grupo_quimico);
					}
					else{
						sfor=sfor+" + "+value.ingrediente_activo+" "+value.concentracion+ (value.unidad_medida==null?"":value.unidad_medida).toLowerCase();
						quim=quim+", "+(value.grupo_quimico==null?"":value.grupo_quimico);
					}
				});
				if((sfor!="") && (formulacion!='undefined'))
					sfor=sfor+", "+formulacion;
				$('#plagRefIa').val(sfor);
				$('#plagRefQuimico').val(quim);

			}

			if(items.fabricantes!=null){

				var sr='<option value="">Seleccione....</option>';
				for(var i in items.fabricantes)
				{
					var map=items.fabricantes[i];
					sr=sr+'<option value="' + map["id_fabricante_formulador"] + '">' + map['nombre'] + '</option>';
					fabricantesReferencia[map["id_fabricante_formulador"]]=map["pais_origen"];
				}
				$("#plagRefFormulador").html(sr);

			}
		}
	}

	$("#plagRefFormulador").change(function(){
		var indice=$(this).val();
		$("#plagRefPais").val(fabricantesReferencia[indice]);
	});

	//Coadyuvante

	$("[name='tienePlaguicidaCoadyuvante']").change(function(){
		if($(this).val()=="t"){
			$(".disPlaguicidaCoadyuvante").show();
		}
		else{
			$(".disPlaguicidaCoadyuvante").hide();
		}
	});

	$("#cyNoRegistro").autocomplete({
		source: productosRegistradosDelOperador,
		minLength: 2
	});

	$("#cyNoRegistro").change(function(){
		$("#cyNombre").val('');
		$('#cyDosis').val('');
		$('#cyDosisUnidad').val('');
		$("#cyIa").val('');
		$("#cyQuimico").val('');


		var noReg=$(this).val();
		if(noReg!=""){
			var param={noRegistro:noReg};
			llamarServidor('ensayoEficacia','consultarNoRegistroProducto',param,llenarCy);
		}
	});

	var fabricantesCy={};
	function llenarCy (items){
		var formulacion='';
		if(items==null){
			//no hace nada link
			mostrarMensaje("El número de registro introducido no existe",'FALLO');
			$("#cyNoRegistro").val('');
			$("#cyNoRegistro").focus();
		}
		else{
			if(items.producto==null)
				mostrarMensaje("Producto nulo",'FALLO');
			else{
				borrarMensaje();
				$("#cyNombre").val(items.producto[0].nombre_comun);

				$("#cyDosis").val(items.producto[0].dosis);
				$("#cyDosisUnidad").val(items.producto[0].unidad_dosis);

				var fs=buscarVectorEnMatriz(formulaciones,'id_formulacion',items.producto[0].id_formulacion);
				formulacion=fs.sigla;

				ponerTitulo();
			}


			if(items.composicion!=null){
				var sfor="";
				var quim="";
				$.each(items.composicion, function(key, value) {
					if(sfor==""){
						sfor=value.ingrediente_activo+" "+value.concentracion  + (value.unidad_medida==null?"":value.unidad_medida).toLowerCase();
						quim=(value.grupo_quimico==null?"":value.grupo_quimico);
					}
					else{
						sfor=sfor+" + "+value.ingrediente_activo+" "+value.concentracion  + (value.unidad_medida==null?"":value.unidad_medida).toLowerCase();
						quim=quim+", "+(value.grupo_quimico==null?"":value.grupo_quimico);
					}
				});
				if(sfor!="")
					sfor=sfor+", "+formulacion;
				$('#cyIa').val(sfor);
				$('#cyQuimico').val(quim);
			}

		}
	}

	$("#cyFormulador").change(function(){
		var indice=$(this).val();
		$("#cyformuladorPais").val(fabricantesReferencia[indice]);
	});


	//modos de accion plaguicida de referencia
	$("[name='plagRefModoAccion[]']").change(function(){
		atenderPulsarCheckbox("[name='plagRefModoAccion[]']",$(this), "#pr_modo_accion","MAC_OTRO","#verPlagRefOtroModoAccion");

	});


	$("#tipoAplicacion").change(function(){
		verSeccionOtro("#tipoAplicacion","TAP_OTRO","#disTipoAplicacion");

	});

	function verSeccionOtro(elemento,valorOtro,elementoMostrar){
		if($(elemento).val()==valorOtro)
			$(elementoMostrar).show();
		else
			$(elementoMostrar).hide();
	}

	$("#tipoEquipoUso").change(function(){
		verSeccionOtro("#tipoEquipoUso","TEU_OTRO","#disTipoEquipoUsoOtro");

	});


	$("[name='tieneUnidadDosis']").change(function(){
		if($(this).val()=="t"){
			$("#disUnidadDosis").show();
			$("#disUnidadDosisOtra").hide();
		}
		else{
			$("#disUnidadDosis").hide();
			$("#disUnidadDosisOtra").show();
		}
		mostrarUnidadDosis();
	});

	$("#unidadDosis").change(function(){
		mostrarUnidadDosis();

	});

	$("#unidadDosisOtra").change(function(){
		mostrarUnidadDosis();

	});

	function mostrarUnidadDosis(){
		if($("#siTieneUnidadDosis").is(":checked")){
			$("#nombreDosis").html('Dosis('+$("#unidadDosis option:selected").html()+')');
		}
		else{
			$("#nombreDosis").html('Dosis('+$("#unidadDosisOtra").val()+')');
		}
	}

	$('#noTratamientos').change(function(){
		var val=$(this).val();
		var m=this.min;
		var mx=this.max;
		if(isNaN(val))
			mostrarMensaje('Solo numeros',"FALLO");
		else
		{
			if(Number(val)<Number(m)){
				$(this).val('');
				this.focus();
				mostrarMensaje('El valores recomendado debe mínimo '+m,'FALLO');

			}
			else if(Number(val)>Number(mx)){
				$(this).val('');
				this.focus();
				mostrarMensaje('El valor recomendado debe ser máximo '+mx,'FALLO');

			}
			else{
				borrarMensaje();
				//actualiza para guardar tratamientos
				$('#noTratamientos').val(val);
				mostrarItemsTratamientos(val);

			}
		}
	});

	function mostrarItemsTratamientos(noTratamientos){
		for(i=1;i<=8;i++){
			$("#tratamientoT"+i+"view").hide();

			$("#tratamientoT"+i+"").val(tratamientosDatos[i]);
		}
		for(i=1;i<=noTratamientos;i++)
		{
			$("#tratamientoT"+i+"view").show();

			$("#tratamientoT"+i).attr('readonly', false);
		}
		if(noTratamientos=="5"){

			$("#tratamientoT3").attr('readonly', true);
			$("#tratamientoT4").attr('readonly', true);

			ponerAutoLlenadoTratamientos();
		}
	}
	$("#tratamientoT1").change(function(){
		ponerAutoLlenadoTratamientos();
	});

	$("#tratamientoT2").change(function(){
		ponerAutoLlenadoTratamientos();
	});

	function ponerAutoLlenadoTratamientos(){
		if($('#noTratamientos').val()=="5"){
			var t1=Number($("#tratamientoT1").val());
			var t2=Number($("#tratamientoT2").val());

			$("#tratamientoT3").val(t2+(t2-t1));
			if($("#siTienePlaguicidaReferencia").is(":checked")){

				$("#tratamientoT4").attr('readonly', false);
				}
			else
				$("#tratamientoT4").val(2*t2);

		}
	}

	//****************  Guardar las tratamientos ******************

	$("#btnTratamientos").click(function (event) {
		event.preventDefault();

		borrarMensaje();

		var param={opcion_llamada:'guardarSubsanacionesTratamientos',	id_protocolo:protocolo.id_protocolo,noTratamientos:$('#noTratamientos').val()};

		$('#tablaTratamientos tbody tr').find("input:enabled").each(function(j) {

			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param[item.attr("id")]=item.val();
			}
		});

		mostrarMensaje('Generando tratamientos...','');
		
		llamarServidor('ensayoEficacia','atenderLlamadaServidor',param,resultadoGuardarTratamientos);

		
	});

	function resultadoGuardarTratamientos(items){
		borrarMensaje();
		if(items==null){
			mostrarMensaje("Error al guardar los datos de los tratamientos","FALLO");
		}
		else{
			mostrarMensaje("Datos de los tratamientos fueron guardados","EXITO");
		}
		
	}

	//**************************************************************

	$("#estadioInsecto").change(function(){
		if($(this).val()=="ASE_OTRO")
			$("#disEstadioInsectoOtro").show();
		else
			$("#disEstadioInsectoOtro").hide();

	});

	$("[name='condicionSuelo[]']").change(function(){
		atenderPulsarCheckbox("[name='condicionSuelo[]']",$(this), "#condicion_suelo","CDS_OTRO","#disCondicionSueloOtro");

	});

	$("[name='condicionAmbiental[]']").change(function(){
		atenderPulsarCheckbox("[name='condicionAmbiental[]']",$(this), "#condicion_ambiental","CAM_OTRO","#disCondicionAmbientalOtro");

	});



	$("#mmEvaluacion").change(function(){
		verSeccionOtro(this,"UMC_OTRO","#disMmEvaluacionOtro");

	});

	$("[name='tieneEscalaEvaluacion']").change(function(){
		verEscalaEvaluacion($(this).val()=="t");

	});

	function verEscalaEvaluacion(tiene){
		if(tiene){
			$("#tieneEscalaEvaluacionView1").show();
			$("#tieneEscalaEvaluacionView2").show();
		}
		else{
			$("#tieneEscalaEvaluacionView1").hide();
			$("#tieneEscalaEvaluacionView2").hide();
		}
	}



	$("#evalEficacia").change(function(){
		verSeccionOtro(this,"VEE_OTRO","#evalEficaciaOtraView");

	});


</script>

