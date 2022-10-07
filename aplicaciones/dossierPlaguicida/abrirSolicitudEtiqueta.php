<?php 
session_start();

	require_once '../../clases/Conexion.php';	
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPlaguicida.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';


	$numeroPestania = $_POST['numeroPestania'];
	
	$idUsuario= $_SESSION['usuario'];			//Es el usuario logeado en la solicitud
	$id_solicitud = $_POST['id'];
	
	$identificador=$idUsuario;					//Es el duenio del documento, puede variar si ya hay un protocolo y el usuario es alguien de revision, aprobacion, etc..

	$conexion = new Conexion();
	$co = new ControladorRegistroOperador();
	$ce = new ControladorEnsayoEficacia();	
	$cg=new ControladorDossierPlaguicida();
	$cr = new ControladorRequisitos();
	$cc=new ControladorCatalogos();
	
	
	$datosGenerales=array();	
	$fabricantes=array();
	$formuladores=array();
	$anexosSolicitud=array();
	$operador = array();
	
	$operadoresFabricantes=array();
	
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

		$datosGenerales=$cg->obtenerEtiquetaSolicitud($conexion, $id_solicitud);
		
		$datosDossier=$cg->obtenerSolicitud($conexion, $id_solicitud);

		$identificador=$datosGenerales['identificador'];						//El duenio del documento
		
		 $fabricantes=$cg->obtenerFabricantesModificacion($conexion,$id_solicitud,'F');
		 $formuladores=$cg->obtenerFabricantesModificacion($conexion,$id_solicitud,'R');

		 $presentaciones=$cg->obtenerPresentaciones($conexion,$id_solicitud);
		
		 $anexosSolicitud=$cg->obtenerArchivosAnexos($conexion,$id_solicitud);
	}
	
	//busca los datos del operador
	$res = $co->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);

	
	$clonesRegistrados=$ce->obtenerClonesRegistrados($conexion,$identificador);	//para clones
	

//****************** ANEXOS **************************************
	$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
	$pathAnexo=$paths['ruta'];
	
?>

<header>
	<h1>Puntos mínimos para la etiqueta</h1>
</header>

<div id="estado"></div>




<div>
   <form id='frmSolicitudEtiqueta' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosEtiqueta'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="E1" />

      <fieldset>
         <legend>Etiquetado del producto formulado</legend>

         <div class="justificado">
            <label for="precaucion_uso">Precauciones de uso y aplicación:</label>
            <textarea name="precaucion_uso"  id="precaucion_uso"  maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['precaucion_uso'];?></textarea>
         </div>
         <div class="justificado">
            <label for="medidas_seguridad">Medidas relativas a la seguridad:</label>
            <textarea name="medidas_seguridad"  id="medidas_seguridad" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['medidas_seguridad'];?></textarea>
         </div>
         <div class="justificado">
            <label for="almacen_manejo">Almacenamiento y manejo del producto:</label>
            <textarea name="almacen_manejo"  id="almacen_manejo" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['almacen_manejo'];?></textarea>
         </div>
         <div class="justificado">
            <label for="medidas_auxilio">Medidas relativas a primeros auxilios:</label>
            <textarea name="medidas_auxilio"  id="medidas_auxilio" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['medidas_auxilio'];?></textarea>
         </div>
         <div class="justificado">
            <label for="nota_medico">Nota para el médico tratante:</label>
            <textarea name="nota_medico"  id="nota_medico" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['nota_medico'];?></textarea>				
         </div>
         <div class="justificado">
            <label for="rotulo_veneno">Rótulo caso de emergencia:</label>
            <textarea name="rotulo_veneno"  id="rotulo_veneno" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php
				if(($datosGenerales==null || ($datosGenerales['rotulo_veneno']==null))){
					$texto='EN CASO DE EMERGENCIA LLAME AL 1800-VENENO  (836366); o comuníquese con ...';
					echo $texto;
				}
				else
					echo $datosGenerales['rotulo_veneno'];
				?></textarea> 
         </div>
         <div class="justificado">
            <label for="medidas_envases">Medidas relativas para la disposición de envases vacíos:</label>
            <textarea name="medidas_envases"  id="medidas_envases" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['medidas_envases'];?></textarea>
         </div>
         <div class="justificado">
            <label for="medidas_ambiente">Medidas relativaspara la protección del ambiente:</label>
            <textarea name="medidas_ambiente"  id="medidas_ambiente" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['medidas_ambiente'];?></textarea>
         </div>
                  
         <div class="justificado">
            <label for="instruccion_uso">Instrucciones de uso y manejo:</label>
            <textarea name="instruccion_uso"  id="instruccion_uso" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['instruccion_uso'];?></textarea>
         </div>
         <div class="justificado">
            <label for="modo_empleo">Modo de empleo:</label>
            <textarea name="modo_empleo"  id="modo_empleo" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['modo_empleo'];?></textarea>
         </div>
         <div class="justificado">
            <label for="epoca_aplicacion">ÉPOCA Y FRECUENCIA DE APLICACIÓN:</label>
            <textarea name="epoca_aplicacion"  id="epoca_aplicacion" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['epoca_aplicacion'];?></textarea>
         </div> 
         <div class="justificado">
            <label for="periodo_reingreso">PERÍODO DE REINGRESO:</label>
            <textarea name="periodo_reingreso"  id="periodo_reingreso" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['periodo_reingreso'];?></textarea>
         </div>
         <div class="justificado">
            <label for="fitoxicidad">FITOTOXICIDAD:</label>
            <textarea name="fitoxicidad"  id="fitoxicidad" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['fitoxicidad'];?></textarea>
         </div>
         <div class="justificado">
            <label for="compatibilidad">COMPATIBILIDAD:</label>
            <textarea name="compatibilidad"  id="compatibilidad" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php echo $datosGenerales['compatibilidad'];?></textarea>
         </div>

         <div class="justificado">
            <label for="responsabilidad">RESPONSABILIDAD:</label>
            <textarea name="responsabilidad"  id="responsabilidad" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"><?php 
			if(($datosGenerales==null) || ($datosGenerales['responsabilidad']==null)){
				$texto='El Titular del registro garantiza que las características físico-químicas del producto contenido en este envase, corresponden a las anotadas en la etiqueta y que es eficaz para los fines aquí recomendados, si se usa y maneja de acuerdo con las condiciones e instrucciones dadas.';
				echo $texto;
			}
			else
				echo $datosGenerales['responsabilidad'];
			?>
			</textarea>
         </div>
         <div data-linea="18">
            <label for="id_categoria_toxicologica">CATEGORÍA TOXICOLÓGICA:</label>
            <select name="id_categoria_toxicologica" id="id_categoria_toxicologica" required>
               <option value="">Seleccione....</option>               <?php
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

      <fieldset class="verOrganismosExternos">
         <legend>Informes de organismo externos</legend>


         <div class="justificado">
            <label for="mae">Comentario de MAE:</label>
            <textarea name="mae"  id="mae" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled="disabled">
					<?php echo $datosDossier['mae_comentario'];?>
				</textarea>
         </div>
         <div class="justificado">
            <a id="verInformeMAE" href="<?php echo $datosDossier['mae_ruta'];?>" target="_blank">Informe de MAE</a>
         </div>
         <hr />
         <div class="justificado">
            <label for="salud">Comentario de SALUD:</label>
            <textarea name="salud"  id="salud" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" disabled="disabled">
					<?php echo $datosDossier['salud_comentario'];?>
				</textarea>
         </div>
         <div class="justificado">
            <a id="verInformeSALUD" href="<?php echo $datosDossier['salud_ruta'];?>" target="_blank">Informe de SALUD</a>
         </div>

      </fieldset>

      <button type="submit" class="guardar">Guardar</button>
   </form>

   <form id='frmVistaPreviaEtiqueta' data-rutaaplicacion='dossierPlaguicida' data-opcion=''>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />

      <button id="btnVistaPreviaEtiqueta" type="button" class="documento btnVistaPreviaEtiqueta">Vista previa</button>
      <a id="verReporteEtiqueta" href="" target="_blank" style="display:none">Ver archivo</a>
   </form>
   <form id='frmFinalizarEtiqueta' data-rutaaplicacion='dossierPlaguicida' data-opcion=''>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />

      <button id="btnFinalizarEtiqueta" type="button" class="guardar btnFinalizarEtiqueta">Solicitar aprobación</button>
     
   </form>
</div>



<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/generales.js"></script>


<script type="text/javascript">

	
	var solicitud=<?php echo json_encode($datosGenerales); ?>;
	

	var datosDossier=<?php echo json_encode($datosDossier); ?>;


	var codigoFormulacion='';
	var protocolo={};

	//**************************  VARIABLES GENERALES *****************************

	//****************** CARGA *************************************
	$("document").ready(function(){

		prevenirCaracteresMaximos("textarea,select");

		distribuirLineas();

		//verifica si esta en estado de aprobacion
		if(solicitud!=null && solicitud.estado=='aprobarEtiqueta'){
			//deshabilita casillas
			$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
			$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
			
			$('section#detalleItem').find('button:not(.btnVistaPreviaEtiqueta)').hide();
		
		}

		if(datosDossier.es_clon=='t'){
			$('.verOrganismosExternos').hide();
		}
		else{
			$('.verOrganismosExternos').show();
			if(datosDossier!=null && datosDossier.mae_ruta!=null)
				$('#verInformeMAE').show();
			else
				$('#verInformeMAE').hide();

			if(datosDossier!=null && datosDossier.salud_ruta!=null)
				$('#verInformeSALUD').show();
			else
				$('#verInformeSALUD').hide();
		}

		
	});

	$('#frmSolicitudEtiqueta').submit(function(event){
		event.preventDefault();
		$("#estado").html("");
		borrarMensaje();
		error = false;

		verificarCamposVisiblesNulos(['#precaucion_uso','#medidas_seguridad','#almacen_manejo','#medidas_auxilio','#nota_medico','#rotulo_veneno','#medidas_envases','#medidas_ambiente']);
		verificarCamposVisiblesNulos(['#instruccion_uso','#modo_empleo','#epoca_aplicacion','#periodo_reingreso','#fitoxicidad','#compatibilidad','#responsabilidad','#id_categoria_toxicologica']);
		
		
		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		borrarMensaje();
		

		if(error){
			mostrarMensaje("Llene los campos obligatorios","FALLO");
			return;
		}
		//borrarMensaje();

		var form=$(this);
		//$("#precaucion_uso").attr("required", true);
		//$('#precaucion_uso').prop("required", true);
		


		form.attr('data-opcion', 'guardarPasosEtiqueta');
		form.attr('data-destino', 'detalleItem');
		form.attr('data-accionEnExito', '');
		
		ejecutarJson(form);

	});


	//***************************** VISTA PREVIA ETIQUETA ***************************************
	$('button.btnVistaPreviaEtiqueta').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+datosDossier.id_solicitud+"' />"); // añade el nivel del formulario

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


	//***************************** FINALIZAR ETIQUETA ***************************************
	$('button.btnFinalizarEtiqueta').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='opcion_llamada' name='opcion_llamada' value='guardarPuntosEtiqueta' />"); 

		form.attr('data-opcion', 'atenderFlujosPlaguicidas');

		mostrarMensaje("Generando archivo ... ",'');
		
		ejecutarJson(form,new exitoFinalizarEtiqueta());

	});

	function exitoFinalizarEtiqueta(){
		this.ejecutar=function (msg){
			if(msg.estado=='exito'){
				mostrarMensaje('Trámite enviado','EXITO');
				$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
				abrir($("input:hidden"), null, false);
			}else{
				mostrarMensaje('Favor revise si todos los puntos fueron subsanados, la transacción no pudo ser compleada','FALLO');
			}
			
		};
	}


</script>

