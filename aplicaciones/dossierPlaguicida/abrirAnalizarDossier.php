<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';


	require_once '../../clases/ControladorDossierPlaguicida.php';

	require_once 'abrirSolicitudDossier.php';


	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];

	$idUsuario= $_SESSION['usuario'];
	
	$id_tramite_flujo = $_POST['nombreOpcion'];

	$observaciones_ia=$_POST['observaciones_ia'];

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$cg=new ControladorDossierPlaguicida();

	$tramiteFlujo=$cg->obtenerTramiteFlujoDG($conexion,$id_tramite_flujo);
	$id_tramite=$tramiteFlujo['id_tramite'];

	$tipo_documento='DG';
	$doc=$ce->obtenerFormatoDocumento($conexion,$tipo_documento);
	$subsanados=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento);
	

	$datosDossier=$cg->obtenerSolicitud($conexion, $id_documento);

	//recupera los ingredientes
	$ias=$cg->obtenerIngredientesSolicitud($conexion, $id_documento);
	$subsanadosIa=array();
	foreach($ias as $item){
		$obsIa=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento,null,$item['id_solicitud_ia']);
		if(count($obsIa)>0){
			foreach($obsIa as $observacion){
				$observacion["ingrediente_activo"]=$item["ingrediente_activo"];
				$subsanadosIa[]=$observacion;
				
			}
		}
	}
	unset($ias);

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<div class="pestania" id="P0" style="display: block;">
   <div class="justificado">
      <label>Observaciones:</label>
      <textarea id="observacionAnterior" maxlength="2048">
		<?php
		echo $tramiteFlujo['observacion'];
        ?>

	</textarea>
   </div>
</div>

   <fieldset id="areaComentario">
      <legend>Comentarios</legend>
		
		<?php
			if($datosDossier['es_clon']!='t'){
				if(($datosDossier['ruta_dossier']!=null) && (strlen(trim($datosDossier['ruta_dossier']))>0) && (trim($datosDossier['ruta_dossier'])!='0'))
					echo '<a  href="'.$datosDossier['ruta_dossier'].'"	target="_blank">Dossier</a><br/>';
			}
			if(($datosDossier['ruta_solicitud']!=null) && (strlen(trim($datosDossier['ruta_solicitud']))>0)  && (trim($datosDossier['ruta_solicitud'])!='0'))
				echo '<a  href="'.$datosDossier['ruta_solicitud'].'"	target="_blank">Solicitud de dossier</a><br/>';
       ?>

      <div class="justificado">
         <label>Comentarios:</label>
         <textarea id="observacionSiguiente" class="habilitado" maxlength="2048"></textarea>
      </div>
   </fieldset>

<fieldset id="verObservacionLimite">
	<legend>Datos necesarios</legend>
	<div data-linea="1">
      <label>Justificación del retraso de su respuesta :</label>
		<textarea id="retraso" name="retraso" class="habilitado" data-distribuir='no' maxlength="512"></textarea>
            
   </div>

</fieldset>

   <script type="text/javascript">
	var doc=<?php echo json_encode($doc); ?>;
	
	var id_flujo=<?php echo json_encode($id_flujo); ?>;
	
	var id_documento=<?php echo json_encode($id_documento); ?>;
	var tramiteFlujo=<?php echo json_encode($tramiteFlujo); ?>;
	var idUsuario=<?php echo json_encode($idUsuario); ?>;
	var identificador=<?php echo json_encode($identificador); ?>;

	var id_tramite=<?php echo json_encode($id_tramite); ?>;
	var id_tramite_flujo=<?php echo json_encode($id_tramite_flujo); ?>;
	
	var subsanados=<?php echo json_encode($subsanados); ?>;
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;

   	
	var subsanadosIa=<?php echo json_encode($subsanadosIa); ?>;
	var observaciones_ia=<?php echo json_encode($observaciones_ia); ?>;
		

	$("document").ready(function(){

		$('#detalleItem').find('h1').first().html("Analisis de dossier plaguicida : "+solicitud.id_expediente);
		//desactiva las entradas de datos
		$('section#detalleItem').find("input:not([type=hidden])").attr('disabled', 'disabled');
		
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('button:not(.bsig,.bant,.verCampoObservar)').hide();

		$('section#detalleItem').find('div.ocultarOtros').hide();
		$('section#detalleItem').find('textarea.habilitado').prop('disabled', false);
		

		$('.noRevision').hide();


		//desactiva todos los botones
		if(tramiteFlujo==null || tramiteFlujo.observacion==null || tramiteFlujo.observacion.trim()==''){
			$('#P0').hide();
			$('#P0').removeClass('pestania');
		}
		else{
			var antes=$('#P1');
			antes.before($('#P0'));
		}


		pestaniaReferente=$('#P12');
		var elemento=pestaniaReferente.find('#btnFinalizar');
		elemento.before($('#areaComentario'));

		if(idUsuario!=identificador){

			visualizarObservaciones();

			var div=$("#P12");
			div.append('<button id="btnObservarProtocoloEE" type="button" class="mas guardarObservaciones" disabled="disabled" >Reportar Observaciones</button>');
			div.append('<button id="btnAprobarProtocoloEE" type="button" class="mas guardarObservaciones">Aprobar dossier</button>');
		}

		mostrarPuntosSubsanados();

		elemento.before($('#verObservacionLimite'));

		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();

		construirAnimacion(".pestania");

		//Desactivo los IA	
		$(".frmAbrirIa").each(function() {
			var form=$(this);		
			form.append("<input type='hidden' id='idFlujo' name='idFlujo' value='"+<?php echo json_encode($id_flujo); ?>+"' />");
			form.append("<input type='hidden' id='nombreOpcion' name='nombreOpcion' value='"+<?php echo json_encode($id_tramite_flujo); ?>+"' />");
			form.append("<input type='hidden' id='paginaRegreso' name='paginaRegreso' value='abrirAnalizarDossier' />");
			form.append("<input type='hidden' id='esDossierBloqueado' name='esDossierBloqueado' value='1' />");
		});

		var areaIA=$('#tablaIngredienteActivos');
		//recupera las observacioes anteriores
		if(subsanadosIa!=null){
			for(var i in subsanadosIa){
				$id="obs_GI_ant_"+subsanadosIa[i].id_tramite_observacion
				areaIA.append('<input type="text" id="'+$id+'" name="'+$id+'" data-distribuir="no" class="observacionRealizadaVer" value="'+subsanadosIa[i].ingrediente_activo+'('+subsanadosIa[i].elemento+") "+subsanadosIa[i].observacion+'" disabled="disabled"/>');
			}
		}
		//recupera las observaciones recientes para habilitar el boton observado
		
		var iaObservaciones=JSON.parse(observaciones_ia);
		if(iaObservaciones!=null){
			for(var i in iaObservaciones){
				areaIA.append('<input type="hidden" id="obs_GI_temporal" name="obs_GI_temporal"  class="observacionRealizada" value="1"/>');
				break;
			}
		}
		
		distribuirLineas();

		gestionarBotonesDesicion();

	});
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

			id="obs_EP_"+ids;
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
			$('#btnObservarProtocoloEE').removeAttr('disabled');
			$('#btnAprobarProtocoloEE').attr("disabled", "disabled");
		}
		else{
			$('#btnObservarProtocoloEE').attr("disabled", "disabled");
			$('#btnAprobarProtocoloEE').removeAttr('disabled');
		}
	}

	//*******************************************************
	$("body").off("click", ".guardarObservaciones").on("click", ".guardarObservaciones", function (event) {
		event.preventDefault();
		

		if(sobreTiempo){
			if(!esNoNuloEsteCampo("#retraso"))
			{
				mostrarMensaje("Favor ingrese su justificación","FALLO");
				return;
			}
		}

		var param={opcion_llamada:'guardarObservacionesSolicitud',
			id_flujo:id_flujo,
			id_tramite:id_tramite,
			id_tramite_flujo:id_tramite_flujo,
			id_documento:id_documento,
			retraso:$('#retraso').val(),
			observacion:$('#observacionSiguiente').val()

		};
		if($(this).attr('id')=="btnObservarProtocoloEE"){
			$('#detalleItem').find("input.observacionRealizada").each(function(j) {
				if ($(this).attr("id") !== undefined) {
					item=$(this);
					param[item.attr("id")]=item.val();

				}

			});
		}
		mostrarMensaje("Enviando evaluación...","");
		llamarServidor('dossierPlaguicida','atenderFlujosPlaguicidas',param,resultadoFlujo);

	});

	function resultadoFlujo(items){
		if(items!=null){
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
		}else{
			mostrarMensaje('Errores al enviar la transacçión y no pudo ser compleada','FALLO');
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
				
				var id="obs_antes_EP_"+obs.elemento+"_"+obs.id_tramite_flujo;
				var str='<input type="text" value"" class="observacionRealizadaVer" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';

				div.append(str);

				$('#'+id).val("Observacion: "+obs.observacion);
			}
		}
	}


	//******************************* COLOCAR ELEMENTOS PARA OBSERVAR

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
		visualizarObservaciones();
	});


   </script>


