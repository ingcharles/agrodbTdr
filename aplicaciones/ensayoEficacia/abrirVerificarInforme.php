<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';

	require_once 'abrirSolicitudInforme.php';

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$tipo_documento='IF';
	$doc=$ce->obtenerFormatoDocumento($conexion,$tipo_documento);

	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];

	$id_tramite_flujo = $_POST['nombreOpcion'];
	
	$tramiteFlujo=$ce->obtenerFlujoDeTramiteEE($conexion,$id_tramite_flujo);

	//verifica si ya fue subsanado
	$subsanados=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,'IF');

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<div id="P0" class="pestania" style="display: block;">
	<div class="justificado">
		<label>Observaciones:</label>
		<textarea id="observacionAnterior" maxlength="2048">
			<?php
			echo htmlspecialchars( $tramiteFlujo['observacion']);
			?>

		</textarea>
	</div>
</div>

<fieldset id="areaComentario">
	<legend>Comentarios</legend>
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
	var id_tramite=<?php echo json_encode($id_tramite); ?>;
	var id_tramite_flujo=<?php echo json_encode($id_tramite_flujo); ?>;

	var idUsuario=<?php echo json_encode($idUsuario); ?>;
	var identificador=<?php echo json_encode($identificador); ?>;
	var subsanados=<?php echo json_encode($subsanados); ?>;

	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;


	$("document").ready(function () {

		$('#detalleItem').find('h1').first().html("Análisis del informe : "+solicitud.id_expediente);

		//deshabilita casillas
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');

		
		$('section#detalleItem').find('button:not(.bsig,.bant)').hide();
		$('section#detalleItem').find('div.ocultarOtros').hide();
		$('section#detalleItem').find('textarea.habilitado').prop('disabled', false);

		var pestaniaReferente=$('#P1');
		pestaniaReferente.before($('#P0'));


		
		construirAnimacion(".pestania");

		pestaniaReferente=$('#P5');
		var elemento=pestaniaReferente.find('#btnFinalizar');
		elemento.before($('#areaComentario'));

		

		if(idUsuario!=identificador){
			
			visualizarObservaciones();

			var div=$("#P5");
			div.append('<button id="btnObservarProtocoloEE" type="button" class="mas guardarObservaciones" disabled="disabled" >Reportar Observaciones</button>');
			div.append('<button id="btnAprobarProtocoloEE" type="button" class="mas guardarObservaciones">Aprobar Informe</button>');
			
		}
		

		mostrarPuntosSubsanados();

		//sobretiempo
		elemento.before($('#verObservacionLimite'));
		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();

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
		
		var param={opcion_llamada:'guardarObservacionesInforme',
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
		llamarServidor('ensayoEficacia','atenderFlujosInformes',param,resultadoFlujo);

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

	//******************************* ACTIVAR OBSERVACIONES ************************

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
