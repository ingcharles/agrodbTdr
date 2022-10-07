<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';


	require_once 'abrirSolicitudInforme.php';

	$conexion = new Conexion();
	$ce=new ControladorEnsayoEficacia();

	$id_documento = $_POST['id'];
	$id_flujo = $_POST['idFlujo'];
	
	$id_fase = $_POST['opcion'];
	$id_tramite_flujo = $_POST['nombreOpcion'];
	$tramite_flujo=$ce->obtenerTramiteEE($conexion,$id_tramite_flujo);

	$observaciones=$ce->obtenerObservacionesDelDocumento($conexion,$id_documento,'IF');

?>

<div id="P0" class="pestania" style="display: block;">
   <label>Observaciones:</label>
   <textarea id="observacionAnterior" maxlength="2048">
		<?php
		echo $tramite_flujo['observacion'];
		?>

	</textarea>
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

		$('#detalleItem').find('h1').first().html("Subsanación del informe final : "+solicitud.id_expediente);
		//desactiva las entradas de datos
		$('section#detalleItem').find("input:not([type=hidden])").attr('disabled', 'disabled');	
		
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');

		
		
		$('section#detalleItem').find('button:not(.bsig,.bant,.btnVistaPrevia)').hide();

		$('input:checkbox[name="boolFinalizo"]').prop('disabled',false);
		$('section#detalleItem').find("input[type=hidden]").prop('disabled',false);
		
		//desactiva todos los botones
		var pestaniaReferente=$('#P1');
		pestaniaReferente.before($('#P0'));


		construirAnimacion(".pestania");

		activarObservaciones();

		activarActualizacion();


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
		var elemento=$('#frmFinalizarInforme');
		var btn=elemento.find('button#btnFinalizar')[0];
		elemento.append('<button id="btnSubsanar" type="button" class="guardar">Enviar subsanación</button>');

	}

	//*******************************************************
	$("body").off("click", "#btnSubsanar").on("click", "#btnSubsanar", function (event) {
		event.preventDefault();
		//verifica la aceptacion
		if($('#boolFinalizo').is(':checked')!=true){
			mostrarMensaje('Debe aceptar las condiciones para continuar','FALLO');
			return;
		}
		borrarMensaje();
		
		$('input:checkbox[name="boolFinalizo"]').prop('disabled',true);

		
		var param={opcion_llamada:'guardarSubsanacionesInforme',
			id_flujo:id_flujo,
			id_tramite_flujo:id_tramite_flujo,			
			id_documento:id_documento};

		$('section#detalleItem').find("input:enabled:not(.obsEficacia)").each(function(j) {

			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param["subsan_"+item.attr("id")]=item.val();
			}
		});

		$('section#detalleItem').find("input.obsEficacia:enabled").each(function(j) {

			if ($(this).attr("id") !== undefined) {
				item=$(this);
				param[item.attr("id")]=item.val();
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

		mostrarMensaje('Generando documentación...','');

		llamarServidor('ensayoEficacia','atenderFlujosInformes',param,resultadoFlujo);

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

	$('.bsig').click(function(event) {
		event.preventDefault();
		activarObservaciones();
	});

	$('.bant').click(function(event) {
		event.preventDefault();
		activarObservaciones();
	});


</script>


