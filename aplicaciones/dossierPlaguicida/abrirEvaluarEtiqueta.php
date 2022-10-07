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


	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	$id_tramite_flujo = $_POST['nombreOpcion'];

	$identificador=$idUsuario;					//Es el duenio del documento, puede variar si ya hay un protocolo y el usuario es alguien de revision, aprobacion, etc..

	$conexion = new Conexion();
	$co = new ControladorRegistroOperador();
	$ce = new ControladorEnsayoEficacia();
	$cg=new ControladorDossierPlaguicida();

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
	<h1>Evaluación de puntos mínimos para la etiqueta del dossier: <?php echo $datosDossier['id_expediente'];?></h1>
</header>

<div id="estado"></div>




<div>
   <form id='frmSolicitudEtiqueta' data-rutaAplicacion='dossierPlaguicida' data-opcion='guardarPasosEtiqueta'>
      <input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $id_solicitud;?>" />
		<input type="hidden" id="id_documento" name="id_documento" value="<?php echo $id_solicitud;?>" />

		<input type="hidden" id="id_flujo" name="id_flujo" value="<?php echo $id_flujo;?>" />
		<input type="hidden" id="id_tramite_flujo" name="id_tramite_flujo" value="<?php echo $id_tramite_flujo;?>" />
      <input type="hidden" id="paso_solicitud" name="paso_solicitud" value="E1" />

      
      <fieldset class=verOrganismosExternos>
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

		<fieldset>
			<legend>Resultado de la evaluación</legend>
			<div class="justificado">
				<a id="verPuntosMinimos" href="<?php echo $datosGenerales['ruta'];?>" target="_blank">Puntos mínimos reportados</a>
			</div>
			<div class="justificado">
				<label for="salud">Comentario:</label>
				<textarea name="comentario"  id="comentario" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$">
					<?php echo $datosGenerales['comentario'];?>
				</textarea>
			</div>
			<div >
				<label>
					<input type="radio" name="opcionAprobar" value="C_D_O" />Observar
				</label>
				<br />
				
				<label>
					<input type="radio" name="opcionAprobar" value="" />Aprobar
				</label>
				<br />
				<button id="btnEnviarTramite" type="button" class="guardar btnEnviarTramite">Enviar trámite</button>
			</div>


		</fieldset>

      
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

		
		distribuirLineas();

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

	


	//***************************** VISTA PREVIA ETIQUETA ***************************************
	$('button.btnVistaPreviaEtiqueta').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario

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


	//***************************** APROBAR ETIQUETA ***************************************
	$('button.btnEnviarTramite').click(function (event) {

		event.preventDefault();
		
		if($('input[name="opcionAprobar"]:checked').val()===undefined){
			mostrarMensaje('Favor elegir el destino del trámite', "FALLO");
			return;
		}


		
		if(datosDossier.es_clon!='t'){
    		if((datosDossier.mae_estado=='t') && (datosDossier.salud_estado=='t')){
    			borrarMensaje();
    		}
    		else{
    			mostrarMensaje('Para aprobar el certificado debe contar con las aprobaciones de MAE y SALUD', "FALLO");
    			return;
    		}
		}

		//Envia el formulario
		var form=$(this).parent().parent().parent();
		form.append("<input type='hidden' id='opcion_llamada' name='opcion_llamada' value='guardarAprobacionCumplimiento' />"); 
		form.append("<input type='hidden' id='condicion' name='condicion' value='"+$('input:radio[name=opcionAprobar]:checked').val()+"' />");
		form.append("<input type='hidden' id='observacion' name='observacion' value='"+$('#comentario').val()+"' />");
		form.attr('data-opcion', 'atenderFlujosPlaguicidas');
		mostrarMensaje("Generando archivo ... ",'FALLO');
    		
		ejecutarJson(form,new exitoFinalizarEtiqueta(), new falloFinalizarEtiqueta());
		
	});

	function exitoFinalizarEtiqueta(){
		this.ejecutar=function (msg){
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
			
		};
	}

	function falloFinalizarEtiqueta(){
		this.ejecutar=function (msg){
			mostrarMensaje('Error al guardar la evaluación de la etiqueta, revisar los requisitos previos','FALLO');
						
		};
	}


</script>

