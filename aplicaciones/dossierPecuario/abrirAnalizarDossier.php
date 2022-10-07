<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	
	
	require_once '../../clases/ControladorDossierPecuario.php';

	require_once 'abrirSolicitudDossier.php';
    

	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	$id_tramite_flujo = $_POST['nombreOpcion'];

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$cp=new ControladorDossierPecuario();

	$tramiteFlujo=$cp->obtenerTramiteFlujoDP($conexion,$id_tramite_flujo);
	$id_tramite=$tramiteFlujo['id_tramite'];

	$tipo_documento='DP';
	$doc=$ce->obtenerFormatoDocumento($conexion,$tipo_documento);
	$subsanados=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento);

	$observaciones=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,$tipo_documento);

	$fechaActual=new DateTime();
	$fechaTiempo=new DateTime($tramiteFlujo['fecha_fin']);
	$sobreTiempo=false;
	if($fechaActual>$fechaTiempo)
		$sobreTiempo=true;

?>

<div class="pestania" id="P0" style="display: block;">
	<fieldset>
		<legend>Resultado de revisión</legend>
		
		<label>Observaciones:</label>
		<div data-linea="100">    		
    		<textarea id="observacionAnterior" maxlength="2048"><?php echo $tramiteFlujo['observacion']; ?></textarea>
		</div>
	</fieldset>
</div>

<fieldset id="areaComentario">
	<legend>Declaración de venta y comentarios</legend>
	<div data-linea="1">
				<label for="declaracion_venta">Declaración de venta</label>
				<select name="declaracion_venta" id="declaracion_venta" required>
					<option value="">Seleccione....</option>
					<?php
					$declaracionVenta=$ce->listarElementosCatalogoEx($conexion,'PC_DE_VE');
					foreach ($declaracionVenta as $key=>$item){
						if(strtoupper($item['codigo']) == strtoupper($datosGenerales['declaracion_venta'])){
							echo '<option value="' . $item['codigo'] . '" selected="selected" >' . $item['nombre'] . '</option>';
						}else{
							echo '<option value="' . $item['codigo'] . '" >' . $item['nombre'] . '</option>';
						}
					}
                    ?>
				</select>
			</div>
	<div class="justificado">
		<label>Comentarios:</label>
		<textarea id="observacionSiguiente" class="habilitado" maxlength="2048"></textarea>
	</div>
	
	<!-- Documento adjunto -->
	<input type="hidden" id="idTramiteFlujo" name="idTramiteFlujo" value="<?php echo $tramiteFlujo['id_tramite_flujo'];?>" />
	
	<div data-linea="14">
    	<label for="documento">Documento: </label>
    	<input type="file" class="archivoInforme" id="informeRevision" name="informeRevision" accept="application/pdf" />
    	<input type="hidden" class="rutaArchivoInforme" id="archivoRevision" name="archivoRevision" name="archivoRevision" value="0" />						
    	<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    	<button type="button" id="btnArchivoRevision" name="btnArchivoRevision" data-rutaCarga="aplicaciones/dossierPecuario/informes" >Subir archivo</button>						
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
	var observaciones=<?php echo json_encode($observaciones); ?>;
	var id_flujo=<?php echo json_encode($id_flujo); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var id_documento=<?php echo json_encode($id_documento); ?>;
	var tramiteFlujo=<?php echo json_encode($tramiteFlujo); ?>;
	var idUsuario=<?php echo json_encode($idUsuario); ?>;
	var identificador=<?php echo json_encode($identificador); ?>;
	
	var id_tramite=<?php echo json_encode($id_tramite); ?>;
	var id_tramite_flujo=<?php echo json_encode($id_tramite_flujo); ?>;

	var subsanados=<?php echo json_encode($subsanados); ?>;
	var sobreTiempo=<?php echo json_encode($sobreTiempo); ?>;

	$("document").ready(function(){

		$('#detalleItem').find('h1').first().html("Analisis de dossier pecuario : "+solicitud.id_expediente);
		//desactiva las entradas de datos
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');		
		$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPreviaDossier)').hide();

		$('section#detalleItem').find('div.ocultarOtros').hide();
		$('section#detalleItem').find('textarea.habilitado').prop('disabled', false);
		$('#declaracion_venta').prop('disabled', false);
		
		$('.noRevision').hide();

		
		//desactiva todos los botones

		var antes=$('#P1');
		antes.before($('#P0'));

		pestaniaReferente=$('#P10');
		var elemento=pestaniaReferente.find('#btnFinalizar');
		elemento.before($('#areaComentario'));

		mostrarPuntosSubsanados();

		if(idUsuario!=identificador){
			
			visualizarObservaciones();

			var div=$("#P10");
			div.append('<button id="btnObservarProtocoloEE" type="button" class="mas guardarObservaciones" disabled="disabled" >Reportar Observaciones</button>');
			div.append('<button id="btnAprobarProtocoloEE" type="button" class="mas guardarObservaciones">Aprobar dossier</button>');
		}

		
		elemento.before($('#verObservacionLimite'));

		if(sobreTiempo)
			$('#verObservacionLimite').show();
		else
			$('#verObservacionLimite').hide();

		
		construirAnimacion(".pestania");
		distribuirLineas();

		//Ocultar formulario de Origen del Producto
		$('#frmProcedencia').hide();	
		$('#btnArchivoRevision').remove('disabled');
		$('#btnArchivoRevision').show();	

		$('#informeRevision').removeAttr('disabled');
		$('#informeRevision').show();	
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
	//Campo para observaciones
	$("body").off("click", ".observar").on("click", ".observar", function (event) {
		event.preventDefault();
		var div = $(this).parent();

		var ids=$(this).data("id");
		var id='';
		if(ids!=null || ids!='undefined' || ids!=''){
			
			id="obs_EP_"+ids;
		}
		div.append('<input type="text" value"" class="observacionRealizada" data-distribuir="no" id="'+id+'" name="'+id+'" maxlength="64"/>');
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
	//Función de guardado de respuesta
	$("body").off("click", ".guardarObservaciones").on("click", ".guardarObservaciones", function (event) {
		event.preventDefault();
		if($('#declaracion_venta').val()==""){
			mostrarMensaje('Es obligatorio elegir la declaración de venta','FALLO');
			return;
		}

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
			observacion:$('#observacionSiguiente').val(),
			retraso:$('#retraso').val(),
			declaracion_venta:$('#declaracion_venta').val(),
			archivoInforme:$('#archivoRevision').val()
		};
		if($(this).attr('id')=="btnObservarProtocoloEE"){
			$('#detalleItem').find("input.observacionRealizada").each(function(j) {
				if ($(this).attr("id") !== undefined) {
					item=$(this);
					param[item.attr("id")]=item.val();

				}

			});
		}
		mostrarMensaje("Preparando documentación, favor espere...","");
		llamarServidor('dossierPecuario','atenderFlujosPecuarios',param,resultadoFlujo);

	});

	function resultadoFlujo(items){
		if(items!=null){
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
		}else{
			mostrarMensaje('Errores al enviar la transacción y no pudo ser compleada','FALLO');
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


	//Subir archivo adjunto revisión
	$('#btnArchivoRevision').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivoInforme");
        var rutaArchivo = boton.parent().find(".rutaArchivoInforme");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || $("#id_solicitud").val() != "") {

            subirArchivo(
                archivo
                , ($("#id_solicitud").val()+"-"+$("#idTramiteFlujo").val()).replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
  });
	  
   </script>