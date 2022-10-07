<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';

	require_once 'abrirSolicitudDossier.php';

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$cp=new ControladorDossierPecuario();

	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];

	//Busca si hay un trámite en curso de subsanación
	$id_fase=$ce->obtenerFaseDelFlujo($conexion,$id_flujo,'subsanarDossier');

	$resultado=$cp->obtenerFlujosDeTramitesSolicitudDP($conexion,$identificador,$id_fase,$id_documento);

	$tramite_flujo=pg_fetch_assoc($resultado,0);
	$id_tramite_flujo = $tramite_flujo['id_tramite_flujo'];

	$observaciones=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,'DP');

	$informe = pg_fetch_assoc($cp->buscarInformeTecnico($conexion, $id_documento));

?>

<div class="pestania" id="P0" style="display: block;">
	<fieldset>
		<legend>Resultado de revisión</legend>
		
		<label>Observaciones:</label>
		<div data-linea="100">    		
    		<textarea id="observacionAnterior" maxlength="2048"><?php echo $tramite_flujo['observacion']; ?></textarea>
		</div>

		<div data-linea="101">
			<label>Archivo adjunto</label> <?php echo ($informe['ruta_archivo']=='0'? '<span class="alerta">No hay ningún archivo adjunto</span>': $informe['ruta_archivo']==''? '<span class="alerta">No hay ningún archivo adjunto</span>' : '<a href='.$informe['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
		</div>
	</fieldset>
</div>

<script type="text/javascript">

		var observaciones=<?php echo json_encode($observaciones); ?>;
		var id_flujo=<?php echo json_encode($id_flujo); ?>;
		var id_fase=<?php echo json_encode($id_fase); ?>;
		var id_documento=<?php echo json_encode($id_documento); ?>;
		var id_tramite_flujo=<?php echo json_encode($id_tramite_flujo); ?>;
		var idUsuario=<?php echo json_encode($idUsuario); ?>;
		var identificador=<?php echo json_encode($identificador); ?>;

	$("document").ready(function(){

		$('#detalleItem').find('h1').first().html("Subsanación del dossier : "+solicitud.id_expediente);
		//desactiva las entradas de datos
		
		$('section#detalleItem').find("input:not([type=hidden])").attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');

		
		
		$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPrevia)').hide();

		
		$('input:checkbox[name="boolAcepto"]').prop('disabled',false);

		//desactiva todos los botones
		var pestaniaReferente=$('#P1');
		pestaniaReferente.before($('#P0'));

		construirAnimacion(".pestania");

		activarObservaciones();

		activarActualizacion();

		distribuirLineas();
	});

	function activarObservaciones() {
		if(observaciones!=null && observaciones.length>0){
			for(var i in observaciones){
				var obs=observaciones[i];
				var elemento=$('#'+obs.elemento);
				var div = elemento.parent();
				var id="obs_EP_"+obs.elemento+"_"+obs.id_tramite_flujo;
				var str='<input type="text" value"" class="observacionRealizadaVer" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';
				div.append(str);
				$('#'+id).val("Observacion: "+obs.observacion);

				switch(obs.elemento_tipo){
					case null:
					case '':
						if(elemento.prop("disabled") == true){
							elemento.prop('disabled', false);
							elemento.off('click');					//Para impedir modificar el resto del documento al click
							elemento.off('change');					//Para impedir modificar el resto del documento al cambio
						}
					break;
					case 'cambio':
						if(elemento.prop("disabled") == true){
							elemento.prop('disabled', false);
							elemento.off('click');					//Para impedir modificar el resto del documento al click
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

	function activarActualizacion(){
		var elemento=$('#frmFinalizarSolicitud9');
		var btn=elemento.find('button#btnFinalizar')[0];
		elemento.append('<button id="btnSubsanar" type="button" class="guardar">Enviar subsanación</button>');

	}

	//*******************************************************
	$("body").off("click", "#btnSubsanar").on("click", "#btnSubsanar", function (event) {
		event.preventDefault();
		//verifica la aceptacion
		
		if($('#boolAcepto').is(':checked')!=true){
			mostrarMensaje('Debe aceptar las condiciones para continuar','FALLO');
			return;
		}
		borrarMensaje();
		$('input:checkbox[name="boolAcepto"]').prop('disabled',true);
	

		var param={opcion_llamada:'guardarSubsanacionesSolicitud',
			id_flujo:id_flujo,
			id_tramite_flujo:id_tramite_flujo,
			
			id_documento:id_documento};

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

		mostrarMensaje('Ejecutando su acción...','');

		llamarServidor('dossierPecuario','atenderFlujosPecuarios',param,resultadoFlujo);

	});

	function resultadoFlujo(items){
		if(items!=null){
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
		}else{
			mostrarMensaje('Favor revise si todos los puntos fueron subsanados, la transacción no pudo ser compleada','FALLO');
		}
	}

	//*******************************************************
/*
	$('.bsig').click(function(event) {
		event.preventDefault();
		//activarObservaciones();
	});

	$('.bant').click(function(event) {
		event.preventDefault();
		//activarObservaciones();
	});*/


</script>


