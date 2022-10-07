<?php
session_start();

$id_documento = $_POST['id'];

	require_once 'abrirSolicitudProtocolo.php';


?>

<script type="text/javascript">

	
	var id_documento=<?php echo json_encode($id_documento); ?>;
	

		
	$("document").ready(function(){
		
		$('#detalleItem').find('h1').first().html("Protocolo factible de modificaciones");
		//desactiva las entradas de datos
		$('section#detalleItem').find('input:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('textarea:enabled', 'select:enabled').attr('disabled', 'disabled');
		$('section#detalleItem').find('select:enabled').attr('disabled', 'disabled');
		
		$('section#detalleItem').find('button:not(.bsig,.bant)').hide();

		
		$('input:checkbox[name="boolAcepto"]').prop('disabled', false);

		$('#verReporte').show();
		$('#verReporte').attr('href', protocolo.ruta);
		$('#verReporte').text('Ver pdf del protocolo');


		
		activarModificacion();

	});

	function activarModificacion() {
		var elemento = $('#frmFinalizarProtocolo');
		var btn = elemento.find('button#btnFinalizar')[0];
		elemento.append('<button id="btnModificar" type="button" class="guardar">Habilitar para modificaciones</button>');

	}

	$("body").off("click", "#btnModificar").on("click", "#btnModificar", function (event) {
		event.preventDefault();
		//verifica la aceptacion

		if ($('#boolAcepto').is(':checked') != true) {
			mostrarMensaje('Debe aceptar las condiciones para continuar', 'FALLO');
			return;
		}
		borrarMensaje();


		var param = {
			opcion_llamada: 'guardarAccionModificar',
			
			id_documento: id_documento
		};

		

		mostrarMensaje('Generando solicitud...', '');

		llamarServidor('ensayoEficacia', 'atenderFlujos', param, resultadoFlujo);

	});

	function resultadoFlujo(items) {
		alert(items);
		if ((items != null) && (items==true)) {
			mostrarMensaje('Trámite enviado', 'EXITO');
			$("#detalleItem").html('<div class="mensajeInicial">Trámite enviado.</div>');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);
		} else {
			mostrarMensaje('Favor revise el estado del trámite, la transacción no pudo ser compleada', 'FALLO');
		}
	}



</script>


