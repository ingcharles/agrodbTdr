<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	
	require_once '../../clases/ControladorDossierPecuario.php';

	require_once 'abrirSolicitudDossier.php';


	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	$id_fase = $_POST['opcion'];
	$id_tramite = $_POST['nombreOpcion'];

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();
	$cp=new ControladorDossierPecuario();

	$tramiteFlujo=array();
	$id_fase=$ce->obtenerFaseDelFlujo($conexion,$id_flujo,'subsanarMetodo');
	$res=$cp->obtenerFlujosDeTramitesSolicitudDP($conexion,$identificador,$id_fase,$id_documento);	
	try{
		$tramiteFlujo=pg_fetch_assoc($res,0);
	}catch(Exception $e){}


	$observaciones=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,'DP')

?>

<div class="pestania" id="P0" style="display: block;">
	<label>Observaciones:</label>
	<textarea id="observacionAnterior" maxlength="2048">
		<?php
		echo $tramiteFlujo['observacion'];
        ?>

	</textarea>
</div>

<script type="text/javascript">

	var observaciones=<?php echo json_encode($observaciones); ?>;
	var id_flujo=<?php echo json_encode($id_flujo); ?>;
	var id_fase=<?php echo json_encode($id_fase); ?>;
	var id_documento=<?php echo json_encode($id_documento); ?>;
	var tramiteFlujo=<?php echo json_encode($tramiteFlujo); ?>;
	var idUsuario=<?php echo json_encode($idUsuario); ?>;
	var identificador=<?php echo json_encode($identificador); ?>;

	$("document").ready(function(){

		$('#detalleItem').find('h1').first().html("Subsanación de método analítico");
		//desactiva las entradas de datos
		$('section#detalleItem').find("input:not([type=hidden])").attr('disabled', 'disabled');
		
		$('section#detalleItem').find('textarea:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');		
		$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPrevia)').hide();

		$('.subsanarFarmacologico').show();
		$('.subsanarFarmacologico').prop('disabled', false);

		$('input:checkbox[name="boolAcepto"]').prop('disabled',false);
		//desactiva todos los botones

		var antes=$('#P1');
		antes.before($('#P0'));

		construirAnimacion(".pestania");
		

		activarObservaciones();

		activarActualización();

	});

	function activarObservaciones() {
		if(observaciones!=null && observaciones.length>0){
			for(var i in observaciones){
				var obs=observaciones[i];

				var el=$('#'+obs.elemento);
				if(el.prop("disabled") == true){

					el.prop('disabled', false);
					var div = el.parent();
					var id="obs_EP_"+obs.elemento;
					var str='<input type="text" value"" class="observacionRealizada" data-distribuir="no" id="'+id+'" name="'+id+'" disabled="disabled"/>';

					div.append(str);
					
					$('#'+id).val("Observacion: "+obs.observacion);
				}
			}
		}
	}

	function activarActualización(){
		var el=$('#frmFinalizarSolicitud9');
		var btn=el.find('button#btnFinalizar')[0];
		el.append('<button id="btnSubsanar" type="button" class="guardar">Enviar subsanación</button>');

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
		
		var param={opcion_llamada:'guardarSubsanacionesMetodo',
			id_flujo:id_flujo,
			id_tramite:tramiteFlujo.id_tramite,
			id_tramite_flujo:tramiteFlujo.id_tramite_flujo,
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
				param["subsan_"+item.attr("id")]=item.html();

			}

		});
		$('section#detalleItem').find("select:enabled").each(function(j) {
			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();

			}

		});


		llamarServidor('dossierPecuario','atenderFlujosPecuarios',param,resultadoFlujo);

	});

	function resultadoFlujo(items){
		if(items!=null){
			mostrarMensaje('Trámite enviado','EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
		}
		else{
			mostrarMensaje('Error al procesar la petición','FALLO');
		}
	}

	//*******************************************************

	$('.bsig').click(function(event) {
		event.preventDefault();
		activarObservaciones();
	});

	$('.bant').click(function(event) {
		event.preventDefault();
		activarObservaciones();
	});


   </script>

